@extends('layouts.admin')
@section('title', 'Suppliers | Italian Pizza Hut Admin')
@section('page-title', 'Suppliers Management')

@section('admin-content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
    <div>
        <h2 style="font-size:1.25rem;font-weight:800;color:#0f172a;margin-bottom:4px;">Suppliers</h2>
        <p style="font-size:0.8rem;color:#64748b;margin:0;">Manage ingredient vendors and suppliers</p>
    </div>
    <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add New Supplier
    </a>
</div>

<div class="card-panel">
    <div class="card-panel-header" style="flex-wrap:wrap;gap:12px;">
        <span class="card-panel-title"><i class="fa-solid fa-truck"></i> All Suppliers</span>

        <form method="GET" action="{{ route('admin.suppliers.index') }}" style="display:flex;gap:8px;">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by name, phone..." style="padding:6px 12px;font-size:0.82rem;width:220px;">
            <button type="submit" class="btn btn-secondary btn-sm"><i class="fa-solid fa-search"></i></button>
            @if(request('search'))
                <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-xmark"></i></a>
            @endif
        </form>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Company</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Purchases</th>
                <th>Status</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($suppliers as $supplier)
            <tr>
                <td>
                    <strong style="color:#0f172a;display:block;">{{ $supplier->name }}</strong>
                    @if($supplier->address)
                        <small style="color:#64748b;font-size:0.75rem;">{{ Str::limit($supplier->address, 35) }}</small>
                    @endif
                </td>
                <td>{{ $supplier->company_name ?? '—' }}</td>
                <td>
                    @if($supplier->phone)
                        <a href="tel:{{ $supplier->phone }}" style="color:#2563eb;font-weight:600;"><i class="fa-solid fa-phone" style="font-size:0.75rem;"></i> {{ $supplier->phone }}</a>
                    @else
                        —
                    @endif
                </td>
                <td>{{ $supplier->email ?? '—' }}</td>
                <td>
                    <span class="badge" style="background:#e0f2fe;color:#0369a1;">
                        <i class="fa-solid fa-bag-shopping"></i> {{ $supplier->purchases_count }} orders
                    </span>
                </td>
                <td>
                    <span class="badge {{ $supplier->is_active ? 'badge-active' : 'badge-inactive' }}">
                        {{ $supplier->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td style="text-align:right;">
                    <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="btn btn-secondary btn-sm" title="Edit">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this supplier?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:32px;color:#94a3b8;">
                    <i class="fa-solid fa-truck" style="font-size:2rem;margin-bottom:8px;display:block;opacity:0.4;"></i>
                    No suppliers found. Click "Add New Supplier" to create one.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($suppliers->hasPages())
    <div style="padding:16px 20px;border-top:1px solid #f1f5f9;">
        {{ $suppliers->links() }}
    </div>
    @endif
</div>
@endsection

