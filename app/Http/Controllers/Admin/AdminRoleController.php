<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminRoleController extends Controller
{
    /**
     * List all roles
     */
    public function index()
    {
        $roles = Role::withCount('admins')
            ->with('permissions')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show create role form
     */
    public function create()
    {
        $permissions = Permission::all()->groupBy('module');
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store new role
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:roles,name',
            'description' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $slug = Str::slug($request->name);
        // Ensure slug is unique
        $originalSlug = $slug;
        $counter = 1;
        while (Role::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        $role = Role::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'is_system' => false,
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return redirect()->route('admin.roles.index')->with('success', "নতুন রোল '{$role->name}' সফলভাবে তৈরি করা হয়েছে।");
    }

    /**
     * Show edit role form
     */
    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);

        if ($role->slug === 'super-admin' || ($role->is_system && strtolower($role->name) === 'super admin')) {
            return redirect()->route('admin.roles.index')->with('error', 'Super Admin রোলের সকল পারমিশনে স্বয়ংক্রিয় ফুল অ্যাক্সেস রয়েছে, এটি এডিট করা যাবে না।');
        }

        $permissions = Permission::all()->groupBy('module');
        $rolePermissionIds = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissionIds'));
    }

    /**
     * Update existing role
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        if ($role->slug === 'super-admin' || ($role->is_system && strtolower($role->name) === 'super admin')) {
            return redirect()->route('admin.roles.index')->with('error', 'Super Admin রোলের পারমিশন পরিবর্তন করা যাবে না।');
        }

        $request->validate([
            'name' => 'required|string|max:100|unique:roles,name,' . $role->id,
            'description' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Don't modify slug for system roles to avoid breaking core checks
        if (!$role->is_system) {
            $role->name = $request->name;
            $role->slug = Str::slug($request->name);
        } else {
            $role->name = $request->name;
        }

        $role->description = $request->description;
        $role->save();

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        } else {
            $role->permissions()->sync([]);
        }

        return redirect()->route('admin.roles.index')->with('success', "রোল '{$role->name}' সফলভাবে আপডেট করা হয়েছে।");
    }

    /**
     * Delete role
     */
    public function destroy($id)
    {
        $role = Role::withCount('admins')->findOrFail($id);

        if ($role->is_system) {
            return redirect()->back()->with('error', 'সিস্টেম রোল মুছে ফেলা সম্ভব নয়।');
        }

        if ($role->admins_count > 0) {
            return redirect()->back()->with('error', "এই রোলে {$role->admins_count} জন অ্যাডমিন যুক্ত রয়েছে। প্রথমে তাদের রোল পরিবর্তন করুন।");
        }

        $name = $role->name;
        $role->permissions()->detach();
        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', "রোল '{$name}' মুছে ফেলা হয়েছে।");
    }
}
