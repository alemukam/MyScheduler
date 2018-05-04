@extends('layouts.app')

@section('css-files')
    
@endsection

@section('js-files')
    
@endsection

@section('content')
    {{-- Delete and Edit buttons -> only for moderator --}}
    @if(Auth::user() -> id ==  $event -> group['moderator_id'])
        <a href="{{ url('/groups/'. $event['group_id'] .'/group-events/'. $event['id'] . '/edit') }}">
            <button type="button" class="btn btn-outline-primary">Edit Event</button>
        </a>
        <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#delete_modal">
            Delete Event
        </button>
        <hr>

        {{-- Confirmation box of the delete button --}}
        <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="delete_modal_title" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="delete_modal_title">Delete Confirmation Message</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure that you want to delete the event "{{ $event['title'] }}" ?</p>
                        <small>Group: "{{ $event -> group['name'] }}"</small>
                    </div>
                    <div class="modal-footer">
                        {!! Form::open(['action' => ['Event_GroupController@destroy', $event['group_id'], $event['id']], 'method' => 'POST']) !!}
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            {!! Form::hidden('_method', 'DELETE') !!}
                            {!! Form::submit('Delete Event', ['class' => 'btn btn-danger']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- Header of the event --}}
    <h1>{{ $event['title'] }}</h1>
    <small>Group: "{{ $event -> group['name'] }}"</small>
    <hr><hr>

    {{-- Info about the event --}}
    <h3>Date: {{ date_format(date_create($event['date']), '年Y月n日j') }}</h3>
    {{-- Display time in one row --}}
    <div class="row">
        <div class="col-12 col-sm-4 col-md-3">  
            <p>Starts at {{ date_format(date_create($event['start_time']), 'G:i') }}</p>
        </div>
        <div class="col-12 col-sm-4 col-md-3">
            <p>Ends at {{ date_format(date_create($event['end_time']), 'G:i') }}</p>
        </div>
    </div>
    <hr>
    {{-- Description of the event --}}
    <h3>Description of the event</h3>
    <div class="container row">
        <p>{{ $event['description'] }}</p>
    </div>
@endsection