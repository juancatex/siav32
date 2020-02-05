<template>
<main>    
<div style="display:none" id="divMausoleo">
    <div class="card" v-if="divAmbientes">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6 titcard">
                    <span v-if="sector=='F'">FOSA COMÚN</span>
                    <span v-else >{{regServicio.nomsector}}S - BLOQUE "{{bloque}}"</span>
                </div>
                <div class="col-md-6 text-right">
                    <div class="input-group-append" style="display:inline">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                            Nichos<span class="caret"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                            <a href="#" class="dropdown-item" v-for="k in regServicio.bloquesnicho" :key="k" 
                                v-text="'Bloque '+String.fromCharCode(k+64)" @click="cargarBloque('N',String.fromCharCode(k+64))"></a>
                        </div>
                    </div>
                    <div class="input-group-append"  style="display:inline">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                            Sarcógafos<span class="caret"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                            <a href="#" class="dropdown-item" v-for="k in regServicio.bloquessarco" :key="k" 
                                v-text="'Bloque '+String.fromCharCode(k+64)" @click="cargarBloque('S',String.fromCharCode(k+64))"></a>
                        </div>
                    </div>
                    <button class="btn btn-primary"  style="display:inline" @click="cargarBloque('F','F')">Fosa Común</button>
                </div>
            </div>
        </div>
     
        <div class="card-body">
            <div class="text-center table-responsive" v-if="sector!='F'">
                <table class="table-bordered" align="center">
                    <tr v-for="fil in filas" :key="fil" class="bloquefila">
                        <td class="titcard" style="background-color:#f0f3f5" v-text="bloque+(filas+1-fil)"></td>
                        <td v-for="ambiente in arrayAmbientes" v-if="ambiente.fila==(filas+1-fil)" class="bloquecelda" 
                            @click="ambiente.ocupado?verAsignacion(ambiente.idasignacion,ambiente):asignarCliente(ambiente)" 
                            :key="ambiente.idambiente" :class="{ocupado:ambiente.ocupado}" :title="quienOcupa(ambiente.idambiente)">
                            <span v-text="ambiente.caja<10?'0'+ambiente.caja:ambiente.caja" ></span>
                        </td>
                    </tr>
                </table>
            </div>

            <div v-if="sector=='F'">
                <table align="center">
                    <tr><td v-for="fsa in regServicio.bloquesfosa" :key="fsa" class="bloquefila" style="padding:10px">
                        <table class="table-bordered">
                            <tr><td align="center" v-text="'F'+fsa" class="card-header titcard bloquefila"></td></tr>
                            <tr v-for="ambiente in arrayAmbientes" v-if="ambiente.fila==fsa">
                                <td v-text="ambiente.caja<10?'0'+ambiente.caja:ambiente.caja" class="bloquecelda"
                                    :class="{ocupado:ambiente.ocupado}" :title="quienOcupa(ambiente.idambiente)"
                                    @click="ambiente.ocupado?verAsignacion(ambiente.idasignacion,ambiente):asignarCliente(ambiente)">
                                </td>
                            </tr>
                        </table>
                    </td></tr>
                </table>
            </div>
        </div>
    </div><!-- lista de ambientes -->

    <div class="row" v-if="!divAmbientes">
        <div class="col-md-4"> <!-- datos del nicho / sarco -->
            <div class="card">
            <div class="card-header titcard"> Datos del <span v-text="regServicio.nomsector"></span>  </div>
            <div class="card-body">
                <table width="100%" class="table table-bordered table-sm">
                    <tr v-for="fil in filas" :key="fil">
                        <td v-for="caj in cajas" :key="caj" 
                        :class="{ocupado:(regAmbiente.caja==caj)&&(regAmbiente.fila==(filas+1-fil))}"></td>
                    </tr>
                </table>
                <div class="row">
                    <div class="col-md-6">
                    <table>
                        <tr><td class="titcampo">Código:</td><td v-text="regAmbiente.codambiente"></td></tr>
                        <tr><td class="titcampo">Precio:</td><td v-text="regAmbiente.alquiler+' Bs.'"></td></tr>
                    </table>
                    </div>
                    <div class="col-md-6">
                    <table>
                        <tr><td class="titcampo">Bloque:</td><td v-text="regAmbiente.bloque"></td></tr>
                        <tr><td class="titcampo">Fila: </td> <td v-text="regAmbiente.fila"></td></tr>
                        <tr><td class="titcampo">Nicho:</td> <td v-text="regAmbiente.caja"></td></tr>
                    </table>
                    </div>
                </div>
            </div>
            </div>
        </div> <!-- datos del nicho / sarco -->

        <div class="col-md-8"><!-- kardex socio-->
            <div class="card">
                <div class="card-header titcard"> Datos del Fallecido </div>
                <div class="card-body">
                    <div class="row" v-if="regCliente.tipocliente=='s'">
                        <div class="col-md-3">
                            <!-- <img v-if="rutafoto && datossocio" :src="'img/socios/'+rutafoto" width="150" height="150" class="fotosocio"> -->
                            <img src="img/socios/avatar2.png" class="fotosocioservicios">
                        </div>
                        <div class="col-md-9">
                            <div class="nombrecliente text-center" v-text="regCliente.nomcliente"></div>
                            <div class="row">
                                <div class="col-md-6">
                                    <table>
                                        <tr><td class="titcampo">Fuerza:  </td><td v-text="regCliente.nomfuerza"></td> </tr>
                                        <tr><td class="titcampo">Situación:</td><td v-text="regCliente.nomestado"></td> </tr>                                
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table>
                                        <tr><td class="titcampo">Nro C.I.:</td><td v-text="regCliente.ciexp"></td> </tr>
                                        <tr><td class="titcampo">Papeleta:</td><td v-text="regCliente.numpapeleta"></td> </tr>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <div class="card text-white bg-danger" v-if="divNovalido">
                                <div class="card-header text-center"><b>No puede acceder a este servicio</b></div>
                                <div class="card-body text-center" style="background-color:#fff; color:#000;">
                                    <span v-html="txtValidacion"></span> 
                                </div>
                            </div>
                            <div class="row" v-if="!regAsignacion">
                                <div class="col-md-12 text-center">
                                <button class="btn btn-secondary" style="margin:5px" @click="cargarvue(regServicio)">Cancelar Asignación</button>
                                <button class="btn btn-primary" style="margin:5px" @click="asignarCliente(regAmbiente)">Cambiar Fallecido</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
        </div>  <!-- kardex del socio-->
    </div>    

    <div class="row" v-if="!divAmbientes&&!divNovalido"> <!-- ficha entrada + ficha pagos -->
        <div class="col-md-4"> <!-- inhumacion -->
            <div class="card">
                <div class="card-header titcard">Inhumación</div>
                <div class="card-body">
                    <table align="center" v-if="regAsignacion.idasignacion">
                        <tr><td class="titcampo">Defunción:</td>  
                            <td v-text="fechalat(regAsignacion.fechadefuncion)"></td></tr>
                        <tr><td class="titcampo"> Inhumacion:</td>
                            <td v-text="fechalat(regAsignacion.fechaentrada)"></td></tr>
                        <tr><td class="titcampo"> Orden Nro:</td>
                            <td v-text="regAsignacion.nrasignacion"></td></tr>
                        <tr><td class="titcampo" valign="top">Responsable:</td>
                            <td v-text="regResponsable.nombre+' '+regResponsable.apaterno"></td></tr>
                        <tr><td class="titcampo">Parentesco:</td>       
                            <td v-text="regResponsable.parentesco"></td></tr>
                        <tr><td class="titcampo">Celular:</td>          
                            <td v-text="regResponsable.telcelular"></td></tr>
                        <tr><td class="titcampo">Tarifa Definida:</td>           
                            <td v-text="regAmbiente.alquiler+'Bs.'"></td></tr>
                        <tr><td class="titcampo">Estadía cumplida:</td> 
                            <td><span v-text="regAsignacion.aaa"></span>
                                <span v-text="regAsignacion.aaa==1?'año':'años'"></span>,
                                <span v-text="regAsignacion.mmm"></span>
                                <span v-text="regAsignacion.mmm==1?'mes':'meses'"></span>
                            </td></tr>
                        <tr><td class="titcampo" valign="top">Documentos:</td>
                            <td><ul style="list-style:none; padding-left:0px">
                                    <li v-for="requisito in arrayRequisitos" :key="requisito.iddocumento">
                                        <i v-if="requisito.entregado" class="cui-check" style="color:#4dbd74"></i>
                                        <i v-else class="cui-circle-x" style="color:red"></i>
                                        <span v-text="requisito.nomdocumento"></span>
                                    </li>
                                </ul>                                
                            </td>
                        </tr>
                    </table>
                    <p v-if="regAsignacion.obs1"><span class="titcampo">OBS:</span>{{regAsignacion.obs1}}</p>
                    <center>
                    <button v-if="!regAsignacion.idasignacion" class="btn btn-primary" @click="registrarEntrada()">Registrar Inhumación</button>
                    <button v-else class="btn btn-primary" @click="editarEntrada()">Modificar Inhumación</button>
                    </center>
                </div>
            </div>                
        </div> <!-- inhumacion -->

        <div class="col-md-8"> <!-- pagosss -->
            <div class="card">
                <div class="card-header titcard">Administración de Pagos</div>
                <div class="card-body">
                    <div class="table-responsive" v-if="regAsignacion.idasignacion">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                        <tr>
                            <th>Nr</th>
                            <th>Responsable</th>
                            <th>Concepto</th>
                            <th>Fecha</th>
                            <th>Cod. Op.</th>
                            <th>Importe</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(pago,index) in arrayPagos" :key="pago.idpago">
                            <td v-text="index+1" align="center"></td>
                            <td v-text="pago.nombre+' '+pago.apaterno"></td>
                            <td v-text="pago.concepto"></td>
                            <td v-text="fechalat(pago.fecha)" align="center"></td>
                            <td v-text="pago.nrdocumento" align="right"></td>
                            <td v-text="pago.importe" align="right"></td>
                            <td align="center"><button @click="editarPago(pago)" class="btn btn-success btn-sm">
                                <i class="icon-pencil"></i></button></td>
                        </tr>
                        </tbody>
                        <tfoot >
                        <tr><td style="border:none" colspan="4"><b>Saldo por completar: <span v-text="tsaldo"></span>Bs.</b></td>
                            <td style="border:none" align="right"><b>Total Pagos:</b></td>
                            <td style="border:none" align="right"><b><span v-text="tpagado"></span>Bs.</b></td>
                        </tr>
                        </tfoot>
                    </table>
                    </div>

                    <center v-if="regAsignacion.idasignacion">
                        <button style="margin:5px" class="btn btn-primary" @click="registrarPago()">Registrar Pago</button>
                    </center>
                </div>
            </div>
        </div> <!-- pagosss -->
    </div> <!-- ficha entrada + ficha pagos -->
    <br>

    <div class="row" v-if="!divAmbientes&&!divNovalido&&regAsignacion.fechaentrada"> <!-- exhumacion + traslado-->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header titcard">Exhumación</div>
                <div class="card-body">
                    <table v-if="regAsignacion.fechasalida">
                        <tr><td class="titcampo" nowrap>Fecha Exhumacion:</td>
                            <td v-text="fechalat(regAsignacion.fechasalida)"></td></tr>
                        <tr><td class="titcampo" valign="top">Responsable:</td>
                            <td v-text="regResponsable.nombre+' '+regResponsable.apaterno"></td></tr>
                        <tr><td class="titcampo">Parentesco:</td>       
                            <td v-text="regResponsable.parentesco"></td></tr>
                        <tr><td class="titcampo">Celular:</td>          
                            <td v-text="regResponsable.telcelular"></td></tr>
                        <tr><td class="titcampo">Total Pagado:</td>           
                            <td v-text="tpagado+'Bs.'"></td></tr>
                        <tr><td class="titcampo">Estadía cumplida:</td> 
                            <td></td></tr>                        
                        </table>
                        <br>
                    <center v-if="regAsignacion.idasignacion">
                    <button class="btn btn-primary" @click="registrarSalida()">Registrar Exhumación</button>
                    </center>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header titcard">Traslado</div>
                <div class="card-body text-center" >
                    Registre la exhumación para el traslado. Con esta operación liberará el {{regServicio.nomsector}}. El proceso es irreveresible.<br>
                    <div class="row" v-if="regAsignacion.fechasalida">
                        <div class="col-md-4" style="padding:5px">
                            <button class="btn btn-primary col-md-12" :disabled="(sector=='S'||sector=='F')?true:false" 
                                    @click="traslado('S')">Traslado a Sarcófago</button>
                        </div>    
                        <div class="col-md-4" style="padding:5px">
                            <button class="btn btn-primary col-md-12" :disabled="sector=='F'?true:false"
                                    @click="traslado('F')" >Traslado a Fosa Común</button>
                        </div>
                        <div class="col-md-4" style="padding:5px">
                            <button class="btn btn-primary col-md-12" @click="traslado('R')">Retiro definitivo</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div> <!-- exhumacion + traslado-->
</div>
    





    <!-- MODAL MAUSOLEO --><!-- MODAL MAUSOLEO --><!-- MODAL MAUSOLEO --><!-- MODAL MAUSOLEO --><!-- MODAL MAUSOLEO -->
    <!-- MODAL MAUSOLEO --><!-- MODAL MAUSOLEO --><!-- MODAL MAUSOLEO --><!-- MODAL MAUSOLEO --><!-- MODAL MAUSOLEO -->
    <div class="modal " :class="{'mostrar':modalAsignar}" >
        <div class="modal-dialog modal-primary modal-lg">
        <div class="modal-content  animated fadeIn">
            <div class="modal-header">
                <h4 class="modal-title">
                    <span v-text="'Sector '+regServicio.nomsector+'s'"></span> -
                    <span v-text="proceso=='inh'?'Inhumación':'Exhumación'"></span>
                </h4>
                <button type="button" class="close" @click="modalAsignar=0">×</button>                    
            </div>
            <div class="modal-body">
                <div class="text-center"><span class="nombrecliente" v-text="'FALLECIDO: '+regCliente.nomcliente"></span> </div>
                <div class="row" style="padding:10px 0px;">
                    <div class="col-md-2"><b>Código:</b> <span v-text="regAmbiente.codambiente"></span> </div>
                    <div class="col-md-2"><b>sector:</b>   <span v-text="regServicio.nomsector"></span></div>
                    <div class="col-md-2"><b>Bloque:</b> <span v-text="regAmbiente.bloque"></span></div>
                    <div class="col-md-2"><b>Fila:</b>   <span v-text="regAmbiente.fila"></span> </div> 
                    <div class="col-md-2"><b><span v-text="regServicio.nomsector"></span>:</b> <span v-text="regAmbiente.caja"></span> </div> 
                    <div class="col-md-2"><b>Tarifa:</b> <span v-text="regAmbiente.alquiler+'Bs'"> </span></div> 
                </div>
                <div class="row">
                    <div class="col-md-4" :class="{'col-md-6':proceso=='exh'}" style="padding-top:10px;">
                        <div v-if="proceso=='inh'">
                            <h5 class="titazul">Inhumación</h5>
                            <table>
                                <tr><td>Defunción <span class="txtvalidador">*</span></td>
                                    <td><input type="date" class="form-control" v-model="fechadefuncion" @change="validarInh()"></td>
                                </tr>
                                <tr><td nowrap>Inhumación <span class="txtvalidador">*</span></td>
                                    <td><input type="date" class="form-control" v-model="fechainhumacion" @change="validarInh()"></td>
                                </tr>
                                <tr><td>Nro Orden <span class="txtvalidador">*</span></td>
                                    <td><input type="text" class="form-control" v-model="nrasignacion" @keyup="validarInh()"></td>
                                </tr>
                            </table>
                            <div class="text-right txtvalidador" v-if="!completo">* Estos campos son obligatorios</div>
                        </div>
                        <div v-if="proceso=='exh'">
                            <h5 class="titazul">Exhumación</h5>
                            <table>
                                <tr><td>Exhumación:</td>
                                    <td><input type="date" class="form-control" v-model="fechaexhumacion"></td>
                                </tr>
                                <tr><td>Nro Orden</td>
                                    <td><input type="text" class="form-control" v-model="nrasignacion"></td>
                                </tr>
                            </table>
                            <div class="text-right txtvalidador" v-if="!completo">* Estos campos son obligatorios</div>
                        </div>
                   </div>
                    <div class="col-md-4" v-if="proceso=='inh'" style="padding-top:10px;">                        
                        <h5 class="titazul">Documentos</h5>
                        <ul style="list-style:none; padding-left:5px;">
                            <li v-for="requisito in arrayRequisitos" :key="requisito.iddocumento">
                            <input type="checkbox" v-model="arrayDocumentos" :value="requisito.iddocumento">
                            <span v-text="requisito.nomdocumento"></span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4" :class="{'col-md-6':proceso=='exh'}" style="padding-top:10px;">                        
                        <h5 class="titazul">Responsable </h5>
                        <table>
                            <tr><td nowrap>Nombre: <span class="txtvalidador">*</span></td>
                                <td><select @change="selResponsable(idresponsable),validarInh()" class="form-control" v-model="idresponsable">
                                        <option v-for="beneficiario in arrayBeneficiarios" :key="beneficiario.idbeneficiario"
                                            :value="beneficiario.idbeneficiario" v-text="beneficiario.nombre+' '+beneficiario.apaterno">
                                        </option>
                                        <option value="0">Otro...</option>
                                    </select>
                                </td>
                            </tr>
                            <template v-if="idresponsable">
                            <tr><td>Relación:</td><td v-text="regResponsable.parentesco"></td> </tr>
                            <tr><td>Nro C.I.:</td><td v-text="regResponsable.ci+' '+regResponsable.abrvdep"></td> </tr>
                            <tr><td>Celular:</td> <td v-text="regResponsable.telcelular"></td> </tr>
                            <tr><td colspan="2" align="center"><a href="#" @click="editarResponsable()">Modificar Datos</a></td></tr>
                            </template>
                        </table>
                        <span v-if="!idresponsable" class="txtvalidador">Este campo es obligatorio</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 input-group">
                        <span style="padding-right:5px">Observaciones:</span>
                        <input type="text" class="form-control" v-model="obs1">
                    </div>

                </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="modalAsignar=0">Cerrar</button>
            <button v-if="proceso=='inh'&&accion==1" :disabled="!completo" class="btn btn-primary" @click="storeAsignacion()">Guardar Inhumación</button>
            <button v-if="proceso=='inh'&&accion==2" :disabled="!completo" class="btn btn-primary" @click="updateAsignacion()">Guardar Cambios</button>

            <button v-if="proceso=='exh'&&accion==1" :disabled="!completo" class="btn btn-primary" @click="storeExhumacion()">Guardar Exhumación</button>
            <button v-if="proceso=='exh'&&accion==2" :disabled="!completo" class="btn btn-primary" @click="updateExhumacion()">Guardar Cambios</button>
        </div>

        </div>
        </div>
    </div><!-- fin modal mausoleo-->


    <!-- MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS-->
    <!-- MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS MODAL PAGOS-->
    <div class="modal" :class="{'mostrar':modalPagos}">
        <div class="modal-dialog modal-primary modal-lg">
        <div class="modal-content  animated fadeIn">
            <div class="modal-header">
                <h4 class="modal-title"><span v-text="'Pago de '+regServicio.nomsector"></span></h4>
                <button type="button" class="close" @click="modalPagos=0">×</button>                    
            </div>
            <div class="modal-body">
                <div class="text-center"><span class="nombrecliente" v-text="'FALLECIDO: '+regCliente.nomcliente"></span> </div>
                <div class="row" style="padding:10px 0px;">
                    <div class="col-md-2"><b>Código:</b> <span v-text="regAmbiente.codambiente"></span> </div>
                    <div class="col-md-2"><b>Sector:</b> <span v-text="regServicio.nomsector+'s'"></span></div>
                    <div class="col-md-2"><b>Bloque:</b> <span v-text="regAmbiente.bloque"></span></div>
                    <div class="col-md-2"><b>Fila:</b>   <span v-text="regAmbiente.fila"></span> </div> 
                    <div class="col-md-2"><b><span v-text="regServicio.nomsector"></span>:</b> <span v-text="regAmbiente.caja"></span> </div> 
                    <div class="col-md-2"><b>Tarifa:</b> <span v-text="regAmbiente.alquiler+'Bs'"> </span></div> 
                </div>
                <br>
                <div class="row">
                    <div class="col-md-4">
                        <table>
                            <tr>
                                <td>Responsable:</td>
                                <td><select @change="selResponsable(idresponsable),validarPago()" class="form-control" v-model="idresponsable">
                                        <option v-for="beneficiario in arrayBeneficiarios" :key="beneficiario.idbeneficiario"
                                            :value="beneficiario.idbeneficiario" v-text="beneficiario.nombre+' '+beneficiario.apaterno">
                                        </option>
                                        <option value="0">Otro...</option>
                                    </select>
                                </td>
                            </tr>
                            <template v-if="idresponsable">
                            <tr><td>Relación:</td><td v-text="regResponsable.parentesco"></td> </tr>
                            <tr><td>Nro C.I.:</td><td v-text="regResponsable.ci+' '+regResponsable.abrvdep"></td> </tr>
                            <tr><td>Celular:</td> <td v-text="regResponsable.telcelular"></td> </tr>
                            <tr><td colspan="2" align="center"><a href="#" @click="editarResponsable()">Modificar Datos</a></td></tr>
                            </template>
                        </table>
                        <span v-if="!idresponsable" class="txtvalidador">Este campo es obligatorio</span>
                    </div>
                    <div class="col-md-4">
                        <table>
                            <tr><td nowrap>Gestiones: <font color="#4dbd74"><b>*</b></font></td>
                                <td><input type="text" class="form-control" v-model="periodo" @keyup="validarPago()"></td></tr>
                            <tr><td nowrap>Cod. Operación:<font color="#4dbd74"><b>*</b></font></td>
                                <td><input type="text" class="form-control" v-model="nrdocumento" @keyup="validarPago()"></td></tr>
                            <tr><td>Fecha: <font color="#4dbd74"><b>*</b></font></td>
                                <td><input type="date" class="form-control" v-model="fechapago" @change="validarPago()"></td></tr>
                        </table>                            
                    </div>
                    <div class="col-md-4">
                        <table>
                            <tr><td>Importe: <font color="#4dbd74"><b>*</b></font></td>
                                <td><div class="input-group">
                                        <input type="text" class="form-control text-right" v-model="importe" @keyup="validarPago()">
                                        <div class="input-group-append"><span class="input-group-text">Bs.</span></div>
                                    </div></td></tr>
                            <tr><td nowrap>Perf. Cuenta: <font color="#4dbd74"><b>*</b></font> </td>
                                <td>
                                    <!--
                                    <select class="form-control" v-model="idperfilcuenta" @change="validarPago()">
                                <option v-for="perfil in arrayPerfiles" :key="perfil.idperfilcuentamaestro"
                                v-text="perfil.nomperfil" :value="perfil.idperfilcuentamaestro"></option>
                                </select>
                                -->
                                </td></tr>
                            <tr><td colspan="2" align="right">
                                <span v-if="completo" class="txtvalidador">* Estos campos son obligatorios</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row" v-if="completo">
                    <div class="col-md-12" >
                        <span class="titcampo">Glosa generada</span>(para Contabilidad): 
                        <i v-text="glosa"></i>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" @click="modalPagos=0">Cerrar</button>
                <button v-if="accion==1" :disabled="!completo" class="btn btn-primary" @click="storePago()">Guardar Pago</button>
                <button v-if="accion==2" :disabled="!completo" class="btn btn-primary" @click="updatePago()">Guardar cambios del pago</button>
            </div>
        </div>
    </div>
    <ser_busqueda @clienteEncontrado="definirCliente($event)" ref="buscarCliente"></ser_busqueda>
</div> <!-- div Mausoleo-->
</main>    
</template>

<script>
//import Vue from 'vue'; 
//Vue.component("ser_busqueda", require("./submod_Buscarcliente.vue").default);

import * as jsfechas from '../../fechas.js';

export default {
    data(){ return {
        divAmbientes:1, divNovalido:0, divFosa:'', proceso:'', completo:'', jsfechas:'',
        arrayAmbientes:[], arrayBeneficiarios:[], arrayOcupantes:[],
        arrayDocumentos:[], arrayRequisitos:[], arrayPerfiles:[],
        regAmbiente:[], regServicio:[], 
        modalAsignar:0, modalPagos:0, accion:1, completo:0,
        sector:'', bloque:'', selnicho:'A', selsarco:'', selfosa:'',
        bloques:'',filas:'',cajas:'',
        
        fechadefuncion:'', fechainhumacion:'', fechaexhumacion:'',
        idresponsable:'', nrasignacion:'', obs1:'', obs2:'',
        arrayPagos:[], idpago:'', nrdocumento:'', periodo:'', //pagos
        fechapago:'', importe:'', idperfilcuenta:'', glosa:'',//pagos
        tpagado:0, tsaldo:0, //pagos

        regCliente:[], regAmbiente:[], regAsignacion:[], regAsignacionPrevio:[], regResponsable:[],
    }},

    methods:{
        cargarvue(servicio){
            $('#divMausoleo').css('display','block');
            this.regServicio=servicio;
            this.regAsignacion='';
            this.divAmbientes=1;
            this.jsfechas=jsfechas;
            this.cargarBloque('N','A');            
        },

        cerrarvue(){
            $('#divMausoleo').css('display','none');
        },

        cargarBloque(sector,bloque){
            var url='/ser_asignacion/listaOcupantes?idestablecimiento='
                +this.regServicio.idestablecimiento+'&sectorbloque='+sector+bloque;
               
            axios.get(url).then(function(response){
                me.arrayOcupantes=response.data.ocupantes;
            });   

            this.regServicio.bloquesnicho=2; 
            this.regServicio.bloquessarco=5; 
            this.regServicio.bloquesfosa=9;
            this.sector=sector;
            this.bloque=bloque;
            if(sector=='N') { 
                this.regServicio.nomsector='Nicho';
                this.filas=5; 
                this.cajas=14; 
                this.selsarco=''
            }
            if(sector=='S') { 
                this.regServicio.nomsector='Sarcófago';
                if(bloque=='A') { this.filas=4; this.cajas=20; }
                if(bloque=='B') { this.filas=6; this.cajas=18; }
                if(bloque=='C') { this.filas=6; this.cajas=18; }
                if(bloque=='D') { this.filas=4; this.cajas=20; }
                if(bloque=='E') { this.filas=6; this.cajas=8;  }
                this.selnicho='';
            }
            if(sector=='F'){
                //this.regServicio.sector='F';
                this.regServicio.nomsector='Fosa';
                this.cajas=10;
                bloque='F';
                sector='C';
                this.selnicho='';
                this.selsarco='';
                //this.divAmbientes=0;
            }
            let me=this;
            var url='/ser_asignacion/listaAmbientes?codtiposervicio='+this.regServicio.codtiposervicio
                +'&idestablecimiento='+this.regServicio.idestablecimiento+'&sector='+sector+'&bloque='+bloque;
                           
            axios.get(url).then(function(response){
                me.arrayAmbientes=response.data.ambientes;
            });

            /*
            var url='/ser_pago/listaPerfiles';
            axios.get(url).then(function(response){
                me.arrayPerfiles=response.data.perfiles;
            });           
            */
        },

        quienOcupa(idambiente){
            for(var i=0;i<this.arrayOcupantes.length;i++)
            {   if(this.arrayOcupantes[i].idambiente==idambiente)
                    return this.arrayOcupantes[i].nomgrado+' '
                        +this.arrayOcupantes[i].apaterno+' '
                        +this.arrayOcupantes[i].amaterno+' '
                        +this.arrayOcupantes[i].nombre;
            }
        },

        asignarCliente(ambiente)
        {   this.regAmbiente=ambiente;          
            if(this.sector=='S') {   
                if(!this.regCliente.idcliente) {
                    swal('Operación no válida','La asignación de un Sarcófago es a partir de la exhumación desde un Nicho','error'); 
                    return; 
                }
                this.verCliente(this.regAsignacion.idcliente,this.regAsignacion.tipocliente);
                this.regAsignacionPrevio=this.regAsignacion;
                this.regAsignacion='';
                this.arrayPagos='';
                return;
            }
            if(this.sector=='F'){
                if(!this.regCliente.idcliente) {
                    swal('Operación no válida','Debe realizar la exhumación desde un Sarcófago','error'); 
                    return; 
                }
                this.verCliente(this.regAsignacion.idcliente,this.regAsignacion.tipocliente);
                this.regAsignacionPrevio=this.regAsignacion;
                this.regAsignacion='';
                this.arrayPagos='';
                return;
            }
            this.$refs.buscarCliente.cargarModal(this.regAmbiente);
        },


        definirCliente(cliente)
        {   if(cliente.nomgrado.length>1) var sector='s'; 
            else var sector='c';
            this.verCliente(cliente.idcliente,sector);
            this.regAsignacion='';
        },

        listaBeneficiarios(idcliente){
            let me=this;
            var url='/ser_asignacion/listaBeneficiarios?idsocio='+me.regCliente.idcliente;
            axios.get(url).then(function(response){
                me.arrayBeneficiarios=response.data.beneficiarios;
                me.selResponsable(me.regAsignacion.idresponsable);//ficha de entrada
                if(me.arrayBeneficiarios.length==0) {
                    me.divNovalido=1;
                    me.txtValidacion='No existen familiares registrados.'
                        +'<br>Registre Familiares en el módulo SOCIOS <i class="cui-arrow-right"></i> BENEFICIARIOS';
                }
            });
        },

        verCliente(idcliente,sector){
            this.divAmbientes=0;
            this.divNovalido=0;
            let me=this;
            var url='/ser_asignacion/kardexCliente?idcliente='+idcliente+'&tipocliente='+sector;
            axios.get(url).then(function(response){
                me.regCliente=response.data.cliente[0];
                me.regCliente.nomcliente=me.regCliente.apaterno+' '
                    +me.regCliente.amaterno+' '+me.regCliente.nombre;
                if(sector=='s') 
                    me.regCliente.nomcliente=me.regCliente.nomgrado+' '+me.regCliente.nomcliente;
                me.regCliente.ciexp=me.regCliente.ci+' '+me.regCliente.abrvdep;
                me.regCliente.tipocliente=sector;
                me.listaBeneficiarios(me.regCliente.idcliente);         
            });
        },        

        registrarEntrada(){
            this.modalAsignar=1;
            this.accion=1;
            this.proceso='inh';
            this.resetAsignacion();
            if((this.sector=='S')||(this.sector=='F')) {
                this.fechadefuncion=this.regAsignacionPrevio.fechadefuncion;
                this.fechainhumacion=this.regAsignacionPrevio.fechasalida;
            }
        },

        validarInh(){
            this.completo=0;
            if((this.fechadefuncion)
                &&(this.fechainhumacion)
                &&(this.nrasignacion)
                &&(this.idresponsable)
                )this.completo=1;
        },

        validarPago(){
            this.completo=0;
            if((this.periodo)
                &&(this.nrdocumento)
                &&(this.fechapago)
                &&(this.importe)
                &&(this.idperfilcuenta)){
                    this.completo=1;
                    this.glosa=this.regServicio.nomestablecimiento+', pago Alquiler de '
                        +this.regServicio.nomsector+' '+this.regAmbiente.codambiente+', gestión '
                        +this.periodo+' con Depósito Nro '+this.nrdocumento+', hecho por '
                        +this.regResponsable.nombre+' '+this.regResponsable.apaterno+' ('
                        +this.regResponsable.parentesco+')';                    
                }
        },

        resetAsignacion(){
            this.fechadefuncion='';
            this.fechainhumacion='';
            this.fechaexhumacion='';
            this.idresponsable='';
            this.nrasignacion='';
        },

        editarEntrada(){
            this.modalAsignar=1;
            this.proceso='inh';
            this.fechadefuncion=this.regAsignacion.fechadefuncion;
            this.fechainhumacion=this.regAsignacion.fechaentrada;
            this.nrasignacion=this.regAsignacion.nrasignacion;
            this.idresponsable=this.regAsignacion.idresponsable;
            this.accion=2;
            this.validarInh();
        },

        registrarSalida(){
            this.modalAsignar=1;
            this.proceso='exh';
            this.accion=1;
            this.resetAsignacion();
        },

        selResponsable(idbeneficiario){
            if(idbeneficiario==0) {
                swal('Otro Responsable',
                    'Debe registrar a otro responsable o familiar en el módulo Afiliaciones &rarr; Beneficiarios',
                    'warning');
                this.idresponsable='';
            }
            for(var i=0;i<this.arrayBeneficiarios.length;i++){
                if(this.arrayBeneficiarios[i].idbeneficiario==idbeneficiario) {
                    this.regResponsable=this.arrayBeneficiarios[i]; break;
                }
            }
        },

        editarResponsable(){
            swal('Datos del responsable','Modifique estos datos en el módulo Afiliaciones &rarr; Beneficiarios','warning');
        },

        listaDocumentos(){
            let me=this;
            var url='/ser_asignacion/listaDocumentos?iddocumentos='+this.regServicio.iddocumentos;
            axios.get(url).then(function(response){
                me.arrayRequisitos=response.data.documentos;
                me.arrayDocumentos=JSON.parse('['+me.regAsignacion.iddocumentos+']');
                //console.log(me.arrayDocumentos);
                //console.log(me.arrayRequisitos);
                for(var i=0;i<me.arrayDocumentos.length;i++){
                    var j=me.arrayDocumentos[i];
                    //me.arrayRequisitos[j].entregado=1;
                }                
            })
        },

        traslado(destino){
            this.divAmbientes=1;
            switch(destino){
                case 'S': this.cargarBloque('S','A'); this.selsarco='A';break;
                case 'F': this.cargarBloque('F',1); break;
                case 'R': alert('dirgir al inicio de mausoleo');break;
            }
        },

        verAsignacion(idasignacion,ambiente){
            this.divAmbientes=0;
            this.regAmbiente=ambiente;
            let me=this;
            var url='/ser_asignacion/verAsignacion?idasignacion='+idasignacion;
            axios.get(url).then(function(response){
                me.regAsignacion=response.data.asignacion[0];
                me.regAsignacion.aaa=Math.floor(me.regAsignacion.dias/365);
                var dias=me.regAsignacion.dias-(me.regAsignacion.aaa*365)
                me.regAsignacion.mmm=Math.floor(dias/31);
                me.verCliente(me.regAsignacion.idcliente,me.regAsignacion.tipocliente);
                me.listaDocumentos();
                me.listaPagos(me.regAsignacion.idasignacion);
            });
        },

        storeAsignacion(){
            this.modalAsignar=0;
            this.divAmbientes=0;
            axios.post('/ser_asignacion/storeAsignacion',{
                'idcliente':this.regCliente.idcliente,
                'tipocliente':this.regCliente.tipocliente,
                'idambiente':this.regAmbiente.idambiente,
                'nrasignacion':this.nrasignacion,
                'fechasolicitud':'',
                'mesboleta':'',
                'ocupantes':'',
                'iddocumentos':this.arrayDocumentos.join(),
                'idimplementos':'',
                'fechaentrada':this.fechainhumacion,
                'horaentrada':'',
                'fechadefuncion':this.fechadefuncion,
                'idresponsable':this.idresponsable,
                'actividad':'',
                'idrepresentante':'',
                'obs1':this.obs1,
                'sector':this.sector,//pal traslado
                'idasignacionprevio':this.regAsignacionPrevio.idasignacion,//pal traslado
                'idambienteprevio':this.regAsignacionPrevio.idambiente,//pal traslado
            }).then(function(){
                swal('Registro Correcto','Se ha registrado el ingreso','success');
            });
            var url='/ser_asignacion/verAsignacion?idambiente='+this.regAmbiente.idambiente;
            let me=this;
            axios.get(url).then(function(response){
                me.regAsignacion=response.data.asignacion[0];
                me.verAsignacion(me.regAsignacion.idasignacion,me.regAmbiente);
            });
        },

        updateAsignacion(){
            this.modalAsignar=0;
            axios.put('/ser_asignacion/updateAsignacion',{
                'idasignacion':this.regAsignacion.idasignacion,
                'nrasignacion':this.nrasignacion,
                'fechasolicitud':'',
                'mesboleta':'',
                'ocupantes':'',
                'iddocumentos':this.arrayDocumentos.join(),
                'idimplementos':'',
                'fechaentrada':this.fechainhumacion,
                'horaentrada':'',
                'fechadefuncion':this.fechadefuncion,
                'idresponsable':this.idresponsable,
                'actividad':'',
                'idrepresentante':'',
                'obs1':this.obs1,
            }).then(function(){
                swal('Actualizado','Se han guardado los cambios realizados','success');
            });
            this.verAsignacion(this.regAsignacion.idasignacion,this.regAmbiente);
        },              
        

/*
        storeInhumacion(){
            this.modalAsignar=0;
            this.divAmbientes=0;
            var url='/ser_asignacion/storeInhumacion';
            axios.post(url,{
                'idcliente':this.regCliente.idcliente,
                'tipocliente':this.regCliente.tipocliente,
                'idambiente':this.regAmbiente.idambiente,
                'iddocumentos':this.arrayDocumentos.join(),
                'fechadefuncion':this.fechadefuncion,
                'fechaentrada':this.fechainhumacion,
                'idresponsable':this.idresponsable,
                'nrasignacion':this.nrasignacion,
                'sector':this.sector,//pal traslado
                'idasignacionprevio':this.regAsignacionPrevio.idasignacion,//pal traslado
                'idambienteprevio':this.regAsignacionPrevio.idambiente,//pal traslado
                'idusuario':1
            }).then(function(response){
                swal('Registro correcto','Se ha registrado la inhumación. Proceda al cobro','success');
            });  
            this.verAsignacion(this.regAmbiente);
        },
*/
/*
        updateInhumacion(){
            this.modalAsignar=0;
            this.divAmbientes=0;
            var url='/ser_asignacion/updateInhumacion';
            axios.put(url,{
                'idasignacion':this.regAsignacion.idasignacion,
                'iddocumentos':this.arrayDocumentos.join(),
                'fechadefuncion':this.fechadefuncion,
                'fechaentrada':this.fechainhumacion,
                'idresponsable':this.idresponsable,
                'nrasignacion':this.nrasignacion,
                'idusuario':1
            }).then(function(response){
                swal('Registro acutalizado','Se han guardado los cambios','success');
            });
            this.verAsignacion(this.regAmbiente);
        },
*/
        storeExhumacion(){
            this.modalAsignar=0;
            this.divAmbientes=1;
            var url='/ser_asignacion/storeExhumacion';
            axios.put(url,{
                'idasignacion':this.regAsignacion.idasignacion,
                'fechasalida':this.fechaexhumacion,
                'idresponsable':this.idresponsable,
                'nrasignacion':this.nrasignacion,
            }).then(function(response){
                swal('Registro correcto','Se han registrado la exhumación. Seleccione el destino del fallecido','success');
                me.verAsignacion(me.regAmbiente);
            });
        },

        listaPagos(idasignacion){
            let me=this;
            var url='/ser_pago/listaPagos?idasignacion='+idasignacion;
            axios.get(url).then(function(response){
                me.arrayPagos=response.data.pagos;
                for(var i=0,t=0;i<me.arrayPagos.length;i++) t+=me.arrayPagos[i].importe;
                me.tpagado=t; 
                me.tsaldo=me.regAmbiente.alquiler-t;
            });
        },

        resetPago(){
            this.periodo='';
            this.nrdocumento='';
            this.importe='';
            this.fechapago='';
            this.idresponsable='';
            this.idperfilcuenta='';
        },

        registrarPago(){
            this.modalPagos=1;
            this.accion=1;
            this.resetPago();
            this.completo=0;
        },

        editarPago(pago){
            this.modalPagos=1;
            this.accion=2;
            this.idpago=pago.idpago;
            this.idresponsable=pago.idresponsable;
            this.concepto=pago.concepto;
            this.fechapago=pago.fecha;
            this.nrdocumento=pago.nrdocumento;
            this.importe=pago.importe;
            this.idperfilcuenta=pago.idperfilcuentamaestro;
            //this.completo=1;
        },

        storePago(){
            this.modalPagos=0;
            let me=this;
            axios.post('/ser_pago/storePago',{
                'idasignacion':this.regAsignacion.idasignacion,
                'concepto':'Alquiler',
                'periodo':this.periodo,
                'nrdocumento':this.nrdocumento,
                'modopago':2,
                'idperfilcuentamaestro':this.idperfilcuenta,
                'importe':this.importe,
                'fecha':this.fechapago,
                'idresponsable':this.idresponsable,
                'glosa':this.glosa,
            }).then(function(response){
                swal('Registro Correcto','Se ha registrado el pago realizado','success');
                me.listaPagos(me.regAsignacion.idasignacion);
                me.resetPago();
            });
            //this.listaPagos(this.regAsignacion.idasignacion)
            //this.verAsignacion(this.regAsignacion.idasignacion,this.regAmbiente);
        },

        updatePago(){
            this.modalPagos=0;
            let me=this;
            axios.put('/ser_pago/updatePago',{
                'idpago':this.idpago,
                'concepto':'Alquiler',
                'periodo':this.periodo,
                'nrdocumento':this.nrdocumento,
                'modopago':2,
                'idperfilcuentamaestro':this.idperfilcuenta,                
                'importe':this.importe,
                'fecha':this.fechapago,
                'idresponsable':this.idresponsable,
                'glosa':this.glosa
            }).then(function(response){
                swal('Registro actualizado','Se ha guardado los cambios en este pago','success');
                me.listaPagos(me.regAsignacion.idasignacion);
            });
        },


        fechalat(fecha){
            //if(!fecha) return;
            return fecha.substr(8,2)+'/'+fecha.substr(5,2)+'/'+fecha.substr(0,4);
        },

    },
}
</script>
