<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Football Scout Pro') }} - Dashboard</title>

    <!-- Bootstrap CSS from CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
<!-- Bootstrap JS from CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>

</head>
<body>
    <div id="app">
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                    <div class="position-sticky pt-3">
                        <a class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-decoration-none" href="{{ route('home') }}">
                            <img src="{{ asset('images/logo.jpg') }}" alt="Football Scout Pro Logo" height="30" class="me-2">
                            <span class="fs-4">Football Scout Pro</span>
                        </a>
                        <hr>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('players*') ? 'active' : '' }}" href="{{ route('players.index') }}">
                                    <i class="bi bi-person"></i> Players
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('teams*') ? 'active' : '' }}" href="{{ route('teams.index') }}">
                                    <i class="bi bi-people"></i> Teams
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('matches*') ? 'active' : '' }}" href="{{ route('matches.index') }}">
                                    <i class="bi bi-calendar-event"></i> Fixtures
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('stats*') ? 'active' : '' }}" href="{{ route('stats.index') }}">
                                    <i class="bi bi-graph-up"></i> Stats
                                </a>
                            </li>
                            <hr>
                            @if(auth()->user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-gear"></i> Settings
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('logout') }}" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right"></i> {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Main content -->
                <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">@yield('dashboard-title', 'Dashboard')</h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            @yield('dashboard-actions')
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @yield('dashboard-content')
                </div>
            </div>
        </div>

        <!-- AJAX Scripts -->
        @yield('scripts')
    </div>
</body>
</html>