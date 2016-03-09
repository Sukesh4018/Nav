@include('up_map')
 <title>Admin </title>
 
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
	<h1><u>The following requests are pending</u> </h1><br><br>
	<table id = "request" style = "font-size:24px;width:100%; " class="table table-bordered table-condensed f11"></table>
	<?php
		$requests_arr = DB::table('request_volunteer')->get();
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
   	var newdiv ="<tr><td>name</td><td>username</td><td>email</td><td>action</td><td>action</td></tr>"; 
   	for(var m in routes){
   		var aux ='<tr><td>'+routes[m].name+'</td><td>'+routes[m].username+'</td><td>'+routes[m].email+'</td><td>'+'{{ Form::open(array("url"=>"accept_volunteer","method" => "POST","class"=>"navbar-form navbar-left")) }}<input type="hidden" name="user" value = "'+routes[m].username + '">{{ Form::submit("Accept",["class" =>"btn btn-success btn-block btn-lg"]) }}{{ Form::close() }}'+'</td><td>'+'{{ Form::open(array("url"=>"reject_volunteer","method" => "POST","class"=>"navbar-form navbar-left")) }}<input type="hidden"  name="user" value = "'+routes[m].username + '">{{ Form::submit("Reject",["class" =>"btn btn-success btn-block btn-lg"]) }}{{ Form::close() }}'+'</td></tr>';
   		newdiv+= aux;
   	}
   	$('table#request').append(newdiv);
   	jQuery(function($) {
   	$('.expandable').bind('click', function () {
        $(this).children().toggle();
    	});
	});
 </script>

