 @include('up')
    <title>Search</title>
    <div id="nav" class="btn-group"></div>
    {{ Form::open(array('url'=>'header','method' => 'POST')) }}
	<input type="hidden" name="search" value= "city" /> 
	<input type="hidden" name="source" value= <?php echo $source; 
	if($source == 'selection'){Session::put('trans',"");Session::put('city',"");}
	else{Session::put('editTrans',"");Session::put('editCity',"");}?> /> 
    <div id="section"> 	
    <h1> Select the city </h1>
    	</br><input type="text" name="input_string" style="height:60px;width:500px;font-size:18pt;padding:10px;"><br>
	
 	</br></br>{{ Form::submit('Search',['class' =>'btn btn-primary btn-block']) }}
    {{ Form::close() }}
   </div>

