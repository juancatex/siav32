<template>
<main>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <div class="tablatit titcard">
                        <div class="tcelda">Asignación de Responsable</div>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <button class="btn btn-primary" style="margin-top:0" @click="nuevaAsignacion(regActivo.idactivo)">Nueva Asignación</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <span class="titcampo">Título:</span>
                    <span  v-text="regActivo.nomauxiliar"></span>
                    <span class="titcampo">Código:</span>
                    <span v-text="regActivo.codactivo"></span>
                    <br>
                    <span class="titcampo">Descripción:</span>
                    <span v-text="regActivo.descripcion"></span>
                </div>
            </div>
            <br>
            <div class="table-responsive">
                <table class="table table-striped table-sm table-bordered">
                    <thead class="tcabecera">
                        <tr align="center">
                            <th></th>
                            <th align="left">Responsable</th>
                            <th>F. Asignación</th>
                            <th>Estado Entrega</th>
                            <th>Filial</th>
                            <th>Ambiente</th>
                            <th>F. Devolución</th>
                            <th>Estado Recepcion</th>
                            <th align="left">Obs.</th>                            
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="asignacion in arrayAsignaciones" :key="asignacion.idasignacion" align="center">
                            <td>
                                <button v-if="asignacion.activo===1 && arrayAsignaciones.length>1" class="btn btn-warning btn-sm icon-printer" title="Boleta de Traspaso" 
                                    @click="repTraspaso(asignacion.idactivo)"></button>
                                <button class="btn btn-warning btn-sm icon-pencil" @click="editarAsignacion(asignacion)" 
                                    title="Editar/Registrar devolución"></button>
                            </td>
                            <td v-text="asignacion.nomresponsable" align="left"></td>                            
                            <td v-text="jsfechas.fechames(asignacion.fechaini)"></td>
                            <td v-text="arrayEstados[asignacion.estadoini]"></td>
                            <td v-text="asignacion.nommunicipio" align="left"></td>
                            <td v-text="asignacion.nomambiente" align="left"></td>
                            <td>
                                <span v-if="asignacion.fechafin" v-text="jsfechas.fechames(asignacion.fechafin)"></span>
                                <span v-else class="badge badge-success">En uso</span>
                            </td>
                            <td v-text="arrayEstados[asignacion.estadofin]"></td>
                            <td v-text="asignacion.obs" align="left"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal" :class="modalAsignar?'mostrar':''" >
        <div class="modal-dialog modal-primary modal-lg">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title"><span v-text="accion==1?'Nueva':'Modificar'"></span> Asignación</h4>
                    <button type="button" class="close" @click="modalAsignar=0">x</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            Responsable: <span class="txtasterisco"></span> 
                            <select class="form-control" v-model="idresponsable"
                                name="res" :class="{'invalido':errors.has('res')}" v-validate="'required'">
                                <option v-for="directivo in arrayDirectivos" :key="directivo.id"
                                    :value="'s'+directivo.idsocio" v-text="directivo.nomgrado+' '+directivo.nombre+' '+directivo.apaterno"></option>
                                <option v-for="empleado in arrayEmpleados" :key="empleado.idempleado"
                                    :value="'c'+empleado.idempleado" v-text="empleado.nombre+' '+empleado.apaterno">
                                </option> 
                            </select>
                            <p class="txtvalidador" v-if="errors.has('res')">Seleccione un Responsable</p>                            
                        </div>
                        <div class="col-md-4" >F. Asignación: <span class="txtasterisco"></span> 
                            <input type="date" class="form-control" v-model="fechaini"
                                name="fini" :class="{'invalido':errors.has('fini')}" v-validate="'required'">
                            <p class="txtvalidador" v-if="errors.has('fini')">Dato requerido</p>
                            Estado Inicial: <span class="txtasterisco"></span> 
                            <select v-model="estadoini" class="form-control"
                                name="eini" :class="{'invalido':errors.has('eini')}" v-validate="'required'">
                                <option value="1">Bueno</option>
                                <option value="2">Regular</option>
                                <option value="3">Malo</option>
                            </select>
                            <p class="txtvalidador" v-if="errors.has('eini')">Seleccione un Estado</p>
                        </div>
                        <div class="col-md-4">F. Devolución:
                            <input type="date" class="form-control" v-model="fechafin">
                            Estado Devolución
                            <select v-model="estadofin" class="form-control">
                                <option value="1">Bueno</option>
                                <option value="2">Regular</option>
                                <option value="3">Malo</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                                Observaciones:
                                <input type="text" class="form-control" v-model="obs">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="modalAsignar=0">Cerrar</button>
                    <button class="btn btn-primary" @click="validarAsignacion()">
                        Guardar <span v-if="accion==2">Modificaciones</span></button>
                </div>


            </div>
        </div>
    </div>
</main>
</template>

<script>
import * as jsfechas from '../../fechas.js';
import * as reporte from '../../functions.js';

export default {
    props:['regActivo'],

    data(){ return {
        modalAsignar:'', accion:'', jsfechas:'', ipbirt:'',
        arrayAsignaciones:[], arrayEmpleados:[], arrayDirectivos:[],
        idasignacion:'', idresponsable:'', tiporesponsable:'', 
        fechaini:'', fechafin:'', estadoini:'', estadofin:'', obs:'',
        arrayEstados:['','Bueno','Regular','Malo'],
        arrayFiliales:[], arrayAmbientes:[], idfilial:'', idambiente:''
    }},
    
    methods:{
        
        listaFiliales(){
            var url='/fil_filial/listaFiliales?activo=1';
            axios.get(url).then(response=>{
                this.arrayFiliales=response.data.filiales;                
            })
        },

        listaAmbientes(idfilial){
            var url='/act_ambiente/listaAmbientes?idfilial='+idfilial+'&activo=1&orden=nomambiente';
            axios.get(url).then(response=>{
                this.arrayAmbientes=response.data.ambientes;
            });
        },

        listaAsignaciones(idactivo){
            var url='/act_asignacion/listaAsignaciones?idactivo='+idactivo;
            axios.get(url).then(response=>{
                this.arrayAsignaciones=response.data.asignaciones;
                this.ipbirt=response.data.ipbirt;
            });
        },

        listaEmpleados(){
            var url='/rrh_empleado/listaEmpleados?idfilial='+this.regActivo.idfilial+'&activo=1';
            axios.get(url).then(response=>{
                this.arrayEmpleados=response.data.empleados;
            });
        },

        listaDirectivos(){
            var url='/fil_directivo/listaDirectivos?idfilial='+this.regActivo.idfilial+'&activo=1';
            axios.get(url).then(response=>{
                this.arrayDirectivos=response.data.directivos;
            });
        },

        nuevaAsignacion(idactivo){ 
            //verificamos si el activo esta asignado y activo
            var url='/act_activo/validaAsignacion?idactivo='+idactivo;
            axios.get(url).then(response=>{                
                console.log(response.data.valida);
                if (response.data.valida===1) {
                    swal('Activo ya asignado','No se puede Asignar','success'); 
                    this.modalAsignar=0;
                }
                else {
                    this.modalAsignar=1;
                    this.accion=1;
                    this.listaEmpleados();
                    this.listaDirectivos();
                    this.idresponsable='';
                    this.idfilial='';
                    this.idambiente='';
                    this.fechaini='';
                    this.fechafin='';
                    this.estadoini='';
                    this.estadofin='';
                    this.obs='';
                    this.$validator.reset();        
                }
            });

            this.modalAsignar=1;
            this.accion=1;
            this.listaEmpleados();
            this.listaDirectivos();
            this.idresponsable='';
            this.fechaini='';
            this.fechafin='';
            this.estadoini='';
            this.estadofin='';
            this.obs='';
            this.$validator.reset();
        },

        editarAsignacion(asignacion){
            this.modalAsignar=1;
            this.accion=2;
            this.listaEmpleados();
            this.listaDirectivos();
            this.idasignacion=asignacion.idasignacion;
            this.idresponsable=asignacion.tiporesponsable+asignacion.idresponsable;
            this.fechaini=asignacion.fechaini;
            this.estadoini=asignacion.estadoini;
            this.fechafin=asignacion.fechafin;
            this.estadofin=asignacion.estadofin;
            this.obs=asignacion.obs;
        },

        validarAsignacion(){
            this.$validator.validateAll().then(result=>{
                if(!result){ swal('Datos inválidos','Revise los errores','success'); return; }
                this.accion==1?this.storeAsignacion():this.updateAsignacion();
            });
        },

        storeAsignacion(){
            swal({ title:'Procesando...',text:'Un momento por favor', type:'warning',
                showCancelButton:false, showConfirmButton:false, 
                allowOutsideClick: false, allowEscapeKey: false, allowEnterKey: false,
                onOpen:() => { swal.showLoading() }
            });
            axios.post('act_asignacion/storeAsignacion',{
                'idactivo':this.regActivo.idactivo,
                'idresponsable':this.idresponsable.substr(1),
                'tiporesponsable':this.idresponsable.substr(0,1),
                'fechaini':this.fechaini,
                'estadoini':this.estadoini,
                'obs':this.obs
            }).then(response=>{
                swal('Activo asignado','','success');
                this.listaAsignaciones(this.regActivo.idactivo);
                this.modalAsignar=0;
            });
        },

        updateAsignacion(){
            axios.put('/act_asignacion/updateAsignacion',{
                'idasignacion':this.idasignacion,
                'idresponsable':this.idresponsable.substr(1),
                'tiporesponsable':this.idresponsable.substr(0,1),
                'fechaini':this.fechaini,
                'fechafin':this.fechafin,
                'estadoini':this.estadoini,
                'estadofin':this.estadofin,
                'obs':this.obs
            }).then(response=>{
                swal('Operación correcta','Se han actualizado los datos requeridos','success');
                this.listaAsignaciones(this.regActivo.idactivo);
                this.modalAsignar=0;
            });
        },
        /*
        activarAsignacion(asignacion){
            var url='/act_asignacion/activarAsignacion?idasignacion='
                +asignacion.idasignacion+'&idactivo='+this.regActivo.idactivo;
            let me=this;
            axios.put(url).then(function(){
                me.listaAsignaciones(me.regActivo.idactivo);
                me.listaActivos(me.idfilial,me.idoficina,me.idgrupo,me.idauxiliar);
            });
        },
        */

        repTraspaso(idactivo){ 
            var url=[];
            url.push('http://localhost:8080');
            url.push('/birt-viewer/frameset?__report=reportes/activos');
            url.push('/act_traspaso.rptdesign'); 
            url.push('&__format=pdf'); 
            url.push('&idactivo='+idactivo); 
            reporte.viewPDF(url.join(''),'Rep. Traspaso');
        },


    },

    mounted(){
        this.jsfechas=jsfechas;
        this.listaAsignaciones(this.regActivo.idactivo);
        this.listaFiliales();
        this.listaAmbientes(this.idfilial);
    },
}
</script>