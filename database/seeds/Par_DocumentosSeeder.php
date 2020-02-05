<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Par_DocumentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('par__documentos')->insert(['nomdocumento'=>'Carnet Militar','idmodulo'=>'1,4']);
        DB::table('par__documentos')->insert(['nomdocumento'=>'No propiedad DDRR','idmodulo'=>4]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Última Boleta de Pago','idmodulo'=>4]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Orden de Destino','idmodulo'=>4]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'C.I. Esposa','idmodulo'=>4]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Certificado de Defunción','idmodulo'=>4]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'C.I. del Responsable','idmodulo'=>4]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Cert. Nacim. Hijo 1','idmodulo'=>4]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Cert. Nacim. Hijo 2','idmodulo'=>4]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Cert. Nacim. Hijo 3','idmodulo'=>4]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Cert. Nacim. Hijo 4','idmodulo'=>4]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Formulario de Registro Personal','idmodulo'=>11]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Currículum Vitae','idmodulo'=>11]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Cédula de Identidad','idmodulo'=>'1,4,11']);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Certificado de Matrimonio','idmodulo'=>'4,11']);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Certificado de Nacimiento','idmodulo'=>'1,11']);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Título Profesional','idmodulo'=>11]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Certificación ASFI','idmodulo'=>11]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Antecedentes FELCC','idmodulo'=>11]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Formulario AVC-04 (CNS)','idmodulo'=>11]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Anexos','idmodulo'=>7]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Cheque','idmodulo'=>7]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Cheque de Gerencia','idmodulo'=>7]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Credinet','idmodulo'=>7]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Factura','idmodulo'=>7]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Recibo','idmodulo'=>7]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Boleta','idmodulo'=>7]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Informe','idmodulo'=>7]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Comprobante','idmodulo'=>7]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Abono en cuenta','idmodulo'=>7]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Solicitud de fondos','idmodulo'=>7]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Pedido','idmodulo'=>7]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Extractos Bancarios','idmodulo'=>7]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Conciliaciones Bancarias','idmodulo'=>7]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Giro Bancario','idmodulo'=>7]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Hoja de Ruta','idmodulo'=>7]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Baucher','idmodulo'=>7]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Uninet','idmodulo'=>7]);
        DB::table('par__documentos')->insert(['nomdocumento'=>'Nota de Servicio','idmodulo'=>7]);        
    }
}
