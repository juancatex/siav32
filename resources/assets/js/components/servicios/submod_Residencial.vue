<template>
<main>
<div style="display:none" id="divResidencial">
    <div class="card" v-if="divAmbientes">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10 titcard">DORMITORIOS PISO <span v-text="piso"></span>
                </div>
                <div class="col-md-2 text-right">
                    <div class="input-group-append" style="display:inline">
                        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            Piso... <span class="caret"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                            <a href="#" class="dropdown-item" v-for="k in 2" :key="k" 
                                v-text="'Piso '+k" @click="cargarPiso(k)"></a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="card-body"> <!-- dormitorios y ocupantes -->
            <table class="table-bordered" align="center">
                <tr v-for="ambiente in arrayAmbientes" :key="ambiente.idambiente">
                    <td v-text="ambiente.codambiente.substr(-3)" class="card-header titcard"></td>
                    <td>
                    <div style="display:table; width:100%">
                        <div class="tfila bloquefila" v-for="ocupante in arrayOcupantes" :key="ocupante.idcliente" style="cursor:pointer"
                            v-if="ocupante.idambiente==ambiente.idambiente" @click="verAsignacion(ocupante.idasignacion,ambiente)">
                            <div class="tcelda" style="padding:8px 15px">
                                <span v-text="ocupante.nomgrado+' '+ocupante.apaterno+' '+ocupante.amaterno+' '+ocupante.nombre"></span></div>
                            <div class="tcelda" style="padding:8px 15px"><span v-text="jsfechas.fechadia(ocupante.fechaentrada)"></span></div>
                            <div class="tcelda" style="padding:8px 15px"><span v-text="ocupante.horaentrada"></span></div>
                            <div class="tcelda" style="padding:8px 15px">
                                <span v-text="ocupante.currhora<'12:31:00'?(ocupante.noches):(ocupante.noches+1)"></span> noches
                            </div>
                        </div>
                    </div> <!-- if algo < ambiente.capacidad -->
                        <div class="bloquefila text-center" style="padding:8px; cursor:pointer">
                            <a href="#" @click="asignarCliente(ambiente)"> Agregar Huésped</a>
                        </div>
                    
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="row" v-if="!divAmbientes">
        <div class="col-md-8">
            <div class="card"><!-- kardex socio-->
                <div class="card-header titcard"> Datos del Huésped </div>
                <div class="card-body">
                    <div class="row" v-if="regCliente.tipocliente=='s'">
                        <div class="col-md-3"> 
                            <img src="storage/socio/avatar.png" class="fotosocioservicios">
                        </div>
                        <div class="col-md-9">
                            <div class="nombrecliente text-center" v-text="regCliente.nomcliente"></div>
                            <div class="row">
                                <div class="col-md-6">
                                    <table >
                                        <tr><td class="titcampo">Fuerza:   </td><td width="70%" v-text="regCliente.nomfuerza"></td> </tr>
                                        <tr><td class="titcampo">Celular:</td><td v-text="regCliente.telcelular"></td> </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table>
                                        <tr><td class="titcampo">Nro C.I.:</td><td v-text="regCliente.ciexp"></td> </tr>
                                        <tr><td class="titcampo">Papeleta:</td><td v-text="regCliente.numpapeleta"></td> </tr>                                        
                                    </table>
                                </div>
                            </div>
                            <br>
                            <div class="row" v-if="!regAsignacion">
                                <div class="col-md-12 text-center">
                                <button class="btn btn-secondary" style="margin:5px" @click="cargarvue(regServicio)">Cancelar Asignación</button>
                                <button class="btn btn-primary" style="margin:5px" @click="asignarCliente(regAmbiente)">Cambiar Huésped</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="nombrecliente text-center" v-if="regCliente.tipocliente=='c'"> 
                        <span v-text="regCliente.sexo=='m'?'Sr. ':'Sra. '"></span> <span v-text="regCliente.nomcliente"></span>
                    </div>
                    <div class="row" v-if="regCliente.tipocliente=='c'">
                        <div class="col-md-3">
                            <span class="titcampo">Nro C.I.:</span> <span v-text="regCliente.ciexp"></span>
                        </div>
                        <div class="col-md-3">
                            <span class="titcampo">F. Nacim.:</span> <span v-text="jsfechas.fechalat(regCliente.fechanac)"></span>
                        </div>
                        <div class="col-md-3">
                            <span class="titcampo">Celular:</span> <span v-text="regCliente.telcelular"></span>
                        </div>
                        <div class="col-md-3">
                            <span class="titcampo">Huésped:</span>Cliente Civil
                        </div>
                    </div>
                </div>
                
            </div>  <!-- kardex del socio-->
        </div>
        <div class="col-md-4"> <!-- datos del dormitorio -->
            <div class="card">
                <div class="card-header titcard"> Datos del Dormitorio  </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table>
                                <tr><td class="titcampo">Código:</td><td v-text="regAmbiente.codambiente"></td></tr>
                                <tr><td class="titcampo">Piso: </td> <td v-text="regAmbiente.codambiente.substr(1,1)"></td></tr>
                                <tr><td class="titcampo">Pieza:</td> <td v-text="regAmbiente.codambiente.substr(3,2)"></td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table>
                                <tr><td class="titcampo">Tipo: </td><td>Individual</td></tr>
                                <tr><td class="titcampo">Precio:</td>
                                    <td v-if="regCliente.tipocliente=='s'" v-text="regAmbiente.tarifasocio+' Bs.'"></td>
                                    <td v-if="regCliente.tipocliente=='c'" v-text="regAmbiente.tarifareal+' Bs.'"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row" style="padding:10px;">
                        <button class="btn btn-primary col-md-12" :disabled="!regPago.nrdocumento"
                            @click="liberarAmbiente()">Liberar Ambiente</button>
                    </div>
                </div>
            </div>
        </div> <!-- datos del dormitorio -->
    </div>

    
    <div class="row" v-if="!divAmbientes">
        <div class="col-md-4"> <!-- datos entrada -->
            <div class="card">
                <div class="card-header titcard">
                    <div class="row">
                        <div class="col-md-6">Entrada</div>
                        <div class="col-md-6 text-right">Nro <span v-text="regAsignacion.nrasignacion"></span></div>
                    </div>
                </div>
                <div class="card-body">
                    <div v-if="regAsignacion.fechaentrada">
                        <div class="row">
                            <div class="col-md-7 nowrap">
                                <span class="titcampo">Fecha:</span> 
                                <span v-text="jsfechas.fechadia(regAsignacion.fechaentrada)"></span>
                            </div>
                            <div class="col-md-5">
                                <span class="titcampo">Hora: </span> 
                                <span v-text="regAsignacion.horaentrada"></span>
                            </div>
                        </div><br>
                        <center class="titcampo">Prendas Entregadas</center>
                        <div class="row" >
                            <div class="col-md-6">
                                <ul style="list-style:none; padding-left:0px">
                                    <li v-for="(implemento,index) in arrayImplementos" :key="implemento.idimplemento" v-if="index<5">
                                        <input type="checkbox" v-model="arrayEntregados" :value="implemento.idimplemento" disabled>
<!-- 
                                        <i v-if="implemento.entregado" class="cui-check" style="color:green"></i>
                                        <i v-else class="cui-circle-x" style="color:red"></i>
                                         -->
                                        <span v-text="implemento.nomimplemento"></span>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul style="list-style:none; padding-left:0px">
                                    <li v-for="(implemento,index) in arrayImplementos" :key="implemento.idimplemento" v-if="index>=5">
                                        <input type="checkbox" v-model="arrayEntregados" :value="implemento.idimplemento" disabled>
<!-- 
                                        <i v-if="implemento.entregado" class="cui-check" style="color:green"></i>
                                        <i v-else class="cui-circle-x" style="color:red"></i>
                                         -->
                                        <span v-text="implemento.nomimplemento"></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <span class="titcampo" v-if="regAsignacion.obs1">OBS: </span> 
                        <span v-text="regAsignacion.obs1"></span>
                    </div>
                    <center>
                        <button v-if="!regAsignacion.fechaentrada" class="btn btn-primary" 
                            @click="registrarEntrada()" style="margin:5px">Registrar Entrada</button>
                        <button v-else class="btn btn-primary"  :disabled="regAsignacion.fechasalida"
                            @click="editarEntrada()" style="margin:5px">Editar Entrada</button>
                    </center>
                </div>
            </div>
        </div>  <!-- datos entrada -->

        <div class="col-md-4">  <!-- datos salida -->
            <div class="card">
                <div class="card-header titcard">
                    <div class="row">
                        <div class="col-md-6">Salida</div>
                        <div class="col-md-6 text-right">Nro <span v-text="regAsignacion.nrasignacion"></span></div>
                    </div>                    
                </div>
                <div class="card-body">
                    <div v-if="regAsignacion.fechasalida">
                        <div class="row">
                            <div class="col-md-7 nowrap">
                                <span class="titcampo">Fecha:</span> 
                                <span v-text="jsfechas.fechadia(regAsignacion.fechasalida)"></span>
                            </div>
                            <div class="col-md-5">
                                <span class="titcampo">Hora: </span> 
                                <span v-text="regAsignacion.horasalida"></span>
                            </div>
                        </div><br>
                        <center class="titcampo">Prendas Devueltas</center>
                        <div class="row" >
                            <div class="col-md-6">
                                <ul style="list-style:none; padding-left:0px">
                                    <li v-for="(implemento,index) in arrayImplementos" :key="implemento.idimplemento" v-if="index<5">
                                        <input type="checkbox" v-model="arrayEntregados" :value="implemento.idimplemento" disabled>
                                        <span v-text="implemento.nomimplemento"></span>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul style="list-style:none; padding-left:0px">
                                    <li v-for="(implemento,index) in arrayImplementos" :key="implemento.idimplemento" v-if="index>=5">
                                        <input type="checkbox" v-model="arrayEntregados" :value="implemento.idimplemento" disabled>
                                        <span v-text="implemento.nomimplemento"></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <span class="titcampo" v-if="regAsignacion.obs2">OBS: </span>
                        <span v-text="regAsignacion.obs2"></span>
                        <br>
                    </div>
                    <center style="padding:3px">
                        <button v-if="!regAsignacion.fechasalida" class="btn btn-primary" @click="registrarSalida()"
                            :disabled="!regAsignacion.fechaentrada">Registrar Salida</button>
                        <button v-else class="btn btn-primary" @click="editarSalida()"
                            :disabled="regPago.nrdocumento">Editar Salida</button>
                    </center>
                </div>
            </div>
        </div> <!-- datos salida -->

        <div class="col-md-4">
            <div class="card">
                <div class="card-header titcard">Cobranza</div>
                <div class="card-body">
                    <center v-if="regPago.modopago==3" class="titcard">MODALIDAD DESCUENTO</center>
                    <table align="center" v-if="regPago.idpago">
                        <tr><td class="titcampo" nowrap><span v-text="regPago.modopago==3?'Nro Préstamo: ':'Nro Factura: '"></span></td> 
                            <td v-text="regPago.nrdocumento"></td></tr>
                        <tr><td class="titcampo">Fecha:</td>       
                            <td v-text="jsfechas.fechalat(regPago.fecha)"></td></tr>                        
                        <tr><td class="titcampo" valign="top">Titular:</td>     
                            <td v-text="regCliente.nombre+' '+regCliente.apaterno"></td></tr>
                        <tr><td class="titcampo" valign="top">Concepto:</td>    
                            <td v-text="regPago.concepto"></td></tr>
                        <tr><td class="titcampo" valign="top">Estadía:</td>
                            <td><span v-text="jsfechas.fechadia(regAsignacion.fechaentrada)+' '
                                +regAsignacion.horaentrada"></span> al 
                            <br><span v-text="jsfechas.fechadia(regAsignacion.fechasalida) +' '
                                +regAsignacion.horasalida"></span></td></tr>
                        <tr><td class="titcampo" valign="top">Periodo:</td>    
                            <td v-text="regPago.periodo"></td></tr>
                        <tr><td class="titcampo">Importe:</td>     
                            <td><span v-text="regPago.importe"></span>Bs.</td></tr>
                    </table>
                    <center>
                        <button v-if="!regPago.idpago" class="btn btn-primary" @click="registrarPago()" style="margin:5px"
                            :disabled="!regAsignacion.fechasalida">Registrar Pago </button>
                        <button v-else class="btn btn-primary" @click="editarPago()" style="margin:5px">Editar Pago </button>
                    </center>
                </div>
            </div>
        </div>
    </div>

    <div class="row" v-if="!divAmbientes">
        <div class="col-md-6">
            <button class="btn btn-secondary btn-block" :disabled="!regAsignacion" style="margin:5px 0px"
            @click="reporteEntrada(regAsignacion.idasignacion)">Boleta de Entrada </button>
        </div>
        <div class="col-md-6">
            <button class="btn btn-secondary btn-block" :disabled="!regAsignacion.fechasalida" style="margin:5px 0px"
            @click="reporteSalida(regPago.idasignacion)">Boleta de Salida </button>
        </div>

    </div>
    
    


    <!-- MODAL ASIGNACIÓN MODAL ASIGNACIÓN MODAL ASIGNACIÓN MODAL ASIGNACIÓN MODAL ASIGNACIÓN MODAL ASIGNACIÓN  -->
    <!-- MODAL ASIGNACIÓN MODAL ASIGNACIÓN MODAL ASIGNACIÓN MODAL ASIGNACIÓN MODAL ASIGNACIÓN MODAL ASIGNACIÓN  -->
    <div class="modal" :class="{'mostrar':modalAsignar}" >
        <div class="modal-dialog modal-primary">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="regServicio.nomestablecimiento"></h4>
                    <button type="button" class="close" @click="modalAsignar=0">×</button>                    
                </div>
                <div class="modal-body">
                    <div class="text-center"><span class="nombrecliente" v-text="'CLIENTE: '+regCliente.nomcliente"></span> </div>
                    <div class="row" style="padding:10px 0px;">
                        <div class="col-md-4"><b>Cod. Pieza:</b> <span v-text="regAmbiente.codambiente"></span> </div>
                        <div class="col-md-4"><b>Tipo:</b> Individual</div>
                        <div class="col-md-4"><b>Precio Noche:</b> 
                            <span v-text="regCliente.tipocliente=='s'?regAmbiente.tarifasocio:regAmbiente.tarifareal"> </span>Bs.
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="titazul" v-text="'Registro de '+tituloAccion"></h5>
                            <div style="display:table; width:100%">
                                <div class="tfila">
                                    <div class="tcelda" style="vertical-align:middle">Fecha:</div>
                                    <div class="tcelda" style="padding:5px 0px">
                                        <input type="date" class="form-control" v-model="fecha">
                                    </div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda" style="vertical-align:middle">Hora:</div>
                                    <div class="tcelda" style="padding:5px 0px"><input type="time" class="form-control" v-model="hora"></div>
                                </div>
                            </div>
                            <div>Observaciones:<br>
                                <textarea v-if="tituloAccion=='Entrada'" class="form-control" v-model="obs1" rows="3"></textarea>
                                <textarea v-if="tituloAccion=='Salida'"  class="form-control" v-model="obs2" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5 class="titazul" v-text="tituloPrendas"></h5>
                            <ul style="list-style:none">
                                <li v-for="implemento in arrayImplementos" :key="implemento.idimplemento">
                                    <input type="checkbox" v-model="arrayEntregados" :value="implemento.idimplemento">
                                    <span v-text="implemento.nomimplemento"></span>
                                </li>
                            </ul>
                        </div>    
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="modalAsignar=0">Cerrar</button>
                    <button type="submit" v-if="accion==1" class="btn btn-primary" @click="storeAsignacion()">Guardar Entrada</button>
                    <button type="submit" v-if="accion==2" class="btn btn-primary" @click="updateAsignacion()">Guardar Cambios</button> 
                    
                    <button type="submit" v-if="accion==3" class="btn btn-primary" @click="storeSalida()">Guardar Salida</button> 
                    <button type="submit" v-if="accion==4" class="btn btn-primary" @click="storeSalida()">Guardar Cambios</button> 
                </div>
            </div>
        </div>
    </div>



    <!-- MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS  -->
    <!-- MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS  -->
    <!-- MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS  -->
    <!-- MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS  -->
    <div class="modal" :class="{'mostrar':modalPagos}" >
        <div class="modal-dialog modal-primary">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="'REGISTRAR PAGO '+regServicio.nomestablecimiento"></h4>
                    <button type="button" class="close" @click="modalPagos=0">×</button>                    
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="titazul">Consumo</h5>
                            <div class="tfila">
                                <div class="tcelda titcampo">Titular:</div>
                                <div class="tcelda"><span v-text="regCliente.nombre+' '+regCliente.apaterno"></span></div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Concepto:</div>
                                <div class="tcelda">Hospedaje</div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Estadía:</div>
                            </div>
                            <div class="text-right">
                                Del: <span v-text="(regAsignacion.fechaentrada)+'; '
                                +regAsignacion.horaentrada"></span> <br>
                                Al: <span v-text="(regAsignacion.fechasalida)+'; '
                                +regAsignacion.horasalida"></span>                                
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Periodo:</div>
                                <div class="tcelda"><span v-text="regPago.periodo"></span></div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Precio:</div>
                                <div class="tcelda"> 
                                    <span v-if="regCliente.tipocliente=='s'">
                                        <span v-text="regAmbiente.tarifasocio"></span>Bs./noche (Socio)
                                    </span>
                                    <span v-if="regCliente.tipocliente=='c'">
                                        <span v-text="regAmbiente.tarifareal"></span>Bs./noche (Civil)
                                    </span>
                                </div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Importe:</div>
                                <div class="tcelda"><span v-text="regPago.importe+'Bs.'"></span></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h5 class="titazul">Forma de pago</h5>
                            <div class="text-center"  style="padding:5px 0px">
                                <input type="checkbox" v-model="descuento" @change="pedirDescuento()">
                                Solicitud de descuento
                            </div>
                            <div style="display:table; width:100%">
                                <div class="tfila nowrap">
                                    <div class="tcelda" style="vertical-align:middle">Nro <span v-text="txtModopago"></span>: </div>
                                    <div class="tcelda" style="padding:5px 0px">
                                        <input type="text" class="form-control" v-model="nrdocumento"  @keyup="validarPago()">
                                    </div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda" style="vertical-align:middle">Fecha:</div>
                                    <div class="tcelda" style="padding:5px 0px" >
                                        <input type="date" class="form-control" v-model="fecha">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="modalPagos=0">Cerrar</button>
                    <button v-if="accion==1" :disabled="!completo" class="btn btn-primary" @click="storePago()">Registrar Pago</button>
                    <button v-if="accion==2" :disabled="!completo" class="btn btn-primary" @click="updatePago()">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>

    <ser_busqueda @clienteEncontrado="definirCliente($event)" ref="buscarCliente"></ser_busqueda>
</div><!-- divResidencial-->
</main>  
</template>

<script> 
import * as jsfechas from '../../fechas.js';
import * as reporte from '../../functions.js';

export default {
    data(){return { piso:1,
        divAmbientes:1, arrayAmbientes:[], arrayOcupantes:[], rutas:[], jsfechas:'',
        arrayImplementos:[], arrayEntregados:[], txtModopago:'', descuento:0,
        divNovalido:0, txtValidacion:'', completo:0,
        regAmbiente:[], regCliente:[], regServicio:[], regAsignacion:[], regPago:[],
        divBuscarcliente:0,  modalAsignar:0, modalPagos:0, modalReporte:0,
        accion:1, tituloAccion:'',tituloPrendas:'',
        fecha:'', hora:'', tipocliente:'', modopago:'', nrdocumento:'', obs1:'', obs2:'',
        idperfilcuentamaestro:'',
    }},

    methods:{
        cargarvue(servicio){
            $('#divResidencial').css('display','block');
            this.divAmbientes=1;
            this.regServicio=servicio;
            this.regAsignacion='';
            this.jsfechas=jsfechas;
            this.getRutasReports();
            this.cargarPiso(1);
        },

        cerrarvue(){
            $('#divResidencial').css('display','none');
        },

        cargarPiso(piso){
            this.piso=piso;
            let me=this;
            var url='/ser_asignacion/listaAmbientes?codservicio='+this.regServicio.codservicio
                +'&idestablecimiento='+this.regServicio.idestablecimiento+'&piso='+piso;
            axios.get(url).then(function(response){
                me.arrayAmbientes=response.data.ambientes;
            });
            var url='/ser_asignacion/listaOcupantes?idestablecimiento='+this.regServicio.idestablecimiento
            axios.get(url).then(function(response){
                me.arrayOcupantes=response.data.ocupantes;
            });
        },

        asignarCliente(ambiente)
        {   this.regAmbiente=ambiente;
            this.$refs.buscarCliente.cargarModal(this.regAmbiente);
        },

        definirCliente(cliente)
        {   
            if(cliente.nomgrado.length>1) var tipo='s'; 
            else var tipo='c';
            this.verCliente(cliente.idcliente,tipo);
            this.regAsignacion='';
            this.regPago='';
        },

        verCliente(idcliente,tipo){
            this.divAmbientes=0;
            this.divNovalido=0;
            let me=this;
            var url='/ser_asignacion/kardexCliente?idcliente='+idcliente+'&tipocliente='+tipo;
            axios.get(url).then(function(response){
                me.regCliente=response.data.cliente[0];
                me.regCliente.nomcliente=me.regCliente.apaterno+' '+me.regCliente.amaterno+' '+me.regCliente.nombre;
                if(tipo=='s') me.regCliente.nomcliente=me.regCliente.nomgrado+' '+me.regCliente.nomcliente;
                me.regCliente.ciexp=me.regCliente.ci+' '+me.regCliente.abrvdep;
                me.regCliente.tipocliente=tipo;
            });
        },

        verAsignacion(idasignacion,ambiente){
            this.divAmbientes=0;
            this.regAmbiente=ambiente;
            let me=this;
            var url='/ser_asignacion/verAsignacion?idasignacion='+idasignacion;
            axios.get(url).then(function(response){
                me.regAsignacion=response.data.asignacion[0];
                me.verCliente(me.regAsignacion.idcliente,me.regAsignacion.tipocliente);
                me.listaImplementos();
                me.arrayEntregados=JSON.parse('['+me.regAsignacion.identregados+']');
                me.verPago(me.regAsignacion.idasignacion);
            });  
        },

        verPago(idasignacion){
            url='/ser_pago/verPago?idasignacion='+idasignacion;
            let me=this;
            axios.get(url).then(function(response){
                me.regPago=response.data.pago[0];
                if(!response.data.pago.length) me.regPago=[];
            });
        },

        listaImplementos() {
            var url='/ser_asignacion/listaImplementos?idimplementos='+this.regServicio.idimplementos;
            let me=this;
            axios.get(url).then(function(response){
                me.arrayImplementos=response.data.implementos;
            });
        },

        validarPago(){
            this.completo=0;
            if((this.nrdocumento)&&(this.fecha)) this.completo=1;
        },

        registrarEntrada(cliente){
            this.modalAsignar=1;
            this.tituloAccion="Entrada";
            this.tituloPrendas="Prendas Entregadas";
            this.accion=1;
            this.listaImplementos();
            this.arrayEntregados=[];
            this.fecha=this.regCliente.currfecha;
            this.hora=this.regCliente.currhora;
        },

        editarEntrada(){
            this.modalAsignar=1;
            this.tituloAccion="Entrada";
            this.tituloPrendas="Prendas Entregadas";            
            this.accion=2;
            
            this.fecha=this.regAsignacion.fechaentrada;
            this.hora=this.regAsignacion.horaentrada;
            this.arrayEntregados=JSON.parse('['+this.regAsignacion.identregados+']');
            this.idimplementos=this.arrayImplementos;
            this.obs1=this.regAsignacion.obs1;
        },

        storeAsignacion(){
            this.modalAsignar=0;
            this.divAmbientes=0;
            let me=this;
            axios.post('/ser_asignacion/storeAsignacion',{
                'idcliente':this.regCliente.idcliente,
                'tipocliente':this.regCliente.tipocliente,
                'idambiente':this.regAmbiente.idambiente,
                'nrasignacion':'',
                'fechasolicitud':'',
                'mesboleta':'',
                'ocupantes':'',
                'iddocumentos':'',
                'identregados':this.arrayEntregados.join(),
                'fechaentrada':this.fecha,
                'horaentrada':this.hora,
                'horasalida':'',
                'fechadefuncion':'',
                'idresponsable':'',
                'actividad':'',
                'idrepresentante':'',
                'obs1':this.obs1
            }).then(function(){
                swal('Registro Correcto','Se ha registrado el ingreso','success');
                var url='/ser_asignacion/verAsignacion?idambiente='+me.regAmbiente.idambiente;
                //let me=this;
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
                'fechasolicitud':'',
                'mesboleta':'',
                'ocupantes':'',
                'iddocumentos':'',
                'identregados':this.arrayEntregados.join(),
                'fechaentrada':this.fecha,
                'horaentrada':this.hora,
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



        registrarSalida(){
            this.modalAsignar=1;
            this.tituloAccion="Salida";
            this.tituloPrendas="Prendas Recibidas";
            this.accion=3;
            this.fecha=this.regCliente.currfecha;
            this.hora=this.regCliente.currhora;
            this.arrayEntregados=JSON.parse('['+this.regAsignacion.identregados+']');
            //this.idimplemetos=this.arrayImplementos;
            
        },

        storeSalida(){
            this.modalAsignar=0;
            //var url='/ser_asignacion/storeSalida?idasignacion='+this.regAsignacion.idasignacion;
            var url='/ser_asignacion/updateAsignacion?idasignacion='+this.regAsignacion.idasignacion;
            let me=this;
            axios.put(url,{
                'idasignacion':this.regAsignacion.idasignacion,
                'nrasignacion':this.regAsignacion.nrasignacion,
                'fechaentrada':this.regAsignacion.fechaentrada,
                'horaentrada':this.regAsignacion.horaentrada,
                'fechasalida':this.fecha,
                'horasalida':this.hora,
                'identregados':this.arrayEntregados.join(),
                'obs2':this.obs2
            }).then(function(){
                swal('Registro correcto','Se ha registrado la salida','success');
                me.verAsignacion(me.regAsignacion.idasignacion,me.regAmbiente);
            });
        },

        editarSalida(){
            this.accion=4;
            this.modalAsignar=1;
            this.tituloAccion="Salida";
            this.tituloPrendas="Prendas Recibidas";
            this.fecha=this.regAsignacion.fechasalida;
            this.hora=this.regAsignacion.horasalida;
            this.obs2=this.regAsignacion.obs2;
        },

        pedirDescuento(){
            this.txtModopago='Factura';
            this.modopago=1;
            this.nrdocumento='';
            if(this.descuento){
                this.txtModopago='Préstamo';
                this.modopago=3;
                swal({
                    title:'Verificación',
                    html:'Para la modalidad de descuento, deberá generar el '
                        +'<br><font color=green><b>Código de Préstamo</b></font> ' 
                        +'con el responsable de dicho módulo',
                    type:'info',
                    confirmButtonText:'Entendido'});
            }
        },


        registrarPago(){
            this.modalPagos=1;
            this.completo=0;
            this.nrdocumento='';
            this.txtModopago='Factura';
            this.modopago=1;
            this.fecha=this.regCliente.currfecha;
            this.accion=1;
            var noches=this.regAsignacion.noches;
            if(this.regAsignacion.horasalida>'12:30') noches++;
            this.regPago.periodo=noches+' noches';
            if(this.regCliente.tipocliente=='s') this.regPago.importe=this.regAmbiente.tarifasocio*noches;
            if(this.regCliente.tipocliente=='c') this.regPago.importe=this.regAmbiente.tarifareal*noches;
        },

        editarPago(){
            this.modalPagos=1;
            this.verPago(this.regAsignacion.idasignacion);
            this.nrdocumento=this.regPago.nrdocumento.substr;
            if(this.modopago==1) this.nrdocumento=this.regPago.nrdocumento.substr(1,8);
            this.fecha=this.regPago.fecha;
            this.descuento=0;
            this.txtModopago='Factura';
            if(this.modopago==3) {
                this.descuento=1;
                this.txtModopago='Préstamo';
            }
            this.accion=2;
        },

        storePago(){
            this.modalPagos=0;
            this.idasientomaestro=1;
            var tipodoc='F'; if(this.modopago==3) var tipodoc='';
            if(tipodoc=='F') var nombredoc='Factura'; else var nombredoc='Préstamo';
            let me=this;
            axios.post('/ser_pago/storePago',{
                'idasignacion':this.regAsignacion.idasignacion,
                'concepto': 'Hospedaje',
                'periodo': this.regPago.periodo,
                'nrdocumento':tipodoc+this.nrdocumento,
                'modopago':this.modopago,
                'idperfilcuentamaestro':9,//this.idperfilcuentamaestro,//mientras funcione mi select
                'fecha':this.fecha,
                'importe':this.regPago.importe,
                'idresponsable':0,
                'glosa':'Hospedaje '+this.regPago.periodo+' '+' con '+nombredoc+' Nro.'+this.nrdocumento,
            }).then(function(){
                swal('Registro correcto','Se ha registrado correctamente el pago','success');
                me.verPago(me.regAsignacion.idasignacion);
            });
        },

        updatePago(){
            this.modalPagos=0;
            var tipodoc='F'; if(this.modopago==3) var tipodoc='';
            if(tipodoc=='F') var nombredoc='Factura'; else var nombredoc='Préstamo';
            let mee=this;
            axios.put('/ser_pago/updatePago',{
                'idpago':this.regPago.idpago,
                'concepto': 'Hospedaje',
                'periodo': this.regPago.periodo,
                'nrdocumento':tipodoc+this.nrdocumento,
                //'idasientomaestro':this.idasientomaestro,
                'modopago':this.modopago,                
                'importe':this.regPago.importe,
                'fecha':this.fecha,
                'idresponsable':0,
                'glosa':'Hospedaje '+this.regPago.periodo+' '+' con '+nombredoc+' Nro.'+this.nrdocumento,
            }).then(function(){
                swal('Actualizado','Se han guardado los cambios en el pago','success');
                me.verPago(me.regAsignacion.idasignacion);
            });
        },

        liberarAmbiente(){
            swal({
                title: 'Proceso Irreversible',
                html: 'Habilitará la disponibilidad del ambiente, '
                     +'verifique el cumplimiento de los <font color=red><b>pagos</b></font> '
                     +'u otros aspectos <font color=red><b>pendientes.</b></font>',
                type: 'warning',
                showCancelButton:'true',
                confirmButtonColor:'#f86c6b',
                confirmButtonText:'Habilitar Ambiente',
                cancelButtonText:'Cancelar',
                reverseButtons: true,
            }).then(confirmar=>{
                if(confirmar.value){
                    var url='/ser_asignacion/liberarAmbiente?idambiente='
                        +this.regAmbiente.idambiente+'&idasignacion='+this.regAsignacion.idasignacion;
                    let me=this;
                    axios.put(url).then(function(){
                        swal('Habilitado','Este ambiente está disponible para otros clientes.','success' );
                        me.divAmbientes=0;
                        me.cargarvue(me.regServicio);
                    });
                }
            });
        },




        reporteEntrada(idasignacion){
            var url=this.rutas.REP_ENTRADACC+idasignacion;
            reporte.viewPDF(url,'Boleta de Entrada');
        },

        reporteSalida(idasignacion){
            var url=this.rutas.REP_SALIDACC+idasignacion;
            reporte.viewPDF(url,'Boleta de Salida');
        },

        getRutasReports (){ 
            let me=this;
            var url= '/ser_asignacion/reportes';
            axios.get(url).then(function (response) { 
                me.rutas = response.data;   
            });
        },
    },

}
</script>
