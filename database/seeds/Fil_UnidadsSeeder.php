<?php

use Illuminate\Database\Seeder;

class Fil_UnidadsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fil__unidads')->insert(['codunidad'=>'01','abrev'=>'PRES','nomunidad'=>'PRESIDENCIA','nomcargo'=>'PRESIDENTE']);
        DB::table('fil__unidads')->insert(['codunidad'=>'02','abrev'=>'VPRE','nomunidad'=>'VICEPRESIDENCIA','nomcargo'=>'VICEPRESIDENTE']);
        DB::table('fil__unidads')->insert(['codunidad'=>'03','abrev'=>'SHD' ,'nomunidad'=>'SECRETARÍA DE HACIENDA','nomcargo'=>'SECRETARIO DE HACIENDA']);
        DB::table('fil__unidads')->insert(['codunidad'=>'04','abrev'=>'DADM','nomunidad'=>'DIRECCIÓN ADMINISTRATIVA','nomcargo'=>'DIRECTOR ADMINISTRATIVO']);
        DB::table('fil__unidads')->insert(['codunidad'=>'05','abrev'=>'SBSV','nomunidad'=>'SECRETARÍA DE BIENESTAR SOCIAL, VIVIENDA Y RÉGIMEN INTERNO','nomcargo'=>'SECRETARIO BIENESTAR SOCIAL, VIVIENDA Y RÉGIMEN INTERNO','activo'=>0]);
        DB::table('fil__unidads')->insert(['codunidad'=>'06','abrev'=>'SEDC','nomunidad'=>'SECRETARÍA DE EDUCACIÓN, DEPORTES Y CULTURA','nomcargo'=>'SECRETARIO DE EDUCACIÓN, DEPORTES Y CULTURA','activo'=>0]);
        DB::table('fil__unidads')->insert(['codunidad'=>'07','abrev'=>'SGA' ,'nomunidad'=>'SECRETARÍA GENERAL Y DE ACTAS','nomcargo'=>'SECRETARIO GENERAL Y DE ACTAS','activo'=>0]);       
        DB::table('fil__unidads')->insert(['codunidad'=>'05','abrev'=>'SBSV','nomunidad'=>'SECRETARÍA DE BIENESTAR SOCIAL Y VIVIENDA','nomcargo'=>'SECRETARIO DE BIENESTAR SOCIAL Y VIVIENDA']);
        DB::table('fil__unidads')->insert(['codunidad'=>'06','abrev'=>'SRIP','nomunidad'=>'SECRETARÍA DE RÉGIMEN INTERNO Y PERSONAL','nomcargo'=>'SECRETARIO DE RÉGIMEN INTERNO Y PERSONAL']);
        DB::table('fil__unidads')->insert(['codunidad'=>'07','abrev'=>'SPP' ,'nomunidad'=>'SECRETARÍA DE RELACIONES PÚBLICAS, PRENSA Y PROPAGANDA','nomcargo'=>'SECRETARIO DE RELACIONES PÚBLICAS, PRENSA Y PROPAGANDA']);
        DB::table('fil__unidads')->insert(['codunidad'=>'08','abrev'=>'SEC' ,'nomunidad'=>'SECRETARÍA DE EDUCACIÓN Y CULTURA','nomcargo'=>'SECRETARIO DE EDUCACIÓN Y CULTURA']);
        DB::table('fil__unidads')->insert(['codunidad'=>'09','abrev'=>'SAD' ,'nomunidad'=>'SECRETARÍA DE ACTAS Y DEPORTES','nomcargo'=>'SECRETARIO DE ACTAS Y DEPORTES']);
        DB::table('fil__unidads')->insert(['codunidad'=>'10','abrev'=>'SG' ,'nomunidad'=>'SECRETARÍA GENERAL','nomcargo'=>'SECRETARIO GENERAL']);
    }
}
