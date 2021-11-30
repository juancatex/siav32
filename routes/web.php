<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/getexcel', function () {
    return Storage::download('newsocios.xlsx');
  })->name('getexcel');

Route::any('/', function(){
    if(session('error')){
        $value=session('error');
        if(strcasecmp($value[0], '2002')==0){
            return view('auth.newlogin', ['nota' => '','status' =>'No se pudo conectar con la base de datos.']);
        }else{
            return view('auth.newlogin', ['nota' => '','status' => $value[1]]);
        }
    }else{
        if(Auth::check()) return view('contenido/contenido')->with('status','');
        else return view('auth.newlogin', ['nota'=>(session('setout'))?session('setout'):'','status'=>(session('status'))?session('status'):'']);
    }
});
Route::get('login' ,function(){
    if(Auth::check()) return view('contenido/contenido')->with('status','');
    else {
        Auth::logout();
        Session::flush();
        Session::regenerate();
        return view('auth.newlogin')->with('status','Error al momento de conectar con el servidor, intente nuevamente porfavor.');
    }
});

Route::post('login' ,'Auth\LoginController@login')->name('login');
Route::post('logout','Auth\LoginController@logout')->name('logout');
Route::post('logout2', function(){
    Auth::logout();
    Session::flush();
    Session::regenerate();
    return view('auth.newlogin', ['nota' => '','status' =>'']);
});

Route::get('/login_server','apkMovile@login')->name('login_server');
Route::get('/check','apkMovile@validate')->name('check');
Route::get('/logout', function(){ Auth::logout(); return response()->json(array('data' =>'ok'), 200); });
Route::get('/getPrestamos','apkMovile@getPrestamos')->name('getPrestamos');
Route::get('/getServicios','apkMovile@verServicios')->name('getServicios');
Route::get('/getAportes','apkMovile@verAportes')->name('getAportes');
Route::get('/getExAportes','apkMovile@extractoAportes')->name('getExAportes');
Route::get('/getimage','apkMovile@getimage')->name('getimage'); 
Route::get('sessionOut','SesionOut@out');

Route::group(['middleware' => ['webinterno']], function () { 
        Route::get ('/afi_beneficiario', 'AfiBeneficiarioController@index');
        Route::post('/afi_beneficiario/registrar', 'AfiBeneficiarioController@store');
        Route::post('/saveimagenBeneficiario', 'AfiBeneficiarioController@saveimagebene');
        Route::get ('/afi_beneficiario/listaBeneficiarios','AfiBeneficiarioController@listaBeneficiarios');
        Route::get ('/afi_beneficiario/socioResponsable','AfiBeneficiarioController@socioResponsable');
        Route::get ('/afi_beneficiario/verEsposa', 'AfiBeneficiarioController@verEsposa');
        Route::put ('/afi_beneficiario/actualizar', 'AfiBeneficiarioController@update');
        Route::put ('/afi_beneficiario/desactivar', 'AfiBeneficiarioController@desactivar');
        Route::put ('/afi_beneficiario/activar', 'AfiBeneficiarioController@activar');


        ////////////////////////////////////////////////////////////////////////////////////////////////////
        //contabilidad
        Route::get('/con_cuentasocio', 'ConCuentasocioController@index');
        Route::post('/con_cuentasocio/registrar', 'ConCuentasocioController@store');
        Route::put('/con_cuentasocio/actualizar', 'ConCuentasocioController@update');
        Route::put('/con_cuentasocio/desactivar', 'ConCuentasocioController@desactivar');
        Route::put('/con_cuentasocio/activar', 'ConCuentasocioController@activar');

        Route::get('/con_entidadbancaria', 'ConEntidadbancariaController@index');
        Route::post('/con_entidadbancaria/registrar', 'ConEntidadbancariaController@store');
        Route::put('/con_entidadbancaria/actualizar', 'ConEntidadbancariaController@update');
        Route::put('/con_entidadbancaria/desactivar', 'ConEntidadbancariaController@desactivar');
        Route::put('/con_entidadbancaria/activar', 'ConEntidadbancariaController@activar');
        Route::get('/con_entidadbancaria/selectEntidadbancaria', 'ConEntidadbancariaController@selectEntidadbancaria');

        Route::get('/con_tipocomprobante', 'ConTipocomprobanteController@index');
        Route::post('/con_tipocomprobante/registrar', 'ConTipocomprobanteController@store');
        Route::put('/con_tipocomprobante/actualizar', 'ConTipocomprobanteController@update');
        Route::put('/con_tipocomprobante/desactivar', 'ConTipocomprobanteController@desactivar');
        Route::put('/con_tipocomprobante/activar', 'ConTipocomprobanteController@activar');
        Route::get('/con_tipocomprobante/selectTipocomprobante', 'ConTipocomprobanteController@selectTipocomprobante');

        Route::get('/con_cuentan1', 'ConCuentanivel1Controller@index');
        Route::post('/con_cuentan1/registrar', 'ConCuentanivel1Controller@store');
        Route::put('/con_cuentan1/actualizar', 'ConCuentanivel1Controller@update');
        Route::put('/con_cuentan1/desactivar', 'ConCuentanivel1Controller@desactivar');
        Route::put('/con_cuentan1/activar', 'ConCuentanivel1Controller@activar');
        Route::get('/con_cuentan1/selectCuentanivel1', 'ConCuentanivel1Controller@selectCuentanivel1');
        Route::get('/con_cuentan1/selectValornivel1', 'ConCuentanivel1Controller@selectcodn1');

        Route::get('/con_cuentan2', 'ConCuentanivel2Controller@index');
        Route::post('/con_cuentan2/registrar', 'ConCuentanivel2Controller@store');
        Route::put('/con_cuentan2/actualizar', 'ConCuentanivel2Controller@update');
        Route::put('/con_cuentan2/desactivar', 'ConCuentanivel2Controller@desactivar');
        Route::put('/con_cuentan2/activar', 'ConCuentanivel2Controller@activar');
        Route::get('/con_cuentan2/selectCuentanivel2', 'ConCuentanivel2Controller@selectCuentanivel2');
        Route::get('/con_cuentan2/selectValornivel2', 'ConCuentanivel2Controller@selectValorn2');

        Route::get('/con_cuentan3', 'ConCuentanivel3Controller@index');
        Route::post('/con_cuentan3/registrar', 'ConCuentanivel3Controller@store');
        Route::put('/con_cuentan3/actualizar', 'ConCuentanivel3Controller@update');
        Route::put('/con_cuentan3/desactivar', 'ConCuentanivel3Controller@desactivar');
        Route::put('/con_cuentan3/activar', 'ConCuentanivel3Controller@activar');
        Route::get('/con_cuentan3/selectCuentanivel3', 'ConCuentanivel3Controller@selectCuentanivel3');
        Route::get('/con_cuentan3/selectValornivel3', 'ConCuentanivel3Controller@selectValorn3');

        Route::get('/con_cuentan4', 'ConCuentanivel4Controller@index');
        Route::post('/con_cuentan4/registrar', 'ConCuentanivel4Controller@store');
        Route::put('/con_cuentan4/actualizar', 'ConCuentanivel4Controller@update');
        Route::put('/con_cuentan4/desactivar', 'ConCuentanivel4Controller@desactivar');
        Route::put('/con_cuentan4/activar', 'ConCuentanivel4Controller@activar');
        Route::get('/con_cuentan4/selectCuentanivel4', 'ConCuentanivel4Controller@selectCuentanivel4');
        Route::get('/con_cuentan4/selectValornivel4', 'ConCuentanivel4Controller@selectValorn4');

        Route::get('/con_cuentan5', 'ConCuentanivel5Controller@index');
        Route::post('/con_cuentan5/registrar', 'ConCuentanivel5Controller@store');
        Route::put('/con_cuentan5/actualizar', 'ConCuentanivel5Controller@update');
        Route::put('/con_cuentan5/desactivar', 'ConCuentanivel5Controller@desactivar');
        Route::put('/con_cuentan5/activar', 'ConCuentanivel5Controller@activar');
        Route::get('/con_cuentan5/selectCuentanivel5', 'ConCuentanivel5Controller@selectCuentanivel5');
        Route::get('/con_cuentan5/selectValornivel5', 'ConCuentanivel5Controller@selectValorn5');

        Route::get('/con_cuentas', 'ConCuentasController@index');
        Route::post('/con_cuentas/registrar', 'ConCuentasController@store');
        Route::put('/con_cuentas/actualizar', 'ConCuentasController@update');
        Route::put('/con_cuentas/desactivar', 'ConCuentasController@desactivar');
        Route::put('/con_cuentas/activar', 'ConCuentasController@activar');
        Route::get('/con_cuentas/selectCuentas', 'ConCuentasController@selectCuentas');
        Route::get('/con_cuentas/selectBuscarcuenta', 'ConCuentasController@selectBuscarcuenta');
        Route::get('/con_cuentas/selectBuscarcuenta2', 'ConCuentasController@selectBuscarcuenta2');
        Route::get('/con_cuentas/selectcuentapto', 'ConCuentasController@selectCuentaspto');

        Route::get('/con_perfilcuentamaestro','ConPerfilcuentamaestroController@index');
        Route::get('/con_perfilcuentamaestro/reporte','ConPerfilcuentamaestroController@reporte');
        Route::get('/con_perfilcuentamaestro/selectPerfilcuentamaestro', 'ConPerfilcuentamaestroController@selectPerfilcuentamaestro');
        Route::get('/con_perfilcuentamaestro/selectPerfilcuentamaestro_contable', 'ConPerfilcuentamaestroController@selectPerfilcuentamaestro_contable');
        Route::get('/con_perfilcuentamaestro/selectmaestrofinalizado', 'ConPerfilcuentamaestroController@selectmaestrofinalizado');
        Route::get('/con_perfilcuentamaestro/getnamePerfilcuentamaestro', 'ConPerfilcuentamaestroController@getnamePerfilcuentamaestro');

        Route::post('/con_perfilcuentamaestro/registrar','ConPerfilcuentamaestroController@store');
        Route::put ('/con_perfilcuentamaestro/desactivar','ConPerfilcuentamaestroController@desactivar');
        Route::put ('/con_perfilcuentamaestro/activar','ConPerfilcuentamaestroController@activar');
        Route::put ('/con_perfilcuentamaestro/cambionombre','ConPerfilcuentamaestroController@cambionombre');
        Route::put ('/con_perfilcuentamaestro/actualizar','ConPerfilcuentamaestroController@update');
        Route::put ('/con_perfilcuentamaestro/finalizarcuenta','ConPerfilcuentamaestroController@finalizarcuenta');

        Route::get('/con_perfilcuentadetalle/selectcuentahaber', 'ConAsientodetalleController@selectCuentaHaber');
        Route::get('/con_perfilcuentamaestro/selectcuentatesoreria', 'ConPerfilcuentamaestroController@selectPerfilMaestroTesoreria');

        Route::get ('/con_asientomaestro','ConAsientomaestroController@index');
        Route::get ('/con_asientomaestro_getasientosmaestros_automatico','ConAsientomaestroController@getasientosmaestros_automatico');
        Route::put ('/con_asientomaestro/actualizar','ConAsientomaestroController@update');
        Route::get ('/con_asientomaestro/selectsubcuenta', 'ConAsientomaestroController@selectSubcuenta');
        Route::get ('/con_asientomaestro/selectsocio2', 'ConAsientomaestroController@selectSocio2');
        Route::post('/con_asientomaestro/registrar','ConAsientomaestroController@store');
        Route::post('/con_asientomaestro/revertir', 'ConAsientomaestroController@revertir');
        Route::put ('/con_asientomaestro/observar','ConAsientomaestroController@observar');
        Route::put ('/con_asientomaestro/validarborrador','ConAsientomaestroController@validarBorrador');
        Route::put ('/con_asientomaestro/eliminarborrador', 'ConAsientomaestroController@eliminarBorrador');
        Route::post('/con_asientomaestro/registrarccuenta','ConAsientomaestroController@storeccuenta');
        Route::get ('/con_asientomaestro/selectdesembolso', 'ConAsientomaestroController@selectdesembolso');
        Route::put ('/con_asientomaestro/desembolsar','ConAsientomaestroController@desembolsar');
        Route::put ('/con_asientomaestro/cobrar','ConAsientomaestroController@cobrar');
        Route::get ('/con_asientomaestro/agruparcomprobante', 'ConAsientomaestroController@agruparcomprobante');
        Route::post('/con_asientomaestro/registraragrupacion','ConAsientomaestroController@registraragrupacion');
        Route::put ('/con_asientomaestro/editarcabecera','ConAsientomaestroController@editarcabecera');
        Route::get ('/con_asientomaestro/recuperarcuenta', 'ConAsientomaestroController@recuperarCuenta');
        Route::get ('/con_asientomaestro/cobranza', 'ConAsientomaestroController@listarcobranza');
        

        

        Route::get ('/con_asientodetalle/selectasientodetalle', 'ConAsientodetalleController@selectAsientoDetalle');
        Route::get ('/con_asientodetalle/selectasientodetalle_2', 'ConAsientodetalleController@selectAsientoDetalle2');
        Route::get ('/con_asientomaestro/recuperarsubcuenta', 'ConAsientomaestroController@recuperarSubcuenta');

        Route::get ('/con_librocompras','ConLibrocompraController@index');
        Route::post('/con_librocompras/registrar','ConLibrocompraController@store');
        Route::put ('/con_librocompras/actualizar','ConLibrocompraController@update');
        Route::get ('/con_librocompras/verificarfactura','ConLibrocompraController@verificarfactura');
        Route::post ('/con_librocompras/desactivar', 'ConLibrocompraController@desactivar');

        Route::get ('/con_cierrelibrocompra','ConCierrelibrocompraController@index');
        Route::post('/con_cierrelibrocompra/registrar','ConCierrelibrocompraController@store');

        Route::get('/con_reportes', 'ConReportesController@rutas');

        Route::get('/con_config/cuentaslibros', 'ConConfiguracionController@cuentas_libros');
        Route::get('/con_config', 'ConConfiguracionController@index');
        Route::post('/con_config/registrar', 'ConConfiguracionController@store');
        Route::put('/con_config/desactivar', 'ConConfiguracionController@desactivar');
        Route::get('/con_config/cuentasconciliacion', 'ConConfiguracionController@cuentas_conciliacion');
        Route::get('/con_config/selectconciliacion', 'ConConfiguracionController@selectCuentasConciliacion');
        Route::post('/con_config/desactivardias', 'ConConfiguracionController@desactivarccdias');
        Route::get('/con_config/recuperarccdias', 'ConConfiguracionController@recuperarccdias');
        Route::get('/con_config/selectsubcuentaascinalss', 'ConConfiguracionController@selectSubCuentaAscinalss');
        

        Route::get ('/con_conciliacion/selectconciliacion', 'ConMovimientobancarioController@selectConciliacion');
        Route::post('/con_conciliacion/registrar', 'ConMovimientobancarioController@store');
        Route::get ('/con_conciliacion/segcheques', 'ConMovimientobancarioController@segCheques');
        Route::put ('/con_conciliacion/regcambio', 'ConMovimientobancarioController@registrarCambio');

        Route::get ('/con_segbancario','ConSegbancarioController@index');
        Route::post('/con_segbancario/registrar','ConSegbancarioController@store');
        Route::put ('/con_segbancario/actualizar','ConSegbancarioController@update');
        Route::get ('/con_segbancario/verificarfactura','ConSegbancarioController@verificarfactura');
        Route::put ('/con_segbancario/desactivar', 'ConSegbancarioController@desactivar');

        ////////////////////// REPORTES CONTABILIDAD////////////////////
        Route::get ('/libro_mayor', 'ConReportesController@libromayor');
        Route::get ('/balance_general', 'ConReportesController@balancegeneral');
        Route::get ('/libro_diario', 'ConReportesController@librodiario');
        Route::get ('/saldoconciliacion', 'ConReportesController@conciliacion');

        //////////////////////////////////////////////////////////////////////////////////////////
        Route::get ('/par_departamento', 'Par_DepartamentoController@index');
        Route::post('/par_departamento/registrar', 'Par_DepartamentoController@store');
        Route::put ('/par_departamento/actualizar', 'Par_DepartamentoController@update');
        Route::put ('/par_departamento/desactivar', 'Par_DepartamentoController@desactivar');
        Route::put ('/par_departamento/activar', 'Par_DepartamentoController@activar');
        Route::get ('/par_departamento/selectDepartamento', 'Par_DepartamentoController@selectDepartamento');
        Route::get ('/par_departamento/listaDepartamentos','Par_DepartamentoController@listaDepartamentos');//para filiales

        Route::get ('/par_municipio', 'Par_MunicipioController@index');
        Route::post('/par_municipio/registrar', 'Par_MunicipioController@store');
        Route::put ('/par_municipio/actualizar', 'Par_MunicipioController@update');
        Route::put ('/par_municipio/desactivar', 'Par_MunicipioController@desactivar');
        Route::put ('/par_municipio/activar', 'Par_MunicipioController@activar');
        Route::get ('/par_municipio/selectMunicipio', 'Par_MunicipioController@selectMunicipio');
        Route::get ('/par_municipio/listaMunicipios', 'Par_MunicipioController@listaMunicipios');

        Route::get ('/par_destino', 'ParDestinoController@index');
        Route::post('/par_destino/registrar', 'ParDestinoController@store');
        Route::put ('/par_destino/actualizar', 'ParDestinoController@update');
        Route::put ('/par_destino/desactivar', 'ParDestinoController@desactivar');
        Route::put ('/par_destino/activar', 'ParDestinoController@activar');
        Route::get ('/par_destino/selectDestino', 'ParDestinoController@selectDestino');

        Route::get('/par_fuerza', 'Par_FuerzaController@index');
        Route::post('/par_fuerza/registrar', 'Par_FuerzaController@store');
        Route::put('/par_fuerza/actualizar', 'Par_FuerzaController@update');
        Route::put('/par_fuerza/desactivar', 'Par_FuerzaController@desactivar');
        Route::put('/par_fuerza/activar', 'Par_FuerzaController@activar');
        Route::get('/par_fuerza/selectFuerza', 'Par_FuerzaController@selectFuerza');

        Route::get('/par_grado', 'Par_GradoController@index');
        Route::post('/par_grado/registrar', 'Par_GradoController@store');
        Route::put('/par_grado/actualizar', 'Par_GradoController@update');
        Route::put('/par_grado/desactivar', 'Par_GradoController@desactivar');
        Route::put('/par_grado/activar', 'Par_GradoController@activar');
        Route::get('/par_grado/selectGrado', 'Par_GradoController@selectGrado');

        Route::get('/par_especialidad', 'Par_EspecialidadController@index');
        Route::post('/par_especialidad/registrar', 'Par_EspecialidadController@store');
        Route::put('/par_especialidad/actualizar', 'Par_EspecialidadController@update');
        Route::put('/par_especialidad/desactivar', 'Par_EspecialidadController@desactivar');
        Route::put('/par_especialidad/activar', 'Par_EspecialidadController@activar');
        Route::get('/par_especialidad/selectEspecialidad', 'Par_EspecialidadController@selectEspecialidad');

        Route::get('/par_estadocivil', 'Par_EstadocivilController@index');
        Route::post('/par_estadocivil/registrar', 'Par_EstadocivilController@store');
        Route::put('/par_estadocivil/actualizar', 'Par_EstadocivilController@update');
        Route::put('/par_estadocivil/desactivar', 'Par_EstadocivilController@desactivar');
        Route::put('/par_estadocivil/activar', 'Par_EstadocivilController@activar');
        Route::get('/par_estadocivil/selectEstadocivil', 'Par_EstadocivilController@selectEstadocivil');

        Route::get('/par_escalafon', 'Par_EscalafonController@index');
        Route::post('/par_escalafon/registrar', 'Par_EscalafonController@store');
        Route::put('/par_escalafon/actualizar', 'Par_EscalafonController@update');
        Route::put('/par_escalafon/desactivar', 'Par_EscalafonController@desactivar');
        Route::put('/par_escalafon/activar', 'Par_EscalafonController@activar');
        Route::get('/par_escalafon/selectEscalafon', 'Par_EscalafonController@selectEscalafon');

        Route::get('/par_moneda', 'ParMonedaController@index');
        Route::post('/par_moneda/registrar', 'ParMonedaController@store');
        Route::put('/par_moneda/actualizar', 'ParMonedaController@update');
        Route::put('/par_moneda/desactivar', 'ParMonedaController@desactivar');
        Route::put('/par_moneda/activar', 'ParMonedaController@activar');
        Route::get('/par_moneda/selectMoneda', 'ParMonedaController@selectmoneda');

    Route::post('/par_documento/storeDocumento', 'ParDocumentoController@storeDocumento');     //C
    Route::get ('/par_documento/listaDocumentos','ParDocumentoController@listaDocumentos');    //R
    Route::put ('/par_documento/updateDocumento','ParDocumentoController@updateDocumento');    //U
    Route::put ('/par_documento/switchDocumento','ParDocumentoController@switchDocumento');    //D
    Route::get ('/par_documento/selectDocumento','ParDocumentoController@selectDocumento');    //R

    Route::post('/par_modulo/storeModulo','ParModuloController@storeModulo');       //C
    Route::get ('/par_modulo/listaModulos', 'ParModuloController@listaModulos');    //R
    Route::get ('/par_modulo/selectModulo', 'ParModuloController@selectModulo');    //R
    Route::get ('/par_modulo/selectModulo_contable', 'ParModuloController@selectModulocontable');    //R
    Route::put ('/par_modulo/updateModulo', 'ParModuloController@updateModulo');    //U
    Route::put ('/par_modulo/switchModulo', 'ParModuloController@switchModulo');    //D

        Route::get ('/sociogetfotoCR', 'SocioController@getfotoCR');
        Route::get ('/sociogetfotoCRV', 'SocioController@getfotoCRV');
        Route::get ('/sociogetfotoBENE', 'SocioController@getfotoBENE');

        Route::get ('/sociogetfotoCRV_cen', 'SocioController@getfotoCRV_cen');
        Route::get ('/sociogetfotoCRV_emp', 'SocioController@getfotoCRV_emp');

        Route::get ('/socio', 'SocioController@index');
        Route::post('/socio/registrar', 'SocioController@store');
        Route::post('/socio/upload', 'SocioController@update');
        Route::put ('/socio/actualizar', 'SocioController@update');
        Route::post('/socio/actualizarfoto', 'SocioController@updatefoto');
        Route::put ('/socio/desactivar', 'SocioController@desactivar');
        Route::put ('/socio/activar', 'SocioController@activar');
        Route::post('/socio/updateliquido', 'SocioController@updateliquido');
        Route::get ('/socio/listaSocios','SocioController@listaSocios');
        Route::get ('/socio/listaSociosCivil','SocioController@listaSociosCivil');
        Route::get ('/socio/verSocio','SocioController@verSocio');


        Route::get('/afi_reportes', 'Afi_Reportes@rutas');
        Route::get('/ser_reportes', 'Ser_Reportes@rutas');

        Route::get('/par_estadomodulo', 'Par_EstadomoduloController@index');
        Route::post('/par_estadomodulo/registrar', 'Par_EstadomoduloController@store');
        Route::put('/par_estadomodulo/actualizar', 'Par_EstadomoduloController@update');
        Route::put('/par_estadomodulo/desactivar', 'Par_EstadomoduloController@desactivar');
        Route::put('/par_estadomodulo/activar', 'Par_EstadomoduloController@activar');
        Route::get('/par_estadomodulo/selectEstado', 'Par_EstadomoduloController@selectEstado');
        Route::get('/par_estadomodulo/afiliacion', 'Par_EstadomoduloController@afiliacion');

        Route::get('/par_tiposocio', 'ParTiposocioController@index');
        Route::post('/par_tiposocio/registrar', 'ParTiposocioController@store');
        Route::put('/par_tiposocio/actualizar', 'ParTiposocioController@update');
        Route::put('/par_tiposocio/desactivar', 'ParTiposocioController@desactivar');
        Route::put('/par_tiposocio/activar', 'ParTiposocioController@activar');
        Route::get('/par_tiposocio/selectTiposocio', 'ParTiposocioController@selectTiposocio');



        //Route::get('/import_get', 'ImportController@getImport')->name('import');
        //Route::post('/import_parse', 'ImportController@parseImport')->name('import_parse');
        Route::post('/import_process', 'ImportController@processImport')->name('import_process');
        Route::get('/csvdata','CsvDataController@index');
        Route::get('/csvdata/selectcsvdata','CsvDataController@selectCsvdata');
        Route::get('/csvdata/selectlote','CsvDataController@selectLote');
        Route::post('/csvdata/fechaaporte','CsvDataController@revisarfechaaporte');
        Route::post('/csvdata/registrar', 'CsvDataController@store');
        //Route::get ('/asientomaestro','ImportController@guardarAsiento');


        Route::get('/apo_tipoaporte', 'ApoTipoaporteController@index');
        Route::post('/apo_tipoaporte/registrar', 'ApoTipoaporteController@store');
        Route::put('/apo_tipoaporte/actualizar', 'ApoTipoaporteController@update');
        Route::put('/apo_tipoaporte/desactivar', 'ApoTipoaporteController@desactivar');
        Route::put('/apo_tipoaporte/activar', 'ApoTipoaporteController@activar');
        Route::get('/apo_tipoaporte/selectTipoaporte', 'ApoTipoaporteController@selectTipoaporte');
        Route::get('/apo_reportes', 'ApoReportesController@rutas');
        Route::post('/apo_observados', 'ApoObservadosController@index');


    //// ACTIVOS FIJOS ACTIVOS FIJOS ACTIVOS FIJOS ACTIVOS FIJOS ACTIVOS FIJOS
    //// ACTIVOS FIJOS ACTIVOS FIJOS ACTIVOS FIJOS ACTIVOS FIJOS ACTIVOS FIJOS
    Route::post('/act_ambiente/storeAmbiente','ActAmbienteController@storeAmbiente');     //C
    Route::get ('/act_ambiente/listaAmbientes','ActAmbienteController@listaAmbientes');   //R
    Route::put ('/act_ambiente/updateAmbiente','ActAmbienteController@updateAmbiente');   //U
    Route::put ('/act_ambiente/switchAmbiente','ActAmbienteController@switchAmbiente');   //D

    Route::post('/act_grupo/storeGrupo' ,'ActGrupoController@storeGrupo');   //C
    Route::get ('/act_grupo/listaGrupos','ActGrupoController@listaGrupos');  //R
    Route::get ('/act_grupo/listaCuentas','ActGrupoController@listaCuentas');//R
    Route::put ('/act_grupo/updateGrupo','ActGrupoController@updateGrupo');  //U
    Route::put ('/act_grupo/switchGrupo','ActGrupoController@switchGrupo');  //D

    Route::post('/act_motivo/storeMotivo','ActMotivoController@storeMotivo');     //C
    Route::get ('/act_motivo/listaMotivos','ActMotivoController@listaMotivos');   //R
    Route::put ('/act_motivo/updateMotivo','ActMotivoController@updateMotivo');   //U
    Route::put ('/act_motivo/switchMotivo','ActMotivoController@switchMotivo');   //D

    Route::post('/act_auxiliar/storeAuxiliar','ActAuxiliarController@storeAuxiliar');    //C
    Route::get ('/act_auxiliar/listaAuxiliares','ActAuxiliarController@listaAuxiliares');//R
    Route::put ('/act_auxiliar/updateAuxiliar','ActAuxiliarController@updateAuxiliar');  //U
    Route::put ('/act_auxiliar/switchAuxiliar','ActAuxiliarController@switchAuxiliar');  //D

    Route::post('/act_activo/storeActivo','ActActivoController@storeActivo');   //C
    Route::get ('/act_activo/listaActivos','ActActivoController@listaActivos'); //R
    Route::get ('/act_activo/verActivo','ActActivoController@verActivo');       //R
    Route::put ('/act_activo/updateActivo','ActActivoController@updateActivo');//U
    Route::put ('/act_activo/storeBaja','ActActivoController@storeBaja');    //baja
    Route::get ('/act_activo/listaBajas','ActActivoController@listaBajas');  //baja
    Route::get ('/act_activo/validaAsignacion','ActActivoController@validaAsignacion');  //baja

    Route::get ('/act_activo/calcDepreciacion','ActActivoController@calcDepreciacion');

    Route::post('/act_asignacion/storeAsignacion','ActAsignacionController@storeAsignacion');     //C
    Route::get ('/act_asignacion/listaAsignaciones','ActAsignacionController@listaAsignaciones'); //R
    Route::put ('/act_asignacion/updateAsignacion','ActAsignacionController@updateAsignacion');   //U
    //Route::put ('/act_asignacion/activarAsignacion','ActAsignacionController@activarAsignacion'); //U

    Route::post('/act_ufv/cargarExcel','ActUfvController@cargarExcel'); //C
    Route::get ('/act_ufv/verUfv','ActUfvController@verUfv');           //R
    Route::get ('/act_ufv/maxverUfv','ActUfvController@maxverUfv');           //R
    Route::get ('/act_ufv/ufvGestion','ActUfvController@ufvGestion');   //R


    ///ALMACEN ALMACEN ALMACEN ALMACEN
    Route::get ('/alm_suministro/listaCuentas','AlmSuministroController@listaCuentas'); //R
    Route::put ('/alm_suministro/updateCuenta','AlmSuministroController@updateCuenta'); //U

    Route::post('/alm_suministro/storeSuministro','AlmSuministroController@storeSuministro');   //C
    Route::get ('/alm_suministro/listaSuministros','AlmSuministroController@listaSuministros'); //R
    Route::put ('/alm_suministro/updateSuministro','AlmSuministroController@updateSuministro'); //U
    Route::put ('/alm_suministro/switchSuministro','AlmSuministroController@switchSuministro'); //D

    Route::post('/alm_suministro/storeMedida','AlmSuministroController@storeMedida');   //C
    Route::get ('/alm_suministro/listaMedidas','AlmSuministroController@listaMedidas'); //R
    Route::put ('/alm_suministro/updateMedida','AlmSuministroController@updateMedida'); //U
    Route::put ('/alm_suministro/switchMedida','AlmSuministroController@switchMedida'); //D

    Route::post('/alm_entrada/storeEntrada','AlmEntradaController@storeEntrada');   //C
    Route::get ('/alm_entrada/listaEntradas','AlmEntradaController@listaEntradas'); //R
    Route::put ('/alm_entrada/updateEntrada','AlmEntradaController@updateEntrada'); //U
    Route::put ('/alm_entrada/switchEntrada','AlmEntradaController@switchEntrada'); //D

    Route::post('/alm_salida/storeSalida','AlmSalidaController@storeSalida');   //C
    Route::get ('/alm_salida/listaSalidas','AlmSalidaController@listaSalidas'); //R
    Route::put ('/alm_salida/updateSalida','AlmSalidaController@updateSalida'); //U
    Route::put ('/alm_salida/switchSalida','AlmSalidaController@switchSalida'); //D

    Route::get ('/act_activo/listaCuentas','ActActivoController@listaCuentas');

    Route::post('/alm_proveedor/storeProveedor','AlmProveedorController@storeProveedor');     //C
    Route::get ('/alm_proveedor/listaProveedores','AlmProveedorController@listaProveedores'); //R
    Route::put ('/alm_proveedor/updateProveedor','AlmProveedorController@updateProveedor');   //U
    Route::put ('/alm_proveedor/switchProveedor','AlmProveedorController@switchProveedor');   //D
    Route::get ('/alm_proveedor/searchProveedor','AlmProveedorController@searchProveedor');   //S
    Route::get ('/alm_proveedor/selectProveedor', 'AlmProveedorController@selectProveedor');//eddy
    Route::get ('/alm_proveedor/selectProveedor2', 'AlmProveedorController@selectProveedor2');//eddy

    //FILIALES Y OFICINAS FILIALES Y OFICINAS FILIALES Y OFICINAS FILIALES Y OFICINAS
    //FILIALES Y OFICINAS FILIALES Y OFICINAS FILIALES Y OFICINAS FILIALES Y OFICINAS
    Route::post('/fil_filial/storeFilial','FilFilialController@storeFilial');    //C
    Route::get ('/fil_filial/listaFiliales','FilFilialController@listaFiliales');//R
    Route::put ('/fil_filial/updateFilial','FilFilialController@updateFilial');  //U
    Route::put ('/fil_filial/switchFilial','FilFilialController@switchFilial');  //D

    Route::post('/fil_unidad/storeUnidad', 'FilUnidadController@storeUnidad');  //C
    Route::get ('/fil_unidad/listaUnidades','FilUnidadController@listaUnidades'); //R
    Route::put ('/fil_unidad/updateUnidad','FilUnidadController@updateUnidad'); //U
    Route::put ('/fil_unidad/switchUnidad','FilUnidadController@switchUnidad'); //D
    

    Route::post('/fil_directivo/storeDirectivo','FilDirectivoController@storeDirectivo');     //C
    Route::get ('/fil_directivo/listaDirectivos','FilDirectivoController@listaDirectivos');   //R
    Route::put ('/fil_directivo/updateDirectivo','FilDirectivoController@updateDirectivo');   //U
    Route::put ('/fil_directivo/switchDirectivo','FilDirectivoController@switchDirectivo');   //D

    Route::post('/fil_oficina/storeOficina','FilOficinaController@storeOficina');     //C
    Route::get ('/fil_oficina/listaOficinas','FilOficinaController@listaOficinas');   //R
    Route::put ('/fil_oficina/updateOficina','FilOficinaController@updateOficina');   //U
    Route::put ('/fil_oficina/switchOficina','FilOficinaController@switchOficina');   //D

    Route::get ('/fil_filial/selectFiliales','FilFilialController@selectFiliales');// Eddy
    

    ///// SERVICIOS SERVICIOS SERVICIOS SERVICIOS SERVICIOS SERVICIOS
    ///// SERVICIOS SERVICIOS SERVICIOS SERVICIOS SERVICIOS SERVICIOS

    //eddy servicios

    Route::get('/ser_servicio2', 'SerServicio2ControllerController@index');
    Route::get('/ser_establecimiento/listaEstablecimientos2','SerEstablecimientoController@listaEstablecimientos2'); 
    Route::get('/ser_ambientes/listarpisos','SerAmbienteController@listaPisos'); 
    Route::get('/ser_asignacion', 'SerAsignacionController@index');
    Route::get('/ser_asignacion/fechahora', 'SerAsignacionController@fechaHoraSistema'); 
    Route::put('/ser_asignacion/solicituddescuento', 'SerAsignacionController@soliciardescuento');
    Route::get('/ser_asignacion/autorizaciones', 'SerAsignacionController@autorizaciones');
    Route::put('/ser_asignacion/aprobarsolicitud', 'SerAsignacionController@aprobardescuento');
    Route::get ('/ser_civils/selectcivil','SerCivilController@selectCivil'); ///eddy 
    Route::put('/ser_asignacion/registrarsalida', 'SerAsignacionController@registrarsalida');
    Route::get ('/reporteingreso', 'SerAsignacionController@reporteingreso');
    Route::get ('/reportesalida', 'SerAsignacionController@reportesalida');
    Route::get ('/ser_civils/listarfamiliar','SerCivilController@listarFamiliar'); ///eddy 






    //ivan
    Route::post('/ser_servicio/storeServicio','SerServicioController@storeServicio');   //C
    Route::get ('/ser_servicio/listaServicios','SerServicioController@listaServicios'); //R
    Route::put ('/ser_servicio/updateServicio','SerServicioController@updateServicio'); //U
    Route::put ('/ser_servicio/switchServicio','SerServicioController@switchServicio'); //D

    Route::post('/ser_establecimiento/storeEstablecimiento', 'SerEstablecimientoController@storeEstablecimiento');  //C
    Route::get ('/ser_establecimiento/listaEstablecimientos','SerEstablecimientoController@listaEstablecimientos'); //R
    Route::put ('/ser_establecimiento/updateEstablecimiento','SerEstablecimientoController@updateEstablecimiento'); //U
    Route::put ('/ser_establecimiento/switchEstablecimiento','SerEstablecimientoController@switchEstablecimiento'); //D
    Route::put ('/ser_establecimiento/storeRequisitos','SerEstablecimientoController@storeRequisitos'); //U

    Route::post('/ser_ambiente/storeAmbiente', 'SerAmbienteController@storeAmbiente');  //C
    Route::get ('/ser_ambiente/listaAmbientes','SerAmbienteController@listaAmbientes'); //R
    Route::get ('/ser_ambiente/listaAmbientesSocio','SerAmbienteController@listaAmbientesSocio'); //R
    Route::put ('/ser_ambiente/updateAmbiente','SerAmbienteController@updateAmbiente'); //U
    Route::put ('/ser_ambiente/switchAmbiente','SerAmbienteController@switchAmbiente'); //D
    Route::put ('/ser_ambiente/storeBloque' ,  'SerAmbienteController@storeBloque');    //C
    Route::put ('/ser_ambiente/liberarAmbiente' ,'SerAmbienteController@liberarAmbiente');    //C

    Route::post('/ser_implemento/storeImplemento','SerImplementoController@storeImplemento');    //C
    Route::get ('/ser_implemento/listaImplementos','SerImplementoController@listaImplementos');  //R
    Route::put ('/ser_implemento/updateImplemento','SerImplementoController@updateImplemento');  //U
    Route::put ('/ser_implemento/switchImplemento','SerImplementoController@switchImplemento');  //D

    //// ASIGNACION DE SERVICIOS ASIGNACION DE SERVICIOS ASIGNACION DE SERVICIOS ASIGNACION DE SERVICIOS
    //// ASIGNACION DE SERVICIOS ASIGNACION DE SERVICIOS ASIGNACION DE SERVICIOS ASIGNACION DE SERVICIOS
    Route::post('/ser_asignacion/storeAsignacion','SerAsignacionController@storeAsignacion');       //C
    Route::get ('/ser_asignacion/listaAsignaciones','SerAsignacionController@listaAsignaciones');   //R
    Route::get ('/ser_asignacion/verAsignacion','SerAsignacionController@verAsignacion');           //R
    Route::put ('/ser_asignacion/updateAsignacion','SerAsignacionController@updateAsignacion');     //U
    Route::put ('/ser_asignacion/confirmaTraspaso','SerAsignacionController@confirmaTraspaso');
    Route::get ('/ser_asignacion/verCliente','SerAsignacionController@verCliente');
    Route::get ('/ser_asignacion/listarRegistrados','SerAsignacionController@listarRegistrados');
    Route::get ('/ser_asignacion/traspasoSocio','SerAsignacionController@traspasoSocio');
    Route::get ('/ser_asignacion/verifica','SerAsignacionController@verifica');

    Route::post('ser_civil/store','SerCivilController@store');
    Route::put ('ser_civil/update','SerCivilController@update');
    Route::get ('ser_civil/encontrar','SerCivilController@encontrar');
    Route::get ('ser_civil/ultimo','SerCivilController@ultimo');

    Route::get ('/ser_pago/listaPagos','SerPagoController@listaPagos');
    Route::get ('/ser_pago/verPago','SerPagoController@verPago');
    Route::post('/ser_pago/storePago','SerPagoController@storePago');
    Route::put ('/ser_pago/updatePago','SerPagoController@updatePago');

    //RECURSOS HUMANOS  RECURSOS HUMANOS  RECURSOS HUMANOS  RECURSOS HUMANOS  RECURSOS HUMANOS
    //RECURSOS HUMANOS  RECURSOS HUMANOS  RECURSOS HUMANOS  RECURSOS HUMANOS  RECURSOS HUMANOS
    Route::post('/rrh_empleado/storeEmpleado','RrhEmpleadoController@storeEmpleado');      //C
    Route::get ('/rrh_empleado/listaEmpleados','RrhEmpleadoController@listaEmpleados');    //R
    Route::get ('/rrh_empleado/selectempleados','RrhEmpleadoController@selectEmpleados');  //R
    Route::get ('/rrh_empleado/verEmpleado','RrhEmpleadoController@verEmpleado');          //R
    Route::get ('/rrh_empleado/verEmpleadopdf','RrhEmpleadoController@verEmpleadopdf');          //R
    Route::put ('/rrh_empleado/updateEmpleado','RrhEmpleadoController@updateEmpleado');    //U
    Route::put ('/rrh_empleado/switchEmpleado','RrhEmpleadoController@switchEmpleado');    //D
    Route::get ('/rrh_empleado/selectempleados2','RrhEmpleadoController@selectEmpleados2'); ///eddy
    Route::get ('/rrh_empleado/selectdirectivos','RrhEmpleadoController@selectDirectivos'); ///eddy
    Route::get ('/rrh_empleado/selectsocios','RrhEmpleadoController@selectSocios'); ///eddy


    Route::post('/rrh_presentado/storePresentado','RrhPresentadoController@storePresentado');   //C
    Route::get ('/rrh_presentado/listaPresentados','RrhPresentadoController@listaPresentados'); //R
    Route::put ('/rrh_presentado/updatePresentado','RrhPresentadoController@updatePresentado'); //U
    Route::put ('/rrh_presentado/switchPresentado','RrhPresentadoController@switchPresentado'); //D

    Route::post('/rrh_permiso/storePermiso','RrhPermisoController@storePermiso');   //C
    Route::get ('/rrh_permiso/listaPermisos','RrhPermisoController@listaPermisos'); //R
    Route::get ('/rrh_permiso/horasAcumuladasConGoce','RrhPermisoController@horasAcumuladasConGoce'); //R
    Route::get ('/rrh_permiso/horasAcumuladasSinGoce','RrhPermisoController@horasAcumuladasSinGoce'); //R
    Route::put ('/rrh_permiso/updatePermiso','RrhPermisoController@updatePermiso'); //U

    Route::post('/rrh_contrato/storeContrato','RrhContratoController@storeContrato');   //C
    Route::get ('/rrh_contrato/listaContratos','RrhContratoController@listaContratos'); //R
    Route::put ('/rrh_contrato/updateContrato','RrhContratoController@updateContrato'); //U
    Route::put ('/rrh_contrato/switchContrato','RrhContratoController@switchContrato'); //D

    Route::post('/rrh_formacion/storeFormacion','RrhFormacionController@storeFormacion');      //C
    Route::get ('/rrh_formacion/listaFormaciones','RrhFormacionController@listaFormaciones');  //R
    Route::put ('/rrh_formacion/updateFormacion','RrhFormacionController@updateFormacion');    //U
    Route::put ('/rrh_formacion/switchFormacion','RrhFormacionController@switchFormacion');    //D

    Route::post('/rrh_profesion/storeProfesion','RrhProfesionController@storeProfesion');      //C
    Route::get ('/rrh_profesion/listaProfesiones','RrhProfesionController@listaProfesiones');  //R
    Route::put ('/rrh_profesion/updateProfesion','RrhProfesionController@updateProfesion');    //U
    Route::put ('/rrh_profesion/switchProfesion','RrhProfesionController@switchProfesion');    //D

    Route::post('/rrh_cargo/storeCargo','RrhCargoController@storeCargo');      //C
    Route::get ('/rrh_cargo/listaCargos','RrhCargoController@listaCargos');    //R
    Route::put ('/rrh_cargo/updateCargo','RrhCargoController@updateCargo');    //U
    Route::put ('/rrh_cargo/switchCargo','RrhCargoController@switchCargo');    //D

    Route::post('/rrh_motivo/storeMotivo','RrhMotivoController@storeMotivo');   //C
    Route::get ('/rrh_motivo/listaMotivos','RrhMotivoController@listaMotivos'); //R
    Route::put ('/rrh_motivo/updateMotivo','RrhMotivoController@updateMotivo'); //U
    Route::put ('/rrh_motivo/switchMotivo','RrhMotivoController@switchMotivo'); //D

    Route::post('/rrh_seguro/storeSeguro','RrhSeguroController@storeSeguro');     //C
    Route::get ('/rrh_seguro/listaSeguros','RrhSeguroController@listaSeguros');   //R
    Route::put ('/rrh_seguro/updateSeguro','RrhSeguroController@updateSeguro');   //U
    Route::put ('/rrh_seguro/switchSeguro','RrhSeguroController@switchSeguro');   //D

    Route::post('/rrh_tipocontrato/storeTipocontrato','RrhTipocontratoController@storeTipocontrato');     //C
    Route::get ('/rrh_tipocontrato/listaTipocontratos','RrhTipocontratoController@listaTipocontratos');   //R
    Route::put ('/rrh_tipocontrato/updateTipocontrato','RrhTipocontratoController@updateTipocontrato');   //U
    Route::put ('/rrh_tipocontrato/switchTipocontrato','RrhTipocontratoController@switchTipocontrato');   //D

    Route::post('/rrh_referencia/storeReferencia','RrhReferenciaController@storeReferencia');     //C
    Route::get ('/rrh_referencia/listaReferencias','RrhReferenciaController@listaReferencias');   //R
    Route::put ('/rrh_referencia/updateReferencia','RrhReferenciaController@updateReferencia');   //U
    Route::put ('/rrh_referencia/switchReferencia','RrhReferenciaController@switchReferencia');   //D

    Route::get ('/rrh_marca/listaMarcas','RrhMarcaController@listaMarcas');
    Route::get ('/rrh_planilla/listaPlanillas','RrhPlanillaController@listaPlanillas');
    Route::post('/rrh_planilla/storePlanilla','RrhPlanillaController@storePlanilla');


    Route::post('/rrh_atraso/storeAtraso','RrhAtrasoController@storeAtraso');   //C
    Route::get ('/rrh_atraso/verAtraso','RrhAtrasoController@verAtraso');       //R
    Route::put ('/rrh_atraso/updateAtraso','RrhAtrasoController@updateAtraso'); //U
    Route::get ('/rrh_biometrico/getUser','RrhhBiometrico@getUser');
    Route::get ('/rrh_biometrico/getUsers','RrhhBiometrico@getUsers');
    Route::get ('/rrh_biometrico/setUsersdos','RrhhBiometrico@setUsersdos');
    Route::get ('/rrh_biometrico/getdatee','RrhhBiometrico@getdatee');
    Route::get ('/rrh_biometrico/getfotoBio','RrhhBiometrico@getfotoBio');



       Route::get ('/cuentacontable/selectCuentacontable', 'CuentacontableController@selectCuenta');
       Route::get ('/apo_estado','ApoEstadoController@index');
       Route::post('/apo_estado/registrar','ApoEstadoController@store');
       Route::put ('/apo_estado/actualizar','ApoEstadoController@update');
       Route::put ('/apo_estado/desactivar','ApoEstadoController@desactivar');
       Route::put ('/apo_estado/activar','ApoEstadoController@activar');
       Route::get ('/apo_estado/selectEstado', 'ApoEstadoController@selectEstado');

       Route::get ('/tipooperacion/selectTipooperacion', 'TipooperacionController@selectTipooperacion');

       Route::post('/con_perfilcuentadetalle', 'ConPerfilcuentadetalleController@index');
       Route::get ('/con_perfilcuentadetalle/selectPerfilcuentadetalle', 'ConPerfilcuentadetalleController@selectPerfilcuentadetalle');
       Route::get ('/con_perfilcuentadetalle/selectPerfilcuentadetalleProducto', 'ConPerfilcuentadetalleController@selectPerfilcuentadetalleProducto');
       Route::post('/con_perfilcuentadetalle/registrar','ConPerfilcuentadetalleController@store');


       Route::get ('/apo_aporte','ApoAporteController@index');
       Route::post('/apo_aporte/registrar','ApoAporteController@store');
       Route::get ('/apo_aporte/selectAporte', 'ApoAporteController@selectAporte');
       Route::get ('/apo_aporte/selectAporteDebito', 'ApoAporteController@selectAporteDebito');
       Route::put ('/apo_aporte/actualizar','ApoAporteController@update');
       Route::put ('/apo_aporte/eliminaraporte','ApoAporteController@eliminarAporte');


       Route::get ('/apo_totalaporte','ApoTotalAporteController@index');////////////// metodo para actualizar la fecha de primer y ultimo aporte con total obligados =0
       Route::get ('/apo_totalaporte/2','ApoTotalAporteController@index2');////////////// metodo para actualizar la fecha de primer y ultimo aporte con total obligados =1
       Route::get ('/apo_totalaporte/3','ApoTotalAporteController@index3');////////////// metodo para actualizar la fecha de primer y ultimo aporte con total obligados =1 y total obligatorios 0
       Route::get ('/apo_totalaporte/4','ApoTotalAporteController@index4');////////////// metodo para actualizar la fecha de primer y ultimo aporte con total obligados =1 y total obligatorios 1
       Route::get ('/apo_totalaporte/faltantes_obligados','ApoTotalAporteController@faltantes_obligados');////////////// metodo para buscar faltantes en los aportes
       Route::get ('/apo_totalaporte/faltantes_jubilacion','ApoTotalAporteController@faltantes_jubilacion');////////////// metodo para buscar faltantes en los aportes de jubilacion

       Route::post ('/apo_debito/registrar','ApoDebitoController@store');

       Route::get ('/par_producto','ParProductoController@index');
       Route::post('/par_producto/registrar','ParProductoController@store');
       Route::put ('/par_producto/actualizar','ParProductoController@update');
       Route::put ('/par_producto/actualizar/map','ParProductoController@updatemap');
       Route::put ('/par_producto/desactivar','ParProductoController@desactivar');
       Route::put ('/par_producto/activar','ParProductoController@activar');
       Route::get ('/par_producto/productos','ParProductoController@getproductos');
       Route::get ('/par_producto/productosid','ParProductoController@getproductosid');
       Route::get ('/par_producto/productosidLista','ParProductoController@getproductosidLista');
       Route::get ('/par_producto/getproductosid_tabla','ParProductoController@getproductosid_tabla');
       Route::get ('/par_producto/getproductosperfil','ParProductoController@getproductosperfil');



       Route::get ('/vista','PruebaViewController@index');

       Route::get ('/pre_listasocio','PreCalificacionController@pre_listasocio');
       Route::get ('/pre_listasocio2','PreCalificacionController@pre_listasocio_lista');
       Route::get ('/pre_listasocio_beneficiario','PreCalificacionController@pre_listasocio_lista_beneficiario');
       Route::get ('/pre_listasocio_prueba','PreCalificacionController@pre_listasocio_prueba');
       Route::get ('/datos_aportes','PreCalificacionController@datos_aportes');
       Route::get ('/par_producto/selectProducto', 'PreCalificacionController@selectProducto');
       Route::get ('/par_producto/cuentas', 'PreCalificacionController@selectCuentaBancarias');
       Route::get ('/getfechas', 'PreCalificacionController@getfechas');
       Route::get ('/getsaldocapital', 'PreCalificacionController@getsaldocapital');
       Route::get ('/getsaldocapital_desembolso', 'PreCalificacionController@getsaldocapital_desembolso');
       Route::get ('/pre_listasociogarante','PreCalificacionController@pre_listasociogarante');
       Route::get ('/reporte1','PreCalificacionController@reporte1');
       Route::get ('/plandepagos','PreCalificacionController@plandepagos');

       Route::get('/par_ventanamodulo/selectVentanamodulo', 'ParVentanamoduloController@selectVentanamodulo');


       Route::get ('/par_procesos_fatores/selectfactor','ParProductoFactoresController@selectfactores');
       Route::get ('/par_procesos_fatores/factores','ParProductoFactoresController@factores');
       Route::put ('/par_procesos_fatores/activar','ParProductoFactoresController@activar');
       Route::put ('/par_procesos_fatores/desactivar','ParProductoFactoresController@desactivar');
       Route::post('/par_procesos_fatores/registrar','ParProductoFactoresController@store');

        //administracion sistema
        Route::get ('/adm_log', 'AdmLogController@index');
        Route::get ('/adm_ventanamodulo', 'AdmVentanamoduloController@index');
        Route::post('/adm_ventanamodulo/registrar', 'AdmVentanamoduloController@store');
        Route::put ('/adm_ventanamodulo/actualizar', 'AdmVentanamoduloController@update');
        Route::put ('/adm_ventanamodulo/desactivar', 'AdmVentanamoduloController@desactivar');
        Route::put ('/adm_ventanamodulo/activar', 'AdmVentanamoduloController@activar');

        Route::get ('/adm_role', 'AdmRoleController@index');
        Route::post('/adm_role/registrar', 'AdmRoleController@store');
        Route::get ('/adm_role/rolepermiso', 'AdmRoleController@rolepermiso');
        Route::put ('/adm_role/actualizar', 'AdmRoleController@update');
        Route::put ('/adm_role/desactivar', 'AdmRoleController@desactivar');
        Route::put ('/adm_role/activar', 'AdmRoleController@activar');
        Route::get ('/adm_role/selectPermiso', 'AdmRoleController@selectPermiso');
        Route::post('/adm_role/registrar_rolepermiso', 'AdmRoleController@registrar_rolepermiso');
        Route::put ('/adm_role/actualizar_rolepermiso', 'AdmRoleController@actualizar_rolepermiso');
        Route::get ('/adm_role/permisosbase', 'AdmRoleController@permisosbase');
        Route::get ('/adm_role/selectPermisos', 'AdmRoleController@selectPermisos');

        Route::get ('/adm_permiso', 'AdmPermisoController@index');
        Route::post('/adm_permiso/registrar', 'AdmPermisoController@store');
        Route::put ('/adm_permiso/actualizar', 'AdmPermisoController@update');
        Route::put ('/adm_permiso/desactivar', 'AdmPermisoController@desactivar');
        Route::put ('/adm_permiso/activar', 'AdmPermisoController@activar');

        Route::get ('/adm_user', 'AdmUserController@index');
        Route::get ('/adm_user/roleuser', 'AdmUserController@roleuser');
        Route::post('/adm_user/registrar', 'AdmUserController@store');
        Route::put ('/adm_user/desactivar','AdmUserController@desactivar');
        Route::put ('/adm_user/activar','AdmUserController@activar');

        Route::get ('/adm_user/selectUsuario', 'AdmUserController@selectUsuario');
        Route::get ('/adm_user/selectRole', 'AdmUserController@selectRole');
        Route::post('/adm_user/registrar_roleuser', 'AdmUserController@registrar_roleuser');
        Route::put ('/adm_user/actualizar', 'AdmUserController@update');
        Route::put ('/adm_user/actualizar_roleuser', 'AdmUserController@actualizar_roleuser');




        Route::get ('/config/suma', 'ConfigController@sumatotal');
        Route::get ('/getColumnsFileInput', 'ConfigController@getColumnsFileInput');
        Route::get ('/par_prestamos_escalas/getescalas','ParPrestamosEscalaController@index');
        Route::get ('/getdate','ParFechaSistemaController@index');
        Route::get ('/getdatacalculo','ParFechaSistemaController@calculoplanpagos');
        Route::get ('/getcriterios', 'ProceduresController@criterio');
        Route::get ('/ip', 'ProceduresController@ip');

        //routes daaro
        Route::get ('/daa_tipodevolucion', 'DaaTipodevolucionController@index');
        Route::get ('/daa_tipodevolucion/selectValoraporte', 'DaaTipodevolucionController@selectValoraporte');
        Route::post('/daa_tipodevolucion/registrar', 'DaaTipodevolucionController@store');
        Route::put ('/daa_tipodevolucion/actualizar', 'DaaTipodevolucionController@update');
        Route::put ('/daa_tipodevolucion/desactivar', 'DaaTipodevolucionController@desactivar');
        Route::put ('/daa_tipodevolucion/activar', 'DaaTipodevolucionController@activar');


        Route::get('/daa_devolucion', 'DaaDevolucionController@index');
        Route::post('/daa_devolucion/regdaaro', 'DaaDevolucionController@store');
        Route::put('/daa_devolucion/editdaaro', 'DaaDevolucionController@update');
        Route::get('/daa_devolucion/selectTipodevolucion', 'DaaDevolucionController@selectTipodevolucion');
        Route::get('/daa_devolucion/calculomatematico', 'DaaDevolucionController@calculomatematico');
        Route::get('/daa_devolucion/selectDetalleaportes', 'DaaDevolucionController@selectDetalleaportes');
        Route::get ('/daa_devolucion/pre_listasociodev','DaaDevolucionController@pre_listasociodev');
        Route::get ('/daa_devolucion/devregistrado','DaaDevolucionController@devregistrado');
        Route::get ('/daa_devolucion/devregistradojub','DaaDevolucionController@devregistradojub');
        Route::get ('/daa_devolucion/validaconta','DaaDevolucionController@validaconta');
        Route::put ('/daa_devolucion/revertir','DaaDevolucionController@revertir');

        Route::get('/daa_estudiomatematico/selectEstudiomatematico', 'DaaEstudiomatematicoController@selectEstudiomatematico');
        Route::get('/daa_perfilcuenta/selectPerfilcuenta', 'DaaDevolucionController@selectPerfilcuenta');

        Route::get('/daa_devolucion/reportes', 'DaaDevolucionController@rutas');

       //routes presupuesto
       Route::get('/pto_pei', 'PtoPeiController@index');
       Route::post('/pto_pei/registrar', 'PtoPeiController@store');
       Route::put('/pto_pei/actualizar', 'PtoPeiController@update');
       Route::put('/pto_pei/desactivar', 'PtoPeiController@desactivar');
       Route::put('/pto_pei/activar', 'PtoPeiController@activar');
       Route::get('/pto_pei/selectPei', 'PtoPeiController@selectPei');
       Route::get('/pto_pei/selectObjestrategico', 'PtoPeiController@selectObjestrategico');

       Route::get('/pto_objestrategico', 'PtoObjestrategicoController@index');
       Route::post('/pto_objestrategico/registrar', 'PtoObjestrategicoController@store');
       Route::put('/pto_objestrategico/actualizar', 'PtoObjestrategicoController@update');
       Route::put('/pto_objestrategico/desactivar', 'PtoObjestrategicoController@desactivar');
       Route::put('/pto_objestrategico/activar', 'PtoObjestrategicoController@activar');

       Route::get('/pto_objgestion', 'PtoObjgestionController@index');
       Route::post('/pto_objgestion/registrar', 'PtoObjgestionController@store');
       Route::put('/pto_objgestion/actualizar', 'PtoObjgestionController@update');
       Route::put('/pto_objgestion/desactivar', 'PtoObjgestionController@desactivar');
       Route::put('/pto_objgestion/activar', 'PtoObjgestionController@activar');
       Route::get('/pto_objgestion/selectObjgestion', 'PtoObjgestionController@selectObjgestion');

       Route::get('/pto_partida', 'PtoPartidaController@index');
       Route::post('/pto_partida/registrar', 'PtoPartidaController@store');
       Route::put('/pto_partida/actualizar', 'PtoPartidaController@update');
       Route::put('/pto_partida/desactivar', 'PtoPartidaController@desactivar');
       Route::put('/pto_partida/activar', 'PtoPartidaController@activar');
       Route::get('/pto_partida/selectGestionPartida', 'PtoPartidaController@selectgestion');
       Route::put('/pto_partida/partidaCuentas', 'PtoPartidaController@partidaCuentas');

       Route::get('/pto_estructuraprog', 'PtoEstructuraprogController@index');
       Route::post('/pto_estructuraprog/registrar', 'PtoEstructuraprogController@store');
       Route::put('/pto_estructuraprog/actualizar', 'PtoEstructuraprogController@update');
       Route::put('/pto_estructuraprog/desactivar', 'PtoEstructuraprogController@desactivar');
       Route::put('/pto_estructuraprog/activar', 'PtoEstructuraprogController@activar');
       Route::get('/pto_estructuraprog/selectGestionEstructuraprog', 'PtoEstructuraprogController@selectgestion');

       Route::get('/pto_asignacion', 'PtoAsignacionController@index');
       Route::post('/pto_asignacion/registrar', 'PtoAsignacionController@store');
       Route::put('/pto_asignacion/actualizar', 'PtoAsignacionController@update');
       Route::put('/pto_asignacion/desactivar', 'PtoAsignacionController@desactivar');
       Route::put('/pto_asignacion/activar', 'PtoAsignacionController@activar');
       Route::get('/pto_asignacion/selectReparticion', 'PtoAsignacionController@selectReparticion');
       Route::get('/pto_asignacion/selectAsignacion', 'PtoAsignacionController@selectAsignacion');

       Route::get('/pto_estimacion', 'PtoEstimacionController@index');
       Route::post('/pto_estimacion/registrar', 'PtoEstimacionController@store');
       Route::put('/pto_estimacion/actualizar', 'PtoEstimacionController@update');
       Route::put('/pto_estimacion/desactivar', 'PtoEstimacionController@desactivar');
       Route::put('/pto_estimacion/activar', 'PtoEstimacionController@activar');
       Route::get('/pto_estimacion/selectReparticion', 'PtoEstimacionController@selectReparticion');




        //routes cobranza
        Route::get('/pre_cobranza/selectPrestamo', 'PreCobranzaController@selectPrestamo');
        Route::post('/daa_listaescalafon/registrar', 'DaaListaescalafonController@store');
        Route::put('/daa_listaescalafon/actualizar', 'DaaListaescalafonController@update');
        Route::put('/daa_listaescalafon/desactivar', 'DaaListaescalafonController@desactivar');
        Route::put('/daa_listaescalafon/activar', 'DaaListaescalafonController@activar');
        Route::get('/daa_listaescalafon/selectEscalafon', 'DaaListaescalafonController@selectEscalafon');


        Route::post('/registrarlista','ParPrestamosListaController@registrarlista');

        Route::get('/prestamos/getprestamosperiodo','ParPrestamosController@getprestamosperiodo');
        Route::get('/prestamos/getprestamostotal','ParPrestamosController@getprestamostotal');
        Route::get('/prestamos/desembolso','ParPrestamosController@prestamosDesembolso');
        Route::get('/prestamos/getprestamorefinan','ParPrestamosController@getprestamorefinan');
        Route::get('/prestamos/prestamoscambiogarante','ParPrestamosController@prestamoscambiogarante');
        Route::get('/prestamos/prestamoscobranzamanual','ParPrestamosController@prestamoscobranzamanual');
        Route::get('/prestamos/prestamosMoras','ParPrestamosController@prestamosMoras');
        Route::get('/prestamosEstatus','ParPrestamosController@prestamosEstatus');
        Route::post('/prestamos/regprestamo','ParPrestamosController@store');
        Route::post('/prestamos/regprestamoLista','ParPrestamosController@storeLista');
        Route::put('/prestamos/desembolsoupdate', 'ParPrestamosController@grabar_estado');
        Route::put('/prestamos/grabar_desembolsoNormal', 'ParPrestamosController@grabar_desembolsoNormal');
        Route::get('/start_desembolso', 'ParPrestamosController@desembolso');
        Route::get('/getprestamosEstado', 'ParPrestamosController@getprestamosEstado');
        Route::put ('/par_prestamos/eliminar','ParPrestamosController@delete_estado');
        Route::put ('/update_estado_observado','ParPrestamosController@update_estado_observado');
        Route::put ('/update_estado_revertir','ParPrestamosController@update_estado_revertir');
        Route::put ('/reversionPrestamos','ParPrestamosController@reversionPrestamos');
        Route::get ('/getprestamoRefi','ParPrestamosController@getprestamoRefi');
        Route::get ('/pruebare','ParPrestamosController@prueba');
        Route::get ('/get_status_reg','ParPrestamosController@get_status_reg');
        Route::post ('/start_refinanciamiento','ParPrestamosController@start_refinanciamiento');
        Route::post ('/start_cobranzamanual_total','ParPrestamosController@start_cobranzamanual_total');
        Route::get ('/start_cobranzamanual_total_validate','ParPrestamosController@start_cobranzamanual_total_validate');

        Route::get ('/liq_saldosmenores','LiqSaldosmenorController@index');
        Route::get ('/liq_saldosmenores/procesarsaldos','LiqSaldosmenorController@procesarsaldos');
        Route::post ('/liq_saldosmenores/liquidarsaldos','LiqSaldosmenorController@liquidarsaldos');
        Route::get ('/liq_saldosmenores/reportes', 'LiqSaldosmenorController@rutasLiquidar');

        Route::get ('/liq_acreedor', 'LiqAcreedorController@index');
        Route::get ('/liq_acreedor/index1', 'LiqAcreedorController@index1');
        Route::get ('/liq_acreedor/cobranza', 'LiqAcreedorController@cobranza');
        Route::put ('/liq_acreedor/cobra', 'LiqAcreedorController@cobra');
        Route::get ('/liq_acreedor/lista', 'LiqAcreedorController@lista');
        Route::put ('/liq_acreedor/devolucion', 'LiqAcreedorController@devolucion');
        Route::get ('/liq_acreedor/reportesdev', 'LiqAcreedorController@rutas');
        Route::get ('/liq_acreedor/procesarsaldosacreedor','LiqAcreedorController@procesarsaldosacreedor');
        Route::post ('/liq_acreedor/liquidaracreedores','LiqAcreedorController@liquidaracreedores');
        Route::get ('/liq_acreedor/reportes', 'LiqAcreedorController@rutasLiquidar');
        Route::get('/liq_reportes', 'LiqAcreedorController@reportesgeneral');

        Route::get('/getcriteriosgarantes', 'ProceduresController@criteriogarante');

        Route::post('/garantes/reggarante','ParPrestamoGaranteController@store');

        Route::post('/productosperfil/regperfil','ParProductosPerfilcuentaController@store');
        Route::post('/productosperfil/update','ParProductosPerfilcuentaController@update');
        Route::get('/getFormulas','ParProductosPerfilcuentaController@getFormulas');

        Route::post('/prestamos/Regplan','ParPrestamosPlanController@store');
        Route::put('/grabar_estado_plan','ParPrestamosPlanController@grabar_estado_plan');
        Route::post('/cobranzaascii','ParPrestamosPlanController@cobranza_ascii');
        Route::get('/get_cobranza_refinanciamiento','ParPrestamosPlanController@get_cobranza_refinanciamiento');
        Route::get('/getSendedAscii','ParPrestamosPlanController@getSendedAscii');
        Route::put ('/delete_planpagos','ParPrestamosPlanController@delete_planpagos');

        Route::get('/getlote/getstatus','ParPrestamosLoteController@get_lote_id');
        Route::get('/closeLote','ParPrestamosLoteController@closeLote');
        Route::get('/statusLote','ParPrestamosLoteController@statusLote');

        Route::get('/ascii' ,'File_Ascii@createFile');
        Route::get('/download' ,'File_Ascii@download');
        Route::get('/getFile' ,'File_Ascii@get');
        Route::get('/view' ,'File_Ascii@view');
        Route::get('/getcode' ,'CodeControl@getCode');

        /////////////////////////////////////////////////procedimientos/////////////////////
        Route::get ('/observadosaportes','buscarapoobservadosController@VerificaAportes');

        //   GLOBAL //////////////////////
        Route::get('/glo_solccuenta', 'GloSolicitudCargoCuentaController@index');
        Route::get('/glo_solccuenta/listarconta', 'GloSolicitudCargoCuentaController@listarconta');
        Route::post('/glo_solccuenta/registrar','GloSolicitudCargoCuentaController@store');
        Route::put('/glo_solccuenta/actualizar', 'GloSolicitudCargoCuentaController@update');
        Route::put('/glo_solccuenta/desactivar', 'GloSolicitudCargoCuentaController@desactivar');
        Route::put('/glo_solccuenta/observar','GloSolicitudCargoCuentaController@observar');
        Route::get('/glo_solccuenta/listarobs', 'GloSolicitudCargoCuentaController@listarobs');
        Route::put('/glo_solccuenta/actualizarsegccuenta', 'GloSolicitudCargoCuentaController@updatesegccuenta');
        Route::get('/glo_solccuenta/listartesoreria', 'GloSolicitudCargoCuentaController@listarTesoreria');
        Route::put('/glo_solccuenta/desembolsar', 'GloSolicitudCargoCuentaController@desembolsar');
        Route::put('/glo_solccuenta/agregarpersona', 'GloSolicitudCargoCuentaController@addpersona');
        
        
        Route::get('/doc_obligacion','GloSolicitudCargoCuentaController@docobligacion');

        //FACTURAS
        Route::get('/con_facturaparametro', 'ConFacturaParametroController@index');
        Route::post('/con_facturaparametro/registrar', 'ConFacturaParametroController@store');
        Route::put('/con_facturaparametro/actualizar', 'ConFacturaParametroController@update');
        Route::put('/con_facturaparametro/desactivar', 'ConFacturaParametroController@desactivar');
        Route::put('/con_facturaparametro/activar', 'ConFacturaParametroController@activar');

        Route::get('/con_factura', 'ConFacturaController@index');
        Route::get('/con_factura/dataparametro', 'ConFacturaController@dataparametro');
        Route::get('/con_factura/maxfactura', 'ConFacturaController@maxfactura');
        Route::get('/con_factura/verificarfactura', 'ConFacturaController@verificarfactura');
        Route::post('/con_factura/registrar', 'ConFacturaController@store');
        Route::put('/con_factura/actualizar', 'ConFacturaController@update');
        Route::put('/con_factura/desactivar', 'ConFacturaController@desactivar');
        Route::put('/con_factura/activar', 'ConFacturaController@activar');
        Route::post('/con_factura/cerrarmes', 'ConFacturaController@cerrarmes');
        Route::put('/con_contabilidad/updateDateCuentaComprobante', 'ConFacturaController@updateDateCuentaComprobante');
        Route::post('/con_contabilidad/proceso', 'ConFacturaController@proceso');
        Route::post('/con_contabilidad/procesoservicio', 'ConFacturaController@procesoservicio');
        Route::post('/con_contabilidad/procesoReserva', 'ConFacturaController@procesoReserva');
        Route::post('/con_contabilidad/procesoReserva2019', 'ConFacturaController@procesoReserva2019');
        Route::post('/con_contabilidad/procesoReserva2018', 'ConFacturaController@procesoReserva2018');
        Route::post('/con_contabilidad/procesoReservaReversion', 'ConFacturaController@procesoReservaReversion');
        Route::post('/con_contabilidad/procesoReservaUpdate', 'ConFacturaController@procesoReservaUpdate');
        Route::post('/con_contabilidad/procesoReservaUpdate2019', 'ConFacturaController@procesoReservaUpdate2019');
        Route::post('/con_contabilidad/procesoReservaUpdate2018', 'ConFacturaController@procesoReservaUpdate2018');
        Route::post('/con_contabilidad/procesoReservaUpdaterev', 'ConFacturaController@procesoReservaUpdaterev');
        Route::put ('/con_contabilidad/updateCuenta','ConFacturaController@updateCuentaComprobante');

        Route::get('/pruebaget','ConFacturaController@pruebaget');

        
        Route::get('/pdf','ConAsientomaestroController@datospdf');

        ///////////////////////////firmas autorizadas
        Route::get ('/con_firmasautorizadas','ConFirmaautorizadaController@index');
        Route::post('/con_firmasautorizadas/registrar','ConFirmaautorizadaController@store');
        Route::put ('/con_firmasautorizadas/desactivar','ConFirmaautorizadaController@desactivar');
        /*
        Route::put ('/con_firmasautorizadas/updatePago','SerPagoController@updatePago'); */
        

});