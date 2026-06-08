@extends('layouts.app')
@section('title','Suppliers')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1" style="color:var(--secondary)">Suppliers</h4><p class="text-muted small mb-0">Manage product suppliers</p></div>
    @can('create suppliers')<a href="{{ route('suppliers.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Supplier</a>@endcan
</div>
<div class="card mb-3"><div class="card-body py-2">
    <form method="GET" class="d-flex gap-2">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ request('search') }}" style="max-width:250px">
        <button type="submit" class="btn btn-primary btn-sm">Search</button>
        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
    </form>
</div></div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table mb-0">
        <thead><tr><th>Name</th><th>Contact</th><th>Phone</th><th>Email</th><th>City</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($suppliers as $s)
            <tr>
                <td>
                    <div class="fw-semibold">{{ $s->name }}</div>
                    <div class="text-muted small">{{ $s->company ?? '' }}</div>
                </td>
                <td>{{ $s->contact_person ?? 'â€”' }}</td>
                <td>{{ $s->phone ?? 'â€”' }}</td>
                <td>{{ $s->email ?? 'â€”' }}</td>
                <td>{{ $s->city ?? 'â€”' }}</td>
                <td><span class="badge {{ $s->status=='active'?'bg-success':'bg-secondary' }}">{{ ucfirst($s->status ?? 'active') }}</span></td>
                <td>
                    <div class="d-flex gap-1">
                        @can('edit suppliers')<a href="{{ route('suppliers.edit',$s) }}" class="btn btn-sm btn-outline-primary py-0 px-2"><i class="bi bi-pencil"></i></a>@endcan
                        @can('delete suppliers')<form method="POST" action="{{ route('suppliers.destroy',$s) }}" onsubmit="return confirm('Delete supplier?')">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2"><i class="bi bi-trash"></i></button></form>@endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-4 text-muted">No suppliers found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div></div>@if($suppliers->hasPages())
<div class="card-footer">
    <span>Showing {{ $suppliers->firstItem() }}-{{ $suppliers->lastItem() }} of {{ $suppliers->total() }}</span>
    {{ $suppliers->links() }}
</div>
@endif</div>
@endsection

