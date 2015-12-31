 @include('up')
    <title>Edit Route</title>
<style>
p.uppercase {
    text-transform: uppercase;
}
</style>

<div id="nav" class="btn-group">

	<button id = "stops" type="button" class="btn btn-info btn-lg btn-block" >Stops</button><br><br>
  	<button id = "map" type="button" class="btn btn-info btn-lg btn-block" >Map</button><br><br>
  	{{ Form::open(array('url'=>'edit_this_route','method' => 'GET')) }}
		{{ Form::submit('Edit Route',['class' =>'btn btn-info btn-block btn-lg ']) }}
	{{ Form::close() }}
  	{{ Form::open(array('url'=>'download_route','method' => 'GET')) }}
		{{ Form::submit('Download',['class' =>'btn btn-info btn-block btn-lg ']) }}
	{{ Form::close() }}

</div>
<p  style="font-size:32;margin-left: 50px !important;margin-top: 10px !important;"><b><i>Edit Select Route</i></b></p>
  <div id="section" style = "margin-left: 50px !important;margin-top: 10px !important;">
  <p style="font-size:24px;display: inline;margin-right:200px;"><nobr><?php $route = Session::get('route');if($route!=""){echo 'Edit Route:  "'.$route.'"';} ?></nobr></p>
    
<?php
	set_time_limit(0);
	$i = 0;
	if(isset($datam)){
	$data = $datam[0];
	$route = $datam[1];
	echo  '<button id = "add" style="display: inline;float:right;" class="btn btn-info btn-md">Add Stop</button>';	
	echo Form::open(array('url'=>'edit_done','method' => 'POST')) ;
	echo '<br><table id = "form" border="1" style="width:100%; font-size:18px; "><tr><td>&nbspPosition&nbsp&nbsp</td><td>&nbspName &nbsp&nbsp  </td><td>&nbspLatitude  &nbsp&nbsp </td><td>&nbspLongitude</td></tr>';
	
	echo Form::hidden('route', $route) ;
	echo Form::hidden('proc', "info") ;
	echo Form::hidden('op', 'edit') ;
	
	foreach($data as $stop){	
		echo 
		'<tr><td>'. Form::text('stop_pos'.$i,$stop->stop_pos).
		'</td><td>'.Form::text('stop_name'.$i,$stop->stop_name).
		'</td><td>'.Form::text('stop_lat'.$i,$stop->stop_lat).
		'</td><td>'.Form::text('stop_lon'.$i,$stop->stop_lon).
		'</td></tr>';	
		$i = $i+1;
  	}
  	echo '<input type="hidden" id = "count" name="size" value="'.$i.'">';
  	echo '</table></br>';
  	echo Form::submit('Save',['class' =>'btn btn-primary btn-md']) ;
    	echo Form::close();
    	
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
		
		 var newdiv="<tr><td><input type='text' name= stop_pos"+count+"></td><td>"+
		"<input type='text' name=stop_name"+count+"></td><td>"+
		"<input type='text' name=stop_lat"+count+"></td><td>"+
		"<input type='text' name=stop_lon"+count+"></td></tr>";
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
  </div>

