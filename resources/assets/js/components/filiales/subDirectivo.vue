<template>
<main>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-7">
                    <div class="tablatit titcard">
                        <div class="tcelda">Filial <span v-text="regFilial.nommunicipio"></span> - Directorio</div> 
                    </div>
                </div>
                <div class="col-md-3 text-center">
                    <div class="tablatit" style="width:100%; border:1px solid #acb4bc; border-radius:5px">
                        <div class="tcelda" style="padding:0px 15px">Mostrando: 
                            <input type="radio" name="estado" @click="listaDirectivos(regFilial.idfilial,1)" checked >Activos &nbsp;
                            <input type="radio" name="estado" @click="listaDirectivos(regFilial.idfilial,0)">Inactivos
                        </div>
                    </div>
                </div>
                <div class="col-md-2 text-right">
                    <button class="btn btn-primary" style="margin-top:0" @click="nuevoDirectivo()">Nuevo Directivo</button>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped table-sm">
                <thead class="tcabecera">
                    <tr>
                        <th><span class="badge badge-success" v-text="arrayDirectivos.length+' items'"></span></th>
                        <th>Cargo</th>
                        <th>Nombre</th>
                        <th>Fuerza</th>
                        <th>Periodo</th>
                        <th>Celular</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="directivo in arrayDirectivos" :key="directivo.iddirectivo" :class="directivo.activo?'':'txtdesactivado'">
                        <td v-if="directivo.activo" nowrap align="center">
                            <button class="btn btn-warning btn-sm icon-pencil" @click="editarDirectivo(directivo)"></button>
                            <button class="btn btn-danger btn-sm icon-trash" @click="estadoDirectivo(directivo)" title="Desactivar al Directivo"></button>
                        </td>
                        <td v-else align="center">
                            <button class="btn btn-warning btn-sm icon-action-redo" @click="estadoDirectivo(directivo)"  title="Reactivar al Directivo"></button>
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

    <div class="modal" :class="modalDirectivo?'mostrar':''" >
        <div class="modal-dialog modal-primary">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title"><span v-text="accion==1?'Nuevo':'Modificar'"></span> Directivo</h4>
                    <button class="close" @click="modalDirectivo=0">x</button>
                </div>
                <div class="modal-body">
                    Cargo:
                    <select class="form-control" v-model="idcargo">
                        <option v-for="cargo in arrayCargos" :key="cargo.id"
                            :value="cargo.idcargo" v-text="cargo.nomcargo"></option>
                    </select>
                    Socio:
                    <autocomplete @encontrado="verIDsocio($event)"></autocomplete>
                    <div class="row">
                        <div class="col-md-4">Celular:
                            <input type="text" class="form-control" v-model="telcelular">
                        </div>
                        <div class="col-md-4">Fecha Inicio:
                            <input type="date" class="form-control" v-model="fechaini">
                        </div>
                        <div class="col-md-4">Fecha Fin:
                            <input type="date" class="form-control" v-model="fechafin">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="modalDirectivo=0">Cerrar</button>
                    <button class="btn btn-primary" @click="accion==1?storeDirectivo():updateDirectivo()">
                        Guardar <span v-if="accion==2">Modificaciones</span></button>
                </div>
            </div>
        </div>
    </div>
</main>    
</template>

<script>
import * as jsfechas from '../../fechas.js';

export default {
    props:['regFilial'],

    data(){ return{
        modalDirectivo:0, accion:1, jsfechas:'', completo:'', activo:'',
        arrayFiliales:[], arrayDirectivos:[], arrayCargos:[], 
        iddirectivo:'', idcargo:'', idsocio:'', fechaini:'', fechafin:'', telcelular:'',
    }},

    methods:{
        verIDsocio(idsocio){ this.idsocio=idsocio; },

        listaDirectivos(idfilial,activo){
            var url='/fil_directivo/listaDirectivos?idfilial='+idfilial+'&activo='+activo;
            axios.get(url).then(response=>{
                this.arrayDirectivos=response.data.directivos;
            });
        },

        listaCargos(){
            var url='/fil_cargo/listaCargos?activo=1';
            axios.get(url).then(response=>{
                this.arrayCargos=response.data.cargos;
            });
        },

        nuevoDirectivo(){
            this.modalDirectivo=1;
            this.accion=1;
            this.completo=0;
            this.listaCargos();
            this.idcargo='';
            this.idsocio='';
            this.fechaini='';
            this.fechafin='';
        },

        editarDirectivo(directivo){
            this.modalDirectivo=1;
            this.accion=2;
            this.completo=1;
            this.listaCargos();
            this.iddirectivo=directivo.iddirectivo;
            this.idcargo=directivo.idcargo;
            this.idsocio=directivo.idsocio;
            this.fechaini=directivo.fechaini;
            this.fechafin=directivo.fechafin; 
        }, 

        valDirectivo(){
            this.completo=0;
            if((this.idcargo)&&(this.idsocio)&&(this.fechaini)&&(this.fechafin))
                this.completo=1;
        },

        storeDirectivo(){
            swal({ title:'Procesando...',text:'Un momento por favor', type:'warning',
                showCancelButton:false, showConfirmButton:false, //closeOnConfirm: false,
                allowOutsideClick: false, allowEscapeKey: false, allowEnterKey: false,
                onOpen:() => { swal.showLoading() }
            });
            axios.post('fil_directivo/storeDirectivo',{
                'idcargo':this.idcargo,
                'idfilial':this.regFilial.idfilial,
                'idsocio':this.idsocio,
                'fechaini':this.fechaini,
                'fechafin':this.fechafin
            }).then(response=>{
                swal('Creado correctamente','','success');
                this.modalDirectivo=0;
                this.listaDirectivos(this.regFilial.idfilial);
            });
        },

        updateDirectivo(){
            axios.put('/fil_directivo/updateDirectivo',{
                'iddirectivo':this.iddirectivo,
                'idcargo':this.idcargo,
                'idsocio':this.idsocio,
                'fechaini':this.fechaini,
                'fechafin':this.fechafin         
            }).then(response=>{
                swal('Datos actualizados','','success');
                this.modalDirectivo=0;
                this.listaDirectivos(this.regFilial.idfilial);
            });
        },

        estadoDirectivo(directivo){
            this.iddirectivo=directivo.iddirectivo;
            if(directivo.activo){
                swal({title:'Desactivará al directivo<br>'+directivo.nombre+' '+directivo.apaterno, type:'warning',
                    html: 'No podrá acceder a la información dependiente', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Directivo',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(confirmar=>{
                    if(confirmar.value) this.switchDirectivo(1);
                });
            }
            else this.switchDirectivo(0);
        },

        switchDirectivo(activo){
            if(activo) var titswal='Desactivado'; else var titswal='Activado';
            var url='/fil_directivo/switchDirectivo?iddirectivo='+this.iddirectivo;
            axios.put(url).then(response=>{
                swal(titswal+' correctamente','','success');
                this.listaDirectivos(this.regFilial.idfilial);
            });
        }       

    },

    mounted(){
        this.jsfechas=jsfechas;
        this.listaDirectivos(this.regFilial.idfilial,1);
    },
}
</script>

