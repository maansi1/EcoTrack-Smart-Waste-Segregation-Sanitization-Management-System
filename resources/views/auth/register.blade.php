{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.auth')
@section('title', 'Register')

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
        <div class="auth-title">Create account</div>
        <div class="auth-sub">Join the cleanliness initiative</div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger py-2 mb-3">
            @foreach ($errors->all() as $error)
                <div style="font-size:13px">{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label class="swms-label">Full name</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="swms-input" placeholder="Arjun Kumar" required autofocus>
        </div>
        <div class="mb-3">
            <label class="swms-label">Email address</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="swms-input" placeholder="you@example.com" required>
        </div>
        <div class="mb-3">
            <label class="swms-label">Phone (optional)</label>
            <input type="text" name="phone" value="{{ old('phone') }}"
                   class="swms-input" placeholder="9876543210">
        </div>
        <div class="mb-3">
            <label class="swms-label">Your area / block</label>
            <input type="text" name="area" value="{{ old('area') }}"
                   class="swms-input" placeholder="e.g. North Campus, Block A">
        </div>
        <div class="mb-3">
            <label class="swms-label">Password</label>
            <input type="password" name="password" class="swms-input" placeholder="Minimum 8 characters" required>
        </div>
        <div class="mb-3">
            <label class="swms-label">Confirm password</label>
            <input type="password" name="password_confirmation" class="swms-input" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn-swms-primary w-100 justify-content-center">
            <i class="ti ti-user-plus"></i> Create account
        </button>
    </form>

    <p class="text-center mt-4 mb-0" style="font-size:13px;color:var(--text-muted)">
        Already have an account?
        <a href="{{ route('login') }}" style="color:var(--green);font-weight:500">Sign in</a>
    </p>
</div>
@endsection
