<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm fixed-top" style="height: 70px;">
    <div class="container-fluid px-3 px-lg-4">
        <!-- Brand & Toggle -->
        <div class="d-flex align-items-center">
            <button class="btn sidebar-toggle me-3 d-lg-none" type="button" id="sidebarToggleMobile">
                <i class="fas fa-bars fa-lg"></i>
            </button>

            <a class="navbar-brand d-none d-lg-flex align-items-center" href="{{ route('dashboard') }}">
                <div class="logo-icon bg-primary rounded-circle p-2 me-3">
                    <i class="fas fa-stethoscope text-white fa-lg"></i>
                </div>
                <div>
                    <span class="fw-bold fs-5 text-dark">MediCare</span>
                    <small class="d-block text-muted" style="font-size: 0.75rem;">Medical Device Management</small>
                </div>
            </a>

            <!-- Logo for Mobile -->
            <a class="navbar-brand d-lg-none d-flex align-items-center" href="{{ route('dashboard') }}">
                <div class="logo-icon bg-primary rounded-circle p-2 me-2">
                    <i class="fas fa-stethoscope text-white fa-lg"></i>
                </div>
                <span class="fw-bold fs-5 text-dark">MediCare</span>
            </a>
        </div>

        <!-- Search Desktop -->
        <div class="navbar-search d-none d-lg-flex mx-auto" style="max-width: 450px;">
            <div class="input-group">
                <input type="text" class="form-control border-end-0 rounded-start"
                    placeholder="ابحث عن جهاز، عطل، تقرير..." aria-label="Search" aria-describedby="search-button">
                <button class="btn btn-outline-secondary border-start-0 rounded-end" type="button" id="search-button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        <!-- Right Section -->
        <div class="d-flex align-items-center">
            <!-- Language Selector -->
            <div class="dropdown me-2 me-lg-3">
                <button class="btn btn-light dropdown-toggle d-flex align-items-center py-2 px-3" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-globe me-2"></i>
                    <span class="text-uppercase">{{ app()->getLocale() }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">English</a></li>
                    <li><a class="dropdown-item" href="{{ route('lang.switch', 'ar') }}">العربية</a></li>
                </ul>
            </div>

            <!-- Notifications -->
            <div class="dropdown me-2 me-lg-3">
                <button class="btn btn-light position-relative p-2" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="far fa-bell fa-lg"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                        style="font-size: 0.6rem; padding: 0.2em 0.5em;">
                        3
                    </span>
                </button>
                <div class="dropdown-menu dropdown-menu-end p-0" style="min-width: 320px;">
                    <div class="dropdown-header bg-light py-3 px-4">
                        <h6 class="mb-0 d-flex align-items-center">
                            <i class="fas fa-bell me-2"></i>
                            الإشعارات
                            <span class="badge bg-primary ms-2">3</span>
                        </h6>
                    </div>
                    <div class="dropdown-list" style="max-height: 300px; overflow-y: auto;">
                        <a href="#" class="dropdown-item py-3 px-4 border-bottom">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-warning bg-opacity-10 p-2">
                                        <i class="fas fa-tools text-warning"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-0 fw-medium">جهاز يحتاج صيانة</p>
                                    <small class="text-muted">جهاز رقم #123 - منذ ٢ ساعة</small>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="dropdown-item py-3 px-4 border-bottom">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-danger bg-opacity-10 p-2">
                                        <i class="fas fa-exclamation-triangle text-danger"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-0 fw-medium">عطل جديد تم الإبلاغ عنه</p>
                                    <small class="text-muted">منذ ٤ ساعات</small>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="dropdown-item py-3 px-4">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-success bg-opacity-10 p-2">
                                        <i class="fas fa-check-circle text-success"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-0 fw-medium">تم إكمال صيانة جهاز</p>
                                    <small class="text-muted">جهاز رقم #456 - منذ يوم</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="dropdown-footer text-center py-3 bg-light">
                        <a href="#" class="text-decoration-none">
                            <i class="fas fa-list me-1"></i>
                            عرض جميع الإشعارات
                        </a>
                    </div>
                </div>
            </div>

            <!-- User Profile -->
            <div class="dropdown">
                <button class="btn d-flex align-items-center p-0" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <div class="avatar me-2">
                        <div class="avatar-initials bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 38px; height: 38px; font-size: 1rem;">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="user-info d-none d-lg-block text-start">
                        <div class="fw-medium" style="font-size: 0.9rem;">{{ Auth::user()->name }}</div>
                        <small class="text-muted" style="font-size: 0.75rem;">
                            {{ Auth::user()->role->name ?? 'User' }}
                        </small>
                    </div>
                    <i class="fas fa-chevron-down ms-2 d-none d-lg-block"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li>
                        <div class="dropdown-header py-3 px-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <div class="avatar-initials bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 48px; height: 48px; font-size: 1.2rem;">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-0" style="font-size: 1rem;">{{ Auth::user()->name }}</h6>
                                    <small class="text-muted"
                                        style="font-size: 0.8rem;">{{ Auth::user()->email }}</small>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <hr class="dropdown-divider my-2">
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user-cog me-2"></i>
                            الملف الشخصي
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-cog me-2"></i>
                            الإعدادات
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider my-2">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                تسجيل الخروج
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- Search for Mobile -->
<div class="d-lg-none bg-white border-bottom py-2 px-3">
    <div class="input-group">
        <input type="text" class="form-control border-end-0 rounded-start" placeholder="ابحث عن جهاز، عطل، تقرير...">
        <button class="btn btn-outline-secondary border-start-0 rounded-end" type="button">
            <i class="fas fa-search"></i>
        </button>
    </div>
</div>

<style>
    /* Navbar Styles */
    .navbar {
        height: 70px;
        z-index: 1030;
    }

    .logo-icon {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .avatar-initials {
        font-weight: 600;
    }

    .navbar-search .input-group {
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #dee2e6;
    }

    .navbar-search input {
        border: none;
        padding: 8px 15px;
        background-color: #f8f9fa;
    }

    .navbar-search input:focus {
        background-color: #fff;
        box-shadow: none;
    }

    .navbar-search button {
        padding: 8px 15px;
        border: none;
        background-color: #f8f9fa;
    }

    .navbar-search button:hover {
        background-color: #e9ecef;
    }

    /* Dropdown Styles */
    .dropdown-menu {
        border: 1px solid rgba(0, 0, 0, .1);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    .dropdown-item {
        padding: 10px 20px;
        transition: all 0.2s ease;
        border-radius: 4px;
        margin: 2px 5px;
        width: calc(100% - 10px);
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
        padding-right: 25px;
    }

    /* Mobile Responsive */
    @media (max-width: 992px) {
        .navbar {
            padding: 5px 0;
            height: 60px;
        }

        .navbar-brand {
            font-size: 1rem;
        }

        .logo-icon {
            width: 36px;
            height: 36px;
        }

        .btn {
            padding: 6px 10px;
        }

        .avatar-initials {
            width: 34px !important;
            height: 34px !important;
            font-size: 0.9rem !important;
        }
    }
</style>

<script>
    // Search functionality
    document.addEventListener('DOMContentLoaded', function () {
        const searchButton = document.getElementById('search-button');
        const searchInput = document.querySelector('.navbar-search input');

        if (searchButton && searchInput) {
            searchButton.addEventListener('click', function () {
                performSearch(searchInput.value);
            });

            searchInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    performSearch(this.value);
                }
            });
        }

        function performSearch(query) {
            if (query.trim() !== '') {
                window.location.href = '/search?q=' + encodeURIComponent(query);
            }
        }
    });
</script>