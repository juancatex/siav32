<template>
         <main > 
             <div class="modal fade" tabindex="-1" role="dialog" style="z-index: 1600;" aria-hidden="true"
      id="modal_lista_cali">
      <div class="modal-dialog modal-primary modal-xl"  role="document">
        <div class="modal-content animated fadeIn">
          <div class="modal-header">
            <h4 class="modal-title" >Registro de Prestamos por Lista</h4>
            <button type="button" class="close" aria-hidden="true" aria-label="Close"
              @click="classModal.closeModal('modal_lista_cali')">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body" style="padding: 30px;" v-if="validateShow">
            <div class="row form-group" >
                <div class="col-md-6">
                  <label style="text-align: left; align-items: center;font-weight: 500;"
                    class="form-control-label">Socio Beneficiario :</label>
                  <template>
                    <Ajaxselect :ruta="'/pre_listasocio_beneficiario?idpro='+producto+'&buscar='" @found="sociosBeneficiario"
                      @cleaning="sociosBeneficiarioClear" resp_ruta="socios" :id="socio_id"
                      labels="numpapeleta,nombre,apaterno,amaterno" placeholder="Ingrese texto" idtabla="idsocio"
                      :clearable="true">
                    </Ajaxselect>
                  </template>

                </div>

                <div class="col-md-6" v-if="arrayCuentaSocio.length>0&&socio_id!=''">
                  <label style="text-align: left; align-items: center;font-weight: 500;"
                    class="form-control-label">Numero
                    de Cuenta :</label>
                  <div>
                    <div style="display:flex">
                      <v-select ref="cuentaB" style="width: 100%" :class="{'error': errors.has('cuenta bancaria')}"
                        v-validate.initial="'required'" name="cuenta bancaria" label="numcuentasocio"
                        :getOptionLabel="cuentaB => cuentaB.siglaentidadbancaria+' - '+cuentaB.numcuentasocio"
                        :options="arrayCuentaSocio" v-model="cuentaBancaria" placeholder="Seleccione cuenta"
                        :reduce="cuentaB=> cuentaB.idcuentasocio" :searchable="false" :clearable="false">
                        <span slot="no-options">No existen Datos</span>
                        <template slot="option" slot-scope="option">{{ option.siglaentidadbancaria }} -
                          {{ option.numcuentasocio }}</template>
                      </v-select>
                    </div>
                    <span class="text-error">{{ errors.first('cuenta bancaria') }}</span>
                  </div>
                </div>

                <div class="row" v-if="arrayCuentaSocio.length==0&&socio_id!=''">
                   <div class="col align-self-center">
                    <span class="text-error">El socio no tiene una cuenta bancaria activa.</span>
                  </div>
                </div>
              </div>

                  <div class="form-group row" v-if="arrayCuentaSocio.length>0&&cuentaBancaria!=''&&socio_id!=''">
                    <div class="col-md-12 text-center">
                      <button v-if="listaSocios.length==0&&listaSociosObservados.length==0" @click="classModal.openModal('listadoSociosLista')" class="btn btn-primary"> Seleccionar socios </button>
                      <button type="button" v-else @click="classModal.openModal('listadoSociosLista')" 
                      class="btn btn-primary">
                      <span  style="font-size: large;       padding-right: 20px;">Ver lista</span>
                      <span v-if="listaSocios.length>0" style="padding: 4px;    border-radius: 8px;    vertical-align: middle;    font-weight: 500;    margin-right: 10px;" class=" badge-success" >( {{listaSocios.length}} socios seleccionados ) </span>
                      <span v-if="listaSociosObservados.length>0" style="    padding: 4px;    border-radius: 8px;    vertical-align: middle;    font-weight: 500;" class=" badge-warning">{{(listaSociosObservados.length>0)?'( '+listaSociosObservados.length+' socios observados ) ':''}}</span>
                      </button>
                    </div>
                  </div>


              <div class="form-group row" v-if="listaSociosObservados.length==0&&listaSocios.length>0&&cuentaBancaria!=''">
                <div class="col-md-4">
                  <label style="text-align: right; align-items: center;font-weight: 500;" class="form-control-label"
                    for="text-input">Plazo :</label>
                  <div class="input-group">
                    <input v-validate.initial="'required|between:1,12'"
                      style="background-color: white;font-weight: bold;font-size: 20px;text-align: right;" type="number"
                      v-model.number="plazomeses" :class="{'form-control': true, 'is-invalid': errors.has('plazo')}"
                      placeholder="Plazo en meses" name="plazo" autofocus step="any"
                      @keyup.enter="calculoCuota(false)" />
                    <div class="input-group-append">
                      <span style="min-width: 60px;" class="input-group-text">
                        <i style="font-size: 13px;font-weight: 600;">Meses</i>
                      </span>
                    </div>
                  </div>
                  <span class="text-error">{{ errors.first('plazo')}}</span>
                </div>
                <div class="col-md-4">
                  <label style="text-align: right; align-items: center;font-weight: 500;" class="form-control-label"
                    for="text-input">Monto Solicitado :</label>
                  <div class="input-group">
                    <input v-validate.initial="'required|between:1,1000'"
                      style="background-color: white;font-weight: bold;font-size: 20px;text-align: right;" type="number"
                      v-model.number="montosolicitado"
                      :class="{'form-control': true, 'is-invalid': errors.has('monto solicitado')}"
                      placeholder="Monto solicitado" name="monto solicitado" step="any"
                      @keyup.enter="calculoCuota(false)" />
                    <div class="input-group-append">
                      <span class="input-group-text" style="min-width: 60px;">
                        <i style="font-size: 13px;font-weight: 600;" v-text="codigomoneda"></i>
                      </span>
                    </div>
                  </div>
                  <span class="text-error">{{ errors.first('monto solicitado')}}</span>
                </div>
                <div class="col-md-4">
                  <label style="text-align: right; align-items: center;font-weight: 500;" class="form-control-label"
                    for="text-input">Cuota aproximada :</label>
                  <div class="input-group">
                    <div class="input-group-append">
                      <span class="input-group-text" style="min-width: 60px;">
                        <i style="font-size: 13px;font-weight: 600;" v-text="codigomoneda"></i>
                      </span>
                    </div>
                    <input style="background-color: #f0f3f5;font-weight: bold;font-size: 23px;" type="text"
                      :value="cuotaaproximada" class="form-control" placeholder="Cuota aproximada"
                      name="Cuota aproximada" step="any" disabled />
                    <span v-if="cargandocalculo" class="input-group-append">
                      <div style="min-width: 60px;    border-radius: 5px;" class="lds-dual-ring-wait"></div>
                    </span>
                    <span v-else class="input-group-append" v-bind:class="{'ocultaricono':cuotaaproximada==0}">
                      <button style="min-width: 60px;" class="btn btn-primary" type="button"
                        @click="view2();classModal.openModal('plandepagos2');">
                        <i class="fa fa-file-pdf-o"></i>
                      </button>
                    </span>
                  </div>
                </div> 
              </div>

                     <div class="form-group row" v-if="listaSociosObservados.length==0&&listaSocios.length>0&&cuentaBancaria!=''">
                      <label style="text-align: right; align-items: center;" class="col-md-2 form-control-label"
                        for="text-input">Observaciones :</label>
                      <div class="col-md-9">
                        <div class="input-group">
                          <textarea class="col-md-12" rows="4" v-model="obs" placeholder="Observaciones"></textarea>
                        </div>
                      </div>
                    </div>
          </div>

          <div class="modal-footer">
            <button v-if="listaSociosObservados.length==0&&listaSocios.length>0&&cuotaaproximada>0&&cuentaBancaria!=''"  type="button" class="btn btn-success"
              @click="regListaSocios()">Registrar</button>

            <button class="btn btn-secondary" type="button"
              @click="closemodallist">cerrar</button>
          </div>
        </div>
      </div>
    </div>
     
  
      
        </main>
</template>

<script>  
    export default { 
        data (){
            return {
                   
                  
            }
        }, 
        computed:{  
            
        },
        methods : {  
            showVue(){
                this.modalcobranza=new _pl.Modals();
                this.modalcobranza.addModal('modal_lista_cali'); 
                this.modalcobranza.openModal('modal_lista_cali'); 
            } 
        } 
    }
</script>
<style> 
  
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
