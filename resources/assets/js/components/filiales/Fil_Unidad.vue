<template>
<main class="main">
    <div class="breadcrumb titmodulo">Filiales > Unidades</div>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6 titcard">
                        <div class="tablatit"><div class="tcelda">Unidades </div></div>
                    </div>
                    <div class="col-md-6 text-right">
                        <button class="btn btn-primary" style="margin-top:0" @click="nuevaUnidad()">Nueva Unidad</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="text-right" style="padding-bottom:10px">
                    <div class="vervigente"> Ver: &nbsp;
                        <input type="radio" name="estado" id="r1" @click="listaUnidades(1)">Vigentes &nbsp;
                        <input type="radio" name="estado" id="r0" @click="listaUnidades(0)">Inactivas
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead class="tcabecera">
                            <tr>
                                <th align="center"><span class="badge badge-success" v-text="arrayUnidades.length+' items'"></span></th>
                                <th align="center">Código</th>
                                <th align="center">Abrev</th>
                                <th>Nombre Unidad</th>
                                <th>Cargo Relacionado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="unidad in arrayUnidades" :key="unidad.id" :class="unidad.activo?'':'txtdesactivado'" align="center">
                                <td v-if="unidad.activo" nowrap>
                                    <button class="btn btn-warning btn-sm icon-pencil" title="Editar" @click="editarUnidad(unidad)"></button>
                                    <button class="btn btn-danger btn-sm icon-trash" title="Desactivar" @click="estadoUnidad(unidad)"></button>
                                </td>
                                <td v-else >
                                    <button class="btn btn-warning btn-sm icon-action-redo" title="Reactivar unidad" @click="estadoUnidad(unidad)"></button>
                                </td>
                                <td v-text="unidad.codunidad"></td>
                                <td v-text="unidad.abrev"></td>
                                <td v-text="unidad.nomunidad" align="left"></td>
                                <td v-text="unidad.nomcargo" align="left"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL UNIDAD MODAL UNIDAD MODAL UNIDAD MODAL UNIDAD MODAL UNIDAD MODAL UNIDAD -->
    <!-- MODAL UNIDAD MODAL UNIDAD MODAL UNIDAD MODAL UNIDAD MODAL UNIDAD MODAL UNIDAD -->
    <div class="modal" :class="modalUnidad?'mostrar':''">
        <div class="modal-dialog modal-primary">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title"><span v-text="accion==1?'Nueva':'Modificar'"></span> Unidad</h4>
                    <button class="close" @click="modalUnidad=0">x</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            Código (nivel jerárquico): <span class="txtasterisco"></span>
                            <input type="text" class="form-control text-center" v-model="codunidad"
                                maxlength="2" placeholder="(dos dígitos)"
                                name="cod" :class="{'invalido':errors.has('cod')}" v-validate="'required|numeric'">
                            <p v-if="errors.has('cod')" class="txtvalidador">Dato requerido. Debe ser numérico.</p>
                        </div>
                        <div class="col-md-6">
                            Abreviación <span class="txtasterisco"></span>
                            <input type="text" class="form-control text-center" v-model="abrev"
                                maxlength="4" placeholder="(hasta cuatro letras)"  
                                name="abr" :class="{'invalido':errors.has('abr')}" v-validate="'required|alpha'">
                            <p v-if="errors.has('abr')" class="txtvalidador">Dato requerido. Hasta 4 letras.</p>
                        </div>
                    </div>
                    <br>Nombre de la Unidad: <span class="txtasterisco"></span>
                    <input type="text" class="form-control" v-model="nomunidad" 
                        name="nom" :class="{'invalido':errors.has('nom')}" v-validate="'required'" >
                    <p v-if="errors.has('nom')" class="txtvalidador">Dato requerido</p>                        
                    <br>Nombre del Cargo:
                    <input type="text" class="form-control" v-model="nomcargo">
                    <div class="txtobligatorio text-right"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="modalUnidad=0">Cerrar</button>
                    <button class="btn btn-primary" @click="validarUnidad()">
                        Guardar <span v-if="accion==2">Modificaciones</span></button>
                </div>
            </div>
        </div>
    </div>
</main>
</template>

<script>
export default {
    data(){ return {
        modalUnidad:0, accion:1, 
        arrayUnidades:[], idunidad:'', codunidad:'', 
        nomunidad:'', nomcargo:'', abrev:'',
    }},
    methods:{
        listaUnidades(activo){
            $('#r'+activo).prop('checked',true);
            var url='/fil_unidad/listaUnidades?activo='+activo;
            axios.get(url).then(response=>{
                this.arrayUnidades=response.data.unidades;
            });
        },

        nuevaUnidad(){
            this.modalUnidad=1;
            this.accion=1;
            this.codunidad='';
            this.nomunidad='';
            this.nomcargo='';
            this.abrev='';
            this.$validator.reset();
        },

        editarUnidad(unidad){
            window.scroll({top:0,left:0,behavior:'smooth'});
            this.modalUnidad=1;
            this.accion=2;
            this.idunidad=unidad.idunidad;
            this.codunidad=unidad.codunidad;
            this.nomunidad=unidad.nomunidad;
            this.nomcargo=unidad.nomcargo;
            this.abrev=unidad.abrev;
        },

        validarUnidad(){
            this.$validator.validateAll().then(result => {
                if (!result) { swal('Datos inválidos','Revise los errores','error'); return; }
                this.accion==1?this.storeUnidad():this.updateUnidad();
            });
        },

        storeUnidad(){
            swal({ title:'Procesando...',text:'Un momento por favor', type:'warning',
                showCancelButton:false, showConfirmButton:false, 
                allowOutsideClick: false, allowEscapeKey: false, allowEnterKey: false,
                onOpen:() => { swal.showLoading() }
            });  
            axios.post('/fil_unidad/storeUnidad',{
                'codunidad':this.codunidad,
                'abrev':this.abrev.toUpperCase(),
                'nomunidad':this.nomunidad.toUpperCase(),
                'nomcargo':this.nomcargo?this.nomcargo.toUpperCase():'',
            }).then(response=>{
                swal('Unidad creada correctamente','','success');
                this.modalUnidad=0;
                this.listaUnidades(1);
            });
        },

        updateUnidad(){
            axios.put('fil_unidad/updateUnidad',{
                'idunidad':this.idunidad,
                'abrev':this.abrev.toUpperCase(),
                'nomunidad':this.nomunidad.toUpperCase(),
                'nomcargo':this.nomcargo?this.nomcargo.toUpperCase():'',
            }).then(response=>{
                swal('Datos actualizados','','success');
                this.modalUnidad=0;
                this.listaUnidades(1);
            });
        },

        estadoUnidad(unidad){
            this.idunidad=unidad.idunidad;
            if(unidad.activo)
                swal({title:'Desactivará<br>'+unidad.nomunidad, type:'warning',
                    html: 'No podrá acceder a la información dependiente', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Unidad',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(respuesta=>{respuesta.value?this.switchUnidad(0):''});
            else this.switchUnidad(1);
        },

        switchUnidad(activo){
            var url='/fil_unidad/switchUnidad?idunidad='+this.idunidad;
            axios.put(url).then(response=>{
                swal(activo?'Activada correctamente':'Desactivada correctamente','','success');
                this.listaUnidades(activo); 
            })
        },
    },

    mounted(){
        this.listaUnidades(1);
    }
}
</script>