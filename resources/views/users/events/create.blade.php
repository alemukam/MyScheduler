@extends('layouts.app')
@php
    // language
    if (Session::has('lang')) app() -> setLocale(Session::get('lang'));
    // End time - by default now + 30 min
    $end_time = date_create(date('H:i'));
    date_add($end_time, new DateInterval('PT30M'));
@endphp

@section('css-files')
@endsection

@section('js-files') 
@endsection

@section('content')
    <h1>{{ __('events/create.header_u') }}</h1>
    {!! Form::open(['action' => 'Event_UserController@store', 'method' => 'POST']) !!}
        {{-- 1) Title of the event --}}
        <div class="form-group">
            {{ Form::label('title', __('general.title')) }}
            {{ Form::text('title', '', ['required', 'placeholder' => __('events/create.title_ph'), 'class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : '')]) }}
        </div>
        {{-- 2) Date of the event --}}
        <div class="form-group row">
            {{ Form::label('date', __('general.date'), ['class' => 'col-12 col-sm-2 col-md-2 col-lg-1 col-form-label']) }}
            <div class="col-12 col-sm-10 col-md-10 col-lg-11">
                {{ Form::text('date', date('Y-m-d'), ['required', 'class' => 'form-control' . ($errors->has('date') ? ' is-invalid' : '')]) }}
            </div>
        </div>
        {{-- 3) Start time of the event --}}
        <div class="form-group row">
            {{ Form::label('start_time', __('events/create.start'), ['class' => 'col-12 col-sm-2 col-md-2 col-lg-1 col-form-label']) }}
            <div class="col-12 col-sm-10 col-md-10 col-lg-11">
                {{ Form::text('start_time', date('H:i'), ['required', 'class' => 'form-control' . ($errors->has('start_time') ? ' is-invalid' : '')]) }}
            </div>
        </div>
        {{-- 4) End time of the event --}}
        <div class="form-group row">
            {{ Form::label('end_time', __('events/create.end'), ['class' => 'col-12 col-sm-2 col-md-2 col-lg-1 col-form-label']) }}
            <div class="col-12 col-sm-10 col-md-10 col-lg-11">
                {{ Form::text('end_time', date_format($end_time, 'H:i'), ['required', 'class' => 'form-control' . ($errors->has('end_time') ? ' is-invalid' : '')]) }}
            </div>
        </div>
        {{-- 5) Descriptions --}}
        <div class="form-group">
            {{ Form::label('description', __('general.description')) }}
            {{ Form::textarea('description', '', ['required', 'placeholder' => __('events/create.description_ph'), 'class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : '')]) }}
        </div>
        {{ Form::submit(__('general.btn_create'), ['class' => 'btn btn-primary']) }}
    {!! Form::close() !!}
@endsection