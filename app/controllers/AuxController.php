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

}
