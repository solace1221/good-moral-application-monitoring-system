<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecOSAApplication extends Model
{
  use HasFactory;

  protected $table = 'sec_osa_applications'; // ✅ Explicit table name

  protected $fillable = [
    'reference_number',
    'number_of_copies',
    'student_id',
    'department',
    'department_id',
    'reason',
    'fullname',
    'course_completed',  // New field
    'graduation_date',    // New field
    'is_undergraduate',   // New field
    'last_course_year_level', // New field
    'last_semester_sy',   // New field
    'status',
  ];

  protected $casts = [
    'graduation_date' => 'date',
    'is_undergraduate' => 'boolean',
    'number_of_copies' => 'integer',
  ];

  public function receipt()
  {
    return $this->hasOne(Receipt::class, 'reference_num', 'reference_number');
  }
  public function student()
  {
    return $this->belongsTo(RoleAccount::class, 'student_id', 'student_id');
  }

  public function departmentRecord()
  {
    return $this->belongsTo(Department::class, 'department_id');
  }

  public function getDepartmentAttribute()
  {
    return $this->departmentRecord?->department_code
      ?? $this->attributes['department']
      ?? null;
  }
}
