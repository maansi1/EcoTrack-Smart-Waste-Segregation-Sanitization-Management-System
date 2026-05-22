{{-- resources/views/feedback/index.blade.php --}}
@extends('layouts.app')
@section('title','Feedback')
@section('page-title','Feedback & Ratings')
@section('page-sub','Rate resolutions and area cleanliness')

@section('content')
<div class="row g-4">
    {{-- Rate a resolved complaint --}}
    <div class="col-md-6">
        <div class="swms-card">
            <div class="card-title mb-3"><i class="ti ti-star"></i> Rate a complaint resolution</div>
            @if($resolvedComplaints->count())
            <form method="POST" action="{{ route('feedback.store') }}">
                @csrf
                <input type="hidden" name="type" value="complaint">
                <div class="mb-3">
                    <label class="swms-label">Select complaint</label>
                    <select name="complaint_id" class="swms-select" required>
                        <option value="">Choose resolved complaint...</option>
                        @foreach($resolvedComplaints as $c)
                            <option value="{{ $c->id }}">#{{ $c->complaint_number }} — {{ Str::limit($c->title,40) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="swms-label">Your rating</label>
                    <div class="star-rating mb-1" id="stars">
                        @for($i=1;$i<=5;$i++)
                            <span class="star" data-val="{{ $i }}" onclick="setStar({{ $i }})">★</span>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" value="0">
                    <div style="font-size:11px;color:var(--text-muted)">Click to rate</div>
                </div>
                <div class="mb-3">
                    <label class="swms-label">Comment</label>
                    <textarea name="comment" class="swms-textarea" rows="3" placeholder="Share your experience..."></textarea>
                </div>
                <button type="submit" class="btn-swms-primary"><i class="ti ti-send"></i> Submit feedback (+5 pts)</button>
            </form>
            @else
                <p style="font-size:13px;color:var(--text-muted)">No resolved complaints to rate right now.</p>
            @endif
        </div>
    </div>

    {{-- Rate area cleanliness --}}
    <div class="col-md-6">
        <div class="swms-card">
            <div class="card-title mb-3"><i class="ti ti-map-pin"></i> Rate area cleanliness</div>
            <form method="POST" action="{{ route('feedback.store') }}">
                @csrf
                <input type="hidden" name="type" value="area_cleanliness">
                <input type="hidden" name="rating" id="areaRating" value="0">
                <div class="mb-3">
                    <label class="swms-label">Select area</label>
                    <select name="area" class="swms-select" required>
                        <option value="">Choose area...</option>
                        @foreach(['Main Canteen','Block A','Hostel Area','Library','Sports Ground','Admin Block','Parking Zone'] as $a)
                            <option value="{{ $a }}">{{ $a }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="swms-label">How clean is this area today?</label>
                    <div class="star-rating mb-1" id="areaStars">
                        @for($i=1;$i<=5;$i++)
                            <span class="star" data-val="{{ $i }}" onclick="setAreaStar({{ $i }})">★</span>
                        @endfor
                    </div>
                </div>
                <div class="mb-3">
                    <label class="swms-label">Comment</label>
                    <textarea name="comment" class="swms-textarea" rows="2" placeholder="Anything specific to note?"></textarea>
                </div>
                <button type="submit" class="btn-swms-primary"><i class="ti ti-send"></i> Submit rating (+5 pts)</button>
            </form>
        </div>
    </div>

    {{-- My past feedback --}}
    @if($myFeedback->count())
    <div class="col-12">
        <div class="swms-card">
            <div class="card-title mb-3"><i class="ti ti-history"></i> My feedback history</div>
            <div class="table-responsive">
                <table class="swms-table">
                    <thead><tr><th>Type</th><th>Reference</th><th>Rating</th><th>Comment</th><th>Date</th></tr></thead>
                    <tbody>
                        @foreach($myFeedback as $f)
                        <tr>
                            <td>{{ ucfirst(str_replace('_',' ',$f->type)) }}</td>
                            <td>
                                @if($f->complaint) <span class="complaint-id">#{{ $f->complaint->complaint_number }}</span>
                                @elseif($f->area) {{ $f->area }}
                                @else — @endif
                            </td>
                            <td>
                                <span style="color:var(--amber)">{{ str_repeat('★',$f->rating) }}</span>
                                <span style="color:var(--border)">{{ str_repeat('★',5-$f->rating) }}</span>
                            </td>
                            <td style="font-size:12px">{{ Str::limit($f->comment,50) ?? '—' }}</td>
                            <td style="font-size:12px">{{ $f->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $myFeedback->links() }}</div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function setStar(n) {
    document.getElementById('ratingInput').value = n;
    document.querySelectorAll('#stars .star').forEach((s,i) => s.classList.toggle('filled', i < n));
}
function setAreaStar(n) {
    document.getElementById('areaRating').value = n;
    document.querySelectorAll('#areaStars .star').forEach((s,i) => s.classList.toggle('filled', i < n));
}
</script>
@endpush
