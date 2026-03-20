<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MDT - Mobile Data Terminal</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body class="landing-page">
    <div class="logo-wrap">
        <img src="{{ asset('images/mdt_logo.png') }}" alt="MDT Logo">
    </div>
    
    <h1 class="landing-title">
        Instant vehicle & license data. Smarter MDT. Faster civil processes.
    </h1>

    <a href="{{ route('login-civilian') }}" class="btn btn-mdt btn-lg px-5 mb-5">Get Started</a>

    <div class="cards-row">
        <div class="feature-card">
            <i class="bi bi-receipt mb-3 feature-icon"></i>
            <h6>Smart Ticketing</h6>
            <p>Easily create, view, and update, traffic violation tickets</p>
        </div>
        <div class="feature-card">
            <i class="bi bi-person-check mb-3 feature-icon"></i>
            <h6>User-Friendly Dashboard</h6>
            <p>Navigate between vehicle and driver labs seamslessly</p>
        </div>
        <div class="feature-card">
            <i class="bi bi-phone mb-3 feature-icon"></i>
            <h6>Responsive Interface</h6>
            <p>Access the sytem from desktop or mobile securely</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>