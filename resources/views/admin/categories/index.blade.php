@extends('layouts.admin')
@section('title', 'Categories | Italian Pizza Admin')
@section('page-title', 'Categories')

@section('admin-content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <p style="color:#64748b;font-size:0.85rem;">{{ $categories->total() }} categories found</p>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add Category
    </a>
</div>

<div class="card-panel">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Name</th>
                <th>Icon</th>
                <th>Products</th>
                <th>Sort</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $cat)
            <tr>
                <td>{{ $cat->id }}</td>
                <td>
                    @if($cat->image)
                        <img src="{{ asset('uploads/' . $cat->image) }}" style="width:40px;height:40px;object-fit:cover;border-radius:6px;">
                    @else
                        <div style="width:40px;height:40px;background:#f1f5f9;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#94a3b8;">
                            <i class="fa-solid fa-image"></i>
                        </div>
                    @endif
                </td>
                <td><strong>{{ $cat->name }}</strong></td>
                <td>{{ $cat->icon ?: '—' }}</td>
                <td><span class="badge badge-active">{{ $cat->products_count }}</span></td>
                <td>{{ $cat->sort_order }}</td>
                <td>
                    <span class="badge {{ $cat->is_active ? 'badge-active' : 'badge-inactive' }}">
                        {{ $cat->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.categories.edit', $cat->id) }}" class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this category?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;color:#94a3b8;padding:32px;">No categories yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($categories->hasPages())
    <div style="margin-top:16px;">{{ $categories->links() }}</div>
@endif
@endsection
