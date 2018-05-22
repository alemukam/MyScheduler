@extends('layouts.app')

@section('css-files')
    <link href="{{ asset('css/custom/about.css') }}" rel="stylesheet">
@endsection

@section('js-files')
@endsection

@section('content')
    <h1>{{ __('pages/about.header') }}</h1>
    <div class="row">
        <div class="col-12 col-md-4 col-lg-3 img_hide">
            <img src="{{ asset('storage/app_images/LatvijaFF_noBG.png') }}" alt="latvijaff">
            <div class="img_sub">
                <p><small>{{ __('pages/about.img_footer-1') }}<br>{{ __('pages/about.img_footer-2') }}</small></p>
            </div>
        </div>
        <div class="col-sm-12 col-md-8 col-lg-9">
            <h4>{{ __('pages/about.head1') }}</h4>
            <p>{{ __('pages/about.par1-1') }}</p>
            <p>{{ __('pages/about.par1-2') }}</p>
            <p>{{ __('pages/about.par1-3') }}</p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-7 col-lg-8">
            <h4>{{ __('pages/about.head2') }}</h4>
            <p>{{ __('pages/about.par2-1') }}</p>
            <p>{{ __('pages/about.par2-2') }}</p>
        </div>
        <div class="col-12 col-md-5 col-lg-4">
            <img src="{{ asset('storage/app_images/andrey_nosik.jpg') }}" alt="andrey_nosik">
            <div class="img_sub">
                <p><b>{{ __('pages/about.img_footer-3') }}</b><br><small>{{ __('pages/about.img_footer-4') }}</small></p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="img_hide col-12 col-md-4 col-lg-3">
            <img src="{{ asset('storage/app_images/services.png') }}" alt="our_services">
        </div>
        <div class="col-sm-12 col-md-8 col-lg-9">
            <h4>{{ __('pages/about.head3') }}</h4>
            <p>{{ __('pages/about.par3') }}</p>
            <div class="row">
                <div class="col-12 col-sm-4">
                    <div class="list-group" id="list-tab" role="tablist">
                        <a class="list-group-item list-group-item-action active" id="list-dev-list" data-toggle="list" href="#list-dev" role="tab">{{ __('pages/about.s1') }}</a>
                        <a class="list-group-item list-group-item-action" id="list-maintenance-list" data-toggle="list" href="#list-maintenance" role="tab">{{ __('pages/about.s2') }}</a>
                        <a class="list-group-item list-group-item-action" id="list-hosting-list" data-toggle="list" href="#list-hosting" role="tab">{{ __('pages/about.s3') }}</a>
                        <a class="list-group-item list-group-item-action" id="list-ff-list" data-toggle="list" href="#list-ff" role="tab">{{ __('pages/about.s4') }}</a>
                    </div>
                </div>
                <div class="col-12 col-sm-8">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="list-dev" role="tabpanel">
                            <h5>{{ __('pages/about.s1') }}</h5>
                            <p>{{ __('pages/about.des1') }}</p>
                        </div>
                        <div class="tab-pane fade" id="list-maintenance" role="tabpanel">
                            <h5>{{ __('pages/about.s2') }}</h5>
                            <p>{{ __('pages/about.des2') }}</p>
                        </div>
                        <div class="tab-pane fade" id="list-hosting" role="tabpanel">
                            <h5>{{ __('pages/about.s3') }}</h5>
                            <p>{{ __('pages/about.des3') }}</p>
                        </div>
                        <div class="tab-pane fade" id="list-ff" role="tabpanel">
                            <h5>{{ __('pages/about.s4') }}</h5>
                            <p>{{ __('pages/about.des4') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection