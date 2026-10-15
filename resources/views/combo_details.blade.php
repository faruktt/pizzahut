@extends('layouts.app')

@section('title', $combo->name . ' | Italian Pizza')
@section('meta-description', $combo->description ?? $combo->name . ' - Select your items and enjoy!')

@push('styles')
<style>
:root {
    --brand-red: #c8102e;
    --brand-red-dark: #a50b24;
}

.combo-page-wrap {
    padding: 28px 0 80px;
}

/* HERO */
.combo-hero {
    background: linear-gradient(135deg, #c8102e 0%, #e62843 100%);
    border-radius: 16px;
    color: #fff;
    padding: 32px 36px;
    margin-bottom: 32px;
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 24px;
    align-items: center;
    box-shadow: 0 10px 30px rgba(200,16,46,0.2);
    position: relative;
    overflow: hidden;
}
.combo-hero::before {
    content: '';
    position: absolute;
    top: -30px; right: -30px;
    width: 180px; height: 180px;
    border-radius: 50%;
    background: rgba(255,255,255,0.07);
}
.combo-hero-badge {
    display: inline-block;
    background: rgba(255,255,255,0.2);
    border: 1px solid rgba(255,255,255,0.4);
    color: #fff;
    font-weight: 800;
    font-size: 0.75rem;
    padding: 3px 12px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 10px;
}
.combo-hero-title { font-size: 2rem; font-weight: 900; margin-bottom: 6px; }
.combo-hero-desc { font-size: 0.92rem; color: rgba(255,255,255,0.85); line-height: 1.5; }
.combo-price-box {
    background: rgba(255,255,255,0.15);
    border: 2px solid rgba(255,255,255,0.35);
    border-radius: 14px;
    padding: 20px 28px;
    text-align: center;
    backdrop-filter: blur(10px);
    min-width: 140px;
}
.combo-price-currency { font-size: 1.2rem; font-weight: 800; }
.combo-price-amount { font-size: 3rem; font-weight: 900; line-height: 1; }
.combo-price-label { font-size: 0.75rem; color: rgba(255,255,255,0.75); margin-top: 4px; }

/* PROGRESS BAR */
.combo-progress-bar-wrap {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 28px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.progress-bar-inner {
    flex: 1;
    height: 8px;
    background: #f1f5f9;
    border-radius: 99px;
    overflow: hidden;
}
.progress-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--brand-red), #e62843);
    border-radius: 99px;
    transition: width 0.4s ease;
    width: 0%;
}
.progress-label { font-size: 0.85rem; font-weight: 700; color: #0f172a; white-space: nowrap; }
.progress-count { font-size: 0.85rem; font-weight: 800; color: var(--brand-red); }

/* PRODUCT CARDS */
.combo-products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 18px;
    margin-bottom: 36px;
}
.combo-product-card {
    background: #fff;
    border: 2px solid #e2e8f0;
    border-radius: 14px;
    overflow: hidden;
    transition: all 0.25s;
    cursor: pointer;
    position: relative;
}
.combo-product-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.1); transform: translateY(-2px); }
.combo-product-card.selected {
    border-color: var(--brand-red);
    box-shadow: 0 4px 18px rgba(200,16,46,0.18);
}
.combo-product-card.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
.combo-product-card.disabled:hover { transform: none; box-shadow: none; }

.combo-product-check {
    position: absolute;
    top: 10px; right: 10px;
    width: 28px; height: 28px;
    background: var(--brand-red);
    border-radius: 50%;
    display: none;
    align-items: center; justify-content: center;
    color: #fff;
    font-size: 0.85rem;
    z-index: 2;
    box-shadow: 0 3px 8px rgba(200,16,46,0.35);
}
.combo-product-card.selected .combo-product-check { display: flex; }

.combo-slot-number {
    position: absolute;
    top: 10px; right: 10px;
    width: 28px; height: 28px;
    background: rgba(0,0,0,0.5);
    border-radius: 50%;
    display: none;
    align-items: center; justify-content: center;
    color: #fff;
    font-size: 0.75rem;
    font-weight: 800;
    z-index: 2;
}
.combo-product-card.selected .combo-slot-number { display: none; }

.combo-product-img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    display: block;
    background: #f8fafc;
}
.combo-product-img-placeholder {
    width: 100%; height: 150px;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    display: flex; align-items: center; justify-content: center;
}

.combo-product-body { padding: 14px; }
.combo-product-name { font-size: 0.95rem; font-weight: 800; color: #0f172a; margin-bottom: 4px; }
.combo-product-desc { font-size: 0.76rem; color: #64748b; margin-bottom: 10px; line-height: 1.4; }

/* Size/Crust selects */
.variation-select-wrap { margin-bottom: 8px; }
.variation-select-wrap label { font-size: 0.72rem; font-weight: 700; color: #374151; display: block; margin-bottom: 3px; }
.variation-select {
    width: 100%;
    padding: 6px 10px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 0.8rem;
    font-family: inherit;
    color: #0f172a;
    background: #f8fafc;
    transition: border-color 0.2s;
}
.variation-select:focus { outline: none; border-color: var(--brand-red); }

.combo-product-price { font-size: 0.85rem; font-weight: 800; color: var(--brand-red); }

.select-btn {
    display: block;
    width: 100%;
    padding: 10px;
    background: var(--brand-red);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 800;
    cursor: pointer;
    transition: all 0.2s;
    margin-top: 10px;
    font-family: inherit;
}
.select-btn:hover { background: var(--brand-red-dark); }
.select-btn.selected-btn {
    background: #dcfce7;
    color: #15803d;
}
.select-btn.selected-btn:hover { background: #bbf7d0; }

/* ORDER SUMMARY STRIP */
.combo-summary-strip {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 20px 24px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.06);
    margin-bottom: 28px;
}
.combo-summary-items { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 16px; min-height: 38px; }
.summary-item-chip {
    display: inline-flex; align-items: center; gap: 6px;
    background: #fde8eb; color: var(--brand-red);
    border-radius: 20px; padding: 5px 12px; font-size: 0.8rem; font-weight: 700;
}
.summary-item-chip .rm { cursor: pointer; opacity: 0.7; transition: opacity 0.2s; }
.summary-item-chip .rm:hover { opacity: 1; }

.combo-confirm-btn {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--brand-red);
    color: #fff;
    padding: 13px 28px;
    border-radius: 10px;
    border: none;
    font-size: 1rem;
    font-weight: 800;
    cursor: pointer;
    transition: all 0.2s;
    font-family: inherit;
    box-shadow: 0 4px 14px rgba(200,16,46,0.3);
}
.combo-confirm-btn:hover:not(:disabled) { background: var(--brand-red-dark); transform: translateY(-1px); }
.combo-confirm-btn:disabled { opacity: 0.5; cursor: not-allowed; }

/* Notification toast */
.toast-msg {
    position: fixed;
    bottom: 28px; right: 28px;
    background: #0f172a;
    color: #fff;
    padding: 12px 20px;
    border-radius: 10px;
    font-size: 0.88rem;
    font-weight: 600;
    z-index: 9999;
    box-shadow: 0 8px 24px rgba(0,0,0,0.2);
    transform: translateY(20px);
    opacity: 0;
    transition: all 0.3s;
    display: flex; align-items: center; gap: 10px;
}
.toast-msg.show { transform: translateY(0); opacity: 1; }
.toast-msg.success { background: #16a34a; }
.toast-msg.error { background: var(--brand-red); }

@media (max-width:768px) {
    .combo-hero { grid-template-columns: 1fr; }
    .combo-price-box { text-align: left; }
    .combo-products-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width:480px) {
    .combo-products-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="combo-page-wrap container" style="max-width:1100px;margin:0 auto;padding:0 16px;">
    
    {{-- BREADCRUMB --}}
    <div style="margin-bottom:18px;font-size:0.82rem;color:#64748b;display:flex;align-items:center;gap:6px;">
        <a href="{{ route('home') }}" style="color:#64748b;text-decoration:none;"><i class="fa-solid fa-house"></i> Home</a>
        <i class="fa-solid fa-chevron-right" style="font-size:0.65rem;"></i>
        <a href="{{ route('home') }}#combos" style="color:#64748b;text-decoration:none;">Combos</a>
        <i class="fa-solid fa-chevron-right" style="font-size:0.65rem;"></i>
        <span style="color:#0f172a;font-weight:600;">{{ $combo->name }}</span>
    </div>

    {{-- HERO --}}
    <div class="combo-hero">
        <div>
            <div class="combo-hero-badge"><i class="fa-solid fa-layer-group"></i> Combo Meal</div>
            <div class="combo-hero-title">{{ $combo->name }}</div>
            @if($combo->description)
                <div class="combo-hero-desc">{{ $combo->description }}</div>
            @endif
            <div style="margin-top:14px;font-size:0.85rem;color:rgba(255,255,255,0.8);">
                <i class="fa-solid fa-circle-info"></i> Select any <strong>{{ $combo->max_select }}</strong> item(s) from the menu below
            </div>
        </div>
        <div>
            @if($combo->image && file_exists(public_path('uploads/'.$combo->image)))
                <img src="{{ asset('uploads/'.$combo->image) }}" alt="{{ $combo->name }}" 
                     style="width:120px;height:90px;object-fit:cover;border-radius:12px;border:3px solid rgba(255,255,255,0.3);">
            @endif
            <div class="combo-price-box" style="margin-top:10px;">
                <div class="combo-price-amount"><span class="combo-price-currency">৳</span>{{ number_format($combo->price, 0) }}</div>
                <div class="combo-price-label">Combo Price</div>
            </div>
        </div>
    </div>

    {{-- PROGRESS --}}
    <div class="combo-progress-bar-wrap">
        <i class="fa-solid fa-basket-shopping" style="color:var(--brand-red);font-size:1.1rem;"></i>
        <div class="progress-label">Your Selection</div>
        <div class="progress-bar-inner">
            <div class="progress-bar-fill" id="progressFill"></div>
        </div>
        <div class="progress-count"><span id="selectedCount">0</span>/{{ $combo->max_select }} items</div>
    </div>

    {{-- PRODUCTS GRID --}}
    <h2 style="font-size:1.1rem;font-weight:800;color:#0f172a;margin-bottom:16px;">
        <i class="fa-solid fa-pizza-slice" style="color:var(--brand-red);"></i> Choose Your Items
    </h2>

    <div class="combo-products-grid" id="productGrid">
        @foreach($combo->products as $product)
        @php
            $pizzaMap  = $product->pizza_map ?? [];
            $firstSize = array_key_first($pizzaMap);
            $firstCrust= $firstSize ? array_key_first($pizzaMap[$firstSize]) : null;
            $defaultPrice = ($firstSize && $firstCrust) ? ($pizzaMap[$firstSize][$firstCrust]['price'] ?? $product->base_price) : $product->base_price;
            $defaultImage = ($firstSize && $firstCrust && !empty($pizzaMap[$firstSize][$firstCrust]['image'])) 
                            ? $pizzaMap[$firstSize][$firstCrust]['image'] 
                            : $product->image;
        @endphp
        <div class="combo-product-card" 
             id="prod-card-{{ $product->id }}"
             data-id="{{ $product->id }}"
             data-name="{{ $product->name }}"
             data-map="{{ json_encode($pizzaMap) }}">
            <div class="combo-product-check"><i class="fa-solid fa-check"></i></div>

            {{-- Product Image --}}
            @if($defaultImage && file_exists(public_path('uploads/'.$defaultImage)))
                <img src="{{ asset('uploads/'.$defaultImage) }}" 
                     alt="{{ $product->name }}" 
                     class="combo-product-img"
                     id="prod-img-{{ $product->id }}">
            @else
                <div class="combo-product-img-placeholder" id="prod-img-{{ $product->id }}">
                    <i class="fa-solid fa-pizza-slice" style="font-size:2.5rem;color:#cbd5e1;"></i>
                </div>
            @endif

            <div class="combo-product-body">
                <div class="combo-product-name">{{ $product->name }}</div>
                @if($product->description)
                    <div class="combo-product-desc">{{ Str::limit($product->description, 55) }}</div>
                @endif

                {{-- Size Select --}}
                @if(count($pizzaMap) > 0)
                <div class="variation-select-wrap">
                    <label><i class="fa-solid fa-ruler-horizontal"></i> Select Size</label>
                    <select class="variation-select" id="size-{{ $product->id }}" 
                            onchange="onSizeChange({{ $product->id }})">
                        @foreach($pizzaMap as $sizeName => $crusts)
                            <option value="{{ $sizeName }}">{{ $sizeName }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Crust Select --}}
                <div class="variation-select-wrap">
                    <label><i class="fa-solid fa-circle-half-stroke"></i> Select Your Crust</label>
                    <select class="variation-select" id="crust-{{ $product->id }}" 
                            onchange="onCrustChange({{ $product->id }})">
                        @if($firstSize)
                            @foreach($pizzaMap[$firstSize] as $crustName => $vData)
                                <option value="{{ $crustName }}">{{ $crustName }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                @endif

                <div style="display:flex;align-items:center;justify-content:space-between;margin-top:6px;">
                    <div class="combo-product-price" id="prod-price-{{ $product->id }}">
                        ৳ {{ number_format($defaultPrice, 0) }}
                    </div>
                </div>

                <button type="button" class="select-btn" id="sel-btn-{{ $product->id }}"
                        onclick="toggleSelect({{ $product->id }}, '{{ addslashes($product->name) }}')">
                    <i class="fa-solid fa-circle-plus"></i> Select
                </button>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ORDER SUMMARY --}}
    <div class="combo-summary-strip" id="summaryStrip">
        <div style="font-size:1rem;font-weight:800;color:#0f172a;margin-bottom:12px;">
            <i class="fa-solid fa-clipboard-list" style="color:var(--brand-red);"></i> Your Combo Order
        </div>
        <div class="combo-summary-items" id="summaryItems">
            <span style="color:#94a3b8;font-size:0.82rem;">No items selected yet...</span>
        </div>

        <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
            <button type="button" class="combo-confirm-btn" id="confirmBtn" 
                    onclick="submitCombo()" disabled>
                <i class="fa-solid fa-cart-plus"></i> Add to Cart
                <span id="btnSelCount" style="background:rgba(255,255,255,0.2);padding:2px 8px;border-radius:10px;font-size:0.8rem;">0/{{ $combo->max_select }}</span>
            </button>
            <a href="{{ route('home') }}" style="font-size:0.85rem;color:#64748b;text-decoration:none;">
                <i class="fa-solid fa-arrow-left"></i> Back to Menu
            </a>
        </div>
    </div>

    {{-- Hidden form --}}
    <form id="comboOrderForm" method="POST" action="{{ route('combo.add', $combo->id) }}" style="display:none;">
        @csrf
        <div id="formInputs"></div>
    </form>
</div>

<div class="toast-msg" id="toastMsg"></div>
@endsection

@push('scripts')
<script>
const maxSelect = {{ $combo->max_select }};
let selectedItems = {}; // { productId: { name, size, crust, price, image } }
let pizzaMaps = {};

// Build pizza maps from data attributes
document.querySelectorAll('.combo-product-card').forEach(card => {
    const id = parseInt(card.dataset.id);
    pizzaMaps[id] = JSON.parse(card.dataset.map || '{}');
});

function onSizeChange(productId) {
    const sizeSelect  = document.getElementById('size-' + productId);
    const crustSelect = document.getElementById('crust-' + productId);
    const map  = pizzaMaps[productId];
    const size = sizeSelect.value;

    if (!map || !map[size]) return;

    // Update crust options
    crustSelect.innerHTML = '';
    Object.keys(map[size]).forEach(crustName => {
        const opt = document.createElement('option');
        opt.value = crustName;
        opt.textContent = crustName;
        crustSelect.appendChild(opt);
    });

    updateCardDisplay(productId);

    // If already selected, update selection
    if (selectedItems[productId]) {
        updateSelectedItem(productId);
    }
}

function onCrustChange(productId) {
    updateCardDisplay(productId);
    if (selectedItems[productId]) {
        updateSelectedItem(productId);
    }
}

function updateCardDisplay(productId) {
    const map   = pizzaMaps[productId];
    const size  = document.getElementById('size-' + productId)?.value;
    const crust = document.getElementById('crust-' + productId)?.value;
    if (!map || !size || !crust || !map[size] || !map[size][crust]) return;

    const vData = map[size][crust];

    // Update price
    const priceEl = document.getElementById('prod-price-' + productId);
    if (priceEl) priceEl.textContent = '৳ ' + Math.round(vData.price || 0).toLocaleString();

    // Update image
    if (vData.image) {
        const imgEl = document.getElementById('prod-img-' + productId);
        if (imgEl && imgEl.tagName === 'IMG') {
            imgEl.src = '/uploads/' + vData.image;
        }
    }
}

function updateSelectedItem(productId) {
    const map   = pizzaMaps[productId];
    const size  = document.getElementById('size-' + productId)?.value;
    const crust = document.getElementById('crust-' + productId)?.value;
    if (!map || !size || !crust) return;

    const vData = map[size]?.[crust] || {};
    selectedItems[productId] = {
        ...selectedItems[productId],
        size: size,
        crust: crust,
        price: vData.price || 0,
        image: vData.image || '',
    };
    renderSummary();
}

function toggleSelect(productId, productName) {
    if (selectedItems[productId]) {
        // Deselect
        delete selectedItems[productId];
        document.getElementById('prod-card-' + productId).classList.remove('selected');
        document.getElementById('sel-btn-' + productId).innerHTML = '<i class="fa-solid fa-circle-plus"></i> Select';
        document.getElementById('sel-btn-' + productId).classList.remove('selected-btn');
    } else {
        // Check max
        if (Object.keys(selectedItems).length >= maxSelect) {
            showToast('You can only select ' + maxSelect + ' item(s) for this combo!', 'error');
            return;
        }
        const map   = pizzaMaps[productId];
        const size  = document.getElementById('size-' + productId)?.value || '';
        const crust = document.getElementById('crust-' + productId)?.value || '';
        const vData = (map && size && crust && map[size] && map[size][crust]) ? map[size][crust] : {};

        selectedItems[productId] = {
            product_id: productId,
            name: productName,
            size: size,
            crust: crust,
            price: vData.price || 0,
            image: vData.image || '',
        };
        document.getElementById('prod-card-' + productId).classList.add('selected');
        document.getElementById('sel-btn-' + productId).innerHTML = '<i class="fa-solid fa-circle-check"></i> Selected';
        document.getElementById('sel-btn-' + productId).classList.add('selected-btn');
    }

    updateDisabledState();
    renderSummary();
}

function updateDisabledState() {
    const count = Object.keys(selectedItems).length;
    document.querySelectorAll('.combo-product-card').forEach(card => {
        const id = parseInt(card.dataset.id);
        if (count >= maxSelect && !selectedItems[id]) {
            card.classList.add('disabled');
        } else {
            card.classList.remove('disabled');
        }
    });
}

function renderSummary() {
    const count    = Object.keys(selectedItems).length;
    const progress = (count / maxSelect) * 100;

    document.getElementById('selectedCount').textContent = count;
    document.getElementById('progressFill').style.width  = Math.min(progress, 100) + '%';
    document.getElementById('btnSelCount').textContent   = count + '/' + maxSelect;

    const confirmBtn = document.getElementById('confirmBtn');
    confirmBtn.disabled = (count === 0);

    const summaryEl = document.getElementById('summaryItems');
    if (count === 0) {
        summaryEl.innerHTML = '<span style="color:#94a3b8;font-size:0.82rem;">No items selected yet...</span>';
        return;
    }

    summaryEl.innerHTML = Object.values(selectedItems).map(item => 
        `<span class="summary-item-chip">
            <i class="fa-solid fa-pizza-slice"></i>
            ${item.name}${item.size ? ' ('+item.size+')' : ''}
            <span class="rm" onclick="toggleSelect(${item.product_id}, '')"><i class="fa-solid fa-xmark"></i></span>
        </span>`
    ).join('');
}

const isCustomerLoggedIn = {{ Auth::guard('web')->check() ? 'true' : 'false' }};
const customerLoginUrl = "{{ route('login') }}";

function submitCombo() {
    if (!isCustomerLoggedIn) {
        window.location.href = customerLoginUrl;
        return;
    }

    const items = Object.values(selectedItems);
    if (items.length === 0) {
        showToast('Please select at least 1 item!', 'error');
        return;
    }

    const formInputsEl = document.getElementById('formInputs');
    let html = '';
    items.forEach((item, idx) => {
        html += `<input type="hidden" name="selected_items[${idx}][product_id]" value="${item.product_id}">`;
        html += `<input type="hidden" name="selected_items[${idx}][size]" value="${item.size || 'Regular'}">`;
        html += `<input type="hidden" name="selected_items[${idx}][crust]" value="${item.crust || 'Pan Crust'}">`;
    });
    formInputsEl.innerHTML = html;

    showToast('Adding to cart...', 'success');
    setTimeout(() => document.getElementById('comboOrderForm').submit(), 500);
}

function showToast(msg, type = '') {
    const el = document.getElementById('toastMsg');
    el.textContent = msg;
    el.className = 'toast-msg show ' + type;
    setTimeout(() => { el.classList.remove('show'); }, 3000);
}

// Initialize
document.querySelectorAll('.combo-product-card').forEach(card => {
    const id = parseInt(card.dataset.id);
    updateCardDisplay(id);
});
</script>
@endpush
