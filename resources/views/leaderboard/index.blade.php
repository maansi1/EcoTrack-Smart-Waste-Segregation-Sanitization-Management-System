{{-- resources/views/leaderboard/index.blade.php --}}
@extends('layouts.app')
@section('title','Leaderboard')
@section('page-title','Cleanliness Leaderboard')
@section('page-sub','Top contributors to campus cleanliness this month')

@section('content')
<div class="row g-4">
    <div class="col-md-7">
        <div class="swms-card">
            <div class="card-title mb-4"><i class="ti ti-trophy"></i> Top reporters this month</div>

            @foreach($topUsers as $i => $u)
            <div class="lb-item {{ $i === 0 ? 'bg-green-light' : '' }}" style="{{ $i < 3 ? 'background:'.['#fffbeb','#f8f9fa','#fff5f0'][$i] : '' }}">
                <div class="lb-rank {{ ['gold','silver','bronze'][$i] ?? '' }}">{{ $i + 1 }}</div>
                <div class="lb-avatar" style="background:{{ ['#f0a500','#aaa','#cd7f32','#2da563','#1a5fb4','#0f7173','#c0392b'][$i % 7] }}">
                    {{ strtoupper(substr($u->name, 0, 2)) }}
                </div>
                <div class="flex-fill">
                    <div style="font-size:13px;font-weight:500">
                        {{ $u->name }}
                        @if($u->id === auth()->id()) <span style="font-size:10px;background:var(--green-light);color:var(--green);padding:1px 6px;border-radius:8px;margin-left:4px">You</span> @endif
                    </div>
                    <div class="lb-pts">{{ $u->complaints()->count() }} complaints · {{ $u->feedback()->count() }} ratings</div>
                </div>
                <div class="text-end">
                    <div class="lb-score">{{ $u->points }} pts</div>
                    @if($i < 3)
                        <div style="font-size:10px">{{ ['🥇 Champion','🥈 Silver','🥉 Bronze'][$i] }}</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="col-md-5">
        <div class="swms-card mb-3">
            <div class="card-title mb-1"><i class="ti ti-medal"></i> Your ranking</div>
            <div class="text-center py-3">
                <div style="font-size:11px;color:var(--text-muted)">Your cleanliness score</div>
                <div style="font-size:48px;font-weight:700;font-family:'Space Mono',monospace;color:var(--green)">{{ $myPoints }}</div>
                <div style="font-size:13px;color:var(--text-muted);margin-bottom:14px">points</div>
                <div class="btn-swms-primary" style="cursor:default">
                    <i class="ti ti-trophy"></i> Rank #{{ $myRank }}
                </div>
            </div>
            <div style="background:var(--green-light);border-radius:8px;padding:12px;font-size:12px;color:var(--green)">
                <strong>How to earn points:</strong><br>
                +10 pts — Submit a complaint<br>
                +20 pts — Complaint verified & resolved<br>
                +5 pts — Submit feedback or rating
            </div>
        </div>

        <div class="swms-card">
            <div class="card-title mb-3"><i class="ti ti-award"></i> Milestones</div>
            @foreach([
                ['pts'=>50,  'badge'=>'🌱 Eco Starter',   'desc'=>'First 50 points'],
                ['pts'=>200, 'badge'=>'♻️ Green Reporter', 'desc'=>'200 points'],
                ['pts'=>500, 'badge'=>'🏆 Eco Champion',  'desc'=>'500 points'],
                ['pts'=>1000,'badge'=>'🌟 Campus Hero',   'desc'=>'1000 points'],
            ] as $m)
            <div class="d-flex align-items-center gap-3 py-2 border-bottom">
                <div style="font-size:20px">{{ explode(' ',$m['badge'])[0] }}</div>
                <div class="flex-fill">
                    <div style="font-size:13px;font-weight:500">{{ implode(' ', array_slice(explode(' ',$m['badge']),1)) }}</div>
                    <div style="font-size:11px;color:var(--text-muted)">{{ $m['desc'] }}</div>
                </div>
                @if($myPoints >= $m['pts'])
                    <span class="badge-resolved">Earned ✓</span>
                @else
                    <span style="font-size:11px;color:var(--text-muted)">{{ $m['pts'] - $myPoints }} pts to go</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
