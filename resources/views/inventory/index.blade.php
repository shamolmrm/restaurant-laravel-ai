@extends('layouts.app')
@section('title','Inventory')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:var(--secondary)">Inventory</h4>
        <p class="text-muted small mb-0">Track stock levels and movements</p>
    </div>
    @can('create inventory')<a href="{{ route('inventory.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Item</a>@endcan
</div>

@if($lowStockCount > 0)
<div class="alert alert-warning d-flex align-items-center gap-2 mb-4">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <strong>{{ $lowStockCount }} item(s)</strong>&nbsp;are below minimum stock level.&nbsp;<a href="?filter=low_stock" class="alert-link">View low stock items</a>
</div>
@endif

<div class="card mb-3"><div class="card-body py-2">
    <form method="GET" class="d-flex gap-2 flex-wrap">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search items..." value="{{ request('search') }}" style="max-width:200px">
        <select name="filter" class="form-select form-select-sm" style="max-width:160px">
            <option value="">All Items</option>
            <option value="low_stock" {{ request('filter')=='low_stock'?'selected':'' }}>Low Stock</option>
            <option value="out_of_stock" {{ request('filter')=='out_of_stock'?'selected':'' }}>Out of Stock</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
    </form>
</div></div>

<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table mb-0">
        <thead><tr><th>SKU</th><th>Item</th><th>Category</th><th>Quantity</th><th>Min Qty</th><th>Unit</th><th>Unit Cost</th><th>Total Value</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($items as $item)
            @php $isLow = $item->isLowStock(); @endphp
            <tr class="{{ $item->quantity==0?'table-danger':($isLow?'table-warning':'') }}">
                <td class="text-muted small">{{ $item->sku }}</td>
                <td class="fw-semibold">{{ $item->name }}</td>
                <td class="text-muted small">{{ $item->category ?? 'â€”' }}</td>
                <td>
                    <span class="fw-bold {{ $item->quantity==0?'text-danger':($isLow?'text-warning':'text-success') }}">{{ number_format($item->quantity,2) }}</span>
                </td>
                <td class="text-muted">{{ number_format($item->min_quantity,2) }}</td>
                <td class="text-muted">{{ $item->unit }}</td>
                <td>à§³{{ number_format($item->unit_cost,2) }}</td>
                <td class="fw-semibold">à§³{{ number_format($item->total_value,2) }}</td>
                <td>
                    @if($item->quantity == 0)
                        <span class="badge bg-danger">Out of Stock</span>
                    @elseif($isLow)
                        <span class="badge bg-warning text-dark">Low Stock</span>
                    @else
                        <span class="badge bg-success">In Stock</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('inventory.show',$item) }}" class="btn btn-sm btn-outline-info py-0 px-2"><i class="bi bi-eye"></i></a>
                        @can('edit inventory')<a href="{{ route('inventory.edit',$item) }}" class="btn btn-sm btn-outline-primary py-0 px-2"><i class="bi bi-pencil"></i></a>@endcan
                        @can('edit inventory')
                        <button class="btn btn-sm btn-outline-success py-0 px-2" data-bs-toggle="modal" data-bs-target="#adjustModal{{ $item->id }}" title="Adjust Stock"><i class="bi bi-arrow-repeat"></i></button>
                        @endcan
                        @can('delete inventory')<form method="POST" action="{{ route('inventory.destroy',$item) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2"><i class="bi bi-trash"></i></button></form>@endcan
                    </div>
                </td>
            </tr>
            @can('edit inventory')
            <div class="modal fade" id="adjustModal{{ $item->id }}" tabindex="-1">
                <div class="modal-dialog modal-sm"><div class="modal-content"><div class="modal-header"><h6 class="modal-title">Adjust: {{ $item->name }}</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <form method="POST" action="{{ route('inventory.adjust',$item) }}">@csrf
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label">Type</label>
                            <select name="type" class="form-select form-select-sm">
                                <option value="adjustment">Manual Adjustment</option>
                                <option value="purchase">Purchase/Receive</option>
                                <option value="waste">Waste/Damage</option>
                                <option value="usage">Usage</option>
                            </select></div>
                        <div class="mb-3"><label class="form-label">Quantity (+ add, - remove)</label><input type="number" name="quantity" class="form-control form-control-sm" step="0.01" placeholder="e.g. 10 or -5" required></div>
                        <div class="mb-2"><label class="form-label">Notes</label><textarea name="notes" class="form-control form-control-sm" rows="2"></textarea></div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-sm btn-primary">Adjust</button></div>
                    </form>
                </div></div>
            </div>
            @endcan
            @empty
            <tr><td colspan="10" class="text-center py-4 text-muted">No inventory items found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div></div>@if($items->hasPages())
<div class="card-footer">
    <span>Showing {{ $items->firstItem() }}-{{ $items->lastItem() }} of {{ $items->total() }}</span>
    {{ $items->links() }}
</div>
@endif</div>
@endsection

