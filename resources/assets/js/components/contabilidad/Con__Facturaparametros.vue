<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Registro Parametros de Factura</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Parametros Factura
                        <button type="button" @click="abrirModal('facturaparametros','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Registar Paramnetros de Factura
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">                                    
                                    <input type="text" v-model="buscar" @keyup.enter="listarFacturaparametros(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarFacturaparametros(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Propietario</th>
                                    <th>Actividad</th>
                                    <th>NIT</th> 
                                    <th>No. Autorizacion</th>                       
                                    <th>Direccion</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="facturaparametros in arrayFacturaParametros" :key="facturaparametros.id">
                                    <td>
                                        <button type="button" @click="abrirModal('facturaparametros','actualizar',facturaparametros)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="facturaparametros.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarFacturaParametros(facturaparametros.idfacturaparametro)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarFacturaParametros(facturaparametros.idfacturaparametro)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="facturaparametros.nombreinstitucion"></td>
                                    <td v-text="facturaparametros.propietario"></td>
                                    <td v-text="facturaparametros.actividad"></td>
                                    <td v-text="facturaparametros.nit"></td>
                                    <td v-text="facturaparametros.numeroautorizacion"></td>
                                    <td v-text="facturaparametros.direccion"></td>
                                    <td>
                                        <div v-if="facturaparametros.activo">
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
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre Institucion:</label>
                                    <div class="col-md-6"> 
                                        <input v-validate.initial="'required'" 
                                                type="text" 
                                                v-model="nombreinstitucion" 
                                                class="form-control" 
                                                placeholder="Nombre de la institucion"
                                                name="nombre institucion">
                                    <span class="text-error">{{ errors.first('nombre institucion')}}</span>   
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Propietario:</label>
                                    <div class="col-md-3">
                                        <input v-validate.initial="'required'" 
                                                type="text" 
                                                v-model="propietario" 
                                                class="form-control" 
                                                placeholder="Propietario"
                                                name="propietario">
                                    <span class="text-error">{{ errors.first('propietario')}}</span>   
                                    </div>                                
                                    <label class="form-control-label" for="text-input">Direccion:</label>
                                    <div class="col-md-3">
                                        <input v-validate.initial="'required'" 
                                                type="text" 
                                                v-model="direccion" 
                                                class="form-control" 
                                                placeholder="Direccion"
                                                name="direccion">
                                    <span class="text-error">{{ errors.first('direccion')}}</span>   
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Telefonos:</label>
                                    <div class="col-md-3">
                                        <input v-validate.initial="'required'" 
                                                type="text" 
                                                v-model="telefono" 
                                                class="form-control" 
                                                placeholder="Telefono"
                                                name="telefono">
                                    <span class="text-error">{{ errors.first('telefono')}}</span>   
                                    </div>
                                
                                    <label class="form-control-label" for="text-input">Ciudad:</label>
                                    <div class="col-md-3">
                                        <input v-validate.initial="'required'" 
                                                type="text" 
                                                v-model="ciudad" 
                                                class="form-control" 
                                                placeholder="Ciudad"
                                                name="ciudad">
                                    <span class="text-error">{{ errors.first('ciudad')}}</span>   
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Actividad:</label>
                                    <div class="col-md-3">
                                        <input v-validate.initial="'required'" 
                                                type="text" 
                                                v-model="actividad" 
                                                class="form-control" 
                                                placeholder="Actividad"
                                                name="actividad">
                                    <span class="text-error">{{ errors.first('actividad')}}</span>   
                                    </div>                                
                                    <label class="form-control-label" for="text-input">NIT:</label>
                                    <div class="col-md-3">
                                        <input v-validate.initial="'required'" 
                                                type="text" 
                                                v-model="nit" 
                                                class="form-control" 
                                                placeholder="Nit"
                                                name="nit">
                                    <span class="text-error">{{ errors.first('nit')}}</span>   
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">No. Autorizacion</label>
                                    <div class="col-md-3">
                                        <input v-validate.initial="'required'" 
                                                type="text" 
                                                v-model="numeroautorizacion" 
                                                class="form-control" 
                                                placeholder="No. Aurtorizacion"
                                                name="numeroautorizacion">
                                    <span class="text-error">{{ errors.first('numeroautorizacion')}}</span>   
                                    </div>
                                    <label class="form-control-label" for="text-input">No. Dosificacion:</label>
                                    <div class="col-md-3">
                                        <input v-validate.initial="'required'" 
                                                type="text" 
                                                v-model="numerodosificacion" 
                                                class="form-control" 
                                                placeholder="numero dosificacion"
                                                name="numerodosificacion">
                                    <span class="text-error">{{ errors.first('numerodosificacion')}}</span>   
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Fecha Limite:</label>
                                    <div class="col-md-3">
                                        <input v-validate.initial="'required'" 
                                                type="date" 
                                                v-model="fechalimite" 
                                                class="form-control" 
                                                placeholder="fecha limite"
                                                name="fechalimite">
                                    <span class="text-error">{{ errors.first('fechalimite')}}</span>   
                                    </div>
                                    <label class="col-md-2 form-control-label" for="text-input">No. Factura Rango 1:</label>
                                    <div class="col-md-1">
                                        <input v-validate.initial="'required'" 
                                                type="text" 
                                                v-model="rangofactura1" 
                                                class="form-control" 
                                                placeholder="rango factura inicial"
                                                name="rangofactura1">
                                    <span class="text-error">{{ errors.first('rangofactura1')}}</span>   
                                    </div>
                                    <label class="col-md-2 form-control-label" for="text-input">No. Factura Rango 2:</label>
                                    <div class="col-md-1">
                                        <input v-validate.initial="'required'" 
                                                type="text" 
                                                v-model="rangofactura2" 
                                                class="form-control" 
                                                placeholder="rango factura final"
                                                name="rangofactura2">
                                    <span class="text-error">{{ errors.first('rangofactura2')}}</span>   
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Texto 1</label>
                                    <div class="col-md-9">
                                        <input v-validate.initial="'required'" 
                                                type="text" 
                                                v-model="texto1" 
                                                class="form-control" 
                                                placeholder="Texto 1"
                                                name="texto1">
                                    <span class="text-error">{{ errors.first('texto1')}}</span>   
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Texto 2</label>
                                    <div class="col-md-9">
                                        <input v-validate.initial="'required'" 
                                                type="text" 
                                                v-model="texto2" 
                                                class="form-control" 
                                                placeholder="Texto 2"
                                                name="texto2">
                                    <span class="text-error">{{ errors.first('texto2')}}</span>   
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Llave (Impuestos)</label>
                                    <div class="col-md-9">
                                        <input v-validate.initial="'required'" 
                                                type="text" 
                                                v-model="llave" 
                                                class="form-control" 
                                                placeholder="llave"
                                                name="llave">
                                    <span class="text-error">{{ errors.first('llave')}}</span>   
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>

                            <button :disabled = "errors.any()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarFacturaParametros()">Guardar</button>
                            <button :disabled = "errors.any()" type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarFacturaParametros()">Actualizar</button>

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
                facturaparametro_id: 0,
                nombreinstitucion : '',
                propietario:'',
                direccion:'',
                telefono:'',
                ciudad:'',
                actividad:'',
                nit:'',
                numeroautorizacion:'',
                numerodosificacion:'',
                rangofactura1:'',
                rangofactura2:'',
                fechalimite:'',
                texto1:'',
                texto2:'',
                llave:'',
                arrayFacturaParametros : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorFacturaParametro : 0,
                errorMostrarMsjFacturaParametro : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 10,
                criterio : 'nombreinstitucion',
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
            listarFacturaparametros (page,buscar,criterio){
                let me=this;
                var url= '/con_facturaparametro?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayFacturaParametros = respuesta.facturaparametro.data;
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
                me.listarFacturaparametros(page,buscar,criterio);
            },
            
            registrarFacturaParametros(){
               let me = this;
                axios.post('/con_facturaparametro/registrar',{
                    'nombreinstitucion': this.nombreinstitucion,
                    'propietario': this.propietario,
                    'direccion': this.direccion,
                    'telefono': this.telefono,
                    'ciudad': this.ciudad,
                    'actividad': this.actividad,
                    'nit': this.nit,
                    'numeroautorizacion': this.numeroautorizacion,
                    'numerodosificacion': this.numerodosificacion,
                    'rangofactura1': this.rangofactura1,
                    'rangofactura2': this.rangofactura2,
                    'fechalimite': this.fechalimite,
                    'texto1': this.texto1,
                    'texto2': this.texto2,
                    'llave': this.llave
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
                        me.listarFacturaparametros(1,'','nombreinstitucion');
                    }
                    
                }).catch(function (error) {
                    console.log(error);
                });
            },

            actualizarFacturaParametros(){  
                let me = this;
                axios.put('/con_facturaparametro/actualizar',{
                    'nombreinstitucion': this.nombreinstitucion,
                    'idfacturaparametro': this.facturaparametro_id, 
                    'propietario': this.propietario,
                    'direccion': this.direccion,
                    'telefono': this.telefono,
                    'ciudad': this.ciudad,
                    'actividad': this.actividad,
                    'nit': this.nit,
                    'numeroautorizacion': this.numeroautorizacion,
                    'numerodosificacion': this.numerodosificacion,
                    'rangofactura1': this.rangofactura1,
                    'rangofactura2': this.rangofactura2,
                    'fechalimite': this.fechalimite,
                    'texto1': this.texto1,
                    'texto2': this.texto2,
                    'llave': this.llave
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
                        me.listarFacturaparametros(1,'','nombreinstitucion');
                    }
                }).catch(function (error) {
                    console.log(error);
                }); 
            },

            desactivarFacturaParametros(id){
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

                    axios.put('/con_facturaparametro/desactivar',{
                        'idfacturaparametro': id
                    }).then(function (response) {
                        me.listarFacturaparametros(1,'','nombreinstitucion');
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
            
            activarFacturaParametros(id){
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

                    axios.put('/con_facturaparametro/activar',{

                         'idfacturaparametro': id
                    }).then(function (response) {
                        me.listarFacturaparametros(1,'','nombreinstitucion');
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
                this.nombreinstitucion='';

            },

            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "facturaparametros":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Factura Parametros';
                                this.nombreinstitucion= '';
                                this.propietario= '';
                                this.direccion= '';
                                this.telefono= '';
                                this.ciudad= '';
                                this.actividad= '';
                                this.nit= '';
                                this.numeroautorizacion= '';
                                this.numerodosificacion= '';
                                this.rangofactura1= '';
                                this.rangofactura2= '';
                                this.fechalimite= '';
                                this.texto1= '';
                                this.texto2= '';                                
                                this.llave= '';                                
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Factura Parametros';
                                this.tipoAccion=2;
                                this.facturaparametro_id=data['idfacturaparametro'];
                                this.nombreinstitucion = data['nombreinstitucion'];
                                this.propietario = data['propietario'];
                                this.direccion = data['direccion'];
                                this.telefono = data['telefono'];
                                this.ciudad = data['ciudad'];
                                this.actividad = data['actividad'];
                                this.nit = data['nit'];
                                this.numeroautorizacion = data['numeroautorizacion'];
                                this.numerodosificacion = data['numerodosificacion'];
                                this.rangofactura1 = data['rangofactura1'];
                                this.rangofactura2 = data['rangofactura2'];
                                this.fechalimite = data['fechalimite'];
                                this.texto1 = data['texto1'];
                                this.texto2 = data['texto2'];
                                this.llave = data['llave'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarFacturaparametros(1,this.buscar,this.criterio);
        }
    }
</script>
