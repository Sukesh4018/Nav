<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});
Route::get('upload', array('before' => 'auth', function()
{
	return View::make('static_uploadzip');
}));

Route::get('upload_file', array('before' => 'auth',function()
{
	return View::make('upload_file');
}));

Route::get('get_search', array('before' => 'auth', function()
{
	return View::make('header')->with('source','selection');
}));

Route::get('get_change', array('before' => 'auth',  function()
{
	return View::make('header')->with('source','mupload');
}));

Route::get('upload_help',  array('before' => 'auth', function()
{
	return View::make('header')->with('source','mupload');
}));

Route::get('add_agen', array('before' => 'auth', function()
{
	return View::make('add_agency')->with('source','mupload');
}));

Route::get('successful_register', function()
{
	return View::make('successful_register');
});


Route::get('change_pwd',  array('before' => 'auth',  function()
{
	return View::make('change_pwd');
}));
Route::post('change_pwd', array('before' => 'auth','uses' =>'AccountController@change_pwd'));

Route::get('test',  function()
{
	return View::make('search');
});

Route::get('login', function() {
  return View::make('login');
});


Route::get('register',  function()
{
	return View::make('register_user');
});
Route::post('login', 'AccountController@login');

Route::post('register_user', 'AccountController@register_user');

Route::get('logout',function(){
 Session::flush();
 Auth::logout(); // logout user
 return Redirect::to('login'); //redirect back to login
});

Route::get('app_data/{name}/c/{corp}/r/{rid}', ['uses' =>'HeadController@send_data']);
Route::post('get_data', ['uses' =>'HeadController@get_data']);


Route::post('geocode_data', array('before' => 'auth','uses' =>'HeadController@cache_geocode'));
Route::get('download_route', array('before' => 'auth','uses' =>'HeadController@download_route'));
Route::get('edit_this_route', array('before' => 'auth','uses' =>'HeadController@edit_curr_route'));
Route::post('main', array('before' => 'auth','uses' =>'HeadController@route_finder'));
Route::get('main', array('before' => 'auth','uses' =>'HeadController@route_init'));
Route::post('header', array('before' => 'auth','uses' =>'HeadController@header_proc'));
Route::post('selection', array('before' => 'auth','uses' =>'HeadController@session_init'));
Route::get('selection', array('before' => 'auth','uses' =>'HeadController@route_init'));
Route::get('edit_help', array('before' => 'auth','uses' =>'HeadController@manual_upload'));
Route::post('edit_help', array('before' => 'auth','uses' =>'HeadController@edit_helper'));
Route::post('edit_done', array('before' => 'auth','uses' =>'HeadController@edit_done'));
Route::get('add_route', array('before' => 'auth','uses' =>'HeadController@addroute_help'));
Route::post('add_route', array('before' => 'auth','uses' =>'HeadController@addroute_help'));
Route::get('mupload', array('before' => 'auth','uses' =>'HeadController@manual_upload'));
Route::post('mupload', array('before' => 'auth','uses' =>'HeadController@manual_upload'));
Route::post('upload_zip', array('before' => 'auth','uses' =>'BaseController@upload_and_extract'));
Route::post('upload_file', array('before' => 'auth','uses' =>'BaseController@file_upload'));
Route::post('list_trans', array('before' => 'auth','uses' =>'HeadController@list_trans'));
Route::get('list_trans', array('before' => 'auth','uses' =>'HeadController@route_init'));
Route::post('add_agen', array('before' => 'auth','uses' =>'HeadController@add_agency'));
Route::get('download_app', array('before' => 'auth','uses' =>'HeadController@download_app'));


Route::get('edit_stop',function(){
	return "To be Updated";
});


