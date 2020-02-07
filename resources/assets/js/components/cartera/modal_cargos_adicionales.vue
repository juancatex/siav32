<template>
            <main class="main">  
                <div class="modal fade " tabindex="-1"  role="dialog"  style="z-index: 1600;" aria-hidden="true" id="primarymodal" >
                     <div class="modal-dialog modal-primary modal-xl" role="document">
                        <div class="modal-content animated fadeIn"> 
                                <div class="modal-header"> 
                                    <h4 class="modal-title" v-text="nombreperfilmaestro"></h4>
                                    <button type="button" class="close" aria-hidden="true" aria-label="Close" @click="classModal2.closeModal('primarymodal')"><span aria-hidden="true">×</span></button>
                                </div> 

                                <div class="modal-body">
                                     
                                    <div class="col-md-12">
                                     <div class="row contenedor" id="contenedor">  
                                    </div></div>
                                   
                                </div>  

                                <div class="modal-footer">  
                                <button type="button" class="btn btn-secondary" @click="cerrarmodal_principal()">Cerrar</button>     
                                <button type="button" class="btn btn-secondary" @click="mostrarelement()">Probar</button>         
                                <button type="button" class="btn btn-primary" @click="save()">Guardar</button>    
                                </div>    
                        </div>
                    </div>
                </div>
   <view-cargos  @cerrarvue="cerrarModalvue" ref="view"></view-cargos>
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
                arrayPermisos:{Nuevo:0,Edición:0},
               classModal2:null,
               idproducto:'',
               nombreperfilmaestro:'', 
               tipocambio:0,
               idmaestro:0,
               codmoneda:'',
               nombreproducto:'', 
               mapData:new Map(),
               mapDataGeneral:new Map()
                
            }
        } ,
        methods : {
            save(){
             let me=this; 
                this.validateData(this.mapData).then(function(responses) { 
                    if (responses) {

                        try {
                            _pl._mmf2251_3325(me.mapData);
                           // me.mapDataGeneral.set(me.idmaestro, JSON.stringify(Array.from(me.mapData)));
                            // axios.put('/par_producto/actualizar/map', {
                            //     'idproducto': me.idproducto,
                            //     'map': JSON.stringify(Array.from(me.mapDataGeneral))
                            // }).then(function (response) {
                            //     me.$emit('cerrarvueprincipal');
                            //     me.classModal2.closeModal('primarymodal');
                            //     swal("¡Se registro los datos correctamente!", "", "success");
                            //     $(".swal2-modal").css('z-index', '2000');
                            //     $(".swal2-container").css('z-index', '2000');
                            // }).catch(function (error) {
                            //     console.log(error);
                            // });
                                me.$emit('savePerfil',me.idmaestro,me.mapData);
                                me.classModal2.closeModal('primarymodal');
                                swal("¡Se registro los datos correctamente!", "", "success");
                                $(".swal2-modal").css('z-index', '2000');
                                $(".swal2-container").css('z-index', '2000');
                        } catch (error) {
                            console.error(error.stack);
                            swal("¡Existe un error en la compilacion de la formula!", error.name + ' <b>:</b><span style="font-weight: 400;"> ' + error.message + '</span>', "error").then((result) => {
                                me.$emit('cerrarvueprincipal');
                            })
                            $(".swal2-modal").css('z-index', '2000');
                            $(".swal2-container").css('z-index', '2000');

                        }




                    } else {
                        $('.noF').effect("pulsate", "slow");
                        swal("¡Debe introducir la formula a todos los parametros del perfil!", '', "error");
                        $(".swal2-modal").css('z-index', '2000');
                        $(".swal2-container").css('z-index', '2000');
                    }
                }); 
 
            }, 
             cerrarModalvue(){   
                this.classModal2.openModal('primarymodal'); 
              },
            cerrarmodal_principal(){ 
                this.classModal2.closeModal('primarymodal');
                this.$emit('cerrarvueprincipal');
            },
             mostrarelement(mapinto=this.mapData,nombre=this.nombreperfilmaestro,tipoc=this.tipocambio,cod=this.codmoneda,name=this.nombreproducto){
                 let me=this; 
                this.validateData(this.mapData).then(function(responses) { 
                    if(responses){ 
                        me.classModal2.closeModal('primarymodal');  
                        me.$refs.view.showVue(mapinto,nombre,tipoc,cod,name);
                        // console.log(mapinto); 
                        // var prueba=new Map();
                        // prueba.set(6,JSON.stringify(Array.from(mapinto)));
                        // prueba.set(7,JSON.stringify(Array.from(mapinto)));
                        // var serializedMap = JSON.stringify(Array.from(prueba));
                        // console.log('Map as array: '+serializedMap); 
                        // /* desserealizar */
                        // var map = new Map(JSON.parse(serializedMap))
                        // console.log(map); 
                        // console.log('--------------------- estos son los campos ---------------------'); 
                        // console.log(new Map(JSON.parse(map.get(6)))); 
                        // console.log(new Map(JSON.parse(map.get(7)))); 

                    } else{
                       $('.noF').effect( "pulsate", "slow" );
                       swal("¡Debe introducir la formula a todos los parametros del perfil!",'', "error"); 
                       $(".swal2-modal").css('z-index', '2000'); 
                       $(".swal2-container").css('z-index', '2000');
                    }
                }); 
            },
            async validateData(map){
             let responses = true; 
                for (var [key, value] of map)  {  
                    console.log(value);
                    var item=$('.itemrow[id="'+key+'"] .divdrag[id="'+value.key+'"]'); 
                    if(item.siblings('div[v-type="checkconten"]').find('input[v-type="check"]').is(':checked')){  value.adi=1; }else{value.adi=0;}
                    if(item.contents().length==0){ item.addClass('noF');responses = false; 
                    }else{ value.idpro=this.idproducto;value.formula=_pl._perff_00125(item);
                    value.item=$('.itemrow[id="'+key+'"]')[0].outerHTML; map.set(key,value);} 
                 }  
                return responses;
            }, 
           getperfilDetalle(id){
               if(id!=null){ 
                   let me=this;  
                    var url= '/con_perfilcuentadetalle/selectPerfilcuentadetalleProducto?idmaestro='+ id;
                        axios.get(url).then(function (response) {   
                            _pl.generaperfil(me.mapData,response.data.perfilcuentadetalles,'contenedor');
                        }).catch(function (response) {
                            console.log(response);
                        });
                   } 
           }, listar(id){
                let me=this; 
                this.nombreperfilmaestro='';
                var url= '/con_perfilcuentamaestro/getnamePerfilcuentamaestro?idmodulo='+ this.idmodulo+'&idperfil='+id;
                axios.get(url).then(function (response) { 
                    me.nombreperfilmaestro=response.data.perfil[0].nomperfil; 
                })
                .catch(function (response) {
                    console.log(response);
                });
            } ,
            showVuecargos(id,idperfilmaestro){ 
                $('#contenedor').empty(); 
              this.idmaestro=idperfilmaestro;
              this.listar(idperfilmaestro);  
              this.idproducto=id.idproducto;
              this.tipocambio=id.tipocambio;
              this.codmoneda=id.codmoneda;
              this.nombreproducto=id.nomproducto;   
              this.mapDataGeneral = new Map(JSON.parse(id.serializedmap));
              if(this.mapDataGeneral.has(idperfilmaestro)){
               this.mapData = new Map(JSON.parse(this.mapDataGeneral.get(idperfilmaestro)));
              }else{
               this.mapData.clear(); 
              }  
              this.classModal2.openModal('primarymodal');  
                if(this.mapData.size>0){
                   _pl._mff82sdf_mjd(this.mapData,'contenedor');
                }else{
                  this.getperfilDetalle(idperfilmaestro); 
                } 
            } 
        } ,
        mounted() {
             this.classModal2=new _pl.Modals();
             this.classModal2.addModal('primarymodal');  
        }
    }
</script>
<style> 
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
