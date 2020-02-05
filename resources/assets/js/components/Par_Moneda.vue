<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Registro de Moneda</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Moneda
                        <button type="button" @click="abrirModal('moneda','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Registar Moneda
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nommoneda">Nombre</option>
                                      <option value="codmoneda">Codigo</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarMoneda(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarMoneda(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre Moneda</th>                                    
                                    <th>Codigo Moneda</th>
                                    <th>Tipo de cambio</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="moneda in arrayMoneda" :key="moneda.id">
                                    <td>
                                        <button type="button" @click="abrirModal('moneda','actualizar',moneda)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="moneda.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarMoneda(moneda.idmoneda)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarMoneda(moneda.idmoneda)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="moneda.nommoneda"></td>
                                    <td v-text="moneda.codmoneda"></td>
                                    <td v-text="moneda.tipocambio"></td> 
                                    <td>
                                        <div v-if="moneda.activo">
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
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input v-validate.initial="'required'" 
                                                type="text" 
                                                v-model="nommoneda" 
                                                class="form-control" 
                                                placeholder="Nombre de moneda"
                                                name="Nombre escalafón">
                                    <span class="text-error">{{ errors.first('Nombre escalafón')}}</span>   <!--Lineas Agregadas<-->
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Codigo</label>
                                    <div class="col-md-9">
                                        <input v-validate.initial="'required'" 
                                                type="text" 
                                                v-model="codmoneda" 
                                                class="form-control" 
                                                placeholder="codmoneda"
                                                name="codmoneda">
                                    <span class="text-error">{{ errors.first('codmoneda')}}</span>   <!--Lineas Agregadas<-->
                                    </div>
                                </div>
                                <div class="form-group row">
                                     <label class="col-md-3 form-control-label" for="text-input">Tipo de cambio</label>
                                    <div class="col-md-9">
                                         <div class="input-group">
                                        <input  v-validate.initial= "'required'"   
                                                type="number" 
                                                v-model.number="tipocambio"  
                                                class="form-control"  
                                                placeholder="Tipo de cambio"
                                                name='tipo de cambio'
                                                autofocus
                                                step="any">  
                                       
                                                <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fa fa-money"></i>
                                                </span>
                                                </div>
                                         </div>
                                          <span class="text-error">{{ errors.first('tipo de cambio')}}</span>   <!--Lineas Agregadas<-->
                                    </div>
                                 </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>

                            <button :disabled = "errors.any()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarMoneda()">Guardar</button>
                            <button :disabled = "errors.any()" type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarMoneda()">Actualizar</button>

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
                moneda_id: 0,
                nommoneda : '',
                codmoneda : '',
                arrayMoneda : [],
                modal : 0,
                tipocambio : 1,
                tituloModal : '',
                tipoAccion : 0,
                errorMoneda : 0,
                errorMostrarMsjMoneda : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 10,
                criterio : 'nommoneda',
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
            listarMoneda (page,buscar,criterio){
                let me=this;
                var url= '/par_moneda?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayMoneda = respuesta.monedas.data;
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
                me.listarMoneda(page,buscar,criterio);
            },
            registrarMoneda(){
               let me = this;
                axios.post('/par_moneda/registrar',{
                    'nommoneda': this.nommoneda,
                    'tipocambio':this.tipocambio,
                    'codmoneda': this.codmoneda
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
                        me.listarMoneda(1,'','nommoneda');
                    }
                    
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarMoneda(){
                let me = this;
                axios.put('/par_moneda/actualizar',{
                    'nommoneda': this.nommoneda,
                    'idmoneda': this.moneda_id,
                    'tipocambio':this.tipocambio,
                    'codmoneda': this.codmoneda
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
                        me.listarMoneda(1,'','nommoneda');
                    }
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            desactivarMoneda(id){
               swal({
                title: 'Esta seguro de desactivar este Moneda?',
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

                    axios.put('/par_moneda/desactivar',{
                        'idmoneda': id
                    }).then(function (response) {
                        me.listarMoneda(1,'','nommoneda');
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
            activarMoneda(id){
               swal({
                title: 'Esta seguro de activar este Moneda?',
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

                    axios.put('/par_moneda/activar',{

                         'idmoneda': id
                    }).then(function (response) {
                        me.listarMoneda(1,'','nommoneda');
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
            validarMoneda(){
                this.errorMoneda=0;
                this.errorMostrarMsjMoneda =[];

                if (!this.nommoneda) this.errorMostrarMsjMoneda.push("El nombre del Moneda no puede estar vacío.");
                if (this.errorMostrarMsjMoneda.length) this.errorMoneda = 1;

                return this.errorMoneda;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nommoneda='';

            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "moneda":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Moneda';
                                this.nommoneda= '';
                                this.codmoneda= '';
                                this.tipocambio=1;
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Moneda';
                                this.tipoAccion=2;
                                this.moneda_id=data['idmoneda'];
                                this.tipocambio=data['tipocambio'];
                                this.nommoneda = data['nommoneda'];
                                this.codmoneda = data['codmoneda'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarMoneda(1,this.buscar,this.criterio);
        }
    }
</script>

