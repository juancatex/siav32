<template>
<main>
<div style="display:none" id="divSalon">
    <div class="card" v-if="divContratos">
        <div class="card-header">
            <div class="row">
                <div class="col-md-8 titcard">RESERVAS PROGRAMADAS</div>
                <div class="col-md-4 text-right" style="padding-top:8px">
                    <button class="btn btn-primary" @click="asignarCliente(regAmbiente)">Nueva Reserva</button>
                </div>
            </div>                
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="tcabecera">
                    <tr>
                        <th>Nr Contrato</th>
                        <th>Fecha Reservada</th>
                        <th>Cliente</th>
                        <th>Celular</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="asignacion in arrayAsignaciones" :key="asignacion.idasignacion">
                        <td v-text="asignacion.nrasignacion" :class="{vencido:vencioReserva(asignacion.fechaentrada)}"></td>
                        <td v-text="jsfechas.fechadia(asignacion.fechaentrada)" :class="{vencido:vencioReserva(asignacion.fechaentrada)}" ></td>
                        <td :class="{vencido:vencioReserva(asignacion.fechaentrada)}">
                            <span v-for="ocupante in arrayOcupantes" :key="ocupante.idasignacion" 
                            v-if="ocupante.idasignacion==asignacion.idasignacion"
                            v-text="ocupante.nomgrado+' '+ocupante.apaterno+' '+ocupante.amaterno+' '+ocupante.nombre">
                            </span>
                        </td>
                        <td :class="{vencido:vencioReserva(asignacion.fechaentrada)}">
                            <span v-for="ocupante in arrayOcupantes" :key="ocupante.idasignacion" 
                            v-if="ocupante.idasignacion==asignacion.idasignacion"
                            v-text="ocupante.telcelular">
                            </span>
                        </td>
                        <td align="center" :class="{vencido:vencioReserva(asignacion.fechaentrada)}">
                            <button class="btn btn-primary" @click="verAsignacion(asignacion)" title="Ver la Asignación">
                            <i class="icon-folder-alt"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="row" v-if="!divContratos">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header titcard">Datos del Cliente </div>
                <div class="card-body">
                    <div class="nombrecliente text-center" v-text="regCliente.nomcliente"></div>
                    <div class="row">
                        <div class="col-md-4">
                            <span class="titcampo">Fuerza:</span><span v-text="regCliente.nomfuerza"></span>
                        </div>
                        <div class="col-md-4">
                            <span class="titcampo">Nro CI:</span><span v-text="regCliente.ciexp"></span>
                        </div>
                        <div class="col-md-4 text-center">
                            <span class="titcampo">Celular:</span><span v-text="regCliente.telcelular"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header titcard">Datos Reserva</div>
                <div class="card-body">
                    <table>
                        <tr><td class="titcampo" valign="top" nowrap>Fecha Evento: </td>
                            <td v-text="jsfechas.fechadia(regAsignacion.fechaentrada)"></td></tr>
                        <tr><td class="titcampo" valign="top">De Horas: </td>
                            <td v-text="regAsignacion.horaentrada"></td></tr>
                        <tr><td class="titcampo" valign="top">A Horas: </td>
                            <td v-text="regAsignacion.horasalida"></td></tr>
                        <tr><td class="titcampo">Nr Contrato:</td>
                            <td v-text="regAsignacion.nrasignacion"></td></tr>
                        <tr><td class="titcampo">Fecha Contrato:</td>
                            <td v-text="jsfechas.fechames(regAsignacion.fechasolicitud)"></td></tr>
                        <tr><td class="titcampo" valign="top">Repr. Legal:</td>
                            <td v-text="'SOF AAA BBB CCC'"></td></tr>
                    </table><br>
                    <center>
                        <button class="btn btn-primary" @click="editarReserva()">Modificar Reserva</button>
                    </center>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header titcard">Administración de Pagos</div>
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Nr</th>
                                <th>Concepto</th>
                                <th>Fecha</th>
                                <th>Cod. Operación</th>
                                <th>Importe</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(pago,index) in arrayPagos" :key="pago.idpago">
                                <td v-text="index+1" align="center"></td>
                                <td v-text="pago.concepto"></td>
                                <td v-text="jsfechas.fechames(pago.fecha)" align="center"></td>
                                <td v-text="pago.nrdocumento" align="center" ></td>
                                <td v-text="pago.importe +'Bs'" align="right"></td>
                                <td align="center">
                                    <button class="btn btn-success" @click="editarPago(pago)"><i class="icon-pencil"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    <center>
                    <button class="btn btn-primary" @click="registrarPago()">Registrar Pago</button>
                    </center>
                </div>
            </div>                    
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header titcard">Cierre del contrato</div>
                <div class="card-body">
                    <table v-if="regPago.nrfactura">
                        <tr><td class="titcampo">Total Cobrado:</td>
                            <td align="right" v-text="tcobrado+'Bs.'"></td></tr>
                        <tr><td class="titcampo" nowrap>Devol. Garantía:</td>
                            <td align="right" v-text="'(-)'+devgarantia+'Bs.'"></td></tr>
                        <tr><td class="titcampo">Importe Final:</td>
                            <td align="right" v-text="regPago.importe+'Bs.'"></td></tr>
                        <tr><td class="titcampo">Número Factura:</td>
                            <td align="right" v-text="regPago.nrfactura"></td></tr>
                        <tr><td class="titcampo">Fecha Factura:</td>
                            <td align="right" v-text="jsfechas.fechalat(regPago.fecha)"></td></tr>
                    </table><br>
                    <center>
                        <button v-if="!regPago.nrfactura" class="btn btn-primary" @click="registrarFactura()">Facturar</button> 
                        <button v-else class="btn btn-primary" @click="editarFactura()">Modificar Factura</button>
                    </center>
                </div>
            </div>                    
        </div>                
    </div>

    

    <!-- MODAL CONTRATO MODAL CONTRATO MODAL CONTRATO  MODAL CONTRATO  MODAL CONTRATO  MODAL CONTRATO -->
    <!-- MODAL CONTRATO MODAL CONTRATO MODAL CONTRATO  MODAL CONTRATO  MODAL CONTRATO  MODAL CONTRATO -->
    <!-- MODAL CONTRATO MODAL CONTRATO MODAL CONTRATO  MODAL CONTRATO  MODAL CONTRATO  MODAL CONTRATO -->
    <div class="modal" :class="modalAsignar?'mostrar':''" >
        <div class="modal-dialog modal-primary modal-lg">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title">Asignar Salón ASCINALSS</h4>
                    <button class="close" @click="modalAsignar=0">x</button>
                </div>
                <div class="modal-body">
                    <div class="text-center"><span class="nombrecliente" v-text="'CLIENTE: '+regCliente.nomcliente"></span> </div>
                    <br> 
                    <h5 class="titazul">Reserva</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <table width="100%">
                                <tr>
                                    <td nowrap>Fecha del Evento:</td>
                                    <td><input type="date" class="form-control" v-model="fechaevento"></td>
                                </tr>                                    
                            </table>                                    
                        </div>
                        <div class="col-md-4">
                            <table width="100%">
                                <tr><td>De Horas:</td>
                                    <td><input type="time" class="form-control" v-model="horaentrada"></td>
                                </tr>
                            </table>                                    
                        </div>
                        <div class="col-md-4">
                            <table width="100%">
                                <tr><td>A Horas:</td>
                                    <td><input type="time" class="form-control" v-model="horasalida"></td>
                                </tr>                                    
                            </table>
                        </div>
                    </div>

                    <br> 
                    <h5 class="titazul">Contrato</h5>
                    <div class="row" >
                        <div class="col-md-4">
                            <table>
                                <tr><td nowrap>Contrato Número:<td>
                                    <td><input type="text" class="form-control" v-model="nrasignacion"></td>
                                </tr>
                            </table>                                    
                        </div>
                        <div class="col-md-4">
                            <table>
                                <tr><td nowrap>Fecha Contrato:&nbsp;</td>
                                    <td><input type="date" class="form-control" v-model="fechacontrato"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <table>
                                <tr><td nowrap>Repr. Legal:&nbsp;</td>
                                    <td><select class="form-control" v-model="idrepresentante">
                                        <option value=""></option>
                                        <option value="">SOF MY DEMETRIO CHIPANA</option>
                                        <option value="">SOF 2DO ISAC COLQUEHUANCA</option>
                                        <option value="">SOF MY CARLOS NAJERA</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>                                    
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="modalAsignar=0">Cerrar</button>
                    <button v-if="accion==1" class="btn btn-primary" @click="storeAsignacion()">Registrar Reserva</button>
                    <button v-if="accion==2" class="btn btn-primary" @click="updateAsignacion()">Guardar modificaciones en la Reserva</button>
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
                    <button type="button" class="close" @click="modalPagos=0">×</button>                    
                </div>
                <div class="modal-body">
                    <div class="text-center"><span class="nombrecliente" v-text="'CLIENTE: '+regCliente.nomcliente"></span> </div>
                    <br>
                    <template v-if="!facturacion">
                    <h5 class="titazul">Pagos del Contrato</h5>
                    <div class="row" style="padding-bottom:10px">
                        <div class="col-md-2"></div>
                        <div class="col-md-2" style="padding:0px 2px">Concepto:
                            <select class="form-control" v-model="concepto" @change="selImporte(concepto)">
                                <option value=""></option>
                                <option value="Garantía">Garantía</option>
                                <option value="Alquiler">Alquiler</option>
                                <option value="Limpieza">Limpieza</option>
                            </select>
                        </div>
                        <div class="col-md-2" style="padding:0px 2px">Fecha:
                            <input type="date" class="form-control" v-model="fecha">
                        </div>
                        <div class="col-md-2" style="padding:0px 2px">Cod Operación:
                            <input type="text" class="form-control" v-model="nrdocumento">
                        </div>
                        <div class="col-md-2" style="padding:0px 2px">Importe Definido:
                            <div class="input-group">
                                <input class="form-control text-right" type="text" v-model="importe">
                                <div class="input-group-append"><span class="input-group-text">Bs.</span></div>
                            </div>                          
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 text-right"><span style="margin:5px 0px">Observaciones:</span></div>
                        <div class="col-md-9">
                            <input type="text" class="form-control" v-model="observaciones">
                        </div>
                    </div>
                    </template>


                    <h5 v-if="facturacion" class="titazul">Facturación</h5>
                    <div v-if="facturacion" class="row">
                        <div class="col-md-4">
                            <table>
                                <tr><td nowrap>Número Factura:</td>
                                    <td><input type="text" class="form-control" v-model="nrfactura"></td>
                                </tr>
                                <tr><td>Fecha Factura:</td>
                                    <td><input type="date" class="form-control" v-model="fechafactura"></td>
                                </tr>
                            </table>                            
                        </div>                        
                        <div class="col-md-4">
                            <table>
                                <tr><td class="titcampo">Total Cobrado: </td>
                                    <td><div class="input-group">
                                        <input type="text" class="form-control text-right" readonly v-model="tcobrado">
                                        <div class="input-group-append"><span class="input-group-text">Bs.</span></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr><td nowrap>(-) Devol. Garantía:</td>
                                    <td><div class="input-group">
                                        <input type="text" class="form-control text-right" v-model="devgarantia" @keyup="importe=tcobrado-devgarantia">
                                        <div class="input-group-append"><span class="input-group-text">Bs.</span></div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4 text-center">
                            <table>
                                <tr><td class="titcampo">Importe Final:</td>
                                    <td><div class="input-group">
                                        <input type="text" class="form-control text-right" readonly v-model="importe">
                                        <div class="input-group-append"><span class="input-group-text">Bs.</span></div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button class="btn btn-secondary" @click="modalPagos=0">Cerrar</button>
                    <template v-if="!facturacion">
                    <button v-if="accion==1" class="btn btn-primary" @click="storePago()">Guardar Pago</button>
                    <button v-if="accion==2" class="btn btn-primary" @click="updatePago()">Guardar modificaciones al Pago</button>
                    </template>
                    <template v-if="facturacion">
                    <button v-if="accion==1" class="btn btn-primary" @click="storePago()">Guardar Factura</button>
                    <button v-if="accion==2" class="btn btn-primary" @click="updatePago()">Guardar modificaciones de la Factura</button>
                    </template>
                </div>

            </div>
        </div>
    </div>

    <ser_busqueda @clienteEncontrado="definirCliente($event)" ref="buscarCliente"></ser_busqueda>
</div>
</main>
</template>

<script>
import * as jsfechas from '../../fechas.js';

export default {
    data(){ return {  
        modalAsignar:0, accion:1, modalPagos:0, divContratos:1, jsfechas:'',
        regServicio:[], regAmbiente:[],regAsignacion:[],regCliente:[], regPago:[], 
        arrayAsignaciones:[], arrayPagos:[], arrayOcupantes:[], arrayPerfiles:[],
        fechaevento:'', horaentrada:'', horasalida:'', //asignacion reserva
        nrasignacion:'', fechacontrato:'', idrepresentante:'',observaciones:'', //asignacioncontrato
        nrfactura:'', importe:'', concepto:'', nrdocumento:'', fecha:'', fechafactura:'',//pagos
        facturacion:'', devgarantia:0, tcobrado:0, idperfilcuenta:'',
    }},

    methods:{
        cargarvue(servicio){
            $('#divSalon').css('display','block');
            this.regServicio=servicio;
            this.jsfechas=jsfechas;
            this.regAsignacion='';
            this.divContratos=1;
            let me=this;
            var url='/ser_asignacion/listaAmbientes?codservicio='
                +this.regServicio.codservicio+'&idestablecimiento='+this.regServicio.idestablecimiento;            
            axios.get(url).then(function(response){
                me.regAmbiente=response.data.ambientes[0];
                me.listaAsignaciones();
            });
        },

        cerrarvue(){
            $('#divSalon').css('display','none');
        },

        vencioReserva(fechaevento){
            var aa=new Date().getFullYear();
            var mm=new Date().getMonth()+1; if(mm<10)mm='0'+mm;
            var dd=new Date().getDate();  if(dd<10)dd='0'+dd;
            var fechahoy=aa+mm+dd;
            var fechaevt=fechaevento.substr(0,4)+fechaevento.substr(5,2)+fechaevento.substr(8,2);
            if(fechaevt<fechahoy) return true;
            else return false;
        },

        listaAsignaciones(){
            let me=this;
            var url='/ser_asignacion/listaAsignaciones?idambiente='+this.regAmbiente.idambiente;
            axios.get(url).then(function(response){
                me.arrayAsignaciones=response.data.asignaciones;
            })
            var url='/ser_asignacion/listaOcupantes?idestablecimiento='+this.regServicio.idestablecimiento;
            axios.get(url).then(function(response){
                me.arrayOcupantes=response.data.ocupantes;
            });
            /*
            var url='/ser_pago/listaPerfiles';
            axios.get(url).then(function(response){
                me.arrayPerfiles=response.data.perfiles;
            });            
            */
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

        verCliente(idcliente,tipo){
            let me=this;
            //var url='/ser_asignacion/encontrarCliente?idcliente='+idcliente+'&tipocliente='+tipo;
            var url='/ser_asignacion/kardexCliente?idcliente='+idcliente+'&tipocliente='+tipo;
            axios.get(url).then(function(response){
                me.regCliente=response.data.cliente[0];
                me.regCliente.nomcliente=me.regCliente.apaterno+' '
                    +me.regCliente.amaterno+' '+me.regCliente.nombre;
                if(tipo=='s') 
                    me.regCliente.nomcliente=me.regCliente.nomgrado+' '+me.regCliente.nomcliente;
                me.regCliente.ciexp=me.regCliente.ci+' '+me.regCliente.abrvdep;
                me.regCliente.tipocliente=tipo;                
            });
        },

        verAsignacion(asignacion){
            this.divContratos=0;
            this.verCliente(asignacion.idcliente,asignacion.tipocliente);
            this.listaPagos(asignacion.idasignacion);
            this.regAsignacion=asignacion;  
            let me=this;
            url='/ser_asignacion/encontrarAsignacion?idasignacion='+asignacion.idasignacion;
            axios.get(url).then(function(response){
                me.regAsignacion=response.data.asignacion[0];
            });
        },

        resetAsignacion(){
            this.nrasignacion='';
            this.fechacontrato='';
            this.fechaevento='';
            this.horaentrada='';
            this.horasalida='';
            this.idrepresentante='';
        },

        resetPago(){
            this.concepto='';
            this.nrdocumento='';
            this.fecha='';
            this.concepto='';
            this.importe='';
        },

        editarReserva(){
            this.modalAsignar=1;
            this.accion=2;
            this.nrasignacion=this.regAsignacion.nrasignacion;
            this.fechacontrato=this.regAsignacion.fechasolicitud;
            this.fechaevento=this.regAsignacion.fechaentrada;
            this.horaentrada=this.regAsignacion.horaentrada;
            this.horasalida=this.regAsignacion.horasalida;
        },

        selImporte(concepto){
            switch(concepto){
                case 'Garantía': this.importe=this.regAmbiente.garantia; break;
                case 'Alquiler': this.importe=this.regAmbiente.alquiler; break;
                case 'Limpieza': this.importe=this.regAmbiente.limpieza; break;
                default: this.importe='';
            }
        },

        registrarPago(){
            this.modalPagos=1;
            this.facturacion=0;
            this.accion=1;
            this.resetPago();
            if(this.tcobrado==0) {
                this.concepto='Garantía';
                this.importe=this.regAmbiente.garantia;
            }
        },

        editarPago(pago){
            this.modalPagos=1;
            this.facturacion=0;
            this.accion=2;
            this.idpago=pago.idpago;
            this.concepto=pago.concepto;
            this.nrdocumento=pago.nrdocumento;
            this.fecha=pago.fecha;
            this.importe=pago.importe;            
        },

        listaPagos(idasignacion){
            let me=this;
            var url='/ser_pago/listaPagos?idasignacion='+idasignacion;
            axios.get(url).then(function(response){
                me.arrayPagos=response.data.pagos;
                me.tcobrado=0;
                for(var i=0;i<me.arrayPagos.length;i++) 
                   if(me.arrayPagos[i].concepto=='Garantía'){
                        me.devgarantia=me.arrayPagos[i].importe;
                        me.tcobrado+=me.arrayPagos[i].importe;
                    }   
            });
        },

        verFactura(idasignacion){
                let me=this;
                var url='/ser_pago/verPago?idasignacion='+idasignacion+'&nrfactura=1';
                axios.get(url).then(function(response){
                    me.regPago=response.data.pago[0];
                });
        },

        registrarFactura(){
            this.modalPagos=1;
            this.accion=1;
            this.facturacion=1;
            this.importe=this.tcobrado-this.devgarantia;
        },

        editarFactura(){
            this.modalPagos=1;
            this.accion=2;
            this.facturacion=1;
            this.nrfactura=this.regPago.nrfactura;
            this.fechafactura=this.regPago.fecha;
            this.importe=this.tcobrado-this.devgarantia;
        },

        storeAsignacion(){
            this.modalAsignar=0;
            let me=this;            
            var url='/ser_asignacion/storeAsignacion';
            axios.post(url,{
                'idcliente':this.regCliente.idcliente,
                'tipocliente':this.regCliente.tipocliente,
                'idambiente':this.regAmbiente.idambiente,
                'nrasignacion':this.nrasignacion,
                'fechasolicitud':this.fechacontrato,
                'mesboleta':'',
                'ocupantes':'',
                'idpresentados':'',
                'idimplementos':'',
                'fechaentrada':this.fechaevento,
                'horaentrada':this.horaentrada,
                'horasalida':this.horasalida,
                'fechadefuncion':'',
                'idresponsable':'',
                'actividad':'',
                'idrepresentante':'',
                'obs1':this.obs1,
            }).then(function(){
                swal('Registro correcto','Proceda a realizar los cobros','success');
                url='/ser_asignacion/encontrarAsignacion?idambiente='+me.regAmbiente.idambiente;
                axios.get(url).then(function(response){
                    me.regAsignacion=response.data.asignacion[0];
                    me.verAsignacion(me.regAsignacion);
                });
            });
        },

        updateAsignacion(){
            this.modalAsignar=0;
            var url='/ser_asignacion/updateAsignacion';
            let me=this;
            axios.put(url,{
                'idasignacion':this.regAsignacion.idasignacion,
                'idcliente':this.regCliente.idcliente,
                'tipocliente':this.regCliente.tipocliente,
                'idambiente':this.regAmbiente.idambiente,
                'nrasignacion':this.nrasignacion,
                'fechasolicitud':this.fechacontrato,
                'mesboleta':'',
                'ocupantes':'',
                'idpresentados':'',
                'idimplementos':'',
                'fechaentrada':this.fechaevento,
                'horaentrada':this.horaentrada,
                'horasalida':this.horasalida,
                'fechadefuncion':'',
                'idresponsable':'',
                'actividad':'',
                'idrepresentante':'',
                'obs1':this.obs1,
            }).then(function(){
                swal('Registro actualizado','Se han guardado los cambios en la reserva','success');
                me.verAsignacion(me.regAsignacion);                
            });
            
        },

        storePago(){
            this.modalPagos=0;
            var url='/ser_pago/storePago';
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
                'glosa':''
            }).then(function(){
                swal('Registro Correcto','Se ha registrado el pago realizado','success');
                me.listaPagos(me.regAsignacion.idasignacion);
            });
        },

        updatePago(){
            this.modalPagos=0;
            var url='/ser_pago/updatePago';
            axios.put(url,{
                'idpago':this.idpago,
                'concepto':this.concepto,
                'periodo':this.regAsignacion.fechaentrada,
                'nrdocumento':this.nrdocumento,
                'modopago':this.modopago,               
                'fecha':this.fecha,
                'importe':this.importe,
                'glosa':this.glosa
            }).then(function(response){
                swal('Registro actualizado','Se ha guardado los cambios en este pago','success');
            });
            this.listaPagos(this.regAsignacion.idasignacion);
        },
    }
}
</script>
