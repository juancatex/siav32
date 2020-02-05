<?php

use Illuminate\Database\Seeder;

class Apo_EstadoAporte extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('apo__estados')->insert([
            'nomestado'=>'Comision',
        ]);

        DB::table('apo__estados')->insert([
            'nomestado'=>'Proceso de Liquidacion',
        ]);

        DB::table('apo__estados')->insert([
            'nomestado'=>'Afiliado Inactivo',
        ]);

        DB::table('apo__estados')->insert([
            'nomestado'=>'Con licencia maxima',
        ]);

        DB::table('apo__estados')->insert([
            'nomestado'=>'con prestacion liquidada',
        ]);

        DB::table('apo__estados')->insert([
            'nomestado'=>'retirado',
        ]);
        DB::table('apo__estados')->insert([
            'nomestado'=>'suspendido',
        ]);

        DB::table('apo__estados')->insert([
            'nomestado'=>'activo',
        ]);
    }
}
