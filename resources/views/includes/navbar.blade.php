@php
    if (!isset($uri)) $uri = '';
@endphp
<nav class="navbar navbar-expand-md navbar-light fixed-top navbar-laravel">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            @lang('navbar.myScheduler')
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <li><a href="{{ url('about') }}" class="nav-link{{ ($uri == 'about' ? ' active' : '') }}">
                    @lang('navbar.about')
                </a></li>
                <li><a href="{{ url('contact') }}" class="nav-link{{ ($uri == 'contact' ? ' active' : '') }}">
                    @lang('navbar.contact_us')
                </a></li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li><a class="nav-link" href="{{ route('login') }}">{{ __('navbar.login') }}</a></li>
                    <li><a class="nav-link" href="{{ route('register') }}">{{ __('navbar.register') }}</a></li>
                @else
                    <li>
                        <a class="nav-link{{ ($uri == 'groups' ? ' active' : '') }}" href="{{ url('groups') }}">
                            @lang('navbar.groups')
                        </a>
                    </li>
                    @if(strtolower(Auth::user() -> user_role) == 'admin')
                        <a class="nav-link{{ ($uri == 'finduser' ? ' active' : '') }}" href="{{ url('find-users') }}">
                            @lang('navbar.find_users')
                        </a>
                    @endif
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user() -> name }} 
                            @if(strtolower(Auth::user() -> user_role) == 'admin')
                                <span> [{{ __('navbar.admin') }}]</span>
                            @endif
                            <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ url('dashboard') }}">
                                @lang('navbar.dashboard')
                            </a>
                            <a class="dropdown-item" href="{{ url('/') }}">
                                @lang('navbar.myCalendar')
                            </a>
                            <a class="dropdown-item" href="{{ url('/dashboard/groups') }}">
                                @lang('navbar.myGroups')
                            </a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('navbar.logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        {!! Form::open(['id' => 'lang_form', 'action' => 'LocaleController@setLang', 'method' => 'POST']) !!}
                            {{ Form::select('lang', ['jp' => '日本語', 'en' => 'English'], app()->getLocale(), ['id' => 'lang_dropdown', 'class' => 'custom-select']) }}
                        {!! Form::close() !!}
                    </li> 
                @endguest
            </ul>
        </div>
    </div>
</nav>