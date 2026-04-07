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
    'unique_id',
    'department',
    'course',
    'ref_num',
    'document_path',
    'meeting_date',
    'meeting_notes',
    'proceedings_uploaded_by',
    'proceedings_uploaded_at',
    'forwarded_to_admin_at',
    'forwarded_by',
    'closed_by',
    'closed_at',
  ];

  protected $casts = [
    'meeting_date' => 'date',
    'proceedings_uploaded_at' => 'datetime',
    'forwarded_to_admin_at' => 'datetime',
    'closed_at' => 'datetime',
  ];

  /**
   * Relationship: A student violation belongs to a student.
   */
  public function student()
  {
    return $this->belongsTo(StudentRegistration::class, 'student_id', 'student_id');
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
      return $this->studentAccount->getDisplayYearLevel();
    }

    // If no student account found, generate random year level based on student_id
    if ($this->student_id) {
      $yearLevels = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
      mt_srand(crc32($this->student_id));
      $randomIndex = mt_rand(0, count($yearLevels) - 1);
      mt_srand(); // Reset random seed
      return $yearLevels[$randomIndex];
    }

    return '1st Year'; // Default fallback
  }
}
