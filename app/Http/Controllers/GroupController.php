<?php

namespace App\Http\Controllers;

use App\User;
use App\Group;
use App\AdminNotification;
use App\UserGroupRelation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
{

    private $description_length = 100;

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // get uri for a correct rendering of the "active" class
        $uri = $request -> path();

        // get all approved groups from the data base
        if (auth() -> user() -> user_role == 'admin') $groups = Group::all();
        else $groups = Group::where('status', 'a') -> get();

        return view('groups.index') -> with('uri', $uri) -> with('groups', $groups) -> with('length', $this -> description_length);
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
        if (strtolower($user_role) != 'basic')
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
        return view('groups.dashboard') -> with('data', $data) -> with('length', $this -> description_length);
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
            'name' => 'required|max:32', // unique:groups,name - implemented in custom code due to the message text
            'description' => 'required',
            'group_img' => 'image|nullable|max:1999'
        ]);



        // 1.1. it is not allowed to create two groups with the same name
        $checker = Group::where('name', $request -> input('name')) -> get();
        if (sizeof($checker) > 0)
        {
            return view('groups.create') -> with('validation_failed', __('messages.group_store_failed'));
        }


        
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
        $notif -> status = 'n';
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
        
        unset($id, $user, $checker, $notif, $group);

        // 4. go to the dashboard with all groups of the user
        return redirect('/dashboard/groups') -> with('success', __('messages.group_store_success'));
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
        if (auth() -> user() -> user_role != 'admin')
        {
            if (strtolower($group -> status) != 'a' && $group -> moderator_id != $user_id) abort(404);
        }
        

        // get user status
        $user_status = $group -> userRelations() -> wherePivot('user_id', $user_id) -> first()['pivot']['status'];

        // get information about the group
        // for all members and moderator
        if ($user_status == 'a' || $group -> moderator_id == $user_id)
        {
            $group_events = $group -> groupEvents() -> where('date', '>=', date("Y-m-d"))
            -> orderBy('date', 'asc') -> orderBy('start_time', 'asc') -> take(5) -> get();
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
        $user_id = auth() -> user() -> id;
        $group = Group::findOrfail($id);

        // access control - only the moderator can edit the group
        if (strtolower($group -> status) == 'p' && $user_id != $group -> moderator_id) abort(404);
        if ($user_id != $group -> moderator_id) abort(403);

        return view('groups.edit') -> with('group' , $group);

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
        $user_id = auth() -> user() -> id;
        $group = Group::findOrfail($id);

        // access control - only the moderator can edit the group
        if (strtolower($group -> status) == 'p' && $user_id != $group -> moderator_id) abort(404);
        if ($user_id != $group -> moderator_id) abort(403);


        // 1.1. it is not allowed to create two groups with the same name
        $checker = Group::where('name', $request -> input('name')) -> get();
        if (sizeof($checker) > 0)
        {
            return view('groups.create') -> with('validation_failed', __('messages.group_store_failed'));
        }


        // the same validation as on store
        $this -> validate($request, [
            'name' => 'required | max: 32',
            'description' => 'required',
            'group_img' => 'image | nullable | max: 1999'
        ]);



        $user = User::find($user_id);
        // handle file upload (only if a new file is provided)
        if ($request -> hasFile('group_img'))
        {
            // 1. delete the old file
            if (strtolower($group -> img) != 'no_image.jpg') Storage::delete('public/imgs_g/' . $group -> img);

            // 2. process the new file
            // get filename with the extension
            $fileNameWithExt = $request -> file('group_img') -> getClientOriginalName();
            // get just file name
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // get just extension
            $ext = $request -> file('group_img') -> getClientOriginalExtension();
            $fileNameToStore = $user_id . $fileName . '_' . time() . '.' . $ext;
        
            // upload the image
            $path = $request -> file('group_img') -> storeAs('public/imgs_g/', $fileNameToStore);
        }


        // update the group info
        $group -> name = $request -> input('name');
        $group -> description = $request -> input('description');
        // update the image only if a new image is provided
        if ($request -> hasFile('group_img')) $group -> img = $fileNameToStore;
        // set status to "pending" only if the request had been rejected by the administrator
        // update a request to the administrator
        $stats = array('p', 'r');
        if (in_array(strtolower($group -> status), $stats))
        {
            $group -> status = 'p';

            $admin_req = $group -> adminNotification() -> get();
            if (sizeof($admin_req) > 0)
            {
                // initial request is available
                $admin_req = $admin_req -> first();
                $admin_req -> title = 'Updated Group Request';
                $admin_req -> message = 'I have made the corrections. Could you please review the group one more time ?';
                $admin_req -> status = 'n';
                $admin_req -> save();
            }
            else
            {
                // initial request has been deleted
                $admin_req = new AdminNotification;
                $admin_req -> name = $user -> name;
                $admin_req -> email = $user -> email;
                $admin_req -> title = 'Updated Group Reques';
                $admin_req -> message = 'I have made the corrections. Could you please review the group one more time ?';
                $admin_req -> status = 'n';
                $admin_req -> type = '1';
                $admin_req -> group_id = $group -> id;
                $admin_req -> save();
            }
        }
        
        $group -> save();

        unset($group, $admin_req, $user, $user_id);
        return redirect('/dashboard/groups') -> with('success', __('messages.group_update_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $user_id = auth() -> user() -> id;
        $group = Group::findOrfail($id);

        // access control - only the moderator can edit the group
        if (strtolower($group -> status) == 'p' && $user_id != $group -> moderator_id) abort(404);
        if ($user_id != $group -> moderator_id) abort(403);


        // 1. Destroy all foreign keys
        // 1.1. Admin request
        $admin_req = $group -> adminNotification() -> get();
        if (sizeof($admin_req) > 0) foreach($admin_req as $req) $req -> delete();
        unset($admin_req);

        // 1.2. Membership
        $membership = $group -> userRelations() -> get();
        if (sizeof($membership) > 0) foreach($membership as $member) $member['pivot'] -> delete();
        unset($membership);

        // 1.3. Group Events
        $group_events = $group -> groupEvents() -> get();
        if (sizeof($group_events) > 0) foreach($group_events as $event) $event -> delete();
        unset($group_events);


        // 2. Delete the group itself
        // 2.1. Delete profile image
        if (strtolower($group -> img) != 'no_image.jpg') Storage::delete('public/imgs_g/' . $group -> img);

        // 2.2. Delete the group
        $group -> delete();
        unset($group);


        // 3. set role to "basic" if the user does not have any published groups or any requests
        $user_profile = User::find($user_id);
        $count = sizeof($user_profile -> groups() -> get());

        if ($count == 0)
        {
            $user_profile -> user_role = 'basic';
            $user_profile -> save();
        }

        unset($user_profile, $user_id, $count);
        return redirect('/dashboard/groups') -> with('success', __('messages.group_destroy_success'));
    }


    // Moderator events for membership requests
    // $id - table UserGroupRelations; $group_id - id of the subject group
    // accept membership
    public function approveOfRequest($id, $group_id, Request $request)
    {
        $moderator = Group::find($group_id)['moderator_id'];
        // only the moderator can approve of the membership
        if (auth() -> user() -> id == $moderator)
        {
            $membership = UserGroupRelation::find($id);
            $membership -> status = 'a'; // a - status is approved
            $membership -> save();

            return redirect() -> action('GroupController@show', ['id' => $group_id]) -> with('success', __('messages.group_approve') . $membership -> user() -> first()['name']);
        }
        else return redirect() -> action('GroupController@show', ['id' => $group_id]);
    }




    // reject membership
    public function rejectRequest(Request $request, $id, $group_id)
    {
        $moderator = Group::find($group_id)['moderator_id'];
        // only the moderator can reject the membership
        if (auth() -> user() -> id == $moderator)
        {
            $membership = UserGroupRelation::find($id);
            $membership -> delete(); // for rejected - delete a row from the table

            // mod_action - isset == false when the moderator rejects the initial request
            if ($request -> input('mod_action') !== null)
            {
                if (strtolower($request -> input('mod_action')) == 'unblock')
                {
                    $msg = __('general.user') . $membership -> user() -> first()['name'] . __('messages.group_unblock');
                }
                elseif (strtolower($request -> input('mod_action')) == 'expel')
                {
                    $msg =  __('general.user') . $membership -> user() -> first()['name'] . __('messages.group_expel');
                }
            }
            else
            {
                $msg = __('messages.group_reject') . $membership -> user() -> first()['name'];
            }


            // display the notification to the moderator
            return redirect() -> action('GroupController@show', ['id' => $group_id]) -> with('success', $msg);
        }
        else return redirect() -> action('GroupController@show', ['id' => $group_id]);
    }




    // block the user
    public function blockUser(Request $request, $id, $group_id)
    {
        $moderator = Group::find($group_id)['moderator_id'];
        // only the moderator can block the user
        if (auth() -> user() -> id == $moderator)
        {
            $msg = '';


            $membership = UserGroupRelation::find($id);
            $membership -> status = 'b'; // b - status for blocked
            $membership -> save();


            // mod_action - isset == false when the moderator block the user by the initial request
            if ($request -> input('mod_action') !== null)
            {
                if (strtolower($request -> input('mod_action')) == 'expel')
                {
                    $msg = __('general.user') . $membership -> user() -> first()['name'] . __('messages.group_expel_and_block');
                }
            }
            else
            {
                $msg = __('general.user') . $membership -> user() -> first()['name'] . __('messages.group_block');
            }


            // display the notification of that the moderator has performed
            return redirect() -> action('GroupController@show', ['id' => $group_id]) -> with('success', $msg);
        }
        else return redirect() -> action('GroupController@show', ['id' => $group_id]);
    }




    // process a request for membership
    // id - group_id
    public function newJoiner($id, Request $request)
    {
        // allowed only for the status 'a'
        $group = Group::findOrFail($id);
        if (strtolower($group -> status) != 'a') return redirect() -> action('GroupController@dashboard');

        $user_id = auth() -> user() -> id;

        // process the request for membership
        $req = new UserGroupRelation;
        $req -> user_id = $user_id;
        $req -> group_id = $id;
        $req -> status = 'p'; // p = pending
        $req -> save();

        // redirect to the dashboard
        return redirect() -> action('GroupController@dashboard') -> with('success', __('messages.group_membership_send'));
    }
    


    // leave group
    // id - group id
    public function leaveGroup($id, Request $request)
    {
        $user_id = auth() -> user() -> id;
        $group = Group::findOrFail($id);
        $membership = $group -> userRelations() -> wherePivot('user_id', $user_id) -> first()['pivot'];
        
        if (sizeof($membership) == 0)
        {
            unset($user_id, $group, $membership);
            return redirect() -> action('GroupController@dashboard');
        }
        $membership -> delete();

        unset($user_id, $membership);
        return redirect() -> action('GroupController@dashboard') -> with('success', __('messages.group_leave') . $group -> name);
    }
}
