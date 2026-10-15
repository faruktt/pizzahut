@extends('layouts.admin')

@section('title', 'Point of Sale (POS) & Table Management | Italian Pizza')
@section('page-title', 'Restaurant Point of Sale (POS)')

@push('admin-styles')
<style>
    .pos-container {
        display: grid;
        grid-template-columns: 280px 1fr 370px;
        gap: 16px;
        height: calc(100vh - 110px);
        min-height: 620px;
    }

    /* Column 1: Tables List */
    .pos-col-tables {
        background: #ffffff;
        border: 1px solid var(--border-light);
        border-radius: 14px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }

    .pos-col-header {
        padding: 14px 16px;
        border-bottom: 1px solid var(--border-light);
        font-weight: 800;
        font-size: 0.92rem;
        color: var(--text-main);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
    }

    .table-filter-bar {
        display: flex;
        gap: 6px;
        padding: 8px 12px;
        background: #f8fafc;
        border-bottom: 1px solid var(--border-light);
    }

    .table-filter-btn {
        flex: 1;
        padding: 6px 8px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        font-size: 0.74rem;
        font-weight: 700;
        color: #64748b;
        cursor: pointer;
        text-align: center;
        transition: all 0.15s ease;
    }

    .table-filter-btn:hover {
        border-color: #cbd5e1;
        color: #1e293b;
    }

    .table-filter-btn.active {
        background: #0f172a;
        color: #ffffff;
        border-color: #0f172a;
    }

    .pos-tables-scroll {
        flex: 1;
        overflow-y: auto;
        padding: 12px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .pos-table-btn {
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 14px;
        background: #ffffff;
        cursor: pointer;
        text-align: left;
        transition: all 0.2s ease;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .pos-table-btn:hover {
        border-color: #94a3b8;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0,0,0,0.04);
    }

    .pos-table-btn.available {
        border-left: 5px solid #10b981;
    }

    .pos-table-btn.occupied {
        border-left: 5px solid #ef4444;
        background: #fff8f8;
    }

    .pos-table-btn.selected {
        border-color: #0284c7 !important;
        background: #f0f9ff !important;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
    }

    .pos-table-badge {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 12px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: inline-block;
    }

    .badge-available {
        background: #ecfdf5;
        color: #059669;
    }

    .badge-occupied {
        background: #fef2f2;
        color: #dc2626;
    }

    /* Column 2: Menu Catalog */
    .pos-col-menu {
        background: #ffffff;
        border: 1px solid var(--border-light);
        border-radius: 14px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }

    .pos-menu-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 10px 14px;
        background: #ffffff;
        border-bottom: 1px solid var(--border-light);
    }

    .pos-search-wrapper {
        position: relative;
        flex: 1;
        display: flex;
        align-items: center;
    }

    .pos-search-icon {
        position: absolute;
        left: 12px;
        color: #94a3b8;
        font-size: 0.85rem;
        pointer-events: none;
    }

    .pos-search-input {
        width: 100%;
        height: 38px;
        padding: 6px 32px 6px 34px;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.85rem;
        background: #f8fafc;
        transition: all 0.2s;
        font-family: inherit;
        color: var(--text-main);
    }

    .pos-search-input:focus {
        outline: none;
        background: #ffffff;
        border-color: var(--admin-primary);
        box-shadow: 0 0 0 3px rgba(200, 16, 46, 0.1);
    }

    .pos-search-clear {
        position: absolute;
        right: 10px;
        background: none;
        border: none;
        color: #94a3b8;
        font-size: 1.1rem;
        cursor: pointer;
        padding: 2px 6px;
        line-height: 1;
    }

    .pos-menu-count {
        font-size: 0.78rem;
        font-weight: 700;
        color: #64748b;
        background: #f1f5f9;
        padding: 5px 12px;
        border-radius: 12px;
        white-space: nowrap;
    }

    .pos-category-tabs {
        display: flex;
        gap: 8px;
        padding: 10px 14px;
        border-bottom: 1px solid var(--border-light);
        overflow-x: auto;
        background: #f8fafc;
    }

    .cat-tab-btn {
        padding: 6px 14px;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        font-size: 0.78rem;
        font-weight: 700;
        color: #64748b;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.15s ease;
    }

    .cat-tab-btn:hover {
        border-color: var(--admin-primary);
        color: var(--admin-primary);
        background: #fff5f5;
    }

    .cat-tab-btn.active {
        background: var(--admin-primary);
        color: #ffffff;
        border-color: var(--admin-primary);
        box-shadow: 0 2px 6px rgba(200, 16, 46, 0.25);
    }

    /* Products Grid: 4 cards per row */
    .pos-products-grid {
        flex: 1;
        overflow-y: auto;
        padding: 12px;
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 10px;
        align-content: start;
    }

    .pos-product-card {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        user-select: none;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    }

    .pos-product-card:hover {
        border-color: var(--admin-primary);
        transform: translateY(-3px);
        box-shadow: 0 8px 18px rgba(200, 16, 46, 0.12);
    }

    .pos-product-card:active {
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(200, 16, 46, 0.1);
    }

    /* Top: Large Image Area */
    .pos-prod-img-box {
        width: 100%;
        height: 105px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8px;
        overflow: hidden;
        position: relative;
    }

    .pos-prod-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.25s ease;
        filter: drop-shadow(0 3px 6px rgba(0,0,0,0.08));
    }

    .pos-product-card:hover .pos-prod-img {
        transform: scale(1.08);
    }

    .pos-thumb-fallback {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: var(--admin-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        box-shadow: 0 3px 8px rgba(200, 16, 46, 0.15);
    }

    /* Bottom: Name & Price Area */
    .pos-prod-body {
        padding: 10px 10px 12px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex: 1;
        background: #ffffff;
        border-top: 1px solid #f1f5f9;
    }

    .pos-prod-name {
        font-size: 0.85rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.25;
        margin-bottom: 5px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .pos-prod-price-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 2px;
    }

    .pos-prod-price {
        font-size: 0.86rem;
        font-weight: 800;
        color: var(--admin-primary);
        letter-spacing: -0.2px;
    }

    .pos-card-add-icon {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        transition: all 0.2s ease;
    }

    .pos-product-card:hover .pos-card-add-icon {
        background: var(--admin-primary);
        color: #ffffff;
        transform: scale(1.1);
    }

    /* Column 3: Table Order / Bill */
    .pos-col-order {
        background: #ffffff;
        border: 1px solid var(--border-light);
        border-radius: 14px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }

    .pos-order-items-scroll {
        flex: 1;
        overflow-y: auto;
        padding: 14px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .pos-order-item-row {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 12px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        font-size: 0.85rem;
        transition: all 0.15s ease;
    }

    .pos-order-item-row:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    }

    .pos-order-footer {
        padding: 16px;
        border-top: 1px solid var(--border-light);
        background: #f8fafc;
    }

    .pos-bill-line {
        display: flex;
        justify-content: space-between;
        font-size: 0.86rem;
        margin-bottom: 6px;
        color: var(--text-sub);
    }

    .pos-bill-total {
        display: flex;
        justify-content: space-between;
        font-size: 1.25rem;
        font-weight: 900;
        color: var(--admin-primary);
        border-top: 2px dashed #cbd5e1;
        padding-top: 10px;
        margin-top: 8px;
    }

    .btn-pos-pay {
        width: 100%;
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 13px;
        font-size: 0.95rem;
        font-weight: 800;
        cursor: pointer;
        margin-top: 12px;
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.28);
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-pos-pay:hover:not(:disabled) {
        background: linear-gradient(135deg, #059669, #047857);
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(16, 185, 129, 0.38);
    }

    /* Modal for Quick Customizing Pizza onto Table */
    .pos-modal {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s ease;
    }

    .pos-modal.open {
        opacity: 1;
        pointer-events: auto;
    }

    .pos-modal-card {
        background: #ffffff;
        border-radius: 14px;
        width: 100%;
        max-width: 520px;
        padding: 24px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.25);
        transform: translateY(10px);
        transition: transform 0.2s ease;
    }

    .pos-modal.open .pos-modal-card {
        transform: translateY(0);
    }

    .btn-quick-pos {
        background: linear-gradient(135deg, var(--admin-primary), #a50b24);
        color: #ffffff;
        border: none;
        border-radius: 8px;
        padding: 11px 22px;
        font-size: 0.92rem;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(200, 16, 46, 0.25);
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-quick-pos:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(200, 16, 46, 0.35);
    }

    @media (max-width: 1200px) {
        .pos-container {
            grid-template-columns: 240px 1fr 340px;
            gap: 12px;
        }
    }
</style>
@endpush

@section('admin-content')
<div class="pos-container">

    <!-- 1. RESTAURANT TABLES SELECTOR -->
    <div class="pos-col-tables">
        <div class="pos-col-header">
            <span><i class="fa-solid fa-utensils"></i> Dining Tables</span>
            <span style="font-size:0.75rem; color:#64748b;">{{ $tables->count() }} Total</span>
        </div>

        <div class="table-filter-bar">
            <button type="button" class="table-filter-btn active" onclick="filterTables('all', this)">All ({{ $tables->count() }})</button>
            <button type="button" class="table-filter-btn" onclick="filterTables('available', this)">Available ({{ $tables->where('status', 'available')->count() }})</button>
            <button type="button" class="table-filter-btn" onclick="filterTables('occupied', this)">Occupied ({{ $tables->where('status', 'occupied')->count() }})</button>
        </div>

        <div class="pos-tables-scroll" id="tablesList">
            @foreach($tables as $tbl)
                <button type="button" 
                        class="pos-table-btn {{ $tbl->status }} {{ request('table') == $tbl->id ? 'selected' : '' }}" 
                        id="table-btn-{{ $tbl->id }}" 
                        onclick="selectTable({{ $tbl->id }}, '{{ $tbl->table_number }}', '{{ $tbl->status }}')">
                    <div>
                        <div style="font-weight:800; font-size:0.95rem; color:#0f172a;">{{ $tbl->table_number }}</div>
                        <div style="font-size:0.72rem; color:#64748b;">Cap: {{ $tbl->capacity }} guests</div>
                    </div>
                    <div style="text-align: right;">
                        <span class="pos-table-badge badge-{{ $tbl->status }}">{{ ucfirst($tbl->status) }}</span>
                        @if($tbl->status === 'occupied' && $tbl->activeOrder)
                            <div style="font-size:0.75rem; font-weight:800; color:var(--admin-primary); margin-top:3px;">
                                ৳{{ number_format($tbl->activeOrder->total_amount) }}
                            </div>
                        @endif
                    </div>
                </button>
            @endforeach
        </div>
    </div>

    <!-- 2. MENU ITEM PICKER -->
    <div class="pos-col-menu">
        <div class="pos-menu-topbar">
            <div class="pos-search-wrapper">
                <i class="fa-solid fa-magnifying-glass pos-search-icon"></i>
                <input type="text" id="posSearchInput" class="pos-search-input" placeholder="Search menu items..." oninput="filterPosProducts()">
                <button type="button" id="posSearchClear" class="pos-search-clear" onclick="clearPosSearch()" style="display:none;">&times;</button>
            </div>
            <span class="pos-menu-count" id="posProductCount">{{ $products->count() }} Items</span>
        </div>

        <!-- Category Tabs -->
        <div class="pos-category-tabs">
            <button type="button" class="cat-tab-btn active" onclick="filterCategory('all', this)">All Items</button>
            @foreach($categories as $cat)
                <button type="button" class="cat-tab-btn" onclick="filterCategory('{{ $cat->id }}', this)">
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>

        <!-- Products Grid: 4 cards per row, large image on top, name and price below -->
        <div class="pos-products-grid" id="posProductsGrid">
            @foreach($products as $prod)
                <div class="pos-product-card" data-category="{{ $prod->category_id }}" data-name="{{ strtolower($prod->name) }}" onclick="openPosItemModal({{ $prod->id }})">
                    <div class="pos-prod-img-box">
                        @if($prod->image)
                            <img src="{{ asset('uploads/' . $prod->image) }}" alt="{{ $prod->name }}" class="pos-prod-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="pos-thumb-fallback" style="display:none;"><i class="fa-solid fa-pizza-slice"></i></div>
                        @else
                            <div class="pos-thumb-fallback"><i class="fa-solid fa-pizza-slice"></i></div>
                        @endif
                    </div>
                    <div class="pos-prod-body">
                        <div class="pos-prod-name" title="{{ $prod->name }}">{{ $prod->name }}</div>
                        <div class="pos-prod-price-row">
                            <span class="pos-prod-price">৳ {{ number_format($prod->base_price) }}</span>
                            <span class="pos-card-add-icon" title="Add to table"><i class="fa-solid fa-plus"></i></span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- 3. TABLE'S LIVE RUNNING BILL / ORDER -->
    <div class="pos-col-order">
        <div class="pos-col-header" style="background:#f8fafc;">
            <div>
                <span id="displaySelectedTableName" style="font-weight:800;">Select a Table</span>
                <div id="displayTableStatus" style="font-size:0.75rem; color:#64748b; font-weight:normal;">Click a table on the left</div>
            </div>
            <button type="button" onclick="printReceipt()" style="background:none; border:none; cursor:pointer; font-size:1.1rem; color:#64748b;" title="Print Receipt"><i class="fa-solid fa-print"></i></button>
        </div>

        <!-- Order Items on Table -->
        <div class="pos-order-items-scroll" id="posOrderItemsContainer">
            <div style="text-align: center; color: #94a3b8; padding: 40px 10px; font-size: 0.85rem;">
                Please select a table to view or add orders.
            </div>
        </div>

        <!-- Bill Totals & Complete Action -->
        <div class="pos-order-footer">
            <div class="pos-bill-line">
                <span>Subtotal</span>
                <strong id="posSubtotal">৳ 0</strong>
            </div>
            <div class="pos-bill-line">
                <span>VAT / Tax (5%)</span>
                <strong id="posVat">৳ 0</strong>
            </div>
            <div class="pos-bill-total">
                <span>Total Bill</span>
                <span id="posTotal">৳ 0</span>
            </div>

            <button type="button" class="btn-pos-pay" id="btnPosComplete" onclick="completeCurrentTableOrder()" disabled style="opacity: 0.6; cursor: not-allowed;">
                <i class="fa-solid fa-circle-check"></i> Complete Payment & Free Table
            </button>
        </div>
    </div>

</div>

<!-- MODAL: ADD ITEM WITH SIZES & CRUSTS TO TABLE -->
<div class="pos-modal" id="posAddModal">
    <div class="pos-modal-card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <h3 id="posModalProdName" style="font-size:1.1rem; font-weight:800;">Product Name</h3>
            <button type="button" onclick="closePosModal()" style="background:none; border:none; font-size:1.2rem; cursor:pointer; color:#64748b;"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <!-- Size Selection -->
        <div style="margin-bottom: 14px;" id="posSizeGroup">
            <label style="display:block; font-size:0.78rem; font-weight:700; color:#475569; margin-bottom:6px;">Select Size</label>
            <select id="posModalSizeSelect" class="form-control" style="width:100%; padding:8px;" onchange="recalcPosModalPrice()"></select>
        </div>

        <!-- Crust Selection -->
        <div style="margin-bottom: 14px;" id="posCrustGroup">
            <label style="display:block; font-size:0.78rem; font-weight:700; color:#475569; margin-bottom:6px;">Select Crust</label>
            <select id="posModalCrustSelect" class="form-control" style="width:100%; padding:8px;" onchange="recalcPosModalPrice()"></select>
        </div>

        <!-- Addons checkboxes -->
        <div style="margin-bottom: 14px;">
            <label style="display:block; font-size:0.78rem; font-weight:700; color:#475569; margin-bottom:6px;">Add-Ons</label>
            <div style="max-height: 120px; overflow-y:auto; border:1px solid #e2e8f0; border-radius:6px; padding:8px;" id="posModalAddonsList">
                @foreach($addons as $ad)
                    <label style="display:flex; justify-content:space-between; font-size:0.8rem; margin-bottom:6px; cursor:pointer;">
                        <span>
                            <input type="checkbox" value="{{ $ad->id }}" data-price="{{ $ad->price }}" class="pos-chk-addon" onchange="recalcPosModalPrice()">
                            @if(Str::startsWith($ad->icon, 'fa-') || Str::contains($ad->icon, 'fa-'))
                                <i class="{{ $ad->icon }}"></i>
                            @else
                                <i class="fa-solid fa-cheese"></i>
                            @endif {{ $ad->name }}
                        </span>
                        <strong>+৳{{ number_format($ad->price) }}</strong>
                    </label>
                @endforeach
            </div>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:20px;">
            <div style="font-size:1.2rem; font-weight:900; color:var(--admin-primary);" id="posModalPriceDisplay">
                ৳ 0
            </div>
            <button type="button" class="btn-quick-pos" onclick="submitItemToTable()">
                Add to Table Order <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>
    </div>
</div>
@endsection

@push('admin-scripts')
<script>
    let activeTableId = null;
    let activeTableName = '';
    let currentModalProd = null;
    let modalUnitPrice = 0;
    let modalCrustExtra = 0;
    let currentCategoryId = 'all';

    // Filter categories in POS
    function filterCategory(catId, btn) {
        currentCategoryId = catId;
        document.querySelectorAll('.cat-tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        applyProductFilters();
    }

    // Filter products by live search query
    function filterPosProducts() {
        const query = (document.getElementById('posSearchInput')?.value || '').trim();
        const clearBtn = document.getElementById('posSearchClear');
        if (clearBtn) {
            clearBtn.style.display = query ? 'block' : 'none';
        }
        applyProductFilters();
    }

    function clearPosSearch() {
        const searchInput = document.getElementById('posSearchInput');
        if (searchInput) searchInput.value = '';
        const clearBtn = document.getElementById('posSearchClear');
        if (clearBtn) clearBtn.style.display = 'none';
        applyProductFilters();
    }

    function applyProductFilters() {
        const query = (document.getElementById('posSearchInput')?.value || '').toLowerCase().trim();
        let visibleCount = 0;

        document.querySelectorAll('.pos-product-card').forEach(card => {
            const matchesCat = (currentCategoryId === 'all' || card.dataset.category === currentCategoryId);
            const name = (card.dataset.name || card.querySelector('.pos-prod-name')?.innerText || '').toLowerCase();
            const matchesSearch = !query || name.includes(query);

            if (matchesCat && matchesSearch) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const countElem = document.getElementById('posProductCount');
        if (countElem) {
            countElem.innerText = visibleCount + ' Items';
        }
    }

    // Filter Tables by status
    function filterTables(status, btn) {
        document.querySelectorAll('.table-filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        document.querySelectorAll('.pos-table-btn').forEach(tbl => {
            if (status === 'all' || tbl.classList.contains(status)) {
                tbl.style.display = 'flex';
            } else {
                tbl.style.display = 'none';
            }
        });
    }

    // Select Table
    function selectTable(tableId, tableName, status) {
        activeTableId = tableId;
        activeTableName = tableName;

        document.querySelectorAll('.pos-table-btn').forEach(b => b.classList.remove('selected'));
        const btn = document.getElementById('table-btn-' + tableId);
        if (btn) btn.classList.add('selected');

        document.getElementById('displaySelectedTableName').innerText = tableName;
        document.getElementById('displayTableStatus').innerText = 'Status: ' + status.toUpperCase();

        loadTableOrder(tableId);
    }

    // Fetch Table Order via AJAX
    function loadTableOrder(tableId) {
        fetch('/admin/pos/table/' + tableId)
            .then(res => res.json())
            .then(data => {
                renderTableOrder(data.order);
            });
    }

    // Render Order in Column 3
    function renderTableOrder(order) {
        const container = document.getElementById('posOrderItemsContainer');
        const payBtn = document.getElementById('btnPosComplete');

        if (!order || !order.items || order.items.length === 0) {
            container.innerHTML = `
                <div style="text-align: center; color: #94a3b8; padding: 40px 10px; font-size: 0.85rem;">
                    No active items on ${activeTableName}.<br>
                    Click any menu item to start table order.
                </div>
            `;
            document.getElementById('posSubtotal').innerText = '৳ 0';
            document.getElementById('posVat').innerText = '৳ 0';
            document.getElementById('posTotal').innerText = '৳ 0';
            payBtn.disabled = true;
            payBtn.style.opacity = '0.6';
            payBtn.style.cursor = 'not-allowed';
            return;
        }

        let html = '';
        order.items.forEach(item => {
            let addonsText = '';
            if (item.addons && item.addons.length > 0) {
                addonsText = item.addons.map(a => a.add_on_name).join(', ');
            }

            html += `
                <div class="pos-order-item-row">
                    <div>
                        <strong style="color:#0f172a;">${item.quantity}x ${item.product_name}</strong>
                        <div style="font-size:0.75rem; color:#64748b;">${item.size_name || ''} • ${item.crust_name || ''}</div>
                        ${addonsText ? `<div style="font-size:0.72rem; color:#0284c7;">+ ${addonsText}</div>` : ''}
                    </div>
                    <div style="text-align:right;">
                        <strong style="color:var(--admin-primary);">৳ ${Math.round(item.total_price).toLocaleString()}</strong>
                        <div>
                            <button type="button" onclick="removeItemFromTable(${item.id})" style="background:none; border:none; color:#ef4444; font-size:0.75rem; cursor:pointer; text-decoration:underline;">
                                Remove
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
        document.getElementById('posSubtotal').innerText = '৳ ' + Math.round(order.subtotal).toLocaleString();
        document.getElementById('posVat').innerText = '৳ ' + Math.round(order.vat_tax).toLocaleString();
        document.getElementById('posTotal').innerText = '৳ ' + Math.round(order.total_amount).toLocaleString();

        payBtn.disabled = false;
        payBtn.style.opacity = '1';
        payBtn.style.cursor = 'pointer';
    }

    // Open Modal to Customize Item for Table
    function openPosItemModal(productId) {
        if (!activeTableId) {
            alert('Please select a table first!');
            return;
        }

        fetch('/product-details/' + productId)
            .then(res => res.json())
            .then(data => {
                currentModalProd = data.product;
                document.getElementById('posModalProdName').innerText = currentModalProd.name;

                const sizeSelect = document.getElementById('posModalSizeSelect');
                sizeSelect.innerHTML = '';
                if (data.sizes && data.sizes.length > 0) {
                    data.sizes.forEach(s => {
                        sizeSelect.innerHTML += `<option value="${s.id}" data-name="${s.name}" data-price="${s.price}">${s.name} - ৳${Math.round(s.price)}</option>`;
                    });
                    document.getElementById('posSizeGroup').style.display = 'block';
                } else {
                    sizeSelect.innerHTML = `<option value="" data-name="Standard" data-price="${currentModalProd.base_price}">Standard - ৳${Math.round(currentModalProd.base_price)}</option>`;
                    document.getElementById('posSizeGroup').style.display = 'none';
                }

                const crustSelect = document.getElementById('posModalCrustSelect');
                crustSelect.innerHTML = '';
                if (data.crusts && data.crusts.length > 0) {
                    data.crusts.forEach(c => {
                        crustSelect.innerHTML += `<option value="${c.id}" data-name="${c.name}" data-extra="${c.extra_price}">${c.name} ${c.extra_price > 0 ? '(+৳' + Math.round(c.extra_price) + ')' : ''}</option>`;
                    });
                    document.getElementById('posCrustGroup').style.display = 'block';
                } else {
                    crustSelect.innerHTML = `<option value="" data-name="Standard" data-extra="0">Standard</option>`;
                    document.getElementById('posCrustGroup').style.display = 'none';
                }

                // Reset checkboxes
                document.querySelectorAll('.pos-chk-addon').forEach(chk => chk.checked = false);

                recalcPosModalPrice();
                document.getElementById('posAddModal').classList.add('open');
            });
    }

    function closePosModal() {
        document.getElementById('posAddModal').classList.remove('open');
    }

    function recalcPosModalPrice() {
        const sizeSelect = document.getElementById('posModalSizeSelect');
        const crustSelect = document.getElementById('posModalCrustSelect');

        modalUnitPrice = parseFloat(sizeSelect.options[sizeSelect.selectedIndex]?.dataset.price || currentModalProd.base_price);
        modalCrustExtra = parseFloat(crustSelect.options[crustSelect.selectedIndex]?.dataset.extra || 0);

        let addonsExtra = 0;
        document.querySelectorAll('.pos-chk-addon:checked').forEach(chk => {
            addonsExtra += parseFloat(chk.dataset.price || 0);
        });

        const total = modalUnitPrice + modalCrustExtra + addonsExtra;
        document.getElementById('posModalPriceDisplay').innerText = '৳ ' + Math.round(total).toLocaleString();
    }

    // Submit customized item to table order
    function submitItemToTable() {
        if (!activeTableId || !currentModalProd) return;

        const sizeSelect = document.getElementById('posModalSizeSelect');
        const crustSelect = document.getElementById('posModalCrustSelect');

        const selectedAddonIds = [];
        document.querySelectorAll('.pos-chk-addon:checked').forEach(chk => {
            selectedAddonIds.push(chk.value);
        });

        const payload = {
            product_id: currentModalProd.id,
            size_name: sizeSelect.options[sizeSelect.selectedIndex]?.dataset.name,
            unit_price: modalUnitPrice,
            crust_name: crustSelect.options[crustSelect.selectedIndex]?.dataset.name,
            crust_extra: modalCrustExtra,
            addons: selectedAddonIds,
            quantity: 1,
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        };

        fetch(`/admin/pos/table/${activeTableId}/add`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': payload._token,
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                closePosModal();
                renderTableOrder(data.order);
                // Update table button state
                const tblBtn = document.getElementById('table-btn-' + activeTableId);
                if (tblBtn) {
                    tblBtn.className = 'pos-table-btn occupied selected';
                }
            }
        });
    }

    function removeItemFromTable(itemId) {
        if (!confirm('Remove item from table order?')) return;

        fetch(`/admin/pos/item/${itemId}/remove`, {
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
                renderTableOrder(data.order);
                if (!data.order) {
                    const tblBtn = document.getElementById('table-btn-' + activeTableId);
                    if (tblBtn) {
                        tblBtn.className = 'pos-table-btn available selected';
                    }
                }
            }
        });
    }

    // Complete payment & free table
    function completeCurrentTableOrder() {
        if (!activeTableId) return;
        if (!confirm(`Mark payment complete for ${activeTableName} and set table as Available?`)) return;

        fetch(`/admin/pos/table/${activeTableId}/complete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ payment_method: 'cash' })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(`✓ ${activeTableName} payment completed and table is now Available!`);
                location.reload();
            }
        });
    }

    function printReceipt() {
        window.print();
    }

    // Auto select table if query string provided
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const tblId = urlParams.get('table');
        if (tblId) {
            const btn = document.getElementById('table-btn-' + tblId);
            if (btn) btn.click();
        } else {
            // Select Table 1 by default
            const firstTable = document.querySelector('.pos-table-btn');
            if (firstTable) firstTable.click();
        }
    });
</script>
@endpush
