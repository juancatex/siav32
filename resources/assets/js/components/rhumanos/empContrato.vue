<template>
<main>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-8">
                    <div class="tablatit">
                        <div class="tcelda">
                            <span class="titcard" v-text="regEmpleado.nombre+' '+regEmpleado.apaterno"></span>
                            <span class="titcard" v-text="regEmpleado.amaterno"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-right">
                    <button class="btn btn-primary" style="margin-top:0" @click="nuevoContrato()">Nuevo Contrato</button>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped table-sm">
                <thead class="tcabecera">
                    <tr align="center">
                        <th></th>
                        <th>Nro</th>
                        <th>Tipo</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th align="right">Salario</th>
                        <th align="left">Obs.</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="contrato in arrayContratos" :key="contrato.id" :class="contrato.activo?'':'txtdesactivado'" align="center">
                        <td v-if="contrato.activo" align="center">
                            <button class="btn btn-warning icon-pencil  btn-sm" title="Editar Contrato" @click="editarContrato(contrato)"></button>
                            <button class="btn btn-warning icon-printer btn-sm" title="Imprimir Contrato" @click="reporteContrato(contrato)"></button>
                            <button class="btn btn-danger icon-trash btn-sm" title="Desactivar Contrato" @click="estadoContrato(contrato)"></button>
                        </td>
                        <td v-else align="center">
                            <button class="btn btn-warning icon-printer btn-sm" title="Imprimir Contrato" @click="reporteContrato(contrato)"></button>
                            <!-- <button class="btn btn-warning icon-action-redo btn-sm" title="Restaurar Contrato" @click="estadoContrato(contrato)"></button> -->
                        </td>
                        <td v-text="contrato.nrcontrato"></td>
                        <td v-text="contrato.nomtipocontrato"></td>
                        <td v-text="contrato.inicontrato?jsfechas.fechames(contrato.inicontrato):''"></td>
                        <td v-text="contrato.fincontrato?jsfechas.fechames(contrato.fincontrato):''"></td>
                        <td v-text="contrato.salario+'Bs'" align="right"></td>
                        <td v-text="contrato.obs" align="left"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal" :class="modalContratos?'mostrar':''">
        <div class="modal-dialog modal-primary">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title"><span v-text="accion==1?'Nuevo':'Modificar'"></span> Contrato</h4>
                    <button class="close" @click="modalContratos=0">x</button>
                </div>
                <div class="modal-body">
                    <h4 class="titsubrayado" v-text="regEmpleado.nombre+' '+regEmpleado.apaterno"></h4>
                    <div class="row">
                        <div class="col-md-6">
                            Número: <span class="txtasterisco"></span>
                            <input type="text" class="form-control" v-model="nrcontrato" 
                                name="nrc" :class="{'invalido':errors.has('nrc')}" v-validate="'required'">
                            <p v-if="errors.has('nrc')" class="txtvalidador">Dato requerido</p>
                            Tipo de contrato: <span class="txtasterisco"></span>
                            <select class="form-control" v-model="idtipocontrato" 
                                name="tip" :class="{'invalido':errors.has('tip')}" v-validate="'required'">
                                <option v-for="tipocontrato in arrayTipocontratos" :key="tipocontrato.id"
                                    :value="tipocontrato.idtipocontrato" v-text="tipocontrato.nomtipocontrato"></option>
                            </select>
                            <p v-if="errors.has('tip')" class="txtvalidador" >Seleccione un Tipo de contrato</p>
                            Salario convenido: <span class="txtasterisco"></span>
                            <div class="input-group">
                                <input type="text" class="form-control text-right" v-model="salario"
                                    name="sal" :class="{'invalido':errors.has('sal')}" v-validate="'required|decimal:2'">
                                <div class="input-group-append"><span class="input-group-text">Bs.</span></div>
                            </div>
                            <p v-if="errors.has('sal')" class="txtvalidador">Dato requerido, numérico hasta 2 decimales</p>
                        </div>
                        <div class="col-md-6">
                            Inicio: <span class="txtasterisco"></span>
                            <input type="date" class="form-control" v-model="inicontrato"
                                name="ini" :class="{'invalido':errors.has('ini')}" v-validate="'required'">
                            <p v-if="errors.has('ini')" class="txtvalidador">Dato requerido</p>
                            Fin: <span class="txtasterisco"></span>
                            <input type="date" class="form-control" v-model="fincontrato">
                            Observaciones:
                            <input type="text" class="form-control" v-model="obs">
                        </div>
                        <div class="col-md-12 text-right txtobligatorio"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="modalContratos=0">Cerrar</button>
                    <button class="btn btn-primary" @click="validarContrato()">
                        Guardar <span v-if="accion==2">Modificaciones</span></button>
                </div>
            </div>
        </div>
    </div>
</main>
</template>

<script>
import * as jsfechas from '../../fechas.js'; 

export default {
    props:['regEmpleado'],

    data(){ return{ 
        modalContratos:0, jsfechas:'', accion:'', ipbirt:'',
        arrayTipocontratos:[], arrayContratos:[],
        idcontrato:'', nrcontrato:'', idtipocontrato:'', 
        inicontrato:'', fincontrato:'', salario:'', obs:'',
    }},

    methods:{
        listaContratos(idempleado){
            var url='/rrh_contrato/listaContratos?idempleado='+idempleado;
            axios.get(url).then(response=>{
                this.arrayContratos=response.data.contratos;
                this.ipbirt=response.data.ipbirt; 
            });
        },

        listaTipocontratos(){
            var url='/rrh_tipocontrato/listaTipocontratos?activo=1';
            axios.get(url).then(response=>{
                this.arrayTipocontratos=response.data.tipocontratos;
            });
        },

        nuevoContrato(){
            this.modalContratos=1;
            this.accion=1;
            this.listaTipocontratos();
            this.nrcontrato='';  
            this.idtipocontrato='';
            this.inicontrato='';
            this.fincontrato='';
            this.salario='';
            this.obs='';
            this.$validator.reset();
        },

        editarContrato(contrato){
            this.modalContratos=1;
            this.accion=2;
            this.listaTipocontratos();
            this.idcontrato=contrato.idcontrato;
            this.idempleado=contrato.idempleado;
            this.nrcontrato=contrato.nrcontrato;
            this.idtipocontrato=contrato.idtipocontrato;
            this.inicontrato=contrato.inicontrato;
            this.fincontrato=contrato.fincontrato;
            this.salario=contrato.salario;
            this.obs=contrato.obs;
        },

        validarContrato(){
            this.$validator.validateAll().then(result=>{
                if(!result){ swal('Datos inválidos','Revise los errores','error'); return; }
                this.accion==1?this.storeContrato():this.updateContrato();
            })
        },

        storeContrato(){            
            axios.post('/rrh_contrato/storeContrato',{
                'idempleado':this.regEmpleado.idempleado,
                'nrcontrato':this.nrcontrato,
                'idtipocontrato':this.idtipocontrato,
                'inicontrato':this.inicontrato,
                'fincontrato':this.fincontrato,
                'salario':this.salario,
                'obs':this.obs
            }).then(response=>{
                swal('Contrato Guardado','','success');
                this.modalContratos=0;
                this.listaContratos(this.regEmpleado.idempleado);
            });
        },

        updateContrato(){
            axios.put('/rrh_contrato/updateContrato',{
                'idcontrato':this.idcontrato,
                'idempleado':this.regEmpleado.idempleado,
                'nrcontrato':this.nrcontrato,
                'idtipocontrato':this.idtipocontrato,
                'inicontrato':this.inicontrato,
                'fincontrato':this.fincontrato,
                'salario':this.salario,
                'obs':this.obs
            }).then(reponse=>{
                swal('Datos actualizados','','success');
                this.modalContratos=0;
                this.listaContratos(this.regEmpleado.idempleado);
            });
        },

        estadoContrato(contrato){
            this.idcontrato=contrato.idcontrato;
            if(contrato.activo){
                swal({  title:'Desactivará el contrato '+contrato.nrcontrato, type: 'warning', 
                    html:'', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Contrato',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(confirmar=>{ confirmar.value?this.switchContrato(0):'' });
            }
            else this.switchContrato(1);
        },

        switchContrato(activo){
            var url='/rrh_contrato/switchContrato?idcontrato='+this.idcontrato;
            axios.put(url).then(response=>{
                swal(activo?'Activado correctamente':'Desactivado correctamente','','success');
                this.listaContratos(this.regEmpleado.idempleado);
            });
        },

        reporteContrato(contrato){  
           _pl._vm2154_12186_135(this.ipbirt['REP_CONTRATO']+contrato.idcontrato,'Contrato de trabajo');
        }
    },

    mounted(){
        this.jsfechas=jsfechas;
        this.listaContratos(this.regEmpleado.idempleado);
    },
}
</script>