<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$faker = Faker\Factory::create();
    	$user = new App\User;
    	$user->name = $faker->name;
    	$user->email = 'admin@admin.com';
    	$user->password = bcrypt('password');
        $user->save();
        $user->roles()->attach(1);

        $user = new App\User;
    	$user->name = $faker->name;
    	$user->email = 'konstruktor@konstruktor.com';
    	$user->password = bcrypt('password');
        $user->save();
        $user->roles()->attach(2);

        $user = new App\User;
    	$user->name = $faker->name;
    	$user->email = 'user@user.com';
    	$user->password = bcrypt('password');
        $user->save();
        $user->roles()->attach(3);
    }
}
