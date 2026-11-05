@extends('layouts.admin')
@section('title', 'New Purchase | Italian Pizza Hut Admin')
@section('page-title', 'Create Purchase')

@section('admin-content')
<div style="max-width:1000px;">
    <a href="{{ route('admin.purchases.index') }}" class="btn btn-secondary" style="margin-bottom:20px;">
        <i class="fa-solid fa-arrow-left"></i> Back to Purchases
    </a>

    <form action="{{ route('admin.purchases.store') }}" method="POST" id="purchaseForm">
        @csrf

        <!-- Purchase Meta -->
        <div class="card-panel" style="margin-bottom:20px;">
            <div class="card-panel-header">
                <span class="card-panel-title"><i class="fa-solid fa-receipt"></i> Purchase Information</span>
            </div>
            <div class="card-panel-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Supplier</label>
                        <select name="supplier_id" class="form-control">
                            <option value="">-- Cash / Walk-in Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }} {{ $supplier->company_name ? '('.$supplier->company_name.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <span class="form-hint">Supplier not listed? <a href="{{ route('admin.suppliers.create') }}" target="_blank" style="color:var(--admin-primary);font-weight:700;">+ Add Supplier</a></span>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Purchase Date *</label>
                        <input type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date', date('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="form-row-3">
                    <div class="form-group">
                        <label class="form-label">Payment Status *</label>
                        <select name="payment_status" id="paymentStatus" class="form-control" required onchange="handlePaymentStatusChange()">
                            <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="partial" {{ old('payment_status') == 'partial' ? 'selected' : '' }}>Partial</option>
                            <option value="unpaid" {{ old('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Payment Method *</label>
                        <select name="payment_method" class="form-control" required>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank" {{ old('payment_method') == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="bkash" {{ old('payment_method') == 'bkash' ? 'selected' : '' }}>bKash</option>
                            <option value="nagad" {{ old('payment_method') == 'nagad' ? 'selected' : '' }}>Nagad</option>
                            <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                            <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="form-group" id="paidAmountGroup">
                        <label class="form-label">Paid Amount (৳)</label>
                        <input type="number" step="0.01" min="0" name="paid_amount" id="paidAmountInput" class="form-control" value="{{ old('paid_amount', 0) }}">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Note / Reference</label>
                    <textarea name="note" class="form-control" rows="2" placeholder="e.g. Memo no, invoice reference, etc.">{{ old('note') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Purchase Items Table -->
        <div class="card-panel" style="margin-bottom:20px;">
            <div class="card-panel-header" style="justify-content:space-between;">
                <span class="card-panel-title"><i class="fa-solid fa-list-check"></i> Purchase Items (Ingredients)</span>
                <button type="button" class="btn btn-secondary btn-sm" onclick="addItemRow()">
                    <i class="fa-solid fa-plus"></i> Add Another Item
                </button>
            </div>

            <div style="overflow-x:auto;">
                <table class="admin-table" id="itemsTable">
                    <thead>
                        <tr>
                            <th style="min-width:240px;">Ingredient *</th>
                            <th style="width:140px;">Quantity *</th>
                            <th style="width:140px;">Unit Price (৳) *</th>
                            <th style="width:140px;">Subtotal (৳)</th>
                            <th style="width:60px;text-align:center;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="itemsTableBody">
                        <!-- Dynamic Rows Injected Here -->
                    </tbody>
                    <tfoot>
                        <tr style="background:#f8fafc;font-weight:700;">
                            <td colspan="3" style="text-align:right;font-size:0.95rem;padding:14px;">Grand Total:</td>
                            <td style="font-size:1.15rem;color:var(--admin-primary);padding:14px;">
                                ৳<span id="grandTotalText">0.00</span>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div style="padding:12px 18px;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="addItemRow()">
                    <i class="fa-solid fa-plus"></i> Add Item
                </button>
            </div>
        </div>

        <div style="display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary" style="padding:10px 24px;font-size:0.9rem;">
                <i class="fa-solid fa-save"></i> Complete Purchase & Update Stock
            </button>
            <a href="{{ route('admin.purchases.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<!-- Template Ingredient JSON data for row cloning -->
<script>
const availableIngredients = @json($ingredients);
</script>

@push('admin-scripts')
<script>
let rowIndex = 0;

function addItemRow() {
    const tbody = document.getElementById('itemsTableBody');
    const tr = document.createElement('tr');
    tr.id = 'row_' + rowIndex;

    let ingredientOptions = '<option value="">-- Select Ingredient --</option>';
    availableIngredients.forEach(ing => {
        ingredientOptions += `<option value="${ing.id}" data-unit="${ing.unit}" data-cost="${ing.cost_price}">${ing.name} (${ing.unit.toUpperCase()})</option>`;
    });

    tr.innerHTML = `
        <td>
            <select name="items[${rowIndex}][ingredient_id]" class="form-control" required onchange="handleIngredientChange(this, ${rowIndex})">
                ${ingredientOptions}
            </select>
        </td>
        <td>
            <div style="display:flex;align-items:center;gap:6px;">
                <input type="number" step="0.01" min="0.01" name="items[${rowIndex}][quantity]" id="qty_${rowIndex}" class="form-control" value="1" required oninput="calculateRow(${rowIndex})">
                <span id="unit_${rowIndex}" style="font-size:0.75rem;color:#64748b;font-weight:700;"></span>
            </div>
        </td>
        <td>
            <input type="number" step="0.01" min="0" name="items[${rowIndex}][unit_price]" id="price_${rowIndex}" class="form-control" value="0.00" required oninput="calculateRow(${rowIndex})">
        </td>
        <td>
            <strong style="color:#0f172a;font-size:0.92rem;">৳<span id="subtotal_${rowIndex}">0.00</span></strong>
        </td>
        <td style="text-align:center;">
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(${rowIndex})" title="Remove">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </td>
    `;

    tbody.appendChild(tr);
    rowIndex++;
}

function removeRow(index) {
    const row = document.getElementById('row_' + index);
    if (row) {
        row.remove();
        calculateGrandTotal();
    }
}

function handleIngredientChange(select, index) {
    const selectedOption = select.options[select.selectedIndex];
    const unit = selectedOption.getAttribute('data-unit') || '';
    const cost = parseFloat(selectedOption.getAttribute('data-cost') || 0);

    document.getElementById('unit_' + index).textContent = unit;
    const priceInput = document.getElementById('price_' + index);
    if (parseFloat(priceInput.value) === 0 && cost > 0) {
        priceInput.value = cost.toFixed(2);
    }
    calculateRow(index);
}

function calculateRow(index) {
    const qty = parseFloat(document.getElementById('qty_' + index)?.value) || 0;
    const price = parseFloat(document.getElementById('price_' + index)?.value) || 0;
    const subtotal = qty * price;

    const subtotalElem = document.getElementById('subtotal_' + index);
    if (subtotalElem) {
        subtotalElem.textContent = subtotal.toFixed(2);
    }
    calculateGrandTotal();
}

function calculateGrandTotal() {
    let grand = 0;
    for (let i = 0; i < rowIndex; i++) {
        const subtotalElem = document.getElementById('subtotal_' + i);
        if (subtotalElem) {
            grand += parseFloat(subtotalElem.textContent) || 0;
        }
    }
    document.getElementById('grandTotalText').textContent = grand.toFixed(2);

    const paymentStatus = document.getElementById('paymentStatus').value;
    const paidInput = document.getElementById('paidAmountInput');
    if (paymentStatus === 'paid') {
        paidInput.value = grand.toFixed(2);
    }
}

function handlePaymentStatusChange() {
    const status = document.getElementById('paymentStatus').value;
    const paidInput = document.getElementById('paidAmountInput');
    const grand = parseFloat(document.getElementById('grandTotalText').textContent) || 0;

    if (status === 'paid') {
        paidInput.value = grand.toFixed(2);
    } else if (status === 'unpaid') {
        paidInput.value = '0.00';
    }
}

// Add first row automatically on load
document.addEventListener('DOMContentLoaded', function () {
    addItemRow();
});
</script>
@endpush
@endsection

