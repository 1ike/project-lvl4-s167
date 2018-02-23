<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        @php $currentRoute = Route::currentRouteName() @endphp
        @if ($currentRoute !== 'home')
            @yield('title') -
        @endif
        {{ config('app.name', 'Task Manager @ Laravel') }}
    </title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">Task Manager</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @php
                            $tasks = 'tasks.index';
                            $isTasks = $currentRoute === $tasks;
                        @endphp
                        <li class="nav-item{{ $isTasks ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route($tasks) }}">Tasks{!! $isTasks ? ' <span class="sr-only">(current)</span>' : '' !!}</a>
                        </li>

                        @php
                            $users = 'users.index';
                            $isUsers = $currentRoute === $users;
                        @endphp
                        <li class="nav-item{{ $isUsers ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route($users) }}">Users{!! $isUsers ? ' <span class="sr-only">(current)</span>' : '' !!}</a>
                        </li>

                        @php
                            $taskstatuses = 'taskstatuses.index';
                            $isTaskstatuses = $currentRoute === $taskstatuses;
                        @endphp
                        <li class="nav-item{{ $isTaskstatuses ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route($taskstatuses) }}">Task Statuses{!! $isTaskstatuses ? ' <span class="sr-only">(current)</span>' : '' !!}</a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                            <li><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('users.edit', ['id' => Auth::id()]) }}">
                                        Edit
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Logout
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

        <main class="container py-4">
            @include('flash::message')
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
