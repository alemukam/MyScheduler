@extends('layouts.app')
{{-- Update locale if necessary --}}
@php
    if (Session::has('lang')) app() -> setLocale(Session::get('lang'));
@endphp

@section('css-files')
    <link href="{{ asset('css/custom/start.css') }}" rel="stylesheet">
@endsection

@section('js-files')
@endsection

@section('content')
    <h1>{{ __('pages/start_guest.header') }}</h1>
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <img src="{{ asset('storage/app_images/start1.jpg') }}" alt="calendar_picture">
        </div>
        <div class="col-sm-12 col-md-6">
            <p>{{ __('pages/start_guest.par1') }}</p>
        </div>
    </div>
    <h5>{{ __('pages/start_guest.head2') }}</h5>
    <div class="row">
        <div class="push-sm-12 col-md-6">
            <p>{{ __('pages/start_guest.par2') }}</p>
        </div>
        <div class="pull-sm-12 col-md-6">
            <img src="{{ asset('storage/app_images/start2.jpg') }}" alt="scalability">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <img src="{{ asset('storage/app_images/start3.jpg') }}" alt="worldwide">
        </div>
        <div class="col-sm-12 col-md-6">
            <h5>{{ __('pages/start_guest.head3') }}</h5>
            <p>{{ __('pages/start_guest.par3') }}</p>
        </div>
    </div>
    <h5>{{ __('pages/start_guest.head4') }}</h5>
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <p>{{ __('pages/start_guest.par4') }}</p>
        </div>
        <div class="col-sm-12 col-md-6">
            <img src="{{ asset('storage/app_images/start4.jpg')}}" alt="freestyle">
        </div>
    </div>
@endsection