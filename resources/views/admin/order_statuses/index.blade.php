@extends('layouts.admin')

@section('page-title', 'Order Statuses Management')

@section('admin-content')
<div class="card-panel">
    <div class="card-panel-header" style="display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid var(--border-light);">
        <div>
            <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0;">Order Statuses</h3>
            <p style="font-size: 0.8rem; color: #64748b; margin: 2px 0 0 0;">Create and customize dynamic order stages shown across admin orders table and invoices</p>
        </div>
        <a href="{{ route('admin.order-statuses.create') }}" class="btn btn-primary" style="padding: 8px 16px;">
            <i class="fa-solid fa-plus"></i> Add New Status
        </a>
    </div>

    <div style="overflow-x: auto;">
        <table class="data-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid var(--border-light); text-align: left; font-size: 0.78rem; text-transform: uppercase; color: #64748b;">
                    <th style="padding: 12px 20px;">Sort</th>
                    <th style="padding: 12px 20px;">Badge Preview</th>
                    <th style="padding: 12px 20px;">Status Name</th>
                    <th style="padding: 12px 20px;">Code / Slug</th>
                    <th style="padding: 12px 20px;">Orders Using</th>
                    <th style="padding: 12px 20px;">Status</th>
                    <th style="padding: 12px 20px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($statuses as $st)
                    @php
                        $count = ($statusCounts[$st->slug] ?? 0) + ($statusCounts[$st->name] ?? 0);
                    @endphp
                    <tr style="border-bottom: 1px solid var(--border-light); font-size: 0.88rem;">
                        <td style="padding: 14px 20px; color: #94a3b8; font-weight: 700;">
                            #{{ $st->sort_order }}
                        </td>
                        <td style="padding: 14px 20px;">
                            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 999px; font-size: 0.8rem; font-weight: 700; color: {{ $st->color }}; background-color: {{ $st->bg_color }}; border: 1px solid rgba(0,0,0,0.06);">
                                <span style="width: 8px; height: 8px; border-radius: 50%; background-color: {{ $st->color }};"></span>
                                {{ $st->name }}
                            </span>
                        </td>
                        <td style="padding: 14px 20px; font-weight: 700; color: #1e293b;">
                            {{ $st->name }}
                            @if($st->is_default)
                                <span style="font-size: 0.68rem; background: #e0f2fe; color: #0369a1; padding: 2px 6px; border-radius: 4px; margin-left: 6px; font-weight: 700;">DEFAULT</span>
                            @endif
                        </td>
                        <td style="padding: 14px 20px;">
                            <code style="background: #f1f5f9; padding: 3px 8px; border-radius: 6px; font-size: 0.8rem; color: #475569;">{{ $st->slug }}</code>
                        </td>
                        <td style="padding: 14px 20px;">
                            <span style="font-weight: 700; color: {{ $count > 0 ? '#0f172a' : '#94a3b8' }};">
                                {{ $count }} order(s)
                            </span>
                        </td>
                        <td style="padding: 14px 20px;">
                            <form action="{{ route('admin.order-statuses.toggle', $st->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" style="background: none; border: none; cursor: pointer;">
                                    @if($st->is_active)
                                        <span class="badge-active" style="padding: 4px 10px; border-radius: 6px; font-size: 0.76rem; font-weight: 700;">Active</span>
                                    @else
                                        <span class="badge-inactive" style="padding: 4px 10px; border-radius: 6px; font-size: 0.76rem; font-weight: 700;">Inactive</span>
                                    @endif
                                </button>
                            </form>
                        </td>
                        <td style="padding: 14px 20px; text-align: right;">
                            <div style="display: inline-flex; gap: 8px; align-items: center;">
                                <a href="{{ route('admin.order-statuses.edit', $st->id) }}" class="btn btn-secondary btn-sm" title="Edit Status">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </a>

                                <form action="{{ route('admin.order-statuses.destroy', $st->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this status?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete Status" {{ $count > 0 ? 'disabled' : '' }} style="{{ $count > 0 ? 'opacity:0.4; cursor:not-allowed;' : '' }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: #94a3b8;">
                            No order statuses configured yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
