<aside class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse" id="sidebarMenu">
    <div class="position-sticky d-flex flex-column" style="top: 70px; height: calc(100vh - 70px);">
        <!-- Sidebar Header -->
        <div class="sidebar-header p-3 border-bottom flex-shrink-0">
            <h6 class="sidebar-title mb-0">
                <i class="fas fa-tachometer-alt me-2"></i>القائمة الرئيسية
            </h6>
        </div>

        <!-- Navigation - Scrollable Content -->
        <div class="sidebar-content flex-grow-1 overflow-auto" style="max-height: calc(100vh - 200px);">
            <ul class="nav flex-column mt-3">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        لوحة التحكم
                    </a>
                </li>

                <li class="nav-section mt-4">
                    <small class="text-muted px-3 fw-bold">الإدارة</small>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('devices*') ? 'active' : '' }}"
                        href="{{ route('devices.index') }}">
                        <i class="fas fa-microscope me-2"></i>
                        الأجهزة الطبية
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('projects*') ? 'active' : '' }}"
                        href="{{ route('projects.index') }}">
                        <i class="fas fa-hospital me-2"></i>
                        المشاريع
                    </a>
                </li>

                <li class="nav-section mt-4">
                    <small class="text-muted px-3 fw-bold">الصيانة</small>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('pm-plans*') ? 'active' : '' }}"
                        href="{{ route('pm.plans.index') }}">
                        <i class="fas fa-calendar-check me-2"></i>
                        الصيانة الوقائية
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('breakdowns*') ? 'active' : '' }}"
                        href="{{ route('breakdowns.index') }}">
                        <i class="fas fa-tools me-2"></i>
                        إدارة الأعطال
                    </a>
                </li>

                <li class="nav-section mt-4">
                    <small class="text-muted px-3 fw-bold">المخزون</small>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('spare-parts*') ? 'active' : '' }}"
                        href="{{ route('spare_parts.index') }}">
                        <i class="fas fa-cogs me-2"></i>
                        قطع الغيار
                    </a>
                </li>

                <li class="nav-section mt-4">
                    <small class="text-muted px-3 fw-bold">التقارير</small>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('reports*') ? 'active' : '' }}"
                        href="{{ route('reports.spare-parts') }}">
                        <i class="fas fa-chart-bar me-2"></i>
                        التقارير والإحصائيات
                    </a>
                </li>

                <li class="nav-section mt-4">
                    <small class="text-muted px-3 fw-bold">الإعدادات</small>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                        <i class="fas fa-users me-2"></i>
                        المستخدمون
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('profile*') ? 'active' : '' }}"
                        href="{{ route('profile.edit') }}">
                        <i class="fas fa-user-cog me-2"></i>
                        الإعدادات الشخصية
                    </a>
                </li>
            </ul>
        </div>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer p-3 border-top flex-shrink-0">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-primary"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <small class="text-muted">
                        <strong>الإصدار:</strong> 2.0.1<br>
                        <strong>الحالة:</strong> نشط
                    </small>
                </div>
            </div>
        </div>
    </div>
</aside>

<style>
    /* Sidebar Styles */
    .sidebar {
        position: fixed;
        top: 70px;
        right: 0;
        bottom: 0;
        width: 250px;
        z-index: 1020;
        box-shadow: -2px 0 15px rgba(0, 0, 0, 0.08);
        border-left: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        overflow-y: auto;
        background: white;
    }

    /* Hide sidebar by default on mobile */
    @media (max-width: 991.98px) {
        .sidebar {
            right: -280px;
            width: 280px;
        }

        .sidebar.show {
            right: 0;
        }

        .sidebar-backdrop {
            position: fixed;
            top: 70px;
            right: 0;
            bottom: 0;
            left: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1019;
            display: none;
        }

        .sidebar.show~.sidebar-backdrop {
            display: block;
        }
    }

    /* Show sidebar by default on desktop */
    @media (min-width: 992px) {
        .sidebar {
            right: 0 !important;
            display: block !important;
        }
    }

    .sidebar-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }

    .sidebar-title {
        font-weight: 600;
        color: #2a4c7d;
        font-size: 1rem;
    }

    .sidebar-content {
        scrollbar-width: thin;
        scrollbar-color: #c1c1c1 transparent;
    }

    /* Custom scrollbar for sidebar */
    .sidebar-content::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-content::-webkit-scrollbar-track {
        background: transparent;
    }

    .sidebar-content::-webkit-scrollbar-thumb {
        background-color: #c1c1c1;
        border-radius: 10px;
    }

    .sidebar-content::-webkit-scrollbar-thumb:hover {
        background-color: #a8a8a8;
    }

    .nav-item .nav-link {
        padding: 12px 20px;
        color: #495057;
        border-radius: 8px;
        margin: 4px 10px;
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
    }

    .nav-item .nav-link:hover {
        background: linear-gradient(90deg, rgba(42, 76, 125, 0.1) 0%, rgba(42, 76, 125, 0.05) 100%);
        color: #2a4c7d;
        padding-right: 25px;
        transform: translateX(-2px);
    }

    .nav-item .nav-link:hover:before {
        content: '';
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 3px;
        height: 60%;
        background: #2a4c7d;
        border-radius: 3px;
    }

    .nav-item .nav-link.active {
        background: linear-gradient(90deg, #2a4c7d 0%, #3a86ff 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(42, 76, 125, 0.25);
        padding-right: 25px;
    }

    .nav-item .nav-link.active:after {
        content: '';
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 70%;
        background: white;
        border-radius: 4px;
        box-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
    }

    .nav-item .nav-link i {
        width: 20px;
        text-align: center;
        font-size: 0.95rem;
    }

    .nav-section {
        margin: 20px 0 8px 0;
        padding: 0 20px;
    }

    .nav-section small {
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }

    .sidebar-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #e9ecef;
        transition: all 0.3s;
    }

    .sidebar-footer:hover {
        background-color: #f0f2f5;
    }
</style>

<script>
    // Sidebar Toggle for Mobile
    document.addEventListener('DOMContentLoaded', function () {
        const sidebarToggle = document.getElementById('sidebarToggleMobile');
        const sidebar = document.getElementById('sidebarMenu');

        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', function () {
                sidebar.classList.toggle('show');

                // Add backdrop for mobile
                if (window.innerWidth < 992) {
                    if (sidebar.classList.contains('show')) {
                        const backdrop = document.createElement('div');
                        backdrop.className = 'sidebar-backdrop';
                        backdrop.style.transition = 'opacity 0.3s';
                        backdrop.style.opacity = '0';

                        backdrop.addEventListener('click', function () {
                            sidebar.classList.remove('show');
                            setTimeout(() => {
                                this.style.opacity = '0';
                                setTimeout(() => {
                                    if (this.parentNode) {
                                        this.parentNode.removeChild(this);
                                    }
                                }, 300);
                            }, 10);
                        });

                        document.body.appendChild(backdrop);
                        setTimeout(() => {
                            backdrop.style.opacity = '1';
                        }, 10);
                    } else {
                        const backdrop = document.querySelector('.sidebar-backdrop');
                        if (backdrop) {
                            backdrop.style.opacity = '0';
                            setTimeout(() => {
                                if (backdrop.parentNode) {
                                    backdrop.parentNode.removeChild(backdrop);
                                }
                            }, 300);
                        }
                    }
                }
            });
        }

        // Auto-close sidebar on mobile when clicking a link
        const navLinks = document.querySelectorAll('.sidebar .nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function () {
                if (window.innerWidth < 992) {
                    const sidebar = document.getElementById('sidebarMenu');
                    const backdrop = document.querySelector('.sidebar-backdrop');

                    if (sidebar) sidebar.classList.remove('show');
                    if (backdrop) {
                        backdrop.style.opacity = '0';
                        setTimeout(() => {
                            if (backdrop.parentNode) {
                                backdrop.parentNode.removeChild(backdrop);
                            }
                        }, 300);
                    }
                }
            });
        });

        // Handle window resize
        function handleResize() {
            const sidebar = document.getElementById('sidebarMenu');
            const backdrop = document.querySelector('.sidebar-backdrop');

            if (window.innerWidth >= 992) {
                // On desktop, ensure sidebar is visible
                if (sidebar) {
                    sidebar.classList.add('show');
                    sidebar.style.right = '0';
                }
                // Remove backdrop on desktop
                if (backdrop) {
                    backdrop.parentNode.removeChild(backdrop);
                }
            } else {
                // On mobile, ensure sidebar is hidden by default
                if (sidebar) {
                    sidebar.classList.remove('show');
                    sidebar.style.right = '-280px';
                }
                // Remove backdrop on mobile if sidebar is hidden
                if (backdrop && !sidebar.classList.contains('show')) {
                    backdrop.parentNode.removeChild(backdrop);
                }
            }
        }

        // Initial check
        handleResize();

        // Listen for resize
        window.addEventListener('resize', handleResize);
    });
</script>