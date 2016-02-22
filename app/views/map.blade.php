<?php  
if(is_array($data)){
	$stops = $data[0];
	$routes = $data[1];
	$stops_data = array();
	$city = Session::get('city');
	$trans = Session::get('trans');
	if($stops != 'get'){
	$i = 0;
	foreach($stops as $stop){
    		$stops_data[$i] = $stop;
   		 $i = $i+1;
  	} 
  	}
  	$i = 0;
  	foreach($routes as $route){
    		$routes_data[$i] = $route->route;
   		 $i = $i+1;
  	}
  }
?>
 <style>
#header {
    background-color:black;
    color:white;
    text-align:center;
    padding:5px;
    height:80px;
}
    
#nav {
    line-height:30px;
    background-color:#eeeeee;
    height:100%;
    width:150px;
    float:left;
    padding:5px;
}
#section {
    padding:10px;
    float:left;
    padding:10px;
}
#footer {
    background-color:black;
    color:white;
    clear:both;
    text-align:center;
    padding:5px;
}

p {
    text-transform: uppercase;
}

.margin-left{
    margin-left: 10px !important;
}
</style> 


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
 @include('header_js')
 	<title>Stops Info</title>
 <body>

<div id="header">

<nav class="navbar navbar-inverted navbar-static-top">
<a class="navbar-brand" rel="home" href="get_search" title="Bus Route Portal" style="float:left;">
        <img style="max-width:80px; margin-top: -20px; "
            alt = "Logo" src={{asset('img/Bus.png')}}>

</a>

 <p class="navbar-brand" >{{Session::get('city')}}, {{Session::get('trans')}}</p> 
 {{ Form::open(array('url'=>'get_search','method' => 'GET','class'=>'navbar-form navbar-left')) }}
	{{ Form::submit('Change Agency',['class' =>'btn btn-success btn-block btn-lg']) }}
{{ Form::close() }}

  <div  class="btn-group">

 {{ Form::open(array('url'=>'upload','method' => 'GET','class'=>'navbar-form navbar-right')) }}
		{{ Form::submit('Add Data',['class' =>'btn btn-success btn-block btn-lg ']) }}
{{ Form::close() }}


{{ Form::open(array('url'=>'main','method' => 'GET','class'=>'navbar-form navbar-right')) }}
		{{ Form::submit('Info',['class' =>'btn btn-success btn-block btn-lg ']) }}
{{ Form::close() }}


  </div>

<div class="dropdown" style ="float:right;margin-top:15px;margin-right:80px;"; >
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        <?php echo Session::get('user'); ?>  
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
    <li><a href="#">Action</a></li>
    <li><a href="change_pwd">Change Password</a></li>>
    <li role="separator" class="divider"></li>
    <li><a href="logout">Logout</a></li>
  </ul>
</div>

<a class="navbar-brand" rel="home" href="download_app" title="Download Android App" style="float:right;">
        <img style="max-width:120px; margin-top: -5px; "
            alt = "Logo" src={{asset('img/downloadAppAndroid.png')}}>

</a>

</nav>
</div>

<div class="btn-group" role="group" aria-label="..." style ="float:left;margin-top:15px;margin-left:15px;"; >
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
  

<div class="container-fluid" align="right">

{{ Form::open(array('url'=>'main','method' => 'POST','class'=>'navbar-form navbar-right')) }}

  <p style="font-size:24px;display: inline;margin-right:200px;"><nobr><?php if(is_array($data)){if(sizeof($data)==3){Session::put('route',$data[2]);echo 'Result for Route:  "'.$data[2].'"';}} ?></nobr></p>
  {{ Form::label('route', 'Route: ') }}
  <input type="text" id = "route" name="route" style="height:40px;width:400px;">
  {{ Form::submit('Go',['class' =>'btn btn-primary btn-md ']) }}
  

{{ Form::close() }}




</div>


<div id="nav" class="btn-group">
	<button id = "stops" type="button" class="btn btn-info btn-lg btn-block" >Stops</button><br><br>
  	<button id = "map" type="button" class="btn btn-info btn-lg btn-block" >Map</button><br><br>
  	{{ Form::open(array('url'=>'edit_this_route','method' => 'GET')) }}
		{{ Form::submit('Edit Route',['class' =>'btn btn-info btn-block btn-lg ']) }}
	{{ Form::close() }}
  	{{ Form::open(array('url'=>'download_route','method' => 'GET')) }}
		{{ Form::submit('Download',['class' =>'btn btn-info btn-block btn-lg ']) }}
	{{ Form::close() }}
</div>

<div class="container-fluid" align="left" style="width:999px; height:540px;">
  <div id="map-canvas" style="width: 100%; height: 100%;overflow: auto;"></div>
</div>

<script>

var route_flag =  <?php if($data[0]!='get'){if(isset($stops_data)){echo "true";}else{echo "false";}}else{echo "false";} ?>;
var points = <?php if($data[0]!='get'){if(isset($stops_data)){echo json_encode($stops_data);}else{echo "[]";}}else{echo "[]";}?>;  
var city = <?php echo json_encode($city); ?>;
var trans = <?php echo json_encode($trans); ?>;

if(points.length==0&&route_flag){
	document.getElementById("map-canvas").innerHTML = "<h1><i>No Such route Exists....</i></h1>";
}
else{
var disp = "";
for(var temp =0; temp<points.length;temp++){
 disp = disp+points[temp].stop_pos+"  "+points[temp].stop_name+"</br>";

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


