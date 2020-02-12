<template>
<main>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-8">
                    <div class="tablatit titcard">
                        <div class="tcelda">Filial <span v-text="regFilial.nommunicipio"></span> - Directorio</div> 
                    </div>
                </div>
                <div class="col-md-4 text-right">
                    <button class="btn btn-primary" style="margin-top:0" @click="nuevoDirectivo()">Nuevo Directivo</button>
                </div>
            </div>
        </div>
        <div class="card-body ">
            <div class="text-right" style="padding-bottom:10px">
                <div class="vervigente">Ver: &nbsp;
                    <input type="radio" name="estado" id="r1" @click="listaDirectivos(regFilial.idfilial,1)">Vigentes &nbsp;
                    <input type="radio" name="estado" id="r0" @click="listaDirectivos(regFilial.idfilial,0)">Inactivos
                </div>
                <button class="btn btn-success btn-sm icon-printer" title="Vista de Impresión" style="margin-left:10px"
                    @click="reporteDirectivos(regFilial.idfilial)"></button>
            </div>                
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead class="tcabecera">
                        <tr>
                            <th align="center"><span class="badge badge-success" v-text="arrayDirectivos.length+' items'"></span></th>
                            <th>Cargo</th>
                            <th>Nombre</th>
                            <th align="center">Fuerza</th>
                            <th align="center">Periodo</th>
                            <th align="center">Celular</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="directivo in arrayDirectivos" :key="directivo.iddirectivo" :class="directivo.activo?'':'txtdesactivado'">
                            <td v-if="directivo.activo" nowrap align="center">
                                <button class="btn btn-warning btn-sm icon-pencil" title="Editar Directivo"
                                    @click="editarDirectivo(directivo)"></button>
                                <button class="btn btn-danger btn-sm icon-trash" title="Desactivar Directivo"
                                    @click="estadoDirectivo(directivo)"></button>
                            </td>
                            <td v-else align="center">
                                <button class="btn btn-warning btn-sm icon-action-redo" title="Reactivar al Directivo"
                                    @click="estadoDirectivo(directivo)"></button>
                            </td>
                            <td v-text="directivo.nomcargo"></td>
                            <td><span v-text="directivo.nomgrado"></span> <span v-text="directivo.nomespecialidad"></span>
                                <span v-text="directivo.nombre"></span> <span v-text="directivo.apaterno"></span>
                                <span v-text="directivo.amaterno"></span>
                            </td>
                            <td v-text="directivo.nomfuerza" align="center"></td>
                            <td align="center">
                                <span v-text="directivo.fechaini?jsfechas.fechames(directivo.fechaini):''"></span> al
                                <span v-text="directivo.fechafin?jsfechas.fechames(directivo.fechafin):''"></span>
                            </td>
                            <td v-if="directivo.telcelular" v-text="directivo.telcelular" align="center"></td>
                            <td v-else align="center">
                                <span class="badge badge-danger">Registrar en</span>
                                <span class="badge badge-danger">Afiliaciones</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal" :class="modalDirectivo?'mostrar':''" >
        <div class="modal-dialog modal-primary">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title"><span v-text="accion==1?'Nuevo':'Modificar'"></span> Directivo</h4>
                    <button class="close" @click="clearSelected=0;modalDirectivo=0">x</button>
                </div>
                <div class="modal-body">
                    Cargo: <span class="txtasterisco"></span>
                    <select class="form-control" v-model="idunidad"
                        name="car" :class="{'invalido':errors.has('car')}" v-validate="'required'">
                        <option v-for="unidad in arrayUnidades" :key="unidad.id"
                            :value="unidad.idunidad" v-text="unidad.nomcargo"></option>
                    </select>
                    <p v-if="errors.has('car')" class="txtvalidador">Seleccione un Cargo</p>
                    <br>Socio: <span class="txtasterisco"></span> 
                    <!-- <autocomplete :idsocio="idsocio" edit="1" :accion="accion" @encontrado="verIDsocio($event)"></autocomplete>  -->
                    <Ajaxselect v-if="clearSelected"
                        ruta="/socio/listaSocios?cadena=" 
                        @found="loadIDsocio" @cleaning="cleanSocios"
                        resp_ruta="socios" 
                        labels="nomcompleto" 
                        placeholder="Ingrese Texto..." 
                        idtabla="idsocio" 
                        :id="idsocio" 
                        :clearable="true">
                    </Ajaxselect>
                    <br>
                    <div class="row">
                        <!--
                        <div class="col-md-4">Celular:<input type="text" class="form-control" v-model="telcelular"></div>
                        -->
                        <div class="col-md-6 col-sm-6">Fecha Inicio:
                            <input type="date" class="form-control" v-model="fechaini">
                        </div>
                        <div class="col-md-6 col-sm-6">Fecha Fin:
                            <input type="date" class="form-control" v-model="fechafin">
                        </div>
                    </div>
                    <div class="txtobligatorio text-center"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="clearSelected=0;modalDirectivo=0">Cerrar</button>
                    <button class="btn btn-primary" @click="validarDirectivo()">
                        Guardar <span v-if="accion==2">Modificaciones</span></button>
                </div>
            </div>
        </div>
    </div>
</main>    
</template>

<script>
import * as jsfechas from '../../fechas.js';
import * as reporte from '../../functions';

export default {
    props:['regFilial'],

    data(){ return{
        modalDirectivo:0, accion:1, jsfechas:'', ipbirt:'', clearSelected:1,
        arrayFiliales:[], arrayDirectivos:[], arrayUnidades:[], 
        iddirectivo:'', idunidad:'', idsocio:'', fechaini:'', fechafin:'', telcelular:'',
    }},

    methods:{
        //verIDsocio(idsocio){ this.idsocio=idsocio; },
        tiempo() { this.clearSelected=1},
        cleanSocios(){ this.idsocio=''; },
        loadIDsocio(resultado) { this.idsocio=resultado.idsocio; },

        listaDirectivos(idfilial,activo){
            $('#r'+activo).prop('checked',true);
            var url='/fil_directivo/listaDirectivos?idfilial='+idfilial+'&activo='+activo;
            axios.get(url).then(response=>{
                this.arrayDirectivos=response.data.directivos;
                this.ipbirt=response.data.ipbirt;
            });
        },

        listaUnidades(){
            var url='/fil_unidad/listaUnidades?activo=1';
            axios.get(url).then(response=>{
                this.arrayUnidades=response.data.unidades;
            });
        },

        nuevoDirectivo(){                  this.clearSelected=1;
            this.modalDirectivo=1;
            this.accion=1;
            this.listaUnidades();
            this.idunidad='';
            this.idsocio='';
            this.fechaini='';
            this.fechafin='';
            this.$validator.reset();
        },

        editarDirectivo(directivo){        this.clearSelected=0; setTimeout(this.tiempo, 200);
            this.modalDirectivo=1;
            this.accion=2;
            this.listaUnidades();
            this.iddirectivo=directivo.iddirectivo;
            this.idunidad=directivo.idunidad;
            this.idsocio=directivo.idsocio;
            this.fechaini=directivo.fechaini;
            this.fechafin=directivo.fechafin; 
        }, 

        validarDirectivo(){
            this.$validator.validateAll().then(result=>{
                if(!result) { swal('Datos inválidos','Revise los errores','error'); return; }
                this.accion==1?this.storeDirectivo():this.updateDirectivo();
            });
        },

        storeDirectivo(){
            swal({ title:'Procesando...',text:'Un momento por favor', type:'warning',
                showCancelButton:false, showConfirmButton:false, 
                allowOutsideClick: false, allowEscapeKey: false, allowEnterKey: false,
                onOpen:() => { swal.showLoading() }
            });
            axios.post('fil_directivo/storeDirectivo',{
                'idunidad':this.idunidad,
                'idfilial':this.regFilial.idfilial,
                'idsocio':this.idsocio,
                'fechaini':this.fechaini,
                'fechafin':this.fechafin
            }).then(response=>{
                swal('Creado correctamente','','success');
                this.modalDirectivo=0;
                this.listaDirectivos(this.regFilial.idfilial,1);
            });
        },

        updateDirectivo(){
            axios.put('/fil_directivo/updateDirectivo',{
                'iddirectivo':this.iddirectivo,
                'idunidad':this.idunidad,
                'idsocio':this.idsocio,
                'fechaini':this.fechaini,
                'fechafin':this.fechafin         
            }).then(response=>{
                swal('Datos actualizados','','success');
                this.modalDirectivo=0;
                this.listaDirectivos(this.regFilial.idfilial,1);
            });
        },

        estadoDirectivo(directivo){
            this.iddirectivo=directivo.iddirectivo;
            if(directivo.activo){
                swal({title:'Desactivará al directivo<br>'+directivo.nombre+' '+directivo.apaterno, type:'warning',
                    html: 'No podrá acceder a la información dependiente', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Directivo',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(confirmar=>{confirmar.value?this.switchDirectivo(0):''});
            }
            else this.switchDirectivo(1);
        },

        switchDirectivo(activo){
            var url='/fil_directivo/switchDirectivo?iddirectivo='+this.iddirectivo;
            axios.put(url).then(response=>{
                swal(activo?'Activado correctamente':'Desactivado correctamente','','success');
                this.listaDirectivos(this.regFilial.idfilial,activo);
            });
        },

        reporteDirectivos(idfilial){
            var url=[];
            url.push('http://'+this.ipbirt+':8080');
            url.push('/birt-viewer/frameset?__report=reportes/filiales');
            url.push('/fil_directivos.rptdesign'); //archivo
            url.push('&idfilial='+idfilial); 
            url.push('&__format=pdf'); //formato
            reporte.viewPDF(url.join(''),'Directivos');            
        }

    },

    mounted(){
        this.jsfechas=jsfechas;
        this.listaDirectivos(this.regFilial.idfilial,1);
    },
}
</script>

