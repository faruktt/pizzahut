@extends('layouts.admin')

@section('title', 'Point of Sale (POS) & Table Management | Italian Pizza')
@section('page-title', 'Restaurant Point of Sale (POS)')

@push('admin-styles')
<style>
    .pos-container {
        display: grid;
        grid-template-columns: 280px 1fr 360px;
        gap: 20px;
        height: calc(100vh - 120px);
        min-height: 600px;
    }

    /* Column 1: Tables List */
    .pos-col-tables {
        background: #ffffff;
        border: 1px solid var(--border-light);
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .pos-col-header {
        padding: 16px;
        border-bottom: 1px solid var(--border-light);
        font-weight: 800;
        font-size: 0.95rem;
        color: var(--text-main);
        display: flex;
        align-items: center;
        justify-content: space-between;
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
        border: 2px solid var(--border-light);
        border-radius: 10px;
        padding: 12px 14px;
        background: #ffffff;
        cursor: pointer;
        text-align: left;
        transition: all 0.2s;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .pos-table-btn:hover {
        border-color: #94a3b8;
    }

    .pos-table-btn.available {
        border-left: 5px solid #10b981;
    }

    .pos-table-btn.occupied {
        border-left: 5px solid #ef4444;
        background: #fef2f2;
    }

    .pos-table-btn.selected {
        border-color: #0284c7;
        background: #f0f9ff;
        box-shadow: 0 0 0 2px rgba(2, 132, 199, 0.2);
    }

    /* Column 2: Menu Catalog */
    .pos-col-menu {
        background: #ffffff;
        border: 1px solid var(--border-light);
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .pos-category-tabs {
        display: flex;
        gap: 8px;
        padding: 12px 16px;
        border-bottom: 1px solid var(--border-light);
        overflow-x: auto;
        background: #f8fafc;
    }

    .cat-tab-btn {
        padding: 6px 14px;
        border-radius: 20px;
        border: 1px solid var(--border-light);
        background: #ffffff;
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--text-sub);
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s;
    }

    .cat-tab-btn.active {
        background: var(--admin-primary);
        color: #ffffff;
        border-color: var(--admin-primary);
    }

    .pos-products-grid {
        flex: 1;
        overflow-y: auto;
        padding: 16px;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 14px;
    }

    .pos-product-card {
        border: 1px solid var(--border-light);
        border-radius: 10px;
        padding: 12px;
        background: #ffffff;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.2s;
    }

    .pos-product-card:hover {
        border-color: var(--admin-primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }

    .pos-prod-thumb {
        height: 90px;
        border-radius: 6px;
        overflow: hidden;
        margin-bottom: 8px;
    }

    .pos-prod-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pos-prod-name {
        font-size: 0.85rem;
        font-weight: 800;
        color: var(--text-main);
        line-height: 1.25;
        margin-bottom: 6px;
    }

    .pos-prod-price {
        font-size: 0.9rem;
        font-weight: 800;
        color: var(--admin-primary);
    }

    /* Column 3: Table Order / Bill */
    .pos-col-order {
        background: #ffffff;
        border: 1px solid var(--border-light);
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
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
        border: 1px solid var(--border-light);
        border-radius: 8px;
        padding: 10px 12px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        font-size: 0.85rem;
    }

    .pos-order-footer {
        padding: 16px;
        border-top: 1px solid var(--border-light);
        background: #f8fafc;
    }

    .pos-bill-line {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
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
        background: #10b981;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 12px;
        font-size: 0.95rem;
        font-weight: 800;
        cursor: pointer;
        margin-top: 12px;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        transition: background 0.2s;
    }

    .btn-pos-pay:hover {
        background: #059669;
    }

    /* Modal for Quick Customizing Pizza onto Table */
    .pos-modal {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(3px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s;
    }

    .pos-modal.open {
        opacity: 1;
        pointer-events: auto;
    }

    .pos-modal-card {
        background: #ffffff;
        border-radius: 12px;
        width: 100%;
        max-width: 520px;
        padding: 24px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    @media (max-width: 1100px) {
        .pos-container {
            grid-template-columns: 220px 1fr 320px;
        }
    }
</style>
@endpush

@section('admin-content')
<div class="pos-container">

    <!-- 1. RESTAURANT TABLES SELECTOR -->
    <div class="pos-col-tables">
        <div class="pos-col-header">
            <span>🍽️ Dining Tables</span>
            <span style="font-size:0.75rem; color:#64748b;">{{ $tables->count() }} Total</span>
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
                        <span class="badge badge-{{ $tbl->status }}">{{ ucfirst($tbl->status) }}</span>
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
        <!-- Category Tabs -->
        <div class="pos-category-tabs">
            <button type="button" class="cat-tab-btn active" onclick="filterCategory('all', this)">All Items</button>
            @foreach($categories as $cat)
                <button type="button" class="cat-tab-btn" onclick="filterCategory('{{ $cat->id }}', this)">
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>

        <!-- Products Grid -->
        <div class="pos-products-grid" id="posProductsGrid">
            @foreach($products as $prod)
                <div class="pos-product-card" data-category="{{ $prod->category_id }}" onclick="openPosItemModal({{ $prod->id }})">
                    <div class="pos-prod-thumb">
                        <img src="{{ asset('images/pizza-hero.jpg') }}" alt="{{ $prod->name }}">
                    </div>
                    <div>
                        <div class="pos-prod-name">{{ $prod->name }}</div>
                        <div class="pos-prod-price">৳ {{ number_format($prod->base_price) }}</div>
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
            <button type="button" onclick="printReceipt()" style="background:none; border:none; cursor:pointer; font-size:1.1rem;" title="Print Receipt">🖨️</button>
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
                ✓ Complete Payment & Free Table
            </button>
        </div>
    </div>

</div>

<!-- MODAL: ADD ITEM WITH SIZES & CRUSTS TO TABLE -->
<div class="pos-modal" id="posAddModal">
    <div class="pos-modal-card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <h3 id="posModalProdName" style="font-size:1.1rem; font-weight:800;">Product Name</h3>
            <button type="button" onclick="closePosModal()" style="background:none; border:none; font-size:1.2rem; cursor:pointer;">✕</button>
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
                            {{ $ad->icon }} {{ $ad->name }}
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
                Add to Table Order ➔
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

    // Filter categories in POS
    function filterCategory(catId, btn) {
        document.querySelectorAll('.cat-tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        document.querySelectorAll('.pos-product-card').forEach(card => {
            if (catId === 'all' || card.dataset.category === catId) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
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
