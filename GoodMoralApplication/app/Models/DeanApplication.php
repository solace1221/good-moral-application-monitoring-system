<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeanApplication extends Model
{
  use HasFactory;

  protected $table = 'dean_applications'; // ✅ Explicit table name

  protected $fillable = [
    'reference_number',
    'number_of_copies',
    'application_id',
    'student_id',
    'department',
    'fullname',
    'reason',
    'course_completed',  // New field
    'graduation_date',    // New field
    'is_undergraduate',   // New field
    'last_course_year_level', // New field
    'last_semester_sy',   // New field
    'status',
  ];

  public function student()
  {
    return $this->belongsTo(RoleAccount::class, 'student_id', 'student_id');
  }
}
