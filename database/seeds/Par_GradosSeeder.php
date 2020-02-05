<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Par_GradosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('par_grados')->insert(['nomgrado'=>'SGTO.INL.','abrev'=>'SGI']);
        DB::table('par_grados')->insert(['nomgrado'=>'SGTO.2DO.','abrev'=>'SG2']);
        DB::table('par_grados')->insert(['nomgrado'=>'SGTO.1RO.','abrev'=>'SG1']);
        DB::table('par_grados')->insert(['nomgrado'=>'SOF.INL.','abrev'=>'SOI']);
        DB::table('par_grados')->insert(['nomgrado'=>'SOF.2DO.','abrev'=>'SO2']);
        DB::table('par_grados')->insert(['nomgrado'=>'SOF.1RO.','abrev'=>'SO1']);
        DB::table('par_grados')->insert(['nomgrado'=>'SOF.MY.','abrev'=>'SOM']);
        DB::table('par_grados')->insert(['nomgrado'=>'SOF.MTRE.','abrev'=>'SMT']); 
        DB::table('par_grados')->insert(['nomgrado'=>'SGTO.INCL.','abrev'=>'SGI']); 
        /*
        DB::table('par_grados')->insert(['nomgrado'=>'PROF. I',]); 
        DB::table('par_grados')->insert(['nomgrado'=>'GRAL.DIV.',]); 
        DB::table('par_grados')->insert(['nomgrado'=>'SGTO.INCL.',]); 
        DB::table('par_grados')->insert(['nomgrado'=>'AP.ADM.I',]); 
        DB::table('par_grados')->insert(['nomgrado'=>'ADM.I',]); 
        DB::table('par_grados')->insert(['nomgrado'=>'PERSONAL C.O.I.',]); 
        
        DB::table('par_grados')->insert(['nomgrado'=>'AP.ADM.V',]);            
        DB::table('par_grados')->insert(['nomgrado'=>'GRAL.FZA.',]);           
        DB::table('par_grados')->insert(['nomgrado'=>'GRAL.BRIG.',]);          
        DB::table('par_grados')->insert(['nomgrado'=>'CNL.',]);                
        DB::table('par_grados')->insert(['nomgrado'=>'TCNL.',]);               
        DB::table('par_grados')->insert(['nomgrado'=>'MY.',]);                 
        DB::table('par_grados')->insert(['nomgrado'=>'CAP.',]);                
        DB::table('par_grados')->insert(['nomgrado'=>'TTE.',]);                
        DB::table('par_grados')->insert(['nomgrado'=>'STTE.',]);               
        DB::table('par_grados')->insert(['nomgrado'=>'vALMTE.',]);              
        DB::table('par_grados')->insert(['nomgrado'=>'V.ALMTE.',]);            
        DB::table('par_grados')->insert(['nomgrado'=>'C.ALMTE.',]);
        */
    }
}
   
            