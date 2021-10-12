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

                            <div class="col-md-10">
                                <div class="input-group" style="align-items: center;">
                                    <p style="text-align: right;margin: 0px; margin-right: 10px; font-weight: bold;">
                                        Criterio de busqueda:</p>
                                    <input type="text" v-model="buscarcobranza" @keyup.enter="listarprestamos()" class="form-control"
                                        placeholder="Ingresar  Nombres , Apellidos , Ci , Numero de Papeleta , Nombre producto, Numero de prestamo">
                                    <button  type="submit" @click="listarprestamos()" class="btn btn-primary"><i
                                            class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>

                         <table class="table table-bordered table-striped table-sm" v-if="arrayPrestamos.length>0">
                        <thead>
                            <tr>
                                <th class="thcell">Usuario</th>
                                <th class="thcell" style="width: 120px;">Opciones</th>
                                <th class="thcell">Nombre completo</th>
                                <th class="thcell">Producto</th>
                                <th class="thcell">Moneda</th>
                                <th class="thcell">Monto</th>
                                <th class="thcell">Plazo</th>
                                <th class="thcell" style="width: 100px;">Contabilidad</th>
                                <th class="thcell">Fecha</th>
                                <th class="thcell" style="width: 130px;">Codigo</th>
                                <th class="thcell">No.Lote</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="prestamos in arrayPrestamos" :key="prestamos.idprestamo" 
                                v-bind:class="prestamos.apro_conta>2||prestamos.idestado==6? 'table-danger' :prestamos.idestado==3 ? 'table-warning' :''" valign="middle">
                                <td class="tdcell" style="font-size: 11px; text-align: center;vertical-align: middle;"> 
                                    <div v-if="prestamos.idoperario!=null" class="dataUser"><span
                                            style="font-weight: 700;margin-right: 5px;">REG.:</span>{{prestamos.idoperario}}
                                    </div>
                                    <div v-if="prestamos.idusuario!=null" class="dataUser"><span
                                            style="font-weight: 700;margin-right: 5px;">DES.:</span>{{prestamos.idusuario}}
                                    </div>
                                </td>
                                <td class="tdcell" style="text-align:center;vertical-align: middle;">
                                    
                                   <div v-if="prestamos.apro_conta==1">  
                                        <button v-if="check('Registro_cobranza')"   type="button"
                                        class="btn btn-success  btn-sm "
                                        @click="regcobranza(prestamos)" title="Realizar cobranza">Cobranza</button> 
                                   </div>
                                    <div v-else> 
                                         <h6>
                                           <span class="badge badge-warning">{{prestamos.nombreestado}}</span>
                                        </h6>
                                   </div>
                                     
                                </td>
                                <td class="tdcell"
                                    v-text="prestamos.nomgrado+' '+prestamos.nombre+'  '+prestamos.apaterno+'  '+prestamos.amaterno"
                                    style="font-size: 12px;vertical-align: middle;"></td>
                                <td class="tdcell" v-text="prestamos.nomproducto" style="font-size: 12px;vertical-align: middle;"></td>
                                <td class="tdcell" v-text="prestamos.nommoneda" style="vertical-align: middle;"></td>
                                <td class="tdcell" style="text-align: right;vertical-align: middle;" v-text="completacero(prestamos.monto)">
                                </td>
                                <td class="tdcell" style="text-align:center;vertical-align: middle;" v-text="prestamos.plazo"></td>

                                <td class="tdcell" style="text-align:center;vertical-align: middle;">
                                    <h6>
                                        <span v-if="prestamos.apro_conta==0||prestamos.apro_conta==5" class="badge badge-danger">No
                                            validado</span>
                                        <span v-if="prestamos.apro_conta==1"
                                            class="badge badge-success">Validado</span>
                                        <span v-if="prestamos.apro_conta==2"
                                            class="badge badge-danger">Eliminado</span>
                                        <span v-if="prestamos.apro_conta==3"
                                            class="badge badge-warning">Observado</span>
                                        <span v-if="prestamos.apro_conta==4"
                                            class="badge badge-warning">Revertido</span>
                                    </h6>
                                </td>
                                <td class="tdcell" style="font-size: 12px; text-align: center;vertical-align: middle;padding: 0;">
                                    <div v-if="prestamos.fecharegistro!=null" class="" style=" width: 100%; "><span
                                            style="display: block;font-weight: bold;font-size: 12px;">Prestamo:</span><span
                                            v-text="prestamos.fecharegistro"></span></div>
                                    <div v-if="prestamos.fechardesembolso!=null" class="border-top" style="border: solid 1px #c2cfd6;width: 100%;margin-top: 6px !important; margin-bottom: 4px !important;"> </div>
                                    <div v-if="prestamos.fechardesembolso!=null" class="" style=" width: 100%; "><span
                                            style="display: block;font-weight: bold;font-size: 12px;">Desembolso:</span><span
                                            v-text="prestamos.fechardesembolso"></span></div>
                                </td>
                                <td class="tdcell" style="font-size: 11px; text-align: center;vertical-align: middle;padding: 0;">
                                    <div v-if="prestamos.no_prestamo!=null" class="" style=" width: 100%; "><span
                                            style="display: block;font-weight: bold;font-size: 12px;">Prestamo:</span><span
                                            v-text="prestamos.no_prestamo"></span></div>
                                    <div v-if="prestamos.idtransaccionD!=null" class="border-top" style="border: solid 1px #c2cfd6;width: 100%;margin-top: 6px !important; margin-bottom: 4px !important;"> </div>
                                    <div v-if="prestamos.idtransaccionD!=null" class="" style=" width: 100%; "><span
                                            style="display: block;font-weight: bold;font-size: 12px;">Desembolso:</span><span
                                            v-text="prestamos.idtransaccionD"></span></div>
                                </td>
                                <td class="tdcell" style="text-align: center;vertical-align: middle;">
                                    <h5>{{prestamos.lote}}</h5>
                                </td>
                                 
                            </tr>
                        </tbody>
                    </table>

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
 
 <cobranza_ascii @cerrarvue="cerrarModalvue" ref="cobranza_ascii"></cobranza_ascii>
 <cobranza_manual @cerrarvue="cerrarModalvue" ref="cobranza_manuall"></cobranza_manual>
    </main>
</template>

<script>
 
    export default {
        props: ['idmodulo','object','idventanamodulo'],
        data (){
            return {
                 arrayPermisos: { 
                   Registro_cobranza: 0 
                }, 
                arrayPermisosIn: {},
             classModal:null,  
             datasFiles:[],  
             filesName:[], 
             arrayPrestamos: [],
             buscarcobranza:'', 
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
            regcobranza(prestamo){ 
             this.$refs.cobranza_manuall.showVue(prestamo,this.idmodulomain);
           },
            getPermisos() { 
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
             completacero(g){ 
                return _pl.fillDecimals(g);
            }, 
             listarprestamos(page = 1) {

                 let me = this;
                 var url = '/prestamos/prestamoscobranzamanual?page=' + page + '&buscar=' + this.buscarcobranza;
                 axios.get(url).then(function (response) {
                         var respuesta = response.data;
                         me.pagination = respuesta.pagination;
                         me.arrayPrestamos = respuesta.prestamos.data; 
                     })
                     .catch(function (response) {
                         console.log(response);
                     });
             },
            cerrarModalvue(ins){
                this.listarprestamos();
                if(ins>=0){
                    if(ins==1){
                        swal("¡El proceso termino correctamente!", "", "success");
                                $(".swal2-modal").css('z-index', '2000');
                                $(".swal2-container").css('z-index', '2000');
                    }else{
                        swal("¡Ocurrio un error al momento de realizar el proceso!", "", "warning"); 
                                $(".swal2-modal").css('z-index', '2000');
                                $(".swal2-container").css('z-index', '2000');
                    }
                      
                }
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
            this.getPermisos();   
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
