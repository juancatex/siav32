<template>
<main class="main">
    <div class="modal" :class="modalPermisos?'mostrar':''">
        <div class="modal-dialog modal-primary modal-lg">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title">Gestión de Permisos</h4>
                    <button class="close" @click="modalPermisos=0">x</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="tablatit">
                                <div class="tcelda">
                                    <span class="titcard" v-text="regEmpleado.nombre+' '+regEmpleado.apaterno"></span>
                                    <span class="titcard" v-text="regEmpleado.amaterno"></span>
                                </div>
                            </div>
                        </div>
                        <div v-if="!divFormulario" class="col-md-3">
                            <div class="tabla100">
                                <div class="tfila">
                                    <div class="tcelda">Periodo:</div>
                                    <div class="tcelda">
                                        <select class="form-control" v-model="per">
                                            <option v-for="(mes,i) in arrayMeses" :key="i" :value="i" v-text="mes"></option>
                                        </select>
                                    </div>
                                    <div class="tcelda">
                                        <select class="form-control" v-model="ges">
                                            <option value="2020">2020</option>
                                            <option value="2019">2019</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="!divFormulario" class="col-md-3">
                            <button class="btn btn-primary btn-block" @click="nuevoPermiso()">Nuevo Permiso</button>
                        </div>
                    </div>
                    <br>
                    <div v-if="!divFormulario" class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead class="tcabecera">
                                <tr>
                                    <th>Nro</th>
                                    <th>Motivo</th>
                                    <th>Solicitud</th>
                                    <th>Haberes</th>
                                    <th>Cantidad</th>
                                    <th>Cuándo</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(permiso,i) in arrayPermisos" :key="permiso.id" align="center">
                                    <td v-text="i+1"></td>
                                    <td v-text="permiso.nommotivo" align="left"></td>
                                    <td v-text="jsfechas.fechames(permiso.fechasolicitud)"></td>
                                    <td nowrap><span v-text="permiso.gocehaberes?'CON':'SIN'"></span> goce</td>
                                    <td><span v-text="permiso.cantidad>4?permiso.cantidad/8:permiso.cantidad"></span>
                                        <span v-text="permiso.cantidad>4?'Días':'Horas'"></span>
                                    </td>
                                    <td align="center" nowrap>
                                        <span v-text="jsfechas.fechames(permiso.fechasalida)"></span><br>
                                        <span v-if="permiso.cantidad>4">al
                                            <span v-text="jsfechas.fechames(permiso.fechasalida.substr(0,8)+(1*permiso.fechasalida.substr(8,2)+permiso.cantidad/8))"></span> 
                                        </span>
                                        <span v-else>
                                            <span v-text="permiso.horasalida"></span> a 
                                            <span v-text="((1*permiso.horasalida.substr(0,2))+permiso.cantidad)+':'+permiso.horasalida.substr(-2)"></span>
                                        </span>
                                    </td>
                                    <td align="center" nowrap>
                                        <button class="btn btn-warning btn-sm icon-pencil" title="Modificar datos"
                                            @click="editarPermiso(permiso)"></button>
                                        <button class="btn btn-warning btn-sm icon-printer" title="Imprimir boleta"
                                            @click="boletaPermiso(permiso)"></button>
                                        <button class="btn btn-danger btn-sm icon-trash" title="Desactivar"
                                            @click="estadoPermiso(permiso)"></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h4 v-if="divFormulario" class="titazul"><span v-text="accion==1?'Nuevo':'Editar'"></span> Permiso</h4>
                    <div v-if="divFormulario" class="row">
                        <div class="col-md-4">
                            Tipo de Permiso
                            <select class="form-control" v-model="gocehaberes">
                                <option value="1">CON goce de haberes</option>
                                <option value="0">SIN goce de haberes</option>
                            </select>
                            Motivo
                            <select class="form-control" v-model="idmotivo">
                                <option v-for="motivo in arrayMotivos" :key="motivo.id"
                                    :value="motivo.idmotivo" v-text="motivo.nommotivo"></option>
                            </select>                            
                            Fecha de Solicitud
                            <input type="date" class="form-control" v-model="fechasolicitud">
                        </div>
                        <div class="col-md-4">
                            Lapso:
                            <select class="form-control" v-model="lapso">
                                <option value="1">Horas</option>
                                <option value="2">Días</option>
                            </select>
                            Cantidad solicitada:
                            <div class="input-group">                                
                                <select class="form-control text-right" v-model="cantidad">
                                    <option v-for="i in 4" :key="i" :value="i" v-text="i"></option>                                    
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text" v-text="lapso==1?'Horas':'Días'"></span>
                                </div>
                            </div>
                            <div v-if="lapso==1" class="tabla100">
                                <div class="tfila">
                                    <div class="tcelda">De Horas:
                                        <input type="time" class="form-control" v-model="horasalida">
                                    </div>
                                    <div class="tcelda" style="width:20px"></div>
                                    <div class="tcelda">A Horas:
                                        <input type="time" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <div v-else class="text-center"><br>
                                <input type="checkbox"> Con cargo a vacaciones
                            </div>
                        </div>
                        <div class="col-md-4">
                            Desde Fecha:
                            <input type="date" class="form-control" v-model="fechasalida">
                            Hasta Fecha:
                            <input type="date" class="form-control" v-model="fechasalida" readonly>
                            <center>Acumulado Dic/2019:</center>
                            <div class="tfila">
                                <div class="tcelda">
                                    <div class="input-group" >
                                        <input type="text" class="form-control text-center txtnegrita" v-model="acumdias" disabled>
                                        <div class="input-group-append">    
                                            <span class="input-group-text">Días</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="tcelda" style="width:10px"></div>
                                <div class="tcelda">
                                    <div class="input-group" >
                                        <input type="text" class="form-control text-center txtnegrita" v-model="acumhoras" disabled>
                                        <div class="input-group-append">    
                                            <span class="input-group-text">Horas</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" style="padding-top:20px">
                            <div class="ttabla">
                                <div class="tfila">
                                    <div class="tcelda taltura">Descripción:</div>
                                    <div class="tcelda tinput"><input type="text" class="form-control" v-model="obs"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 text-right txtvalidador">* Datos obligatorios</div>
                    </div>
                </div>
                <div v-if="divFormulario" class="modal-footer">
                    <button class="btn btn-secondary" @click="divFormulario=0">Cerrar</button>
                    <button class="btn btn-primary" @click="accion==1?storePermiso():updatePermiso()">
                        Guardar <span v-if="accion==2">Modificaciones</span></button>
                </div>
                <div v-else class="modal-footer">
                    <button class="btn btn-secondary" @click="modalPermisos=0">Cerrar</button>
                    <button class="btn btn-primary">Imprimir</button>
                </div>
            </div>
        </div>        
    </div>    
</main>
</template>

<script>
import * as jsfechas from '../../fechas.js';
import * as reporte from '../../functions.js';

export default {
    data(){ return {
        modalPermisos:'', accion:'', jsfechas:'', divFormulario:'',
        arrayPermisos:[], arrayMotivos:[], regEmpleado:[],
        idpermiso:'', idmotivo:'', gocehaberes:'', cargovacacion:'', 
        fechasolicitud:'', fechasalida:'', horasalida:'', obs:'',
        lapso:'', cantidad:'', ipbirt:'', 
        acumhoras:'', acumdias:'', ges:'', per:'',
        arrayMeses:['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
    }},

    methods:{
        abrirModal(empleado){
            window.scroll({top:0,left:0,behavior:'smooth'});
            this.jsfechas=jsfechas;
            this.modalPermisos=1;
            this.listaPermisos(empleado.idempleado);
            this.listaMotivos();
            this.accion=1;
            this.idmotivo='';
            this.regEmpleado=empleado;
        },

        listaPermisos(idempleado){
            var url='/rrh_permiso/listaPermisos?idempleado='+idempleado;
            axios.get(url).then(response=>{
                this.arrayPermisos=response.data.permisos;
                this.ipbirt=response.data.ipbirt;
                this.acumdias=response.data.acumdias;
                this.acumhoras=response.data.acumhoras;
            });
        },

        listaMotivos(){
            var url='/rrh_motivo/listaMotivos?activo=1';
            axios.get(url).then(response=>{
                this.arrayMotivos=response.data.motivos;
            })
        },

        setHorafin(){
            this.horafin;
        },

        setFechafin(){
            this.fechafin;
        },

        boletaPermiso(permiso){
            var url=[];
            url.push('http://'+this.ipbirt+':8080');
            url.push('/birt-viewer/frameset?__report=reportes/rhumanos');
            url.push('/rrh_permiso.rptdesign'); //archivo
            url.push('&idpermiso='+permiso.idpermiso); 
            url.push('&__format=pdf'); //formato
            reporte.viewPDF(url.join(''),'Boleta de Permiso');
        },

        nuevoPermiso(){
            this.idmotivo='';
            this.gocehaberes=1;
            this.cargovacacion=0;
            this.fechasolicitud='';
            this.lapso=1;
            this.cantidad='';
            this.fechasalida='';
            this.horasalida='';
            this.obs='';
            this.divFormulario=1;
            this.accion=1;
        },

        editarPermiso(permiso){
            this.divFormulario=1;
            this.accion=2;
            this.idpermiso=permiso.idpermiso;
            this.idmotivo=permiso.idmotivo;
            this.gocehaberes=permiso.gocehaberes;
            this.fechasolicitud=permiso.fechasolicitud;
            this.lapso=this.cantidad<=4?1:2;
            this.cantidad=permiso.cantidad;
            this.fechasalida=permiso.fechasalida;
            this.horasalida=permiso.horasalida;
            this.obs=permiso.obs;
        },

        storePermiso(){
            let me=this;
            axios.post('/rrh_permiso/storePermiso',{
                'idmotivo':this.idmotivo,
                'idempleado':this.regEmpleado.idempleado,
                'gocehaberes':this.gocehaberes,
                'cargovacacion':this.cargovacacion,
                'fechasolicitud':this.fechasolicitud,
                //'lapso':this.lapso,
                'cantidad':this.lapso==1?this.cantidad:this.cantidad*8,
                'fechasalida':this.fechasalida,
                'horasalida':this.horasalida,
                'horafin':this.horafin,
                'obs':this.obs
            }).then(function(){
                swal('Permiso creado correctamente','','success');
                me.listaPermisos(me.regEmpleado.idempleado);
                me.divFormulario=0;
            });
        },

        updatePermiso(){
            let me=this;
            axios.put('/rrh_permiso/updatePermiso',{
                'idpermiso':this.idpermiso,
                'idmotivo':this.idmotivo,
                'gocehaberes':this.gocehaberes,
                'cargovacacion':this.cargovacacion,
                'fechasolicitud':this.fechasolicitud,
                //'lapso':this.lapso,
                'cantidad':this.lapso==1?this.cantidad:this.cantidad*8,
                'fechasalida':this.fechasalida,
                'horasalida':this.horasalida,
                'horafin':this.horafin,
                'obs':this.obs
            }).then(function(){
                swal('Datos actualizados','','success');
                me.listaPermisos(me.regEmpleado.idempleado);
                me.divFormulario=0;
            });
        },
    }
}
</script>