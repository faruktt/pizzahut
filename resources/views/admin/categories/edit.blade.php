@extends('layouts.admin')
@section('title', 'Edit Category | Italian Pizza Admin')
@section('page-title', 'Edit Category')

@section('admin-content')
<div style="max-width:600px;">
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary" style="margin-bottom:20px;">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>

    <div class="card-panel">
        <div class="card-panel-header">
            <span class="card-panel-title"><i class="fa-solid fa-pen-to-square"></i> Edit: {{ $category->name }}</span>
        </div>
        <div class="card-panel-body">
            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="form-group">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">FA Icon class</label>
                        <input type="text" name="icon" class="form-control" value="{{ old('icon', $category->icon) }}" placeholder="fa-solid fa-pizza-slice">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $category->sort_order) }}" min="0">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Image</label>
                    @if($category->image)
                        <div style="margin-bottom:8px;">
                            <img src="{{ asset('uploads/' . $category->image) }}" style="height:60px;border-radius:6px;">
                        </div>
                    @endif
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>

                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                        <input type="checkbox" name="is_active" {{ $category->is_active ? 'checked' : '' }}> Active
                    </label>
                </div>

                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Update</button>
            </form>
        </div>
    </div>
</div>
@endsection
