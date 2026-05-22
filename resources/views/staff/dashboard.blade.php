{{-- resources/views/staff/dashboard.blade.php --}}
@extends('layouts.app')
@section('title','Staff Dashboard')
@section('page-title','Staff Dashboard')
@section('page-sub','Your assigned tasks and complaints for today')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon green"><i class="ti ti-list-check"></i></div>
            <div class="metric-val">{{ $stats['tasks_today'] }}</div>
            <div class="metric-label">Tasks today</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon blue"><i class="ti ti-circle-check"></i></div>
            <div class="metric-val">{{ $stats['completed_tasks'] }}</div>
            <div class="metric-label">Completed total</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon amber"><i class="ti ti-file-alert"></i></div>
            <div class="metric-val">{{ $stats['assigned_complaints'] }}</div>
            <div class="metric-label">Assigned complaints</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon red"><i class="ti ti-bell"></i></div>
            <div class="metric-val">{{ $stats['unread_notifs'] }}</div>
            <div class="metric-label">Unread notifications</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="swms-card">
            <div class="card-title mb-3"><i class="ti ti-calendar-check"></i> Today's sanitization tasks</div>
            @forelse($myTasks as $task)
            <div class="d-flex align-items-start gap-3 py-2 border-bottom">
                <div class="flex-fill">
                    <div style="font-size:13px;font-weight:500">{{ $task->task_type }}</div>
                    <div style="font-size:11px;color:var(--text-muted)">
                        <i class="ti ti-map-pin" style="font-size:11px"></i> {{ $task->area }}
                        &nbsp;·&nbsp;<i class="ti ti-clock" style="font-size:11px"></i> {{ $task->scheduled_time }}
                    </div>
                    <div class="swms-progress mt-2" style="width:120px">
                        <div class="swms-progress-fill" style="width:{{ $task->completion_percent }}%"></div>
                    </div>
                </div>
                <form method="POST" action="{{ route('sanitization.update', $task) }}">
                    @csrf @method('PUT')
                    <select name="status" class="swms-select" style="width:120px;padding:5px 8px;font-size:12px" onchange="this.form.submit()">
                        <option value="pending"     {{ $task->status == 'pending'     ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed"   {{ $task->status == 'completed'   ? 'selected' : '' }}>Completed</option>
                    </select>
                    <input type="hidden" name="completion_percent" value="{{ $task->status === 'completed' ? 100 : $task->completion_percent }}">
                </form>
            </div>
            @empty
                <p style="font-size:13px;color:var(--text-muted)">No tasks scheduled for today.</p>
            @endforelse
        </div>
    </div>
    <div class="col-md-6">
        <div class="swms-card">
            <div class="card-title mb-3"><i class="ti ti-file-text"></i> Assigned complaints</div>
            @forelse($myComplaints as $c)
            <a href="{{ route('complaints.show', $c) }}" class="d-flex align-items-start gap-2 py-2 border-bottom text-decoration-none">
                <div style="font-size:20px">{{ $c->wasteCategory->icon ?? '🗑️' }}</div>
                <div class="flex-fill">
                    <div class="complaint-id">#{{ $c->complaint_number }}</div>
                    <div style="font-size:13px;font-weight:500;color:var(--text-main)">{{ $c->title }}</div>
                    <div style="font-size:11px;color:var(--text-muted)">{{ $c->area }}</div>
                </div>
                {!! $c->priority_badge !!}
            </a>
            @empty
                <p style="font-size:13px;color:var(--text-muted)">No complaints assigned.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
