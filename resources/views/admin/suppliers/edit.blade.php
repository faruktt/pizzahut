@extends('layouts.admin')
@section('title', 'Edit Supplier | Italian Pizza Hut Admin')
@section('page-title', 'Edit Supplier')

@section('admin-content')
<div style="max-width:750px;">
    <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary" style="margin-bottom:20px;">
        <i class="fa-solid fa-arrow-left"></i> Back to Suppliers
    </a>

    <form action="{{ route('admin.suppliers.update', $supplier->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-panel">
            <div class="card-panel-header">
                <span class="card-panel-title"><i class="fa-solid fa-pen-to-square"></i> Edit Supplier: {{ $supplier->name }}</span>
            </div>
            <div class="card-panel-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Contact / Supplier Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $supplier->name) }}" required>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Company / Shop Name</label>
                        <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $supplier->company_name) }}">
                        @error('company_name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $supplier->phone) }}">
                        @error('phone')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $supplier->email) }}">
                        @error('email')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="3">{{ old('address', $supplier->address) }}</textarea>
                    @error('address')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div style="display:flex;align-items:center;gap:10px;margin-top:10px;">
                    <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:0.85rem;font-weight:600;">
                        <input type="checkbox" name="is_active" {{ old('is_active', $supplier->is_active) ? 'checked' : '' }}>
                        <i class="fa-solid fa-check" style="color:#10b981;"></i> Active Supplier
                    </label>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:12px;margin-top:20px;">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-save"></i> Update Supplier
            </button>
            <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

