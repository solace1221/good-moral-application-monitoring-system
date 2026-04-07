<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NfcViolationLog extends Model
{
    use HasFactory;

    protected $table = 'nfc_violation_logs';

    protected $fillable = [
        'student_id',
        'student_number',
        'nfc_uid',
        'has_violation',
        'tap_status',
        'violation_type',
        'remarks',
        'expected_tap_date',
        'actual_tap_date',
        'logged_by',
    ];

    protected $casts = [
        'has_violation'     => 'boolean',
        'expected_tap_date' => 'datetime',
        'actual_tap_date'   => 'datetime',
    ];

    // ─── Relationships ─────────────────────────────────────────────────────────

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function logger()
    {
        return $this->belongsTo(User::class, 'logged_by');
    }

    // ─── Scopes ────────────────────────────────────────────────────────────────

    public function scopeViolationsNotTapped($query)
    {
        return $query->where('has_violation', true)->where('tap_status', 'not_tapped');
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}
