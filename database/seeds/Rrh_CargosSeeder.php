<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Rrh_CargosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        DB::table('rrh__cargos')->insert(['idoficina'=>1,'nomcargo'=>'Asesor Jurídico']);
        DB::table('rrh__cargos')->insert(['idoficina'=>1,'nomcargo'=>'Auditor']);
        DB::table('rrh__cargos')->insert(['idoficina'=>1,'nomcargo'=>'Contador General']);
        DB::table('rrh__cargos')->insert(['idoficina'=>1,'nomcargo'=>'Responsable de Activos Fijos']);
        DB::table('rrh__cargos')->insert(['idoficina'=>1,'nomcargo'=>'Responsable de Afiliaciones']);
        DB::table('rrh__cargos')->insert(['idoficina'=>1,'nomcargo'=>'Responsable de Almacenes']);
        DB::table('rrh__cargos')->insert(['idoficina'=>1,'nomcargo'=>'Responsable de Archivo']);
        DB::table('rrh__cargos')->insert(['idoficina'=>1,'nomcargo'=>'Responsable de Cobranzas']);
        DB::table('rrh__cargos')->insert(['idoficina'=>1,'nomcargo'=>'Responsable de DAARO']);
        DB::table('rrh__cargos')->insert(['idoficina'=>1,'nomcargo'=>'Responsable de Filiales']);
        DB::table('rrh__cargos')->insert(['idoficina'=>1,'nomcargo'=>'Responsable de Inmuebles']);
        DB::table('rrh__cargos')->insert(['idoficina'=>1,'nomcargo'=>'Responsable de Préstamos de Emergencia']);
        DB::table('rrh__cargos')->insert(['idoficina'=>1,'nomcargo'=>'Responsable de Préstamos Regulares']);        
        DB::table('rrh__cargos')->insert(['idoficina'=>1,'nomcargo'=>'Responsable de Recursos Humanos']);
        DB::table('rrh__cargos')->insert(['idoficina'=>1,'nomcargo'=>'Responsable de Servicios']);
        DB::table('rrh__cargos')->insert(['idoficina'=>1,'nomcargo'=>'Responsable de Sistemas']);
        DB::table('rrh__cargos')->insert(['idoficina'=>1,'nomcargo'=>'Responsable de Tesorería']);
        DB::table('rrh__cargos')->insert(['idoficina'=>1,'nomcargo'=>'Recepcionista Casa Comunitaria']);
        DB::table('rrh__cargos')->insert(['idoficina'=>1,'nomcargo'=>'Auxiliar Contable']);
        DB::table('rrh__cargos')->insert(['idoficina'=>1,'nomcargo'=>'Analista Desarrollador']);
        DB::table('rrh__cargos')->insert(['idoficina'=>1,'nomcargo'=>'Procurador']);
        DB::table('rrh__cargos')->insert(['idoficina'=>1,'nomcargo'=>'Conserje Oficina Central']);
        DB::table('rrh__cargos')->insert(['idoficina'=>1,'nomcargo'=>'Conserje Cancha Sucre']);
        DB::table('rrh__cargos')->insert(['idoficina'=>1,'nomcargo'=>'Conserje Cancha Cota Cota']);
        DB::table('rrh__cargos')->insert(['idoficina'=>1,'nomcargo'=>'Mantenimiento y refacción']);        
        DB::table('rrh__cargos')->insert(['idoficina'=>1,'nomcargo'=>'Mucama Casa Comunitaria']);
        DB::table('rrh__cargos')->insert(['idoficina'=>1,'nomcargo'=>'Secretaria']);
        DB::table('rrh__cargos')->insert(['idoficina'=>1,'nomcargo'=>'Conserje Filial Oruro']);
    }

}

