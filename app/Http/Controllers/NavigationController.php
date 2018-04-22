<?php

namespace App\Http\Controllers;
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
        $this->middleware('auth', ['except' => ['homepage', 'about', 'contact', 'post_contact',]]);
    }

    /**
     * Show the home page 1) authenticated users - calendar; 2) guests - facts about the app.
     *
     * @return \Illuminate\Http\Response
     */
    public function homepage()
    {
        if (!Auth::check()) return view('pages.start_guest');
        else{
            $current_day = getdate();
            $current_year = $current_day['year'];
            $current_month = $current_day['mon'];
            $current_day = $current_day['mday'];

            return view('pages.start_auth') -> with('date', ['day' => $current_day, 'month' => $current_month, 'year' => $current_year]);
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
    public function post_contact()
    {
        //fgegtregtergtretgergtews
        return view('pages.contact');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        return view('pages.dashboard');
    }
}
