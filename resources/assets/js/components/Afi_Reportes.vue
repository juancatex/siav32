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
                        <i class="fa fa-align-justify"></i> Reportes Modulo Afiliaciones.
                        <ul>
                            
                            <!-- <li><a class="nav-link" v-bind:href="''+kardex+''" target="_blank"><i class="icon-link"></i> Kardex Personal</a></li>                             -->
                            <li> --- </li>
                            <button v-if="check('por_kardex')" class="col-md-3 btn btn-block btn-primary " name="por_kardex" @click="verReporteKardex()">Kardex</button>
                            <!-- <li><a class="nav-link" v-bind:href="''+fuerza+''" target="_blank"><i class="icon-link"></i> Reporte por Fuerza</a></li> -->
                            <li> --- </li>
                            <button v-if="check('por_fuerza')" class="col-md-3 btn btn-block btn-primary active" name="por_fuerza" @click="verReporteFuerza()">Por Fuerza</button>
                            <!-- <li><a class="nav-link" v-bind:href="''+egreso+''" target="_blank"><i class="icon-link"></i> Reporte por Gestion de Egreso</a></li>                             -->
                            <li> --- </li>
                            <button v-if="check('por_egreso')" class="col-md-3 btn btn-block btn-primary active" name="por_egreso" @click="verReporteEgreso()">Por Fecha de Egreso</button>
                            <!-- <li><a class="nav-link" v-bind:href="''+inscripcion+''" target="_blank"><i class="icon-link"></i> Reporte por Gestion de Inscripcion</a></li> -->
                            <li> --- </li>
                            <button v-if="check('por_inscripcion')" class="col-md-3 btn btn-block btn-primary active" aria-pressed="true" name="por_inscripcion" @click="verReporteInscripcion()">Por Fecha de Inscripcion</button>
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
                                    <label class="col-md-3 form-control-label" for="text-input">Numero de Papeleta:</label>
                                                                        
                                    <div class="col-md-3">
                                        <input type="text" v-validate.initial="'required'" name="numpapeleta" v-model="numpapeleta">
                                    <span class="text-error">{{ errors.first('numpapeleta')}}</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button :disabled = "errors.any()" type="submit" class="btn btn-primary" @click="mostrarReporte(Modalview,0,0,0,numpapeleta,0,0,0,0)">Reporte</button>                            
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->            
            </div>

            <div v-if="Modalview === 'F'">
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModalF"></h4>
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
                                    <label class="col-md-3 form-control-label" for="text-input">Elija la  Fuerza: </label>
                                    <div class="col-md-3">
                                        <select name="fuerza" v-validate.initial="'required'" v-model="fuerza1">
                                            <option selected="selected" value="" disabled >Fuerza...</option>
                                            <option value="4">Aerea</option>
                                            <option value="3">Ejercito</option>
                                            <option value="5">Armada</option>
                                            <option value="6">Todos</option>
                                        </select>
                                    <span class="text-error">{{ errors.first('fuerza')}}</span>
                                    </div>
                                </div>
                                <label class="form-control-label" for="text-input">Elija una opcion para el reporte: </label>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Opcion 1:</label>
                                    <div class="col-md-3">
                                        <select name="op1" v-model="op1">
                                            <option selected="selected" value="" disabled >Ninguno...</option>
                                            <option v-for="opc in opciones" :key="opc.va" :value="opc.va" v-text="opc.valor"></option>                                            
                                        </select>      
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Opcion 2:</label>
                                    <div class="col-md-3">
                                        <select name="op2" v-model="op2">
                                            <option selected="selected" value="" disabled >Ninguno...</option>
                                            <option v-for="opc in opciones" :key="opc.valor" :value="opc.va" v-text="opc.valor"></option>                                            
                                        </select>                                    
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Opcion 3:</label>
                                    <div class="col-md-3">
                                        <select name="op3" v-model="op3">
                                            <option selected="selected" value="" disabled >Ninguno...</option>
                                            <option v-for="opc in opciones" :key="opc.valor" :value="opc.va" v-text="opc.valor"></option>                                            
                                        </select>                                    
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Opcion 4:</label>
                                    <div class="col-md-3">
                                        <select name="op4" v-model="op4">
                                            <option selected="selected" value="" disabled >Ninguno...</option>
                                            <option v-for="opc in opciones" :key="opc.valor" :value="opc.va" v-text="opc.valor"></option>                                            
                                        </select>                                    
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button :disabled = "errors.any()" type="submit" class="btn btn-primary" @click="mostrarReporte(Modalview,fuerza1,0,0,0,op1,op2,op3,op4)">Reporte</button>                            
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->            
            </div>


            <div v-if="Modalview === 'E'">            
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModalE"></h4>
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
                                    <!-- <label class="col-md-3 form-control-label" for="text-input">Elija la Fuerza:</label> -->
                                    <div class="col-md-3">
                                        Elija la Fuerza: <select name="fuerza" v-validate.initial="'required'" v-model="fuerza1">
                                            <option selected="selected" value="" disabled >Fuerza...</option>
                                            <option value="4">Aerea</option>
                                            <option value="3">Ejercito</option>
                                            <option value="5">Armada</option>
                                            <option value="6">Todos</option>
                                        </select>
                                    <span class="text-error">{{ errors.first('fuerza')}}</span>
                                    </div>
                                    <div class="col-md-3">
                                        Fecha Desde: <input type="date" v-validate.initial="'required'" name="date1" v-model="date1">
                                    <span class="text-error">{{ errors.first('date1')}}</span>
                                    </div>
                                    <div class="col-md-3">
                                        Fecha Hasta: <input type="date" v-validate.initial="'required'" name="date2" v-model="date2">
                                    <span class="text-error">{{ errors.first('date2')}}</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button :disabled = "errors.any()" type="submit" class="btn btn-primary" @click="mostrarReporte(Modalview,fuerza1,date1,date2,0,0,0,0,0)">Reporte</button>                            
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->            
            </div>

            <div v-if="Modalview === 'I'">            
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModalI"></h4>
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
                                    <!-- <label class="col-md-3 form-control-label" for="text-input">Elija la Fuerza:</label> -->
                                    <div class="col-md-3">
                                        Elija la Fuerza: <select name="fuerza" v-validate.initial="'required'" v-model="fuerza1">
                                            <option selected="selected" value="" disabled >Fuerza...</option>
                                            <option value="4">Aerea</option>
                                            <option value="3">Ejercito</option>
                                            <option value="5">Armada</option>
                                            <option value="6">Todos</option>
                                        </select>
                                    <span class="text-error">{{ errors.first('fuerza')}}</span>
                                    </div>
                                    <div class="col-md-3">
                                        Fecha Desde: <input type="date" v-validate.initial="'required'" name="date1" v-model="date1">
                                    <span class="text-error">{{ errors.first('date1')}}</span>
                                    </div>
                                    <div class="col-md-3">
                                        Fecha Hasta: <input type="date" v-validate.initial="'required'" name="date2" v-model="date2">
                                    <span class="text-error">{{ errors.first('date2')}}</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button :disabled = "errors.any()" type="submit" class="btn btn-primary" @click="mostrarReporte(Modalview,fuerza1,date1,date2,0,0,0,0,0)">Reporte</button>                                                    </div>
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
    import * as repo from '../functions.js';
    
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
                modal : 0,                
                tituloModalK : 'Por Kardex',                
                tituloModalF : 'Por Fuerza',
                tituloModalE : 'Por Fecha de Egreso',
                tituloModalI : 'Por Fecha de Inscripcion',
                numpapeleta : '',
                fuerza1: '', date1:'', date2:'', op1:'', op2:'', op3:'', op4:'',
                opciones: [                    
                    {valor : 'Ap. Esposo',va:1},{valor : 'Estado Civil',va:2},{valor : 'Sexo',va:3},{valor : 'Fec. Nac.',va:4},{valor : 'Domicilio',va:5},
                    {valor : 'Tel. Fijo',va:6},{valor : 'Tel. Celular',va:7},{valor : 'Email',va:8},{valor : 'Escalafon',va:9},{valor : 'Destino',va:10},
                    {valor : 'Especialidad',va:11},{valor : 'Carnet Socio',va:12},{valor : 'Carnet Militar',va:13}
                ],                                
                kardex: '',
                fuerza:'',
                egreso: '',
                inscripcion: '',
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
                arrayPermisos : {por_kardex:0,por_egreso:0,por_inscripcion:0,por_fuerza:0},
                arrayPermisosIn:[]
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

            }
        },
        methods : {
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarEstado(page,buscar,criterio);
            },
            getRutasReports (){ 
                let me=this;
                var url= '/afi_reportes';
                axios.get(url).then(function (response) {
                     var respuesta= response.data; ;
                    me.egreso = respuesta.REP_EGRESO; 
                    me.inscripcion = respuesta.REP_INSCRIPCION;
                    me.fuerza = respuesta.REP_FUERZA;
                    me.kardex = respuesta.REP_KARDEX;
                })
                .catch(function (error) {
                    console.log(error); 
                });
            },

            setear (){
                this.fuerza1='';
                this.date1='';
                this.date2='';
                this.numpapeleta='',
                this.op1='';this.op2='';this.op3='';this.op4='';
            },
            verReporteKardex(){ 
                this.Modalview='K';  
                this.setear();
                this.modal=1;                 
            },
            verReporteFuerza(){ 
                this.Modalview='F';
                this.setear(); 
                this.modal=1;                 
            },
            verReporteEgreso(){ 
                this.Modalview='E';  
                this.setear();
                this.modal=1;                 
            },
            verReporteInscripcion(){ 
                this.Modalview='I';  
                this.setear();
                this.modal=1;                 
            },
            
            mostrarReporte(modal,fuerza,date1,date2,numpapeleta,op1,op2,op3,op4){
                if (modal === 'K') {
                    var url=this.kardex + '&numpape='+numpapeleta; 
                    this.reporte_fuerza(url,'Por Kardex');
                }
                if (modal === 'F') {
                    if (op1==="") op1=0; 
                    if (op2==="") op2=0;
                    if (op3==="") op3=0;
                    if (op4==="") op4=0;
                    var url=this.fuerza + '&Fuerza='+fuerza+'&Campos='+op1+'&Campos1='+op2+'&Campos2='+op3+'&Campos3='+op4; 
                    this.reporte_fuerza(url,'Por Fuerza');
                }
                if (modal === 'E') {
                    var url=this.egreso + '&Fuerza='+fuerza+'&fechaInicio='+date1+'&fechaFinal='+date2; 
                    this.reporte_fuerza(url,'Por Egreso');
                }
                if (modal === 'I') {
                    var url=this.inscripcion + '&Fuerza='+fuerza+'&fechaInicio='+date1+'&fechaFinal='+date2; //alert(url);
                    this.reporte_fuerza(url,'Por Inscripcion');
                }                                
            },

            reporte_fuerza(url,title) {
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
            this.getPermisos(); 
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