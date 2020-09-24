<template>
<main>
    <div>
        <button v-if="divAsignaciones===0" class="btn btn-primary" @click="atras()">nivel atras</button>
    </div>
    <div class="card" v-if="divAsignaciones">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10 titcard">
                    <div class="tablatit">
                        <div class="tcelda"><span v-text="regEstablecimiento.sector"></span>
                            BLOQUE "<span v-text="String.fromCharCode(nrgrupo+64)"></span>"</div>
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
            <br>
            <table class="table-bordered" align="center">
                <tr v-for="fil in fils" :key="fil" class="bloquefila">
                    <td class="card-header titcard" v-text="String.fromCharCode(nrgrupo+64)+(fils+1-fil)"></td>
                    <td v-for="ambiente in arrayAmbientes" :key="ambiente.id" class="bloquecelda" 
                        v-if="ambiente.piso==(fils+1-fil)" 
                        @click="ambiente.ocupado?cualAsignacion(ambiente.idambiente):nuevaAsignacion(ambiente)" 
                        :class="ambiente.ocupado?'ocupado':''" title="">
                            <span v-text="ambiente.codambiente.substr(-2)"></span>
                    </td>
                </tr>
            </table>
            <br>
        </div>       
    </div>

    <!-- FICHA ASIGNACION  FICHA ASIGNACION FICHA ASIGNACION FICHA ASIGNACION FICHA ASIGNACION -->
    <!-- FICHA ASIGNACION  FICHA ASIGNACION FICHA ASIGNACION FICHA ASIGNACION FICHA ASIGNACION -->
    <div class="row" v-if="!divAsignaciones">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header titcard">Difunto</div>
                <div class="card-body">
                    <h4 class="titcard text-center">
                        <span v-text="regCliente.nomgrado"></span>
                        <span v-text="regCliente.nombre"></span>
                        <span v-text="regCliente.apaterno"></span>
                        <span v-text="regCliente.amaterno"></span>
                    </h4>
                    <div class="row">
                        <div class="col-md-6">
                            <span class="titcampo">Fuerza:</span>
                            <span v-text="regCliente.nomfuerza">Fuerza:</span>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <span class="titcampo">CI:</span>
                            <span v-text="regCliente.ci+regCliente.abrvdep"></span>
                        </div>
                    </div> 
                    <center><br>
                        <span class="titcampo">Fecha Defunción: </span>
                        <span v-text="jsfechas.fechames(regAsignacion.fechadefuncion)"></span>
                    </center>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header titcard">Inhumación</div>
                <div class="card-body">
                    <div class="tablacen">
                        <div class="tfila">
                            <div class="tcelda titcampo">Fecha:</div>
                            <div class="tcelda" v-text="jsfechas.fechames(regAsignacion.fechaentrada)"></div>
                        </div>
                        <div class="tfila">
                            <div class="tcelda titcampo">Nro Orden:</div>
                            <div class="tcelda" v-text="regAsignacion.nrasignacion"></div>
                        </div>
                        <div class="tfila">
                            <div class="tcelda titcampo">Responsable:</div>
                            <div class="tcelda" v-text="regResponsable.nombre+' '+regResponsable.apaterno+' '+regResponsable.amaterno"></div>
                        </div>
                        <div class="tfila">
                            <div class="tcelda titcampo">Nro CI:</div>
                            <div class="tcelda" v-text="regResponsable.ci"></div>
                        </div>
                        <div class="tfila">
                            <div class="tcelda titcampo">Relación:</div>
                            <div class="tcelda" v-text="regResponsable.parentesco"></div>
                        </div>
                        <div class="tfila">
                            <div class="tcelda titcampo">Obs:</div>
                            <div class="tcelda" v-text="regAsignacion.obs1"></div>
                        </div>
                        <br>
                    </div>
                    <center>
                        <button class="btn btn-primary" @click="editarAsignacion(regAsignacion)">Modificar</button>
                    </center>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header titcard">Exhumación</div>
                <div class="card-body">
                    <center>
                        <span style="display:none">{{op=regAsignacion.fechasalida?2:1}}</span> 
                        <button class="btn btn-primary" @click="editarAsignacion(regAsignacion,op)">Registrar</button>
                    </center>
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
                            <button class="btn btn-primary" style="margin-top:0px" @click="extractoPagos(regAsignacion.idcliente,regAsignacion.tipocliente)">Extracto Pagos</button>
                            <button class="btn btn-primary" style="margin-top:0px" @click="nuevoPago()">Registrar Pago</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-sm">
                        <thead class="tcabecera">
                            <tr>
                                <th></th>
                                <th>Nr</th>
                                <th>Responsable</th>
                                <th>Concepto</th>
                                <th>Fecha</th>
                                <th>Cod. Op.</th>
                                <th>Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(pago,index) in arrayPagos" :key="pago.id">
                                <td align="center">
                                    <button class="btn btn-warning btn-sm icon-pencil" @click="editarPago(pago)"></button>
                                    <button class="btn btn-warning btn-sm icon-printer" @click="reporteMausoleo(pago,regAsignacion.tipocliente)"></button>
                                </td>
                                <td v-text="index+1" align="center"></td>
                                <td v-text="pago.nombre+' '+pago.apaterno"></td>
                                <td v-text="pago.concepto"></td>
                                <td v-text="jsfechas.fechames(pago.fecha)" align="center"></td>
                                <td v-text="pago.nrdocumento"></td>
                                <td v-text="pago.importe" align="right"></td>
                            </tr>
                        </tbody>
                    </table>                    
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
                    <h4 class="modal-title"><span v-text="accion==1?'Nueva':'Modificar'"></span> Asignación</h4>
                    <button class="close" @click="modalAsignacion=0">x</button>
                </div>
                <div class="modal-body" style="overflow-y:scroll; height:420px;">
                    <h4 class="titsubrayado" v-text="regEstablecimiento.nomestablecimiento"></h4>
                    <div class="row">
                        <div class="col-md-6">
                            <span class="titcampo">Código:</span> <span v-text="regAmbiente.codambiente"></span>
                        </div>
                        <div class="col-md-6 text-right">
                            <span class="titcampo">Canon Alquiler:</span>
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
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="titazul">Contrato</h4>
                            Defunción: <span class="txtasterisco"></span>
                            <input type="date" class="form-control" v-model="fechadefuncion"
                                name="def" :class="{'invalido':errors.has('def')}" v-validate="'required'">
                            <p class="txtvalidador" v-if="errors.has('def')">Dato requerido</p>
                            Inhumación: <span class="txtasterisco"></span>
                            <input type="date" class="form-control" v-model="fechaentrada"
                                name="inh" :class="{'invalido':errors.has('inh')}" v-validate="'required'">
                            <p class="txtvalidador" v-if="errors.has('inh')">Dato requerido</p>                                
                            Nro de Orden: <span class="txtasterisco"></span>
                            <input type="text" class="form-control" v-model="nrasignacion"
                                name="nra" :class="{'invalido':errors.has('nra')}" v-validate="'required'">
                            <p class="txtvalidador" v-if="errors.has('nra')">Dato requerido</p>
                            <br>
                        </div>

                        <div class="col-md-6">
                            <h4 class="titazul">Responsable:</h4>
                            Nombre del Familiar: <span class="txtasterisco"></span>
                            <select class="form-control" v-model="idresponsable" @change="verResponsable(idresponsable)"
                                name="res" :class="{'invalido':errors.has('res')}" v-validate="'required'">
                                <option v-for="beneficiario in arrayBeneficiarios" :key="beneficiario.idbeneficiario"
                                    :value="beneficiario.idbeneficiario" v-text="beneficiario.nombre+' '+beneficiario.apaterno">
                                </option>                                        
                            </select>
                            <p class="txtvalidador" v-if="errors.has('res')">Seleccione un responsable</p>
                            <div v-if="idresponsable" class="tabla100">
                                <div class="tfila">
                                    <div class="tcelda titcampo">Relación:</div>
                                    <div v-if="regResponsable.resp" class="tcelda">Socio Institucion</div>
                                    <div v-else class="tcelda" v-text="regResponsable.parentesco"></div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda titcampo">Nro C.I.:</div>
                                    <div class="tcelda" v-text="regResponsable.ci+' '+regResponsable.abrvdep"></div>
                                </div>
                                <div v-if="regResponsable.resp" class="tfila">
                                    <div class="tcelda titcampo">Papeleta:</div>
                                    <div class="tcelda" v-text="regResponsable.resp"></div>
                                </div>
                                <div v-else class="tfila">
                                    <div class="tcelda titcampo">Celular:</div>
                                    <div class="tcelda" v-text="regResponsable.telcelular"></div>
                                </div>                                
                            </div>
                            <br>
                            <h4 class="titazul">Documentos</h4>
                            <ul style="list-style:none; padding-left:10px;">
                                <li v-for="documento in arrayDocumentos" :key="documento.iddocumento">
                                    <input type="checkbox" v-model="arrayIDdocumentos" :value="documento.iddocumento"> 
                                    <span v-text="documento.nomdocumento"></span> 
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            Observaciones:
                            <input type="text" class="form-control" v-model="obs1">
                            <p class="txtobligatorio text-right"></p>
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
                    <textarea class="form-control" v-model="concepto"></textarea>
                    Nro Operación: <span class="txtasterisco"></span>
                    <input type="text" class="form-control" v-model="nrdocumento">
                    Fecha: <span class="txtasterisco"></span>
                    <input type="date" class="form-control" v-model="fecha">
                    Importe: <span class="txtasterisco"></span>
                    <div class="input-group">
                        <input type="text" class="form-control text-right" v-model="importe">
                        <div class="input-group-append"><span class="input-group-text">Bs.</span></div>
                    </div>
                    Responsable <span class="txtasterisco"></span>
                    <select class="form-control" v-model="idresponsable" @change="verResponsable(idresponsable)">
                        <option v-for="beneficiario in arrayBeneficiarios" :key="beneficiario.idbeneficiario"
                            :value="beneficiario.idbeneficiario" v-text="beneficiario.nombre+' '+beneficiario.apaterno">
                        </option>                                        
                    </select>
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

export default {
    props:['regEstablecimiento'],

    data(){return {
        modalAsignacion:0, modalPago:'', divAsignaciones:1, jsfechas:'', accion:'',
        nrgrupo:1, cantgrupos:'', fils:'', codsector:'', ipbirt:'',
        arrayAmbientes:[], arrayAsignaciones:[], arrayBeneficiarios:[],
        arrayDocumentos:[], arrayIDdocumentos:[], arrayPagos:[],
        regCantgrupos:[], regAmbiente:[], regAsignacion:[], regCliente:[], regResponsable:[],
        idasignacion:'', idresponsable:'', nrasignacion:'', fechadefuncion:'', fechaentrada:'', obs1:'',
        idpago:'', concepto:'', periodo:'', nrdocumento:'', fecha:'', importe:'',
        tipo:'',
    }},

    methods:{

        getRutasReports (){ 
            let me=this;
            var url= '/ser_reportes';
            axios.get(url).then(function (response) {
                var respuesta= response.data; ;                                    
                me.pagoMausoleo = respuesta.REP_PAGOMAUSOLEO;                
                me.pagoExtracto = respuesta.REP_EXTRACTO_MAUSOLEO;                
                me.pagoMausoleoBe = respuesta.REP_PAGOMAUSOLEO_BE;                
                me.pagoExtractoBe = respuesta.REP_EXTRACTO_MAUSOLEO_BE;                
            })
            .catch(function (error) {
                console.log(error); 
            });
        },

        verIDcliente(valores){ //console.log(valores);
            var x = valores.split("-"); // separamos tipo de id
            this.idcliente=x[0];
            this.tipo=x[1]; //asignamos el tipo de socio
            if (x[1]=='beneficiario'){
                this.tipo='b'; //asignamos el tipo de socio
                this.socioResponsable(x[2]); //buscamos al responsable que wseria el socio
            }
            else {
                this.listaBeneficiarios(x[0]);
            }
            
        },

        reporteMausoleo(pago,tipo){ 
            if (tipo=='s') {
                var url=this.pagoMausoleo +'&idpago='+pago.idpago;          
                this.reporte_resumen(url,'Pago Mausoleo');
            }
            else if (tipo=='b') {
                var url=this.pagoMausoleoBe +'&idpago='+pago.idpago;          
                this.reporte_resumen(url,'Pago Mausoleo');
            }
                
        },

        extractoPagos(idsocio,tipo){
            if (tipo=='s') {
                var url=this.pagoExtracto +'&idcliente='+idsocio; 
                this.reporte_resumen(url,'Extracto pago mausoleo');
            }
            if (tipo=='b') { 
                var url=this.pagoExtractoBe +'&idcliente='+idsocio; 
                this.reporte_resumen(url,'Extracto pago mausoleo');
            }
            
        },

        reporte_resumen(url,title) {
            console.log(url);
            _pl._vm2154_12186_135(url,title);
        },

        atras() {
            this.divAsignaciones=1;
            this.listaAmbientes(this.regEstablecimiento.idestablecimiento,1);
        },

        listaAsignaciones(idestablecimiento,nrgrupo){
            var url='/ser_asignacion/listaAsignaciones?idestablecimiento='
                +this.regEstablecimiento.idestablecimiento+'&bloque='+String.fromCharCode(nrgrupo+64);
            axios.get(url).then(response=>{
                this.arrayAsignaciones=response.data.asignaciones;
            });
        },

        listaAmbientes(idestablecimiento,nrgrupo){
            this.nrgrupo=nrgrupo;
            this.fils=Math.floor(this.regCantgrupos[nrgrupo]/100);
            this.cols=Math.floor(this.regCantgrupos[nrgrupo]%100);
            var url='/ser_ambiente/listaAmbientes?idestablecimiento='
                +idestablecimiento+'&bloque='+this.codsector+String.fromCharCode(nrgrupo+64);
            axios.get(url).then(response=>{
                this.arrayAmbientes=response.data.ambientes;
            });
            url='/ser_asignacion/listaAsignaciones?idestablecimiento='
                +this.regEstablecimiento.idestablecimiento+'&bloque='+this.codsector+String.fromCharCode(nrgrupo+64);
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

        listaBeneficiarios(idsocio){
            var url='/afi_beneficiario/listaBeneficiarios?idsocio='+idsocio+'&activo=1';
            axios.get(url).then(response=>{
                this.arrayBeneficiarios=response.data.beneficiarios;
            });
        },

        socioResponsable(idsocio){
            var url='/afi_beneficiario/socioResponsable?idsocio='+idsocio+'&activo=1';
            axios.get(url).then(response=>{
                this.arrayBeneficiarios=response.data.beneficiarios; 
            });
        },

        listaPagos(idasignacion,tipo){
            var url='/ser_pago/listaPagos?idasignacion='+idasignacion+'&tipo='+tipo;
            axios.get(url).then(response=>{
                this.arrayPagos=response.data.pagos;
            });
        },

        verResponsable(idbeneficiario){ //console.log(idbeneficiario);
            for(var i=0;i<this.arrayBeneficiarios.length;i++)
                if(this.arrayBeneficiarios[i].idbeneficiario==idbeneficiario) {
                    this.regResponsable=this.arrayBeneficiarios[i]; return;  }
        },

        ocupado(nrbloque,fil,col){
            if(col==3) return true; //funciona
        },

        cualAsignacion(idambiente){ console.log('s'+idambiente);
            for(var i=0; i<this.arrayAsignaciones.length; i++)
                if(this.arrayAsignaciones[i].idambiente==idambiente) {
                   this.verAsignacion(this.arrayAsignaciones[i]); return;
                }
        },

        async verAsignacion(asignacion){ 
            let response=await axios.get('/ser_asignacion/verAsignacion?idasignacion='+asignacion.idasignacion);
            this.regAsignacion=response.data.asignacion[0]; 

            if (asignacion.tipocliente=='b')
                response=await axios.get('/afi_beneficiario/socioResponsable?idsocio='+this.regAsignacion.idresponsable);
            else
                response=await axios.get('/afi_beneficiario/listaBeneficiarios?idbeneficiario='+this.regAsignacion.idresponsable);
        
            this.regResponsable=response.data.beneficiarios[0];
            
            response=await axios.get('/ser_ambiente/listaAmbientes?idambiente='+asignacion.idambiente);
            this.regAmbiente=response.data.ambientes[0];
            response=await axios.get('/ser_asignacion/verCliente?idcliente='+asignacion.idcliente+'&tipocliente='+asignacion.tipocliente);
            this.regCliente=response.data.cliente[0];
            this.arrayIDdocumentos=JSON.parse('['+this.regAsignacion.iddocumentos+']');
            this.listaPagos(asignacion.idasignacion,asignacion.tipocliente);
            this.divAsignaciones=0;
        },

        nuevaAsignacion(ambiente){
            this.regAmbiente=ambiente;
            this.modalAsignacion=1; this.regAsignacion.idasignacion='';
            this.accion=1;
            this.listaDocumentos();
            this.idcliente='';
            this.regAsignacion.idcliente='';
            this.fechadefuncion='';
            this.fechaentrada='';
            this.nrasignacion='';
            this.idresponsable='';
            this.$validator.reset();
        },

        editarAsignacion(asignacion){ console.log(asignacion.idcliente);
            window.scroll({top:0,left:0,behavior:'smooth'});
            this.modalAsignacion=1;
            this.accion=2;
            this.listaDocumentos();
            if (asignacion.tipocliente=='b')
                this.socioResponsable(asignacion.idresponsable);
            else
                this.listaBeneficiarios(asignacion.idcliente);
            this.idasignacion=asignacion.idasignacion;
            this.nrasignacion=asignacion.nrasignacion;
            this.fechadefuncion=asignacion.fechadefuncion;
            this.fechaentrada=asignacion.fechaentrada;
            this.idresponsable=asignacion.idresponsable;
            this.obs1=asignacion.obs1;
            this.arrayIDdocumentos=JSON.parse('['+asignacion.iddocumentos+']');
        },

        validarAsignacion(){
            this.$validator.validateAll().then(result=>{
                if(!result) { swal('Datos inválidos','Revise los errores','error'); return; } 
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
                'tipocliente':this.tipo,
                'idambiente':this.regAmbiente.idambiente,
                'nrasignacion':this.nrasignacion,
                'fechasolicitud':'',
                'mesboleta':'',
                'ocupantes':'',
                'iddocumentos':this.arrayIDdocumentos.join(),
                'idimplementos':'',
                'fechaentrada':this.fechaentrada,
                'horaentrada':'',
                'horasalida':'',
                'fechadefuncion':this.fechadefuncion,
                'idresponsable':this.idresponsable,
                'idrepresentante':'',
                'obs1':this.obs1
            }).then(response=>{
                swal('Asignación creada','Proceda a la verificación de pagos','success');
                this.listaAmbientes(this.regAmbiente.idestablecimiento,this.nrgrupo);
                this.modalAsignacion=0;
            });
        },

        updateAsignacion(){
             swal({ title:'Procesando...',text:'Un momento por favor', type:'warning',
                showCancelButton:false, showConfirmButton:false, 
                allowOutsideClick: false, allowEscapeKey: false, allowEnterKey: false,
                onOpen:() => { swal.showLoading() }
            });  
            axios.put('/ser_asignacion/updateAsignacion',{
                'idasignacion':this.regAsignacion.idasignacion,
                'nrasignacion':this.nrasignacion,
                'fechasolicitud':'',
                'mesboleta':'',
                'ocupantes':'',
                'iddocumentos':this.arrayIDdocumentos.join(),
                'idimplementos':'',
                'fechaentrada':this.fechaentrada,
                'horaentrada':'',
                'horasalida':'',
                'fechadefuncion':this.fechadefuncion,
                'idresponsable':this.idresponsable,
                'actividad':'',
                'idrepresentante':'',
                'obs1':this.obs1,  
            }).then(response=>{
                swal('Actualizado','Se han guardado los cambios realizados','success');
                this.cualAsignacion(this.regAmbiente.idambiente)
                this.modalAsignacion=0;
            });            
        },
                
        nuevoPago(){
            window.scroll({top:0,left:0,behavior:'smooth'});
            this.modalPago=1;
            this.accion=1;
            if (this.regAsignacion.tipocliente=='b')
                this.socioResponsable(this.regAsignacion.idresponsable);
            else
                this.listaBeneficiarios(this.regAsignacion.idcliente);
            this.concepto='';
            this.nrdocumento='';
            this.fecha='';
            this.importe='';
            this.idresponsable='';
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
            this.idresponsable=pago.idresponsable;
            this.listaBeneficiarios(this.regAsignacion.idcliente);
        },

        storePago(){
            swal({ title:'Procesando...',text:'Un momento por favor', type:'warning',
                showCancelButton:false, showConfirmButton:false, 
                allowOutsideClick: false, allowEscapeKey: false, allowEnterKey: false,
                onOpen:() => { swal.showLoading() }
            });
            var glosa=this.regEstablecimiento.nomestablecimiento+'. Pago '+this.concepto
                +', según depósito Nro '+this.nrdocumento;
            axios.post('/ser_pago/storePago',{
                'idasignacion':this.regAsignacion.idasignacion,
                'concepto':this.concepto,
                'periodo':'',
                'nrdocumento':this.nrdocumento,
                'modopago':2,
                'idperfilcuentamaestro':30, 
                'fecha':this.fecha,
                'importe':this.importe,
                'idresponsable':this.idresponsable,
                'glosa':glosa,
                'idfilial':this.regEstablecimiento.idfilial,
                'numpapeleta':this.regCliente.numpapeleta
            }).then(response=>{
                swal('Pago registrado correctamente','','success');
                this.modalPago=0;
                this.listaPagos(this.regAsignacion.idasignacion,this.regAsignacion.tipocliente);
            });
        },

        updatePago(){
            var glosa=this.regEstablecimiento.nomestablecimiento+'. Pago '+this.concepto
                +', según depósito Nro '+this.nrdocumento;
            axios.put('/ser_pago/updatePago',{
                'idpago':this.idpago,
                'concepto':this.concepto,
                'periodo':'',
                'nrdocumento':this.nrdocumento,
                'modopago':2,
                'idperfilcuentamaestro':30, 
                'fecha':this.fecha,
                'importe':this.importe,
                'idresponsable':this.idresponsable,
                'glosa':glosa
            }).then(response=>{
                swal('Datos actualizados','','success');
                this.modalPago=0;
                this.listaPagos(this.regAsignacion.idasignacion,this.regAsignacion.tipocliente);
            });
        },


    },

    mounted(){
        this.getRutasReports();
        this.jsfechas=jsfechas;
        this.regCantgrupos=JSON.parse('['+this.regEstablecimiento.cantgrupos+']');
        this.cantgrupos=this.regCantgrupos.length-1;//cant bloques
        this.codsector=this.regEstablecimiento.sector.substr(0,1);
        this.listaAmbientes(this.regEstablecimiento.idestablecimiento,1); 
    }
    

}
</script>
