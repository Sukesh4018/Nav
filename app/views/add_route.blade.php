<?php
set_time_limit(0);
?>
 @include('up')
    <title>Add Route</title>
<style>
p.uppercase {
    text-transform: uppercase;
}
</style>
<div id="nav" class="btn-group">

	<a href="upload" class="btn btn-info btn-lg btn-block" role="button"> GTFS Zip</a>
  	<a href="mupload" class="btn btn-info btn-lg btn-block" role="button">Edit Route</a>
  	<a href="add_route" class="btn btn-info btn-lg btn-block" role="button">Add Route</a>
  	<a href="add_agen" class="btn btn-info btn-lg btn-block" role="button">Add Agency</a>
  	<a href="upload_file" class="btn btn-info btn-lg btn-block" role="button">Upload File</a>
</div>
	
	
<p  style="font-size:32;margin-left: 50px !important;margin-top: 10px !important;"><b><i>Enter the Route Number you wish to Add</i></b></p>
	
    <div id="section">
    	<button id = "add" style="display: inline;float:right;" class="btn btn-info btn-md">Add Stop</button>
    	
    	{{ Form::open(array('url'=>'edit_done','method' => 'POST')) }}
  	{{ Form::label('route', 'Enter Route Number') }}
  	 
    	
  	<input type="text" id = "route" name="route" required style="height:40px;width:400px;">
  	<input type="hidden" id = "count" name="size" value="1">
  	<input type="hidden"  name="op" value="add">
  	<br><br>
  	<table id = "form" border  "1" style = "font-size:16px;">
  	 
  	
     <script>
	var count = 0;
	var first = 1;
	$('#add').on('click', function (e) {
	
		//var newdiv = document.createElement('div');
		//newdiv.innerHTML
		
		if(first==1){
			var init = "<tr><td> stop_pos</td><td>stop_name  </td><td> stop_lat  </td><td> stop_lon </td></tr> ";
			$('table#form').append(init);
			first++;
		}
		var newdiv="<tr><td><input type='text' name= stop_pos"+count+"></td><td>"+
		"<input type='text' name=stop_name"+count+"></td><td>"+
		"<input type='text' name=stop_lat"+count+"></td><td>"+
		"<input type='text' name=stop_lon"+count+"></td></tr>";
		$('table#form').append(newdiv);
		/*
		 var newdiv=  count+" "+"<input type='text' name= stop_pos"+count+">"+"&nbsp&nbsp"+
		"<input type='text' name=stop_name"+count+">"+"&nbsp&nbsp"+
		"<input type='text' name=stop_lat"+count+">"+"&nbsp&nbsp"+
		"<input type='text' name=stop_lon"+count+"><br>";
		
		var div = document.getElementById('form');
		div.innerHTML = div.innerHTML + newdiv;
		//document.getElementById("form").appendChild(newdiv);
		*/		
		count++;
		document.getElementById('count').value = count; 
		
	});
	</script>
     </table>
 
     <br><br>
      {{ Form::submit('Done',['class' =>'btn btn-primary btn-lg btn-block']) }}
      {{ Form::close() }}
     
     </div>
     
