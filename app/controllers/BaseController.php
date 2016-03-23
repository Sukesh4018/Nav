<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}
	
	
public  function rmdir_recursive($dir) {
        	foreach(scandir($dir) as $file) {
          		 if ('.' === $file || '..' === $file) continue;
          		 if (is_dir("$dir/$file")) $this->rmdir_recursive("$dir/$file");
          		 else unlink("$dir/$file");
       		}
     
      		 rmdir($dir);
}

public function hello(){
		return "hello word";
}


function onCreate($city,$trans){
	$routes = DB::table($city.'_routes')->select('route_id')->get();	
	$stops_id = 0;
	$username = Auth::user()->username;
	foreach($routes as $route){	
	$query = "SELECT DISTINCT ". $city."_stops.stop_id, ". $city."_stops.stop_name, ". $city."_stops.stop_lat, ". $city."_stops.stop_lon
  	FROM ". $city."_trips
  	INNER JOIN ". $city."_stop_times ON ". $city."_stop_times.trip_id = ". $city."_trips.trip_id
  	INNER JOIN ". $city."_stops ON ". $city."_stops.stop_id = ". $city."_stop_times.stop_id
  	WHERE route_id = :var";
  	
  	$stops = DB::select( DB::raw($query), array('var' => $route->route_id,));
  		$i = 0;
  		
  		foreach($stops as $stop){
   			$i = $i+1;
   			//DB::statement("INSERT INTO ".$city."_".$trans."_data(route,stop_name,stop_pos,stop_lat,stop_lon) values('".$route->route_id."' ,'".$stop->stop_name."' ,'".$i."' ,'".$stop->stop_lat."' ,'".$stop->stop_lon."')"); 
   			
   			
   			$query = "SELECT stop_id FROM ".$city."_".$trans."_stop WHERE stop_name = :var"; 

  	                $stopid = DB::select( DB::raw($query), array('var' => $stop->stop_name,));
   			if(sizeof($stopid)!=1){
   				$stops_id = $stops_id+1;
   				//DB::connection()->getPdo()->quote("INSERT INTO ".$city."_".$trans."_route(route,stop_id,stop_pos) values('".$route->route_id."' ,'".$stops_id."' ,'".$i."')"); 
   				$quer = "INSERT INTO ".$city."_".$trans."_route(route,stop_id,stop_pos) values(:var1 ,:var2,'".$i."')"; 
  	        		DB::insert( DB::raw($quer), array('var1' => $route->route_id,'var2' => $stops_id,));
   						  			
   				//DB::connection()->getPdo()->quote("INSERT INTO ".$city."_".$trans."_stop(stop_id,stop_name,stop_lat,stop_lon) values('".$stops_id."' ,'".$stop->stop_name."' ,'".$stop->stop_lat."' ,'".$stop->stop_lon."')");
   				$quer = "INSERT INTO ".$city."_".$trans."_stop(stop_id,stop_name,stop_lat,stop_lon) values(:var1,:var2,:var3,:var4)";
   				DB::insert( DB::raw($quer), array('var1' => $stops_id,'var2' => $stop->stop_name,'var3' => $stop->stop_lat,'var4' => $stop->stop_lon,));
   				 			
   			
   			}
   			else{
   				
   				//print_r("match".$stopid[0]->stop_id." <br>");
   				//DB::connection()->getPdo()->quote("INSERT INTO ".$city."_".$trans."_route(route,stop_id,stop_pos) values('".$route->route_id."' ,'".$stopid[0]->stop_id."' ,'".$i."')"); 
   				$quer = "INSERT INTO ".$city."_".$trans."_route(route,stop_id,stop_pos) values(:var1 ,:var2,'".$i."')"; 
  	        		DB::insert( DB::raw($quer), array('var1' => $route->route_id,'var2' => $stopid[0]->stop_id,));
   					
   				  						
   			}		
  		} 
  		
  		
  	}
  	
  	$routes_added = DB::select( DB::raw("SELECT distinct(route) FROM ".$city."_".$trans."_route")); 
	foreach($routes_added as $elem){
		//DB::connection()->getPdo()->quote("INSERT INTO ".$city."_".$trans."_info(route,created_by,edited_by) values('".$elem->route."' ,'".$username."' ,'".$username."')");
		$quer = "INSERT INTO ".$city."_".$trans."_info(route,created_by,edited_by) values(:var1,:var2,:var3)"; 
  	        DB::insert( DB::raw($quer), array('var1' => $elem->route,'var2' => $username,'var3' => $username,));
	}
  	
  	DB::statement("drop table ".$city."_agency");
  	DB::statement("drop table ".$city."_routes");
  	DB::statement("drop table ".$city."_stop_times");
  	DB::statement("drop table ".$city."_stops");
  	DB::statement("drop table ".$city."_calendar");
  	DB::statement("drop table ".$city."_trips");
  	
  	
}

//For GTFS zip
public function speed_load($direc,$city,$trans){
	set_time_limit(0);
	$city = strtolower($city);
	$trans = strtolower($trans);
	$dir = $direc;
	$files = array_diff(scandir($dir), array('..', '.'));
	//print_r($files);
	$lim = count($files)+2;
	//echo '</br>'.$lim ."</br>";
	DB::statement("REPLACE INTO cities(name,transport_corp) values('".$city."','".$trans."')");
	for($i = 2;$i<$lim;$i++){
		$tab = substr($files[$i],0,-4);
		if($tab=="routes"||$tab=="agency"||$tab=="calendar"||$tab=="stops"||$tab=="stop_times"||$tab=="trips"){
		$table_name = $city.'_'.$tab;
		$path = $dir.'/'.$files[$i];
		//echo '</br>'.'</br>'.$table_name.'    '.$path.'</br>';
		$raw = file_get_contents($path);
		$lines1 = explode("\n", $raw);
		$tableHeaders = $lines1[0];
		$tableHeaders = str_replace(' ', '_', $tableHeaders);
		$tableHeaders = str_replace('/', '_', $tableHeaders);
		$tableHeaders = str_replace(array( '(', ')','?' ), '', $tableHeaders);
		$tableHeaders = "(".$tableHeaders.")";
		$fields = explode(",",$lines1[0]);
		$field = "(";
		foreach($fields as $element) {
			$element = str_replace(' ', '_', $element);
			$element = str_replace('/', '_', $element);
			$element = str_replace(array( '(', ')','?' ), '', $element);
			$field .=  "$element varchar(500),";
		}
		$field  = substr($field , 0, -1) . ")";
		
		$create_table = "create table " .$table_name." ".$field;
		//echo $create_table.'</br>';
		DB::statement($create_table);//or die('tables cannot be created.<br>' . mysql_error());
		$load ="LOAD DATA INFILE '$path' INTO TABLE $table_name
		FIELDS TERMINATED BY ','
		LINES TERMINATED BY '\\n'
		IGNORE 1 LINES ".$tableHeaders;
		DB::unprepared($load)or die('  Error Loading Data File.<br>' . mysql_error());
		}
	
	}

	$sqlAgency = "ALTER TABLE ".$city.'_'."agency
	ADD PRIMARY KEY (agency_id)";

	$sqlRoutes = "ALTER TABLE ".$city.'_'."routes
	ADD PRIMARY KEY (route_id)";

	$sqlStops = "ALTER TABLE ".$city.'_'."stops
	ADD PRIMARY KEY (stop_id)";

	$sqlTrips = "ALTER TABLE ".$city.'_'."trips
	ADD PRIMARY KEY (trip_id)";
	
	$sanitize_trips = "UPDATE ".$city.'_'."trips SET trip_id = REPLACE(trip_id,'\r','')";
	
	
	DB::statement($sqlAgency);
	DB::statement($sqlRoutes);
	DB::statement($sqlStops);
	DB::statement($sqlTrips);
	DB::statement($sanitize_trips);
	
	//$create  = "DROP TABLE IF EXISTS ".$city.'_'.$trans."_data";
	//DB::statement($create);
	//$create  = "create table ".$city.'_'.$trans."_data(route varchar(500), stop_name varchar(500), stop_pos varchar(500), stop_lat varchar(500), stop_lon varchar(500))";
	//DB::statement($create);
	$create  = "DROP TABLE IF EXISTS ".$city.'_'.$trans."_route";
	DB::statement($create);
	$create  = "create table ".$city.'_'.$trans."_route(route varchar(500), stop_id INT, stop_pos INT)";
	DB::statement($create);
	$create  = "DROP TABLE IF EXISTS ".$city.'_'.$trans."_stop";
	DB::statement($create);
	$create  = "create table ".$city.'_'.$trans."_stop(stop_id INT, stop_name varchar(500), stop_lat varchar(500), stop_lon varchar(500))";
	DB::statement($create);
	$create  = "DROP TABLE IF EXISTS ".$city.'_'.$trans."_info";
	DB::statement($create);
	$create  = "create table ".$city.'_'.$trans."_info(route varchar(500),upvotes INT default 0, downvotes INT default 0 , views INT default 0, created_by varchar(500) default 'admin',verified_by varchar(500) default 'admin',edited_by varchar(500) default 'admin' ,PRIMARY KEY(route))";
	DB::statement($create);
	
	$stops = "ALTER TABLE ".$city.'_'.$trans."_stop
	ADD PRIMARY KEY (stop_id,stop_name)";
	DB::statement($stops);
	
	$this->onCreate($city,$trans);
	
	$this->rmdir_recursive($direc);
		
}

//For csv file
public function load_file1($city,$trans,$filename){
	$city = strtolower($city);
	$trans = strtolower($trans);
	$username = Auth::user()->username;
	$create  = "DROP TABLE IF EXISTS ".$city.'_'.$trans."_route";
	DB::statement($create);
	$create  = "create table ".$city.'_'.$trans."_route(route varchar(500), stop_id INT, stop_pos INT)";
	DB::statement($create);
	$create  = "DROP TABLE IF EXISTS ".$city.'_'.$trans."_stop";
	DB::statement($create);
	$create  = "create table ".$city.'_'.$trans."_stop(stop_id INT, stop_name varchar(500), stop_lat varchar(500), stop_lon varchar(500))";
	DB::statement($create);
	$create  = "create table ".$city.'_'.$trans."_info(route varchar(500),upvotes INT default 0, downvotes INT default 0 , views INT default 0, created_by varchar(500) default 'admin',verified_by varchar(500) default 'admin',edited_by varchar(500) default 'admin' ,PRIMARY KEY(route))";
	DB::statement($create);
	$stops = "ALTER TABLE ".$city.'_'.$trans."_stop
	ADD PRIMARY KEY (stop_id,stop_name)";
	DB::statement($stops);
	DB::statement("REPLACE INTO cities(name,transport_corp) values('".$city."','".$trans."')");
	$path = public_path()."/";
	system("cd ".$path);
	system("java Conv ".$filename." ".$path);
	$stop = public_path().'/'.$filename."_s.csv";
	$route = public_path().'/'.$filename."_r.csv";
	$stop_table = $city.'_'.$trans."_stop";
	$route_table = $city.'_'.$trans."_route";
	$load ="LOAD DATA INFILE '$stop' INTO TABLE $stop_table
		FIELDS TERMINATED BY ','
		LINES TERMINATED BY '\\n'";
	DB::unprepared($load)or die('  Error Loading Data File.<br>' . mysql_error());
	$load ="LOAD DATA INFILE '$route' INTO TABLE $route_table
		FIELDS TERMINATED BY ','
		LINES TERMINATED BY '\\n'";
	DB::unprepared($load)or die('  Error Loading Data File.<br>' . mysql_error());
	$routes_added = DB::select( DB::raw("SELECT distinct(route) FROM ".$city."_".$trans."_route")); 
	foreach($routes_added as $elem){
		 //DB::statement("INSERT INTO ".$city."_".$trans."_info(route,created_by,edited_by) values('".$elem->route."' ,'".$username."' ,'".$username."')");
		 $quer = "INSERT INTO ".$city."_".$trans."_info(route,created_by,edited_by) values(:var1,:var2,:var3)"; 
  	        DB::insert( DB::raw($quer), array('var1' => $elem->route,'var2' => $username,'var3' => $username,));
	}

	system("rm ".$stop);
	system("rm ".$route);
	system("rm ".public_path().'/'.$filename.".csv");
}

public function load_file($city,$trans,$targetzip){
	$city = strtolower($city);
	$trans = strtolower($trans);
	$create  = "DROP TABLE IF EXISTS ".$city.'_'.$trans."_route";
	DB::statement($create);
	$create  = "create table ".$city.'_'.$trans."_route(route varchar(500), stop_id INT, stop_pos INT)";
	DB::statement($create);
	$create  = "DROP TABLE IF EXISTS ".$city.'_'.$trans."_stop";
	DB::statement($create);
	$create  = "create table ".$city.'_'.$trans."_stop(stop_id INT, stop_name varchar(500), stop_lat varchar(500), stop_lon varchar(500))";
	DB::statement($create);
	$create  = "create table ".$city.'_'.$trans."_info(route varchar(500),upvotes INT default 0, downvotes INT default 0 , views INT default 0, created_by varchar(500) default 'admin',verified_by varchar(500) default 'admin',edited_by varchar(500) default 'admin' ,PRIMARY KEY(route))";
	DB::statement($create);
	$stops = "ALTER TABLE ".$city.'_'.$trans."_stop
	ADD PRIMARY KEY (stop_id,stop_name)";
	DB::statement($stops);
	
	DB::statement("REPLACE INTO cities(name,transport_corp) values('".$city."','".$trans."')");
	$myfile = fopen($targetzip, "r") or die("Unable to open file!");
	$stop = fopen(dirname(__FILE__).'/'."temp_stop.csv", "w") or die("Unable to open file!");
	$route= fopen(dirname(__FILE__).'/'."temp_route.csv", "w") or die("Unable to open file!");
	$stops_id = 0;
	while(!feof($myfile)) {
	
  	$line = explode(",", fgets($myfile));
  	
  	if(sizeof($line)>=2){
  		$query = "SELECT stop_id FROM ".$city."_".$trans."_stop WHERE stop_name = :var"; 
  	        $stopid = DB::select( DB::raw($query), array('var' => $line[1],));
  	        $query  = "SELECT max(stop_pos) as pos FROM ".$city."_".$trans."_route WHERE route = :var group by route"; 
  	        $pos = DB::select( DB::raw($query), array('var' => $line[0],));
  	        if(sizeof($pos)==0){
  	        	$pos = 1;
  	        }
  	        else{
  	        	$pos = $pos[0]->pos+1;
  	        }
  	        
  		if(sizeof($line)==2){
  		$line[1] = str_replace(array("\n", "\r"), '', $line[1]);
  			if(sizeof($stopid)!=1){
   				$stops_id = $stops_id+1;
   				
   				$dat0 = $line[0]. ",".$stops_id.','.$pos;
   				$dat1 = $stops_id.','.$line[1].','."Not Available".','."Not Available";
   				fwrite($route, $dat0);
   				fwrite($route,"\n");
   				fwrite($stop, $dat1);
   				fwrite($stop,"\n");
   				//DB::statement("INSERT INTO ".$city."_".$trans."_route(route,stop_id,stop_pos) values('".$line[0]."' ,'".$stops_id."' ,'".$pos."')"); 
   				  				  			
   				//DB::statement("INSERT INTO ".$city."_".$trans."_stop(stop_id,stop_name,stop_lat,stop_lon) values('".$stops_id."' ,'".$line[1]."' ,'"."Not Available"."' ,'"."Not Available"."')"); 
   				
   				
   			
   			}
   			else{
   				
   				//print_r("match".$stopid[0]->stop_id." <br>");
   				$dat0 = $line[0].','.$stopid[0]->stop_id.','.$pos;
   				fwrite($route, $dat0);
   				fwrite($route,"\n");
   				
   				//DB::statement("INSERT INTO ".$city."_".$trans."_route(route,stop_id,stop_pos) values('".$line[0]."' ,'".$stopid[0]->stop_id."' ,'".$pos."')");  
   				
   				  						
   			}
  		}
  		else{
  		if(sizeof($line)==4){
  		$line[1] = str_replace(array("\n", "\r"), '', $line[1]);
  		$line[3] = str_replace(array("\n", "\r"), '', $line[3]);
  			if(sizeof($stopid)!=1){
   				$stops_id = $stops_id+1;
   				
   				$dat0 = $line[0]. ",".$stops_id.','.$pos;
   				$dat1 = $stops_id.','.$line[1].','.$line[2].','.$line[3];
   				fwrite($route, $dat0);
   				fwrite($route,"\n");
   				fwrite($stop, $dat1);
   				fwrite($stop,"\n");
   				
   				//DB::statement("INSERT INTO ".$city."_".$trans."_route(route,stop_id,stop_pos) values('".$line[0]."' ,'".$stops_id."' ,'".$pos."')"); 
   				  				  			
   				//DB::statement("INSERT INTO ".$city."_".$trans."_stop(stop_id,stop_name,stop_lat,stop_lon) values('".$stops_id."' ,'".$line[1]."' ,'".$line[2]."' ,'".$line[3]."')");  
   					
   			}
   			else{
   				
   				//print_r("match".$stopid[0]->stop_id." <br>");
   				$dat0 = $line[0].','.$stopid[0]->stop_id.','.$pos;
   				fwrite($route, $dat0);
   				fwrite($route,"\n");
   				//DB::statement("INSERT INTO ".$city."_".$trans."_route(route,stop_id,stop_pos) values('".$line[0]."' ,'".$stopid[0]->stop_id."' ,'".$pos."')");  
   				
   				  						
   			}
   		}
  		}
  		
	}
	}
	fclose($route);
	fclose($stop);
	fclose($myfile);
	$path = dirname(__FILE__).'/'."upload_file";
	$path1 = dirname(__FILE__).'/'."temp_stop.csv";
	$path2 = dirname(__FILE__).'/'."temp_route.csv";
	//$this->rmdir_recursive($path1);
	//$this->rmdir_recursive($path2);
	$this->rmdir_recursive($path);
	
}
public function upload_and_extract(){
	set_time_limit(0);
	$file = Input::file('zip_file');
	$inp = Input::all();
	$city = $inp['city'];
	$trans = $inp['trans_agen'];
   	if($file->getClientOriginalName()) {
    		$filename = $file->getClientOriginalName();
    		$type = $file->getClientOriginalExtension();
    		$name = explode(".", $filename);
    		$accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
    		foreach($accepted_types as $mime_type) {
    			if($mime_type == $type) {
    				$okay = true;
    				break;
    			} 
    		}
     
    		$continue = strtolower($name[1]) == 'zip' ? true : false;
    		if(!$continue) {
    			$message = "The file you are trying to upload is not a .zip file. Please try again.";
    		}
     
      /* PHP current path */
      		$path = dirname(__FILE__).'/';  // absolute path to the directory where zipper.php is in
      		$filenoext = basename ($filename, '.zip');  // absolute path to the directory where zipper.php is in (lowercase)
      		$filenoext = basename ($filenoext, '.ZIP');  // absolute path to the directory where zipper.php is in (when uppercase)
      		$targetdir = $path . $filenoext; // target directory
      		$targetzip = $targetdir.'/'. $filename; // target zip file
      /* create directory if not exists', otherwise overwrite */
      /* target directory is same as filename without extension */
     
      		if (is_dir($targetdir))  $this->rmdir_recursive ( $targetdir);
      		mkdir($targetdir, 0777);
      /* here it is really happening */
    		if($file->move($targetdir, $filename)) {
    			system("chmod -R 777 ".$path);	
    			$zip = new ZipArchive();
    			$x = $zip->open($targetzip);  // open the zip file to extract
    			if ($x === true) {
    				$zip->extractTo($targetdir); // place in the directory with same name  
    				$zip->close();
    				unlink($targetzip);
    			}
    			system("chmod -R 777 /var/www/html/Nav/app/");	
    			$message = "Your .zip file was uploaded and unpacked.";
    		} else {	
    			$message = "There was a problem with the upload. Please try again.";
    		}
    		//echo '</br>'.$message;
    		$this->speed_load($targetdir,$city,$trans);
    		echo '<script>window.alert("Successfully uploaded the File!");</script>';

    	}
    	
    	  	  return View::make('static_uploadzip');
    	
}

//For csv file
function file_upload(){
	$file = Input::file('zip_file');
	$inp = Input::all();
	$city = $inp['city'];
	$trans = $inp['trans_agen'];
		if($file->getClientOriginalName()) {
    		$filename = $file->getClientOriginalName();
    		$type = $file->getClientOriginalExtension();
    		$name = explode(".", $filename);
    		
    		$continue = strtolower($name[1]) == 'csv' ? true : false;
    		if(!$continue) {
    			$message = "The file you are trying to upload is not a .zip file. Please try again.";
    		}
     
      /* PHP current path */
      		$path = public_path().'/';  // absolute path to the directory where zipper.php is in
      		$filenoext = basename ($filename, '.csv');  // absolute path to the directory where zipper.php is in (lowercase)
      		$filenoext = basename ($filenoext, '.CSV');  // absolute path to the directory where zipper.php is in (when uppercase)
      		$targetdir = $path ; // target directory
      		$targetzip = $targetdir.'/'. $filename; // target zip file
      /* create directory if not exists', otherwise overwrite */
      /* target directory is same as filename without extension */
     
      		//if (is_dir($targetdir))  $this->rmdir_recursive ( $targetdir);
      		//mkdir($targetdir, 0777);
      /* here it is really happening */
    		if($file->move($targetdir, $filename)) {
    			system("chmod -R 777 ".$path);	
    			$zip = new ZipArchive();
    			$x = $zip->open($targetzip);  // open the zip file to extract
    			if ($x === true) {
    				$zip->extractTo($targetdir); // place in the directory with same name  
    				$zip->close();
    				unlink($targetzip);
    			}
    			system("chmod -R 777 /var/www/html/Nav/app/");	
    			$message = "Your .zip file was uploaded and unpacked.";
    		} else {	
    			$message = "There was a problem with the upload. Please try again.";
    		}
    		//echo '</br>'.$message;
    		$this->load_file1($city,$trans,$name[0]);
    		echo '<script>window.alert("Successfully uploaded the File!");</script>';

    	}
    	return View::make('upload_file');
	
}



}
