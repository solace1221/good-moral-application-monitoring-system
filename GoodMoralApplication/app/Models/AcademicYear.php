<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AcademicYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'year_name',
        'start_date', 
        'end_date',
        'is_current',
        'year_level_promotion_active',
        'promotion_triggered_at',
        'promotion_triggered_by',
        'notes',
        
        // Legacy compatibility
        'academic_year',
        'start_year',
        'end_year',
        'is_active',
        'description',
        'sort_order',
    ];

    protected $casts = [
        'is_current' => 'boolean',
        'year_level_promotion_active' => 'boolean',
        'promotion_triggered_at' => 'datetime',
        'start_date' => 'date',
        'end_date' => 'date',
        
        // Legacy compatibility
        'is_active' => 'boolean',
        'start_year' => 'integer',
        'end_year' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get the display name for the academic year (handles backward compatibility)
     */
    public function getDisplayNameAttribute()
    {
        return $this->year_name ?: $this->academic_year ?: 'Unnamed Academic Year';
    }

    /**
     * Get the effective year name (backward compatibility accessor)
     */
    public function getYearNameAttribute($value)
    {
        return $value ?: $this->attributes['academic_year'] ?? null;
    }

    /**
     * Scope to get only active academic years
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get the current academic year
     */
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    /**
     * Get academic years ordered by sort_order and start_year
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('start_year', 'desc');
    }

    /**
     * Get all active academic years as an array
     */
    public static function getActiveYears()
    {
        return self::active()->ordered()->pluck('academic_year')->toArray();
    }

    /**
     * Get the current academic year
     */
    public static function getCurrentYear()
    {
        return self::current()->first();
    }

    /**
     * Set this academic year as current (and unset others)
     */
    public function setCurrent()
    {
        // Unset all other current academic years
        self::where('is_current', true)->update(['is_current' => false]);
        
        // Set this one as current
        $this->update(['is_current' => true]);
    }

    /**
     * Generate academic year string from start and end years
     */
    public static function generateAcademicYear($startYear, $endYear)
    {
        return $startYear . '-' . $endYear;
    }

    /**
     * Validate academic year format
     */
    public static function validateAcademicYear($academicYear)
    {
        if (!preg_match('/^\d{4}-\d{4}$/', $academicYear)) {
            return false;
        }

        $parts = explode('-', $academicYear);
        $startYear = (int) $parts[0];
        $endYear = (int) $parts[1];

        return $endYear === $startYear + 1;
    }

    /**
     * Create a new academic year
     */
    public static function createAcademicYear($startYear, $endYear, $description = null)
    {
        $academicYear = self::generateAcademicYear($startYear, $endYear);

        if (!self::validateAcademicYear($academicYear)) {
            throw new \InvalidArgumentException('Invalid academic year format. End year must be exactly one year after start year.');
        }

        if (self::where('academic_year', $academicYear)->exists()) {
            throw new \InvalidArgumentException('Academic year already exists.');
        }

        $maxSortOrder = self::max('sort_order') ?? 0;

        return self::create([
            'academic_year' => $academicYear,
            'start_year' => $startYear,
            'end_year' => $endYear,
            'is_active' => true,
            'is_current' => false,
            'description' => $description,
            'sort_order' => $maxSortOrder + 1,
        ]);
    }

    /**
     * Start new academic year and trigger year level promotions
     */
    public function triggerNewAcademicYear($triggeredBy)
    {
        DB::transaction(function () use ($triggeredBy) {
            // Set this as current academic year
            self::where('is_current', true)->update(['is_current' => false]);
            $this->update(['is_current' => true]);

            // Activate year level promotion
            $this->update([
                'year_level_promotion_active' => true,
                'promotion_triggered_at' => now(),
                'promotion_triggered_by' => $triggeredBy
            ]);

            // Log this action
            Log::info('New academic year triggered', [
                'academic_year' => $this->year_name,
                'triggered_by' => $triggeredBy,
                'triggered_at' => now()
            ]);
        });
    }

    /**
     * Get students eligible for year level promotion
     */
    public function getEligibleStudents()
    {
        return RoleAccount::where('account_type', 'student')
            ->where('year_level', '<', 4) // Don't promote 4th year students automatically
            ->where('status', '1') // Active students only
            ->get();
    }

    /**
     * Process automatic year level promotions
     */
    public function processYearLevelPromotions($processedBy)
    {
        if (!$this->year_level_promotion_active) {
            throw new \Exception('Year level promotion is not active for this academic year.');
        }

        $promotedCount = 0;
        $eligibleStudents = $this->getEligibleStudents();

        DB::transaction(function () use ($eligibleStudents, $processedBy, &$promotedCount) {
            foreach ($eligibleStudents as $student) {
                $this->promoteStudent($student, $processedBy);
                $promotedCount++;
            }
        });

        return $promotedCount;
    }

    /**
     * Promote individual student
     */
    public function promoteStudent($student, $processedBy, $reason = 'Automatic promotion')
    {
        $oldYearLevel = $student->year_level;
        $newYearLevel = $oldYearLevel + 1;

        // Update student year level
        $student->update(['year_level' => $newYearLevel]);

        // Record in history
        StudentYearLevelHistory::create([
            'student_id' => $student->student_id,
            'academic_year_id' => $this->id,
            'previous_year_level' => $oldYearLevel,
            'new_year_level' => $newYearLevel,
            'promotion_type' => 'automatic',
            'reason' => $reason,
            'processed_by' => $processedBy,
            'effective_date' => now()
        ]);
    }

    /**
     * Check if promotion is active for current academic year
     */
    public static function isPromotionActive()
    {
        $current = self::current()->first();
        return $current && $current->year_level_promotion_active;
    }

    /**
     * Get year level history for a student
     */
    public static function getStudentYearLevelHistory($studentId)
    {
        return StudentYearLevelHistory::where('student_id', $studentId)
            ->with('academicYear')
            ->orderBy('effective_date', 'desc')
            ->get();
    }

    // =========================================================
    // Relationships
    // =========================================================

    /**
     * Get students for this academic year
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'academic_id', 'id');
    }
}
