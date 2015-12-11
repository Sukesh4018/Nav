<?php

echo '<form method="post" action="main.php">
<p>Search for the name of the transport : <input type="text" name="name" /></p></form>';

$my_data = $_POST['name'];
if($my_data!=""){
$sql = "SELECT name FROM agencies WHERE name LIKE '%$my_data%' ORDER BY name";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
 
    while($row = mysqli_fetch_assoc($result)) {
        echo '<form method="post" action="name.php"><input type="submit" name="name" value ="'.$row["name"]. '"/></form>';
    }
} else {
    echo "NO Matches were found";
}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Upload</title>
    </head>
     
    <body>
