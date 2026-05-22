{{-- resources/views/auth/reset-password.blade.php --}}
@extends('layouts.auth')
@section('title','Reset Password')
@section('content')
<div class="auth-card">
    <div class="auth-logo">
        <div class="brand-icon"><i class="ti ti-recycle"></i></div>
        <div><div class="brand-name" style="font-size:18px;font-weight:700">SwMS</div></div>
    </div>
    <div class="text-center mb-4">
        <div class="auth-title">Reset password</div>
        <div class="auth-sub">Enter your new password below.</div>
    </div>
    @if($errors->any())
        <div class="alert alert-danger py-2 mb-3" style="font-size:13px">{{ $errors->first() }}</div>
    @endif
    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div class="mb-3">
            <label class="swms-label">Email</label>
            <input type="email" name="email" value="{{ old('email', $request->email) }}" class="swms-input" required>
        </div>
        <div class="mb-3">
            <label class="swms-label">New password</label>
            <input type="password" name="password" class="swms-input" required>
        </div>
        <div class="mb-3">
            <label class="swms-label">Confirm password</label>
            <input type="password" name="password_confirmation" class="swms-input" required>
        </div>
        <button type="submit" class="btn-swms-primary w-100 justify-content-center">
            <i class="ti ti-key"></i> Reset password
        </button>
    </form>
</div>
@endsection
