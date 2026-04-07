<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
  use HasFactory;

  protected $table = 'receipt';

  protected $fillable = [
    'document_path',
    'reference_num',
    'official_receipt_no',
    'date_paid',
    'receipt_number',
    'student_id',
    'amount',
    'payment_method',
    'status',
  ];

  protected $casts = [
    'date_paid' => 'date',
    'amount' => 'decimal:2',
  ];
}
