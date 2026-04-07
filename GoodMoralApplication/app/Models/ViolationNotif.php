<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViolationNotif extends Model
{
  use HasFactory;

  protected $fillable = [
    'ref_num',
    'student_id',
    'status',
    'notif',
  ];

  /**
   * Get the student this notification belongs to.
   */
  public function student()
  {
    return $this->belongsTo(RoleAccount::class, 'student_id', 'student_id');
  }

  /**
   * Get the student violation this notification references.
   */
  public function studentViolation()
  {
    return $this->belongsTo(StudentViolation::class, 'ref_num', 'ref_num');
  }
}
