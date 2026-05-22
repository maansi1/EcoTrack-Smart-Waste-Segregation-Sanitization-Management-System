{{-- resources/views/auth/forgot-password.blade.php --}}
@extends('layouts.auth')
@section('title','Forgot Password')
@section('content')
<div class="auth-card">
    <div class="auth-logo">
        <div class="brand-icon"><i class="ti ti-recycle"></i></div>
        <div><div class="brand-name" style="font-size:18px;font-weight:700">SwMS</div></div>
    </div>
    <div class="text-center mb-4">
        <div class="auth-title">Forgot password?</div>
        <div class="auth-sub">Enter your email and we'll send a reset link.</div>
    </div>
    @if (session('status'))
        <div class="alert alert-success py-2 mb-3" style="font-size:13px">{{ session('status') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger py-2 mb-3" style="font-size:13px">{{ $errors->first() }}</div>
    @endif
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-3">
            <label class="swms-label">Email address</label>
            <input type="email" name="email" value="{{ old('email') }}" class="swms-input" required autofocus>
        </div>
        <button type="submit" class="btn-swms-primary w-100 justify-content-center">
            <i class="ti ti-send"></i> Send reset link
        </button>
    </form>
    <p class="text-center mt-3 mb-0" style="font-size:13px;color:var(--text-muted)">
        <a href="{{ route('login') }}" style="color:var(--green)">Back to login</a>
    </p>
</div>
@endsection
