{{-- resources/views/public/home.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwMS — Smart Waste Management & Sanitization System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.5.0/tabler-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <style>
        .hero {
            background: linear-gradient(135deg, #0f4d2e 0%, #1a7a4a 50%, #0d3d5c 100%);
            color: #fff;
            padding: 100px 0 80px;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -100px; right: -100px;
            width: 500px; height: 500px;
            background: rgba(255,255,255,.04);
            border-radius: 50%;
        }
        .hero-title { font-size: clamp(28px, 5vw, 52px); font-weight: 800; line-height: 1.15; }
        .hero-sub   { font-size: 17px; opacity: .85; max-width: 560px; margin: 16px 0 32px; }
        .hero-btn   { padding: 13px 28px; border-radius: 10px; font-size: 15px; font-weight: 600; }

        .feature-icon {
            width: 52px; height: 52px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 26px; margin-bottom: 14px;
        }
        .stat-box {
            text-align: center;
            padding: 28px;
            border-radius: 12px;
            background: #fff;
            border: 1px solid #e8ecf0;
        }
        .stat-num { font-size: 36px; font-weight: 700; color: var(--green); }
        .nav-public { background: rgba(255,255,255,.97); backdrop-filter: blur(8px); border-bottom: 1px solid #e8ecf0; }
    </style>
</head>
<body style="background:#f5f7fa">

{{-- Navbar --}}
<nav class="navbar navbar-expand-md nav-public sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <div class="brand-icon" style="width:34px;height:34px;font-size:18px"><i class="ti ti-recycle"></i></div>
            <span style="font-weight:700;font-size:16px">SwMS</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('features') }}">Features</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
                @auth
                    <li class="nav-item">
                        <a class="btn-swms-primary" href="{{ route('dashboard') }}" style="font-size:13px;padding:7px 18px">
                            <i class="ti ti-layout-dashboard"></i> Dashboard
                        </a>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item">
                        <a class="btn-swms-primary" href="{{ route('register') }}" style="font-size:13px;padding:7px 18px">
                            Register free
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

{{-- Hero --}}
<section class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-title">Smart Waste Management &amp; Sanitization System</div>
                <div class="hero-sub">
                    A digital platform for colleges and communities to report waste issues,
                    track cleanliness, monitor smart bins, and improve sanitization through
                    data-driven management.
                </div>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('register') }}" class="btn btn-light hero-btn text-success fw-600">
                        <i class="ti ti-user-plus me-1"></i> Get started free
                    </a>
                    <a href="{{ route('complaints.track') }}" class="btn btn-outline-light hero-btn">
                        <i class="ti ti-map-pin me-1"></i> Track complaint
                    </a>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-flex justify-content-center mt-4 mt-lg-0">
                <div style="font-size:120px;line-height:1;text-align:center;opacity:.9">
                    ♻️<br>
                    <div style="font-size:40px;margin-top:12px;opacity:.7">🗑️ 🧹 🌿</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Stats --}}
<section style="background:#fff;padding:48px 0;border-bottom:1px solid #e8ecf0">
    <div class="container">
        @if(!empty($dbUnavailable))
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-warning rounded-4">
                    <strong>Database unavailable:</strong> Statistics are temporarily unavailable.
                    The homepage is still visible, but live counts may not load until the database is connected.
                </div>
            </div>
        </div>
        @endif
        <div class="row g-3 text-center">
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <div class="stat-num">{{ $complaintCount }}+</div>
                    <div style="font-size:13px;color:#6b7280">Complaints reported</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <div class="stat-num">{{ $resolvedCount }}+</div>
                    <div style="font-size:13px;color:#6b7280">Issues resolved</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <div class="stat-num">{{ $binCount }}</div>
                    <div style="font-size:13px;color:#6b7280">Smart bins monitored</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <div class="stat-num">{{ $userCount }}+</div>
                    <div style="font-size:13px;color:#6b7280">Active users</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Features --}}
<section style="padding:72px 0">
    <div class="container">
        <div class="text-center mb-5">
            <h2 style="font-size:32px;font-weight:700">Everything you need for clean campus management</h2>
            <p style="color:#6b7280;font-size:15px;max-width:540px;margin:10px auto 0">
                A complete digital system built for colleges, schools, and residential communities.
            </p>
        </div>
        <div class="row g-4">
            @foreach([
                ['icon'=>'🗑️','color'=>'var(--green-light)','title'=>'Smart Bin Monitoring','desc'=>'Real-time fill-level tracking for all waste bins with color-coded alerts and overflow notifications.'],
                ['icon'=>'📋','color'=>'var(--blue-light)','title'=>'Complaint Management','desc'=>'Submit, track and resolve waste complaints with timeline progress, image upload and status updates.'],
                ['icon'=>'🧹','color'=>'var(--amber-light)','title'=>'Sanitization Scheduling','desc'=>'Plan and assign sanitization tasks to staff, track completion, and view area-wise history.'],
                ['icon'=>'💡','color'=>'#ece8fb','title'=>'AI Waste Guide','desc'=>'Describe any waste item and get instant guidance on proper segregation and disposal methods.'],
                ['icon'=>'🏆','color'=>'var(--amber-light)','title'=>'Gamification','desc'=>'Earn points for reporting issues, climb the leaderboard and unlock eco-champion badges.'],
                ['icon'=>'📊','color'=>'var(--green-light)','title'=>'Reports & Analytics','desc'=>'Visual dashboards, monthly reports, staff performance tracking and CSV/PDF export.'],
            ] as $f)
            <div class="col-md-4">
                <div style="background:#fff;border:1px solid #e8ecf0;border-radius:14px;padding:24px;height:100%">
                    <div class="feature-icon" style="background:{{ $f['color'] }}">{{ $f['icon'] }}</div>
                    <div style="font-size:15px;font-weight:600;margin-bottom:8px">{{ $f['title'] }}</div>
                    <div style="font-size:13px;color:#6b7280">{{ $f['desc'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section style="background:linear-gradient(135deg,#0f4d2e,#1a7a4a);padding:72px 0;color:#fff;text-align:center">
    <div class="container">
        <h2 style="font-size:32px;font-weight:700;margin-bottom:12px">Ready to make your campus cleaner?</h2>
        <p style="opacity:.85;font-size:15px;max-width:480px;margin:0 auto 28px">
            Join the smart waste management initiative. Report issues, earn points, and help maintain a clean environment.
        </p>
        <a href="{{ route('register') }}" class="btn btn-light hero-btn text-success fw-600">
            Get started for free →
        </a>
    </div>
</section>

{{-- Footer --}}
<footer style="background:#1a202c;color:#9ca3af;padding:28px 0;text-align:center;font-size:13px">
    <div class="container">
        © {{ date('Y') }} Smart Waste Management & Sanitization System — Student Innovation Project
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
