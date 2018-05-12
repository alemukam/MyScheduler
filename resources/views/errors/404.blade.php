@extends('layouts.errors')
@php
    if (Session::has('lang')) app() -> setLocale(Session::get('lang'));
@endphp

@section('content')
    <h1>The page you are looking for is not available.</h1>
    <h4>あなたが探しているページは利用できません。</h4>
    <img src="{{ asset('storage/app_images/404.jpg') }}" alt="latvijaff">
@endsection