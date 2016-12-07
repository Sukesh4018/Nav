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
	if(Auth::user()->role!="2"){
		return View::make('static_uploadzip');
	}
	else{
		return Redirect::to('mupload');
	}
}));

Route::get('upload_file', array('before' => 'auth',function()
{
	if(Auth::user()->role!="2"){
		return View::make('upload_file');
	}
	else{
		return Redirect::to('mupload');;
	}
}));

Route::get('get_search', array(function()
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
	if(Auth::user()->role!="2"){
		return View::make('add_agency')->with('source','mupload');
	}
	else{
		 return Redirect::to('mupload');
	}
});


Route::get('change_pwd',  array('before' => 'auth',  function()
{
	return View::make('change_pwd');
}));

Route::get('list_route',  array(function()
{
	$city = Session::get('city');
 	$trans = Session::get('trans'); 
	if($city==""||$trans==""){
    		 echo '<script>alert("Please select a city!");</script>';
    		 return View::make('header')->with('source','selection');
    	}
	else{
		return View::make('list_route');
	}
}));
Route::post('change_pwd', array('before' => 'auth','uses' =>'AccountController@change_pwd'));

Route::get('test',  function()
{
	return View::make('search');
});


Route::get('admin_view',array('before' => 'auth',function()
{	
	if(Auth::user()->role=="0"){
		return View::make('volunteer_requests');
	}
	else{
		return "you are not the admin. Please return to the portal by clicking <a href='login'>here</a>";
	}
}));

Route::get('volunteer_view',array('before' => 'auth', function()
{	
	if(Auth::user()->role=="1"){
		return View::make('accept_edits');
	}
	else{
		return "you are not the admin. Please return to the portal by clicking <a href='login'>here</a>";
	}
}));



Route::get('volunteer_requests',array('before' => 'auth',function()
{	
	if(Auth::user()->role=="0"){
		return View::make('volunteer_requests');
	}
	else{
		return "you are not the admin. Please return to the portal by clicking <a href='login'>here</a>";
	}
}));

Route::get('admin_login',function()
{
	return View::make('login_admin');
});

Route::post('admin_login', 'AccountController@admin_login');


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
 Auth::logout();
 return Redirect::to('login');
});

Route::get('request_volunteer',  array('before' => 'auth',   function()
{
	if(Auth::user()->role=="2"){
		return View::make('request_volunteer');
	}
	else{
		return "you are already a volunteer. Please return to the portal by clicking <a href='get_search'>here</a>";
	}
	
}));

Route::get('accept_edits', array('before' => 'auth', function()
{
	if(Auth::user()->role!="2"){
		return View::make('accept_edits');
	}
	else{
		return "you are not the admin/volunteer. Please return to the portal by clicking <a href='get_search'>here</a>";
	}
	
}));

Route::post('delete_route',  array('before' => 'auth','uses' =>'AuxController@delete_route'));

Route::get('delete_route', array('before' => 'auth', function()
{
	if(Auth::user()->role!="2"){
		if(Session::get('editTrans') != ""){
			return View::make('delete_route');
		}
		else{
			return View::make('header')->with('source','mupload');
		}
	}
	else{
		return "you are not the admin/volunteer. Please return to the portal by clicking <a href='get_search'>here</a>";
	}
	
}));

Route::post('edits_view', array('before' => 'auth', function()
{

	if(Auth::user()->role!="2"){
		return View::make('edits_view');
	}
	else{
		return "you are not the admin/volunteer. Please return to the portal by clicking <a href='get_search'>here</a>";
	}
}));


Route::post('edits_view', array('before' => 'auth','uses' =>'AuxController@list_route_edits'));

Route::post('request_volunteer', array('before' => 'auth','uses' =>'AccountController@request_volunteer'));

Route::get('app_data/{name}/c/{corp}/r/{rid}', ['uses' =>'HeadController@send_data']);
Route::post('get_data', ['uses' =>'HeadController@get_data']);

Route::get('edit_done',array('before' => 'auth',function(){
 return Redirect::to('mupload');
}));

Route::get('transl/{name}', array('uses' =>'AuxController@transliterate'));
Route::get('stop_info/{name}', array('uses' =>'AuxController@stop_info'));

Route::post('geocode_data', array('before' => 'auth','uses' =>'HeadController@cache_geocode'));
Route::get('download_route', array('uses' =>'HeadController@download_route'));
Route::get('edit_this_route', array('before' => 'auth','uses' =>'HeadController@edit_curr_route'));
Route::post('main', array('uses' =>'HeadController@route_finder'));
Route::get('main', array('uses' =>'HeadController@route_init1'));
Route::post('header', array('before' => 'auth','uses' =>'HeadController@header_proc'));
Route::post('selection', array('uses' =>'HeadController@session_init'));
Route::get('selection', array('uses' =>'HeadController@route_init'));
Route::get('edit_help', array('before' => 'auth','uses' =>'HeadController@manual_upload'));
Route::post('edit_help', array('before' => 'auth','uses' =>'HeadController@edit_helper'));
Route::post('edit_done', array('before' => 'auth','uses' =>'HeadController@edit_done'));
Route::get('add_route', array('before' => 'auth','uses' =>'HeadController@addroute_help'));
Route::post('add_route', array('before' => 'auth','uses' =>'HeadController@addroute_help'));
Route::get('mupload', array('before' => 'auth','uses' =>'HeadController@manual_upload'));
Route::post('mupload', array('before' => 'auth','uses' =>'HeadController@manual_upload'));
Route::post('upload_zip', array('before' => 'auth','uses' =>'BaseController@upload_and_extract'));
Route::post('upload_file', array('before' => 'auth','uses' =>'BaseController@file_upload'));
Route::post('list_trans', array('uses' =>'HeadController@list_trans'));
Route::get('list_trans', array('uses' =>'HeadController@route_init'));
Route::post('add_agen', array('before' => 'auth','uses' =>'HeadController@add_agency'));
Route::get('download_app', array('uses' =>'HeadController@download_app'));
Route::get('download_route_app', array('uses' =>'HeadController@download_route_app'));

Route::post('upvote_route', array('uses' =>'AuxController@upvote_route'));
Route::post('downvote_route', array('uses' =>'AuxController@downvote_route'));

Route::post('accept_volunteer', array('before' => 'auth','uses' =>'AuxController@accept_volunteer'));
Route::post('reject_volunteer', array('before' => 'auth','uses' =>'AuxController@reject_volunteer'));

Route::post('data_verified', array('before' => 'auth','uses' =>'AuxController@verified_data_enter'));
Route::post('ignore_edit', array('before' => 'auth','uses' =>'AuxController@delete_route_entry_edit'));


Route::get('edit_stop',function(){
	return "To be Updated";
});

Route::group(array('prefix' => 'app/'), function() {
        Route::get('get_cities', 'BaseController@getCities');
        Route::get('get_routes/{city}', 'BaseController@getRoutes');
        Route::get('get_stops/{city}', 'BaseController@getStops');
        Route::post('add_new_city','BaseController@addNewCity');
});

