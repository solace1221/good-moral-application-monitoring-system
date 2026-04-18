<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_code',
        'course_name',
        'department',
        'department_id',
        'department_name',
        'sort_order',
    ];

    /**
     * Auto-eager-load the department relationship.
     */
    protected $with = ['departmentRecord'];

    /**
     * Resolve department code from FK, falling back to string column.
     */
    public function getDepartmentAttribute()
    {
        return $this->departmentRecord?->department_code
            ?? $this->attributes['department']
            ?? null;
    }

    /**
     * Get the department model via FK
     */
    public function departmentRecord()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * Legacy: get the department model via department code string
     */
    public function departmentModel()
    {
        return $this->belongsTo(Department::class, 'department', 'department_code');
    }

    /**
     * Scope to get courses by department code
     */
    public function scopeByDepartmentCode($query, $departmentCode)
    {
        return $query->where('department', $departmentCode);
    }

    /**
     * Scope to get courses by department code (alias)
     */
    public function scopeByDepartment($query, $departmentCode)
    {
        return $query->where('department', $departmentCode);
    }

    /**
     * Get courses ordered by course_name
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('course_name');
    }

    /**
     * Get all courses grouped by department code
     */
    public static function getByDepartment()
    {
        return self::ordered()
            ->get()
            ->groupBy('department')
            ->map(function ($courses) {
                return $courses->pluck('course_name', 'course_code');
            });
    }

    /**
     * Get all courses as flat array (code => name)
     */
    public static function getAllCourses()
    {
        return self::ordered()
            ->pluck('course_name', 'course_code')
            ->toArray();
    }

    /**
     * Get departments with their full names
     */
    public static function getDepartments()
    {
        return Department::orderBy('department_code')
            ->pluck('department_name', 'department_code')
            ->toArray();
    }
}
