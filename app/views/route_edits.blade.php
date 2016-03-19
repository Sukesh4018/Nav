@include('up_map')
<style>
html, body, #map-canvas  {
  margin: 0;
  padding: 0;
  height: 100%;
}

#map-canvas {
  width:540px;
  height:460px;
}


</style>
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

		$i = 0;
		$requests_arr = $data;
		$requests = array();
		$stops = array();
		
		foreach($requests_arr as $request){
			$requests[$i] = $request;
			$ids = DB::table('routes_edit_history_help')->where('ID','=',$requests[$i]->ID)->get();
			$stop = array();
			$j = 0;
			foreach($ids as $id){
				$stop[$j] = $id;
				$j++;
			}
			$stops[$i] = $stop;
			$i++;
		}
			$essen = [$requests[0]->city,$requests[0]->trans,$requests[0]->route];
		
			echo '<h1><u>The following changes have been made</h1><br> 
			<p>Route: '.$requests[0]->route.'<br>'.
			'City: '.$requests[0]->city.'<br>'.
			'Transport Corporation: '.$requests[0]->trans.'</u></p> <br><br>';		
	?>
{{ Form::open(array('url'=>'data_verified','method' => 'POST')) }}	  
	 <table id = "request" style = "font-size:24px;width:100%; " class="table  table-condensed f11 table-hover"></table>
	 <button type="submit" class="btn btn-success btn-lg " value="Submit" style="display:inline-block;">Commit</button>
{{ Form::close() }}
<div class="btn-toolbar">
{{ Form::open(array('url'=>'accept_edits','method' => 'GET','style'=>'display:inline-block;float:right;margin-right:5px;')) }}
		{{ Form::submit('Cancel',['class' =>'btn btn-primary btn-block btn-lg  ']) }}
{{ Form::close() }}

{{ Form::open(array('url'=>'ignore_edit','method' => 'POST','style'=>'display:inline-block;float:right;margin-right:20px;')) }}
		<?php 	echo Form::hidden('city',$essen[0]);
			echo Form::hidden('trans',$essen[1]);
			echo Form::hidden('route',$essen[2]);
		?>
		{{ Form::submit('Ignore All',['class' =>'btn btn-danger btn-block btn-lg']) }}
{{ Form::close() }}
</div>


</div>
<div class="container">
  <!-- Modal -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Route Edit Info</h4>
        </div>
        <div class="modal-body">
          <ul id = "myTab" class = "nav nav-tabs">
  		 <li><a href = "#stops" data-toggle = "tab">Stops</a></li>
   		<li class = "active"><a href = "#map" data-toggle = "tab" id="map_tab">Map</a></li>
   	</ul>
   	
    <div id = "myTabContent" class = "tab-content">
	<div class = "tab-pane fade " id = "stops">  </div>
	
	<div class = "tab-pane fade in active" id = "map">
  		<div id="map-canvas" class=""></div>
	</div>
   
    </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
</div>
 <script>
 	var routes = <?php echo json_encode($requests)?>;
 	var stops = <?php echo json_encode($stops)?>;
 	var essen = <?php echo json_encode($essen)?>;
   	var newdiv ='<tr><td>Select</td><td>Revision</td><td>User</td><td>Action</td><td>View</td>Selection</tr>'; 
   	document.getElementById('stops').innerHTML = '';
   	var map;
   	function initialize(m){
		//alert(stops[2][0].stop_name);
   		var points = stops[m];
   		document.getElementById('map-canvas').innerHTML = "";
   		var mapOptions = {
   		zoom: 14,
    		mapTypeId: google.maps.MapTypeId.ROADMAP,
    		center: new google.maps.LatLng(points[0].stop_lat, points[0].stop_lon)
  		};
		map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions); 
		var routePoints = new Array();
		var marker = new Array();
		for(var n in points) {
  			var myLatlng = new google.maps.LatLng(points[n].stop_lat,points[n].stop_lon);
  			routePoints[n] = myLatlng;
  			marker[n] = new google.maps.Marker({
  			  position: myLatlng,
   			 map: map,
   			 title:points[n].stop_pos+" "+points[n].stop_name
  		});
 		 var name = new google.maps.InfoWindow();
 		 var data = points[n].stop_pos+" "+points[n].stop_name;
		(function (marker,data) {
 		 google.maps.event.addListener(marker, "click", function () {name.setContent(data); name.open(map, this); });
 		 })(marker[n],data);  
		}
		
		
   	}
   	
   	
   	for(var m in routes){
   		var aux ='<tr><td><input type="radio" name="select" value="'+essen[0]+','+essen[1]+','+essen[2]+','+routes[m].revision+','+routes[m].edited_by+','+routes[m].action+'"required></td><td>'+routes[m].revision+'</td><td>'+routes[m].edited_by+'</td><td>'+routes[m].action+'</td><td>'+'<button type="button" class="btn btn-primary btn-md" data-toggle="modal" value="'+m+'" data-target="#myModal">View</button>'+'</tr>';
   		newdiv+= aux;	
   	}
   	 newdiv+=' <button type="submit" class="btn btn-primary btn-md " value="Submit">Go</button>{{ Form::close() }}';		
	function stops_data(stop){
		document.getElementById('stops').innerHTML = "";
		//alert(stop[0].stop_name);
		for (var k in stop){
   			document.getElementById('stops').innerHTML += ('<br>'+k+". "+stop[k].stop_name) ;
   		}
	}
	 	
   	$(document).ready(function() {
        $('.btn').click(function() {
        	var m = $(this).attr("value");
        	//alert(m);
        	stops_data(stops[m]);
   		initialize(m);
            //alert($(this).attr("value"));
        });
    	});

   $(function () {
      $('#myTab li:eq(0) a').tab('show');
   });
   
//initialize(0);
var map_button = document.getElementById('map_tab');
//google.maps.event.addDomListener(map_button, 'click', initialize(0));


$("a[href='#map']").on('shown.bs.tab', function() {
   resizeMap();
})

function resizeMap() {
   if(typeof map =="undefined") return;
   setTimeout( function(){resizingMap();} , 400);
}

function resizingMap() {
   if(typeof map =="undefined") return;
   var center = map.getCenter();
   google.maps.event.trigger(map, "resize");
   map.setCenter(center); 
}

$('table#request').append(newdiv);
   	jQuery(function($) {
   	$('.expandable').bind('click', function () {
        $(this).children().toggle();
    	});
});

 </script>


