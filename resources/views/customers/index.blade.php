@extends('layouts.app')
@section('title','Customers')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1" style="color:var(--secondary)">Customers</h4><p class="text-muted small mb-0">Manage customer database & loyalty</p></div>
    @can('create customers')<a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Customer</a>@endcan
</div>
<div class="card mb-3"><div class="card-body py-2">
    <form method="GET" class="d-flex gap-2">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name, phone, email..." value="{{ request('search') }}" style="max-width:300px">
        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
        <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
    </form>
</div></div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table mb-0">
        <thead><tr><th>#</th><th>Name</th><th>Phone</th><th>Email</th><th>Orders</th><th>Spent</th><th>Points</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($customers as $c)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><a href="{{ route('customers.show',$c) }}" class="fw-semibold text-decoration-none" style="color:var(--secondary)">{{ $c->name }}</a></td>
                <td>{{ $c->phone }}</td>
                <td>{{ $c->email ?? 'â€”' }}</td>
                <td><span class="badge bg-primary">{{ $c->orders_count }}</span></td>
                <td class="fw-semibold">৳{{ number_format($c->total_spent,0) }}</td>
                <td><span class="badge bg-warning text-dark"><i class="bi bi-star-fill me-1"></i>{{ $c->loyalty_points }}</span></td>
                <td><span class="badge {{ $c->status=='active'?'bg-success':'bg-secondary' }}">{{ ucfirst($c->status) }}</span></td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('customers.show',$c) }}" class="btn btn-sm btn-outline-info py-0 px-2"><i class="bi bi-eye"></i></a>
                        @can('edit customers')<a href="{{ route('customers.edit',$c) }}" class="btn btn-sm btn-outline-primary py-0 px-2"><i class="bi bi-pencil"></i></a>@endcan
                        @can('delete customers')<form method="POST" action="{{ route('customers.destroy',$c) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2"><i class="bi bi-trash"></i></button></form>@endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center py-4 text-muted">No customers found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div></div>@if($customers->hasPages())
<div class="card-footer">
    <span>Showing {{ $customers->firstItem() }}-{{ $customers->lastItem() }} of {{ $customers->total() }}</span>
    {{ $customers->links() }}
</div>
@endif</div>
@endsection

