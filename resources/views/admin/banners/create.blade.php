@extends('layouts.admin')
@section('title', 'Add Banner | Italian Pizza Admin')
@section('page-title', 'Add Banner')

@section('admin-content')
<div style="max-width:600px;">
    <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary" style="margin-bottom:20px;">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>

    <div class="card-panel">
        <div class="card-panel-header">
            <span class="card-panel-title"><i class="fa-solid fa-image"></i> New Banner</span>
        </div>
        <div class="card-panel-body">
            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label class="form-label">Banner Image *</label>
                    <input type="file" name="image" class="form-control" accept="image/*" required>
                    @error('image')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                </div>

                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                        <input type="checkbox" name="is_active" checked> Active
                    </label>
                </div>

                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save Banner</button>
            </form>
        </div>
    </div>
</div>
@endsection
