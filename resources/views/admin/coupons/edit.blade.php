@extends('layouts.admin')
@section('title', 'Edit Coupon | Italian Pizza Admin')
@section('page-title', 'Edit Coupon #' . $coupon->code)

@section('admin-content')
<div style="max-width:750px;">
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary" style="margin-bottom:20px;">
        <i class="fa-solid fa-arrow-left"></i> Back to Coupons
    </a>

    <div class="card-panel">
        <div class="card-panel-header">
            <span class="card-panel-title"><i class="fa-solid fa-ticket"></i> Edit Coupon: {{ $coupon->code }}</span>
        </div>
        <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST" class="card-panel-body">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Coupon Code *</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $coupon->code) }}" placeholder="e.g. SUMMER20" style="text-transform:uppercase;" required>
                    @error('code')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Discount Type *</label>
                    <select name="type" id="couponTypeSelect" class="form-control" onchange="handleTypeChange(this.value)" required>
                        <option value="percentage" {{ old('type', $coupon->type) === 'percentage' ? 'selected' : '' }}>Percentage Discount (%)</option>
                        <option value="fixed" {{ old('type', $coupon->type) === 'fixed' ? 'selected' : '' }}>Fixed Amount (৳)</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" id="valLabel">Discount Value (৳) *</label>
                    <input type="number" step="0.01" min="0.01" name="value" class="form-control" value="{{ old('value', $coupon->value) }}" required>
                    @error('value')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" id="maxDiscountGroup" style="display:none;">
                    <label class="form-label">Maximum Discount Limit (৳)</label>
                    <input type="number" step="0.01" min="0" name="max_discount" class="form-control" value="{{ old('max_discount', $coupon->max_discount) }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Minimum Order Amount (৳)</label>
                    <input type="number" step="0.01" min="0" name="min_order_amount" class="form-control" value="{{ old('min_order_amount', $coupon->min_order_amount) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Usage Limit</label>
                    <input type="number" min="1" name="usage_limit" class="form-control" value="{{ old('usage_limit', $coupon->usage_limit) }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Expiry Date & Time</label>
                    <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at', $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\TH:i') : '') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <div style="padding-top:10px;">
                        <label style="display:inline-flex; align-items:center; gap:8px; cursor:pointer; font-weight:700;">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }} style="width:18px; height:18px;">
                            <span>Active</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description / Note</label>
                <textarea name="description" class="form-control" rows="2">{{ old('description', $coupon->description) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="padding:10px 24px;">
                <i class="fa-solid fa-check"></i> Update Coupon
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
