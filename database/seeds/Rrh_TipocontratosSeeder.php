<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Rrh_TipocontratosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rrh__tipocontratos')->insert(['nomtipocontrato'=>'Pasante']);
        DB::table('rrh__tipocontratos')->insert(['nomtipocontrato'=>'Eventual']);
        DB::table('rrh__tipocontratos')->insert(['nomtipocontrato'=>'Planta']);
    }
}

