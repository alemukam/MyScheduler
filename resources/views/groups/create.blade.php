@extends('layouts.app')

@section('css-files')
@endsection

@section('js-files')
@endsection

@section('content')
    <h1>{{ __('groups/create.header') }}</h1>
    {!! Form::open(['action' => 'GroupController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        {{-- Form Label for the name --}}
        <div class="form-group row">
            {{ Form::label('name', __('general.name'), ['class' => 'col-sm-1 col-form-label']) }}
            <div class="col-sm-11">
                {{ Form::text('name', '', ['type' => 'text', 'placeholder' => __('groups/create.name_ph'), 'class' => 'form-control']) }}
            </div>
        </div>
        {{-- Form Label for the description --}}
        <div class="form-group">
            {{ Form::label('description', __('general.description')) }}
            {{ Form::textarea('description', '', ['placeholder' => __('groups/create.name_ph'), 'class' => 'form-control']) }}
        </div>
        {{-- Form Label for the image of the group --}}
        <div class="form-group">
            {{ Form::file('group_img') }}
            <br>
            <small>{{ __('groups/create.logo_txt') }}</small>
        </div>
        {{ Form::submit( __('general.btn_create'), ['class' => 'btn btn-primary']) }}
    {!! Form::close() !!}
@endsection