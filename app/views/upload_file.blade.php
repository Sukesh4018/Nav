<?php
set_time_limit(0);
?>
 @include('up')
    <title>Upload file</title>
<div id="nav" class="btn-group">

	<a href="upload" class="btn btn-info btn-lg btn-block" role="button"> GTFS Zip</a>
  	<a href="mupload" class="btn btn-info btn-lg btn-block" role="button">Edit Route</a>
  	<a href="add_route" class="btn btn-info btn-lg btn-block" role="button">Add Route</a>
  	<a href="add_agen" class="btn btn-info btn-lg btn-block" role="button">Add Agency</a>
  	<a href="upload_file" class="btn btn-info btn-lg btn-block" role="button">Upload File</a>
</div>
    <div id="section">
    <h1><b><i>Upload file in the format specified below</i></b></h1></br>
    <p  style="font-size:18;margin-left: 5px !important;margin-top: 1px !important;">1. The file should be in .csv format i.e. Comma Seperated Values.<br> 2. Format: Route_no,stop_name,stop_lat,stop_lon <br> 3. The next input starts in newline. </p></br>
    {{ Form::open(array('url'=>'upload_file', 'files'=>true, 'enctype' => 'multipart/form-data', 'method' => 'POST')) }}
    	{{ Form::label('Name of the City') }}
    	{{ Form::text('city','',array('required' => 'required')) }}</br></br>
    	{{ Form::label('Name of the Transport Agency') }}
    	{{ Form::text('trans_agen','',array('required' => 'required')) }}</br></br>
    	{{ Form::label('Choose a .csv file to upload:') }}</br></br>
 	{{ Form::file('zip_file',['class' =>'btn btn-success']) }}</br></br>
 	{{ Form::submit('Upload',['class' =>'btn btn-primary']) }}
    {{ Form::close() }}
     </div>
     @include('down')
   
