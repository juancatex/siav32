<?php

use Illuminate\Database\Seeder;

class Fil_DirectivosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fil__directivos')->insert(['idunidad'=>1,'idfilial'=>1,'idsocio'=>14432,'fechaini'=>'2016-08-29','fechafin'=>'2019-08-30','activo'=>0]);
        DB::table('fil__directivos')->insert(['idunidad'=>3,'idfilial'=>1,'idsocio'=>6781,'fechaini'=>'2016-08-29','fechafin'=>'2019-08-30','activo'=>0]);
        DB::table('fil__directivos')->insert(['idunidad'=>4,'idfilial'=>1,'idsocio'=>8950,'fechaini'=>'2016-08-29','fechafin'=>'2019-08-30','activo'=>0]);
        DB::table('fil__directivos')->insert(['idunidad'=>5,'idfilial'=>1,'idsocio'=>963,'fechaini'=>'2016-08-29','fechafin'=>'2019-08-30','activo'=>0]);
        DB::table('fil__directivos')->insert(['idunidad'=>6,'idfilial'=>1,'idsocio'=>6480,'fechaini'=>'2016-08-29','fechafin'=>'2019-08-30','activo'=>0]);
        DB::table('fil__directivos')->insert(['idunidad'=>7,'idfilial'=>1,'idsocio'=>14538,'fechaini'=>'2016-08-29','fechafin'=>'2019-08-30','activo'=>0]);
        DB::table('fil__directivos')->insert(['idunidad'=>1,'idfilial'=>1,'idsocio'=>4713,'fechaini'=>'2019-09-02','fechafin'=>'2022-08-31']);
        DB::table('fil__directivos')->insert(['idunidad'=>2,'idfilial'=>1,'idsocio'=>6167,'fechaini'=>'2019-09-02','fechafin'=>'2022-08-31']);
        DB::table('fil__directivos')->insert(['idunidad'=>3,'idfilial'=>1,'idsocio'=>1888,'fechaini'=>'2019-09-02','fechafin'=>'2022-08-31']);
        DB::table('fil__directivos')->insert(['idunidad'=>4,'idfilial'=>1,'idsocio'=>6244,'fechaini'=>'2019-09-02','fechafin'=>'2022-08-31']); 
        DB::table('fil__directivos')->insert(['idunidad'=>8,'idfilial'=>1,'idsocio'=>14355,'fechaini'=>'2019-09-02','fechafin'=>'2022-08-31']);
        DB::table('fil__directivos')->insert(['idunidad'=>9,'idfilial'=>1,'idsocio'=>8322,'fechaini'=>'2019-09-02','fechafin'=>'2022-08-31']); 
        DB::table('fil__directivos')->insert(['idunidad'=>10,'idfilial'=>1,'idsocio'=>6575,'fechaini'=>'2019-09-02','fechafin'=>'2022-08-31']);
        DB::table('fil__directivos')->insert(['idunidad'=>11,'idfilial'=>1,'idsocio'=>14348,'fechaini'=>'2019-09-02','fechafin'=>'2022-08-31']); 
        DB::table('fil__directivos')->insert(['idunidad'=>12,'idfilial'=>1,'idsocio'=>15489,'fechaini'=>'2019-09-02','fechafin'=>'2022-08-31']); 
        DB::table('fil__directivos')->insert(['idunidad'=>1,'idfilial'=>2,'idsocio'=>1224,'fechaini'=>'2017-06-09','fechafin'=>'2019-06-09']);
        DB::table('fil__directivos')->insert(['idunidad'=>3,'idfilial'=>2,'idsocio'=>10662,'fechaini'=>'2017-06-09','fechafin'=>'2019-06-09']);
        DB::table('fil__directivos')->insert(['idunidad'=>1,'idfilial'=>3,'idsocio'=>13454,'fechaini'=>'2019-04-22','fechafin'=>'2021-04-22']);
        DB::table('fil__directivos')->insert(['idunidad'=>3,'idfilial'=>3,'idsocio'=>14691,'fechaini'=>'2019-04-22','fechafin'=>'2021-04-22']);
        DB::table('fil__directivos')->insert(['idunidad'=>12,'idfilial'=>3,'idsocio'=>7347,'fechaini'=>'2019-04-22','fechafin'=>'2021-04-22']);
        
    }
}
