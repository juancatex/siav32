<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-nomtipodevolucion"><a href="/">Registro de Tipo Devolucion </a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Tipo Devolucion
                        <button type="button" @click="abrirModal('tipodevolucion','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Registar Tipo Devolucion
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!-- <select class="form-control col-md-3" v-model="criterio">
                                      <option value="idconfig">Nombre</option>                                      
                                    </select> -->
                                    <input type="text" v-model="buscar" @keyup.enter="listarTipodevolucion(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarTipodevolucion(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>

                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>                                    
                                    <th>Aporte Minimo</th>
                                    <th>% Retencion</th>
                                    <th>Valida cantidad <br> Aportes</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="tipodevolucion in arrayTipodevolucion" :key="tipodevolucion.id">
                                    <td>
                                        <button type="button" @click="abrirModal('tipodevolucion','actualizar',tipodevolucion)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="tipodevolucion.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarTipodevolucion(tipodevolucion.idtipodevolucion)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarTipodevolucion(tipodevolucion.idtipodevolucion)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="tipodevolucion.nomtipodevolucion"></td>
                                    <td v-text="tipodevolucion.aporteminimo"></td>
                                    <td v-text="tipodevolucion.porcentaje"></td>                                    
                                    <td v-if="tipodevolucion.valido===0">No</td>
                                    <td v-if="tipodevolucion.valido===1">Si</td>                                    
                                    <td>
                                        <div v-if="tipodevolucion.activo">
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
                                <li class="page-nomtipodevolucion" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-nomtipodevolucion" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-nomtipodevolucion" v-if="pagination.current_page < pagination.last_page">
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
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input v-validate.initial="'required'" 
                                                type="text" 
                                                v-model="nomtipodevolucion" 
                                                class="form-control" 
                                                placeholder="Tipo Devolucion"
                                                name="Tipo Devolucion">
                                    <span class="text-error">{{ errors.first('Tipo Devolucion')}}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Cantidad Aportes</label>
                                    <div class="col-md-9">
                                        <input v-validate.initial="'required'" 
                                                type="number" 
                                                v-model="cantidadaporte" 
                                                class="form-control" 
                                                placeholder="Cantidad minimo de aporte"
                                                name="cantidad aporte">
                                    <span class="text-error">{{ errors.first('cantidad aporte')}}</span>
                                    </div>                                    
                                </div>    
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Porcentaje %</label>
                                    <div class="col-md-9">
                                        <input v-validate.initial="'required'" 
                                                type="number" 
                                                v-model="porcentaje" 
                                                class="form-control" 
                                                placeholder="Ej. 2"
                                                name="porcentaje">
                                    <span class="text-error">{{ errors.first('porcentaje')}}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Valido</label>
                                    <div class="col-md-9">
                                        <input type="checkbox" id="valido" v-model="valido">
                                        <label v-if="valido===true" for="checkbox">Aceptado</label>
                                        <label v-if="valido===false" for="checkbox">No</label>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>

                            <button :disabled = "errors.any()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarTipodevolucion()">Guardar</button>
                            <button :disabled = "errors.any()" type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarTipodevolucion()">Actualizar</button>

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
                listaescalafon_id: 0,
                idconfig : '',
                nomtipodevolucion : '',
                arrayTipodevolucion : [],
                arrayValoraporte: [],
                valido: 0,
                porcentaje: '',
                cantidadaporte: '',
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorEscalafon : 0,
                errorMostrarMsjEscalafon : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 10,
                criterio : 'idconfig',
                buscar : ''
            }
        },
       
        formOptions: {
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
            listarTipodevolucion (page,buscar,criterio){
                let me=this;
                var url= '/daa_tipodevolucion?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayTipodevolucion = respuesta.tipodevolucions.data;
                    me.pagination= respuesta.pagination;
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
                me.listarTipodevolucion(page,buscar,criterio);
            },
            registrarTipodevolucion(){
               let me = this;
                axios.post('/daa_tipodevolucion/registrar',{
                    'idconfig': this.idconfig,
                    'nomtipodevolucion': this.nomtipodevolucion,
                    'cantidadaporte': this.cantidadaporte,
                    'porcentaje': this.porcentaje,
                    'valido': this.valido
                }).then(function (response) {
                    
                    if (response.data.length) {                        
                        swal(
                            // response.data,
                            'El valor ya existe',
                            'Ingresar una dato diferente',
                            'error')
                    }
                    else {
                        me.cerrarModal();
                        me.listarTipodevolucion(1,'','idconfig');
                    }
                    
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarTipodevolucion(){
                let me = this;
                axios.put('/daa_tipodevolucion/actualizar',{
                    'idconfig': this.idconfig,
                    'idtipodevolucion': this.listaescalafon_id,
                    'nomtipodevolucion': this.nomtipodevolucion,
                    'cantidadaporte': this.cantidadaporte,
                    'porcentaje': this.porcentaje,
                    'valido': this.valido
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
                        me.listarTipodevolucion(1,'','idconfig');
                    }
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            desactivarTipodevolucion(id){
               swal({
                title: 'Esta seguro de desactivar este Tipo Devolucion?',
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

                    axios.put('/daa_tipodevolucion/desactivar',{
                        'idtipodevolucion': id
                    }).then(function (response) {
                        me.listarTipodevolucion(1,'','idconfig');
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
            activarTipodevolucion(id){
               swal({
                title: 'Esta seguro de activar este Tipo Devolucion?',
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

                    axios.put('/daa_tipodevolucion/activar',{
                         'idtipodevolucion': id
                    }).then(function (response) {
                        me.listarTipodevolucion(1,'','idconfig');
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
            validarEscalafon(){
                this.errorEscalafon=0;
                this.errorMostrarMsjEscalafon =[];

                if (!this.idconfig) this.errorMostrarMsjEscalafon.push("El nombre del Tipo Devolucion no puede estar vacío.");
                if (this.errorMostrarMsjEscalafon.length) this.errorEscalafon = 1;

                return this.errorEscalafon;
            },

            selectValoraporte(){
                let me=this;
                var url= '/daa_tipodevolucion/selectValoraporte';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayValoraporte = respuesta.aportes;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.idconfig='';

            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "tipodevolucion":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Tipo Devolucion';
                                this.idconfig= '';
                                this.nomtipodevolucion= '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Tipo Devolucion';
                                this.tipoAccion=2;
                                this.listaescalafon_id=data['idtipodevolucion'];
                                this.nomtipodevolucion = data['nomtipodevolucion'];
                                this.cantidadaporte = data['aporteminimo'];
                                this.porcentaje = data['porcentaje'];
                                this.valido = data['valido'];

                                break;
                            }
                        }
                    }
                }
                //this.selectValoraporte();   
            }
        },
        mounted() {
            this.listarTipodevolucion(1,this.buscar,this.criterio);
        }
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-nomtipodevolucion !important;
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
