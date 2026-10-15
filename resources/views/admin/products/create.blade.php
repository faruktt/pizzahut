@extends('layouts.admin')
@section('title', 'Add Product | Italian Pizza Admin')
@section('page-title', 'Add Product')

@section('admin-content')
<div style="max-width:960px;">
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary" style="margin-bottom:20px;">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Basic Information --}}
        <div class="card-panel" style="margin-bottom:20px;">
            <div class="card-panel-header">
                <span class="card-panel-title"><i class="fa-solid fa-circle-info"></i> Basic Information</span>
            </div>
            <div class="card-panel-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Product Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. Pepperoni Feast">
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category *</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">— Select Category —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Base Price (৳) *</label>
                        <input type="number" name="base_price" step="0.01" min="0" class="form-control" value="{{ old('base_price', 0) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fa-solid fa-coins" style="color:#f59e0b;"></i> Reward Points Earned</label>
                        <input type="number" name="reward_points" min="0" class="form-control" value="{{ old('reward_points', 0) }}" placeholder="e.g. 15">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Default Product Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*" onchange="previewMainImg(this)">
                        <img id="mainImgPreview" src="" style="display:none;margin-top:8px;height:70px;border-radius:6px;object-fit:cover;">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Brief description...">{{ old('description') }}</textarea>
                </div>

                <div style="display:flex;gap:20px;flex-wrap:wrap;padding-top:4px;">
                    <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:0.85rem;font-weight:600;">
                        <input type="checkbox" name="is_bestseller" {{ old('is_bestseller') ? 'checked' : '' }}> <i class="fa-solid fa-fire" style="color:#f59e0b;"></i> Bestseller
                    </label>
                    <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:0.85rem;font-weight:600;">
                        <input type="checkbox" name="is_new" {{ old('is_new') ? 'checked' : '' }}> <i class="fa-solid fa-star" style="color:#3b82f6;"></i> New Item
                    </label>
                </div>
            </div>
        </div>

        {{-- Product Variations Section --}}
        <div class="card-panel" style="margin-bottom:20px;">
            <div class="card-panel-header" style="justify-content:space-between;">
                <div>
                    <span class="card-panel-title"><i class="fa-solid fa-layer-group"></i> Product Variations (Sizes & Crusts)</span>
                    <div style="font-size:0.78rem;color:#64748b;margin-top:4px;">
                        Select size(s), then choose crusts with specific price & image for each variation.
                    </div>
                </div>
                <div style="display:flex;gap:8px;">
                    <a href="{{ route('admin.sizes.index') }}" target="_blank" class="btn btn-secondary btn-sm" title="Add new sizes">
                        <i class="fa-solid fa-ruler-horizontal"></i> Sizes
                    </a>
                    <a href="{{ route('admin.crusts.index') }}" target="_blank" class="btn btn-secondary btn-sm" title="Add new crusts">
                        <i class="fa-solid fa-circle-half-stroke"></i> Crusts
                    </a>
                </div>
            </div>

            <div class="card-panel-body">
                {{-- 1. Step: Select Sizes --}}
                <div style="margin-bottom:20px;">
                    <label class="form-label" style="font-size:0.9rem;font-weight:700;margin-bottom:10px;display:block;">
                        Step 1: Choose Available Sizes
                    </label>

                    @if($sizes->isNotEmpty())
                    <div style="display:flex;flex-wrap:wrap;gap:12px;">
                        @foreach($sizes as $size)
                        <label style="display:flex;align-items:center;gap:8px;padding:10px 16px;border:2px solid #e2e8f0;border-radius:10px;cursor:pointer;background:#fff;transition:all 0.2s;font-size:0.9rem;font-weight:700;" class="size-pill" id="pill_size_{{ $size->id }}">
                            <input type="checkbox" name="size_selected_{{ $size->id }}" id="chk_size_{{ $size->id }}" value="1" onchange="toggleSizeBox({{ $size->id }})" style="width:18px;height:18px;accent-color:var(--admin-primary);">
                            <span>{{ $size->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    @else
                    <div style="padding:14px;background:#f8fafc;border-radius:8px;color:#64748b;font-size:0.85rem;">
                        <i class="fa-solid fa-circle-exclamation"></i> No sizes created yet. <a href="{{ route('admin.sizes.index') }}" target="_blank">Create sizes here →</a>
                    </div>
                    @endif
                </div>

                {{-- 2. Step: Crust, Price & Image per Size --}}
                <div id="variationsBoxesWrap">
                    <label class="form-label" style="font-size:0.9rem;font-weight:700;margin-bottom:10px;display:block;">
                        Step 2: Configure Crust, Price & Image for Selected Sizes
                    </label>

                    <div id="noSizeSelectedMsg" style="padding:24px;text-align:center;background:#f8fafc;border:2px dashed #cbd5e1;border-radius:12px;color:#64748b;font-size:0.88rem;">
                        <i class="fa-solid fa-hand-pointer" style="font-size:1.5rem;display:block;margin-bottom:8px;color:#94a3b8;"></i>
                        Please select one or more sizes above to configure crust variations, prices, and images.
                    </div>

                    @foreach($sizes as $size)
                    <div class="size-variation-card" id="sizeCard_{{ $size->id }}" style="display:none;background:#fcfcfd;border:1px solid #e2e8f0;border-radius:12px;padding:18px;margin-bottom:18px;box-shadow:0 1px 3px rgba(0,0,0,0.02);">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid #e2e8f0;">
                            <div style="display:flex;align-items:center;gap:8px;">
                                <span style="background:var(--admin-primary);color:#fff;width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.75rem;">
                                    <i class="fa-solid fa-ruler"></i>
                                </span>
                                <h4 style="margin:0;font-size:1rem;font-weight:800;color:#1e293b;">
                                    Size: {{ $size->name }}
                                </h4>
                            </div>
                            <span style="font-size:0.78rem;color:#64748b;background:#e2e8f0;padding:3px 10px;border-radius:12px;font-weight:600;">
                                Select crusts below
                            </span>
                        </div>

                        @if($crusts->isNotEmpty())
                        <div style="display:flex;flex-direction:column;gap:12px;">
                            @foreach($crusts as $crust)
                            <div class="variation-row" id="varRow_{{ $size->id }}_{{ $crust->id }}" style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:12px 16px;display:flex;align-items:center;gap:16px;flex-wrap:wrap;transition:all 0.2s;">
                                {{-- Checkbox to enable this crust --}}
                                <div style="display:flex;align-items:center;gap:10px;min-width:180px;">
                                    <input type="checkbox" 
                                           name="variations[{{ $size->id }}][{{ $crust->id }}][enabled]" 
                                           id="chk_var_{{ $size->id }}_{{ $crust->id }}" 
                                           value="1" 
                                           onchange="toggleVarRow({{ $size->id }}, {{ $crust->id }})"
                                           style="width:18px;height:18px;accent-color:var(--admin-primary);">
                                    <label for="chk_var_{{ $size->id }}_{{ $crust->id }}" style="cursor:pointer;font-weight:700;font-size:0.88rem;color:#1e293b;margin:0;">
                                        <i class="fa-solid fa-circle-half-stroke" style="color:var(--admin-primary);font-size:0.8rem;margin-right:4px;"></i>
                                        {{ $crust->name }}
                                    </label>
                                </div>

                                {{-- Price Input --}}
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <label style="font-size:0.78rem;font-weight:600;color:#64748b;margin:0;">Price (৳):</label>
                                    <input type="number" 
                                           step="0.01" 
                                           min="0" 
                                           name="variations[{{ $size->id }}][{{ $crust->id }}][price]" 
                                           id="price_var_{{ $size->id }}_{{ $crust->id }}" 
                                           placeholder="e.g. 550" 
                                           class="form-control" 
                                           style="width:120px;padding:6px 10px;font-size:0.85rem;" 
                                           disabled>
                                </div>

                                {{-- Image Input --}}
                                <div style="display:flex;align-items:center;gap:8px;flex:1;min-width:240px;">
                                    <label style="font-size:0.78rem;font-weight:600;color:#64748b;margin:0;">Image:</label>
                                    <input type="file" 
                                           name="variation_image_{{ $size->id }}_{{ $crust->id }}" 
                                           id="img_var_{{ $size->id }}_{{ $crust->id }}" 
                                           accept="image/*" 
                                           class="form-control" 
                                           style="padding:4px 8px;font-size:0.78rem;" 
                                           onchange="previewVarImg(this, 'preview_{{ $size->id }}_{{ $crust->id }}')" 
                                           disabled>
                                    <img id="preview_{{ $size->id }}_{{ $crust->id }}" 
                                         src="" 
                                         style="display:none;width:40px;height:40px;border-radius:6px;object-fit:cover;border:1px solid #e2e8f0;">
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div style="color:#64748b;font-size:0.85rem;">
                            No crusts available. <a href="{{ route('admin.crusts.index') }}" target="_blank">Add crusts first →</a>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="padding:12px 28px;font-size:0.95rem;">
            <i class="fa-solid fa-save"></i> Save Product & Variations
        </button>
    </form>
</div>

@push('admin-scripts')
<script>
    function previewMainImg(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.getElementById('mainImgPreview');
                img.src = e.target.result;
                img.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewVarImg(input, previewId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.getElementById(previewId);
                img.src = e.target.result;
                img.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function toggleSizeBox(sizeId) {
        const chk = document.getElementById('chk_size_' + sizeId);
        const card = document.getElementById('sizeCard_' + sizeId);
        const pill = document.getElementById('pill_size_' + sizeId);

        if (chk.checked) {
            card.style.display = 'block';
            pill.style.borderColor = 'var(--admin-primary)';
            pill.style.background = '#fef2f2';
        } else {
            card.style.display = 'none';
            pill.style.borderColor = '#e2e8f0';
            pill.style.background = '#fff';
            // Disable all crusts in this size
            const crustCheckboxes = card.querySelectorAll('input[type="checkbox"]');
            crustCheckboxes.forEach(c => {
                c.checked = false;
                const match = c.id.match(/chk_var_(\d+)_(\d+)/);
                if (match) {
                    toggleVarRow(match[1], match[2]);
                }
            });
        }
        checkAnySizeActive();
    }

    function toggleVarRow(sizeId, crustId) {
        const chk = document.getElementById('chk_var_' + sizeId + '_' + crustId);
        const priceInput = document.getElementById('price_var_' + sizeId + '_' + crustId);
        const imgInput = document.getElementById('img_var_' + sizeId + '_' + crustId);
        const row = document.getElementById('varRow_' + sizeId + '_' + crustId);

        if (chk.checked) {
            priceInput.disabled = false;
            priceInput.required = true;
            imgInput.disabled = false;
            row.style.borderColor = 'var(--admin-primary)';
            row.style.background = '#fffafa';
        } else {
            priceInput.disabled = true;
            priceInput.required = false;
            imgInput.disabled = true;
            row.style.borderColor = '#e2e8f0';
            row.style.background = '#fff';
        }
    }

    function checkAnySizeActive() {
        const anyChecked = document.querySelectorAll('input[id^="chk_size_"]:checked').length > 0;
        const msg = document.getElementById('noSizeSelectedMsg');
        if (msg) {
            msg.style.display = anyChecked ? 'none' : 'block';
        }
    }
</script>
@endpush
@endsection
