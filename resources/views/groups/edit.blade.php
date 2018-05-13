@extends('layouts.app')
{{-- Update locale if necessary --}}
@php
    if (Session::has('lang')) app() -> setLocale(Session::get('lang'));
@endphp

@section('css-files')
@endsection

@section('js-files')
@endsection

@section('content')
    <h1>{{ __('groups/edit.header') }}</h1>
    {!! Form::open(['action' => ['GroupController@update', $group -> id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        {{-- Form Label for the name --}}
        <div class="form-group row">
            {{ Form::label('name', __('general.name'), ['class' => 'col-sm-1 col-form-label']) }}
            <div class="col-sm-11">
                {{ Form::text('name', $group -> name, ['type' => 'text', 'placeholder' => __('groups/edit.name_ph'), 'class' => 'form-control']) }}
            </div>
        </div>
        {{-- Form Label for the description --}}
        <div class="form-group">
            {{ Form::label('description', __('general.description')) }}
            {{ Form::textarea('description', $group -> description, ['placeholder' => __('groups/edit.description_ph'), 'class' => 'form-control']) }}
        </div>
        {{-- Form Label for the image of the group --}}
        <div class="form-group">
            {{ Form::file('group_img') }}
            <br>
            <small>{{ __('groups/edit.logo_txt') }}</small>
        </div>
        {{ Form::hidden('_method', 'PUT') }}
        {{ Form::submit( __('general.update'), ['class' => 'btn btn-primary']) }}
    {!! Form::close() !!}
@endsection