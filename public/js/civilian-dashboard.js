const hamburgerBtn = document.getElementById('hamburgerBtn');
const sidebar      = document.getElementById('sidebar');
const overlay      = document.getElementById('overlay');

const isMobile = () => window.innerWidth <= 640;

function getSavedState() {
    const saved = sessionStorage.getItem('sidebarOpen');
    if (saved !== null) return saved === 'true';
    return !isMobile(); // default
}

let sidebarOpen = getSavedState();

function applyState(animate) {
    if (!animate) {
        sidebar.style.transition = 'none';
        overlay.style.transition = 'none';
    }

    if (sidebarOpen) {
        sidebar.classList.remove('closed');
        if (isMobile()) {
            overlay.classList.add('show');
        } else {
            overlay.classList.remove('show');
        }
    } else {
        sidebar.classList.add('closed');
        overlay.classList.remove('show');
    }

    sessionStorage.setItem('sidebarOpen', sidebarOpen);

    if (!animate) {
        requestAnimationFrame(() => {
            sidebar.style.transition = '';
            overlay.style.transition = '';
        });
    }
}

function openSidebar() {
    sidebarOpen = true;
    applyState(true);
}

function closeSidebar() {
    sidebarOpen = false;
    applyState(true);
}

function toggleSidebar() {
    sidebarOpen ? closeSidebar() : openSidebar();
}

applyState(false);

hamburgerBtn.addEventListener('click', toggleSidebar);
overlay.addEventListener('click', closeSidebar);

window.addEventListener('resize', () => {
    if (!isMobile()) {
        overlay.classList.remove('show');
        if (sidebarOpen) {
            sidebar.classList.remove('closed');
        }
    } else {
        if (!sidebarOpen) {
            overlay.classList.remove('show');
        }
    }
});