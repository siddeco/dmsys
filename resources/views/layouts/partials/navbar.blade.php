<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    {{-- LEFT --}}
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    {{-- RIGHT --}}
    <ul class="navbar-nav ml-auto">

        {{-- 🌍 LANGUAGE --}}
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-language"></i>
                <span class="ml-1 text-uppercase">
                    {{ app()->getLocale() }}
                </span>
            </a>

            <div class="dropdown-menu dropdown-menu-right">
                <a href="{{ url('locale/en') }}" class="dropdown-item">
                    🇺🇸 English
                </a>
                <a href="{{ url('locale/ar') }}" class="dropdown-item">
                    🇸🇦 العربية
                </a>
            </div>
        </li>

        {{-- 🔔 Notifications --}}
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-danger navbar-badge">0</span>
            </a>

            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg">
                <span class="dropdown-header">Notifications</span>
                <div class="dropdown-divider"></div>
                <span class="dropdown-item text-muted">
                    No notifications
                </span>
            </div>
        </li>

        {{-- 💬 Messages --}}
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-comments"></i>
                <span class="badge badge-warning navbar-badge">0</span>
            </a>

            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg">
                <span class="dropdown-header">Messages</span>
                <div class="dropdown-divider"></div>
                <span class="dropdown-item text-muted">
                    No messages
                </span>
            </div>
        </li>

        {{-- 👤 USER --}}
        <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle"
       href="#"
       id="userDropdown"
       role="button"
       data-bs-toggle="dropdown"
       aria-expanded="false">

        <i class="fas fa-user-circle"></i>
        {{ auth()->user()->name }}

    </a>

    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">

        <li class="dropdown-header text-center">
            <strong>{{ auth()->user()->name }}</strong><br>
            <small>{{ auth()->user()->email }}</small>
        </li>

        <li><hr class="dropdown-divider"></li>

        <li>
            <a class="dropdown-item" href="#">
                <i class="fas fa-user me-2"></i> Profile
            </a>
        </li>

        <li>
            <a class="dropdown-item" href="#">
                <i class="fas fa-cog me-2"></i> Settings
            </a>
        </li>

        <li><hr class="dropdown-divider"></li>

        <li>
            <a class="dropdown-item text-danger"
               href="#"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>

            <form id="logout-form"
                  action="{{ route('logout') }}"
                  method="POST"
                  class="d-none">
                @csrf
            </form>
        </li>

    </ul>
</li>

    </ul>
</nav>
