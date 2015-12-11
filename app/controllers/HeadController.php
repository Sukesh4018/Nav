<?php

class HeadController extends Controller {

function header_proc(){
	$inp = Input::all();
	$search = $inp['search'];
	$search_string = $inp['input_string'];
	$source = $inp['source'];
	/*
	if($search =='city'){
		$sql = "SELECT name FROM cities WHERE name LIKE  :var  ORDER BY name";
	}
	else{
		$sql = "SELECT transport_corp FROM cities WHERE transport_corp LIKE  :var  ORDER BY transport_corp";
	}
	*/
	$sql = "SELECT * FROM cities WHERE name LIKE  :var  ORDER BY name";
	$results = DB::select( DB::raw($sql), array('var' => '%'.$search_string.'%',));
	$data = array($search,$results,$source);
	return View::make('header_view')->with('data', $data);
}

function list_trans(){
	$inp = Input::all();
	$source = $inp['source'];
	$search_string = $inp['selec'];
	$sql = "SELECT transport_corp FROM cities WHERE name =  :var  ORDER BY name";
	if($source=='selection'){
		Session::put('city',$search_string);
	}
	else{
		Session::put('editCity',$search_string);
	}
	
	$results = DB::select( DB::raw($sql), array('var' => $search_string,));
	$data = array($results,$source,$search_string);
	return View::make('list_corp')->with('data', $data);	
}


function manual_upload(){
	$inp = Input::all();
	$city = Session::get('editCity');
	$trans = Session::get('editTrans');
	if($city!=""){
	//$name = $inp['selec'];
	Session::put('editTrans',$trans);
	if($trans==""){
	$name = $inp['selec'];
	$type = $inp['type'];
	if($type=='name'){
		$city = $name;
	}
	else{
		$sql = "SELECT name FROM cities WHERE transport_corp = :var";
		$result = DB::select( DB::raw($sql), array('var' => $name,));
		$city = $result[0]->name;
		Session::put('editTrans',$name);
	}
	
	}
	return View::make('manual_upload')->with('city',$city);	
	}
	else{
		if($inp==null){
			return View::make('header')->with('source','mupload');
		}
	}
	
}

function addroute_help(){
	$inp = Input::all();
	$city = Session::get('editCity');
	$trans = Session::get('editTrans');
	if($city!=""){
	Session::put('editTrans',$trans);
	if($trans==""){
	
	$name = $inp['selec'];
	$type = $inp['type'];
	if($type=='name'){
		$city = $name;
	}
	else{
		$sql = "SELECT name FROM cities WHERE transport_corp = :var";
		$result = DB::select( DB::raw($sql), array('var' => $name,));
		$city = $result[0]->name;
		Session::put('editTrans',$name);
	}
	}
	
	return View::make('add_route')->with('city',$city);	
	}
	else{
		if($inp==null){
			return View::make('header')->with('source','add_route');
		}
	}
	
}


function edit_helper(){
	
	$inp = Input::all();
	$route = $inp['route'];	
	$city = Session::get('editCity');
	$trans = Session::get('editTrans');
	$table = $city.'_'.$trans.'_data';
	$res = DB::table($table)->select('route')->where('route',$route)->distinct()->get();

	if(sizeof($res)==1){
		
		$stops = DB::table($table)->where('route',$route)->get();
		return View::make('manual_upload')->with('datam',array($stops,$route));
	}
	else{
		
		$stops = "The route doesn't exists! Do you wish to add it?";
		return View::make('manual_upload')->with('no_data',$stops);		
	}
	
	
	/*
	
	$temp = $route.$start_name;
	
	if($handle == 'replace'){
		$query = "replace into ".$city."_stops(stop_lon, stop_lat, stop_name) values(".$gpsx1.",".$gpsy1.",'".$start_name."')";
		DB::statement($query);
		$query = "replace into ".$city."_stop_times(arrival_time, departure_time) values('".$start_time."','".$end_time."')";
		DB::statement($query);
		echo "DONE!!!";
	}
	else{
	
	$res = DB::table($city.'_stops')->where('stop_id',$start_name)->get();
	if(sizeof($res)==0){
		DB::table($city.'_stops')->insert(array('stop_lon' => $gpsx1, 'stop_lat' => $gpsy1,'stop_name' =>$start_name,'stop_id'=>$start_name));	
		DB::table($city.'_stop_times')->insert(array('trip_id' => $temp, 'arrival_time' => $start_time,'departure_time' =>$end_time,'stop_id'=>$start_name));
		DB::table($city.'_trips')->insert(array('trip_id' => $temp, 'route_id' => $route));	
		DB::table($city.'_routes')->insert(array('route_id' => $route));
		echo "DONE!!!";
	}
	else{
		echo "The stop already exists! Do you wish to edit it?\n";

		
		$stop_name = $res[0]->stop_name;
		$stop_lon = $res[0]->stop_lon;
		$stop_lat = $res[0]->stop_lat;
		$res = DB::table($city.'_stop_times')->where('stop_id',$start_name)->get();
		$arr_time = $res[0]->arrival_time;
		$dep_time = $res[0]->departure_time;
		$data = array($route,$stop_name,$stop_lon,$stop_lat,$arr_time,$dep_time);

		return View::make('edit_route')->with('data',$data);
		
	}
	}
	*/
	
	
}

function del($table,$route){
	DB::table($table)->where('route', '=', $route)->delete();
}

function edit_done(){
	$inp = Input::all();
	$city = Session::get('editCity');
	$trans = Session::get('editTrans');
	$table = $city.'_'.$trans.'_data';
	$count = $inp['size'];
	$route = $inp['route'];
	$op = $inp['op'];
	echo $count;
	
	if($op=="edit"){
	   $this->del($table,$route);
	}
	for($i=0;$i<$count;$i++){
		$t1 = $inp['stop_pos'.$i];
		$t2 = $inp['stop_name'.$i];
		$t3 = $inp['stop_lat'.$i];
		$t4 = $inp['stop_lon'.$i];
		//echo $t1." ".$t2." ".$t3." ".$t4." ";
		DB::table($table)->insert(array('route' => $route, 'stop_pos' => $t1, 'stop_name' => $t2,'stop_lat' =>$t3,'stop_lon'=>$t4));		
	}
	if($op=="edit"){
		echo '<script>window.alert("Successfully updated the route!");</script>';
		return View::make('manual_upload');
	}
	else{
		echo '<script>window.alert("Successfully added the route!");</script>';
		return View::make('add_route');
	}
	
	
}

function session_init(){
	$inp = Input::all();
	$name = $inp['selec'];
	$type = $inp['type'];
	Session::put('selec',$name);
	Session::put('type',$type);
	return $this->route_init();
}
function route_init(){
	$name = Session::get('selec');
	$type = Session::get('type');	
	if($type==""){
		echo '<script>window.alert("Select a City or Agency!");</script>';
		return View::make('header')->with('source','selection');
	}
	if($type=='name'){
		$city = $name;
	}
	else{
		$sql = "SELECT name FROM cities WHERE transport_corp = :var";
		$result = DB::select( DB::raw($sql), array('var' => $name,));
		$city = $result[0]->name;
		Session::put('trans',$name);
	}
	Session::put('city',$city);
	$trans = Session::get('trans');
	$routes = DB::table($city.'_'.$trans.'_data')->select('route')->distinct()->get();
	$data = array('get',$routes);
	return View::make('map')->with('data',$data);
	
}
function route_finder(){

	$inp = Input::all();
	$route = $inp['route'];;
	$city = Session::get('city');
	$trans = Session::get('trans');
	/*
	$query = "SELECT DISTINCT ". $city."_stops.stop_id, ". $city."_stops.stop_name, ". $city."_stops.stop_lat, ". $city."_stops.stop_lon
  	FROM ". $city."_trips
  	INNER JOIN ". $city."_stop_times ON ". $city."_stop_times.trip_id = ". $city."_trips.trip_id
  	INNER JOIN ". $city."_stops ON ". $city."_stops.stop_id = ". $city."_stop_times.stop_id
  	WHERE route_id = :var";
  	*/
  	$query = "select * from ".$city.'_'.$trans."_data where route = :var "; 

  	$stops = DB::select( DB::raw($query), array('var' => $route,));
  	
  	$routes = DB::table($city.'_'.$trans.'_data')->select('route')->distinct()->get();
  	$data = array($stops, $routes,$route);
  	return View::make('map')->with('data',$data);
  	
}



}

