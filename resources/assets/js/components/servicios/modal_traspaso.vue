<template>
    <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Lista de Ambientes Disponibles</a></li>
    </ol>
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Listado                
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Ambiente</th>
                            <th>Tipo</th>                                    
                            <th>Capacidad</th>
                            <th>Ocupados</th>
                            <th>Disponibles</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="libres in arrayLibres" :key="libres.id">                                                
                            <td v-text="libres.codambiente"></td>
                            <td v-text="libres.tipo"></td>  
                            <td v-text="libres.capacidad"></td>                            
                            <td v-text="libres.ocupados"></td>                            
                            <td v-text="libres.disponibles"></td>                            
                        </tr>                                
                    </tbody>
                </table>                
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
                arrayLibres : [],
                tipoAccion : 0,
                errorEscalafon : 0,
                errorMostrarMsjEscalafon : [],
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
        methods : {

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
                repo.viewPDF(url,title);
            },
            
             listarRegistrados (page,buscar){
                let me=this;
                var url= '/ser_asignacion/listarRegistrados?page=' + page + '&buscar='+ buscar;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayLibres = respuesta.libres.data; 
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
    }
</script>
