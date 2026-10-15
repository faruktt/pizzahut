@extends('layouts.admin')
@section('title', 'Edit Add On | Italian Pizza Admin')
@section('page-title', 'Edit Add On')

@section('admin-content')
<div style="max-width: 700px;">
    <a href="{{ route('admin.addons.index') }}" class="btn btn-secondary" style="margin-bottom:20px;">
        <i class="fa-solid fa-arrow-left"></i> Back to List
    </a>

    <form action="{{ route('admin.addons.update', $addon->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-panel" style="margin-bottom:20px;">
            <div class="card-panel-header">
                <span class="card-panel-title"><i class="fa-solid fa-plus-circle"></i> Add On Details</span>
            </div>
            <div class="card-panel-body">
                <div class="form-group">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $addon->name) }}" required placeholder="e.g. Extra Cheese, Chicken, Spicy Chicken">
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Icon (Emoji or FontAwesome class)</label>
                    <input type="text" name="icon" class="form-control" value="{{ old('icon', $addon->icon) }}" placeholder="e.g. 🧀 or fa-solid fa-cheese">
                    @if($addon->icon)
                        <div style="margin-top:8px; font-size:1.5rem;">{{ $addon->icon }}</div>
                    @endif
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Price (৳) *</label>
                        <input type="number" name="price" step="0.01" min="0" class="form-control" value="{{ old('price', $addon->price) }}" required>
                    </div>
                </div>

                <div style="display:flex;align-items:center;gap:12px;padding-top:4px;">
                    <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:0.85rem;font-weight:600;">
                        <input type="checkbox" name="is_active" {{ old('is_active', $addon->is_active) ? 'checked' : '' }}> <i class="fa-solid fa-check" style="color:#10b981;"></i> Active (Show on frontend & POS)
                    </label>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-save"></i> Update Add On
            </button>
            <a href="{{ route('admin.addons.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection