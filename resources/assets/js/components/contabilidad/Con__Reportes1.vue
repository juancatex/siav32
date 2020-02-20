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
                        <i class="fa fa-align-justify"></i> Reporte Resumen
                        <ul>
                            
                            <!-- <li><a class="nav-link" v-bind:href="''+resumen+''" target="_blank"><i class="icon-link"></i> resumen Personal</a></li>                             -->
                            <li> --- </li>
                            <button class="col-md-3 btn btn-block btn-primary " name="resumen_val" @click="verResumen()">Resumen</button>                            
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
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha:</label>
                                                                        
                                    <div class="col-md-3">
                                        <input type="date" v-validate.initial="'required'" name="fecha" v-model="fecha">
                                    <span class="text-error">{{ errors.first('fecha')}}</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button :disabled = "errors.any()" type="submit" class="btn btn-primary" @click="mostrarReporte(Modalview,fecha)">Reporte</button>                            
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
                modal : 0,                
                tituloModalK : 'Resumen',                
                resumen : '',
                fecha : '',
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
                    me.resumen = respuesta.REP_RESUMEN;
                })
                .catch(function (error) {
                    console.log(error); 
                });
            },

            setear (){
                this.fuerza1='';
                this.date1='';
                this.date2='';
                this.fecha='',
                this.op1='';this.op2='';this.op3='';this.op4='';
            },
            verResumen(){ 
                this.Modalview='K';  
                this.setear();
                this.modal=1;                 
            },
                        
            mostrarReporte(modal,fecha){
                if (modal === 'K') {
                    var url=this.resumen + '&fecha='+fecha; 
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