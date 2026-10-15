@extends('layouts.admin')

@section('title', 'Roles & Permissions')
@section('page-title', 'Roles & Permissions')

@section('admin-content')
<div style="display:flex;flex-direction:column;gap:20px;">
    <!-- Top Action Bar -->
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <div>
            <h2 style="font-size:1.25rem;font-weight:800;color:#0f172a;margin:0;">Manage Roles</h2>
            <div style="font-size:0.8rem;color:#64748b;">Configure access levels and granular permissions for staff.</div>
        </div>

        <a href="{{ route('admin.roles.create') }}" style="padding:9px 18px;background:var(--admin-primary);color:#fff;border-radius:8px;font-weight:700;font-size:0.85rem;text-decoration:none;display:inline-flex;align-items:center;gap:8px;box-shadow:0 2px 6px rgba(200,16,46,0.25);">
            <i class="fa-solid fa-plus"></i> Create New Role
        </a>
    </div>

    <!-- Roles Grid / Table -->
    <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(340px, 1fr));gap:18px;">
        @forelse($roles as $role)
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:22px;display:flex;flex-direction:column;justify-content:space-between;box-shadow:0 1px 3px rgba(0,0,0,0.02);">
                <div>
                    <!-- Header with Role Name and Badges -->
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                        <h3 style="font-size:1.1rem;font-weight:800;color:#0f172a;margin:0;">{{ $role->name }}</h3>
                        @if($role->is_system)
                            <span style="padding:3px 8px;background:#f1f5f9;color:#475569;border-radius:12px;font-size:0.7rem;font-weight:700;text-transform:uppercase;">
                                System
                            </span>
                        @endif
                    </div>

                    @if($role->description)
                        <p style="font-size:0.82rem;color:#64748b;line-height:1.4;margin-bottom:14px;">{{ $role->description }}</p>
                    @endif

                    <!-- Admins count -->
                    <!-- Admins count & Permissions summary -->
                    <div style="display:flex;align-items:center;gap:16px;font-size:0.82rem;color:#475569;margin-bottom:14px;padding-bottom:12px;border-bottom:1px solid #f1f5f9;">
                        <div>
                            <i class="fa-solid fa-user-shield" style="color:var(--admin-primary);margin-right:4px;"></i>
                            <strong>{{ $role->admins_count }}</strong> staff assigned
                        </div>
                        <div>
                            @if($role->slug === 'super-admin')
                                <i class="fa-solid fa-shield-halved" style="color:#10b981;margin-right:4px;"></i>
                                <strong style="color:#10b981;">Full Access (All Permissions)</strong>
                            @else
                                <i class="fa-solid fa-key" style="color:#f59e0b;margin-right:4px;"></i>
                                <strong>{{ $role->permissions->count() }}</strong> permissions
                            @endif
                        </div>
                    </div>

                    <!-- Permissions tags preview -->
                    <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:18px;max-height:85px;overflow-y:auto;">
                        @if($role->slug === 'super-admin')
                            <span style="padding:4px 10px;background:#dcfce7;border:1px solid #bbf7d0;border-radius:6px;font-size:0.75rem;color:#15803d;font-weight:700;">
                                <i class="fa-solid fa-check-double" style="margin-right:4px;"></i> Full Unrestricted System Permissions
                            </span>
                        @else
                            @forelse($role->permissions->take(6) as $perm)
                                <span style="padding:2px 8px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;font-size:0.72rem;color:#475569;font-weight:600;">
                                    {{ $perm->name }}
                                </span>
                            @empty
                                <span style="font-size:0.75rem;color:#94a3b8;font-style:italic;">No permissions assigned.</span>
                            @endforelse
                            @if($role->permissions->count() > 6)
                                <span style="padding:2px 8px;background:#f1f5f9;border-radius:6px;font-size:0.72rem;color:#64748b;font-weight:700;">
                                    +{{ $role->permissions->count() - 6 }} more
                                </span>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Card Actions -->
                <div style="display:flex;align-items:center;justify-content:flex-end;gap:8px;border-top:1px solid #f1f5f9;padding-top:14px;">
                    @if($role->slug === 'super-admin')
                        <span style="padding:6px 14px;border-radius:6px;background:#f8fafc;color:#64748b;font-size:0.78rem;font-weight:700;display:inline-flex;align-items:center;gap:6px;border:1px solid #e2e8f0;" title="Super Admin has full access to all features and cannot be edited">
                            <i class="fa-solid fa-lock" style="font-size:0.75rem;color:#94a3b8;"></i> All Permissions (Locked)
                        </span>
                    @else
                        <a href="{{ route('admin.roles.edit', $role->id) }}" style="padding:6px 12px;border-radius:6px;background:#f8fafc;border:1px solid #cbd5e1;color:#1e293b;font-size:0.78rem;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:5px;">
                            <i class="fa-regular fa-pen-to-square"></i> Edit Permissions
                        </a>

                        @if(!$role->is_system && $role->admins_count === 0)
                            <form method="POST" action="{{ route('admin.roles.destroy', $role->id) }}" onsubmit="return confirm('Delete role \'{{ $role->name }}\'?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="padding:6px 10px;border-radius:6px;background:#fff;border:1px solid #fecaca;color:#ef4444;font-size:0.78rem;cursor:pointer;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        @empty
            <div style="grid-column:1/-1;padding:40px;background:#fff;border:1px solid #e2e8f0;border-radius:14px;text-align:center;color:#94a3b8;">
                No roles defined.
            </div>
        @endforelse
    </div>
</div>
@endsection
