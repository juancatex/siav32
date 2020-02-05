<?php

use Illuminate\Database\Seeder;

class Alm_MedidasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alm__medidas')->insert(['nommedida'=>'Unidad']);
        DB::table('alm__medidas')->insert(['nommedida'=>'Hoja']);
        DB::table('alm__medidas')->insert(['nommedida'=>'Block']);
        DB::table('alm__medidas')->insert(['nommedida'=>'Caja']);
        DB::table('alm__medidas')->insert(['nommedida'=>'Metro']);
        DB::table('alm__medidas')->insert(['nommedida'=>'Rollo']);
        DB::table('alm__medidas')->insert(['nommedida'=>'Bolsa']);
        DB::table('alm__medidas')->insert(['nommedida'=>'Paquete']);
        
    }
}
