<template>
<main>  
<div style="display:none" id="divMultifamiliar">    
    <div class="card" v-if="divAmbientes">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10 titcard">
                    <div class="ttabla">
                        <div class="tcelda">DEPARTAMENTOS BLOQUE "{{bloque}}"</div>
                    </div>
                </div>  
                <div class="col-md-2 text-right"> 
                    <div class="input-group-append" style="display:inline">
                        <button class="btn btn-primary dropdown-toggle" style="margin-top:0"
                            data-toggle="dropdown" aria-expanded="false">
                            Bloque...<span class="caret"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                            <a href="#" class="dropdown-item" v-for="k in regServicio.cantgrupos" :key="k" 
                                v-text="'Bloque '+String.fromCharCode(k+64)" @click="cargarBloque(String.fromCharCode(k+64))"></a>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
        <div class="card-body">
            <div class="row">
                <!--
                <div class="col-md-3" v-for="pso in 4" :key="pso">
                    <table class="table table-bordered">
                        <tr><td colspan="2" align="center" class="card-header titcard">PISO <span v-text="pso"></span></td></tr>
                        <tr v-for="dep in regServicio.ambientes" :key="dep" class="bloquefila">
                            <td v-for="ambiente in arrayAmbientes" v-if="ambiente.piso==pso&&ambiente.depto==dep" 
                                :key="ambiente.idambiente" class="bloquecelda" :class="{ocupado:ambiente.ocupado}"
                                @click="ambiente.ocupado?verAsignacion(ambiente.idasignacion,ambiente):asignarCliente(ambiente)">
                                <span v-text="'D'+ambiente.depto"></span>
                            </td>
                            <td v-for="ambiente in arrayAmbientes" v-if="ambiente.piso==pso&&ambiente.depto==dep" 
                                :key="ambiente.codambiente" style="cursor:pointer" align="left"
                                @click="ambiente.ocupado?verAsignacion(ambiente.idasignacion,ambiente):asignarCliente(ambiente)">
                                <span v-for="ocupante in arrayOcupantes" :key="ocupante.idambiente">
                                    <span v-if="ocupante.idambiente==ambiente.idambiente" 
                                          v-text="ocupante.nomgrado+' '+ocupante.nombre+' '+ocupante.apaterno"
                                          :title="ocupante.idasignacion"></span>
                                </span>
                            </td>

                        </tr>
                    </table>
                </div>
                -->

                <table class="table table-bordered" style="max-width:60%" align="center">
                    
                    <thead>
                        <tr class="tcabecera">
                            <th>DEPTO</th>
                            <th>Info</th>
                            <th>Ocupante</th>
                            <th>Ingreso</th>
                            <th>Vecimiento</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <tr v-for="ocupante in arrayOcupantes" :key="ocupante.idambiente" class="bloquefila" style="cursor:pointer"
                            @click="ocupante.ocupado?verAsignacion(ocupante.idasignacion,ocupante):asignarCliente(ocupante)">
                            <td v-text="ocupante.codambiente" class="card-header titcard" :class="{ocupado:ocupante.ocupado}" align="center"></td>
                            <td>Piso: <span v-text="ocupante.piso"></span> <br>
                                Dorm: <span v-text="ocupante.capacidad"></span>
                            </td>
                            <td><span v-if="ocupante.ocupado" v-text="ocupante.nomgrado+' '+ocupante.nomcliente"></span><br>
                                <span v-if="ocupante.ocupado" v-text="ocupante.nomfuerza"></span>
                            </td>
                            <td align="center"><span v-if="ocupante.ocupado" v-text="jsfechas.fechames(ocupante.fechaentrada)"></span></td>
                            <td align="center"><span v-if="ocupante.ocupado" v-text="jsfechas.fechames(ocupante.fechasalida)"></span></td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

            
    <div class="row" v-if="!divAmbientes">
        <div class="col-md-4"> <!-- datos del departamento -->
            <div class="card">
                <div class="card-header titcard"> Datos del Departamento </div>
                <div class="card-body">
                    <div class="text-center titcampo">
                        <span v-if="regAmbiente.depto==1">GARZONIER</span>
                        <span v-else>DEPARTAMENTO {{regAmbiente.depto}} DORMITORIOS</span>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                        <table>
                            <tr><td class="titcampo">Código:</td><td v-text="regAmbiente.codambiente"></td></tr>
                            <tr><td class="titcampo">Garantía:</td><td v-text="regAmbiente.garantia+'Bs.'"></td></tr>
                            <tr><td class="titcampo">Canon:</td><td v-text="regAmbiente.tarifasocio+'Bs.'"></td></tr>
                            <tr><td class="titcampo" nowrap>Canon Real:</td><td v-text="regAmbiente.tarifareal+'U$'"></td></tr>
                        </table>
                        </div>
                        <div class="col-md-6">
                        <table>
                            <tr><td class="titcampo">Bloque:</td><td v-text="regAmbiente.bloque"></td></tr>
                            <tr><td class="titcampo">Piso: </td> <td v-text="regAmbiente.piso"></td></tr>
                            <tr><td class="titcampo">Depto:</td> <td v-text="regAmbiente.depto"></td></tr>
                            <tr><td class="titcampo">Capacidad:</td> <td v-text="regAmbiente.capacidad+'px'"></td></tr>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- datos del departamento -->

        <div class="col-md-8"><!-- kardex socio-->
            <div class="card">
                <div class="card-header titcard"> Datos del Locatario </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <img v-if="regCliente.rutafoto && datossocio" :src="'img/socios/'+regCliente.rutafoto" width="150" height="150" class="fotosocio">
                            <img v-else src="img/socios/avatar.png" class="fotosocioservicios">
                        </div>
                        <div class="col-md-9">
                            <div class="nombrecliente text-center" v-text="regCliente.nomcliente"></div>
                            <div class="row">
                                <div class="col-md-6">
                                    <table>
                                        <tr><td class="titcampo">Fuerza:  </td><td v-text="regCliente.nomfuerza"></td> </tr>
                                        <tr><td class="titcampo">Situación:</td><td v-text="regCliente.nomestado"></td> </tr>
                                        <tr><td class="titcampo">Destino:</td><td v-text="regCliente.nommunicipio"></td> </tr>
                                        <tr><td colspan="2" v-text="regCliente.nomdestino"></td> </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table>
                                        <tr><td class="titcampo">Nro Celular:</td><td v-text="regCliente.telcelular"></td> </tr>
                                        <tr><td class="titcampo">Nro C.I.:</td><td v-text="regCliente.ciexp"></td> </tr>
                                        <tr><td class="titcampo">Papeleta:</td><td v-text="regCliente.numpapeleta"></td> </tr>
                                        <tr v-if="regCliente.idestadocivil!=2">
                                            <td class="titcampo">Estado Civil:</td><td v-text="regCliente.nomestadocivil"></td> 
                                        </tr>
                                        <tr v-if="regCliente.idestadocivil==2">
                                            <td colspan="2"><span class="titcampo">Esposa:</span><br>
                                                <div v-if="regEsposa">
                                                    <span v-text="regEsposa.nombre+' '+regEsposa.apaterno"></span>&nbsp;
                                                    <span v-if="regEsposa.amaterno" v-text="regEsposa.amaterno"></span>
                                                </div>

                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <div class="card text-white bg-danger" v-if="divNovalido">
                                <div class="card-header text-center"><b>No puede acceder a este servicio</b></div>
                                <div class="card-body text-center" style="background-color:#fff; color:#000;">
                                    <span v-if="txtValidacion.casado" v-html="txtValidacion.casado+'<br>'"></span> 
                                    <span v-if="txtValidacion.esposa" v-html="txtValidacion.esposa+'<br>'"></span> 
                                    <span v-if="txtValidacion.estado" v-html="txtValidacion.estado+'<br>'"></span> 
                                    <span v-if="txtValidacion.destino" v-html="txtValidacion.destino+'<br>'"></span> 
                                    <span v-if="txtValidacion.repetido" v-html="txtValidacion.repetido"></span> 
                                </div>
                            </div>
                            <div class="row" v-if="!regAsignacion">
                                <div class="col-md-12 text-center">
                                <button class="btn btn-secondary" style="margin:5px" @click="cargarvue(regServicio)">Cancelar Asignación</button>
                                <button class="btn btn-primary" style="margin:5px" @click="asignarCliente(regAmbiente)">Cambiar Locatario</button>
                                </div>
                            </div>
                        </div>
                    </div>                            
                </div>
            </div>
        </div>  <!-- kardex del socio-->
    </div>  
    
    
    
    <div class="row" v-if="!divAmbientes&&!divNovalido">
        <div class="col-md-4"> <!-- datos del contrato-->
            <div class="card">
                <div class="card-header titcard">Contrato</div>
                <div class="card-body">
                    <table align="center" v-if="regAsignacion.idasignacion">
                        <tr><td class="titcampo">Nro Contrato:</td>  
                            <td v-text="regAsignacion.nrasignacion"></td></tr>
                        <tr><td class="titcampo">Fecha Solicitud:</td>
                            <td v-text="jsfechas.fechames(regAsignacion.fechasolicitud)"></td></tr>
                        <tr><td class="titcampo">Fecha Contrato:</td>
                            <td v-text="jsfechas.fechames(regAsignacion.fechaentrada)"></td></tr>
                        <tr><td class="titcampo">Boleta Pago:</td>          
                            <td v-text="regAsignacion.mesboleta"></td></tr>
                        <tr><td class="titcampo">Ocupantes:</td>           
                            <td v-text="regAsignacion.ocupantes"></td></tr>
                        <tr><td class="titcampo">Estadía:</td> 
                            <td><span v-text="regAsignacion.aaa"></span>
                                <span v-text="regAsignacion.aaa==1?'año':'años'"></span>,
                                <span v-text="regAsignacion.mmm"></span>
                                <span v-text="regAsignacion.mmm==1?'mes':'meses'"></span>
                            </td></tr>
                        <tr v-if="regAsignacion.obs1"><td colspan="2">
                            <span class="titcampo">OBS: </span>
                            <span v-text="regAsignacion.obs1"></span>
                            </td></tr>
                        <tr><td colspan="2" class="titcampo">Documentos Presentados:</td></tr>
                        <tr><td colspan="2" >
                                <ul style="list-style:none; padding-left:10px">
                                    <li v-for="documento in arrayDocumentos" :key="documento.iddocumento">
                                        <input type="checkbox" v-model="arrayPresentados" :value="documento.iddocumento" disabled> 
                                        <!--
                                        <span v-for="i in arrayPresentados" :key="i"> 
                                            <i v-if="i==documento.iddocumento" class="cui-check" style="color:green"></i>
                                        </span> -->
                                        <!--
                                        <i v-if="requisito.entregado" class="cui-check" style="color:green"></i>
                                        <i v-else class="cui-circle-x" style="color:red"></i>
                                        -->
                                        <span v-text="documento.nomdocumento"></span> 
                                    </li>
                                </ul>

                            </td>
                        </tr>
                    </table>
                    <center>
                    <button v-if="!regAsignacion.idasignacion" class="btn btn-primary" @click="registrarContrato()">Registrar Contrato</button>
                    <button v-else class="btn btn-primary" @click="editarContrato()">Modificar Contrato</button>
                    <br><br>
                    <button class="btn btn-secondary" @click="reporteContrato(regAsignacion.idasignacion)">Reporte Contrato</button>
                    </center>
                </div>
            </div>                
        </div> <!-- datos del contrato -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header titcard">Administración de Pagos</div>
                <div class="card-body">
                    <div class="table-responsive" v-if="regAsignacion.idasignacion">
                    <table class="table table-bordered table-striped table-sm">
                        <thead class="tcabecera">
                            <tr>
                                <th>Nr</th> 
                                <th>Concepto</th>
                                <th>Periodo</th>
                                <th>Modo</th>
                                <th>Documento</th>
                                <th>Fecha</th>
                                <th>Importe</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(pago,index) in arrayPagos" :key="pago.idpago">
                                <td v-text="index+1" align="center"></td>
                                <td v-text="pago.concepto"></td>
                                <td v-text="pago.periodo"></td>
                                <td><span v-text="pago.modopago==2?'Depósito':'Descuento'"></span><br>
                                    <span v-if="pago.modopago==3&&pago.idestado>4">
                                        <span v-html="'<font color=green><b><i class=cui-check></i>Cobrado</b></font>'"></span>
                                    </span>
                                    <span v-if="pago.modopago==3&&pago.idestado<=4">
                                        <span v-html="'<font color=red><b><i class=cui-circle-x></i>Pendiente</b></font>'"></span>
                                    </span>
                                </td>
                                <td v-text="pago.nrdocumento" align="right"></td>
                                <td v-text="jsfechas.fechalat(pago.fecha)" align="center"></td>
                                <td v-text="pago.importe" align="right"></td>
                                <td align="center"><button @click="editarPago(pago)" class="btn btn-success btn-sm">
                                    <i class="icon-pencil"></i></button></td>
                            </tr>
                        </tbody>
                        <tfoot >
                        </tfoot>
                    </table>
                    </div>
                    <center v-if="regAsignacion.idasignacion">
                        <button style="margin:5px" class="btn btn-primary" @click="registrarPago()">Registrar Pago</button>                            
                    </center>
                </div>
            </div>
        </div><!--  ficha pagos -->
    </div> 

    


    <!-- MODAL MULTIFAMILIAR --> <!-- MODAL MULTIFAMILIAR --> <!-- MODAL MULTIFAMILIAR --><!-- MODAL MULTIFAMILIAR -->
    <!-- MODAL MULTIFAMILIAR --> <!-- MODAL MULTIFAMILIAR --> <!-- MODAL MULTIFAMILIAR --><!-- MODAL MULTIFAMILIAR -->
    <!-- MODAL MULTIFAMILIAR --> <!-- MODAL MULTIFAMILIAR --> <!-- MODAL MULTIFAMILIAR --><!-- MODAL MULTIFAMILIAR -->
    <div class="modal" :class="modalAsignar?'mostrar':''" >
        <div class="modal-dialog modal-primary modal-lg">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title">Asignar Departamento</h4>
                    <button type="button" class="close" @click="modalAsignar=0">×</button>                    
                </div>
                <div class="modal-body">
                    <div class="text-center"><span class="nombrecliente" v-text="'LOCATARIO: '+regCliente.nomcliente"></span> </div>
                    <div class="row" style="padding:10px 0px;">
                        <div class="col-md-2"><span class="titcampo">Código:</span>  <span v-text="regAmbiente.codambiente"></span> </div>
                        <div class="col-md-2"><span class="titcampo">Bloque:</span>  <span v-text="regAmbiente.bloque"></span></div>
                        <div class="col-md-2"><span class="titcampo">Piso:</span>    <span v-text="regAmbiente.piso"></span> </div> 
                        <div class="col-md-2"><span class="titcampo">Depto:</span>   <span v-text="regAmbiente.depto"></span> </div> 
                        <div class="col-md-2"><span class="titcampo">Garantía:</span><span v-text="regAmbiente.garantia"></span>Bs</div> 
                        <div class="col-md-2"><span class="titcampo">Alquiler:</span><span v-text="regAmbiente.tarifasocio"></span>Bs</div> 
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="titazul">Contrato</h5>
                            <table align="center">
                                <tr>
                                    <td nowrap>Fecha de Solicitud: <span class="txtasterisco"></span></td>
                                    <td><input type="date" class="form-control datepicker" v-model="fechasolicitud" @change="validarContrato()"></td>
                                </tr>                                 
                                <tr>
                                    <td>Nro Contrato: <span class="txtasterisco"></span></td>
                                    <td><input type="text" class="form-control" v-model="nrasignacion" @keyup="validarContrato()"></td>
                                </tr>                               
                                <tr>
                                    <td >Fecha Contrato: <span class="txtasterisco"></span></td>
                                    <td><input type="date" class="form-control" v-model="fechacontrato" @change="selBoletas(fechacontrato),validarContrato()"></td>                                    
                                </tr>
                                <tr>
                                    <td >Boleta de pago: <span class="txtasterisco"></span></td>
                                    <td><select v-model="mesboleta" class="form-control" @change="validarContrato()">
                                            <option v-for="boleta in arrayBoletas" :key="boleta" :value="boleta" v-text="boleta"></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Ocupantes: <span class="txtasterisco"></span></td>
                                    <td><select v-model="ocupantes" class="form-control" @change="validarContrato()">
                                        <option v-for="i in regAmbiente.capacidad" :key="i" :value="i" v-text="i"></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="txtvalidador" align="center">* Datos obligatorios</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5 class="titazul">Requisitos</h5>
                            <ul style="list-style:none;">
                                <li v-for="documento in arrayDocumentos" :key="documento.iddocumento">
                                    <input type="checkbox" v-model="arrayPresentados" :value="documento.iddocumento"> 
                                    <span v-text="documento.nomdocumento"></span> 
                                </li>
                            </ul>
                            <br>
                            Observaciones:<input type="text" class="form-control" v-model="obs1" >
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="modalAsignar=0">Cerrar</button>
                    <button v-if="accion==1" :disabled="!completo" class="btn btn-primary" @click="storeAsignacion()">Registrar Contrato</button>
                    <button v-if="accion==2" :disabled="!completo" class="btn btn-primary" @click="updateAsignacion()">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>


    <!-- MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS  MODAL PAGOS MODAL PAGOS  MODAL PAGOS MODAL PAGOS  -->
    <!-- MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS  MODAL PAGOS MODAL PAGOS  MODAL PAGOS MODAL PAGOS  -->
    <!-- MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS  MODAL PAGOS MODAL PAGOS  MODAL PAGOS MODAL PAGOS  -->
    <div class="modal" :class="modalPagos?'mostrar':''" >
        <div class="modal-dialog modal-primary modal-lg">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title">Registrar pago</h4>
                    <button type="button" class="close" @click="modalPagos=0">×</button>                    
                </div>
                <div class="modal-body">
                    <div class="text-center"><span class="nombrecliente" v-text="'LOCATARIO: '+regCliente.nomcliente"></span> </div>
                    <div class="row" style="padding:10px 0px;">
                        <div class="col-md-2"><b>Código:</b> <span v-text="regAmbiente.codambiente"></span> </div>
                        <div class="col-md-2"><b>Bloque:</b> <span v-text="regAmbiente.bloque"></span></div>
                        <div class="col-md-2"><b>Piso:</b>   <span v-text="regAmbiente.piso"></span> </div> 
                        <div class="col-md-2"><b>Depto:</b> <span v-text="regAmbiente.depto"></span> </div> 
                        <div class="col-md-2"><b>Garantía:</b><span v-text="regAmbiente.garantia"></span>Bs</div> 
                        <div class="col-md-2"><b>Alquiler:</b><span v-text="regAmbiente.tarifasocio"></span>Bs</div> 
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <table align="center">
                                <tr><td>Concepto: <span class="txtasterisco"></span></td>
                                    <td><select class="form-control" v-model="concepto" @change="validarPago()">
                                        <option value="Garantía">Garantía</option>
                                        <option value="Alquiler">Alquiler</option>
                                        <option value="Serv.Agua">Serv.Agua</option>
                                        </select></td>
                                </tr>
                                <tr><td>Periodo:</td>
                                    <td><select class="form-control" v-model="periodo" @change="validarPago()">
                                        <option value="Contrato">Contrato</option>
                                        <option v-for="per in arrayPeriodos" :key="per" :value="per" v-text="per"></option>
                                        </select></td>
                                </tr>
                                <tr><td>Modo de pago:</td>
                                    <td><select v-model="modopago" class="form-control" @change="validarPago(),pedirDescuento()">
                                        <option value="2">Depósito</option>
                                        <option value="3">Descuento</option>
                                        </select></td>
                                </tr>
                            </table>
                            <div class="txtvalidador text-center">Todos los campos son obligatorios</div>
                        </div>
                        <div class="col-md-6">
                            <table align="center">
                                <tr><td nowrap>Cod. <span v-text="txtModopago"></span>:</td>
                                    <td><input type="text" class="form-control text-right" v-model="nrdocumento" @change="validarPago()"></td></tr>
                                <tr><td>Fecha:</td>
                                    <td><input type="date" class="form-control text-right" v-model="fecha" @change="validarPago()"></td></tr>
                                <tr><td>Importe:</td>
                                    <td><div class="input-group">
                                            <input type="text" class="form-control text-right" v-model="importe" @change="validarPago()">
                                            <div class="input-group-append"><span class="input-group-text">Bs.</span></div>
                                        </div>
                                    </td></tr>
                            </table>
                            <div class="txtvalidador text-center">Todos los campos son obligatorios</div>
                        </div>
                    </div>
                    <p v-if="completo"><br><span class="titcampo">Glosa (para Contabilidad):</span> <i v-text="glosa"></i></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="modalPagos=0">Cancelar</button>
                    <button v-if="accion==1" :disabled="!completo" class="btn btn-primary" @click="storePago()">Registrar Pago</button>
                    <button v-if="accion==2" :disabled="!completo" class="btn btn-primary" @click="updatePago()">Guardar cambios en el pago</button>
                </div>
            </div>
        </div>
    </div>
    <ser_busqueda @clienteEncontrado="definirCliente($event)" ref="buscarCliente"></ser_busqueda>
</div><!-- divMultifamiliar -->
</main>

</template>

<script>
import * as jsfechas from '../../fechas.js';
import * as reporte from '../../functions.js';

export default {
    data(){ return {
        divAmbientes:1, divNovalido:0, jsfechas:'', rutas:[],
        arrayAmbientes:[], arrayPresentados:[], arrayDocumentos:[],  arrayClientes:[],
        arrayBoletas:[], arrayPeriodos:[], arrayOcupantes:[], arrayPerfiles:[],
        modalAsignar:0, modalPagos:0, accion:1, bloque:'A', 
        regCliente:[], regAmbiente:[], regServicio:[], regAsignacion:[], regEsposa:[], regAnterior:[],
        txtValidacion:[], completo:0, txtModopago:'Operación',

        idcliente:'', ocupantes:'', obs1:'', //para la BD
        nrasignacion:'', fechacontrato:'', fechasolicitud:'', mesboleta:'', //para la BD
        
        arrayPagos:[], idpago:'', nrdocumento:'', concepto:'', periodo:'', //pagos
        fecha:'', importe:'', modopago:2, idperfilcuentamaestro:'', glosa:'',//pagos
    }},

    methods:{
        cargarvue(servicio){
            $('#divMultifamiliar').css('display','block');
            this.regServicio=servicio;
            this.regAsignacion='';
            this.divAmbientes=1;
            this.jsfechas=jsfechas;
            this.getRutasReports();
            this.cargarBloque('A');
        },

        cerrarvue(){
            $('#divMultifamiliar').css('display','none');
        },

        /*
        cargarBloque(bloque){
            this.bloque=bloque;
            let me=this;
            var url='/ser_asignacion/listaAmbientes?codservicio='+this.regServicio.codservicio
                +'&idestablecimiento='+this.regServicio.idestablecimiento+'&bloque='+bloque;
            axios.get(url).then(function(response){
                me.arrayAmbientes=response.data.ambientes;
                
            });
            var url='/ser_asignacion/listaOcupantes?idestablecimiento='+this.regServicio.idestablecimiento;
            axios.get(url).then(function(response){
                me.arrayOcupantes=response.data.ocupantes;
            });
        },
        */

        async cargarBloque(bloque){
            this.bloque=bloque;
            let me=this;
            var url='/ser_asignacion/listaAmbientes?codservicio='+this.regServicio.codservicio+'&idestablecimiento='+this.regServicio.idestablecimiento+'&bloque='+bloque;
            let response=await axios.get(url);
            this.arrayAmbientes=response.data.ambientes;
            var url='/ser_asignacion/listaOcupantes?idestablecimiento='+this.regServicio.idestablecimiento;
            response=await axios.get(url)
            this.arrayClientes=response.data.ocupantes;
            this.arrayOcupantes=[];
            for(var i=0; i<this.arrayAmbientes.length; i++){
                this.arrayOcupantes[i]=this.arrayAmbientes[i];
                for(var j=0; j<this.arrayClientes.length; j++){
                    if(this.arrayAmbientes[i].idambiente==this.arrayClientes[j].idambiente) {
                        this.arrayOcupantes[i].nomgrado=this.arrayClientes[j].nomgrado;
                        this.arrayOcupantes[i].nomcliente=this.arrayClientes[j].nombre+this.arrayClientes[j].apaterno;
                        this.arrayOcupantes[i].nomfuerza=this.arrayClientes[j].nomfuerza;
                        this.arrayOcupantes[i].fechaentrada=this.arrayClientes[j].fechaentrada;
                        this.arrayOcupantes[i].fechasalida=this.arrayClientes[j].fechasalida;
                        break;
                    }
                }
            }
        },


        asignarCliente(ambiente)
        {   this.regAmbiente=ambiente;
            this.$refs.buscarCliente.cargarModal(this.regAmbiente);
        },

        definirCliente(cliente)
        {   this.verCliente(cliente.idcliente,'s');
            this.regAsignacion='';
        },

        verCliente(idcliente,tipo){
            this.divAmbientes=0;
            this.divNovalido=0;
            let me=this;
            var url='/ser_asignacion/kardexCliente?idcliente='+idcliente+'&tipocliente='+tipo;
            axios.get(url).then(function(response){
                me.regCliente=response.data.cliente[0];
                me.regCliente.nomcliente=me.regCliente.nomgrado+' '+me.regCliente.apaterno+' '
                    +me.regCliente.amaterno+' '+me.regCliente.nombre;
                me.regCliente.ciexp=me.regCliente.ci+' '+me.regCliente.abrvdep;
                me.regCliente.tipocliente=tipo;
                me.txtValidacion.esposa='';
                if(me.regCliente.idestadocivil==2){
                    url='afi_beneficiario/verEsposa?idsocio='+me.regCliente.idcliente;
                    axios.get(url).then(function(response){
                        me.regEsposa=response.data.esposa[0];
                        if(!me.regEsposa){
                            me.divNovalido=1; 
                            me.txtValidacion.esposa='Registre los DATOS DE LA ESPOSA en el módulo: <br> Socios &rarr; Afiliaciones &rarr;  Beneficiarios';
                        }
                    });
                }                
                me.txtValidacion.casado='';
                me.txtValidacion.estado='';
                me.txtValidacion.destino='';
                me.txtValidacion.repetido='';
                if(me.regCliente.idestadocivil!=2){
                    me.divNovalido=1;
                    me.txtValidacion.casado='El socio debe tener estado civil CASADO';
                }
                if(me.regCliente.idestservicios!=1){
                    me.divNovalido=1;
                    me.txtValidacion.estado='El socio debe pertenecer al SERVICIO ACTIVO';
                }
                if(me.regCliente.idmunicipio!=30){
                    me.divNovalido=1;
                    me.txtValidacion.destino='El socio debe estar destinado en la ciudad de LA PAZ';
                }
            });

        },   

        verAsignacion(idasignacion,ambiente){
            this.divAmbientes=0;
            this.regAmbiente=ambiente;
            let me=this;
            var url='/ser_asignacion/verAsignacion?idasignacion='+idasignacion;
            axios.get(url).then(function(response){
                me.regAsignacion=response.data.asignacion[0];
                me.regAsignacion.aaa=Math.floor(me.regAsignacion.dias/365);
                var dias=me.regAsignacion.dias-(me.regAsignacion.aaa*365)
                me.regAsignacion.mmm=Math.floor(dias/31);
                me.verCliente(me.regAsignacion.idcliente,me.regAsignacion.tipocliente);
                me.listaDocumentos();
                me.arrayPresentados=JSON.parse('['+me.regAsignacion.idpresentados+']');
                me.listaPagos(me.regAsignacion.idasignacion);
            });
        },

        listaDocumentos(){
            let me=this;
            var url='/ser_asignacion/listaDocumentos?iddocumentos='+this.regServicio.iddocumentos;
            axios.get(url).then(function(response){
                me.arrayDocumentos=response.data.documentos;
                //me.arrayPresentados=JSON.parse('['+me.regAsignacion.idpresentados+']');
            })
        },

        selBoletas(fechacontrato){
            var mes=['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
            var aa=fechacontrato.substr(0,4);
            var k=fechacontrato.substr(5,2)-4;
            for(var i=0;i<3;i++)
                this.arrayBoletas[i]=mes[i+k]+'/'+aa;
        },

        selPeriodos(){
            var arrayAlquiler=[], arrayAgua=[];
            for(var i=0;i<this.arrayPagos.length;i++){
                if(this.arrayPagos[i].concepto=='Alquiler')
                    arrayAlquiler.push(this.arrayPagos[i]);
                if(this.arrayPagos[i].concepto=='Serv.Agua')
                    arrayAgua.push(this.arrayPagos[i]);
            }
            var k=this.regAsignacion.fechaentrada.substr(5,2)-1;
            var mes=['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
            var aa=this.regAsignacion.fechaentrada.substr(0,4);
            if(arrayAlquiler.length>0) {
                var mm=arrayAlquiler[arrayAlquiler.length-1].periodo.substr(0,3);
                for(var k=0;k<mes.length;k++) if(mes[k]==mm) break;
            }
            for(var i=0;i<6;i++) this.arrayPeriodos[i]=mes[i+k]+'/'+aa;
            this.periodo=this.arrayPeriodos[0];
        },

        validarContrato(enviar){
            this.completo=0;
            if((this.fechasolicitud) 
                &&(this.nrasignacion)
                &&(this.fechacontrato)
                &&(this.mesboleta)
                &&(this.ocupantes))
                    this.completo=1;
            if((enviar)&&(this.fechacontrato<this.fechasolicitud))
            {   swal('Error de fechas','La fecha del contrato no puede ser anterior a la fecha de solicitud','error');
                this.completo=0;
            }
        },

        pedirDescuento(){
            this.txtModopago='Operación';
            if(this.modopago==3) 
            {   swal({  title:'Verificación',type:'info',
                    html:'Para la modalidad de descuento, deberá generar el '
                        +'<br><font color=green><b>Código de Préstamo</b></font> ' 
                        +'con el responsable de dicho módulo',
                    confirmButtonText:'Entendido'});
                this.txtModopago='Préstamo';
            }
        },

        validarPago(){
            this.completo=0;
            if((this.concepto)
                &&(this.periodo)
                &&(this.modopago)
                //&&(this.nrdocumento)
                &&(this.fecha)
                &&(this.importe)){
                    this.completo=1;
                    this.glosa=this.regServicio.nomestablecimiento+', pago '+this.concepto;
                    if(this.concepto=='Garantía') this.glosa+=' contrato nro ' +this.regAsignacion.nrasignacion;
                    if(this.concepto=='Alquiler') this.glosa+=' periodo '+this.periodo;
                    this.glosa+=' con depósito nro '+this.nrdocumento+' - '+this.regCliente.nomcliente;
                }
        },

        resetAsignacion(){
            this.nrasignacion='';
            this.fechacontrato='';
            this.fechasolicitud='';
            this.mesboleta='';
            this.ocupantes='';
        },

        registrarContrato(){
            this.modalAsignar=1;
            this.accion=1;
            this.listaDocumentos();
            this.resetAsignacion();
        },

        editarContrato(){
            this.modalAsignar=1;
            this.accion=2;
            this.nrasignacion=this.regAsignacion.nrasignacion;
            this.fechasolicitud=this.regAsignacion.fechasolicitud;
            this.fechacontrato=this.regAsignacion.fechaentrada;
            this.selBoletas(this.regAsignacion.fechaentrada);
            this.mesboleta=this.regAsignacion.mesboleta;
            this.ocupantes=this.regAsignacion.ocupantes;
            this.arrayPresentados=JSON.parse('['+this.regAsignacion.idpresentados+']');
            this.validarContrato();
        },

        storeAsignacion(){
            this.modalAsignar=0;
            this.divAmbientes=0;
            let me=this;
            axios.post('/ser_asignacion/storeAsignacion',{
                'idcliente':this.regCliente.idcliente,
                'tipocliente':'s',
                'idambiente':this.regAmbiente.idambiente,
                'nrasignacion':this.nrasignacion,
                'fechasolicitud':this.fechasolicitud,
                'mesboleta':this.mesboleta,
                'ocupantes':this.ocupantes,
                'idpresentados':this.arrayPresentados.join(),
                'idimplementos':'',
                'fechaentrada':this.fechacontrato,
                'horaentrada':'',
                'horasalida':'',
                'fechadefuncion':'',
                'idresponsable':'',
                'actividad':'',
                'idrepresentante':'',
                'obs1':this.obs1,
            }).then(function(){
                swal('Registro Correcto','Se ha registrado el ingreso','success');
                var url='/ser_asignacion/verAsignacion?idambiente='+me.regAmbiente.idambiente;
                axios.get(url).then(function(response){
                    me.regAsignacion=response.data.asignacion[0];
                    me.verAsignacion(me.regAsignacion.idasignacion,me.regAmbiente);
                });
            });
        },

        updateAsignacion(){
            this.modalAsignar=0;
            let me=this;
            axios.put('/ser_asignacion/updateAsignacion',{
                'idasignacion':this.regAsignacion.idasignacion,
                'nrasignacion':this.nrasignacion,
                'fechasolicitud':this.fechasolicitud,
                'mesboleta':this.mesboleta,
                'ocupantes':this.ocupantes,
                'idpresentados':this.arrayPresentados.join(),
                'idimplementos':'',
                'fechaentrada':this.fechacontrato,
                'horaentrada':'',
                'horasalida':'',
                'fechadefuncion':'',
                'idresponsable':'',
                'actividad':'',
                'idrepresentante':'',
                'obs1':this.obs1
            }).then(function(){
                swal('Actualizado','Se han guardado los cambios realizados','success');
                me.verAsignacion(me.regAsignacion.idasignacion,me.regAmbiente);
            });
        },        

        listaPagos(idasignacion){
            let me=this;
            var url='/ser_pago/listaPagos?idasignacion='+idasignacion;
            axios.get(url).then(function(response){
                me.arrayPagos=response.data.pagos;
                for(var i=0,t=0;i<me.arrayPagos.length;i++) t+=me.arrayPagos[i].importe;
                me.tpagado=t; 
                me.tsaldo=me.regAmbiente.tarifasocio-t;
            });
        },

        registrarPago(){
            this.modalPagos=1;
            this.accion=1;
            this.txtModopago='Operación';
            this.resetPago();
            this.selPeriodos();
            this.modopago=2;
            if(this.tpagado==0) {
                this.concepto='Garantía';
                this.periodo='Contrato';
                this.importe=this.regAmbiente.garantia;
            }
            if(this.tpagado>=this.regAmbiente.garantia){
                this.concepto='Alquiler';
                this.importe=this.regAmbiente.tarifasocio;
            }
        },

        editarPago(pago){
            this.modalPagos=1;
            this.accion=2;
            this.txtModopago='Operación';
            if(this.modopago=='Descuento') this.txtModopago='Préstamo';
            this.idpago=pago.idpago;
            this.concepto=pago.concepto;
            this.periodo=pago.periodo;
            this.nrdocumento=pago.nrdocumento;
            this.modopago=pago.modopago;
            this.fecha=pago.fecha;
            this.importe=pago.importe;
            this.validarPago();
        },

        resetPago(){
            this.concepto='';
            this.periodo='';
            this.nrdocumento='';
            this.modopago='';            
            this.fecha='';
            this.importe='';
            this.completo=0;
        },

        storePago(){
            this.modalPagos=0;

            if(this.modopago==2) this.idperfilcuentamaestro=9;
            if(this.modopago==3) this.idperfilcuentamaestro=10;
            let me=this;
            axios.post('/ser_pago/storePago',{
                'idasignacion':this.regAsignacion.idasignacion,
                'concepto':this.concepto,
                'periodo':this.periodo,
                'nrdocumento':this.nrdocumento,
                'modopago':this.modopago,
                'idperfilcuentamaestro':this.idperfilcuentamaestro,
                'fecha':this.fecha,
                'importe':this.importe,
                'idresponsable':0,
                'glosa':this.glosa
            }).then(function(response){
                swal('Pago registrado correctamente','','success');
                me.verAsignacion(me.regAsignacion.idasignacion,me.regAmbiente);
            });
        },

        updatePago(){
            this.modalPagos=0;
            var glosa=this.regServicio.nomestablecimiento+', pago '+this.concepto;
            if(this.concepto=='Garantía') glosa+=' contrato nro ' +this.regAsignacion.nrasignacion;
            else glosa+=' periodo '+this.periodo;
            glosa+=' con depósito nro '+this.nrdocumento+' - '+this.regCliente.nomcliente;
            if(this.modopago==2) this.idperfilcuentamaestro=9;
            if(this.modopago==3) this.idperfilcuentamaestro=10;            
            let me=this;
            axios.put('/ser_pago/updatePago',{
                'idpago':this.idpago,
                'concepto':this.concepto,
                'periodo':this.periodo,
                'nrdocumento':this.nrdocumento,
                'modopago':this.modopago,
                'idperfilcuentamaestro':this.idperfilcuentamaestro,
                'fecha':this.fecha,
                'importe':this.importe,
                'idresponsable':0,
                'glosa':glosa
            }).then(function(response){
                swal('Registro actualizado','Se ha guardado los cambios en este pago','success');
                me.verAsignacion(me.regAsignacion.idasignacion,me.regAmbiente);
            });
        },






        reporteContrato(idasignacion){
            var url=this.rutas.REP_CONTRATOJP+idasignacion;
            reporte.viewPDF(url,'el contrato');
        },

	    getRutasReports (){ 
            let me=this;
            var url= '/ser_asignacion/reportes';
            axios.get(url).then(function (response) { 
                me.rutas = response.data;   
            });
        },
    }
}
</script>
