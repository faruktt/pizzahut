@extends('layouts.admin')
@section('title', 'Crusts | Italian Pizza Admin')
@section('page-title', 'Pizza Crusts')

@section('admin-content')
<div class="form-row" style="align-items:flex-start;">
    <div>
        <div class="card-panel">
            <div class="card-panel-header">
                <span class="card-panel-title"><i class="fa-solid fa-plus"></i> Add Crust</span>
            </div>
            <div class="card-panel-body">
                <form action="{{ route('admin.crusts.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Crust Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. Pan Crust">
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                            <input type="checkbox" name="is_active" checked> Active
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Add Crust</button>
                </form>
            </div>
        </div>
    </div>

    <div>
        <div class="card-panel">
            <div class="card-panel-header">
                <span class="card-panel-title"><i class="fa-solid fa-circle-half-stroke"></i> All Crusts</span>
            </div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($crusts as $crust)
                    <tr>
                        <td><strong>{{ $crust->name }}</strong></td>
                        <td><span class="badge {{ $crust->is_active ? 'badge-active' : 'badge-inactive' }}">{{ $crust->is_active ? 'Active' : 'Off' }}</span></td>
                        <td>
                            <button class="btn btn-secondary btn-sm" onclick="openEditCrust({{ $crust->id }}, '{{ $crust->name }}', {{ $crust->is_active ? 1 : 0 }})">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <form action="{{ route('admin.crusts.destroy', $crust->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center;color:#94a3b8;padding:24px;">No crusts yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="editCrustModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:12px;padding:28px;width:400px;max-width:95vw;">
        <h3 style="margin-bottom:16px;font-size:1rem;"><i class="fa-solid fa-pen"></i> Edit Crust</h3>
        <form id="editCrustForm" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Name *</label>
                <input type="text" name="name" id="editCrustName" class="form-control" required>
            </div>
            <div class="form-group">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                    <input type="checkbox" name="is_active" id="editCrustActive"> Active
                </label>
            </div>
            <div style="display:flex;gap:8px;margin-top:8px;">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Update</button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('editCrustModal').style.display='none'">Cancel</button>
            </div>
        </form>
    </div>
</div>

@push('admin-scripts')
<script>
function openEditCrust(id, name, active) {
    document.getElementById('editCrustForm').action = '/admin/crusts/' + id;
    document.getElementById('editCrustName').value = name;
    document.getElementById('editCrustActive').checked = active == 1;
    document.getElementById('editCrustModal').style.display = 'flex';
}
</script>
@endpush
@endsection
