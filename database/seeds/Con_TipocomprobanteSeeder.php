<?php

use Illuminate\Database\Seeder;

class Con_TipocomprobanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('con__tipocomprobantes')->insert([
            'nomtipocomprobante'=>'Ingreso',
            'descripcion'=>'ingreso',
            'prefijo'=>'I',

        ]);

        DB::table('con__tipocomprobantes')->insert([
            'nomtipocomprobante'=>'Egreso',
            'descripcion'=>'egreso',
            'prefijo'=>'E',
        ]);
        
        DB::table('con__tipocomprobantes')->insert([
            'nomtipocomprobante'=>'Traspaso',
            'descripcion'=>'Traspaso',
            'prefijo'=>'T',
        ]);
        DB::table('con__tipocomprobantes')->insert([
            'nomtipocomprobante'=>'Apertura',
            'descripcion'=>'Para registrar la apertura de gestion',
            'prefijo'=>'A',
        ]);
    }
}
