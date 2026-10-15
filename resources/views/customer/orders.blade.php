@extends('layouts.app')

@section('title', 'My Orders | Italian Pizza')

@push('styles')
<style>
    .customer-orders {
        padding: 32px 0 60px;
    }

    .orders-header {
        margin-bottom: 24px;
    }

    .orders-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 4px;
    }

    .orders-subtitle {
        color: var(--text-muted);
        font-size: 0.95rem;
    }

    /* Orders Table */
    .orders-table-wrap {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 14px;
        overflow: hidden;
    }

    .order-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr auto;
        gap: 16px;
        padding: 18px 24px;
        border-bottom: 1px solid var(--border-color);
        align-items: center;
        transition: background 0.15s;
    }

    .order-row:last-child {
        border-bottom: none;
    }

    .order-row:hover {
        background: #fafafa;
    }

    .order-row-header {
        background: #f8f9fa;
        font-weight: 700;
        font-size: 0.75rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .order-id {
        font-weight: 700;
        color: var(--brand-red);
        font-size: 0.9rem;
    }

    .order-id small {
        display: block;
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 400;
        margin-top: 2px;
    }

    .order-items {
        color: var(--text-dark);
        font-size: 0.85rem;
    }

    .order-items-count {
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    .order-total {
        font-weight: 700;
        color: var(--text-dark);
        font-size: 0.95rem;
    }

    .order-date {
        color: var(--text-muted);
        font-size: 0.85rem;
    }

    .order-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .status-pending { background: #fef3c7; color: #b45309; }
    .status-preparing { background: #e0f2fe; color: #0369a1; }
    .status-ready { background: #ede9fe; color: #6d28d9; }
    .status-served { background: #f3e8ff; color: #7e22ce; }
    .status-completed { background: #dcfce7; color: #15803d; }
    .status-delivered { background: #dcfce7; color: #15803d; }
    .status-cancelled { background: #fee2e2; color: #b91c1c; }

    .order-actions {
        display: flex;
        gap: 8px;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 0.78rem;
        font-weight: 600;
        border-radius: 6px;
    }

    .btn-outline {
        background: transparent;
        border: 1px solid var(--border-color);
        color: var(--text-dark);
    }

    .btn-outline:hover {
        background: var(--brand-red-light);
        border-color: var(--brand-red);
        color: var(--brand-red);
    }

    .empty-state {
        text-align: center;
        padding: 60px 24px;
        color: var(--text-muted);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 16px;
        color: var(--border-color);
    }

    .empty-state h3 {
        font-size: 1.1rem;
        color: var(--text-dark);
        margin-bottom: 8px;
    }

    .empty-state p {
        margin-bottom: 20px;
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 6px;
        padding: 24px;
    }

    .pagination a,
    .pagination span {
        padding: 8px 14px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-dark);
        text-decoration: none;
        transition: all 0.2s;
    }

    .pagination a:hover {
        background: var(--brand-red);
        border-color: var(--brand-red);
        color: #fff;
    }

    .pagination .current {
        background: var(--brand-red);
        border-color: var(--brand-red);
        color: #fff;
    }

    @media (max-width: 900px) {
        .order-row {
            grid-template-columns: 1fr 1fr;
            grid-template-areas:
                "id date"
                "items items"
                "total status"
                "actions actions";
        }
        .order-id { grid-area: id; }
        .order-date { grid-area: date; text-align: right; }
        .order-items { grid-area: items; }
        .order-total { grid-area: total; }
        .order-status { grid-area: status; }
        .order-actions { grid-area: actions; justify-content: flex-start; }
        .order-row-header { display: none; }
    }

    @media (max-width: 600px) {
        .order-row {
            grid-template-columns: 1fr;
            grid-template-areas:
                "id"
                "date"
                "items"
                "total"
                "status"
                "actions";
        }
        .order-date { text-align: left; }
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="customer-orders">
        <div class="orders-header">
            <h1 class="orders-title">My Orders</h1>
            <p class="orders-subtitle">Track and manage all your orders</p>
        </div>

        <div class="orders-table-wrap">
            <div class="order-row order-row-header">
                <div>Order ID</div>
                <div>Date</div>
                <div>Items</div>
                <div>Total</div>
                <div>Status</div>
                <div>Action</div>
            </div>

            @if($orders->isNotEmpty())
                @foreach($orders as $order)
                <div class="order-row">
                    <div class="order-id">
                        #{{ $order->order_number }}
                        <small>Type: {{ ucfirst($order->order_type) }}</small>
                    </div>
                    <div class="order-date">{{ $order->created_at->format('M d, Y H:i') }}</div>
                    <div class="order-items">
                        @foreach($order->items->take(2) as $item)
                            <div>{{ $item->product_name }}{{ $item->size_name ? ' (' . $item->size_name . ')' : '' }}{{ $item->crust_name ? ' - ' . $item->crust_name : '' }} (x{{ $item->quantity }})</div>
                        @endforeach
                        @if($order->items->count() > 2)
                            <div class="order-items-count">+{{ $order->items->count() - 2 }} more items</div>
                        @endif
                    </div>
                    <div class="order-total">৳{{ number_format($order->total_amount) }}</div>
                    <div class="d-flex align-items-center gap-8">
                        <span class="order-status status-{{ strtolower($order->order_status) }}">
                            {{ ucfirst($order->order_status) }}
                        </span>
                        <a href="{{ route('customer.orders.show', $order->id) }}" class="btn-sm btn-outline">
                            <i class="fa-solid fa-eye"></i> Details
                        </a>
                    </div>
                </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="fa-solid fa-receipt"></i>
                    <h3>No orders yet</h3>
                    <p>Your order history will appear here once you place an order.</p>
                    <a href="{{ route('home') }}" class="btn-signin"><i class="fa-solid fa-pizza-slice"></i> Start Ordering</a>
                </div>
            @endif
        </div>

        @if($orders->hasPages())
        <div class="pagination">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection