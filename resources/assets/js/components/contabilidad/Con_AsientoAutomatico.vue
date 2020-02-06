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
                <i class="fa fa-align-justify"></i> Asientos Contables Automaticos
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <strong class="col-md-4">Seleccione Modulo:</strong>
                            <select class="form-control col-md-8" v-model="idmodulo" @change="listarPerfil()">
                                <option value="0">Seleccionar</option>
                                <option v-for="modulo in arrayModulo" :key="modulo.idmodulo" :value="modulo.idmodulo" v-text="modulo.nommodulo"></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <strong class="col-md-4">Seleccione Perfil:</strong>
                            <select class="form-control col-md-8" v-model="idperfil" @change="listarAsientomaestro(1,idmodulo,idperfil)">
                                <option value="0">Seleccionar</option>
                                <option v-for="perfil in arrayPerfilcuentamaestro" :key="perfil.idperfilcuentamaestro" :value="perfil.idperfilcuentamaestro" v-text="perfil.nomperfil"></option>
                            </select>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Glosa</th>
                            <th># Documento</th>
                            <th>Documento</th>
                            <th>Fecha de Registro</th>
                            <th>comprobante</th>
                            <th>Monto</th>
                            <th>Agrupar</th>
                            <!--<th>Estado</th>-->
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="asientomaestro in arrayAsientomaestro" :key="asientomaestro.idasientomaestro" v-bind:class="[asientomaestro.estado==3 ? 'table-danger' :true , false]">
                            <td>
                                <button type="button" v-if="asientomaestro.estado!=3" @click="abrirModal('asientodetalle','detalle',asientomaestro)" class="btn btn-warning btn-sm" title="Asiento Contable" :disabled="asientomaestro.idtipocomprobante==2 && asientomaestro.desembolso==0">
                                    <i class="cui-dashboard"></i>
                                </button> 
                                 <button v-else type="button"  @click="observado_view(asientomaestro.observaciones)" class="btn btn-danger btn-sm" title="Ver Observacion">
                                    <i class="icon-eye"></i>
                                </button> &nbsp;
                              <!--  <template v-if="asientomaestro.activo">
                                    <button type="button" class="btn btn-danger btn-sm" @click="desactivarAsienarrayAsientomaestro(asientomaestro.idasientomaestro)">
                                        <i class="icon-trash"></i>
                                    </button>
                                </template>
                                <template v-else>
                                    <button type="button" class="btn btn-info btn-sm" @click="activarAsienarrayAsientomaestro(asientomaestro.idasientomaestro)">
                                        <i class="icon-check"></i>
                                    </button>
                                </template>
                                -->
                            </td>
                            <td v-text="asientomaestro.glosa"></td>
                            <td v-text="asientomaestro.numdocumento"></td>
                            <td v-text="asientomaestro.tipodocumento"></td>
                            <td v-text="asientomaestro.fecharegistro"></td>
                            <td v-text="asientomaestro.nomtipocomprobante"></td>
                            <td >{{asientomaestro.sdebe | currency }}</td>
                            <td>
                                <label class="switch switch-label switch-pill switch-outline-primary-alt">
                                    <input class="switch-input" type="checkbox" unchecked="" v-model="checkValidacion" :value="asientomaestro.idasientomaestro" :disabled="(asientomaestro.idtipocomprobante==2 && asientomaestro.desembolso==0) || asientomaestro.estado==3" >
                                    <span class="switch-slider" data-checked="Si" data-unchecked="No"></span>
                                </label>
                            </td>
                        </tr>  
                        <tr v-if="idperfil!=0 && arrayAsientomaestro.length!=0">
                            <td colspan="7" style="text-align:right"></td>
                            <td style="text-align:center"><button class="btn btn-primary" type="button" @click="abrirModalAgrupacion()" :disabled="checkValidacion.length<=1">
                                        Agrupar
                                </button>
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
    <form @submit.prevent="validateBeforeSubmit" @keyup.esc="cerrarModal('individual')">
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" class="close" @click="cerrarModal('individual')"  aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" @submit.prevent="validateBeforeSubmit" >
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label"><b>Comprobante de: </b> </label>
                            <template v-if="tipocomprobante">
                                <b><label class="col-md-9" v-text="' '+tipocomprobante"></label></b>
                            </template>
                            <template v-else>
                                <div class="col-md-6">
                                        <select class="form-control" 
                                                v-model="idtipocomprobante"
                                                v-validate.initial="'required'"
                                                name="tipocomprobante">

                                            <option value="0">Seleccione</option>
                                            <option v-for="tipocomprobantes in arrayTipocomprobante" :key="tipocomprobantes.idtipocomprobante" :value="tipocomprobantes.idtipocomprobante" v-text="tipocomprobantes.nomtipocomprobante"></option>
                                        </select>                                        
                                        <span class="text-error">{{ errors.first('tipocomprobante')}}</span>   <!--Lineas Agregadas<-->
                                    </div>
                            </template>

                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 form-control-label" ><b>Documento: </b></label>
                            <template v-if="tipodocumento">
                                <label class="col-md-4 form-control-label" v-text="' '+tipodocumento"></label>
                            </template>
                            <template v-else>
                                <div class="col-md-4">
                                    <input  v-validate.initial="'required'"
                                            type="text" 
                                            v-model="tipodocumento1" 
                                            class="form-control" 
                                            placeholder="Tipo Documento"
                                            name="Tipo Documento">   
                                    <span class="text-error">{{ errors.first('Tipo Documento')}}</span>   <!--Lineas Agregadas<-->                                     
                                </div>
                            </template>
                            
                            <label class="col-md-2 form-control-label" ><b>Num. Doc.:</b></label>
                            <template v-if="numdocumento">
                                <label class="col-md-4 form-control-label"  v-text="' '+numdocumento"></label>
                            </template>
                            <template v-else>
                                <div class="col-md-4">
                                    <input  v-validate.initial="'required'"
                                            type="text" 
                                            v-model="numdocumento1" 
                                            class="form-control" 
                                            placeholder="Num Documento"
                                            name="Num Documento">   
                                    <span class="text-error">{{ errors.first('Num Documento')}}</span>   <!--Lineas Agregadas<-->                                     
                                </div>
                            </template>
                            
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 form-control-label" ><b>Fecha: </b></label>
                            <div class="form-group col-sm-4">
                                    <input class="form-control formu-entrada" 
                                    v-validate.initial="'required'"
                                    type="date" 
                                    v-model="fecharegistro"
                                    name="fecha registro"
                                    v-bind:max="fechanow"
                                    >
                                <p class="text-error">{{ errors.first('fecha registro')}}</p>
                            </div>

                            <label class="col-md-2 form-control-label" ><b>Perfil: </b></label>
                            
                            <label class="col-md-4 form-control-label"  v-text="' '+ nomperfil"></label>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 form-control-label" ><b>Glosa: </b></label>
                            <div class="col-md-10 form-control-label">
                                    <input  v-validate.initial="'required'"
                                            type="text" 
                                            v-model="glosa" 
                                            class="form-control" 
                                            placeholder="glosa"
                                            name="glosa">   
                                    <span class="text-error">{{ errors.first('glosa')}}</span>   <!--Lineas Agregadas<-->                                     
                            </div>
                            
                        </div>
                        <div class="form-group row">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Cod Cuenta</th>
                                        <th colspan="4">Cuenta</th>
                                        <th>Debe</th>
                                        <th>Haber</th>
                                        <th>T/C</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="asientodetalle in arrayAsientoDetalle" :key="asientodetalle.idasientodetalle">
                                        
                                        <td v-text="asientodetalle.codcuenta"></td>
                                        <td colspan="4" v-text="asientodetalle.nomcuenta" ></td>
                                        <template v-if="asientodetalle.monto>=0">
                                            <td v-text="formatearnumero(asientodetalle.monto)" style="text-align:right"></td>
                                            <td></td>
                                            <td style="text-align:right">1</td>
                                        </template>
                                        <template v-else>
                                            <td></td>
                                            <td  v-text="formatearnumero(asientodetalle.monto*-1)" style="text-align:right"></td>
                                            <td style="text-align:right">1</td>
                                        </template>
                                    </tr> 
                                    <tr>
                                        <td colspan="5" style="text-align:right"><strong>Totales</strong></td>
                                        <td style="text-align:right"><b v-text="formatearnumero(total)" ></b></td>
                                        <td style="text-align:right"><b v-text="formatearnumero(total)" ></b></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="8" v-text="'Son: '+totalliteral" ></td> 
                                    </tr>
                                                               
                                </tbody>
                            </table>
                        </div>



                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                <template v-if="estado==3 ">
                   
                    <div>
                        <label for="" class="text-error">COMPROBANTE OBSERVADO:<br></label>
                        <label for="" v-text="observacion"></label>
                    </div>
                     <div>
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('individual')">Cerrar</button>
                    </div>
                </template>
                <template v-else>
                   <div>
                        <button type="button" v-if="idtipocomprobante!=2" class="btn btn-danger" @click="ObservarAsiento()">Observar Asiento</button>
                    </div>
                    <div>
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('individual')">Cerrar</button>
                        <!-- modificar boton aceptar -->
                        <button :disabled="!isComplete" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="validarAsiento()">Validar</button>
                    </div>
                </template>
                    
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    </form>
    <!--Fin del modal-->
    <!-- modal de agrupacion -->
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true" id="agruparcomprobantes">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="form-control-label col-md-6" v-text="'Comprobante de: '+ tipocomprobante"> </h4>
                    <div class="input-group col-md-5">
                        <span style="padding-top: 5px;padding-right: 10px;">Fecha Transaccion:</span>
                        <input  type="date" 
                                v-model="fechatransaccion" 
                                class="form-control" 
                                :min="fecha_solicitud"
                                :max="fechanow"
                                v-validate.initial="'required'"
                                name="Fecha Transaccion"
                                style="margin-top: 0px;font-size: 1rem;" focus>  <!--  -->
                        <span class="text-error">{{ errors.first('Fecha Transaccion') }}</span>
                    </div>
                    <button type="button" class="close" @click="cerrarModal('agrupar')"  aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-4">
                            <label class="form-control-label" ><b>Perfil: </b></label>
                            <label class="form-control-label"  v-text="' '+ nomperfil"></label>
                        </div>
                        <div class="col-4">
                            <label class="form-control-label" ><b>Documento: </b></label>
                            <input  v-validate.initial="'required'"
                                    type="text" 
                                    v-model="tipodocumento1" 
                                    class="form-control" 
                                    placeholder="Tipo Documento"
                                    name="Tipo Documento">   
                            <span class="text-error">{{ errors.first('Tipo Documento')}}</span>   <!--Lineas Agregadas<-->
                            
                        </div>
                        <div class="col-4">
                            <label class=" form-control-label" ><b>Num. Doc.:</b></label>
                            <input  v-validate.initial="'required'"
                                    type="text" 
                                    v-model="numdocumento1" 
                                    class="form-control" 
                                    placeholder="Num Documento"
                                    name="Num Documento">   
                            <span class="text-error">{{ errors.first('Num Documento')}}</span>        
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label" ><b>Glosa: </b></label>
                        <div class="col-md-10 form-control-label">
                            <input  v-validate.initial="'required'"
                                    type="text" 
                                    v-model="glosa" 
                                    class="form-control" 
                                    placeholder="glosa"
                                    name="glosa">   
                            <span class="text-error">{{ errors.first('glosa')}}</span>   <!--Lineas Agregadas<-->                                     
                        </div>
                    </div>
                    <div class="form-group row" style="padding-left: 15px;padding-right: 15px;">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Cod Cuenta</th>
                                    <th colspan="8">Cuenta</th>
                                    <th style="text-align:center">Debe</th>
                                    <th style="text-align:center">Haber</th>
                                    <th>T/C</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="cuentasdebe in arrayDebe" :key="cuentasdebe.index">
                                    <td ><b>{{cuentasdebe.codcuenta}}</b></td>
                                    <td colspan="8" ><b>{{cuentasdebe.nomcuenta}}</b></td>
                                    <td  style="text-align:right"><b>{{cuentasdebe.sdebe|currency}}</b></td>
                                    <td></td>
                                    <td style="text-align:right"><b>1</b></td>
                                </tr>
                                <tr v-for="cuentasdetalle in arrayDetalles" :key="cuentasdetalle.index">
                                    <td></td>
                                    
                                    <td colspan="2">&emsp;<small> {{cuentasdetalle.numpapeleta}}</small></td>
                                    <td colspan="4"><small>{{cuentasdetalle.nombres}}</small></td>
                                    <td ><small>{{cuentasdetalle.documento}}</small></td>
                                    <td><small>{{ cuentasdetalle.sdebe|currency }}</small> </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                               </tr> 
                                <tr v-for="cuentashaber in arrayHaber" :key="cuentashaber.index">
                                    <td ><b>{{cuentashaber.codcuenta}}</b></td>
                                    <td colspan="8"><b>{{cuentashaber.nomcuenta}}</b></td>
                                    <td ></td>
                                    <td style="text-align:right"><b>{{cuentashaber.shaber|currency}}</b></td>
                                    <td style="text-align:right"><b>1</b></td>
                                </tr>
                                 <tr>
                                    <td colspan="9" style="text-align:right"><strong>Totales:</strong></td>
                                    <td style="text-align:right"><b>{{total|currency}} </b></td>
                                    <td style="text-align:right"><b >{{total|currency}}</b></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="12" v-text="'Son: '+totalliteral" ></td> 
                                </tr>
                                                            
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('agrupar')">Cerrar</button>
                        <!-- modificar boton aceptar -->
                        <button :disabled="!isCompleteagrupacion" type="submit" class="btn btn-primary" @click="registrarAgrupacion()">Validar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- fin del modal agrupacion -->

    <!-- <button type="button" class="btn btn-secondary" @click="pruebatiempo()">prueba tiempo</button> -->
                        <!-- modificar boton aceptar -->
        
    </main>

</template>

<script>

    import Vue from 'vue';
    import VeeValidate from 'vee-validate';
    import * as plugin from '../../literal.js';
    import * as plugin2 from '../../functions.js';
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
    

    export default {
        data (){
            return {
                asientomaestro_id: 0,
                glosa : '',
                nomperfil:'',
                numdocumento:'',
                tipodocumento:'',
                fecharegistro:'',
                tipocomprobante:'',
                arrayAsientomaestro : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorDepartamento : 0,
                errorMostrarMsjDepartamento : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nomdepartamento',
                buscar : '',

                arrayModulo:[],
                idmodulo:0,
                arrayAsientoDetalle:[],
                arrayTipocomprobante:[],
                idtipocomprobante:0,
                fechaactual: new Date(),
                fechanow:'',
                numeroliteral:null,
                total:0,
                totalliteral:'',
                tipodocumento1:'',
                numdocumento1:'',
                reporte_asiento_automatico:'',
                observado:'',
                estado:0,
                observacion:'',
                arrayPerfilcuentamaestro:[],
                idperfil:0,
                checkValidacion:[],
                arrayPerfilDetalle:[],
                arrayMaestros:[],
                arrayDetalles:[],
                fechacomprobantes:[],
                fechatransaccion:'',
                fecha_solicitud:'',
                //fechaactual:'',
                c:0,

            }
        },

        formOptions:{
            validateAfterLoad: true,
            validateAfterChanged: true
        },

        computed:{
            isCompleteagrupacion(){
                var valor=false;
                let me=this;
                if(me.fechatransaccion && me.tipodocumento1 && me.numdocumento1 && me.glosa)
                    valor=true;
                
                return valor;

            },
            verificarfecha(){
                let me=this;
                me.checkValidacion.forEach(element => {
                    me.arrayAsientomaestro.idasientomaestro
                });
                

            },
            arrayDebe(){
                let me=this;
                var arrayResultado = me.arrayMaestros.filter( element=> {
                if( element.sdebe != null ){
                    return element;
                }
                });
               /*  me.total=me.arrayResultado[0].sdebe;
                me.totalliteral=plugin.numero_a_literal(me.total); */

                return arrayResultado

            },
            arrayHaber(){
                let me=this;
                var arrayResultado = me.arrayMaestros.filter( element=> {
                if( element.shaber != null ){
                    return element;
                }
                });
                return arrayResultado
            },
            isComplete () {
                //console.log( this.idperfilcuentamaestro && this.montodebito && this.obsdebito);
                var correcto=false;
                if(this.idtipocomprobante!=0)
                {
                    if(this.numdocumento || this.numdocumento1)
                    {
                        if(this.tipodocumento || this.tipodocumento1)
                        {
                            correcto=true;
                        }
                        else
                        {
                        correcto=false;
                        }
                    }
                    else
                    {
                        correcto=false;
                    }
                }
                else
                {
                    correcto=false;
                }

                return correcto;
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
             observado_view(observado){
                //console.log(pr);
                let me=this;
                swal({
                title: '¡Observaciones!',
                html:   '<div class="form-group row "> ' +
                        '<div class="col-md-12"> <div class="input-group"> <textarea style="    background: transparent;"  class="form-control"  placeholder="Observaciones" autofocus readonly>'+
                        observado+'</textarea></div> </div></div>',
                type:"info",
                showCancelButton: false,
                confirmButtonColor: "#4dbd74",
                cancelButtonColor: "#f86c6b",
                confirmButtonText: "Ok", 
                buttonsStyling: true,
                reverseButtons: true
                });
            },
            registrarAgrupacion(){
                let me=this;
                var url='/con_asientomaestro/registraragrupacion';
                swal({
                              title: "Registrando los datos",
                              allowOutsideClick:  false,
                              allowEscapeKey: false,
                              onOpen: function () {   swal.showLoading();  }
                          }).catch(function(error){
                              swal.showValidationError('Request failed: ${error}')
                          });
                axios.post(url,{
                    'idperfilcuentamaestro':me.idperfil,
                    'tipocomprobante':me.idtipocomprobante,
                    'tipodocumento':me.tipodocumento1,
                    'numdocumento':me.numdocumento1,
                    'glosa':me.glosa,
                    'arrayDetalle':me.arrayMaestros,
                    'idmodulo':me.idmodulo,
                    'fecharegistro':me.fechatransaccion,
                    'listaids':me.checkValidacion
                }).then(function(){
                    swal('Adicionado correctamente','','success');
                    me.cerrarModal('agrupar');
                    me.checkValidacion=[];
                    me.listarAsientomaestro(1,me.idmodulo,me.idperfil);
                });
                
            },
            abrirModalAgrupacion(){
                let me=this;
                
                var url= '/con_asientomaestro/agruparcomprobante?listaagrupados='+me.checkValidacion;
                axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayMaestros = respuesta.arrayMaestros;
                me.arrayDetalles=respuesta.arrayDetalles;
                me.fechacomprobantes=respuesta.fechacomprobante;
                if(me.fechacomprobantes.length>1)
                {
                    swal(
                        'Las fechas de registro No son las mismas!',
                        'Debe agrupar comprobantes con la misma fecha.',
                        'error'
                        )
                }
                else{
                    //console.log(me.fechacomprobantes[0].fecha);
                    me.fechatransaccion=me.fechacomprobantes[0].fecha;
                    me.fecha_solicitud=me.fechatransaccion;
                    me.tipocomprobante=me.arrayAsientomaestro[0].nomtipocomprobante;
                    me.idtipocomprobante=me.arrayAsientomaestro[0].idtipocomprobante;
                    me.nomperfil=me.arrayAsientomaestro[0].nomperfil;
                    me.sumatotalagrupado(me.arrayMaestros);
                    me.classModal.openModal('agruparcomprobantes');
                }
            })
            .catch(function (response) {
                console.log(response);
            });

            },
            listarPerfil(){
                let me=this;
                me.idperfil=0;
                me.arrayAsientomaestro=[];
                var url= '/con_perfilcuentamaestro/selectPerfilcuentamaestro?idmodulo='+me.idmodulo;
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayPerfilcuentamaestro = respuesta.perfilcuentamaestros;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            ObservarAsiento(idasientomaestro)
            {
                let me=this;
                swal({
                title: 'Esta seguro de Observar Este Comprobante?',
                html:   '<div class="form-group row "> <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label" for="text-input">Obs.: </label>' +
                        '<div class="col-md-9"> <div class="input-group"> <textarea  id="observacionswal" class="form-control"  placeholder="Observaciones" autofocus step="any"></textarea></div> </div></div>',
                type: 'warning',
                showConfirmButton: true,
                allowOutsideClick: () => false,
                allowEscapeKey: () => false,
                showCancelButton: true,
                showLoaderOnConfirm: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                buttonsStyling: true,
                reverseButtons: true,
                onOpen:function(){
                    swal.disableConfirmButton();
                    $('#observacionswal').on("input",function(){
                        var oInput = this.value;
                        if(oInput!='')
                        {
                            swal.enableConfirmButton();
                            me.observado=oInput;
                        }
                        else swal.disableConfirmButton();
                    });

                },
                
                }).then((result) => {
                if (result.value) {
                    let me = this;
                    axios.put('/con_asientomaestro/observar',{
                        'idasientomaestro': me.asientomaestro_id,
                        'observado':me.observado
                    }).then(function (response) {
                        me.listarAsientomaestro(1,me.idmodulo,me.idperfil)
                        swal(
                        'Observado!',
                        'El registro ha sido Observado correctamente.',
                        'success'
                        )
                        me.cerrarModal('individual');
                    }).catch(function (error) {
                        console.log(error);
                    });
                } 
                else if (result.dismiss === swal.DismissReason.cancel)
                {
                    
                }
            }) 
        },
        formatearnumero(numero){
            //numero=Math.floor(numero) // solo parte entera
            numero=numero.toFixed(2) ;
            return numero;
        },
        sumatotal(arraydetalle=[]){
            let me= this;
            me.total=0
            arraydetalle.forEach(element => {
                if(element.monto>=0)
                    me.total=me.total+element.monto;
            });
            //console.log(me.total + ' total ');
            me.totalliteral=plugin.numero_a_literal(me.total);
        },
        sumatotalagrupado(arraymaestros=[]){
            //console.log('entrasumatotalagrupado');
            let me= this;
            me.total=0
            arraymaestros.forEach(element => {
                if(element.sdebe)
                    me.total=me.total+element.sdebe;
            });
            //console.log(me.total + ' total ');
            me.totalliteral=plugin.numero_a_literal(me.total);
        },
        validateBeforeSubmit() {
            this.$validator.validateAll().then((result) => {
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
        selectModulo(){
            let me=this;
            var url= '/par_modulo/selectModulo';
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayModulo = respuesta.modulos;
            })
            .catch(function (response) {
                console.log(response);
            });
        },
        obtenerfecha(){
            let me = this;
            var anio=me.fechaactual.getFullYear();//año
            var mes=me.fechaactual.getMonth()+1;//mes
            var dia = me.fechaactual.getDate(); //obteniendo dia
            
            // me.fechaanterior = new Date(me.anio+'-'+me.mes+'-01');
            // var ultimoDia = new Date(me.fechaanterior.getFullYear(),me.fechaanterior.getMonth()+1, 0);
            //me.fecha_aporte=me.anio+'-'+me.mes+'-'+ultimoDia.getDate();
            if(mes<10)
                mes='0'+mes;
            

            me.fechanow=anio+'-'+mes+'-'+dia;
            console.log(me.fechanow);
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
        listarAsientomaestro(page,idmodulo,idperfil){
            let me=this;
            me.arrayAsientomaestro=[];
            me.checkValidacion=[];
            var url= '/con_asientomaestro?page=' + page + '&idmodulo='+ idmodulo+'&idperfil='+idperfil;
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayAsientomaestro = respuesta.asientomaestros.data;
                me.pagination= respuesta.pagination;
                me.arrayPerfilDetalle=respuesta.perfildetalle;
            })
            .catch(function (response) {
                console.log(response);
            });
        },
        listarAsientodetalle(idmaestro){
            let me=this;
            var url= '/con_asientodetalle/selectasientodetalle?idasientomaestro=' + idmaestro;
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                // console.log(respuesta.data);
                me.arrayAsientoDetalle = respuesta.asientodetalles;
                me.sumatotal(me.arrayAsientoDetalle);
                //console.log(me.arrayAsientoDetalle);
            })
            .catch(function (response) {
                console.log(response);
            });
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
        cambiarPagina(page,buscar,criterio){
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            //Envia la petición para visualizar la data de esa página
            me.listarAsientomaestro(page,me.idmodulo,me.idperfil);
        },
        validarAsiento(){
            let me = this;
                if(me.tipodocumento1!='')
                    me.tipodocumento=me.tipodocumento1;
                if(me.numdocumento1!='')
                    me.numdocumento=me.numdocumento1;
            swal({
                              title: "Registrando los datos",
                              //html:'<div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: '+me.c+'0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>',
                              allowOutsideClick:  false,
                              allowEscapeKey: false,
                              onOpen: function () {   swal.showLoading();  }
                          }).catch(function(error){
                              swal.showValidationError('Request failed: ${error}')
                          });
            //me.pruebatiempo();
            axios.put('/con_asientomaestro/actualizar',{
                'idasientomaestro':me.asientomaestro_id,
                //'idtipocomprobante':me.idtipocomprobante,
                'fecharegistro':me.fecharegistro,
                'numdocumento':me.numdocumento,
                'tipodocumento':me.tipodocumento,
                'glosa':me.glosa
            }).then(function (response) {
                swal(
                        'Asiento Contable Validado Correctamente',
                    ) 
                    var url=me.reporte_asiento_automatico + me.asientomaestro_id+'&tiposubcuenta=1'; 
                    //me.abrirVentanaModalURL(url,"reporte_asiento_automatico",800,700);		                   
                    plugin2.viewPDF(url,'Reporte Asiento Automatico');
                    me.listarAsientomaestro(1,me.idmodulo,me.idperfil)
                    me.cerrarModal('individual');
                    //me.listarAsientos(1,'','nomdepartamento');
            }).catch(function (error) {
                console.log(error);
            });
        },
        cerrarModal(valor){
            switch (valor) {
                case 'individual':
                    {
                        this.modal=0;
                        this.tituloModal='';
                        this.nomdepartamento='';
                        //this.arrayAsientoDetalle=[];
                        this.total=0;
                        this.asientomaestro_id=0;
                        this.tipocomprobante='';
                        this.glosa='';
                        this.tipocodumento='';
                        this.tipodocumento1='';
                        this.fecharegistro='';
                        this.nomperfil='';
                        this.numdocumento='';
                        this.numdocumento1='';
                        this.idtipocomprobante=0;
                    }
                    
                break;
                case 'agrupar':
                    {
                        this.numdocumento1='';
                        this.glosa='';
                        this.tipodocumento1='';
                        this.idtipocomprobante=0;
                        this.classModal.closeModal('agruparcomprobantes');
                    }
                    
                break;
            }
        },
        abrirModal(modelo, accion, data = []){
            switch(modelo){
                case "asientodetalle":
                {
                    switch(accion){
                        case 'detalle':
                        {
                            this.modal = 1;
                            this.tituloModal = 'Vista Previa Asiento Contable ';
                            this.asientomaestro_id=data['idasientomaestro'];
                            this.tipocomprobante= data['nomtipocomprobante'];
                            this.glosa=data['glosa'];
                            this.idtipocomprobante=data['idtipocomprobante'];
                            this.estado=data['estado'];
                            this.observacion=data['observaciones'];

                            /*  if(data=['tipodocumento'])
                                {   */
                                    this.tipodocumento=data['tipodocumento'];
                            /*   }
                            else
                                {   
                                    this.tipodocumento1=data['tipodocumento'];                                
                                }*/

                            //var Date = '24/02/2009'; 
                            var elem = data['fecharegistro'].split(" "); 
                            var fecha = elem[0]; 
                            var hora = elem[1]; 
                            //año = elem[2];
                            this.fecharegistro = fecha;
                            this.nomperfil = data['nomperfil'];

                            /*if(data=['numdocumento'])
                            {*/
                                this.numdocumento=data['numdocumento'];
                            /* }
                            else
                            {
                                this.numdocumento1=data['numdocumento'];
                            }
*/
                            this.listarAsientodetalle(this.asientomaestro_id)
                            this.selectTipocomprobante();
                            this.tipoAccion=1;
                            
                            break;

                        }
                        case 'actualizar':
                        {
                            //console.log(data);
                            this.modal=1;
                            this.tituloModal='Actualizar Departamento';
                            this.tipoAccion=2;
                            this.departamento_id=data['iddepartamento'];
                            this.nomdepartamento = data['nomdepartamento'];
                            this.abrvdep = data['abrvdep'];
                            
                            break;
                        }
                    }
                }
            }
        },
 
        pruebatiempo(){
            let me=this;
            me.c=0;
            var myVar=setInterval(() => {
                me.c=me.c+1;
                this.holamundo()
                if(me.c==10)
                   clearInterval(myVar);
            }, 2000);
        },
        holamundo(){
            console.log('hola mundo');
            
        },
        
        
    },
    mounted() {
        //this.listarAsientos(1,this.buscar,this.criterio);
        this.selectModulo();
        this.obtenerfecha();
        this.getRutasReports();
        this.classModal=new _pl.Modals();
        this.classModal.addModal('agruparcomprobantes');
        
        
    }
}
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
        /*background-color: #2C3E50;*/
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
