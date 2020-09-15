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
                            <input type="text" v-model="buscar" @keyup.enter="listarRegistrados(1,buscar)" class="form-control" placeholder="Texto a buscar">
                            <button type="submit" @click="listarRegistrados(1,buscar)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Nombre Huesped</th>                                    
                            <th>Fecha Entrada</th>
                            <th>Servicio</th>
                            <th>Filial</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="registrados in arrayRegistrados" :key="registrados.id">
                            <td v-if="registrados.codservicio==='HTR'">
                                <button type="button" @click="mostrarReporte(registrados.idasignacion)" class="btn btn-warning btn-sm">
                                    <i class="icon-book-open"> Boleta Entrada</i>
                                </button> &nbsp;
                            </td>
                            <td v-else></td>
                            <td v-text="registrados.nomgrado+' '+registrados.nombre+' '+registrados.apaterno+' '+registrados.amaterno"></td>                            
                            <td v-text="registrados.fechaentrada"></td>  
                            <td v-text="registrados.codservicio"></td>                            
                            <td v-text="registrados.sigla"></td>                            
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
                buscar : ''
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

            getRutasReports (){ 
                let me=this;
                var url= '/ser_reportes';
                axios.get(url).then(function (response) {
                    var respuesta= response.data; ;                    
                    me.resumen = respuesta.REP_ENTRADACC;                    
                })
                .catch(function (error) {
                    console.log(error); 
                });
            },

            mostrarReporte(id){ 
                var url=this.resumen + id;
                this.reporte_resumen(url,'Resumen');
                
            },

            reporte_resumen(url,title) {
                console.log(url);
                repo.viewPDF(url,title);
            },
            
            
            reporteEntrada(){
            var url=[];
            url.push('http://'+this.ipbirt+':8080');
            url.push('/birt-viewer/frameset?__report=reportes/servicios');
            url.push('/ser_casacomentrada.rptdesign'); 
            url.push('&idasignacion='+this.regAsignacion.idasignacion); 
            url.push('&__format=pdf'); 
            reporte.viewPDF(url.join(''),'Boleta de Entrada');
            },

            listarRegistrados (page,buscar){
                let me=this;
                var url= '/ser_asignacion/listarRegistrados?page=' + page + '&buscar='+ buscar;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayRegistrados = respuesta.registrados.data; 
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
            this.listarRegistrados(1,this.buscar);
            this.getRutasReports();
        }
    }
</script>
