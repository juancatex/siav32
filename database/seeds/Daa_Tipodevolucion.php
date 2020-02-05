<?php

use Illuminate\Database\Seeder;

class Daa_Tipodevolucion extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('daa__tipodevolucions')->insert(['nomtipodevolucion'=>'Cumplimiento','aporteminimo'=>'300','porcentaje'=>'1','valido'=>'1','activo'=>'1']);
        DB::table('daa__tipodevolucions')->insert(['nomtipodevolucion'=>'Jubilado','aporteminimo'=>'120','porcentaje'=>'0','valido'=>'1','activo'=>'1']);
        DB::table('daa__tipodevolucions')->insert(['nomtipodevolucion'=>'Cumplimiento Retiro','aporteminimo'=>'0','porcentaje'=>'1','valido'=>'0','activo'=>'1']);
        DB::table('daa__tipodevolucions')->insert(['nomtipodevolucion'=>'Cumplimiento Fallecido','aporteminimo'=>'0','porcentaje'=>'0','valido'=>'0','activo'=>'1']);
        DB::table('daa__tipodevolucions')->insert(['nomtipodevolucion'=>'Jubilacion Retiro','aporteminimo'=>'0','porcentaje'=>'1','valido'=>'0','activo'=>'1']);
        DB::table('daa__tipodevolucions')->insert(['nomtipodevolucion'=>'Jubilacion Fallecido','aporteminimo'=>'0','porcentaje'=>'0','valido'=>'0','activo'=>'1']);
    }
}
