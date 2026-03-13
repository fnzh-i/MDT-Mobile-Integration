<div class="container">
    <h2>Search License</h2>
    <div class="search-box">
        <input type="text" id="license_number" placeholder="Enter License Number">
        <button onclick="performSearch()">Search</button>
    </div>

    <div id="result-area" style="margin-top: 20px;"></div>
</div>

<script>
function performSearch() {
    const num = document.getElementById('license_number').value;
    const resultDiv = document.getElementById('result-area');
    
    resultDiv.innerHTML = "Searching...";

    // This calls your Core Controller via the route we made in Step 1
    fetch(`/api/license/search?license_number=${num}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                resultDiv.innerHTML = `<pre>${JSON.stringify(data.data, null, 2)}</pre>`;
            } else {
                resultDiv.innerHTML = `<p style="color:red">${data.message}</p>`;
            }
        })
        .catch(err => {
            resultDiv.innerHTML = "Error connecting to server.";
        });
}
</script>