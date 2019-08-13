<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lexend+Deca&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu&display=swap" rel="stylesheet">
    <style>
        body, html {
            position: relative;
            min-height: 100vh;
        }
        * {
            scroll-behavior: smooth;
        }
        p {
            font-family: 'Ubuntu', sans-serif !important;
        }
        *::selection {
            background: #343A40;
            color: white;
        }
        *::-moz-selection {
            background: #343A40;
            color: white;
        }
        h1,h2,h3,h4,h5,h6 {
            font-family: 'Lexend Deca', sans-serif !important;
        }
        .navbar-brand {
            color: white !important;
            font-weight: 800;
        }
        .pagination li a {
            color: #343A40 !important;
            border: none !important;
        }
        .pagination .active a {
            background-color:  #343A40 !important;
            color: white !important;
        }
        .add-space {
            width: 100%;
            height: 80px;
        }
        .fa {
            color: silver;
        }
        .btn-bulma {
            background-color: #f9f9f9 !important;
            color: #494f54 !important;
            font-weight: 900;
            width: 200px;
        }
        .btn-bulma-full {
            background-color: #343A40 !important;
            color: white !important;
            font-weight: 600;
        }
        .noselect {
            -webkit-touch-callout: none; /* iOS Safari */
            -webkit-user-select: none; /* Safari */
            -moz-user-select: none; /* Firefox */
            -ms-user-select: none; /* Internet Explorer/Edge */
            user-select: none; /* Non-prefixed version, currently supported by Chrome and Opera */
        }
        .max-hr {
            max-width: 300px !important;
            margin: 0 auto;
            margin-bottom: 14px;
        }
        footer {
            position: absolute;
            bottom: 0;
            margin: 0 auto;
            padding-bottom: 10px;
            width: 100%;
            color: #343A40 !important;;
        }
        footer i {
            font-size: 35px;
            font-weight: 100;
            color: #343A40 !important;
        }
        .spacer {
            height: 80px;
            background: transparent;
        }
        .home-img {
            display: block;
            max-width: 800px;
            width: 90%;
            margin: 0 auto !important;
        }
        .bulmaColor {
            color: black !important;
        }
    </style>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken'=> csrf_token(),
            'user'=> [
                'authenticated' => auth()->check(),
                'id' => auth()->check() ? auth()->user()->id : null,
                'name' => auth()->check() ? auth()->user()->name : null,
                ]
            ])
        !!};
    </script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm fixed-top">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="fab fa-accusoft"></i> {{ config('app.name', 'MyBlog') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @auth
                            <li class="{{ Request::is('/') ? 'active nav-item' : 'nav-item' }}">
                                <a class="nav-link" href="{{ route('pages.home') }}">Home</a>
                            </li>
                            <li class="{{ Request::is('posts') ? 'active nav-item' : 'nav-item' }}">
                                <a class="nav-link" href="{{ route('posts.index') }}">Posts</a>
                            </li>
                            <li class="{{ Request::is('posts/create') ? 'active nav-item' : 'nav-item' }}">
                                <a class="nav-link" href="/posts/create">Create Post</a>
                            </li>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="{{ Request::is('login') ? 'active nav-item' : 'nav-item' }}">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="{{ Request::is('register') ? 'active nav-item' : 'nav-item' }}">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="{{ Request::is('profile/*') ? 'active nav-item' : 'nav-item' }}">
                                <a class="nav-link" href="{{ route('pages.profile',['id' => Auth::id()]) }}">Profile</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <div class="add-space"></div>
        <main class="py-4">
            @yield('content')
        </main>
        <div id="container">
            <div class="spacer"></div>
        </div>
        <footer>
            <div class="text-center noselect">
                <hr class="max-hr">
                <b>Nebojsha Mitikj | 2019</b> <br>
                <a href="https://github.com/NeboM" target="_blank"><i class="fab fa-github"></i></a>
                <a href="https://www.linkedin.com/in/nebojsha-mitikj-15ab75150/" target="_blank"><i class="fab fa-linkedin"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </footer>
    </div>
<script>
    var perfEntries = performance.getEntriesByType("navigation");
    if (perfEntries[0].type === "back_forward") {
        location.reload();
    }
</script>
</body>
</html>
