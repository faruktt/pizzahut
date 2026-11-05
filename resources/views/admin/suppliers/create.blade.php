@extends('layouts.admin')
@section('title', 'Add Supplier | Italian Pizza Hut Admin')
@section('page-title', 'Add New Supplier')

@section('admin-content')
<div style="max-width:750px;">
    <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary" style="margin-bottom:20px;">
        <i class="fa-solid fa-arrow-left"></i> Back to Suppliers
    </a>

    <form action="{{ route('admin.suppliers.store') }}" method="POST">
        @csrf

        <div class="card-panel">
            <div class="card-panel-header">
                <span class="card-panel-title"><i class="fa-solid fa-truck"></i> Supplier Details</span>
            </div>
            <div class="card-panel-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Contact / Supplier Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. Rahim Agro / John Doe">
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Company / Shop Name</label>
                        <input type="text" name="company_name" class="form-control" value="{{ old('company_name') }}" placeholder="e.g. Dhaka Dairy & Cheese Supply">
                        @error('company_name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="e.g. 01700000000">
                        @error('phone')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="e.g. supplier@example.com">
                        @error('email')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="3" placeholder="Full address of the supplier...">{{ old('address') }}</textarea>
                    @error('address')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div style="display:flex;align-items:center;gap:10px;margin-top:10px;">
                    <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:0.85rem;font-weight:600;">
                        <input type="checkbox" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                        <i class="fa-solid fa-check" style="color:#10b981;"></i> Active Supplier
                    </label>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:12px;margin-top:20px;">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-save"></i> Save Supplier
            </button>
            <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

