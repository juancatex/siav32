<template>
    <main class="main">
        <ol class="breadcrumb">
            <li class="breadcrum-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i>Solicitud de Cargo de Cuenta
                    <button type="button" class="btn btn-secondary" @click="abrirModalSolicitud('modalsolicitud','registrar')">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" v-model="buscar" @keyup.enter="listarPersonal(1,buscar,tipocargo)" class="form-control" placeholder="Buscar Personal...">
                                <button type="submit" title="Buscar Cargo de Cuenta"   @click="listarPersonal(1,buscar,tipocargo)" class="btn btn-primary"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        <!-- <div class="col-4 col-form-label " style="border: 1px solid #c2cfd6 !important; border-radius: 5px;">
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" v-model="tipocargo" value="personal" checked @change="listarPersonal(1,buscar,tipocargo)">Personal
                                </label>
                                </div>
                                <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" v-model="tipocargo" value="directorio" @change="listarPersonal(1,buscar,tipocargo)">Dirctorio
                                </label>
                            </div>
                        </div> -->
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Fecha Solicitud</th>
                                <th>Fecha Desemboldo</th>
                                <th>Nombre</th>
                                <th>Glosa</th>
                                <th>Monto</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="solicitud in arraySolicitud" :key="solicitud.idsolccuenta">
                                <td>
                                    <template v-if="solicitud.estado_aprobado!=1 && solicitud.estado_aprobado!=3">
                                        <!-- <button type="button" @click="abrirModalSolicitud('modalsolicitud','editar',solicitud)" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Editar">
                                               <i class="cui-note"></i>
                                        </button> -->
                                        <button type="button" @click="eliminarSolicitud(solicitud.idsolccuenta)" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Eliminar Solicitud">
                                            <i class="cui-trash"></i>
                                        </button> 
                                    </template>
                                </td>
                                <td v-text="solicitud.fecha_solicitud"></td>
                                <td v-text="solicitud.fechav"></td>
                                <td v-text="solicitud.nombres"></td>
                                <td v-text="solicitud.glosa"></td>
                                <td style="text-align:right" >{{solicitud.monto | currency}}</td>
                                <!-- <td v-text="solicitud.nomgrado"></td>-->
                                <td>
                                    <div v-if="solicitud.estado_aprobado==1">
                                        <span class="badge badge-success">Validado Conta</span>
                                    </div>
                                    <div v-else-if="solicitud.estado_aprobado==0">
                                        <span class="badge badge-warning">No Desembolsado</span>
                                    </div>
                                    <div v-else-if="solicitud.estado_aprobado==3">
                                        <span class="badge badge-info">Desembolsado</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-danger">Obsevado</span>
                                    </div>
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
            <!-- MODAL AGREGAR SOLICITUD --> 
            <div class="modal fade " tabindex="-1"  role="dialog"   aria-hidden="true" id="modalsolicitud"  data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content animated fadeIn">
                            <div class="modal-header"> 
                                <h4 class="modal-title" v-text="tituloModal"></h4>
                                <button type="button" class="close" aria-hidden="true" aria-label="Close" @click="cerrarModal('modalsolicitud')"><span aria-hidden="true">×</span></button>
                            </div> 
                        <div class="modal-body">
                           
                            <div class="col-6 col-form-label " style="border: 1px solid #c2cfd6 !important; border-radius: 5px;">
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" v-model="directivo" value="directivo" checked @change="cambiaDirectivo()"> Directivos
                                </label>
                                </div>
                                <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" v-model="directivo" value="personal" @change="cambiaDirectivo()">Personal
                                </label>
                            </div>
                        </div>
                            <div class="row">
                                <div class="form-group col-md-8" v-if="directivo=='directivo'">
                                    <strong>Directivo:</strong>
                                    <Ajaxselect  v-if="clearSelected"
                                        ruta="/rrh_empleado/selectdirectivos?buscar=" @found="empleados" @cleaning="cleanempleados"
                                        resp_ruta="empleados"
                                        labels="nombres"
                                        placeholder="Ingrese Texto..." 
                                        idtabla="idsocio"
                                        :id="idempleadoselected"
                                        :clearable='true'>
                                    </Ajaxselect>
                                </div>
                                <div class="form-group col-md-8" v-else>
                                    <strong>Personal:</strong>
                                    <Ajaxselect  v-if="clearSelected"
                                        ruta="/rrh_empleado/selectempleados2?buscar=" @found="empleados" @cleaning="cleanempleados"
                                        resp_ruta="empleados"
                                        labels="nombres"
                                        placeholder="Ingrese Texto..." 
                                        idtabla="idempleado"
                                        :id="idempleadoselected"
                                        :clearable='true'>
                                    </Ajaxselect>
                                </div>
                                
                                <div class="form-group col-md-4">
                                    <strong>Monto:</strong>
                                    <vue-numeric  
                                        class="form-control input-importe"
                                        currency="Bs." 
                                        separator="," 
                                        v-model="monto"
                                        v-bind:precision="2"
                                        v-on:focus="selectAll">
                                    </vue-numeric>
                                    <template v-if="monto==0">
                                        <div>
                                            <span class="text-error">Debe Ingresar monto</span>        
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <strong><label>Glosa:</label></strong>
                                    <textarea 
                                        :class="{'form-control': true, 'is-invalid textareaerror': errors.has('glosa')}"  
                                        rows="2" 
                                        v-model="glosa"
                                        name="glosa"
                                        v-validate.initial="'required'" >
                                    </textarea>
                                    <span class="text-error">{{ errors.first('glosa')}}</span> 
                                </div>
                            </div>
                        </div>  

                        <div class="modal-footer"> 
                            <button type="button" class="btn btn-secondary" @click="cerrarModal('modalsolicitud')">Cerrar</button>
                            <button :disabled="!isComplete" class="btn btn-primary" type="button" v-if="tipoAccion==1"  @click="registrarSolicitud('modalsolicitud')">Guardar</button>
                            <button :disabled="!isComplete" class="btn btn-primary" type="button" v-if="tipoAccion==2"  @click="actualizarSolicitud()">Actualizar</button>
                            <!--  <button class="btn btn-primary" type="button" @click="print()">Imprimir</button> -->               
                        </div>    
                    </div>
                </div>
            </div>
            <!-- fin otro modal -->
            <!--Inicio del modal progreso-->
            
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title" >Progreso...</h6>
                            <!--
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close"></button>

                            <span aria-hidden="true">×</span>
                            -->
                        </div>
                        <div class="modal-body">
                            <div >
                                <div class="sk-folding-cube" v-if="modal==1">
                                    <div class="sk-cube1 sk-cube"></div>
                                    <div class="sk-cube2 sk-cube"></div>
                                    <div class="sk-cube4 sk-cube"></div>
                                    <div class="sk-cube3 sk-cube"></div>
                                </div>
                                <!--
                                <div class="sk-wave" >
                                    <div class="sk-rect sk-rect1"></div>
                                    <div class="sk-rect sk-rect2"></div>
                                    <div class="sk-rect sk-rect3"></div>
                                    <div class="sk-rect sk-rect4"></div>
                                    <div class="sk-rect sk-rect5"></div>
                                </div>
                                -->
                                <!--<div class="col-md-6">                                    
                                    <img :src="'img/gif/cargando.gif'" style="width:100px">                                 
                                </div>    
                                -->
                            </div>
                        </div>
                        
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            
            <!--Fin del modal-->
        </div>
    </main>
</template>
<script>
const st = {};
/*prueba*/
   
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
    import Vue from 'vue';
    import VeeValidate from 'vee-validate';
    import vSelect from 'vue-select';
    import VueNumeric from 'vue-numeric'

    const VueValidationEs=require('vee-validate/dist/locale/es');
    Vue.use(VeeValidate,
    {
        locale:'es',
        dictionary:
        {
            es:VueValidationEs
        }
    });

    Vue.use(VeeValidate);
    Vue.component('v-select',vSelect);
    Vue.use(VueNumeric);
    export default {
        data(){
            return{
                buscar:'',
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                tipocargo:'personal',
                criterio:'',
                idempleado:[],
                arraySolicitud:[],
                monto:0,
                glosa:'',
                tituloModal:'',
                clearSelected:1,
                tipoAccion:1,
                idempleadoselected:'',
                solicitud_id:'',
                offset:3,
                modal:0,
                directivo:'directivo'

            }
        },
        components:{
            'barcode':VueBarcode
        },
        computed:{
            isComplete () {
                return this.monto && this.glosa && this.idempleado.length>0;
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
            },
           
        },
        methods:{
            cambiaDirectivo(valor){
                let me=this;
                me.clearSelected=0;
                if(valor=="directivo")
                {
                        valor="personal";
                        setTimeout(me.tiempo, 200); 
                }
                else
                {
                    valor="directivo";
                    setTimeout(me.tiempo, 200); 
                }
            },
            cambiarPagina(page,buscar){
                let me = this;
                //console.log(page + ' ' +buscar);
                
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarPersonal(page,buscar,me.tipocargo);
            },
            selectAll: function (event) {
                setTimeout(function () {
                    event.target.select()
                }, 0)
            } ,  
            tiempo(){
            this.clearSelected=1;
            }, 
            empleados(empleados){
                this.idempleado=[];
                for (const key in empleados) {
                    if (empleados.hasOwnProperty(key)) {
                        const element = empleados[key];
                        //console.log(element);
                        this.idempleado.push(element);
                    }
                }
                //console.log(this.idempleado);
            },
            cleanempleados(){
                this.idempleado=[];
                //this.idempleadorespuesta=0;
            //console.log('clean')
            
            },
             listarPersonal(page,buscar,tipocargo){
                  //console.log(borradorcheck);
                let me=this;
                var url= '/glo_solccuenta?page=' + page + '&buscar='+ buscar + '&tipocargo='+ tipocargo;
                //console.log(url);
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arraySolicitud = respuesta.solicitudes.data;
                    me.pagination= respuesta.pagination;
                    //console.log(me.pagination);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            abrirModalSolicitud(id, accion, data = []){
                var nombretipocomprobante;
                let me=this;
                switch(accion){
                    case 'registrar':
                        {
                            //this.modal = 1;
                            me.tituloModal = 'Agregar Solicitud de cargo de Cuenta ';
                            me.idempleado=[];
                            me.glosa='';
                            me.monto='';
                            me.tipoAccion = 1;
                            me.classModal.openModal(id);
                            break;
                        }
                    case 'editar':
                        {
                            me.tituloModal="Editar Solicitud de cargo de Cuenta -" + nombretipocomprobante;
                            me.idempleado[0]=data['subcuenta'];
                            me.idempleadoselected=data['subcuenta'];
                            me.monto=data['monto'];
                            me.glosa=data['glosa'];
                            me.tipoAccion=2;
                            me.clearSelected=0;
                            me.solicitud_id=data['idsolccuenta'];
                            setTimeout(me.tiempo, 200); 
                            me.classModal.openModal(id);
                            
                            //this.rowregistros=respuesta;
                            break;
                        }
                }
            },
            registrarSolicitud(){
                let me = this;
                let valor=0;

                /*swal({
                     title: "Ajax request example",
                    text: "Submit to run ajax request",
                    type: "info",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true 

                    }, function () {
                    setTimeout(function () {
                        swal("Ajax request finished!");
                    }, 2000);
                }); */
                this.abrirModal();
                if(me.directivo=="directivo")
                    valor=1;
                else
                    valor=0;
                axios.post('/glo_solccuenta/registrar',{
                    'subcuenta':me.idempleado[1],
                    'directorio':valor,
                    'monto':me.monto,
                    'glosa':me.glosa,
                    
                }).then(function (response) {
                    var residasientomaestro=response.data;
                    //console.log(response.data)
                    swal(
                            'Registrado Correctamente'
                       )   
                    me.modal=0;                 
                    me.cerrarModal('modalsolicitud');
                    me.listarPersonal(1,me.buscar,me.tipocargo);
                   // console.log('cerrar modal');
                    
                    
                }).catch(function (error) {
                    console.log(error);
                });
            },
            abrirModal(){
                this.modal=1;

            },
            actualizarSolicitud(){
                let me = this;
                let valor=0;
                if(me.directivo=="directivo")
                    valor=1;
                else
                    valor=0;
                axios.put('/glo_solccuenta/actualizar',{
                    'idsolccuenta':me.solicitud_id,
                    'subcuenta':me.idempleado[1],
                    'directorio':valor,
                    'monto':me.monto,
                    'glosa':me.glosa,
                    
                }).then(function (response) {
                    if(response.data.length)
                    {    swal(
                            'El Valor Ya Existe',
                            'Ingrese un Dato Diferente'
                        )                    
                    }
                    else
                    {
                        swal(
                            'Modificado Correctamente'
                       )                    
                    }
                    me.cerrarModal('modalsolicitud');
                    me.listarPersonal(1,me.buscar,me.tipocargo);
                    //console.log('cerrar modal');
                    
                    
                }).catch(function (error) {
                    console.log(error);
                });
            },
            cerrarModal(id){
                let me=this;
                me.clearSelected=0;
                setTimeout(me.tiempo, 50); 
                me.classModal.closeModal(id);
                me.idempleado=[];
                me.monto=0;
                me.glosa='';
            },
            eliminarSolicitud(idsolccuenta){
               swal({
                title: 'Esta seguro de Eliminar esta Solicitud?',
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

                    axios.put('/glo_solccuenta/desactivar',{
                        'idsolccuenta': idsolccuenta
                    }).then(function (response) {
                        me.listarPersonal(1,me.buscar,me.tipocargo);
                        swal(
                        'Desactivado!',
                        'El registro ha sido eliminado con éxito.',
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
        },
        mounted(){
            this.listarPersonal(1,this.buscar,this.tipocargo);
            this.classModal=new _pl.Modals();
            this.classModal.addModal('modalsolicitud');
            
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
