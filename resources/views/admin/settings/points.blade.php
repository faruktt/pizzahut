@extends('layouts.admin')

@section('page-title', 'Reward Points')

@section('admin-content')
<div style="max-width: 520px; margin: 20px auto;">
    <div class="card-panel">
        <div class="card-panel-header" style="padding: 18px 24px; border-bottom: 1px solid var(--border-light);">
            <h3 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-coins" style="color: #f59e0b;"></i>
                Reward Points
            </h3>
        </div>

        <div style="padding: 24px;">
            <form action="{{ route('admin.settings.points.update') }}" method="POST">
                @csrf

                <div class="form-row" style="margin-bottom: 18px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Points *</label>
                        <input type="number" name="point_rate_points" class="form-control" 
                               value="{{ old('point_rate_points', $pointRatePoints) }}" 
                               min="1" step="1" required style="font-size: 1.05rem; font-weight: 700;">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Discount (৳) *</label>
                        <input type="number" name="point_rate_taka" class="form-control" 
                               value="{{ old('point_rate_taka', $pointRateTaka) }}" 
                               min="0.01" step="0.5" required style="font-size: 1.05rem; font-weight: 700;">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 22px;">
                    <label class="form-label">Minimum Points to Redeem</label>
                    <input type="number" name="min_redeem_points" class="form-control" 
                           value="{{ old('min_redeem_points', $minRedeemPoints) }}" 
                           min="1" step="1" style="font-size: 1rem; font-weight: 700;">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; height: 44px; font-size: 0.92rem; font-weight: 700; justify-content: center;">
                    Save
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
