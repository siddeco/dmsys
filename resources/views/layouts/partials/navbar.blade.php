<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

       <!-- Language Switch -->
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button"
       data-bs-toggle="dropdown" aria-expanded="false">
        🌐 {{ strtoupper(app()->getLocale()) }}
    </a>

    <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">English</a></li>
        <li><a class="dropdown-item" href="{{ route('lang.switch', 'ar') }}">العربية</a></li>
    </ul>
</li>


        <!-- Logout -->
        <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-danger btn-sm ml-3">{{ __('Logout') }}</button>
            </form>
        </li>

    </ul>

</nav>
