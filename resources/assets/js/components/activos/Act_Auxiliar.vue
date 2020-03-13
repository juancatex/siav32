<template>
<main class="main">
    <div class="breadcrumb titmodulo">Activos > Auxilliares</div>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6 titcard">
                        <div class="tablatit">
                            <div class="tcelda">Cuenta: <span v-text="regGrupo.nomgrupo"></span></div>
                        </div>
                    </div>
                    <div class="col-md-6 text-right ">
                        <div class="input-group-append" style="display:inline">
                            <!-- <button class="btn btn-primary dropdown-toggle" style="margin-top:0px" 
                                data-toggle="dropdown" aria-expanded="false">
                                Otra cuenta... <span class="caret"></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                                <a href="#" class="dropdown-item" v-for="grupo in arrayGrupos" 
                                :key="grupo.id" v-text="grupo.nomgrupo" 
                                @click="listaAuxiliares(grupo.idgrupo,1)"></a>
                            </div> -->

                            <div class="tfila">
                                <div class="tcelda titcampo" style="vertical-align:top">Grupo:</div>
                                <select class="form-control"  v-model="idgrupo" 
                                    @change="listaAuxiliares(idgrupo,1)">
                                    <option v-for="grupo in arrayGrupos" :key="grupo.idgrupo"
                                        v-text="grupo.nomgrupo" :value="grupo.idgrupo"></option>
                                </select>
                            </div>


                        </div>
                        <button class="btn btn-primary" style="margin-top:0px" 
                            @click="nuevoAuxiliar()">Nuevo Auxiliar</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="text-right" style="padding-bottom:10px">
                    <div class="vervigente">Ver: &nbsp;
                        <input type="radio" name="estado" id="r1" @click="listaAuxiliares(idgrupo,1)">Vigentes &nbsp;
                        <input type="radio" name="estado" id="r0" @click="listaAuxiliares(idgrupo,0)">Inactivos
                    </div>
                    <button class="btn btn-success btn-sm icon-printer" title="Vista de impresión" style="margin-left:10px"
                        @click="reporteAuxiliares(idgrupo)"></button>
                </div>                
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead class="tcabecera">
                            <tr align="center">
                                <th><span class="badge badge-success" v-text="arrayAuxiliares.length+' items'"></span></th>
                                <th>Cód. Auxiliar.</th>
                                <th align="left">Descripción del Auxiliar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="auxiliar in arrayAuxiliares" :key="auxiliar.idauxiliar" :class="auxiliar.activo?'':'txtdesactivado'">
                                <td v-if="auxiliar.activo" align="center">
                                    <button class="btn btn-warning btn-sm icon-pencil" title="Editar Categoría"
                                        @click="editarAuxiliar(auxiliar)"></button>
                                    <button class="btn btn-danger btn-sm icon-trash" title="Desactivar Categoría"
                                        @click="estadoAuxiliar(auxiliar)"></button>
                                </td>
                                <td v-else align="center">
                                    <button class="btn btn-warning btn-sm icon-action-redo" title="Reactivar Categoría"
                                        @click="estadoAuxiliar(auxiliar)"></button>
                                </td>
                                <td v-text="auxiliar.codauxiliar" align="center"></td>
                                <td v-text="auxiliar.nomauxiliar"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL AUXILIAR MODAL AUXILIAR MODAL AUXILIAR MODAL AUXILIAR MODAL AUXILIAR -->
    <!-- MODAL AUXILIAR MODAL AUXILIAR MODAL AUXILIAR MODAL AUXILIAR MODAL AUXILIAR -->
    <div class="modal" :class="modalAuxiliar?'mostrar':''">
        <div class="modal-dialog modal-primary modal-sm">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title"><span v-text="accion==1?'Nuevo':'Modificar'"></span> Auxiliar</h4>
                    <button class="close" @click="modalAuxiliar=0">x</button>
                </div>
                <div class="modal-body">
                    <h4 class="titsubrayado">Cuenta:<br><span v-text="regGrupo.nomcuenta"></span></h4>
                    <div class="tfila">
                        <div class="tcelda taltura nowrap">Código cuenta auxiliar:</div>
                        <div class="tcelda tinput">
                            <input type="text" class="form-control text-center txtnegrita" readonly v-model="codauxiliar">
                        </div>
                    </div>                                
                    Descripción: <span class="txtasterisco"></span>
                    <input type="text" class="form-control" v-model="nomauxiliar"
                        name="nom" :class="{'invalido':errors.has('nom')}" v-validate="'required'">
                    <p class="txtvalidador" v-if="errors.has('nom')">Dato requerido</p>
                </div>
                <div class="modal-footer">                                                            
                    <button class="btn btn-secondary" @click="modalAuxiliar=0">Cancelar</button>
                    <button class="btn btn-primary" @click="validarAuxiliar()">
                        Guardar <span v-if="accion==2">Modificaciones</span></button>
                </div>
            </div>
        </div>
    </div>

</main>
</template>

<script>
import * as reporte from '../../functions';

export default {
    data(){ return {
        modalAuxiliar:0, accion:1, ipbirt:'',
        arrayGrupos:[], arrayAuxiliares:[], regGrupo:[],
        idgrupo:2, codcuenta:'', idauxiliar:'', codauxiliar:'', nomauxiliar:'', 
    }},

    methods:{
        listaAuxiliares(idgrupo,activo){
            $('#r'+activo).prop('checked',true);
            if(this.arrayGrupos.length) this.verGrupo(idgrupo);
            var url='/act_auxiliar/listaAuxiliares?idgrupo='+idgrupo+'&activo='+activo;
            axios.get(url).then(response=>{
                this.arrayAuxiliares=response.data.auxiliares;
                this.ipbirt=response.data.ipbirt;
            });
        },

        listaGrupos(){
            var url='/act_grupo/listaGrupos?activo=1';
            axios.get(url).then(response=>{
                this.arrayGrupos=response.data.grupos;
                this.verGrupo(this.idgrupo);
            });
        },

        verGrupo(idgrupo){
            for(var i=0; i<this.arrayGrupos.length; i++)
                if(this.arrayGrupos[i].idgrupo==idgrupo)
                {   this.regGrupo=this.arrayGrupos[i]; return; }
        },

        generarCodigo(){
            var tam=this.arrayAuxiliares.length-1;
            this.codauxiliar=0;
            if(tam>=0) this.codauxiliar=this.arrayAuxiliares[tam].codauxiliar;
            this.codauxiliar++;
            if(this.codauxiliar<10) this.codauxiliar='0'+this.codauxiliar;
        },

        nuevoAuxiliar(){
            this.modalAuxiliar=1;
            this.accion=1;
            this.generarCodigo();
            this.idgrupo=this.regGrupo.idgrupo;
            this.nomauxiliar='';
            this.$validator.reset();
        },

        editarAuxiliar(auxiliar){
            window.scroll({top:0,left:0,behavior:'smooth'});
            this.modalAuxiliar=1;
            this.accion=2;
            this.idauxiliar=auxiliar.idauxiliar;
            this.codauxiliar=auxiliar.codauxiliar;
            this.idgrupo=auxiliar.idgrupo;
            this.nomauxiliar=auxiliar.nomauxiliar;
        },

        validarAuxiliar(){
            this.$validator.validateAll().then(result=>{
                if(!result){ swal('Datos inválidos','Revise los errores','error'); return; }
                this.accion==1?this.storeAuxiliar():this.updateAuxiliar();
            });
        },

        storeAuxiliar(){
            swal({ title:'Procesando...',text:'Un momento por favor', type:'warning',
                showCancelButton:false, showConfirmButton:false, 
                allowOutsideClick: false, allowEscapeKey: false, allowEnterKey: false,
                onOpen:() => { swal.showLoading() }
            });
            axios.post('/act_auxiliar/storeAuxiliar',{
                'idgrupo':this.idgrupo,
                'codauxiliar':this.codauxiliar,
                'nomauxiliar':this.nomauxiliar.toUpperCase()
            }).then(response=>{
                swal('Adicionado correctamente','','success');
                this.modalAuxiliar=0;
                this.listaAuxiliares(this.idgrupo,1);
            });
        },

        updateAuxiliar(){
            swal({ title:'Procesando...',text:'Un momento por favor', type:'warning',
                showCancelButton:false, showConfirmButton:false, closeOnConfirm: false,
                allowOutsideClick: false, allowEscapeKey: false, allowEnterKey: false,
                onOpen:() =>  swal.showLoading() 
            });
            axios.put('/act_auxiliar/updateAuxiliar',{
                'idauxiliar':this.idauxiliar,
                'nomauxiliar':this.nomauxiliar.toUpperCase()
            }).then(response=>{
                swal('Datos actualizados','','success');
                this.modalAuxiliar=0;
                this.listaAuxiliares(this.idgrupo,1);
            });
        },

        estadoAuxiliar(auxiliar){
            this.idauxiliar=auxiliar.idauxiliar;
            if(auxiliar.activo){
                swal({  title:'Desactivará<br>'+auxiliar.nomauxiliar, type: 'warning', 
                    html: 'No podrá acceder a la información dependiente', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Auxiliar',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(confirmar=>{ confirmar.value?this.switchAuxiliar(0):'' });
            }
            else this.switchAuxiliar(1);
        },

        switchAuxiliar(activo){
            var url='/act_auxiliar/switchAuxiliar?idauxiliar='+this.idauxiliar;
            axios.put(url).then(response=>{
                swal(activo?'Activado correctamente':'Desactivado correctamente','','success');
                this.listaAuxiliares(this.idgrupo,activo);
            });
        },

        reporteAuxiliares(idgrupo){
            var url=[];
            url.push('http://'+this.ipbirt+':8080');
            url.push('/birt-viewer/frameset?__report=reportes/activos');
            url.push('/act_auxiliares.rptdesign'); 
            url.push('&__format=pdf'); 
            url.push('&idgrupo='+idgrupo); 
            reporte.viewPDF(url.join(''),'Cuentas Auxiliares');
        },
    },

    mounted() {
        this.listaGrupos();
        this.listaAuxiliares(this.idgrupo,1);
    },

}

</script>

