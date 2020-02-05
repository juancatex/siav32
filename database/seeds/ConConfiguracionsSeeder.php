<?php

use Illuminate\Database\Seeder;

class ConConfiguracionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('con__configuracions')->insert(['codigo'=>'LV','descripcion'=>'Relacion Libro de Ventas - id Cuenta Debito Fiscal','valor'=>'226','tipoconfiguracion'=>1]);
        DB::table('con__configuracions')->insert(['codigo'=>'LC','descripcion'=>'Relacion Libro de Compras - id Cuenta Credito Fiscal','valor'=>'54','tipoconfiguracion'=>2]);
        DB::table('con__configuracions')->insert(['codigo'=>'LB','descripcion'=>'Relacion Libreta bancaria - id  Cuenta bancos','valor'=>'7','tipoconfiguracion'=>3]);
        DB::table('con__configuracions')->insert(['codigo'=>'CC','descripcion'=>'Cuenta cargo de cuenta para asiento contable','valor'=>'99','tipoconfiguracion'=>4]);
        
    }
}
