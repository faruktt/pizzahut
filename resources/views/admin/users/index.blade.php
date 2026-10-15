@extends('layouts.admin')

@section('title', 'Customer Management')
@section('page-title', 'Customers')

@section('admin-content')
<div style="display:flex;flex-direction:column;gap:20px;">
    <!-- Stat Counter Cards -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:16px;">
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:18px 20px;display:flex;align-items:center;gap:16px;">
            <div style="width:46px;height:46px;border-radius:10px;background:rgba(200,16,46,0.1);color:var(--admin-primary);display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <div style="font-size:0.8rem;color:#64748b;font-weight:600;">Total Customers</div>
                <div style="font-size:1.5rem;font-weight:800;color:#0f172a;line-height:1.2;">{{ number_format($stats['total']) }}</div>
            </div>
        </div>

        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:18px 20px;display:flex;align-items:center;gap:16px;">
            <div style="width:46px;height:46px;border-radius:10px;background:rgba(16,185,129,0.1);color:#10b981;display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
                <i class="fa-solid fa-user-check"></i>
            </div>
            <div>
                <div style="font-size:0.8rem;color:#64748b;font-weight:600;">Active Accounts</div>
                <div style="font-size:1.5rem;font-weight:800;color:#10b981;line-height:1.2;">{{ number_format($stats['active']) }}</div>
            </div>
        </div>

        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:18px 20px;display:flex;align-items:center;gap:16px;">
            <div style="width:46px;height:46px;border-radius:10px;background:rgba(239,68,68,0.1);color:#ef4444;display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
                <i class="fa-solid fa-user-slash"></i>
            </div>
            <div>
                <div style="font-size:0.8rem;color:#64748b;font-weight:600;">Blocked Accounts</div>
                <div style="font-size:1.5rem;font-weight:800;color:#ef4444;line-height:1.2;">{{ number_format($stats['blocked']) }}</div>
            </div>
        </div>

        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:18px 20px;display:flex;align-items:center;gap:16px;">
            <div style="width:46px;height:46px;border-radius:10px;background:rgba(245,158,11,0.1);color:#f59e0b;display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
                <i class="fa-solid fa-coins"></i>
            </div>
            <div>
                <div style="font-size:0.8rem;color:#64748b;font-weight:600;">Reward Points</div>
                <div style="font-size:1.5rem;font-weight:800;color:#f59e0b;line-height:1.2;">{{ number_format($stats['total_points']) }}</div>
            </div>
        </div>
    </div>

    <!-- Filter & Table Card -->
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.02);">
        <!-- Filter Bar -->
        <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;">
            <form method="GET" action="{{ route('admin.users.index') }}" style="display:flex;align-items:center;gap:10px;flex:1;max-width:550px;">
                <div style="position:relative;flex:1;">
                    <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:0.85rem;"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, phone..." style="width:100%;padding:8px 12px 8px 34px;border:1px solid #cbd5e1;border-radius:8px;font-size:0.85rem;font-family:'Poppins',sans-serif;outline:none;">
                </div>
                <select name="status" style="padding:8px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:0.85rem;font-family:'Poppins',sans-serif;outline:none;background:#fff;">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>Blocked</option>
                </select>
                <button type="submit" style="padding:8px 16px;background:#0f172a;color:#fff;border:none;border-radius:8px;font-size:0.85rem;font-weight:600;cursor:pointer;">Filter</button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.users.index') }}" style="padding:8px 12px;background:#f1f5f9;color:#64748b;border-radius:8px;font-size:0.85rem;text-decoration:none;">Reset</a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;text-align:left;font-size:0.88rem;">
                <thead>
                    <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;color:#64748b;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;">
                        <th style="padding:14px 20px;">Customer</th>
                        <th style="padding:14px 16px;">Contact</th>
                        <th style="padding:14px 16px;text-align:center;">Orders</th>
                        <th style="padding:14px 16px;text-align:center;">Points</th>
                        <th style="padding:14px 16px;">Joined</th>
                        <th style="padding:14px 16px;text-align:center;">Status</th>
                        <th style="padding:14px 20px;text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody style="divide-y:1px solid #f1f5f9;">
                    @forelse($users as $user)
                        <tr style="border-bottom:1px solid #f1f5f9;transition:background 0.15s;" onmouseover="this.style.background='#fbfcfd'" onmouseout="this.style.background='transparent'">
                            <td style="padding:14px 20px;">
                                <div style="display:flex;align-items:center;gap:12px;">
                                    <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg, #c8102e, #a50b24);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.95rem;flex-shrink:0;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight:700;color:#0f172a;">{{ $user->name }}</div>
                                        <div style="font-size:0.75rem;color:#64748b;">ID: #{{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding:14px 16px;">
                                <div style="color:#334155;">{{ $user->email }}</div>
                                <div style="font-size:0.78rem;color:#64748b;">{{ $user->phone ?? '—' }}</div>
                            </td>
                            <td style="padding:14px 16px;text-align:center;">
                                <span style="display:inline-block;padding:3px 10px;background:#f1f5f9;border-radius:20px;font-weight:700;font-size:0.8rem;color:#334155;">
                                    {{ $user->orders_count }}
                                </span>
                            </td>
                            <td style="padding:14px 16px;text-align:center;">
                                <span style="display:inline-block;padding:3px 10px;background:rgba(245,158,11,0.1);color:#d97706;border-radius:20px;font-weight:700;font-size:0.8rem;">
                                    <i class="fa-solid fa-coins" style="font-size:0.7rem;margin-right:3px;"></i>{{ number_format($user->reward_points ?? 0) }}
                                </span>
                            </td>
                            <td style="padding:14px 16px;color:#64748b;font-size:0.82rem;">
                                {{ $user->created_at ? $user->created_at->format('d M, Y') : '—' }}
                            </td>
                            <td style="padding:14px 16px;text-align:center;">
                                @if($user->is_blocked)
                                    <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;background:#fee2e2;color:#b91c1c;border-radius:20px;font-weight:700;font-size:0.75rem;">
                                        <i class="fa-solid fa-ban" style="font-size:0.7rem;"></i> Blocked
                                    </span>
                                @else
                                    <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;background:#dcfce7;color:#15803d;border-radius:20px;font-weight:700;font-size:0.75rem;">
                                        <i class="fa-solid fa-check" style="font-size:0.7rem;"></i> Active
                                    </span>
                                @endif
                            </td>
                            <td style="padding:14px 20px;text-align:right;">
                                <div style="display:inline-flex;align-items:center;gap:8px;">
                                    <!-- View Details -->
                                    <a href="{{ route('admin.users.show', $user->id) }}" title="View Profile" style="padding:6px 10px;border-radius:6px;background:#f1f5f9;color:#334155;border:1px solid #cbd5e1;font-size:0.78rem;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                                        <i class="fa-regular fa-eye"></i> Details
                                    </a>

                                    <!-- Block / Unblock Toggle -->
                                    <form method="POST" action="{{ route('admin.users.toggleBlock', $user->id) }}" style="display:inline;" onsubmit="return confirm('{{ $user->is_blocked ? "Are you sure you want to unblock this user?" : "Are you sure you want to block this user from logging in?" }}')">
                                        @csrf
                                        @if($user->is_blocked)
                                            <button type="submit" title="Unblock User" style="padding:6px 12px;border-radius:6px;background:#dcfce7;color:#15803d;border:1px solid #bbf7d0;font-size:0.78rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:4px;">
                                                <i class="fa-solid fa-unlock"></i> Unblock
                                            </button>
                                        @else
                                            <button type="submit" title="Block User" style="padding:6px 12px;border-radius:6px;background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;font-size:0.78rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:4px;">
                                                <i class="fa-solid fa-ban"></i> Block
                                            </button>
                                        @endif
                                    </form>

                                    <!-- Delete Button -->
                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" style="display:inline;" onsubmit="return confirm('Delete this customer account completely?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete User" style="padding:6px 8px;border-radius:6px;background:#fff;color:#94a3b8;border:1px solid #e2e8f0;font-size:0.78rem;cursor:pointer;">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding:40px;text-align:center;color:#94a3b8;">
                                <i class="fa-solid fa-users" style="font-size:2rem;margin-bottom:8px;display:block;"></i>
                                No customers found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div style="padding:16px 20px;border-top:1px solid #f1f5f9;">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
