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

}
