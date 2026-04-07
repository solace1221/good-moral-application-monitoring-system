<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeadOSA extends Model
{
  use HasFactory;

  // 💡 Tell Laravel the table name explicitly
  protected $table = 'head_osa';

  protected $fillable = [
    'name',
    'email',
    'password',
  ];
}
