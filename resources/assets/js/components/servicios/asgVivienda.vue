<template>
<main>
    <div class="card" v-if="divAsignaciones">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10 titcard">
                    <div class="tablatit">
                        <div class="tcelda">DEPARTAMENTOS BLOQUE "<span v-text="String.fromCharCode(nrgrupo+64)"></span>"</div>
                    </div>
                </div>  
                <div class="col-md-2 text-right"> 
                    <div class="input-group-append" style="display:inline">
                        <button class="btn btn-primary dropdown-toggle" style="margin-top:0"
                            data-toggle="dropdown" aria-expanded="false">
                            Bloque...<span class="caret"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                            <a href="#" class="dropdown-item" v-for="k in cantgrupos" :key="k" 
                                v-text="'Bloque '+String.fromCharCode(k+64)"
                                @click="listaAmbientes(regEstablecimiento.idestablecimiento,k)"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped table-sm">
                <thead class="tcabecera">
                    <tr>
                        <th>DEPTO</th>
                        <th>Descripción</th>
                        <th>Ocupante</th>
                        <th>Ingresó</th>
                        <th>Vence</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="ambiente in arrayAmbientes" :key="ambiente.id">
                        <td class="titcard" v-text="ambiente.codambiente" align="center"></td>
                        <td><span v-text="ambiente.piso+(arrayOrdinal[ambiente.piso]).substr(-2)"></span> Piso,
                            <span v-text="ambiente.tipo"></span>
                            <br>Canon: <span v-text="ambiente.tarifasocio"></span>Bs.
                        </td>
                        <td><span v-if="ambiente.ocupado" v-html="quienOcupa(ambiente.idambiente)"></span></td>
                        <td><span v-if="ambiente.ocupado" v-html="queFecha(ambiente.idambiente)"></span></td>
                        <td></td>
                        <td align="center">
                            <button v-if="ambiente.ocupado" class="btn btn-warning icon-book-open" title="Ver asignación"
                                @click="cualAsignacion(ambiente.idambiente)"></button>
                            <button v-else class="btn btn-success icon-user-follow" title="Asignar departamento"
                                @click="nuevaAsignacion(ambiente)"></button>                            
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row" v-if="!divAsignaciones">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <span class="titcampo">Depto:</span>
                            <span v-text="regAmbiente.codambiente"></span>,
                            <span v-text="regAmbiente.piso+(arrayOrdinal[regAmbiente.piso]).substr(-2)"></span> Piso
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <span class="titcampo">Tipo:</span>
                            <span v-text="regAmbiente.tipo"></span>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <span class="titcampo">Canon Socio:</span>
                            <span v-text="regAmbiente.tarifasocio"></span>Bs
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <span class="titcampo">Canon Real:</span>
                            <span v-text="regAmbiente.tarifareal"></span>U$
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header titcard">Locatario</div>
                <div class="card-body">
                    <h4 class="titcard text-center">
                        <span v-text="regCliente.nomgrado"></span>
                        <span v-text="regCliente.nombre"></span>
                        <span v-text="regCliente.apaterno"></span>
                        <span v-text="regCliente.amaterno"></span>
                    </h4>
                    <div class="row">
                        <div class="col-md-2 text-center">
                            <img v-if="regCliente.rutafoto" :src="'img/socios/'+regCliente.rutafoto">
                            <img v-else src="img/socios/avatar.png" >
                        </div>
                        <div class="col-md-5">
                            <div class="tablacen">
                                <div class="tfila">
                                    <div class="tcelda titcampo">Fuerza:</div>
                                    <div class="tcelda" v-text="regCliente.nomfuerza"></div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda titcampo">Nr Papeleta:</div>
                                    <div class="tcelda" v-text="regCliente.nomfuerza"></div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda titcampo">Destino:</div>
                                    <div class="tcelda"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="tablacen">
                                <div class="tfila">
                                    <div class="tcelda titcampo">CI:</div>
                                    <div class="tcelda"></div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda titcampo">Tel Cel:</div>
                                    <div class="tcelda"></div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda titcampo">Espos@:</div>
                                    <div class="tcelda" v-text="regCliente.nomfuerza"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header titcard">Asignación</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="titsubrayado">Contrato</h4>
                            <div class="tablacen">
                                <div class="tfila">
                                    <div class="tcelda titcampo">Nro: Contrato</div>
                                    <div class="tcelda" v-text="regAsignacion.nrasignacion"></div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda titcampo">Fecha Solicitud:</div>
                                    <div class="tcelda" v-text="regAsignacion.fechasolicitud"></div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda titcampo">Fecha Contrato:</div>
                                    <div class="tcelda" v-text="jsfechas.fechames(regAsignacion.fechaentrada)"></div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda titcampo">Boleta de Pago:</div>
                                    <div class="tcelda" v-text="regAsignacion.mesboleta"></div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda titcampo">Ocupantes:</div>
                                    <div class="tcelda" v-text="regAsignacion.ocupantes"></div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda titcampo">Estadía Actual:</div>
                                    <div class="tcelda" v-text="regAsignacion.fechasolicitud"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4 class="titsubrayado">Documentos Presentados</h4>
                            <div class="tablacen">
                                <div class="tfila" v-for="documento in arrayDocumentos" :key="documento.id">
                                    <input type="checkbox" v-model="arrayIDdocumentos" :value="documento.iddocumento" disabled> 
                                    <span style="text-transform:capitalize" v-text="documento.nomdocumento"></span>
                                </div>
                            </div>
                        </div>
                        <div v-if="regAsignacion.obs1" class="col-md-12">
                            <span class="titcampo">OBS: </span>
                            <span v-text="regAsignacion.obs1"></span>
                        </div>
                        <div class="col-md-12 text-center"><br>
                            <button class="btn btn-primary" @click="editarAsignacion(regAsignacion)">Modifcar asignación</button>
                        </div>
                    </div>
                    

                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="tablatit titcard">
                                <div class="tcelda">Gestión de Pagos</div>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <button class="btn btn-primary" style="margin-top:0px" @click="nuevoPago()">Registrar Pago</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-sm">
                        <thead class="tcabecera">
                            <tr>
                                <th>Nr</th> 
                                <th>Concepto</th>
                                <th>Periodo</th>
                                <th>Modo Pago</th>
                                <th>Documento</th>
                                <th>Fecha</th>
                                <th>Importe</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(pago,index) in arrayPagos" :key="pago.id">
                                <td v-text="index+1" align="center"></td>
                                <td v-text="pago.concepto"></td>
                                <td v-text="pago.periodo"></td>
                                <td><span v-text="pago.modopago==2?'Depósito':'Descuento'"></span><br>
                                    <!--
                                    <span v-if="pago.modopago==3&&pago.idestado>4">
                                        <span v-html="'<font color=green><b><i class=cui-check></i>Cobrado</b></font>'"></span>
                                    </span>
                                    <span v-if="pago.modopago==3&&pago.idestado<=4">
                                        <span v-html="'<font color=red><b><i class=cui-circle-x></i>Pendiente</b></font>'"></span>
                                    </span>
                                    -->
                                </td>
                                <td v-text="pago.nrdocumento" align="right"></td>
                                <td v-text="jsfechas.fechalat(pago.fecha)" align="center"></td>
                                <td v-text="pago.importe" align="right"></td>
                                <td align="center">
                                    <button class="btn btn-warning btn-sm icon-pencil" @click="editarPago(pago)"></button>
                                    <button class="btn btn-warning btn-sm icon-printer" @click="imprimirPago(pago)"></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>                    
                </div>
            </div>
        </div>

    </div>


    <div class="modal" :class="modalAsignacion?'mostrar':''">
        <div class="modal-dialog modal-primary">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span v-text="accion==1?'Nueva':'Modificar'"></span> Asignación</h4>
                    <button class="close" @click="modalAsignacion=0">x</button>
                </div>
                <div class="modal-body">
                    <h4 class="titsubrayado" v-text="regEstablecimiento.nomestablecimiento"></h4>
                    <div class="row">
                        <div class="col-md-6">
                            <span class="titcampo">Depto:</span> <span v-text="regAmbiente.codambiente"></span>
                            (<span v-text="regAmbiente.tipo"></span>)
                        </div>
                        <div class="col-md-6 text-right">
                            <span class="titcampo">Canon:</span>
                            <span v-text="regAmbiente.tarifasocio"></span>Bs.
                        </div>
                    </div>

                    <div v-if="!regAsignacion.idasignacion"><br>Socio: (Digite nombres, apellidos, CI o papeleta)
                        <autocomplete @encontrado="verIDcliente($event)" ></autocomplete>
                    </div>
                    <h4 v-if="regAsignacion.idcliente" class="titsubrayado"><br>
                        <span v-text="regCliente.nomgrado"></span> <span v-text="regCliente.nombre"></span>
                        <span v-text="regCliente.apaterno"></span> <span v-text="regCliente.amaterno"></span>
                    </h4><br>

                    <div v-if="!divValidado">
                        NO PUEDE ACCEDER AL SERVICIO
                        El socio no cumple lo siguiente:
                        Debe estar destinado en la ciudad de La Paz o El Alto
                        Debe estar casado

                    </div>

                    <div v-else class="row">
                        <div class="col-md-6">
                            <h4 class="titazul">Contrato</h4>
                            Fecha de Solicitud: <span class="txtasterisco"></span>
                            <input type="date" class="form-control" v-model="fechasolicitud" @change="validarAsignacion()">
                            Nr. Contrato: <span class="txtasterisco"></span>
                            <input type="text" class="form-control" v-model="nrasignacion" @keyup="validarAsignacion()">
                            Fecha Contrato: <span class="txtasterisco"></span>
                            <input type="date" class="form-control" v-model="fechaentrada" @change="validarAsignacion()">
                            Boleta de pago: <span class="txtasterisco"></span>
                            <div class="tabla100">
                                <div class="tfila">
                                    <div class="tcelda "><select class="form-control">
                                        <option value="">Ene</option><option value="">Feb</option>
                                        </select>
                                    </div>
                                    <div class="tcelda" style="width:10px"></div>
                                    <div class="tcelda" >
                                        <select class="form-control">
                                        <option value="">2019</option><option value="">2020</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!--<select v-model="mesboleta" class="form-control" @change="validarAsignacion()"><option value=""></option></select>-->
                            Ocupantes: <span class="txtasterisco"></span>                                    
                            <select v-model="ocupantes" class="form-control" @change="validarAsignacion()">
                                <option v-for="i in 7" :key="i" :value="i" v-text="i"></option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <h4 class="titazul">Documentos</h4>
                            <ul style="list-style:none; padding-left:10px;">
                                <li v-for="documento in arrayDocumentos" :key="documento.iddocumento">
                                    <input type="checkbox" v-model="arrayIDdocumentos" :value="documento.iddocumento"> 
                                    <span v-text="documento.nomdocumento"></span> 
                                </li>
                            </ul>
                            <br>
                            Observaciones:<input type="text" class="form-control" v-model="obs1" >                            
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="modalAsignacion=0">Cerrar</button>
                    <button class="btn btn-primary" 
                        @click="accion==1?storeAsignacion():updateAsignacion()">
                        Guardar <span v-if="accion==2">Modificaciones</span></button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" :class="modalPago?'mostrar':''">
        <div class="modal-dialog modal-primary modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Registrar Pago</h4>
                    <button class="close" @click="modalPago=0">x</button>
                </div>
                <div class="modal-body">
                    Concepto:
                    <input type="text" class="form-control" v-model="concepto">
                    Periodo:
                    <input type="text" class="form-control" v-model="periodo">
                    <!-- Modo Pago:<input type="text" class="form-control"> -->
                    Nro Documento:
                    <input type="text" class="form-control" v-model="nrdocumento">
                    Fecha:
                    <input type="date" class="form-control" v-model="fecha">
                    Importe:
                    <input type="text" class="form-control" v-model="importe">
                    <input type="checkbox"> Pago al descuento
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="modalPago=0">Cerrar</button>
                    <button class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</main>
</template>

<script>
import * as jsfechas from '../../fechas.js';

export default {
    props: ['regEstablecimiento'],

    data(){ return {
        modalAsignacion:0, modalPago:0, accion:'', jsfechas:'', completo:0,
        divAsignaciones:1, divValidado:1, nrgrupo:1, cantgrupos:'',
        regAsignacion:[], regAmbiente:[], regCliente:[],
        arrayAsignaciones:[], arrayAmbientes:[], arrayPagos:[],
        arrayDocumentos:[], arrayIDdocumentos:[], 
        idasignacion:'', nrasignacion:'', fechasolicitud:'', fechaentrada:'', 
        mesboleta:'', ocupantes:'', obs1:'',
        idpago:'', concepto:'', periodo:'', nrdocumento:'', fecha:'', importe:'',
        arrayOrdinal:['','Primer','Segundo','Tercer','Cuarto','Quinto','Sexto','Séptimo'],
    }},

    methods:{
        listaAmbientes(idestablecimiento,nrgrupo){
            this.nrgrupo=nrgrupo;
            var url='/ser_ambiente/listaAmbientes?idestablecimiento='
                +idestablecimiento+'&bloque='+String.fromCharCode(nrgrupo+64);
            axios.get(url).then(response=>{
                this.arrayAmbientes=response.data.ambientes;
            });
            url='/ser_asignacion/listaAsignaciones?idestablecimiento='
                +idestablecimiento+'&bloque='+String.fromCharCode(nrgrupo+64);
            axios.get(url).then(response=>{
                this.arrayAsignaciones=response.data.asignaciones;
            });
        },

        listaDocumentos(){
            var url='/par_documento/listaDocumentos?iddocumentos='+this.regEstablecimiento.iddocumentos;
            axios.get(url).then(response=>{
                this.arrayDocumentos=response.data.documentos;
            });
        },

        listaPagos(idasignacion){
            let me=this;
            var url='/ser_pago/listaPagos?idasignacion='+idasignacion;
            axios.get(url).then(function(response){
                me.arrayPagos=response.data.pagos;
            });
        },

        quienOcupa(idambiente){
            for(var i=0; i<this.arrayAsignaciones.length; i++){   
                if(this.arrayAsignaciones[i].idambiente==idambiente)
                    return this.arrayAsignaciones[i].nomgrado+' '
                    +this.arrayAsignaciones[i].nombre+' '
                    +this.arrayAsignaciones[i].apaterno+' '
                    +this.arrayAsignaciones[i].amaterno+'<br>'
                    +this.arrayAsignaciones[i].nomfuerza;
            }
        },

        queFecha(idambiente){
            for(var i=0; i<this.arrayAsignaciones.length; i++){
                if(this.arrayAsignaciones[i].idambiente==idambiente)
                    return '<center>'+jsfechas.fechames(this.arrayAsignaciones[i].fechaentrada)+'</center>';
            }
        },

        cualAsignacion(idambiente){
            for(var i=0; i<this.arrayAsignaciones.length; i++){
                if(this.arrayAsignaciones[i].idambiente==idambiente)
                   this.verAsignacion(this.arrayAsignaciones[i]); 
            }
        },

        verIDcliente(idcliente){
            this.idcliente=idcliente;
            this.divValidado=1; //this.completo=1;
            let me=this;
            var url='/socio/verSocio?op=viv&idsocio='+idcliente;
            axios.get(url).then(function(response){
                var regSocio=response.data.socio[0];               
                if(regSocio.idestadocivil!=2) me.divValidado=0; //me.completo=0;
            });
        },

        async verAsignacion(asignacion){
            let response=await axios.get('/ser_asignacion/verAsignacion?idasignacion='+asignacion.idasignacion);
            this.regAsignacion=response.data.asignacion[0];
            response=await axios.get('/ser_ambiente/listaAmbientes?idambiente='+asignacion.idambiente);
            this.regAmbiente=response.data.ambientes[0];
            response=await axios.get('/ser_asignacion/verCliente?idcliente='+asignacion.idcliente+'&tipocliente='+asignacion.tipocliente);
            this.regCliente=response.data.cliente[0];
            this.arrayIDdocumentos=JSON.parse('['+this.regAsignacion.iddocumentos+']');
            this.listaPagos(this.regAsignacion.idasignacion);
            this.divAsignaciones=0;
        },

        nuevaAsignacion(ambiente){
            this.regAmbiente=ambiente;
            this.modalAsignacion=1;
            this.accion=1;
            this.divValidado=1;
            this.listaDocumentos();
            this.idcliente='';
            this.fechasolicitud='';
            this.nrasignacion='';
            this.fechaentrada='';
            this.mesboleta='';
            this.ocupantes='';
            this.obs1='';
        },

        editarAsignacion(asignacion){
            this.modalAsignacion=1;
            this.accion=2;
            this.completo=1;
            this.listaDocumentos();
            this.idasignacion=asignacion.idasignacion;
            this.fechasolicitud=asignacion.fechasolicitud;
            this.nrasignacion=asignacion.nrasignacion;
            this.fechaentrada=asignacion.fechaentrada;
            this.mesboleta=asignacion.mesboleta;
            this.ocupantes=asignacion.ocupantes;
            this.obs1=asignacion.obs1;
            this.arrayIDdocumentos=JSON.parse('['+asignacion.iddocumentos+']');
        },

        validarAsignacion(){
            return true;
        },

        storeAsignacion(){
            swal({ title:'Procesando...',text:'Un momento por favor', type:'warning',
                showCancelButton:false, showConfirmButton:false, //closeOnConfirm: false,
                allowOutsideClick: false, allowEscapeKey: false, allowEnterKey: false,
                onOpen:() => { swal.showLoading() }
            });            
            axios.post('/ser_asignacion/storeAsignacion',{
                'idcliente':this.idcliente,
                'tipocliente':'s',
                'idambiente':this.regAmbiente.idambiente,
                'nrasignacion':this.nrasignacion,
                'fechasolicitud':this.fechasolicitud,
                'mesboleta':this.mesboleta,
                'ocupantes':this.ocupantes,
                'iddocumentos':this.arrayIDdocumentos.join(),
                'idimplementos':'',
                'fechaentrada':this.fechaentrada,
                'horaentrada':'',
                'horasalida':'',
                'fechadefuncion':'',
                'idresponsable':'',
                'idrepresentante':'',
                'obs1':this.obs1
            }).then(response=>{
                swal('Asignación creada','Proceda a la verificación de pagos','success');
                this.listaAmbientes(this.regAmbiente.idestablecimiento,this.nrgrupo);
                this.modalAsignacion=0;
            });
        },

        updateAsignacion(){
            this.modalAsignacion=0;
            let me=this;
            axios.put('/ser_asignacion/updateAsignacion',{
                'idasignacion':this.idasignacion,
                'nrasignacion':this.regAsignacion.nrasignacion,
                'fechasolicitud':this.fechasolicitud,
                'mesboleta':this.mesboleta,
                'ocupantes':this.ocupantes,
                'iddocumentos':this.arrayIDdocumentos.join(),
                'idimplementos':'',
                'fechaentrada':this.fechaentrada,
                'horaentrada':'',
                'fechasalida':'',
                'horasalida':'',
                'fechadefuncion':'',
                'idresponsable':'',
                'obs1':this.obs1
            }).then(function(){
                swal('Datos actualizados','','success');
                me.verAsignacion(me.regAsignacion);
            });
        },

        nuevoPago(){
            this.modalPago=1;
            this.accion=1;
        },
    },

    mounted(){
        this.jsfechas=jsfechas;
        this.cantgrupos=this.regEstablecimiento.cantgrupos*1;
        this.listaAmbientes(this.regEstablecimiento.idestablecimiento,1);
        this.listaDocumentos();
    }
}
</script>
