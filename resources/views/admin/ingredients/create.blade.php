@extends('layouts.admin')
@section('title', 'Add Ingredient | Italian Pizza Hut Admin')
@section('page-title', 'Add New Ingredient')

@section('admin-content')
<div style="max-width:750px;">
    <a href="{{ route('admin.ingredients.index') }}" class="btn btn-secondary" style="margin-bottom:20px;">
        <i class="fa-solid fa-arrow-left"></i> Back to Ingredients
    </a>

    <form action="{{ route('admin.ingredients.store') }}" method="POST">
        @csrf

        <div class="card-panel">
            <div class="card-panel-header">
                <span class="card-panel-title"><i class="fa-solid fa-carrot"></i> Ingredient Information</span>
            </div>
            <div class="card-panel-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Ingredient Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. Mozzarella Cheese, Pizza Flour, Oregano">
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Code / SKU</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="e.g. ING-001">
                        @error('code')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-row-3">
                    <div class="form-group">
                        <label class="form-label">Measurement Unit *</label>
                        <select name="unit" class="form-control" required>
                            <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                            <option value="g" {{ old('unit') == 'g' ? 'selected' : '' }}>Gram (g)</option>
                            <option value="l" {{ old('unit') == 'l' ? 'selected' : '' }}>Liter (L)</option>
                            <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>Milliliter (ml)</option>
                            <option value="pcs" {{ old('unit') == 'pcs' ? 'selected' : '' }}>Pieces (pcs)</option>
                            <option value="box" {{ old('unit') == 'box' ? 'selected' : '' }}>Box</option>
                            <option value="packet" {{ old('unit') == 'packet' ? 'selected' : '' }}>Packet</option>
                            <option value="can" {{ old('unit') == 'can' ? 'selected' : '' }}>Can / Tin</option>
                        </select>
                        @error('unit')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Approx Cost per Unit (৳) *</label>
                        <input type="number" step="0.01" min="0" name="cost_price" class="form-control" value="{{ old('cost_price', 0) }}" required>
                        @error('cost_price')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Initial Stock</label>
                        <input type="number" step="0.01" min="0" name="current_stock" class="form-control" value="{{ old('current_stock', 0) }}">
                        @error('current_stock')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Low Stock Alert Quantity</label>
                        <input type="number" step="0.01" min="0" name="alert_quantity" class="form-control" value="{{ old('alert_quantity', 5) }}" placeholder="e.g. 5">
                        <span class="form-hint">Shows alert badge when stock falls below or equal to this amount</span>
                        @error('alert_quantity')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group" style="display:flex;align-items:flex-end;">
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:0.86rem;font-weight:600;padding-bottom:12px;">
                            <input type="checkbox" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                            <i class="fa-solid fa-check" style="color:#10b981;"></i> Active in Inventory
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:12px;margin-top:20px;">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-save"></i> Save Ingredient
            </button>
            <a href="{{ route('admin.ingredients.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

