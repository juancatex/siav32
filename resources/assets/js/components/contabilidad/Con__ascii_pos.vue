<template>
    <main class ="main">
        <div class="row">
            <!-- Aquí pon las col-x necesarias, comienza tu contenido, etcétera -->
             <div class="container-fluid">
                <div class="card">
                    <div class="col-12">
                        <h1>Actualizacion Comprobantes de Datos y Porcentajes</h1>                         
                        <div v-if="check('desarrollo') && check('produccion')?true:false">
                            <label>Servidor: 
                                <select  name="server" v-model="host" v-validate.initial="'required'">
                                    <option value=''>--elegir--</option>
                                    <option value=1>Desarrollo</option>
                                    <option value=2>Produccion</option>                                     
                                </select>
                                <span class="text-error">{{ errors.first('server')}}</span>
                            </label><br>    
                        </div>                        
                        <div v-if="check('desarrollo')==0 && check('produccion')==0?true:false">
                            <label>Servidor: 
                                <select  name="server" v-model="host" v-validate.initial="'required'">
                                    <option value=''>--elegir--</option>
                                    <option value='99'>Sin valor</option>
                                </select>
                                <span class="text-error">{{ errors.first('server')}}</span>
                            </label><br>    
                        </div>
                        <div v-if="check('desarrollo')==1 && check('produccion')==0?true:false">
                            <label>Servidor: 
                                <select  name="server" v-model="host" v-validate.initial="'required'">
                                    <option value=''>--elegir--</option>
                                    <option value=1>Desarrollo</option>
                                </select>
                                <span class="text-error">{{ errors.first('server')}}</span>
                            </label><br>    
                        </div>
                        <div v-if="check('desarrollo')==0 && check('produccion')==1?true:false">
                            <label>Servidor: 
                                <select name="server" v-model="host" v-validate.initial="'required'">
                                    <option value=''>--elegir--</option>
                                    <option value=2>Produccion</option>
                                </select>
                                <span class="text-error">{{ errors.first('server')}}</span>
                            </label><br>    
                        </div>                        

                        <!-- <label>Base Datos: {{asdf}}</label><br> -->
                        <!-- <label>Estado: <div v-if="flag=='0'"><font color='green'>Pruebas</font></div><div v-else><font color='red'>Produccion</font></div></label> -->
                        <label>Proceso:
                            <select v-model="flag"><option value=0>Pruebas</option><option value=1>Actualizacion</option></select>
                        </label>
                        
                    </div>
                    <div class="col-6">
                        
                            <div class="form-group">
                                <label for="num_comprobante"><b>Comprobante:</b></label>
                                <input name="num_comprobante"  type="number" v-model="num_comprobante"   v-validate.initial="'required'"
                                    class="form-control" placeholder="Num Comprobante">
                                    <span class="text-error">{{ errors.first('num_comprobante')}}</span>
                            </div>
                            <div class="form-group">
                                <label for="cuenta1">Cuenta Prestamo Regular: 41101101</label> <br>                                                   
                                <label for="cuenta2">Cuenta reserva: 22501101</label> <br>
                                <label for="cuenta3">Cuenta Debito fiscal: 21301102</label> <br>
                                <label for="cuenta4">Cuenta impuestos transacciones - Haber: 21301103</label> <br>
                                <label for="cuenta5">Cuenta impuestos transacciones - Debe: 51301106</label> <br>
                                <label for="subcuenta">Subcuenta: 20000000000</label> <br>
                                <label for="tipo">Tipo: 
                                    <select v-model="tipo">
                                        <option value='SEC_CON_COM_INGRESO'>INGRESO</option>
                                        <option value='SEC_CON_COM_EGRESO'>EGRESO</option>                                        
                                        <option value='SEC_CON_COM_TRASPASO'>TRASPASO</option>
                                    </select>
                                    </label> <br>
                            </div>
                            <div class="form-group">
                                <label for="porcentaje2">% a la cuenta reserva: 20%</label>
                                <label for="porcentaje1">% a la cuenta de prestamo regular: 80% (100%)</label> 
                                <ol>
                                    <ul><label for="porcentaje1">% a la cuenta prestamo regular: 87%</label></ul>
                                    <ul><label for="porcentaje1">% a la cuenta Debito fiscal: 13%</label></ul>
                                    <ul><label for="porcentaje1">% a la cuenta Debito fiscal: 3%</label></ul>
                                </ol>
                                
                            </div>
                            <div class="form-group">
                                <!-- <input class="btn-success btn" type="submit" name="submit"></input> -->
                                <button :disabled = "errors.any()" class="btn btn-success" type="button" id="boton" @click="proceso(num_comprobante)">Procesar</button>                            
                            </div>
                        
                    </div>
                </div>
             </div>
        </div>

        <!-- MODAL  MODAL  MODAL  MODAL  -->
        <div class="modal" :class="modal?'mostrar':''" >
            <div class="modal-dialog modal-primary modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button class="close" @click="cerrarModal()">x</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Resultados</label>
                            <div class="col-md-9">
                                <label>Resultados:</label> <br>
                            </div>
                            <div class="col-md-9">
                                {{mensaje}}
                                <div v-if="arrayProceso">
                                    Se realizo el registro a la cuenta: {{arrayDatos.cuenta_aporte}} por el monto de Bs.: {{arrayProceso.sumabol80}} <br>
                                    Se realizo el registro a la cuenta: {{arrayDatos.cuenta_deb_fis}} por el monto de Bs.: {{arrayProceso.sumabol13}} <br>
                                    Se realizo el registro a la cuenta: {{arrayDatos.cuenta_imp_tra}} por el monto de Bs.: {{arrayProceso.sumabol03}} <br>
                                    Se realizo el registro a la cuenta: {{arrayDatos.cuenta_imp_tra_debe}} por el monto de Bs.: {{arrayProceso.sumabol03*-1}} <br>
                                    Finalizado
                                </div>
                                <div v-if="arrayExiste">                                    
                                        Existe la cuenta: {{arrayExiste.cuenta}} por el monto de Bs.: {{arrayExiste.importe}} <br>                                    
                                        Finalizado                                        
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>                        
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
    
Vue.use(VeeValidate);    
    export default {
        props:['idmodulo','idventanamodulo'],
        data (){
            return {
                host: '',                
                num_comprobante:'',
                modal: 0,
                mensaje: '',
                tituloModal:'Resultado',      
                arrayProceso : [],
                arrayExiste : [],
                arrayDatos : [],
                buscar : '',
                flag : 0,  //por defectio en pruebas
                tipo: 'SEC_CON_COM_INGRESO',
                arrayPermisos : {desarrollo:0,produccion:0},  
                arrayPermisosIn:[],
                desarrollo:'',
                produccion:'',
            }
        },
       
        formOptions: {
            validateAfterLoad: true,
            validateAfterChanged: true
          },  

        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            }
        },

        methods : {
            proceso(num_comprobante) { 

                if (this.flag==0) {
                    var t='Mostrar'; var text='Mostrar resultados, no se actualizaran los datos'; var color1='green';} 
                else if (this.flag==1){
                    var t='Actualizar'; var text='Se actualizaran datos, seguro desea proceder?'; var color1='red';}
                if (this.host==1) {
                    var h='Desarrollo'; var tipo='info'; var color='green';} 
                else if (this.host==2)
                    {var h='PRODUCCION'; var tipo='error'; var color='red';}
                swal({
                    title: text + '\n Comprobante:'+this.num_comprobante+' \n Accion:<b><font color='+color1+'>'+t+'</font></b>\n Servidor:<b><font color='+color+'>'+h+'</font></b>',
                    type: tipo,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!',
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
                        var url= '/con_contabilidad/proceso';
                        axios.post(url,{'com':num_comprobante, 
                                        'flag':this.flag, 
                                        'host':this.host,
                                        'tipo':this.tipo}).then(function (response) {
                            swal("¡Proceso Terminado!", "", "success").then((result) => {
                                var respuesta= response.data; console.log(respuesta.existe);       
                                me.arrayProceso = respuesta.proceso;   
                                me.arrayDatos = respuesta.datos;   
                                me.arrayExiste = respuesta.existe;   
                                me.mensaje = respuesta.mensaje;
                                me.modal=1;    
                            })                            
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                    }                
                })                
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

            cerrarModal(){
                this.modal=0;
                this.tituloModal='';                                
            },

        },
        mounted() {       
            this.getPermisos();         

        }
    }
</script>
