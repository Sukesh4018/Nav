 <style>
#header {
    background-color:black;
    color:white;
    text-align:center;
    padding:5px;
    height:100%;
}
#nav {
    line-height:30px;
    background-color:#eeeeee;
    height:100%;
    width:200px;
    float:left;
    padding:5px;
    display: inline-block;
}
#section {
    padding:10px;
    float:left;
    padding:10px;
    display: inline-block;
    width:140vh;


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

.hidden {
position:absolute;
left:-10000px;
top:auto;
width:1px;
height:1px;
overflow:hidden;
}
</style> 


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <html lang="en">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
     @include('header_js')
 <body style = "height: 100%;">


<nav class="navbar navbar-inverse navbar-static-top">



<div  class="btn-group">
<?php
if(Session::get('editTrans')!=''){
echo Form::open(array('url'=>'get_change','method' => 'GET','class'=>'navbar-form navbar-left')) ;
	echo Form::submit('Change City',['class' =>'btn btn-default btn-block btn-lg']) ;
echo  Form::close() ;
}
?>
 {{ Form::open(array('url'=>'main','method' => 'GET','class'=>'navbar-form navbar-left')) }}
		{{ Form::submit('Info',['class' =>'btn btn-success btn-block btn-lg']) }}
{{ Form::close() }}
 {{ Form::open(array('url'=>'get_search','method' => 'GET','class'=>'navbar-form navbar-left')) }}
	{{ Form::submit('Search',['class' =>'btn btn-success btn-block btn-lg  ']) }}
{{ Form::close() }}
 {{ Form::open(array('url'=>'upload','method' => 'GET','class'=>'navbar-form navbar-left')) }}
		{{ Form::submit('Add Data',['class' =>'btn btn-success btn-block btn-lg ']) }}
{{ Form::close() }}

</div>



<?php 
if (Auth::check()){
	echo  '<div class="dropdown" style ="float:right;margin-top:15px;margin-right:80px;"; >
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">'
    .Session::get('user'). 
  '<span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">';
  if(Auth::user()->role=="1"){
  	echo '<li><a href="volunteer_view">Volunteer Ops</a></li>';
  	}
  else if(Auth::user()->role=="2"){
  	echo '<li><a href="request_volunteer">Be as Volunteer</a></li>';
  }
  else {
  	echo '<li><a href="admin_view">Admin Ops</a></li>';
  }
  echo '<li><a href="change_pwd">Change Password</a></li>';
  echo '<li role="separator" class="divider"></li>
    <li><a href="logout">Logout</a></li>
  </ul>
</div>';
}
else{

	echo Form::open(array('url'=>'get_change','method' => 'GET','class'=>'navbar-form','style' => 'float:right;margin-top:15px;margin-right:80px;'));
		echo Form::submit('Login',['class' =>'btn btn-default btn-block btn-md']);
	echo Form::close() ;
}
  ?>
    
    
    
<a class="navbar-brand" rel="home" href="get_search" title="Bus Route Portal" style="float:left;">
        <img style="max-width:80px; margin-top: -24px; "
            alt = "Bus Route Portal Logo"   src={{asset('img/Bus.png')}}>
              
<a class="navbar-brand" rel="home" href="download_app" title="Download Android App" style="float:right;">
        <img style="max-width:120px; margin-top: -5px; "
            alt = "Download Android App Logo" src={{asset('img/downloadAppAndroid.png')}}>          
</a>
<p class="uppercase" style="font-size:24; float:left;margin-top: 10px !important;"><?php if(Session::get('editTrans')!=""){
echo  Session::get('editCity').', '.Session::get('editTrans');
}?></p>
</nav>




