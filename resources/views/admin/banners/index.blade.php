@extends('layouts.admin')
@section('title', 'Banners | Italian Pizza Admin')
@section('page-title', 'Website Banners')

@section('admin-content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <p style="color:#64748b;font-size:0.85rem;">{{ $banners->total() }} banners found — shown as a slider on the homepage</p>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add Banner
    </a>
</div>

<div class="card-panel">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Banner Image</th>
                <th>Sort</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($banners as $banner)
            <tr>
                <td>{{ $banner->id }}</td>
                <td>
                    @if($banner->image)
                        <img src="{{ asset('uploads/' . $banner->image) }}" style="width:140px;height:56px;object-fit:cover;border-radius:6px;border:1px solid #e2e8f0;">
                    @else
                        <div style="width:140px;height:56px;background:#f1f5f9;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#94a3b8;">
                            <i class="fa-solid fa-image"></i>
                        </div>
                    @endif
                </td>
                <td>{{ $banner->sort_order }}</td>
                <td>
                    <span class="badge {{ $banner->is_active ? 'badge-active' : 'badge-inactive' }}">
                        {{ $banner->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this banner?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;color:#94a3b8;padding:32px;">No banners yet. Click "Add Banner" to upload one.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($banners->hasPages())
    <div style="margin-top:16px;">{{ $banners->links() }}</div>
@endif
@endsection
