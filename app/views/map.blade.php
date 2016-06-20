<?php  
if(is_array($data)){
	$stops = $data[0];
	$routes = $data[1];
	$stops_data = array();
	$city = Session::get('city');
	$trans = Session::get('trans');
	$current_route = Session::get('route');
	$stop_names = "";
	if($stops != 'get'){
	$i = 0;
	foreach($stops as $stop){
    		$stops_data[$i] = $stop;
    		$stop_names = $stop_names.",".$stop->stop_name;
   		 $i = $i+1;
  	} 
  	}
  	Session::put('stop_names',$stop_names);
  	$i = 0;
  	foreach($routes as $route){
    		$routes_data[$i] = $route->route;
   		 $i = $i+1;
  	}
  	$data_of_route = DB::table($city.'_'.$trans.'_info')->where('route','=',$current_route)->get();
  	
  }
?>

 @include('up_map')
 <title>Stops Info</title>

<div class="btn-group" role="group" aria-label="..." style ="float:left;margin-top:0px;margin-left:15px;"; >
  <button id = "font" onclick="maximizeText()" type="button" class="btn btn-default  btn-group-lg">A+</button>
  <button id = "font" onclick="minimizeText()" type="button" class="btn btn-default  btn-group-lg">A-</button>
</div>






<script>
  $(function() {
    var availableroute = <?php echo json_encode($routes_data)?>;
    $( "#route" ).autocomplete({
      source: availableroute
    });
  });
</script>
  


<div class="container-fluid" align="left" style="width:100%;">

  
  {{ Form::open(array('url'=>'main','method' => 'POST','style'=>'display:inline-block;float:right;')) }}

  
  {{ Form::label('route', 'Route: ') }}
  <input type="text" id = "route" name="route" required style="height:40px;width:400px;display:inline-block;;">

  <button type="submit" class="btn btn-primary btn-md " value="Submit">Go</button>
  

{{ Form::close() }}

<p style="font-size:24px;display: inline; margin-left:100px;float:left;"><nobr>
<?php 
if(is_array($data)){
if(sizeof($data)==4){
Session::put('route',$data[2]); 
echo '<a style="color: #000000;"data-toggle="modal" data-target="#route_modal" href="#">Route:  " '.$data[2].'"</a></nobr></p>';
if(isset($current_route)){
      		if(sizeof($data_of_route)>0){
      			if($data_of_route[0]->verified_status == 0){
      				echo '<p style="color:red;"> Unverfied</p>';
      			}
      			else{
      				echo '<p style="color:green;"> Verified</p>';
      			}
      		}
      	}
 } 
} 
?>


</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Select Language</h4>
      </div>
      <div class="modal-body">
      <table id = "request" style = "font-size:24px;width:100%; " class="table  table-condensed f11 table-hover">
	<tr><td>Hindi</td><td>Telugu</td><td>Tamil</td></tr>
	<tr><td>Kannada</td><td>Gujarati</td><td>Bengali</td></tr>
	<tr><td>Gurmukhi</td><td>Malayalam</td><td>Odiya</td></tr>
	<tr><td>English</td><td>Hebrew</td><td>Russian</td></tr>
      </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="stop" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id = "stop_heading">Routes through Stop</h4>
      </div>
      <div class="modal-body" id = "stop-info">
      <?php echo Session::get('route'); ?>    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="route_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" ><?php echo Session::get('route'); ?></h4>
      </div>
      <div class="modal-body" >
      <?php
      if(isset($current_route)){
      		//$data_of_route = DB::table($city.'_'.$trans.'_info')->where('route','=',$current_route)->get();
      		if(sizeof($data_of_route)>0){
      			echo '<div>';
			echo "Total Views : ".$data_of_route[0]->views.'</br>';
			echo "Upvotes : ".$data_of_route[0]->upvotes.'</br>';
			echo "Downvotes : ".$data_of_route[0]->downvotes.'</br>';
			echo "Created by : ".$data_of_route[0]->created_by.'</br>';
			echo "Edited by : ".$data_of_route[0]->edited_by.'</br>';
			echo "Verified by : ".$data_of_route[0]->verified_by.'</br>';
			echo '</div>';
		}
	}
      ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="feedback" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style=" color:green;">Please provide the Feedback</h4>
      </div>
      <div class="modal-body">
     
      <p style="font-size:16px;display: inline; margin-left:100px;float:left;"><nobr>
<?php 
if(is_array($data)){if(sizeof($data)==4){Session::put('route',$data[2]);
$info = $data[3];
echo ' Does the route dispaly the correct data?<br><br><br>&nbsp&nbsp<div style="display: inline;"><button id = "upvote" class="upvotebuttonclick btn btn-primary btn-md " type="button" style="display: inline;"  " >Correct</button> &nbsp<p id = "upvote_text" style="display: inline;">'.$info[0]->upvotes." </p>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
echo '<button id = "downvote" class="downvotebuttonclick btn btn-danger btn-md "type="button" >Incorrect</button>&nbsp <p id = "downvote_text" style="display: inline; ">'.$info[0]->downvotes."</p> </div>";
}
} 
?>
</nobr></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="nav" class="btn-group">
	<button id = "stops" type="button" class="btn btn-primary btn-lg btn-block" >Stops</button><br><br>
	<label for="stops" class="hidden">Get the stops in the route</label>
  	<button id = "map" type="button" class="btn btn-primary btn-lg btn-block" >Map</button><br><br>
  	<label for="map" class="hidden">Plot the stops on a map</label>
  	
  	{{ Form::open(array('url'=>'download_route','method' => 'GET')) }}
		{{ Form::submit('Download',['class' =>'btn btn-primary btn-block btn-lg ','id' => 'Download_the_route']) }}
	{{ Form::close() }}
	<label for="Download_the_route" class="hidden">Download the current route</label>
	<button id = "transliterate_button" type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#myModal">Transliterate</button><br><br>
	<label for="transliterate_button" class="hidden">Click to select the language</label>
	{{ Form::open(array('url'=>'list_route','method' => 'GET')) }}
		{{ Form::submit('All Routes',['class' =>'btn btn-primary btn-block btn-lg ','id' => 'list_all_routes_button']) }}
	{{ Form::close() }}
	<label for="list_all_routes_button" class="hidden">Click to display all the routes</label>
	<?php
	if (Auth::check()){
	echo Form::open(array('url'=>'edit_this_route','method' => 'GET')) ;
		echo Form::submit('Edit Route',['class' =>'btn btn-primary btn-block btn-lg ','id' => 'edit_this_route_button']) ;
	echo Form::close() ;
	echo '<label for="edit_this_route_button" class="hidden">Edit the current route</label>';
	}
	?>
	<button id = "feedback_button" type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#feedback">Feedback</button><br><br>
	<label for="feedback_button" class="hidden">Click to Give the Feedback for the current route</label>
</div>

<div class="container-fluid" align="left" style="width:899px; height:540px;">
  <div id="map-canvas" style="width: 100%; height: 90%;overflow: auto;"></div>
</div>

<script>

var route_flag =  <?php if($data[0]!='get'){if(isset($stops_data)){echo "true";}else{echo "false";}}else{echo "false";} ?>;
var points = <?php if($data[0]!='get'){if(isset($stops_data)){echo json_encode($stops_data);}else{echo "[]";}}else{echo "[]";}?>;  
var city = <?php echo json_encode($city); ?>;
var trans = <?php echo json_encode($trans); ?>;
var current_route = <?php echo json_encode($current_route); ?>;

if(points.length==0&&route_flag){
	document.getElementById("map-canvas").innerHTML = "<h1><i>No Such route Exists....</i></h1>";
}
else{
	var disp = "";
	for(var temp =0; temp<points.length;temp++){
		 disp = disp+points[temp].stop_pos+".  "+'<a data-toggle="modal" class="stoplinks" style="color: #000000;" data-datac="'+points[temp].stop_name+'" data-target="#stop" href="#">'+points[temp].stop_name+"</a></br>";
	}

document.getElementById("map-canvas").style.overflow = "auto";
document.getElementById("map-canvas").innerHTML = disp;
}
$('#stops').on('click', function (e) {
if(route_flag){	
	document.getElementById("map-canvas").style.overflow = "auto";
	document.getElementById("map-canvas").innerHTML = disp;	
}
else{
    document.getElementById("map-canvas").innerHTML = "<h1><i>Please select route</i></h1>";

  }
});
  
var table = document.getElementById("request");
if (table != null) {
    for (var i = 0; i < table.rows.length; i++) {
        for (var j = 0; j < table.rows[i].cells.length; j++)
        table.rows[i].cells[j].onclick = function () {
            tableText(this);
        };
    }
}

function tableText(tableCell) {
	var cell = tableCell.innerHTML;
    	var tranl = "transl/hi";
    	$('#myModal').modal('hide');
    switch(cell){
    		case "Hindi" :
			tranl =  "transl/hi";
			break;
		case "Telugu" :
			tranl =  "transl/te";
			break;
		case "Tamil" :
			tranl =  "transl/ta";
			break;
		case "Bengali" :
			tranl =  "transl/be";
			break;
		case "Gujarati" :
			tranl =  "transl/gu";
			break;
		case "Kannada" :
			tranl =  "transl/ka";
			break;
		case "Malayalam" :
			tranl =  "transl/ma";
			break;
		case "Gurmukhi" :
			tranl =  "transl/gr";
			break;
		case "Odiya" :
			tranl =  "transl/or";
			break;
		case "English" :
			tranl =  "transl/en";
			break;
		case "Hebrew" :
			tranl =  "transl/he";
			break;
		case "Russian" :
			tranl =  "transl/ru";
			break;
			
    }
    var url = "<?php echo Request::root(); ?>/"+tranl;
    request = $.ajax({
        url: url,
        method:'get',
        cache:false,
        success:function(data){
          //alert('success');
          console.log('sucess');
        },
        error:function(xhr,status,error){
        	alert(error);
        	console.log('error');
            //errors here
        }
    });
    request.done(function (res, textStatus, jqXHR){
        if (res.status = "ok"){     
        	document.getElementById("map-canvas").style.overflow = "auto";
        	var stop_names_transl  = $.parseJSON(res);
        	var temp = "";
        	for(var m in stop_names_transl){
        		temp += (m+".  "+'<a  class="stoplinks" style="color: #000000;" data-datac="'+points[m-1].stop_name+'" data-toggle="modal" data-target="#stop" href="#">'+stop_names_transl[m]+'</a><br>');
        	}
        	disp = temp;
		document.getElementById("map-canvas").innerHTML = disp;
       		console.log('res');
       }
   });
    
}
  

//$('#upvote').on('click', function (e) {
$(document).on('click','.upvotebuttonclick',function() {
var val_text = document.getElementById("upvote_text").innerHTML;
var val =  parseInt(val_text);
document.getElementById("upvote_text").innerHTML = val+1;
var url = "<?php echo Request::root(); ?>/upvote_route";
request = $.ajax({
        url: url,
        method:'post',
        cache:false,
        data: {"city":city,"trans":trans,"route":current_route},
        success:function(data){
          //alert('success');
          console.log('sucess');
        },
        error:function(xhr,status,error){
        	alert(error);
        	console.log('error');
            //errors here
        }
    });
   });

//$('#downvote').on('click', function (e) {
$(document).on('click','.downvotebuttonclick',function() {
var val_text = document.getElementById("downvote_text").innerHTML;
var val =  parseInt(val_text);
document.getElementById("downvote_text").innerHTML = val+1;
var url = "<?php echo Request::root(); ?>/downvote_route";
request = $.ajax({
        url: url,
        method:'post',
        cache:false,
        data: {"city":city,"trans":trans,"route":current_route},
        success:function(data){
          //alert('success');
          console.log('sucess');
        },
        error:function(xhr,status,error){
        	alert(error);
        	 console.log('error');
            //errors here
        }
    });
    request.done(function (res, textStatus, jqXHR){
        if (res.status = "ok"){     
 	console.log(res);
        
       }
   });
   });
    
$(document).on('click','.stoplinks',function() {
  var url = "<?php echo Request::root(); ?>/stop_info/";
  var stop_name = $(this).text();
  var txt = $(this).data('datac');
  txt = txt.replace("/","$**$");
  url = url+txt;
  console.log(txt);
  request = $.ajax({
        url: url,
        method:'get',
        cache:false,
        //dataType:'json',
        data: {},
        success:function(data){
          //alert('success');
          console.log('sucess');
        },
        error:function(xhr,status,error){
        	alert(error);
        	 console.log('error');
         
        }
    });
    request.done(function (res, textStatus, jqXHR){
        if (res.status = "ok"){     
		document.getElementById("stop-info").innerHTML = res;
		document.getElementById("stop_heading").innerHTML = 'Routes through "'+stop_name+'"';
        
       }
   });
});
   
if(document.body.style.fontSize==""){
  document.body.style.fontSize = "1.5em";
}
  

function maximizeText() {
  document.body.style.fontSize = parseFloat(document.body.style.fontSize) + (2 * 0.2) + "em";
} 
function minimizeText() {
  document.body.style.fontSize = parseFloat(document.body.style.fontSize) - (2 * 0.2) + "em";
} 

function callback(response, status) {
    
  if (status == google.maps.DistanceMatrixStatus.OK) {
   var origins = response.originAddresses;
    var destinations = response.destinationAddresses;
    for (var i = 0; i < origins.length; i++) {
      var results = response.rows[i].elements;
      for (var j = 0; j < results.length; j++) {
        var element = results[j];
        var distance = element.distance.text;
        var duration = element.duration.text;
        var from = origins[i];
        var to = destinations[j];
        window.alert(distance+" "+duration);
      }
    }
  }

}

var positions = new Array();
var latit = new Array();
var longit = new Array();
var stopname = new Array();
var ind = 0;



// Credits : http://acleach.me.uk/gmaps/v3/plotaddresses.htm

  // delay between geocode requests - at the time of writing, 100 miliseconds seems to work well
    var delay = 100;


      // ====== Create map objects ======
      //var infowindow = new google.maps.InfoWindow();
      //var latlng = new google.maps.LatLng(-34.397, 150.644);
      /*var mapOptions = {
        zoom: 8,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      }
      */
      var geo = new google.maps.Geocoder(); 
      //var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
      var bounds = new google.maps.LatLngBounds();

      // ====== Geocoding ======
      function getAddress(search, next) {
        geo.geocode({address:search}, function (results,status)
          { 
            // If that was successful
            if (status == google.maps.GeocoderStatus.OK) {
              // Lets assume that the first marker is the one we want
              var p = results[0].geometry.location;
              positions[ind] = p;
              stopname[ind] = search;   
              var lat=p.lat();
              var lng=p.lng();
              latit[ind] = lat;
              longit[ind] = lng;
              ind++;
              // Output the data
                //var msg = 'address="' + search + '" lat=' +lat+ ' lng=' +lng+ '(delay='+delay+'ms)<br>';
                //document.getElementById("map-canvas").innerHTML += msg;
              // Create a marker
              //createMarker(search,lat,lng);
            }
            // ====== Decode the error status ======
            else {
              // === if we were sending the requests to fast, try this one again and increase the delay
              if (status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
                nextAddress--;
                delay++;
              } else {
                var reason="Code "+search+" "+status;
               // alert(reason);
              //  var msg = 'address="' + search + '" error=' +reason+ '(delay='+delay+'ms)<br>';
              //  document.getElementById("map-canvas").innerHTML += msg;
              }   
            }
            next();
          }
        );
      }

	/*
     // ======= Function to create a marker
     function createMarker(add,lat,lng) {
       var contentString = add;
       var marker = new google.maps.Marker({
         position: new google.maps.LatLng(lat,lng),
         map: map,
         zIndex: Math.round(latlng.lat()*-100000)<<5
       });

      google.maps.event.addListener(marker, 'click', function() {
         infowindow.setContent(contentString); 
         infowindow.open(map,marker);
       });

       bounds.extend(marker.position);

     }
	*/
      // ======= An array of locations that we want to Geocode ========
     var addresses = new Array();
     var names  = new Array();
     var count = 0;
     for(var m in points) {
     	if(points[m].stop_lat=="-"){
     		addresses[count] = points[m].stop_name+" Bus Stop, "+city+", India";
     		names[count] = points[m].stop_name;
     		count++;
     	}
     }
     var req_size = count;
    // alert("address length"+addresses.length);
     
      // ======= Global variable to remind us what to do next
      var nextAddress = 0;

      // ======= Function to call the next Geocode operation when the reply comes back

      function theNext() {
        if (nextAddress < addresses.length) {
          setTimeout('getAddress("'+addresses[nextAddress]+'",theNext)', delay);
          nextAddress++;
        } else {
          // We're done. Show map bounds
          //map.fitBounds(bounds);
        }
      }

      // ======= Call that function for the first time =======
     	theNext();
  
// END
function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}

 var dislat = new Array();
 var dislon = new Array();

function initialize() {
if(positions.length==req_size){
//if(req_size>0){alert(names[req_size-1]+positions[req_size-1])};
var stringy = JSON.stringify(positions);
var stop_name = JSON.stringify(stopname);
   	for(k in points){
   		if(points[k].stop_lat=="-"){
   			dislat[k] = latit[k];
   			dislon[k] = longit[k];
   		}
   		else{
			dislat[k] = points[k].stop_lat;
   			dislon[k] = points[k].stop_lon;   		
   		}
   		//names[k]  = points[k].stop_name;
   	}  	
var stops_name = JSON.stringify(names);
var mapOptions = {
    backgroundColor : "FFFFFF",
    zoom: 11,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    center: new google.maps.LatLng(dislat[0], dislon[0])
  };
var map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions); 
var trafficLayer = new google.maps.TrafficLayer();
trafficLayer.setMap(map);
var routePoints = new Array();
var marker = new Array();
for(var m in points) {
  var myLatlng = new google.maps.LatLng(dislat[m],dislon[m]);
  routePoints[m] = myLatlng;
  marker[m] = new google.maps.Marker({
    position: myLatlng,
    map: map,
    title:points[m].stop_pos+" "+points[m].stop_name
  });
  var name = new google.maps.InfoWindow();
  var data = points[m].stop_pos+" "+points[m].stop_name;
(function (marker,data) {
  google.maps.event.addListener(marker, "click", function () {name.setContent(data); name.open(map, this); });
  })(marker[m],data);  
}


//alert(stringy);
//alert(positions.length);
if(positions.length>0){
var url = "<?php echo Request::root(); ?>/geocode_data";
request = $.ajax({
        url: url,
        method:'post',
        cache:false,
        //dataType:'json',
        data: {"city":city,"trans":trans,"stops":stops_name,"dat":stringy},
        success:function(data){
          //alert('success');
          console.log('sucess');
        },
        error:function(xhr,status,error){
        	alert(error);
        	 console.log('error');
            //errors here
        }
    });
    request.done(function (res, textStatus, jqXHR){
        if (res.status = "ok"){     
        //alert(res);
       }
   });
   
  }
  /* 
   // Snap a user-created polyline to roads and draw the snapped path
function runSnapToRoad(path) {
  var pathValues = [];
  for(var k in dislat){
  	 pathValues.push(dislat[k].','.dislon[k]);
  }
  

  $.get('https://roads.googleapis.com/v1/snapToRoads?path=-35.27801,149.12958|-35.28032,149.12907|-35.28099,149.12929|-35.28144,149.12984|-35.28194,149.13003|-35.28282,149.12956|-35.28302,149.12881|-35.28473,149.12836', {
   interpolate: true,
        key:AIzaSyB5mBXMQ3njnxXJLe6chM9vcKvbLADmUfg

    
  }, function(data) {
    processSnapToRoadResponse(data);
    drawSnappedPolyline();
    getAndDrawSpeedLimits();
  });
}

// Store snapped polyline returned by the snap-to-road method.
function processSnapToRoadResponse(data) {
  snappedCoordinates = [];
  placeIdArray = [];
  for (var i = 0; i < data.snappedPoints.length; i++) {
    var latlng = new google.maps.LatLng(
        data.snappedPoints[i].location.latitude,
        data.snappedPoints[i].location.longitude);
    snappedCoordinates.push(latlng);
    placeIdArray.push(data.snappedPoints[i].placeId);
  }
}

// Draws the snapped polyline (after processing snap-to-road response).
function drawSnappedPolyline() {
  var snappedPolyline = new google.maps.Polyline({
    path: snappedCoordinates,
    strokeColor: 'black',
    strokeWeight: 3
  });

  snappedPolyline.setMap(map);
  polylines.push(snappedPolyline);
}

// Gets speed limits (for 100 segments at a time) and draws a polyline
// color-coded by speed limit. Must be called after processing snap-to-road
// response.
function getAndDrawSpeedLimits() {
  for (var i = 0; i <= placeIdArray.length / 100; i++) {
    // Ensure that no query exceeds the max 100 placeID limit.
    var start = i * 100;
    var end = Math.min((i + 1) * 100 - 1, placeIdArray.length);

    drawSpeedLimits(start, end);
  }
}

// Gets speed limits for a 100-segment path and draws a polyline color-coded by
// speed limit. Must be called after processing snap-to-road response.
function drawSpeedLimits(start, end) {
    var placeIdQuery = '';
    for (var i = start; i < end; i++) {
      placeIdQuery += '&placeId=' + placeIdArray[i];
    }

    $.get('https://roads.googleapis.com/v1/speedLimits',
        'key=' + apiKey + placeIdQuery,
        function(speedData) {
          processSpeedLimitResponse(speedData, start);
        }
    );
}

// Draw a polyline segment (up to 100 road segments) color-coded by speed limit.
function processSpeedLimitResponse(speedData, start) {
  var end = start + speedData.speedLimits.length;
  for (var i = 0; i < speedData.speedLimits.length - 1; i++) {
    var speedLimit = speedData.speedLimits[i].speedLimit;
    var color = getColorForSpeed(speedLimit);

    // Take two points for a single-segment polyline.
    var coords = snappedCoordinates.slice(start + i, start + i + 2);

    var snappedPolyline = new google.maps.Polyline({
      path: coords,
      strokeColor: color,
      strokeWeight: 6
    });
    snappedPolyline.setMap(map);
    polylines.push(snappedPolyline);
  }
}

function getColorForSpeed(speed_kph) {
  if (speed_kph <= 40) {
    return 'purple';
  }
  if (speed_kph <= 50) {
    return 'blue';
  }
  if (speed_kph <= 60) {
    return 'green';
  }
  if (speed_kph <= 80) {
    return 'yellow';
  }
  if (speed_kph <= 100) {
    return 'orange';
  }
  return 'red';
}
*/
   
   /*
 var service = new google.maps.DistanceMatrixService();
service.getDistanceMatrix(
  {
     origins: [{lat: points[0].stop_lat, lng: points[0].stop_lon}],
    destinations: [{lat: points[1].stop_lat, lng: points[1].stop_lon}],
    travelMode: google.maps.TravelMode.DRIVING,
    drivingOptions: {
       departureTime: new Date(Date.now()),  // for the time N milliseconds from now.
        trafficModel: "optimistic"
    }
  }, callback);
*/
}
/*
for(var i=0;i<routePoints.length-1;i++){
 var temp  = [routePoints[i],routePoints[i+1]];
 var routePath = new google.maps.Polyline({
    path: temp,
    geodesic: true,
    strokeColor: '#FF0000',
    strokeOpacity: 1.0,
    strokeWeight: 2
  });
   routePath.setMap(map);
  }
  */
}
var map_button = document.getElementById('map');
google.maps.event.addDomListener(map_button, 'click', initialize);
</script>
</body>
</html>


