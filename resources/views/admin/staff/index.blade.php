@extends('layouts.admin')

@section('title', 'Staff Management')
@section('page-title', 'Staff & Admins')

@section('admin-content')
<div style="display:flex;flex-direction:column;gap:20px;">
    <!-- Top Action Bar -->
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <div>
            <h2 style="font-size:1.25rem;font-weight:800;color:#0f172a;margin:0;">Staff Members</h2>
            <div style="font-size:0.8rem;color:#64748b;">Manage administrative team and assign role-based permissions.</div>
        </div>

        <a href="{{ route('admin.staff.create') }}" style="padding:9px 18px;background:var(--admin-primary);color:#fff;border-radius:8px;font-weight:700;font-size:0.85rem;text-decoration:none;display:inline-flex;align-items:center;gap:8px;box-shadow:0 2px 6px rgba(200,16,46,0.25);">
            <i class="fa-solid fa-user-plus"></i> Add Staff Member
        </a>
    </div>

    <!-- Staff Table Card -->
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.02);">
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;text-align:left;font-size:0.88rem;">
                <thead>
                    <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;color:#64748b;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;">
                        <th style="padding:14px 20px;">Staff Member</th>
                        <th style="padding:14px 16px;">Email</th>
                        <th style="padding:14px 16px;">Assigned Role</th>
                        <th style="padding:14px 16px;">Added On</th>
                        <th style="padding:14px 20px;text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $staff)
                        <tr style="border-bottom:1px solid #f1f5f9;transition:background 0.15s;" onmouseover="this.style.background='#fbfcfd'" onmouseout="this.style.background='transparent'">
                            <td style="padding:14px 20px;">
                                <div style="display:flex;align-items:center;gap:12px;">
                                    <div style="width:40px;height:40px;border-radius:10px;background:#f1f5f9;border:1px solid #cbd5e1;overflow:hidden;display:flex;align-items:center;justify-content:center;color:#475569;font-weight:700;flex-shrink:0;">
                                        @if(!empty($staff->image) && file_exists(public_path('uploads/' . $staff->image)))
                                            <img src="{{ asset('uploads/' . $staff->image) }}" alt="{{ $staff->name }}" style="width:100%;height:100%;object-fit:cover;">
                                        @else
                                            {{ strtoupper(substr($staff->name, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div style="font-weight:700;color:#0f172a;">
                                            {{ $staff->name }}
                                            @if(auth('admin')->id() === $staff->id)
                                                <span style="font-size:0.68rem;padding:2px 6px;background:#f1f5f9;border-radius:10px;color:#64748b;margin-left:4px;">You</span>
                                            @endif
                                        </div>
                                        <div style="font-size:0.75rem;color:#64748b;">ID: #{{ $staff->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding:14px 16px;color:#334155;">
                                {{ $staff->email }}
                            </td>
                            <td style="padding:14px 16px;">
                                @if($staff->roleModel)
                                    <span style="display:inline-block;padding:3px 10px;background:rgba(200,16,46,0.1);color:var(--admin-primary);border-radius:20px;font-weight:700;font-size:0.78rem;">
                                        {{ $staff->roleModel->name }}
                                    </span>
                                @elseif($staff->isSuperAdmin())
                                    <span style="display:inline-block;padding:3px 10px;background:#f1f5f9;color:#334155;border-radius:20px;font-weight:700;font-size:0.78rem;">
                                        Super Admin
                                    </span>
                                @else
                                    <span style="color:#94a3b8;font-size:0.8rem;">—</span>
                                @endif
                            </td>
                            <td style="padding:14px 16px;color:#64748b;font-size:0.82rem;">
                                {{ $staff->created_at ? $staff->created_at->format('d M, Y') : '—' }}
                            </td>
                            <td style="padding:14px 20px;text-align:right;">
                                <div style="display:inline-flex;align-items:center;gap:8px;">
                                    <a href="{{ route('admin.staff.edit', $staff->id) }}" style="padding:6px 12px;border-radius:6px;background:#f8fafc;border:1px solid #cbd5e1;color:#1e293b;font-size:0.78rem;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                                        <i class="fa-regular fa-pen-to-square"></i> Edit
                                    </a>

                                    @if(auth('admin')->id() !== $staff->id)
                                        <form method="POST" action="{{ route('admin.staff.destroy', $staff->id) }}" onsubmit="return confirm('Remove staff member \'{{ $staff->name }}\'?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="padding:6px 10px;border-radius:6px;background:#fff;border:1px solid #fecaca;color:#ef4444;font-size:0.78rem;cursor:pointer;">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding:40px;text-align:center;color:#94a3b8;">
                                No staff members found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($admins->hasPages())
            <div style="padding:16px 20px;border-top:1px solid #f1f5f9;">
                {{ $admins->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
