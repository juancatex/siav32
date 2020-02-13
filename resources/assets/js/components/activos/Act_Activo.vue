<template>
<main class="main">
    <div class="breadcrumb titmodulo">
        <div class="col-md-8 col-sm-11 titmodulo" style="padding:0px">
            <div class="tablatit">
                <div v-if="!divActivos" class="tcelda" style="padding-right:8px">
                    <button class="btn btn-success cui-arrow-left botonnav" @click="nivelPrevio()"></button>
                </div>
                <div class="tcelda">Activos Fijos
                    <span v-if="divActivos"> > Catálogo</span>
                    <span v-if="divKardex"> > Kardex</span>
                    <span v-if="divAsignacion"> > Asignacion</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-1 text-right" style="padding:0px">
            <button class="btn btn-success cui-options botonnav"></button>
        </div>
                    <!--
                    <a href="#" class="dropdown-item" > Detalle de Depreciaciones</a>
                    <a href="#" class="dropdown-item" > Resumen por cuenta</a>
                    <a href="#" class="dropdown-item" > Resumen Gerencial</a>
                    <a href="#" class="dropdown-item" > Asientos Contables</a>
                    -->
    </div>
    <div class="container-fluid">
        <div v-if="divActivos" class="card noprint"><!-- los filtros -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="tabla100">
                            <div class="tfila">
                                <div class="tcelda">Filial:</div>
                                <div class="tcelda">
                                    <select class="form-control" v-model="idfilial" 
                                        @change="listaAmbientes(idfilial),listaActivos(idfilial,idambiente,idgrupo,idauxiliar)">
                                        <option v-for="filial in arrayFiliales" :key="filial.idfilial"
                                            v-text="filial.nommunicipio" :value="filial.idfilial"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="tabla100">                        
                            <div class="tfila">
                                <div class="tcelda">Ambiente:</div>
                                <div class="tcelda">
                                    <select class="form-control" v-model="idambiente" 
                                        @change="listaActivos(idfilial,idambiente,idgrupo,idauxiliar)">
                                        <option v-for="ambiente in arrayAmbientes" :key="ambiente.id"
                                            v-text="ambiente.nomambiente" :value="ambiente.idambiente"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="tabla100">                        
                            <div class="tfila">
                                <div class="tcelda">Cuenta:</div>
                                <div class="tcelda">
                                    <select class="form-control" v-model="idgrupo" 
                                        @change="listaAuxiliares(idgrupo),listaActivos(idfilial,idambiente,idgrupo,idauxiliar)">
                                        <option v-for="grupo in arrayGrupos" :key="grupo.id" 
                                            v-text="grupo.nomgrupo" :value="grupo.idgrupo"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="tabla100">                        
                            <div class="tfila">
                                <div class="tcelda">Auxiliar:</div>
                                <div class="tcelda">
                                    <select class="form-control" v-model="idauxiliar" 
                                        @change="listaActivos(idfilial,idambiente,idgrupo,idauxiliar)">
                                        <option value="">(todos)</option>
                                        <option v-for="auxiliar in arrayAuxiliares" :key="auxiliar.id" 
                                            v-text="auxiliar.nomauxiliar" :value="auxiliar.idauxiliar"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- los filtros -->

        <div v-if="divActivos" class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-5 titcard">
                        <div class="tablatit">
                            <div class="tcelda">
                                <span v-text="regAmbiente.nomambiente+' - '+regFilial.nommunicipio"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 titcard">
                        <div class="tablatit">
                            <div class="tcelda">
                                <span v-text="regGrupo.nomgrupo"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 text-right">
                        <button class="btn btn-primary" style="margin-top:0px" @click="nuevoActivo()">Nuevo Activo</button>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                <h4 v-if="idauxiliar" class="titsubrayado" v-text="regAuxiliar.nomauxiliar"></h4> 
                <table class="table  table-striped table-sm">
                    <thead class="tcabecera">
                        <tr>
                            <th align="center"><span class="badge badge-success noprint" v-text="arrayActivos.length+' items'"></span></th>
                            <th>Código</th>
                            <th v-if="!idauxiliar">Auxiliar</th>
                            <th>Descripción</th>
                            <th>Responsable</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="activo in arrayActivos" :key="activo.idactivo">
                            <td align="center" nowrap>
                                <button class="btn btn-warning btn-sm icon-pencil"  title="Editar datos"
                                    @click="editarActivo(activo)"></button>
                                <button class="btn btn-warning btn-sm icon-user-following" title="Asignar responsable"
                                    @click="asignarActivo(activo)"></button> 
                                <button class="btn btn-warning btn-sm icon-chart" title="Kárdex y Depreciación"
                                    @click="kardexActivo(activo)"></button>
                                <button class="btn btn-danger btn-sm icon-fire" title="Dar de baja" 
                                    @click="bajaActivo(activo)"></button>
                            </td>
                            <td v-text="activo.codactivo" align="center"></td>
                            <td v-if="!idauxiliar" v-text="activo.nomauxiliar"> </td>
                            <td><span v-text="activo.descripcion+'.'"></span>
                                <span v-if="activo.marca" v-text="' Marca: '+activo.marca"></span>
                                <span v-if="activo.serie" v-text="' Serie: '+activo.serie"></span>
                            </td>
                            <td>
                                <!-- <span v-text="listaAsignaciones(activo.idactivo)"></span> -->
                                <span v-text="quienUsa(activo.idactivo)"></span>
                            </td>
                            <!--
                            <td :align="verResponsable(activo.idactivo)?'left':'center'">
                                <span v-if="!verResponsable(activo.idactivo)" class="badge badge-danger">Sin Asignar</span>
                                <span v-else v-text="verResponsable(activo.idactivo)"></span>
                            </td> 
                            -->
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <subKardex v-if="divKardex" :idactivo="idactivo" :currfecha="currfecha"></subKardex>
        <subAsignacion v-if="divAsignacion" :regActivo="regActivo"></subAsignacion>

    </div>

    <!-- MODAL ACTIVO MODAL ACTIVO MODAL ACTIVO MODAL ACTIVO MODAL ACTIVO MODAL ACTIVO MODAL ACTIVO MODAL ACTIVO-->
    <!-- MODAL ACTIVO MODAL ACTIVO MODAL ACTIVO MODAL ACTIVO MODAL ACTIVO MODAL ACTIVO MODAL ACTIVO MODAL ACTIVO-->
    <div class="modal" :class="modalActivo?'mostrar':''" >
        <div class="modal-dialog modal-primary modal-lg">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title"><span v-text="accion==1?'Nuevo':'Modificar'"></span> Activo</h4>
                    <button type="button" class="close" @click="modalActivo=0">×</button>
                </div>
                <div class="modal-body">
                    <div class="row" style="overflow-y:scroll; height:400px">
                        <div class="col-md-4">
                            <h5 class="titazul">Identificación</h5>
                            <div class="table">
                                <div class="tfila">
                                    <div class="tcelda titcampo" style="vertical-align:top">Filial:</div>
                                    <div class="tcelda" style="text-transform:uppercase" v-text="regFilial.nommunicipio"></div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda titcampo" style="vertical-align:top">Oficina:</div>
                                    <div class="tcelda" v-text="regAmbiente.nomambiente"></div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda titcampo" style="vertical-align:top">Cuenta:</div>
                                    <div class="tcelda" v-text="regGrupo.nomgrupo"></div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda titcampo" style="vertical-align:top">Auxiliar:</div>
                                    <div class="tcelda" v-text="regAuxiliar.nomauxiliar"></div>
                                </div>
                            </div>
                            <center>
                                <span class="titcampo">Código <span v-if="accion==1">asignado</span>:</span> 
                                <div class="tcelda" style="padding:0px 4px">
                                    <span class="titsubrayado">ASC</span><br>inst
                                </div>
                                <div class="tcelda" style="padding:0px 4px">
                                    <span class="titsubrayado" v-text="regFilial.codfilial"></span><br>filial
                                </div>
                                <div class="tcelda" style="padding:0px 4px">
                                    <span class="titsubrayado" v-text="regAmbiente.codambiente"></span><br>ofic
                                </div>
                                <div class="tcelda" style="padding:0px 4px">
                                    <span class="titsubrayado" v-text="regGrupo.codgrupo"></span><br>cta
                                </div>
                                <div class="tcelda" style="padding:0px 4px">
                                    <span class="titsubrayado" v-text="regAuxiliar.codauxiliar"></span><br>aux
                                </div>
                                <div class="tcelda" style="padding:0px 4px">
                                    <span class="titsubrayado" v-text="correlativo"></span><br>correl
                                </div>
                            </center>
                        </div>
                        <div class="col-md-4">
                            <h5 class="titazul">Descripción</h5>
                            <center v-text="regAuxiliar.nomauxiliar"></center>
                            <textarea rows="3" class="form-control" style="resize:none" v-model="descripcion" 
                                placeholder="(color, dimensiones, etc)"></textarea>
                            Marca-Modelo:
                            <input type="text" class="form-control" v-model="marca">
                            Nro de Serie:
                            <input type="text" class="form-control" v-model="serie">
                        </div>
                        <div class="col-md-4">
                            <h5 class="titazul">Imagen</h5>
                            <img src="img/activos/general.jpg" :alt="regAuxiliar.nomauxiliar">
                            <center><button class="btn btn-primary">Cargar Imagen</button></center>
                        </div>
                        <div class="col-md-12"><h5 class="titazul">Detalle de la compra</h5></div>
                        <div class="col-md-4">
                            Fecha de adquisición: <span class="txtasterisco"></span>
                            <input type="date" class="form-control" v-model="fechaingreso"
                                name="adq" :class="{'invalido':errors.has('adq')}" v-validate="'required'">
                            <p class="txtvalidador" v-if="errors.has('adq')">Dato requerido</p>
                            Costo: 
                            <div class="input-group">
                                <input type="text" v-model="costo" class="form-control text-right"
                                    name="cos" :class="{'invalido':errors.has('cos')}" v-validate="'required|decimal:2'">
                                <div class="input-group-append"><span class="input-group-text">Bs.</span></div>
                            </div>
                            <p class="txtvalidador" v-if="errors.has('cos')">Dato numérico requerido</p>
                        </div>
                        <div class="col-md-4">
                            Factura:
                            <input type="text" v-model="idcompra" class="form-control text-right">
                            <!--
                            Proveedor: JOTA IMPRENTA – OFFSET 
                            <br>tEL: 2202275-->
                        </div>
                        <div class="col-md-4">
                            Observaciones:
                            <textarea rows="3" class="form-control" style="resize:none" v-model="obs" ></textarea>
                        </div>

                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button class="btn btn-secondary" @click="modalActivo=0">Cerrar</button>
                    <button class="btn btn-primary" @click="validarActivo()">
                        Guardar <span v-if="accion==2">Modificaciones</span></button>
                </div>
            </div>
        </div>
    </div> 
    

    <!-- MODAL BAJA MODAL BAJA MODAL BAJA MODAL BAJA MODAL BAJA MODAL BAJA MODAL BAJA MODAL BAJA-->
    <!-- MODAL BAJA MODAL BAJA MODAL BAJA MODAL BAJA MODAL BAJA MODAL BAJA MODAL BAJA MODAL BAJA-->
    <div class="modal" :class="modalBaja?'mostrar':''" >
        <div class="modal-dialog modal-primary modal-lg">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title">Baja de Activo</h4>
                    <button type="button" class="close" @click="modalBaja=0">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h5 class="titazul">Información</h5>
                            <div class="tfila">
                                <div class="tcelda titcampo">Código:</div>
                                <div class="tcelda" v-text="regActivo.codactivo"></div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Título:</div>
                                <div class="tcelda" v-text="regActivo.nomauxiliar"></div>
                            </div>
                            <div class="titcampo">Descripción:</div>
                            <div v-text="regActivo.descripcion"></div>
                            <br>
                            <div class="tfila">
                                <div class="tcelda titcampo">Adquisición:</div>
                                <div class="tcelda" v-text="(regActivo.fechaingreso)"></div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Filial:</div>
                                <div class="tcelda" v-text="regActivo.nommunicipio"></div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo" style="vertical-align:top">Oficina:</div>
                                <div class="tcelda" v-text="regActivo.nomambiente"></div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo nowrap">Costo Inicial:</div>
                                <div class="tcelda" v-text="regActivo.costo+'Bs.'"></div>
                            </div>
                            <!--
                            <div class="tfila">
                                <div class="tcelda titcampo nowrap">Tiempo Consumido:</div>
                                <div class="tcelda"><span v-text="regDepreciacion.consumido"></span></div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Incremento:</div>
                                <div class="tcelda"> {{formatomon(regDepreciacion.incrtotal)}}Bs.</div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Depreciación:</div>
                                <div class="tcelda"> {{formatomon(regDepreciacion.deprtotal)}}Bs.</div> 
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Valor Final:</div>
                                <div class="tcelda"> {{formatomon(regDepreciacion.valorfin)}}Bs.</div>
                            </div>
                            -->
                        </div>
                        <div class="col-md-4">
                            <h5 class="titazul">Estado físico</h5><br>
                            <img src="img/activos/general.jpg" :alt="regActivo.nomauxiliar">
                        </div>
                        <div class="col-md-4">
                            <h5 class="titazul">Registro de Baja</h5>
                            <div class="tfila">
                                Fecha: <span class="txtasterisco"></span>
                                <input type="date" class="form-control" v-model="fechabaja"
                                    name="baj" :class="{'invalido':errors.has('baj')}" v-validate="'required'">
                                <p class="txtvalidador" v-if="errors.has('baj')">Dato requerido</p>
                                Número Orden/Informe: <span class="txtasterisco"></span>
                                <input v-model="nrorden" type="text" class="form-control"
                                    name="nro" :class="{'invalido':errors.has('nro')}" v-validate="'required'">
                                <p class="txtvalidador" v-if="errors.has('nro')">Dato requerido</p>                                    
                                Motivo: <span class="txtasterisco"></span>
                                <select v-model="idmotivo" class="form-control"
                                    name="mot" :class="{'invalido':errors.has('mot')}" v-validate="'required'">
                                    <option v-for="motivo in arrayMotivos" :key="motivo.idmotivo"
                                        :value="motivo.idmotivo" v-text="motivo.nommotivo"></option>
                                </select>
                                <p class="txtvalidador" v-if="errors.has('mot')">Selecciones un Motivo</p>
                                Obervaciones:
                                <textarea class="form-control" style="resize:none" rows="2" v-model="obsbaja"></textarea>
                                <p class="txtobligatorio text-right"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button class="btn btn-secondary" @click="modalBaja=0">Cerrar</button>
                    <button class="btn btn-danger" @click="validarBaja(regActivo)">Ejecutar Baja </button>
                </div>
            </div>
        </div>

    </div> 
</main>    
</template>

<script>

Vue.component('subAsignacion',require('./subAsignacion.vue').default);
Vue.component('subKardex',    require('./subKardex.vue').default);

import * as jsfechas from '../../fechas.js';
import * as reporte from '../../functions.js';

export default {
    data(){ return {
        modalActivo:'', jsfechas:'', jsfunc:'', ipbirt:'',       
        modalBaja:0, accion:1, currfecha:'',
        divActivos:1, divKardex:0, divAsignacion:0,
        arrayFiliales:[], arrayAmbientes:[], arrayGrupos:[], arrayAuxiliares:[], 
        arrayActivos:[], arrayEmpleados:[], arrayMotivos:[], arrayAsignaciones:[],  
        regFilial:[], regAmbiente:[], regGrupo:[], regAuxiliar:[], regActivo:[],
        idfilial:1, idambiente:1, idgrupo:2, idauxiliar:'',
        codactivo:'', descripcion:'', marca:'', serie:'', correlativo:'',
        idcompra:'', fechaingreso:'', costo:'', residual:0, obs:'', 
        baja:'', fechabaja:'', nrorden:'', idmotivo:'', obsbaja:'',
    }},

    methods:{
        listaFiliales(){
            var url='/fil_filial/listaFiliales?activo=1';
            axios.get(url).then(response=>{
                this.arrayFiliales=response.data.filiales;
                this.verFilial(this.idfilial);
            })
        },

        listaAmbientes(idfilial){
            var url='/act_ambiente/listaAmbientes?idfilial='+idfilial+'&activo=1&orden=nomambiente';
            axios.get(url).then(response=>{
                this.arrayAmbientes=response.data.ambientes;
                this.verAmbiente(this.idambiente);
            });
        },

        listaGrupos(){
            var url='/act_grupo/listaGrupos?activo=1';
            axios.get(url).then(response=>{
                this.arrayGrupos=response.data.grupos;
                this.verGrupo(this.idgrupo);
            });
        },

        listaAuxiliares(idgrupo){
            var url='/act_auxiliar/listaAuxiliares?idgrupo='+idgrupo+'&activo=1&orden=nomauxiliar';
            axios.get(url).then(response=>{
                this.arrayAuxiliares=response.data.auxiliares;
            });
        },

        listaActivos(idfilial,idambiente,idgrupo,idauxiliar){
            if(this.arrayFiliales.length) this.verFilial(idfilial);
            if(this.arrayAmbientes.length) this.verAmbiente(idambiente);
            if(this.arrayGrupos.length)  this.verGrupo(idgrupo);
            if(this.arrayAuxiliares.length)this.verAuxiliar(idauxiliar);
            var url='/act_activo/listaActivos?idfilial='+idfilial+'&idambiente='+idambiente+'&idgrupo='+idgrupo;
            if(idauxiliar) url+='&idauxiliar='+idauxiliar;
            axios.get(url).then(response=>{
                this.arrayActivos=response.data.activos;
                this.ipbirt=response.data.ipbirt;
                this.currfecha=response.data.fechahoy;
            });
        },
        
        listaMotivos(){
            let me=this;
            axios.get('/act_motivo/listaMotivos').then(function(response){
                me.arrayMotivos=response.data.motivos;
            })
        },       
        
        listaAsignaciones(idactivo){
            var url='/act_asignacion/listaAsignaciones?idactivo='+idactivo+'&activo=1';
            axios.get(url).then(response=>{
                this.arrayAsignaciones=response.data.asignaciones;
            });
        },

        quienUsa(){
            return;
        },
        

        verFilial(idfilial){
            for(var i=0; i<this.arrayFiliales.length; i++)
                if(this.arrayFiliales[i].idfilial==idfilial){
                    this.regFilial=this.arrayFiliales[i]; return; }
        },

        verAmbiente(idambiente){
            for(var i=0;i<this.arrayAmbientes.length;i++)
                if(this.arrayAmbientes[i].idambiente==idambiente){
                    this.regAmbiente=this.arrayAmbientes[i]; return; }
        },

        verGrupo(idgrupo){
            for(var i=0;i<this.arrayGrupos.length;i++)
                if(this.arrayGrupos[i].idgrupo==idgrupo){
                    this.regGrupo=this.arrayGrupos[i]; return;  }
        },

        verAuxiliar(idauxiliar){
            for(var i=0;i<this.arrayAuxiliares.length;i++)
                if(this.arrayAuxiliares[i].idauxiliar==idauxiliar){
                    this.regAuxiliar=this.arrayAuxiliares[i]; return; }
        },

        /*
        verResponsable(idactivo){
            for(var i=0; i<this.arrayResponsables.length; i++)
                if(this.arrayResponsables[i].idactivo==idactivo){
                   return this.arrayResponsables[i].nomresponsable; 
                }
            return false;
        },
        */

        nivelPrevio(){
            this.divActivos=1;
            this.divKardex=0;
            this.divAsignacion=0;
        },

        async kardexActivo(activo){
            this.divActivos=0;
            this.divKardex=1;
            this.idactivo=activo.idactivo;
        },

        async bajaActivo(activo){
            this.modalBaja=1;
            this.accion=1;
            this.listaMotivos();
            var url='/act_activo/verActivo?idactivo='+activo.idactivo;           
            let response=await axios.get(url);
            this.regActivo=response.data.activo[0];
            this.$validator.reset();
            //this.depreciacionActual(activo,activo.currfecha);            
        },

        asignarActivo(activo){
            this.regActivo=activo;
            this.divActivos=0;
            this.divKardex=0;
            this.divAsignacion=1;
        },

        generarCodigo(){
            this.verFilial(this.idfilial);
            this.verAmbiente(this.idambiente);
            this.verGrupo(this.idgrupo);
            this.verAuxiliar(this.idauxiliar);
            var ult=this.arrayActivos.length;
            if(ult>0) ult=this.arrayActivos[ult-1].codactivo.substr(-3);
            ult++;
            if(ult<10) ult='0'+ult; if(ult<100) ult='0'+ult;
            this.codactivo=this.regFilial.codfilial+this.regAmbiente.codambiente
                +this.regGrupo.codgrupo+this.regAuxiliar.codauxiliar + ult;
            this.correlativo=ult;
        },

        nuevoActivo(){
            if(!this.idauxiliar) { swal('Especifique Auxiliar','','error'); return; } 
            this.modalActivo=1;
            this.accion=1;
            this.codactivo='';
            this.descripcion='';
            this.marca='';
            this.serie='';
            this.fechaingreso='';
            this.costo='';
            this.obs='';            
            this.generarCodigo();
            this.$validator.reset();
        },

        async editarActivo(activo){
            this.modalActivo=1;
            this.accion=2;
            let response=await axios.get('/act_activo/listaActivos?idactivo='+activo.idactivo);
            this.regActivo=response.data.activos[0];
            this.idactivo=this.regActivo.idactivo;
            this.idfilial=this.regActivo.idfilial;
            this.descripcion=this.regActivo.descripcion;
            this.marca=this.regActivo.marca;
            this.serie=this.regActivo.serie;
            this.fechaingreso=this.regActivo.fechaingreso;
            this.costo=this.regActivo.costo;
            this.obs=this.regActivo.obs;
        },

        validarActivo(){
            this.$validator.validateAll().then(result=>{
                if(!result) { swal('Datos no válidos','Revise los errores','error'); return; }
                this.accion==1?this.storeActivo():this.updateActivo();
            });
        },

        storeActivo(){
            swal({ title:'Procesando...',text:'Un momento por favor', type:'warning',
                showCancelButton:false, showConfirmButton:false, 
                allowOutsideClick: false, allowEscapeKey: false, allowEnterKey: false,
                onOpen:() => { swal.showLoading() }
            });
            axios.post('/act_activo/storeActivo',{
                'codactivo':'ASC'+this.codactivo,
                'descripcion':this.descripcion?this.descripcion.toUpperCase():'',
                'marca':this.marca?this.marca.toUpperCase:'',
                'serie':this.serie,
                'fechaingreso':this.fechaingreso,
                'costo':this.costo,
                'idfilial':this.idfilial,
                'idambiente':this.idambiente,
                'idgrupo':this.idgrupo,
                'idauxiliar':this.idauxiliar,
                'obs':this.obs
            }).then(reponse=>{
                swal('Activo creado correctamente','Proceda a la asignación de responsable','success');
                this.modalActivo=0;
                this.listaActivos(this.idfilial,this.idambiente,this.idgrupo,this.idauxiliar);
            });
        },

        updateActivo(){
            let me=this;
            axios.put('/act_activo/updateActivo',{
                'idactivo':this.idactivo,
                'descripcion':this.descripcion,
                'marca':this.marca,
                'serie':this.serie,
                'fechaingreso':this.fechaingreso,
                'costo':this.costo,
                'obs':this.obs
            }).then(response=>{
                swal('Datos Actualizados','','success');
                this.modalActivo=0;
                this.listaActivos(this.idfilial,this.idambiente,this.idgrupo,this.idauxiliar);
            });
        },

        validarBaja(){
            this.$validator.validateAll().then(result=>{
                if(!result){ swal('Datos no válidos','Revise los errores','error'); return; }
                this.storeBaja();
            })
        },

        storeBaja(activo){
            axios.put('/act_activo/storeBaja',{
                'idactivo':activo.idactivo,
                'fechabaja':this.fechabaja,
                'nrorden':this.nrorden,
                'idmotivo':this.idmotivo,
                'obsbaja':this.obsbaja
            }).then(response=>{
                swal('Operación correcta','Se ha dado de baja este activo. Verifique en el módulo Activos -> Bajas','success');
                this.modalBaja=0;
                this.listaActivos(activo.idfilial,activo.idambiente,activo.idgrupo,activo.idauxiliar);
            });
        },

    },

    mounted(){
        this.listaFiliales();
        this.listaAmbientes(this.idfilial);
        this.listaGrupos();
        this.listaAuxiliares(this.idgrupo);
        this.listaActivos(this.idfilial,this.idambiente,this.idgrupo,this.idauxiliar);
        this.jsfechas=jsfechas;
    }
}
</script>

