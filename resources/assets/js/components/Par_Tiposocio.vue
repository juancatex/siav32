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
                        <i class="fa fa-align-justify"></i> Tiposocios
                        <button type="button" @click="abrirModal('tiposocio','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nomtiposocio">Nombre</option>
                                      
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarTiposocio(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarTiposocio(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="tiposocio in arrayTiposocio" :key="tiposocio.idtiposocio">
                                    <td>
                                        <button type="button" @click="abrirModal('tiposocio','actualizar',tiposocio)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="tiposocio.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarTiposocio(tiposocio.idtiposocio)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarTiposocio(tiposocio.idtiposocio)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="tiposocio.nomtiposocio"></td>
                                    
                                    <td>
                                        <div v-if="tiposocio.activo">
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
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre del Tiposocio</label>
                                    <div class="col-md-9">
                                        <input  v-validate.initial= "'required'"   
                                                type="text" 
                                                v-model="nomtiposocio" 
                                                class="form-control" 
                                                placeholder="Tiposocio"
                                                name='Nombre Tiposocio'
                                                autofocus>
                                        <span class="text-error">{{ errors.first('Nombre Tiposocio')}}</span>   <!--Lineas Agregadas<-->
                                    </div>
                                </div>                                

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- modificar boton aceptar -->
                            <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarTiposocio()">Guardar</button>
                            <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarTiposocio()">Actualizar</button>
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

    import Vue from 'vue';
    import VeeValidate from 'vee-validate';

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
                tiposocio_id: 0,
                nomtiposocio : '',
                arrayTiposocio : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorTiposocio : 0,
                errorMostrarMsjTiposocio : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nomtiposocio',
                buscar : ''
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


            //metodo agregado para la validacion
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




            listarTiposocio(page,buscar,criterio){
                let me=this;
                var url= '/par_tiposocio?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayTiposocio = respuesta.tiposocios.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (response) {
                    console.log(response);
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarTiposocio(page,buscar,criterio);
            },
            registrarTiposocio(){
                /*if (this.validarTiposocio()){
                    return;
                }*/
                
                let me = this;

                axios.post('/par_tiposocio/registrar',{
                    'nomtiposocio': this.nomtiposocio
                    
                }).then(function (response) {
                    if(response.data.length){
                       //console.log(response)
                       swal(
                            'El Valor ya Existe',
                            'Ingresa un dato Diferente',
                            'error'
                       )                    }
                    else{
                        me.cerrarModal();
                        me.listarTiposocio(1,'','nomtiposocio');

                    }

                    
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarTiposocio(){
               /*if (this.validarTiposocio()){
                    return;
                }*/
                
                let me = this;

                axios.put('/par_tiposocio/actualizar',{
                    'nomtiposocio': this.nomtiposocio,
                    
                    'idtiposocio': this.tiposocio_id
                }).then(function (response) {
                    if(response.data.length){
                        swal(
                            'El Valor ya Existe',
                            'Ingresa un dato Diferente',
                            'error'
                        )
                    }
                    else{
                        me.cerrarModal();
                        me.listarTiposocio(1,'','nomtiposocio');
                    }
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            desactivarTiposocio(idtiposocio){
               swal({
                title: 'Esta seguro de desactivar este Tiposocio?',
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

                    axios.put('/par_tiposocio/desactivar',{
                        'idtiposocio': idtiposocio
                    }).then(function (response) {
                        me.listarTiposocio(1,'','nomtiposocio');
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
            activarTiposocio(idtiposocio){
               swal({
                title: 'Esta seguro de activar este Tiposocio?',
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

                    axios.put('/par_tiposocio/activar',{
                        'idtiposocio': idtiposocio
                    }).then(function (response) {
                        me.listarTiposocio(1,'','nomtiposocio');
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
            },

            /*    //////////////   comentar esta funcion ya no se usa
            validarTiposocio(){
                this.errorTiposocio=0;
                this.errorMostrarMsjTiposocio =[];

                if (!this.nomtiposocio) this.errorMostrarMsjTiposocio.push("El nombre del Tiposocio no puede estar vacío.");

                if (this.errorMostrarMsjTiposocio.length) this.errorTiposocio = 1;

                return this.errorTiposocio;
            },*/
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nomtiposocio='';
                
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "tiposocio":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Tiposocio';
                                this.nomtiposocio= '';
                                
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Tiposocio';
                                this.tipoAccion=2;
                                this.tiposocio_id=data['idtiposocio'];
                                this.nomtiposocio = data['nomtiposocio'];
                                
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarTiposocio(1,this.buscar,this.criterio);
        }
    }
</script>

