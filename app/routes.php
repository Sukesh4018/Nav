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
Route::get('upload', function()
{
	return View::make('static_uploadzip');
});

Route::get('upload_file', function()
{
	return View::make('upload_file');
});

Route::get('get_search',  function()
{
	return View::make('header')->with('source','selection');
});

Route::get('get_change',  function()
{
	return View::make('header')->with('source','mupload');
});

Route::get('upload_help',  function()
{
	return View::make('header')->with('source','mupload');
});

Route::get('add_agen',  function()
{
	return View::make('add_agency')->with('source','mupload');
});

/*
Route::get('add_route',  function()
{
	return View::make('add_route');
});
*/
Route::get('test',  function()
{
	return View::make('search');
});

/*
Route::post('geocode_data',  function()
{
	$inp = Input::all();
	$dat = $inp['dat'];
	$city = $inp['city'];
	$trans = $inp['trans'];
	$arr = json_decode($dat);
	$stops = json_decode($inp['stops']);
	$table = $city."_".$trans."_stop";
	for($i=0;$i<sizeof($arr); $i++){
		$query = "select * from ".$table." where stop_name = :var";
		$id = DB::select( DB::raw($query), array('var' => $stops[$i],));	
		DB::statement("REPLACE INTO $table(stop_id,stop_name,stop_lat,stop_lon) values('".$id[0]->stop_id."','".$stops[$i]."','".$arr[$i]->lat."','".$arr[$i]->lng."')");
	}
	$rep = "success ".sizeof($arr);
	return $rep;
});
*/
Route::post('geocode_data', 'HeadController@cache_geocode');
Route::get('download_route', 'HeadController@download_route');
Route::get('edit_this_route', 'HeadController@edit_curr_route');
Route::post('main', 'HeadController@route_finder');
Route::get('main', 'HeadController@route_init');
Route::post('header', 'HeadController@header_proc');
Route::post('selection', 'HeadController@session_init');
Route::get('selection', 'HeadController@route_init');
Route::get('edit_help', 'HeadController@manual_upload');
Route::post('edit_help', 'HeadController@edit_helper');
Route::post('edit_done', 'HeadController@edit_done');
Route::get('add_route', 'HeadController@addroute_help');
Route::post('add_route', 'HeadController@addroute_help');
Route::get('mupload', 'HeadController@manual_upload');
Route::post('mupload', 'HeadController@manual_upload');
Route::post('upload_zip', 'BaseController@upload_and_extract');
Route::post('upload_file', 'BaseController@file_upload');
Route::post('list_trans', 'HeadController@list_trans');
Route::get('list_trans', 'HeadController@route_init');
Route::post('add_agen', 'HeadController@add_agency');

Route::get('edit_stop',function(){
	return "To be Updated";
});


