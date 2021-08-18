<template>
    <main class ="main">
        <div class="row">
            <!-- Aquí pon las col-x necesarias, comienza tu contenido, etcétera -->
             <div class="container-fluid">
                <div class="card">
     <div class="row">  
        <div class="col-md-12">
          <h1>Actualizacion de cuentas a Reserva</h1>              
        </div>
 <div class="col-md-6">
    
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
                           <span class="input-group-text btn btn-success" style="min-width: 60px;" @click="buscarcomprobante('/con_contabilidad/procesoReserva')">
                            <i class="fa fa-search"></i> probar
                           </span>
                          </div>
                      </div>
                    </div>
  </div>
   <div class="col-md-6" v-if="datos.length>0">
                        
       <h1>Cambio de fecha</h1> 
                     <div class="col-md-10">
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
        <h1>Operaciones</h1>  
                    <div  class="col-md-12" v-if="check('procesarCambios')">
                    <button v-if="debesuma==(habersuma)" :disabled = "errors.any()" @click='procesar' type="button" class="btn btn-success btn-lg btn-block">Realizar cambios</button>    
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
                                 <tr>
                                <td v-if="mostrarporcuentas" colspan='2' style="text-align: right;font-weight: bold;"><span >Total</span></td>
                                <td v-if="!mostrarporcuentas" colspan='3' style="text-align: right;font-weight: bold;"><span >Total</span></td> 
                                <td v-text="debesuma" ></td>
                                <td v-text="habersuma"></td>
                                <td></td>
                                </tr>  
                                <template v-if="mostrarporcuentas">
                                                    <template  v-for="(padre,index) in datos" >
                                                    <tr :key="index" style='background-color: khaki;font-weight: 700;'>  
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
                                <template v-else v-for="(padre,index)  in datos" >
                                    <tr v-if="padre.monto>0" :key="index" style='background-color: khaki;font-weight: 700;'>  
                                        <td v-text="padre.id +'  --  '+ padre.des" colspan='5'></td> 
                                        <td v-if="padre.analisis"  >Analisis auxiliar</td>
                                    </tr> 
                                    <tr v-else :key="index" style='background-color: khaki;font-weight: 700;'>  
                                        <td > </td>
                                        <td v-text="padre.id +'  --  '+ padre.des" colspan='4'></td> 
                                        <td v-if="padre.analisis"  >Analisis auxiliar</td>
                                    </tr> 

                                    <tr v-for="(datain,indexs) in padre.value" :key="indexs+'data'"> 
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
                                <tr>
                                <td v-if="mostrarporcuentas" colspan='2' style="text-align: right;font-weight: bold;"><span >Total</span></td>
                                <td v-if="!mostrarporcuentas" colspan='3' style="text-align: right;font-weight: bold;"><span >Total</span></td> 
                                <td v-text="debesuma" ></td>
                                <td v-text="habersuma"></td>
                                <td></td>
                                </tr>                            
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
                auxxx:0,
                fechacomprobantenew:'', 
                fechacomprobante:'', 
                debesuma:0,  
                habersuma:0, 
                numcomprobante:'', 
                valuetipo:'', 
                valuedb:'', 
                cuentaAcambiar:'', 
                cuentaorigenacambiar:'', 
                mostrarselect:1,
                mostrarporcuentas:0,
                datos:[],  
                tipoarray:[{nombre:'INGRESO',id:'SEC_CON_COM_INGRESO'},
                {nombre:'EGRESO',id:'SEC_CON_COM_EGRESO'},
                {nombre:'AJUSTE',id:'SEC_CON_COM_AJUSTE'},
                {nombre:'TRASPASO',id:'SEC_CON_COM_TRASPASO'}],
                
                db:[
                    {nombre:'DB Prueba',id:'pgsql_desarrollo'},
                    {nombre:'DB 2020',id:'pgsql2020'},
                {nombre:'DB Safcon',id:'pgsql'}],
                arrayPermisos : {procesarCambios:0},  
                arrayPermisosIn:[],
            }
        },
         
        
        methods : {
            updateDate() {
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
            procesar(){ 
                this.debesuma=0;
                this.habersuma=0;
                    
let me=this;
 swal({
                    title: 'Esta seguro de realizar los cambios?',
                    html:   '<div style="text-align: left;">Base de datos:      <b><font >'+(this.valuedb=='pgsql'?'DB Safcon':'DB Prueba')+'</font></b>'+
                    ' <br> No. comprobante:      <b><font >'+this.numcomprobante+'</font></b>'+
                    ' <br> Tipo:      <b><font >'+this.valuetipo+'</font></b>',
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
                    me.datos=[]; 
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
                    
                        var url= '/con_contabilidad/procesoReservaUpdate';
                        axios.post(url,{'valuedb':this.valuedb, 
                                        'valuetipo':this.valuetipo,
                                        'numcomprobante':this.numcomprobante}).then(function (response) {
                                            swal.close();
                                         swal("¡Se actualizo los datos correctamente!", "", "success"); 
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
                                        });


                                     
                                               me.debesuma=_.reduce(me.datos, function(sum, n) {  
                                                        return n.monto>0?_.round(sum +parseFloat(n.monto), 2):sum;
                                                }, 0);

                                                me.habersuma=_.reduce(me.datos, function(sum, n) { 
                                                       return n.monto<0?_.round(sum +parseFloat(n.monto), 2):sum;
                                                }, 0);
                                                me.habersuma=(me.habersuma*-1); 
                                         

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
                this.fechacomprobantenew='';
                this.fechacomprobante='';
                this.debesuma=0;
                this.habersuma=0;
              
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
                                         swal.close();
                                      var aux=response.data.values;
                                      me.auxxx=aux;
                                      me.fechacomprobante=response.data.fecha;
                                      me.fechacomprobantenew=response.data.fecha;  
                                      me.datos=[];   
                                       var cabeseraDebe=[];
                                       var cabeseraHaber=[];
                                        aux.forEach((value, index) => {
                                            if(value.cuenta=='51301106')console.log(value);
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
                                        });


                                     
                                               me.debesuma=_.reduce(me.datos, function(sum, n) {  
                                                        return n.monto>0?_.round(sum +parseFloat(n.monto), 2):sum;
                                                }, 0);

                                                me.habersuma=_.reduce(me.datos, function(sum, n) { 
                                                       return n.monto<0?_.round(sum +parseFloat(n.monto), 2):sum;
                                                }, 0);
                                                me.habersuma=(me.habersuma*-1); 

                                     
                                                

                        })
                        .catch(function (error) {
                            console.log(error);
                        });




                } 
            } ,
            
            onChangeTipo(){ 
                this.numcomprobante='';
                this.datos=[]; 
                this.cuentaAcambiar='';
                this.cuentaorigenacambiar='';
                 
            },
            onChangeDb(){
                this.valuetipo='';
                this.numcomprobante='';
                this.datos=[]; 
                this.cuentaAcambiar='';
                this.cuentaorigenacambiar='';
                
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
