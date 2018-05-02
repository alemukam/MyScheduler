@extends('layouts.app')

@section('css-files')
    <link href="{{ asset('css/custom/findUsers.css') }}" rel="stylesheet">
@endsection

@section('js-files')
@endsection

@section('content')
    {{-- Additional if-statement for security reasons --}}
    @if(strtolower(Auth::user() -> user_role) == 'admin')
        {{-- Submit form --}}
        <h1>Search engine</h1>
        {!! Form::open(['action' => 'AdminController@performFindUsers', 'method' => 'POST']) !!}
            <div class="form-group">
                {{ Form::label('name', 'User name') }}
                {{ Form::text('name', '', ['required', 'type' => 'text', 'placeholder' => 'Type name here ...', 'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : '')]) }}
            </div>
            {{ Form::submit('Find', ['class' => 'btn btn-primary']) }}
        {!! Form::close() !!}


        {{-- Result will be displayed here --}}
        @if(isset($user_find_result))
            @if(sizeof($user_find_result) > 0)
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th >Name</th>
                            <th>Email</th>
                            <th class="actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user_find_result as $user)
                            <tr>
                                <td>{{ $user -> name }}</td>
                                <td>{{ $user -> email }}</td>
                                <td>
                                    @if($user -> status == 'a')
                                        {!! Form::open(['action' => ['AdminController@block', $user -> id], 'method' => 'POST', 'class' => 'col-12']) !!}
                                            {{ Form::hidden('_method', 'PUT') }}
                                            {{ Form::submit('Block User', ['class' => 'col-12 btn btn-danger']) }}
                                        {!! Form::close() !!}
                                    @elseif($user -> status == 'b')
                                        {!! Form::open(['action' => ['AdminController@unblock', $user -> id], 'method' => 'POST', 'class' => 'col-12']) !!}
                                            {{ Form::hidden('_method', 'PUT') }}
                                            {{ Form::submit('Unblock User', ['class' => 'col-12 btn btn-success']) }}
                                        {!! Form::close() !!}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <h3>No users found</h3>
            @endif
        @endif
    @endif
@endsection