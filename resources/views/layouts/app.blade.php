<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'DMsys'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS الرئيسي -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <link rel="icon" href="{{ asset('assets/brand/dmsys-icon-tech.svg') }}" type="image/svg+xml">

    @stack('styles')
</head>
<script>
    // إصلاح فوري للتصميم المنكسر
    document.addEventListener('DOMContentLoaded', function () {
        // إعادة تعيين الأنماط المكسورة
        const styleFix = document.createElement('style');
        styleFix.innerHTML = `
            /* إصلاحات فورية */
            body { overflow-x: hidden !important; }
            #app { display: flex !important; min-height: 100vh !important; }
            .main-content { flex: 1 !important; }
            .content-wrapper { flex: 1 !important; }
            
            /* إصلاح التداخل */
            * { box-sizing: border-box !important; }
            .row { display: flex !important; flex-wrap: wrap !important; }
            
            /* منع الانهيار */
            .container-fluid { width: 100% !important; }
            .dashboard-container { width: 100% !important; overflow: hidden !important; }
            
            /* إصلاح الشاشات الصغيرة */
            @media (max-width: 768px) {
                .main-navbar { position: fixed !important; top: 0 !important; }
                .content-wrapper { padding-top: 70px !important; }
            }
        `;
        document.head.appendChild(styleFix);

        // إعادة تحميل التصميم بعد ثانية
        setTimeout(() => {
            document.body.classList.add('layout-fixed');
        }, 100);
    });
</script>

<body>
    <div id="app">
        <!-- Overlay للجوال -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- السايد بار -->
        @include('layouts.partials.sidebar')

        <!-- المحتوى الرئيسي -->
        <div class="main-content">
            <!-- الناف بار -->
            @include('layouts.partials.navbar')

            <!-- المحتوى الفعلي -->
            <div class="content-wrapper">
                <!-- رسائل التنبيه -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- محتوى الصفحة -->
                @yield('content')
            </div>

            <!-- الفوتر -->
            <footer class="footer mt-auto py-3 bg-light border-top">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-md-6 text-muted">
                            &copy; {{ date('Y') }} {{ config('app.name', 'DMsys') }}
                        </div>
                        <div class="col-md-6 text-end text-muted">
                            v{{ config('app.version', '1.0') }}
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript الرئيسي -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Scripts إضافية -->
    <script>
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

            // إصلاح القوائم المنسدلة على الجوال
            document.addEventListener('click', function (event) {
                if (window.innerWidth < 992) {
                    if (!event.target.closest('.dropdown')) {
                        document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                            menu.classList.remove('show');
                        });
                    }
                }
            });

            // إصلاح الجداول على الجوال
            function fixMobileTables() {
                const tables = document.querySelectorAll('.table-responsive');
                tables.forEach(table => {
                    if (window.innerWidth < 768) {
                        table.style.overflowX = 'auto';
                        table.style.WebkitOverflowScrolling = 'touch';

                        const innerTable = table.querySelector('table');
                        if (innerTable) {
                            innerTable.style.minWidth = '600px';
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

            fixMobileTables();
            window.addEventListener('resize', fixMobileTables);
        });
    </script>

    @stack('scripts')
</body>

</html>