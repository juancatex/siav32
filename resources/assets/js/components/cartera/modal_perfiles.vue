<template>
   <main class="main">  
    <div class="modal fade " tabindex="-1"  role="dialog"  style="z-index: 1600;" aria-hidden="true" id="modalperfiles" >
            <div class="modal-dialog modal-primary" role="document">
            <div class="modal-content animated fadeIn"> 
                    <div class="modal-header"> 
                        <h4 class="modal-title" v-text="nombreperfilmaestro"></h4>
                        <button type="button" class="close" aria-hidden="true" aria-label="Close" @click="cerrarmodalperfiles">
                            <span aria-hidden="true">×</span></button>
                    </div> 

                    <div class="modal-body">
                            
                        <div class="col-md-12">
                            <div class="row" v-if="data_producto_main&&refreshh">  
                                   
                                    <button v-if="data_producto_main.desembolso_perfil_refi!=0" type="button" :class="validateperfil(data_producto_main.desembolso_perfil_refi)?'btn-success':'btn-primary'" class="botonperfilespecial btn btn-sm btn-block" @click="set_perfil(data_producto_main,data_producto_main.desembolso_perfil_refi,true)">Perfil de Desembolso por Refinanciamiento<br><b style="color: red;font-size: 15px;font-weight: 800;">( Expresado en Bolivianos )</b></button>
                                    <button v-if="data_producto_main.desembolso_perfil_garante!=0" type="button" :class="validateperfil(data_producto_main.desembolso_perfil_garante)?'btn-success':'btn-primary'" class="botonperfilespecial btn  btn-sm btn-block" @click="set_perfil(data_producto_main,data_producto_main.desembolso_perfil_garante,true)">Perfil de Desembolso por Activación a garante/titular<br><b style="color: red;font-size: 15px;font-weight: 800;">( Expresado en Bolivianos )</b></button>
                                    <button v-if="data_producto_main.cobranza_perfil_refi!=0" type="button" :class="validateperfil(data_producto_main.cobranza_perfil_refi)?'btn-success':'btn-primary'" class="botonperfilespecial btn  btn-sm btn-block" @click="set_perfil(data_producto_main,data_producto_main.cobranza_perfil_refi)">Perfil de Cobranza Refinanciamiento</button>
                                    <button v-if="data_producto_main.cobranza_perfil_garante!=0" type="button" :class="validateperfil(data_producto_main.cobranza_perfil_garante)?'btn-success':'btn-primary'" class="botonperfilespecial btn  btn-sm btn-block" @click="set_perfil(data_producto_main,data_producto_main.cobranza_perfil_garante)">Perfil de Cobranza Activación a garante/titular</button>

                                    <button v-if="data_producto_main.desembolso_perfil!=0" type="button" :class="validateperfil(data_producto_main.desembolso_perfil)?'btn-success':'btn-primary'" class="botonperfilespecial btn  btn-sm btn-block" @click="set_perfil(data_producto_main,data_producto_main.desembolso_perfil)">Perfil de Desembolso</button>
                                    <button v-if="data_producto_main.cobranza_perfil!=0" type="button" :class="validateperfil(data_producto_main.cobranza_perfil)?'btn-success':'btn-primary'" class="botonperfilespecial btn  btn-sm btn-block" @click="set_perfil(data_producto_main,data_producto_main.cobranza_perfil)">Perfil de Cobranza Manual</button>
                                    <button v-if="data_producto_main.cobranza_perfil_ascii!=0" type="button" :class="validateperfil(data_producto_main.cobranza_perfil_ascii)?'btn-success':'btn-primary'" class="botonperfilespecial btn  btn-sm btn-block" @click="set_perfil(data_producto_main,data_producto_main.cobranza_perfil_ascii)">Perfil de Cobranza ASCII</button>
                                    <button v-if="data_producto_main.cobranza_perfil_acreedor!=0" type="button" :class="validateperfil(data_producto_main.cobranza_perfil_acreedor)?'btn-success':'btn-primary'" class="botonperfilespecial btn  btn-sm btn-block" @click="set_perfil(data_producto_main,data_producto_main.cobranza_perfil_acreedor)">Perfil de Cobranza Acreedor</button>
                                    <button v-if="data_producto_main.cobranza_perfil_daro!=0" type="button" :class="validateperfil(data_producto_main.cobranza_perfil_daro)?'btn-success':'btn-primary'" class="botonperfilespecial btn  btn-sm btn-block" @click="set_perfil(data_producto_main,data_producto_main.cobranza_perfil_daro)">Perfil de Cobranza Daaro</button>
                                    <button v-if="data_producto_main.perfil_cambio_estado!=0" type="button" :class="validateperfil(data_producto_main.perfil_cambio_estado)?'btn-success':'btn-primary'" class="botonperfilespecial btn  btn-sm btn-block" @click="set_perfil(data_producto_main,data_producto_main.perfil_cambio_estado)">Perfil de Cambio de Estado (Vigente a Vencido)</button>
                                    <button v-if="data_producto_main.perfil_cambio_estado_mora!=0" type="button" :class="validateperfil(data_producto_main.perfil_cambio_estado_mora)?'btn-success':'btn-primary'" class="botonperfilespecial btn  btn-sm btn-block" @click="set_perfil(data_producto_main,data_producto_main.perfil_cambio_estado_mora)">Perfil de Cambio de Estado (Vencido a Vigente)</button>
                                     
                            </div>
                        </div>
                        
                    </div>  

                    <div class="modal-footer" v-if="refreshh">  
                    <button type="button" class="btn btn-secondary" @click="cerrarmodalperfiles">Cerrar</button>     
                    <button v-if="buttonsave" type="button" class="btn btn-primary" @click="save()">Guardar</button>    
                    </div>    
            </div>
        </div>
    </div>
   <!-- <view-cargos  @cerrarvue="cerrarModalvue" ref="view"></view-cargos> -->
    <cargos @cerrarvueprincipalperfiles="cerrarModalvue"  @savePerfil="saveperfilselected"  ref="ModalCargos" :idmodulo="idmoduloin"></cargos>
   </main>
</template>

<script> 
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
         props: ['idmodulo'],
        data (){
            return {
                    nombreperfilmaestro:'',
                    modalperfilesclass:null,
                    idmoduloin:this.idmodulo,
                    perfilesfinales:[],
                    data_producto_main:null ,
                    mapDataGeneral:new Map() ,
                    refreshh:true ,
                    buttonsave:false            
            }
        } ,
          watch: {
              'data_producto_main.seriemap': function () { 
               this.buttonsave= (((this.data_producto_main.desembolso_perfil_refi != 0) ? this.mapDataGeneral.has(this.data_producto_main.desembolso_perfil_refi) : true) &&
                          ((this.data_producto_main.cobranza_perfil_refi != 0) ? this.mapDataGeneral.has(this.data_producto_main.cobranza_perfil_refi) : true) &&
                          ((this.data_producto_main.desembolso_perfil_garante != 0) ? this.mapDataGeneral.has(this.data_producto_main.desembolso_perfil_garante) : true) &&
                          ((this.data_producto_main.cobranza_perfil_garante != 0) ? this.mapDataGeneral.has(this.data_producto_main.cobranza_perfil_garante) : true) &&
                          ((this.data_producto_main.desembolso_perfil != 0) ? this.mapDataGeneral.has(this.data_producto_main.desembolso_perfil) : true) &&
                          ((this.data_producto_main.cobranza_perfil != 0) ? this.mapDataGeneral.has(this.data_producto_main.cobranza_perfil) : true) &&
                          ((this.data_producto_main.cobranza_perfil_ascii != 0) ? this.mapDataGeneral.has(this.data_producto_main.cobranza_perfil_ascii) : true) &&
                          ((this.data_producto_main.cobranza_perfil_acreedor != 0) ? this.mapDataGeneral.has(this.data_producto_main.cobranza_perfil_acreedor) : true) &&
                          ((this.data_producto_main.perfil_cambio_estado != 0) ? this.mapDataGeneral.has(this.data_producto_main.perfil_cambio_estado) : true) &&
                          ((this.data_producto_main.perfil_cambio_estado_mora != 0) ? this.mapDataGeneral.has(this.data_producto_main.perfil_cambio_estado_mora) : true) &&
                          ((this.data_producto_main.cobranza_perfil_daro != 0) ? this.mapDataGeneral.has(this.data_producto_main.cobranza_perfil_daro) : true));
                }
          },
        methods : { 
            save(){
              let me=this; 
               axios.put('/par_producto/actualizar/map', {
                                'idproducto': me.data_producto_main.idproducto,
                                'map': me.data_producto_main.seriemap
                            }).then(function (response) { 
                                me.modalperfilesclass.closeModal('modalperfiles');
                                me.$emit('cerrarvueprincipal');
                                swal("¡Se guardo los datos correctamente!", "", "success");
                                $(".swal2-modal").css('z-index', '2000');
                                $(".swal2-container").css('z-index', '2000');
                            }).catch(function (error) {
                                console.log('error:',error);
                            });
            }, 
             validateperfil(idperfil){
              return  this.mapDataGeneral.has(idperfil);
            },
             saveperfilselected(idmaestro,valoresperfil){
                this.refreshh=false;
                this.mapDataGeneral.set(idmaestro, JSON.stringify(Array.from(valoresperfil)));
                this.modalperfilesclass.openModal('modalperfiles');  
                this.refreshh=true;
                this.data_producto_main.seriemap=JSON.stringify(Array.from(this.mapDataGeneral));
              },
              cerrarModalvue(){ 
                this.modalperfilesclass.openModal('modalperfiles'); 
              },
             cerrarmodalperfiles(){
              this.modalperfilesclass.closeModal('modalperfiles');
                this.$emit('cerrarvueprincipal');
              },
            showVueperfiles(id){ 
              this.nombreperfilmaestro='Perfiles: '+id.nomproducto;  
              this.data_producto_main=id;
            //  console.log(this.data_producto_main); 
              this.modalperfilesclass.openModal('modalperfiles'); 
              
            } ,
             set_perfil(id_in,perfil,exp=false){
                   this.modalperfilesclass.closeModal('modalperfiles'); 
                   this.$refs.ModalCargos.showVuecargos(id_in,perfil,exp);  
                }
        } ,
        mounted() {
            this.mapDataGeneral=new Map();
             this.modalperfilesclass=new _pl.Modals();
             this.modalperfilesclass.addModal('modalperfiles');  
        }
    }
</script>
<style> 
.botonperfilespecial{
        font-size: 17px;
}
  .hoverdrop { background:#ffa70770 !important; } 
  .hoverdropfunctions { background:#e8e30ebf !important; padding-right: 37px !important;} 
	
 
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-style: italic;
    }
 
   .divdrag{
    border: 1px solid gray;
    min-width: 60%;
    /* max-width: 290px; */
    padding-right: 37px !important;
    padding-left: 7px !important;
    min-height: 30px;
    display: inline-block;
    vertical-align: middle;
    background: white;
     
   } 
   
    
   .asig{
    display: unset;
    vertical-align: middle;
    text-align: center;
   margin: 1px 6px 1px 1px;
   } 
   span[v-data="funcion"] div[id="values"]{
    border-right: 2px solid;
    border-left: 2px solid;
    padding-left: 3px;
    padding-right: 3px;
    border-radius: 6px;
    margin: 0 4px 0 1px;
    min-width: 31px;
    min-height: 27px;
    display: inline-block;
    vertical-align: middle;
    background: white;
    text-align: left;
   } 
    span[v-data="inputSi"] div[id="values"]{
    border-right: 2px solid;
    border-left: 2px solid;
    padding-left: 3px;
    padding-right: 3px;
    border-radius: 6px;
    margin: 0 4px 0 1px;
    min-width: 31px;
    min-height: 27px;
    display: inline-block;
    vertical-align: middle;
    background: white;
    text-align: left;
   } 
    span[v-data="input"] div[id="values"]{ 
    display: inline-block;
    border: 2px solid; 
    padding-left: 3px;
    padding-right: 3px;
    border-radius: 6px;
    margin: 4px;
    min-width: 21px;
    
    min-height:30px; 
    vertical-align: middle;
    background: white;
    text-align: left;
   }
  span[v-data="inputSi"] input{
       border-color: transparent;
       width: 50px;
       text-align: center;
    }
    span[v-data="input"] input{
       border-color: transparent;
       width: 50px;
       text-align: center;
    }
    div[v-data="perfil"],span[v-data="perfil"]{
    min-width: 20px;
    border-radius: 6px;
    text-align: center;
    font-weight: bold;
    vertical-align: middle;
    border: 1px solid; 
    display: inline-block;
    background-color: white;
     margin: 2px; 
    cursor: move;   
    cursor: -webkit-grab;  
    cursor:    -moz-grab;  
    cursor:         grab;
    padding: 0px 6px 0 6px;
    font-size: 13px;
   }
   div[v-data="perfil"][v-tipo="1"]{
    min-width: 30px;
    border-radius: 6px;
    text-align: center;
    font-weight: bold;
    vertical-align: middle;
    border: 1px solid; 
    display: inline-block;
    background-color: white;
     margin: 2px; 
    cursor: move;   
    cursor: -webkit-grab;  
    cursor:    -moz-grab;  
    cursor:         grab;
    padding: 0px 6px 0 6px;
    font-size: 18px;
   }
    div[v-tipo="titulo"]{
    min-width: 30px;
    border-radius: 6px;
    text-align: center;
    font-weight: bold;
    vertical-align: middle;
    border: 1px solid; 
    display: inline-block;
    background-color: white;
    margin: 2px;  
    margin-right: 6px;
    padding: 0px 6px 0 6px;
    font-size: 18px;
   }
  
   span[v-data="datain"]{
      min-width: 20px;
    border-radius: 6px;
    text-align: center;
    vertical-align: middle;
    border: 2px solid;
    display: inline-block;
    background-color: white;
    margin: 2px; 
    cursor: default;
    padding: 0 4px 0 4px;
    font-size: 10px;
    font-weight: 700; 
   }
span[v-data="operador"]{
    text-align: center;
    padding: 3px;
    vertical-align: middle;
    font-weight: 700;
    font-size: 13px;
}  
/* ------------------------------------------------------------- */
 
    
.tooltipdiv {
    position: relative;
    display: inline-block; 
}

.tooltipdiv .tooltiptext {
    visibility: hidden;
    position: absolute;
    width: 120px;
    background-color: #555;
    color: #fff;
    text-align: center;
    font-size: 12px;
    padding: 6px 6px;
    border-radius: 6px;
    z-index: 1;
    opacity: 0;
    transition: opacity 0.3s;
}

.tooltipdiv:hover .tooltiptext {
    visibility: visible;
    opacity: 1;
}

.tooltip-right {
  top: -5px;
  left: 125%;  
}

.tooltip-right::after {
    content: "";
    position: absolute;
    top: 50%;
    right: 100%;
    margin-top: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: transparent #555 transparent transparent;
}

.tooltip-bottom {
  top: 135%;
  left: 50%;
  margin-left: -60px;
}

.tooltip-bottom::after {
    content: "";
    position: absolute;
    bottom: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: transparent transparent #555 transparent;
}

.tooltip-top {
  bottom: 125%;
  left: 50%;  
  margin-left: -60px;
}

.tooltip-top::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #555 transparent transparent transparent;
}

.tooltip-left {
  top: -5px;
  bottom:auto;
  right: 128%;  
}
.tooltip-left::after {
    content: "";
    position: absolute;
    top: 50%;
    left: 100%;
    margin-top: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: transparent transparent transparent #555;
}

.contenedor:not(:empty){
    border: 1px solid gray;
    padding: 6px 0px 6px 6px;
    height :600px;
    /* margin-bottom: 10px;
    margin-top: 10px; */
}

.divdrag:empty:not(:focus):before{
    content:'Ingrese la formula';
        color: #808080a8;
}

.divdrag.noF:empty:not(:focus):before{
    content:'Debe ingresar la formula para este parametro';
    color: #e60000f0;
}
.noF{
border-color: red;
-webkit-box-shadow: 0px 0px 4px -1px rgba(255,0,0,1);
-moz-box-shadow: 0px 0px 4px -1px rgba(255,0,0,1);
box-shadow: 0px 0px 4px -1px rgba(255,0,0,1);
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
.handle{
    cursor: move; 
     /* z-index: 100000; */
}
</style>
