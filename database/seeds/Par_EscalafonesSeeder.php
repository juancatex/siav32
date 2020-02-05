<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Par_EscalafonesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('par_escalafones')->insert(['nomescalafon'=>'ARMAS']);
        DB::table('par_escalafones')->insert(['nomescalafon'=>'MUS.EGR']);
        DB::table('par_escalafones')->insert(['nomescalafon'=>'MUS']);        
        DB::table('par_escalafones')->insert(['nomescalafon'=>'S.PROF.']);
        DB::table('par_escalafones')->insert(['nomescalafon'=>'SERV.']);
        DB::table('par_escalafones')->insert(['nomescalafon'=>'SAN.PRF']);
        DB::table('par_escalafones')->insert(['nomescalafon'=>'SAN.']);
        DB::table('par_escalafones')->insert(['nomescalafon'=>'CPN.']);
        DB::table('par_escalafones')->insert(['nomescalafon'=>'CIVIL']);
        DB::table('par_escalafones')->insert(['nomescalafon'=>'HONORIF.']);        
    }
}
