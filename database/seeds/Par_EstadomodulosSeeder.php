<?php

use Illuminate\Database\Seeder;

class Par_EstadomodulosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('par_estadomodulos')->insert(['nomestado'=>'Afi. Activo','idmodulo'=>'1','idestado'=>'1']);
        DB::table('par_estadomodulos')->insert(['nomestado'=>'Afi. Inactivo','idmodulo'=>'1','idestado'=>'2']);                
        DB::table('par_estadomodulos')->insert(['nomestado'=>'Aportes Correctos','idmodulo'=>'2','idestado'=>'1']);
        DB::table('par_estadomodulos')->insert(['nomestado'=>'Aportes Observados, Faltantes','idmodulo'=>'2','idestado'=>'2']);
        DB::table('par_estadomodulos')->insert(['nomestado'=>'Pre. Activo','idmodulo'=>'3','idestado'=>'1']);
        DB::table('par_estadomodulos')->insert(['nomestado'=>'Pre. Inactivo','idmodulo'=>'3','idestado'=>'2']);
        DB::table('par_estadomodulos')->insert(['nomestado'=>'Servicio Activo','idmodulo'=>'4','idestado'=>'1']);
        DB::table('par_estadomodulos')->insert(['nomestado'=>'Reserva Activa','idmodulo'=>'4','idestado'=>'2']);
        DB::table('par_estadomodulos')->insert(['nomestado'=>'Servicio Pasivo','idmodulo'=>'4','idestado'=>'3']);
        DB::table('par_estadomodulos')->insert(['nomestado'=>'Daaro No Pagado','idmodulo'=>'8','idestado'=>'0']);
        DB::table('par_estadomodulos')->insert(['nomestado'=>'Daaro Pagado','idmodulo'=>'8','idestado'=>'1']);
    }
}
