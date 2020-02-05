<template>
<main class="main">
    <div class="modal" :class="modalReferencias?'mostrar':''">
        <div class="modal-dialog modal-primary modal-lg">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title">Referencias</h4>
                    <button class="close" @click="modalReferencias=0">x</button>
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
                            <button v-if="!divFormulario" class="btn btn-primary" @click="nuevaReferencia()">Registrar Referencia</button>
                        </div>
                    </div>
                    <br>
                    <div v-if="!divFormulario" class="table-responsive">
                        <b>REFERENCIAS PERSONALES</b> <span style="display:none">{{i=1}}</span>
                        <table class="table table-striped table-sm">
                            <thead class="tcabecera">
                                <tr>
                                    <th>Nro</th>
                                    <th>Nombre</th>
                                    <th>Relación</th>
                                    <th>Celular</th>
                                    <th>Teléfono</th>
                                    <th>Dirección</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="referencia in arrayReferencias" :key="referencia.id" :class="referencia.activo?'':'txtdesactivado'" align="center">
                                    <template v-if="referencia.tiporef==1">
                                    <td v-text="i++"></td>
                                    <td v-text="referencia.nombre"></td>
                                    <td v-text="referencia.relacion"></td>
                                    <td v-text="referencia.celular"></td>
                                    <td v-text="referencia.telefono"></td>
                                    <td v-text="referencia.direccion"></td>
                                    <td v-if="referencia.activo" nowrap align="center">
                                        <button class="btn btn-warning icon-pencil btn-sm" title="Editar Referencia" @click="editarReferencia(referencia)"></button>
                                        <button class="btn btn-danger icon-trash btn-sm" title="Desactivar Referencia" @click="estadoReferencia(referencia)"></button>
                                    </td>
                                    <td v-else align="center">
                                        <button class="btn btn-warning icon-action-undo btn-sm" title="Restaurar Referencia" @click="estadoReferencia(referencia)"></button>
                                    </td>
                                    </template>
                                </tr>
                            </tbody>
                        </table>
                        <b>REFERENCIAS LABORALES</b> <span style="display:none">{{i=1}}</span>
                        <table class="table table-striped table-sm">
                            <thead class="tcabecera">
                                <tr>
                                    <th>Nro</th>
                                    <th>Nombre</th>
                                    <th>Institución</th>
                                    <th>Cargo</th>
                                    <th>Celular</th>
                                    <th>Teléfono</th>
                                    <th>Dirección</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="referencia in arrayReferencias" :key="referencia.id" :class="referencia.activo?'':'txtdesactivado'" align="center">
                                    <template v-if="referencia.tiporef==2">
                                    <td v-text="i++"></td>
                                    <td v-text="referencia.nombre"></td>
                                    <td v-text="referencia.relacion"></td>
                                    <td v-text="referencia.cargo"></td>
                                    <td v-text="referencia.celular"></td>
                                    <td v-text="referencia.telefono"></td>
                                    <td v-text="referencia.direccion"></td>
                                    <td v-if="referencia.activo" nowrap align="center">
                                        <button class="btn btn-warning icon-pencil btn-sm" title="Editar Referencia" @click="editarReferencia(referencia)"></button>
                                        <button class="btn btn-danger icon-trash btn-sm" title="Desactivar Referencia" @click="estadoReferencia(referencia)"></button>
                                    </td>
                                    <td v-else align="center">
                                        <button class="btn btn-warning icon-action-undo btn-sm" title="Restaurar Referencia" @click="estadoReferencia(referencia)"></button>
                                    </td>
                                    </template>
                                </tr>
                            </tbody>
                        </table>     
                    </div>
                    <div v-if="divFormulario" class="row">
                        <div class="col-md-8">
                            <h4 class="titazul">
                                <span v-text="accion==1?'Nueva':'Modificar'"></span> Referencia
                                <span v-text="tiporef==1?'Personal':'Laboral'"></span>
                            </h4>
                        </div>
                        <div v-if="accion==1" class="col-md-4 text-center">TIPO:&nbsp;&nbsp;
                            <input type="radio" name="tiporef" value="1" v-model="tiporef">Personal
                            <input type="radio" name="tiporef" value="2" v-model="tiporef">Laboral
                        </div>
                    </div>
                    <div v-if="divFormulario&&tiporef==1" class="row">
                        <div class="col-md-4">
                            Nombre:
                            <input type="text" class="form-control" v-model="nombre" name="Nombre" v-validate.initial="'required|alpha_spaces'">
                            <p class="text-right txtvalerror" v-text="errors.first('Nombre')"></p>
                            Relación:
                            <input type="text" class="form-control" v-model="relacion">
                        </div>
                        <div class="col-md-4">
                            Celular:
                            <input type="text" class="form-control" v-model="celular" name="Celular" v-validate.initial="'required|numeric'">
                            <p class="text-right txtvalerror" v-text="errors.first('Celular')"></p>
                            Teléfono:
                            <input type="text" class="form-control" v-model="telefono">
                        </div>
                        <div class="col-md-4">
                            Domicilio:
                            <input type="text" class="form-control" v-model="direccion">
                        </div>
                    </div>
                    <div v-if="divFormulario&&tiporef==2" class="row">
                        <div class="col-md-4">
                            Nombre:
                            <input type="text" class="form-control" v-model="nombre" name="Nombre" v-validate.initial="'required|alpha_spaces'">
                            <p class="text-right txtvalerror" v-text="errors.first('Nombre')"></p>
                            Celular:
                            <input type="text" class="form-control" v-model="celular" name="Celular" v-validate.initial="'required|numeric'">
                            <p class="text-right txtvalerror" v-text="errors.first('Celular')"></p>
                        </div>
                        <div class="col-md-4">
                            Empresa/Institución:
                            <input type="text" class="form-control" v-model="relacion">
                            Cargo:
                            <input type="text" class="form-control" v-model="cargo">
                        </div>
                        <div class="col-md-4">
                            Dirección:
                            <input type="text" class="form-control" v-model="direccion">
                            Teléfono:
                            <input type="text" class="form-control" v-model="telefono">
                        </div>
                    </div>

                </div>
                <div v-if="divFormulario" class="modal-footer">
                    <button class="btn btn-secondary" @click="divFormulario=0">Cancelar</button>
                    <button class="btn btn-primary"  @click="accion==1?storeReferencia():updateReferencia()" :disabled="errors.any()">
                        Guardar <span v-if="accion==2">Modificaciones</span></button>
                </div>
            </div>
        </div>
    </div>
</main>
</template>

<script>

export default {
    data(){ return{ 
        modalReferencias:0, divFormulario:0, completo:0,
        arrayReferencias:[], regEmpleado:[],
        idreferencia:'', idempleado:'', tiporef:'', nombre:'', relacion:'', 
        celular:'', direccion:'', telefono:'', cargo:'',
    }},

    methods:{
        abrirModal(empleado){
            window.scroll({top:0,left:0,behavior:'smooth'});
            this.modalReferencias=1;
            this.listaReferencias(empleado.idempleado);
            this.accion=1;
            this.regEmpleado=empleado;
        },

        listaReferencias(idempleado){
            var url='/rrh_referencia/listaReferencias?idempleado='+idempleado;
            axios.get(url).then(response=>{
                this.arrayReferencias=response.data.referencias;
            });
        },

        nuevaReferencia(){
            this.divFormulario=1;
            this.accion=1;
            this.tiporef=1;
            this.nombre='';
            this.relacion='';
            this.celular='';
            this.telefono='';
            this.direccion='';
            this.cargo='';
        },

        editarReferencia(referencia){
            this.divFormulario=1;
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
                this.divFormulario=0;
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
                this.divFormulario=0;
                this.listaReferencias(this.regEmpleado.idempleado);
            });
        },

        estadoReferencia(referencia){
            this.idreferencia=referencia.idreferencia;
            if(referencia.activo){
                swal({  title:'Desactivará '+referencia.nombre, type: 'warning', 
                    html:'', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Referencia',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(confirmar=>{
                    if(confirmar.value) this.switchReferencia(1);
                });
            }
            else this.switchReferencia(0);
        },

        switchReferencia(activo){
            if(activo) var titswal='Desactivada'; else var titswal='Activada';
            var url='/rrh_referencia/switchReferencia?idreferencia='+this.idreferencia;
            axios.put(url).then(response=>{
                swal(titswal+' correctamente','','success');
                this.listaReferencias(this.regEmpleado.idempleado);
            });
        },
    },
}
</script>