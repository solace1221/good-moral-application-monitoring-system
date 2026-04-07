<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchivedRoleAccount extends Model
{
    use HasFactory;

    // If the table name doesn't follow Laravel's convention (pluralized form), set it explicitly
    protected $table = 'archived_role_accounts';

    // If the primary key is not the default 'id', you can specify it here
    // protected $primaryKey = 'your_primary_key_column';

    // If you want to specify which attributes are mass assignable
    protected $fillable = [
        'student_id',
        'fullname',
        'department',
        'status',
        'account_type',
        'created_at',
        'updated_at',
    ];

    // If you want to disable timestamps (if not needed in the archived table)
    // public $timestamps = false;
}
