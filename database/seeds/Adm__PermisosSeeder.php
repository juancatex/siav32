<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Adm_Permiso;

class Adm__PermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $sql="
        INSERT INTO adm__permisos
        (idventanamodulo, nompermiso, descripcion, metodo, activo, created_at, updated_at)
        select 
        pv.idventanamodulo,
        pv.nomventanamodulo as nompermiso,
        concat('Desc. ', pv.nomventanamodulo) as descripcion,
        '' as metodo,
        1 as activo,
        (select CURDATE()) as created_at ,
        (select CURDATE()) as updated_at 
        from par__ventanamodulos pv ";

        DB::select($sql);

        // $permiso = new Adm_Permiso();
        // $permiso->nompermiso = 'Roles total';
        // $permiso->descripcion = 'Acceso total a Roles';
        // $permiso->idventanamodulo = 19;
        // $permiso->metodo = 'all';
        // $permiso->save();
                
    }
}
