@extends('layouts.app')

@section('css-files')
@endsection

@section('js-files')
@endsection

@section('content')
    {{-- Delete and Edit buttons -> only for moderator --}}
    @if(Auth::user() -> id ==  $event -> user_id)
        <a class="btn btn-outline-primary" href="{{ url('user-events/'. $event['id'] . '/edit') }}">
            {{ __('events/show.btn_edit') }}
        </a>
        <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#delete_modal">
            {{ __('events/show.btn_delete') }}
        </button>
        <hr>

        {{-- Confirmation box of the delete button --}}
        <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="delete_modal_title" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="delete_modal_title">{{ __('general.delete_confirm') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>{{ __('events/show.delete_confirm') }} "{{ $event['title'] }}" ?</p>
                    </div>
                    <div class="modal-footer">
                        {!! Form::open(['action' => ['Event_UserController@destroy', $event['id']], 'method' => 'POST']) !!}
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('general.btn_cancel') }}</button>
                            {!! Form::hidden('_method', 'DELETE') !!}
                            {!! Form::submit(__('events/show.btn_edit'), ['class' => 'btn btn-danger']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- Header of the event --}}
    <h1>{{ $event['title'] }}</h1>
    <hr><hr>

    {{-- Info about the event --}}
    <h3>{{ __('general.date') }}: {{ date_format(date_create($event['date']), '年Y月n日j') }}</h3>
    {{-- Display time in one row --}}
    <div class="row">
        <div class="col-12 col-sm-4 col-md-3">  
            <p>{{ __('events/show.start') }} {{ date_format(date_create($event['start_time']), 'G:i') }}</p>
        </div>
        <div class="col-12 col-sm-4 col-md-3">
            <p>{{ __('events/show.end') }} {{ date_format(date_create($event['end_time']), 'G:i') }}</p>
        </div>
    </div>
    <hr>
    {{-- Description of the event --}}
    <h3>{{ __('events/show.description') }}</h3>
    <div class="container row">
        <p>{{ $event['description'] }}</p>
    </div>
@endsection