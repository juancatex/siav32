<template>
<main>
<div style="display:none" id="divDeportivo">
    <div class="card-header" v-if="divContratos">
        <div class="row" >
            <div class="col-md-6 titcard">RESERVAS PROGRAMADAS</div>
            <div class="col-md-6 text-right">
                <button class="btn btn-primary" @click="asignarCliente(regAmbiente)">Nueva Reserva</button>
            </div>
        </div>
    </div>
    <br>
    <div class="table-responsive" v-if="divContratos" style="background-color:#fff"> <!-- listado de reservas -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nro</th>
                    <th>Fecha Reservada</th>
                    <th>Cliente</th>
                    <th>Actividad</th>
                    <th>Pagos</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="asignacion in arrayAsignaciones" :key="asignacion.idasignacion" style="cursor:pointer" >
                    <td v-text="asignacion.nrasignacion"></td>
                    <td @click="verAsignacion(asignacion)">
                        <b><span v-text="jsfechas.fechadia(asignacion.fechaentrada)"></span></b>
                        <br>{{asignacion.horaentrada.substr(0,5)}} a {{asignacion.horasalida.substr(0,5)}}
                    </td>
                    <td @click="verAsignacion(asignacion)">
                        <b><span v-text="asignacion.nomgrado+' '+asignacion.apaterno
                            +' '+asignacion.amaterno+' '+asignacion.nombre"></span></b><br>
                        <span class="titcampo">Fuerza:</span>{{asignacion.nomfuerza}}; 
                        <span class="titcampo">CI:</span>{{asignacion.ci+asignacion.abrvdep}};
                        <span class="titcampo">Celular:</span>{{asignacion.telcelular}} 
                    </td>
                    <td @click="verAsignacion(asignacion)" v-text="asignacion.actividad">
                    </td>
                    <td @click="verAsignacion(asignacion)">
                        <div class="tfila" v-for="pago in arrayPagos" :key="pago.idpago" v-if="pago.idasignacion==asignacion.idasignacion">
                            <div class="tcelda titcampo nowrap" v-text="pago.concepto"></div>
                            <div class="tcelda text-right" v-text="pago.importe+'Bs.'"></div>
                            <div class="tcelda text-right" v-text="'(Op.'+pago.nrdocumento+')'"></div>
                        </div>
                    </td>
                    <td align="center" nowrap >
                        <button class="btn btn-primary" @click="verPagos(asignacion)" title="Administración de Pagos">
                        <i class="icon cui-dollar"></i></button>
                        <button class="btn btn-primary" @click="editarReserva(asignacion)" title="Modificar Reserva">
                        <i class="icon icon-pencil"></i></button> 
                    </td>
                </tr>
            </tbody>
        </table>
    </div> <!-- listado de reservas -->

    <div class="row" v-if="!divContratos">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header titcard">Reserva Nro <span v-text="regAsignacion.nrasignacion"></span></div>
                <div class="card-body">
                    <div class="tfila">
                        <div class="tcelda titcampo">Fecha:</div>
                        <div class="tcelda"><span v-text="jsfechas.fechadia(regAsignacion.fechaentrada)"></span></div>
                    </div>
                    <div class="tfila">
                        <div class="tcelda titcampo">Hora Inicio:</div>
                        <div class="tcelda"><span v-text="regAsignacion.horaentrada.substr(0,5)"></span></div>
                    </div>
                    <div class="tfila">
                        <div class="tcelda titcampo">Hora Fin:</div>
                        <div class="tcelda"><span v-text="regAsignacion.horasalida.substr(0,5)"></span></div>
                    </div>
                    <div class="tfila">
                        <div class="tcelda titcampo">Actividad:</div>
                        <div class="tcelda"><span v-text="regAsignacion.actividad"></span></div>
                    </div>
                    <div class="tfila">
                        <div class="tcelda titcampo">Responsable:</div>
                        <div class="tcelda"><span v-text="regAsignacion.idresponsable"></span></div>
                    </div>
                </div>
            </div>    
        </div> <!-- datos contrato -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header titcard">Cliente</div>
                <div class="card-body">
                    <div class="tfila">
                        <div class="tcelda titcampo">Nombre:</div>
                        <div class="tcelda" v-text="regCliente.nomcliente"></div>
                    </div>
                    <div class="tfila">
                        <div class="tcelda titcampo nowrap">Cliente:</div>
                        <div class="tcelda" v-text="regCliente.tipocliente=='s'?'Socio':'Civil'"></div>
                    </div>
                    <div class="tfila">
                        <div class="tcelda titcampo">Fuerza:</div>
                        <div class="tcelda" v-text="regCliente.nomfuerza"></div>
                    </div>
                    <div class="tfila">
                        <div class="tcelda titcampo">Nro CI:</div>
                        <div class="tcelda" v-text="regCliente.ciexp"></div>
                    </div>
                    <div class="tfila">
                        <div class="tcelda titcampo">Celular:</div>
                        <div class="tcelda" v-text="regCliente.telcelular"></div>
                    </div>
                </div>
            </div>    
        </div> <!-- datos cliente -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header titcard">Pagos</div>
                <div class="card-body">                            
                    <div class="tfila" v-for="pago in arrayPagos" :key="pago.idpago">
                        <div class="tcelda titcampo nowrap"><span v-text="pago.concepto"></span>:</div>
                        <div class="tcelda text-right"><span v-text="pago.importe"></span>Bs.</div>
                        <div class="tcelda text-right" style="width:40%">Op.<span v-text="pago.nrdocumento"></span></div>
                    </div>
                    <div class="tfila">
                        <div class="tcelda titcampo nowrap">Total Cobrado:</div>
                        <div class="tcelda titcampo text-right"><span v-text="tcobrado"></span>Bs.</div>
                    </div>
                    <span class="titcampo">Son:</span> literal literal literal 00/100 Bs.
                </div>
            </div>    
        </div> <!-- pagos -->
    </div>

            <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
    <div class="row" v-if="!divContratos">
        <div class="col-md-2">&nbsp;</div>
        <div class="col-md-3 text-center">
            <span v-text="regCliente.nomcliente"></span>
            <br><b>ARRENDATARIO</b>
        </div>
        <div class="col-md-2">&nbsp;</div>
        <div class="col-md-3 text-center">
            <span v-text="regAsignacion.idresponsable"></span>
            <br><b>ENCARGADO DE SERVICIOS</b>
        </div>
    </div> <!-- firmantes -->

            <p>&nbsp;</p><p>&nbsp;</p>
            <button v-if="!divContratos" class="btn btn-primary" @click="divContratos=1,listaPagos()">Volver a Reservas</button>
        
    

    <!-- MODAL CONTRATO MODAL CONTRATO MODAL CONTRATO  MODAL CONTRATO  MODAL CONTRATO  MODAL CONTRATO -->
    <!-- MODAL CONTRATO MODAL CONTRATO MODAL CONTRATO  MODAL CONTRATO  MODAL CONTRATO  MODAL CONTRATO -->
    <!-- MODAL CONTRATO MODAL CONTRATO MODAL CONTRATO  MODAL CONTRATO  MODAL CONTRATO  MODAL CONTRATO -->
    <div class="modal" :class="{'mostrar':modalAsignar}" >
        <div class="modal-dialog modal-primary modal-lg">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title">Asignar <span v-text="regServicio.nomestablecimiento"></span></h4>
                    <button type="button" class="close" @click="modalAsignar=0">×</button>                    
                </div>
                <div class="modal-body">
                    <div class="text-center"><span class="nombrecliente" v-text="'CLIENTE: '+regCliente.nomcliente"></span> </div>
                    <br> 
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="titazul">Reserva</h5>
                            <table align="center">
                                <tr><td nowrap>Fecha Actividad:* </td>
                                    <td><input type="date" class="form-control" v-model="fechaevento" @change="validarReserva()"></td>
                                </tr>                                    
                                <tr><td>Hora Inicio:* </td>
                                    <td><input type="time" class="form-control" v-model="horaentrada" @change="validarReserva()"></td>
                                </tr>
                                <tr><td>Hora Fin:* </td>
                                    <td><input type="time" class="form-control" v-model="horasalida" @change="validarReserva()"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5 class="titazul">Contrato</h5>
                            <table align="center">
                                <tr><td nowrap>Nro Contrato:* </td>
                                    <td><input type="text" class="form-control" v-model="nrasignacion" @change="validarReserva()"></td>
                                </tr>
                                <tr><td>Actividad:* </td>
                                    <td><select class="form-control" v-model="actividad" @change="validarReserva()">
                                            <option value="Cancha">Cancha</option>
                                            <option value="Cancha y Parrillero">Cancha y Parrilero</option>
                                            <option value="Recepción Social">Recepción Social</option>
                                            <option value="Kermesse">Kermesse</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr><td nowrap>Repr. Legal:* </td>
                                    <td><select class="form-control" v-model="idrepresentante" >
                                        <option value=""></option>
                                        <option value="">SOF MY WILSON ALMANZA</option>
                                        <!--
                                        <option value="">SOF 2DO ISAC COLQUEHUANCA</option>
                                        <option value="">SOF MY CARLOS NAJERA</option>
                                        -->
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="txtvalidador text-center">* Todos los campos son obligatorios</div>
                            <br>
                            <div class="input-group">
                            Observaciones: 
                            <input type="text" v-model="observaciones" class="form-control">
                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="modalAsignar=0">Cerrar</button>
                    <button v-if="accion==1" :disabled="!completo" class="btn btn-primary" @click="storeAsignacion()">Registrar Reserva</button>
                    <button v-if="accion==2" :disabled="!completo" class="btn btn-primary" @click="updateAsignacion()">Guardar modificaciones en la Reserva</button>
                    <!-- <button :disabled="!califica" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarAsignacion()">Actualizar</button> -->
                </div>
            </div>
        </div>
    </div>


    <!-- MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS -->
    <!-- MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS -->
    <!-- MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS -->
    <div class="modal" :class="{'mostrar':modalPagos}" >
        <div class="modal-dialog modal-primary modal-lg">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title">Registro de Pagos</h4>
                    <button type="button" class="close" @click="modalPagos=0,listaPagos()">×</button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                <th>Nr</th>
                                <th>Concepto</th>
                                <th>Fecha</th>
                                <th>Cod Operación</th>
                                <th>Importe</th>
                                <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(pago,index) in arrayPagos" :key="pago.idpago">
                                <td v-text="index+1" align="center"></td>
                                <td v-text="pago.concepto"></td>
                                <td v-text="jsfechas.fechames(pago.fecha)" align="center" ></td>
                                <td v-text="pago.nrdocumento" align="center"></td>
                                <td v-text="pago.importe" align="right"></td>
                                <td align="center"><button class="btn btn-success" @click="editarPago(pago)">
                                    <i class="icon icon-pencil"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h5 class="titazul">Registrar Pago</h5>
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-2" style="padding:0px 2px">Concepto: *
                            <select class="form-control" v-model="concepto" @change="definirImporte(concepto)">
                                        <option></option>
                                        <option value="Garantía">Garantía</option>
                                        <option value="Alquiler">Alquiler</option>
                                        <option value="Limpieza">Limpieza</option>
                                        <option value="Dev. Garantía">Dev. Garantía</option>
                                    </select>
                        </div>
                        <div class="col-md-2" style="padding:0px 2px">Fecha: *
                            <input type="date" class="form-control" v-model="fecha" @change="validarPago()">
                        </div>
                        <div class="col-md-2" style="padding:0px 2px">Cod Operación: *
                            <input type="text" class="form-control text-right" v-model="nrdocumento" @change="validarPago()">
                        </div>
                        <div class="col-md-2" style="padding:0px 2px">Importe: *
                            <span v-if="concepto=='Alquiler'">({{thoras}}Horas)</span>
                            <div class="input-group">
                                <input type="text" class="form-control text-right" v-model="importe" @change="validarPago()">
                                <div class="input-group-append"><span class="input-group-text">Bs.</span></div>
                            </div>
                        </div>
                        <!--
                        <div class="col-md-2" style="padding:0px 15px 0px 2px">Perfil: *
                                        <select v-model="idperfilcuenta" class="form-control">
                                        <option v-for="perfil in arrayPerfiles" :key="perfil.idperfil"
                                            :value="perfil.idperfilcuentamaestro" v-text="perfil.nomperfil"></option>
                                        </select>
                        </div>
                        -->
                    </div>
                    <div class="row text-right">
                        <div class="col-md-12 txtvalidador">* Estos campos son obligatorios</div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button class="btn btn-secondary" @click="modalPagos=0,listaPagos()">Cerrar</button>
                    <button v-if="accion==1" :disabled="!completo" class="btn btn-primary" @click="storePago()">Guardar Pago</button>
                    <button v-if="accion==2" :disabled="!completo" class="btn btn-primary" @click="updatePago()">Guardar modificaciones del Pago</button>
                </div>

            </div>
        </div>
    </div>

    <ser_busqueda @clienteEncontrado="definirCliente($event)" ref="buscarCliente"></ser_busqueda>
</div> <!-- divDeportivo-->
</main>
</template>



<script>
import * as jsfechas from '../../fechas.js';

export default {
    data(){ return {  
        divContratos:1, modalAsignar:0, accion:1, modalPagos:0, jsfechas:'', //divContratos:1, 
        regServicio:[], regAmbiente:[],regAsignacion:[],regCliente:[],  
        arrayAsignaciones:[], arrayPagos:[], arrayPerfiles:[],
        fechaevento:'', horaentrada:'', horasalida:'', actividad:'',//asignacion reserva
        nrasignacion:'', idrepresentante:'',observaciones:'', //asignacion contrato
        idpago:'', importe:'', concepto:'', nrdocumento:'', fecha:'', glosa:'', idperfilcuenta:'',//pagos
        tarifa:'', thoras:'', devgarantia:0, tcobrado:0, completo:0,
    }},

    methods:{
        cargarvue(servicio){
            $('#divDeportivo').css('display','block');
            this.regServicio=servicio;
            this.regAsignacion='';
            this.jsfechas=jsfechas;
            let me=this;
            var url='/ser_asignacion/listaAmbientes?codservicio='
                +this.regServicio.codservicio+'&idestablecimiento='+this.regServicio.idestablecimiento;
            axios.get(url).then(function(response){
                me.regAmbiente=response.data.ambientes[0];
                me.listaAsignaciones();
                me.listaPagos();
            });
        },

        cerrarvue(){
            $('#divDeportivo').css('display','none');
        },

        listaAsignaciones(){
            let me=this;
            var url='/ser_asignacion/listaAsignacionesCliente?idambiente='+this.regAmbiente.idambiente;
            axios.get(url).then(function(response){
                me.arrayAsignaciones=response.data.asignaciones;
            });
            /*
            var url='/ser_pago/listaPerfiles';
            axios.get(url).then(function(response){
                me.arrayPerfiles=response.data.perfiles;
            });              
            */
        },

        listaPagos(idasignacion){
            let me=this;
            if(idasignacion) var url='/ser_pago/listaPagos?idasignacion='+idasignacion;
            else var url='/ser_pago/listaPagos?idambiente='+this.regAmbiente.idambiente;
            axios.get(url).then(function(response){
                me.arrayPagos=response.data.pagos;
                if(idasignacion){
                    me.tcobrado=0;
                    for(var i=0;i<me.arrayPagos.length;i++) 
                        me.tcobrado+=me.arrayPagos[i].importe;
                }
            });
        },

        asignarCliente(ambiente){   
            this.regAmbiente=ambiente;
            this.$refs.buscarCliente.cargarModal(this.regAmbiente);
        },

        definirCliente(cliente){   
            if(cliente.nomgrado.length>1) var tipo='s'; 
            else var tipo='c';
            this.verCliente(cliente.idcliente,tipo);            
            this.regAsignacion='';
            this.modalAsignar=1;
            this.accion=1;
            this.resetAsignacion();
        },

        definirImporte(concepto){
            this.fecha=this.regAsignacion.fechaentrada;
            if(concepto=='Garantía') this.importe=this.regAmbiente.garantia;
            if(concepto=='Limpieza') this.importe=this.regAmbiente.limpieza;
            if((concepto=='Alquiler')&&(this.regAsignacion.tipocliente=='s')){
                if(this.regAsignacion.actividad=='Cancha') this.importe=this.regAmbiente.alquiler;
                if(this.regAsignacion.actividad=='Cancha y Parrillero') this.importe=this.regAmbiente.alquiler*2;
                if(this.regAsignacion.actividad=='Kermesse') this.importe=100;
                if(this.regAsignacion.actividad=='Recepción Social') this.importe=150;
            }
            if((concepto=='Alquiler')&&(this.regAsignacion.tipocliente=='c')){
                if(this.regAsignacion.actividad=='Cancha') this.importe=this.regAmbiente.alquiler2;
                if(this.regAsignacion.actividad=='Cancha y Parrillero') this.importe=this.regAmbiente.alquiler2*2;
                if(this.regAsignacion.actividad=='Kermesse') this.importe=250;
                if(this.regAsignacion.actividad=='Recepción Social') this.importe=200;
            }
            if(concepto=='Dev. Garantía') this.importe=this.regAmbiente.garantia*-1;
            this.thoras=this.regAsignacion.horasalida.substr(0,2)-this.regAsignacion.horaentrada.substr(0,2);
            this.importe=this.importe*this.thoras; 
        },
        
        resetAsignacion(){
            this.nrasignacion='';
            this.fechacontrato='';
            this.fechaevento='';
            this.horaentrada='';
            this.horasalida='';
            this.idrepresentante='';
            this.actividad='';
            this.observaciones='';
        },

        editarReserva(asignacion){
            this.modalAsignar=1;
            this.accion=2;
            this.regAsignacion=asignacion;
            this.regCliente.nomcliente='';
            if(asignacion.tipocliente=='s') 
                this.regCliente.nomcliente=this.regAsignacion.nomgrado+' ';
            this.regCliente.nomcliente += this.regAsignacion.apaterno+' '
                +this.regAsignacion.amaterno+' '+this.regAsignacion.nombre;            
            this.fechaevento=this.regAsignacion.fechaentrada;
            this.horaentrada=this.regAsignacion.horaentrada;
            this.horasalida=this.regAsignacion.horasalida;
            this.nrasignacion=this.regAsignacion.nrasignacion;
            this.actividad=this.regAsignacion.actividad;
        },

        verCliente(idcliente,tipo){
            let me=this;
            var url='/ser_asignacion/kardexCliente?idcliente='+idcliente+'&tipocliente='+tipo;
            axios.get(url).then(function(response){
                me.regCliente=response.data.cliente[0];
                me.regCliente.nomcliente='';
                if(tipo=='s') me.regCliente.nomcliente=me.regCliente.nomgrado+' '
                me.regCliente.nomcliente+=me.regCliente.apaterno+' '+me.regCliente.amaterno+' '+me.regCliente.nombre;
                me.regCliente.ciexp=me.regCliente.ci+' '+me.regCliente.abrvdep;
                me.regCliente.tipocliente=tipo;                
            });
        },

        verPagos(asignacion){
            this.modalPagos=1;
            this.accion=1;
            this.completo=0;
            this.resetPago();
            this.listaPagos(asignacion.idasignacion);
            if(this.tcobrado==0) {
                this.concepto='Garantía';
                this.fecha=this.regAsignacion.fechaentrada;
                this.importe=this.regAmbiente.garantia;
            }
            this.regAsignacion=asignacion;
        },

        editarPago(pago){
            this.accion=2;
            this.idpago=pago.idpago;
            this.concepto=pago.concepto;
            this.fecha=pago.fecha;
            this.nrdocumento=pago.nrdocumento;
            this.importe=pago.importe;
        },

        resetPago(){
            this.concepto='';
            this.fecha='';
            this.nrdocumento='';
            this.importe='';
        },

        verAsignacion(asignacion){
            this.divContratos=0;
            this.verCliente(asignacion.idcliente,asignacion.tipocliente);
            this.listaPagos(asignacion.idasignacion);
            this.regAsignacion=asignacion;  
        },

        validarReserva(){
            this.completo=0;
            if((this.fechaevento)
                &&(this.horaentrada)
                &&(this.horasalida)
                &&(this.nrasignacion)
                &&(this.actividad)
                //&&(this.idrepresentante) 
                )  this.completo=1;
        },

        validarPago(){
            this.completo=0;
            if((this.concepto)
                &&(this.fecha)
                &&(this.nrdocumento)
                &&(this.importe)
                )  this.completo=1;
        },

        storePago(){
            //this.modalPagos=0;
            var url='/ser_pago/storePago';
            this.idperfilcuentamaestro=9;
            let me=this;
            axios.post(url,{
                'idasignacion':this.regAsignacion.idasignacion,
                'concepto':this.concepto,
                'periodo':this.regAsignacion.fechaentrada,
                'nrdocumento':this.nrdocumento,
                'modopago':2,
                'idperfilcuentamaestro':9,
                'fecha':this.fecha,
                'importe':this.importe,
                //'idresponsable':0,
                'glosa':''
            }).then(function(){
                swal('Registro Correcto','Se ha registrado el pago realizado','success');
                me.resetPago();
                me.listaPagos(me.regAsignacion.idasignacion);
                me.completo=0;
            });
        },

        updatePago(){
            var url='/ser_pago/updatePago';
            axios.put(url,{
                'idpago':this.idpago,
                'concepto':this.concepto,
                'periodo':'',
                'nrdocumento':this.nrdocumento,
                'modopago':'',
                'nrfactura':'',
                'fecha':this.fecha,
                'importe':this.importe, 
            }).then(function(){
                swal('Registro actualizado','Se ha guardado los cambios en este pago','success');
            });
            this.resetPago();
            this.listaPagos(this.regAsignacion.idasignacion);
        },

        storeAsignacion(){
            this.modalAsignar=0;
            var url='/ser_asignacion/storeAsignacion';
            let me=this;
            axios.post(url,{
                'idcliente':this.regCliente.idcliente,
                'tipocliente':this.regCliente.tipocliente,
                'idambiente':this.regAmbiente.idambiente,
                'nrasignacion':this.nrasignacion,
                'fechaentrada':this.fechaevento,
                'horaentrada':this.horaentrada,
                'horasalida':this.horasalida,
                'actividad':this.actividad,
                'fechasolicitud':'',
                'mesboleta':'',
                'ocupantes':'',
                'idpresentados':'',
                'idimplementos':'',
                'idresponsable':'',
                'idrepresentante':'',
                'obs1':this.observaciones
            }).then(function(){
                swal('Registro correcto','Se ha registrado la reserva. Proceda a realizar los cobros','success');
                me.listaAsignaciones();
            });
        },

        updateAsignacion(){
            this.modalAsignar=0;
            var url='/ser_asignacion/updateAsignacion';
            axios.put(url,{
                'idasignacion':this.regAsignacion.idasignacion,
                'fechaentrada':this.fechaevento,
                'horaentrada':this.horaentrada,
                'horasalida':this.horasalida,
                'nrasignacion':this.nrasignacion,
                'actividad':this.actividad,
                'fechasolicitud':'',
                'mesboleta':'',
                'ocupantes':'',
                'idpresentados':'',
                'idimplementos':'',
                'idresponsable':'',
                'idrepresentante':'',
                'obs':this.observaciones
            }).then(function(){
                swal('Registro actualizado','Se han guardado los cambios en la reserva','success');
            });  
            this.listaAsignaciones();          
        },
    }
}
</script>
