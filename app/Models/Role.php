<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_system',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    public function admins()
    {
        return $this->hasMany(Admin::class);
    }

    /**
     * Check if role has a specific permission
     */
    public function hasPermission(string $permissionSlug): bool
    {
        if ($this->slug === 'super-admin' || ($this->is_system && strtolower($this->name) === 'super admin')) {
            return true;
        }

        return $this->permissions->contains('slug', $permissionSlug);
    }
}
