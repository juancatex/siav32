<template>
    <div class="modal" :class="modalAsignar?'mostrar':''" >
        <div class="modal-dialog modal-primary modal-lg">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title">Asignación del Activo</h4>
                    <button type="button" class="close" @click="modalAsignar=0,$emit('cerrarAsignacion')">x</button>
                </div>
                <div class="modal-body">
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
                        <div class="col-md-3 text-right">
                            <button v-if="!divFormulario" class="btn btn-primary" @click="nuevaAsignacion()">Nueva Asignación</button>
                        </div>
                    </div>
                    <br>
                    <div v-if="!divFormulario" class="table-responsive">
                        <table class="table table-striped table-sm table-bordered">
                            <thead class="tcabecera">
                                <tr>
                                    <th>Responsable</th>
                                    <th>F. Asignación</th>
                                    <th>Estado</th>
                                    <th>F. Devolución</th>
                                    <th>Estado</th>
                                    <th>Obs.</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="asignacion in arrayAsignaciones" :key="asignacion.idasignacion" align="center">
                                    <td v-text="asignacion.nomresponsable" align="left"></td>
                                    <td v-text="jsfechas.fechames(asignacion.fechaini)"></td>
                                    <td v-text="arrayEstados[asignacion.estadoini]"></td>
                                    <td>
                                        <span v-if="asignacion.fechafin" v-text="jsfechas.fechames(asignacion.fechafin)"></span>
                                        <span v-else class="badge badge-success">En uso</span>
                                    </td>
                                    <td v-text="arrayEstados[asignacion.estadofin]"></td>
                                    <td><button v-if="asignacion.obs" :title="asignacion.obs"
                                            class="btn btn-light icon-bubble" style="font-size:18px;padding:2px"></button></td>
                                    <td>
                                        <!--
                                        <button class="btn btn-sm icon-check" :class="asignacion.activo?'btn-success':'btn-light'"
                                            title="Activar/Desactivar Asignación" @click="activarAsignacion(asignacion)"></button>
                                            -->
                                        <button class="btn btn-warning btn-sm icon-printer" title="Boleta de Asignación" 
                                            @click="printAsignacion(asignacion)"></button>
                                        <button class="btn btn-warning btn-sm icon-pencil" @click="editarAsignacion(asignacion)" 
                                            title="Editar/Registrar devolución"></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <h4  v-if="divFormulario" class="titazul">Nueva Asignación</h4>
                    <div v-if="divFormulario" class="row">
                        <div class="col-md-4">
                            Responsable: 
                            <select class="form-control" v-model="idresponsable">
                                <option v-for="directivo in arrayDirectivos" :key="directivo.id"
                                    :value="'s'+directivo.idsocio" v-text="directivo.nomgrado+' '+directivo.nombre+' '+directivo.apaterno"></option>
                                <option v-for="empleado in arrayEmpleados" :key="empleado.idempleado"
                                    :value="'c'+empleado.idempleado" v-text="empleado.nombre+' '+empleado.apaterno">
                                </option> 
                            </select> 
                        </div>
                        <div class="col-md-4" >F. Asignación:
                            <input type="date" class="form-control" v-model="fechaini">
                            Estado Inicial:
                            <select v-model="estadoini" class="form-control">
                                <option value="1">Bueno</option>
                                <option value="2">Regular</option>
                                <option value="3">Malo</option>
                            </select>
                        </div>
                        <div class="col-md-4"  >F. Devolución:
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

                <div v-if="divFormulario" class="modal-footer">
                    <button class="btn btn-secondary" @click="divFormulario=0">Cancelar</button>
                    <button class="btn btn-primary" @click="accion==1?storeAsignacion():updateAsignacion()">
                        Guardar <span v-if="accion==2">Modificaciones</span></button>
                </div>
                <div v-else class="modal-footer">
                    <button class="btn btn-secondary" @click="modalAsignar=0, $emit('cerrarAsignacion')">Cerrar</button>
                    <button class="btn btn-primary"> Imprimir</button>
                </div>


            </div>
        </div>
    </div>

</template>

<script>
import * as jsfechas from '../../fechas.js';

export default {
    data(){ return {
        modalAsignar:'', divFormulario:0, accion:'', jsfechas:'',
        regActivo:[], regOficina:[], 
        arrayAsignaciones:[], arrayEmpleados:[], arrayDirectivos:[],
        idasignacion:'', idresponsable:'', tiporesponsable:'', 
        fechaini:'', fechafin:'', estadoini:'', estadofin:'', obs:'',
        arrayEstados:['','Bueno','Regular','Malo'],
    }},
    
    methods:{
        abrirModal(activo){
            this.modalAsignar=1;
            this.regActivo=activo;
            this.jsfechas=jsfechas;
            this.listaAsignaciones(activo.idactivo);
        },

        listaAsignaciones(idactivo){
            var url='/act_asignacion/listaAsignaciones?idactivo='+idactivo;
            axios.get(url).then(response=>{
                this.arrayAsignaciones=response.data.asignaciones;
            });
        },

        listaEmpleados(){
            var url='/rrh_empleado/listaEmpleados?activo=1';
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

        nuevaAsignacion(){
            this.divFormulario=1;
            this.accion=1;
            this.listaEmpleados();
            this.listaDirectivos();
            this.idresponsable='';
            this.fechaini='';
            this.fechafin='';
            this.estadoini='';
            this.estadofin='';
            this.obs='';            
        },

        editarAsignacion(asignacion){
            this.divFormulario=1;
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


        storeAsignacion(){
            swal({ title:'Procesando...',text:'Un momento por favor', type:'warning',
                showCancelButton:false, showConfirmButton:false, closeOnConfirm: false,
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
                this.divFormulario=0;
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
                this.divFormulario=0;
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

        printAsignacion(asignacion){
            alert('reporte pendiente');
        },


    },
}
</script>