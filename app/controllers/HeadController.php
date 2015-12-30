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
	$sql = "SELECT distinct * FROM cities WHERE name LIKE  :var  group BY name";
	$results = DB::select( DB::raw($sql), array('var' => '%'.$search_string.'%',));
	$data = array($search,$results,$source);
	return View::make('header_view')->with('data', $data);
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

function add_agency(){
	$inp = Input::all();
	$city = $inp['city'];
	$trans = $inp['trans_agen'];
	$city = strtolower($city);
	$trans = strtolower($trans);
	//DB::table('cities')->insert(['name' => $city, 'transport_corp' => $trans]);
	DB::statement("REPLACE INTO cities(name,transport_corp) values('".$city."','".$trans."')");
	//$create  = "create table ".$city.'_'.$trans."_data(route varchar(500), stop_name varchar(500), stop_pos varchar(500), stop_lat varchar(500), stop_lon varchar(500))";
	//DB::statement($create);
	$create  = "create table ".$city.'_'.$trans."_stop(stop_id INT, stop_name varchar(500), stop_lat varchar(500), stop_lon varchar(500))";
	DB::statement($create);
	$create  = "create table ".$city.'_'.$trans."_route(route varchar(500), stop_id INT, stop_pos varchar(500))";
	DB::statement($create);

	Session::put('editCity',$city);
	Session::put('editTrans',$trans);
	return View::make('add_route')->with('city',$city);
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
	//$table = $city.'_'.$trans.'_data';
	
	$table = $city.'_'.$trans.'_route';
		
	$res = DB::table($table)->select('route')->where('route',$route)->distinct()->get();
	
	if(sizeof($res)==1){
		
		$stops = DB::table($table)->where('route',$route)->get();
		$query = "select * from ".$city.'_'.$trans."_route , ".$city.'_'.$trans."_stop where route = :var and ".$city.'_'.$trans."_route.stop_id = ".$city.'_'.$trans."_stop.stop_id ORDER BY ABS(stop_pos);";
		$stops = DB::select( DB::raw($query), array('var' => $route,));
		return View::make('manual_upload')->with('datam',array($stops,$route));
	}
	else{
		
		$stops = "The route doesn't exists! Do you wish to "
.'<a href="add_route">add it?</a>';
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
	//$table = $city.'_'.$trans.'_data';
	$table = $city.'_'.$trans.'_route';
	$count = $inp['size'];
	$route = $inp['route'];
	$op = $inp['op'];
	//echo $count;
	
	if($op=="edit"){
	   $this->del($table,$route);
	}
	for($i=0;$i<$count;$i++){
		$t1 = $inp['stop_pos'.$i];
		$t2 = $inp['stop_name'.$i];
		$t3 = $inp['stop_lat'.$i];
		$t4 = $inp['stop_lon'.$i];
		//echo $t1." ".$t2." ".$t3." ".$t4." ";
		//DB::table($table)->insert(array('route' => $route, 'stop_pos' => $t1, 'stop_name' => $t2,'stop_lat' =>$t3,'stop_lon'=>$t4));				
		$query = "SELECT stop_id FROM ".$city."_".$trans."_stop WHERE stop_name = :var"; 
  	        $stopid = DB::select( DB::raw($query), array('var' => $t2,));
  	         	        
   		if(sizeof($stopid)!=1){
   			$stops_id = $price = DB::table($city."_".$trans."_stop")->max('stop_id');
 			$stops_id = $stops_id+1;
   			DB::statement("INSERT INTO ".$city."_".$trans."_route(route,stop_id,stop_pos) values('".$route."' ,'".$stops_id."' ,'".$t1."')"); 
   				  				  			
   			DB::statement("INSERT INTO ".$city."_".$trans."_stop(stop_id,stop_name,stop_lat,stop_lon) values('".$stops_id."' ,'".$t2."' ,'".$t3."' ,'".$t4."')"); 
   						
   		}
   		else{
   			DB::statement("INSERT INTO ".$city."_".$trans."_route(route,stop_id,stop_pos) values('".$route."' ,'".$stopid[0]->stop_id."' ,'".$t1."')"); 
   			DB::statement("REPLACE INTO ".$city."_".$trans."_stop(stop_id,stop_name,stop_lat,stop_lon) values('".$stopid[0]->stop_id."' ,'".$t2."' ,'".$t3."' ,'".$t4."')");
   				
   				  						
   			}
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

function list_trans(){
	$inp = Input::all();
	$source = $inp['source'];
	//$search_string = $inp['selec'];
	$search_string = $inp['input_string'];
	$sql = "SELECT transport_corp FROM cities WHERE name =  :var  ORDER BY name";
	if($source=='selection'){
		Session::put('city',$search_string);
	}
	else{
		Session::put('editCity',$search_string);
	}
	
	$results = DB::select( DB::raw($sql), array('var' => $search_string,));
	if(sizeof($results)==1 && $source=='selection'){
		Session::put('trans',$results[0]->transport_corp);

		$city = Session::get('city');
		$trans = Session::get('trans');
		$routes = DB::table($city.'_'.$trans.'_route')->select('route')->distinct()->get();
		$data = array('get',$routes);
		return View::make('map')->with('data',$data);
	}
	else{
		$data = array($results,$source,$search_string);
		return View::make('list_corp')->with('data', $data);	
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
	if($name != ""){
		Session::put('trans',$name);
	}
	$type = Session::get('type');
	$trans = Session::get('trans');	
	$city = Session::get('city');
	
	if($trans==""){
		echo '<script>window.alert("Select a City or Agency!");</script>';
		return View::make('header')->with('source','selection');
	}
	/*
	if($type=='name'){
		$city = $name;
	}
	else{
		$sql = "SELECT name FROM cities WHERE transport_corp = :var";
		$result = DB::select( DB::raw($sql), array('var' => $name,));
		$city = $result[0]->name;
		Session::put('trans',$name);
	}
	*/
	//Session::put('city',$city);
	
	$routes = DB::table($city.'_'.$trans.'_route')->select('route')->distinct()->get();
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
  	
  	//$query = "select * from ".$city.'_'.$trans."_data where route = :var  ORDER BY ABS(stop_pos);"; 

  	//$stops = DB::select( DB::raw($query), array('var' => $route,));
  	
  	$query = "select * from ".$city.'_'.$trans."_route , ".$city.'_'.$trans."_stop where route = :var and ".$city.'_'.$trans."_route.stop_id = ".$city.'_'.$trans."_stop.stop_id ORDER BY ABS(stop_pos);"; 

  	$stops = DB::select( DB::raw($query), array('var' => $route,));	
  	$routes = DB::table($city.'_'.$trans.'_route')->select('route')->distinct()->get();
  	$data = array($stops, $routes,$route);
  	return View::make('map')->with('data',$data);
  	
}


function cache_geocode(){
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
}


}

