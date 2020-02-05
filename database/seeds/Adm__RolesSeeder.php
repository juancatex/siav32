<?php

use Illuminate\Database\Seeder;
use App\Adm_Role;
use App\Adm_Permiso;

class Adm__RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $permiso = Adm_Permiso::where('nompermiso', 'Roles-Permisos')->first();

        $role = new Adm_Role();
        $role->nomrol = 'Admin';
        $role->descripcionrol = 'Administrator';
        $role->save();        
        $role->permisos()->attach($permiso->idpermiso);

        $role = new Adm_Role();
        $role->nomrol = 'Sistemas';
        $role->descripcionrol = 'Mantenimiento del sistema';
        $role->save();

        $role = new Adm_Role();
        $role->nomrol = 'Contador';
        $role->descripcionrol = 'Contador General';
        $role->save();

        $role = new Adm_Role();
        $role->nomrol = 'Operador Prestamos';
        $role->descripcionrol = 'Persona que registra los prestamos';
        $role->save();

        $role = new Adm_Role();
        $role->nomrol = 'Supervisor Prestamos';
        $role->descripcionrol = 'Persona que aprueba los prestamos';
        $role->save();

        $role = new Adm_Role();
        $role->nomrol = 'Operador de Servicios';
        $role->descripcionrol = 'Persona que registra los servicios';
        $role->save();

        $role = new Adm_Role();
        $role->nomrol = 'Operador de DAARO';
        $role->descripcionrol = 'Persona que registra las devoluciones de aportes';
        $role->save();
    }
}
