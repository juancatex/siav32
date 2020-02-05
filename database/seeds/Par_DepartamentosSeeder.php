<?php

use Illuminate\Database\Seeder;

class Par_DepartamentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('par_departamentos')->insert(['nomdepartamento'=>'Chuquisaca','abrvdep'=>'CH','orden'=>4]);
        DB::table('par_departamentos')->insert(['nomdepartamento'=>'La Paz','abrvdep'=>'LP','orden'=>1]);
        DB::table('par_departamentos')->insert(['nomdepartamento'=>'Cochabamba','abrvdep'=>'CB','orden'=>2]);
        DB::table('par_departamentos')->insert(['nomdepartamento'=>'Oruro','abrvdep'=>'OR','orden'=>5]);
        DB::table('par_departamentos')->insert(['nomdepartamento'=>'Potosí','abrvdep'=>'PT','abrvdep2'=>'PT','orden'=>6]);
        DB::table('par_departamentos')->insert(['nomdepartamento'=>'Tarija','abrvdep'=>'TJ','abrvdep2'=>'TA','orden'=>7]);
        DB::table('par_departamentos')->insert(['nomdepartamento'=>'Santa Cruz','abrvdep'=>'SC','orden'=>3]);
        DB::table('par_departamentos')->insert(['nomdepartamento'=>'Beni','abrvdep'=>'BN','abrvdep2'=>'BN','orden'=>8]);
        DB::table('par_departamentos')->insert(['nomdepartamento'=>'Pando','abrvdep'=>'PD','abrvdep2'=>'PD','orden'=>9]);
    }
}
