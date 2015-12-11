<?php

$username = "root";
$password = "ashoka";
$host = "localhost";
$dbName = "Navi_Misc";
$name = $_POST['name'];
$conn = mysqli_connect($host, $username, $password, $dbName) or die("Connection failed: " . mysqli_connect_error());

$sql = "SELECT * FROM agencies WHERE name  = '$name'";
$result = mysqli_query($conn, $sql);
$result = mysqli_fetch_assoc($result);
//print_r($result);
mysqli_close($conn);
$conn = mysqli_connect($servername, $username, $password, "detroit")or die("Connection failed: " . mysqli_connect_error());
$sql = "SELECT route_long_name,route_id FROM routes";
$result = mysqli_query($conn, $sql) or die("Query Failed". mysqli_connect_error());
while($routes = mysqli_fetch_assoc($result)){
	//echo $routes['route_id'] ;
}

echo '<form method="post" action="name.php">
<p>Enter the route number : <input type="text" name="route" /></p></form>';

$route = $_POST['route'];

if($route!=""){
	$query = "SELECT DISTINCT stops.stop_id, stops.stop_name
  FROM trips
  INNER JOIN stop_times ON stop_times.trip_id = trips.trip_id
  INNER JOIN stops ON stops.stop_id = stop_times.stop_id
  WHERE route_id =". $route;
  echo $query;
  $result = mysqli_query($conn, $query) or die("Query Failed". mysqli_error($conn));
  $stops = mysqli_fetch_assoc($result);
  print_r($stops);
}




?>

