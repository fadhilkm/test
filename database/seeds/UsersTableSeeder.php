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
    	// $user->name = $faker->name;
        $user->name = 'Rieqy Muwachid Erysya';
    	$user->email = 'admin@admin.com';
    	$user->password = bcrypt('password');
        $user->save();
        $user->roles()->attach(Role::where('name','admin')->first()->id);

        $konstruktors = ['Adhi Tri Ardiyanto, S.Kom.',
                        'Resti Budianti, S.Pd.',
                        'Indaryanto, M.Kom.',
                        'Dody Iskandar, M.Eng.',
                        'Wiwik Akhirul Aeni, M.Kom.',
                        'Maulina Akhadiyah, M.Pd.',
                        'Sigit Hendriyanto, S.Kom.',
                        'Son Kuncara Edhi, S.Kom.',
                        'Manikowati, M.Pd.'];

        foreach($konstruktors as $key=>$konstruktor){
            $user = new App\User;
            $user->name = $faker->name;
            $user->email = 'konstruktor'.$key.'@konstruktor.com';
            $user->password = bcrypt('password');
            $user->save();
            $user->roles()->attach(Role::where('name','konstruktor')->first()->id);
        }



        $user = new App\User;
    	$user->name = $faker->name;
    	$user->email = 'user@user.com';
    	$user->password = bcrypt('password');
        $user->save();
        $user->roles()->attach(Role::where('name','user')->first()->id);
    }
}
