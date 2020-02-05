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
                        <i class="fa fa-align-justify"></i> Abono de Aportes
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-6" v-model="criterio">
                                      <option value="numpapeleta">Num Papeleta</option>
                                      <option value="apaterno">Apellido Paterno</option>
                                      
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarSocio(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" title="Abono Aportes"   @click="listarSocio(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Num Papeleta</th>
                                    <th>Nombre Socio</th>
                                    <th>CI</th>
                                    <th>Fuerza</th>
                                    <th>Grado</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="socio in arraySocio" :key="socio.idsocio">
                                    <td>
                                        <button type="button" @click="abrirModal('aporte','registrar',socio)" class="btn btn-warning btn-sm">
                                          <i class="icon-calculator"></i>
                                        </button> &nbsp;
                                        <button type="button" @click="abrirModalDebito('debito','registrar',socio)" class="btn btn-warning btn-sm">
                                          <i class="icon-credit-card"></i>
                                        </button> &nbsp;
                                        

                                    </td>
                                    <td v-text="socio.numpapeleta"></td>
                                    <td v-text="socio.fullname"></td>
                                    <td v-text="socio.carnet"></td>
                                    <td v-text="socio.nomfuerza"></td>
                                    <td v-text="socio.nomgrado"></td>
                                    
                                    
                                    <td>
                                        <div v-if="socio.activo">
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
            <!--Inicio del modal agregar/actualizar
            <form @submit.prevent="validateBeforeSubmit">-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal +' '+ grado +' '+ nombrecompleto + ' '+ ' '+numeropapeleta"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body1">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Editar</th>
                                            <th>Tipo de Aporte</th>
                                            <th>Perfil de Cuenta</th>                     
                                            <th>Fecha de Aporte</th>
                                            <th>Importe</th>
                                            <th>Metodo Aporte</th>

                                            <th>Glosa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="aporte in arrayAporte" :key="aporte.idaporte">
                                            <td>
                                                <button type="button" @click="abrirModal('aporte','actualizar',aporte)" class="btn btn-warning btn-sm">
                                                <i class="icon-pencil"></i>
                                                </button>&nbsp;
                                               <template v-if="aporte.activo">
                                                    <button type="button" class="btn btn-danger btn-sm" @click="desactivarAporte(aporte.idaporte,aporte.idsocio)">
                                                        <i class="icon-trash"></i>
                                                    </button>
                                                </template>
                                                <!-- 
                                                <template v-else>
                                                    <button type="button" class="btn btn-info btn-sm" @click="activarAporte(aporte.idaporte,aporte.idsocio)">
                                                        <i class="icon-check"></i>
                                                    </button>
                                                </template>
                                                -->
                                                                                    
                                            </td>
                                            <td v-text="aporte.descripcion"></td>
                                            <td v-text="aporte.nomperfil"></td>
                                            <td v-text="aporte.fechaaporte"></td>
                                            <td v-text="aporte.aporte"></td>
                                            <td v-text="aporte.metodoaporte"></td>
                                            <td v-text="aporte.obsaporte"></td>
                                            <!--
                                            <td>
                                                <div v-if="aporte.activo">
                                                    <span class="badge badge-success">Activo</span>
                                                </div>
                                                <div v-else>
                                                    <span class="badge badge-danger">Desactivado</span>
                                                </div>
                                                
                                            </td>
                                            -->
                                            
                                        </tr>                                
                                    </tbody>
                                </table>
                            <div class="form-row">
                                <div class="form-group row col-sm-6">
                                    <label class="col-md-4 form-control-label" for="text-input">Tipo de Aporte</label>
                                    <div class="col-md-8">
                                        <select class="form-control" 
                                                v-model="idtipoaporte"
                                                v-validate.initial="'required'"
                                                
                                                name="Aporte">

                                            <option value="0">Seleccione</option>
                                            <option v-for="tipoaporte in arrayTipoaporte" :key="tipoaporte.idtipoaporte" :value="tipoaporte.idtipoaporte" v-text="tipoaporte.descripcion"></option>
                                        </select>                                        
                                        <span class="text-error">{{ errors.first('Aporte')}}</span>   <!--Lineas Agregadas<-->
                                    </div>
                                </div>
                                <div class="form-group row col-sm-6">
                                    <label class="col-md-4 form-control-label" for="text-input">Perfil de cuenta</label>
                                    <div class="col-md-8">
                                        <select class="form-control" 
                                                v-model="idperfilcuentamaestro"
                                                v-validate.initial="'required'"
                                                
                                                name="Perfilcuentamaestro">

                                            <option value="0">Seleccione</option>
                                            <option v-for="perfilcuentamaestro in arrayPerfilcuentamaestro" :key="perfilcuentamaestro.idperfilcuentamaestro" :value="perfilcuentamaestro.idperfilcuentamaestro" v-text="perfilcuentamaestro.nomperfil"></option>
                                        </select>                                        
                                        <span class="text-error">{{ errors.first('Perfilcuentamaestro')}}</span>   <!--Lineas Agregadas<-->
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group row col-sm-6">
                                    <label class="col-md-4 form-control-label" for="text-input">Tipo de Documento</label>
                                    <div class="col-md-8">
                                        <input class="form-control formu-entrada" 
                                            v-validate.initial="'required'"
                                            type="text" v-model="tipodocumento"
                                            name="Tipo Documento">
                                        <span class="text-error">{{ errors.first('Tipo Documento')}}</span>
                                    </div>
                                </div>
                                <div class="form-group row col-sm-6">
                                    <label class="col-md-4 form-control-label" for="text-input">Numero de Documento</label>
                                    <div class="col-md-8">
                                        <input class="form-control formu-entrada" 
                                            v-validate.initial="'required'"
                                            type="text" v-model="numdocumento"
                                            name="Numero Documento">
                                        <span class="text-error">{{ errors.first('Numero Documento')}}</span>
                                    </div>
                                </div>
                                <div class="form-group row col-sm-6">
                                    <label class="col-md-4 form-control-label" for="text-input">Fecha de Aporte</label>
                                    <div class="col-md-8">
                                        <input class="form-control formu-entrada" 
                                            v-validate.initial="'required'"
                                            type="date" v-model="fechaaporte"
                                            name="Fechar Aporte">
                                        <span class="text-error">{{ errors.first('Fecha Aporte')}}</span>
                                    </div>
                                </div>
                                <div class="form-group row col-sm-6">
                                    <label class="col-md-4 form-control-label" for="text-input">Importe</label>
                                    <div class="col-md-8"><!--expresion regular /^[0-9]+(\.[0-9][0-9]?)?/}"-->
                                        <input  v-validate.initial="{required:true,regex:/^[0-9]+(\.[0-9][0-9]?)?/}"  
                                                type="text" 
                                                v-model="aporte" 
                                                class="form-control" 
                                                placeholder="aporte"
                                                name="aporte">   

                                        <span class="text-error">{{ errors.first('aporte')}}</span>   <!--Lineas Agregadas<-->                                     

                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                   <div class="form-group row col-md-12">
                                    <label class="col-md-2 form-control-label" for="text-input">Glosa</label>
                                    <div class="col-md-10">
                                        <input  type="text" 
                                                v-model="obsaporte" 
                                                class="form-control" 
                                                placeholder="obs"
                                                name="obs">   
                                    </div>
                                </div>
                            </div>


                                <!--
                                <div v-show="errorSocio" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjSocio" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                                -->
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- modificar boton aceptar -->
                            <input  :disabled = "errors.any ()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarAporte()" value="Guardar">
                            <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarAporte()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
           <!--</form>
            Fin del modal-->

            <!--Inicio del modal debito-->
            <template>
            <div class="validation-component">
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalDebito}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal +' '+ grado +' '+ nombrecompleto + ' '+ ' '+numeropapeleta"></h4>
                            <button type="button" class="close" @click="cerrarModalDebito()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body1">
                            
                              <div class="card">
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <select class="form-control col-md-6" v-model="criterio">
                                                        <option value="numpapeleta">Num Papeleta</option>
                                                    </select>
                                                    
                                                    <input type="date" v-model="buscarF" @keyup.enter="listarAporteDebito(1,numeropapeleta,buscarF)" class="form-control">
                                                    <button type="submit" title="Abono Aportes"   @click="listarAporteDebito(1,numeropapeleta,buscarF)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Opciones</th>
                                                    <th>Fecha Aporte</th>
                                                    <th>Tipo Aporte</th>
                                                    <th>metodoaporte</th>
                                                    <th>Monto</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="debito in arrayAportedebito" :key="debito.idaporte">
                                                    <td>
                                                        <button type="button" @click="abrirModalDebito('debito','actualizar',debito)" class="btn btn-warning btn-sm">
                                                        <i class="icon-pencil"></i>
                                                        </button> &nbsp;
                                                        
                                                    </td>
                                                    <td v-text="debito.fechaaporte"></td>
                                                    <td v-text="debito.descripcion"></td>
                                                    <td v-text="debito.metodoaporte"></td>
                                                    <td v-text="debito.sumatotal"></td>
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
                            
                            
                            <template v-if="mdebito">
                                
                            
                            <div class="form-row">
                                <div class="form-group row col-md-6">
                                    <label class="col-md-4 form-control-label" for="text-input">Fecha de aporte: </label>
                                    <p class="col-md-8"> <b>{{ fechaaporte }} </b></p>
                                </div>
                                <div class="form-group row col-md-6">
                                    <label class="col-md-4 form-control-label" for="text-input">Monto Aporte:</label>
                                    <p class="col-md-8">{{ aporte }} </p>
                                    
                                </div>
                            </div>
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">    
                            <div class="form-row">
                                <div class="form-group row col-sm-6">
                                    <label class="col-md-4 form-control-label" for="text-input">Perfil de cuenta:</label>
                                    <div class="col-md-8">
                                        <select class="form-control" 
                                                v-model="idperfilcuentamaestro"
                                                v-validate.initial="'required'"
                                                
                                                name="Perfilcuentamaestro">

                                            <option value="0">Seleccione</option>
                                            <option v-for="perfilcuentamaestro in arrayPerfilcuentamaestro" :key="perfilcuentamaestro.idperfilcuentamaestro" :value="perfilcuentamaestro.idperfilcuentamaestro" v-text="perfilcuentamaestro.nomperfil"></option>
                                        </select>                                        
                                        <span class="text-error">{{ errors.first('Perfilcuentamaestro')}}</span>   <!--Lineas Agregadas<-->
                                    </div>
                                </div>
                                
                                
                                <div class="form-group row col-sm-6">
                                    <label class="col-md-4 form-control-label" for="text-input">Monto a Debitar :</label>
                                    <div class="col-md-8">
                                        <input class="form-control formu-entrada" 
                                            v-validate.initial="'required'"
                                            type="number" v-model="montodebito"
                                            name="Monto Debito" placeholder="0">
                                        <span class="text-error">{{ errors.first('Monto Debito')}}</span>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="form-row">
                                <div class="form-group row col-md-6">
                                    <label class="col-md-4 form-control-label" for="text-input"></label>
                                    <p class="col-md-8"> </p>
                                </div>
                                <div class="form-group row col-md-6">
                                    <label class="col-md-4 form-control-label" for="text-input">Aporte Actualizado:</label>
                                    <p v-if="saldo_aporte=='El Saldo No debe ser Negativo'" class="col-md-8"><span class="text-error"><b> {{ saldo_aporte }}</b></span> </p>
                                    <p v-else><b>{{saldo_aporte}}</b></p>
                                    
                                </div>
                            </div>
                            
                            <div class="form-row">
                                   <div class="form-group row col-md-12">
                                    <label class="col-md-2 form-control-label" for="text-input">Glosa</label>
                                    <div class="col-md-10">
                                        <input  type="text" 
                                                v-model="obsdebito" 
                                                v-validate.initial="'required'"
                                                class="form-control" 
                                                placeholder="Glosa Debito"
                                                name="obs_debito"> 
                                        <span class="text-error">{{ errors.first('obs_debito')}}</span>  
                                    </div>
                                </div>
                            </div>


                                <!--
                                <div v-show="errorSocio" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjSocio" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                                -->
                            </form>
                            </template>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModalDebito()">Cerrar</button>
                            <!-- modificar boton aceptar -->
                            
                            <button :disabled="!isComplete" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="registrarDebito()">Registar</button>
                        </div><!--:disabled="errors.any() || !isCompleted"-->
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            </div>
            </template>
            <!--Fin del modal-->

        
        </main>
</template>

<script>
    import VueBarcode from 'vue-barcode';
    import Vue from 'vue';
    import VeeValidate from 'vee-validate';

    //import vSelect from 'vue-select';
    //Vue.component( 'v-select', vSelect );

    //Vue.component('v-select', VueSelect.VueSelect)

    const VueValidationEs = require('vee-validate/dist/locale/es');
        Vue.use(VeeValidate, 
        {
            locale: 'es',
            dictionary: 
            {
                es: VueValidationEs
            }
        });
    
    Vue.use(VeeValidate);

    export default {
        data (){
            return {
                mdebito:null,
                buscarF:'',
                socio_id: 0,
                aporte_id:0,
                idperfilcuentamaestro : 0,
                idtipoaporte:0,
                numpapeleta : '',
                nomperfil:'',
                arraySocio : [],
                modal : 0,
                modalDebito:0,
                tituloModal : '',
                tituloModalDebito:'',
                tipoAccion : 0,
                errorSocio : 0,
                errorMostrarMsjSocio : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'numpapeleta',
                criterioFecha:'fechaaporte',
                buscarFecha:'',
                buscar : '',
                
                buscarAporte:'',
                arrayDepartamento :[],
                arrayAporte:[],
                
                arraySocio:[],
                arrayTipoaporte:[],
                arrayPerfilcuentamaestro:[],
                nombrecompleto:'',
                numeropapeleta:'',
                grado:'',
                aporte:'',
                fechaaporte:'',    
                obsaporte:'',  
                tipooperacion:0,    
                numdocumento:'',
                tipodocumento:'',    
                
                arrayAportedebito:[],

                montodebito:'',
                obsdebito:'',


            }
        },
        components: {
        'barcode': VueBarcode
    },
        computed:{
            isComplete () {
                //console.log( this.idperfilcuentamaestro && this.montodebito && this.obsdebito);
                return this.idperfilcuentamaestro && this.montodebito && this.obsdebito;
            },
            saldo_aporte: function(){
                /*var me=this
                var saldo_aporte
                me.saldo_aporte= this.aporte - this.montodebito*/
                var saldo
                saldo= this.aporte - this.montodebito
                if(saldo<0)
                    return "El Saldo No debe ser Negativo"
                else
                    return saldo

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
            //metodo agregado para la validacion
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

            listarSocio (page,buscar,criterio){
                let me=this;
                var url= '/apo_aporte?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    
                    if(!response.data)
                    {
                        swal({
                        text: "Debe Ingresar un dato para buscar.", 
                        type: "warning",                                                  
                        })     
                    }
                    else
                    {
                        var respuesta= response.data;
                        //console.log(respuesta.socios.data.length)
                        if(respuesta.socios.data.length==0)
                        {
                            swal({
                                text: "No se Encontraron Registros.", 
                                type: 'warning',                                                  
                            })  
                            me.arraySocio=[]
                        }
                        else
                        {
                            
                            me.arraySocio = respuesta.socios.data;
                            me.pagination= respuesta.pagination;
                        }
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectPerfilcuentamaestro(tipooperacion){
                let me=this;
                var url= '/con_perfilcuentamaestro/selectPerfilcuentamaestro?idmodulo='+2;
                axios.get(url).then(function (response) {
                    //console.log(url);
                    var respuesta= response.data;
                    me.arrayPerfilcuentamaestro = respuesta.perfilcuentamaestros;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectTipoaporte(){
                let me=this;
                var url= '/apo_tipoaporte/selectTipoaporte';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayTipoaporte = respuesta.tipoaportes;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            /*
            listarAporte(numpapeleta){
                let me=this;
                var url= '/apo_aporte?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arraySocio = respuesta.socios.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            */
            
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarSocio(page,buscar,criterio);
            },
            registrarAporte(){
                /*if (this.validarAporte()){
                    return;
                }*/
                
                let me = this;

                axios.post('/apo_aporte/registrar',{
                    'numpapeleta':this.numeropapeleta,
                    'idtipoaporte': this.idtipoaporte,
                    'aporte': this.aporte,
                    'fechaaporte': this.fechaaporte,
                    'metodoaporte':'formulario-aporte',
                    'idperfilcuentamaestro':this.idperfilcuentamaestro,
                    'obsaporte':this.obsaporte,
                    'numdocumento':this.numdocumento,
                    'tipodocumento':this.tipodocumento

                    
                }).then(function (response) {
                        swal(
                            'Registrado Correctamente'
                       )  
                        me.resetModal();
                        me.listarAporte(me.numeropapeleta);
                    
                }).catch(function (error) {
                    console.log(error);
                });
            },

            registrarDebito(){
                let me = this;

                axios.post('/apo_debito/registrar',{
                    'idaporte':this.aporte_id,
                    
                    'montodebito': this.montodebito,
                    
                    'idperfilcuentamaestro':this.idperfilcuentamaestro,
                    'obsdebito':this.obsdebito
                }).then(function (response) {
                        swal(
                            'Registrado Correctamente'
                       )  
                        me.resetModalDebito();
                        me.listarAporteDebito(1,me.numeropapeleta);
                    
                }).catch(function (error) {
                    console.log(error);
                });

            },

            actualizarAporte(){
                
                let me = this;

                axios.put('/apo_aporte/actualizar',{
                    'idaporte':this.aporte_id,
                    'numpapeleta':this.numeropapeleta,
                    'idtipoaporte': this.idtipoaporte,
                    'aporte': this.aporte,
                    'fechaaporte': this.fechaaporte,
                    'metodoaporte':'formulario-aporte',
                    'idperfilcuentamaestro':this.idperfilcuentamaestro,
                    'obsaporte':this.obsaporte
                }).then(function (response) {
                    if(response.data.length){
                    swal(
                            'El Valor ya Existe',
                            'Ingresa un dato Diferente',
                            'error'
                       )
                    }
                    else{
                        me.resetModal2();
                        me.listarAporte(me.numeropapeleta);
                        //this.tipoAccion = 1;
                    }
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            
            cerrarModal(){
                this.modal=0;
                this.resetModal();
            },
            cerrarModalDebito(){
                this.modalDebito=0;
                this.buscarFecha='';
                this.resetModal();
                this.buscarF='';
                
            },
            resetModal(){
                
                //this.tituloModal='';
                this.idtipoaporte= 0;
                this.idperfilcuentamaestro = 0;
                this.fechaaporte = '';
                this.aporte = '';
                this.obsaporte='';
                this.numdocumento='';
                this.tipodocumento='';
            },
            resetModal2(){
                
                //this.tituloModal='';
                this.idtipoaporte= 0;
                this.aporte_id=0;
                this.idperfilcuentamaestro = 0;
                this.fechaaporte = '';
                this.aporte = '';
                this.obsaporte='';
                this.tipoAccion=1;
            },
            resetModalDebito(){
                this.mdebito=0;
                this.obsdebito=''
            },
            listarAporte (numpapeleta){ 
                let me=this;
                var url= '/apo_aporte/selectAporte?numpapeleta='+ numpapeleta;
                axios.get(url).then(function (response) {
                    //console.log(response)
                    var respuesta= response.data; 
                    me.arrayAporte = respuesta.aportes; 
                    //console.log(respuesta.aportes)
                    //me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listarAporteDebito(page,numpapeleta,buscarF)
            {
                let me=this;
                buscarF=me.buscarF;
                //console.log('buscarF '+ me.buscarF)
                var url= '/apo_aporte/selectAporteDebito?page=' + page+'&numpapeleta='+ numpapeleta + '&fechabusqueda='+ buscarF;                //console.log(url)
                //console.log(url)
                /*axios.post('/apo_aporte/selectAporteDebito',{
                    'page':page,
                    'numpapeleta':numpapeleta,
                    'fechabusqueda':me.buscarF

                */
               axios.get(url).then(function (response) {
                    if(!response.data)
                    {
                        swal({
                        text: "Debe Ingresar un dato para buscar.", 
                        type: "warning",                                                  
                        })     
                    }
                    else
                    {
                        var respuesta= response.data; 
                        //console.log(respuesta.socios.data.length)
                        if(respuesta.aportes.data.length==0)
                        {
                            swal({
                                text: "No se Encontraron Registros.", 
                                type: 'warning',                                                  
                            })  
                            me.arrayAportedebito=[]
                            
                        }
                        else
                        {
                            me.arrayAportedebito = respuesta.aportes.data;
                            
                            //me.pagination= respuesta.pagination;
                            //console.log('prueba arrayaportedebito '+ me.arrayAportedebito)
                        }
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "aporte":
                    {
                        switch(accion)
                        {
                            case 'registrar':
                            {
                                this.tipooperacion=1;
                                //console.log(this.tipooperacion);
                                this.modal = 1;
                                this.tituloModal = 'Registrar Aporte';
                                this.num_papeleta=data['numpapeleta'];
                                this.listarAporte(this.num_papeleta);
                                this.nombrecompleto=data['fullname'];
                                this.numeropapeleta=data['numpapeleta'];
                                this.grado=data['nomgrado'];
                                this.nomsocio= '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                //this.modal=1;
                                this.tipooperacion=1;
                                this.tituloModal='Actualizar aporte';
                                this.tipoAccion=2;
                                this.aporte_id=data['idaporte'];
                                //console.log(aporte_id);
                                this.idtipoaporte=data['idtipoaporte'];
                                this.idperfilcuentamaestro=data['idperfilcuentamaestro'];
                                this.fechaaporte=data['fechaaporte'];
                                this.numpapeleta=data['numpapeleta'];
                                this.aporte=data['aporte']
                                this.obsaporte = data['obsaporte'];
                                break;
                            }
                        }
                        break;
                    }
                }
                this.selectPerfilcuentamaestro(this.tipooperacion);
                this.selectTipoaporte();
                //this.listarAporte();
            },
            abrirModalDebito(modelo, accion, data = []){
                switch(modelo){
                    case "debito":
                    {
                        switch(accion)
                        {
                            case 'registrar':
                            {
                                
                                this.mdebito=0;
                                this.tipooperacion=2;
                                this.modalDebito = 1;
                                this.tituloModalDebito = 'Registrat Debito Aporte';
                                this.num_papeleta=data['numpapeleta'];
                                //this.listarAporteDebito(1,this.num_papeleta,this.buscarF);
                                this.nombrecompleto=data['fullname'];
                                this.numeropapeleta=data['numpapeleta'];
                                this.grado=data['nomgrado'];
                                this.nomsocio= '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.idperfilcuentamaestro=0;
                                this.montodebito='';
                                this.aporte='';
                                this.obsaporte='';
                                this.mdebito=1;
                                this.tipooperacion=2;
                                //console.log(this.tipooperacion);
                                this.modalDebito=1;
                                this.tituloModalDebito='Actualizar Debito aporte';
                                this.tipoAccion=2;
                                this.aporte_id=data['idaporte'];
                                //console.log(aporte_id);
                                
                                this.idtipoaporte=data['idtipoaporte'];
                                //this.idperfilcuentamaestro=data['idperfilcuentamaestro'];
                                this.fechaaporte=data['fechaaporte'];
                                this.numpapeleta=data['numpapeleta'];
                                this.aporte=data['sumatotal']
                                this.obsaporte = data['obsaporte'];
                                
                                break;
                            }
                        }
                        break;
                    }
                }
                //console.log(this.tipooperacion);
                this.selectPerfilcuentamaestro(this.tipooperacion);
                this.selectTipoaporte();
                //this.listarAporte();
                this.listarAporteDebito(1,this.numeropapeleta,this.buscarF);
            }



        },
        mounted() {
            //this.listarSocio(1,this.buscar,this.criterio);
        }
    }
</script>
<style>    
    .modal-content{
        width: 120% !important;
        /*top:50%; 
        right: 5%; */
        position: absolute !important;
        
    }
    
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
        
        /*outline: none; 
        overflow:hidden;*/
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
