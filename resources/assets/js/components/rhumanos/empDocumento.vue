<template>
<main class="main">
    <div class="modal" :class="modalDocumentos?'mostrar':''">
        <div class="modal-dialog modal-primary modal-lg">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title">Documentos Presentados</h4>
                    <button class="close" @click="modalDocumentos=0">x</button>
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
                            <button v-if="!divFormulario" class="btn btn-primary" @click="nuevoPresentado()">Registrar Documento</button>
                        </div>
                    </div>
                    <br>
                    <div v-if="!divFormulario" class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead class="tcabecera">
                                <tr>
                                    <th>Nro</th>
                                    <th>Documento</th>
                                    <th>Fecha </th>
                                    <th>Formato</th>
                                    <th>Observaciones</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(presentado,index) in arrayPresentados" :key="presentado.id" :class="presentado.activo?'':'txtdesactivado'">
                                    <td v-text="index+1" align="center"></td>
                                    <td v-text="presentado.nomdocumento"></td>
                                    <td v-text="jsfechas.fechames(presentado.fechapres)" align="center"></td>
                                    <td><span v-if="presentado.idformato==1" v-text="arrayFormatos[1]"></span>
                                        <span v-if="presentado.idformato==2" v-text="arrayFormatos[2]"></span>
                                        <span v-if="presentado.idformato==3" v-text="arrayFormatos[3]"></span>
                                    </td>
                                    <td v-text="presentado.obs"></td>
                                    <td v-if="presentado.activo" align="center">
                                        <button class="btn btn-warning icon-pencil btn-sm" title="Actualizar" @click="editarPresentado(presentado)"></button>
                                        <button class="btn btn-danger  icon-trash  btn-sm" title="Desactivar" @click="estadoPresentado(presentado)"></button>
                                    </td>
                                    <td v-else align="center">
                                        <button class="btn btn-warning icon-action-undo btn-sm" title="Restaurar" @click="estadoPresentado(presentado)"></button>
                                    </td>
                                </tr>

                            </tbody>
                        </table>                        
                    </div>
                    <h4 v-if="divFormulario" class="titazul"><span v-text="accion==1?'Nuevo':'Editar'"></span> Documento</h4>
                    <div v-if="divFormulario" class="row">
                        <div class="col-md-4">Documento <span class="txtasterisco"></span>
                            <select class="form-control" v-model="iddocumento">
                                <option v-for="documento in arrayDocumentos" :key="documento.id"
                                    :value="documento.iddocumento" v-text="documento.nomdocumento"></option>
                            </select>
                        </div>
                        <div class="col-md-4">Formato: <span class="txtasterisco"></span>
                            <select class="form-control" v-model="idformato">
                                <template v-for="(id,index) in arrayFormatos">
                                    <option v-if="index>0" :key="id" :value="index" v-text="id"></option>
                                </template>
                            </select>
                        </div>
                        <div class="col-md-4">Fecha Presentación: <span class="txtasterisco"></span>
                            <input type="date" class="form-control" v-model="fechapres">
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
                    <button class="btn btn-primary" @click="accion==1?storePresentado():updatePresentado()" v-text="txtBoton"></button>
                </div>
            </div>
        </div>
    </div>    
</main>
</template>

<script>
import * as jsfechas from '../../fechas.js';

export default {
    data(){ return {
        divFormulario:0, jsfechas:'',
        modalDocumentos:'', regEmpleado:[],
        arrayDocumentos:[], arrayPresentados:[], 
        iddocumento:'', fechapres:'', obs:'', arrayDocumentos:[], idformato:'', arrayPresentados:[],
        arrayFormatos:['','Original','Copia Legalizada','Fotocopia Simple'],
    }},

    methods:{
        abrirModal(empleado){
            window.scroll({top:0,left:0,behavior:'smooth'});
            this.jsfechas=jsfechas;
            this.listaDocumentos();
            this.listaPresentados(empleado.idempleado);
            this.modalDocumentos=1;
            this.regEmpleado=empleado;
        },

        listaDocumentos(){
            let me=this;
            var url='/par_documento/listaDocumentos?idmodulo=11&activo=1';
            axios.get(url).then(function(response){
                me.arrayDocumentos=response.data.documentos;
            });
        },

        listaPresentados(idempleado){
            let me=this;
            var url='/rrh_presentado/listaPresentados?idempleado='+idempleado;
            axios.get(url).then(function(response){
                me.arrayPresentados=response.data.presentados;
            });
        },

        nuevoPresentado(){
            this.divFormulario=1;
            this.txtBoton='Guardar';
            this.accion=1;
            this.iddocumento='';
            this.fechapres='';
            this.idformato='';
            this.obs='';
        },

        editarPresentado(presentado){
            this.listaDocumentos();
            this.idpresentado=presentado.idpresentado;
            this.idempleado=presentado.idempleado;
            this.iddocumento=presentado.iddocumento;
            this.fechapres=presentado.fechapres;
            this.idformato=presentado.idformato;
            this.obs=presentado.obs;
            this.txtBoton='Guardar modificaciones';
            this.accion=2;
            this.divFormulario=1;
        },

        storePresentado(){
            let me=this;
            var url='/rrh_presentado/storePresentado';
            axios.post(url,{
                'idempleado':this.regEmpleado.idempleado,
                'iddocumento':this.iddocumento,
                'idformato':this.idformato,
                'fechapres':this.fechapres,
                'obs':this.obs
            }).then(function(){
                swal('Documento registrado correctamente','','success');
                me.listaPresentados(me.regEmpleado.idempleado);
                me.divFormulario=0;
            });
        },

        updatePresentado(){
            let me=this;
            var url='/rrh_presentado/updatePresentado';
            axios.put(url,{
                'idpresentado':this.idpresentado,
                'idempleado':this.regEmpleado.idempleado,
                'iddocumento':this.iddocumento,
                'idformato':this.idformato,
                'fechapres':this.fechapres,
                'obs':this.obs
            }).then(function(){
                swal('Se han actualizado los datos','','success');
                me.listaPresentados(me.regEmpleado.idempleado);
                me.divFormulario=0;
            });
        },

        estadoPresentado(presentado){
            this.idpresentado=presentado.idpresentado;
            if(presentado.activo){
                swal({  title:'Desactivará<br>'+presentado.nomdocumento, type: 'warning', 
                    html:'', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Documento',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(confirmar=>{
                    if(confirmar.value) this.switchPresentado(1);
                });
            }
            else this.switchPresentado(0);
        },

        switchPresentado(activo){
            if(activo) var titswal='Desactivado'; else var titswal='Activado';
            var url='/rrh_presentado/switchPresentado?idpresentado='+this.idpresentado;
            let me=this;
            axios.put(url).then(function(){
                swal(titswal+' correctamente','','success');
                me.listaPresentados(me.regEmpleado.idempleado);
            });
        },


    },
}
</script>