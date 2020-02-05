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
                        <i class="fa fa-align-justify"></i> Grados
                        <button type="button" @click="abrirModal('grado','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nomgrado">Nombre Grado</option>
                                      
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarGrado(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarGrado(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th> 
                                    <th>Nombre Grado</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="grado in arrayGrado" :key="grado.idgrado">
                                    <td>
                                        <button type="button" @click="abrirModal('grado','actualizar',grado)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="grado.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarGrado(grado.idgrado)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarGrado(grado.idgrado)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td> 
                                    <td v-text="grado.nomgrado"></td>
                                    
                                    <td>
                                        <div v-if="grado.activo">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                        
                                    </td>
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
            <!--Inicio del modal agregar/actualizar-->
            
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" @submit.prevent="validateBeforeSubmit">
                               
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre Grado</label>
                                    <div class="col-md-9">
                                        <input  v-validate.initial="'required'"
                                                type="text" 
                                                v-model="nomgrado" 
                                                class="form-control" 
                                                placeholder="Nombre de Grado"
                                                name="Grado"> 
                                        <span class="text-error">{{ errors.first('Grado') }}</span>                                       
                                    </div>
                                </div>

                                <!--
                                <div v-show="errorGrado" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjGrado" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                                -->

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarGrado()">Guardar</button>
                            <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarGrado()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            
            <!--Fin del modal-->
        </main>
</template>

<script>
    import VueBarcode from 'vue-barcode';
    import Vue from 'vue';
        import VeeValidate from 'vee-validate';

        
        

        const VueValidationEs = require('vee-validate/dist/locale/es');
                Vue.use(VeeValidate, {
                locale: 'es',
                dictionary: {
                es: VueValidationEs
            }
        });

    export default {
        data (){
            return {
                grado_id: 0, 
                nomgrado : '', 
                arrayGrado : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorGrado : 0,
                errorMostrarMsjGrado : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nomgrado',
                buscar : '',
                arrayFuerza :[]
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

            validateBeforeSubmit() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                    //alert('enviado');
                    //return this.result;
                    return;
                    }
                    
                    //alert('no enviado');
                    return;
                    ////alert(result);
                    
                    
                });
            },


            listarGrado (page,buscar,criterio){
                let me=this;
                var url= '/par_grado?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayGrado = respuesta.grados.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectFuerza(){
                let me=this;
                var url= '/par_fuerza/selectFuerza';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayFuerza = respuesta.fuerzas;
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
                me.listarGrado(page,buscar,criterio);
            },
            registrarGrado(){
                /*if (this.validarGrado()){
                    return;
                }*/
                
                let me = this;

                axios.post('/par_grado/registrar',{ 
                    'nomgrado': this.nomgrado, 
                }).then(function (response) {
                    me.cerrarModal(); 
					swal("¡Se registro los datos correctamente!", "", "success").then((result) => {
					     me.listarGrado(1,'','nomgrado'); 
                      })
					
                }).catch(function (error) { 
					console.log(error);
							me.output = error;
							swal("¡Error al momento de registrar los datos!", error.message, "error");
                });
            },
            actualizarGrado(){
               /*if (this.validarGrado()){
                    return;
                }*/
                
                let me = this;

                axios.put('/par_grado/actualizar',{ 
                    'nomgrado': this.nomgrado,
                    'idgrado': this.grado_id
                }).then(function (response) {
                     me.cerrarModal(); 
					swal("¡Se actualizaron los datos correctamente!", "", "success").then((result) => {
					     me.listarGrado(1,'','nomgrado'); 
                      })
					
                }).catch(function (error) {
                    console.log(error);
					me.output = error;
					swal("¡Error al momento de actualizar los datos!", error.message, "error");
                }); 
            },
            desactivarGrado(idgrado){
               swal({
                title: 'Esta seguro de desactivar esta Grado?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/par_grado/desactivar',{
                        'idgrado': idgrado
                    }).then(function (response) {
                        me.listarGrado(1,'','nomgrado');
                        swal(
                        'Desactivado!',
                        'El registro ha sido desactivado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
            activarGrado(idgrado){
               swal({
                title: 'Esta seguro de activar esta Grado?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/par_grado/activar',{
                        'idgrado': idgrado
                    }).then(function (response) {
                        me.listarGrado(1,'','nomgrado');
                        swal(
                        'Activado!',
                        'El registro ha sido activado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },/*
            validarGrado(){
                this.errorGrado=0;
                this.errorMostrarMsjGrado =[];

                if (this.idfuerza==0) this.errorMostrarMsjGrado.push("Seleccione un Fuerza.");
                if (!this.nomgrado) this.errorMostrarMsjGrado.push("El nombre de la Grado no puede estar vacío.");
                
                if (this.errorMostrarMsjGrado.length) this.errorGrado = 1;

                return this.errorGrado;
            },*/
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';  
                
                this.nomgrado = '';
                this.errorGrado=0;
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "grado":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Grado';  
                                this.nomgrado= '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Grado';
                                this.tipoAccion=2;
                                this.grado_id=data['idgrado'];  
                                this.nomgrado = data['nomgrado'];
                                break;
                            }
                        }
                    }
                }
                this.selectFuerza();
            }
        },
        mounted() {
            this.listarGrado(1,this.buscar,this.criterio);
        }
    }
</script>

