<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'MediCare System') - نظام إدارة الأجهزة الطبية</title>

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&family=Tajawal:wght@300;400;500;700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        /* حل مشكلة Navbar الثابت */
        body {
            padding-top: 70px !important;
            font-family: 'Cairo', 'Tajawal', sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }

        /* تحسينات للشاشات الصغيرة */
        @media (max-width: 992px) {
            body {
                padding-top: 60px !important;
            }
        }

        /* تحسينات عامة */
        :root {
            --navbar-height: 70px;
            --sidebar-width: 250px;
        }

        /* إصلاح كامل للمشكلة: إزالة تأثيرات الـ container-fluid */
        .container-fluid {
            padding-left: 15px !important;
            padding-right: 15px !important;
            max-width: 100% !important;
            margin: 0 !important;
        }

        /* المحتوى الرئيسي */
        main.col-md-9 {
            width: 100% !important;
            margin-right: 0 !important;
            float: none !important;
            transition: all 0.3s;
        }

        /* على الشاشات الكبيرة، ضبط المحتوى */
        @media (min-width: 992px) {
            main.col-md-9 {
                width: calc(100% - var(--sidebar-width)) !important;
                margin-right: var(--sidebar-width) !important;
                padding-right: 15px !important;
                padding-left: 15px !important;
            }
        }

        /* إصلاح الـ row والـ col */
        .row {
            margin-right: 0 !important;
            margin-left: 0 !important;
        }

        .col-md-9,
        .col-md-3 {
            padding-right: 0 !important;
            padding-left: 0 !important;
        }

        /* تحسينات للعناصر الداخلية */
        .bg-white {
            background-color: white !important;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        /* إصلاحات للهواتف */
        @media (max-width: 768px) {
            .px-md-4 {
                padding-right: 15px !important;
                padding-left: 15px !important;
            }

            .pt-3 {
                padding-top: 1rem !important;
            }
        }

        /* تحسينات للبطاقات والإحصائيات */
        .card {
            border: none !important;
            border-radius: 12px !important;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05) !important;
            transition: transform 0.3s, box-shadow 0.3s !important;
        }

        .card:hover {
            transform: translateY(-5px) !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
        }

        /* إصلاحات للعناوين */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-weight: 600 !important;
            color: #2a4c7d !important;
        }

        /* تحسينات للأزرار */
        .btn {
            border-radius: 8px !important;
            font-weight: 500 !important;
            padding: 8px 20px !important;
        }

        .btn-primary {
            background: linear-gradient(45deg, #2a4c7d, #3a86ff) !important;
            border: none !important;
        }

        /* تحسينات للـ breadcrumb */
        .breadcrumb {
            background: white !important;
            border-radius: 10px !important;
            padding: 15px 20px !important;
            margin-bottom: 25px !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03) !important;
        }

        /* تحسينات للتنبيهات */
        .alert {
            border-radius: 10px !important;
            border: none !important;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05) !important;
            margin-bottom: 20px !important;
        }

        /* إصلاح footer */
        footer {
            background: white !important;
            padding: 20px !important;
            border-radius: 10px !important;
            margin-top: 30px !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03) !important;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-light">
    @include('layouts.navbar')

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar - تم إصلاحه ليصبح fixed -->
            @include('layouts.sidebar')

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-3">
                <!-- Breadcrumb إذا كان موجودًا -->
                @if(isset($breadcrumbs))
                    <nav aria-label="breadcrumb" class="mb-4">
                        <ol class="breadcrumb bg-white p-3 rounded shadow-sm">
                            @foreach($breadcrumbs as $breadcrumb)
                                <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
                                    @if(!$loop->last)
                                        <a href="{{ $breadcrumb['url'] }}" class="text-decoration-none">
                                            <i class="{{ $breadcrumb['icon'] ?? 'fas fa-home' }} me-1"></i>
                                            {{ $breadcrumb['title'] }}
                                        </a>
                                    @else
                                        <i class="{{ $breadcrumb['icon'] ?? 'fas fa-chevron-left' }} me-1"></i>
                                        {{ $breadcrumb['title'] }}
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    </nav>
                @endif

                <!-- الرسائل التنبيهية -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- المحتوى الرئيسي -->
                <div class="content-wrapper bg-white p-4 rounded shadow-sm mb-4">
                    @yield('content')
                </div>

                <!-- Footer داخل المحتوى -->
                <footer class="mt-5 pt-3 border-top text-center text-muted">
                    <small>
                        &copy; {{ date('Y') }} MediCare System. جميع الحقوق محفوظة.
                        <span class="mx-2">|</span>
                        <a href="#" class="text-decoration-none">الشروط والأحكام</a>
                        <span class="mx-2">|</span>
                        <a href="#" class="text-decoration-none">سياسة الخصوصية</a>
                    </small>
                </footer>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarToggle = document.getElementById('sidebarToggleMobile');
            const sidebar = document.getElementById('sidebarMenu');
            const mainContent = document.querySelector('main.col-md-9');

            // Toggle Sidebar on Mobile
            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function () {
                    sidebar.classList.toggle('show');

                    // Add backdrop for mobile
                    if (window.innerWidth < 992) {
                        if (sidebar.classList.contains('show')) {
                            const backdrop = document.createElement('div');
                            backdrop.className = 'sidebar-backdrop';
                            backdrop.style.cssText = `
                                position: fixed;
                                top: 70px;
                                right: 0;
                                bottom: 0;
                                left: 0;
                                background: rgba(0, 0, 0, 0.5);
                                z-index: 1019;
                                transition: opacity 0.3s;
                            `;
                            backdrop.addEventListener('click', function () {
                                sidebar.classList.remove('show');
                                setTimeout(() => this.remove(), 300);
                            });
                            document.body.appendChild(backdrop);
                            setTimeout(() => backdrop.style.opacity = '1', 10);
                        } else {
                            const backdrop = document.querySelector('.sidebar-backdrop');
                            if (backdrop) {
                                backdrop.style.opacity = '0';
                                setTimeout(() => backdrop.remove(), 300);
                            }
                        }
                    }
                });
            }

            // إصلاح عرض المحتوى عند تغيير حجم النافذة
            function adjustContentWidth() {
                const sidebar = document.getElementById('sidebarMenu');
                const main = document.querySelector('main.col-md-9');

                if (window.innerWidth >= 992 && sidebar && main) {
                    if (sidebar.classList.contains('show')) {
                        main.style.width = 'calc(100% - 250px)';
                        main.style.marginRight = '250px';
                    } else {
                        main.style.width = '100%';
                        main.style.marginRight = '0';
                    }
                } else {
                    if (main) {
                        main.style.width = '100%';
                        main.style.marginRight = '0';
                    }
                }
            }

            // Auto dismiss alerts after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Handle window resize
            function handleResize() {
                const sidebar = document.getElementById('sidebarMenu');
                const backdrop = document.querySelector('.sidebar-backdrop');

                if (window.innerWidth >= 992) {
                    // On desktop, ensure sidebar is visible
                    if (sidebar) sidebar.classList.add('show');
                    // Remove backdrop on desktop
                    if (backdrop) backdrop.remove();
                    // Adjust content width
                    adjustContentWidth();
                } else {
                    // On mobile, hide sidebar by default
                    if (sidebar) sidebar.classList.remove('show');
                    // Adjust content width
                    adjustContentWidth();
                }
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
                            setTimeout(() => backdrop.remove(), 300);
                        }
                    }
                });
            });

            // Initial adjustments
            handleResize();
            adjustContentWidth();

            // Listen for resize
            window.addEventListener('resize', function () {
                handleResize();
                adjustContentWidth();
            });

            // تحسين تجربة المستخدم على الهواتف
            if (window.innerWidth < 992) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.add('dropdown-menu-end');
                });
            }
        });
    </script>

    @stack('scripts')
</body>

</html>