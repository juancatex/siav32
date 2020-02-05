<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Rrh_MotivosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rrh__motivos')->insert(['nommotivo'=>'Aniversario natal']);
        DB::table('rrh__motivos')->insert(['nommotivo'=>'Exámen médico']);
        DB::table('rrh__motivos')->insert(['nommotivo'=>'Matrimonio']);
        DB::table('rrh__motivos')->insert(['nommotivo'=>'Nacimiento de hijos']);
        DB::table('rrh__motivos')->insert(['nommotivo'=>'Fallecimiento de familiares']);
        DB::table('rrh__motivos')->insert(['nommotivo'=>'Diligencias personales']);
        DB::table('rrh__motivos')->insert(['nommotivo'=>'Cursos de capacitación']);
        DB::table('rrh__motivos')->insert(['nommotivo'=>'Atención médica']);
    }
}

