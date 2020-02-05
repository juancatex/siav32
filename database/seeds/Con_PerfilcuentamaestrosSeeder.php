<?php

use Illuminate\Database\Seeder;

class Con_PerfilcuentamaestrosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('con__perfilcuentamaestros')->insert([
            'nomperfil'=>'Aportes Socios Ascii',
            'descripcion'=>'Canal para registrar carga masiva Ascii',
            'idtipocomprobante'=>'1',
            'completo'=>'1',
            'idmodulo'=>'2'
        ]);

        DB::table('con__perfilcuentamaestros')->insert([
            'nomperfil'=>'Aportes Socios regulares',
            'descripcion'=>'Canal para registrar aportes regulares',
            'idtipocomprobante'=>'1',
            'completo'=>'1',
            'idmodulo'=>'2'
        ]);

        DB::table('con__perfilcuentamaestros')->insert([
            'nomperfil'=>'Aportes Socios regularizacion',
            'descripcion'=>'Canal para registrar aportes regularizacion',
            'idtipocomprobante'=>'1',
            'completo'=>'1',
            'idmodulo'=>'2'
            
        ]);
        DB::table('con__perfilcuentamaestros')->insert([
            'nomperfil'=>'Desembolso Prestamos Regulares',
            'descripcion'=>'Desembolso Prestamos Regulares',
            'idtipocomprobante'=>'2',
            'completo'=>'1',
            'siporcentaje'=>'0',
            'idmodulo'=>'3'
            
        ]);
        DB::table('con__perfilcuentamaestros')->insert([
            'nomperfil'=>'Desembolso Prestamos Emergencia',
            'descripcion'=>'Desembolso Prestamos Emergencia',
            'idtipocomprobante'=>'2',
            'completo'=>'1',
            'siporcentaje'=>'0',
            'idmodulo'=>'3' 
        ]);
        DB::table('con__perfilcuentamaestros')->insert([
            'nomperfil'=>'Cobranza Prestamos Emergencia',
            'descripcion'=>'Cobranza Prestamos Emergencia',
            'idtipocomprobante'=>'1',
            'completo'=>'1',
            'siporcentaje'=>'0',
            'idmodulo'=>'3' 
        ]);
        DB::table('con__perfilcuentamaestros')->insert([
            'nomperfil'=>'Cobranza Prestamos Regulares',
            'descripcion'=>'Cobranza Prestamos Regulares',
            'idtipocomprobante'=>'1',
            'completo'=>'1',
            'siporcentaje'=>'0',
            'idmodulo'=>'3' 
        ]);
        DB::table('con__perfilcuentamaestros')->insert([
            'nomperfil'=>'Desembolso por servicios',
            'descripcion'=>'Desembolso por servicios',
            'idtipocomprobante'=>'2',
            'completo'=>'1',
            'idmodulo'=>'3' 
        ]);
        DB::table('con__perfilcuentamaestros')->insert([
            'nomperfil'=>'Cobranza por servicios',
            'descripcion'=>'Cobranza por servicios',
            'idtipocomprobante'=>'1',
            'completo'=>'1',
            'idmodulo'=>'3' 
        ]);                
    }
}
