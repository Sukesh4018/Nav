@include('up_map')
 <title>Verify Edits </title>
 
  <div id="nav" style = "width:250px;" class="btn-group">
	
  	{{ Form::open(array('url'=>'volunteer_requests','method' => 'GET')) }}
		{{ Form::submit('Volunteer Requests',['class' =>'btn btn-primary btn-block btn-lg ']) }}
	{{ Form::close() }}
  	{{ Form::open(array('url'=>'download_route','method' => 'GET')) }}
		{{ Form::submit('Verification',['class' =>'btn btn-primary btn-block btn-lg ']) }}
	{{ Form::close() }}
	{{ Form::open(array('url'=>'list_route','method' => 'GET')) }}
		{{ Form::submit('All Routes',['class' =>'btn btn-primary btn-block btn-lg ']) }}
	{{ Form::close() }}
</div>

<div id="section">
	<h1><u>The following routes have been edited/added</u> </h1><br><br>
	<table id = "request" style = "font-size:24px;width:100%; " class="table table-condensed f11"></table>
	<?php
		$requests_arr = DB::table('routes_edit_history')->groupby('route')->distinct()->get();
		$i = 0;
		$requests = array();
		foreach($requests_arr as $request){
			$requests[$i] = $request;
			$i++;
		}
		
	?>
	

</div>
 <script>
 	var routes = <?php echo json_encode($requests)?>;
   	var newdiv ="<tr><td>Route</td><td>City</td><td>Trans</td><td>Op</td></tr>"; 
   	for(var m in routes){
   		var aux ='<tr><td>'+routes[m].route+'</td><td>'+routes[m].city+'</td><td>'+routes[m].trans+'</td><td>'+'{{ Form::open(array("url"=>"edits_view","method" => "POST","class"=>"navbar-form navbar-left")) }}<input type="hidden" name="route" value = "'+routes[m].route+ '"><input type="hidden" name="city" value = "'+routes[m].city + '"><input type="hidden" name="trans" value = "'+routes[m].trans + '">{{ Form::submit("View",["class" =>"btn btn-primary btn-block btn-md"]) }}{{ Form::close() }}'+'</tr>';
   		newdiv+= aux;
   	}
   	$('table#request').append(newdiv);
   	jQuery(function($) {
   	$('.expandable').bind('click', function () {
        $(this).children().toggle();
    	});
	});
 </script>

