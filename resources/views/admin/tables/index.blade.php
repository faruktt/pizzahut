@extends('layouts.admin')

@section('title', 'Manage Dining Tables | Italian Pizza Admin')
@section('page-title', 'Restaurant Dining Tables Management')

@section('admin-content')
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: flex-start;">

    <!-- Left: Tables List -->
    <div class="card-panel">
        <div class="card-panel-header">
            <h3 class="card-panel-title">🍽️ Active Restaurant Tables</h3>
            <span style="font-size:0.8rem; color:#64748b;">{{ $tables->count() }} Tables Registered</span>
        </div>

        <div style="overflow-x: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Table #</th>
                        <th>Capacity</th>
                        <th>Status</th>
                        <th>Current Order</th>
                        <th>Change Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tables as $tbl)
                        <tr>
                            <td>
                                <strong>{{ $tbl->table_number }}</strong>
                            </td>
                            <td>
                                <span>{{ $tbl->capacity }} Persons</span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $tbl->status }}">
                                    {{ ucfirst($tbl->status) }}
                                </span>
                            </td>
                            <td>
                                @if($tbl->status === 'occupied' && $tbl->activeOrder)
                                    <div>
                                        <a href="{{ route('admin.pos.index') }}?table={{ $tbl->id }}" style="color:var(--admin-primary); font-weight:700;">
                                            #{{ $tbl->activeOrder->order_number }}
                                        </a>
                                        <div style="font-size:0.75rem; color:#64748b;">৳{{ number_format($tbl->activeOrder->total_amount) }}</div>
                                    </div>
                                @else
                                    <span style="color:#94a3b8; font-size:0.78rem;">No active order</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.tables.updateStatus', $tbl->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" style="padding:4px 8px; border-radius:6px; font-size:0.75rem; border:1px solid #cbd5e1;">
                                        <option value="available" {{ $tbl->status === 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="occupied" {{ $tbl->status === 'occupied' ? 'selected' : '' }}>Occupied</option>
                                        <option value="reserved" {{ $tbl->status === 'reserved' ? 'selected' : '' }}>Reserved</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <form action="{{ route('admin.tables.destroy', $tbl->id) }}" method="POST" onsubmit="return confirm('Delete this table?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background:none; border:none; color:#ef4444; font-size:0.8rem; cursor:pointer;">
                                        🗑️ Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right: Add Table Form -->
    <div class="card-panel">
        <div class="card-panel-header">
            <h3 class="card-panel-title">➕ Add New Table</h3>
        </div>

        <form action="{{ route('admin.tables.store') }}" method="POST" class="card-panel-body">
            @csrf
            <div style="margin-bottom: 14px;">
                <label style="display:block; font-size:0.8rem; font-weight:700; color:#334155; margin-bottom:6px;">Table Number / Name *</label>
                <input type="text" name="table_number" placeholder="e.g. Table 11" required style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px; font-size:0.9rem;">
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display:block; font-size:0.8rem; font-weight:700; color:#334155; margin-bottom:6px;">Seating Capacity *</label>
                <input type="number" name="capacity" value="4" min="1" required style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px; font-size:0.9rem;">
            </div>

            <button type="submit" class="btn-quick-pos" style="width: 100%; justify-content: center; padding: 10px;">
                Save Dining Table
            </button>
        </form>
    </div>

</div>
@endsection
