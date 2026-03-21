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
    <link rel="stylesheet" href="{{ asset('css/civilian-dashboard.css') }}">
</head>
<body>
    <div class="shell">
        <div class="topbar">
            <div class="topbar-left">
                <button class="hamburger" id="hamburgerBtn" aria-label="Toggle menu"> <span></span> <span></span> <span></span> </button>
                <span class="topbar-title">Mobile Data Terminal</span>
            </div>
            <div class="topbar-right">
                <form action="{{ route('logout') }}" method="POST"> {{-- Add Route --}}
                    @csrf
                    <button type="submit" class="btn btn-logout"> <i class="bi bi-box-arrow-right me-1"></i> Logout</button>
                </form>
            </div>
        </div>

        <div class="body-area">
            <div class="overlay" id="overlay"></div>
            <aside class="sidebar" id="sidebar">
                <div class="user-block">
                    <div class="user-avatar"> <i class="bi bi-person"></i> </div>
                    <div class="user-role"> Civilian </div>
                    <div class="user-name"> Sample User </div>
                </div>
                <nav>
                    <a href="{{ route('civilian-dashboard') }}" class="nav-link {{ $section === 'dashboard' ? 'active' : '' }}"> <i class="bi bi-speedometer2 me-2"></i> Dashboard </a>
                    <a href="{{ route('civilian-license') }}" class="nav-link {{ $section === 'license' ? 'active' : '' }}"> <i class="bi bi-card-heading me-2"></i> View Full License</a>
                    <a href="{{ route('civilian-vehicle') }}" class="nav-link {{ $section === 'vehicle' ? 'active' : '' }}"> <i class="bi bi-car-front me-2"></i> View All Vehicles</a>
                    <a href="{{ route('civilian-violations') }}" class="nav-link {{ $section === 'violations' ? 'active': '' }}"> <i class="bi bi-exclamation-triangle me-2"></i> View Violations</a>
                    <a href="{{ route('civilian-settings') }}" class="nav-link {{ $section === 'settings' ? 'active': '' }}"> <i class="bi bi-gear me-2"></i> Settings</a>
                </nav>
            </aside>

            <main class="main" id="main">
                @if ($section === 'dashboard')
                    <h1 class="dash-title">Civilian Dashboard</h1>
                    <p class="dash-sub">Select a category type to display data</p>
 
                    <div class="cards-grid">
                        <div class="dash-card">
                            <div class="card-header-custom">
                                <div class="card-icon"> <i class="bi bi-person-vcard"></i> </div>
                                <span class="card-label">Driver's License</span>
                            </div>
                            <div class="card-body-custom">
                                <div class="card-detail"> <span class="detail-label">Name:</span> Sample Name </div>
                                <div class="card-detail"> <span class="detail-label">License Number:</span> Sample License Number </div>
                                <div class="card-detail"> <span class="detail-label">Status:</span> <span class="status-green">Sample Status</span> </div>
                                <div class="card-detail"> <span class="detail-label">Expiry Date:</span> Sample Expiry Date</div>
                            </div>
                            <div class="card-footer-custom">
                                <a href="{{ route('civilian-license') }}" class="btn-card">View Full License Details</a>
                            </div>
                        </div>
                        
                        <div class="dash-card">
                            <div class="card-header-custom">
                                <div class="card-icon"> <i class="bi bi-car-front"></i> </div>
                                <span class="card-label">Registered Vehicles</span>
                            </div>
                            <div class="card-body-custom">
                                <div class="card-detail"> <span class="detail-label">Model:</span> Sample Model </div>
                                <div class="card-detail"> <span class="detail-label">Plate Number:</span> Sample Plate Number </div>
                                <div class="card-detail"> <span class="detail-label">Status:</span> <span class="status-green">Sample Status</span> </div>
                                <div class="card-detail"> <span class="detail-label">Registration Expiry:</span> Sample Registration Expiry </div>
                            </div> 
                            <div class="card-footer-custom">
                                <a href="{{ route('civilian-vehicle') }}" class="btn-card">View All Vehicles</a> 
                            </div>
                        </div>

                        <div class="dash-card">
                            <div class="card-header-custom">
                                <div class="card-icon"> <i class="bi bi-file-earmark-text"></i> </div>
                                <span class="card-label">Ticket Violations</span>
                            </div>
                            <div class="card-body-custom">
                                <div class="card-detail"> <span class="status-red fw-bold">Sample Violation</span> </div>
                                <div class="card-detail"> <span class="detail-label">Issued:</span> Sample Issued Date </div>
                                <div class="card-detail"> <span class="detail-label">Fined:</span> Sample Fine Amount </div>
                            </div>
                            <div class="card-footer-custom">
                                <a href="{{ route('civilian-violations') }}" class="btn-card">View All Violations</a>
                            </div>
                        </div>
                    </div>

                @elseif ($section === 'license')
                    <h1 class="dash-title">Driver's License</h1>

                    <div class="section-card">
                        <div class="section-card-accent"></div>
                        <div class="section-card-inner">
                            <div class="license-two-col">

                                <div>
                                    <h2 class="section-card-title mb-3">Personal Information</h2>
                                    <div class="info-row"><span class="info-label">Last Name:</span> Sample Last Name</div>
                                    <div class="info-row"><span class="info-label">First Name:</span> Sample First Name</div>
                                    <div class="info-row"><span class="info-label">Middle Name:</span> Sample Middle Name</div>
                                    <div class="info-row"><span class="info-label">Date of Birth:</span> Sample DOB</div>
                                    <div class="info-row"><span class="info-label">Sex:</span> Sample Gender</div>
                                    <div class="info-row"><span class="info-label">Address:</span> Sample Address</div>
                                    <div class="info-row"><span class="info-label">Nationality:</span> Sample Nationality</div>
                                    <div class="info-row"><span class="info-label">Height:</span> Sample Height</div>
                                    <div class="info-row"><span class="info-label">Weight:</span> Sample Weight</div>
                                    <div class="info-row"><span class="info-label">Eye Color:</span> Sample Eye Color</div>
                                    <div class="info-row"><span class="info-label">Blood Type:</span> Sample Blood Type</div>
                                    <div class="info-row"><span class="info-label">Conditions:</span> Sample Conditions</div>
                                </div>

                                <div>
                                    <h2 class="section-card-title mb-3">License Status</h2>
                                    <div class="info-row">
                                        <span class="info-label">Status:</span>
                                        <span class="status-green">Sample Status</span>
                                        <span class="status-dot dot-green" style="margin-left: 3px;"></span>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="section-card">
                        <div class="section-card-accent"></div>
                        <div class="section-card-inner">
                            <h2 class="section-card-title mb-3">License Details</h2>
                            <div class="license-two-col">

                                <div>
                                    <div class="info-row"><span class="info-label">License Number:</span> Sample License Number</div>
                                    <div class="info-row"><span class="info-label">License Type:</span> Sample License Type</div>
                                    <div class="info-row"><span class="info-label">Restrictions:</span> Sample Restrictions</div>
                                </div>

                                <div>
                                    <div class="info-row"><span class="info-label">Issue Date:</span> Sample Issue Date</div>
                                    <div class="info-row"><span class="info-label">Expiry Date:</span> Sample Expiry Date</div>
                                </div>

                            </div>
                        </div>
                    </div>
                
                 @elseif ($section === 'vehicle')
                    <h1 class="dash-title">Vehicle Search</h1>

                    @if ($selectedVehicle) {{-- To get this working adjust the controller --}}
                        <div class="section-card">
                            <div class="section-card-accent"></div>
                            <div class="section-card-inner">
                                <div class="section-card-header">
                                    <h2 class="section-card-title">Vehicle Information</h2>
                                </div>
                                <div class="section-card-body">
                                    <div class="info-row"><span class="info-label">Status:</span> <span class="status-green">Sample Status</span></div>
                                    <div class="info-row"><span class="info-label">MV File Number:</span> Sample MV File Number</div>
                                    <div class="info-row"><span class="info-label">Model:</span> Sample Model</div>
                                    <div class="info-row"><span class="info-label">Color:</span> Sample Color</div>
                                    <div class="info-row"><span class="info-label">Registration Expiry:</span> Sample Expiry Date</div>
                                    <div class="info-row"><span class="info-label">Plate Number:</span> Sample Plate Number</div>
                                    <div class="info-row"><span class="info-label">Make:</span> Sample Make</div>
                                    <div class="info-row"><span class="info-label">Year:</span> Sample Year</div>
                                    <div class="info-row"><span class="info-label">VIN:</span> Sample VIN</div>
                                </div>
                            </div>
                        </div>

                        <div class="section-card">
                            <div class="section-card-accent"></div>
                            <div class="section-card-inner">
                                <div class="section-card-header">
                                    <h2 class="section-card-title">Registered Owner</h2>
                                </div>
                                <div class="section-card-body">
                                    <div class="info-row"><span class="info-label">Name:</span> Sample Name</div>
                                    <div class="info-row"><span class="info-label">License Number:</span> Sample License Number</div>
                                    <div class="info-row"><span class="info-label">Address:</span> Sample Address</div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-2 mb-4">
                            <a href="{{ route('civilian-vehicle') }}" class="btn-back">
                                <i class="bi bi-arrow-left me-1"></i> Back to All Vehicles
                            </a>
                        </div>

                    @else
                        <form method="GET" action="{{ route('civilian-vehicle') }}" class="vehicle-search-form">
                            <input type="text" name="plate" class="vehicle-search-input"
                                placeholder="Enter plate number..."
                                value="{{ request('plate') }}" autocomplete="off">
                            <button type="submit" class="btn-search">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                        </form>

                        <div class="vehicles-grid">
                            <div class="vehicle-card">
                                <div class="vehicle-card-top">
                                    <div class="vehicle-card-icon">
                                        <i class="bi bi-car-front"></i>
                                    </div>
                                    <div class="vehicle-card-info">
                                        <div class="vehicle-info-text">Sample Make</div>
                                        <div class="vehicle-info-text">Sample Model</div>
                                        <div class="vehicle-info-text">Sample Plate Number</div>
                                        <div class="vehicle-info-text status-green">Sample Status</div>
                                    </div>
                                </div>
                                <a href="{{ route('civilian-vehicle') }}?view=1" class="btn-card">View Full Details</a>
                            </div>
                        </div>
                    @endif

                @elseif ($section === 'violations')
                    <h1 class="dash-title">Ticket Violations</h1>
                    <p class="dash-sub">View All of the Violations</p>

                    <div class="violations-grid">
                        <div class="violation-card">
                            <div class="info-row violation-offence"> Sample Offence </div>
                            <div class="info-row"> <span class="info-label">Date:</span> Sample Date </div>
                            <div class="info-row"> <span class="info-label">Place:</span> Sample Place </div>
                            <div class="info-row"> <span class="info-label">Note:</span> Sample Note </div>
                            <div class="info-label"> <span class="info-label">Fine:</span> Sample Fine </div>
                        </div>

                        <div class="violation-card">
                            <div class="info-row violation-offence"> Sample Offence </div>
                            <div class="info-row"> <span class="info-label">Date:</span> Sample Date </div>
                            <div class="info-row"> <span class="info-label">Place:</span> Sample Place </div>
                            <div class="info-row"> <span class="info-label">Note:</span> Sample Note </div>
                            <div class="info-label"> <span class="info-label">Fine:</span> Sample Fine </div>
                        </div>
                    </div>

                  @elseif ($section === 'settings')
                    <h1 class="dash-title">Settings</h1>
                    <p class="dash-sub">Submit a ticket for account-related changes or to contact support.</p>

                    <div class="settings-card">
                        <h2 class="settings-form-title">Support Ticket Form</h2>
                        <p class="settings-form-desc">Administrators must manually approve account changes. Use this form to request:</p>
                        <ul class="settings-list">
                            <li>Password change or reset</li>
                            <li>Update to account name or admin details</li>
                            <li>Recovery for forgotten password</li>
                            <li>Report account or login issues</li>
                            <li>Contact administrative support</li>
                        </ul>

                        <form action="{{ route('civilian-support') }}" method="POST"> {{-- Add Route--}}
                            @csrf
                            <div class="settings-field">
                                <label for="support_category" class="settings-label">Support Category</label>
                                <select id="support_category" name="category" class="settings-select" required>
                                    <option value="" disabled selected>Select a category</option>
                                    <option value="password_change">Password Change</option>
                                    <option value="forgot_password">Forgot Password / Reset</option>
                                    <option value="account_update">Account Name / Details Update</option>
                                    <option value="login_issue">Account or Login Issue</option>
                                    <option value="other">Other Request</option>
                                </select>
                            </div>

                            <div class="settings-field">
                                <label for="support_message" class="settings-label">Describe Your Issue</label>
                                <textarea id="support_message" name="message" class="settings-textarea" placeholder="Explain what you need help with..." rows="5" required></textarea>
                            </div>

                            <button type="submit" class="btn-submit-ticket">Submit Ticket</button>
                        </form>
                    </div>
                @endif  
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/civilian-dashboard.js') }}"></script>
</body>
</html>