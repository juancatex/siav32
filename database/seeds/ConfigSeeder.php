<?php

use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('configs')->insert(['codigo'=>'AO','descripcion'=>'Aportes Obligados','valor'=>'300']);
        DB::table('configs')->insert(['codigo'=>'AJ','descripcion'=>'Aportes por Jubilacion','valor'=>'120']);
        DB::table('configs')->insert(['codigo'=>'LOTE','descripcion'=>'Cantidad maxima de prestamos por lote','valor'=>'10']);
        DB::table('configs')->insert(['codigo'=>'MORA','descripcion'=>'Dias minimo para ejecutar a garantes (mora)','valor'=>'31']);

        DB::table('configs')->insert(['codigo'=>'SM','descripcion'=>'Saldos menores en bolivianos menor o igual a','valor'=>'10']);
        DB::table('configs')->insert(['codigo'=>'SA','descripcion'=>'Saldos acreeedor en bolivianos menor o igual a','valor'=>'20']);

        DB::table('configs')->insert(['codigo'=>'COA','descripcion'=>'Columnas excluidas para carga Aportes','fileColumns'=>'[{"file":"GESTION","db":"gestion"},{"file":"DOCUMENTO_RESPALDO","db":"codaporte"},{"file":"EIT_CODORG","db":"codfuerza"},{"file":"ORGANISMOS","db":"fuerza"},{"file":"EIT_CODREP","db":"coddestino"},{"file":"REPARTICION","db":"destino"},{"file":"IDENTIFICADOR_ACREEDOR","db":"codaportedestino"},{"file":"ACREEDOR","db":"descaporte"},{"file":"CTA_BANCARIA_ACREEDOR","db":"cuentaasscinals"},{"file":"CODIGO_PERSONAL","db":"numpapeleta"},{"file":"CARNET","db":"cisocio"},{"file":"GRADO","db":"grado"},{"file":"MENSION","db":"especialidad"},{"file":"NOMBRES","db":"nombres"},{"file":"MONTO_DESCUENTO","db":"aporte"},{"file":"TOT_2","db":"aporte2"},{"file":"COMISION","db":"comision"}]']);
        DB::table('configs')->insert(['codigo'=>'COP','descripcion'=>'Columnas excluidas para carga Prestamos','fileColumns'=>'[{"file":"GESTION","db":"gestion"},{"file":"DOCUMENTO_RESPALDO","db":"codaporte"},{"file":"EIT_CODORG","db":"codfuerza"},{"file":"ORGANISMOS","db":"fuerza"},{"file":"EIT_CODREP","db":"coddestino"},{"file":"REPARTICION","db":"destino"},{"file":"IDENTIFICADOR_ACREEDOR","db":"codaportedestino"},{"file":"ACREEDOR","db":"descaporte"},{"file":"CTA_BANCARIA_ACREEDOR","db":"cuentaasscinals"},{"file":"CODIGO_PERSONAL","db":"numpapeleta"},{"file":"CARNET","db":"cisocio"},{"file":"GRADO","db":"grado"},{"file":"MENSION","db":"especialidad"},{"file":"NOMBRES","db":"nombres"},{"file":"MONTO_DESCUENTO","db":"aporte"},{"file":"TOT_2","db":"aporte2"},{"file":"COMISION","db":"comision"}]']);
        

    }
}
