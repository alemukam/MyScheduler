@php
    if (!isset($uri)) $uri = '';
@endphp
<nav class="navbar navbar-expand-md navbar-light fixed-top navbar-laravel">
    <!-- navbar navbar-expand-md navbar-light navbar-laravel navbar-fixed-top -->
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            myScheduler
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <li><a href="{{ url('about') }}" class="nav-link{{ ($uri == 'about' ? ' active' : '') }}">About</a></li>
                <li><a href="{{ url('contact') }}" class="nav-link{{ ($uri == 'contact' ? ' active' : '') }}">Contact Us</a></li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                    <li><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>
                @else
                    <li>
                        <a class="nav-link{{ ($uri == 'groups' ? ' active' : '') }}" href="{{ url('groups') }}">
                            Groups
                        </a>
                    </li>
                    @if(strtolower(Auth::user() -> user_role) == 'admin')
                        <a class="nav-link{{ ($uri == 'finduser' ? ' active' : '') }}" href="{{ url('find-users') }}">
                            Find Users
                        </a>
                    @endif
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user() -> name }} 
                            @if(strtolower(Auth::user() -> user_role) == 'admin')
                                <span> [Admin]</span>
                            @endif
                            <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ url('dashboard') }}">
                                Dashboard
                            </a>
                            <a class="dropdown-item" href="{{ url('/') }}">
                                myCalendar
                            </a>
                            <a class="dropdown-item" href="{{ url('/dashboard/groups') }}">
                                My Groups
                            </a>
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