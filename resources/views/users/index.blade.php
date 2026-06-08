@extends('layouts.app')
@section('title','Users')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1" style="color:var(--secondary)">System Users</h4><p class="text-muted small mb-0">Manage user accounts and roles</p></div>
    @can('create users')<a href="{{ route('users.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Add User</a>@endcan
</div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table mb-0">
        <thead><tr><th>User</th><th>Email</th><th>Phone</th><th>Roles</th><th>Status</th><th>Last Login</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($users as $u)
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        @if($u->avatar)
                        <img src="{{ asset('storage/'.$u->avatar) }}" class="rounded-circle" width="35" height="35" style="object-fit:cover">
                        @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:35px;height:35px;background:var(--secondary);color:#fff;font-weight:700">{{ strtoupper(substr($u->name,0,1)) }}</div>
                        @endif
                        <div><div class="fw-semibold">{{ $u->name }}</div><div class="text-muted small">#{{ $u->id }}</div></div>
                    </div>
                </td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->phone ?? 'â€”' }}</td>
                <td>
                    @foreach($u->roles as $role)<span class="badge me-1" style="background:var(--secondary)">{{ ucfirst($role->name) }}</span>@endforeach
                </td>
                <td><span class="badge {{ ($u->status ?? 'active')=='active'?'bg-success':'bg-secondary' }}">{{ ucfirst($u->status ?? 'active') }}</span></td>
                <td class="text-muted small">{{ $u->last_login_at?->diffForHumans() ?? 'Never' }}</td>
                <td>
                    <div class="d-flex gap-1">
                        @can('edit users')<a href="{{ route('users.edit',$u) }}" class="btn btn-sm btn-outline-primary py-0 px-2"><i class="bi bi-pencil"></i></a>@endcan
                        @can('delete users')
                        @if($u->id !== auth()->id())
                        <form method="POST" action="{{ route('users.destroy',$u) }}" onsubmit="return confirm('Delete user?')">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2"><i class="bi bi-trash"></i></button></form>
                        @endif
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-4 text-muted">No users found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div></div>@if($users->hasPages())
<div class="card-footer">
    <span>Showing {{ $users->firstItem() }}-{{ $users->lastItem() }} of {{ $users->total() }}</span>
    {{ $users->links() }}
</div>
@endif</div>
@endsection

