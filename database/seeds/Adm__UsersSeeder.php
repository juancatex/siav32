<?php

use Illuminate\Database\Seeder;
use App\Adm_User;
use App\Adm_Role;

class Adm__UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$role_user = Adm_Role::where('nomrol', 'Sistemas')->first();
        $role_admin = Adm_Role::where('nomrol', 'Admin')->first();        


        $user = new Adm_User();
        $user->idempleado = '1';
        $user->username = 'user';  //es el admin
        $user->email = 'user@example.com';
        $user->password = bcrypt('secret');
        $user->save();
        $user->roles()->attach($role_admin->idrole);
        
        // $user = new Adm_User();
        // $user->idempleado = '1';
        // $user->username = 'admin';
        // $user->email = 'admin@example.com';
        // $user->password = bcrypt('secret');
        // $user->save();
        // $user->roles()->attach($role_admin->idrole);
    }
}
