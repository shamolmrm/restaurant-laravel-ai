@extends('layouts.app')
@section('title','Notifications')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:var(--secondary)">Notifications</h4>
        <p class="text-muted small mb-0"><span class="badge bg-primary">{{ $unreadCount }}</span> unread</p>
    </div>
    @if($unreadCount > 0)
    <form method="POST" action="{{ route('notifications.mark-all-read') }}">@csrf
        <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="bi bi-check2-all me-1"></i>Mark All Read</button>
    </form>
    @endif
</div>
<div class="card"><div class="card-body p-0">
    @forelse($notifications as $n)
    <div class="d-flex align-items-start gap-3 p-3 border-bottom {{ !$n->is_read?'bg-light':'' }}">
        <div class="flex-shrink-0 mt-1">
            @php
                $icon = match($n->type ?? 'info') {
                    'order' => 'bi-bag-check text-primary',
                    'payment' => 'bi-credit-card text-success',
                    'inventory' => 'bi-boxes text-warning',
                    'reservation' => 'bi-calendar-check text-info',
                    'kitchen' => 'bi-fire text-danger',
                    'delivery' => 'bi-truck text-secondary',
                    default => 'bi-bell text-muted',
                };
            @endphp
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;background:#f5f7fa">
                <i class="bi {{ $icon }} fs-5"></i>
            </div>
        </div>
        <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="fw-semibold mb-0">{{ $n->title }}</p>
                    <p class="text-muted small mb-1">{{ $n->message }}</p>
                </div>
                <div class="d-flex align-items-center gap-2 flex-shrink-0 ms-3">
                    <span class="text-muted small">{{ $n->created_at->diffForHumans() }}</span>
                    @if(!$n->is_read)
                    <span class="badge bg-primary" style="width:8px;height:8px;padding:0;border-radius:50%"></span>
                    @endif
                </div>
            </div>
            @if($n->action_url)
            <a href="{{ $n->action_url }}" class="btn btn-sm btn-outline-primary mt-1 py-0">View</a>
            @endif
        </div>
    </div>
    @empty
    <div class="text-center py-5">
        <i class="bi bi-bell-slash display-4 text-muted"></i>
        <p class="text-muted mt-2">No notifications yet</p>
    </div>
    @endforelse
</div>@if($notifications->hasPages())
<div class="card-footer">
    <span>Showing {{ $notifications->firstItem() }}-{{ $notifications->lastItem() }} of {{ $notifications->total() }}</span>
    {{ $notifications->links() }}
</div>
@endif</div>
@endsection

