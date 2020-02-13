require("./bootstrap");

window.Vue = require("vue");
window._pl = require("./func_10251.js");
window._num = require("./numeral.js");
window._pdf = require("./pdf.js");
window._code = require("./ControlCode.js");
window.VeeValidate = require("vee-validate");
window.DatasVues = [];

const VueValidationEs = require("vee-validate/dist/locale/es");

setvue(0, 'principal', './components/plugin vue/principal.vue');
setvue(0, 'Body_header', './components/plugin vue/Body_header.vue');
setvue(0, 'Ajaxselect', './components/plugin vue/selectAjax.vue');
setvue(0, 'excelReader', './components/plugin vue/excelReader.vue');
setvue(0, 'autocomplete', './components/plugin vue/autocomplete.vue');

//parametros 
setvue(1, 'par_departamento', './components/Par_Departamento.vue');
setvue(1, 'par_municipio', './components/Par_Municipio.vue');
setvue(1, 'par_destino', './components/Par_Destino.vue');
setvue(1, 'par_fuerza', './components/Par_Fuerza.vue');
setvue(1, 'par_grado', './components/Par_Grado.vue');
setvue(1, 'par_especialidad', './components/Par_Especialidad.vue');
setvue(1, 'par_estadocivil', './components/Par_Estadocivil.vue');
setvue(1, 'par_escalafon', './components/Par_Escalafon.vue');
setvue(1, 'par_moneda', './components/Par_Moneda.vue');

setvue(1, 'par_estadomodulo', './components/Par_Estadomodulo.vue');
setvue(1, 'par_documento', './components/Par_Documento.vue');
setvue(1, 'par_tiposocio', './components/Par_Tiposocio.vue');

//servicios
setvue(1, 'ser_servicio', './components/servicios/Ser_Servicio.vue');
setvue(1, 'ser_establecimiento', './components/servicios/Ser_Establecimiento.vue');
setvue(1, 'ser_asignacion', './components/servicios/Ser_Asignacion.vue');

//filiales
setvue(1,'fil_unidad','./components/filiales/Fil_Unidad.vue'); 
setvue(1,'fil_filial','./components/filiales/Fil_Filial.vue'); 

//rrhh
setvue(1, 'rrh_empleado', './components/rhumanos/Rrh_Empleado.vue');
setvue(1, 'rrh_configuracion', './components/rhumanos/Rrh_Configuracion.vue');
setvue(1, 'rrh_operacion', './components/rhumanos/Rrh_Operacion.vue');

//activos fijos
setvue(1, 'act_configuracion', './components/activos/Act_Configuracion.vue');
setvue(1, 'act_ambiente', './components/activos/Act_Ambiente.vue');
setvue(1, 'act_grupo', './components/activos/Act_Grupo.vue');
setvue(1, 'act_auxiliar', './components/activos/Act_Auxiliar.vue');
setvue(1, 'act_activo', './components/activos/Act_Activo.vue');
setvue(1, 'act_baja', './components/activos/Act_Baja.vue');

//almacenes
setvue(0, 'alm_configuracion', './components/almacenes/Alm_Configuracion.vue');
setvue(0, 'alm_proveedor', './components/almacenes/Alm_Proveedor.vue');
setvue(1, 'alm_suministro', './components/almacenes/Alm_Suministro.vue');
setvue(1, 'alm_entrada', './components/almacenes/Alm_Entrada.vue');
setvue(0, 'alm_salida', './components/almacenes/Alm_Salida.vue');

//presupuesto
setvue(1, 'pto_pei', './components/presupuesto/Pto_Pei.vue');
setvue(1, 'pto_estructuraprog', './components/presupuesto/Pto_Estructuraprog.vue');
setvue(1, 'pto_partida', './components/presupuesto/Pto_Partida.vue');
setvue(1, 'pto_asignacion', './components/presupuesto/Pto_Asignacion.vue');
setvue(1, 'pto_estimacion', './components/presupuesto/Pto_Estimacion.vue');

//aportes
setvue(1, 'apo_tipoaporte', './components/Apo_Tipoaporte.vue');
setvue(1, 'apo_estado', './components/Apo_Estado.vue');
setvue(1, 'apo_aporte', './components/Apo_Aporte.vue');
setvue(1, 'apo_debitoascii', './components/Apo_DebitoAscii.vue');
setvue(1, 'apo_reportes', './components/Apo_Reportes.vue');


//adm
setvue(1, 'adm_role', './components/administracion/Adm_Role.vue');
setvue(1, 'adm_user', './components/administracion/Adm_User.vue');
setvue(1, 'adm_permiso', './components/administracion/Adm_Permiso.vue');
setvue(1, 'adm_roleuser', './components/administracion/Adm_Roleuser.vue');
setvue(1, 'adm_rolepermiso', './components/administracion/Adm_Rolepermiso.vue');
setvue(1, 'adm_ventanamodulo', './components/administracion/Adm_Ventanamodulo.vue');
setvue(1, 'adm_log', './components/administracion/Adm_Log.vue');

//SUBIR
setvue(1, 'subircsv', './components/SubirCsv.vue');

//DAARO
setvue(1, 'daa_tipodevolucion', './components/daaro/Daa_Tipodevolucion.vue');
setvue(1, 'daa_devolucion', './components/daaro/Daa_Devolucion.vue');

//contabilidad
setvue(1, 'con_tipocomprobante', './components/contabilidad/Con_Tipocomprobante.vue');
setvue(1, 'con_plandecuentas', './components/contabilidad/Con_Plandecuentas.vue');
setvue(1, 'con_perfilcuenta', './components/contabilidad/Con_Perfilcuenta.vue');
setvue(1, 'con_asientoautomatico', './components/contabilidad/Con_AsientoAutomatico.vue');
setvue(1, 'con_comprobantemanual', './components/contabilidad/Con_ComprobanteManual.vue');
setvue(1, 'con_librocompra', './components/contabilidad/Con_Librocompra.vue');
setvue(1, 'con_libroventa', './components/contabilidad/Con_Libroventa.vue');
setvue(1, 'con_cargocuenta', './components/contabilidad/Con_Cargocuenta.vue');
setvue(0, 'con_relacionescuentas', './components/contabilidad/Con__RelacionesCuentas.vue');
setvue(0, 'con_reportes', './components/contabilidad/Con__Reportes.vue');
setvue(0, 'con_conciliacion', './components/contabilidad/Con__ConciliacionBancaria.vue');
setvue(0, 'con_productos', './components/contabilidad/Con__Productos.vue');
setvue(1, 'con_facturaparametro', './components/contabilidad/Con__Facturaparametros.vue');
setvue(1, 'con_factura', './components/contabilidad/Con__Factura.vue');
setvue(0, 'con_tesoreria', './components/contabilidad/Con__Tesoreria.vue');
setvue(1, 'con_entidadbancaria', './components/Con_Entidadbancaria.vue');

// global
setvue(1, 'glo_solicitudcargocuenta', './components/global/Glo_SolicitudCargoCuenta.vue');

// socio
setvue(1, 'socio', './components/Socio.vue');
setvue(1, 'afi_reportes', './components/Afi_Reportes.vue');

// cartera
setvue(0, 'crearascii', './components/cartera/Pre_CrearAscii.vue');
setvue(1, 'pre_cobranzas', './components/cartera/Pre_Cobranza.vue');
setvue(1, 'par_prestamo_factor', './components/cartera/Par_factores.vue');
setvue(1, 'pre_calificacion', './components/cartera/Pre_Calificacion.vue');
setvue(1, 'par_producto', './components/cartera/Par_Producto.vue');
setvue(1, 'par_desembolso', './components/cartera/Par_Desembolso.vue');
setvue(1, 'cargos', './components/cartera/modal_cargos_adicionales.vue');
setvue(1, 'view-cargos', './components/cartera/modal_cargos_adicionales_view.vue');
setvue(1, 'cuentaBancaria', './components/cartera/modal_cuentaBancaria.vue');
setvue(1, 'status', './components/cartera/modal_status_prestamos.vue');

setvue(0, 'cobranza_ascii', './components/cartera/modal_cobranza.vue');
setvue(1, 'liq_saldosmenores', './components/cartera/Liq_saldosmenores.vue');
setvue(1, 'liq_saldosacreedor', './components/cartera/Liq_saldosacreedores.vue');
setvue(1, 'liq_acreedores', './components/cartera/Liq_acreedores.vue');
setvue(1, 'par_cobranzaacreedor', './components/cartera/Par_cobranzaacreedor.vue');
setvue(1, 'liq_reportes', './components/cartera/liq_reportes.vue');

function setvue(e, a, s) { var r = require(`${s}`).default;
    1 == e && DatasVues.push({ name: a, permisos: r.data().arrayPermisos }), Vue.component(a, r) }

window.vue = new Vue({
    el: "#app",
    data: {
        menu: 0,
        object: null
    },
    methods: {

        to(id, obj = null) {
            this.object = obj;
            if (id >= 0) {
                this.menu = id;
                $var = "[id=" + id + "]";
                $($var).parents(".nav-dropdown").children('a').click();
                $("a").removeClass("active");
                $($var).find("a").addClass("active");
            }
        },
        click(id) {
            this.menu = id;
            $var = "[id=" + id + "]";
            $("a").removeClass("active");
            $($var).find("a").addClass("active");
            $('#app').toggleClass('sidebar-mobile-show');
        }
    }
});