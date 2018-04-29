@extends('layouts.app')

@section('css-files')
    <link href="{{ asset('css/custom/groups.dashboard.css') }}" rel="stylesheet">
@endsection

@section('js-files')
    <script src="{{ asset('js/custom/groups_dashboard.js') }}"></script>
@endsection

@section('content')
    @if(strtolower(Auth::user() -> user_role) == 'moderator')
        {{-- Group moderator section --}}
        @php
            if ($data['moderator'] !== null)
            {
                $gr_approved = sizeof($data['moderator']['approved']);
                $gr_pending = sizeof($data['moderator']['pending']);
                $gr_rejected = sizeof($data['moderator']['rejected']);
            }
            else $gr_approved = $gr_pending = $gr_rejected = 0;
        @endphp

        <h1>My Groups</h1>
        <small>In this section there are the groups which have been created by you</small>
        <hr>
        <div class="container nav-buttons">
            <div class="row">
                <input id="btn_activeGr" class="col-12 col-sm-4 btn btn-outline-primary active" type="button" value="Active Groups">
                <input id="btn_pendingGr" class="col-12 col-sm-4 btn btn-outline-primary" type="button" value="Awaiting Approval">
                <input id="btn_rejectedGr" class="col-12 col-sm-4 btn btn-outline-primary" type="button" value="Rejected">
            </div>
        </div>

        {{-- Display all published (active) groups --}}
        <div id="div_activeGr">
            <h3>My Published Groups</h3>
            @if($gr_approved > 0)
                @foreach($data['moderator']['approved'] as $appr)
                    <div class="row">
                        <div class="col-12 col-sm-4 col-md-3 col-lg-2">
                            <img src="{{ asset('storage/imgs_g/' . $appr -> img) }}" alt="group_img">
                        </div>
                        <div class="col-12 col-sm-8 col-md-9 col-lg-10">
                            <h4><a href="{{ url('groups/' . $appr -> id) }}">{{ $appr -> name }}</a></h4>
                            <p>{{ $appr -> description }}</p>
                        </div>
                    </div>
                    @if(!$loop -> last)
                        <hr>
                    @endif
                @endforeach
            @else
                <h5>You have no published groups</h5>
            @endif
        </div>


        {{-- Display all groups in the approval stage --}}
        <div id="div_pendingGr">
            <h3>My Groups Awaiting Approval</h3>
            @if($gr_pending > 0)
                @foreach($data['moderator']['pending'] as $pend)
                    <div class="row">
                        <div class="col-12 col-sm-4 col-md-3 col-lg-2">
                            <img src="{{ asset('storage/imgs_g/' . $pend -> img) }}" alt="group_img">
                        </div>
                        <div class="col-12 col-sm-8 col-md-9 col-lg-10">
                            <h4><a href="{{ url('groups/' . $pend -> id) }}">{{ $pend -> name }}</a></h4>
                            <p>{{ $pend -> description }}</p>
                        </div>
                    </div>
                    @if(!$loop -> last)
                        <hr>
                    @endif
                @endforeach
            @else
                <h5>You have submitted no requests for group publishing</h5>
            @endif
        </div>


        {{-- Display all rejected groups --}}
        <div id="div_rejectedGr">
            <h3>My Rejected Groups</h3>
            @if($gr_rejected > 0)
                <small>Please read the message from the administrator and correct all compliance mistakes. If you have some questions, please, do not hesitate to contact the administrator via the <a href="{{ url('/contact') }}">contact form</a>.</small>
                <hr>
                @foreach($data['moderator']['rejected'] as $rej)
                    <div class="row">
                        <div class="col-12 col-sm-4 col-md-3 col-lg-2">
                            <img src="{{ asset('storage/imgs_g/' . $rej -> img) }}" alt="group_img">
                        </div>
                        <div class="col-12 col-sm-8 col-md-9 col-lg-10">
                            <h4><a href="{{ url('groups/' . $rej -> id) }}">{{ $rej -> name }}</a></h4>
                            <p>Message from the administrator: "{{ $rej -> adminNotification['admin_message'] }}"</p>
                        </div>
                    </div>
                    @if(!$loop -> last)
                        <hr>
                    @endif
                @endforeach
            @else
                <h5>You have submitted no requests for group publishing</h5>
            @endif
        </div>

        <hr>
        <hr>
        <hr>
    @endif


    {{-- Membership section --}}
    @php
        // number of each memebership type
        $mem_approved = sizeof($data['member']['approved']);
        $mem_pending = sizeof($data['member']['pending']);
    @endphp

    <h1>Membership Groups</h1>
    <small>This section shows all groups to which you are subscribed. You are not a moderator of these groups</small>
    <hr>
    @if($mem_approved + $mem_pending > 0)
        <div class="container nav-buttons">
            <div class="row">
                <input id="btn_activeMem" class="col-12 col-sm-6 btn btn-outline-primary active" type="button" value="Approved Membership">
                <input id="btn_pendingMem" class="col-12 col-sm-6 btn btn-outline-primary" type="button" value="Awaiting Approval">
            </div>
        </div>

        {{-- Display all accepted membership --}}
        <div id="div_activeMem">
            <h3>My Approved Membership</h3>
            @if($mem_approved > 0)
                @foreach($data['member']['approved'] as $appr)
                    <div class="row">
                        <div class="col-12 col-sm-4 col-md-3 col-lg-2">
                            <img src="{{ asset('storage/imgs_g/' . $appr -> group['img']) }}" alt="group_img">
                        </div>
                        <div class="col-12 col-sm-8 col-md-9 col-lg-10">
                            <h4><a href="{{ url('groups/' . $appr -> group['id']) }}">{{ $appr -> group['name'] }}</a></h4>
                            <p>{{ $appr -> group['description'] }}</p>
                            <small>Group Moderator: {{ $appr -> group -> user['name'] }}</small>
                        </div>
                    </div>
                    @if(!$loop -> last)
                        <hr>
                    @endif
                @endforeach
            @else
                <h5>You are not a member of any group</h5>
            @endif
        </div>


        {{-- Display all pending membership requests --}}
        <div id="div_pendingMem">
            <h3>My Membership Requests</h3>
            @if($mem_pending > 0)
                @foreach($data['member']['pending'] as $pend)
                    <div class="row">
                        <div class="col-12 col-sm-4 col-md-3 col-lg-2">
                            <img src="{{ asset('storage/imgs_g/' . $pend -> group['img']) }}" alt="group_img">
                        </div>
                        <div class="col-12 col-sm-8 col-md-9 col-lg-10">
                            <h4><a href="{{ url('groups/' . $pend -> group['id']) }}">{{ $pend -> group['name'] }}</a></h4>
                            <p>{{ $pend -> group['description'] }}</p>
                            <small>Group Moderator: {{ $pend -> group -> user['name'] }}</small>
                        </div>
                    </div>
                    @if(!$loop -> last)
                        <hr>
                    @endif
                @endforeach
            @else
                <h5>You have not submitted any membership request</h5>
            @endif
        </div>
    @else
        <p>You are not a member of any group</p>
        <a href="{{ url('groups') }}"><button type="button" class="btn btn-outline-primary btn-lg btn-block">See all available groups</button></a>
    @endif
@endsection