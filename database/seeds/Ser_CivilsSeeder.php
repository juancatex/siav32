<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Ser_CivilsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ser__civils')->insert(['nombre'=>'JORGE ORLANDO','apaterno'=>'TORRICO','amaterno'=>'VASQUEZ','fechanac'=>'1988-12-30','sexo'=>'m','ci'=>'6865974','iddepartamento'=>'2','telcelular'=>'67011988']);
        DB::table('ser__civils')->insert(['nombre'=>'ROCIO','apaterno'=>'MAMANI','amaterno'=>'HUATA','fechanac'=>'1986-10-05','sexo'=>'f','ci'=>'6752984','iddepartamento'=>'2','telcelular'=>'72040084']);
        DB::table('ser__civils')->insert(['nombre'=>'IVAN','apaterno'=>'LAIME','amaterno'=>'SARSURI','fechanac'=>'1985-04-14','sexo'=>'m','ci'=>'4906232','iddepartamento'=>'2','telcelular'=>'78784448']);
        DB::table('ser__civils')->insert(['nombre'=>'EDSON ROLANDO','apaterno'=>'SALAZAR','amaterno'=>'VARGAS ','fechanac'=>'1988-01-28','sexo'=>'m','ci'=>'6184097','iddepartamento'=>'2','telcelular'=>'72047741']);
        DB::table('ser__civils')->insert(['nombre'=>'VANNESA GABRIELA','apaterno'=>'VERA','amaterno'=>'AGRAMONT','fechanac'=>'1989-04-28','sexo'=>'f','ci'=>'6726390','iddepartamento'=>'2','telcelular'=>'72536346']);        
    }
}
