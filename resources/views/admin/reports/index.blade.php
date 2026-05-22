{{-- resources/views/admin/reports/index.blade.php --}}
@extends('layouts.app')
@section('title','Reports & Analytics')
@section('page-title','Reports & Analytics')
@section('page-sub','Generate, view and export waste management reports')

@section('content')

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon green"><i class="ti ti-chart-line"></i></div>
            <div class="metric-val">{{ $stats['resolution_rate'] }}%</div>
            <div class="metric-label">Resolution rate</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon blue"><i class="ti ti-star"></i></div>
            <div class="metric-val">{{ $stats['avg_rating'] }}/5</div>
            <div class="metric-label">Avg satisfaction</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon amber"><i class="ti ti-spray"></i></div>
            <div class="metric-val">{{ $stats['sanitization_rate'] }}%</div>
            <div class="metric-label">Sanitization rate</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon green"><i class="ti ti-file-text"></i></div>
            <div class="metric-val">{{ $stats['total_complaints'] }}</div>
            <div class="metric-label">Total complaints</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-7">
        <div class="swms-card">
            <div class="card-title"><i class="ti ti-chart-bar"></i> Monthly complaint trends</div>
            <div class="card-sub">Last 6 months</div>
            <div style="position:relative;height:220px">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="swms-card">
            <div class="card-title"><i class="ti ti-chart-donut"></i> Waste by category</div>
            <div class="card-sub">All time distribution</div>
            <div style="position:relative;height:180px">
                <canvas id="catChart"></canvas>
            </div>
            <div class="mt-2 d-flex flex-wrap gap-2">
                @foreach($wasteByCategory as $c)
                    <span style="font-size:11px;color:var(--text-muted);display:flex;align-items:center;gap:3px">
                        <span style="width:8px;height:8px;border-radius:2px;background:{{ $c->color }};display:inline-block"></span>
                        {{ $c->name }}: {{ $c->complaints_count }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="swms-card">
            <div class="card-title mb-3"><i class="ti ti-users"></i> Staff performance</div>
            @foreach($staffPerformance as $s)
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1" style="font-size:13px">
                    <span class="fw-500">{{ $s->name }}</span>
                    <span class="mono" style="font-size:12px">{{ $s->tasks_done }}/{{ $s->tasks_total }}</span>
                </div>
                <div class="swms-progress">
                    <div class="swms-progress-fill"
                         style="width:{{ $s->tasks_total > 0 ? round(($s->tasks_done/$s->tasks_total)*100) : 0 }}%">
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="col-md-6">
        <div class="swms-card">
            <div class="card-title mb-3"><i class="ti ti-file-plus"></i> Generate new report</div>
            <form method="POST" action="{{ route('admin.reports.generate') }}">
                @csrf
                <div class="mb-3">
                    <label class="swms-label">Report title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="swms-input" placeholder="e.g. May 2026 Waste Summary" required>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="swms-label">Report type</label>
                        <select name="type" class="swms-select">
                            <option value="waste">Waste generation</option>
                            <option value="cleanliness">Cleanliness</option>
                            <option value="staff">Staff performance</option>
                            <option value="sanitization">Sanitization</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="swms-label">Period</label>
                        <select name="period" class="swms-select">
                            <option value="monthly">Monthly</option>
                            <option value="weekly">Weekly</option>
                            <option value="daily">Daily</option>
                            <option value="yearly">Yearly</option>
                            <option value="custom">Custom range</option>
                        </select>
                    </div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="swms-label">From date</label>
                        <input type="date" name="from_date" class="swms-input">
                    </div>
                    <div class="col-6">
                        <label class="swms-label">To date</label>
                        <input type="date" name="to_date" class="swms-input">
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn-swms-primary flex-fill justify-content-center">
                        <i class="ti ti-file-plus"></i> Generate
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($reports->count())
<div class="swms-card">
    <div class="card-title mb-3"><i class="ti ti-history"></i> Generated reports</div>
    <div class="table-responsive">
        <table class="swms-table">
            <thead>
                <tr><th>Title</th><th>Type</th><th>Period</th><th>Generated by</th><th>Date</th><th>Export</th></tr>
            </thead>
            <tbody>
                @foreach($reports as $r)
                <tr>
                    <td class="fw-500">{{ $r->title }}</td>
                    <td>{{ ucfirst($r->type) }}</td>
                    <td>{{ ucfirst($r->period) }}</td>
                    <td>{{ $r->generatedBy->name }}</td>
                    <td>{{ $r->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.reports.show', $r) }}" class="btn-swms-secondary" style="padding:4px 10px;font-size:12px">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="{{ route('admin.reports.csv', $r) }}" class="btn-swms-primary" style="padding:4px 10px;font-size:12px">
                                <i class="ti ti-download"></i> CSV
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $reports->links() }}</div>
</div>
@endif

@endsection

@push('scripts')
<script>
const monthly = @json($monthlyData);
new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: {
        labels: monthly.map(d => d.month),
        datasets: [{ label: 'Complaints', data: monthly.map(d => d.count), backgroundColor: '#2da563', borderRadius: 5 }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,.04)' }, ticks: { font: { size: 11 } } },
            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});

const cats = @json($wasteByCategory);
new Chart(document.getElementById('catChart'), {
    type: 'doughnut',
    data: {
        labels: cats.map(c => c.name),
        datasets: [{ data: cats.map(c => c.complaints_count), backgroundColor: cats.map(c => c.color), borderWidth: 0 }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, cutout: '65%' }
});
</script>
@endpush
