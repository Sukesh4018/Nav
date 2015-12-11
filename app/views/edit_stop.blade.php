<?php
set_time_limit(0);
?>
 @include('up')
    <title>Edit Stop</title>
<style>
p.uppercase {
    text-transform: uppercase;
}
</style>
<div id="nav" class="btn-group">

	<a href="upload" class="btn btn-info btn-lg btn-block" role="button"> GTFS Zip</a>
  	<a href="mupload" class="btn btn-info btn-lg btn-block" role="button">Edit Route</a>
  	<a href="add_route" class="btn btn-info btn-lg btn-block" role="button">Add Route</a>
  	<a href="edit_stop" class="btn btn-info btn-lg btn-block" role="button">Edit Stop</a>

</div>
    <div id="section">
    	{{ Form::open(array('url'=>'edit_done','method' => 'POST')) }}
    	 
  	 {{ Form::label('Start Position Name', 'Stop Name ') }}
  	 {{ Form::text('name')}}
 
  	 {{ Form::label('Start Position GPS Coordinates', 'Start Position GPS Coordinates ') }}<br>
  	 {{ Form::label('gpsx11', 'Longitude:') }}
  	 {{ Form::text('gpsx1')}}
  	 {{ Form::label('gpsy11', 'Latitude:') }}
  	 {{ Form::text('gpsy1')}}<br><br><br>
  	 
	 {{ Form::submit('Done',['class' =>'btn btn-primary btn-lg btn-block']) }}
    	 {{ Form::close() }}
     </div>
     @include('down')
