<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Basic navigation - miscellaneous pages
Route::get('/', 'NavigationController@homepage');
Route::get('/about', 'NavigationController@about');
Route::get('/contact', 'NavigationController@contact');
Route::post('/contact', 'NavigationController@post_contact');



// Admin routes
Route::get('/find-users', 'AdminController@findUsers'); // display the search engine for all users
Route::post('/find-users', 'AdminController@performFindUsers'); // display results of the search
Route::put('/block/{id}', 'AdminController@block'); // block the user
Route::put('/unblock/{id}', 'AdminController@unblock'); // unblock the user
// notifications
Route::put('/resolve-message/{id}', 'AdminController@resolveMessage');
Route::put('/delete-message/{id}', 'AdminController@deleteMessage'); // make a message as "deleted", but will remain in the database



// Authentication related routes
Auth::routes();



// Dashboard routes
Route::get('/dashboard', 'DashboardController@show');
Route::put('/dashboard/image', 'DashboardController@updateImage');
Route::get('/dashboard/settings', 'DashboardController@settings');
Route::put('/dashboard/settings', 'DashboardController@updateSettings');
Route::put('/dashboard/password', 'DashboardController@updatePassword');
Route::delete('/dashboard/settings', 'DashboardController@deleteUser');



// Group related routes
Route::resource('groups', 'GroupController');
Route::get('/dashboard/groups', 'GroupController@dashboard');
Route::post('groups/{id}/newjoiner', 'GroupController@newJoiner');
Route::delete('/groups/{id}/leave', 'GroupController@leaveGroup');



// Group - approve or reject memebership requests
Route::put('/approve/{id}/{group_id}', 'GroupController@approveOfRequest');
Route::delete('/reject/{id}/{group_id}', 'GroupController@rejectRequest');
Route::put('/block/{id}/{group_id}', 'GroupController@blockUser');
