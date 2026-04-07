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
    'course',
    'password',
    'status',
    'student_id',
    'account_type',
    'year_level',
    'organization',
    'position',
    'designation_id',
    'position_id',
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
}
