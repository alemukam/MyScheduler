@extends('layouts.app')

@section('css-files')
    <link href="{{ asset('css/custom/groups.show.css') }}" rel="stylesheet">
    @if(Auth::user() -> id ==  $group -> moderator_id || strtolower($data['user_status']) == 'a')
        <link href="{{ asset('css/custom/calendar.css') }}" rel="stylesheet">
    @endif
@endsection

@section('js-files')
    @if(Auth::user() -> id ==  $group -> moderator_id || strtolower($data['user_status']) == 'a')
        <?php $date = getdate(); ?>
        <script>
            var mainLink = '{{ url('') }}';
            var groupID = '{{ $group -> id }}';

            var currentDay = {{ $date['mday'] }};
            var currentMonth = {{ $date['mon'] }};
            var currentYear = {{ $date['year'] }};
        </script>
        <script src="{{ asset('js/classes/Calendar.js') }}"></script>
        <script src="{{ asset('js/custom/calendar.js') }}"></script>
        <script src="{{ asset('js/custom/calendar_group.js') }}"></script>
        <script>
            $(document).ready(function() {
                renderCalendar(currentMonth, currentYear);
            });
        </script>
    @endif
@endsection

@section('content')
    @if(Auth::user() -> id ==  $group -> moderator_id)
        {{-- Control buttons of the group = Edit and delete --}}
        <a class="btn btn-outline-primary" href="{{ url('groups/' . $group -> id . '/edit') }}">
            {{ __('groups/show.edit') }}
        </a>
        <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#delete_modal">
            {{ __('groups/show.delete') }}
        </button>
        <hr>

        {{-- Confirmation box of the delete button --}}
        <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="delete_modal_title" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="delete_modal_title">{{ __('general.delete_confirm') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>{{ __('groups/show.delete_msg') }} "{{ $group['name'] }}" ?</p>
                    </div>
                    <div class="modal-footer">
                        {!! Form::open(['action' => ['GroupController@destroy', $group -> id], 'method' => 'POST']) !!}
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('general.btn_cancel') }}</button>
                            {!! Form::hidden('_method', 'DELETE') !!}
                            {!! Form::submit( __('groups/show.delete'), ['class' => 'btn btn-danger']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>


        {{-- Display new request of membership = approve or reject buttons --}}
        <h4>{{ __('groups/show.head1') }}</h4>
        @if(sizeof($data['new_requests']) > 0)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ __('general.name') }}</th>
                        <th class="actions">{{ __('general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['new_requests'] as $request)
                        <tr class="container">
                            <td>{{ $request['name'] }}</td>
                            <td class="row" style="border-top: 0px">
                                {{-- Approve of the membership --}}
                                {!! Form::open(['action' => ['GroupController@approveOfRequest', $request['pivot']['id'], $request['pivot']['group_id']], 'method' => 'POST', 'class' => 'col-12 col-sm-4']) !!}
                                    {!! Form::hidden('_method', 'PUT') !!}
                                    {!! Form::submit( __('general.btn_accept'), ['class' => 'col-12 btn action-btn btn-success']) !!}
                                {!! Form::close() !!}
                                {{-- Reject the membership --}}
                                {!! Form::open(['action' => ['GroupController@rejectRequest', $request['pivot']['id'], $request['pivot']['group_id']], 'method' => 'POST', 'class' => 'col-12 col-sm-4']) !!}
                                    {!! Form::hidden('_method', 'DELETE') !!}
                                    {!! Form::submit( __('general.btn_reject'), ['class' => 'col-12 btn action-btn btn-warning']) !!}
                                {!! Form::close() !!}
                                {{-- Block the user --}}
                                {!! Form::open(['action' => ['GroupController@blockUser', $request['pivot']['id'], $request['pivot']['group_id']], 'method' => 'POST', 'class' => 'col-12 col-sm-4']) !!}
                                    {!! Form::hidden('_method', 'PUT') !!}
                                    {!! Form::submit( __('general.btn_block'), ['class' => 'col-12 btn action-btn btn-danger']) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        @else
            <p>{{ __('groups/show.par1') }}</p>
        @endif
    <hr>

    @elseif(sizeof($data['user_status']) > 0)
        @if($data['user_status'] == 'p')
            <button type="button" class="btn action-btn btn-info" disabled>{{ __('groups/show.req_sent') }}</button>
            <hr>
        @elseif($data['user_status'] == 'a')
            <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#leave_modal">
                {{ __('groups/show.leave') }}
            </button>
            <hr>

            {{-- Confirmation box of the leave button --}}
            <div class="modal fade" id="leave_modal" tabindex="-1" role="dialog" aria-labelledby="leave_modal_title" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="leave_modal_title">{{ __('groups/show.leave_conf') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>{{ __('groups/show.leave_msg') }} "{{ $group['name'] }}" ?</p>
                        </div>
                        <div class="modal-footer">
                            {!! Form::open(['action' => ['GroupController@leaveGroup', $group -> id], 'method' => 'POST']) !!}
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('general.btn_cancel') }}</button>
                                {!! Form::hidden('_method', 'DELETE') !!}
                                {!! Form::submit( __('groups/show.leave'), ['class' => 'btn btn-danger']) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @else
        {{-- Available only for status "null", send the request to the moderator --}}
        {!! Form::open(['action' => ['GroupController@newJoiner', $group['id']], 'method' => 'POST']) !!}
            {!! Form::submit( __('groups/show.req'), ['class' => 'btn action-btn btn-primary']) !!}
        {!! Form::close() !!}
    @endif
    <h1>{{ $group['name'] }}</h1>
    <div class="row">
        <div class="col-12 col-sm-4 col-md-3 col-lg-2">
            <img src="{{ asset('storage/imgs_g/' . $group -> img) }}" alt="group_img">
        </div>
        <div class="col-12 col-sm-8 col-md-9 col-lg-10">
            <h4>{{ $group -> name }}</h4>
            <p>{{ $group -> description }}</p>
        </div>
    </div>
    <hr>

    {{-- Display the calendar for the moderator and memebers --}}
    @if(Auth::user() -> id ==  $group -> moderator_id || strtolower($data['user_status']) == 'a')
        <h2 id="today" class="col-12 col-sm-8 col-md-6" onclick="renderCalendar(<?php echo $date['mon'] . ', ' . $date['year']; ?>)">{{ __('general.today') }}: 年{{ $date['year'] }}月{{ $date['mon'] }}日{{ $date['mday'] }}</h2>
        <div id="render_calendar"></div>
        <div class="container row">
            <h3 id="events_title">{{ __('groups/show.upcoming') }}</h3>
            @if(Auth::user() -> id ==  $group -> moderator_id)
                <a id="btn_newEvent" class="btn btn-outline-success" href="{{ url('groups/' . $group -> id . '/group-events/create') }}">{{ __('groups/show.new') }}</a>
            @endif
        </div>

        @if(sizeof($group_events) > 0)
            <table id="events_table" class="table table-bordered">
                <thead id="group_events">
                    <tr>
                        <th>{{ __('general.date') }}</th>
                        <th>{{ __('general.title') }}</th>
                        <th>{{ __('general.start_time') }}</th>
                    </tr>
                </thead>
                <tbody id="render_events">
                    @foreach($group_events as $event)
                        <tr>
                            <td>{{ date('年Y月n日j', strtotime($event['date'])) }}</td>
                            <td><a href="{{ url('groups/'. $event['group_id'] .'/group-events/'. $event['id']) }}">{{ $event['title'] }}</a></td>
                            <td>{{ $event['start_time'] }}</td>   
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>{{ __('groups/show.no_events') }}</p>
        @endif
    @else
        <h2>{{ __('groups/show.about_mod') }}</h2>
        <div class="row">
            <div class="col-12 col-sm-4 col-md-3 col-lg-2">
                <img src="{{ asset('storage/imgs_u/' . $group -> user['img']) }}" alt="user_img">
            </div>
            <div class="col-12 col-sm-8 col-md-9 col-lg-10">
                <h4>{{ __('general.name') }}: {{ $group -> user['name'] }}</h4>
            </div>
        </div>
    @endif

    {{-- Display a lost of users --}}
    @if(Auth::user() -> id ==  $group -> moderator_id)
        <hr>

        {{-- List of blocked users --}}
        <h4>{{ __('groups/show.blocked') }}</h4>
        @if(sizeof($data['blocked_users']) > 0)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th colspan="2">{{ __('general.name') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['blocked_users'] as $blocked_user)
                        <tr class="container">
                            <td>{{ $blocked_user['name'] }}</td>
                            <td class="row" style="border-top: 0px">
                                {!! Form::open(['action' => ['GroupController@rejectRequest', $blocked_user['pivot']['id'], $blocked_user['pivot']['group_id']], 'method' => 'POST', 'class' => 'col-12']) !!}
                                    {!! Form::hidden('mod_action', 'unblock') !!}
                                    {!! Form::hidden('_method', 'DELETE') !!}
                                    {!! Form::submit( __('general.btn_unblock'), ['class' => 'col-12 btn action-btn btn-success']) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>{{ __('groups/show.no_blocked') }}</p>
        @endif

        <h4>{{ __('groups/show.members') }}</h4>
        @if(sizeof($data['users']) > 0)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th colspan="2">{{ __('general.name') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['users'] as $user)
                        <tr class="container">
                            <td>{{ $user['name'] }}</td>
                            <td class="row" style="border-top: 0px">
                                {{-- Exclude user from the group --}}
                                {!! Form::open(['action' => ['GroupController@rejectRequest', $user['pivot']['id'], $user['pivot']['group_id']], 'method' => 'POST', 'class' => 'col-12 col-sm-6']) !!}
                                    {!! Form::hidden('mod_action', 'expel') !!}
                                    {!! Form::hidden('_method', 'DELETE') !!}
                                    {!! Form::submit( __('general.btn_expel'), ['class' => 'col-12 btn action-btn btn-warning']) !!}
                                {!! Form::close() !!}

                                {{-- Exclude and block the user --}}
                                {!! Form::open(['action' => ['GroupController@blockUser', $user['pivot']['id'], $user['pivot']['group_id']], 'method' => 'POST', 'class' => 'col-12 col-sm-6']) !!}
                                    {!! Form::hidden('mod_action', 'expel') !!}
                                    {!! Form::hidden('_method', 'PUT') !!}
                                    {!! Form::submit( __('general.btn_expel_block'), ['class' => 'col-12 btn action-btn btn-danger']) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>{{ __('groups/show.no_members') }}</p>
        @endif

    @endif {{-- End of the Moderator section for users --}}
@endsection