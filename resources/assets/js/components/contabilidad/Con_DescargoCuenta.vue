<template>
    <main>  
        <div style="display:none" id="divdescargo">    
            <div class="container-fluid" style="padding-right: 0px;padding-left: 0px;">
                <div class="card" style="padding-bottom: 5px;padding-top: 5px;">
                    <!-- <form class="form-horizontal" @submit.prevent="validateBeforeSubmit" autocomplete="off"> -->
                    <div class="card-header" style="padding-top: 5px;padding-bottom: 0px;height: 51px;">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <h4>Descargo de Cargo de Cuenta</h4>
                            </div>
                            <div class="input-group col-md-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-info">Fecha Transaccion:</span>
                                </div>
                                <input  type="date" 
                                        v-model="fechatransaccion" 
                                        class="form-control" 
                                        :min="fecha_solicitud"
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
                    <div class="card-body" style="padding-bottom: 5px; padding-top: 5px;">
                        <div class="form-group row" style="margin-bottom:5px">
                            <div class="col-md-2 pt-1" >
                                <strong>Tipo Comprobante:&nbsp;&nbsp;</strong>
                            </div>
                            <div class="col-md-4">
                                    <select class="form-control " style="background-color: burlywood; font-size: 1rem; padding-top: 0.2rem; color:black"
                                            v-model="idtipocomprobante"
                                            v-validate.initial="'required'"
                                            name="tipocomprobante">
                                        
                                        <option v-for="tipocomprobante in arrayTipocomprobante" :key="tipocomprobante.idtipocomprobante" :value="tipocomprobante.idtipocomprobante" v-text="tipocomprobante.nomtipocomprobante"></option>
                                    </select>  
                                
                            </div>
                            <div class="col-md-2 pt-1" >
                                <label for="checkbox"><strong>Excedente?</strong> &nbsp;&nbsp;</label>
                                <input class="form-check-input" type="checkbox" id="checkbox" v-model="excedente" style="margin-left: 0.25rem;" @click="resetexcedente()">
                            </div>
                            <div class="col-md-4 " v-if="excedente">
                                <div class="form-group row">
                                    <label for="montoexcedente" class="pt-1"><strong>Monto Total Bs.:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong> </label>
                                    <vue-numeric  
                                                class="inputnext form-control input-importe" style="width: 150px; font-size: 1.2rem; background: burlywood;color:black"
                                                separator="," 
                                                v-model="montoexcedente"
                                                v-bind:precision="2"
                                                v-on:focus="selectAll"
                                                @blur="modificarsaldo">
                                            </vue-numeric>
                                </div>
                                    <span v-if="siexcedente" class="text-error">El excedente debe ser mayor al monto original</span>
                            </div>
                        </div>
                        <div class="form-group row" style="margin-bottom: 5px;">
                            <div class="col-md-6">
                                <strong>Asignado a:&nbsp;&nbsp;</strong>
                                <label  v-text="asignadoa"></label>
                            </div>
                            <div class="col-md-3">
                                <strong>Cargo: &nbsp;&nbsp;</strong>
                                <label  v-text="tipocargo"></label>
                            </div>
                            <div class="col-md-3">
                                <strong>Monto Cargo de Cuenta:&nbsp;&nbsp;</strong>
                                <label  v-text="montooriginal + ' Bs.'"></label>                                
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-9">
                                <strong>Glosa Solicitud:&nbsp;&nbsp;</strong>
                                <label v-text="glosa" style="text-align:left"></label>
                            </div>
                            <div class="col-md-3">
                                <strong>Filial: &nbsp;&nbsp;</strong>
                                <label  v-text="nommunicipio"></label>
                            </div>
                            
                        </div>
                        <hr style="margin-top: 5px;margin-bottom: 5px;">
                        <h4 class="col-md-8">Asiento Contable</h4>

                        <div class="form-group row" style="margin-bottom: 5px;">
                            <div v-if="idfilial==1" class="col-md-3 padding5">
                                <strong class="form-control-label">Repartición:</strong>
                                <select v-model="idunidad"  class="form-control" name="car" :class="{'invalido':errors.has('car')}" v-validate="'required'">
                                        <option v-for="unidad in arrayUnidades" :key="unidad.id"
                                        :value="unidad.idunidad" v-text="unidad.nomunidad"></option>
                                </select>
                            </div>
                            <div class="col-md-3 padding5">
                                <strong>Tipo Documento:</strong>
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
                                <strong>Num. Documento:</strong>
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
                                <strong>Glosa Comprobante:</strong>
                                <textarea :class="{'form-control': true, 'is-invalid textareaerror': errors.has('glosa2')}"  
                                    rows="4"
                                    v-validate.initial="'required'"
                                    v-model="glosa2"
                                    placeholder="Glosa Comprobante Contable"
                                    name="Glosa Comprobante">
                                </textarea>
                                <span class="text-error">{{ errors.first('Glosa Comprobante') }}</span> 
                            </div>
                        </div>
                        <!--  -->

                        <hr style="margin-top: 5px;margin-bottom: 5px;">
                         <div class="form-group row" style="margin-bottom: 5px;">
                            <div class="col-md-12" ><!-- v-if="(accion=='editar' && silibrocompra==1)"  para ocultar el boton  -->
                                <button type="button" @click="abrirmodalCompras()" style="float: right;" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Abrir Libro Compras">
                                    <i class="icon-basket"></i> Libro de compras
                                </button> 
                            </div>
                        </div>
                            <div id="contenglobalButton" >
                                <div class="row">
                                    <div class="bg-info text-white border border-white" :class="excedente?' col-md-4':'col-md-6'"  >
                                        <strong style="padding-left: 20px;">CUENTA</strong>
                                    </div>
                                    <div class="bg-info text-white border border-white col-md-3" >
                                        <strong>SUBUENTAS</strong>
                                    </div>
                                    <div class="bg-info text-white border border-white col-md-2" >
                                        <strong>DEBE</strong>
                                    </div>
                                    <div class="bg-info text-white border border-white col-md-2" v-if="excedente" >
                                        <strong><label>HABER</label></strong>
                                    </div>
                                    <div class="bg-info text-white border border-white col-md-1"  style="text-align:center">
                                        <strong> + </strong>
                                    </div>
                                </div>
                                <div id="contenidoValue">
                                <div v-for="(rowcuentas, index) in rowcuentas" :id="'filaRow'+index" :filaindex="index" :key="index" class="row filacontable">
                                    <template >
                                        <div :class="excedente?'border col-md-4':'border col-md-6'"   >
                                        <Ajaxselect v-if="limpiarajax" :ref="'ajaxselect'+index"
                                            class="border-0 "  
                                            ruta="/con_cuentas/selectBuscarcuenta2?buscar=" @found="cuentas" @cleaning="clean" @next="e=>{e()}"
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
                                    <div class="border col-md-3" >
                                        <!-- <input  id="input1" inputvalued="1"
                                                v-model="rowcuentas.documento" 
                                                class="inputnext form-control border-0 input-text2" 
                                                type="text" > -->
                                        <button v-if="rowcuentas.idcuenta!=lc" type="button" @click="abrirModalSubcuentas(index)" style="float: right;" class="btn btn-success btn-sm" :disabled="rowcuentas.idcuenta==''?true:false" data-toggle="tooltip" data-placement="top" title="Seleccionar Socios">
                                            <i class="icon-people"></i> Subcuentas
                                        </button> 
                                    </div>
                                    <template v-if="rowcuentas.idcuenta==lc && acumulado13!=0">
                                        <div class="border col-md-2" style="padding-right: 5px;padding-left: 5px;text-align:right">
                                            <vue-numeric  id="input2" inputvalued="2"
                                                disabled
                                                class="inputnext form-control input-importe border-0"
                                                separator="," 
                                                v-model="rowcuentas.debe"
                                                v-bind:precision="2"
                                                readOnly>
                                            </vue-numeric>
                                        </div>
                                    </template>
                                    <template v-else-if="rowcuentas.idcuenta==lc && acumulado13==0">
                                        <div class="border col-md-2" style="padding-right: 5px;padding-left: 5px; text-align:right">
                                            <vue-numeric  id="input2" inputvalued="2"
                                                disabled
                                                class="inputnext form-control input-importe border-0"
                                                separator="," 
                                                v-model="rowcuentas.debe"
                                                v-bind:precision="2"
                                                readOnly>
                                            </vue-numeric>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <div class="border col-md-2" style="padding-right: 5px;padding-left: 5px; text-align:right">
                                            <vue-numeric  id="input2" inputvalued="2"
                                                class="inputnext form-control input-importe border-0"
                                                separator="," 
                                                v-model="rowcuentas.debe"
                                                v-bind:precision="2"
                                                readOnly>
                                            </vue-numeric>
                                        </div>
                                    </template>
                                    <div class="border col-md-2" v-if="excedente" style="text-align:right">
                                        <vue-numeric  id="input3" inputvalued="3"
                                            :disabled="rowcuentas.debe!=0"
                                            class="inputnext form-control input-importe border-0"
                                            separator="," 
                                            v-model="rowcuentas.haber"
                                            v-bind:precision="2"
                                            readOnly>
                                        </vue-numeric>
                                    </div>
                                    <div v-if="recorrerowcuentas-1==index" class="border col-md-1" style="text-align:center">
                                        <button @click="deleterowcuentas(index)" class="btn btn-danger botonpadding" >
                                                Borrar
                                        </button>
                                    </div>
                                    <table border="1" style="border-left-width: 100px; font-size: small;">
                                        <tr v-for="(subcuenta,ind) in rowcuentas.idsubcuenta" :key="ind">
                                            <td>{{ subcuenta.subcuenta }}</td>
                                            <td>{{ subcuenta.nombre }}</td>
                                            <td>{{ subcuenta.detalle }}</td>
                                            <td style="width: 100px;text-align: right;">{{ subcuenta.subdebe | currency }}</td>
                                            <td style="width: 100px;text-align: right;">{{ subcuenta.subhaber | currency }}</td>
                                        </tr>
                                    </table>
                                </div>
                                </div>
                                <div class="row" style="text-align:right;">
                                    <div class="bg-info text-white" :class="excedente?'col-md-7':'col-md-9'" >
                                        <strong><label >Total es:</label></strong>
                                    </div>
                                    <div class="border col-md-2" style="padding-right: 5px;">
                                        <strong for="debe" >{{debe | currency }}</strong>
                                    </div>
                                    <div class="border col-md-2" style="padding-right: 5px;" v-if="excedente">
                                        <strong for="haber" >{{haber | currency }}</strong>
                                    </div>
                                    <div class="border col-md-1" style="text-align:center">
                                        <button id='botonAddAjax' @click="addrowcuentas" class="btn btn-success botonpadding">
                                            Nuevo
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <hr style="margin-bottom: 5px;">
                        <div v-if="acumulado13!=0 && acumulado87!=0 && confacturas" class="row table-warning rounded" style="padding-top: 5px; margin-bottom: 10px;">
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
                        <div class="form-group row" v-if="!excedente">
                            <div class="col-md-3">
                                <strong style="font-size: 1.2rem; ">Saldo Cargo de Cuenta:</strong>
                            </div>
                            <div :class="saldoc<0?'col-md-2 divsierror':'col-md-2 divnoerror'" style="font-size: 1.2rem;" >
                                <label :class="saldoc<0?'error':''">{{salcoc=saldocargocuenta |currency}} </label><span>&nbsp;</span>
                            </div>
                            <div class="col-md-5">
                                <span class="text-error" v-if="saldoc<0">El Saldo no debe ser menor a 0</span> 
                                <span class="text-error" v-if="menorfacturas">El Monto del Comprobante no debe ser menor a la suma del Total de las Facturas</span>
                            </div>
                            
                           <!--  <div class="col-md-2">
                                <button type="button" @click="abrirModalCompras('registrar')" class="btn btn-secondary" :disabled='!ismescerrado'>
                                    <i class="icon-plus"></i>
                                </button>
                                <button type="button" @click="abrirModalCompras('registrar')" class="btn btn-secondary" :disabled='!ismescerrado'>
                                    <i class="icon-plus"></i>
                                </button>
                            </div> -->
                        </div>
                        <div class="form-group row" v-else>
                             <div class="col-md-3">
                                <strong style="font-size: 1.2rem; ">Saldo Cargo de Cuenta:</strong>
                            </div>
                            <div :class="saldoc<0?'col-md-2 divsierror':'col-md-2 divnoerror'" style="font-size: 1.2rem;" >
                                <label :class="saldoc<0?'error':''">{{salcoc=saldocargocuenta |currency}} </label><span>&nbsp;</span>
                            </div>
                            <div class="col-md-5">
                                <span class="text-error" v-if="saldoc<0">El Saldo no debe ser menor a 0</span> 
                                <span class="text-error" v-if="menorfacturas">El Monto del Comprobante no debe ser menor a la suma del Total de las Facturas</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div style="text-align:right">
                            <button :disabled ="!isallcomplete || !isComplete2 || menorfacturas || debe==0 || !glosaisComplete || saldoc<0"   v-if="tipoAccion==1" class="btn btn-warning" @click="registrarAsiento()">Registrar Descargo</button>
                            <button :disabled ="!isallcomplete || !isComplete || !isComplete2"   v-if="tipoAccion==2" class="btn btn-warning" @click="actualizarAsiento()" >Guardar Edicion</button>
                        </div>
                    </div>
                </div>
                    
            </div>
        </div>
        
        <!-- MODAL Libro de compras --> 
        <div class="modal fade " tabindex="-1"  role="dialog"   aria-hidden="true" id="librocompras"  data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-primary modal-xl" role="document">
                <div class="modal-content">
                    
                    <div class="modal-header justify-content-between" style="padding-bottom: 5px;padding-top: 5px;">
                        <h4 class="modal-title" v-text="tituloModallibro" ></h4>
                        <div>
                        <div class="form-group row" style="margin-top: 10px">
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
                                        <th>No Cred Fiscal</th>
                                        <th>Filial</th>
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
                                        <td v-text="librocompras.impnocredfiscal+' Bs.'" style="text-align:right"></td>
                                        <td v-text="librocompras.sigla"></td>
                                        <td><template v-if="librocompras.lote && librocompras.idasientomaestro && librocompras.validadoconta">
                                                <span class="badge badge-success">Validado</span>
                                            </template>
                                            <template v-if="librocompras.lote && librocompras.idasientomaestro && !librocompras.validadoconta">
                                                <span class="badge badge-warning">No Validado</span>
                                            </template>
                                            <template v-if="librocompras.idasientomaestro==null && librocompras.lote==null">
                                                <span class="badge badge-danger">Sin Compr.</span>
                                            </template>
                                        </td>
                                        
                                        <td style="text-align:right">
                                            <template v-if="!librocompras.idasientomaestro">
                                                <input type="checkbox" class="form-check-input"  v-model="checkusarfactura" :value="librocompras.idlibrocompra" @change="sumar13()" :checked="verchecked(librocompras.idlibrocompra)" >
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
        <!-- modal subuentas -->
        <div class="modal fade " tabindex="-1"  role="dialog"   aria-hidden="true" id="addsubcuentas"  data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-primary modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModalSubcuentas" ></h4><br/>
                        <button type="button" class="close" @click="cerrarModalSubcuentas()" aria-label="Close">
                            <span  aria-hidden="true">x</span>    
                        </button>
                    </div>
                    <div class="modal-body">
                            <div class="col-12 col-form-label " style="border: 1px solid #c2cfd6 !important; border-radius: 5px;">
                                <div class="col-2 form-check-inline">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" v-model="directivo" value="4" @change="cambiaDirectivo('4')"> Ascinalss
                                    </label>
                                </div>
                                <div class="col-2 form-check-inline">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" v-model="directivo" value="1" checked @change="cambiaDirectivo('1')"> Socios
                                    </label>
                                </div>
                                <div class="col-2 form-check-inline">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" v-model="directivo" value="2" @change="cambiaDirectivo('2')">Personal
                                    </label>
                                </div>
                                <div class="col-2 form-check-inline">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" v-model="directivo" value="3" @change="cambiaDirectivo('3')">Otros
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-5" v-if="directivo=='4'">
                                    <h5 >{{ arraySubAscinalss.nombre }}</h5>
                                </div>
                                <div class="form-group col-md-5" v-else-if="directivo=='1'">
                                    <strong>Socio:</strong>
                                    <Ajaxselect  v-if="clearSelected"
                                        ruta="/rrh_empleado/selectsocios?buscar=" @found="empleados" @cleaning="cleanempleados"
                                        resp_ruta="empleados"
                                        labels="nombres"
                                        placeholder="Ingrese Texto..." 
                                        idtabla="idsocio"
                                        :id="idempleadoselected"
                                        :clearable='true'>
                                    </Ajaxselect>
                                </div>
                                <div class="form-group col-md-5" v-else-if="directivo=='2'">
                                    <strong>Personal:</strong>
                                    <Ajaxselect  v-if="clearSelected"
                                        ruta="/rrh_empleado/selectempleados2?buscar=" @found="empleados" @cleaning="cleanempleados"
                                        resp_ruta="empleados"
                                        labels="nombres"
                                        placeholder="Ingrese Texto..." 
                                        idtabla="idempleado"
                                        :id="idempleadoselected"
                                        :clearable='true'>
                                    </Ajaxselect>
                                </div>
                                <div v-else class="form-group col-md-5">
                                    <strong>Otros:</strong>
                                     <Ajaxselect  v-if="clearSelected"
                                            ruta="/alm_proveedor/selectProveedor2?buscar=" @found="empleados" @cleaning="cleanproveedores"
                                            resp_ruta="proveedores"
                                            labels="nit_proveedor"
                                            placeholder="Ingrese texto..." 
                                            idtabla="idproveedor"
                                            :clearable='true'>
                                    </Ajaxselect>
                                </div>
                                
                                <div class="form-group col-md-3">
                                    <strong>Debe:</strong>
                                    <vue-numeric  
                                        class="form-control input-importe"
                                        currency="Bs." 
                                        separator="," 
                                        v-model="subdebe"
                                        v-bind:precision="2"
                                        v-on:focus="selectAll"
                                        :disabled="subhaber!=0 || sisubhaber">
                                    </vue-numeric>
                                </div>
                                <div class="form-group col-md-3">
                                    <strong>Haber:</strong>
                                    <vue-numeric  
                                        class="form-control input-importe"
                                        currency="Bs." 
                                        separator="," 
                                        v-model="subhaber"
                                        v-bind:precision="2"
                                        v-on:focus="selectAll"
                                        :disabled="subdebe!=0 || sisubdebe || !excedente">
                                    </vue-numeric>
                                </div>
                                <div class="form-group col-md-1 pt-3">
                                    <button type="button" class="btn btn-success" @click="agregarSubcuenta()" data-toggle="tooltip" data-placement="top" title="Registrar Subcuenta" :disabled="!iscompletesubcuenta">
                                        <i class="icon-plus"></i>&nbsp;
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <strong class="col-md-1 col-form-label" for="text-input">Detalle:</strong>
                                <div class="col-md-11">
                                    <input type="text" 
                                    v-model="subdetalle"
                                    class="form-control"  
                                    name="subdetalle"
                                    style="text-align:left"
                                    v-on:focus="selectAll">
                                    <span class="text-error">{{ errors.first('subdetalle')}}</span>
                                </div>
                            </div>
                            <hr>
                            <div class="col">
                                <table border="1">
                                    <thead>
                                        <tr>
                                            <th>Subcuenta</th>
                                            <th>Nombre</th>
                                            <th>detalle</th>
                                            <th>Debe</th>
                                            <th>Haber</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(subcu,i) in subcuentas" :key=i :class="(subcu.subdebe>0 || subcu.subhaber>0)?'bc-green':''">
                                            <td>{{ subcu.subcuenta}}</td>
                                            <td>{{ subcu.nombre}}</td>
                                            <td>{{ subcu.detalle}}</td>
                                            <td><vue-numeric   inputvalued="2"
                                                    class="inputnext form-control input-importe border-0"
                                                    separator="," 
                                                    v-model="subcuentas[i].subdebe"
                                                    v-bind:precision="2"
                                                    v-on:focus="selectAll"
                                                    :disabled="sisubhaber"
                                                    >
                                                </vue-numeric>
                                                </td>
                                            <td><vue-numeric   inputvalued="2"
                                                    class="inputnext form-control input-importe border-0"
                                                    separator="," 
                                                    v-model="subcuentas[i].subhaber"
                                                    v-bind:precision="2"
                                                    v-on:focus="selectAll"
                                                    :disabled="sisubdebe || !excedente">
                                                </vue-numeric></td>
                                                
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>  
                    <div class="modal-footer">
                        <button type="button"   class="btn btn-secondary" @click="cerrarModalSubcuentas()">Cerrar</button>
                        <button type="submit" class="btn btn-primary" @click="registrarSubcuenta()">Registrar Subcuentas</button>
                    </div>
                </div>                    
            </div>                
        </div> 
        <!-- fin modal subcuentas -->
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
});
import * as plugin from '../../functions.js';
import VueMask from 'v-mask'
Vue.use(VueMask);
export default {
    data(){ return {
        limpiarajax:1,
        idmodulo:0,
        idcuenta:[],
        subcuenta:'',
        classModal:null,
        modal : 0,
        tituloModal : '',
        tipoAccion : 1,
        reporte_asiento_automatico:'',
        fechaactual:'',
        fechatransaccion:'',
        idtipocomprobante:3, //tipo de comprobante por defecto 3 traspaso
        excedente:false,
        montoexcedente:0,
        tipodocumento:'',
        numdocumento:'',
        glosa2:'',
        cuenta:[],
        arrayDocumento:[],
        accion:'',
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
        librocomprasOk:false,
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
        borrador:1,
        sumafac:0,
        confacturas:true,
        ////////////////modal proveedores /////////////////
        nomproveedor:'',
        direccion:'',
        nomcontacto:'',
        nit:'',
        telefono:'',
        celular:'',
        ///////////////////////
        idcuentahaber:0,
        asignadoa:'',
        monto:0,
        glosa:'',
        tipocargo:'',
        idasientomaestro:'',
        tituloModal:'',
        fecha_solicitud:'',
        idsolccuenta:'',
        saldoccuenta:0,
        saldoc:0,
        reporte_seg_ccuentas:'',
        reporte_automatico:'',
        reporte_documento:'',

        nommunicipio:'',
        sigla:'',
        idfilial:'',
        detalle:'',
        sidirectorio:'',
        arrayTipocomprobante:[],
        montoexcedente2:0,
        montooriginal:0,
        idunidad:3,
        arrayUnidades:[],
        subcuentas:[],
        tituloModalSubcuentas:'',
        directivo:1,
        idempleadoselected:'',
        subdebe:0,
        subhaber:0,
        idempleado:[],
        subdetalle:'',
        arraySubAscinalss:[]
      

        
    }},
    computed:{
        iscompletesubcuenta(){
            if((this.idempleado.length!=0 || this.directivo==4) && (this.subdebe !=0 || this.subhaber !=0) && this.subdetalle!='' )
                return true;

        },
        sisubdebe(){
            let me=this;
            let valor=false;
            let resultado = me.subcuentas.find( elem => elem.subdebe > 0 );
            //console.log(resultado);
            if(resultado)
                valor=true;
            else
                valor= false;            
            //console.log(valor);
            return valor;

        },
        sisubhaber(){
            let me=this;
            let valor=false;
            let resultado = me.subcuentas.find( elem => elem.subhaber > 0 );
            //console.log(resultado);
            if(resultado)
                valor=true;
            else
                valor= false;            
            //console.log(valor);
            return valor;

        },
        siexcedente(){
            let me=this;
            
            if(me.montooriginal>=me.montoexcedente)
                return true;
            else
                return false;
        },
        actualizarmonto(){
            if(me.siexcedente)
                me.montoexcedente2=me.montoexcedente-me.monto;
            return me.montoexcedente2;
        },
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
            if(me.excedente)
            {
                me.haber=me.montooriginal;
            }
            
            me.confacturas=false
            me.rowcuentas.forEach(element => {
                contador++;
                /* me.debe=Number((me.debe).toFixed(2))+Number((element.debe).toFixed(2));
                me.haber=Number((me.haber).toFixed(2))+Number((element.haber).toFixed(2));  */
                element.debe=Number(element.debe);
                element.haber=Number(element.haber);
                me.debe=Number(me.debe);
                me.haber=Number(me.haber);
                me.debe=(me.debe+element.debe).toFixed(2);
                me.haber=(me.haber+element.haber).toFixed(2); 

                /* me.debe=parseFloat(me.debe)+parseFloat(element.debe);
                me.haber=parseFloat(me.haber)+parseFloat(element.haber); */
                if(element.idcuenta==me.lc)
                    me.confacturas=true;

                //console.log(me.debe+ '---'+me.haber);
                
            });
            //console.log(contador);
            return contador;
        
        },
        saldocargocuenta(){
            let me=this;
            me.saldoc=me.monto-me.debe
            return me.saldoc

        },
        ismescerrado: function(){
            
            //console.log(this.cerrado);
            
            return !this.cierremes;
        },
        iscompletelibro(){
            let me=this;
            if(me.idproveedor.length!=0 && me.fechafactura && me.numfactura && me.numautorizacion && me.importetotal&& me.detalle)
                return true;
            else
                return false

        },
        glosaisComplete(){
            let me=this;
            var completo=false;
            if(me.glosa2!='')
                completo=true;
            
            return completo;
        },
        isallcomplete(){
            if(this.tipodocumento && this.numdocumento)
                return true;
            else
                return false;
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
       
    },
    methods:{ 
        selectAscinalss(){
            let me=this;
            var url="/con_config/selectsubcuentaascinalss";
            //console.log(Array.isArray(url));
            this.resetComprobante();
            setTimeout(this.tiempo, 200); 
            axios.get(url).then(response=>{
                    me.arraySubAscinalss=response.data.subasc;
                });
                    /*
                */
        },
        cambiaDirectivo(valor){
            //console.log(valor);
            let me=this;
            me.clearSelected=0;
            setTimeout(me.tiempo, 200); 
            me.directivo=valor;
            if (me.directivo==4) {
                //console.log('entra');
                me.idempleado=[
                        me.arraySubAscinalss[0].idconconfig,
                        me.arraySubAscinalss[0].valor,
                        me.arraySubAscinalss[0].descripcion,
                        me.arraySubAscinalss[0].valor
                        ]
            }else
                me.idempleado=[];
            },
        agregarSubcuenta(){
            let me=this;
            
            me.subcuentas.push({//indice:me.indice,
                                //idcuenta: me.rowcuentas[me.indice].idcuenta,
                                tiposubcuenta:me.directivo,
                                subcuenta:me.idempleado[3],
                                subdebe:me.subdebe,
                                subhaber:me.subhaber,
                                detalle:me.subdetalle,
                                nombre:me.idempleado[2],
                                idsubcuenta:me.idempleado[0]
                            });
            me.idempleado=[];
            me.clearSelected=0;
            setTimeout(me.tiempo, 200); 
            me.subdebe=0;
            me.subhaber=0;
            me.subdetalle='';
        },
        empleados(empleados){
            this.idempleado=[];
            for (const key in empleados) {
                if (empleados.hasOwnProperty(key)) {
                    const element = empleados[key];
                    this.idempleado.push(element);
                }
            }
        },
          cleanempleados(){
                this.idempleado=[];
            },
        abrirModalSubcuentas(indice){
            let me=this;
            //console.log(indice);
            me.tituloModalSubcuentas="Agregar Subcuentas";
            me.indice=indice;
            me.clearSelected=0;
            setTimeout(me.tiempo, 50); 
            me.classModal.openModal('addsubcuentas');

        },
        cerrarModalSubcuentas(){
            let me=this;
            me.classModal.closeModal('addsubcuentas'); 
        },
        registrarSubcuenta(){
            let me=this;
            let valor=0;
            let sumad=0;
            let sumah=0;
           
            me.rowcuentas[me.indice].idsubcuenta=[];

            me.subcuentas.forEach((element,index) => {
                if(element.subdebe!=0 || element.subhaber!=0)
                {
                    sumad=sumad+element.subdebe;
                    sumah=sumah+element.subhaber;

                    me.rowcuentas[me.indice].idsubcuenta.push({
                                    indice:me.indice,
                                    idcuenta: me.rowcuentas[me.indice].idcuenta,
                                    tiposubcuenta:element.tiposubcuenta,
                                    subcuenta:element.subcuenta,
                                    subdebe:element.subdebe,
                                    subhaber:element.subhaber,
                                    detalle:element.detalle,
                                    nombre:element.nombre,
                                    idsubcuenta:element.idsubcuenta});

                    element.subdebe=0;
                    element.subhaber=0;
                }
            });
            //console.log(sumad+"-"+sumah);
            me.rowcuentas[me.indice].debe=sumad;
            me.rowcuentas[me.indice].haber=sumah;

            me.cerrarModalSubcuentas();

        },
        listaUnidades(){
            var url='/fil_unidad/listaUnidades?activo=1';
            axios.get(url).then(response=>{
                this.arrayUnidades=response.data.unidades;
            });
        },
        modificarsaldo(){
            let me=this;
            console.log("saldo modificado");
            me.monto=me.montoexcedente;
        },
        resetexcedente(){
            let me=this;
            if(!me.excedente)
            {    
                me.montoexcedente=0;
                //console.log(me.monto+" verdad");
                me.monto=me.montooriginal;
                console.log(me.monto);
            }
            else
            {
                me.monto=me.montooriginal;
                console.log(me.monto+" falso");
            }
          
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
        cargarvue(arrayValores){
            $('#divdescargo').css('display','block');
            this.selectAscinalss();// cuenta global ascinalss 2000000
            this.fechahoy();
            this.getRutasReports();
            this.idmodulo=arrayValores['idmodulo'];
            this.selectDocumento();
            this.classModal=new _pl.Modals();
            this.classModal.addModal('librocompras');
            this.classModal.addModal('proveedor');
            this.classModal.addModal('addsubcuentas');
            this.resetComprobante()
            this.selectLibroCuenta();
            this.listaUnidades();
            this.rowcuentas=[];
            this.asignadoa=arrayValores['asignadoa'];
            this.monto=arrayValores['saldo_descargo'];
            this.glosa=arrayValores['glosa'];
            this.tipocargo=arrayValores['cargo'];
            this.idasientomaestro=arrayValores['idasientomaestro'];
            this.tituloModal=arrayValores['titulomodal'];
            this.fecha_solicitud=arrayValores['fechamin'];
            this.idsolccuenta=arrayValores['idsolccuenta'];
            this.idfilial=arrayValores['idfilial'];
            this.nommunicipio=arrayValores['nommunicipio'];
            this.sigla=arrayValores['sigla'];
            this.sidirectorio=arrayValores['sidirectorio'];
            this.subcuenta=arrayValores['subcuenta'];
            
            this.selectCuentaHaber(this.idasientomaestro);
            //console.log(arrayValores);
            this.tipoAccion=1;
            this.borrador=false;
            this.rowcuentas= [{   idcuenta:'',
                    idsubcuenta: [],
                    moneda:'bs',
                    documento:'',
                    debe:0,
                    haber:0
                        }] ;
            this.subcuentas.push({
                tiposubcuenta:arrayValores['tiposubcuenta'],
                subcuenta:arrayValores['subcuenta'],
                subdebe:0,
                subhaber:0,
                detalle:'Cierre Cargo de Cuenta',
                nombre:arrayValores['asignadoa'],
                idsubcuenta:arrayValores['idsubcuenta']

            })
            this.montooriginal=this.monto;
            this.excedente=false;
            this.montoexcedente=0;

        },
        selectCuentaHaber(id){
            let me=this;
            var url= '/con_perfilcuentadetalle/selectcuentahaber?id='+id;
            //console.log(url);
            
            axios.get(url).then(function (response){
                var respuesta= response.data;
                //console.log(respuesta);
                me.idcuentahaber=respuesta;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        cerrarvue(){
            $('#divdescargo').css('display','none');
            this.silibrocompra=0;
            this.limpiarajax=0;
        },
        deleterowcuentas:function(index) {
            this.rowcuentas.splice(index, 1);
            if(index===0)
                this.addrowcuentas(false);
        },
        addrowcuentas(focusin=true) {
            var ajax='ajaxselect'+this.rowcuentas.length;
            this.rowcuentas.push({   idcuenta:'',
                            idsubcuenta: [],
                            moneda:'bs',
                            documento:'',
                            debe:0,
                            haber:0
                             });
                            
           if(focusin){    setTimeout(() => {
                   this.$refs[ajax][0].ifocus();
               }, 50); }
        },
        tiempo(){
        this.clearSelected=1;
        this.borrador=1;
        },
        tiempolimpiar(){
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
            me.idcuenta.push({"idcuenta":cuentas.idcuenta,"indice":index});
            me.rowcuentas.forEach(element => {
                if(element.idcuenta==me.lc)
                {
                    cont++;
                }
            });
            //console.log(cont);
            
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
        },
        clean(a){
        this.rowcuentas[a].idcuenta='';  
        },
        selectDocumento(){
            let me=this;
            var url= '/par_documento/selectDocumento?idmodulo='+this.idmodulo;
            //console.log(url);
            axios.get(url).then(function (response) {
                //console.log(response);
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
                
                if(me.sifacturas){
                    me.addrowcuentas(false);
                me.indice=me.rowcuentas.length-1;
                me.rowcuentas[me.indice].idcuenta=me.lc; 
                me.rowcuentas[me.indice].debe=me.acumulado13;}
            }

            if(me.indice!='')
            {
                me.rowcuentas[me.indice].idsubcuenta=[];
                me.subcuentas=me.subcuentas.filter(function(el){
                    return el.tiposubcuenta!=3;
                });
                me.checkusarfactura.forEach((element,index) => {
                    var resultado = me.arrayLibrocompras.find( elem => elem.idlibrocompra == element );
                    //console.log(index);
                    
                    me.rowcuentas[me.indice].idsubcuenta.push({indice:me.indice,
                                        idcuenta: me.rowcuentas[me.indice].idcuenta,
                                        tiposubcuenta:3,
                                        subcuenta:resultado.nit,
                                        subdebe:resultado.credfiscal,
                                        subhaber:0,
                                        detalle:resultado.detalle_fac,
                                        nombre:resultado.nomproveedor,
                                        idsubcuenta:resultado.idproveedor
                                        });
                                    
                    me.subcuentas.push({//indice:me.indice,
                                        //idcuenta: me.rowcuentas[me.indice].idcuenta,
                                        tiposubcuenta:3,
                                        subcuenta:resultado.nit,
                                        subdebe:0,
                                        subhaber:0,
                                        detalle:resultado.detalle_fac,
                                        nombre:resultado.nomproveedor,
                                        idsubcuenta:resultado.idproveedor
                                        });
                });
            }    
           





            me.classModal.closeModal('librocompras'); 
            me.classModal.openModal('comprobantecontable')
            me.sifacturas=false; 
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
        registrarAsiento(){
            let me = this;
            let conexcedente;
            if(me.excedente)
                conexcedente=me.montooriginal;
            else
                conexcedente=me.debe;
            
            /* me.acumulado13=0;
            me.acumulado87=0; */
            var validarfacturas=0;
            me.subcuentas[0].subhaber=me.montooriginal;
            me.subcuentas[0].idcuenta=me.idcuentahaber;
            me.rowcuentas.push({ idcuenta: me.idcuentahaber,
                                            idsubcuenta:[me.subcuentas[0]],
                                            moneda:'bs',
                                            documento:'',
                                            debe:"",
                                            haber: conexcedente}); 


            //console.log(me.rowcuentas);
            if(me.checkusarfactura.length>0)
            validarfacturas=1;

            
            axios.post('/con_asientomaestro/registrar',{
                'rowregistros':me.rowcuentas,
                'fechatransaccion':me.fechatransaccion,
                'idtipocomprobante':me.idtipocomprobante, //corregido y ahora se selcciona el tipo de comprobante, por defecto es 3 tipo comprobante traspaso
                'tipodocumento':me.tipodocumento,
                'numdocumento':me.numdocumento,
                'glosa':me.glosa2,
                'idmodulo':me.idmodulo,
                'idfacturas':me.checkusarfactura,
                'validarfacturas':validarfacturas,
                'saldoc':me.saldoc,
                'sidirectorio':this.sidirectorio,
                'borrador':true,
                'idunidad':me.idunidad

            }).then(function (response) {
                
                var residasientomaestro=response.data;
                swal(
                        'Registrado y validado Correctamente'
                    )   
                if(me.saldoc==0)
                {
                    me.actualizarsolicitud(2,residasientomaestro);
                }
                else
                {
                    me.actualizarsolicitud(1,residasientomaestro)
                }      
                if(me.sidirectorio)
                    var directorio=1;
                else
                    var directorio=2;

                me.reporteAsientoautomatico(residasientomaestro);
                me.resetComprobante();
                me.$emit('cerrardescargo');
                //me.listrcomprobantes();
                //me.$refs.comprobanteprincipal.listacomprobantes();
            }).catch(function (error) {
                console.log(error);
            });
        },
        actualizarsolicitud(valor,residasientomaestro){
            let me = this;
            //console.log(me.saldoc);
            
                axios.put('/glo_solccuenta/actualizarsegccuenta',{
                    'idsolccuenta': me.idsolccuenta,
                    'valor':valor,
                    'saldocuenta':me.saldoc,
                    'residasientomaestro':residasientomaestro,
                    'idunidad':me.idunidad
                }).then(function (response) {
                    //console.log('correcto');
                                            
                }).catch(function (error) {
                    console.log(error);
                });
        },
        reporteAsientoautomatico(idasientomaestro){
            let me=this;
            /////////////////////birt
            /* var url=me.reporte_automatico + idasientomaestro+'&tiposubcuenta='+directorio; 
            console.log(url);
            plugin.viewPDF(url,'Asiento Contable'); */
            ///////////////////////////////////////
            var url='/pdf?idasientomaestro='+idasientomaestro;
            //console.log(url);
            window.open(url, '_blank');
        },
        getRutasReports(){ 
            let me=this;
            var url= '/con_reportes';
            axios.get(url).then(function (response) {
                    var respuesta= response.data; ;
                me.reporte_seg_ccuentas = respuesta.REP_SEG_CCUENTAS; 
                me.reporte_automatico=respuesta.REP_ASIENTO_AUTOMATICO;
                me.reporte_documento=respuesta.REP_DOC_OBLIGACION;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        /*
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
                'idfacturas':me.idfacturas,
            }).then(function (response) {
                var residasientomaestro=response.data;
                //console.log(response.data)
                swal(
                        'Editado Correctamente'
                    )  
                me.resetComprobante();                  
                //me.listacomprobantes();
                
            }).catch(function (error) {
                console.log(error);
            });
        },
        */
        resetComprobante(){
            this.limpiarajax=0;
            setTimeout(this.tiempolimpiar, 100); 
            this.rowcuentas= [{   idcuenta:'',
                        idsubcuenta: '',
                        moneda:'bs',
                        documento:'',
                        debe:'',
                        haber:''
                            }] 
            this.fechatransaccion=this.fechaactual;
            this.idtipocomprobante=3;
            this.tipodocumento='';
            this.numdocumento='';
            this.glosa2='';
            this.idasientomaestro='',
            this.acumulado13=0;
            this.idfacturas=[];
            this.subcuentas=[];
        },
        abrirmodalCompras(indice=''){
            
            let me=this;
            me.selectLibrocompras();
            me.clearSelected=0;
            setTimeout(me.tiempo, 50); 
            me.sumar13();
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
            me.detalle='';
        },
        selectLibrocompras(){
            let me=this;
            me.diaminmax();
            if(me.mes+1==me.messelected)
            {    
                //console.log(me.mes+' entra a me.mes+1=me.messelected');
                me.fechafactura=me.fechaactual;
                //me.fechafinal=me.fechaactual;
            }
            else   {
                me.fechafactura=me.fechafinal;
                //console.log(me.fechafactura+' entra a fechafactura');
            }

            var url= '/con_librocompras?modal=1&mes='+me.messelected+'&anio='+me.anioselected+'&idfilial='+me.idfilial;
            //console.log(url);
            
            axios.get(url).then(function (response) {
                var respuesta= response.data; 
                me.arrayLibrocompras = respuesta.librocompras;
                me.cierremes=respuesta.cierremes;
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



            var _13porciento=Number((total*0.13).toFixed(2));   //parseFloat((total * 0.13)).toFixed(2);
            var _87porciento=Number((total-_13porciento).toFixed(2));//(parseFloat(total)-parseFloat(_13porciento)).toFixed(2);
            
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
                'idfilial':me.idfilial,
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
                if(me.messelected==me.mes) 
                    me.fechafactura=me.fechaactual;
                else
                    me.fechafactura=me.fechafinal;

                me.numfactura='';
                me.numautorizacion='';
                me.codcontrol='';
                me.importetotal=0;
                me.nocreditofiscal=0;
                me.descuentos=0;
                //me.acumulado13=parseFloat(me.acumulado13)+parseFloat(_13porciento);
                //me.acumulado87=parseFloat(me.acumulado87)+parseFloat(_87porciento);
                //me.librocomprasOk=true;
                me.checkusarfactura=[];
                me.acumulado13=0;
                me.acumulado87=0;
                me.idfacturas=[];
                me.detalle='';
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
            me.acumulado13=0;
            console.log(me.arrayLibrocompras);
            


            me.checkusarfactura.forEach(element => {
                var resultado = me.arrayLibrocompras.find( elem => elem.idlibrocompra == element );
                //console.log(resultado.credfiscal);
                sumacheck=Number(sumacheck+resultado.credfiscal);
                suma87=Number(suma87+resultado.subtotal);
            });
            me.acumulado13=Number((me.acumulado13+sumacheck).toFixed(2));
            me.sumafac=suma87;
            me.acumulado87=Number((me.sumafac-me.acumulado13).toFixed(2));
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
                console.log(response);
                
                
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
    },
    mounted() {
        this.selectTipocomprobante();
    },
}
</script>
<style  scoped>
.botonpadding{
    padding-top: 2px;
    padding-bottom: 2px;
    
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
.form-group{
    margin-top: 0px;
    margin-bottom: 5px;

}

</style>
