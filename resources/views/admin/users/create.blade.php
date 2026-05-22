{{-- resources/views/admin/users/create.blade.php --}}
@extends('layouts.app')
@section('title','Add User')
@section('page-title','Add New User')
@section('page-sub','Create a new system account')

@section('content')
<div class="swms-card" style="max-width:560px">
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-sm-6">
                <label class="swms-label">Full name <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="swms-input" required>
            </div>
            <div class="col-sm-6">
                <label class="swms-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" class="swms-input" required>
            </div>
            <div class="col-sm-6">
                <label class="swms-label">Role <span class="text-danger">*</span></label>
                <select name="role" class="swms-select" required>
                    <option value="user"  {{ old('role')=='user'  ? 'selected' : '' }}>Public User</option>
                    <option value="staff" {{ old('role')=='staff' ? 'selected' : '' }}>Sanitation Staff</option>
                    <option value="admin" {{ old('role')=='admin' ? 'selected' : '' }}>Administrator</option>
                </select>
            </div>
            <div class="col-sm-6">
                <label class="swms-label">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="swms-input" placeholder="9876543210">
            </div>
            <div class="col-12">
                <label class="swms-label">Area / Block</label>
                <input type="text" name="area" value="{{ old('area') }}" class="swms-input" placeholder="e.g. North Campus">
            </div>
            <div class="col-sm-6">
                <label class="swms-label">Password <span class="text-danger">*</span></label>
                <input type="password" name="password" class="swms-input" required minlength="8">
            </div>
            <div class="col-sm-6">
                <label class="swms-label">Confirm password <span class="text-danger">*</span></label>
                <input type="password" name="password_confirmation" class="swms-input" required>
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn-swms-primary"><i class="ti ti-user-plus"></i> Create user</button>
            <a href="{{ route('admin.users.index') }}" class="btn-swms-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
