{{-- resources/views/complaints/create.blade.php --}}
@extends('layouts.app')
@section('title','Submit Complaint')
@section('page-title','Submit Complaint')
@section('page-sub','Report a waste issue in your area')

@section('content')
<form method="POST" action="{{ route('complaints.store') }}" enctype="multipart/form-data">
@csrf

<div class="row g-4">

    {{-- Left: category + image --}}
    <div class="col-md-5">
        <div class="swms-card">
            <div class="card-title"><i class="ti ti-recycle"></i> Select waste category</div>
            <div class="card-sub">Choose the type of waste you're reporting</div>

            <div class="waste-cat-grid">
                @foreach($categories as $cat)
                <label class="waste-cat-option {{ old('waste_category_id') == $cat->id ? 'selected' : '' }}">
                    <input type="radio" name="waste_category_id" value="{{ $cat->id }}"
                           {{ old('waste_category_id') == $cat->id ? 'checked' : '' }}
                           onchange="this.closest('.waste-cat-grid').querySelectorAll('.waste-cat-option').forEach(e=>e.classList.remove('selected')); this.closest('.waste-cat-option').classList.add('selected')">
                    <div class="cat-icon">{{ $cat->icon }}</div>
                    <div class="cat-name">{{ $cat->name }}</div>
                    <div class="cat-desc">{{ $cat->bin_color }} bin</div>
                </label>
                @endforeach
            </div>
            @error('waste_category_id')
                <div class="text-danger" style="font-size:12px">{{ $message }}</div>
            @enderror

            <div class="mt-3">
                <label class="swms-label">Upload image of waste location</label>
                <div class="upload-zone" id="uploadZone" onclick="document.getElementById('imageInput').click()">
                    <i class="ti ti-cloud-upload" style="font-size:28px;display:block;margin-bottom:8px"></i>
                    <div id="uploadText">Click to upload or drag & drop</div>
                    <div style="font-size:11px;margin-top:4px">JPG, PNG, WebP — max 5MB</div>
                </div>
                <input type="file" id="imageInput" name="image" accept="image/*" class="d-none"
                       onchange="previewImage(this)">
                @error('image')
                    <div class="text-danger" style="font-size:12px">{{ $message }}</div>
                @enderror
            </div>

            {{-- Image preview --}}
            <div id="imagePreview" class="mt-2 d-none">
                <img id="previewImg" src="" style="width:100%;border-radius:8px;max-height:180px;object-fit:cover">
            </div>
        </div>
    </div>

    {{-- Right: complaint details --}}
    <div class="col-md-7">
        <div class="swms-card">
            <div class="card-title"><i class="ti ti-edit"></i> Complaint details</div>
            <div class="card-sub">Provide location and description</div>

            <div class="mb-3">
                <label class="swms-label">Complaint title <span class="text-danger">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}"
                       class="swms-input" placeholder="e.g. Overflowing bin near Gate 3" required>
                @error('title') <div class="text-danger" style="font-size:12px">{{ $message }}</div> @enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label class="swms-label">Building / Block</label>
                    <input type="text" name="building" value="{{ old('building') }}"
                           class="swms-input" placeholder="Block A">
                </div>
                <div class="col-sm-6">
                    <label class="swms-label">Area / Zone <span class="text-danger">*</span></label>
                    <select name="area" class="swms-select" required>
                        <option value="">Select area...</option>
                        @foreach(['North Campus','South Campus','Hostel Area','Academic Block','Sports Ground','Admin Area','Library','Main Canteen','Parking Zone','Medical Centre','Other'] as $area)
                            <option value="{{ $area }}" {{ old('area') == $area ? 'selected' : '' }}>{{ $area }}</option>
                        @endforeach
                    </select>
                    @error('area') <div class="text-danger" style="font-size:12px">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="swms-label">Specific location description</label>
                <input type="text" name="specific_location" value="{{ old('specific_location') }}"
                       class="swms-input" placeholder="Near the entrance, beside the bin...">
            </div>

            <div class="mb-3">
                <label class="swms-label">Description <span class="text-danger">*</span></label>
                <textarea name="description" class="swms-textarea" rows="4"
                          placeholder="Describe the waste issue in detail..." required>{{ old('description') }}</textarea>
                @error('description') <div class="text-danger" style="font-size:12px">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="swms-label">Priority level</label>
                <select name="priority" class="swms-select">
                    <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="high"   {{ old('priority') == 'high'   ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn-swms-primary">
                    <i class="ti ti-send"></i> Submit complaint
                </button>
                <a href="{{ route('complaints.index') }}" class="btn-swms-secondary">Cancel</a>
            </div>
        </div>
    </div>

</div>
</form>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('d-none');
            document.getElementById('uploadText').textContent = input.files[0].name;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
// Drag & drop
const zone = document.getElementById('uploadZone');
zone.addEventListener('dragover', e => { e.preventDefault(); zone.style.borderColor = 'var(--green)'; });
zone.addEventListener('dragleave', () => { zone.style.borderColor = ''; });
zone.addEventListener('drop', e => {
    e.preventDefault();
    zone.style.borderColor = '';
    document.getElementById('imageInput').files = e.dataTransfer.files;
    previewImage(document.getElementById('imageInput'));
});
</script>
@endpush
