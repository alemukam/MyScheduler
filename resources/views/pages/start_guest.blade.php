@extends('layouts.app')

@section('css-files')
    <link href="{{ asset('css/custom/start.css') }}" rel="stylesheet">
@endsection

@section('js-files')
@endsection

@section('content')
    <h1>What is "myScheduler"?</h1>
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <img src="storage/app_images/start1.jpg" alt="calendar_picture">
        </div>
        <div class="col-sm-12 col-md-6">
            <p>It is a brand new calendar application where you can store all your events in order to keep track of them and do not forget all the important appointmepts you have. Imagine a Birthday of a very important person to you, but because of the daily routine you forgot to buy a present and you must find something in a rush. The calendar application "myScheduler" helps you avoid such situations.</p>
        </div>
    </div>
    <h5>It is scalable</h5>
    <div class="row">
        <div class="push-sm-12 col-md-6">
            <p>The are tons of calendar applications on the Internet. But why is "myScheduler" special? It is a cross-platform application, so it is compatible with any operationg system. It is scalable in managed in the cloud, so you must not worry about the physical safety and availability of your data. It is FREE. So, relax and let us take care of the rest.</p>
        </div>
        <div class="pull-sm-12 col-md-6">
            <img src="storage/app_images/start2.jpg" alt="scalability">
        </div>
    </div>
    <h5>It is cross-border</h5>
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <img src="storage/app_images/start3.jpg" alt="worldwide">
        </div>
        <div class="col-sm-12 col-md-6">
            <p>Initially the application was designed for the football freestyle team from Kyoto (Japan) it complies with all international standards for intormation dissipitation and distribution as well as proper protection from hacker attacks. With the help of Ibuki Yoshida (吉田伊吹) the application has been internationalized in order to meet the needs of freestylers from all other countries, but Ibuki wanted to keep some Japanese design features.</p>
        </div>
    </div>
    <h5>It is football freestyle</h5>
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <p>The application is designed by football freestyler for football freestylers. It is very easy to schedule meetings and other events for communities. Simply complete a registration form, create a new group, invite all your fellow afreestylers and arrange a meeting. All freestylers will receive the event prompt and will not forget about the meeting. Schedule a freestyle meeting and have fun together.</p>
        </div>
        <div class="col-sm-12 col-md-6">
            <img src="storage/app_images/start4.jpg" alt="freestyle">
        </div>
    </div>
@endsection