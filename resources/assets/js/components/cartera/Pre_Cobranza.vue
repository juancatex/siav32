<template>
    <main class="main">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
      

<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Cobranza Manual</div>
                    <div class="card-body">
                        <div class="form-group row" style="justify-content: flex-end;">


                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Cobranza Por ASCII</div>
                        <div class="card-body">
                            <div class="form-group">
                                   <excelReader col_codigo="COP" :remove="removef" @array_Files_Data="datasArray"></excelReader>
                            </div>
                            <div v-if="tam>0" class="col-md-12" style="text-align: center;    display: inline-block;">
                                <button class="btn btn-outline-success active" type="button"
                                @click="procesarFiles" >Procesar</button>
                            </div>
                        </div>
                </div>
            </div>
        </div>

    </div>
</div>

        <div class="modal fade " tabindex="-1" role="dialog" style="z-index: 1600;" aria-hidden="true"
            id="primarymodal">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content animated fadeIn">
                    <div class="modal-header">
                        <h4 class="modal-title" ></h4>
                        <button type="button" class="close" aria-hidden="true" aria-label="Close"
                            @click="classModal.closeModal('primarymodal')"><span aria-hidden="true">×</span></button>
                    </div>

                    <div class="modal-body-modalPlugin">

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            @click="classModal.closeModal('primarymodal')">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
 <cobranza_ascii @cerrarvue="cerrarModalvue" ref="cobranza_ascii"></cobranza_ascii>
    </main>
</template>

<script>
 
    export default {
        props: ['idmodulo','object','permisos'],
        data (){
            return {
             classModal:null,  
             datasFiles:[],  
             idmodulomain: this.idmodulo,
             removef:(valuein)=>{return valuein.CODIGO_CONCEPTO != '124'}
                                 
            }
        },  
        computed:{
            tam:function(){ 
                return this.datasFiles.length;
            }
        },
        methods : { 
            cerrarModalvue(){

            },
          processing(arraysql,productos_map) {  
              let me = this; 
              var sumabefore=0;
                _.forEach(me.datasFiles, function(value,key) { 
                      sumabefore=_pl.redondeo_valor((sumabefore+value.aporte), 2, false);
                      me.datasFiles[key].totaloriginal=me.datasFiles[key].aporte;
                });

            
             _.forEach(arraysql, function(value, key) {
                     var out = _.findIndex(me.datasFiles, (o) => {return (o.numpapeleta === value.numpapeleta); });
                     if(out>=0){ 
                         if((me.datasFiles[out].aporte-value.cut2)>=0){
                                me.datasFiles[out].aporte=_pl.redondeo_valor((me.datasFiles[out].aporte-value.cut2), 2, false); 
                                
                               if(value.inn > (value.cut-value.car-value.indi)){
                                arraysql[key].estatus=2; 

                                var interesnew=_pl.redondeo_valor((value.cut-value.car-value.indi),2); 
                                var intermedio=_pl.redondeo_valor((value.inn/value.di),2);   
                                var dias_new= Math.round(interesnew/intermedio);                       
                                arraysql[key].newdata=[{idprestamo:value.idprestamo,pe:value.pe,fp:value.fp,
                                di:parseInt(dias_new),
                                am:0,
                                in:interesnew,
                                indi:value.indi,
                                car:value.car,
                                cut:value.cut,
                                ca:value.ca,
                                ca_an:value.ca_an,
                                cuota:value.cuota,
                                send_ascii:value.send_ascii,
                                file:value.file
                                },value.idplan,{
                                di:(parseInt(value.di-dias_new)>0)?parseInt(value.di-dias_new):1,
                                in:_pl.redondeo_valor((value.inn-interesnew),2),
                                indi:0, 
                                send_ascii:0,
                                file:''
                                },value.idprestamo]; 
                                var outcobranza=_pl._mf36265_25421(productos_map.get(value.idproducto),0,value.cut,interesnew,value.indi,value.monto,'ascii',value.plazo,value.cuota,value.tipocambio); 
                                arraysql[key].cobranza=outcobranza;
                               }else{
                             // var outcobranza=_pl._mf36265_25421(productos_map.get(value.idproducto),value.am,value.cut,value.inn,value.indi,value.monto,value.numpapeleta,value.plazo,value.cuota,value.tipocambio); 
                               var outcobranza=_pl._mf36265_25421(productos_map.get(value.idproducto),value.am,value.cut,value.inn,value.indi,value.monto,'ascii',value.plazo,value.cuota,value.tipocambio); 
                                arraysql[key].cobranza=outcobranza;  
                                arraysql[key].estatus=1;  
                                
                               }

                              if(!_.has(me.datasFiles[out], 'plans')){
                                    me.datasFiles[out].plans= [value.idplan];
                                }else{
                                    me.datasFiles[out].plans.push(value.idplan);
                                }
                               
                                if(!_.has(me.datasFiles[out], 'plans_total')){
                                    me.datasFiles[out].plans_total= value.cut2;
                                }else{
                                    var countsuma=me.datasFiles[out].plans_total;
                                    me.datasFiles[out].plans_total=_pl.redondeo_valor((countsuma+value.cut2), 2, false);
                                }
                        }else{
                         arraysql[key].estatus=0;   
                        }
                     }else{
                         arraysql[key].estatus=0;
                     }
             });

              console.log("arraysql",arraysql);
              console.log(me.datasFiles);  
                var sumaafter=0;
                _.forEach(me.datasFiles, function(value) {   
                       sumaafter=_pl.redondeo_valor((sumaafter+value.aporte), 2, false);
                });
                    
                var sumacobrado=0;

                // var productos = new Map();
                var productos = [];
                var moras=[];

                _.forEach(arraysql, function(value) { 
                      if(value.estatus==1){ 
                          
                           sumacobrado=_pl.redondeo_valor((sumacobrado+value.cut2), 2, false);
                           var vectorproductos=_.find(productos, function(o) { return o.idproducto == value.idproducto; }); 
                         if(typeof vectorproductos === 'undefined'){  
                              productos.push({idproducto : value.idproducto,
                               perfil:value.cobranza,
                               ascii:value.cut2,
                               plans:[value.idplan],
                               nuevos:[],
                               prestamos:[value.idprestamo]
                               }); 
                           }else{  
                                vectorproductos.plans.push(value.idplan); 
                                vectorproductos.prestamos.push(value.idprestamo); 
                                vectorproductos.ascii=_pl.redondeo_valor((vectorproductos.ascii+value.cut2), 2, false); 
                                _.forEach(value.cobranza, function(valueinto, key) { 
                                    var othervalue2=_.find(vectorproductos.perfil, function(o) { return o.idmaestro == valueinto.idmaestro&&o.iddetalle==valueinto.iddetalle&&o.id==valueinto.id; });
                                        if(typeof othervalue2 === 'undefined'){ 
                                                 
                                                vectorproductos.perfil.push(valueinto);
                                            }else{ 
                                              othervalue2.value = parseFloat(_pl.redondeo_valor(othervalue2.value+ valueinto.value));  
                                            }
                                    });

                           }
                      }else if(value.estatus==2){
                           sumacobrado=_pl.redondeo_valor((sumacobrado+value.cut2), 2, false);
                           var vectorproductos=_.find(productos, function(o) { return o.idproducto == value.idproducto; }); 
                         if(typeof vectorproductos === 'undefined'){  
                              productos.push({idproducto : value.idproducto,
                               perfil:value.cobranza,
                               ascii:value.cut2,
                               plans:[],
                               nuevos:[value.newdata],
                               prestamos:[value.idprestamo]
                               }); 
                           }else{  
                                vectorproductos.nuevos.push(value.newdata); 
                                vectorproductos.prestamos.push(value.idprestamo); 
                                vectorproductos.ascii=_pl.redondeo_valor((vectorproductos.ascii+value.cut2), 2, false); 
                                _.forEach(value.cobranza, function(valueinto, key) { 
                                    var othervalue2=_.find(vectorproductos.perfil, function(o) { return o.idmaestro == valueinto.idmaestro&&o.iddetalle==valueinto.iddetalle&&o.id==valueinto.id; });
                                        if(typeof othervalue2 === 'undefined'){ 
                                                 
                                                vectorproductos.perfil.push(valueinto);
                                            }else{ 
                                              othervalue2.value = parseFloat(_pl.redondeo_valor(othervalue2.value+ valueinto.value));  
                                            }
                                    });

                           }
                      }else{
                          moras.push(value);
                      }
                });

                console.log('suma before:',sumabefore);
                console.log('suma after:',sumaafter);
                
                console.log('productos:',productos);
                console.log('moras:',moras);

                console.log('suma cobrado:',sumacobrado); 
                console.log('total:',_pl.redondeo_valor((sumaafter+sumacobrado), 2, false),'<=>',sumabefore);
                
                this.$refs.cobranza_ascii.showVue(productos,moras,me.datasFiles,sumabefore,sumaafter,sumacobrado,(_pl.redondeo_valor((sumaafter+sumacobrado), 2, false)===_pl.redondeo_valor((sumabefore), 2, false)),this.idmodulomain);
          },
            procesarFiles() {
                let me = this; 
                axios.get('/getFormulas').then((response) => {  
                     var productos_in = new Map();
                    _.forEach(response.data, function(value, key) { 
                       productos_in.set(parseInt(key),value); 
                    });
                    return productos_in;
                }).then((out)=>{  
                    axios.get('/getSendedAscii').then((response_ascii) => { 
                         me.processing(response_ascii.data,out);  
                    });
                });
            },
            datasArray(data) { 
                this.datasFiles=[]; 
                if (data != null) { 
                    this.datasFiles=data.values; 
                   // console.log(this.datasFiles);
                }
            } 
        },
        mounted() {
            this.classModal=new _pl.Modals();
            this.classModal.addModal('primarymodal');   
            }
    }
</script>
<style> 
.labelInput:empty:not(:focus):before{
    content:'Seleccione los archivos';
        color: #808080a8;
}

 .inputFile { 
     border: solid 1px #c2cfd6;
     border-radius: 5px; 
     display: inline;
     padding: 0;
 }
 .botonSelect{
    border-radius: 5px;
    border-bottom-right-radius: 0;
    border-top-right-radius: 0;
 }
      
    .div-error{
        display: flex;
        justify-content: center;
    }

    .text-error{
        color: red !important;
        font-style: italic;
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
