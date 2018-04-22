@extends('layouts.app')

@section('css-files')
    <link href="{{ asset('css/custom/calendar.css') }}" rel="stylesheet">
@endsection

@section('js-files')
    <script src="{{ asset('js/classes/Calendar.js') }}"></script>
    <script src="{{ asset('js/custom/calendar.js') }}"></script>
    <script>
        $(document).ready(function() {
            renderCalendar(<?php echo $date['month'] . ', ' . $date['year']; ?>);
        });
    </script>
@endsection

@section('content')
    <h2>Today: 年{{ $date['year'] }}月{{ $date['month'] }}日{{ $date['day'] }}</h2>
    <div id="render_calendar"></div>
@endsection