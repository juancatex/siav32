<template>
            <main > 
                <div class="modal fade " tabindex="-1"  role="dialog"  style="z-index: 1600;" aria-hidden="false" id="modal_cobranza_manual" >
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content animated fadeIn"> 
                                <div class="modal-header"> 
                                    <h4 class="modal-title" v-text="tituloModalCuentasocio"></h4>
                                    <button type="button" class="close" aria-hidden="true" aria-label="Close" @click="cerrarmodaleve('modal_cobranza_manual')"><span aria-hidden="true">×</span></button>
                                </div> 

                                <div class="modal-body">
                                    <div class="col-md-12 mb-4">
                                       <label style="width: 100%; text-align: center;"><h3>Detalle de la cobranza</h3></label> 
                                    </div>

                                <div class="form-group row ">
                                        <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label"
                                            for="text-input">Socio : </label>
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                <input   style="background-color: white;" type="text" class="form-control"
                                                    :value="dataprestamo!=null?(dataprestamo.nomgrado+' '+dataprestamo.nombre+' '+dataprestamo.apaterno+' '+dataprestamo.amaterno):''" 
                                                    name='socio' readonly>
                                            </div>  
                                        </div> 
                                </div>
                                <div class="form-group row ">
                                        <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label"
                                            for="text-input">Numero de papeleta : </label>
                                         <div class="col-md-3">
                                            <div class="input-group">
                                                <input   style="background-color: white;" type="text" class="form-control"
                                                    :value="dataprestamo!=null?dataprestamo.numpapeleta:''" 
                                                    name='numero de papeleta' readonly>
                                            </div>  
                                        </div>
                                        <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label"
                                            for="text-input">Numero de prestamo : </label>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <input   style="background-color: white;" type="text" class="form-control"
                                                    :value="dataprestamo!=null?dataprestamo.no_prestamo:''" 
                                                    name='numero de prestamo' readonly>
                                            </div>  
                                        </div> 
                                </div>
                                <div class="form-group row ">
                                        <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label"
                                            for="text-input">Fecha de registro : </label>
                                         <div class="col-md-3">
                                            <div class="input-group">
                                                <input   style="background-color: white;" type="text" class="form-control"
                                                    :value="dataprestamo!=null?dataprestamo.fecharegistro:''" 
                                                    name='fecha de registro' readonly>
                                            </div>  
                                        </div>
                                        <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label"
                                            for="text-input">Fecha de desembolso : </label>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <input   style="background-color: white;" type="text" class="form-control"
                                                    :value="dataprestamo!=null?dataprestamo.fechardesembolso:''" 
                                                    name='fecha de desembolso' readonly>
                                            </div>  
                                        </div> 
                                </div>
                                <div class="form-group row ">
                                        <label style="text-align: right; align-items: center;font-weight: bold; " class="col-md-3 form-control-label"
                                            for="text-input">Numero de Documento : </label>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <input v-validate.initial="'required'" style="background-color: white;" type="text"
                                                    v-model="numdoc"
                                                    :class="{'form-control': true, 'is-invalid': errors.has('numero de documento')}"
                                                    placeholder="Numero de documento" name='numero de documento'>
                                            </div> <span class="text-error">{{ errors.first('numero de documento')}}</span>
                                        </div>

                                        <label style="text-align: right; align-items: center;font-weight: bold; " class="col-md-3 form-control-label"
                                            for="text-input">Fecha del documento : </label>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <input v-validate.initial="'required'" style="background-color: white;" type="date"
                                                    v-model="fechadoc"
                                                    :class="{'form-control': true, 'is-invalid': errors.has('fecha documento')}"
                                                    placeholder="Fecha documento" name='fecha documento'>
                                            </div> <span class="text-error">{{ errors.first('fecha documento')}}</span>
                                        </div>
                                </div>
                       
                       <div class="form-group row ">
                                        <label class="col-md-6 form-control-label"
                                            for="text-input"></label> 

                                        <label style="text-align: right; align-items: center;font-weight: bold;font-size: x-large;" class="col-md-3 form-control-label"
                                            for="text-input">Importe : </label>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <input v-validate.initial="'required|min_value:'+(preview!=null?preview.data.db.importe:1)+'|decimal:2'" style="background-color: white;" type="number"
                                                    v-model="importe"
                                                    :class="{'form-control': true, 'is-invalid': errors.has('importe')}"
                                                    placeholder="Importe" name='importe'>
                                            </div> <span class="text-error">{{ errors.first('importe')}}</span>
                                        </div>
                                </div>
                       
                        <div class="form-group rowModal">
                            <label style="text-align: right; align-items: center;font-weight: bold;font-size: x-large;" class="col-md-3 form-control-label"
                                for="text-input">Detalle : </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <textarea v-validate.initial="'required'"
                                        :class="{'form-control':true,'is-invalid': errors.has('detalle')}"
                                        name="detalle" class="col-md-12" rows="4" v-model="obser"
                                        placeholder="Detalle"></textarea>
                                </div>
                                <span class="text-error">{{ errors.first('detalle') }}</span>
                            </div>
                        </div>

                                <div class="col-md-12">
                                    <table class="table table-bordered table-striped table-sm" v-if="preview!=null">
                                    <thead>
                                        <tr>
                                            <th class="thcell">dias</th> 
                                            <th class="thcell">Amortizacion</th>
                                            <th class="thcell">Interes</th>
                                            <th class="thcell">Diferido</th>
                                            <th class="thcell">Seguro</th>
                                            <th class="thcell">Cargos</th> 
                                            <th class="thcell">Total</th> 
                                            <th class="thcell">Total Bs.</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr valign="middle">
                                            <td class="tdcell" style="text-align: center;vertical-align: middle;"> 
                                                {{preview.data.db.di}}
                                            </td> 
                                            <td class="tdcell" style="text-align: center;vertical-align: middle;"> 
                                                {{preview.data.db.am}}
                                            </td> 
                                            <td class="tdcell" style="text-align: center;vertical-align: middle;"> 
                                                {{preview.data.db.in}}
                                            </td> 
                                            <td class="tdcell" style="text-align: center;vertical-align: middle;"> 
                                                {{preview.data.db.indi}}
                                            </td> 
                                            <td class="tdcell" style="text-align: center;vertical-align: middle;"> 
                                                {{preview.data.db.seg}}
                                            </td> 
                                            <td class="tdcell" style="text-align: center;vertical-align: middle;"> 
                                                {{preview.data.db.car}}
                                            </td> 
                                            <td class="tdcell" style="text-align: center;vertical-align: middle;"> 
                                                {{preview.data.db.cut}}
                                            </td> 
                                            <td class="tdcell" style="text-align: center;vertical-align: middle;"> 
                                                {{preview.data.db.importe}}
                                            </td> 
                                        </tr>
                                    </tbody>
                                </table>  
                                </div>  

                                </div>  

                                <div class="modal-footer"> 
                                     <div class="w-100 row " > 
                                                    <label style="text-align: right; align-items: center;" class="col-md-5 form-control-label"
                                            for="text-input">¿ Cobrar cuotas en transito ? </label>
                                                <div class="col-md-2 my-auto"> 
                                                        <label class="switch switch-label switch-pill switch-primary" style="margin: 0 !important;display: table-cell;">
                                                    <input class="switch-input" type="checkbox" checked="" v-model="cobrarenvioascii" disabled>
                                                    <span class="switch-slider" data-checked="Si" data-unchecked="No"></span>
                                                    </label>   
                                                </div>
                                        </div>  
                                    <button type="button" class="btn btn-secondary" @click="cerrarmodaleve('modal_cobranza_manual')">Cancelar</button>  
                                    <button  v-if="!errors.any()" type="submit"   class="btn btn-primary" @click="procesar()">Procesar</button>
                                </div>    
                        </div>
                    </div>
                </div>
  
      
        </main>
</template>

<script> 
     import VueNumeric from 'vue-numeric';
    export default { 
        data (){
            return {
                tituloModalCuentasocio:"Cobranza Manual" ,
                dataprestamo:null,  
                obser:'', 
                fechadoc:'', 
                numdoc:'', 
                importe:0, 
                cobrarenvioascii: true,
                preview:null, 
                idmodulomain:null 
                 }
        }, 
        computed:{  
            
        },
        methods : { 
            cerrarmodaleve(id,a=-1){ 
                this.modalcobranza.closeModal(id);
                this.$emit('cerrarvue',a);
            },showVue(prestamo,idmod){ 
                // console.log(prestamo);
                this.ini();
                let me=this;
                this.tituloModalCuentasocio="Cobranza "+prestamo.nomproducto; 
                this.modalcobranza=new _pl.Modals();
                this.modalcobranza.addModal('modal_cobranza_manual');  
                this.dataprestamo=prestamo;
                this.idmodulomain=idmod; 
                      swal({
                        title: "Obteniendo datos...", 
                        allowOutsideClick: () => false,
                        allowEscapeKey:() => false, 
                        onOpen: function() { 
                            swal.showLoading() ; 
                        }}).catch(error => { swal.showValidationError( 'Request failed: ${error}' )  }); 

                                axios.get('/start_cobranzamanual_total_validate?idprestamo='+me.dataprestamo.idprestamo+'&idmodulo='
                                         +me.idmodulomain+'&detalle='+me.obser+ '&numdoc='+me.numdoc+'&cobrar='+(me.cobrarenvioascii?1:0))
                                .then(function (response) {  
                                    swal.close(); 
                                    me.preview=response.data;
                                    me.modalcobranza.openModal('modal_cobranza_manual');  
                                }).catch(function (error) { 
                                    console.log("error:",error.response.data.message);
                                swal("¡ocurrio un problema al cobrar el prestamos!",error.response.data.message, "error");
                                });
            },procesar(){
                this.modalcobranza.closeModal('modal_cobranza_manual'); 
                let me=this;
                swal({
                        title: "Procesando datos...", 
                        allowOutsideClick: () => false,
                        allowEscapeKey:() => false, 
                        onOpen: function() { 
                            swal.showLoading() ; 
                        }}).catch(error => { swal.showValidationError( 'Request failed: ${error}' )  }); 
                axios.post('/start_cobranzamanual_total',{
                        'idprestamo':me.dataprestamo.idprestamo,
                        'idmodulo':me.idmodulomain, 
                        'detalle':me.obser, 
                        'fechadoc':me.fechadoc, 
                        'numdoc':me.numdoc,  
                        'cobrar':me.cobrarenvioascii?1:0 
                }).then(function (response) {  
                      console.log("response:",response); 
                      me.cerrarmodaleve('modal_cobranza_manual',1);  
                }).catch(function (error) { 
                    console.log("error:",error); 
                    swal("¡ocurrio un problema al cobrar el prestamo!",error.response.data.mensaje, "error");
                });
            },
            ini(){
                this.dataprestamo=null; 
                this.idmodulomain=null; 
                this.preview=null; 
                this.obser=''; 
                this.fechadoc=''; 
                this.numdoc=''; 
                this.cobrarenvioascii=true;
                this.importe=0;
            } 
        }, mounted() {
            this.ini();
        },
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
