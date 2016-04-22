<?php

class AuxController extends Controller {

function upvote_route(){
	$inp = Input::all();
	$route = $inp['route'];
	$city = $inp['city'];
	$trans = $inp['trans'];

	$query = "SELECT * FROM ".$city."_".$trans."_info WHERE route = :var"; 
  	$routes = DB::select( DB::raw($query), array('var' => $route,));
	$quer = "REPLACE INTO ".$city."_".$trans."_info(route,views,upvotes,downvotes,created_by,verified_by,edited_by) values(:var1,:var2,:var3,:var4,:var5,:var6,:var7)";

   	DB::insert( DB::raw($quer), array('var1' => $route,'var2' => $routes[0]->views,'var3' => $routes[0]->upvotes+1,'var4' => $routes[0]->downvotes,'var5' => $routes[0]->created_by,'var6' => $routes[0]->verified_by,'var7' => $routes[0]->edited_by,));
   	return $routes[0]->upvotes+1;
   	
}

function downvote_route(){
	$inp = Input::all();
	$route = $inp['route'];
	$city = $inp['city'];
	$trans = $inp['trans'];
	$query = "SELECT * FROM ".$city."_".$trans."_info WHERE route = :var"; 
  	$routes = DB::select( DB::raw($query), array('var' => $route,));
	$quer = "REPLACE INTO ".$city."_".$trans."_info(route,views,upvotes,downvotes,created_by,verified_by,edited_by) values(:var1,:var2,:var3,:var4,:var5,:var6,:var7)";
   	DB::insert( DB::raw($quer), array('var1' => $route,'var2' => $routes[0]->views,'var3' => $routes[0]->upvotes,'var4' => $routes[0]->downvotes+1,'var5' => $routes[0]->created_by,'var6' => $routes[0]->verified_by,'var7' => $routes[0]->edited_by,));
   	return $routes[0]->downvotes+1;
}

function accept_volunteer(){
	$inp = Input::all();
	$username = $inp['user'];
	$user = User::where('username', '=', $username)->first();
	$user->role = "1";
	$user->save();
	DB::table('request_volunteer')->where('username','=',$username)->delete();
	return Redirect::to('volunteer_requests');

}

function reject_volunteer(){
	$inp = Input::all();
	$username = $inp['user'];
	DB::table('request_volunteer')->where('username','=',$username)->delete();
	return Redirect::to('volunteer_requests');
}

function list_route_edits(){
	$inp = Input::all();
	$route = $inp['route'];
	$city = $inp['city'];
	$trans = $inp['trans'];
	$data = DB::table('routes_edit_history')->where('city','=',$city)->where('trans','=',$trans)->where('route','=',$route)->get();
	return View::make('route_edits')->with('data',$data);
}

function verified_data_enter(){
	$username = Auth::user()->username;
	$inp = Input::all();
	$data = explode(",",$inp['select']);
	$city = $data[0];
	$trans = $data[1];
	$route = $data[2];
	$rev = $data[3];
	$user = $data[4];
	$op = $data[5];
	$table = $city.'_'.$trans.'_route';	
	$edit = DB::table('routes_edit_history')->where('city','=',$city)->where('trans','=',$trans)->where('route','=',$route)->where('revision','=',$rev)->where('edited_by','=',$user)->where('action','=',$op)->get();
	$ids = DB::table('routes_edit_history_help')->where('ID','=',$edit[0]->ID)->get();
	$count = sizeof($ids);
	$input = array();
	for($i=0;$i<$count;$i++){
	 	$input['stop_pos'.$i] = $i+1;
		$input['stop_name'.$i] = $ids[$i]->stop_name;
		$input['stop_lat'.$i] = $ids[$i]->stop_lat;
		$input['stop_lon'.$i] = $ids[$i]->stop_lon;
		
	}
	
	if($op=="edit"){
	   DB::table($table)->where('route', '=', $route)->delete();
	}
	for($i=0;$i<$count;$i++){
		$t1 = $input['stop_pos'.$i];
		$t2 = $input['stop_name'.$i];
		$t3 = $input['stop_lat'.$i];
		$t4 = $input['stop_lon'.$i];
		
		$query = "SELECT stop_id FROM ".$city."_".$trans."_stop WHERE stop_name = :var"; 
  	        $stopid = DB::select( DB::raw($query), array('var' => $t2,));
  	         	        
   		if(sizeof($stopid)!=1){ //stop name exists
   			$stops_id = $price = DB::table($city."_".$trans."_stop")->max('stop_id');
 			$stops_id = $stops_id+1;
   			 
   			$quer = "INSERT INTO ".$city."_".$trans."_route(route,stop_id,stop_pos) values(:var1 ,:var2,'".$t1."')"; 
  	        	DB::insert( DB::raw($quer), array('var1' => $route,'var2' => $stops_id,));
   			if($op=="edit"){
				$quer = "SELECT * FROM ".$city."_".$trans."_info WHERE route = :var"; 
  	        		$created = DB::select( DB::raw($quer), array('var' => $route,));
  	        		$created_by = $created[0]->created_by;
	  			
   				$query = "SELECT * FROM ".$city."_".$trans."_info WHERE route = :var";
				$routes = DB::select( DB::raw($query), array('var' => $route,));
				$quer = "REPLACE INTO ".$city."_".$trans."_info(route,views,upvotes,downvotes,created_by,verified_by,edited_by) values(:var1,:var2,:var3,:var4,:var5,:var6,:var7)";
				DB::insert( DB::raw($quer), array('var1' => $route,'var2' => $routes[0]->views+1,'var3' => $routes[0]->upvotes,'var4' => $routes[0]->downvotes,'var5' => $created_by,'var6' => $username,'var7' => $user,));
   				
			}
			else{
				
   				$quer = "REPLACE INTO ".$city."_".$trans."_info(route,views,upvotes,downvotes,created_by,verified_by,edited_by) values(:var1,:var2,:var3,:var4,:var5,:var6,:var7)";
				DB::insert( DB::raw($quer), array('var1' => $route,'var2' => $routes[0]->views+1,'var3' => $routes[0]->upvotes,'var4' => $routes[0]->downvotes,'var5' => $username,'var6' => $username,'var7' => $user,));
   				
			}
   				  				  			
   			
   			$quer = "replace INTO ".$city."_".$trans."_stop(stop_id,stop_name,stop_lat,stop_lon) values(:var1,:var2,:var3,:var4)";
   			DB::insert( DB::raw($quer), array('var1' => $stops_id,'var2' => $t2,'var3' => $t3,'var4' => $t4,));
   						
   		}
   		else{
   			 
   			$quer = "INSERT INTO ".$city."_".$trans."_route(route,stop_id,stop_pos) values(:var1 ,:var2,'".$t1."')"; 
  	        	DB::insert( DB::raw($quer), array('var1' => $route,'var2' => $stopid[0]->stop_id,));
   			if($op=="edit"){
				$quer = "SELECT * FROM ".$city."_".$trans."_info WHERE route = :var"; 
  	        		$created = DB::select( DB::raw($quer), array('var' => $route,));
  	        		$created_by = $created[0]->created_by;
	  			
   				$query = "SELECT * FROM ".$city."_".$trans."_info WHERE route = :var";
				$routes = DB::select( DB::raw($query), array('var' => $route,));
				$quer = "REPLACE INTO ".$city."_".$trans."_info(route,views,upvotes,downvotes,created_by,verified_by,edited_by) values(:var1,:var2,:var3,:var4,:var5,:var6,:var7)";
				DB::insert( DB::raw($quer), array('var1' => $route,'var2' => $routes[0]->views+1,'var3' => $routes[0]->upvotes,'var4' => $routes[0]->downvotes,'var5' => $created_by,'var6' => $username,'var7' => $user,));
			}
			else{
				
				$quer = "REPLACE INTO ".$city."_".$trans."_info(route,views,upvotes,downvotes,created_by,verified_by,edited_by) values(:var1,:var2,:var3,:var4,:var5,:var6,:var7)";
				DB::insert( DB::raw($quer), array('var1' => $route,'var2' => $routes[0]->views+1,'var3' => $routes[0]->upvotes,'var4' => $routes[0]->downvotes,'var5' => $username,'var6' => $username,'var7' => $user,));
   				
			}
   			
   			$quer = "replace INTO ".$city."_".$trans."_stop(stop_id,stop_name,stop_lat,stop_lon) values(:var1,:var2,:var3,:var4)";
   			DB::insert( DB::raw($quer), array('var1' => $stopid[0]->stop_id,'var2' => $t2,'var3' => $t3,'var4' => $t4,));
   				
   				  						
   			}
	}
	$ident = DB::table('routes_edit_history')->where('city','=',$city)->where('trans','=',$trans)->where('route','=',$route)->get();
	foreach($ident as $id){
		$ident = DB::table('routes_edit_history_help')->where('ID', '=', $id->ID)->get();
		DB::table('routes_edit_history_help')->where('ID', '=', $id->ID)->delete();
	}
	DB::table('routes_edit_history')->where('route', '=', $route)->delete();
	echo '<script>window.alert("The edit has been Committed!");</script>';
	return View::make('accept_edits');

}

function delete_route_entry_edit(){
	$username = Auth::user()->username;
	$inp = Input::all();
	$route = $inp['route'];
	$city = $inp['city'];
	$trans = $inp['trans'];
	$ident = DB::table('routes_edit_history')->where('city','=',$city)->where('trans','=',$trans)->where('route','=',$route)->get();
	foreach($ident as $id){
		$ident = DB::table('routes_edit_history_help')->where('ID', '=', $id->ID)->get();
		DB::table('routes_edit_history_help')->where('ID', '=', $id->ID)->delete();
		
	}
	DB::table('routes_edit_history')->where('route', '=', $route)->delete();
	echo '<script>window.alert("All the edits have been Ignored!");</script>';
	return View::make('accept_edits');
}

function transliterate($lang){

	$tranl = "Any-Devanagari";
	switch($lang){
		case "hi" :
			$tranl =  "Any-Devanagari";
			break;
		case "te" :
			$tranl =  "Any-Telugu";
			break;
		case "ta" :
			$tranl =  "Any-Tamil";
			break;
		case "be" :
			$tranl =  "Any-Bengali";
			break;
		case "gu" :
			$tranl =  "Any-Gujarati";
			break;
		case "ka" :
			$tranl =  "Any-Kannada";
			break;
		case "ma" :
			$tranl =  "Any-Malayalam";
			break;
		case "gr" :
			$tranl =  "Any-Gurmukhi";
			break;
		case "or" :
			$tranl =  "Any-Oriya";
			break;
		case "en" :
			$tranl =  "Any-Latin";
			break;
		case "he" :
			$tranl =  "Any-Hebrew";
			break;
		case "ru" :
			$tranl =  "Any-ru";
			break;
		
	}
	$route = Session::get('route');
	$stop_names = Session::get('stop_names');
	Session::put('language',$lang);
	$input = $route.$stop_names;
	$city = Session::get('city');
	$trans = Session::get('trans');
	$user =  Auth::user()->username;
	$file = public_path()."/".rand(10,100000).$city.$trans.$user.".txt";
	$command = 'java -jar transl.jar "'.$tranl.'" "'.$input.'" "'.$file.'"' ;
	//echo $command.'<br>';
	system($command);
	$result = [];
	if (file_exists($file)){
		$myfile = fopen($file, "r") or die("Unable to open file!");	
		header('Content-Type: text/html; charset=UTF-8');
		while(!feof($myfile)) {
  			$line = explode(",", fgets($myfile));
  			$route = $line[0];
  			$i = 0;
  			foreach($line as $transl_stop){
  				if($i>0){
  				$res = $i.".   ".$transl_stop.'<br>';
  				//echo $res ;
  				$result[$i] = $transl_stop;
  				}
  				$i++;
  			}
  		}
  	}
  	unlink($file);
  	Session::put('stops_route',$result);
  	echo json_encode($result);
}

function delete_route(){
	$inp = Input::all();
	$route = $inp['route'];;
	$city = Session::get('editCity');
	$trans = Session::get('editTrans');
	$role = Auth::user()->role;
	if($role!="2"){
	DB::table($city.'_'.$trans.'_info')->where('route', '=', $route)->delete();
	DB::table($city.'_'.$trans.'_route')->where('route', '=', $route)->delete();
	return Redirect::to('mupload')->with('success',"The route was deleted successfully!");
	}
	else{
		return "you are not the admin/volunteer. Please return to the portal by clicking <a href='get_search'>here</a>";
	}
		
}

function stop_info($stop){
	$stop = str_replace("$**$","/",$stop);
	$city = Session::get('city');
	$trans = Session::get('trans');
	$data = DB::table($city.'_'.$trans.'_stop')->where('stop_name','=', $stop)->get();
	$stop_id = $data[0]->stop_id;
	$routes = DB::table($city.'_'.$trans.'_route')->where('stop_id','=', $stop_id)->get();
	$result = "";
	foreach ($routes as $route){
		$result = $result.$route->route.'<br>';
	}
	echo $result;
}






}
