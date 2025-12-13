<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <span class="brand-text font-weight-light">
            {{ __('Maintenance System') }}
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">

                {{-- ===========================
                     DASHBOARD
                ============================ --}}
                @can('view dashboard')
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>{{ __('Dashboard') }}</p>
                    </a>
                </li>
                @endcan


                {{-- ===========================
                     DEVICES (Admin / Engineer)
                ============================ --}}
                @can('view devices')
                <li class="nav-item">
                    <a href="{{ route('devices.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-tools"></i>
                        <p>{{ __('Devices') }}</p>
                    </a>
                </li>
                @endcan

                @can('manage devices')
                <li class="nav-item">
                    <a href="{{ route('devices.create') }}" class="nav-link">
                        <i class="nav-icon fas fa-plus-circle"></i>
                        <p>{{ __('Add Device') }}</p>
                    </a>
                </li>
                @endcan


                {{-- ===========================
                     MODULES HEADER
                ============================ --}}
                <li class="nav-header">{{ __('Modules') }}</li>


                {{-- ===========================
                     PROJECTS (Admin / Engineer)
                ============================ --}}
                @can('view projects')
                <li class="nav-item">
                    <a href="{{ route('projects.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-project-diagram"></i>
                        <p>{{ __('Projects') }}</p>
                    </a>
                </li>
                @endcan


                {{-- ===========================
                     PM (ALL USERS)
                ============================ --}}
                @can('view pm')
                <li class="nav-item">
                    <a href="{{ route('pm.plans.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-wrench"></i>
                        <p>{{ __('Preventive Maintenance') }}</p>
                    </a>
                </li>
                @endcan


                {{-- ===========================
                     BREAKDOWNS
                ============================ --}}
                @can('view breakdowns')
                <li class="nav-item">
                    <a href="{{ route('breakdowns.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-bolt"></i>
                        <p>{{ __('Breakdown Requests') }}</p>
                    </a>
                </li>
                @endcan


                {{-- ===========================
                     SPARE PARTS (Admin / Engineer)
                ============================ --}}
                @can('view spare parts')
                <li class="nav-item">
                    <a href="{{ route('spare_parts.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>{{ __('Spare Parts') }}</p>
                    </a>
                </li>
                @endcan


                {{-- ===========================
                     USERS (Admin ONLY)
                ============================ --}}
                @can('manage users')
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>{{ __('Users') }}</p>
                    </a>
                </li>
                @endcan

            </ul>
        </nav>

    </div>

</aside>
