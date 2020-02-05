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
                        <i class="fa fa-align-justify"></i> Especialidades
                        <button type="button" @click="abrirModal('especialidad','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nomespecialidad">Nombre Especialidad</option>
                                      
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarEspecialidad(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarEspecialidad(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Fuerza</th>
                                    <th>Nombre Especialidad</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="especialidad in arrayEspecialidad" :key="especialidad.id">
                                    <td>
                                        <button type="button" @click="abrirModal('especialidad','actualizar',especialidad)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="especialidad.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarEspecialidad(especialidad.idespecialidad)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarEspecialidad(especialidad.idespecialidad)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="especialidad.nomfuerza"></td>
                                    <td v-text="especialidad.nomespecialidad"></td>
                                    
                                    <td>
                                        <div v-if="especialidad.activo">
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
            <!-- <form @submit.prevent="validateBeforeSubmit"> -->
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
                                    <label class="col-md-3 form-control-label" for="text-input">Fuerza</label>
                                    <div class="col-md-9">
                                        <select class="form-control" 
                                                v-model="idfuerza"
                                                v-validate.initial="'required'"                                                
                                                name="Fuerza" required>
                                            <option value="0" disabled>Seleccione</option>
                                            <option v-for="fuerza in arrayFuerza" :key="fuerza.idfuerza" :value="fuerza.idfuerza" v-text="fuerza.nomfuerza"></option>
                                        </select>                                        
                                        <span class="text-error">{{ errors.first('Fuerza')}}</span>   <!--Lineas Agregadas<-->
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre Especialidad</label>
                                    <div class="col-md-9">
                                        <input  v-validate.initial="'required'"
                                                type="text" 
                                                v-model="nomespecialidad" 
                                                class="form-control" 
                                                placeholder="Nombre de Especialidad"
                                                name="Nombre de Especialidad">   

                                        <span class="text-error">{{ errors.first('Nombre de Especialidad')}}</span>   <!--Lineas Agregadas<-->                                     

                                    </div>
                                </div>

                                <!--
                                <div v-show="errorEspecialidad" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjEspecialidad" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                                -->
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- modificar boton aceptar -->
                            <button :disabled = "errors.any()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarEspecialidad()">Guardar</button>
                            <button :disabled = "errors.any()" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarEspecialidad()">Actualizar</button>
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
    // import VueBarcode from 'vue-barcode';
    // import Vue from 'vue';
    // import VeeValidate from 'vee-validate';

    // const VueValidationEs = require('vee-validate/dist/locale/es');
    //     Vue.use(VeeValidate, 
    //     {
    //         locale: 'es',
    //         dictionary: 
    //         {
    //             es: VueValidationEs
    //         }
    //     });

    // Vue.use(VeeValidate);

    export default {
        data (){
            return {
                especialidad_id: 0,
                idfuerza : 0,
                nomespecialidad : '',
                nomfuerza:'',
                arrayEspecialidad : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorEspecialidad : 0,
                errorMostrarMsjEspecialidad : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nomespecialidad',
                buscar : '',
                arrayFuerza :[]
            }
        },
        // components: {
        // 'barcode': VueBarcode
 
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
          /*  validateBeforeSubmit() {
                this.$validator.this.Type.then((result) => {
                    if (result) {
                    //alert('enviado');
                    //return this.result;
                    return;
                    }
                    
                    //alert('no enviado');
                    return;
                    ////alert(result);
                    
                    
                });
            },*/

            listarEspecialidad (page,buscar,criterio){
                let me=this;
                var url= '/par_especialidad?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayEspecialidad = respuesta.especialidades.data;
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
                me.listarEspecialidad(page,buscar,criterio);
            },
            registrarEspecialidad(){
                /*if (this.validarEspecialidad()){
                    return;
                }*/
                
                let me = this;
                axios.post('/par_especialidad/registrar',{
                    'idfuerza': this.idfuerza,
                    'nomespecialidad': this.nomespecialidad
                    
                // }).then(function (response) {
                //     me.cerrarModal();
                //     me.listarEspecialidad(1,'','nomespecialidad');
                // }).catch(function (error) {
                //     console.log(error);
                // });
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
                        me.listarEspecialidad(1,'','nomespecialidad');
                    }
                    
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarEspecialidad(){
               /*if (this.validarEspecialidad()){
                    return;
                }*/
                
                let me = this;

                axios.put('/par_especialidad/actualizar',{
                    'idfuerza': this.idfuerza,
                    'nomespecialidad': this.nomespecialidad,
                    'idespecialidad': this.especialidad_id
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
                        me.listarEspecialidad(1,'','nomespecialidad');
                    }
                    

                   
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            desactivarEspecialidad(idespecialidad){
               swal({
                title: 'Esta seguro de desactivar esta Especialidad?',
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

                    axios.put('/par_especialidad/desactivar',{
                        'idespecialidad': idespecialidad
                    }).then(function (response) {
                        me.listarEspecialidad(1,'','nomespecialidad');
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
            activarEspecialidad(idespecialidad){
               swal({
                title: 'Esta seguro de activar esta especialidad?',
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

                    axios.put('/par_especialidad/activar',{
                        'idespecialidad': id
                    }).then(function (response) {
                        me.listarEspecialidad(1,'','nomespecialidad');
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

            /*
            validarEspecialidad(){
                this.errorEspecialidad=0;
                this.errorMostrarMsjEspecialidad =[];

                if (this.idfuerza==0) this.errorMostrarMsjEspecialidad.push("Seleccione un Fuerza.");
                if (!this.nomespecialidad) this.errorMostrarMsjEspecialidad.push("El nombre de la Especialidad no puede estar vacío.");
                
                if (this.errorMostrarMsjEspecialidad.length) this.errorEspecialidad = 1;

                return this.errorEspecialidad;
            },*/
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.idfuerza= 0;
                this.nomfuerza = '';
                
                this.nomespecialidad = '';
                this.errorEspecialidad=0;
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "especialidad":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Especialidad';
                                this.idfuerza=0;
                                this.nomfuerza='';
                                this.nomespecialidad= '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Especialidad';
                                this.tipoAccion=2;
                                this.especialidad_id=data['idespecialidad'];
                                this.idfuerza=data['idfuerza'];
                                
                                this.nomespecialidad = data['nomespecialidad'];
                                break;
                            }
                        }
                    }
                }
                this.selectFuerza();
            }
        },
        mounted() {
            this.listarEspecialidad(1,this.buscar,this.criterio);
        }
    }
</script>
