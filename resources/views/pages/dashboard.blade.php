@extends('layouts.app')

@section('css-files')
    <link href="{{ asset('css/custom/dashboard.css') }}" rel="stylesheet">
@endsection

@section('js-files')
@endsection

@section('content')
    <h1>My Dashboard{{ (strtolower(Auth::user() -> user_role) == 'admin') ? ' [Administrator]' : '' }}</h1>
    {{-- Access Settings --}}
    <div class="container row">
        <a id="btn_settings" href="{{ url('/dashboard/settings') }}" class="btn btn-outline-secondary col-12 col-sm-6">Settings</a>
    </div>

    {{-- Informative page --}}
    <div class="row">
        {{-- Image Container --}}
        <div class="col-12 col-sm-5 col-md-4 col-lg-3">
            <img src="{{ asset('storage/imgs_u/' . Auth::user() -> img) }}" alt="user_img">
            {{-- Update image --}}
            {!! Form::open(['action' => 'DashboardController@updateImage', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                <div class="form-group">
                    {{ Form::file('user_img') }}
                    <br>
                    <small>Update your profile picture</small>
                </div>
                {{ Form::hidden('_method', 'PUT') }}
                {{ Form::submit('Update', ['class' => 'col-12 btn btn-primary']) }}
            {!! Form::close() !!}
        </div>

        {{-- Profile info Container --}}
        <div id="info_container" class="col-12 col-sm-7 col-md-8 col-lg-9">
            <h4><strong>Name:</strong> {{ Auth::user() -> name }}</h4>
            <h4><strong>E-mail:</strong> {{ Auth::user() -> email }}</h4>
        </div>
    </div>

    {{-- Only for Administrators DisplayRequests --}}
    @if(isset($data) && Auth::user() -> user_role == 'admin')
        <hr>
        <h2>Messages from users</h2>
        @if(sizeof($data) > 0)
            @foreach($data as $new)
                <div class="row">
                    <div class="col-12 col-sm-2">
                    <p><strong>{{ $new -> title }}</strong></p>
                    </div>
                    <div class="col-12 col-sm-4 col-md-2">
                        <p>{{ $new -> email }}</p>
                    </div>
                    <div class="col-12 col-sm-6 col-md-8">
                        <p>{{ $new -> message }}</p>
                        @if($new -> type == 1)
                            <p>Link: <a href="{{ url('/groups/' . $new -> group_id) }}">{{ $new -> group['name'] }}</a></p>
                        @endif
                    </div>
                </div>
                <div class="row">
                    {{-- Type is 0 => regular message --}}
                    @if($new -> type == 0)
                        <div class="col-3">
                            {!! Form::open(['action' => ['AdminController@resolveMessage', $new -> id], 'method' => 'POST']) !!}
                                {{ Form::hidden('_method', 'PUT') }}
                                {{ Form::submit('Resolved', ['class' => 'col-12 btn btn-outline-success']) }}
                            {!! Form::close() !!}
                        </div>
                        <div class="col-3">
                            {!! Form::open(['action' => ['AdminController@deleteMessage', $new -> id], 'method' => 'POST']) !!}
                                {{ Form::hidden('_method', 'PUT') }}
                                {{ Form::submit('Discard', ['class' => 'col-12 btn btn-outline-danger']) }}
                            {!! Form::close() !!}
                        </div>
                    {{-- Type is 1 => group approval --}}
                    @elseif($new -> type == 1)
                        <div class="col-12 col-sm-3">
                            {!! Form::open(['action' => ['AdminController@resolveMessage', $new -> id], 'method' => 'POST']) !!}
                                {{ Form::hidden('_method', 'PUT') }}
                                {{ Form::submit('Approve', ['class' => 'col-12 btn btn-outline-success']) }}
                            {!! Form::close() !!}
                        </div>
                        <div class="col-12 col-sm-3">
                            <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#delete_modal">
                                    Reject
                            </button>
                        </div>

                        {{-- Confirmation box of the delete button --}}
                        <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="delete_modal_title" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="delete_modal_title">Provide a reason</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    {!! Form::open(['action' => ['AdminController@deleteMessage', $new -> id], 'method' => 'POST']) !!}
                                        <div class="modal-body">
                                            <div class="form-group">
                                                {!! Form::label('reason', 'Please provide a reson:') !!}
                                                {!! Form::textarea('reason', '', ['type' => 'text', 'placeholder' => 'Type the reason here . . .', 'class' => 'form-control', 'required']) !!}
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            {{ Form::hidden('_method', 'PUT') }}
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            {{ Form::submit('Reject', ['class' => 'btn btn-danger']) }}
                                        </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                @if(!$loop -> last)
                    <hr>
                @endif
            @endforeach
        @else
            <p>No new messages</p>
        @endif
    @endif
@endsection
