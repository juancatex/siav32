<?php

use Illuminate\Database\Seeder;

class Act_MotivosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('act__motivos')->insert(['nommotivo'=>'Disposición definitiva de bienes']);
        DB::table('act__motivos')->insert(['nommotivo'=>'Hurto, robo o pérdida']);
        DB::table('act__motivos')->insert(['nommotivo'=>'Mermas y daño físico']);
        DB::table('act__motivos')->insert(['nommotivo'=>'Vencimiento, alteraciones y deterioros']);
        DB::table('act__motivos')->insert(['nommotivo'=>'Inutilización']);
        DB::table('act__motivos')->insert(['nommotivo'=>'Obsolescencia']);
        DB::table('act__motivos')->insert(['nommotivo'=>'Desmantelamiento de edificaciones']);
        DB::table('act__motivos')->insert(['nommotivo'=>'Siniestros fortuitos']);
    }
}
