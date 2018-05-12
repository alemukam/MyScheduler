@extends('layouts.errors')
@php
    if (Session::has('lang')) app() -> setLocale(Session::get('lang'));
@endphp

@section('content')
    <h1>{{ __('errors.403') }}</h1>
    <img src="{{ asset('storage/app_images/403.jpg') }}" alt="latvijaff">
@endsection