<?php

use Illuminate\Database\Seeder;

class Apo_TipoaportesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('apo__tipoaportes')->insert([
            'descripcion'=>'Regular',
            //'idcuentacontable'=>'1',
        ]);
        DB::table('apo__tipoaportes')->insert([
            'descripcion'=>'Regularizacion',
            //'idcuentacontable'=>'2',
        ]);
        DB::table('apo__tipoaportes')->insert([
            'descripcion'=>'Reintegro',
            //'idcuentacontable'=>'3',
        ]);
    }
}
