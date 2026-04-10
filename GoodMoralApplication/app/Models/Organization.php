<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [
        'department_id',
        'description'
    ];

    /**
     * Get the department that owns the organization.
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * Get the positions under this organization.
     */
    public function positions()
    {
        return $this->hasMany(Position::class, 'organization_id');
    }

}
