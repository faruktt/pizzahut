@extends('layouts.admin')
@section('title', 'Products | Italian Pizza Admin')
@section('page-title', 'Products & Menu')

@section('admin-content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <form method="GET" style="display:flex;gap:10px;">
        <input type="text" name="search" class="form-control" placeholder="Search product..." value="{{ request('search') }}" style="width:220px;">
        <select name="category_id" class="form-control" style="width:180px;">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-magnifying-glass"></i></button>
        @if(request()->hasAny(['search','category_id']))
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary"><i class="fa-solid fa-xmark"></i></a>
        @endif
    </form>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add Product
    </a>
</div>

<div class="card-panel">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Sizes</th>
                <th>Crusts</th>
                <th>Tags</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>
                    @if($product->image)
                        <img src="{{ asset('uploads/' . $product->image) }}" style="width:44px;height:44px;object-fit:cover;border-radius:8px;">
                    @else
                        <div style="width:44px;height:44px;background:#f1f5f9;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#94a3b8;">
                            <i class="fa-solid fa-image"></i>
                        </div>
                    @endif
                </td>
                <td><strong>{{ $product->name }}</strong></td>
                <td style="color:#64748b;">{{ $product->category?->name ?? '—' }}</td>
                <td><strong>৳{{ number_format($product->base_price, 2) }}</strong></td>
                <td style="font-size:0.78rem;color:#64748b;">
                    {{ $product->variations->isNotEmpty() ? $product->variations->pluck('size_name')->unique()->filter()->join(', ') : ($product->sizes->pluck('name')->join(', ') ?: '—') }}
                </td>
                <td style="font-size:0.78rem;color:#64748b;">
                    {{ $product->variations->isNotEmpty() ? $product->variations->pluck('crust_name')->unique()->filter()->join(', ') : ($product->crusts->pluck('name')->join(', ') ?: '—') }}
                </td>
                <td>
                    @if($product->is_bestseller)<span class="badge" style="background:#fef3c7;color:#b45309;" title="Bestseller"><i class="fa-solid fa-fire"></i></span> @endif
                    @if($product->is_new)<span class="badge" style="background:#dbeafe;color:#1d4ed8;" title="New"><i class="fa-solid fa-star"></i></span> @endif
                </td>
                <td>
                    <span class="badge {{ $product->is_active ? 'badge-active' : 'badge-inactive' }}">
                        {{ $product->is_active ? 'Active' : 'Off' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this product?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" style="text-align:center;color:#94a3b8;padding:40px;">
                <i class="fa-solid fa-box-open" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                No products yet. <a href="{{ route('admin.products.create') }}">Add first product →</a>
            </td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($products->hasPages())
    <div>{{ $products->links() }}</div>
@endif
@endsection
