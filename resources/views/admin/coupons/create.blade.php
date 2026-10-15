@extends('layouts.admin')
@section('title', 'Add Coupon | Italian Pizza Admin')
@section('page-title', 'Create New Coupon')

@section('admin-content')
<div style="max-width:750px;">
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary" style="margin-bottom:20px;">
        <i class="fa-solid fa-arrow-left"></i> Back to Coupons
    </a>

    <div class="card-panel">
        <div class="card-panel-header">
            <span class="card-panel-title"><i class="fa-solid fa-ticket"></i> Coupon Details</span>
        </div>
        <form action="{{ route('admin.coupons.store') }}" method="POST" class="card-panel-body">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Coupon Code *</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="e.g. SUMMER20" style="text-transform:uppercase;" required>
                    @error('code')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Discount Type *</label>
                    <select name="type" id="couponTypeSelect" class="form-control" onchange="handleTypeChange(this.value)" required>
                        <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>Percentage Discount (%)</option>
                        <option value="fixed" {{ old('type', 'fixed') === 'fixed' ? 'selected' : '' }}>Fixed Amount (৳)</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" id="valLabel">Discount Value (৳) *</label>
                    <input type="number" step="0.01" min="0.01" name="value" class="form-control" value="{{ old('value') }}" placeholder="e.g. 100 or 15" required>
                    @error('value')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" id="maxDiscountGroup" style="display:none;">
                    <label class="form-label">Maximum Discount Limit (৳)</label>
                    <input type="number" step="0.01" min="0" name="max_discount" class="form-control" value="{{ old('max_discount') }}" placeholder="e.g. 250">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Minimum Order Amount (৳)</label>
                    <input type="number" step="0.01" min="0" name="min_order_amount" class="form-control" value="{{ old('min_order_amount', 0) }}" placeholder="0 for no minimum">
                </div>
                <div class="form-group">
                    <label class="form-label">Usage Limit</label>
                    <input type="number" min="1" name="usage_limit" class="form-control" value="{{ old('usage_limit') }}" placeholder="Leave blank for unlimited">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Expiry Date & Time</label>
                    <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <div style="padding-top:10px;">
                        <label style="display:inline-flex; align-items:center; gap:8px; cursor:pointer; font-weight:700;">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} style="width:18px; height:18px;">
                            <span>Active</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description / Note</label>
                <textarea name="description" class="form-control" rows="2" placeholder="e.g. Flat ৳100 discount on weekend orders">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="padding:10px 24px;">
                <i class="fa-solid fa-check"></i> Save Coupon
            </button>
        </form>
    </div>
</div>

@push('admin-scripts')
<script>
    function handleTypeChange(val) {
        const isPercent = (val === 'percentage');
        document.getElementById('valLabel').innerText = isPercent ? 'Discount Percentage (%) *' : 'Discount Amount (৳) *';
        document.getElementById('maxDiscountGroup').style.display = isPercent ? 'block' : 'none';
    }
    document.addEventListener('DOMContentLoaded', () => {
        handleTypeChange(document.getElementById('couponTypeSelect').value);
    });
</script>
@endpush
@endsection
