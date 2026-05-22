{{-- resources/views/notifications/index.blade.php --}}
@extends('layouts.app')
@section('title','Notifications')
@section('page-title','Notifications')
@section('page-sub','Your system alerts and updates')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div style="font-size:13px;color:var(--text-muted)">{{ $notifications->total() }} notifications</div>
    <form method="POST" action="{{ route('notifications.markAllRead') }}">
        @csrf
        <button type="submit" class="btn-swms-secondary" style="font-size:12px;padding:6px 14px">
            <i class="ti ti-check"></i> Mark all read
        </button>
    </form>
</div>

<div class="swms-card">
    @forelse($notifications as $n)
    @php
        $iconMap = ['complaint'=>'green','task'=>'blue','bin'=>'red','reminder'=>'amber','points'=>'green','system'=>'blue'];
        $iconClass = $iconMap[$n->type] ?? 'blue';
        $tiIcons = ['complaint'=>'ti-file-text','task'=>'ti-calendar-check','bin'=>'ti-alert-triangle','reminder'=>'ti-clock','points'=>'ti-trophy','system'=>'ti-info-circle'];
        $tiClass = $tiIcons[$n->type] ?? 'ti-bell';
    @endphp
    <div class="notif-item {{ !$n->is_read ? '' : 'opacity-75' }}">
        <div class="notif-icon {{ $iconClass }}"><i class="ti {{ $tiClass }}"></i></div>
        <div class="flex-fill">
            <div style="font-size:13px;font-weight:{{ !$n->is_read ? '600' : '400' }}">{{ $n->title }}</div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:2px">{{ $n->message }}</div>
            <div style="font-size:11px;color:var(--text-subtle);margin-top:3px">
                <i class="ti ti-clock" style="font-size:11px"></i> {{ $n->created_at->diffForHumans() }}
            </div>
        </div>
        @if(!$n->is_read)
            <div style="width:8px;height:8px;background:var(--green);border-radius:50%;flex-shrink:0;margin-top:6px"></div>
        @endif
    </div>
    @empty
        <div class="text-center py-5">
            <div style="font-size:36px">🔔</div>
            <div style="font-size:13px;color:var(--text-muted);margin-top:8px">No notifications yet.</div>
        </div>
    @endforelse
</div>

<div class="mt-3">{{ $notifications->links() }}</div>
@endsection
