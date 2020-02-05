<template>
<main>
    <div class="card" v-if="divAsignaciones">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10 titcard">
                    <div class="tablatit">
                        <div class="tcelda">HABITACIONES <span v-text="(arrayOrdinal[nrgrupo]).toUpperCase()"></span> PISO</div>
                    </div>
                </div>  
                <div class="col-md-2 text-right"> 
                    <div class="input-group-append" style="display:inline">
                        <button class="btn btn-primary dropdown-toggle" style="margin-top:0"
                            data-toggle="dropdown" aria-expanded="false">
                            Piso...<span class="caret"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                            <a href="#" class="dropdown-item" v-for="k in cantgrupos" :key="k" 
                                v-text="'Piso '+k" @click="listaAmbientes(regEstablecimiento.idestablecimiento,k)"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
        <div class="card-body table-responsive" v-if="divAsignaciones">
            <table class="table table-striped table-sm">
                <thead class="tcabecera">
                    <tr>
                        <th>PIEZA</th>
                        <th>Descripción</th>
                        <th>Tarifas</th>
                        <th>Ocupantes</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="ambiente in arrayAmbientes" :key="ambiente.id">
                        <td class="card-header titcard" v-text="ambiente.codambiente" align="center"></td>
                        <td><span style="display:none"> {{c=1}}</span>
                            <span v-text="ambiente.tipo"></span><br>
                            <span v-text="ambiente.capacidad"></span> personas
                        </td>
                        <td>Socios: <span v-text="ambiente.tarifasocio"></span>Bs,<br>
                            Externos: <span v-text="ambiente.tarifareal"></span>Bs,
                        </td>
                        <td> 
                            <!-- <div class="tabla100">                               
                                <div class="tfila bloquefila" v-for="ocupante in arrayOcupantes" :key="ocupante.id">
                                    <template v-if="ocupante.idambiente==ambiente.idambiente"> 
                                        <div class="tcelda" > {{c++}}.
                                            <span v-text="ocupante.nomgrado+' '+ocupante.nombre+' '+ocupante.apaterno"></span></div>
                                        <div class="tcelda"><span v-text="jsfechas.fechadia(ocupante.fechaentrada)"></span></div>
                                        <div class="tcelda"><span v-text="ocupante.horaentrada"></span></div>
                                        <div class="tcelda text-right">
                                            <span v-text="ocupante.currhora<'12:31:00'?(ocupante.noches):(ocupante.noches+1)"></span> noches
                                        </div>
                                        <div class="tcelda text-right" style="padding:5px 0px">
                                            <button class="btn btn-warning btn-sm icon-book-open" 
                                                @click="verAsignacion(ocupante.idasignacion,ambiente)"></button> 
                                        </div>
                                    </template>
                                </div>
                            </div>  -->

                            <div class="tabla100">                               
                                <div class="tfila bloquefila" v-for="asignacion in arrayAsignaciones" :key="asignacion.id">
                                    <template v-if="asignacion.idambiente==ambiente.idambiente"> 
                                        <div class="tcelda" > {{c++}}.
                                            <span v-text="asignacion.nomgrado+' '+asignacion.nombre+' '+asignacion.apaterno"></span></div>
                                        <div class="tcelda"><span v-text="jsfechas.fechadia(asignacion.fechaentrada)"></span></div>
                                        <div class="tcelda"><span v-text="asignacion.horaentrada"></span></div>
                                        <div class="tcelda text-right">
                                            <span v-text="asignacion.currhora<'12:31:00'?(asignacion.noches):(asignacion.noches+1)"></span> noches
                                        </div>
                                        <div class="tcelda text-right" style="padding:5px 0px">
                                            <button class="btn btn-warning btn-sm icon-book-open" title="Ficha de ocupación"
                                                @click="verAsignacion(asignacion)"></button> 
                                        </div>
                                    </template>
                                </div>
                            </div> 
                            <div v-if="c<=ambiente.capacidad" class="bloquefila text-center noprint" style="padding:8px; cursor:pointer">
                                <a href="#" @click="nuevaAsignacion(ambiente)"> Agregar Huésped</a>
                            </div>                            
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>        
    </div>

    <!-- FICHA ASIGNACION  FICHA ASIGNACION FICHA ASIGNACION FICHA ASIGNACION FICHA ASIGNACION -->
    <!-- FICHA ASIGNACION  FICHA ASIGNACION FICHA ASIGNACION FICHA ASIGNACION FICHA ASIGNACION -->
    <div class="row" v-if="!divAsignaciones">
        <div class="col-md-4"><!-- HUESPED -->
            <div class="card">
                <div class="card-header titcard">Huésped</div>
                <div class="card-body">
                    <h4 class="titmodulo text-center">
                        <span v-text="regCliente.nomgrado"></span>
                        <span v-text="regCliente.nombre"></span>
                        <span v-text="regCliente.apaterno"></span>
                        <span v-text="regCliente.amaterno"></span>
                    </h4>
                    <center>
                        <span class="titcampo">Fuerza:</span>
                        <span v-text="regCliente.nomfuerza">Fuerza:</span>
                    </center>
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <span class="titcampo">CI:</span>
                            <span v-text="regCliente.ci+regCliente.abrvdep"></span>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <span class="titcampo">Celular:</span>
                            <span v-text="regCliente.telcelular"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header titcard">HABITACIÓN</div>
                <div class="card-body">
                    <div class="tabla100">
                        <div class="tfila">
                            <div class="tcelda titcampo">Código:</div>
                            <div class="tcelda">
                                <span v-text="regAmbiente.codambiente"></span> -
                                <span v-text="regAmbiente.piso+(arrayOrdinal[regAmbiente.piso]).substr(-2)"></span> Piso
                            </div>
                        </div>
                        <div class="tfila">
                            <div class="tcelda titcampo">Tipo:</div>
                            <div class="tcelda" v-text="regAmbiente.tipo"></div>
                        </div>
                        <div class="tfila">
                            <div class="tcelda titcampo">Tarifa:</div>
                            <div class="tcelda"> 
                                <span v-text="regAsignacion.tarifa"></span>Bs/Noche
                                (<span v-text="regAsignacion.cliente"></span>)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4"><!-- HOSPEDAJE -->
            <div class="card">
                <div class="card-header titcard">
                    <div class="row">
                        <div class="col-md-6 col-sm-6">Hospedaje</div>
                        <div class="col-md-6 col-sm-6 text-right">Nro 10000</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tabla100">
                        <div class="tfila bloquefila">
                            <div class="tcelda">
                                <span class="titcampo">Entrada:</span>
                                <center>
                                    <span v-text="jsfechas.fechadia(regAsignacion.fechaentrada)"></span>,&nbsp;
                                    <span v-text="regAsignacion.horaentrada"></span>
                                </center>
                            </div>
                            <div class="tcelda text-right">
                                <button class="btn btn-primary icon-pencil" :disabled="regAsignacion.fechasalida" 
                                    @click="editarAsignacion()"></button>
                            </div>
                        </div>
                        <div class="tfila">&nbsp;</div>
                        <div class="tfila bloquefila">
                            <div class="tcelda">
                                <span class="titcampo">Salida:</span>
                                <center>
                                    <span v-text="jsfechas.fechadia(regAsignacion.fechasalida)"></span>,&nbsp;
                                    <span v-text="regAsignacion.horasalida"></span>
                                </center>
                            </div>
                            <div class="tcelda text-right">
                                <span style="display:none">{{op=regAsignacion.fechasalida?2:1}}</span> 
                                <button class="btn btn-primary icon-pencil" :disabled="regPago.idpago"
                                @click="editarAsignacion(op)"></button>
                            </div>
                        </div>
                    </div><br>
                    <span class="titcampo">Implementos:</span>
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <span style="display:none">{{mitad=Math.floor(arrayImplementos.length/2)}}</span>
                            <ul style="list-style:none; padding-left:5px">
                                <li v-for="(implemento,index) in arrayImplementos" :key="implemento.idimplemento" >
                                    <template v-if="index<=mitad">
                                        <input type="checkbox" v-model="arrayIDimplementos" :value="implemento.idimplemento" disabled> 
                                        <span style="text-transform:capitalize" v-text="implemento.nomimplemento"></span>
                                    </template>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <ul style="list-style:none; padding-left:0px">
                                <li v-for="(implemento,index) in arrayImplementos" :key="implemento.idimplemento" >
                                    <template v-if="index>mitad">
                                        <input type="checkbox" v-model="arrayIDimplementos" :value="implemento.idimplemento" disabled> 
                                        <span style="text-transform:capitalize" v-text="implemento.nomimplemento"></span>
                                    </template>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-12" v-if="regAsignacion.obs1">
                            <span class="titcampo">OBS:</span> <span v-text="regAsignacion.obs1"></span><br><br>
                        </div>
                    </div>
                    <center><button class="btn btn-primary" @click="reporteEntrada()">Boleta de Entrada</button></center>
                </div>
            </div>
        </div>
        <div class="col-md-4"><!-- PAGO -->
            <div class="card">
                <div class="card-header titcard">Resumen</div>
                <div class="card-body">
                    <div v-if="regAsignacion.fechasalida">
                        <h4 class="titsubrayado">Estadía</h4> 
                        <div class="tablacen">
                            <div class="tfila">
                                <div class="tcelda titcampo">Huésped:</div>
                                <div class="tcelda" v-text="regCliente.nombre+' '+regCliente.apaterno"></div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Ingreso:</div>
                                <div class="tcelda">
                                    <span v-text="jsfechas.fechadia(regAsignacion.fechaentrada)"></span>, 
                                    <span v-text="regAsignacion.horaentrada"></span>
                                </div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Salida:</div>
                                <div class="tcelda">
                                    <span v-text="jsfechas.fechadia(regAsignacion.fechasalida)"></span>, 
                                    <span v-text="regAsignacion.horasalida"></span>
                                </div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Estadía:</div>
                                <div class="tcelda" v-text="regAsignacion.noches+' noches'"></div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Tarifa:</div>
                                <div class="tcelda"><span v-text="regAsignacion.tarifa"></span>Bs/noche
                                (<span v-text="regAsignacion.cliente"></span>)
                                </div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">A cancelar:</div>
                                <div class="tcelda"><span v-text="regAsignacion.tarifa*regAsignacion.noches"></span>Bs
                                </div>
                            </div>
                        </div><br>
                    </div>

                    <div v-if="regPago.idpago">
                        <h4 class="titsubrayado">Facturación</h4>
                        <div class="tablacen">
                            <div class="tfila">
                                <div class="tcelda titcampo">Nro Factura:</div>
                                <div class="tcelda" v-text="regPago.nrdocumento"></div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">NIT / CI:</div>
                                <div class="tcelda" v-text="regPago.nit"></div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Razón Social:</div>
                                <div class="tcelda" v-text="regPago.razon"></div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Importe:</div>
                                <div class="tcelda"><span v-text="regPago.importe"></span>Bs.</div>
                            </div>
                        </div>
                        <!-- <center v-if="regPago.modopago==3">PAGO CON DESCUENTO</center> -->
                        <!-- <center v-if="regPago.modopago==3" class="alert alert-danger">PAGO CON DESCUENTO</center> -->
                        <div v-if="regPago.modopago==3" style="background-color:#f86c6b; color:#fff; text-align:center; padding:5px; font-weight:bold">
                            PAGO CON DESCUENTO
                        </div>
                    </div>
                    
                    <center>
                        <button v-if="regAsignacion.fechasalida" class="btn btn-primary" 
                            @click="regPago.idpago?editarPago():nuevoPago()">
                            <span v-text="regPago.idpago?'Editar':'Registrar'"></span> Pago</button>

                            
                        <button v-if="regPago.idpago" class="btn btn-primary" 
                            @click="reporteSalida()">Boleta de Salida</button>
                        
                        <button v-if="regPago.idpago" class="btn btn-success">Liberar Ambiente</button>
                    </center>
                </div>
            </div>
        </div>    
    </div>

    <!-- MODAL ASIGNACION  MODAL ASIGNACION MODAL ASIGNACION MODAL ASIGNACION MODAL ASIGNACION -->
    <!-- MODAL ASIGNACION  MODAL ASIGNACION MODAL ASIGNACION MODAL ASIGNACION MODAL ASIGNACION -->
    <div class="modal" :class="modalAsignacion?'mostrar':''">
        <div class="modal-dialog modal-primary">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="titModal.toUpperCase()"></h4>
                    <button class="close" @click="modalAsignacion=0">x</button>
                </div>
                <div class="modal-body" style="overflow-y:scroll; height:400px;">
                    <h4 class="titsubrayado" v-text="regEstablecimiento.nomestablecimiento"></h4>
                    <div class="row">
                        <div class="col-md-6">
                            <span class="titcampo">Pieza:</span> <span v-text="regAmbiente.codambiente"></span>
                            (<span v-text="regAmbiente.tipo"></span>)
                        </div>
                        <div class="col-md-6 text-right">
                            <span class="titcampo">Tarifa:</span>
                            <span v-text="nosocio?regAmbiente.tarifareal:regAmbiente.tarifasocio"></span>Bs/noche
                            (<span v-text="nosocio?'Particular':'Socio'"></span>)
                        </div>
                    </div>
                    <div v-if="!regAsignacion.idasignacion"><br>
                        <div class="tfila">
                            <div class="tcelda">Cliente:</div>
                            <div class="tcelda tinput text-right nowrap">
                                <input type="checkbox" v-model="nosocio">Cliente particular
                            </div>
                        </div>
                        <autocomplete @encontrado="verIDcliente($event)" ></autocomplete>
                    </div>
                    <h4 v-if="regAsignacion.idcliente" class="titsubrayado"><br>
                        <span v-text="regCliente.nomgrado"></span> <span v-text="regCliente.nombre"></span>
                        <span v-text="regCliente.apaterno"></span> <span v-text="regCliente.amaterno"></span>
                    </h4><br>

                    <div class="row">
                        <div class="col-md-4">
                            <h4 class="titazul" v-text="titModal"></h4>
                            Fecha <input type="date" class="form-control" v-model="fecha">
                            Hora <input type="time" class="form-control" v-model="hora">
                        </div>
                        <div class="col-md-8"> 
                            <h4 class="titazul">Prendas</h4>
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <span style="display:none">{{mitad=Math.floor(arrayImplementos.length/2)}}</span>
                                    <ul style="list-style:none; padding-left:10px">
                                        <li v-for="(implemento,index) in arrayImplementos" :key="implemento.idimplemento" >
                                            <template v-if="index<=mitad">
                                                <input type="checkbox" :value="implemento.idimplemento" 
                                                    v-model="arrayIDimplementos">
                                                <span style="text-transform:capitalize" v-text="implemento.nomimplemento"></span>
                                            </template>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <ul style="list-style:none; padding-left:10px">
                                        <li v-for="(implemento,index) in arrayImplementos" :key="implemento.idimplemento" >
                                            <template v-if="index>mitad">
                                                <input type="checkbox" :value="implemento.idimplemento" v-model="arrayIDimplementos">
                                                <span style="text-transform:capitalize" v-text="implemento.nomimplemento"></span>
                                            </template>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>                        
                        <div class="col-md-12">
                            Observaciones: <input type="text" class="form-control" v-model="obs">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="modalAsignacion=0">Cerrar</button>
                    <button class="btn btn-primary" @click="accion==1?storeAsignacion():updateAsignacion()">
                        Guardar <span v-text="titModal"></span></button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PAGOS  MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS -->
    <!-- MODAL PAGOS  MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS -->
    <div class="modal" :class="modalPago?'mostrar':''">
        <div class="modal-dialog modal-primary modal-sm">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title"><span v-text="accion==1?'Registrar':'Modificar'"></span> Pago</h4>
                    <button class="close" @click="modalPago=0">x</button>
                </div>
                <div class="modal-body">
                            
                                        Nro Factura:
                                        <input type="text" class="form-control" v-model="nrdocumento"  @keyup="validarPago()">
                                        Fecha:                                    
                                        <input type="date" class="form-control" v-model="fecha">
                                        NIT/CI:
                                        <input type="text" class="form-control" v-model="nit" 
                                            @input="razon=''" @blur="verRazon(nit)" @keyup="validarPago()">
                                        Razón Social:                                   
                                        <input type="text" class="form-control" v-model="razon"  @keyup="validarPago()">
                                        <br>Concepto:
                                        <br>Importe:

                            
                            <center class="taltura"><input type="checkbox" v-model="descuento"> Pago con descuento</center>

                    <!--
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="titazul">Detalle</h4>
                            <div class="tfila">
                                <div class="tcelda titcampo">Titular:</div>
                                <div class="tcelda"><span v-text="regCliente.nombre+' '+regCliente.apaterno"></span></div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Ingresó:</div>
                                <div class="tcelda">
                                    <span v-text="regAsignacion.fecha1"></span>; <span v-text="regAsignacion.horaentrada"></span>
                                </div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Salió:</div>
                                <div class="tcelda">
                                    <span v-text="regAsignacion.fecha2"></span>; <span v-text="regAsignacion.horasalida"></span>
                                </div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Estadía:</div>
                                <div class="tcelda"><span v-text="regAsignacion.noches"></span> noches</div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Tarifa:</div>
                                <div class="tcelda"> 
                                    <span v-text="regAsignacion.tarifa"></span>Bs/noche
                                    (<span v-text="regAsignacion.cliente"></span>)
                                </div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Importe:</div>
                                <div class="tcelda"><span v-text="regPago.importe"></span>Bs.</div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo" style="vertical-align:top">Son:</div>
                                <div class="tcelda"><span v-text="regPago.literal"></span></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4 class="titazul">Facturación</h4>
                            <div class="ttable">
                                <div class="tfila">
                                    <div class="tcelda taltura">Nro Factura:</div>
                                    <div class="tcelda tinput">
                                        <input type="text" class="form-control" v-model="nrdocumento"  @keyup="validarPago()">
                                    </div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda taltura">Fecha:</div>
                                    <div class="tcelda tinput">
                                        <input type="date" class="form-control" v-model="fecha">
                                    </div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda taltura">NIT/CI:</div>
                                    <div class="tcelda tinput">
                                        <input type="text" class="form-control" v-model="nit" 
                                            @input="razon=''" @blur="verRazon(nit)" @keyup="validarPago()">
                                    </div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda taltura nowrap">Razón Social:</div>
                                    <div class="tcelda tinput">
                                        <input type="text" class="form-control" v-model="razon"  @keyup="validarPago()">
                                    </div>
                                </div>
                            </div>
                            <center class="taltura"><input type="checkbox" v-model="descuento"> Pago con descuento</center>
                        </div>
                    </div>
                    -->
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="modalPago=0">Cerrar</button>
                    <button class="btn btn-primary" @click="accion==1?storePago():updatePago()">
                        Guardar <span v-if="accion==2">Modificaciones</span></button>
                </div>
            </div>
        </div>
    </div>
</main>
</template>

<script>
import * as jsfechas from '../../fechas.js';
import * as literal from '../../literal.js';
import * as factura from '../../factura.js';
import * as reporte from '../../functions.js';

export default {
    props:["regEstablecimiento"],

    data(){ return { 
        modalAsignacion:0, modalPago:0, titModal:'', accion:'', jsfechas:'', nosocio:'', 
        ipbirt:'', currfecha:'', currhora:'',
        divAsignaciones:1, nrgrupo:1, cantgrupos:'', tarifa:'',
        idpago:'',concepto:'', periodo:'', modopago:'', 
        idasignacion:'', idcliente:'', tipocliente:'', fecha:'', hora:'', obs:'',
        idpago:'', nrdocumento:'', nit:'', razon:'', fecha:'', importe:'', descuento:'',
        arrayAmbientes:[], arrayOcupantes:[], arrayAsignaciones:[],
        arrayImplementos:[],  arrayIDimplementos:[], 
        regAmbiente:[], regCliente:[], regAsignacion:[], regPago:[], 
        arrayOrdinal:['','Primer','Segundo','Tercer','Cuarto','Quinto','Sexto','Séptimo'],
    }},

    methods:{
        listaAmbientes(idestablecimiento,nrgrupo){
            this.nrgrupo=nrgrupo;
            var url='/ser_ambiente/listaAmbientes?idestablecimiento='+idestablecimiento+'&piso='+nrgrupo;
            axios.get(url).then(response=>{
                this.arrayAmbientes=response.data.ambientes;
            });
            url='/ser_asignacion/listaAsignaciones?idestablecimiento='+idestablecimiento+'&piso='+nrgrupo;
            axios.get(url).then(response=>{
                this.arrayAsignaciones=response.data.asignaciones;
                this.ipbirt=response.data.ipbirt;
                this.currfecha=response.data.currfecha;
                this.currhora=response.data.currhora;
            });
        },

        listaImplementos(){
            var url='ser_implemento/listaImplementos?activo=1';
            axios.get(url).then(response=>{
                this.arrayImplementos=response.data.implementos;
            })
        },

        verIDcliente(idcliente){ this.idcliente=idcliente; },

        async verAsignacion(asignacion){
            let response=await axios.get('/ser_asignacion/verAsignacion?idasignacion='+asignacion.idasignacion);
            this.regAsignacion=response.data.asignacion[0];
            response=await axios.get('/ser_ambiente/listaAmbientes?idambiente='+asignacion.idambiente);
            this.regAmbiente=response.data.ambientes[0];
            response=await axios.get('/ser_asignacion/verCliente?idcliente='+asignacion.idcliente+'&tipocliente='+asignacion.tipocliente);
            this.regCliente=response.data.cliente[0];
            this.regAsignacion.tarifa=asignacion.tipocliente=='s'?this.regAmbiente.tarifasocio:this.regAmbiente.tarifareal;
            this.regAsignacion.cliente=asignacion.tipocliente=='s'?'Socio':'Externo';
            this.arrayIDimplementos=JSON.parse('['+this.regAsignacion.idimplementos+']');
            this.verPago(asignacion.idasignacion);
            this.divAsignaciones=0;
        },

        nuevaAsignacion(ambiente){
            this.regAmbiente=ambiente;
            this.arrayIDimplementos=[1,2,3,4,5,6,7,8,9];
            this.fecha=this.currfecha;
            this.hora=this.currhora;
            this.idcliente='';
            this.obs='';
            this.modalAsignacion=1;
            this.titModal='Entrada';
            this.accion=1;
        },

        editarAsignacion(op){            
            switch(op){
                case 1: //regsalida
                    this.fecha=this.regCliente.currfecha;
                    this.hora=this.regCliente.currhora;
                    break;
                case 2: //editsalida
                    this.fecha=this.regAsignacion.fechasalida;
                    this.hora=this.regAsignacion.horasalida;
                    break;
                default: //editentrada
                    this.fecha=this.regAsignacion.fechaentrada;
                    this.hora=this.regAsignacion.horaentrada;
                    break;
            }
            this.titModal=op?'Salida':'Entrada';
            this.idasignacion=this.regAsignacion.idasignacion;
            this.arrayIDimplementos=JSON.parse('['+this.regAsignacion.idimplementos+']');
            this.obs=this.regAsignacion.obs1;
            this.modalAsignacion=1;
            this.accion=2;
        },

        storeAsignacion(){
            swal({ title:'Procesando...',text:'Un momento por favor', type:'warning',
                showCancelButton:false, showConfirmButton:false, 
                allowOutsideClick: false, allowEscapeKey: false, allowEnterKey: false,
                onOpen:() => { swal.showLoading() }
            });             
            axios.post('/ser_asignacion/storeAsignacion',{
                'idcliente':this.idcliente,
                'tipocliente':this.nosocio?'c':'s',
                'idambiente':this.regAmbiente.idambiente,
                'nrasignacion':'',
                'fechasolicitud':'',
                'mesboleta':'',
                'ocupantes':'',
                'iddocumentos':'',
                'idimplementos':this.arrayIDimplementos.join(),
                'fechaentrada':this.fecha,
                'horaentrada':this.hora,
                'horasalida':'',
                'fechadefuncion':'',
                'idresponsable':'',
                'idrepresentante':'',
                'obs1':this.obs
            }).then(response=>{
                swal('Asignación creada','Proceda a la verificación de pagos','success');
                this.listaAmbientes(this.regAmbiente.idestablecimiento,this.nrgrupo);
                this.modalAsignacion=0;
            });
        },

        updateAsignacion(){
            axios.put('/ser_asignacion/updateAsignacion',{
                'idasignacion':this.idasignacion,
                'nrasignacion':this.regAsignacion.nrasignacion,
                'fechasolicitud':'',
                'mesboleta':'',
                'ocupantes':'',
                'iddocumentos':'',
                'idimplementos':this.arrayIDimplementos.join(),
                'fechaentrada':(this.titModal=='Entrada'?this.fecha:this.regAsignacion.fechaentrada),
                'horaentrada': (this.titModal=='Entrada'?this.hora:this.regAsignacion.horaentrada),
                'fechasalida': (this.titModal=='Salida'?this.fecha:this.regAsignacion.fechasalida),
                'horasalida':  (this.titModal=='Salida'?this.hora:this.regAsignacion.horasalida),
                'fechadefuncion':'',
                'idresponsable':'',
                'obs1':this.obs
            }).then(response=>{
                swal('Datos actualizados','','success');
                //this.verAsignacion(this.idasignacion,this.regAmbiente);
                this.verAsignacion(this.regAsignacion);
                this.modalAsignacion=0;
            });
        },

        verPago(idasignacion){
            url='/ser_pago/verPago?idasignacion='+idasignacion;
            axios.get(url).then(response=>{
                this.regPago=response.data.pago[0];
                if(!response.data.pago.length) this.regPago=[];
            });
        },

        verRazon(nit){
            axios.get('/ser_pago/listaPagos?nit='+nit).then(response=>{
                this.razon=response.data.pagos[0].razon;
            });
        },

        nuevoPago(){
            this.modalPago=1;
            this.accion=1;
            this.nrdocumento='';            
            this.regAsignacion.fecha1=jsfechas.fechadia(this.regAsignacion.fechaentrada);
            this.regAsignacion.fecha2=jsfechas.fechadia(this.regAsignacion.fechasalida);
            this.fecha=this.regCliente.currfecha;
            this.nit=this.regCliente.ci;
            this.razon=this.regCliente.apaterno;
            if(this.regCliente.currhora>'12:30') this.regAsignacion.noches++;
            this.regPago.concepto='Hospedaje '+this.regAsignacion.noches+' noches';
            this.regPago.importe=this.regAsignacion.tarifa*this.regAsignacion.noches;
            this.regPago.literal=literal.numero_a_literal(this.regPago.importe);
        },

        editarPago(){
            this.modalPago=1;
            this.accion=2;
            this.idpago=this.regPago.idpago;
            this.nrdocumento=this.regPago.nrdocumento;
            this.fecha=this.regPago.fecha;
            this.nit=this.regPago.nit;
            this.razon=this.regPago.razon;
            this.descuento=this.regPago.modopago==3?true:false;
            this.regAsignacion.fecha1=jsfechas.fechadia(this.regAsignacion.fechaentrada);
            this.regAsignacion.fecha2=jsfechas.fechadia(this.regAsignacion.fechasalida);
            this.regPago.literal=literal.numero_a_literal(this.regPago.importe);
        },

        storePago(){
            swal({ title:'Procesando...',text:'Un momento por favor', type:'warning',
                showCancelButton:false, showConfirmButton:false, 
                allowOutsideClick: false, allowEscapeKey: false, allowEnterKey: false,
                onOpen:() => { swal.showLoading() }
            });              
            var glosa='Hospedaje '+this.regAsignacion.noches+' noches '+' con Factura Nro.'+this.nrdocumento;
            axios.post('/ser_pago/storePago',{
                'idasignacion':this.regAsignacion.idasignacion,
                'concepto': 'Hospedaje',
                'periodo': this.regAsignacion.noches,
                'nit':this.nit,
                'razon':this.razon,
                'nrdocumento':this.nrdocumento,
                'modopago':this.descuento?3:1,
                'idperfilcuentamaestro':this.descuento?8:9,
                'fecha':this.fecha,
                'importe':this.regPago.importe,
                'glosa':glosa,
                'idresponsable':'',
                'idfilial':this.regEstablecimiento.idfilial,
                'numpapeleta':this.regCliente.numpapeleta
            }).then(response=>{
                swal('Pago guardado','','success');
                this.modalPago=0;
                this.verPago(this.regAsignacion.idasignacion);
                var detalle=glosa+'|'+this.regPago.importe+'|1|'+this.regPago.importe;
                //factura.genera(this.nit,this.razon,detalle,this.regPago.importe);
            });
        },

        updatePago(){
            var glosa='Hospedaje '+this.regAsignacion.noches+' noches '+' con Factura Nro.'+this.nrdocumento;
            axios.put('/ser_pago/updatePago',{
                'idpago':this.regPago.idpago,
                'concepto': 'Hospedaje',
                'periodo': this.regAsignacion.noches,
                'nit':this.nit,
                'razon':this.razon,
                'nrdocumento':this.nrdocumento,
                'modopago':this.descuento?3:1,
                'idperfilcuentamaestro':this.descuento?8:9,
                'fecha':this.fecha,
                'importe':this.regPago.importe,
                'idresponsable':'',
                'glosa':glosa,
            }).then(response=>{
                swal('Datos actualizados','','success');
                this.modalPago=0;
                this.verPago(this.regAsignacion.idasignacion);
            });
        },

        validarPago(){

        },



        reporteEntrada(){
            var url=[];
            url.push('http://'+this.ipbirt+':8080');
            url.push('/birt-viewer/frameset?__report=reportes/servicios');
            url.push('/ser_casacomentrada.rptdesign'); 
            url.push('&idasignacion='+this.regAsignacion.idasignacion); 
            url.push('&__format=pdf'); 
            reporte.viewPDF(url.join(''),'Boleta de Entrada');
        },

        reporteSalida(){
            var url=[];
            url.push('http://'+this.ipbirt+':8080');
            url.push('/birt-viewer/frameset?__report=reportes/servicios');
            url.push('/ser_casacomsalida.rptdesign'); 
            url.push('&idasignacion='+this.regAsignacion.idasignacion); 
            url.push('&__format=pdf'); 
            reporte.viewPDF(url.join(''),'Boleta de Salida');
        },

    },

    mounted(){
        this.jsfechas=jsfechas;
        this.cantgrupos=this.regEstablecimiento.cantgrupos*1;
        this.listaAmbientes(this.regEstablecimiento.idestablecimiento,1);
        this.listaImplementos();        
    }
}
</script>
