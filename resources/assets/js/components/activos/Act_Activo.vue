<template>
<main class="main">
    <div class="breadcrumb">
        <div class="col-md-6 titmodulo" style="padding:0px" >Activos > Catálogo</div>
        <div class="col-md-6 text-right">
            <div class="input-group-append" style="display:inline">
                <button class="btn btn-outline-success cui-options botonnav" 
                    data-toggle="dropdown" aria-expanded="false"></button>
                <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                    <a href="#" class="dropdown-item" @click="reporteActivos()"> Listado de Activos</a>
                    <a href="#" class="dropdown-item" > Detalle de Depreciaciones</a>
                    <a href="#" class="dropdown-item" @click="reporteCuentas()"> Resumen por cuenta</a>
                    <a href="#" class="dropdown-item" > Resumen Gerencial</a>
                    <a href="#" class="dropdown-item" > Asientos Contables</a>
                </div>
            </div>
        </div>        
    </div>

    
    <div class="container-fluid">
        <div class="card noprint"><!-- los filtros -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="tabla100">
                            <div class="tfila">
                                <div class="tcelda">Filial:</div>
                                <div class="tcelda">
                                    <select class="form-control" v-model="idfilial" 
                                        @change="listaOficinas(idfilial),listaActivos(idfilial,idambiente,idgrupo,idauxiliar)">
                                        <option v-for="filial in arrayFiliales" :key="filial.idfilial"
                                            v-text="filial.nommunicipio" :value="filial.idfilial"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="tabla100">                        
                            <div class="tfila">
                                <div class="tcelda">Ambiente:</div>
                                <div class="tcelda">
                                    <select class="form-control" v-model="idambiente" 
                                        @change="listaActivos(idfilial,idambiente,idgrupo,idauxiliar)">
                                        <option v-for="ambiente in arrayAmbientes" :key="ambiente.id"
                                            v-text="ambiente.nomambiente" :value="ambiente.idambiente"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="tabla100">                        
                            <div class="tfila">
                                <div class="tcelda">Cuenta:</div>
                                <div class="tcelda">
                                    <select class="form-control" v-model="idgrupo" 
                                        @change="listaAuxiliares(idgrupo),listaActivos(idfilial,idambiente,idgrupo,idauxiliar)">
                                        <option v-for="grupo in arrayGrupos" :key="grupo.id" 
                                            v-text="grupo.nomgrupo" :value="grupo.idgrupo"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="tabla100">                        
                            <div class="tfila">
                                <div class="tcelda">Auxiliar:</div>
                                <div class="tcelda">
                                    <select class="form-control" v-model="idauxiliar" 
                                        @change="listaActivos(idfilial,idambiente,idgrupo,idauxiliar)">
                                        <option value="">(todos)</option>
                                        <option v-for="auxiliar in arrayAuxiliares" :key="auxiliar.id" 
                                            v-text="auxiliar.nomauxiliar" :value="auxiliar.idauxiliar"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- los filtros -->

        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-5 titcard">
                        <div class="tablatit">
                            <div class="tcelda">
                                <span v-text="regAmbiente.nomambiente+' - '+regFilial.nommunicipio"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 titcard">
                        <div class="tablatit">
                            <div class="tcelda">
                                <span v-text="regGrupo.nomgrupo"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 text-right">
                        <button class="btn btn-primary" style="margin-top:0px" @click="nuevoActivo()">Nuevo Activo</button>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                <h4 v-if="idauxiliar" class="titsubrayado" v-text="regAuxiliar.nomauxiliar"></h4> 
                <table class="table  table-striped table-sm">
                    <thead class="tcabecera">
                        <tr>
                            <th><span class="badge badge-success noprint" v-text="arrayActivos.length+' items'"></span></th>
                            <th>Código</th>
                            <th v-if="!idauxiliar">Auxiliar</th>
                            <th>Descripción</th>
                            <th>Responsable</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="activo in arrayActivos" :key="activo.idactivo" :class="activo.activo?'':'txtdesactivado'">
                            <td v-if="activo.activo" align="center" nowrap >
                                <button class="btn btn-warning btn-sm icon-pencil" @click="editarActivo(activo)" title="Editar datos"></button>
                                <button class="btn btn-warning btn-sm icon-user-following" title="Asignar responsable"
                                    @click="$refs.subAsignacion.abrirModal(activo)"></button> 
                                <button class="btn btn-warning btn-sm icon-chart" @click="kardexActivo(activo)" title="Kárdex y Depreciación"></button>
                                <button class="btn btn-warning btn-sm icon-fire" @click="bajaActivo(activo)" title="Dar de baja"></button>
                                <button class="btn btn-danger  btn-sm icon-trash" @click="estadoActivo(activo)" title="Desactivar activo"></button>
                            </td>
                            <td v-else align="center">
                                <button class="btn btn-warning btn-sm icon-action-redo" @click="estadoActivo(activo)" title="Restaurar acttivo"></button>
                            </td>
                            <td v-text="activo.codactivo" align="center"></td>
                            <td v-if="!idauxiliar" v-text="activo.nomauxiliar"> </td>
                            <td><span v-text="activo.descripcion+'.'"></span>
                                <span v-if="activo.marca" v-text="' Marca: '+activo.marca"></span>
                                <span v-if="activo.serie" v-text="' Serie: '+activo.serie"></span>
                            </td>
                            <td>
                                <!-- <span v-text="listaAsignaciones(activo.idactivo)"></span> -->
                                <span v-text="quienUsa(activo.idactivo)"></span>
                            </td>
                            <!--
                            <td :align="verResponsable(activo.idactivo)?'left':'center'">
                                <span v-if="!verResponsable(activo.idactivo)" class="badge badge-danger">Sin Asignar</span>
                                <span v-else v-text="verResponsable(activo.idactivo)"></span>
                            </td> 
                            -->
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- REPORTE DEPRECIACIÓN POR CADA ACTIVO   REPORTE DEPRECIACIÓN POR CADA ACTIVO -->
        <!-- REPORTE DEPRECIACIÓN POR CADA ACTIVO   REPORTE DEPRECIACIÓN POR CADA ACTIVO -->
        <!--
        <div class="card" > 
            <div class="card-body">
                <table class="table  table-striped table-sm" >
                    <thead class="tcabecera">
                        <tr>
                            <th>Código</th>
                            <th>Detalle</th>
                            <th>Adquisición</th>
                            <th>UFV</th>
                            <th>Consumido</th>
                            <th>Costo</th>
                            <th>Incremento</th>
                            <th>Depreciación</th>
                            <th nowrap>Valor Neto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="activo in arrayActivos" :key="activo.idactivo">
                            <td v-text="activo.codactivo"></td>
                            <td v-text="activo.nomauxiliar+' '+activo.descripcion.substr(0,25)"></td>
                            <td v-text="jsfechas.fechames(activo.fechaingreso)"></td>
                            <td v-text="activo.ufvini"></td>
                            <td v-text="activo.diasusado" align="right"></td>
                            <td v-text="activo.costo" align="right"></td>
                            <td v-text="activo.incrtotal" align="right"></td>
                            <td v-text="activo.deprtotal" align="right"></td>
                            <td v-text="activo.valorneto" align="right"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        -->

        <!-- REPORTE DEPRECIACIÓN POR CUENTA CONTABLE REPORTE DEPRECIACIÓN POR CUENTA CONTABLE -->
        <!-- REPORTE DEPRECIACIÓN POR CUENTA CONTABLE REPORTE DEPRECIACIÓN POR CUENTA CONTABLE -->
        <!--
        <div class="card" >
            <div class="card-body">
                <div class="row">
                    <div class="col-md-9"></div>
                    <div class="col-md-3">
                        <div style="display:table; width:100%">
                            <div class="tcelda" style="vertical-align:middle">Fecha:</div>
                            <div class="tcelda">
                                <input type="date" class="form-control" v-model="fechahoy" 
                                @change="reporteCuentas(fechahoy)">
                            </div>
                        </div>
                    </div>
                </div><br>
                <table class="table table-bordered table-striped table-sm">
                    <thead class="tcabecera">
                        <tr>
                            <th>Código</th>
                            <th>Cuenta Contable</th>
                            <th>Depr. Anual</th>
                            <th>Costo Inicial<br>(Bs.)</th>
                            <th>Actualización<br>(Bs.)</th>
                            <th>Depr. Acumulada<br>(Bs.)</th>
                            <th>Valor Neto<br>(Bs.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="cuenta in arrayGrupos" :key="cuenta.idgrupo" align="right">
                            <td v-text="cuenta.codgrupo" align="center"></td>
                            <td v-text="cuenta.nomcuenta" align="left"></td>
                            <td v-text="round2dec(100/cuenta.vida)+'%'"></td>
                            <td v-text="jsfunc.formatomon(cuenta.costo)"></td> 
                            <td v-text="jsfunc.formatomon(cuenta.increm)"></td>
                            <td v-text="jsfunc.formatomon(cuenta.deprec)"></td>
                            <td v-text="jsfunc.formatomon(cuenta.valor)"></td>
                        </tr>
                    </tbody>
                </table>


            </div>
        </div>
        -->
    </div>

    <!-- REPORTE DE DEPRECIACION  REPORTE DE DEPRECIACION  REPORTE DE DEPRECIACION  REPORTE DE DEPRECIACION -->
    <!-- REPORTE DE DEPRECIACION  REPORTE DE DEPRECIACION  REPORTE DE DEPRECIACION  REPORTE DE DEPRECIACION -->
    <!--
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header titcard">Código <span v-text="regActivo.codactivo"></span></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                             <div class="tfila">
                                    <div class="tcelda titcampo nowrap">Cuenta Contable:</div>
                                    <div class="tcelda" v-text="regActivo.nomcuenta"></div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda titcampo">Auxiliar:</div>
                                    <div class="tcelda" v-text="regActivo.nomauxiliar"></div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda titcampo">Descripción:</div>
                                    <div class="tcelda" v-text="regActivo.descripcion"></div>
                                </div>
                                <div class="tfila" v-if="regActivo.obs">
                                    <div class="tcelda titcampo">Observaciones:</div>
                                    <div class="tcelda" v-text="regActivo.obs"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="tfila">
                                    <div class="tcelda titcampo">Marca:</div>
                                    <div class="tcelda" v-text="regActivo.marca"></div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda titcampo">Nr Serie:</div>
                                    <div class="tcelda" v-text="regActivo.serie"></div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda titcampo">Estado:</div>
                                    <div class="tcelda" v-text="regActivo.estado==1?'Bueno':regActivo.estado==2?'Regular':'Malo'">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header titcard">Asignación</div>
                    <div class="card-body">
                        <div class="tfila">
                            <div class="tcelda titcampo">Filial:</div>
                            <div class="tcelda" v-text="regActivo.nommunicipio" ></div>
                        </div>                        
                        <div class="tfila">
                            <div class="tcelda titcampo">Oficina:</div>
                            <div class="tcelda" v-text="regActivo.nomambiente"></div>
                        </div>                        
                        <div class="tfila">
                            <div class="tcelda titcampo">Responsable:</div>
                            <div class="tcelda">
                                <span v-if="!verResponsable(regActivo.idactivo)" class="badge badge-danger">Sin Asignar</span>
                                <span v-else v-text="verResponsable(regActivo.idactivo)"></span>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header titcard">
                <div class="row">
                    <div class="col-md-6 titcard">
                        <div class="tabla100">
                            <div class="tcelda">Cálculo de Depreciación</div>
                        </div>
                    </div>
                    <div class="col-md-6 text-right">
                        <button class="btn btn-primary icon-printer" style="margin-top:0px" 
                        title="Reporte PDF" @click="reporteDepreciaciones(regActivo.idactivo)"></button>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                <div class="row">
                    <div class="col-md-2 nowrap">
                        <span class="titcampo">Compra:</span><span v-text="jsfechas.fechames(regActivo.fechaingreso)"> </span> 
                    </div>
                    <div class="col-md-2">
                        <span class="titcampo">UFV:</span><span v-text="regActivo.ufvini"></span>
                    </div>
                    <div class="col-md-2">
                        <span class="titcampo">Costo:</span><span v-text="regActivo.costo+'Bs.'"></span>
                    </div>
                    <div class="col-md-2">
                        <span class="titcampo">V.Residual:</span><span v-text="regActivo.residual+'Bs.'"></span>
                    </div>
                    <div class="col-md-2">
                        <span class="titcampo">Coeficiente:</span><span v-text="regActivo.coeficiente+'%'"></span>
                    </div>
                    <div class="col-md-2 text-right">
                        <span class="titcampo">Vida útil:</span><span v-text="regActivo.vida+'años'"></span>
                    </div>
                </div>
                <h5 class="titsubrayado">Drepreciación Anual</h5>
                <table class="table table-bordered table-sm table-striped">
                    <thead>
                        <tr style="background-color:#e4e7ea">
                            <th class="text-center">Gestión</th>
                            <th class="text-center">UFV Inicio</th>
                            <th class="text-center">UFV Cierre</th>
                            <th class="text-center">Consumido</th>
                            <th class="text-center">Saldo de vida</th>
                            <th class="text-center">Incremento Anual (+)</th>
                            <th class="text-center">Depreciación Anual</th>
                            <th class="text-center">Depreciación Acumulada (-)</th>
                            <th class="text-center">Valor final</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="depreciacion in arrayDepreciaciones" :key="depreciacion.id" align="center">
                            <td v-text="depreciacion.gestion"></td> 
                            <td v-text="depreciacion.ufvini"></td> 
                            <td v-text="depreciacion.ufvfin"></td>
                            <td v-text="depreciacion.consumido"></td>
                            <td v-text="depreciacion.saldovida"></td>
                            <td v-text="jsfunc.formatomon(depreciacion.incranual)" align="right"></td>
                            <td v-text="jsfunc.formatomon(depreciacion.depranual)" align="right"></td>
                            <td v-text="jsfunc.formatomon(depreciacion.depracum)"  align="right"></td>
                            <td v-text="jsfunc.formatomon(depreciacion.valorfin)"  align="right"></td>
                            
                        </tr>
                    </tbody>
                </table>

                <h5 class="titsubrayado">Depreciación a la fecha</h5>

                <table class="table table-bordered table-sm ">
                    <thead>
                        <tr style="background-color:#e4e7ea">
                            <th class="text-center">Fecha</th>
                            <th class="text-center">UFV Inicio</th>
                            <th class="text-center">UFV Cierre</th>
                            <th class="text-center">Consumido</th>
                            <th class="text-center">Saldo de vida</th>
                            <th class="text-center">Incremento Total</th>
                            <th class="text-center">Depreciación Total</th>
                            <th class="text-center">Valor Final</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr align="center">
                            <td>
                                <div class="tfila">
                                    <div class="tcelda">Al:&nbsp;</div>
                                    <div class="tcelda">
                                        <input type="date" class="form-control"  v-model="fechahoy"
                                        @change="depreciacionActual(regActivo,fechahoy)">
                                    </div>
                                </div>
                            </td>
                            <td v-text="regDepreciacion.ufvini"></td>
                            <td v-text="regDepreciacion.ufvfin"></td>
                            <td v-text="regDepreciacion.consumido"></td>
                            <td v-text="regDepreciacion.saldovida"></td>
                            <td v-text="jsfunc.formatomon(regDepreciacion.incrtotal)"></td>
                            <td v-text="jsfunc.formatomon(regDepreciacion.deprtotal)"></td>
                            <td v-text="jsfunc.formatomon(regDepreciacion.valorfin)" class="titcampo"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>                
            
        
    </div>
    -->


    <!-- MODAL ACTIVO MODAL ACTIVO MODAL ACTIVO MODAL ACTIVO MODAL ACTIVO MODAL ACTIVO MODAL ACTIVO MODAL ACTIVO-->
    <!-- MODAL ACTIVO MODAL ACTIVO MODAL ACTIVO MODAL ACTIVO MODAL ACTIVO MODAL ACTIVO MODAL ACTIVO MODAL ACTIVO-->
    <div class="modal" :class="modalActivo?'mostrar':''" >
        <div class="modal-dialog modal-primary modal-lg">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title"><span v-text="accion==1?'Nuevo':'Modificar'"></span> Activo</h4>
                    <button type="button" class="close" @click="modalActivo=0">×</button>
                </div>
                <div class="modal-body">
                    <div class="row" style="overflow-y:scroll; height:400px">
                        <div class="col-md-4">
                            <h5 class="titazul">Identificación</h5>
                            <div class="table">
                                <div class="tfila">
                                    <div class="tcelda titcampo" style="vertical-align:top">Filial:</div>
                                    <div class="tcelda" style="text-transform:uppercase" v-text="regFilial.nommunicipio"></div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda titcampo" style="vertical-align:top">Oficina:</div>
                                    <div class="tcelda" v-text="regAmbiente.nomambiente"></div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda titcampo" style="vertical-align:top">Cuenta:</div>
                                    <div class="tcelda" v-text="regGrupo.nomgrupo"></div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda titcampo" style="vertical-align:top">Auxiliar:</div>
                                    <div class="tcelda" v-text="regAuxiliar.nomauxiliar"></div>
                                </div>
                            </div>
                            <center>
                                <span class="titcampo">Código <span v-if="accion==1">asignado</span>:</span> 
                                <div class="tcelda" style="padding:0px 4px">
                                    <span class="titsubrayado">ASC</span><br>inst
                                </div>
                                <div class="tcelda" style="padding:0px 4px">
                                    <span class="titsubrayado" v-text="regFilial.codfilial"></span><br>filial
                                </div>
                                <div class="tcelda" style="padding:0px 4px">
                                    <span class="titsubrayado" v-text="regAmbiente.codambiente"></span><br>ofic
                                </div>
                                <div class="tcelda" style="padding:0px 4px">
                                    <span class="titsubrayado" v-text="regGrupo.codgrupo"></span><br>cta
                                </div>
                                <div class="tcelda" style="padding:0px 4px">
                                    <span class="titsubrayado" v-text="regAuxiliar.codauxiliar"></span><br>aux
                                </div>
                                <div class="tcelda" style="padding:0px 4px">
                                    <span class="titsubrayado" v-text="correlativo"></span><br>correl
                                </div>
                            </center>
                        </div>
                        <div class="col-md-4">
                            <h5 class="titazul">Descripción</h5>
                            <center v-text="regAuxiliar.nomauxiliar"></center>
                            <textarea rows="3" class="form-control" style="resize:none" v-model="descripcion" 
                                placeholder="(color, dimensiones, etc)"></textarea>
                            Marca-Modelo:
                            <input type="text" class="form-control" v-model="marca">
                            Nro de Serie:
                            <input type="text" class="form-control" v-model="serie">
                        </div>
                        <div class="col-md-4">
                            <h5 class="titazul">Imagen</h5>
                            <img src="img/activos/general.jpg" :alt="regAuxiliar.nomauxiliar">
                            <center><button class="btn btn-primary">Cargar Imagen</button></center>
                        </div>
                        <div class="col-md-12"><h5 class="titazul">Detalle de la compra</h5></div>
                        <div class="col-md-4">
                            Fecha de adquisición: 
                            <input type="date" class="form-control" v-model="fechaingreso">
                            Costo: 
                            <div class="input-group">
                                <input type="text" v-model="costo" class="form-control text-right">
                                <div class="input-group-append"><span class="input-group-text">Bs.</span></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            Factura:
                            <input type="text" v-model="idcompra" class="form-control text-right">
                            <!--
                            Proveedor: JOTA IMPRENTA – OFFSET 
                            <br>tEL: 2202275-->
                        </div>
                        <div class="col-md-4">
                            Observaciones:
                            <textarea rows="3" class="form-control" style="resize:none" v-model="obs" ></textarea>
                        </div>

                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button class="btn btn-secondary" @click="modalActivo=0">Cerrar</button>
                    <button class="btn btn-primary" @click="accion==1?storeActivo():updateActivo()">
                        Guardar <span v-if="accion==2">Modificaciones</span></button>
                </div>
            </div>
        </div>
    </div> 
    

    <!-- MODAL BAJA MODAL BAJA MODAL BAJA MODAL BAJA MODAL BAJA MODAL BAJA MODAL BAJA MODAL BAJA-->
    <!-- MODAL BAJA MODAL BAJA MODAL BAJA MODAL BAJA MODAL BAJA MODAL BAJA MODAL BAJA MODAL BAJA-->
    <!--
    <div class="modal" :class="modalBaja?'mostrar':''" >
        <div class="modal-dialog modal-primary modal-lg">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title">Baja de Activo</h4>
                    <button type="button" class="close" @click="modalBaja=0">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="titazul">Información</h5>
                            <div class="tfila">
                                <div class="tcelda titcampo">Código:</div>
                                <div class="tcelda" v-text="regActivo.codactivo"></div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Título:</div>
                                <div class="tcelda" v-text="regActivo.nomauxiliar"></div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Descripción:</div>
                                <div class="tcelda" v-text="regActivo.descripcion"></div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Adquisición:</div>
                                <div class="tcelda" v-text="(regActivo.fechaingreso)"></div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Filial:</div>
                                <div class="tcelda" v-text="regActivo.nommunicipio"></div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Oficina:</div>
                                <div class="tcelda" v-text="regActivo.nomambiente"></div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Costo Inicial:</div>
                                <div class="tcelda" v-text="regActivo.costo+'Bs.'"></div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo nowrap">Tiempo Consumido:</div>
                                <div class="tcelda"><span v-text="regDepreciacion.consumido"></span></div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Incremento:</div>
                                <div class="tcelda"> {{formatomon(regDepreciacion.incrtotal)}}Bs.</div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Depreciación:</div>
                                <div class="tcelda"> {{formatomon(regDepreciacion.deprtotal)}}Bs.</div> 
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Valor Final:</div>
                                <div class="tcelda"> {{formatomon(regDepreciacion.valorfin)}}Bs.</div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <h5 class="titazul">Registro de Baja</h5>
                            <div class="tfila">
                                <div class="tcelda" style="vertical-align:middle">Fecha:</div>
                                <div class="tcelda" style="padding:5px 0px">
                                    <input type="date" class="form-control" v-model="fechabaja">
                                </div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda" style="vertical-align:middle">Nro. Orden:</div>
                                <div class="tcelda" style="padding:5px 0px">
                                    <input v-model="nrorden" type="text" class="form-control" >
                                </div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda" style="vertical-align:middle">Motivo:</div>
                                <div class="tcelda" > 
                                    <select v-model="idmotivo" class="form-control">
                                        <option v-for="motivo in arrayMotivos" :key="motivo.idmotivo"
                                            :value="motivo.idmotivo" v-text="motivo.nommotivo"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda" style="vertical-align:middle">Obervaciones:&nbsp;</div>
                                <div class="tcelda" style="padding:5px 0px">
                                    <textarea class="form-control" style="resize:none" rows="2" v-model="obsbaja">
                                    </textarea></div>
                            </div>                             
                        </div>

                    </div>

                </div>
                <div class="modal-footer text-center">
                    <button class="btn btn-secondary" @click="modalBaja=0">Cerrar</button>
                    <button v-if="accion==1" class="btn btn-primary" @click="storeBaja(regActivo)">Dar de Baja </button>
                    <button v-if="accion==2" class="btn btn-primary" @click="updateActivo()">Guardar modificaciones</button>
                </div>
            </div>
        </div>

    </div> 
    -->
    <subAsignacion ref="subAsignacion" @cerrarAsignacion="listaActivos(idfilial,idambiente,idgrupo,idauxiliar)"></subAsignacion>    

</main>    
</template>

<script>

Vue.component('subAsignacion',require('./subAsignacion.vue').default);

import * as jsfechas from '../../fechas.js';
import * as jsfunc from '../../funciones.js';
import * as reporte from '../../functions.js';

export default {
    data(){ return {
        jsfechas:'', jsfunc:'', rutas:[], ipbirt:'',
        //divDepreciacion:0, 
        modalActivo:0, modalBaja:0, accion:1, 
        arrayFiliales:[], arrayAmbientes:[], arrayGrupos:[], arrayAuxiliares:[], 
        arrayActivos:[], arrayEmpleados:[], arrayDepreciaciones:[], 
        arrayMotivos:[], arrayAsignaciones:[], 
        regFilial:[], regAmbiente:[], regGrupo:[], regAuxiliar:[], regActivo:[], // regDepreciacion:[],
        idfilial:1, idambiente:1, idgrupo:2, idauxiliar:'',
        codactivo:'', descripcion:'', marca:'', serie:'', correlativo:'',
        idcompra:'', fechaingreso:'', costo:'', residual:0, obs:'', 

        //divFormulario:0,idresponsable:'', tiporesponsable:'', fechaini:'', fechafin:'', estadoini:'', estadofin:'',

        //fechahoy:'', arrayUfvini:[], arrayUfvfin:[],
        baja:'', fechabaja:'', nrorden:'', idmotivo:'', obsbaja:'',
        //arrayEstados:['','Bueno','Regular','Malo'],
    }},

    methods:{
        listaFiliales(){
            var url='/fil_filial/listaFiliales?activo=1';
            axios.get(url).then(response=>{
                this.arrayFiliales=response.data.filiales;
                this.verFilial(this.idfilial);
            })
        },

        listaAmbientes(idfilial){
            var url='/act_ambiente/listaAmbientes?idfilial='+idfilial+'&idactivo=1';
            axios.get(url).then(response=>{
                this.arrayAmbientes=response.data.ambientes;
                this.verAmbiente(this.idambiente);
            });
        },

        listaGrupos(){
            var url='/act_grupo/listaGrupos?activo=1';
            axios.get(url).then(response=>{
                this.arrayGrupos=response.data.grupos;
                this.verGrupo(this.idgrupo);
            });
        },

        listaAuxiliares(idgrupo){
            var url='/act_auxiliar/listaAuxiliares?idgrupo='+idgrupo+'&activo=1';
            axios.get(url).then(response=>{
                this.arrayAuxiliares=response.data.auxiliares;
            });
        },

        listaActivos(idfilial,idambiente,idgrupo,idauxiliar){
            if(this.arrayFiliales.length) this.verFilial(idfilial);
            if(this.arrayAmbientes.length) this.verAmbiente(idambiente);
            if(this.arrayGrupos.length)  this.verGrupo(idgrupo);
            if(this.arrayAuxiliares.length)this.verAuxiliar(idauxiliar);
            var url='/act_activo/listaActivos?idfilial='+idfilial+'&idambiente='+idambiente+'&idgrupo='+idgrupo;
            if(idauxiliar) url+='&idauxiliar='+idauxiliar;
            axios.get(url).then(response=>{
                this.arrayActivos=response.data.activos;
                this.ipbirt=response.data.ipbirt;
            });
            //let response=await axios.get(url);
            //if(!response.data.activos) return;
            //this.arrayActivos=response.data.activos;
            //url='/act_asignacion/listaResponsables?idfilial='+idfilial+'&idambiente='+idambiente+'&idgrupo='+idgrupo;
            //response=await axios.get(url);
            //this.arrayResponsables=response.data.responsables;
            //url='act_ufv/verUfv?fecha='+this.arrayActivos[0].currfecha;
            //response=await axios.get(url);
            //this.ufvfin=response.data.ufvfecha[0].valor;

               /*
                for(var i=0;i<this.arrayActivos.length;i++){
                    var diasusado=this.diastransc(this.arrayActivos[i].fechaingreso,this.arrayActivos[i].currfecha);
                    //this.arrayActivos[i].diasusado=diasusado;
                    this.arrayActivos[i].diasusado=this.tiempotransc(this.arrayActivos[i].fechaingreso,this.arrayActivos[i].currfecha);
                    this.arrayActivos[i].deprtotal=this.round2dec(1/(this.arrayActivos[i].vida*365)*diasusado*this.arrayActivos[i].costo);
                    this.arrayActivos[i].incrtotal=this.round2dec((this.ufvfin/this.arrayActivos[i].ufvini)*this.arrayActivos[i].costo);
                    this.arrayActivos[i].valorneto=this.round2dec(this.arrayActivos[i].costo+this.arrayActivos[i].incrtotal-this.arrayActivos[i].deprtotal);
                }
                */
                
        },
        /*
        listaEmpleados(idambiente){
            var url='/rrh_empleado/listaEmpleados?idambiente='+idambiente;
            let me=this;
            axios.get(url).then(function(response){
                me.arrayEmpleados=response.data.empleados;
            });
        },
        */
        /*
        listaMotivos(){
            let me=this;
            axios.get('/act_activo/listaMotivos').then(function(response){
                me.arrayMotivos=response.data.motivos;
            })
        },
        */
        
        listaAsignaciones(idactivo){
            var url='/act_asignacion/listaAsignaciones?idactivo='+idactivo+'&activo=1';
            axios.get(url).then(response=>{
                this.arrayAsignaciones=response.data.asignaciones;
            });
        },

        quienUsa(){
            return;
        },
        

        verFilial(idfilial){
            for(var i=0; i<this.arrayFiliales.length; i++)
                if(this.arrayFiliales[i].idfilial==idfilial){
                    this.regFilial=this.arrayFiliales[i]; return; }
        },

        verAmbiente(idambiente){
            for(var i=0;i<this.arrayAmbientes.length;i++)
                if(this.arrayAmbientes[i].idambiente==idambiente){
                    this.regAmbiente=this.arrayAmbientes[i]; return; }
        },

        verGrupo(idgrupo){
            for(var i=0;i<this.arrayGrupos.length;i++)
                if(this.arrayGrupos[i].idgrupo==idgrupo){
                    this.regGrupo=this.arrayGrupos[i]; return;  }
        },

        verAuxiliar(idauxiliar){
            for(var i=0;i<this.arrayAuxiliares.length;i++)
                if(this.arrayAuxiliares[i].idauxiliar==idauxiliar){
                    this.regAuxiliar=this.arrayAuxiliares[i]; return; }
        },

        /*
        verResponsable(idactivo){
            for(var i=0; i<this.arrayResponsables.length; i++)
                if(this.arrayResponsables[i].idactivo==idactivo){
                   return this.arrayResponsables[i].nomresponsable; 
                }
            return false;
        },
        */

        generarCodigo(){
            this.verFilial(this.idfilial);
            this.verAmbiente(this.idambiente);
            this.verGrupo(this.idgrupo);
            this.verAuxiliar(this.idauxiliar);
            var ult=this.arrayActivos.length;
            if(ult>0) ult=this.arrayActivos[ult-1].codactivo.substr(-3);
            ult++;
            if(ult<10) ult='0'+ult; if(ult<100) ult='0'+ult;
            this.codactivo=this.regFilial.codfilial+this.regAmbiente.codambiente
                +this.regGrupo.codgrupo+this.regAuxiliar.codauxiliar + ult;
            this.correlativo=ult;
        },

        nuevoActivo(){
            if(!this.idauxiliar) { swal('Especifique Auxiliar','','error'); return; } 
            this.modalActivo=1;
            this.accion=1;
            this.codactivo='';
            this.descripcion='';
            this.marca='';
            this.serie='';
            this.fechaingreso='';
            this.costo='';
            this.obs='';            
            this.generarCodigo();
        },

        async editarActivo(activo){
            this.modalActivo=1;
            this.accion=2;
            let response=await axios.get('/act_activo/listaActivos?idactivo='+activo.idactivo);
            this.regActivo=response.data.activos[0];
            this.idactivo=this.regActivo.idactivo;
            this.idfilial=this.regActivo.idfilial;
            this.descripcion=this.regActivo.descripcion;
            this.marca=this.regActivo.marca;
            this.serie=this.regActivo.serie;
            this.fechaingreso=this.regActivo.fechaingreso;
            this.costo=this.regActivo.costo;
            this.obs=this.regActivo.obs;
        },

        storeActivo(){
            swal({ title:'Procesando...',text:'Un momento por favor', type:'warning',
                showCancelButton:false, showConfirmButton:false, closeOnConfirm: false,
                allowOutsideClick: false, allowEscapeKey: false, allowEnterKey: false,
                onOpen:() => { swal.showLoading() }
            });
            axios.post('/act_activo/storeActivo',{
                'codactivo':'ASC'+this.codactivo,
                'descripcion':this.descripcion?this.descripcion.toUpperCase():'',
                'marca':this.marca?this.marca.toUpperCase:'',
                'serie':this.serie,
                'fechaingreso':this.fechaingreso,
                'costo':this.costo,
                'idfilial':this.idfilial,
                'idambiente':this.idambiente,
                'idgrupo':this.idgrupo,
                'idauxiliar':this.idauxiliar,
                'obs':this.obs
            }).then(reponse=>{
                swal('Activo creado correctamente','Proceda a la asignación de responsable','success');
                this.modalActivo=0;
                this.listaActivos(this.idfilial,this.idambiente,this.idgrupo,this.idauxiliar);
            });
        },

        updateActivo(){
            let me=this;
            axios.put('/act_activo/updateActivo',{
                'idactivo':this.idactivo,
                'descripcion':this.descripcion,
                'marca':this.marca,
                'serie':this.serie,
                'fechaingreso':this.fechaingreso,
                'costo':this.costo,
                'obs':this.obs
            }).then(response=>{
                swal('Datos Actualizados','','success');
                this.modalActivo=0;
                this.listaActivos(this.idfilial,this.idambiente,this.idgrupo,this.idauxiliar);
            });
        },

        estadoActivo(activo){
            this.idactivo=activo.idactivo;
            if(activo.activo){
                swal({title:'Desactivará '+activo.codactivo, type:'warning',
                    html: activo.nomauxiliar+'<br>'+activo.descripcion
                        +'<br>No podrá acceder a la información dependiente', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Activo',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(confirmar=>{
                    if(confirmar.value) this.switchActivo(1);
                });
            }
            else this.switchActivo(0);
        },

        switchActivo(estado){
            if(estado) var titswal='Desactivado'; else var titswal='Activado';
            var url='/act_activo/switchActivo?idactivo='+this.idactivo;
            axios.put(url).then(response=>{
                swal(titswal+' correctamente','','success');
                this.listaActivos(this.idfilial,this.idambiente,this.idgrupo,this.idauxiliar);
            });
        },

        kardexActivo(activo){
            var url=[];
            url.push('http://'+this.ipbirt+':8080');
            url.push('/birt-viewer/frameset?__report=reportes/activos');
            //url.push('/act_kardex.rptdesign'); //archivo
            url.push('/depr_activo.rptdesign'); //archivo
            url.push('&__format=pdf'); //formato
            url.push('&idactivo='+activo.idactivo); //idactivo
            url.push('&ip='+this.ipbirt);//pa la foto
            reporte.viewPDF(url.join(''),'Kardex del Activo');
        },



        async verActivo3333(idactivo){
            var url='/act_activo/verActivo?idactivo='+idactivo;
            let response= await axios.get(url);
            this.regActivo=response.data.activo[0];
            //url='act_activo/calcDepreciacion?idactivo='+idactivo;
            response=await axios.get('act_activo/calcDepreciacion?idactivo='+idactivo);
            this.arrayDepreciaciones=response.data.depreciaciones;


            /*
            response=await axios.get('/act_ufv/ufvGestion?criterio=ini');
            this.arrayUfvini=response.data.ufvgestion;
            response=await axios.get('/act_ufv/ufvGestion?criterio=fin');
            this.arrayUfvfin=response.data.ufvgestion;
            */
            //this.depreciacionAnual(this.regActivo);
            
            this.fechahoy=this.regActivo.currfecha;
            this.depreciacionActual(this.regActivo,this.regActivo.currfecha);            
            this.divDepreciacion=1;
        },

        /*
        calcDepreciacion(idactivo){
            var url='act_activo/calcDepreciacion?idactivo='+idactivo;
            axios.get(url).then(function(response){
                me.arrayDepreciaciones=response.data.depreciaciones;
            });
        },

        /*
        depreciacionAnual(activo){
            this.arrayDepreciaciones=[];
            var gesini=activo.fechaingreso.substr(0,4)*1;
            var gesfin=activo.currfecha.substr(0,4)*1;           
            var depranual=(activo.costo/activo.vida);
            var depracum=0, valorres=0;
            for(var ges=gesini; ges<gesfin; ges++){
                var regDepreciacion=new Object();
                regDepreciacion.gestion=ges;
                regDepreciacion.ufvini=this.arrayUfvini[ges-2009].valor;
                regDepreciacion.ufvfin=this.arrayUfvfin[ges-2009].valor;
                regDepreciacion.depranual=depranual;
                if(ges==gesini) {
                    regDepreciacion.ufvini=activo.ufvini;
                    var cantdias=this.diastransc(activo.fechaingreso,gesini+'-12-31')
                    regDepreciacion.depranual=(activo.costo/(activo.vida*365))*cantdias;
                }
                regDepreciacion.incranual=activo.costo*((regDepreciacion.ufvfin/regDepreciacion.ufvini)-1);
                depracum+=regDepreciacion.depranual;
                regDepreciacion.depracum=depracum;
                regDepreciacion.valorfin=activo.costo-depracum+regDepreciacion.incranual;
                var ini=activo.fechaingreso; var fin=ges+'-12-31';
                regDepreciacion.consumido=this.tiempotransc(ini,fin);
                ini=(ges+1)+'-01-01'; fin=(gesini+activo.vida)+activo.fechaingreso.substr(4,6);
                regDepreciacion.saldovida=this.tiempotransc(ini,fin);
                this.arrayDepreciaciones.push(regDepreciacion);                
            }
        },
        */

        async depreciacionActual(activo,fecha){

            swal('No se puede procesar','Proceda aactualizar la tabla de UFVs','error');

            /*
            var url='/act_ufv/verUfv?fecha='+activo.fechaingreso;
            let response=await axios.get(url);
            this.regDepreciacion.ufvini=response.data.ufvfecha[0].valor;
            
            url='/act_ufv/verUfv?fecha='+fecha;
            response=await axios.get(url);
            this.regDepreciacion.ufvfin=response.data.ufvfecha[0].valor;
            
            this.regDepreciacion.consumido=this.tiempotransc(activo.fechaingreso,fecha);
            var fechafin=(1*activo.fechaingreso.substr(0,4)+activo.vida)+activo.fechaingreso.substr(4,6);
            this.regDepreciacion.saldovida=this.tiempotransc(fecha,fechafin);
            this.regDepreciacion.incrtotal=activo.costo*((this.regDepreciacion.ufvfin/this.regDepreciacion.ufvini)-1);
            var cantdias = this.diastransc(activo.fechaingreso,fecha);
            var valorincr= this.regDepreciacion.incrtotal+activo.costo;
            this.regDepreciacion.deprtotal=valorincr/(activo.vida*365)*cantdias;
            this.regDepreciacion.valorfin=activo.costo+this.regDepreciacion.incrtotal-this.regDepreciacion.deprtotal;
            */
            
        },


        /*
        async reporteCuentas(fechahoy){
            var arrayResumen=[];
            if(!fechahoy) fechahoy=this.fechahoy=this.arrayActivos[0].currfecha;
            for(var i=1; i<this.arrayGrupos.length;i++){
                var url='/act_activo/resumenCuenta?idgrupo='+this.arrayGrupos[i].idgrupo
                    +'&idfilial='+this.idfilial+'&fecha='+fechahoy;
                let response=await axios(url);
                arrayResumen=response.data.resumen;
                var sumcosto=0, sumincrem=0, sumdeprec=0;
                for(var k=0;k<arrayResumen.length; k++){
                    sumcosto +=arrayResumen[k].costo;
                    sumincrem+=arrayResumen[k].increm;
                    sumdeprec+=arrayResumen[k].deprec;
                }
                this.arrayGrupos[i].costo =sumcosto;
                this.arrayGrupos[i].increm=this.round2dec(sumincrem);
                this.arrayGrupos[i].deprec=this.round2dec(sumdeprec);
                this.arrayGrupos[i].valor =this.round2dec(sumcosto+sumincrem-sumdeprec);
            }
        },
        */


        bajaActivo(activo){
            this.modalBaja=1;
            this.listaMotivos();
            this.verActivo(activo.idactivo);
            this.depreciacionActual(activo,activo.currfecha);

            this.accion=1;
        },

        storeBaja(activo){
            let me=this;
            axios.put('/act_activo/storeBaja',{
                'idactivo':activo.idactivo,
                'fechabaja':this.fechabaja,
                'nrorden':this.nrorden,
                'idmotivo':this.idmotivo,
                'obsbaja':this.obsbaja
            }).then(function(){
                swal('Operación correcta','Se ha dado de baja este activo. Verifique en el módulo Activos -> Bajas','success');
                me.modalBaja=0;
                me.listaActivos(activo.idfilial,activo.idambiente,activo.idgrupo,activo.idauxiliar);
            });
        },



        tiempotransc(fini,ffin){
            //var diasmes=[31,28,31,30,31,30,31,31,30,31,30,31];
            var aa=ffin.substr(0,4)-fini.substr(0,4);
            var mm=ffin.substr(5,2)-fini.substr(5,2);

            if(mm<0) {mm=12+mm; aa--;}
            //var dd=ffin.substr(8,2)-fini.substr(8,2);
            //if(dd<0) {dd=30+dd; mm--;}
            //return aa+'a'+' '+mm+'m'+' '+dd+'d';
            return aa+'a'+' '+mm+'m';
        },

        formatomon(x){  //215451.325145 --> 215,451.32
            var num=Math.round(x*100)/100;
            var cad=num.toString();
            if(!cad.includes('.')) cad=cad+'.00';
            cad=cad.split('').reverse();
            if(cad[1]=='.') cad.unshift('0');
            var arr=[]; var c=0;
            for(var i=0;i<cad.length;i++){
                if(i<3) arr.push(cad[i]);
                else {
                    if(c<3){ arr.push(cad[i]); c++; }
                    else { arr.push(','); c=0; i--; }
                }
            }
            return arr.reverse().join('');
        },



        diastransc(fini,ffin){ //dias transcurridos
            var fechaini = new Date(fini);
            var fechafin = new Date(ffin);
            return Math.round((fechafin.getTime()-fechaini.getTime())/86400000);
        },

        round2dec(x){
            //var y=;
            return Math.round(x*100)/100;
        },




    },

    mounted(){
        this.listaFiliales();
        this.listaAmbientes(this.idfilial);
        this.listaGrupos();
        this.listaAuxiliares(this.idgrupo);
        this.listaActivos(this.idfilial,this.idambiente,this.idgrupo,this.idauxiliar);
        this.jsfechas=jsfechas;
        this.jsfunc=jsfunc;
    }
}
</script>

