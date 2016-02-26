 <?php 
 $city = Session::get('city');
 $trans = Session::get('trans'); 
 $table = $city.'_'.$trans.'_route';

 $routes = DB::table($table)->select('route')->distinct()->get();
 $i = 0;
foreach($routes as $route){
    $routes_data[$i] = $route->route;
    $i = $i+1;
}
 ?>
 
 <style>
 .cell{
     -ms-word-break: break-all;
     word-break: break-all;
     word-break: break-word;

-webkit-hyphens: auto;
   -moz-hyphens: auto;
        hyphens: auto;
 max-width: 150px; 
 }

 </style>
@include('up_map')
<title>Routes</title>
<script>
  $(function() {
    var availableroute = <?php echo json_encode($routes_data)?>;
    $( "#route" ).autocomplete({
      source: availableroute
    });
  });
</script>
<div style="width: 100%;" >
<div id="nav" style = "height:100%;"class="btn-group"></div>
<h1><u> Select the route </u></h1>
<div id="section" > 
{{ Form::open(array('url'=>'main','method' => 'POST','class'=>'navbar-form navbar-left','style'=>'display:inline-block')) }}

  {{ Form::label('route', 'Route: ') }}
  <input type="text" id = "route" name="route" required style="height:40px;width:400px;display:inline-block;">

  <button type="submit" class="btn btn-primary btn-md " value="Submit">Go</button>
{{ Form::close() }}
<br><br><br><br><h1><u> The following routes are available </u></h1><br>
     <table id = "route" style = "font-size:24px;width:100%; " class="table table-bordered table-condensed f11"></table>
</div>
</div>
     <script>
   	var routes = <?php echo json_encode($routes_data)?>;
   	var newdiv ="<tr>"; 
   	for(var m in routes){
   		
   		if(m%7==0 && m!=0){
   			newdiv += '</tr><tr><td class="cell">{{ Form::open(array("url"=>"main","method" => "POST","class"=>"navbar-form navbar-left","style"=>"display:inline-block")) }}<label class="hidden" for="route">Route Number </label> <input type="submit" style = "background-color: Transparent;background-repeat:no-repeat;border: none; text-decoration:underline;text-transform: capitalize;width: 100px;word-break: break-word;" id = "route" name="route"value=\"' ;
   			newdiv += routes[m] + '\"/>{{ Form::close() }}</td>';
   		}
   		else{
   			newdiv += '<td class="cell">{{ Form::open(array("url"=>"main","method" => "POST","class"=>"navbar-form navbar-left","style"=>"display:inline-block")) }}<label class="hidden" for="route">Route Number </label> <input type="submit" style = "background-color: Transparent;background-repeat:no-repeat;border: none; text-decoration:underline;text-transform: capitalize;width: 100px;word-break: break-word;" id = "route" name="route"value=\"' ;  
   			newdiv += routes[m] + '\"/>{{ Form::close() }}</td>';
   		}
   	}

   	$('table#route').append(newdiv);
   	jQuery(function($) {
   	$('.expandable').bind('click', function () {
        $(this).children().toggle();
    });

});
   </script>
