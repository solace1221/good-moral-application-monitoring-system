<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
  use HasFactory;

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

  /**
   * Get the student who owns this receipt.
   */
  public function student()
  {
    return $this->belongsTo(RoleAccount::class, 'student_id', 'student_id');
  }

  /**
   * Get the application this receipt belongs to.
   */
  public function application()
  {
    return $this->belongsTo(GoodMoralApplication::class, 'reference_num', 'reference_number');
  }
}
