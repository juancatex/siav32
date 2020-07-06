<template>
<main class="main">
    <div class="breadcrumb titmodulo">Servicios > Tipos de Servicio</div>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6 titcard">
                        <div class="ttabla">
                            <div class="tcelda">Tipos de Servicio</div>
                        </div>
                    </div>
                    <div class="col-md-6 text-right">
                        <button class="btn btn-primary" style="margin-top:0px" @click="nuevoServicio()">Nuevo Servicio</button>
                    </div>
                </div>                
            </div> 
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped table-sm">
                    <thead class="tcabecera">
                        <tr>
                            <th><span class="badge badge-success" v-text="arrayServicios.length+' items'"></span></th>
                            <th>Código</th>
                            <th>Servicio</th>
                            <th>Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="servicio in arrayServicios" :key="servicio.idservicio" :class="servicio.activo?'':'txtdesactivado'">
                            <td v-if="servicio.activo" align="center" nowrap>
                                <button class="btn btn-warning btn-sm icon-pencil" title="Editar datos" 
                                    @click="editarServicio(servicio)"></button> 
                                <button class="btn btn-danger btn-sm icon-trash" title="Desactivar servicio" 
                                    @click="estadoServicio(servicio)"></button>
                            </td>
                            <td v-else align="center">
                                <button class="btn btn-warning btn-sm icon-action-redo" title="Reactivar servicio"
                                    @click="estadoServicio(servicio)"></button>
                            </td>
                            <td v-text="servicio.codservicio" align="center"></td>
                            <td v-text="servicio.nomservicio"></td>
                            <td v-text="servicio.descripcion"></td>
                        </tr>                                
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!--MODAL NUEVO SERVICIO MODAL NUEVO SERVICIO MODAL NUEVO SERVICIO MODAL NUEVO SERVICIO -->
    <!--MODAL NUEVO SERVICIO MODAL NUEVO SERVICIO MODAL NUEVO SERVICIO MODAL NUEVO SERVICIO -->
    <!--MODAL NUEVO SERVICIO MODAL NUEVO SERVICIO MODAL NUEVO SERVICIO MODAL NUEVO SERVICIO -->
    <div class="modal" :class="modalServicio?'mostrar':''">
        <div class="modal-dialog modal-primary modal-sm">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" class="close" @click="modalServicio=0">x</button>
                </div>
                <div class="modal-body">
                    <div>
                        <div class="ttabla">
                            <div class="tfila">
                                <div class="tcelda taltura nowrap">Código: <span class="txtasterisco"></span></div>
                                <div class="tcelda tinput">
                                    <input  type="text" class="form-control" v-model="codservicio" maxlength="3"  @keyup="valServicio()" placeholder="(tres letras)">
                                </div>
                            </div>
                        </div>
                    </div>                    
                    Servicio: <span class="txtasterisco"></span>
                    <input  type="text" v-model="nomservicio" class="form-control" @keyup="valServicio()" >
                    <br>Descripción:
                    <textarea rows="2" class="form-control" style="resize:none" v-model="descripcion"></textarea>
                    <div class="txtvalidador text-center">* Campos obligatorios</div>
                </div>        
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="modalServicio=0">Cerrar</button>
                    <button class="btn btn-primary" :disabled="!completo" v-text="txtBoton" @click="accion==1?storeServicio():updateServicio()"></button>
                </div>
            </div>
        </div>
    </div>
</main>
</template>


<script>
export default {
    data (){ return {
        modalServicio:0, tituloModal:'', accion:1, completo:'', txtBoton:'',
        arrayServicios:[], idservicio:'', codservicio:'', nomservicio:'', descripcion:'',
    }},

    methods : {
        listaServicios(){
            let me=this;
            var url= '/ser_servicio/listaServicios';
            axios.get(url).then(function (response) {                
                me.arrayServicios = response.data.servicios;
            });
        },

        resetServicio(){
            this.codservicio='';
            this.nomservicio='';
            this.descripcion='';
        },

        nuevoServicio(){ 
            this.modalServicio=1;
            this.accion=1;
            this.tituloModal='Nuevo Servicio';
            this.txtBoton='Guardar';
            this.completo=0;
            this.resetServicio();
        },

        editarServicio(servicio){
            this.modalServicio=1;
            this.accion=2;
            this.tituloModal='Editar Servicio';
            this.txtBoton='Guardar modificaciones';
            this.completo=1;
            this.idservicio =servicio.idservicio;
            this.codservicio=servicio.codservicio;
            this.nomservicio=servicio.nomservicio;
            this.descripcion=servicio.descripcion;
        },

        valServicio(){
            this.completo=0;
            if((this.codservicio)&&(this.nomservicio)) this.completo=1;
        },

        storeServicio(){
            let me=this;
            axios.post('/ser_servicio/storeServicio',{
                'codservicio':this.codservicio.toUpperCase(),
                'nomservicio':this.nomservicio,
                'descripcion':this.descripcion
            }).then(function (response) {
                swal('Servicio creado correctamente','','success');
                me.listaServicios();
                me.modalServicio=0;
            });
        },
        
        updateServicio(){
            let me = this;
            axios.put('/ser_servicio/updateServicio',{
                'idservicio': this.idservicio,
                'codservicio':this.codservicio.toUpperCase(),
                'nomservicio':this.nomservicio,
                'descripcion':this.descripcion,
            }).then(function () {
                swal('Actualizado correctamente','','success');
                me.listaServicios();
                me.modalServicio=0;
            }); 
        },

        estadoServicio(servicio){
            if(servicio.activo){
                swal({  title:'Desactivará el Servicio '+servicio.nomservicio, type: 'warning',
                    html: 'No podrá acceder a la información dependiente', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Servicio',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(confirmar=>{
                    if(confirmar.value) this.switchServicio(servicio.idservicio,1);
                });
            }
            else this.switchServicio(servicio.idservicio,0);
        },
        
        switchServicio(idservicio,activo){
            if(activo) var titswal='Desactivado'; else var titswal='Activado';
            var url='/ser_servicio/switchServicio?idservicio='+idservicio;
            let me=this;
            axios.put(url).then(function(){
                swal(titswal+' correctamente','','success');
                me.listaServicios();
            });
        },        

    },

    mounted() {
        this.listaServicios();
    }
}
</script>
