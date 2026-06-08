@extends('layouts.app')
@section('title','Delivery Orders')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1" style="color:var(--secondary)">Delivery Orders</h4><p class="text-muted small mb-0">Track and manage deliveries</p></div>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><p class="text-muted small mb-1">Pending</p><h3 class="fw-bold text-warning">{{ $stats['pending'] ?? 0 }}</h3></div></div></div>
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><p class="text-muted small mb-1">Assigned</p><h3 class="fw-bold text-primary">{{ $stats['assigned'] ?? 0 }}</h3></div></div></div>
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><p class="text-muted small mb-1">In Transit</p><h3 class="fw-bold" style="color:var(--secondary)">{{ $stats['in_transit'] ?? 0 }}</h3></div></div></div>
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><p class="text-muted small mb-1">Delivered Today</p><h3 class="fw-bold text-success">{{ $stats['delivered_today'] ?? 0 }}</h3></div></div></div>
</div>
<div class="card mb-3"><div class="card-body py-2">
    <form method="GET" class="d-flex gap-2 flex-wrap">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search order..." value="{{ request('search') }}" style="max-width:200px">
        <select name="status" class="form-select form-select-sm" style="max-width:160px">
            <option value="">All Status</option>
            @foreach(['pending','assigned','picked_up','in_transit','delivered','failed','cancelled'] as $s)
            <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <a href="{{ route('delivery.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
    </form>
</div></div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table mb-0">
        <thead><tr><th>Tracking</th><th>Order</th><th>Customer</th><th>Address</th><th>Rider</th><th>Status</th><th>Created</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($deliveries as $d)
            <tr>
                <td class="fw-semibold small" style="color:var(--secondary)">{{ $d->tracking_code }}</td>
                <td>{{ $d->order?->order_number ?? 'â€”' }}</td>
                <td>
                    <div>{{ $d->customer_name ?? $d->order?->customer?->name ?? 'â€”' }}</div>
                    <div class="text-muted small">{{ $d->customer_phone ?? '' }}</div>
                </td>
                <td class="text-muted small" style="max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $d->delivery_address ?? 'â€”' }}</td>
                <td>{{ $d->rider?->name ?? '<span class="text-muted">Unassigned</span>' }}</td>
                <td>
                    <span class="badge {{ match($d->status ?? 'pending'){'pending'=>'bg-warning text-dark','assigned'=>'bg-info','picked_up'=>'bg-primary','in_transit'=>'bg-primary','delivered'=>'bg-success','failed'=>'bg-danger','cancelled'=>'bg-secondary',default=>'bg-secondary'} }}">
                        {{ ucfirst(str_replace('_',' ',$d->status ?? 'pending')) }}
                    </span>
                </td>
                <td class="text-muted small">{{ $d->created_at->format('d M, h:i A') }}</td>
                <td>
                    <div class="d-flex gap-1">
                        @if(!$d->rider_id && ($d->status=='pending' || !$d->status))
                        <button class="btn btn-sm btn-outline-primary py-0 px-2" data-bs-toggle="modal" data-bs-target="#assignModal{{ $d->id }}" title="Assign Rider"><i class="bi bi-person-plus"></i></button>
                        @endif
                        @if(in_array($d->status,['assigned','picked_up','in_transit']))
                        <form method="POST" action="{{ route('delivery.update-status',$d) }}">@csrf @method('PATCH')
                            <select name="status" class="form-select form-select-sm" style="width:120px" onchange="this.form.submit()">
                                <option value="">Update...</option>
                                @foreach(['picked_up'=>'Picked Up','in_transit'=>'In Transit','delivered'=>'Delivered','failed'=>'Failed'] as $val=>$lbl)
                                <option value="{{ $val }}" {{ $d->status==$val?'selected':'' }}>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            <!-- Assign Modal -->
            <div class="modal fade" id="assignModal{{ $d->id }}" tabindex="-1">
                <div class="modal-dialog modal-sm"><div class="modal-content">
                    <div class="modal-header"><h6 class="modal-title">Assign Rider</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <form method="POST" action="{{ route('delivery.assign',$d) }}">@csrf @method('PATCH')
                    <div class="modal-body">
                        <select name="rider_id" class="form-select" required>
                            <option value="">Select Rider</option>
                            @foreach($riders as $r)<option value="{{ $r->id }}">{{ $r->name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-sm btn-primary">Assign</button></div>
                    </form>
                </div></div>
            </div>
            @empty
            <tr><td colspan="8" class="text-center py-4 text-muted">No delivery orders found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div></div>@if($deliveries->hasPages())
<div class="card-footer">
    <span>Showing {{ $deliveries->firstItem() }}-{{ $deliveries->lastItem() }} of {{ $deliveries->total() }}</span>
    {{ $deliveries->links() }}
</div>
@endif</div>
@endsection

