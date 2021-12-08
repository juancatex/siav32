<template>
    <main class="main">
        <ol class="breadcrumb">
            <li class="breadcrum-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i>Solicitud Descuento Casa Comunitaria
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" v-model="buscar" @keyup.enter="listarsolicitud(1)" class="form-control" placeholder="Buscar solicitud...">
                                <button type="submit" title="Buscar Cargo de Cuenta"   @click="listarPersonal(1)" class="btn btn-primary"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Estado</th>
                                <th>Fecha Solicitud</th>
                                <th>Fecha Aprobacion</th>
                                <th>Nombre Socio</th>
                                <th>Establecimiento</th>
                                <th>Asignacion</th>
                                <th>Monto Original</th>
                                <th>Monto Final</th>
                                <th>Fecha Entrada</th>
                                <th>Opciones</th>
                            </tr>
                        </thead> 
                        <tbody>
                            <tr v-for="solicitud in arraySolicitud" :key="solicitud.idasignacion">
                                <td>
                                      <div v-if="solicitud.descuento==2">
                                        <span class="badge badge-success">Solicitud Aprobada</span>
                                    </div>
                                     <div v-if="solicitud.descuento==1">
                                        <span class="badge badge-danger">Por Aprobar</span>
                                    </div>
                                    
                                </td>
                                <td > {{ solicitud.fechasoldescuento }}</td>
                                <td>{{ solicitud.fechaaprodescuento }}</td>
                                <td v-text="solicitud.nombres"></td>
                                <td v-text="solicitud.nomestablecimiento"></td>
                                <td v-text="solicitud.codambiente +' - '+solicitud.tipo+' - Piso:'+solicitud.piso"></td>
                                <td style="text-align:right;">{{ solicitud.montosindescuento }} Bs.</td>
                                <td style="text-align:right;">{{ solicitud.monto }} Bs.</td>
                                <td >{{solicitud.fechaentrada }} - {{solicitud.horaentrada}}</td>
                                <td>
                                    <template v-if="solicitud.descuento==1">
                                        <button type="button" @click="autorizarSolicitud(solicitud)" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Aprobar Solicitud">
                                            <i class="fas fa-check"></i>
                                        </button> 
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
            <!-- MODAL AGREGAR SOLICITUD --> 
            <div class="modal fade " tabindex="-1"  role="dialog"   aria-hidden="true" id="solicitudDescuento"  data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content animated fadeIn">
                            <div class="modal-header"> 
                                <h4 class="modal-title" v-text="tituloModal"></h4>
                                <button type="button" class="close" aria-hidden="true" aria-label="Close" @click="cerrarModal('solicitudDescuento')"><span aria-hidden="true">×</span></button>
                            </div> 
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="col-md-12" for="socio"><strong> Socio: </strong>&nbsp;{{nombre}}</label>
                                    <label class="col-md-12" for="montooriginal"><strong> Monto Original: </strong>{{montooriginal}} Bs.</label>
                                    <label class="col-md-6" for="montoaprobado"><strong> Monto aprobado con descuento:</strong></label>
                                    <input class="form-control col-md-4" type="number" v-model="montoaprobado" id="montoaprobado" style="text-align:right;" v-on:focus="selectAll">
                                    <span class="text-error">{{ errors.first('montoaprobado')}}</span> 
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
                            </div>
                       <!--  <div class="modal-body form-group">
                            <label  for="socio">Socio: &nbsp;{{nombre}}</label>
                            <label  for="montooriginal">Monto: {{montooriginal}} Bs.</label>
                            <label  for="montoaprobado">Monto aprobado con descuento:</label>
                            <input  type="number" v-model="montoaprobado" id="montoaprobado" style="text-align:right;">
                            <span class="text-error">{{ errors.first('montoaprobado')}}</span> 
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
                        </div>   -->

                        <div class="modal-footer"> 
                            <button type="button" class="btn btn-secondary" @click="cerrarModal('solicitudDescuento')">Cerrar</button>
                            <button  class="btn btn-primary" type="button"   @click="registrarSolicitud()">Aprobar Solicitud</button>
                            <!--  <button class="btn btn-primary" type="button" @click="print()">Imprimir</button> -->               
                        </div>    
                    </div>
                </div>
            </div>
            <!-- fin otro modal -->
          
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
                
                arraySolicitud:[],
                monto:0,
                glosa:'',
                tituloModal:'',
                clearSelected:1,
                tipoAccion:1,
                
                offset:3,
                modal:0,
               


                nombre:'',
                montooriginal:0,
                montoaprobado:0,
                idasignacion:0,

            }
        },
        components:{
            'barcode':VueBarcode
        },
        computed:{
           
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
            autorizarSolicitud(data=[]){
                let me=this;
                me.nombre=data['nombres'];
                me.montooriginal=data['montosindescuento'];
                me.montoaprobado=0;
                me.idasignacion=data['idasignacion'];
                me.classModal.openModal('solicitudDescuento');

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
          
             listarPersonal(page){
                  //console.log(borradorcheck);
                let me=this;
                var url= '/ser_asignacion/autorizaciones';
                //console.log(url);
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arraySolicitud = respuesta.solicitud.data;
                    me.pagination= respuesta.pagination;
                    //console.log(me.pagination);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            registrarSolicitud(){
                let me = this;

                axios.put('/ser_asignacion/aprobarsolicitud',{
                    'idasignacion':me.idasignacion,
                    'obs2':me.glosa,
                    'monto':me.montoaprobado,
                    
                }).then(function (response) {
                    
                    swal(
                            'Registrado Correctamente'
                       )   
                              
                    me.cerrarModal('solicitudDescuento');
                    me.listarPersonal(1);
                   // console.log('cerrar modal');
                    
                    
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
           
        },
        mounted(){
            this.listarPersonal(1,this.buscar,this.tipocargo);
            this.classModal=new _pl.Modals();
            this.classModal.addModal('solicitudDescuento');
            
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
