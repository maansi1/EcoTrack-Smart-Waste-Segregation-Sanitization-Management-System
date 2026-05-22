{{-- resources/views/public/contact.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact — SwMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.5.0/tabler-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <style>
        body { background: #f5f7fa; font-family: 'DM Sans', sans-serif; }
        .nav-public { background: rgba(255,255,255,.97); backdrop-filter: blur(8px); border-bottom: 1px solid #e8ecf0; }
        .page-hero { background: #0d3d5c; color: #fff; padding: 80px 0; }
        .page-hero h1 { font-size: clamp(32px, 5vw, 48px); font-weight: 800; }
        .card-shadow { box-shadow: 0 18px 45px rgba(15, 77, 46, .06); }
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
                <li class="nav-item"><a class="nav-link" href="{{ route('features') }}">Features</a></li>
                <li class="nav-item"><a class="nav-link active" href="{{ route('contact') }}">Contact</a></li>
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
        <h1>Contact Smart Waste Management</h1>
        <p class="mt-3" style="max-width:640px;margin:auto;opacity:.8;font-size:1.05rem">Get in touch with our team for support, partnership inquiries, or feedback on the SwMS platform.</p>
    </div>
</section>
<section class="py-5">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-6">
                <div class="card card-shadow border-0 p-4">
                    <h3>Send us a message</h3>
                    <p class="text-muted">Please use the form below to contact support or request help with your campus sanitation workflow.</p>
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" placeholder="Your name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" placeholder="you@example.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea class="form-control" rows="5" placeholder="How can we help?"></textarea>
                        </div>
                        <button class="btn btn-success">Submit request</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card card-shadow border-0 p-4">
                    <h3>Contact details</h3>
                    <p class="text-muted">Our team is here to support your waste management and sanitization efforts.</p>
                    <ul class="list-unstyled mt-4" style="line-height:1.9">
                        <li><strong>Email:</strong> support@swms.example.com</li>
                        <li><strong>Phone:</strong> +1 (555) 123-4567</li>
                        <li><strong>Office:</strong> Smart Campus Operations, 123 Innovation Blvd.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<footer class="text-center py-4" style="background:#fff;border-top:1px solid #e8ecf0">
    <div class="container">
        © {{ date('Y') }} SwMS — Smart Waste Management & Sanitization System
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
