<template>
    <main class="main">    
        <div class="modal fade " tabindex="-1"  role="dialog"  style="z-index: 1600;" aria-hidden="false" id="modal_traspaso" >
        <div class="modal-dialog modal-primary">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title">Lista de Disponibles</h4>
                    <button class="close" @click="cerrarmodaleve('modal_traspaso')">x</button>
                </div>
                <div class="modal-body" style="overflow-y: scroll;max-height: 400px;">          
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Traspaso 
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Ambiente</th>
                                    <th>Tipo</th>                                    
                                    <th>Cap. Camas</th>
                                    <th>Ocupados</th>
                                    <th>Libres</th>
                                    <th>Elegir</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="libres in arrayLibres" :key="libres.id">                                                
                                    <td v-text="libres.codambiente"></td>
                                    <td v-text="libres.tipo"></td>
                                    <td align="center" v-text="libres.capacidad"></td>
                                    <td align="center" v-text="libres.ocupados"></td>
                                    <td align="center" v-text="libres.disponibles"></td>
                                    <td><input type="radio" v-model='traspaso' :value=libres.idambiente></td>
                                </tr>                                
                            </tbody>
                        </table>                
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="cerrarmodaleve('modal_traspaso')">Cerrar</button>
                    <button class="btn btn-primary" @click="confirmaTraspaso(traspaso, asig)"> Guardar </button>
                </div>
            </div>
        </div>
    </div>



    </main>
</template>
<script>        
    import * as repo from '../../functions.js';
    export default {
        data (){
            return {
                nombre : '',
                arrayLibres : [],
                tipoAccion : 0,
                errorEscalafon : 0,
                errorMostrarMsjEscalafon : [],
                offset : 10,                
                buscar : '',
                asig:'',
                traspaso: '',
            }
        },
       
        computed: {

        },

        formOptions: {

        },  
        
        methods : {
            
            cerrarmodaleve(id){ 
                this.modaltraspaso.closeModal(id);
                this.$emit('cerrarvue');
            }, 
                 
            confirmaTraspaso(newidambiente, idasignacion) { console.log(newidambiente, idasignacion);
                let me=this;
                var url='/ser_asignacion/confirmaTraspaso';
                axios.put(url,{
                    'newidambiente': newidambiente,
                    'idasignacion':idasignacion,                
                }).then(function(response){
                    swal({title:'Registro correcto',
                        html:'Traspaso Realizado.<br>Se realizo el Traspaso.',
                        type:'success'});                                
                });
                me.cerrarmodaleve('modal_traspaso');
            },

            showVue(asignacion) {
                this.modaltraspaso=new _pl.Modals();
                this.modaltraspaso.addModal('modal_traspaso'); 
                this.modaltraspaso.openModal('modal_traspaso'); 
                let me = this;
                url='/ser_asignacion/traspasoSocio?idambiente='+asignacion.idambiente+'&camas='+asignacion.ocupantes;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayLibres = respuesta.libres;
                    me.asig =  asignacion.idasignacion;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        },
            
        mounted() {
            
        }
        
    }
</script>
