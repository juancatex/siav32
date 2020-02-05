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
                        <i class="fa fa-align-justify"></i> Municipios
                        <button type="button" @click="abrirModal('municipio','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nommunicipio">Nombre de Municipio</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarMunicipio(1,buscar,criterio)" class="form-control" placeholder="Municipio a buscar">
                                    <button type="submit" @click="listarMunicipio(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Departamento</th>
                                    <th>Nombre Municipio</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="municipio in arrayMunicipio" :key="municipio.idmunicipio">
                                    <td>
                                        <button type="button" @click="abrirModal('municipio','actualizar',municipio)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="municipio.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarMunicipio(municipio.idmunicipio)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarMunicipio(municipio.idmunicipio)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="municipio.nomdepartamento"></td>
                                    <td v-text="municipio.nommunicipio"></td>
                                    
                                    <td>
                                        <div v-if="municipio.activo">
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
            <form @submit.prevent="validateBeforeSubmit">
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
                                    <label class="col-md-3 form-control-label" for="text-input">Departamento</label>
                                    <div class="col-md-9">
                                        <select class="form-control" 
                                                v-model="iddepartamento"
                                                v-validate.initial="'required'"
                                                name="Departamento">
                                            <option value="0">Seleccione</option>
                                            <option v-for="departamento in arrayDepartamento" :key="departamento.iddepartamento" :value="departamento.iddepartamento" v-text="departamento.nomdepartamento"></option>
                                        </select>                                        
                                        <span class="text-error">{{ errors.first('Departamento')}}</span>   <!--Lineas Agregadas<-->
                                    </div>
                                </div>
         
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre Municipio</label>
                                    <div class="col-md-9">
                                        <input  v-validate.initial="'required'"
                                                type="text" 
                                                v-model="nommunicipio" 
                                                class="form-control" 
                                                placeholder="Nombre de Municipio"
                                                name="Nombre de Municipio">   
                                        <span class="text-error">{{ errors.first('Nombre de Municipio')}}</span>   <!--Lineas Agregadas<-->
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- modificar boton aceptar -->
                            <input  :disabled = "errors.any ()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarMunicipio()" value="Guardar">
                            <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarMunicipio()">Actualizar</button>
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
    import VueBarcode from 'vue-barcode';
    import Vue from 'vue';
    import VeeValidate from 'vee-validate';

    //import vSelect from 'vue-select';
    //Vue.component( 'v-select', vSelect );
    //Vue.component('v-select', VueSelect.VueSelect)

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
                municipio_id: 0,
                iddepartamento : 0,
                nommunicipio : '',
                //nomdepartamento:'',
                arrayMunicipio : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorMunicipio : 0,
                errorMostrarMsjMunicipio : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nommunicipio',
                buscar : '',
                arrayDepartamento :[]
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
            //metodo agregado para la validacion
            validateBeforeSubmit() {
                this.$validator.validateAll().then((result) => {
                //this.$validator.validate(this.Type) => {
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

            listarMunicipio (page,buscar,criterio){
                let me=this;
                var url= '/par_municipio?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayMunicipio = respuesta.municipios.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectDepartamento(){
                let me=this;
                var url= '/par_departamento/selectDepartamento';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayDepartamento = respuesta.departamentos;
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
                me.listarMunicipio(page,buscar,criterio);
            },
            registrarMunicipio(){
                let me = this;
                axios.post('/par_municipio/registrar',{
                    'iddepartamento': this.iddepartamento,
                    'nommunicipio': this.nommunicipio,
                }).then(function (response) {
                    if(response.data.length){
                    swal(
                            'El Valor ya Existe',
                            'Ingresa un dato Diferente',
                            'error'
                       )                    }
                    else{
                        me.cerrarModal();
                        me.listarMunicipio(1,'','nommunicipio');
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarMunicipio(){
                let me = this;
                axios.put('/par_municipio/actualizar',{
                    'iddepartamento': this.iddepartamento,
                    'nommunicipio': this.nommunicipio,
                    'idmunicipio': this.municipio_id
                }).then(function (response) {
                    if(response.data.length){
                    swal(
                            'El Valor ya Existe',
                            'Ingresa un dato Diferente',
                            'error'
                       )                    }
                    else{
                        me.cerrarModal();
                        me.listarMunicipio(1,'','nommunicipio');
                    }
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            desactivarMunicipio(idmunicipio){
               swal({
                title: 'Esta seguro de desactivar esta Municipio?',
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
                    axios.put('/par_municipio/desactivar',{
                        'idmunicipio': idmunicipio
                    }).then(function (response) {
                        me.listarMunicipio(1,'','nommunicipio');
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
            activarMunicipio(idmunicipio){
               swal({
                title: 'Esta seguro de activar esta Municipio?',
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

                    axios.put('/par_municipio/activar',{
                        'idmunicipio': idmunicipio
                    }).then(function (response) {
                        me.listarMunicipio(1,'','nommunicipio');
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
                this.iddepartamento= 0;
                this.nomdepartamento = '';
                this.nommunicipio = '';
                this.errorMunicipio=0;
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "municipio":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Municipio';
                                this.iddepartamento=0;
                                this.nomdepartamento='';
                                this.nommunicipio= '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar Municipio';
                                this.tipoAccion=2;
                                this.municipio_id=data['idmunicipio'];
                                this.iddepartamento=data['iddepartamento'];
                                this.nommunicipio = data['nommunicipio'];
                                break;
                            }
                        }
                    }
                }
                this.selectDepartamento();
            }
        },
        mounted() {
            this.listarMunicipio(1,this.buscar,this.criterio);
        }
    }
</script>
