@extends('layouts.app')
{{-- Update locale if necessary --}}
@php
    if (Session::has('lang')) app() -> setLocale(Session::get('lang'));
@endphp

@section('css-files')
    <link href="{{ asset('css/custom/contact.css') }}" rel="stylesheet">
@endsection

@section('js-files')
@endsection

@section('content')
    <h3>{{ __('pages/contact.head1') }}</h3>
    <div class="row">
        <div class="col-sm-12 col-md-8 col-lg-9">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">SIA "LatvijaFF"</li>
                <li class="list-group-item">{{ __('pages/contact.reg_num') }} 40000000000</li>
                <li class="list-group-item">Rīga, Mazā Pils iela 17, Latvija, LV-1050</li>
                <li class="list-group-item">+371 22222222</li>
                <li class="list-group-item">info@latvijaff.lv</li>
            </ul>
        </div>
        <div class="col-12 col-md-4 col-lg-3">
            <img src="{{ asset('storage/app_images/LatvijaFF_noBG.png') }}" alt="latvijaff">
        </div>
    </div>
    <h3>{{ __('pages/contact.head2') }}</h3>
    {!! Form::open(['action' => 'NavigationController@post_contact', 'method' => 'POST']) !!}
        {{-- Form Label for the name --}}
        <div class="form-group row">
            {{ Form::label('name', __('pages/contact.lbl1'), ['class' => 'col-12 col-sm-2 col-md-2 col-lg-1 col-form-label']) }}
            <div class="col-12 col-sm-10 col-md-10 col-lg-11">
                @guest
                    {{ Form::text('name', '', ['type' => 'text', 'placeholder' => __('pages/contact.tb1'), 'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : '')]) }}
                @else
                    {{ Form::text('name', Auth::user() -> name, ['type' => 'text', 'class' => 'form-control-plaintext' . ($errors->has('name') ? ' is-invalid' : ''), 'readonly']) }}
                @endguest
            </div>
        </div>
        {{-- Form Label for the e-mail --}}
        <div class="form-group row">
            {{ Form::label('email', __('pages/contact.lbl2'), ['class' => 'col-12 col-sm-2 col-md-2 col-lg-1 col-form-label']) }}
            <div class="col-12 col-sm-10 col-md-10 col-lg-11">
                @guest
                    {{ Form::text('email', '', ['type' => 'email', 'placeholder' => __('pages/contact.tb2'), 'class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : '')]) }}
                @else
                    {{ Form::text('email', Auth::user() -> email , ['type' => 'email', 'class' => 'form-control-plaintext' . ($errors->has('email') ? ' is-invalid' : ''), 'readonly']) }}
                @endguest
                <small class="form-text text-muted">{{ __('pages/contact.b_text') }}</small>
            </div>
        </div>
        {{-- Form Label for the title --}}
        <div class="form-group row">
            {{ Form::label('title', __('pages/contact.lbl3'), ['class' => 'col-12 col-sm-2 col-md-2 col-lg-1 col-form-label']) }}
            <div class="col-12 col-sm-10 col-md-10 col-lg-11">
                {{ Form::text('title', '', ['type' => 'text', 'placeholder' => __('pages/contact.tb3'), 'class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : '')]) }}
            </div>
        </div>
        {{-- Form Label for the message --}}
        <div class="form-group">
            {{ Form::label('message', __('pages/contact.lbl4')) }}
            {{ Form::textarea('message', '', ['placeholder' => __('pages/contact.tb4'), 'class' => 'form-control' . ($errors->has('message') ? ' is-invalid' : '')]) }}
        </div>
        {{ Form::submit(__('pages/contact.submit'), ['class' => 'btn btn-primary']) }}
    {!! Form::close() !!}
@endsection