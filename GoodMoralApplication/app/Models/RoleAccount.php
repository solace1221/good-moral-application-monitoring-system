<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use App\Notifications\CustomResetPasswordNotification;

class RoleAccount extends Authenticatable
{
  use HasFactory, Notifiable, CanResetPassword;

  protected $table = 'role_account'; // Specify the table name explicitly
  protected $fillable = [
    'email',
    'password',
    'account_type',
    'student_id',
    'department',
    'course',
    'year_level',
    'fullname',
    'mname',
    'extension',
    'gender',
    'organization',
    'position',
    'status',
    'is_graduating',
    'graduation_date',
    'graduated_at',
  ];

  protected $hidden = [
    'password', // Hide the password from being returned
  ];

  protected $casts = [
    'is_graduating' => 'boolean',
    'graduation_date' => 'date',
    'graduated_at' => 'datetime',
  ];

  public function setfullnameAttribute($value)
  {
    $this->attributes['fullname'] = strtoupper($value);
  }

  public function setMnameAttribute($value)
  {
    $this->attributes['mname'] = $value ? strtoupper($value) : null;
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
  public function studentInfo()
  {
    return $this->belongsTo(StudentRegistration::class, 'student_id', 'student_id',);
  }

  /**
   * Get the good moral applications for this student.
   */
  public function goodMoralApplications()
  {
    return $this->hasMany(GoodMoralApplication::class, 'student_id', 'student_id');
  }

  /**
   * Send the password reset notification.
   *
   * @param  string  $token
   * @return void
   */
  public function sendPasswordResetNotification($token)
  {
    $this->notify(new CustomResetPasswordNotification($token));
  }

  /**
   * Get the year level for the student, randomizing if not set
   * 
   * @return string
   */
  public function getYearLevelAttribute($value)
  {
    // If year_level is already set and not empty, return it
    if ($value && !empty(trim($value))) {
      return $value;
    }

    // If year_level is empty or null, generate a random one
    $yearLevels = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
    
    // Use student_id as seed for consistent randomization per student
    if ($this->student_id) {
      mt_srand(crc32($this->student_id));
      $randomIndex = mt_rand(0, count($yearLevels) - 1);
      mt_srand(); // Reset random seed
      return $yearLevels[$randomIndex];
    }
    
    // Fallback to truly random if no student_id
    return $yearLevels[array_rand($yearLevels)];
  }

  /**
   * Get display-friendly year level
   * 
   * @return string
   */
  public function getDisplayYearLevel()
  {
    return $this->year_level ?: $this->getYearLevelAttribute(null);
  }
}
