 @include('up')
    <title>Results</title>
      
   <div id="nav" class="btn-group"></div>
  
<?php

$type = $data[0];
$result = $data[1];
$source = $data[2];


   //Search
echo Form::open(array('url'=>'header','method' => 'POST')) ;
echo Form::hidden('source', $source);

    echo '<div id="section">'; 	
	echo '<h1> Select the city </h1>';
	echo '<input type="hidden" name="search" value= "city" /> ';
	echo '<input type="hidden" name="source" value= '.$source.'/> ';
        echo '</br><input type="text" name="input_string" style="height:60px;width:500px;font-size:18pt;padding:10px;"><br><br><br>';
 	echo Form::submit('Search',['class' =>'btn btn-primary btn-lg btn-block']).'<br><br><br>';
   echo Form::close() ;
   
   
   //Results
    echo '<h1> Results </h1>';
	if(sizeof($result)==0){
		echo "No Results Found.\n";
	}
	else{
		echo Form::open(array('url'=>'list_trans','method' => 'POST')) ;
		echo Form::hidden('source', $source);
		foreach($result as $row){
			if($type =='city'){
				echo Form::hidden('type', 'name');
				echo Form::radio('selec', $row->name);
				echo Form::label($row->name). '</br>';
			}
	/*
	else {
		echo Form::hidden('type', 'trans');
		echo Form::radio('selec', $row->transport_corp) ;
		echo Form::label($row->transport_corp). '</br>';
	}
	*/
	
			}
echo  '</br>'.'</br>' ;
echo Form::submit('select',['class' =>'btn btn-primary btn-lg']);
echo Form::close();
}
?>
 </div>

