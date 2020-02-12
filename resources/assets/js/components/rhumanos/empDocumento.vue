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
                    <button class="btn btn-primary" style="margin-top:0" @click="nuevoPresentado()">Nuevo Documento</button>
                </div>
            </div>            
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped table-sm">
                <thead class="tcabecera">
                    <tr align="center">
                        <th></th>
                        <th>Nro</th>
                        <th align="left">Documento</th>
                        <th>Fecha </th>
                        <th>Formato</th>
                        <th align="left">Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(presentado,index) in arrayPresentados" :key="presentado.id" :class="presentado.activo?'':'txtdesactivado'">
                        <td v-if="presentado.activo" align="center">
                            <button class="btn btn-warning icon-pencil btn-sm" title="Actualizar" @click="editarPresentado(presentado)"></button>
                            <button class="btn btn-danger  icon-trash  btn-sm" title="Desactivar" @click="estadoPresentado(presentado)"></button>
                        </td>
                        <td v-else align="center">
                            <button class="btn btn-warning icon-action-redo btn-sm" title="Restaurar" @click="estadoPresentado(presentado)"></button>
                        </td>
                        <td v-text="index+1" align="center"></td>
                        <td v-text="presentado.nomdocumento"></td>
                        <td v-text="jsfechas.fechames(presentado.fechapres)" align="center"></td>
                        <td align="center">
                            <span v-if="presentado.idformato==1" v-text="arrayFormatos[1]"></span>
                            <span v-if="presentado.idformato==2" v-text="arrayFormatos[2]"></span>
                            <span v-if="presentado.idformato==3" v-text="arrayFormatos[3]"></span>
                        </td>
                        <td v-text="presentado.obs"></td>
                    </tr>
                </tbody>
            </table>                        
        </div>
    </div>
    <div class="modal" :class="modalDocumentos?'mostrar':''">
        <div class="modal-dialog modal-primary">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title"><span v-text="accion==1?'Nuevo':'Modificar'"></span> Documento</h4>
                    <button class="close" @click="modalDocumentos=0">x</button>
                </div>
                <div class="modal-body">
                    <h4 class="titsubrayado" v-text="regEmpleado.nombre+' '+regEmpleado.apaterno"></h4>
                    <div class="row">
                        <div class="col-md-6">
                            Documento: <span class="txtasterisco"></span>
                            <select class="form-control" v-model="iddocumento"
                                name="doc" :class="{'invalido':errors.has('doc')}" v-validate="'required'">
                                <option v-for="documento in arrayDocumentos" :key="documento.id"
                                    :value="documento.iddocumento" v-text="documento.nomdocumento"></option>
                            </select>
                            <p v-if="errors.has('doc')" class="txtvalidador">Seleccione un Documento</p>
                            Formato: <span class="txtasterisco"></span>
                            <select class="form-control" v-model="idformato"
                                name="fmt" :class="{'invalido':errors.has('fmt')}" v-validate="'required'">
                                <template v-for="(id,index) in arrayFormatos">
                                    <option v-if="index>0" :key="id" :value="index" v-text="id"></option>
                                </template>
                            </select>
                            <p v-if="errors.has('fmt')" class="txtvalidador">Seleccione un Formato</p>
                        </div>
                        <div class="col-md-6">
                            Fecha de Presentación: <span class="txtasterisco"></span>
                            <input type="date" class="form-control" v-model="fechapres"
                                name="pre" :class="{'invalido':errors.has('pre')}" v-validate="'required'">
                            <p v-if="errors.has('pre')" class="txtvalidador">Dato requerido</p>
                            Observaciones:
                            <input type="text" class="form-control" v-model="obs">
                        </div>
                    </div>
                    <p class="text-center txtobligatorio"></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="modalDocumentos=0">Cerrar</button>
                    <button class="btn btn-primary" @click="validarPresentado()">
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

    data(){ return {
        modalDocumentos:'', accion:'', jsfechas:'',
        arrayDocumentos:[], arrayPresentados:[], arrayDocumentos:[], 
        iddocumento:'', fechapres:'', obs:'', idformato:'',
        arrayFormatos:['','Original','Copia Legalizada','Fotocopia Simple'],
    }},

    methods:{
        listaDocumentos(){
            var url='/par_documento/listaDocumentos?idmodulo=11&activo=1';
            axios.get(url).then(response=>{
                this.arrayDocumentos=response.data.documentos;
            });
        },

        listaPresentados(idempleado){
            var url='/rrh_presentado/listaPresentados?idempleado='+idempleado;
            axios.get(url).then(response=>{
                this.arrayPresentados=response.data.presentados;
            });
        },

        nuevoPresentado(){
            this.modalDocumentos=1;
            this.accion=1;
            this.listaDocumentos();
            this.iddocumento='';
            this.fechapres='';
            this.idformato='';
            this.obs='';
            this.$validator.reset();
        },

        editarPresentado(presentado){
            this.modalDocumentos=1;
            this.accion=2;
            this.listaDocumentos();
            this.idpresentado=presentado.idpresentado;
            this.idempleado=presentado.idempleado;
            this.iddocumento=presentado.iddocumento;
            this.fechapres=presentado.fechapres;
            this.idformato=presentado.idformato;
            this.obs=presentado.obs;
        },

        validarPresentado(){
            this.$validator.validateAll().then(result=>{
                if(!result) {swal('Datos no válidos','Revise los errores','error'); return; }
                this.accion==1?this.storePresentado():this.updatePresentado();
            })
        },

        storePresentado(){
            var url='/rrh_presentado/storePresentado';
            axios.post(url,{
                'idempleado':this.regEmpleado.idempleado,
                'iddocumento':this.iddocumento,
                'idformato':this.idformato,
                'fechapres':this.fechapres,
                'obs':this.obs
            }).then(response=>{
                swal('Documento registrado correctamente','','success');
                this.listaPresentados(this.regEmpleado.idempleado);
                this.modalDocumentos=0;
            });
        },

        updatePresentado(){
            var url='/rrh_presentado/updatePresentado';
            axios.put(url,{
                'idpresentado':this.idpresentado,
                'idempleado':this.regEmpleado.idempleado,
                'iddocumento':this.iddocumento,
                'idformato':this.idformato,
                'fechapres':this.fechapres,
                'obs':this.obs
            }).then(response=>{
                swal('Se han actualizado los datos','','success');
                this.listaPresentados(this.regEmpleado.idempleado);
                this.modalDocumentos=0;
            });
        },

        estadoPresentado(presentado){
            this.idpresentado=presentado.idpresentado;
            if(presentado.activo){
                swal({  title:'Desactivará<br>'+presentado.nomdocumento, type: 'warning', 
                    html:'', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Documento',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(confirmar=>{ confirmar.value?this.switchPresentado(0):''});
            }
            else this.switchPresentado(1);
        },

        switchPresentado(activo){
            var url='/rrh_presentado/switchPresentado?idpresentado='+this.idpresentado;
            axios.put(url).then(response=>{
                swal(activo?'Activado correctamente':'Desactivado correctamente','','success');
                this.listaPresentados(this.regEmpleado.idempleado);
            });
        },
    },

    mounted(){
        this.jsfechas=jsfechas;
        this.listaPresentados(this.regEmpleado.idempleado);
    },
}
</script>