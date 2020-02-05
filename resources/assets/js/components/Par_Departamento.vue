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
                        <i class="fa fa-align-justify"></i> Departamentos
                        <button type="button" @click="abrirModal('departamento','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nomdepartamento">Nombre</option>
                                      
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarDepartamento(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarDepartamento(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Abreviación</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="departamento in arrayDepartamento" :key="departamento.iddepartamento">
                                    <td>
                                        <button type="button" @click="abrirModal('departamento','actualizar',departamento)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="departamento.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarDepartamento(departamento.iddepartamento)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarDepartamento(departamento.iddepartamento)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="departamento.nomdepartamento"></td>
                                    <td v-text="departamento.abrvdep"></td>
                                    
                                    <td>
                                        <div v-if="departamento.activo">
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
            <form @submit.prevent="validateBeforeSubmit" @keyup.esc="cerrarModal()">
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()"  aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" @submit.prevent="validateBeforeSubmit" >
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre del Departamento</label>
                                    <div class="col-md-9">
                                        <input  v-validate.initial= "'required|alpha_spaces'"   
                                                type="text" 
                                                v-model="nomdepartamento"  @keyup.esc="cerrarModal()"
                                                class="form-control" 
                                                placeholder="Departamento"
                                                name='Nombre Departamento'
                                                autofocus
                                                >  <!-- @keyup.enter="registrarDepartamento()"  para habilitar enter -->
                                        <span class="text-error">{{ errors.first('Nombre Departamento')}}</span>   <!--Lineas Agregadas<-->
                                    </div>
                                </div>
							 
							
							   <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Abreviación</label>
                                    <div class="col-md-9">
                                        <input  v-validate.initial= "'required|alpha_spaces'"   
                                                type="text" 
                                                v-model="abrvdep"  @keyup.esc="cerrarModal()"
                                                class="form-control" 
                                                placeholder="Abreviación"
                                                name='abreviacion'
                                                autofocus
                                                >  <!-- @keyup.enter="registrarDepartamento()"  para habilitar enter -->
                                        <span class="text-error">{{ errors.first('abreviacion')}}</span>   <!--Lineas Agregadas<-->
                                    </div>
                                </div>
							

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- modificar boton aceptar -->
                            <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarDepartamento()">Guardar</button>
                            <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarDepartamento()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            </form>
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
                departamento_id: 0,
                nomdepartamento : '',
                abrvdep : '',
                arrayDepartamento : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorDepartamento : 0,
                errorMostrarMsjDepartamento : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nomdepartamento',
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




            listarDepartamento(page,buscar,criterio){
                let me=this;
                var url= '/par_departamento?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayDepartamento = respuesta.departamentos.data;
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
                me.listarDepartamento(page,buscar,criterio);
            },
            registrarDepartamento(){
                /*if (this.validarDepartamento()){
                    return;
                }*/
                
                let me = this;

                axios.post('/par_departamento/registrar',{
                    'nomdepartamento': this.nomdepartamento,
                    'abrvdep': this.abrvdep
                    
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
                        me.listarDepartamento(1,'','nomdepartamento');

                    }

                    
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarDepartamento(){
               /*if (this.validarDepartamento()){
                    return;
                }*/
                
                let me = this;

                axios.put('/par_departamento/actualizar',{
                    'nomdepartamento': this.nomdepartamento, 
                    'iddepartamento': this.departamento_id,
                    'abrvdep': this.abrvdep
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
                        me.listarDepartamento(1,'','nomdepartamento');
                    }
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            desactivarDepartamento(iddepartamento){
               swal({
                title: 'Esta seguro de desactivar este Departamento?',
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

                    axios.put('/par_departamento/desactivar',{
                        'iddepartamento': iddepartamento
                    }).then(function (response) {
                        me.listarDepartamento(1,'','nomdepartamento');
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
            activarDepartamento(iddepartamento){
               swal({
                title: 'Esta seguro de activar este Departamento?',
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

                    axios.put('/par_departamento/activar',{
                        'iddepartamento': iddepartamento
                    }).then(function (response) {
                        me.listarDepartamento(1,'','nomdepartamento');
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
            validarDepartamento(){
                this.errorDepartamento=0;
                this.errorMostrarMsjDepartamento =[];

                if (!this.nomdepartamento) this.errorMostrarMsjDepartamento.push("El nombre del Departamento no puede estar vacío.");

                if (this.errorMostrarMsjDepartamento.length) this.errorDepartamento = 1;

                return this.errorDepartamento;
            },*/
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nomdepartamento='';
                
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "departamento":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Departamento';
                                this.nomdepartamento= '';
                                this.abrvdep= '';
                                
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Departamento';
                                this.tipoAccion=2;
                                this.departamento_id=data['iddepartamento'];
                                this.nomdepartamento = data['nomdepartamento'];
                                this.abrvdep = data['abrvdep'];
                                
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarDepartamento(1,this.buscar,this.criterio);
        }
    }
</script>
