<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Rrh_FormacionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rrh__formacions')->insert(['nomformacion'=>'Primaria']);
        DB::table('rrh__formacions')->insert(['nomformacion'=>'Secundaria']);        
        DB::table('rrh__formacions')->insert(['nomformacion'=>'Técnico Medio']);
        DB::table('rrh__formacions')->insert(['nomformacion'=>'Técnico Superior']);
        DB::table('rrh__formacions')->insert(['nomformacion'=>'Egresado Técnico']);
        DB::table('rrh__formacions')->insert(['nomformacion'=>'Estudiante Universitario']);
        DB::table('rrh__formacions')->insert(['nomformacion'=>'Egresado Universitario']);
        DB::table('rrh__formacions')->insert(['nomformacion'=>'Titulado Universitario']);
        DB::table('rrh__formacions')->insert(['nomformacion'=>'Post Grado']);
        DB::table('rrh__formacions')->insert(['nomformacion'=>'Maestría']);
        DB::table('rrh__formacions')->insert(['nomformacion'=>'Doctorado']);
    }
}

