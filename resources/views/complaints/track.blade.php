{{-- resources/views/complaints/track.blade.php --}}
@extends('layouts.app')
@section('title','Track Complaint')
@section('page-title','Track Complaint Status')
@section('page-sub','Enter your complaint ID to see real-time updates')

@section('content')
<div class="swms-card mb-4" style="max-width:500px">
    <form method="GET" action="{{ route('complaints.track') }}" class="d-flex gap-2">
        <input type="text" name="number" value="{{ request('number') }}"
               class="swms-input flex-fill" placeholder="Enter complaint ID (e.g. C-0001)" required>
        <button type="submit" class="btn-swms-primary">
            <i class="ti ti-search"></i> Track
        </button>
    </form>
    @error('number')
        <div class="text-danger mt-2" style="font-size:12px"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
    @enderror
</div>

@if($complaint)
<div class="swms-card" style="max-width:560px">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="complaint-id mb-1">#{{ $complaint->complaint_number }}</div>
            <h5 class="fw-600 mb-1">{{ $complaint->title }}</h5>
            <div style="font-size:12px;color:var(--text-muted)">
                <i class="ti ti-recycle"></i> {{ $complaint->wasteCategory->name }}
                &nbsp;·&nbsp;<i class="ti ti-map-pin"></i> {{ $complaint->area }}
            </div>
        </div>
        {!! $complaint->status_badge !!}
    </div>

    <div class="tl-wrap">
        @php
            $steps = [
                ['label' => 'Complaint submitted',       'date' => $complaint->created_at->format('d M Y, g:i A'),   'state' => 'done'],
                ['label' => 'Reviewed by admin',         'date' => in_array($complaint->status,['in_progress','resolved','closed']) ? 'Completed' : 'Pending', 'state' => in_array($complaint->status,['in_progress','resolved','closed']) ? 'done' : 'pending'],
                ['label' => 'Assigned to staff',         'date' => $complaint->assignedStaff ? 'Assigned to ' . $complaint->assignedStaff->name : 'Pending',   'state' => $complaint->assigned_to ? ($complaint->status == 'in_progress' ? 'active' : 'done') : 'pending'],
                ['label' => 'Waste cleared & resolved',  'date' => $complaint->resolved_at ? $complaint->resolved_at->format('d M Y') : 'Pending',             'state' => in_array($complaint->status,['resolved','closed']) ? 'done' : 'pending'],
            ];
        @endphp
        @foreach($steps as $i => $step)
        <div class="tl-item">
            <div class="tl-spine">
                <div class="tl-dot {{ $step['state'] }}"></div>
                @if($i < count($steps)-1) <div class="tl-line"></div> @endif
            </div>
            <div>
                <div class="tl-text" style="{{ $step['state'] === 'pending' ? 'color:var(--text-muted)' : '' }}">
                    {{ $step['label'] }}
                </div>
                <div class="tl-date">{{ $step['date'] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    @if($complaint->resolution_notes)
    <div class="seg-result mt-3">
        <div style="font-size:12px;font-weight:600;color:var(--green);margin-bottom:4px">Resolution notes</div>
        <div class="seg-tip">{{ $complaint->resolution_notes }}</div>
    </div>
    @endif
</div>
@endif
@endsection
