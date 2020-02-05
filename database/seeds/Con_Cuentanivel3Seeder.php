<?php

use Illuminate\Database\Seeder;

class Con_Cuentanivel3Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('con__cuentanivel3')->insert(['codn2'=>'11','codn3'=>'111','valorn3'=>'1','nomcuentan3'=>'Disponibilidades']);
        DB::table('con__cuentanivel3')->insert(['codn2'=>'11','codn3'=>'112','valorn3'=>'2','nomcuentan3'=>'Inversiones Temporarias']);
        DB::table('con__cuentanivel3')->insert(['codn2'=>'11','codn3'=>'113','valorn3'=>'3','nomcuentan3'=>'Cartera - Prestamos de Emergencia']);
        DB::table('con__cuentanivel3')->insert(['codn2'=>'11','codn3'=>'114','valorn3'=>'4','nomcuentan3'=>'Aportes por cobrar']);
        DB::table('con__cuentanivel3')->insert(['codn2'=>'11','codn3'=>'115','valorn3'=>'5','nomcuentan3'=>'Cuentas por cobrar']);
        DB::table('con__cuentanivel3')->insert(['codn2'=>'11','codn3'=>'116','valorn3'=>'6','nomcuentan3'=>'Bienes Realizables']);
        
        DB::table('con__cuentanivel3')->insert(['codn2'=>'12','codn3'=>'121','valorn3'=>'1','nomcuentan3'=>'Cartera - Prestamos Regulares']);
        DB::table('con__cuentanivel3')->insert(['codn2'=>'12','codn3'=>'122','valorn3'=>'2','nomcuentan3'=>'Bienes de Uso']);
        DB::table('con__cuentanivel3')->insert(['codn2'=>'12','codn3'=>'123','valorn3'=>'3','nomcuentan3'=>'Inversiones Financieras a largo plazo']);
        DB::table('con__cuentanivel3')->insert(['codn2'=>'12','codn3'=>'124','valorn3'=>'4','nomcuentan3'=>'Acciones judiciales']);
        DB::table('con__cuentanivel3')->insert(['codn2'=>'12','codn3'=>'125','valorn3'=>'5','nomcuentan3'=>'Activos intangibles']);
        DB::table('con__cuentanivel3')->insert(['codn2'=>'12','codn3'=>'126','valorn3'=>'6','nomcuentan3'=>'Cuentas incobrables']);
        
        DB::table('con__cuentanivel3')->insert(['codn2'=>'21','codn3'=>'211','valorn3'=>'1','nomcuentan3'=>'Obligaciones a corto plazo']);

        DB::table('con__cuentanivel3')->insert(['codn2'=>'22','codn3'=>'221','valorn3'=>'1','nomcuentan3'=>'Obligaciones a largo plazo']);
        
        DB::table('con__cuentanivel3')->insert(['codn2'=>'31','codn3'=>'311','valorn3'=>'1','nomcuentan3'=>'Capital']);
        DB::table('con__cuentanivel3')->insert(['codn2'=>'31','codn3'=>'312','valorn3'=>'2','nomcuentan3'=>'Reservas']);
        DB::table('con__cuentanivel3')->insert(['codn2'=>'31','codn3'=>'313','valorn3'=>'3','nomcuentan3'=>'Resultados']);
       
       
       
        DB::table('con__cuentanivel3')->insert(['codn2'=>'51','codn3'=>'511','valorn3'=>'1','nomcuentan3'=>'Ingresos por Prestaciones']);
        
        DB::table('con__cuentanivel3')->insert(['codn2'=>'52','codn3'=>'521','valorn3'=>'1','nomcuentan3'=>'Ingresos Operativos']);
        
        DB::table('con__cuentanivel3')->insert(['codn2'=>'53','codn3'=>'531','valorn3'=>'1','nomcuentan3'=>'Ingresos no Operativos']);
        DB::table('con__cuentanivel3')->insert(['codn2'=>'53','codn3'=>'532','valorn3'=>'2','nomcuentan3'=>'Otros ingresos']);





        
    }
}
