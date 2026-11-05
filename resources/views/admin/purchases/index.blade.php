@extends('layouts.admin')
@section('title', 'Purchases | Italian Pizza Hut Admin')
@section('page-title', 'Stock Purchases')

@section('admin-content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
    <div>
        <h2 style="font-size:1.25rem;font-weight:800;color:#0f172a;margin-bottom:4px;">Stock Purchases</h2>
        <p style="font-size:0.8rem;color:#64748b;margin:0;">Procure raw materials from suppliers and automatically update inventory stock</p>
    </div>
    <a href="{{ route('admin.purchases.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> New Purchase
    </a>
</div>

<!-- Financial Summary Cards -->
<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:16px;margin-bottom:20px;">
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:16px 20px;display:flex;align-items:center;gap:16px;">
        <div style="width:46px;height:46px;border-radius:10px;background:#eff6ff;color:#2563eb;display:flex;align-items:center;justify-content:center;font-size:1.2rem;">
            <i class="fa-solid fa-dolly"></i>
        </div>
        <div>
            <div style="font-size:0.75rem;color:#64748b;font-weight:700;text-transform:uppercase;">Total Purchased</div>
            <div style="font-size:1.35rem;font-weight:800;color:#0f172a;">৳{{ number_format($totalPurchased, 2) }}</div>
        </div>
    </div>

    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:16px 20px;display:flex;align-items:center;gap:16px;">
        <div style="width:46px;height:46px;border-radius:10px;background:#f0fdf4;color:#16a34a;display:flex;align-items:center;justify-content:center;font-size:1.2rem;">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div>
            <div style="font-size:0.75rem;color:#64748b;font-weight:700;text-transform:uppercase;">Total Paid</div>
            <div style="font-size:1.35rem;font-weight:800;color:#16a34a;">৳{{ number_format($totalPaid, 2) }}</div>
        </div>
    </div>

    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:16px 20px;display:flex;align-items:center;gap:16px;">
        <div style="width:46px;height:46px;border-radius:10px;background:#fef2f2;color:#dc2626;display:flex;align-items:center;justify-content:center;font-size:1.2rem;">
            <i class="fa-solid fa-clock"></i>
        </div>
        <div>
            <div style="font-size:0.75rem;color:#64748b;font-weight:700;text-transform:uppercase;">Due Amount</div>
            <div style="font-size:1.35rem;font-weight:800;color:{{ $totalDue > 0 ? '#dc2626' : '#16a34a' }};">
                ৳{{ number_format($totalDue, 2) }}
            </div>
        </div>
    </div>
</div>

<div class="card-panel">
    <div class="card-panel-header" style="flex-wrap:wrap;gap:12px;">
        <span class="card-panel-title"><i class="fa-solid fa-cart-shopping"></i> Purchase History</span>

        <form method="GET" action="{{ route('admin.purchases.index') }}" style="display:flex;gap:8px;flex-wrap:wrap;">
            <select name="supplier_id" class="form-control" style="width:160px;padding:6px 10px;font-size:0.82rem;">
                <option value="">All Suppliers</option>
                @foreach($suppliers as $s)
                    <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                @endforeach
            </select>

            <select name="payment_status" class="form-control" style="width:140px;padding:6px 10px;font-size:0.82rem;">
                <option value="">All Statuses</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="partial" {{ request('payment_status') == 'partial' ? 'selected' : '' }}>Partial</option>
                <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
            </select>

            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search PUR#..." style="width:140px;padding:6px 10px;font-size:0.82rem;">
            <button type="submit" class="btn btn-secondary btn-sm"><i class="fa-solid fa-filter"></i></button>
            @if(request()->anyFilled(['supplier_id', 'payment_status', 'search']))
                <a href="{{ route('admin.purchases.index') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-xmark"></i></a>
            @endif
        </form>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Invoice #</th>
                <th>Supplier</th>
                <th>Date</th>
                <th>Items</th>
                <th>Total</th>
                <th>Paid</th>
                <th>Due</th>
                <th>Status</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($purchases as $purchase)
            <tr>
                <td>
                    <a href="{{ route('admin.purchases.show', $purchase->id) }}" style="font-weight:700;color:var(--admin-primary);">
                        {{ $purchase->purchase_number }}
                    </a>
                </td>
                <td>
                    @if($purchase->supplier)
                        <strong>{{ $purchase->supplier->name }}</strong>
                        @if($purchase->supplier->company_name)
                            <div style="font-size:0.75rem;color:#64748b;">{{ $purchase->supplier->company_name }}</div>
                        @endif
                    @else
                        <span style="color:#94a3b8;">Walk-in / Cash Vendor</span>
                    @endif
                </td>
                <td>{{ $purchase->purchase_date->format('d M, Y') }}</td>
                <td>
                    <span class="badge" style="background:#f1f5f9;color:#334155;">
                        {{ $purchase->items->count() }} items
                    </span>
                </td>
                <td><strong>৳{{ number_format($purchase->total_amount, 2) }}</strong></td>
                <td style="color:#16a34a;font-weight:600;">৳{{ number_format($purchase->paid_amount, 2) }}</td>
                <td>
                    @if($purchase->due_amount > 0)
                        <span style="color:#dc2626;font-weight:700;">৳{{ number_format($purchase->due_amount, 2) }}</span>
                    @else
                        <span style="color:#16a34a;font-weight:600;">৳0.00</span>
                    @endif
                </td>
                <td>
                    @if($purchase->payment_status === 'paid')
                        <span class="badge" style="background:#dcfce7;color:#15803d;"><i class="fa-solid fa-check"></i> Paid</span>
                    @elseif($purchase->payment_status === 'partial')
                        <span class="badge" style="background:#fef3c7;color:#b45309;"><i class="fa-solid fa-circle-half-stroke"></i> Partial</span>
                    @else
                        <span class="badge" style="background:#fee2e2;color:#b91c1c;"><i class="fa-solid fa-clock"></i> Unpaid</span>
                    @endif
                </td>
                <td style="text-align:right;">
                    <a href="{{ route('admin.purchases.show', $purchase->id) }}" class="btn btn-secondary btn-sm" title="View Invoice">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                    <form action="{{ route('admin.purchases.destroy', $purchase->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this purchase? Item stock will be reverted.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align:center;padding:32px;color:#94a3b8;">
                    <i class="fa-solid fa-dolly" style="font-size:2rem;margin-bottom:8px;display:block;opacity:0.4;"></i>
                    No purchases recorded yet. Click "New Purchase" to add one.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($purchases->hasPages())
    <div style="padding:16px 20px;border-top:1px solid #f1f5f9;">
        {{ $purchases->links() }}
    </div>
    @endif
</div>
@endsection

