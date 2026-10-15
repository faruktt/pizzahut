@extends('layouts.admin')

@section('title', 'Customer Details - ' . $user->name)
@section('page-title', 'Customer Profile')

@section('admin-content')
<div style="display:flex;flex-direction:column;gap:20px;">
    <!-- Top Bar with Back and Actions -->
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <a href="{{ route('admin.users.index') }}" style="display:inline-flex;align-items:center;gap:6px;color:#64748b;text-decoration:none;font-size:0.88rem;font-weight:600;">
            <i class="fa-solid fa-arrow-left"></i> Back to Customers
        </a>

        <div style="display:flex;align-items:center;gap:10px;">
            <form method="POST" action="{{ route('admin.users.toggleBlock', $user->id) }}" onsubmit="return confirm('{{ $user->is_blocked ? "Unblock this user?" : "Block this user from logging in?" }}')">
                @csrf
                @if($user->is_blocked)
                    <button type="submit" style="padding:8px 16px;background:#dcfce7;color:#15803d;border:1px solid #bbf7d0;border-radius:8px;font-weight:700;font-size:0.85rem;cursor:pointer;display:inline-flex;align-items:center;gap:6px;">
                        <i class="fa-solid fa-unlock"></i> Unblock Customer
                    </button>
                @else
                    <button type="submit" style="padding:8px 16px;background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;border-radius:8px;font-weight:700;font-size:0.85rem;cursor:pointer;display:inline-flex;align-items:center;gap:6px;">
                        <i class="fa-solid fa-ban"></i> Block Customer
                    </button>
                @endif
            </form>
        </div>
    </div>

    <!-- Customer Info Card & Stats -->
    <div style="display:grid;grid-template-columns:300px 1fr;gap:20px;">
        <!-- Left: Profile Info -->
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:24px;text-align:center;">
            <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg, #c8102e, #a50b24);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:2rem;font-weight:800;margin-bottom:14px;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h2 style="font-size:1.2rem;font-weight:800;color:#0f172a;margin-bottom:4px;">{{ $user->name }}</h2>
            <div style="font-size:0.82rem;color:#64748b;margin-bottom:12px;">Customer ID: #{{ $user->id }}</div>

            @if($user->is_blocked)
                <div style="display:inline-flex;align-items:center;gap:6px;padding:4px 12px;background:#fee2e2;color:#b91c1c;border-radius:20px;font-weight:700;font-size:0.8rem;margin-bottom:18px;">
                    <i class="fa-solid fa-ban"></i> Account Blocked
                </div>
            @else
                <div style="display:inline-flex;align-items:center;gap:6px;padding:4px 12px;background:#dcfce7;color:#15803d;border-radius:20px;font-weight:700;font-size:0.8rem;margin-bottom:18px;">
                    <i class="fa-solid fa-check"></i> Account Active
                </div>
            @endif

            <div style="border-top:1px solid #f1f5f9;padding-top:16px;text-align:left;display:flex;flex-direction:column;gap:10px;font-size:0.85rem;">
                <div>
                    <span style="color:#64748b;display:block;font-size:0.75rem;">Email Address</span>
                    <strong style="color:#1e293b;">{{ $user->email }}</strong>
                </div>
                <div>
                    <span style="color:#64748b;display:block;font-size:0.75rem;">Phone Number</span>
                    <strong style="color:#1e293b;">{{ $user->phone ?? 'Not provided' }}</strong>
                </div>
                <div>
                    <span style="color:#64748b;display:block;font-size:0.75rem;">Registered On</span>
                    <strong style="color:#1e293b;">{{ $user->created_at ? $user->created_at->format('d M, Y h:i A') : '—' }}</strong>
                </div>
            </div>
        </div>

        <!-- Right: Stats & Order History -->
        <div style="display:flex;flex-direction:column;gap:18px;">
            <!-- Stats Row -->
            <div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:14px;">
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px 18px;">
                    <div style="font-size:0.78rem;color:#64748b;font-weight:600;">Total Orders</div>
                    <div style="font-size:1.4rem;font-weight:800;color:#0f172a;line-height:1.2;margin-top:4px;">{{ $user->orders_count }}</div>
                </div>
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px 18px;">
                    <div style="font-size:0.78rem;color:#64748b;font-weight:600;">Total Spent (Paid)</div>
                    <div style="font-size:1.4rem;font-weight:800;color:var(--admin-primary);line-height:1.2;margin-top:4px;">৳{{ number_format($totalSpent, 2) }}</div>
                </div>
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px 18px;">
                    <div style="font-size:0.78rem;color:#64748b;font-weight:600;">Reward Points</div>
                    <div style="font-size:1.4rem;font-weight:800;color:#d97706;line-height:1.2;margin-top:4px;">{{ number_format($user->reward_points ?? 0) }}</div>
                </div>
            </div>

            <!-- Orders Table -->
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden;">
                <div style="padding:14px 18px;border-bottom:1px solid #f1f5f9;font-weight:700;color:#0f172a;font-size:0.95rem;">
                    Order History
                </div>
                <div style="overflow-x:auto;">
                    <table style="width:100%;border-collapse:collapse;font-size:0.85rem;text-align:left;">
                        <thead>
                            <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;color:#64748b;font-size:0.75rem;text-transform:uppercase;">
                                <th style="padding:12px 16px;">Order No</th>
                                <th style="padding:12px 14px;">Date</th>
                                <th style="padding:12px 14px;">Type</th>
                                <th style="padding:12px 14px;">Status</th>
                                <th style="padding:12px 14px;text-align:right;">Total</th>
                                <th style="padding:12px 16px;text-align:right;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr style="border-bottom:1px solid #f1f5f9;">
                                    <td style="padding:12px 16px;font-weight:700;color:#0f172a;">
                                        #{{ $order->order_number }}
                                    </td>
                                    <td style="padding:12px 14px;color:#64748b;">
                                        {{ $order->created_at->format('d M, Y') }}
                                    </td>
                                    <td style="padding:12px 14px;">
                                        <span style="text-transform:capitalize;font-weight:600;color:#475569;">{{ $order->order_type }}</span>
                                    </td>
                                    <td style="padding:12px 14px;">
                                        @php
                                            $stColor = $order->orderStatus->color ?? '#64748b';
                                            $stName = $order->orderStatus->name ?? ucfirst($order->status);
                                        @endphp
                                        <span style="display:inline-block;padding:2px 8px;border-radius:12px;font-size:0.75rem;font-weight:700;background:{{ $stColor }}20;color:{{ $stColor }};">
                                            {{ $stName }}
                                        </span>
                                    </td>
                                    <td style="padding:12px 14px;text-align:right;font-weight:700;color:#0f172a;">
                                        ৳{{ number_format($order->grand_total, 2) }}
                                    </td>
                                    <td style="padding:12px 16px;text-align:right;">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" style="padding:5px 10px;border-radius:6px;background:#f1f5f9;color:#334155;font-weight:600;font-size:0.75rem;text-decoration:none;">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="padding:30px;text-align:center;color:#94a3b8;">
                                        No orders placed yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($orders->hasPages())
                    <div style="padding:12px 16px;border-top:1px solid #f1f5f9;">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
