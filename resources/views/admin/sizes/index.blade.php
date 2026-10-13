@extends('layouts.admin')
@section('title', 'Sizes | Italian Pizza Admin')
@section('page-title', 'Pizza Sizes')

@section('admin-content')
<div class="form-row" style="align-items:flex-start;">
    <div>
        <div class="card-panel">
            <div class="card-panel-header">
                <span class="card-panel-title"><i class="fa-solid fa-plus"></i> Add Size</span>
            </div>
            <div class="card-panel-body">
                <form action="{{ route('admin.sizes.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Size Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. Medium">
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                            <input type="checkbox" name="is_active" checked> Active
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Add Size</button>
                </form>
            </div>
        </div>
    </div>

    <div>
        <div class="card-panel">
            <div class="card-panel-header">
                <span class="card-panel-title"><i class="fa-solid fa-ruler-horizontal"></i> All Sizes</span>
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
                    @forelse($sizes as $size)
                    <tr>
                        <td><strong>{{ $size->name }}</strong></td>
                        <td><span class="badge {{ $size->is_active ? 'badge-active' : 'badge-inactive' }}">{{ $size->is_active ? 'Active' : 'Off' }}</span></td>
                        <td>
                            <button class="btn btn-secondary btn-sm" onclick="openEditSize({{ $size->id }}, '{{ $size->name }}', {{ $size->is_active ? 1 : 0 }})">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <form action="{{ route('admin.sizes.destroy', $size->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center;color:#94a3b8;padding:24px;">No sizes yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="editSizeModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:12px;padding:28px;width:400px;max-width:95vw;">
        <h3 style="margin-bottom:16px;font-size:1rem;"><i class="fa-solid fa-pen"></i> Edit Size</h3>
        <form id="editSizeForm" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Name *</label>
                <input type="text" name="name" id="editSizeName" class="form-control" required>
            </div>
            <div class="form-group">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                    <input type="checkbox" name="is_active" id="editSizeActive"> Active
                </label>
            </div>
            <div style="display:flex;gap:8px;margin-top:8px;">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Update</button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('editSizeModal').style.display='none'">Cancel</button>
            </div>
        </form>
    </div>
</div>

@push('admin-scripts')
<script>
function openEditSize(id, name, active) {
    document.getElementById('editSizeForm').action = '/admin/sizes/' + id;
    document.getElementById('editSizeName').value = name;
    document.getElementById('editSizeActive').checked = active == 1;
    document.getElementById('editSizeModal').style.display = 'flex';
}
</script>
@endpush
@endsection
