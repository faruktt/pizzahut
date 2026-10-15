<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Admin;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Dashboard
            ['name' => 'View Dashboard', 'slug' => 'dashboard.view', 'module' => 'Dashboard'],
            
            // POS & Tables
            ['name' => 'Access POS System', 'slug' => 'pos.access', 'module' => 'POS & Tables'],
            ['name' => 'Manage Tables', 'slug' => 'tables.manage', 'module' => 'POS & Tables'],
            
            // Orders
            ['name' => 'View Orders', 'slug' => 'orders.view', 'module' => 'Orders'],
            ['name' => 'Create Walk-in Orders', 'slug' => 'orders.create', 'module' => 'Orders'],
            ['name' => 'Update Order Status', 'slug' => 'orders.update_status', 'module' => 'Orders'],
            ['name' => 'Delete Orders', 'slug' => 'orders.delete', 'module' => 'Orders'],
            ['name' => 'Manage Order Statuses', 'slug' => 'order_statuses.manage', 'module' => 'Orders'],
            
            // Products
            ['name' => 'View Products', 'slug' => 'products.view', 'module' => 'Products'],
            ['name' => 'Create Products', 'slug' => 'products.create', 'module' => 'Products'],
            ['name' => 'Edit Products', 'slug' => 'products.edit', 'module' => 'Products'],
            ['name' => 'Delete Products', 'slug' => 'products.delete', 'module' => 'Products'],
            ['name' => 'Manage Categories', 'slug' => 'categories.manage', 'module' => 'Products'],
            ['name' => 'Manage Sizes', 'slug' => 'sizes.manage', 'module' => 'Products'],
            ['name' => 'Manage Crusts', 'slug' => 'crusts.manage', 'module' => 'Products'],
            ['name' => 'Manage Add-ons', 'slug' => 'addons.manage', 'module' => 'Products'],
            ['name' => 'Manage Combos', 'slug' => 'combos.manage', 'module' => 'Products'],
            
            // Customers
            ['name' => 'View Customers', 'slug' => 'users.view', 'module' => 'Customers'],
            ['name' => 'Block / Unblock Customers', 'slug' => 'users.block', 'module' => 'Customers'],
            ['name' => 'Delete Customers', 'slug' => 'users.delete', 'module' => 'Customers'],
            
            // Marketing
            ['name' => 'Manage Coupons', 'slug' => 'coupons.manage', 'module' => 'Marketing'],
            ['name' => 'Manage Reward Points', 'slug' => 'points.manage', 'module' => 'Marketing'],
            ['name' => 'Manage Banners', 'slug' => 'banners.manage', 'module' => 'Marketing'],
            ['name' => 'Manage CMS Pages', 'slug' => 'pages.manage', 'module' => 'Marketing'],
            
            // Staff & Roles
            ['name' => 'Manage Roles & Permissions', 'slug' => 'roles.manage', 'module' => 'Staff & Roles'],
            ['name' => 'Manage Staff / Sub-Admins', 'slug' => 'admins.manage', 'module' => 'Staff & Roles'],
            
            // Settings
            ['name' => 'Manage Site Settings & Shipping', 'slug' => 'settings.manage', 'module' => 'Settings'],
        ];

        $permissionIds = [];
        foreach ($permissions as $p) {
            $permission = Permission::updateOrCreate(
                ['slug' => $p['slug']],
                ['name' => $p['name'], 'module' => $p['module']]
            );
            $permissionIds[] = $permission->id;
        }

        // Create Super Admin Role
        $superAdminRole = Role::updateOrCreate(
            ['slug' => 'super-admin'],
            [
                'name' => 'Super Admin',
                'description' => 'Full access to all system features and settings.',
                'is_system' => true,
            ]
        );
        $superAdminRole->permissions()->sync($permissionIds);

        // Create Branch Manager Role
        $managerRole = Role::updateOrCreate(
            ['slug' => 'manager'],
            [
                'name' => 'Branch Manager',
                'description' => 'Can manage orders, POS, products, customers and coupons.',
                'is_system' => false,
            ]
        );
        $managerPermissions = Permission::whereIn('slug', [
            'dashboard.view', 'pos.access', 'tables.manage',
            'orders.view', 'orders.create', 'orders.update_status',
            'products.view', 'products.create', 'products.edit',
            'categories.manage', 'sizes.manage', 'crusts.manage', 'addons.manage', 'combos.manage',
            'users.view', 'users.block',
            'coupons.manage', 'points.manage',
        ])->pluck('id')->toArray();
        $managerRole->permissions()->sync($managerPermissions);

        // Create Cashier / POS Operator Role
        $cashierRole = Role::updateOrCreate(
            ['slug' => 'cashier'],
            [
                'name' => 'Cashier / POS Staff',
                'description' => 'Can process POS orders and view orders list.',
                'is_system' => false,
            ]
        );
        $cashierPermissions = Permission::whereIn('slug', [
            'dashboard.view', 'pos.access', 'tables.manage',
            'orders.view', 'orders.create', 'orders.update_status',
        ])->pluck('id')->toArray();
        $cashierRole->permissions()->sync($cashierPermissions);

        // Assign existing admins to Super Admin if they don't have a role
        Admin::whereNull('role_id')->update([
            'role_id' => $superAdminRole->id,
            'role' => 'super_admin',
        ]);
    }
}
