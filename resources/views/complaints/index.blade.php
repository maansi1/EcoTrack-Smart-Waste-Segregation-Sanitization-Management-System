{{-- resources/views/complaints/index.blade.php --}}
@extends('layouts.app')
@section('title','Complaints')
@section('page-title','Complaint Management')
@section('page-sub','All submitted waste complaints')

@section('content')

{{-- Filters --}}
<div class="swms-card mb-3">
    <form method="GET" action="{{ route('complaints.index') }}" class="d-flex flex-wrap gap-2 align-items-end">
        <div>
            <label class="swms-label">Search</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="swms-input" style="width:220px" placeholder="ID, title, area...">
        </div>
        <div>
            <label class="swms-label">Status</label>
            <select name="status" class="swms-select" style="width:140px">
                <option value="">All statuses</option>
                <option value="pending"     {{ request('status') == 'pending'     ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="resolved"    {{ request('status') == 'resolved'    ? 'selected' : '' }}>Resolved</option>
                <option value="closed"      {{ request('status') == 'closed'      ? 'selected' : '' }}>Closed</option>
            </select>
        </div>
        <div>
            <label class="swms-label">Category</label>
            <select name="category" class="swms-select" style="width:140px">
                <option value="">All categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="swms-label">Area</label>
            <select name="area" class="swms-select" style="width:140px">
                <option value="">All areas</option>
                @foreach($areas as $area)
                    <option value="{{ $area }}" {{ request('area') == $area ? 'selected' : '' }}>{{ $area }}</option>
                @endforeach
            </select>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn-swms-primary"><i class="ti ti-search"></i> Filter</button>
            <a href="{{ route('complaints.index') }}" class="btn-swms-secondary">Reset</a>
        </div>
        @if(auth()->user()->isUser())
            <a href="{{ route('complaints.create') }}" class="btn-swms-primary ms-auto">
                <i class="ti ti-plus"></i> New complaint
            </a>
        @endif
    </form>
</div>

<div class="swms-card">
    {{-- Summary pills --}}
    <div class="d-flex gap-2 mb-3 flex-wrap">
        <span class="badge-pending">Pending: {{ $complaints->where('status','pending')->count() }}</span>
        <span class="badge-progress">In Progress: {{ $complaints->where('status','in_progress')->count() }}</span>
        <span class="badge-resolved">Resolved: {{ $complaints->where('status','resolved')->count() }}</span>
    </div>

    <div class="table-responsive">
        <table class="swms-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Title</th>
                    <th>Area</th>
                    @if(!auth()->user()->isUser()) <th>Reported by</th> @endif
                    <th>Date</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($complaints as $c)
                <tr>
                    <td class="complaint-id">#{{ $c->complaint_number }}</td>
                    <td>{{ $c->wasteCategory->icon ?? '' }} {{ $c->wasteCategory->name }}</td>
                    <td>{{ Str::limit($c->title, 40) }}</td>
                    <td>{{ $c->area }}</td>
                    @if(!auth()->user()->isUser()) <td>{{ $c->user->name }}</td> @endif
                    <td>{{ $c->created_at->format('d M Y') }}</td>
                    <td>{!! $c->priority_badge !!}</td>
                    <td>{!! $c->status_badge !!}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('complaints.show', $c) }}" class="btn-swms-secondary" style="padding:4px 10px;font-size:12px">
                                <i class="ti ti-eye"></i>
                            </a>
                            @if(auth()->user()->isAdmin())
                            <button class="btn-swms-danger" style="padding:4px 10px;font-size:12px"
                                    onclick="if(confirm('Delete this complaint?')) document.getElementById('del-{{ $c->id }}').submit()">
                                <i class="ti ti-trash"></i>
                            </button>
                            <form id="del-{{ $c->id }}" method="POST" action="{{ route('admin.complaints.destroy', $c) }}" class="d-none">
                                @csrf @method('DELETE')
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-5" style="color:var(--text-muted)">
                        <div style="font-size:32px">📋</div>
                        No complaints found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-3">
        <div style="font-size:12px;color:var(--text-muted)">
            Showing {{ $complaints->firstItem() }}–{{ $complaints->lastItem() }} of {{ $complaints->total() }} complaints
        </div>
        {{ $complaints->links() }}
    </div>
</div>
@endsection
