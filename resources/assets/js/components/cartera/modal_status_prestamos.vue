<template>
            <main class="main"> 
                      
                <div class="modal fade " tabindex="-1"  role="dialog"  style="z-index: 1600;" aria-hidden="true" id="primarymodalstatus" >
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content animated fadeIn"> 
                                <div class="modal-header"> 
                                    <div style="margin-right: 17px;">
                                        <img v-if="socioimg" :src="'img/socios/'+socioimg" style="    width: 60px;" class="rounded-circle fotosociomini" alt="Cinque Terre">
										<img v-else :src="'img/socios/avatar.png'" style="    width: 60px;"  class="rounded-circle fotosociomini" alt="Cinque Terre" >
                                    </div>
                                    <h4 class="modal-title" v-text="tituloModalstatus"></h4>
                                    <button type="button" class="close" aria-hidden="true" aria-label="Close" @click="cerrarmodaleve('primarymodalstatus')"><span aria-hidden="true">×</span></button>
                                </div> 

                                <div class="modal-body">
                                        
                                            <table class="table table-bordered table-sm">
                                                    <thead>
                                                        <tr> 
                                                            <th>Plan</th>  
                                                            <th>Producto</th>  
                                                            <th>Moneda</th> 
                                                            <th>Monto</th> 
                                                            <th>Cuota</th> 
                                                            <th>Plazo</th>                                    
                                                            <th>Fecha Registro</th>   
                                                            <th style="text-align: center;">Estado</th>                   
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="prestamos in arrayPrestamos" :key="prestamos.idprestamo" >   
                                                                    <td style="vertical-align: middle; text-align: center;">
                                                                     <button class="btn btn-primary" type="button"
                                                                         @click="pdf(prestamos.planPagosMap)"> <i class="fa fa-file-pdf-o"></i> </button>
                                                                    </td> 
                                                                    <td style="vertical-align: middle;" :class="{'tableviewstatus': prestamos.idestado>3}" v-text="prestamos.nomproducto"></td> 
                                                                    <td style="vertical-align: middle; text-align: center;" :class="{'tableviewstatus': prestamos.idestado>3}" v-text="prestamos.nommoneda"></td> 
                                                                    <td :class="{'tableviewstatus': prestamos.idestado>3}" style="text-align: right;vertical-align: middle;" v-text="completacero(prestamos.monto)"></td>
                                                                    <td :class="{'tableviewstatus': prestamos.idestado>3}" style="text-align: right;vertical-align: middle;" v-text="prestamos.cuota"></td>
                                                                    <td :class="{'tableviewstatus': prestamos.idestado>3}" style="text-align: center;vertical-align: middle;" v-text="prestamos.plazo"></td>
                                                                    <td :class="{'tableviewstatus': prestamos.idestado>3}" style="text-align: center;vertical-align: middle;" v-text="prestamos.fecharegistro"></td>
                                                                    <td :class="{'tableviewstatus': prestamos.idestado>3}" style="width: 140px;text-align: left;background: rgba(128, 128, 128, 0.14);"> 
                                                                        
                                                                        <div style="width: 100%;display: inline-flex;" ><div class="col-md-4"><span style="font-weight: 600;">Tipo:</span></div><div class="col-md-9"><span style="width:100%;" class="badge badge-primary">{{prestamos.nombretipo}}</span></div> </div>

                                                                        <div style="width: 100%;display: inline-flex;"   v-if="prestamos.idestado==1"><div class="col-md-4"><span style="font-weight: 600;">Estado: </span></div><div class="col-md-9"><span style="    width: 100%;"  class="badge badge-primary">{{prestamos.nombreestado}}</span> </div> </div>
                                                                        <div style="width: 100%;display: inline-flex;"   v-if="prestamos.idestado==2"><div class="col-md-4"><span style="font-weight: 600;">Estado: </span></div><div class="col-md-9"><span style="    width: 100%;" class="badge badge-success">{{prestamos.nombreestado}}</span> </div> </div>
                                                                        <div style="width: 100%;display: inline-flex;"   v-if="prestamos.idestado>=3"><div class="col-md-4"><span style="font-weight: 600;">Estado: </span></div><div class="col-md-9"><span style="    width: 100%;" class="badge badge-warning">{{prestamos.nombreestado}}</span> </div></div>

                                                                       
                                                                        <div style="width: 100%;display: inline-flex;"   v-if="prestamos.apro_conta==0"><div class="col-md-4"><span style="font-weight: 600;">Conta:</span></div><div class="col-md-9"><span style="    width: 100%;" class="badge badge-danger">No validado</span></div></div>
                                                                        <div style="width: 100%;display: inline-flex;"   v-if="prestamos.apro_conta==1"><div class="col-md-4"><span style="font-weight: 600;">Conta:</span></div><div class="col-md-9"><span style="    width: 100%;" class="badge badge-success">Validado</span></div></div>
                                                                        <div style="width: 100%;display: inline-flex;"   v-if="prestamos.apro_conta==3"><div class="col-md-4"><span style="font-weight: 600;">Conta:</span></div><div class="col-md-9"><span style="    width: 100%;" class="badge badge-warning">Observado</span></div></div>
                                                                        <div style="width: 100%;display: inline-flex;"   v-if="prestamos.apro_conta==4"><div class="col-md-4"><span style="font-weight: 600;">Conta:</span></div><div class="col-md-9"><span style="    width: 100%;" class="badge badge-danger">Revertido</span></div></div>

                                                                        <div style="width: 100%;display: inline-flex;"   v-if="prestamos.acsii>0"><div class="col-md-4"><span style="font-weight: 600;">info:</span></div><div class="col-md-9"><span style="    width: 100%;" class="badge badge-primary">Enviado acsii</span>  </div></div>
                                                                    </td>
                                                        </tr>                          
                                                    </tbody>
                                                </table>
                                                <nav>
                                                    <ul class="pagination">
                                                        <li class="page-item" v-if="pagination.current_page > 1">
                                                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                                                        </li>
                                                        <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                                            <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                                                        </li>
                                                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                                                        </li>
                                                    </ul>
                                                </nav> 
                                </div>   
                                <div class="modal-footer">  
                                <button type="button" class="btn btn-secondary" @click="cerrarmodaleve('primarymodalstatus')">Cerrar</button>              
                                </div>    
                        </div>
                    </div>
                </div>
        <div class="modal fade" tabindex="-1" role="dialog" style="z-index: 1600;" aria-hidden="true" id="plandepagospdf">
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content animated fadeIn">
          <div class="modal-header">
            <h4 class="modal-title" id="modalOneLabel">Plan de pagos</h4>
            <button type="button" class="close" aria-hidden="true" aria-label="Close"
              @click="classModalstatus.openModal('primarymodalstatus')">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body-plandepagos">
            <!--  <div style="display:none" v-html="plandepagosSimulacion"></div>-->
            <iframe name="planoutpdf" id="planoutpdf" style="width:100%;height:100%;"></iframe>
          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" type="button"
              @click="classModalstatus.openModal('primarymodalstatus')">cerrar</button>
            <!--  <button class="btn btn-primary" type="button" @click="print()">Imprimir</button> -->
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
        {
            locale: 'es',
            dictionary: 
            {
                es: VueValidationEs
            }
        });

    Vue.use(VeeValidate);

    export default {
        data (){
            return {
                arrayPermisos:{Nuevo:0,Edición:0},
               classModalstatus:null,
               tituloModalstatus:'',
               arrayPrestamos:[],
               socioimg:'',
               idsocio:'',
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3, 
                
            }
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
            pdf(pdfin){
       this.classModalstatus.openModal('plandepagospdf');        
      _pl._vvp2521_00001(new Map(JSON.parse(pdfin)),'planoutpdf');
            },
            completacero(g){
                return _pl.fillDecimals(g);
            },
            cerrarmodaleve(id){ 
                this.classModalstatus.closeModal(id); 
                 this.$emit('cerrarvuestatus');
            },
             listar(page=1){
                let me=this; 
                var url= '/prestamosEstatus?page=' + page + '&idsocio='+ this.idsocio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data; 
                    me.pagination= respuesta.pagination;
                    me.arrayPrestamos= respuesta.prestamos.data; 
                })
                .catch(function (response) {
                    console.log(response);
                });
            },
              cambiarPagina(page){
                let me = this; 
                me.pagination.current_page = page; 
                me.listar(page);
            } ,
              showVuestatus(socio){
                this.idsocio=socio.idsocio;
                this.listar();  
                this.socioimg=socio.rutafoto; 
                this.tituloModalstatus=socio.nomgrado+'  '+ socio.nombre +' '+socio.apaterno+' '+socio.amaterno;
                
                this.classModalstatus=new _pl.Modals();
                this.classModalstatus.addModal('primarymodalstatus'); 
                this.classModalstatus.addModal("plandepagospdf");
                this.classModalstatus.openModal('primarymodalstatus'); 
              }
        } 
         
    }
</script>
<style> 
 
      
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-style: italic;
    }
    .fotosociomini{
	     display: inline-block;
        border:#efefef 1px solid;
        filter:drop-shadow(1px 0px 2px #333);
        width:40px;
    }

    .tableviewstatus{background: #ffd60026;}
   
 

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
