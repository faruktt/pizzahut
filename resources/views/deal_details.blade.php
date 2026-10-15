@extends('layouts.app')

@section('title', $deal->name . ' Combo Deal | Italian Pizza Hut')

@push('styles')
<style>
    .deal-page-wrap {
        padding: 32px 0 80px 0;
    }

    /* Top Hero Deal Info Banner */
    .deal-hero-header {
        background: linear-gradient(135deg, #c8102e 0%, #e62843 100%);
        border-radius: 16px;
        color: #fff;
        padding: 36px 40px;
        margin-bottom: 36px;
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 24px;
        align-items: center;
        box-shadow: 0 10px 30px rgba(200, 16, 46, 0.2);
    }

    .deal-badge-pill {
        display: inline-block;
        background: #fff;
        color: var(--brand-red);
        font-weight: 800;
        font-size: 0.8rem;
        padding: 4px 12px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
    }

    .deal-hero-title {
        font-size: 2.2rem;
        font-weight: 900;
        line-height: 1.15;
        margin-bottom: 8px;
    }

    .deal-hero-desc {
        font-size: 0.95rem;
        color: #ffe6e8;
        max-width: 600px;
        line-height: 1.4;
    }

    .deal-price-badge-box {
        background: rgba(255, 255, 255, 0.15);
        border: 2px solid rgba(255, 255, 255, 0.35);
        border-radius: 14px;
        padding: 20px 28px;
        text-align: center;
        backdrop-filter: blur(10px);
    }

    .deal-price-amount {
        font-size: 3rem;
        font-weight: 900;
        line-height: 1;
        display: flex;
        align-items: baseline;
        justify-content: center;
    }

    /* Slots Selection */
    .combo-slots-container {
        display: flex;
        flex-direction: column;
        gap: 36px;
        margin-bottom: 40px;
    }

    .slot-section-card {
        background: #ffffff;
        border: 1px solid #eaeaea;
        border-radius: 14px;
        padding: 24px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }

    .slot-title-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f0f0f0;
    }

    .slot-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #222;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .slot-number-badge {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--brand-red);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        font-weight: 800;
    }

    .slot-selected-preview {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--brand-red);
    }

    /* Product Cards in Slot Grid */
    .slot-products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
        gap: 16px;
    }

    .slot-prod-card {
        border: 2px solid #e8e8e8;
        border-radius: 10px;
        padding: 12px;
        background: #ffffff;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.2s ease;
        position: relative;
    }

    .slot-prod-card:hover {
        border-color: #cbd5e1;
        transform: translateY(-2px);
    }

    .slot-prod-card.active {
        border-color: var(--brand-red);
        background: var(--brand-red-light);
        box-shadow: 0 4px 14px rgba(200, 16, 46, 0.15);
    }

    .slot-prod-thumb {
        height: 110px;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 10px;
        background: #f7f7f7;
    }

    .slot-prod-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .slot-prod-name {
        font-size: 0.88rem;
        font-weight: 800;
        color: #222;
        line-height: 1.3;
        margin-bottom: 4px;
    }

    .slot-check-icon {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #e2e8f0;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 800;
    }

    .slot-prod-card.active .slot-check-icon {
        background: var(--brand-red);
    }

    /* Bottom Action Sticky Bar */
    .deal-action-box {
        background: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 14px;
        padding: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        flex-wrap: wrap;
    }

    .deal-summary-stat {
        display: flex;
        flex-direction: column;
    }

    .btn-add-combo {
        background: var(--brand-red);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 14px 36px;
        font-size: 1.05rem;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 16px rgba(200, 16, 46, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-add-combo:hover {
        background: var(--brand-red-dark);
        transform: translateY(-2px);
    }

    .btn-add-combo:disabled {
        background: #cbd5e1;
        cursor: not-allowed;
        box-shadow: none;
        transform: none;
    }

    @media (max-width: 900px) {
        .deal-hero-header {
            grid-template-columns: 1fr;
        }
        .deal-action-box {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>
@endpush

@section('content')
<div class="container deal-page-wrap">

    <!-- 1. Top Hero Deal Header -->
    <div class="deal-hero-header">
        <div>
            <span class="deal-badge-pill"><i class="fa-solid fa-fire"></i> Top Combo Deal</span>
            <h1 class="deal-hero-title">{{ $deal->name }}</h1>
            <p class="deal-hero-desc">{{ $deal->description }}</p>
            <div style="margin-top: 12px; font-size: 0.85rem; font-weight: 700; color: #fff; display:flex; align-items:center; gap:6px;">
                <i class="fa-solid fa-circle-check"></i> <span>Select your {{ $comboSlotsCount }} favorite pizzas / items below to customize your combo!</span>
            </div>
        </div>

        <div class="deal-price-badge-box">
            <div style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; color: #ffe6e8;">Combo Price</div>
            <div class="deal-price-amount">
                <span style="font-size: 1.8rem; margin-right: 4px;">৳</span>
                <span>{{ number_format($deal->base_price) }}</span>
            </div>
            <div style="font-size: 0.72rem; color: #ffe6e8; margin-top: 4px;">All inclusive</div>
        </div>
    </div>

    <!-- 2. Form for Choosing Combo Items -->
    <form action="{{ route('deal.add', $deal->id) }}" method="POST" id="dealComboForm">
        @csrf

        <div class="combo-slots-container">
            @for($slot = 1; $slot <= $comboSlotsCount; $slot++)
                <div class="slot-section-card" id="slot-card-{{ $slot }}">
                    <div class="slot-title-bar">
                        <div class="slot-title">
                            <span class="slot-number-badge">{{ $slot }}</span>
                            <span>Choose Item #{{ $slot }}</span>
                        </div>
                        <div class="slot-selected-preview" id="slot-preview-{{ $slot }}">
                            Pending Selection...
                        </div>
                    </div>

                    <!-- Products Grid for this Slot -->
                    <div class="slot-products-grid">
                        @foreach($eligibleProducts as $prod)
                            <div class="slot-prod-card" 
                                 id="card-slot-{{ $slot }}-prod-{{ $prod->id }}" 
                                 onclick="chooseSlotProduct({{ $slot }}, {{ $prod->id }}, '{{ addslashes($prod->name) }}')">
                                <span class="slot-check-icon"><i class="fa-solid fa-check"></i></span>
                                <div class="slot-prod-thumb">
                                    <img src="{{ $prod->image ? asset('uploads/' . $prod->image) : asset('images/pizza-hero.jpg') }}" alt="{{ $prod->name }}">
                                </div>
                                <div>
                                    <h4 class="slot-prod-name">{{ $prod->name }}</h4>
                                    <div style="font-size: 0.75rem; color: #64748b;">{{ $prod->category->name ?? 'Pizza' }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Hidden input to store chosen product for this slot -->
                    <input type="hidden" name="combo_items[]" id="slot-input-{{ $slot }}" required>
                </div>
            @endfor
        </div>

        <!-- 3. Special Instructions -->
        <div style="background: #ffffff; border: 1px solid #eaeaea; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
            <label style="display:block; font-size:0.85rem; font-weight:700; color:#333; margin-bottom:6px;">
                Special Cooking Instructions for this Combo (Optional)
            </label>
            <input type="text" name="special_instructions" class="form-control" placeholder="e.g. Well done crust, extra oregano sprinkle" style="width:100%; padding:10px 14px; border:1px solid #ddd; border-radius:8px; font-size:0.9rem;">
        </div>

        <!-- 4. Bottom Action Bar -->
        <div class="deal-action-box">
            <div class="deal-summary-stat">
                <div style="font-size: 1.15rem; font-weight: 800; color: #222;">
                    {{ $deal->name }}
                </div>
                <div style="font-size: 0.85rem; color: #64748b;" id="selectionProgressText">
                    0 of {{ $comboSlotsCount }} items selected
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 20px;">
                <div style="text-align: right;">
                    <div style="font-size: 0.75rem; color: #777; text-transform: uppercase;">Total Combo Price</div>
                    <div style="font-size: 1.6rem; font-weight: 900; color: var(--brand-red);">
                        ৳ {{ number_format($deal->base_price) }}
                    </div>
                </div>

                <button type="submit" class="btn-add-combo" id="btnAddCombo" disabled>
                    <span>Add Combo to Cart</span> <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
    const totalSlots = {{ $comboSlotsCount }};
    const selectedSlots = {};

    function chooseSlotProduct(slotNum, prodId, prodName) {
        // Remove active class from other products in this slot
        document.querySelectorAll(`#slot-card-${slotNum} .slot-prod-card`).forEach(card => {
            card.classList.remove('active');
        });

        // Set active on chosen card
        const card = document.getElementById(`card-slot-${slotNum}-prod-${prodId}`);
        if (card) {
            card.classList.add('active');
        }

        // Store value in hidden input
        document.getElementById(`slot-input-${slotNum}`).value = prodId;
        selectedSlots[slotNum] = prodName;

        // Update preview text
        document.getElementById(`slot-preview-${slotNum}`).innerHTML = `✓ ${prodName}`;
        document.getElementById(`slot-preview-${slotNum}`).style.color = '#10b981';

        checkAllSlotsFilled();
    }

    function checkAllSlotsFilled() {
        const count = Object.keys(selectedSlots).length;
        document.getElementById('selectionProgressText').innerText = `${count} of ${totalSlots} items selected`;

        const btn = document.getElementById('btnAddCombo');
        if (count >= totalSlots) {
            btn.disabled = false;
            document.getElementById('selectionProgressText').style.color = '#10b981';
            document.getElementById('selectionProgressText').innerText = `✓ All ${totalSlots} items selected! Ready to add.`;
        } else {
            btn.disabled = true;
            document.getElementById('selectionProgressText').style.color = '#64748b';
        }
    }

    // Auto select first items by default so user has a quick default combination!
    document.addEventListener('DOMContentLoaded', () => {
        @foreach($eligibleProducts->take($comboSlotsCount) as $index => $firstProd)
            chooseSlotProduct({{ $index + 1 }}, {{ $firstProd->id }}, '{{ addslashes($firstProd->name) }}');
        @endforeach

        const isCustomerLoggedIn = {{ Auth::guard('web')->check() ? 'true' : 'false' }};
        const form = document.getElementById('dealComboForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                if (!isCustomerLoggedIn) {
                    e.preventDefault();
                    window.location.href = "{{ route('login') }}";
                }
            });
        }
    });
</script>
@endpush
