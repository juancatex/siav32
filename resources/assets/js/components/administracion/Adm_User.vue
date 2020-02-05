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
                        <i class="fa fa-align-justify"></i> Usuario
                        <button type="button" @click="abrirModal('user','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!-- <select class="form-control col-md-3" v-model="criterio">
                                      <option value="username">Nombre</option>                                      
                                    </select> -->
                                    <input type="text" v-model="buscar" @keyup.enter="listarUser(1,buscar,criterio)" class="form-control" placeholder="Usuario a buscar">
                                    <button type="submit" @click="listarUser(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th> 
                                    <th>Nombre</th> 
                                    <th>Username</th>
                                    <th>Correo</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in arrayUser" :key="user.iduser">
                                    <td>
                                        <button type="button" @click="abrirModal('user','actualizar',user)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="user.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivar(user.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activar(user.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td> 
                                    <td v-text="user.nombre"></td>
                                    <td v-text="user.username"></td>
                                    <td v-text="user.email"></td>
                                    <td>
                                        <div v-if="user.activo">
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
            
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" user="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" user="document">
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
                                    <label class="col-md-3 form-control-label">Empleado:</label>
                                    <div class="col-md-6"> 
                                        <Ajaxselect v-if="clearSelected"
                                        ruta="/rrh_empleado/selectempleados?buscar=" @found="listaSocios" @cleaning="cleanempleados"
                                        resp_ruta="empleados"
                                        labels="nombres"
                                        placeholder="Ingrese Texto..." 
                                        idtabla="idempleado"
                                        :id=idempleado1
                                        :clearable='true'>
                                    </Ajaxselect>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Username</label>
                                    <div class="col-md-6">
                                        <input v-validate.initial="'required'"
                                                type="text" 
                                                v-model="username" 
                                                class="form-control" 
                                                placeholder="username"
                                                name="username"> 
                                        <span class="text-error">{{ errors.first('username') }}</span>                                       
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Correo</label>
                                    <div class="col-md-6">
                                        <input v-validate.initial="'required|email'" :class="{'input': true, 'is-danger': errors.has('email') }"
                                                type="text" 
                                                v-model="email" 
                                                class="form-control formu-entrada"
                                                placeholder="Email"
                                                name="Email"> 
                                        <span class="text-error">{{ errors.first('Email') }}</span>                                       
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Password</label>
                                    <div class="col-md-6">
                                        <input  v-validate.initial="'required'"
                                                type="password" 
                                                v-model="password" 
                                                class="form-control" 
                                                placeholder="password"
                                                name="password"> 
                                        <span class="text-error">{{ errors.first('password') }}</span>                                       
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Confirmar Password</label>
                                    <div class="col-md-6">
                                        <input  v-validate.initial="'required'"
                                                type="password" 
                                                v-model="password2" 
                                                class="form-control" 
                                                placeholder="password"
                                                name="password"> 
                                        <span class="text-error">{{ errors.first('password') }}</span>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarUser()">Guardar</button>
                            <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarUser()">Actualizar</button>
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
                user_id: 0, 
                idempleado1 :'',
                clearSelected:1,
                username : '', 
                email : '', 
                password : '', 
                password2 : '', 
                arrayUser : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorRole : 0,
                errorMostrarMsjUser : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'username',
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
            cleanempleados(){
                this.idempleado='';            
            },

            tiempo(){
                this.clearSelected=1;
            }, 

            validateBeforeSubmit() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                    return;
                    }
                    return;
                });
            },

            listaSocios(ads) {           
                this.idempleado1 = ads.idempleado;
                //console.log(ads.idempleado);
            },

            listarUser (page,buscar,criterio){
                let me=this;
                var url= '/adm_user?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;                
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayUser = respuesta.users.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectRol(){
                let me=this;
                var url= '/adm_user/selectRol';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayUser = respuesta.users;
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
                me.listarUser(page,buscar,criterio);
            },

            registrarUser(){
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
                let me = this;

                axios.post('/adm_user/registrar',{ 
                    'idempleado': this.idempleado1, 
                    'username': this.username, 
                    'password': this.password, 
                    'password2': this.password2, 
                    'email': this.email, 
                }).then(function (response) {
                    if (response.data.length) { 
                        if (response.data=="no") {
                            swal(
                            // response.data,
                            'Error Password',
                            'Password no coincide',
                            'error')
                        }
                        else {
                            swal(
                            // response.data,
                            'El usuario ya existe',
                            'Ingresar un dato diferente',
                            'error')
                        }                        
                    }
                    else {
                        swal("¡Se registraron los datos correctamente!", "", "success").then((result) => {
                            me.cerrarModal();
                            me.listarUser(1,'','username'); 
                        })
                    }                    
                }).catch(function (error) { 
					console.log(error);
							me.output = error;
							swal("¡Error al momento de registrar el usuario!", error.message, "error");
                });
            },

            actualizarUser(){ 
                let me = this;
                axios.put('/adm_user/actualizar',{ 
                    'idempleado': me.idempleado1, 
                    'username': this.username,
                    'iduser': this.user_id,
                    'email': this.email, 
                    'password': this.password, 
                    'password2': this.password2, 
                }).then(function (response) {
                    if (response.data=="no") {
                            swal(
                            // response.data,
                            'Error Password',
                            'Password no coincide',
                            'error')
                        }
                    else {
                    swal("¡Se actualizaron los datos correctamente!", "", "success").then((result) => {
                        me.cerrarModal(); 
                        me.listarUser(1,'','username'); 
                    })    
                    }                    					
					
                }).catch(function (error) {
                    console.log(error);
					me.output = error;
					swal("¡Error al momento de actualizar los datos!", error.message, "error");
                }); 
            },

            desactivar(iduser){ 
               swal({
                title: 'Esta seguro de desactivar este Usuario?',
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

                    axios.put('/adm_user/desactivar',{
                        'iduser': iduser
                    }).then(function (response) {
                        me.listarUser(1,'','username');
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

            activar(iduser){
               swal({
                title: 'Esta seguro de activar este Usuario?',
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

                    axios.put('/adm_user/activar',{
                        'iduser': iduser
                    }).then(function (response) {
                        me.listarUser(1,'','username');
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
                this.clearSelected=0;
                this.username = '';
                this.errorRole=0;
            },

            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "user":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.clearSelected=1;
                                this.modal = 1;
                                this.tituloModal = 'Registrar Usuario';  
                                this.idempleado1= '';
                                this.username= '';
                                this.password= '';
                                this.password2= '';
                                this.email= '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Usuario';
                                this.tipoAccion=2;
                                this.user_id=data['id'];  
                                this.idempleado1= data['idempleado'];
                                this.clearSelected=0;
                                setTimeout(this.tiempo, 200); 
                                this.username = data['username'];
                                this.email = data['email'];
                                this.password= '';
                                this.password2= '';
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarUser(1,this.buscar,this.criterio);
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
