<template>
    <main class="main">
          <ol class="breadcrumb">
              <li class="breadcrumb-item container">
                  <a href="/">Escritorio</a>
                  <div v-if="statusLote" class="row justify-content-end" style="  float: right; ">
                      <div style=" text-align: center;margin: 4px">
                          <div>
                              <h5 style="margin: 0;">Lote <b>{{statusLote.idlote}}</b></h5>
                          </div>
                          <div class="my-auto">
                              <h6 style="margin: 0;"> ({{statusLote.min}} de {{statusLote.max}})</h6>
                          </div>
                      </div>
                  </div>
              </li>
          </ol>
        <div class="container-fluid">

            <div class="card animated fadeIn">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Estado de Prestamos 
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
                    <table class="table table-bordered table-striped table-sm">
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
                                    
                                   <div v-if="prestamos.idestado==3">
                                       <!-- <button v-if="check('Alta_garantes')?(prestamos.garantes>0):false"
                                            @click="altaGarantes(prestamos)"
                                           type="button" class="btn btn-danger btn-sm"
                                           title="Ejecutar alta garantes">Ejecutar Alta Garantes</button> -->
                                       <h6>
                                           <span v-if="prestamos.garantes==0"
                                               class="badge badge-danger">{{prestamos.nombreestado}}</span>
                                       </h6>
                                   </div>
                                    <div v-else> 
                                    <!-- <button v-if="check('Aprobacion_desembolso')?prestamos.idestado==1&&!prestamos.no_prestamo.includes('P.L'):false" :id="getid(prestamos)" type="button" -->
                                    <button v-if="check('Aprobacion_desembolso')?prestamos.idestado==1:false" :id="getid(prestamos)" type="button"
                                        class="btn btn-success  btn-sm icon-check"
                                        @click="openmodal('primarymodal',prestamos)" title="Aprobar"></button>
                                     <button v-if="check('Imprimir_calificacion')?prestamos.idestado==1:false" class="btn btn-primary btn-sm icon-printer" type="button"
                                        @click="printer(prestamos.idprestamo)"  title="Imprimir Calificación"></button>
                                    <button v-if="check('Eliminar_calificacion')?prestamos.idestado==1:false" type="button"
                                        class="btn btn-danger btn-sm icon-trash"
                                        @click="deleteCali(prestamos)" title="Eliminar Calificación"></button>

                                    <button v-if="prestamos.apro_conta==3" type="button"
                                        @click="observado_view(prestamos)" class="btn btn-warning btn-sm icon-question" title="Observado"></button>
                                    
                                    <button v-if="check('Corregir_observacion')?prestamos.apro_conta==3:false" type="button"
                                        @click="corregir_prestamo(prestamos)" class="btn btn-success btn-sm icon-check" title="Corregir"></button>

                                    <button v-if="check('Revertir_prestamo')?prestamos.apro_conta==4:false" type="button"
                                        @click="revertir_prestamo(prestamos)" class="btn btn-danger btn-sm" title="Revertir" >Revertir</button> 
                                     
                                    <h6><span v-if="prestamos.idestado!=6&&prestamos.idestado!=1&&prestamos.apro_conta<=1"
                                            class="badge badge-success">{{prestamos.nombreestado}}</span>
                                            
                                            <!-- <div v-if="prestamos.idprestamo!=prestamos.idref" class="" style=" width: 100%; "><span
                                            style="display: block;font-weight: bold;font-size: 12px;">Prestamo:</span><span>{{getprestamoidref(prestamos)+""+ (typeof prestamos.prestamoidref === 'undefined')?'':prestamos.prestamoidref}}</span></div> -->

                                        <span v-else-if="prestamos.idestado==6"
                                            class="badge badge-danger">{{prestamos.nombreestado}}</span>
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
                        <h4 class="modal-title" id="modalOneLabel"></h4>
                        <button type="button" class="close" aria-hidden="true" aria-label="Close"
                            @click="classModal.closeModal('primarymodal')"><span aria-hidden="true">×</span></button>
                    </div>

                    <div class="modal-body">

                        <div class=" row " style="justify-content: center; margin-bottom: 15px;">
                            <div v-if="statusLote" style=" text-align: center;border: solid 1.5px gray;">
                                <div class="row" style=" text-align: center;margin: 4px">
                                    <div style="margin-right: 15px;"><h4 style="margin: 0;">Lote <b style="font-size: 30px;">{{statusLote.idlote}}</b></h4> </div>
                                    <div class="my-auto"><h6 style="font-size: 16px;margin: 0;"> ({{statusLote.min}} de {{statusLote.max}})</h6></div> 
                                </div> 
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label"
                                for="text-input">Numero de Documento : </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input v-validate.initial="'required'" style="background-color: white;" type="text"
                                        v-model="numdoc_desembolso"
                                        :class="{'form-control': true, 'is-invalid': errors.has('numero de documento')}"
                                        placeholder="Numero de documento" name='numero de documento'>
                                </div> <span class="text-error">{{ errors.first('numero de documento')}}</span>
                            </div>
                        </div>

                        <div class="form-group rowModal">
                            <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label"
                                for="text-input">Detalle : </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <textarea v-validate.initial="'required'"
                                        :class="{'form-control':true,'is-invalid': errors.has('detalle')}"
                                        name="detalle" class="col-md-12" rows="4" v-model="obs"
                                        placeholder="Detalle del desembolso"></textarea>
                                </div>
                                <span class="text-error">{{ errors.first('detalle') }}</span>
                            </div>
                        </div>

                        <div class="form-group row " v-if="arrayPrestamosSeleccionado.cancelarprestamos==1"> 
                                     <label style="text-align: right; align-items: center;" class="col-md-6 form-control-label"
                                for="text-input">¿ Refinanciar cuotas en transito ? </label>
                                    <div class="col-md-6 my-auto"> 
                                         <label class="switch switch-label switch-pill switch-primary" style="margin: 0 !important;display: table-cell;">
                                        <input class="switch-input" type="checkbox" checked="" v-model="cobrarenvioascii" disabled>
                                        <span class="switch-slider" data-checked="Si" data-unchecked="No"></span>
                                        </label>   
                                    </div>
                         </div>



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


        <div class="modal fade " tabindex="-1" role="dialog" style="z-index: 1600;" aria-hidden="true" id="plandepagos">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content animated fadeIn">

                    <div class="modal-header">
                        <h4 class="modal-title" id="modalOneLabelplan">Plan de pagos </h4>
                        <button type="button" class="close" aria-hidden="true" aria-label="Close"
                            @click="classModal.closeModal('plandepagos')"><span aria-hidden="true">×</span></button>
                    </div>

                    <div class="modal-body-plandepagos">
                        <!--  <div style="display:none" v-html="plandepagosSimulacion"></div>-->
                        <iframe name="viewpdf" id="viewpdf" style="width:100%;height:100%;"></iframe>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button"
                            @click="classModal.closeModal('plandepagos')">cerrar</button>
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
        props: ['idmodulo','object','idventanamodulo'],
        data (){
            return {
                arrayPermisos: {
                    // Alta_garantes: 0,
                    Aprobacion_desembolso: 0,
                    Imprimir_calificacion: 0,
                    Eliminar_calificacion: 0,
                    Corregir_observacion: 0,
                    Revertir_prestamo: 0 
                }, 
                arrayPermisosIn: {},

                classModal: null,
                buscar: '',
                fecha_actual: '',
                fechacorte: 0,
                objectlocal: this.object,
                idmodulomain: this.idmodulo,
                tipocambio: 0,
                tasaanual: 0,
                cobrarenvioascii: true,
                plazomeses: 0, 
                montosolicitado: 0,
                idsocio: 0,
                fechasjson: '',
                numdoc_desembolso: '',
                obs: '',
                idproducto: '',
                arrayPrestamos: [],
                arrayMoras: [],
                rutas: [], 
                arrayPrestamosSeleccionado: [],

                statusLote: null,
                idpres: '',
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
        getprestamoidref(idpre){ 
            axios.get('/prestamos/getprestamorefinan?idpre='+idpre.idref).then(function (responsee) {
                         idpre.prestamoidref=responsee;
                     })
                     .catch(function (response) {
                         console.log(response);
                     }); 
          
        },
            altaGarantes(data){

            },
            closeLote(){

            },
            printer(idpres){ 
            _pl._vm2154_12186_135(this.rutas['REP_PRECALIFICACION']+idpres,'Calificación del Prestamo'); 
            }, 
            deleteCali(data){ 
                this.idpres=data.idprestamo; 
                 swal({
                title: '¿ Esta seguro de eliminar este Prestamo ?',
                type: 'warning',
                showConfirmButton: true,
                allowOutsideClick: () => false,
                allowEscapeKey:() => false,
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText:'No', 
                confirmButtonColor: '#4dbd74',
                cancelButtonColor: '#f86c6b', 
                buttonsStyling: true,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this; 
                    axios.put('/par_prestamos/eliminar',{
                        'idprestamo': data.idprestamo
                    }).then(function (response) {
                        me.listar(); 
                        swal(
                        'Eliminado!',
                        'El registro ha sido eliminado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    }); 
                  }  
                });
            },
  async revertirPrestamo(arrayprestamos) {
      let responses = true;
      try {  for (var key in arrayprestamos) {
              await axios
                  .put('/reversionPrestamos', {
                      'idprestamo': arrayprestamos[key].idprestamo
                  })
                  .then(function (response) {
                      responses = true;
                  })
                  .catch(function (error) {
                      responses = false;
                  });
          }
      } catch (err) {
          responses = false;
      }
      return responses;
  }
            ,
            corregir_prestamo(pr){
               swal({
                title: '¿ Esta seguro de corregir este Prestamo ?',
                 text: "Se eliminara el prestamo seleccionado",
                type: 'warning',
                showConfirmButton: true,
                allowOutsideClick: () => false,
                allowEscapeKey:() => false,
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText:'No', 
                confirmButtonColor: '#4dbd74',
                cancelButtonColor: '#f86c6b', 
                buttonsStyling: true,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
  let me = this;  
                    _pl._vm2999158_1fgf_135(this.rutas['REP_OBS']+pr.idprestamo,'Para seguir debe imprimir este documento.').then((result) => {
                    if (result.value) { 
                        swal({
                        title: "Revertiendo Prestamos Realizados", 
                        allowOutsideClick: () => false,
                        allowEscapeKey:() => false, 
                        onOpen: function() { 
                            swal.showLoading() ; 
                        }}).catch(error => { swal.showValidationError( 'Request failed: ${error}' )  }); 
                        axios.get('/getprestamoRefi?id='+pr.idprestamo).then(function (response) { 
                                        
                                     me.revertirPrestamo(response.data.refi).then(
                                         function (responses) {
                                             if (responses) {
                                                 swal({
                                                     title: "Eliminando prestamo observado",
                                                     allowOutsideClick: () => false,
                                                     allowEscapeKey: () => false,
                                                     onOpen: function () {
                                                         swal.showLoading();
                                                     }
                                                 }).catch(error => {
                                                     swal.showValidationError('Request failed: ${error}')
                                                 });
                                                 axios.put('/update_estado_observado', {
                                                     'idp': pr.idprestamo
                                                 }).then(function (response) {
                                                     me.listar();
                                                     swal({
                                                         title: '¿ Desea registrar el prestamo ?',
                                                         type: 'question',
                                                         showConfirmButton: true,
                                                         allowOutsideClick: () => false,
                                                         allowEscapeKey: () => false,
                                                         showCancelButton: true,
                                                         confirmButtonText: 'Si',
                                                         cancelButtonText: 'No',
                                                         confirmButtonColor: '#4dbd74',
                                                         cancelButtonColor: '#f86c6b',
                                                         buttonsStyling: true,
                                                         reverseButtons: true
                                                     }).then((result) => {
                                                         if (result.value) {
                                                             vue.to(30);
                                                         } else {
                                                             swal("¡Se registro los datos correctamente!", "", "success");
                                                         }
                                                     }); 
                                                 }).catch(function (error) {
                                                     console.log(error);
                                                 });

                                             } else {
                                                 swal(
                                                     "¡ocurrio un problema al registrar el dato!",
                                                     "",
                                                     "error"
                                                 );
                                             }
                                         }
                                     ); 
                                    }).catch(function (error) {
                                        console.log(error);
                                    });  
                                    
                    }  
                    });
                
                                 
                  }  
                });
            },
             revertir_prestamo(pr){
               swal({
                title: '¿ Esta seguro de revertir este Prestamo ?',
                 text: "Se eliminara el prestamo seleccionado",
                type: 'warning',
                showConfirmButton: true,
                allowOutsideClick: () => false,
                allowEscapeKey:() => false,
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText:'No', 
                confirmButtonColor: '#4dbd74',
                cancelButtonColor: '#f86c6b', 
                buttonsStyling: true,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this; 
                    swal({
                        title: "Revertiendo Prestamo", 
                        allowOutsideClick: () => false,
                        allowEscapeKey:() => false, 
                        onOpen: function() { 
                            swal.showLoading() ; 
                        }}).catch(error => { swal.showValidationError( 'Request failed: ${error}' )  }); 

                        axios.get('/getprestamoRefi?id='+pr.idprestamo).then(function (response) { 
                                        
                                     me.revertirPrestamo(response.data.refi).then(
                                         function (responses) {
                                             if (responses) {
                                                 swal({
                                                     title: "Eliminando prestamo",
                                                     allowOutsideClick: () => false,
                                                     allowEscapeKey: () => false,
                                                     onOpen: function () {
                                                         swal.showLoading();
                                                     }
                                                 }).catch(error => {
                                                     swal.showValidationError('Request failed: ${error}')
                                                 });

                                                 axios.put('/update_estado_revertir', {
                                                     'idp': pr.idprestamo
                                                 }).then(function (response) {
                                                     me.listar();
                                                     swal("¡Se registro los datos correctamente!", "", "success");
                                                 }).catch(function (error) {
                                                     console.log(error);
                                                 });

                                             } else {
                                                 swal(
                                                     "¡ocurrio un problema al registrar el dato!",
                                                     "",
                                                     "error"
                                                 );
                                             }
                                         }
                                     ); 
                                    }).catch(function (error) {
                                        console.log(error);
                                    });  
                                 
                  }  
                });
            },
            observado_view(pr){
                console.log(pr);
                let me=this;
                swal({
                title: '¡Observaciones!',
                html:   '<div class="form-group row "> ' +
                        '<div class="col-md-12"> <div class="input-group"> <textarea style="    background: transparent;"  class="form-control"  placeholder="Observaciones" autofocus readonly>'+
                        pr.observaciones+'</textarea></div> </div></div>',
                type:"info",
                showCancelButton: false,
                confirmButtonColor: "#4dbd74",
                cancelButtonColor: "#f86c6b",
                confirmButtonText: "Ok", 
                buttonsStyling: true,
                reverseButtons: true
                });
            },
             getid(id){ 
                 if(this.objectlocal!=null){ 
                    if(this.objectlocal==id.idprestamo){ 
                        this.openmodal('primarymodal',id);
                        this.objectlocal=null;
                        vue.to(-1);
                    }
                 }
                return id.idprestamo;                 
             },
            completacero(g){ 
                return _pl.fillDecimals(g);
            }, 
            
             listar(page = 1, buscar = '') {

                 let me = this;
                 var url = '/prestamos/desembolso?page=' + page + '&buscar=' + buscar;
                 axios.get(url).then(function (response) {
                         var respuesta = response.data;
                         me.pagination = respuesta.pagination;
                         me.arrayPrestamos = respuesta.prestamos.data; 
                     })
                     .catch(function (response) {
                         console.log(response);
                     });
             },
             getloteStatus(){
                  let me = this; 
                 axios.get('/statusLote').then(function (response) {
                         me.statusLote = response.data.lote;  
                        //  console.log(response.data)
                        //  console.log('idlote',me.statusLote.idlote);
                        //   console.log('min',me.statusLote.min);
                        //   console.log('max',me.statusLote.max);
                        //      console.log('tam', _.size(me.statusLote));
                     })
                     .catch(function (response) {
                         console.log(response);
                     });
             },
           fechasistema(){
                let me=this; 
                 var url= '/getdatacalculo';
                axios.get(url).then(function (response) {
                    var respuesta= response.data; 
                    me.fecha_actual = respuesta[0].fecha;  
                    me.fechacorte = respuesta[0].corte;  
                    me.fechasjson = respuesta[0].fechas;  
                })
                .catch(function (error) {
                    console.log(error);
                });
           },
            
           cambiarPagina(page,buscar){
                let me = this; 
                 
                me.pagination.current_page = page; 
                me.listar(page,buscar);
            },
            regaprovacion(id){
              let me=this; 
                this.classModal.closeModal(id); 
              swal({
                title: '¿Esta seguro de aprobar el desembolso?', 
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4dbd74',
                cancelButtonColor: '#f86c6b', 
                confirmButtonText: 'Si',
                cancelButtonText: 'No',
                buttonsStyling: true,
                reverseButtons: true
                }).then((result) => {
                                if (result.value) { 
                                        if(me.arrayPrestamosSeleccionado.cancelarprestamos==1){
                                                        swal({
                                                            title: "Registrando los Datos",
                                                            html:_pl._ppf351_2516(), 
                                                            allowOutsideClick: () => false,
                                                            allowEscapeKey:() => false,
                                                            showConfirmButton:false,
                                                            showCancelButton:false
                                                            }).catch(error => { swal.showValidationError( 'Request failed: ${error}' )  }); 
                                                                $('#text_saving').text('Obteniendo numero de lote...'); 

                                                                    axios.get('/getlote/getstatus?idproducto='+me.idproducto).then(function (responselote) { 
                                                                                /////////////////////////////////////////////////////////////////
                                                                                        $('#text_saving').text('Realizando el refinanciamiento de prestamos existentes.');
                                                                                      
                                                                                      axios.post('/start_refinanciamiento',{
                                                                                                    'socio':me.arrayPrestamosSeleccionado.idsocio,
                                                                                                    'idmodulo':me.idmodulomain,
                                                                                                    'idprestamoin':me.arrayPrestamosSeleccionado.idprestamo,
                                                                                                    'detalle':me.obs,
                                                                                                    'lote':responselote.data.id,
                                                                                                    'numdoc':me.numdoc_desembolso,
                                                                                                    'interesDiferido':0,   
                                                                                                    'seg':me.arrayPrestamosSeleccionado.seguro ,
                                                                                                    'cobrar':me.cobrarenvioascii?1:0 
                                                                                            }).then(function (response) {  
                                                                                               console.log("response:",response); 
                                                                                                $('.progress-bar[id="save"]').css('width','100%').attr('aria-valuenow', 100).text('100%');   
                                                                                                    me.listar(); 
                                                                                                   _pl._vm2154_12185_145(me.idpres,me.rutas);  
                                                                                            }).catch(function (error) { 
                                                                                                console.log("error:",error);
                                                                                                swal("¡ocurrio un problema al refinanciar  el prestamos!",error.response.data.message, "error");
                                                                                            });
                                                                                /////////////////////////////////////////////////////////////////
                                                                    }).catch(function (error) { 
                                                                        swal("¡ocurrio un problema al obtener el numero de lote!",'', "error");
                                                                    }); 
                                        }else if(me.arrayPrestamosSeleccionado.activar_garante==1){
                                                // calcular el prestamo para dar altaa garante
                                        }else{
                                            swal({
                                                            title: "Registrando los Datos", 
                                                            allowOutsideClick: () => false,
                                                            allowEscapeKey:() => false,
                                                            showConfirmButton:false,
                                                            showCancelButton:false,
                                                             onOpen: function () {
                                                               swal.showLoading();
                                                             }
                                                            }).catch(error => { swal.showValidationError( 'Request failed: ${error}' )  });  
                                                                 axios.get('/getlote/getstatus?idproducto='+me.idproducto).then(function (responselote) { 
                                                                                ///////////////////////////////////////////////////////////////// 
                                                                                        axios.put('/prestamos/grabar_desembolsoNormal',{ 
                                                                                                'idprestamoin':me.idpres, 
                                                                                                'detalle':me.obs,
                                                                                                'numdoc':me.numdoc_desembolso,
                                                                                                'lote':responselote.data.id,
                                                                                                'idmodulo':me.idmodulomain,
                                                                                                'interesDiferido':0,
                                                                                                'perfilmaestro':me.perfilmaestrodesembolso,  
                                                                                                'seg':me.arrayPrestamosSeleccionado.seguro  
                                                                                        }).then(function (responsess) {  
                                                                                                              $('.progress-bar[id="save"]').css('width','100%').attr('aria-valuenow', 100).text('100%');   
                                                                                                       me.listar(); 
                                                                                                      _pl._vm2154_12185_145(me.idpres,me.rutas); 
                                                                                        }).catch(function (error) { 
                                                                                            swal("¡ocurrio un problema al actualizar el registro (desembolso)!",'', "error");
                                                                                        });
                                                                                /////////////////////////////////////////////////////////////////
                                                                    }).catch(function (error) { 
                                                                        console.log(error);
                                                                        swal("¡ocurrio un problema al obtener el numero de lote!",error.response.data.message, "error");
                                                                    }); 
                                        }
                                }
   
                });
 
               
            },
            saveaprovacion(id){
             this.classModal.closeModal(id);   
            },
            async regPlan_de_pagos(array,id){ 
                let me=this;
                let responses = true;
                var sizema=array.size; 
                try { for (var prestamos of array) {  
                    var valuestatus=parseInt((prestamos.pe*100)/sizema); 
                     $('.progress-bar[id="save"]').css('width', valuestatus+'%').attr('aria-valuenow', valuestatus).text(valuestatus+'%');  
                     $('#text_saving').text('Registrando datos para el periodo No.'+prestamos.pe);
                      await axios.post('/prestamos/Regplan',
                      {  
                       'idprestamo': id,
                       'pe':prestamos.pe,
                       'fp':prestamos.fp,
                       'di':prestamos.di,
                       'am':prestamos.am,
                       'in':prestamos.in,
                       'car':prestamos.car,
                       'cut':prestamos.cut,
                       'indi':prestamos.indi,
                       'seg':prestamos.seg,
                       'ca':prestamos.ca,
                       'ca_an':prestamos.ca_an,
                       'cuota':prestamos.cuota
                      }).then(function (response) {  responses=true;  }).catch(function (error) {  responses=false; throw BreakException; });  

                      }
                } catch (err) {
                    console.log(err);
                       responses=false;
                }
              return responses;      
            }, 
            mainto(obj){
                alert(obj);
                console.log(obj);
            },
            openmodal(id,data=[]){
                this.arrayPrestamosSeleccionado=data; 
                this.idpres=data.idprestamo;
                this.idsocio=data.idsocio; 
                this.idproducto=data.idproducto; 
                this.tipocambio=data.tipocambio;
                this.tasaanual=data.tasa;
                this.cobrarenvioascii=true;
                this.plazomeses=data.plazo;
                this.montosolicitado=data.monto;
                this.numdoc_desembolso=data.no_prestamo;
                this.obs='';
                $('#modalOneLabel').text('Aprobacion del desembolso prestamo no.'+data.no_prestamo);
                
                    
            this.classModal.openModal('primarymodal');
            } ,
            getRutasReports (){ 
            let me=this;
            var url= '/reporte1';
                axios.get(url).then(function (response) { 
                    me.rutas = response.data;   
                })
                .catch(function (error) {
                    console.log(error);
                });
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
            this.classModal.addModal('plandepagos');  
            this.listar(); 
            this.getloteStatus(); 
            this.fechasistema(); 
            this.getRutasReports(); 
            
            // var other = _.concat(array, 2, [3], [[4]]);
            // console.log(other);
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
