<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Abono de Aportes
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <label class="form-control-label" for="text-input">Texto a Buscar: &nbsp;</label>
                                    
                                    <!--<select class="form-control col-md-6" v-model="criterio">
                                      <option value="numpapeleta">Num Papeleta</option>
                                      <option value="apaterno">Apellido Paterno</option>
                                      
                                    </select>
                                    -->
                                    
                                    <input type="text" v-model="buscar" @keyup.enter="listarSocio(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" title="Abono Aportes"   @click="listarSocio(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Num Papeleta</th>
                                    <th>Nombre Socio</th>
                                    <th>CI</th>
                                    <th>Fuerza</th>
                                    <!--<th>Grado</th>-->
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="socio in arraySocio" :key="socio.idsocio">
                                    <td>
                                        <template v-if="socio.obligatorios && socio.jubilados && check('rep_devueltos')">
                                            <button  type="button" @click="reporteAportesDevueltos(socio.numpapeleta,reporte_devolucion,'obligatorios')" class="btn btn-secondary" data-toggle="tooltip" data-placement="top" title="Aportes Obligatorios">
                                                <i class="icon-briefcase"></i>
                                            </button> 
                                            <button  type="button" @click="reporteAportesDevueltos(socio.numpapeleta,reporte_devolucion,'jubilados')" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Aportes Jubilacion">
                                                <i class="icon-briefcase"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button v-if="check('addabono')" type="button" @click="abrirModal('aporte','registrar',socio)" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Abono">
                                                <i class="icon-calculator"></i>
                                            </button><!-- &nbsp; -->
                                            <button  v-if="check('adddebito')" type="button" @click="abrirModalDebito('debito','registrar',socio)" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Debito">
                                                <i class="icon-credit-card"></i>
                                            </button><!-- &nbsp; -->
                                            <button v-if="check('rep_vertical')" type="button" @click="reportevertical(socio.numpapeleta,reporte_vertical)" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Extracto Vertical">
                                                <i class="cui-justify-left"></i>
                                            </button><!-- &nbsp; -->
                                            <button v-if="check('rep_horizontal')" type="button" @click="reportehorizontal(socio.numpapeleta,reporte_horizontal)" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Extracto Horizontal">
                                                <i class="cui-justify-right"></i>
                                            </button><!-- &nbsp; -->
                                            
                                            <button v-if="check('rep_faltantes')" type="button" @click="reporteAportesFaltantes(socio.numpapeleta,reporte_faltantes)" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Aportes Faltantes">
                                                <i class="icon-eye"></i>
                                            </button>&nbsp; 
                                            <template v-if="socio.obligatorios && check('rep_devueltos')">
                                                <button type="button" @click="reporteAportesDevueltos(socio.numpapeleta,reporte_devolucion,'obligatorios')" class="btn btn-secondary" data-toggle="tooltip" data-placement="top" title="Aportes Obligatorios">
                                                    <i class="icon-briefcase"></i>
                                                </button> 
                                            </template>
                                            <template v-if="socio.jubilados">
                                                <button type="button" @click="reporteAportesDevueltos(socio.numpapeleta,reporte_devolucion,'jubilados')" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Aportes Jubilacion">
                                                <i class="icon-briefcase"></i>
                                                </button>
                                            </template>
                                        </template>
                                    </td>
                                    <td v-text="socio.numpapeleta"></td>
                                    <td v-text="socio.fullname"></td>
                                    <td v-text="socio.carnet"></td>
                                    <td v-text="socio.nomfuerza"></td>
                                   <!-- <td v-text="socio.nomgrado"></td>-->
                                    
                                    
                                    <td>
                                        <div v-if="socio.activo">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                        
                                    </td>
                                </tr>                                
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar abono aporte
            <form @submit.prevent="validateBeforeSubmit">-->
            <div class="modal fade" tabindex="-1" id="modalabono" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal +' '+ nombrecompleto + ' '+ ' '+numeropapeleta"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body1">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th style="width:90px; text-align:center">Opciones</th>
                                            <th>Tipo de Aporte</th>
                                            <th>Perfil de Cuenta</th>                     
                                            <th>Fecha de Aporte</th>
                                            <th>Importe</th>
                                            <th>Metodo Aporte</th>

                                            <th>Glosa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="aporte in arrayAporte" :key="aporte.idaporte" v-bind:class="[aporte.estadoconta==3 ? 'table-danger' :true , aporte.estadoconta==1?'table-info':true,false]">
                                            <td>
                                                
                                                <button v-if="aporte.estadoconta==3" type="button" @click="observado_view(aporte.observaciones)" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Ver Observacion de Contabilidad">
                                                    <i class="icon-eye"></i>
                                                </button>
                                                <button v-if="aporte.estadoconta==3" type="button" class="btn btn-danger btn-sm" @click="eliminarAporte(aporte.idaporte,aporte.idasientomaestro,aporte.aporte)" data-toggle="tooltip" data-placement="top" title="Eliminar/Corregir Aporte">
                                                    <i class="icon-trash"></i>
                                                </button>
                                                <div v-if="aporte.estadoconta==0">
                                                    <span class="badge badge-warning">No Validado</span>
                                                </div>
                                                    
                                                <!-- 
                                                <template v-else>
                                                    <button type="button" class="btn btn-info btn-sm" @click="activarAporte(aporte.idaporte,aporte.idsocio)">
                                                        <i class="icon-check"></i>
                                                    </button>
                                                </template>
                                                -->
                                                                                    
                                            </td>
                                            <td v-text="aporte.descripcion"></td>
                                            <td v-text="aporte.nomperfil"></td>
                                            <td v-text="aporte.fechaaporte"></td>
                                            <td v-text="aporte.aporte"></td>
                                            <td v-text="aporte.metodoaporte"></td>
                                            <td v-text="aporte.obsaporte"></td>
                                            <!--
                                            <td>
                                                <div v-if="aporte.activo">
                                                    <span class="badge badge-success">Activo</span>
                                                </div>
                                                <div v-else>
                                                    <span class="badge badge-danger">Desactivado</span>
                                                </div>
                                                
                                            </td>
                                            -->
                                            
                                        </tr>                                
                                    </tbody>
                                </table>
                            <div class="form-row">
                                <div class="form-group row col-sm-6">
                                    <label class="col-md-4 form-control-label" for="text-input">Tipo de Aporte</label>
                                    <div class="col-md-8">
                                        <select class="form-control" 
                                                v-model="idtipoaporte"
                                                v-validate.initial="'required'"
                                                
                                                name="Aporte">

                                            <option value="0">Seleccione</option>
                                            <option v-for="tipoaporte in arrayTipoaporte" :key="tipoaporte.idtipoaporte" :value="tipoaporte.idtipoaporte" v-text="tipoaporte.descripcion"></option>
                                        </select>                                        
                                        <span class="text-error">{{ errors.first('Aporte')}}</span>   <!--Lineas Agregadas<-->
                                    </div>
                                </div>
                                <div class="form-group row col-sm-6">
                                    <label class="col-md-4 form-control-label" for="text-input">Perfil de cuenta</label>
                                    <div class="col-md-8">
                                        <select class="form-control" 
                                                v-model="idperfilcuentamaestro"
                                                v-validate.initial="'required'"
                                                
                                                name="Perfilcuentamaestro">

                                            <option value="0">Seleccione</option>
                                            <option v-for="perfilcuentamaestro in arrayPerfilcuentamaestro" :key="perfilcuentamaestro.idperfilcuentamaestro" :value="perfilcuentamaestro.idperfilcuentamaestro" v-text="perfilcuentamaestro.nomperfil"></option>
                                        </select>                                        
                                        <span class="text-error">{{ errors.first('Perfilcuentamaestro')}}</span>   <!--Lineas Agregadas<-->
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group row col-sm-6">
                                    <label class="col-md-4 form-control-label" for="text-input">Tipo de Documento</label>
                                    <div class="col-md-8">
                                        <input class="form-control formu-entrada" 
                                            v-validate.initial="'required'"
                                            type="text" v-model="tipodocumento"
                                            name="Tipo Documento">
                                        <span class="text-error">{{ errors.first('Tipo Documento')}}</span>
                                    </div>
                                </div>
                                <div class="form-group row col-sm-6">
                                    <label class="col-md-4 form-control-label" for="text-input">Numero de Documento</label>
                                    <div class="col-md-8">
                                        <input class="form-control formu-entrada" 
                                            v-validate.initial="'required'"
                                            type="text" v-model="numdocumento"
                                            name="Numero Documento">
                                        <span class="text-error">{{ errors.first('Numero Documento')}}</span>
                                    </div>
                                </div>
                                <div class="form-group row col-sm-6">
                                    <label class="col-md-4 form-control-label" for="text-input">Fecha de Aporte</label>
                                    <div class="col-md-8">
                                        <input class="form-control formu-entrada" 
                                            v-validate.initial="'required'"
                                            type="date" v-model="fechaaporte"
                                            :max="fechaactual"
                                            name="Fechar Aporte">
                                        <span class="text-error" v-if="!verificarfecha">La Fecha debe ser menor a la fecha actual</span>
                                        <span class="text-error" v-if="fechaaporte==''">La Fecha no debe Estar Vacia</span>
                                    </div>
                                </div>
                                <div class="form-group row col-sm-6">
                                    <label class="col-md-4 form-control-label" for="text-input">Importe</label>
                                    <div class="col-md-8"><!--expresion regular /^[0-9]+(\.[0-9][0-9]?)?/}"-->
                                        <input  v-validate.initial="{required:true,regex:/^[0-9]+(\.[0-9][0-9]?)?/}"  
                                                type="text" 
                                                v-model="aporte" 
                                                class="form-control" 
                                                placeholder="aporte"
                                                name="aporte">   

                                        <span class="text-error">{{ errors.first('aporte')}}</span>   <!--Lineas Agregadas<-->                                     

                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                   <div class="form-group row col-md-12">
                                    <label class="col-md-2 form-control-label" for="text-input">Glosa</label>
                                    <div class="col-md-10">
                                        <input  v-validate.initial="'required'"
                                                type="text" 
                                                v-model="obsaporte" 
                                                class="form-control" 
                                                placeholder="obs"
                                                name="Glosa"> 
                                        <span class="text-error">{{ errors.first('Glosa')}}</span>   <!--Lineas Agregadas<-->                                       
                                    </div>
                                </div>
                            </div>


                                <!--
                                <div v-show="errorSocio" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjSocio" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                                -->
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- modificar boton aceptar -->
                            <input  :disabled ="errors.any() ||!verificarfecha" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarAporte()" value="Guardar">
                            <button :disabled ="errors.any () ||!verificarfecha" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarAporte()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
           <!--</form>
            Fin del modal-->

            <!--Inicio del modal debito-->
            <template>
            <div class="validation-component">
            <div class="modal fade" tabindex="-1" id="modaldebito" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModalDebito +' '+ nombrecompleto + ' '+ ' '+numeropapeleta"></h4>
                            <button type="button" class="close" @click="cerrarModalDebito()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body1">
                            
                              <div class="card">
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <label class="form-control">Fecha de Aporte</label>

                                                    <!--<select class="form-control col-md-6" v-model="criterio">
                                                        <option value="numpapeleta">Num Papeleta</option>
                                                    </select>
                                                    -->
                                                    
                                                    <input type="date" v-model="buscarF" @keyup.enter="listarAporteDebito(1,numeropapeleta,buscarF)" class="form-control">
                                                    <button type="submit" title="Abono Aportes"   @click="listarAporteDebito(1,numeropapeleta,buscarF)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Opciones</th>
                                                    <th>Fecha Aporte</th>
                                                    <th>Tipo Aporte</th>
                                                    <th>metodoaporte</th>
                                                    <th>Monto</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="debito in arrayAportedebito" :key="debito.idaporte">
                                                    <td>
                                                        <button type="button" @click="abrirModalDebito('debito','actualizar',debito)" class="btn btn-warning btn-sm">
                                                        <i class="icon-pencil"></i>
                                                        </button> &nbsp;
                                                        
                                                    </td>
                                                    <td v-text="debito.fechaaporte"></td>
                                                    <td v-text="debito.descripcion"></td>
                                                    <td v-text="debito.metodoaporte"></td>
                                                    <td v-text="debito.sumatotal"></td>
                                                </tr>                                
                                            </tbody>
                                        </table>
                                        <!--
                                        <nav>
                                            <ul class="pagination">
                                                <li class="page-item" v-if="paginationDebito.current_page > 1">
                                                    <a class="page-link" href="#" @click.prevent="cambiarPaginaDebito(paginationDebito.current_page - 1,numeropapeleta,buscarF)">Ant</a>
                                                </li>
                                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                                    <a class="page-link" href="#" @click.prevent="cambiarPaginaDebito(page,numeropapeleta,buscarF)" v-text="page"></a>
                                                </li>
                                                <li class="page-item" v-if="paginationDebito.current_page < paginationDebito.last_page">
                                                    <a class="page-link" href="#" @click.prevent="cambiarPaginaDebito(paginationDebito.current_page + 1,numeropapeleta,buscarF)">Sig</a>
                                                </li>
                                            </ul>
                                        </nav>
                                        -->
                                    </div>
                                </div>  
                            
                            
                            <template v-if="mdebito">
                                
                            
                            <div class="form-row">
                                <div class="form-group row col-md-6">
                                    <label class="col-md-4 form-control-label" for="text-input">Fecha de aporte: </label>
                                    <p class="col-md-8"> <b>{{ fechaaporte }} </b></p>
                                </div>
                                <div class="form-group row col-md-6">
                                    <label class="col-md-4 form-control-label" for="text-input">Monto Aporte:</label>
                                    <p class="col-md-8">{{ aporte }} </p>
                                    
                                </div>
                            </div>
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">    
                            <div class="form-row">
                                <div class="form-group row col-sm-6">
                                    <label class="col-md-4 form-control-label" for="text-input">Tipo de Documento</label>
                                    <div class="col-md-8">
                                        <input class="form-control formu-entrada" 
                                            v-validate.initial="'required'"
                                            type="text" v-model="tipodocumentodeb"
                                            name="Tipo Documento Debito">
                                        <span class="text-error">{{ errors.first('Tipo Documento Debito')}}</span>
                                    </div>
                                </div>
                                <div class="form-group row col-sm-6">
                                    <label class="col-md-4 form-control-label" for="text-input">Numero de Documento</label>
                                    <div class="col-md-8">
                                        <input class="form-control formu-entrada" 
                                            v-validate.initial="'required'"
                                            type="text" v-model="numdocumentodeb"
                                            name="Numero Documento Debito">
                                        <span class="text-error">{{ errors.first('Numero Documento Debito')}}</span>
                                    </div>
                                </div>
                                <div class="form-group row col-sm-6">
                                    <label class="col-md-4 form-control-label" for="text-input">Perfil de cuenta:</label>
                                    <div class="col-md-8">
                                        <select class="form-control" 
                                                v-model="idperfilcuentamaestro"
                                                v-validate.initial="'required'"
                                                
                                                name="Perfilcuentamaestro">

                                            <option value="0">Seleccione</option>
                                            <option v-for="perfilcuentamaestro in arrayPerfilcuentamaestro" :key="perfilcuentamaestro.idperfilcuentamaestro" :value="perfilcuentamaestro.idperfilcuentamaestro" v-text="perfilcuentamaestro.nomperfil"></option>
                                        </select>                                        
                                        <span class="text-error">{{ errors.first('Perfilcuentamaestro')}}</span>   <!--Lineas Agregadas<-->
                                    </div>
                                </div>
                                
                                
                                <div class="form-group row col-sm-6">
                                    <label class="col-md-4 form-control-label" for="text-input">Monto a Debitar :</label>
                                    <p class="col-md-8">{{ aporte }} </p>
                                    <!--
                                    <div class="col-md-8">
                                        <input class="form-control formu-entrada" 
                                            v-validate.initial="'required'"
                                            type="number" v-model="montodebito"
                                            name="Monto Debito" placeholder="0" readonly>
                                        <span class="text-error">{{ errors.first('Monto Debito')}}</span>
                                    </div>-->
                                </div>
                                
                            </div>
                            <div class="form-row">
                                <div class="form-group row col-md-6">
                                    <label class="col-md-4 form-control-label" for="text-input"></label>
                                    <p class="col-md-8"> </p>
                                </div>
                                <!--<div class="form-group row col-md-6">
                                    <label class="col-md-4 form-control-label" for="text-input">Aporte Actualizado:</label>
                                    <p v-if="saldo_aporte=='El Saldo No debe ser Negativo'" class="col-md-8"><span class="text-error"><b> {{ saldo_aporte }}</b></span> </p>
                                    <p v-else><b>{{saldo_aporte}}</b></p>
                                    
                                </div>-->
                            </div>
                            
                            <div class="form-row">
                                   <div class="form-group row col-md-12">
                                    <label class="col-md-2 form-control-label" for="text-input">Glosa</label>
                                    <div class="col-md-10">
                                        <input  type="text" 
                                                v-model="obsdebito" 
                                                v-validate.initial="'required'"
                                                class="form-control" 
                                                placeholder="Glosa Debito"
                                                name="obs_debito"> 
                                        <span class="text-error">{{ errors.first('obs_debito')}}</span>  
                                    </div>
                                </div>
                            </div>


                                <!--
                                <div v-show="errorSocio" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjSocio" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                                -->
                            </form>
                            </template>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModalDebito()">Cerrar</button>
                            <!-- modificar boton aceptar -->
                            
                            <button :disabled="!isComplete" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="registrarDebito()">Registar</button>
                        </div><!--:disabled="errors.any() || !isCompleted"-->
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            </div>
            </template>
            <!--Fin del modal-->

        
        </main>
</template>

<script>
    import VueBarcode from 'vue-barcode';
    import Vue from 'vue';
    import VeeValidate from 'vee-validate';
    import * as plugin from './../functions.js';

    //import vSelect from 'vue-select';
    //Vue.component( 'v-select', vSelect );

    //Vue.component('v-select', VueSelect.VueSelect)

   
    export default {
        props:['idmodulo','idventanamodulo'],
        data (){
            return {
                mdebito:null,
                buscarF:'',
                socio_id: 0,
                aporte_id:0,
                idperfilcuentamaestro : 0,
                idtipoaporte:0,
                numpapeleta : '',
                nomperfil:'',
                //modal : 0,
                //modalDebito:0,
                tituloModal : '',
                tituloModalDebito:'',
                tipoAccion : 0,
                errorSocio : 0,
                errorMostrarMsjSocio : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                paginationDebito : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'numpapeleta',
                criterioFecha:'fechaaporte',
                buscarFecha:'',
                buscar : '',
                
                buscarAporte:'',
                arrayDepartamento :[],
                arrayAporte:[],
                
                arraySocio:[],
                arrayTipoaporte:[],
                arrayPerfilcuentamaestro:[],
                nombrecompleto:'',
                numeropapeleta:'',
                grado:'',
                aporte:'',
                fechaaporte:'',    
                obsaporte:'',  
                tipooperacion:0,    
                numdocumento:'',
                tipodocumento:'',    
                
                arrayAportedebito:[],

                //montodebito:'',
                obsdebito:'',
                iddebito:0,
                tipodocumentodeb:'',
                numdocumentodeb:'',
                reporte_horizontal:'',
                reporte_vertical:'',
                reporte_abono:'',
                reporte_debito:'',
                reporte_faltantes:'',
                reporte_devolucion:'',

                fechaactual:'',
                mes:'',
                anio:'',
                arrayPermisos:{addabono:0,
                                adddebito:0,
                                rep_horizontal:0,
                                rep_vertical:0,
                                rep_faltantes:0,
                                rep_devueltos:0
                                },
                
                arrayPermisosIn:[]
                
            }
        },
        components: {
        'barcode': VueBarcode
    },
        computed:{
            verificarfecha(){
                let me=this;
                if(me.fechaactual>=me.fechaaporte)
                    return true;
                else
                    return false;

            },
            isComplete () {
                //console.log( this.idperfilcuentamaestro && this.montodebito && this.obsdebito);
                return this.idperfilcuentamaestro && this.obsdebito;
            },
            //saldo_aporte: function(){
                /*var me=this
                var saldo_aporte
                me.saldo_aporte= this.aporte - this.montodebito*/
            //    var saldo
            //    saldo= this.aporte - this.montodebito
            //    if(saldo<0)
            //        return "El Saldo No debe ser Negativo"
            //    else
            //        return saldo

            //},
            isActived: function(){
                return this.pagination.current_page;
            },
            isActivedDebito: function(){
                return this.paginationDebito.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            },

            pagesNumberDebito: function() {
                if(!this.paginationDebito.to) {
                    return [];
                }
                
                var from = this.paginationDebito.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.paginationDebito.last_page){
                    to = this.paginationDebito.last_page;
                }  

                var pagesArrayDebito = [];
                while(from <= to) {
                    pagesArrayDebito.push(from);
                    from++;
                }
                return pagesArrayDebito;             

            }
        }, 
        methods : {   
            getPermisos() {
            var url= '/adm_role/selectPermisos?idmodulo=' + this.idmodulo+'&idventanamodulo='+this.idventanamodulo;
                let me = this; 
                axios.get(url).then(function (response) {
                   me.arrayPermisosIn=[];
                    if(response.data.datapermiso.length>0){
                        var respuesta=response.data.datapermiso[0].permisos; 
                        me.arrayPermisosIn = JSON.parse((respuesta));
                    } 
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            check(n){
                return _pl.validatePermission(this.arrayPermisosIn,n);
            },
            eliminarAporte(idaporte,idasientomaestro,monto){
                 swal({
                    title: 'Se Eliminara el Aporte Observado',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!',
                    cancelButtonText: 'Cancelar',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    reverseButtons: true
                    }).then((result) => {
                    if (result.value) {
                        let me = this;
                        axios.put('/apo_aporte/eliminaraporte',{
                            'idaporte': idaporte,
                            'idasientomaestro':idasientomaestro,
                            'numpapeleta':me.num_papeleta,
                            'monto':monto
                        }).then(function (response) {
                            swal(
                            'Aporte Eliminado!',
                            'success'
                            )
                            me.listarAporte(me.num_papeleta);
                            //console.log(1,me.criterio,me.borradorcheck);
                        }).catch(function (error) {
                            console.log(error);
                        });
                        
                        
                    } 
                    else if (result.dismiss === swal.DismissReason.cancel)
                    {
                        
                    }
                    })

            },
            observado_view(observado){
                //console.log(pr);
                let me=this;
                swal({
                title: '¡Observaciones!',
                html:   '<div class="form-group row "> ' +
                        '<div class="col-md-12"> <div class="input-group"> <textarea style="    background: transparent;"  class="form-control"  placeholder="Observaciones" autofocus readonly>'+
                        observado+'</textarea></div> </div></div>',
                type:"info",
                showCancelButton: false,
                confirmButtonColor: "#4dbd74",
                cancelButtonColor: "#f86c6b",
                confirmButtonText: "Ok", 
                buttonsStyling: true,
                reverseButtons: true
                });
            },
            fechahoy(){
                let me=this;
                var hoy = new Date();
                var dd = hoy.getDate(); 
                var mm = hoy.getMonth();
                var aa = hoy.getFullYear();
                me.mes=mm;
                me.anio=aa;
                //me.messelected=me.arraymes[me.mes].value;
                //me.anioselected=me.anio;
                mm=mm+1;
                if(mm<10) 
                    mm='0'+mm;
                
                if(dd<10)
                    dd='0'+dd; 
                me.fechaactual=aa+'-'+mm+'-'+dd;
                //me.fechafactura=me.fechaactual;

            },
            /* diaminmax(){
                let me=this;
                var primerDiaMes = new Date(me.anioselected, me.messelected - 1  , 1);
                var ultimoDiaMes = new Date(me.anioselected, me.messelected , 0);
                var mm=me.messelected;
                if(mm<10) 
                    mm='0'+mm;

                var dd=primerDiaMes.getDate()
                if(dd<10)
                    dd='0'+dd; 
                
                me.fechainicial=me.anioselected+'-'+mm+'-'+dd;
                me.fechafinal=me.anioselected+'-'+mm+'-'+ultimoDiaMes.getDate();
            }, */
        reporteAportesDevueltos(papeletanum,reporte_devolucion,tipodevolucion){
            var tipodev=0;
            if(tipodevolucion=='obligatorios')
                tipodev=1;
            else    
                tipodev=2;

            var url=reporte_devolucion + papeletanum+'&tipodevolucion='+tipodev; 
            //this.abrirVentanaModalURL(url,"Reporte Faltantes",800,700);		
            plugin.viewPDF(url,'Reportes Faltantes');
            //window.open('http://192.168.100.60:8080/birt-viewer/frameset?__report=reportes/apo_reportes/rep_horizontal.rptdesign&numpapeleta='+papeletanum,'_blank');
            //window.open('http://localhost:8080/birt-viewer/frameset?__report=reportes/apo_reportes/rep_horizontal.rptdesign&numpapeleta='+papeletanum,'_blank');
        },

        /* abrirVentanaModalURL(url, title, w, h) { 
					var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : window.screenX;
					var dualScreenTop = window.screenTop != undefined ? window.screenTop : window.screenY; 
					var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
					var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height; 
					var left = ((width / 2) - (w / 2)) + dualScreenLeft;
					var top = ((height / 2) - (h / 2)) + dualScreenTop;
					var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
 
					if (window.focus) {
						newWindow.focus();
					}
				}, */
		getRutasReports (){ 
                let me=this;
                var url= '/apo_reportes';
                axios.get(url).then(function (response) {
                     var respuesta= response.data; ;
                    me.reporte_horizontal = respuesta.REP_APO_HORIZONTAL; 
                    me.reporte_vertical = respuesta.REP_APO_VERTICAL; 
                    me.reporte_abono=respuesta.REP_APO_ABONO;
                    me.reporte_debito=respuesta.REP_APO_DEBITO;
                    me.reporte_faltantes=respuesta.REP_APO_FALTANTES;
                    me.reporte_devolucion=respuesta.REP_APO_DEVOLUCION;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            //metodo agregado para la validacion
            validateBeforeSubmit() {
                this.$validator.validateAll().then((result) => {

                //this.$validator.validate(this.Type) => {

                    if (result) {
                    //alert('enviado');
                    //return this.result;
                    return;
                    }
                    
                    //alert('no enviado');
                    return;
                    ////alert(result);
                    
                    
                });
            },

            listarSocio (page,buscar,criterio){
                let me=this;
                var url= '/apo_aporte?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    
                    if(!response.data)
                    {
                        swal({
                        text: "Debe Ingresar un dato para buscar.", 
                        type: "warning",                                                  
                        })     
                    }
                    else
                    {
                        var respuesta= response.data;
                        //console.log(respuesta.socios.data.length)
                        if(respuesta.socios.data.length==0)
                        {
                            swal({
                                text: "No se Encontraron Registros.", 
                                type: 'warning',                                                  
                            })  
                            me.arraySocio=[]
                        }
                        else
                        {
                            
                            me.arraySocio = respuesta.socios.data;
                            me.pagination= respuesta.pagination;
                        }
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectPerfilcuentamaestro(tipooperacion){
                let me=this;
                var url= '/con_perfilcuentamaestro/selectPerfilcuentamaestro?idmodulo='+2;
                axios.get(url).then(function (response) {
                    //console.log(url);
                    var respuesta= response.data;
                    me.arrayPerfilcuentamaestro = respuesta.perfilcuentamaestros;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectTipoaporte(){
                let me=this;
                var url= '/apo_tipoaporte/selectTipoaporte';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayTipoaporte = respuesta.tipoaportes;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            /*
            listarAporte(numpapeleta){
                let me=this;
                var url= '/apo_aporte?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arraySocio = respuesta.socios.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            */
            
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarSocio(page,buscar,criterio);
            },

            cambiarPaginaDebito(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarAporteDebito(page,numpapeleta,buscarF);
            },

            reportevertical(papeletanum,reporte_vertical){
                var url=reporte_vertical + papeletanum; 
                //this.abrirVentanaModalURL(url,"Reporte Vertical",800,700);
                //<li><a class="nav-link" v-bind:href="''+ascii+''" target="_blank"><i class="icon-link"></i> Reporte Carga Ascii</a></li>
                //window.open('http://192.168.100.60:8080/birt-viewer/frameset?__report=reportes/apo_reportes/reporte_vertical.rptdesign&numpapeleta='+papeletanum,'_blank');
                //window.open('http://localhost:8080/birt-viewer/frameset?__report=reportes/apo_reportes/reporte_vertical.rptdesign&numpapeleta='+papeletanum,'_blank');
                plugin.viewPDF(url,'Reporte Vertical');
            },
            reportehorizontal(papeletanum,reporte_horizontal){
                var url=reporte_horizontal + papeletanum; 
                //this.abrirVentanaModalURL(url,"Reporte Horizontal",800,700);		
                plugin.viewPDF(url,'Reporte Horizontal');
                //window.open('http://192.168.100.60:8080/birt-viewer/frameset?__report=reportes/apo_reportes/rep_horizontal.rptdesign&numpapeleta='+papeletanum,'_blank');
                //window.open('http://localhost:8080/birt-viewer/frameset?__report=reportes/apo_reportes/rep_horizontal.rptdesign&numpapeleta='+papeletanum,'_blank');
            },
            reporteAportesFaltantes(papeletanum,reporte_faltantes){
                var url=reporte_faltantes + papeletanum; 
                //this.abrirVentanaModalURL(url,"Reporte Aportes Faltantes",800,700);		
                plugin.viewPDF(url,'Reporte Aportes Faltantes')
            },

            comprobanteabono(ridaporte,rep_abono){
                var url=rep_abono + ridaporte; 
                //this.abrirVentanaModalURL(url,"Comprobante Abono",800,700);	
                plugin.viewPDF(url,'Comprobante Abono');
                //window.open('http://192.168.100.60:8080/birt-viewer/frameset?__report=reportes/apo_reportes/rep_horizontal.rptdesign&numpapeleta='+papeletanum,'_blank');
                //window.open('http://localhost:8080/birt-viewer/frameset?__report=reportes/apo_reportes/rep_horizontal.rptdesign&numpapeleta='+papeletanum,'_blank');
            },
            comprobantedebito(riddebito,rep_debito){
                var url=rep_debito + riddebito; 
                //this.abrirVentanaModalURL(url,"Comprobante Debito",800,700);		
                plugin.viewPDF(url,'Comprobante Debito');
            },
            


            registrarAporte(){
                /*if (this.validarAporte()){
                    return;
                }*/
                
                let me = this;

                axios.post('/apo_aporte/registrar',{
                    'numpapeleta':this.numeropapeleta,
                    'idtipoaporte': this.idtipoaporte,
                    'aporte': this.aporte,
                    'fechaaporte': this.fechaaporte,
                    'metodoaporte':'formulario-aporte',
                    'idperfilcuentamaestro':this.idperfilcuentamaestro,
                    'obsaporte':this.obsaporte,
                    'numdocumento':this.numdocumento,
                    'tipodocumento':this.tipodocumento,
                    'idmodulo':this.idmodulo

                    
                }).then(function (response) {
                        //console.log(response.data);
                        var residaporte= response.data;
                        swal(
                            'Registrado Correctamente'
                       )  
                        me.resetModal();
                        me.listarAporte(me.numeropapeleta);
                        me.comprobanteabono(residaporte,me.reporte_abono)
                }).catch(function (error) {
                    console.log(error);
                });
            },
            registrarDebito(){
                let me = this;
                //console.log(this.aporte_id);
                
                axios.post('/apo_debito/registrar',{
                    'idaporte':this.aporte_id,
                    'montodebito': this.aporte,
                    'idperfilcuentamaestro':this.idperfilcuentamaestro,
                    'obsdebito':this.obsdebito,
                    'numdocumentodeb':this.numdocumentodeb,
                    'tipodocumentodeb':this.tipodocumentodeb,
                    'idmodulo':this.idmodulo
                }).then(function (response) {
                        var iddebito=response.data;
                        console.log(iddebito);
                        swal(
                            'Registrado Correctamente'
                       )  
                        
                        me.listarAporteDebito(1,me.numeropapeleta);
                        me.resetModalDebito();
                        me.comprobantedebito(iddebito,me.reporte_debito)
                }).catch(function (error) {
                    console.log(error);
                });

            },

            actualizarAporte(){
                var residaporte=this.aporte_id;
                let me = this;
                console.log (this.numeropapeleta);

                axios.put('/apo_aporte/actualizar',{
                    'idaporte':this.aporte_id,
                    'numpapeleta':this.numeropapeleta,
                    'idtipoaporte': this.idtipoaporte,
                    'aporte': this.aporte,
                    'fechaaporte': this.fechaaporte,
                    'metodoaporte':'formulario-aporte',
                    'idperfilcuentamaestro':this.idperfilcuentamaestro,
                    'obsaporte':this.obsaporte,
                    //'numdocumento':this.numdocumento,
                    //'tipodocumento':this.tipodocumento,
                }).then(function (response) {
                    if(response.data.length){
                    swal(
                            'El Valor ya Existe',
                            'Ingresa un dato Diferente',
                            'error'
                       )
                    }
                    else{
                        
                        
                        me.listarAporte(me.numeropapeleta);
                        me.comprobanteabono(residaporte,me.reporte_abono)
                        //window.open('http://192.168.100.60:8080/birt-viewer/frameset?__report=reportes/apo_reportes/rep_abono_aportes.rptdesign&idaporte='+residaporte,'_blank');
                        //window.open('http://localhost:8080/birt-viewer/frameset?__report=reportes/apo_reportes/rep_abono_aportes.rptdesign&idaporte='+residaporte,'_blank');
                        me.resetModal2();
                        //window.open('http://localhost:8080/birt-viewer/frameset?__report=apo_reportes/rep_abono_aportes.rptdesign&idaporte='+this.aporte_id,'_blank');
                        //this.tipoAccion = 1;
                    }
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            
            cerrarModal(){
                //this.modal=0;
                this.classModal.closeModal('modalabono');
                this.resetModal();
            },
            cerrarModalDebito(){
                //this.modalDebito=0;
                this.classModal.closeModal('modaldebito');
                this.buscarFecha='';
                this.resetModal();
                this.buscarF='';
                
            },
            resetModal(){
                
                //this.tituloModal='';
                this.idtipoaporte= 0;
                this.idperfilcuentamaestro = 0;
                this.fechaaporte = '';
                this.aporte = '';
                this.obsaporte='';
                this.numdocumento='';
                this.tipodocumento='';
            },
            resetModal2(){
                
                //this.tituloModal='';
                this.idtipoaporte= 0;
                this.aporte_id=0;
                this.idperfilcuentamaestro = 0;
                this.fechaaporte = '';
                this.aporte = '';
                this.obsaporte='';
                this.tipoAccion=1;
            },
            resetModalDebito(){
                this.mdebito=0;
                this.obsdebito=''
            },
            listarAporte (numpapeleta){ 
                let me=this;
                var url= '/apo_aporte/selectAporte?numpapeleta='+ numpapeleta;
                axios.get(url).then(function (response) {
                    //console.log(response)
                    var respuesta= response.data; 
                    me.arrayAporte = respuesta.aportes; 
                    //console.log(respuesta.aportes)
                    //me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listarAporteDebito(page,numpapeleta,buscarF)
            {
                let me=this;
                buscarF=me.buscarF;
                //console.log('buscarF '+ me.buscarF)
                var url= '/apo_aporte/selectAporteDebito?page=' + page+'&numpapeleta='+ numpapeleta + '&fechabusqueda='+ buscarF;                //console.log(url)
                //console.log(url)
                /*axios.post('/apo_aporte/selectAporteDebito',{
                    'page':page,
                    'numpapeleta':numpapeleta,
                    'fechabusqueda':me.buscarF

                */
               axios.get(url).then(function (response) {
                    if(!response.data)
                    {
                        swal({
                        text: "Debe Ingresar un dato para buscar.", 
                        type: "warning",                                                  
                        })     
                    }
                    else
                    {
                        var respuesta= response.data; 
                        //console.log(respuesta.socios.data.length)
                        if(respuesta.debitos.data.length==0)
                        {
                            swal({
                                text: "No se Encontraron Registros.", 
                                type: 'warning',                                                  
                            })  
                            me.arrayAportedebito=[]
                            
                        }
                        else
                        {
                            me.arrayAportedebito = respuesta.debitos.data;
                            
                            //me.pagination= respuesta.pagination;
                            //console.log('prueba arrayaportedebito '+ me.arrayAportedebito)
                        }
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            abrirModal(modelo, accion, data = []){
                let me=this;
                switch(modelo){
                    case "aporte":
                    {
                        switch(accion)
                        {
                            case 'registrar':
                            {
                                me.tipooperacion=1;
                                //console.log(me.tipooperacion);
                                //me.modal = 1;
                                me.classModal.openModal('modalabono');
                                me.tituloModal = 'Registrar Aporte';
                                me.num_papeleta=data['numpapeleta'];
                                me.listarAporte(me.num_papeleta);
                                me.nombrecompleto=data['fullname'];
                                me.numeropapeleta=data['numpapeleta'];
                                me.grado=data['nomgrado'];
                                me.nomsocio= '';
                                me.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                //me.modal=1;
                                me.tipooperacion=1;
                                me.tituloModal='Actualizar aporte';
                                me.tipoAccion=2;
                                me.aporte_id=data['idaporte'];
                                //console.log(aporte_id);
                                me.idtipoaporte=data['idtipoaporte'];
                                me.idperfilcuentamaestro=data['idperfilcuentamaestro'];
                                me.fechaaporte=data['fechaaporte'];
                                me.numpapeleta=data['numpapeleta'];
                                me.aporte=data['aporte']
                                me.obsaporte = data['obsaporte'];
                                me.numdocumento=data['numdocumento'];
                                me.tipodocumento=data['tipodocumento'];
                                break;
                            }
                        }
                        break;
                    }
                }
                this.selectPerfilcuentamaestro(this.tipooperacion);
                this.selectTipoaporte();
                //this.listarAporte();
            },
            abrirModalDebito(modelo, accion, data = []){
                let me=this;
                switch(modelo){
                    case "debito":
                    {
                        switch(accion)
                        {
                            case 'registrar':
                            {
                                
                                //this.mdebito=0;
                                me.tipooperacion=2;
                                //me.modalDebito = 1;
                                me.classModal.openModal('modaldebito');
                                me.tituloModalDebito = 'Registrar Debito Aporte';
                                me.num_papeleta=data['numpapeleta'];
                                //me.listarAporteDebito(1,me.num_papeleta,me.buscarF);
                                me.nombrecompleto=data['fullname'];
                                me.numeropapeleta=data['numpapeleta'];
                                me.grado=data['nomgrado'];
                                me.nomsocio= '';
                                me.tipoAccion = 1;
                                me.montodebito=data['sumatotal'];
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                me.idperfilcuentamaestro=0;
                                me.montodebito='';
                                me.aporte='';
                                me.obsaporte='';
                                me.mdebito=1;
                                me.tipooperacion=2;
                                //console.log(me.tipooperacion);
                                //me.modalDebito=1;
                                me.classModal.openModal('modaldebito');
                                me.tituloModalDebito='Actualizar Debito aporte';
                                me.tipoAccion=2;
                                me.aporte_id=data['idaporte'];
                                //console.log(aporte_id);
                                
                                me.idtipoaporte=data['idtipoaporte'];
                                //me.idperfilcuentamaestro=data['idperfilcuentamaestro'];
                                me.fechaaporte=data['fechaaporte'];
                                me.numpapeleta=data['numpapeleta'];
                                me.aporte=data['sumatotal']
                                me.obsaporte = data['obsaporte'];
                                
                                break;
                            }
                        }
                        break;
                    }
                }
                //console.log(this.tipooperacion);
                this.selectPerfilcuentamaestro(this.tipooperacion);
                this.selectTipoaporte();
                //this.listarAporte();
                this.listarAporteDebito(1,this.numeropapeleta,this.buscarF);
                
            }
        },
        mounted() {
            this.fechahoy();
            this.getPermisos();
            this.getRutasReports();
            this.classModal=new _pl.Modals();
            this.classModal.addModal('modalabono');
            this.classModal.addModal('modaldebito');
            //this.listarSocio(1,this.buscar,this.criterio);
        }
    }
</script>
<style>    
    .modal-content{
        width: 120% !important;
        /*top:50%; 
        right: 5%; */
        position: absolute !important;
        
    }
    
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
        
        /*outline: none; 
        overflow:hidden;*/
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-style: italic;
    }
</style>
