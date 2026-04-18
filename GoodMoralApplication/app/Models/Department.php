<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $fillable = [
        'department_name',
        'department_code',
        'logo',
        'color',
        'label',
        'is_undergraduate',
    ];

    protected $casts = [
        'is_undergraduate' => 'boolean',
    ];

    /**
     * Get all department codes from the database (cached for 1 hour).
     *
     * @return array<string>
     */
    public static function allCodes(): array
    {
        return Cache::remember('department_codes', 3600, function () {
            return static::orderBy('department_code')->pluck('department_code')->toArray();
        });
    }

    /**
     * Get department codes that participate in violation tracking.
     * Only includes departments marked as undergraduate.
     *
     * @return array<string>
     */
    public static function violationCodes(): array
    {
        return Cache::remember('violation_department_codes', 3600, function () {
            return static::where('is_undergraduate', true)
                ->orderBy('department_code')
                ->pluck('department_code')
                ->toArray();
        });
    }

    /**
     * Get the department_name for a given code, returning both code and name as
     * possible search variants (for records that may store either form).
     *
     * @return array<string>
     */
    public static function possibleNames(string $code): array
    {
        $name = Cache::remember("dept_name_{$code}", 3600, function () use ($code) {
            return static::where('department_code', $code)->value('department_name');
        });

        $variants = [$code];
        if ($name) {
            $variants[] = $name;
            $variants[] = strtoupper($name);
        }
        return array_unique($variants);
    }

    /**
     * Clear cached department data (call after create/update/delete).
     */
    public static function clearCache(): void
    {
        Cache::forget('department_codes');
        Cache::forget('violation_department_codes');

        // Clear individual name and display caches
        foreach (static::pluck('department_code') as $code) {
            Cache::forget("dept_name_{$code}");
            Cache::forget("dept_display_{$code}");
        }
    }

    /**
     * Find a department ID by its code. Returns null if not found.
     */
    public static function findIdByCode(?string $code): ?int
    {
        if (!$code) {
            return null;
        }

        return static::where('department_code', $code)->value('id');
    }

    /**
     * Get role accounts in this department
     */
    public function roleAccounts()
    {
        return $this->hasMany(RoleAccount::class, 'department_id');
    }

    /**
     * Get organizations in this department
     */
    public function organizations()
    {
        return $this->hasMany(Organization::class, 'department_id');
    }

    /**
     * Get courses in this department
     */
    public function courses()
    {
        return $this->hasMany(Course::class, 'department_id');
    }
}
