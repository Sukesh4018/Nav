<?php
set_time_limit(0);
 	$city = Session::get("editCity");
 	$trans = Session::get("editTrans");
 	$cities_data = DB::table($city.'_'.$trans.'_route')->select('route')->distinct()->orderBy('route', 'asc')->get();
 	$i = 0;
 	$city_data = [];
	foreach($cities_data as $city){
    		$city_data[$i] = $city->route;
   		 $i = $i+1;
  	}

?>
 @include('up')
    <title>Add Route</title>
<style>
p.uppercase {
    text-transform: uppercase;
}
</style>
<div id="nav" class="btn-group">

	<a href="mupload" class="btn btn-primary btn-lg btn-block" role="button">Edit Route</a>
  	<a href="add_route" class="btn btn-primary btn-lg btn-block" role="button">Add Route</a>
  	<?php 
  	if(Auth::user()->role!="2"){
  	echo '
  	<a href="upload" class="btn btn-primary btn-lg btn-block" role="button"> GTFS Zip</a>
  	<a href="add_agen" class="btn btn-primary btn-lg btn-block" role="button">Add Agency</a>
  	<a href="upload_file" class="btn btn-primary btn-lg btn-block" role="button">Upload File</a>
  	<a href="delete_route" class="btn btn-primary btn-lg btn-block" role="button">Delete Route</a>
  	';
  	}
  	?>
</div>
	
	

      <div id="section" style = "margin-left: 50px !important;margin-top: 10px !important;">
      <h1>Enter the Route Number you wish to Add</h1>
	<h2>If you don't have the GPS data enter  "-"</h2>
    	<button id = "add" style="display: inline;float:right;" class="btn btn-primary btn-md">Add Stop</button>
    	
    	{{ Form::open(array('url'=>'edit_done','method' => 'POST')) }}
  	{{ Form::hidden('proc', "add") ;}}
    	
  	 <label>Enter Route Number:  <input type="text" id = "route" name="route" required style="height:40px;width:400px;"></label>
  	<input type="hidden" id = "count" name="size" value="0">
  	<input type="hidden"  name="op" value="add">
  	<br><br>
  	<table id = "form" border  "1" style = "font-size:16px;">
  	 
  	
     <script>
	var count = 0;
	var first = 1;
	var flag = true;
	$('#add').on('click', function (e) {
	
		//var newdiv = document.createElement('div');
		//newdiv.innerHTML
		
		var route_entered = document.getElementById('route').value;
		var routes = <?php echo json_encode($city_data)?>;
		for(var m in routes) {
			if(routes[m].toUpperCase().trim() === route_entered.toUpperCase()){
				alert("The route already exists. Please edit it");
				flag = false;
			break;
			}
		}
		if(flag){
		if(first==1){
			var init = "<tr><td> stop_pos</td><td>stop_name  </td><td> stop_lat  </td><td> stop_lon </td></tr> ";
			$('table#form').append(init);
			first++;
		}
		var newdiv="<tr><td><label for='stop_pos"+count+"' class = 'hidden'>stop position</label><input type='text' id=stop_pos"+count+" name= stop_pos"+count+"></td><td>"+
		"<label for='stop_name"+count+"' class = 'hidden'>stop name</label><input type='text'id=stop_name"+count+" name=stop_name"+count+"></td><td>"+
		"<label for='stop_lat"+count+"' class = 'hidden'>stop latitude</label><input type='text' id=stop_lat"+count+" name=stop_lat"+count+"></td><td>"+
		"<label for='stop_lon"+count+"' class = 'hidden'>stop longitude</label><input type='text' id=stop_lon"+count+" name=stop_lon"+count+"></td></tr>";
		$('table#form').append(newdiv);
		/*
		 var newdiv=  count+" "+"<input type='text' name= stop_pos"+count+">"+"&nbsp&nbsp"+
		"<input type='text' name=stop_name"+count+">"+"&nbsp&nbsp"+
		"<input type='text' name=stop_lat"+count+">"+"&nbsp&nbsp"+
		"<input type='text' name=stop_lon"+count+"><br>";
		
		var div = document.getElementById('form');
		div.innerHTML = div.innerHTML + newdiv;
		//document.getElementById("form").appendChild(newdiv);
		*/		
		count++;
		document.getElementById('count').value = count; 
		}
	});
	</script>
     </table>
 
     <br><br>
      {{ Form::submit('Done',['class' =>'btn btn-primary btn-lg btn-block']) }}
      {{ Form::close() }}
     
     </div>
     
