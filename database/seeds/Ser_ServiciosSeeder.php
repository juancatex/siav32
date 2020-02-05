<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Ser_ServiciosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ser__servicios')->insert(['codservicio'=>'VIV','nomservicio'=>'Vivienda','descripcion'=>'Bloques multifamiliares de departamentos']);
        DB::table('ser__servicios')->insert(['codservicio'=>'HPR','nomservicio'=>'Hospedaje Permanente']);
        DB::table('ser__servicios')->insert(['codservicio'=>'HTR','nomservicio'=>'Hospedaje Transitorio']);
        DB::table('ser__servicios')->insert(['codservicio'=>'MAU','nomservicio'=>'Mausoleo']);
        DB::table('ser__servicios')->insert(['codservicio'=>'PER','nomservicio'=>'Servicios Permanentes','descripcion'=>'Servicios de alquiler mensual bajo contrato']);
        DB::table('ser__servicios')->insert(['codservicio'=>'EVE','nomservicio'=>'Servicios Eventuales','descripcion'=>'Servicios según demanda por hora o jornada']);
    }
}

