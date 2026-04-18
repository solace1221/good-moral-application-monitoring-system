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
    'department_id',
    'course_id',
    'course',
    'year_level',
    'academic_status',
    'fullname',
    'mname',
    'extension',
    'gender',
    'organization',
    'position',
    'status',
    'created_via',
    'is_graduating',
    'graduation_date',
    'graduated_at',
    'pending_email',
    'email_verification_token',
    'email_verification_sent_at',
    'email_verified_at',
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
   * Check if this account is a student-type account.
   */
  public function isStudentType(): bool
  {
    return in_array($this->account_type, ['student', 'alumni']);
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

  public function violations()
  {
    return $this->hasMany(StudentViolation::class, 'student_id', 'student_id');
  }

  public function headOsaApplications()
  {
    return $this->hasMany(HeadOSAApplication::class, 'student_id', 'student_id');
  }

  public function deanApplications()
  {
    return $this->hasMany(DeanApplication::class, 'student_id', 'student_id');
  }

  public function secOsaApplications()
  {
    return $this->hasMany(SecOSAApplication::class, 'student_id', 'student_id');
  }

  public function receipts()
  {
    return $this->hasMany(Receipt::class, 'student_id', 'student_id');
  }

  public function violationNotifs()
  {
    return $this->hasMany(ViolationNotif::class, 'student_id', 'student_id');
  }

  public function notifArchives()
  {
    return $this->hasMany(NotifArchive::class, 'student_id', 'student_id');
  }

  public function yearLevelHistories()
  {
    return $this->hasMany(StudentYearLevelHistory::class, 'student_id', 'student_id');
  }

  public function archivedAccount()
  {
    return $this->hasOne(ArchivedRoleAccount::class, 'student_id', 'student_id');
  }
}
