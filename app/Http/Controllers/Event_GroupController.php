<?php

namespace App\Http\Controllers;

use App\Group;
use App\GroupEvent;
use App\Http\Resources\GroupEvent as ResourceGroupEvent;

use App\Http\Requests;
use Illuminate\Http\Request;

class Event_GroupController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this -> middleware('auth', ['except' => ['getEventsOnDate']]);
        $this -> middleware('check_block', ['except' => ['getEventsOnDate']]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($group_id)
    {
        $user_id = auth() -> user() -> id;
        $group = Group::findOrFail($group_id);

        if ($group -> moderator_id != $user_id) { unset($user_id, $group); abort(403);}

        $data = array('id' => $group_id, 'name' => $group -> name);
        unset($user_id, $group);
        return view('groups.events.create') -> with('data', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($group_id, Request $request)
    {
        $user_id = auth() -> user() -> id;
        $group = Group::findOrFail($group_id);

        // only group moderator can perform this action
        if ($group -> moderator_id != $user_id) { unset($user_id, $group); abort(403); }

        // basic validation
        $this -> validate($request, [
            'title' => 'required | max: 35',
            'date' => 'required | date | after_or_equal: today',
            'start_time' => 'required | regex: /^[0-9:]+$/',
            'end_time' => 'required | regex: /^[0-9:]+$/',
            'description' => 'required'
        ]);

        // time validation
        $start_time = date_create($request -> input('start_time'));
        $end_time = date_create($request -> input('end_time'));
        $date = date_create($request -> input('date'));
        $data = array('id' => $group_id, 'name' => $group -> name);

        if ($start_time === false || $end_time === false || $date === false)
        {
            return view('groups.events.create') -> with('data', $data) -> with('validation_failed', __('messages.event_format_error'));
        }


        $start_time =  date_format($start_time, 'H:i:s');
        $end_time =  date_format($end_time, 'H:i:s');
        $date = date_format($date, 'Y-m-d');

        // timestamp validation
        unset($user_id, $group);
        // not allowed to create new events in the past
        if ($date == date('Y-m-d') && $start_time < date("H:i:s"))
        {
            return view('groups.events.create') -> with('data', $data) -> with('validation_failed', __('messages.event_past'));
        }
        // incorrect sequence
        elseif ($start_time >= $end_time)
        {
            return view('groups.events.create') -> with('data', $data) -> with('validation_failed', __('messages.event_start_time_error'));
        }

        // validation - OK, insert data into the DB
        $event = new GroupEvent;
        $event -> group_id = $group_id;
        $event -> title = $request -> input('title');
        $event -> date = $date;
        $event -> start_time = $start_time;
        $event -> end_time = $end_time;
        $event -> description = $request -> input('description');
        $event -> save();

        return redirect() -> action('GroupController@show', ['id' => $group_id]) -> with('success', __('messages.event_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($group_id, $id)
    {
        $user_id = auth() -> user() -> id;
        $group = Group::findOrFail($group_id);
        // get user status
        $user_status = $group -> userRelations() -> wherePivot('user_id', $user_id) -> first()['pivot']['status'];

        // only group members can access the event
        if (sizeof($user_status) == 0)
        {
            if ($group -> moderator_id != $user_id)
            {
                unset($user_id, $group, $user_status);
                abort(403); 
            }
        }
        elseif ($user_status != 'a') 
        { 
            unset($user_id, $group, $user_status);
            abort(403); 
        }

        $event = GroupEvent::findOrFail($id);

        unset($user_id, $group, $user_status);
        return view('groups.events.show') -> with('event', $event);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($group_id, $id)
    {
        $user_id = auth() -> user() -> id;
        $group = Group::findOrFail($group_id);

        // only group moderator can perform this action
        if ($group -> moderator_id != $user_id) { unset($user_id, $group); abort(403); }

        $event = GroupEvent::findOrFail($id);
        unset($user_id, $group);
        return view('groups.events.edit') -> with('event', $event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $group_id, $id)
    {
        $user_id = auth() -> user() -> id;
        $group = Group::findOrFail($group_id);
        $event = GroupEvent::findOrFail($id);

        // only group moderator can perform this action
        if ($group -> moderator_id != $user_id) { unset($user_id, $group); abort(403); }

        // basic validation
        $this -> validate($request, [
            'title' => 'required | max: 35',
            'date' => 'required | date',
            'start_time' => 'required | regex: /^[0-9:]+$/',
            'end_time' => 'required | regex: /^[0-9:]+$/',
            'description' => 'required'
        ]);

        $start_time = date_create($request -> input('start_time'));
        $end_time = date_create($request -> input('end_time'));
        $date = date_create($request -> input('date'));
        $data = array('id' => $group_id, 'name' => $group -> name);
        if ($start_time === false || $end_time === false || $date === false)
        {
            return view('groups.events.edit') -> with('event', $event) -> with('validation_failed', __('messages.event_format_error'));
        }


        $start_time =  date_format($start_time, 'H:i:s');
        $end_time =  date_format($end_time, 'H:i:s');
        $date = date_format($data, 'Y-m-d');
        

        // timestamp validation
        unset($user_id, $group);
        // not allowed to create new events in the past
        if ($date < date("Y-m-d") || $start_time < date("H:i:s"))
        {
            return view('groups.events.edit') -> with('event', $event) -> with('validation_failed', __('messages.event_past'));
        }
        // correct sequence
        elseif ($start_time >= $end_time)
        {
            return view('groups.events.edit') -> with('event', $event) -> with('validation_failed', __('messages.event_start_time_error'));
        }


        // validation - OK, insert data into the DB
        $event -> group_id = $group_id;
        $event -> title = $request -> input('title');
        $event -> date = $date;
        $event -> start_time = $start_time;
        $event -> end_time = $end_time;
        $event -> description = $request -> input('description');
        $event -> save();

        return redirect() -> action('GroupController@show', ['id' => $group_id]) -> with('success', $event -> title . __('messages.event_update_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($group_id, $id, Request $request)
    {
        $user_id = auth() -> user() -> id;
        $group = Group::findOrFail($group_id);
        $event = GroupEvent::findOrFail($id);

        // only group moderator can perform this action
        if ($group -> moderator_id != $user_id) { unset($user_id, $group); abort(403); }

        $event_name = $event -> title;

        $event -> delete();
        unset($user_id, $group);

        return redirect() -> action('GroupController@show', ['id' => $group_id]) -> with('success', $event_name . __('messages.event_delete'));
    }




    // API - get events on a particular day
    public function getEventsOnDate(Request $request, $id)
    {
        $str_date = $request -> input('year') . '-' . $request -> input('month') . '-' . $request -> input('day');

        $group = Group::findOrFail($id) -> groupEvents() -> where('date', $str_date)
                        -> orderBy('date', 'asc') -> orderBy('start_time', 'asc') -> get();

        return ResourceGroupEvent::collection($group);
    }
}