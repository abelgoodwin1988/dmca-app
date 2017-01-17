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
/**
 * Homepage
 */
Route::get('/', 'PagesController@Home');

/**
 * Authentication
 */
Auth::routes();

/**
 * Notices
 */

Route::resource('notices','NoticesController');

/**
 * Login Home
 */
Route::get('/home', 'HomeController@index');

