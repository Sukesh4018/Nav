 @include('up_map')
 <title>Request Volunteer</title>
 <div id="nav" class="btn-group"></div>
 <div id="section">
 <h1>Request For Volunteer</h1></br></br>
 <p style = "font-size: 24px;"> You will be editing the data presented on the portal.<br>
 You will be reviewing the routes updated/edited/created.<br>
 Do you wish be a volunteer and help ?
 
{{ Form::open(array('url'=>'request_volunteer','method' => 'POST','class'=>'navbar-form navbar-left')) }}
		{{ Form::submit('Yes',['class' =>'btn btn-success btn-block btn-lg ']) }}
{{ Form::close() }}
{{ Form::open(array('url'=>'get_search','method' => 'GET','class'=>'navbar-form navbar-left')) }}
		{{ Form::submit('No',['class' =>'btn btn-danger btn-block btn-lg ']) }}
{{ Form::close() }}
 </div>
