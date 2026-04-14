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
      'status',
    ];

    /**
     * Scope: only active violation types.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: only inactive (archived) violation types.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Check if this violation type is currently in use.
     */
    public function isInUse(): bool
    {
        return $this->studentViolations()->exists();
    }

    /**
     * Get student violations of this type.
     */
    public function studentViolations()
    {
        return $this->hasMany(StudentViolation::class, 'violation_id');
    }
}
