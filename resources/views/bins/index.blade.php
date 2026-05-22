{{-- resources/views/bins/index.blade.php --}}
@extends('layouts.app')
@section('title','Smart Bins')
@section('page-title','Smart Bin Monitoring')
@section('page-sub','Real-time fill levels across all locations')

@section('content')

@if($fullBins->count())
<div class="alert-danger-swms mb-4">
    <i class="ti ti-alert-triangle" style="font-size:18px"></i>
    <div>
        <strong>{{ $fullBins->count() }} bin(s) need immediate collection:</strong>
        {{ $fullBins->pluck('name')->join(', ') }}
    </div>
</div>
@endif

<div class="row g-3">
    @foreach($bins as $bin)
    @php
        if ($bin->fill_level >= 75) {
            $cls = 'bin-red';
            $pctColor = 'var(--red)';
        } elseif ($bin->fill_level >= 40) {
            $cls = 'bin-yellow';
            $pctColor = 'var(--amber)';
        } else {
            $cls = 'bin-green';
            $pctColor = 'var(--green)';
        }
    @endphp
    <div class="col-6 col-md-4 col-xl-3">
        <div class="bin-card {{ $cls }}">
            <div class="bin-visual">
                <div class="bin-lid"></div>
                <div class="bin-body">
                    <div class="bin-fill" style="height:{{ $bin->fill_level }}%"></div>
                </div>
            </div>
            <div class="bin-name">{{ $bin->name }}</div>
            <div class="bin-pct" style="color:{{ $pctColor }}">{{ $bin->fill_level }}%</div>
            <div class="bin-status">{{ $bin->status_emoji }} {{ ucfirst($bin->status) }}</div>
            <div style="font-size:10px;color:var(--text-muted);margin-top:4px">{{ ucfirst($bin->bin_type) }} waste bin</div>

            @if(auth()->user()->isAdmin())
            <div class="mt-3 d-flex flex-column gap-2">
                {{-- Update fill level --}}
                <form method="POST" action="{{ route('admin.bins.update', $bin) }}" class="d-flex gap-1">
                    @csrf @method('PUT')
                    <input type="number" name="fill_level" value="{{ $bin->fill_level }}"
                           class="swms-input" style="width:60px;padding:4px 6px;font-size:12px" min="0" max="100">
                    <button type="submit" class="btn-swms-secondary" style="padding:4px 8px;font-size:11px">
                        <i class="ti ti-refresh"></i> Update
                    </button>
                </form>
                {{-- Mark collected --}}
                <form method="POST" action="{{ route('admin.bins.collected', $bin) }}">
                    @csrf
                    <button type="submit" class="btn-swms-primary w-100 justify-content-center" style="padding:5px;font-size:11px">
                        <i class="ti ti-check"></i> Mark collected
                    </button>
                </form>
            </div>
            @endif

            @if($bin->last_collected_at)
            <div style="font-size:10px;color:var(--text-muted);margin-top:6px">
                Last collected: {{ $bin->last_collected_at->diffForHumans() }}
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>

{{-- Add new bin (admin) --}}
@if(auth()->user()->isAdmin())
<div class="swms-card mt-4" style="max-width:500px">
    <div class="card-title mb-3"><i class="ti ti-plus"></i> Add new smart bin</div>
    <form method="POST" action="{{ route('admin.bins.store') }}">
        @csrf
        <div class="row g-2 mb-3">
            <div class="col-sm-6">
                <label class="swms-label">Bin name</label>
                <input type="text" name="name" class="swms-input" placeholder="e.g. Block C — Entry" required>
            </div>
            <div class="col-sm-6">
                <label class="swms-label">Area</label>
                <input type="text" name="area" class="swms-input" placeholder="North Campus" required>
            </div>
            <div class="col-sm-6">
                <label class="swms-label">Bin type</label>
                <select name="bin_type" class="swms-select">
                    <option value="general">General</option>
                    <option value="dry">Dry Waste</option>
                    <option value="wet">Wet Waste</option>
                    <option value="plastic">Plastic</option>
                    <option value="ewaste">E-Waste</option>
                    <option value="hazardous">Hazardous</option>
                </select>
            </div>
            <div class="col-sm-6">
                <label class="swms-label">Location description</label>
                <input type="text" name="location_description" class="swms-input" placeholder="Near main gate">
            </div>
        </div>
        <button type="submit" class="btn-swms-primary"><i class="ti ti-plus"></i> Add bin</button>
    </form>
</div>
@endif
@endsection
