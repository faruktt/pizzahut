@extends('layouts.admin')
@section('title', 'Ingredients | Italian Pizza Hut Admin')
@section('page-title', 'Ingredients & Stock')

@section('admin-content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
    <div>
        <h2 style="font-size:1.25rem;font-weight:800;color:#0f172a;margin-bottom:4px;">Ingredients Stock</h2>
        <p style="font-size:0.8rem;color:#64748b;margin:0;">Track raw materials, stock levels, and replenishment alerts</p>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('admin.ingredients.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add New Ingredient
        </a>
    </div>
</div>

<!-- Stock Highlights Cards -->
<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:16px;margin-bottom:20px;">
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:16px 20px;display:flex;align-items:center;gap:16px;">
        <div style="width:46px;height:46px;border-radius:10px;background:#fef2f2;color:#dc2626;display:flex;align-items:center;justify-content:center;font-size:1.2rem;">
            <i class="fa-solid fa-carrot"></i>
        </div>
        <div>
            <div style="font-size:0.75rem;color:#64748b;font-weight:700;text-transform:uppercase;">Total Items</div>
            <div style="font-size:1.4rem;font-weight:800;color:#0f172a;">{{ $totalIngredients }}</div>
        </div>
    </div>

    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:16px 20px;display:flex;align-items:center;gap:16px;">
        <div style="width:46px;height:46px;border-radius:10px;background:#fef3c7;color:#d97706;display:flex;align-items:center;justify-content:center;font-size:1.2rem;">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div>
            <div style="font-size:0.75rem;color:#64748b;font-weight:700;text-transform:uppercase;">Low Stock Alert</div>
            <div style="font-size:1.4rem;font-weight:800;color:{{ $lowStockCount > 0 ? '#dc2626' : '#0f172a' }};">
                {{ $lowStockCount }}
            </div>
        </div>
    </div>
</div>

<div class="card-panel">
    <div class="card-panel-header" style="flex-wrap:wrap;gap:12px;">
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
            <span class="card-panel-title"><i class="fa-solid fa-boxes-stacked"></i> All Ingredients</span>
            <div style="display:flex;gap:6px;">
                <a href="{{ route('admin.ingredients.index') }}" class="btn btn-sm {{ !request('filter') ? 'btn-primary' : 'btn-secondary' }}">
                    All
                </a>
                <a href="{{ route('admin.ingredients.index', ['filter' => 'low_stock']) }}" class="btn btn-sm {{ request('filter') === 'low_stock' ? 'btn-danger' : 'btn-secondary' }}">
                    <i class="fa-solid fa-triangle-exclamation"></i> Low Stock ({{ $lowStockCount }})
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.ingredients.index') }}" style="display:flex;gap:8px;">
            @if(request('filter'))
                <input type="hidden" name="filter" value="{{ request('filter') }}">
            @endif
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search ingredient..." style="padding:6px 12px;font-size:0.82rem;width:220px;">
            <button type="submit" class="btn btn-secondary btn-sm"><i class="fa-solid fa-search"></i></button>
            @if(request('search'))
                <a href="{{ route('admin.ingredients.index', request()->only('filter')) }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-xmark"></i></a>
            @endif
        </form>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Code</th>
                <th>Ingredient Name</th>
                <th>Unit</th>
                <th>Unit Cost</th>
                <th>Current Stock</th>
                <th>Alert Level</th>
                <th>Status</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ingredients as $ingredient)
            @php $isLow = $ingredient->isLowStock(); @endphp
            <tr style="{{ $isLow ? 'background:#fff8f6;' : '' }}">
                <td><code style="background:#f1f5f9;padding:2px 6px;border-radius:4px;font-size:0.75rem;">{{ $ingredient->code ?? '—' }}</code></td>
                <td>
                    <strong style="color:#0f172a;display:block;">{{ $ingredient->name }}</strong>
                </td>
                <td><span style="font-weight:600;color:#475569;">{{ strtoupper($ingredient->unit) }}</span></td>
                <td><strong>৳{{ number_format($ingredient->cost_price, 2) }}</strong></td>
                <td>
                    <span style="font-size:0.95rem;font-weight:800;color:{{ $isLow ? '#dc2626' : '#16a34a' }};">
                        {{ (float)$ingredient->current_stock }} {{ $ingredient->unit }}
                    </span>
                    @if($isLow)
                        <span class="badge" style="background:#fee2e2;color:#dc2626;margin-left:6px;font-size:0.65rem;">LOW STOCK</span>
                    @endif
                </td>
                <td><span style="color:#64748b;font-size:0.8rem;">&le; {{ (float)$ingredient->alert_quantity }} {{ $ingredient->unit }}</span></td>
                <td>
                    <span class="badge {{ $ingredient->is_active ? 'badge-active' : 'badge-inactive' }}">
                        {{ $ingredient->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td style="text-align:right;">
                    <button type="button" class="btn btn-secondary btn-sm" onclick="openAdjustModal({{ $ingredient->id }}, '{{ addslashes($ingredient->name) }}', '{{ $ingredient->unit }}', {{ (float)$ingredient->current_stock }})" title="Quick Stock Adjustment">
                        <i class="fa-solid fa-sliders"></i>
                    </button>
                    <a href="{{ route('admin.ingredients.edit', $ingredient->id) }}" class="btn btn-secondary btn-sm" title="Edit">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <form action="{{ route('admin.ingredients.destroy', $ingredient->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this ingredient?');">
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
                <td colspan="8" style="text-align:center;padding:32px;color:#94a3b8;">
                    <i class="fa-solid fa-carrot" style="font-size:2rem;margin-bottom:8px;display:block;opacity:0.4;"></i>
                    No ingredients found. Click "Add New Ingredient" to get started.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($ingredients->hasPages())
    <div style="padding:16px 20px;border-top:1px solid #f1f5f9;">
        {{ $ingredients->links() }}
    </div>
    @endif
</div>

<!-- Stock Adjust Modal -->
<div id="adjustModal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.45);z-index:999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:14px;padding:24px;width:420px;max-width:92vw;box-shadow:0 20px 40px rgba(0,0,0,0.15);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <h3 style="font-size:1rem;font-weight:800;color:#0f172a;margin:0;">
                <i class="fa-solid fa-sliders" style="color:var(--admin-primary);"></i> Adjust Stock
            </h3>
            <button type="button" onclick="closeAdjustModal()" style="border:none;background:none;font-size:1.1rem;color:#94a3b8;cursor:pointer;">&times;</button>
        </div>

        <p style="font-size:0.84rem;color:#475569;margin-bottom:14px;">
            Ingredient: <strong id="modalIngName" style="color:#0f172a;"></strong><br>
            Current Stock: <strong id="modalIngStock" style="color:#2563eb;"></strong>
        </p>

        <form id="adjustStockForm" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Action</label>
                <select name="action" class="form-control" required>
                    <option value="add">+ Add to Stock (Restock / Found)</option>
                    <option value="subtract">- Deduct from Stock (Waste / Damaged / Used)</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Quantity (<span id="modalIngUnit"></span>) *</label>
                <input type="number" step="0.01" min="0.01" name="amount" class="form-control" placeholder="e.g. 5" required>
            </div>

            <div style="display:flex;gap:10px;margin-top:20px;">
                <button type="submit" class="btn btn-primary" style="flex:1;">
                    <i class="fa-solid fa-check"></i> Save Adjustment
                </button>
                <button type="button" class="btn btn-secondary" onclick="closeAdjustModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

@push('admin-scripts')
<script>
function openAdjustModal(id, name, unit, stock) {
    document.getElementById('adjustStockForm').action = '/admin/ingredients/' + id + '/adjust-stock';
    document.getElementById('modalIngName').textContent = name;
    document.getElementById('modalIngUnit').textContent = unit;
    document.getElementById('modalIngStock').textContent = stock + ' ' + unit;
    document.getElementById('adjustModal').style.display = 'flex';
}
function closeAdjustModal() {
    document.getElementById('adjustModal').style.display = 'none';
}
</script>
@endpush
@endsection

