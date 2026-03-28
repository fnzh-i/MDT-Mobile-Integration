const hamburgerBtn = document.getElementById('hamburgerBtn');
const sidebar      = document.getElementById('sidebar');
const overlay      = document.getElementById('overlay');

const isMobile = () => window.innerWidth <= 640;

function getSavedState() {
    const saved = sessionStorage.getItem('adminSidebarOpen');
    if (saved !== null) return saved === 'true';
    return !isMobile();
}

let sidebarOpen = getSavedState();

function applyState(animate) {
    if (!animate) {
        sidebar.style.transition = 'none';
        overlay.style.transition = 'none';
    }
    if (sidebarOpen) {
        sidebar.classList.remove('closed');
        isMobile() ? overlay.classList.add('show') : overlay.classList.remove('show');
    } else {
        sidebar.classList.add('closed');
        overlay.classList.remove('show');
    }
    sessionStorage.setItem('adminSidebarOpen', sidebarOpen);
    if (!animate) {
        requestAnimationFrame(() => {
            sidebar.style.transition = '';
            overlay.style.transition = '';
        });
    }
}

function openSidebar()   { sidebarOpen = true;  applyState(true); }
function closeSidebar()  { sidebarOpen = false; applyState(true); }
function toggleSidebar() { sidebarOpen ? closeSidebar() : openSidebar(); }

applyState(false);

hamburgerBtn.addEventListener('click', toggleSidebar);
overlay.addEventListener('click', closeSidebar);

window.addEventListener('resize', () => {
    if (!isMobile()) {
        overlay.classList.remove('show');
        if (sidebarOpen) sidebar.classList.remove('closed');
    } else {
        if (!sidebarOpen) overlay.classList.remove('show');
    }
});

window.addEventListener('load', () => { //sample
    const opts = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { font: { size: 10 } } },
            x: { grid: { display: false }, ticks: { font: { size: 10 } } }
        }
    };

    const u = document.getElementById('usersChart'); 
    if (u) new Chart(u, { type: 'bar', data: { labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul'], datasets: [{ data: [5,12,8,20,15,18,10], backgroundColor: '#064789', borderRadius: 4 }] }, options: opts });

    const l = document.getElementById('licenseChart');
    if (l) new Chart(l, { type: 'bar', data: { labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul'], datasets: [{ data: [40,70,55,90,60,80,45], backgroundColor: '#064789', borderRadius: 4 }] }, options: opts });

    const v = document.getElementById('vehicleChart');
    if (v) new Chart(v, { type: 'line', data: { labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul'], datasets: [{ data: [20,45,30,60,35,50,25], borderColor: '#064789', backgroundColor: 'rgba(6,71,137,0.1)', fill: true, tension: 0.4, pointRadius: 3 }] }, options: opts });

    const t = document.getElementById('ticketChart');
    if (t) new Chart(t, { type: 'bar', data: { labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul'], datasets: [{ data: [3,8,5,14,9,11,6], backgroundColor: '#064789', borderRadius: 4 }] }, options: opts });
});

document.getElementById('generate-client-num-btn')?.addEventListener('click', () => {
    fetch('/admin/generate-user-number')
        .then(res => {
            if (!res.ok) throw new Error('Check route prefix');
            return res.json();
        })
        .then(data => {
            document.getElementById('clientNumber').value = data.clientNumber;
        })
        .catch(err => console.error('Gen Error:', err));
});
document.getElementById('generate-ln-btn')?.addEventListener('click', () => {
    fetch('/admin/generate-license-number')
        .then(res => {
            if (!res.ok) throw new Error('Route not found');
            return res.json();
        })
        .then(data => {
            document.getElementById('licenseNumber').value = data.licenseNumber;
        })
        .catch(err => console.error('License Gen Error:', err));
});
document.getElementById('generate-mvfile-btn')?.addEventListener('click', () => {
    fetch('/admin/generate-mvfile-number')
        .then(res => {
            if (!res.ok) throw new Error('Route not found');
            return res.json();
        })
        .then(data => {
            document.getElementById('mvFileNumber').value = data.mvFileNumber;
        })
        .catch(err => console.error('License Gen Error:', err));
});