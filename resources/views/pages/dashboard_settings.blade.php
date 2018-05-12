@extends('layouts.app')
{{-- Update locale if necessary --}}
@php
    if (Session::has('lang')) app() -> setLocale(Session::get('lang'));
@endphp

@section('css-files')
    <link href="{{ asset('css/custom/dashboard.settings.css') }}" rel="stylesheet">
@endsection

@section('js-files')
@endsection

@section('content')
    <div class="container row">
        <h1>{{ __('pages/dashboard_settings.header') }}</h1>
        @if(strtolower(Auth::user() -> user_role) != 'admin')
            <button id="btn_delete" type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#delete_modal">
                {{ __('pages/dashboard_settings.btn_delete') }}
            </button>
            {{-- Confirmation box of the delete button --}}
            <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="delete_modal_title" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="delete_modal_title">{{ __('pages/dashboard_settings.head_delete') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @if(strtolower(Auth::user() -> user_role) == 'moderator')
                            {{-- it is not allowed to delete the profile is the user is the moderator --}}
                            <div class="modal-body">
                                <p>{{ __('pages/dashboard_settings.p1_delete') }}</p>
                            <p>{{ __('pages/dashboard_settings.p2_delete') }} <a href="{{ url('/dashboard/groups') }}">{{ __('pages/dashboard_settings.a_delete') }}</a></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('general.btn_cancel') }}</button>
                                <button type="button" class="btn btn-danger" disabled>{{ __('pages/dashboard_settings.btn_delete') }}</button>
                            </div>
                        @else
                            {{-- Basic users can delete their profiles --}}
                            <div class="modal-body">
                                <p>{{ __('pages/dashboard_settings.msg_delete') }}</p>
                            </div>
                            <div class="modal-footer">
                                {!! Form::open(['action' => 'DashboardController@deleteUser', 'method' => 'POST']) !!}
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('general.btn_cancel') }}</button>
                                    {!! Form::hidden('_method', 'DELETE') !!}
                                    {!! Form::submit( __('pages/dashboard_settings.btn_delete'), ['class' => 'btn btn-danger']) !!}
                                {!! Form::close() !!}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
    {{-- Update only name, email is a login name - not allowe to change --}}
    <h3>{{ __('pages/dashboard_settings.head1') }}</h3>
    {!! Form::open(['action' => 'DashboardController@updateSettings', 'method' => 'POST']) !!}
        <div class="form-group">
            {!! Form::label('name', __('general.name')) !!}
            {!! Form::text('name', $user -> name, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            <label>{{ __('general.email') }}</label><br>
            <small>({{ __('pages/dashboard_settings.email_not') }})</small>
            <input class="form-control" type="text" value="{{ $user -> email }}" readonly>
        </div>
        {{ Form::hidden('_method', 'PUT') }}
        {{ Form::submit( __('pages/dashboard_settings.btn_change'), ['class' => 'btn btn-primary']) }}
    {!! Form::close() !!}
    <hr>

    {{-- Update password --}}
    <h3>{{ __('pages/dashboard_settings.head2') }}</h3>
    {!! Form::open(['action' => 'DashboardController@updatePassword', 'method' => 'POST']) !!}
        <div class="form-group row">
            {!! Form::label('old_pwd', __('pages/dashboard_settings.current_pwd'), ['class' => 'col-12 col-sm-4 col-md-3 col-lg-2 col-form-label']) !!}
            <div class="col-12 col-sm-8 col-md-9 col-lg-10">
                {!! Form::password('old_pwd', ['class' => 'form-control', 'required']) !!}
            </div>
        </div>
        <div class="form-group row">
            {!! Form::label('New_Password', __('pages/dashboard_settings.new_pwd1'), ['class' => 'col-12 col-sm-4 col-md-3 col-lg-2 col-form-label']) !!}
            <div class="col-12 col-sm-8 col-md-9 col-lg-10">
                {!! Form::password('New_Password', ['class' => 'form-control', 'required']) !!}
            </div>
        </div>
        <div class="form-group row">
            {!! Form::label('new_pwd_2', __('pages/dashboard_settings.new_pwd2'), ['class' => 'col-12 col-sm-4 col-md-3 col-lg-2 col-form-label']) !!}
            <div class="col-12 col-sm-8 col-md-9 col-lg-10">
                {!! Form::password('new_pwd_2', ['class' => 'form-control', 'required']) !!}
            </div>
        </div>
        {{ Form::hidden('_method', 'PUT') }}
        {{ Form::submit( __('pages/dashboard_settings.btn_changePwd'), ['class' => 'btn btn-primary']) }}
    {!! Form::close() !!}

@endsection