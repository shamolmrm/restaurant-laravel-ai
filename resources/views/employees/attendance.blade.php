@extends('layouts.app')
@section('title','Attendance - '.$employee->name)
@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('employees.show',$employee) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h4 class="fw-bold mb-0" style="color:var(--secondary)">Attendance: {{ $employee->name }}</h4>
        <p class="text-muted small mb-0">{{ $employee->employee_id }} &bull; {{ ucfirst(str_replace('_',' ',$employee->role)) }}</p>
    </div>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><p class="text-muted small mb-1">Present Days</p><h3 class="fw-bold text-success">{{ $stats['present'] }}</h3></div></div></div>
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><p class="text-muted small mb-1">Absent Days</p><h3 class="fw-bold text-danger">{{ $stats['absent'] }}</h3></div></div></div>
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><p class="text-muted small mb-1">Late Days</p><h3 class="fw-bold text-warning">{{ $stats['late'] }}</h3></div></div></div>
    <div class="col-md-3"><div class="card text-center"><div class="card-body"><p class="text-muted small mb-1">Total Hours</p><h3 class="fw-bold" style="color:var(--secondary)">{{ $stats['total_hours'] }}</h3></div></div></div>
</div>
<div class="card mb-3"><div class="card-body py-2">
    <form method="GET" class="d-flex gap-2 flex-wrap">
        <input type="month" name="month" class="form-control form-control-sm" value="{{ request('month',now()->format('Y-m')) }}" style="max-width:160px">
        <select name="status" class="form-select form-select-sm" style="max-width:150px">
            <option value="">All Status</option>
            @foreach(['present','absent','late','half_day','holiday'] as $s)
            <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
    </form>
</div></div>
<div class="card"><div class="card-body p-0">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Date</th><th>Day</th><th>Check In</th><th>Check Out</th><th>Working Hours</th><th>Status</th><th>Notes</th></tr></thead>
            <tbody>
                @forelse($attendances as $att)
                <tr>
                    <td>{{ $att->date->format('d M Y') }}</td>
                    <td class="text-muted">{{ $att->date->format('l') }}</td>
                    <td>{{ $att->check_in?->format('h:i A') ?? 'â€”' }}</td>
                    <td>{{ $att->check_out?->format('h:i A') ?? 'â€”' }}</td>
                    <td>{{ $att->working_hours ? $att->working_hours.'h' : 'â€”' }}</td>
                    <td><span class="badge {{ match($att->status){'present'=>'bg-success','absent'=>'bg-danger','late'=>'bg-warning text-dark','half_day'=>'bg-info',default=>'bg-secondary'} }}">{{ ucfirst(str_replace('_',' ',$att->status)) }}</span></td>
                    <td class="text-muted small">{{ $att->notes ?? '' }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">No attendance records for selected period.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>@if($attendances->hasPages())
<div class="card-footer">
    <span>Showing {{ $attendances->firstItem() }}-{{ $attendances->lastItem() }} of {{ $attendances->total() }}</span>
    {{ $attendances->links() }}
</div>
@endif</div>
@endsection

