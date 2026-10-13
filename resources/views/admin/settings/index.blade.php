@extends('layouts.admin')
@section('title', 'Website Settings | Italian Pizza Admin')
@section('page-title', 'Website Settings')

@section('admin-content')
<div class="form-row" style="align-items:flex-start;">

    {{-- Logo Management --}}
    <div>
        <div class="card-panel">
            <div class="card-panel-header">
                <span class="card-panel-title"><i class="fa-solid fa-image"></i> Website Logo</span>
            </div>
            <div class="card-panel-body">
                <div style="background:#f8fafc;border:1px dashed #cbd5e1;border-radius:10px;padding:24px;text-align:center;margin-bottom:16px;">
                    @if($logo)
                        <img src="{{ asset('uploads/' . $logo) }}" alt="Current Logo" style="max-height:80px;max-width:100%;object-fit:contain;">
                        <div style="font-size:0.72rem;color:#64748b;margin-top:8px;">{{ $logo }}</div>
                    @else
                        <div style="color:#94a3b8;padding:20px 0;">
                            <i class="fa-solid fa-image" style="font-size:1.6rem;"></i>
                            <div style="font-size:0.8rem;margin-top:6px;">No logo uploaded — default logo is shown</div>
                        </div>
                    @endif
                </div>

                <form action="{{ route('admin.settings.logo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Upload Logo</label>
                        <input type="file" name="logo" class="form-control" accept="image/*" required>
                        <div class="form-hint">PNG / JPG / WEBP / SVG. Transparent PNG recommended. Max 2MB.</div>
                        @error('logo')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-upload"></i> Save Logo</button>
                </form>

                @if($logo)
                <form action="{{ route('admin.settings.logo.remove') }}" method="POST" style="margin-top:10px;" onsubmit="return confirm('Remove current logo and restore default?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Remove Logo</button>
                </form>
                @endif
            </div>
        </div>
    </div>

    {{-- Favicon Management --}}
    <div>
        <div class="card-panel">
            <div class="card-panel-header">
                <span class="card-panel-title"><i class="fa-solid fa-star"></i> Favicon</span>
            </div>
            <div class="card-panel-body">
                <div style="background:#f8fafc;border:1px dashed #cbd5e1;border-radius:10px;padding:24px;text-align:center;margin-bottom:16px;">
                    @if($favicon)
                        <img src="{{ asset('uploads/' . $favicon) }}" alt="Current Favicon" style="height:64px;width:64px;object-fit:contain;">
                        <div style="font-size:0.72rem;color:#64748b;margin-top:8px;">{{ $favicon }}</div>
                    @else
                        <div style="color:#94a3b8;padding:20px 0;">
                            <i class="fa-solid fa-star" style="font-size:1.6rem;"></i>
                            <div style="font-size:0.8rem;margin-top:6px;">No favicon uploaded — browser default is shown</div>
                        </div>
                    @endif
                </div>

                <form action="{{ route('admin.settings.favicon') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Upload Favicon</label>
                        <input type="file" name="favicon" class="form-control" accept=".ico,image/*" required>
                        <div class="form-hint">ICO / PNG / SVG, square (e.g. 32×32 or 64×64). Max 1MB.</div>
                        @error('favicon')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-upload"></i> Save Favicon</button>
                </form>

                @if($favicon)
                <form action="{{ route('admin.settings.favicon.remove') }}" method="POST" style="margin-top:10px;" onsubmit="return confirm('Remove current favicon?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Remove Favicon</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card-panel">
    <div class="card-panel-body" style="font-size:0.8rem;color:#64748b;">
        <i class="fa-solid fa-circle-info" style="color:#3b82f6;"></i>
        The logo appears in the website header and the favicon appears in the browser tab. Both are shown on all frontend pages.
    </div>
</div>
@endsection
