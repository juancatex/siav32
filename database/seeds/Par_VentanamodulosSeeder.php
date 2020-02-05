<?php

use Illuminate\Database\Seeder;

class Par_VentanamodulosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { 

//prestamos 
        DB::table('par__ventanamodulos')->insert(['idventanamodulo'=>'29','idmodulo'=>'3','nomventanamodulo'=>'Productos','template'=>'par_producto']);
        DB::table('par__ventanamodulos')->insert(['idventanamodulo'=>'30','idmodulo'=>'3','nomventanamodulo'=>'Prestamos - Calificacion','template'=>'pre_calificacion']);
        DB::table('par__ventanamodulos')->insert(['idventanamodulo'=>'31','idmodulo'=>'3','nomventanamodulo'=>'Factores','template'=>'par_prestamo_factor']);
        DB::table('par__ventanamodulos')->insert(['idventanamodulo'=>'32','idmodulo'=>'3','nomventanamodulo'=>'Estado de Prestamos','template'=>'par_desembolso']);
        DB::table('par__ventanamodulos')->insert(['idventanamodulo'=>'33','idmodulo'=>'3','nomventanamodulo'=>'Cobranzas','template'=>'pre_cobranzas']);
        DB::table('par__ventanamodulos')->insert(['idventanamodulo'=>'34','idmodulo'=>'3','nomventanamodulo'=>'ASCII','template'=>'crearAscii']);

//servicios
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'4','nomventanamodulo'=>'Tipo Servicio','template'=>'ser_servicio']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'4','nomventanamodulo'=>'Establecimientos','template'=>'ser_establecimiento']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'4','nomventanamodulo'=>'Asignación','template'=>'ser_asignacion']);

//parametros        
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'5','nomventanamodulo'=>'Departamentos','template'=>'par_departamento']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'5','nomventanamodulo'=>'Municipios','template'=>'par_municipio']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'5','nomventanamodulo'=>'Destinos','template'=>'par_destino']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'5','nomventanamodulo'=>'Fuerzas','template'=>'par_fuerza']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'5','nomventanamodulo'=>'Grados','template'=>'par_grado']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'5','nomventanamodulo'=>'Especialidades','template'=>'par_especialidad']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'5','nomventanamodulo'=>'Escalafones','template'=>'par_escalafon']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'5','nomventanamodulo'=>'Estadocivil','template'=>'par_estadocivil']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'5','nomventanamodulo'=>'Estados Modulo','template'=>'par_estadomodulo']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'5','nomventanamodulo'=>'Documentos','template'=>'par_documento']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'5','nomventanamodulo'=>'Tipo Socio','template'=>'par_tiposocio']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'5','nomventanamodulo'=>'Monedas','template'=>'par_moneda']);

//administracion
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'6','nomventanamodulo'=>'Usuarios','template'=>'adm_user']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'6','nomventanamodulo'=>'Usuarios-Roles','template'=>'adm_roleuser']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'6','nomventanamodulo'=>'Roles','template'=>'adm_role']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'6','nomventanamodulo'=>'Menu','template'=>'adm_permiso']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'6','nomventanamodulo'=>'Roles-Permisos','template'=>'adm_rolepermiso']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'6','nomventanamodulo'=>'Ventana Módulo','template'=>'adm_ventanamodulo']);

//afiliacion
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'1','nomventanamodulo'=>'Socios','template'=>'socio']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'1','nomventanamodulo'=>'Reporte','template'=>'afi_reportes']);

//aportes
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'2','nomventanamodulo'=>'Subir ASCII','template'=>'subircsv']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'2','nomventanamodulo'=>'Abono/Débito Aporte','template'=>'apo_aporte']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'2','nomventanamodulo'=>'Reportes','template'=>'apo_reportes']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'2','nomventanamodulo'=>'Débito ASCII','template'=>'apo_debitoascii']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'2','nomventanamodulo'=>'Estado Aportes','template'=>'apo_estado']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'2','nomventanamodulo'=>'Tipo Aporte','template'=>'apo_tipoaporte']);

//contabilidad
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'7','nomventanamodulo'=>'Entidades Bancarias','template'=>'con_entidadbancaria']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'7','nomventanamodulo'=>'Tipo Comprobantes','template'=>'con_tipocomprobante']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'7','nomventanamodulo'=>'Plan de Cuentas','template'=>'con_plandecuentas']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'7','nomventanamodulo'=>'Comprobante Automatico','template'=>'con_asientoautomatico']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'7','nomventanamodulo'=>'Comprobante Manual','template'=>'con_comprobantemanual']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'7','nomventanamodulo'=>'Libro de Compras','template'=>'con_librocompra']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'7','nomventanamodulo'=>'Libro de Ventas','template'=>'con_libroventa']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'7','nomventanamodulo'=>'Cargos de Cuenta','template'=>'con_cargocuenta']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'7','nomventanamodulo'=>'Relaciones-Cuentas','template'=>'con_relacionescuentas']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'7','nomventanamodulo'=>'Factura','template'=>'con_factura']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'7','nomventanamodulo'=>'Factura Parametros','template'=>'con_facturaparametro']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'7','nomventanamodulo'=>'Perfil de Cuenta','template'=>'con_perfilcuenta']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'7','nomventanamodulo'=>'Reportes','template'=>'con_reportes']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'7','nomventanamodulo'=>'Conciliación Bancaria','template'=>'con_conciliacion']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'7','nomventanamodulo'=>'Productos','template'=>'con_productos']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'7','nomventanamodulo'=>'Tesorería','template'=>'con_tesoreria']);
        
//daaro
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'8','nomventanamodulo'=>'Devoluciones','template'=>'daa_devolucion']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'8','nomventanamodulo'=>'Tipo Devoluciones','template'=>'daa_tipodevolucion']);

//activos
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'9','nomventanamodulo'=>'Configuración','template'=>'act_configuracion']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'9','nomventanamodulo'=>'Auxiliares','template'=>'act_auxiliar']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'9','nomventanamodulo'=>'Catálogo','template'=>'act_activo']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'9','nomventanamodulo'=>'Bajas','template'=>'act_baja']);

//filiales
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'10','nomventanamodulo'=>'Unidades','template'=>'fil_unidad']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'10','nomventanamodulo'=>'Filiales','template'=>'fil_filial']);

//recursos humanos
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'11','nomventanamodulo'=>'Configuración','template'=>'rrh_configuracion']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'11','nomventanamodulo'=>'Empleados','template'=>'rrh_empleado']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'11','nomventanamodulo'=>'Operaciones','template'=>'rrh_operacion']);

//almacen
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'12','nomventanamodulo'=>'Configuración','template'=>'alm_configuracion']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'12','nomventanamodulo'=>'Proveedores','template'=>'alm_proveedor']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'12','nomventanamodulo'=>'Suministros','template'=>'alm_suministro']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'12','nomventanamodulo'=>'Entrada','template'=>'alm_entrada']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'12','nomventanamodulo'=>'Salida','template'=>'alm_salida']);

//presupuestos
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'13','nomventanamodulo'=>'Asignacion','template'=>'pto_asignacion']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'13','nomventanamodulo'=>'Estimacion','template'=>'pto_estimacion']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'13','nomventanamodulo'=>'Estructura Prog.','template'=>'pto_estructuraprog']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'13','nomventanamodulo'=>'Partida','template'=>'pto_partida']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'13','nomventanamodulo'=>'pei','template'=>'pto_pei']);

//global
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'14','nomventanamodulo'=>'Solicitud C/Cuenta','template'=>'glo_solicitudcargocuenta']);

//acreedores
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'15','nomventanamodulo'=>'Dev. por Acreedores','template'=>'liq_acreedores']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'15','nomventanamodulo'=>'Liq. Saldo Acreedores','template'=>'Liq_saldosacreedor']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'15','nomventanamodulo'=>'Liq. Saldos Menores','template'=>'liq_saldosmenores']);
        DB::table('par__ventanamodulos')->insert(['idmodulo'=>'15','nomventanamodulo'=>'Cobranza Acreedor','template'=>'par_cobranzaacreedor']);
        
    }
}
