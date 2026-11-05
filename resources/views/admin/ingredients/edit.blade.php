@extends('layouts.admin')
@section('title', 'Edit Ingredient | Italian Pizza Hut Admin')
@section('page-title', 'Edit Ingredient')

@section('admin-content')
<div style="max-width:750px;">
    <a href="{{ route('admin.ingredients.index') }}" class="btn btn-secondary" style="margin-bottom:20px;">
        <i class="fa-solid fa-arrow-left"></i> Back to Ingredients
    </a>

    <form action="{{ route('admin.ingredients.update', $ingredient->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-panel">
            <div class="card-panel-header">
                <span class="card-panel-title"><i class="fa-solid fa-pen-to-square"></i> Edit Ingredient: {{ $ingredient->name }}</span>
            </div>
            <div class="card-panel-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Ingredient Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $ingredient->name) }}" required>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Code / SKU</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code', $ingredient->code) }}">
                        @error('code')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-row-3">
                    <div class="form-group">
                        <label class="form-label">Measurement Unit *</label>
                        <select name="unit" class="form-control" required>
                            @php $u = old('unit', $ingredient->unit); @endphp
                            <option value="kg" {{ $u == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                            <option value="g" {{ $u == 'g' ? 'selected' : '' }}>Gram (g)</option>
                            <option value="l" {{ $u == 'l' ? 'selected' : '' }}>Liter (L)</option>
                            <option value="ml" {{ $u == 'ml' ? 'selected' : '' }}>Milliliter (ml)</option>
                            <option value="pcs" {{ $u == 'pcs' ? 'selected' : '' }}>Pieces (pcs)</option>
                            <option value="box" {{ $u == 'box' ? 'selected' : '' }}>Box</option>
                            <option value="packet" {{ $u == 'packet' ? 'selected' : '' }}>Packet</option>
                            <option value="can" {{ $u == 'can' ? 'selected' : '' }}>Can / Tin</option>
                        </select>
                        @error('unit')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Approx Cost per Unit (৳) *</label>
                        <input type="number" step="0.01" min="0" name="cost_price" class="form-control" value="{{ old('cost_price', (float)$ingredient->cost_price) }}" required>
                        @error('cost_price')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Current Stock *</label>
                        <input type="number" step="0.01" min="0" name="current_stock" class="form-control" value="{{ old('current_stock', (float)$ingredient->current_stock) }}" required>
                        @error('current_stock')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Low Stock Alert Quantity *</label>
                        <input type="number" step="0.01" min="0" name="alert_quantity" class="form-control" value="{{ old('alert_quantity', (float)$ingredient->alert_quantity) }}" required>
                        <span class="form-hint">Shows alert badge when stock falls below or equal to this amount</span>
                        @error('alert_quantity')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group" style="display:flex;align-items:flex-end;">
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:0.86rem;font-weight:600;padding-bottom:12px;">
                            <input type="checkbox" name="is_active" {{ old('is_active', $ingredient->is_active) ? 'checked' : '' }}>
                            <i class="fa-solid fa-check" style="color:#10b981;"></i> Active in Inventory
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:12px;margin-top:20px;">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-save"></i> Update Ingredient
            </button>
            <a href="{{ route('admin.ingredients.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

