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
                        <i class="fa fa-align-justify"></i> Ventana Modulo
                        <button type="button" @click="abrirModal('ventanamodulo','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!-- <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nomventanamodulo">Nombre </option>                                      
                                    </select> -->
                                    <input type="text" v-model="buscar" @keyup.enter="listarVentanamodulo(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarVentanamodulo(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th> 
                                    <th>Nombre Ventana Modulo</th>
                                    <th>Template</th>
                                    <th>Modulo</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="ventanamodulo in arrayVentanamodulo" :key="ventanamodulo.idventanamodulo">
                                    <td>
                                        <button type="button" @click="abrirModal('ventanamodulo','actualizar',ventanamodulo)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="ventanamodulo.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarVentanamodulo(ventanamodulo.idventanamodulo)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarVentanamodulo(ventanamodulo.idventanamodulo)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td> 
                                    <td v-text="ventanamodulo.nomventanamodulo"></td>
                                    <td v-text="ventanamodulo.template"></td>
                                    <td v-text="ventanamodulo.nommodulo"></td>
                                    <td>
                                        <div v-if="ventanamodulo.activo">
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
            
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" ventanamodulo="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" ventanamodulo="document">
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
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre Ventanamodulo</label>
                                    <div class="col-md-9">
                                        <input  v-validate.initial="'required'"
                                                type="text" 
                                                v-model="nomventanamodulo" 
                                                class="form-control" 
                                                placeholder="Nombre"
                                                name="nombre"> 
                                        <span class="text-error">{{ errors.first('nombre') }}</span>                                       
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Template <i>(Ej. pre_cobranza)</i></label>
                                    <div class="col-md-9">
                                        <input  v-validate.initial="'required'"
                                                type="text" 
                                                v-model="template" 
                                                class="form-control" 
                                                placeholder="template"
                                                name="template"> 
                                        <span class="text-error">{{ errors.first('template') }}</span>                                       
                                    </div>
                                </div>
                                <div class="card-body">
                                                                                                            
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Modulo</label>
                                        <div class="col-md-9">
                                            <select class="form-control col-md-3" 
                                                    v-model="idmodulo"
                                                    name="Modulo">
                                                <option value="0">Ninguno</option>
                                                <option v-for="ventanamodulo in arrayModulo" :key="ventanamodulo.idmodulo" :value="ventanamodulo.idmodulo" v-text="ventanamodulo.nommodulo"></option>
                                            </select>                                        
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarVentanamodulo()">Guardar</button>
                            <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarVentanamodulo()">Actualizar</button>
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
                ventanamodulo_id: 0, 
                nomventanamodulo : '', 
                template : '', 
                idventanamodulo: '',
                idmodulo: '',
                arrayVentanamodulo : [],
                arrayModulo : [],
                modal : 0,
                ventanamodulos :'',
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
                criterio : 'nomventanamodulo',
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


            listarVentanamodulo (page,buscar,criterio){
                let me=this;
                var url= '/adm_ventanamodulo?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayVentanamodulo = respuesta.ventanamodulos.data;
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
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarVentanamodulo(page,buscar,criterio);
            },
            registrarVentanamodulo(){
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

                axios.post('/adm_ventanamodulo/registrar',{ 
                    'nomventanamodulo': this.nomventanamodulo, 
                    'template': this.template, 
                    'idmodulo': this.idmodulo, 
                }).then(function (response) {
                    if (response.data.length) {                        
                        swal(
                            // response.data,
                            'El valor ya existe',
                            'Ingresar una dato diferente',
                            'error')
                    }
                    else {
                        
                        swal("¡Se actualizaron los datos correctamente!", "", "success").then((result) => {
                        me.cerrarModal();
                        me.listarVentanamodulo(1,'','nomventanamodulo'); 
                      })
                        
                    }                    										
                }).catch(function (error) { 
					console.log(error);
							me.output = error;
							swal("¡Error al momento de registrar los datos!", error.message, "error");
                });
            },
            actualizarVentanamodulo(){
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

                axios.put('/adm_ventanamodulo/actualizar',{ 
                    'nomventanamodulo': this.nomventanamodulo,
                    'idventanamodulo': this.ventanamodulo_id,
                    'template': this.template,
                    'idmodulo': this.idmodulo,  
                }).then(function (response) {
                     me.cerrarModal(); 
					swal("¡Se actualizaron los datos correctamente!", "", "success").then((result) => {
					     me.listarVentanamodulo(1,'','nomventanamodulo'); 
                      })
					
                }).catch(function (error) {
                    console.log(error);
					me.output = error;
					swal("¡Error al momento de actualizar los datos!", error.message, "error");
                }); 
            },
            desactivarVentanamodulo(idventanamodulo){
               swal({
                title: 'Esta seguro de desactivar?',
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

                    axios.put('/adm_ventanamodulo/desactivar',{
                        'idventanamodulo': idventanamodulo
                    }).then(function (response) {
                        me.listarVentanamodulo(1,'','nomventanamodulo');
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
            activarVentanamodulo(idventanamodulo){
               swal({
                title: 'Esta seguro de activar?',
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

                    axios.put('/adm_ventanamodulo/activar',{
                        'idventanamodulo': idventanamodulo
                    }).then(function (response) {
                        me.listarVentanamodulo(1,'','nomventanamodulo');
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
                
                this.nomventanamodulo = '';
                this.errorRole=0;
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "ventanamodulo":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Ventana Modulo';  
                                this.nomventanamodulo= '';
                                this.template= '';
                                this.idventanamodulo= '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Ventana Modulo';
                                this.tipoAccion=2;
                                this.ventanamodulo_id=data['idventanamodulo'];  
                                this.idmodulo=data['idmodulo'];  
                                this.nomventanamodulo = data['nomventanamodulo'];
                                this.template = data['template'];
                                break;
                            }
                        }
                    }
                }
                this.selectModulo();
            }
        },
        mounted() {
            this.listarVentanamodulo(1,this.buscar,this.criterio);
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
