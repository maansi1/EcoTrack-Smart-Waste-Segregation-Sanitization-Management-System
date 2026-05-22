{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — SwMS</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Tabler Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.5.0/tabler-icons.min.css" rel="stylesheet">
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    {{-- Custom CSS --}}
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>

<div class="swms-wrapper">

    {{-- ── Sidebar ─────────────────────────────────────────────────────── --}}
    <aside class="swms-sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon"><i class="ti ti-recycle"></i></div>
            <div>
                <div class="brand-name">SwMS</div>
                <div class="brand-sub">Smart Waste Management</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">Main</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="ti ti-layout-dashboard"></i> Dashboard
            </a>

            @if(auth()->user()->isUser() || auth()->user()->isAdmin())
            <a href="{{ route('complaints.create') }}" class="nav-link {{ request()->routeIs('complaints.create') ? 'active' : '' }}">
                <i class="ti ti-file-plus"></i> Submit Complaint
            </a>
            @endif

            <a href="{{ route('complaints.index') }}" class="nav-link {{ request()->routeIs('complaints.index') ? 'active' : '' }}">
                <i class="ti ti-list-check"></i> Complaints
                @php $pending = \App\Models\Complaint::pending()->count() @endphp
                @if($pending > 0)
                    <span class="nav-badge">{{ $pending }}</span>
                @endif
            </a>

            <a href="{{ route('complaints.track') }}" class="nav-link {{ request()->routeIs('complaints.track') ? 'active' : '' }}">
                <i class="ti ti-map-pin"></i> Track Status
            </a>

            <div class="nav-section">Smart Features</div>
            <a href="{{ route('waste-guide.index') }}" class="nav-link {{ request()->routeIs('waste-guide.*') ? 'active' : '' }}">
                <i class="ti ti-bulb"></i> Waste Guide
            </a>
            <a href="{{ route('bins.index') }}" class="nav-link {{ request()->routeIs('bins.*') ? 'active' : '' }}">
                <i class="ti ti-trash"></i> Smart Bins
                @php $fullBins = \App\Models\Bin::needsCollection()->count() @endphp
                @if($fullBins > 0)
                    <span class="nav-badge bg-danger">{{ $fullBins }}</span>
                @endif
            </a>

            @if(auth()->user()->isStaff() || auth()->user()->isAdmin())
            <a href="{{ route('sanitization.index') }}" class="nav-link {{ request()->routeIs('sanitization.*') ? 'active' : '' }}">
                <i class="ti ti-spray"></i> Sanitization
            </a>
            @endif

            <div class="nav-section">Community</div>
            <a href="{{ route('feedback.index') }}" class="nav-link {{ request()->routeIs('feedback.*') ? 'active' : '' }}">
                <i class="ti ti-star"></i> Feedback
            </a>
            <a href="{{ route('leaderboard') }}" class="nav-link {{ request()->routeIs('leaderboard') ? 'active' : '' }}">
                <i class="ti ti-trophy"></i> Leaderboard
            </a>
            <a href="{{ route('notifications.index') }}" class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <i class="ti ti-bell"></i> Notifications
                @php $unread = \App\Models\Notification::where('user_id', auth()->id())->unread()->count() @endphp
                @if($unread > 0)
                    <span class="nav-badge bg-danger">{{ $unread }}</span>
                @endif
            </a>

            @if(auth()->user()->isAdmin())
            <div class="nav-section">Admin</div>
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="ti ti-users"></i> User Management
            </a>
            <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <i class="ti ti-chart-bar"></i> Reports
            </a>
            @endif
        </nav>

        <div class="sidebar-footer">
            <a href="#" class="nav-link"><i class="ti ti-settings"></i> Settings</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent">
                    <i class="ti ti-logout"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- ── Main area ────────────────────────────────────────────────────── --}}
    <div class="swms-main">

        {{-- Topbar --}}
        <header class="swms-topbar">
            <button class="btn-sidebar-toggle d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="ti ti-menu-2"></i>
            </button>
            <div class="topbar-title">
                <div class="fw-500">@yield('page-title', 'Dashboard')</div>
                <div class="topbar-sub text-muted small">@yield('page-sub', '')</div>
            </div>
            <div class="topbar-actions ms-auto d-flex align-items-center gap-2">
                <span class="role-chip role-{{ auth()->user()->role }}">{{ auth()->user()->role_label }}</span>
                <a href="{{ route('notifications.index') }}" class="notif-btn position-relative">
                    <i class="ti ti-bell"></i>
                    @if($unread ?? 0 > 0)
                        <span class="notif-dot"></span>
                    @endif
                </a>
                <div class="dropdown">
                    <div class="user-avatar dropdown-toggle" data-bs-toggle="dropdown" style="cursor:pointer">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item-text fw-500">{{ auth()->user()->name }}</span></li>
                        <li><span class="dropdown-item-text text-muted small">{{ auth()->user()->email }}</span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item text-danger">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        {{-- Flash messages --}}
        <div class="px-4 pt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                    <i class="ti ti-circle-check"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ti ti-alert-circle me-1"></i>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        {{-- Page content --}}
        <main class="swms-content">
            @yield('content')
        </main>
    </div>
</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
{{-- Leaflet.js --}}
<link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

@stack('scripts')
</body>
</html>
