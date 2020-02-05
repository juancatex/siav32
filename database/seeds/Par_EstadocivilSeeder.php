<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Par_EstadocivilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('par_estadocivil')->insert(['nomestadocivil'=>'Soltero']);
        DB::table('par_estadocivil')->insert(['nomestadocivil'=>'Casado']);
        DB::table('par_estadocivil')->insert(['nomestadocivil'=>'Divorciado']);
        DB::table('par_estadocivil')->insert(['nomestadocivil'=>'Viudo']);
        
    }
}
