@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-id-card mr-2"></i> {{ __('Search Driver License or Vehicle') }}
                </div>
                <div class="card-body">
                    <div class="input-group mb-3">
                        <input type="text" id="license_number" class="form-control" placeholder="Enter License Number (e.g., D01-12-000567)" aria-label="License Number">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button" onclick="performLicenseSearch()">
                                <span id="btn-text">Search</span>
                                <span id="btn-spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>
                        </div>
                    </div>

                    <div id="search-results" class="mt-4 d-none">
                        <div class="alert alert-info border">
                            <h5 class="alert-heading">License Information</h5>
                            <hr>
                            <div id="result-content" class="bg-light p-3 rounded">
                                </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="text" id="search_number" class="form-control" placeholder="Enter Vehicle Plate Number (e.g., ABC 123)" aria-label="Vehicle Number">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button" onclick="performVehicleSearch()">
                                <span id="btn-text">Search</span>
                                <span id="btn-spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>
                        </div>
                    </div>
                    <div id="search-results" class="mt-4 d-none">
                        <div class="alert alert-info border">
                            <h5 class="alert-heading">Vehicle Information</h5>
                            <hr>
                            <div id="result-content" class="bg-light p-3 rounded">
                                </div>
                        </div>
                    </div>
                    

                    <a href="{{ route('license.create') }}" class="btn btn-success mb-3">
                        <i class="fas fa-plus"></i> Create New License
                    </a>
                     <a href="{{ route('vehicle.create') }}" class="btn btn-success mb-3">
                        <i class="fas fa-plus"></i> Create New Vehicle
                    </a>
                    <a href="{{ route('user.create') }}" class="btn btn-success mb-3">
                        <i class="fas fa-plus"></i> Create New User
                    </a>
                    <a href="{{ route('ticket.create') }}" class="btn btn-success mb-3">
                        <i class="fas fa-plus"></i> Create New Ticket
                    </a>

                    <div id="search-error" class="alert alert-danger mt-3 d-none"></div>
                </div>
                <div class="mt-4 p-3 border rounded bg-light">
                    <h6>Test Deletion Manually:</h6>
                    <div class="input-group">
                        <input type="number" id="test_ticket_id" class="form-control" placeholder="Enter Ticket ID to Delete">
                        <button class="btn btn-danger" onclick="deleteTicket(document.getElementById('test_ticket_id').value)">
                            Delete Ticket & File
                        </button>
                    </div>
                </div>

               <div class="mt-4 p-3 border rounded bg-light">
                    <h6>Test Settlement Manually:</h6>
                    <div class="input-group">
                        <input type="number" id="test_settle_id" class="form-control" placeholder="Ticket ID">
                        
                        <button class="btn btn-success" style="width: 100px;" 
                                onclick="settleTicket(document.getElementById('test_settle_id').value)">
                            Settle
                        </button>
                        
                        <button class="btn btn-warning text-black" style="width: 100px;" 
                                onclick="unsettleTicket(document.getElementById('test_settle_id').value)">
                            Unsettle
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function performLicenseSearch() {
    const licenseNum = document.getElementById('license_number').value;
    const resultArea = document.getElementById('search-results');
    const resultContent = document.getElementById('result-content');
    const errorArea = document.getElementById('search-error');
    const btnText = document.getElementById('btn-text');
    const btnSpinner = document.getElementById('btn-spinner');

    if (!licenseNum) {
        alert("Please enter a license number.");
        return;
    }

    // UI Feedback: Loading
    errorArea.classList.add('d-none');
    resultArea.classList.add('d-none');
    btnText.classList.add('d-none');
    btnSpinner.classList.remove('d-none');

    // Fetch call to your Core LicenseController
    fetch(`/api/license/search?license_number=${encodeURIComponent(licenseNum)}`)
        .then(response => response.json())
        .then(data => {
            btnText.classList.remove('d-none');
            btnSpinner.classList.add('d-none');

            if (data.status === "success") {
                resultArea.classList.remove('d-none');
                // Format the JSON data nicely for display
                resultContent.innerHTML = `<pre class="mb-0"><code>${JSON.stringify(data.data, null, 2)}</code></pre>`;
            } else {
                errorArea.classList.remove('d-none');
                errorArea.innerText = data.message || "License not found.";
            }
        })
        .catch(err => {
            btnText.classList.remove('d-none');
            btnSpinner.classList.add('d-none');
            errorArea.classList.remove('d-none');
            errorArea.innerText = "Connection error. Please check your database/server.";
        });
}

function performVehicleSearch() {
    const searchNum = document.getElementById('search_number').value;
    const resultArea = document.getElementById('search-results');
    const resultContent = document.getElementById('result-content');
    const errorArea = document.getElementById('search-error');
    const btnText = document.getElementById('btn-text');
    const btnSpinner = document.getElementById('btn-spinner');

    if (!searchNum) {
        alert("Please enter a plate number.");
        return;
    }

    // UI Feedback: Loading
    errorArea.classList.add('d-none');
    resultArea.classList.add('d-none');
    btnText.classList.add('d-none');
    btnSpinner.classList.remove('d-none');

    // Fetch call to your Core VehicleController
    fetch(`/api/vehicle/search?search_number=${encodeURIComponent(searchNum)}`)
        .then(response => response.json())
        .then(data => {
            btnText.classList.remove('d-none');
            btnSpinner.classList.add('d-none');

            if (data.status === "success") {
                resultArea.classList.remove('d-none');
                // Format the JSON data nicely for display
                resultContent.innerHTML = `<pre class="mb-0"><code>${JSON.stringify(data.data, null, 2)}</code></pre>`;
            } else {
                errorArea.classList.remove('d-none');
                errorArea.innerText = data.message || "Vehicle not found.";
            }
        })
        .catch(err => {
            btnText.classList.remove('d-none');
            btnSpinner.classList.add('d-none');
            errorArea.classList.remove('d-none');
            errorArea.innerText = "Connection error. Please check your database/server.";
        });
}
function deleteTicket(ticketId) {
    if (!confirm("Are you sure you want to delete this ticket and its image?")) return;

    fetch(`/ticket/delete/${ticketId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload(); // Refresh to show the data is gone
    })
    .catch(err => alert("Error deleting ticket."));
}
function settleTicket(ticketId) {
    if (!ticketId) {
        alert("Please enter a Ticket ID.");
        return;
    }

    if (!confirm("Are you sure you want to mark this ticket as Settled?")) return;

    fetch(`/ticket/settle/${ticketId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload();
    })
    .catch(err => alert("Error settling ticket."));
}
function unsettleTicket(ticketId) {
    if (!ticketId) {
        alert("Please enter a Ticket ID.");
        return;
    }

    if (!confirm("Are you sure you want to Unsettle this ticket?")) return;

    fetch(`/ticket/unsettle/${ticketId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload();
    })
    .catch(err => alert("Error unsettling ticket."));
}
</script>
@endsection