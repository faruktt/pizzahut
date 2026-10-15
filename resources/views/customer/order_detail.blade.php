@extends('layouts.app')

@section('title', 'Order #' . $order->order_number . ' | Italian Pizza')

@push('styles')
<style>
    .order-detail {
        padding: 32px 0 60px;
    }

    .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
        gap: 16px;
        flex-wrap: wrap;
    }

    .detail-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--text-dark);
    }

    .detail-meta {
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    .detail-meta strong {
        color: var(--text-dark);
    }

    .back-link {
        color: var(--brand-red);
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.9rem;
    }

    .back-link:hover {
        text-decoration: underline;
    }

    /* Order Info Cards */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .info-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 20px;
    }

    .info-card h4 {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .info-card p {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text-dark);
    }

    .info-card .status-badge {
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

    /* Items Table */
    .items-table {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .items-header {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        gap: 16px;
        padding: 16px 20px;
        background: #f8f9fa;
        border-bottom: 1px solid var(--border-color);
        font-weight: 700;
        font-size: 0.75rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .item-row {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        gap: 16px;
        padding: 16px 20px;
        border-bottom: 1px solid var(--border-color);
        align-items: center;
    }

    .item-row:last-child {
        border-bottom: none;
    }

    .item-name {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.9rem;
    }

    .item-meta {
        font-size: 0.78rem;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .item-addons {
        font-size: 0.72rem;
        color: var(--brand-red);
        margin-top: 4px;
    }

    .item-qty {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f1f2f4;
        border-radius: 6px;
        padding: 4px 10px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .item-price {
        font-weight: 600;
        color: var(--text-dark);
    }

    .item-total {
        font-weight: 700;
        color: var(--brand-red);
        font-size: 0.95rem;
    }

    /* Bill Summary */
    .bill-summary {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 24px;
        max-width: 400px;
    }

    .bill-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        font-size: 0.9rem;
        border-bottom: 1px solid var(--border-color);
    }

    .bill-row:last-child {
        border-bottom: none;
        font-weight: 800;
        font-size: 1.1rem;
        color: var(--brand-red);
        margin-top: 8px;
        padding-top: 16px;
    }

    .bill-label {
        color: var(--text-muted);
    }

    .bill-value {
        font-weight: 600;
        color: var(--text-dark);
    }

    .bill-row.total .bill-label,
    .bill-row.total .bill-value {
        font-weight: 800;
        color: var(--brand-red);
    }

    .payment-badge {
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

    .payment-paid { background: #dcfce7; color: #15803d; }
    .payment-pending { background: #fef3c7; color: #b45309; }
    .payment-cod { background: #e0f2fe; color: #0369a1; }

    .instructions-box {
        background: #f8f9fa;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 16px;
        margin-top: 16px;
    }

    .instructions-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    @media (max-width: 768px) {
        .items-header { display: none; }
        .item-row {
            grid-template-columns: 1fr;
            gap: 8px;
        }
        .item-qty, .item-price, .item-total {
            display: flex;
            justify-content: space-between;
        }
        .item-qty::before { content: 'Qty:'; color: var(--text-muted); font-weight: 500; }
        .item-price::before { content: 'Unit Price:'; color: var(--text-muted); font-weight: 500; }
        .item-total::before { content: 'Total:'; color: var(--text-muted); font-weight: 500; }
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="order-detail">
        <!-- Header -->
        <div class="detail-header">
            <div>
                <h1 class="detail-title">Order Details</h1>
                <p class="detail-meta">Order <strong>#{{ $order->order_number }}</strong> placed on <strong>{{ $order->created_at->format('M d, Y \a\t H:i') }}</strong></p>
            </div>
            <a href="{{ route('customer.orders') }}" class="back-link">
                <i class="fa-solid fa-arrow-left"></i> Back to Orders
            </a>
        </div>

        <!-- Info Cards -->
        <div class="info-grid">
            <div class="info-card">
                <h4>Order Status</h4>
                @php
                    $st = $order->status_object;
                @endphp
                @if($st)
                    <span class="status-badge" style="background: {{ $st->bg_color }}; color: {{ $st->color }}; border: 1px solid {{ $st->color }}33; font-weight:700;">
                        <span style="display:inline-block; width:6px; height:6px; border-radius:50%; background:{{ $st->color }}; margin-right:4px;"></span>
                        {{ $st->name }}
                    </span>
                @else
                    <span class="status-badge status-{{ strtolower($order->order_status) }}">
                        {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                    </span>
                @endif
            </div>
            <div class="info-card">
                <h4>Order Type</h4>
                <p>{{ ucfirst($order->order_type) }}</p>
            </div>
            <div class="info-card">
                <h4>Payment Status</h4>
                <span class="payment-badge payment-{{ strtolower($order->payment_status) }}">
                    {{ ucfirst($order->payment_status) }}
                </span>
            </div>
            <div class="info-card">
                <h4>Payment Method</h4>
                <p>{{ ucfirst($order->payment_method) }}</p>
            </div>
            @if($order->restaurant_table_id)
            <div class="info-card">
                <h4>Table</h4>
                <p>Table #{{ $order->restaurantTable->table_number ?? $order->restaurant_table_id }}</p>
            </div>
            @endif
        </div>

        <!-- Items Table -->
        <div class="items-table">
            <div class="items-header">
                <div>Item</div>
                <div>Qty</div>
                <div>Unit Price</div>
                <div>Total</div>
            </div>

            @foreach($order->items as $item)
            <div class="item-row">
                <div>
                    <div class="item-name">{{ $item->product_name }}</div>
                    @if($item->size_name || $item->crust_name)
                        <div class="item-meta">
                            @if($item->size_name){{ $item->size_name }}@endif
                            @if($item->size_name && $item->crust_name) / @endif
                            @if($item->crust_name){{ $item->crust_name }}@endif
                        </div>
                    @endif
                    @if($item->addons && $item->addons->count() > 0)
                        <div class="item-addons">
                            <i class="fa-solid fa-plus"></i> {{ $item->addons->pluck('add_on_name')->join(', ') }}
                        </div>
                    @endif
                    @if($item->special_instructions)
                        <div class="item-meta" style="color:#3b82f6;">
                            <i class="fa-solid fa-comment-dots"></i> {{ $item->special_instructions }}
                        </div>
                    @endif
                </div>
                <div class="item-qty">{{ $item->quantity }}</div>
                <div class="item-price">৳{{ number_format($item->unit_price) }}</div>
                <div class="item-total">৳{{ number_format($item->total_price) }}</div>
            </div>
            @endforeach
        </div>

        <!-- Bill Summary & Delivery Info -->
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 24px;">
            <div>
                <div class="bill-summary">
                    <div class="bill-row">
                        <span class="bill-label">Subtotal</span>
                        <span class="bill-value">৳{{ number_format($order->subtotal) }}</span>
                    </div>
                    @if($order->coupon_discount > 0)
                    <div class="bill-row" style="color:#16a34a;">
                        <span class="bill-label"><i class="fa-solid fa-ticket"></i> Coupon ({{ $order->coupon_code }})</span>
                        <span class="bill-value">-৳{{ number_format($order->coupon_discount) }}</span>
                    </div>
                    @endif
                    @if($order->points_discount > 0)
                    <div class="bill-row" style="color:#16a34a;">
                        <span class="bill-label"><i class="fa-solid fa-coins" style="color:#f59e0b;"></i> Points ({{ $order->points_used }} pts)</span>
                        <span class="bill-value">-৳{{ number_format($order->points_discount) }}</span>
                    </div>
                    @endif
                    @if($order->delivery_charge > 0)
                    <div class="bill-row">
                        <span class="bill-label">Delivery Charge</span>
                        <span class="bill-value">৳{{ number_format($order->delivery_charge) }}</span>
                    </div>
                    @endif
                    @if($order->vat_tax > 0)
                    <div class="bill-row">
                        <span class="bill-label">VAT/Tax</span>
                        <span class="bill-value">৳{{ number_format($order->vat_tax) }}</span>
                    </div>
                    @endif
                    <div class="bill-row total">
                        <span class="bill-label">Total Amount</span>
                        <span class="bill-value">৳{{ number_format($order->total_amount) }}</span>
                    </div>
                    @if($order->points_earned > 0)
                    <div style="margin-top:10px; background:#ecfdf5; border:1px solid #a7f3d0; border-radius:6px; padding:6px 10px; font-size:0.8rem; color:#065f46; font-weight:700;">
                        <i class="fa-solid fa-coins" style="color:#10b981;"></i> +{{ $order->points_earned }} Reward Points earned
                    </div>
                    @endif
                </div>
            </div>

            <div>
                <div class="info-card" style="height: 100%;">
                    <h4>Delivery Address</h4>
                    <p style="white-space: pre-line;">{{ $order->delivery_address ?? 'Takeaway / Dine-in (No delivery address)' }}</p>
                </div>
                <div class="info-card" style="margin-top: 16px; height: 100%;">
                    <h4>Contact</h4>
                    <p>{{ $order->customer_name ?? $order->user->name }}<br>{{ $order->customer_phone ?? $order->user->phone }}</p>
                </div>
                @if($order->items->where('special_instructions', '!=', '')->count() > 0)
                <div class="instructions-box">
                    <div class="instructions-label">Special Instructions</div>
                    @foreach($order->items as $item)
                        @if($item->special_instructions)
                            <div style="margin-bottom: 8px; padding: 8px; background: #fff; border-radius: 6px; font-size: 0.85rem;">
                                <strong>{{ $item->product_name }}:</strong> {{ $item->special_instructions }}
                            </div>
                        @endif
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection