<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Reportes Hospedaje Filiales         
                    <ul> FILIAL CBBA                                                
                        <li> --- </li>
                        <button class="col-md-3 btn btn-block btn-primary " name="resumen_val" @click="verRegistro('CBBA')">H. Transitorio CBBA.</button>
                        <li> --- </li>
                        <button class="col-md-3 btn btn-block btn-primary " name="resumen_val" @click="mostrarPermanente('CBBA')">H. Permanente CBBA.</button>
                        <li> --- </li>
                        <button class="col-md-3 btn btn-block btn-primary " name="resumen_val" @click="mostrarPermanenteSocio('CBBA')">H. Permanente CBBA - Por Socio.</button>
                    </ul>
                    <ul> FILIAL LPZ                                    
                        <li> --- </li>
                        <button class="col-md-3 btn btn-block btn-primary " name="resumen_val" @click="verRegistro('LPZ')">H. Transitorio LPZ</button>                    
                    </ul>
                </div>                    
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio de modal-->            

        <div v-if="Modalview === 'K'">
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModalK"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <!-- <ul>
                        <li v-for="error in errors.all()" v-bind:key="error"> {{ error }}</li>
                    </ul> -->
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">                                                                                            
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Tipo:</label>
                                <div class="col-md-3">
                                    <input type="radio" value="in"  v-model="tipo">Entradas
                                    <input type="radio" value="out" v-model="tipo">Salidas
                            </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Fecha Inicio:</label>                                                                    
                                <div class="col-md-3">
                                    <input type="date" v-validate.initial="'required'" name="fechaIn" v-model="fechaIn">
                                <span class="text-error">{{ errors.first('Fecha valida')}}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Fecha Final:</label>
                                <div class="col-md-3">
                                    <input type="date" v-validate.initial="'required'" name="fechaOut" v-model="fechaOut">
                                <span class="text-error">{{ errors.first('fecha valida')}}</span>
                                </div>
                            </div>
                            <div v-if="tipo==='in'">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Estado:</label>
                                    <div class="col-md-3">
                                        <select class="form-control" name='estado' v-model='estado'>
                                            <option value="">Todos</option>
                                            <option value="1">Ocupados</option>
                                            <option value="0">Desocupados</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button :disabled = "errors.any()" type="submit" class="btn btn-primary" @click="mostrarReporte(Modalview,tipo,fechaIn,fechaOut,estado,filial,0,0)">Reporte</button>                            
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->            
        </div>            

        <div v-if="Modalview === 'S'">  
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModalS"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>                    

                    <div class="form-group row">
                        <label class="col-md-3 form-control-label">Buscar Socio:</label>
                    <div class="col-sm-6">
                        <Ajaxselect v-if="clearSelected"
                        ruta="/socio/listaSocios?cadena=" 
                        @found="listaSocios" 
                        resp_ruta="socios" 
                        labels="nomcompleto" 
                        placeholder="Ingrese Texto..." 
                        idtabla="idsocio" 
                        :id="idsocio" 
                        :clearable="true">
                    </Ajaxselect>
                    </div>                                                                                       
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button :disabled = "errors.any()" type="submit" class="btn btn-primary" @click="mostrarReporteSocio(idsocio)">Reporte</button>                            
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->            
        </div>


        <div v-if="Modalview === 'P'">
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModalK"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">                                                                                            
                            <div class="form-group row">
                                Elija el periodo: 
                                
                                    <div class="tfila col-sm-6">
                                        <div class="tcelda ">
                                            <select class="form-control" v-model="per"
                                                name="per" :class="{'invalido':errors.has('per')}" v-validate="'required'">
                                                <option v-for="mes in arrayMeses" :key="mes" :value="mes" v-text="mes"></option>
                                            </select>
                                            <p class="txtvalidador" v-if="errors.has('per')">Seleccione un Mes</p>
                                        </div>
                                        <div class="tcelda" style="width:20px"></div>
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
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button :disabled = "errors.any()" type="submit" class="btn btn-primary" @click="mostrarReporte(Modalview,tipo,fechaIn,fechaOut,0,'CBBA',ges,per)">Reporte</button>                            
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->            
        </div>  

    </main>
</template>

<script>

    import Vue from 'vue';
    import VeeValidate from 'vee-validate'; 
    import * as repo from '../../functions.js';
    
    const VueValidationEs = require('vee-validate/dist/locale/es');
        Vue.use(VeeValidate, 
        {
            locale: 'es',
            dictionary: 
            {
                es: VueValidationEs
            }
        });

    Vue.use(VeeValidate);

    export default {
        props:['idmodulo','idventanamodulo'],        
        
        data (){
            return {                                
                Modalview: '',
                modal : 0, modal : 1,
                tituloModalK : 'Reportes HTR',                
                tituloModalS : 'Reportes HTR por SOCIO',                
                transitorio : '',
                transitorio_todo : '',
                transitorio_salida : '',
                permanente: '',
                fechaOut : '',
                fechaIn : '',
                tipoAccion : 0,
                errorEstado : 0,
                errorMostrarMsjEstado : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nomestado',
                buscar : '',
                arrayPermisos : {resumen:0},
                arrayPermisosIn:[],
                filial:'',
                arrayMeses:['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
                per:'', 
                ges:'',
                estado:'', 
                tipo:'in',
                idsocio:'',
                clearSelected:1,
                idempleado1 :'',
            }
        },

        formOptions:{
            validateAfterLoad: true,
            validateAfterChanged: true
        },

        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
        },
        methods : {

            getRutasReports (){ 
                let me=this;
                var url= '/ser_reportes';
                axios.get(url).then(function (response) {
                    var respuesta= response.data; ;                    
                    me.transitorio = respuesta.REP_REGISTRO;
                    me.transitorio_todo = respuesta.REP_REGISTRO_TODO;
                    me.transitorio_salida = respuesta.REP_REGISTRO_SALIDA;
                    me.permanente = respuesta.REP_PERMANENTE;
                    me.permanenteSocio = respuesta.REP_PERMANENTE_SOCIO;
                })
                .catch(function (error) {
                    console.log(error); 
                });
            },

            cleanempleados(){
                this.idempleado='';            
            },

            listaSocios(ads) { console.log(ads);           
                this.idsocio= ads.idsocio;
                //console.log(ads.idempleado);
            },

            setear (){
                this.fechaOut='',
                this.fechaIn='',
                this.op1='';this.op2='';this.op3='';this.op4='';
            },

            mostrarPermanente(filial){               
                this.filial=filial;
                this.Modalview='P';  
                this.setear();
                this.modal=1;                 
            },

            mostrarPermanenteSocio(filial){
                this.filial=filial;
                this.Modalview='S';  
                this.setear();
                this.clearSelected=1;
                this.modal=1;                 
            },

            verRegistro(filial){ 
                this.filial=filial;
                this.Modalview='K';  
                this.setear();
                this.modal=1;                 
            },
                        
            mostrarReporteSocio(idsocio){
                var url=this.permanenteSocio +'&idsocio='+idsocio;
                this.reporte_resumen(url,'Resumen por Socio');
            },
            
            mostrarReporte(modal, tipo, fechaIn, fechaOut, estado, filial, ges, per){
                if (modal === 'K') {
                    if (filial=='LPZ') {
                        if (tipo=='in')
                            if (estado=="") //todos
                                var url=this.transitorio_todo +'&fechaIn='+fechaIn+'&fechaOut='+fechaOut+'&idfilial=1&idestablecimiento=2';
                            else    
                                var url=this.transitorio +'&fechaIn='+fechaIn+'&fechaOut='+fechaOut+'&idfilial=1&idestablecimiento=2&vigente='+estado;
                        if (tipo=='out')
                            var url=this.transitorio_salida +'&fechaIn='+fechaIn+'&fechaOut='+fechaOut+'&idfilial=1&idestablecimiento=2';
                    }
                        
                    if (filial=='CBBA') {
                        if (tipo=='in')
                            if (estado=="") //todos
                                var url=this.transitorio_todo +'&fechaIn='+fechaIn+'&fechaOut='+fechaOut+'&idfilial=2&idestablecimiento=11';
                            else
                                var url=this.transitorio +'&fechaIn='+fechaIn+'&fechaOut='+fechaOut+'&idfilial=2&idestablecimiento=11&vigente='+estado;
                        if (tipo=='out')
                            var url=this.transitorio_salida +'&fechaIn='+fechaIn+'&fechaOut='+fechaOut+'&idfilial=2&idestablecimiento=11';
                    }                                            
                    this.reporte_resumen(url,'Resumen');

                }
                if (modal === 'P') { // reporte permanente
                    if (filial=='CBBA') {
                        var periodo = per+'/'+ges;
                        var url=this.permanente + '&periodo='+periodo;
                    }                        
                    this.reporte_resumen(url,'Resumen');
                }                
            },

            reporte_resumen(url,title) {
                console.log(url);
                _pl._vm2154_12186_135(url,title);
            },

            cerrarModal() {
                this.Modalview='S';  
                this.modal=0;
                this.clearSelected=0;
            },            
 
            getPermisos() { 
                //permisoId poner axios para obtener los permisos                
                var url= '/adm_role/selectPermisos?idmodulo=' + this.idmodulo + '&idventanamodulo=' + this.idventanamodulo;
                let me = this; 
                axios.get(url).then(function (response) {
                    me.arrayPermisosIn=[];
                    if(response.data.datapermiso.length>0){
                        var respuesta=response.data.datapermiso[0].permisos; 
                        me.arrayPermisosIn = JSON.parse((respuesta));
                    } 
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            check(n){
                return _pl.validatePermission(this.arrayPermisosIn,n);
            }
            
        },
        mounted() {
            //this.getPermisos(); 
            this.getRutasReports();
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
    .text-error{
        color: red !important;
        font-style: italic;
    }
</style>