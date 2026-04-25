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

window.addEventListener('load', () => {
    const dashboardElements = {
        totalUsers: document.getElementById('totalUsers'),
        totalLicenses: document.getElementById('totalLicenses'),
        totalVehicles: document.getElementById('totalVehicles'),
        totalTickets: document.getElementById('totalTickets')
    };

    const usersCanvas = document.getElementById('usersChart');
    const licensesCanvas = document.getElementById('licenseChart');
    const vehiclesCanvas = document.getElementById('vehicleChart');
    const ticketsCanvas = document.getElementById('ticketChart');

    if (!usersCanvas && !licensesCanvas && !vehiclesCanvas && !ticketsCanvas) {
        return;
    }

    const toInt = (value) => {
        const parsed = parseInt(value, 10);
        return Number.isNaN(parsed) ? 0 : parsed;
    };

    const readInitialTotals = () => ({
        totalUsers: toInt(dashboardElements.totalUsers?.dataset.total ?? dashboardElements.totalUsers?.textContent),
        totalLicenses: toInt(dashboardElements.totalLicenses?.dataset.total ?? dashboardElements.totalLicenses?.textContent),
        totalVehicles: toInt(dashboardElements.totalVehicles?.dataset.total ?? dashboardElements.totalVehicles?.textContent),
        totalTickets: toInt(dashboardElements.totalTickets?.dataset.total ?? dashboardElements.totalTickets?.textContent)
    });

    const updateStatCards = (totals) => {
        Object.entries(dashboardElements).forEach(([key, element]) => {
            if (!element) return;
            const value = toInt(totals[key]);
            element.textContent = value;
            element.dataset.total = value;
        });
    };

    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0,
                    font: { size: 10 }
                }
            },
            x: {
                grid: { display: false },
                ticks: { font: { size: 10 } }
            }
        }
    };

    const buildChart = (canvas, label, color, values = [], labels = [], type = 'bar') => {
        if (!canvas) return null;
        return new Chart(canvas, {
            type,
            data: {
                labels,
                datasets: [{
                    label,
                    data: values,
                    backgroundColor: type === 'line' ? 'rgba(6,71,137,0.15)' : color,
                    borderColor: color,
                    borderWidth: type === 'line' ? 2 : 0,
                    borderRadius: type === 'line' ? 0 : 4,
                    fill: type === 'line',
                    tension: 0.35,
                    pointRadius: type === 'line' ? 3 : 0
                }]
            },
            options: chartOptions
        });
    };

    const initialTotals = readInitialTotals();
    const initialLabels = ['Current'];

    const charts = {
        users: buildChart(usersCanvas, 'Registered Users', '#064789', [initialTotals.totalUsers], initialLabels, 'bar'),
        licenses: buildChart(licensesCanvas, 'Registered Licenses', '#0f766e', [initialTotals.totalLicenses], initialLabels, 'bar'),
        vehicles: buildChart(vehiclesCanvas, 'Registered Vehicles', '#b45309', [initialTotals.totalVehicles], initialLabels, 'line'),
        tickets: buildChart(ticketsCanvas, 'Issued Tickets', '#b91c1c', [initialTotals.totalTickets], initialLabels, 'bar')
    };

    const updateCharts = (totals) => {
        const trendLabels = Array.isArray(totals.trend?.labels) && totals.trend.labels.length > 0
            ? totals.trend.labels
            : ['Current'];

        const normalizeSeries = (series, fallbackValue) => {
            if (!Array.isArray(series) || series.length === 0) {
                return [toInt(fallbackValue)];
            }
            return series.map((value) => toInt(value));
        };

        const chartSeriesMap = {
            users: normalizeSeries(totals.trend?.users, totals.totalUsers),
            licenses: normalizeSeries(totals.trend?.licenses, totals.totalLicenses),
            vehicles: normalizeSeries(totals.trend?.vehicles, totals.totalVehicles),
            tickets: normalizeSeries(totals.trend?.tickets, totals.totalTickets)
        };

        Object.entries(charts).forEach(([key, chart]) => {
            if (!chart) return;
            chart.data.labels = trendLabels;
            chart.data.datasets[0].data = chartSeriesMap[key];
            chart.update();
        });
    };

    const refreshDashboardMetrics = () => {
        fetch('/admin/api/dashboard-totals', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error('Failed to fetch dashboard totals');
                }
                return response.json();
            })
            .then((totals) => {
                updateStatCards(totals);
                updateCharts(totals);
            })
            .catch((error) => {
                console.error('Dashboard totals refresh error:', error);
            });
    };

    refreshDashboardMetrics();
    window.setInterval(refreshDashboardMetrics, 30000);
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
        .catch(err => console.error('Client Gen Error:', err));
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
        .catch(err => console.error('MV File Gen Error:', err));
});
document.getElementById('generate-plate-btn')?.addEventListener('click', () => {
    fetch('/admin/generate-plate-number')
        .then(res => {
            if (!res.ok) throw new Error('Route not found');
            return res.json();
        })
        .then(data => {
            document.getElementById('plateNumber').value = data.plateNumber;
        })
        .catch(err => console.error('Plate Gen Error:', err));
});

// this still needs to be connected
function toggleEyeColorInput() {
    const select = document.getElementById('eyeColorSelect');
    const otherInput = document.getElementById('otherEyeColor');
    
    if (select && otherInput) {
        if (select.value === 'other') {
            otherInput.style.display = 'block';
            otherInput.required = true;
            select.removeAttribute('name');
            otherInput.setAttribute('name', 'eye_color');
        } else {
            otherInput.style.display = 'none';
            otherInput.required = false;
            select.setAttribute('name', 'eye_color');
            otherInput.removeAttribute('name');
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Make sure the eye color select exists
    if (document.getElementById('eyeColorSelect')) {
        toggleEyeColorInput(); // Initialize state
    }
});