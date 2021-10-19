<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        DB::table('con__configuracions')->insert(['codigo'=>'LV','descripcion'=>'Relacion Libro de Ventas - id Cuenta Debito Fiscal','valor'=>'108','tipoconfiguracion'=>1,'descripcion2'=>'porcentaje','valor2'=>'13']);
        DB::table('con__configuracions')->insert(['codigo'=>'LC','descripcion'=>'Relacion Libro de Compras - id Cuenta Credito Fiscal','valor'=>'23','tipoconfiguracion'=>2,'descripcion2'=>'porcentaje','valor2'=>'13']);
        DB::table('con__configuracions')->insert(['codigo'=>'LB','descripcion'=>'Relacion Libreta bancaria - id  Cuenta bancos','valor'=>'3','tipoconfiguracion'=>3]);
        DB::table('con__configuracions')->insert(['codigo'=>'LB','descripcion'=>'Relacion Libreta bancaria - id  Cuenta bancos','valor'=>'4','tipoconfiguracion'=>3]);
        DB::table('con__configuracions')->insert(['codigo'=>'LB','descripcion'=>'Relacion Libreta bancaria - id  Cuenta bancos','valor'=>'5','tipoconfiguracion'=>3]);
        DB::table('con__configuracions')->insert(['codigo'=>'CC','descripcion'=>'Cuenta cargo de cuenta para asiento contable','valor'=>'21','tipoconfiguracion'=>4]);
        DB::table('con__configuracions')->insert(['codigo'=>'EJD','descripcion'=>'aportes de que estan relacionadas con el ejercito y la cuenta contable que van al debe','valor'=>'16','tipoconfiguracion'=>5]);
        DB::table('con__configuracions')->insert(['codigo'=>'AED','descripcion'=>'aportes de que estan relacionadas con la fuerza aerea y la cuenta contable que van al debe','valor'=>'17','tipoconfiguracion'=>5]);
        DB::table('con__configuracions')->insert(['codigo'=>'ARD','descripcion'=>'aportes de que estan relacionadas con la armada y la cuenta contable que van al debe','valor'=>'18','tipoconfiguracion'=>5]);
        DB::table('con__configuracions')->insert(['codigo'=>'EJH','descripcion'=>'aportes de que estan relacionadas con el ejercito y la cuenta contable que van al haber','valor'=>'136','tipoconfiguracion'=>5]);
        DB::table('con__configuracions')->insert(['codigo'=>'AEH','descripcion'=>'aportes de que estan relacionadas con la fuerza aerea y la cuenta contable que van al haber','valor'=>'137','tipoconfiguracion'=>5]);
        DB::table('con__configuracions')->insert(['codigo'=>'ARH','descripcion'=>'aportes de que estan relacionadas con la armada y la cuenta contable que van al haber','valor'=>'138','tipoconfiguracion'=>5]);
        DB::table('con__configuracions')->insert(['codigo'=>'SubASC','descripcion'=>'Ascinalss','valor'=>'20000000','tipoconfiguracion'=>6]);
        DB::table('con__configuracions')->insert(['codigo'=>'ccdiasor','descripcion'=>'Cantidad de dias oficina regional','valor'=>'20','tipoconfiguracion'=>4]);
        DB::table('con__configuracions')->insert(['codigo'=>'ccdiasoc','descripcion'=>'Cantidad de dias oficina central','valor'=>'5','tipoconfiguracion'=>4]);
    }
}
