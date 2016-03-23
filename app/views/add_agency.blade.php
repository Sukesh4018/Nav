<?php
$data = DB::table('cities')->get();
$i = 0;
$cities_data = [];
foreach($data as $city){
    	$cities_data[$i] = $city;
   	$i = $i+1;
}
?>
 @include('up')
    <title>Add Agency</title>
    
 <div style="width: 100%; overflow: hidden;">
<div id="nav" class="btn-group">

	<a href="mupload" class="btn btn-primary btn-lg btn-block" role="button">Edit Route</a>
  	<a href="add_route" class="btn btn-primary btn-lg btn-block" role="button">Add Route</a>
  	<?php 
  	if(Auth::user()->role!="2"){
  	echo '
  	<a href="upload" class="btn btn-primary btn-lg btn-block" role="button"> GTFS Zip</a>
  	<a href="add_agen" class="btn btn-primary btn-lg btn-block" role="button">Add Agency</a>
  	<a href="upload_file" class="btn btn-primary btn-lg btn-block" role="button">Upload File</a>
  	<a href="delete_route" class="btn btn-primary btn-lg btn-block" role="button">Delete Route</a>
  	';
  	}
  	?>
</div>
    <div id="section">
    <h1>Enter the name of the City and Transport Agency</h1></br></br>
    {{ Form::open(array('url'=>'add_agen', 'method' => 'POST')) }}
    	{{ Form::label('city','Name of the City') }}
    	{{ Form::text('city','',array('required' => 'required')) }}</br></br>
    	{{ Form::label('trans_agen','Name of the Transport Agency') }}
    	{{ Form::text('trans_agen','',array('required' => 'required')) }}</br></br>  	
 	<button id = "save"  class="btn btn-primary" onClick="return checkform();">Save</button>
    {{ Form::close() }}
     </div>
     </div>
     <script>
     var data = <?php echo json_encode($cities_data)?>;
     function checkform(){
     	var city = document.getElementById('city').value;
     	var trans = document.getElementById('trans_agen').value;
	for(var m in data) {
		if((((data[m].name).toUpperCase()).trim() === city.toUpperCase())&&(((data[m].transport_corp).toUpperCase()).trim() === trans.toUpperCase())){
			alert("The City and Transport agency are already present");
			return false;
		}
	}
     	return true;
     }
     </script>
     @include('down')
