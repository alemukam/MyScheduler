<?php

namespace App\Http\Controllers;
use App\User;
use App\GroupEvent;
use App\AdminNotification;
use App\UserGroupRelation;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class NavigationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['homepage', 'about', 'contact', 'post_contact']]);
    }



    /**
     * Show the home page 1) authenticated users - calendar; 2) guests - facts about the app.
     *
     * @return \Illuminate\Http\Response
     */
    public function homepage()
    {
        if (!Auth::check()) return view('pages.start_guest');
        else
        {
            // current month should be displayed by default
            $current_day = getdate();
            $current_year = $current_day['year'];
            $current_month = $current_day['mon'];
            $current_day = $current_day['mday'];

            // get events of the user
            $id = auth() -> user() -> id;
            $user_events = User::find($id) -> userEvents() -> take(5) -> get();
            $groups = User::find($id) -> groupRelations;
            //return $groups;
            //$groups = UserGroupRelation::where('user_id', $id) -> pluck('group_id') -> toArray();
            //$group_events = GroupEvent::where('')

            return view('pages.start_auth') -> with('date', ['day' => $current_day, 'month' => $current_month, 'year' => $current_year])
            -> with('type', 'user');
        }
    }



    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function about(Request $request)
    {
        $uri = $request->path();
        return view('pages.about') -> with('uri', $uri);
    }



    /**
     * Display contact information about the community
     *
     * @return \Illuminate\Http\Response
     */
    public function contact(Request $request)
    {
        $uri = $request->path();
        return view('pages.contact') -> with('uri', $uri);
    }



    /**
     * Insert a new message from the site (contact form)
     *
     * @return \Illuminate\Http\Response
     */
    public function post_contact(Request $request)
    {
        // validate the input - all fields are mandatory
        $this -> validate($request, [
            'name' => 'required | max: 64',
            'email' => 'required | email | max: 64',
            'title' => 'required | max: 32',
            'message' => 'required'
        ]);

        $notif = new AdminNotification;
        $notif -> name = $request -> input('name');
        $notif -> email = $request -> input('email');
        $notif -> title = $request -> input('title');
        $notif -> message = $request -> input('message');
        $notif -> status = 'n';
        $notif -> type = 0;
        $notif -> save();

        return redirect() -> action('NavigationController@contact') -> with('success', 'Thank you. Message has been sent. We will reply shortly');
    }
}
