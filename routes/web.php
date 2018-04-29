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

// Authentication related routes
Auth::routes();
Route::get('/dashboard', 'NavigationController@dashboard')->name('home');

// Group related routes
Route::resource('groups', 'GroupController');
Route::get('/dashboard/groups', 'GroupController@dashboard');
Route::post('groups/{id}/newjoiner', 'GroupController@newJoiner');
// Group - approve or reject memebership requests
Route::put('/approve/{id}/{group_id}', 'GroupController@approveOfRequest');
Route::delete('/reject/{id}/{group_id}', 'GroupController@rejectRequest');
Route::put('/block/{id}/{group_id}', 'GroupController@blockUser');
