<template ref="comprobanteprincipal">
    <main class="main">
        <!-- Breadcrumb -->
        <div class="breadcrumb" style="padding-top: 5px;padding-bottom: 5px;margin-bottom: 5px;">
            <div class="col-md-1" style="padding:0px">
                <button v-if="!divCompPrincipal" class="btn btn-outline-primary cui-arrow-left" 
                style="font-size:22px; padding:5px;" @click="listacomprobantes()"></button>
            </div>
            <div class="col-md-10 text-center">
                <h4 v-if="divCompPrincipal" class="nombrecliente">Comprobantes Contables</h4>
            </div>
        </div>
        <div class="container-fluid" style="padding-right: 5px;padding-left: 5px;">
            <!-- Ejemplo de tabla Listado -->
            <div class="card" v-if="divCompPrincipal">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Asientos Contables
                    <button v-if="check('agregar')" type="button" @click="cargarvue('nuevo')" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                        <div class="form-group row">
                        <div class="col-4">
                            <span class="input-group-prepend">
                                <input class="form-control" type="text" v-model="buscar" placeholder="Buscar"  @keyup.enter="listarAsientoMaestro(1,criterio,borradorcheck,buscar)" v-on:focus="selectAll">
                                <button class="btn btn-primary" type="button" @click="listarAsientoMaestro(1,criterio,borradorcheck,buscar)">
                                        <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                        <div class="col-4">
                            <!-- <label class="col-4"><strong>Tipo:</strong></label> -->
                                <select class="form-control" v-model="criterio" @change="listarAsientoMaestro(1,criterio,borradorcheck,buscar)">
                                    <option v-for="tipocomprobante in arrayTipocomprobante" :key="tipocomprobante.idtipocomprobante" :value="tipocomprobante.idtipocomprobante" v-text="tipocomprobante.nomtipocomprobante"></option>
                                </select>
                        </div>
                        <div class="col-4 col-form-label " style="border: 1px solid #c2cfd6 !important; border-radius: 5px;">
                            <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" v-model="borradorcheck" value="validado" checked @change="listarAsientoMaestro(1,criterio,borradorcheck,buscar)">Validados
                            </label>
                            </div>
                            <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" v-model="borradorcheck" value="borrador" @change="listarAsientoMaestro(1,criterio,borradorcheck,buscar)">Borradores
                            </label>
                            </div>
                        </div>
                    </div>
                    <template v-if="borradorcheck=='validado'">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th><div class="size">Opciones</div></th>
                                    <th style="width:80px"># Compr.</th>
                                    <th> <div class="size2"> Fecha Registro</div></th>
                                    <th>Nombre Perfil</th>
                                    <!--  <th>Tipo Documento</th>
                                        <th>Num Documento</th>
                                    
                                    <th>Filial </th>-->
                                    <th>Glosa </th> 
                                    <th>Importe</th> 
                                    <th>Modulo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="asientomaestro in arrayAsientoMaestro" :key="asientomaestro.idasientomaestro" v-bind:class="[asientomaestro.estado==4 ? 'table-info' :true , false]" >
                                    <td>
                                        <button type="button"  @click="abrirAsientoMaestro(asientomaestro.idasientomaestro)" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Ver Comprobante">
                                            <i class="icon-eye"></i>
                                        </button> 
                                        <template v-if="asientomaestro.estado==1 && asientomaestro.idrevertido==null">
                                            <button  v-if="criterio!=2 && check('revertir')" type="button" @click="revertirAsiento(asientomaestro)" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Revertir">
                                                <i class="icon-shuffle"></i><!-- fa fa-exchange   icon-shuffle -->
                                            </button>
                                            <button type="button" v-if="check('copiar')" @click="cargarvue('copiar',asientomaestro)" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Copiar">
                                                <i class="icon-docs"></i><!-- fa fa-exchange   icon-shuffle -->
                                            </button>      
                                        </template>
                                        <button type="button" v-if="check('editar_cabecera')" @click="abrirmodalcabecera(asientomaestro)" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar Cabecera">
                                            <i class="cui-bold"></i><!-- fa fa-exchange   icon-shuffle -->
                                        </button>
                                    </td>
                                    <td v-text="asientomaestro.cod_comprobante" ></td>
                                    <td v-text="asientomaestro.fecharegistro"></td>
                                    <td v-text="asientomaestro.nomperfil"></td>
                                    <!--  <td v-text="asientomaestro.tipodocumento"></td>
                                    <td v-text="asientomaestro.numdocumento"></td>
                                    <td v-text="asientomaestro.idfilidal"></td> -->
                                    <td v-text="asientomaestro.glosa"></td>
                                    <td style="text-align: right">{{asientomaestro.total|currency}}</td>
                                    <td v-text="asientomaestro.nommodulo"></td>
                                </tr>                                
                            </tbody>
                        </table>
                    </template>
                    <template v-else-if="borradorcheck=='borrador'"> 
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th><div class="size">Opciones</div></th>
                                    <th><button @click="validarBorrador()" class="btn btn-success botonpadding">
                                            Validar
                                        </button>
                                    </th>
                                    <!-- <th>Tipo Documento</th>
                                    <th>Num Documento</th> -->
                                    <th>Glosa </th>
                                    <th>Importe </th>
                                    <th>Filial </th>
                                    <th> <div class="size2"> Fecha Registro</div></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="asientomaestro in arrayAsientoMaestro" :key="asientomaestro.idasientomaestro" v-bind:class="[asientomaestro.estado==4 ? 'table-info' :true , false]" >
                                    <td>
                                        <button v-if="check('editar_borrador')" type="button" @click="cargarvue('editar',asientomaestro)" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Editar">
                                            <i class="icon-note"></i>
                                        </button> 
                                        <button v-if="check('eliminar_borrador')" type="button" @click="eliminarBorrador(asientomaestro.idasientomaestro)" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                            <i class="icon-trash"></i>
                                        </button> 
                                        
                                        <template v-if="asientomaestro.estado==1">
                                            <button type="button" @click="revertirAsiento(asientomaestro)" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Revertir">
                                                <i class="icon-shuffle"></i><!-- fa fa-exchange   icon-shuffle -->
                                            </button>     
                                        </template>
                                    </td>
                                    <td><div style="text-align:center">
                                            <input type="checkbox" class="form-check-input"  v-model="checkValidacion" :value="asientomaestro.idasientomaestro" :disabled="asientomaestro.fact_modificada== 1">
                                            
                                        </div>
                                    </td>
                                    <!-- <td v-text="asientomaestro.tipodocumento"></td>
                                    <td v-text="asientomaestro.numdocumento"></td> -->
                                    <td v-text="asientomaestro.glosa"></td>
                                    <td style="text-align: right" v-bind:class="[asientomaestro.fact_modificada==1 ? 'table-danger' :true , false]">{{asientomaestro.total|currency}}</td>
                                    <!-- <td v-text="asientomaestro.total + ' Bs.'" style="text-align: right" v-bind:class="[asientomaestro.fact_modificada==1 ? 'table-danger' :true , false]"></td> -->
                                    <td v-text="asientomaestro.idfilidal"></td>
                                    <td v-text="asientomaestro.fecharegistro"></td>
                                </tr>                                
                            </tbody>
                        </table>
                    </template>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,criterio,borradorcheck)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page,criterio,borradorcheck)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,criterio,borradorcheck)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <con_comprobante @cerrarmanual="cerrarvuemanual" ref="vuecomprobante"></con_comprobante>
        </div>
        <!-- MODAL EDITAR CABECERA  -->
        <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="cabecera"  data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-primary modal-lg">
                <div class="modal-content animated fadeIn">
                    <div class="modal-header">
                        <h4 class="modal-title">Editar Cabecera Comprobante de {{`${edit_nomtipocomprobante} Nº ${edit_cont_comprobante}   por: Bs. ${edit_monto}`}}</h4>
                        <button class="close" @click="cerrarmodalcabecera()">x</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label ><strong>Documento:</strong></label>
                                <input type="text" 
                                            v-model="edit_documento"
                                            class="form-control formu-entrada" 
                                            v-validate.initial="'required'"
                                            name="Documento">
                                        <span class="text-error">{{ errors.first('Documento')}}</span>
                            </div>
                            <div class="form-group col-md-6">
                                <label ><strong>Nº Documento:</strong></label>
                                <input type="text" 
                                            v-model="edit_numdocumento"
                                            class="form-control formu-entrada" 
                                            v-validate.initial="'required'"
                                            name="num documento">
                                        <span class="text-error">{{ errors.first('num documento')}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row col-md-12">
                                <label class="col-md-12"><strong>Glosa:</strong></label>
                                <div class="col-md-12">
                                    <textarea 
                                        :class="{'form-control': true, 'is-invalid textareaerror': errors.has('glosa')}"  
                                        rows="4" 
                                        v-model="edit_glosa"
                                        name="glosa"
                                        v-validate.initial="'required'" >
                                    </textarea>
                                    <span class="text-error">{{ errors.first('glosa')}}</span> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarmodalcabecera()">Cerrar</button>
                        <button :disabled="!iscompleteedit" class="btn btn-primary" @click="editarcabecera()">Editar Cabecera</button>
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- fin modal EDITAR CABECERA -->
    </main>
</template>

<script>
    Vue.component("con_comprobante", require("./con_comprobante.vue").default);

    import VueCurrencyFilter from 'vue-currency-filter'
    Vue.use(VueCurrencyFilter,
    {
    symbol : '',
    thousandsSeparator: '.',
    fractionCount: 2,
    fractionSeparator: ',',
    symbolPosition: 'front',
    symbolSpacing: true
    })
    import VueBarcode from 'vue-barcode'; 
    import VeeValidate from 'vee-validate';
    import vSelect from 'vue-select'; 

    import VueNumeric from 'vue-numeric'
    import * as plugin from '../../functions.js';

    Vue.component('v-select',vSelect);
    Vue.use(VueNumeric);
 
    export default {
        props:['idmodulo','idventanamodulo'],
        data (){
            return {
                divCompPrincipal:1,
                arrayAsientoMaestro : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 1,
                buscar : '',
                reporte_asiento_automatico:'',
                arrayTipocomprobante:[],
                borradorcheck:'validado',
                checkValidacion:[],
                arrayPermisos:{agregar:0,
                                revertir:0,
                                copiar:0,
                                editar_borrador:0,
                                eliminar_borrador:0,
                                editar_cabecera:0
                                },
                
                arrayPermisosIn:[],
                edit_documento:'',
                edit_numdocumento:'',
                edit_glosa:'',
                edit_cod_comprobante:'',
                edit_monto:0,
                edit_nomtipocomprobante:'',
                edit_cont_comprobante:0,
                edit_idasientomaestro:0,
            }
        },
        components: {
        'barcode': VueBarcode
        },
        formOptions:{
                validateAfterLoad: true,
                validateAfterChanged: true
            },
        computed:{
            iscompleteedit(){
                let valor=false;
                let me=this;
                    if(me.edit_documento && me.edit_numdocumento && me.edit_glosa)
                        valor=true;
                    else
                        valor=false;
                return valor
            },
            isActived: function(){
                return this.pagination.current_page;
            },
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
            cerrarvuemanual(){
                this.listarAsientoMaestro(1,this.criterio,this.borradorcheck);
                $('#divcomprobante').css('display','none');
                //console.log('hola');
                this.divCompPrincipal=1;
                
                
            },
            abrirmodalcabecera(data=[]){
                let me=this;
                me.edit_documento=data['tipodocumento'];
                me.edit_numdocumento=data['numdocumento'];
                me.edit_glosa=data['glosa'];
                me.edit_monto=data['total'];
                me.edit_cod_comprobante=data['cod_comprobante'];
                me.edit_cont_comprobante=data['cont_comprobante'];
                me.edit_nomtipocomprobante=data['nomtipocomprobante'];
                me.edit_idasientomaestro=data['idasientomaestro'];
                //console.log(data);
                me.classModal.openModal('cabecera');

            },
            cerrarmodalcabecera(){
                let me=this;
                me.edit_documento='';
                me.edit_numdocumento='';
                me.edit_glosa='';
                me.edit_monto=0;
                me.edit_cod_comprobante='';
                me.edit_cont_comprobante=0;
                me.edit_nomtipocomprobante='';
                me.edit_idasientomaestro=0;

                me.classModal.closeModal('cabecera'); 
            },
            editarcabecera(){
                let me=this;
                axios.put('/con_asientomaestro/editarcabecera',{
                    'idasientomaestro':me.edit_idasientomaestro,
                    'documento':me.edit_documento,
                    'numdocumento':me.edit_numdocumento,
                    'glosa':me.edit_glosa,
                }).then(function (response) {
                    me.cerrarmodalcabecera();
                    me.listarAsientoMaestro(1,me.criterio,me.borradorcheck);
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            selectAll: function (event) {
                setTimeout(function () {
                    event.target.select()
                }, 0)
            },    
            cargarvue(valor,idasientoma=[]){
                this.divCompPrincipal=0;
                var arrayValores=[]
                switch (valor) {
                    case 'nuevo':
                        arrayValores['idtipocomprobante']=this.criterio;
                        arrayValores['idmodulo']=this.idmodulo;
                        arrayValores['titulo']=this.arrayTipocomprobante[this.criterio-1].nomtipocomprobante;
                        this.$refs.vuecomprobante.cargarvue(arrayValores,'nuevo');
                    break;
                    case 'editar':
                        arrayValores['idtipocomprobante']=this.criterio;
                        arrayValores['idasientomaestro']=idasientoma;
                        arrayValores['titulo']=this.arrayTipocomprobante[this.criterio-1].nomtipocomprobante;
                        arrayValores['idmodulo']=this.idmodulo;
                        this.$refs.vuecomprobante.cargarvue(arrayValores,'editar');
                    break;
                    case 'copiar':
                        arrayValores['idtipocomprobante']=this.criterio;
                        arrayValores['idasientomaestro']=idasientoma;
                        arrayValores['titulo']=this.arrayTipocomprobante[this.criterio-1].nomtipocomprobante;
                        arrayValores['idmodulo']=this.idmodulo;
                        this.$refs.vuecomprobante.cargarvue(arrayValores,'copiar');
                    break;
                }
            },
            listacomprobantes(){
                this.divCompPrincipal=1;
                this.$refs.vuecomprobante.cerrarvue();
                this.listarAsientoMaestro(1,this.criterio,this.borradorcheck);
            },
            validarBorrador(){
                let me=this;
                //console.log(me.checkValidacion);
                if(me.checkValidacion.length==0)
                {
                    swal({
                        title: "Nada Seleccionado",
                        text: "Debe Seleccionar los Comprobantes a Validar",
                        type: "warning",
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Cerrar",
                    });
                }
                else
                {
                    swal({
                    title: 'Esta seguro de Validar '+ me.checkValidacion.length + ' Comprobantes?',
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
                        axios.put('/con_asientomaestro/validarborrador',{
                            'checkvalidacion': me.checkValidacion,
                            'idtipocomprobante':me.criterio,
                        }).then(function (response) {
                            me.listarAsientoMaestro(1,me.criterio,me.borradorcheck,me.buscar);
                            //console.log(1,me.criterio,me.borradorcheck);
                            swal(
                            'Validados!',
                            'El(los) comprobantes han sido Validados correctamente.',
                            'success'
                            )
                            me.checkValidacion=[];
                        }).catch(function (error) {
                            console.log(error);
                        });
                        
                        
                    } 
                    else if (result.dismiss === swal.DismissReason.cancel)
                    {
                        
                    }
                    })
                }
            },
            selectTipocomprobante(){
                let me=this;
                var url= '/con_tipocomprobante/selectTipocomprobante';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayTipocomprobante = respuesta.tipocomprobantes;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            eliminarBorrador(idasientomaestro){
                let me = this;
                var url= '/con_librocompras/verificarfactura?idasientomaestro='+idasientomaestro;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    
                    if(respuesta==1)
                        var texto='<p class="text-factura">El comprobante tiene Facturas Asociadas</p><p>Las Facturas Seran Eliminadas!!</>';
                    else
                        var texto=''
                    
                    swal({
                    
                    title: 'Esta seguro de Eliminar este Borrador?',
                    html:texto,
                    
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
                            
                            axios.put('/con_asientomaestro/eliminarborrador',{
                                'idasientomaestro': idasientomaestro,
                                'respuestafactura':respuesta
                            }).then(function (response) {
                                me.listarAsientoMaestro(1,me.criterio,me.borradorcheck,me.buscar);
                                //console.log(1,me.criterio,me.borradorcheck);
                                swal(
                                'Desactivado!',
                                'El Borrador ha sido eliminado correctamente.',
                                'success'
                                )
                            }).catch(function (error) {
                                console.log(error);
                            });
                            
                            
                        } else if (result.dismiss === swal.DismissReason.cancel)
                        {
                            
                        }
                    })
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            revertirAsiento(data = [])
            {
                let me=this;
                var idasientomaestro=data['idasientomaestro'];
                var glosaobservado="Reversión de Comprobante de "+data['nomtipocomprobante'] + " Nº " + data['cod_comprobante'];
                var fecharegistro=data['fecharegistro'];
                var fechamin = fecharegistro.split(" ");

                swal({
                    title: 'Esta seguro de Revertir Este comprobante?',
                    html:   '<div class="form-group row "> <label style="text-align: left; align-items: center;" class="col-md-5 form-control-label" for="text-input">Fecha de Reversion : </label>' +
                            '<div class="col-md-7">  <div class="input-group"> <input class="form-control" id="fechareversion"   type="text"    placeholder="Fecha de reversion" autocomplete="off"></div> </div> </div>' +
                            '<div class="form-group row "> <label style="text-align: right; align-items: center;" class="col-md-2 form-control-label" for="text-input">Glosa : </label>' +
                            '<div class="col-md-10"> <div class="input-group"> <input  id="glosareversion" type="text"  class="form-control" value="'+glosaobservado+'"  step="any"></div> </div></div>',
                    
                    type: 'warning',
                    showCancelButton: true,
                    allowOutsideClick: () => false,
                    allowEscapeKey: () => false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!',
                    cancelButtonText: 'Cancelar',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    reverseButtons: true,
                    onOpen:function(){
                        if(document.getElementById('glosareversion').value!='')
                        {
                            swal.enableConfirmButton();
                            //me.glosaobservado=oInput;
                        }
                        else swal.disableConfirmButton();
                        
                        _pl.datapicker(
                        "fechareversion",
                        fechamin[0],       //limite inferior
                        me.fechaactual,     // limite superior
                        me.fechaactual      //fecha que muestra el date
                        );
                    },
                    preConfirm: () => {
                        //console.log();
                        return [document.getElementById('fechareversion').value,
                                document.getElementById('glosareversion').value
                        ]
                    },
                }).then((result) => {
                    if (result.value) {
                        //console.log(result.value[0]);
                        axios.post('/con_asientomaestro/revertir',{
                            'idasientomaestro': idasientomaestro,
                            'idmodulo':this.idmodulo,
                            'fechareversion':result.value[0],
                            'glosareversion':result.value[1]
                            
                            
                        }).then(function (response) {
                            //me.listarDocumento(1,'','nomdocumento');
                            me.listarAsientoMaestro(1,me.criterio,me.borradorcheck,me.buscar);
                            //console.log(response.data)
                            swal(
                            'Revertido!',
                            'El Comprobante ha sido Revertido correctamente.',
                            'success'
                            )
                        }).catch(function (error) {
                            console.log(error);
                        });
                    } else if (result.dismiss === swal.DismissReason.cancel) 
                    {
                        
                    }
                }) 
            },
            
            getRutasReports (){ 
                let me=this;
                var url= '/con_reportes';
                axios.get(url).then(function (response) {
                        var respuesta= response.data; ;
                    me.reporte_asiento_automatico = respuesta.REP_ASIENTO_AUTOMATICO; 
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            abrirAsientoMaestro(asientomaestro){
                let me=this;
                var url=me.reporte_asiento_automatico + asientomaestro+'&tiposubcuenta=1'; 
                //me.abrirVentanaModalURL(url,"reporte_asiento_automatico",800,700);	
                plugin.viewPDF(url,'Reporte Asiento Automatico');

            },
            //metodo agregado para la validacion
            listarAsientoMaestro (page,criterio,borradorcheck,buscar=''){
                //console.log(borradorcheck);
                let me=this;
                var url= '/con_asientomaestro?page=' + page + '&borradorcheck='+ borradorcheck + '&criterio='+ criterio+'&buscar='+buscar;
                //console.log(url);
                
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayAsientoMaestro = respuesta.asientomaestros.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page,criterio,borradorcheck){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarAsientoMaestro(page,criterio,borradorcheck,me.buscar);
            },
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
        },
        mounted() {
            this.listarAsientoMaestro(1,this.criterio,this.borradorcheck);
            this.getRutasReports();
            this.getPermisos();
            this.selectTipocomprobante();
            this.classModal=new _pl.Modals();
            this.classModal.addModal('cabecera');
        }
    }
</script>
<style>  
    .div-tabla{
        margin-bottom: 0rem;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-style: italic;
    }
    .text-factura{
        color:red !important;
        font: size 1,5rem;
    }
    .sizecuenta{
        width:80px !important;
    }
    .sizemoneda{
        width: 30px;
    }
    .sizesubcuenta{
        width: 40px;
    }
    .sizedebehaber{
        width: 40px
    }
    #input-subcuenta::placeholder{
        color: red;
        opacity: 1; /* Firefox */
    }
    #input-cuenta::placeholder{
        color: red;
        opacity: 1; /* Firefox */
    }
    #input-documento::placeholder{
        color: red;
        opacity: 1; /* Firefox */
    }
    .size{
    width: 8em;
    }
    .size2{
    width: 10em;
    }
    .input-number{
        text-align:right;
        width: 100%;
        height: 27px;
    }
    .input-text{
        text-align:left;
        width: 100%;
        padding-left: 7px;
        padding-right: 7px;
        height: 28px;
    }
    .ancho20{
        width: 20%;
    }
    .ancho12{
        width:12%;
        padding-bottom: 5px;
        padding-bottom: 1px;
    }
    .ancho10{
        width:10%;
    }
    .ancho70{
        width:70%;
        padding-right: 15px;
    }
    .ancho12c{
        text-align: center;
        width:12%;
    }
    .ancho10c{
        text-align: center;
        width:10%;
    }
    .ancho20c{
        text-align: center;
        width: 20%;
        
    }
    .botonpadding{
        padding-top: 2px;
        padding-bottom: 2px;
        
    }
    .ancho6c{
        text-align: center;
        width:6%;
    }
    .ancho6{
        width:6%;
    }
    .padding5{
        margin-bottom: 5px;
        padding-left: 5px;
        padding-right: 5px;

    }
    hr{
        border-top: 2px solid rgba(112, 159, 181, 0.52);
    }
    .headerpadding{   
    padding-top: 5px;
    padding-bottom: 5px;
    }
    .input-importe{
        text-align: right;
    }
</style>
