{{-- resources/views/public/about.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About — SwMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.5.0/tabler-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <style>
        body { background: #f5f7fa; font-family: 'DM Sans', sans-serif; }
        .nav-public { background: rgba(255,255,255,.97); backdrop-filter: blur(8px); border-bottom: 1px solid #e8ecf0; }
        .page-hero { background: #0d3d5c; color: #fff; padding: 80px 0; }
        .page-hero h1 { font-size: clamp(32px, 5vw, 48px); font-weight: 800; }
    </style>
</head>
<body>
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
                <li class="nav-item"><a class="nav-link active" href="{{ route('about') }}">About</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('features') }}">Features</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
                @auth
                    <li class="nav-item"><a class="btn btn-outline-success" href="{{ route('dashboard') }}">Dashboard</a></li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="btn btn-success text-white" href="{{ route('register') }}">Register</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
<section class="page-hero text-center">
    <div class="container">
        <h1>About SwMS</h1>
        <p class="mt-3" style="max-width:680px;margin:auto;opacity:.8;font-size:1.05rem">A modern solution for campus sanitation, waste tracking, reporting and community-driven cleanups.</p>
    </div>
</section>
<section class="py-5">
    <div class="container">
        <div class="row gy-4 align-items-center">
            <div class="col-lg-6">
                <h2>Built for smarter waste management</h2>
                <p class="text-muted">SwMS helps students, staff, and administrators work together to identify issues, assign cleanup tasks, monitor smart bins, and close the loop with reporting and rewards.</p>
                <ul class="list-unstyled mt-4" style="line-height:1.9">
                    <li>• Quick complaint filing and tracking</li>
                    <li>• Role-based access for users, sanitation staff, and admins</li>
                    <li>• Real-time smart bin monitoring</li>
                    <li>• Task scheduling, reporting, and analytics</li>
                </ul>
            </div>
            <div class="col-lg-6">
                <div class="card p-4 border-0 shadow-sm">
                    <h4>Our mission</h4>
                    <p class="text-muted">Enable communities to keep their environments clean through transparent reporting, faster response, and data-driven oversight.</p>
                    <p class="text-muted">SwMS is designed as a campus-first solution with a simple workflow for reporting issues, assigning teams, and tracking resolution progress.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<footer class="text-center py-4" style="background:#fff;border-top:1px solid #e8ecf0">
    <div class="container">© {{ date('Y') }} SwMS — Smart Waste Management & Sanitization System</div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
