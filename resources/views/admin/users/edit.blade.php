{{-- resources/views/admin/users/edit.blade.php --}}
@extends('layouts.app')
@section('title','Edit User')
@section('page-title','Edit User')
@section('page-sub','Update account details for {{ $user->name }}')

@section('content')
<div class="swms-card" style="max-width:560px">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-sm-6">
                <label class="swms-label">Full name <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="swms-input" required>
            </div>
            <div class="col-sm-6">
                <label class="swms-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="swms-input" required>
            </div>
            <div class="col-sm-6">
                <label class="swms-label">Role <span class="text-danger">*</span></label>
                <select name="role" class="swms-select" required>
                    <option value="user"  {{ old('role', $user->role) == 'user'  ? 'selected' : '' }}>Public User</option>
                    <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>Sanitation Staff</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
                </select>
            </div>
            <div class="col-sm-6">
                <label class="swms-label">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="swms-input">
            </div>
            <div class="col-sm-6">
                <label class="swms-label">Area / Block</label>
                <input type="text" name="area" value="{{ old('area', $user->area) }}" class="swms-input">
            </div>
            <div class="col-sm-6">
                <label class="swms-label">Status</label>
                <select name="is_active" class="swms-select">
                    <option value="1" {{ old('is_active', $user->is_active) ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !old('is_active', $user->is_active) ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-12">
                <div style="font-size:12px;color:var(--text-muted);margin-bottom:8px">
                    Leave password fields blank to keep current password.
                </div>
            </div>
            <div class="col-sm-6">
                <label class="swms-label">New password</label>
                <input type="password" name="password" class="swms-input" minlength="8">
            </div>
            <div class="col-sm-6">
                <label class="swms-label">Confirm new password</label>
                <input type="password" name="password_confirmation" class="swms-input">
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn-swms-primary">
                <i class="ti ti-device-floppy"></i> Save changes
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn-swms-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
