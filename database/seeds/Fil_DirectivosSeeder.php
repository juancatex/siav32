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
        DB::table('fil__directivos')->insert(['idcargo'=>1,'idfilial'=>1,'idsocio'=>13193,'fechaini'=>'2016-08-29','fechafin'=>'2019-08-30','activo'=>0]);
        DB::table('fil__directivos')->insert(['idcargo'=>3,'idfilial'=>1,'idsocio'=>6779,'fechaini'=>'2016-08-29','fechafin'=>'2019-08-30','activo'=>0]);
        DB::table('fil__directivos')->insert(['idcargo'=>4,'idfilial'=>1,'idsocio'=>8948,'fechaini'=>'2016-08-29','fechafin'=>'2019-08-30','activo'=>0]);
        DB::table('fil__directivos')->insert(['idcargo'=>5,'idfilial'=>1,'idsocio'=>962,'fechaini'=>'2016-08-29','fechafin'=>'2019-08-30','activo'=>0]);
        DB::table('fil__directivos')->insert(['idcargo'=>6,'idfilial'=>1,'idsocio'=>6478,'fechaini'=>'2016-08-29','fechafin'=>'2019-08-30','activo'=>0]);
        DB::table('fil__directivos')->insert(['idcargo'=>7,'idfilial'=>1,'idsocio'=>13299,'fechaini'=>'2016-08-29','fechafin'=>'2019-08-30','activo'=>0]);
        DB::table('fil__directivos')->insert(['idcargo'=>1,'idfilial'=>1,'idsocio'=>4711,'fechaini'=>'2019-09-02','fechafin'=>'2022-08-31']);
        DB::table('fil__directivos')->insert(['idcargo'=>2,'idfilial'=>1,'idsocio'=>6165,'fechaini'=>'2019-09-02','fechafin'=>'2022-08-31']);
        DB::table('fil__directivos')->insert(['idcargo'=>3,'idfilial'=>1,'idsocio'=>1886,'fechaini'=>'2019-09-02','fechafin'=>'2022-08-31']);
        DB::table('fil__directivos')->insert(['idcargo'=>4,'idfilial'=>1,'idsocio'=>6242,'fechaini'=>'2019-09-02','fechafin'=>'2022-08-31']);
        DB::table('fil__directivos')->insert(['idcargo'=>8,'idfilial'=>1,'idsocio'=>13116,'fechaini'=>'2019-09-02','fechafin'=>'2022-08-31']);
        DB::table('fil__directivos')->insert(['idcargo'=>9,'idfilial'=>1,'idsocio'=>8320,'fechaini'=>'2019-09-02','fechafin'=>'2022-08-31']);
        DB::table('fil__directivos')->insert(['idcargo'=>10,'idfilial'=>1,'idsocio'=>6573,'fechaini'=>'2019-09-02','fechafin'=>'2022-08-31']);
        DB::table('fil__directivos')->insert(['idcargo'=>11,'idfilial'=>1,'idsocio'=>13109,'fechaini'=>'2019-09-02','fechafin'=>'2022-08-31']);
        DB::table('fil__directivos')->insert(['idcargo'=>12,'idfilial'=>1,'idsocio'=>14126,'fechaini'=>'2019-09-02','fechafin'=>'2022-08-31']);
        DB::table('fil__directivos')->insert(['idcargo'=>1,'idfilial'=>2,'idsocio'=>1223,'fechaini'=>'2017-06-09','fechafin'=>'2019-06-09']);
        DB::table('fil__directivos')->insert(['idcargo'=>3,'idfilial'=>2,'idsocio'=>10660,'fechaini'=>'2017-06-09','fechafin'=>'2019-06-09']);
        DB::table('fil__directivos')->insert(['idcargo'=>1,'idfilial'=>3,'idsocio'=>13452,'fechaini'=>'2019-04-22','fechafin'=>'2021-04-22']);
        DB::table('fil__directivos')->insert(['idcargo'=>3,'idfilial'=>3,'idsocio'=>6461,'fechaini'=>'2019-04-22','fechafin'=>'2021-04-22']);
        DB::table('fil__directivos')->insert(['idcargo'=>12,'idfilial'=>3,'idsocio'=>7345,'fechaini'=>'2019-04-22','fechafin'=>'2021-04-22']);
        
    }
}
