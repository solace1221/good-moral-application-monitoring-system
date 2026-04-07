<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $fillable = [
        'department_name',
        'department_code',
        'description',
    ];

    /**
     * Get users in this department
     */
    public function users()
    {
        return $this->hasMany(User::class, 'department_id', 'id');
    }

    /**
     * Get students in this department
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'department_id', 'id');
    }
}
