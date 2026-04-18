<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentRegistration extends Authenticatable
{
  use HasFactory;

  protected $table = 'student_registrations';

  protected $fillable = [
    'fname',
    'mname',
    'lname',
    'extension',
    'gender',
    'email',
    'department',
    'department_id',
    'course_id',
    'course',
    'password',
    'status',
    'student_id',
    'account_type',
    'year_level',
    'organization',
    'position',
  ];


  protected $hidden = [
    'password',
  ];

  public function setFnameAttribute($value)
  {
    $this->attributes['fname'] = strtoupper($value);
  }

  public function setMnameAttribute($value)
  {
    $this->attributes['mname'] = $value ? strtoupper($value) : null;
  }

  public function setLnameAttribute($value)
  {
    $this->attributes['lname'] = strtoupper($value);
  }

  public function setExtensionAttribute($value)
  {
    $this->attributes['extension'] = $value ? strtoupper($value) : null;
  }

  public function setOrganizationAttribute($value)
  {
    $this->attributes['organization'] = $value ? strtoupper($value) : null;
  }

  public function setPositionAttribute($value)
  {
    $this->attributes['position'] = $value ? strtoupper($value) : null;
  }

  // =========================================================
  // Relationships
  // =========================================================

  /**
   * Get the department via FK.
   */
  public function departmentRecord()
  {
    return $this->belongsTo(Department::class, 'department_id');
  }

  /**
   * Resolve department code from FK, falling back to string column.
   */
  public function getDepartmentAttribute()
  {
    return $this->departmentRecord?->department_code
      ?? $this->attributes['department']
      ?? null;
  }

  public function courseRecord()
  {
    return $this->belongsTo(Course::class, 'course_id');
  }
}
