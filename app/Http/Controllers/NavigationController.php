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
     * Show the home page 1) authenticated users - calendar; 2) guests - facts about the app.
     *
     * @return \Illuminate\Http\Response
     */
    public function homepage(Request $request)
    {
        if (!Auth::check()) return view('pages.start_guest');
        else
        {
            // check block
            if (auth() -> user() -> status != 'a')
            {
                $request -> session() -> flush();
                return redirect() -> action('NavigationController@homepage');
            }

            // current month should be displayed by default
            $current_day = getdate();
            $current_year = $current_day['year'];
            $current_month = $current_day['mon'];
            $current_day = $current_day['mday'];

            // get events of the user
            $user = User::find(auth() -> user() -> id);
            // get personal user events
            $user_events = $user -> userEvents() -> where('date', '>=', date('Y-m-d'))
            -> orderBy('date', 'asc') -> orderBy('start_time', 'asc') -> take(5) -> get();

            // get group events
            // get IDs of the groups where the user is present
            $group_events = $user -> groupEvents() -> take(5) -> get();

            return view('pages.start_auth') -> with('date', ['day' => $current_day, 'month' => $current_month, 'year' => $current_year])
            -> with('u_events', $user_events) -> with('g_events', $group_events);
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

        
        return redirect() -> action('NavigationController@contact') -> with('success', __('messages.navigation_post_contact'));
    }
}
