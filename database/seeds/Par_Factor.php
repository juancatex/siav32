<?php

use Illuminate\Database\Seeder;

class Par_Factor extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('par__factors')->insert(['nomfactor'=>'Historial de Prestamos']);
        DB::table('par__factors')->insert(['nomfactor'=>'Estado de Prestamo']);
        DB::table('par__factors')->insert(['nomfactor'=>'Monto Solicitado']);
   }
}
