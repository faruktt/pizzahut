@extends('layouts.admin')
@section('title', 'Pages Management | Italian Pizza Admin')
@section('page-title', 'Custom Website Pages')

@section('admin-content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
    <div>
        <h2 style="font-size:1.25rem;font-weight:800;color:#0f172a;margin-bottom:4px;">Website CMS Pages</h2>
        <p style="color:#64748b;font-size:0.85rem;margin:0;">Create and manage public pages like About Us, Privacy Policy, Terms & Conditions.</p>
    </div>
    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:8px;">
        <i class="fa-solid fa-plus"></i> Create New Page
    </a>
</div>

<div class="card-panel">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width:50px;">#</th>
                <th>Page Title</th>
                <th>URL Slug</th>
                <th>Frontend Link</th>
                <th style="width:100px;">Sort</th>
                <th style="width:120px;">Status</th>
                <th style="width:140px;text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pages as $page)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    <div style="font-weight:700;color:#0f172a;font-size:0.92rem;">{{ $page->title }}</div>
                    <div style="font-size:0.75rem;color:#94a3b8;">Updated {{ $page->updated_at ? $page->updated_at->diffForHumans() : 'N/A' }}</div>
                </td>
                <td>
                    <code style="background:#f1f5f9;color:#0369a1;padding:3px 8px;border-radius:4px;font-size:0.82rem;font-weight:600;">/page/{{ $page->slug }}</code>
                </td>
                <td>
                    <a href="{{ route('page.show', $page->slug) }}" target="_blank" style="display:inline-flex;align-items:center;gap:5px;font-size:0.82rem;color:#0284c7;font-weight:600;text-decoration:none;">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Preview
                    </a>
                </td>
                <td>
                    <span style="font-weight:600;color:#475569;">{{ $page->sort_order }}</span>
                </td>
                <td>
                    <form action="{{ route('admin.pages.toggleStatus', $page->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" style="background:none;border:none;cursor:pointer;padding:0;">
                            <span class="badge {{ $page->is_active ? 'badge-active' : 'badge-inactive' }}">
                                <i class="fa-solid {{ $page->is_active ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                                {{ $page->is_active ? 'Published' : 'Draft' }}
                            </span>
                        </button>
                    </form>
                </td>
                <td style="text-align:right;">
                    <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-secondary btn-sm" title="Edit Page" style="margin-right:4px;">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this page?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete Page">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:48px 16px;color:#94a3b8;">
                    <i class="fa-regular fa-file-lines" style="font-size:2.5rem;display:block;margin-bottom:12px;color:#cbd5e1;"></i>
                    <div style="font-size:1rem;font-weight:700;color:#475569;margin-bottom:4px;">No pages created yet</div>
                    <div style="font-size:0.85rem;margin-bottom:16px;">Create your first page such as About Us, Privacy Policy or Terms.</div>
                    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-plus"></i> Add New Page
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
