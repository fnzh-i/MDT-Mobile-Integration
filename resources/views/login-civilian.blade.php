<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MDT - Mobile Data Terminal</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/login-civilian.css') }}">
</head>
<body>
    <div class="page-wrapper d-flex align-items-center justify-content-center min-vh-100">
        <div class="form-card">
            <div class="form-header text-center mb-4">
                <h1 class="form-title">LOGIN</h1>
                <p class="form-subtitle">Welcome to the MDT System.<br>Please login to continue.</p>

                <form action="{{ route('login-civilian') }}" method="post">
                    @csrf

                    <div class="mb-3">
                        <input type="text" id="email" name="email" class="input-custom @error('email') is-invalid @enderror" placeholder="Email" value="{{ old('email') }}" autocomplete="email" required>

                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-1">
                        <div class="input-password-wrapper">
                            <input type="password" id="password" name="password" class="input-custom @error('password') is-invalid @enderror" placeholder="Password" autocomplete="current-password" required>

                            <button type="button" class="password-toggle" onclick="togglePassword('password', this)" tabindex="-1"> <i class="bi bi-eye"></i> </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="text-end mb-4">
                        <a href="#" class="forgot-link" data-bs-toggle="modal" data-bs-target="#forgotModal">Forgot Password?</a>
                    </div>

                    @if (session('error'))
                        <div class="alert-error mb-3">{{ session('error') }}</div>
                    @endif

                    <button type="submit" class="btn-login w-100 mb-2">LOGIN</button>

                    <hr class="btn-divider">

                    <a href="{{ route('register') }}" class="btn-create w-100 d-block text-center mb-4">CREATE ACCOUNT</a>
                    <p class="text-center help-text mb-1">Need help? <a href="#" class="support-link" data-bs-toggle="modal" data-bs-target="#supportModal">Contact Support</a></p>
                    <p class="text-center copyright mb-0">
                        © 2025 MDT System. All rights reserved.
                    </p>
                </form>
            </div>
        </div>
        <div class="modal fade" id="forgotModal" tabindex="-1" aria-labelledby="forgotModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-custom">
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title-custom" id="forgotModalLabel"> <i class="bi bi-key me-2"></i> Forgot Password</h5>
                        <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close"> <i class="bi bi-x-lg"></i> </button>
                    </div>
                    <div class="modal-body modal-body-custom">
                        <p class="modal-desc">Enter your email address and we'll notify an admin to assist you with resetting your password.</p>
                        <form action="{{ route('forgot-password') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="forgot_email" class="form-label-custom">Email Address</label>
                                <input type="email" id="forgot_email" name="email" class="input-custom" placeholder="example@example.com" required>
                            </div>
                            <button type="submit" class="btn-login w-100">Send Request</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="supportModal" tabindex="-1" aria-labelledby="supportModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-custom">
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title-custom" id="supportModalLabel"> <i class="bi bi-headset me-2"></i>How can we help?</h5>
                        <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close"> <i class="bi bi-x-lg"></i> </button>
                    </div>
                    <div class="modal-body modal-body-custom">
                        <div class="support-options">
                            <div class="support-option" data-bs-toggle="modal" data-bs-target="#callModal" data-bs-dismiss="modal">
                                <div class="support-icon"> <i class="bi bi-telephone"></i> </div>
                                <div class="support-text">
                                    <p class="support-title">Call Support</p>
                                    <p class="support-sub">+63 000 0000 000</p>
                                </div>
                            </div>
                            <div class="support-option" data-bs-toggle="modal" data-bs-target="#emailModal" data-bs-dismiss="modal">
                                <div class="support-icon"> <i class="bi bi-envelope"></i> </div>
                                <div class="support-text">
                                    <p class="support-title">Email Support</p>
                                    <p class="support-sub">support@example.com</p>
                                </div>
                            </div>
                            <div class="support-option" data-bs-toggle="modal" data-bs-target="#faqModal" data-bs-dismiss="modal">
                                <div class="support-icon"> <i class="bi bi-chat-dots"></i> </div>
                                <div class="support-text">
                                    <p class="support-title">FAQ</p>
                                    <p class="support-sub">Common Questions & Answers</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="callModal" tabindex="-1" aria-labelledby="callModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-custom">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title-custom" id="callModalLabel"> <i class="bi bi-telephone me-2"></i> Call Support</h5>
                    <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close"> <i class="bi bi-x-lg"></i> </button>
                </div>
                <div class="modal-body modal-body-custom text-center">
                    <p class="modal-desc">Our support line is available all day.</p>
                    <p class="support-sub">+63 000 0000 000</p>
                </div>
                <div class="modal-footer modal-footer-custom">
                    <button class="btn-modal-close" type="button" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-custom">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title-custom" id="emailModalLabel"> <i class="bi bi-envelope me-2"></i> Email Support</h5>
                    <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close"> <i class="bi bi-x-lg"></i> </button>
                </div>
                <div class="modal-body modal-body-custom text-center">
                    <p class="modal-desc">Send a message to our admin and we'll get back to you within 4 to 3 business days.</p>
                    <form action="{{ route ('support.email') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="support_message" class="form-label-custom">Message</label>
                            <textarea id="support_message" name="message" class="input-custom input-textarea" placeholder="Describe your issue..." rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn-login w-100">Send Message</button>
                    </form>
                    @if (session('support_success'))
                    <div class="alert-error mb-3" style="color: green;">{{ session('support_success') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="faqModal" tabindex="-1" aria-labelledby="faqModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content modal-custom">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title-custom" id="faqModalLabel"> <i class="bi bi-question-circle me-2"></i> Frequently Asked Questions</h5>
                    <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close"> <i class="bi bi-x-lg"></i> </button>
                </div>
                <div class="modal-body modal-body-custom">
                    <div class="faq-item">
                        <p class="faq-q">How do I reset my password?</p>
                        <p class="faq-a">Click the "Forgot password?" link on the login screen.</p>
                    </div>
                    <div class="faq-item">
                        <p class="faq-q">The app won't load?</p>
                        <p class="faq-a">Check your network connection or try restarting the app.</p>
                    </div>
                    <div class="faq-item">
                        <p class="faq-q">Response time of Email Support?</p>
                        <p class="faq-a">4 hours to 3 business days.</p>
                    </div>
                    <div class="faq-item">
                        <p class="faq-q">Call Support availability?</p>
                        <p class="faq-a">Our call support line is available all day.</p>
                    </div>
                    <div class="faq-item">
                        <p class="faq-q">How do I create an account?</p>
                        <p class="faq-a">Click the "Create Account" button on the login screen and fill out the registration form.</p>
                    </div>
                </div>
                <div class="modal-footer modal-footer-custom">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/login-civilian.js') }}"></script>
</body>
</html>