<?php

class AccountController extends Controller {
	
function login() {
    // Getting all post data
    $data = Input::all();
    Auth::logout();
    // Applying validation rules.
    $rules = array(
		'username' => 'required',
		'password' => 'required|min:5',
	     );
    $validator = Validator::make($data, $rules);
    if ($validator->fails()){
      // If validation falis redirect back to login.
      return Redirect::to('/login')->withInput(Input::except('password'))->withErrors($validator);
    }
    else {
      $userdata = array(
		    'username' => Input::get('username'),
		    'password' => Input::get('password')
		  );
      // doing login.
      if (Auth::validate($userdata)) {
        if (Auth::attempt($userdata)) {
          $username = Auth::user()->username;
          Session::put('username',$username);
          Session::put('user',Auth::user()->name);
          return Redirect::intended('/get_search');
        }
      } 
      else {
        // if any error send back with message.
        Session::flash('error', 'Wrong Username/Password '); 
        return Redirect::to('login');
      }
    }
  }

function register_user(){
	    // Getting all post data
    $data = Input::all();
    // Applying validation rules.
    $rules = array(
		'username' => 'required',
		'email'   => 'required|email',
		'password' => 'required|min:5',
	     );
    $validator = Validator::make($data, $rules);
    if ($validator->fails()){
      // If validation falis redirect back to login.
      return Redirect::to('/register')->withInput(Input::except('password'))->withErrors($validator);
    }
    else {
	    $username = Input::get('username');
	    $email    = Input::get('email');
	    $password = Input::get('password');
	    $repassword = Input::get('repassword');
	    $name = Input::get('name');
	    $user = User::where('username', '=', $username)->first();
		if ($user !== null) {
			Session::flash('error', 'The username is not available!'); 
        		return Redirect::to('register');
		}
	    if($password == $repassword){	    	
	    	$hash_password = Hash::make($password);
	    	$user = new User;
	    	$user->name = $name;
	    	$user->username = $username;
	    	$user->email = $email;
	    	$user->password = $hash_password;
	    	$user->role = '2';
	    	$user->save();
	    	return Redirect::to('successful_register');
	    	
	    }
	    else{
	    	Session::flash('error', 'The Passwords do not match!'); 
        	return Redirect::to('register');
	    }
		  
    }
	
}

function change_pwd(){
	$data = Input::all();
    // Applying validation rules.
    $rules = array(
		'password' => 'required|min:5',
	     );
    $validator = Validator::make($data, $rules);
    if ($validator->fails()){
      // If validation falis redirect back to login.
      return Redirect::to('/change_pwd')->withInput(Input::except('password'))->withErrors($validator);
    }
    else{
    	 $oldpassword = Input::get('oldpassword');
    	 $password = Input::get('password');
	 $repassword = Input::get('repassword');
	 $username  =  Session::get('username');  
	 $hashedPassword = Auth::user()->password;		 
      if (Hash::check($oldpassword, $hashedPassword)) {
          if($password == $repassword){	    	
	    	$hash_password = Hash::make($password);
	    	$user = User::where('username','=', $username)->first();
	    	//print_r($user);
	    	//echo $user->name;
	    	$user->password = $hash_password;
	    	$user->save();
	    	echo '<script>window.alert("Password Changed successfully!");</script>';
	    	return Redirect::to('get_search');	    	
	    }
	    else{
	    	Session::flash('error', 'The Passwords do not match!'); 
        	return Redirect::to('change_pwd');
	    }
        
      } 
      else {
        Session::flash('error', 'The entered password is wrong!'); 
        return Redirect::to('change_pwd');
      }
      
      
	   
    }
	
}

function admin_login() {
    // Getting all post data
    $data = Input::all();
    Auth::logout();
    // Applying validation rules.
    $rules = array(
		'username' => 'required',
		'password' => 'required|min:5',
	     );
    $validator = Validator::make($data, $rules);
    if ($validator->fails()){
      // If validation falis redirect back to login.
      return Redirect::to('/admin_login')->withInput(Input::except('password'))->withErrors($validator);
    }
    else {
      $userdata = array(
		    'username' => Input::get('username'),
		    'password' => Input::get('password'),
		    'role' => '0'
		  );
      // doing login.
      if (Auth::validate($userdata)) {
        if (Auth::attempt($userdata)) {
          $username = Auth::user()->username;
          Session::put('username',$username);
          Session::put('user',Auth::user()->name);
          return Redirect::intended('/get_search');
        }
      } 
      else {
        // if any error send back with message.
        Session::flash('error', 'Wrong Username/Password '); 
        return Redirect::to('admin_login');
      }
    }
  }
  
  function request_volunteer(){
  	$username = Auth::user()->username;
  	$user = User::where('username','=', $username)->first();
  	$name = $user->name;
  	$email = $user->email;
  	$ldate = date('Y-m-d H:i:s');
 
  	DB::insert( DB::raw("Replace into request_volunteer(email,name,username,created_at) values(:var1,:var2,:var3,:var4)"), array('var1' => $email,'var2' => $name,'var3' => $username,'var4' => $ldate,)); 
  	//DB::table('request_volunteer')->insert(['email' => $email, 'name' => $name,'username' => $username,'created_at' =>  $ldate]);
	return Redirect::to('get_search')->with('success',"Congratulations! You request for volunteer is under review"); 
  }

}
