<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MDT - Secret Login</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/login-civilian.css') }}">
</head>
<body>
    <div class="page-wrapper d-flex align-items-center justify-content-center min-vh-100">
        <div class="form-card">
            <div class="form-header text-center mb-4">
                <h1 class="form-title">SECRET LOGIN</h1>
                <p class="form-subtitle">Authorized Personnel Only.<br>Please enter your credentials.</p>

                <form method="POST" action="{{ route('secret-login') }}">
                    @csrf

                    <div class="mb-3">
                        <input id="email" type="email" name="email" class="input-custom @error('email') is-invalid @enderror" placeholder="Email Address" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        
                        @error('email')
                            <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-1">
                        <div class="input-password-wrapper">
                            <input id="password" type="password" name="password" class="input-custom @error('password') is-invalid @enderror" placeholder="Password" required autocomplete="current-password">
                            
                            <button type="button" class="password-toggle" onclick="togglePassword('password', this)" tabindex="-1">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        
                        @error('password')
                            <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label help-text" for="remember" style="font-size: 0.85rem;">
                                Remember Me
                            </label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="#" class="forgot-link" data-bs-toggle="modal" data-bs-target="#forgotModal">Forgot Password?</a>
                        @endif
                    </div>

                    @if (session('error'))
                        <div class="alert-error mb-3">{{ session('error') }}</div>
                    @endif

                    <button type="submit" class="btn-login w-100 mb-4">ACCESS TERMINAL</button>

                    <p class="text-center copyright mb-0">
                        © 2025 MDT System. Secure Access Node.
                    </p>
                </form>
            </div>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/login-civilian.js') }}"></script>
</body>
</html>