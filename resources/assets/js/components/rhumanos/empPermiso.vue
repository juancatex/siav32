<template>
<main>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <div class="tablatit">
                        <div class="tcelda">
                            <span class="titcard" v-text="regEmpleado.nombre+' '+regEmpleado.apaterno"></span>
                            <span class="titcard" v-text="regEmpleado.amaterno"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <button class="btn btn-primary" style="margin-top:0" @click="nuevoPermiso()">Nuevo Permiso</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 text-right">
                    <div style="display:inline-block; padding-bottom:10px">
                        <div class="tcelda">Periodo:</div>
                        <div class="tcelda">
                            <select class="form-control" v-model="per" @change="listaPermisos(regEmpleado.idempleado,ges,per)">
                                <option v-for="(mes,i) in arrayMeses" :key="i" :value="i+1" v-text="mes"></option>
                            </select>
                        </div>
                        <div class="tcelda">
                            <select class="form-control" v-model="ges" @change="listaPermisos(regEmpleado.idempleado,ges,per)">
                                <option value="2020">2020</option>
                                <option value="2019">2019</option>
                            </select>
                        </div>
                        <div class="tcelda" style="padding-left:5px">
                            <button class="btn btn-success btn-sm icon-printer" title="Vista de impresión" @click="reportePermisos()"></button>
                        </div>                        
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead class="tcabecera">
                        <tr align="center">
                            <th><span class="badge badge-success" v-text="arrayPermisos.length+' items'"></span></th>
                            <th>Nro</th>
                            <th align="left">Motivo</th>
                            <th>Solicitud</th>
                            <th>Haberes</th>
                            <th>Cantidad</th>
                            <th>Cuándo</th>
                            <th align="left">Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="permiso in arrayPermisos" :key="permiso.id" align="center" :class="permiso.activo?'':'txtdesactivado'">
                            <td v-if="permiso.activo" nowrap>
                                <button class="btn btn-warning btn-sm icon-pencil" title="Modificar datos"
                                    @click="editarPermiso(permiso)"></button>
                                <button class="btn btn-warning btn-sm icon-printer" title="Imprimir boleta"
                                    @click="reporteBoleta(permiso)"></button>
                                <button class="btn btn-danger btn-sm icon-trash" title="Desactivar permiso"
                                    @click="estadoPermiso(permiso)"></button>
                            </td>
                            <td v-else>
                                <button class="btn btn-warning btn-sm icon-action-redo" title="Restaurar permiso"
                                    @click="estadoPermiso(permiso)"></button>
                            </td>
                            <td></td>
                            <td v-text="permiso.nommotivo" align="left"></td>
                            <td v-text="jsfechas.fechames(permiso.fechasolicitud)"></td>
                            <td nowrap><span v-text="permiso.gocehaberes?'CON':'SIN'"></span> goce</td>
                            <td><span v-text="permiso.cantidad>4?permiso.cantidad/8:permiso.cantidad"></span>
                                <span v-text="permiso.cantidad>4?'Días':'Horas'"></span>
                            </td>
                            <td nowrap>
                                <span v-text="jsfechas.fechames(permiso.fechasalida)"></span><br>
                                <span v-if="permiso.cantidad>4">al
                                    <span v-text="jsfechas.fechames(permiso.fechasalida.substr(0,8)+(1*permiso.fechasalida.substr(8,2)+permiso.cantidad/8))"></span> 
                                </span>
                                <span v-else>
                                    <span v-text="permiso.horasalida"></span> a 
                                    <span v-text="((1*permiso.horasalida.substr(0,2))+permiso.cantidad)+':'+permiso.horasalida.substr(-2)"></span>
                                </span>
                            </td>
                            <td v-text="permiso.obs" align="left"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <br><h4 class="titsubrayado">RESUMEN DE PERMISOS Y LICENCIAS</h4>
            <table class="table table-bordered">
                <thead class="tcabecera">
                    <tr align="center">
                        <th></th>
                        <th v-for="mes in arrayMeses" :key="mes" v-text="mes"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr align="center">
                        <td align="left">CON goce de haberes Hrs.</td>
                        <th v-for="acu in arrayAcumuladoConGoce" :key="acu.mes" v-text="acu.total"></th> 
                    </tr>
                    <tr align="center">
                        <td align="left">SIN goce de haberes Hrs.</td>
                        <th v-for="acu in arrayAcumuladoSinGoce" :key="acu.mes" v-text="acu.total"></th>
                    </tr>
                    <!-- <tr align="center">
                        <td align="left">A cuenta de Vacación</td>
                    </tr> -->
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal" :class="modalPermisos?'mostrar':''">
        <div class="modal-dialog modal-primary modal-lg">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title"><span v-text="accion==1?'Nuevo':'Modificar'"></span> Permiso</h4>
                    <button class="close" @click="modalPermisos=0">x</button>
                </div>
                <div class="modal-body">
                    <div  class="row">
                        <div class="col-md-4">
                            Tipo de Permiso: <span class="txtasterisco"></span>
                            <select class="form-control" v-model="gocehaberes"
                                name="tip" :class="{'invalido':errors.has('tip')}" v-validate="'required'">
                                <option value="1">CON goce de haberes</option>
                                <option value="0">SIN goce de haberes</option>
                            </select>
                            <p v-if="errors.has('tip')" class="txtvalidador">Seleccione un Tipo</p>
                            Motivo: <span class="txtasterisco"></span>
                            <select class="form-control" v-model="idmotivo"
                                name="mot" :class="{'invalido':errors.has('mot')}" v-validate="'required'">
                                <option v-for="motivo in arrayMotivos" :key="motivo.id"
                                    :value="motivo.idmotivo" v-text="motivo.nommotivo"></option>
                            </select>
                            <p v-if="errors.has('mot')" class="txtvalidador">Seleccione un Motivo</p>
                            Fecha de Solicitud: <span class="txtasterisco"></span>
                            <input type="date" class="form-control" v-model="fechasolicitud"
                                name="sol" :class="{'invalido':errors.has('sol')}" v-validate="'required'">
                            <p v-if="errors.has('sol')" class="txtvalidador">Dato requerido</p>                                
                        </div>
                        <div class="col-md-4">
                            Lapso: <span class="txtasterisco"></span>
                            <select class="form-control" v-model="lapso"
                                name="lap" :class="{'invalido':errors.has('lap')}" v-validate="'required'">
                                <option value="1">Horas</option>
                                <option value="2">Días</option>
                            </select>
                            <p v-if="errors.has('lap')" class="txtvalidador">Seleccione el lapso</p>
                            Cantidad solicitada: <span class="txtasterisco"></span>
                            <div class="input-group">                                
                                <select class="form-control text-right" v-model="cantidad"
                                    name="can" :class="{'invalido':errors.has('can')}" v-validate="'required'">
                                    <option v-for="i in 4" :key="i" :value="i" v-text="i"></option>                                    
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text" v-text="lapso==1?'Horas':'Días'"></span>
                                </div>
                            </div>
                            <p v-if="errors.has('can')" class="txtvalidador">Dato requerido</p>
                            <div v-if="lapso==1" class="tabla100">
                                <div class="tfila">
                                    <div class="tcelda">De Horas: <span class="txtasterisco"></span>
                                        <input type="time" class="form-control" v-model="horasalida" @change="setHorafin()">
                                    </div>
                                    <div class="tcelda" style="width:20px"></div>
                                    <div class="tcelda">A Horas:
                                        <input type="time" class="form-control" v-model="horafin" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <div v-else class="text-center"><br>
                                <input type="checkbox"> Con cargo a vacaciones
                            </div>
                        </div>
                        <div class="col-md-4">
                            Desde Fecha: <span class="txtasterisco"></span>
                            <input type="date" class="form-control" v-model="fechasalida" @change="setFechafin()">
                            Hasta Fecha:
                            <input type="date" class="form-control" v-model="fechafin" readonly>
                            <center class="titcampo">Acumulado <span v-text="arrayMeses[per-1]+'/'+ges"></span>:</center>
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
                                    <div class="input-group">
                                        <input type="text" class="form-control text-center txtnegrita" v-model="acumhoras" disabled>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Horas</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><br>
                        <div class="col-md-12" style="padding-top:20px">
                            <div class="ttabla">
                                <div class="tfila">
                                    <div class="tcelda taltura">Descripción:</div>
                                    <div class="tcelda tinput"><input type="text" class="form-control" v-model="obs"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 text-right txtobligatorio"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="modalPermisos=0">Cerrar</button>
                    <button class="btn btn-primary" @click="validarPermiso()">
                        Guardar <span v-if="accion==2">Modificaciones</span></button>
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
    props:['regEmpleado','currfecha'], 

    data(){ return {
        modalPermisos:'', accion:'', jsfechas:'', ipbirt:'', 
        arrayPermisos:[], arrayMotivos:[], 
        idpermiso:'', idmotivo:'', gocehaberes:'', cargovacacion:'', 
        fechasolicitud:'', fechasalida:'', horasalida:'', obs:'',
        lapso:'', cantidad:'', fechafin:'', horafin:'', 
        acumhoras:'', acumdias:'', ges:'', per:'',
        arrayMeses:['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
        arrayAcumuladoConGoce:[],arrayAcumuladoSinGoce:[]
    }},

    methods:{
        listaPermisos(idempleado,ges,per){
            if(per<10) per='0'+per;
            var url='/rrh_permiso/listaPermisos?idempleado='+idempleado+'&periodo='+ges+'-'+per;
            axios.get(url).then(response=>{
                this.arrayPermisos=response.data.permisos;
                if(!this.arrayPermisos.length) swal('Error','No existen registros para el periodo seleccionado','error');
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

        horasAcumuladasConGoce(idempleado) {
            var url='/rrh_permiso/horasAcumuladasConGoce?idempleado='+idempleado;
            axios.get(url).then(response=>{
                this.arrayAcumuladoConGoce=response.data.acumuladoConGoce;                                
            });
        },

        horasAcumuladasSinGoce(idempleado) {
            var url='/rrh_permiso/horasAcumuladasSinGoce?idempleado='+idempleado;
            axios.get(url).then(response=>{
                this.arrayAcumuladoSinGoce=response.data.acumuladoSinGoce;                                
            });
        },

        setHorafin(){
            if(!this.horasalida) return;
            var hh=1*this.horasalida.substr(0,2)+this.cantidad;
            if(hh<10) hh='0'+hh;
            var mm=this.horasalida.substr(3,2);
            this.horafin=hh+':'+mm;
        },

        setFechafin(){
            if(this.lapso==1) { this.fechafin=this.fechasalida; return; }
            var ff=1*this.fechasalida.substr(9)+this.cantidad;
            if(ff<10) ff='0'+ff;
            this.fechafin=this.fechasalida.substr(0,8)+ff;
        },

        nuevoPermiso(){
            this.modalPermisos=1;
            this.accion=1;
            this.listaMotivos();
            this.idmotivo='';
            this.gocehaberes=1;
            this.cargovacacion=0;
            this.fechasolicitud='';
            this.cantidad='';
            this.fechasalida='';
            this.horasalida='';
            this.obs='';
            this.$validator.reset();
        },

        editarPermiso(permiso){
            this.modalPermisos=1;
            this.accion=2;
            this.listaMotivos();
            this.idpermiso=permiso.idpermiso;
            this.idmotivo=permiso.idmotivo;
            this.gocehaberes=permiso.gocehaberes;
            this.fechasolicitud=permiso.fechasolicitud;
            this.lapso=this.cantidad<=4?1:2;
            this.cantidad=this.cantidad<=4?permiso.cantidad:permiso.cantidad/8;
            this.fechasalida=permiso.fechasalida;
            this.horasalida=permiso.horasalida;
            this.obs=permiso.obs;
            this.setHorafin();
            this.setFechafin();
        },

        validarPermiso(){
            this.$validator.validateAll().then(result=>{
                if(!result) { swal('Datos inválidos','Revise los errores','error'); return; }
                this.accion==1?this.storePermiso():this.updatePermiso();
            });
        },

        storePermiso(){
            swal({ title:'Procesando...',text:'Un momento por favor', type:'warning',
                showCancelButton:false, showConfirmButton:false, 
                allowOutsideClick: false, allowEscapeKey: false, allowEnterKey: false,
                onOpen:() => { swal.showLoading() }
            });            
            axios.post('/rrh_permiso/storePermiso',{
                'idmotivo':this.idmotivo,
                'idempleado':this.regEmpleado.idempleado,
                'gocehaberes':this.gocehaberes,
                'cargovacacion':this.cargovacacion,
                'fechasolicitud':this.fechasolicitud,
                'cantidad':this.lapso==1?this.cantidad:this.cantidad*8,
                'fechasalida':this.fechasalida,
                'horasalida':this.horasalida,
                'horafin':this.horafin,
                'obs':this.obs
            }).then(response=>{
                swal('Permiso creado correctamente','','success');
                var ges=this.fechasalida.substr(0,4);
                var per=1*this.fechasalida.substr(5,2);
                this.listaPermisos(this.regEmpleado.idempleado,ges,per);
                this.modalPermisos=0;
            });
        },

        updatePermiso(){
            axios.put('/rrh_permiso/updatePermiso',{
                'idpermiso':this.idpermiso,
                'idmotivo':this.idmotivo,
                'gocehaberes':this.gocehaberes,
                'cargovacacion':this.cargovacacion,
                'fechasolicitud':this.fechasolicitud,
                'cantidad':this.lapso==1?this.cantidad:this.cantidad*8,
                'fechasalida':this.fechasalida,
                'horasalida':this.horasalida,
                'horafin':this.horafin,
                'obs':this.obs
            }).then(response=>{
                swal('Datos actualizados','','success');
                var ges=this.fechasalida.substr(0,4);
                var per=1*this.fechasalida.substr(5,2);
                this.listaPermisos(this.regEmpleado.idempleado,ges,per);
                this.modalPermisos=0;
            });
        },

        reporteBoleta(permiso){
            var url=[];
            url.push('http://'+this.ipbirt+':8080');
            url.push('/birt-viewer/frameset?__report=reportes/rhumanos');
            url.push('/rrh_permiso.rptdesign'); //archivo
            url.push('&idpermiso='+permiso.idpermiso); 
            url.push('&__format=pdf'); //formato
            reporte.viewPDF(url.join(''),'Boleta de Permiso');
        },

        reportePermisos(){
            var url=[];
            url.push('http://'+this.ipbirt+':8080');
            url.push('/birt-viewer/frameset?__report=reportes/rhumanos');
            url.push('/rrh_listapermisos.rptdesign'); //archivo
            url.push('&idempleado='+this.regEmpleado.idempleado); 
            //url.push('&gestion='+periodo); 
            url.push('&__format=pdf'); //formato
            reporte.viewPDF(url.join(''),'Papeleta de Pago');
        },


    },

    mounted(){
        this.jsfechas=jsfechas;
        this.ges=this.currfecha.substr(0,4);
        this.per=1*this.currfecha.substr(5,2);
        this.listaPermisos(this.regEmpleado.idempleado,this.ges,this.per);
        this.horasAcumuladasConGoce(this.regEmpleado.idempleado);
        this.horasAcumuladasSinGoce(this.regEmpleado.idempleado);
    },
}
</script>