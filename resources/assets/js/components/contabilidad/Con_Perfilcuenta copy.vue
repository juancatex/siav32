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
                        <i class="fa fa-align-justify"></i> Perfil de Cuentas
                        <button type="button" @click="abrirModal('perfilcuentamaestro','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <!--
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nomperfilcuentamaestro">Nombre Perfilcuentamaestro</option>
                                      
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarPerfilcuentamaestro(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarPerfilcuentamaestro(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>-->
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre Perfil</th>
                                    <th>Descripcion</th>
                                    <th>Tipo de Comprobante</th>
                                    <th>Modulo</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="perfilcuentamaestro in arrayPerfilcuentamaestro" :key="perfilcuentamaestro.idperfilcuentamaestro">
                                    <td>
                                        
                                       <template v-if="perfilcuentamaestro.siporcentaje">
                                        <button type="button" @click="abrirModalDetalle('registrar',perfilcuentamaestro)" class="btn btn-warning btn-sm">
                                          <i class="cui-cog"></i>
                                        </button> &nbsp;
                                       </template>
                                       <template v-else>
                                           <button type="button" @click="abrirModalDetallenoporcentaje('registrar',perfilcuentamaestro)" class="btn btn-warning btn-sm">
                                          <i class="cui-cog"></i>
                                        </button> &nbsp;
                                       </template>
                                        
                                        <template v-if="perfilcuentamaestro.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarPerfilcuentamaestro(perfilcuentamaestro.idperfilcuentamaestro)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarPerfilcuentamaestro(perfilcuentamaestro.idperfilcuentamaestro)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="perfilcuentamaestro.nomperfil"></td>
                                    <td v-text="perfilcuentamaestro.descripcion"></td>
                                    <td v-text="perfilcuentamaestro.nomtipocomprobante"></td>
                                    <td v-text="perfilcuentamaestro.nommodulo"></td>
                                    
                                    <td>
                                        <div v-if="perfilcuentamaestro.completo">
                                            <span class="badge badge-success">Completo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Incompleto</span>
                                        </div>
                                        <div v-if="perfilcuentamaestro.activo">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Inactivo</span>
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
            
            <!--Inicio del modal agregar/actualizar Perfil cuenta maestro-->
            <form @submit.prevent="validateBeforeSubmit" accept-charset="UTF-8">
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            
                                <div class="form-group row">
                                    <label class="col-md-4 form-control-label" for="text-input">Nombre del Perfil de Cuenta</label>
                                    <div class="col-md-8">
                                        <input  v-validate.initial="'required'"
                                                type="text" 
                                                v-model="nomperfilcuentamaestro" 
                                                class="form-control" 
                                                placeholder="Nombre de Perfil de Cuenta"
                                                name="Nombre de Perfil de Cuenta">   

                                        <span class="text-error">{{ errors.first('Nombre de Perfil de Cuenta')}}</span>   <!--Lineas Agregadas<-->                                     

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-control-label" for="text-input">Descripcion del Perfil de Cuenta</label>
                                    <div class="col-md-8">
                                        <input  v-validate.initial="'required'"
                                                type="text" 
                                                v-model="descripcionperfilcuentamaestro" 
                                                class="form-control" 
                                                placeholder="Descripcion de Perfil de Cuenta"
                                                name="Descripcion de Perfil de Cuenta">   

                                        <span class="text-error">{{ errors.first('Descripcion de Perfil de Cuenta')}}</span>   <!--Lineas Agregadas<-->                                     

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-control-label" for="text-input">Modulo</label>
                                    <div class="col-md-8">
                                        <select class="form-control" 
                                                v-model="idmodulo"
                                                v-validate.initial="'required'"
                                                name="Modulo">
                                            <option value="" selected="selected" disabled>Seleccione...</option>
                                            <option v-for="modulo in arrayModulo" :key="modulo.idmodulo" :value="modulo.idmodulo" v-text="modulo.nommodulo"></option>
                                        </select>                                        
                                        <span class="text-error">{{ errors.first('Modulo')}}</span>   <!--Lineas Agregadas<-->
                                    </div>
                                </div>
                                 <div class="form-group row">
                                    <label class="col-md-4 form-control-label" for="text-input">Tipo de Comprobante</label>
                                    <div class="col-md-8">
                                        <select class="form-control" 
                                                v-model="idtipocomprobante"
                                                v-validate.initial="'required'" 
                                                name="Tipocomprobante">
                                            <option value="" selected="selected" disabled>Seleccione...</option>
                                            <option v-for="tipocomprobante in arrayTipocomprobante" :key="tipocomprobante.idtipocomprobante" :value="tipocomprobante.idtipocomprobante" v-text="tipocomprobante.nomtipocomprobante"></option>
                                        </select>                                        
                                        <span class="text-error">{{ errors.first('Tipocomprobante') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="chekbox" class="col-md-4">¿Comprobar Porcentaje?</label>
                                    <div class="col-md-8">
                                        <label class="switch switch-pill switch-success">
                                            <input class="switch-input" 
                                                    v-model="siporcentaje"
                                                    type="checkbox" 
                                                    checked="">
                                            <span class="switch-slider"></span>
                                        </label>    
                                    </div>
                                </div>

                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- modificar boton aceptar -->
                            <input  :disabled="!iscomplete" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPerfilcuentamaestro()" value="Guardar">
                            <button :disabled="!iscomplete" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPerfilcuentamaestro()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            </form>
            <!--Fin del modal-->

            <!--Inicio del modal Perfildecuenta Detalle-->
            <!--<form @submit.prevent="validateBeforeSubmit">-->
            
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modaldetalle}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModalDetalle"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body1">
                            
                            <h2>Debed</h2>
                            <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Cod Cuenta</th>
                                    <th>Nom Cuenta</th>
                                    <th>Porcentaje %</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="perfilcuentadetalled in arrayDetalleD" :key="perfilcuentadetalled.idperfilcuentadetalle">
                                    <td v-text="perfilcuentadetalled.codcuenta"></td>
                                    <td v-text="perfilcuentadetalled.nomcuenta"></td>
                                    <td v-text="perfilcuentadetalled.porcentaje"></td>
                                </tr> 
                            </tbody>
                            </table>
                            <div class="row">
                            <template v-if="restod===0">
                                <div class="form-group col-sm-3">
                                    <Ajaxselect  
                                        ruta="/con_cuentas/selectBuscarcuenta2?buscar=" @found="cuentas" @cleaning="clean"
                                        resp_ruta="cuentas"
                                        labels="cuentas"
                                        placeholder="Ingrese texto" 
                                        idtabla="idcuenta"
                                        :clearable='true'>

                                    </Ajaxselect>
                                    <!-- <v-select 
                                            v-validate.initial="'required'"
                                            ref='combo_debe'
                                            name="Cuenta Debe" 
                                            label="codcuenta"
                                            :options="arrayCuenta"
                                            v-model="pruebad"  
                                            placeholder="Cuenta Debe"
                                            :reduce="json =>[json.idcuenta,json.codcuenta,json.nomcuenta]"
                                            disabled> 
                                        <span slot="no-options">No existen coincidencias</span> 
                                    </v-select>  -->
                                    <span class="text-error">{{ errors.first('Cuenta Debe')}}</span>   <!--Lineas Agregadas<-->
                                </div>
                                <div class="form-group col-sm-4">
                                    <h6><label v-if="pruebad" v-text="pruebad[2]" class="text-info"></label></h6>
                                </div>
                                <div class="form-group col-sm-6">
                                    <div class="controls">
                                        <div class="input-prepend input-group">
                                            <input  class="form-control" id="appendedPrependedInput" size="16" type="text"
                                                    v-validate.initial="'required'"
                                                    v-model="restod" 
                                                    :placeholder="restod+'%'"
                                                    name="% Debe"
                                                    disabled>
                                            <div class="input-group-prepend">
                                            <span class="input-group-text">%</span>
                                            </div>
                                            <span class="text-error" >{{ errors.first('% Debe')}}</span>   <!--Lineas Agregadas<-->
                                        </div>
                                    </div>
                                </div>
                              <!--  <div class="form-group col-sm-2">
                                    <span class="text-error"><b> {{ mensajesaldo_d }}</b></span> 
                                </div>-->
                            </template>
                            <template v-else>
                                <div class="form-group col-sm-8">
                                     <Ajaxselect  
                                        ruta="/con_cuentas/selectBuscarcuenta2?buscar=" @found="cuentas" @cleaning="clean"
                                        resp_ruta="cuentas"
                                        labels="cuentas"
                                        placeholder="Ingrese texto" 
                                        idtabla="idcuenta"
                                        :clearable='true'>

                                    </Ajaxselect>
                                    <!-- <v-select 
                                            ref='combo_debe'
                                            v-validate.initial="'required'"
                                            name="Cuenta Debe" 
                                            label="codcuenta"
                                            :options="arrayCuenta"
                                            v-model="pruebad"  
                                            placeholder="Cuenta Debe"
                                            :reduce="json =>[json.idcuenta,json.codcuenta,json.nomcuenta]"> 
                                        <span slot="no-options">No existen coincidencias</span> 
                                    </v-select>  -->
                                    <span class="text-error">{{ errors.first('Cuenta Debe')}}</span>   <!--Lineas Agregadas<-->
                                </div>
                                <div class="form-group col-sm-4">
                                    <h6><label v-if="pruebad" v-text="pruebad[2]" class="text-info"></label></h6>
                                </div>
                                <div class="form-group col-sm-3">
                                    <div class="controls">
                                        <div class="input-prepend input-group">
                                           <input  class="form-control" id="appendedPrependedInput" size="16" type="text"
                                                    v-validate.initial="'required'"
                                                    v-model="restod" 
                                                    :placeholder="'%'"
                                                    name="% Debe"
                                                    @input="saldoporcentajed()"
                                                    >
                                            <div class="input-group-prepend">
                                            <span class="input-group-text">%</span>
                                            </div>
                                            <span class="text-error" >{{ errors.first('% Debe')}}</span>   <!--Lineas Agregadas<-->
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-2">
                                    <span class="text-error"><b> {{ mensajesaldo_d }}</b></span> 
                                    
                                </div>
                            </template>
                            </div>
                        <h2>Haber</h2>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Cod Cuenta</th>
                                    <th>Nom Cuenta</th>
                                    <th>Porcentaje %</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="perfilcuentadetalleh in arrayDetalleH" :key="perfilcuentadetalleh.idperfilcuentadetalle">
                                    <td v-text="perfilcuentadetalleh.codcuenta"></td>
                                    <td v-text="perfilcuentadetalleh.nomcuenta"></td>
                                    <td v-text="perfilcuentadetalleh.porcentaje"></td>
                                </tr>                                
                            </tbody>
                        </table>
                        <div class="row">
                            <template v-if="restoh===0">
                                <div class="form-group col-sm-3">
                                    <v-select 
                                        ref='combo_haber'
                                        v-validate.initial="'required'"
                                        name="Cuenta Haber" 
                                        label="codcuenta"
                                        :options="arrayCuenta"
                                        v-model="pruebah"  
                                        placeholder="Cuenta Haber"
                                        disabled
                                        :reduce="json =>[json.idcuenta,json.codcuenta,json.nomcuenta]" > <!--@input="selec(pruebah)"-->
                                        <span slot="no-options">No existen coincidencias</span> 
                                    </v-select> 
                                    <span class="text-error">{{ errors.first('Cuenta Haber')}}</span>   <!--Lineas Agregadas<-->
                                </div>
                                <div class="form-group col-sm-4">
                                    <h6><label v-if="pruebah" v-text="pruebah[2]" class="text-info"></label></h6>
                                </div>
                                <div class="form-group col-sm-3">
                                    <div class="controls">
                                        <div class="input-prepend input-group">
                                            <input  class="form-control" id="appendedPrependedInput" size="16" type="text"
                                                    v-validate.initial="'required'"
                                                    v-model="restoh" 
                                                    :placeholder="restoh+'%'"
                                                    name="% Haber"
                                                    disabled>
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">%</span>
                                            </div>
                                             <span class="text-error">{{ errors.first('% Haber')}}</span>   <!--Lineas Agregadas<-->
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <template v-else>
                                <div class="form-group col-sm-3">
                                    <v-select 
                                        ref='combo_haber'
                                        v-validate.initial="'required'"
                                        name="Cuenta Haber" 
                                        label="codcuenta"
                                        :options="arrayCuenta"
                                        v-model="pruebah"  
                                        placeholder="Cuenta Haber"
                                        :reduce="json =>[json.idcuenta,json.codcuenta,json.nomcuenta]">
                                        <span slot="no-options">No existen coincidencias</span> 
                                    </v-select> 
                                    <span class="text-error">{{ errors.first('Cuenta Haber')}}</span>   <!--Lineas Agregadas<-->
                                </div>
                                <div class="form-group col-sm-4">
                                    <h6><label v-if="pruebah" v-text="pruebah[2]" class="text-info"></label></h6>
                                </div>
                                <div class="form-group col-sm-3">
                                    <div class="controls">
                                        <div class="input-prepend input-group">
                                            <input  class="form-control" id="appendedPrependedInput" size="16" type="text"
                                                    v-validate.initial="'required'"
                                                    v-model="restoh" 
                                                    :placeholder="'%'"
                                                    name="% Haber"
                                                    @input="saldoporcentajeh()"
                                                    >
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        <span class="text-error">{{ errors.first('% Haber')}}</span>   <!--Lineas Agregadas<-->
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-2">
                                    <span class="text-error"><b> {{ mensajesaldo_h }}</b></span> 
                                    
                                </div>

                            </template>
                        </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- modificar boton aceptar -->
                            <input :disabled="!iscompletedetalle" v-if="tipoAccion==1"  type="submit"  class="btn btn-primary" @click="registrarPerfildetalle1()" value="Guardar">
                            <!--
                                <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPerfilcuentamaestro()">Actualizar</button>-->
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            
            <!--Fin del modal-->

            <!--Inicio del modal Perfildecuenta Detalle no porcentaje -->
            <!--<form @submit.prevent="validateBeforeSubmit">-->
            
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modaldetallenoporcentaje}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModalDetalle"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body1">
                            
                            <h2>Debe Solo Cuenta</h2>
                            <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    
                                    <th>Cod Cuenta</th>
                                    <th>Nom Cuenta</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="perfilcuentadetalled in arrayDetalleD" :key="perfilcuentadetalled.idperfilcuentadetalle">
                                <!--                                    
                                    <td>
                                        <button type="button" @click="abrirModalDetalle('registrar',perfilcuentadetalle)" class="btn btn-warning btn-sm">
                                          <i class="cui-cog"></i>
                                        </button> &nbsp;
                                        <template v-if="perfilcuentadetalled.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarPerfilcuentamaestro(perfilcuentadetalle.idperfilcuentadetalle)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarPerfilcuentamaestro(perfilcuentadetalle.idperfilcuentadetalle)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    -->
                                    <td v-text="perfilcuentadetalled.codcuenta"></td>
                                    <td v-text="perfilcuentadetalled.nomcuenta"></td>
                                    <!--
                                    <td>
                                        <div v-if="perfilcuentadetalled.activo">
                                            <span class="badge badge-success">activado</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">desactivado</span>
                                        </div>
                                    </td>
                                    -->
                                </tr> 
                            </tbody>
                            </table>
                            <div class="row">
                                <div class="form-group col-sm-3">
                                    <v-select 
                                            ref='combodebe'
                                            v-validate.initial="'required'"
                                            name="Cuenta Debe" 
                                            label="codcuenta"
                                            :options="arrayCuenta"
                                            v-model="pruebad"  
                                            placeholder="Cuenta Debe"
                                            :reduce="json =>[json.idcuenta,json.codcuenta,json.nomcuenta]"> 
                                        <span slot="no-options">No existen coincidencias</span> 
                                    </v-select> 
                                    <span class="text-error">{{ errors.first('Cuenta Debe')}}</span>   <!--Lineas Agregadas<-->
                                </div>
                                <div class="form-group col-sm-9">
                                    <h6><label v-if="pruebad" v-text="pruebad[2]" class="text-info"></label></h6>
                                </div>
                            </div>
                        <h2>Haber Solo Cuenta</h2>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                   
                                    <th>Cod Cuenta</th>
                                    <th>Nom Cuenta</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="perfilcuentadetalleh in arrayDetalleH" :key="perfilcuentadetalleh.idperfilcuentadetalle">
                                   <!--
                                    <td>
                                        <button type="button" @click="abrirModalDetalle('registrar',perfilcuentadetalle)" class="btn btn-warning btn-sm">
                                          <i class="cui-cog"></i>
                                        </button> &nbsp;
                                        <template v-if="perfilcuentadetalleh.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarPerfilcuentamaestro(perfilcuentadetalle.idperfilcuentadetalle)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarPerfilcuentamaestro(perfilcuentadetalle.idperfilcuentadetalle)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    -->
                                    <td v-text="perfilcuentadetalleh.codcuenta"></td>
                                    <td v-text="perfilcuentadetalleh.nomcuenta"></td>
                                    <!--
                                    <td>
                                    
                                        <div v-if="perfilcuentadetalleh.activo">
                                            <span class="badge badge-success">activado</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">desactivado</span>
                                        </div>
                                    </td>
                                    -->
                                </tr>                                
                            </tbody>
                        </table>
                        <div class="row">
                                <div class="form-group col-sm-3">
                                    <v-select 
                                        ref='combohaber'
                                        v-validate.initial="'required'"
                                        name="Cuenta Haber" 
                                        label="codcuenta"
                                        :options="arrayCuenta"
                                        v-model="pruebah"  
                                        placeholder="Cuenta Haber"
                                        :reduce="json =>[json.idcuenta,json.codcuenta,json.nomcuenta]">
                                        <span slot="no-options">No existen coincidencias</span> 
                                    </v-select> 
                                    <span class="text-error">{{ errors.first('Cuenta Haber')}}</span>   <!--Lineas Agregadas<-->
                                </div>
                                <div class="form-group col-sm-7">
                                    <h6><label v-if="pruebah" v-text="pruebah[2]" class="text-info"></label></h6>
                                </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group row">
                                <label for="chekbox" class="col-md-4">¿Finalizar Perfil?</label>
                                <div class="col-md-8">
                                <template v-if="finalizarperfil">
                                    <label class="switch switch-pill switch-success">
                                        <input class="switch-input" 
                                                v-model="finalizarperfil1"
                                                type="checkbox" 
                                                checked=""
                                                disabled>
                                        <span class="switch-slider"></span>
                                    </label>    
                                </template>
                                <template v-else>
                                    <label class="switch switch-pill switch-success">
                                        <input class="switch-input" 
                                                v-model="finalizarperfil1"
                                                type="checkbox" 
                                                checked="">
                                        <span class="switch-slider"></span>
                                    </label>    
                                </template>
                                </div>
                        </div>
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <!-- modificar boton aceptar -->
                        <input :disabled="!iscompletedetallenoporcentaje" v-if="tipoAccion==1"  type="submit"  class="btn btn-primary" @click="registrarPerfildetalleNoPorcentaje()" value="Guardar">
                        <!--
                            <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPerfilcuentamaestro()">Actualizar</button>-->
                    </div>
                </div>
                    <!-- /.modal-content -->
            </div>
                <!-- /.modal-dialog -->
        </div>
            
            <!--Fin del modal-->
        </main>
</template>

<script>
    import VueBarcode from 'vue-barcode';
    import Vue from 'vue';
    import VeeValidate from 'vee-validate';
    import vSelect from 'vue-select'

    //import vSelect from 'vue-select';
    //Vue.component( 'v-select', vSelect );

    //Vue.component('v-select', VueSelect.VueSelect)

    const VueValidationEs = require('vee-validate/dist/locale/es');
        Vue.use(VeeValidate, 
        {
            locale: 'es',
            dictionary: 
            {
                es: VueValidationEs
            }
        });
    
    Vue.use(VeeValidate);

    Vue.component('v-select',vSelect)
    export default {
        data (){
            return {
                idcuenta:[],
                pruebad:'',
                pruebah:'',
                arrayCuenta:[],
                arrayCuentas:[],
                perfilcuentamaestro_id: 0,
                perfilcuentadetalle_id:0,
                idmodulo : '',
                idtipocomprobante:'',
                nomperfilcuentamaestro : '',
                descripcionperfilcuentamaestro:'',
                nommodulo:'',
                arrayPerfilcuentamaestro : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorPerfilcuentamaestro : 0,
                errorMostrarMsjPerfilcuentamaestro : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : '',//'nomperfilcuentamaestro',
                buscar : '',
                arrayModulo :[],
                modaldetalle:0,
                modaldetallenoporcentaje:0,
                arrayModulo:[],
                arrayTipocomprobante:[],
                tituloModalDetalle:'',
                arrayDetalleD:[],
                arrayDetalleH:[],
                porcentaje:'',
                restoh:100,
                restod:100,
                saldo_d:0,
                max_h:0,
                max_d:0,
                mensajesaldo_d:'',
                mensajesaldo_h:'',
                errord:0,
                errorh:0,
                siporcentaje:true,
                finalizarperfil:false,
                finalizarperfil1:false,

                
                
                
            }
        },
        components: {
        'barcode': VueBarcode
    },
        computed:{
            isZero:function(){
                

            },
            iscomplete:function(){
                return this.nomperfilcuentamaestro && this.descripcionperfilcuentamaestro && this.idmodulo && this.idtipocomprobante;
            },
            iscompletedetallenoporcentaje:function(){
                var correcto=false;
                if(!this.finalizarperfil)
                {
                    //console.log (this.arrayDetalleD.length)
                    if(this.arrayDetalleD.length==0 || this.arrayDetalleH.length==0)
                        if(this.pruebad && this.pruebah)
                            correcto=true;
                        else
                            correcto=false;
                    else
                        correcto=true;
                }
                else
                    correcto=false;
                    
                return correcto

            },
            iscompletedetalle:function(){
                var correcto=false;
                if(!this.finalizarperfil)
                {
                    if(this.max_d===0 )
                    {
                        if(this.max_h===0)
                            correcto=false;
                        else
                        {
                            if(this.pruebah && this.errorh)
                                correcto=true;
                        }
                    }   
                    else
                    {
                        if(this.max_h===0)
                        {
                            if(this.pruebad && this.errord)
                                correcto=true;
                        }
                        else
                        {
                            if(this.pruebad && this.errord && this.pruebah && this.errorh)
                                correcto=true;
                        }
                    }
                }
                else
                    correcto=false;

                return correcto

            },
            isActived: function(){
                return this.pagination.current_page;
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

            }
        }, 
        methods : {
            cuentas(cuentas){ 
                this.idcuenta=[];
               // this.arrayCuenta.push({cuentita:cuentas.idcuenta})         
                //console.log(cuentas);
                for (const key in cuentas) {
                    if (cuentas.hasOwnProperty(key)) {
                        const element = cuentas[key];
                        //console.log(element);
                        this.idcuenta.push(element);
                        
                    }
                }
                //console.log(this.idcuenta);
                
            },
            clean(){
                this.idcuenta=[];
            //console.log('clean')
            
            },
            actualizarmaestro(idperfilmaestro){
                let me=this;
                //console.log(fecha_ap+' fecha_ap');
                axios.post('/con_perfilcuentadetalle',{
                        'idmaestro': idperfilmaestro,
                    })
                    .then(function(response){
                    //console.log(response.data)
                    })

                    .catch(function (error) {
                        console.log(error);
                    })
            },
            saldoporcentajed(){
                let me=this;
                var saldo=0;
                saldo=this.max_d-me.restod;
               // console.log(saldo + 'saldod')
                if(saldo==this.max_d)
                {
                    me.mensajesaldo_d='Incorrecto El valor No debe ser Cero ';
                    me.errord=0; 
                }
                else
                {
                    if(saldo<0 || me.restod=='')
                    {
                        me.mensajesaldo_d='Incorrecto El valor debe ser menor a '+this.max_d;
                        me.errord=0;
                    }
                    else
                    {
                        me.mensajesaldo_d='Valor correcto';
                        me.errord=1;
                    }
                 }
                //console.log(me.restod+'resto')
            },
             saldoporcentajeh(){
                let me=this;
                var saldo=0;
                saldo=this.max_h-me.restoh;
                //console.log(saldo + ' saldoh '+ me.restoh+' restoh')
                 if(saldo==this.max_h)
                {
                    me.mensajesaldo_h='Incorrecto El valor No debe ser Cero ';
                    me.errorh=0; 
                }
                else
                {
                    if(saldo>=0)
                    {
                        me.mensajesaldo_h='Valor correcto';
                        me.errorh=1;
                    }
                    else
                    {
                        me.mensajesaldo_h='Incorrecto El valor debe ser menor a '+this.max_h;
                        me.errorh=0;
                    }
                }
                //console.log(me.restod+'resto')
            },
            recorrerarrayd(){
                let me= this;
                me.max_d=0;
                var restod=100;
                me.arrayDetalleD.forEach(element => {
                   // console.log(element.porcentaje)
                    me.restod=me.restod-element.porcentaje;
                });
               // console.log(me.restod + ' restod; '+me.max_d+' maxd');
                me.max_d=me.restod;
                this.saldoporcentajed();
            },
            recorrerarrayh(){
                let me= this;
                me.max_h=0;
                var restoh=100;
                me.arrayDetalleH.forEach(element => {
                    //console.log(element.porcentaje)
                    me.restoh=me.restoh-element.porcentaje;
                    
                });
               // console.log(me.restoh + ' restoh')
                me.max_h=me.restoh;
                this.saldoporcentajeh();
            },
            selec(id){
               // console.log(id);
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
            selectCuenta(){
                
                let me=this;
                me.arrayCuentas=[];
                var url= '/con_cuentas/selectBuscarcuenta';
                axios.get(url).then(function (response) {
                    /*response.data.forEach((arrayCuentass)=>{
                        me.arrayCuentas.push(arrayCuentass);
                        console.log(me.arrayCuentas)
                    });
                    */
                    var respuesta= response.data;
                    
                    me.arrayCuenta = respuesta;
                   // console.log(me.arrayCuenta)

                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            verificarperfilfinalizado(idmaestro)
            {
                let me=this;
                var url='/con_perfilcuentamaestro/selectmaestrofinalizado?idmaestro='+idmaestro;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    //console.log(respuesta.perfilcuentamaestros+ 'respuests');
                    me.finalizarperfil=respuesta.perfilcuentamaestros;
                    
                    //console.log(me.arrayPerfilcuentamaestro)
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },
            listarPerfilcuentamaestro (page,buscar,criterio){
                let me=this;
                var url= '/con_perfilcuentamaestro?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayPerfilcuentamaestro = respuesta.perfilcuentamaestros.data;
                    me.pagination= respuesta.pagination;
                    //console.log(me.arrayPerfilcuentamaestro)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectModulo(){
                let me=this;
                var url= '/par_modulo/selectModulo';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayModulo = respuesta.modulos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectTipocomprobante(){
                let me=this;
                var url= '/con_tipocomprobante/selectTipocomprobante';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayTipocomprobante = respuesta.tipocomprobantes;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectPerfilcuentadetalle(idmaestro,tipocargo){
                let me=this;
                switch (tipocargo) {
                    case 'debe':
                    {
                        var url= '/con_perfilcuentadetalle/selectPerfilcuentadetalle?idmaestro='+idmaestro+'&tipocargo=d';
                        axios.get(url).then(function (response) {
                            //console.log(response);
                            var respuesta= response.data;
                            me.arrayDetalleD = respuesta.perfilcuentadetalles;
                            me.recorrerarrayd();
                            })
                        .catch(function (error) {
                            console.log(error);
                        });
                        break;
                    }
                    case 'haber':
                    {
                        var url= '/con_perfilcuentadetalle/selectPerfilcuentadetalle?idmaestro='+idmaestro+'&tipocargo=h';
                        axios.get(url).then(function (response) {
                            //console.log(response);
                            var respuesta= response.data;
                            me.arrayDetalleH = respuesta.perfilcuentadetalles;
                            me.recorrerarrayh();
                            })
                        .catch(function (error) {
                            console.log(error);
                        });
                        break;
                    }
                }
                
                
            },
            

            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarPerfilcuentamaestro(page,buscar,criterio);
            },
            registrarPerfilcuentamaestro(){
                /*if (this.validarPerfilcuentamaestro()){
                    return;
                }*/
                
                let me = this;

                axios.post('/con_perfilcuentamaestro/registrar',{
                    'nomperfilcuentamaestro':this.nomperfilcuentamaestro,
                    'descripcion':this.descripcionperfilcuentamaestro,
                    'idmodulo': this.idmodulo,
                    'idtipocomprobante':this.idtipocomprobante,
                    'siporcentaje':this.siporcentaje
                }).then(function (response) {
                    if(response.data.length){
                    swal(
                            'El Valor ya Existe',
                            'Ingresa un dato Diferente',
                            'error'
                       )                    }
                    else{
                        me.cerrarModal();
                        me.listarPerfilcuentamaestro(1,'','nomperfilcuentamaestro');
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            },
            registrarPerfildetalle1(){
                let me=this;
                 if(this.max_d===0 )
                {
                    if(this.max_h===0)
                        correcto=false;
                    else
                    {
                        axios.post('/con_perfilcuentadetalle/registrar',{
                            'idmaestro':me.perfilcuentamaestro_id,
                            'idcuentah':me.pruebah[0],
                            'porcentajeh': me.restoh,
                        }).then(function (response) {
                            swal(
                                    'Registrado correctamente'
                            )                    
                                
                            me.actualizarmaestro(me.perfilcuentamaestro_id);
                            me.selectPerfilcuentadetalle(me.perfilcuentamaestro_id,'haber')
                            me.$refs.combo_haber.clearSelection(); 
                            me.arrayDetalleH=[];
                            me.pruebah=[];
                            me.restoh=100;
                            me.max_h=0;
                            me.listarPerfilcuentamaestro(1,'','nomperfilcuentamaestro');
                            
                                
                        }).catch(function (error) {
                            console.log(error);
                        });
                       // console.log(me.pruebah+' ' + me.restoh)
                    }
                }   
                else
                {
                    if(this.max_h===0)
                    {
                        
                        axios.post('/con_perfilcuentadetalle/registrar',{
                            'idmaestro':me.perfilcuentamaestro_id,
                            'idcuentad':me.pruebad[0],
                            'porcentajed': me.restod,
                        }).then(function (response) {
                            swal(
                                    'Registrado correctamente'
                            )                    
                                me.actualizarmaestro(me.perfilcuentamaestro_id);
                                me.selectPerfilcuentadetalle(me.perfilcuentamaestro_id,'debe');
                                me.$refs.combo_debe.clearSelection(); 
                                me.arrayDetalleD=[];
                                me.pruebad=[];
                                me.restod=100;
                                me.max_d=0;
                                me.listarPerfilcuentamaestro(1,'','nomperfilcuentamaestro');
                            
                            
                        }).catch(function (error) {
                            console.log(error);
                        });
                    
                      //  console.log(me.pruebad+' '+me.restod)
                    }
                    else
                    {
                        
                        axios.post('/con_perfilcuentadetalle/registrar',{
                            'idmaestro':me.perfilcuentamaestro_id,
                            'idcuentad':me.pruebad[0],
                            'porcentajed': me.restod,
                            'idcuentah':me.pruebah[0],
                            'porcentajeh': me.restoh,
                        }).then(function (response) {
                            swal(
                                    'Registrado correctamente'
                            )                    
                                me.actualizarmaestro(me.perfilcuentamaestro_id);
                                
                                me.selectPerfilcuentadetalle(me.perfilcuentamaestro_id,'debe');
                                me.selectPerfilcuentadetalle(me.perfilcuentamaestro_id,'haber');
                                me.$refs.combo_debe.clearSelection(); 
                                me.$refs.combo_haber.clearSelection(); 
                                me.arrayDetalleD=[];
                                me.arrayDetalleH=[];
                                me.pruebad=[];
                                me.pruebah=[];
                                me.restoh=100;
                                me.restod=100;
                                me.max_h=0;
                                me.max_d=0;
                                me.listarPerfilcuentamaestro(1,'','nomperfilcuentamaestro');
                              
                             
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }
                }
                
            },

            registrarPerfildetalleNoPorcentaje(){
                let me=this;
                
                    if(me.finalizarperfil1)
                        this.finalizarcuenta(me.perfilcuentamaestro_id)
                    axios.post('/con_perfilcuentadetalle/registrar',{
                        'idmaestro':me.perfilcuentamaestro_id,
                        'idcuentad':me.pruebad[0],
                        'idcuentah':me.pruebah[0],
                        
                    }).then(function (response) {
                        swal(
                                'Registrado correctamente'
                        )                    
                            //
                            me.selectPerfilcuentadetalle(me.perfilcuentamaestro_id,'debe');
                            me.selectPerfilcuentadetalle(me.perfilcuentamaestro_id,'haber');
                            me.verificarperfilfinalizado(me.perfilcuentamaestro_id);
                            me.$refs.combodebe.clearSelection(); 
                            me.$refs.combohaber.clearSelection(); 
                            me.arrayDetalleD=[];
                            me.arrayDetalleH=[];
                            me.pruebad=[];
                            me.pruebah=[];
                            
                           
                    }).catch(function (error) {
                        console.log(error);
                    });
                    // console.log(me.pruebah+' ' + me.restoh)
                //this.actualizarmaestro();
                this.listarPerfilcuentamaestro(1,'','nomperfilcuentamaestro');
            },
            actualizarPerfilcuentamaestro(){
               /*if (this.validarPerfilcuentamaestro()){
                    return;
                }*/
                let me = this;
                axios.put('/con_perfilcuentamaestro/actualizar',{
                    'idmaestro':this.perfilcuentamaestro_id,
                    'idmodulo': this.idmodulo,
                    'nomperfilcuentamaestro': this.nomperfilcuentamaestro,
                    'descripcionperfilcuentamaestro':this.descripcionperfilcuentamaestro,
                    'idtipocomprobante': this.idtipocomprobante

                }).then(function (response) {
                    if(response.data.length){
                    swal(
                            'El Valor ya Existe',
                            'Ingresa un dato Diferente',
                            'error'
                       )
                    }
                    else{
                        me.cerrarModal();
                        me.listarPerfilcuentamaestro(1,'','nomperfilcuentamaestro');
                    }
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            desactivarPerfilcuentamaestro(idperfilcuentamaestro){
               swal({
                title: 'Esta seguro de desactivar este Perfil de Cuenta?',
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
                    //console.log(idperfilcuentamaestro);
                    axios.put('/con_perfilcuentamaestro/desactivar',{
                        'idmaestro': idperfilcuentamaestro
                    }).then(function (response) {
                        me.listarPerfilcuentamaestro(1,'','nomperfilcuentamaestro');
                        swal(
                        'Desactivado!',
                        'El registro ha sido desactivado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
            
            finalizarcuenta(idperfilcuentamaestro){
                let me=this;
                axios.put('/con_perfilcuentamaestro/finalizarcuenta',{
                    'idmaestro': idperfilcuentamaestro
                }).then(function (response) {
                    me.listarPerfilcuentamaestro(1,'','nomperfilcuentamaestro');
                }).catch(function (error) {
                    console.log(error);
                });
            },



            activarPerfilcuentamaestro(idperfilcuentamaestro){
               swal({
                title: 'Esta seguro de activar esta Perfilcuentamaestro?',
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

                    axios.put('/con_perfilcuentamaestro/activar',{
                        'idmaestro': idperfilcuentamaestro
                    }).then(function (response) {
                        me.listarPerfilcuentamaestro(1,'','nomperfilcuentamaestro');
                        swal(
                        'Activado!',
                        'El registro ha sido activado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
            cerrarModal(){
                let me= this;
                me.modal=0;
                me.modaldetalle=0;
                me.tituloModal='';
                me.idmodulo= '';
                me.nommodulo = '';
                me.modaldetallenoporcentaje=0;
                
                me.nomperfilcuentamaestro = '';
                me.errorPerfilcuentamaestro=0;
                me.restoh=100;
                me.restod=100;

                me.pruebad=null;
                me.pruebah=null;
                me.arrayCuenta=[];
//                me.vSelect=null
                me.$refs.combo_debe.clearSelection(); 
                me.$refs.combohaber.clearSelection();
                me.errord=0;
                me.errorh=0;
                me.siporcentaje=true;
                me.listarPerfilcuentamaestro(1,'','nomperfilcuentamaestro');
            },
            abrirModal(modelo, accion, data = []){
                let me=this;
                switch(modelo){
                    case "perfilcuentamaestro":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                
                                me.$refs.combodebe.clearSelection(); 
                                me.$refs.combohaber.clearSelection(); 
                                me.modal = 1;
                                me.tituloModal = 'Registrar Perfil de Cuenta Maestro';
                                me.idmodulo='';
                                me.nommodulo='';
                                me.nomperfilcuentamaestro= '';
                                me.idtipocomprobante='';
                                me.descripcionperfilcuentamaestro='';
                                me.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                me.modal=1;
                                me.tituloModal='Actualizar Perfil de Cuenta Maestro';
                                me.tipoAccion=2;
                                me.perfilcuentamaestro_id=data['idperfilcuentamaestro'];
                                me.idmodulo=data['idmodulo'];
                                me.nomperfilcuentamaestro=data['nomperfil'];
                                me.descripcionperfilcuentamaestro=data['descripcion'];
                                me.idtipocomprobante=data['idtipocomprobante'];
                                
                                break;
                            }
                        }
                    }
                }
                me.selectModulo();
            },
            abrirModalDetalle(accion,data = []){
                let me=this;
                switch(accion){
                    case 'registrar':
                    {
                        //vSelect=null;
                        me.selectCuenta();
                        me.$refs.combodebe.clearSelection(); 
                        me.$refs.combohaber.clearSelection(); 
                        me.pruebad='';
                        me.perfilcuentamaestro_id=data['idperfilcuentamaestro'];
                        me.modaldetalle = 1;
                        me.tituloModalDetalle = 'Registrar Perfil Cuenta Detalle';
                        me.idmodulo='';
                        me.nommodulo='';
                        me.nomperfilcuentamaestro= '';
                        me.tipoAccion = 1;
                        me.finalizarperfil=data['completo'];
                        me.selectPerfilcuentadetalle(me.perfilcuentamaestro_id,'debe');
                        me.selectPerfilcuentadetalle(me.perfilcuentamaestro_id,'haber');
                        break;
                    }
                    case 'actualizar':
                    {
                        //console.log(data);
                        me.modal=1;
                        me.tituloModal='Actualizar Perfilcuentamaestro';
                        me.tipoAccion=2;
                        me.perfilcuentamaestro_id=data['idperfilcuentamaestro'];
                        me.idmodulo=data['idmodulo'];
                        
                        me.nomperfilcuentamaestro = data['nomperfilcuentamaestro'];
                        break;
                    }
                }
            },
            abrirModalDetallenoporcentaje(accion,data = []){
                let me=this;
                switch(accion){
                    case 'registrar':
                    {
                        //vSelect=null;
                        me.selectCuenta();
                        me.$refs.combodebe.clearSelection(); 
                        me.$refs.combohaber.clearSelection(); 
                        me.pruebad='';
                        me.perfilcuentamaestro_id=data['idperfilcuentamaestro'];
                        me.modaldetallenoporcentaje = 1;
                        me.tituloModalDetalle = 'Registrar Perfil Cuenta Detalle Sin Porcentaje';
                        me.idmodulo='';
                        me.nommodulo='';
                        me.nomperfilcuentamaestro= '';
                        me.tipoAccion = 1;
                        me.finalizarperfil=data['completo'];
                        me.finalizarperfil1=data['completo'];
                        me.selectPerfilcuentadetalle(me.perfilcuentamaestro_id,'debe');
                        me.selectPerfilcuentadetalle(me.perfilcuentamaestro_id,'haber');
                        break;
                    }
                    case 'actualizar':
                    {
                        //console.log(data);
                        me.modal=1;
                        me.tituloModal='Actualizar Perfilcuentamaestro';
                        me.tipoAccion=2;
                        me.perfilcuentamaestro_id=data['idperfilcuentamaestro'];
                        me.idmodulo=data['idmodulo'];
                        
                        me.nomperfilcuentamaestro = data['nomperfilcuentamaestro'];
                        break;
                    }
                }
            }
        },
        mounted() {
            this.listarPerfilcuentamaestro(1,this.buscar,this.criterio);
            this.selectModulo();
            this.selectTipocomprobante();
            this.selectCuenta();
            /*this.saldoporcentajed();
            this.saldoporcentajeh();*/
        }
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-style: italic;
    }
    
::-webkit-scrollbar {
  width: 6px;
  border-radius: 10px;
}
::-webkit-scrollbar-track {
  background: transparent;  
}
::-webkit-scrollbar-thumb {
  background: #2186aab0; 
  border-radius: 10px;
}
::-webkit-scrollbar-thumb:hover {
  background: #276f8a; 
}
</style>
