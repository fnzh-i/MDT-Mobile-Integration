<div class="container">
    <h2>Search License</h2>
    <div class="search-box">
        <input type="text" id="license_number" placeholder="Enter License Number">
        <button onclick="performSearch()">Search</button>
    </div>

    <div id="result-content" style="margin-top: 20px;"></div>
</div>

<script>
function performSearch() {
    const num = document.getElementById('license_number').value;
    const resultContent = document.getElementById('result-content');
    

    fetch(`/api/license/search?license_number=${num}`, {
        headers: { 'Accept': 'application/json' }
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || "Server Error");
        return data;
    })
    .then(data => {
        if (data.status === "success") {
            const p = data.data.person;
            const l = data.data.license;

            let html = `
                <h3>License Details</h3>
                <ul>
                    <li><strong>License ID:</strong> ${l.id}</li>
                    <li><strong>License Number:</strong> ${l.licenseNumber}</li>
                    <li><strong>Type:</strong> ${l.type}</li>
                    <li><strong>Status:</strong> ${l.status}</li>
                    <li><strong>DL Codes:</strong> ${l.dlCodes}</li>
                    <li><strong>Issue Date:</strong> ${l.issueDate}</li>
                    <li><strong>Expiry Date:</strong> ${l.expiryDate}</li>
                </ul>

                <hr>
                <h3>Personal Information</h3>
                <ul>
                    <li><strong>First Name:</strong> ${p.firstName}</li>
                    <li><strong>Middle Name:</strong> ${p.middleName ?? 'N/A'}</li>
                    <li><strong>Last Name:</strong> ${p.lastName}</li>
                    <li><strong>Suffix:</strong> ${p.suffix ?? 'N/A'}</li>
                    <li><strong>Date of Birth:</strong> ${p.dateOfBirth}</li>
                    <li><strong>Gender:</strong> ${p.gender}</li>
                    <li><strong>Address:</strong> ${p.address}</li>
                    <li><strong>Nationality:</strong> ${p.nationality}</li>
                    <li><strong>Height:</strong> ${p.height}</li>
                    <li><strong>Weight:</strong> ${p.weight}</li>
                    <li><strong>Eye Color:</strong> ${p.eyeColor}</li>
                    <li><strong>Blood Type:</strong> ${p.bloodType}</li>
                </ul>

                <hr>
                <h3>Tickets & Violations</h3>
            `;

            data.data.tickets.forEach(ticket => {
                const fullPath = `/storage/${ticket.proof_image}`;
                
                html += `
                    <div style="border: 1px solid #000; padding: 10px; margin-bottom: 20px;">
                        <p><strong>Reference No.:</strong> ${ticket.refNumber}</p>
                        <p><strong>Date of Incident:</strong> ${ticket.dateOfIncident}</p>
                        <p><strong>Place of Incident:</strong> ${ticket.placeOfIncident}</p>
                        <p><strong>Notes:</strong> ${ticket.notes ?? 'N/A'}</p>
                        <p><strong>Status:</strong> ${ticket.status}</p>
                        
                        <p><strong>Violations:</strong></p>
                        <ul>`;

                ticket.violations.forEach(v => {
                    html += `
                        <li>${v.name} (${v.offense_level ?? 'Base'}) - ₱${v.fine}</li>
                    `;
                });

                html += `
                        </ul>
                        <p><strong>Total Fine:</strong> ₱${ticket.totalFine}</p>
                        <img src="${fullPath}" alt="Ticket Proof" class="img-fluid" style="max-width: 300px; display: block; margin-top: 10px; border: 1px solid #ccc;">
                        <p><strong>Created At:</strong> ${ticket.createdAt}</p>
                        <p><strong>Updated At:</strong> ${ticket.updatedAt}</p>
                    </div>
                `;
            });

            resultContent.innerHTML = html;
        }
    })
    .catch(err => {
        alert(err.message);
    });
}
</script>