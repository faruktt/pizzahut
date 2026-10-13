@extends('layouts.admin')

@section('title', 'Admin Dashboard | Italian Pizza')
@section('page-title', 'Overview & Restaurant Analytics')

@push('admin-styles')
<style>
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 28px;
    }

    .kpi-card {
        background: #ffffff;
        border: 1px solid var(--border-light);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        transition: transform 0.2s;
    }

    .kpi-card:hover { transform: translateY(-2px); }

    .kpi-icon-box {
        width: 52px; height: 52px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .kpi-val { font-size: 1.45rem; font-weight: 800; color: #0f172a; line-height: 1.2; }
    .kpi-label { font-size: 0.76rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

    .floor-tables-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 14px;
        margin-bottom: 28px;
    }

    .floor-table-card {
        background: #ffffff;
        border: 2px solid var(--border-light);
        border-radius: 12px;
        padding: 14px;
        text-align: center;
        transition: all 0.2s;
        display: flex; flex-direction: column; gap: 6px;
    }

    .floor-table-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); transform: translateY(-2px); }
    .floor-table-card.available { border-color: #86efac; background: #f0fdf4; }
    .floor-table-card.occupied { border-color: #fca5a5; background: #fef2f2; }

    .table-number-text { font-size: 1rem; font-weight: 800; color: #0f172a; }
    .table-capacity-text { font-size: 0.73rem; color: #64748b; }
    .table-running-bill { font-size: 0.83rem; font-weight: 800; color: var(--admin-primary); margin-top: 4px; }

    .btn-table-action {
        margin-top: 8px; padding: 5px 10px;
        border-radius: 6px; font-size: 0.76rem; font-weight: 700;
        border: none; cursor: pointer;
        background: #0284c7; color: #fff;
    }

    @media (max-width: 1024px) {
        .kpi-grid { grid-template-columns: repeat(2, 1fr); }
        .floor-tables-grid { grid-template-columns: repeat(3, 1fr); }
    }
</style>
@endpush

@section('admin-content')
<div>

    {{-- KPI CARDS --}}
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-icon-box" style="background:#e0f2fe;color:#0284c7;">
                <i class="fa-solid fa-bangladeshi-taka-sign"></i>
            </div>
            <div>
                <div class="kpi-val">৳ {{ number_format($todaySales) }}</div>
                <div class="kpi-label">Today's Sales</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon-box" style="background:#fef3c7;color:#d97706;">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <div>
                <div class="kpi-val">{{ $todayOrdersCount }}</div>
                <div class="kpi-label">Today's Orders</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon-box" style="background:#fee2e2;color:#dc2626;">
                <i class="fa-solid fa-table-cells"></i>
            </div>
            <div>
                <div class="kpi-val">{{ $activeTablesCount }} / {{ $totalTablesCount }}</div>
                <div class="kpi-label">Occupied Tables</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon-box" style="background:#dcfce7;color:#16a34a;">
                <i class="fa-solid fa-pizza-slice"></i>
            </div>
            <div>
                <div class="kpi-val">{{ $totalProducts }}</div>
                <div class="kpi-label">Menu Items</div>
            </div>
        </div>
    </div>

    {{-- LIVE RESTAURANT FLOOR --}}
    <div class="card-panel">
        <div class="card-panel-header">
            <div>
                <h3 class="card-panel-title"><i class="fa-solid fa-utensils"></i> Live Restaurant Floor & Tables</h3>
                <p style="font-size:0.78rem;color:#64748b;margin-top:2px;">Click any table to manage orders via POS</p>
            </div>
            <a href="{{ route('admin.pos.index') }}" class="btn-quick-pos" style="padding:6px 14px;font-size:0.8rem;">
                <i class="fa-solid fa-cash-register"></i> Open Full POS
            </a>
        </div>

        <div class="card-panel-body">
            <div class="floor-tables-grid">
                @foreach($tables as $tbl)
                    <div class="floor-table-card {{ $tbl->status }}">
                        <div class="table-number-text">
                            <i class="fa-solid fa-chair" style="font-size:0.85rem;"></i> {{ $tbl->table_number }}
                        </div>
                        <div class="table-capacity-text"><i class="fa-solid fa-users" style="font-size:0.7rem;"></i> {{ $tbl->capacity }} Persons</div>

                        <span class="badge badge-{{ $tbl->status }}">{{ ucfirst($tbl->status) }}</span>

                        @if($tbl->status === 'occupied' && $tbl->activeOrder)
                            <div class="table-running-bill">
                                <i class="fa-solid fa-file-invoice-dollar"></i> ৳{{ number_format($tbl->activeOrder->total_amount) }}
                            </div>
                        @else
                            <div style="font-size:0.73rem;color:#15803d;margin-top:4px;">
                                <i class="fa-solid fa-circle-check"></i> Ready for Guests
                            </div>
                        @endif

                        <a href="{{ route('admin.pos.index') }}?table={{ $tbl->id }}" class="btn-table-action">
                            <i class="fa-solid fa-cash-register"></i> Manage
                        </a>
                    </div>
                @endforeach

                @if($tables->isEmpty())
                    <div style="grid-column:1/-1;text-align:center;padding:32px;color:#94a3b8;">
                        <i class="fa-solid fa-table-cells" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                        No tables added yet. <a href="{{ route('admin.tables.index') }}">Add tables →</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- RECENT ORDERS --}}
    <div class="card-panel">
        <div class="card-panel-header">
            <h3 class="card-panel-title"><i class="fa-solid fa-clock-rotate-left"></i> Recent Orders</h3>
            <a href="{{ route('admin.orders.index') }}" style="font-size:0.82rem;font-weight:700;color:var(--admin-primary);">
                View All <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Type</th>
                        <th>Customer / Table</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td>
                                <strong>#{{ $order->order_number }}</strong>
                                <div style="font-size:0.72rem;color:#94a3b8;">{{ $order->created_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                <span style="font-weight:700;text-transform:uppercase;font-size:0.75rem;">
                                    @if($order->order_type === 'delivery')
                                        <i class="fa-solid fa-motorcycle" style="color:#0284c7;"></i> Delivery
                                    @elseif($order->order_type === 'takeaway')
                                        <i class="fa-solid fa-bag-shopping" style="color:#7c3aed;"></i> Takeaway
                                    @else
                                        <i class="fa-solid fa-utensils" style="color:#c8102e;"></i> Dine-in
                                    @endif
                                </span>
                            </td>
                            <td>
                                @if($order->order_type === 'dine_in' && $order->table)
                                    <strong><i class="fa-solid fa-chair"></i> {{ $order->table->table_number }}</strong>
                                @else
                                    <strong>{{ $order->customer_name ?: 'Guest' }}</strong>
                                    <div style="font-size:0.75rem;color:#64748b;">{{ $order->customer_phone }}</div>
                                @endif
                            </td>
                            <td>{{ $order->items->count() }} item(s)</td>
                            <td><strong style="color:var(--admin-primary);">৳ {{ number_format($order->total_amount) }}</strong></td>
                            <td>
                                <span class="badge {{ $order->payment_status === 'paid' ? 'badge-completed' : 'badge-pending' }}">
                                    <i class="fa-solid {{ $order->payment_status === 'paid' ? 'fa-circle-check' : 'fa-clock' }}"></i>
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $order->order_status }}">{{ ucfirst($order->order_status) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary btn-sm">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;padding:40px;color:#94a3b8;">
                                <i class="fa-solid fa-inbox" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                                No orders yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
