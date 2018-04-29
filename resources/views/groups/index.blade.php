@extends('layouts.app')

@section('css-files')
    <link href="{{ asset('css/custom/groups.index.css') }}" rel="stylesheet">
@endsection

@section('js-files')
@endsection

@section('content')
    <a href="{{ url('groups/create') }}"><button id="create_new" type="button" class="btn btn-primary btn-lg btn-block">Create new group</button></a>
    @if(sizeof($groups) < 1)
        <h3>There are no groups</h3>
    @else
        @foreach($groups as $item)
            <div class="row">
                <div class="col-12 col-sm-4 col-md-3 col-lg-2">
                    <img src="{{ asset('storage/imgs_g/' . $item -> img) }}" alt="group_img">
                </div>
                <div class="col-12 col-sm-8 col-md-9 col-lg-10">
                    <h4><a href="groups/{{ $item -> id }}">{{ $item -> name }}</a></h4>
                    <p>{{ $item -> description }}</p>
                    <small>Group Moderator: {{ $item -> user['name'] }}</small>
                </div>
            </div>
            @if(!$loop -> last)
                <hr>
            @endif
        @endforeach
    @endif
@endsection