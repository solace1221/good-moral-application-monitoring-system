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
        'employee_id',
        'role',
        'status',
        'department_id',
        'designation_id',
        'position_id',
        'course_id',
        'picture',
        'password_changed_at',
        'failed_login_attempts',
        'locked_until',
        'last_login_at',
        'last_login_ip',
        'force_password_change',
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
            'email_verified_at'      => 'datetime',
            'password'               => 'hashed',
            'locked_until'           => 'datetime',
            'last_login_at'          => 'datetime',
            'password_changed_at'    => 'datetime',
            'force_password_change'  => 'boolean',
            'failed_login_attempts'  => 'integer',
        ];
    }

    /**
     * Get the account_type attribute (for backward compatibility)
     * Maps the 'role' column to 'account_type'
     */
    public function getAccountTypeAttribute()
    {
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
     * Get student info relation (for backward compatibility)
     */
    public function studentInfo()
    {
        return $this->belongsTo(StudentRegistration::class, 'student_id', 'student_id');
    }

    /**
     * Get the good moral applications for this student
     */
    public function goodMoralApplications()
    {
        return $this->hasMany(GoodMoralApplication::class, 'student_id', 'student_id');
    }

    /**
     * Get the designation for PSG Officer
     */
    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    // =========================================================
    // General Relationships
    // =========================================================

    /**
     * Get the department this user belongs to
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * Get courses this user coordinates
     */
    public function coordinatedCourses()
    {
        return $this->hasMany(Course::class, 'coordinator_id', 'id');
    }

    // =========================================================
    // Security / Account Lockout Methods
    // =========================================================

    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    public function getRemainingLockoutTime(): int
    {
        return $this->isLocked() ? (int) $this->locked_until->diffInSeconds(now()) : 0;
    }

    public function needsPasswordChange(): bool
    {
        return $this->force_password_change || ! $this->password_changed_at;
    }

    public function incrementFailedAttempts(): void
    {
        $this->increment('failed_login_attempts');
        $this->refresh();
        if ($this->failed_login_attempts >= 5) {
            $this->update(['locked_until' => now()->addMinutes(30)]);
        }
    }

    public function resetFailedAttempts(): void
    {
        $this->update(['failed_login_attempts' => 0, 'locked_until' => null]);
    }

    /** Alias for resetFailedAttempts */
    public function clearFailedAttempts(): void
    {
        $this->resetFailedAttempts();
    }

    /** Alias for incrementFailedAttempts */
    public function recordFailedAttempt(): void
    {
        $this->incrementFailedAttempts();
    }

    public function recordLogin(?string $ipAddress = null): void
    {
        $this->update([
            'last_login_at'         => now(),
            'last_login_ip'         => $ipAddress,
            'failed_login_attempts' => 0,
            'locked_until'          => null,
        ]);
    }
}

