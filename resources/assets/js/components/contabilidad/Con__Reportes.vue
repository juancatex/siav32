<template>
<main class="main">
    <!-- Breadcrumb -->
    <div class="breadcrumb" >
        <div class="col-md-1" style="padding:0px">
            <button v-if="!divFiliales" class="btn btn-outline-primary cui-arrow-left" 
            style="font-size:22px; padding:5px;" @click="reportecontabilidad()"></button>
        </div>
        <div class="col-md-10 text-center">
            <h4 v-if="divFiliales" class="nombrecliente">Reportes Contabilidad</h4>
            <span v-if="!divFiliales" style="font-size:18px" v-text="tituloReporte"></span>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row" v-if="divFiliales">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        <strong>Reportes Generales</strong> Contabilidad
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <h6 class="col-md-12 text-info">Libro Diario</h6>
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <span><strong>Tipo Comprobante:</strong></span>
                                    </div>
                                    <div class="col-md-5">
                                        <select class="form-control" 
                                            v-model="idtipocomprobante">
                                            <option value="0">Todos</option>
                                            <option v-for="tipocomprobante in arrayTipocomprobante" :key="tipocomprobante.idtipocomprobante" :value="tipocomprobante.idtipocomprobante" v-text="tipocomprobante.nomtipocomprobante"></option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <button class="btn btn-primary" type="submit" @click="reportelibrodiario()" >Libro Diario</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <h6 class="col-md-12 text-info">Libro Mayor</h6>
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <span><strong>Cuenta Inicial:</strong></span>
                                    </div>
                                    <div class="col-md-9"><!-- v-if="clearSelected"  -->
                                        <Ajaxselect 
                                            ruta="/con_cuentas/selectBuscarcuenta2?buscar=" @found="cuentasinicial" @cleaning="cleaninicial"
                                            resp_ruta="cuentas"
                                            labels="cuentas"
                                            placeholder="Cuenta Inicial" 
                                            idtabla="idcuenta"
                                            :clearable='true'>
                                        </Ajaxselect>
                                        <span class="text-error" v-if="idcuentainicial.length==0">Debe Seleccionar una cuenta</span> 
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <span><strong>Cuenta Final:</strong></span>
                                    </div>
                                    <div class="col-md-9"><!--v-if="clearSelected"  -->
                                        <Ajaxselect  
                                            ruta="/con_cuentas/selectBuscarcuenta2?buscar=" @found="cuentasfinal" @cleaning="cleanfinal"
                                            resp_ruta="cuentas"
                                            labels="cuentas"
                                            placeholder="Cuenta Final" 
                                            idtabla="idcuenta"
                                            :clearable='true'>
                                        </Ajaxselect>
                                        <span class="text-error" v-if="idcuentafinal.length==0">Debe Seleccionar una cuenta</span> 
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12 text-right">
                                        <button :disabled="!reportemayor" class="btn btn-primary" type="submit" @click="reportelibromayor()">Libro Mayor</button><!-- @click="cargarvue('reportemayor')" -->
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <hr>
                        <div class="form-group row">
                            <h6 class="col-md-12 text-info">Balance General</h6>
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-md-2">
                                        <span><strong>Nivel:</strong></span>
                                    </div>
                                    <div class="col-md-5">
                                        <select class="form-control" 
                                            v-model="idnivel">
                                            
                                            <option v-for="(niv,index) in nivel" :key="index" :value="niv" v-text="niv"></option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <button class="btn btn-primary" type="submit" @click="reportebalancegeneral()" >Generar Balance General</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>

                        
                        <!-- <div class="form-group row">
                            <label class="col-md-12">Transacciones</label>
                            <div class="col-md-8">
                                

                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary" type="submit">Generar Reporte</button>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label class="col-md-12">Resumen de Transacciones</label>
                            <div class="col-md-6">
                                

                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-primary" type="submit">Generar Reporte</button>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label class="col-md-12">Resumen de Aportes</label>
                            <div class="col-md-6">
                                

                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-primary" type="submit">Generar Reporte</button>
                            </div>
                        </div>
                       
                      <hr> -->
                      
                        
                        
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <strong>Opciones de Reportes</strong>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label" for="date-input">Fecha Inicial:</label>
                            <div class="col-md-8">
                                <input class="form-control" 
                                    type="date" v-model="fechainicio"
                                    :max="fechafin"
                                    :min="fechamin">
                                <!-- <input class="form-control" 
                                    type="date" v-model="fechainicio"> -->
                                <span class="text-error" v-if="!verificarfechainicio">La Fecha debe ser menor a la fecha actual o fecha final</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label" for="date-input">Fecha Final:</label>
                            <div class="col-md-8">
                                <input class="form-control" 
                                    type="date" v-model="fechafin"
                                    :max="fechahoy">
                                         <!-- <input class="form-control" 
                                    type="date" v-model="fechafin" > -->
                                <span class="text-error" v-if="!verificarfechafin">La Fecha debe ser menor a la fecha actual</span>
                            </div>
                        </div>
                        <hr>
                        <div>
                            <strong class="form-control-label">Filial:</strong>
                            <select v-model="filialselected"  class="form-control"> <!-- por defecto 1 para la filial la paz -->
                                <option value="0" selected="selected">Todas</option>
                                <option v-for="filial in arrayFilial" v-bind:key="filial.idfilial" :value="filial.idfilial" v-text="filial.nommunicipio"></option>
                            </select>
                        </div>
                        
                        <div v-if="filialselected==1">
                            <strong class="form-control-label">Repartición:</strong>
                            <select v-model="idunidad"  class="form-control" name="car" :class="{'invalido':errors.has('car')}" v-validate="'required'">
                                    <option value="0" selected="selected">Todas</option>
                                    <option v-for="unidad in arrayUnidades" :key="unidad.id"
                                    :value="unidad.idunidad" v-text="unidad.nomunidad"></option>
                            </select>
                        </div>
                        <hr>
                        <div>
                            <strong class="form-control-label col-md-8">Subcuenta: &nbsp;&nbsp;</strong>
                            <input class="form-check-input" type="checkbox" id="checkbox" v-model="subcuenta" style="margin-left: 0.25rem;" @change="reset()">
                            <div class="col-form-label " style="border: 1px solid #c2cfd6 !important; border-radius: 5px;" v-if="subcuenta">
                                <div class="form-check-inline" >
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" v-model="directivo" value="4" @change="cambiaDirectivo('4')"> Ascinalss
                                    </label>
                                
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" v-model="directivo" value="1" checked @change="cambiaDirectivo('1')"> Socios
                                    </label>
                                
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" v-model="directivo" value="2" @change="cambiaDirectivo('2')">Personal
                                    </label>
                                
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" v-model="directivo" value="3" @change="cambiaDirectivo('3')">Otros
                                    </label>
                                </div>
                            
                                <div class="row">
                                    <div class="form-group col-md-12" v-if="directivo=='4'">
                                        <h5 >{{ arraySubAscinalss.nombre }}</h5>
                                    </div>
                                    <div class="form-group col-md-12" v-else-if="directivo=='1'">
                                        <strong>Socio:</strong>
                                        <Ajaxselect  v-if="clearSelected"
                                            ruta="/rrh_empleado/selectsocios?buscar=" @found="empleados" @cleaning="cleanempleados"
                                            resp_ruta="empleados"
                                            labels="nombres"
                                            placeholder="Ingrese Texto..." 
                                            idtabla="idsocio"
                                            :id="idempleadoselected"
                                            :clearable='true'>
                                        </Ajaxselect>
                                    </div>
                                    <div class="form-group col-md-12" v-else-if="directivo=='2'">
                                        <strong>Personal:</strong>
                                        <Ajaxselect  v-if="clearSelected"
                                            ruta="/rrh_empleado/selectempleados2?buscar=" @found="empleados" @cleaning="cleanempleados"
                                            resp_ruta="empleados"
                                            labels="nombres"
                                            placeholder="Ingrese Texto..." 
                                            idtabla="idempleado"
                                            :id="idempleadoselected"
                                            :clearable='true'>
                                        </Ajaxselect>
                                    </div>
                                    <div v-else class="form-group col-md-12">
                                        <strong>Otros:</strong>
                                        <Ajaxselect  v-if="clearSelected"
                                                ruta="/alm_proveedor/selectProveedor2?buscar=" @found="empleados" @cleaning="cleanproveedores"
                                                resp_ruta="proveedores"
                                                labels="nit_proveedor"
                                                placeholder="Ingrese texto..." 
                                                idtabla="idproveedor"
                                                :clearable='true'>
                                        </Ajaxselect>
                                    </div>
                                </div>
                            </div>

                        </div>
                        
                        


                    </div>
                </div>
               
            </div>

            <!-- /.col-->
        </div>
        <!-- /.row-->
    <con_reportemayor ref="vuereportemayor"></con_reportemayor>
    <con_reportediario ref="vuereportediario"></con_reportediario>
    </div>
    <!-- <div class="card">
         <div>
                    <table id="table" class="table table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First name</th>
                                <th>Last name</th>
                                <th>Email</th>
                                <th>Country</th>
                                <th>IP-address</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1</td>
                                <td>Donna</td>
                                <td>Moore</td>
                                <td>dmoore0@furl.net</td>
                                <td>China</td>
                                <td>211.56.242.221</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Janice</td>
                                <td>Henry</td>
                                <td>jhenry1@theatlantic.com</td>
                                <td>Ukraine</td>
                                <td>38.36.7.199</td>
                            </tr>
                        </tbody>
                    </table>
                    <button @click="generarpdf()">Generate pdf</button>
                </div>

    </div> -->
    
</main>
</template>
<script>

    Vue.component("con_reportemayor", require("./rep_libromayor.vue").default);
    Vue.component("con_reportediario", require("./rep_librodiario.vue").default);
    import Vue from 'vue';
    import VeeValidate from 'vee-validate'; 
    //import * as plugin from './../functions.js';
    import * as plugin from '../../functions.js';

    export default {
        data (){
            return {    
                obsascii: '',
                ascii: '',
                nuevosascii:'',
                detalleascii:'',
                tipoAccion : 0,
                errorEstado : 0,
                errorMostrarMsjEstado : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nomestado',
                buscar : '',
                //fechaactual: new Date(),
                fechahoy:'',
                mes:'',
                anio:0,
                dia:'',
                
                messelected:0,
                anioselected:0,
                arraymes:[{text:"Enero",value:1},
                          {text:"Febrero",value:2},
                          {text:"Marzo",value:3},
                          {text:"Abril",value:4},
                          {text:"Mayo",value:5},
                          {text:"Junio",value:6},
                          {text:"Julio",value:7},
                          {text:"Agosto",value:8},
                          {text:"Septiembre",value:9},
                          {text:"Octubre",value:10},
                          {text:"Noviembre",value:11},
                          {text:"Diciembre",value:12}
                        ],
                idtipoaporte:0,

                fechafin:'',
                fechainicio:'',
                fechamin:'',
                arrayTipocomprobante:[],
                idtipocomprobante:0,
                valor:100,
                divFiliales:1,
                tituloReporte:'',
                idcuentainicial:[],
                idcuentafinal:[],
                clearSelected:1,
                opciones:'',
                arrayFilial:[],
                filialselected:0,
                arrayUnidades:[],
                idunidad:3, /*por defecto hacienda*/
                directivo:'1',
                subcuenta:false,
                
                arraySubAscinalss:[],
                idempleado:[],
                idempleadoselected:'',
                nivel:[1,2,3,4,5],
                idnivel:5,


            };
        },

        formOptions:{
            validateAfterLoad: true,
            validateAfterChanged: true
        },

        computed:{
            
            reportemayor:function(){
                let me=this;
                var correcto=true;
                if(me.idcuentainicial.length==0 || me.idcuentafinal.length==0)
                    correcto=false;

                return correcto
            },
            verificarfechainicio:function(){
                let me=this;
                var correcto=true;
                if(me.fechainicio>me.fechahoy || me.fechainicio>me.fechafin)
                    correcto=false;    
                return correcto

            },
            verificarfechafin:function(){
                let me=this;
                var correcto=true;
                if(me.fechafin>me.fechahoy || me.fechafin<me.fechainicio)
                    correcto=false;    
                return correcto

            },
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
            reportelibrodiario(){
                let me=this;
                /////////////////////birt
                /* var url=me.reporte_automatico + idasientomaestro+'&tiposubcuenta='+directorio; 
                console.log(url);
                plugin.viewPDF(url,'Asiento Contable'); */
                ///////////////////////////////////////
                
                var url='http://localhost:8000/libro_diario?idtipocomprobante='+ me.idtipocomprobante +'&fechai='+me.fechainicio+'&fechaf='+me.fechafin+'&filial='+me.filialselected+'&idunidad='+me.idunidad+'&idempleado='+me.idempleado;
                
                //console.log(url);
                window.open(url, '_blank');
            },
            reportelibromayor(){
                let me=this;
                var url='http://localhost:8000/libro_mayor?fechai='+me.fechainicio+'&fechaf='+me.fechafin+'&cuentai='+me.idcuentainicial[2]+'&cuentaf='+me.idcuentafinal[2]+'&filial='+me.filialselected+'&idunidad='+me.idunidad+'&idempleado='+me.idempleado;
                //console.log(url);
                window.open(url, '_blank');
            },
            reportebalancegeneral(){
                let me=this;
                var url='http://localhost:8000/balance_general?nivel='+me.idnivel+'&fechai='+me.fechainicio+'&fechaf='+me.fechafin+'&filial='+me.filialselected+'&idunidad='+me.idunidad+'&idempleado='+me.idempleado;
                //console.log(url);
                window.open(url, '_blank');
            },
            reset(){
                let me=this;
                if(!me.subcuenta)
                    this.idempleado=[];


            },
            cleanproveedores(){
            this.idproveedor=[];
            this.idproveedorrespuesta=0;
        //console.log('clean')
        
        },
            cleanempleados(){
                this.idempleado=[];
            },
            empleados(empleados){
            this.idempleado=[];
            for (const key in empleados) {
                if (empleados.hasOwnProperty(key)) {
                    const element = empleados[key];
                    this.idempleado.push(element);
                }
            }
        },
            selectAscinalss(){
            let me=this;
            var url="/con_config/selectsubcuentaascinalss";
            //console.log(Array.isArray(url));
            //this.resetComprobante();
            setTimeout(this.tiempo(2), 200); 
            axios.get(url).then(response=>{
                    me.arraySubAscinalss=response.data.subasc;
            });
                  
        },
            cambiaDirectivo(valor){
                let me=this;
                me.clearSelected=0;
                //console.log(me.clearSelected1);
                setTimeout(me.tiempo, 200); 
                me.directivo=valor;
                if (me.directivo==4) {
                    me.idempleado=[
                        me.arraySubAscinalss[0].idconconfig,
                        me.arraySubAscinalss[0].valor,
                        me.arraySubAscinalss[0].descripcion,
                        me.arraySubAscinalss[0].valor
                        ]
                }else
                    me.idempleado=[];

            },
            listaUnidades(){
                var url='/fil_unidad/listaUnidades?activo=1';
                axios.get(url).then(response=>{
                    this.arrayUnidades=response.data.unidades;
                });
            },
            selectfilial(){
                let me=this;
                var url='/fil_filial/selectFiliales';
                axios.get(url).then(function(response){
                    me.arrayFilial=response.data.filiales;
                    //console.log(me.arrayFilial);
                    
                });
            },
            tiempo(){
               
                    this.clearSelected=1;
            },
            cleaninicial(){
                this.idcuentainicial=[];
                //this.idmovimiento=0;
             
            },
            cuentasinicial(cuentas){ 
                let me=this;
                me.idcuentainicial=[];
                for (const key in cuentas) {
                    if (cuentas.hasOwnProperty(key)) {
                        const element = cuentas[key];
                        //console.log(element);
                        this.idcuentainicial.push(element);
                    }
                }
            },
            cleanfinal(){
                this.idcuentafinal=[];
                //this.idmovimiento=0;
             
            },
            cuentasfinal(cuentas){ 
                let me=this;
                me.idcuentafinal=[];
                for (const key in cuentas) {
                    if (cuentas.hasOwnProperty(key)) {
                        const element = cuentas[key];
                        //console.log(element);
                        this.idcuentafinal.push(element);
                    }
                }
            },

            
            cargarvue(vue){
                this.divFiliales=0;
                switch (vue) {
                    case 'reportemayor':
                        var arrayValores=[];
                        this.tituloReporte="Libro Mayor";
                        arrayValores['fechainicio']=this.fechainicio;
                        arrayValores['fechafin']=this.fechafin;
                        arrayValores['cuentainicio']=this.idcuentainicial[2];
                        arrayValores['cuentafin']=this.idcuentafinal[2];
                        arrayValores['tituloreporte']=this.tituloReporte;
                        this.$refs.vuereportemayor.cargarvue(arrayValores);
                    break;
                    case 'reportecontabilidad':
                        this.reportecontabilidad();
                        
                    break;
                    case 'reportediario':
                        var arrayValores=[];
                        this.tituloReporte="Libro Diario";
                        arrayValores['idtipocomprobante']=this.idtipocomprobante;
                        arrayValores['fechainicio']=this.fechainicio;
                        arrayValores['fechafin']=this.fechafin;
                        arrayValores['tituloreporte']=this.tituloReporte;
                        this.$refs.vuereportediario.cargarvue(arrayValores);
                    break;
                
                    default:
                        break;
                }
                
            },
            reportecontabilidad(){
                this.idcuentafinal=[];
                this.idcuentainicial=[];
                this.idtipocomprobante=0;
                this.divFiliales=1;
                this.$refs.vuereportemayor.cerrarvue();
                this.$refs.vuereportediario.cerrarvue();


            },
            reportes(tipo,mes, anio)
            {
                let me=this;
                var url;
                var titulo; //=me.ascii+mes+'&anio='+anio;
                switch (tipo) {
                    case 1:
                        url=me.ascii+mes+'&anio='+anio;
                        titulo='Reporte Ascii';
                        break;
                    case 2:
                        url=me.nuevosascii+mes+'&anio='+anio;
                        titulo='Nuevos Socios Carga Ascii';
                        break;
                    case 3:
                        url=me.obsascii+mes+'&anio='+anio;
                        titulo='Socios Observados Carga Ascii';
                        break;
                    case 4:
                        url=me.detalleascii+mes+'&anio='+anio;
                        titulo='Detalle Carga Ascii';
                        break;
                }
                //console.log(url);
                
                plugin.viewPDF(url,titulo);
            },
            obtenerfecha(){
                let me = this;
                var url= '/getdate?modal=1';
                axios.get(url).then(function (response) {
                    var respuesta= response.data; 
                    me.fechaactual=respuesta[0]['fecha'];
                    me.fechaactual= new Date();

                    console.log(me.fechaactual);
                    /* var arrayfechas = me.fechaactual.split("-");
                    me.anio=arrayfechas[0];//año
                    me.mes=arrayfechas[1];//mes
                    me.dia=arrayfechas[2];//dia
                     me.fechamin=me.anio+'-01-01';
                    me.fechainicio=me.fechaactual;
                    me.fechahoy=me.fechaactual;
                    me.fechafin=me.fechahoy;
                     */
                    me.anio=me.fechaactual.getFullYear();
                    me.mes=me.fechaactual.getMonth()+1;
                    me.dia=me.fechaactual.getDate();
                    if(me.mes<10)
                        me.mes='0'+me.mes;
                    
                    me.fechamin=me.anio+'-01-01';
                    me.fechaactual=me.anio+'-'+me.mes+'-'+me.dia;
                    me.fechainicio=me.anio+'-'+me.mes+'-'+'01';
                    me.fechahoy=me.fechaactual;
                    me.fechafin=me.fechahoy;

                })
                .catch(function (error) {
                    console.log(error);
                });
                
                
                //me.fechafactura=me.fechaactual;
            },

            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarEstado(page,buscar,criterio);
            },
            getRutasReports (){ 
                let me=this;
                var url= '/con_reportes';
                axios.get(url).then(function (response) {
                    var respuesta= response.data; 
                    //console.log(respuesta);
                    
                    me.obsascii = respuesta.REP_OBSASCII;
                    me.nuevosascii=respuesta.REP_NUEVOSASCII;
                    me.ascii = respuesta.REP_ASCII;
                    me.detalleascii=respuesta.REP_DETALLEASCII;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectTipoaporte(){
                let me=this;
                var url= '/apo_tipoaporte/selectTipoaporte';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayTipoaporte = respuesta.tipoaportes;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectTipocomprobante(){
                let me=this;
                var url= '/con_tipocomprobante/selectTipocomprobante';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayTipocomprobante = respuesta.tipocomprobantes;

                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        },
        mounted() {
           this.getRutasReports();
           this.obtenerfecha();
           this.selectTipocomprobante();
           this.selectfilial();
           this.listaUnidades();
           this.selectAscinalss();
           
        }
    }
</script>
