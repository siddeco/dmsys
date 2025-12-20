// resources/js/sidebar.js
// إضافة JavaScript للتحكم في السايد بار

// توجيه الصفحة عند النقر على رابط
document.addEventListener('DOMContentLoaded', function () {
    // highlight active menu item
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.sidebar-link, .sidebar-submenu-link');

    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
            // فتح القائمة الأم إذا كان الرابط في submenu
            const parentCollapse = link.closest('.collapse');
            if (parentCollapse) {
                parentCollapse.classList.add('show');
                const parentLink = parentCollapse.previousElementSibling;
                if (parentLink && parentLink.classList.contains('sidebar-link')) {
                    parentLink.setAttribute('aria-expanded', 'true');
                }
            }
        }
    });

    // إغلاق السايد بار عند النقر على رابط في الجوال
    if (window.innerWidth < 992) {
        const sidebarLinks = document.querySelectorAll('.sidebar-link, .sidebar-submenu-link');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function () {
                const sidebar = document.getElementById('mainSidebar');
                sidebar.classList.remove('mobile-open');
            });
        });
    }
});