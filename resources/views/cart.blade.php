@extends('layouts.app')

@section('title', 'Your Cart | Pizza Hut Bangladesh')

@push('styles')
<style>
    .cart-page-wrap {
        padding: 32px 0 60px 0;
    }

    .cart-split-layout {
        display: grid;
        grid-template-columns: 1.55fr 0.95fr;
        gap: 32px;
        align-items: flex-start;
    }

    /* Left Column: Your Cart */
    .cart-main-heading {
        font-size: 1.35rem;
        font-weight: 800;
        color: #222;
        margin-bottom: 20px;
        position: relative;
        padding-bottom: 6px;
    }

    .cart-main-heading::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 3px;
        background: var(--brand-red);
        border-radius: 2px;
    }

    .cart-items-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-bottom: 40px;
    }

    .cart-item-card {
        background: #ffffff;
        border: 1px solid #eaeaea;
        border-radius: 12px;
        padding: 16px 20px;
        display: grid;
        grid-template-columns: auto 1fr auto auto;
        gap: 16px;
        align-items: center;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    }

    .cart-item-thumb {
        width: 68px;
        height: 68px;
        border-radius: 8px;
        object-fit: cover;
    }

    .cart-item-details h4 {
        font-size: 0.98rem;
        font-weight: 800;
        color: #222;
        margin-bottom: 2px;
    }

    .cart-item-size {
        font-size: 0.78rem;
        color: #777;
        margin-bottom: 4px;
    }

    .cart-details-toggle {
        font-size: 0.76rem;
        color: var(--brand-red);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-weight: 600;
    }

    .cart-addons-collapse {
        margin-top: 6px;
        font-size: 0.76rem;
        color: #555;
        background: #fbfbfb;
        padding: 6px 10px;
        border-radius: 6px;
        border-left: 3px solid var(--brand-red);
        display: none;
    }

    .cart-addons-collapse.show {
        display: block;
    }

    .cart-pricing-side {
        text-align: right;
    }

    .cart-unit-label {
        font-size: 0.72rem;
        color: #888;
    }

    .cart-item-total-price {
        font-size: 1.15rem;
        font-weight: 800;
        color: #222;
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: flex-end;
    }

    .btn-trash-item {
        background: none;
        border: none;
        color: var(--brand-red);
        cursor: pointer;
        font-size: 1.1rem;
        transition: transform 0.2s;
        display: inline-flex;
        align-items: center;
    }

    .btn-trash-item:hover {
        transform: scale(1.15);
    }

    .cart-qty-box {
        display: flex;
        align-items: center;
        border: 1px solid #ddd;
        border-radius: 6px;
        overflow: hidden;
    }

    .cart-qty-btn {
        width: 28px;
        height: 30px;
        background: #f8f8f8;
        border: none;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #444;
    }

    .cart-qty-num {
        width: 32px;
        text-align: center;
        font-size: 0.88rem;
        font-weight: 700;
    }

    /* You May Also Like Section (Exact match to Image 3) */
    .recommendations-wrap {
        margin-top: 20px;
    }

    .recommendations-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #222;
        margin-bottom: 16px;
        position: relative;
        display: inline-block;
        padding-bottom: 6px;
    }

    .recommendations-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 48px;
        height: 3px;
        background: var(--brand-red);
        border-radius: 2px;
    }

    .recommendations-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }

    .rec-item-card {
        background: #ffffff;
        border: 1px solid #eaeaea;
        border-radius: 10px;
        padding: 12px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    }

    .rec-thumb-img {
        width: 100px;
        height: 80px;
        object-fit: cover;
        border-radius: 6px;
        margin-bottom: 8px;
    }

    .rec-item-name {
        font-size: 0.82rem;
        font-weight: 700;
        color: #222;
        margin-bottom: 8px;
        min-height: 32px;
        line-height: 1.25;
    }

    .btn-rec-add {
        width: 100%;
        background: var(--brand-red);
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 6px 10px;
        font-weight: 700;
        font-size: 0.78rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-rec-add:hover {
        background: var(--brand-red-dark);
    }

    /* Right Column: Checkout Summary (Exact match to Image 3) */
    .cart-sidebar {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .explore-menu-banner {
        background: #f1f2f4;
        border-radius: 10px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-weight: 700;
        color: #222;
        font-size: 0.95rem;
        cursor: pointer;
        transition: background 0.2s;
    }

    .explore-menu-banner:hover {
        background: #e5e7eb;
    }

    .explore-menu-arrow {
        color: var(--brand-red);
        font-size: 1.2rem;
    }

    .rewards-actions-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .btn-reward-action {
        background: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 12px;
        font-size: 0.85rem;
        font-weight: 700;
        color: #333;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-reward-action:hover, .btn-reward-action.active {
        border-color: var(--brand-red);
        color: var(--brand-red);
        background: #fff5f5;
        box-shadow: 0 2px 8px rgba(200, 16, 46, 0.1);
    }

    .reward-drawer-panel {
        display: none;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 16px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.06);
        animation: fadeInSlide 0.25s ease forwards;
    }

    .reward-drawer-panel.show {
        display: block;
    }

    @keyframes fadeInSlide {
        from { opacity: 0; transform: translateY(-6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .coupon-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 6px;
        padding: 5px 10px;
        font-size: 0.78rem;
        font-weight: 700;
        color: #334155;
        cursor: pointer;
        transition: all 0.15s;
    }

    .coupon-chip:hover {
        background: #fef2f2;
        border-color: var(--brand-red);
        color: var(--brand-red);
        transform: translateY(-1px);
    }

    .applied-discount-pill {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 0.82rem;
        color: #065f46;
        font-weight: 600;
        margin-top: 8px;
    }

    .applied-discount-pill .btn-remove-pill {
        background: none;
        border: none;
        color: #dc2626;
        font-size: 0.85rem;
        cursor: pointer;
        padding: 2px 6px;
        border-radius: 4px;
        transition: background 0.15s;
    }

    .applied-discount-pill .btn-remove-pill:hover {
        background: #fee2e2;
    }

    .points-reward-hint {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 0.8rem;
        color: #92400e;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 14px;
    }

    .summary-card-box {
        background: #f4f5f7;
        border-radius: 12px;
        padding: 20px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.88rem;
        color: #555;
        margin-bottom: 12px;
    }

    .summary-row.total-row {
        border-top: 1px solid #ddd;
        padding-top: 14px;
        margin-top: 14px;
        font-size: 1.15rem;
        font-weight: 800;
        color: #222;
    }

    .total-amount-val {
        font-size: 1.3rem;
        font-weight: 900;
        color: #222;
    }

    .btn-review-checkout {
        display: block;
        width: 100%;
        background: var(--brand-red);
        color: #fff;
        text-align: center;
        padding: 14px;
        border-radius: 8px;
        font-size: 0.95rem;
        font-weight: 800;
        margin-top: 16px;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(200, 16, 46, 0.25);
        transition: background 0.2s;
    }

    .btn-review-checkout:hover {
        background: var(--brand-red-dark);
    }

    .empty-cart-state {
        background: #fff;
        border-radius: 12px;
        padding: 40px;
        text-align: center;
        border: 1px solid #eaeaea;
    }

    @media (max-width: 992px) {
        .cart-split-layout {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="container cart-page-wrap">
    <div class="cart-split-layout">

        <!-- LEFT COLUMN: Your Cart Items + You May Also Like -->
        <div>
            <h2 class="cart-main-heading">Your cart</h2>

            @if(empty($cart))
                <div class="empty-cart-state">
                    <div style="font-size: 3rem; margin-bottom: 12px; color:var(--brand-red);"><i class="fa-solid fa-pizza-slice"></i></div>
                    <h3 style="font-size: 1.2rem; font-weight: 800; color: #333; margin-bottom: 8px;">Your cart is empty</h3>
                    <p style="color: #777; margin-bottom: 20px;">Explore our signature pan pizzas and delicious combos!</p>
                    <a href="{{ route('home') }}" class="btn-signin" style="padding: 10px 24px; display:inline-flex; align-items:center; gap:8px;">
                        <i class="fa-solid fa-utensils"></i> <span>Browse Menu</span>
                    </a>
                </div>
            @else
                <div class="cart-items-list">
                    @foreach($cart as $key => $item)
                        <div class="cart-item-card" id="cart-item-{{ $key }}">
                            <img src="{{ !empty($item['image']) ? asset('uploads/' . $item['image']) : asset('images/pizza-hero.jpg') }}" alt="{{ $item['name'] }}" class="cart-item-thumb">

                            <div class="cart-item-details">
                                <h4>{{ $item['name'] }}</h4>
                                <div class="cart-item-size">Size: {{ $item['size_name'] }} • {{ $item['crust_name'] }}</div>
                                
                                @if(!empty($item['is_combo']) && !empty($item['combo_summary']))
                                    <div style="font-size: 0.78rem; color: var(--brand-red); font-weight: 700; margin-top: 2px;">
                                        <i class="fa-solid fa-pizza-slice"></i> Included: {{ $item['combo_summary'] }}
                                    </div>
                                @endif

                                <span class="cart-details-toggle" onclick="toggleItemDetails('{{ $key }}')">
                                    Details <i class="fa-solid fa-chevron-down" style="font-size:0.75rem; margin-left:3px;"></i>
                                </span>

                                <div class="cart-addons-collapse" id="addons-collapse-{{ $key }}">
                                    @if(!empty($item['is_combo']) && !empty($item['combo_items']))
                                        <div style="margin-bottom: 6px;">
                                            <strong>Selected Combo Items:</strong>
                                            <ul style="margin-left: 16px; margin-top: 4px;">
                                                @foreach($item['combo_items'] as $ci)
                                                    @php
                                                        $label = $ci['name'] ?? $ci['product_name'] ?? 'Item';
                                                        $slot = $ci['slot'] ?? $loop->iteration;
                                                    @endphp
                                                    <li>
                                                        {{ is_numeric($slot) ? 'Item ' . $slot : $slot }}:
                                                        <strong>{{ $label }}</strong>
                                                        @if(!empty($ci['size']))<span style="color:#64748b;"> ({{ $ci['size'] }})</span>@endif
                                                        @if(!empty($ci['crust']))<span style="color:#64748b;"> • {{ $ci['crust'] }}</span>@endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @if(!empty($item['addons']))
                                        <div><strong>Add-ons:</strong>
                                            @foreach($item['addons'] as $addon)
                                                <span>{{ $addon['name'] }} (+৳{{ $addon['price'] }})</span>@if(!$loop->last), @endif
                                            @endforeach
                                        </div>
                                    @elseif(empty($item['is_combo']))
                                        <div>No add-ons selected.</div>
                                    @endif

                                    @if(!empty($item['special_instructions']))
                                        <div style="margin-top: 4px;"><strong>Note:</strong> {{ $item['special_instructions'] }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="cart-pricing-side">
                                <div class="cart-unit-label">৳ {{ number_format($item['unit_price']) }} each</div>
                                <div class="cart-item-total-price">
                                    <span id="item-total-{{ $key }}">৳ {{ number_format($item['total_price']) }}</span>
                                    <button type="button" class="btn-trash-item" onclick="removeCartItem('{{ $key }}')" title="Remove item">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="cart-qty-box">
                                <button type="button" class="cart-qty-btn" onclick="updateCartQty('{{ $key }}', {{ $item['quantity'] - 1 }})"><i class="fa-solid fa-minus" style="font-size:0.75rem;"></i></button>
                                <span class="cart-qty-num" id="item-qty-{{ $key }}">{{ $item['quantity'] }}</span>
                                <button type="button" class="cart-qty-btn" onclick="updateCartQty('{{ $key }}', {{ $item['quantity'] + 1 }})"><i class="fa-solid fa-plus" style="font-size:0.75rem;"></i></button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- "You may also like" Section (Matching Image 3) -->
            <div class="recommendations-wrap">
                <h3 class="recommendations-title">You may also like</h3>

                <div class="recommendations-grid">
                    @foreach($recommendations as $rec)
                        <div class="rec-item-card">
                            <img src="{{ $rec->image ? asset('uploads/' . $rec->image) : asset('images/pizza-hero.jpg') }}" alt="{{ $rec->name }}" class="rec-thumb-img">
                            <h4 class="rec-item-name">{{ $rec->name }}</h4>
                            <button type="button" class="btn-rec-add" onclick="quickAddSide({{ $rec->id }}, '{{ addslashes($rec->name) }}')">
                                <span>Add</span>
                                <span>৳ {{ number_format($rec->base_price) }}</span>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Order Summary & Review Payment (Matching Image 3) -->
        <div class="cart-sidebar">
            <a href="{{ route('home') }}" class="explore-menu-banner">
                <span>Explore Menu</span>
                <span class="explore-menu-arrow"><i class="fa-solid fa-arrow-right"></i></span>
            </a>

            <div class="rewards-actions-row">
                <button type="button" class="btn-reward-action {{ $appliedCoupon ? 'active' : '' }}" id="btnToggleCoupon" onclick="toggleRewardDrawer('coupon')">
                    <i class="fa-solid fa-tag"></i>
                    <span id="labelCouponBtn">{{ $appliedCoupon ? 'Coupon Applied' : 'Apply Coupon' }}</span>
                </button>
                <button type="button" class="btn-reward-action {{ $appliedPoints ? 'active' : '' }}" id="btnTogglePoints" onclick="toggleRewardDrawer('points')">
                    <i class="fa-solid fa-coins"></i>
                    <span id="labelPointsBtn">{{ $appliedPoints ? 'Points Applied' : 'Redeem Points' }}</span>
                </button>
            </div>

            <!-- DRAWER: Apply Coupon -->
            <div class="reward-drawer-panel" id="drawerCoupon">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                    <h5 style="font-size:0.9rem; font-weight:700; margin:0; color:#1e293b;">
                        <i class="fa-solid fa-ticket" style="color:var(--brand-red);"></i> Promo Coupon
                    </h5>
                    <button type="button" onclick="toggleRewardDrawer('coupon')" style="background:none; border:none; color:#94a3b8; font-size:1.1rem; cursor:pointer;">&times;</button>
                </div>

                <div id="appliedCouponStatus" style="{{ $appliedCoupon ? '' : 'display:none;' }}; margin-bottom:12px;">
                    <div class="applied-discount-pill">
                        <div>
                            <i class="fa-solid fa-circle-check"></i>
                            <span id="appliedCouponText">
                                @if($appliedCoupon)
                                    Coupon <strong>{{ $appliedCoupon['code'] }}</strong> applied (-৳{{ number_format($couponDiscount) }})
                                @endif
                            </span>
                        </div>
                        <button type="button" class="btn-remove-pill" onclick="removeCouponCode()" title="Remove coupon">
                            <i class="fa-solid fa-circle-xmark"></i> Remove
                        </button>
                    </div>
                </div>

                <div id="couponInputGroup" style="display:flex; gap:8px;">
                    <input type="text" id="couponCodeInput" class="form-control" placeholder="Enter coupon code" 
                           value="{{ $appliedCoupon['code'] ?? '' }}"
                           style="flex:1; padding:8px 12px; font-size:0.85rem; border:1px solid #cbd5e1; border-radius:6px; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">
                    <button type="button" id="btnApplyCoupon" onclick="applyCouponCode()" 
                            style="background:var(--brand-red); color:#fff; border:none; border-radius:6px; padding:8px 16px; font-weight:700; font-size:0.85rem; cursor:pointer; transition:all 0.2s;">
                        Apply
                    </button>
                </div>
            </div>

            <!-- DRAWER: Redeem Reward Points -->
            <div class="reward-drawer-panel" id="drawerPoints">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                    <h5 style="font-size:0.9rem; font-weight:700; margin:0; color:#1e293b;">
                        <i class="fa-solid fa-coins" style="color:#f59e0b;"></i> Redeem Reward Points
                    </h5>
                    <button type="button" onclick="toggleRewardDrawer('points')" style="background:none; border:none; color:#94a3b8; font-size:1.1rem; cursor:pointer;">&times;</button>
                </div>

                @guest
                    <div style="text-align:center; padding:12px; background:#f8fafc; border-radius:8px; font-size:0.82rem; color:#64748b;">
                        <i class="fa-solid fa-lock" style="color:var(--brand-red); font-size:1.1rem; margin-bottom:6px; display:block;"></i>
                        Please <a href="{{ route('login') }}" style="color:var(--brand-red); font-weight:700; text-decoration:underline;">login</a> to redeem and earn reward points on your orders.
                    </div>
                @else
                    <div style="display:flex; justify-content:space-between; align-items:center; background:#fef3c7; border:1px solid #fde68a; border-radius:8px; padding:8px 12px; margin-bottom:12px; font-size:0.82rem; color:#92400e;">
                        <span>Your Balance:</span>
                        <strong style="font-size:0.95rem; color:#b45309;"><i class="fa-solid fa-coins"></i> {{ number_format($userPoints) }} Points</strong>
                    </div>

                    <div id="appliedPointsStatus" style="{{ $appliedPoints ? '' : 'display:none;' }}; margin-bottom:12px;">
                        <div class="applied-discount-pill">
                            <div>
                                <i class="fa-solid fa-circle-check"></i>
                                <span id="appliedPointsText">
                                    @if($appliedPoints)
                                        Redeemed <strong>{{ $appliedPoints['points'] }} Points</strong> (-৳{{ number_format($pointsDiscount) }})
                                    @endif
                                </span>
                            </div>
                            <button type="button" class="btn-remove-pill" onclick="removeRewardPoints()" title="Remove points discount">
                                <i class="fa-solid fa-circle-xmark"></i> Remove
                            </button>
                        </div>
                    </div>

                    @if($userPoints > 0)
                        <div style="display:flex; gap:8px; align-items:center;">
                            <input type="number" id="pointsInput" min="{{ $minRedeemPoints ?? 1 }}" max="{{ $userPoints }}" 
                                   value="{{ $appliedPoints['points'] ?? min($userPoints, max(1, $subtotal - $couponDiscount)) }}"
                                   class="form-control" placeholder="Points"
                                   style="flex:1; padding:8px 12px; font-size:0.85rem; border:1px solid #cbd5e1; border-radius:6px; font-weight:700;">
                            <button type="button" onclick="document.getElementById('pointsInput').value = {{ $userPoints }};"
                                    style="background:#f1f5f9; border:1px solid #cbd5e1; border-radius:6px; padding:8px 10px; font-size:0.78rem; font-weight:700; color:#475569; cursor:pointer;">
                                Max
                            </button>
                            <button type="button" id="btnApplyPoints" onclick="applyRewardPoints()" 
                                    style="background:#f59e0b; color:#fff; border:none; border-radius:6px; padding:8px 14px; font-weight:700; font-size:0.85rem; cursor:pointer; transition:all 0.2s;">
                                Redeem
                            </button>
                        </div>
                        <span style="display:block; font-size:0.72rem; color:#64748b; margin-top:6px;">
                            * Conversion: {{ $pointRatePoints }} Point{{ $pointRatePoints > 1 ? 's' : '' }} = ৳{{ number_format($pointRateTaka, 2) }} discount.
                        </span>
                    @else
                        <p style="font-size:0.78rem; color:#64748b; margin:0;">
                            You don't have any reward points yet. Complete this order to earn points!
                        </p>
                    @endif
                @endguest
            </div>

            <div class="summary-card-box">
                <div class="summary-row">
                    <span>Sub Total (Including Tax)</span>
                    <strong id="summarySubtotal">৳ {{ number_format($subtotal) }}</strong>
                </div>

                <div class="summary-row" id="rowCouponDiscount" style="{{ $couponDiscount > 0 ? '' : 'display:none;' }}; color:#16a34a; align-items:center;">
                    <span style="display:flex; align-items:center; gap:6px;">
                        <i class="fa-solid fa-ticket"></i> Coupon (<span id="summaryCouponCode">{{ $appliedCoupon['code'] ?? '' }}</span>)
                        <button type="button" onclick="removeCouponCode()" title="Remove Coupon" 
                                style="background:#fee2e2; border:none; color:#dc2626; border-radius:4px; padding:2px 7px; font-size:0.74rem; cursor:pointer; font-weight:700; display:inline-flex; align-items:center; gap:2px;">
                            <i class="fa-solid fa-xmark"></i> Remove
                        </button>
                    </span>
                    <strong id="summaryCouponDiscount">-৳ {{ number_format($couponDiscount) }}</strong>
                </div>

                <div class="summary-row" id="rowPointsDiscount" style="{{ $pointsDiscount > 0 ? '' : 'display:none;' }}; color:#16a34a; align-items:center;">
                    <span style="display:flex; align-items:center; gap:6px;">
                        <i class="fa-solid fa-coins" style="color:#f59e0b;"></i> Points (<span id="summaryPointsUsed">{{ $appliedPoints['points'] ?? 0 }} pts</span>)
                        <button type="button" onclick="removeRewardPoints()" title="Remove Points Discount" 
                                style="background:#fee2e2; border:none; color:#dc2626; border-radius:4px; padding:2px 7px; font-size:0.74rem; cursor:pointer; font-weight:700; display:inline-flex; align-items:center; gap:2px;">
                            <i class="fa-solid fa-xmark"></i> Remove
                        </button>
                    </span>
                    <strong id="summaryPointsDiscount">-৳ {{ number_format($pointsDiscount) }}</strong>
                </div>

                <div class="summary-row">
                    <span>Delivery Charge</span>
                    <strong id="summaryDelivery">৳ {{ number_format($deliveryCharge) }}</strong>
                </div>

                <div class="summary-row total-row">
                    <div>
                        <div>Total</div>
                        <span style="font-size:0.75rem; color:#777; font-weight:normal;">(Including Vat & Tax)</span>
                    </div>
                    <span class="total-amount-val" id="summaryTotal">৳ {{ number_format($total) }}</span>
                </div>

                <div class="points-reward-hint">
                    <i class="fa-solid fa-gift" style="font-size:1rem; color:var(--brand-red);"></i>
                    <span>You will earn <strong id="summaryEarnedPoints">{{ $earnedPoints ?? 0 }}</strong> reward points on this order!</span>
                </div>

                @if(!empty($cart))
                    @if(Auth::guard('web')->check())
                        <a href="{{ route('checkout.index') }}" class="btn-review-checkout" style="display:inline-flex; align-items:center; justify-content:center; gap:8px;">
                            <span>Review payment & address</span> <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-review-checkout" style="display:inline-flex; align-items:center; justify-content:center; gap:8px; background: #e52d27;">
                            <i class="fa-solid fa-lock"></i> <span>Login to Complete Order</span> <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    @endif
                @else
                    <button type="button" class="btn-review-checkout" disabled style="opacity: 0.6; cursor: not-allowed;">
                        Cart is Empty
                    </button>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleItemDetails(key) {
        const collapse = document.getElementById('addons-collapse-' + key);
        if (collapse) collapse.classList.toggle('show');
    }

    function toggleRewardDrawer(type) {
        const drawerCoupon = document.getElementById('drawerCoupon');
        const drawerPoints = document.getElementById('drawerPoints');

        if (type === 'coupon') {
            const isShown = drawerCoupon.classList.contains('show');
            drawerPoints.classList.remove('show');
            if (isShown) {
                drawerCoupon.classList.remove('show');
            } else {
                drawerCoupon.classList.add('show');
                const inp = document.getElementById('couponCodeInput');
                if (inp) inp.focus();
            }
        } else if (type === 'points') {
            const isShown = drawerPoints.classList.contains('show');
            drawerCoupon.classList.remove('show');
            if (isShown) {
                drawerPoints.classList.remove('show');
            } else {
                drawerPoints.classList.add('show');
                const inp = document.getElementById('pointsInput');
                if (inp) inp.focus();
            }
        }
    }

    function updateSummaryTotals(data) {
        if (!data) return;

        if (document.getElementById('summarySubtotal')) {
            document.getElementById('summarySubtotal').innerText = '৳ ' + Math.round(data.subtotal).toLocaleString();
        }
        if (document.getElementById('summaryDelivery')) {
            document.getElementById('summaryDelivery').innerText = '৳ ' + Math.round(data.deliveryCharge).toLocaleString();
        }
        if (document.getElementById('summaryTotal')) {
            document.getElementById('summaryTotal').innerText = '৳ ' + Math.round(data.total).toLocaleString();
        }
        if (document.getElementById('headerCartCount') && data.cartCount !== undefined) {
            document.getElementById('headerCartCount').innerText = data.cartCount;
        }
        if (document.getElementById('summaryEarnedPoints')) {
            document.getElementById('summaryEarnedPoints').innerText = Math.floor(data.subtotal / 100);
        }

        // Coupon Row
        const rowCoupon = document.getElementById('rowCouponDiscount');
        const valCoupon = document.getElementById('summaryCouponDiscount');
        const codeCoupon = document.getElementById('summaryCouponCode');
        if (rowCoupon && valCoupon) {
            if (data.couponDiscount > 0) {
                rowCoupon.style.display = 'flex';
                valCoupon.innerText = '-৳ ' + Math.round(data.couponDiscount).toLocaleString();
                if (codeCoupon && data.coupon) codeCoupon.innerText = data.coupon.code;
            } else {
                rowCoupon.style.display = 'none';
            }
        }

        // Points Row
        const rowPoints = document.getElementById('rowPointsDiscount');
        const valPoints = document.getElementById('summaryPointsDiscount');
        const usedPoints = document.getElementById('summaryPointsUsed');
        if (rowPoints && valPoints) {
            if (data.pointsDiscount > 0) {
                rowPoints.style.display = 'flex';
                valPoints.innerText = '-৳ ' + Math.round(data.pointsDiscount).toLocaleString();
                if (usedPoints && data.points) usedPoints.innerText = data.points.points + ' pts';
            } else {
                rowPoints.style.display = 'none';
            }
        }
    }

    function applyCouponCode(codeOverride) {
        const code = codeOverride || (document.getElementById('couponCodeInput') ? document.getElementById('couponCodeInput').value.trim() : '');
        if (!code) {
            showToast('Please enter a coupon code.', 'error');
            return;
        }

        const btn = document.getElementById('btnApplyCoupon');
        const origText = btn ? btn.innerText : 'Apply';
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
        }

        fetch('{{ route("cart.applyCoupon") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ code: code })
        })
        .then(res => res.json())
        .then(data => {
            if (btn) {
                btn.disabled = false;
                btn.innerText = origText;
            }

            if (data.success) {
                showToast(data.message);

                // Update Coupon drawer status
                const statusBox = document.getElementById('appliedCouponStatus');
                const statusText = document.getElementById('appliedCouponText');
                if (statusBox && statusText && data.coupon) {
                    statusText.innerHTML = 'Coupon <strong>' + data.coupon.code + '</strong> applied (-৳' + Math.round(data.couponDiscount).toLocaleString() + ')';
                    statusBox.style.display = 'block';
                }

                // Update toggle button
                const btnToggle = document.getElementById('btnToggleCoupon');
                const labelBtn = document.getElementById('labelCouponBtn');
                if (btnToggle) btnToggle.classList.add('active');
                if (labelBtn) labelBtn.innerText = 'Coupon Applied';

                updateSummaryTotals(data);
            } else {
                showToast(data.message || 'Invalid coupon code.', 'error');
            }
        })
        .catch(err => {
            if (btn) {
                btn.disabled = false;
                btn.innerText = origText;
            }
            showToast('Error applying coupon. Please try again.', 'error');
        });
    }

    function quickApplyCoupon(code) {
        const inp = document.getElementById('couponCodeInput');
        if (inp) inp.value = code;
        applyCouponCode(code);
    }

    function removeCouponCode() {
        const rowCoupon = document.getElementById('rowCouponDiscount');
        if (rowCoupon) rowCoupon.style.opacity = '0.5';

        fetch('{{ route("cart.removeCoupon") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message);

                const statusBox = document.getElementById('appliedCouponStatus');
                if (statusBox) statusBox.style.display = 'none';

                const inp = document.getElementById('couponCodeInput');
                if (inp) inp.value = '';

                const btnToggle = document.getElementById('btnToggleCoupon');
                const labelBtn = document.getElementById('labelCouponBtn');
                if (btnToggle) btnToggle.classList.remove('active');
                if (labelBtn) labelBtn.innerText = 'Apply Coupon';

                if (rowCoupon) {
                    rowCoupon.style.display = 'none';
                    rowCoupon.style.opacity = '1';
                }

                updateSummaryTotals(data);
            } else {
                if (rowCoupon) rowCoupon.style.opacity = '1';
                showToast('Unable to remove coupon.', 'error');
            }
        })
        .catch(err => {
            if (rowCoupon) rowCoupon.style.opacity = '1';
            showToast('Error removing coupon. Please try again.', 'error');
        });
    }

    function applyRewardPoints() {
        const inp = document.getElementById('pointsInput');
        const points = inp ? parseInt(inp.value, 10) : 0;
        if (!points || points <= 0) {
            showToast('Please enter a valid amount of points.', 'error');
            return;
        }

        const btn = document.getElementById('btnApplyPoints');
        const origText = btn ? btn.innerText : 'Redeem';
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
        }

        fetch('{{ route("cart.applyPoints") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ points: points })
        })
        .then(res => res.json())
        .then(data => {
            if (btn) {
                btn.disabled = false;
                btn.innerText = origText;
            }

            if (data.success) {
                showToast(data.message);

                const statusBox = document.getElementById('appliedPointsStatus');
                const statusText = document.getElementById('appliedPointsText');
                if (statusBox && statusText && data.points) {
                    statusText.innerHTML = 'Redeemed <strong>' + data.points.points + ' Points</strong> (-৳' + Math.round(data.pointsDiscount).toLocaleString() + ')';
                    statusBox.style.display = 'block';
                }

                const btnToggle = document.getElementById('btnTogglePoints');
                const labelBtn = document.getElementById('labelPointsBtn');
                if (btnToggle) btnToggle.classList.add('active');
                if (labelBtn) labelBtn.innerText = 'Points Applied';

                updateSummaryTotals(data);
            } else {
                showToast(data.message || 'Unable to redeem points.', 'error');
            }
        })
        .catch(err => {
            if (btn) {
                btn.disabled = false;
                btn.innerText = origText;
            }
            showToast('Error redeeming points.', 'error');
        });
    }

    function removeRewardPoints() {
        const rowPoints = document.getElementById('rowPointsDiscount');
        if (rowPoints) rowPoints.style.opacity = '0.5';

        fetch('{{ route("cart.removePoints") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message);

                const statusBox = document.getElementById('appliedPointsStatus');
                if (statusBox) statusBox.style.display = 'none';

                const btnToggle = document.getElementById('btnTogglePoints');
                const labelBtn = document.getElementById('labelPointsBtn');
                if (btnToggle) btnToggle.classList.remove('active');
                if (labelBtn) labelBtn.innerText = 'Redeem Points';

                if (rowPoints) {
                    rowPoints.style.display = 'none';
                    rowPoints.style.opacity = '1';
                }

                updateSummaryTotals(data);
            } else {
                if (rowPoints) rowPoints.style.opacity = '1';
                showToast('Unable to remove points discount.', 'error');
            }
        })
        .catch(err => {
            if (rowPoints) rowPoints.style.opacity = '1';
            showToast('Error removing points discount.', 'error');
        });
    }

    function updateCartQty(key, newQty) {
        fetch('/cart/update/' + key, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ quantity: newQty })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (newQty <= 0) {
                    const el = document.getElementById('cart-item-' + key);
                    if (el) el.remove();
                } else {
                    document.getElementById('item-qty-' + key).innerText = newQty;
                    document.getElementById('item-total-' + key).innerText = '৳ ' + Math.round(data.itemTotal).toLocaleString();
                }

                updateSummaryTotals(data);

                if (data.cartCount === 0) {
                    location.reload();
                }
            }
        });
    }

    function removeCartItem(key) {
        if (!confirm('Are you sure you want to remove this pizza?')) return;

        fetch('/cart/remove/' + key, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const el = document.getElementById('cart-item-' + key);
                if (el) el.remove();

                updateSummaryTotals(data);
                showToast('Item removed from cart.');

                if (data.cartCount === 0) {
                    location.reload();
                }
            }
        });
    }

    const isCustomerLoggedIn = {{ Auth::guard('web')->check() ? 'true' : 'false' }};
    const customerLoginUrl = "{{ route('login') }}";

    function quickAddSide(sideId, name) {
        if (!isCustomerLoggedIn) {
            window.location.href = customerLoginUrl;
            return;
        }

        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ product_id: sideId, quantity: 1 })
        })
        .then(res => {
            if (res.status === 401) {
                window.location.href = customerLoginUrl;
                throw new Error('Unauthenticated');
            }
            return res.json();
        })
        .then(data => {
            if (data.status === 'auth_required') {
                window.location.href = data.redirect || customerLoginUrl;
                return;
            }
            if (data.success) {
                location.reload();
            }
        })
        .catch(err => {
            console.error(err);
        });
    }
</script>
@endpush
