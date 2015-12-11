<?php  
if(is_array($data)){
	$stops = $data[0];
	$routes = $data[1];
	
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
 <body>

<div id="header">

<nav class="navbar navbar-inverted navbar-static-top">
<a class="navbar-brand" rel="home" href="get_search" title="Bus Route Portal" style="float:left;">
        <img style="max-width:80px; margin-top: -20px; "
            alt = "Logo" src="http://localhost/Nav/public/img/Bus.png">
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
   <div class="btn-group" role="group" aria-label="..." style ="float:right;"; >
  <button id = "font" onclick="maximizeText()" type="button" class="btn btn-default  btn-group-lg">A+</button>
  <button id = "font" onclick="minimizeText()" type="button" class="btn btn-default  btn-group-lg">A-</button>
</div>
</nav>
</div>

<title>Stops Info</title>



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

  <p style="font-size:24px;display: inline;margin-right:200px;"><nobr><?php if(is_array($data)){if(sizeof($data)==3){echo 'Result for "'.$data[2].'"';}} ?></nobr></p>
  {{ Form::label('route', 'Route: ') }}
  <input type="text" id = "route" name="route" style="height:40px;width:400px;">
  {{ Form::submit('Go',['class' =>'btn btn-primary btn-md ']) }}
  

{{ Form::close() }}




</div>


<div id="nav" class="btn-group">
	<button id = "stops" type="button" class="btn btn-info btn-lg btn-block" >Stops</button><br><br>
  	<button id = "map" type="button" class="btn btn-info btn-lg btn-block" >Map</button><br>
</div>

<div class="container-fluid" align="left" style="width:999px; height:540px;">
  <div id="map-canvas" style="width: 100%; height: 100%;overflow: auto;"></div>
</div>

<script>
var points = <?php if($data[0]!='get'){echo json_encode($stops_data);}?>;  

$('#stops').on('click', function (e) {
var disp = "";
for(var temp =0; temp<points.length;temp++){
 disp = disp+points[temp].stop_pos+"  "+points[temp].stop_name+"</br>";

}
document.getElementById("map-canvas").style.overflow = "auto";
document.getElementById("map-canvas").innerHTML = disp;
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

function initialize() {
var mapOptions = {
    backgroundColor : "FFFFFF",
    zoom: 11,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    center: new google.maps.LatLng(points[0].stop_lat, points[0].stop_lon)
  };
var map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions); 
var routePoints = new Array();

var marker = new Array();
for(var m in points) {
  var myLatlng = new google.maps.LatLng(points[m].stop_lat,points[m].stop_lon);
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


