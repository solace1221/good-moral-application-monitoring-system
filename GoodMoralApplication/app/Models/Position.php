<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $table = 'positions';

    protected $fillable = [
        'organization_id',
        'position_title',
    ];

    /**
     * Get the organization this position belongs to.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    // =========================================================
    // Legacy Accessors / Mutators
    // =========================================================
    // Existing views and forms use `dsn_id` and `position_id`.
    // These map to the normalized column names.

    /**
     * Accessor: $position->position_id → $this->id
     */
    public function getPositionIdAttribute()
    {
        return $this->id;
    }

    /**
     * Accessor: $position->dsn_id → $this->organization_id
     */
    public function getDsnIdAttribute()
    {
        return $this->organization_id;
    }

    /**
     * Mutator: $position->dsn_id = value → sets organization_id
     */
    public function setDsnIdAttribute($value)
    {
        $this->attributes['organization_id'] = $value;
    }
}
