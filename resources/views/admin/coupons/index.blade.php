@extends('layouts.admin')
@section('title', 'Manage Coupons | Italian Pizza Admin')
@section('page-title', 'Coupons & Discounts')

@section('admin-content')
<div>
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:12px;">
        <p style="color:#64748b; font-size:0.9rem; margin:0;">
            Create discount coupon codes that customers can apply in their cart.
        </p>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add New Coupon
        </a>
    </div>

    <div class="card-panel">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Discount</th>
                    <th>Min Spend</th>
                    <th>Max Cap</th>
                    <th>Usage / Limit</th>
                    <th>Expiry</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                <tr>
                    <td>
                        <strong style="color:var(--admin-primary); font-size:0.95rem; letter-spacing:0.5px;">
                            <i class="fa-solid fa-ticket" style="margin-right:4px;"></i> {{ $coupon->code }}
                        </strong>
                        @if($coupon->description)
                            <div style="font-size:0.75rem; color:#64748b; margin-top:2px;">{{ $coupon->description }}</div>
                        @endif
                    </td>
                    <td>
                        <span class="badge" style="background:#e0f2fe; color:#0369a1; font-weight:800;">
                            @if($coupon->type === 'percentage')
                                {{ number_format($coupon->value, 0) }}% OFF
                            @else
                                ৳{{ number_format($coupon->value, 0) }} FLAT
                            @endif
                        </span>
                    </td>
                    <td>৳{{ number_format($coupon->min_order_amount, 0) }}</td>
                    <td>
                        @if($coupon->max_discount)
                            ৳{{ number_format($coupon->max_discount, 0) }}
                        @else
                            <span style="color:#94a3b8;">None</span>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $coupon->used_count }}</strong>
                        <span style="color:#64748b;">/ {{ $coupon->usage_limit ?: '∞' }}</span>
                    </td>
                    <td>
                        @if($coupon->expires_at)
                            @if($coupon->expires_at->isPast())
                                <span style="color:#ef4444; font-size:0.8rem; font-weight:700;">Expired ({{ $coupon->expires_at->format('d M Y') }})</span>
                            @else
                                <span style="color:#15803d; font-size:0.8rem;">{{ $coupon->expires_at->format('d M Y') }}</span>
                            @endif
                        @else
                            <span style="color:#64748b;">No Expiry</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.coupons.toggleStatus', $coupon->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="badge {{ $coupon->is_active ? 'badge-active' : 'badge-inactive' }}" style="cursor:pointer; border:none;">
                                {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                    </td>
                    <td>
                        <div style="display:flex; gap:6px;">
                            <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-secondary btn-sm" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete coupon {{ $coupon->code }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:40px; color:#94a3b8;">
                        <i class="fa-solid fa-ticket" style="font-size:2rem; display:block; margin-bottom:8px;"></i>
                        No coupons created yet. Click "Add New Coupon" above.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($coupons->hasPages())
            <div style="padding:16px;">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
