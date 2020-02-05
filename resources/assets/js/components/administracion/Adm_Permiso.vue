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
                        <i class="fa fa-align-justify"></i> Menu
                        <button type="button" @click="abrirModal('permiso','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select  class="form-control"
                                        v-model="idmodulo" @change="onChangeModulo(idmodulo)">
                                        <option selected="selected" value="" disabled >Modulo...</option>
                                        <option v-for="modulo in arrayModulo" :key="modulo.idmodulo" :value="modulo.idmodulo" v-text="modulo.nommodulo"></option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarPermiso(1,buscar,criterio,idmodulo)" class="form-control" placeholder="Nombre de Menu">
                                    <button type="submit" @click="listarPermiso(1,buscar,criterio,idmodulo)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th> 
                                    <th>Menu</th>
                                    <th>Descripcion</th>
                                    <th>Modulo</th>                                    
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="permiso in arrayPermiso" :key="permiso.idpermiso">
                                    <td>
                                        <button type="button" @click="abrirModal('permiso','actualizar',permiso)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="permiso.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivar(permiso.idpermiso)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activar(permiso.idpermiso)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td> 
                                    <td v-text="permiso.nompermiso"></td>
                                    <td v-text="permiso.descripcion"></td>
                                    <td v-text="permiso.nommodulo"></td>
                                    <td>
                                        <div v-if="permiso.activo">
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
            
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" permiso="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" permiso="document">
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
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre Menu</label>
                                    <div class="col-md-9">
                                        <input  v-validate.initial="'required'"
                                                type="text" 
                                                v-model="nompermiso" 
                                                class="form-control" 
                                                placeholder="Nombre de permiso"
                                                name="permiso"> 
                                        <span class="text-error">{{ errors.first('permiso') }}</span>                                       
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Descripcion Menu</label>
                                    <div class="col-md-9">
                                        <input  v-validate.initial="'required'"
                                                type="text" 
                                                v-model="descripcion" 
                                                class="form-control" 
                                                placeholder="Descripcion de Permiso"
                                                name="Descripcion"> 
                                        <span class="text-error">{{ errors.first('Descripcion') }}</span>                                       
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Ventana Modulo</label>
                                        <div class="col-md-9">
                                            <select class="form-control col-md-3"
                                                v-model="idmodulo" @change="onChangeVentana(idmodulo)">
                                                <option selected="selected" value="" disabled >Modulo...</option>
                                                <option v-for="modulo in arrayModulo" :key="modulo.idmodulo" :value="modulo.idmodulo" v-text="modulo.nommodulo"></option>
                                            </select>

                                            <select class="form-control col-md-6"
                                                v-model="idventanamodulo"
                                                v-validate.initial="'required'"
                                                name="Modulo"
                                                @change="listarPermisos1(idventanamodulo)">
                                                <option value="0">Seleccione</option>
                                                <option
                                                    v-for="modulo in arrayVentanamodulo"
                                                    :key="modulo.idventanamodulo"
                                                    :value="modulo.idventanamodulo"
                                                    v-text="modulo.nomventanamodulo +'  -  '+ modulo.nommodulo"
                                                ></option>
                                            </select>
                                            <span class="text-error">{{ errors.first('Modulo')}}</span>                                    
                                        </div>
                                </div>
                                <!-- <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Permisos</label>
                                     <div class="col-md-9">
                                        <input  v-validate.initial="'required'"
                                                type="text" 
                                                v-model="metodo" 
                                                class="form-control" 
                                                placeholder="Metodo"
                                                name="Metodo"> 
                                        <span class="text-error">{{ errors.first('Metodo') }}</span>
                                    </div> 
                                    <div class="col-md-9">
                                        <div v-for="permis in arrayPer" :key="permis.id">
                                            {{permis}}
                                        </div>
                                    </div>
                                </div> -->
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrar()">Guardar</button>
                            <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizar()">Actualizar</button>
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
    
    
    // Vue.component("socio", require("../Socio.vue").default);
        //var socio= require("../Socio.vue").default;
        //console.log(socio.data().tipoAccion);
    //Vue.component("socioss", socio);

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
                permiso_id: 0, 
                idventanamodulo : '',
                nompermiso : '', 
                descripcion : '', 
                metodo : '', 
                arrayPermiso : [],
                arrayModulo : [], idmodulo:'',
                arrayVentanamodulo : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorPermiso : 0,
                errorMostrarMsjPermiso : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nompermiso',
                buscar : '',
                nomventanamodulo : '', arrayPer:'',
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

            selectVentanamodulo() {
                let me = this;
                var url = "/par_ventanamodulo/selectVentanamodulo";
                axios.get(url).then(function(response) {
                    //console.log(response.data);
                    var respuesta = response.data; //alert(respuesta.ventanamodulos[0].nommodulo);
                    me.arrayVentanamodulo = respuesta.ventanamodulos;
                })
                .catch(function(error) {
                    console.log(error);
                });
            },
            
            onChangeVentana(idmodulo) {
                let me = this;
                var url = "/par_ventanamodulo/selectVentanamodulo?idmodulo=" + idmodulo;
                axios.get(url).then(function(response) {
                    //console.log(response.data);
                    var respuesta = response.data; //alert(respuesta.ventanamodulos[0].nommodulo);
                    me.arrayVentanamodulo = respuesta.ventanamodulos;
                })
                .catch(function(error) {
                    console.log(error);
                });
            },
            
            listarPermisos1 (e) {
                //buscamos el nombre del modulo
                let me = this;
                var url = "/par_ventanamodulo/selectVentanamodulo?id=" + e;
                axios.get(url).then(function(response) {
                    //console.log(response.data);
                    me.nomventanamodulo = response.data.ventanamodulos[0].template; 
                    //buscamos los permisos
                    // for (var i=0; i < DatasVues.length; i++) {                        
                    //     if (DatasVues[i].name===me.nomventanamodulo) {                            
                    //         me.arrayPer = (DatasVues[i]);                            
                    //         console.log(me.arrayPer); 
                    //     }
                    // }
                })
                .catch(function(error) {
                    console.log(error);
                });
               
                // alert(DatasVues[56].permisos.editar);
            },

            onChangeModulo(idmodulo) {
                this.listarPermiso(1,this.buscar,'',idmodulo); 
                //console.log(this.buscar, idmodulo);
            },

            listarPermiso (page,buscar,criterio,idmodulo=''){
                let me=this;
                if (idmodulo!='')
                    var url= '/adm_permiso?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio + '&idmodulo='+ idmodulo;
                else
                    var url= '/adm_permiso?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayPermiso = respuesta.permisos.data;
                    me.pagination= respuesta.pagination;
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

            selectPermiso(){
                let me=this;
                var url= '/adm_permiso/selectPermiso';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayPermiso = respuesta.permisos;
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
                me.listarPermiso(page,buscar,criterio);
            },

            registrar(){      
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

                axios.post('/adm_permiso/registrar',{ 
                    'nompermiso': this.nompermiso, 
                    'descripcion': this.descripcion, 
                    'idventanamodulo': this.idventanamodulo, 
                    'metodo': this.metodo, 
                }).then(function (response) {
                    if (response.data.length) {
                        swal(
                            'El valor ya existe',
                            'Ingresar una dato diferente',
                            'error')
                    }
                    else {
                        swal("¡Se registraron los datos correctamente!", "", "success").then((result) => {
                            me.cerrarModal();
                            me.listarPermiso(1,'','nompermiso'); 
                        })                        
                    }                    										
                }).catch(function (error) { 
					console.log(error);
							me.output = error;
							swal("¡Error al momento de registrar los datos!", error.message, "error");
                });
            },

            actualizar(){
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

                axios.put('/adm_permiso/actualizar',{ 
                    'nompermiso': this.nompermiso,
                    'idpermiso': this.permiso_id,
                    'idventanamodulo': this.idventanamodulo,
                    'descripcion': this.descripcion, 
                    'metodo': this.metodo
                }).then(function (response) {
                    if (response.data.length) {
                        swal(
                            'El valor ya existe',
                            'Ingresar una dato diferente',
                            'error')
                    }
                    else {
                        swal("¡Se registraron los datos correctamente!", "", "success").then((result) => {
                            me.cerrarModal();
                            me.listarPermiso(1,'','nompermiso', me.idmodulo); 
                        }) 
                    }
					
                }).catch(function (error) {
                    console.log(error);
					me.output = error;
					swal("¡Error al momento de actualizar los datos!", error.message, "error");
                }); 
            },

            desactivar(idpermiso){
               swal({
                title: 'Esta seguro de desactivar este Permiso?',
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

                    axios.put('/adm_permiso/desactivar',{
                        'idpermiso': idpermiso
                    }).then(function (response) {
                        me.listarPermiso(1,'','nompermiso');
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

            activar(idpermiso){
               swal({
                title: 'Esta seguro de activar este Permiso?',
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

                    axios.put('/adm_permiso/activar',{
                        'idpermiso': idpermiso
                    }).then(function (response) {
                        me.listarPermiso(1,'','nompermiso');
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
                
                this.nompermiso = '';
                this.errorPermiso=0;
            },

            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "permiso":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Menu';  
                                this.nompermiso= '';
                                this.descripcion= '';
                                this.idventanamodulo= '';
                                this.metodo= '';
                                this.tipoAccion = 1;
                                this.selectVentanamodulo();
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Menu';
                                this.tipoAccion=2;
                                this.permiso_id=data['idpermiso'];  
                                this.nompermiso = data['nompermiso'];
                                this.idmodulo = data['idmodulo'];
                                this.descripcion = data['descripcion'];
                                this.idventanamodulo = data['idventanamodulo'];
                                this.metodo = data['metodo'];
                                this.onChangeVentana(data['idmodulo']);
                                break;
                            }
                        }
                    }
                   
                }
            }
        },
        mounted() {
            this.listarPermiso(1,this.buscar,this.criterio); 
            this.selectModulo();
            //console.log(DatasVues); 
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
