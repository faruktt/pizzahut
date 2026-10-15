@extends('layouts.admin')
@section('title', 'Edit Combo | Admin')
@section('page-title', 'Edit Combo: ' . $combo->name)

@push('admin-styles')
<style>
.product-picker-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 12px;
    margin-top: 10px;
    max-height: 420px;
    overflow-y: auto;
    padding: 4px;
}
.product-picker-card {
    border: 2px solid var(--border-light);
    border-radius: 10px;
    padding: 10px;
    cursor: pointer;
    transition: all 0.2s;
    background: #fff;
    text-align: center;
    position: relative;
}
.product-picker-card:hover { border-color: #94a3b8; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
.product-picker-card.selected { border-color: var(--admin-primary); background: #fff5f6; }
.product-picker-card .check-badge {
    display: none;
    position: absolute;
    top: 6px; right: 6px;
    width: 20px; height: 20px;
    background: var(--admin-primary);
    color: #fff;
    border-radius: 50%;
    font-size: 0.7rem;
    align-items: center; justify-content: center;
}
.product-picker-card.selected .check-badge { display: flex; }
.product-picker-card img { width: 100%; height: 80px; object-fit: cover; border-radius: 6px; margin-bottom: 6px; }
.product-picker-card .pname { font-size: 0.78rem; font-weight: 700; color: #0f172a; line-height: 1.3; }
.product-picker-card .pcat { font-size: 0.7rem; color: #94a3b8; margin-top: 2px; }
.selected-tags { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px; min-height: 30px; }
.selected-tag {
    display: inline-flex; align-items: center; gap: 5px;
    background: #fde8eb; color: var(--admin-primary);
    border-radius: 20px; padding: 3px 10px; font-size: 0.75rem; font-weight: 700;
}
.selected-tag .rm { cursor: pointer; font-size: 0.7rem; }
.img-preview-box { width: 120px; height: 90px; border: 2px dashed var(--border-light); border-radius: 8px; display:flex; align-items:center; justify-content:center; overflow:hidden; background:#f8fafc; }
.img-preview-box img { width:100%; height:100%; object-fit:cover; }
</style>
@endpush

@section('admin-content')
<div style="max-width:900px;">
    <form method="POST" action="{{ route('admin.combos.update', $combo->id) }}" enctype="multipart/form-data" id="comboForm">
        @csrf @method('PUT')

        <div class="card-panel" style="margin-bottom:20px;">
            <div class="card-panel-header">
                <span class="card-panel-title"><i class="fa-solid fa-info-circle" style="color:var(--admin-primary);margin-right:6px;"></i> Combo Details</span>
            </div>
            <div class="card-panel-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label"><i class="fa-solid fa-tag"></i> Combo Name <span style="color:red;">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $combo->name) }}" required>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row" style="grid-template-columns:1fr 1fr;gap:12px;">
                        <div class="form-group">
                            <label class="form-label"><i class="fa-solid fa-bangladeshi-taka-sign"></i> Price (৳) <span style="color:red;">*</span></label>
                            <input type="number" name="price" class="form-control" value="{{ old('price', $combo->price) }}" min="0" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fa-solid fa-check-square"></i> Max Items Customer Selects</label>
                            <input type="number" name="max_select" class="form-control" value="{{ old('max_select', $combo->max_select) }}" min="1" max="10">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="fa-solid fa-align-left"></i> Description</label>
                    <textarea name="description" class="form-control" rows="2">{{ old('description', $combo->description) }}</textarea>
                </div>

                <div class="form-row" style="grid-template-columns:auto 1fr;gap:20px;align-items:start;">
                    <div class="form-group">
                        <label class="form-label"><i class="fa-solid fa-image"></i> Combo Image</label>
                        <div class="img-preview-box" id="imgPreviewBox">
                            @if($combo->image && file_exists(public_path('uploads/'.$combo->image)))
                                <img src="{{ asset('uploads/'.$combo->image) }}" id="currentImg">
                            @else
                                <i class="fa-solid fa-image" style="font-size:1.5rem;color:#cbd5e1;"></i>
                            @endif
                        </div>
                        <input type="file" name="image" id="imageInput" accept="image/*" style="margin-top:8px;font-size:0.8rem;" onchange="previewImg(this)">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fa-solid fa-sort-numeric-up"></i> Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $combo->sort_order) }}" min="0" style="max-width:120px;">
                        <div style="margin-top:14px;">
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:0.85rem;font-weight:600;">
                                <input type="checkbox" name="is_active" value="1" {{ $combo->is_active ? 'checked' : '' }}> Active
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-panel">
            <div class="card-panel-header">
                <span class="card-panel-title"><i class="fa-solid fa-boxes-stacked" style="color:var(--admin-primary);margin-right:6px;"></i> Select Products</span>
                <span id="selectedCountBadge" style="background:#fde8eb;color:var(--admin-primary);padding:4px 12px;border-radius:20px;font-size:0.8rem;font-weight:700;">0 selected</span>
            </div>
            <div class="card-panel-body">
                @error('product_ids')
                    <div class="alert-box alert-error" style="margin-bottom:12px;">{{ $message }}</div>
                @enderror

                <div class="form-group" style="margin-bottom:10px;">
                    <input type="text" id="productSearch" class="form-control" placeholder="Search products..." oninput="filterProducts(this.value)">
                </div>

                <div class="selected-tags" id="selectedTagsContainer"></div>

                <div class="product-picker-grid" id="productGrid">
                    @foreach($products as $product)
                    <div class="product-picker-card {{ in_array($product->id, $selectedIds) ? 'selected' : '' }}" 
                         id="card-{{ $product->id }}" 
                         data-id="{{ $product->id }}" 
                         data-name="{{ $product->name }}"
                         data-cat="{{ $product->category->name ?? '' }}"
                         onclick="toggleProduct({{ $product->id }}, '{{ addslashes($product->name) }}')">
                        <div class="check-badge"><i class="fa-solid fa-check"></i></div>
                        @if($product->image && file_exists(public_path('uploads/'.$product->image)))
                            <img src="{{ asset('uploads/'.$product->image) }}" alt="{{ $product->name }}">
                        @else
                            <div style="width:100%;height:80px;background:#f1f5f9;border-radius:6px;display:flex;align-items:center;justify-content:center;margin-bottom:6px;">
                                <i class="fa-solid fa-pizza-slice" style="color:#cbd5e1;font-size:1.5rem;"></i>
                            </div>
                        @endif
                        <div class="pname">{{ $product->name }}</div>
                        <div class="pcat">{{ $product->category->name ?? 'General' }}</div>
                    </div>
                    @endforeach
                </div>

                <div id="hiddenInputs"></div>
            </div>
        </div>

        <div style="display:flex;gap:12px;margin-top:4px;">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Update Combo</button>
            <a href="{{ route('admin.combos.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Cancel</a>
        </div>
    </form>
</div>
@endsection

@push('admin-scripts')
<script>
let selectedIds = @json($selectedIds);
let selectedNames = {};

// Pre-populate names from already selected
@foreach($combo->products as $p)
selectedNames[{{ $p->id }}] = '{{ addslashes($p->name) }}';
@endforeach

renderTags();
renderHiddenInputs();
document.getElementById('selectedCountBadge').textContent = selectedIds.length + ' selected';

function toggleProduct(id, name) {
    const idx = selectedIds.indexOf(id);
    if (idx > -1) {
        selectedIds.splice(idx, 1);
        delete selectedNames[id];
        document.getElementById('card-' + id).classList.remove('selected');
    } else {
        selectedIds.push(id);
        selectedNames[id] = name;
        document.getElementById('card-' + id).classList.add('selected');
    }
    renderTags();
    renderHiddenInputs();
    document.getElementById('selectedCountBadge').textContent = selectedIds.length + ' selected';
}

function renderTags() {
    const container = document.getElementById('selectedTagsContainer');
    if (selectedIds.length === 0) {
        container.innerHTML = '<span style="font-size:0.75rem;color:#94a3b8;padding:4px;">No products selected...</span>';
        return;
    }
    container.innerHTML = selectedIds.map(id => 
        `<span class="selected-tag"><i class="fa-solid fa-pizza-slice"></i>${selectedNames[id] || 'Product #'+id}<span class="rm" onclick="toggleProduct(${id},'')">✕</span></span>`
    ).join('');
}

function renderHiddenInputs() {
    document.getElementById('hiddenInputs').innerHTML = 
        selectedIds.map(id => `<input type="hidden" name="product_ids[]" value="${id}">`).join('');
}

function filterProducts(query) {
    document.querySelectorAll('.product-picker-card').forEach(card => {
        const match = card.dataset.name.toLowerCase().includes(query.toLowerCase()) ||
                      card.dataset.cat.toLowerCase().includes(query.toLowerCase());
        card.style.display = match ? '' : 'none';
    });
}

function previewImg(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { document.getElementById('imgPreviewBox').innerHTML = `<img src="${e.target.result}">`; };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
