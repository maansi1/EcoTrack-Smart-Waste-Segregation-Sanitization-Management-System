{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<div class="auth-card">
    <div class="auth-logo">
        <div class="brand-icon"><i class="ti ti-recycle"></i></div>
        <div>
            <div class="brand-name" style="font-size:18px;font-weight:700">SwMS</div>
            <div class="brand-sub">Smart Waste Management</div>
        </div>
    </div>

    <div class="text-center mb-4">
        <div class="auth-title">Welcome back</div>
        <div class="auth-sub">Sign in to your account</div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger py-2 mb-3">
            @foreach ($errors->all() as $error)
                <div style="font-size:13px">{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label class="swms-label">Email address</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="swms-input" placeholder="you@example.com" required autofocus>
        </div>
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label class="swms-label mb-0">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size:12px;color:var(--green)">Forgot password?</a>
                @endif
            </div>
            <input type="password" name="password" class="swms-input" placeholder="••••••••" required>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label" for="remember" style="font-size:13px">Remember me</label>
        </div>
        <button type="submit" class="btn-swms-primary w-100 justify-content-center">
            <i class="ti ti-login"></i> Sign in
        </button>
    </form>

    <p class="text-center mt-4 mb-0" style="font-size:13px;color:var(--text-muted)">
        Don't have an account?
        <a href="{{ route('register') }}" style="color:var(--green);font-weight:500">Register here</a>
    </p>

    {{-- Demo credentials --}}
    <div class="mt-4 p-3 rounded" style="background:var(--bg-body);font-size:11px;color:var(--text-muted)">
        <div class="fw-600 mb-1" style="font-size:12px">Demo accounts</div>
        <div>Admin: admin@swms.com / password</div>
        <div>Staff: staff@swms.com / password</div>
        <div>User: user@swms.com / password</div>
    </div>
</div>
@endsection
