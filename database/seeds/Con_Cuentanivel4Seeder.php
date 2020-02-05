<?php

use Illuminate\Database\Seeder;

class Con_Cuentanivel4Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('con__cuentanivel4')->insert(['codn3'=>'111','codn4'=>'1111', 'valorn4'=>'1','nomcuentan4'=>'Cajas']);
        DB::table('con__cuentanivel4')->insert(['codn3'=>'111','codn4'=>'1112', 'valorn4'=>'2','nomcuentan4'=>'Bancos']);
        
        DB::table('con__cuentanivel4')->insert(['codn3'=>'112','codn4'=>'1121', 'valorn4'=>'1','nomcuentan4'=>'Inversiones Temporarias']);

        DB::table('con__cuentanivel4')->insert(['codn3'=>'113','codn4'=>'1131', 'valorn4'=>'1','nomcuentan4'=>'Cartera Vigente']);
        DB::table('con__cuentanivel4')->insert(['codn3'=>'113','codn4'=>'1132', 'valorn4'=>'2','nomcuentan4'=>'Cartera Vencida']);
        DB::table('con__cuentanivel4')->insert(['codn3'=>'113','codn4'=>'1133', 'valorn4'=>'3','nomcuentan4'=>'Productos Devengados']);
        DB::table('con__cuentanivel4')->insert(['codn3'=>'113','codn4'=>'1134', 'valorn4'=>'4','nomcuentan4'=>'Prevision para Incobrabilidad de Cartera']);

        DB::table('con__cuentanivel4')->insert(['codn3'=>'114','codn4'=>'1141', 'valorn4'=>'1','nomcuentan4'=>'Aportes por Cobrar']);
        
        DB::table('con__cuentanivel4')->insert(['codn3'=>'115','codn4'=>'1151', 'valorn4'=>'1','nomcuentan4'=>'Cuentas por cobrar Institucionales']);

        DB::table('con__cuentanivel4')->insert(['codn3'=>'116','codn4'=>'1161', 'valorn4'=>'1','nomcuentan4'=>'Bienes Adquiridos']);
        
        DB::table('con__cuentanivel4')->insert(['codn3'=>'121','codn4'=>'1211', 'valorn4'=>'1','nomcuentan4'=>'Cartera Vigente']);
        DB::table('con__cuentanivel4')->insert(['codn3'=>'121','codn4'=>'1212','valorn4'=>'2','nomcuentan4'=>'Prevision para Incobrabilidad de Cartera']);
        DB::table('con__cuentanivel4')->insert(['codn3'=>'121','codn4'=>'1213','valorn4'=>'3','nomcuentan4'=>'Cuentas transitorias']);

        DB::table('con__cuentanivel4')->insert(['codn3'=>'122','codn4'=>'1221','valorn4'=>'1','nomcuentan4'=>'Bienes de uso y Terrenos']);
        
        DB::table('con__cuentanivel4')->insert(['codn3'=>'123','codn4'=>'1231','valorn4'=>'1','nomcuentan4'=>'Acciones de Participaciones de capital']);
        
        DB::table('con__cuentanivel4')->insert(['codn3'=>'124','codn4'=>'1241','valorn4'=>'1','nomcuentan4'=>'Acciones judiciales']);
        
        DB::table('con__cuentanivel4')->insert(['codn3'=>'125','codn4'=>'1251','valorn4'=>'1','nomcuentan4'=>'Programas y aplicaciones informaticas']);
        
        DB::table('con__cuentanivel4')->insert(['codn3'=>'126','codn4'=>'1261','valorn4'=>'1','nomcuentan4'=>'Cuentas incobrables']);
        
        DB::table('con__cuentanivel4')->insert(['codn3'=>'211','codn4'=>'2111','valorn4'=>'1','nomcuentan4'=>'obligaciones laborales y patronales por pagar']);
        DB::table('con__cuentanivel4')->insert(['codn3'=>'211','codn4'=>'2112','valorn4'=>'2','nomcuentan4'=>'obligaciones tributarias']);
        DB::table('con__cuentanivel4')->insert(['codn3'=>'211','codn4'=>'2113', 'valorn4'=>'3','nomcuentan4'=>'obligaciones con instituciones no financieras']);
        DB::table('con__cuentanivel4')->insert(['codn3'=>'211','codn4'=>'2114', 'valorn4'=>'4','nomcuentan4'=>'obligaciones con los socios']);
        DB::table('con__cuentanivel4')->insert(['codn3'=>'211','codn4'=>'2115','valorn4'=>'5','nomcuentan4'=>'garantias por pagar']);
        DB::table('con__cuentanivel4')->insert(['codn3'=>'211','codn4'=>'2116','valorn4'=>'6','nomcuentan4'=>'depositos no identificados']);

        DB::table('con__cuentanivel4')->insert(['codn3'=>'221','codn4'=>'2211','valorn4'=>'1','nomcuentan4'=>'aportes por pagar no liquidados']);
        DB::table('con__cuentanivel4')->insert(['codn3'=>'221','codn4'=>'2212','valorn4'=>'2','nomcuentan4'=>'reservas']);

        DB::table('con__cuentanivel4')->insert(['codn3'=>'311','codn4'=>'3111','valorn4'=>'1','nomcuentan4'=>'capital']);

        DB::table('con__cuentanivel4')->insert(['codn3'=>'312','codn4'=>'3121','valorn4'=>'1','nomcuentan4'=>'reservas']);

        DB::table('con__cuentanivel4')->insert(['codn3'=>'313','codn4'=>'3131','valorn4'=>'1','nomcuentan4'=>'resultados']);

        DB::table('con__cuentanivel4')->insert(['codn3'=>'511','codn4'=>'5111','valorn4'=>'1','nomcuentan4'=>'Ingresos por prestaciones']);

        DB::table('con__cuentanivel4')->insert(['codn3'=>'521','codn4'=>'5211','valorn4'=>'1','nomcuentan4'=>'Ingresos Oficina central']);
        DB::table('con__cuentanivel4')->insert(['codn3'=>'521','codn4'=>'5212','valorn4'=>'2','nomcuentan4'=>'Ingresos de filiales']);

        DB::table('con__cuentanivel4')->insert(['codn3'=>'531','codn4'=>'5311','valorn4'=>'1','nomcuentan4'=>'ingresos no operativos']);

        DB::table('con__cuentanivel4')->insert(['codn3'=>'532','codn4'=>'5321','valorn4'=>'1','nomcuentan4'=>'otros ingresos']);
    }


}

