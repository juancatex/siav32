<template>
<main class="main">
    <div class="breadcrumb titmodulo">RRHH > Configuración de Parámetros</div>
    <div class="container-fluid">
        <div class="row">
            <!-- niveles de formación  niveles de formación  niveles de formación  niveles de formación -->
            <!-- niveles de formación  niveles de formación  niveles de formación  niveles de formación -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header titcard">
                        <div class="row">
                            <div class="col-md-8 col-sm-8">
                                <div class="tablatit"><div class="tcelda">Niveles de Formación</div></div>
                            </div>
                            <div class="col-md-4 col-sm-4 text-right">
                                <button v-if="!formFormacion" class="btn btn-primary" style="margin-top:0" 
                                    @click="nuevaFormacion()">Nueva</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="height:280px; overflow-y:scroll">
                        <table v-if="!formFormacion" class="table table-sm table-striped">
                            <thead class="tcabecera">
                                <tr>
                                    <th><span class="badge badge-success" v-text="arrayFormaciones.length+' items'"></span></th>
                                    <th>Descripción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="formacion in arrayFormaciones" :key="formacion.id" 
                                    :class="formacion.activo?'':'txtdesactivado'" align="center">
                                    <td v-if="formacion.activo" nowrap>
                                        <button class="btn btn-warning btn-sm icon-pencil" title="Editar Formación" 
                                            @click="editarFormacion(formacion)"></button>
                                        <button class="btn btn-danger btn-sm icon-trash" title="Desactivar Formación"
                                            @click="estadoFormacion(formacion)"></button>
                                    </td>
                                    <td v-else>
                                        <button class="btn btn-warning btn-sm icon-action-redo" title="Restaurar Formación"
                                            @click="estadoFormacion(formacion)"></button>
                                    </td>
                                    <td v-text="formacion.nomformacion" align="left"></td>
                                </tr>
                            </tbody>
                        </table>
                        <div v-if="formFormacion"><br>
                            <h4 class="titazul"><span v-text="accion==1?'Nueva':'Modificar'"></span> Formación</h4>
                            <br>Nivel de Formación: <input type="text" class="form-control" autofocus v-model="nomformacion">
                            <p align="right"><br>
                            <button class="btn btn-secondary" @click="formFormacion=0">Cancelar</button>
                            <button class="btn btn-primary" @click="accion==1?storeFormacion():updateFormacion()">
                                Guardar <span v-if="accion==2">Modificaciones</span></button>                            
                            </p>
                        </div>                        
                    </div>

                </div>
            </div>

            <!-- profesiones profesiones profesiones profesiones profesiones profesiones profesiones-->
            <!-- profesiones profesiones profesiones profesiones profesiones profesiones profesiones-->
            <div class="col-md-6"> 
                <div class="card">
                    <div class="card-header titcard">
                        <div class="row">
                            <div class="col-md-8 col-sm-8">
                                <div class="tablatit"><div class="tcelda">Profesiones</div></div>
                            </div>
                            <div class="col-md-4 col-sm-4 text-right">
                                <button v-if="!formProfesion" class="btn btn-primary" style="margin-top:0" 
                                    @click="nuevaProfesion()">Nueva</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="height:280px; overflow-y:scroll">
                        <table v-if="!formProfesion" class="table table-sm table-striped">
                            <thead class="tcabecera">
                                <tr>
                                    <th><span class="badge badge-success" v-text="arrayProfesiones.length+' items'"></span></th>
                                    <th>Descripción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="profesion in arrayProfesiones" :key="profesion.id"
                                    :class="profesion.activo?'':'txtdesactivado'" align="center">
                                    <td v-if="profesion.activo" align="center" nowrap>
                                        <button class="btn btn-warning btn-sm icon-pencil" title="Editar Profesión"
                                            @click="editarProfesion(profesion)"></button>
                                        <button class="btn btn-danger btn-sm icon-trash" title="Desactivar Profesión"
                                            @click="estadoProfesion(profesion)"></button>
                                    </td>
                                    <td v-else>
                                        <button class="btn btn-warning btn-sm icon-action-redo" title="Restaurar Profesión"
                                            @click="estadoProfesion(profesion)"></button>
                                    </td>
                                    <td v-text="profesion.nomprofesion" align="left"></td>
                                </tr>
                            </tbody>
                        </table>
                        <div v-if="formProfesion"><br>
                            <h4 class="titazul"><span v-text="accion==1?'Nueva':'Modificar'"></span> Profesión</h4>
                            <br>Profesión: <input type="text" class="form-control" autofocus v-model="nomprofesion">
                            <p align="right"><br>
                            <button class="btn btn-secondary" @click="formProfesion=0">Cancelar</button>
                            <button class="btn btn-primary" @click="accion==1?storeProfesion():updateProfesion()">
                                Guardar <span v-if="accion==2">Modificaciones</span></button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>           
            
            <!-- motivos de permiso  motivos de permiso  motivos de permiso  motivos de permiso -->
            <!-- motivos de permiso  motivos de permiso  motivos de permiso  motivos de permiso -->
            <div class="col-md-6"> 
                <div class="card">
                    <div class="card-header titcard">
                        <div class="row">
                            <div class="col-md-8 col-sm-8">
                                <div class="tablatit"><div class="tcelda">Motivos de Permiso</div></div>
                            </div>
                            <div class="col-md-4 col-sm-4 text-right">
                                <button v-if="!formMotivo" class="btn btn-primary" style="margin-top:0" 
                                    @click="nuevoMotivo()">Nueva</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="height:280px; overflow-y:scroll">
                        <table v-if="!formMotivo" class="table table-sm table-striped">
                            <thead class="tcabecera">
                                <tr>
                                    <th><span class="badge badge-success" v-text="arrayMotivos.length+' items'"></span></th>
                                    <th>Descripción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="motivo in arrayMotivos" :key="motivo.id" 
                                    :class="motivo.activo?'':'txtdesactivado'" align="center">
                                    <td v-if="motivo.activo" nowrap>
                                        <button class="btn btn-warning btn-sm icon-pencil" title="Editar motivo"  
                                            @click="editarMotivo(motivo)"></button>
                                        <button class="btn btn-danger btn-sm icon-trash" title="Desactivar motivo" 
                                            @click="estadoMotivo(motivo)"></button>
                                    </td>
                                    <td v-else>
                                        <button class="btn btn-warning btn-sm icon-action-redo" title="Restaurar motivo"  
                                            @click="estadoMotivo(motivo)"></button>
                                    </td>
                                    <td v-text="motivo.nommotivo" align="left"></td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <div v-if="formMotivo">
                            <h4 class="titazul"><span v-text="accion==1?'Nuevo':'Modifcar'"></span> Motivo</h4>
                            <br>Motivo: <input type="text" class="form-control" v-model="nommotivo">
                            <p align="right"><br>
                                <button class="btn btn-secondary" @click="formMotivo=0">Cancelar</button>
                                <button class="btn btn-primary" @click="accion==1?storeMotivo():updateMotivo()">
                                    Guardar <span v-if="accion==2">Modificaciones</span></button>
                            </p>
                        </div>                        
                    </div>
                </div>
            </div>

            <!-- seguro social  seguro social  seguro social  seguro social   seguro social -->
            <!-- seguro social  seguro social  seguro social  seguro social   seguro social -->
            <div class="col-md-6"> 
                <div class="card">
                    <div class="card-header titcard">
                        <div class="row">
                            <div class="col-md-8 col-sm-8">
                                <div class="tablatit"><div class="tcelda">Seguro Social</div></div>
                            </div>
                            <div class="col-md-4 col-sm-4 text-right">
                                <button v-if="!formSeguro" class="btn btn-primary" style="margin-top:0" 
                                    @click="nuevoSeguro()">Nuevo</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="height:280px; overflow-y:scroll">
                        <table v-if="!formSeguro" class="table table-sm table-striped">
                            <thead class="tcabecera">
                                <tr>
                                    <th><span class="badge badge-success" v-text="arraySeguros.length+' items'"></span></th>
                                    <th>Tipo</th>
                                    <th>Sigla</th>
                                    <th>Descripción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="seguro in arraySeguros" :key="seguro.id"
                                    :class="seguro.activo?'':'txtdesactivado'" align="center">
                                    <td v-if="seguro.activo" nowrap>
                                        <button class="btn btn-warning btn-sm icon-pencil" @click="editarSeguro(seguro)"></button>
                                        <button class="btn btn-danger btn-sm icon-trash" @click="estadoSeguro(seguro)"></button>
                                    </td>
                                    <td v-else>
                                        <button class="btn btn-warning btn-sm icon-action-redo" @click="estadoSeguro(seguro)"></button>
                                    </td>
                                    <td v-text="seguro.tipo==1?'Pensiones':'Salud'"></td>
                                    <td v-text="seguro.sigla"></td>
                                    <td v-text="seguro.nomseguro" align="left"></td>
                                </tr>
                            </tbody>
                        </table>
                        <br v-if="formSeguro">
                        <h4 v-if="formSeguro" class="titazul"><span v-text="accion==1?'Nuevo':'Modificar'"></span> Seguro</h4><br>
                        <div v-if="formSeguro" class="row">
                            <div class="col-md-3">Tipo:
                                <select class="form-control" v-model="tipo">
                                    <option value="1">Pensiones</option>
                                    <option value="2">Salud</option>
                                </select>
                            </div>
                            <div class="col-md-3">Sigla:
                                <input type="text" class="form-control" v-model="sigla">
                            </div>
                            <div class="col-md-6">Nombre:
                                <input type="text" class="form-control" v-model="nomseguro">
                            </div>
                        </div>
                        <div v-if="formSeguro" class="text-right"><br>
                            <button class="btn btn-secondary" @click="formSeguro=0">Cancelar</button>
                            <button class="btn btn-primary" @click="accion==1?storeSeguro():updateSeguro()">
                                Guardar <span v-if="accion==2">Modificaciones</span></button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- tipo contratos  tipo contratos tipo contratos tipo contratos tipo contratos tipo contratos-->
            <!-- tipo contratos  tipo contratos tipo contratos tipo contratos tipo contratos tipo contratos-->
            <div class="col-md-6"> 
                <div class="card">
                    <div class="card-header titcard">
                        <div class="row">
                            <div class="col-md-8 col-sm-8">
                                <div class="tablatit"><div class="tcelda">Tipos de Contrato</div></div>
                            </div>
                            <div class="col-md-4 col-sm-4 text-right">
                                <button v-if="!formProfesion" class="btn btn-primary" style="margin-top:0" 
                                    @click="nuevoTipocontrato()">Nuevo</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="height:280px; overflow-y:scroll">
                        <table v-if="!formTipocontrato" class="table table-sm table-striped">
                            <thead class="tcabecera">
                                <tr>
                                    <th><span class="badge badge-success" v-text="arrayTipocontratos.length+' items'"></span></th>
                                    <th>Descripción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="tipocontrato in arrayTipocontratos" :key="tipocontrato.id"
                                    :class="tipocontrato.activo?'':'txtdesactivado'" align="center">
                                    <td v-if="tipocontrato.activo" align="center" nowrap>
                                        <button class="btn btn-warning btn-sm icon-pencil" title="Editar Profesión"
                                            @click="editarTipocontrato(tipocontrato)"></button>
                                        <button class="btn btn-danger btn-sm icon-trash" title="Desactivar Profesión"
                                            @click="estadoTipocontrato(tipocontrato)"></button>
                                    </td>
                                    <td v-else>
                                        <button class="btn btn-warning btn-sm icon-action-redo" title="Restaurar Profesión"
                                            @click="estadoTipocontrato(tipocontrato)"></button>
                                    </td>
                                    <td v-text="tipocontrato.nomtipocontrato" align="left"></td>
                                </tr>
                            </tbody>
                        </table>
                        <div v-if="formTipocontrato"><br>
                            <h4 class="titazul"><span v-text="accion==1?'Nueva':'Modificar'"></span> Tipo de contrato</h4>
                            <br>Tipo de Contrato: <input type="text" class="form-control" autofocus v-model="nomtipocontrato">
                            <p align="right"><br>
                            <button class="btn btn-secondary" @click="formTipocontrato=0">Cancelar</button>
                            <button class="btn btn-primary" @click="accion==1?storeTipocontrato():updateTipocontrato()">
                                Guardar <span v-if="accion==2">Modificaciones</span></button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- cargos  cargos cargos cargos cargos cargos cargos -->
            <!-- cargos  cargos cargos cargos cargos cargos cargos -->
            <div class="col-md-6"> 
                <div class="card">
                    <div class="card-header titcard">
                        <div class="row">
                            <div class="col-md-8 col-sm-8">
                                <div class="tablatit"><div class="tcelda">Cargos</div></div>
                            </div>
                            <div class="col-md-4 col-sm-4 text-right">
                                <button v-if="!formProfesion" class="btn btn-primary" style="margin-top:0" 
                                    @click="nuevoCargo()">Nuevo</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="height:280px; overflow-y:scroll">
                        <table v-if="!formCargo" class="table table-sm table-striped">
                            <thead class="tcabecera">
                                <tr>
                                    <th><span class="badge badge-success" v-text="arrayCargos.length+' items'"></span></th>
                                    <th>Descripción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="cargo in arrayCargos" :key="cargo.id"
                                    :class="cargo.activo?'':'txtdesactivado'" align="center">
                                    <td v-if="cargo.activo" align="center" nowrap>
                                        <button class="btn btn-warning btn-sm icon-pencil" title="Editar Cargo"
                                            @click="editarCargo(cargo)"></button>
                                        <button class="btn btn-danger btn-sm icon-trash" title="Desactivar Cargo"
                                            @click="estadoCargo(cargo)"></button>
                                    </td>
                                    <td v-else>
                                        <button class="btn btn-warning btn-sm icon-action-redo" title="Restaurar Cargo"
                                            @click="estadoCargo(cargo)"></button>
                                    </td>
                                    <td v-text="cargo.nomcargo" align="left"></td>
                                </tr>
                            </tbody>
                        </table>
                        <div v-if="formCargo"><br>
                            <h4 class="titazul"><span v-text="accion==1?'Nuevo':'Modificar'"></span> cargo</h4>
                            <br>Cargo: <input type="text" class="form-control" autofocus v-model="nomcargo">
                            <p align="right"><br>
                            <button class="btn btn-secondary" @click="formCargo=0">Cancelar</button>
                            <button class="btn btn-primary" @click="accion==1?storeCargo():updateCargo()">
                                Guardar <span v-if="accion==2">Modificaciones</span></button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
</template>

<script>
export default {
    data(){ return { accion:'', 
        formFormacion:0, arrayFormaciones:[], idformacion:'', nomformacion:'',
        formProfesion:0, arrayProfesiones:[], idprofesion:'', nomprofesion:'',
        formTipocontrato:0,  arrayTipocontratos:[], idtipocontrato:'', nomtipocontrato:'',
        formMotivo:0, arrayMotivos:[], idmotivo:'', nommotivo:'',
        formSeguro:0, arraySeguros:[], idseguro:'', nomseguro:'', tipo:'', sigla:'',
        formCargo:0, arrayCargos:[], idcargo:'', nomcargo:'',
    }},

    methods:{
        //formaciones formaciones formaciones  formaciones formaciones formaciones formaciones
        //formaciones formaciones formaciones  formaciones formaciones formaciones formaciones
        listaFormaciones(){
            let me=this;
            axios.get('rrh_formacion/listaFormaciones').then(function(response){
                me.arrayFormaciones=response.data.formaciones;
            });
        },

        nuevaFormacion(){
            this.formFormacion=1;
            this.accion=1;
            this.nomformacion='';
        },

        editarFormacion(formacion){
            this.formFormacion=1;
            this.accion=2;
            this.idformacion=formacion.idformacion;
            this.nomformacion=formacion.nomformacion;
        },

        storeFormacion(){
            let me=this;
            var url='/rrh_formacion/storeFormacion';
            axios.post(url,{
                'nomformacion':this.nomformacion
            }).then(function(){
                swal('Nivel de formación creada correctamente','','success');
                me.formFormacion=0;
                me.listaFormaciones();
            });
        },

        updateFormacion(){
            let me=this;
            var url='/rrh_formacion/updateFormacion';
            axios.put(url,{
                'idformacion':this.idformacion,
                'nomformacion':this.nomformacion
            }).then(function(){
                swal('Datos actualizados','','success');
                me.formFormacion=0;
                me.listaFormaciones();
            });
        },

        estadoFormacion(formacion){
            this.idformacion=formacion.idformacion;
            if(formacion.activo){
                swal({  title:'Desactivará<br>'+formacion.nomformacion, type: 'warning', 
                    html:'No podrá acceder a la información dependiente', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Formacion',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(confirmar=>{
                    if(confirmar.value) this.switchFormacion(1);
                });
            }
            else this.switchFormacion(0);
        },

        switchFormacion(activo){
            if(activo) var titswal='Desactivado'; else var titswal='Activado';
            var url='/rrh_formacion/switchFormacion?idformacion='+this.idformacion;
            let me=this;
            axios.put(url).then(function(){
                swal(titswal+' correctamente','','success');
                me.listaFormaciones();
            });
        },        

        //profesiones profesiones profesiones profesiones profesiones profesiones profesiones
        //profesiones profesiones profesiones profesiones profesiones profesiones profesiones
        listaProfesiones(){
            let me=this;
            axios.get('rrh_profesion/listaProfesiones').then(function(response){
                me.arrayProfesiones=response.data.profesiones;
            });
        },

        nuevaProfesion(){
            this.formProfesion=1;
            this.accion=1;
            this.nomprofesion='';
        },

        editarProfesion(profesion){
            this.formProfesion=1;
            this.accion=2;
            this.idprofesion=profesion.idprofesion;
            this.nomprofesion=profesion.nomprofesion;
        },

        storeProfesion(){
            let me=this;
            var url='/rrh_profesion/storeProfesion';
            axios.post(url,{
                'nomprofesion':this.nomprofesion
            }).then(function(){
                swal('Profesion creado correctamente','','success');
                me.formProfesion=0;
                me.listaProfesiones();
            });
        },

        updateProfesion(){
            let me=this;
            var url='/rrh_profesion/updateProfesion';
            axios.put(url,{
                'idprofesion':this.idprofesion,
                'nomprofesion':this.nomprofesion
            }).then(function(){
                swal('Datos actualizados','','success');
                me.formProfesion=0;
                me.listaProfesiones();
            });
        },

        estadoProfesion(profesion){
            this.idprofesion=profesion.idprofesion;
            if(profesion.activo){
                swal({  title:'Desactivará este item', type: 'warning', 
                    html:'No podrá acceder a la información dependiente', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Profesion',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(confirmar=>{
                    if(confirmar.value) this.switchProfesion(1);
                });
            }
            else this.switchProfesion(0);
        },

        switchProfesion(activo){
            if(activo) var titswal='Desactivado'; else var titswal='Activado';
            var url='/rrh_profesion/switchProfesion?idprofesion='+this.idprofesion;
            let me=this;
            axios.put(url).then(function(){
                swal(titswal+' correctamente','','success');
                me.listaProfesiones();
            });
        },        

        // tipo contratos tipo contratos tipo contratos tipo contratos tipo contratos tipo contratos
        // tipo contratos tipo contratos tipo contratos tipo contratos tipo contratos tipo contratos
        listaTipocontratos(){
            let me=this;
            var url='/rrh_tipocontrato/listaTipocontratos';
            axios.get(url).then(function(response){
                me.arrayTipocontratos=response.data.tipocontratos;
            });
        },

        nuevoTipocontrato(){
            this.formTipocontrato=1;
            this.accion=1;
            this.nomtipocontrato='';
        },

        editarTipocontrato(tipocontrato){
            this.formTipocontrato=1;
            this.accion=2;
            this.idtipocontrato=tipocontrato.idtipocontrato;
            this.nomtipocontrato=tipocontrato.nomtipocontrato;
        },

        storeTipocontrato(){
            let me=this;
            var url='/rrh_tipocontrato/storeTipocontrato';
            axios.post(url,{
                'nomtipocontrato':this.nomtipocontrato
            }).then(function(){
                swal('Tipocontrato creado correctamente','','success');
                me.formTipocontrato=0;
                me.listaTipocontratos();
            });
        },

        updateTipocontrato(){
            let me=this;
            var url='/rrh_tipocontrato/updateTipocontrato';
            axios.put(url,{
                'idtipocontrato':this.idtipocontrato,
                'nomtipocontrato':this.nomtipocontrato
            }).then(function(){
                swal('Datos actualizados','','success');
                me.formTipocontrato=0;
                me.listaTipocontratos();
            });
        },

        estadoTipocontrato(tipocontrato){
            this.idtipocontrato=tipocontrato.idtipocontrato;
            if(tipocontrato.activo){
                swal({  title:'Desactivará este item', type: 'warning', 
                    html:'No podrá acceder a la información dependiente', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Tipocontrato',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(confirmar=>{
                    if(confirmar.value) this.switchTipocontrato(1);
                });
            }
            else this.switchTipocontrato(0);
        },

        switchTipocontrato(activo){
            if(activo) var titswal='Desactivado'; else var titswal='Activado';
            var url='/rrh_tipocontrato/switchTipocontrato?idtipocontrato='+this.idtipocontrato;
            let me=this;
            axios.put(url).then(function(){
                swal(titswal+' correctamente','','success');
                me.listaTipocontratos();
            });
        },        


        //motivos motivos motivos motivos motivos motivos motivos motivos
        //motivos motivos motivos motivos motivos motivos motivos motivos
        listaMotivos(){
            let me=this;
            var url='/rrh_motivo/listaMotivos';
            axios.get(url).then(function(response){
                me.arrayMotivos=response.data.motivos;
            });
        },

        nuevoMotivo(){
            this.formMotivo=1;
            this.accion=1;
            this.nommotivo='';
        },

        editarMotivo(motivo){
            this.formMotivo=1;
            this.accion=2;
            this.idmotivo=motivo.idmotivo;
            this.nommotivo=motivo.nommotivo;
        },

        storeMotivo(){
            let me=this;
            var url='/rrh_motivo/storeMotivo';
            axios.post(url,{
                'nommotivo':this.nommotivo
            }).then(function(){
                swal('Motivo creado correctamente','','success');
                me.formMotivo=0;
                me.listaMotivos();
            });
        },

        updateMotivo(){
            let me=this;
            var url='/rrh_motivo/updateMotivo';
            axios.put(url,{
                'idmotivo':this.idmotivo,
                'nommotivo':this.nommotivo
            }).then(function(){
                swal('Datos actualizados','','success');
                me.formMotivo=0;
                me.listaMotivos();
            });
        },

        estadoMotivo(motivo){
            this.idmotivo=motivo.idmotivo;
            if(motivo.activo){
                swal({  title:'Desactivará este item', type: 'warning', 
                    html:'No podrá acceder a la información dependiente', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Motivo',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(confirmar=>{
                    if(confirmar.value) this.switchMotivo(1);
                });
            }
            else this.switchMotivo(0);
        },

        switchMotivo(activo){
            if(activo) var titswal='Desactivado'; else var titswal='Activado';
            var url='/rrh_motivo/switchMotivo?idmotivo='+this.idmotivo;
            let me=this;
            axios.put(url).then(function(){
                swal(titswal+' correctamente','','success');
                me.listaMotivos();
            });
        },        


        // seguros  seguros  seguros  seguros  seguros  seguros  seguros  seguros 
        // seguros  seguros  seguros  seguros  seguros  seguros  seguros  seguros 
        listaSeguros(){
            let me=this;
            var url='/rrh_seguro/listaSeguros';
            axios.get(url).then(function(response){
                me.arraySeguros=response.data.seguros;
            });
        },

        nuevoSeguro(){
            this.formSeguro=1;
            this.accion=1;
            this.tipo='';
            this.sigla='';
            this.nomseguro='';
        },

        editarSeguro(seguro){
            this.formSeguro=1;
            this.accion=2;
            this.idseguro=seguro.idseguro;
            this.tipo=seguro.tipo;
            this.sigla=seguro.sigla;
            this.nomseguro=seguro.nomseguro;
        },

        storeSeguro(){
            let me=this;
            var url='/rrh_seguro/storeSeguro';
            axios.post(url,{
                'tipo':this.tipo,
                'sigla':this.sigla,
                'nomseguro':this.nomseguro
            }).then(function(){
                swal('Seguro creado correctamente','','success');
                me.formSeguro=0;
                me.listaSeguros();
            });
        },

        updateSeguro(){
            let me=this;
            var url='/rrh_seguro/updateSeguro';
            axios.put(url,{
                'idseguro':this.idseguro,
                'tipo':this.tipo,
                'sigla':this.sigla,
                'nomseguro':this.nomseguro
            }).then(function(){
                swal('Datos actualizados','','success');
                me.formSeguro=0;
                me.listaSeguros();
            });
        },

        estadoSeguro(seguro){
            this.idseguro=seguro.idseguro;
            if(seguro.activo){
                swal({  title:'Desactivará '+seguro.sigla, type: 'warning', 
                    html:'No podrá acceder a la información dependiente', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Seguro',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(confirmar=>{
                    if(confirmar.value) this.switchSeguro(1);
                });
            }
            else this.switchSeguro(0);
        },

        switchSeguro(activo){
            if(activo) var titswal='Desactivado'; else var titswal='Activado';
            var url='/rrh_seguro/switchSeguro?idseguro='+this.idseguro;
            let me=this;
            axios.put(url).then(function(){
                swal(titswal+' correctamente','','success');
                me.listaSeguros();
            });
        },        

        //cargos cargos cargos cargos cargos cargos cargos cargos
        //cargos cargos cargos cargos cargos cargos cargos cargos
        listaCargos(){
            let me=this;
            var url='/rrh_cargo/listaCargos';
            axios.get(url).then(function(response){
                me.arrayCargos=response.data.cargos;
            });
        },

        nuevoCargo(){
            this.formCargo=1;
            this.accion=1;
            this.nomcargo='';
        },

        editarCargo(cargo){
            this.formCargo=1;
            this.accion=2;
            this.idcargo=cargo.idcargo;
            this.nomcargo=cargo.nomcargo;
        },

        storeCargo(){
            let me=this;
            var url='/rrh_cargo/storeCargo';
            axios.post(url,{
                'nomcargo':this.nomcargo
            }).then(function(){
                swal('Cargo creado correctamente','','success');
                me.formCargo=0;
                me.listaCargos();
            });
        },

        updateCargo(){
            let me=this;
            var url='/rrh_cargo/updateCargo';
            axios.put(url,{
                'idcargo':this.idcargo,
                'nomcargo':this.nomcargo
            }).then(function(){
                swal('Datos actualizados','','success');
                me.formCargo=0;
                me.listaCargos();
            });
        },

        estadoCargo(cargo){
            this.idcargo=cargo.idcargo;
            if(cargo.activo){
                swal({  title:'Desactivará:<br>'+cargo.nomcargo, type: 'warning', 
                    html:'No podrá acceder a la información dependiente', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Cargo',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(confirmar=>{
                    if(confirmar.value) this.switchCargo(1);
                });
            }
            else this.switchCargo(0);
        },

        switchCargo(activo){
            if(activo) var titswal='Desactivado'; else var titswal='Activado';
            var url='/rrh_cargo/switchCargo?idcargo='+this.idcargo;
            let me=this;
            axios.put(url).then(function(){
                swal(titswal+' correctamente','','success');
                me.listaCargos();
            });
        },        


    },

    mounted(){
        this.listaFormaciones();
        this.listaProfesiones();
        this.listaTipocontratos();
        this.listaMotivos();
        this.listaSeguros();
        this.listaCargos();
    }
}
</script>