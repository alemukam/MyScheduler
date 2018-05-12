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
        $this -> middleware('auth');
        $this -> middleware('check_block');
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
    public function block($id, Request $request)
    {
        if (auth() -> user() -> user_role != 'admin') return redirect() -> action('DashboardController@show');
        $lang = ($request -> session() -> has('lang') ? $request -> session() -> get('lang') : 'en');

        $user = User::findOrFail($id);

        $msg = '';
        // not possible to block the admin
        if (strtolower($user -> user_role) == 'admin')
        {
            switch ($lang) 
            {
                case 'jp':
                    $msg = '禁じられている';
                    break;
                case 'en':
                default:
                    $msg = 'Not Allowed';
            }
            return redirect() -> action('DashboardController@show') -> with('error', $msg);
        }

        $user -> status = 'b'; // b - blocked
        $user -> save();

        switch ($lang) 
        {
            case 'jp':
                $msg = 'ユーザー' . $user -> name . 'がブロックされました';
                break;
            case 'en':
            default:
                $msg = 'User ' . $user -> name . ' has been blocked.';
        }
        return redirect() -> action('AdminController@findUsers') -> with('success', $msg);
    }



    /**
     * Unblock the user
     *
     * @return \Illuminate\Http\Response
     */
    public function unblock($id, Request $request)
    {
        if (auth() -> user() -> user_role != 'admin') return redirect() -> action('DashboardController@show');
        $lang = ($request -> session() -> has('lang') ? $request -> session() -> get('lang') : 'en');

        $user = User::findOrFail($id);

        $msg = '';
        // not possible to block the admin
        if (strtolower($user -> user_role) == 'admin')
        {
            switch ($lang) 
            {
                case 'jp':
                    $msg = '禁じられている';
                    break;
                case 'en':
                default:
                    $msg = 'Not Allowed';
            }
            return redirect() -> action('DashboardController@show') -> with('error', $msg);
        }

        $user -> status = 'a'; // a - active
        $user -> save();

        switch ($lang) 
        {
            case 'jp':
                $msg = 'ユーザー' . $user -> name . 'がブロック解除されました';
                break;
            case 'en':
            default:
                $msg = 'User ' . $user -> name . ' has been unblocked.';
        }
        return redirect() -> action('AdminController@findUsers') -> with('success', $msg);
    }



    // dealing with messages
    /**
     * Mark a message as resolved
     *
     * @return \Illuminate\Http\Response
     */
    public function resolveMessage($id, Request $request)
    {
        if (auth() -> user() -> user_role != 'admin') return redirect() -> action('DashboardController@show');
        $lang = ($request -> session() -> has('lang') ? $request -> session() -> get('lang') : 'en');

        $msg = AdminNotification::findOrFail($id);
        $msg -> status = 'r'; // r - resolved
        $msg -> save();

        // if at was a request for group approval - update status of the group
        if ($msg -> type == 0)
        {
            unset($msg);
            $msg = '';
            switch ($lang) 
            {
                case 'jp':
                    $msg = 'インシデント解決済み';
                    break;
                case 'en':
                default:
                    $msg = 'Incident resolved';
            }

            return redirect() -> action('DashboardController@show') -> with('success', $msg);
        }

        
        $group = $msg -> group;
        $group -> status = 'a'; // a - approved
        $group -> save();
        unset($group, $msg);

        $msg = '';
            switch ($lang) 
            {
                case 'jp':
                    $msg = 'グループが承認されました';
                    break;
                case 'en':
                default:
                    $msg = 'Group has been approved';
            }
        return redirect() -> action('DashboardController@show') -> with('success', $msg);
    }



    /**
     * Mark a message as resolved
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteMessage($id, Request $request)
    {
        if (auth() -> user() -> user_role != 'admin') return redirect() -> action('DashboardController@show');
        $lang = ($request -> session() -> has('lang') ? $request -> session() -> get('lang') : 'en');

        $msg = AdminNotification::findOrFail($id);
        $msg -> status = 'd'; // d - deleted
        $msg -> save();

        // if at was a request for group approval - update status of the group
        if ($msg -> type == 0)
        {
            unset($msg);
            $msg = '';
            switch ($lang) 
            {
                case 'jp':
                    $msg = 'インシデントが破棄された';
                    break;
                case 'en':
                default:
                    $msg = 'Incident discarded';
            }
            return redirect() -> action('DashboardController@show') -> with('success', $msg);
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

        $msg = '';
        switch ($lang) 
        {
            case 'jp':
                $msg = 'グループは拒否されました。 通知は司会者に送信されます。';
                break;
            case 'en':
            default:
                $msg = 'Group has been rejected. Notification is sent to the moderator.';
        }
        return redirect() -> action('DashboardController@show') -> with('success', $msg);
    }
}
