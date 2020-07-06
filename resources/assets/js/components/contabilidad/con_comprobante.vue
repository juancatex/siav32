<template>
    <main>  
        <div style="display:none" id="divcomprobante">    
            <div class="container-fluid" style="padding-right: 0px;padding-left: 0px;">
                <div class="card">
                    <!-- <form class="form-horizontal" @submit.prevent="validateBeforeSubmit" autocomplete="off"> -->
                    <div class="card-header" style="padding-top: 5px;padding-bottom: 0px;height: 51px;">
                        <div class="form-group row">
                            <div class="col-md-7">
                                <h4>Comprobante de: {{ titulo | ucase }}</h4>
                            </div>
                            <div class="input-group col-md-5">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-info">Fecha Transaccion</span>
                                </div>
                                <input  type="date" 
                                        v-model="fechatransaccion" 
                                        class="form-control" 
                                        :max="fechaactual"
                                        v-validate.initial="'required'"
                                        name="Fecha Transaccion"
                                        style="margin-top: 0px;font-size: 1rem;" focus>
                                
                                <div class="input-group-append ">
                                    <span class="input-group-text bg-info">
                                    <i class="cui-calendar"></i>
                                    </span>
                                </div>
                                <span class="text-error">{{ errors.first('Fecha Transaccion') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="padding-bottom: 5px;">
                        <div class="form-group row" style="margin-bottom: 5px;">
                            <div class="col-md-3 padding5">
                                <strong><label>Tipo Documento:</label></strong>
                                <select 
                                    :class="{'form-control': true, 'is-invalid selecterror': errors.has('Tipo Documento')}"  
                                    v-validate.initial="'required'" 
                                    v-model="tipodocumento"
                                    name="Tipo Documento">
                                    <option value="" selected="selected" disabled>Seleccione...</option>
                                    <option v-for="tipodocumento in arrayDocumento" :key="tipodocumento.iddocumento" :value="tipodocumento.nomdocumento" v-text="tipodocumento.nomdocumento"></option>
                                </select> 
                                <span class="text-error">{{ errors.first('Tipo Documento') }}</span><br />
                            </div>
                            <div class="col-md-3 padding5">
                                 <strong><label>Num. Documento:</label></strong>
                                <input  type="text" 
                                        v-model="numdocumento" 
                                        :class="{'form-control': true, 'is-invalid inputerror': errors.has('Numero Documento')}" 
                                        v-validate.initial="'required'"
                                        placeholder="Num. Documento"
                                        name="Numero Documento"
                                        autofocus>
                                <span class="text-error">{{ errors.first('Numero Documento')}}</span>    
                            </div>
                            <div class="col-md-6 padding5">
                                <strong><label>Glosa:</label></strong>
                                <textarea 
                                    :class="{'form-control': true, 'is-invalid textareaerror': errors.has('glosa')}"  
                                    rows="4" 
                                    v-model="glosa"
                                    name="glosa"
                                    v-validate.initial="'required'" >
                                </textarea>
                                <span class="text-error">{{ errors.first('glosa')}}</span> 
                            </div>
                            
                        </div>
                        <hr style="margin-top: 5px;margin-bottom: 5px;">
                         <div class="form-group row" style="margin-bottom: 5px;">
                            <h4 class="col-md-8">Asiento Contable</h4>
                            <div class="col-md-4" >  <!-- v-if="(accion=='editar' && silibrocompra==1)"  para ocultar icono de libro de compras-->
                                <button type="button" @click="abrirmodalCompras()" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Abrir libro de compras">
                                    <i class="icon-basket"></i>
                                </button> 
                            </div>
                        </div>
                            <div class="" >
                                <div class="row">
                                    <div class="bg-info text-white border border-white ancho44c" >
                                        <strong style="padding-left: 20px;"><label>CUENTA</label></strong>
                                    </div>
                                    <div class="bg-info text-white border border-white ancho24c" >
                                        <strong><label>DESCRIPCION</label></strong>
                                    </div>
                                    <div class="bg-info text-white border border-white ancho12c" >
                                        <strong><label>DEBE</label></strong>
                                    </div>
                                    <div class="bg-info text-white border border-white ancho12c" >
                                        <strong><label>HABER</label></strong>
                                    </div>
                                    <div class="bg-info text-white border border-white ancho8c"  style="text-align:center">
                                        <strong><label> + </label></strong>
                                    </div>
                                </div>
                                <div id="contenidoValue">
                                <div v-for="(rowcuentas, index) in rowcuentas" :id="'filaRow'+index" :filaindex="index" :key="index" class="row filacontable">
                                    <template v-if="!borrador">
                                        <div class="border ancho44" >
                                        <Ajaxselect v-if="limpiarajax"
                                            class="border-0"  
                                            ruta="/con_cuentas/selectBuscarcuenta2?buscar=" @found="cuentas" @cleaning="clean"
                                            resp_ruta="cuentas"
                                            labels="cuentas"
                                            placeholder="Ingrese texto" 
                                            idtabla="idcuenta"
                                            :keyIn="index"
                                            :clearable='true'>
                                        </Ajaxselect>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <div class="border ancho44" >
                                        <Ajaxselect
                                            class="border-0"  
                                            ruta="/con_cuentas/selectBuscarcuenta2?buscar=" @found="cuentas" @cleaning="clean"
                                            resp_ruta="cuentas"
                                            labels="cuentas"
                                            placeholder="Ingrese texto" 
                                            idtabla="idcuenta"
                                            :keyIn="index"
                                            :id="rowcuentas.idcuenta"
                                            :clearable='true'>
                                        </Ajaxselect>
                                        </div>
                                    </template>
                                    
                                    <div class="border ancho24" >
                                        <input  id="input1" inputvalued="1"
                                                v-model="rowcuentas.documento" 
                                                class="inputnext form-control border-0 input-text2" 
                                                type="text" >
                                    </div>
                                    <template v-if="rowcuentas.idcuenta==lc && acumulado13!=0">
                                        <div class="ancho12 border"  style="text-align:right">
                                            <vue-numeric   id="input2" inputvalued="2"
                                                :disabled="rowcuentas.haber!=0"
                                                readOnly
                                                class="inputnext form-control input-importe border-0"
                                                separator="," 
                                                v-model="rowcuentas.debe"
                                                v-bind:precision="2"
                                                v-on:focus="selectAll">
                                            </vue-numeric>
                                        </div>
                                    </template>
                                    <template v-else-if="rowcuentas.idcuenta==lc && acumulado13==0">
                                        <div class="ancho12 border" >
                                            <vue-numeric   id="input2" inputvalued="2"
                                                disabled
                                                class="inputnext form-control input-importe border-0"
                                                separator="," 
                                                v-model="rowcuentas.debe"
                                                v-bind:precision="2"
                                                v-on:focus="selectAll">
                                            </vue-numeric>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <div class="ancho12 border" >
                                            <vue-numeric  id="input2" inputvalued="2"
                                                :disabled="rowcuentas.haber!=0"
                                                class="inputnext form-control input-importe border-0"
                                                separator="," 
                                                v-model="rowcuentas.debe"
                                                v-bind:precision="2"
                                                v-on:focus="selectAll">
                                            </vue-numeric>
                                        </div>
                                    </template>
                                    <div class="ancho12 border" >
                                        <vue-numeric  id="input3" inputvalued="3"
                                            :disabled="rowcuentas.debe!=0"
                                            class="inputnext form-control input-importe border-0"
                                            separator="," 
                                            v-model="rowcuentas.haber"
                                            v-bind:precision="2"
                                            v-on:focus="selectAll">
                                        </vue-numeric>
                                    </div>
                                    <div v-if="recorrerowcuentas-1==index" class="ancho8 border" style="text-align:center">
                                        <button @click="deleterowcuentas(index)" class="btn btn-danger botonpadding" >
                                                Borrar
                                        </button>
                                    </div>
                                </div>
                                </div>
                                <div class="row" style="text-align:right;">
                                    <div class="bg-info text-white ancho68" >
                                        <strong><label >Total es:</label></strong>
                                    </div>
                                    <div class="ancho12 border" style="padding-right: 5px;">
                                        <strong for="debe" >{{debe | currency }}</strong>
                                    </div>
                                    <div class="ancho12 border" style="padding-right: 5px;">
                                        <strong for="haber" >{{haber |currency}}</strong>
                                    </div>
                                    <div class="ancho8 border" style="text-align:center">
                                        <button  @click="addrowcuentas" class="btn btn-success botonpadding">
                                            Nuevo
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <hr style="margin-bottom: 5px;">
                        <div v-if="acumulado13!=0 && acumulado87!=0 && confacturas" class="row table-warning rounded" style="padding-top: 5px;">
                            <div class="col-md-3">
                                <strong>Datos Credito fiscal:&nbsp;</strong><label># de Facturas:&nbsp;</label><strong v-text="checkusarfactura.length"></strong>
                            </div>                            
                            <div class="col-md-3">
                                <label style="margin-bottom: 5px;">Acumulado 13%:&nbsp;<strong v-text="acumulado13+' Bs.'"></strong></label>    
                            </div>
                            <div class="col-md-3">
                                <label style="margin-bottom: 5px;">Acumulado 87%:&nbsp;<strong v-text="acumulado87+' Bs.'"></strong></label>
                            </div>
                            <div class="col-md-3">
                                <label style="margin-bottom: 5px;">Suma Total:&nbsp;<strong v-text="sumafac+' Bs.'"></strong></label>
                            </div>
                            
                        </div>
                        <div class="row" v-if="menorfacturas" style="text-align:right">
                            <label class="text-error">El Monto del Comprobante no debe ser menor a la suma del Total de las Facturas</label>
                        </div>

                        </div>
                        <div class="card-footer">
                        <div style="text-align:right">
                            <button :disabled ="!isallcomplete || !isComplete || !isComplete2 || menorfacturas"   
                                v-if="tipoAccion==1" class="btn btn-warning" @click="registrarAsiento(true)">Guardar Borrador</button>
                            <button :disabled ="!isallcomplete || !isComplete || !isComplete2"   
                                v-if="tipoAccion==2" class="btn btn-warning" @click="actualizarAsiento()" >Guardar Edicion</button>
                        </div>
                    </div>
                    </div>
                    
                </div>
            </div>
        
        <!-- MODAL Libro de compras --> 
        <div class="modal fade " tabindex="-1"  role="dialog"   aria-hidden="true" id="librocompras"  data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    
                    <div class="modal-header" style="padding-bottom: 5px;padding-top: 5px;">
                        <h4 class="modal-title" v-text="tituloModallibro" ></h4>
                        <div>
                        <div class="form-group row" style="margin-top: 10px">
                            <strong class="form-control-label" style="margin-bottom: 0px;margin-top: 8px; padding-right: 10px;padding-left: 10px;">Filial</strong>
                            <div>
                                <select v-model="filialselected"  class="form-control" @change="selectLibrocompras()">
                                    <option v-for="filial in arrayFilial" v-bind:key="filial.idfilial" :value="filial.idfilial" v-text="filial.nommunicipio"></option>
                                </select>
                            </div>
                            <strong class="form-control-label" style="margin-bottom: 0px;margin-top: 8px; padding-right: 10px;padding-left: 10px;" >Mes:</strong>
                            <div>
                                <select v-model="messelected"  class="form-control" @change="selectLibrocompras()">
                                
                                <option v-for="mesarray in arraymes" v-bind:key="mesarray.value" :value="mesarray.value" v-text="mesarray.text"></option>
                                </select>   
                            </div>
                            <strong class="form-control-label" style="margin-bottom: 0px;margin-top: 8px; padding-right: 10px;padding-left: 10px;">Año:</strong>
                            <div>
                                <select v-model="anioselected" class="form-control">
                                    <option :value="anio" v-text="anio" selected></option>
                                    <option :value="anio-1" v-text="anio-1"></option>
                                </select>
                            </div>
                            <div>
                                <template v-if="cierremes" >
                                    <button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Mes Cerrado" style="margin-left: 10px;">
                                        <i class="cui-lock-locked"></i>
                                    </button>
                                </template>
                                <template v-else>
                                    <button type="button" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Mes Abierto" style="margin-left: 10px;">
                                        <i class="cui-lock-unlocked"></i>
                                    </button>
                                </template>
                            </div>
                            <button type="button" class="close" @click="cerrarModalCompras()" aria-label="Close">
                                    <span aria-hidden="true">x</span>    
                            </button>
                        </div>
                        </div>
                    </div>
                    <div class="modal-body1">
                        <div style="height: 200px; overflow: scroll;">
                            <table class="table table-hover table-sm overflow-auto">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Nº</th>
                                        <th style="width:90px">Fecha Factura</th>                     
                                        <th>Nit - Razon Social</th>
                                        <th>Nº Factura</th>
                                        <th>Registrado Por</th>
                                        <th>Importe</th>
                                        <th>Estado</th>
                                        <th>*</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="librocompras in arrayLibrocompras" :key="librocompras.idlibrocompra"  v-bind:class="[(librocompras.idasientomaestro==idasientomaestro)&&(librocompras.validadoconta==0)  ? 'table-warning' :true , false]">
                                        <td v-text="librocompras.numeracion"></td>
                                        <td v-text="librocompras.fecha_factura"></td>
                                        <td v-text="librocompras.nit + ' ' +  librocompras.nomproveedor"></td>
                                        <td v-text="librocompras.numfactura" style="text-align:right"></td>
                                        <td v-text="librocompras.username"></td>
                                        <td v-text="librocompras.importe+' Bs.'" style="text-align:right"></td>
                                        <td><template v-if="librocompras.estado==1">
                                                <span class="badge badge-success">Validado</span>
                                            </template>
                                            <template v-if="librocompras.estado==5">
                                                <span class="badge badge-warning">Borrador</span>
                                            </template>
                                            <template v-if="librocompras.idasientomaestro==null && librocompras.lote==null">
                                                <span class="badge badge-danger">Sin Compr.</span>
                                            </template>
                                        </td>
                                        <td style="text-align:right">
                                            <template v-if="!librocompras.idasientomaestro || (librocompras.estado==5 && librocompras.idasientomaestro==asientomaestro.idasientomaestro)">
                                                <input type="checkbox" class="form-check-input"  v-model="checkusarfactura" :value="librocompras.idlibrocompra" @change="sumar13()"  :checked="verchecked(librocompras.idlibrocompra)">
                                            </template>
                                        </td>
                                    </tr>                                
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-8">
                                <label for="idproveedor"><strong>Nit - Proveedor:</strong></label>
                                <div class="form-group row">
                                    <div class="col-md-11" style="padding-right: 5px;">
                                        <Ajaxselect  v-if="clearSelected"
                                            ruta="/alm_proveedor/selectProveedor?buscar=" @found="proveedores" @cleaning="cleanproveedores"
                                            resp_ruta="proveedores"
                                            labels="nit_proveedor"
                                            placeholder="Ingrese texto" 
                                            idtabla="idproveedor"
                                            :clearable='true'>
                                        </Ajaxselect>
                                    </div>
                                    <div class="col-md-1" style="padding-left: 0px;">
                                        <button type="button" class="btn btn-primary" @click="abrirModalProveedores()" aria-label="Close">
                                            <i class="icon-plus"></i>
                                        </button>
                                    </div>
                                    </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="Fecha Factura"><strong>Fecha Factura:</strong></label>
                                <input type="date" 
                                        v-model="fechafactura"
                                        class="form-control formu-entrada" 
                                        :max="fechafinal"
                                        :min="fechainicial"
                                        v-validate.initial="'required'"
                                        name="Fechar Factura"
                                        :disabled='!ismescerrado'>
                                    <span class="text-error">{{ errors.first('Fecha Factura')}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <strong>Nº Factura:</strong>
                                <input class="form-control input-importe" type="number" 
                                        v-model="numfactura" 
                                        v-on:focus="selectAll"
                                        name="Nº Factura"
                                        v-validate.initial="'required'"
                                        :disabled='!ismescerrado'>
                                <span class="text-error">{{ errors.first('Nº Factura')}}</span>
                            </div>
                            <div class="form-group col-md-4">
                                <strong>Nº Autorizacion:</strong>
                                <input class="form-control" type="text" style="text-align:right"
                                        v-model="numautorizacion" 
                                        v-on:focus="selectAll"
                                        v-validate.initial="'required'"
                                        name='Nº autorizacion'
                                        :disabled='!ismescerrado'>
                                <span class="text-error">{{ errors.first('Nº autorizacion')}}</span>
                            </div>
                            <div class="form-group col-md-4">
                                <strong>Codigo Control:</strong>
                                <input class="form-control" type="text" 
                                        v-model="codcontrol" 
                                        v-mask="'NN-NN-NN-NN-NN'"
                                        v-on:focus="selectAll"
                                        onKeyUp="this.value=this.value.toUpperCase();"
                                        :disabled='!ismescerrado'
                                        placeholder="00-00-00-00-00">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <strong>Importe Total:</strong>
                                <vue-numeric  
                                                class="form-control input-importe"
                                                currency="Bs." 
                                                separator="," 
                                                v-model="importetotal"
                                                v-bind:precision="2"
                                                v-on:focus="selectAll"
                                                :disabled='!ismescerrado'>
                                </vue-numeric>
                                <template v-if="importetotal==0">
                                    <div>
                                        <span class="text-error">Debe Ingresar Importe</span>        
                                    </div>
                                </template>
                            </div>
                            <div class="form-group col-md-4">
                                <strong>No sujeto a credito Fiscal:</strong>
                                <vue-numeric  
                                                class="form-control input-importe"
                                                currency="Bs." 
                                                separator="," 
                                                v-model="nocreditofiscal"
                                                v-bind:precision="2"
                                                v-on:focus="selectAll"
                                                :disabled='!ismescerrado'>
                                </vue-numeric>
                            </div>
                            <div class="form-group col-md-4">
                                <strong>Descuentos - Rebajas:</strong>
                                <vue-numeric  
                                                class="form-control input-importe"
                                                currency="Bs." 
                                                separator="," 
                                                v-model="descuentos"
                                                v-bind:precision="2"
                                                v-on:focus="selectAll"
                                                :disabled='!ismescerrado'>
                                </vue-numeric>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <strong>Detalle de factura:</strong>
                            </div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" v-model="detalle">
                                <span class="text-error" v-if="!detalle">Debe Ingresar el detalle de la factura</span>
                            </div>
                            
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between" style="padding-bottom: 0px;padding-top: 0px;">
                        <div>
                            <p style="margin-bottom: 5px;">Acumulado 13%:&nbsp;<strong v-text="acumulado13+' Bs.'"></strong></p>
                            <p style="margin-bottom: 5px;">Acumulado 87%:&nbsp;<strong v-text="acumulado87+' Bs.'"></strong></p>
                        </div>
                        <div>
                            <button type="button"  class="btn btn-secondary" v-if="!sifacturas" @click="cerrarModalCompras()">Cerrar</button>
                            <button type="button"  class="btn btn-success" v-if="sifacturas" @click="cerrarModalCompras()">Registrar 13%</button>

                            <button :disabled ="!iscompletelibro" type="submit" v-if="tipoAccionlibro==1" class="btn btn-primary" @click="registroLibrocompra()">Registrar Factura</button>
                            <button :disabled = "errors.any()" type="button" v-if="tipoAccionlibro==2" class="btn btn-primary" @click="actualizarLibrocompra()">Actualizar</button>
                        </div>
                    </div>
                </div>                    
            </div>                
        </div> 
                <!-- MODAL PROVEEDOR MODAL PROVEEDOR MODAL PROVEEDOR MODAL PROVEEDOR MODAL PROVEEDOR  -->
        <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="proveedor"  data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-primary">
                <div class="modal-content animated fadeIn">
                    <div class="modal-header">
                        <h4 class="modal-title">Registro de Proveedores</h4>
                        <button class="close" @click="cerrarmodalproveedor()">x</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-sm-7">
                                <strong>Nombre Proveedor:</strong>
                                <input type="text" class="form-control" v-model="nomproveedor">
                                <span class="text-error" v-if="nomproveedor==''">Proveedor no debe estar Vacio</span>
                            </div>
                            <div class="form-group col-sm-5">
                                <strong>NIT:</strong>
                                <input type="text" class="form-control" v-model="nit">
                                <span class="text-error" v-if="nit==''">NIT no debe estar Vacio</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-7">
                                <strong>Dirección:</strong>
                                <input type="text" class="form-control" v-model="direccion">
                            </div>
                            <div class="form-group col-sm-5">
                                <strong>Teléfono:</strong>
                                <input type="text" class="form-control" v-model="telefono">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-7">
                                <strong>Nombre Contacto:</strong>
                                <input type="text" class="form-control" v-model="nomcontacto">
                            </div>
                            <div class="form-group col-sm-5">
                                <strong>Celular:</strong>
                                <input type="text" class="form-control" v-model="celular">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarmodalproveedor()">Cerrar</button>
                        <button :disabled="!iscompleteproveedor" class="btn btn-primary" @click="storeProveedor()">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- fin modal add proveedor -->
         <!-- modal conciliacion bancaria -->
        <div class="modal fade " tabindex="-1"  role="dialog"   aria-hidden="true" id="conciliacionbancaria"  data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModalConciliacion" ></h4><br/>
                        
                        <button type="button" class="close" @click="cerrarModalConciliacion()" aria-label="Close">
                            <span  aria-hidden="true">x</span>    
                        </button>
                    </div>
                    <div class="modal-body">
                        <div style="height: 200px; overflow: scroll;">
                        <label>Registrar Cheque en Cuenta: <strong v-text="nomcuenta"></strong></label><br />
                        <strong>Historial:</strong>

                            <table class="table table-hover table-sm overflow-auto">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nº de cheque</th>
                                    <th>Fecha Registro</th>
                                    <th>Portador</th>
                                    <th>Importe</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="conciliacion in arrayConciliacion" :key="conciliacion.idmovimiento"  :class="[conciliacion.externo ? 'table-info' : '']">
                                    <td v-text="conciliacion.fecha"></td>
                                    <td v-text="conciliacion.numdocumento"></td>
                                    <td v-text="conciliacion.nomempleado"></td>
                                    <td v-text="conciliacion.importe + ' Bs.'" style="text-align:right"></td>
                                </tr>                                
                            </tbody>
                        </table>
                        </div>
                        <hr>
                        <strong>Registro de Cheque:</strong>
                        <div class="row">
                            <div class="form-group col-md-6" style="margin-bottom: 5px;">
                                <div class="form-group row">
                                    <strong class="col-md-4 col-form-label" for="text-input">Portador:</strong>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" v-model="asignadoa" v-on:focus="selectAll">
                                        <span class="text-error" v-if="asignadoa==''">Ingrese el Nombre del Portador</span>
                                    </div>
                                </div>
                            </div>
                                
                            <div class="form-group col-md-6" style="margin-bottom: 5px;">
                                <div class="form-group row">
                                    <strong class="col-md-4 col-form-label" for="text-input">Nº Cheque:</strong>
                                    <div class="col-md-8">
                                        <input type="text" 
                                        v-model="numcheque"
                                        class="form-control"  
                                        v-validate.initial="'required'"
                                        name="Nº cheque"
                                        style="text-align:right"
                                        v-on:focus="selectAll">
                                    <span class="text-error">{{ errors.first('Nº cheque')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row col-md-12">
                                <strong class="col-md-2 col-form-label" for="text-input">Importe:</strong>
                                <div class="col-md-4">
                                    <vue-numeric  
                                        class="form-control input-importe"
                                        separator="," 
                                        v-model="monto"
                                        v-bind:precision="2"
                                        v-on:focus="selectAll">
                                    </vue-numeric>
                                    <span class="text-error" v-if="monto==0">Debe Ingresar el Monto del Cheque</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button"   class="btn btn-secondary" @click="cerrarModalConciliacion()">Cerrar</button>
                        <button :disabled ="!iscompletecheque" type="submit" class="btn btn-primary" @click="registrocheque()">Registrar Cheque</button>
                    </div>
                </div>                    
            </div>                
        </div> 
        <!-- fin modal conciliacion bancaria -->
    </main>
</template>

<script>

import VueCurrencyFilter from 'vue-currency-filter'
Vue.use(VueCurrencyFilter,
{
  symbol : '',
  thousandsSeparator: '.',
  fractionCount: 2,
  fractionSeparator: ',',
  symbolPosition: 'front',
  symbolSpacing: true
})
import VueMask from 'v-mask'
Vue.use(VueMask);
export default {
    data(){ 
        return {
        limpiarajax:1,
        idmodulo:0,
        titulo:'',
        divCompPrincipal:1,
        idcuenta:[],
        idsubcuenta:[],
        classModal:null,
        tipoAccion : 1,
        fechaactual:'',
        fechatransaccion:'',
        idtipocomprobante:'',
        tipodocumento:'',
        numdocumento:'',
        glosa:'',
        arrayDocumento:[],
        accion:'',
        arrayPrecio:[],
        indice:'',
        contador:0,
        idasientomaestro:'',
        arrayLibrocompras:[],
        tituloModallibro:'',
        fechafactura:'',
        idproveedor:'',
        numfactura:'',
        numautorizacion:'',
        codcontrol:'',
        importetotal:0,
        nocreditofiscal:0,
        descuentos:0,
        tipoAccionlibro:1,
        
        arrayLibroCuentas:[],
        lv:0,
        lc:0,
        //librocomprasOk:false,
        reslote:0,
        silibrocompra:0,
        checkusarfactura: [],
        idfacturas:[],
        sifacturas:false,
        acumulado13:0,
        acumulado13_return:0,
        acumulado87:0,
        lote:0,
        clearSelected:1,
        
        arraymes:[{text:"Enero",value:1},
                    {text:"Febrero",value:2},
                    {text:"Marzo",value:3},
                    {text:"Abril",value:4},
                    {text:"Mayo",value:5},
                    {text:"Junio",value:6},
                    {text:"Julio",value:7},
                    {text:"Agosto",value:8},
                    {text:"Septiembre",value:9},
                    {text:"Octubre",value:10},
                    {text:"Noviembre",value:11},
                    {text:"Diciembre",value:12}
                ],
        anioselected:'',
        messelected:'',
        mes:'',
        anio:'',
        cierremes:0,
        fechainicial:'',
        fechafinal:'',
        rowcuentas: [] ,
        debe:0,
        haber:0,
        asientomaestro:[],
        borrador:0,
        sumafac:0,
        confacturas:true,
        ////////////////modal proveedores /////////////////
        nomproveedor:'',
        direccion:'',
        nomcontacto:'',
        nit:'',
        telefono:'',
        celular:'',
        //////////////modal conciliaciop///////////////
        cuentasconciliacion:[],
        tituloModalConciliacion:'',
        numcheque:'',
        ccuentaopen:0,
        arrayConciliacion:[],
        idmovimiento:0,
        numcheque:'',
        monto:0,
        asignadoa:'',
        arrayConciliacionExterna:[],
        nomcuenta:'',

        nommunicipio:'',
        sigla:'',
        idfilial:'',
        arrayFilial:[],
        filialselected:1,
        detalle:'',
        loteverificacion:'',

      
    }
    },
    computed:{
        menorfacturas(){
            let me=this;
            if (me.debe<me.sumafac)
                return true;
            else
                return false;


        },
        iscompleteproveedor(){
            let me=this;
            var correcto=false;
            if (me.nomproveedor!='' && me.nit!='')
                correcto=true;
            return correcto;
        },
        recorrerowcuentas:function(){
            let me=this;
            var contador=0;
            me.debe=0;
            me.haber=0;
            me.confacturas=false
            me.rowcuentas.forEach(element => {
                contador++;
                me.debe=parseFloat(me.debe)+parseFloat(element.debe);
                me.debe=me.debe.toFixed(2);
                me.haber=parseFloat(me.haber)+parseFloat(element.haber);
                me.haber=me.haber.toFixed(2);
                if(element.idcuenta==me.lc)
                    me.confacturas=true;

                //console.log(me.debe+ '---'+me.haber);
                
            });

            return contador;
        
        },
        ismescerrado: function(){
            
            //console.log(this.cerrado);
            
            return !this.cierremes;
        },
        iscompletelibro(){
            let me=this;
            if(me.idproveedor.length!=0 && me.fechafactura && me.numfactura && me.numautorizacion && me.importetotal && me.detalle)
                return true;
            else
                return false

        },
        isallcomplete(){
            if(this.tipodocumento && this.numdocumento)
                return true;
            else
                return false;
        },
        isComplete(){
            var completo=false;
            let me=this;
            if(me.debe==me.haber)
            {
                if(me.debe!=0)
                {
                    completo=true;
                }
            }
            return completo
        },
        isComplete2(){
            let me=this
            var complet=true
            var cont=0;
            me.rowcuentas.forEach(element=>{
                cont++;
                //console.log(element.idcuenta);
                if(element.idcuenta=="" )// dentro del if para subcuenta -> || element.idsubcuenta==""
                {
                    complet=false;
                }
            }); 
            //console.log(cont); 
            return complet
        },
        iscompletecheque(){
            if(this.numcheque && this.nomportador!=''&& this.monto!=0)
                return true;
            else
                return false;
        },
    },
    methods:{
        verchecked(id){
            // console.log(id);
            let me=this;
            let valor=false;
            let resultado = me.checkusarfactura.find( elem => elem == id );
            if(resultado)
                valor=true;
            else
                valor= false;            
            //console.log(valor);
            return valor;
            
        },
        cargarvue(arrayvalores,valor){
            $('#divcomprobante').css('display','block');
            this.classModal=new _pl.Modals();
            this.classModal.addModal('librocompras');
            this.classModal.addModal('proveedor');
            this.classModal.addModal('conciliacionbancaria');
            this.borrador=false;
            this.resetComprobante();
            setTimeout(this.tiempo, 200); 
            this.selectfilial();
            this.getCConciliacion();
            this.fechahoy();
            this.selectLibroCuenta();
            this.rowcuentas=[];
            this.idmodulo=arrayvalores['idmodulo'];
            this.titulo=arrayvalores['titulo'];
            this.idtipocomprobante=arrayvalores['idtipocomprobante'];
            this.selectDocumento();
            this.reslote=0;
            //console.log(valor);
            switch (valor) {
                case 'nuevo':
                    this.tipoAccion=1;
                    this.rowcuentas= [{   idcuenta:'',
                            idsubcuenta: '',
                            moneda:'bs',
                            documento:'',
                            debe:0,
                            haber:0
                             }] ;
                    
                    this.accion='nuevo'
                break;
                case 'editar':
                    this.asientomaestro=arrayvalores['idasientomaestro'];
                    //console.log(this.asientomaestro.idasientomaestro);
                    
                    this.numdocumento=this.asientomaestro.numdocumento;
                    this.tipodocumento=this.asientomaestro.tipodocumento;
                    this.glosa=this.asientomaestro.glosa;
                    this.fechatransaccion=this.asientomaestro.fecharegistro.split(' ')[0];
                    this.idasientomaestro=this.asientomaestro.idasientomaestro;
                    this.selectasientodetalles(this.idasientomaestro,'editar');
                    this.tipoAccion=2;
                    this.accion='editar';
                break;
                case 'copiar':
                   // console.log('switch copiar');
                    
                    this.asientomaestro=arrayvalores['idasientomaestro'];
                    this.idasientomaestro=this.asientomaestro.idasientomaestro;
                    this.selectasientodetalles(this.idasientomaestro,'copiar');
                    this.tipoAccion=1;
                    this.accion='copiar';
                break;
            }
        },
        cerrarvue(){
            $('#divcomprobante').css('display','none');
            this.silibrocompra=0;
        },
        selectfilial(){
                let me=this;
                var url='/fil_filial/selectFiliales';
                axios.get(url).then(function(response){
                    me.arrayFilial=response.data.filiales;
                    //console.log(me.arrayFilial);
                    
                });
            },
        getCConciliacion(){
            let me=this;
            var conciliacion;
            var url= '/con_config/cuentasconciliacion';
            //console.log(url);
            axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    respuesta.forEach(element => {
                            me.cuentasconciliacion.push(element.valor);
                        //console.log(element.valor);
                    });
                    //console.log(me.cuentasconciliacion);
                    //console.info( me.cuentasconciliacion.includes( 12 ) ); // true
            })
            .catch(function (error) {
                console.log(error);
            });
            //return conciliacion
        },
        abrirModalConciliacion(idcuenta,indice='',nomcuenta){
            let me=this;
            me.indice=0;
            var cuenta_id=idcuenta;
            me.nomcuenta=nomcuenta;
            me.tituloModalConciliacion='¿Registrar Nº de cheque?';
            me.selectConciliacion(cuenta_id);
            me.indice=indice;
            //me.nomcuenta=
            /* if(){
                me.tituloModalConciliacion='Registro de cheques';
            }
            else{

            } */

            
            me.classModal.openModal('conciliacionbancaria');
        },
        cerrarModalConciliacion(){
            let me=this;
            me.classModal.closeModal('conciliacionbancaria');
            me.rowcuentas[me.indice].haber=me.monto;
            me.rowcuentas[me.indice].documento='Cheque Nº '+me.numcheque;
            if(me.ccuentaopen==1)
                me.classModal.openModal('ccuenta');
            
            me.ccuentaopen=0;
            me.monto=0;
            me.asignadoa='';
            me.numcheque='';

        },
        selectConciliacion(idcuenta){
            let me=this;
            var url= '/con_conciliacion/selectconciliacion?idcuenta='+idcuenta;
            //console.log(url);
            
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                //console.log(response);
                me.arrayConciliacion = respuesta.conciliacion;
                me.arrayConciliacionExterna=respuesta.conciliacionexterna;
                //console.log(me.arrayConciliacion);
                me.arrayConciliacion = me.arrayConciliacion.concat(me.arrayConciliacionExterna);
                //console.log(me.arrayConciliacion);
                me.arrayConciliacion.sort(function (a, b) {
                    if (a.numdocumento > b.numdocumento) {
                        return -1;
                    }
                    if (a.numdocumento < b.numdocumento) {
                        return 1;
                    }
                    // a must be equal to b
                    return 0;
                });
                //console.log(me.arrayConciliacion);
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        registrocheque(){
            let me=this;
            me.idmovimiento=0;
            axios.post('/con_conciliacion/registrar',{
                'idcuenta': me.idcuenta[0].idcuenta,
                'numdocumento': me.numcheque,
                'importe':me.monto,
                'tipocargo':'h',
                'nomportador':me.asignadoa,
            }).then(function (response) {
                //console.log(response);
                me.idmovimiento=response.data;
                        'Registrado correctamente',
                me.cerrarModalConciliacion('conciliacion');
                me.classModal.openModal('ccuenta');
            }).catch(function (error) {
                console.log(error);
            });
        },
        selectasientodetalles(idmaestro,tipo){
            let me=this;
            var url= '/con_asientodetalle/selectasientodetalle?idasientomaestro=' + idmaestro;
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                var optdebe=0;
                var opthaber=0;
                var optdocumento='';
                if(tipo=='editar')
    	        {
                    //console.log(tipo);
                    for (let index = 0; index < respuesta.asientodetalles.length; index++) {
                        const element = respuesta.asientodetalles[index];
                        //console.log(element.nomcuenta);
                        if(element.debe!=null)
                            optdebe=element.debe;
                        else
                            optdebe=0;
                        if(element.haber!=null)
                            opthaber=element.haber;
                        else
                            opthaber=0;
                        if(element.documento!=null)
                            optdocumento=element.documento;
                        else
                            optdocumento='';
                        if(element.idcuenta==me.lc)
                            me.silibrocompra=1;

                        if(index==0)
                        {
                        me.rowcuentas= [{ idcuenta:element.idcuenta,
                                    idsubcuenta: '',
                                    moneda:'bs',
                                    documento:optdocumento,
                                    debe:optdebe,
                                    haber:opthaber
                                    }] ;
                        }
                        else
                        {
                            me.addrowcuentas();
                            me.rowcuentas[index].idcuenta=element.idcuenta;              
                            me.rowcuentas[index].idsubcuenta='';
                            me.rowcuentas[index].moneda=element.moneda;
                            me.rowcuentas[index].documento=optdocumento;
                            me.rowcuentas[index].debe=optdebe;
                            me.rowcuentas[index].haber=opthaber;
                        }                    
                    }
                }
                else {
                    if(tipo=='copiar')
                    {
                        //console.log(tipo);
                        
                        for (let index = 0; index < respuesta.asientodetalles.length; index++) {
                            const element = respuesta.asientodetalles[index];
                            //console.log(element.nomcuenta);
                            if(index==0)
                            {
                            me.rowcuentas= [{ idcuenta:element.idcuenta,
                                        idsubcuenta: '',
                                        moneda:'bs',
                                        documento:optdocumento,
                                        debe:0,
                                        haber:0
                                        }] ;
                            }
                            else
                            {
                                me.addrowcuentas();
                                me.rowcuentas[index].idcuenta=element.idcuenta;              
                                me.rowcuentas[index].idsubcuenta='';
                                me.rowcuentas[index].moneda=element.moneda;
                                me.rowcuentas[index].documento='';
                                me.rowcuentas[index].debe=0;
                                me.rowcuentas[index].haber=0;
                            }                    
                        }
                    }
                }
                me.borrador=true; 
            })
            .catch(function (response) {
                console.log(response);
            });
        
        },
        deleterowcuentas:function(index) {
            this.rowcuentas.splice(index, 1);
            if(index===0)
                this.addrowcuentas();
        },
        addrowcuentas() {
            this.rowcuentas.push({   idcuenta:'',
                            idsubcuenta: '',
                            moneda:'bs',
                            documento:'',
                            debe:0,
                            haber:0
                             });
        },
        tiempo(){
        this.clearSelected=1;
        this.borrador=1;
        },
        tiempoajax(){
            this.limpiarajax=1;
        },
        proveedores(proveedores){
            this.idproveedor=[];
            // this.arrayCuenta.push({cuentita:cuentas.idcuenta})         
            //console.log(idproveedores);
            for (const key in proveedores) {
                if (proveedores.hasOwnProperty(key)) {
                    const element = proveedores[key];
                    //console.log(element);
                    this.idproveedor.push(element);
                }
            }
            //console.log(this.idproveedor);
        },
        cleanproveedores(){
            this.idproveedor=[];
            this.idproveedorrespuesta=0;
        //console.log('clean')
        
        },
        cuentas(cuentas,index){ 
            let me=this;
            var cont=0;
            me.idcuenta=[];
            me.idcuenta.push({"idcuenta":cuentas.idcuenta,"indice":index,"nomcuenta":cuentas.nomcuenta});
            me.rowcuentas.forEach(element => {
                if(element.idcuenta==me.lc)
                {
                    cont++;
                }
            });
            if(cont==0)
            {
                me.rowcuentas[index].idcuenta=cuentas.idcuenta;  
                cont++;
                cuentas='';  
                if(me.idcuenta[0].idcuenta==me.lc)
                {   
                    me.abrirmodalCompras(me.idcuenta[0].indice);
                }
            }
            else if(me.idcuenta[0].idcuenta==me.lc)
            {
                swal('Cuenta de Credito Fiscal Ya Seleccionada','','error');
            }
            else
            {
                me.rowcuentas[index].idcuenta=cuentas.idcuenta;  
                cuentas=''; 
            }
            if(me.cuentasconciliacion.includes( me.idcuenta[0].idcuenta))
            {
                me.abrirModalConciliacion(me.idcuenta[0].idcuenta,index,me.idcuenta[0].nomcuenta);
            }
        },
        clean(a){
        this.rowcuentas[a].idcuenta='';  
        },
        selectDocumento(){
            let me=this;
            var url= '/par_documento/selectDocumento?idmodulo='+this.idmodulo;
            //console.log(url);
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayDocumento = respuesta.documentos;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        selectLibroCuenta(){
            let me=this;
            var url= '/con_config/cuentaslibros';
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayLibroCuentas = respuesta;
                //console.log(me.arrayLibroCuentas[0]['codigo']);
                me.arrayLibroCuentas.forEach(element => {
                    if(element.codigo=='LV')                            
                        me.lv=element.valor;
                    else 
                        if (element.codigo=='LC')
                            me.lc=element.valor;
                });
                //console.log("lv->"+me.lv+"-"+"lc->"+me.lc);
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        selectAll: function (event) {
    
            setTimeout(function () {
                event.target.select()
            }, 0)
        },           
        cerrarModalCompras(){
            let me=this;
            //console.log(me.acumulado13);
            var sw=0;
            me.rowcuentas.forEach((element,index) => {
                if(element.idcuenta==me.lc)
                {
                    me.indice=index;
                    sw=1;
                }
            });
            if(sw==1)
                me.rowcuentas[me.indice].debe=me.acumulado13;
            else
            {
                me.addrowcuentas();
                me.indice=me.rowcuentas.length-1;
                me.rowcuentas[me.indice].idcuenta=me.lc;
                me.rowcuentas[me.indice].debe=me.acumulado13;
            }

            me.classModal.closeModal('librocompras'); 
            me.classModal.openModal('comprobantecontable')
            me.sifacturas=false;
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
        diaminmax(){
            var primerDiaMes = new Date(this.anioselected, this.messelected - 1  , 1);
            var ultimoDiaMes = new Date(this.anioselected, this.messelected , 0);
            /* console.log(this.anioselected+ ' aniop');
            console.log(this.messelected + 'mes'); */

            var mm=this.messelected;
            //console.log(mm);
            
            
            if(mm<10) 
                mm='0'+mm;

            var dd=primerDiaMes.getDate()
            if(dd<10)
                dd='0'+dd; 
            
            this.fechainicial=this.anioselected+'-'+mm+'-'+dd;
            this.fechafinal=this.anioselected+'-'+mm+'-'+ultimoDiaMes.getDate();
            if(this.fechafinal > this.fechaactual)
                    this.fechafinal=this.fechaactual;
                
            //console.log(this.fechafinal);
            

            /* console.log(this.fechainicial);
            console.log(this.fechafinal);
                */
            
        },
        fechahoy(){
            var hoy = new Date();
            var dd = hoy.getDate(); 
            var mm = hoy.getMonth();
            var aa = hoy.getFullYear();
            this.mes=mm;
            this.anio=aa;
            
            this.messelected=this.arraymes[this.mes].value;
            this.anioselected=this.anio;

            mm=mm+1;

            if(mm<10) 
                mm='0'+mm;

            if(dd<10) 
                dd='0'+dd; 
            
            
            this.fechaactual=aa+'-'+mm+'-'+dd;
            this.fechafactura=this.fechaactual;
            this.fechatransaccion=this.fechaactual;
        },
        registrarAsiento(borrador){
            let me = this;
            var validarfacturas=0;
           /*  me.acumulado13=0;
            me.acumulado87=0; */
            if(me.checkusarfactura.length>0)
            validarfacturas=1;
            axios.post('/con_asientomaestro/registrar',{
                'rowregistros':me.rowcuentas,
                'fechatransaccion':me.fechatransaccion,
                'idtipocomprobante':me.idtipocomprobante,
                'tipodocumento':me.tipodocumento,
                'numdocumento':me.numdocumento,
                'glosa':me.glosa,
                'idmodulo':me.idmodulo,
                'borrador':borrador,
                //'librocomprasok':me.librocomprasOk,
                //'reslote':me.reslote
                'idfacturas':me.checkusarfactura,
                'idmovimiento':me.idmovimiento,
                'validarfacturas':validarfacturas,

            }).then(function (response) {
                //console.log(response);
                me.limpiarajax=0;
                setTimeout(me.tiempoajax,100);  
                var residasientomaestro=response.data;
                swal(
                        'Registrado Correctamente'
                    )   
                               
                me.resetComprobante();
                me.$emit('cerrarmanual');
                //me.listrcomprobantes();
                //me.$refs.comprobanteprincipal.listacomprobantes();
            }).catch(function (error) {
                console.log(error);
            });
        },
        actualizarAsiento(){
            let me = this;
            axios.post('/con_asientomaestro/registrar',{
                'rowregistros':me.rowcuentas,
                'fechatransaccion':me.fechatransaccion,
                'idtipocomprobante':me.idtipocomprobante,
                'tipodocumento':me.tipodocumento,
                'numdocumento':me.numdocumento,
                'glosa':me.glosa,
                'idmodulo':me.idmodulo,
                'borrador':true,
                'idasientomaestro':me.idasientomaestro,
                'reslote':me.reslote,
                'idfacturas':me.checkusarfactura,
            }).then(function (response) {
                var residasientomaestro=response.data;
                //console.log(response.data)
                swal(
                        'Editado Correctamente'
                    )  
                me.resetComprobante(); 
                me.$emit('cerrarmanual');                 
                //me.listacomprobantes();
                
            }).catch(function (error) {
                console.log(error);
            });
        },
        resetComprobante(){
            this.limpiarajax=0;
            setTimeout(this.tiempoajax,100);
            this.rowcuentas= [{   idcuenta:'',
                        idsubcuenta: '',
                        moneda:'bs',
                        documento:'',
                        debe:'',
                        haber:''
                            }] 
            this.fechatransaccion=this.fechaactual;
            this.idtipocomprobante='';
            this.tipodocumento='';
            this.numdocumento='';
            this.glosa='';
            this.idasientomaestro='',
            this.acumulado13=0;
            this.idfacturas=[];
        },
        abrirmodalCompras(indice=''){
            
            let me=this;
            me.indice=0;
            //me.selectProveedor();
            
            me.checkusarfactura=[];
            me.selectLibrocompras();
            me.clearSelected=0;
            setTimeout(me.tiempo, 50); 
            //me.sumar13();
            
            //me.classModal.closeModal('comprobantecontable');
            me.classModal.openModal('librocompras');
            me.tituloModallibro = 'Libro de Compras';
            //me.$refs.comboproveedor.clearSelection(); 
            if(me.mes+1==me.messelected)
            {    
                me.fechafactura=me.fechaactual;
                //me.fechafinal=me.fechaactual;
            }
            else   
                me.fechafactura=me.fechafinal;

            me.numfactura='';
            me.numautorizacion='';
            me.codcontrol='';
            me.importetotal=0;
            me.nocreditofiscal=0;
            me.descuentos=0;
            me.indice=indice;
        },
        selectLibrocompras(){
            let me=this;
            me.diaminmax();
            if(me.mes+1==me.messelected)
            {    
                me.fechafactura=me.fechaactual;
                //me.fechafinal=me.fechaactual;
            }
            else   
                me.fechafactura=me.fechafinal;

            var url= '/con_librocompras?modal=1&mes='+me.messelected+'&anio='+me.anioselected+'&idfilial='+me.filialselected;
            //console.log(url);
            
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                //console.log(respuesta);
                me.arrayLibrocompras = respuesta.librocompras;
                me.cierremes=respuesta.cierremes;
                //const result = words.filter(word => word.length > 6);
                var regreso=me.arrayLibrocompras.filter(elem => elem.idasientomaestro==me.asientomaestro.idasientomaestro);
                regreso.forEach(element => {
                    me.checkusarfactura.push(element.idlibrocompra);
                    me.reslote=element.lote;
                });
                
               // console.log(me.loteverificacion);
                me.sumar13();
                
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        registroLibrocompra(){
            let me = this;
            //console.log(me.idproveedor[0]);
            var total=me.importetotal-me.nocreditofiscal-me.descuentos;
            me.corregircodcontrol()
            var _13porciento=parseFloat((total * 0.13)).toFixed(2);
            var _87porciento=(parseFloat(total)-parseFloat(_13porciento)).toFixed(2);
            
            axios.post('/con_librocompras/registrar',{
                'fechafactura':me.fechafactura,
                'idproveedor':me.idproveedor[0],
                'numfactura':me.numfactura,
                'numautorizacion':me.numautorizacion,
                'codcontrol':me.codcontrol,
                'importetotal':me.importetotal,
                'nocreditofiscal':me.nocreditofiscal,
                'descuentos':me.descuentos,
                '_13porciento':_13porciento,
                '_87porciento':_87porciento,
                'accion':me.accion,
                'idasientomaestro':me.idasientomaestro,
                'lote':me.lote,
                'idfilial':me.filialselected,
                'detalle':me.detalle
            }).then(function (response) {
                //me.reslote=response;
                //console.log(me.reslote)
                swal(
                        'Registrado Correctamente'
                    )                    
                // me.listarAsientoMaestro(1,me.criterio,me.borradorcheck);
                me.selectLibrocompras();
                //me.$refs.comboproveedor.clearSelection(); 
                me.clearSelected=0;
                setTimeout(me.tiempo, 50); 
                me.fechafactura=me.fechaactual;
                me.numfactura='';
                me.numautorizacion='';
                me.codcontrol='';
                me.importetotal=0;
                me.nocreditofiscal=0;
                me.descuentos=0;
                me.detalle='';
                //me.acumulado13=parseFloat(me.acumulado13)+parseFloat(_13porciento);
                //me.acumulado87=parseFloat(me.acumulado87)+parseFloat(_87porciento);
                //me.librocomprasOk=true;
                me.checkusarfactura=[];
                me.acumulado13=0;
                me.acumulado87=0;
                me.idfacturas=[];
                me.idproveedor=[];
                //me.cerrarModalCompras();
            }).catch(function (error) {
                console.log(error);
            });

        },
        sumar13(){
            let me=this;
            var sumacheck=0;
            var suma87=0;
            me.idfacturas=[];
            
            me.acumulado13=0;
            me.acumulado87=0;
            me.checkusarfactura.forEach(element => {
                var resultado = me.arrayLibrocompras.find( elem => elem.idlibrocompra == element );
                sumacheck=parseFloat(sumacheck)+parseFloat(resultado.credfiscal);
                suma87=parseFloat(suma87)+parseFloat(resultado.importe);
                //me.idfacturas.push(me.arrayLibrocompras[element].idlibrocompra);
                
            });
            me.acumulado13=parseFloat(me.acumulado13)+parseFloat(sumacheck.toFixed(2));
            me.acumulado13=me.acumulado13.toFixed(2);
            me.sumafac=suma87;
            me.acumulado87=parseFloat(me.sumafac)-parseFloat(me.acumulado13);
            me.acumulado87=me.acumulado87.toFixed(2);
           //console.log(me.acumulado13);
            
            if(me.acumulado13>0)
                me.sifacturas=true;
            else
                me.sifacturas=false;
        },
        verificarfactura(idasientomaestro)
        {
            let me=this;
            var url= '/con_librocompras/verificarfactura?idasientomaestro='+idasientomaestro;
            axios.get(url).then(function (response) {
                var respuesta= response.data;
               // console.log(response);
                
                
                if(respuesta.success)
                {
                    //console.log('entra success');
                    
                    me.sifacturas=respuesta;
                }
                //console.log(respuesta)  ;
                //me.arrayLibrocompras = respuesta.librocompras;
                
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        corregircodcontrol(){
                let me=this;
                if(me.codcontrol!='')
                {
                    var corregido;
                    var tamanio;
                    var letrafinal= me.codcontrol.substr(-1,1)
                    if(letrafinal=='-')
                    {
                        tamanio=me.codcontrol.length;
                        corregido=me.codcontrol.substr(0,tamanio-1);
                         me.codcontrol=corregido;
                    }
                }
                    
                
                //return letrafinal;
            },
            cerrarmodalproveedor(){
                let me =this;
                me.nomproveedor='';
                me.direccion='';
                me.nomcontacto='';
                me.nit='';
                me.telefono='';
                me.celular='';
                me.classModal.closeModal('proveedor'); 
                me.classModal.openModal('librocompras');

            },
            storeProveedor(){
                var url='/alm_proveedor/storeProveedor';
                let me=this;
                axios.post(url,{
                    'nomproveedor':this.nomproveedor,
                    'nomcontacto':this.nomcontacto,
                    'nit':this.nit,
                    'direccion':this.direccion,
                    'telefono':this.telefono,
                    'celular':this.celular
                }).then(function(){
                    swal('Adicionado correctamente','','success');
                    me.cerrarmodalproveedor();
                });
            },
            abrirModalProveedores(){
                let me=this;
                me.classModal.closeModal('librocompras'); 
                me.classModal.openModal('proveedor');
            },
    },
    filters: {
        ucase: function (value) {
            if (!value) return ''
            value = value.toString()
            return value.toUpperCase(); 
        }
    } 
    }
</script> 
<style>
.ancho30{
        width: 30%;
}.ancho12{
    width: 12%;
}.ancho10{
    width: 10%;
}
.ancho30c{
    text-align: center;
    width: 30%;
}
.ancho60c{
    text-align: center;
    width: 60%;
}.ancho12c{
    text-align: center;
    width: 12%;
    padding-bottom: 5px;
    padding-bottom: 1px;
}.ancho10c{
    text-align: center;
    width: 10%;
    
}
.ancho44c{
    width:44%;
    padding-right: 15px;
}
.ancho44{
    width:44%;
}
.ancho24c{
    width:24%;
    padding-right: 15px;
}
.ancho68{
    width:68%;
    padding-right: 15px;
}
.ancho24{
    width: 24%;
}
.ancho12{
    width:12%;
    padding-bottom: 5px;
    padding-bottom: 1px;
}
.ancho8{
    width:8%;
}
.ancho70{
    width:70%;
    padding-right: 15px;
}
.ancho12c{
    text-align: center;
    width:12%;
}
.ancho8c{
    text-align: center;
    width:8%;
}
.ancho20c{
    text-align: center;
    width: 20%;
    
}
.botonpadding{
    padding-top: 2px;
    padding-bottom: 2px;
    
}
.ancho6c{
    text-align: center;
    width:6%;
}
.ancho6{
    width:6%;
}
.padding5{
    margin-bottom: 5px;
    padding-left: 5px;
    padding-right: 5px;

}

.headerpadding{   
padding-top: 5px;
padding-bottom: 5px;
}
.input-importe{
    text-align: right;
}

</style>
