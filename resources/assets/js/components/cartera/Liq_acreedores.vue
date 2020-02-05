<template>
        <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Registro Devolucion Acreedor</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Acreedores                    
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">                                
                                <input type="text" v-model="buscar" @keyup.enter="listarAcreedor(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listarAcreedor(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Grado</th>
                                <th>Nombre Completo</th>
                                <th>Num Papeleta</th>
                                <th>Saldo Acreedor</th>
                                <th>Cod. Comprobante</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="acreedor in arrayAcreedor" :key="acreedor.id">
                                <td>{{acreedor.idasientomaestro}}
                                    <template v-if="acreedor.idasientomaestro>0">                                        
                                        <button type="button" @click="reporte_dev(acreedor.idacreedor, dev_acreedor)" class="btn btn-warning btn-sm" title="Reportes">
                                        <i class="icon-book-open"></i>
                                    </button> &nbsp;
                                    </template>
                                    <template v-else>
                                        <button type="button" @click="abrirModalDevolucion('acreedor','devolucion',acreedor)" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                    </button>&nbsp;
                                    </template>
                                    
                                    <!-- <template v-if="acreedor.activo">
                                        <button type="button" class="btn btn-danger btn-sm" @click="desactivarEscalafon(acreedor.idescalafon)">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" class="btn btn-info btn-sm" @click="activarEscalafon(acreedor.idescalafon)">
                                            <i class="icon-check"></i>
                                        </button>
                                    </template> -->
                                </td>
                                <td v-text="acreedor.nomgrado"></td>
                                <td v-text="acreedor.nombre"></td>
                                <td v-text="acreedor.numpapeleta"></td>
                                <td v-text="acreedor.importe1"></td>
                                <td v-text="acreedor.cod_comprobante"></td>
                                <td> 
                                    <div v-if="acreedor.estado_conta===1">
                                        <span class="badge badge-success">Validado Contabilidad</span>
                                    </div>
                                    <div v-else-if="acreedor.estado_conta===0">
                                        <span class="badge badge-warning">Borrador Contabilidad</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-info">No Contabilizado</span>
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
                                    <label class="form-control-label" for="text-input">{{nombre}}</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">No. Papeleta</label>
                                <div class="col-md-9">
                                    <label class="form-control-label" for="text-input">{{numpapeleta}}</label>                                    
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Saldo Acreedor</label>
                                <div class="col-md-9">
                                    <label class="form-control-label" for="text-input">{{importe}}</label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Perfil de Cuenta:</label>
                                <div class="col-md-3">                                                                
                                        <select class="form-control" 
                                            v-model="idperfilcuentamaestro"
                                            v-validate.initial="'required'"
                                            name='perfil'>
                                            <option selected="selected" value="" >...Seleccione</option>
                                            <option v-for="perfil in arrayPerfil" :key="perfil.idperfilcuentamaestro" :value="perfil.idperfilcuentamaestro" v-text="perfil.nomperfil"></option>
                                        </select>
                                </div>                            
                                <span v-if="idperfilcuentamaestro===''" class="text-error">{{errors.first('perfil')}}</span> 
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Detalle</label>
                                <div class="col-md-9">
                                    <input v-validate.initial="'required'" 
                                            type="text" 
                                            v-model="detalle" 
                                            class="form-control" 
                                            placeholder="detalle"
                                            name="detalle">
                                <span class="text-error">{{ errors.first('detalle')}}</span>   <!--Lineas Agregadas<-->
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Documento</label>
                                <div class="col-md-9">
                                    <input v-validate.initial="'required'" 
                                            type="text" 
                                            v-model="documento" 
                                            class="form-control" 
                                            placeholder="documento"
                                            name="documento">
                                <span class="text-error">{{ errors.first('documento')}}</span>   <!--Lineas Agregadas<-->
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Fecha Devolucion</label>
                                <div class="col-md-9">
                                    <label class="form-control-label" for="text-input">{{fechadevolucion}}</label>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button :disabled = "errors.any()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarDevolucion()">Registrar</button>
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
     
    import * as repo from '../../functions.js';
    var hoy = new Date();
    var datehoy=hoy.getDate()+'/'+hoy.getMonth()+1+'/'+hoy.getFullYear();  
    
    export default {
        props: ['idmodulo'],
        data (){
            return {
                acreedor_id: 0,
                nombre : '',
                numpapeleta : '',
                importe : '',
                detalle : '',
                documento : '',
                fechadevolucion : datehoy,
                dev_acreedor : '',
                idperfilcuentamaestro : '',
                arrayAcreedor : [],
                arrayPerfil : [],
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
                criterio : 'nomescalafon',
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

            reporte_dev(id,dev_acreedor){
              var url=dev_acreedor + id;                console.log(url);
              repo.viewPDF(url,'Devolucion Acreedor');
            },

            selectPerfilcuenta(){
                let me=this;
                var url= '/daa_perfilcuenta/selectPerfilcuenta?idmodulo='+ this.idmodulo;
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayPerfil = respuesta.perfilcuenta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            listarAcreedor (page,buscar,criterio){
                let me=this;
                var url= '/liq_acreedor?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayAcreedor = respuesta.acreedores.data; 
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
                me.listarAcreedor(page,buscar,criterio);
            },
            
            registrarDevolucion(){ 
               let me = this;
                axios.put('/liq_acreedor/devolucion',{
                    'idacreedor': this.acreedor_id,
                    'idperfilcuentamaestro': this.idperfilcuentamaestro,
                    'idsocio': this.idsocio,
                    'moneda': this.moneda,
                    'detalle': this.detalle,
                    'documento': this.documento,
                    'fechadevolucion': this.fechadevolucion,
                    'importe': this.importe,
                    'numpapeleta': this.numpapeleta,
                    'idmodulo':this.idmodulo
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
                        me.listarAcreedor(1,'','');
                    }
                    
                }).catch(function (error) {
                    console.log(error);
                });
            },
                       
            desactivarEscalafon(id){
               swal({
                title: 'Esta seguro de desactivar este Escalafon?',
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

                    axios.put('/liq_acreedor/desactivar',{
                        'idescalafon': id
                    }).then(function (response) {
                        me.listarAcreedor(1,'','nomescalafon');
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

            activarEscalafon(id){
               swal({
                title: 'Esta seguro de activar este Escalafon?',
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

                    axios.put('/liq_acreedor/activar',{

                         'idescalafon': id
                    }).then(function (response) {
                        me.listarAcreedor(1,'','nomescalafon');
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
                this.nomescalafon='';
            },

            getRutasReports (){ 
                let me=this;
                var url= '/liq_acreedor/reportesdev';
                axios.get(url).then(function (response) {
                    var respuesta= response.data; ;                    
                    me.dev_acreedor=respuesta.REP_DEV_ACREEDOR; console.log(me.dev_acreedor);

                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            abrirModalDevolucion(modelo, accion, data = []){
                switch(modelo){
                    case "acreedor":
                    {
                        switch(accion){
                            case 'devolucion':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Devolucion de Descuentos';
                                this.acreedor_id=data['idacreedor']; 
                                this.idsocio = data['idsocio'];
                                this.nombre = data['nombre'];
                                this.moneda = data['moneda'];
                                this.numpapeleta = data['numpapeleta'];
                                this.importe = data['importe1'];
                                this.idperfilcuentamaestro = '';
                                this.detalle = '';
                                this.documento = '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                // this.modal=1;
                                // this.tituloModal='Actualizar Escalafon';
                                // this.tipoAccion=2;
                                // this.acreedor_id=data['idescalafon'];
                                // this.nomescalafon = data['nomescalafon'];
                                break;
                            }
                        }
                    }
                }
                this.selectPerfilcuenta();   
            }
        },
        mounted() {
            this.listarAcreedor(1,this.buscar,this.criterio);
            this.getRutasReports();
        }
    }
</script>
