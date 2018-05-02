@extends('layouts.app')

@section('css-files')
@endsection

@section('js-files')
@endsection

@section('content')
    <h1>Update the group</h1>
    {!! Form::open(['action' => ['GroupController@update', $group -> id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        {{-- Form Label for the name --}}
        <div class="form-group row">
            {{ Form::label('name', 'Name', ['class' => 'col-sm-1 col-form-label']) }}
            <div class="col-sm-11">
                {{ Form::text('name', $group -> name, ['type' => 'text', 'placeholder' => 'Name of the group ...', 'class' => 'form-control']) }}
            </div>
        </div>
        {{-- Form Label for the description --}}
        <div class="form-group">
            {{ Form::label('description', 'Description') }}
            {{ Form::textarea('description', $group -> description, ['type' => 'text', 'placeholder' => 'Describe your group ...', 'class' => 'form-control']) }}
        </div>
        {{-- Form Label for the image of the group --}}
        <div class="form-group">
            {{ Form::file('group_img') }}
            <br>
            <small>Choose a logo for your group</small>
        </div>
        {{ Form::hidden('_method', 'PUT') }}
        {{ Form::submit('Update', ['class' => 'btn btn-primary']) }}
    {!! Form::close() !!}
@endsection