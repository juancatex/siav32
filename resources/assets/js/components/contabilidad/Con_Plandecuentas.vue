<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-9 titcard">
                                <div class="ttabla">
                                    <div class="tcelda">Plan de Cuentas</div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="button" @click="imprimirPlanCuentas()" class="btn btn-primary">
                                    <i class="cui-print"></i>
                                </button>
                            </div>
                        </div>
                    </div>


                    
                    <div class="card-body">
                        <div class="form-group col">
                            <div class="col-md-12">
                                <div class="input-group col-md-12">
                                    <label class="col-md-2 form-control-label" for="text-input"><b>Cuenta Nivel 1</b></label>
                                    <label class="col-md-2 form-control-label" for="text-input"><b>Cuenta Nivel 2</b></label>
                                    <label class="col-md-2 form-control-label" for="text-input"><b>Cuenta Nivel 3</b></label>
                                    <label class="col-md-3 form-control-label" for="text-input"><b>Cuenta Nivel 4</b></label>
                                    <label class="col-md-3 form-control-label" for="text-input"><b>Cuenta Nivel 5</b></label>
                                </div>

                                <div class="input-group col-md-12">
                                    <select class="form-control col-md-2" v-model="codn1"  @change="selectCuentaNivel2()">
                                      <option value="0" disabled>Seleccionar...</option>
                                      <option v-for="cuentan1 in arrayCuentan1" :key="cuentan1.codn1" :value="cuentan1.codn1" v-text="cuentan1.nomcuentan1"></option>
                                    </select>
                                    <select class="form-control col-md-2" v-model="codn2"  @change="selectCuentaNivel3()">
                                      <option value="0" disabled>Seleccionar...</option>
                                      <option v-for="cuentan2 in arrayCuentan2" :key="cuentan2.codn2" :value="cuentan2.codn2" v-text="cuentan2.nomcuentan2"></option>
                                    </select>
                                    <select class="form-control col-md-2" v-model="codn3" @change="selectCuentaNivel4()">
                                      <option value="0" disabled>Seleccionar...</option>
                                      <option v-for="cuentan3 in arrayCuentan3" :key="cuentan3.codn3" :value="cuentan3.codn3" v-text="cuentan3.nomcuentan3"></option>
                                    </select>
                                    <select class="form-control col-md-3" v-model="codn4" @change="selectCuentaNivel5()" >
                                      <option value="0" disabled>Seleccionar...</option>
                                      <option v-for="cuentan4 in arrayCuentan4" :key="cuentan4.codn4" :value="cuentan4.codn4" v-text="cuentan4.nomcuentan4"></option>
                                    </select>
                                    <select class="form-control col-md-3" v-model="codn5" @change="selectCuentaNivelss()" >
                                      <option value="0" disabled>Seleccionar...</option>
                                      <option v-for="cuentan5 in arrayCuentan5" :key="cuentan5.codn5" :value="cuentan5.codn5" v-text="cuentan5.nomcuentan5"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col">
                            <h3><span class="text-info">Prefijo de cuenta Seleccionado:</span>
                            <label class="col-md-4 form-control-label" v-text="codcuentan1+codcuentan2+codcuentan3+codcuentan4+codcuentan5" ></label> 
                            </h3>
                        

                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <template v-if="codcuentan5!=''">
                                        <th>Opciones</th>
                                    </template>    
                                    <th style="width:200px">Valor (Codigo)<br> Cuenta {{ nomcuenta }}</th>
                                    <th>Nombre</th>
                                    <template v-if="codcuentan5!=''">
                                        
                                        <th>Moneda</th>
                                        <th>Cod Cuenta</th>
                                    </template>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- <template v-if="arrayCuentan1!=[] && arrayCuentan2==[] && arrayCuentan3==[] && arrayCuentan4==[]"  >-->
                                <template v-if="arrayCuentan1!=[] && arrayCuentan2[0]==Array[0] && arrayCuentan3[0]==Array[0] && arrayCuentan4[0]==Array[0] && arrayCuentan5[0]==Array[0] && arrayCuentann[0]==Array[0] && codcuentan1==''">
                                <tr v-for="cuentan1 in arrayCuentann1" :key="cuentan1.idcuentan1">
                                   
                                    <td v-text="cuentan1.codn1" style="padding-left: 20px;"></td>
                                    <td v-text="cuentan1.nomcuentan1"></td>
                                    
                                    
                                    <td>
                                        <div v-if="cuentan1.activo">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                        
                                        
                                    </td>
                                </tr>
                                
                                </template> 
                                <!--<template v-else-if="arrayCuentan1!=[] && arrayCuentan2!=[] && arrayCuentan3==[] && arrayCuentan4==[]"  >-->
                                <template v-else-if="arrayCuentan1!=[] && arrayCuentan2!=[] && arrayCuentan3[0]==Array[0] && arrayCuentan4[0]==Array[0]  && arrayCuentan5[0]==Array[0] && arrayCuentann[0]==Array[0]&& codcuentan2=='' ">
                                <tr v-for="cuentan2 in arrayCuentann2" :key="cuentan2.idcuentan2">
                                    <td v-text="cuentan2.valorn2" style="padding-left: 20px;"></td>
                                    <td v-text="cuentan2.nomcuentan2"></td>
                                    <td>
                                        <div v-if="cuentan2.activo">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                    </td>
                                </tr>  
                                
                                </template> 
                                <!--    <template v-else-if="arrayCuentan1!=[] && arrayCuentan2!=[] && arrayCuentan3!=[] && arrayCuentan4==[]"  >-->
                                <template v-else-if="arrayCuentan1!=[] && arrayCuentan2!=[] && arrayCuentan3!=[] && arrayCuentan4[0]==Array[0] && arrayCuentan5[0]==Array[0] && arrayCuentann[0]==Array[0] && codcuentan3==''">
                                <tr v-for="cuentan3 in arrayCuentann3" :key="cuentan3.idcuentan3">
                                    
                                    <td v-text="cuentan3.valorn3" style="padding-left: 20px;"></td>
                                    <td v-text="cuentan3.nomcuentan3"></td>
                                    
                                    <td>
                                        <div v-if="cuentan3.activo">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                        
                                    </td>
                                </tr>  
                                
                                </template> 
                                <!--<template v-else-if="arrayCuentan1!=[] && arrayCuentan2!=[] && arrayCuentan3!=[] && arrayCuentan4!=[]"  >-->
                                <template v-else-if="arrayCuentan1!=[] && arrayCuentan2!=[] && arrayCuentan3!=[] && arrayCuentan4!=[] && arrayCuentan5[0]==Array[0] && arrayCuentann[0]==Array[0] && codcuentan4==''">
                                <tr v-for="cuentan4 in arrayCuentann4" :key="cuentan4.codn4">
                                    
                                    <td v-text="cuentan4.valorn4" style="padding-left: 20px;"></td>
                                    <td v-text="cuentan4.nomcuentan4"></td>
                                    
                                    <td>
                                        <div v-if="cuentan4.activo">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                        
                                    </td>
                                </tr>  
                                
                                </template> 

                                <template v-else-if="arrayCuentan1!=[] && arrayCuentan2!=[] && arrayCuentan3!=[] && arrayCuentan4!=[] && arrayCuentan5!=[] && arrayCuentann[0]==Array[0] && codcuentan5==''">
                                <tr v-for="cuentan5 in arrayCuentann5" :key="cuentan5.codn5">
                                    <td v-text="cuentan5.valorn5" style="padding-left: 20px;"></td>
                                    <td v-text="cuentan5.nomcuentan5"></td>
                                    
                                    <td>
                                        <div v-if="cuentan5.activo">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                        
                                    </td>
                                </tr>  
                                
                                </template> 





                                <template v-else-if="arrayCuentan1!=[] && arrayCuentan2!=[] && arrayCuentan3!=[] && arrayCuentan4!=[] && arrayCuentan5!=[] && arrayCuentann!=[] ">
                                <tr v-for="cuentann in arrayCuentannn" :key="cuentann.idcuentann">
                                    <td>                                                      
                                        <button type="button" @click="botonactualizarcuenta(cuentann.idcuenta,cuentann.valorcuenta,cuentann.nomcuenta,nomcuenta,cuentann.descripcion,cuentann.idmoneda)" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Actualizar">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="cuentann.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarcuenta(nomcuenta,cuentann.idcuenta)" data-toggle="tooltip" data-placement="top" title="Desactivar Cuenta">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarcuenta(nomcuenta,cuentann.idcuenta)" data-toggle="tooltip" data-placement="top" title="Activar Cuenta">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="cuentann.valorcuenta" style="padding-left: 20px;"></td>
                                    <td v-text="cuentann.nomcuenta"></td>
                                    
                                    <td v-text="cuentann.codmoneda"></td>
                                    <td v-text="cuentann.codcuenta"></td>
                                    <td>
                                        <div v-if="cuentann.activo">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                        
                                    </td>
                                </tr> 
                               
                                </template>
                                <tr>
                                    <template v-if="codcuentan5!=''">
                                        <td></td>
                                    </template>  
                                    <td>
                                        <div class="col-md-6" style="padding-left: 10px;">
                                            <h4>  {{valorcuenta}}</h4>
                                        </div>
                                    </td>
                                    <td>
                                        <input   
                                                v-validate.initial= "'required'" 
                                                type="text" 
                                                v-model="nombrecuenta"  
                                                class="form-control" 
                                                placeholder="Nombre Cuenta" 
                                                name='Nombre Cuenta'>  
                                        <span class="text-error">{{ errors.first('Nombre Cuenta')}}</span>  
                                    </td>
                                    <template v-if="codcuentan5!=''">
                                        <td>
                                        <!--<label class="col-md-12 form-control-label" for="text-input">Seleccionar Moneda</label>-->
                                            <select class="form-control" 
                                                    v-model="idmoneda"
                                                    v-validate.initial="'required'"
                                                    name="Moneda">

                                                <!--<option value="0">Tipo de Moneda</option>-->
                                                <option v-for="moneda in arrayMonedas" :key="moneda.idmoneda" :value="moneda.idmoneda" v-text="moneda.codmoneda"></option>
                                            </select>   
                                            <span class="text-error">{{ errors.first('Moneda') }}</span>  
                                        </td>
                                    
                                    <td colspan="2">
                                        <button  :disabled = "errors.any ()" type="submit" v-if="tipoAccion==1 || check('plan_agregar')" class="btn btn-primary" @click="registrarcuenta(valorcuenta,nomcuenta)">
                                            <i class="icon-plus"></i>&nbsp;Nueva {{ nomcuenta }}
                                        </button>

                                        <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==2 || check('plan_agregar')" class="btn btn-primary" @click="actualizarcuenta(valorcuenta,nomcuenta)">
                                            <i class="icon-plus"></i>&nbsp;Actualizar {{ nomcuenta }}
                                        </button>
                                    </td>
                                    </template>
                                    <template v-else>
                                    <td >
                                        <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==1 || check('plan_agregar')" class="btn btn-primary" @click="registrarcuenta(valorcuenta,nomcuenta)">
                                            <i class="icon-plus"></i>&nbsp;Nueva {{ nomcuenta }}
                                        </button>

                                        <button  :disabled = "errors.any ()" type="submit" v-if="tipoAccion==2 || check('plan_agregar')" class="btn btn-primary" @click="actualizarcuenta(valorcuenta,nomcuenta)">
                                            <i class="icon-plus"></i>&nbsp;Actualizar {{ nomcuenta }}
                                        </button>
                                    </td>
                                    </template>


                                </tr>
                                
                                <template v-if="codcuentan5!=''">
                                <tr>
                                    <td></td>
                                    <td colspan="2">
                                        <input   
                                                v-validate.initial= "'required'" 
                                                type="text" 
                                                v-model="descripcioncuenta"  
                                                class="form-control" 
                                                placeholder="Descripcion Cuenta" 
                                                name='Descripcion Cuenta'>  
                                        <span class="text-error">{{ errors.first('Descripcion Cuenta')}}</span>  
                                    </td>
                                </tr> 
                                                          
                                </template>
                            </tbody>
                        </table>
                        </div>
                        
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,nomcuenta)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,nomcuenta)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,nomcuenta)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                        
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h6>Buscador de Cuentas</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <strong class="col-md-3">Cuenta:</strong>
                                    <input type="text" v-model="buscador" @keyup.enter="buscarcuenta()" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="buscarcuenta()" class="btn btn-primary" :disabled="buscador==''"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Cod Cuenta</th>
                                    <th>Nombre Cuenta</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="buscados in arrayBuscarCuentas" :key="buscados.idcuenta">
                                    <td v-text="buscados.codcuenta"></td>
                                    <td v-text="buscados.nomcuenta"></td>
                                    <td><span v-if="buscados.activo" class="badge badge-success">Activo</span>
                                        <span v-else class="badge badge-danger">Desactivado</span>
                                    </td>
                                </tr>                                
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">

                    </div>

                </div>
                
            </div>
            
            
        </main>

</template>

<script>


    import Vue from 'vue';
    import VeeValidate from 'vee-validate';
    import * as plugin from '../../functions.js';
/* 
    import BootstrapVueTreeview from 'bootstrap-vue-treeview'
    Vue.use(BootstrapVueTreeview); */
    Vue.use(VeeValidate);

    export default {
        props:['idmodulo','idventanamodulo'],
        data (){
            
            return {
                //treeData: [{"id": 2, "name": "Venus" , "children": [{"id": 3, "name": "Neptune"}, {"id": 4, "name": "Stratus"} ] },{"id": 6, "name": "nnNeptune","children": [{"id": 3, "name": "Neptune"}, {"id": 4, "name": "Stratus"} ]} ],
                tipoAccion:1,
                arrayCuentan1:[],
                arrayCuentan2:[],
                arrayCuentan3:[],
                arrayCuentan4:[],
                arrayCuentan5:[],
                arrayCuentann:[],
                arrayCuentann1:[],
                arrayCuentann2:[],
                arrayCuentann3:[],
                arrayCuentann4:[],
                arrayCuentann5:[],
                arrayCuentannn:[],
                arrayMonedas:[],
                idmoneda:'',

                codcuentan1:'',
                codcuentan2:'',
                codcuentan3:'',
                codcuentan4:'',
                codcuentan5:'',
                tamanio:1,
                caracteres:'1 Caracter',

                
                codn1:0,
                codn2:0,
                codn3:0,
                codn4:0,
                codn5:0,

                nomcuenta:'',

                valorcuenta:'',
                nombrecuenta:'',

                nombrecuentanivel1:'',
                nombrecuentanivel2:'',
                nombrecuentanivel3:'',
                nombrecuentanivel4:'',
                nombrecuentanivel5:'',
                nombrecuentaniveln:'',
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : '',
                buscar : '',
                descripcioncuenta:'',
                idcuenta:'',
                maxcn1:'',
                maxcn2:'',
                maxcn3:'',
                maxnc4:'',
                maxnc5:'',
                maxcnn:'',
                buscador:'',
                arrayBuscarCuentas:[],
                reporte_plan_cuentas:'',
                arrayPermisos:{plan_agregar:0},
                
                arrayPermisosIn:[]
            }
        },

        formOptions:{
            validateAfterLoad: true,
            validateAfterChanged: true
        },

        computed:{
           /* isComplete () {
                
                return this. && this.obsdebito;
            },*/
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
            getRutasReports(){ 
            let me=this;
            var url= '/con_reportes';
            axios.get(url).then(function (response) {
                    var respuesta= response.data; ;
                me.reporte_plan_cuentas = respuesta.REP_PLAN_CUENTAS; 
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        imprimirPlanCuentas(){
            let me=this;
            var url=me.reporte_plan_cuentas; 
            //console.log(url);
            
            //me.abrirVentanaModalURL(url,"reporte_asiento_automatico",800,700);	
            plugin.viewPDF(url,'libro de compras');

        },
        selectCuentanivel1(){
            let me=this;
            me.nomcuenta='Cuenta Nivel 1';
            me.nombrecuenta='';
            me.tamanio=1;
            me.caracteres='1 Caracter';
            var url= '/con_cuentan1/selectCuentanivel1';
            axios.get(url).then(function (response) {
                //console.log(response.data);
                var respuesta= response.data;
                me.arrayCuentan1 = respuesta.cuentasnivel1;
            })
            .catch(function (error) {
                console.log(error);
            });
            //me.arrayCuentass=me.arrayCuentan1;
            me.listarCuentaNivel1(1);
            
        },

            selectCuentaNivel2(){
                let me=this;
                me.nomcuenta='Cuenta Nivel 2';
                me.codcuentan2='';
                me.codcuentan3='';
                me.codcuentan4='';
                me.codcuentan5='';
                me.codcuentann='';
                me.arrayCuentan2=[];
                me.arrayCuentan3=[];
                me.arrayCuentan4=[];
                me.arrayCuentan5=[];
                me.arrayCuentann=[];
                me.codn2=0;
                me.codn3=0;
                me.codn4=0;
                me.codn5=0;
                me.idcuentanivelnn=0;
                me.arrayCuentann2=[];
                me.arrayCuentann3=[];
                me.arrayCuentann4=[];
                me.arrayCuentann5=[];
                me.arrayCuentannn=[];
                me.nombrecuentanivel2='';
                me.nombrecuentanivel3='';
                me.nombrecuentanivel4='';
                me.nombrecuentanivel5='';
                me.valorcuenta='';
                me.nombrecuenta='';

                
               
                me.tamanio=1;
                me.caracteres='1 Caracter';
                me.selectCodnivel1();
                me.listarCuentaNivel2(1);
                //console.log(valor)
                
                //console.log(ccuenta);
                if(this.codn1!=0)
                {
                   // console.log('entra');
                    var url= '/con_cuentan2/selectCuentanivel2?codn1='+this.codn1;
                    axios.get(url).then(function (response) {
                    
                    var respuesta= response.data;
                    me.arrayCuentan2 = respuesta.cuentasnivel2;
                   
                    //console.log(me.arrayCuentan2)
                })
                .catch(function (error) {
                    console.log(error);
                });
                }
                

            },
            selectCuentaNivel3(){
                let me=this;
                me.nomcuenta='Cuenta Nivel 3';
                me.codcuentan3='';
                me.codcuentan4='';
                me.codcuentan5='';
                me.codcuentann='';
                me.arrayCuentan3=[];
                me.arrayCuentan4=[];
                me.arrayCuentan5=[];
                me.arrayCuentann=[];
                me.codn3=0;
                me.codn4=0;
                me.codn5=0;
                me.idcuentanivelnn=0;
                me.nombrecuentanivel3='';
                me.nombrecuentanivel4='';
                me.nombrecuentanivel5='';
                
                me.arrayCuentann3=[];
                me.arrayCuentann4=[];
                me.arrayCuentann5=[];
                me.arrayCuentannn=[];
                me.valorcuenta='';
                me.nombrecuenta='';
                me.tamanio=1;
                me.caracteres='1 Caracter';
                me.selectCodnivel2();
                me.listarCuentaNivel3(1);
                if(this.codn2!=0)
                {
                    var url= '/con_cuentan3/selectCuentanivel3?codn2='+this.codn2;
                    axios.get(url).then(function (response) {
                    //console.log(response.data);
                    var respuesta= response.data;
                    me.arrayCuentan3 = respuesta.cuentasnivel3;
                    

                })
                .catch(function (error) {
                    console.log(error);
                });
                }
            },
            selectCuentaNivel4(){
                let me=this;
                me.nomcuenta='Cuenta Nivel 4';
                me.codcuentan4='';
                me.codcuentan5='';
                me.codcuentann='';
                me.arrayCuentan4=[];
                me.arrayCuentan5=[];
                me.codn4=0;
                
                me.nombrecuentanivel4='';
                me.idcuentaniveln5=0;
                me.arrayCuentann4=[];
                me.arrayCuentann5=[];
                me.valorcuenta='';
                me.nombrecuenta='';
                
                me.tamanio=1;
                me.caracteres='1 Caracter';
                me.selectCodnivel3();
                me.listarCuentaNivel4(1);
                if(this.codn3!=0)
                {
                    var url= '/con_cuentan4/selectCuentanivel4?codn3='+this.codn3;
                    axios.get(url).then(function (response) {
                    //console.log(response.data);
                    var respuesta= response.data;
                    me.arrayCuentan4 = respuesta.cuentasnivel4;
                   
                })
                .catch(function (error) {
                    console.log(error);
                });
                }

            },
            selectCuentaNivel5(){
                let me=this;
                me.nomcuenta='Cuenta Nivel 5';
                me.codcuentan5='';
                me.codcuentann='';
                me.arrayCuentan5=[];
                me.arrayCuentann=[];
                me.codn5=0;
                
                me.nombrecuentanivel5='';
                me.idcuentanivelnn=0;
                me.arrayCuentann5=[];
                me.arrayCuentannn=[];
                me.valorcuenta='';
                me.nombrecuenta='';
                
                me.tamanio=2;
                me.caracteres='2 Caracteres';
                me.selectCodnivel4();
                me.listarCuentaNivel5(1);
                if(this.codn4!=0)
                {
                    var url= '/con_cuentan5/selectCuentanivel5?codn4='+this.codn4;
                    axios.get(url).then(function (response) {
                    //console.log(response.data);
                    var respuesta= response.data;
                    me.arrayCuentan5 = respuesta.cuentasnivel5;
                   
                })
                .catch(function (error) {
                    console.log(error);
                });
                }

            },
            selectCuentaNivelss(){
                let me=this;
                me.nomcuenta='Cuenta';
                me.codcuentann=''
                me.arrayCuentann=[];
                me.idcuentanivelnn=0;
                me.valorcuenta='';
                me.nombrecuenta='';
                me.tamanio=2;
                me.caracteres='2 Caracteres'; me.descripcioncuenta='-';
                me.arrayCuentannn=[];
                me.selectCodnivel5();
                me.listarCuentas(1);
                if(this.codn5!=0)
                {
                    var url= '/con_cuentas/selectCuentas?codn5='+this.codn5;
                    axios.get(url).then(function (response) {
                    //console.log(response.data);
                    var respuesta= response.data;
                    me.arrayCuentann = respuesta.cuentass;
                   
                })
                .catch(function (error) {
                    console.log(error);
                });
                }

            },

            selectCodnivel1(){
                let me=this;
                
                
                var url= '/con_cuentan1/selectValornivel1?codn1='+this.codn1;
                axios.get(url).then(function (response) {
               // console.log(response.data);
                var respuesta= response.data;
                me.codcuentan1 = respuesta.codn1[0].codn1;
                //console.log(me.codcuentan1);
                
                me.nombrecuentanivel1=respuesta.codn1[0].nomcuentan1;
              //  console.log(me.codcuentan1);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            selectCodnivel2(){
                let me=this;
                
                // me.arrayCuentass=me.arrayCuentan2;
                var url= '/con_cuentan2/selectValornivel2?codn2='+this.codn2;
                axios.get(url).then(function (response) {
                //console.log(response.data);
                var respuesta= response.data;
                me.codcuentan2 = respuesta.valorn2[0].valorn2;
                me.nombrecuentanivel2=respuesta.valorn2[0].nomcuentan2;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            selectCodnivel3(){
                let me=this;
                
                //me.arrayCuentass=me.arrayCuentan3;
                var url= '/con_cuentan3/selectValornivel3?codn3='+this.codn3;
                axios.get(url).then(function (response) {
              //  console.log(response.data);
                var respuesta= response.data;
                me.codcuentan3 = respuesta.valorn3[0].valorn3;
                me.nombrecuentanivel3=respuesta.valorn3[0].nomcuentan3;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectCodnivel4(){
                let me=this;
                
                //me.arrayCuentass=me.arrayCuentan4;
                var url= '/con_cuentan4/selectValornivel4?codn4='+this.codn4;
                axios.get(url).then(function (response) {
              //  console.log(response.data);
                var respuesta= response.data;
                me.codcuentan4 = respuesta.valorn4[0].valorn4;
                me.nombrecuentanivel4=respuesta.valorn4[0].nomcuentan4;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectCodnivel5(){
                let me=this;
                //me.arrayCuentass=me.arrayCuentan4;
                var url= '/con_cuentan5/selectValornivel5?codn5='+this.codn5;
                axios.get(url).then(function (response) {
              //  console.log(response.data);
                var respuesta= response.data;
                me.codcuentan5 = respuesta.valorn5[0].valorn5;
                me.nombrecuentanivel5=respuesta.valorn5[0].nomcuentan5;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            verificarcuenta(){
                let me=this;
                if (!arrayNivel2)
                {
                    arrayCuentass=$arrayCuentan1;
                  //  console.log("vacioN2")
                }

            },
            validateBeforeSubmit() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                    //alert('enviado');
                    //return this.result;
                    return;
                    }
                    
                    //alert('no enviado');
                    return;
                    ////alert(result);
                    
                    
                });
            },
            listarCuentaNivel1(page){
                let me=this;
                var url= '/con_cuentan1?page=' + page;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayCuentann1 = respuesta.cuentasnivel1.data;
                    me.pagination= respuesta.pagination;
                    me.maxcn1=respuesta.maxcn1[0].maxcn1;
                    //console.log(me.maxcn1);
                    if(me.maxcn1)
                        me.valorcuenta=parseInt(me.maxcn1)+1;
                    else
                        me.valorcuenta=1;
                })
                .catch(function (response) {
                  //  console.log(response);
                });
            },
            listarCuentaNivel2(page){
                let me=this;
                var url= '/con_cuentan2?page='+page+'&codn1='+me.codn1;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayCuentann2 = respuesta.cuentasnivel2.data;
                    me.pagination= respuesta.pagination;
                    me.maxcn2=respuesta.maxcn2[0].maxcn2;
                    if(me.maxcn2)
                        me.valorcuenta=parseInt(me.maxcn2)+1;
                    else
                        me.valorcuenta=1;
                    //console.log(me.maxcn2);
                    
                    
                })
                .catch(function (response) {
                  //  console.log(response);
                });
            },
            listarCuentaNivel3(page){
                let me=this;
                var url= '/con_cuentan3?page='+page+'&codn2='+me.codn2;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayCuentann3 = respuesta.cuentasnivel3.data;
                    me.pagination= respuesta.pagination;
                    me.maxcn3=respuesta.maxcn3[0].maxcn3;
                    if(me.maxcn3)
                        me.valorcuenta=parseInt(me.maxcn3)+1;
                    else
                        me.valorcuenta=1;
                })
                .catch(function (response) {
                  //  console.log(response);
                });
            },
            listarCuentaNivel4(page){
                let me=this;
                var url= '/con_cuentan4?page='+page+'&codn3='+me.codn3;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayCuentann4 = respuesta.cuentasnivel4.data;
                    me.pagination= respuesta.pagination;
                    me.maxcn4=respuesta.maxcn4[0].maxcn4;
                    if(me.maxcn4)
                        me.valorcuenta=parseInt(me.maxcn4)+1; //TODO:VERIFICAR QUE NO PASE DE 9 LOS VALORES PARA NO GENERAR ERROR EN LOS CODIGOS
                    else    
                        me.valorcuenta=1;

                   // console.log(me.valorcuenta);
                    
                    
                })
                .catch(function (response) {
                  //  console.log(response);
                });
            },
            listarCuentaNivel5(page){
                let me=this;
                var url= '/con_cuentan5?page='+page+'&codn4='+me.codn4;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayCuentann5 = respuesta.cuentasnivel5.data;
                    me.pagination= respuesta.pagination;
                    me.maxcn5=respuesta.maxcn5[0].maxcn5;
                    if(me.maxcn5)
                        me.valorcuenta=parseInt(me.maxcn5)+1;
                    else
                        me.valorcuenta=1;
                   // console.log(me.valorcuenta);
                })
                .catch(function (response) {
                  //  console.log(response);
                });
            },
            listarCuentas(page){
                let me=this;
                var url= '/con_cuentas?page='+page+'&codn5='+me.codn5;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayCuentannn = respuesta.cuentass.data;
                    me.pagination= respuesta.pagination;
                    me.maxcnn =respuesta.maxccuenta[0].maxccuenta;
                    if(me.maxcnn)
                        me.valorcuenta=parseInt(me.maxcnn)+1;
                    else
                        me.valorcuenta=1;

                    if(me.valorcuenta<10)
                        me.valorcuenta='0'+me.valorcuenta;
                    console.log(me.valorcuenta);
                })
                .catch(function (response) {
                  //  console.log(response);
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                
                //Envia la petición para visualizar la data de esa página
               // console.log(page);
                if (criterio=='Cuenta')
                {    
                    me.listarCuentas(page);
                    me.pagination.current_page = page;
                }
                if (criterio=='Cuenta Nivel 1')
                {
                    me.listarCuentaNivel1(page);
                    me.pagination.current_page = page;
                }
                if (criterio=='Cuenta Nivel 2')
                {
                    me.listarCuentaNivel2(page);
                    me.pagination.current_page = page;
                }   
                if (criterio=='Cuenta Nivel 3')
                {   
                    me.listarCuentaNivel3(page);
                    me.pagination.current_page = page;
                }
                if (criterio=='Cuenta Nivel 4')
                {
                    me.listarCuentaNivel4(page);
                    me.pagination.current_page = page;
                }
                
                criterio='';
                    
            },
            registrarcuenta(valordecuenta,nombredecuenta){
                /*if (this.validarTipocomprobante()){
                    return;
                }*/
                //console.log(valordecuenta,this.nombrecuenta+'valores');
                let me = this;
                switch (nombredecuenta) {
                    case 'Cuenta Nivel 1':
                    {
                        axios.post('/con_cuentan1/registrar',{
                            'codn1': valordecuenta,
                            'nombrecuentan1': this.nombrecuenta
                            
                        }).then(function (response) {
                            if(response.data.length){
                            //console.log(response)
                            swal(
                                    'El Valor ya Existe',
                                    'Ingresa un dato Diferente',
                                    'error'
                            )                    }
                            else{
                                swal(
                                    'Registrado correctamente',
                            )  
                                //me.cerrarModal();
                                me.selectCuentanivel1();

                            }

                            
                        }).catch(function (error) {
                            console.log(error);
                        });
                        this.valorcuenta='';
                        this.nombrecuenta='';
                        break;
                    }
                    case 'Cuenta Nivel 2':
                    {
                        axios.post('/con_cuentan2/registrar',{
                            'codn1':this.codn1,
                            'valorn2': valordecuenta,
                            'nombrecuentan2': this.nombrecuenta
                            
                        }).then(function (response) {
                            if(response.data.length){
                            //console.log(response)
                            swal(
                                    'El Valor ya Existe',
                                    'Ingresa un dato Diferente',
                                    'error'
                            )                    }
                            else{
                                    swal(
                                    'Registrado correctamente',
                                    )

                                me.selectCuentaNivel2();
                                

                            }

                            
                        }).catch(function (error) {
                            //console.log(error.response);
                            swal(
                                    'El Valor ya Existe',
                                    'Ingresa un dato Diferente',
                                    'error'
                            )
                        });
                        this.valorcuenta='';
                        this.nombrecuenta='';
                        break;
                    }
                    case 'Cuenta Nivel 3':
                    {
                        axios.post('/con_cuentan3/registrar',{
                            'codn2':this.codn2,
                            'valorn3': valordecuenta,
                            'nombrecuentan3': this.nombrecuenta
                            
                        }).then(function (response) {
                            if(response.data.length){
                           // console.log(response)
                            swal(
                                    'El Valor ya Existe',
                                    'Ingresa un dato Diferente',
                                    'error'
                            )                    }
                            else{
                                    swal(
                                    'Registrado correctamente',
                                    )

                                me.selectCuentaNivel3();
                                

                            }

                            
                        }).catch(function (error) {
                            //console.log(error.response);
                            swal(
                                    'El Valor ya Existe',
                                    'Ingresa un dato Diferente',
                                    'error'
                            )
                        });
                        this.valorcuenta='';
                        this.nombrecuenta='';

                        break;
                    }
                    case 'Cuenta Nivel 4':
                    {
                        axios.post('/con_cuentan4/registrar',{
                            'codn3':this.codn3,
                            'valorn4': valordecuenta,
                            'nombrecuentan4': this.nombrecuenta
                            
                        }).then(function (response) {
                            if(response.data.length){
                            //console.log(response)
                            swal(
                                    'El Valor ya Existe',
                                    'Ingresa un dato Diferente',
                                    'error'
                            )                    }
                            else{
                                    swal(
                                    'Registrado correctamente',
                                    )

                                me.selectCuentaNivel4();
                                

                            }

                            
                        }).catch(function (error) {
                            //console.log(error.response);
                            swal(
                                    'El Valor ya Existe',
                                    'Ingresa un dato Diferente',
                                    'error'
                            )
                        });
                        this.valorcuenta='';
                        this.nombrecuenta='';


                        break;
                    }
                    case 'Cuenta Nivel 5':
                    {
                        axios.post('/con_cuentan5/registrar',{
                            'codn4':this.codn4,
                            'valorn5': valordecuenta,
                            'nombrecuentan5': this.nombrecuenta
                            
                        }).then(function (response) {
                            if(response.data.length){
                            //console.log(response)
                            swal(
                                    'El Valor ya Existe',
                                    'Ingresa un dato Diferente',
                                    'error'
                            )                    }
                            else{
                                    swal(
                                    'Registrado correctamente',
                                    )

                                me.selectCuentaNivel5();
                                

                            }

                            
                        }).catch(function (error) {
                            //console.log(error.response);
                            swal(
                                    'El Valor ya Existe',
                                    'Ingresa un dato Diferente',
                                    'error'
                            )
                        });
                        this.valorcuenta='';
                        this.nombrecuenta='';


                        break;
                    }
                    case 'Cuenta':
                    {
                       // console.log(this.codcuentan1+this.codcuentan2+this.codcuentan3+this.codcuentan4+' descripcion'+this.descripcioncuenta);

                        axios.post('/con_cuentas/registrar',{
                            'codn5':this.codn5,
                            'valorcuenta': valordecuenta,
                            'nombrecuenta': this.nombrecuenta,
                            'codcuenta':this.codcuentan1+this.codcuentan2+this.codcuentan3+this.codcuentan4+this.codcuentan5+valordecuenta,
                            'descripcion':this.descripcioncuenta,
                            'idmoneda':this.idmoneda
                            
                        }).then(function (response) {
                            if(response.data.length){
                            //console.log(response)
                            swal(
                                    'El Valor ya Existe',
                                    'Ingresa un dato Diferente',
                                    'error'
                            )                    }
                            else{
                                    swal(
                                    'Registrado correctamente',
                                    )

                                me.selectCuentaNivelss();
                                

                            }
                        }).catch(function (error) {
                            //console.log(error.response);
                            swal(
                                    'El Valor ya Existe',
                                    'Ingresa un dato Diferente',
                                    'error'
                            )
                        });
                        this.valorcuenta='';
                        this.nombrecuenta='';
                        this.descripcioncuenta='';
                        this.idmoneda=''


                        break;
                    }
                    default:
                        break;
                }
            },
            actualizarcuenta(valordecuenta,nombredecuenta){
                /*if (this.validarTipocomprobante()){
                    return;
                }*/
                //console.log(valordecuenta,this.nombrecuenta+'valores');
                let me = this;
                switch (nombredecuenta) {
                    
                    case 'Cuenta Nivel 4':
                    {
                        axios.put('/con_cuentan4/actualizar',{
                            'valorn4': valordecuenta,
                            'nomcuentan4': me.nombrecuenta,
                            'codn4':me.idcuenta
                            
                        }).then(function (response) {
                            if(response.data.length){
                           // console.log(response)
                            swal(
                                    'El Valor ya Existe',
                                    'Ingresa un dato Diferente',
                                    'error'
                            )                    }
                            else{
                                    swal(
                                    'actualizado correctamente',
                                    )

                                me.selectCuentaNivel4();
                                me.tipoAccion=1;
                                

                            }

                            
                        }).catch(function (error) {
                            //console.log(error.response);
                            swal(
                                    'El Valor ya Existe',
                                    'Ingresa un dato Diferente',
                                    'error'
                            )
                        });
                        this.valorcuenta='';
                        this.nombrecuenta='';


                        break;
                    }
                    case 'Cuenta':
                    {
                       // console.log(this.codcuentan1+this.codcuentan2+this.codcuentan3+this.codcuentan4);

                        axios.put('/con_cuentas/actualizar',{
                            //'valorcuenta': valordecuenta,
                            'nomcuenta': me.nombrecuenta,
                            'idcuenta':me.idcuenta,
                            //'codcuenta':this.codcuentan1+this.codcuentan2+this.codcuentan3+this.codcuentan4,
                            'descripcion':this.descripcioncuenta,
                            'idmoneda':this.idmoneda,
                            
                        }).then(function (response) {
                            if(response.data.length){
                           // console.log(response)
                            swal(
                                    'El Valor ya Existe',
                                    'Ingresa un dato Diferente',
                                    'error'
                            )                    }
                            else{
                                    swal(
                                    'actualizado correctamente',
                                    )

                                me.selectCuentaNivelss();
                                me.tipoAccion=1;
                                

                            } 

                            
                        }).catch(function (error) {
                            //console.log(error.response);
                            swal(
                                    'El Valor ya Existe',
                                    'Ingresa un dato Diferente',
                                    'error'
                            )
                        });
                        this.valorcuenta='';
                        this.nombrecuenta='';
                        this.descripcioncuenta='';
                        this.idmoneda='';



                        break;
                    }
                    default:
                        break;
                }
            },
            //cuentann.idcuenta,cuentann.valorcuenta,cuentann.nomcuenta,nomcuenta,cuentann.descripcion
            //botonactualizarcuenta(cuentan4.codn4,cuentan4.valorn4,cuentan4.nomcuenta4,nomcuenta)
            botonactualizarcuenta(idcuenta,valorcuenta,nombrecuenta,nomcuenta,descripcion,idmoneda){
                //console.log(data);
                let me = this;
                
               switch (nomcuenta) {
                    case 'Cuenta Nivel 4':
                    {
                        me.tipoAccion=2;
                        me.valorcuenta=valorcuenta;
                        me.nombrecuenta=nombrecuenta;
                        me.idcuenta=idcuenta
                        

                        break;
                    }
                    case 'Cuenta':
                    {
                        me.tipoAccion=2;
                        me.valorcuenta=valorcuenta;
                        me.nombrecuenta=nombrecuenta;
                        me.descripcioncuenta=descripcion;
                        me.idcuenta=idcuenta;
                        me.idmoneda=idmoneda;
                        
                        break;
                    }
                    
                }
            },

            
            desactivarcuenta(nombredecuenta,idcuenta){
               // console.log(nombredecuenta,idcuenta+' valores desactivar')
               swal({
                title: 'Esta seguro de desactivar esta Cuenta?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;
                    if(nombredecuenta=='Cuenta Nivel 4')
                    {
                        //console.log('entra cuenta nivel 4')
                        axios.put('/con_cuentan4/desactivar',{
                            'idcuenta': idcuenta
                        }).then(function (response) {
                            me.selectCuentaNivel4();
                            swal(
                            'Desactivado!',
                            'El registro ha sido desactivado con éxito.',
                            'success'
                            )
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }
                    else
                    {   //console.log('entra else')
                        axios.put('/con_cuentas/desactivar',{
                            'idcuenta': idcuenta
                        }).then(function (response) {
                            me.selectCuentaNivelss();
                            swal(
                            'Desactivado!',
                            'El registro ha sido desactivado con éxito.',
                            'success'
                            )
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
            selectmoneda(){
                let me=this;
                var url= '/par_moneda/selectMoneda';
                axios.get(url).then(function (response) {
                    //console.log(response.data);
                    var respuesta= response.data;
                    me.arrayMonedas = respuesta.monedas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            activarcuenta(nombredecuenta,idcuenta){
               swal({
                title: 'Esta seguro de activar esta cuenta?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) 
                {
                    let me = this;
                    var url="";
                    if(nombredecuenta=='Cuenta Nivel 4')
                    {    axios.put('/con_cuentan4/activar',{
                            'idcuenta': idcuenta
                        }).then(function (response) {
                            
                            me.selectCuentaNivel5();
                            swal(
                            'Activado!',
                            'El registro ha sido activado con éxito.',
                            'success'
                            )
                        }).catch(function (error) {
                            console.log(error);
                        });

                    }
                    else
                    {    
                        axios.put('/con_cuentas/activar',{
                            'idcuenta': idcuenta
                        }).then(function (response) {
                            
                            me.selectCuentaNivelss();
                            swal(
                            'Activado!',
                            'El registro ha sido activado con éxito.',
                            'success'
                            )
                        }).catch(function (error) {
                            console.log(error);
                        });

                    }
                       
                                        
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
            buscarcuenta(){
                let me=this;
                me.arrayBuscarCuentas=[];
                var url= '/con_cuentas/selectBuscarcuenta?buscar='+me.buscador;
                //console.log(url);
                
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayBuscarCuentas = respuesta.cuentas;
                    //console.log(me.arrayBuscarCuentas)

                })
                .catch(function (error) {
                    console.log(error);
                });
            },
             getPermisos() {
            var url= '/adm_role/selectPermisos?idmodulo=' + this.idmodulo+'&idventanamodulo='+this.idventanamodulo;
                let me = this; 
                axios.get(url).then(function (response) {
                    if(response.data.datapermiso.length!=0)
                    {   
                        var respuesta = response.data.datapermiso[0].permisos;  
                        me.arrayPermisosIn = JSON.parse((respuesta)); //console.log(me.arrayPermisosIn);
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
            this.listarCuentaNivel1(1);
            this.selectCuentanivel1();
            this.selectmoneda();
            this.getRutasReports();
            this.getPermisos();
        }
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-style: italic;
    }
</style>
