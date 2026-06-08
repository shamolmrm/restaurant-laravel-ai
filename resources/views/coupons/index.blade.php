@extends('layouts.app')
@section('title','Coupons')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1" style="color:var(--secondary)">Coupons & Discounts</h4><p class="text-muted small mb-0">Manage promotional codes</p></div>
    @can('create coupons')<a href="{{ route('coupons.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Create Coupon</a>@endcan
</div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table mb-0">
        <thead><tr><th>Code</th><th>Name</th><th>Type</th><th>Value</th><th>Min Order</th><th>Usage</th><th>Validity</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($coupons as $c)
            @php $expired = $c->expires_at && $c->expires_at->isPast(); $limitReached = $c->usage_limit && $c->used_count >= $c->usage_limit; @endphp
            <tr>
                <td><code class="fw-bold" style="color:var(--primary)">{{ $c->code }}</code></td>
                <td>{{ $c->name }}</td>
                <td><span class="badge bg-light text-dark">{{ ucfirst($c->type) }}</span></td>
                <td class="fw-semibold">{{ $c->type=='percentage'?$c->value.'%':'à§³'.number_format($c->value,0) }}</td>
                <td>{{ $c->min_order_amount?'à§³'.number_format($c->min_order_amount,0):'â€”' }}</td>
                <td>{{ $c->used_count ?? 0 }}{{ $c->usage_limit?'/'.($c->usage_limit):'/ âˆž' }}</td>
                <td class="text-muted small">
                    @if($c->starts_at && $c->expires_at) {{ $c->starts_at->format('d M y') }} â€“ {{ $c->expires_at->format('d M y') }}
                    @elseif($c->expires_at) Expires {{ $c->expires_at->format('d M Y') }}
                    @else <span class="text-success">No Expiry</span>
                    @endif
                </td>
                <td>
                    @if(!$c->is_active) <span class="badge bg-secondary">Inactive</span>
                    @elseif($expired) <span class="badge bg-danger">Expired</span>
                    @elseif($limitReached) <span class="badge bg-warning text-dark">Limit Reached</span>
                    @else <span class="badge bg-success">Active</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex gap-1">
                        @can('edit coupons')<a href="{{ route('coupons.edit',$c) }}" class="btn btn-sm btn-outline-primary py-0 px-2"><i class="bi bi-pencil"></i></a>@endcan
                        @can('delete coupons')<form method="POST" action="{{ route('coupons.destroy',$c) }}" onsubmit="return confirm('Delete coupon?')">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2"><i class="bi bi-trash"></i></button></form>@endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center py-4 text-muted">No coupons found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div></div>@if($coupons->hasPages())
<div class="card-footer">
    <span>Showing {{ $coupons->firstItem() }}-{{ $coupons->lastItem() }} of {{ $coupons->total() }}</span>
    {{ $coupons->links() }}
</div>
@endif</div>
@endsection

