<template>
<main class="main">
    <div class="breadcrumb titmodulo">Activos > Grupos Contables</div>
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
            <div class="card-body">

                <div class="text-right" style="padding-bottom:10px">
                    <div class="vervigente">Ver: &nbsp;
                        <input type="radio" name="estado" id="r1" @click="listaGrupos(1)">Vigentes &nbsp;
                        <input type="radio" name="estado" id="r0" @click="listaGrupos(0)">Inactivos
                    </div>
                    <!--
                    <button class="btn btn-success btn-sm icon-printer" title="Vista de impresión" style="margin-left:10px"
                        @click="reporteGrupos(regFilial.idfilial)"></button>
                    -->
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead class="tcabecera">
                            <tr align="center">
                                <th><span class="badge badge-success" v-text="arrayGrupos.length+' items'"></span></th>
                                <th>Cód</th>
                                <th align="left">Nombre Grupo</th>
                                <th>Vida útil</th>
                                <th>Depre ciación</th>
                                <th align="left">Cuenta Contable</th>
                                <th align="left">Cuenta Depreciación</th>
                                <th align="left">Cuenta Gastos</th>
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
                                <td align="center"><span v-if="grupo.vida" v-html="grupo.vida+'<br>años'"></span> </td>
                                <td align="center"><span v-if="grupo.vida" v-text="Math.round(10000/grupo.vida)/100+'%'"></span></td>
                                <td v-text="grupo.cuentaconta"></td>
                                <td v-text="grupo.cuentadepre"></td>
                                <td v-text="grupo.cuentagasto"></td>
                            </tr>                                
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL GRUPOS MODAL GRUPOS MODAL GRUPOS MODAL GRUPOS   -->
    <!-- MODAL GRUPOS MODAL GRUPOS MODAL GRUPOS MODAL GRUPOS   -->
    <div class="modal" :class="modalGrupos?'mostrar':''">
        <div class="modal-dialog modal-primary modal-lg">
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
                            <input  type="text" class="form-control" v-model="nomgrupo"
                                name="nom" :class="{'invalido':errors.has('nom')}" v-validate="'required'">
                            <p class="txtvalidador" v-if="errors.has('nom')">Dato requerido</p>
                            <div class="tfila" style="vertical-align:top">
                                <div class="tcelda">
                                    Vida útil:
                                    <div class="input-group">
                                        <input  type="text" v-model="vida" class="form-control text-right"
                                            name="vid" :class="{'invalido':errors.has('vid')}" v-validate="'required|numeric'" 
                                            @keyup="vida?coef=Math.round(10000/vida)/100:''">
                                        <span class="input-group-text">Años</span>
                                    </div>
                                    <p class="txtvalidador" v-if="errors.has('vid')">Dato numérico requerido</p>
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
                    <button class="btn btn-primary" @click="validarGrupo()">
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
        modalGrupos:0, accion:'', ipbirt:'', jsfechas:'', 
        arrayGrupos:[], arrayCuentas:[], 
        idgrupo:'', codgrupo:'', nomgrupo:'', vida:'', coef:'',
        idcuentaconta:'', idcuentadepre:'', idcuentagasto:'',
    }},

    methods:{
        listaGrupos(activo){
            $('#r'+activo).prop('checked',true);
            var url= '/act_grupo/listaGrupos?activo='+activo;
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

        validarGrupo(){
            this.$validator.validateAll().then(result=>{
                if(!result) { swal('Datos no válidos','Revise los errores','error'); return; }
                this.accion==1?this.storeGrupo():this.updateGrupo();
            });            
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
                this.listaGrupos(1);
                this.modalGrupos=0;
            }); 
        },
      
        updateGrupo(){
            swal({ title:'Procesando...',text:'Un momento por favor', type:'warning',
                showCancelButton:false, showConfirmButton:false,
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
                this.listaGrupos(1);
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
                }).then(confirmar=>{ confirmar.value?this.switchGrupo(0):'' });
            }
            else this.switchGrupo(1);
        },

        switchGrupo(activo){
            var url='/act_grupo/switchGrupo?idgrupo='+this.idgrupo;
            axios.put(url).then(response=>{
                swal(activo?'Activado correctamente':'Desactivado correctamente','','success');
                this.listaGrupos(activo);
            });
            
        },

    },

    mounted(){
        this.listaGrupos(1);
        this.listaCuentas();
    }
}
</script>
