// resources/js/app.js
import './bootstrap';

// JavaScript للتحكم في السايد بار
document.addEventListener('DOMContentLoaded', function () {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.main-sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function () {
            if (window.innerWidth < 992) {
                sidebar.classList.toggle('mobile-open');
                if (overlay) overlay.classList.toggle('show');
            }
        });
    }

    if (overlay) {
        overlay.addEventListener('click', function () {
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('show');
        });
    }

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 992) {
            sidebar.classList.remove('mobile-open');
            if (overlay) overlay.classList.remove('show');
        }
    });
});