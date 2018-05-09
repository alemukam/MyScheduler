@extends('layouts.app')

@section('css-files')
    <link href="{{ asset('css/custom/start_auth.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom/calendar.css') }}" rel="stylesheet">
@endsection

@section('js-files')
    <script>
        var mainLink = '{{ url('') }}';
        var id = {{ Auth::user() -> id }};

        var currentDay = {{ $date['day'] }};
        var currentMonth = {{ $date['month'] }};
        var currentYear = {{ $date['year'] }};
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

    <div class="container row">
        <h2 id="events_title">Upcoming events</h2>
    </div>
    {{-- User Events --}}
    <div id="user_events">
        <div class="container row">
            <h4>Personal Events</h4>
            <a id="btn_newEvent" class="btn btn-outline-success" href="{{ url('user-events/create') }}">Create new Event</a>
        </div>
        @if(sizeof($u_events) == 0)
            <p class="u_events">No have no personal events</p>
        @else
            <table id="u_events_table" class="table table-bordered u_events">
                <thead id="u_events">
                    <tr>
                        <th>Date</th>
                        <th>Title</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody id="u_events_render">
                    @foreach($u_events as $event)
                        <tr>
                            <td>{{ date('年Y月n日j', strtotime($event['date'])) }}</td>
                            <td><a href="{{ url('/user-events/'. $event['id']) }}">{{ $event['title'] }}</a></td>
                            <td>{{ $event['start_time'] }}</td>   
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    {{-- Group Events --}}
    <h4>Group Events</h4>
    <div id="user_group_events">
        @if(sizeof($g_events) == 0)
            <p>You have no group events</p>
        @else
            <table id="g_events_table" class="table table-bordered">
                <thead id="g_events">
                    <tr>
                        <th>Date</th>
                        <th>Title</th>
                        <th>Group</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody id="g_events_render">
                    @foreach($g_events as $event)
                        <tr>
                            <td>{{ date('年Y月n日j', strtotime($event['date'])) }}</td>
                            <td><a href="{{ url('groups/'. $event['group_id'] .'/group-events/'. $event['id']) }}">{{ $event['title'] }}</a></td>
                        <td><a href="{{ url('groups/' . $event['group_id']) }}">{{ $event -> group['name'] }}</a></td>
                            <td>{{ $event['start_time'] }}</td>   
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection