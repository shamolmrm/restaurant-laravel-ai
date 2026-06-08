@extends('layouts.app')
@section('title','Reservations')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h4 class="fw-bold mb-1" style="color:var(--secondary)">Reservations</h4>
        <p class="text-muted small mb-0"><span class="badge bg-primary">{{ $upcomingCount }}</span> upcoming reservations</p></div>
    @can('create reservations')<a href="{{ route('reservations.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>New Reservation</a>@endcan
</div>
<div class="card mb-3"><div class="card-body py-2">
    <form method="GET" class="d-flex gap-2">
        <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}" style="max-width:180px">
        <select name="status" class="form-select form-select-sm" style="max-width:160px">
            <option value="">All Status</option>
            @foreach(['pending','confirmed','seated','completed','cancelled','no_show'] as $s)
            <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <a href="{{ route('reservations.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
    </form>
</div></div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table mb-0">
        <thead><tr><th>Res #</th><th>Guest</th><th>Phone</th><th>Date & Time</th><th>Table</th><th>Guests</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($reservations as $r)
            <tr>
                <td class="fw-semibold" style="color:var(--secondary)">{{ $r->reservation_number }}</td>
                <td>{{ $r->customer_name }}</td>
                <td>{{ $r->customer_phone }}</td>
                <td>{{ $r->reservation_date->format('d M Y') }}<br><small class="text-muted">{{ substr($r->reservation_time,0,5) }}</small></td>
                <td>{{ $r->table?->table_number ?? 'â€”' }}</td>
                <td><i class="bi bi-people me-1 text-muted"></i>{{ $r->guest_count }}</td>
                <td>
                    <span class="badge" style="background:{{ match($r->status){'pending'=>'#fef3c7','confirmed'=>'#dbeafe','seated'=>'#dcfce7','completed'=>'#d1fae5','cancelled'=>'#fee2e2','no_show'=>'#f3f4f6',default=>'#f3f4f6'} }};color:{{ match($r->status){'pending'=>'#92400e','confirmed'=>'#1e40af','seated'=>'#166534','completed'=>'#065f46','cancelled'=>'#991b1b','no_show'=>'#374151',default=>'#374151'} }}">
                        {{ ucfirst(str_replace('_',' ',$r->status)) }}
                    </span>
                </td>
                <td>
                    <div class="d-flex gap-1">
                        @can('edit reservations')<a href="{{ route('reservations.edit',$r) }}" class="btn btn-sm btn-outline-primary py-0 px-2"><i class="bi bi-pencil"></i></a>@endcan
                        @can('delete reservations')<form method="POST" action="{{ route('reservations.destroy',$r) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2"><i class="bi bi-trash"></i></button></form>@endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-4 text-muted">No reservations found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div></div>@if($reservations->hasPages())
<div class="card-footer">
    <span>Showing {{ $reservations->firstItem() }}-{{ $reservations->lastItem() }} of {{ $reservations->total() }}</span>
    {{ $reservations->links() }}
</div>
@endif</div>
@endsection

