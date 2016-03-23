 <?php
 	$cities_data = DB::table('cities')->select('name')->distinct()->orderBy('name', 'asc')->get();
 	$i = 0;
	foreach($cities_data as $city){
    		$city_data[$i] = $city->name;
   		 $i = $i+1;
  	}
  	
  	$messg = "";
	if(Session::has('success')){
		$messg = Session::get('success');
	}
 ?>
 <style>
 	 #city td:hover{color:#1E90FF;}
 </style>
 
 @include('up')
<title>Search</title>
<script>
  $(function() {
    var availableroute = <?php echo json_encode($city_data)?>;
    //alert(availableroute );
    $( "#search" ).autocomplete({
      source: availableroute
    });
  });
</script>
<div style="width: 100%;" >
    {{ Form::open(array('url'=>'list_trans','method' => 'POST')) }}
	<input type="hidden" name="search" value= "city" /> 
	<input type="hidden" name="source" value= <?php echo $source; 
	if($source == 'selection'){Session::put('trans',"");Session::put('city',"");}
	else{Session::put('editTrans',"");Session::put('editCity',"");}?> /> 
    <div id="section" style="margin-left:40px;" > 	
    <h1><u> Select the city </u></h1>
        <label class="hidden" for="search">Search City</label>
    	</br><input type="text" id = "search"  name="input_string" style="height:9%;width:60%;font-size:18pt;padding:10px;display: inline-block;margin-top:-3px;">
	
 	{{ Form::submit('Done',['class' =>'btn btn-primary btn-lg']) }}
    {{ Form::close() }}
     <br><br><h1><u> The following cities are available </u></h1><br>
     <table id = "city" style = "font-size:24px;width:100%;"></table>
   </div>       
  </div> 
  
     <script>
   	var cities = <?php echo json_encode($city_data)?>;
   	var newdiv ="<tr>"; 
   	for(var m in cities){
   		
   		if(m%4==0 && m!=0){
   			newdiv += '</tr><tr><td>{{ Form::open(array("url"=>"list_trans","method" => "POST")) }}<input type="hidden" name="search" value= "city" /> <input type="hidden" name="source" value="<?php echo $source;?>" /><input type="submit" style = "background-color: Transparent;background-repeat:no-repeat;border: none; text-decoration:underline;text-transform: capitalize;"name="input_string" value=\"' ;
   			newdiv += cities[m] + '\"/>{{ Form::close() }}</td>';
   		}
   		else{
   			newdiv += '<td>{{ Form::open(array("url"=>"list_trans","method" => "POST")) }}<input type="hidden" name="search" value= "city" /> <input type="hidden" name="source" value="<?php echo $source;?>" /><input type="submit" style = "background-color: Transparent;background-repeat:no-repeat;border: none; text-decoration:underline;text-transform: capitalize;"name="input_string" value=\"' ;  
   			newdiv += cities[m] + '\"/>{{ Form::close() }}</td>';
   		}
   	}

   	$('table#city').append(newdiv);
   </script>
   
   	<script>
	var msg =  <?php echo json_encode($messg); ?>;
	if(msg!=""){
   		 $(window).load(function(){
        		alert(msg);
    		});
    	}
	</script>
    

