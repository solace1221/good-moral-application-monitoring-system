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
        'department_id',
        'coordinator_id',
    ];

    /**
     * Get the department that owns the course
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * Scope to get courses by department code
     */
    public function scopeByDepartmentCode($query, $departmentCode)
    {
        return $query->whereHas('department', function($q) use ($departmentCode) {
            $q->where('department_code', $departmentCode);
        });
    }

    /**
     * Scope to get courses by department ID
     */
    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    /**
     * Get courses ordered by course_name
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('course_name');
    }

    /**
     * Get the coordinator (User) for this course
     */
    public function coordinator()
    {
        return $this->belongsTo(User::class, 'coordinator_id', 'id');
    }

    /**
     * Get all courses grouped by department code
     */
    public static function getByDepartment()
    {
        return self::with('department')
            ->ordered()
            ->get()
            ->groupBy(function($course) {
                return $course->department ? $course->department->department_code : 'Unknown';
            })
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
