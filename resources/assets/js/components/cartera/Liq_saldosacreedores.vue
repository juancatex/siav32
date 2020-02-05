<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-nomtipodevolucion"><a href="/">Liq. Saldos Acreedores </a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i>Liq. Saldos Acreedores           
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">                                                            
                                    <button type="submit" @click="abrirModal('liquidaracreedores','registrar')" class="btn btn-primary"><i class="fa fa-search"></i>Procesar</button>
                                </div>
                            </div>
                        </div>                        
                    </div>
                </div>
                <label class="col-md-9 form-control-label">Datos Procesados</label>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Fecha Proceso</th>                               
                            <th>Comprobante</th>                                                                
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="procesados in arrayProcesadosacreedor" :key="procesados.id">
                            <td>
                                <button type="button" @click="reporte_liq(procesados.fechaproceso,dev_liqsaldo)" class="btn btn-warning btn-sm" title="Reportes">
                                    <i class="icon-book-open"></i>
                                </button>&nbsp;
                            </td>
                            <td>{{procesados.fechaproceso}}</td>
                            <td>{{procesados.cod_comprobante}}</td>
                            <td> 
                                <div v-if="procesados.estado===1">
                                    <span class="badge badge-success">Validado Contabilidad</span>
                                </div>
                                <div v-else-if="procesados.estado===0">
                                    <span class="badge badge-warning">Borrador Contabilidad</span>
                                </div>
                                <div v-else>
                                    <span class="badge badge-info">No Contabilizado</span>
                                </div>                                    
                            </td>
                        </tr>                                
                    </tbody>
                </table>
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
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de Proceso:</label>
                                    <div class="col-md-3">
                                        <input v-validate.initial="'required'" 
                                                type="date" 
                                                v-model="fechaproceso" 
                                                class="form-control" 
                                                placeholder="Fecha de Proceso"
                                                name="fechaproceso">
                                    <span class="text-error">{{ errors.first('fechaproceso')}}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Saldo Menor Bs:</label>
                                    <div class="col-md-3">
                                        <input v-validate.initial="'required'" 
                                                type="number" 
                                                v-model="saldomenoracreedor" 
                                                class="form-control" 
                                                placeholder="Saldo Menor"
                                                name="saldomenoracreedor">
                                    <span class="text-error">{{ errors.first('saldomenoracreedor')}}</span>
                                    </div>                                    
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nro. Documento:</label>
                                    <div class="col-md-3">
                                        <input v-validate.initial="'required'" 
                                                type="text" 
                                                v-model="nro_documento" 
                                                class="form-control" 
                                                placeholder="No. Documento"
                                                name="nro_documento">
                                    <span class="text-error">{{ errors.first('nro_documento')}}</span>
                                    </div>                                    
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Glosa:</label>
                                    <div class="col-md-9">
                                        <input v-validate.initial="'required'" 
                                                type="text" 
                                                v-model="observacion" 
                                                class="form-control" 
                                                placeholder="Observacion"
                                                name="observacion">
                                    <span class="text-error">{{ errors.first('observacion')}}</span>
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
                            </form>
                            <table class="table table-bordered table-striped table-sm">
                            <thead class="thead-dark">
                                <tr>                                    
                                    <th>No. Papeleta</th>
                                    <th>Nombre</th>
                                    <th>Saldo</th>
                                    <th>Moneda</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="saldos in arraySaldosmenoresacreedor" :key="saldos.id">                                                                                                            
                                    <td v-text="saldos.numpapeleta"></td>
                                    <td v-text="saldos.nombre"></td>
                                    <td v-text="saldos.importe"></td>
                                    <td v-text="saldos.nommoneda"></td>
                                </tr>
                            </tbody>
                        </table>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button  v-if="tipoAccion==2 && arraySaldosmenoresacreedor!=''" :disabled = "errors.any()" type="button" class="btn btn-warning" @click="liquidaresaldosacreedor()">Liquidar</button>
                            <button :disabled = "errors.any()" type="button" class="btn btn-primary" @click="procesarsaldosacreedor(saldomenoracreedor)">Procesar</button>

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
    export default {
        props: ['idmodulo'],
        data (){
            return {
                nro_documento: '',                
                arrayPerfil : [],
                arraySaldosmenoresacreedor: [],
                arrayProcesadosacreedor: [],
                valido: 0,
                idperfilcuentamaestro:'',
                fechaproceso: '',
                saldomenoracreedor: '',
                observacion : '',
                modal : 0,
                dev_liqsaldo : '',
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

            procesarsaldosacreedor(saldomenoracreedor) {
                let me=this;
                var url= '/liq_acreedor/procesarsaldosacreedor?saldomenoracreedor='+ saldomenoracreedor;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arraySaldosmenoresacreedor = respuesta.saldosmenoresacreedor; console.log(me.arraySaldosmenoresacreedor);
                    me.tipoAccion=2;
                }).then(function (response) { 
                    if (me.arraySaldosmenoresacreedor.length===0) {
                        swal("¡No hay datos!", "", "success").then((result) => {
                            me.cerrarModal();                        
                        })
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            },

            liquidaresaldosacreedor() { 
                let me = this;
                axios.post('/liq_acreedor/liquidaracreedores',{                            
                     'fechaproceso': this.fechaproceso,   
                     'saldomenoracreedor':this.saldomenoracreedor,                      
                     'idperfilcuentamaestro':this.idperfilcuentamaestro,
                     'detalle':this.observacion, 
                     'documento':this.nro_documento,
                     'datos':this.arraySaldosmenoresacreedor,                                          
                     'idmodulo':this.idmodulo
                }).then(function (response) { 
					swal("¡Se liquidaron los datos correctamente!", "", "success").then((result) => {                        
                        me.reporte_liq(me.fechaproceso,me.dev_liqsaldo)
                        me.cerrarModal();    
                        me.listarProcesadosacreedor(1,'','');                    
                      })  
                }).catch(function (error) {
                    console.log(error); 
					 swal("¡Error al registrar los datos!", error.message, "error");
                });
            },


            reporte_liq(fecha,dev_liqsaldo){ console.log(dev_liqsaldo);
              var url=dev_liqsaldo + fecha;
              repo.viewPDF(url,'Liq. Saldos Menores Acreedor');
            }, 

            getRutasReports (){ 
                let me=this;
                var url= '/liq_acreedor/reportes';
                axios.get(url).then(function (response) {
                    var respuesta= response.data; ;
                    me.dev_liqsaldo=respuesta.LIQ_SALDOS_ACREEDOR; 

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
                me.listarSaldosmenores(page,buscar,criterio);
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
                        me.listarSaldosmenores(1,'','idconfig');
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
                        me.listarSaldosmenores(1,'','idconfig');
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
                this.idconfig='';

            },

            listarProcesadosacreedor (page,buscar,criterio){
                let me=this;
                var url= '/liq_acreedor/lista?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayProcesadosacreedor = respuesta.saldosprocesadosacreedor.data; 
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },


            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "liquidaracreedores":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal='Proceso Saldo Menores Acreedor';
                                this.idperfilcuentamaestro='';                                
                                this.fechaproceso='2019-01-01';
                                this.saldomenoracreedor='10';
                                this.nro_documento='10-123';
                                this.observacion='xzz';
                                this.tipoAccion = 0;
                                this.arraySaldosmenoresacreedor = [];
                                break;
                            }
                        }
                    }
                }
                this.selectPerfilcuenta();   
            }
        },
        mounted() {
            this.getRutasReports();
            this.listarProcesadosacreedor(1,'','');
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
