<template>
  <main class="main">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="/">Escritorio</a>
      </li>
    </ol> 
    <div class="container-fluid">
      <div class="card animated fadeIn">
        <div class="card-header">
          <i class="fa fa-align-justify"></i>
          Calificacion del Prestamo
        </div>
        <div class="card-body">
          <!-- <div class="form-group row">
                <div class="col-md-6">
               
                <Ajaxselect 
                  ruta="/pre_listasocio_prueba?buscar=" @found="tttttttt"  @cleaning="clean"
                  resp_ruta="socios"
                  labels="rutafoto,numpapeleta,nombre,apaterno,amaterno" 
                  placeholder="Ingrese texto" 
                  id="16"
                  idtabla="idsocio"
                  :clearable="true">
                </Ajaxselect>
                
                </div>
          </div>   -->
           
          <div class="form-group row" style="justify-content: flex-end;">
            <div class="col-md-7">
              <div class="input-group" style="align-items: center;">
                <p style="text-align: right;margin: 0px; margin-right: 10px; font-weight: 500;">Criterio de busqueda:
                </p>
                <input type="text" v-model="buscar" @keyup.enter="listarSocio(1,buscar)" class="form-control"
                  placeholder="Ingresar  Nombres , Apellidos , Ci , Numero de Papeleta" />
                <button type="submit" @click="listarSocio(1,buscar)" class="btn btn-primary">
                  <i class="fa fa-search"></i> Buscar
                </button>
              </div>
            </div>
          </div>
          <table class="table table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th style="width:170px;">Opciones</th>
                <th>Foto</th>
                <th>Grado</th>
                <th>Nombre Completo</th>
                <th>Num Papeleta</th>
                <th>CI</th>
                <th>Fuerza</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="socio in arraysocio" :key="socio.idsocio"
                v-bind:class="socio.idestprestamos==1 ? 'table-danger' :''">
                <td style=" vertical-align: middle;text-align: center;">
                   <button v-if="socio.idestprestamos==0"   type="button" style="margin-top: 0.2rem;min-width: 75px;" class="btn btn-success btn-sm "  @click="abrirModal('modalCalificacion','calificar',socio)">Registrar</button>
                   <button type="button" style="margin-top: 0.2rem;min-width: 75px;" class="btn btn-warning btn-sm " @click="viewstatus(socio)">Estado</button> 
                </td>

                <td style=" vertical-align: middle;text-align: center;">
                  <img v-if="socio.rutafoto" :src="'img/socios/'+socio.rutafoto" class="rounded-circle fotosociomini_pre"
                    alt="Cinque Terre" />
                  <img v-else :src="'img/socios/avatar.png'" class="rounded-circle fotosociomini_pre" alt="Cinque Terre" />
                </td>

                <td style=" vertical-align: middle;" v-text="socio.nomgrado"></td>
                <td :id="getid(socio)" style=" vertical-align: middle;" v-text="socio.nombre +' '+socio.apaterno+' '+socio.amaterno"></td>
                <td style=" vertical-align: middle;text-align: center;" v-text="socio.numpapeleta"></td>
                <td style=" vertical-align: middle;text-align: center;" v-text="socio.ci+' '+ socio.abrvdep"></td>
                <td style=" vertical-align: middle;text-align: center;" v-text="socio.nomfuerza"></td>

                <td style=" vertical-align: middle;text-align: center;">
                  <div v-if="socio.activo">
                    <span class="badge badge-success">Activo</span>
                  </div>
                  <div v-else>
                    <span class="badge badge-danger">Desactivado</span>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <nav>
            <ul class="pagination">
              <li class="page-item" v-if="pagination.current_page > 1">
                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar)">Ant</a>
              </li>
              <li class="page-item" v-for="page in pagesNumber" :key="page"
                :class="[page == isActived ? 'active' : '']">
                <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar)" v-text="page"></a>
              </li>
              <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar)">Sig</a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalCalificacion">
      <div id="modalRegistroPrestamos" class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content animated fadeIn">
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModal"></h4>
            <button type="button" class="close" @click="cerrarModal('modalCalificacion')" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body-plandepagos1">

            <div class="form-group row" style=" margin-left: 3px;margin-right: 3px;justify-content: center;">
              <div class="col-md-3" style="text-align: center;">
                <img v-if="fotosocio" :src="'img/socios/'+fotosocio" class="fotosocioimg" alt="Cinque Terre" />
                <img v-else :src="'img/socios/avatar.png'" class="fotosocioimg" alt="Cinque Terre" />
              </div>
              <div class="row col-md-9" style="display: inherit;    align-content: baseline;">
                <p class="col-md-12 tituloP">{{grado}} {{nombre}} {{apaterno}} {{amaterno}}</p>
                <div class="col-md-6">
                  <table align="center" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td style="padding-right: 10px;">
                        <p class="titulo">Numero Papeleta:</p>
                      </td>
                      <td align="left">
                        <p class="contentTitulo" v-text="numpapeleta"></p>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding-right: 10px;">
                        <p class="titulo">Carnet de Identidad:</p>
                      </td>
                      <td align="left">
                        <p class="contentTitulo">{{ci}} {{ext}}</p>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding-right: 10px;">
                        <p class="titulo">Años Servicio:</p>
                      </td>
                      <td align="left">
                        <p class="contentTitulo" v-text="servicio"></p>
                      </td>
                    </tr>
                  </table>
                </div>

                <div class="col-md-6">
                  <table align="center" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td style="padding-right: 10px;">
                        <p class="titulo">Numero Aportes:</p>
                      </td>
                      <td align="left">
                        <p class="contentTitulo" v-text="total_aportes"></p>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding-right: 10px;">
                        <p class="titulo">Total Aporte:</p>
                      </td>
                      <td align="left">
                        <p class="contentTitulo">{{total_bs}} Bs.</p>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding-right: 10px;">
                        <p class="titulo">Garantias Vigentes:</p>
                      </td>
                      <td align="left">
                        <p class="contentTitulo" v-text="garantias"></p>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            <div class="card-header" style="margin-bottom: 12px;">
              <i class="fa fa-align-justify"></i>
              {{pasoPrestamo==0?'Descripción de Ingresos y Descuentos':(pasoPrestamo==1?'Valoración del Prestamo':(pasoPrestamo==2?'Registro de Garantes':(pasoPrestamo==3?'Registro del Prestamo':'')))}}
            </div>
            <!-- ///////////////////////////////////////////////////////Descripcion de ingresos y descuentos/////////////////////////////////////////////////////////////////////////// -->
            <div v-if="pasoPrestamo==0">
              <div class="form-group row animated fadeIn">
                <div class="col-md-6" style="border-right: 1px solid #c2cfd6; padding-bottom: 12px;">
                  <p class="col-md-12 titulopaso">Paso 1: Registro del Liquido Pagable</p>
                  <label class="col-md-12">Liquido pagable:</label>
                  <div class="col-md-12">
                    <div class="input-group">
                      <input class="col-md-12 form-control" style="background-color: white;"
                        v-validate.initial="'required|numeric'" type="number" v-model.number="liqpagable"
                        placeholder="ej.: 10000" name="liquido pagable" step="any" disabled />

                      <span class="input-group-append">
                        <button class="btn btn-primary" type="button" @click="regLiquidoPagable()">Modificar</button>
                      </span>
                    </div>
                  </div>
                  <span class="text-error col-md-12">{{ errors.first('liquido pagable')}}</span>

                  <label class="col-md-12">Liquido pagable computable:</label>
                  <div class="col-md-12">
                    <input
                      v-bind:class="(liquidopagablecomputable>0 ? 'col-md-12 form-control is-valid formpp':'col-md-12 form-control is-invalid formpp')"
                      type="number" class="form-control" placeholder="ej.: 10000" name="liquido pagable computable"
                      v-model.number="liquidopagablecomputable" readonly />
                  </div>
                </div>
                <div class="col-md-6">
                  <p class="col-md-12 titulopaso">Paso 2: Descuentos por Bonos</p>
                  <div class="col-md-12">
                    <label class="col-md-12" style="font-weight: 500;">Suma de cuotas de prestamos vigentes :</label>
                    <div class="col-md-12">
                      <div class="input-group">
                        <input style="background-color: white;" type="number" :value="prestamosvigentes"
                          class="form-control" placeholder="Suma de prestamos" name="Suma de prestamos" disabled />
                        <div class="input-group-append">
                          <span style="min-width: 60px;" class="input-group-text">
                            <i style="font-size: 13px;font-weight: 600;">BOB</i>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12" style="display: flex;">

                    <div class="col-md-6">
                      <label style="text-align: right; align-items: center;font-weight: 500;" class="form-control-label"
                        for="text-input">Bono Frontera:</label>
                      <div class="input-group">
                        <input v-validate.initial="'decimal:2'" type="number" class="form-control"
                          v-model.number="frontera" placeholder="ej.: 10000" name="Bono Frontera" />
                        <div class="input-group-append">
                          <span style="min-width: 60px;" class="input-group-text">
                            <i style="font-size: 13px;font-weight: 600;">BOB</i>
                          </span>
                        </div>
                      </div>
                    </div>




                    <div class="col-md-6">
                      <label style="text-align: right; align-items: center;font-weight: 500;" class="form-control-label"
                        for="text-input">Bono Pro Libro:</label>
                      <div class="input-group">
                        <input v-validate.initial="'decimal:2'" type="number" class="form-control"
                          v-model.number="prolibro" placeholder="ej.: 10000" name="Bono Pro Libro" />
                        <div class="input-group-append">
                          <span style="min-width: 60px;" class="input-group-text">
                            <i style="font-size: 13px;font-weight: 600;">BOB</i>
                          </span>
                        </div>

                      </div>
                    </div>


 

                  </div>

                  <div class="col-md-12" style="display: flex;">

                    <div class="col-md-6">
                      <label style="text-align: right; align-items: center;font-weight: 500;" class="form-control-label"
                        for="text-input">Bono Asig. Familiar:</label>
                      <div class="input-group">
                        <input v-validate.initial="'decimal:2'" type="number" class="form-control"
                          v-model.number="familiar" placeholder="ej.: 10000" name="Bono Asi" />
                        <div class="input-group-append">
                          <span style="min-width: 60px;" class="input-group-text">
                            <i style="font-size: 13px;font-weight: 600;">BOB</i>
                          </span>
                        </div>

                      </div>
                    </div>


                    <div class="col-md-6">
                      <label style="text-align: right; align-items: center;font-weight: 500;" class="form-control-label"
                        for="text-input">Bono Riesgo:</label>
                      <div class="input-group">
                        <input v-validate.initial="'decimal:2'" type="number" class="form-control"
                          v-model.number="riesgo" placeholder="ej.: 10000" name="Bono Riesgo" />
                        <div class="input-group-append">
                          <span style="min-width: 60px;" class="input-group-text">
                            <i style="font-size: 13px;font-weight: 600;">BOB</i>
                          </span>
                        </div>

                      </div>
                    </div>

                  </div>



                </div>
              </div>
            </div>
            <div v-else>
              <!-- ///////////////////////////////////////////////////////Valoracion del prestamo/////////////////////////////////////////////////////////////////////////// -->
              <div v-if="pasoPrestamo==1">
                <div class="animated fadeIn " style="padding: 0px 21px 0 29px;">
                  <p style="margin: 0px;" class="col-md-12 titulopaso">Paso 3: Valoracion del prestamo</p>

                  <div class="row">

                    <div class="col-md-4">
                      <label style="text-align: right;font-weight: 500;" class="form-control-label"
                        for="text-input">Producto :</label>
                      <v-select v-if="mostrarselect==1" ref="comboProducto" :class="{'error': errors.has('Producto')}"
                        v-validate.initial="'required'" name="Producto" label="nomproducto" :options="arrayProducto"
                        v-model="producto" placeholder="Seleccione producto"
                        :reduce="productoSS => productoSS.idproducto" :searchable="false" :clearable="false"
                       
                        @input="onChangeproducto(producto)">

                        <span slot="no-options">No existen Datos</span>
                        <template slot="option" slot-scope="option">{{ option.nomproducto }}</template>
                      </v-select>
                      <p class="text-error">{{ errors.first('Producto') }}</p>
                    </div>
                    <div class="col-md-4">
                      <label style="text-align: right; align-items: center;font-weight: 500;" class="form-control-label"
                        for="text-input">Total saldo capital de prestamos vigentes :</label>
                      <div class="input-group">
                        <input style="background-color: #f0f3f5;text-align: right;" type="number"
                          :value="total_saldo_capital" class="form-control" placeholder="Monto maximo"
                          name="monto maximo" step="any" disabled />
                        <div class="input-group-append">
                          <span class="input-group-text" style="min-width: 60px;">
                            <i style="font-size: 13px;font-weight: 600;" v-text="codigomoneda"></i>
                          </span>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <label style="text-align: right; align-items: center;font-weight: 500;" class="form-control-label"
                        for="text-input">Liquido Pagable :</label>
                      <div class="input-group">
                        <input style="background-color: #f0f3f5;text-align: right;" type="number"
                          :value="liquidopagablecomputable" class="form-control" placeholder="Monto maximo"
                          name="monto maximo" step="any" disabled />
                        <div class="input-group-append">
                          <span style="min-width: 60px;" class="input-group-text">
                            <i style="font-size: 13px;font-weight: 600;">BOB</i>
                          </span>
                        </div>
                      </div>
                    </div>


                  </div>

                  <div v-if="montomaximo==0">
                    <div v-if="errors.has('anios')" class="alert alert-danger" v-validate="'required'"
                      data-vv-name="anios" role="alert">{{ errors.first('anios') }}</div>
                  </div>
                  <div v-else>
                    <div v-if="producto>0">
                      <div class="form-group row">
                        <div class="col-md-4">
                          <label style="text-align: right; align-items: center;font-weight: 500;"
                            class=" form-control-label" for="text-input">Tasa de Interes Mensual :</label>
                          <div class="input-group">
                            <input style="background-color: #f0f3f5;text-align: right;" type="number"
                              :value="tasamensual" class="form-control" placeholder="Tasa de interes mensual"
                              name="tasa" step="any" disabled />
                            <div class="input-group-append">
                              <span style="min-width: 60px;" class="input-group-text">
                                <i class="fa fa-percent"></i>
                              </span>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-4">
                          <label style="text-align: right; align-items: center;font-weight: 500;"
                            class=" form-control-label" for="text-input">Tipo de Cambio :</label>
                          <div class="input-group">
                            <input style="background-color: #f0f3f5;text-align: right;" type="number"
                              :value="tipocambio" class="form-control" placeholder="Tipo de Cambio"
                              name="tipo de cambio" step="any" disabled />
                            <div class="input-group-append">
                              <span style="min-width: 60px;" class="input-group-text">
                                <i style="font-size: 13px;font-weight: 600;">BOB</i>
                              </span>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <label style="text-align: right; align-items: center;font-weight: 500;"
                            class="form-control-label" for="text-input">Monto Maximo :</label>
                          <div class="input-group">
                            <input style="background-color: #f0f3f5;text-align: right;" type="number"
                              :value="montomaximo" class="form-control" placeholder="Monto maximo" name="monto maximo"
                              step="any" disabled />
                            <div class="input-group-append">
                              <span style="min-width: 60px;" class="input-group-text">
                                <i style="font-size: 13px;font-weight: 600;" v-text="codigomoneda"></i>
                              </span>
                            </div>
                          </div>
                        </div>


                      </div>

                      <div class="form-group row">
                        <div class="col-md-4">
                          <label style="text-align: right; align-items: center;font-weight: 500;"
                            class="form-control-label" for="text-input">Plazo :</label>
                          <div class="input-group">
                            <input v-validate.initial="'required|between:'+plazomesesmin+','+plazomesesmax"
                              style="background-color: white;font-weight: bold;font-size: 20px;text-align: right;"
                              type="number" v-model.number="plazomeses"
                              :class="{'form-control': true, 'is-invalid': errors.has('plazo')}"
                              placeholder="Plazo en meses" name="plazo" autofocus step="any"
                              @keyup.enter="calculoCuota" />
                            <div class="input-group-append">
                              <span style="min-width: 60px;" class="input-group-text">
                                <i style="font-size: 13px;font-weight: 600;">Meses</i>
                              </span>
                            </div>
                          </div>
                          <span class="text-error">{{ errors.first('plazo')}}</span>
                        </div>
                        <div class="col-md-4">
                          <label style="text-align: right; align-items: center;font-weight: 500;"
                            class="form-control-label" for="text-input">Monto Solicitado :</label>
                          <div class="input-group">
                            <!-- <span v-if="montosolicitado>0&&montosolicitado>=(parseFloat(montominimo)+parseFloat(total_saldo_capital))" style="
                                display: grid;
                                position: fixed;
                                z-index: 10000;
                                height: 46px;
                                vertical-align: middle;
                                align-content: center;
                                padding-left: 9px;
                                font-size: 18px;
                                font-weight: 700;
                                color: rgba(247, 11, 11, 0.44);
                            ">(- {{total_saldo_capital}})
                                </span> -->
                            <input
                              v-validate.initial="'required|between:'+(getmontomin(montominimo,total_saldo_capital))+','+montomaximo"
                              style="background-color: white;font-weight: bold;font-size: 20px;text-align: right;"
                              type="number" v-model.number="montosolicitado"
                              :class="{'form-control': true, 'is-invalid': errors.has('monto solicitado')}"
                              placeholder="Monto solicitado" name="monto solicitado" step="any"
                              @keyup.enter="calculoCuota" />
                            <div class="input-group-append">
                              <span class="input-group-text" style="min-width: 60px;">
                                <i style="font-size: 13px;font-weight: 600;" v-text="codigomoneda"></i>
                              </span>
                            </div>
                          </div>
                          <span class="text-error">{{ errors.first('monto solicitado')}}</span>
                        </div>
                        <div class="col-md-4">
                          <label style="text-align: right; align-items: center;font-weight: 500;"
                            class="form-control-label" for="text-input">Monto aproximado a desembolsar :</label>
                          <div class="input-group">
                            <input
                              style="background-color: #f0f3f5;font-weight: bold;font-size: 20px;text-align: right;"
                              type="number" :value="getmontodesembolso" class="form-control" placeholder="Monto maximo"
                              name="monto maximo" step="any" disabled />
                            <div class="input-group-append">
                              <span style="min-width: 60px;" class="input-group-text">
                                <i style="font-size: 13px;font-weight: 600;" v-text="codigomoneda"></i>
                              </span>
                            </div>
                          </div>
                        </div>


                      </div>

                      <div class="form-group row">
                        <label style="text-align: right; align-items: center;font-weight: 900;font-size: 20px;"
                          class="col-md-4 form-control-label" for="text-input"> </label>




                        <div class="col-md-4">
                          <label style="text-align: right; align-items: center;font-weight: 500;"
                            class="form-control-label" for="text-input">Cuota aproximada :</label>
                          <div class="input-group">
                            <div class="input-group-append">
                              <span class="input-group-text" style="min-width: 60px;">
                                <i style="font-size: 13px;font-weight: 600;" v-text="codigomoneda"></i>
                              </span>
                            </div>
                            <input style="background-color: #f0f3f5;font-weight: bold;font-size: 23px;" type="text"
                              :value="cuotaaproximada" class="form-control" placeholder="Cuota aproximada"
                              name="Cuota aproximada" step="any" disabled />
                            <span v-if="cargandocalculo" class="input-group-append">
                              <div style="min-width: 60px;    border-radius: 5px;" class="lds-dual-ring-wait"></div>
                            </span>
                            <span v-else class="input-group-append" v-bind:class="{'ocultaricono':cuotaaproximada==0}">
                              <button style="min-width: 60px;" class="btn btn-primary" type="button"
                                @click="view();classModal.openModal('plandepagos');">
                                <i class="fa fa-file-pdf-o"></i>
                              </button>
                            </span>
                          </div>
                        </div>

                        <div class="col-md-4">
                          <label style="text-align: right; align-items: center;font-weight: 500;"
                            class="form-control-label" for="text-input">Factor determinante :</label>
                          <div class="input-group">
                            <div class="input-group-append">
                              <span style="min-width: 60px;display: grid;"
                                :class="{'input-group-text': true,'bg-danger': valorfactor<=maxfactor, 'bg-success': valorfactor>maxfactor}">
                                <i class="fa fa-percent"></i>
                              </span>
                            </div>
                            <input style="background-color: white;" type="text" :value="valorfactor"
                              :class="{'form-control': true, 'is-invalid factordeterminantecss': valorfactor<=maxfactor, 'is-valid factordeterminantecss2': valorfactor>maxfactor}"
                              placeholder="Factor" name="facvtor determinante" step="any" disabled />
                            <span v-if="cargandocalculo" class="input-group-append">
                              <div style="min-width: 60px;    border-radius: 5px;" class="lds-dual-ring-wait"></div>
                            </span>
                            <span v-else class="input-group-append" v-bind:class="{'ocultaricono':valorfactor==0}">
                              <button style="min-width: 60px;"
                                :class="{'btn': true, 'btn-danger': valorfactor<=maxfactor, 'btn-success': valorfactor>maxfactor}"
                                type="button" @click="classModal.openModal('factorview')">
                                <i class="fa fa-bar-chart"></i>
                              </button>
                            </span>
                          </div>
                          <div class="progress progress-xs mb-0">
                            <div
                              :class="{'progress-bar': true,'bg-danger': valorfactor<=maxfactor, 'bg-success': valorfactor>maxfactor}"
                              role="progressbar" v-bind:style="'width: '+valorfactor+'%'"></div>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div v-else>
                <!-- ///////////////////////////////////////////////////////Resgistro de garantes/////////////////////////////////////////////////////////////////////////// -->
                <div v-if="pasoPrestamo==2">
                  <div class="animated fadeIn">
                    <p class="col-md-12 titulopaso">Paso 4: Registro de Garantes ( {{totalgarantesseleccionados}} de
                      {{garantesporproducto}} )</p>

                    <div class="form-group row">
                      <div class="col-md-12">
                        <div class="input-group" style="align-items: center;">
                          <p style="text-align: right;margin: 0px; margin-right: 10px; font-weight: 500;">Criterio de
                            busqueda:</p>
                          <input :disabled="totalgarantesseleccionados==parseInt(garantesporproducto)" type="text"
                            v-model="buscargarante" @keyup.enter="listargarantes(buscargarante)" class="form-control"
                            placeholder="Ingresar  Nombres , Apellidos , Ci , Numero de Papeleta" />
                          <button :disabled="totalgarantesseleccionados==parseInt(garantesporproducto)" type="submit"
                            @click="listargarantes(buscargarante)" class="btn btn-primary">
                            <i class="fa fa-search"></i> Buscar
                          </button>
                        </div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-md-12">
                        <ul class="list-group listadogarantes"
                          style="overflow-y: auto;    max-height: 260px;min-height: 236px;    border: 2px solid #c2cfd6;">
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- ///////////////////////////////////////////////////////Resgistro del prestamo/////////////////////////////////////////////////////////////////////////// -->
                <div v-else-if="pasoPrestamo==3">
                  <div class="animated fadeIn">
                    <p class="col-md-12 titulopaso">Paso 5: Registro del Prestamo</p>
                    <div v-html="listagarantesregistrados()"></div>

                    <div class="form-group row">
                      <p class="col-md-12 titulo">Datos del prestamo:</p>
                    </div>

                    <div class="form-group row">
                      <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label"
                        for="text-input">Monto Solicitado :</label>
                      <div class="col-md-3">
                        <div class="input-group">
                          <input style="background-color: white;font-weight: bold;" type="number"
                            :value="montosolicitado" class="form-control" placeholder="Monto solicitado"
                            name="monto solicitado" step="any" disabled />
                        </div>
                      </div>

                      <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label"
                        for="text-input">Plazo en Meses :</label>
                      <div class="col-md-3">
                        <div class="input-group">
                          <input style="background-color: white;font-weight: bold;" type="number" :value="plazomeses"
                            class="form-control" placeholder="Plazo en meses" name="plazo" step="any" disabled />
                        </div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label"
                        for="text-input">Cuota aproximada :</label>
                      <div class="col-md-3">
                        <div class="input-group">
                          <input style="background-color: white;font-weight: bold;" type="text" :value="cuotaFinalSocio"
                            class="form-control" placeholder="Cuota aproximada" name="Cuota aproximada" step="any"
                            disabled />
                        </div>
                      </div>

                      <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label"
                        for="text-input">Factor determinante :</label>
                      <div class="col-md-3">
                        <div class="input-group">
                          <input style="background-color: white;font-weight: bold;" type="text" :value="valorfactor"
                            :class="{'form-control': true, 'is-invalid ': valorfactor<=maxfactor, 'is-valid ': valorfactor>maxfactor}"
                            placeholder="Factor" name="facvtor determinante" step="any" disabled />
                        </div>
                        <div class="progress progress-xs mb-0">
                          <div
                            :class="{'progress-bar': true,'bg-danger': valorfactor<=maxfactor, 'bg-success': valorfactor>maxfactor}"
                            role="progressbar" v-bind:style="'width: '+valorfactor+'%'"></div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label"
                        for="text-input">Numero de Cuenta :</label>
                      <div class="col-md-9">
                        <div style="display:flex">
                          <v-select ref="cuentaB" style="width: 100%" :class="{'error': errors.has('cuenta bancaria')}"
                            v-validate.initial="'required'" name="cuenta bancaria" label="numcuentasocio"
                            :getOptionLabel="cuentaB => cuentaB.siglaentidadbancaria+' - '+cuentaB.numcuentasocio"
                            :options="arrayCuentaSocio" v-model="cuentaBancaria" placeholder="Seleccione cuenta"
                            :reduce="cuentaB=> cuentaB.idcuentasocio" :searchable="false" :clearable="false">
                            <span slot="no-options">No existen Datos</span>
                            <template slot="option" slot-scope="option">{{ option.siglaentidadbancaria }} -
                              {{ option.numcuentasocio }}</template>
                          </v-select>
                          <span class="input-group-append">
                            <button class="btn btn-primary" type="button"
                              @click="openVueCuentaBancaria()">Editar</button>
                          </span>
                        </div>
                        <span class="text-error">{{ errors.first('cuenta bancaria') }}</span>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label"
                        for="text-input">Observaciones :</label>
                      <div class="col-md-9">
                        <div class="input-group">
                          <textarea class="col-md-12" rows="4" v-model="obs" placeholder="Observaciones"></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <button v-if="pasoPrestamo>0"  type="button" class="btn btn-success"
              @click="anteriorPaso()">Anterior</button>
            <button
              :disabled="  errors.any()?true:(pasoPrestamo==1?(valorfactor<=maxfactor):(pasoPrestamo==2?(!(totalgarantesseleccionados==parseInt(garantesporproducto))):false))"
              v-if="pasoPrestamo<3" type="button" class="btn btn-success" @click="siguientePaso()"
              id="btsig">Siguiente</button>

            <button :disabled="errors.any()" type="submit" v-if="pasoPrestamo==3" class="btn btn-primary"
              @click="regprestamo('modalCalificacion')">Registrar Prestamo</button>
            <button type="button" class="btn btn-secondary" @click="cerrarModal('modalCalificacion')">Cerrar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

    <!--Fin del modal-->

    <!-- para poner otro modal -->
    <div class="modal fade" tabindex="-1" role="dialog" style="z-index: 1600;" aria-hidden="true" id="plandepagos">
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content animated fadeIn">
          <div class="modal-header">
            <h4 class="modal-title" id="modalOneLabel">Plan de pagos</h4>
            <button type="button" class="close" aria-hidden="true" aria-label="Close"
              @click="classModal.openModal('modalCalificacion')">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body-plandepagos">
            <!--  <div style="display:none" v-html="plandepagosSimulacion"></div>-->
            <iframe name="planout" id="planout" style="width:100%;height:100%;"></iframe>
          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" type="button"
              @click="classModal.openModal('modalCalificacion')">cerrar</button>
            <!--  <button class="btn btn-primary" type="button" @click="print()">Imprimir</button> -->
          </div>
        </div>
      </div>
    </div>

    <!-- para poner otro modal -->
    <div class="modal fade" tabindex="-1" role="dialog" style="z-index: 1600;" aria-hidden="true" id="factorview">
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content animated fadeIn">
          <div class="modal-header">
            <h4 class="modal-title" id="modalOneLabel">Factores</h4>
            <button type="button" class="close" aria-hidden="true" aria-label="Close"
              @click="classModal.openModal('modalCalificacion')">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body" style="padding: 8px;">
            <div id="detallefactordeterminante"></div>
          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" type="button"
              @click="classModal.openModal('modalCalificacion')">cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- fin segundo modal -->

    <!-- para poner otro modal -->
    <div class="modal fade" tabindex="-1" role="dialog" style="z-index: 1600;" aria-hidden="true"
      id="factorviewgarante">
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content animated fadeIn">
          <div class="modal-header">
            <h4 class="modal-title" id="modalOneLabel">Factores (Garantes)</h4>
            <button type="button" class="close" aria-hidden="true" aria-label="Close"
              @click="classModal.openModal('modalCalificacion')">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body" style="padding: 8px;">
            <div id="detallefactordeterminantegarante"></div>
          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" type="button"
              @click="classModal.openModal('modalCalificacion')">cerrar</button>
          </div>
        </div>
      </div>
    </div>

       
    <cuentaBancaria @cerrarvue="cerrarModalvue" ref="ModalVueCuentaBancaria"></cuentaBancaria>

    <status ref="ModalVueStatus"></status>
  </main>
</template>

<script>
  
import vSelect from "vue-select";  
Vue.component("v-select", vSelect);
 
export default {
  props: ['idmodulo','object'],
  data() {
    return {  
      arrayPermisos:{Nuevo:0,Edición:0},
       objectlocal:this.object,
      classModal: null,
      fecha_actual: "",
      socio_id: "",
      numpapeleta: "",
      ci: "",
      apaterno: "",
      
      cuentaBancaria: "",
      amaterno: "",
      nombre: "",
      garantias: "0",
      grado: "",
      ext: "",
      fechasjson: "",
      fotosocio: "",
      reporte1: "",

      producto: "",
      obs: "",
      pasoPrestamo: 0,

      liqpagable: 0,
      frontera: 0,
      riesgo: 0,
      familiar: 0,
      prolibro: 0,

      tasaanual: 0,
      fechacorte: 0,
      tipocambio: 0,
      codigomoneda: "",
      plazomeses: 1,
      mostrarselect: 1,
      cancelarprestamos: 0,
      plazomesesmax: 1,
      plazomesesmin: 0,
      montomaximo: 0,
      montominimo: 0,
      factorid: 0,
      cuotaFinalSocio: 0,
      maxfactor: 0,
      valorfactor: 0,
      prestamosvigentes: 0,
      montosolicitado: 0,
      cuotaaproximada: 0,
      total_saldo_capital: 0,
      garantesporproducto: 0,
      validadorcapital:0,
      cargandocalculo:0,
      total_aportes: 0,
      islineal: 0,
      total_bs: 0,
      servicio: 0,
      arraysocio: [],
      arraygarantes: [],
      arrayperfilgarante: [],
      
      arrayProducto: [],
      arrayCuentaSocio: [],
      arrayFormulasProducto: [],
      garantesseleccionados: new Map(),
      totalgarantesseleccionados: 0,
      modal: 0,
      tituloModal: "",
      tipoAccion: 0,
      errorsocio: 0,
      errorMostrarMsjsocio: [],
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      offset: 3,
      buscar: "",
      buscargarante: "",

      nomsocio: "",
      tiempo: null,
      barChart: null,
      barChartG: null,
      PlandePagosPrint: null,
      dataCuentaBancaria: null
    };
  },

  formOptions: {
    validateAfterLoad: true,
    validateAfterChanged: true
  },

  computed: {
    
    liquidopagablecomputable: function() {
      let sum =
        parseFloat(this.liqpagable) -
        (parseFloat(this.riesgo) +
          parseFloat(this.familiar) +
          parseFloat(this.prolibro) +
          parseFloat(this.frontera) +
          parseFloat(this.prestamosvigentes));
      return sum > 0 ? this.redondeo(sum, 2) : 0;
    }, 
    tasamensual: function() {
      return this.tasaanual > 0
        ?  _pl.redondeo_valor(_pl.TEM(this.tasaanual), 2, false)
        : 0;
    },
 getmontodesembolso: function(){ 
 
      // return _pl.redondeo_valor(this.montosolicitado-this.total_saldo_capital);
      return _pl.redondeo_valor((this.validadorcapital==3)?this.montosolicitado:(this.montosolicitado-this.total_saldo_capital));
    },
    isActived: function() {
      return this.pagination.current_page;
    },
     
    pagesNumber: function() {
      if (!this.pagination.to) {
        return [];
      }

      var from = this.pagination.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }

      var to = from + this.offset * 2;
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
      }

      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    }
  },
  watch: {
    montosolicitado: function() {
      this.calculoCuota();
    },
    plazomeses: function() {
      this.calculoCuota();
    }
  },
  methods: {
      tttttttt(ddddd){
    console.log('llegada de variable=',ddddd); 
      },
      clean(){
        console.log('clean')
      },
    // cleandata() {
    //   this.valorfactor = 0;
    //   this.cuotaaproximada = 0;
    // },
     
          printer(idpres){ 
           return _pl._vm2154_12186_135(this.reporte1+idpres,'Calificación del Prestamo'); 
            },
        getRutasReports (){ 
            let me=this;
            var url= '/reporte1';
            axios.get(url).then(function (response) { 
                me.reporte1 = response.data['REP_PRECALIFICACION']; 
            })
            .catch(function (error) {
                console.log(error);
            });
        },
    getid(id){ 
                if(this.objectlocal!=null){  
                      this.objectlocal=null;
                      vue.to(-1); 
                  }
                  return id.idsocio;                 
    },
    getmontomin(a,b){
      if(this.validadorcapital==0||this.validadorcapital==3){
        return a;
      }else{
      return a>0?_pl.redondeo_valor(parseFloat(a)+parseFloat(b)):_pl.redondeo_valor(parseFloat(b));
      } 
    },
    calculoCuota: function() {
      this.valorfactor = 0;
         this.cuotaaproximada = 0;
      if (
        this.montosolicitado > 0 &&
        this.plazomeses > 0 &&
        !this.errors.any()
      ) { 
        this.cargandocalculo=1;
         this.generacuota(this);
      }  
       
    },generacuota:_.debounce((ls) => {
    
        var salida = (ls.tasaanual>0)?_pl._fff3512_23622(
          ls.fecha_actual,
          ls.fechasjson,
          ls.tasaanual,
          ls.montosolicitado,
          ls.plazomeses,
          ls.fechacorte,
          ls.prestamosvigentes, 
          ls.tipocambio,
          ls.arrayFormulasProducto
        )
:
       _pl._fff3512_23623(
          ls.fecha_actual,
          ls.fechasjson,
          ls.tasaanual,
          ls.montosolicitado,
          ls.plazomeses,
          ls.fechacorte,
          ls.prestamosvigentes, 
          ls.tipocambio,
          ls.arrayFormulasProducto
        );
 
        ls.PlandePagosPrint = salida.data;
        ls.cuotaaproximada = (ls.montosolicitado > 0 && ls.plazomeses > 0 && !ls.errors.any())  ? salida["cuota"]  : 0;
         if (ls.cuotaaproximada) {
          ls.cambiocuota();
        }
        ls.cargandocalculo=0;
    }, 550),
    viewstatus(socio) {
      this.$refs.ModalVueStatus.showVuestatus(socio);
    },
    listacuentabancaria(id) {
      this.arrayCuentaSocio = [];
      this.cuentaBancaria = "";
      let me = this;
      var url = "/par_producto/cuentas?idsocio=" + id;
      axios
        .get(url)
        .then(function(response) {
          me.arrayCuentaSocio = response.data.cuentas;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    openVueCuentaBancaria() {
      if (this.dataCuentaBancaria != null) {
        this.classModal.closeModal("modalCalificacion");
        this.$refs.ModalVueCuentaBancaria.showVue(this.dataCuentaBancaria);
      } else {
        console.log("Error al momento de enviar los datos del socio");
      }
    },
    cerrarModalvue() {
      this.$refs.cuentaB.clearSelection();
      this.listacuentabancaria(this.socio_id);
      this.classModal.openModal("modalCalificacion");
    },

    view() {
      _pl._vvp2521_00001(this.PlandePagosPrint);
    },
    async regGarantes(array, id) {
      let responses = true;
      try {
        for (var [key, garante] of array) {
          await axios
            .post("/garantes/reggarante", {
              idsocio: garante.idsocio,
              factor: garante.factorg,
              idprestamo: id
            })
            .then(function(response) {
              responses = true;
            })
            .catch(function(error) {
              responses = false;
            });
        }
      } catch (err) {
        responses = false;
      }
      return responses;
    },
    regprestamo(id) {
      let me = this;
      this.cerrarModal(id);
      swal({
        title: "Registrando los Datos",
        allowOutsideClick: () => false,
        allowEscapeKey: () => false,
        onOpen: function() {
          swal.showLoading();
        }
      }).catch(error => {
        swal.showValidationError("Request failed: ${error}");
      });

      axios
        .post("/prestamos/regprestamo", {
          idsocio: this.socio_id,
          idproducto: this.producto,
          monto: this.montosolicitado,
          plazo: this.plazomeses,
          factor: this.valorfactor,
          cuenta: this.cuentaBancaria,
          obs: this.obs, 
          lipcom:this.liquidopagablecomputable,
          lip:this.liqpagable,
          riesgo:this.riesgo,
          fami:this.familiar,
          libro:this.prolibro,
          fron:this.frontera,
          pvig:this.prestamosvigentes,
          cuo_aprox:this.cuotaFinalSocio  
        })
        .then(function(response) {
                   

 
          if (me.garantesporproducto > 0) {
            me.regGarantes(me.garantesseleccionados, response.data.id).then(
              function(responses) {
                if (responses) { 
                 
                    me.printer(response.data.id).then(result => {
                   
                  swal({
                    title: "¿Desea realizar el desembolso?",
                    type: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#4dbd74",
                    cancelButtonColor: "#f86c6b",
                    confirmButtonText: "Si",
                    cancelButtonText: "No",
                    buttonsStyling: true,
                    reverseButtons: true
                  }).then(result => {
                    if (result.value) {
                     // vue.to(33, response.data.id);
                      vue.to(32, response.data.id);
                    } else {
                      swal(
                        "¡Se registro los datos correctamente!",
                        "",
                        "success"
                      ).then(result => {
                        me.listarSocio(1, "", "nombre");
                      });
                    }
                  });
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
          } else { 
                   me.printer(response.data.id).then(result => {
              
            swal({
              title: "¿Desea realizar el desembolso?",
              type: "question",
              showCancelButton: true,
              confirmButtonColor: "#4dbd74",
              cancelButtonColor: "#f86c6b",
              confirmButtonText: "Si",
              cancelButtonText: "No",
              buttonsStyling: true,
              reverseButtons: true
            }).then(result => {
              if (result.value) {
                //vue.to(33, response.data.id);
                vue.to(32, response.data.id);
              } else {
                swal(
                  "¡Se registro los datos correctamente!",
                  "",
                  "success"
                ).then(result => {
                  me.listarSocio(1, "", "nombre");
                });
              }
            });
            });
 
          }
        })
        .catch(function(error) {
          console.log(error);
          swal("¡Error al registrar los datos!", error.message, "error");
        });
    },
    regLiquidoPagable() {
      let me = this;
      swal({
        title: "Registro del Liquido Pagable",
        html:
          '<div class="form-group row "> <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label" for="text-input">Fecha de Papeleta : </label>' +
          '<div class="col-md-9">  <div class="input-group"> <input class="form-control" id="fechapapeleta"   type="text"    placeholder="Fecha de registro de papeleta" autocomplete="off"></div> </div> </div>' +
          '<div class="form-group row "> <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label" for="text-input">Liquido Pagable : </label>' +
          '<div class="col-md-9"> <div class="input-group"> <input  id="liquidopagableswal" type="number"  class="form-control"  placeholder="Liquido pagable" autofocus step="any"></div> </div></div>',
        showConfirmButton: true,
        allowOutsideClick: () => false,
        allowEscapeKey: () => false,
        showCancelButton: true,
        confirmButtonText: "Registrar",
        cancelButtonText: "Cancelar",
        showLoaderOnConfirm: true,
        confirmButtonColor: "#4dbd74",
        cancelButtonColor: "#f86c6b",
        buttonsStyling: true,
        reverseButtons: true,
        onOpen: function() {
          me.classModal.closeModal("modalCalificacion");
          swal.disableConfirmButton();
          $("#liquidopagableswal").on("input", function() {
            var dInput = this.value;
            if (dInput > 0) swal.enableConfirmButton();
            else swal.disableConfirmButton();
          });
          _pl.datapicker(
            "fechapapeleta",
            "1200-01-01",
            me.fecha_actual,
            me.fecha_actual
          );
        },
        onClose: function() {
          me.classModal.openModal("modalCalificacion");
        },
        preConfirm: function() {
          me.classModal.openModal("modalCalificacion");
          return new Promise(function() {
            swal.showLoading();
             me.liqpagable = $("#liquidopagableswal").val(); 
                  axios
                    .post("/socio/updateliquido", {
                      idsocio: me.socio_id,
                      fechapapeleta: $("#fechapapeleta").val(),
                      liquido: $("#liquidopagableswal").val()
                    })
                    .then(function(response) {
                      swal(
                        "¡Se registro el dato exitosamente!",
                        "",
                        "success"
                      ).then(result => {
                        me.listarSocio(1, me.buscar);
                      });
                    })
                    .catch(function(error) {
                      console.log(error);
                      swal(
                        "¡ocurrio un problema al registrar el dato!",
                        error,
                        "error"
                      );
                    }); 
          });
        }
      }).catch(error => {
        swal.showValidationError("Request failed: ${error}");
      });
      $(".swal2-modal").css("z-index", "2000");
      $(".swal2-container").css("z-index", "2000");
    },

    getTotalcapital(idproducto){  
      var conta="&conta=1"; 
        
      let me =this;
       axios.get('/getsaldocapital_desembolso?idsocio='+this.socio_id+'&idpro='+idproducto+conta) 
       .then(function(response) {
         var salidaout=response.data.capital; 
         if(salidaout>=0){
              me.total_saldo_capital=response.data.capital; 
              if(me.total_saldo_capital>me.montomaximo){
                                        var valorm=me.montomaximo;
                                        me.errors.add({
                                          id: "8301791",
                                          field: "anios",
                                          msg:
                                            "El saldo capital es mayor al monto maximo ("+valorm+" "+me.codigomoneda+") del producto seleccionado."
                                        });
                                        me.montomaximo=0;
              }
         }else {
           me.montomaximo=0;
           me.total_saldo_capital=0;
           salidaout=salidaout*(-1); 
            
            if(salidaout==10){
               me.errors.add({ id: "8301791", field: "anios",  msg:  "Error de configuracion del producto, contactese con el administrador del Sistema." });
            }else if(salidaout==15){
              me.errors.add({ id: "8301791", field: "anios",  msg:  "Existe una variación con el saldo capital y el monto solicitado, contactese con el administrador del Sistema parta verificación de datos." });
            }else if(salidaout==25){
              me.errors.add({ id: "8301791", field: "anios",  msg:  "El socio tiene prestamos pendientes de aprovación por contabilidad." });
            }else{
             me.errors.add({ id: "8301791", field: "anios",  msg:  "Error no identificado, contactese con el administrador del Sistema." });
            }
             

         }
                        
 
        }) .catch(function(response) {
          console.log(response);
        });
    },

    listagarantesregistrados() {
      return _pl._mg3612152_215(this.garantesseleccionados);
    },
    cambiocuota() {
      if (
        this.montosolicitado > 0 &&
        this.plazomeses > 0 &&
        !this.errors.any()
      ) {
        _pl.getcriterio(this);
      } else {
        this.valorfactor = 0;
      }
    },
 
    onChangeproducto(e) {
      if (e != null) {
        let me = this;
        
        me.montomaximo = 0;
        me.montominimo = 0; 
        me.maxfactor = 0;
        me.tasaanual = 0;
        me.montosolicitado = 0;
        me.cargandocalculo=0;
        me.cuotaaproximada = 0;
        me.total_saldo_capital = 0;
        me.cancelarprestamos = 0; 
        me.obs = "";
        me.tipocambio = 0;
        me.cuentaBancaria = "";
        me.codigomoneda = ""; 
        me.plazomeses = 1;
        me.plazomesesmax = 1;
        me.factorid = 0;
        me.valorfactor = 0;
        me.plazomesesmin = 0; 
        me.islineal=0;
        me.garantesporproducto = 0;
        me.arrayFormulasProducto = [];
        me.garantesseleccionados.clear();
        me.totalgarantesseleccionados = 0;
        me.validadorcapital=0;
        var url =
          "/par_producto/productosid?id=" + e +  "&totalmesesaporte=" + me.total_aportes + "&idsocio=" +  me.socio_id;
        axios .get(url) .then(function(response) {
            var respuesta = response.data;
            me.tasaanual = respuesta.productos[0].tasa;
            me.tipocambio = respuesta.productos[0].tipocambio;
            me.codigomoneda = respuesta.productos[0].codmoneda;
            me.islineal = respuesta.productos[0].linea;
            me.plazomesesmax = respuesta.productos[0].plazomaximo;
            me.cancelarprestamos = respuesta.productos[0].cancelarprestamos;
            me.plazomesesmin = respuesta.productos[0].plazominimo;
            me.factorid = respuesta.productos[0].idfactor;
            me.maxfactor = respuesta.productos[0].aprobacion;
            me.garantesporproducto = respuesta.productos[0].garantes;
            me.validadorcapital=respuesta.cuotasvalidador; 
            me.getTotalcapital(e); 
             me.getprestamossocio(me.socio_id,respuesta.cuotasvalidador,e);
            if (respuesta.status == 0) { 
             if(respuesta.cuotasvalidador==0||respuesta.cuotasvalidador==3) {
                        if (respuesta.escala.length > 0) {
                          me.errors.removeById("8301791");
                          me.montomaximo = parseInt(respuesta.escala[0].maxmonto);
                          me.montominimo = parseInt(respuesta.escala[0].minmonto);
                          me.arrayFormulasProducto["cobranza"] = respuesta.formulas;
                          me.arrayFormulasProducto["desembolso"] = [];
                            console.log('formulas');
console.log(respuesta.formulas);
                        } else {
                          me.montomaximo=0;
                          me.errors.add({
                            id: "8301791",
                            field: "anios",
                            msg:
                              "El socio no cumple con la cantidad minima de aportes requeridos por el producto seleccionado"
                          });
                        }
            }else if(respuesta.cuotasvalidador==1) {
                        if (respuesta.escala.length > 0) {
                          me.errors.removeById("8301791");
                          me.montomaximo = parseInt(respuesta.escala[0].maxmonto);
                          me.montominimo = parseInt(respuesta.escala[0].minmonto);
                          me.arrayFormulasProducto["cobranza"] = respuesta.formulas;
                          me.arrayFormulasProducto["desembolso"] = [];
                             console.log('formulas');
console.log(respuesta.formulas);
                        } else {
                          me.montomaximo=0;
                          me.errors.add({
                            id: "8301791",
                            field: "anios",
                            msg:
                              "El socio no cumple con la cantidad minima de aportes requeridos por el producto seleccionado"
                          });
                        }
            }else if(respuesta.cuotasvalidador==2) {
                        if (respuesta.escala.length > 0) {
                          me.errors.removeById("8301791");
                          me.montomaximo = parseInt(respuesta.escala[0].maxmonto);
                          me.montominimo = parseInt(respuesta.escala[0].minmonto);
                          me.arrayFormulasProducto["cobranza"] = respuesta.formulas;
                          me.arrayFormulasProducto["desembolso"] = [];
                        console.log('formulas');
console.log(respuesta.formulas);
                        } else {
                          me.montomaximo=0;
                          me.errors.add({
                            id: "8301791",
                            field: "anios",
                            msg:
                              "El socio no cumple con la cantidad minima de aportes requeridos por el producto seleccionado"
                          });
                        }
            }else if(respuesta.cuotasvalidador==10) {
              me.montomaximo=0;
              me.errors.add({
                id: "8301791",
                field: "anios",
                msg:
                  "Error de configuracion del producto, contactese con el administrador del Sistema."
              });
            } 

 

            } else if(respuesta.cuotasvalidador==0) {
              me.montomaximo=0;
              me.errors.add({
                id: "8301791",
                field: "anios",
                msg:
                  "Ya existe un prestamo con el mismo producto para el socio seleccionado"
              });
            }else if(respuesta.cuotasvalidador==1) {
              if(respuesta.status<2){
                          if (respuesta.escala.length > 0) {
                          me.errors.removeById("8301791");
                          me.montomaximo = parseInt(respuesta.escala[0].maxmonto);
                          me.montominimo = parseInt(respuesta.escala[0].minmonto);
                          me.arrayFormulasProducto["cobranza"] = respuesta.formulas;
                          me.arrayFormulasProducto["desembolso"] = [];
                            console.log('formulas');
console.log(respuesta.formulas);
                        } else {
                          me.montomaximo=0;
                          me.errors.add({
                            id: "8301791",
                            field: "anios",
                            msg:
                              "El socio no cumple con la cantidad minima de aportes requeridos por el producto seleccionado"
                          });
                        }
              }else{
                me.montomaximo=0;
                me.errors.add({
                            id: "8301791",
                            field: "anios",
                            msg:
                              "El socio tiene "+respuesta.status+" prestamos vigentes, no puede optar a otro nuevo."
                          });
              }
                

            }else if(respuesta.cuotasvalidador==2) {
                if(respuesta.status<2){
                        if (respuesta.escala.length > 0) {
                          me.errors.removeById("8301791");
                          me.montomaximo = parseInt(respuesta.escala[0].maxmonto);
                          me.montominimo = parseInt(respuesta.escala[0].minmonto);
                          me.arrayFormulasProducto["cobranza"] = respuesta.formulas;
                          me.arrayFormulasProducto["desembolso"] = [];
                            console.log('formulas');
console.log(respuesta.formulas);
                        } else {
                          me.montomaximo=0;
                          me.errors.add({
                            id: "8301791",
                            field: "anios",
                            msg:
                              "El socio no cumple con la cantidad minima de aportes requeridos por el producto seleccionado"
                          });
                        }
                  }else{
                    me.montomaximo=0;
                    me.errors.add({
                                id: "8301791",
                                field: "anios",
                                msg:
                                  "El socio tiene "+respuesta.status+" prestamos vigentes, no puede optar a otro nuevo."
                              });
                  }

            }else if(respuesta.cuotasvalidador==3) {
                        if (respuesta.escala.length > 0) {
                          me.errors.removeById("8301791");
                          me.montomaximo = parseInt(respuesta.escala[0].maxmonto);
                          me.montominimo = parseInt(respuesta.escala[0].minmonto);
                          me.arrayFormulasProducto["cobranza"] = respuesta.formulas;
                          me.arrayFormulasProducto["desembolso"] = [];
                          console.log('formulas');
console.log(respuesta.formulas);

                        } else {
                          me.montomaximo=0;
                          me.errors.add({
                            id: "8301791",
                            field: "anios",
                            msg:
                              "El socio no cumple con la cantidad minima de aportes requeridos por el producto seleccionado"
                          });
                        }
            }else if(respuesta.cuotasvalidador==10) {
              me.montomaximo=0;
              me.errors.add({
                id: "8301791",
                field: "anios",
                msg:
                  "Error de configuracion del producto, contactese con el administrador del Sistema."
              });
            } 
        
          })
          .catch(function(error) {
            console.log(error);
          });
      }
    },
    siguientePaso() {
      switch (this.pasoPrestamo) {
        case 0:
          if (this.liquidopagablecomputable > 0) {

            $('#modalRegistroPrestamos').removeClass('modal-lg');
            $('#modalRegistroPrestamos').addClass('modal-xl');
            this.listarProductos();
            this.producto = "";
            this.tasaanual = 0;
            this.montomaximo = 0;
            this.maxfactor = 0;
            this.montominimo = 0;
            this.tipocambio = 0;
            this.cuotaaproximada = 0;
            this.total_saldo_capital=0;
            this.codigomoneda = "";
            this.plazomeses = 1;
            this.cancelarprestamos = 0;
            this.factorid = 0;
            this.plazomesesmax = 1;
            this.plazomesesmin = 0;
            this.garantesporproducto = 0;
            this.garantesseleccionados.clear();
            this.totalgarantesseleccionados = 0;
            this.montosolicitado = 0;
            this.cargandocalculo = 0;
            this.obs = "";
            this.cuentaBancaria = "";
          
            this.arrayProducto = [];
            this.valorfactor = 0;
            this.buscargarante = "";
            this.pasoPrestamo++;
          }
          break;
        case 1:
            $('#modalRegistroPrestamos').removeClass('modal-xl');
            $('#modalRegistroPrestamos').addClass('modal-lg'); 
          this.buscargarante = "";
          this.cuotaFinalSocio = this.cuotaaproximada;
          if (this.garantesporproducto == 0) {
            this.pasoPrestamo += 2;
          } else {
            this.listargarantes(this.buscargarante);
            this.pasoPrestamo++;
          }
          break;
        case 2:
          this.pasoPrestamo++;
          break;
        case 3:
          this.pasoPrestamo++;
          break;
        default:
          this.pasoPrestamo++;
          break;
      }
    },
    anteriorPaso() {
      switch (this.pasoPrestamo) {
        case 0:
          this.pasoPrestamo--;  
          break;
        case 1:
          swal({
            title: "¿Esta seguro retornar a un proceso anterior?",
            text: "Los datos registrados seras eliminados",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Aceptar",
            cancelButtonText: "Cancelar",
            confirmButtonColor: "#4dbd74",
            cancelButtonColor: "#f86c6b",
            buttonsStyling: true,
            reverseButtons: true
          }).then(result => {
            if (result.value) {
            $('#modalRegistroPrestamos').removeClass('modal-xl');
            $('#modalRegistroPrestamos').addClass('modal-lg');
            this.getprestamossocio(this.socio_id);
              this.pasoPrestamo--;
            }
          });
          break;
        case 2:
          swal({
            title: "¿Esta seguro retornar a un proceso anterior?",
            text: "Los datos registrados seras eliminados",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Aceptar",
            cancelButtonText: "Cancelar",
            confirmButtonColor: "#4dbd74",
            cancelButtonColor: "#f86c6b",
            buttonsStyling: true,
            reverseButtons: true
          }).then(result => {
            if (result.value) {
              $('#modalRegistroPrestamos').removeClass('modal-lg');
              $('#modalRegistroPrestamos').addClass('modal-xl');
              this.garantesseleccionados.clear();
              this.totalgarantesseleccionados = 0;
              this.buscargarante = "";
              this.pasoPrestamo--;
            }
          });
          break;
        case 3:
          this.buscargarante = "";
          if (this.garantesporproducto == 0) {
            $('#modalRegistroPrestamos').removeClass('modal-lg');
              $('#modalRegistroPrestamos').addClass('modal-xl');
            this.pasoPrestamo -= 2;
          } else {
            this.listargarantes(this.buscargarante);
            this.pasoPrestamo--;
          }
          break;
        default:
          this.pasoPrestamo--;
          break;
      }
    }, 
    validateBeforeSubmit() {
      this.$validator.validateAll().then(result => {
        if (result) {
          return;
        }
        return;
      });
    },
    getprestamossocio(id,validador=0,pro=0) {
      let me = this;
      var url = "/prestamos/getprestamostotal?idsocio=" + id+'&pro='+pro+'&valor='+validador;
      axios.get(url).then(function(response) {
          var respuesta = response.data;
          me.prestamosvigentes = me.redondeo(respuesta.cuotas, 2);
        }).catch(function(response) {
          console.log(response);
        });
    },
    listarSocio(page, buscar) {
      let me = this;
      var url = "/pre_listasocio?page=" + page + "&buscar=" + buscar;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arraysocio = respuesta.socios.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function(response) {
          console.log(response);
        });
    },
    listargarantes(textoint) {
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
    },

    listarProductos() {
       //this.$refs.comboProducto.clearSelection();
        let me = this;
        this.mostrarselect=0;
        me.arrayProducto = []; 
        var url = "/par_producto/productos";
        axios .get(url).then(function(response) { 
            var respuesta = response.data;
            me.arrayProducto = respuesta.productos; 
             me.mostrarselect=1;
          })
          .catch(function(error) {
            console.log(error);
          });
    },
    getfecha() {
      let me = this;

      var url = "/getdatacalculo";
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.fecha_actual = respuesta[0].fecha;
          me.fechacorte = respuesta[0].corte;
          me.fechasjson = respuesta[0].fechas;
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    cambiarPagina(page, buscar) {
      let me = this;
      //Actualiza la página actual
      me.pagination.current_page = page;
      //Envia la petición para visualizar la data de esa página
      me.listarSocio(page, buscar);
    },
    redondeo(value, precision) {
      return _pl.redondeo_valor(value, precision);
    },

    cerrarModal(id) {
      this.classModal.closeModal(id);
      this.pasoPrestamo = 0;
      this.fotosocio = "";

      this.garantias = 0;
      this.dataCuentaBancaria = null;
    },

    abrirModal(id, accion, data) { 
       this.getRutasReports();
       $('#modalRegistroPrestamos').removeClass('modal-xl');
       $('#modalRegistroPrestamos').addClass('modal-lg');
      this.dataCuentaBancaria = data;

      this.classModal.openModal(id);
      this.errors.clear();
      switch (accion) {
        case "calificar": {
          this.producto = "";
          this.obs = "";
          this.cuentaBancaria = "";
          this.tasaanual = 0;
          this.valorfactor = 0;
          this.montomaximo = 0;
          this.maxfactor = 0;
          this.montominimo = 0;
          this.montosolicitado = 0;
          this.cargandocalculo = 0;
          this.tipocambio = 0;
          this.codigomoneda = "";
          this.cancelarprestamos = 0;
          this.plazomesesmax = 1;
          this.plazomesesmin = 0;
          this.islineal=0;
          this.frontera = 0;
          this.riesgo = 0;
          this.familiar = 0;
          this.prolibro = 0;
          this.liqpagable = data.liquidopagable_papeleta;
          this.plazomeses = 1;
          this.factorid = 0;
          this.tituloModal = "Registro de Prestamos";
          this.tipoAccion = 1;
          this.socio_id = data.idsocio;
          this.nombre = data.nombre;
          this.apaterno = data.apaterno;
          this.amaterno = data.amaterno;
          this.grado = data.nomgrado;
          this.fotosocio = data.rutafoto;
          this.ci = data.ci;
          this.numpapeleta = data.numpapeleta;
          this.idgrado = data.idgrado;
          this.ext = data.abrvdep;
          this.total_aportes = data.cantaportes;
          this.total_bs = data.totalaportes;
          this.servicio = this.redondeo(this.total_aportes / 12);
          this.garantias = data.totalgarantias;
          this.prestamosvigentes = 0; // debe obtener la suma de todos los prestmos anteriores....
          this.getprestamossocio(data.idsocio);
          this.listacuentabancaria(data.idsocio);
          break;
        }
      }
    }
  },
  mounted() {
    this.listarSocio(1, this.buscar);
    this.listarProductos();
    this.getRutasReports();
    this.getfecha();
    this.classModal = new _pl.Modals();
    this.classModal.addModal("modalCalificacion");
    this.classModal.addModal("plandepagos");
    this.classModal.addModal("factorview");
    this.classModal.addModal("factorviewgarante"); 
    
     
    //$("#tttt *").prop('disabled',true); inhabilita todos los botones
  }
};
</script>
<style>
.titulopaso {
  /*font-weight: bold;   */
  color: dodgerblue;
  text-align: center;
  font-size: 16px;
}
.titulo {
  font-weight: bold;
  /*  text-align: right;*/
  margin-bottom: 2px;
}
.contentTitulo {
  margin-bottom: 2px;
}
.tituloP {
  text-align: center;
  font-size: x-large;
}

.ocultaricono {
  display: none !important;
}

.ocultarBotonsiguiente {
  display: none !important;
}
.factordeterminantecss {
  background-color: #ff0000b8 !important;
  color: white !important;
  font-weight: bold;
  font-size: 23px;
  -webkit-transition: width 0.35s ease-in-out;
  transition: width 0.35s ease-in-out;
}

.factordeterminantecss2 {
  background-color: #4dbd7478 !important;
  color: black !important;
  font-weight: bold;
  font-size: 23px;
}
.fotosocioimg {
  border: #efefef 2px solid;
  filter: drop-shadow(4px 4px 5px #333);
  width: 65% !important;
  max-width: 100%;
}
.div-error {
  display: flex;
  justify-content: center;
}
.text-error {
  color: red !important;
  font-style: italic;
}
.fotosociomini_pre {
  display: inline-block;
  border: #efefef 1px solid;
  filter: drop-shadow(0px 0px 3px #333);
  width: 45px !important;
  margin: 0px !important;
}
.formpp {
  background-color: transparent !important;
}
.selecterror {
  background-image: none !important;
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
