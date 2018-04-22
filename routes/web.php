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

Route::get('/', 'NavigationController@homepage');
Route::get('/about', 'NavigationController@about');
Route::get('/contact', 'NavigationController@contact');
Route::post('/contact', 'NavigationController@post_contact');

Auth::routes();
Route::get('/dashboard', 'NavigationController@dashboard')->name('home');
