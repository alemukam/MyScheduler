@extends('layouts.app')

@section('css-files')
    <link href="{{ asset('css/custom/calendar.css') }}" rel="stylesheet">
@endsection

@section('js-files')
    <script>
        var currentDay = <?php echo $date['day']; ?>;
        var currentMonth = <?php echo $date['month']; ?>;
        var currentYear = <?php echo $date['year']; ?>;
    </script>
    <script src="{{ asset('js/classes/Calendar.js') }}"></script>
    <script src="{{ asset('js/custom/calendar.js') }}"></script>
    <script src="{{ asset('js/custom/calendar_user.js') }}"></script>
    <script>
        $(document).ready(function() {
            renderCalendar(currentMonth, currentYear);
        });
    </script>
@endsection

@section('content')
    <h2 id="today" class="col-12 col-sm-8 col-md-6" onclick="renderCalendar(<?php echo $date['month'] . ', ' . $date['year']; ?>)">Today: 年{{ $date['year'] }}月{{ $date['month'] }}日{{ $date['day'] }}</h2>
    <div id="render_calendar"></div>

    <div class="row">
        <h2>Upcoming events</h2>
    </div>
    <div id="user_events">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Title</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>
@endsection