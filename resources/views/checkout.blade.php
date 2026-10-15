@extends('layouts.app')

@section('title', 'Review Payment & Address | Pizza Hut Bangladesh')

@push('styles')
<style>
    .checkout-wrap {
        padding: 32px 0 60px 0;
    }

    .checkout-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 32px;
        align-items: flex-start;
    }

    .checkout-card {
        background: #ffffff;
        border: 1px solid #eaeaea;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        margin-bottom: 24px;
    }

    .checkout-heading {
        font-size: 1.25rem;
        font-weight: 800;
        color: #222;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-label {
        display: block;
        font-size: 0.85rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 6px;
    }

    .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 0.9rem;
        outline: none;
        transition: border-color 0.2s;
    }

    .form-control:focus {
        border-color: var(--brand-red);
    }

    .type-select-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 16px;
    }

    .type-radio-box {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        font-weight: 700;
        color: #444;
        transition: all 0.2s;
    }

    .type-radio-box.active {
        border-color: var(--brand-red);
        background: var(--brand-red-light);
        color: var(--brand-red);
    }

    .payment-methods-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }

    .payment-method-card {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 14px 10px;
        text-align: center;
        cursor: pointer;
        font-weight: 700;
        font-size: 0.85rem;
        transition: all 0.2s;
    }

    .payment-method-card.active {
        border-color: var(--brand-red);
        background: var(--brand-red-light);
        color: var(--brand-red);
    }

    .summary-side-card {
        background: #f7f8fa;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #eaeaea;
    }

    .summary-item-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.85rem;
        padding: 8px 0;
        border-bottom: 1px dashed #e2e4e8;
    }

    .btn-place-order {
        display: block;
        width: 100%;
        background: var(--brand-red);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 14px;
        font-size: 1rem;
        font-weight: 800;
        cursor: pointer;
        margin-top: 20px;
        box-shadow: 0 4px 16px rgba(200, 16, 46, 0.3);
        transition: background 0.2s;
    }

    .btn-place-order:hover {
        background: var(--brand-red-dark);
    }

    @media (max-width: 900px) {
        .checkout-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="container checkout-wrap">
    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="checkout-grid">

            <!-- LEFT: Customer Delivery & Payment Information -->
            <div>
                <!-- 1. Order Type Selection -->
                <div class="checkout-card">
                    <h3 class="checkout-heading"><span><i class="fa-solid fa-motorcycle" style="color:var(--brand-red);"></i></span> 1. Select Delivery or Takeaway</h3>
                    
                    <div class="type-select-row">
                        <label class="type-radio-box active" id="labelDelivery">
                            <input type="radio" name="order_type" value="delivery" checked onchange="handleOrderTypeChange('delivery')" style="accent-color: var(--brand-red);">
                            <span>Home Delivery (+৳70)</span>
                        </label>
                        <label class="type-radio-box" id="labelTakeaway">
                            <input type="radio" name="order_type" value="takeaway" onchange="handleOrderTypeChange('takeaway')" style="accent-color: var(--brand-red);">
                            <span>Store Takeaway (Free)</span>
                        </label>
                    </div>
                </div>

                <!-- 2. Customer Contact & Address -->
                <div class="checkout-card">
                    <h3 class="checkout-heading"><span><i class="fa-solid fa-location-dot" style="color:var(--brand-red);"></i></span> 2. Contact & Address Details</h3>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="form-group">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="customer_name" class="form-control" value="{{ $user->name ?? old('customer_name') }}" placeholder="e.g. Tanvir Ahmed" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone Number *</label>
                            <input type="text" name="customer_phone" class="form-control" value="{{ old('customer_phone') }}" placeholder="e.g. 017xxxxxxxx" required>
                        </div>
                    </div>

                    <div class="form-group" id="addressGroup">
                        <label class="form-label">Delivery Address *</label>
                        <textarea name="customer_address" class="form-control" rows="3" placeholder="House / Flat / Road / Area details..." id="addressInput" required>{{ old('customer_address') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Delivery Location / Landmark</label>
                        <input type="text" name="delivery_location" class="form-control" value="Q8CX+3X6, Dhaka, Bangladesh">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Special Delivery Instructions</label>
                        <input type="text" name="special_instructions" class="form-control" placeholder="e.g. Please ring bell twice, call upon arrival">
                    </div>
                </div>

                <!-- 3. Payment Method -->
                <div class="checkout-card">
                    <h3 class="checkout-heading"><span><i class="fa-solid fa-credit-card" style="color:var(--brand-red);"></i></span> 3. Payment Method</h3>

                    <div class="payment-methods-grid">
                        <label class="payment-method-card active" id="payCash">
                            <input type="radio" name="payment_method" value="cash" checked onchange="handlePaymentChange('cash')" style="display:none;">
                            <div style="font-size: 1.5rem; margin-bottom: 6px; color:#16a34a;"><i class="fa-solid fa-money-bill-wave"></i></div>
                            <span>Cash on Delivery</span>
                        </label>

                        <label class="payment-method-card" id="payBkash">
                            <input type="radio" name="payment_method" value="bkash" onchange="handlePaymentChange('bkash')" style="display:none;">
                            <div style="font-size: 1.5rem; margin-bottom: 6px; color:#e11d48;"><i class="fa-solid fa-mobile-screen-button"></i></div>
                            <span>bKash / Nagad</span>
                        </label>

                        <label class="payment-method-card" id="payCard">
                            <input type="radio" name="payment_method" value="card" onchange="handlePaymentChange('card')" style="display:none;">
                            <div style="font-size: 1.5rem; margin-bottom: 6px; color:#2563eb;"><i class="fa-solid fa-credit-card"></i></div>
                            <span>Credit / Debit Card</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Order Summary Breakdown -->
            <div>
                <div class="summary-side-card">
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: #222; margin-bottom: 16px;">
                        Order Summary ({{ count($cart) }} Items)
                    </h3>

                    <div style="margin-bottom: 16px;">
                        @foreach($cart as $item)
                            <div class="summary-item-row">
                                <div>
                                    <strong>{{ $item['quantity'] }}x</strong> {{ $item['name'] }}
                                    <div style="font-size: 0.72rem; color: #777;">{{ $item['size_name'] }} • {{ $item['crust_name'] }}</div>
                                </div>
                                <span style="font-weight: 700;">৳ {{ number_format($item['total_price']) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div style="border-top: 1px solid #ddd; padding-top: 12px;">
                        <div style="display:flex; justify-content:space-between; font-size:0.85rem; margin-bottom:8px;">
                            <span>Subtotal</span>
                            <strong>৳ {{ number_format($subtotal) }}</strong>
                        </div>
                        @if(!empty($couponDiscount) && $couponDiscount > 0)
                            <div style="display:flex; justify-content:space-between; font-size:0.85rem; margin-bottom:8px; color:#16a34a;">
                                <span><i class="fa-solid fa-ticket" style="margin-right:4px;"></i> Coupon ({{ $couponCode }})</span>
                                <strong>-৳ {{ number_format($couponDiscount) }}</strong>
                            </div>
                        @endif
                        @if(!empty($pointsDiscount) && $pointsDiscount > 0)
                            <div style="display:flex; justify-content:space-between; font-size:0.85rem; margin-bottom:8px; color:#16a34a;">
                                <span><i class="fa-solid fa-coins" style="margin-right:4px; color:#f59e0b;"></i> Points Redeemed ({{ $pointsUsed }} pts)</span>
                                <strong>-৳ {{ number_format($pointsDiscount) }}</strong>
                            </div>
                        @endif
                        <div style="display:flex; justify-content:space-between; font-size:0.85rem; margin-bottom:8px;">
                            <span>Delivery Fee</span>
                            <strong id="displayDeliveryFee">৳ {{ number_format($deliveryCharge) }}</strong>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:0.85rem; margin-bottom:8px;">
                            <span>VAT / Tax (5%)</span>
                            <strong>৳ {{ number_format($vatTax) }}</strong>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:1.25rem; font-weight:900; color:#c8102e; border-top:2px solid #ddd; padding-top:10px; margin-top:8px;">
                            <span>Total Payable</span>
                            <span id="displayTotalPayable">৳ {{ number_format($total) }}</span>
                        </div>
                    </div>

                    <div style="margin-top:14px; background:#fffbeb; border:1px solid #fde68a; border-radius:8px; padding:10px 12px; font-size:0.8rem; color:#92400e; display:flex; align-items:center; gap:8px;">
                        <i class="fa-solid fa-gift" style="font-size:1rem; color:var(--brand-red);"></i>
                        <span>You will earn <strong>{{ $pointsEarned ?? 0 }}</strong> reward points on this order!</span>
                    </div>

                    <button type="submit" class="btn-place-order" style="display:inline-flex; align-items:center; justify-content:center; gap:8px;">
                        <span>Confirm & Place Order</span> <i class="fa-solid fa-arrow-right"></i>
                    </button>

                    <div style="margin-top: 12px; font-size: 0.75rem; color: #777; text-align: center; display:flex; align-items:center; justify-content:center; gap:8px; flex-wrap:wrap;">
                        <span><i class="fa-solid fa-shield-halved" style="color:#16a34a;"></i> Secure Checkout</span>
                        <span>•</span>
                        <span><i class="fa-solid fa-fire" style="color:#c8102e;"></i> Hot & Fresh Pizza Guarantee</span>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const subtotal = {{ $subtotal }};
    const vatTax = {{ $vatTax }};

    function handleOrderTypeChange(type) {
        document.getElementById('labelDelivery').classList.toggle('active', type === 'delivery');
        document.getElementById('labelTakeaway').classList.toggle('active', type === 'takeaway');

        const addressInput = document.getElementById('addressInput');
        const addressGroup = document.getElementById('addressGroup');

        let deliveryFee = (type === 'delivery') ? 70 : 0;
        if (type === 'takeaway') {
            addressInput.removeAttribute('required');
            addressGroup.style.opacity = '0.5';
        } else {
            addressInput.setAttribute('required', 'required');
            addressGroup.style.opacity = '1';
        }

        const newTotal = subtotal + vatTax + deliveryFee;
        document.getElementById('displayDeliveryFee').innerText = '৳ ' + deliveryFee;
        document.getElementById('displayTotalPayable').innerText = '৳ ' + Math.round(newTotal).toLocaleString();
    }

    function handlePaymentChange(method) {
        document.getElementById('payCash').classList.toggle('active', method === 'cash');
        document.getElementById('payBkash').classList.toggle('active', method === 'bkash');
        document.getElementById('payCard').classList.toggle('active', method === 'card');
    }
</script>
@endpush
