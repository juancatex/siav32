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
                <button v-if="check('perfil_agregar')" type="button" @click="abrirModal('perfilcuentamaestro','registrar')" class="btn btn-secondary">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button>
                <button   type="button" @click="getReporte()" class="btn btn-danger" style="float: right;">
                     Reporte
                </button>
            </div>
            <div class="card-body">
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
                            <td style="text-align: center;">
                                
                                <template v-if="perfilcuentamaestro.completo">
                                    <button type="button" @click="abrirModalDetalle('actualizar',perfilcuentamaestro)" class="btn btn-success btn-sm">
                                        <i class="icon-check"></i>
                                    </button> &nbsp;
                                    <button type="button" @click="abrirModalDetalle('ver',perfilcuentamaestro)" class="btn btn-success btn-sm">
                                        <i class="icon-eye"></i>
                                    </button> &nbsp;
                                    <button v-if="check('perfil_eliminar')" type="button" class="btn btn-danger btn-sm" @click="desactivarPerfilcuentamaestro(perfilcuentamaestro.idperfilcuentamaestro)">
                                            <i class="icon-trash"></i>
                                    </button>
                                </template>
                                <template v-else>
                                    <button v-if="check('perfil_editar')" type="button" @click="abrirModalDetalle('registrar',perfilcuentamaestro)" class="btn btn-warning btn-sm">
                                    <i class="cui-cog"></i>
                                   </button> &nbsp;
                                </template>
                                 
                                <!-- <template v-if="perfilcuentamaestro.activo&&perfilcuentamaestro.completo">
                                    <button v-if="check('perfil_eliminar')" type="button" class="btn btn-danger btn-sm" @click="desactivarPerfilcuentamaestro(perfilcuentamaestro.idperfilcuentamaestro)">
                                        <i class="icon-trash"></i>
                                    </button>
                                </template>
                                <template v-else-if="perfilcuentamaestro.completo">
                                    <button type="button" class="btn btn-info btn-sm" @click="activarPerfilcuentamaestro(perfilcuentamaestro.idperfilcuentamaestro)">
                                        <i class="icon-check"></i>
                                    </button>
                                </template> -->
                            </td>

                            <!-- <td v-text="perfilcuentamaestro.nomperfil"></td> -->
                            <td><input   type="text" 
                                    :value="perfilcuentamaestro.nomperfil" 
                                    class="form-control" 
                                    placeholder="Nombre de Perfil de Cuenta" @keyup.enter="chancename($event,perfilcuentamaestro.idperfilcuentamaestro)" > </td>


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
    
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" id="perfilcuentamaestro" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" class="close" @click="cerrarModal('perfilcuentamaestro')" aria-label="Close">
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
                                    v-model="idmodulo1"
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
                    <button type="button" class="btn btn-secondary" @click="cerrarModal('perfilcuentamaestro')">Cerrar</button>
                    <!-- modificar boton aceptar -->
                    <input  :disabled="!iscomplete" type="submit" class="btn btn-primary" @click="registrarPerfilcuentamaestro()" value="Guardar">
                    <!-- <button :disabled="!iscomplete" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPerfilcuentamaestro()">Actualizar</button> -->
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    
    <!--Fin del modal-->

    <!--Inicio del modal Perfildecuenta Detalle siporcentaje-->
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" id='siporcentaje' style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModalSiPorcentaje"></h4>
                    <button type="button" class="close" @click="cerrarModal('siporcentaje')" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body1">
                    <!-- bloque debe -->
                    <div class="" >
                        <h2>Debe</h2>
                        <div class="" v-if="tipoAccion==2">
                            <table class="table table-bordered table-striped table-sm" style="margin-bottom: 0px;">
                            <thead>
                                <tr>
                                    <th style="width:80%">Cuenta</th>
                                    <th>Porcentaje</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="perfilcuentadetalled in arrayDetalleD" :key="perfilcuentadetalled.idperfilcuentadetalle">
                                    <td v-text="perfilcuentadetalled.codcuenta + ' ' + perfilcuentadetalled.nomcuenta"></td>
                                    <td v-text="perfilcuentadetalled.porcentaje"></td>
                                </tr> 
                            </tbody>
                            </table>
                            </div>
                        <div class="" v-if="tipoAccion==1">
                            <div class="row">
                                <div class="bg-info text-white border border-white col-md-8" >
                                    <strong><label>Cuenta</label></strong>
                                </div>
                                <div class="bg-info text-white border border-white col-md-2" >
                                    <strong><label>Porcentaje</label></strong>
                                </div>
                                <div class="bg-info text-white border border-white col-md-2"  style="text-align:center">
                                    <strong><label> + </label></strong>
                                </div>
                            </div>
                            <div v-for="(rowregistro, index) in rowregistrosdebe" :key="index" class="row">
                                <div class="border col-md-8" >
                                    <Ajaxselect  v-if="clearSelected"
                                        ruta="/con_cuentas/selectBuscarcuenta2?buscar=" @found="cuentas" @cleaning="clean"
                                        resp_ruta="cuentas"
                                        labels="cuentas"
                                        placeholder="Ingrese texto" 
                                        idtabla="idcuenta"
                                        :keyIn="index"
                                        :clearable='true'>
                                    </Ajaxselect>
                                </div>
                                <div class="col-md-2 border" >
                                    <input v-model="rowregistro.porcentaje" class="border-0 input-text" type="number" placeholder="%" style="text-align:right" v-on:focus="selectAll">
                                </div>
                                <div v-if="recorrerowregistrodebe-1==index" class="col-md-2 border" style="text-align:center">
                                    <button @click="deleterowregistrodebe(index)" class="btn btn-danger botonpadding" >
                                            Borrar
                                    </button>
                                </div>
                            </div>
                            <div class="row" style="text-align:right;">
                                <div class="bg-info text-white col-md-8" >
                                    <strong><label >Total Porcentaje:</label></strong>
                                </div>
                                <div class="col-md-2 border" style="padding-right: 25px;">
                                    <label for="porcentaje_debe" v-text="porcentajedebe"></label>
                                </div>
                                <div class="col-md-2 border" style="text-align:center">
                                    <button  @click="addrowregistrodebe" class="btn btn-success botonpadding">
                                        Nuevo
                                    </button>
                                </div>
                            </div>
                            <div v-if="!verporcentajedebe || !verrowcuentadebe" style="text-align:right" >
                                <span class="text-error">Las Cuentas No deben estar Vacias y El Porcentaje debe ser 100% </span><br />
                            </div>
                        </div>
                        <hr style="margin-top: 5px;margin-bottom: 5px;">
                    </div>
                    <!-- bloque haber -->
                    
                    <div class="">
                        <h2>Haber</h2>
                        <div class="" v-if="tipoAccion==2">
                            <table class="table table-bordered table-striped table-sm" style="margin-bottom: 0px;">
                            <thead>
                                <tr>
                                    <th style="width:80%">Cuenta</th>
                                    <th>Porcentaje</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="perfilcuentadetalleh in arrayDetalleH" :key="perfilcuentadetalleh.idperfilcuentadetalle">
                                    <td v-text="perfilcuentadetalleh.codcuenta + ' ' +  perfilcuentadetalleh.nomcuenta"></td>
                                    <td v-text="perfilcuentadetalleh.porcentaje"></td>
                                </tr> 
                            </tbody>
                            </table>
                        </div>
                        <div class="" v-if="tipoAccion==1">
                            <div class="row">
                                <div class="bg-info text-white border border-white col-md-8" >
                                    <strong><label>Cuenta</label></strong>
                                </div>
                                <div class="bg-info text-white border border-white col-md-2" >
                                    <strong><label>Porcentaje</label></strong>
                                </div>
                                <div class="bg-info text-white border border-white col-md-2"  style="text-align:center">
                                    <strong><label> + </label></strong>
                                </div>
                            </div>
                            <div v-for="(rowregistro, index) in rowregistroshaber" :key="index" class="row">
                                <div class="border col-md-8" >
                                    <Ajaxselect  v-if="clearSelected" 
                                        ruta="/con_cuentas/selectBuscarcuenta2?buscar=" @found="cuentash" @cleaning="cleanh"
                                        resp_ruta="cuentas"
                                        labels="cuentas"
                                        placeholder="Ingrese texto" 
                                        idtabla="idcuenta"
                                        :keyIn="index"
                                        :clearable='true'>
                                    </Ajaxselect>
                                </div>
                                <div class="col-md-2 border" >
                                    <input v-model="rowregistro.porcentaje" class="border-0 input-text" type="number" placeholder="%" style="text-align:right" v-on:focus="selectAll">
                                </div>
                                <div v-if="recorrerowregistrohaber-1==index" class="col-md-2 border" style="text-align:center">
                                    <button @click="deleterowregistrohaber(index)" class="btn btn-danger botonpadding" >
                                            Borrar
                                    </button>
                                </div>
                            </div>
                            
                            <div class="row" style="text-align:right;">
                                <div class="bg-info text-white col-md-8" >
                                    <strong><label >Total Porcentaje:</label></strong>
                                </div>
                                <div class="col-md-2 border" style="padding-right: 25px;">
                                    <label for="porcentaje_haber" v-text="porcentajehaber"></label>
                                </div>
                                <div class="col-md-2 border" style="text-align:center">
                                    <button  @click="addrowregistrohaber" class="btn btn-success botonpadding">
                                        Nuevo
                                    </button>
                                </div>
                            </div>
                            <div v-if="!verporcentajehaber || !verrowcuentahaber" style="text-align:right" >
                                <span class="text-error">Las Cuentas No deben estar Vacias y El Porcentaje debe ser 100% </span><br />
                            </div>
                        </div> 
                        <hr style="margin-top: 5px;margin-bottom: 5px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <div>
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('siporcentaje')">Cerrar</button>
                        <!-- :disabled = "errors.any()" -->
                        <button :disabled ="!verporcentajehaber || !verrowcuentahaber || !verporcentajedebe || !verrowcuentadebe" v-if="tipoAccion==1" type="button"  class="btn btn-primary" @click="registrarPerfildetalle1('siporcentaje')">Guardar</button>
                        <!-- <button :disabled ="!iscompletelibro" type="button" v-if="tipoAccionlibro==2" class="btn btn-primary" @click="actualizarLibrocompra()">Actualizar</button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <!--Fin del modal-->

    <!--Inicio del modal Perfildecuenta Detalle no porcentaje -->
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" id='noporcentaje' style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModalNoPorcentaje"></h4>
                    <button type="button" class="close" @click="cerrarModal('siporcentaje')" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body1">
                    <!-- bloque debe -->
                    <div class="" >
                        <h2>Debe</h2>
                        <div class="" v-if="tipoAccion==2">
                            <table class="table table-bordered table-striped table-sm" style="margin-bottom: 0px;">
                            <thead>
                                <tr>
                                    <th style="width:80%">Cuenta</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="perfilcuentadetalled in arrayDetalleD" :key="perfilcuentadetalled.idperfilcuentadetalle">
                                    <td v-text="perfilcuentadetalled.codcuenta + perfilcuentadetalled.nomcuenta"></td>
                                </tr> 
                            </tbody>
                            </table>
                            </div>
                        <div class="" v-if="tipoAccion==1">
                            <div class="row">
                                <div class="bg-info text-white border border-white col-md-10" >
                                    <strong><label>Cuenta</label></strong>
                                </div>
                                <div class="bg-info text-white border border-white col-md-2"  style="text-align:center">
                                    <strong><label> + </label></strong>
                                </div>
                            </div>
                            <div v-for="(rowregistro, index) in rowregistrosdebe" :key="index" class="row">
                                <div class="border col-md-10" >
                                    <Ajaxselect   v-if="clearSelected"
                                        ruta="/con_cuentas/selectBuscarcuenta2?buscar=" @found="cuentas" @cleaning="clean"
                                        resp_ruta="cuentas"
                                        labels="cuentas"
                                        placeholder="Ingrese texto" 
                                        idtabla="idcuenta"
                                        :keyIn="index"
                                        :clearable='true'>
                                    </Ajaxselect>
                                </div>
                                <div v-if="recorrerowregistrodebe-1==index" class="col-md-2 border" style="text-align:center">
                                    <button @click="deleterowregistrodebe(index)" class="btn btn-danger botonpadding" >
                                            Borrar
                                    </button>
                                </div>
                            </div>
                            <div class="row" style="text-align:right;">
                                <div class="bg-info text-white col-md-10" >
                                    <strong><label ></label></strong>
                                </div>
                                <div class="col-md-2 border" style="text-align:center">
                                    <button  @click="addrowregistrodebe" class="btn btn-success botonpadding">
                                        Nuevo
                                    </button>
                                </div>
                            </div>
                            <div v-if="!verrowcuentadebe" style="text-align:right" >
                                <span class="text-error">Las Cuentas No deben estar Vacias</span><br />
                            </div>
                        </div>
                        <hr style="margin-top: 5px;margin-bottom: 5px;">
                    </div>
                    <!-- bloque haber -->
                    
                    <div class="">
                        <h2>Haber</h2>
                        <div class="" v-if="tipoAccion==2">
                            <table class="table table-bordered table-striped table-sm" style="margin-bottom: 0px;">
                            <thead>
                                <tr>
                                    <th style="width:80%">Cuenta</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="perfilcuentadetalleh in arrayDetalleH" :key="perfilcuentadetalleh.idperfilcuentadetalle">
                                    <td v-text="perfilcuentadetalleh.codcuenta + perfilcuentadetalleh.nomcuenta"></td>
                                </tr> 
                            </tbody>
                            </table>
                        </div>
                        <div class="" v-if="tipoAccion==1">
                            <div class="row">
                                <div class="bg-info text-white border border-white col-md-10" >
                                    <strong><label>Cuenta</label></strong>
                                </div>
                                <div class="bg-info text-white border border-white col-md-2"  style="text-align:center">
                                    <strong><label> + </label></strong>
                                </div>
                            </div>
                            <div v-for="(rowregistro, index) in rowregistroshaber" :key="index" class="row">
                                <div class="border col-md-10" >
                                    <Ajaxselect  v-if="clearSelected"
                                        ruta="/con_cuentas/selectBuscarcuenta2?buscar=" @found="cuentash" @cleaning="cleanh"
                                        resp_ruta="cuentas"
                                        labels="cuentas"
                                        placeholder="Ingrese texto" 
                                        idtabla="idcuenta"
                                        :keyIn="index"
                                        :clearable='true'>
                                    </Ajaxselect>
                                </div>
                                
                                <div v-if="recorrerowregistrohaber-1==index" class="col-md-2 border" style="text-align:center">
                                    <button @click="deleterowregistrohaber(index)" class="btn btn-danger botonpadding" >
                                            Borrar
                                    </button>
                                </div>
                            </div>
                            
                            <div class="row" style="text-align:right;">
                                <div class="bg-info text-white col-md-10" >
                                    <strong><label ></label></strong>
                                </div>
                                <div class="col-md-2 border" style="text-align:center">
                                    <button  @click="addrowregistrohaber" class="btn btn-success botonpadding">
                                        Nuevo
                                    </button>
                                </div>
                            </div>
                            <div v-if="!verrowcuentahaber" style="text-align:right" >
                                <span class="text-error">Las Cuentas No deben estar Vacias</span><br />
                            </div>
                        </div> 
                        <hr style="margin-top: 5px;margin-bottom: 5px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <div>
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('noporcentaje')">Cerrar</button>
                        <!-- :disabled = "errors.any()" -->
                        <button :disabled ="!verrowcuentahaber || !verrowcuentadebe" v-if="tipoAccion==1" type="button"  class="btn btn-primary" @click="registrarPerfildetalle1('noporcentaje')">Guardar</button>
                        <!-- <button :disabled ="!iscompletelibro" type="button" v-if="tipoAccionlibro==2" class="btn btn-primary" @click="actualizarLibrocompra()">Actualizar</button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" style="z-index: 1600;" aria-hidden="true" id="reporteview">
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content animated fadeIn">
          <div class="modal-header">
            <h4 class="modal-title" id="modalOneLabel">Reporte de la configuración de perfiles de cuentas</h4>
            <button type="button" class="close" aria-hidden="true" aria-label="Close"
              @click="classModal.closeModal('reporteview')">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body-plandepagos">
            <!--  <div style="display:none" v-html="plandepagosSimulacion"></div>-->
            <iframe name="viewreporte" id="viewreporte" style="width:100%;height:100%;"></iframe>
          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" type="button"
              @click="classModal.closeModal('reporteview')">cerrar</button>
            <!--  <button class="btn btn-primary" type="button" @click="print()">Imprimir</button> -->
          </div>
        </div>
      </div>
    </div>

    </main>
</template>

<script>
import VueBarcode from 'vue-barcode';
import Vue from 'vue';
import VeeValidate from 'vee-validate';
import vSelect from 'vue-select';


import * as plugin from '../../functions.js';

//import vSelect from 'vue-select';
//Vue.component( 'v-select', vSelect );

//Vue.component('v-select', VueSelect.VueSelect)


Vue.component('v-select',vSelect)
export default {
    props:['idmodulo','idventanamodulo'],
        data (){
            return {
                rowregistrosdebe: [{idcuenta:'',porcentaje:0}] ,
                //contador:0,
                porcentajedebe:0,
                rowcuentad:false,
                
                rowregistroshaber: [{idcuenta:'',porcentaje:0}] ,
                //contador:0,
                porcentajehaber:0,
                rowcuentah:false,
                


                idcuenta:[],
                pruebad:'',
                pruebah:'',
                arrayCuenta:[],
                arrayCuentas:[],
                perfilcuentamaestro_id: 0,
                perfilcuentadetalle_id:0,
                idmodulo1: '',
                idtipocomprobante:'',
                nomperfilcuentamaestro : '',
                descripcionperfilcuentamaestro:'',
                nommodulo:'',
                arrayPerfilcuentamaestro : [],
                
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
                tituloModalSiPorcentaje:'',
                tituloModalNoPorcentaje:'',
                arrayDetalle:[],
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
                arrayPermisos:{perfil_agregar:0,
                                perfil_editar:0,
                                perfil_eliminar:0
                                },
                
                arrayPermisosIn:[],
                clearSelected:1

            }
        },
        components: {
        'barcode': VueBarcode
    },
    computed:{
        recorrerowregistrodebe:function(){
            let me=this;
            var contador=0;
            me.porcentajedebe=0;
            me.rowcuentad=false
            me.rowregistrosdebe.forEach(element => {
                contador++;
                me.porcentajedebe=parseInt(me.porcentajedebe)+parseInt(element.porcentaje);
                if (element.idcuenta=='')
                    me.rowcuentad=true
            });

            return contador;
        },
        verporcentajedebe:function(){
            let me=this;
                if(me.porcentajedebe==100)
            {
                return true;
            }
            return false;
        },
        verrowcuentadebe:function(){
            let me=this;
            if(me.rowcuentad==true)
                return false;
            
            return true;
        },

        recorrerowregistrohaber:function(){
            let me=this;
            var contador=0;
            me.porcentajehaber=0;
            me.rowcuentah=false
            me.rowregistroshaber.forEach(element => {
                contador++;
                me.porcentajehaber=parseInt(me.porcentajehaber)+parseInt(element.porcentaje);
                if (element.idcuenta=='')
                    me.rowcuentah=true
            });

            return contador;
        },
        verporcentajehaber:function(){
            let me=this;
                if(me.porcentajehaber==100)
            {
                return true;
            }
            return false;
        },
        verrowcuentahaber:function(){
            let me=this;
            if(me.rowcuentah==true)
                return false;
            
            return true;
        },
        iscomplete:function(){
            return this.nomperfilcuentamaestro && this.descripcionperfilcuentamaestro && this.idmodulo1 && this.idtipocomprobante;
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
        chancename(ev,id){ 
            let me=this;
             axios.put('/con_perfilcuentamaestro/cambionombre',{
                    'idmaestro': id,
                    'nom': ev.target.value
                }).then(function (response) {
                    me.listarPerfilcuentamaestro(1,'','nomperfilcuentamaestro');
                    swal(
                    'Actualizado!',
                    'El registro ha sido actualizado con éxito.',
                    'success'
                    )
                }).catch(function (error) {
                    console.log(error);
                });
        },
        tiempo(){
            this.clearSelected=1;
            },
        selectAll: function (event) {
    
            setTimeout(function () {
                event.target.select()
            }, 0)
        },
        
        addrowregistrodebe() {
            this.rowregistrosdebe.push({ idcuenta: '',porcentaje:0});
        },
        deleterowregistrodebe:function(index) {
            this.rowregistrosdebe.splice(index, 1);
            if(index===0)
                this.addrowregistrodebe();
        },
        cuentas(cuentas,index){ 
            this.idcuenta=[];
            this.rowregistrosdebe[index].idcuenta=cuentas.idcuenta;  
            cuentas='';       
        },
        clean(a){
        //console.log(a)
        this.rowregistrosdebe[a].idcuenta='';  
        
        },
        addrowregistrohaber() {
            this.rowregistroshaber.push({ idcuenta: '',porcentaje:0});
        },
        deleterowregistrohaber:function(index) {
            this.rowregistroshaber.splice(index, 1);
            if(index===0)
                this.addrowregistrohaber();
        },
        cuentash(cuentash,index){ 
            this.rowregistroshaber[index].idcuenta=cuentash.idcuenta;  
            cuentash='';       
        },
        cleanh(a){

            this.rowregistroshaber[a].idcuenta='';  
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
        selectPerfilcuentadetalle(idmaestro){
            let me=this;
            
            var url= '/con_perfilcuentadetalle/selectPerfilcuentadetalle?idmaestro='+idmaestro;
            axios.get(url).then(function (response) {
                //console.log(response);
                var respuesta= response.data;
                me.arrayDetalle=respuesta.perfilcuentadetalles;
                me.arrayDetalleD=me.arrayDetalle.filter(elem=>elem.tipocargo=='d')
                me.arrayDetalleH=me.arrayDetalle.filter(elem=>elem.tipocargo=='h')
                })
            .catch(function (error) {
                console.log(error);
            });
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
            console.log(me.nomperfilcuentamaestro);
            
            axios.post('/con_perfilcuentamaestro/registrar',{
                'nomperfilcuentamaestro':me.nomperfilcuentamaestro,
                'descripcion':me.descripcionperfilcuentamaestro,
                'idmodulo': me.idmodulo1,
                'idtipocomprobante':me.idtipocomprobante,
                'siporcentaje':me.siporcentaje
            }).then(function (response) {
                if(response.data.length){
                swal(
                        'El Valor ya Existe',
                        'Ingresa un dato Diferente',
                        'error'
                    )                    }
                else{
                    me.cerrarModal('perfilcuentamaestro');
                    me.listarPerfilcuentamaestro(1,'','nomperfilcuentamaestro');
                }
            }).catch(function (error) {
                console.log(error);
            });
        },
        registrarPerfildetalle1(porcentaje){
            let me=this;
            axios.post('/con_perfilcuentadetalle/registrar',{
                    'idmaestro':me.perfilcuentamaestro_id,
                    'arraydebe':me.rowregistrosdebe,
                    'arrayhaber': me.rowregistroshaber,
                }).then(function (response) {
                    swal(
                            'Registrado correctamente'
                    )                    
                        
                    
                    me.listarPerfilcuentamaestro(1,'','nomperfilcuentamaestro');
                    me.cerrarModal(porcentaje);
                        

                }).catch(function (error) {
                    console.log(error);
                });
        },getReporte(){
            let me=this;
            axios.get('/con_perfilcuentamaestro/reporte').then((value)=>{ 
                var outdata = new Map();
                for (let datain of value.data.reporte){  
                      if(outdata.has(datain.idperfilcuentamaestro)){
                          var arr=outdata.get(datain.idperfilcuentamaestro);
                          var valuein=arr.data;
                          valuein.push(datain);
                          arr.data=valuein;
                          outdata.set(datain.idperfilcuentamaestro,arr);
                      }else{
                          var vaa = new Array();
                          vaa.push(datain);
                          outdata.set(datain.idperfilcuentamaestro,{nombre:datain.nomperfil,tipo:datain.nomtipocomprobante,modulo:datain.nommodulo,data:vaa});
                      } 
                } 
                //  for (var valor of outdata.values()) {
                //    console.log(valor)
                //  }

               _pl._vvp2521_00004(outdata,value.data.user,value.data.date,'viewreporte');
 
                 me.classModal.openModal('reporteview');
            }).catch((e)=>{
                console.log(e)
            })
        },
        /* actualizarPerfilcuentamaestro(){
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
                    me.listarPerfilcuentamaestro(1,'','nomperfilcuentamaestro');
                    me.cerrarModal();
                    
                }
            }).catch(function (error) {
                console.log(error);
            }); 
        }, */
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
        cerrarModal(valor){
            let me= this;
            switch (valor) {
                case 'perfilcuentamaestro':
                    me.nomperfilcuentamaestro = '';
                    me.descripcionperfilcuentamaestro='';
                    me.idmodulo1= '';
                    me.idtipocomprobante='';
                    me.siporcentaje=true;
                    me.listarPerfilcuentamaestro(1,'','nomperfilcuentamaestro');
                    me.classModal.closeModal('perfilcuentamaestro');
                    break;
                case 'siporcentaje':
                    me.classModal.closeModal('siporcentaje');
                    me.rowregistrosdebe= [{idcuenta:'',porcentaje:0}] ,
                    me.porcentajedebe=0;
                    me.rowcuentad=false;
                    me.rowregistroshaber= [{idcuenta:'',porcentaje:0}];
                    me.porcentajehaber=0;
                    me.rowcuentah=false;
                    me.clearSelected=0;
                    setTimeout(me.tiempo, 200); 
                    break;
                case 'noporcentaje':
                    me.classModal.closeModal('noporcentaje');
                    me.rowregistrosdebe= [{idcuenta:'',porcentaje:0}] ,
                    me.rowcuentad=false;
                    me.rowregistroshaber= [{idcuenta:'',porcentaje:0}];
                    me.rowcuentah=false;
                    me.clearSelected=0;
                    setTimeout(me.tiempo, 200); 
                    break;
                
            }

            /*   
            
            me.modaldetalle=0;
            me.tituloModal='';
            
            me.nommodulo = '';
            me.modaldetallenoporcentaje=0;
            
            
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
                */
        },
        abrirModal(modelo, accion, data = []){
            let me=this;
            switch(modelo){
                case "perfilcuentamaestro":
                {
                    switch(accion){
                        case 'registrar':
                        {
                            //console.log('intra registrar');
                            me.clearSelected=0;
                            setTimeout(me.tiempo, 200); 
                            me.classModal.openModal('perfilcuentamaestro');
                            me.tituloModal = 'Registrar Perfil de Cuenta Maestro';
                            me.idmodulo1='';
                            me.nommodulo='';
                            me.nomperfilcuentamaestro= '';
                            me.idtipocomprobante='';
                            me.descripcionperfilcuentamaestro='';
                            
                            
                            break;
                        }
                        case 'actualizar':
                        {
                            //console.log(data);
                            me.modal=1;
                            me.tituloModal='Actualizar Perfil de Cuenta Maestro';
                            
                            me.perfilcuentamaestro_id=data['idperfilcuentamaestro'];
                            me.idmodulo1=data['idmodulo'];
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
                    me.clearSelected=0;
                    setTimeout(me.tiempo, 200); 
                    me.perfilcuentamaestro_id=data['idperfilcuentamaestro'];
                    
                    me.tipoAccion = 1; //
                    if (data['siporcentaje']==1)
                    {   
                        me.classModal.openModal('siporcentaje');
                        me.tituloModalSiPorcentaje = 'Registrar Perfil Cuenta Detalle - ' + data['nomperfil'];
                    }
                    else
                    {
                        me.classModal.openModal('noporcentaje')
                        me.tituloModalNoPorcentaje = 'Registrar Perfil Cuenta Detalle Sin Porcentaje - '  + data['nomperfil'];
                    }
                    break;
                }
                case 'ver':
                {
                    me.clearSelected=0;
                    setTimeout(me.tiempo, 200); 
                    me.perfilcuentamaestro_id=data['idperfilcuentamaestro'];
                    
                    me.tipoAccion = 2;
                    if (data['siporcentaje']==1)
                    {
                        me.classModal.openModal('siporcentaje');
                        me.tituloModalSiPorcentaje = 'Ver Perfil Cuenta Detalle - '  + data['nomperfil'];
                    }
                    else
                    {
                        me.classModal.openModal('noporcentaje');
                        me.tituloModalNoPorcentaje = 'Ver Perfil Cuenta Detalle Sin Porcentaje - '  + data['nomperfil'];
                    }
                    me.selectPerfilcuentadetalle(me.perfilcuentamaestro_id);
                    break;
                }
                case 'actualizar':
                {
                    //console.log(data);
                    me.clearSelected=0;
                    setTimeout(me.tiempo, 200);
                    me.perfilcuentamaestro_id=data['idperfilcuentamaestro'];
                    // me.idmodulo1=data['idmodulo'];
                      me.tipoAccion = 2; 
                    if (data['siporcentaje']==1)
                    {   
                        me.classModal.openModal('siporcentaje');
                        me.tituloModalSiPorcentaje = 'Registrar Perfil Cuenta Detalle - ' + data['nomperfil'];
                    }
                    else
                    {
                        me.classModal.openModal('noporcentaje')
                        me.tituloModalNoPorcentaje = 'Registrar Perfil Cuenta Detalle Sin Porcentaje - '  + data['nomperfil'];
                    }
                     me.selectPerfilcuentadetalle(me.perfilcuentamaestro_id);
                    break;
                }
            }
        },
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
    },
    mounted() {
        this.listarPerfilcuentamaestro(1,this.buscar,this.criterio);
        this.selectModulo();
        this.selectTipocomprobante();
        //this.selectCuenta();
        this.getPermisos();
        this.classModal=new _pl.Modals();
        this.classModal.addModal('perfilcuentamaestro');
        this.classModal.addModal('siporcentaje');
        this.classModal.addModal('noporcentaje');
        this.classModal.addModal('reporteview');
    }
}
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
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
