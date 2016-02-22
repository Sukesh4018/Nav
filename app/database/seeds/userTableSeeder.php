<?php

class UserTableSeeder extends Seeder
{
  public function run()
  {
    DB::table('users')->delete();
    User::create(array(
    	'name'     => 'admin',
        'username' => 'admin',
        'email'    => 'sp506377@gmail.com',
        'role'     => '0',
        'password' => Hash::make('passme'),
    ));
  }
}
