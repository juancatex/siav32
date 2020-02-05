<?php

use Illuminate\Database\Seeder;

class Act_GruposSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('act__grupos')->insert(['codgrupo'=>'01','nomgrupo'=>'MUEBLES Y ENSERES DE OFICINA','vida'=>10]);
        DB::table('act__grupos')->insert(['codgrupo'=>'02','nomgrupo'=>'EQUIPOS DE COMUNICACIÓN','vida'=>8]);
        DB::table('act__grupos')->insert(['codgrupo'=>'03','nomgrupo'=>'EQUIPO EDUCACIONAL Y RECREATIVO','vida'=>8]);
        DB::table('act__grupos')->insert(['codgrupo'=>'04','nomgrupo'=>'HERRAMIENTAS EN GENERAL','vida'=>4]);
        DB::table('act__grupos')->insert(['codgrupo'=>'05','nomgrupo'=>'EQUIPOS DE COMPUTACIÓN','vida'=>4]);        
        DB::table('act__grupos')->insert(['codgrupo'=>'06','nomgrupo'=>'MUEBLES Y ENSERES EN VIVIENDAS DE PERSONAL','vida'=>10]);
        DB::table('act__grupos')->insert(['codgrupo'=>'07','nomgrupo'=>'EQUIPO MEDICO Y DE LABORATORIO','vida'=>8]);
        //DB::table('act__grupos')->insert(['codgrupo'=>'11','nomgrupo'=>'EDIFICIOS','vida'=>10]);
        //DB::table('act__grupos')->insert(['codgrupo'=>'12','nomgrupo'=>'TERRENOS','vida'=>10]);
    }
}
