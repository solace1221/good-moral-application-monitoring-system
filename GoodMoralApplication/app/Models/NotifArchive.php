<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotifArchive extends Model
{
    use HasFactory;

    protected $table = 'notifarchives'; // Table name

    protected $fillable = [
        'student_id',
        'reference_number',
        'number_of_copies',
        'status',
        'fullname',
        'gender',
        'department',
        'reason',
        'application_status',
        'course_completed',
        'graduation_date',
        'is_undergraduate',
        'last_course_year_level',
        'last_semester_sy',
        'certificate_type',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'reason' => 'array',
        'graduation_date' => 'date',
        'is_undergraduate' => 'boolean',
    ];

    // If you're working with timestamps
    public $timestamps = true;

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
}
