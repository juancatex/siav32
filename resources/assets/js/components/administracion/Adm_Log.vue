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
                        <i class="fa fa-align-justify"></i> Registro de Sistema
                        
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div>                                    
                                    Operacion: <input type="text" v-model="buscar" @keyup.enter="listarLog(1,buscar,buscar1,buscar2,buscar3)" placeholder="operacion">
                                    Usuario: <input type="text" v-model="buscar1" @keyup.enter="listarLog(1,buscar,buscar1,buscar2,buscar3)" placeholder="usuario">
                                    Fecha Inicio: <input type="text" v-model="buscar2" @keyup.enter="listarLog(1,buscar,buscar1,buscar2,buscar3)" placeholder="2019-01-01 00:00:00.000">
                                    Fecha Fin: <input type="text" v-model="buscar3" @keyup.enter="listarLog(1,buscar,buscar1,buscar2,buscar3)" placeholder="2019-01-01 00:00:00.000">                                    
                                    <button type="submit" @click="listarLog(1,buscar,buscar1,buscar2,buscar3)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Operacion</th> 
                                    <th>Consulta</th> 
                                    <th>Tipo</th>
                                    <th>Usuario</th>
                                    <th>Ver detalle</th>
                                    <th>Fecha - Hora</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="log in arrayLog" :key="log.uuid">                                    
                                    <td>{{leercontenido(log.content)}}</td>
                                    <td>{{leercontenido2(log.content)}}</td>
                                    <td v-text="log.type"></td>
                                    <td>{{leercontenido1(log.content)}}</td>
                            
                                    <td><a :href="'telescope/queries/' + log.uuid">Ver</a></td>
                                    <td v-text="log.created_at"></td>
                                </tr>                                
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,buscar1,buscar2,buscar3)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,buscar1,buscar2,buscar3)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,buscar1,buscar2,buscar3)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->                                    
            <!--Fin del modal-->
        </main>
</template>

<script>
    import VueBarcode from 'vue-barcode';
    import Vue from 'vue';
    import VeeValidate from 'vee-validate';                
        
    export default {
        data (){
            return {
                ventanamodulo_id: 0, 
                uuid : '', 
                template : '', 
                idventanamodulo: '',
                idmodulo: '',
                arrayLog : [],
                ventanamodulos :'',
                tituloModal : '',
                tipoAccion : 0,
                errorRole : 0,
                errorMostrarMsjRole : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                buscar : '',                
                buscar1 : '',
                buscar2 : '',
                buscar3 : ''
            }
        },
        components: {
        'barcode': VueBarcode
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
            
            listarLog (page,buscar,buscar1,buscar2,buscar3){
                let me=this;
                var url= '/adm_log?page=' + page + '&buscar='+ buscar + '&buscar1='+ buscar1 + '&buscar2='+ buscar2 + '&buscar3='+ buscar3;
                axios.get(url).then(function (response) {                    
                    var respuesta= response.data; 
                    me.arrayLog = respuesta.logs.data; //console.log(me.arrayLog); alert(me.arrayLog[0].type); 
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page,buscar,buscar1,buscar2,buscar3){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarLog(page,buscar,buscar1,buscar2,buscar3);
            },
            leercontenido(dato) {
                var obj = JSON.parse(dato);                
                var dato = obj.sql.split(' ')[0];
                if (dato=== 'select') {
                    return('select');
                }
                else if (dato=== 'insert') {
                    return('insert');
                }
                else if (dato=== 'update') {
                    return('update');
                }                
            },
            leercontenido1(dato) {
                var obj = JSON.parse(dato);                
                    return(obj.user['name']);                
            },
            leercontenido2(dato) {
                var obj = JSON.parse(dato);
                // var dato = obj.sql.split('from')[1];
                // var dato1 = dato.split('inner join');
                return(obj.sql);
            }
                                                           
        },
        mounted() {            
            this.listarLog(1,this.buscar,this.buscar1,this.buscar2,this.buscar3);
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
