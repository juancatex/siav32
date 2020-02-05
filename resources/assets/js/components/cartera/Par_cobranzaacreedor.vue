<template>
        <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Cobranza por Acreedor</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Cobranza por Acreedores
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">                                
                                <input type="text" v-model="buscar" @keyup.enter="listarCobranzaAcreedor(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listarCobranzaAcreedor(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
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
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="acreedor in arrayAcreedor" :key="acreedor.id">
                                <td>
                                    
                                    <button type="button" @click="abrirModalCobranza('acreedor','cobranza',acreedor)" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                    </button> &nbsp;
                                                                        
                                </td>
                                <td v-text="acreedor.nomgrado"></td>
                                <td v-text="acreedor.nombre"></td>
                                <td v-text="acreedor.numpapeleta"></td>
                                <td v-text="acreedor.importe1"></td>                                
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
                                    <label class="form-control-label" for="text-input">{{importe1}}</label>
                                </div>
                            </div>

                            <!-- incluimos los prestamos -->
                            <table class="table table-bordered table-striped table-sm">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Prestamo</th>
                                        <th>Interes</th>
                                        <th>I. Diferido</th>
                                        <th>Cargos</th>
                                        <th>Cuota</th>                                       
                                        <th>Elegir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="acreedorDetalle in arrayAcreedorDetalle" :key="acreedorDetalle.id">
                                        <td v-text="acreedorDetalle.no_prestamo"></td>
                                        <td v-text="acreedorDetalle.in"></td>
                                        <td v-text="acreedorDetalle.indi"></td>
                                        <td v-text="acreedorDetalle.car"></td>                                
                                        <td v-text="acreedorDetalle.cut"></td>                                
                                        <td> 
                                            <input type="radio" v-model="eleccion" name="eleccion" :value="acreedorDetalle.idprestamo"> 
                                        </td>
                                    </tr>   You have selected : {{eleccion}}, resto: {{importe-eleccion}}  
                                </tbody>
                            </table>


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
                        <button :disabled = "errors.any()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarCobranza()">Cobrar</button>
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
                importe1 : '',
                detalle : '',
                documento : '',
                fechadevolucion : datehoy,
                dev_acreedor : '',
                idperfilcuentamaestro : '',
                arrayAcreedor : [],
                arrayAcreedorDetalle : [],
                eleccion:'',
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
              var url=dev_acreedor + id;
              repo.viewPDF(url,'Devolucion Acreedor');
            },

            listarCobranzaAcreedor (page,buscar,criterio){
                let me=this;
                var url= '/liq_acreedor/index1?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio + '&tipo=all';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayAcreedor = respuesta.acreedores.data; 
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            listarDetalleCobranzaAcreedor(idsocio, importe){
                let me=this;
                var url= '/liq_acreedor/cobranza?idsocio=' + idsocio + '&importe=' + importe;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayAcreedorDetalle = respuesta.cobranzaacreedordetalle.data;  console.log(me.arrayAcreedorDetalle);
                    me.pagination= respuesta.pagination;
                }). then(function (response) { 
                    if (me.pagination.total===0) {
                        swal("¡No existen prestamos !", "para el socio", "warning").then((result) => {
                            me.cerrarModal();                        
                        })
                    }                    					
                }).  catch(function (error) {
                    console.log(error);
                });
            },

            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarCobranzaAcreedor(page,buscar,criterio);
            },
            
            registrarCobranza(){ 
               let me = this;
                axios.put('/liq_acreedor/cobra',{
                    'idacreedor': this.acreedor_id,
                    'idsocio': this.idsocio,
                    'numpapeleta': this.numpapeleta,
                    'importe': this.importe,
                    'moneda': this.moneda,
                    'detalle': this.detalle,
                    'documento': this.documento,
                    'idprestamo': this.eleccion,
                    'fechadevolucion': this.fechadevolucion,
                    'resto': this.eleccion,                                        
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
                        swal(
                            // response.data,
                            'Se Cobro',
                            'el prestamo',
                            '')
                        me.cerrarModal();
                        me.listarCobranzaAcreedor(1,'','');
                    }
                    
                }).catch(function (error) {
                    console.log(error);
                });
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

            abrirModalCobranza(modelo, accion, data = []){
                
                switch(modelo){
                    case "acreedor":
                    {
                        switch(accion){
                            case 'cobranza':
                            {
                                //buscamos los prestamos del socio                                                                
                                this.listarDetalleCobranzaAcreedor(data['idsocio'],data['importe1']);
                                this.modal = 1;
                                this.tituloModal = 'Cobranza por Acreedor';
                                this.acreedor_id=data['idacreedor']; 
                                this.nombre = data['nombre'];
                                this.idsocio = data['idsocio'];
                                this.moneda = data['moneda'];
                                this.numpapeleta = data['numpapeleta'];
                                this.importe = data['importe'];
                                this.importe1 = data['importe1'];
                                this.idperfilcuentamaestro = '';
                                this.detalle = '';
                                this.documento = '';
                                this.tipoAccion = 1;
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarCobranzaAcreedor(1,this.buscar,this.criterio);
            //this.getRutasReports();
        }
    }
</script>
