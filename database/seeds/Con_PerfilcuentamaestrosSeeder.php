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
                
    }
}
