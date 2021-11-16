<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Registro de Factura</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <!-- desabilitado el boton registrar factura que habre el modal, ahora se realiza el registro en linea -->
                                <!-- <i class="fa fa-align-justify"></i> Factura   
                                <button type="button" @click="abrirModal('factura','registrar')" class="btn btn-secondary">
                                    <i class="icon-plus"></i>&nbsp;Registrar Factura
                                </button> -->
                                <!-- <div class="form-group row" style="margin-bottom: 0px;">
                                    <div class="col-md-4">
                                        <strong class="form-control-label">Fecha Factura:</strong>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="date" 
                                        v-model="fechafactura"
                                        class="form-control formu-entrada" 
                                        :max="fechafinal"
                                        :min="fechainicial"
                                        v-validate.initial="'required'"
                                        name="Fechar Factura">
                                        <span class="text-error">{{ errors.first('Fecha Factura')}}</span>
                                   
                                    </div>
                                </div> -->
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row" style="margin-bottom: 0px;">
                                    <div class="col-md-5">
                                        <strong class="form-control-label">Num Autorizacion:</strong>
                                    </div>
                                    <div class="col-md-7">
                                    <input class="form-control " style="text-align:right" type="number" v-model="nunumautorizacion" >
                                    </div>
                                </div>
                            </div>

                        </div>
                        
                    </div>
                    <div class="card-body">
                       <!--  <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">                                    
                                    <input type="text" v-model="buscar" @keyup.enter="listarFactura(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarFactura(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div> -->
                         <div class="form-group row">
                        <div class="col-md-3">
                            <div class="input-group">
                            <strong class="col-md-4 form-control-label" style="margin-bottom: 0px;margin-top: 8px;" >Mes:</strong>
                            <select v-model="messelected"  class="form-control" @change="listarFactura(1,buscar,messelected,anioselected,filialselected)">
                                    <!--TODO: MODIFICAR EL MES ARRAY Y EL VALOR DEL ARRAY PARA ENVIAR MES COMO NUMERO-->
                                    <option v-for="mesarray in arraymes" v-bind:key="mesarray.value" :value="mesarray.value" v-text="mesarray.text"></option>
                                </select>   
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <strong class="col-md-4 form-control-label" style="margin-bottom: 0px;margin-top: 8px;">Año:</strong>
                                <!-- <input  type="text" class="form-control" v-model="anio">    -->
                                <select v-model="anioselected" class="form-control">
                                    <option :value="anio" v-text="anio" selected></option>
                                    <option :value="anio-1" v-text="anio-1"></option>
                                </select>
                            </div>
                                <!--<span>seleccionado: {{ anio }}</span> -->
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                               <input class="form-control" type="text" v-model="buscar" placeholder="Buscar"  @keyup.enter="listarFactura(1,buscar,messelected,anioselected,filialselected)" v-on:focus="selectAll">
                                    <button class="btn btn-primary" type="button" @click="listarFactura(1,buscar,messelected,anioselected,filialselected)">
                                            <i class="fa fa-search"></i>
                                    </button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <button type="button" @click="reportelibroventas('pdf')" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Imprimir PDF">
                                    <i class="fa fa-file-pdf"></i>
                                    
                                </button>
                                &nbsp;
                                <button type="button" @click="reportelibroventas('xls')" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Descargar XLS">
                                    <!-- <i class="cui-xls"></i> -->
                                    <i class="fa fa-file-excel"></i>
                                    
                                </button>
                                &nbsp;
                                <template v-if="cerrado" >
                                    <button type="button" class="btn btn-danger" disabled data-toggle="tooltip" data-placement="top" title="Mes Cerrado">
                                        <i class="cui-lock-locked"></i>
                                    </button>
                                </template>
                                <template v-else>
                                    <button type="button" @click="CerrarmesLibroVentas()" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Cerrar Mes">
                                        <i class="cui-lock-unlocked"></i>
                                    </button>
                                </template>
                                    
                                
                            </div>
                        </div>
                    </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th style="width:15%">Opciones</th>
                                    <th style="width:8%">No. Factura</th>
                                    <th style="width:5%">Fecha</th>
                                    <th style="width:17%">Nombre / Razon Social</th>
                                    <th style="width:12%">NIT</th>                                    
                                    <th>Detalle</th>                                    
                                    <th style="width:10%">Importe</th>
                                    <th style="width:10%">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="cerrado==0">
                                    <td>
                                        Descuento? &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="checkbox" class="form-check-input"  v-model="checkdescuento" @change="modaldescuento()">
                                        <label >{{nomsocio}}</label>
                                       
                                    </td>
                                    <td><vue-numeric  
                                                class="form-control " style="text-align:right;"
                                                separator="," 
                                                v-model="nufactura"
                                                v-bind:precision="0"
                                                @blur="verficarnumfactura">
                                            </vue-numeric>
                                            <span v-if="duplicado" class="text-error">El Numero de Factura ya exite!!</span>
                                        
                                        
                                        <!-- <input class="form-control " style="text-align:right" type="number" v-model="nufactura" v-on:focus="selectAll" @blur="verficarnumfactura"> --></td>
                                    <td> 
                                        <input type="date" 
                                        v-model="fechafactura"
                                        class="form-control" 
                                        :max="fechafinal"
                                        :min="fechainicial"
                                        v-validate.initial="'required'"
                                        name="Fechar Factura">
                                        <span class="text-error">{{ errors.first('Fecha Factura')}}</span>
                                   
                                    </td>
                                    <td><input class="form-control " type="text" v-model="nurazonsocial"  ></td>
                                    <td><input class="form-control " style="text-align:right" type="number" v-model="nunit" v-on:focus="selectAll"></td>
                                    <td><input class="form-control " type="text" v-model="nudetalle" ></td>
                                    <td><input class="form-control " style="text-align:right" type="number" v-model="nuimporte"   > <!-- @keyup.enter="registrarFactura()" --></td>
                                    <td style="text-align:center"><button class="btn btn-primary"  type="button" @click="registrarFactura()" data-toggle="tooltip" data-placement="top" title="Registrar Factura" :disabled="!iscompletefactura">
                                            <i class="fa fa-plus"></i>
                                    </button></td>
                                    

                                </tr>
                                <tr v-for="factura in arrayFactura" :key="factura.id">
                                    <td>
                                        <template v-if="factura.activo>=1 "> <!-- && factura.validadoconta==0 -->
                                            <button v-if="factura.activo==1" type="button" @click="abrirModal('factura','actualizar',factura)" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Editar Factura">
                                                <i class="icon-pencil"></i>
                                            </button> 
                                        
                                           
                                        </template>
                                        <template v-if="factura.activo>=1  && factura.validadoconta==0 "> <!---->
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarFactura(factura.idfactura,false)" data-toggle="tooltip" data-placement="top" title="Eliminar Factura">  <!-- quitar esta opcion cuando ya sean facturas electronicas -->
                                                <i class="icon-trash"></i>
                                            </button> 
                                            <button v-if="factura.activo==1" type="button" class="btn btn-info btn-sm" @click="desactivarFactura(factura.idfactura,true)" data-toggle="tooltip" data-placement="top" title="Anular Factura">
                                                <i class="icon-exclamation"></i>
                                            </button> 
                                        </template>

                                        <button v-if="factura.activo!=2" type="button" @click="ver_factura(factura)" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Ver Factura">
                                          <i class="icon-eye"></i>
                                        </button> 
                                    </td>
                                    <td style="text-align:right" v-text="factura.numerofactura"></td>
                                    <td style="text-align:center" v-text="factura.fecha"></td>
                                    <td>{{factura.razonsocial}} <br /><b v-if="factura.nombres!=null"> Descuento: {{ factura.nombres}} </b>
                                        </td>
                                    <td style="text-align:right" v-text="factura.nit"></td>
                                    <td v-text="factura.detalle"> </td>
                                    <td style="text-align:right" v-text="factura.importetotal + ' Bs.'"></td>                                    
                                    <td>
                                        <span v-if="factura.activo==1 && factura.validadoconta==0" class="badge badge-warning">Sin Comprobante</span>
                                        
                                        <span v-if="factura.activo==1 && factura.validadoconta!=0 && factura.cod_comprobante==null" class="badge badge-success">Comprobante <br />Borrador</span>
                                        <div v-if="factura.activo==1 && factura.validadoconta!=0 && factura.cod_comprobante!=null">
                                            <span  class="badge badge-success">Comprobante</span><span class="badge badge-info"> {{ factura.cod_comprobante}}</span>
                                        </div>
                                        <span v-if="factura.activo==2" class="badge badge-danger">Anulada</span>
                                        <span v-if="factura.nombres!=null" class="badge badge-info">Descuento</span>
                                        
                                        
                                    </td>
                                </tr>                                
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" role="dialog" id="editarfactura" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content animated fadeIn">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">No. Factura:</label>
                                    <p><span v-if="duplicado" class="text-error">El Numero de Factura ya exite!!</span></p>
                                    <div class="col-md-3"> 
                                        <input  v-validate.initial="'required'" 
                                                type="text" 
                                                v-model="nufactura" 
                                                name="numerofactura"
                                                class="form-control"
                                                @blur="verficarnumfactura" 
                                                :disabled="validadoconta>0">  
                                    </div>
                                    
                                    <div class="col-md-6">
                                         <input type="date" 
                                        v-model="fechafactura"
                                        class="form-control" 
                                        :max="fechafinal"
                                        :min="fechainicial"
                                        v-validate.initial="'required'"
                                        name="Fechar Factura"
                                        :disabled="validadoconta>0">
                                        <span class="text-error">{{ errors.first('Fecha Factura')}}</span>

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">NIT:</label>
                                    <div class="col-md-3">
                                        <input v-validate.initial="'required'" 
                                                type="number" 
                                                v-model="nunit" 
                                                class="form-control" 
                                                placeholder="nit"
                                                name="nit">
                                    <span class="text-error">{{ errors.first('nit')}}</span>   
                                    </div>
                                    <label class="col-md-3 form-control-label" for="text-input">Razon Social / Nombre:</label>
                                    <div class="col-md-3">
                                        <input v-validate.initial="'required'" 
                                                type="text" 
                                                v-model="nurazonsocial" 
                                                class="form-control" 
                                                placeholder="razon social"
                                                name="razonsocial">
                                    <span class="text-error">{{ errors.first('razonsocial')}}</span>   
                                    </div>                                                                                                                                                                                                            
                                </div>
                                <!-- parte del detalle  de la factura -->
                                <!-- <div class="form-group row"> -->
                                    <label class="col-md-3 form-control-label" for="text-input">Detalle:</label>
                                    <table class="table table-sm">
                                         <thead class="thead-dark">
                                            <tr>
                                                <th>Detalle</th>
                                                <th>Total Bs.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input class="form-control" type="text" v-model="nudetalle" />
                                                </td>
                                                <td>
                                                    <input class="form-control text-right" type="number" min="0" step=".01" v-model="nuimporte" :disabled="validadoconta>0" >
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <!-- </div> -->
                                
                                <div class="form-group row col-md-3 ">
                               <!--  <button type='button' class="btn btn-info" @click="addNewRow">
                                    <i class="fas fa-plus-circle"></i>
                                    Nueva Fila
                                </button> -->
                                </div>

                                <!-- <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Importe Total Bs.:</label>
                                    <div class="col-md-3">
                                        <input readonly class="form-control text-right" type="number" min="0" step=".01" v-model="invoice_subtotal" />
                                    <span class="text-error">{{ errors.first('importetotal')}}</span>   
                                    </div>                                
                                    <label class="form-control-label" for="text-input">Codigo Control:</label>
                                    <div class="col-md-3">
                                        <input v-validate.initial="'required'" readonly class="form-control text-right" type="text" name="codigocontrol" v-model="codigocontrol" />
                                    </div>
                                </div>  -->                               
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>

                            <button :disabled = "errors.any()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarFactura()">Guardar</button>
                            <button :disabled = "errors.any()" type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarFactura()">Actualizar</button>

                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
            <!-- MODAL descuentos Libro  de VENTAS --> 
            <div class="modal fade " tabindex="-1"  role="dialog"   aria-hidden="true" id="descuentos"  data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="padding-bottom: 5px;padding-top: 5px;">
                            <h4 class="modal-title">Seleccionar Socio Para Descuento</h4>
                        </div>
                        <div class="modal-body">
                            <div>
                                 <Ajaxselect  v-if="clearSelected"
                                    ruta="/rrh_empleado/selectsocios?buscar=" @found="empleados" @cleaning="cleanempleados"
                                    resp_ruta="empleados"
                                    labels="nombres"
                                    placeholder="Ingrese Texto..." 
                                    idtabla="idsocio"
                                    :id="idempleadoselected"
                                    :clearable='true'>
                                </Ajaxselect>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <div>
                                <button type="button"  class="btn btn-secondary"  @click="cerrardescuento()">Cerrar</button>
                                <button type="button"  class="btn btn-success"  v-if="idempleado.length>0" @click="registrardescuento()">Registrar Descuento</button>
                            </div>
                        </div>
                    </div>                    
                </div>                
            </div> 
            <!-- FIN MODAL LIBRO VENTAS -->
        </main>
</template>


<script>        
    // import * as pdf from '../../pdf.js';
    import * as factura from '../../factura.js';
    import * as plugin from '../../functions.js';

    export default {
        data (){
            return {
                validadoconta:0,
                factura_id: 0,
                numerofactura : '',
                codigocontrol:'',
                razonsocial:'',                
                nit:'',
                reporte_libro_ventas:'',

                invoice_subtotal: 0,
                invoice_total: 0,
                invoice_tax: 5,
                
                invoice_products: [{
                    product_no: '',
                    product_name: '',
                    product_price: '',
                    product_qty: '',
                    line_total: 0
                }],
                

                detalle:'',
                importeparcial:'',
                importetotal:'',
                importecf:'',
                arrayFactura : [],
                arrayFacturaParametro:[],
                maxfacturavalor:'',
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorFactura : 0,
                errorMostrarMsjFactura : [],
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
                buscar : '',
                arrayLibroCuentas:[],
                resLibroventas:[],
                lv:0,
                porcentajelv:0,
                it:0,
                porcentajeit:0,
                itx:0,
                messelected:'',
                mes:'',
                anio:'',
                anioselected:'',
                fechaactual:'',
                fechafactura:'',
                arraymes:[{text:"Enero",value:1},
                          {text:"Febrero",value:2},
                          {text:"Marzo",value:3},
                          {text:"Abril",value:4},
                          {text:"Mayo",value:5},
                          {text:"Junio",value:6},
                          {text:"Julio",value:7},
                          {text:"Agosto",value:8},
                          {text:"Septiembre",value:9},
                          {text:"Octubre",value:10},
                          {text:"Noviembre",value:11},
                          {text:"Diciembre",value:12}
                        ],
                
                
                cerrado:0,
                filialselected:'',

                ///////////////////////// nuevos valores para registro en linea de facturas sin generar codigo de control 
                nufactura:0,
                nurazonsocial:'',
                nunit:0,
                nuimporte:0,
                nudetalle:'HOSPEDAJE',
                nunumautorizacion:'',
                fechainicial:'',
                fechafinal:'',
                duplicado:false,
                nufechafactura:'',
                //////////////////////////////////////////////////////////////////////////////////////////////////////////
                clearSelected:0,
                nomsocio:'',
                checkdescuento:0,
                idempleadoselected:'',
                idempleado:[],




            }
        },
       
        formOptions: {
            validateAfterLoad: true,
            validateAfterChanged: true
          },  

        computed:{
            sidescuento(){
                if(this.idempleado.length>0)
                    return true;
                else
                    return false;
            },
            iscompletefactura(){
                let me=this;
                let valor=0;
                if(me.checkdescuento==1)
                {
                    if(me.nufactura!=0 && me.nurazonsocial!=0 && me.nunit!=0 && me.nudetalle!='' && me.nuimporte!=0 && me.nunumautorizacion!='' && me.duplicado==false)
                        return true;
                    else   
                        return false;
                }
                else
                {
                    if(me.nufactura!=0 && me.nurazonsocial!=0 && me.nudetalle!='' && me.nuimporte!=0 && me.nunumautorizacion!='' && me.duplicado==false)
                        return true;
                    else   
                        return false;
                }
            },
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
             empleados(empleados){
                this.idempleado=[];
                for (const key in empleados) {
                    if (empleados.hasOwnProperty(key)) {
                        const element = empleados[key];
                        this.idempleado.push(element);
                    }
                }
            },
            tiempo(){
                this.clearSelected=1;
            },
            modaldescuento(){
                let me=this;
                if (me.checkdescuento)
                {
                    me.clearSelected=0;
                    setTimeout(this.tiempo, 200); 
                    me.classModal.openModal('descuentos');
                }
                else
                {
                    me.idempleado=[];
                    me.nomsocio='';
                    me.nurazonsocial='';
                    me.nunit='';
                }
                
                //me.classModal.closeModal('librocompras'); 
            },
            cerrardescuento(){
                let me=this;
                if(me.idempleado.length==0)
                    me.checkdescuento=0
                me.classModal.closeModal('descuentos');
            },
            registrardescuento(){
                let me=this;
                let res=[];
                me.nomsocio=me.idempleado[2];
                res=me.nomsocio.split(' ');
                me.nurazonsocial=res[2];
                me.nunit=me.idempleado[3];
                me.cerrardescuento();


            },
            cleanempleados(){
                this.idempleado=[];
            },
            CerrarmesLibroVentas(){
                 swal({
                    title: 'Esta seguro de Cerrar el Mes: '+ this.arraymes[this.messelected-1]+' / '+this.anioselected, 
                    text:'Ya no se Podran Ingresar mas Facturas',
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
                        axios.post('/con_factura/cerrarmes',{
                            'mes': me.messelected,
                            'anio':me.anioselected,
                        }).then(function (response) {
                            me.listarFactura(1,me.buscar,me.messelected,me.anioselected,me.filialselected);
                            //console.log(1,me.criterio,me.borradorcheck);
                            swal(
                            'Mes Cerrado Correctamente!',
                            'Ya no se podran ingresar mas facturas',
                            'success'
                            )
                        }).catch(function (error) {
                            console.log(error);
                        });
                    } 
                    else if (result.dismiss === swal.DismissReason.cancel)
                    {
                        
                    }
                    })
            },
             selectAll: function (event) {
      
                setTimeout(function () {
                    event.target.select()
                }, 0)
            },
            fechahoy(){
                let me=this;
                var hoy = new Date();
                var dd = hoy.getDate(); 
                
                var mm = hoy.getMonth();
                var aa = hoy.getFullYear();
                mm=mm+1;
                if(mm<10) 
                    mm='0'+mm;
                
                if(dd<10)
                    dd='0'+dd; 
               
                //console.log(dd,mm,aa);
                me.mes=mm;
                //console.log(me.mes);
                me.anio=aa;
                me.fechaactual=aa+'-'+mm+'-'+dd;
                me.fechafactura=me.fechaactual;
                me.messelected=me.arraymes[me.mes-1].value;
                //console.log(me.messelected);
                me.anioselected=me.anio;

            },
             diaminmax(){
                var primerDiaMes = new Date(this.anioselected, this.messelected - 1  , 1);
                var ultimoDiaMes = new Date(this.anioselected, this.messelected , 0);
                /* console.log(this.anioselected+ ' aniop');
                console.log(this.messelected + 'mes'); */

                var mm=this.messelected;
                //console.log(mm);
                
                
                if(mm<10) 
                    mm='0'+mm;

                var dd=primerDiaMes.getDate()
                if(dd<10)
                    dd='0'+dd; 
                //console.log(this.cerrado);
                this.fechainicial=this.anioselected+'-'+mm+'-'+dd;
                this.fechafinal=this.anioselected+'-'+mm+'-'+ultimoDiaMes.getDate();
                if(this.fechafinal > this.fechaactual)
                {
                    this.fechafinal=this.fechaactual;
                    //this.cerrado=1;
                }   
                if(this.messelected!=this.mes)
                    this.fechafactura=this.fechainicial;
                else
                    this.fechafactura=this.fechaactual;
                /* else
                    this.cerrado=0; */
                    

               
                /* console.log(this.fechainicial);
                console.log(this.fechafinal);
                 */
                
            },

            ver_factura(ft) {
                this.invoice_products = [];
                //console.log(factura);
                var detalle_fin = [];
                
                var aux1 = ft.detalle.split(',');

                for (var i=0; i<aux1.length; i++) {    
                    var aux2 = aux1[i].split('|');
                    detalle_fin.push({
                    product_name: aux2[0],
                    product_price: aux2[1],
                    product_qty: aux2[2],
                    line_total: aux2[3],
                    });
                }
                 _pdf.imgToBase64('img/iconad.png', function (base) {
                  _pdf.openRecibo(ft.numerofactura,ft.codigocontrol,ft.razonsocial,ft.nit,detalle_fin,ft.importetotal,base); 
                });
            },

            calculateTotal() {
                var subtotal, total;
                subtotal = this.invoice_products.reduce(function (sum, product) {
                    var lineTotal = parseFloat(product.line_total);
                    if (!isNaN(lineTotal)) {
                        return sum + lineTotal;
                    }
                }, 0);

                this.invoice_subtotal = subtotal.toFixed(2);

                total = (subtotal * (this.invoice_tax / 100)) + subtotal;
                total = parseFloat(total);
                if (!isNaN(total)) {
                    this.invoice_total = total.toFixed(2);
                } else {
                    this.invoice_total = '0.00'
                }

                //para el codigo de control
                var hoy = new Date();
                var datehoy=hoy.getFullYear()+''+hoy.getMonth()+1+''+hoy.getDate();  
                if(this.invoice_subtotal!=0) {
                    this.codigocontrol=_code.genCode(
                        this.arrayFacturaParametro[0].numeroautorizacion,//Numero de autorizacion
                        //this.numerofactura,//Numero de factura
                        '934509',
                        this.nit,//Número de Identificación Tributaria o Carnet de Identidad
                        '20080825',//fecha de transaccion
                        this.invoice_subtotal,//Monto de la factura
                        this.arrayFacturaParametro[0].llave//Llave de dosificación 
                    );
                }
                else 
                    this.codigocontrol='';
                
                // console.log('Cod control:',_code.genCode(
                //     '7904005357862',//Numero de autorizacion
                //     '297141',//Numero de factura
                //     '331073014',//Número de Identificación Tributaria o Carnet de Identidad
                //     '20071018',//fecha de transaccion
                //     '39375',//Monto de la factura
                //     '_(Ia6BU#Q_*Qc{T4P}s{QrhF+%cF-Qrjtviv]TIxgImcnKWX6jbn6}Mt=up{a6X3'//Llave de dosificación 
                // ));
            },

            calculateLineTotal(invoice_product) {
                var total = parseFloat(invoice_product.product_price) * parseFloat(invoice_product.product_qty);
                if (!isNaN(total)) {
                    invoice_product.line_total = total.toFixed(2);
                }
                this.calculateTotal();
                },
                
            deleteRow(index, invoice_product) {
                var idx = this.invoice_products.indexOf(invoice_product);
                console.log(idx, index);
                if (idx > -1) {
                    this.invoice_products.splice(idx, 1);
                }
                
                this.calculateTotal();
            },
            
            addNewRow() {
                this.invoice_products.push({
                    product_no: '',
                    product_name: '',
                    product_price: '',
                    product_qty: '',
                    line_total: 0
                });
            },
            
            dataparametro (){
                let me=this;
                var url= '/con_factura/dataparametro';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayFacturaParametro = respuesta.facturaparametro.data;         //console.log(me.arrayFacturaParametro[0].numeroautorizacion);           
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            maxfactura (){
                let me=this;
                var url= '/con_factura/maxfactura';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.maxfacturavalor = respuesta['maxfactura']+1;     //console.log(me.maxfacturavalor);    
                    me.nufactura=me.maxfacturavalor;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            listarFactura (page,buscar,mes,anio,filial){
                let me=this;
                me.diaminmax();
                var url= '/con_factura?page=' + page + '&buscar='+ buscar +'&mes='+mes+'&anio='+anio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayFactura = respuesta.factura.data;
                    if(me.arrayFactura.length>0)
                    {
                        me.nunumautorizacion=me.arrayFactura[0].numautorizacion; 
                        if(me.messelected>me.mes && me.anioselected== me.anio)
                            me.cerrado=1;
                        else
                        {
                            me.cerrado=me.arrayFactura[0].mescerrado;
                            //console.log(me.cerrado);
                        }

                    }
                    else
                    {
                        if(me.messelected<me.mes && me.anioselected==me.anio)
                            me.cerrado=1;
                        else
                            if(me.messelected>me.mes && me.anioselected==me.anio)
                                me.cerrado=1;
                            else
                                me.cerrado=0
                    }
                        
                        
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            cambiarPagina(page,buscar){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarFactura(page,buscar,me.messelected,me.anioselected);
            },
            
            registrarFactura(){ 
               let me = this;
               me.nufechafactura=me.fechafactura

               ////////////////////////// quitar o modificar para facturas electronica s
               if(me.nuimporte!=0)
               {
                    me.invoice_subtotal=me.nuimporte;
                    me.numerofactura=me.nufactura;
                    me.codigocontrol='', //// por el momento facturas manuales
                    me.razonsocial=me.nurazonsocial;
                    me.nit=me.nunit;
                    me.invoice_products=me.nudetalle;
               }
               ///////////////////////////////////////////////////////////////////////

                //var _13porciento=Number((total*0.13).toFixed(2));
                let porcendebito=0;
                let porcenit=0;
                let restodebito=0;
                porcendebito =Number((me.invoice_subtotal * me.porcentajelv).toFixed(2));
                //console.log(porcendebito);
                restodebito=Number((me.invoice_subtotal-porcendebito).toFixed(2));
                //console.log(restodebito);
                porcenit=Number((me.invoice_subtotal * me.porcentajeit).toFixed(2)); 


                axios.post('/con_factura/registrar',{
                    'numerofactura': me.numerofactura,
                    'codigocontrol': me.codigocontrol,
                    'razonsocial': me.razonsocial,                    
                    'nit': me.nit,
                    'detalle': me. invoice_products,
                    'importetotal': me.invoice_subtotal,
                    'importecf': me.invoice_subtotal,
                    'debfiscal':porcendebito,
                    'restoimporte':restodebito,
                    'it':porcenit,
                    'numautorizacion':me.nunumautorizacion,
                    'fechafactura':me.fechafactura,
                    'subcuenta':me.idempleado[0]

                }).then(function (response) {                    
                    
                      
                    if (response.data.length) {                        
                        swal(
                            // response.data,
                            'El valor ya existe',
                            'Ingresar una dato diferente',
                            'error')
                    }
                    else {  ///las lineas comentadas son para mostrar la factura electronica
                            //_pdf.imgToBase64('img/iconad.png', function (base) {
                            //_pdf.openRecibo(me.numerofactura,me.codigocontrol,me.razonsocial,me.nit,me.invoice_products,me.importetotal,base); 
                        //});
                        me.listarFactura(1,'',me.messelected,me.anioselected);
                        me.maxfactura(); 
                        //me.cerrarModal(); 
                        me.resetfactura();   
                        me.fechafactura=me.nufechafactura;                    
                        
                         
                    }
                    
                }).catch(function (error) {
                    console.log(error);
                });
            },
            resetfactura(){
                let me =this;
                me.nurazonsocial='';
                me.nunit=0;
                me.nudetalle='HOSPEDAJE';
                me.nuimporte=0;
                me.idempleado=[];
                me.checkdescuento=0;
                me.nomsocio='';
                //me.$refs.nurazonsocial.focus();
                //Vue.nextTick().then(()=> this.$refs.input.focus());


            },
            calcularimpuestos(){
                let me= this;

            },

            selectLibroCuenta(){
            let me=this;
            let respuesta;
            var url= '/con_config/cuentaslibros';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayLibroCuentas = respuesta;
                me.resLibroventas=me.arrayLibroCuentas.libroventas;
                //console.log(me.resLibroventas);
                respuesta=me.resLibroventas.find(element=>element.codigo=='LV');
                me.lv=respuesta.valor;
                me.porcentajelv=respuesta.valor2;
                me.porcentajelv=Number((me.porcentajelv/100).toFixed(2));
                

                respuesta=me.resLibroventas.find(element=>element.codigo=='IT');
                me.it=respuesta.valor;
                me.porcentajeit=respuesta.valor2;
                me.porcentajeit=Number((me.porcentajeit/100).toFixed(2));
                
                respuesta=me.resLibroventas.find(element=>element.codigo=='ITXP');
                me.itxp=respuesta.valor;
                me.porcentajeitxp=respuesta.valor2;
                //console.log(me.lv,me.porcentajelv,me.it,me.porcentajeit,me.itxp,me.porcentajeitxp);
            })
            .catch(function (error) {
                console.log(error);
            });
        },

            actualizarFactura(){  
                let me = this;
                axios.put('/con_factura/actualizar',{
                    'idfactura': this.factura_id, 
                    'numerofactura': this.nufactura,
                    'codigocontrol': '',
                    'razonsocial': this.nurazonsocial,                    
                    'nit': this.nunit,
                    'detalle': this.nudetalle,
                    'importetotal': this.nuimporte,
                    'importecf': this.nuimporte,
                    'fechafactura':this.fechafactura
                }).then(async function (response) {
                    if(response.data.length){
                        swal(
                            'El Valor ya Existe',
                            'Ingresa un dato Diferente',
                            'error'
                        )
                    }
                    else{                                                
                        //campo detalle: detalle|importe|cantidad|total
                        //factura.genera(nit,nombre,detalle,monto)                                                
                        // var resultado = await factura.genera('123123123','nombre','121231231|23.2|1|23.3,84846548|11.23|1|11.23',23.36);                         
                        // console.log('dev: ' + resultado['nrofactura']);   
                        me.cerrarModal();
                        me.resetfactura();
                        me.listarFactura(1,'',me.messelected,me.anioselected);
                    }
                }).catch(function (error) {
                    console.log(error);
                });                 
            },

            desactivarFactura(id,anularfac){
                let mensaje='';
                let mensaje2=''
                if(anularfac==true)
                {
                    mensaje='Anulada';
                    mensaje2='Anular'
                }
                else
                {
                    mensaje='Eliminada';
                    mensaje2='Eliminar'
                }
                    
               swal({
                title: 'Esta seguro que desea '+mensaje2+' esta Factura?',
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

                    axios.put('/con_factura/desactivar',{
                        'idfactura': id,
                        'anularfac':anularfac
                    }).then(function (response) {
                        me.listarFactura(1,'',me.messelected,me.anioselected);
                        swal(
                        mensaje+'!',
                        'La factra ha sido ' + mensaje + ' con exito',
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
            
            activarFactura(id){
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

                    axios.put('/con_factura/activar',{

                         'idfactura': id
                    }).then(function (response) {
                        me.listarFactura(1,'',me.messelected,me.anioselected);
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
                this.classModal.closeModal('editarfactura'); 
                this.modal=0;
                this.tituloModal='';                
                this.numerofactura='';
                this.codigocontrol='';
                this.razonsocial='';               
                this.nit='';
                //this.invoice_products='';
                this.invoice_subtotal='';
                this.invoice_subtotal='';
                this.validadoconta=0;
            },
            verficarnumfactura(valor){
                //console.log('entra')
                let me =this;
                let respuesta;
                //console.log(me.arrayFactura);
                respuesta=me.arrayFactura.find(element=>element.numerofactura==me.nufactura);
                if(respuesta)
                    me.duplicado=true;
                else
                    me.duplicado=false;
            },
            getRutasReports(){ 
            let me=this;
            var url= '/con_reportes';
            axios.get(url).then(function (response) {
                    var respuesta= response.data; ;
                me.reporte_libro_ventas = respuesta.REP_LIBRO_VENTAS; 
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        reportelibroventas(tipo){
            let me=this;
            let url=me.reporte_libro_ventas + tipo+'&mes='+me.messelected+'&anio='+me.anioselected; 
            //console.log(url);
            if(tipo=='pdf')
                plugin.viewPDF(url,'Libro de Ventas');
            else
              window.open(url,'_blank');  
            //this.abrirVentanaModalURL(url,"Reporte Horizontal",800,700);		
            
            //window.open('http://192.168.100.60:8080/birt-viewer/frameset?__report=reportes/apo_reportes/rep_horizontal.rptdesign&numpapeleta='+papeletanum,'_blank');
            //window.open('http://localhost:8080/birt-viewer/frameset?__report=reportes/apo_reportes/rep_horizontal.rptdesign&numpapeleta='+papeletanum,'_blank');
        },

            abrirModal(modelo, accion, data = []){ //console.log('ja' + this.maxfacturavalor);
                switch(modelo){
                    case "factura":
                    {
                        switch(accion){
                            case 'registrar':
                            {                                                                
                                this.invoice_products = [];
                                this.classModal.openModal('editarfactura');
                                //this.modal = 1;
                                this.tituloModal = 'Registrar Factura';
                                this.numerofactura=this.maxfacturavalor;
                                this.codigocontrol='',
                                this.razonsocial='',               
                                this.nit='',
                                this.detalle='',
                                this.importeparcial='',
                                this.importetotal='',
                                this.importecf='',                         
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            { 
                                /* this.invoice_products = [];
                                var detalle0 = [];
                                var detalle1 = [];
                                detalle0 = data['detalle'].split(','); //console.log(detalle0[0]);
                                //var array1 = JSON.parse("[" + data['reparticiones'] + "]"); 
                                
                                for (var i=0; i < detalle0.length; i++){
                                    //console.log(detalle1[i]);         
                                    detalle1 = detalle0[i].split('|'); //console.log(detalle0[0]);
                                    this.invoice_products.push({
                                        product_name:detalle1[0],
                                        product_price:detalle1[1],
                                        product_qty:detalle1[2],
                                        line_total:detalle1[3]}) 
                                } */
                                                                                                
                                //console.log(this.invoice_products);

                                //console.log(data);
                                //this.modal=1;
                                this.classModal.openModal('editarfactura');
                                this.tituloModal='Actualizar Factura';
                                this.tipoAccion=2;
                                this.factura_id=data['idfactura'];
                                this.nufactura = data['numerofactura'];
                                this.codigocontrol = data['codigocontrol'];
                                this.nurazonsocial = data['razonsocial'];
                                this.nunit = data['nit'];
                                this.nudetalle = data['detalle'];
                                this.fechafactura=data['fecha'];
                                
                                this.nuimporte = data['importetotal'];
                                this.validadoconta= data['validadoconta'];
                                //this.importecf = data['importecf'];
                                //this.calculateTotal();
                                break;
                            }
                        }
                    }
                }
            },

        },
        mounted() {
            this.classModal=new _pl.Modals();
            this.fechahoy();                
            this.listarFactura(1,this.buscar,this.mes,this.anio);
            this.dataparametro();
            this.maxfactura();  
            this.selectLibroCuenta();  
            this.classModal.addModal('descuentos');
            this.classModal.addModal('editarfactura');
            this.getRutasReports();
            
        }
    }
</script>
