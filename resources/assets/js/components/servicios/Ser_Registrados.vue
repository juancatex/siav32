<template>
    <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Lista de Registrados Transitorios</a></li>
    </ol>
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Listado
                
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">                            
                            <input type="text" v-model="buscar" @keyup.enter="listarRegistrados(1,buscar)" class="form-control" placeholder="Buscar por Nombre, apellidos, ci, numpapeleta">
                            <button type="submit" @click="listarRegistrados(1,buscar)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Nº Asignacion</th>
                            <th>Nombre Huesped</th>
                            <th>Habitacion</th>                                    
                            <th>Fecha Entrada</th>
                            <th>Fecha Salida</th>
                            <th>Dias</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="registrados in arrayRegistrados" :key="registrados.id">
                            <td v-if="registrados.codservicio==='HTR'">
                                <button type="button" @click="mostrarReporteIn(registrados.idasignacion)" class="btn btn-warning btn-sm">
                                    <i class="icon-book-open"> B. Entrada</i>
                                </button> &nbsp;
                                <button type="button" @click="mostrarReporteOut(registrados.idasignacion)" class="btn btn-warning btn-sm">
                                    <i class="icon-book-open"> B. Salida</i>
                                </button> &nbsp;
                            </td>
                            <td>{{registrados.idasignacion}}</td>
                            <td >{{ registrados.nombres }} -{{ registrados.ci}}</td>
                            <td>{{ registrados.codambiente}} - {{ registrados.tipo }} Piso:{{ registrados.piso }}</td>
                            <td >{{registrados.fechaentrada}} - {{registrados.horaentrada}}
                                <button class="btn btn-success btn-sm" title="Imprimir Ingreso"
                                @click="imprimirasignacion(registrados)"> <i class="fas fa-print"></i> </button> </td>  
                            <td >{{registrados.fechasalida }} - {{registrados.horasalida}}
                                <button v-if="registrados.fechasalida!=null" class="btn btn-info btn-sm" title="Imprimir Salida"
                                @click="imprimirasalida(registrados)"> <i class="fas fa-print"></i> </button> </td>  
                            <td >{{registrados.cantdias}}</td>                            
                        </tr>                                
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination">
                        <li class="page-item" v-if="pagination.current_page > 1">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                        </li>
                        <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                        </li>
                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
    </div>
                
</main>
</template>
<script>        
    import * as repo from '../../functions.js';
    export default {
        data (){
            return {
                nombre : '',
                arrayRegistrados : [],
                tipoAccion : 0,
                errorEscalafon : 0,
                errorMostrarMsjEscalafon : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 10,                
                buscar : '',
                tipocliente:'',
                idasignacion:''
            }
        },
       
        formOptions: {
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
            imprimirasignacion(data=[]){
               // console.log(data);
                let me=this;
                me.tipocliente=data['tipocliente']
                me.idasignacion=data['idasignacion'];
                var url='/reporteingreso?idasignacion='+ me.idasignacion +'&tiposocio='+me.tipocliente;
                /* window.open(url, '_blank'); */
                _pl._vm2154_12186_135(url,'Reporte de Ingreso');
                me.tipocliente='';
                me.idasignacion='';
            },
            imprimirasalida(data=[]){
                //console.log(data);
                let me=this;
                me.tipocliente=data['tipocliente']
                me.idasignacion=data['idasignacion'];
                var url='/reportesalida?idasignacion='+ me.idasignacion +'&tiposocio='+me.tipocliente;
                /* window.open(url, '_blank'); */
                _pl._vm2154_12186_135(url,'Reporte de Salida');
                me.tipocliente='';
                me.idasignacion='';
            },

            getRutasReports (){ 
                let me=this;
                var url= '/ser_reportes';
                axios.get(url).then(function (response) {
                    var respuesta= response.data; ;                    
                    me.entrada = respuesta.REP_ENTRADACC;
                    me.salida = respuesta.REP_SALIDACC;
                })
                .catch(function (error) {
                    console.log(error); 
                });
            },

            mostrarReporteIn(id){ 
                var url=this.entrada + '&idasignacion='+id;
                this.reporte_resumen(url,'Boleta Entrada');                
            },

            mostrarReporteOut(id){ 
                var url=this.salida + '&idasignacion='+id;
                this.reporte_resumen(url,'Boleta Salida');                
            },

            reporte_resumen(url,title) {
                console.log(url);
                _pl._vm2154_12186_135(url,title);
            },
            
             listarRegistrados (page,buscar){
                let me=this;
                var url= '/ser_asignacion/listarRegistrados?page=' + page + '&buscar='+ buscar;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayRegistrados = respuesta.hospedaje.data; 
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarRegistrados(page,buscar);
            },
                        
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nomescalafon='';
            },

        },
        mounted() {
            this.classModal=new _pl.Modals();
            this.listarRegistrados(1,this.buscar);
            this.getRutasReports();
        }
    }
</script>
