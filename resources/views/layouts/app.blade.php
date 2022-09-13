<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{asset('css/app.min.css')}}">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/components.css')}}">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="{{asset('css/custom.css')}}">
    <link rel='shortcut icon' type='image/x-icon' href="{{asset('img/favicon.ico')}}" />

    @stack('css')

</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar sticky">
                <div class="form-inline mr-auto">

                </div>
                <ul class="navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            <img alt="image" src="{{asset('img/users/avatar_default.png')}}" class="user-img-radious-style"> <span class="d-sm-none d-lg-inline-block"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right pullDown">
                            <div class="dropdown-title">Hola {{auth()->user()->name}}</div>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" class="dropdown-item has-icon text-danger"> <i class="fas fa-sign-out-alt"></i>
                                Salir
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            <div class="main-sidebar sidebar-style-2">
                <aside id="sidebar-wrapper">
                    <div class="sidebar-brand">
                        <a href="{{route('cars.index')}}"> 
                            <img alt="image" src="{{asset('img/logo.png')}}" class="header-logo" /> <span class="logo-name">SEMOV</span>
                        </a>
                    </div>
                    <ul class="sidebar-menu">
                        <li class="menu-header">Main</li>
                        <li class="dropdown active">
                            <a href="{{route('cars.index')}}" class="nav-link"><i class="fas fa-car"></i><span>Vehiculos</span></a>
                        </li>                       
                    </ul>
                </aside>
            </div>
            <!-- Main Content -->
            <div class="main-content">
                @yield('content')
            </div>
            <footer class="main-footer">
                <div class="footer-left">
                    <a href="#">&copy; {{date('Y')}} Todos los derechos reservados</a></a>
                </div>
                <div class="footer-right">
                    {{date('Y')}}
                </div>
            </footer>
        </div>
    </div>
    <!-- General JS Scripts -->
    <script src="{{asset('js/app.min.js')}}"></script>
    <!-- JS Libraies -->
    <script src="{{asset('bundles/apexcharts/apexcharts.min.js')}}"></script>
    <!-- Page Specific JS File -->
    <script src="{{asset('js/page/index.js')}}"></script>
    <!-- Template JS File -->
    <script src="{{asset('js/scripts.js')}}"></script>
    <!-- Custom JS File -->
    <script src="{{asset('js/custom.js')}}"></script>

    @stack('js')

</body>

</html>