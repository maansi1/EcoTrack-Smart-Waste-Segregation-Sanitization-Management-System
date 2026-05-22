{{-- resources/views/sanitization/index.blade.php --}}
@extends('layouts.app')
@section('title','Sanitization')
@section('page-title','Sanitization Management')
@section('page-sub','Schedule and track sanitization tasks')

@section('content')

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon green"><i class="ti ti-calendar-check"></i></div>
            <div class="metric-val">{{ $todayTasks->count() }}</div>
            <div class="metric-label">Today's tasks</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon blue"><i class="ti ti-chart-bar"></i></div>
            <div class="metric-val">{{ $completionRate }}%</div>
            <div class="metric-label">Overall completion</div>
        </div>
    </div>
</div>

<div class="row g-4">
    @if(auth()->user()->isAdmin())
    <div class="col-md-4">
        <div class="swms-card">
            <div class="card-title mb-3"><i class="ti ti-calendar-plus"></i> Schedule new task</div>
            <form method="POST" action="{{ route('sanitization.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="swms-label">Area <span class="text-danger">*</span></label>
                    <select name="area" class="swms-select" required>
                        <option value="">Select area...</option>
                        @foreach(['Main Canteen','Block A','Block B','Hostel Area','Library','Sports Ground','Admin Block','Parking'] as $a)
                            <option value="{{ $a }}">{{ $a }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="swms-label">Task type <span class="text-danger">*</span></label>
                    <select name="task_type" class="swms-select" required>
                        @foreach(['Floor sanitization','Bin cleaning','Drain clearing','Area disinfection','Pathway sweeping','Deep cleaning'] as $t)
                            <option value="{{ $t }}">{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="swms-label">Date <span class="text-danger">*</span></label>
                        <input type="date" name="scheduled_date" class="swms-input"
                               value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-6">
                        <label class="swms-label">Time <span class="text-danger">*</span></label>
                        <input type="time" name="scheduled_time" class="swms-input" value="08:00" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="swms-label">Assign to staff <span class="text-danger">*</span></label>
                    <select name="assigned_to" class="swms-select" required>
                        <option value="">Select staff...</option>
                        @foreach($staffList as $staff)
                            <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="swms-label">Notes</label>
                    <textarea name="description" class="swms-textarea" rows="2" placeholder="Additional instructions..."></textarea>
                </div>
                <button type="submit" class="btn-swms-primary w-100 justify-content-center">
                    <i class="ti ti-calendar-plus"></i> Schedule task
                </button>
            </form>
        </div>
    </div>
    @endif

    <div class="{{ auth()->user()->isAdmin() ? 'col-md-8' : 'col-12' }}">
        <div class="swms-card">
            <div class="card-title mb-3"><i class="ti ti-list-check"></i> Task log</div>
            <div class="table-responsive">
                <table class="swms-table">
                    <thead>
                        <tr>
                            <th>Area</th>
                            <th>Type</th>
                            <th>Staff</th>
                            <th>Scheduled</th>
                            <th>Progress</th>
                            <th>Status</th>
                            @if(auth()->user()->isStaff()) <th>Update</th> @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                        <tr>
                            <td>{{ $task->area }}</td>
                            <td>{{ $task->task_type }}</td>
                            <td>{{ $task->assignedStaff->name }}</td>
                            <td>{{ $task->scheduled_date->format('d M') }} {{ $task->scheduled_time }}</td>
                            <td style="width:100px">
                                <div class="swms-progress">
                                    <div class="swms-progress-fill" style="width:{{ $task->completion_percent }}%"></div>
                                </div>
                                <div style="font-size:10px;color:var(--text-muted);margin-top:2px">{{ $task->completion_percent }}%</div>
                            </td>
                            <td>
                                <span class="@if($task->status == 'completed') badge-resolved @elseif($task->status == 'in_progress') badge-progress @elseif($task->status == 'cancelled') badge-closed @else badge-pending @endif">
                                    {{ ucfirst(str_replace('_',' ',$task->status)) }}
                                </span>
                            </td>
                            @if(auth()->user()->isStaff() && $task->assigned_to == auth()->id())
                            <td>
                                <form method="POST" action="{{ route('sanitization.update', $task) }}" class="d-flex gap-1">
                                    @csrf @method('PUT')
                                    <select name="status" class="swms-select" style="width:110px;padding:4px 6px;font-size:11px" onchange="this.form.submit()">
                                        <option value="pending"     {{ $task->status == 'pending'     ? 'selected' : '' }}>Pending</option>
                                        <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed"   {{ $task->status == 'completed'   ? 'selected' : '' }}>Completed</option>
                                    </select>
                                    <input type="hidden" name="completion_percent" value="{{ $task->status == 'completed' ? 100 : 50 }}">
                                </form>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-4" style="color:var(--text-muted)">No tasks found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $tasks->links() }}</div>
        </div>
    </div>
</div>
@endsection
