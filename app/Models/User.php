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

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'manager_capability',
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
            'password' => 'hashed',
        ];
    }

    /**
     * Returns true if the user is a super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Returns true if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Returns true if user is admin or super admin.
     */
    public function isAdminOrSuperAdmin(): bool
    {
        return $this->isAdmin() || $this->isSuperAdmin();
    }

    /**
     * Returns true if user is manager.
     */
    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    /**
     * Checks if manager can access a capability: income, expense, both. Admin always true.
     */
    public function canAccess($capability): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        if ($this->isAdmin()) {
            return true;
        }
        if ($this->isManager()) {
            if ($this->manager_capability === 'both') {
                return true;
            }

            return $this->manager_capability === $capability;
        }

        return false;
    }
}
