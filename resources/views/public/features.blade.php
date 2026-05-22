{{-- resources/views/public/features.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Features — SwMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.5.0/tabler-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <style>
        body { background: #f5f7fa; font-family: 'DM Sans', sans-serif; }
        .nav-public { background: rgba(255,255,255,.97); backdrop-filter: blur(8px); border-bottom: 1px solid #e8ecf0; }
        .page-hero { background: #0d3d5c; color: #fff; padding: 80px 0; }
        .feature-card { background: #fff; border:1px solid #e8ecf0; border-radius:16px; padding:28px; min-height:240px; }
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
                <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
                <li class="nav-item"><a class="nav-link active" href="{{ route('features') }}">Features</a></li>
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
        <h1>Feature-rich waste management</h1>
        <p class="mt-3" style="max-width:680px;margin:auto;opacity:.8;font-size:1.05rem">Explore the tools that help your campus report, track, schedule, and resolve sanitation issues faster.</p>
    </div>
</section>
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4"><div class="feature-card"><h4>Complaint tracking</h4><p class="text-muted">Users file complaints, track status, and see resolution timelines.</p></div></div>
            <div class="col-md-4"><div class="feature-card"><h4>Role-based access</h4><p class="text-muted">Separate dashboards and permissions for users, staff, and admins.</p></div></div>
            <div class="col-md-4"><div class="feature-card"><h4>Smart bins</h4><p class="text-muted">Monitor bin fill levels and collection status in one place.</p></div></div>
            <div class="col-md-4"><div class="feature-card"><h4>Sanitization tasks</h4><p class="text-muted">Schedule work, assign staff, and track completion progress.</p></div></div>
            <div class="col-md-4"><div class="feature-card"><h4>Reports & analytics</h4><p class="text-muted">View summaries, export CSVs, and measure cleanup performance.</p></div></div>
            <div class="col-md-4"><div class="feature-card"><h4>Reward system</h4><p class="text-muted">Users earn points for reporting and providing feedback.</p></div></div>
        </div>
    </div>
</section>
<footer class="text-center py-4" style="background:#fff;border-top:1px solid #e8ecf0">
    <div class="container">© {{ date('Y') }} SwMS — Smart Waste Management & Sanitization System</div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
