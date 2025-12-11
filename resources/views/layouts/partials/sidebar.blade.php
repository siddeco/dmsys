<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <span class="brand-text font-weight-light">{{ __('Maintenance System') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">


                {{-- ===========================
                     Dashboard
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
                     Devices
                ============================ --}}
                @can('view devices')
                <li class="nav-item">
                    <a href="{{ route('devices.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-tools"></i>
                        <p>{{ __('Devices') }}</p>
                    </a>
                </li>
                @endcan

                @can('create devices')
                <li class="nav-item">
                    <a href="{{ route('devices.create') }}" class="nav-link">
                        <i class="nav-icon fas fa-plus-circle"></i>
                        <p>{{ __('Add Device') }}</p>
                    </a>
                </li>
                @endcan


                {{-- ===========================
                     Modules Header
                ============================ --}}
                <li class="nav-header">{{ __('Modules') }}</li>


                {{-- ===========================
                     Projects
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
                     PM Plans
                ============================ --}}
                @can('view pm plans')
                <li class="nav-item">
                    <a href="{{ route('pm.plans.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-wrench"></i>
                        <p>{{ __('Preventive Maintenance (PM)') }}</p>
                    </a>
                </li>
                @endcan


                {{-- ===========================
                     Breakdowns
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
                     Spare Parts
                ============================ --}}
                @can('view spare parts')
                <li class="nav-item">
                    <a href="{{ route('spare_parts.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>{{ __('Spare Parts') }}</p>
                    </a>
                </li>
                @endcan


                {{-- ===========================
                     User Management
                ============================ --}}
                @can('view users')
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
