{{-- Update locale if necessary --}}
@if(Session::has('lang'))
    {{ app() -> setLocale(Session::get('lang')) }}
@endif

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>myScheduler</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('css-files')

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script>
        $(document).ready(function() {
            $('#lang_dropdown').on('change', function() {
                $('#lang_form').submit();
            });
        });
    </script>
    @yield('js-files')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

</head>
<body>
    <div id="app">
        @include('includes.navbar')

        <main class="container">
                @include('includes.messages')
            @yield('content')
        </main>
    </div>
</body>
</html>
