@extends('layouts.admin')

@section('title', 'Point of Sale (POS) & Table Management | Italian Pizza Hut')
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
        min-height: 0;
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

    /* Products Grid: 4 cards per row, never collapses */
    .pos-products-grid {
        flex: 1;
        min-height: 0;
        overflow-y: auto;
        padding: 12px;
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        grid-auto-rows: minmax(195px, auto);
        gap: 12px;
        align-content: start;
    }

    @media (max-width: 1400px) {
        .pos-products-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 1100px) {
        .pos-products-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    .pos-product-card {
        min-height: 195px;
        height: 100%;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        user-select: none;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
        box-sizing: border-box;
    }

    .pos-product-card.pos-card-hidden {
        display: none !important;
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

    /* Top: Image Area */
    .pos-prod-img-box {
        width: 100%;
        height: 115px;
        min-height: 115px;
        max-height: 115px;
        flex-shrink: 0;
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
        max-height: 100px;
        object-fit: contain;
        transition: transform 0.25s ease;
        filter: drop-shadow(0 3px 6px rgba(0,0,0,0.08));
        display: block;
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
        padding: 10px 12px 12px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex: 1 0 auto;
        min-height: 75px;
        background: #ffffff;
        border-top: 1px solid #f1f5f9;
        box-sizing: border-box;
    }

    .pos-prod-name {
        font-size: 0.88rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.3;
        margin-bottom: 6px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        word-break: break-word;
    }

    .pos-prod-price-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: auto;
    }

    .pos-prod-price {
        font-size: 0.92rem;
        font-weight: 900;
        color: var(--admin-primary);
        letter-spacing: -0.2px;
    }

    .pos-card-add-icon {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.72rem;
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

    .btn-pos-print {
        background: #0f172a;
        color: #ffffff;
        border: none;
        border-radius: 8px;
        padding: 12px;
        font-size: 0.88rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s ease;
    }
    .btn-pos-print:hover:not(:disabled) {
        background: #1e293b;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.25);
    }

    /* Thermal Receipt Modal Preview */
    .thermal-modal {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .thermal-modal.open {
        display: flex;
    }
    .thermal-modal-box {
        background: #ffffff;
        border-radius: 12px;
        width: 100%;
        max-width: 440px;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        overflow: hidden;
    }
    .thermal-modal-top {
        padding: 14px 18px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8fafc;
    }
    .thermal-modal-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .thermal-paper-scroll {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        background: #e2e8f0;
        display: flex;
        justify-content: center;
    }
    .thermal-paper {
        background: #ffffff;
        width: 100%;
        max-width: 320px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.12);
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        box-sizing: border-box;
    }
    .thermal-modal-actions {
        padding: 14px 18px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        gap: 10px;
        background: #ffffff;
    }

    @media (max-width: 1200px) {
        .pos-container {
            grid-template-columns: 240px 1fr 340px;
            gap: 12px;
        }
    }

    /* PRINT RULES: Hide entire Admin Dashboard and print ONLY the receipt */
    @media print {
        html, body {
            background: #ffffff !important;
            margin: 0 !important;
            padding: 0 !important;
            height: auto !important;
            overflow: visible !important;
        }
        .admin-sidebar,
        .admin-header,
        .admin-main-wrap,
        .pos-container,
        .pos-modal,
        .alert-box,
        #adminUserDropdownWrap,
        .thermal-modal-top,
        .thermal-modal-actions,
        .thermal-paper-scroll {
            display: none !important;
        }

        #posDirectPrintContainer {
            display: block !important;
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 48mm !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 1mm !important;
            background: #ffffff !important;
            color: #000000 !important;
            border: none !important;
            box-shadow: none !important;
        }

        @page {
            size: auto;
            margin: 0mm !important;
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
                @php
                    $hasImg = !empty($prod->image) && file_exists(public_path('uploads/' . $prod->image));
                    $imgUrl = $hasImg ? asset('uploads/' . $prod->image) : (file_exists(public_path('images/pizza-hero.jpg')) ? asset('images/pizza-hero.jpg') : null);
                @endphp
                <div class="pos-product-card" data-category="{{ $prod->category_id }}" data-name="{{ strtolower($prod->name) }}" onclick="openPosItemModal({{ $prod->id }})">
                    <div class="pos-prod-img-box">
                        @if($imgUrl)
                            <img src="{{ $imgUrl }}" alt="{{ $prod->name }}" class="pos-prod-img" loading="lazy" onerror="this.onerror=null; this.src='{{ asset('images/pizza-hero.jpg') }}';">
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
            <div style="display:flex; align-items:center; gap:6px;">
                <button type="button" onclick="printPosKitchenInvoice()" id="btnPosKitchenHeader" style="background:#e0f2fe; border:none; cursor:pointer; font-size:0.82rem; color:#0369a1; padding:4px 8px; border-radius:6px; font-weight:700;" title="Print Kitchen KOT (রান্নাঘরের টোকেন)"><i class="fa-solid fa-utensils"></i> KOT</button>
                <button type="button" onclick="printPosInvoice()" id="btnPosPrintHeader" style="background:none; border:none; cursor:pointer; font-size:1.15rem; color:#64748b; padding:4px 8px; border-radius:6px;" title="Print Table Invoice"><i class="fa-solid fa-print"></i></button>
            </div>
        </div>

        <!-- POS Customer Selector Bar -->
        <div class="pos-customer-selector-bar" style="background:#ffffff; border-bottom:1px solid #e2e8f0; padding:8px 12px; position:relative;">
            <!-- When a customer is selected -->
            <div id="posSelectedCustomerBox" style="display:none; align-items:center; justify-content:space-between; background:#f0fdf4; border:1.5px solid #86efac; padding:6px 10px; border-radius:8px;">
                <div style="display:flex; align-items:center; gap:8px; overflow:hidden;">
                    <div style="width:28px; height:28px; border-radius:50%; background:#16a34a; color:#fff; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:800; flex-shrink:0;">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                    <div style="line-height:1.2; overflow:hidden;">
                        <div id="posCustDisplayName" style="font-weight:800; font-size:0.84rem; color:#15803d; white-space:nowrap; text-overflow:ellipsis; overflow:hidden;">Customer Name</div>
                        <div style="font-size:0.74rem; color:#475569; display:flex; align-items:center; gap:6px; margin-top:2px;">
                            <span id="posCustDisplayPhone" style="font-weight:600;">017xxxxxxxx</span>
                            <span id="posCustDisplayDueBadge" style="background:#fee2e2; color:#dc2626; font-size:0.7rem; font-weight:800; padding:1px 6px; border-radius:10px; display:none;">Due: ৳0</span>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="clearSelectedCustomer()" style="background:none; border:none; cursor:pointer; color:#ef4444; font-size:0.9rem; padding:4px 6px;" title="Remove / Change Customer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- When in search / select mode (Default) -->
            <div id="posSearchCustomerBox" style="position:relative;">
                <div style="display:flex; gap:6px; align-items:center;">
                    <div style="position:relative; flex:1;">
                        <i class="fa-solid fa-phone" style="position:absolute; left:9px; top:50%; transform:translateY(-50%); font-size:0.75rem; color:#94a3b8;"></i>
                        <input type="text" id="posCustSearchInput" class="form-control" placeholder="Search customer (Phone / Name)..." style="padding:6px 26px 6px 28px; font-size:0.82rem; border-radius:6px; height:34px;" oninput="onCustomerSearchInput(this.value)" autocomplete="off">
                        <button type="button" id="posClearSearchBtn" onclick="resetCustSearchInput()" style="display:none; position:absolute; right:7px; top:50%; transform:translateY(-50%); background:none; border:none; color:#94a3b8; cursor:pointer; font-size:0.75rem;">✕</button>
                    </div>
                    <button type="button" onclick="openQuickAddCustomerModal()" style="background:#0f172a; color:#fff; border:none; border-radius:6px; padding:0 12px; font-size:0.78rem; font-weight:700; cursor:pointer; white-space:nowrap; display:flex; align-items:center; gap:5px; height:34px;" title="Add New Customer">
                        <i class="fa-solid fa-user-plus"></i> + Add
                    </button>
                </div>

                <!-- Live Search Results Dropdown -->
                <div id="posCustSearchResults" style="display:none; position:absolute; left:0; right:0; top:100%; background:#fff; border:1px solid #cbd5e1; border-radius:8px; box-shadow:0 10px 25px rgba(0,0,0,0.15); z-index:99; max-height:220px; overflow-y:auto; margin-top:4px;"></div>
            </div>
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
            <div class="pos-bill-line" id="posDiscountLine" style="display:flex; justify-content:space-between; align-items:center; padding:2px 0;">
                <span style="display:flex; align-items:center; gap:5px; font-weight:700; color:#dc2626; font-size:0.83rem;">
                    <i class="fa-solid fa-tag"></i> Discount (ছাড়)
                </span>
                <div style="display:flex; align-items:center; gap:4px;">
                    <span style="color:#dc2626; font-weight:800; font-size:0.85rem;">-৳</span>
                    <input type="number" id="posOrderDiscountInput" min="0" step="1" value="0" placeholder="0" onchange="onPosOrderDiscountChange(this.value)" onkeyup="if(event.key==='Enter') onPosOrderDiscountChange(this.value)" disabled style="width:75px; text-align:right; padding:3px 6px; font-size:0.88rem; font-weight:800; border:1.5px solid #fca5a5; border-radius:6px; color:#dc2626; background:#fff;" title="Enter discount in Taka and press Enter">
                </div>
            </div>
            <div class="pos-bill-line" id="posVatLine" style="display:none;">
                <span>VAT / Tax</span>
                <strong id="posVat">৳ 0</strong>
            </div>
            <div class="pos-bill-total">
                <span>Total Bill</span>
                <span id="posTotal">৳ 0</span>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:8px; margin-top:10px;">
                <button type="button" class="btn-pos-print" id="btnPosKitchen" onclick="printPosKitchenInvoice()" disabled style="background:#0284c7; color:#fff; border:none; border-radius:6px; padding:7px; font-weight:700; opacity:0.5; cursor:not-allowed;">
                    <i class="fa-solid fa-utensils"></i> Kitchen KOT
                </button>
                <button type="button" class="btn-pos-print" id="btnPosPrintFooter" onclick="printPosInvoice()" disabled style="opacity:0.5; cursor:not-allowed;">
                    <i class="fa-solid fa-print"></i> Customer Bill
                </button>
            </div>
            <button type="button" class="btn-pos-pay" id="btnPosComplete" onclick="openPosPaymentModal()" disabled style="width:100%; margin-top:8px; opacity:0.5; cursor:not-allowed;">
                <i class="fa-solid fa-circle-check"></i> Pay / Due & Free Table
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

        <!-- Quantity Selector in Modal -->
        <div style="margin-bottom: 14px; display:flex; justify-content:space-between; align-items:center; background:#f8fafc; padding:8px 12px; border-radius:8px; border:1px solid #e2e8f0;">
            <label style="font-size:0.82rem; font-weight:700; color:#334155; margin:0;">Quantity (পরিমাণ)</label>
            <div style="display:flex; align-items:center; gap:8px;">
                <button type="button" onclick="stepModalQty(-1)" style="width:28px; height:28px; border-radius:6px; border:1px solid #cbd5e1; background:#fff; font-weight:900; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#334155;">-</button>
                <input type="number" id="posModalQuantity" value="1" min="1" style="width:52px; text-align:center; padding:4px; border:1px solid #cbd5e1; border-radius:6px; font-weight:800; font-size:0.95rem;" onchange="recalcPosModalPrice()">
                <button type="button" onclick="stepModalQty(1)" style="width:28px; height:28px; border-radius:6px; border:1px solid #cbd5e1; background:#fff; font-weight:900; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#334155;">+</button>
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

<!-- MODAL: POS PAYMENT WITH PAID / DUE / PARTIAL -->
<div class="pos-modal" id="posPaymentModal">
    <div class="pos-modal-card" style="max-width:440px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
            <h3 style="font-size:1.1rem; font-weight:800; margin:0;"><i class="fa-solid fa-cash-register" style="color:var(--admin-primary);"></i> Payment & Free Table</h3>
            <button type="button" onclick="closePosPaymentModal()" style="background:none; border:none; font-size:1.2rem; cursor:pointer; color:#64748b;"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:12px; margin-bottom:14px;">
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:0.84rem; color:#64748b; margin-bottom:5px;">
                <span>Subtotal:</span>
                <strong id="posModalSubtotal" style="color:#1e293b; font-size:0.95rem;">৳ 0</strong>
            </div>
            <div style="display:flex; justify-content:space-between; align-items:center; font-size:0.84rem; color:#dc2626; margin-bottom:6px;">
                <span style="font-weight:700;"><i class="fa-solid fa-tag"></i> Discount (ছাড়):</span>
                <div style="display:flex; align-items:center; gap:4px;">
                    <span style="font-weight:800; font-size:0.85rem;">-৳</span>
                    <input type="number" id="posModalDiscountInput" min="0" step="1" value="0" placeholder="0" oninput="onPosModalDiscountChange()" style="width:80px; text-align:right; padding:3px 6px; font-weight:800; font-size:0.92rem; border:1.5px solid #fca5a5; border-radius:6px; color:#dc2626; background:#fff;">
                </div>
            </div>
            <div style="border-top:1px dashed #cbd5e1; padding-top:6px; display:flex; justify-content:space-between; align-items:center;">
                <span style="font-size:0.88rem; font-weight:800; color:#1e293b;">Net Payable:</span>
                <span style="font-size:1.6rem; font-weight:900; color:var(--admin-primary);" id="posModalBillTotal">৳ 0</span>
            </div>
        </div>

        <!-- Customer Info -->
        <div style="margin-bottom:10px;">
            <label style="display:block; font-size:0.78rem; font-weight:700; color:#475569; margin-bottom:4px;">Customer Name</label>
            <input type="text" id="posCustomerName" class="form-control" placeholder="e.g. Dine-in / Walk-in / Name" style="padding:7px 10px;">
        </div>

        <div style="margin-bottom:12px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                <label style="font-size:0.78rem; font-weight:700; color:#475569; margin:0;">Customer Phone (Required if Due)</label>
                <span id="posPaymentCustomerDueBadge" style="font-size:0.72rem; color:#dc2626; font-weight:800; display:none;">Previous Due: ৳0</span>
            </div>
            <input type="text" id="posCustomerPhone" class="form-control" placeholder="e.g. 017xxxxxxxx" style="padding:7px 10px;" oninput="onPaymentPhoneInput(this.value)">
        </div>

        <!-- Payment Option: Full, Partial, Due -->
        <div style="margin-bottom:12px;">
            <label style="display:block; font-size:0.78rem; font-weight:700; color:#475569; margin-bottom:6px;">Payment Option</label>
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:6px;">
                <button type="button" id="payOptionPaid" onclick="selectPayOption('paid')" class="btn btn-sm btn-primary" style="padding:8px 4px; font-weight:700; font-size:0.8rem;">
                    <i class="fa-solid fa-check"></i> Full Paid
                </button>
                <button type="button" id="payOptionPartial" onclick="selectPayOption('partial')" class="btn btn-sm btn-secondary" style="padding:8px 4px; font-weight:700; font-size:0.8rem;">
                    <i class="fa-solid fa-circle-half-stroke"></i> Partial
                </button>
                <button type="button" id="payOptionDue" onclick="selectPayOption('due')" class="btn btn-sm btn-secondary" style="padding:8px 4px; font-weight:700; font-size:0.8rem;">
                    <i class="fa-solid fa-clock"></i> Full Due (বাকি)
                </button>
            </div>
        </div>

        <!-- Paid Amount & Remaining Due Inputs -->
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:12px;" id="posAmountsRow">
            <div>
                <label style="display:block; font-size:0.75rem; font-weight:700; color:#16a34a; margin-bottom:4px;">Paid Cash (৳)</label>
                <input type="number" step="1" min="0" id="posPaidAmountInput" class="form-control" style="font-weight:800; font-size:1rem; color:#16a34a;" oninput="onPosPaidAmountChange()">
            </div>
            <div>
                <label style="display:block; font-size:0.75rem; font-weight:700; color:#dc2626; margin-bottom:4px;">Due Balance (৳)</label>
                <input type="number" id="posDueAmountDisplay" class="form-control" readonly style="font-weight:800; font-size:1rem; color:#dc2626; background:#fef2f2;">
            </div>
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block; font-size:0.78rem; font-weight:700; color:#475569; margin-bottom:4px;">Payment Method (পেমেন্ট মাধ্যম)</label>
            <select id="posPaymentMethod" class="form-control" style="padding:8px 10px; font-weight:600; font-size:0.88rem;">
                <option value="cash" selected>💵 Cash (ক্যাশ)</option>
                <option value="bank">🏦 Bank (ব্যাংক)</option>
                <option value="card">💳 Card (কার্ড)</option>
                <option value="bkash">📱 bKash (বিকাশ)</option>
                <option value="nagad">📱 Nagad (নগদ)</option>
                <option value="rocket">📱 Rocket (রকেট)</option>
            </select>
        </div>

        <div style="display:flex; gap:10px;">
            <button type="button" class="btn btn-primary" onclick="submitPosPayment()" style="flex:1; padding:10px; font-weight:800;">
                <i class="fa-solid fa-circle-check"></i> Complete & Free Table
            </button>
            <button type="button" class="btn btn-secondary" onclick="closePosPaymentModal()">Cancel</button>
        </div>
    </div>
</div>

<!-- MODAL: QUICK ADD CUSTOMER -->
<div class="pos-modal" id="posQuickAddCustomerModal">
    <div class="pos-modal-card" style="max-width:380px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
            <h3 style="font-size:1.05rem; font-weight:800; margin:0; display:flex; align-items:center; gap:6px;">
                <i class="fa-solid fa-user-plus" style="color:var(--admin-primary);"></i> Quick Add Customer
            </h3>
            <button type="button" onclick="closeQuickAddCustomerModal()" style="background:none; border:none; font-size:1.2rem; cursor:pointer; color:#64748b;"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <div style="margin-bottom:12px;">
            <label style="display:block; font-size:0.78rem; font-weight:700; color:#475569; margin-bottom:4px;">Phone Number (মোবাইল নম্বর) *</label>
            <input type="text" id="quickAddPhone" class="form-control" placeholder="e.g. 017xxxxxxxx" style="padding:7px 10px; font-size:0.85rem;" required>
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block; font-size:0.78rem; font-weight:700; color:#475569; margin-bottom:4px;">Customer Name (কাস্টমারের নাম) *</label>
            <input type="text" id="quickAddName" class="form-control" placeholder="e.g. Rahim Ahmed" style="padding:7px 10px; font-size:0.85rem;" required>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:8px;">
            <button type="button" onclick="closeQuickAddCustomerModal()" class="btn btn-secondary btn-sm" style="padding:6px 14px;">Cancel</button>
            <button type="button" onclick="submitQuickAddCustomer()" class="btn-quick-pos" style="padding:6px 16px; font-size:0.85rem;">
                <i class="fa-solid fa-check"></i> Save & Select
            </button>
        </div>
    </div>
</div>

<!-- MODAL: RESTAURANT THERMAL INVOICE PREVIEW -->
<div class="thermal-modal" id="posReceiptModal">
    <div class="thermal-modal-box">
        <div class="thermal-modal-top">
            <div class="thermal-modal-title">
                <i class="fa-solid fa-receipt" style="color:var(--admin-primary);"></i> Restaurant Invoice
            </div>
            <div style="display:flex; align-items:center; gap:6px;">
                <span style="font-size:0.75rem; font-weight:700; color:#64748b;">Paper:</span>
                <div style="display:inline-flex; background:#e2e8f0; border-radius:6px; padding:2px;">
                    <button type="button" id="btnReceiptSize58" onclick="setReceiptSize('58mm')" style="padding:4px 10px; font-size:0.75rem; font-weight:800; border:none; border-radius:5px; cursor:pointer; background:var(--admin-primary); color:#fff;">58mm</button>
                    <button type="button" id="btnReceiptSize80" onclick="setReceiptSize('80mm')" style="padding:4px 10px; font-size:0.75rem; font-weight:800; border:none; border-radius:5px; cursor:pointer; background:transparent; color:#64748b;">80mm</button>
                </div>
                <button type="button" onclick="closePosReceiptModal()" style="background:none; border:none; font-size:1.25rem; cursor:pointer; color:#64748b; margin-left:6px;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
        <div style="font-size:0.72rem; color:#475569; background:#f1f5f9; padding:5px 12px; border-bottom:1px solid #e2e8f0; text-align:center;">
            💡 <strong>Print Tip:</strong> Browser Print ডায়ালগে Margins: <strong>None</strong> দিন যাতে সাইড কেটে না যায়।
        </div>
        <div class="thermal-paper-scroll">
            <div class="thermal-paper" id="thermalPaperReceipt">
                <!-- Thermal receipt content injected by JS -->
            </div>
        </div>
        <div class="thermal-modal-actions">
            <button type="button" onclick="executePrint()" class="btn-quick-pos" style="flex:1; justify-content:center;">
                <i class="fa-solid fa-print"></i> Print Receipt Now
            </button>
            <button type="button" onclick="closePosReceiptModal()" style="padding:10px 18px; border:1px solid #cbd5e1; background:#ffffff; border-radius:8px; font-weight:700; color:#475569; cursor:pointer;">
                Close
            </button>
        </div>
    </div>
</div>

<!-- Hidden container for direct @media print fallback and isolated iframe printing -->
<div id="posDirectPrintContainer" style="display:none;"></div>
<iframe id="posPrintIframe" style="position:fixed; right:0; bottom:0; width:0; height:0; border:0; visibility:hidden;"></iframe>
@endsection

@push('admin-scripts')
<script>
    let activeTableId = null;
    let activeTableName = '';
    let currentTableOrder = null;
    let currentModalProd = null;
    let modalUnitPrice = 0;
    let modalCrustExtra = 0;
    let currentCategoryId = 'all';
    let reloadAfterPrint = false;

    // Restaurant details for invoice
    window.posSettings = {!! json_encode([
        'name' => $restaurantName ?? 'ITALIAN PIZZA HUT',
        'address' => $restaurantAddress ?? 'House 14, Road 11, Block D, Gulshan-2, Dhaka',
        'phone' => $restaurantPhone ?? '+880 1711-223344',
    ]) !!};

    // Filter categories in POS
    function filterCategory(catId, btn) {
        currentCategoryId = String(catId).trim();
        document.querySelectorAll('.cat-tab-btn').forEach(b => b.classList.remove('active'));
        if (btn) btn.classList.add('active');
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
            const cardCat = String(card.dataset.category || '').trim();
            const matchesCat = (currentCategoryId === 'all' || cardCat === currentCategoryId);
            const name = (card.dataset.name || card.querySelector('.pos-prod-name')?.innerText || '').toLowerCase();
            const matchesSearch = !query || name.includes(query);

            if (matchesCat && matchesSearch) {
                card.classList.remove('pos-card-hidden');
                card.style.setProperty('display', 'flex', 'important');
                visibleCount++;
            } else {
                card.classList.add('pos-card-hidden');
                card.style.setProperty('display', 'none', 'important');
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

        clearSelectedCustomer(false);
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
        currentTableOrder = order;
        const container = document.getElementById('posOrderItemsContainer');
        const payBtn = document.getElementById('btnPosComplete');
        const printBtnFooter = document.getElementById('btnPosPrintFooter');
        const printBtnHeader = document.getElementById('btnPosPrintHeader');

        // Sync customer UI with order
        if (order && order.user) {
            selectSearchedCustomer({
                id: order.user.id,
                name: order.user.name,
                phone: order.user.phone || '',
                due_amount: 0
            }, false);
        } else if (order && order.customer_phone && order.customer_phone !== 'Walk-in' && !order.customer_phone.startsWith('Walk')) {
            selectSearchedCustomer({
                id: order.user_id || null,
                name: order.customer_name || 'Customer',
                phone: order.customer_phone,
                due_amount: 0
            }, false);
        } else if (!selectedCustomer) {
            clearSelectedCustomer(false);
        }

        if (!order || !order.items || order.items.length === 0) {
            container.innerHTML = `
                <div style="text-align: center; color: #94a3b8; padding: 40px 10px; font-size: 0.85rem;">
                    No active items on ${activeTableName || 'this table'}.<br>
                    Click any menu item to start table order.
                </div>
            `;
            document.getElementById('posSubtotal').innerText = '৳ 0';
            document.getElementById('posVat').innerText = '৳ 0';
            document.getElementById('posTotal').innerText = '৳ 0';
            const discInput = document.getElementById('posOrderDiscountInput');
            if (discInput) {
                discInput.value = 0;
                discInput.disabled = true;
            }

            if (payBtn) {
                payBtn.disabled = true;
                payBtn.style.opacity = '0.5';
                payBtn.style.cursor = 'not-allowed';
            }
            if (printBtnFooter) {
                printBtnFooter.disabled = true;
                printBtnFooter.style.opacity = '0.5';
                printBtnFooter.style.cursor = 'not-allowed';
            }
            if (printBtnHeader) {
                printBtnHeader.disabled = true;
                printBtnHeader.style.opacity = '0.4';
                printBtnHeader.style.cursor = 'not-allowed';
            }
            return;
        }

        let html = '';
        order.items.forEach(item => {
            // Clean size and crust text
            let variationParts = [];
            if (item.size_name && item.size_name.toLowerCase() !== 'standard') {
                variationParts.push(item.size_name);
            }
            if (item.crust_name && item.crust_name.toLowerCase() !== 'standard') {
                variationParts.push(item.crust_name);
            }
            let variationText = variationParts.join(' • ');

            // Add-ons text
            let addonsText = '';
            if (item.addons && item.addons.length > 0) {
                addonsText = item.addons.map(a => a.add_on_name).join(', ');
            }

            html += `
                <div class="pos-order-item-row" style="padding:10px 0; border-bottom:1px solid #f1f5f9;">
                    <div style="flex:1;">
                        <div style="display:flex; align-items:center; gap:6px;">
                            <div style="display:inline-flex; align-items:center; background:#f1f5f9; border-radius:6px; padding:2px;">
                                <button type="button" onclick="updateItemQty(${item.id}, 'decrease')" style="width:24px; height:24px; border:none; background:#fff; border-radius:4px; font-weight:900; font-size:0.85rem; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#334155; box-shadow:0 1px 2px rgba(0,0,0,0.06);">-</button>
                                <span style="font-weight:800; font-size:0.85rem; min-width:24px; text-align:center; color:#0f172a;">${item.quantity}</span>
                                <button type="button" onclick="updateItemQty(${item.id}, 'increase')" style="width:24px; height:24px; border:none; background:#fff; border-radius:4px; font-weight:900; font-size:0.85rem; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#334155; box-shadow:0 1px 2px rgba(0,0,0,0.06);">+</button>
                            </div>
                            <strong style="color:#0f172a; font-size:0.88rem;">${item.product_name}</strong>
                        </div>
                        ${variationText ? `<div style="font-size:0.75rem; color:#64748b; margin-top:2px; padding-left:4px;">${variationText}</div>` : ''}
                        ${addonsText ? `<div style="font-size:0.72rem; color:#0284c7; margin-top:2px; padding-left:4px;"><i class="fa-solid fa-plus" style="font-size:0.65rem;"></i> ${addonsText}</div>` : ''}
                        ${item.special_instructions ? `<div style="font-size:0.7rem; color:#64748b; font-style:italic; margin-top:2px; padding-left:4px;">Note: ${item.special_instructions}</div>` : ''}
                    </div>
                    <div style="text-align:right; margin-left:10px;">
                        <strong style="color:var(--admin-primary); font-size:0.92rem; display:block;">৳ ${Math.round(item.total_price).toLocaleString()}</strong>
                        <button type="button" onclick="removeItemFromTable(${item.id})" style="background:none; border:none; color:#ef4444; font-size:0.72rem; cursor:pointer; text-decoration:underline; margin-top:4px;">
                            Remove
                        </button>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
        document.getElementById('posSubtotal').innerText = '৳ ' + Math.round(order.subtotal).toLocaleString();
        document.getElementById('posVat').innerText = '৳ ' + Math.round(order.vat_tax).toLocaleString();
        document.getElementById('posTotal').innerText = '৳ ' + Math.round(order.total_amount).toLocaleString();
        const discInputActive = document.getElementById('posOrderDiscountInput');
        if (discInputActive) {
            discInputActive.disabled = false;
            discInputActive.value = Math.round(order.discount || 0);
        }

        const btnKitchen = document.getElementById('btnPosKitchen');
        const btnKitchenHdr = document.getElementById('btnPosKitchenHeader');

        if (payBtn) {
            payBtn.disabled = false;
            payBtn.style.opacity = '1';
            payBtn.style.cursor = 'pointer';
        }
        if (printBtnFooter) {
            printBtnFooter.disabled = false;
            printBtnFooter.style.opacity = '1';
            printBtnFooter.style.cursor = 'pointer';
        }
        if (printBtnHeader) {
            printBtnHeader.disabled = false;
            printBtnHeader.style.opacity = '1';
            printBtnHeader.style.cursor = 'pointer';
        }
        if (btnKitchen) {
            btnKitchen.disabled = false;
            btnKitchen.style.opacity = '1';
            btnKitchen.style.cursor = 'pointer';
        }
        if (btnKitchenHdr) {
            btnKitchenHdr.disabled = false;
            btnKitchenHdr.style.opacity = '1';
            btnKitchenHdr.style.cursor = 'pointer';
        }
    }

    function updateItemQty(itemId, action) {
        fetch(`/admin/pos/item/${itemId}/update-qty`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ action: action })
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
                    sizeSelect.innerHTML = `<option value="" data-name="" data-price="${currentModalProd.base_price}">Base Price - ৳${Math.round(currentModalProd.base_price)}</option>`;
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
                    crustSelect.innerHTML = `<option value="" data-name="" data-extra="0">None</option>`;
                    document.getElementById('posCrustGroup').style.display = 'none';
                }

                // Reset checkboxes & quantity
                document.querySelectorAll('.pos-chk-addon').forEach(chk => chk.checked = false);
                const qInput = document.getElementById('posModalQuantity');
                if (qInput) qInput.value = 1;

                recalcPosModalPrice();
                document.getElementById('posAddModal').classList.add('open');
            });
    }

    function stepModalQty(delta) {
        const input = document.getElementById('posModalQuantity');
        let q = (parseInt(input.value) || 1) + delta;
        if (q < 1) q = 1;
        input.value = q;
        recalcPosModalPrice();
    }

    function closePosModal() {
        document.getElementById('posAddModal').classList.remove('open');
    }

    function recalcPosModalPrice() {
        const sizeSelect = document.getElementById('posModalSizeSelect');
        const crustSelect = document.getElementById('posModalCrustSelect');
        const qty = parseInt(document.getElementById('posModalQuantity')?.value) || 1;

        modalUnitPrice = parseFloat(sizeSelect.options[sizeSelect.selectedIndex]?.dataset.price || currentModalProd.base_price);
        modalCrustExtra = parseFloat(crustSelect.options[crustSelect.selectedIndex]?.dataset.extra || 0);

        let addonsExtra = 0;
        document.querySelectorAll('.pos-chk-addon:checked').forEach(chk => {
            addonsExtra += parseFloat(chk.dataset.price || 0);
        });

        const total = (modalUnitPrice + modalCrustExtra + addonsExtra) * qty;
        document.getElementById('posModalPriceDisplay').innerText = '৳ ' + Math.round(total).toLocaleString() + (qty > 1 ? ` (${qty}x)` : '');
    }

    // Submit customized item to table order
    function submitItemToTable() {
        if (!activeTableId || !currentModalProd) return;

        const sizeSelect = document.getElementById('posModalSizeSelect');
        const crustSelect = document.getElementById('posModalCrustSelect');
        const qty = parseInt(document.getElementById('posModalQuantity')?.value) || 1;

        const selectedAddonIds = [];
        document.querySelectorAll('.pos-chk-addon:checked').forEach(chk => {
            selectedAddonIds.push(chk.value);
        });

        const payload = {
            product_id: currentModalProd.id,
            size_name: (sizeSelect.options[sizeSelect.selectedIndex]?.dataset.name || '').trim(),
            unit_price: modalUnitPrice,
            crust_name: (crustSelect.options[crustSelect.selectedIndex]?.dataset.name || '').trim(),
            crust_extra: modalCrustExtra,
            addons: selectedAddonIds,
            quantity: qty,
            user_id: selectedCustomer ? selectedCustomer.id : null,
            customer_name: selectedCustomer ? selectedCustomer.name : null,
            customer_phone: selectedCustomer ? selectedCustomer.phone : null,
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

    // ==========================================
    // POS CUSTOMER SEARCH & QUICK ADD LOGIC
    // ==========================================
    let selectedCustomer = null;
    let custSearchTimer = null;

    function onCustomerSearchInput(query) {
        clearTimeout(custSearchTimer);
        const q = (query || '').trim();
        const clearBtn = document.getElementById('posClearSearchBtn');
        const resultsBox = document.getElementById('posCustSearchResults');

        if (clearBtn) clearBtn.style.display = q ? 'block' : 'none';

        if (!q || q.length < 2) {
            if (resultsBox) {
                resultsBox.style.display = 'none';
                resultsBox.innerHTML = '';
            }
            return;
        }

        custSearchTimer = setTimeout(() => {
            fetch('/admin/pos/customers/search?q=' + encodeURIComponent(q))
                .then(res => res.json())
                .then(customers => {
                    let html = '';
                    if (customers && customers.length > 0) {
                        customers.forEach(c => {
                            const dueBadge = c.due_amount > 0 
                                ? `<span style="background:#fee2e2; color:#dc2626; font-size:0.72rem; font-weight:800; padding:1px 6px; border-radius:10px;">Due: ৳${Math.round(c.due_amount)}</span>` 
                                : '';
                            html += `
                                <div onclick='selectSearchedCustomer(${JSON.stringify(c)})' style="padding:8px 12px; border-bottom:1px solid #f1f5f9; cursor:pointer; display:flex; justify-content:space-between; align-items:center;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
                                    <div style="line-height:1.2;">
                                        <strong style="color:#0f172a; font-size:0.84rem;">${c.name}</strong>
                                        <div style="color:#64748b; font-size:0.75rem;">${c.phone}</div>
                                    </div>
                                    ${dueBadge}
                                </div>
                            `;
                        });
                        html += `
                            <div style="padding:6px 12px; background:#f8fafc; text-align:center; border-top:1px solid #e2e8f0;">
                                <button type="button" onclick="openQuickAddWithPhone('${q}')" style="background:none; border:none; color:var(--admin-primary); font-weight:700; font-size:0.75rem; cursor:pointer;">
                                    + Add "${q}" as New Customer
                                </button>
                            </div>
                        `;
                    } else {
                        html = `
                            <div style="padding:14px 12px; text-align:center; font-size:0.8rem; color:#64748b;">
                                <div>No customer found with "<strong>${q}</strong>"</div>
                                <button type="button" onclick="openQuickAddWithPhone('${q}')" style="margin-top:6px; background:var(--admin-primary); color:#fff; border:none; border-radius:6px; padding:5px 12px; font-weight:700; font-size:0.75rem; cursor:pointer;">
                                    <i class="fa-solid fa-user-plus"></i> + Add as New Customer
                                </button>
                            </div>
                        `;
                    }
                    resultsBox.innerHTML = html;
                    resultsBox.style.display = 'block';
                });
        }, 250);
    }

    function resetCustSearchInput() {
        const inp = document.getElementById('posCustSearchInput');
        if (inp) inp.value = '';
        const clr = document.getElementById('posClearSearchBtn');
        if (clr) clr.style.display = 'none';
        const res = document.getElementById('posCustSearchResults');
        if (res) {
            res.style.display = 'none';
            res.innerHTML = '';
        }
    }

    function selectSearchedCustomer(c, syncBackend = true) {
        selectedCustomer = c;
        resetCustSearchInput();

        document.getElementById('posSearchCustomerBox').style.display = 'none';
        const selBox = document.getElementById('posSelectedCustomerBox');
        selBox.style.display = 'flex';
        document.getElementById('posCustDisplayName').innerText = c.name;
        document.getElementById('posCustDisplayPhone').innerText = c.phone || 'No phone';

        const dueBadge = document.getElementById('posCustDisplayDueBadge');
        if (c.due_amount > 0) {
            dueBadge.innerText = 'Due: ৳' + Math.round(c.due_amount).toLocaleString();
            dueBadge.style.display = 'inline-block';
        } else {
            dueBadge.style.display = 'none';
        }

        // Sync with active table order if available
        if (syncBackend && activeTableId && currentTableOrder) {
            fetch(`/admin/pos/table/${activeTableId}/customer`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    user_id: c.id,
                    customer_name: c.name,
                    customer_phone: c.phone
                })
            });
        }
    }

    function clearSelectedCustomer(syncBackend = true) {
        selectedCustomer = null;
        document.getElementById('posSelectedCustomerBox').style.display = 'none';
        document.getElementById('posSearchCustomerBox').style.display = 'block';
        resetCustSearchInput();

        if (syncBackend && activeTableId && currentTableOrder) {
            fetch(`/admin/pos/table/${activeTableId}/customer`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    user_id: null,
                    customer_name: `Dine-in (${activeTableName})`,
                    customer_phone: 'Walk-in'
                })
            });
        }
    }

    function openQuickAddCustomerModal() {
        document.getElementById('quickAddPhone').value = '';
        document.getElementById('quickAddName').value = '';
        document.getElementById('posQuickAddCustomerModal').classList.add('open');
        document.getElementById('quickAddPhone').focus();
    }

    function openQuickAddWithPhone(phone) {
        resetCustSearchInput();
        document.getElementById('quickAddPhone').value = phone;
        document.getElementById('quickAddName').value = '';
        document.getElementById('posQuickAddCustomerModal').classList.add('open');
        document.getElementById('quickAddName').focus();
    }

    function closeQuickAddCustomerModal() {
        document.getElementById('posQuickAddCustomerModal').classList.remove('open');
    }

    function submitQuickAddCustomer() {
        const phone = document.getElementById('quickAddPhone').value.trim();
        const name = document.getElementById('quickAddName').value.trim();

        if (!phone) {
            alert('Please enter a phone number.');
            document.getElementById('quickAddPhone').focus();
            return;
        }
        if (!name) {
            alert('Please enter customer name.');
            document.getElementById('quickAddName').focus();
            return;
        }

        fetch('/admin/pos/customers/quick-add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name: name, phone: phone })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.customer) {
                closeQuickAddCustomerModal();
                selectSearchedCustomer(data.customer, true);
            } else {
                alert(data.message || 'Error saving customer.');
            }
        })
        .catch(err => {
            alert('Failed to add customer. Please try again.');
        });
    }

    function onPaymentPhoneInput(val) {
        const p = val.trim();
        const badge = document.getElementById('posPaymentCustomerDueBadge');
        if (p.length >= 6) {
            fetch('/admin/pos/customers/search?q=' + encodeURIComponent(p))
                .then(res => res.json())
                .then(customers => {
                    const match = customers.find(c => c.phone === p);
                    if (match) {
                        if (!document.getElementById('posCustomerName').value || document.getElementById('posCustomerName').value.startsWith('Dine-in (')) {
                            document.getElementById('posCustomerName').value = match.name;
                        }
                        selectedCustomer = match;
                        if (match.due_amount > 0 && badge) {
                            badge.innerText = 'Previous Due: ৳' + Math.round(match.due_amount).toLocaleString();
                            badge.style.display = 'inline-block';
                        } else if (badge) {
                            badge.style.display = 'none';
                        }
                    } else if (badge) {
                        badge.style.display = 'none';
                    }
                });
        } else if (badge) {
            badge.style.display = 'none';
        }
    }

    // POS Payment Modal Logic
    let currentSelectedPayOption = 'paid';

    function openPosPaymentModal() {
        if (!activeTableId || !currentTableOrder) {
            alert('Please select an active table first.');
            return;
        }

        const subtotal = Math.round(currentTableOrder.subtotal || 0);
        const discount = Math.round(currentTableOrder.discount || 0);
        const billTotal = Math.max(0, subtotal - discount);

        const modalSubtotalEl = document.getElementById('posModalSubtotal');
        if (modalSubtotalEl) modalSubtotalEl.innerText = '৳ ' + subtotal.toLocaleString();
        const modalDiscInput = document.getElementById('posModalDiscountInput');
        if (modalDiscInput) modalDiscInput.value = discount;

        document.getElementById('posModalBillTotal').innerText = '৳ ' + billTotal.toLocaleString();
        
        const badge = document.getElementById('posPaymentCustomerDueBadge');
        if (selectedCustomer) {
            document.getElementById('posCustomerName').value = selectedCustomer.name;
            document.getElementById('posCustomerPhone').value = selectedCustomer.phone;
            if (selectedCustomer.due_amount > 0 && badge) {
                badge.innerText = 'Previous Due: ৳' + Math.round(selectedCustomer.due_amount).toLocaleString();
                badge.style.display = 'inline-block';
            } else if (badge) {
                badge.style.display = 'none';
            }
        } else {
            document.getElementById('posCustomerName').value = currentTableOrder.customer_name || `Dine-in (${activeTableName})`;
            document.getElementById('posCustomerPhone').value = (currentTableOrder.customer_phone === 'Walk-in') ? '' : (currentTableOrder.customer_phone || '');
            if (badge) badge.style.display = 'none';
        }

        selectPayOption('paid');
        document.getElementById('posPaymentModal').classList.add('open');
    }

    function onPosModalDiscountChange() {
        const subtotal = Math.round(currentTableOrder?.subtotal || 0);
        const discount = Math.max(0, parseFloat(document.getElementById('posModalDiscountInput').value) || 0);
        const billTotal = Math.max(0, subtotal - discount);

        document.getElementById('posModalBillTotal').innerText = '৳ ' + billTotal.toLocaleString();

        if (currentTableOrder) {
            currentTableOrder.discount = discount;
            currentTableOrder.total_amount = billTotal;
            const sidebarDiscInput = document.getElementById('posOrderDiscountInput');
            if (sidebarDiscInput) sidebarDiscInput.value = discount;
            document.getElementById('posTotal').innerText = '৳ ' + billTotal.toLocaleString();
        }

        selectPayOption(currentSelectedPayOption);
    }

    function onPosOrderDiscountChange(val) {
        if (!activeTableId || !currentTableOrder) return;
        const discount = Math.max(0, parseFloat(val) || 0);

        fetch(`/admin/pos/table/${activeTableId}/discount`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ discount: discount })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.order) {
                currentTableOrder = data.order;
                renderTableOrder(data.order);
            }
        })
        .catch(err => console.error(err));
    }

    function closePosPaymentModal() {
        document.getElementById('posPaymentModal').classList.remove('open');
    }

    function selectPayOption(opt) {
        currentSelectedPayOption = opt;
        const billTotal = Math.round(currentTableOrder?.total_amount || 0);

        const btnPaid = document.getElementById('payOptionPaid');
        const btnPartial = document.getElementById('payOptionPartial');
        const btnDue = document.getElementById('payOptionDue');

        btnPaid.className = 'btn btn-sm ' + (opt === 'paid' ? 'btn-primary' : 'btn-secondary');
        btnPartial.className = 'btn btn-sm ' + (opt === 'partial' ? 'btn-primary' : 'btn-secondary');
        btnDue.className = 'btn btn-sm ' + (opt === 'due' ? 'btn-primary' : 'btn-secondary');

        const paidInput = document.getElementById('posPaidAmountInput');
        const dueDisplay = document.getElementById('posDueAmountDisplay');

        if (opt === 'paid') {
            paidInput.value = billTotal;
            dueDisplay.value = 0;
            paidInput.readOnly = true;
        } else if (opt === 'due') {
            paidInput.value = 0;
            dueDisplay.value = billTotal;
            paidInput.readOnly = true;
        } else if (opt === 'partial') {
            paidInput.readOnly = false;
            if (parseFloat(paidInput.value) >= billTotal || parseFloat(paidInput.value) <= 0) {
                paidInput.value = Math.round(billTotal / 2);
            }
            onPosPaidAmountChange();
            paidInput.focus();
        }
    }

    function onPosPaidAmountChange() {
        const billTotal = Math.round(currentTableOrder?.total_amount || 0);
        const paid = Math.max(0, parseFloat(document.getElementById('posPaidAmountInput').value) || 0);
        const due = Math.max(0, billTotal - paid);
        document.getElementById('posDueAmountDisplay').value = due;
    }

    function submitPosPayment() {
        if (!activeTableId) return;

        const custName = document.getElementById('posCustomerName').value.trim();
        const custPhone = document.getElementById('posCustomerPhone').value.trim();
        const payMethod = document.getElementById('posPaymentMethod').value;
        const paidAmount = parseFloat(document.getElementById('posPaidAmountInput').value) || 0;
        const dueAmount = parseFloat(document.getElementById('posDueAmountDisplay').value) || 0;
        const discountAmount = parseFloat(document.getElementById('posModalDiscountInput')?.value) || 0;

        if (dueAmount > 0 && !custPhone) {
            alert('Customer Phone is REQUIRED for Due / Partial orders so it can be tracked in Due Customers!');
            document.getElementById('posCustomerPhone').focus();
            return;
        }

        const payload = {
            payment_status: currentSelectedPayOption,
            payment_method: payMethod,
            paid_amount: paidAmount,
            due_amount: dueAmount,
            discount: discountAmount,
            customer_name: custName,
            customer_phone: custPhone,
            user_id: selectedCustomer ? selectedCustomer.id : null,
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        };

        fetch(`/admin/pos/table/${activeTableId}/complete`, {
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
                closePosPaymentModal();
                const finishedOrder = data.order || currentTableOrder;
                const tblName = activeTableName;

                if (confirm(`✓ Payment recorded for ${tblName}!\nPaid: ৳${paidAmount} | Due: ৳${dueAmount}\n\nPrint customer invoice now?`)) {
                    reloadAfterPrint = true;
                    if (finishedOrder) {
                        printPosInvoice(finishedOrder, tblName);
                    } else {
                        location.reload();
                    }
                } else {
                    location.reload();
                }
            }
        });
    }

    // Receipt Paper Size Preference ('58mm' or '80mm', default 58mm)
    let currentReceiptSize = localStorage.getItem('pos_receipt_size') || '58mm';

    function setReceiptSize(size) {
        currentReceiptSize = size;
        try {
            localStorage.setItem('pos_receipt_size', size);
        } catch(e){}
        updateReceiptSizeUI();
        if (currentTableOrder) {
            const receiptHtml = generateReceiptHtml(currentTableOrder, activeTableName, currentReceiptSize);
            const modalContainer = document.getElementById('thermalPaperReceipt');
            if (modalContainer) modalContainer.innerHTML = receiptHtml;
            const directContainer = document.getElementById('posDirectPrintContainer');
            if (directContainer) directContainer.innerHTML = receiptHtml;
        }
    }

    function updateReceiptSizeUI() {
        const btn58 = document.getElementById('btnReceiptSize58');
        const btn80 = document.getElementById('btnReceiptSize80');
        if (btn58 && btn80) {
            if (currentReceiptSize === '80mm') {
                btn80.style.background = 'var(--admin-primary)';
                btn80.style.color = '#fff';
                btn58.style.background = 'transparent';
                btn58.style.color = '#64748b';
            } else {
                btn58.style.background = 'var(--admin-primary)';
                btn58.style.color = '#fff';
                btn80.style.background = 'transparent';
                btn80.style.color = '#64748b';
            }
        }
    }

    // Kitchen Order Ticket (KOT)
    function generateKitchenReceiptHtml(order, tableNumber, size = currentReceiptSize) {
        let orderDate = '';
        if (order.created_at) {
            const d = new Date(order.created_at);
            orderDate = d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short' }) + ', ' +
                        d.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
        } else {
            const d = new Date();
            orderDate = d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short' }) + ', ' +
                        d.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
        }

        const is80 = (size === '80mm');
        const wrapWidth = is80 ? '72mm' : '48mm';
        const fontSize = is80 ? '11px' : '9.5px';
        const itemFont = is80 ? '16px' : '13px';

        let itemsHtml = '';
        (order.items || []).forEach(item => {
            let variationParts = [];
            if (item.size_name && item.size_name.toLowerCase() !== 'standard') {
                variationParts.push('SIZE: ' + item.size_name.toUpperCase());
            }
            if (item.crust_name && item.crust_name.toLowerCase() !== 'standard') {
                variationParts.push('CRUST: ' + item.crust_name.toUpperCase());
            }
            let variationLine = variationParts.join(' | ');

            let addonsHtml = '';
            if (item.addons && item.addons.length > 0) {
                addonsHtml = item.addons.map(a => `<div style="font-size: ${is80 ? '12px' : '10px'}; font-weight: 800; color: #000; padding-left: 8px;">+ ${a.add_on_name.toUpperCase()}</div>`).join('');
            }

            itemsHtml += `
                <div style="margin-bottom: 8px; padding-bottom: 6px; border-bottom: 1.5px dashed #000;">
                    <div style="font-size: ${itemFont}; font-weight: 900; line-height: 1.2; word-break: break-word;">
                        ${item.quantity}x ${item.product_name}
                    </div>
                    ${variationLine ? `<div style="font-size: ${is80 ? '11px' : '9.5px'}; font-weight: bold; margin-top: 2px; padding-left: 8px; color:#111;">${variationLine}</div>` : ''}
                    ${addonsHtml}
                    ${item.special_instructions ? `<div style="font-size: ${is80 ? '11px' : '9px'}; font-weight: 900; background: #eee; padding: 3px; margin-top: 3px;">*** NOTE: ${item.special_instructions} ***</div>` : ''}
                </div>
            `;
        });

        const resolvedTable = tableNumber || (order.table ? order.table.table_number : activeTableName) || 'Table';

        return `
            <div class="thermal-receipt-wrap" style="width: ${wrapWidth}; max-width: ${wrapWidth}; margin: 0 auto; padding: ${is80 ? '2mm 2mm' : '1mm 1mm'}; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #000; font-size: ${fontSize}; line-height: 1.28; background: #fff; box-sizing: border-box;">
                <div style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 4px; margin-bottom: 5px;">
                    <h2 style="margin: 0; font-size: ${is80 ? '16px' : '14px'}; font-weight: 900; letter-spacing: 0.5px; color: #000;">** KITCHEN KOT **</h2>
                    <div style="font-size: 8.5px; margin-top: 1px; text-transform: uppercase; font-weight: 700; color: #000;">Chef Order Token</div>
                </div>

                <div style="border: 2px solid #000; text-align: center; padding: 3px; margin-bottom: 5px; border-radius: 4px; color: #000;">
                    <span style="font-size: ${is80 ? '12px' : '10.5px'}; font-weight: 800;">TABLE:</span>
                    <span style="font-size: ${is80 ? '17px' : '14px'}; font-weight: 900; margin-left: 4px;">${resolvedTable}</span>
                </div>

                <div style="display: flex; justify-content: space-between; font-size: ${is80 ? '10.5px' : '9px'}; margin-bottom: 3px; color: #000;">
                    <span>Token: <strong style="color: #000; white-space: nowrap;">#${order.order_number || 'POS'}</strong></span>
                    <span style="white-space: nowrap;">${orderDate}</span>
                </div>

                <div style="border-top: 1.5px solid #000; margin: 4px 0;"></div>

                <div style="margin-bottom: 4px;">
                    ${itemsHtml}
                </div>

                <div style="text-align: center; border-top: 1.5px solid #000; padding-top: 4px; font-size: ${is80 ? '10px' : '8.5px'}; font-weight: 900; color: #000;">
                    *** PREPARE FRESH & HOT ***
                </div>
            </div>
        `;
    }

    function printPosKitchenInvoice() {
        const orderToPrint = currentTableOrder;
        const tableNameToPrint = activeTableName;

        if (!orderToPrint || !orderToPrint.items || orderToPrint.items.length === 0) {
            alert('Please select a table with active order items to print kitchen token.');
            return;
        }

        const kitchenHtml = generateKitchenReceiptHtml(orderToPrint, tableNameToPrint, currentReceiptSize);

        const directContainer = document.getElementById('posDirectPrintContainer');
        if (directContainer) directContainer.innerHTML = kitchenHtml;

        executePrint();
    }

    // Generate Restaurant Thermal Invoice HTML (58mm default / 80mm adaptive, safe widths)
    function generateReceiptHtml(order, tableNumber, size = currentReceiptSize) {
        const restName = window.posSettings?.name || 'ITALIAN PIZZA HUT';
        const restAddress = window.posSettings?.address || 'House 14, Road 11, Block D, Gulshan-2, Dhaka';
        const restPhone = window.posSettings?.phone || '+880 1711-223344';

        const is80 = (size === '80mm');
        const wrapWidth = is80 ? '72mm' : '48mm';
        const fontSize = is80 ? '11px' : '9.5px';

        let orderDate = '';
        if (order.created_at) {
            const d = new Date(order.created_at);
            orderDate = d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) + ', ' +
                        d.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
        } else {
            const d = new Date();
            orderDate = d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) + ', ' +
                        d.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
        }

        let itemsHtml = '';
        (order.items || []).forEach(item => {
            // Variation: Size & Crust
            let variationParts = [];
            if (item.size_name && item.size_name.toLowerCase() !== 'standard') {
                variationParts.push('Size: ' + item.size_name);
            }
            if (item.crust_name && item.crust_name.toLowerCase() !== 'standard') {
                variationParts.push('Crust: ' + item.crust_name);
            }
            let variationLine = variationParts.join(' | ');

            // Add-ons list
            let addonsHtml = '';
            if (item.addons && item.addons.length > 0) {
                addonsHtml = item.addons.map(a => `<div style="font-size: ${is80 ? '9.5px' : '8.5px'}; font-weight: 700; color: #000; margin-top: 1px; padding-left: 6px;">+ ${a.add_on_name} (৳${Math.round(a.price)})</div>`).join('');
            }

            itemsHtml += `
                <div style="margin-bottom: 4px; padding-bottom: 3px; border-bottom: 1px dotted #000;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; font-weight: 800; font-size: ${is80 ? '11px' : '10px'}; color: #000;">
                        <span style="flex: 1; padding-right: 4px; word-break: break-word;">${item.quantity}x ${item.product_name}</span>
                        <span style="white-space: nowrap; margin-left: auto;">৳${Math.round(item.total_price).toLocaleString()}</span>
                    </div>
                    ${variationLine ? `<div style="font-size: ${is80 ? '9.5px' : '8.5px'}; font-weight: 700; color: #000; margin-top: 1px; padding-left: 6px;">${variationLine}</div>` : ''}
                    ${addonsHtml}
                    ${item.special_instructions ? `<div style="font-size: ${is80 ? '9px' : '8px'}; font-weight: 700; color: #000; padding-left: 6px;">Note: ${item.special_instructions}</div>` : ''}
                </div>
            `;
        });

        const subtotal = Math.round(order.subtotal || 0).toLocaleString();
        const vat = Math.round(order.vat_tax || 0).toLocaleString();
        const total = Math.round(order.total_amount || 0).toLocaleString();
        const isPaid = (order.payment_status === 'paid');
        const resolvedTable = tableNumber || (order.table ? order.table.table_number : activeTableName) || 'Table';

        return `
            <div class="thermal-receipt-wrap" style="width: ${wrapWidth}; max-width: ${wrapWidth}; margin: 0 auto; padding: ${is80 ? '2mm 2mm' : '1mm 1mm'}; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #000; font-size: ${fontSize}; line-height: 1.28; background: #fff; box-sizing: border-box;">
                
                <!-- RESTAURANT BRANDING -->
                <div style="text-align: center; margin-bottom: 5px;">
                    <h2 style="margin: 0; font-size: ${is80 ? '15px' : '13.5px'}; font-weight: 900; letter-spacing: 0.5px; text-transform: uppercase; color: #000;">${restName}</h2>
                    <div style="font-size: ${is80 ? '9px' : '8px'}; margin-top: 1px; text-transform: uppercase; font-weight: 700; color: #000;">Authentic Italian Oven & Dine</div>
                    <div style="font-size: ${is80 ? '9px' : '8px'}; margin-top: 1px; color: #000; line-height: 1.2;">${restAddress}</div>
                    <div style="font-size: ${is80 ? '9px' : '8px'}; font-weight: 700; color: #000;">Hotline: ${restPhone}</div>
                </div>

                <div style="border-top: 1.5px dashed #000; margin: 4px 0;"></div>

                <!-- TABLE NUMBER & INVOICE DETAILS -->
                <div style="margin: 3px 0; font-size: ${fontSize}; color: #000;">
                    <div style="border: 1.5px solid #000; padding: 3px; text-align: center; margin-bottom: 4px; border-radius: 4px; color: #000;">
                        <span style="font-weight: 900; font-size: ${is80 ? '13px' : '12px'}; letter-spacing: 0.5px;">TABLE: ${resolvedTable}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1px;">
                        <span>Invoice No:</span>
                        <strong style="color: #000; white-space: nowrap;">#${order.order_number || 'POS-INVOICE'}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1px;">
                        <span>Date & Time:</span>
                        <span style="color: #000; white-space: nowrap;">${orderDate}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Order Type:</span>
                        <span style="color: #000;">Dine-In (Table Bill)</span>
                    </div>
                    ${(order.customer_name && order.customer_name !== 'Walk-in' && !order.customer_name.startsWith('Dine-in (')) ? `
                    <div style="display: flex; justify-content: space-between; margin-top: 1px;">
                        <span>Customer:</span>
                        <strong style="color: #000; text-align: right; word-break: break-word;">${order.customer_name}</strong>
                    </div>` : ''}
                    ${(order.customer_phone && order.customer_phone !== 'Walk-in') ? `
                    <div style="display: flex; justify-content: space-between; margin-top: 1px;">
                        <span>Phone:</span>
                        <span style="color: #000; white-space: nowrap;">${order.customer_phone}</span>
                    </div>` : ''}
                </div>

                <div style="border-top: 1px dashed #000; margin: 4px 0;"></div>

                <!-- TABLE ITEMS HEADER -->
                <div style="display: flex; justify-content: space-between; font-weight: 900; font-size: ${is80 ? '10px' : '9px'}; margin-bottom: 2px; text-transform: uppercase; color: #000;">
                    <span>Qty & Item</span>
                    <span>Total</span>
                </div>

                <div style="border-top: 1px solid #000; margin-bottom: 4px;"></div>

                <!-- ITEMS LIST -->
                <div style="margin-bottom: 4px;">
                    ${itemsHtml}
                </div>

                <div style="border-top: 1px dashed #000; margin: 4px 0;"></div>

                <!-- BILL TOTALS -->
                <div style="font-size: ${fontSize}; color: #000;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1px;">
                        <span>Subtotal:</span>
                        <strong style="color: #000; white-space: nowrap;">৳ ${subtotal}</strong>
                    </div>
                    ${(order.discount > 0) ? `
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1px;">
                        <span>Discount:</span>
                        <strong style="color: #000; white-space: nowrap;">-৳ ${Math.round(order.discount).toLocaleString()}</strong>
                    </div>` : ''}
                    ${(order.vat_tax > 0) ? `
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1px;">
                        <span>VAT / Tax:</span>
                        <span style="white-space: nowrap;">৳ ${vat}</span>
                    </div>` : ''}

                    <div style="border-top: 1.5px solid #000; border-bottom: 1.5px solid #000; padding: 3px 0; margin: 4px 0; display: flex; justify-content: space-between; align-items: center; color: #000;">
                        <span style="font-weight: 900; font-size: ${is80 ? '12px' : '11px'};">TOTAL:</span>
                        <span style="font-weight: 900; font-size: ${is80 ? '14px' : '13px'}; white-space: nowrap;">৳ ${total}</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; margin-top: 1px;">
                        <span>Method:</span>
                        <span style="text-transform: uppercase; font-weight: 700; color: #000; white-space: nowrap;">${order.payment_method || 'Cash'}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 1px;">
                        <span>Paid Amount:</span>
                        <strong style="color: #000; white-space: nowrap;">৳ ${Math.round(order.paid_amount ?? (isPaid ? (order.total_amount || 0) : 0)).toLocaleString()}</strong>
                    </div>
                    ${(order.due_amount > 0) ? `
                    <div style="display: flex; justify-content: space-between; margin-top: 2px; border: 1.5px solid #000; padding: 2px 3px; border-radius: 3px; color: #000;">
                        <span style="font-weight: 900;">DUE BALANCE:</span>
                        <strong style="font-weight: 900; white-space: nowrap;">৳ ${Math.round(order.due_amount).toLocaleString()}</strong>
                    </div>` : ''}
                    <div style="display: flex; justify-content: space-between; margin-top: 1px;">
                        <span>Payment Status:</span>
                        <strong style="text-transform: uppercase; color: #000; white-space: nowrap;">${isPaid ? 'PAID' : (order.due_amount > 0 && order.paid_amount > 0 ? 'PARTIAL' : 'DUE')}</strong>
                    </div>
                </div>

                <div style="border-top: 1.5px dashed #000; margin: 6px 0 3px 0;"></div>

                <!-- FOOTER -->
                <div style="text-align: center; font-size: ${is80 ? '9.5px' : '8.5px'}; color: #000; margin-top: 3px; line-height: 1.25;">
                    <div style="font-weight: 800;">Thank you for dining with Italian Pizza Hut!</div>
                    <div>Please visit again!</div>
                </div>

            </div>
        `;
    }

    // Print Receipt Function
    function printPosInvoice(customOrder = null, customTableName = null) {
        const orderToPrint = customOrder || currentTableOrder;
        const tableNameToPrint = customTableName || activeTableName;

        if (!orderToPrint || !orderToPrint.items || orderToPrint.items.length === 0) {
            alert('Please select a table with active order items to print invoice.');
            return;
        }

        const receiptHtml = generateReceiptHtml(orderToPrint, tableNameToPrint, currentReceiptSize);

        // Update modal and fallback print container
        const modalContainer = document.getElementById('thermalPaperReceipt');
        if (modalContainer) modalContainer.innerHTML = receiptHtml;

        const directContainer = document.getElementById('posDirectPrintContainer');
        if (directContainer) directContainer.innerHTML = receiptHtml;

        // Open preview modal
        const modal = document.getElementById('posReceiptModal');
        if (modal) modal.classList.add('open');

        // Immediately trigger print via isolated iframe
        executePrint();
    }

    // Execute iframe printing (isolated, thermal 58mm/80mm adaptive, zero margin, no cutting)
    function executePrint(customSize = null) {
        const sizeToUse = customSize || currentReceiptSize || '58mm';
        const is80 = (sizeToUse === '80mm');
        const printWidth = is80 ? '72mm' : '48mm';
        const pageSize = is80 ? '80mm' : '58mm';

        const directContainer = document.getElementById('posDirectPrintContainer');
        if (!directContainer || !directContainer.innerHTML) return;

        const printFrame = document.getElementById('posPrintIframe');
        if (!printFrame) return;

        try {
            const frameDoc = printFrame.contentWindow.document;
            frameDoc.open();
            frameDoc.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Restaurant Invoice - Italian Pizza Hut</title>
                    <style>
                        @page {
                            size: ${pageSize} auto;
                            margin: 0mm !important;
                        }
                        * {
                            box-sizing: border-box !important;
                        }
                        html, body {
                            margin: 0 !important;
                            padding: 0 !important;
                            background: #ffffff !important;
                            color: #000000 !important;
                            width: 100% !important;
                            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif !important;
                            -webkit-print-color-adjust: exact !important;
                            print-color-adjust: exact !important;
                        }
                        .thermal-receipt-wrap {
                            width: ${printWidth} !important;
                            max-width: ${printWidth} !important;
                            margin: 0 auto !important;
                            padding: ${is80 ? '2mm 2mm' : '1mm 1mm'} !important;
                            color: #000000 !important;
                            box-sizing: border-box !important;
                        }
                    </style>
                </head>
                <body>
                    ${directContainer.innerHTML}
                </body>
                </html>
            `);
            frameDoc.close();

            printFrame.contentWindow.focus();
            setTimeout(() => {
                printFrame.contentWindow.print();
            }, 300);
        } catch (e) {
            // Fallback to window.print() if iframe blocked
            window.print();
        }
    }

    function closePosReceiptModal() {
        const modal = document.getElementById('posReceiptModal');
        if (modal) modal.classList.remove('open');
        if (reloadAfterPrint) {
            reloadAfterPrint = false;
            location.reload();
        }
    }

    // Auto select table if query string provided
    document.addEventListener('DOMContentLoaded', () => {
        updateReceiptSizeUI();
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
