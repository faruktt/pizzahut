@extends('layouts.admin')
@section('title', 'Edit Banner | Italian Pizza Admin')
@section('page-title', 'Edit Banner')

@section('admin-content')
<div style="max-width:600px;">
    <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary" style="margin-bottom:20px;">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>

    <div class="card-panel">
        <div class="card-panel-header">
            <span class="card-panel-title"><i class="fa-solid fa-pen-to-square"></i> Edit Banner #{{ $banner->id }}</span>
        </div>
        <div class="card-panel-body">
            <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="form-group">
                    <label class="form-label">Banner Image</label>
                    @if($banner->image)
                        <div style="margin-bottom:8px;">
                            <img src="{{ asset('uploads/' . $banner->image) }}" style="max-width:100%;height:120px;object-fit:contain;border-radius:8px;border:1px solid #e2e8f0;background:#f8fafc;">
                        </div>
                    @endif
                    <input type="file" name="image" class="form-control" accept="image/*">
                    @error('image')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $banner->sort_order) }}" min="0">
                </div>

                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                        <input type="checkbox" name="is_active" {{ $banner->is_active ? 'checked' : '' }}> Active
                    </label>
                </div>

                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Update Banner</button>
            </form>
        </div>
    </div>
</div>
@endsection
