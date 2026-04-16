<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'firstname',
        'lastname',
        'middlename',
        'suffix_name',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Get the account_type attribute (for backward compatibility)
     * Maps the 'role' column to 'account_type', checking role_accounts for alumni
     */
    public function getAccountTypeAttribute()
    {
        if ($this->role === 'student') {
            $roleAccount = \App\Models\RoleAccount::where('email', $this->email)->first();
            if ($roleAccount && $roleAccount->account_type === 'alumni') {
                return 'alumni';
            }
        }
        return $this->role;
    }

    /**
     * Get the full name attribute (for backward compatibility)
     * Constructs fullname from name parts
     */
    public function getFullnameAttribute()
    {
        // Construct from name parts
        if ($this->firstname && $this->lastname) {
            $name = $this->firstname;
            if ($this->middlename) {
                $name .= ' ' . substr($this->middlename, 0, 1) . '.';
            }
            $name .= ' ' . $this->lastname;
            if ($this->suffix_name) {
                $name .= ' ' . $this->suffix_name;
            }
            return $name;
        }

        // Fallback to 'name' field
        return $this->name ?? $this->email;
    }

    /**
     * Accessor for backward compatibility - fname maps to firstname
     */
    public function getFnameAttribute()
    {
        return $this->firstname;
    }

    /**
     * Accessor for backward compatibility - lname maps to lastname
     */
    public function getLnameAttribute()
    {
        return $this->lastname;
    }

    /**
     * Accessor for backward compatibility - mname maps to middlename
     */
    public function getMnameAttribute()
    {
        return $this->middlename;
    }

    /**
     * Accessor for backward compatibility - extension maps to suffix_name
     */
    public function getExtensionAttribute()
    {
        return $this->suffix_name;
    }

    /**
     * Get the role_account record linked to this user via email.
     */
    public function roleAccount()
    {
        return $this->hasOne(RoleAccount::class, 'email', 'email');
    }

    /**
     * Accessor: resolve department from role_account table.
     */
    public function getDepartmentAttribute()
    {
        return $this->roleAccount?->department;
    }

    /**
     * Accessor: resolve student_id from role_account table.
     */
    public function getStudentIdAttribute()
    {
        return $this->roleAccount?->student_id;
    }

    /**
     * Accessor: resolve gender from role_account table.
     */
    public function getGenderAttribute()
    {
        return $this->roleAccount?->gender;
    }
}

