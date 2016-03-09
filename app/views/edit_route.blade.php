 
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

p.uppercase {
    text-transform: uppercase;
    color: white;
}
</style> 


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <html lang="en">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Edit Route</title>
 @include('header_js')
 
 <body>


<nav class="navbar navbar-inverse navbar-static-top">
<a class="navbar-brand" rel="home" href="get_search" title="Bus Route Portal" style="float:left;">
        <img style="max-width:80px; margin-top: -25px; "
          alt = "Bus Route Portal Logo"    src="http://localhost/Nav/public/img/Bus.png">
</a>

<p class="uppercase" style="font-size:24; float:left;margin-top: 10px !important;">{{Session::get('city')}}, {{Session::get('trans')}}</p>

<div  class="btn-group">
 {{ Form::open(array('url'=>'get_search','method' => 'GET','class'=>'navbar-form navbar-left')) }}
	{{ Form::submit('Change Agency',['class' =>'btn btn-success btn-block btn-lg']) }}
{{ Form::close() }}
 
 {{ Form::open(array('url'=>'main','method' => 'GET','class'=>'navbar-form navbar-left')) }}
		{{ Form::submit('Info',['class' =>'btn btn-success btn-block btn-lg']) }}
{{ Form::close() }}

 {{ Form::open(array('url'=>'upload','method' => 'GET','class'=>'navbar-form navbar-left')) }}
		{{ Form::submit('Add Data',['class' =>'btn btn-success btn-block btn-lg ']) }}
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
            alt = "Download Android App Logo" src={{asset('img/downloadAppAndroid.png')}}>          
</a>
</nav>



<div id="nav" class="btn-group">

	
  	{{ Form::open(array('url'=>'download_route','method' => 'GET')) }}
		{{ Form::submit('Download',['class' =>'btn btn-primary btn-block btn-lg ']) }}
	{{ Form::close() }}

</div>

<p  style="font-size:32;margin-left: 50px !important;margin-top: 10px !important;"><b><i>Edit Select Route</i></b></p>
 
  <div id="section" style = "margin-left: 50px !important;margin-top: 10px !important;">
  <p style="font-size:24px;display: inline;margin-right:200px;"><nobr><?php $route = Session::get('route');if($route!=""){echo 'Edit Route:  "'.$route.'"';} ?></nobr></p>
    
<?php
	$i = 0;
	if(isset($datam)){
	$data = $datam[0];
	$route = $datam[1];
	echo  '<button id = "add" style="display: inline;float:right;" class="btn btn-primary btn-md">Add Stop</button>';	
	echo Form::open(array('url'=>'edit_done','method' => 'POST')) ;
	echo '<br><table id = "form" border="1" style="width:100%; font-size:18px; "><tr><td>&nbspPosition&nbsp&nbsp</td><td>&nbspName &nbsp&nbsp  </td><td>&nbspLatitude  &nbsp&nbsp </td><td>&nbspLongitude</td></tr>';
	
	echo Form::hidden('route', $route) ;
	echo Form::hidden('proc', "info") ;
	echo Form::hidden('op', 'edit') ;
	
	foreach($data as $stop){	
		echo 
		'<tr><td><label for= "stop_pos'.$i.'" class="hidden">'.$stop->stop_pos.'</label>'. Form::text('stop_pos'.$i,$stop->stop_pos,['id'=>'stop_pos'.$i]).
		'</td><td><label for= "stop_name'.$i.'" class="hidden">'.$stop->stop_name.'</label>'.Form::text('stop_name'.$i,$stop->stop_name,['id'=>'stop_name'.$i]).
		'</td><td><label for= "stop_lat'.$i.'" class="hidden">'.$stop->stop_lat.'</label>'.Form::text('stop_lat'.$i,$stop->stop_lat,['id'=>'stop_lat'.$i]).
		'</td><td><label for= "stop_lon'.$i.'" class="hidden">'.$stop->stop_lon.'</label>'.Form::text('stop_lon'.$i,$stop->stop_lon,['id'=>'stop_lon'.$i]).
		'</td></tr>';	
		$i = $i+1;
  	}
  	echo '<input type="hidden" id = "count" name="size" value="'.$i.'">';
  	echo '</table></br>';
  	echo Form::submit('Save',['class' =>'btn btn-primary btn-md']) ;
    	echo Form::close();
    	
    	echo Form::open(array('url'=>'main','method' => 'GET')) ;
		echo Form::submit('Cancel',['class' =>'btn btn-primary btn-md ', 'style'=>'float:right']) ;
	echo Form::close() ;
  	}
  	else{
  		if(isset($no_data)){
  			echo $no_data;
  		}
  	}
?>	

	<script>
	var count =  <?php echo json_encode($i); ?>;
	
	$('#add').on('click', function (e) {
	
		//var newdiv = document.createElement('div');
		//newdiv.innerHTML
		
		 var newdiv="<tr><td><label for='stop_pos"+count+"' class = 'hidden'>stop position</label><input type='text' id=stop_pos"+count+" name= stop_pos"+count+"></td><td>"+
		"<label for='stop_name"+count+"' class = 'hidden'>stop name</label><input type='text'id=stop_name"+count+" name=stop_name"+count+"></td><td>"+
		"<label for='stop_lat"+count+"' class = 'hidden'>stop latitude</label><input type='text' id=stop_lat"+count+" name=stop_lat"+count+"></td><td>"+
		"<label for='stop_lon"+count+"' class = 'hidden'>stop longitude</label><input type='text' id=stop_lon"+count+" name=stop_lon"+count+"></td></tr>";
		$('table#form').append(newdiv);
		/*
		var div = document.getElementById('form');
		window.alert(div.innerHTML);
		div.innerHTML = div.innerHTML + newdiv;
		window.alert(div.innerHTML);
		//document.getElementById("form").appendChild(newdiv);
		*/
		//window.alert(newdiv);		
		count++;
		
		document.getElementById('count').value = count; 
		//window.alert(document.getElementById('count').value);
		
		
	});
	</script> 
	
	<script>
function maximizeText() {
  document.body.style.fontSize = parseFloat(document.body.style.fontSize) + (2 * 0.2) + "em";
} 
function minimizeText() {
  document.body.style.fontSize = parseFloat(document.body.style.fontSize) - (2 * 0.2) + "em";
} 
</script>
  </div>
  
  <table>

