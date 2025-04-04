<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Football Scout Pro') }} - @yield('title', 'Player Performance Tracker')</title>

    <!-- Fonts -->
    <!-- Bootstrap CSS from CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
    /* Fix for pagination SVG paths appearing without container */
    svg {
        display: inline-block;
        width: 1em;
        height: 1em;
        vertical-align: -0.125em;
    }
    
    /* Hide any SVG paths that might be rendered outside SVG elements */
    body > path {
        display: none !important;
    }
</style>

    

    
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm sticky-top">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Football Scout Pro Logo" height="30" class="me-2">
                    {{ config('app.name', 'Football Scout Pro') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('players*') ? 'active' : '' }}" href="{{ route('players.index') }}">Players</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('teams*') ? 'active' : '' }}" href="{{ route('teams.index') }}">Teams</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('matches*') ? 'active' : '' }}" href="{{ route('matches.index') }}">Fixtures</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('stats*') ? 'active' : '' }}" href="{{ route('stats.index') }}">Stats</a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                        @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('dashboard') }}">
                                        Dashboard
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @if (session('success'))
                <div class="container">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="container">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="bg-dark text-white py-4 mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <h5>Football Scout Pro</h5>
                        <p>The ultimate player performance tracking platform for football scouts, coaches, and enthusiasts.</p>
                    </div>
                    <div class="col-md-4">
                        <h5>Quick Links</h5>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('home') }}" class="text-white">Home</a></li>
                            <li><a href="{{ route('players.index') }}" class="text-white">Players</a></li>
                            <li><a href="{{ route('teams.index') }}" class="text-white">Teams</a></li>
                            <li><a href="{{ route('matches.index') }}" class="text-white">Fixtures</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h5>Contact</h5>
                        <address>
                            <strong>Football Scout Pro</strong><br>
                            123 Stadium Avenue<br>
                            Sportsville, SP 12345<br>
                            <i class="bi bi-envelope"></i> info@footballscoutpro.com<br>
                            <i class="bi bi-telephone"></i> +1 (123) 456-7890
                        </address>
                    </div>
                </div>
                <hr>
                <div class="text-center">
                    <p>&copy; {{ date('Y') }} Football Scout Pro. All rights reserved.</p>
                </div>
            </div>
        </footer>

         <!-- JavaScript Libraries -->
        <!-- jQuery first, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Custom Scripts -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                console.log('DOM fully loaded');
                
                // Handle Select All functionality
                const selectAll = document.getElementById('selectAll');
                const playerCheckboxes = document.querySelectorAll('.player-checkbox');
                const compareBtn = document.getElementById('compareBtn');
                
                console.log('Select All element found:', selectAll !== null);
                console.log('Player checkboxes found:', playerCheckboxes.length);
                console.log('Compare button found:', compareBtn !== null);
                
                if (selectAll) {
                    selectAll.addEventListener('change', function() {
                        playerCheckboxes.forEach(checkbox => {
                            checkbox.checked = selectAll.checked;
                        });
                        updateCompareButton();
                    });
                }
                
                if (playerCheckboxes.length > 0) {
                    playerCheckboxes.forEach(checkbox => {
                        checkbox.addEventListener('change', function() {
                            updateCompareButton();
                            
                            // Update "Select All" checkbox
                            if (selectAll) {
                                const allChecked = Array.from(playerCheckboxes).every(c => c.checked);
                                const someChecked = Array.from(playerCheckboxes).some(c => c.checked);
                                
                                selectAll.checked = allChecked;
                                selectAll.indeterminate = someChecked && !allChecked;
                            }
                        });
                    });
                }
                
                // Handle position and team filters
                const positionSelect = document.getElementById('position');
                const teamSelect = document.getElementById('team');
                
                console.log('Position select found:', positionSelect !== null);
                console.log('Team select found:', teamSelect !== null);
                
                if (positionSelect) {
                    positionSelect.addEventListener('change', function() {
                        console.log('Position changed to:', positionSelect.value);
                        // Find the closest form to this select element
                        const form = positionSelect.closest('form');
                        if (form) {
                            console.log('Found form, submitting');
                            form.submit();
                        } else {
                            console.error('No parent form found for position select');
                        }
                    });
                }
                
                if (teamSelect) {
                    teamSelect.addEventListener('change', function() {
                        console.log('Team changed to:', teamSelect.value);
                        // Find the closest form to this select element
                        const form = teamSelect.closest('form');
                        if (form) {
                            console.log('Found form, submitting');
                            form.submit();
                        } else {
                            console.error('No parent form found for team select');
                        }
                    });
                }
                
                function updateCompareButton() {
                    if (!compareBtn) return;
                    
                    const checkedCount = Array.from(playerCheckboxes).filter(c => c.checked).length;
                    console.log('Checked players count:', checkedCount);
                    
                    compareBtn.disabled = checkedCount < 2;
                    
                    if (checkedCount >= 2) {
                        compareBtn.innerHTML = `<i class="bi bi-bar-chart-fill"></i> Compare (${checkedCount})`;
                    } else {
                        compareBtn.innerHTML = `<i class="bi bi-bar-chart-fill"></i> Compare Selected`;
                    }
                }
            });
        </script>

        <!-- Additional AJAX Scripts -->
        @yield('scripts')
    </div>
</body>
</html>