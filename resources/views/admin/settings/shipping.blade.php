@extends('layouts.admin')

@section('page-title', 'Shipping Charge')

@section('admin-content')
<div style="max-width: 520px; margin: 20px auto;">
    <div class="card-panel">
        <div class="card-panel-header" style="padding: 18px 24px; border-bottom: 1px solid var(--border-light);">
            <h3 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-truck-fast" style="color: var(--admin-primary);"></i>
                Shipping Charge
            </h3>
        </div>

        <div style="padding: 24px;">
            <form action="{{ route('admin.settings.shipping.update') }}" method="POST">
                @csrf

                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label" for="deliveryChargeInput" style="font-weight: 700; font-size: 0.9rem; margin-bottom: 8px;">
                        Delivery Charge (৳)
                    </label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <span style="position: absolute; left: 14px; font-weight: 800; color: #64748b; font-size: 1.1rem; pointer-events: none;">৳</span>
                        <input 
                            type="number" 
                            step="1" 
                            min="0" 
                            name="delivery_charge" 
                            id="deliveryChargeInput" 
                            class="form-control" 
                            value="{{ old('delivery_charge', $deliveryCharge) }}" 
                            placeholder="0"
                            required 
                            style="padding-left: 38px; font-size: 1.15rem; font-weight: 800; height: 48px;"
                        >
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; height: 44px; font-size: 0.92rem; font-weight: 700; justify-content: center;">
                    Save
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
