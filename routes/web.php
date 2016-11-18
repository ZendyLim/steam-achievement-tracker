<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'AuthController@index');

Route::get('login', 'AuthController@login');

Route::get('logout', 'AuthController@logout');

Route::get('profile/{id}', array('as' => 'profile', 'uses' => 'ProfileController@index'));

Route::get('profile/{id}/{appid}', array('as' => 'details', 'uses' => 'ProfileController@details'));

Route::get('profile', 'ProfileController@redirectProfile');