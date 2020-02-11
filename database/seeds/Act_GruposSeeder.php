<?php

use Illuminate\Database\Seeder;

class Act_GruposSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('act__grupos')->insert(['codgrupo'=>'01','nomgrupo'=>'EDIFICACIONES','vida'=>40,'activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'02','nomgrupo'=>'MUEBLES Y ENSERES DE OFICINA','vida'=>10]);
        DB::table('act__grupos')->insert(['codgrupo'=>'03','nomgrupo'=>'MAQUINARIA EN GENERAL','activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'04','nomgrupo'=>'EQUIPO MEDICO Y DE LABORATORIO','activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'05','nomgrupo'=>'EQUIPO DE COMUNICACIONES','vida'=>8]);
        DB::table('act__grupos')->insert(['codgrupo'=>'06','nomgrupo'=>'EQUIPO EDUCACIONAL Y RECREATIVO','vida'=>8]);
        DB::table('act__grupos')->insert(['codgrupo'=>'07','nomgrupo'=>'BARCOS Y LANCHAS EN GENERAL','activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'08','nomgrupo'=>'VEHICULOS AUTOMOTORES','vida'=>5,'activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'09','nomgrupo'=>'AVIONES','vida'=>5,'activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'10','nomgrupo'=>'MAQUINARIA PARA LA CONSTRUCCION','activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'11','nomgrupo'=>'MAQUINARIA AGRICOLA','activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'12','nomgrupo'=>'ANIMALES DE TRABAJO','activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'13','nomgrupo'=>'HERRAMIENTAS EN GENERAL','vida'=>4]);
        DB::table('act__grupos')->insert(['codgrupo'=>'14','nomgrupo'=>'REPRODUCTORES Y HEMBRAS DE PEDIGREE','activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'15','nomgrupo'=>'EQUIPOS DE COMPUTACION','vida'=>4]);
        DB::table('act__grupos')->insert(['codgrupo'=>'16','nomgrupo'=>'CANALES DE REGADIO Y POZOS','activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'17','nomgrupo'=>'ESTANQUES, BAÑADEROS Y ABREVADEROS','activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'18','nomgrupo'=>'ALAMBRADOS, TRANQUERAS Y VALLAS','activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'19','nomgrupo'=>'VIVIENDAS PARA EL PERSONAL','activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'20','nomgrupo'=>'MUEBLES Y ENSERES EN VIVIENDAS DE PERSONAL','vida'=>10]);
        DB::table('act__grupos')->insert(['codgrupo'=>'21','nomgrupo'=>'SILOS, ALMACENES Y GALPONES','activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'22','nomgrupo'=>'TINGLADOS Y COBERTIZOS DE MADERA','activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'23','nomgrupo'=>'TINGLADOS Y COBERTIZOS DE METAL','activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'24','nomgrupo'=>'INSTALACION DE ELECTRIFICACION Y TELEFONIA RURAL','activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'25','nomgrupo'=>'CAMINOS INTERIORES','activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'26','nomgrupo'=>'CAÑA DE AZUCAR','activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'27','nomgrupo'=>'VIDES','activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'28','nomgrupo'=>'FRUTALES','activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'29','nomgrupo'=>'LINEAS DE RECOLECCION DE LA INDUSTRIA PETROLERA','activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'30','nomgrupo'=>'POZOS PETROLEROS','activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'31','nomgrupo'=>'EQUIPOS DE CAMPO DE LA INDUSTRIA PETROLERA','activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'32','nomgrupo'=>'PLANTA DE PROCESAMIENTO DE LA INDUSTRIA PETROLERA','activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'33','nomgrupo'=>'DUCTOS DE LA INDUSTRIA PETROLERA','activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'34','nomgrupo'=>'TERRENOS','vida'=>40,'activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'36','nomgrupo'=>'OTROS ACTIVOS FIJOS','activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'37','nomgrupo'=>'ACTIVOS INTANGIBLES','activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'38','nomgrupo'=>'EQUIPO E INSTALACIONES','activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'39','nomgrupo'=>'OTRAS PLANTACIONES','activo'=>0]);
        DB::table('act__grupos')->insert(['codgrupo'=>'40','nomgrupo'=>'ACTIVOS MUSEOLOGICOS Y CULTURALES','activo'=>0]);        
    }
}
