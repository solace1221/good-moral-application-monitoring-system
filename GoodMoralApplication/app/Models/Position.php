<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $primaryKey = 'position_id';
    
    protected $fillable = [
        'dsn_id',
        'position_title'
    ];

    /**
     * Get the designation that owns the position.
     */
    public function designation()
    {
        return $this->belongsTo(Designation::class, 'dsn_id', 'dsn_id');
    }
}
