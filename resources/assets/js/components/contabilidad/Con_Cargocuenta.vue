<template>
    <main class="main">
         <div class="breadcrumb" style="padding-top: 5px;padding-bottom: 5px;margin-bottom: 5px;">
            <div class="col-md-1" style="padding:0px">
                <button v-if="!divCargoPrincipal" class="btn btn-outline-primary cui-arrow-left" 
                style="font-size:22px; padding:5px;" @click="listaCargos()"></button>
            </div>
            <div class="col-md-10 text-center">
                <h4 v-if="divCargoPrincipal" class="nombrecliente">Cargos de Cuenta</h4>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card" v-if="divCargoPrincipal">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i>Cargos de Cuenta
                    <!-- <button type="button" class="btn btn-secondary" @click="abrirModal()">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button> -->
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <label class="form-control-label" for="text-input" style="margin-top: 7px;">Buscar Cargo de Cuenta: &nbsp;</label>
                                <input type="text" v-model="buscar" @keyup.enter="listarCargoCuenta(1,buscar,tipocargo,filtro)" class="form-control" placeholder="Buscar Cargo de Cuenta">
                                <button type="submit" title="Buscar Cargo de Cuenta"   @click="listarCargoCuenta(1,buscar,tipocargo,filtro)" class="btn btn-primary"><i class="fa fa-search"></i>Buscar</button>
                            </div>
                        </div>
                        <!-- <div class="col-3 col-form-label " style="border: 1px solid #c2cfd6 !important; border-radius: 5px;">
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" v-model="tipocargo" value="interno" checked @change="listarCargoCuenta(1,buscar,tipocargo,filtro)">Internos
                                </label>
                                </div>
                                <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" v-model="tipocargo" value="externo" @change="listarCargoCuenta(1,buscar,tipocargo,filtro)">Externos
                                </label>
                            </div>
                        </div> -->
                        <div class="col-md-3">
                            <div class="input-group">
                                 <select class="form-control" @change="listarCargoCuenta(1,buscar,tipocargo,filtro)"
                                    v-model="filtro">
                                    <option v-for="(tipofiltro,index) in arrayfiltros" :key="index" :value="index" v-text="tipofiltro"></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th style="width:90px">Opciones</th>
                                <th style="width:90px">Fecha Solcitud</th>
                                <th style="width:90px">Fecha Apertura</th>
                                <th style="width:80px"># Compr. Aprob.</th>
                                <th>a nombre de:</th>
                                <th>Filial</th>
                                <th>glosa</th>
                                <th>Monto</th>
                                <th>Solicitado por:</th>
                                <th>Cargo</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="cargocuenta in arrayCargocuenta" :key="cargocuenta.idsolccuenta">
                                <td>
                                    <template v-if="cargocuenta.estado_aprobado==0 || cargocuenta.estado_aprobado==3 || cargocuenta.estado==4">
                                        <button v-if="cargocuenta.estado!=4" type="button" @click="abrirModal('ccuenta','validar',cargocuenta)" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Realizar Cargo de Cuenta" :disabled="cargocuenta.estado_aprobado==0">
                                            <i class="icon-check"></i>
                                        </button> 
                                    </template>
                                    <template v-else-if="cargocuenta.estado_aprobado==2">
                                        <button type="button" @click="detallesObservacion(cargocuenta.idsolccuenta)" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Detalles Observacion">
                                            <i class="icon-eye"></i>
                                        </button>
                                    </template>
                                    <template v-else-if="cargocuenta.estado_aprobado==4">
                                        <button type="button" @click="repdocobligacion(cargocuenta.idsolccuenta,cargocuenta.sidirectorio)" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Doc. Obligacion">
                                                <i class="cui-file"></i>
                                        </button> 
                                    </template>
                                    <template v-else >
                                        <div v-if="cargocuenta.seg_descargoccuenta==0">
                                            <button type="button" @click="cargarvue(cargocuenta)" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Seguimiento">
                                                <i class="icon-loop"></i>
                                            </button>  
                                            <button type="button" @click="repdocobligacion(cargocuenta.idsolccuenta,cargocuenta.sidirectorio)" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Doc. Obligacion">
                                                <i class="cui-file"></i>
                                            </button>  
                                        </div>
                                        <div v-else-if="cargocuenta.seg_descargoccuenta==1">
                                            <button type="button" @click="cargarvue(cargocuenta)" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Seguimiento">
                                                <i class="icon-loop"></i>
                                            </button>    
                                            <button type="button" @click="reporteSeguimiento(cargocuenta.idsolccuenta)" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Seguimiento">
                                                <i class="cui-justify-left"></i>
                                            </button>    
                                        </div>
                                        <div v-else>
                                            <button type="button" @click="reporteSeguimiento(cargocuenta.idsolccuenta)" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Seguimiento">
                                                <i class="cui-justify-left"></i>
                                            </button>    
                                        </div>
                                        <!-- <button type="button" @click="abrirModal('descargoccuenta','descargo',cargocuenta)" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Seguimiento">
                                            <i class="icon-loop"></i>
                                        </button> -->
                                    </template>
                                </td>
                                <td v-text="cargocuenta.fecha_solicitud"></td>
                                <td v-text="cargocuenta.fecha_apertura"></td>
                                <td v-text="cargocuenta.cod_comprobante"></td>
                                <td v-text="cargocuenta.nombres"></td>
                                <td v-text="cargocuenta.sigla"></td>
                                <td v-text="cargocuenta.glosa"></td>
                                <td style="text-align:right">{{cargocuenta.monto | currency}}</td>
                                <td v-text="cargocuenta.username"></td>
                                <td v-text="cargocuenta.nomrol"></td>
                                <!-- <td v-text="socio.nomgrado"></td>-->
                                
                                
                                <td>
                                    <span v-if="cargocuenta.estado_aprobado==3" class="badge badge-info">Ya Desembolsado</span>
                                    <div v-else-if="cargocuenta.estado_aprobado==1">
                                        <span class="badge badge-success">Aprobado</span><br />
                                        <span v-if="cargocuenta.seg_descargoccuenta==0" class="badge badge-danger">Sin Descargo</span>
                                        <span v-else-if="cargocuenta.seg_descargoccuenta==1" class="badge badge-info">Con Descargo y Saldo</span>
                                        <span v-else class="badge badge-success">Con Desacargo y Saldo 0</span>
                                    </div>
                                    <div v-else-if="cargocuenta.estado_aprobado==4">
                                        <span class="badge badge-warning">Comprobante Borrador</span><br />
                                    </div>
                                    <span v-else-if="cargocuenta.estado_aprobado==2" class="badge badge-danger">Observado</span>
                                    <span v-else-if="cargocuenta.estado_aprobado==0" class="badge badge-warning">No Desembolsado</span>
                                </td>
                            </tr>                                
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,tipocargo,filtro)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,tipocargo,filtro)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,tipocargo,filtro)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <con_descargo @cerrardescargo="cerrarvuedescargo" ref="vuedescargo"></con_descargo>
        </div>
         <!-- MODAL Validar Cargo de cuenta --> 
        <div class="modal fade " tabindex="-1"  role="dialog"   aria-hidden="true" id="ccuenta"  data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content animated fadeIn">
                    <div class="modal-header justify-content-between headerpadding"> 
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <div>
                            <div class="form-group row">
                                <strong><label style="padding-top: 18px;padding-right: 10px;">Fecha:</label></strong>
                                <div>
                                    <!-- <input  type="date" 
                                            v-model="fecharegistro" 
                                            class="form-control" 
                                            :max="fechaactual"
                                            :min="fechamin"
                                            v-validate.initial="'required'"
                                            name="Fecha"
                                            style="margin-top: 10px;"> -->
                                             <input  type="date" 
                                            v-model="fecharegistro" 
                                            class="form-control" 
                                            :max="fechaactual"
                                            v-validate.initial="'required'"
                                            name="Fecha"
                                            style="margin-top: 10px;">
                                    <span class="text-error" v-if="fecharegistro==''">No debe estar vacio</span>
                                </div>
                                <button type="button"  class="close" @click="cerrarModal('ccuenta')" aria-label="Close" style="width: 62px;">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                        </div>
                    </div> 

                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label ><strong>Asignado a:</strong></label>
                                <label  v-text="asignadoa"></label>
                            </div>
                            <div class="form-group col-md-3">
                                <label ><strong>Monto:</strong></label>
                                <label  v-text="monto + ' Bs.'" style="text-align:right"></label>
                            </div>
                            
                            <div class="form-group col-md-3">
                                <label ><strong>Tipo Cargo:</strong></label>
                                <label  v-text="tipocargo" style="text-align:right">Tipo Cargo:</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row col-md-9">
                                <label class="col-md-2"><strong>Glosa:</strong></label>
                                <div class="col-md-10">
                                    <textarea 
                                        :class="{'form-control': true, 'is-invalid textareaerror': errors.has('glosa')}"  
                                        rows="2" 
                                        v-model="glosa"
                                        name="glosa"
                                        v-validate.initial="'required'" >
                                    </textarea>
                                    <span class="text-error">{{ errors.first('glosa')}}</span> 
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label><strong>Solicitado por:</strong></label>
                                <label v-text="solicitadopor"></label>
                            </div>
                        </div>
                        <hr style="margin-top: 5px;margin-bottom: 5px;">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Cuenta de Desembolso:</label>
                            <!-- <div class="col-md-8"> -->
                            <label class="col-md-9 col-form-label"><strong v-text="codcuenta+' - '+ nomcuentadesembolso"></strong>&nbsp; &nbsp;&nbsp;<label v-if="numchequeregistrado">Nº Cheque:&nbsp;</label><strong v-if="numchequeregistrado" v-text="numchequeregistrado"></strong> </label>
                                <!-- <Ajaxselect  v-if="clearSelected"
                                    ruta="/con_cuentas/selectBuscarcuenta2?buscar=" @found="cuentas" @cleaning="clean"
                                    resp_ruta="cuentas"
                                    labels="cuentas"
                                    placeholder="Ingrese texto" 
                                    idtabla="idcuenta"
                                    :id="idcuentadesembolso"
                                    :clearable='false'>
                                </Ajaxselect>
                                <span class="text-error" v-if="idcuenta.length==0">Debe Seleccionar una cuenta</span>  -->
                                
                            <!-- </div> -->
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Documento:</label>
                            <div class="col-md-4">
                                <select 
                                    :class="{'form-control': true, 'is-invalid selecterror': errors.has('Tipo Documento')}"  
                                    v-validate.initial="'required'" 
                                    v-model="tipodocumento"
                                    name="Tipo Documento">
                                    <option value="" selected="selected" disabled>Seleccione...</option>
                                    <option v-for="tipodocumento in arrayDocumento" :key="tipodocumento.iddocumento" :value="tipodocumento.nomdocumento" v-text="tipodocumento.nomdocumento"></option>
                                </select>
                                <span class="text-error">{{ errors.first('Tipo Documento')}}</span> 
                            </div>
                            <label class="col-md-3 col-form-label">Num Documento:</label>
                            <div class="col-md-3">
                                <input :class="{'form-control': true, 'is-invalid texterror': errors.has('numdoc')}" type="text" 
                                    v-validate.initial="'required'"
                                    v-model="numdoc"
                                    placeholder="Ingrese Numero..."
                                    name="numdoc">
                                <span class="text-error">{{ errors.first('numdoc')}}</span> 
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer"> 
                        <button class="btn btn-secondary" type="button" @click="cerrarModal('ccuenta')">Cerrar</button>                
                        <button :disabled="!iscompleteccuenta" class="btn btn-primary" type="button" @click="guardarAsientoccuenta()">Registrar Contablemente</button>
                    </div>  
                </div>  
            </div>
        </div>

            <!-- fin modal validar cuenta -->
            <!-- modal conciliacion bancaria -->
            <div class="modal fade " tabindex="-1"  role="dialog"   aria-hidden="true" id="conciliacionbancaria"  data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModalConciliacion" ></h4><br/>
                            
                            <button type="button" class="close" @click="cerrarModalConciliacion()" aria-label="Close">
                                <span  aria-hidden="true">x</span>    
                            </button>
                        </div>
                        <div class="modal-body">
                            <div style="height: 200px; overflow: scroll;">
                            <label>Registrar Cheque en Cuenta: <strong v-text="idcuenta[1]"></strong></label><br />
                            <strong>Historial:</strong>

                             <table class="table table-hover table-sm overflow-auto">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Nº de cheque</th>
                                        <th>Fecha Registro</th>
                                        <th>Portador</th>
                                        <th>Importe</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="conciliacion in arrayConciliacion" :key="conciliacion.idmovimiento" :class="[conciliacion.externo ? 'table-info' : '']">
                                        <td v-text="conciliacion.fecha"></td>
                                        <td v-text="conciliacion.numdocumento"></td>
                                        <td v-text="conciliacion.nomempleado"></td>
                                        <td v-text="conciliacion.importe + ' Bs.'" style="text-align:right"></td>
                                    </tr>                                
                                </tbody>
                            </table>
                            </div>
                            <hr>
                            <strong>Registro de Cheque:</strong>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <div class="form-group row">
                                        <strong class="col-md-3 col-form-label" for="text-input">Portador:</strong>
                                        <div class="col-md-9">
                                            <label class="col-form-label" v-text="asignadoa"></label>
                                        </div>
                                    </div>
                                </div>
                                    
                                <div class="form-group col-md-6">
                                    <div class="form-group row">
                                        <strong class="col-md-4 col-form-label" for="text-input">Nº Cheque:</strong>
                                        <div class="col-md-8">
                                            <input type="text" 
                                            v-model="numcheque"
                                            class="form-control formu-entrada" 
                                            v-validate.initial="'required'"
                                            name="Nº cheque">
                                        <span class="text-error">{{ errors.first('Nº cheque')}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group row col-md-12">
                                    <strong class="col-md-2 col-form-label" for="text-input">Importe:</strong>
                                    <div class="col-md-10">
                                        <label class="col-form-label" for="text-input" v-text="monto + ' Bs.'"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button"   class="btn btn-secondary" @click="cerrarModalConciliacion()">Cerrar</button>
                            <button :disabled ="!iscompletecheque" type="submit" class="btn btn-primary" @click="registrocheque()">Registrar Cheque</button>
                        </div>
                    </div>                    
                </div>                
            </div> 
            <!-- fin modal conciliacion bancaria -->
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
    })
    Vue.component("con_descargo", require("./Con_DescargoCuenta.vue").default);
    
    import VueBarcode from 'vue-barcode';
    import Vue from 'vue';
    import VeeValidate from 'vee-validate';
    import vSelect from 'vue-select';
    import VueNumeric from 'vue-numeric'
    import * as plugin from '../../functions.js';
    import VueMask from 'v-mask'
    Vue.use(VueMask);

    Vue.component('v-select',vSelect);
    Vue.use(VueNumeric);
    export default {
        props:['idmodulo'],
        data(){
            return{
                buscar:'',
                arrayCargocuenta:[],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                tipocargo:'interno',
                offset:3,
                tituloModal:'',
                asignadoa:'',
                monto:'',
                montoresto:0,
                glosa:'',
                solicitadopor:'',
                cargo:'',
                arrayDocumento:[],
                idcuenta:[],
                tipodocumento:'',
                clearSelected:1,
                numdoc:'',
                fechaactual:'',
                fecharegistro:'',
                fechamin:'',
                solccuenta_id:'',
                observado:'',
                observacion:'',
                messelected:'',
                anioselected:'',
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
                arrayfiltros:["Filtrar...",
                                "Aprobado",
                                "Aprobado Con Saldo",
                                "Aprobado Sin Descargo",
                                "Aprobado Con Descargo",
                                "No Desembolsado",
                ],
                filtro:0,
                anio:'',
                idproveedor:'',
                cuentasconciliacion:[],
                tituloModalConciliacion:'',
                arrayConciliacion:[],
                nomcuenta:'',
                numcheque:'',
                idmovimiento:'',
                ccuentaopen:0,
                accion:'',
                idcuentahaber:[],
                numdocumento:'',
                reporte_automatico:'',
                reporte_seg_ccuentas:'',
                reporte_documento:'',
                reporte_documento_directorio:'',
                divCargoPrincipal:1,
                arrayConciliacionExterna:[],
                idcuentadesembolso:'',
                nomcuentadesembolso:'',
                codcuenta:'',
                numchequeregistrado:'',
                sidirectorio:'',
                subcuenta:''

            }
        },
        components:{
            'barcode':VueBarcode
        },
        computed:{
            iscompletecheque(){
                if(this.numcheque)
                    return true;
                else
                    return false;
            },
           
            iscompleteccuenta(){
                let me=this;
                if(me.tipodocumento && me.numdoc)
                    return true;
                else
                    return false;

            },
            
            
            isActived: function(){
                return this.pagination.current_page;
            },
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            },
        },
        methods:{
            cerrarvuedescargo(){
                this.listarCargoCuenta(1,this.buscar,this.tipocargo,this.filtro);
                $('#divdescargo').css('display','none');
                //console.log('hola');
                this.divCargoPrincipal=1;
                
                
            },
            listaCargos(){
                this.divCargoPrincipal=1;
                this.$refs.vuedescargo.cerrarvue();
                this.listarCargoCuenta(1,this.buscar,this.tipocargo,this.filtro);
            },
            cargarvue(cargocuenta=[]){
                //console.log(cargocuenta);
                this.divCargoPrincipal=0;
                var arrayValores=[];
                arrayValores['asignadoa']=cargocuenta['nombres'];
                arrayValores['saldo_descargo']=cargocuenta['saldo_descargo'];
                arrayValores['glosa']=cargocuenta['glosa'];
                arrayValores['cargo']=cargocuenta['nomrol'];
                arrayValores['idasientomaestro']=cargocuenta['idasientomaestro'];
                arrayValores['titulomodal']='Descargo de Asignacion';
                arrayValores['fechamin']=cargocuenta['fecha_apertura'];
                arrayValores['idsolccuenta']=cargocuenta['idsolccuenta'];
                arrayValores['idfilial']=cargocuenta['idfilial'];
                arrayValores['nommunicipio']=cargocuenta['nommunicipio'];
                arrayValores['sigla']=cargocuenta['sigla'];
                arrayValores['idmodulo']=this.idmodulo;
                arrayValores['sidirectorio']=cargocuenta['sidirectorio'];
                arrayValores['subcuenta']=cargocuenta['subcuenta'];
                //console.log(arrayValores);
                this.$refs.vuedescargo.cargarvue(arrayValores);
               
            },
            selectAll: function (event) {
      
                setTimeout(function () {
                    event.target.select()
                }, 0)
            } ,
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
                this.fecharegistro=this.fechaactual;
                
            },
            tiempo(){
            this.clearSelected=1;
            },
            clean(){
                this.idcuenta=[];
                this.idmovimiento=0;
                //this.idproveedorrespuesta=0;
                //console.log('clean')
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
            cuentas(cuentas){ 
                let me=this;
                me.idcuenta=[];
               // this.arrayCuenta.push({cuentita:cuentas.idcuenta})         
                console.log(cuentas);
                for (const key in cuentas) {
                    if (cuentas.hasOwnProperty(key)) {
                        const element = cuentas[key];
                        //console.log(element);
                        this.idcuenta.push(element);
                        
                    }
                }
                if(me.cuentasconciliacion.includes( this.idcuenta[0] ))
                {
                    me.abrirModalConciliacion(this.idcuenta[0]);
                }
                //console.log(this.idcuenta);
                
            },
            cambiarPagina(page,buscar,tipocargo,filtro){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarCargoCuenta(page,buscar,tipocargo,filtro);
            },
            listarCargoCuenta(page,buscar,tipocargo,filtro){
                let me=this;
                var url= '/glo_solccuenta/listarconta?page=' + page + '&buscar='+ buscar+'&tipocargo='+tipocargo+'&filtro='+filtro;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayCargocuenta = respuesta.cargocuentas.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectDocumento(){
                let me=this;
                var url= '/par_documento/selectDocumento?idmodulo='+this.idmodulo;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayDocumento = respuesta.documentos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            abrirModal(id,accion,data=[]){
                let me=this;
                switch (accion) {
                    case 'validar':
                        me.selectDocumento();
                        me.asignadoa=data['nombres'];
                        me.monto=data['saldo_descargo'];
                        me.glosa=data['glosa'];
                        me.solicitadopor=data['username'];
                        me.sidirectorio=data['sidirectorio'];
                        me.cargo=data['nomrol'];
                        me.tituloModal='Asiento Contable Cargo de Cuenta - BORRADOR';
                        me.clearSelected=0;
                        me.fechamin=data['fecha_solicitud'];
                        me.subcuenta=data['subcuenta'];
                        me.solccuenta_id=data['idsolccuenta'];
                        me.ccuentaopen=1;
                        me.idcuentadesembolso=data['idcuentadesembolso'];
                        me.nomcuentadesembolso=data['nomcuenta'];
                        me.codcuenta=data['codcuenta'];
                        me.numchequeregistrado=data['numdocumento'];
                        me.idmovimiento=data['idmovimiento'];
                        setTimeout(me.tiempo, 200); 
                        this.classModal.openModal(id);
                        break;
                }

            },
            guardarAsientoccuenta(){
                let me = this;
                axios.post('/con_asientomaestro/registrarccuenta',{
                    'fecharegistro': this.fecharegistro,
                    'subcuenta': this.subcuenta,
                    'monto':this.monto,
                    'glosa':this.glosa,
                    'idcuentahaber':this.idcuentadesembolso,
                    'documento':this.tipodocumento,
                    'numdocumento':this.numdoc,
                    'idmodulo':this.idmodulo,
                    'idsolccuenta':this.solccuenta_id,
                    'idmovimiento':this.idmovimiento,
                    'sidirectorio':this.sidirectorio

                }).then(function (response) {
                    console.log(response.data);
                    
                    if(response.data=='incorrecto')
                    {
                        swal({
                            title: "Incorrecto",
                            text: "La Solicitud se Elimino desde Origen",
                            type: "warning",
                        })
                    }
                    else
                    {
                    swal(
                            'Registrado correctamente en estado borrador',
                       )                    
                    }
                    if(me.sidirectorio)
                        var directorio=1;
                    else    
                        var directorio=2;

                    me.reporteAsientoautomatico(response.data,directorio);
                    me.listarCargoCuenta(1,'',me.tipocargo,me.filtro);
                    me.cerrarModal('ccuenta');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            cerrarModal(id){
                let me=this;
                me.classModal.closeModal(id);
                me.idcuenta=[];
                me.fecharegistro=me.fechaactual;
                me.tipodocumento='';
                me.numdoc='';
                
            },
            
            detallesObservacion(id){
                let me=this;
                var url= '/glo_solccuenta/listarobs?idsolccuenta='+id;
                //console.log(url);
                
                axios.get(url).then(function (response){
                    var respuesta= response.data;
                    //console.log(respuesta);
                    
                    me.observacion = respuesta;
                    swal({
                        title: "Solicitud Observada:",
                        text: me.observacion,
                        type: "info",
                        confirmButtonClass: "btn-info",
                    });
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },
            abrirModalConciliacion(idcuenta){
                let me=this;
                var cuenta_id=idcuenta;
                me.tituloModalConciliacion='¿Registrar # de cheque?';
                me.selectConciliacion(cuenta_id);
                //me.nomcuenta=
                /* if(){
                    me.tituloModalConciliacion='Registro de cheques';
                }
                else{

                } */

                
                me.classModal.openModal('conciliacionbancaria');
            },
            getRutasReports(){ 
                let me=this;
                var url= '/con_reportes';
                axios.get(url).then(function (response) {
                        var respuesta= response.data; ;
                    me.reporte_seg_ccuentas = respuesta.REP_SEG_CCUENTAS; 
                    me.reporte_automatico=respuesta.REP_ASIENTO_AUTOMATICO;
                    me.reporte_documento=respuesta.REP_DOC_OBLIGACION;
                    me.reporte_documento_directorio=respuesta.REP_DOC_OBLIGACION_DIRECTORIO;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            repdocobligacion(idsolccuenta,sidirectorio){
                let me=this;
                if(sidirectorio)
                var url=me.reporte_documento_directorio+idsolccuenta;
                else
                var url=me.reporte_documento+idsolccuenta;
                //console.log(url);
                
                plugin.viewPDF(url,'Documento de Obligacion Cargo de Cuenta');
            },
            reporteSeguimiento(idsolccuenta){
                let me=this;
                var url=me.reporte_seg_ccuentas + idsolccuenta; 
                //me.abrirVentanaModalURL(url,"reporte_asiento_automatico",800,700);	
                plugin.viewPDF(url,'Reporte Seguimiento Cargo de Cuenta');

            },
            reporteAsientoautomatico(idasientomaestro,directorio){
                let me=this;
                var url=me.reporte_automatico + idasientomaestro+'&tiposubcuenta='+directorio; 
                console.log(url);

                plugin.viewPDF(url,'Asiento Contable');

            },
            getCConciliacion(){
                let me=this;
                var conciliacion;
                var url= '/con_config/cuentasconciliacion';
                //console.log(url);
                axios.get(url).then(function (response) {
                        var respuesta= response.data;
                        console.log(respuesta);
                        
                        respuesta.forEach(element => {
                             me.cuentasconciliacion.push(element.valor);
                            //console.log(element.valor);
                        });
                        //console.log(me.cuentasconciliacion);
                        //console.info( me.cuentasconciliacion.includes( 12 ) ); // true
                })
                .catch(function (error) {
                    console.log(error);
                });
                //return conciliacion
            },
            cerrarModalConciliacion(){
                let me=this;
                me.numcheque='';
                me.classModal.closeModal('conciliacionbancaria');
                if(me.ccuentaopen==1)
                    me.classModal.openModal('ccuenta');
                
                me.ccuentaopen=0;

            },
            selectConciliacion(idcuenta){
                let me=this;
                var url= '/con_conciliacion/selectconciliacion?idcuenta='+idcuenta;
                console.log(url);
                
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    //console.log(response);
                    me.arrayConciliacion = respuesta.conciliacion;
                    me.arrayConciliacionExterna=respuesta.conciliacionexterna;
                    //console.log(me.arrayConciliacion);
                    me.arrayConciliacion = me.arrayConciliacion.concat(me.arrayConciliacionExterna);
                    //console.log(me.arrayConciliacion);
                    me.arrayConciliacion.sort(function (a, b) {
                        if (a.numdocumento > b.numdocumento) {
                            return -1;
                        }
                        if (a.numdocumento < b.numdocumento) {
                            return 1;
                        }
                        // a must be equal to b
                        return 0;
                    });
                    //console.log(me.arrayConciliacion);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            registrocheque(){
                let me=this;
                me.idmovimiento=0;
                axios.post('/con_conciliacion/registrar',{
                    'idcuenta': me.idcuenta[0],
                    'numdocumento': me.numcheque,
                    'importe':me.monto,
                    'tipocargo':'h'
                }).then(function (response) {
                    console.log(response);
                    me.idmovimiento=response.data;
                            'Registrado correctamente',
                    me.cerrarModalConciliacion('conciliacion');
                    me.classModal.openModal('ccuenta');
                }).catch(function (error) {
                    console.log(error);
                });
            }
        },
        mounted(){
            this.listarCargoCuenta(1,this.buscar,this.tipocargo,this.filtro);
            this.getRutasReports();
            this.getCConciliacion();
            //this.verificarcuenta(12);
            this.classModal=new _pl.Modals();
            this.classModal.addModal('ccuenta');
            this.classModal.addModal('conciliacionbancaria');
            
            //this.classModal.addModal('librocompras');
            this.fechahoy();
        }
    }
</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .divnoerror{
        background-color: #ccc;
        text-align: right;
        border-radius: 3px;
        padding-top: 3px;
                                                      
    }
    .divsierror{
        background-color: rgb(255, 0, 0);
        text-align: right;
        border-radius: 3px;
        padding-top: 3px;
                                                      
    }
    .error{
        color:white !important;
    }
    .text-error{
        color: red !important;
        font-style: italic;
    }

</style>
