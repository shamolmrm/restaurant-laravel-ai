@extends('layouts.app')
@section('title','Orders')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1" style="color:var(--secondary)">Orders</h4>
        <p class="text-muted small mb-0">Manage all restaurant orders</p></div>
    @can('create orders')
    <a href="{{ route('orders.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>New Order</a>
    @endcan
</div>

<div class="card mb-3"><div class="card-body py-2">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3"><input type="text" name="search" class="form-control form-control-sm" placeholder="Order number..." value="{{ request('search') }}"></div>
        <div class="col-md-2">
            <select name="status" class="form-select form-select-sm">
                <option value="">All Status</option>
                @foreach(['pending','confirmed','preparing','ready','served','completed','cancelled'] as $s)
                <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="type" class="form-select form-select-sm">
                <option value="">All Types</option>
                <option value="dine_in" {{ request('type')=='dine_in'?'selected':'' }}>Dine In</option>
                <option value="takeaway" {{ request('type')=='takeaway'?'selected':'' }}>Takeaway</option>
                <option value="delivery" {{ request('type')=='delivery'?'selected':'' }}>Delivery</option>
            </select>
        </div>
        <div class="col-md-2"><input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}"></div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search me-1"></i>Filter</button>
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
        </div>
    </form>
</div></div>

<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table mb-0">
        <thead><tr><th>Order #</th><th>Table/Type</th><th>Customer</th><th>Items</th><th>Total</th><th>Status</th><th>Time</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td><a href="{{ route('orders.show',$order) }}" class="fw-semibold text-decoration-none" style="color:var(--secondary)">{{ $order->order_number }}</a></td>
                <td>
                    <span class="badge bg-light text-dark">{{ str_replace('_',' ',ucfirst($order->type)) }}</span>
                    @if($order->table)<br><small class="text-muted">{{ $order->table->table_number }}</small>@endif
                </td>
                <td>{{ $order->customer?->name ?? 'Walk-in' }}</td>
                <td><span class="badge bg-primary">{{ $order->items->count() }}</span></td>
                <td class="fw-semibold">৳{{ number_format($order->total_amount,0) }}</td>
                <td>
                    <span class="badge" style="font-size:0.73rem;background:{{ match($order->status){'pending'=>'#fef3c7','confirmed'=>'#dbeafe','preparing'=>'#ede9fe','ready'=>'#d1fae5','served'=>'#cffafe','completed'=>'#dcfce7','cancelled'=>'#fee2e2',default=>'#f3f4f6'} }};color:{{ match($order->status){'pending'=>'#92400e','confirmed'=>'#1e40af','preparing'=>'#5b21b6','ready'=>'#065f46','served'=>'#164e63','completed'=>'#166534','cancelled'=>'#991b1b',default=>'#374151'} }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td class="text-muted small">{{ $order->created_at->diffForHumans() }}</td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('orders.show',$order) }}" class="btn btn-sm btn-outline-primary py-0 px-2"><i class="bi bi-eye"></i></a>
                        @if(!in_array($order->status,['completed','cancelled']))
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary py-0 px-2 dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                            <ul class="dropdown-menu border-0 shadow">
                                @foreach(['confirmed','preparing','ready','served','completed','cancelled'] as $s)
                                @if($s != $order->status)
                                <li>
                                    <form method="POST" action="{{ route('orders.update-status',$order) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="{{ $s }}">
                                        <button type="submit" class="dropdown-item small {{ $s=='cancelled'?'text-danger':'' }}">
                                            <i class="bi {{ match($s){'confirmed'=>'bi-check','preparing'=>'bi-fire','ready'=>'bi-bell','served'=>'bi-person-check','completed'=>'bi-check-circle','cancelled'=>'bi-x-circle',default=>'bi-arrow-right'} }} me-2"></i>
                                            {{ ucfirst($s) }}
                                        </button>
                                    </form>
                                </li>
                                @endif
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-4 text-muted">No orders found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div></div>
@if($orders->hasPages())
<div class="card-footer">
    <span>Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }} of {{ $orders->total() }}</span>
    {{ $orders->links() }}
</div>
@endif
</div>
@endsection
