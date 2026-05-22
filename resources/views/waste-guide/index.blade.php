{{-- resources/views/waste-guide/index.blade.php --}}
@extends('layouts.app')
@section('title','Waste Guide')
@section('page-title','AI Waste Segregation Guide')
@section('page-sub','Smart disposal and recycling guidance')

@section('content')
<div class="row g-4">
    <div class="col-md-5">
        <div class="swms-card">
            <div class="card-title mb-1"><i class="ti ti-bulb"></i> Get smart guidance</div>
            <div class="card-sub">Describe what you want to dispose of</div>
            <label class="swms-label">Describe the waste</label>
            <textarea class="swms-textarea mb-3" id="wasteInput" rows="4"
                      placeholder="e.g. old mobile phone and charger, plastic bottles, food scraps..."></textarea>
            <button class="btn-swms-primary w-100 justify-content-center" onclick="getGuidance()">
                <i class="ti ti-wand"></i> Get guidance
            </button>
            <div id="guidanceResult" class="mt-3 d-none">
                <div class="seg-result">
                    <div class="seg-type" id="catName"></div>
                    <div id="catInstructions"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="swms-card">
            <div class="card-title mb-3"><i class="ti ti-book"></i> Waste category reference</div>
            @foreach($categories as $cat)
            <div class="mb-3 p-3 rounded" style="background:var(--bg-body);border:1px solid var(--border)">
                <div style="font-size:13px;font-weight:600;margin-bottom:6px">
                    <span style="font-size:18px">{{ $cat->icon }}</span>
                    {{ $cat->name }}
                    <span style="font-size:11px;background:{{ $cat->color }}22;color:{{ $cat->color }};padding:2px 8px;border-radius:10px;margin-left:6px">{{ $cat->bin_color }} bin</span>
                    @if($cat->is_hazardous)
                        <span style="font-size:10px;background:var(--red-light);color:var(--red);padding:2px 8px;border-radius:10px;margin-left:4px">⚠ Hazardous</span>
                    @endif
                </div>
                <div style="font-size:12px;color:var(--text-muted)">{{ $cat->description }}</div>
                <button class="btn-swms-secondary mt-2" style="padding:4px 12px;font-size:11px"
                        onclick="toggleTips({{ $cat->id }})">
                    Show tips
                </button>
                <div id="tips-{{ $cat->id }}" class="d-none mt-2">
                    @foreach(explode("\n", $cat->disposal_instructions) as $line)
                        @if(trim($line))<div style="font-size:12px;color:var(--green);margin-top:3px">{{ trim($line) }}</div>@endif
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
async function getGuidance() {
    const desc = document.getElementById('wasteInput').value;
    if (!desc.trim()) return alert('Please describe the waste first.');
    const res = await fetch('{{ route("waste-guide.guide") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ description: desc })
    });
    const data = await res.json();
    document.getElementById('catName').textContent = (data.category.icon ?? '') + ' ' + data.category.name;
    const lines = (data.disposal_instructions || '').split('\n').filter(l => l.trim());
    document.getElementById('catInstructions').innerHTML = lines.map(l => `<div style="font-size:12px;color:var(--green);margin-top:3px">${l}</div>`).join('');
    document.getElementById('guidanceResult').classList.remove('d-none');
}
function toggleTips(id) {
    const el = document.getElementById('tips-' + id);
    el.classList.toggle('d-none');
}
</script>
@endpush
