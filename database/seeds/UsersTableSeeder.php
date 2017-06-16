<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      User::updateOrCreate(array(
        'name'=>'justina',
        'email'=>'justina@coingate.com',
        'password'=>'admin',
        'admin'=>1
       ));
    }
}
