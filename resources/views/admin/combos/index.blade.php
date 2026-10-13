@extends('layouts.admin')
@section('title', 'Combos | Admin')
@section('page-title', 'Combo Meals Management')

@section('admin-content')
<div class="card-panel">
    <div class="card-panel-header">
        <span class="card-panel-title"><i class="fa-solid fa-layer-group" style="color:var(--admin-primary);margin-right:6px;"></i> All Combos</span>
        <a href="{{ route('admin.combos.create') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus"></i> Add Combo
        </a>
    </div>
    <div class="card-panel-body" style="padding:0;">
        @if($combos->isEmpty())
            <div style="padding:40px;text-align:center;color:#94a3b8;">
                <i class="fa-solid fa-layer-group" style="font-size:2rem;margin-bottom:10px;"></i>
                <p>No combos yet. <a href="{{ route('admin.combos.create') }}" style="color:var(--admin-primary);">Create your first combo</a></p>
            </div>
        @else
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:60px;">#</th>
                    <th style="width:70px;">Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Max Select</th>
                    <th>Products</th>
                    <th>Status</th>
                    <th style="width:140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($combos as $combo)
                <tr>
                    <td>{{ $combo->sort_order ?: $loop->iteration }}</td>
                    <td>
                        @if($combo->image && file_exists(public_path('uploads/'.$combo->image)))
                            <img src="{{ asset('uploads/'.$combo->image) }}" style="width:50px;height:40px;object-fit:cover;border-radius:6px;">
                        @else
                            <div style="width:50px;height:40px;background:#f1f5f9;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="fa-solid fa-image" style="color:#cbd5e1;"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:700;color:#0f172a;">{{ $combo->name }}</div>
                        @if($combo->description)
                            <div style="font-size:0.75rem;color:#64748b;margin-top:2px;">{{ Str::limit($combo->description, 60) }}</div>
                        @endif
                    </td>
                    <td style="font-weight:700;color:var(--admin-primary);">৳ {{ number_format($combo->price, 0) }}</td>
                    <td>
                        <span class="badge" style="background:#e0f2fe;color:#0369a1;">
                            <i class="fa-solid fa-check-square"></i> Select {{ $combo->max_select }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-active">
                            <i class="fa-solid fa-box-open"></i> {{ $combo->products_count }} products
                        </span>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.combos.toggle', $combo->id) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="badge {{ $combo->is_active ? 'badge-active' : 'badge-inactive' }}" style="border:none;cursor:pointer;">
                                <i class="fa-solid fa-{{ $combo->is_active ? 'toggle-on' : 'toggle-off' }}"></i>
                                {{ $combo->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                    </td>
                    <td>
                        <a href="{{ route('admin.combos.edit', $combo->id) }}" class="btn btn-warning btn-sm">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.combos.destroy', $combo->id) }}" style="display:inline;" onsubmit="return confirm('Delete this combo?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                        </form>
                        <a href="{{ route('combo.show', $combo->id) }}" target="_blank" class="btn btn-secondary btn-sm" title="Preview">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection
