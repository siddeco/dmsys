<nav class="main-navbar navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container-fluid px-3">
        {{-- LEFT SECTION --}}
        <div class="d-flex align-items-center">
            {{-- زر تبديل السايد بار --}}
            <button class="btn sidebar-toggle me-3" type="button" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>

            {{-- اللوجو للجوال --}}
            <a class="navbar-brand d-lg-none" href="{{ route('dashboard') }}">
                @php
                    $iconExists = file_exists(public_path('assets/brand/dmsys-icon-tech.svg'));
                @endphp

                @if($iconExists)
                    <img src="{{ asset('assets/brand/dmsys-icon-tech.svg') }}" alt="DMsys" height="32" class="navbar-logo">
                @else
                    <div class="navbar-brand-fallback d-flex align-items-center">
                        <div class="logo-fallback me-2 d-flex align-items-center justify-content-center"
                            style="width: 32px; height: 32px; background: #3b82f6; border-radius: 6px;">
                            <i class="fas fa-tools text-white"></i>
                        </div>
                        <span class="fw-bold text-dark">DMsys</span>
                    </div>
                @endif
            </a>
        </div>

        {{-- SEARCH (Desktop) --}}
        <div class="navbar-search d-none d-lg-flex mx-auto" style="max-width: 400px;">
            <div class="input-group input-group-sm">
                <input type="text" class="form-control" placeholder="Search..."
                    style="border-radius: 6px 0 0 6px; border-right: none;">
                <button class="btn btn-outline-secondary" type="button"
                    style="border-radius: 0 6px 6px 0; border-left: none;">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        {{-- RIGHT SECTION --}}
        {{-- زر القائمة للجوال --}}
        <button class="navbar-toggler d-lg-none ms-auto" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
            aria-label="Toggle navigation">
            <i class="fas fa-ellipsis-v"></i>
        </button>

        {{-- عناصر سطح المكتب --}}
        <div class="d-none d-lg-flex align-items-center ms-auto">
            {{-- 🌍 Language --}}
            <div class="nav-item dropdown me-3">
                <a class="nav-link d-flex align-items-center p-0" href="#" id="languageDropdown" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-globe me-2" style="color: #4b5563; font-size: 1.25rem;"></i>
                    <span class="text-uppercase fw-medium">{{ app()->getLocale() }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="languageDropdown">
                    <li>
                        <a class="dropdown-item d-flex align-items-center py-2" href="{{ url('locale/en') }}">
                            <span class="flag-icon flag-icon-us me-2"></span>
                            English
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center py-2" href="{{ url('locale/ar') }}">
                            <span class="flag-icon flag-icon-sa me-2"></span>
                            العربية
                        </a>
                    </li>
                </ul>
            </div>

            {{-- 🔔 Notifications --}}
            <div class="nav-item dropdown me-3">
                <a class="nav-link position-relative p-0" href="#" id="notificationsDropdown" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="far fa-bell fa-lg" style="color: #4b5563; font-size: 1.25rem;"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                        style="font-size: 0.65rem; padding: 0.25em 0.45em; min-width: 18px; height: 18px;">
                        0
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-0" style="min-width: 320px;">
                    <div class="dropdown-header bg-light py-3 px-4 border-bottom">
                        <h6 class="mb-0">
                            <i class="fas fa-bell me-2 text-primary"></i>
                            Notifications
                            <span class="badge bg-primary ms-2">0</span>
                        </h6>
                    </div>
                    <div class="py-4 text-center">
                        <i class="far fa-bell fa-2x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No notifications</p>
                    </div>
                    <div class="dropdown-footer bg-light py-3 px-4 border-top">
                        <a href="#" class="text-decoration-none small">
                            View all notifications <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- 👤 User Profile --}}
            <div class="nav-item dropdown">
                <a class="nav-link d-flex align-items-center p-0" href="#" id="userDropdown" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="avatar-sm me-2 d-flex align-items-center justify-content-center rounded-circle"
                        style="width: 36px; height: 36px; background-color: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-info">
                        <div class="user-name" style="font-weight: 500; color: #1f2937; font-size: 0.9rem;">
                            {{ auth()->user()->name }}
                        </div>
                        <div class="user-role small" style="color: #6b7280; font-size: 0.75rem;">
                            {{ auth()->user()->role->name ?? 'User' }}
                        </div>
                    </div>
                    <i class="fas fa-chevron-down ms-2 small" style="color: #6b7280;"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="min-width: 240px;">
                    <li class="dropdown-header py-3 px-4 bg-light">
                        <div class="d-flex align-items-center">
                            <div class="avatar-md me-3 d-flex align-items-center justify-content-center rounded-circle"
                                style="width: 48px; height: 48px; background-color: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                                <small class="text-muted">{{ auth()->user()->email }}</small>
                            </div>
                        </div>
                    </li>
                    <li>
                        <hr class="dropdown-divider my-2">
                    </li>
                    <li>
                        <a class="dropdown-item py-2 px-4" href="#">
                            <i class="fas fa-user-circle me-3 text-primary"></i>
                            My Profile
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2 px-4" href="#">
                            <i class="fas fa-cog me-3 text-primary"></i>
                            Settings
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider my-2">
                    </li>
                    <li>
                        <a class="dropdown-item py-2 px-4 text-danger" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-3"></i>
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        {{-- قائمة الجوال --}}
        <div class="collapse navbar-collapse d-lg-none" id="navbarCollapse">
            <div class="py-3">
                {{-- SEARCH (Mobile) --}}
                <div class="navbar-search mb-3 px-3">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                {{-- القائمة اليمنى للجوال --}}
                <ul class="navbar-nav">
                    {{-- 🌍 Language --}}
                    <li class="nav-item dropdown mb-2">
                        <a class="nav-link d-flex align-items-center py-2 px-3" href="#" id="languageDropdownMobile"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-globe me-3"></i>
                            <span>Language ({{ app()->getLocale() }})</span>
                            <i class="fas fa-chevron-down ms-auto"></i>
                        </a>
                        <ul class="dropdown-menu border-0 shadow-sm w-100" aria-labelledby="languageDropdownMobile">
                            <li>
                                <a class="dropdown-item d-flex align-items-center py-2" href="{{ url('locale/en') }}">
                                    <span class="flag-icon flag-icon-us me-2"></span>
                                    English
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center py-2" href="{{ url('locale/ar') }}">
                                    <span class="flag-icon flag-icon-sa me-2"></span>
                                    العربية
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- 🔔 Notifications --}}
                    <li class="nav-item dropdown mb-2">
                        <a class="nav-link d-flex align-items-center py-2 px-3" href="#"
                            id="notificationsDropdownMobile" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="far fa-bell me-3"></i>
                            <span>Notifications</span>
                            <span class="badge bg-danger ms-auto">0</span>
                        </a>
                        <div class="dropdown-menu border-0 shadow-sm w-100"
                            aria-labelledby="notificationsDropdownMobile">
                            <div class="py-4 text-center">
                                <i class="far fa-bell fa-2x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No notifications</p>
                            </div>
                        </div>
                    </li>

                    {{-- 👤 User Profile --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link d-flex align-items-center py-2 px-3" href="#" id="userDropdownMobile"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="avatar-sm me-3 d-flex align-items-center justify-content-center rounded-circle"
                                style="width: 36px; height: 36px; background-color: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <div class="user-name">{{ auth()->user()->name }}</div>
                                <small class="text-muted">{{ auth()->user()->email }}</small>
                            </div>
                            <i class="fas fa-chevron-down ms-auto"></i>
                        </a>
                        <ul class="dropdown-menu border-0 shadow-sm w-100" aria-labelledby="userDropdownMobile">
                            <li>
                                <a class="dropdown-item py-2" href="#">
                                    <i class="fas fa-user-circle me-3 text-primary"></i>
                                    My Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="#">
                                    <i class="fas fa-cog me-3 text-primary"></i>
                                    Settings
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item py-2 text-danger" href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                                    <i class="fas fa-sign-out-alt me-3"></i>
                                    Logout
                                </a>
                                <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<style>
    /* ===== أنماط الناف بار الأساسية ===== */
    .main-navbar {
        height: 64px;
        background: white;
        border-bottom: 1px solid #e5e7eb;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 1030;
        width: 100%;
    }

    /* ===== زر تبديل السايد بار ===== */
    .sidebar-toggle {
        background: transparent;
        border: none;
        color: #4b5563;
        font-size: 1.25rem;
        padding: 0.5rem;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .sidebar-toggle:hover {
        background-color: #f3f4f6;
        color: #1f2937;
    }

    /* ===== زر القائمة للجوال ===== */
    .navbar-toggler {
        border: none;
        padding: 0.5rem;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4b5563;
        font-size: 1.25rem;
        background: transparent;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .navbar-toggler:hover {
        background-color: #f3f4f6;
        color: #1f2937;
    }

    /* ===== قائمة الجوال ===== */
    #navbarCollapse {
        background: white;
        border-top: 1px solid #e5e7eb;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* ===== علم الدول ===== */
    .flag-icon-us,
    .flag-icon-sa {
        width: 20px;
        height: 15px;
        display: inline-block;
        background-size: contain;
        background-repeat: no-repeat;
    }

    .flag-icon-us {
        background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAzNiAyNCI+PHBhdGggZmlsbD0iIzAyNDU4OCIgZD0iTTAgMGgzNnYyNEgweiIvPjxwYXRoIGZpbGw9IiNmZmYiIGQ9Ik0wIDBoMzZ2M0gweiIvPjxwYXRoIGZpbGw9IiNmZmYiIGQ9Ik0wIDNoMzZ2M0gweiIvPjxwYXRoIGZpbGw9IiNmZmYiIGQ9Ik0wIDloMzZ2M0gweiIvPjxwYXRoIGZpbGw9IiNmZmYiIGQ9Ik0wIDE1aDM2djNIMHoiLz48cGF0aCBmaWxsPSIjZmZmIiBkPSJNMCAyMWgzNnYzSDB6Ii8+PHBhdGggZmlsbD0iI0M2MjMxOSIgZD0iTTAgMGgxOHYxM0gweiIvPjwvc3ZnPg==');
    }

    .flag-icon-sa {
        background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAzNiAyNCI+PHBhdGggZmlsbD0iIzAwNTAwMCIgZD0iTTAgMGgzNnYyNEgweiIvPjxwYXRoIGZpbGw9IiNmZmYiIGQ9Ik0wIDBoMzZ2MjRIMHoiIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIi8+PHBhdGggZmlsbD0iIzAwNTAwMCIgZD0iTTAgMGgxMHYyNEgweiIvPjwvc3ZnPg==');
    }

    /* ===== للجوال ===== */
    @media (max-width: 991.98px) {
        .main-navbar .container-fluid {
            padding: 0 1rem !important;
        }

        .d-lg-none {
            display: flex !important;
        }

        .d-lg-flex {
            display: none !important;
        }
    }

    /* ===== للشاشات الكبيرة ===== */
    @media (min-width: 992px) {
        .d-lg-none {
            display: none !important;
        }

        #navbarCollapse {
            display: none !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // إدارة القوائم المنسدلة على الجوال
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

                        // تبديل هذه القائمة
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

        // إغلاق قائمة النافبار عند النقر على رابط
        const mobileNavLinks = document.querySelectorAll('#navbarCollapse .nav-link');
        mobileNavLinks.forEach(link => {
            link.addEventListener('click', function () {
                const navbarCollapse = document.getElementById('navbarCollapse');
                if (navbarCollapse && navbarCollapse.classList.contains('show')) {
                    const bsCollapse = new bootstrap.Collapse(navbarCollapse);
                    bsCollapse.hide();
                }
            });
        });
    });
</script>