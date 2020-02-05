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
                        <i class="fa fa-align-justify"></i> Destinos
                        <button type="button" @click="abrirModal('destino','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nomdestino">Nombre Destino</option>
                                      
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarDestino(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarDestino(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Fuerza</th>
                                    <th>Nombre Destino</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="destino in arrayDestino" :key="destino.iddestino">
                                    <td>
                                        <button type="button" @click="abrirModal('destino','actualizar',destino);" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="destino.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarDestino(destino.iddestino)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarDestino(destino.iddestino)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="destino.nomfuerza "></td>
                                    <td v-text="destino.nomdestino"></td>
                                    
                                    <td>
                                        <div v-if="destino.activo">
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
                                    <label class="col-md-3 form-control-label" for="text-input">Fuerza</label>
                                    <div class="col-md-9">
                                        <select class="form-control" 
                                                v-model="idfuerza"
                                                v-validate.initial="'required'"
                                                name="Fuerza">
                                            <option value="0">Seleccione</option>
                                            <option v-for="fuerza in arrayFuerza" :key="fuerza.idfuerza" :value="fuerza.idfuerza" v-text="fuerza.nomfuerza"></option>
                                        </select>                                        
                                        <span class="text-error">{{ errors.first('Fuerza')}}</span>   <!--Lineas Agregadas<-->
                                    </div>
                                </div>
                         
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre Destino</label>
                                    <div class="col-md-9">
                                        <input  v-validate.initial="'required'"
                                                type="text" 
                                                v-model="nomdestino" 
                                                class="form-control" ref="search" id="desti"
                                                placeholder="Nombre de Destino"
                                                name="dest">   
                                        <span class="text-error">{{ errors.first('Nombre de Destino')}}</span>   <!--Lineas Agregadas<-->                                     

                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <input  :disabled = "errors.any ()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarDestino()" value="Guardar">
                            <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarDestino()">Actualizar</button>
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
                destino_id: 0,
                idfuerza : 0,
                nomdestino : '',
                nomfuerza:'',
                arrayDestino : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorDestino : 0,
                errorMostrarMsjDestino : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nomdestino',
                buscar : '',
                arrayFuerza :[]
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

    daleFocus: function()
    {
      this.$refs.search.focus();
    },

            //metodo agregado para la validacion
            validateBeforeSubmit() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        //return this.result;
                        return;
                    }
                    return;
                });
            },

            listarDestino (page,buscar,criterio){
                let me=this;
                var url= '/par_destino?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayDestino = respuesta.destinos.data;
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
                me.listarDestino(page,buscar,criterio);
            },
            registrarDestino(){
                let me = this;
                axios.post('/par_destino/registrar',{
                    'idfuerza': this.idfuerza,
                    'nomdestino': this.nomdestino,
                }).then(function (response) {
                    if(response.data.length){
                    swal(
                            'El Valor ya Existe',
                            'Ingresa un dato Diferente',
                            'error'
                       )                    }
                    else{
                        me.cerrarModal();
                        me.listarDestino(1,'','nomdestino');
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarDestino(){
                let me = this;
                axios.put('/par_destino/actualizar',{
                    'idfuerza': this.idfuerza,
                    'nomdestino': this.nomdestino,
                    'iddestino': this.destino_id
                }).then(function (response) {
                    if(response.data.length){
                    swal(
                            'El Valor ya Existe',
                            'Ingresa un dato Diferente',
                            'error'
                       )                    }
                    else{
                        me.cerrarModal();
                        me.listarDestino(1,'','nomdestino');
                    }
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            desactivarDestino(iddestino){
               swal({
                title: 'Esta seguro de desactivar este Destino?',
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
                    axios.put('/par_destino/desactivar',{
                        'iddestino': iddestino
                    }).then(function (response) {
                        me.listarDestino(1,'','nomdestino');
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
            activarDestino(iddestino){
               swal({
                title: 'Esta seguro de activar esta Destino?',
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

                    axios.put('/par_destino/activar',{
                        'iddestino': iddestino
                    }).then(function (response) {
                        me.listarDestino(1,'','nomdestino');
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
                this.idfuerza= 0;
                this.nomfuerza = '';
                this.nomdestino = '';
                this.errorDestino=0;
            },
            abrirModal(modelo, accion, data = []){   
                this.modal = 1;
                this.selectFuerza();
                switch(modelo){
                    case "destino":
                    {   switch(accion){
                            case 'registrar':{   
                                this.tituloModal = 'Registrar Destino';
                                this.idfuerza=0;
                                this.nomfuerza='';
                                this.nomdestino= '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':{   
                                this.tituloModal='Actualizar Destino';
                                this.tipoAccion=2;
                                this.destino_id=data['iddestino'];
                                this.idfuerza=data['idfuerza'];
                                this.nomdestino = data['nomdestino'];
                                break;
                            }
                        }
                    }
                    break;
                }
                //this.nomdestino.daleFocus();
                //daleFocus();
                this.$refs.search.focus();
                document.getElementById("desti").focus();
            }
        },
        mounted() {
            this.listarDestino(1,this.buscar,this.criterio);
        }
    }
</script>
