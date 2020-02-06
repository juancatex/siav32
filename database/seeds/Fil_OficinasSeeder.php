<?php

use Illuminate\Database\Seeder;

class Fil_OficinasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {       
        DB::table('fil__oficinas')->insert(['idfilial'=>1,'idunidad'=>1,'codoficina'=>'01','nomoficina'=>'UNIDAD JURÍDICA']);
        DB::table('fil__oficinas')->insert(['idfilial'=>1,'idunidad'=>1,'codoficina'=>'02','nomoficina'=>'UNIDAD DE SISTEMAS']);
        DB::table('fil__oficinas')->insert(['idfilial'=>1,'idunidad'=>1,'codoficina'=>'03','nomoficina'=>'AUDITORÍA INTERNA']);
        DB::table('fil__oficinas')->insert(['idfilial'=>1,'idunidad'=>2,'codoficina'=>'01','nomoficina'=>'SECCION FILIALES']);
        DB::table('fil__oficinas')->insert(['idfilial'=>1,'idunidad'=>3,'codoficina'=>'01','nomoficina'=>'DIRECCIÓN FINANCIERA']);
        DB::table('fil__oficinas')->insert(['idfilial'=>1,'idunidad'=>3,'codoficina'=>'02','nomoficina'=>'CONTABILIDAD']);
        DB::table('fil__oficinas')->insert(['idfilial'=>1,'idunidad'=>3,'codoficina'=>'03','nomoficina'=>'TESORERIA']);
        DB::table('fil__oficinas')->insert(['idfilial'=>1,'idunidad'=>3,'codoficina'=>'04','nomoficina'=>'AFILIACION']);
        DB::table('fil__oficinas')->insert(['idfilial'=>1,'idunidad'=>3,'codoficina'=>'05','nomoficina'=>'PRESTACIONES']);
        DB::table('fil__oficinas')->insert(['idfilial'=>1,'idunidad'=>3,'codoficina'=>'06','nomoficina'=>'COBRANZAS']);
        DB::table('fil__oficinas')->insert(['idfilial'=>1,'idunidad'=>3,'codoficina'=>'07','nomoficina'=>'DAARO']);
        DB::table('fil__oficinas')->insert(['idfilial'=>1,'idunidad'=>4,'codoficina'=>'01','nomoficina'=>'SECCIÓN ACTIVOS FIJOS']);
        DB::table('fil__oficinas')->insert(['idfilial'=>1,'idunidad'=>4,'codoficina'=>'02','nomoficina'=>'SECCIÓN INMUEBLES']);
        DB::table('fil__oficinas')->insert(['idfilial'=>1,'idunidad'=>4,'codoficina'=>'03','nomoficina'=>'ALMACENES']);
        DB::table('fil__oficinas')->insert(['idfilial'=>1,'idunidad'=>4,'codoficina'=>'04','nomoficina'=>'ASESORÍA TÉCNICA']);
        DB::table('fil__oficinas')->insert(['idfilial'=>1,'idunidad'=>5,'codoficina'=>'01','nomoficina'=>'MULTIFAMILIAR JUANCITO PINTO']);
        DB::table('fil__oficinas')->insert(['idfilial'=>1,'idunidad'=>5,'codoficina'=>'02','nomoficina'=>'CASA COMUNITARIA']);
        DB::table('fil__oficinas')->insert(['idfilial'=>1,'idunidad'=>5,'codoficina'=>'03','nomoficina'=>'CAMPOS DEPORTIVOS']);
        DB::table('fil__oficinas')->insert(['idfilial'=>1,'idunidad'=>5,'codoficina'=>'05','nomoficina'=>'SALONES ASCINALSS']);
        DB::table('fil__oficinas')->insert(['idfilial'=>1,'idunidad'=>6,'codoficina'=>'01','nomoficina'=>'DEPTO DE RECURSOS HUMANOS']);
        DB::table('fil__oficinas')->insert(['idfilial'=>1,'idunidad'=>6,'codoficina'=>'02','nomoficina'=>'CONSERJERÍAS']);
        DB::table('fil__oficinas')->insert(['idfilial'=>1,'idunidad'=>7,'codoficina'=>'01','nomoficina'=>'DEPTO DE RRPP']);
        DB::table('fil__oficinas')->insert(['idfilial'=>1,'idunidad'=>8,'codoficina'=>'01','nomoficina'=>'STRIA. PRENSA']);
        DB::table('fil__oficinas')->insert(['idfilial'=>1,'idunidad'=>9,'codoficina'=>'01','nomoficina'=>'ARCHIVO GENERAL']);
        DB::table('fil__oficinas')->insert(['idfilial'=>2,'idunidad'=>1,'codoficina'=>'01','nomoficina'=>'PRESIDENCIA']);
        DB::table('fil__oficinas')->insert(['idfilial'=>2,'idunidad'=>3,'codoficina'=>'02','nomoficina'=>'HACIENDA']);
        DB::table('fil__oficinas')->insert(['idfilial'=>3,'idunidad'=>1,'codoficina'=>'01','nomoficina'=>'PRESIDENCIA']);
        DB::table('fil__oficinas')->insert(['idfilial'=>3,'idunidad'=>3,'codoficina'=>'02','nomoficina'=>'HACIENDA']);
        DB::table('fil__oficinas')->insert(['idfilial'=>4,'idunidad'=>1,'codoficina'=>'01','nomoficina'=>'PRESIDENCIA']);
        DB::table('fil__oficinas')->insert(['idfilial'=>4,'idunidad'=>3,'codoficina'=>'02','nomoficina'=>'HACIENDA']);
        DB::table('fil__oficinas')->insert(['idfilial'=>5,'idunidad'=>1,'codoficina'=>'01','nomoficina'=>'PRESIDENCIA']);
        DB::table('fil__oficinas')->insert(['idfilial'=>5,'idunidad'=>3,'codoficina'=>'02','nomoficina'=>'HACIENDA']);        
    }
}
