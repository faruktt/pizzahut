@extends('layouts.app')

@section('title', 'Order Confirmed #' . $order->order_number . ' | Pizza Hut Bangladesh')

@push('styles')
<style>
    .order-success-wrap {
        padding: 40px 0 80px 0;
        max-width: 800px;
        margin: 0 auto;
    }

    .success-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #eaeaea;
        padding: 36px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        text-align: center;
    }

    .success-badge-icon {
        width: 72px;
        height: 72px;
        background: #e8f5e9;
        color: #2e7d32;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        margin: 0 auto 16px auto;
    }

    .success-title {
        font-size: 1.8rem;
        font-weight: 900;
        color: #222;
        margin-bottom: 6px;
    }

    .order-num-pill {
        display: inline-block;
        background: var(--brand-red-light);
        color: var(--brand-red);
        font-weight: 800;
        font-size: 1rem;
        padding: 6px 18px;
        border-radius: 20px;
        margin-bottom: 24px;
    }

    /* Live Progress Tracker */
    .tracker-steps {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        position: relative;
        margin: 32px 0 40px 0;
    }

    .tracker-steps::before {
        content: '';
        position: absolute;
        top: 18px;
        left: 12%;
        right: 12%;
        height: 4px;
        background: #e0e0e0;
        z-index: 1;
    }

    .step-item {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #f0f0f0;
        color: #888;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        margin-bottom: 8px;
        border: 3px solid #fff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .step-item.active .step-circle {
        background: var(--brand-red);
        color: #fff;
    }

    .step-label {
        font-size: 0.78rem;
        font-weight: 700;
        color: #555;
    }

    .receipt-box {
        background: #fbfbfb;
        border: 1px solid #eeeeee;
        border-radius: 12px;
        padding: 24px;
        text-align: left;
        margin-top: 24px;
    }

    .receipt-header {
        border-bottom: 1px dashed #ddd;
        padding-bottom: 12px;
        margin-bottom: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .receipt-item-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
        font-size: 0.88rem;
    }
</style>
@endpush

@section('content')
<div class="container order-success-wrap">
    <div class="success-card">
        <div class="success-badge-icon"><i class="fa-solid fa-check"></i></div>
        <h1 class="success-title">Order Received!</h1>
        <p style="color: #666; font-size: 0.95rem;">Thank you! Your pizza is now being prepared with authentic Italian passion.</p>
        <div class="order-num-pill">Order #{{ $order->order_number }}</div>

        <!-- Live Status Tracker -->
        <div class="tracker-steps">
            <div class="step-item active">
                <div class="step-circle">1</div>
                <div class="step-label">Confirmed</div>
            </div>
            <div class="step-item active">
                <div class="step-circle">2</div>
                <div class="step-label">In Oven</div>
            </div>
            <div class="step-item {{ in_array($order->order_status, ['ready', 'served', 'completed']) ? 'active' : '' }}">
                <div class="step-circle">3</div>
                <div class="step-label">Ready / Pack</div>
            </div>
            <div class="step-item {{ $order->order_status === 'completed' ? 'active' : '' }}">
                <div class="step-circle">4</div>
                <div class="step-label">Delivered</div>
            </div>
        </div>

        <!-- Receipt Box -->
        <div class="receipt-box">
            <div class="receipt-header">
                <div>
                    <strong style="font-size: 1.1rem; color: #222;">Order Summary</strong>
                    <div style="font-size: 0.78rem; color: #777;">Type: {{ strtoupper($order->order_type) }} • Pay: {{ strtoupper($order->payment_method) }}</div>
                </div>
                <div>
                    <span style="display:inline-block; padding:4px 10px; border-radius:12px; font-size:0.75rem; font-weight:700; background:#e8f5e9; color:#2e7d32;">
                        Status: {{ ucfirst($order->order_status) }}
                    </span>
                </div>
            </div>

            <!-- Items -->
            <div style="margin-bottom: 16px;">
                @foreach($order->items as $item)
                    <div class="receipt-item-row">
                        <div>
                            <strong>{{ $item->quantity }}x</strong> {{ $item->product_name }}
                            <div style="font-size: 0.76rem; color: #777;">
                                Size: {{ $item->size_name }} • Crust: {{ $item->crust_name }}
                            </div>
                            @if($item->combo_details)
                                <div style="font-size: 0.76rem; color: #c8102e; font-weight: 700; margin-top: 2px;">
                                    <i class="fa-solid fa-pizza-slice" style="margin-right:4px;"></i> Combo: {{ $item->combo_details }}
                                </div>
                            @endif
                            @if($item->addons->isNotEmpty())
                                <div style="font-size: 0.72rem; color: #555; margin-top: 2px;">
                                    + Add-ons: {{ $item->addons->pluck('add_on_name')->join(', ') }}
                                </div>
                            @endif
                        </div>
                        <strong style="color: #222;">৳ {{ number_format($item->total_price) }}</strong>
                    </div>
                @endforeach
            </div>

            <!-- Totals -->
            <div style="border-top: 1px dashed #ddd; padding-top: 12px; font-size: 0.88rem;">
                <div style="display:flex; justify-content:space-between; margin-bottom: 6px;">
                    <span>Subtotal:</span>
                    <strong>৳ {{ number_format($order->subtotal) }}</strong>
                </div>
                @if($order->coupon_discount > 0)
                    <div style="display:flex; justify-content:space-between; margin-bottom: 6px; color:#16a34a;">
                        <span><i class="fa-solid fa-ticket" style="margin-right:4px;"></i> Coupon Discount ({{ $order->coupon_code }}):</span>
                        <strong>-৳ {{ number_format($order->coupon_discount) }}</strong>
                    </div>
                @endif
                @if($order->points_discount > 0)
                    <div style="display:flex; justify-content:space-between; margin-bottom: 6px; color:#16a34a;">
                        <span><i class="fa-solid fa-coins" style="margin-right:4px; color:#f59e0b;"></i> Points Discount ({{ $order->points_used }} pts):</span>
                        <strong>-৳ {{ number_format($order->points_discount) }}</strong>
                    </div>
                @endif
                <div style="display:flex; justify-content:space-between; margin-bottom: 6px;">
                    <span>Delivery Fee:</span>
                    <strong>৳ {{ number_format($order->delivery_charge) }}</strong>
                </div>
                <div style="display:flex; justify-content:space-between; margin-bottom: 6px;">
                    <span>VAT (5%):</span>
                    <strong>৳ {{ number_format($order->vat_tax) }}</strong>
                </div>
                <div style="display:flex; justify-content:space-between; font-size: 1.25rem; font-weight: 900; color: var(--brand-red); border-top: 1px solid #ddd; padding-top: 8px; margin-top: 8px;">
                    <span>Total Amount:</span>
                    <span>৳ {{ number_format($order->total_amount) }}</span>
                </div>
            </div>

            @if($order->points_earned > 0)
                <div style="margin-top:14px; background:#ecfdf5; border:1px solid #a7f3d0; border-radius:8px; padding:10px 14px; color:#065f46; font-size:0.85rem; font-weight:700; display:flex; align-items:center; gap:8px;">
                    <i class="fa-solid fa-coins" style="color:#10b981; font-size:1.1rem;"></i>
                    <span>Congratulations! You earned +{{ $order->points_earned }} Reward Points on this order!</span>
                </div>
            @endif

            @if($order->customer_address)
                <div style="margin-top: 16px; padding-top: 12px; border-top: 1px solid #eee; font-size: 0.82rem; color: #555;">
                    <strong>Delivery To:</strong> {{ $order->customer_name }} ({{ $order->customer_phone }})<br>
                    <span>{{ $order->customer_address }}</span>
                </div>
            @endif
        </div>

        <div style="margin-top: 24px; display: flex; gap: 12px; justify-content: center; flex-wrap:wrap;">
            <a href="{{ route('home') }}" class="btn-signin" style="padding: 12px 28px; display:inline-flex; align-items:center; gap:8px;">
                <i class="fa-solid fa-pizza-slice"></i> <span>Order More Pizzas</span>
            </a>
            <button type="button" onclick="window.print()" style="background:#f1f2f4; border:1px solid #ddd; padding:12px 24px; border-radius:6px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:8px;">
                <i class="fa-solid fa-print"></i> <span>Print Receipt</span>
            </button>
        </div>
    </div>
</div>
@endsection
