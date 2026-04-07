<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentYearLevelHistory extends Model
{
    use HasFactory;

    protected $table = 'student_year_level_history';

    protected $fillable = [
        'student_id',
        'academic_year_id',
        'previous_year_level',
        'new_year_level',
        'promotion_type',
        'reason',
        'processed_by',
        'effective_date',
    ];

    protected $casts = [
        'effective_date' => 'datetime',
        'previous_year_level' => 'integer',
        'new_year_level' => 'integer',
    ];

        /**
     * Get the student associated with this promotion history
     */
    public function student()
    {
        return $this->belongsTo(RoleAccount::class, 'student_id', 'student_id');
    }

    /**
     * Get the academic year associated with this promotion history
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Get promotion type display name
     */
    public function getPromotionTypeDisplayAttribute()
    {
        return match($this->promotion_type) {
            'automatic' => 'Automatic Promotion',
            'manual' => 'Manual Promotion',
            'graduation' => 'Graduation',
            'repeat' => 'Repeat Year',
            default => 'Unknown'
        };
    }

    /**
     * Scope to get history for a specific student
     */
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Scope to get history for a specific academic year
     */
    public function scopeForAcademicYear($query, $academicYearId)
    {
        return $query->where('academic_year_id', $academicYearId);
    }
}
