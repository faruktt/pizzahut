@extends('layouts.admin')

@section('title', 'Create Walk-in / Phone Order | Italian Pizza')
@section('page-title', 'Create Manual Order (Without Customer Account)')

@section('admin-content')
<div style="max-width: 900px; margin: 0 auto;">
    <div class="card-panel">
        <div class="card-panel-header">
            <h3 class="card-panel-title">➕ New Order Entry</h3>
            <a href="{{ route('admin.orders.index') }}" class="btn-view-store">« Back to Orders</a>
        </div>

        <form action="{{ route('admin.orders.store') }}" method="POST" class="card-panel-body" id="manualOrderForm">
            @csrf

            <!-- 1. Order Type & Dining Table / Location -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                <div>
                    <label style="display:block; font-size:0.8rem; font-weight:700; color:#334155; margin-bottom:6px;">Order Type *</label>
                    <select name="order_type" id="orderTypeSelect" class="form-control" style="width:100%; padding:9px;" onchange="toggleTableSelection(this.value)" required>
                        <option value="takeaway">🛍️ Takeaway / Counter Walk-in</option>
                        <option value="delivery">🛵 Home Delivery</option>
                        <option value="dine_in">🍽️ Dine-in Table</option>
                    </select>
                </div>

                <div id="tableSelectWrapper" style="display:none;">
                    <label style="display:block; font-size:0.8rem; font-weight:700; color:#334155; margin-bottom:6px;">Select Table</label>
                    <select name="restaurant_table_id" class="form-control" style="width:100%; padding:9px;">
                        @foreach($tables as $tbl)
                            <option value="{{ $tbl->id }}">{{ $tbl->table_number }} (Cap: {{ $tbl->capacity }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- 2. Customer Optional Details -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                <div>
                    <label style="display:block; font-size:0.8rem; font-weight:700; color:#334155; margin-bottom:6px;">Customer Name (Optional)</label>
                    <input type="text" name="customer_name" class="form-control" placeholder="Walk-in Guest" style="width:100%; padding:9px; border:1px solid #cbd5e1; border-radius:6px;">
                </div>
                <div>
                    <label style="display:block; font-size:0.8rem; font-weight:700; color:#334155; margin-bottom:6px;">Customer Phone (Optional)</label>
                    <input type="text" name="customer_phone" class="form-control" placeholder="017xxxxxxxx" style="width:100%; padding:9px; border:1px solid #cbd5e1; border-radius:6px;">
                </div>
            </div>

            <!-- 3. Items Selection -->
            <div style="margin-bottom: 24px;">
                <label style="display:block; font-size:0.95rem; font-weight:800; color:#0f172a; margin-bottom:10px;">Select Menu Items *</label>
                
                <div id="orderItemsRows" style="display:flex; flex-direction:column; gap:10px;">
                    <!-- First Item Row -->
                    <div class="item-input-row" style="display:grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap:10px; align-items:center; background:#f8fafc; padding:12px; border-radius:8px; border:1px solid #e2e8f0;">
                        <div>
                            <select name="items[0][product_id]" class="form-control prod-select" onchange="handleProductSelect(this, 0)" style="width:100%; padding:8px;" required>
                                <option value="">-- Choose Product --</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}" data-name="{{ $p->name }}" data-price="{{ $p->base_price }}">
                                        {{ $p->name }} (৳{{ number_format($p->base_price) }})
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="items[0][product_name]" id="prodName-0">
                        </div>

                        <div>
                            <select name="items[0][size_name]" class="form-control" style="width:100%; padding:8px;">
                                <option value="Personal">Personal</option>
                                <option value="Medium" selected>Medium</option>
                                <option value="Family">Family</option>
                                <option value="Large">Large</option>
                            </select>
                        </div>

                        <div>
                            <select name="items[0][crust_name]" class="form-control" style="width:100%; padding:8px;">
                                <option value="Pan Crust">Pan Crust</option>
                                <option value="Stuffed Crust">Stuffed Crust</option>
                                <option value="Sausage Crust">Sausage Crust</option>
                            </select>
                        </div>

                        <div>
                            <input type="number" name="items[0][quantity]" value="1" min="1" class="form-control" placeholder="Qty" style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;" required>
                            <input type="hidden" name="items[0][unit_price]" id="unitPrice-0" value="0">
                        </div>

                        <div>
                            <button type="button" onclick="this.closest('.item-input-row').remove()" style="background:none; border:none; color:#ef4444; font-size:1.1rem; cursor:pointer;">✕</button>
                        </div>
                    </div>
                </div>

                <button type="button" onclick="addNewItemRow()" class="btn-view-store" style="margin-top: 10px;">
                    ➕ Add Another Item
                </button>
            </div>

            <!-- Payment & Submit -->
            <div style="border-top: 1px solid #e2e8f0; padding-top: 18px; display:flex; justify-content:space-between; align-items:center;">
                <div style="display:flex; gap:12px; align-items:center;">
                    <label style="font-size:0.85rem; font-weight:700;">Payment:</label>
                    <select name="payment_status" class="form-control" style="padding:6px 12px;">
                        <option value="paid">Paid (Cash / Card)</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>

                <button type="submit" class="btn-quick-pos" style="padding: 10px 24px; font-size: 0.95rem;">
                    Submit Order ➔
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('admin-scripts')
<script>
    let rowIndex = 1;

    function toggleTableSelection(val) {
        document.getElementById('tableSelectWrapper').style.display = (val === 'dine_in') ? 'block' : 'none';
    }

    function handleProductSelect(selectEl, idx) {
        const option = selectEl.options[selectEl.selectedIndex];
        document.getElementById('prodName-' + idx).value = option.dataset.name || '';
        document.getElementById('unitPrice-' + idx).value = option.dataset.price || 0;
    }

    function addNewItemRow() {
        const container = document.getElementById('orderItemsRows');
        const row = document.createElement('div');
        row.className = 'item-input-row';
        row.style = 'display:grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap:10px; align-items:center; background:#f8fafc; padding:12px; border-radius:8px; border:1px solid #e2e8f0;';
        
        row.innerHTML = `
            <div>
                <select name="items[${rowIndex}][product_id]" class="form-control prod-select" onchange="handleProductSelect(this, ${rowIndex})" style="width:100%; padding:8px;" required>
                    <option value="">-- Choose Product --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" data-name="{{ $p->name }}" data-price="{{ $p->base_price }}">
                            {{ $p->name }} (৳{{ number_format($p->base_price) }})
                        </option>
                    @endforeach
                </select>
                <input type="hidden" name="items[${rowIndex}][product_name]" id="prodName-${rowIndex}">
            </div>

            <div>
                <select name="items[${rowIndex}][size_name]" class="form-control" style="width:100%; padding:8px;">
                    <option value="Personal">Personal</option>
                    <option value="Medium" selected>Medium</option>
                    <option value="Family">Family</option>
                    <option value="Large">Large</option>
                </select>
            </div>

            <div>
                <select name="items[${rowIndex}][crust_name]" class="form-control" style="width:100%; padding:8px;">
                    <option value="Pan Crust">Pan Crust</option>
                    <option value="Stuffed Crust">Stuffed Crust</option>
                    <option value="Sausage Crust">Sausage Crust</option>
                </select>
            </div>

            <div>
                <input type="number" name="items[${rowIndex}][quantity]" value="1" min="1" class="form-control" placeholder="Qty" style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;" required>
                <input type="hidden" name="items[${rowIndex}][unit_price]" id="unitPrice-${rowIndex}" value="0">
            </div>

            <div>
                <button type="button" onclick="this.closest('.item-input-row').remove()" style="background:none; border:none; color:#ef4444; font-size:1.1rem; cursor:pointer;">✕</button>
            </div>
        `;

        container.appendChild(row);
        rowIndex++;
    }
</script>
@endpush
