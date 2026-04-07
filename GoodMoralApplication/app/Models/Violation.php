<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    use HasFactory;

    protected $fillable = [
      'offense_type',
      'description',
      'article',
    ];

    /**
     * Get student violations of this type.
     */
    public function studentViolations()
    {
        return $this->hasMany(StudentViolation::class, 'violation_id');
    }
}
