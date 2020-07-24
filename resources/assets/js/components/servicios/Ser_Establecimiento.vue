<template>
<main class="main">
    <div class="breadcrumb titmodulo">Servicios > Establecimientos</div>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6 titcard" >
                        <div class="tablatit"> 
                            <div class="tcelda">Servicios Departamento de <span v-text="regDepartamento.nomdepartamento"></span></div>
                        </div> 
                    </div>
                    <div class="col-md-6 text-right ">
                        <div class="input-group-append" style="display:inline">
                            <button class="btn btn-primary dropdown-toggle" style="margin-top:0px" 
                                data-toggle="dropdown" aria-expanded="false">
                                Departamento... <span class="caret"></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end"> 
                                <a href="#" class="dropdown-item" v-for="departamento in arrayDepartamentos" 
                                :key="departamento.iddepartamento" v-text="departamento.nomdepartamento" 
                                @click="listaEstablecimientos(departamento.iddepartamento)"></a>
                            </div>
                        </div>
                        <button class="btn btn-primary" style="margin-top:0px" @click="nuevoEstablecimiento()">Nuevo Establecimiento</button>
                    </div>
                </div>
            </div> 

            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped table-sm">
                    <thead class="tcabecera">

                        <tr>
                            <th><span class="badge badge-success" v-text="arrayEstablecimientos.length+' items'"></span></th>
                            <th>Filial</th>
                            <th>Servicio</th>
                            <th>Establecimiento</th>
                            <th>Dirección</th>
                            <th>Tel y/o Cel</th>
                            <th>Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="establecimiento in arrayEstablecimientos" :key="establecimiento.idestablecimiento" :class="establecimiento.activo?'':'txtdesactivado'">
                            <td v-if="establecimiento.activo" align="center" nowrap>
                                <button class="btn btn-warning btn-sm icon-pencil" title="Editar Establecimiento"
                                    @click="editarEstablecimiento(establecimiento)"></button> 
                                <button class="btn btn-warning btn-sm icon-settings" title="Configuración de Ambientes"
                                    @click="$refs.config_establecimiento.abrirModal(establecimiento);"></button>
                                <button class="btn btn-warning btn-sm icon-docs" title="Requisitos para este servicio"
                                    @click="requisitosEstablecimiento(establecimiento)"></button> 
                                <button class="btn btn-danger btn-sm icon-trash" title="Desactivar Establecimiento"
                                    @click="estadoEstablecimiento(establecimiento)"></button>
                            </td>
                            <td v-else align="center">
                                <button class="btn btn-warning btn-sm icon-action-redo" title="Reactivar establecimiento"
                                    @click="estadoEstablecimiento(establecimiento)"></button>
                            </td>
                            <td v-text="establecimiento.nommunicipio" nowrap></td>
                            <td v-text="establecimiento.nomservicio"></td>
                            <td v-text="establecimiento.nomestablecimiento"></td>
                            <td v-text="establecimiento.direccion"></td>
                            <td v-text="establecimiento.telefonos"></td>
                            <td v-text="establecimiento.descripcion"></td>
                        </tr>                                
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL  ESTABLECIMIENTO  MODAL  ESTABLECIMIENTO MODAL  ESTABLECIMIENTO-->
    <!-- MODAL  ESTABLECIMIENTO  MODAL  ESTABLECIMIENTO MODAL  ESTABLECIMIENTO-->
    <div class="modal" :class="modalEstablecimiento?'mostrar':''">
        <div class="modal-dialog modal-primary">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button class="close" @click="modalEstablecimiento=0">x</button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            Filial: <span class="txtasterisco"></span>
                                <select class="form-control" v-model="idfilial" @change="valEstablecimiento()">
                                    <option v-for="filial in arrayFiliales" :key="filial.idfilial"
                                        :value="filial.idfilial" v-text="filial.nommunicipio"></option>
                                </select>
                            Servicio: <span class="txtasterisco"></span>
                                <select class="form-control" v-model="idservicio" @change="valEstablecimiento()">
                                    <option v-for="servicio in arrayServicios" :key="servicio.idservicio"
                                        :value="servicio.idservicio" v-text="servicio.nomservicio"></option>
                                </select>
                            Denominación: <span class="txtasterisco"></span>
                                <input type="text" class="form-control" v-model="nomestablecimiento" @keyup="valEstablecimiento()">
                        </div>
                        <div class="col-md-6">
                            Dirección:
                                <input type="text" class="form-control" v-model="direccion" >
                            Teléfono y/o Celular:
                                <input type="text" class="form-control" v-model="telefonos" >
                            Descripción:
                                <input type="text" class="form-control" v-model="descripcion" >
                        </div>
                        <div class="col-md-12 txtvalidador">* Campos obligatorios</div>
                    </div>
                </div>


                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="modalEstablecimiento=0">Cerrar</button>
                    <button class="btn btn-primary" v-text="txtBoton" :disabled="!completo"
                        @click="accion==1?storeEstablecimiento():updateEstablecimiento()" ></button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- MODAL REQUSITOS  MODAL REQUSITOS  MODAL REQUSITOS  MODAL REQUSITOS  MODAL REQUSITOS -->
    <!-- MODAL REQUSITOS  MODAL REQUSITOS  MODAL REQUSITOS  MODAL REQUSITOS  MODAL REQUSITOS -->
    <div class="modal" :class="modalRequisitos?'mostrar':''">
        <div class="modal-dialog modal-primary modal-sm">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button class="close" @click="modalRequisitos=0">x</button>
                </div>
                <div class="modal-body">
                    <p class="titsubrayado">Para acceder al servicio<br><span v-text="nomestablecimiento"></span></p>
                    <div v-for="documento in arrayDocumentos" :key="documento.iddocumento" class="form-check checkbox">
                        <input type="checkbox" :value="documento.iddocumento" v-model="arrayRequisitos">
                        <span v-text="documento.nomdocumento"></span>
                    </div>
                </div> 
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="modalRequisitos=0">Cerrar</button>
                    <button class="btn btn-primary" @click="storeRequisitos()">Guardar requisitos</button>
                </div>
            </div>
        </div>
    </div>

    <config_establecimiento ref="config_establecimiento"></config_establecimiento>    
</main>
</template>

<script>
Vue.component('config_establecimiento', require('./config_Establecimiento.vue').default);

export default {
    data (){ return {
        modalEstablecimiento:0, modalAmbientes:0, modalRequisitos:0,
        tituloModal:'', accion:1, txtBoton:'', completo:'',
        arrayEstablecimientos:[], arrayDepartamentos:[], arrayFiliales:[], 
        arrayServicios:[], arrayDocumentos:[], arrayRequisitos:[],
        regDepartamento:[], 
        idestablecimiento:'', iddepartamento:2, idfilial:'', idservicio:'', 
        nomestablecimiento:'',direccion:'', telefonos:'', descripcion:'',
        cantgrupos:'',
    }},

    methods: {
        listaEstablecimientos (iddepartamento){
            if(this.arrayDepartamentos.length) this.verDepartamento(iddepartamento);
            let me=this;
            var url= '/ser_establecimiento/listaEstablecimientos?iddepartamento='+iddepartamento;
            axios.get(url).then(function (response) {
                me.arrayEstablecimientos=response.data.establecimientos;
            });
        },

        listaDepartamentos(){
            let me=this;
            var url='/par_departamento/listaDepartamentos';
            axios.get(url).then(function(response){
                me.arrayDepartamentos=response.data.departamentos;
                me.verDepartamento(me.iddepartamento);
            });
        },

        listaFiliales(iddepartamento){
            let me=this;
            var url='/fil_filial/listaFiliales?activo=1&iddepartamento='+iddepartamento;
            axios.get(url).then(function(response){
                me.arrayFiliales=response.data.filiales;
            });
        },

        listaServicios(){
            let me=this;
            var url='/ser_servicio/listaServicios?activo=1';
            axios.get(url).then(function(response){
                me.arrayServicios=response.data.servicios;
            });
        },

        listaDocumentos(){
            let me=this;
            var url='/par_documento/listaDocumentos?idmodulo=4&activo=1';
            axios.get(url).then(function(response){
                me.arrayDocumentos=response.data.documentos;
            });
        },

        verDepartamento(iddepartamento){
            for(var i=0; i<this.arrayDepartamentos.length; i++)
                if(this.arrayDepartamentos[i].iddepartamento==iddepartamento)
                    {this.regDepartamento=this.arrayDepartamentos[i]; return;}
        },

        resetEstablecimiento(){
            this.idfilial='';
            this.idservicio='';
            this.cantgrupos='';
            this.nomestablecimiento='';
            this.descripcion='';
            this.direccion='';
            this.telefonos='';
        },

        nuevoEstablecimiento(){
            this.modalEstablecimiento=1;
            this.accion=1;
            this.tituloModal='Nuevo Establecimiento';
            this.txtBoton='Guardar';
            this.completo=0;
            this.resetEstablecimiento();
            this.listaFiliales(this.regDepartamento.iddepartamento);
        },

        editarEstablecimiento(establecimiento){
            this.modalEstablecimiento=1;
            this.accion=2;
            this.tituloModal='Modificar datos';
            this.txtBoton='Guardar modificaciones';
            this.completo=1;
            this.listaFiliales(establecimiento.iddepartamento);
            this.idestablecimiento=establecimiento.idestablecimiento;
            this.iddepartamento=establecimiento.iddepartamento;
            this.idfilial=establecimiento.idfilial;
            this.idservicio=establecimiento.idservicio;
            this.nomestablecimiento=establecimiento.nomestablecimiento;
            this.direccion=establecimiento.direccion;
            this.telefonos=establecimiento.telefonos;
            this.descripcion=establecimiento.descripcion;
            this.config=establecimiento.idimplementos;
        },

        requisitosEstablecimiento(establecimiento){
            this.listaDocumentos();
            this.modalRequisitos=1;
            this.accion=1;
            this.tituloModal='Requisitos';
            this.idestablecimiento=establecimiento.idestablecimiento;
            this.nomestablecimiento=establecimiento.nomestablecimiento;
            this.iddepartamento=establecimiento.iddepartamento;
            this.arrayRequisitos=JSON.parse('['+establecimiento.iddocumentos+']');
        },

        valEstablecimiento(){
            this.completo=0;
            if(this.idservicio==4) this.nomestablecimiento='MAUSOLEO';
            if((this.idfilial)&&(this.idservicio)&&(this.nomestablecimiento)) this.completo=1;
        },

        storeEstablecimiento(){
            var url='/ser_establecimiento/storeEstablecimiento';
            let me=this;
            axios.post(url,{
                'idfilial':this.idfilial,
                'idservicio':this.idservicio,
                'configuracion':this.idservicio==4?'[0,100]':'',
                'nomestablecimiento':this.nomestablecimiento.toUpperCase(),
                'direccion':this.direccion,
                'telefonos':this.telefonos,
                'descripcion':this.descripcion,
            }).then(function(){
                swal('Establecimiento creado correctamente','','success');
                me.modalEstablecimiento=0;
                me.listaEstablecimientos(me.regDepartamento.iddepartamento);
            });
        },

        updateEstablecimiento(){
            var url='/ser_establecimiento/updateEstablecimiento';
            let me=this;
            axios.put(url,{
                'idestablecimiento':this.idestablecimiento,
                'idfilial':this.idfilial,
                'idservicio':this.idservicio,
                'nomestablecimiento':this.nomestablecimiento.toUpperCase(),
                'direccion':this.direccion,
                'telefonos':this.telefonos,
                'descripcion':this.descripcion,
            }).then(function(){
                swal('Datos actualizados correctamente','','success');
                me.modalEstablecimiento=0;
                me.listaEstablecimientos(me.iddepartamento);
            });
        },
        
        estadoEstablecimiento(establecimiento){
            this.idestablecimiento=establecimiento.idestablecimiento;
            if(establecimiento.activo){
                swal({  title:'Desactivará '+establecimiento.nomestablecimiento, type: 'warning', 
                    html: 'No podrá acceder a la información dependiente', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Establecimiento',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(confirmar=>{
                    if(confirmar.value) this.switchEstablecimiento(1);
                });
            }
            else this.switchEstablecimiento(0);
        },
        
        switchEstablecimiento(activo){
            if(activo) var titswal='Desactivado'; else var titswal='Activado';
            var url='/ser_establecimiento/switchEstablecimiento?idestablecimiento='+this.idestablecimiento;
            let me=this;
            axios.put(url).then(function(){
                swal(titswal+' correctamente','','success');
                me.listaEstablecimientos(me.iddepartamento);
            });
        },

        storeRequisitos(){
            var url='/ser_establecimiento/storeRequisitos?idestablecimiento='+this.idestablecimiento;
            let me=this;
            axios.put(url,{
                'iddocumentos':this.arrayRequisitos.join()
            }).then(function(){
                swal('Requisitos guardados','','success');
                me.modalRequisitos=0;
                me.listaEstablecimientos(me.iddepartamento);
            });
        },

    },

    mounted() {
        this.listaDepartamentos();
        this.listaFiliales(2); 
        this.listaServicios();        
        this.listaEstablecimientos(2);
    }
}
</script>
