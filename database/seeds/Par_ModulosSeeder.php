<?php

use Illuminate\Database\Seeder;

class Par_ModulosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('par__modulos')->insert(['nommodulo'=>'afiliaciones']);
        DB::table('par__modulos')->insert(['nommodulo'=>'aportes']);
        DB::table('par__modulos')->insert(['nommodulo'=>'préstamos']);
        DB::table('par__modulos')->insert(['nommodulo'=>'servicios']);
        DB::table('par__modulos')->insert(['nommodulo'=>'parámetros']);
        DB::table('par__modulos')->insert(['nommodulo'=>'administración']);
        DB::table('par__modulos')->insert(['nommodulo'=>'contabilidad']);
        DB::table('par__modulos')->insert(['nommodulo'=>'DAARO']);
        DB::table('par__modulos')->insert(['nommodulo'=>'activos fijos']);
        DB::table('par__modulos')->insert(['nommodulo'=>'filiales']);
        DB::table('par__modulos')->insert(['nommodulo'=>'recursos humanos']);
        DB::table('par__modulos')->insert(['nommodulo'=>'almacenes']);
        DB::table('par__modulos')->insert(['nommodulo'=>'presupuesto']);
        DB::table('par__modulos')->insert(['nommodulo'=>'global']);        
        DB::table('par__modulos')->insert(['nommodulo'=>'acreedores']);        
    }
}
