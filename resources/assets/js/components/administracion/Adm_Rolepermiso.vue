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
                        <i class="fa fa-align-justify"></i> Roles - Permisos
                        <button type="button" @click="abrirModal('rolepermiso','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!-- <select class="form-control col-md-3" v-model="criterio">
                                      <option value="username">Nombre Rol</option>
                                    </select> -->
                                    <input type="text" v-model="buscar" @keyup.enter="listarRolepermiso(1,buscar,criterio)" class="form-control" placeholder="Rol a buscar">
                                    <button type="submit" @click="listarRolepermiso(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th> 
                                    <th>Rol</th>
                                    <th>Permisos Asignados</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="rolepermiso in arrayRolePermiso_data" :key="rolepermiso.idrole">
                                    <td>
                                        <button type="button" @click="abrirModal('rolepermiso','actualizar',rolepermiso)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <!-- <template v-if="rolepermiso.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarRole(rolepermiso.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarRole(rolepermiso.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template> -->
                                    </td> 
                                    <td v-text="rolepermiso.nomrol"></td>                                    
                                    <td>     
                                        Ver Detalle en editar                                   
                                        <!-- <ul><li v-for="aux1 in (rolepermiso.permisos.split(','))" :key="aux1">{{aux1}}</li></ul> -->
                                    </td>
                                    <td><span class="badge badge-success">Relacionado al Rol</span></td>
                                    <!-- <td>
                                        <div v-if="rolepermiso.activo">
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
            
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" rolepermiso="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" rolepermiso="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-permisos">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" @submit.prevent="validateBeforeSubmit">                                                               
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Elija un Rol</label>
                                    <div class="col-md-9">
                                        <select class="form-control col-md-3" 
                                                v-model="idrole"
                                                v-validate.initial="'required'"                                                
                                                name="Rol">
                                            <option value="0">Seleccione</option>
                                            <option v-for="rol in arrayRole" :key="rol.idrole" :value="rol.idrole" v-text="rol.nomrol"></option>
                                        </select>                                        
                                        <span class="text-error">{{ errors.first('Rol')}}</span>   <!--Lineas Agregadas<-->
                                    </div>
                                </div>

                                <td class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Permisos disponibles por Modulo:</label>    
                                    <div>
                                    <label class="form-checkbox">
                                        <b>SELECCIONAR TODO / NINGUNO: </b><input type="checkbox" v-model="selectAll" @click="select">
                                        <i class="form-icon"></i>
                                    </label>                                
                                    </div>
                                    <table class="table table-bordered table-striped table-sm">                                        
                                        <tr v-for="modulo in arrayModulo" :key="modulo.idmodulo" width="100%" align="center">
                                            <th>
                                                {{(modulo.nommodulo).toUpperCase()}}
                                            </th>

                                            <td align="left">                                                
                                                <div class="container" v-for="permiso in arrayPermiso" :key="permiso.idpermiso">                                                    
                                                    <div v-if="modulo.idmodulo == permiso.idmodulo" class="row">
                                                        <label data-toggle="collapse" data-target="permiso.nompermiso" aria-expanded="false" aria-controls="collapseOne">
                                                            <input type="checkbox"        
                                                            :value="permiso.idpermiso"
                                                            :id="permiso.idpermiso"
                                                            v-model="idpermiso"
                                                            @click="check($event, permiso.idpermiso)" > {{permiso.nompermiso}}  <!--{{permiso.template}}-->   
                                                        </label>                                                                                                            
                                                        
                                                        <div class="container" :id="permiso.nompermiso" v-for="(nombre, k1) in menus" :key="nombre.k1">
                                                            <div class="row align-items-center" v-if="permiso.template===nombre">
                                                                                                                                   
                                                                <!-- Permisos: {{k1}} -->
                                                                <span class="border border-primary">
                                                                <!-- <div class="badge badge-secondary text-wrap">
                                                                    Permisos:
                                                                </div> -->

                                                                <div class="col-sm-auto" v-for="(permisos1, k) in test[k1].permisos" :key="k">                                                                
                                                                    <!-- {{permiso.idpermiso}} {{k1}} {{permisos1.nombre}} {{k}} -->
                                                                    <div v-if="habilitado[permiso.idpermiso]===true" class="form-group form-check"> 
                                                                        <input type="checkbox" class="form-check-input" v-model="jaja" :value="permiso.idpermiso+'-'+permisos1.nombre"/>                                                                                                                                                      
                                                                        <label class="form-check-label" for="exampleCheck1">{{permisos1.nombre}}</label>
                                                                    </div>
                                                                    <div v-else class="form-group form-check">
                                                                        <input type="checkbox" disabled class="form-check-input" v-model="jaja" :value="permiso.idpermiso+'-'+permisos1.nombre"/>                                                                                                                                                      
                                                                        <label class="form-check-label" for="exampleCheck1">{{permisos1.nombre}}</label>
                                                                    </div>
                                                                </div>
                                                                </span>
                                                            </div>    
                                                        </div>                                                                                                                                                                              
                                                    </div>
                                                </div>                                                
                                            </td>
                                        </tr>                                                                                    
                                    </table>
                                </td>
                            </form>
                        </div>
                        
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarRolepermiso()">Guardar</button>
                            <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarRolepermiso()">Actualizar</button>
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
   
    export default {
        data (){
            return {
                roleuser_id: 0, 
                nomrol : '', 
                arrayRolePermiso : [],
                arrayRolePermiso_data : [],
                arrayPermiso : [],
                arrayModulo : [],
                arrayRole : [],
                idrole : '',
                idpermiso : [],
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
                buscar : '',
                selectAll: false, 
                test : [], 
                jaja: [], menus: [], habilitado:[], 
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
            select() {
			this.idpermiso = [];
			    if (!this.selectAll) {
				    for (let i in this.arrayPermiso) { 
					    this.idpermiso.push(this.arrayPermiso[i].idpermiso);
                    }
                }
            },

            check: function(e, menu) {
                //console.log(e.target.checked);
                if (e.target.checked===true) {                    
                    this.habilitado[menu]=true;                         
                    //console.log(e.target.value)
                }
                else {
                    this.habilitado[menu]=false;                    
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

            listarRolepermiso (page,buscar,criterio){
                let me=this;
                var url= '/adm_role/rolepermiso?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;                
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayRolePermiso_data = respuesta.rolepermisos.data; //alert(me.rarrayRolePermiso_data[0].username);
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

            selectPermiso(){
                let me=this;
                var url= '/adm_role/selectPermiso';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayPermiso = respuesta.permisos; //console.log(me.arrayPermiso);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectModulo(){
                let me=this;
                var url= '/par_modulo/selectModulo';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayModulo = respuesta.modulos;
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
                me.listarRolepermiso(page,buscar,criterio);
            },

            registrarRolepermiso(){      
                swal({
                    title: "Registrando permisos...",
                    text: "Registro permisos",
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
                //console.log(this.idpermiso); 
                //console.log(this.jaja);                 
                
                axios.post('/adm_role/registrar_rolepermiso',{ 
                    'idrole': this.idrole, 
                    'idpermiso': this.idpermiso, 
                    'permisos': this.jaja,
                }).then(function (response) {
                    if (response.data.length) {                        
                        swal(
                            'El valor ya existe',
                            'Ingresar una dato diferente',
                            'error')
                    }
                    else {
                        swal("¡Se registraron los datos correctamente!", "", "success").then((result) => {
                            me.cerrarModal(); location.reload();
                            me.listarRolepermiso(1,'','idpermiso'); 
                        })
                        
                    }                    
                }).catch(function (error) {  
					console.log(error);
							me.output = error;
							swal("¡Error al momento de registrar el usuario!", error.message, "error");
                });
            },

            actualizarRolepermiso(){
                swal({
                    title: "Registrando permisos...",
                    text: "Registro permisos.",
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

                axios.put('/adm_role/actualizar_rolepermiso',{ 
                    'idrole': this.idrole, 
                    'idpermiso': this.idpermiso,
                    'permisos': this.jaja,
                }).then(function (response) {
                    me.cerrarModal(); 
					swal("¡Se actualizaron los roles correctamente!", "", "success").then((result) => {
					     location.reload(); me.listarRolepermiso(1,'','idpermiso'); 
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
                        me.listarRolepermiso(1,'','username');
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
                        me.listarRolepermiso(1,'','username');
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
                    case "rolepermiso":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Asignar Permisos-Rol';  
                                this.nomrol= '';
                                this.idrole= '';
                                this.idpermiso=[];
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Permisos-Rol';
                                this.tipoAccion=2;
                                this.idrole = data['idrole'];   
                                var myarr = data['permisosid'].split(",");                                
                                this.idpermiso = myarr;

                                //obtener datos 
                                //console.log(this.idpermiso);
                                var me = this;
                                var url= '/adm_role/permisosbase?role=' + this.idrole + '&idpermiso='+ this.idpermiso;
                                axios.get(url).then(function (response) {
                                    var respuesta= response.data;
                                    me.jaja = respuesta.permisosbase; //console.log(me.jaja); 
                                    //para habilitar los checkboc que tiene habilitdo el menu
                                    for (var i=0; i<me.jaja.length; i++) {
                                        var menu = me.jaja[i].split('-');
                                        me.habilitado[menu[0]]=true;                                        
                                    }

                                })
                                .catch(function (error) {
                                    console.log(error);
                                });

                                break;
                            }
                        }
                    }
                }
                this.selectRole();
                this.selectPermiso();
                this.selectModulo();
            }
        },
        mounted() {
            this.listarRolepermiso(1,this.buscar,this.criterio);
            
            for (var i=0; i<DatasVues.length; i++) {
                this.menus[i] = DatasVues[i].name;                
                //para bloquear iniclamente topdops los check de permisos
                this.habilitado[i]=false;
            };    
            
            //console.log(this.menus); 

            let me = this; var i=0;
            for (var i=0; i<DatasVues.length; i++) {
                
            var nombre = DatasVues[i].name;
            me.test.push({menu:nombre,permisos:[]}); 
            //console.log('menu:' + DatasVues[i].name + ' id: ' + i); 
            _.forEach(DatasVues[i].permisos, function(value, key) {                                        
                //console.log('permiso: ' + key + 'id: ' + i); 
                me.test[i].permisos.push({nombre:key})                        
            });

                
            }

            // me.test.push({menu:'socio',permisos:[]})
            //  _.forEach(DatasVues[56].permisos, function(value, key) {                
            //             console.log('permiso: ' + key); 
            //             me.test[0].permisos.push({nombre:key})
            //             i++;            
            //         });
                
            //this.test.push({menu:'socio',permisos:[{nombre:'asd'},{nombre:'df'},{nombre:'as'}]})
            // this.test.push({menu:'socio',permisos:[]}); 
            // this.test[0].permisos.push({nombre:'asdfasdf'})
                                             
            // console.log(this.test[56].menu);
            // console.log(this.test[56].permisos[0].nombre);
            //console.log(this.test);

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
