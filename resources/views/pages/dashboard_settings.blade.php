@extends('layouts.app')

@section('css-files')
    <link href="{{ asset('css/custom/dashboard.settings.css') }}" rel="stylesheet">
@endsection

@section('js-files')
@endsection

@section('content')
    <div class="container row">
        <h1>Update my settings</h1>
        @if(strtolower(Auth::user() -> user_role) != 'admin')
            <button id="btn_delete" type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#delete_modal">
                Delete My Profile
            </button>
            {{-- Confirmation box of the delete button --}}
            <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="delete_modal_title" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="delete_modal_title">Delete Confirmation Message</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @if(strtolower(Auth::user() -> user_role) == 'moderator')
                            {{-- it is not allowed to delete the profile is the user is the moderator --}}
                            <div class="modal-body">
                                <p>You cannot delete your profile because you are a moderator of groups.</p>
                            <p>If you want to delete your profile you must delete your groups in the <a href="{{ url('/dashboard/groups') }}">dashboard</a></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" disabled>Delete My Profile</button>
                            </div>
                        @else
                            {{-- Basic users can delete their profiles --}}
                            <div class="modal-body">
                                <p>Are you sure that you want to delete your profile ?</p>
                            </div>
                            <div class="modal-footer">
                                {!! Form::open(['action' => 'DashboardController@deleteUser', 'method' => 'POST']) !!}
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    {!! Form::hidden('_method', 'DELETE') !!}
                                    {!! Form::submit('Delete My Profile', ['class' => 'btn btn-danger']) !!}
                                {!! Form::close() !!}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
    {{-- Update only name, email is a login name - not allowe to change --}}
    <h3>Personal Information</h3>
    {!! Form::open(['action' => 'DashboardController@updateSettings', 'method' => 'POST']) !!}
        <div class="form-group">
            {!! Form::label('name', 'Name') !!}
            {!! Form::text('name', $user -> name, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            <label>Email</label><br>
            <small>(it is not allowed to change the e-mail)</small>
            <input class="form-control" type="text" value="{{ $user -> email }}" readonly>
        </div>
        {{ Form::hidden('_method', 'PUT') }}
        {{ Form::submit('Change', ['class' => 'btn btn-primary']) }}
    {!! Form::close() !!}
    <hr>

    {{-- Update password --}}
    <h3>Change Password</h3>
    {!! Form::open(['action' => 'DashboardController@updatePassword', 'method' => 'POST']) !!}
        <div class="form-group row">
            {!! Form::label('old_pwd', 'Current Password', ['class' => 'col-12 col-sm-4 col-md-3 col-lg-2 col-form-label']) !!}
            <div class="col-12 col-sm-8 col-md-9 col-lg-10">
                {!! Form::password('old_pwd', ['class' => 'form-control', 'required']) !!}
            </div>
        </div>
        <div class="form-group row">
            {!! Form::label('New_Password', 'New Password', ['class' => 'col-12 col-sm-4 col-md-3 col-lg-2 col-form-label']) !!}
            <div class="col-12 col-sm-8 col-md-9 col-lg-10">
                {!! Form::password('New_Password', ['class' => 'form-control', 'required']) !!}
            </div>
        </div>
        <div class="form-group row">
            {!! Form::label('new_pwd_2', 'Confirm Password', ['class' => 'col-12 col-sm-4 col-md-3 col-lg-2 col-form-label']) !!}
            <div class="col-12 col-sm-8 col-md-9 col-lg-10">
                {!! Form::password('new_pwd_2', ['class' => 'form-control', 'required']) !!}
            </div>
        </div>
        {{ Form::hidden('_method', 'PUT') }}
        {{ Form::submit('Change Password', ['class' => 'btn btn-primary']) }}
    {!! Form::close() !!}

@endsection