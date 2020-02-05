<template>
<main>
    <div class="card" v-if="divAsignaciones">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10 titcard">
                    <div class="tablatit">
                        <div class="tcelda">RESERVAS PROGRAMADAS</div>
                    </div>
                </div>
                <div class="col-md-2 text-right">
                    <button class="btn btn-primary" style="margin-top:0" @click="nuevaAsignacion()">Nueva Reserva</button>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped table-sm">
                <thead class="tcabecera">
                    <tr>
                        <th>Nr Contrato</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Actividad</th>
                        <th>Pagos</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="asignacion in arrayAsignaciones" :key="asignacion.id">
                        <td v-text="asignacion.nrasignacion" align="center"></td>
                        <td align="center">
                            <span v-text="jsfechas.fechadia(asignacion.fechaentrada)"></span><br>
                            <span v-text="asignacion.horaentrada?asignacion.horaentrada.substr(0,5):''"></span> -
                            <span v-text="asignacion.horasalida?asignacion.horasalida.substr(0,5):''"></span>
                        </td>
                        <td><span v-text="asignacion.nomgrado"></span>
                            <span v-text="asignacion.nombre"></span>
                            <span v-text="asignacion.apaterno"></span><br>
                            Cel: <span v-text="asignacion.telcelular"></span>
                        </td>
                        <td v-text="asignacion.descripcion"></td>
                        <td></td>
                        <td>
                            <button class="btn btn-warning icon-book-open" @click="verAsignacion(asignacion)"></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- FICHA ASIGNACION  FICHA ASIGNACION  FICHA ASIGNACION  FICHA ASIGNACION  FICHA ASIGNACION -->
    <!-- FICHA ASIGNACION  FICHA ASIGNACION  FICHA ASIGNACION  FICHA ASIGNACION  FICHA ASIGNACION -->
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
                    </div><br>
                    <center><button class="btn btn-primary" @click="reporteEntrada()">Boleta de Entrada</button></center>
                </div>
            </div>
        </div>
        <div class="col-md-4"><!-- PAGO -->
            <div class="card">
                <div class="card-header titcard">Pago</div>
                <div class="card-body">
                    <div v-if="regPago.idpago">
                        <h4 class="titsubrayado">Resumen</h4>
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
                        </div><br>
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
                            PAGO CON DESCUENTO</div>
                        <br>
                    </div>
                    <center>
                        <button v-if="regAsignacion.fechasalida" class="btn btn-primary" 
                            @click="regPago.idpago?editarPago():nuevoPago()">
                            <span v-text="regPago.idpago?'Editar':'Registrar'"></span> Pago</button>
                        <button v-if="regPago.idpago" class="btn btn-primary" 
                            @click="reporteSalida()">Boleta de Salida</button>
                    </center>
                </div>
            </div>
        </div>    
    </div>    

    <!-- MODAL ASIGNACION  MODAL ASIGNACION  MODAL ASIGNACION  MODAL ASIGNACION  MODAL ASIGNACION -->
    <!-- MODAL ASIGNACION  MODAL ASIGNACION  MODAL ASIGNACION  MODAL ASIGNACION  MODAL ASIGNACION -->
    <div class="modal" :class="modalAsignacion?'mostrar':''">
        <div class="modal-dialog modal-primary">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span v-text="accion==1?'Nueva':'Modificar'"></span> Reserva</h4>
                    <button class="close" @click="modalAsignacion=0">x</button>
                </div>
                <div class="modal-body">
                    <h4 class="titsubrayado" v-text="regEstablecimiento.nomestablecimiento"></h4>
                    <div class="row">
                        <div class="col-md-6">
                            <span class="titcampo">Actividad:</span> <span v-text="regAmbiente.descripcion"></span>
                        </div>
                        <div class="col-md-6 text-right">
                            <span class="titcampo">Tarifa:</span>
                            <span v-text="nosocio?regAmbiente.tarifareal:regAmbiente.tarifasocio"></span>
                            Bs<span v-if="regAmbiente.porhora">/hora</span>
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
                        <div class="col-md-6">
                            Nr. Contrato: <span class="txtasterisco"></span>
                            <input type="text" class="form-control" v-model="nrasignacion" @keyup="validarAsignacion()">
                            <div v-if="regAmbiente.porhora">Actividad: 
                                <select class="form-control">
                                    <option v-for="ambiente in arrayAmbientes" :key="ambiente.id"
                                        v-text="ambiente.descripcion"></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            Fecha Contrato: <span class="txtasterisco"></span>
                            <input type="date" class="form-control" v-model="fechaentrada" @change="validarAsignacion()">
                            <div v-if="regAmbiente.porhora" class="tabla100">
                                <div class="tfila">
                                    <div class="tcelda">
                                        Hora Inicio:
                                        <input type="time" class="form-control" v-model="horaentrada" @change="validarAsignacion()">
                                    </div>
                                    <div class="tcelda" style="width:20px"></div>
                                    <div class="tcelda">
                                        Hora Fin:
                                        <input type="time" class="form-control" v-model="horasalida" @change="validarAsignacion()">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <br>Observaciones: <input class="form-control" v-model="obs1">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="modalAsignacion=0">Cerrar</button>
                    <button class="btn btn-primary" @click="accion==1?storeAsignacion():updateAsignacion()">
                        Guardar <span v-if="accion==2">Modificaciones</span></button>
                </div>
            </div>
        </div>
    </div>    

    <!-- MODAL PAGOS  MODAL PAGOS  MODAL PAGOS  MODAL PAGOS  MODAL PAGOS  MODAL PAGOS -->
    <!-- MODAL PAGOS  MODAL PAGOS  MODAL PAGOS  MODAL PAGOS  MODAL PAGOS  MODAL PAGOS -->

</main>
</template>

<script>
import * as jsfechas from '../../fechas.js';

export default {
    props:['regEstablecimiento'],

    data(){ return {
        modalAsignacion:0, modalPago:0, accion:'', divAsignaciones:1, nosocio:0,
        arrayAsignaciones:[], arrayAmbientes:[],
        regAsignacion:[], regAmbiente:[], regCliente:[], regPago:[],
        idasignacion:'', nrasignacion:'', fechaentrada:'', horaentrada:'', horasalida:'', obs1:'',
        idpago:'', fecha:'', importe:'',
    }},

    methods:{
        /*
        listaAsignaciones(idestablecimiento){
            var url='/ser_asignacion/listaAsignaciones?idestablecimiento='+idestablecimiento;
            let me=this;
            axios.get(url).then(function(response){
                me.arrayAsignaciones=response.data.asignaciones;
            });
        },*/

        listaAmbientes(idestablecimiento){
            var url='/ser_ambiente/listaAmbientes?idestablecimiento='+idestablecimiento;
            let me=this;
            axios.get(url).then(function(response){
                me.arrayAmbientes=response.data.ambientes;
                me.regAmbiente=response.data.ambientes[0];
            });
            url='/ser_asignacion/listaAsignaciones?idestablecimiento='+idestablecimiento;
            axios.get(url).then(function(response){
                me.arrayAsignaciones=response.data.asignaciones;
            });
        },

        verIDcliente(idcliente){ this.idcliente=idcliente; },

        async verAsignacion(asignacion){
            let response=await axios.get('/ser_asignacion/verAsignacion?idasignacion='+asignacion.idasignacion);
            this.regAsignacion=response.data.asignacion[0];
            response=await axios.get('/ser_ambiente/listaAmbientes?idambiente='+asignacion.idambiente);
            this.regAmbiente=response.data.ambientes[0];
            response=await axios.get('/ser_asignacion/verCliente?idcliente='+asignacion.idcliente+'&tipocliente='+asignacion.tipocliente);
            this.regCliente=response.data.cliente[0];
            this.regAsignacion.tarifa=this.regAsignacion.tipocliente=='s'?this.regAmbiente.tarifasocio:this.regAmbiente.tarifareal;
            this.regAsignacion.cliente=this.regAsignacion.tipocliente=='s'?'Socio':'Externo';
            this.verPago(asignacion.idasignacion);
            this.divAsignaciones=0;
        },

        verPago(idasignacion){
            url='/ser_pago/verPago?idasignacion='+idasignacion;
            let me=this;
            axios.get(url).then(function(response){
                me.regPago=response.data.pago[0];
                if(!response.data.pago.length) me.regPago=[];
            });
        },

        nuevaAsignacion(){
            this.modalAsignacion=1;
            this.accion=1;
            this.nrasignacion='';
            this.fechaentrada='';
            this.obs1='';
        },

        editarAsignacion(asignacion){
            this.modalAsignacion=1;
            this.accion=2;
            this.idasignacion=asignacion.idasignacion;
            this.nrasignacion=asignacion.nrasignacion;
            this.fechaentrada=asignacion.fechaentrada;
            this.obs1=asignacion.obs1;
        },

        validarAsignacion(){

        },

        storeAsignacion(){
            this.modalAsignacion=0;
            let me=this;
            axios.post('/ser_asignacion/storeAsignacion',{
                'idcliente':this.idcliente,
                'tipocliente':this.nosocio?'c':'s',
                'idambiente':this.regAmbiente.idambiente,
                'nrasignacion':this.nrasignacion,
                'fechasolicitud':'',
                'mesboleta':'',
                'ocupantes':'',
                'iddocumentos':'',
                'idimplementos':'',
                'fechaentrada':this.fechaentrada,
                'horaentrada':this.regAmbiente.porhora?this.horaentrada:'',
                'horasalida':this.regAmbiente.porhora?this.horasalida:'',
                'fechadefuncion':'',
                'idresponsable':'',
                'idrepresentante':'',
                'obs1':this.obs1
            }).then(function(){
                swal('Asignación creada','Proceda a la verificación de pagos','success');
                me.listaAsignaciones(me.regAmbiente.idestablecimiento);
            });
        },

        updateAsignacion(){
            this.modalAsignacion=0;
            let me=this;
            axios.put('/ser_asignacion/updateAsignacion',{
                'idasignacion':this.idasignacion,
                'nrasignacion':this.regAsignacion.nrasignacion,
                'fechasolicitud':'',
                'mesboleta':'',
                'ocupantes':'',
                'iddocumentos':'',
                'idimplementos':'',
                'fechaentrada':this.regAsignacion.fechaentrada,
                'horaentrada':this.regAmbiente.capacidad?this.regAsignacion.horaentrada:'',
                'fechasalida':'',
                'horasalida':this.regAmbiente.capacidad?this.regAsignacion.horasalida:'',
                'fechadefuncion':'',
                'idresponsable':'',
                'obs1':this.obs1
            }).then(function(){
                swal('Datos actualizados','','success');
                me.verAsignacion(me.idasignacion);
            })
        },
        
        verPago(idasignacion){
            url='/ser_pago/verPago?idasignacion='+idasignacion;
            let me=this;
            axios.get(url).then(function(response){
                me.regPago=response.data.pago[0];
                if(!response.data.pago.length) me.regPago=[];
            });
        },

    },

    mounted(){
        this.jsfechas=jsfechas;
        this.listaAmbientes(this.regEstablecimiento.idestablecimiento);
        //this.listaAsignaciones(this.regEstablecimiento.idestablecimiento);
        
    },
}
</script>