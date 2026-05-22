{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')
@section('title','Admin Dashboard')
@section('page-title','Dashboard')
@section('page-sub','System-wide overview — Smart Waste Management')

@section('content')

{{-- Bin overflow alert --}}
@if($fullBins->count())
<div class="alert-danger-swms mb-4">
    <i class="ti ti-alert-triangle" style="font-size:18px"></i>
    <strong>{{ $fullBins->count() }} bin(s) full / overflowing:</strong>
    {{ $fullBins->pluck('name')->join(', ') }} — immediate collection required.
</div>
@endif

{{-- ── Metrics ──────────────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon green"><i class="ti ti-file-text"></i></div>
            <div class="metric-val">{{ $stats['total_complaints'] }}</div>
            <div class="metric-label">Total complaints</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon blue"><i class="ti ti-circle-check"></i></div>
            <div class="metric-val">{{ $stats['resolved'] }}</div>
            <div class="metric-label">Resolved</div>
            @if($stats['total_complaints'])
                <div class="metric-trend up">
                    <i class="ti ti-arrow-up"></i>
                    {{ round($stats['resolved'] / $stats['total_complaints'] * 100) }}% rate
                </div>
            @endif
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
            <div class="metric-icon red"><i class="ti ti-alert-triangle"></i></div>
            <div class="metric-val">{{ $stats['full_bins'] }}</div>
            <div class="metric-label">Full bins</div>
        </div>
    </div>
</div>

{{-- ── Charts row ───────────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-md-7">
        <div class="swms-card">
            <div class="card-title"><i class="ti ti-chart-line"></i> Complaints over time</div>
            <div class="card-sub">Daily complaints — last 14 days</div>
            <div style="position:relative;height:200px">
                <canvas id="lineChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="swms-card">
            <div class="card-title"><i class="ti ti-chart-donut"></i> Waste categories</div>
            <div class="card-sub">Distribution this month</div>
            <div class="d-flex flex-wrap gap-2 mb-2">
                @foreach($categoryData as $cat)
                    <span style="display:flex;align-items:center;gap:4px;font-size:11px;color:var(--text-muted)">
                        <span style="width:9px;height:9px;border-radius:2px;background:{{ $cat->color }};display:inline-block"></span>
                        {{ $cat->name }} ({{ $cat->complaints_count }})
                    </span>
                @endforeach
            </div>
            <div style="position:relative;height:160px">
                <canvas id="donutChart"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- ── Recent complaints & Map ──────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="swms-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="card-title mb-0"><i class="ti ti-list"></i> Recent complaints</div>
                <a href="{{ route('complaints.index') }}" class="btn-swms-secondary" style="padding:5px 12px;font-size:12px">View all</a>
            </div>
            @forelse($recentComplaints as $c)
            <div class="d-flex align-items-start gap-2 py-2 border-bottom">
                <div class="flex-fill">
                    <div class="complaint-id">#{{ $c->complaint_number }}</div>
                    <div style="font-size:13px;font-weight:500">{{ $c->title }}</div>
                    <div style="font-size:11px;color:var(--text-muted)">
                        <i class="ti ti-map-pin" style="font-size:11px"></i> {{ $c->area }}
                    </div>
                </div>
                {!! $c->status_badge !!}
            </div>
            @empty
                <p class="text-muted" style="font-size:13px">No complaints yet.</p>
            @endforelse
        </div>
    </div>
    <div class="col-md-6">
        <div class="swms-card">
            <div class="card-title"><i class="ti ti-map"></i> Complaint map</div>
            <div class="card-sub">Active waste complaint locations</div>
            <div id="map" style="height:260px;border-radius:10px"></div>
        </div>
    </div>
</div>

{{-- ── Sanitization bar chart ───────────────────────────────────────────────── --}}
<div class="swms-card">
    <div class="card-title"><i class="ti ti-chart-bar"></i> Sanitization completion by area</div>
    <div class="card-sub">Tasks completed vs scheduled this week</div>
    <div style="position:relative;height:200px">
        <canvas id="sanChart"></canvas>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Line chart
const lineData = @json($complaintsChart);
new Chart(document.getElementById('lineChart'), {
    type: 'line',
    data: {
        labels: lineData.map(d => d.date),
        datasets: [{
            data:            lineData.map(d => d.count),
            borderColor:     '#2da563',
            backgroundColor: 'rgba(45,165,99,.08)',
            tension:         0.4,
            pointRadius:     3,
            pointBackgroundColor: '#2da563',
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,.04)' }, ticks: { font: { size: 11 } } },
            x: { grid: { display: false },                              ticks: { font: { size: 10 } } },
        }
    }
});

// Donut chart
const catData = @json($categoryData);
new Chart(document.getElementById('donutChart'), {
    type: 'doughnut',
    data: {
        labels:   catData.map(c => c.name),
        datasets: [{ data: catData.map(c => c.complaints_count), backgroundColor: catData.map(c => c.color), borderWidth: 0 }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, cutout: '68%' }
});

// Sanitization bar chart
const sanData = @json($sanitizationChart);
new Chart(document.getElementById('sanChart'), {
    type: 'bar',
    data: {
        labels:   sanData.map(d => d.area),
        datasets: [{ label: 'Completion %', data: sanData.map(d => d.percent), backgroundColor: '#2da563', borderRadius: 4 }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%', font: { size: 11 } }, grid: { color: 'rgba(0,0,0,.04)' } },
            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});

// Leaflet map
const map = L.map('map').setView([30.9010, 75.8573], 15);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

@foreach($recentComplaints->whereNotNull('latitude') as $c)
    L.marker([{{ $c->latitude }}, {{ $c->longitude }}])
        .addTo(map)
        .bindPopup('<b>#{{ $c->complaint_number }}</b><br>{{ $c->title }}<br><i>{{ $c->area }}</i>');
@endforeach
</script>
@endpush
