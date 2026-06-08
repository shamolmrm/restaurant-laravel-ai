@extends('layouts.app')
@section('title','Purchase Orders')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1" style="color:var(--secondary)">Purchase Orders</h4><p class="text-muted small mb-0">Manage supplier orders</p></div>
    @can('create purchase_orders')<a href="{{ route('purchases.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>New PO</a>@endcan
</div>
<div class="card mb-3"><div class="card-body py-2">
    <form method="GET" class="d-flex gap-2 flex-wrap">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="PO Number..." value="{{ request('search') }}" style="max-width:200px">
        <select name="status" class="form-select form-select-sm" style="max-width:160px">
            <option value="">All Status</option>
            @foreach(['draft','ordered','partial','received','cancelled'] as $s)
            <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
    </form>
</div></div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table mb-0">
        <thead><tr><th>PO Number</th><th>Supplier</th><th>Items</th><th>Total</th><th>Status</th><th>Payment</th><th>Order Date</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($purchaseOrders as $po)
            <tr>
                <td class="fw-semibold" style="color:var(--secondary)">{{ $po->po_number }}</td>
                <td>{{ $po->supplier?->name ?? 'â€”' }}</td>
                <td>{{ $po->items_count ?? $po->items->count() }}</td>
                <td class="fw-semibold">à§³{{ number_format($po->total_amount,2) }}</td>
                <td>
                    <span class="badge {{ match($po->status){'draft'=>'bg-secondary','ordered'=>'bg-primary','partial'=>'bg-warning text-dark','received'=>'bg-success','cancelled'=>'bg-danger',default=>'bg-secondary'} }}">
                        {{ ucfirst($po->status) }}
                    </span>
                </td>
                <td>
                    <span class="badge {{ $po->payment_status=='paid'?'bg-success':($po->payment_status=='partial'?'bg-warning text-dark':'bg-light text-dark') }}">
                        {{ ucfirst($po->payment_status ?? 'unpaid') }}
                    </span>
                </td>
                <td class="text-muted small">{{ $po->order_date?->format('d M Y') ?? $po->created_at->format('d M Y') }}</td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('purchases.show',$po) }}" class="btn btn-sm btn-outline-info py-0 px-2"><i class="bi bi-eye"></i></a>
                        @if($po->status !== 'received' && $po->status !== 'cancelled')
                        @can('edit purchase_orders')<a href="{{ route('purchases.edit',$po) }}" class="btn btn-sm btn-outline-primary py-0 px-2"><i class="bi bi-pencil"></i></a>@endcan
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-4 text-muted">No purchase orders found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div></div>@if($purchaseOrders->hasPages())
<div class="card-footer">
    <span>Showing {{ $purchaseOrders->firstItem() }}-{{ $purchaseOrders->lastItem() }} of {{ $purchaseOrders->total() }}</span>
    {{ $purchaseOrders->links() }}
</div>
@endif</div>
@endsection

