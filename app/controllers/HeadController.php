<?php
/*
This Controller contains functions which mostly deal with displaying the data and other functionality
*/
class HeadController extends Controller {

//Retrieves all the cities names available and sends them to the view 'header_view'
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
	$sql = "SELECT distinct * FROM cities WHERE name LIKE  :var  group BY name"; //Get all the city names
	$results = DB::select( DB::raw($sql), array('var' => '%'.$search_string.'%',));
	$data = array($search,$results,$source);
	return View::make('header_view')->with('data', $data);
}

//Displays the 'manual_upload' view with the given city and transport corporation
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
		$sql = "SELECT name FROM cities WHERE transport_corp = :var"; //Get the corresponding transport corporation
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

//Add a new agency in a city
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
	
	//Create the necessary tables
	$create  = "create table ".$city.'_'.$trans."_stop(stop_id INT, stop_name varchar(500), stop_lat varchar(500), stop_lon varchar(500))";
	DB::statement($create);
	$create  = "create table ".$city.'_'.$trans."_route(route varchar(500), stop_id INT, stop_pos varchar(500))";
	DB::statement($create);
	$create  = "create table ".$city.'_'.$trans."_info(route varchar(500),upvotes INT default 0, downvotes INT default 0 , views INT default 0, created_by varchar(500) default 'admin',verified_by varchar(500) default 'admin',edited_by varchar(500) default 'admin' ,PRIMARY KEY(route)) ";
	DB::statement($create);
	$stops = "ALTER TABLE ".$city.'_'.$trans."_stop
	ADD PRIMARY KEY (stop_id,stop_name)";
	DB::statement($stops);
	
	Session::put('editCity',$city);
	Session::put('editTrans',$trans);
	return View::make('add_route')->with('city',$city);
}


//Assists in adding route 
function addroute_help(){
	$inp = Input::all();
	$city = Session::get('editCity');
	$trans = Session::get('editTrans');
	if($city!=""){
	Session::put('editTrans',$trans);
	if($trans==""){
	if($inp!=null){
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
	return View::make('add_route')->with('city',$city);
	}
	else{
		return View::make('header')->with('source','add_route');
	}
	}
	else{
		return View::make('add_route')->with('city',$city);
	}
		
	}
	else{
		if($inp==null){
			return View::make('header')->with('source','add_route');
		}
	}
	
}

//Retrieves route information for editing the route
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
		$stops = DB::select( DB::raw($query), array('var' => $route,)); //Getting the route data
		return View::make('manual_upload')->with('datam',array($stops,$route));
	}
	else{
		
		$stops = "The route doesn't exists! Do you wish to "
.'<a href="add_route">add it?</a>';
		return View::make('manual_upload')->with('no_data',$stops);		
	}
	
}

//Delete a route
function del($table,$route){
	DB::table($table)->where('route', '=', $route)->delete();
}

//Adds an edited/created route to the ' routes_edit_history' and ' routes_edit_history_help' table
function update_history($city,$trans,$route,$inp,$username,$action,$count){
 
	$quer = "select max(revision) as ver from routes_edit_history where route = :var1 and city = :var2 and trans = :var3"; //Get the maximum revision number on the given route
  	$rev = DB::select( DB::raw($quer), array('var1' => $route,'var2' => $city,'var3' => $trans,));
  	$ver = $rev[0]->ver;
  	if($ver==null){
  		$ver=0;
  	}
  	if($action!="edit"){
  		$action="add";
  	}
  	$ver = $ver+1;//Increment the revision number
  	$quer = "INSERT INTO routes_edit_history(route, city, trans, revision, action, edited_by) values(:var1 ,:var2, :var3 ,:var4, :var5 ,:var6)"; 
  	DB::insert( DB::raw($quer), array('var1' => $route,'var2' => $city,'var3' => $trans,'var4' => $ver,'var5' => $action,'var6' => $username,));// Insert into the 'routes_edit_history' table
  	$quer = "select id  from routes_edit_history where route = :var1 and city = :var2 and trans = :var3 and revision = :var4"; 
  	$ids = DB::select( DB::raw($quer), array('var1' => $route,'var2' => $city,'var3' => $trans,'var4' => $ver));
  	$id = $ids[0]->id;//Getting the id of the insert
  	for($i=0;$i<$count;$i++){
		$t1 = $inp['stop_pos'.$i];
		$t2 = $inp['stop_name'.$i];
		$t3 = $inp['stop_lat'.$i];
		$t4 = $inp['stop_lon'.$i];
		$quer = "INSERT INTO routes_edit_history_help(ID,route,stop_pos, stop_name, stop_lat, stop_lon) values(:var1 ,:var2, :var3 ,:var4, :var5 ,:var6)"; 
  		DB::insert( DB::raw($quer), array('var1' => $id,'var2' => $route,'var3' => $t1,'var4' => $t2,'var5' => $t3,'var6' => $t4,)); //Insert the stops of the edited/created route in 'routes_edit_history_help'
	}
	//echo '<script>window.alert("Your edit has been recorded for verification!");</script>';  	
}

//Insert the route data into the corresponding table
function edit_done(){
	$inp = Input::all();
	$proc = $inp['proc'];
	$username = Auth::user()->username;
	if($proc=="info"){
		$city = Session::get('city');
		$trans = Session::get('trans');
	}
	else{
		$city = Session::get('editCity');
		$trans = Session::get('editTrans');
	}
	
	//$table = $city.'_'.$trans.'_data';
	$table = $city.'_'.$trans.'_route';
	$count = $inp['size'];
	$route = $inp['route'];
	$op = $inp['op'];
	if($count>0){
	if(Auth::user()->role!="2"){  //If not a user
	if($op=="edit"){//If a route that exists is edited
	   $this->del($table,$route); //Delete the route which would be replaced by the new edited route
	}
	if($op=="edit"){//If a route that exists is edited
				$quer = "SELECT * FROM ".$city."_".$trans."_info WHERE route = :var"; 
  	        		$created = DB::select( DB::raw($quer), array('var' => $route,));
  	        		$created_by = $created[0]->created_by;
	  			 //DB::statement("REPLACE INTO ".$city."_".$trans."_info(route,created_by,edited_by) values('".$route."' ,'".$created_by."' ,'".$username."')");
	  			//$quer = "REPLACE INTO ".$city."_".$trans."_info(route,created_by,edited_by) values(:var1,:var2,:var3)";
   				//DB::insert( DB::raw($quer), array('var1' => $route,'var2' => $created_by,'var3' => $username,));
   				$query = "SELECT * FROM ".$city."_".$trans."_info WHERE route = :var";
				$routes = DB::select( DB::raw($query), array('var' => $route,));
				$quer = "REPLACE INTO ".$city."_".$trans."_info(route,views,upvotes,downvotes,created_by,verified_by,edited_by,verified_status) values(:var1,:var2,:var3,:var4,:var5,:var6,:var7,:var8)";//update the route info
				DB::insert( DB::raw($quer), array('var1' => $route,'var2' => $routes[0]->views+1,'var3' => $routes[0]->upvotes,'var4' => $routes[0]->downvotes,'var5' => $created_by,'var6' => $username,'var7' => $username,'var8'=>1,));
   				
			}
	else{
				//DB::statement("INSERT INTO ".$city."_".$trans."_info(route,created_by,edited_by) values('".$route."' ,'".$username."' ,'".$username."')");
   				$quer = "REPLACE INTO ".$city."_".$trans."_info(route,views,upvotes,downvotes,created_by,verified_by,edited_by,verified_status) values(:var1,:var2,:var3,:var4,:var5,:var6,:var7,:var8)";
				DB::insert( DB::raw($quer), array('var1' => $route,'var2' => '0','var3' => '0','var4' => '0','var5' => $username,'var6' => $username,'var7' => $username,'var8'=>1,));//update the route info
   				/*
   				$query = "SELECT * FROM ".$city."_".$trans."_info WHERE route = :var";
				$routes = DB::select( DB::raw($query), array('var' => $route,));
				$quer = "REPLACE INTO ".$city."_".$trans."_info(route,views,upvotes,downvotes,created_by,verified_by,edited_by) values(:var1,:var2,:var3,:var4,:var5,:var6,:var7)";
				DB::insert( DB::raw($quer), array('var1' => $route,'var2' => $routes[0]->views+1,'var3' => $routes[0]->upvotes,'var4' => $routes[0]->downvotes,'var5' => $username,'var6' => "na",'var7' => $username,));
				*/
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
  	         	        
   		if(sizeof($stopid)!=1){ //stop name exists
   			$stops_id = $price = DB::table($city."_".$trans."_stop")->max('stop_id');
 			$stops_id = $stops_id+1;
   			//DB::statement("INSERT INTO ".$city."_".$trans."_route(route,stop_id,stop_pos) values('".$route."' ,'".$stops_id."' ,'".$t1."')"); 
   			$quer = "INSERT INTO ".$city."_".$trans."_route(route,stop_id,stop_pos) values(:var1 ,:var2,'".$t1."')"; 
  	        	DB::insert( DB::raw($quer), array('var1' => $route,'var2' => $stops_id,));
   			
   				  				  			
   			//DB::statement("REPLACE INTO ".$city."_".$trans."_stop(stop_id,stop_name,stop_lat,stop_lon) values('".$stops_id."' ,'".$t2."' ,'".$t3."' ,'".$t4."')"); 
   			$quer = "replace INTO ".$city."_".$trans."_stop(stop_id,stop_name,stop_lat,stop_lon) values(:var1,:var2,:var3,:var4)";
   			DB::insert( DB::raw($quer), array('var1' => $stops_id,'var2' => $t2,'var3' => $t3,'var4' => $t4,));
   						
   		}
   		else{
   			//DB::statement("INSERT INTO ".$city."_".$trans."_route(route,stop_id,stop_pos) values('".$route."' ,'".$stopid[0]->stop_id."' ,'".$t1."')"); 
   			$quer = "INSERT INTO ".$city."_".$trans."_route(route,stop_id,stop_pos) values(:var1 ,:var2,'".$t1."')"; 
  	        	DB::insert( DB::raw($quer), array('var1' => $route,'var2' => $stopid[0]->stop_id,));
   			//DB::statement("REPLACE INTO ".$city."_".$trans."_stop(stop_id,stop_name,stop_lat,stop_lon) values('".$stopid[0]->stop_id."' ,'".$t2."' ,'".$t3."' ,'".$t4."')");
   			$quer = "replace INTO ".$city."_".$trans."_stop(stop_id,stop_name,stop_lat,stop_lon) values(:var1,:var2,:var3,:var4)";
   			DB::insert( DB::raw($quer), array('var1' => $stopid[0]->stop_id,'var2' => $t2,'var3' => $t3,'var4' => $t4,));
   				
   				  						
   			}
	}
	if($proc=="info"){
		echo '<script>window.alert("Successfully updated the route!");</script>';
		return $this->route_init();;
	}
	else{
	if($op=="edit"){
		echo '<script>window.alert("Successfully updated the route!");</script>';
		return View::make('manual_upload');
	}
	else{
		echo '<script>window.alert("Successfully added the route!");</script>';
		return View::make('add_route');
	}
	}
	}
	else{ //If user
		$this->update_history($city,$trans,$route,$inp,$username,$op,$count);
		return Redirect::to('mupload')->with('success', 'Your edit has been recorded for verification!');;		
	}
	}
	else{
		echo '<script>window.alert("Please add stops!");</script>';
		return View::make('add_route');
	}
	
}


//List the tranport corporations in a given city and pass them to the view
function list_trans(){
	$inp = Input::all();
	$source = $inp['source'];
	//$search_string = $inp['selec'];
	$search_string = $inp['input_string'];
	$sql = "SELECT transport_corp FROM cities WHERE name =  :var  ORDER BY name";//Get the tranport corporations
	if($source=='selection'){
		Session::put('city',$search_string);
		Session::put('selec',"");
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
		return View::make('list_route')->with('data',$data);
	}
	else{
		$data = array($results,$source,$search_string);
		return View::make('list_corp')->with('data', $data);	
	}
}

//Set the appropriate session variables
function session_init(){
	$inp = Input::all();
	$name = $inp['selec'];
	$type = $inp['type'];
	Session::put('selec',$name);
	Session::put('type',$type);
	return $this->route_init();
}

//Retrieves the rote data and pass them to the view
function route_init(){
	Session::put('route',"");
	Session::put('stops_route',"");
	Session::put('language',"en");
	$name = Session::get('selec');
	if($name != ""){
		Session::put('trans',$name);
	}
	$type = Session::get('type');
	$trans = Session::get('trans');	
	$city = Session::get('city');	
	
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
	
	if($trans==""){
		echo '<script>window.alert("Select a City or Agency!");</script>';
		return View::make('header')->with('source','selection');
	}
	$routes = DB::table($city.'_'.$trans.'_route')->select('route')->orderBy('route', 'asc')->distinct()->get();
	$data = array('get',$routes);
	return View::make('list_route')->with('data',$data);
	
	
}

//Set the appropritate sessio variables and pass route data
function route_init1(){
	Session::put('route',"");
	Session::put('stops_route',"");
	Session::put('language',"en");
	$name = Session::get('selec');
	if($name != ""){
		Session::put('trans',$name);
	}
	$type = Session::get('type');
	$trans = Session::get('trans');	
	$city = Session::get('city');
	if($city==""){
		echo '<script>window.alert("Select a City or Agency!");</script>';
		return View::make('header')->with('source','selection');
	}		
	if($trans==""){
		echo '<script>window.alert("Select a City or Agency!");</script>';
		return View::make('header')->with('source','selection');
	}
	$routes = DB::table($city.'_'.$trans.'_route')->select('route')->distinct()->orderBy('route', 'asc')->get();	
	$data = array('get',$routes);
	return View::make('map')->with('data',$data);
	
	
}

//Given a route display the data associated with that route
function route_finder(){

	$inp = Input::all();
	$route = $inp['route'];;
	$city = Session::get('city');
	$trans = Session::get('trans');
	$query = "SELECT * FROM ".$city."_".$trans."_info WHERE route = :var";
	$routes = DB::select( DB::raw($query), array('var' => $route,));
	if(sizeof($routes)==1){
	Session::put('route',$route);
	$quer = "REPLACE INTO ".$city."_".$trans."_info(route,views,upvotes,downvotes,created_by,verified_by,edited_by,verified_status) values(:var1,:var2,:var3,:var4,:var5,:var6,:var7,:var8)";
	DB::insert( DB::raw($quer), array('var1' => $route,'var2' => $routes[0]->views+1,'var3' => $routes[0]->upvotes,'var4' => $routes[0]->downvotes,'var5' => $routes[0]->created_by,'var6' => $routes[0]->verified_by,'var7' => $routes[0]->edited_by,'var8' => $routes[0]->verified_status,));//Increment the view counter of route
	
  	
  	//$query = "select * from ".$city.'_'.$trans."_data where route = :var  ORDER BY ABS(stop_pos);"; 

  	//$stops = DB::select( DB::raw($query), array('var' => $route,));
  	
  	$query = "select * from ".$city.'_'.$trans."_route , ".$city.'_'.$trans."_stop where route = :var and ".$city.'_'.$trans."_route.stop_id = ".$city.'_'.$trans."_stop.stop_id ORDER BY ABS(stop_pos);"; 

  	$stops = DB::select( DB::raw($query), array('var' => $route,));	
  	$routes_all = DB::table($city.'_'.$trans.'_route')->select('route')->distinct()->get(); //Get
  	$query = "select * from ".$city.'_'.$trans."_info where route = :var "; 
  	$info_all = DB::select( DB::raw($query), array('var' => $route,)); 
  	$data = array($stops, $routes_all, $route,$info_all);
  	//print_r($data);
  	return View::make('map')->with('data',$data);

  	}
  	else{
  		echo "The given route doesn't exist!!! Go <a href='main'> back</a>";
  	}
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

function download_route(){
    $inp = Input::all();
    
    $route = Session::get('route');
    $city = Session::get('city');
    $trans = Session::get('trans');
    $route_mod = str_replace(array(".","/"), '-', $route);
    $name = public_path().'/'.$city.'_'.$trans.'_'.$route_mod.'.csv';
         
    $dat = Session::get('stops_route');
    if($route!=""){
    $file = fopen($name, "w");
    $query = "select route,stop_name,stop_lat,stop_lon from ".$city.'_'.$trans.'_'."route, ".$city.'_'.$trans.'_'."stop where route = :var and ".$city.'_'.$trans.'_'."route.stop_id = ".$city.'_'.$trans.'_'."stop.stop_id ORDER BY ABS(stop_pos)";
    $data = DB::select( DB::raw($query), array('var' => $route,));
    if($dat==""){
    	foreach($data as $datum){
    		$temp = $datum->route.",".$datum->stop_name.",".$datum->stop_lat.",".$datum->stop_lon."\n" ;
    		fwrite($file,$temp);
    	}
    }
    else{
    	$i = 1;
    	foreach($data as $datum){
    		$temp = $datum->route.",".$dat[$i].",".$datum->stop_lat.",".$datum->stop_lon."\n" ;
    		fwrite($file,$temp);
    		$i++;
    	}    
    }
    
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($name).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($name));
    
    //return "ok";
    readfile($name);
    system("rm ".$name);
    }
    else{
    	echo '<script>window.alert("Please select a route!");</script>';
    	return $this->route_init();
    }
   

}

function send_data($city,$trans,$route){
	/*
    $route = Session::get('route');
    $city = Session::get('city');
    $trans = Session::get('trans');
    */
    $name = public_path().'/'.$city.'_'.$trans.'_'.$route.'.csv';
         
    if($route!=""){
    $file = fopen($name, "w");
    $query = "select route,stop_name,stop_lat,stop_lon from ".$city.'_'.$trans.'_'."route, ".$city.'_'.$trans.'_'."stop where route = :var and ".$city.'_'.$trans.'_'."route.stop_id = ".$city.'_'.$trans.'_'."stop.stop_id ORDER BY ABS(stop_pos)";
    $data = DB::select( DB::raw($query), array('var' => $route,));
    foreach($data as $datum){
    	$temp = $datum->route.",".$datum->stop_name.",".$datum->stop_lat.",".$datum->stop_lon."\n" ;
    	fwrite($file,$temp);
    }
    
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($name).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($name));
    
    //return "ok";
    readfile($name);
    system("rm ".$name);
    }
    else{
    	echo '<script>window.alert("Please select a route!");</script>';
    	return $this->route_init();
    }
    

}


//getting data from the mobile
function get_data(){
	 $inp = Input::all();
	 $city = $inp['city'];
	 $trans  = $inp['corp'];
	 $route  = $inp['route'];
	 $username = $inp['username'];
	 $op = "edit";
	 $stop_list = json_decode($inp['stopList'],true);
	 $lat_list = json_decode($inp['latList'],true);
	 $lon_list = json_decode($inp['lonList'],true);
	 
	 $this->update_history($city,$trans,$route,$inp,$username,$op,$count);	
	 //DB::statement("INSERT INTO test(city,route) values('$city','$route')");	
	 DB::insert( DB::raw("INSERT INTO test(city,route) values(:var0,:var1)"), array('var0' => $city,'var1' => $trans,)); 
	 $quer = "select * from cities where name = :var0 and transport_corp = :var1";
	 $result = DB::select( DB::raw($quer), array('var0' => $city,'var1' => $trans,));
	 if(sizeof($result)!=1){
	 	//DB::statement("INSERT INTO cities(name,transport_corp) values('$city','$trans')");
	 	DB::insert( DB::raw("INSERT INTO test(city,route) values(:var0,:var1)"), array('var0' => $city,'var1' => $trans,)); 
		$create  = "create table ".$city.'_'.$trans."_stop(stop_id INT, stop_name varchar(500), stop_lat varchar(500), stop_lon varchar(500))";
		DB::statement($create);
		$create  = "create table ".$city.'_'.$trans."_route(route varchar(500), stop_id INT, stop_pos varchar(500))";
		DB::statement($create);
		
		$stops = "ALTER TABLE ".$city.'_'.$trans."_stop ADD PRIMARY KEY (stop_id,stop_name)";
		DB::statement($stops);
	 }
	 $count  = sizeof($stop_list);
	 $table = $city."_".$trans."_route";
	 //$this->del($table,$route);
	 $input = array();
	 for($i=0;$i<$count;$i++){
	 	$input['stop_pos'.$i] = $i+1;
		$input['stop_name'.$i] = $stop_list[$i];
		$input['stop_lat'.$i] = $lat_list[$i];
		$input['stop_lon'.$i] = $lon_list[$i];
		
	 }
	 $this->update_history($city,$trans,$route,$input,$username,$op,$count);
	 
	 for($i=0;$i<$count;$i++){
		$t1 = $i+1;
		$t2 = $stop_list[$i];
		$t3 = $lat_list[$i];
		$t4 = $lon_list[$i];
		//echo $t1." ".$t2." ".$t3." ".$t4." ";
		//DB::table($table)->insert(array('route' => $route, 'stop_pos' => $t1, 'stop_name' => $t2,'stop_lat' =>$t3,'stop_lon'=>$t4));				
		$query = "SELECT stop_id FROM ".$city."_".$trans."_stop WHERE stop_name = :var"; 
  	        $stopid = DB::select( DB::raw($query), array('var' => $t2,));
   		if(sizeof($stopid)!=1){
   			$stops_id =  DB::table($city."_".$trans."_stop")->max('stop_id');
 			$stops_id = $stops_id+1;
   			//DB::statement("INSERT INTO ".$city."_".$trans."_route(route,stop_id,stop_pos) values('".$route."' ,'".$stops_id."' ,'".$t1."')"); 
   			$quer = "INSERT INTO ".$city."_".$trans."_route(route,stop_id,stop_pos) values(:var1 ,:var2,'".$t1."')"; 
  	        	DB::insert( DB::raw($quer), array('var1' => $route,'var2' => $stops_id,));
   			//DB::statement("INSERT INTO ".$city."_".$trans."_info(route,created_by,edited_by) values('".$route."' ,'".$username."' ,'".$username."')");  
   			//$quer = "REPLACE INTO ".$city."_".$trans."_info(route,edited_by) values(:var1,:var3)";
   			//DB::insert( DB::raw($quer), array('var1' => $route,'var3' => $username,));			
   			$query = "SELECT * FROM ".$city."_".$trans."_info WHERE route = :var";
			$routes = DB::select( DB::raw($query), array('var' => $route,));
			$quer = "REPLACE INTO ".$city."_".$trans."_info(route,views,upvotes,downvotes,created_by,verified_by,edited_by) values(:var1,:var2,:var3,:var4,:var5,:var6,:var7)";
			DB::insert( DB::raw($quer), array('var1' => $route,'var2' => $routes[0]->views+1,'var3' => $routes[0]->upvotes,'var4' => $routes[0]->downvotes,'var5' => $routes[0]->created_by,'var6' => "na",'var7' => $username,));			
   			//DB::statement("REPLACE INTO ".$city."_".$trans."_stop(stop_id,stop_name,stop_lat,stop_lon) values('".$stops_id."' ,'".$t2."' ,'".$t3."' ,'".$t4."')"); 
   			$quer = "replace INTO ".$city."_".$trans."_stop(stop_id,stop_name,stop_lat,stop_lon) values(:var1,:var2,:var3,:var4)";
   			DB::insert( DB::raw($quer), array('var1' => $stops_id,'var2' => $t2,'var3' => $t3,'var4' => $t4,));

   						
   		}
   		else{
   			//$query = "INSERT INTO ".$city."_".$trans."_route(route,stop_id,stop_pos) values('".$route."' ,'".$stopid[0]->stop_id."' ,'".$t1."')";
   			//DB::statement($query); 
   			$quer = "INSERT INTO ".$city."_".$trans."_route(route,stop_id,stop_pos) values(:var1 ,:var2,'".$t1."')"; 
  	        	DB::insert( DB::raw($quer), array('var1' => $route,'var2' => $stopid[0]->stop_id,));
   			//DB::statement("INSERT INTO ".$city."_".$trans."_info(route,created_by,edited_by) values('".$route."' ,'".$username."' ,'".$username."')");
   			//$quer = "REPLACE INTO ".$city."_".$trans."_info(route,created_by,edited_by) values(:var1,:var3)";
   			//DB::insert( DB::raw($quer), array('var1' => $route,'var2' => $username,'var3' => $username,));		
   			
   			$query = "SELECT * FROM ".$city."_".$trans."_info WHERE route = :var";
			$routes = DB::select( DB::raw($query), array('var' => $route,));
			$quer = "REPLACE INTO ".$city."_".$trans."_info(route,views,upvotes,downvotes,created_by,verified_by,edited_by) values(:var1,:var2,:var3,:var4,:var5,:var6,:var7)";
			DB::insert( DB::raw($quer), array('var1' => $route,'var2' => $routes[0]->views+1,'var3' => $routes[0]->upvotes,'var4' => $routes[0]->downvotes,'var5' => $username,'var6' => "na",'var7' => $username,));							
   			
   			//DB::statement("REPLACE INTO ".$city."_".$trans."_stop(stop_id,stop_name,stop_lat,stop_lon) values('".$stopid[0]->stop_id."' ,'".$t2."' ,'".$t3."' ,'".$t4."')");
   			$quer = "replace INTO ".$city."_".$trans."_stop(stop_id,stop_name,stop_lat,stop_lon) values(:var1,:var2,:var3,:var4)";
   			DB::insert( DB::raw($quer), array('var1' => $stopid[0]->stop_id,'var2' => $t2,'var3' => $t3,'var4' => $t4,));
   			

   				
   				  						
   			}
	}
}

function edit_curr_route(){
	
	$route = Session::get('route');
    	$city = Session::get('city');
    	$trans = Session::get('trans');
	$table = $city.'_'.$trans.'_route';	
	
	if($route!=""){
	$res = DB::table($table)->select('route')->where('route',$route)->distinct()->get();
	
	if(sizeof($res)==1){
		
		$stops = DB::table($table)->where('route',$route)->get();
		$query = "select * from ".$city.'_'.$trans."_route , ".$city.'_'.$trans."_stop where route = :var and ".$city.'_'.$trans."_route.stop_id = ".$city.'_'.$trans."_stop.stop_id ORDER BY ABS(stop_pos);";
		$stops = DB::select( DB::raw($query), array('var' => $route,));
		return View::make('edit_route')->with('datam',array($stops,$route));
	}
	
	}
	else{
    		echo '<script>window.alert("Please select a route!");</script>';
    		return $this->route_init();
    	}

}

function download_app(){
	$name = public_path().'/files/app-debug.apk';
	header('Content-Description: File Transfer');
    	header('Content-Type: application/octet-stream');
    	header('Content-Disposition: attachment; filename="'.basename($name).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($name));
    	readfile($name);
}

function download_route_app(){
	$name = public_path().'/files/RouteManager-debug.apk';
	header('Content-Description: File Transfer');
    	header('Content-Type: application/octet-stream');
    	header('Content-Disposition: attachment; filename="'.basename($name).'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($name));
    	readfile($name);
}

}


