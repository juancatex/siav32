<template>
    <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
    </ol>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Reportes Carga Ascii
            </div>
            
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">
                        <label class="col-md-3 form-control-label" >Mes:</label>
                        <select v-model="messelected"
                        class="form-control ">
                                
                                <option v-for="mesarray in arraymes" v-bind:key="mesarray.value" :value="mesarray.value" v-text="mesarray.text"></option>
                            </select>   
                        
                            <!--<span>seleccionado: {{ messelected }}</span>  -->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <label class="col-md-3 form-control-label" >Año:</label>
                            <input  type="number" 
                                class="form-control" 
                                v-model="anio">   
                        </div>
                    </div>
                </div>
                <ul>
                    <div class="form-group">
                        <button type="submit" @click="reportes(4,messelected,anio)" class="btn btn-primary">Detalle Carga Ascii</button>&nbsp;&nbsp;
                        <button type="submit" @click="reportes(1,messelected,anio)" class="btn btn-primary">Resumen Carga Ascii</button>&nbsp;&nbsp;
                        <button type="button" @click="reportes(2,messelected,anio)" class="btn btn-success">Reporte Nuevos Carga Ascii</button>&nbsp;&nbsp;
                        <button type="button" @click="reportes(3,messelected,anio)" class="btn btn-danger">Reporte Observados en Carga Ascii</button>
                    </div>
                </ul> 
            </div>
        </div>   
                <!-- Fin ejemplo de tabla Listado -->
        <div class="row">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        <strong>Reportes Generales</strong> Aportes
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <h6 class="col-md-12 text-info">Aportes No Remitidos</h6>
                            <div class="col-md-8">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <span class="col-md-3"><strong>Tipo Aporte:</strong></span>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" 
                                            v-model="idtipoaporte">
                                            <option value="0">Todos</option>
                                            <option v-for="tipoaporte in arrayTipoaporte" :key="tipoaporte.idtipoaporte" :value="tipoaporte.idtipoaporte" v-text="tipoaporte.descripcion"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <span class="col-md-3"><strong>Tipo Devolucion:</strong></span>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" >
                                            <option value="0">Seleccionar</option>
                                            <option value="3">300 aportes</option>
                                            <option value="4">120 aportes</option>
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary" type="submit">Generar Reporte</button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <h6 class="col-md-12 text-info">Estado General de Aportes</h6>
                            <div class="col-md-8">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <span class="col-md-3"><strong>Tipo Aporte:</strong></span>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" 
                                            v-model="idtipoaporte">
                                            <option value="0">Todos</option>
                                            <option v-for="tipoaporte in arrayTipoaporte" :key="tipoaporte.idtipoaporte" :value="tipoaporte.idtipoaporte" v-text="tipoaporte.descripcion"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <span class="col-md-3"><strong>Tipo Devolucion:</strong></span>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" >
                                            <option value="0">Seleccionar</option>
                                            <option value="3">300 aportes</option>
                                            <option value="4">120 aportes</option>
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary" type="submit">Generar Reporte</button>
                            </div>
                        </div>
                        <hr>
                       <!--  <div class="form-group row">
                            <label class="col-md-12">Transacciones</label>
                            <div class="col-md-8">
                                

                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary" type="submit">Generar Reporte</button>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label class="col-md-12">Resumen de Transacciones</label>
                            <div class="col-md-6">
                                

                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-primary" type="submit">Generar Reporte</button>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label class="col-md-12">Resumen de Aportes</label>
                            <div class="col-md-6">
                                

                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-primary" type="submit">Generar Reporte</button>
                            </div>
                        </div>
                       
                      <hr> -->
                      
                        
                        
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <strong>Opciones de Reportes</strong>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label" for="date-input">Fecha Inicial:</label>
                            <div class="col-md-8">
                                <input class="form-control" 
                                    type="date" v-model="fechainicio"
                                    :max="fechafin">
                                <span class="text-error" v-if="!verificarfecha">La Fecha debe ser menor a la fecha actual</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label" for="date-input">Fecha Final:</label>
                            <div class="col-md-8">
                                <input class="form-control" 
                                    type="date" v-model="fechafin"
                                    :max="fechahoy">
                                <span class="text-error" v-if="!verificarfecha">La Fecha debe ser menor a la fecha actual</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label" for="select1">Fuerza:</label>
                            <div class="col-md-8">
                                <select class="form-control" id="select1" name="select1">
                                    <option value="0">Todas</option>
                                    <option value="3">Ejercito</option>
                                    <option value="4">Aerea</option>
                                    <option value="5">Armada</option>
                                    
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.col-->
        </div>
        <!-- /.row-->
    </div>
</main>
</template>

<script>

    import Vue from 'vue';
    import VeeValidate from 'vee-validate'; 
    //import * as plugin from './../functions.js';
    import * as plugin from './../functions.js';

    export default {
        data (){
            return {                
                obsascii: '',
                ascii: '',
                nuevosascii:'',
                detalleascii:'',
                verificarfecha:'',
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
                fechaactual: new Date(),
                fechahoy:'',
                mes:'',
                anio:0,
                
                messelected:0,
                anioselected:0,
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

                fechafin:'',
                fechainicio:'',
                arrayTipoaporte:[],
                idtipoaporte:0,
            };
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
            reportes(tipo,mes, anio)
            {
                let me=this;
                var url;
                var titulo; //=me.ascii+mes+'&anio='+anio;
                switch (tipo) {
                    case 1:
                        url=me.ascii+mes+'&anio='+anio;
                        titulo='Reporte Ascii';
                        break;
                    case 2:
                        url=me.nuevosascii+mes+'&anio='+anio;
                        titulo='Nuevos Socios Carga Ascii';
                        break;
                    case 3:
                        url=me.obsascii+mes+'&anio='+anio;
                        titulo='Socios Observados Carga Ascii';
                        break;
                    case 4:
                        url=me.detalleascii+mes+'&anio='+anio;
                        titulo='Detalle Carga Ascii';
                        break;
                }
                //console.log(url);
                
                plugin.viewPDF(url,titulo);
            },
            obtenerfecha(){
                let me = this;
                me.anio=me.fechaactual.getFullYear();//año
                me.mes=me.fechaactual.getMonth();//mes

                if(me.mes==0)
                {   
                    me.mes=11;
                    me.anio=me.anio-1;
                }
                else
                    me.mes=me.mes-1;
                me.messelected=me.arraymes[me.mes].value;

                
                var dd = me.fechaactual.getDate(); 
                var mm = me.fechaactual.getMonth();
                var aa = me.fechaactual.getFullYear();
                
                mm=mm+1;
                if(mm<10) 
                    mm='0'+mm;
                
                if(dd<10)
                    dd='0'+dd; 
                me.fechahoy=aa+'-'+mm+'-'+dd;
                me.fechafin=me.fechahoy;
                //me.fechafactura=me.fechaactual;
            },

            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarEstado(page,buscar,criterio);
            },
            getRutasReports (){ 
                let me=this;
                var url= '/apo_reportes';
                axios.get(url).then(function (response) {
                    var respuesta= response.data; 
                    console.log(respuesta);
                    
                    me.obsascii = respuesta.REP_OBSASCII;
                    me.nuevosascii=respuesta.REP_NUEVOSASCII;
                    me.ascii = respuesta.REP_ASCII;
                    me.detalleascii=respuesta.REP_DETALLEASCII;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectTipoaporte(){
                let me=this;
                var url= '/apo_tipoaporte/selectTipoaporte';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayTipoaporte = respuesta.tipoaportes;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        },
        mounted() {
           this.getRutasReports();
           this.obtenerfecha();
           this.selectTipoaporte();
           
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