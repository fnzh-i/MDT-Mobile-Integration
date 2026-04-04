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
                        <div class="section-card">
                            <div class="section-card-accent"></div>
                            <div class="section-card-inner">
                                <h2 class="section-card-title mb-3">Vehicle Information</h2>
                                <div class="info-row">
                                    <span class="info-label">Status:</span>
                                    <span class="status-green">Sample Status</span>
                                </div>
                                <div class="info-row"><span class="info-label">MV File Number:</span> Sample MV File Number</div>
                                <div class="info-row"><span class="info-label">Model:</span> Sample Model</div>
                                <div class="info-row"><span class="info-label">Color:</span> Sample Color</div>
                                <div class="info-row"><span class="info-label">Registration Expiry:</span> Sample Registration Expiry</div>
                                <div class="info-row"><span class="info-label">Plate Number:</span> Sample Plate Number</div>
                                <div class="info-row"><span class="info-label">Make:</span> Sample Make</div>
                                <div class="info-row"><span class="info-label">Year:</span> Sample Year</div>
                                <div class="info-row"><span class="info-label">VIN:</span> Sample VIN</div>
                            </div>
                        </div>

                        <div class="section-card">
                            <div class="section-card-accent"></div>
                            <div class="section-card-inner">
                                <h2 class="section-card-title mb-3">Registered Owner</h2>
                                <div class="info-row"><span class="info-label">Name:</span> Sample Name</div>
                                <div class="info-row"><span class="info-label">License Number:</span> Sample License Number</div>
                                <div class="info-row"><span class="info-label">Address:</span> Sample Address</div>
                            </div>
                        </div>
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
                        <div class="section-card">
                            <div class="section-card-accent"></div>
                            <div class="section-card-inner">
                                <div class="section-card-header">
                                    <h2 class="section-card-title">License Status</h2>
                                    <span class="status-dot dot-green"></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Status:</span>
                                    <span class="status-green">Sample Status</span>
                                </div>
                            </div>
                        </div>

                        <div class="section-card">
                            <div class="section-card-accent"></div>
                            <div class="section-card-inner">
                                <h2 class="section-card-title mb-3">Personal Information</h2>
                                <div class="info-row"><span class="info-label">Last Name:</span> Sample Last Name</div>
                                <div class="info-row"><span class="info-label">First Name:</span> Sample First Name</div>
                                <div class="info-row"><span class="info-label">Middle Name:</span> Sample Middle Name</div>
                                <div class="info-row"><span class="info-label">Date of Birth:</span> Sample Date of Birth</div>
                                <div class="info-row"><span class="info-label">Sex:</span> Sample Sex</div>
                                <div class="info-row"><span class="info-label">Address:</span> Sample Address</div>
                                <div class="info-row"><span class="info-label">Nationality:</span> Sample Nationality</div>
                                <div class="info-row"><span class="info-label">Height:</span> Sample Height</div>
                                <div class="info-row"><span class="info-label">Weight:</span> Sample Weight</div>
                                <div class="info-row"><span class="info-label">Eye Color:</span> Sample Eye Color</div>
                                <div class="info-row"><span class="info-label">Blood Type:</span> Sample Blood Type</div>
                                <div class="info-row"><span class="info-label">Conditions:</span> Sample Conditions</div>
                            </div>
                        </div>

                        <div class="section-card">
                            <div class="section-card-accent"></div>
                            <div class="section-card-inner">
                                <h2 class="section-card-title mb-3">License Details</h2>
                                <div class="info-row"><span class="info-label">License Number:</span> Sample License Number</div>
                                <div class="info-row"><span class="info-label">License Type:</span> Sample License Type</div>
                                <div class="info-row"><span class="info-label">Issue Date:</span> Sample Issue Date</div>
                                <div class="info-row"><span class="info-label">Expiry Date:</span> Sample Expiry Date</div>
                                <div class="info-row"><span class="info-label">Restrictions:</span> Sample Restrictions</div>
                            </div>
                        </div>

                        <div class="section-card">
                            <div class="section-card-accent"></div>
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
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Sample Date</td>
                                                <td>Sample Offense</td>
                                                <td>Sample Place</td>
                                                <td>Sample Notes</td>
                                                <td><span class="status-red">Sample Unsettled</span></td>
                                                <td class="violation-actions">
                                                    <a href="#" class="btn-vio btn-vio-view">View</a>
                                                    <button type="button" class="btn-vio btn-vio-edit" onclick="openEditModal(1, 'Unsettled')">Edit</button>
                                                    <button type="button" class="btn-vio btn-vio-archive" onclick="if(confirm('Archive this ticket?')){}">Archive</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Sample Date</td>
                                                <td>Sample Offense</td>
                                                <td>Sample Place</td>
                                                <td>Sample Notes</td>
                                                <td><span class="status-green">Sample Settled</span></td>
                                                <td class="violation-actions">
                                                    <a href="#" class="btn-vio btn-vio-view">View</a>
                                                    <button type="button" class="btn-vio btn-vio-edit" onclick="openEditModal(2, 'Settled')">Edit</button>
                                                    <button type="button" class="btn-vio btn-vio-archive" onclick="if(confirm('Archive this ticket?')){}">Archive</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div id="editModal" class="modal-overlay" style="display:none">
                            <div class="modal-box">
                                <h3 class="modal-title">Edit Violation Status</h3>
                                <form id="editStatusForm" method="POST">
                                    @csrf @method('PATCH')
                                    <div class="modal-field">
                                        <label class="modal-label">Status</label>
                                        <div class="radio-row">
                                            <label class="radio-option"><input type="radio" name="status" value="Settled"> Settled</label>
                                            <label class="radio-option"><input type="radio" name="status" value="Unsettled"> Unsettled</label>
                                            <label class="radio-option"><input type="radio" name="status" value="Dismissed"> Dismissed</label>
                                        </div>
                                    </div>
                                    <div class="modal-actions">
                                        <button type="submit" class="btn-modal-save">Save</button>
                                        <button type="button" class="btn-modal-cancel" onclick="closeEditModal()">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        @if (request()->has('query'))
                            <p class="no-data text-center mt-3">No license found for "{{ request('query') }}".</p>
                        @endif
                    @endif

                @elseif ($section === 'settings')
                    <h1 class="dash-title">Settings</h1>
                    <p class="dash-sub">Manage your account preferences</p>

                    <div class="settings-card">
                        <h2 class="settings-form-title">Account Settings</h2>
                        <form action="{{ route('supervisor-update-settings') }}" method="POST">
                            @csrf @method('PUT')
                            <div class="settings-field">
                                <label class="settings-label">Username</label>
                                <input type="text" name="username" class="lookup-input" style="width:100%" value="{{ auth()->user()->username ?? 'supervisor_user' }}" required>
                            </div>
                            <div class="settings-field">
                                <label class="settings-label">Email</label>
                                <input type="email" name="email" class="lookup-input" style="width:100%" value="{{ auth()->user()->email ?? 'supervisor@mdt.gov.ph' }}" required>
                            </div>
                            <div style="border-top:1.5px solid #d0daea;margin:20px 0 16px"></div>
                            <div class="settings-field">
                                <label class="settings-label">Current Password</label>
                                <input type="password" name="current_password" class="lookup-input" style="width:100%" placeholder="Enter current password">
                            </div>
                            <div class="settings-field">
                                <label class="settings-label">New Password</label>
                                <input type="password" name="new_password" class="lookup-input" style="width:100%" placeholder="New password">
                            </div>
                            <div class="settings-field">
                                <label class="settings-label">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" class="lookup-input" style="width:100%" placeholder="Confirm new password">
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