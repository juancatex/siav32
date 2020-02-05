<?php

use Illuminate\Database\Seeder;

class Daa_Estudiomatematico extends Seeder
{
    /**
     * Run the database seeds.//
     *
     * @return void
     */
    public function run()
    {
        DB::table('daa__estudiomatematicos')->insert(['nomestudiomatematico'=>'Est. Mat 2019','formula'=>'$var0 = $apo->aporte * pow((1+($j/$m)),($cantidadaportes+1-$p));$var1 = (pow((1+($j/$m)),($cantidadaportes+1-$p))-1);','anioinicio'=>'1999','aniofinal'=>'2011','activo'=>'1']);
    }
}
