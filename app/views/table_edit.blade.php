 <?php
 	$city = Session::get("editCity");
 	$trans = Session::get("editTrans");
 	$cities_data = DB::table($city.'_'.$trans.'_route')->select('route')->distinct()->orderBy('route', 'asc')->get();
 	$i = 0;
	foreach($cities_data as $city){
    		$city_data[$i] = $city->route;
   		 $i = $i+1;
  	}
  	
  	$messg = "";
	if(Session::has('success')){
		$messg = Session::get('success');
	}
 ?>

    <title>Edit Route</title>
<style>
p.uppercase {
    text-transform: uppercase;
}
</style>
 @include('up')

<script>
  $(function() {
    var availableroute = <?php echo json_encode($city_data)?>;
    $( "#search" ).autocomplete({
      source: availableroute
    });
  });
</script>

<div style="width: 100%; " >

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

  <div id="section" style = "margin-left: 50px !important;margin-top: 10px !important;">
  
 	<h1>Enter the Route Number you wish to Modify</h1></br>

	 {{ Form::open(array('url'=>'edit_help','method' => 'POST')) }}
	 {{ Form::hidden('handle', 'insert') }}
  	  <label>Route Number:  <input type="text" id="search" name="route" required style="height:40px;width:400px;"></label>
  	 
	 {{ Form::submit('Get',['class' =>'btn btn-primary btn-md']) }}
    	 {{ Form::close() }}
    
<?php
	set_time_limit(0);
	$i = 0;
	if(isset($datam)){
	$data = $datam[0];
	$route = $datam[1];
	echo '<h2><nobr><u>Route : '.$route.'</u></h2></nobr>';
	echo  '<button id = "add" style="display: inline;float:right;" class="btn btn-primary btn-md">Add Stop</button>';
	echo  '<button id = "del" style="display: inline;float:right;" class="btn btn-primary btn-md">Delete Stop</button>';	
	echo Form::open(array('url'=>'edit_done','method' => 'POST')) ;
	echo '<br><table id = "form"   border="1" style="width:100%; font-size:18px; "><thead><tr><td>&nbspPosition&nbsp&nbsp</td><td>&nbspName &nbsp&nbsp  </td><td>&nbspLatitude  &nbsp&nbsp </td><td>&nbspLongitude</td></td></tr><thead>';
	
	echo Form::hidden('route', $route) ;
	echo Form::hidden('op', 'edit') ;
	echo Form::hidden('proc', "add") ;
	echo '<tbody>';
	foreach($data as $stop){	
		echo 
		'<tr><td><label for= "stop_pos'.$i.'" class="hidden">'.$stop->stop_pos.'</label>'. '<input type="hidden" id=stop_pos'.$i.' name= stop_pos'.$i.' value='.($i+1).'>'.($i+1).
		'</td><td><label for= "stop_name'.$i.'" class="hidden">'.$stop->stop_name.'</label>'.Form::text('stop_name'.$i,$stop->stop_name,['id'=>'stop_name'.$i]).
		'</td><td><label for= "stop_lat'.$i.'" class="hidden">'.$stop->stop_lat.'</label>'.Form::text('stop_lat'.$i,$stop->stop_lat,['id'=>'stop_lat'.$i]).
		'</td><td><label for= "stop_lon'.$i.'" class="hidden">'.$stop->stop_lon.'</label>'.Form::text('stop_lon'.$i,$stop->stop_lon,['id'=>'stop_lon'.$i]).
		'</td></tr>';	
		$i = $i+1;
  	}
  	echo '</tbody>';
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
    	<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js" type="text/javascript"></script>
	<script>
	var count =  <?php echo json_encode($i); ?>;
	
	$('#adds').on('click', function (e) {
	
		//var newdiv = document.createElement('div');
		//newdiv.innerHTML
		
		 var newdiv="<tr><td><label for='stop_pos"+count+"' class = 'hidden'>stop position</label><input type='text' id=stop_pos"+count+" name= stop_pos"+count+"></td><td>"+
		"<label for='stop_name"+count+"' class = 'hidden'>stop name</label><input type='text' id=stop_name"+count+" name=stop_name"+count+"></td><td>"+
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
	var msg =  <?php echo json_encode($messg); ?>;
	if(msg!=""){
   		 $(window).load(function(){
        		alert(msg);
    		});
    	}
    	
/*
$(document).ready(function() {
    $('#form').DataTable( {
        columnDefs: [ {
            orderable: true,
            className: 'select-checkbox',
            targets:   0
        } ],
        select: {
            style:    'os',
            selector: 'td:first-child'
        },
        order: [[ 1, 'asc' ]]
    } );
} );
*/
var count =  <?php echo json_encode($i); ?>;
$(document).ready(function() {
    var t = $('#form').DataTable( {
    paging: false,
        columnDefs: [ {
            orderable: true,
            className: 'select-checkbox',
            targets:   0
        } ],
        select: {
            style:    'os',
            selector: 'td:first-child'
        },
        order: [[ 0, 'asc' ]]
    } );
   
 
    $('#add').on( 'click', function () {
        t.row.add( [
            '<label for=stop_pos'+count+' class = "hidden">stop position</label><input type="hidden" id=stop_pos'+count+' name= stop_pos'+count+' value='+(count)+'>'+(count+1),
            '<label for= stop_name'+count+' class="hidden">stop name</label>'+"<input type='text' id=stop_name"+count+" name=stop_name"+count+">",
            '<label for= stop_lat'+count+' class="hidden">stop latitude</label>'+"<input type='text' id=stop_lat"+count+" name=stop_lat"+count+">",
            '<label for= stop_lon'+count+' class="hidden">stop longitude</label>'+"<input type='text' id=stop_lon"+count+" name=stop_lon"+count+">"
        ] ).draw( false );
        count++;
        document.getElementById('count').value = count; 
        alert(count);
    } );
 
    // Automatically add a first row of data
    $('#addRow').click();
    
   $('#form tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            t.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );
 
    $('#del').click( function () {
        t.row('.selected').remove().draw( false );
        alert(t.row('.selected').data());
        count--;
        document.getElementById('count').value = count; 
        alert(count);
    } );

    
} );


	</script>
  </div>
  </div>

