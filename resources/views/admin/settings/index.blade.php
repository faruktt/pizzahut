@extends('layouts.admin')
@section('title', 'Website & Store Settings | Italian Pizza Admin')
@section('page-title', 'Website & Store Settings')

@section('admin-content')
<div style="display:flex;flex-direction:column;gap:24px;">

    {{-- Store Address, Contact & Google Map --}}
    <div class="card-panel">
        <div class="card-panel-header" style="background:#f8fafc;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:36px;height:36px;border-radius:8px;background:rgba(200,16,46,0.1);color:var(--admin-primary);display:flex;align-items:center;justify-content:center;font-size:1.1rem;">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <div>
                    <span class="card-panel-title">Restaurant Address, Contact & Google Map Location</span>
                    <div style="font-size:0.75rem;color:#64748b;">This address displays under the logo in header/footer, and the Google Map is shown above the footer</div>
                </div>
            </div>
        </div>
        <div class="card-panel-body">
            <form action="{{ route('admin.settings.general') }}" method="POST">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="site_address">Restaurant Address</label>
                        <input type="text" name="site_address" id="site_address" class="form-control" value="{{ old('site_address', $siteAddress) }}" placeholder="e.g. House 14, Road 11, Block D, Gulshan-2, Dhaka">
                        @error('site_address') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="site_phone">Contact Phone Number</label>
                        <input type="text" name="site_phone" id="site_phone" class="form-control" value="{{ old('site_phone', $sitePhone) }}" placeholder="e.g. +880 1711-223344">
                        @error('site_phone') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="site_email">Contact Email Address</label>
                        <input type="email" name="site_email" id="site_email" class="form-control" value="{{ old('site_email', $siteEmail) }}" placeholder="e.g. contact@italianpizza.com">
                        @error('site_email') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="opening_hours">Opening Hours</label>
                        <input type="text" name="opening_hours" id="opening_hours" class="form-control" value="{{ old('opening_hours', $openingHours) }}" placeholder="e.g. Daily: 11:00 AM – 11:30 PM">
                        @error('opening_hours') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="google_map_embed">Google Map Embed Code or URL</label>
                    <textarea name="google_map_embed" id="google_map_embed" class="form-control" rows="3" placeholder='Paste your Google Maps iframe embed code or map URL here (e.g. &lt;iframe src="https://www.google.com/maps/embed?..." ...&gt;&lt;/iframe&gt;)'>{{ old('google_map_embed', $googleMapEmbed) }}</textarea>
                    @error('google_map_embed') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                @if($googleMapEmbed)
                <div style="margin-top:16px;margin-bottom:20px;">
                    <div style="font-size:0.8rem;font-weight:700;color:#334155;margin-bottom:8px;">
                        <i class="fa-solid fa-map-location-dot" style="color:var(--admin-primary);"></i> Current Live Map Preview:
                    </div>
                    <div style="border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;height:240px;position:relative;background:#f1f5f9;">
                        @if(Str::startsWith(trim($googleMapEmbed), '<iframe'))
                            {!! preg_replace('/width="\d+"/', 'width="100%"', preg_replace('/height="\d+"/', 'height="100%"', $googleMapEmbed)) !!}
                        @elseif(Str::startsWith(trim($googleMapEmbed), 'http'))
                            <iframe src="{{ trim($googleMapEmbed) }}" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        @else
                            <iframe src="https://maps.google.com/maps?q={{ urlencode($siteAddress ?: 'Italian Pizza Dhaka') }}&t=&z=15&ie=UTF8&iwloc=&output=embed" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        @endif
                    </div>
                </div>
                @endif

                <div style="display:flex;justify-content:flex-end;">
                    <button type="submit" class="btn btn-primary" style="padding:10px 24px;">
                        <i class="fa-solid fa-floppy-disk"></i> Save Address & Map Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Logo & Favicon Management --}}
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
                            <label class="form-label">Upload New Logo</label>
                            <input type="file" name="logo" class="form-control" accept="image/*" required>
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
                    <span class="card-panel-title"><i class="fa-solid fa-star"></i> Browser Favicon</span>
                </div>
                <div class="card-panel-body">
                    <div style="background:#f8fafc;border:1px dashed #cbd5e1;border-radius:10px;padding:24px;text-align:center;margin-bottom:16px;">
                        @if($favicon)
                            <img src="{{ asset('uploads/' . $favicon) }}" alt="Current Favicon" style="height:64px;width:64px;object-fit:contain;">
                            <div style="font-size:0.72rem;color:#64748b;margin-top:8px;">{{ $favicon }}</div>
                        @else
                            <div style="color:#94a3b8;padding:20px 0;">
                                <i class="fa-solid fa-star" style="font-size:1.6rem;"></i>
                                <div style="font-size:0.8rem;margin-top:6px;">No favicon uploaded — default is shown</div>
                            </div>
                        @endif
                    </div>

                    <form action="{{ route('admin.settings.favicon') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Upload New Favicon</label>
                            <input type="file" name="favicon" class="form-control" accept=".ico,image/*" required>
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

    {{-- Loyalty Points Shortcut --}}
    <div class="card-panel" style="background:#fffbeb; border:1px solid #fde68a;">
        <div class="card-panel-body" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px;">
            <div>
                <h4 style="font-size:1rem; font-weight:800; color:#b45309; margin-bottom:4px;">
                    <i class="fa-solid fa-coins" style="color:#f59e0b; margin-right:6px;"></i> Customer Reward Points & Conversion Rate
                </h4>
                <p style="font-size:0.84rem; color:#78716c; margin:0;">
                    Configure customer points per order, point conversion to Taka, and redeem settings.
                </p>
            </div>
            <a href="{{ route('admin.settings.points') }}" class="btn btn-primary" style="background:#f59e0b; border-color:#d97706; padding:8px 18px;">
                <i class="fa-solid fa-sliders"></i> Manage Reward Points
            </a>
        </div>
    </div>

</div>
@endsection
