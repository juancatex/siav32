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
                    <ul>                                                
                        <li> --- </li>
                        <button class="col-md-3 btn btn-block btn-primary " name="resumen_val" @click="verRegistro('CBBA')">Hospedaje Transitorio CBBA</button>
                        <li> --- </li>
                        <button class="col-md-3 btn btn-block btn-primary " name="resumen_val" @click="mostrarPermanente('CBBA')">Hospedaje Permanente CBBA</button>                            
                    </ul>
                    <ul>                                                
                        <li> --- </li>
                        <button class="col-md-3 btn btn-block btn-primary " name="resumen_val" @click="verRegistro('LPZ')">Hospedaje Transitorio LPZ</button>                    
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
                                <label class="col-md-3 form-control-label" for="text-input">Fecha Inicio:</label>                                                                    
                                <div class="col-md-3">
                                    <input type="date" v-validate.initial="'required'" name="fechaIn" v-model="fechaIn">
                                <span class="text-error">{{ errors.first('fechaIn')}}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Fecha Final:</label>
                                <div class="col-md-3">
                                    <input type="date" v-validate.initial="'required'" name="fechaOut" v-model="fechaOut">
                                <span class="text-error">{{ errors.first('fechaOut')}}</span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button :disabled = "errors.any()" type="submit" class="btn btn-primary" @click="mostrarReporte(Modalview,fechaIn,fechaOut,filial)">Reporte</button>                            
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
                                Elija el periodo: <span class="txtasterisco"></span>
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
                            </div>                            
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button :disabled = "errors.any()" type="submit" class="btn btn-primary" @click="mostrarReporte(Modalview,fechaIn,fechaOut,'CBBA',ges,per)">Reporte</button>                            
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
                tituloModalK : 'Resumen',                
                resumen : '',
                resumen1: '',
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
                per:'', ges:'',
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
                    me.resumen = respuesta.REP_REGISTRO;
                    me.resumen1 = respuesta.REP_PERMANENTE;
                })
                .catch(function (error) {
                    console.log(error); 
                });
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

            verRegistro(filial){ 
                this.filial=filial;
                this.Modalview='K';  
                this.setear();
                this.modal=1;                 
            },
                        
            mostrarReporte(modal,fechaIn, fechaOut, filial, ges, per){
                if (modal === 'K') {
                    if (filial=='LPZ')
                        var url=this.resumen + '&fechaIn='+fechaIn+ '&fechaOut='+fechaOut+ '&idfilial=1&idestablecimiento=2';
                    if (filial=='CBBA')
                        var url=this.resumen + '&fechaIn='+fechaIn+ '&fechaOut='+fechaOut+ '&idfilial=2&idestablecimiento=11';
                    this.reporte_resumen(url,'Resumen');
                }
                if (modal === 'P') {                    
                    if (filial=='CBBA') {
                        var periodo = per+'/'+ges;
                        var url=this.resumen1 + '&periodo='+periodo;
                    }                        
                    this.reporte_resumen(url,'Resumen');
                }                
            },

            reporte_resumen(url,title) {
                console.log(url);
                repo.viewPDF(url,title);
            },

            cerrarModal() {
                this.Modalview='G';  
                this.modal=0;
            },            
 
            getPermisos() { 
                //permisoId poner axios para obtener los permisos                
                var url= '/adm_role/selectPermisos?idmodulo=' + this.idmodulo + '&idventanamodulo=' + this.idventanamodulo;
                let me = this; 
                axios.get(url).then(function (response) {
                    var respuesta = response.data.datapermiso[0].permisos;  console.log(response.data);
                    me.arrayPermisosIn = JSON.parse((respuesta)); console.log(me.arrayPermisosIn);
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