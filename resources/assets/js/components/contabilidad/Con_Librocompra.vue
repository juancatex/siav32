<template>
    <main class="main">
        <div class="breadcrumb titmodulo">Contabilidad > Libro de Compras</div>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <div class="form-group row " style="margin-bottom: 0px;">
                        <div class="col-md-8">
                             <div class="form-group row" style="margin-bottom: 0px;padding-left: 30px;">
                                <i class="fa fa-align-justify"></i>&nbsp; Libro de Compras &nbsp;
                                <button v-if="check('libro_agregar')" type="button" @click="abrirModalCompras('registrar')" class="btn btn-secondary" :disabled='!ismescerrado || filialselected==0'>
                                    <i class="icon-plus"></i>&nbsp;Nuevo
                                </button>
                                &nbsp;&nbsp;&nbsp;<span class="text-error" v-if="filialselected==0">Debe seleccionar una filial para agregar mas facturas</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group row" style="margin-bottom: 0px;">
                                <div class="col-md-3">
                                    <strong class="form-control-label">Filial:</strong>
                                </div>
                                <div class="col-md-8">
                                    <select v-model="filialselected"  class="form-control" @change="listarFacturas(1,buscar,messelected,anioselected,filialselected)">
                                        <option value="0">Todas</option>
                                        <option v-for="filial in arrayFilial" v-bind:key="filial.idfilial" :value="filial.idfilial" v-text="filial.nommunicipio"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-3">
                            <div class="input-group">
                            <strong class="col-md-4 form-control-label" style="margin-bottom: 0px;margin-top: 8px;" >Mes:</strong>
                            <select v-model="messelected"  class="form-control" @change="listarFacturas(1,buscar,messelected,anioselected,filialselected)">
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
                               <input class="form-control" type="text" v-model="buscar" placeholder="Buscar"  @keyup.enter="listarFacturas(1,buscar,messelected,anioselected,filialselected)" v-on:focus="selectAll">
                                    <button class="btn btn-primary" type="button" @click="listarFacturas(1,buscar,messelected,anioselected,filialselected)">
                                            <i class="fa fa-search"></i>
                                    </button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <button type="button" @click="imprimirLibroCompras(messelected,anioselected)" class="btn btn-primary">
                                    <i class="cui-print"></i>
                                    
                                </button>
                                &nbsp;
                                <template v-if="cerrado" >
                                    <button type="button" class="btn btn-danger" disabled data-toggle="tooltip" data-placement="top" title="Mes Cerrado">
                                        <i class="cui-lock-locked"></i>
                                    </button>
                                </template>
                                <template v-else>
                                    <button type="button" @click="CerrarmesLibroCompras()" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Cerrar Mes">
                                        <i class="cui-lock-unlocked"></i>
                                    </button>
                                </template>
                                    
                                
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead class="thead-light">
                                    <tr>
                                        <th style="width:90px">Opciones</th>
                                        <th>Nº</th>
                                        <th>Comprobante</th>
                                        <th>Fecha Factura</th>                     
                                        <th>Nit - Razon Social</th>
                                        <th>Nº Factura</th>
                                        <th>Registrado por:</th>
                                        <th>Importe</th>
                                        <th v-if="filialselected==0">Filial</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                        <tbody>
                            <tr v-for="librocompras in arrayLibrocompras" :key="librocompras.idlibrocompra">
                                <td>
                                    <template v-if="(librocompras.idasientomaestro==null && librocompras.lote==null) || !librocompras.validadoconta">
                                        <button v-if="check('libro_editar')" type="button" @click="abrirModalCompras('editar',librocompras)" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Editar">
                                            <i class="icon-note"></i>
                                        </button>
                                    </template>
                                    <template v-if="librocompras.idasientomaestro==null && librocompras.lote==null">
                                        <button v-if="check('libro_eliminar')" type="button" @click="eliminarFactura(librocompras.idlibrocompra)" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </template>
                                </td>
                                <td v-text="librocompras.numeracion"></td>
                                <td v-text="librocompras.cod_comprobante"></td>
                                <td v-text="librocompras.fecha_factura"></td>
                                <td v-text="librocompras.nit + ' ' +  librocompras.nomproveedor"></td>
                                <td v-text="librocompras.numfactura" style="text-align:right"></td>
                                <td v-text="librocompras.username"></td>
                                <td v-text="librocompras.importe+' Bs.'" style="text-align:right"></td>
                                <td v-if="filialselected==0" v-text="librocompras.sigla"></td>
                                <td><template v-if="librocompras.estado==1">
                                        <span class="badge badge-success">Validado</span>
                                    </template>
                                    <template v-if="librocompras.estado==5">
                                        <span class="badge badge-warning">Comprobante borrador</span>
                                    </template>
                                    <template v-if="librocompras.idasientomaestro==null && librocompras.lote==null">
                                        <span class="badge badge-danger">Sin Comprobante</span>
                                    </template>
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
        <!--Inicio del modal agregar factura-->
        <div class="modal fade" tabindex="-1" role="dialog"   aria-hidden="true" id="librocompras"  data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header justify-content-between" style="padding-bottom: 5px;padding-top: 5px;">
                        <h4 class="modal-title" v-text="tituloModallibro"></h4>
                        <div>
                            <div v-if="tipoAccionlibro==2" class=" form-group row" style="margin-top:10px">
                                <div style="padding-right: 30px; padding-top: 10px;">
                                    <strong class="form-control-label">Filial:</strong>
                                </div>
                                <div>
                                    <select v-model="filialselected"  class="form-control" @change="listarFacturas(1,buscar,messelected,anioselected,filialselected)">
                                        <option value="0">Todas</option>
                                        <option v-for="filial in arrayFilial" v-bind:key="filial.idfilial" :value="filial.idfilial" v-text="filial.nommunicipio"></option>
                                    </select>
                                </div>
                                <button type="button" class="close" @click="cerrarModalCompras()" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-8">
                                <label for="idproveedor"><strong>Nit - Proveedor:</strong></label>
                                <div class="form-group row">
                                    <div class="col-md-11" style="padding-right: 5px;">
                                        <Ajaxselect  v-if="clearSelected"
                                            ruta="/alm_proveedor/selectProveedor?buscar=" @found="proveedores" @cleaning="cleanproveedores"
                                            resp_ruta="proveedores"
                                            labels="nit_proveedor"
                                            placeholder="Ingrese texto" 
                                            idtabla="idproveedor"
                                            :id="idproveedorrespuesta"
                                            :clearable='true'>
                                        </Ajaxselect>
                                    </div>
                                    <div class="col-md-1" style="padding-left: 0px;">
                                        <button type="button" class="btn btn-primary" @click="abrirModalProveedores()" aria-label="Close">
                                            <i class="icon-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="Fecha Factura"><strong>Fecha Factura:</strong></label>
                                <input type="date" 
                                        v-model="fechafactura"
                                        class="form-control formu-entrada" 
                                        :max="fechafinal"
                                        :min="fechainicial"
                                        v-validate.initial="'required'"
                                        name="Fechar Factura">
                                    <span class="text-error">{{ errors.first('Fecha Factura')}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <strong>Nº Factura:</strong>
                                <input class="form-control input-importe" type="number" 
                                        v-model="numfactura" 
                                        v-on:focus="selectAll"
                                        name="Nº Factura"
                                        v-validate.initial="'required'">
                                <span class="text-error">{{ errors.first('Nº Factura')}}</span>
                            </div>
                            <div class="form-group col-md-4">
                                <strong>Nº Autorizacion:</strong>
                                <input class="form-control" type="text"  style="text-align:right"
                                        v-model="numautorizacion" 
                                        
                                        v-on:focus="selectAll"
                                        v-validate.initial="'required'"
                                        name='Nº autorizacion'>
                                <span class="text-error">{{ errors.first('Nº autorizacion')}}</span>
                            </div>
                            <div class="form-group col-md-4">
                                <strong>Codigo Control:</strong>
                                <input class="form-control" 
                                    type="text" 
                                    v-mask="'NN-NN-NN-NN-NN'" 
                                    v-model="codcontrol" 
                                    v-on:focus="selectAll" 
                                    onKeyUp="this.value=this.value.toUpperCase();"
                                    placeholder="00-00-00-00-00">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <strong>Importe Total:</strong>
                                <vue-numeric  
                                                class="form-control input-importe"
                                                currency="Bs." 
                                                separator="," 
                                                v-model="importetotal"
                                                v-bind:precision="2"
                                                v-on:focus="selectAll">
                                </vue-numeric>
                                <template v-if="importetotal==0">
                                    <div>
                                        <span class="text-error">Debe Ingresar Importe</span>        
                                    </div>
                                </template>
                            </div>
                            <div class="form-group col-md-4">
                                <strong>No sujeto a credito Fiscal:</strong>
                                <vue-numeric  
                                                class="form-control input-importe"
                                                currency="Bs." 
                                                separator="," 
                                                v-model="nocreditofiscal"
                                                v-bind:precision="2"
                                                v-on:focus="selectAll">
                                </vue-numeric>
                                <span class="text-error" v-if="iscreditofiscal">el monto no debe ser mayor al importe total</span>
                            </div>
                            <div class="form-group col-md-4">
                                <strong>Descuentos - Rebajas:</strong>
                                <vue-numeric  
                                                class="form-control input-importe"
                                                currency="Bs." 
                                                separator="," 
                                                v-model="descuentos"
                                                v-bind:precision="2"
                                                v-on:focus="selectAll">
                                </vue-numeric>
                                <span class="text-error" v-if="isdescuentos">el monto no debe ser mayor al importe</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <strong>Detalle de factura:</strong>
                            </div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" v-model="detalle">
                                <span class="text-error" v-if="!detalle">Debe Ingresar el detalle de la factura</span>
                            </div>
                            
                        </div>
                        <div class="modal-footer">
                            <div>
                                <button type="button" class="btn btn-secondary" @click="cerrarModalCompras()">Cerrar</button>
                                <!-- :disabled = "errors.any()" -->
                                <button :disabled ="!iscompletelibro || (isdescuentos || iscreditofiscal)" type="submit" v-if="tipoAccionlibro==1" class="btn btn-primary" @click="registroLibrocompra()">Guardar</button>
                                <button :disabled ="!iscompletelibro || (isdescuentos || iscreditofiscal)" type="button" v-if="tipoAccionlibro==2" class="btn btn-primary" @click="actualizarLibrocompra()">Actualizar</button>
                            </div>
                        </div>
                    </div>
                <!-- /.modal-content -->
                </div>
            <!-- /.modal-dialog -->
            </div>
        </div>
        <!--Fin del modal-->
        <!-- MODAL PROVEEDOR MODAL PROVEEDOR MODAL PROVEEDOR MODAL PROVEEDOR MODAL PROVEEDOR  -->
        <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="proveedor"  data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-primary">
                <div class="modal-content animated fadeIn">
                    <div class="modal-header">
                        <h4 class="modal-title">Registro de Proveedores</h4>
                        <button class="close" @click="cerrarmodalproveedor()">x</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-sm-7">
                                <strong>Nombre Proveedor:</strong>
                                <input type="text" class="form-control" v-model="nomproveedor">
                                <span class="text-error" v-if="nomproveedor==''">Proveedor no debe estar Vacio</span>
                            </div>
                            <div class="form-group col-sm-5">
                                <strong>NIT:</strong>
                                <input type="text" class="form-control" v-model="nit">
                                <span class="text-error" v-if="nit==''">NIT no debe estar Vacio</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-7">
                                <strong>Dirección:</strong>
                                <input type="text" class="form-control" v-model="direccion">
                            </div>
                            <div class="form-group col-sm-5">
                                <strong>Teléfono:</strong>
                                <input type="text" class="form-control" v-model="telefono">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-7">
                                <strong>Nombre Contacto:</strong>
                                <input type="text" class="form-control" v-model="nomcontacto">
                            </div>
                            <div class="form-group col-sm-5">
                                <strong>Celular:</strong>
                                <input type="text" class="form-control" v-model="celular">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarmodalproveedor()">Cerrar</button>
                        <button :disabled="!iscompleteproveedor" class="btn btn-primary" @click="storeProveedor()">Guardar</button>
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- fin modal add proveedor -->
    </main>
</template>

<script>
    import VueBarcode from 'vue-barcode';
    import Vue from 'vue';
   // import VeeValidate from 'vee-validate';
    import vSelect from 'vue-select'; 
    import VueNumeric from 'vue-numeric';
    import * as plugin from '../../functions.js';
    import VueMask from 'v-mask'
    Vue.use(VueMask);


    //import vSelect from 'vue-select';
    //Vue.component( 'v-select', vSelect );

    //Vue.component('v-select', VueSelect.VueSelect)

  /*   const VueValidationEs = require('vee-validate/dist/locale/es');
        Vue.use(VeeValidate, 
        {
            locale: 'es',
            dictionary: 
            {
                es: VueValidationEs
            }
        });
    
    Vue.use(VeeValidate); */
    Vue.component('v-select',vSelect);
    Vue.use(VueNumeric);

    export default {
        props:['idmodulo','idventanamodulo'],
        data (){
            return {
                
                arrayLibrocompras:[],
                tituloModallibro:'',
                fechafactura:'',
                idproveedor:[],
                numfactura:'',
                numautorizacion:'',
                codcontrol:'',
                importetotal:0,
                nocreditofiscal:0,
                descuentos:0,
                tipoAccionlibro:1,
                fechaactual:'',
                mes:'',
                anio:'',
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
                messelected:'',
                anioselected:'',
                
                
                
                ciudad_id: 0,
                iddepartamento : 0,
                nomciudad : '',
                nomdepartamento:'',
                
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorCiudad : 0,
                errorMostrarMsjCiudad : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                buscar : '',
                arrayDepartamento :[],
                cerrado:false,
                accion:'registrar',
                primerDia:0,
                ultimoDia:0,
                fechainicial:'',
                fechafinal:'',
                clearSelected:1,
                idproveedorrespuesta:'',
                idlibrocompra:0,
                asientomaestro:0,
                resimportetotal:0,
                resnocreditofiscal:0,
                resdescuentos:0,
                detalle:'',
                ////////////////modal proveedores /////////////////
                nomproveedor:'',
                direccion:'',
                nomcontacto:'',
                nit:'',
                telefono:'',
                celular:'',
                ////////////////////filial/////////////
                filialselected:0,
                arrayFilial:[],
                nomfilial:'',
                arrayPermisos:{libro_agregar:0,
                                libro_editar:0,
                                libro_eliminar:0
                                },
                
                arrayPermisosIn:[]

            }
        },
        components: {
        'barcode': VueBarcode
    },
        computed:{
            iscompleteproveedor(){
                let me=this;
                var correcto=false;
                if (me.nomproveedor!='' && me.nit!='')
                    correcto=true;
                return correcto;
            },
            ismescerrado: function(){
                
                //console.log(this.cerrado);
                
                return !this.cerrado;
            },
            isdescuentos(){
                let me=this;
                if(me.descuentos>me.importetotal)
                    return true;
                else
                    return false;
            },
            iscreditofiscal(){
                let me=this;
                if(me.nocreditofiscal>me.importetotal)
                    return true;
                else
                    return false;
            },

            iscompletelibro(){
                let me=this;
                //console.log(me.idproveedor.length+ ' complete libro');
                
                if((me.idproveedor.length!=0||me.idproveedorrespuesta!=0) && me.fechafactura && me.numfactura && me.numautorizacion && me.importetotal && me.detalle) 
                    return true;
                else
                    return false

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
             getPermisos() {
            var url= '/adm_role/selectPermisos?idmodulo=' + this.idmodulo+'&idventanamodulo='+this.idventanamodulo;
                let me = this; 
                axios.get(url).then(function (response) {
                   me.arrayPermisosIn=[];
                    if(response.data.datapermiso.length>0){
                        var respuesta=response.data.datapermiso[0].permisos; 
                        me.arrayPermisosIn = JSON.parse((respuesta));
                    } 
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            check(n){
                return _pl.validatePermission(this.arrayPermisosIn,n);
            },
            selectfilial(){
                let me=this;
                var url='/fil_filial/selectFiliales';
                axios.get(url).then(function(response){
                    me.arrayFilial=response.data.filiales;
                    //console.log(me.arrayFilial);
                    
                });
            },
            corregircodcontrol(){
                let me=this;
                if(me.codcontrol!='')
                {
                    var corregido;
                    var tamanio;
                    var letrafinal= me.codcontrol.substr(-1,1)
                    if(letrafinal=='-')
                    {
                        tamanio=me.codcontrol.length;
                        corregido=me.codcontrol.substr(0,tamanio-1);
                         me.codcontrol=corregido;
                    }
                }
                    
                
                //return letrafinal;
            },
            cerrarmodalproveedor(){
                let me =this;
                me.nomproveedor='';
                me.direccion='';
                me.nomcontacto='';
                me.nit='';
                me.telefono='';
                me.celular='';
                me.classModal.closeModal('proveedor'); 
                me.classModal.openModal('librocompras');

            },
            storeProveedor(){
                var url='/alm_proveedor/storeProveedor';
                let me=this;
                axios.post(url,{
                    'nomproveedor':this.nomproveedor,
                    'nomcontacto':this.nomcontacto,
                    'nit':this.nit,
                    'direccion':this.direccion,
                    'telefono':this.telefono,
                    'celular':this.celular
                }).then(function(){
                    swal('Adicionado correctamente','','success');
                    me.cerrarmodalproveedor();
                });
            },
            abrirModalProveedores(){
                let me=this;
                me.classModal.closeModal('librocompras'); 
                me.classModal.openModal('proveedor');
                
            },
            getRutasReports (){ 
                let me=this;
                var url= '/con_reportes';
                axios.get(url).then(function (response) {
                     var respuesta= response.data; ;
                    me.reporte_libro_compras = respuesta.REP_LIBRO_COMPRAS; 
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            eliminarFactura(idlibrocompra){
               swal({
                title: 'Esta seguro de elimiar Esta Factura?',
                text: 'Ya no se Podra utilizar los datos de esta factura',
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
                    axios.put('/con_librocompras/desactivar',{
                        'idlibrocompra': idlibrocompra
                    }).then(function (response) {
                        me.listarFacturas(1,'',me.messelected,me.anioselected,me.filialselected);
                        swal(
                        'Eliminado!',
                        'La Factura ha sido eliminada  con éxito.',
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
            imprimirLibroCompras(mselected,aselected){
                let me=this;
                var url=me.reporte_libro_compras + mselected+'&anio='+aselected; 
                //console.log(url);
                
                //me.abrirVentanaModalURL(url,"reporte_asiento_automatico",800,700);	
                plugin.viewPDF(url,'libro de compras');

            },
            cleanproveedores(){
                this.idproveedor=[];
                this.idproveedorrespuesta=0;
            //console.log('clean')
            
            },
            tiempo(){
            this.clearSelected=1;
            },
            proveedores(proveedores){
                this.idproveedor=[];
               // this.arrayCuenta.push({cuentita:cuentas.idcuenta})         
                //console.log(idproveedores);
                for (const key in proveedores) {
                    if (proveedores.hasOwnProperty(key)) {
                        const element = proveedores[key];
                        //console.log(element);
                        this.idproveedor.push(element);
                    }
                }
                //console.log(this.idproveedor);
            },
            CerrarmesLibroCompras(){
                 swal({
                    title: 'Esta seguro de Cerrar el Mes: '+ this.arraymes[this.messelected-1].text+' / '+this.anioselected, 
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
                        axios.post('/con_cierrelibrocompra/registrar',{
                            'mes': me.messelected,
                            'anio':me.anioselected,
                        }).then(function (response) {
                            me.listarFacturas(1,me.buscar,me.messelected,me.anioselected,me.filialselected);
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
            mescerrado(){
                let me=this;
                var url= '/con_cierrelibrocompra?mes='+me.messelected+'&anio='+me.anioselected;
                //console.log(url);
                
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    //console.log(respuesta);
                    
                    me.cerrado=respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                });

            },
            abrirModalCompras(accion,data=[]){
                let me=this;
                console.log(data);
                
                switch(accion){
                    case 'registrar':
                    {
                        me.idproveedor=[];
                        me.tipoAccionlibro=1;
                        me.idproveedorrespuesta=0;
                        me.clearSelected=0;
                        setTimeout(me.tiempo, 50); 
                        me.classModal.openModal('librocompras');
                        me.tituloModallibro = 'Registro Libro de Compras Filial: '+me.nomfilial;
                        //me.$refs.comboproveedor.clearSelection(); 
                        
                        if(me.mes+1==me.messelected)
                        {    
                            me.fechafactura=me.fechaactual;
                            //me.fechafinal=me.fechaactual;
                        }
                        else   
                            me.fechafactura=me.fechafinal;

                        me.numfactura='';
                        me.numautorizacion='';
                        me.codcontrol='';
                        me.importetotal=0;
                        me.nocreditofiscal=0;
                        me.descuentos=0;
                        break;
                    }
                    case 'editar':
                    {
                        me.tipoAccionlibro=2;
                        me.idlibrocompra=data['idlibrocompra'];
                        me.idproveedorrespuesta=data['idproveedor'];
                        me.idproveedor[0]=data['idproveedor'];
                        me.clearSelected=0;
                        setTimeout(me.tiempo, 50);
                        me.tituloModallibro = 'Editar Factura';
                        //me.$refs.comboproveedor.clearSelection(); 
                        me.fechafactura=data['fecha_factura'];
                        me.numfactura=data['numfactura'];
                        me.numautorizacion=data['numfactura'];
                        me.codcontrol=data['codigocontrol'];
                        me.importetotal=data['importe'];
                        me.resimportetotal=data['importe'];
                        me.nocreditofiscal=data['impnocredfiscal'];
                        me.resnocreditofiscal=data['impnocredfiscal'];
                        me.descuentos=data['descuentos'];
                        me.resdescuentos=data['descuentos'];
                        me.detalle=data['detalle_fac'];
                        me.filialselected=data['idfilial'];
                        if(data['idasientomaestro']!=null)
                        {
                            
                            swal({
                            title: 'Esta Factura Esta Asociada a un Comprobante Contable',
                            text: 'si modifica el importe de esta factura debera modificar el Registro contable',
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
                                me.asientomaestro=1;
                                //console.log('asientomaestrook '+me.asientomaestro);
                                me.idasientomaestro=data['idasientomaestro'];
                                me.classModal.openModal('librocompras');
                            } 
                            else if (result.dismiss === swal.DismissReason.cancel)
                            {
                                me.asientomaestro=0;
                                //console.log('me.asientomaestrocancel '+me.asientomaestro);
                                
                            }
                            }) 
                        }
                        else
                        {
                            me.classModal.openModal('librocompras');
                        }
                        //console.log('me.asientomaestro '+ me.asientomaestro);
                        
                        break;
                    }
                }
                //this.selecttoDepartamen();
            },
            registroLibrocompra(){
                let me = this;
                //console.log(me.idproveedor[0]);
                var total=me.importetotal-me.nocreditofiscal-me.descuentos;
                me.corregircodcontrol()
               
                var _13porciento=Number((total*0.13).toFixed(2));   //parseFloat((total * 0.13)).toFixed(2);
                var _87porciento=Number((total-_13porciento).toFixed(2));//(parseFloat(total)-parseFloat(_13porciento)).toFixed(2);

    /*             var _13porciento=parseFloat((total * 0.13)).toFixed(2);
                var _87porciento=(parseFloat(total)-parseFloat(_13porciento)).toFixed(2); */
                axios.post('/con_librocompras/registrar',{
                    'fechafactura':me.fechafactura,
                    'idproveedor':me.idproveedor[0],
                    'numfactura':me.numfactura,
                    'numautorizacion':me.numautorizacion,
                    'codcontrol':me.codcontrol,
                    'importetotal':me.importetotal,
                    'nocreditofiscal':me.nocreditofiscal,
                    'descuentos':me.descuentos,
                    '_13porciento':_13porciento,
                    '_87porciento':_87porciento,
                    'accion':me.accion,
                    'idfilial':me.filialselected,
                    'lote':me.lote,
                    'detalle':me.detalle
                    
                }).then(function (response) {
                    //me.reslote=response;
                    //console.log(me.reslote)
                    swal(
                            'Registrado Correctamente'
                       )                    
                    // me.listarAsientoMaestro(1,me.criterio,me.borradorcheck);
                    me.listarFacturas(1,'',me.messelected,me.anioselected,me.filialselected);
                    me.cerrarModalCompras();
                    //me.$refs.comboproveedor.clearSelection(); 
                    me.clearSelected=0;
                    setTimeout(me.tiempo, 100);
                    me.idproveedor=[];
                    me.idproveedorrespuesta=0;

                    if(me.messelected==me.mes) 
                        me.fechafactura=me.fechaactual;
                    else
                    me.fechafactura=me.fechafinal;
                    me.fechafactura=me.fechaactual;
                    me.numfactura='';
                    me.numautorizacion='';
                    me.codcontrol='';
                    me.importetotal=0;
                    me.nocreditofiscal=0;
                    me.descuentos=0;
                    me.detalle='';
                }).catch(function (error) {
                    console.log(error);
                });

            },
            cerrarModalCompras(){
                let me=this;
                //console.log(idcuenta[0]);
                //console.log(idcuenta[2]+' '+idcuenta[3])
                me.idproveedor=[];
                me.idproveedorrespuesta=0;
                if(me.accion=='editar')
                {
                    me.rowregistros[me.indice].debe=me.acumulado13;
                }   
                if(me.accion=='registrar')
                {
                    
                    //this.addrowregistro(true);
                    //me.rowregistros[me.indice+1].debe=me.acumulado87;
                }
                me.classModal.closeModal('librocompras'); 
              
            },
            fechahoy(){
                var hoy = new Date();
                var dd = hoy.getDate(); 
                
                var mm = hoy.getMonth();
                

                var aa = hoy.getFullYear();
                this.mes=mm;
                this.anio=aa;
                //console.log(this.fechaactual);
                //console.log(this.arraymes[this.mes].value + 'arraymes.value');
                
                this.messelected=this.arraymes[this.mes].value;
                this.anioselected=this.anio;
                /* console.log(this.messelected);
                console.log(this.anioselected);
                */
                mm=mm+1;
                if(mm<10) 
                    mm='0'+mm;
                
                if(dd<10)
                    dd='0'+dd; 
                this.fechaactual=aa+'-'+mm+'-'+dd;
                this.fechafactura=this.fechaactual;

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
                
                this.fechainicial=this.anioselected+'-'+mm+'-'+dd;
                this.fechafinal=this.anioselected+'-'+mm+'-'+ultimoDiaMes.getDate();
                if(this.fechafinal > this.fechaactual)
                    this.fechafinal=this.fechaactual;

               
                /* console.log(this.fechainicial);
                console.log(this.fechafinal);
                 */
                
            },
            selectAll: function (event) {
      
                setTimeout(function () {
                    event.target.select()
                }, 0)
            },
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

            listarFacturas(page,buscar,mes,anio,filial){
                let me=this;
                me.mescerrado();
                me.diaminmax();
                var arrayDatosFilial = me.arrayFilial.filter( function(obj) {
                if( obj.idfilial == filial ){
                    return obj;
                }
                });
                if(arrayDatosFilial.length!=0)
                    //console.log(arrayDatosFilial[0].nommunicipio);
                    me.nomfilial=arrayDatosFilial[0].nommunicipio;
                
                var url= '/con_librocompras?page=' + page + '&buscar='+ buscar+'&mes='+mes+'&anio='+anio+'&filial='+filial;
               // console.log(url);
                
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                   // console.log(respuesta);
                    
                    me.arrayLibrocompras = respuesta.librocompras.data;
                    me.pagination= respuesta.pagination;
                    //console.log(me.arrayLibrocompras);
                    
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
                me.listarFacturas(page,buscar,me.messelected,me.anioselected);
                
            },
            actualizarLibrocompra(){
               
                let me = this;
                var total=me.importetotal-me.nocreditofiscal-me.descuentos;
                var _13porciento=parseFloat((total * 0.13)).toFixed(2);
                var _87porciento=(parseFloat(total)-parseFloat(_13porciento)).toFixed(2);
                
                if(me.asientomaestro==1)
                {
                    if(me.resimportetotal!=me.importetotal || me.resnocreditofiscal!=me.nocreditofiscal || me.resdescuentos!=me.descuentos)
                    {
                        var modificado_montos=1;
                    }
                }

                axios.put('/con_librocompras/actualizar',{
                    'fechafactura':me.fechafactura,
                    'idproveedor':me.idproveedor[0],
                    'numfactura':me.numfactura,
                    'numautorizacion':me.numautorizacion,
                    'codcontrol':me.codcontrol,
                    'importetotal':me.importetotal,
                    'nocreditofiscal':me.nocreditofiscal,
                    'descuentos':me.descuentos,
                    '_13porciento':_13porciento,
                    '_87porciento':_87porciento,
                    'idlibrocompra':me.idlibrocompra,
                    'idasientomaestro':me.idasientomaestro,
                    'modificado_montos':modificado_montos,
                    'detalle':me.detalle,
                    'idfilial':me.filialselected,
                }).then(function (response) {
                    if(response.data.length){
                    swal(
                            'El Valor ya Existe',
                            'Ingresa un dato Diferente',
                            'error'
                       )
                    }
                    else{
                        swal(
                            'Actualizado correctamente'
                        )
                        me.detalle='';
                        me.classModal.closeModal('librocompras'); 
                        me.listarFacturas(1,'',me.messelected,me.anioselected,me.filialselected);
                    }
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
           
        },
        mounted() {
            this.fechahoy();
            this.listarFacturas(1,this.buscar,this.messelected,this.anioselected,this.filialselected);
            this.mescerrado();
            this.selectfilial();
            this.classModal=new _pl.Modals();
            this.classModal.addModal('librocompras');
            this.classModal.addModal('proveedor');
            this.getRutasReports();
            this.getPermisos();
            
        }
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
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
