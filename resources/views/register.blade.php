<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MDT - Mobile Data Terminal</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body>
    <div class="page-wrapper d-flex align-items-center justify-content-center min-vh-100 py-5">
        <div class="form-card">
            <div class="form-header text-center mb-4">
                <h1 class="form-title">Register</h1>
                <p class="form-subtitle">Create a new civilian profile for the MDT system.</p>
            </div>

            <form action="{{ route('register') }}" class="" method="post">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-4">
                        <label for="first_name" class="form-label-custom">First Name</label>
                        <input type="text" id="first_name" name="first_name" class="input-custom @error('first_name') is-invalid @enderror" placeholder="First Name" value="{{ old('first_name') }}" required>

                        @error('first_name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="middle_name" class="form-label-custom">Middle Name</label>
                        <input type="text" id="middle_name" name="middle_name" class="input-custom" placeholder="Middle Name" value="{{ old('middle_name') }}">
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="last_name" class="form-label-custom">Last Name</label>
                        <input type="text" id="last_name" name="last_name" class="input-custom @error('last_name') is-invalid @enderror" placeholder="Last Name" value="{{ old('last_name') }}" required>

                        @error('last_name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="license_number" class="form-label-custom">License Number</label>
                        <input type="text" id="license_number" name="license_number" class="input-custom @error('license_number') is-invalid @enderror" placeholder="License Number" value="{{ old('license_number') }}" required>

                        @error('license_number')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="date_of_birth" class="form-label-custom">Date of Birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" class="input-custom @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth') }}" required>

                        @error('date_of_birth')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label for="weight" class="form-label-custom">Weight (kg)</label>
                            <input type="number" id="weight" name="weight" class="input-custom @error('weight') is-invalid @enderror" placeholder="Weight (kg)" step="0.1" min="1" value="{{ old('weight') }}" required>

                            @error('weight')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="height" class="form-label-custom">Height (cm)</label>
                            <input type="number" id="height" name="height" class="input-custom @error('height') is-invalid @enderror" placeholder="Height (cm)" step="0.01" min="0.5" value="{{ old('height') }}" required>

                            @error('height')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="expiration_date" class="form-label-custom">Expiration Date</label>
                        <input type="date" id="expiration_date" name="expiration_date" class="input-custom @error('expiration_date') is-invalid @enderror" value="{{ old('expiration_date') }}" required>

                        @error('expiration_date')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="section-divider">

                    <div class="mb-3">
                        <label for="username" class="form-label-custom">Username</label>
                        <input type="text" id="username" name="username" class="input-custom @error('username') is-invalid @enderror" placeholder="Username" value="{{ old('username') }}" required>

                        @error('username')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label-custom">Email</label>
                        <input type="email" id="email" name="email" class="input-custom @error('email') is-invalid @enderror" placeholder="example@example.com" value="{{ old('email') }}" required>

                        @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label-custom">Password</label>
                        <div class="input-password-wrapper">
                            <input type="password" id="password" name="password" class="input-custom @error('password') is-invalid @enderror" placeholder="Password" autocomplete="new-password" required>

                            <button class="password-toggle" type="button" onclick="togglePassword('password', this)" tabindex="-1"> 
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>

                        @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label-custom">Confirm Password</label>
                        <div class="input-password-wrapper">
                            <input type="password" id="password_confirmation" name="password_confirmation" class="input-custom @error('password_confirmation') is-invalid @enderror" placeholder="Confirm Password" autocomplete="new-password" required>

                            <button class="password-toggle" type="button" onclick="togglePassword('password_confirmation', this)" tabindex="-1"> <i class="bi bi-eye"></i> </button>
                        </div>

                        @error('password_confirmation')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <div class="checkbox-wrapper">
                            <input type="checkbox" id="agree_terms" name="agree_terms" class="checkbox-custom" required>
                            <label for="agree_terms" class="checkbox-label">I agree to the  <a href="#" class="policy-link" data-bs-toggle="modal" data-bs-target="#termsModal">Terms & Conditions</a>.</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="checkbox-wrapper">
                            <input type="checkbox" id="agree_privacy" name="agree_privacy" class="checkbox-custom" required>
                            <label for="agree_privacy" class="checkbox-label">I agree to the <a href="#" class="policy-link" data-bs-toggle="modal" data-bs-target="#privacyModal">Privacy Policy</a>.</label>
                        </div>
                    </div>

                    <button type="submit" class="btn-register w-100">Register</button>

                    <p class="text-center mt-3 mb-0 back-to-login">Already have an account? <a href="{{ route('login-civilian') }}" class="login-link">Back to Login</a></p>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content modal-custom">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title-custom" id="termsModalLabel">Terms & Conditions</h5>
                   <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close"> <i class="bi bi-x-lg"></i> </button>
                </div>
                <div class="modal-body modal-body-custom">
                    <div class="policy-section">
                        <h6 class="policy-heading">1. Overview</h6>
                        <p class="policy-text">The MDT System (Management of Driver and Traffic system) is an application designed to help authorities manage information on vehicles, drivers, and traffic violation records. Through this system, it is possible to create, view, and update traffic violation tickets and other related records.</p>
                    </div>
                    <div class="policy-section">
                        <h6 class="policy-heading">2. Data Collection</h6>
                        <p class="policy-text">The MDT System collects personal and non-personal information necessary to provide services — including names, addresses, license numbers, vehicle information, and details of violations. We safeguard this data and use it solely for the purposes of system operations.</p>
                    </div>
                    <div class="policy-section">
                        <h6 class="policy-heading">3.  Use of Information</h6>
                        <p class="policy-text">The collected information will be used for:</p>
                        <ul class="policy-list">
                            <li>(a) processing and issuing tickets;</li>
                            <li>(b) record-keeping and reporting;</li>
                            <li>(c) law enforcement and coordination with other agencies if necessary;</li>
                            <li>(d) analytics for service improvement.</li>
                        </ul>
                    </div>
                    <div class="policy-section">
                        <h6 class="policy-heading">4. User Obligations</h6>
                        <p class="policy-text">As a user, you agree that:</p>
                        <ul class="policy-list">
                            <li>(a) you will provide only truthful and accurate information;</li>
                            <li>(b) you will not use the account for illegal activities;</li>
                            <li>(c) you will comply with policies and laws applicable to the handling of personal information.</li>
                        </ul>
                    </div>
                    <div class="policy-section">
                        <h6 class="policy-heading">5. Limitation of Liability</h6>
                        <p class="policy-text">Although the MDT System strives to protect data, it shall not be liable for unforeseen events that result in the loss or unauthorized access of information, except in cases of gross negligence by the system management.</p>
                    </div>
                    <div class="policy-section">
                        <h6 class="policy-heading">6. Changes in Terms</h6>
                        <p class="policy-text">These terms may be updated from time to time. Continued use of the system means you agree to the changes. We will try to notify major changes through a notification or email.</p>
                    </div>
                    <div class="policy-section">
                        <h6 class="policy-heading">7. Contact Information</h6>
                        <p class="policy-text mb-0">For questions about the Terms, contact the system administrator or support team of the MDT System.</p>
                    </div>
                </div>
                <div class="modal-footer modal-footer-custom">
                    <button type="button" class="btn-modal-close" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content modal-custom">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title-custom" id="privacyModalLabel"> <i class="bi bi-shield-check me-2"></i> Privacy Policy</h5>
                    <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close"> <i class="bi bi-x-lg"></i> </button>
                </div>
                <div class="modal-body modal-body-custom">
                    <div class="policy-section">
                        <h6 class="policy-heading">1. Purpose</h6>
                        <p class="policy-text">The purpose of this policy is to explain how the MDT System collects, uses, stores, and shares the personal information of users and recorded individuals.</p>
                    </div>
                    <div class="policy-section">
                        <h6 class="policy-heading">2. What Information Is Collected</h6>
                        <p class="policy-text">The MDT System may collect the following: name, date of birth, license number, address, contact details, vehicle plate number, photo of the driver or vehicle (if uploaded), and details of the violation or case history.</p>
                    </div>
                    <div class="policy-section">
                        <h6 class="policy-heading">3. Use of Information</h6>
                        <p class="policy-text">Data is used for identity verification, ticket processing and case management, report creation, and granting access to authorized users. It is also used for internal analytics and service enhancement.</p>
                    </div>
                    <div class="policy-section">
                        <h6 class="policy-heading">4. Data Retention</h6>
                        <p class="policy-text">Data will be stored in accordance with local laws and regulations and for the period necessary for operation and auditing. Data that is no longer needed will be securely deleted or anonymized.</p>
                    </div>
                    <div class="policy-section">
                        <h6 class="policy-heading">5. Data Sharing</h6>
                        <p class="policy-text">Data may be shared with authorized agencies (e.g., traffic enforcement, law enforcement) if required by law or for the implementation of regulations. Personal data is not sold to third parties for commercial marketing without consent.</p>
                    </div>
                    <div class="policy-section">
                        <h6 class="policy-heading">6. Security</h6>
                        <p class="policy-text">There are technical and organizational controls implemented to protect data (e.g., access control, encryption in transit or storage if available). However, no system is 100% secure; user caution is required in handling their account credentials.</p>
                    </div>
                    <div class="policy-section">
                        <h6 class="policy-heading">7. User Rights</h6>
                        <p class="policy-text">Individuals recorded in the data have the right to request access, clarification, correction, or deletion (if applicable). For any requests, contact the data protection officer or administrator of the MDT System.</p>
                    </div>
                </div>
                <div class="modal-footer modal-footer-custom">
                    <button type="button" class="btn-modal-close" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/register.js') }}"></script>
</body>
</html>