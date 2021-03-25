<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card animated fadeIn">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Registro de Productos
                        <button v-if="check('Nuevo')" type="button" @click="abrirModal('modalproducto','producto','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nomproducto">Nombre</option>
                                      
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarProducto(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarProducto(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <!-- <th>Perfiles</th> --> 
                                    <th>Estado</th>
                                    <th>Nombre</th>
                                    <th>Codigo</th>
                                    <th>Moneda</th>
                                    <th>Tasa Anual</th>
                                    <!-- <th>Monto Minimo</th> 
                                    <th>Monto Maximo</th>-->
                                   
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="producto in arrayproducto" :key="producto.idproducto">
                                    <td>
                                            <button v-if="producto.activo==0&&check('Habilitar_Deshabilitar_Producto')" data-toggle="tooltip" data-placement="top" title="Activar" type="button" class="btn btn-success btn-sm" @click="activarproducto(producto.idproducto)"><i class="icon-check"></i></button> 
                                            <button v-if="producto.activo==1&&check('Habilitar_Deshabilitar_Producto')" data-toggle="tooltip" data-placement="top" title="Desactivar" type="button" class="btn btn-danger btn-sm" @click="desactivarproducto(producto.idproducto)"><i class="icon-trash"></i></button> 
                                            <button v-if="producto.activo==2&&check('Edición')" data-toggle="tooltip" data-placement="top" title="Editar" type="button" @click="abrirModal('modalproducto','producto','actualizar',producto)" class="btn btn-warning btn-sm"><i class="icon-pencil"></i> </button>
                                            <button v-if="producto.activo==2&&visormaps(producto)&&check('Consolidar_perfil')" data-toggle="tooltip" data-placement="top" title="Consolidar" type="button" class="btn btn-primary btn-sm" @click="consolidar(producto)"> <i class="icon-login"></i>&nbsp;Consolidar</button>    
                                            <button v-if="producto.activo==2&&!visormaps(producto)&&check('Edición_Perfiles')" data-toggle="tooltip" data-placement="top" title="Perfiles" type="button" style="min-width: 115px;" class="btn btn-success btn-sm" @click="perfilesproducto(producto)"> <i class="icon-list"></i> &nbsp;Perfiles</button>
                                    </td>
                                    <td>
                                        <div v-if="producto.activo==1">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else-if="producto.activo==0">
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                        <div v-else-if="producto.activo==2">
                                            <span class="badge badge-danger">No consolidado</span>
                                        </div>
                                        <div v-else-if="producto.activo==3">
                                            <span class="badge badge-danger">Inhabilitado por Contabilidad</span>
                                        </div>
                                        
                                    </td>
                                    <!-- <td>    
                                    
                                     <button v-if="producto.activo==2" type="button" style="min-width: 115px;" class="btn btn-success btn-sm" @click="perfilcobranzaVista(producto)"> <i class="icon-list"></i> &nbsp;Cobranza</button>
                                    </td> -->
                                    <td v-text="producto.nomproducto"></td>
                                    <td v-text="producto.codproducto"></td>
                                    <td v-text="producto.codmoneda"></td>
                                    <td v-text="producto.tasa+' %'"></td> 
                                    
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
            <!--Inicio del modal agregar/actualizar-->
             
            <div class="modal fade" tabindex="-1"  role="dialog"  style="z-index: 1600;" aria-hidden="true" id="modalproducto" >    
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal('modalproducto')"  aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body" style="overflow-y: scroll;max-height: 600px;">
                            
                                <div class="form-group row">
                                    <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label" for="text-input">Nombre del Producto : </label>
                                    <div class="col-md-9">
                                        <input  v-validate.initial= "'required|alpha_spaces'"   
                                                type="text" 
                                                v-model="nomproducto"  @keyup.esc="cerrarModal('modalproducto')"
                                                class="form-control" 
                                                placeholder="Nombre del Producto"
                                                name='Nombre producto'
                                                autofocus
                                                >  
                                        <span class="text-error">{{ errors.first('Nombre producto')}}</span>  
                                    </div>
                                </div>   
                                 

                                <div class="form-group row">
                                   <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label" for="text-input">Codigo Producto : </label>
                                    <div class="col-md-3">
                                        <input  v-validate.initial= "'required'"   
                                                type="text" 
                                                v-model="codproducto"  
                                                class="form-control" 
                                                placeholder="Codigo de producto"
                                                name='codigo'
                                                autofocus
                                                >   
                                        <span class="text-error">{{ errors.first('codigo')}}</span>   
                                    </div>
                                    <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label" for="text-input">Moneda : </label>
                                    <div class="col-md-3">
                                             
                                        <div style="display:flex"> 
                                            <Combo  v-if="modal==1" 
                                                style="width: 100%" 
                                                :class="{'error': errors.has('Moneda')}"    
                                                v-validate.initial="'required'"
                                                name="Moneda" 
                                                label="nommoneda" 
                                                :options="arrayMoneda"
                                                v-model="moneda"  
                                                placeholder="Seleccione moneda" 
                                                :reduce="json => json.idmoneda"  
                                                :searchable="false"
                                                :clearable="false"   
                                                ><span slot="no-options">No existen Datos</span>
                                                <template slot="option" slot-scope="option">
                                                    {{ option.nommoneda }}  
                                                </template>
                                              </Combo> 
                                        </div> 
                                        <span class="text-error">{{ errors.first('Moneda')}}</span> 
                                    </div>
                                       

                                </div>
                                
                                <div class="form-group row">
                                  <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label" for="text-input">Tasa de Interes Anual : </label>
                                    <div class="col-md-3">
                                         <div class="input-group">
                                        <input  v-validate.initial= "'required|max_value:100'" 
                                                data-vv-as="tasa anual"  
                                                type="number" 
                                                v-model.number="tasa"  
                                                class="form-control"  
                                                placeholder="Tasa de interes anual"
                                                name='tasa'
                                                autofocus
                                                step="any">   
                                                <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fa fa-percent"></i>
                                                </span>
                                                </div>
                                         </div>
                                          <span class="text-error">{{ errors.first('tasa')}}</span> 
                                    </div> 
                                     <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label" for="text-input">Numero de Garantes : </label>
                                    <div class="col-md-3">
                                         <div class="input-group">
                                        <input  id="idgarante" v-validate.initial= "'required'"   
                                                type="number" 
                                                v-model.number="garantes"  
                                                class="form-control"  
                                                placeholder="Numero de garantes"
                                                name='numero de garantes'
                                                autofocus
                                                step="any">   
                                                <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fa fa-users"></i>
                                                </span>
                                                </div>
                                         </div>
                                          <span class="text-error">{{ errors.first('numero de garantes')}}</span> 
                                    </div>
                                     
                                </div>

                                
                                <div class="form-group row">    
                                    <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label" for="text-input">Plazo Minimo (meses) : </label>
                                    <div class="col-md-3">
                                        <input  v-validate.initial= "'required|integer'"   
                                                type="number" 
                                                v-model.number="plazominimo" 
                                                class="form-control" 
                                                placeholder="ej.: 1"
                                                name='plazo minimo'
                                                autofocus
                                                >   
                                        <span class="text-error">{{ errors.first('plazo minimo')}}</span> 
                                    </div>
                                    
                                    <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label" for="text-input">Plazo Maximo (meses) : </label>
                                    <div class="col-md-3">
                                        <input  v-validate.initial= "'required|integer'"   
                                                type="number" 
                                                v-model.number="plazomaximo"  
                                                class="form-control" 
                                                placeholder="ej.: 9999"
                                                name='plazo maximo'
                                                autofocus
                                                >  <!-- @keyup.enter="registrarproducto()"  para habilitar enter -->
                                        <span class="text-error">{{ errors.first('plazo maximo')}}</span>   <!--Lineas Agregadas<-->
                                    </div>
                                </div>

 <div class="form-group row">
                                   
                                    <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label" for="text-input">Factor determinante : </label>
                                    <div class="col-md-9">
                                      
                                           <div style="display:flex"> 
                                            <Combo  v-if="modal==1" 
                                                style="width: 100%" 
                                                :class="{'error': errors.has('factor')}"    
                                                v-validate.initial="'required'"
                                                name="factor" 
                                                label="nombrefactor" 
                                                :options="arrayfactores"
                                                v-model="id_factor"  
                                                placeholder="Seleccione factor" 
                                                :reduce="json => json.idfactor" 
                                                :getOptionLabel="json => json.nombrefactor+' - '+json.descripcion" 
                                                :searchable="false"
                                                :clearable="false"   
                                                ><span slot="no-options">No existen Datos</span>
                                                <template slot="option" slot-scope="option">
                                                    {{ option.nombrefactor }} - {{ option.descripcion }}
                                                </template>
                                              </Combo> 
                                        </div>                                    
                                        <span class="text-error">{{ errors.first('factor')}}</span>   
                                    </div>
                                     

                                </div> 

                                <div class="form-group row">
                                    <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label" for="text-input">Escala de Montos : </label>
                                    <div class="col-md-9">
                                              
                                         <div style="display:flex"> 
                                            <Combo  v-if="modal==1" 
                                                style="width: 100%" 
                                                :class="{'error': errors.has('escala de montos')}"    
                                                v-validate.initial="'required'"
                                                name="escala de montos" 
                                                label="nomescala" 
                                                :options="arrayescalas"
                                                v-model="id_escala"  
                                                placeholder="Seleccione escala" 
                                                :reduce="json => json.idescala"  
                                                :searchable="false"
                                                :clearable="false"   
                                                ><span slot="no-options">No existen Datos</span>
                                                <template slot="option" slot-scope="option">
                                                    {{ option.nomescala }}  
                                                </template>
                                              </Combo> 
                                        </div> 

                                        <span class="text-error">{{ errors.first('escala de montos')}}</span>    
                                    </div>
                                </div>
    


                            <div v-if="modal==1&&!cancelarprestamos&&!activacion_a_garante" class="form-group row">
                                    <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label" for="text-input">Perfil de Desembolso : </label>
                                    <div class="col-md-9">   
                                        <div style="display:flex"> 
                                            <Combo  v-if="modal==1&&!cancelarprestamos&&!activacion_a_garante"
                                                ref="perfilldesembolso"
                                                style="width: 100%" 
                                                :class="{'error': errors.has('Perfil Desembolso')}"    
                                                v-validate.initial="'required'"
                                                name="Perfil Desembolso" 
                                                label="nomperfil" 
                                                :options="arrayPerfiles"
                                                v-model="idPerfilDesembolso"  
                                                placeholder="Seleccione perfil" 
                                                :reduce="json => json.idperfilcuentamaestro" 
                                                :searchable="false"
                                                :clearable="false"   
                                                ><span slot="no-options">No existen Datos</span>
                                                <template slot="option" slot-scope="option">
                                                    {{ option.nomperfil }}
                                                </template>
                                              </Combo> 
                                        </div>
                                        <span class="text-error">{{ errors.first('Perfil Desembolso') }}</span> 
                                    </div>
                                    </div>
                           

<!-- ////////// cobranzas //////////// -->
                             <div class="form-group row">
                                    <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label" 
                                    for="text-input">Perfil de Cobranza <br><b>Manual</b> :</label>
                                    <div class="col-md-9">   
                                        <div style="display:flex"> 
                                            <Combo v-if="modal==1" 
                                                style="width: 100%" 
                                                :class="{'error': errors.has('Perfil Cobranza Manual')}"    
                                                v-validate.initial="'required'"
                                                name="Perfil Cobranza Manual" 
                                                label="nomperfil" 
                                                :options="arrayPerfiles"
                                                v-model="idPerfilCobranza_manual"  
                                                placeholder="Seleccione perfil" 
                                                :reduce="json => json.idperfilcuentamaestro" 
                                                :searchable="false"
                                                :clearable="false"   
                                                ><span slot="no-options">No existen Datos</span>
                                                <template slot="option" slot-scope="option">
                                                    {{ option.nomperfil }}
                                                </template>
                                                </Combo> 
                                        </div>
                                        <span class="text-error">{{ errors.first('Perfil Cobranza Manual') }}</span> 
                                    </div>
                            </div> 
                        
                        
                         <div class="form-group row">
                                    <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label" 
                                    for="text-input">Perfil de Cobranza <br><b>Ascii</b> :</label>
                                    <div class="col-md-9">   
                                        <div style="display:flex"> 
                                            <Combo v-if="modal==1" 
                                                style="width: 100%" 
                                                :class="{'error': errors.has('Perfil Cobranza Ascii')}"    
                                                v-validate.initial="'required'"
                                                name="Perfil Cobranza Ascii" 
                                                label="nomperfil" 
                                                :options="arrayPerfiles"
                                                v-model="idPerfilCobranza_ascii"  
                                                placeholder="Seleccione perfil" 
                                                :reduce="json => json.idperfilcuentamaestro" 
                                                :searchable="false"
                                                :clearable="false"   
                                                ><span slot="no-options">No existen Datos</span>
                                                <template slot="option" slot-scope="option">
                                                    {{ option.nomperfil }}
                                                </template>
                                                </Combo> 
                                        </div>
                                        <span class="text-error">{{ errors.first('Perfil Cobranza Ascii') }}</span> 
                                    </div>
                            </div> 
                            <div class="form-group row">
                                    <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label" 
                                    for="text-input">Perfil de Cobranza <br><b>Acreedor</b> :</label>
                                    <div class="col-md-9">   
                                        <div style="display:flex"> 
                                            <Combo v-if="modal==1" 
                                                style="width: 100%" 
                                                :class="{'error': errors.has('Perfil Cobranza Acreedor')}"    
                                                v-validate.initial="'required'"
                                                name="Perfil Cobranza Acreedor" 
                                                label="nomperfil" 
                                                :options="arrayPerfiles"
                                                v-model="idPerfilCobranza_acreedor"  
                                                placeholder="Seleccione perfil" 
                                                :reduce="json => json.idperfilcuentamaestro" 
                                                :searchable="false"
                                                :clearable="false"   
                                                ><span slot="no-options">No existen Datos</span>
                                                <template slot="option" slot-scope="option">
                                                    {{ option.nomperfil }}
                                                </template>
                                                </Combo> 
                                        </div>
                                        <span class="text-error">{{ errors.first('Perfil Cobranza Acreedor') }}</span> 
                                    </div>
                            </div> 

                            <div class="form-group row">
                                    <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label" 
                                    for="text-input">Perfil de Cobranza <br><b>Daaro</b> :</label>
                                    <div class="col-md-9">   
                                        <div style="display:flex"> 
                                            <Combo v-if="modal==1" 
                                                style="width: 100%" 
                                                :class="{'error': errors.has('Perfil Cobranza Daaro')}"    
                                                v-validate.initial="'required'"
                                                name="Perfil Cobranza Daaro" 
                                                label="nomperfil" 
                                                :options="arrayPerfiles"
                                                v-model="idPerfilCobranza_daro"  
                                                placeholder="Seleccione perfil" 
                                                :reduce="json => json.idperfilcuentamaestro" 
                                                :searchable="false"
                                                :clearable="false"   
                                                ><span slot="no-options">No existen Datos</span>
                                                <template slot="option" slot-scope="option">
                                                    {{ option.nomperfil }}
                                                </template>
                                                </Combo> 
                                        </div>
                                        <span class="text-error">{{ errors.first('Perfil Cobranza Daaro') }}</span> 
                                    </div>
                            </div> 

                             <div class="form-group row">
                                    <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label" 
                                    for="text-input">Perfil Cambio de Estado<br><b>De Vigente a Vencido</b> :</label>
                                    <div class="col-md-9">   
                                        <div style="display:flex"> 
                                            <Combo v-if="modal==1" 
                                                style="width: 100%" 
                                                :class="{'error': errors.has('Perfil Cambio Estado')}"    
                                                v-validate.initial="'required'"
                                                name="Perfil Cambio Estado" 
                                                label="nomperfil" 
                                                :options="arrayPerfiles"
                                                v-model="idPerfilCambio_estado"  
                                                placeholder="Seleccione perfil" 
                                                :reduce="json => json.idperfilcuentamaestro" 
                                                :searchable="false"
                                                :clearable="false"   
                                                ><span slot="no-options">No existen Datos</span>
                                                <template slot="option" slot-scope="option">
                                                    {{ option.nomperfil }}
                                                </template>
                                                </Combo> 
                                        </div>
                                        <span class="text-error">{{ errors.first('Perfil Cambio Estado') }}</span> 
                                    </div>
                            </div>
                            <div class="form-group row">
                                    <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label" 
                                    for="text-input">Perfil Cambio de Estado<br><b>De Vencido a Vigente</b> :</label>
                                    <div class="col-md-9">   
                                        <div style="display:flex"> 
                                            <Combo v-if="modal==1" 
                                                style="width: 100%" 
                                                :class="{'error': errors.has('Perfil Cambio Estado')}"    
                                                v-validate.initial="'required'"
                                                name="Perfil Cambio Estado" 
                                                label="nomperfil" 
                                                :options="arrayPerfiles"
                                                v-model="idPerfilCambio_estado_mora"  
                                                placeholder="Seleccione perfil" 
                                                :reduce="json => json.idperfilcuentamaestro" 
                                                :searchable="false"
                                                :clearable="false"   
                                                ><span slot="no-options">No existen Datos</span>
                                                <template slot="option" slot-scope="option">
                                                    {{ option.nomperfil }}
                                                </template>
                                                </Combo> 
                                        </div>
                                        <span class="text-error">{{ errors.first('Perfil Cambio Estado') }}</span> 
                                    </div>
                            </div>

<!-- ////////////////////// -->
<div class="form-group row" style="font-weight: bold;background-color: #c2cfd6;padding: 7px; margin-left: 3px;margin-right: 3px; margin-bottom: 0px; margin-top: 12px;">    
                                    <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label" for="text-input">Agrupar por Lote : </label>
                                    <div class="col-md-3 my-auto"> 
                                         <label class="switch switch-label switch-pill switch-primary" style="margin: 0 !important;display: table-cell;">
                                        <input class="switch-input" type="checkbox" checked="" v-model="lote">
                                        <span class="switch-slider" data-checked="Si" data-unchecked="No"></span>
                                        </label>   
                                    </div>

                                    <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label" for="text-input">Prestamo Unico : </label>
                                    <div class="col-md-3 my-auto"> 
                                         <label class="switch switch-label switch-pill switch-primary" style="margin: 0 !important;display: table-cell;">
                                        <input class="switch-input" type="checkbox" checked="" v-model="max_prestamos">
                                        <span class="switch-slider" data-checked="Si" data-unchecked="No"></span>
                                        </label>   
                                    </div>
                            </div>

 
                                <div class="form-group row" style=" margin-left: 3px;
                                        border: 1px solid rgb(194, 207, 214);
                                        margin-right: 3px;    margin-top: 0px;"> 
                                        <!-- ///////////////////////////////////  es refinanciable  ////////////////////////////////////// -->
<div class="col-md-12 h-100 " style="padding: 9px;min-height: 60px; text-align: center;border: 1px solid rgb(194, 207, 214);">
                                    <div class="row "> 
                                     <label style="text-align: right; align-items: center;"  class="col-md-6 my-auto"
                                      for="text-input">Es refinanciable :</label>
                                    <div class="col-md-6 my-auto"> 
                                         <label class="switch switch-label switch-pill switch-primary" style="margin: 0 !important;display: table-cell;">
                                        <input class="switch-input" type="checkbox" checked="" v-model="isrefinanciable">
                                        <span class="switch-slider" data-checked="Si" data-unchecked="No"></span>
                                        </label>   
                                    </div>
                                    </div>
                                    <div v-if="isrefinanciable" class="row" style="margin-top: 15px;"> 
                                     <label v-if="isrefinanciable" style="text-align: right; align-items: center;" class="col-md-3" for="text-input">Cobranza por<br><b>Refinanciamiento</b>:</label>
                                     <div class="col-md-9" v-if="isrefinanciable" >   
                                        <div style="display:flex"> 
                                            <Combo  v-if="isrefinanciable&&modal==1" 
                                                style="width: 100%" 
                                                :class="{'error': errors.has('Perfil Cobranza Refinanciamiento')}"    
                                                v-validate.initial="'required'"
                                                name="Perfil Cobranza Refinanciamiento" 
                                                label="nomperfil" 
                                                :options="arrayPerfiles"
                                                v-model="idPerfilRefinanciamientoCobranza"  
                                                placeholder="Seleccione perfil" 
                                                :reduce="json => json.idperfilcuentamaestro" 
                                                :searchable="false"
                                                :clearable="false"   
                                                ><span slot="no-options">No existen Datos</span>
                                                <template slot="option" slot-scope="option"> 
                                                    {{ option.nomperfil }}
                                                </template>
                                                <template slot="selected-option" slot-scope="optionselected">
                                                    <div class="selected d-center">  {{ optionselected.nomperfil }} </div>
                                                 </template>
                                              </Combo> 
                                        </div>
                                        <span class="text-error">{{ errors.first('Perfil Cobranza Refinanciamiento') }}</span> 
                                    </div>

                                    </div>
                               </div> 

<!-- //////////////////////////////  refinanciar  ///////////////////////////// -->
                              <div class="col-md-12 h-100 " style="padding: 9px;min-height: 60px; text-align: center;border: 1px solid rgb(194, 207, 214);">
                                    <div class="row "> 
                                     <label style="text-align: right; align-items: center;" :class="cancelarprestamos?'col-md-6 my-auto':'col-md-6 my-auto'"
                                      for="text-input">Refinanciar prestamos vigentes :</label>
                                    <div class="col-md-6 my-auto"> 
                                         <label class="switch switch-label switch-pill switch-primary" style="margin: 0 !important;display: table-cell;">
                                        <input class="switch-input" type="checkbox" checked="" v-model="cancelarprestamos">
                                        <span class="switch-slider" data-checked="Si" data-unchecked="No"></span>
                                        </label>   
                                    </div>
                                    </div>
                                    <div v-if="cancelarprestamos" class="row" style="margin-top: 15px;"> 
                                    <label v-if="cancelarprestamos" style="text-align: right; align-items: center;" class="col-md-3" for="text-input">Desembolso por<br><b>Refinanciamiento</b>:</label>
                                     <div class="col-md-9" v-if="cancelarprestamos" >   
                                        <div style="display:flex"> 
                                            <Combo  v-if="cancelarprestamos&&modal==1" 
                                                style="width: 100%" 
                                                :class="{'error': errors.has('Perfil Desembolso Refinanciamiento')}"    
                                                v-validate.initial="'required'"
                                                name="Perfil Desembolso Refinanciamiento" 
                                                label="nomperfil" 
                                                :options="arrayPerfiles"
                                                v-model="idPerfilRefinanciamientoDesembolso"  
                                                placeholder="Seleccione perfil" 
                                                :reduce="json => json.idperfilcuentamaestro" 
                                                :searchable="false"
                                                :clearable="false"   
                                                ><span slot="no-options">No existen Datos</span>
                                                 <template slot="option" slot-scope="option"> 
                                                    {{ option.nomperfil }}
                                                </template>
                                                <template slot="selected-option" slot-scope="optionselected">
                                                    <div class="selected d-center" >  {{ optionselected.nomperfil }} </div>
                                                 </template>
                                              </Combo> 
                                        </div>
                                        <span class="text-error">{{ errors.first('Perfil Desembolso Refinanciamiento') }}</span> 
                                    </div>
                                    
                                      

                                    </div>
                               </div> 
<!-- ////////////   activar garantes    /////////// -->
                                <div class="col-md-12 h-100 " style="padding: 9px;min-height: 60px; text-align: center;border: 1px solid rgb(194, 207, 214);">
                                    <div class="row "> 
                                     <label style="text-align: right; align-items: center;" :class="activacion_a_garante?'col-md-6 my-auto':'col-md-6 my-auto'"
                                      for="text-input">Activar a garantes/titular :</label>
                                    <div class="col-md-6 my-auto"> 
                                         <label class="switch switch-label switch-pill switch-primary" style="margin: 0 !important;display: table-cell;">
                                        <input class="switch-input" type="checkbox" checked="" v-model="activacion_a_garante">
                                        <span class="switch-slider" data-checked="Si" data-unchecked="No"></span>
                                        </label>   
                                    </div>
                                    </div>
                                    <div v-if="activacion_a_garante" class="row" style="margin-top: 15px;"> 
                                    <label v-if="activacion_a_garante" style="text-align: right; align-items: center;" class="col-md-2" for="text-input">Desembolso :</label>
                                     <div class="col-md-4" v-if="activacion_a_garante" >   
                                        <div style="display:flex"> 
                                            <Combo  v-if="activacion_a_garante&&modal==1" 
                                                style="width: 100%" 
                                                :class="{'error': errors.has('Perfil Desembolso Activación')}"    
                                                v-validate.initial="'required'"
                                                name="Perfil Desembolso Activación" 
                                                label="nomperfil" 
                                                :options="arrayPerfiles"
                                                v-model="idPerfil_ActivacionGarante_Desembolso"  
                                                placeholder="Seleccione perfil" 
                                                :reduce="json => json.idperfilcuentamaestro" 
                                                :searchable="false"
                                                :clearable="false"   
                                                ><span slot="no-options">No existen Datos</span>
                                                 <template slot="option" slot-scope="option"> 
                                                    {{ option.nomperfil }}
                                                </template>
                                                <template slot="selected-option" slot-scope="optionselected">
                                                    <div class="selected d-center" style="font-size: 11px;">  {{ optionselected.nomperfil }} </div>
                                                 </template>
                                              </Combo> 
                                        </div>
                                        <span class="text-error">{{ errors.first('Perfil Desembolso Activación') }}</span> 
                                    </div>
                                    
                                     <label v-if="activacion_a_garante" style="text-align: right; align-items: center;" class="col-md-2" for="text-input">Cobranza :</label>
                                     <div class="col-md-4" v-if="activacion_a_garante" >   
                                        <div style="display:flex"> 
                                            <Combo  v-if="activacion_a_garante&&modal==1" 
                                                style="width: 100%" 
                                                :class="{'error': errors.has('Perfil Cobranza Activación')}"    
                                                v-validate.initial="'required'"
                                                name="Perfil Cobranza Activación" 
                                                label="nomperfil" 
                                                :options="arrayPerfiles"
                                                v-model="idPerfil_ActivacionGarante_Cobranza"  
                                                placeholder="Seleccione perfil" 
                                                :reduce="json => json.idperfilcuentamaestro" 
                                                :searchable="false"
                                                :clearable="false"   
                                                ><span slot="no-options">No existen Datos</span>
                                                <template slot="option" slot-scope="option"> 
                                                    {{ option.nomperfil }}
                                                </template>
                                                <template slot="selected-option" slot-scope="optionselected">
                                                    <div class="selected d-center" style="font-size: 11px;">  {{ optionselected.nomperfil }} </div>
                                                 </template>
                                              </Combo> 
                                        </div>
                                        <span class="text-error">{{ errors.first('Perfil Cobranza Activación') }}</span> 
                                    </div>

                                    </div>
                               </div> 


                                 
                                </div> 
                                

                             
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal('modalproducto')">Cerrar</button>
                            <!-- modificar boton aceptar -->
                            <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarproducto()">Guardar</button>
                            <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarproducto()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
          
            <!--Fin del modal-->
     <perfiles @cerrarvueprincipal="cerrarModalvue"  ref="ModalPerfiles" :idmodulo="idmodulo"></perfiles>
     
        </main>

</template>

<script>
 
 
    import vSelect from 'vue-select'
     Vue.component('Combo', vSelect);
    
    export default {
         props:['idmodulo','idventanamodulo'],
        data (){
            return {
                arrayPermisos:{Nuevo:0,Edición:0,Habilitar_Deshabilitar_Producto:0,Edición_Perfiles:0,Consolidar_perfil:0},
                arrayPermisosIn:[],
                classModal:null,
                producto_id: 0,
                pasoeditar:0,
                id_factor:'',
                id_escala:'',
                idmoneda: '',
                nomproducto : '',
                codproducto : '',
                idPerfilDesembolso:'',
                idPerfilRefinanciamientoDesembolso:'',
                idPerfilRefinanciamientoCobranza:'',
                idPerfil_ActivacionGarante_Desembolso:'',
                idPerfil_ActivacionGarante_Cobranza:'',
                idPerfilCobranza_manual:'',
                idPerfilCobranza_ascii:'',
                idPerfilCobranza_acreedor:'',
                idPerfilCobranza_daro:'',
                idPerfilCambio_estado:'',
                idPerfilCambio_estado_mora:'',
                moneda :'',
                tasa:0,
                garantes:0,  
                plazominimo: 0,
                plazomaximo: 0,

                max_prestamos:0,
                activacion_a_garante:0,
                lineaC:0,
                lote:0,
                cancelarprestamos:0, 
                isrefinanciable:0, 

                arrayproducto : [],
                arrayPerfiles:[],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorproducto : 0,
                errorMostrarMsjproducto : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nomproducto',
                buscar : '',
                arrayMoneda :[],
                arrayfactores :[],
                arrayescalas:[]
            }
        },

        formOptions:{
            validateAfterLoad: true,
            validateAfterChanged: true
        },
 watch:{
//    max_prestamos: function() {
//       if(this.max_prestamos){
//        $('input[id="lineacredito"]').attr("disabled", false);
//        $('input[id="lineacredito"]').removeAttr("disabled");
//        $('input[id="lineacancelarprestamos"]').attr("disabled", false);
//        $('input[id="lineacancelarprestamos"]').removeAttr("disabled");
//        /* para desactivar linea de credito en todos los productos*/
//        this.lineaC=0;
//         $('input[id="lineacredito"]').attr("disabled", true); 
//       }else{
//           this.lineaC=0;
//           this.cancelarprestamos=0;
//        $('input[id="lineacredito"]').attr("disabled", true); 
//        $('input[id="lineacancelarprestamos"]').attr("disabled", true); 

//       }
//     },
//  lineaC:function(){
//      if(this.lineaC){
//          this.cancelarprestamos=0;
//      }
//  },
//  cancelarprestamos:function(){
//      if(this.cancelarprestamos){
//          this.lineaC=0;
//      }
//  }
  cancelarprestamos:function(){
     if(this.cancelarprestamos){
         this.activacion_a_garante=0;
            if(this.pasoeditar==0){
                this.idPerfilRefinanciamientoDesembolso='';  
            } 
         this.idPerfil_ActivacionGarante_Desembolso=''; 
         this.idPerfil_ActivacionGarante_Cobranza='';
         
         this.idPerfilDesembolso='';
          
     }
 },
   isrefinanciable:function(){
     if(this.isrefinanciable&&this.pasoeditar==0){  
                this.idPerfilRefinanciamientoCobranza='';    
     }
 },
  activacion_a_garante:function(){
     if(this.activacion_a_garante){
         this.cancelarprestamos=0; 
         this.idPerfilRefinanciamientoDesembolso=''; 
         
         if(this.pasoeditar==0){
            this.idPerfil_ActivacionGarante_Desembolso=''; 
            this.idPerfil_ActivacionGarante_Cobranza='';
         } 
         this.idPerfilDesembolso='';
     }
 }

 },
        computed:{
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
            getPermisos() {   
                var url= '/adm_role/selectPermisos?idmodulo=' + this.idmodulo + '&idventanamodulo=' + this.idventanamodulo;
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
             
            visormaps(value){
                console.log('visor:',value);
                console.log('serializedmap:',value.serializedmap);
                console.log('json:',JSON.parse(value.serializedmap));
              var aux = new Map(JSON.parse(value.serializedmap));
              return aux.size>0;
            },
            consolidar(prod){
              var mapDataGeneral = new Map(JSON.parse(prod.serializedmap));
              
              if(mapDataGeneral.size>0){
 
            swal({
                title: '¿Esta seguro de consolidar este Producto?',
                text:'El proceso es irreversible',
                type: 'info',
                showConfirmButton: true,
                allowOutsideClick: () => false,
                allowEscapeKey:() => false,
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText:'No', 
                confirmButtonColor: '#4dbd74',
                cancelButtonColor: '#f86c6b', 
                buttonsStyling: true,
                reverseButtons: true
                }).then((result) => {
                    if (result.value) { 
                    let me = this;
                    swal({
                    title: "Registrando los Datos", 
                    allowOutsideClick: () => false,
                    allowEscapeKey:() => false, 
                    onOpen: function() { 
                        swal.showLoading() ;
                    }}).catch(error => { swal.showValidationError( 'Request failed: ${error}' )  }); 
                        
                        axios.post('/productosperfil/update',{'idproducto':prod.idproducto}).then(function (response) { 
                            me.regperfiles(mapDataGeneral,prod.idproducto).then(function(responses) { 
                                    if(responses){
                                        swal("¡Se registro los datos correctamente!", "", "success").then((result) => {
                                             me.listarProducto(1,me.buscar,me.criterio);
                                        }); 
                                    }else{
                                       swal("¡Ocurrio un problema al registrar los datos!",'', "error"); 
                                    }
                                }); 
                        }).catch(function (error) {  responses=false;  });   


                   


                    }  
                });  

              }else{
                swal("¡Debe introducir las formulas a todos los perfiles!",'', "warning");  
              } 
            }, async regperfiles(mapmaestro,id){
                let responses = true;
                try {  
                     for (var [key, element] of mapmaestro){ 
                         var mapData = new Map(JSON.parse(element)); 
                         for (var [llave, values] of mapData) {  
                      await axios.post('/productosperfil/regperfil',{ 
                            'idproducto': values.idpro,
                            'idperfilcuentadetalle':values.idperfilcuentadetalle, 
                            'idperfilcuentamaestro':values.idperfilcuentamaestro, 
                            'valor_abc':values.data, 
                            'valor_abc_php':values.data_php, 
                            'iscargo':values.adi, 
                            'iscomision':values.iscomision, 
                            'formula':values.formula, 
                            'formulaphp':values.formula2 
                            }).then(function (response) { responses=true;  }).catch(function (error) {  responses=false;  });   
                          }
                        };

                    if(responses){
                     axios.put('/par_producto/activar',{ 
                        'idproducto':id
                    }).then(function (response) { responses=true;  }).catch(function (error) {  responses=false;  });     
                    }

                } catch (err) {
                       responses=false;
                }
              return responses;      
            },
                cerrarModalvue(){   
                    this.listarProducto(1,this.buscar,this.criterio);
                },
                perfilesproducto(id){ 
                   this.$refs.ModalPerfiles.showVueperfiles(id);  
                },  
            listar(){
                let me=this; 
                var url= '/con_perfilcuentamaestro/selectPerfilcuentamaestro?idmodulo='+ this.idmodulo;
                axios.get(url).then(function (response) { 
                    me.arrayPerfiles= response.data.perfilcuentamaestros; 
                }).catch(function (response) {
                    console.log(response);
                });
            } ,
            listarProducto(page,buscar,criterio){
                let me=this;
                var url= '/par_producto?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayproducto = respuesta.productos.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (response) {
                    console.log(response);
                });
            },

            selectMoneda(){
                let me=this;
                var url= '/par_moneda/selectMoneda';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayMoneda = respuesta.monedas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            }, 
             selectFactor(){
                let me=this;
                var url= '/par_procesos_fatores/selectfactor';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayfactores = respuesta.factores;
                })
                .catch(function (error) {
                    console.log(error);
                });
            }, 
             selectEscala(){
                 ///////////////////////////////////////////////hacer para escalaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
                let me=this;
                var url= '/par_prestamos_escalas/getescalas';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayescalas = respuesta.escalas;
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
                me.listarProducto(page,buscar,criterio);
            },
            registrarproducto(){              
                let me = this;

                axios.post('/par_producto/registrar',{
                    'nomproducto': this.nomproducto,
                    'codproducto':this.codproducto,
                    'moneda': this.moneda,
                    'tasa': this.tasa,
                    'garantes': this.garantes,
                    'max_prestamos': this.max_prestamos,
                    'lote': this.lote,
                    'activar_garante': this.activacion_a_garante,
                    'linea': this.lineaC,
                    'cancelarprestamos': this.cancelarprestamos, 
                    'idfactor':this.id_factor, 
                    'idescala':this.id_escala, 
                    'plazominimo': this.plazominimo,
                    'plazomaximo': this.plazomaximo,
                    'desembolso_perfil':this.idPerfilDesembolso.toString().length>0?this.idPerfilDesembolso:0,
                    'desembolso_perfil_refi':this.idPerfilRefinanciamientoDesembolso.toString().length>0?this.idPerfilRefinanciamientoDesembolso:0,
                    'cobranza_perfil_refi':this.idPerfilRefinanciamientoCobranza.toString().length>0?this.idPerfilRefinanciamientoCobranza:0,
                    'desembolso_perfil_garante':this.idPerfil_ActivacionGarante_Desembolso.toString().length>0?this.idPerfil_ActivacionGarante_Desembolso:0,
                    'cobranza_perfil_garante':this.idPerfil_ActivacionGarante_Cobranza.toString().length>0?this.idPerfil_ActivacionGarante_Cobranza:0,
                    'cobranza_perfil':this.idPerfilCobranza_manual,                   
                    'cobranza_perfil_ascii':this.idPerfilCobranza_ascii,                   
                    'cobranza_perfil_acreedor':this.idPerfilCobranza_acreedor,                   
                    'cobranza_perfil_daro':this.idPerfilCobranza_daro,                   
                    'perfil_cambio_estado':this.idPerfilCambio_estado,                   
                    'perfil_cambio_estado_mora':this.idPerfilCambio_estado_mora                   
                    
                }).then(function (response) {
                   me.cerrarModal('modalproducto'); 
                    swal("¡Se registro los datos correctamente!", "", "success").then((result) => {
					   me.listarProducto(1,'','nomproducto');   
                      });
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarproducto(){ 
            swal({
                title: '¿Esta seguro de modificar los datos registrados?',
                text:'La configuración de los perfiles de cuenta seran eliminados',
                type: 'warning',
                showConfirmButton: true,
                allowOutsideClick: () => false,
                allowEscapeKey:() => false,
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText:'No', 
                confirmButtonColor: '#4dbd74',
                cancelButtonColor: '#f86c6b', 
                buttonsStyling: true,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;   
                    axios.put('/par_producto/actualizar',{
                        'nomproducto': this.nomproducto,
                        'codproducto':this.codproducto,
                        'moneda': this.moneda,
                        'tasa':this.tasa,
                        'garantes': this.garantes,
                        'max_prestamos': this.max_prestamos,
                        'lote': this.lote,
                        'activar_garante': this.activacion_a_garante,
                        'linea': this.lineaC,
                        'cancelarprestamos': this.cancelarprestamos, 
                        'idfactor':this.id_factor,
                        'idescala':this.id_escala,  
                        'plazominimo': this.plazominimo,
                        'plazomaximo': this.plazomaximo,
                        'idproducto': this.producto_id,
                        'desembolso_perfil':this.idPerfilDesembolso.toString().length>0?this.idPerfilDesembolso:0, 
                        'desembolso_perfil_refi':this.idPerfilRefinanciamientoDesembolso.toString().length>0?this.idPerfilRefinanciamientoDesembolso:0,
                        'cobranza_perfil_refi':this.idPerfilRefinanciamientoCobranza.toString().length>0?this.idPerfilRefinanciamientoCobranza:0,
                        'desembolso_perfil_garante':this.idPerfil_ActivacionGarante_Desembolso.toString().length>0?this.idPerfil_ActivacionGarante_Desembolso:0,
                        'cobranza_perfil_garante':this.idPerfil_ActivacionGarante_Cobranza.toString().length>0?this.idPerfil_ActivacionGarante_Cobranza:0,
                        'cobranza_perfil':this.idPerfilCobranza_manual,
                        'cobranza_perfil_ascii':this.idPerfilCobranza_ascii,                   
                        'cobranza_perfil_acreedor':this.idPerfilCobranza_acreedor,                   
                        'cobranza_perfil_daro':this.idPerfilCobranza_daro,  
                        'perfil_cambio_estado':this.idPerfilCambio_estado,  
                        'perfil_cambio_estado_mora':this.idPerfilCambio_estado_mora  
                    }).then(function (response) {
                    me.cerrarModal('modalproducto'); 
                        swal("¡Se actualizo los datos correctamente!", "", "success").then((result) => {
                        me.listarProducto(1,'','nomproducto'); 
                        })
                    }).catch(function (error) {
                        console.log(error);
                    });
                }  
                }) ;
                $(".swal2-modal").css('z-index', '2000'); 
                $(".swal2-container").css('z-index', '2000');
  
            },
            desactivarproducto(idproducto){
               swal({
                title: 'Esta seguro de desactivar este Producto?',
                type: 'warning',
                showConfirmButton: true,
                allowOutsideClick: () => false,
                allowEscapeKey:() => false,
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText:'No', 
                confirmButtonColor: '#4dbd74',
                cancelButtonColor: '#f86c6b', 
                buttonsStyling: true,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this; 
                    axios.put('/par_producto/desactivar',{
                        'idproducto': idproducto
                    }).then(function (response) {
                        me.listarProducto(1,'','nomproducto');
                        swal(
                        'Desactivado!',
                        'El registro ha sido desactivado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                }  
                }); 
            },
            activarproducto(idproducto){
               swal({
                title: 'Esta seguro de activar este Producto?',
                type: 'warning',
                showConfirmButton: true,
                allowOutsideClick: () => false,
                allowEscapeKey:() => false,
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText:'No', 
                confirmButtonColor: '#4dbd74',
                cancelButtonColor: '#f86c6b', 
                buttonsStyling: true,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/par_producto/activar',{
                        'idproducto': idproducto
                    }).then(function (response) {
                        me.listarProducto(1,'','nomproducto');
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

            cerrarModal(id){
                this.classModal.closeModal(id)
                this.modal=0;  
                this.codigo='';
                this.inicial='';
                this.final='';
            },
            abrirModal(id,modelo, accion, data = []){
                this.modal = 1; 
                this.classModal.openModal(id);  
                this.selectMoneda();
                this.selectFactor();
                this.selectEscala();
                switch(modelo){
                    case "producto":
                    {
                        switch(accion){
                            case 'registrar':

                            {   
                                
                                this.listar(); 
                                this.pasoeditar=0;
                                this.tituloModal = 'Registrar Producto Crediticio';
                                this.nomproducto= '';
                                this.codproducto='';
                                this.id_factor='';
                                this.id_escala='';
                                this.moneda= '',
                                this.tasa=0,
                                this.garantes=0,
                                this.plazominimo= 0;
                                this.plazomaximo= 0;
                                this.idPerfilDesembolso= '';
                                this.idPerfilRefinanciamientoDesembolso='';
                                this.idPerfil_ActivacionGarante_Desembolso='';
                                this.idPerfilRefinanciamientoCobranza='';
                                this.idPerfil_ActivacionGarante_Cobranza='';
                                this.idPerfilCobranza_manual= '';
                                this.idPerfilCobranza_ascii='';                   
                                this.idPerfilCobranza_acreedor='';                   
                                this.idPerfilCobranza_daro='';  
                                this.idPerfilCambio_estado='';  
                                this.idPerfilCambio_estado_mora='';  
                                this.tipoAccion = 1;
                                this.max_prestamos= 0;
                                this.lote= 0;
                                this.activacion_a_garante= 0;
                                this.lineaC= 0;
                                this.cancelarprestamos= 0;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.pasoeditar=1;
                                this.tituloModal='Actualizar Producto Crediticio';
                                this.tipoAccion=2;
                                this.listar(); 
                                this.producto_id=data['idproducto'];
                                this.nomproducto = data['nomproducto'];
                                this.codproducto = data['codproducto'];
                                this.moneda = data['idmoneda'];
                                this.tasa = data['tasa'];
                                this.id_factor = data['idfactor']; 
                                this.id_escala= data['idescala'];   
                                this.plazominimo = data['plazominimo'];
                                this.plazomaximo = data['plazomaximo'];  
                                this.garantes= data['garantes'];

                                this.max_prestamos= data['max_prestamos'];
                                this.lote= data['lote'];
                                this.activacion_a_garante= data['activar_garante'];
                                this.lineaC= data['linea'];
                                this.cancelarprestamos= data['cancelarprestamos'];
                                this.idPerfilDesembolso= parseInt(data['desembolso_perfil'])>0?data['desembolso_perfil']:'';

                                this.idPerfilRefinanciamientoDesembolso= parseInt(data['desembolso_perfil_refi'])>0?data['desembolso_perfil_refi']:'';
                                this.idPerfilRefinanciamientoCobranza= parseInt(data['cobranza_perfil_refi'])>0?data['cobranza_perfil_refi']:'';
                                this.isrefinanciable=parseInt(data['cobranza_perfil_refi'])>0?1:0;
                                this.idPerfil_ActivacionGarante_Desembolso= parseInt(data['desembolso_perfil_garante'])>0?data['desembolso_perfil_garante']:'';
                                this.idPerfil_ActivacionGarante_Cobranza= parseInt(data['cobranza_perfil_garante'])>0?data['cobranza_perfil_garante']:'';
 
                                this.idPerfilCobranza_manual= data['cobranza_perfil']; 
                                this.idPerfilCobranza_ascii= data['cobranza_perfil_ascii'];                    
                                this.idPerfilCobranza_acreedor= data['cobranza_perfil_acreedor'];                    
                                this.idPerfilCobranza_daro= data['cobranza_perfil_daro']; 
                                this.idPerfilCambio_estado= data['perfil_cambio_estado']; 
                                this.idPerfilCambio_estado_mora= data['perfil_cambio_estado_mora']; 
                                
                                
                                break;
                            }
                        }
                    }
                }
                
            }
        },
        mounted() {
            this.listarProducto(1,this.buscar,this.criterio);
            this.classModal=new _pl.Modals();
            this.classModal.addModal('modalproducto');
            this.listar();
            this.selectMoneda();
            this.selectFactor();
            this.selectEscala();
            this.getPermisos(); 
        }
    }
</script>

