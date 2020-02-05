<template>
            <main > 
                <div class="modal fade " tabindex="-1"  role="dialog"  style="z-index: 1600;" aria-hidden="false" id="primarymodal" >
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content animated fadeIn"> 
                                <div class="modal-header"> 
                                    <h4 class="modal-title" v-text="tituloModalCuentasocio"></h4>
                                    <button type="button" class="close" aria-hidden="true" aria-label="Close" @click="cerrarmodaleve('primarymodal')"><span aria-hidden="true">×</span></button>
                                </div> 

                                <div class="modal-body">
                                <div class="col-md-12">
                                    <div class="col-md-12">
                                    <label><h5>Numeros de Cuenta Socio: {{ nombreSC| capitalize}} {{ apaternoSC|capitalize }}  {{ amaternoSC|capitalize }} </h5></label>
                                    
                                    </div>

                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Editar</th>
                                                <th>Banco</th>                     
                                                <th>Numero de Cuenta</th>
                                                <th>Estado</th>                                                                                                       
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="cuentasocio in arrayCuentasocio" :key="cuentasocio.idcuentasocio">
                                                <td>
                                                    
                                                    <template v-if="cuentasocio.activo">
                                                         <button type="button" @click="abrirModalCuentasocio('actualizar',cuentasocio)" class="btn btn-warning btn-sm">
                                                         <i class="icon-pencil"></i>
                                                         </button>&nbsp;
                                                        <button type="button" class="btn btn-danger btn-sm" @click="desactivarCuentasocio(cuentasocio.idcuentasocio,cuentasocio.idsocio)">
                                                            <i class="icon-trash"></i>
                                                        </button>
                                                    </template>
                                                    <template v-else>
                                                         <button type="button" class="btn btn-danger btn-sm" disabled>
                                                         <i class="icon-pencil"></i>
                                                         </button>&nbsp;
                                                        <button type="button" class="btn btn-info btn-sm" @click="activarCuentasocio(cuentasocio.idcuentasocio,cuentasocio.idsocio)">
                                                            <i class="icon-check"></i>
                                                        </button>
                                                    </template>
                                                                                        
                                                </td>
                                                <td v-text="cuentasocio.nomentidadbancaria"></td>
                                                <td v-text="cuentasocio.numcuentasocio"></td>
                                                <td>
                                                    <div v-if="cuentasocio.activo">
                                                        <span class="badge badge-success">Activo</span>
                                                    </div>
                                                    <div v-else>
                                                        <span class="badge badge-danger">Desactivado</span>
                                                    </div>
                                                    
                                                </td>
                                                
                                            </tr>                                
                                        </tbody>
                                    </table>




                                        <div class="form-group row">
                                            <label class="col-md-3 form-control-label" for="text-input">Banco</label>
                                            <div class="col-md-9">
                                                <select  :class="{'form-control': true, 'is-invalid selecterror': errors.has('Entidad Bancaria')}" 
                                                        v-validate.initial="'required'" 
                                                        name="Entidad Bancaria" required
                                                        v-model="identidadbancaria">
                                                   <option selected="selected" value="" disabled >...Seleccione...</option>
                                                    <option v-for="entidadbancaria in arrayEntidadbancaria" :key="entidadbancaria.identidadbancaria" :value="entidadbancaria.identidadbancaria" v-text="entidadbancaria.nomentidadbancaria"></option>
                                                </select> 
 
                                              <span class="text-error">{{ errors.first('Entidad Bancaria') }}</span>





                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 form-control-label" for="text-input">Numero de Cuenta</label>
                                            <div class="col-md-9">
                                                <input 
                                                    v-validate.initial="'required'"
                                                        type="text"                                                 
                                                        v-model="numcuentasocio" 
                                                        class="form-control formu-entrada" 
                                                        placeholder="Numero de Cuenta"
                                                        name="Numero de cuenta"
                                                        autofocus>  
                                            <span class="text-error">{{ errors.first('Numero de cuenta')}}</span>                                     
                                            
                                           </div>
                                        </div> 
                                     </div>   
                                </div>  

                                <div class="modal-footer">  
                                <button type="button" class="btn btn-secondary" @click="cerrarmodaleve('primarymodal')">Volver</button>  
                            
                            <button :disabled = "errors.any()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarCuentasocio()">Guardar</button>
                            <button :disabled = "errors.any()" type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarCuentasocio()">Actualizar</button>
                                    
                                </div>    
                        </div>
                    </div>
                </div>
  
      
        </main>
</template>

<script>

    import Vue from 'vue';
    import VeeValidate from 'vee-validate';  
    const VueValidationEs = require('vee-validate/dist/locale/es');
        Vue.use(VeeValidate, 
        {
            locale: 'es',
            dictionary: 
            {
                es: VueValidationEs
            }
        });

    Vue.use(VeeValidate);

    export default {
      
        data (){
            return {
               classModal2:null,
               nombreSC:'',
               apaternoSC:'',
               amaternoSC:'',
               socio_idSC:0,
               arrayCuentasocio:[],
               arrayEntidadbancaria:[], 
               identidadbancaria:'',
               nomentidadbancaria:'',
               numcuentasocio:'',
               tipoAccion : 0,
               tituloModalCuentasocio:'',
               cuentasocio_id:0,
            }
        }, 
        computed:{  
            
        },
        methods : { 
            cerrarmodaleve(id){ 
                this.classModal2.closeModal(id);
                this.$emit('cerrarvue');
            },
             listarCuentasocio (page,idsocio){ 
                let me=this;
                var url= '/con_cuentasocio?page=' + page + '&idsocio='+ idsocio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data; ;
                    me.arrayCuentasocio = respuesta.cuentasocios.data;  
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectEntidadbancaria(){
                let me=this;
                var url= '/con_entidadbancaria/selectEntidadbancaria';
                axios.get(url).then(function (response) { 
                    var respuesta= response.data;
                    me.arrayEntidadbancaria = respuesta.entidadbancarias;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
             abrirModalCuentasocio(accion, data=[]){
             this.classModal2.openModal('primarymodal'); 
                 
                        switch(accion){
                            case 'registrar':
                            {   this.tituloModalCuentasocio = 'Registro de Cuenta de Socio';
                                this.socio_idSC=data['idsocio'];
                                this.listarCuentasocio(1,this.socio_idSC);
                                this.nombreSC=data['nombre'];
                                this.apaternoSC=data['apaterno'];
                                this.amaternoSC=data['amaterno'];   
                                this.identidadbancaria='';
                                this.nomentidadbancaria='';
                                this.numcuentasocio='';
                                this.tipoAccion=1; 
                                break;
                            }
                            case 'actualizar':
                            {  
                                this.tituloModalCuentasocio='Actualizar Cuenta de Socio';
                                this.tipoAccion=2;
                                this.cuentasocio_id=data['idcuentasocio']; 
                                this.identidadbancaria=data['identidadbancaria'];
                                this.numcuentasocio=data['numcuentasocio'];         
                                break;
                            }
                        }
                    
               
                this.selectEntidadbancaria();
            },  desactivarCuentasocio(idcuentasocio,idsocio){
               swal({
                title: 'Esta seguro de desactivar esta Cuenta?',
                type: 'warning',
                showCancelButton: true, 
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#4dbd74',
                cancelButtonColor: '#f86c6b', 
                buttonsStyling: true,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/con_cuentasocio/desactivar',{
                        'idcuentasocio': idcuentasocio
                    }).then(function (response) {
                        me.listarCuentasocio(1,idsocio);
                        swal(
                        'Desactivado!',
                        'El registro ha sido desactivado con éxito.',
                        'success'
                        )
                        $(".swal2-modal").css('z-index', '2000'); 
                $(".swal2-container").css('z-index', '2000');
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                });
                $(".swal2-modal").css('z-index', '2000'); 
                $(".swal2-container").css('z-index', '2000');
            },

            activarCuentasocio(idcuentasocio,idsocio){
               swal({
                title: 'Esta seguro de activar esta Cuenta?',
                type: 'warning',
                showCancelButton: true, 
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#4dbd74',
                cancelButtonColor: '#f86c6b', 
                buttonsStyling: true,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/con_cuentasocio/activar',{
                        'idcuentasocio': idcuentasocio
                    }).then(function (response) {
                        me.listarCuentasocio(1,idsocio);
                        swal(
                        'Activado!',
                        'El registro ha sido activado con éxito.',
                        'success'
                        )
                        $(".swal2-modal").css('z-index', '2000'); 
                        $(".swal2-container").css('z-index', '2000');
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                });
                $(".swal2-modal").css('z-index', '2000'); 
                $(".swal2-container").css('z-index', '2000');
            },
             registrarCuentasocio(){ 
                let me = this;
                axios.post('/con_cuentasocio/registrar',{
                    'idsocio':this.socio_idSC,                         
                    'identidadbancaria': this.identidadbancaria,
                    'numcuentasocio': this.numcuentasocio,
                    
                }).then(function (response) { 
                    me.listarCuentasocio(1,me.socio_idSC);
                    me.identidadbancaria='';
                    me.numcuentasocio='';
                }).catch(function (error) {
                    console.log(error);
                });
            },
            showVue(id){ 
                this.classModal2=new _pl.Modals();
               this.classModal2.addModal('primarymodal'); 
                this.abrirModalCuentasocio('registrar',id); 
            },
            actualizarCuentasocio(){ 
                let me = this;

                axios.put('/con_cuentasocio/actualizar',{
                    'idcuentasocio':this.cuentasocio_id,
                    'idsocio':this.socio_idSC,
                    'identidadbancaria': this.identidadbancaria,
                    'numcuentasocio': this.numcuentasocio,
                }).then(function (response) {
                    //me.cerrarModal();
                    me.listarCuentasocio(1,me.socio_idSC);
                    me.identidadbancaria='';
                    me.numcuentasocio='';
                    me.tipoAccion=1;

                }).catch(function (error) {
                    console.log(error);
                }); 
            }
        },
        mounted() { 
         
        }
    }
</script>
<style> 
 
      
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-style: italic;
    }
    .fotosociomini{
	     display: inline-block;
        border:#efefef 1px solid;
        filter:drop-shadow(1px 0px 2px #333);
        width:40px;
    }
   
 

::-webkit-scrollbar {
  width: 6px;
  border-radius: 10px;
}
::-webkit-scrollbar-track {
  background: transparent;  
}
::-webkit-scrollbar-thumb {
  background: #2186aab0; 
  border-radius: 10px;
}
::-webkit-scrollbar-thumb:hover {
  background: #276f8a; 
}
</style>
