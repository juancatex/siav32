<?php

use Illuminate\Database\Seeder;

class Alm_ProveedoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alm__proveedors')->insert(['nomproveedor'=>'comunicaciones el pais s.a.','nit'=>'1020241028','direccion'=>'xxxx','telefono'=>'12345678']);
        DB::table('alm__proveedors')->insert(['nomproveedor'=>'EE.SS. siempre a full miraflores s.r.l.','nit'=>'1020285026','direccion'=>'xxxx','telefono'=>'12345679']);
        DB::table('alm__proveedors')->insert(['nomproveedor'=>'maritza blanco febrero','nit'=>'6919243012','direccion'=>'xxxx','telefono'=>'12345670']);
        DB::table('alm__proveedors')->insert(['nomproveedor'=>'owen srl','nit'=>'241934024','direccion'=>'xxxx','telefono'=>'12345610']);
        DB::table('alm__proveedors')->insert(['nomproveedor'=>'ana chambilla mancilla','nit'=>'4330564017','direccion'=>'xxxx','telefono'=>'12345611']);
        DB::table('alm__proveedors')->insert(['nomproveedor'=>'embotelladoras bolivianas unidas s.a.','nit'=>'1007039026','direccion'=>'xxxx','telefono'=>'12345612']);
        DB::table('alm__proveedors')->insert(['nomproveedor'=>'JOTA IMPRENTA – OFFSET – GRABADOS','nomcontacto'=>'JUAN JOSE CHOQUE E.','nit'=>'1008459026','direccion'=>'xxxx','telefono'=>'2202275']);
        DB::table('alm__proveedors')->insert(['nomproveedor'=>'IMPRENTA VELOZ','nomcontacto'=>'WILFLOR VEGA VELEZ','nit'=>'10048659026','direccion'=>'xxxx','telefono'=>'2202917']);
        DB::table('alm__proveedors')->insert(['nomproveedor'=>'Punto & Imagen','nomcontacto'=>'EDHIT RINA VELASCO CUBA','nit'=>'10048659026','direccion'=>'xxxx','telefono'=>'70505642']);
        DB::table('alm__proveedors')->insert(['nomproveedor'=>'CASA JACK','nomcontacto'=>'Juan Jose Mendoza Mamani','nit'=>'10048659026','direccion'=>'xxxx','telefono'=>'2457875']);
        DB::table('alm__proveedors')->insert(['nomproveedor'=>'N&P IMPRESORES DISTRIBUIDORA DE PAPEL','nomcontacto'=>'MARIO MACUCHAPI CHIPANA','nit'=>'10048659026','direccion'=>'xxxx','telefono'=>'71981472']);
    }
}
