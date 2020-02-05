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
                        <i class="fa fa-align-justify"></i> Estadocivil
                        <button type="button" @click="abrirModal('estadocivil','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nomestadocivil">Nombre</option>
                                      
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarEstadocivil(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarEstadocivil(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
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
                                <tr v-for="estadocivil in arrayEstadocivil" :key="estadocivil.idestadocivil">
                                    <td>
                                        <button type="button" @click="abrirModal('estadocivil','actualizar',estadocivil)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="estadocivil.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarEstadocivil(estadocivil.idestadocivil)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarEstadocivil(estadocivil.idestadocivil)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="estadocivil.nomestadocivil"></td>
                                    
                                    <td>
                                        <div v-if="estadocivil.activo">
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
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre del Estadocivil</label>
                                    <div class="col-md-9">
                                        <!-- <input type="text" v-model="nomestadocivil" class="form-control" placeholder="Estadocivil">
                                        
                                    </div>
                                </div>
                                
                                <div v-show="errorEstadocivil" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjEstadocivil" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div> -->

                                            <input  v-validate.initial= "'required'"   
                                                type="text" 
                                                v-model="nomestadocivil" 
                                                class="form-control" 
                                                placeholder="Estadocivil"
                                                name='Nombre Estadocivil'>

                                        <span class="text-error">{{ errors.first('Nombre Estadocivil')}}</span>   <!--Lineas Agregadas<-->
                                        
                                    </div>
                                </div>
                                
                                <!-- no se utiliza esta parte porque ya no valida con la funcion antigua
                                <div v-show="errorDepartamento" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjDepartamento" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                                -->

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button :disabled = "errors.any()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarEstadocivil()">Guardar</button>
                            <button :disabled = "errors.any()" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarEstadocivil()">Actualizar</button>
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
                estadocivil_id: 0,
                nomestadocivil : '',
                arrayEstadocivil : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorEstadocivil : 0,
                errorMostrarMsjEstadocivil : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nomestadocivil',
                buscar : ''
            }
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




            listarEstadocivil(page,buscar,criterio){
                let me=this;
                var url= '/par_estadocivil?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayEstadocivil = respuesta.estadocivil.data;
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
                me.listarEstadocivil(page,buscar,criterio);
            },
            registrarEstadocivil(){
                let me = this;
                axios.post('/par_estadocivil/registrar',{
                    'nomestadocivil': this.nomestadocivil,                    
                }).then(function (response) {
                    if (response.data.length) {                        
                        swal(
                            // response.data,
                            'El valor ya existe',
                            'Ingresar una dato diferente',
                            'error')
                    }
                    else {
                        me.cerrarModal();
                        me.listarEstadocivil(1,'','nomestadocivil');
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarEstadocivil(){
            //    if (this.validarEstadocivil()){
            //         return;
            //     }
                
                let me = this;

                axios.put('/par_estadocivil/actualizar',{
                    'nomestadocivil': this.nomestadocivil,
                    
                    'idestadocivil': this.estadocivil_id
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarEstadocivil(1,'','nomestadocivil');
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            desactivarEstadocivil(idestadocivil){
               swal({
                title: 'Esta seguro de desactivar este Estadocivil?',
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

                    axios.put('/par_estadocivil/desactivar',{
                        'idestadocivil': idestadocivil
                    }).then(function (response) {
                        me.listarEstadocivil(1,'','nomestadocivil');
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
            activarEstadocivil(idestadocivil){
               swal({
                title: 'Esta seguro de activar este Estadocivil?',
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

                    axios.put('/par_estadocivil/activar',{
                        'idestadocivil': idestadocivil
                    }).then(function (response) {
                        me.listarEstadocivil(1,'','nomestadocivil');
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
            /* ////////////// comentar esta parte ya no se utiliza
            // validarEstadocivil(){
            //     this.errorEstadocivil=0;
            //     this.errorMostrarMsjEstadocivil =[];

            //     if (!this.nomestadocivil) this.errorMostrarMsjEstadocivil.push("El nombre del Estadocivil no puede estar vacío.");

            //     if (this.errorMostrarMsjEstadocivil.length) this.errorEstadocivil = 1;

            //     return this.erroreEstadocivil;
            // },*/
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nomestadocivil='';
                
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "estadocivil":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Estadocivil';
                                this.nomestadocivil= '';
                                
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Estadocivil';
                                this.tipoAccion=2;
                                this.estadocivil_id=data['idestadocivil'];
                                this.nomestadocivil = data['nomestadocivil'];
                                
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarEstadocivil(1,this.buscar,this.criterio);
        }
    }
</script>

