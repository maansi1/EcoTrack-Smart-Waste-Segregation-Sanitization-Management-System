{{-- resources/views/user/dashboard.blade.php --}}
@extends('layouts.app')
@section('title','My Dashboard')
@section('page-title','My Dashboard')
@section('page-sub','Your waste reporting activity and points')

@section('content')

{{-- ── Metrics ──────────────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon green"><i class="ti ti-file-text"></i></div>
            <div class="metric-val">{{ $stats['my_complaints'] }}</div>
            <div class="metric-label">My complaints</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon amber"><i class="ti ti-clock"></i></div>
            <div class="metric-val">{{ $stats['pending'] }}</div>
            <div class="metric-label">Pending</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon blue"><i class="ti ti-circle-check"></i></div>
            <div class="metric-val">{{ $stats['resolved'] }}</div>
            <div class="metric-label">Resolved</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon green"><i class="ti ti-trophy"></i></div>
            <div class="metric-val mono">{{ $stats['my_points'] }}</div>
            <div class="metric-label">My points</div>
            <div class="metric-trend up">Rank #{{ $stats['rank'] }}</div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Recent complaints --}}
    <div class="col-md-7">
        <div class="swms-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="card-title mb-0"><i class="ti ti-list-check"></i> My recent complaints</div>
                <a href="{{ route('complaints.create') }}" class="btn-swms-primary" style="padding:6px 14px;font-size:12px">
                    <i class="ti ti-plus"></i> New
                </a>
            </div>
            @forelse($myComplaints as $c)
            <a href="{{ route('complaints.show', $c) }}" class="d-flex align-items-start gap-2 py-2 border-bottom text-decoration-none">
                <div style="font-size:22px">{{ $c->wasteCategory->icon ?? '🗑️' }}</div>
                <div class="flex-fill">
                    <div class="complaint-id">#{{ $c->complaint_number }}</div>
                    <div style="font-size:13px;font-weight:500;color:var(--text-main)">{{ $c->title }}</div>
                    <div style="font-size:11px;color:var(--text-muted)">{{ $c->area }} · {{ $c->created_at->diffForHumans() }}</div>
                </div>
                {!! $c->status_badge !!}
            </a>
            @empty
                <div class="text-center py-4">
                    <div style="font-size:32px">🗑️</div>
                    <div style="font-size:13px;color:var(--text-muted);margin-top:6px">No complaints yet.<br>Spot something dirty? Report it!</div>
                    <a href="{{ route('complaints.create') }}" class="btn-swms-primary mt-3" style="font-size:13px">Submit first complaint</a>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Leaderboard sidebar --}}
    <div class="col-md-5">
        <div class="swms-card mb-3">
            <div class="card-title mb-1"><i class="ti ti-trophy"></i> Your score</div>
            <div class="text-center py-3">
                <div style="font-size:11px;color:var(--text-muted)">Cleanliness points</div>
                <div style="font-size:44px;font-weight:700;font-family:'Space Mono',monospace;color:var(--green)">
                    {{ $stats['my_points'] }}
                </div>
                <div style="font-size:13px;color:var(--text-muted);margin-bottom:12px">points earned</div>
                <span class="btn-swms-primary">
                    <i class="ti ti-medal"></i> Rank #{{ $stats['rank'] }}
                </span>
            </div>
            <div style="background:var(--green-light);border-radius:8px;padding:10px 12px;font-size:12px;color:var(--green);margin-top:4px">
                🎯 Keep reporting to climb the leaderboard!<br>
                <div class="swms-progress mt-2"><div class="swms-progress-fill" style="width:{{ min(($stats['my_points'] % 100), 100) }}%"></div></div>
            </div>
        </div>
        <div class="swms-card">
            <div class="card-title mb-3"><i class="ti ti-medal"></i> Top contributors</div>
            @foreach($leaderboard as $i => $u)
            <div class="lb-item {{ $i === 0 ? 'bg-green-light' : '' }}">
                <div class="lb-rank {{ ['gold','silver','bronze'][$i] ?? '' }}">{{ $i + 1 }}</div>
                <div class="lb-avatar" style="background:{{ ['#f0a500','#aaa','#cd7f32','#2da563','#1a5fb4'][$i] ?? '#2da563' }}">
                    {{ strtoupper(substr($u->name, 0, 2)) }}
                </div>
                <div class="flex-fill">
                    <div style="font-size:13px;font-weight:500">{{ $u->name }}</div>
                    <div class="lb-pts">{{ $u->complaints_count ?? 0 }} reports</div>
                </div>
                <div class="lb-score">{{ $u->points }} pts</div>
            </div>
            @endforeach
            <a href="{{ route('leaderboard') }}" class="btn-swms-secondary w-100 justify-content-center mt-2" style="font-size:12px">
                View full leaderboard
            </a>
        </div>
    </div>
</div>

@endsection
