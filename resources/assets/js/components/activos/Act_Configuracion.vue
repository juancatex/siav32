<template>
<main class="main">
    <div class="breadcrumb titmodulo">Activos > Configuración de Parámetros</div>
    <div class="container-fluid">
        <div class="card" >
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="tablatit">
                            <div class="tcelda titcard">Clasificación de Grupos </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 text-right">
                        <button class="btn btn-primary" style="margin-top:0" @click="nuevoGrupo()">Nuevo Grupo</button>
                    </div>
                </div>                
            </div>
            <div class="card-body table-responsive">
                <table class="table table-striped table-sm">
                    <thead class="tcabecera">
                        <tr>
                            <th><span class="badge badge-success" v-text="arrayGrupos.length+' items'"></span></th>
                            <th>Cód</th>
                            <th>Nombre Grupo</th>
                            <th>Vida útil</th>
                            <th>Depre ciación</th>
                            <th>Cuenta Contable</th>
                            <th>Cuenta Depreciación</th>
                            <th>Cuenta Gastos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="grupo in arrayGrupos" :key="grupo.idgrupo" :class="grupo.activo?'':'txtdesactivado'">
                            <td v-if="grupo.activo" align="center" nowrap>
                                <button class="btn btn-warning btn-sm icon-pencil" title="Editar Datos"
                                    @click="editarGrupo(grupo)"></button>
                                <button class="btn btn-danger btn-sm icon-trash" title="Desactivar Grupo"
                                    @click="estadoGrupo(grupo)"></button>
                            </td>
                            <td v-else align="center" >
                                <button class="btn btn-warning btn-sm icon-action-redo" title="Restaurar Grupo"
                                    @click="estadoGrupo(grupo)"></button>
                            </td>
                            <td v-text="grupo.codgrupo" align="center" ></td>
                            <td v-text="grupo.nomgrupo"></td>
                            <td align="center" ><span v-if="grupo.vida" v-text="grupo.vida+' años'"></span> </td>
                            <td align="center" ><span v-if="grupo.vida" v-text="Math.round(10000/grupo.vida)/100+'%'"></span></td>
                            <td v-text="grupo.cuentaconta"></td>
                            <td v-text="grupo.cuentadepre"></td>
                            <td v-text="grupo.cuentagasto"></td>
                        </tr>                                
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="tablatit">
                                    <div class="tcelda titcard">Motivos de Baja</div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 text-right">
                                <button v-if="!formMotivo" class="btn btn-primary" style="margin-top:0" 
                                    @click="nuevoMotivo()">Nuevo Motivo</button>
                            </div>
                        </div>                
                    </div>
                    <div class="card-body">
                        <table v-if="!formMotivo" class="table table-striped table-sm">
                            <thead class="tcabecera">
                                <th><span class="badge badge-success" v-text="arrayMotivos.length+' items'"></span></th>
                                <th>Descripción Motivo</th>
                            </thead>
                            <tbody>
                                <tr v-for="motivo in arrayMotivos" :key="motivo.id" :class="motivo.activo?'':'txtdesactivado'" align="center">
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
                        <div v-if="formMotivo"><br>
                            <h4 class="titazul"><span v-text="accion==1?'Nuevo':'Modificar'"></span> Motivo</h4>                        
                            Descripción del Motivo
                            <input type="text" class="form-control" v-model="nommotivo">
                            <p align="right"><br>
                            <button class="btn btn-secondary" @click="formMotivo=0">Cancelar</button>
                            <button class="btn btn-primary" @click="accion==1?storeMotivo():updateMotivo()">
                                Guardar <span v-if="accion==2">Modificaciones</span></button>
                            </p>                    
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header titcard">Índices UFV</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 text-center titcard">
                                <span v-text="fecha?jsfechas.fechadia(fecha):''"></span>
                            </div>
                            <div class="col-md-6 text-center titcard">
                                Valor UFV: <span v-text="ufvfecha"></span>
                            </div>
                        </div>
                        <br>
                        <div class="tabla100">
                            <div class="tfila">
                                <div class="tcelda">Índices hasta:</div>
                                <div class="tcelda">10/02/2020</div>
                                <div class="tcelda"></div>
                            </div>
                            <br>
                            <div class="tfila">
                                <div class="tcelda">Consultar Fecha:</div>
                                <div class="tcelda">
                                    <input type="date" class="form-control" v-model="fecha" min="2009-01-01" max="2020-02-10" @change="verUfv(fecha)">
                                </div>
                                <div class="tcelda">
                                    <button class="btn btn-primary btn-block" @click="verIndice(gestion)">Ver</button>
                                </div>
                            </div>
                            <br>

                            <div class="tfila">
                                <div class="tcelda">Planilla Gestión: </div>
                                <div class="tcelda">
                                    <select class="form-control" v-model="gestion">
                                        <option v-for="i in 12" :key="i" :value="i+2008" v-text="i+2008"></option>
                                    </select>
                                </div>
                                <div class="tcelda">
                                    <button class="btn btn-primary btn-block" @click="verPlanilla(gestion)">Ver</button>
                                </div>
                            </div>
                            <br>
                            <div class="tfila">
                                <div class="tcelda">Actualizar Planilla:</div>
                            </div>
                        </div> 
                        <div class="tabla100">
                            <div class="tfila">
                                <div class="tcelda">
                                    <excelReader @array_Files_Data="datasArray"></excelReader>
                                </div>
                                <div class="tcelda">
                                    <button class="btn btn-primary" @click="procesarExcel()">Guardar</button>
                                </div>
                            </div>
                        </div>
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL GRUPOS MODAL GRUPOS MODAL GRUPOS MODAL GRUPOS   -->
    <!-- MODAL GRUPOS MODAL GRUPOS MODAL GRUPOS MODAL GRUPOS   -->
    <div class="modal" :class="modalGrupos?'mostrar':''">
        <div class="modal-dialog modal-primary">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title"><span v-text="accion==1?'Nuevo':'Modificar'"></span> Grupo</h4>
                    <button type="button" class="close" @click="modalGrupos=0">x</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            Código:
                            <input type="text" class="form-control text-center txtnegrita" v-model="codgrupo" disabled>
                            Nombre del Grupo:
                            <input  type="text" class="form-control" v-model="nomgrupo" >
                            <div class="tfila">
                                <div class="tcelda">
                                    Vida útil:
                                    <div class="input-group">
                                        <input  type="text" v-model="vida" class="form-control text-right" 
                                            @keyup="vida?coef=Math.round(10000/vida)/100:''">
                                        <span class="input-group-text">Años</span>
                                    </div>
                                </div>
                                <div class="tcelda" style="width:20px"></div>
                                <div class="tcelda">
                                    Depreciación:
                                    <div class="input-group">
                                        <input  type="text" v-model="coef" class="form-control text-right" disabled>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            Cuenta Contable:
                            <select class="form-control" v-model="idcuentaconta">
                                <template v-for="cuenta in arrayCuentas">
                                    <option v-if="cuenta.grupo==1" :key="cuenta.id" 
                                        :value="cuenta.idcuenta" v-text="cuenta.nomcuenta">
                                    </option>
                                </template>
                            </select>
                            Cuenta de Depreciación:
                            <select class="form-control" v-model="idcuentadepre">
                                <template v-for="cuenta in arrayCuentas">
                                    <option v-if="cuenta.grupo==2" :key="cuenta.id" 
                                        :value="cuenta.idcuenta" v-text="cuenta.nomcuenta">
                                    </option>
                                </template>
                            </select>
                            Cuenta de Gasto:
                            <select class="form-control" v-model="idcuentagasto">
                                <template v-for="cuenta in arrayCuentas">
                                    <option v-if="cuenta.grupo==3" :key="cuenta.id" 
                                        :value="cuenta.idcuenta" v-text="cuenta.nomcuenta">
                                    </option>
                                </template>
                            </select>
                        </div>
                    </div>
                </div>        
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="modalGrupos=0">Cerrar</button>
                    <button class="btn btn-primary" @click="accion==1?storeGrupo():updateGrupo()">
                        Guardar <span v-if="accion==2">Modificaciones</span></button>
                </div>
            </div>
        </div>
    </div>
    

</main>
</template>

<script>
import * as reporte from '../../functions.js';
import * as jsfechas from '../../fechas';

export default {
    data (){ return {
        modalGrupos:0, formMotivo:'', accion:'', ipbirt:'', jsfechas:'', csrfToken:'',
        arrayGrupos:[], arrayCuentas:[], 
        idgrupo:'', codgrupo:'', nomgrupo:'', vida:'', coef:'',
        idcuentaconta:'', idcuentadepre:'', idcuentagasto:'',
        arrayMotivos:[], idmotivo:'', nommotivo:'',

        gestion:'', fecha:'', ufvfecha:'',
                parse_csv:[], nombre_archivo:[],
    }},

    methods : {
        // cuentas cuentas cuentas cuentas cuentas cuentas cuentas 
        // cuentas cuentas cuentas cuentas cuentas cuentas cuentas 
        listaGrupos(){
            var url= '/act_grupo/listaGrupos';
            axios.get(url).then(response=>{
                this.arrayGrupos=response.data.grupos;
                this.ipbirt=response.data.ipbirt; 
            });
        },

        listaCuentas(){
            var url='/act_grupo/listaCuentas';
            axios.get(url).then(response=>{
                this.arrayCuentas = response.data.cuentas;
            });
        },

        generarCodigo(){
            var tam=this.arrayGrupos.length-1;
            this.codgrupo=0;
            if(tam>=0) this.codgrupo=this.arrayGrupos[tam].codgrupo;
            this.codgrupo++;
            if(this.codgrupo<10) this.codgrupo='0'+this.codgrupo;
        },

        nuevoGrupo(){
            this.modalGrupos=1;
            this.accion=1;
            this.generarCodigo();
            this.nomgrupo='';
            this.vida='';
            this.idcuentaconta='';
            this.idcuentadepre='';
            this.idcuentagasto='';
        },

        editarGrupo(grupo){
            this.modalGrupos=1;
            this.accion=2;
            this.idgrupo =grupo.idgrupo;
            this.codgrupo=grupo.codgrupo;
            this.nomgrupo=grupo.nomgrupo;
            this.vida=grupo.vida;
            this.coef=Math.round(10000/grupo.vida)/100;
            this.idcuentaconta=grupo.idcuentaconta;
            this.idcuentadepre=grupo.idcuentadepre;
            this.idcuentagasto=grupo.idcuentagasto;
        },

        storeGrupo(){
            swal({ title:'Procesando...',text:'Un momento por favor', type:'warning',
                showCancelButton:false, showConfirmButton:false, closeOnConfirm: false,
                allowOutsideClick: false, allowEscapeKey: false, allowEnterKey: false,
                onOpen:() => { swal.showLoading() }
            });            
            axios.post('/act_grupo/storeGrupo',{
                'codgrupo':this.codgrupo,
                'nomgrupo':this.nomgrupo.toUpperCase(),
                'vida':this.vida,
                'idcuentaconta':this.idcuentaconta,
                'idcuentadepre':this.idcuentadepre,
                'idcuentagasto':this.idcuentagasto,
            }).then(response=> {
                swal('Datos Guardados','','success');
                this.listaGrupos();
                this.modalGrupos=0;
            }); 
        },
      
        updateGrupo(){
            swal({ title:'Procesando...',text:'Un momento por favor', type:'warning',
                showCancelButton:false, showConfirmButton:false, closeOnConfirm: false,
                allowOutsideClick: false, allowEscapeKey: false, allowEnterKey: false,
                onOpen:() => { swal.showLoading() }
            });             
            axios.put('/act_grupo/updateGrupo',{
                'idgrupo': this.idgrupo,
                'nomgrupo':this.nomgrupo.toUpperCase(),
                'vida':this.vida,
                'idcuentaconta':this.idcuentaconta,
                'idcuentadepre':this.idcuentadepre,
                'idcuentagasto':this.idcuentagasto,
            }).then(response=> {
                swal('Datos Actualizados','','success');
                this.listaGrupos();
                this.modalGrupos=0;
            }); 
        },

        estadoGrupo(grupo){
            this.idgrupo=grupo.idgrupo;
            if(grupo.activo){
                swal({title:'Desactivará<br>'+grupo.nomgrupo, type:'warning',
                    html: 'No podrá acceder a la información dependiente', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Grupo',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(confirmar=>{ if(confirmar.value) this.switchGrupo(1); });
            }
            else this.switchGrupo(0);
        },

        switchGrupo(estado){
            if(estado) var titswal='Desactivado'; else var titswal='Activado';
            var url='/act_grupo/switchGrupo?idgrupo='+this.idgrupo;
            axios.put(url).then(response=>{
                swal(titswal+' correctamente','','success');
                this.listaGrupos();
            });
            
        },

        // motivos motivos motivos motivos motivos motivos motivos
        // motivos motivos motivos motivos motivos motivos motivos
        listaMotivos(){
            var url='/act_motivo/listaMotivos';
            axios.get(url).then(response=>{
                this.arrayMotivos = response.data.motivos;
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
            swal({ title:'Procesando...',text:'Un momento por favor', type:'warning',
                showCancelButton:false, showConfirmButton:false, closeOnConfirm: false,
                allowOutsideClick: false, allowEscapeKey: false, allowEnterKey: false,
                onOpen:() => { swal.showLoading() }
            });             
            axios.post('/act_motivo/storeMotivo',{
                'nommotivo':this.nommotivo
            }).then(response=>{
                swal('Datos Guardados','','success');
                this.formMotivo=0;
                this.listaMotivos();
            });
        },

        updateMotivo(){
            swal({ title:'Procesando...',text:'Un momento por favor', type:'warning',
                showCancelButton:false, showConfirmButton:false, closeOnConfirm: false,
                allowOutsideClick: false, allowEscapeKey: false, allowEnterKey: false,
                onOpen:() => { swal.showLoading() }
            });
            axios.put('/act_motivo/updateMotivo',{
                'idmotivo':this.idmotivo,
                'nommotivo':this.nommotivo
            }).then(response=>{
                swal('Datos Actualizados','','success');
                this.formMotivo=0;
                this.listaMotivos();
            });
        },

        estadoMotivo(motivo){
            this.idmotivo=motivo.idmotivo;
            if(motivo.activo){
                swal({title:'Desactivará<br>'+motivo.nommotivo, type:'warning',
                    html: 'No podrá acceder a la información dependiente', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Motivo',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(confirmar=>{ if(confirmar.value) this.switchMotivo(1); });
            }
            else this.switchMotivo(0);
        },

        switchMotivo(estado){
            if(estado) var titswal='Desactivado'; else var titswal='Activado';
            var url='/act_motivo/switchMotivo?idmotivo='+this.idmotivo;
            axios.put(url).then(response=>{
                swal(titswal+' correctamente','','success');
                this.listaMotivos();
            });
        },

        // indice ufvs indice ufvs indice ufvs indice ufvs indice ufvs 
        // indice ufvs indice ufvs indice ufvs indice ufvs indice ufvs 
        verUfv(fecha){
            var url='/act_ufv/verUfv';
            if(fecha) url+='?fecha='+fecha;
            axios.get(url).then(response=>{
                this.ufvfecha=response.data.ufvfecha.valor;
                this.fecha=response.data.fecha;
            });
        },

            datasArray(data) {
                this.parse_csv=[]; this.nombre_archivo=[];
                if(data!=null){
                    this.nombre_archivo=data.names;
                    this.parse_csv=data.values; 
               }
            },

        procesarExcel(){ 
            //let archivo=event.target.files[0];
            swal({ title:'Guardando la planilla...',text:'Un momento por favor', type:'warning',
                showCancelButton:false, showConfirmButton:false, closeOnConfirm: false,
                allowOutsideClick: false, allowEscapeKey: false, allowEnterKey: false,
                onOpen:() => { swal.showLoading() }
            });            
            axios.post('/act_ufv/cargarExcel',{
                'tabla':this.parse_csv
            }).then(response=>{
                swal('Se ha actualizado la tabla de UFVs','Verfique visualizando la Planilla de la Gestión','success');
            });
        },

        verPlanilla(gestion){
            var url=[];
            url.push('http://'+this.ipbirt+':8080');
            url.push('/birt-viewer/frameset?__report=reportes/activos');
            url.push('/act_ufvs.rptdesign'); //archivo
            url.push('&gestion='+gestion); //idempleado
            url.push('&__format=pdf'); //formato
            reporte.viewPDF(url.join(''),'UFVs Gestión '+gestion);
        },




    },

    mounted() {
        this.jsfechas=jsfechas;
        //this.csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        this.listaGrupos();
        this.listaCuentas();
        this.listaMotivos();
        this.verUfv();
    }
}
</script>
