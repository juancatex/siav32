<template>
<main class="main">
    <div class="breadcrumb titmodulo">Activos > Ambientes físicos</div>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-8">
                        <div class="tablatit titcard">
                            <div class="tcelda">Ambientes físicos - Filial <span v-text="regFilial.nommunicipio"></span></div>
                        </div>
                    </div>
                    <div class="col-md-4 text-right">
                        <button class="btn btn-primary" style="margin-top:0" @click="nuevoAmbiente()">Nuevo Ambiente</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row" style="padding-bottom:10px;">
                    <div class="col-md-6">
                        <div class="tfila">
                            <div class="tcelda">Filial:&nbsp;&nbsp;</div>
                            <div class="tcelda">
                                <select class="form-control" v-model="idfilial" @change="listaAmbientes(idfilial,1)">
                                    <option v-for="filial in arrayFiliales" :key="filial.id" 
                                        :value="filial.idfilial" v-text="filial.nommunicipio"></option>
                                </select>
                            </div>
                        </div>                        
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="vervigente">Ver: &nbsp;
                            <input type="radio" name="estado" id="r1" @click="listaAmbientes(idfilial,1)">Vigentes &nbsp;
                            <input type="radio" name="estado" id="r0" @click="listaAmbientes(idfilial,0)">Inactivos
                        </div>
                        <!-- <button class="btn btn-success btn-sm icon-printer" title="Vista de impresión" style="margin-left:10px"
                            @click="reporteAmbientes(idfilial)"></button> -->
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead class="tcabecera">
                            <tr align="center">
                                <th><span class="badge badge-success" v-text="arrayAmbientes.length+' items'"></span></th>
                                <th>Código</th>
                                <th align="left">Ambiente</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="ambiente in arrayAmbientes" :key="ambiente.id" :class="ambiente.activo?'':'txtdesactivado'"> 
                                <td v-if="ambiente.activo" align="center" nowrap>
                                    <button class="btn btn-warning btn-sm icon-pencil" title="Editar Datos"
                                        @click="editarAmbiente(ambiente)"></button>
                                    <button class="btn btn-danger btn-sm icon-trash" title="Desactivar Ambiente"
                                        @click="estadoAmbiente(ambiente)"></button>
                                </td>
                                <td v-else align="center">
                                    <button class="btn btn-warning ing btn-sm icon-action-redo" title="Reactivar Ambiente"
                                        @click="estadoAmbiente(ambiente)"></button>
                                </td>
                                <td v-text="ambiente.codambiente" align="center"></td>
                                <td v-text="ambiente.nomambiente"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="ambientes" class="modal" :class="modalAmbiente?'mostrar':''" >
        <div class="modal-dialog modal-primary">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title"><span v-text="accion==1?'Nuevo':'Modificar'"></span> Ambiente</h4>
                    <button class="close" @click="cerrarmodal('ambientes')">x</button>
                </div>
                <div class="modal-body">
                    <h4 class="titsubrayado">Filial <span v-text="regFilial.nommunicipio"></span></h4>
                    <div class="tabla100">
                        <div class="tcelda">Código asignado:</div>
                        <div class="tcelda">
                            <input type="text" class="form-control txtnegrita text-center" readonly v-model="idambiente">
                        </div>
                    </div>
                    <br>Nombre Ambiente: <span class="txtasterisco"></span>
                        <input type="text" class="form-control" v-model="nomambiente"
                            name="nom" :class="{'invalido':errors.has('nom')}" v-validate="'required'">
                        <p v-if="errors.has('nom')" class="txtvalidador">Dato requerido</p>
                    <br>
                    <div class="txtobligatorio text-right"></div>                    
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="cerrarmodal('ambientes')">Cancelar</button>
                    <button class="btn btn-primary" @click="validarAmbiente()">
                        Guardar <span v-if="accion==2">Modificaciones</span></button>
                </div>
            </div>
        </div>
    </div>

</main>
</template>

<script>
import * as reporte from  '../../functions.js';

export default {
    data(){ return {
        modalAmbiente:0, accion:1, ipbirt:'',
        arrayAmbientes:[], arrayFiliales:[], regFilial:[],
        idambiente:'', idfilial:1, codambiente:'', nomambiente:'',
    }},

    methods:{
        listaAmbientes(idfilial,activo){
            $('#r'+activo).prop('checked',true);
            if(this.arrayFiliales.length) this.verFilial(idfilial);
            var url='/act_ambiente/listaAmbientes?idfilial='+idfilial+'&activo='+activo;
            axios.get(url).then(response=>{
                this.arrayAmbientes=response.data.ambientes;
                this.ipbirt=response.data.ipbirt;
            });
        },

        listaFiliales(){
            var url='/fil_filial/listaFiliales?activo=1';
            axios.get(url).then(response=>{
                this.arrayFiliales=response.data.filiales;
                this.verFilial(this.idfilial);
            });
        },

        verFilial(idfilial){ 
            for(var i=0; i<this.arrayFiliales.length; i++)
                if(this.arrayFiliales[i].idfilial==idfilial) { 
                    this.regFilial=this.arrayFiliales[i]; return; }
        },

        async generarCodigo(){
            var url='/act_ambiente/listaAmbientes?idfilial='+this.idfilial+'&activo=1';
            let response=await axios.get(url);
            var arrayCodigos=response.data.ambientes;
            var tam=arrayCodigos.length-1;
            this.codambiente=0;
            if(tam>=0) this.codambiente=arrayCodigos[tam].codambiente;
            this.codambiente++;
            if(this.codambiente<10) this.codambiente='0'+this.codambiente;
        },

        nuevoAmbiente(){
            this.classModalAmbientes=new _pl.Modals();
            this.classModalAmbientes.addModal('ambientes'); 
            this.classModalAmbientes.openModal('ambientes')
            //this.modalAmbiente=1;
            this.accion=1;
            this.generarCodigo();
            this.nomambiente='';
            this.$validator.reset();
        },

        editarAmbiente(ambiente){         
            this.classModalAmbientes=new _pl.Modals();
            this.classModalAmbientes.addModal('ambientes'); 
            this.classModalAmbientes.openModal('ambientes') 
            //window.scroll({top:0,left:0,behavior:'smooth'});  
            //this.modalAmbiente=1;
            this.accion=2;
            this.idambiente=ambiente.idambiente;
            this.nomambiente=ambiente.nomambiente;
        },

        cerrarmodal(id){ 
                this.classModalAmbientes.closeModal(id); 
        },

        validarAmbiente(){
            this.$validator.validateAll().then(result=>{
                if(!result) {swal('Datos inválidos','Revise los errores','error'); return;}
                this.accion==1?this.storeAmbiente():this.updateAmbiente();
            });
        },

        storeAmbiente(){
            axios.post('act_ambiente/storeAmbiente',{
                'idfilial':this.idfilial,
                'codambiente':this.codambiente,
                'nomambiente':this.nomambiente.toUpperCase(),
            }).then(response=>{
                swal('Ambiente creado correctamente','','success');
                this.listaAmbientes(this.idfilial,1);
                this.cerrarmodal('ambientes');
            });
        },

        updateAmbiente(){
            axios.put('act_ambiente/updateAmbiente',{
                'idambiente':this.idambiente,
                'nomambiente':this.nomambiente.toUpperCase(),
            }).then(response=>{
                swal('Datos actualizados','','success');
                this.cerrarmodal('ambientes');
                this.listaAmbientes(this.idfilial,1);
            });
        },

        estadoAmbiente(ambiente){
            this.idambiente=ambiente.idambiente;
            if(ambiente.activo){
                swal({title:'Desactivará el Ambiente<br>'+ambiente.nomambiente, type:'warning',
                    html: 'No podrá acceder a la información dependiente', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Ambiente',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(confirmar=>{confirmar.value?this.switchAmbiente(0):''});
            }
            else this.switchAmbiente(1);
        },

        switchAmbiente(activo){
            var url='/act_ambiente/switchAmbiente?idambiente='+this.idambiente;
            axios.put(url).then(response=>{
                swal(activo?'Activado correctamente':'Desactivado correctamente','','success');
                this.listaAmbientes(this.idfilial,activo);
            });
        },

        reporteAmbientes(idfilial){
            var url=[];
            url.push('http://'+this.ipbirt+':8080');
            url.push('/birt-viewer/frameset?__report=reportes/activos');
            url.push('/act_ambientes.rptdesign');
            url.push('&idfilial='+idfilial);
            url.push('&__format=pdf'); 
            reporte.viewPDF(url.join(''),'Listado de Ambientes');
        },

    },

    mounted(){
        this.listaFiliales();
        this.listaAmbientes(this.idfilial,1);
    }
}
</script>
