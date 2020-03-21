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
             filesName:[],  
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
          processing(arraysql) {  
              let me = this; 
              var sumabefore=0;
              var sumaafter=0; 
              var sumacobrado=0;

                _.forEach(me.datasFiles, function(value,key) { 
                      sumabefore=_pl.redondeo_valor((sumabefore+value.aporte), 2, false);
                      me.datasFiles[key].totaloriginal=me.datasFiles[key].aporte;
                      me.datasFiles[key].estado=0; 
                });

            
               _.forEach(arraysql, function(value, key) {
                     var out = _.findIndex(me.datasFiles, (o) => {return (o.numpapeleta === value.numpapeleta); });
                     if(out>=0){ 
                         if((me.datasFiles[out].aporte-value.send_ascii)>=0){
                                me.datasFiles[out].aporte=_pl.redondeo_valor((me.datasFiles[out].aporte-value.send_ascii), 2, false); 
                                arraysql[key].estatus=1;  
                                me.datasFiles[out].estado=1;
                            
                            //   if(!_.has(me.datasFiles[out], 'plans')){
                            //         me.datasFiles[out].plans= [value.idplan];
                            //     }else{
                            //         me.datasFiles[out].plans.push(value.idplan);
                            //     }
                            
                            //    if(!_.has(me.datasFiles[out], 'prestamos')){
                            //         me.datasFiles[out].prestamos= [value.idprestamo];
                            //     }else{
                            //         me.datasFiles[out].prestamos.push(value.idprestamo);
                            //     }
                               
                                if(!_.has(me.datasFiles[out], 'plans_total')){
                                    me.datasFiles[out].plans_total= value.send_ascii;
                                }else{
                                    var countsuma=me.datasFiles[out].plans_total;
                                    me.datasFiles[out].plans_total=_pl.redondeo_valor((countsuma+value.send_ascii), 2, false);
                                }
 
                        }else{
                         arraysql[key].estatus=0;   
                        }
                     }else{
                         arraysql[key].estatus=0;
                     }
                    });
 
                _.forEach(me.datasFiles, function(value) {   
                       sumaafter=_pl.redondeo_valor((sumaafter+value.aporte), 2, false);  
                }); 
                _.forEach(arraysql, function(value) { 
                      if(value.estatus==1){  
                           sumacobrado=_pl.redondeo_valor((sumacobrado+value.send_ascii), 2, false); 
                      } 
                });

                console.log("arraysql:",arraysql);
                console.log("datasFiles:",me.datasFiles);  
                console.log('suma before:',sumabefore);
                console.log('suma after:',sumaafter);
                  

                console.log('suma cobrado:',sumacobrado); 
                console.log('total:',_pl.redondeo_valor((sumaafter+sumacobrado), 2, false),'<=>',sumabefore);
                
                this.$refs.cobranza_ascii.showVue(arraysql,me.datasFiles,sumabefore,sumaafter,sumacobrado,(_pl.redondeo_valor((sumaafter+sumacobrado), 2, false)===_pl.redondeo_valor((sumabefore), 2, false)),this.idmodulomain,this.filesName);
          },
            procesarFiles() {
                let me = this; 
                 axios.get('/getSendedAscii').then((response_ascii) => { 
                         me.processing(response_ascii.data);  
                  }).catch(function (error) {
                                        console.log(error);
                 }); 
            },
            datasArray(data) { 
                this.datasFiles=[]; 
                if (data != null) { 
                    this.datasFiles=data.values;
                    this.filesName=data.names; 
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
