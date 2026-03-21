<div class="container">
    <h2>Search Vehicle</h2>
    <div class="search-box">
        <input type="text" id="search_number" placeholder="Plate or MV File Number">
        <button onclick="performSearch()">Search</button>
    </div>

    <div id="result-area" style="margin-top: 20px;"></div>
</div>

<script>
function performSearch() {
    const num = document.getElementById('search_number').value;
    const resultDiv = document.getElementById('result-area');
    

    fetch(`/api/vehicle/search?search_number=${num}`, {
        headers: { 'Accept': 'application/json' }
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || "Server Error");
        return data;
    })
    .then(data => {
        if (data.status === "success") {
            const v = data.data.vehicle;
            const o = data.data.owner;

            let html = `
                <hr>
                <h3>Vehicle Information</h3>
                <ul>
                    <li><strong>Plate Number:</strong> ${v.plateNumber}</li>
                    <li><strong>MV File Number:</strong> ${v.mvFileNumber}</li>
                    <li><strong>VIN:</strong> ${v.vin}</li>
                    <li><strong>Make:</strong> ${v.make}</li>
                    <li><strong>Model:</strong> ${v.model}</li>
                    <li><strong>Year:</strong> ${v.year}</li>
                    <li><strong>Color:</strong> ${v.color}</li>
                    <li><strong>Registration Status:</strong> ${v.regStatus}</li>
                    <li><strong>Issue Date:</strong> ${v.issueDate}</li>
                    <li><strong>Expiry Date:</strong> ${v.expiryDate}</li>
                </ul>

                <h3>Owner Information</h3>
                <ul>
                    <li><strong>License Number:</strong> ${o.licenseNumber}</li>
                    <li><strong>Owner:</strong> ${o.firstName} ${o.middleName || ''} ${o.lastName} ${o.suffix || ''}</li>
                    <li><strong>Address:</strong> ${o.address}</li>
                </ul>
                <hr>
            `;

            resultDiv.innerHTML = html;
        }
    })
    .catch(err => {
        alert(err.message);
    });
}
</script>