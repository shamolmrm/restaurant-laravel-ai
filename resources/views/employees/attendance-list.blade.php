@extends('layouts.app')
@section('title','Attendance')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:var(--secondary)">Attendance Sheet</h4>
        <p class="text-muted small mb-0">Mark daily attendance for all employees</p>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="fw-bold fs-4 text-success">{{ $summary['present'] }}</div>
                <div class="text-muted small">Present</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="fw-bold fs-4 text-danger">{{ $summary['absent'] }}</div>
                <div class="text-muted small">Absent</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="fw-bold fs-4 text-warning">{{ $summary['late'] }}</div>
                <div class="text-muted small">Late</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="fw-bold fs-4 text-secondary">{{ $summary['not_marked'] }}</div>
                <div class="text-muted small">Not Marked</div>
            </div>
        </div>
    </div>
</div>

{{-- Date & Search Filter --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-auto">
                <input type="date" name="date" class="form-control form-control-sm" value="{{ $date }}">
            </div>
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search employee..." value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search me-1"></i>Filter</button>
                <a href="{{ route('employees.attendance') }}" class="btn btn-outline-secondary btn-sm ms-1">Today</a>
            </div>
        </form>
    </div>
</div>

{{-- Attendance Table --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Role</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                        <th>Mark</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                    @php $att = $employee->attendances->first(); @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                                     style="width:34px;height:34px;background:var(--primary);font-size:0.75rem">
                                    {{ strtoupper(substr($employee->name,0,1)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold small">{{ $employee->name }}</div>
                                    <div class="text-muted" style="font-size:0.72rem">{{ $employee->employee_id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-muted small">{{ ucfirst(str_replace('_',' ',$employee->role)) }}</td>
                        <td class="small">{{ $att?->check_in?->format('h:i A') ?? '—' }}</td>
                        <td class="small">{{ $att?->check_out?->format('h:i A') ?? '—' }}</td>
                        <td>
                            @if($att)
                                <span class="badge {{ match($att->status){
                                    'present'  => 'bg-success',
                                    'absent'   => 'bg-danger',
                                    'late'     => 'bg-warning text-dark',
                                    'half_day' => 'bg-info',
                                    'leave'    => 'bg-secondary',
                                    default    => 'bg-secondary'
                                } }}">{{ ucfirst(str_replace('_',' ',$att->status)) }}</span>
                            @else
                                <span class="badge bg-light text-muted">Not Marked</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('employees.mark-attendance') }}" class="d-flex gap-1 flex-wrap">
                                @csrf
                                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                <input type="hidden" name="date" value="{{ $date }}">
                                <select name="status" class="form-select form-select-sm" style="max-width:115px">
                                    @foreach(['present','absent','late','half_day','leave'] as $s)
                                    <option value="{{ $s }}" {{ $att?->status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary py-0 px-2"><i class="bi bi-check-lg"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">No active employees found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <span class="text-muted small">{{ $employees->count() }} active employees &bull; Date: {{ \Carbon\Carbon::parse($date)->format('d M Y, l') }}</span>
    </div>
</div>
@endsection
