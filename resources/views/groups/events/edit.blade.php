@extends('layouts.app')

@section('css-files')
    
@endsection

@section('js-files')
    
@endsection

@section('content')
    <h1>Update event "{{ $event['title'] }}"</h1>
    <small>Group: <strong>{{ $event -> group['name'] }}</strong></small>
    <hr>
    {!! Form::open(['action' => ['Event_GroupController@update', $event -> group['id'], $event['id']], 'method' => 'POST']) !!}
        {{-- 1) Title of the event --}}
        <div class="form-group">
            {{ Form::label('title', 'Title') }}
            {{ Form::text('title', $event['title'], ['required', 'placeholder' => 'Title of the event ...', 'class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : '')]) }}
        </div>
        {{-- 2) Date of the event --}}
        <div class="form-group row">
            {{ Form::label('date', 'Date', ['class' => 'col-12 col-sm-2 col-md-2 col-lg-1 col-form-label']) }}
            <div class="col-12 col-sm-10 col-md-10 col-lg-11">
                {{ Form::date('date', date_format(date_create($event['date']), 'Y-m-d'), ['required', 'class' => 'form-control' . ($errors->has('date') ? ' is-invalid' : '')]) }}
            </div>
        </div>
        {{-- 3) Start time of the event --}}
        <div class="form-group row">
            {{ Form::label('start_time', 'Start', ['class' => 'col-12 col-sm-2 col-md-2 col-lg-1 col-form-label']) }}
            <div class="col-12 col-sm-10 col-md-10 col-lg-11">
                {{ Form::time('start_time', date_format(date_create($event['start_time']), 'H:i'), ['required', 'class' => 'form-control' . ($errors->has('start_time') ? ' is-invalid' : '')]) }}
            </div>
        </div>
        {{-- 4) End time of the event --}}
        <div class="form-group row">
            {{ Form::label('end_time', 'End', ['class' => 'col-12 col-sm-2 col-md-2 col-lg-1 col-form-label']) }}
            <div class="col-12 col-sm-10 col-md-10 col-lg-11">
                {{ Form::time('end_time', date_format(date_create($event['end_time']), 'H:i'), ['required', 'class' => 'form-control' . ($errors->has('end_time') ? ' is-invalid' : '')]) }}
            </div>
        </div>
        {{-- 5) Descriptions --}}
        <div class="form-group">
            {{ Form::label('description', 'Description') }}
            {{ Form::textarea('description', $event['description'], ['required', 'type' => 'text', 'placeholder' => 'Describe the event ...', 'class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : '')]) }}
        </div>
        {{ Form::hidden('_method', 'PUT') }}
        {{ Form::submit('Update', ['class' => 'btn btn-primary']) }}
    {!! Form::close() !!}
@endsection