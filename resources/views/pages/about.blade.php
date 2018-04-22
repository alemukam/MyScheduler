@extends('layouts.app')

@section('css-files')
    <link href="{{ asset('css/custom/about.css') }}" rel="stylesheet">
@endsection

@section('js-files')
@endsection

@section('content')
    <h1>Who we are</h1>
    <div class="row">
        <div class="img_hide col-12 col-md-4 col-lg-3">
            <img src="storage/app_images/LatvijaFF_noBG.png" alt="latvijaff">
            <div class="img_sub">
                <p><small>Football Freestyle community<br>in Latvia</small></p>
            </div>
        </div>
        <div class="col-sm-12 col-md-8 col-lg-9">
            <h4>LatvijaFF - Development department</h4>
            <p>Team LatvijaFF is a football freestyle community which was established in Riga (Latvia) in 2015 by four local freestylers. The main goal of the community is to popularize the sport in Latvia by organizing various events, i.e. football freestyle meetings in Riga and other major cities and performances at different kinds of events. One of the biggest problems concerning football freestyle in Latvia is that the sport is not that popular as in other European countries and for quite a long time there have been no newcomers to the Latvian community. Therefore, the current short-term target is to establish the academy where everyone will have an opportunity to learn how to freestyle with a football.</p>
            <p>However, due to the worldwide football freestyle crisis in late summer 2017 the community has suffered a signifficant loss and was forced to leave the Football Freestyle Federadion right after SuperBall 2017 which was held in Prague.</p>
            <p>The president and CEO of the community Andrey Nosik started looking for new opportunities for conducting business. Having had a strong programming background he decided that the community could offer IT solutions for all football freestylers around the globe. That is how the development department was established.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-7 col-lg-8">
            <h4>Vision of Andrey Nosik</h4>
            <p>Before the crisis in 2017 football freestyle communities had been growing quite fast and they had been making creating websites of the local communities using latest technologies because demand for modern-level functionality was quite high. And that is why they purchased professional services for specialized companies and it cost a lot.</p>
            <p>Now after the crisis many communities cannot allow to pay the maintenance fee for the created webpages and it is the problem I found. I think that freestyle must keep developing, communities must keep growing notwithstanding a potential lack of financial resources and the Internet is required in order to achieve it. Therefore, we have established our IT department of the LatvijaFF community where we help other football freestyle communities design and host webpages and hopefully more people will see them.</p>
        </div>
        <div class="col-12 col-md-5 col-lg-4">
            <img src="storage/app_images/andrey_nosik.jpg" alt="andrey_nosik">
            <div class="img_sub">
                <p><b>Andrey Nosik</b><br><small>LatvijaFF President & CEO</small></p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="img_hide" class="col-12 col-md-4 col-lg-3">
            <img src="storage/app_images/services.png" alt="our_services">
        </div>
        <div class="col-sm-12 col-md-8 col-lg-9">
            <h4>Our services</h4>
            <p>We use Laravel 5.6 framework in order to deliver up-to-date WEB solutions for football freestyle communities around the world.</p>
            <div class="row">
                <div class="col-12 col-sm-4">
                    <div class="list-group" id="list-tab" role="tablist">
                        <a class="list-group-item list-group-item-action active" id="list-home-list" data-toggle="list" href="#list-home" role="tab" aria-controls="home">Development</a>
                        <a class="list-group-item list-group-item-action" id="list-profile-list" data-toggle="list" href="#list-profile" role="tab" aria-controls="profile">Maintenance</a>
                        <a class="list-group-item list-group-item-action" id="list-messages-list" data-toggle="list" href="#list-messages" role="tab" aria-controls="messages">Hosting</a>
                        <a class="list-group-item list-group-item-action" id="list-settings-list" data-toggle="list" href="#list-settings" role="tab" aria-controls="settings">Freestyle</a>
                    </div>
                </div>
                <div class="col-12 col-sm-8">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
                            <h5>Development</h5>
                            <p>Creation of WEB application from scratch. You only need to tell us what kind of a WEB application you would like to develop, outline approximate design and we will take care of the rest from the initial wish to the actual website.</p>
                        </div>
                        <div class="tab-pane fade" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
                            <h5>Maintenance</h5>
                            <p>After an application is developed and published on the Internet the job is not done at this stage because there are always some more thing which will have to be integrated into the application and we also keep an eye on the applications we develop. Just let us know what new functionality should be included.</p>
                        </div>
                        <div class="tab-pane fade" id="list-messages" role="tabpanel" aria-labelledby="list-messages-list">
                            <h5>Hosting</h5>
                            <p>We collaborate with the leading global corporations (Amazon Web Services, Microsoft Azure and Google Cloud) in order to offer you the best and most secure hosting of your applications.</p>
                        </div>
                        <div class="tab-pane fade" id="list-settings" role="tabpanel" aria-labelledby="list-settings-list">
                            <h5>Football Freestyle</h5>
                            <p>And of couse we do not forget our origin. We are football freestylers, so can help with some pieces of advice about football freestyle progression in lowerbody, upperbody and sitting. We can share tutorials of various tricks and help develop consistency in basic tricks.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection