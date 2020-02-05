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
                        <i class="fa fa-align-justify"></i> Reportes Modulo Acreedores
                        <ul>                            
                            <li> --- </li>
                            <button class="col-md-3 btn btn-block btn-primary " name="general" @click="verReporteGeneral()">Rep. General</button>                           
                        </ul>                                            
                    </div>                    
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio de modal-->            

            <div v-if="Modalview === 'A'">
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModalA"></h4>
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
                                        <input type="date" v-validate.initial="'required'" name="fechaproceso" v-model="fechaproceso">
                                    <span class="text-error">{{ errors.first('fechaproceso')}}</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button :disabled = "errors.any()" type="submit" class="btn btn-primary" @click="mostrarReporte(Modalview,fechaproceso)">Reporte</button>                            
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
        data (){
            return {                                
                Modalview: '',
                modal : 0,                
                tituloModalA : 'Reporte General',                                
                fechaproceso : '',                
                general : '',
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
                criterio : '',
                buscar : '',
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
                var url= '/liq_reportes';
                axios.get(url).then(function (response) {
                     var respuesta= response.data; ;
                    me.general = respuesta.REP_GENERAL;                     
                })
                .catch(function (error) {
                    console.log(error); 
                });
            },

            setear (){                
                this.fechaproceso=''                
            },

            verReporteGeneral(){ 
                this.Modalview='A';  
                this.setear();
                this.modal=1;                 
            },
            
            
            mostrarReporte(modal,fechaproceso){
                if (modal === 'A') {
                    var url=this.general + '&fechaproceso='+fechaproceso; 
                    this.reporte_acreedor(url,'Rep., General');
                }                                                
            },

            reporte_acreedor(url,title) {
                console.log(url);
                repo.viewPDF(url,title);
            },

            cerrarModal() {
                this.Modalview='A';  
                this.modal=0;
            }
            
        },
        mounted() {
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