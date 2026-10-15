@extends('layouts.admin')

@section('title', 'Edit Role - ' . $role->name)
@section('page-title', 'Edit Role')

@section('admin-content')
<div style="max-width:960px;margin:0 auto;display:flex;flex-direction:column;gap:20px;">
    <!-- Back Link -->
    <a href="{{ route('admin.roles.index') }}" style="display:inline-flex;align-items:center;gap:6px;color:#64748b;text-decoration:none;font-size:0.85rem;font-weight:600;">
        <i class="fa-solid fa-arrow-left"></i> Back to Roles
    </a>

    <form method="POST" action="{{ route('admin.roles.update', $role->id) }}" style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:26px;display:flex;flex-direction:column;gap:24px;">
        @csrf
        @method('PUT')

        <!-- Role Basic Info -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;">
            <div>
                <label style="display:block;font-size:0.82rem;font-weight:700;color:#334155;margin-bottom:6px;">Role Name *</label>
                <input type="text" name="name" value="{{ old('name', $role->name) }}" required style="width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:0.88rem;font-family:'Poppins',sans-serif;outline:none;">
                @error('name')
                    <div style="color:#ef4444;font-size:0.75rem;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label style="display:block;font-size:0.82rem;font-weight:700;color:#334155;margin-bottom:6px;">Description</label>
                <input type="text" name="description" value="{{ old('description', $role->description) }}" placeholder="Brief description of this role's purpose" style="width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:0.88rem;font-family:'Poppins',sans-serif;outline:none;">
            </div>
        </div>

        <!-- Master Select All -->
        <div style="border-top:1px solid #f1f5f9;padding-top:18px;display:flex;align-items:center;justify-content:space-between;">
            <h3 style="font-size:1.1rem;font-weight:800;color:#0f172a;margin:0;">Permissions</h3>
            <label style="display:inline-flex;align-items:center;gap:8px;font-size:0.82rem;font-weight:700;color:var(--admin-primary);cursor:pointer;user-select:none;">
                <input type="checkbox" id="masterSelectAll" onchange="toggleAllPermissions(this)" style="width:16px;height:16px;accent-color:var(--admin-primary);cursor:pointer;">
                Select All Permissions
            </label>
        </div>

        <!-- Modules Grid -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:16px;">
            @foreach($permissions as $module => $modulePerms)
                <div style="border:1px solid #e2e8f0;border-radius:10px;overflow:hidden;background:#fafafa;">
                    <!-- Module Header with Select All for this module -->
                    <div style="padding:10px 14px;background:#f1f5f9;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;">
                        <span style="font-weight:800;font-size:0.82rem;color:#0f172a;text-transform:uppercase;letter-spacing:0.3px;">
                            {{ $module }}
                        </span>
                        <label style="display:inline-flex;align-items:center;gap:5px;font-size:0.72rem;color:#64748b;font-weight:600;cursor:pointer;">
                            <input type="checkbox" class="module-select-all" data-module="{{ Str::slug($module) }}" onchange="toggleModulePermissions(this, '{{ Str::slug($module) }}')" style="width:14px;height:14px;accent-color:var(--admin-primary);">
                            All
                        </label>
                    </div>

                    <!-- Module Checkboxes -->
                    <div style="padding:12px 14px;display:flex;flex-direction:column;gap:8px;background:#fff;">
                        @foreach($modulePerms as $perm)
                            <label style="display:flex;align-items:center;gap:9px;font-size:0.8rem;color:#334155;cursor:pointer;user-select:none;">
                                <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" class="perm-checkbox perm-module-{{ Str::slug($module) }}" {{ in_array($perm->id, $rolePermissionIds) ? 'checked' : '' }} style="width:15px;height:15px;accent-color:var(--admin-primary);cursor:pointer;">
                                <span>{{ $perm->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Form Submit -->
        <div style="border-top:1px solid #f1f5f9;padding-top:18px;display:flex;align-items:center;justify-content:flex-end;gap:12px;">
            <a href="{{ route('admin.roles.index') }}" style="padding:9px 18px;background:#f1f5f9;color:#64748b;border-radius:8px;font-size:0.85rem;font-weight:600;text-decoration:none;">Cancel</a>
            <button type="submit" style="padding:9px 22px;background:var(--admin-primary);color:#fff;border:none;border-radius:8px;font-size:0.85rem;font-weight:700;cursor:pointer;">Update Role</button>
        </div>
    </form>
</div>

<script>
    function toggleAllPermissions(master) {
        document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = master.checked);
        document.querySelectorAll('.module-select-all').forEach(cb => cb.checked = master.checked);
    }

    function toggleModulePermissions(moduleMaster, moduleSlug) {
        document.querySelectorAll('.perm-module-' + moduleSlug).forEach(cb => cb.checked = moduleMaster.checked);
    }
</script>
@endsection
