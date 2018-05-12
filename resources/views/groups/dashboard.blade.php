@extends('layouts.app')
{{-- Update locale if necessary --}}
@php
    if (Session::has('lang')) app() -> setLocale(Session::get('lang'));
@endphp

@section('css-files')
    <link href="{{ asset('css/custom/groups.dashboard.css') }}" rel="stylesheet">
@endsection

@section('js-files')
    <script src="{{ asset('js/custom/groups_dashboard.js') }}"></script>
@endsection

@section('content')
    @if(strtolower(Auth::user() -> user_role) != 'basic')
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

        <h1>{{ __('groups/dashboard.header') }}</h1>
        <small>{{ __('groups/dashboard.header_sub') }}</small>
        <hr>
        <div class="container nav-buttons">
            <div class="row">
                <input id="btn_activeGr" class="col-12 col-sm-4 btn btn-outline-primary active" type="button" value="{{ __('groups/dashboard.btn_active') }}">
                <input id="btn_pendingGr" class="col-12 col-sm-4 btn btn-outline-primary" type="button" value="{{ __('groups/dashboard.btn_pending') }}">
                <input id="btn_rejectedGr" class="col-12 col-sm-4 btn btn-outline-primary" type="button" value="{{ __('groups/dashboard.btn_rejected') }}">
            </div>
        </div>

        {{-- Display all published (active) groups --}}
        <div id="div_activeGr">
            <h3>{{ __('groups/dashboard.active_header') }}</h3>
            @if($gr_approved > 0)
                @foreach($data['moderator']['approved'] as $appr)
                    <div class="row">
                        <div class="col-12 col-sm-4 col-md-3 col-lg-2">
                            <img src="{{ asset('storage/imgs_g/' . $appr -> img) }}" alt="group_img">
                        </div>
                        <div class="col-12 col-sm-8 col-md-9 col-lg-10">
                            <h4><a href="{{ url('groups/' . $appr -> id) }}">{{ $appr -> name }}</a></h4>
                            @if(strlen($appr -> description) <= $length)
                                <p>{{ $appr -> description }}</p>
                            @else
                                <p>{{ substr($appr -> description, 0, $length) . ' . . .' }}</p>
                            @endif
                        </div>
                    </div>
                    @if(!$loop -> last)
                        <hr>
                    @endif
                @endforeach
            @else
                <h5>{{ __('groups/dashboard.no_active') }}</h5>
            @endif
        </div>


        {{-- Display all groups in the approval stage --}}
        <div id="div_pendingGr">
            <h3>{{ __('groups/dashboard.pending_header') }}</h3>
            @if($gr_pending > 0)
                @foreach($data['moderator']['pending'] as $pend)
                    <div class="row">
                        <div class="col-12 col-sm-4 col-md-3 col-lg-2">
                            <img src="{{ asset('storage/imgs_g/' . $pend -> img) }}" alt="group_img">
                        </div>
                        <div class="col-12 col-sm-8 col-md-9 col-lg-10">
                            <h4><a href="{{ url('groups/' . $pend -> id) }}">{{ $pend -> name }}</a></h4>
                            @if(strlen($pend -> description) <= $length)
                                <p>{{ $pend -> description }}</p>
                            @else
                                <p>{{ substr($pend -> description, 0, $length) . ' . . .' }}</p>
                            @endif
                        </div>
                    </div>
                    @if(!$loop -> last)
                        <hr>
                    @endif
                @endforeach
            @else
                <h5>{{ __('groups/dashboard.no_pending') }}</h5>
            @endif
        </div>


        {{-- Display all rejected groups --}}
        <div id="div_rejectedGr">
            <h3>{{ __('groups/dashboard.rejected_header') }}</h3>
            @if($gr_rejected > 0)
                <small>{{ __('groups/dashboard.rejected_sub') }} <a href="{{ url('/contact') }}">{{ __('groups/dashboard.rejected_sub_a') }}</a>.</small>
                <hr>
                @foreach($data['moderator']['rejected'] as $rej)
                    <div class="row">
                        <div class="col-12 col-sm-4 col-md-3 col-lg-2">
                            <img src="{{ asset('storage/imgs_g/' . $rej -> img) }}" alt="group_img">
                        </div>
                        <div class="col-12 col-sm-8 col-md-9 col-lg-10">
                            <h4><a href="{{ url('groups/' . $rej -> id) }}">{{ $rej -> name }}</a></h4>
                            <p>{{ __('groups/dashboard.rejected_msg') }}: "{{ $rej -> adminNotification['admin_message'] }}"</p>
                        </div>
                    </div>
                    @if(!$loop -> last)
                        <hr>
                    @endif
                @endforeach
            @else
                <h5>{{ __('groups/dashboard.no_rejected') }}</h5>
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

    <h1>{{ __('groups/dashboard.header2') }}</h1>
    <small>{{ __('groups/dashboard.header2_sub') }}</small>
    <hr>
    @if($mem_approved + $mem_pending > 0)
        <div class="container nav-buttons">
            <div class="row">
                <input id="btn_activeMem" class="col-12 col-sm-6 btn btn-outline-primary active" type="button" value="{{ __('groups/dashboard.btn_approved2') }}">
                <input id="btn_pendingMem" class="col-12 col-sm-6 btn btn-outline-primary" type="button" value="{{ __('groups/dashboard.btn_pending') }}">
            </div>
        </div>

        {{-- Display all accepted membership --}}
        <div id="div_activeMem">
            <h3>{{ __('groups/dashboard.approved_header2') }}</h3>
            @if($mem_approved > 0)
                @foreach($data['member']['approved'] as $appr)
                    <div class="row">
                        <div class="col-12 col-sm-4 col-md-3 col-lg-2">
                            <img src="{{ asset('storage/imgs_g/' . $appr -> group['img']) }}" alt="group_img">
                        </div>
                        <div class="col-12 col-sm-8 col-md-9 col-lg-10">
                            <h4><a href="{{ url('groups/' . $appr -> group['id']) }}">{{ $appr -> group['name'] }}</a></h4>
                            @if(strlen($appr -> group['description']) <= $length)
                                <p>{{ $appr -> group['description'] }}</p>
                            @else
                                <p>{{ substr($appr -> group['description'], 0, $length) . ' . . .' }}</p>
                            @endif
                            <small>{{ __('groups/dashboard.mod') }}: {{ $appr -> group -> user['name'] }}</small>
                        </div>
                    </div>
                    @if(!$loop -> last)
                        <hr>
                    @endif
                @endforeach
            @else
                <h5>{{ __('groups/dashboard.no_approved2') }}</h5>
            @endif
        </div>


        {{-- Display all pending membership requests --}}
        <div id="div_pendingMem">
            <h3>{{ __('groups/dashboard.pending_header2') }}</h3>
            @if($mem_pending > 0)
                @foreach($data['member']['pending'] as $pend)
                    <div class="row">
                        <div class="col-12 col-sm-4 col-md-3 col-lg-2">
                            <img src="{{ asset('storage/imgs_g/' . $pend -> group['img']) }}" alt="group_img">
                        </div>
                        <div class="col-12 col-sm-8 col-md-9 col-lg-10">
                            <h4><a href="{{ url('groups/' . $pend -> group['id']) }}">{{ $pend -> group['name'] }}</a></h4>
                            @if(strlen($pend -> group['description']) <= $length)
                                <p>{{ $pend -> group['description'] }}</p>
                            @else
                                <p>{{ substr($pend -> group['description'], 0, $length) . ' . . .' }}</p>
                            @endif
                            <small>{{ __('groups/dashboard.mod') }}: {{ $pend -> group -> user['name'] }}</small>
                        </div>
                    </div>
                    @if(!$loop -> last)
                        <hr>
                    @endif
                @endforeach
            @else
                <h5>{{ __('groups/dashboard.no_pending2') }}</h5>
            @endif
        </div>
    @else
        <p>{{ __('groups/dashboard.no_membership') }}</p>
        <a href="{{ url('groups') }}"><button type="button" class="btn btn-outline-primary btn-lg btn-block">{{ __('groups/dashboard.all_groups') }}</button></a>
    @endif
@endsection