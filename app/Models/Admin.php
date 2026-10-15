<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'image',
        'password',
        'role',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Relationship with Role
     */
    public function roleModel()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Check if the admin is a Super Admin
     */
    public function isSuperAdmin(): bool
    {
        if ($this->role === 'super_admin') {
            return true;
        }

        if ($this->roleModel && $this->roleModel->slug === 'super-admin') {
            return true;
        }

        // If no role_id is assigned and role is 'admin', treat as super admin for backwards compatibility
        if (empty($this->role_id) && ($this->role === 'admin' || empty($this->role))) {
            return true;
        }

        return false;
    }

    /**
     * Check if admin has a specific permission
     */
    public function hasPermission(string $permissionSlug): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if ($this->roleModel) {
            return $this->roleModel->hasPermission($permissionSlug);
        }

        return false;
    }

    /**
     * Check if admin has any of the given permissions
     */
    public function hasAnyPermission(array $permissionSlugs): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        foreach ($permissionSlugs as $slug) {
            if ($this->hasPermission($slug)) {
                return true;
            }
        }

        return false;
    }
}
