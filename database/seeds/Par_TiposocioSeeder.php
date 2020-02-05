<?php

use Illuminate\Database\Seeder;

class Par_TiposocioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('par__tiposocios')->insert(['nomtiposocio'=>'Servicio Activo']);
        DB::table('par__tiposocios')->insert(['nomtiposocio'=>'Servicio Pasivo']);        
        DB::table('par__tiposocios')->insert(['nomtiposocio'=>'Retiro Voluntario/Obligado']);
        DB::table('par__tiposocios')->insert(['nomtiposocio'=>'Fallecido']);
    }
}
