@extends('layouts.app')
@section('title','Employees')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1" style="color:var(--secondary)">Employees</h4><p class="text-muted small mb-0">Manage staff members</p></div>
    @can('create employees')<a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Employee</a>@endcan
</div>
<div class="card mb-3"><div class="card-body py-2">
    <form method="GET" class="d-flex gap-2 flex-wrap">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ request('search') }}" style="max-width:200px">
        <select name="role" class="form-select form-select-sm" style="max-width:160px">
            <option value="">All Roles</option>
            @foreach(['manager','cashier','waiter','kitchen_staff','delivery_staff'] as $r)
            <option value="{{ $r }}" {{ request('role')==$r?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$r)) }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
    </form>
</div></div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table mb-0">
        <thead><tr><th>ID</th><th>Employee</th><th>Role</th><th>Phone</th><th>Salary</th><th>Hire Date</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($employees as $emp)
            <tr>
                <td class="text-muted small">{{ $emp->employee_id }}</td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:35px;height:35px;background:var(--secondary);color:#fff;font-weight:600;font-size:0.85rem">{{ strtoupper(substr($emp->name,0,1)) }}</div>
                        <div><div class="fw-semibold">{{ $emp->name }}</div><div class="text-muted small">{{ $emp->email ?? $emp->department ?? '' }}</div></div>
                    </div>
                </td>
                <td><span class="badge bg-light text-dark">{{ ucfirst(str_replace('_',' ',$emp->role)) }}</span></td>
                <td>{{ $emp->phone }}</td>
                <td class="fw-semibold">৳{{ number_format($emp->salary,0) }}</td>
                <td class="text-muted small">{{ $emp->hire_date->format('d M Y') }}</td>
                <td><span class="badge {{ $emp->status=='active'?'bg-success':'bg-secondary' }}">{{ ucfirst($emp->status) }}</span></td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('employees.show',$emp) }}" class="btn btn-sm btn-outline-info py-0 px-2"><i class="bi bi-eye"></i></a>
                        @can('edit employees')<a href="{{ route('employees.edit',$emp) }}" class="btn btn-sm btn-outline-primary py-0 px-2"><i class="bi bi-pencil"></i></a>@endcan
                        @can('delete employees')<form method="POST" action="{{ route('employees.destroy',$emp) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2"><i class="bi bi-trash"></i></button></form>@endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-4 text-muted">No employees found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div></div>@if($employees->hasPages())
<div class="card-footer">
    <span>Showing {{ $employees->firstItem() }}-{{ $employees->lastItem() }} of {{ $employees->total() }}</span>
    {{ $employees->links() }}
</div>
@endif</div>
@endsection

