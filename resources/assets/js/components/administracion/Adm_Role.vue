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
                        <i class="fa fa-align-justify"></i> Roles del Usuario
                        <button type="button" @click="abrirModal('role','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!-- <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nomrol">Nombre Rol</option>                                      
                                    </select> -->
                                    <input type="text" v-model="buscar" @keyup.enter="listarRole(1,buscar,criterio)" class="form-control" placeholder="Rol a buscar">
                                    <button type="submit" @click="listarRole(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th> 
                                    <th>Nombre Rol</th>
                                    <th>Descripcion Rol</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="role in arrayRole" :key="role.idrole">
                                    <td>
                                        <button type="button" @click="abrirModal('role','actualizar',role)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="role.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarRole(role.idrole)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarRole(role.idrole)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td> 
                                    <td v-text="role.nomrol"></td>
                                    <td v-text="role.descripcionrol"></td>
                                    <td>
                                        <div v-if="role.activo">
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
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre Rol</label>
                                    <div class="col-md-9">
                                        <input  v-validate.initial="'required'"
                                                type="text" 
                                                v-model="nomrol" 
                                                class="form-control" 
                                                placeholder="Nombre de rol"
                                                name="rol"> 
                                        <span class="text-error">{{ errors.first('rol') }}</span>                                       
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Descripcion Rol</label>
                                    <div class="col-md-9">
                                        <input  v-validate.initial="'required'"
                                                type="text" 
                                                v-model="descripcionrol" 
                                                class="form-control" 
                                                placeholder="Descripcion de Rol"
                                                name="Descripcion"> 
                                        <span class="text-error">{{ errors.first('Descripcion') }}</span>                                       
                                    </div>
                                </div>
                                <div class="card-body">
                                <!-- para determinar si depende de otro rol -->
                                    <a class="btn btn-primary" data-toggle="collapse" href="#rolpadre" aria-expanded="false" aria-controls="rolpadre">Asignar Rol Padre</a>
                                    
                                    <div class="collapse multi-collapse" id="rolpadre">
                                        <div class="form-group row">
                                            <label class="col-md-3 form-control-label" for="text-input">Rol Padre</label>
                                            <div class="col-md-9">
                                                <select class="form-control" 
                                                        v-model="idrolepadre"
                                                        name="Rol">
                                                    <option value="0">Ninguno</option>
                                                    <option v-for="rol in arrayRole" :key="rol.idrole" :value="rol.idrole" v-text="rol.nomrol"></option>
                                                </select>                                        
                                            </div>
                                        </div>
                                    </div>
                                
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarRole()">Guardar</button>
                            <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarRole()">Actualizar</button>
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
                role_id: 0, 
                nomrol : '', 
                descripcionrol : '', 
                idrolepadre: '',
                arrayRole : [],
                modal : 0,
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
                criterio : 'nomrol',
                buscar : ''
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
                    return;
                    }
                    return;
                });
            },


            listarRole (page,buscar,criterio){
                let me=this;
                var url= '/adm_role?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayRole = respuesta.roles.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectRol(){
                let me=this;
                var url= '/adm_role/selectRol';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayRole = respuesta.roles;
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
                me.listarRole(page,buscar,criterio);
            },
            registrarRole(){
                swal({
                    title: "Registrando datos...",
                    text: "Registro datos",
                    type: "warning",
                    showCancelButton: false,
                    showConfirmButton: false,                    
                    closeOnConfirm: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    onOpen: () => {
                        swal.showLoading()
                    }
                });
                /*if (this.validarGrado()){
                    return;
                }*/
                
                let me = this;

                axios.post('/adm_role/registrar',{ 
                    'nomrol': this.nomrol, 
                    'descripcionrol': this.descripcionrol, 
                    'idrolepadre': this.idrolepadre, 
                }).then(function (response) {
                    if (response.data.length) {                        
                        swal(
                            // response.data,
                            'El valor ya existe',
                            'Ingresar una dato diferente',
                            'error')
                    }
                    else {                        
                        swal("¡Se registraron los datos correctamente!", "", "success").then((result) => {
                        me.cerrarModal();
                        me.listarRole(1,'','nomrol'); 
                        })                        
                    }                    										
                }).catch(function (error) { 
					console.log(error);
							me.output = error;
							swal("¡Error al momento de registrar los datos!", error.message, "error");
                });
            },
            actualizarRole(){
                swal({
                    title: "Registrando datos...",
                    text: "Registro datos",
                    type: "warning",
                    showCancelButton: false,
                    showConfirmButton: false,                    
                    closeOnConfirm: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    onOpen: () => {
                        swal.showLoading()
                    }
                });
               /*if (this.validarGrado()){
                    return;
                }*/
                
                let me = this;

                axios.put('/adm_role/actualizar',{ 
                    'nomrol': this.nomrol,
                    'idrole': this.role_id,
                    'descripcionrol': this.descripcionrol,
                    'idrolepadre': this.idrolepadre,  
                }).then(function (response) {
                    me.cerrarModal(); 
					swal("¡Se actualizaron los datos correctamente!", "", "success").then((result) => {
					     me.listarRole(1,'','nomrol'); 
                    })
					
                }).catch(function (error) {
                    console.log(error);
					me.output = error;
					swal("¡Error al momento de actualizar los datos!", error.message, "error");
                }); 
            },
            desactivarRole(idrole){
               swal({
                title: 'Esta seguro de desactivar este Rol?',
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

                    axios.put('/adm_role/desactivar',{
                        'idrole': idrole
                    }).then(function (response) {
                        me.listarRole(1,'','nomrol');
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
            activarRole(idrole){
               swal({
                title: 'Esta seguro de activar este Rol?',
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

                    axios.put('/adm_role/activar',{
                        'idrole': idrole
                    }).then(function (response) {
                        me.listarRole(1,'','nomrol');
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

            cerrarModal(){
                this.modal=0;
                this.tituloModal='';  
                
                this.nomrol = '';
                this.errorRole=0;
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "role":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Rol';  
                                this.nomrol= '';
                                this.descripcionrol= '';
                                this.idrolepadre= '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Rol';
                                this.tipoAccion=2;
                                this.role_id=data['idrole'];  
                                this.idrolepadre=data['idrolepadre'];  
                                this.nomrol = data['nomrol'];
                                this.descripcionrol = data['descripcionrol'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarRole(1,this.buscar,this.criterio);
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
