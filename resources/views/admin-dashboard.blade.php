@extends('layouts.app')

@section('content')

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
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
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
                    <div class="user-role"> Admin </div>
                    <div class="user-name"> Sample User </div>
                </div>
                <nav>
                    <a href="{{ route('admin-dashboard') }}" class="nav-link {{ $section === 'dashboard' ? 'active' : '' }}"> <i class="bi bi-speedometer2 me-2"></i> Dashboard </a>
                    <a href="{{ route('admin-create-users') }}" class="nav-link {{ $section === 'create-users' ? 'active' : '' }}"> <i class="bi bi-person-plus me-2"></i> Create Users </a>
                    <a href="{{ route('admin-create-license') }}" class="nav-link {{ $section === 'create-license' ? 'active' : '' }}"> <i class="bi bi-card-heading me-2"></i> Create License </a>
                    <a href="{{ route('admin-create-vehicle') }}" class="nav-link {{ $section === 'create-vehicle' ? 'active' : '' }}"> <i class="bi bi-car-front me-2"></i> Create Vehicle </a>
                    <a href="{{ route('admin-search-users') }}" class="nav-link {{ $section === 'search-users' ? 'active' : '' }}"> <i class="bi bi-person-lines-fill me-2"></i> Search & Edit Users </a>
                    <a href="{{ route('admin-search-license') }}" class="nav-link {{ $section === 'search-license' ? 'active' : '' }}"> <i class="bi bi-search me-2"></i> Search & Edit License </a>
                    <a href="{{ route('admin-search-vehicle') }}" class="nav-link {{ $section === 'search-vehicle' ? 'active' : '' }}"> <i class="bi bi-truck me-2"></i> Search & Edit Vehicle </a>
                    <a href="{{ route('admin-settings') }}" class="nav-link {{ $section === 'settings' ? 'active' : '' }}"> <i class="bi bi-gear me-2"></i> Settings </a>

            </aside>

            <main class="main" id="main">
                @if($errors->has('error'))
                    <div class="max-w-4xl mx-auto mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <strong class="font-bold">Database Error:</strong>
                        <span class="block sm:inline">{{ $errors->first('error') }}</span>
                    </div>
                @elseif ($section === 'dashboard')
                    <h1 class="dash-title">Admin Dashboard</h1>
                    <p class="dash-sub">Administrative tools and Management actions</p>
                    
                    <div class="stat-cards-row">
                        <div class="stat-card">
                            <i class="bi bi-people stat-icon"></i>
                            <div class="stat-number">{{ $totalUsers ?? 0 }}</div>
                            <div class="stat-label">Total Registered Users</div>
                        </div>
                        <div class="stat-card">
                            <i class="bi bi-card-heading stat-icon"></i>
                            <div class="stat-number">{{ $totalLicenses ?? 0 }}</div>
                            <div class="stat-label">Total Registered Licenses</div>
                        </div>
                        <div class="stat-card">
                            <i class="bi bi-car-front stat-icon"></i>
                            <div class="stat-number">{{ $totalVehicles ?? 0 }}</div>
                            <div class="stat-label">Total Registered Vehicles</div>
                        </div>
                        <div class="stat-card">
                            <i class="bi bi-file-earmark stat-icon"></i>
                            <div class="stat-number">{{ $totalTickets ?? 0 }}</div>
                            <div class="stat-label">Total Issued Tickets</div>
                        </div>
                    </div>

                    <div class="charts-scroll-wrapper">
                        <div class="charts-row">
                            <div class="chart-card">
                                <span class="chart-title">Registered Users</span>
                                <div class="chart-placeholder"> <canvas id="usersChart"></canvas> </div>
                            </div>
                            <div class="chart-card">
                                <span class="chart-title">Registered Licenses</span>
                                <div class="chart-placeholder"> <canvas id="licenseChart"></canvas> </div>
                            </div>
                            <div class="chart-card">
                                <span class="chart-title">Registered Vehicles</span>
                                <div class="chart-placeholder"> <canvas id="vehicleChart"></canvas> </div>
                            </div>
                            <div class="chart-card">
                                <span class="chart-title">Issued Tickets</span>
                                <div class="chart-placeholder"> <canvas id="ticketChart"></canvas> </div>
                            </div>
                        </div>
                    </div>

                    <div class="admin-cards-grid">
                        <div class="dash-card">
                            <div class="admin-card-body"> <i class="bi bi-card-heading admin-card-icon"></i> </div>
                            <div class="card-footer-custom"> 
                                <a href="{{ route('admin-create-license') }}" class="btn-card">Create License</a> 
                            </div>
                        </div>
                        <div class="dash-card">
                            <div class="admin-card-body"> <i class="bi bi-search admin-card-icon"></i> </div>
                            <div class="card-footer-custom"> 
                                <a href="{{ route('admin-search-license') }}" class="btn-card">Search & Edit License</a> 
                            </div>
                        </div>
                        <div class="dash-card">
                            <div class="admin-card-body"> <i class="bi bi-car-front admin-card-icon"></i> </div>
                            <div class="card-footer-custom"> 
                                <a href="{{ route('admin-create-vehicle') }}" class="btn-card">Create Vehicles</a> 
                            </div>
                        </div>
                        <div class="dash-card">
                            <div class="admin-card-body"> <i class="bi bi-truck admin-card-icon"></i> </div>
                            <div class="card-footer-custom"> 
                                <a href="{{ route('admin-search-vehicle') }}" class="btn-card">Search & Edit Vehicles</a>
                            </div>
                        </div>
                        <div class="dash-card">
                            <div class="admin-card-body"> <i class="bi bi-person-plus admin-card-icon"></i> </div>
                            <div class="card-footer-custom"> 
                                <a href="{{ route('admin-create-users') }}" class="btn-card">Create Users</a>
                            </div>
                        </div>
                        <div class="dash-card">
                            <div class="admin-card-body"> <i class="bi bi-person-lines-fill admin-card-icon"></i> </div>
                            <div class="card-footer-custom"> 
                                <a href="{{ route('admin-search-users') }}" class="btn-card">Search & Edit Users</a>
                            </div>
                        </div>
                    </div>

                @elseif ($section === 'create-user')
                    <div class="form-card">
                        <h2 class="form-card-title">Create New User</h2>
                        <form action="{{ route('user.store') }}" method="POST">
                            @csrf
                            <div class="form-section">
                                <div class="form-field-label">Role</div>
                                <div class="radio-row">
                                    <label for="" class="radio-option"> <input type="radio" name="role" value="CIVILIAN" checked> Civilian </label>
                                    <label for="" class="radio-option"> <input type="radio" name="role" value="ENFORCER"> Enforcer </label>
                                    <label for="" class="radio-option"> <input type="radio" name="role" value="TEAMLEADER"> Team Leader </label>
                                    <label for="" class="radio-option"> <input type="radio" name="role" value="SUPERVISOR"> Supervisor </label>
                                    <label for="" class="radio-option"> <input type="radio" name="role" value="ADMIN"> Admin </label>
                                </div>
                            </div>
                            <div class="form-section">
                                <div class="form-field-label">Client number</div>
                            </div>
                            <div class="form-row-3">
                                <input type="text" name="clientNumber" placeholder="001-XX-XXXXXX" class="form-input" id="clientNumber" required readonly>
                                <button type="button" id="generate-client-num-btn" class="btn-form-submit">
                                    Generate
                                </button>
                            </div>
                            <div class="form-section">
                                <div class="form-field-label">Full Name</div>
                            </div>
                            <div class="form-row-4">
                                <input type="text" name="first_name" class="form-input" placeholder="First Name" required>
                                <input type="text" name="middle_name" class="form-input" placeholder="Middle Name">
                                <input type="text" name="last_name" class="form-input" placeholder="Last Name" required>
                                <input type="text" name="suffix" class="form-input" placeholder="Suffix">
                            </div>
                            <div class="form-section">
                                <div class="form-field-label">Username</div>
                                <input type="text" name="username" class="form-input" required>
                            </div>
                            <div class="form-section">
                                <div class="form-field-label">Email</div>
                                <input type="email" name="email" class="form-input" required>
                            </div>
                            <div class="form-section">
                                <div class="form-field-label">Password</div>
                                <input type="password" name="password" class="form-input" required>
                            </div>
                            <button type="submit" class="btn-form-submit">Create User</button>
                        </form>
                    </div>
                
                @elseif ($section === 'create-license')
                    <div class="form-card">
                        <h2 class="form-card-title">Create New License</h2>
                        <form action="{{ route('license.store') }}" method="POST">
                            @csrf
                            <div class="form-section">
                                <div class="form-field-label">License Number</div>
                                <input type="text" id="licenseNumber" name="license_number" placeholder="D01-XX-XXXXXX" class="form-input" required readonly>
                                <div class="input-group-append">

                                    <!-- <input type="text" name="license_number" placeholder="D01-XX-XXXXXX" class="text-center w-full font-mono text-lg border-b-2 border-blue-800 focus:outline-none" id="license_number" required> -->
                                    <button type="button" id="generate-ln-btn" class="btn-form-submit">
                                        Generate
                                    </button>
                                </div>
                            </div>
                            <div class="form-section">
                                <label class="form-field-label">License Type: </label>
                                <select name="license_type" class="w-full p-2 rounded border">
                                    <option value="Professional">Professional</option>
                                    <option value="Non-Professional">Non-Professional</option>
                                </select>
                            </div>
                            <div class="form-section">
                                <div class="form-field-label">Role</div>
                                <div class="radio-row">
                                    <label for="" class="radio-option"> <input type="Radio" name="license_status" value="Active" checked> Registered </label>
                                    <label for="" class="radio-option"> <input type="Radio" name="license_status" value="Suspended"> Suspended </label>
                                    <label for="" class="radio-option"> <input type="Radio" name="license_status" value="Revoked"> Revoked </label>
                                    <label for="" class="radio-option"> <input type="Radio" name="license_status" value="Expired"> Expired </label>
                                </div>
                            </div>
                            <div class="form-section">
                                <div class="form-field-label">Issue Date</div>
                                <input type="date" name="issue_date" class="form-input" required>
                            </div>
                            <div class="form-section">
                                <div class="form-field-label">Expiry Option</div>
                                <div class="radio-row">
                                    <label for="" class="radio-option"> <input type="radio" name="expiry_option" value="5" checked> 5 Years </label>
                                    <label for="" class="radio-option"> <input type="radio" name="expiry_option" value="10"> On 10 Years </label>
                                </div>
                            </div>
                            <div class="form-section">
                                <div class="form-field-label">Driver License Code</div>
                                <div class="radio-row">
                                    <label for="" class="radio-option"> <input type="checkbox" name="dl_codes[]" value="A"> A </label>
                                    <label for="" class="radio-option"> <input type="checkbox" name="dl_codes[]" value="A1"> A1 </label> 
                                    <label for="" class="radio-option"> <input type="checkbox" name="dl_codes[]" value="B"> B </label>
                                    <label for="" class="radio-option"> <input type="checkbox" name="dl_codes[]" value="B1"> B1 </label>
                                    <label for="" class="radio-option"> <input type="checkbox" name="dl_codes[]" value="C"> C </label>
                                    <label for="" class="radio-option"> <input type="checkbox" name="dl_codes[]" value="D"> D </label>
                                </div>
                            </div>
                            <div class="form-divider"></div>
                            <div class="form-field-label mb-2">Personal Information</div>
                            <div class="form-row-4">
                                <input type="text" name="first_name" class="form-input" placeholder="First Name" required>
                                <input type="text" name="middle_name" class="form-input" placeholder="Middle Name">
                                <input type="text" name="last_name" class="form-input" placeholder="Last Name" required>
                                <input type="text" name="suffix" class="form-input" placeholder="Suffix">
                            </div>
                            <div class="form-row-2">
                                <input type="date" name="date_of_birth" class="form-input" required>
                                <div class="radio-row">
                                    <label for="" class="radio-option"> <input type="radio" name="gender" value="male"> Male </label>
                                    <label for="" class="radio-option"> <input type="radio" name="gender" value="female"> Female </label>
                                </div>
                            </div>
                            <div class="form-section">
                                <input type="text" name="address" class="form-input" placeholder="Address" required>
                            </div>
                            <div class="form-row-2">
                                <input type="text" name="nationality" class="form-input" placeholder="Nationality" required>
                                <input type="text" name="blood_type" class="form-input" placeholder="Blood Type">
                            </div>
                            <div class="form-row-3">
                                <input type="number" name="height" class="form-input" placeholder="Height (cm)" step="0.01" required>
                                <input type="number" name="weight" class="form-input" placeholder="Weight (kg)" step="0.01" required>
                                <input type="text" name="eye_color" class="form-input" placeholder="Eye Color" required>
                            </div>
                            <button type="submit" class="btn-form-submit">Create License</button>
                        </form>
                    </div>
                @elseif ($section === 'create-vehicle')
                    <div class="form-card">
                        <h2 class="form-card-title">Create New Vehicle</h2>
                        <form action="{{ route('vehicle.store') }}" method="POST">
                            @csrf
                            <div class="form-section">
                                <div class="form-field-label">License Number</div>
                                <input type="text" name="license_number" class="form-input" placeholder="License Number" required>
                            </div>
                            <div class="form-section">
                                <div class="form-field-label">Plate Number</div>
                                <input type="text" name="plate_number" class="form-input" placeholder="Plate Number" required>
                            </div>
                            <div class="form-row-2">
                                <div class="">
                                    <div class="form-field-label">MV File Number</div>
                                    <input type="text" id="mvFileNumber" name="mv_file_number" class="form-input" placeholder="MV File Number" required readonly>
                                </div>
                                <button type="button" id="generate-mvfile-btn" class="btn-form-submit">
                                        Generate
                                </button>
                            </div>
                            <div class="">
                                <div class="form-field-label">VIN</div>
                                <input type="text" name="vin" class="form-input" placeholder="VIN" required>
                            </div>
                            <div class="form-section">
                                <div class="form-field-label">Issue Date</div>
                                <input type="date" name="issue_date" class="form-input" required>
                            </div>
                            <div class="form-section">
                                <div class="form-field-label">Registration Status</div>
                                <div class="radio-row">
                                    <label for="" class="radio-option"> <input type="radio" name="registration_status" value="Registered" checked> Registered </label>
                                    <label for="" class="radio-option"> <input type="radio" name="registration_status" value="Unregistered"> Unregistered </label>
                                    <label for="" class="radio-option"> <input type="radio" name="registration_status" value="Suspended"> Suspended </label>
                                    <label for="" class="radio-option"> <input type="radio" name="registration_status" value="Revoked"> Revoked </label>
                                </div> 
                            </div>
                            <div class="form-divider"></div>
                            <div class="form-field-label mb-2">Vehicle Details</div>
                            <div class="form-row-2">
                                <input type="text" name="make" class="form-input" placeholder="Brand" required>
                                <input type="text" name="model" class="form-input" placeholder="Model" required>
                            </div>
                            <div class="form-row-2">
                                <input type="number" name="year" class="form-input" placeholder="Model Year" required>
                                <input type="text" name="color" class="form-input" placeholder="Color" required>
                            </div>
                            <button type="submit" class="btn-form-submit">Create Vehicle</button>
                        </form>
                    </div>
                
                @elseif ($section === 'search-users')
                    @if ($searchedUser)
                        <div class="form-card">
                            <h2 class="form-card-title">User Management</h2>
                            <div class="mgmt-table-wrapper">
                                <table class="mgmt-table">
                                    <thead>
                                        <tr>
                                            <th>User ID</th>
                                            <th>Role</th>
                                            <th>First Name</th>
                                            <th>Middle Name</th>
                                            <th>Last Name</th>
                                            <th>Suffix</th>
                                            <th>Email</th>
                                            <th>Password</th>
                                            <th>Action</th>
                                        </tr>  
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td> <input type="text" class="tbl-input" value="Sample User"> </td> {{--value="{{ $searchedUser->id }}"--}}
                                            <td> <input type="text" class="tbl-input" value="Sample Role" id="edit_role"> </td> {{--value="{{ $searchedUser->role }}" --}}
                                            <td> <input type="text" class="tbl-input" value="Sample First Name" id="edit_first"> </td> {{--value="{{ $searchedUser->first_name }}" --}}
                                            <td> <input type="text" class="tbl-input" value="Sample Middle Name" id="edit_middle"> </td> {{--value="{{ $searchedUser->middle_name }}"--}}
                                            <td> <input type="text" class="tbl-input" value="Sample Last Name" id="edit_last"> </td> {{--value="{{ $searchedUser->last_name }}"--}}
                                            <td> <input type="text" class="tbl-input" value="Sample Suffix" id="edit_suffix"> </td> {{--value="{{ $searchedUser->suffix }}" --}}
                                            <td> <input type="text" class="tbl-input" value="Sample Email" id="edit_email"> </td> {{--value="{{ $searchedUser->email }}" --}}
                                            <td> <input type="text" class="tbl-input" placeholder="••••••••" value="Sample Password" id="edit_password"> </td> {{--value="{{ $searchedUser->password }}" --}}
                                            <td class="action-cell">
                                                <form action="{{ route('admin-update-user', $searchedUser->id) }}" method="POST" style="display: inline">
                                                    @csrf @method('PUT')
                                                    <button type="submit" class="btn-update">Update</button>
                                                </form>
                                                <form action="{{ route('admin-archive-user', $searchedUser->id) }}" method="POST" style="display: inline">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="btn-archive" onclick="return confirm('Archive this user?')">Archive</button>
                                                </form>
                                            </td>
                                        </tr>
                                    </tbody> 
                                </table>
                            </div>
                            <a href="{{ route('admin-create-users') }}" class="btn-form-submit mt-3 d-inline-block">Create User</a>
                        </div>
                    @else
                        <div class="search-card">
                            <h2 class="search-card-title">Search User</h2>
                            <form action="{{ route('admin-search-users') }}" method="GET">
                                <div class="form-field-label">Enter Username or Email</div>
                                <input type="text" name="query" class="search-input" placeholder="example@example.com" value="{{ request('query') }}" required>
                                <button type="submit" class="btn-search-submit">Search</button>
                            </form>
                            @if (request('query') && !$searchedUser)
                            <p class="no-data mt-3">No user found for "{{ request('query') }}"</p>
                            @endif
                        </div>
                    @endif
                
                @elseif ($section === 'search-license')
                    @if ($searchedLicense)
                        <div class="search-card">
                            <h2 class="search-card-title">Search Result</h2>
                            <div class="result-table-wrap">
                                <table class="result-table">
                                    <tbody>
                                        <tr> <td class="result-label">License Number</td> <td>Sample License Number</td> </tr> {{--{{ $searchedLicense->license_number }}--}}
                                        <tr> <td class="result-label">Status</td> <td>Sampel Status</td> </tr> {{--<span class="{{ $searchedLicense->license_status === 'Active' ? 'status-green' : 'status-red' }}">{{ $searchedLicense->license_status }}</span>--}}
                                        <tr> <td class="result-label">Type</td> <td>Sample License Type</td></tr> {{--{{ $searchedLicense->license_type }}--}}
                                        <tr> <td class="result-label">Issue Date</td> <td>Sample Issue Date</td> </tr> {{--{{ \Carbon\Carbon::parse($searchedLicense->issue_date)->format('M-d-Y') }}--}}
                                        <tr> <td class="result-label">Expiry Date</td> <td>Sample Expiry Date</td> </tr> {{--{{ \Carbon\Carbon::parse($searchedLicense->expiry_date)->format('M-d-Y') }}--}}
                                        <tr> <td class="result-label">DL Codes</td> <td>Sample DL Codes</td> </tr> {{-- @php $codes = is_string($searchedLicense->dl_codes) ? json_decode($searchedLicense->dl_codes, true) : $searchedLicense->dl_codes; @endphp {{ is_array($codes) ? implode(', ', $codes) : $searchedLicense->dl_codes }}--}}
                                        <tr> <td class="result-label">Name</td> <td>Sample First + Last Name</td> </tr> {{--{{ $searchedLicense->person->first_name }} {{ $searchedLicense->person->last_name }}--}}
                                        <tr> <td class="result-label">Birthday</td> <td>Sample birthday</td> </tr> {{--{{ \Carbon\Carbon::parse($searchedLicense->person->date_of_birth)->format('M-d-Y') }}--}}
                                        <tr> <td class="result-label">Gender</td> <td>Sample Gender</td> </tr> {{--{{ $searchedLicense->person->gender }--}}
                                        <tr> <td class="result-label">Address</td> <td>Sample Address</td> </tr> {{--{{ $searchedLicense->person->address }}--}}
                                        <tr> <td class="result-label">Nationality</td> <td>Sample Nationality</td> </tr> {{--{{ $searchedLicense->person->nationality }}--}}
                                        <tr> <td class="result-label">Height</td> <td>Sample Height</td> </tr> {{--{{ $searchedLicense->person->height }}--}}
                                        <tr> <td class="result-label">Weight</td> <td>SAmple Weight</td> </tr> {{--{{ $searchedLicense->person->weight }}--}}
                                        <tr> <td class="result-label">Eye Color</td> <td>Sample Eye Color</td> </tr> {{--{{ $searchedLicense->person->eye_color }}--}}
                                        <tr> <td class="result-label">Blood Type</td> <td>Sample Blood Type</td> </tr> {{--{{ $searchedLicense->person->blood_type }}--}}
                                    </tbody>
                                </table>
                            </div>
                            <div class="result-actions">
                                <form action="{{ route('admin-update-license', $searchedLicense->license_id) }}" method="POST" style="display: inline">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn-form-submit">Update</button>
                                </form>
                                <form action="{{ route('admin-revoke-license', $searchedLicense->license_id) }}" method="POST" style="display: inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-revoke" onclick="return confirm('Revoke this vehicle registration?')">Revoke</button>
                                </form>
                            </div>
                        </div>
                    @else 
                        <div class="search-card">
                            <h2 class="search-card-title">Search License</h2>
                            <form action="{{ route('admin-search-license') }}" method="GET">
                                <div class="form-field-label">Enter License Number</div>
                                <input type="text" name="query" class="search-input" placeholder="AXX-XX-XXXXXX" value="{{ request('query') }}" required>
                                <button type="submit" class="btn-search-submit">Search</button>
                            </form>
                            @if (request('query') && !$searchedLicense)
                                <p class="no-data mt-3">No license found for "{{ request('query') }}".</p>
                            @endif
                        </div>
                    @endif
                
                @elseif ($section === 'search-vehicle')
                    @if ($searchedVehicle)
                        <div class="search-card">
                            <h2 class="search-card-title">Search Result</h2>
                            <div class="result-table-wrap">
                                <table class="result-table">
                                    <tbody>
                                        <tr> <td class="result-label">Plate Number</td> <td>Sample Plate Number</td> </tr> {{--{{ $searchedVehicle->plate_number }}--}}
                                        <tr> <td class="result-label">MV File Number</td> <td>Sample MV File Number</td> </tr> {{--{{ $searchedVehicle->mv_file_number }}--}}
                                        <tr> <td class="result-label">VIN</td> <td>Sample VIN</td> </tr> {{--{{ $searchedVehicle->vin }}--}}
                                        <tr> <td class="result-label">Brand</td> <td>Sample Brand</td> </tr> {{--{{ $searchedVehicle->make }}--}}
                                        <tr> <td class="result-label">Model</td> <td>Sample Model</td> </tr> {{--{{ $searchedVehicle->model }}--}}
                                        <tr> <td class="result-label">Color</td> <td>Sample Color</td> </tr> {{--{{ $searchedVehicle->color }}--}}
                                        <tr> <td class="result-label">Year</td> <td>Sample Year</td> </tr> {{--{ $searchedVehicle->year }}--}}
                                        <tr> <td class="result-label">Registration Expiry</td> <td>Sample Registration Expiry</td> </tr> {{--{{ \Carbon\Carbon::parse($searchedVehicle->expiry_date)->format('M-d-Y') }}--}}
                                        <tr> <td class="result-label">Status</td> <td>Sample Status</td> </tr> {{--<span class="{{ $searchedVehicle->reg_status === 'Registered' ? 'status-green' : 'status-red' }}">{{ $searchedVehicle->reg_status }}</span>--}}
                                        <tr> <td class="result-label">License Number</td> <td>Sample License Number</td> </tr> {{--{{ $searchedVehicle->license->license_number ?? '—' }}--}}
                                    </tbody>
                                </table>
                            </div>
                            <div class="result-actions">
                                <form action="{{ route('admin-update-vehicle', $searchedVehicle->vehicle_id) }}" method="POST" style="display: inline">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn-form-submit">Update</button>
                                </form>
                                <form action="{{ route('admin-revoke-vehicle', $searchedVehicle->vehicle_id) }}" method="POST" style="display: inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-revoke" onclick="return confirm('Revoke this vehicle registration?')">Revoke</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="search-card">
                            <h2 class="search-card-title">Search Vehicle</h2>
                            <form method="GET" action="{{ route('admin-search-vehicle') }}">
                                <div class="form-field-label">Enter Plate or MV File Number</div>
                                <input type="text" name="query" class="search-input"
                                    placeholder="ABC-1234 / MV-0123456789"
                                    value="{{ request('query') }}" required>
                                <button type="submit" class="btn-search-submit">Search</button>
                            </form>
                            @if (request('query') && !$searchedVehicle)
                                <p class="no-data mt-3">No vehicle found for "{{ request('query') }}".</p>
                            @endif
                        </div>
                    @endif

                @elseif ($section === 'authorize')
                    <h1 class="dash-title">Authorize</h1>
                    <p class="dash-sub">Review to civilian support requests</p>

                    @if ($supportRequests && $supportRequests->count() > 0)
                        <div class="authorize-grid">
                            @foreach ($supportRequests as $req)
                                <div class="authorize-card">
                                    <div class="authorize-card-header">
                                        <span class="authorize-category">Password Change</span> {{--{{ ucfirst(str_replace('_', ' ', $req->category)) }}--}}
                                        <span class="authorize-date">Mar 24, 2026</span> {{--{{ \Carbon\Carbon::parse($req->created_at)->format('M-d-Y') }}--}}
                                    </div>
                                    <div class="authorize-from"> <i class="bi bi-person-circle me-1"></i> Sample Name </div> {{--{{ $req->user->username ?? 'Unknown' }} &mdash; {{ $req->user->email ?? '' }}--}}
                                    <div class="authorize-message">Sample Message</div> {{--{{ $req->message }}--}}
                                    <div class="authorize-actions">
                                        <form action="{{ route('admin-resolve-request', $req->id) }}" method="POST" style="display: inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn-resolve"><i class="bi bi-check-circle me-1"></i> Resolve </button>
                                        </form>
                                        <form action="{{ route('admin-dismiss-request', $req->id) }}" method="POST" style="display:inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-dismiss"> <i class="bi bi-x-circle me-1"></i> Dismiss </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="bi bi-inbox empty-state-icon"></i>
                            <p class="empty-state-text">No pending support requests.</p>
                        </div>
                    @endif
                
                @elseif ($section === 'settings')
                    <h1 class="dash-title">Settings</h1>
                    <p class="dash-sub">Manage system and account preferences</p>

                    <div class="form-card">
                        <h2 class="form-card-title">Account Settings</h2>
                        <form action="{{ route('admin-update-settings') }}" method="POST">
                            @csrf @method('PUT')
                            <div class="form-section">
                                <div class="form-field-label">Username</div>
                                <input type="text" name="username" class="form-input" value="Sample" required> {{--{{ auth()->user()->username }}--}}
                            </div>
                            <div class="form-section">
                                <div class="form-field-label">Email</div>
                                <input type="email" name="email" class="form-input" value="Sample Email" required> {{--{{ auth()->user()->email }}--}}
                            </div>
                            <div class="form-divider"></div>
                            <h2 class="form-card-title">Change Password</h2>
                            <div class="form-section">
                                <div class="form-field-label">Current Password</div>
                                <input type="password" name="current_password" class="form-input" placeholder="Enter current password">
                            </div>
                            <div class="form-row-2">
                                <div>
                                    <div class="form-field-label">New Password</div>
                                    <input type="password" name="new_password" class="form-input" placeholder="New password">
                                </div>
                                <div>
                                    <div class="form-field-label">Confirm New Password</div>
                                    <input type="password" name="new_password_confirmation" class="form-input" placeholder="Confirm new password">
                                </div>
                            </div>
                            <button type="submit" class="btn-form-submit">Save Changes</button>
                        </form>
                    </div>
                @endif
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/admin-dashboard.js') }}"></script>

</body>
</html>
@endsection