<aside class="main-sidebar" id="mainSidebar">
    <div class="sidebar-inner">
        {{-- Brand Logo --}}
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            <!-- حل نهائي للأيقونة -->

            <img src="{{ asset('assets/brand/dmsys-icon-tech.svg') }}" alt="DMsys" style="height:42px;width:auto">



            <div class="sidebar-brand-text">
                <div class="sidebar-brand-title">DMsys</div>
                <div class="sidebar-brand-subtitle">Maintenance System</div>
            </div>
        </a>

        {{-- Sidebar Menu --}}
        <nav class="sidebar-menu">
            <ul class="nav flex-column">
                {{-- ===========================
                DASHBOARD
                ============================ --}}
                @if(canView('view dashboard'))
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}"
                            class="nav-link d-flex align-items-center py-3 px-4 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-home me-3"></i>
                            <span>{{ __('Dashboard') }}</span>
                        </a>
                    </li>
                @endif

                {{-- Divider --}}
                <li class="nav-divider">
                    {{ __('Management') }}
                </li>

                {{-- ===========================
                DEVICES SECTION
                ============================ --}}
                @if(canView('view devices') || canView('manage devices'))
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center py-3 px-4" data-bs-toggle="collapse"
                            href="#devicesCollapse" role="button"
                            aria-expanded="{{ request()->routeIs('devices.*') ? 'true' : 'false' }}">
                            <i class="fas fa-tools me-3"></i>
                            <span>{{ __('Devices') }}</span>
                            <i class="fas fa-chevron-down ms-auto transition"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('devices.*') ? 'show' : '' }}" id="devicesCollapse">
                            <ul class="nav flex-column">
                                @if(canView('view devices'))
                                    <li class="nav-item">
                                        <a href="{{ route('devices.index') }}"
                                            class="nav-link d-flex align-items-center py-2 ps-5 {{ request()->routeIs('devices.index') ? 'active' : '' }}">
                                            <i class="fas fa-list me-3 small"></i>
                                            <span>{{ __('All Devices') }}</span>
                                        </a>
                                    </li>
                                @endif

                                @if(canView('manage devices'))
                                    <li class="nav-item">
                                        <a href="{{ route('devices.create') }}"
                                            class="nav-link d-flex align-items-center py-2 ps-5 {{ request()->routeIs('devices.create') ? 'active' : '' }}">
                                            <i class="fas fa-plus-circle me-3 small"></i>
                                            <span>{{ __('Add Device') }}</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{ route('devices.archived') }}"
                                            class="nav-link d-flex align-items-center py-2 ps-5 {{ request()->routeIs('devices.archived') ? 'active' : '' }}">
                                            <i class="fas fa-archive me-3 small"></i>
                                            <span>{{ __('Archived Devices') }}</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- ===========================
                PROJECTS
                ============================ --}}
                @if(canView('view projects'))
                    <li class="nav-item">
                        <a href="{{ route('projects.index') }}"
                            class="nav-link d-flex align-items-center py-3 px-4 {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                            <i class="fas fa-project-diagram me-3"></i>
                            <span>{{ __('Projects') }}</span>
                        </a>
                    </li>
                @endif

                {{-- Divider --}}
                <li class="nav-divider">
                    {{ __('Maintenance') }}
                </li>

                {{-- ===========================
                PREVENTIVE MAINTENANCE
                ============================ --}}
                @if(canView('view pm'))
                    <li class="nav-item">
                        <a href="{{ route('pm.plans.index') }}"
                            class="nav-link d-flex align-items-center py-3 px-4 {{ request()->routeIs('pm.*') ? 'active' : '' }}">
                            <i class="fas fa-wrench me-3"></i>
                            <span>{{ __('Preventive Maintenance') }}</span>
                        </a>
                    </li>
                @endif

                {{-- ===========================
                BREAKDOWNS
                ============================ --}}
                @if(canView('view breakdowns'))
                    <li class="nav-item">
                        <a href="{{ route('breakdowns.index') }}"
                            class="nav-link d-flex align-items-center py-3 px-4 {{ request()->routeIs('breakdowns.*') ? 'active' : '' }}">
                            <i class="fas fa-bolt me-3"></i>
                            <span>{{ __('Breakdown Requests') }}</span>
                        </a>
                    </li>
                @endif

                {{-- ===========================
                SPARE PARTS
                ============================ --}}
                @if(canView('view spare parts'))
                    <li class="nav-item">
                        <a href="{{ route('spare_parts.index') }}"
                            class="nav-link d-flex align-items-center py-3 px-4 {{ request()->routeIs('spare_parts.*') ? 'active' : '' }}">
                            <i class="fas fa-cogs me-3"></i>
                            <span>{{ __('Spare Parts') }}</span>
                        </a>
                    </li>
                @endif

                {{-- Divider --}}
                @if(auth()->user()?->hasRole('admin'))
                    <li class="nav-divider">
                        {{ __('Administration') }}
                    </li>
                @endif

                {{-- ===========================
                USERS (ADMIN ONLY)
                ============================ --}}
                @if(auth()->user()?->hasRole('admin'))
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}"
                            class="nav-link d-flex align-items-center py-3 px-4 {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="fas fa-users-cog me-3"></i>
                            <span>{{ __('Users') }}</span>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>

        {{-- Quick Stats Footer --}}
        <div class="sidebar-footer">
            <div class="sidebar-stats">
                <div class="stat-item">
                    <div class="stat-label">Devices</div>
                    <div class="stat-value text-success">
                        <i class="fas fa-circle fa-xs me-1"></i>
                        {{ $totalDevices ?? 0 }}
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Issues</div>
                    <div class="stat-value text-danger">
                        <i class="fas fa-exclamation-circle fa-xs me-1"></i>
                        {{ $openBreakdowns ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>

<style>
    /* ===== إعادة تعيين السايد بار ===== */
    .main-sidebar {
        width: 280px;
        height: 100vh;
        background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        color: white;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1030;
        overflow-y: auto;
        transition: transform 0.3s ease;
        border-right: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-inner {
        display: flex;
        flex-direction: column;
        height: 100%;
        min-height: 100vh;
    }

    /* ===== Brand Logo ===== */
    .sidebar-brand {
        display: flex;
        align-items: center;
        padding: 1.5rem;
        text-decoration: none;
        color: white;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.05);
        transition: all 0.3s ease;
    }

    .sidebar-brand:hover {
        background: rgba(255, 255, 255, 0.08);
    }

    .brand-logo-wrapper {
        width: 40px;
        height: 40px;
        margin-right: 1rem;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sidebar-brand-logo {
        width: 100%;
        height: 100%;
        object-fit: contain;
        filter: brightness(0) invert(1);
        display: block;
    }

    .brand-fallback {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
    }

    .sidebar-brand-text {
        flex: 1;
    }

    .sidebar-brand-title {
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1.2;
        color: white;
        margin-bottom: 0.125rem;
    }

    .sidebar-brand-subtitle {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 400;
    }

    /* ===== Sidebar Menu ===== */
    .sidebar-menu {
        flex: 1;
        padding: 1rem 0;
        overflow-y: auto;
    }

    .nav {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .nav-item {
        margin: 0.125rem 0;
    }

    .nav-link {
        display: flex !important;
        align-items: center !important;
        padding: 0.75rem 1rem !important;
        color: rgba(255, 255, 255, 0.8) !important;
        text-decoration: none !important;
        border-radius: 0.5rem !important;
        margin: 0.125rem 0.5rem !important;
        transition: all 0.2s ease !important;
        border: none !important;
        background: transparent !important;
    }

    .nav-link:hover {
        color: white !important;
        background: rgba(255, 255, 255, 0.1) !important;
    }

    .nav-link.active {
        color: white !important;
        background: rgba(59, 130, 246, 0.2) !important;
        border-left: 3px solid #3b82f6 !important;
        font-weight: 500 !important;
    }

    .nav-link i {
        width: 20px;
        text-align: center;
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.6);
    }

    .nav-link.active i,
    .nav-link:hover i {
        color: white;
    }

    /* ===== Submenu ===== */
    .collapse .nav {
        margin-top: 0.25rem;
        margin-bottom: 0.25rem;
    }

    .collapse .nav-link {
        padding-left: 3.5rem !important;
        font-size: 0.875rem;
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
    }

    .collapse .nav-link i {
        font-size: 0.75rem;
    }

    /* ===== Divider ===== */
    .nav-divider {
        padding: 0.75rem 1rem;
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 0.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.05);
    }

    .nav-divider:first-of-type {
        margin-top: 0;
        border-top: none;
    }

    /* ===== Footer Stats ===== */
    .sidebar-footer {
        padding: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(0, 0, 0, 0.2);
        margin-top: auto;
    }

    .sidebar-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        text-align: center;
    }

    .stat-item {
        padding: 0.5rem;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 0.5rem;
        transition: all 0.2s;
    }

    .stat-item:hover {
        background: rgba(255, 255, 255, 0.08);
    }

    .stat-label {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.6);
        margin-bottom: 0.25rem;
    }

    .stat-value {
        font-size: 1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.25rem;
    }

    .stat-value i {
        font-size: 0.5rem;
    }

    /* ===== Scrollbar ===== */
    .sidebar-menu::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar-menu::-webkit-scrollbar-track {
        background: transparent;
    }

    .sidebar-menu::-webkit-scrollbar-thumb {
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 20px;
    }

    /* ===== للجوال ===== */
    @media (max-width: 991.98px) {
        .main-sidebar {
            transform: translateX(-100%);
            box-shadow: 5px 0 25px rgba(0, 0, 0, 0.3);
        }

        .main-sidebar.mobile-open {
            transform: translateX(0);
        }
    }

    /* ===== إصلاحات Bootstrap ===== */
    .nav-link:focus {
        outline: none;
        box-shadow: none;
    }

    .collapse.show {
        display: block;
    }
</style>