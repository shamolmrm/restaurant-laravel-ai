@extends('layouts.app')
@section('title', 'Menu Items')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:var(--secondary)">Menu Items</h4>
        <p class="text-muted small mb-0">Manage your restaurant menu</p>
    </div>
    @can('create menu')
    <a href="{{ route('menu.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Item</a>
    @endcan
</div>

<!-- Filters -->
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4"><input type="text" name="search" class="form-control form-control-sm" placeholder="Search items..." value="{{ request('search') }}"></div>
            <div class="col-md-3">
                <select name="category_id" class="form-select form-select-sm">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="1" {{ request('status')==='1'?'selected':'' }}>Active</option>
                    <option value="0" {{ request('status')==='0'?'selected':'' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search me-1"></i>Filter</button>
                <a href="{{ route('menu.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>Image</th><th>Item</th><th>Category</th><th>Price</th><th>Cost</th><th>Available</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($menuItems as $item)
                    <tr>
                        <td>
                            @if($item->image)
                            <img src="{{ asset('storage/'.$item->image) }}" width="44" height="44" style="border-radius:8px;object-fit:cover">
                            @else
                            <div style="width:44px;height:44px;background:#f1f5f9;border-radius:8px;display:flex;align-items:center;justify-content:center"><i class="bi bi-image text-muted"></i></div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $item->name }}</div>
                            <div class="text-muted" style="font-size:0.75rem">SKU: {{ $item->sku ?? '—' }}</div>
                        </td>
                        <td><span class="badge bg-light text-dark">{{ $item->category->name }}</span></td>
                        <td class="fw-semibold">৳{{ number_format($item->price,2) }}
                            @if($item->discount > 0)<br><span class="badge bg-warning text-dark" style="font-size:0.7rem">{{ $item->discount }}% off</span>@endif
                        </td>
                        <td class="text-muted">৳{{ number_format($item->cost_price,2) }}</td>
                        <td>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" {{ $item->is_available ? 'checked' : '' }}
                                    onchange="toggleAvailability({{ $item->id }}, this)">
                            </div>
                        </td>
                        <td><span class="badge {{ $item->status ? 'bg-success' : 'bg-secondary' }}">{{ $item->status ? 'Active' : 'Off' }}</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                @can('edit menu')
                                <a href="{{ route('menu.edit',$item) }}" class="btn btn-sm btn-outline-primary py-0 px-2"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('delete menu')
                                <form method="POST" action="{{ route('menu.destroy',$item) }}" onsubmit="return confirm('Delete this item?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2"><i class="bi bi-trash"></i></button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">No menu items found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($menuItems->hasPages())
    <div class="card-footer">
        <span>Showing {{ $menuItems->firstItem() }}–{{ $menuItems->lastItem() }} of {{ $menuItems->total() }}</span>
        {{ $menuItems->links() }}
    </div>
    @endif
</div>
@endsection
@push('scripts')
<script>
function toggleAvailability(id, el) {
    fetch(`/menu/${id}/availability`, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Content-Type': 'application/json' }
    }).then(r=>r.json()).then(d=>{ el.checked = d.is_available; }).catch(()=>{ el.checked = !el.checked; });
}
</script>
@endpush
