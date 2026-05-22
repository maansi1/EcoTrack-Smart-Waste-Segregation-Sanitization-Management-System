{{-- resources/views/auth/verify-email.blade.php --}}
@extends('layouts.auth')
@section('title','Verify Email')
@section('content')
<div class="auth-card text-center">
    <div class="auth-logo justify-content-center">
        <div class="brand-icon"><i class="ti ti-recycle"></i></div>
    </div>
    <div style="font-size:40px;margin-bottom:16px">📧</div>
    <div class="auth-title mb-2">Verify your email</div>
    <div class="auth-sub mb-4">
        Thanks for registering! Please verify your email address by clicking the link we sent you.
    </div>
    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success py-2 mb-3" style="font-size:13px">A new verification link has been sent.</div>
    @endif
    <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
        @csrf
        <button type="submit" class="btn-swms-primary w-100 justify-content-center">
            <i class="ti ti-send"></i> Resend verification email
        </button>
    </form>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-swms-secondary w-100 justify-content-center">
            <i class="ti ti-logout"></i> Log out
        </button>
    </form>
</div>
@endsection
