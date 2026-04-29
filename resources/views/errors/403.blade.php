<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Access Denied</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0b1f33 0%, #143f67 52%, #ebf2fa 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            color: #10233a;
        }
        .forbidden-card {
            width: min(92vw, 560px);
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid rgba(6, 71, 137, 0.16);
            border-radius: 24px;
            box-shadow: 0 24px 80px rgba(0, 0, 0, 0.22);
            padding: 34px 32px;
            text-align: center;
        }
        .forbidden-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: #064789;
            color: #fff;
            font-size: 2rem;
            margin-bottom: 18px;
        }
        .forbidden-title {
            font-size: 2rem;
            font-weight: 700;
            color: #064789;
            margin-bottom: 10px;
        }
        .forbidden-text {
            font-size: 1rem;
            color: #4f647d;
            margin-bottom: 26px;
            line-height: 1.6;
        }
        .forbidden-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn-forbidden {
            min-width: 160px;
            padding: 0.8rem 1.2rem;
            border-radius: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .btn-forbidden:hover {
            transform: translateY(-1px);
        }
        .btn-primary-forbidden {
            background: #064789;
            color: #fff;
            box-shadow: 0 10px 20px rgba(6, 71, 137, 0.2);
        }
        .btn-primary-forbidden:hover {
            color: #fff;
        }
        .btn-secondary-forbidden {
            background: #e9eef5;
            color: #064789;
        }
    </style>
</head>
<body>
    <div class="forbidden-card">
        <div class="forbidden-badge">
            <i class="bi bi-shield-lock"></i>
        </div>
        <h1 class="forbidden-title">Access Denied</h1>
        <p class="forbidden-text">
            You do not have permission to view this page or use this function.
            If you believe this is a mistake, contact an administrator.
        </p>
        <div class="forbidden-actions">
            <a href="{{ url()->previous() }}" class="btn-forbidden btn-secondary-forbidden">Go Back</a>
            <a href="{{ route('login-civilian') }}" class="btn-forbidden btn-primary-forbidden">Return to Login</a>
        </div>
    </div>
</body>
</html>
