<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Rrh_ProfesionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rrh__profesions')->insert(['nomprofesion'=>'Administrador de Empresas']);
        DB::table('rrh__profesions')->insert(['nomprofesion'=>'Abogado']);
        DB::table('rrh__profesions')->insert(['nomprofesion'=>'Auditor']);
        DB::table('rrh__profesions')->insert(['nomprofesion'=>'Contador']);
        DB::table('rrh__profesions')->insert(['nomprofesion'=>'Auxiliar Contable']);
        DB::table('rrh__profesions')->insert(['nomprofesion'=>'Comunicador Social']);
        DB::table('rrh__profesions')->insert(['nomprofesion'=>'Diseñador Gráfico']);
        DB::table('rrh__profesions')->insert(['nomprofesion'=>'Economista']);
        DB::table('rrh__profesions')->insert(['nomprofesion'=>'Informático']);
        DB::table('rrh__profesions')->insert(['nomprofesion'=>'Ingeniero Comercial']);
        DB::table('rrh__profesions')->insert(['nomprofesion'=>'Ingeniero Financiero']);
        DB::table('rrh__profesions')->insert(['nomprofesion'=>'Ingeniero de Sistemas']);
        DB::table('rrh__profesions')->insert(['nomprofesion'=>'Secretaria Ejecutiva']);
    }
}

