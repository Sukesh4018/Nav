<?php
set_time_limit(0);
?>
 @include('up')
    <title>Upload Zip</title>
<div id="nav" class="btn-group">

	<a href="upload" class="btn btn-info btn-lg btn-block" role="button"> GTFS Zip</a>
  	<a href="mupload" class="btn btn-info btn-lg btn-block" role="button">Edit Route</a>
  	<a href="add_route" class="btn btn-info btn-lg btn-block" role="button">Add Route</a>
  	<a href="edit_stop" class="btn btn-info btn-lg btn-block" role="button">Edit Stop</a>
</div>
    <div id="section">
    <h1>Upload Google Transit Feed Specification Format(GTFS) Zip</h1></br></br>
    {{ Form::open(array('url'=>'upload_zip', 'files'=>true, 'enctype' => 'multipart/form-data', 'method' => 'POST')) }}
    	{{ Form::label('Name of the City') }}
    	{{ Form::text('city') }}</br></br>
    	{{ Form::label('Name of the Transport Agency') }}
    	{{ Form::text('trans_agen') }}</br></br>
    	{{ Form::label('Choose a zip file to upload:') }}</br></br>
 	{{ Form::file('zip_file',['class' =>'btn btn-success']) }}</br></br>
 	{{ Form::submit('Upload',['class' =>'btn btn-primary']) }}
    {{ Form::close() }}
     </div>
     @include('down')
   
