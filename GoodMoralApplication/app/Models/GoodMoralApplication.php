<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodMoralApplication extends Model
{
  use HasFactory;

  protected $fillable = [
    'fullname',
    'gender',             // New field for gender
    'student_id',
    'reference_number',
    'number_of_copies',
    'status',
    'department',
    'reason',
    'course_completed',  // New field
    'graduation_date',    // New field
    'application_status', // Application status field
    'rejection_reason',   // Rejection reason
    'rejection_details',  // Detailed rejection explanation
    'rejected_by',        // Who rejected the application
    'rejected_at',        // When it was rejected
    'action_history',     // Track of all actions taken
    'is_undergraduate',   // New field
    'last_course_year_level', // New field
    'last_semester_sy',   // New field
    'certificate_type',   // New field for certificate type
    'receipt_id',
    'claimed_at',
    'claimed_by',
  ];

  /**
   * The attributes that should be cast.
   */
  protected $casts = [
    'reason' => 'array',
    'graduation_date' => 'date',
    'rejected_at' => 'datetime',
    'claimed_at' => 'datetime',
    'is_undergraduate' => 'boolean',
  ];

  protected $appends = ['claimer_name'];

  /**
   * Get the student associated with the application.
   */
  public function student()
  {
    return $this->belongsTo(RoleAccount::class, 'student_id', 'student_id'); // Make sure 'role_account' is used
  }

  /**
   * Get the receipts associated with the application.
   */
  public function receipts()
  {
    return $this->hasMany(\App\Models\Receipt::class, 'reference_num', 'reference_number');
  }

  /**
   * Get the name of the staff who claimed (released) the certificate.
   */
  public function getClaimerNameAttribute(): ?string
  {
    if (!$this->claimed_by) {
      return null;
    }
    $user = \App\Models\User::find($this->claimed_by);
    return $user?->name ?? $user?->fullname ?? 'Unknown';
  }

  /**
   * Get formatted reasons as a string.
   */
  public function getFormattedReasonsAttribute()
  {
    if (is_array($this->reason)) {
      return implode(', ', $this->reason);
    }
    return $this->reason ?? '';
  }

  /**
   * Get reasons as an array.
   */
  public function getReasonsArrayAttribute()
  {
    if (is_array($this->reason)) {
      return $this->reason;
    }
    return $this->reason ? [$this->reason] : [];
  }

  /**
   * Calculate payment amount based on reasons and copies.
   */
  public function getPaymentAmountAttribute()
  {
    $reasonCount = count($this->reasons_array);
    $copies = (int)$this->number_of_copies;
    return $reasonCount * $copies * 50;
  }

  /**
   * Get formatted payment amount with calculation breakdown.
   */
  public function getFormattedPaymentAttribute()
  {
    $reasonCount = count($this->reasons_array);
    $copies = (int)$this->number_of_copies;
    $amount = $this->payment_amount;

    $reasonText = $reasonCount === 1 ? 'reason' : 'reasons';
    $copyText = $copies === 1 ? 'copy' : 'copies';

    return "₱" . number_format($amount, 2) . " ({$reasonCount} {$reasonText} × {$copies} {$copyText} × ₱50.00)";
  }

  /**
   * Scope: applications pending initial review.
   */
  public function scopePending($query)
  {
    return $query->where('status', 'pending');
  }

  /**
   * Scope: applications approved by registrar (status includes appended registrar name).
   */
  public function scopeApprovedByRegistrar($query)
  {
    return $query->where(function ($q) {
      $q->where('application_status', 'LIKE', 'Approved By Registrar%')
        ->orWhere('application_status', 'LIKE', 'Approved by Registrar%');
    });
  }

  /**
   * Scope: applications with a receipt uploaded.
   */
  public function scopeReceiptUploaded($query)
  {
    return $query->where('application_status', 'LIKE', 'Receipt Uploaded%');
  }

  /**
   * Scope: applications ready for moderator print or pickup.
   */
  public function scopeReadyForPrint($query)
  {
    return $query->whereIn('application_status', ['Ready for Moderator Print', 'Ready for Pickup']);
  }
}
