@extends('layouts.admin')

@section('page-title', 'Edit Order Status')

@section('admin-content')
<div style="max-width: 680px; margin: 0 auto;">
    <div class="card-panel">
        <div class="card-panel-header" style="display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid var(--border-light);">
            <div>
                <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0;">Edit Order Status: {{ $status->name }}</h3>
            </div>
            <a href="{{ route('admin.order-statuses.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Back to List
            </a>
        </div>

        <div style="padding: 24px;">
            <form action="{{ route('admin.order-statuses.update', $status->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Live Badge Preview Card -->
                <div style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 18px; margin-bottom: 24px; text-align: center;">
                    <span style="font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700; display: block; margin-bottom: 8px;">Live Badge Preview</span>
                    <span id="previewBadge" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 16px; border-radius: 999px; font-size: 0.9rem; font-weight: 800; color: {{ $status->color }}; background-color: {{ $status->bg_color }}; border: 1px solid rgba(0,0,0,0.06); transition: all 0.2s;">
                        <span id="previewDot" style="width: 8px; height: 8px; border-radius: 50%; background-color: {{ $status->color }};"></span>
                        <span id="previewText">{{ $status->name }}</span>
                    </span>
                </div>

                <div class="form-group">
                    <label class="form-label">Status Name *</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="statusNameInput" 
                        class="form-control" 
                        value="{{ old('name', $status->name) }}" 
                        required 
                        oninput="updatePreview()"
                    >
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Text / Dot Color *</label>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input 
                                type="color" 
                                name="color" 
                                id="textColorInput" 
                                value="{{ old('color', $status->color) }}" 
                                style="width: 44px; height: 40px; padding: 2px; border: 1px solid #cbd5e1; border-radius: 8px; cursor: pointer;"
                                oninput="updatePreview()"
                            >
                            <input 
                                type="text" 
                                id="textColorHex" 
                                class="form-control" 
                                value="{{ old('color', $status->color) }}" 
                                oninput="syncColor('textColorHex', 'textColorInput')"
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Background Color *</label>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input 
                                type="color" 
                                name="bg_color" 
                                id="bgColorInput" 
                                value="{{ old('bg_color', $status->bg_color) }}" 
                                style="width: 44px; height: 40px; padding: 2px; border: 1px solid #cbd5e1; border-radius: 8px; cursor: pointer;"
                                oninput="updatePreview()"
                            >
                            <input 
                                type="text" 
                                id="bgColorHex" 
                                class="form-control" 
                                value="{{ old('bg_color', $status->bg_color) }}" 
                                oninput="syncColor('bgColorHex', 'bgColorInput')"
                            >
                        </div>
                    </div>
                </div>

                <!-- Color Presets -->
                <div class="form-group">
                    <label class="form-label" style="font-size: 0.76rem; color: #64748b;">Quick Color Presets:</label>
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <button type="button" class="btn btn-secondary btn-sm" onclick="applyPreset('#15803d', '#dcfce7')">Green (Success)</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="applyPreset('#b45309', '#fef3c7')">Amber (Pending)</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="applyPreset('#6d28d9', '#ede9fe')">Purple (Kitchen)</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="applyPreset('#0369a1', '#e0f2fe')">Blue (Ready)</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="applyPreset('#c2410c', '#ffedd5')">Orange (Delivery)</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="applyPreset('#b91c1c', '#fee2e2')">Red (Cancelled)</button>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Display Sort Order</label>
                    <input 
                        type="number" 
                        name="sort_order" 
                        class="form-control" 
                        placeholder="0" 
                        value="{{ old('sort_order', $status->sort_order) }}"
                    >
                </div>

                <div class="form-group" style="margin-top: 10px;">
                    <label style="display: inline-flex; align-items: center; gap: 8px; font-weight: 700; cursor: pointer; color: #1e293b; font-size: 0.88rem;">
                        <input type="checkbox" name="is_active" value="1" {{ $status->is_active ? 'checked' : '' }} style="accent-color: var(--admin-primary); width: 18px; height: 18px;">
                        <span>Active</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" style="padding: 10px 24px; margin-top: 12px;">
                    <i class="fa-solid fa-floppy-disk"></i> Update Order Status
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function updatePreview() {
        const name = document.getElementById('statusNameInput').value.trim() || 'Status';
        const color = document.getElementById('textColorInput').value;
        const bgColor = document.getElementById('bgColorInput').value;

        document.getElementById('textColorHex').value = color;
        document.getElementById('bgColorHex').value = bgColor;

        const badge = document.getElementById('previewBadge');
        const dot = document.getElementById('previewDot');
        const text = document.getElementById('previewText');

        text.innerText = name;
        badge.style.color = color;
        badge.style.backgroundColor = bgColor;
        dot.style.backgroundColor = color;
    }

    function syncColor(textId, colorPickerId) {
        const hex = document.getElementById(textId).value;
        if (/^#[0-9A-F]{6}$/i.test(hex)) {
            document.getElementById(colorPickerId).value = hex;
            updatePreview();
        }
    }

    function applyPreset(color, bgColor) {
        document.getElementById('textColorInput').value = color;
        document.getElementById('textColorHex').value = color;
        document.getElementById('bgColorInput').value = bgColor;
        document.getElementById('bgColorHex').value = bgColor;
        updatePreview();
    }
</script>
@endsection
