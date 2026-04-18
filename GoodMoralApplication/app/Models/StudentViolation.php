<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentViolation extends Model
{
  use HasFactory;

  protected $table = 'student_violations'; // Specify the table name if it's not the plural form of the model name

  protected $fillable = [
    'first_name',
    'last_name',
    'student_id',
    'status',
    'offense_type',
    'added_by',
    'violation',
    'violation_id',
    'unique_id',
    'department',
    'department_id',
    'course',
    'ref_num',
    'case_type',
    'group_size',
    'document_path',
    'downloaded',
    'meeting_date',
    'meeting_notes',
    'proceedings_uploaded_by',
    'proceedings_uploaded_at',
    'forwarded_to_admin_at',
    'forwarded_by',
    'closed_by',
    'closed_at',
    'decline_reason',
    'reviewed_by',
    'reviewed_role',
    'reviewed_at',
    'finalized_by',
    'finalized_at',
  ];

  protected $casts = [
    'meeting_date' => 'date',
    'proceedings_uploaded_at' => 'datetime',
    'forwarded_to_admin_at' => 'datetime',
    'closed_at' => 'datetime',
    'reviewed_at' => 'datetime',
    'finalized_at' => 'datetime',
  ];

  /**
   * Relationship: A student violation belongs to a student.
   */
  public function student()
  {
    return $this->belongsTo(StudentRegistration::class, 'student_id', 'student_id');
  }

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

  /**
   * Relationship: Get student account information including year level
   */
  public function studentAccount()
  {
    return $this->belongsTo(RoleAccount::class, 'student_id', 'student_id');
  }

  /**
   * Get year level for this violation's student
   * 
   * @return string
   */
  public function getStudentYearLevel()
  {
    if ($this->studentAccount) {
      return $this->studentAccount->year_level ?: 'N/A';
    }

    return 'N/A';
  }

  /**
   * Relationship: Get the violation type record.
   */
  public function violation()
  {
    return $this->belongsTo(Violation::class, 'violation_id');
  }

  /**
   * Relationship: Get notification records for this violation.
   */
  public function notifs()
  {
    return $this->hasMany(ViolationNotif::class, 'ref_num', 'ref_num');
  }

  /**
   * Scope: minor offenses.
   */
  public function scopeMinor($query)
  {
    return $query->where('offense_type', 'minor');
  }

  /**
   * Scope: major offenses.
   */
  public function scopeMajor($query)
  {
    return $query->where('offense_type', 'major');
  }

  /**
   * Scope: violations not yet resolved (status != 2).
   */
  public function scopePending($query)
  {
    return $query->where('status', '!=', 2);
  }

  /**
   * Scope: resolved / closed violations (status = 2).
   */
  public function scopeResolved($query)
  {
    return $query->where('status', 2);
  }
}
