<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'users_id',
        'student_number',
        'course_id',
        'year',
        'department_id',
        'academic_id',
        'nfc_uid',
        'is_archived',
        'has_violations',
        'is_graduated',
        'is_uniwide',
        'locked_academic_years',
    ];

    protected $casts = [
        'is_archived'                    => 'boolean',
        'is_uniwide'                     => 'boolean',
        'locked_academic_years'          => 'array',
        'has_violations'                 => 'boolean',
        'is_graduated'                   => 'boolean',
    ];

    // ─── Relationships ─────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function courses()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_id', 'id');
    }

    public function nfcTaps()
    {
        return $this->hasMany(NfcTap::class, 'student_id', 'id');
    }

    // ─── Business Logic ────────────────────────────────────────────────────────

    public function hasBlockingViolations(): bool
    {
        return (bool) $this->has_violations;
    }
}
