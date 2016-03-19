 @include('up_map')
 <title>Admin </title>
 
 <div id="nav" style = "width:250px;" class="btn-group">
	
  	{{ Form::open(array('url'=>'volunteer_requests','method' => 'GET')) }}
		{{ Form::submit('Volunteer Requests',['class' =>'btn btn-primary btn-block btn-lg ']) }}
	{{ Form::close() }}
  	{{ Form::open(array('url'=>'accept_edits','method' => 'GET')) }}
		{{ Form::submit('Verification',['class' =>'btn btn-primary btn-block btn-lg ']) }}
	{{ Form::close() }}
</div>

<div id="section">
	<h1><u>The following requests are pending</u> </h1>
	

</div>
