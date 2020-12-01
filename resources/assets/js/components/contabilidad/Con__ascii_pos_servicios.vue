<template>
    <main class ="main">
        <div class="row">
            <!-- Aquí pon las col-x necesarias, comienza tu contenido, etcétera -->
             <div class="container-fluid">
                <div class="card">
                    <div class="col-12">
                        <h1>Actualizacion de cuentas de servicios</h1>                         
                      
                    </div>

               <div class="col-md-4">
                      <label style="text-align: right;font-weight: 500;" class="form-control-label"
                        for="text-input">Seleccione base de datos :</label>
                     
                      <v-select v-if="mostrarselect==1"  :class="{'error': errors.has('db')}"
                        v-validate.initial="'required'" name="db" label="nombre" :options="db"
                        v-model="valuedb" placeholder="Seleccione db"
                        :reduce="productoSS => productoSS.id" :searchable="false" :clearable="false"
                       
                        @input="onChangeDb()">

                        <span slot="no-options">No existen Datos</span>
                        <template slot="option" slot-scope="option">{{ option.nombre }}</template>
                      </v-select>

                      <p class="text-error">{{ errors.first('db') }}</p>
                    </div>



                    <div v-if="!errors.has('db')" class="col-md-4">
                      <label style="text-align: right;font-weight: 500;" class="form-control-label"
                        for="text-input">Tipo :</label>
                     
                      <v-select v-if="mostrarselect==1"   :class="{'error': errors.has('tipo')}"
                        v-validate.initial="'required'" name="tipo" label="nombre" :options="tipoarray"
                        v-model="valuetipo" placeholder="Seleccione tipo"
                        :reduce="productoSS => productoSS.id" :searchable="false" :clearable="false"
                       
                        @input="onChangeTipo()">

                        <span slot="no-options">No existen Datos</span>
                        <template slot="option" slot-scope="option">{{ option.nombre }}</template>
                      </v-select>

                      <p class="text-error">{{ errors.first('tipo') }}</p>
                    </div> 
                     
                     <div v-if="!errors.has('tipo')&&!errors.has('db')" class="col-md-4">
                      <label style="text-align: right; align-items: center;font-weight: 500;" class="form-control-label"
                        for="text-input">Numero del comprobante:</label>
                      <div class="input-group">
                        <input style="background-color: #f0f3f5;text-align: right;" type="number"
                          v-model="numcomprobante" class="form-control" placeholder="Numero de comprobante"/> 
                           <div class="input-group-append">
                            <span class="input-group-text btn btn-primary" style="min-width: 60px;" @click="buscarcomprobante()">
                            <i class="fa fa-search"></i> Buscar
                           </span>
                          </div>
                      </div>
                    </div>

                    <div class="col-12">
                                                
                      
                    </div>

                </div>
             </div>
        </div>
 
    </main>
</template>


<script>        
import VeeValidate from 'vee-validate';
const VueValidationEs = require('vee-validate/dist/locale/es');
Vue.use(VeeValidate, 
{   locale: 'es',
    dictionary: { es: VueValidationEs }
});
   import vSelect from "vue-select";  
Vue.component("v-select", vSelect); 
Vue.use(VeeValidate);    
    export default {
     
        data (){
            return { 
                numcomprobante:'', 
                valuetipo:'', 
                valuedb:'', 
                mostrarselect:1,
                datos:[],
                tipoarray:[{nombre:'INGRESO',id:'SEC_CON_COM_INGRESO'},
                {nombre:'EGRESO',id:'SEC_CON_COM_EGRESO'},
                {nombre:'TRASPASO',id:'SEC_CON_COM_TRASPASO'}],
                
                db:[{nombre:'DB Prueba',id:'pgsql_desarrollo'},
                {nombre:'DB Safcon',id:'pgsql'}]
            }
        },
         
        
        methods : {
            buscarcomprobante() { 
                if(this.numcomprobante.length>0){
                    console.log('valuedb:',this.valuedb); 
                    console.log('valuetipo:',this.valuetipo); 
                    console.log('numcomprobante:',this.numcomprobante); 

                     let me=this;
                        var url= '/con_contabilidad/procesoservicio';
                        axios.post(url,{'valuedb':this.valuedb, 
                                        'valuetipo':this.valuetipo,
                                        'numcomprobante':this.numcomprobante}).then(function (response) {
                                      console.log(response.data.values)                
                        })
                        .catch(function (error) {
                            console.log(error);
                        });




                } 
            } ,
            onChangeTipo(){ 
                this.numcomprobante='';
                this.datos=[];
            },
            onChangeDb(){
                this.valuetipo='';
                this.numcomprobante='';
                this.datos=[];
            }
        },
        mounted() {       
                    

        }
    }
</script>
