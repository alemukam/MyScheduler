<?php

namespace App\Http\Controllers;

use App\User;
use App\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this -> middleware('check_block');
    }




    /**
     * Show the user dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        if (auth() -> user() -> user_role != 'admin') return view('pages.dashboard');

        // deleted and resolved will not be displayed in the dashboard
        $notif_n = AdminNotification::where('status', 'n') -> get();

        return view('pages.dashboard') -> with('data', $notif_n);
    }



    /**
     * Update the user profile picture.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateImage(Request $request)
    {
        $lang = ($request -> session() -> has('lang') ? $request -> session() -> get('lang') : 'en');
        $msg = '';

        // if no image is provided exit the function right away
        if (!$request -> hasFile('user_img'))
        { 
            switch ($lang) 
            {
                case 'jp':
                    $msg = '画像がありません。';
                    break;
                case 'en':
                default:
                    $msg = 'No image provided.';
            }
            return redirect('/dashboard') -> with('error', $msg);
        }

        // process the upload
        $user_id = auth() -> user() -> id;
        $user_profile = User::findOrFail($user_id);

        $this -> validate($request, [
            'user_img' => 'image | nullable | max: 1999'
        ]);



        // 1. delete the old file
        if (strtolower($user_profile -> img) != 'no_image.jpg') Storage::delete('public/imgs_u/' . $user_profile -> img);

        // 2. process the new file
        // get filename with the extension
        $fileNameWithExt = $request -> file('user_img') -> getClientOriginalName();
        // get just file name
        $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
        // get just extension
        $ext = $request -> file('user_img') -> getClientOriginalExtension();
        $fileNameToStore = $user_id . $fileName . '_' . time() . '.' . $ext;
    
        // upload the image
        $path = $request -> file('user_img') -> storeAs('public/imgs_u/', $fileNameToStore);


        $user_profile -> img = $fileNameToStore;
        $user_profile -> save();

        unset($user_id, $user_profile);

        switch ($lang) 
        {
            case 'jp':
                $msg = '画像が更新されました。';
                break;
            case 'en':
            default:
                $msg = 'Image has been updated.';
        }
        return redirect('/dashboard') -> with('success', $msg);
    }



    /**
     * Display the edit form for user settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function settings()
    {
        $user_id = auth() -> user() -> id;
        $user = User::findOrFail($user_id);

        unset($user_id);
        return view('pages.dashboard_settings') -> with('user', $user);
    }



    /**
     * Update settings of the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateSettings(Request $request)
    {
        $lang = ($request -> session() -> has('lang') ? $request -> session() -> get('lang') : 'en');

        $this -> validate($request, [
            'name' => 'required | max: 32'
        ]);

        $user_id = auth() -> user() -> id;
        $user = User::findOrFail($user_id);

        $user -> name = $request -> input('name');
        $user -> save();

        unset($user_id, $user);

        $msg = '';
        switch ($lang) 
        {
            case 'jp':
                $msg = '設定が更新されました';
                break;
            case 'en':
            default:
                $msg = 'Your settings have been updated.';
        }
        return redirect() -> action('DashboardController@show') -> with('success', $msg);
    }



    /**
     * Update the password of the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $lang = ($request -> session() -> has('lang') ? $request -> session() -> get('lang') : 'en');

        $this -> validate($request, [
            'old_pwd' => 'required',
            'New_Password' => 'required | min: 6',
            'new_pwd_2' => 'required'
        ]);


        $user_id = auth() -> user() -> id;
        $user = User::findOrFail($user_id);
        $msg = '';
        switch ($lang) 
        {
            case 'jp':
                $msg = '間違ったパスワードまたは新しいパスワードが一致しません';
                break;
            case 'en':
            default:
                $msg = 'Incorrect password or new passwords do not match.';
        }

        // 1. "Current Password" must be the same
        if (!Hash::check($request -> input('old_pwd'), $user -> password))
        {
            unset($user_id, $user);
            return redirect() -> action('DashboardController@settings') -> with('error', $msg);
        }

        // 2. "New Passwords" must be the same
        if (strcmp($request -> input('New_Password'), $request -> input('new_pwd_2')) !== 0)
        {
            unset($user_id, $user);
            return redirect() -> action('DashboardController@settings') -> with('error', $msg);
        }

        // 3. Validation passed - hash the new password and store in the db
        $user -> password = Hash::make($request -> input('new_pwd_2'));
        $user -> save();

        unset($user_id, $user);

        switch ($lang) 
        {
            case 'jp':
                $msg = 'パスワードが更新されました。';
                break;
            case 'en':
            default:
                $msg = 'Password has been updated.';
        }
        return redirect() -> action('DashboardController@show') -> with('success', $msg);
    }



    /**
     * Delete the user profile.
     * Precondition - all groups have already been deleted
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteUser(Request $request)
    {
        $lang = ($request -> session() -> has('lang') ? $request -> session() -> get('lang') : 'en');
        $msg = '';

        $user_id = auth() -> user() -> id;
        $user = User::findOrFail($user_id);

        // 0. Admins and moderators are not allowed to delete their profile
        if (strtolower($user -> user_role) != 'basic')
        {
            switch ($lang) 
            {
                case 'jp':
                    $msg = '禁じられている。';
                    break;
                case 'en':
                default:
                    $msg = 'Not allowed.';
            }
            return redirect() -> action('DashboardController@show') -> with('error', $msg);
        }

        // 1. Delete foreign keys
        // 1.1. Delete all events
        $user_events = $user -> userEvents() -> get();
        if (sizeof($user_events) > 0) foreach($user_events as $event) $event -> delete();
        unset($user_events);

        // 1.2. Delete membership
        $membership = $user -> groupRelations() -> get();
        if (sizeof($membership) > 0) foreach($membership as $member) $member['pivot'] -> delete();
        unset($membership);

        // 2. Delete the user
        // 2.1. Delete the image
        if (strtolower($user -> img) != 'no_image.jpg') Storage::delete('public/imgs_u/' . $user -> img);

        // 2.2. Delete the user from the db
        $user -> delete();
        unset($user, $user_id);

        switch ($lang) 
        {
            case 'jp':
                $msg = 'あなたのプロフィールは削除されました。';
                break;
            case 'en':
            default:
                $msg = 'Your profile has been deleted.';
        }
        return redirect('/') -> with('success', '');
    }
}
