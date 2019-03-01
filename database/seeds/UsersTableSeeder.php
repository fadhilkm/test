<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Role;
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
        $user->roles()->attach(Role::where('name','admin')->first()->id);


        $user = new App\User;
    	$user->name = $faker->name;
    	$user->email = 'konstruktor@konstruktor.com';
    	$user->password = bcrypt('password');
        $user->save();
        $user->roles()->attach(Role::where('name','konstruktor')->first()->id);

        $user = new App\User;
        $user->name = $faker->name;
        $user->email = 'konstruktor2@konstruktor2.com';
        $user->password = bcrypt('password');
        $user->save();
        $user->roles()->attach(Role::where('name','konstruktor')->first()->id);

         $user = new App\User;
        $user->name = $faker->name;
        $user->email = 'konstruktor3@konstruktor3.com';
        $user->password = bcrypt('password');
        $user->save();
        $user->roles()->attach(Role::where('name','konstruktor')->first()->id);

        $user = new App\User;
    	$user->name = $faker->name;
    	$user->email = 'user@user.com';
    	$user->password = bcrypt('password');
        $user->save();
        $user->roles()->attach(Role::where('name','user')->first()->id);
    }
}
