@extends('layouts.admin')
@section('title', 'Edit Page | Italian Pizza Admin')
@section('page-title', 'Edit Page')

@section('admin-content')
<div style="max-width:860px;margin:0 auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <div>
            <h2 style="font-size:1.2rem;font-weight:800;color:#0f172a;margin:0;">Edit Page: {{ $page->title }}</h2>
        </div>
        <div style="display:flex;gap:8px;">
            <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="btn btn-secondary" style="font-size:0.85rem;">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Preview
            </a>
            <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary" style="font-size:0.85rem;">
                <i class="fa-solid fa-arrow-left"></i> Back to Pages
            </a>
        </div>
    </div>

    <div class="card-panel">
        <div class="card-panel-body">
            <form action="{{ route('admin.pages.update', $page->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="title">Page Title *</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $page->title) }}" required>
                        @error('title') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="slug">URL Slug</label>
                        <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $page->slug) }}">
                        @error('slug') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="content">Page Description / Content *</label>
                    <textarea name="content" id="content" class="form-control" rows="12" required style="line-height:1.6;font-size:0.92rem;">{{ old('content', $page->content) }}</textarea>
                    @error('content') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-row" style="align-items:center;">
                    <div class="form-group">
                        <label class="form-label" for="sort_order">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', $page->sort_order) }}" min="0">
                        @error('sort_order') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group" style="padding-top:24px;">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;user-select:none;font-weight:700;font-size:0.9rem;color:#1e293b;">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $page->is_active) ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--admin-primary);cursor:pointer;">
                            Published
                        </label>
                    </div>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:24px;padding-top:20px;border-top:1px solid var(--border-light);">
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i> Update Page
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
