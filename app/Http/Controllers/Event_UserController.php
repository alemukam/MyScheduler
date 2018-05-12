<?php

namespace App\Http\Controllers;

use App\User;
use App\UserEvent;
use App\Http\Resources\GroupEvent_GroupName as ResourceGroupEvent;
use App\Http\Resources\UserEvent as ResourceUserEvent;

use Illuminate\Http\Request;

class Event_UserController extends Controller
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
    public function create()
    {
        return view('users.events.create');
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $lang = ($request -> session() -> has('lang') ? $request -> session() -> get('lang') : 'en');
        $msg = '';

        // basic validation
        $this -> validate($request, [
            'title' => 'required | max: 35',
            'date' => 'required | date | after_or_equal: today',
            'start_time' => 'required',
            'end_time' => 'required',
            'description' => 'required'
        ]);

        // dates with the correct formats
        $start_time =  date_format(date_create($request -> input('start_time')), 'H:i:s');
        $end_time =  date_format(date_create($request -> input('end_time')), 'H:i:s');
        $date = date_format(date_create($request -> input('date')), 'Y-m-d');


        // 1. incorrect input format
        if ($start_time === false || $end_time === false || $date === false)
        {
            switch ($lang) 
            {
                case 'jp':
                    $msg = '時刻形式が正しくありません。';
                    break;
                case 'en':
                default:
                    $msg = 'Incorrect time format.';
            }
            return view('users.events.create') -> with('validation_failed', $msg);
        }
        // 2. not allowed to create new events in the past
        if ($date == date('Y-m-d') && $start_time < date("H:i:s"))
        {
            switch ($lang)
            {
                case 'jp':
                    $msg = '過去のイベントは許可されていません。';
                    break;
                case 'en':
                default:
                    $msg = 'Events in the past are not allowed.';
            }
            return view('users.events.create') -> with('validation_failed', $msg);
        }
        // 3. incorrect sequence
        elseif ($start_time >= $end_time)
        {
            switch ($lang) 
            {
                case 'jp':
                    $msg = '開始時刻は終了時刻の前にすることはできません。';
                    break;
                case 'en':
                default:
                    $msg = 'Start time cannot be before end time.';
            }
            return view('users.events.create') -> with('validation_failed', $msg);
        }


        // validation - OK, insert data into the DB
        $event = new UserEvent;
        $event -> user_id = auth() -> user() -> id;
        $event -> title = $request -> input('title');
        $event -> date = $date;
        $event -> start_time = $start_time;
        $event -> end_time = $end_time;
        $event -> description = $request -> input('description');
        $event -> save();


        switch ($lang) 
        {
            case 'jp':
                $msg = '新しいイベントが作成されました。';
                break;
            case 'en':
            default:
                $msg = 'New event has been created.';
        }
        unset($event, $date, $start_time, $end_time);
        return redirect() -> action('NavigationController@homepage') -> with('success', $msg);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // only the owner of the event can view the event
        $event = UserEvent::findOrFail($id);

        if ($event -> user_id != auth() -> user() -> id) { unset($event); abort(403); }

        return view('users.events.show') -> with('event', $event);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // only the owner of the event can edit the event
        $event = UserEvent::findOrFail($id);

        if ($event -> user_id != auth() -> user() -> id) { unset($event); abort(403); }

        return view('users.events.edit') -> with('event', $event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $lang = ($request -> session() -> has('lang') ? $request -> session() -> get('lang') : 'en');
        $msg = '';

        // validate the uri input
        $event = UserEvent::findOrFail($id);
        // validate access rights - only the owner can update the event
        if ($event -> user_id != auth() -> user() -> id) { unset($event); abort(403); }

        // basic validation
        $this -> validate($request, [
            'title' => 'required | max: 35',
            'date' => 'required | date | after_or_equal: today',
            'start_time' => 'required',
            'end_time' => 'required',
            'description' => 'required'
        ]);

        // dates with the correct formats
        $start_time =  date_format(date_create($request -> input('start_time')), 'H:i:s');
        $end_time =  date_format(date_create($request -> input('end_time')), 'H:i:s');
        $date = date_format(date_create($request -> input('date')), 'Y-m-d');


        // 1. incorrect input format
        if ($start_time === false || $end_time === false || $date === false)
        {
            switch ($lang) 
            {
                case 'jp':
                    $msg = '時刻形式が正しくありません。';
                    break;
                case 'en':
                default:
                    $msg = 'Incorrect time format.';
            }
            return view('users.events.edit') -> with('event', $event) -> with('validation_failed', $msg);
        }
        // 2. not allowed to create new events in the past
        if ($date == date('Y-m-d') && $start_time < date("H:i:s"))
        {
            switch ($lang) 
            {
                case 'jp':
                    $msg = '過去のイベントは許可されていません。';
                    break;
                case 'en':
                default:
                    $msg = 'Events in the past are not allowed.';
            }
            return view('users.events.edit') -> with('event', $event) -> with('validation_failed', $msg);
        }
        // 3. incorrect sequence
        elseif ($start_time >= $end_time)
        {
            switch ($lang) 
            {
                case 'jp':
                    $msg = '開始時刻は終了時刻の前にすることはできません。';
                    break;
                case 'en':
                default:
                    $msg = 'Start time cannot be before end time.';
            }
            return view('users.events.edit') -> with('event', $event) -> with('validation_failed', $msg);
        }


        // validation - OK, insert data into the DB
        $event -> user_id = auth() -> user() -> id;
        $event -> title = $request -> input('title');
        $event -> date = $date;
        $event -> start_time = $start_time;
        $event -> end_time = $end_time;
        $event -> description = $request -> input('description');
        $event -> save();


        $event_name = $event -> title;
        unset($event, $date, $start_time, $end_time);

        switch ($lang) 
        {
            case 'jp':
                $msg = 'イベント"'. $event_name .'"が更新されました。';
                break;
            case 'en':
            default:
                $msg = 'Event "'. $event_name .'" has been updated.';
        }
        return redirect() -> action('NavigationController@homepage') -> with('success', $msg);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $lang = ($request -> session() -> has('lang') ? $request -> session() -> get('lang') : 'en');
        $msg = '';

        // validate the uri input
        $event = UserEvent::findOrFail($id);
        // validate access rights - only the owner can delete the event
        if ($event -> user_id != auth() -> user() -> id) { unset($event); abort(403); }


        $event_name = $event -> title;
        $event -> delete();

        switch ($lang)
        {
            case 'jp':
                $msg = 'イベント"'. $event_name .'"が削除されました。';
                break;
            case 'en':
            default:
                $msg = 'Event "'. $event_name .'" has been deleted.';
        }
        return redirect() -> action('NavigationController@homepage') -> with('success', $msg);
    }



    // API - get events on a particular day
    public function getEventsOnDate(Request $request, $id)
    {
        $str_date = $request -> input('year') . '-' . $request -> input('month') . '-' . $request -> input('day');

        $user = User::findOrFail($id);
        // User personal events
        $user_events = $user -> userEvents() -> where('date', $str_date) -> get();
        // User group events
        $group_events = $user -> groupEvents() -> join('groups', 'groups.id', '=', 'group_events.group_id') -> where('date', $str_date)
        -> select('group_events.*', 'groups.name as group_name') -> get();
        unset($user, $str_date);

        // merge results
        return array('user' => ResourceUserEvent::collection($user_events), 'group' => ResourceGroupEvent::collection($group_events));
    }
}
