// JavaScript الأساسي للتطبيق - إصلاح كامل
document.addEventListener('DOMContentLoaded', function () {
    console.log('App loaded - fixing layout issues');

    // 1. إصلاح السايد بار
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.main-sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            if (window.innerWidth < 992) {
                sidebar.classList.toggle('mobile-open');
                if (overlay) {
                    overlay.classList.toggle('show');
                }
            }
        });
    }

    // إغلاق السايد بار عند النقر على overlay
    if (overlay) {
        overlay.addEventListener('click', function () {
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('show');
        });
    }

    // 2. إصلاح الجداول على الجوال
    function fixTables() {
        const tables = document.querySelectorAll('.table-responsive');
        tables.forEach(table => {
            if (window.innerWidth < 768) {
                table.style.overflowX = 'auto';
                table.style.WebkitOverflowScrolling = 'touch';

                const innerTable = table.querySelector('table');
                if (innerTable) {
                    innerTable.style.minWidth = '800px';
                }
            } else {
                table.style.overflowX = '';
                const innerTable = table.querySelector('table');
                if (innerTable) {
                    innerTable.style.minWidth = '';
                }
            }
        });
    }

    fixTables();
    window.addEventListener('resize', fixTables);

    // 3. إصلاح الداشبورد على الجوال
    function fixDashboard() {
        const dashboard = document.querySelector('.dashboard-container');
        if (!dashboard) return;

        if (window.innerWidth < 768) {
            // إصلاح البطاقات
            const cards = dashboard.querySelectorAll('.card');
            cards.forEach(card => {
                card.style.marginBottom = '16px';
            });

            // إصلاح الأعمدة
            const cols = dashboard.querySelectorAll('[class*="col-"]');
            cols.forEach(col => {
                col.style.marginBottom = '16px';
            });
        }
    }

    fixDashboard();
    window.addEventListener('resize', fixDashboard);

    // 4. إصلاح القوائم المنسدلة
    const dropdowns = document.querySelectorAll('.dropdown-toggle');
    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('click', function (e) {
            if (window.innerWidth < 992) {
                e.preventDefault();
                const menu = this.nextElementSibling;
                if (menu) {
                    const isOpen = menu.classList.contains('show');

                    // إغلاق جميع القوائم الأخرى
                    document.querySelectorAll('.dropdown-menu.show').forEach(otherMenu => {
                        if (otherMenu !== menu) {
                            otherMenu.classList.remove('show');
                        }
                    });

                    // فتح/إغلاق هذه القائمة
                    menu.classList.toggle('show');
                }
            }
        });
    });

    // إغلاق القوائم عند النقر خارجها
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                menu.classList.remove('show');
            });
        }
    });

    // 5. إصلاح إعادة تحجيم النافذة
    let resizeTimer;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            // إعادة تعيين السايد بار على الشاشات الكبيرة
            if (window.innerWidth >= 992) {
                if (sidebar) sidebar.classList.remove('mobile-open');
                if (overlay) overlay.classList.remove('show');
            }

            // إصلاح الجداول
            fixTables();

            // إصلاح الداشبورد
            fixDashboard();
        }, 250);
    });

    // 6. إصلاح التحميل الأولي
    window.addEventListener('load', function () {
        // إضافة تأثير ظهور
        const content = document.querySelector('.content-wrapper');
        if (content) {
            content.style.opacity = '0';
            content.style.transition = 'opacity 0.3s ease';

            setTimeout(() => {
                content.style.opacity = '1';
            }, 100);
        }

        // إصلاح أي مشاكل متبقية
        setTimeout(fixTables, 500);
        setTimeout(fixDashboard, 500);
    });

    // 7. إصلاح الروابط في السايد بار للجوال
    const sidebarLinks = document.querySelectorAll('.sidebar-menu a');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function () {
            if (window.innerWidth < 992) {
                setTimeout(() => {
                    if (sidebar) sidebar.classList.remove('mobile-open');
                    if (overlay) overlay.classList.remove('show');
                }, 300);
            }
        });
    });

    // 8. منع التمرير عند فتح السايد بار
    function preventScroll() {
        if (window.innerWidth < 992 && sidebar && sidebar.classList.contains('mobile-open')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    }

    // مراقبة تغييرات السايد بار
    if (sidebar) {
        const observer = new MutationObserver(preventScroll);
        observer.observe(sidebar, {
            attributes: true,
            attributeFilter: ['class']
        });
    }

    console.log('All fixes applied successfully');
});