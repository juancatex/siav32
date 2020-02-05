<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Ser_EstablecimientosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ser__establecimientos')->insert(['idfilial'=>1,'idservicio'=>1,'nomestablecimiento'=>'MULTIFAMILIAR JUANCITO PINTO','direccion'=>'Calle Juancito Pinto','tipogrupos'=>1,'cantgrupos'=>5]);
        DB::table('ser__establecimientos')->insert(['idfilial'=>1,'idservicio'=>3,'nomestablecimiento'=>'HOTEL CASA COMUNITARIA','direccion'=>'Calle Diaz Romero','descripcion'=>'Hospedaje transitorio','tipogrupos'=>2,'cantgrupos'=>2]);
        DB::table('ser__establecimientos')->insert(['idfilial'=>1,'idservicio'=>6,'nomestablecimiento'=>'CANCHA SUCRE','direccion'=>'Calle Sucre',]);
        DB::table('ser__establecimientos')->insert(['idfilial'=>1,'idservicio'=>6,'nomestablecimiento'=>'COMPLEJO COTA COTA','direccion'=>'Calle 30 Cota Cota']);
        DB::table('ser__establecimientos')->insert(['idfilial'=>1,'idservicio'=>6,'nomestablecimiento'=>'SALON DORADO ASCINALSS','direccion'=>'Calle Diaz Romero']);
        DB::table('ser__establecimientos')->insert(['idfilial'=>1,'idservicio'=>6,'nomestablecimiento'=>'SALON DE BANDERAS ASCINALSS','direccion'=>'Calle Diaz Romero']);
        DB::table('ser__establecimientos')->insert(['idfilial'=>1,'idservicio'=>4,'nomestablecimiento'=>'MAUSOLEO NICHOS','direccion'=>'Cementerio General','tipogrupos'=>1,'cantgrupos'=>'0,514,514']);
        DB::table('ser__establecimientos')->insert(['idfilial'=>1,'idservicio'=>4,'nomestablecimiento'=>'MAUSOLEO SARCÓFAGOS','direccion'=>'Cementerio General','tipogrupos'=>1,'cantgrupos'=>'0,420,618,618,420,806']);
        DB::table('ser__establecimientos')->insert(['idfilial'=>1,'idservicio'=>4,'nomestablecimiento'=>'MAUSOLEO SECTOR FOSA','direccion'=>'Cementerio General','tipogrupos'=>1,'cantgrupos'=>'0,100']);
        DB::table('ser__establecimientos')->insert(['idfilial'=>2,'idservicio'=>2,'nomestablecimiento'=>'HOSPEDAJE PERMANENTE']);
        DB::table('ser__establecimientos')->insert(['idfilial'=>2,'idservicio'=>3,'nomestablecimiento'=>'HOSPEDAJE TRANSITORIO']);
        DB::table('ser__establecimientos')->insert(['idfilial'=>2,'idservicio'=>4,'nomestablecimiento'=>'MAUSOLEO NICHOS','direccion'=>'Cementerio General','tipogrupos'=>1,'cantgrupos'=>'0,100']);
        DB::table('ser__establecimientos')->insert(['idfilial'=>2,'idservicio'=>4,'nomestablecimiento'=>'MAUSOLEO SARCÓFAGOS','direccion'=>'Cementerio General','tipogrupos'=>1,'cantgrupos'=>'0,100']);
        DB::table('ser__establecimientos')->insert(['idfilial'=>2,'idservicio'=>4,'nomestablecimiento'=>'MAUSOLEO SECTOR FOSA','direccion'=>'Cementerio General','tipogrupos'=>1,'cantgrupos'=>'0,100']);
        DB::table('ser__establecimientos')->insert(['idfilial'=>2,'idservicio'=>5,'nomestablecimiento'=>'TIENDA AV PAPA PAULO']);
        DB::table('ser__establecimientos')->insert(['idfilial'=>3,'idservicio'=>2,'nomestablecimiento'=>'HOSPEDAJE TRANSITORIO']);
        DB::table('ser__establecimientos')->insert(['idfilial'=>3,'idservicio'=>6,'nomestablecimiento'=>'COMPLEJO DEPORTIVO']);
        DB::table('ser__establecimientos')->insert(['idfilial'=>3,'idservicio'=>4,'nomestablecimiento'=>'MAUSOLEO NICHOS','direccion'=>'Cementerio General','tipogrupos'=>1,'cantgrupos'=>'0,100']);
        DB::table('ser__establecimientos')->insert(['idfilial'=>3,'idservicio'=>4,'nomestablecimiento'=>'MAUSOLEO SARCÓFAGOS','direccion'=>'Cementerio General','tipogrupos'=>1,'cantgrupos'=>'0,100']);
        DB::table('ser__establecimientos')->insert(['idfilial'=>3,'idservicio'=>4,'nomestablecimiento'=>'MAUSOLEO SECTOR FOSA','direccion'=>'Cementerio General','tipogrupos'=>1,'cantgrupos'=>'0,100']);
    }
}
