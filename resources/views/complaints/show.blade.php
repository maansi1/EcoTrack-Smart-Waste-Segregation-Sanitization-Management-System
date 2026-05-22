{{-- resources/views/complaints/show.blade.php --}}
@extends('layouts.app')
@section('title','Complaint #' . $complaint->complaint_number)
@section('page-title','Complaint Details')
@section('page-sub','#' . $complaint->complaint_number . ' — ' . $complaint->title)

@section('content')
<div class="row g-4">

    {{-- Left: complaint info + timeline --}}
    <div class="col-md-7">
        <div class="swms-card mb-3">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="complaint-id mb-1">#{{ $complaint->complaint_number }}</div>
                    <h5 class="mb-1 fw-600">{{ $complaint->title }}</h5>
                    <div style="font-size:12px;color:var(--text-muted)">
                        <i class="ti ti-map-pin" style="font-size:12px"></i> {{ $complaint->area }}
                        @if($complaint->building) · {{ $complaint->building }} @endif
                        @if($complaint->specific_location) · {{ $complaint->specific_location }} @endif
                    </div>
                </div>
                <div class="d-flex flex-column gap-1 align-items-end">
                    {!! $complaint->status_badge !!}
                    {!! $complaint->priority_badge !!}
                </div>
            </div>

            <div class="row g-2 mb-3" style="font-size:12px;color:var(--text-muted)">
                <div class="col-6"><i class="ti ti-user"></i> Reported by: <strong>{{ $complaint->user->name }}</strong></div>
                <div class="col-6"><i class="ti ti-calendar"></i> Date: <strong>{{ $complaint->created_at->format('d M Y, g:i A') }}</strong></div>
                <div class="col-6"><i class="ti ti-recycle"></i> Category: <strong>{{ $complaint->wasteCategory->icon }} {{ $complaint->wasteCategory->name }}</strong></div>
                @if($complaint->assignedStaff)
                <div class="col-6"><i class="ti ti-user-check"></i> Assigned to: <strong>{{ $complaint->assignedStaff->name }}</strong></div>
                @endif
                @if($complaint->resolved_at)
                <div class="col-6"><i class="ti ti-clock"></i> Resolved: <strong>{{ $complaint->resolved_at->format('d M Y') }}</strong></div>
                @endif
            </div>

            <div class="mb-3">
                <label class="swms-label">Description</label>
                <p style="font-size:13px;color:var(--text-main);margin:0">{{ $complaint->description }}</p>
            </div>

            @if($complaint->resolution_notes)
            <div class="mb-3" style="background:var(--green-light);border-radius:8px;padding:12px">
                <div class="swms-label mb-1"><i class="ti ti-message-check"></i> Resolution notes</div>
                <p style="font-size:13px;color:var(--green);margin:0">{{ $complaint->resolution_notes }}</p>
            </div>
            @endif

            @if($complaint->image_path)
            <div>
                <label class="swms-label">Uploaded image</label>
                <img src="{{ Storage::url($complaint->image_path) }}"
                     style="width:100%;border-radius:10px;max-height:260px;object-fit:cover">
            </div>
            @endif
        </div>

        {{-- Timeline --}}
        <div class="swms-card">
            <div class="card-title mb-3"><i class="ti ti-timeline"></i> Complaint timeline</div>
            <div class="tl-wrap">
                <div class="tl-item">
                    <div class="tl-spine"><div class="tl-dot done"></div><div class="tl-line"></div></div>
                    <div><div class="tl-text">Complaint submitted</div><div class="tl-date">{{ $complaint->created_at->format('d M Y, g:i A') }} by {{ $complaint->user->name }}</div></div>
                </div>
                <div class="tl-item">
                    <div class="tl-spine">
                        <div class="tl-dot {{ in_array($complaint->status, ['in_progress','resolved','closed']) ? 'done' : 'pending' }}"></div>
                        <div class="tl-line"></div>
                    </div>
                    <div>
                        <div class="tl-text" style="{{ !in_array($complaint->status, ['in_progress','resolved','closed']) ? 'color:var(--text-muted)' : '' }}">Reviewed by admin</div>
                        <div class="tl-date">{{ in_array($complaint->status, ['in_progress','resolved','closed']) ? 'Completed' : 'Pending' }}</div>
                    </div>
                </div>
                <div class="tl-item">
                    <div class="tl-spine">
                        <div class="tl-dot {{ in_array($complaint->status, ['in_progress','resolved','closed']) ? ($complaint->status == 'in_progress' ? 'active' : 'done') : 'pending' }}"></div>
                        <div class="tl-line"></div>
                    </div>
                    <div>
                        <div class="tl-text" style="{{ !in_array($complaint->status, ['in_progress','resolved','closed']) ? 'color:var(--text-muted)' : '' }}">Assigned to staff</div>
                        <div class="tl-date">
                            @if($complaint->assignedStaff)
                                Assigned to {{ $complaint->assignedStaff->name }}
                            @else Pending assignment @endif
                        </div>
                    </div>
                </div>
                <div class="tl-item">
                    <div class="tl-spine">
                        <div class="tl-dot {{ in_array($complaint->status, ['resolved','closed']) ? 'done' : 'pending' }}"></div>
                    </div>
                    <div>
                        <div class="tl-text" style="{{ !in_array($complaint->status, ['resolved','closed']) ? 'color:var(--text-muted)' : '' }}">Waste cleared &amp; resolved</div>
                        <div class="tl-date">
                            @if($complaint->resolved_at) {{ $complaint->resolved_at->format('d M Y') }}
                            @else Pending @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Admin actions / Feedback --}}
    <div class="col-md-5">

        @if(auth()->user()->isAdmin())
        {{-- Admin update form --}}
        <div class="swms-card mb-3">
            <div class="card-title mb-3"><i class="ti ti-settings"></i> Update complaint</div>
            <form method="POST" action="{{ route('admin.complaints.update', $complaint) }}">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="swms-label">Status</label>
                    <select name="status" class="swms-select">
                        <option value="pending"     {{ $complaint->status == 'pending'     ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ $complaint->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved"    {{ $complaint->status == 'resolved'    ? 'selected' : '' }}>Resolved</option>
                        <option value="closed"      {{ $complaint->status == 'closed'      ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="swms-label">Assign to staff</label>
                    <select name="assigned_to" class="swms-select">
                        <option value="">-- Unassigned --</option>
                        @foreach(\App\Models\User::staff()->active()->get() as $staff)
                            <option value="{{ $staff->id }}" {{ $complaint->assigned_to == $staff->id ? 'selected' : '' }}>
                                {{ $staff->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="swms-label">Priority</label>
                    <select name="priority" class="swms-select">
                        <option value="normal" {{ $complaint->priority == 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="high"   {{ $complaint->priority == 'high'   ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ $complaint->priority == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="swms-label">Resolution notes</label>
                    <textarea name="resolution_notes" class="swms-textarea" rows="3"
                              placeholder="Notes on how this was resolved...">{{ $complaint->resolution_notes }}</textarea>
                </div>
                <button type="submit" class="btn-swms-primary w-100 justify-content-center">
                    <i class="ti ti-device-floppy"></i> Save changes
                </button>
            </form>
        </div>
        @endif

        {{-- Disposal guidance --}}
        <div class="swms-card mb-3">
            <div class="card-title mb-2"><i class="ti ti-bulb"></i> Disposal guidance</div>
            <div class="seg-result">
                <div class="seg-type">{{ $complaint->wasteCategory->icon }} {{ $complaint->wasteCategory->name }}</div>
                @foreach(explode("\n", $complaint->wasteCategory->disposal_instructions) as $line)
                    @if(trim($line))<div class="seg-tip">{{ trim($line) }}</div>@endif
                @endforeach
            </div>
        </div>

        {{-- Feedback (user, resolved only) --}}
        @if(auth()->user()->isUser() && $complaint->status === 'resolved' && !$complaint->feedback)
        <div class="swms-card">
            <div class="card-title mb-3"><i class="ti ti-star"></i> Rate this resolution</div>
            <form method="POST" action="{{ route('feedback.store') }}">
                @csrf
                <input type="hidden" name="complaint_id" value="{{ $complaint->id }}">
                <input type="hidden" name="type" value="complaint">
                <div class="mb-3">
                    <label class="swms-label">Your rating</label>
                    <div class="star-rating" id="stars">
                        @for($i=1;$i<=5;$i++)
                            <span class="star" data-val="{{ $i }}" onclick="setStar({{ $i }})">★</span>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" value="0">
                </div>
                <div class="mb-3">
                    <label class="swms-label">Comment (optional)</label>
                    <textarea name="comment" class="swms-textarea" rows="3" placeholder="Share your experience..."></textarea>
                </div>
                <button type="submit" class="btn-swms-primary w-100 justify-content-center">
                    <i class="ti ti-send"></i> Submit feedback
                </button>
            </form>
        </div>
        @elseif($complaint->feedback)
        <div class="swms-card">
            <div class="card-title mb-2"><i class="ti ti-star"></i> Your feedback</div>
            <div class="star-rating mb-2">
                @for($i=1;$i<=5;$i++)<span class="star {{ $i <= $complaint->feedback->rating ? 'filled' : '' }}">★</span>@endfor
            </div>
            @if($complaint->feedback->comment)
                <p style="font-size:13px;color:var(--text-muted)">{{ $complaint->feedback->comment }}</p>
            @endif
        </div>
        @endif

    </div>
</div>

<div class="mt-3">
    <a href="{{ route('complaints.index') }}" class="btn-swms-secondary">
        <i class="ti ti-arrow-left"></i> Back to complaints
    </a>
</div>
@endsection

@push('scripts')
<script>
function setStar(n) {
    document.getElementById('ratingInput').value = n;
    document.querySelectorAll('.star').forEach((s, i) => {
        s.classList.toggle('filled', i < n);
    });
}
</script>
@endpush
