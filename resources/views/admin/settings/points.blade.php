@extends('layouts.admin')
@section('title', 'Reward Points Settings | Italian Pizza Admin')
@section('page-title', 'Reward Points & Loyalty Management')

@section('admin-content')
<div style="display:grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items:flex-start;">

    {{-- Main Settings Panel --}}
    <div>
        <div class="card-panel">
            <div class="card-panel-header" style="background:#fffbeb; border-bottom:1px solid #fde68a;">
                <span class="card-panel-title" style="color:#b45309;">
                    <i class="fa-solid fa-coins" style="color:#f59e0b; margin-right:6px;"></i>
                    Points Redemption Rate (পয়েন্ট কনভার্সন রেট)
                </span>
            </div>
            <div class="card-panel-body">
                <p style="font-size:0.86rem; color:#64748b; margin-bottom:20px; line-height:1.5;">
                    এখানে নির্ধারণ করুন কাস্টমার কত পয়েন্ট রিডিম করলে কত টাকা ডিসকাউন্ট পাবে।
                    (Set how many reward points equal how much Taka discount in the cart/checkout).
                </p>

                <form action="{{ route('admin.settings.points.update') }}" method="POST">
                    @csrf
                    
                    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:20px; margin-bottom:20px;">
                        <div style="font-size:0.95rem; font-weight:800; color:#1e293b; margin-bottom:14px; display:flex; align-items:center; gap:8px;">
                            <i class="fa-solid fa-calculator" style="color:var(--admin-primary);"></i>
                            Conversion Formula (কত পয়েন্টে কত টাকা ছাড়):
                        </div>

                        <div style="display:grid; grid-template-columns: 1fr auto 1fr; gap:16px; align-items:center;">
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label" style="font-weight:700;">Points (পয়েন্ট সংখ্যা) *</label>
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <input type="number" name="point_rate_points" id="ratePoints" 
                                           class="form-control" value="{{ old('point_rate_points', $pointRatePoints) }}" 
                                           min="1" step="1" required oninput="calculateSimulator()"
                                           style="font-size:1.1rem; font-weight:800; text-align:center;">
                                    <span style="font-size:0.85rem; font-weight:700; color:#64748b;">Points</span>
                                </div>
                            </div>

                            <div style="font-size:1.5rem; font-weight:900; color:#94a3b8; text-align:center; padding-top:14px;">
                                =
                            </div>

                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label" style="font-weight:700;">Cash Discount (টাকা ছাড়) *</label>
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <span style="font-size:1.1rem; font-weight:800; color:var(--admin-primary);">৳</span>
                                    <input type="number" name="point_rate_taka" id="rateTaka" 
                                           class="form-control" value="{{ old('point_rate_taka', $pointRateTaka) }}" 
                                           min="0.01" step="0.5" required oninput="calculateSimulator()"
                                           style="font-size:1.1rem; font-weight:800; text-align:center;">
                                </div>
                            </div>
                        </div>

                        {{-- Dynamic Live Formula Display --}}
                        <div id="rateSummaryBadge" style="margin-top:16px; background:#ecfdf5; border:1px solid #a7f3d0; border-radius:8px; padding:12px 16px; display:flex; align-items:center; justify-content:space-between;">
                            <div style="font-size:0.9rem; font-weight:700; color:#065f46;">
                                <i class="fa-solid fa-circle-check" style="margin-right:6px;"></i>
                                Current Rule: <span id="displayFormula"><strong>{{ $pointRatePoints }} Points = ৳ {{ number_format($pointRateTaka, 2) }} Discount</strong></span>
                            </div>
                            <span id="ratePerPoint" style="font-size:0.8rem; color:#047857; font-weight:700; background:#fff; padding:4px 10px; border-radius:20px; border:1px solid #a7f3d0;">
                                (1 Pt = ৳{{ number_format($pointRateTaka / ($pointRatePoints ?: 1), 2) }})
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Minimum Points Required to Redeem (সর্বনিম্ন কত পয়েন্ট থাকলে রিডিম করা যাবে)</label>
                        <input type="number" name="min_redeem_points" class="form-control" 
                               value="{{ old('min_redeem_points', $minRedeemPoints) }}" min="1" step="1">
                    </div>

                    <button type="submit" class="btn btn-primary" style="padding:10px 24px; font-size:0.95rem;">
                        <i class="fa-solid fa-floppy-disk"></i> Save Point Conversion Settings
                    </button>
                </form>
            </div>
        </div>

        {{-- Live Simulator Test Box --}}
        <div class="card-panel">
            <div class="card-panel-header">
                <span class="card-panel-title"><i class="fa-solid fa-wand-magic-sparkles"></i> Test Simulator (টেস্ট ক্যালকুলেটর)</span>
            </div>
            <div class="card-panel-body">
                <p style="font-size:0.82rem; color:#64748b; margin-bottom:12px;">
                    Enter any point balance to preview how much discount the customer will receive in real-time:
                </p>
                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="flex:1;">
                        <input type="number" id="simPoints" class="form-control" value="100" min="1" 
                               placeholder="Test Points" oninput="calculateSimulator()"
                               style="font-size:1rem; font-weight:800;">
                    </div>
                    <div style="font-size:1.2rem; color:#94a3b8;"><i class="fa-solid fa-arrow-right"></i></div>
                    <div style="flex:1; background:#f1f5f9; border:1px solid #cbd5e1; border-radius:6px; padding:9px 14px; font-weight:800; font-size:1rem; color:#0f172a;">
                        Discount: <span id="simDiscount" style="color:#16a34a;">৳ 100</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Sidebar: Statistics & Info --}}
    <div>
        <div class="card-panel">
            <div class="card-panel-header">
                <span class="card-panel-title"><i class="fa-solid fa-chart-simple"></i> Loyalty Overview</span>
            </div>
            <div class="card-panel-body" style="display:flex; flex-direction:column; gap:14px;">
                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px 14px;">
                    <div style="font-size:0.75rem; color:#64748b; font-weight:700; text-transform:uppercase;">Circulated in Wallets</div>
                    <div style="font-size:1.35rem; font-weight:900; color:#b45309; margin-top:2px;">
                        <i class="fa-solid fa-coins"></i> {{ number_format($totalCustomerPoints) }} Pts
                    </div>
                    <div style="font-size:0.75rem; color:#78716c;">Total points currently held by all customers</div>
                </div>

                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px 14px;">
                    <div style="font-size:0.75rem; color:#64748b; font-weight:700; text-transform:uppercase;">Total Redeemed</div>
                    <div style="font-size:1.35rem; font-weight:900; color:#0369a1; margin-top:2px;">
                        <i class="fa-solid fa-receipt"></i> {{ number_format($totalRedeemedPoints) }} Pts
                    </div>
                    <div style="font-size:0.75rem; color:#78716c;">Points spent on completed orders</div>
                </div>

                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px 14px;">
                    <div style="font-size:0.75rem; color:#64748b; font-weight:700; text-transform:uppercase;">Total Discount Given</div>
                    <div style="font-size:1.35rem; font-weight:900; color:#15803d; margin-top:2px;">
                        ৳ {{ number_format($totalDiscountGiven) }}
                    </div>
                    <div style="font-size:0.75rem; color:#78716c;">Total money saved by customers using points</div>
                </div>
            </div>
        </div>

        <div class="card-panel">
            <div class="card-panel-header">
                <span class="card-panel-title"><i class="fa-solid fa-lightbulb" style="color:#eab308;"></i> How Point Earning Works</span>
            </div>
            <div class="card-panel-body" style="font-size:0.84rem; color:#475569; line-height:1.6;">
                <p style="margin-bottom:8px;">
                    <strong>Product-Based Points:</strong> Each product can have its own reward points defined in the Product Create/Edit form.
                </p>
                <p style="margin-bottom:0;">
                    When a customer places an order, the system calculates the sum of all product points in the cart and automatically credits them to the customer's wallet upon order confirmation!
                </p>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    function calculateSimulator() {
        const ratePoints = parseFloat(document.getElementById('ratePoints').value) || 1;
        const rateTaka = parseFloat(document.getElementById('rateTaka').value) || 1;
        const simPoints = parseFloat(document.getElementById('simPoints').value) || 0;

        const ratio = ratePoints > 0 ? (rateTaka / ratePoints) : 1;
        const discount = Math.floor(simPoints * ratio);

        document.getElementById('displayFormula').innerHTML = '<strong>' + ratePoints + ' Points = ৳ ' + rateTaka.toFixed(2) + ' Discount</strong>';
        document.getElementById('ratePerPoint').innerText = '(1 Pt = ৳' + ratio.toFixed(2) + ')';
        document.getElementById('simDiscount').innerText = '৳ ' + discount.toLocaleString();
    }

    document.addEventListener('DOMContentLoaded', calculateSimulator);
</script>
@endpush
@endsection
