@extends('layouts.admin')
@section('title', 'Add Ons | Italian Pizza Admin')
@section('page-title', 'Add Ons (Extra Items)')

@section('admin-content')
<div style="max-width: 960px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2 style="font-size:1.25rem; font-weight:800;">Add Ons Management</h2>
        <a href="{{ route('admin.addons.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add New Add On
        </a>
    </div>

    @if(session('success'))
        <div class="alert-box alert-success" style="margin-bottom:20px;">
            <span><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</span>
        </div>
    @endif

    <div class="card-panel">
        <div class="card-panel-body" style="padding:0;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width:60px;">ID</th>
                        <th>Name</th>
                        <th>Icon</th>
                        <th style="width:100px;">Price</th>
                        <th style="width:100px;">Status</th>
                        <th style="width:140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($addons as $addon)
                    <tr>
                        <td>{{ $addon->id }}</td>
                        <td>{{ $addon->name }}</td>
                        <td>
                            @if($addon->icon)
                                <span style="font-size:1.2rem;">{{ $addon->icon }}</span>
                            @else
                                <span style="color:#94a3b8;">—</span>
                            @endif
                        </td>
                        <td>৳{{ number_format($addon->price, 2) }}</td>
                        <td>
                            <span class="badge {{ $addon->is_active ? 'badge-active' : 'badge-inactive' }}"
                                onclick="toggleAddonStatus({{ $addon->id }})"
                                style="cursor:pointer;">
                                {{ $addon->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex; gap:6px;">
                                <a href="{{ route('admin.addons.edit', $addon->id) }}" class="btn btn-secondary btn-sm">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </a>
                                <form action="{{ route('admin.addons.destroy', $addon->id) }}" method="POST" onsubmit="return confirm('Delete this add-on?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:40px; color:#64748b;">
                            No add-ons found. <a href="{{ route('admin.addons.create') }}">Create your first add-on</a>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $addons->links() }}
        </div>
    </div>
</div>

@push('admin-scripts')
<script>
    function toggleAddonStatus(id) {
        if (!confirm('Toggle status for this add-on?')) return;
        
        fetch('{{ route("admin.addons.toggle", ":id") }}'.replace(':id', id), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to toggle status');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Error toggling status');
        });
    }
</script>
@endpush
@endsection