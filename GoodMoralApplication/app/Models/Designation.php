<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $fillable = [
        'department_id',
        'description'
    ];

    /**
     * Get the department that owns the designation.
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

}
