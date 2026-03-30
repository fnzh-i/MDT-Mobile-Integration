const hamburgerBtn = document.getElementById('hamburgerBtn');
const sidebar      = document.getElementById('sidebar');
const overlay      = document.getElementById('overlay');

const isMobile = () => window.innerWidth <= 640;

function getSavedState() {
    const saved = sessionStorage.getItem('supervisorSidebarOpen');
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
    sessionStorage.setItem('supervisorSidebarOpen', sidebarOpen);
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

function openEditModal(ticketId, currentStatus) {
    const modal = document.getElementById('editModal');
    if (!modal) return;
    const radios = modal.querySelectorAll('input[type="radio"]');
    radios.forEach(r => { r.checked = r.value === currentStatus; });
    modal.style.display = 'flex';
}

function closeEditModal() {
    const modal = document.getElementById('editModal');
    if (modal) modal.style.display = 'none';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeEditModal();
});