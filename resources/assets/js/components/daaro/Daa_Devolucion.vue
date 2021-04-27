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
                <i class="fa fa-align-justify"></i> Devolucion de Aportes  

            </div>
            <div class="card-body">
                <div class="form-group row" style="justify-content: flex-end;">                    
                    <div class="col-md-9"> 
                        <div class="input-group" style="align-items: center;">
                            <p  style="text-align: right;margin: 0px; margin-right: 10px; font-weight: 500;">Criterio de busqueda:</p> 
                            <input type="text" v-model="buscar" @keyup.enter="listarSocio(1,buscar)" class="form-control" placeholder="Ingresar  Nombres , Apellidos , Ci , Numero de Papeleta">
                            <button type="submit" @click="listarSocio(1,buscar)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Foto</th>
                            <th>Grado</th> 
                            <th>Nombre Completo</th>
                            <th>Num Papeleta</th>
                            <th>CI</th>
                            <th>Aportes</th>
                            <th>Fuerza</th>
                            <th>Contabilidad</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="socio in arraysocio" :key="socio.idsocio">
                            <td>                                    
                                <template v-if="socio.activo">                                    
                                    
                                    <div v-if="socio.estado_cont_cump===3">
                                        <button type="button" disabled @click="abrirModalPre('modaDevolucion','devolucion',socio)" class="btn btn-warning btn-sm" title="Daaro Cumplimiento">
                                            <i class="cui-thumb-up"></i>
                                        </button>
                                        <button type="button" @click="revertir(socio.idsocio,socio.estado_cont_cump)" class="btn btn-danger btn-sm" title="Revertir">
                                            <i class="cui-thumb-up"></i>
                                        </button>
                                    </div>
                                    <div v-else>
                                        <button type="button" @click="abrirModalPre('modaDevolucion','devolucion',socio)" class="btn btn-warning btn-sm" title="Daaro Cumplimiento">
                                            <i class="cui-thumb-up"></i>
                                        </button>
                                    </div>

                                    <button type="button" @click="abrirModalPreJub('modaDevolucion','devolucion',socio)" class="btn btn-warning btn-sm" title="Daaro Jubilacion">
                                        <i class="cui-user-follow"></i>
                                    </button> &nbsp;

                                    <button type="button" @click="reporte_dev(socio.idsocio, dev_socio)" class="btn btn-warning btn-sm" title="Reportes">
                                        <i class="icon-user"></i>
                                    </button>&nbsp;

                                </template>                               
                            </td>
                            <td style=" vertical-align: middle;text-align: center;">
                                <img v-if="socio.rutafoto" :src="'storage/socio/'+socio.rutafoto"  class="rounded-circle fotosociomini" alt="Cinque Terre">
                                <img v-else :src="'storage/socio/avatar.png'"  class="rounded-circle fotosociomini" alt="Cinque Terre" >
                            </td> 
                            <td v-text="socio.nomgrado"></td> 
                            <td v-text="socio.nombre +' '+socio.apaterno+' '+socio.amaterno" ></td>
                            <td v-text="socio.numpapeleta"></td> 
                            <td v-text="socio.ci+' '+ socio.abrvdep"></td>
                            <td v-text="socio.cantaportes"></td>
                            <td v-text="socio.nomfuerza"></td>
                            <td align="center">   
                            
                            <div v-if="socio.estado_cont_cump===null && socio.estado_cont_jubi===null">
                                <span class="badge badge-pill badge-secondary">Sin<br>Contabilizar</span>
                            </div>
                            <div v-else> 
                                <div v-if="socio.estado_cont_cump!=null">
                                    <span class="badge badge-pill badge-success">Cumplimiento:</span>
                                    <template v-if="socio.estado_cont_cump===0">
                                            <span class="badge badge-warning">Borrador</span>
                                        </template>                                                                                                            
                                        <template v-if="socio.estado_cont_cump===1">
                                            <span class="badge badge-success">Validado <br> Comprobante: {{socio.comprobante_cump}}</span>
                                        </template>                                        
                                        <template v-if="socio.estado_cont_cump===2">
                                            <span class="badge badge-warning">Eliminado</span>
                                        </template>                                        
                                        <template v-if="socio.estado_cont_cump===3">
                                            <span class="badge badge-danger">Observado</span>
                                        </template>
                                        <template v-if="socio.estado_cont_cump===4">
                                            <span class="badge badge-danger">Revertido</span>
                                        </template>                                    
                                </div>
                                <div v-if="socio.estado_cont_jubi!=null">
                                    <span class="badge badge-pill badge-success">Jubilacion:</span>
                                    <template v-if="socio.estado_cont_jubi===0">
                                            <span class="badge badge-warning">Borrador</span>
                                        </template>                                                                                                            
                                        <template v-if="socio.estado_cont_jubi===1">
                                            <span class="badge badge-success">Validado <br> Comprobante: {{socio.comprobante_jubi}}</span>
                                        </template>                                        
                                        <template v-if="socio.estado_cont_jubi===2">
                                            <span class="badge badge-warning">Eliminado</span>
                                        </template>                                        
                                        <template v-if="socio.estado_cont_jubi===3">
                                            <span class="badge badge-danger">Observado</span>
                                        </template>
                                        <template v-if="socio.estado_cont_jubi===4">
                                            <span class="badge badge-danger">Revertido</span>
                                        </template>                                    
                                </div>
                            </div>
                               
                            </td>
                            <td>
                                <div v-if="socio.activo">
                                    <span class="badge badge-success">Activo</span><br>                                     
                                    <span v-if="socio.estado_daaro" class="badge badge-success">
                                        <div v-for="aux1 in (socio.estado_daaro.split(','))" :key="aux1">                                                                                        
                                            Cobro: {{aux1}}                                            
                                        </div>
                                    </span>
                                </div>

                                <div v-else>
                                    <span class="badge badge-danger">Desactivado <br>{{socio.estado_daaro}}</span>
                                </div>                                
                            </td>
                        </tr>                                
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination">
                        <li class="page-item" v-if="pagination.current_page > 1">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar)">Ant</a>
                        </li>
                        <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar)" v-text="page"></a>
                        </li>
                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar)">Sig</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
    </div>   

    <!--Inicio del modal devoluciones-->    
    <div class="modal fade " tabindex="-1"  role="dialog"  aria-hidden="true" id="modaDevolucion" >
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" class="close" @click="cerrarModal('modaDevolucion')"  aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                
                <div class="modal-body-plandepagos1">
                        
                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" @submit.prevent="validateBeforeSubmit" >
                        <div class="form-group row" style=" margin-left: 3px;margin-right: 3px;">
                            
                            <p class="col-md-12 tituloP" >{{grado}} {{nombre}} {{apaterno}} {{amaterno}}</p>
                            <div class="col-md-3" style="text-align: center;">
                                <img v-if="fotosocio" :src="'storage/socio/'+fotosocio"  class="fotosocioimg" alt="Cinque Terre">
                                <img v-else :src="'storage/socio/avatar.png'"  class="fotosocioimg" alt="Cinque Terre" > 
                            </div>
                            <div class="form-group" style="display: inherit;" >
                                <div class="col-md-15" >
                                    <table align="center" border="0" cellspacing="0" cellpadding="0"> 
                                    <tr><td class="titulo">Papeleta:</td><td align="right" class="contentTitulo" v-text="numpapeleta"></td></tr>
                                    <tr><td class="titulo" >C.I.:</td><td align="right" class="contentTitulo"  >{{ci}} {{ext}}</td></tr>
                                    <!-- <tr><td><p class="titulo" >Años Servicio:</p></td><td align="left"><p class="contentTitulo" v-text="servicio"></p></td></tr> -->

                                    <template v-if="devoluciontipo===1">
                                        <tr><td class="titulo" >Total Aportes Considerados:</td><td align="right" class="contentTitulo" v-text="aportejubilado"></td></tr>
                                    </template>                                        
                                    <template v-else>
                                        <tr><td class="titulo" >Total Aportes Considerados:</td><td align="right" class="contentTitulo" v-text="aporteobligado"></td></tr>
                                    </template>                              
                                    <tr><td class="titulo" >Primer Aporte:</td><td align="right" class="contentTitulo" v-text="primer_aporte"></td></tr>
                                    <tr><td class="titulo" >Ultimo Aporte:</td><td align="right" class="contentTitulo" v-text="ultimo_aporte"></td></tr>
                                    <tr><td class="titulo" >Aportes a la fecha. TOTAL:</td><td align="right" class="contentTitulo"  >Bs. {{total_bs}}</td></tr>
                                    </table>
                                </div>
                            </div>

                        </div>           
                        
        <!-- ///////////////////////////////////////////////////////DEVOLUCIONES/////////////////////////////////////////////////////////////////////////// -->                   
    <ul>
        <li v-for="error in errors.all()" v-bind:key="error"> {{ error }}</li>
    </ul>

        <div class="form-group row animated fadeIn"> 
            <div class="col-md-6" style="border-right: 1px solid #c2cfd6; padding-bottom: 12px;" >
                <p class="col-md-12 titulopaso" >Preliquidacion - Borrador</p> 

                <div class="col-md-12" style="display: flex;">                
                    <div>
                    <label class="col-md-6">% Anual Nominal</label>
                        <div class="col-md-9">
                            <input v-validate.initial="'required'" 
                                type="number" step="0.1"
                                class="form-control"                                  
                                v-model="anualnominal"  
                                placeholder="" @keyup="bloquear" @click="bloquear"
                                name="anualnominal"> 
                        </div> 
                        <span v-show="errors.has('anualnominal')" class="text-danger col-md-6">% Necesaria</span>
                    </div>                    
                    <div>
                        <label class="col-md-6">Capitalizacion Mensual</label>
                        <div class="col-md-9">
                            <input v-validate.initial="'required|numeric'" 
                                type="number"
                                class="form-control"  
                                v-model.number="capitalizacionmensual"  @keyup="bloquear" @click="bloquear"
                                placeholder="" 
                                name="capitalizacionmensual"> 
                        </div>
                        <span v-show="errors.has('capitalizacionmensual')" class="text-danger col-md-6">Cap. Necesaria {{ errors.first('capitalizacionmensual   ') }}</span>                        
                    </div>                
                </div>

                <div class="input-group">
                    <label style="text-align: left;" class="col-md-6 form-control-label" for="text-input">&nbsp;</label>
                </div>                                
                
                <div class="input-group">
                <label style="text-align: left;" class="col-md-6 form-control-label" for="text-input">Estudio Matematico:</label>
                    <div class="form-group row">                                                                
                            <select class="form-control" 
                                v-model="idestudiomatematico"
                                v-validate="'required'"
                                name="estudio" @change="bloquear($event)"
                                :class="{'form-control': true, 'error': errors.has('estudio')}">>
                                <option selected="selected" value="" disabled >...Seleccione...</option>
                                <option v-for="estudiomat in arrayData1" :key="estudiomat.idestudiomatematico" :value="estudiomat.idestudiomatematico" v-text="estudiomat.nomestudiomatematico"></option>
                            </select>
                            <span v-show="errors.has('estudio')" class="text-danger">{{ errors.first('estudio') }}</span>
                    </div>
                </div>    
                <div v-if="idestudiomatematico===''" class="text-error">Dato Requerido</div>

                <div class="input-group">
                <label style="text-align: left;" class="col-md-6 form-control-label" for="text-input">Tipo Devolucion:</label>
                    <div class="form-group row">                                                                
                            <select class="form-control" 
                                v-model="idtipodevolucion"                                
                                name="idtipodevolucion" @keyup="bloquear" @click="bloquear">                           
                                <option selected="selected" value="" disabled >...Seleccione...</option>
                                <option v-for="tipodev in arrayData" :key="tipodev.idtipodevolucion" :value="tipodev.idtipodevolucion" v-text="tipodev.nomtipodevolucion"></option>
                            </select>                                        
                    </div>
                </div>
                <div v-if="idtipodevolucion===''" class="text-error">Dato Requerido</div>

                <div class="input-group">
                    <template v-if="idtipodevolucion && idestudiomatematico && anualnominal && capitalizacionmensual">
                        <template v-if="devoluciontipo===1">
                            <button type="button" id="calcular" @click="onChangematematico(idtipodevolucion,idestudiomatematico,numpapeleta,aportejubilado,anualnominal,capitalizacionmensual)" 
                            class="btn btn-primary" aria-pressed="true">
                            Calcular Devolucion
                            </button>
                        </template>
                        <template v-else>
                            <button type="button" id="calcular" @click="onChangematematico(idtipodevolucion,idestudiomatematico,numpapeleta,aporteobligado,anualnominal,capitalizacionmensual)" 
                            class="btn btn-primary" aria-pressed="true">
                            Calcular Devolucion
                            </button>
                        </template>
                    </template>

                    <template v-else>
                        <button type="button" id="calcular" disabled class="btn btn-danger" aria-pressed="true">
                        Calcular Devolucion
                    </button>
                    </template>                                        
                </div>    
                &nbsp;&nbsp;&nbsp;
                <div class="card-header">
                    <p align="center">
                    <label class="input-group-append" style="cursor: pointer;" data-toggle="modal" data-target="#detalleDevolucion" :class="{'ocultaricono':totaldevolucion==0}"><b><h6>Total Bs.: {{totaldevolucion}}</h6></b>
                    <span class="input-group-text">
                            <i class="fa fa-search"></i>                             
                    </span>
                    </label>
                    </p>
                </div>
                    <!-- <input 
                        v-validate.initial="'required'" 
                        v-bind:class="'col-md-6 form-control is-valid formu-entrada'"
                        type="number"  
                        name="devolucion"
                        placeholder="total devolucion"
                        v-model="totaldevolucion" readonly> 
                        <div class="input-group-append" style="cursor: pointer;" data-toggle="modal" data-target="#detalleDevolucion" :class="{'ocultaricono':totaldevolucion==0}">                        
                        </div>  -->
                
                    
            </div>
                <div class="col-md-6">
                        <p class="col-md-12 titulopaso" >Descuentos por Area</p> 
                        
                        <div class="col-md-12" style="display: flex;">
                            <div >
                            <label class="col-md-12">P. Emergencia:</label>
                                <div class="col-md-12">
                                    <input v-validate.initial="'required|decimal:2'" 
                                        type="number" 
                                        class="form-control"  
                                        v-model.number="emergencia"  
                                        placeholder="ej.: 100"
                                        name="emergencia"> 
                                    <span v-show="errors.has('emergencia')" class="text-danger">{{ errors.first('emergencia') }}</span>    
                                </div>
                            </div>
                            <div>
                                <label class="col-md-12">P. Regular:</label>
                                <div class="col-md-12">
                                    <input v-validate.initial="'required|decimal:2'" 
                                        type="number" 
                                        class="form-control"  
                                        v-model.number="regular" 
                                        placeholder="ej.: 100" 
                                        name="regular"> 
                                    <span v-show="errors.has('regular')" class="text-danger">{{ errors.first('regular') }}</span>    
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12" style="display: flex;">
                            <div >
                                <label class="col-md-12">Servicios:</label>
                                <div class="col-md-12">
                                        <input v-validate.initial="'required|decimal:2'" 
                                        type="number" 
                                        class="form-control"  
                                        v-model.number="servicio"  
                                        placeholder="ej.: 100"
                                        name="servicios"> 
                                    <span v-show="errors.has('servicios')" class="text-danger">{{ errors.first('servicios') }}</span>    
                                </div>
                            </div>
                            <div >
                                <label class="col-md-12">Juridica:</label>
                                <div class="col-md-12">
                                    <input v-validate.initial="'required|decimal:2'" 
                                        type="number" 
                                        class="form-control"  
                                        v-model.number="juridica"  
                                        placeholder="ej.: 100"
                                        name="juridico"> 
                                    <span v-show="errors.has('juridico')" class="text-danger">{{ errors.first('juridico') }}</span>    
                                </div>                                
                            </div> 
                        </div>    
                        <div class="col-md-12" style="display: flex;">                            
                            <div >
                                <label class="col-md-12">Retencion 1% <i>(Retiro y/o Cumplimiento)</i>:</label>
                                <div class="col-md-12">
                                    <label class="form-control">Bs. {{retencion}}</label>
                                    <!-- <input v-validate.initial="'required|numeric'" 
                                        type="number" 
                                        class="form-control"  
                                        v-model.number="retencion"
                                        name='retencion'  
                                        placeholder="" disabled>  -->
                                </div>
                            </div> 
                            <div >
                                <label class="col-md-12">TOTAL DESCUENTOS:</label>
                                <div class="col-md-12">
                                    <label class="form-control">Bs. {{total_descuentos}}</label>
                                    <!-- <input  
                                        type="number" 
                                        class="form-control"  
                                        v-model.number="total_descuentos"  
                                        placeholder="" disabled>  -->
                                </div>
                            </div> 
                        </div>                                      

                        <div class="input-group">
                            <label style="text-align: left;" class="col-md-6 form-control-label" for="text-input">&nbsp;</label>
                        </div>   
                            <div class="input-group">
                            <label style="text-align: left;" class="col-md-6 form-control-label" for="text-input"><b>TOTAL DEVOLUCION:</b><i> (Con Descuento)</i></label>
                            <!-- <input 
                                class="'col-md-6 form-control is-valid formu-entrada'"
                                type="number"  
                                v-model="totaldevdescuento"
                                :class="{'form control is-invalid factordeterminantecss2': totaldevdescuento!=0}" 
                                placeholder="total devolucion"                            
                                step="any" disabled>  -->
                            <label class="input-group-append" :class="{'form control is-invalid': totaldevdescuento!=0}"><b><h5>Bs. {{totaldevdescuento}}</h5></b>
                            </label>
                            </div>
                        </div>                                                                                                         
                </div> 

                <p class="col-md-12 titulopaso" >Registro Contable</p> 
                    <!-- <a class="btn btn-block btn-secondary" data-toggle="collapse" href="#regcontabilidad" aria-expanded="false" aria-controls="rolpadre">Registrar</a>
                    <div class="collapse multi-collapse" id="regcontabilidad"> -->

                        <div class="input-group">
                        <label style="text-align: left;" class="col-md-6 form-control-label" for="text-input">Perfil de Cuenta:</label>
                            <div class="form-group row">                                                                
                                    <select class="form-control" 
                                        v-model="idperfilcuentamaestro"
                                        name='perfil'>
                                        <option selected="selected" value="" disabled >...Seleccione...</option>
                                        <option v-for="perfil in arrayPerfil" :key="perfil.idperfilcuentamaestro" :value="perfil.idperfilcuentamaestro" v-text="perfil.nomperfil"></option>
                                    </select>
                            </div>                            
                            <div v-if="idperfilcuentamaestro===''" class="text-error">Dato Requerido</div>
                        </div>
                                                
                        
                        
                        <div class="input-group">
                            <label style="text-align: left;" class="col-md-6 form-control-label" for="text-input">Tipo de documento:</label>
                            <div class="form-group row">
                            <input v-validate.initial="'required'" 
                                type="text" 
                                class="form-control"  
                                v-model="tipodocumento"                                      
                                name="tipodocumento">   
                                </div>                         
                        </div>                       
                        <span v-show="errors.has('tipodocumento')" class="text-danger">{{ errors.first('tipodocumento') }}</span>     
                                                                            
                        
                        <div class="input-group">
                            <label style="text-align: left;" class="col-md-6 form-control-label" for="text-input">Numero de documento:</label>
                            <div class="form-group row">
                            <input v-validate.initial="'required'" 
                                type="text" 
                                class="form-control"
                                v-model="numerodocumento"
                                name="numerodocumento">   
                                </div>                         
                        </div>                       
                        <span v-show="errors.has('numerodocumento')" class="text-danger">{{ errors.first('numerodocumento') }}</span>
                        
                        <div class="input-group">
                        <label style="text-align: left;" class="col-md-6 form-control-label" for="text-input">Glosa:</label>
                            <div class="form-group row">                                                                
                                <textarea v-model="glosa" class="form-control" placeholder="Glosa"></textarea>                                
                            </div>
                        </div>
                    <!-- </div> -->
                                
                </form>
                </div>
                <div class="modal-footer"> 
                    <template v-if="(aux===1)">
                        <button :disabled = "errors.any() || totaldevolucion===''" type="submit" class="btn btn-primary" @click="editdaaro('modaDevolucion')">Editar DAARO</button>
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('modaDevolucion')">Cerrar</button>      
                    </template>
                    <template v-else>                         
                        <button :disabled = "errors.any() || totaldevolucion===''" type="submit" class="btn btn-primary" @click="regdaaro('modaDevolucion')">Registrar DAARO</button>
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('modaDevolucion')">Cerrar</button>      
                    </template>                                            
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    
    <!--Fin del modal-->

    <!-- para poner otro modal --> 
    <div class="modal fade " tabindex="-1"  role="dialog"  style="z-index: 1600;" aria-hidden="true" id="detalleDevolucion" >
    <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content animated fadeIn">

            <div class="modal-header"> 
                <h4 class="modal-title" id="modalOneLabel">Detalle Devolucion Aportes </h4>
                <button type="button" class="close" aria-hidden="true" aria-label="Close" @click="classModal.closeModal('detalleDevolucion')"><span aria-hidden="true">×</span></button>
            </div> 

            <div class="modal-body-plandepagos" v-html="detDevolucion"> 
            </div>  

            <div class="modal-footer"> 
                <button class="btn btn-secondary" type="button" @click="classModal.closeModal('detalleDevolucion')">cerrar</button>
                <!-- <button class="btn btn-primary" type="button">Imprimir</button>                 -->
            </div>    
        </div>
    </div>
    </div>

           
</main>
</template>

<script>

    import Vue from 'vue';
    import VeeValidate from 'vee-validate';
    import * as plugin from '../../functions.js';
    import vSelect from 'vue-select'
    import * as repo from '../../functions.js';
    Vue.component('v-select', vSelect);

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

    export default {
        props: ['idmodulo'],
        data (){
            return {
                classModal:null,                
                socio_id: '', 
                numpapeleta: '', 
                ci : '',
                apaterno :'',
                moneda :'',
                amaterno :'',
                nombre :'',
                grado:'',
                ext:'',      
                arraysocio: [],          
                fotosocio :'',
                detDevolucion:'<div>No Data Devolucion</div>',                             
                aporteobligado:0,
                aportejubilado:0,
                tipoobligado:0,
                tipojubilado:0,
                total_aportes: 0,
                primer_aporte: 0,
                ultimo_aporte: 0,
                total_bs:0,
                dev_socio:'',
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorsocio : 0,
                errorMostrarMsjsocio : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3, 
                buscar : '',


                nomsocio: '',                  
                //////////////
                arrayData: [],
                arrayData1: [],
                arrayData2: [],
                arrayData3: [],
                arrayRegistrado: [],
                arrayPerfil: [],
                idtipodevolucion: '',
                idestudiomatematico: '',
                totaldevolucion:'',
                fechaminimoaporte:'',
                fechamaximoaporte:'',
                capitalizacionmensual: 0,
                anualnominal:0,
                emergencia:0,
                servicio:0,
                juridica:0,
                total_descuentos:0,
                regular:0,
                retencion:0,
                estudiomatematico:'',
                tipodevolucion:'',
                totalaporte:'',
                rendimiento:'',
                porcentaje:'',
                aporteminimo:'',
                valido:'',
                devoluciontipo:'',
                aux:0,
                idperfilcuentamaestro:'',
                tipodocumento:'0',
                numerodocumento:'0',
                glosa:'', cobro:'',
            }
        },

        formOptions:{
            validateAfterLoad: true,
            validateAfterChanged: true
        },

        computed:{                                                
            
            totaldevdescuento: function() { 
                let sum=parseFloat(this.totaldevolucion)-(parseFloat(this.emergencia)+parseFloat(this.servicio)+parseFloat(this.juridica)+parseFloat(this.regular)+parseFloat(this.retencion));
                this.total_descuentos=(parseFloat(this.emergencia)+parseFloat(this.servicio)+parseFloat(this.juridica)+parseFloat(this.regular)+parseFloat(this.retencion));

                //mandara los datos para el detgalle
                var salida=plugin.detalleAportes(this.arrayData2,this.fechamin,this.fechamax,this.total_aportes); 
                this.detDevolucion=salida['plan'];
                this.totalaporte=salida['totalaporte']; 
                this.rendimiento=salida['rendimiento']; 
                return sum>0?this.redondeo(sum,2):0;
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

            reporte_dev(id,dev_socio){
              var url=dev_socio + id;                console.log(url);
              repo.viewPDF(url,'Devolucion DAARO');
            }, 

            bloquear(event) {
                this.totaldevolucion='';                
                // $('#calcular').attr('disabled',true);
                // $('#calcular').addClass('btn btn-danger');                
                // $('#total0').val('');
                // $('#total1').val('');                
            },

            selectTipodevolucion(tipo, cantaportes){ 
                let me=this;
                var url= '/daa_devolucion/selectTipodevolucion?tipo=' + tipo+'&cantaportes='+cantaportes;
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayData = respuesta.tipodevolucion;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectEstudiomatematico(){
                let me=this;
                var url= '/daa_estudiomatematico/selectEstudiomatematico';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayData1 = respuesta.estudiomatematico;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectPerfilcuenta(){
                let me=this;
                var url= '/daa_perfilcuenta/selectPerfilcuenta?idmodulo='+ this.idmodulo;
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayPerfil = respuesta.perfilcuenta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            //calculamos segun la formula des estudio matematico
            onChangematematico(x,y,z,ca,anual,capital) {  //x=id tipo dev, y=estudio mate, z=numpapeleta, ca=cantidad aportes
                let me = this;                 
                //console.log(x,y,z,ca,anual,capital);
                var url= '/daa_devolucion/calculomatematico?idtipodevolucion='+x+'&idestudiomatematico='+y+'&numpapeleta='+z+'&cantidadaportes='+ca+'&anual='+anual+'&capital='+capital;
                axios.get(url).then(function (response) {  
                    //console.log(response.data);
                    var respuesta= response.data; 
                    if (respuesta===0) {  //error
                        swal({
                            title: "Error",
                            text: "No se puede realizar la operacion", 
                            type: "warning",
                            showCancelButton: false,
                            showConfirmButton: true,                                                
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,                   
                            });
                            me.cerrarModal('modaDevolucion')
                    }
                    else {
                        me.totaldevolucion = plugin.redondeo_valor(respuesta.totaldevolucion,2);                     
                        me.arrayData2 = respuesta.salida; 
                        me.arrayData3 = respuesta.fechasaportes; 
                        me.fechamin = me.arrayData3[0].minimo;
                        me.fechamax = me.arrayData3[0].maximo;
                        me.porcentaje = respuesta.datosdevolucion[0].porcentaje;  
                        //me.servicio = 0; //respuesta.deudaservicio[0].lp
                        me.retencion = plugin.redondeo_valor((me.totaldevolucion*me.porcentaje)/100,2)
                        me.aporteminimo = respuesta.datosdevolucion[0].aporteminimo;
                        me.valido = respuesta.datosdevolucion[0].valido;  
                        // console.log(me.valido, me.aporteminimo, ca);  //console
                        // console.log(respuesta.salida);
                        if (me.valido===1) {
                            if (me.aporteminimo > ca) {
                                swal({
                                title: "No cumple la cantidad minima de aportes que es: " + me.aporteminimo,
                                text: "Aportes realizados: " + ca,
                                type: "warning",
                                showCancelButton: false,
                                showConfirmButton: true,                                                
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                allowEnterKey: false,                   
                                });
                                me.cerrarModal('modaDevolucion')
                            }
                            else {
                                swal({
                                title: "Aportes Calculados: ", 
                                text:  + respuesta.salida['periodo'].length,
                                type: "warning",
                                showCancelButton: false,
                                showConfirmButton: true,                    
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                allowEnterKey: false,                   
                                });                            
                            }
                        }
                        else {
                            swal({
                            title: "Aportes Calculados: ",
                            text:  + respuesta.salida['periodo'].length,
                            type: "warning",
                            showCancelButton: false,
                            showConfirmButton: true,                    
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,                   
                            });                            
                        }
                    }                                           
                })
                .catch(function (error) {
                    console.log(error);
                });                      
                
            },

            reportevertical(papeletanum,reporte_vertical){
                var url=reporte_vertical + papeletanum; 
                this.abrirVentanaModalURL(url,"Reporte Vertical",800,700);            
            },

            reporteAportesDevueltos(papeletanum,reporte_devolucion,tipodevolucion){
                var tipodev=0;
                if(tipodevolucion=='obligatorios')
                    tipodev=1;
                else    
                    tipodev=2;

                var url=reporte_devolucion + papeletanum+'&tipodevolucion='+tipodev; 
                this.abrirVentanaModalURL(url,"Reporte Faltantes",800,700);		            
            },

            abrirVentanaModalURL(url, title, w, h) { 
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
            },

            getRutasReports (){ 
                let me=this;
                var url= '/daa_devolucion/reportes';
                axios.get(url).then(function (response) {
                     var respuesta= response.data; ;
                    // me.reporte_horizontal = respuesta.REP_APO_HORIZONTAL; 
                    // me.reporte_vertical = respuesta.REP_APO_VERTICAL; 
                    // me.reporte_abono=respuesta.REP_APO_ABONO;
                    // me.reporte_debito=respuesta.REP_APO_DEBITO;
                    // me.reporte_faltantes=respuesta.REP_APO_FALTANTES;
                    me.dev_socio=respuesta.REP_DEV_SOCIOS; 

                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            regdaaro(id){
                let me = this;
                
                swal({
                    title: "Registrando los Datos",
                    allowOutsideClick: () => false,
                    allowEscapeKey:() => false, 
                    onOpen: function() { 
                        swal.showLoading() ;
                    }}).catch(error => { swal.showValidationError( 'Request failed: ${error}' )  }); 
 
				axios.post('/daa_devolucion/regdaaro',{
                     'idsocio': this.socio_id,   
                     'idtipodevolucion':this.idtipodevolucion, 
                     'idestudiomatematico':this.idestudiomatematico, 
                     'totalparcial':this.totaldevolucion,
                     'totaldevdescuento':this.totaldevdescuento,
                     'totalaporte':this.totalaporte,
                     'rendimiento':this.rendimiento,
                     'anualnominal':this.anualnominal,
                     'capitalizacionmensual':this.capitalizacionmensual,
                     'cobro':this.cobro,   
                     'idmodulo':this.idmodulo,

                     'emergencia': this.emergencia,
                     'servicio': this.servicio,
                     'regular': this.regular,
                     'retencion': this.retencion,
                     'juridica': this.juridica,

                    'idperfilcuentamaestro': this.idperfilcuentamaestro,
                    'tipodocumento': this.tipodocumento,
                    'numerodocumento': this.numerodocumento,
                    'glosa': this.glosa,

                     //'daaro': this.daaro,
                     
                }).then(function (response) { 
                    console.log(response.data.id);
                    
					swal("¡Se registro los datos correctamente!", "", "success").then((result) => {
					     me.listarSocio(1,'','nombre');  
                      })  
                }).catch(function (error) {
                    console.log(error); 
					 swal("¡Error al registrar los datos!", error.message, "error");
                });
                this.cerrarModal(id);
            },

            editdaaro(id){              
                let me = this;
                
                swal({
                    title: "Editar los Datos",
                    allowOutsideClick: () => false,
                    allowEscapeKey:() => false, 
                    onOpen: function() { 
                        swal.showLoading() ;
                    }}).catch(error => { swal.showValidationError( 'Request failed: ${error}' )  }); 
 
				axios.put('/daa_devolucion/editdaaro',{
                    'iddevolucion':this.iddevolucion, 
                    'idsocio': this.socio_id,   
                    'idtipodevolucion':this.idtipodevolucion, 
                    'idestudiomatematico':this.idestudiomatematico, 
                    'totalparcial':this.totaldevolucion,
                    'totaldevdescuento':this.totaldevdescuento,
                    'totalaporte':this.totalaporte,
                    'rendimiento':this.rendimiento,
                    'anualnominal':this.anualnominal,
                    'capitalizacionmensual':this.capitalizacionmensual,

                    'emergencia': this.emergencia,
                    'servicio': this.servicio,
                    'regular': this.regular,
                    'retencion': this.retencion,
                    'juridica': this.juridica,

                    'idperfilcuentamaestro': this.idperfilcuentamaestro,
                    'tipodocumento': this.tipodocumento,
                    'numerodocumento': this.numerodocumento,
                    'glosa': this.glosa,
                    'idmodulo':this.idmodulo,
                     
                }).then(function (response) { 
                    console.log(response.data.id);
                    
					swal("¡Se registro los datos correctamente!", "", "success").then((result) => {
					     me.listarSocio(1,'','nombre');  
                      })  
                }).catch(function (error) {
                    console.log(error); 
					 swal("¡Error al registrar los datos!", error.message, "error");
                });
                this.cerrarModal(id);
            },
                                                                                      
            //metodo agregado para la validacion
             validateBeforeSubmit() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                    return;
                    }
                    return;
                });
            },

            listarSocio(page,buscar){
                let me=this; 
                var url= '/daa_devolucion/pre_listasociodev?page=' + page + '&buscar='+ buscar;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    //console.log(respuesta.data);
                    me.arraysocio = respuesta.socios.data;  //console.log(me.arraysocio);
                    me.pagination= respuesta.pagination;
                })
                .catch(function (response) {
                    console.log(response);
                });
            }, 

            cambiarPagina(page,buscar){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarSocio(page,buscar);
            },
            
            redondeo(value, precision) {
                return plugin.redondeo_valor(value, precision);  
            },
                
            cerrarModal(id){
             this.classModal.closeModal(id)
                this.tituloModal='';                
                this.anualnominal='';
                this.capitalizacionmensual='';
                this.idtipodevolucion='';
                this.idestudiomatematico='';
                this.emergencia=0; 
                this.regular=0;
                this.juridica=0;
                this.servicio=0;
                this.total_descuentos=0;
                this.retencion=0;
            },

            revertir(idsocio, estado) {
                let me=this;
                swal({
                    title: 'Esta seguro de revertir esta devolucion?',
                    html:   '<div class="form-group row "> <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label" for="text-input">Obs.: </label>' +
                            '<div class="col-md-9"> <div class="input-group"> <textarea  id="observacionswal" class="form-control"  placeholder="Observaciones" autofocus step="any"></textarea></div> </div></div>',
                    type: 'warning',
                    showConfirmButton: true,
                    allowOutsideClick: () => false,
                    allowEscapeKey: () => false,
                    showCancelButton: true,
                    showLoaderOnConfirm: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!',
                    cancelButtonText: 'Cancelar',
                    buttonsStyling: true,
                    reverseButtons: true,
                    onOpen:function(){
                        swal.disableConfirmButton();
                        $('#observacionswal').on("input",function(){
                            var oInput = this.value;
                            if(oInput!='')
                            {
                                swal.enableConfirmButton();
                                me.observado=oInput;
                            }
                            else swal.disableConfirmButton();
                        });
                    },
                
                }).then((result) => {
                    if (result.value) {
                        let me = this;
                        axios.put('/daa_devolucion/revertir',{
                            'idsocio': idsocio,
                            'estado':estado,
                            'observado':me.observado
                        }).then(function (response) {
                            me.listarSocio(1,'')
                            swal(
                                'Revertido!',
                                'El registro ha revertido correctamente.',
                                'success'
                            )
                            me.cerrarModal();
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }                 
                }) 
            },

            abrirModalPre(id,accion,data){                 
                let me = this;
                var url= '/daa_devolucion/devregistrado?idsocio=' + data.idsocio;                
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.fechamaximoaporte = (respuesta.aportemaximo[0].maximo);
                    me.fechaminimoaporte = (respuesta.aporteminimo[0].minimo); 
                    if (respuesta.registrado.length>0) {  //si hay datos registrados
                        me.arrayRegistrado = respuesta.registrado[0];  
                        me.aux=1;
                        if (respuesta.registrado[0].estado === 1) { //en caso de que sea validado
                            swal({
                            title: "Validado Contabilidad",
                            text: "No se puede Editar",
                            type: "warning",
                            showCancelButton: false,
                            showConfirmButton: true,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                            });                                                          
                        }
                        else { 
                            me.abrirModal(id,accion,data,me.aux,me.arrayRegistrado,me.fechaminimoaporte,me.fechamaximoaporte,0);    
                        }
                    }
                    else { //si no hay datos registrados
                        me.aux=0; 
                        me.abrirModal(id,accion,data,me.aux,me.arrayRegistrado,me.fechaminimoaporte,me.fechamaximoaporte,0);
                    }                     
                })
                .catch(function (response) {
                    console.log(response);
                });
            },

            abrirModalPreJub(id,accion,data){ 
                let me = this;

                var url= '/daa_devolucion/validaconta?idsocio=' + data.idsocio;                
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta = response.data; 
                    if (respuesta.v_jubilado.length!=0) {  //si hay datos registrados
                                                
                        swal({
                        title: "Validado Contabilidad Jubilado",
                        text: "No se puede Editar",
                        type: "warning",
                        showCancelButton: false,
                        showConfirmButton: true,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                        });                                                                                  
                        
                    }
                    else { //si no hay datos registrados en jubilacion 
                        if (data.cantaportesjubilado === 0) {
                            swal({
                            title: "No cumple la cantidad minima de aportes para Jubilacion",
                            type: "warning",
                            showCancelButton: false,
                            showConfirmButton: true,                                                
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,                   
                            });
                            this.cerrarModal(id)
                        }
                        // verificar si tiene registrado cumplimiento para proceder a jubilacion
                        else {
                            var url= '/daa_devolucion/devregistrado?idsocio=' + data.idsocio;                
                            axios.get(url).then(function (response) {
                                //console.log(response);
                                var respuesta= response.data;                    
                                if (respuesta.registrado.length===0) {  //si no se registro como cumplimiento
                                    swal({
                                        title: "Devolucion por Cumplimiento no Registrado",
                                        text: "Primero debera registrar Devolucion por Cumplimiento",
                                        type: "warning",
                                        showCancelButton: false,
                                        showConfirmButton: true,
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                        allowEnterKey: false,
                                    });                            
                                }
                                else if (respuesta.registrado.length>0 && respuesta.registrado[0].estado!=1) {
                                    swal({
                                        title: "Devolucion por Cumplimiento aun no Validado",
                                        text: "Primero debera ser validado",
                                        type: "warning",
                                        showCancelButton: false,
                                        showConfirmButton: true,
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                        allowEnterKey: false,
                                    });
                                }
                                else {  //entra para registrar o editar
                                    var url= '/daa_devolucion/devregistradojub?idsocio=' + data.idsocio;                
                                    axios.get(url).then(function (response) {
                                        //console.log(response.data);
                                        var respuesta= response.data; 
                                        me.fechamaximoaporte = (respuesta.aportemaximo[0].maximo);
                                        me.fechaminimoaporte = (respuesta.aporteminimo[0].minimo);
                                        if (respuesta.registrado[0].total===1) {  // si cobro jubilacion
                                            me.arrayRegistrado = respuesta.registrado[0]; 
                                            me.aux=1;
                                        }
                                        else { me.aux=0; } 
                                        me.abrirModal(id,accion,data,me.aux,me.arrayRegistrado,me.fechaminimoaporte,me.fechamaximoaporte,1);
                                    })
                                    .catch(function (response) {
                                        console.log(response);
                                    });
                                }                                         
                            })
                            .catch(function (response) {
                                console.log(response);
                            });                    
                        }                        
                    }                     
                })
                .catch(function (response) {
                    console.log(response);
                });                                
            },

            abrirModal(id,accion,data,existe,dataDevolucion,fechaminimoaporte,fechamaximoaporte,tipo){  
                //verificamos si ya fue registrado antes para obtener lo guardado
                let me = this;                                                
                this.classModal.openModal(id);
                this.devoluciontipo = tipo; 
                this.totaldevolucion=0;
                this.errors.clear();
                if (existe===1) {  //se va a editar                 
                    switch(accion){
                        case 'devolucion':
                        {
                            swal({
                            title: "Devolucion registrado",
                            text: "Se editaran los datos ",// + dataDevolucion.iddevolucion,
                            type: "warning",
                            showCancelButton: false,
                            showConfirmButton: true,                    
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,                   
                            });

                            this.iddevolucion=dataDevolucion.iddevolucion; 
                            this.anualnominal=me.arrayRegistrado.anualnominal;
                            this.capitalizacionmensual=me.arrayRegistrado.capitalizacionmensual;
                            this.idtipodevolucion=me.arrayRegistrado.idtipodevolucion;
                            this.idestudiomatematico=me.arrayRegistrado.idestudiomatematico;
                            this.emergencia=me.arrayRegistrado.emergencia; 
                            this.regular=me.arrayRegistrado.regular;
                            this.juridica=me.arrayRegistrado.juridica;
                            this.servicio=me.arrayRegistrado.servicio;
                            //this.daaro=me.arrayRegistrado.daaro;
                            this.retencion=me.arrayRegistrado.retencion;
                            //this.totaldevdescuento=me.arrayRegistrado.totaldevolucion;
                            //this.totaldevolucion=me.arrayRegistrado.totalparcial;

                            this.idperfilcuentamaestro=me.arrayRegistrado.idperfilcuentamaestro;
                            this.tipodocumento=me.arrayRegistrado.tipodocumento;
                            this.numerodocumento=me.arrayRegistrado.numerodocumento;
                            this.glosa=me.arrayRegistrado.glosa;
                            
                            if (tipo===0) {
                                this.tituloModal = 'Editar Devoluciones Cumplimiento';
                            }
                            else {
                                this.tituloModal = 'Editar Devoluciones Jubilacion';
                            }
                            this.tipoAccion = 1;
                            this.socio_id = data.idsocio;
                            this.nombre = data.nombre;
                            this.apaterno = data.apaterno;
                            this.amaterno = data.amaterno;
                            this.grado = data.nomgrado;
                            this.fotosocio = data.rutafoto;
                            this.ci = data.ci;
                            this.numpapeleta = data.numpapeleta;
                            this.idgrado = data.idgrado;
                            this.ext = data.abrvdep; 
                            this.aporteobligado = data.cantaportesobligado;
                            this.aportejubilado = data.cantaportesjubilado; 
                            this.total_aportes = data.cantaportes;
                            this.total_bs = data.totalaportes;
                            this.primer_aporte = fechaminimoaporte;
                            this.ultimo_aporte = fechamaximoaporte;
                                                            
                            break;
                        }
                    }
                }
                else {  //cuando se registra
                    switch(accion){
                        case 'devolucion':
                        {
                            this.anualnominal=7.7;
                            this.capitalizacionmensual=12;
                            this.idtipodevolucion='';
                            this.idestudiomatematico='';
                            this.emergencia=0; 
                            this.regular=0;
                            this.juridica=0;
                            this.servicio=0;
                            this.total_descuentos=0;
                            this.retencion=0;
                            this.totaldevolucion='';
                            this.tipodocumento='';
                            this.numerodocumento='';
                            this.glosa='';
                            if (tipo===0) {
                                this.tituloModal = 'Registro Devoluciones Cumplimiento';
                                this.cobro = 1; //indicando que cobro cumplimiento
                            }
                            else {
                                this.tituloModal = 'Registro Devoluciones Jubilacion';
                                this.cobro = 2; //indicando que cobro jubilacion
                            }                            
                            this.tipoAccion = 1;
                            this.socio_id=data.idsocio;
                            this.nombre = data.nombre;
                            this.apaterno = data.apaterno;
                            this.amaterno = data.amaterno;
                            this.grado = data.nomgrado;
                            this.fotosocio = data.rutafoto;
                            this.ci = data.ci;
                            this.numpapeleta = data.numpapeleta;
                            this.idgrado = data.idgrado;
                            this.ext = data.abrvdep; 
                            this.aporteobligado = data.cantaportesobligado;
                            this.aportejubilado = data.cantaportesjubilado; 
                            this.tipojubilado = data.tipojubilado; 
                            this.tipoobligado = data.tipoobligado; 
                            this.total_aportes = data.cantaportes;
                            this.total_bs = data.totalaportes;
                            this.primer_aporte = fechaminimoaporte;
                            this.ultimo_aporte = fechamaximoaporte;                            
                                                            
                            break;
                        }
                    }
                }
                       
                this.selectTipodevolucion(tipo,data.cantaportes);  
                this.selectEstudiomatematico();
                this.selectPerfilcuenta();
            }
        },
        mounted() {
            this.listarSocio(1,this.buscar);
            this.getRutasReports();
            
            this.classModal=new plugin.Modals();
            this.classModal.addModal('modaDevolucion');
            this.classModal.addModal('detalleDevolucion');
            this.classModal.addModal('factorview');                       
        }
    }
</script>
<style> 
    .titulopaso{
     /*font-weight: bold;   */
      color: dodgerblue;
      text-align: center;
      font-size: 16px;
    }   
    .titulo{
        font-weight: bold; 
      /*  text-align: right;*/
        margin-bottom: 2px;
    }
    .contentTitulo{
        margin-bottom: 2px;
    }
    .tituloP{
        text-align: center;
        font-size: x-large; 
    }
    
    .ocultaricono{
        display: none !important;
    }
     
    .ocultarBotonsiguiente{
        display: none !important; 
    }
    .factordeterminantecss{
        background-color: #ff0000b8 !important;
        color:white !important;
        font-weight: bold;
    font-size: 23px;
    -webkit-transition: width .35s ease-in-out;
  transition: width .35s ease-in-out;
    }
     .factordeterminantecss2{
        background-color: #4dbd7478 !important; 
        color: black !important;
    font-weight: bold;
    font-size: 23px;
    }
    .fotosocioimg{
        border:#efefef 2px solid;
        filter:drop-shadow(4px 4px 5px #333);
        width:76%;
		max-width: 100%;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-style: italic;
    }
    .fotosociomini{
	     display: inline-block;
        border:#efefef 1px solid;
        filter:drop-shadow(1px 0px 2px #333);
        width:40px;
    }
    .formpp{
    background-color:transparent !important;
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
