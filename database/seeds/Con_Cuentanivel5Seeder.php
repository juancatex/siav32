<?php

use Illuminate\Database\Seeder;

class Con_Cuentanivel5Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('con__cuentanivel5')->insert(['codn4'=>'1111', 'codn5'=>'11111','valorn5'=>'1','nomcuentan5'=>'Caja M/N']);
        
        DB::table('con__cuentanivel5')->insert(['codn4'=>'1112', 'codn5'=>'11121','valorn5'=>'1','nomcuentan5'=>'Banco M/N']);
        
        DB::table('con__cuentanivel5')->insert(['codn4'=>'1121', 'codn5'=>'11211','valorn5'=>'1','nomcuentan5'=>'INVERSIONES M/N']);

        DB::table('con__cuentanivel5')->insert(['codn4'=>'1131', 'codn5'=>'11311','valorn5'=>'1','nomcuentan5'=>'CARTERA VIGENTE']);
        
        DB::table('con__cuentanivel5')->insert(['codn4'=>'1132', 'codn5'=>'11321','valorn5'=>'1','nomcuentan5'=>'CARTERA VENCIDA']);
        
        DB::table('con__cuentanivel5')->insert(['codn4'=>'1133', 'codn5'=>'11331','valorn5'=>'1','nomcuentan5'=>'PRODUCTOS DEVENGADOS POR COBRAR']);
        
        DB::table('con__cuentanivel5')->insert(['codn4'=>'1134', 'codn5'=>'11341','valorn5'=>'1','nomcuentan5'=>'PREVISIÓN PARA INCOBRABILIDAD DE CARTERA']);

        DB::table('con__cuentanivel5')->insert(['codn4'=>'1141', 'codn5'=>'11411','valorn5'=>'1','nomcuentan5'=>'APORTES POR COBRAR']);
        
        DB::table('con__cuentanivel5')->insert(['codn4'=>'1151', 'codn5'=>'11511','valorn5'=>'1','nomcuentan5'=>'CUENTAS POR COBRAR INSTITUCIONALES']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'1151', 'codn5'=>'11512','valorn5'=>'2','nomcuentan5'=>'DIVERSAS']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'1151', 'codn5'=>'11513','valorn5'=>'3','nomcuentan5'=>'POR DESCUENTO VIA MINISTERIO']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'1151','codn5'=>'11514','valorn5'=>'4','nomcuentan5'=>'CUENTAS INCOBRABLES']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'1151','codn5'=>'11515','valorn5'=>'5','nomcuentan5'=>'ANTICIPOS']);

        DB::table('con__cuentanivel5')->insert(['codn4'=>'1161','codn5'=>'11611','valorn5'=>'1','nomcuentan5'=>'INVENTARIO ALMACENES']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'1161','codn5'=>'11612','valorn5'=>'2','nomcuentan5'=>'INVENTARIO LINEA BLANCA']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'1161','codn5'=>'11613','valorn5'=>'3','nomcuentan5'=>'PREVISIÓN PARA DESVALORIZACIÓN']);
        
        DB::table('con__cuentanivel5')->insert(['codn4'=>'1211','codn5'=>'12111','valorn5'=>'1','nomcuentan5'=>'CARTERA VIGENTE']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'1211','codn5'=>'12112','valorn5'=>'2','nomcuentan5'=>'CARTERA VENCIDA']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'1211','codn5'=>'12113','valorn5'=>'3','nomcuentan5'=>'CARTERA REPROGRAMADA O REESTRUCTURADA VIGENTE']);

        DB::table('con__cuentanivel5')->insert(['codn4'=>'1212','codn5'=>'12121','valorn5'=>'1','nomcuentan5'=>'PREVISIÓN PARA INCOBRABILIDAD DE CARTERA']);

        DB::table('con__cuentanivel5')->insert(['codn4'=>'1213', 'codn5'=>'12131','valorn5'=>'1','nomcuentan5'=>'CUENTAS TRANSITORIAS']);

        DB::table('con__cuentanivel5')->insert(['codn4'=>'1221', 'codn5'=>'12211','valorn5'=>'1','nomcuentan5'=>'TERRENOS']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'1221','codn5'=>'12212','valorn5'=>'2','nomcuentan5'=>'Edificios']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'1221','codn5'=>'12213','valorn5'=>'3','nomcuentan5'=>'Muebles y enseres']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'1221','codn5'=>'12214','valorn5'=>'4','nomcuentan5'=>'equipos e instalaciones']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'1221','codn5'=>'12215','valorn5'=>'5','nomcuentan5'=>'equipos de computacion']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'1221','codn5'=>'12216','valorn5'=>'6','nomcuentan5'=>'vehiculos']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'1221','codn5'=>'12217','valorn5'=>'7','nomcuentan5'=>'equipos de comunicacion']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'1221','codn5'=>'12218','valorn5'=>'8','nomcuentan5'=>'obras en construccion']);


        DB::table('con__cuentanivel5')->insert(['codn4'=>'1231','codn5'=>'12311','valorn5'=>'1','nomcuentan5'=>'En Empresas privadas nacionales']);

        DB::table('con__cuentanivel5')->insert(['codn4'=>'1241','codn5'=>'12411','valorn5'=>'1','nomcuentan5'=>'Procesos judiciales a empresas']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'1241','codn5'=>'12412','valorn5'=>'2','nomcuentan5'=>'procesos judiciales a ex directivos']);

        DB::table('con__cuentanivel5')->insert(['codn4'=>'1251','codn5'=>'12511','valorn5'=>'1','nomcuentan5'=>'Programas y aplicaciones informaticas']);

        DB::table('con__cuentanivel5')->insert(['codn4'=>'1261','codn5'=>'12611','valorn5'=>'1','nomcuentan5'=>'Cuentas incobrables']);
        
        DB::table('con__cuentanivel5')->insert(['codn4'=>'2111','codn5'=>'21111','valorn5'=>'1','nomcuentan5'=>'obligaciones con el personal']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'2111','codn5'=>'21112','valorn5'=>'2','nomcuentan5'=>'aportes y retenciones por pagar']);

        DB::table('con__cuentanivel5')->insert(['codn4'=>'2112','codn5'=>'21121','valorn5'=>'1','nomcuentan5'=>'Obligaciones tributarias']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'2112','codn5'=>'21122','valorn5'=>'2','nomcuentan5'=>'Retenciones Tributarias por pagar']);

        DB::table('con__cuentanivel5')->insert(['codn4'=>'2113','codn5'=>'21131','valorn5'=>'1','nomcuentan5'=>'Servicios basicos por pagar']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'2113','codn5'=>'21132','valorn5'=>'2','nomcuentan5'=>'comisiones por pagar']);

        DB::table('con__cuentanivel5')->insert(['codn4'=>'2114','codn5'=>'21141','valorn5'=>'1','nomcuentan5'=>'Daaros por pagar liquidados']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'2114','codn5'=>'21142','valorn5'=>'2','nomcuentan5'=>'acreedores y pagos en demasia']);

        DB::table('con__cuentanivel5')->insert(['codn4'=>'2115','codn5'=>'21151','valorn5'=>'1','nomcuentan5'=>'garantias por pagar']);

        DB::table('con__cuentanivel5')->insert(['codn4'=>'2116','codn5'=>'21161','valorn5'=>'1','nomcuentan5'=>'depositos bancarios no identificados']);

        DB::table('con__cuentanivel5')->insert(['codn4'=>'2211','codn5'=>'22111','valorn5'=>'1','nomcuentan5'=>'Aporjtes por pagar no liquidados']);

        DB::table('con__cuentanivel5')->insert(['codn4'=>'2212','codn5'=>'22121','valorn5'=>'1','nomcuentan5'=>'reservas']);

        DB::table('con__cuentanivel5')->insert(['codn4'=>'3111','codn5'=>'31111','valorn5'=>'1','nomcuentan5'=>'capital']);

        DB::table('con__cuentanivel5')->insert(['codn4'=>'3121','codn5'=>'31211','valorn5'=>'1','nomcuentan5'=>'reservas por revaluo tecnico']);

        DB::table('con__cuentanivel5')->insert(['codn4'=>'3131','codn5'=>'31311','valorn5'=>'1','nomcuentan5'=>'resultados']);

        DB::table('con__cuentanivel5')->insert(['codn4'=>'5111', 'codn5'=>'51111','valorn5'=>'1','nomcuentan5'=>'INTERESES PRESTAMOS DE EMERGENCIA']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'5111','codn5'=>'51112','valorn5'=>'2','nomcuentan5'=>'INTERESES PRESTAMOS REGULARES']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'5111','codn5'=>'51113','valorn5'=>'3','nomcuentan5'=>'INTERESES PENALES']);
        
        DB::table('con__cuentanivel5')->insert(['codn4'=>'5211','codn5'=>'52111','valorn5'=>'1','nomcuentan5'=>'INGRESOS POR ALQUILER']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'5211','codn5'=>'52112','valorn5'=>'2','nomcuentan5'=>'INGRESOS POR HOSPEDAJE']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'5211','codn5'=>'52113','valorn5'=>'3','nomcuentan5'=>'INGRESOS club sociales']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'5211','codn5'=>'52114','valorn5'=>'4','nomcuentan5'=>'ingresos colegio ingavi']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'5211','codn5'=>'52115','valorn5'=>'5','nomcuentan5'=>'ingresos por mausoleos']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'5211','codn5'=>'52116','valorn5'=>'6','nomcuentan5'=>'ingresos por afiliacion']);

        DB::table('con__cuentanivel5')->insert(['codn4'=>'5212','codn5'=>'52121','valorn5'=>'1','nomcuentan5'=>'ingresos por hospedaje filiales']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'5212','codn5'=>'52122','valorn5'=>'2','nomcuentan5'=>'ingresos por alquileres tiendas y otros']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'5212','codn5'=>'52123','valorn5'=>'3','nomcuentan5'=>'ingresos por mausoleos']);
        DB::table('con__cuentanivel5')->insert(['codn4'=>'5212','codn5'=>'52124','valorn5'=>'4','nomcuentan5'=>'ingresos sedes sociales y campos deportivos']);

        DB::table('con__cuentanivel5')->insert(['codn4'=>'5311','codn5'=>'53111','valorn5'=>'1','nomcuentan5'=>'ingresos no operativos']);

        DB::table('con__cuentanivel5')->insert(['codn4'=>'5321','codn5'=>'53211','valorn5'=>'1','nomcuentan5'=>'otros ingresos']);
        

    }
}
