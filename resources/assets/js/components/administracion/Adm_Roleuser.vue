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
                        <i class="fa fa-align-justify"></i> Asignacion de Roles a Usuario
                        <button type="button" @click="abrirModal('roleuser','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!-- <select class="form-control col-md-3" v-model="criterio">
                                      <option value="username">Nombre Usuario</option>
                                    </select> -->
                                    <input type="text" v-model="buscar" @keyup.enter="listarRoleuser(1,buscar,criterio)" class="form-control" placeholder="Usuario a buscar">
                                    <button type="submit" @click="listarRoleuser(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th> 
                                    <th>Usuarios</th>
                                    <th>Roles Asignados</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="roleuser in arrayRoleUser_data" :key="roleuser.username">
                                    <td>
                                        <button type="button" @click="abrirModal('roleuser','actualizar',roleuser)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <!-- <template v-if="roleuser.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarRole(roleuser.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarRole(roleuser.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template> -->
                                    </td> 
                                    <td v-text="roleuser.username"></td>
                                    <td>                                        
                                        <ul><li v-for="aux1 in (roleuser.roles.split(','))" :key="aux1">{{aux1}}</li></ul>
                                    </td>
                                    <td><span class="badge badge-success">Relacionado Socio</span></td>
                                    <!-- <td>
                                        <div v-if="roleuser.activo">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                        
                                    </td> -->
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
            
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" roleuser="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" roleuser="document">
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
                                    <label class="col-md-3 form-control-label" for="text-input">Usuario</label>
                                    <div class="col-md-9">
                                        <select class="form-control col-md-3" 
                                                v-model="iduser"
                                                v-validate.initial="'required'"                                                
                                                name="Usuario">
                                            <option value="0">Seleccione</option>
                                            <option v-for="usuario in arrayUsuario" :key="usuario.id" :value="usuario.id" v-text="usuario.username"></option>
                                        </select>                                        
                                        <span class="text-error">{{ errors.first('Usuario')}}</span>   <!--Lineas Agregadas<-->
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Roles disponibles</label>
                                    <div class="col-md-9">
                                        <li v-for="role in arrayRole" :key="role.idrole">
                                            <input type="checkbox" 
                                            v-validate.initial="'required'"                                            
                                            :value="role.idrole" 
                                            :id="role.idrole" 
                                            v-model="idrole"
                                            @click="check($event)">  {{role.nomrol}}, <i>({{role.descripcionrol}})</i>
                                        </li>                                                                                
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarRoleuser()">Guardar</button>
                            <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarRoleuser()">Actualizar</button>
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
                roleuser_id: 0, 
                username : '', 
                email : '', 
                password : '', 
                arrayRoleUser : [],
                arrayRoleUser_data : [],
                arrayUsuario : [],
                arrayRole : [],
                iduser : '',
                idrole : [],
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

            check: function(e) {
                if (e.target.checked) {
                    console.log(e.target.value)
                }
            },
            
            validateBeforeSubmit() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                    return;
                    }
                    return;
                });
            },

            listarRoleuser (page,buscar,criterio){
                let me=this;
                var url= '/adm_user/roleuser?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;                
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayRoleUser_data = respuesta.roleusers.data; //alert(me.arrayRoleUser_data[0].username);
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectRole(){
                let me=this;
                var url= '/adm_user/selectRole';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayRole = respuesta.roles;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectUsuario(){
                let me=this;
                var url= '/adm_user/selectUsuario';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayUsuario = respuesta.usuarios;
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
                me.listarRoleuser(page,buscar,criterio);
            },

            registrarRoleuser(){
                let me = this;

                axios.post('/adm_user/registrar_roleuser',{ 
                    'idrole': this.idrole, 
                    'iduser': this.iduser, 
                }).then(function (response) {
                    if (response.data.length) {                        
                        swal(
                            // response.data,
                            'El valor ya existe',
                            'Ingresar una dato diferente',
                            'error')
                    }
                    else {
                        swal("¡Se actualizaron los roles correctamente!", "", "success").then((result) => {
                            me.cerrarModal(); 
					        me.listarRoleuser(1,'','username'); 
                        })                        
                    }                    
                }).catch(function (error) { 
					console.log(error);
							me.output = error;
							swal("¡Error al momento de registrar el usuario!", error.message, "error");
                });
            },
            actualizarRoleuser(){
                let me = this;

                axios.put('/adm_user/actualizar_roleuser',{ 
                    'idrole': this.idrole, 
                    'iduser': this.iduser
                }).then(function (response) {                    
					swal("¡Se actualizaron los roles correctamente!", "", "success").then((result) => {
                        me.cerrarModal(); 
					     me.listarRoleuser(1,'','username'); 
                      })					
                }).catch(function (error) {
                    console.log(error);
					me.output = error;
					swal("¡Error al momento de actualizar los datos!", error.message, "error");
                }); 
            },
            desactivarRole(iduser){
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

                    axios.put('/adm_user/desactivar_roleuser',{
                        'iduser': iduser
                    }).then(function (response) {
                        me.listarRoleuser(1,'','username');
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
            activarRole(iduser){
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

                    axios.put('/adm_user/activar_roleuser',{
                        'iduser': iduser
                    }).then(function (response) {
                        me.listarRoleuser(1,'','username');
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
                this.username = '';
                this.errorRole=0;
            },

            abrirModal(modelo, accion, data = []){// alert(data['rolesid']);
                switch(modelo){
                    case "roleuser":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Asignar Roles a Usuario';  
                                this.username= '';
                                this.iduser= '';
                                this.idrole=[];
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Roles del Usuario';
                                this.tipoAccion=2;
                                this.iduser = data['id'];                                    
                                var myarr = data['rolesid'].split(",");                                
                                this.idrole = myarr;
                                break;
                            }
                        }
                    }
                }
                this.selectUsuario();
                this.selectRole();
            }
        },
        mounted() {
            this.listarRoleuser(1,this.buscar,this.criterio);
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
