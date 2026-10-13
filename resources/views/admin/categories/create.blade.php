@extends('layouts.admin')
@section('title', 'Add Category | Italian Pizza Admin')
@section('page-title', 'Add Category')

@section('admin-content')
<div style="max-width:600px;">
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary" style="margin-bottom:20px;">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>

    <div class="card-panel">
        <div class="card-panel-header">
            <span class="card-panel-title"><i class="fa-solid fa-tags"></i> New Category</span>
        </div>
        <div class="card-panel-body">
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. Pizzas">
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">FA Icon class</label>
                        <input type="text" name="icon" class="form-control" value="{{ old('icon') }}" placeholder="fa-solid fa-pizza-slice">
                        <div class="form-hint">Font Awesome class name</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <div class="form-hint">Saved to public/uploads</div>
                    @error('image')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                        <input type="checkbox" name="is_active" checked> Active
                    </label>
                </div>

                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save Category</button>
            </form>
        </div>
    </div>
</div>
@endsection
