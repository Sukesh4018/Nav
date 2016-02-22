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
    display: inline-block;
}
#section {
    padding:10px;
    float:left;
    padding:10px;
    display: inline-block;

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
}
</style> 


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
 @include('header_js')
 <body>

<div id="header">
<nav class="navbar navbar-inverted navbar-static-top">



<div  class="btn-group">
<p class="uppercase" style="font-size:24; float:left;margin-top: 10px !important;"><?php if(Session::get('editTrans')!=""){
echo  Session::get('editCity').', '.Session::get('editTrans');
}?>{{ Form::open(array('url'=>'get_change','method' => 'GET','class'=>'navbar-form navbar-left')) }}
	{{ Form::submit('Change Agency',['class' =>'btn btn-success btn-block btn-lg']) }}
{{ Form::close() }}
 
 {{ Form::open(array('url'=>'upload','method' => 'GET','class'=>'navbar-form navbar-right')) }}
		{{ Form::submit('Add Data',['class' =>'btn btn-success btn-block btn-lg ']) }}
{{ Form::close() }}

 {{ Form::open(array('url'=>'get_search','method' => 'GET','class'=>'navbar-form navbar-right')) }}
	{{ Form::submit('Search',['class' =>'btn btn-success btn-block btn-lg  ']) }}
{{ Form::close() }}

{{ Form::open(array('url'=>'main','method' => 'GET','class'=>'navbar-form navbar-right')) }}
		{{ Form::submit('Info',['class' =>'btn btn-success btn-block btn-lg']) }}
{{ Form::close() }}
</p>

</div>

 <div class="dropdown" style ="float:right;margin-top:15px;margin-right:80px;"; >
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <?php echo Session::get('user'); ?> 
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
    <li><a href="#">Action</a></li>
    <li><a href="change_pwd">Change Password</a></li>
    <li role="separator" class="divider"></li>
    <li><a href="logout">Logout</a></li>
  </ul>
</div>
<a class="navbar-brand" rel="home" href="get_search" title="Bus Route Portal" style="float:left;">
        <img style="max-width:80px; margin-top: -20px; "
              src={{asset('img/Bus.png')}}>
              

            
<a class="navbar-brand" rel="home" href="download_app" title="Download Android App" style="float:right;">
        <img style="max-width:120px; margin-top: -5px; "
            alt = "Logo" src={{asset('img/downloadAppAndroid.png')}}>          
</a>
</nav>
</div>



