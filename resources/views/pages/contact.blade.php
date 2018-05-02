@extends('layouts.app')

@section('css-files')
    <link href="{{ asset('css/custom/contact.css') }}" rel="stylesheet">
@endsection

@section('js-files')
@endsection

@section('content')
    <h3>Contact Information</h3>
    <div class="row">
        <div class="col-sm-12 col-md-8 col-lg-9">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">SIA "LatvijaFF"</li>
                <li class="list-group-item">Reģ.Nr. 40000000000</li>
                <li class="list-group-item">Rīga, Mazā Pils iela 17, Latvija, LV-1050</li>
                <li class="list-group-item">+371 22222222</li>
                <li class="list-group-item">info@latvijaff.lv</li>
            </ul>
        </div>
        <div class="col-12 col-md-4 col-lg-3">
            <img src="{{ asset('storage/app_images/LatvijaFF_noBG.png') }}" alt="latvijaff">
        </div>
    </div>
    <h3>Drop us a message</h3>
    {!! Form::open(['action' => 'NavigationController@post_contact', 'method' => 'POST']) !!}
        {{-- Form Label for the name --}}
        <div class="form-group row">
            {{ Form::label('name', 'Name', ['class' => 'col-12 col-sm-2 col-md-2 col-lg-1 col-form-label']) }}
            <div class="col-12 col-sm-10 col-md-10 col-lg-11">
                @guest
                    {{ Form::text('name', '', ['type' => 'text', 'placeholder' => 'Type your name here ...', 'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : '')]) }}
                @else
                    {{ Form::text('name', Auth::user() -> name, ['type' => 'text', 'class' => 'form-control-plaintext' . ($errors->has('name') ? ' is-invalid' : ''), 'readonly']) }}
                @endguest
            </div>
        </div>
        {{-- Form Label for the e-mail --}}
        <div class="form-group row">
            {{ Form::label('email', 'E-mail', ['class' => 'col-12 col-sm-2 col-md-2 col-lg-1 col-form-label']) }}
            <div class="col-12 col-sm-10 col-md-10 col-lg-11">
                @guest
                    {{ Form::text('email', '', ['type' => 'email', 'placeholder' => 'E-mail address ...', 'class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : '')]) }}
                @else
                    {{ Form::text('email', Auth::user() -> email , ['type' => 'email', 'class' => 'form-control-plaintext' . ($errors->has('email') ? ' is-invalid' : ''), 'readonly']) }}
                @endguest
                <small class="form-text text-muted">Your e-mail will not be shared with the third parties.</small>
            </div>
        </div>
        {{-- Form Label for the title --}}
        <div class="form-group row">
            {{ Form::label('title', 'Title', ['class' => 'col-12 col-sm-2 col-md-2 col-lg-1 col-form-label']) }}
            <div class="col-12 col-sm-10 col-md-10 col-lg-11">
                {{ Form::text('title', '', ['type' => 'text', 'placeholder' => 'Title of the message ...', 'class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : '')]) }}
            </div>
        </div>
        {{-- Form Label for the message --}}
        <div class="form-group">
            {{ Form::label('message', 'Message') }}
            {{ Form::textarea('message', '', ['type' => 'text', 'placeholder' => 'Type your message here ...', 'class' => 'form-control' . ($errors->has('message') ? ' is-invalid' : '')]) }}
        </div>
        {{ Form::submit('Send message', ['class' => 'btn btn-primary']) }}
    {!! Form::close() !!}
@endsection