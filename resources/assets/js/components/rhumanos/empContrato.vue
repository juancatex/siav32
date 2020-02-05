<template>
<main class="main">
    <div class="modal" :class="modalContratos?'mostrar':''">
        <div class="modal-dialog modal-primary modal-lg">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title">Contratos</h4>
                    <button class="close" @click="modalContratos=0">x</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="ttabla">
                                <div class="tcelda">
                                    <span class="titcard" v-text="regEmpleado.nombre+' '+regEmpleado.apaterno"></span>
                                    <span class="titcard" v-text="regEmpleado.amaterno"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-right">
                            <button v-if="!divFormulario" class="btn btn-primary" @click="nuevoContrato()">Registrar Contrato</button>
                        </div>
                    </div>
                    <br>
                    <div v-if="!divFormulario" class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead class="tcabecera">
                                <tr>
                                    <th>Nro</th>
                                    <th>Tipo</th>
                                    <th>Inicio</th>
                                    <th>Fin</th>
                                    <th>Salario</th>
                                    <th>Obs.</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="contrato in arrayContratos" :key="contrato.id" :class="contrato.activo?'':'txtdesactivado'" align="center">
                                    <td v-text="contrato.nrcontrato"></td>
                                    <td v-text="contrato.nomtipocontrato"></td>
                                    <td v-text="contrato.inicontrato?jsfechas.fechames(contrato.inicontrato):''"></td>
                                    <td v-text="contrato.fincontrato?jsfechas.fechames(contrato.fincontrato):''"></td>
                                    <td v-text="contrato.salario+'Bs'" align="right"></td>
                                    <td v-text="contrato.obs" align="left"></td>
                                    <td v-if="contrato.activo" align="center">
                                        <button class="btn btn-warning icon-pencil btn-sm" title="Editar Contrato" @click="editarContrato(contrato)"></button>
                                        <button class="btn btn-danger icon-trash btn-sm" title="Desactivar Contrato" @click="estadoContrato(contrato)"></button>
                                    </td>
                                    <td v-else align="center">
                                        <button class="btn btn-warning icon-action-undo btn-sm" title="Restaurar Contrato" @click="estadoContrato(contrato)"></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <h4 v-if="divFormulario" class="titazul"><span v-text="accion==1?'Nuevo':'Modificar'"></span> Contrato</h4>
                    <div v-if="divFormulario" class="row">
                        <div class="col-md-2">Número <span class="txtasterisco"></span>
                            <input type="text" class="form-control" v-model="nrcontrato" name="nrcontrato" v-validate="'required|digits:2'" @keyup="valContrato()">
                            <span class="txtvalerror" v-text="errors.first('nrcontrato')"></span>
                        </div>
                        <div class="col-md-2">Tipo <span class="txtasterisco"></span>
                            <select class="form-control" v-model="idtipocontrato" name="Tipo" v-validate="'required'" @change="valContrato()">
                                <option v-for="tipocontrato in arrayTipocontratos" :key="tipocontrato.id"
                                    :value="tipocontrato.idtipocontrato" v-text="tipocontrato.nomtipocontrato">Pasante</option>
                            </select>
                            <span class="txtvalerror" v-text="errors.first('Tipo')"></span>
                        </div>
                        <div class="col-md-3">Inicio <span class="txtasterisco"></span>
                            <input type="date" class="form-control" v-model="inicontrato">
                        </div>
                        <div class="col-md-3">Fin <span class="txtasterisco"></span>
                            <input type="date" class="form-control" v-model="fincontrato">
                        </div>
                        <div class="col-md-2">Salario <span class="txtasterisco"></span>
                            <div class="input-group">
                                <input type="text" class="form-control text-right" v-model="salario">
                                <div class="input-group-append"><span class="input-group-text">Bs.</span></div>
                            </div>
                        </div>
                        <div class="col-md-12" style="padding-top:20px">
                            <div class="ttabla">
                                <div class="tfila">
                                    <div class="tcelda taltura">Observaciones:</div>
                                    <div class="tcelda tinput"><input type="text" class="form-control" v-model="obs"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 text-right txtvalidador">* Datos obligatorios</div>
                    </div>
                </div>
                <div v-if="divFormulario" class="modal-footer">
                    <button class="btn btn-secondary" @click="divFormulario=0">Cancelar</button>
                    <button class="btn btn-primary"  @click="accion==1?storeContrato():updateContrato()">
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
    data(){ return{ 
        modalContratos:0, divFormulario:0,  jsfechas:'', completo:0,
        arrayTipocontratos:[], arrayContratos:[], regEmpleado:[],
        idcontrato:'', nrcontrato:'', idtipocontrato:'', 
        inicontrato:'', fincontrato:'', salario:'', obs:'', valnrcontrato:'',
    }},

    methods:{
        abrirModal(empleado){
            window.scroll({top:0,left:0,behavior:'smooth'});
            this.jsfechas=jsfechas;
            this.modalContratos=1;
            this.listaTipocontratos();
            this.listaContratos(empleado.idempleado);
            this.accion=1;
            this.regEmpleado=empleado;
        },

        listaContratos(idempleado){
            let me=this;
            var url='/rrh_contrato/listaContratos?idempleado='+idempleado;
            axios.get(url).then(function(response){
                me.arrayContratos=response.data.contratos;
            });
        },

        listaTipocontratos(){
            let me=this;
            var url='/rrh_tipocontrato/listaTipocontratos?activo=1';
            axios.get(url).then(function(response){
                me.arrayTipocontratos=response.data.tipocontratos;
            });
        },

        valContrato(){
            this.completo=0;
            if((this.nrcontrato)&&(this.idtipocontrato)&&(this.inicontrato)
                &&(this.fincontrato)&&(this.salario)) this.completo=1;
        },

        vContrato(){
            if(!this.nrcontrato) {
                //this.valnrcontrato=0;
                //console.log(this.valnrcontrato);
                //alert('Especifique número de contrato');
                swal('','especifique nr de contrato','error');
                //$('#nrcontrato').focus();
                this.$refs.nrcontrato.focus();
                return false;
            } else this.valnrcontrato=1;

            if(!this.idtipocontrato) {
                //alert('Seleccione una opción'); 
                //$('#idtipocontrato').focus();
                //this.idtipocontrato
                return true;
            }

            return true;
        },

        nuevoContrato(){
            this.divFormulario=1;
            this.accion=1;
            this.nrcontrato='';  this.completo=0;
            this.idtipocontrato='';
            this.inicontrato='';
            this.fincontrato='';
            this.salario='';
            this.obs='';
        },

        editarContrato(contrato){
            this.divFormulario=1;
            this.accion=2;
            this.idcontrato=contrato.idcontrato;
            this.idempleado=contrato.idempleado;
            this.nrcontrato=contrato.nrcontrato;
            this.idtipocontrato=contrato.idtipocontrato;
            this.inicontrato=contrato.inicontrato;
            this.fincontrato=contrato.fincontrato;
            this.salario=contrato.salario;
            this.obs=contrato.obs;
        },

        storeContrato(){            
            let me=this;
            axios.post('/rrh_contrato/storeContrato',{
                'idempleado':this.regEmpleado.idempleado,
                'nrcontrato':this.nrcontrato,
                'idtipocontrato':this.idtipocontrato,
                'inicontrato':this.inicontrato,
                'fincontrato':this.fincontrato,
                'salario':this.salario,
                'obs':this.obs
            }).then(function(){
                swal('Contrato Guardado','','success');
                me.divFormulario=0;
                me.listaContratos(me.regEmpleado.idempleado);
            });
        },

        updateContrato(){
            let me=this;
            axios.put('/rrh_contrato/updateContrato',{
                'idcontrato':this.idcontrato,
                'idempleado':this.regEmpleado.idempleado,
                'nrcontrato':this.nrcontrato,
                'idtipocontrato':this.idtipocontrato,
                'inicontrato':this.inicontrato,
                'fincontrato':this.fincontrato,
                'salario':this.salario,
                'obs':this.obs
            }).then(function(){
                swal('Datos actualizados','','success');
                me.divFormulario=0;
                me.listaContratos(me.regEmpleado.idempleado);
            });
        },

        estadoContrato(contrato){
            this.idcontrato=contrato.idcontrato;
            if(contrato.activo){
                swal({  title:'Desactivará el contrato '+contrato.nrcontrato, type: 'warning', 
                    html:'', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Contrato',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(confirmar=>{
                    if(confirmar.value) this.switchContrato(1);
                });
            }
            else this.switchContrato(0);
        },

        switchContrato(activo){
            if(activo) var titswal='Desactivado'; else var titswal='Activado';
            var url='/rrh_contrato/switchContrato?idcontrato='+this.idcontrato;
            let me=this;
            axios.put(url).then(function(){
                swal(titswal+' correctamente','','success');
                me.listaContratos(me.regEmpleado.idempleado);
            });
        },


    },
}
</script>