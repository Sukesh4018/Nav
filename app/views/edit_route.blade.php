<?php
$route = $data[0];
$stop_name = $data[1];
$stop_lon = $data[2];
$stop_lat = $data[3];
$arr_time = $data[4];
$dep_time = $data[5];
?>
 @include('up')
    <title>Manual Upload</title>
    <style>
p.uppercase {
    text-transform: uppercase;
}
</style>
<div id="nav" class="btn-group">

	<a href="upload" class="btn btn-info btn-lg btn-block" role="button"> GTFS Zip</a>
  	<a href="mupload" class="btn btn-info btn-lg btn-block" role="button">Add Stop</a>
  	<a href="edit_stop" class="btn btn-info btn-lg btn-block" role="button">Edit Stop</a>

</div>

  <div id="section">
	<p class="uppercase" style="font-size:24;">{{Session::get('editCity')}}</p>
	<?php
	 echo Form::open(array('url'=>'edit_help','method' => 'POST')) ;
	 echo Form::hidden('handle', 'replace');
	 echo Form::hidden('op', 'edit');
	 echo Form::label('routeNo', 'Route Number: ');
  	 echo Form::text('route',$route).'<br><br><br>';
  	 echo Form::label('Start Position Name', 'Start Stop Name ') ;
  	 echo Form::text('start_name',$stop_name);
  	 echo Form::label('Arrival Time', 'Arrival Time ');
  	 echo Form::text('start_time',$arr_time);
  	 echo Form::label('Departure Time', 'Departure Time ');
  	 echo Form::text('end_time',$dep_time).'<br><br><br>';
  	 

  	 echo Form::label('Start Position GPS Coordinates', 'Start Position GPS Coordinates ').'<br>';
  	 echo Form::label('gpsx11', 'X:');
  	 echo Form::text('gpsx1',$stop_lon);
  	 echo Form::label('gpsy11', 'Y:');
  	 echo Form::text('gpsy1',$stop_lat).'<br><br><br>';

  	 
	 echo Form::submit('Done',['class' =>'btn btn-primary btn-lg btn-block']) ;
    	 echo Form::close() ;
    	 ?>
	 
  </div>
  
     @include('down')
