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
                        <i class="fa fa-align-justify"></i> Tipo de Comprobantes Contables
                        <button type="button" @click="abrirModal('tipocomprobante','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Tipo de Comprobante</th>
                                    <th>Prefijo</th>
                                    <th>Descripcion</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="tipocomprobante in arrayTipocomprobante" :key="tipocomprobante.idtipocomprobante">
                                    <td>
                                        <button type="button" @click="abrirModal('tipocomprobante','actualizar',tipocomprobante)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="tipocomprobante.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarTipocomprobante(tipocomprobante.idtipocomprobante)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarTipocomprobante(tipocomprobante.idtipocomprobante)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="tipocomprobante.nomtipocomprobante"></td>
                                    <td v-text="tipocomprobante.prefijo"></td>
                                    <td v-text="tipocomprobante.descripcion"></td>
                                    
                                    <td>
                                        <div v-if="tipocomprobante.activo">
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
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre del Tipo de Comprobante</label>
                                    <div class="col-md-9">
                                        <input  v-validate.initial= "'required|alpha_spaces'"   
                                                type="text" 
                                                v-model="nomtipocomprobante"  @keyup.esc="cerrarModal()"
                                                class="form-control" 
                                                placeholder="Tipo de comprobante"
                                                name='Nombre Tipocomprobante'
                                                autofocus
                                                >  <!-- @keyup.enter="registrarTipocomprobante()"  para habilitar enter -->
                                        <span class="text-error">{{ errors.first('Nombre Tipocomprobante')}}</span>   <!--Lineas Agregadas<-->
                                    </div>
                                </div>
							 
							
							   <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Descripcion</label>
                                    <div class="col-md-9">
                                        <input   
                                                type="text" 
                                                v-model="descripcion"  @keyup.esc="cerrarModal()"
                                                class="form-control" 
                                                placeholder="Descripcion"
                                                name='abreviacion'
                                                autofocus
                                                >  <!-- @keyup.enter="registrarTipocomprobante()"  para habilitar enter -->
                                           <!--Lineas Agregadas<-->
                                    </div>
                                </div>
							

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- modificar boton aceptar -->
                            <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarTipocomprobante()">Guardar</button>
                            <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarTipocomprobante()">Actualizar</button>
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
                tipocomprobante_id: 0,
                nomtipocomprobante : '',
                descripcion : '',
                arrayTipocomprobante : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorTipocomprobante : 0,
                errorMostrarMsjTipocomprobante : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nomtipocomprobante',
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

            listarTipocomprobante(page){
                let me=this;
                var url= '/con_tipocomprobante?page=' + page;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayTipocomprobante = respuesta.tipocomprobantes.data;
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
                me.listarTipocomprobante(page);
            },
            registrarTipocomprobante(){
                /*if (this.validarTipocomprobante()){
                    return;
                }*/
                
                let me = this;

                axios.post('/con_tipocomprobante/registrar',{
                    'nomtipocomprobante': this.nomtipocomprobante,
                    'descripcion': this.descripcion
                    
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
                        me.listarTipocomprobante(1);

                    }

                    
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarTipocomprobante(){
               /*if (this.validarTipocomprobante()){
                    return;
                }*/
                
                let me = this;

                axios.put('/con_tipocomprobante/actualizar',{
                    'nomtipocomprobante': this.nomtipocomprobante, 
                    'idtipocomprobante': this.tipocomprobante_id,
                    'descripcion': this.descripcion
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
                        me.listarTipocomprobante(1);
                    }
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            desactivarTipocomprobante(idtipocomprobante){
               swal({
                title: 'Esta seguro de desactivar este Tipo de Comprobante?',
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

                    axios.put('/con_tipocomprobante/desactivar',{
                        'idtipocomprobante': idtipocomprobante
                    }).then(function (response) {
                        me.listarTipocomprobante(1);
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
            activarTipocomprobante(idtipocomprobante){
               swal({
                title: 'Esta seguro de activar este Tipo de Comprobante?',
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

                    axios.put('/con_tipocomprobante/activar',{
                        'idtipocomprobante': idtipocomprobante
                    }).then(function (response) {
                        me.listarTipocomprobante(1);
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
                this.nomtipocomprobante='';
                
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "tipocomprobante":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Tipo de Comprobante';
                                this.nomtipocomprobante= '';
                                this.descripcion= '';
                                
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Tipo de Comprobante';
                                this.tipoAccion=2;
                                this.tipocomprobante_id=data['idtipocomprobante'];
                                this.nomtipocomprobante = data['nomtipocomprobante'];
                                this.descripcion = data['descripcion'];
                                
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarTipocomprobante(1);
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
