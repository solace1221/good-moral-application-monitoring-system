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
     * Get role accounts in this department
     */
    public function roleAccounts()
    {
        return $this->hasMany(RoleAccount::class, 'department_id');
    }

    /**
     * Get designations in this department
     */
    public function designations()
    {
        return $this->hasMany(Designation::class, 'dept_id');
    }

    /**
     * Get courses in this department
     */
    public function courses()
    {
        return $this->hasMany(Course::class, 'department_id');
    }
}
