<template>
<main>
    <div>
        <button v-if="divAsignaciones===0" class="btn btn-primary" @click="atras()">nivel atras</button>
    </div>
    <div class="card" v-if="divAsignaciones">
        <div class="card-header">                                        
            <div class="row">
                
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" v-model="buscar" @keyup.enter="listaAmbientesSocio(regEstablecimiento.idestablecimiento,buscar)" class="form-control" placeholder="Ingrese Apellidos, Nombres, CI, o Papeleta">
                            <button type="submit" @click="listaAmbientesSocio(regEstablecimiento.idestablecimiento,buscar)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                
                <div class="col-md-10 titcard">
                    <div class="tablatit">
                        <div class="tcelda">DEPARTAMENTOS PISO "<span v-text="nrgrupo"></span>"</div>
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
                                v-text="'Piso '+k"
                                @click="listaAmbientes(regEstablecimiento.idestablecimiento,k)"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped table-sm">
                <thead class="tcabecera">
                    <tr align="center">
                        <th>DEPTO</th>
                        <th align="left">Descripción</th>
                        <th align="left">Ocupante</th>
                        <th>Fecha Inicio Contrato</th>
                        <th>Dias a Vencer</th>
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
                        <td v-if="vence(ambiente.idambiente)>=30" align="center"><span v-if="ambiente.ocupado" v-html="vence(ambiente.idambiente)"></span></td>
                        <td v-else align="center"><font color="red"><span v-if="ambiente.ocupado" v-html="vence(ambiente.idambiente)"></span></font></td>
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
                                    <div class="tcelda titcampo">No. Papeleta:</div>
                                    <div class="tcelda" v-text="regCliente.numpapeleta"></div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda titcampo">Destino:</div>
                                    <div class="tcelda" v-text="regCliente.nomdestino"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="tablacen">
                                <div class="tfila">
                                    <div class="tcelda titcampo">CI:</div>
                                    <div class="tcelda" v-text="regCliente.ci+' '+regCliente.abrvdep"></div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda titcampo">Tel Cel:</div>
                                    <div class="tcelda" v-text="regCliente.telcelular"></div>
                                </div>
                                <!-- <div class="tfila">
                                    <div class="tcelda titcampo">Espos@:</div>
                                    <div class="tcelda" v-text="regCliente.nomfuerza"></div>
                                </div> -->
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
                                    <div class="tcelda titcampo">Nro Contrato:</div>
                                    <div class="tcelda" v-text="regAsignacion.nrasignacion"></div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda titcampo">Solicitud:</div>
                                    <div class="tcelda" v-text="jsfechas.fechames(regAsignacion.fechasolicitud)"></div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda titcampo">Inicio Contrato:</div>
                                    <div class="tcelda" v-text="jsfechas.fechames(regAsignacion.fechaentrada)"></div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda titcampo">Final Contrato:</div>
                                    <div class="tcelda" v-text="jsfechas.fechames(regAsignacion.fechasalida)"></div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda titcampo">Boleta de Pago:</div>
                                    <div class="tcelda" v-text="regAsignacion.mesboleta"></div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda titcampo">Ocupantes:</div>
                                    <div class="tcelda" v-text="regAsignacion.ocupantes"></div>
                                </div>
                                <!-- <div class="tfila">
                                    <div class="tcelda titcampo">Estadía Actual:</div>
                                    <div class="tcelda" v-text="'__a __m'"></div>
                                </div> -->
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
                            <button class="btn btn-primary" @click="editarAsignacion(regAsignacion)">Modificar asignación</button>
                            <button class="btn btn-primary" @click="reporteContrato(regAsignacion)">Contrato Locatario</button>                            
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
                            <button class="btn btn-success" style="margin-top:0px" @click="liberarAmbiente(regAsignacion.idambiente,regAsignacion.idasignacion,regAmbiente.idestablecimiento)">Liberar Ambiente</button>
                            <button class="btn btn-primary" style="margin-top:0px" @click="nuevoPago()">Registrar Pago</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-sm">
                        <thead class="tcabecera">
                            <tr>
                                <th></th>
                                <th>Nro</th> 
                                <th>Concepto</th>
                                <th align="center">Periodo</th>
                                <th align="center">Modo Pago</th>
                                <th align="right">Operación</th>
                                <th align="center">Fecha</th>
                                <th align="right">Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(pago,index) in arrayPagos" :key="pago.id">
                                <td align="center">
                                    <button class="btn btn-warning btn-sm icon-pencil" title="Editar pago"
                                        @click="editarPago(pago)"></button>
                                    <button class="btn btn-warning btn-sm icon-printer" title="Imprimir comprobante"
                                        @click="reportePago(pago)"></button>
                                </td>
                                <td v-text="index+1" align="center"></td>
                                <td v-text="pago.concepto"></td>
                                <td v-text="pago.concepto=='Garantía'?'Contrato':pago.periodo" align="center"></td>
                                <td v-text="pago.modopago==2?'Depósito':'Descuento'" align="center"></td>
                                <td v-text="pago.nrdocumento" align="right"></td>
                                <td v-text="pago.fecha?jsfechas.fechalat(pago.fecha):''" align="center"></td>
                                <td v-text="pago.importe?jsfechas.formatomon(pago.importe):''" align="right"></td>
                            </tr>
                        </tbody>
                    </table>                    
                </div>
            </div>
        </div>

    </div>

    <!-- MODAL ASIGNACION MODAL ASIGNACION MODAL ASIGNACION MODAL ASIGNACION MODAL ASIGNACION --> 
    <!-- MODAL ASIGNACION MODAL ASIGNACION MODAL ASIGNACION MODAL ASIGNACION MODAL ASIGNACION --> 
    <div class="modal" :class="modalAsignacion?'mostrar':''">
        <div class="modal-dialog modal-primary">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title"><span v-text="accion==1?'Nueva':'Modificar'"></span> Asignación</h4>
                    <button class="close" @click="modalAsignacion=0">x</button>
                </div>
                <div class="modal-body" style="overflow-y:scroll; height:420px;">
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
                    <div v-if="!regAsignacion.idasignacion" style="padding:5px 0px 10px 0px;">
                        Socio: (Digite nombres, apellidos, CI o papeleta)
                        <autocomplete @encontrado="verIDcliente($event)" ></autocomplete>
                    </div>
                    <h4 v-if="regAsignacion.idcliente" class="titsubrayado" style="margin:15px 0px;">
                        <span v-text="regCliente.nomgrado"></span> <span v-text="regCliente.nombre"></span>
                        <span v-text="regCliente.apaterno"></span> <span v-text="regCliente.amaterno"></span>
                    </h4>
                    <div v-if="!divValidado" class="advertencia">
                        <p>NO PUEDE ACCEDER AL SERVICIO</p>
                        <div class="tablacen"> El socio no cumple lo siguiente:
                            <ul style="margin-bottom:0px">
                                <li>Debe estar destinado en la ciudad de La Paz o El Alto</li>
                                <li>Debe estar casado</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div v-else class="row">
                        <div class="col-md-6">
                            <h4 class="titazul">Contrato</h4>
                            Fecha de Solicitud: <span class="txtasterisco"></span>
                            <input type="date" class="form-control" v-model="fechasolicitud"
                                name="sol" :class="{'invalido':errors.has('sol')}" v-validate="'required'">
                            <p class="txtvalidador" v-if="errors.has('sol')">Dato requerido</p>
                            Nr. Contrato: <span class="txtasterisco"></span>
                            <input type="text" class="form-control" v-model="nrasignacion"
                                name="nro" :class="{'invalido':errors.has('nro')}" v-validate="'required'">
                            <p class="txtvalidador" v-if="errors.has('nro')">Dato requerido</p>
                            Fecha Inicio Contrato: <span class="txtasterisco"></span>
                            <input type="date" class="form-control" v-model="fechaentrada"
                                name="con" :class="{'invalido':errors.has('con')}" v-validate="'required'">
                            <p class="txtvalidador" v-if="errors.has('con')">Dato requerido</p>
                            Fecha Final Contrato: <span class="txtasterisco"></span>
                            <input type="date" class="form-control" v-model="fechasalida"
                                name="con" :class="{'invalido':errors.has('con1')}" v-validate="'required'">
                            <p class="txtvalidador" v-if="errors.has('con1')">Dato requerido</p>
                            Boleta de pago: <span class="txtasterisco"></span>
                            <div class="tabla100">
                                <div class="tfila">
                                    <div class="tcelda ">
                                        <select class="form-control" v-model="per"
                                            name="per" :class="{'invalido':errors.has('per')}" v-validate="'required'">
                                            <option v-for="mes in arrayMeses" :key="mes" :value="mes" v-text="mes"></option>
                                        </select>
                                        <p class="txtvalidador" v-if="errors.has('per')">Seleccione un Mes</p>
                                    </div>
                                    <div class="tcelda" style="width:10px"></div>
                                    <div class="tcelda" >
                                        <select class="form-control" v-model="ges"
                                            name="ges" :class="{'invalido':errors.has('ges')}" v-validate="'required'">
                                            <option value="2016">2016</option>
                                            <option value="2017">2017</option><option value="2018">2018</option>
                                            <option value="2019">2019</option><option value="2020">2020</option>
                                        </select>
                                        <p class="txtvalidador" v-if="errors.has('ges')">Seleccione un año</p>
                                    </div>
                                </div>
                            </div>
                            Ocupantes: <span class="txtasterisco"></span>                                    
                            <select v-model="ocupantes" class="form-control"
                                name="ocu" :class="{'invalido':errors.has('ocu')}" v-validate="'required'">
                                <option v-for="i in 3" :key="i" :value="i" v-text="i"></option>
                            </select>
                            <p class="txtvalidador" v-if="errors.has('ocu')">Especifique cuántos ocupantes</p>
                        </div>
                        <div class="col-md-6">
                            <h4 class="titazul">Documentos</h4>
                            <ul style="list-style:none; padding-left:10px;">
                                <li v-for="documento in arrayDocumentos" :key="documento.iddocumento">
                                    <input type="checkbox" v-model="arrayIDdocumentos" :value="documento.iddocumento"> 
                                    <span v-text="documento.nomdocumento"></span> 
                                </li>
                            </ul>
                            Observaciones:<input type="text" class="form-control" v-model="obs1" >                            
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="modalAsignacion=0">Cerrar</button>
                    <button class="btn btn-primary" @click="validarAsignacion()">
                        Guardar <span v-if="accion==2">Modificaciones</span></button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PAGO MODAL PAGO MODAL PAGO MODAL PAGO MODAL PAGO MODAL PAGO -->
    <!-- MODAL PAGO MODAL PAGO MODAL PAGO MODAL PAGO MODAL PAGO MODAL PAGO -->
    <div class="modal" :class="modalPago?'mostrar':''">
        <div class="modal-dialog modal-primary modal-sm">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title"><span v-text="accion==1?'Registrar':'Modificar'"></span> Pago</h4>
                    <button class="close" @click="modalPago=0">x</button>
                </div>
                <div class="modal-body">
                    Concepto: <span class="txtasterisco"></span>
                    <select class="form-control" v-model="concepto">
                        <option value="Garantía">Garantía</option>
                        <option value="Alquiler">Alquiler</option>
                        <option value="Serv.Agua">Servicios Basicos Gral.</option>
                    </select>
                    <span v-if="concepto!='Garantía'">Periodo: <span class="txtasterisco"></span> </span>
                    <div v-if="concepto!='Garantía'" class="tabla100">
                        <div class="tfila">
                            <div class="tcelda ">
                                <select class="form-control" v-model="per">
                                    <option v-for="mes in arrayMeses" :key="mes" :value="mes" v-text="mes"></option>
                                </select>
                            </div>
                            <div class="tcelda" style="width:10px"></div>
                            <div class="tcelda" >
                                <select class="form-control" v-model="ges">
                                    <option value="2019">2019</option><option value="2020">2020</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <center class="taltura">
                        <input type="checkbox" v-model="chkdescuento"> Pago al descuento
                    </center>
                    <span v-if="!chkdescuento">Nro Operación: </span>
                    <input v-if="!chkdescuento" type="text" class="form-control" v-model="nrdocumento">
                    Fecha: <span class="txtasterisco"></span>
                    <input type="date" class="form-control" v-model="fecha">
                    Importe: <span class="txtasterisco"></span>
                    <div class="input-group">
                        <input type="text" class="form-control text-right" v-model="importe">
                        <div class="input-group-append"><span class="input-group-text">Bs.</span></div>
                    </div>
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
import * as reporte from '../../functions.js';

export default {
    props: ['regEstablecimiento'],

    data(){ return {
        modalAsignacion:0, modalPago:0, accion:'', jsfechas:'', ipbirt:'', buscar:'',
        divAsignaciones:1, divValidado:1, nrgrupo:1, cantgrupos:'',
        regAsignacion:[], regAmbiente:[], regCliente:[],
        arrayAsignaciones:[], arrayAmbientes:[], arrayPagos:[],
        arrayDocumentos:[], arrayIDdocumentos:[], 
        idasignacion:'', nrasignacion:'', fechasolicitud:'', fechaentrada:'', fechasalida:'', 
        mesboleta:'', ocupantes:'', obs1:'', per:'', ges:'',
        idpago:'', concepto:'', periodo:'', nrdocumento:'', fecha:'', importe:'', chkdescuento:0,
        arrayOrdinal:['','Primer','Segundo','Tercer','Cuarto','Quinto','Sexto','Séptimo'],
        arrayMeses:['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
    }},

    methods:{
        listaAmbientes(idestablecimiento,nrgrupo){ 
            this.nrgrupo=nrgrupo;
            var url='/ser_ambiente/listaAmbientes?idestablecimiento='
                +idestablecimiento+'&piso='+nrgrupo;
            axios.get(url).then(response=>{
                this.arrayAmbientes=response.data.ambientes;
                this.ipbirt=response.data.ipbirt;
            });
            url='/ser_asignacion/listaAsignaciones?idestablecimiento='
                +idestablecimiento+'&piso='+nrgrupo;
            axios.get(url).then(response=>{
                this.arrayAsignaciones=response.data.asignaciones;
            });
        },

        listaAmbientesSocio(idestablecimiento,buscar){ 
            this.buscar=buscar;
            var url='/ser_ambiente/listaAmbientesSocio?idestablecimiento='
                +idestablecimiento+'&buscar='+buscar;
            axios.get(url).then(response=>{
                this.arrayAmbientes=response.data.ambientes;
                this.ipbirt=response.data.ipbirt;
            });
            url='/ser_asignacion/listaAsignaciones?idestablecimiento='
                +idestablecimiento;
            axios.get(url).then(response=>{
                this.arrayAsignaciones=response.data.asignaciones;
            });
        },

        liberarAmbiente(idambiente, idasignacion, idestablecimiento) { console.log(idambiente, idasignacion);
            swal({
                title: "Liberar Ambiente",
                text: "El ambiente se liberara para nueva asignacion",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Si, liberara",
                cancelButtonText: "No liberar",
                }).then((result) => {
                    if (result.value) {
                        let me = this;
                        axios.put('/ser_ambiente/liberarAmbiente',{
                            'idambiente': idambiente,
                            'idasignacion': idasignacion,
                        }).then(function (response) {
                            swal(
                            'Ambiente Liberado!',
                            ''
                            )   
                            me.listaAmbientes(idestablecimiento,1);
                            me.divAsignaciones=1;                            
                            me.idcliente='';
                        }).catch(function (error) {
                            console.log(error);
                        });
                    } 
                    else if (result.dismiss === swal.DismissReason.cancel) {                        
                    }
                })
        },

        listaDocumentos(){
            var url='/par_documento/listaDocumentos?iddocumentos='+this.regEstablecimiento.iddocumentos;
            axios.get(url).then(response=>{
                this.arrayDocumentos=response.data.documentos;
            });
        },

        listaPagos(idasignacion){
            var url='/ser_pago/listaPagos?idasignacion='+idasignacion;
            axios.get(url).then(response=>{
                this.arrayPagos=response.data.pagos;
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

        vence(idambiente){  
            for(var i=0; i<this.arrayAsignaciones.length; i++){
                if(this.arrayAsignaciones[i].idambiente==idambiente) {

                var myDate = new Date();
                var thisMonth = new Date(myDate.getFullYear(), myDate.getMonth(), 1);                
                var hoy = formatDate(thisMonth);
                
                function padLeft(n){
                    return ("00" + n).slice(-2);
                }

                function formatDate(){        
                    var d = new Date,
                        dformat = [ d.getFullYear(),                                    
                                    padLeft(d.getMonth()+1),
                                    padLeft(d.getDate())
                                    ].join('-');
                    return dformat
                }
                
                var fechaInicio = new Date(hoy).getTime(); 
                var fechaFin    = new Date(this.arrayAsignaciones[i].fechasalida).getTime();
                var diff = fechaFin - fechaInicio; 
                var dias = (diff/(1000*60*60*24) );

                return dias;
                }
            }
        },

        verIDcliente(idcliente){
            this.idcliente=idcliente;
            this.divValidado=1; 
            // var url='/socio/verSocio?op=viv&idsocio='+idcliente;
            // axios.get(url).then(response=>{
            //     var regSocio=response.data.socio[0];               
            //     if(regSocio.idestadocivil!=2) this.divValidado=0;
            // });
        },

        cualAsignacion(idambiente){
            for(var i=0; i<this.arrayAsignaciones.length; i++){
                if(this.arrayAsignaciones[i].idambiente==idambiente)
                   this.verAsignacion(this.arrayAsignaciones[i]); 
            }
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
            window.scroll({top:0,left:0,behavior:'smooth'});
            this.regAmbiente=ambiente;
            this.modalAsignacion=1; this.regAsignacion.idasignacion='';
            this.accion=1;
            this.divValidado=1;
            this.listaDocumentos();
            this.idcliente='';
            this.fechasolicitud='';
            this.nrasignacion='';
            this.fechaentrada='';
            this.fechasalida='';
            this.ges=''; this.per='';
            this.ocupantes='';
            this.obs1=''; 
            this.regAsignacion.idcliente='';
            this.arrayIDdocumentos=[];
            this.$validator.reset();
        },

        editarAsignacion(asignacion){ 
            window.scroll({top:0,left:0,behavior:'smooth'});
            this.modalAsignacion=1;
            this.accion=2;
            this.listaDocumentos();
            this.idasignacion=asignacion.idasignacion;
            this.fechasolicitud=asignacion.fechasolicitud;
            this.nrasignacion=asignacion.nrasignacion;
            this.fechaentrada=asignacion.fechaentrada;
            this.fechasalida=asignacion.fechasalida;
            this.per=asignacion.mesboleta.substr(0,3);
            this.ges=asignacion.mesboleta.substr(-4);
            this.ocupantes=asignacion.ocupantes;
            this.obs1=asignacion.obs1;
            this.arrayIDdocumentos=JSON.parse('['+asignacion.iddocumentos+']');
        },

        validarAsignacion(){
            this.$validator.validateAll().then(result=>{
                if(!result){ swal('Datos no válidos','Revise los errores','error'); return; }
                this.accion==1?this.storeAsignacion():this.updateAsignacion();
            });
        },

        storeAsignacion(){
            swal({ title:'Procesando...',text:'Un momento por favor', type:'warning',
                showCancelButton:false, showConfirmButton:false, 
                allowOutsideClick: false, allowEscapeKey: false, allowEnterKey: false,
                onOpen:() => { swal.showLoading() }
            });            
            axios.post('/ser_asignacion/storeAsignacion',{
                'idcliente':this.idcliente,
                'tipocliente':'s',
                'idambiente':this.regAmbiente.idambiente,
                'nrasignacion':this.nrasignacion,
                'fechasolicitud':this.fechasolicitud,
                'mesboleta':this.per+'/'+this.ges,
                'ocupantes':this.ocupantes,
                'iddocumentos':this.arrayIDdocumentos.join(),
                'idimplementos':'',
                'fechaentrada':this.fechaentrada,
                'fechasalida':this.fechasalida,
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
            axios.put('/ser_asignacion/updateAsignacion',{
                'idasignacion':this.idasignacion,
                'nrasignacion':this.nrasignacion,
                'fechasolicitud':this.fechasolicitud,
                'mesboleta':this.per+'/'+this.ges,
                'ocupantes':this.ocupantes,
                'iddocumentos':this.arrayIDdocumentos.join(),
                'idimplementos':'',
                'fechaentrada':this.fechaentrada,
                'fechasalida':this.fechasalida,
                'horaentrada':'',
                'horasalida':'',
                'fechadefuncion':'',
                'idresponsable':'',
                'obs1':this.obs1
            }).then(response=>{
                swal('Datos actualizados','','success');
                this.verAsignacion(this.regAsignacion);
            });
        },

        nuevoPago(){
            window.scroll({top:0,left:0,behavior:'smooth'});
            this.modalPago=1;
            this.accion=1;
            this.concepto='';
            this.nrdocumento='';
            this.modopago='';            
            this.fecha='';
            this.importe='';
            this.chkdescuento=0;   
            this.$validator.reset();
        },

        editarPago(pago){
            window.scroll({top:0,left:0,behavior:'smooth'});
            this.modalPago=1;
            this.accion=2;
            this.idpago=pago.idpago;
            this.concepto=pago.concepto;
            this.nrdocumento=pago.nrdocumento;
            this.fecha=pago.fecha;
            this.importe=pago.importe;
            this.chkdescuento=this.modopago==3?1:0;
            if(pago.concepto=='Garantía') return;
            this.per=pago.periodo.substr(0,3);
            this.ges=pago.periodo.substr(-4);
        },

        storePago(){
            swal({ title:'Procesando...',text:'Un momento por favor', type:'warning',
                showCancelButton:false, showConfirmButton:false, 
                allowOutsideClick: false, allowEscapeKey: false, allowEnterKey: false,
                onOpen:() => { swal.showLoading() }
            });
            var glosa=this.regEstablecimiento.nomestablecimiento+'. Pago de '+this.concepto
                +' por el periodo '+this.per+'/'+this.ges
                +', según depósito Nro '+this.nrdocumento;
            axios.post('/ser_pago/storePago',{
                'idasignacion':this.regAsignacion.idasignacion,
                'concepto':this.concepto,
                'periodo':this.per+'/'+this.ges,
                'nrdocumento':this.nrdocumento,
                'modopago':this.chkdescuento?3:2,
                'idperfilcuentamaestro':this.chkdescuento?10:9, 
                'fecha':this.fecha,
                'importe':this.importe,
                'idresponsable':0,
                'glosa':glosa,
                'idfilial':this.regEstablecimiento.idfilial,
                'numpapeleta':this.regCliente.numpapeleta
            }).then(response=>{
                swal('Pago registrado correctamente','','success');
                this.modalPago=0;
                this.listaPagos(this.regAsignacion.idasignacion);
            });
        },

        updatePago(){
            var glosa=this.regEstablecimiento.nomestablecimiento+'. Pago de '+this.concepto
                +' por el periodo '+this.per+'/'+this.ges
                +', según depósito Nro '+this.nrdocumento;
            axios.put('/ser_pago/updatePago',{
                'idpago':this.idpago,
                'concepto':this.concepto,
                'periodo':this.per+'/'+this.ges,
                'nrdocumento':this.nrdocumento,
                'modopago':this.chkdescuento?3:2,
                'idperfilcuentamaestro':this.chkdescuento?10:9, 
                'fecha':this.fecha,
                'importe':this.importe,
                'idresponsable':0,
                'glosa':glosa
            }).then(response=>{
                swal('Datos actualizados','','success');
                this.modalPago=0;
                this.listaPagos(this.regAsignacion.idasignacion);
            });
        },

        atras() { 
            this.divAsignaciones=1;
            this.listaAmbientes(this.regEstablecimiento.idestablecimiento,this.regAmbiente.piso);
        },

        reporteContrato(asignacion){
            var url=[];
            url.push('http://'+this.ipbirt+':8080');
            url.push('/birt-viewer/frameset?__report=reportes/servicios');
            url.push('/ser_jpintocontrato.rptdesign'); 
            url.push('&idasignacion='+asignacion.idasignacion); 
            url.push('&__format=pdf'); 
            reporte.viewPDF(url.join(''),'Contrato');
        },

        reportePago(pago){
            var url=[];
            url.push('http://'+this.ipbirt+':8080');
            url.push('/birt-viewer/frameset?__report=reportes/servicios');
            url.push('/ser_pago.rptdesign'); 
            url.push('&idpago='+pago.idpago); 
            url.push('&__format=pdf');
            reporte.viewPDF(url.join(''),'Credencial');
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
