<template>
    <main class ="main">
        <div class="row">
            <!-- Aquí pon las col-x necesarias, comienza tu contenido, etcétera -->
             <div class="container-fluid">
                <div class="card">
     <div class="row">               
 <div class="col-md-6">
     <h1>Actualizacion de cuentas</h1> 
     <div class="form-group row col-md-6" style="padding: 6px; border: 1px solid gray;  margin: 23px;">
         
<label style="text-align: right; align-items: center;" class="col-md-6 form-control-label" for="text-input">Ver por cuenta : </label>
                                    <div class="col-md-4 my-auto"> 
                                         <label class="switch switch-label switch-pill switch-primary" style="margin: 0 !important;display: table-cell;">
                                        <input class="switch-input" type="checkbox" checked="" v-model="mostrarporcuentas">
                                        <span class="switch-slider" data-checked="Si" data-unchecked="No"></span>
                                        </label>   
                                    </div>
</div>
                   <div class="col-md-10">
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



                    <div v-if="!errors.has('db')" class="col-md-10">
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
                     
                     <div v-if="!errors.has('tipo')&&!errors.has('db')" class="col-md-10">
                      <label style="text-align: right; align-items: center;font-weight: 500;" class="form-control-label"
                        for="text-input">Numero del comprobante:</label>
                      <div class="input-group">
                        <input style="background-color: #f0f3f5;text-align: right;" type="number"
                          v-model="numcomprobante" class="form-control" placeholder="Numero de comprobante" @keyup.enter="buscarcomprobante()"/> 
                           <div class="input-group-append">
                            <span class="input-group-text btn btn-primary" style="min-width: 60px;" @click="buscarcomprobante()">
                            <i class="fa fa-search"></i> Buscar
                           </span>
                          </div>
                      </div>
                    </div>
  </div>
   <div class="col-md-6" v-if="datos.length>0&&cuentas.length>0">
                        <h1 v-if="check('procesarCambios')">Operaciones</h1> 
                    <div  class="col-md-10" v-if="check('procesarCambios')">
                       <label style="text-align: right;font-weight: 500;" class="form-control-label"
                            for="text-input">Cuenta origen :</label>
                           <v-select   :class="{'error': errors.has('cuenta origen')}"
                                v-validate.initial="'required'" name="cuenta origen" label="descripcion" :options="cuentasOrigen"
                                v-model="cuentaorigenacambiar" placeholder="Seleccione cuenta origen"
                                :reduce="productoSS => productoSS.cuenta" :searchable="false" :clearable="false"  
                            
                                @input="onChangeCuenta()">

                                <span slot="no-options">No existen Datos</span>
                                <template slot="option" slot-scope="option">{{ option.cuenta +' - '+ option.descripcion }}</template>
                                <template slot="selected-option" slot-scope="option3">
                                {{ option3.cuenta +' - '+ option3.descripcion }}
                                </template>
                            </v-select> 

                        <p class="text-error">{{ errors.first('cuenta origen') }}</p>
                    </div>

                   <div  class="col-md-10" v-if="check('procesarCambios')">
                       <label style="text-align: right;font-weight: 500;" class="form-control-label"
                            for="text-input">Cuenta por cual sera sustituida :</label>
                           <v-select   :class="{'error': errors.has('cuenta')}"
                                v-validate.initial="'required'" name="cuenta" label="cuenta" :options="cuentas"
                                v-model="cuentaAcambiar" placeholder="Seleccione cuenta"
                                :reduce="productoSS => productoSS.cuenta" :searchable="true" :filterable="true"
                            
                                @input="onChangeCuenta()">

                                <span slot="no-options">No existen Datos</span>
                                <template slot="option" slot-scope="option">{{ option.cuenta +' - '+ option.descripcion }}</template>
                                <template slot="selected-option" slot-scope="option3">
                                {{ option3.cuenta +' - '+ option3.descripcion }}
                                </template>
                            </v-select> 

                        <p class="text-error">{{ errors.first('cuenta') }}</p>
                    </div>   
                    <div  class="col-md-12" v-if="check('procesarCambios')">
                    <button :disabled = "errors.any()" @click='procesar' type="button" class="btn btn-success btn-lg btn-block">Realizar cambios</button>    
                     </div> 
<h1 v-if="check('procesarCambiosFecha')">Cambio de fecha</h1> 
                     <div v-if="check('procesarCambiosFecha')" class="col-md-10">
                      <label style="text-align: right; align-items: center;font-weight: 500;" class="form-control-label"
                        for="text-input">Fecha del comprobante:</label>
                      <div class="input-group">
                        <input style="background-color: #f0f3f5;text-align: right;" type="date" min="2020-01-01" max="2020-12-31"
                          v-model="fechacomprobantenew" class="form-control" placeholder="Numero de comprobante" @keyup.enter="updateDate()"/> 
                           <div class="input-group-append">
                            <span class="input-group-text btn btn-primary" style="min-width: 60px;" @click="updateDate()">
                            <i class="fa fa-search"></i> cambiar fecha
                           </span>
                          </div>
                      </div>
                    </div>     
                      
      </div>
</div>
                    <div class="col-md-12 mt-5" v-if="datos.length>0">
                            <h1>Comprobante</h1> 
                             
                          <table class="table table-bordered table-striped table-sm" style='padding: 23px;'>
                            <thead>
                                <tr>
                                    <th>cuenta</th>
                                    <th v-if="!mostrarporcuentas" >Subcuenta</th>
                                    <th>Desripcion</th>
                                    <th>DEBE</th>
                                    <th>HABER</th>
                                    <th>t/c</th>
                                </tr>
                            </thead>
                            <tbody>
                                  
                                <template v-if="mostrarporcuentas">
                                                    <template  v-for="padre in datos" >
                                                    <tr :key="padre.id" style='background-color: khaki;font-weight: 700;'>  
                                                        <template v-if="padre.monto>0">
                                                            <td v-text="padre.id +'  --  '+ padre.des" colspan='2'></td>
                                                            <td v-text="padre.monto" style='background-color: powderblue;'></td> 
                                                            <td v-text="0"  style='background-color: burlywood;'></td> 
                                                             <td v-if="padre.analisis"  >Analisis auxiliar</td>
                                                        </template>
                                                        <template v-else> 
                                                            <td > </td>
                                                            <td v-text="padre.id +'  --  '+ padre.des" colspan='1'></td> 
                                                            <td v-text="0"  style='background-color: powderblue;'></td> 
                                                            <td v-text="padre.monto*-1" style='background-color: burlywood;'></td>  
                                                             <td v-if="padre.analisis"  >Analisis auxiliar</td>
                                                        </template> 
                                                    </tr>  
                                                </template> 

                                </template>
                                <template v-else v-for="padre in datos" >
                                    <tr v-if="padre.monto>0" :key="padre.id" style='background-color: khaki;font-weight: 700;'>  
                                        <td v-text="padre.id +'  --  '+ padre.des" colspan='5'></td> 
                                        <td v-if="padre.analisis"  >Analisis auxiliar</td>
                                    </tr> 
                                    <tr v-else :key="padre.id" style='background-color: khaki;font-weight: 700;'>  
                                        <td > </td>
                                        <td v-text="padre.id +'  --  '+ padre.des" colspan='4'></td> 
                                        <td v-if="padre.analisis"  >Analisis auxiliar</td>
                                    </tr> 

                                    <tr v-for="datain in padre.value" :key="datain.id_reg+padre.id"> 
                                        <td > </td>
                                         
                                         <template v-if="datain.importe_moneda_local>0">
                                             <td v-text="datain.id_sub_cuenta " style='text-align: left;'></td>
                                             <td v-text="datain.abrev+'  '+datain.nombrecompleto "> </td>
                                             <td v-text="datain.importe_moneda_local" style='background-color: powderblue;'></td> 
                                             <td v-text="0"  style='background-color: burlywood;'></td> 
                                         </template>
                                         <template v-else>
                                             <td v-text="datain.id_sub_cuenta " style='text-align: right;'></td>
                                             <td v-text="datain.abrev+'  '+datain.nombrecompleto "> </td>
                                             <td v-text="0"  style='background-color: powderblue;'></td> 
                                             <td v-text="datain.importe_moneda_local*-1" style='background-color: burlywood;'></td>  
                                         </template> 
                                        <td v-text="datain.tipo_cambio"></td> 
                                    </tr> 
                                </template>                             
                            </tbody>
                        </table>                      
                      
                    </div>



                    

                </div>
             </div>
        </div>
 
    </main>
</template>


<script>        
import VeeValidate, { directive } from 'vee-validate';
const VueValidationEs = require('vee-validate/dist/locale/es');
Vue.use(VeeValidate, 
{   locale: 'es',
    dictionary: { es: VueValidationEs }
});
   import vSelect from "vue-select";  
Vue.component("v-select", vSelect); 
Vue.use(VeeValidate);    
    export default {
     props:['idmodulo','idventanamodulo'],
        data (){
            return { 
                numcomprobante:'', 
                fechacomprobante:'', 
                fechacomprobantenew:'', 
                valuetipo:'', 
                valuedb:'', 
                cuentaAcambiar:'', 
                cuentaorigenacambiar:'', 
                mostrarselect:1,
                mostrarporcuentas:0,
                datos:[],
                cuentas:[],
                cuentasOrigen:[],
                tipoarray:[{nombre:'INGRESO',id:'SEC_CON_COM_INGRESO'},
                {nombre:'EGRESO',id:'SEC_CON_COM_EGRESO'},
                {nombre:'AJUSTE',id:'SEC_CON_COM_AJUSTE'},
                {nombre:'TRASPASO',id:'SEC_CON_COM_TRASPASO'}],
                
                db:[ 
                 {nombre:'DB 2020',id:'pgsql2020'},
                 {nombre:'DB 2021',id:'pgsql2021'},
                {nombre:'DB Safcon',id:'pgsql'} 
                    ],
                arrayPermisos : {procesarCambios:0,procesarCambiosFecha:0},  
                arrayPermisosIn:[],
            }
        },
         
        
        methods : {
            procesar(){
                    console.log('valuedb:',this.valuedb); 
                    console.log('valuetipo:',this.valuetipo); 
                    console.log('numcomprobante:',this.numcomprobante); 
                    console.log('origen :',this.cuentaorigenacambiar);
                    console.log('a cambiar :',this.cuentaAcambiar) ;
                    

 swal({
                    title: 'Esta seguro de realizar los cambios?',
                    html:   '<div style="text-align: left;">Base de datos:      <b><font >'+(this.valuedb=='pgsql'?'DB Safcon':'DB Prueba')+'</font></b>'+
                    ' <br> No. comprobante:      <b><font >'+this.numcomprobante+'</font></b>'+
                    ' <br> Cod. cuenta origen:      <b><font >'+this.cuentaorigenacambiar+'</font></b>'+
                    ' <br> Cod. cuenta a cambiar:      <b><font >'+this.cuentaAcambiar+'</font></b></div>',
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Seguir',
                    cancelButtonText: 'Cancelar',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    reverseButtons: true
                }).then(result => {
                    if (result.value) { 

                        swal({
                            title: "Actualizando datos...",
                            text: "Actualizacion de datos",
                            type: "warning",
                            showCancelButton: false,
                            showConfirmButton: false,                    
                            closeOnConfirm: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                            onOpen: () => {
                                swal.showLoading()
                            }
                        });
 
 
                        let me=this;
                        var url= '/con_contabilidad/updateCuenta';
                        axios.put(url,{'cuentaA':this.cuentaorigenacambiar, 
                                        'cuentaB':this.cuentaAcambiar, 
                                        'idtransaccion':this.numcomprobante, 
                                        'tipo':this.valuetipo,
                                        'valuedb':this.valuedb}).then(function (response) {
                                          console.log(response)
                                          swal("¡Se cambio los datos correctamente!", "", "success").then((result) => {
                                                me.onChangeTipo();     
                                            })              
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                    }                
                }) 



            },
            buscarcomprobante(link='/con_contabilidad/procesoservicio') { 
                this.cuentaAcambiar='';
                this.cuentaorigenacambiar='';
                this.fechacomprobante='';
                this.fechacomprobantenew='';
                this.cuentas=[];
                this.cuentasOrigen=[];
                if(this.numcomprobante.length>0){
                    
                        swal({
                            title: "Obteniendo datos",
                            allowOutsideClick: () => false,
                            allowEscapeKey: () => false,
                            onOpen: function() {
                            swal.showLoading();
                            }
                        }); 
                     let me=this;
                        var url= link;
                        axios.post(url,{'valuedb':this.valuedb, 
                                        'valuetipo':this.valuetipo,
                                        'numcomprobante':this.numcomprobante}).then(function (response) {
                                            swal.close()
                                    
                                      me.cuentas=response.data.cuentas;
                                      me.fechacomprobante=response.data.fecha;
                                      me.fechacomprobantenew=response.data.fecha;
                                      var aux=response.data.values;
                                       me.datos=[]; 
                                       var cabeseraDebe=[];
                                       var cabeseraHaber=[];
                                        aux.forEach((value, index) => { 
                                            if(value.importe_moneda_local>0){
                                                    if(_.has(cabeseraDebe, value.cuenta)){
                                                        var out=cabeseraDebe[value.cuenta];  
                                                            out.push(value);
                                                            cabeseraDebe[value.cuenta]=out; 
                                                    }else{
                                                        var u=[];
                                                        u.push(value);
                                                        cabeseraDebe[value.cuenta]=u; 
                                                    }
                                            }else{
                                                 if(_.has(cabeseraHaber, value.cuenta)){
                                                        var out=cabeseraHaber[value.cuenta];  
                                                            out.push(value);
                                                            cabeseraHaber[value.cuenta]=out; 
                                                    }else{
                                                        var u=[];
                                                        u.push(value);
                                                        cabeseraHaber[value.cuenta]=u; 
                                                    }
                                            }
                                            

                                        }); 
                                        
                                        me.cuentasOrigen=[];

                                        cabeseraDebe.forEach((value, index) => {  
                                            var sumatoria=_.reduce(value, function(sum, n) {
                                                return _.round(sum +parseFloat(n.importe_moneda_local), 2);
                                                }, 0); 
                                             var outtt= _.find(value, function(o) { return o.analisis_auxiliar >0; });
                                            var analisis=0;
                                                if(typeof outtt !== 'undefined'){
                                                   analisis=1; 
                                                } 
                                            me.datos.push({id:index,value:value,monto:sumatoria,analisis:analisis,des:value[0].descripcion});  

                                                   if(!_.has(me.cuentasOrigen,index)&&index=='41201123'){
                                                       me.cuentasOrigen.push({cuenta:index,descripcion:value[0].descripcion});
                                                    } 
                                            
                                        });
                                        cabeseraHaber.forEach((value, index) => {  
                                            var sumatoria=_.reduce(value, function(sum, n) {
                                                return _.round(sum +parseFloat(n.importe_moneda_local), 2);
                                                }, 0); 
                                            var outtt= _.find(value, function(o) { return o.analisis_auxiliar >0; });
                                            var analisis=0;
                                                if(typeof outtt !== 'undefined'){
                                                   analisis=1; 
                                                } 
                                            me.datos.push({id:index,value:value,monto:sumatoria,analisis:analisis,des:value[0].descripcion});  
                                             if(!_.has(me.cuentasOrigen,index)&&index=='41201123'){
                                                       me.cuentasOrigen.push({cuenta:index,descripcion:value[0].descripcion});
                                             }
                                        });

                                         

                        })
                        .catch(function (error) {
                            console.log(error);
                        });




                } 
            } ,updateDate(link='/con_contabilidad/procesoservicio') {
                     
                    

 swal({
                    title: 'Esta seguro de realizar el cambio de fecha?', 
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Seguir',
                    cancelButtonText: 'Cancelar',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    reverseButtons: true
                }).then(result => {
                    if (result.value) { 

                        swal({
                            title: "Actualizando datos...",
                            text: "Actualizacion de datos",
                            type: "warning",
                            showCancelButton: false,
                            showConfirmButton: false,                    
                            closeOnConfirm: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                            onOpen: () => {
                                swal.showLoading()
                            }
                        });
 
 
                        let me=this;
                        // var url= '/con_contabilidad/updateCuenta';
                        // axios.put(url,{'cuentaA':this.cuentaorigenacambiar, 
                        //                 'cuentaB':this.cuentaAcambiar, 
                        //                 'idtransaccion':this.numcomprobante, 
                        //                 'tipo':this.valuetipo,
                        //                 'valuedb':this.valuedb}).then(function (response) {
                        //                   console.log(response)
                        //                   swal("¡Se cambio los datos correctamente!", "", "success").then((result) => {
                        //                         me.onChangeTipo();     
                        //                     })              
                        // })
                        // .catch(function (error) {
                        //     console.log(error);
                        // });
                        axios.put('/con_contabilidad/updateDateCuentaComprobante',{
                        'fechaantes': me.fechacomprobante,
                        'fecha': me.fechacomprobantenew,
                        'valuedb':me.valuedb, 
                        'tipo':me.valuetipo,
                        'idtransaccion':me.numcomprobante
                        }).then(function (response) {
                                        swal("¡Se cambio los datos correctamente!", "", "success") 
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }                
                }) 



            },
            onChangeCuenta(){  
                
            },
            onChangeTipo(){ 
                this.numcomprobante='';
                this.datos=[]; 
                this.cuentaAcambiar='';
                this.cuentaorigenacambiar='';
                this.cuentas=[];
                this.cuentasOrigen=[];
            },
            onChangeDb(){
                this.valuetipo='';
                this.numcomprobante='';
                this.datos=[]; 
                this.cuentaAcambiar='';
                this.cuentaorigenacambiar='';
                this.cuentas=[];
                 this.cuentasOrigen=[];
            },
             getPermisos() { 
                //permisoId poner axios para obtener los permisos                
                var url= '/adm_role/selectPermisos?idmodulo=' + this.idmodulo + '&idventanamodulo=' + this.idventanamodulo;
                let me = this; 
                axios.get(url).then(function (response) {
                    me.arrayPermisosIn=[];
                    if(response.data.datapermiso.length>0){
                        var respuesta=response.data.datapermiso[0].permisos; 
                        me.arrayPermisosIn = JSON.parse((respuesta));
                    } 
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            check(n){ 
                return _pl.validatePermission(this.arrayPermisosIn,n);
            },
        },
        mounted() {       
            this.getPermisos();         

        }
    }
</script>
