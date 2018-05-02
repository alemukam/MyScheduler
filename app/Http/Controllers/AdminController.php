<?php

namespace App\Http\Controllers;

use App\User;
use App\AdminNotification;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }



    /**
     * Administrator only! Find registered users
     *
     * @return \Illuminate\Http\Response
     */
    public function findUsers()
    {
        if (strtolower(auth() -> user() -> user_role) != 'admin') abort(403);

        return view('pages.findUsers') -> with('uri', 'finduser');
    }



    /**
     * Administrator only! Find registered users
     *
     * @return \Illuminate\Http\Response
     */
    public function performFindUsers(Request $request)
    {
        if (strtolower(auth() -> user() -> user_role) != 'admin') abort(403);

        // provided name is not empty (max allowed length of the name is 32 bytes)
        $this -> validate($request, [
            'name' => 'required | max: 32'
        ]);

        $users = User::where('name', 'LIKE', '%'. $request -> input('name') .'%') -> where('user_role', '<>', 'admin') -> select('id', 'name', 'email', 'status') -> get();

        return view('pages.findUsers') -> with('uri', 'finduser') -> with('user_find_result', $users);
    }



    /**
     * Block the user
     *
     * @return \Illuminate\Http\Response
     */
    public function block($id)
    {
        if (auth() -> user() -> user_role != 'admin') return redirect() -> action('DashboardController@show');

        $user = User::findOrFail($id);

        // not possible to block the admin
        if (strtolower($user -> user_role) == 'admin')
        {
            return redirect() -> action('DashboardController@show') -> with('error', 'Not Allowed');
        }

        $user -> status = 'b'; // b - blocked
        $user -> save();

        return redirect() -> action('AdminController@findUsers') -> with('success', 'User ' . $user -> name . ' has been blocked.');
    }



    /**
     * Unblock the user
     *
     * @return \Illuminate\Http\Response
     */
    public function unblock($id)
    {
        if (auth() -> user() -> user_role != 'admin') return redirect() -> action('DashboardController@show');

        $user = User::findOrFail($id);

        // not possible to block the admin
        if (strtolower($user -> user_role) == 'admin')
        {
            return redirect() -> action('DashboardController@show') -> with('error', 'Not Allowed');
        }

        $user -> status = 'a'; // a - active
        $user -> save();

        return redirect() -> action('AdminController@findUsers') -> with('success', 'User ' . $user -> name . ' has been unblocked.');
    }



    // dealing with messages
    /**
     * Mark a message as resolved
     *
     * @return \Illuminate\Http\Response
     */
    public function resolveMessage($id)
    {
        if (auth() -> user() -> user_role != 'admin') return redirect() -> action('DashboardController@show');

        $msg = AdminNotification::findOrFail($id);
        $msg -> status = 'r'; // r - resolved
        $msg -> save();

        // if at was a request for group approval - update status of the group
        if ($msg -> type == 0)
        {
            unset($msg);
            return redirect() -> action('DashboardController@show') -> with('success', 'Incident resolved');
        }

        
        $group = $msg -> group;
        $group -> status = 'a'; // a - approved
        $group -> save();
        unset($group, $msg);

        return redirect() -> action('DashboardController@show') -> with('success', 'Group has been approved');
    }



    /**
     * Mark a message as resolved
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteMessage($id, Request $request)
    {
        if (auth() -> user() -> user_role != 'admin') return redirect() -> action('DashboardController@show');

        $msg = AdminNotification::findOrFail($id);
        $msg -> status = 'd'; // d - deleted
        $msg -> save();

        // if at was a request for group approval - update status of the group
        if ($msg -> type == 0)
        {
            unset($msg);
            return redirect() -> action('DashboardController@show') -> with('success', 'Incident discarded');
        }


        $this -> validate($request, [
            'reason' => 'required'
        ]);

        $msg -> admin_message = $request -> input('reason');
        $msg -> save();

        $group = $msg -> group;
        $group -> status = 'r'; // r - rejected
        $group -> save();
        unset($group, $msg);

        return redirect() -> action('DashboardController@show') -> with('success', 'Group has been rejected. Notification is sent to the moderator.');
    }
}
