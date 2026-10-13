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
        gap: 6px;
        cursor: pointer;
        transition: border-color 0.2s;
    }

    .btn-reward-action:hover {
        border-color: var(--brand-red);
        color: var(--brand-red);
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
                    <div style="font-size: 3rem; margin-bottom: 12px;">🍕</div>
                    <h3 style="font-size: 1.2rem; font-weight: 800; color: #333; margin-bottom: 8px;">Your cart is empty</h3>
                    <p style="color: #777; margin-bottom: 20px;">Explore our signature pan pizzas and delicious top deals!</p>
                    <a href="{{ route('home') }}" class="btn-signin" style="padding: 10px 24px;">Browse Menu</a>
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
                                    Details ⌄
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
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="cart-qty-box">
                                <button type="button" class="cart-qty-btn" onclick="updateCartQty('{{ $key }}', {{ $item['quantity'] - 1 }})">-</button>
                                <span class="cart-qty-num" id="item-qty-{{ $key }}">{{ $item['quantity'] }}</span>
                                <button type="button" class="cart-qty-btn" onclick="updateCartQty('{{ $key }}', {{ $item['quantity'] + 1 }})">+</button>
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
                <span class="explore-menu-arrow">➔</span>
            </a>

            <div class="rewards-actions-row">
                <button type="button" class="btn-reward-action" onclick="showToast('Coupon code input opened')">
                    <span>🏷️</span>
                    <span>Apply Coupon</span>
                </button>
                <button type="button" class="btn-reward-action" onclick="showToast('You have 0 rewards points.')">
                    <span>🎁</span>
                    <span>Redeem Points</span>
                </button>
            </div>

            <div class="summary-card-box">
                <div class="summary-row">
                    <span>Sub Total (Including Tax)</span>
                    <strong id="summarySubtotal">৳ {{ number_format($subtotal) }}</strong>
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

                @if(!empty($cart))
                    <a href="{{ route('checkout.index') }}" class="btn-review-checkout">
                        Review payment & address
                    </a>
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
        collapse.classList.toggle('show');
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

                document.getElementById('headerCartCount').innerText = data.cartCount;
                document.getElementById('summarySubtotal').innerText = '৳ ' + Math.round(data.subtotal).toLocaleString();
                document.getElementById('summaryDelivery').innerText = '৳ ' + Math.round(data.deliveryCharge).toLocaleString();
                document.getElementById('summaryTotal').innerText = '৳ ' + Math.round(data.total).toLocaleString();

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

                document.getElementById('headerCartCount').innerText = data.cartCount;
                document.getElementById('summarySubtotal').innerText = '৳ ' + Math.round(data.subtotal).toLocaleString();
                document.getElementById('summaryDelivery').innerText = '৳ ' + Math.round(data.deliveryCharge).toLocaleString();
                document.getElementById('summaryTotal').innerText = '৳ ' + Math.round(data.total).toLocaleString();

                showToast('Item removed from cart.');

                if (data.cartCount === 0) {
                    location.reload();
                }
            }
        });
    }

    function quickAddSide(sideId, name) {
        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ product_id: sideId, quantity: 1 })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
</script>
@endpush
