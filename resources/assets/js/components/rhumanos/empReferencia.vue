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
                    <button class="btn btn-primary" style="margin-top:0" @click="nuevaReferencia()">Nueva Referencia</button>
                </div>
            </div>            
        </div>
        <div class="card-body table-responsive">
            <b>REFERENCIAS PERSONALES</b> <span style="display:none">{{i=1}}</span>
            <table class="table table-striped table-sm">
                <thead class="tcabecera">
                    <tr align="center">
                        <th></th>
                        <th>Nro</th>
                        <th align="left">Nombre</th>
                        <th align="left">Relación</th>
                        <th>Celular</th>
                        <th>Teléfono</th>
                        <th align="left">Dirección</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="referencia in arrayReferencias" :key="referencia.id" :class="referencia.activo?'':'txtdesactivado'" align="center">
                        <template v-if="referencia.tiporef==1">
                        <td v-if="referencia.activo" nowrap align="center">
                            <button class="btn btn-warning icon-pencil btn-sm" title="Editar Referencia" @click="editarReferencia(referencia)"></button>
                            <button class="btn btn-danger icon-trash btn-sm" title="Desactivar Referencia" @click="estadoReferencia(referencia)"></button>
                        </td>
                        <td v-else align="center">
                            <button class="btn btn-warning icon-action-redo btn-sm" title="Restaurar Referencia" @click="estadoReferencia(referencia)"></button>
                        </td>                                        
                        <td v-text="i++"></td>
                        <td v-text="referencia.nombre" align="left"></td>
                        <td v-text="referencia.relacion" align="left"></td>
                        <td v-text="referencia.celular"></td>
                        <td v-text="referencia.telefono"></td>
                        <td v-text="referencia.direccion" align="left"></td>
                        </template>
                    </tr>
                </tbody>
            </table>
            <br><b>REFERENCIAS LABORALES</b> <span style="display:none">{{i=1}}</span>
            <table class="table table-striped table-sm">
                <thead class="tcabecera">
                    <tr align="center">
                        <th></th>    
                        <th>Nro</th>
                        <th align="left">Nombre</th>
                        <th align="left">Institución</th>
                        <th align="left">Cargo</th>
                        <th>Celular</th>
                        <th>Teléfono</th>
                        <th align="left">Dirección</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="referencia in arrayReferencias" :key="referencia.id" :class="referencia.activo?'':'txtdesactivado'" align="center">
                        <template v-if="referencia.tiporef==2">
                            <td v-if="referencia.activo" nowrap align="center">
                            <button class="btn btn-warning icon-pencil btn-sm" title="Editar Referencia" @click="editarReferencia(referencia)"></button>
                            <button class="btn btn-danger icon-trash btn-sm" title="Desactivar Referencia" @click="estadoReferencia(referencia)"></button>
                        </td>
                        <td v-else align="center">
                            <button class="btn btn-warning icon-action-redo btn-sm" title="Restaurar Referencia" @click="estadoReferencia(referencia)"></button>
                        </td>                                       
                        <td v-text="i++"></td>
                        <td v-text="referencia.nombre" align="left"></td>
                        <td v-text="referencia.relacion" align="left"></td>
                        <td v-text="referencia.cargo" align="left"></td>
                        <td v-text="referencia.celular"></td>
                        <td v-text="referencia.telefono"></td>
                        <td v-text="referencia.direccion" align="left"></td>
                        </template>
                    </tr>
                </tbody>
            </table>  
        </div>
    </div>
    <div class="modal" :class="modalReferencias?'mostrar':''">
        <div class="modal-dialog modal-primary">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title"><span v-text="accion==1?'Nueva':'Modificar'"></span> Referencia</h4>
                    <button class="close" @click="modalReferencias=0">x</button>
                </div>
                <div class="modal-body">
                    <h4 class="titsubrayado" v-text="regEmpleado.nombre+' '+regEmpleado.apaterno"></h4>
                    <div v-if="accion==1" class="text-center">TIPO:&nbsp;
                        <input type="radio" name="tiporef" value="1" v-model="tiporef">Personal&nbsp;
                        <input type="radio" name="tiporef" value="2" v-model="tiporef">Laboral
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            Nombre: <span class="txtasterisco"></span>
                            <input type="text" class="form-control" v-model="nombre" 
                                name="nom" :class="{'invalido':errors.has('nom')}" v-validate="'required|alpha_spaces'">
                            <p v-if="errors.has('nom')" class="txtvalidador">Dato requerido</p>                            
                            <span v-text="tiporef==1?'Relación':'Empresa/Institución'"></span>: <span class="txtasterisco"></span>
                            <input type="text" class="form-control" v-model="relacion"
                                name="rel" :class="{'invalido':errors.has('rel')}" v-validate="'required|alpha_spaces'">
                            <div v-if="tiporef==2">Cargo:
                                <input type="text" class="form-control" v-model="cargo">
                            </div>
                        </div>
                        <div class="col-md-6">
                            Celular: <span class="txtasterisco"></span>
                            <input type="text" class="form-control" v-model="celular" 
                                name="cel" :class="{'invalido':errors.has('cel')}" v-validate="'required|digits:8'">
                            <p v-if="errors.has('cel')" class="txtvalidador">Dato requerido, 8 dígitos.</p>
                            Teléfono:
                            <input type="text" class="form-control" v-model="telefono"
                                name="tel" :class="{'invalido':errors.has('tel')}" v-validate="'digits:7'">
                            <p v-if="errors.has('tel')" class="txtvalidador">(opcional) Sólo 7 dígitos</p>
                            <span v-text="tiporef==1?'Domicilio':'Dirección'"></span>:
                            <input type="text" class="form-control" v-model="direccion">
                        </div>
                    </div>
                    <p class="text-center txtobligatorio"></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="modalReferencias=0">Cerrar</button>
                    <button class="btn btn-primary"  @click="validarReferencia()" >
                        Guardar <span v-if="accion==2">Modificaciones</span></button>
                </div>
            </div>
        </div>
    </div>
</main>
</template>

<script>

export default {
    props:['regEmpleado'],

    data(){ return{ 
        modalReferencias:0, accion:'',
        arrayReferencias:[], 
        idreferencia:'', idempleado:'', tiporef:'', nombre:'', relacion:'', 
        celular:'', direccion:'', telefono:'', cargo:'',
    }},

    methods:{
        listaReferencias(idempleado){
            var url='/rrh_referencia/listaReferencias?idempleado='+idempleado;
            axios.get(url).then(response=>{
                this.arrayReferencias=response.data.referencias;
            });
        },

        nuevaReferencia(){
            this.modalReferencias=1;
            this.accion=1;
            this.tiporef=1;
            this.nombre='';
            this.relacion='';
            this.celular='';
            this.telefono='';
            this.direccion='';
            this.cargo='';
            this.$validator.reset();
        },

        editarReferencia(referencia){
            this.modalReferencias=1;
            this.accion=2;
            this.idreferencia=referencia.idreferencia;
            this.nombre=referencia.nombre;
            this.tiporef=referencia.tiporef;
            this.relacion=referencia.relacion;
            this.celular=referencia.celular;
            this.telefono=referencia.telefono;
            this.direccion=referencia.direccion;
            this.cargo=referencia.cargo;
        },

        validarReferencia(){
            this.$validator.validateAll().then(result=>{
                if(!result){ swal('Datos no válidos','Revise los errorers','error'); return; }
                this.accion==1?this.storeReferencia():this.updateReferencia();
            })
        },

        storeReferencia(){            
            axios.post('/rrh_referencia/storeReferencia',{
                'idempleado':this.regEmpleado.idempleado,
                'tiporef':this.tiporef,
                'nombre':this.nombre,
                'relacion':this.relacion,
                'celular':this.celular,
                'telefono':this.telefono,
                'direccion':this.direccion,
                'cargo':this.cargo,                
            }).then(response=>{
                swal('Referencia Guardada','','success');
                this.modalReferencias=0;
                this.listaReferencias(this.regEmpleado.idempleado);
            });
        },

        updateReferencia(){
            axios.put('/rrh_referencia/updateReferencia',{
                'idreferencia':this.idreferencia,
                'nombre':this.nombre,
                'relacion':this.relacion,
                'celular':this.celular,
                'telefono':this.telefono,
                'direccion':this.direccion,
                'cargo':this.cargo,
            }).then(response=>{
                swal('Datos actualizados','','success');
                this.modalReferencias=0;
                this.listaReferencias(this.regEmpleado.idempleado);
            });
        },

        estadoReferencia(referencia){
            this.idreferencia=referencia.idreferencia;
            if(referencia.activo){
                swal({  title:'Desactivará<br>'+referencia.nombre, type: 'warning', 
                    html:'', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Referencia',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(confirmar=>{ confirmar.value?this.switchReferencia(0):'' });
            }
            else this.switchReferencia(1);
        },

        switchReferencia(activo){
            var url='/rrh_referencia/switchReferencia?idreferencia='+this.idreferencia;
            axios.put(url).then(response=>{
                swal(activo?'Activada correctamente':'Desactivada correctamente','','success');
                this.listaReferencias(this.regEmpleado.idempleado);
            });
        },
    },

    mounted(){
        this.listaReferencias(this.regEmpleado.idempleado);
    }
}
</script>