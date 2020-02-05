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
                        <i class="fa fa-align-justify"></i> Fuerzas
                        <button type="button" @click="abrirModal('fuerza','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nomfuerza">Nombre</option>
                                      
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarFuerza(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarFuerza(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="fuerza in arrayFuerza" :key="fuerza.idfuerza">
                                    <td>
                                        <button type="button" @click="abrirModal('fuerza','actualizar',fuerza)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="fuerza.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarFuerza(fuerza.idfuerza)" disabled>
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarFuerza(fuerza.idfuerza)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="fuerza.nomfuerza"></td>
                                    
                                    <td>
                                        <div v-if="fuerza.activo">
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
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre de la Fuerza</label>
                                    <div class="col-md-9">
                                        <input v-validate.initial="'required'"
                                            type="text" 
                                            v-model="nomfuerza" 
                                            class="form-control" 
                                            placeholder="Fuerza"
                                            name="Fuerza">
                                    <span class="text-error">{{ errors.first('Fuerza')}}</span>   <!--Lineas Agregadas<-->
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button :disabled = "errors.any()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarFuerza()">Guardar</button>
                            <button :disabled = "errors.any()" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarFuerza()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal  -->
        </main>
</template>

<script>
    export default {
        data (){
            return {
                fuerza_id: 0,
                nomfuerza : '',
                arrayFuerza : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorFuerza : 0,
                errorMostrarMsjFuerza : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nomfuerza',
                buscar : ''
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
            listarFuerza(page,buscar,criterio){
                let me=this;
                var url= '/par_fuerza?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayFuerza = respuesta.fuerzas.data;
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
                me.listarFuerza(page,buscar,criterio);
            },
            registrarFuerza(){
                /*
                if (this.validarFuerza()){
                    return;
                }*/
                
                let me = this;

                axios.post('/par_fuerza/registrar',{
                    'nomfuerza': this.nomfuerza,
                    
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
                        me.listarFuerza(1,'','nomfuerza');
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarFuerza(){
                /*
               if (this.validarFuerza()){
                    return;
                }*/
                
                let me = this;

                axios.put('/par_fuerza/actualizar',{
                    'nomfuerza': this.nomfuerza,
                    
                    'idfuerza': this.fuerza_id
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarFuerza(1,'','nomfuerza');
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            desactivarFuerza(idfuerza){
               swal({
                title: 'Esta seguro de desactivar esta Fuerza?',
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

                    axios.put('/par_fuerza/desactivar',{
                        'idfuerza': idfuerza
                    }).then(function (response) {
                        me.listarFuerza(1,'','nomfuerza');
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
            activarFuerza(idfuerza){
               swal({
                title: 'Esta seguro de activar esta Fuerza?',
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

                    axios.put('/par_fuerza/activar',{
                        'idfuerza': idfuerza
                    }).then(function (response) {
                        me.listarFuerza(1,'','nomfuerza');
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
            validarFuerza(){
                this.errorFuerza=0;
                this.errorMostrarMsjFuerza =[];

                if (!this.nomfuerza) this.errorMostrarMsjFuerza.push("El nombre de la Fuerza no puede estar vacío.");

                if (this.errorMostrarMsjFuerza.length) this.errorFuerza = 1;

                return this.errorFuerza;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nomfuerza='';
                
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "fuerza":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Fuerza';
                                this.nomfuerza= '';
                                
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Fuerza';
                                this.tipoAccion=2;
                                this.fuerza_id=data['idfuerza'];
                                this.nomfuerza = data['nomfuerza'];
                                
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarFuerza(1,this.buscar,this.criterio);
        }
    }
</script>

