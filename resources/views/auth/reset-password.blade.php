<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - MDT</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/supervisor-dashboard.css') }}">
    <style>
        /* Center the card on the page if it's not inside a dashboard */
        body {
            background-color: #f4f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Inter', sans-serif;
        }
        .settings-card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }
    </style>
</head>
<body>

<div class="settings-container">
    <div class="settings-card">
        <h2 class="settings-title">Set New Password</h2>
        <div style="border-top:1.5px solid #d0daea; margin:20px 0 16px"></div>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p class="mb-0">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="settings-field">
                <label class="settings-label">Email Address</label>
                <input type="email" class="lookup-input" style="width:100%; background:#f8f9fa;" value="{{ $email }}" disabled>
            </div>

            <div class="settings-field">
                <label class="settings-label">New Password</label>
                <input type="password" name="password" class="lookup-input" style="width:100%" placeholder="Enter new password" required autofocus>
            </div>

            <div class="settings-field">
                <label class="settings-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="lookup-input" style="width:100%" placeholder="Confirm new password" required>
            </div>

            <button type="submit" class="btn-submit-ticket">Reset Password</button>
        </form>
    </div>
</div>

</body>
</html>