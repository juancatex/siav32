<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Ser_ImplementosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ser__implementos')->insert(['idservicio'=>3,'nomimplemento'=>'Colchón']);
        DB::table('ser__implementos')->insert(['idservicio'=>3,'nomimplemento'=>'Frazadas']);
        DB::table('ser__implementos')->insert(['idservicio'=>3,'nomimplemento'=>'Sábanas']);
        DB::table('ser__implementos')->insert(['idservicio'=>3,'nomimplemento'=>'Fundas']);
        DB::table('ser__implementos')->insert(['idservicio'=>3,'nomimplemento'=>'Colchacama']);
        DB::table('ser__implementos')->insert(['idservicio'=>3,'nomimplemento'=>'Televisor']);
        DB::table('ser__implementos')->insert(['idservicio'=>3,'nomimplemento'=>'Control Remoto']);
        DB::table('ser__implementos')->insert(['idservicio'=>3,'nomimplemento'=>'Llave Puerta']);
        DB::table('ser__implementos')->insert(['idservicio'=>3,'nomimplemento'=>'Llave Ropero']);

    }
}
