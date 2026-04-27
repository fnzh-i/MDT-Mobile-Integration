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
    <link rel="stylesheet" href="{{ asset('css/supervisor-dashboard.css') }}">
</head>
<body>
    <div class="shell">
        <div class="topbar">
            <div class="topbar-left">
                <button class="hamburger" id="hamburgerBtn" aria-label="Toggle menu"> <span></span> <span></span> <span></span> </button>
                <span class="topbar-title">Mobile Data Terminal</span>
            </div>
            <div class="topbar-right">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout"> <i class="bi bi-box-arrow-right me-1"></i> Logout</button>
                </form>
            </div>
        </div>

        <div class="body-area">
            <div class="overlay" id="overlay"></div>
            <aside class="sidebar" id="sidebar">
                <div class="user-block">
                    <div class="user-avatar"> <i class="bi bi-person"></i> </div>
                    <div class="user-role">Supervisor</div>
                    <div class="user-name"> {{ auth()->user()->username ?? 'Sample Supervisor' }} </div>
                </div>
                <nav>
                    <a href="{{ route('supervisor-dashboard') }}" class="nav-link {{ $section === 'dashboard' ? 'active' : '' }}"> <i class="bi bi-speedometer2 me-2"></i> Dashboard </a>
                    <a href="{{ route('supervisor-vehicle-lookup') }}" class="nav-link {{ $section === 'vehicle-lookup' ? 'active' : '' }}"> <i class="bi bi-car-front me-2"></i> Vehicle Lookup </a>
                    <a href="{{ route('supervisor-license-lookup') }}" class="nav-link {{ $section === 'license-lookup' ? 'active' : '' }}"> <i class="bi bi-card-heading me-2"></i> License Lookup </a>
                    <a href="{{ route('supervisor-settings') }}" class="nav-link {{ $section === 'settings' ? 'active' : '' }}"> <i class="bi bi-gear me-2"></i> Settings </a>
                </nav>
            </aside>

            <main class="main" id="main">

                @if ($section === 'dashboard')
                    <h1 class="dash-title">Supervisor Dashboard</h1>
                    <p class="dash-sub">Select a search type to retrieve civilian data</p>

                    <div class="sup-cards-grid">
                        <div class="sup-card">
                            <div class="sup-card-body">
                                <i class="bi bi-car-front sup-card-icon"></i>
                            </div>
                            <div class="card-footer-custom">
                                <a href="{{ route('supervisor-vehicle-lookup') }}" class="btn-card">Check Vehicle</a>
                            </div>
                        </div>
                        <div class="sup-card">
                            <div class="sup-card-body">
                                <i class="bi bi-card-heading sup-card-icon"></i>
                            </div>
                            <div class="card-footer-custom">
                                <a href="{{ route('supervisor-license-lookup') }}" class="btn-card">Check License</a>
                            </div>
                        </div>
                    </div>

                @elseif ($section === 'vehicle-lookup')
                    <h1 class="dash-title">Vehicle Lookup</h1>

                    <div class="lookup-search-row">
                        <form method="GET" action="{{ route('supervisor-vehicle-lookup') }}" class="lookup-form">
                            <input type="text" name="query" class="lookup-input" placeholder="Enter plate or MV file number..." value="{{ request('query') }}">
                            <button type="submit" class="btn-lookup-search">Search</button>
                        </form>
                    </div>

                    @if (request('query'))
                        @if (!empty($searchedVehicle))
                            @php
                                $vehicle = $searchedVehicle['vehicle'] ?? [];
                                $owner = $searchedVehicle['owner'] ?? [];
                                $vehicleStatus = (string) ($vehicle['regStatus'] ?? 'Unknown');
                                $vehicleStatusClass = in_array(strtolower($vehicleStatus), ['active', 'registered', 'valid'], true) ? 'status-green' : 'status-red';
                                $middleInitial = !empty($owner['middleName']) ? strtoupper(substr((string) $owner['middleName'], 0, 1)) . '. ' : '';
                                $suffix = !empty($owner['suffix']) ? ' ' . $owner['suffix'] : '';
                                $ownerName = trim((string) ($owner['firstName'] ?? '') . ' ' . $middleInitial . (string) ($owner['lastName'] ?? '') . $suffix);
                            @endphp

                            <div class="section-card">
                                <div class="section-card-inner">
                                    <h2 class="section-card-title mb-3">Vehicle Information</h2>
                                    <div class="info-row">
                                        <span class="info-label">Status:</span>
                                        <span class="{{ $vehicleStatusClass }}">{{ $vehicleStatus }}</span>
                                    </div>
                                    <div class="info-row"><span class="info-label">MV File Number:</span> {{ $vehicle['mvFileNumber'] ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">Model:</span> {{ $vehicle['model'] ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">Color:</span> {{ $vehicle['color'] ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">Registration Expiry:</span> {{ $vehicle['expiryDate'] ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">Plate Number:</span> {{ $vehicle['plateNumber'] ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">Make:</span> {{ $vehicle['make'] ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">Year:</span> {{ $vehicle['year'] ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">VIN:</span> {{ $vehicle['vin'] ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="section-card">
                                <div class="section-card-inner">
                                    <h2 class="section-card-title mb-3">Registered Owner</h2>
                                    <div class="info-row"><span class="info-label">Name:</span> {{ $ownerName !== '' ? $ownerName : 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">License Number:</span> {{ $owner['licenseNumber'] ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">Address:</span> {{ $owner['address'] ?? 'N/A' }}</div>
                                </div>
                            </div>
                        @else
                            <p class="no-data text-center mt-3">{{ $error ?? ('No vehicle found for "' . request('query') . '".') }}</p>
                        @endif
                    @else
                        @if (request()->has('query'))
                            <p class="no-data text-center mt-3">No vehicle found for "{{ request('query') }}".</p>
                        @endif
                    @endif

                @elseif ($section === 'license-lookup')
                    <h1 class="dash-title">Driver's License Lookup</h1>

                    <div class="lookup-search-row">
                        <form method="GET" action="{{ route('supervisor-license-lookup') }}" class="lookup-form">
                            <input type="text" name="query" class="lookup-input" placeholder="Enter license number..." value="{{ request('query') }}">
                            <button type="submit" class="btn-lookup-search">Search</button>
                        </form>
                    </div>

                    @if (request('query'))
                        @if (!empty($searchedLicense))
                            @php
                                $license = $searchedLicense['license'] ?? [];
                                $person = $searchedLicense['person'] ?? [];
                                $tickets = $searchedLicense['tickets'] ?? [];
                                $licenseStatus = (string) ($license['status'] ?? 'Unknown');
                                $isPositiveStatus = in_array(strtolower($licenseStatus), ['active', 'valid', 'settled'], true);
                            @endphp

                            <div class="section-card">
                                <div class="section-card-inner">
                                    <div class="section-card-header">
                                        <h2 class="section-card-title">License Status</h2>
                                        <span class="status-dot {{ $isPositiveStatus ? 'dot-green' : 'dot-red' }}"></span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Status:</span>
                                        <span class="{{ $isPositiveStatus ? 'status-green' : 'status-red' }}">{{ $licenseStatus }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="section-card">
                                <div class="section-card-inner">
                                    <h2 class="section-card-title mb-3">Personal Information</h2>
                                    <div class="info-row"><span class="info-label">Last Name:</span> {{ $person['lastName'] ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">First Name:</span> {{ $person['firstName'] ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">Middle Name:</span> {{ $person['middleName'] ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">Date of Birth:</span> {{ $person['dateOfBirth'] ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">Sex:</span> {{ $person['gender'] ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">Address:</span> {{ $person['address'] ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">Nationality:</span> {{ $person['nationality'] ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">Height:</span> {{ $person['height'] ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">Weight:</span> {{ $person['weight'] ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">Eye Color:</span> {{ $person['eyeColor'] ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">Blood Type:</span> {{ $person['bloodType'] ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">Conditions:</span> {{ $license['dlCodes'] ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="section-card">
                                <div class="section-card-inner">
                                    <h2 class="section-card-title mb-3">License Details</h2>
                                    <div class="info-row"><span class="info-label">License Number:</span> {{ $license['licenseNumber'] ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">License Type:</span> {{ $license['type'] ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">Issue Date:</span> {{ $license['issueDate'] ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">Expiry Date:</span> {{ $license['expiryDate'] ?? 'N/A' }}</div>
                                    <div class="info-row"><span class="info-label">Restrictions:</span> {{ $license['dlCodes'] ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="section-card">
                                <div class="section-card-inner">
                                    <h2 class="section-card-title mb-3">Violation History</h2>
                                    <div class="violation-scroll-wrapper">
                                        <table class="violation-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Date</th>
                                                    <th>Offense</th>
                                                    <th>Place</th>
                                                    <th>Notes</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($tickets as $index => $ticket)
                                                    @php
                                                        $ticketStatus = (string) ($ticket['status'] ?? 'Unknown');
                                                        $ticketClass = in_array(strtolower($ticketStatus), ['settled'], true) ? 'status-green' : 'status-red';
                                                        $offenses = collect($ticket['violations'] ?? [])->pluck('name')->filter()->implode(', ');
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $ticket['dateOfIncident'] ?? 'N/A' }}</td>
                                                        <td>{{ $offenses !== '' ? $offenses : 'N/A' }}</td>
                                                        <td>{{ $ticket['placeOfIncident'] ?? 'N/A' }}</td>
                                                        <td>{{ $ticket['notes'] ?? 'N/A' }}</td>
                                                        <td><span class="{{ $ticketClass }}">{{ $ticketStatus }}</span></td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6">No violations found.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="no-data text-center mt-3">{{ $error ?? ('No license found for "' . request('query') . '".') }}</p>
                        @endif
                    @else
                        @if (request()->has('query'))
                            <p class="no-data text-center mt-3">No license found for "{{ request('query') }}".</p>
                        @endif
                    @endif

                @elseif ($section === 'settings')
                    <h1 class="dash-title">Settings</h1>
                    <p class="dash-sub">Manage your account preferences</p>

                    @if (session('success'))
                        <p class="text-center" style="color:#198754;font-weight:600;margin-bottom:14px;">{{ session('success') }}</p>
                    @endif
                    @if (session('error'))
                        <p class="text-center" style="color:#dc3545;font-weight:600;margin-bottom:14px;">{{ session('error') }}</p>
                    @endif
                    @if ($errors->any())
                        <div class="text-center" style="color:#dc3545;font-weight:600;margin-bottom:14px;">
                            @foreach ($errors->all() as $validationError)
                                <div>{{ $validationError }}</div>
                            @endforeach
                        </div>
                    @endif

                    <div class="settings-card">
                        <h2 class="settings-form-title">Account Settings</h2>
                        <form action="{{ route('supervisor-update-settings') }}" method="POST">
                            @csrf @method('PUT')
                            <div class="settings-field">
                                <label class="settings-label">Username</label>
                                <input type="text" name="username" class="lookup-input input-readonly-gray" style="width:100%" value="{{ auth()->user()->username ?? 'supervisor_user' }}" readonly>
                            </div>
                            <div class="settings-field">
                                <label class="settings-label">Email</label>
                                <input type="email" name="email" class="lookup-input input-readonly-gray" style="width:100%" value="{{ auth()->user()->email ?? 'supervisor@mdt.gov.ph' }}" readonly>
                            </div>
                            <div style="border-top:1.5px solid #d0daea;margin:20px 0 16px"></div>
                            <div class="settings-field">
                                <label class="settings-label">Current Password</label>
                                <input type="password" name="current_password" class="lookup-input" style="width:100%" placeholder="Enter current password" required>
                            </div>
                            <div class="settings-field">
                                <label class="settings-label">New Password</label>
                                <input type="password" name="new_password" class="lookup-input" style="width:100%" placeholder="New password" required>
                            </div>
                            <div class="settings-field">
                                <label class="settings-label">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" class="lookup-input" style="width:100%" placeholder="Confirm new password" required>
                            </div>
                            <button type="submit" class="btn-submit-ticket">Save Changes</button>
                        </form>
                    </div>

                @endif
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/supervisor-dashboard.js') }}"></script>
</body>
</html>