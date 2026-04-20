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

<div class="shell">
    <div class="body-area">
        <div class="overlay" id="overlay"></div>
        <aside class="sidebar" id="sidebar">
                <div class="user-block">
                    <div class="user-avatar"> <i class="bi bi-person"></i> </div>
                    <div class="user-role"> Civilian </div>
                    <div class="user-name"> {{ $userName ?? 'User' }} </div>
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
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error:</strong> {{ $errors->first('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

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
                                <div class="card-detail"> <span class="detail-label">Name:</span> {{ $licenseData->firstName ?? 'N/A' }} {{ $licenseData->lastName ?? '' }} </div>
                                <div class="card-detail"> <span class="detail-label">License Number:</span> {{ $licenseData->licenseNumber ?? 'N/A' }} </div>
                                <div class="card-detail"> <span class="detail-label">Status:</span> <span class="status-green">{{ $licenseData->status ?? 'N/A' }}</span> </div>
                                <div class="card-detail"> <span class="detail-label">Expiry Date:</span> {{ $licenseData->expiryDate ?? 'N/A' }}</div>
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
                                <div class="card-detail"> <span class="detail-label">Model:</span> {{ $vehicleData->make ?? 'N/A' }} {{ $vehicleData->model ?? '' }} </div>
                                <div class="card-detail"> <span class="detail-label">Plate Number:</span> {{ $vehicleData->plateNumber ?? 'N/A' }} </div>
                                <div class="card-detail"> <span class="detail-label">Status:</span> <span class="status-green">{{ $vehicleData->status ?? 'N/A' }}</span> </div>
                                <div class="card-detail"> <span class="detail-label">Registration Expiry:</span> {{ $vehicleData->regExpiryDate ?? 'N/A' }} </div>
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
                                <div class="card-detail"> <span class="status-red fw-bold">{{ count($violations ?? []) }} Violations</span> </div>
                                <div class="card-detail"> <span class="detail-label">View</span> Your traffic violations </div>
                                <div class="card-detail"> <span class="detail-label">Stay</span> Updated on status </div>
                            </div>
                            <div class="card-footer-custom">
                                <a href="{{ route('civilian-violations') }}" class="btn-card">View All Violations</a>
                            </div>
                        </div>
                    </div>

                @elseif ($section === 'license')
                    <h1 class="dash-title">Driver's License</h1>

                    <div class="section-card">
                        <div class="section-card-inner">
                            <div class="section-card-header">
                                <h2 class="section-card-title">License Status</h2>
                                <span class="status-dot dot-green"></span>
                            </div>
                            <div class="section-card-body">
                                <div class="info-row"> <span class="info-label">Status:</span> <span class="status-green">{{ $licenseData->status ?? 'N/A' }}</span> </div>
                            </div>
                        </div>
                    </div>

                    <div class="section-card">
                        <div class="section-card-inner">
                            <div class="section-card-header"> <h2 class="section-card-title">Personal Information</h2> </div>
                            <div class="section-card-body">
                                <div class="info-row"> <span class="info-label">Last Name:</span> <span class="info-value">{{ $licenseData->lastName ?? 'N/A' }}</span> </div>
                                <div class="info-row"> <span class="info-label">First Name:</span> <span class="info-value">{{ $licenseData->firstName ?? 'N/A' }}</span> </div>
                                <div class="info-row"> <span class="info-label">Middle Name:</span> <span class="info-value">{{ $licenseData->middleName ?? 'N/A' }}</span> </div>
                                <div class="info-row"> <span class="info-label">Suffix:</span> <span class="info-value">{{ $licenseData->suffix ?? 'N/A' }}</span> </div>
                                <div class="info-row"> <span class="info-label">Date of Birth:</span> <span class="info-value">{{ $licenseData->dateOfBirth ?? 'N/A' }}</span> </div>
                                <div class="info-row"> <span class="info-label">Gender:</span> <span class="info-value">{{ $licenseData->gender ?? 'N/A' }}</span> </div>
                                <div class="info-row"> <span class="info-label">Address:</span> <span class="info-value">{{ $licenseData->address ?? 'N/A' }}</span> </div>
                                <div class="info-row"> <span class="info-label">Nationality:</span> <span class="info-value">{{ $licenseData->nationality ?? 'N/A' }}</span> </div>
                                <div class="info-row"> <span class="info-label">Height:</span> <span class="info-value">{{ $licenseData->height ?? 'N/A' }}</span> </div>
                                <div class="info-row"> <span class="info-label">Weight:</span> <span class="info-value">{{ $licenseData->weight ?? 'N/A' }}</span> </div>
                                <div class="info-row"> <span class="info-label">Eye Color:</span> <span class="info-value">{{ $licenseData->eyeColor ?? 'N/A' }}</span> </div>
                                <div class="info-row"> <span class="info-label">Blood Type:</span> <span class="info-value">{{ $licenseData->bloodType ?? 'N/A' }}</span> </div>
                            </div>
                        </div>
                    </div>

                    <div class="section-card">
                        <div class="section-card-inner">
                            <div class="section-card-header"> <h2 class="section-card-title">License Details</h2></div>
                            <div class="section-card-body">
                                <div class="info-row"> <span class="info-label">License Number:</span> {{ $licenseData->licenseNumber ?? 'N/A' }} </div>
                                <div class="info-row"> <span class="info-label">License Type:</span> {{ $licenseData->licenseType ?? 'N/A' }} </div>
                                <div class="info-row"> <span class="info-label">Issue Date:</span> {{ $licenseData->issueDate ?? 'N/A' }} </div>
                                <div class="info-row"> <span class="info-label">Expiry Date:</span> {{ $licenseData->expiryDate ?? 'N/A' }} </div>
                                <div class="info-row"> <span class="info-label">Restrictions:</span> {{ $licenseData->dlCodes ?? 'N/A' }} </div>
                            </div>
                        </div>
                    </div>
                
                 @elseif ($section === 'vehicle')
                    <h1 class="dash-title">Vehicle Search</h1>

                    @if ($selectedVehicle)
                        <div class="section-card">
                            <div class="section-card-inner">
                                <div class="section-card-header">
                                    <h2 class="section-card-title">Vehicle Information</h2>
                                </div>
                                <div class="section-card-body">
                                    <div class="info-row"><span class="info-label">Status:</span> <span class="status-green">{{ $selectedVehicle->status ?? 'N/A' }}</span></div>
                                    <div class="info-row"><span class="info-label">MV File Number:</span> {{ $selectedVehicle->mvFileNumber ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">Model:</span> {{ $selectedVehicle->model ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">Color:</span> {{ $selectedVehicle->color ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">Registration Expiry:</span> {{ $selectedVehicle->expiryDate ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">Plate Number:</span> {{ $selectedVehicle->plateNumber ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">Make:</span> {{ $selectedVehicle->make ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">Year:</span> {{ $selectedVehicle->year ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">VIN:</span> {{ $selectedVehicle->vin ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="section-card">
                            <div class="section-card-inner">
                                <div class="section-card-header">
                                    <h2 class="section-card-title">Registered Owner</h2>
                                </div>
                                <div class="section-card-body">
                                    <div class="info-row"><span class="info-label">Name:</span> {{ $selectedVehicle->ownerName ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">Address:</span> {{ $selectedVehicle->ownerAddress ?? 'N/A' }}</div>
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
                            @forelse($vehicles as $vehicle)
                                <div class="vehicle-card">
                                    <div class="vehicle-card-top">
                                        <div class="vehicle-card-icon">
                                            <i class="bi bi-car-front"></i>
                                        </div>
                                        <div class="vehicle-card-info">
                                            <div class="vehicle-info-text">{{ $vehicle->make }} {{ $vehicle->model }}</div>
                                            <div class="vehicle-info-text">{{ $vehicle->plateNumber }}</div>
                                            <div class="vehicle-info-text status-green">{{ $vehicle->status }}</div>
                                        </div>
                                    </div>
                                    <a href="{{ route('civilian-vehicle') }}?plate={{ $vehicle->plateNumber }}" class="btn-card">View Full Details</a>
                                </div>
                            @empty
                                <p class="text-center">No vehicles found</p>
                            @endforelse
                        </div>
                    @endif

                @elseif ($section === 'violations')
                    <h1 class="dash-title">Ticket Violations</h1>
                    <p class="dash-sub">View All of the Violations</p>

                    <div class="violations-grid">
                        @forelse($violations as $violation)
                            <div class="violation-card">
                                <div class="info-row violation-offence"> {{ $violation->offence }} </div>
                                <div class="info-row"> <span class="info-label">Date:</span> {{ $violation->date }} </div>
                                <div class="info-row"> <span class="info-label">Place:</span> {{ $violation->place }} </div>
                                <div class="info-row"> <span class="info-label">Note:</span> {{ $violation->notes }} </div>
                                <div class="info-label"> <span class="info-label">Fine:</span> {{ $violation->fine }} </div>
                            </div>
                        @empty
                            <p class="text-center">No violations found</p>
                        @endforelse
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

                        <form action="{{ route('civilian-support-submit') }}" method="POST">
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