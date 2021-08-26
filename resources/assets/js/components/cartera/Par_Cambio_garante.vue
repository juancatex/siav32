<template>
    <main class="main">
          <ol class="breadcrumb">
              <li class="breadcrumb-item container">
                  <a href="/">Escritorio</a> 
              </li>
          </ol>
        <div class="container-fluid">

            <div class="card animated fadeIn">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Cambio de garantes
                </div>
                <div class="card-body">
                    <div class="form-group row" style="justify-content: flex-end;">

                        <div class="col-md-10">
                            <div class="input-group" style="align-items: center;">
                                <p style="text-align: right;margin: 0px; margin-right: 10px; font-weight: bold;">
                                    Criterio de busqueda:</p>
                                <input type="text" v-model="buscar" @keyup.enter="listar(1,buscar)" class="form-control"
                                    placeholder="Ingresar  Nombres , Apellidos , Ci , Numero de Papeleta , Nombre producto, Numero de prestamo">
                                <button  type="submit" @click="listar(1,buscar)" class="btn btn-primary"><i
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
                                    
                                   <div v-if="prestamos.garantes==0"> 
                                       <h6>
                                           <span class="badge badge-warning">{{prestamos.nombreestado}}</span>
                                       </h6>
                                   </div>
                                    <div v-else> 
                                    <button v-if="check('Cambiar_garante')"   type="button"
                                        class="btn btn-success  btn-sm icon-check"
                                        @click="modificargarante(prestamos)" title="Modificacion de garante"></button>
                                      
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
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#"
                                    @click.prevent="cambiarPagina(pagination.current_page - 1,buscar)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page"
                                :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar)"
                                    v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#"
                                    @click.prevent="cambiarPagina(pagination.current_page + 1,buscar)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

        </div>




        <div class="modal fade " tabindex="-1" role="dialog" style="z-index: 1600;" aria-hidden="true"
            id="primarymodal">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content animated fadeIn">
                    <div class="modal-header">
                        <h4 class="modal-title" >Cambio de garante</h4>
                        <button type="button" class="close" aria-hidden="true" aria-label="Close"
                            @click="classModal.closeModal('primarymodal')"><span aria-hidden="true">×</span></button>
                    </div>

                    <div class="modal-body">

                         



                    </div>
                    <div class="modal-footer">
                        <button :disabled="errors.any()" type="button" class="btn btn-primary"
                            @click="regaprovacion('primarymodal')">Aprobar Desembolso</button>
                        <button type="button" class="btn btn-secondary"
                            @click="classModal.closeModal('primarymodal')">Cerrar</button>

                    </div>
                </div>
            </div>
        </div>
 <div class="modal fade" tabindex="-1" role="dialog" style="z-index: 1600;" aria-hidden="true"
      id="factorviewgaranteLista">
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content animated fadeIn">
          <div class="modal-header">
            <h4 class="modal-title" >Factor determinante</h4>
            <button type="button" class="close" aria-hidden="true" aria-label="Close"
              @click="classModal.openModal('primarymodal')">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body" style="padding: 8px;">
            <div id="detallesociolistagraficos"></div>
          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" type="button"
              @click="classModal.openModal('primarymodal')">cerrar</button>
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
        props: ['idmodulo','object','idventanamodulo'],
        data (){
            return {
                arrayPermisos: { 
                   Cambiar_garante: 0 
                }, 
                arrayPermisosIn: {},

                classModal: null,
                buscar: '',
                barChartG: null,
                arrayPrestamos: [], 
 
                pagination: {
                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0,
                },
                offset: 3,

            }
        }, 
        computed:{  
            isActived: function(){
                return this.pagination.current_page;
            },
           
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
             modificargarante(data=[]){
                console.log(data);
              
                this.classModal.openModal('primarymodal');

                 let me = this;
                var url =
                    "/pre_listasociogarante?buscar=" +  textoint + "&id=" +  me.socio_id +  "&idpro=" +  me.producto;
                axios
                    .get(url)
                    .then(function(response) {
                    var respuesta = response.data;
                    me.arraygarantes = respuesta.garantes;
                    me.arrayperfilgarante["cobranza"] = respuesta.cobranza;
                    me.arrayperfilgarante["desembolso"] = [];
                    _pl._vm2154_12185(me);
                    })
                    .catch(function(response) {
                    console.log(response);
                    });
            } ,
             completacero(g){ 
                return _pl.fillDecimals(g);
            }, 
             listar(page = 1, buscar = '') {

                 let me = this;
                 var url = '/prestamos/prestamoscambiogarante?page=' + page + '&buscar=' + buscar;
                 axios.get(url).then(function (response) {
                         var respuesta = response.data;
                         me.pagination = respuesta.pagination;
                         me.arrayPrestamos = respuesta.prestamos.data; 
                     })
                     .catch(function (response) {
                         console.log(response);
                     });
             }, 
           cambiarPagina(page,buscar){
                let me = this;  
                me.pagination.current_page = page; 
                me.listar(page,buscar);
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
          }

         
        },
        mounted() {
            this.getPermisos();
            this.classModal=new _pl.Modals();
            this.classModal.addModal('primarymodal'); 
            this.classModal.addModal("factorviewgaranteLista"); 
               
        }
    }
</script>
<style> 
.dataUser {
    font-size: 11px;
    font-variant-caps: all-small-caps;
}
 .thcell{
    text-align: center ; 
    vertical-align: middle ; 
 }
 .tdcell{ 
    vertical-align: middle; 
 }
      .fotosocioimg{
        border:#efefef 2px solid;
        filter:drop-shadow(4px 4px 5px #333);
        width:76%;
		max-width: 100%;
    }
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
      

::-webkit-scrollbar {
  width: 10px !important;
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
