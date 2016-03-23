@include('up_map')
 <title>Verify Edits </title>
 
  <div id="nav" style = "width:250px;" class="btn-group">
	<?php 
	if(Auth::user()->role=="0"){
  		echo Form::open(array('url'=>'volunteer_requests','method' => 'GET')) ;
			echo Form::submit('Volunteer Requests',['class' =>'btn btn-primary btn-block btn-lg ']) ;
		echo Form::close() ;
	}
	?>
  	{{ Form::open(array('url'=>'accept_edits','method' => 'GET')) }}
		{{ Form::submit('Verification',['class' =>'btn btn-primary btn-block btn-lg ']) }}
	{{ Form::close() }}
</div>

<div id="section">
	

	
	
	<?php
		$requests_arr = DB::table('routes_edit_history')->groupby('route')->distinct()->get();
		$i = 0;
		$requests = array();
		foreach($requests_arr as $request){
			$requests[$i] = $request;
			$i++;
		}
		if($i==0){
			echo '<h1><u>No Edits are done</u></h1><br><br><br>';
		}
		else{
			echo '<h1><u>The following routes have been edited/added</u> </h1><br><br>';
		}
		
	?>
	<table id = "request" style = "font-size:24px;width:100%; " class="table table-condensed f11 table-hover"></table>
	
	
	

</div>
 <script>
 	var routes = <?php echo json_encode($requests)?>;
 	if(routes.length>0){
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
	}
 </script>

