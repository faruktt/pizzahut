@extends('layouts.admin')

@section('title', 'Create Walk-in / Phone Order | Italian Pizza Hut')
@section('page-title', 'Create Manual Order (Walk-in / Phone / Dine-in)')

@push('admin-styles')
<style>
    .order-create-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid var(--border-light);
        box-shadow: 0 4px 16px rgba(0,0,0,0.03);
        margin-bottom: 30px;
    }

    .order-item-card {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 12px;
        transition: border-color 0.15s ease;
    }

    .order-item-card:hover {
        border-color: #cbd5e1;
    }

    .item-addons-tray {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px dashed #cbd5e1;
        display: none;
    }

    .item-addons-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 8px;
        max-height: 150px;
        overflow-y: auto;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 8px 10px;
    }

    .addon-chk-label {
        font-size: 0.78rem;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 0;
        padding: 4px;
        border-radius: 4px;
        transition: background 0.1s;
    }

    .addon-chk-label:hover {
        background: #f1f5f9;
    }

    .btn-addons-toggle {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 5px 10px;
        font-size: 0.78rem;
        font-weight: 700;
        color: #334155;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.15s;
    }

    .btn-addons-toggle:hover {
        background: #e2e8f0;
    }

    .btn-addons-toggle.has-addons {
        background: #e0f2fe;
        border-color: #7dd3fc;
        color: #0369a1;
    }

    .summary-box {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 16px 20px;
    }

    .summary-line {
        display: flex;
        justify-content: space-between;
        font-size: 0.88rem;
        margin-bottom: 8px;
        color: #475569;
    }

    .summary-line strong {
        color: #0f172a;
    }

    .summary-total {
        display: flex;
        justify-content: space-between;
        font-size: 1.25rem;
        font-weight: 900;
        color: var(--admin-primary);
        border-top: 2px dashed #cbd5e1;
        padding-top: 10px;
        margin-top: 10px;
    }
</style>
@endpush

@section('admin-content')
<div style="max-width: 980px; margin: 0 auto;">
    <div class="order-create-card">
        <div class="card-panel-header" style="padding: 16px 24px; border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0; display:flex; align-items:center; gap:8px;">
                    <i class="fa-solid fa-circle-plus" style="color:var(--admin-primary);"></i> New Order Entry
                </h3>
                <div style="font-size: 0.8rem; color: #64748b; margin-top: 2px;">
                    Create Walk-in, Takeaway, Phone, or Dine-in orders without customer account
                </div>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="btn-view-store">
                <i class="fa-solid fa-arrow-left"></i> Back to Orders
            </a>
        </div>

        <form action="{{ route('admin.orders.store') }}" method="POST" id="manualOrderForm" style="padding: 24px;">
            @csrf

            <!-- 1. Order Type & Dining Table / Delivery Address -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 20px;">
                <div>
                    <label style="display:block; font-size:0.8rem; font-weight:700; color:#334155; margin-bottom:6px;">Order Type *</label>
                    <select name="order_type" id="orderTypeSelect" class="form-control" style="width:100%; padding:9px; font-weight:600;" onchange="handleOrderTypeChange(this.value)" required>
                        <option value="takeaway" selected>Takeaway / Counter Walk-in</option>
                        <option value="dine_in">Dine-in Table</option>
                        <option value="delivery">Home Delivery</option>
                    </select>
                </div>

                <div id="tableSelectWrapper" style="display:none;">
                    <label style="display:block; font-size:0.8rem; font-weight:700; color:#334155; margin-bottom:6px;">Select Restaurant Table *</label>
                    <select name="restaurant_table_id" id="tableSelect" class="form-control" style="width:100%; padding:9px; font-weight:600;">
                        <option value="">-- Choose Table --</option>
                        @foreach($tables as $tbl)
                            <option value="{{ $tbl->id }}">{{ $tbl->table_number }} (Capacity: {{ $tbl->capacity }} Persons)</option>
                        @endforeach
                    </select>
                </div>

                <div id="addressWrapper" style="display:none; grid-column: 1 / -1;">
                    <label style="display:block; font-size:0.8rem; font-weight:700; color:#334155; margin-bottom:6px;">Customer Delivery Address</label>
                    <textarea name="customer_address" id="customerAddressInput" class="form-control" rows="2" placeholder="House #, Road #, Area, City" style="width:100%; padding:8px;"></textarea>
                </div>
            </div>

            <!-- 2. Customer Optional Details -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 24px;">
                <div>
                    <label style="display:block; font-size:0.8rem; font-weight:700; color:#334155; margin-bottom:6px;">Customer Name</label>
                    <input type="text" name="customer_name" class="form-control" placeholder="Walk-in Customer" style="width:100%; padding:9px;">
                </div>
                <div>
                    <label style="display:block; font-size:0.8rem; font-weight:700; color:#334155; margin-bottom:6px;">Customer Phone (মোবাইল নম্বর)</label>
                    <input type="text" name="customer_phone" id="manualCustomerPhone" class="form-control" placeholder="e.g. 017xxxxxxxx" style="width:100%; padding:9px;">
                </div>
            </div>

            <!-- 3. Dynamic Items Selection with accurate Size, Crust & Add-ons -->
            <div style="margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <div>
                        <label style="font-size:0.95rem; font-weight:800; color:#0f172a; margin:0;">
                            Ordered Menu Items *
                        </label>
                        <div style="font-size: 0.75rem; color: #64748b;">
                            Sizes & Crusts appear automatically ONLY for products that have them. Add-ons can be customized per item.
                        </div>
                    </div>
                    <button type="button" onclick="addNewItemRow()" class="btn-view-store" style="font-size:0.82rem; padding:6px 14px; font-weight:700;">
                        <i class="fa-solid fa-plus"></i> Add Another Item
                    </button>
                </div>

                <div id="orderItemsContainer">
                    <!-- Item rows will be rendered here dynamically -->
                </div>
            </div>

            <!-- 4. Financial Summary & Payment Options -->
            <div style="display: grid; grid-template-columns: 1.1fr 1fr; gap: 20px; border-top: 1px solid var(--border-light); padding-top: 20px; align-items: start;">
                <div>
                    <!-- Special Instructions -->
                    <div style="margin-bottom: 16px;">
                        <label style="display:block; font-size:0.8rem; font-weight:700; color:#334155; margin-bottom:6px;">Special Instructions / Kitchen Note</label>
                        <textarea name="special_instructions" class="form-control" rows="2" placeholder="e.g. Less spicy, extra crispy crust, cutlery needed" style="width:100%; padding:8px;"></textarea>
                    </div>

                    <!-- Payment Method & Status -->
                    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:14px;">
                        <div style="font-size:0.82rem; font-weight:800; color:#0f172a; margin-bottom:10px;">Payment Details</div>

                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
                            <div>
                                <label style="display:block; font-size:0.75rem; font-weight:700; color:#475569; margin-bottom:4px;">Payment Method</label>
                                <select name="payment_method" class="form-control" style="width:100%; padding:7px; font-weight:600;">
                                    <option value="cash" selected>Cash</option>
                                    <option value="bank">Bank</option>
                                    <option value="card">Card</option>
                                    <option value="bkash">bKash</option>
                                    <option value="nagad">Nagad</option>
                                    <option value="rocket">Rocket</option>
                                </select>
                            </div>
                            <div>
                                <label style="display:block; font-size:0.75rem; font-weight:700; color:#475569; margin-bottom:4px;">Payment Option</label>
                                <select name="payment_status" id="paymentStatusSelect" class="form-control" style="width:100%; padding:7px; font-weight:700;" onchange="handlePaymentStatusChange(this.value)">
                                    <option value="paid" selected>Full Paid</option>
                                    <option value="partial">Partial Payment</option>
                                    <option value="due">Full Due (বাকি)</option>
                                    <option value="pending">Pending (Unpaid)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Partial / Due amounts -->
                        <div id="partialPaidRow" style="display:none; grid-template-columns:1fr 1fr; gap:12px; margin-top:8px; background:#fff; padding:8px; border-radius:6px; border:1px solid #cbd5e1;">
                            <div>
                                <label style="display:block; font-size:0.72rem; font-weight:700; color:#16a34a; margin-bottom:2px;">Paid Amount (৳)</label>
                                <input type="number" step="1" min="0" name="paid_amount" id="paidAmountInput" value="0" class="form-control" style="font-weight:800; color:#16a34a; padding:6px;" oninput="recalcDueDisplay()">
                            </div>
                            <div>
                                <label style="display:block; font-size:0.72rem; font-weight:700; color:#dc2626; margin-bottom:2px;">Remaining Due (৳)</label>
                                <input type="number" id="dueAmountDisplay" readonly class="form-control" style="font-weight:800; color:#dc2626; background:#fef2f2; padding:6px;" value="0">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Live Bill Summary -->
                <div class="summary-box">
                    <div style="font-size:0.85rem; font-weight:800; color:#0f172a; margin-bottom:12px; text-transform:uppercase;">
                        Bill Summary
                    </div>
                    <div class="summary-line">
                        <span>Items Subtotal:</span>
                        <strong id="displaySubtotal">৳ 0</strong>
                    </div>
                    <div class="summary-line">
                        <span>Delivery Charge:</span>
                        <strong id="displayDeliveryFee">৳ 0</strong>
                    </div>
                    <!-- VAT is removed completely as requested (0) -->
                    <div class="summary-total">
                        <span>Grand Total:</span>
                        <span id="displayTotal">৳ 0</span>
                    </div>

                    <button type="submit" class="btn-quick-pos" style="width:100%; margin-top:16px; padding:12px; font-size:1rem; font-weight:800; justify-content:center;">
                        <i class="fa-solid fa-circle-check"></i> Place Order Now
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection

@push('admin-scripts')
<script>
    // Product catalog & Addons passed from server
    const allProducts = @json($products->keyBy('id'));
    const allAddons = @json($addons);
    const baseDeliveryFee = {{ \App\Models\Setting::getDeliveryCharge() }};
    const freeDeliveryMin = {{ (float) \App\Models\Setting::getValue('free_delivery_min', 0) }};

    let nextRowIndex = 0;

    // Toggle Order Type: show Table dropdown for dine_in, address for delivery
    function handleOrderTypeChange(type) {
        const tblWrap = document.getElementById('tableSelectWrapper');
        const addrWrap = document.getElementById('addressWrapper');
        const tblSelect = document.getElementById('tableSelect');

        if (type === 'dine_in') {
            tblWrap.style.display = 'block';
            tblSelect.setAttribute('required', 'required');
            addrWrap.style.display = 'none';
        } else if (type === 'delivery') {
            tblWrap.style.display = 'none';
            tblSelect.removeAttribute('required');
            addrWrap.style.display = 'block';
        } else {
            tblWrap.style.display = 'none';
            tblSelect.removeAttribute('required');
            addrWrap.style.display = 'none';
        }

        recalculateOrderSummary();
    }

    // Toggle Partial / Due row
    function handlePaymentStatusChange(status) {
        const row = document.getElementById('partialPaidRow');
        const paidInput = document.getElementById('paidAmountInput');
        const total = getCalculatedTotal();

        if (status === 'partial') {
            row.style.display = 'grid';
            if (parseFloat(paidInput.value) <= 0 || parseFloat(paidInput.value) >= total) {
                paidInput.value = Math.round(total / 2);
            }
            recalcDueDisplay();
            paidInput.focus();
        } else if (status === 'due') {
            row.style.display = 'grid';
            paidInput.value = 0;
            recalcDueDisplay();
        } else {
            row.style.display = 'none';
            paidInput.value = total;
            recalcDueDisplay();
        }
    }

    function recalcDueDisplay() {
        const total = getCalculatedTotal();
        const paid = Math.max(0, parseFloat(document.getElementById('paidAmountInput').value) || 0);
        const due = Math.max(0, total - paid);
        document.getElementById('dueAmountDisplay').value = due;
    }

    // Add a new item row
    function addNewItemRow() {
        const idx = nextRowIndex++;
        const container = document.getElementById('orderItemsContainer');

        const card = document.createElement('div');
        card.className = 'order-item-card';
        card.id = `itemCard-${idx}`;

        card.innerHTML = `
            <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 10px; align-items: start;">
                <!-- Product Selector -->
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:700; color:#475569; margin-bottom:4px;">Product *</label>
                    <select name="items[${idx}][product_id]" class="form-control" style="width:100%; padding:8px; font-weight:600;" onchange="onProductSelectChange(this, ${idx})" required>
                        <option value="">-- Choose Product --</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} (৳{{ number_format($p->base_price) }})</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="items[${idx}][product_name]" id="prodName-${idx}">
                    
                    <div style="margin-top:6px;">
                        <button type="button" id="addonBtn-${idx}" class="btn-addons-toggle" onclick="toggleAddonsTray(${idx})" style="display:none;">
                            <i class="fa-solid fa-plus"></i> Add-ons <span id="addonCountBadge-${idx}" style="font-weight:800;"></span>
                        </button>
                    </div>
                </div>

                <!-- Size (Appears ONLY if product has sizes) -->
                <div id="sizeCol-${idx}">
                    <label style="display:block; font-size:0.75rem; font-weight:700; color:#475569; margin-bottom:4px;">Size</label>
                    <div id="sizeContent-${idx}">
                        <span style="font-size:0.75rem; color:#94a3b8; display:block; padding:8px 0;">--</span>
                    </div>
                </div>

                <!-- Crust (Appears ONLY if product has crusts) -->
                <div id="crustCol-${idx}">
                    <label style="display:block; font-size:0.75rem; font-weight:700; color:#475569; margin-bottom:4px;">Crust</label>
                    <div id="crustContent-${idx}">
                        <span style="font-size:0.75rem; color:#94a3b8; display:block; padding:8px 0;">--</span>
                    </div>
                </div>

                <!-- Quantity & Price Output -->
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:700; color:#475569; margin-bottom:4px;">Quantity</label>
                    <input type="number" name="items[${idx}][quantity]" id="itemQty-${idx}" value="1" min="1" class="form-control" style="width:100%; padding:8px; font-weight:800; text-align:center;" oninput="onItemQtyChange(${idx})" required>
                    <input type="hidden" name="items[${idx}][unit_price]" id="unitPriceInput-${idx}" value="0">
                    <div style="font-size:0.75rem; color:#64748b; margin-top:4px; text-align:center;">
                        Unit: <strong id="unitPriceDisplay-${idx}" style="color:#0f172a;">৳ 0</strong>
                    </div>
                </div>

                <!-- Row Total & Remove Button -->
                <div style="text-align: right; padding-top: 24px;">
                    <div style="font-size:0.95rem; font-weight:900; color:var(--admin-primary); margin-bottom:6px;" id="rowTotalDisplay-${idx}">
                        ৳ 0
                    </div>
                    <button type="button" onclick="removeItemCard(${idx})" style="background:none; border:none; color:#ef4444; font-size:1rem; cursor:pointer;" title="Remove this item">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            </div>

            <!-- Add-ons Selection Tray (Collapsible) -->
            <div class="item-addons-tray" id="addonsTray-${idx}">
                <div style="font-size:0.74rem; font-weight:700; color:#475569; margin-bottom:6px; display:flex; justify-content:space-between; align-items:center;">
                    <span>Select Add-ons for this item:</span>
                    <button type="button" onclick="toggleAddonsTray(${idx})" style="background:none; border:none; color:#0284c7; cursor:pointer; font-size:0.72rem; text-decoration:underline;">Close</button>
                </div>
                <div class="item-addons-grid" id="addonsGrid-${idx}">
                    <!-- Addons checkboxes injected here -->
                </div>
            </div>
        `;

        container.appendChild(card);
    }

    // When product selection changes
    function onProductSelectChange(selectEl, idx) {
        const prodId = selectEl.value;
        const prod = allProducts[prodId];

        const prodNameInput = document.getElementById(`prodName-${idx}`);
        const sizeCol = document.getElementById(`sizeCol-${idx}`);
        const sizeContent = document.getElementById(`sizeContent-${idx}`);
        const crustCol = document.getElementById(`crustCol-${idx}`);
        const crustContent = document.getElementById(`crustContent-${idx}`);
        const addonBtn = document.getElementById(`addonBtn-${idx}`);
        const addonsGrid = document.getElementById(`addonsGrid-${idx}`);

        if (!prod) {
            prodNameInput.value = '';
            sizeContent.innerHTML = '<span style="font-size:0.75rem; color:#94a3b8; display:block; padding:8px 0;">--</span>';
            crustContent.innerHTML = '<span style="font-size:0.75rem; color:#94a3b8; display:block; padding:8px 0;">--</span>';
            addonBtn.style.display = 'none';
            recalcItemRowPrice(idx);
            return;
        }

        prodNameInput.value = prod.name;

        // 1. Check if product has sizes: ONLY show sizes if present
        if (prod.sizes && prod.sizes.length > 0) {
            let sizeHtml = `<select name="items[${idx}][size_name]" id="sizeSelect-${idx}" class="form-control" style="width:100%; padding:8px; font-size:0.82rem;" onchange="recalcItemRowPrice(${idx})">`;
            prod.sizes.forEach((s, sIdx) => {
                sizeHtml += `<option value="${s.name}" data-price="${s.price}" ${sIdx === 0 ? 'selected' : ''}>${s.name} - ৳${Math.round(s.price)}</option>`;
            });
            sizeHtml += `</select>`;
            sizeContent.innerHTML = sizeHtml;
            sizeCol.style.opacity = '1';
        } else {
            // NO sizes for this product (e.g. drinks, pasta, sides)
            sizeContent.innerHTML = `<span style="font-size:0.75rem; color:#64748b; background:#f1f5f9; padding:6px 8px; border-radius:4px; display:inline-block;">No Size Option</span>
            <input type="hidden" name="items[${idx}][size_name]" value="">`;
        }

        // 2. Check if product has crusts: ONLY show crusts if present
        if (prod.crusts && prod.crusts.length > 0) {
            let crustHtml = `<select name="items[${idx}][crust_name]" id="crustSelect-${idx}" class="form-control" style="width:100%; padding:8px; font-size:0.82rem;" onchange="recalcItemRowPrice(${idx})">`;
            prod.crusts.forEach((c, cIdx) => {
                const extraTxt = c.extra_price > 0 ? ` (+৳${Math.round(c.extra_price)})` : '';
                crustHtml += `<option value="${c.name}" data-extra="${c.extra_price}" ${cIdx === 0 ? 'selected' : ''}>${c.name}${extraTxt}</option>`;
            });
            crustHtml += `</select>`;
            crustContent.innerHTML = crustHtml;
            crustCol.style.opacity = '1';
        } else {
            // NO crusts for this product
            crustContent.innerHTML = `<span style="font-size:0.75rem; color:#64748b; background:#f1f5f9; padding:6px 8px; border-radius:4px; display:inline-block;">No Crust Option</span>
            <input type="hidden" name="items[${idx}][crust_name]" value="">`;
        }

        // 3. Render Add-ons available for this item
        let addonsHtml = '';
        allAddons.forEach(ad => {
            addonsHtml += `
                <label class="addon-chk-label">
                    <span>
                        <input type="checkbox" name="items[${idx}][addons][]" value="${ad.id}" data-price="${ad.price}" onchange="onAddonToggle(${idx})" class="item-addon-chk-${idx}">
                        ${ad.name}
                    </span>
                    <strong style="color:#0f172a;">+৳${Math.round(ad.price)}</strong>
                </label>
            `;
        });
        addonsGrid.innerHTML = addonsHtml;
        addonBtn.style.display = 'inline-flex';
        updateAddonBadge(idx);

        recalcItemRowPrice(idx);
    }

    // Toggle Addons Tray
    function toggleAddonsTray(idx) {
        const tray = document.getElementById(`addonsTray-${idx}`);
        if (tray) {
            tray.style.display = (tray.style.display === 'block') ? 'none' : 'block';
        }
    }

    function onAddonToggle(idx) {
        updateAddonBadge(idx);
        recalcItemRowPrice(idx);
    }

    function updateAddonBadge(idx) {
        const checked = document.querySelectorAll(`.item-addon-chk-${idx}:checked`);
        const badge = document.getElementById(`addonCountBadge-${idx}`);
        const btn = document.getElementById(`addonBtn-${idx}`);

        if (checked.length > 0) {
            badge.innerText = `(${checked.length})`;
            btn.classList.add('has-addons');
        } else {
            badge.innerText = '';
            btn.classList.remove('has-addons');
        }
    }

    function onItemQtyChange(idx) {
        recalcItemRowPrice(idx);
    }

    // Recalculate row price
    function recalcItemRowPrice(idx) {
        const selectEl = document.querySelector(`select[name="items[${idx}][product_id]"]`);
        const prodId = selectEl ? selectEl.value : null;
        const prod = allProducts[prodId];

        if (!prod) {
            document.getElementById(`unitPriceInput-${idx}`).value = 0;
            document.getElementById(`unitPriceDisplay-${idx}`).innerText = '৳ 0';
            document.getElementById(`rowTotalDisplay-${idx}`).innerText = '৳ 0';
            recalculateOrderSummary();
            return;
        }

        let baseOrSizePrice = parseFloat(prod.base_price) || 0;
        const sizeSelect = document.getElementById(`sizeSelect-${idx}`);
        if (sizeSelect && sizeSelect.options.length > 0) {
            baseOrSizePrice = parseFloat(sizeSelect.options[sizeSelect.selectedIndex]?.dataset.price || baseOrSizePrice);
        }

        let crustExtra = 0;
        const crustSelect = document.getElementById(`crustSelect-${idx}`);
        if (crustSelect && crustSelect.options.length > 0) {
            crustExtra = parseFloat(crustSelect.options[crustSelect.selectedIndex]?.dataset.extra || 0);
        }

        let addonsExtra = 0;
        document.querySelectorAll(`.item-addon-chk-${idx}:checked`).forEach(chk => {
            addonsExtra += parseFloat(chk.dataset.price || 0);
        });

        const singleUnitPrice = baseOrSizePrice + crustExtra + addonsExtra;
        const qty = Math.max(1, parseInt(document.getElementById(`itemQty-${idx}`)?.value) || 1);
        const rowTotal = singleUnitPrice * qty;

        document.getElementById(`unitPriceInput-${idx}`).value = singleUnitPrice;
        document.getElementById(`unitPriceDisplay-${idx}`).innerText = '৳ ' + Math.round(singleUnitPrice).toLocaleString();
        document.getElementById(`rowTotalDisplay-${idx}`).innerText = '৳ ' + Math.round(rowTotal).toLocaleString();

        recalculateOrderSummary();
    }

    function removeItemCard(idx) {
        const card = document.getElementById(`itemCard-${idx}`);
        if (card) {
            const allCards = document.querySelectorAll('.order-item-card');
            if (allCards.length <= 1) {
                alert('Order must contain at least one item.');
                return;
            }
            card.remove();
            recalculateOrderSummary();
        }
    }

    function getCalculatedTotal() {
        let subtotal = 0;
        document.querySelectorAll('input[id^="unitPriceInput-"]').forEach(inp => {
            const idx = inp.id.split('-')[1];
            const unit = parseFloat(inp.value) || 0;
            const q = parseInt(document.getElementById(`itemQty-${idx}`)?.value) || 0;
            subtotal += (unit * q);
        });

        const orderType = document.getElementById('orderTypeSelect')?.value || 'takeaway';
        let deliveryFee = 0;
        if (orderType === 'delivery') {
            deliveryFee = (freeDeliveryMin > 0 && subtotal >= freeDeliveryMin) ? 0 : baseDeliveryFee;
        }

        return subtotal + deliveryFee;
    }

    // Recompute total order bill
    function recalculateOrderSummary() {
        let subtotal = 0;
        document.querySelectorAll('input[id^="unitPriceInput-"]').forEach(inp => {
            const idx = inp.id.split('-')[1];
            const unit = parseFloat(inp.value) || 0;
            const q = parseInt(document.getElementById(`itemQty-${idx}`)?.value) || 0;
            subtotal += (unit * q);
        });

        const orderType = document.getElementById('orderTypeSelect')?.value || 'takeaway';
        let deliveryFee = 0;
        if (orderType === 'delivery') {
            deliveryFee = (freeDeliveryMin > 0 && subtotal >= freeDeliveryMin) ? 0 : baseDeliveryFee;
        }

        const grandTotal = subtotal + deliveryFee;

        document.getElementById('displaySubtotal').innerText = '৳ ' + Math.round(subtotal).toLocaleString();
        document.getElementById('displayDeliveryFee').innerText = '৳ ' + Math.round(deliveryFee).toLocaleString();
        document.getElementById('displayTotal').innerText = '৳ ' + Math.round(grandTotal).toLocaleString();

        const payStatus = document.getElementById('paymentStatusSelect')?.value;
        if (payStatus === 'partial' || payStatus === 'due') {
            recalcDueDisplay();
        }
    }

    // Initialize with first row
    document.addEventListener('DOMContentLoaded', function() {
        addNewItemRow();
    });
</script>
@endpush
