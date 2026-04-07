<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $primaryKey = 'dsn_ids';
    
    protected $fillable = [
        'dept_id',
        'description'
    ];

    /**
     * Get the department that owns the designation.
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }

    /**
     * Get the positions for the designation.
     */
    public function positions()
    {
        return $this->hasMany(Position::class, 'dsn_id', 'dsn_id');
    }
}
