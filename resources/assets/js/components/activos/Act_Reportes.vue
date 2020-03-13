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
                        <i class="fa fa-align-justify"></i> Reportes Activos Fijos.
                        <br/><br/>
                        <ul>
                            <li>
                                <button class="col-md-3 btn btn-block btn-primary" @click="verPorsocio()">Activos asignados por Socio</button>                            
                            </li>
                            <li>
                                <button class="col-md-3 btn btn-block btn-primary" @click="devPorsocio()">Activos Devueltos por Socio</button>                            
                            </li>                                                                    
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
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                                                                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Empleado:</label>
                                                                        
                                    <div class="col-md-6">
                                        <Ajaxselect 
                                            ruta="/rrh_empleado/listaEmpleados?buscado=" @found="empleados"
                                            resp_ruta="empleados"
                                            labels="nombre,apaterno,amaterno"
                                            placeholder="buscar por Nombre o Apellidos" 
                                            :clearable="true"                                                                        
                                            idtabla="idempleado">
                                        </Ajaxselect> 
                                    <span class="text-error">{{ errors.first('empleados')}}</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button :disabled = "errors.any()" type="submit" class="btn btn-primary" @click="repPorSocio(idempleado,modal)">Reporte</button>                            
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->            
            </div>

            <div v-if="Modalview === 'D'">
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModalD"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                                                                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Funcionario:</label>
                                                                        
                                    <div class="col-md-6">
                                        <Ajaxselect v-if="clearSelected"
                                            ruta="/rrh_empleado/listaEmpleados?buscado=" @found="empleados" 
                                            resp_ruta="empleados"
                                            labels="nombre,apaterno,amaterno"
                                            placeholder="buscar por Nombre o Apellidos" 
                                            :clearable="true"                                                                        
                                            idtabla="idempleado">
                                        </Ajaxselect> 
                                    <span class="text-error">{{ errors.first('empleados')}}</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button :disabled = "errors.any()" type="submit" class="btn btn-primary" @click="devPorSocio(idempleado,modal)">Reporte</button>                            
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
    import * as reporte from '../../functions.js';
        
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
                tituloModalK : 'Asignacion Por socio',                
                tituloModalD : 'Devolucion Por socio',
                idempleado : '',
                //empleados:'',
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
                arrayPermisosIn:[],
                clearSelected : 1,
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

            tiempo(){
                this.clearSelected=1;
            },

            empleados(empleados) { 
                this.idempleado=empleados.idempleado; 
            },

            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarEstado(page,buscar,criterio);
            },

            // getRutasReports (){ 
            //     let me=this;
            //     var url= '/afi_reportes';
            //     axios.get(url).then(function (response) {
            //          var respuesta= response.data; ;
            //         me.egreso = respuesta.REP_EGRESO; 
            //         me.inscripcion = respuesta.REP_INSCRIPCION;
            //         me.fuerza = respuesta.REP_FUERZA;
            //         me.kardex = respuesta.REP_KARDEX;
            //     })
            //     .catch(function (error) {
            //         console.log(error); 
            //     });
            // },

            setear(){                
                this.idempleado='';
            },
            
            verPorsocio(){ 
                this.Modalview='K';  
                this.setear();
                this.modal=1;                 
                this.clearSelected=0;
                setTimeout(this.tiempo, 200); 
            },

            devPorsocio(){ 
                this.Modalview='D';  
                this.setear();
                this.modal=1;                 
                this.clearSelected=0;
                setTimeout(this.tiempo, 200); 
            },
            
            repPorSocio(idempleado,modal){ 
                    var url=[];
                    url.push('http://localhost:8080');
                    url.push('/birt-viewer/frameset?__report=reportes/activos');
                    url.push('/act_asig_indi.rptdesign'); 
                    url.push('&__format=pdf'); 
                    url.push('&idresponsable='+idempleado); 
                    reporte.viewPDF(url.join(''),'Rep. Por Socio');
            },

            devPorSocio(idempleado,modal){ 
                    var url=[];
                    url.push('http://localhost:8080');
                    url.push('/birt-viewer/frameset?__report=reportes/activos');
                    url.push('/act_dev_grupo.rptdesign'); 
                    url.push('&__format=pdf'); 
                    url.push('&idresponsable='+idempleado); 
                    reporte.viewPDF(url.join(''),'Rep. Por Socio');
            },

            cerrarModal() {
                this.Modalview='K';  
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
            //this.getRutasReports();
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