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
        'password'=>bcrypt('admin'),
        'admin'=>1
       ));

       User::updateOrCreate(array(
         'name'=>'justina',
         'email'=>'1justina@coingate.com',
         'password'=>bcrypt('jzlabyte'),
         'admin'=>0
        ));
    }
}
