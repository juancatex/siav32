<?php

use Illuminate\Database\Seeder;

class Par_Prestamos_escalasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   DB::table('par__prestamos__escalas')->insert(['nomescala'=>'Prestamos en Bolivianos Servicios']); 
        DB::table('par__prestamos__escalas')->insert(['nomescala'=>'Prestamos en Bolivianos Emergencias']); 
        DB::table('par__prestamos__escalas')->insert(['nomescala'=>'Prestamos en Dolares con Garantes']); 
        DB::table('par__prestamos__escalas')->insert(['nomescala'=>'Prestamos en Dolares sin Garantes']); 
    }
}
