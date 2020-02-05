<?php

use Illuminate\Database\Seeder;

class Alm_GruposSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alm__grupos')->insert(['codgrupo'=>'100000','nomgrupo'=>'PAPEL DE ESCRITORIO']);
        DB::table('alm__grupos')->insert(['codgrupo'=>'200000','nomgrupo'=>'PRODUCTOS ARTES GRÁFICAS PAPEL Y CARTÓN']);
        DB::table('alm__grupos')->insert(['codgrupo'=>'300000','nomgrupo'=>'UTILES DE ESCRITORIO Y OFICINA']);
        DB::table('alm__grupos')->insert(['codgrupo'=>'400000','nomgrupo'=>'ACCESORIOS DE COMPUTACIÓN']);
        DB::table('alm__grupos')->insert(['codgrupo'=>'500000','nomgrupo'=>'ACCESORIOS ELÉCTRICOS']);
        DB::table('alm__grupos')->insert(['codgrupo'=>'600000','nomgrupo'=>'OTROS MATERIALES Y SUMINISTROS']);
        DB::table('alm__grupos')->insert(['codgrupo'=>'700000','nomgrupo'=>'MATERIAL DE LIMPIEZA']);
        DB::table('alm__grupos')->insert(['codgrupo'=>'800000','nomgrupo'=>'ACCESORIOS']);
        DB::table('alm__grupos')->insert(['codgrupo'=>'900000','nomgrupo'=>'COMBOS DE SALUD']);

    }
}
