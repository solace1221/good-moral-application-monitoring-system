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
     * Get role accounts in this department
     */
    public function roleAccounts()
    {
        return $this->hasMany(RoleAccount::class, 'department_id');
    }

    /**
     * Get organizations in this department
     */
    public function organizations()
    {
        return $this->hasMany(Organization::class, 'department_id');
    }

    /**
     * Get courses in this department (matched via department_code string)
     */
    public function courses()
    {
        return $this->hasMany(Course::class, 'department', 'department_code');
    }
}
