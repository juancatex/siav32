<?php

use Illuminate\Database\Seeder;

class Con_CuentasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        DB::table('con__cuentas')->insert(['codn5'=>'11111','valorcuenta'=>'01','nomcuenta'=>'Caja Chica','codcuenta'=>'1111101','descripcion'=>'Caja Chica','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11111','valorcuenta'=>'02','nomcuenta'=>'Caja Casa Comunitaria','codcuenta'=>'1111102','descripcion'=>'Caja Casa Comunitaria','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'11121','valorcuenta'=>'01','nomcuenta'=>'Banco Unión Cta. Nº 1-6015932 ','codcuenta'=>'1112101','descripcion'=>'Banco Unión Cta. Nº 1-6015932 ','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11121','valorcuenta'=>'02','nomcuenta'=>'BCP Cta. Nº 201-3924331-3-39 Administrativos','codcuenta'=>'1112102','descripcion'=>'BCP Cta. Nº 201-3924331-3-39 Administrativos','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11121','valorcuenta'=>'03','nomcuenta'=>'BCP Cta. Nº 201-5039454-3-07 Col. Ingavi','codcuenta'=>'1112103','descripcion'=>'BCP Cta. Nº 201-5039454-3-07 Col. Ingavi','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11121','valorcuenta'=>'04','nomcuenta'=>'BCP Cta. Nº 201-5085058-3-53 Terrenos','codcuenta'=>'1112104','descripcion'=>'BCP Cta. Nº 201-5085058-3-53 Terrenos','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11121','valorcuenta'=>'05','nomcuenta'=>'Mercantil Santa Cruz 1002995029-01 Aportes','codcuenta'=>'1112105','descripcion'=>'Mercantil Santa Cruz 1002995029-01 Aportes','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11121','valorcuenta'=>'06','nomcuenta'=>'Mercantil Santa Cruz 1002995029-02 Captaciones','codcuenta'=>'1112106','descripcion'=>'Mercantil Santa Cruz 1002995029-02 Captaciones','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11121','valorcuenta'=>'07','nomcuenta'=>'Mercantil Santa Cruz 1002995029-04 Servicios','codcuenta'=>'1112107','descripcion'=>'Mercantil Santa Cruz 1002995029-04 Servicios','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'11211','valorcuenta'=>'01','nomcuenta'=>'Inversiones En Bancos','codcuenta'=>'1121101','descripcion'=>'Inversiones En Bancos','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11211','valorcuenta'=>'02','nomcuenta'=>'Depositos a Plazo Fijo a 60 dias','codcuenta'=>'1121102','descripcion'=>'Depositos a Plazo Fijo a 60 dias','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'11311','valorcuenta'=>'01','nomcuenta'=>'Prestaciones de Emergencia Vigente','codcuenta'=>'1131101','descripcion'=>'Prestaciones de Emergencia Vigente','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'11321','valorcuenta'=>'01','nomcuenta'=>'Prestaciones de Emergencia Vencidos','codcuenta'=>'1132101','descripcion'=>'Prestaciones de Emergencia Vencidos','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'11331','valorcuenta'=>'01','nomcuenta'=>'Productos Devengados por Prestamos de Emergencias por cobrar','codcuenta'=>'1133101','descripcion'=>'Productos Devengados por Prestamos de Emergencias por cobrar','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'11341','valorcuenta'=>'01','nomcuenta'=>'(Previsión para Incobrabilidad de Prestamos de Emergencia)','codcuenta'=>'1134101','descripcion'=>'(Previsión para Incobrabilidad de Prestamos de Emergencia)','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'11411','valorcuenta'=>'01','nomcuenta'=>'Aportes por Cobrar Ejercito','codcuenta'=>'1141101','descripcion'=>'Aportes por Cobrar Ejercito','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11411','valorcuenta'=>'02','nomcuenta'=>'Aportes por Cobrar Fuerza Aerea','codcuenta'=>'1141102','descripcion'=>'Aportes por Cobrar Fuerza Aerea','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11411','valorcuenta'=>'03','nomcuenta'=>'Aportes por cobrar Armada Boliviana','codcuenta'=>'1141103','descripcion'=>'Aportes por cobrar Armada Boliviana','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'11511','valorcuenta'=>'01','nomcuenta'=>'Cuentas por Cobrar a Autoridades','codcuenta'=>'1151101','descripcion'=>'Cuentas por Cobrar a Autoridades','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11511','valorcuenta'=>'02','nomcuenta'=>'Cuentas por Cobrar a Personal','codcuenta'=>'1151102','descripcion'=>'Cuentas por Cobrar a Personal','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11511','valorcuenta'=>'03','nomcuenta'=>'Cargos de Cuenta','codcuenta'=>'1151103','descripcion'=>'Cargos de Cuenta','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11511','valorcuenta'=>'04','nomcuenta'=>'Prestamos Ejecución Ex Directivos','codcuenta'=>'1151104','descripcion'=>'Prestamos Ejecución Ex Directivos','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'11512','valorcuenta'=>'01','nomcuenta'=>'Crédito Fiscal IVA','codcuenta'=>'1151201','descripcion'=>'Crédito Fiscal IVA','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11512','valorcuenta'=>'02','nomcuenta'=>'Crédito Fiscal Diferido','codcuenta'=>'1151202','descripcion'=>'Crédito Fiscal Diferido','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11512','valorcuenta'=>'03','nomcuenta'=>'IUE por Compensar','codcuenta'=>'1151203','descripcion'=>'IUE por Compensar','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11512','valorcuenta'=>'04','nomcuenta'=>'Contratos Anticréticos','codcuenta'=>'1151204','descripcion'=>'Contratos Anticréticos','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11512','valorcuenta'=>'05','nomcuenta'=>'Operaciones por Liquidar','codcuenta'=>'1151205','descripcion'=>'Operaciones por Liquidar','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'11513','valorcuenta'=>'01','nomcuenta'=>'Alquileres por Cobrar','codcuenta'=>'1151301','descripcion'=>'Alquileres por Cobrar','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11513','valorcuenta'=>'02','nomcuenta'=>'Servicios por Cobrar','codcuenta'=>'1151302','descripcion'=>'Servicios por Cobrar','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11513','valorcuenta'=>'03','nomcuenta'=>'Cargos de Cuenta','codcuenta'=>'1151303','descripcion'=>'Cargos de Cuenta','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11513','valorcuenta'=>'04','nomcuenta'=>'Servicios Varios','codcuenta'=>'1151304','descripcion'=>'Servicios Varios','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11513','valorcuenta'=>'05','nomcuenta'=>'Hospedaje por Cobrar','codcuenta'=>'1151305','descripcion'=>'Hospedaje por Cobrar','idmoneda'=>1]);

        DB::table('con__cuentas')->insert(['codn5'=>'11514','valorcuenta'=>'01','nomcuenta'=>'Cuentas Incobrables','codcuenta'=>'1151401','descripcion'=>'Cuentas Incobrables','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11514','valorcuenta'=>'02','nomcuenta'=>'(Previsión para Cuentas Incobrables)','codcuenta'=>'1151402','descripcion'=>'(Previsión para Cuentas Incobrables)','idmoneda'=>1]);

        DB::table('con__cuentas')->insert(['codn5'=>'11515','valorcuenta'=>'01','nomcuenta'=>'Anticipo Consultorias','codcuenta'=>'1151501','descripcion'=>'Anticipo Consultorias','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11515','valorcuenta'=>'02','nomcuenta'=>'Anticipo Personal Administrativo','codcuenta'=>'1151502','descripcion'=>'Anticipo Personal Administrativo','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11515','valorcuenta'=>'03','nomcuenta'=>'Anticipo Directivos','codcuenta'=>'1151503','descripcion'=>'Anticipo Directivos','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'11611','valorcuenta'=>'01','nomcuenta'=>'Material de Limpieza e Higiene','codcuenta'=>'1161101','descripcion'=>'Material de Limpieza e Higiene','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11611','valorcuenta'=>'02','nomcuenta'=>'Herramientas Menores','codcuenta'=>'1161102','descripcion'=>'Herramientas Menores','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11611','valorcuenta'=>'03','nomcuenta'=>'Otros Repuestos y Accesorios','codcuenta'=>'1161103','descripcion'=>'Otros Repuestos y Accesorios','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11611','valorcuenta'=>'04','nomcuenta'=>'Papeleria','codcuenta'=>'1161104','descripcion'=>'Papeleria','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11611','valorcuenta'=>'05','nomcuenta'=>'Utiles de Escritorio y Oficina','codcuenta'=>'1161105','descripcion'=>'Utiles de Escritorio y Oficina','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11611','valorcuenta'=>'06','nomcuenta'=>'Utiles y Materiales Electricos','codcuenta'=>'1161106','descripcion'=>'Utiles y Materiales Electricos','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11611','valorcuenta'=>'07','nomcuenta'=>'Productos de Artes Gráficas','codcuenta'=>'1161107','descripcion'=>'Productos de Artes Gráficas','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'11612','valorcuenta'=>'01','nomcuenta'=>'Televisor','codcuenta'=>'1161201','descripcion'=>'Televisor','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11612','valorcuenta'=>'02','nomcuenta'=>'Laptop','codcuenta'=>'1161202','descripcion'=>'Laptop','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11612','valorcuenta'=>'03','nomcuenta'=>'Notebook','codcuenta'=>'1161203','descripcion'=>'Notebook','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11612','valorcuenta'=>'04','nomcuenta'=>'Sistema de Audio','codcuenta'=>'1161204','descripcion'=>'Sistema de Audio','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11612','valorcuenta'=>'05','nomcuenta'=>'Microondas','codcuenta'=>'1161205','descripcion'=>'Microondas','idmoneda'=>1]);

        DB::table('con__cuentas')->insert(['codn5'=>'11613','valorcuenta'=>'01','nomcuenta'=>'(Previsión para Desvalorización Almacenes)','codcuenta'=>'1161301','descripcion'=>'(Previsión para Desvalorización Almacenes)','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'11613','valorcuenta'=>'02','nomcuenta'=>'(Previsión para Desvalorización Linea Blanca)','codcuenta'=>'1161302','descripcion'=>'(Previsión para Desvalorización Linea Blanca)','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'12111','valorcuenta'=>'01','nomcuenta'=>'Prestamos Regulares','codcuenta'=>'1211101','descripcion'=>'Prestamos Regulares','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'12111','valorcuenta'=>'02','nomcuenta'=>'Prestamos Refinanciado','codcuenta'=>'1211102','descripcion'=>'Prestamos Refinanciado','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'12111','valorcuenta'=>'03','nomcuenta'=>'Prestamos Sin Garantes','codcuenta'=>'1211103','descripcion'=>'Prestamos Sin Garantes','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'12111','valorcuenta'=>'04','nomcuenta'=>'Prestamos Ejecución a Garantes','codcuenta'=>'1211104','descripcion'=>'Prestamos Ejecución a Garantes','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'12111','valorcuenta'=>'05','nomcuenta'=>'Prestamos Ejecución a Titulares','codcuenta'=>'1211105','descripcion'=>'Prestamos Ejecución a Titulares','idmoneda'=>1]);

        DB::table('con__cuentas')->insert(['codn5'=>'12112','valorcuenta'=>'01','nomcuenta'=>'Prestamos Regulares Vencido','codcuenta'=>'1211201','descripcion'=>'Prestamos Regulares Vencido','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'12112','valorcuenta'=>'02','nomcuenta'=>'Prestamos Refinanciado Vencido','codcuenta'=>'1211202','descripcion'=>'Prestamos Refinanciado Vencido','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'12112','valorcuenta'=>'03','nomcuenta'=>'Prestamos Sin Garantes Vencido','codcuenta'=>'1211203','descripcion'=>'Prestamos Sin Garantes Vencido','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'12112','valorcuenta'=>'04','nomcuenta'=>'Prestamos Ejecución a Garantes','codcuenta'=>'1211204','descripcion'=>'Prestamos Ejecución a Garantes','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'12112','valorcuenta'=>'05','nomcuenta'=>'Prestamos Ejecución a Titulares','codcuenta'=>'1211205','descripcion'=>'Prestamos Ejecución a Titulares','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'12113','valorcuenta'=>'01','nomcuenta'=>'Prestamos Reprogramados Vigentes','codcuenta'=>'1211301','descripcion'=>'Prestamos Reprogramados Vigentes','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'12113','valorcuenta'=>'02','nomcuenta'=>'Prestamos Reprogramados Vencidos','codcuenta'=>'1211302','descripcion'=>'Prestamos Reprogramados Vencidos','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'12113','valorcuenta'=>'03','nomcuenta'=>'Prestamos Reprogramados en Ejecución','codcuenta'=>'1211304','descripcion'=>'Prestamos Reprogramados en Ejecución','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'12121','valorcuenta'=>'01','nomcuenta'=>'(Previsión para Incobrabilidad de Prestamos de Emergencia)','codcuenta'=>'1212101','descripcion'=>'(Previsión para Incobrabilidad de Prestamos de Emergencia)','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'12131','valorcuenta'=>'01','nomcuenta'=>'Refinanciamiento de Prestamos (Transitoria)','codcuenta'=>'1213101','descripcion'=>'Refinanciamiento de Prestamos (Transitoria)','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'12131','valorcuenta'=>'02','nomcuenta'=>'Prestamos Cobrados Via Acreedores (Transitoria)','codcuenta'=>'1213102','descripcion'=>'Prestamos Cobrados Via Acreedores (Transitoria)','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'12131','valorcuenta'=>'03','nomcuenta'=>'Cobranza o Activación a Garantes Titulares (Transitoria)','codcuenta'=>'1213103','descripcion'=>'Cobranza o Activación a Garantes Titulares (Transitoria)','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'12131','valorcuenta'=>'04','nomcuenta'=>'Liquidación Prestamo por Dto. DAARO','codcuenta'=>'1213104','descripcion'=>'Liquidación Prestamo por Dto. DAARO','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'12131','valorcuenta'=>'05','nomcuenta'=>'Cuente Puente Liq. Prestamos por Dto. DAARO','codcuenta'=>'1213105','descripcion'=>'Cuente Puente Liq. Prestamos por Dto. DAARO','idmoneda'=>1]);

        DB::table('con__cuentas')->insert(['codn5'=>'12211','valorcuenta'=>'01','nomcuenta'=>'TERRENOS LA PAZ','codcuenta'=>'1221101','descripcion'=>'TERRENOS LA PAZ','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'12211','valorcuenta'=>'02','nomcuenta'=>'TERRENOS COCHABAMBA','codcuenta'=>'1221102','descripcion'=>'TERRENOS COCHABAMBA','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'12211','valorcuenta'=>'03','nomcuenta'=>'TERRENOS SANTA CRUZ','codcuenta'=>'1221103','descripcion'=>'TERRENOS SANTA CRUZ','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'12211','valorcuenta'=>'04','nomcuenta'=>'TERRENOS ORURO','codcuenta'=>'1221104','descripcion'=>'TERRENOS ORURO','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'12211','valorcuenta'=>'05','nomcuenta'=>'TERRENOS TARIJA','codcuenta'=>'1221105','descripcion'=>'TERRENOS TARIJA','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'12212','valorcuenta'=>'01','nomcuenta'=>'Edificios','codcuenta'=>'1221201','descripcion'=>'Edificios','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'12212','valorcuenta'=>'02','nomcuenta'=>'(Depreciaicón Acumulada Edificios)','codcuenta'=>'1221202','descripcion'=>'(Depreciaicón Acumulada Edificios)','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'12213','valorcuenta'=>'01','nomcuenta'=>'Muebles y Enseres','codcuenta'=>'1221301','descripcion'=>'Muebles y Enseres','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'12213','valorcuenta'=>'02','nomcuenta'=>'(Depreciaicón Acumulada Muebles y Enseres)','codcuenta'=>'1221302','descripcion'=>'(Depreciaicón Acumulada Muebles y Enseres)','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'12214','valorcuenta'=>'01','nomcuenta'=>'Equipos e Instalaciones','codcuenta'=>'1221401','descripcion'=>'Equipos e Instalaciones','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'12214','valorcuenta'=>'02','nomcuenta'=>'(Depreciaicón Acumulada Equipos e Instalaciones)','codcuenta'=>'1221402','descripcion'=>'(Depreciaicón Acumulada Equipos e Instalaciones)','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'12215','valorcuenta'=>'01','nomcuenta'=>'Equipos de Computación','codcuenta'=>'1221501','descripcion'=>'Equipos de Computación','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'12215','valorcuenta'=>'02','nomcuenta'=>'(Depreciaicón Acumulada Equipos de computación)','codcuenta'=>'1221502','descripcion'=>'(Depreciaicón Acumulada Equipos de computación)','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'12216','valorcuenta'=>'01','nomcuenta'=>'Vehiculos','codcuenta'=>'1221601','descripcion'=>'Vehiculos','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'12216','valorcuenta'=>'02','nomcuenta'=>'(Depreciaicón Acumulada Vehiculos)','codcuenta'=>'1221602','descripcion'=>'(Depreciaicón Acumulada Vehiculos)','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'12217','valorcuenta'=>'01','nomcuenta'=>'Equipos de Comunicación','codcuenta'=>'1221701','descripcion'=>'Equipos de Comunicación','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'12217','valorcuenta'=>'02','nomcuenta'=>'(Depreciaicón Acumulada Equipos de Comunicación)','codcuenta'=>'1221702','descripcion'=>'(Depreciaicón Acumulada Equipos de Comunicación)','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'12218','valorcuenta'=>'01','nomcuenta'=>'Obras en Construcción','codcuenta'=>'1221801','descripcion'=>'Obras en Construcción','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'12218','valorcuenta'=>'02','nomcuenta'=>'(Depreciaicón Acumulada Obras en Construcción)','codcuenta'=>'1221802','descripcion'=>'(Depreciaicón Acumulada Obras en Construcción)','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'12311','valorcuenta'=>'01','nomcuenta'=>'Acciones Telefónicas','codcuenta'=>'1231101','descripcion'=>'Acciones Telefónicas','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'12411','valorcuenta'=>'01','nomcuenta'=>'Caso Marjen - SISTEMA SAFCON','codcuenta'=>'1241101','descripcion'=>'Caso Marjen - SISTEMA SAFCON','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'12412','valorcuenta'=>'01','nomcuenta'=>'Ex Directivos 2006-2009','codcuenta'=>'1241201','descripcion'=>'Ex Directivos 2006-2009','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'12412','valorcuenta'=>'02','nomcuenta'=>'Prestamos Vencidos Ex Directivos','codcuenta'=>'1241202','descripcion'=>'Prestamos Vencidos Ex Directivos','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'12511','valorcuenta'=>'01','nomcuenta'=>'Licencia Paquete Contable','codcuenta'=>'1251101','descripcion'=>'Licencia Paquete Contable','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'12511','valorcuenta'=>'02','nomcuenta'=>'(Amortización acumuada Paquete Contable)','codcuenta'=>'1251102','descripcion'=>'(Amortización acumuada Paquete Contable)','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'12611','valorcuenta'=>'01','nomcuenta'=>'Cuentas Incobrables','codcuenta'=>'1261101','descripcion'=>'Cuentas Incobrables','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'12611','valorcuenta'=>'02','nomcuenta'=>'(Previsión para Cuentas Incobrables)','codcuenta'=>'1261102','descripcion'=>'(Previsión para Cuentas Incobrables)','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'21111','valorcuenta'=>'01','nomcuenta'=>'Sueldos y Salarios por Pagar','codcuenta'=>'2111101','descripcion'=>'Sueldos y Salarios por Pagar','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'21111','valorcuenta'=>'02','nomcuenta'=>'AFP Previsión 12.71% por Pagar','codcuenta'=>'2111102','descripcion'=>'AFP Previsión 12.71% por Pagar','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'21111','valorcuenta'=>'03','nomcuenta'=>'AFP Futuro 12.71 % por Pagar','codcuenta'=>'2111103','descripcion'=>'AFP Futuro 12.71 % por Pagar','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'21111','valorcuenta'=>'04','nomcuenta'=>'Aguinaldos por Pagar','codcuenta'=>'2111104','descripcion'=>'Aguinaldos por Pagar','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'21111','valorcuenta'=>'05','nomcuenta'=>'Descuentos por Atrasos','codcuenta'=>'2111105','descripcion'=>'Descuentos por Atrasos','idmoneda'=>1]);
     
        DB::table('con__cuentas')->insert(['codn5'=>'21112','valorcuenta'=>'01','nomcuenta'=>'Caja Nacional de Salud 10%','codcuenta'=>'2111201','descripcion'=>'Caja Nacional de Salud 10%','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'21112','valorcuenta'=>'02','nomcuenta'=>'Aporte Largo Plazo 1.71 %','codcuenta'=>'2111202','descripcion'=>'Aporte Largo Plazo 1.71 %','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'21112','valorcuenta'=>'03','nomcuenta'=>'Aporte Solidario 3%','codcuenta'=>'2111203','descripcion'=>'Aporte Solidario 3%','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'21112','valorcuenta'=>'04','nomcuenta'=>'Aporte Pro Vivienda 2%','codcuenta'=>'2111204','descripcion'=>'Aporte Pro Vivienda 2%','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'21112','valorcuenta'=>'05','nomcuenta'=>'RC-IVA Dependientes 13%','codcuenta'=>'2111205','descripcion'=>'RC-IVA Dependientes 13%','idmoneda'=>1]);
   
        DB::table('con__cuentas')->insert(['codn5'=>'21121','valorcuenta'=>'01','nomcuenta'=>'Debito Fiscal IVA por Pagar','codcuenta'=>'2112101','descripcion'=>'Debito Fiscal IVA por Pagar','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'21121','valorcuenta'=>'02','nomcuenta'=>'Impuesto a las Transacciones por Pagar','codcuenta'=>'2112102','descripcion'=>'Impuesto a las Transacciones por Pagar','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'21121','valorcuenta'=>'03','nomcuenta'=>'Impuesto a las Utilidades por Pagar','codcuenta'=>'2112103','descripcion'=>'Impuesto a las Utilidades por Pagar','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'21121','valorcuenta'=>'04','nomcuenta'=>'Impuesto a los Bienes Inmuebles por Pagar','codcuenta'=>'2112104','descripcion'=>'Impuesto a los Bienes Inmuebles por Pagar','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'21121','valorcuenta'=>'05','nomcuenta'=>'Patentes y Tasas por Pagar','codcuenta'=>'2112105','descripcion'=>'Patentes y Tasas por Pagar','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'21122','valorcuenta'=>'01','nomcuenta'=>'Retenciones IUE 5% Bienes','codcuenta'=>'2112201','descripcion'=>'Retenciones IUE 5% Bienes','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'21122','valorcuenta'=>'02','nomcuenta'=>'Retenciones IUE 12.5% Servicios ','codcuenta'=>'2112202','descripcion'=>'Retenciones IUE 12.5% Servicios ','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'21122','valorcuenta'=>'03','nomcuenta'=>'Retenciones IT 3% ','codcuenta'=>'2112203','descripcion'=>'Retenciones IT 3% ','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'21122','valorcuenta'=>'04','nomcuenta'=>'Retenciones RC-IVA a Terceros','codcuenta'=>'2112204','descripcion'=>'Retenciones RC-IVA a Terceros','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'21131','valorcuenta'=>'01','nomcuenta'=>'Entel','codcuenta'=>'2113101','descripcion'=>'Entel','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'21131','valorcuenta'=>'02','nomcuenta'=>'Cotel','codcuenta'=>'2113102','descripcion'=>'Cotel','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'21131','valorcuenta'=>'03','nomcuenta'=>'YPFB','codcuenta'=>'2113103','descripcion'=>'YPFB','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'21131','valorcuenta'=>'04','nomcuenta'=>'AXS BOLIVIA S.A.','codcuenta'=>'2113104','descripcion'=>'AXS BOLIVIA S.A.','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'21131','valorcuenta'=>'05','nomcuenta'=>'DELAPAZ','codcuenta'=>'2113105','descripcion'=>'DELAPAZ','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'21131','valorcuenta'=>'06','nomcuenta'=>'EPSAS','codcuenta'=>'21131016','descripcion'=>'EPSAS','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'21132','valorcuenta'=>'01','nomcuenta'=>'Comisiones Ministerio de Defensa','codcuenta'=>'2113201','descripcion'=>'Comisiones Ministerio de Defensa','idmoneda'=>1]);

        DB::table('con__cuentas')->insert(['codn5'=>'21141','valorcuenta'=>'01','nomcuenta'=>'Cumplimiento por Pagar','codcuenta'=>'2114101','descripcion'=>'Cumplimiento por Pagar','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'21141','valorcuenta'=>'02','nomcuenta'=>'Jubilación por Pagar','codcuenta'=>'2114102','descripcion'=>'Jubilación por Pagar','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'21141','valorcuenta'=>'03','nomcuenta'=>'Fallecimiento por Pagar','codcuenta'=>'2114103','descripcion'=>'Fallecimiento por Pagar','idmoneda'=>1]);

        DB::table('con__cuentas')->insert(['codn5'=>'21142','valorcuenta'=>'01','nomcuenta'=>'Aportes en Demasia por Devolver','codcuenta'=>'2114201','descripcion'=>'Aportes en Demasia por Devolver','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'21142','valorcuenta'=>'02','nomcuenta'=>'Acreedores','codcuenta'=>'2114202','descripcion'=>'Acreedores','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'21151','valorcuenta'=>'01','nomcuenta'=>'Garantias Juancito Pinto','codcuenta'=>'2115101','descripcion'=>'Garantias Juancito Pinto','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'21151','valorcuenta'=>'02','nomcuenta'=>'Garantias Salon el Dorado','codcuenta'=>'2115102','descripcion'=>'Garantias Salon el Dorado','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'21151','valorcuenta'=>'03','nomcuenta'=>'Garantias 7% de Cumpliento de Contrato','codcuenta'=>'2115103','descripcion'=>'Garantias 7% de Cumpliento de Contrato','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'21151','valorcuenta'=>'04','nomcuenta'=>'Garantias 10% de Cumplimiento de Contrato','codcuenta'=>'2115104','descripcion'=>'Garantias 10% de Cumplimiento de Contrato','idmoneda'=>1]);

        DB::table('con__cuentas')->insert(['codn5'=>'21161','valorcuenta'=>'01','nomcuenta'=>'Banco Unión','codcuenta'=>'2116101','descripcion'=>'Banco Unión','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'21161','valorcuenta'=>'02','nomcuenta'=>'Banco de Crédito','codcuenta'=>'2116102','descripcion'=>'Banco de Crédito','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'21161','valorcuenta'=>'03','nomcuenta'=>'Banco Mercantil Santa Cruz','codcuenta'=>'2116103','descripcion'=>'Banco Mercantil Santa Cruz','idmoneda'=>1]);

        DB::table('con__cuentas')->insert(['codn5'=>'22111','valorcuenta'=>'01','nomcuenta'=>'Aporte 4% Ejercito','codcuenta'=>'2211101','descripcion'=>'Aporte 4% Ejercito','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'22111','valorcuenta'=>'02','nomcuenta'=>'Aporte 4% Fuerza Aerea','codcuenta'=>'2211102','descripcion'=>'Aporte 4% Fuerza Aerea','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'22111','valorcuenta'=>'03','nomcuenta'=>'Aporte 4% Armada Boliviana','codcuenta'=>'2211103','descripcion'=>'Aporte 4% Armada Boliviana','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'22121','valorcuenta'=>'01','nomcuenta'=>'Reservas DAARO','codcuenta'=>'2212101','descripcion'=>'Reservas DAARO','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'22121','valorcuenta'=>'02','nomcuenta'=>'Rsereva 1% (Refuerzo DAARO)','codcuenta'=>'2212102','descripcion'=>'Rsereva 1% (Refuerzo DAARO)','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'31111','valorcuenta'=>'01','nomcuenta'=>'Capital','codcuenta'=>'3111101','descripcion'=>'Capital','idmoneda'=>1]);

        DB::table('con__cuentas')->insert(['codn5'=>'31211','valorcuenta'=>'01','nomcuenta'=>'Reservas por Revaluo Técnico de Activos Fijos','codcuenta'=>'3121001','descripcion'=>'Reservas por Revaluo Técnico de Activos Fijos','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'31311','valorcuenta'=>'01','nomcuenta'=>'Resultados de la Gestión','codcuenta'=>'3131101','descripcion'=>'Resultados de la Gestión','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'31311','valorcuenta'=>'02','nomcuenta'=>'Resultados Acumulados','codcuenta'=>'3131102','descripcion'=>'Resultados Acumulados','idmoneda'=>1]);



        DB::table('con__cuentas')->insert(['codn5'=>'51111','valorcuenta'=>'01','nomcuenta'=>'Intereses Prestamos de Emergencia','codcuenta'=>'5111101','descripcion'=>'Intereses Prestamos de Emergencia','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'51112','valorcuenta'=>'01','nomcuenta'=>'Intereses Prestamos Regulares','codcuenta'=>'5111201','descripcion'=>'Intereses Prestamos Regulares','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'51113','valorcuenta'=>'01','nomcuenta'=>'Intereses Penales','codcuenta'=>'5111301','descripcion'=>'Intereses Penales','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'52111','valorcuenta'=>'01','nomcuenta'=>'Juancito Pinto','codcuenta'=>'5211101','descripcion'=>'Juancito Pinto','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'52112','valorcuenta'=>'01','nomcuenta'=>'Casa Comunitaria','codcuenta'=>'5211201','descripcion'=>'Casa Comunitaria','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'52113','valorcuenta'=>'01','nomcuenta'=>'Salon Dorado','codcuenta'=>'5211301','descripcion'=>'Salon Dorado','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'52113','valorcuenta'=>'02','nomcuenta'=>'Cancha Sucre','codcuenta'=>'5211302','descripcion'=>'Cancha Sucre','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'52113','valorcuenta'=>'03','nomcuenta'=>'Complejo COTA COTA','codcuenta'=>'5211303','descripcion'=>'Complejo COTA COTA','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'52114','valorcuenta'=>'01','nomcuenta'=>'Alquileres Transitorios','codcuenta'=>'5211401','descripcion'=>'Alquileres Transitorios','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'52115','valorcuenta'=>'01','nomcuenta'=>'Mauselo La Paz ','codcuenta'=>'5211501','descripcion'=>'Mauselo La Paz ','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'52116','valorcuenta'=>'01','nomcuenta'=>'Afiliación','codcuenta'=>'5211601','descripcion'=>'Afiliación','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'52116','valorcuenta'=>'02','nomcuenta'=>'Papeleria','codcuenta'=>'5211602','descripcion'=>'Papeleria','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'52121','valorcuenta'=>'01','nomcuenta'=>'Hospedaje Cochabamba','codcuenta'=>'5212101','descripcion'=>'Hospedaje Cochabamba','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'52121','valorcuenta'=>'02','nomcuenta'=>'Hospedaje Santa Cruz','codcuenta'=>'5212102','descripcion'=>'Hospedaje Santa Cruz','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'52121','valorcuenta'=>'03','nomcuenta'=>'Hospedaje Oruro','codcuenta'=>'5212103','descripcion'=>'Hospedaje Oruro','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'52121','valorcuenta'=>'04','nomcuenta'=>'Hospedaje Tarija','codcuenta'=>'5212104','descripcion'=>'Hospedaje Tarija','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'52121','valorcuenta'=>'05','nomcuenta'=>'Hospedaje Sucre','codcuenta'=>'5212105','descripcion'=>'Hospedaje Sucre','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'52121','valorcuenta'=>'06','nomcuenta'=>'Hospedaje Beni','codcuenta'=>'5212106','descripcion'=>'Hospedaje Beni','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'52122','valorcuenta'=>'01','nomcuenta'=>'Alquileres Tiendas Cochabamba','codcuenta'=>'5212201','descripcion'=>'Alquileres Tiendas Cochabamba','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'52122','valorcuenta'=>'02','nomcuenta'=>'Alquileres Tiendas Potosi','codcuenta'=>'5212202','descripcion'=>'Alquileres Tiendas Potosi','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'52122','valorcuenta'=>'03','nomcuenta'=>'Alquileres Tiendas Sucre','codcuenta'=>'5212203','descripcion'=>'Alquileres Tiendas Sucre','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'52122','valorcuenta'=>'04','nomcuenta'=>'Alquileres Tiendas Rurrenabaque','codcuenta'=>'5212204','descripcion'=>'Alquileres Tiendas Rurrenabaque','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'52123','valorcuenta'=>'01','nomcuenta'=>'Mausoleo Cochabamba','codcuenta'=>'5212301','descripcion'=>'Mausoleo Cochabamba','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'52123','valorcuenta'=>'02','nomcuenta'=>'Mausoleo Santa Cruz','codcuenta'=>'5212302','descripcion'=>'Mausoleo Santa Cruz','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'52123','valorcuenta'=>'03','nomcuenta'=>'Mausoleo Tarija','codcuenta'=>'5212303','descripcion'=>'Mausoleo Tarija','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'52123','valorcuenta'=>'04','nomcuenta'=>'Mausoleo Camiri','codcuenta'=>'5212304','descripcion'=>'Mausoleo Camiri','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'52124','valorcuenta'=>'01','nomcuenta'=>'Club Social Cochabamba','codcuenta'=>'5212401','descripcion'=>'Club Social Cochabamba','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'52124','valorcuenta'=>'02','nomcuenta'=>'Club Social Santa Cruz','codcuenta'=>'5212402','descripcion'=>'Club Social Santa Cruz','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'52124','valorcuenta'=>'03','nomcuenta'=>'Club Social Oruro','codcuenta'=>'5212403','descripcion'=>'Club Social Oruro','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'52124','valorcuenta'=>'04','nomcuenta'=>'Club Social Tarija','codcuenta'=>'5212404','descripcion'=>'Club Social Tarija','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'52124','valorcuenta'=>'05','nomcuenta'=>'Club Social Sucre','codcuenta'=>'5212405','descripcion'=>'Club Social Sucre','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'53111','valorcuenta'=>'01','nomcuenta'=>'Diferencia por Redondeo','codcuenta'=>'5311101','descripcion'=>'Diferencia por Redondeo','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'53111','valorcuenta'=>'02','nomcuenta'=>'Diferencia de Cambio','codcuenta'=>'5311102','descripcion'=>'Diferencia de Cambio','idmoneda'=>1]);
        DB::table('con__cuentas')->insert(['codn5'=>'53111','valorcuenta'=>'03','nomcuenta'=>'Ajuste por Inflación y Tenencia de Bienes','codcuenta'=>'5311103','descripcion'=>'Ajuste por Inflación y Tenencia de Bienes','idmoneda'=>1]);
        
        DB::table('con__cuentas')->insert(['codn5'=>'53211','valorcuenta'=>'01','nomcuenta'=>'Otros Ingresos','codcuenta'=>'5321101','descripcion'=>'Otros Ingresos','idmoneda'=>1]);


    }

    
}
