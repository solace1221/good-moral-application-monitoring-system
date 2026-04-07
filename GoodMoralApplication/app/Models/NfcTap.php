<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NfcTap extends Model
{
    use HasFactory;

    protected $table = 'nfc_taps';

    protected $fillable = [
        'student_id',
        'tapped_by',
        'uid',
        'tapped_at',
    ];

    protected $casts = [
        'tapped_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'tapped_by', 'id');
    }
}
