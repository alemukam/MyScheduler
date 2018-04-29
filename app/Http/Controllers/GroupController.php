<?php

namespace App\Http\Controllers;

use App\User;
use App\Group;
use App\AdminNotification;
use App\UserGroupRelation;
use Illuminate\Http\Request;

class GroupController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // get uri for a correct rendering of the "active" class
        $uri = $request -> path();

        // get all approved groups from the data base
        $groups = Group::where('status', 'a') -> get();
        return view('groups.index') -> with('uri', $uri) -> with('groups', $groups);
    }


    /**
     * Display a dashboard of groups.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $user_id = auth() -> user() -> id;
        $user_role = User::find($user_id) -> first()['user_role'];

        // 1. Whether of not the user is the moderator - find his or her groups
        // 1.1. Public groups
        if (strtolower($user_role) == 'moderator')
        {
            $groups_approved = Group::where('moderator_id', $user_id) -> where('status', 'a') -> get(); // approved groups
            $groups_pending = Group::where('moderator_id', $user_id) -> where('status', 'p') -> get(); // awaiting approval groups
            $groups_rejected = Group::where('moderator_id', $user_id) -> where('status', 'r') -> get(); // rejected groups

            $groups_data = array('approved' => $groups_approved, 'pending' => $groups_pending, 'rejected' => $groups_rejected);
        }
        else $groups_data = null;

        // 2. Find membership in goups
        $membership_approved = UserGroupRelation::where('user_id', $user_id) -> where('status', 'a') -> get();
        $membership_pending = UserGroupRelation::where('user_id', $user_id) -> where('status', 'p') -> get();

        $membership_data = array('approved' => $membership_approved, 'pending' => $membership_pending);

        $data = array('moderator' => $groups_data, 'member' => $membership_data);
        return view('groups.dashboard') -> with('data', $data);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('groups.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this -> validate($request, [
            'name' => 'required | max: 32',
            'description' => 'required',
            'group_img' => 'image | nullable | max: 1999'
        ]);



        $id = auth() -> user() -> id;
        $user = User::find($id);
        // handle file upload
        if ($request -> hasFile('group_img'))
        {
            // get filename with the extension
            $fileNameWithExt = $request -> file('group_img') -> getClientOriginalName();
            // get just file name
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // get just extension
            $ext = $request -> file('group_img') -> getClientOriginalExtension();
            $fileNameToStore = $id . $fileName . '_' . time() . '.' . $ext;
        
            // upload the image
            $path = $request -> file('group_img') -> storeAs('public/imgs_g/', $fileNameToStore);
        }
        else $fileNameToStore = 'no_image.jpg';

        // 1.1. it is not allowed to create two groups with the same name
        $checker = Group::where('name', $request -> input('name')) -> get();
        if (sizeof($checker) > 1) return view('groups.create') -> with('validation_failed', 'Group with this name already exists');

        // 1.2. create a new pending group
        $group = new Group;
        $group -> name = $request -> input('name');
        $group -> description = $request -> input('description');
        $group -> img = $fileNameToStore;
        $group -> moderator_id = $id;
        $group -> status = 'p'; // p - pending (should be approved by the administrator)
        $group -> save();



        // 2. create a new task for the administrator
        $notif = new AdminNotification;
        $notif -> name = $user -> name;
        $notif -> email = $user -> email;
        $notif -> title = 'New Group Request';
        $notif -> message = 'Please approve of my new group.';
        $notif -> status = 'p';
        $notif -> type = '1';
        $notif -> group_id = $group -> id;
        $notif -> save();



        // 3. update a user role to moderator (able to see the groups dashboard)
        // but do not update if the user is the administrator or already moderator
        if ($user -> user_role == 'basic')
        {
            $user -> user_role = 'moderator';
            $user -> save();
        }
        

        // 4. go to the dashboard with all groups of the user
        return redirect('/dashboard/groups');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $group = Group::findOrFail($id);
        $user_id = auth() -> user() -> id;

        // groups with status A (appvoed) are available to the public
        if (strtolower($group -> status) != 'a' && $group -> moderator_id != $user_id) abort(403);

        // get user status
        $user_status = $group -> userRelations() -> wherePivot('user_id', $user_id) -> first()['pivot']['status'];

        // get information about the group
        // for all members and moderator
        if ($user_status == 'a' || $group -> moderator_id == $user_id)
        {
            $group_events = $group['groupEvents'] -> where('date', '>=', date("Y-m-d")) -> sortBy('date') -> sortBy('start_time') -> take(5);
        }
        else $group_events = null;
        // only for admin
        if ($user_id == $group -> moderator_id)
        {
            $new_requests = $group -> userRelations() -> wherePivot('status', 'p') -> get();
            $blocked_users = $group -> userRelations() -> wherePivot('status', 'b') -> get();
            $users = $group -> userRelations() -> wherePivot('status', 'a') -> get();
        }
        else $new_requests = $blocked_users = $users = null;


        $data = array('user_status' => $user_status, 'new_requests' => $new_requests, 'blocked_users' => $blocked_users, 'users' => $users);
        return view('groups.show') -> with('group', $group) -> with('group_events', $group_events) -> with('data', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // access control - only the moderator can edit the group

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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    // Moderator events for membership requests
    // $id - table UserGroupRelations; $group_id - id of the subject group
    // accept membership
    public function approveOfRequest($id, $group_id)
    {
        $moderator = Group::find($group_id) -> select('moderator_id') -> first()['moderator_id'];
        // only the moderator can approve of the membership
        if (auth() -> user() -> id == $moderator)
        {
            $membership = UserGroupRelation::find($id);
            $membership -> status = 'a'; // a - status is approved
            $membership -> save();

            return redirect() -> action('GroupController@show', ['id' => $group_id]) -> with('success', 'Membership approved for ' . $membership -> user() -> first()['name']);
        }
        else return redirect() -> action('GroupController@show', ['id' => $group_id]);
    }




    // reject membership
    public function rejectRequest(Request $request, $id, $group_id)
    {
        $moderator = Group::find($group_id) -> select('moderator_id') -> first()['moderator_id'];
        // only the moderator can reject the membership
        if (auth() -> user() -> id == $moderator)
        {
            $membership = UserGroupRelation::find($id);
            $membership -> delete(); // for rejected - delete a row from the table

            // mod_action - isset == false when the moderator rejects the initial request
            if ($request -> input('mod_action') !== null)
            {
                if (strtolower($request -> input('mod_action')) == 'unblock') $msg = 'User ' . $membership -> user() -> first()['name'] . ' has been unblocked';
                elseif (strtolower($request -> input('mod_action')) == 'expel') $msg = 'User ' . $membership -> user() -> first()['name'] . ' has been expelled from the group';
                else $msg = '';
            }
            else $msg = 'Membership rejected for ' . $membership -> user() -> first()['name'];


            // display the notification to the moderator
            return redirect() -> action('GroupController@show', ['id' => $group_id]) -> with('success', $msg);
        }
        else return redirect() -> action('GroupController@show', ['id' => $group_id]);
    }




    // block the user
    public function blockUser(Request $request, $id, $group_id)
    {
        $moderator = Group::find($group_id) -> select('moderator_id') -> first()['moderator_id'];
        // only the moderator can block the user
        if (auth() -> user() -> id == $moderator)
        {
            $membership = UserGroupRelation::find($id);
            $membership -> status = 'b'; // b - status for blocked
            $membership -> save();


            // mod_action - isset == false when the moderator block the user by the initial request
            if ($request -> input('mod_action') !== null)
            {
                if (strtolower($request -> input('mod_action')) == 'expel') $msg = 'User ' . $membership -> user() -> first()['name'] . ' has been expelled from the group and blocked';
                else $msg = '';
            }
            else $msg = 'User ' . $membership -> user() -> first()['name'] . ' has been blocked';


            // display the notification of that the moderator has performed
            return redirect() -> action('GroupController@show', ['id' => $group_id]) -> with('success', $msg);
        }
        else return redirect() -> action('GroupController@show', ['id' => $group_id]);
    }




    // process a request for membership
    // id - group_id
    public function newJoiner($id)
    {
        $user_id = auth() -> user() -> id;

        // process the request for membership
        $req = new UserGroupRelation;
        $req -> user_id = $user_id;
        $req -> group_id = $id;
        $req -> status = 'p'; // p = pending
        $req -> save();

        // redirect to the dashboard
        return redirect() -> action('GroupController@dashboard') -> with('success', 'Membership request has been sent');
    }
}
