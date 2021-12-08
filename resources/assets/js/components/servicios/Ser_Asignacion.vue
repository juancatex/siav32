<template>
    <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
    </ol>
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <div class="card-header">
                <div class="form-group row " style="margin-bottom: 0px;">
                    <div class="col-md-2">
                            <div class="form-group row" style="margin-bottom: 0px;padding-left: 30px;">
                            <i class="fa fa-align-justify"></i>&nbsp; Asignacion &nbsp;
                           <!--  <button v-if="check('libro_agregar')" type="button" @click="abrirModalCompras('registrar')" class="btn btn-secondary" :disabled='!ismescerrado || filialselected==0'>
                                <i class="icon-plus"></i>&nbsp;Nuevo
                            </button> -->
                            <!-- &nbsp;&nbsp;&nbsp;<span class="text-error" v-if="filialselected==0">Debe seleccionar una filial para agregar mas facturas</span> -->
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group row" style="margin-bottom: 0px;">
                            <div class="col-md-3">
                                <strong class="form-control-label">Filial:</strong>
                            </div>
                            <div class="col-md-8">
                                <select v-model="filialselected"  class="form-control" @change="listarEstablecimiento()" disabled>
                                    <option value="0" disabled selected>Todas</option>
                                    <option v-for="filial in arrayFilial" v-bind:key="filial.idfilial" :value="filial.idfilial" v-text="filial.nommunicipio"></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                            <div class="form-group row" style="margin-bottom: 0px;">
                            <div class="col-md-3">
                                <strong class="form-control-label">Servicio:</strong>
                            </div>
                            <div class="col-md-8">
                                <select v-model="establecimientoselected"  class="form-control" @change="listarPisos()" disabled>
                                    <option value="0" disabled selected >Seleccionar...</option>
                                    <option v-for="establecimientos in arrayestablecimientos" v-bind:key="establecimientos.idestablecimiento" :value="establecimientos.idestablecimiento" v-text="establecimientos.nomestablecimiento"></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" v-if="respuesta">
                            <div class="form-group row" style="margin-bottom: 0px;">
                            <div class="col-md-3">
                                <strong class="form-control-label">Piso:</strong>
                            </div>
                            <div class="col-md-8">
                                <select v-model="pisoselected"  class="form-control" @change="listarAsignaciones()">
                                    <option v-for="p in arraypisos" v-bind:key="p.pisos" :value="p.pisos" v-text="p.pisos"></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" v-model="buscar" @keyup.enter="listarAsignaciones('buscar')" class="form-control" placeholder="Texto a buscar">
                            <button type="submit" @click="listarAsignaciones('buscar')" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                    <div class="col-md-8" style="text-align:right;">
                        <button type="submit" @click="listarAsignaciones()" class="btn btn-success"><i class="fas fa-sync-alt" title="Actualizar"></i> Actualizar</button>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Cod.</th>
                            <th>Descripcion</th>
                            <th>Tarifas</th>
                            <th>Ocupantes - Fecha Entrada - Noches / Pago</th>
                            <th>Opciones</th>

                        </tr>
                    </thead>
                    <tbody>
                         
                        <tr v-for="asignacion in arrayAsignacion" :key="asignacion.idambiente">
                            <td class="card-header titcard" v-text="asignacion.codambiente"></td>
                            <td>{{ asignacion.tipo }} - {{ asignacion.capacidad }} <span v-if="asignacion.capacidad>1">Camas</span> <span v-else>Cama</span><br /> 
                                <span v-if="asignacion.tipo=='COMPARTIDO'" :class="asignacion.camasrestantes==0?'badge badge-danger':'badge badge-info'"> {{ asignacion.camasrestantes }} libre(s)</span>
                                <span v-else-if="asignacion.camasrestantes==0" class="badge badge-danger">Lleno</span>  
                                <span v-else class="badge badge-success">Libre</span>  
                            </td>
                            <td>{{ asignacion.tarifasocio }} Bs. Socio <br /> {{ asignacion.tarifareal }} Bs. Particular</td>
                            <td>
                            <table v-if="asignacion.hospedaje.length>0" style="width:100%" >
                                <tr v-for="hospedaje in asignacion.hospedaje" :key="hospedaje.idasignacion" >
                                    <td style="width:45%">{{ hospedaje.nombres}}</td>
                                    <td style="width:20%">{{ hospedaje.fechaentrada }} - {{ hospedaje.horaentrada }}</td>
                                    <td style="width:20%">Noches: {{ hospedaje.difd }} <br />Monto: <span v-if="hospedaje.descuento!=0">{{ hospedaje.montosindescuento }} Bs.</span>  <span v-else>{{hospedaje.monto}} Bs.</span></td>
                                    <td style="width:15%">
                                        
                                            <button class="btn btn-warning btn-sm btn-block" title="Imprimir Ingreso"
                                                @click="imprimirasignacion(hospedaje)"> <i class="fas fa-print"></i> Ingreso</button> 
                                            <button class="btn btn-danger btn-sm btn-block" title="Salida"
                                                @click="modalTraspaso(asignacion,hospedaje)"><i class="fas fa-random"></i> Traspaso</button> 
                                            <button class="btn btn-primary btn-sm btn-block" title="Salida"
                                                @click="modalregistrarSalida(asignacion,hospedaje)"><i class="fas fa-door-open"></i> Salida</button> 
                                            <!-- <button class="btn btn-danger btn-sm btn-block" title="Traspaso"
                                                @click="traspaso(asignacion)"> <i class="fas fa-random"></i> Traspaso</button>  -->
                                            
                                            
                                            <span v-if="hospedaje.descuento==1" class="badge badge-warning">Descuento Solicitado</span>
                                            <span v-if="hospedaje.descuento==2" class="badge badge-success">Descuento Aprobado</span>
                                    </td>
                                </tr> 
                            </table>
                            </td>
                            <td>
                                 <button v-if="asignacion.camasrestantes>0" type="button" @click="abrirModalAsignacion('asignar',asignacion)" class="btn btn-success icon-user-follow" data-toggle="tooltip" data-placement="top" title="Asignar Habitacion">
                                </button> &nbsp;
                                
                            </td>
                        </tr>                                
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!-- MODAL ASIGNACION   -->
    <div class="modal fade " tabindex="-1"  role="dialog"   aria-hidden="true" id="addasignacion"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-primary modal-lg">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal.toUpperCase()"></h4>
                    <button class="close" @click="cerrarModalAsignacion()">x</button>
                </div>
                <div class="modal-body" style="overflow-y:scroll; height:450px;">
                    <h4 class="titsubrayado" v-text="nomestablecimiento"></h4>
                    <div class="row">
                        <div class="col-md-9">
                            <strong class="titcampo">Habitacion: &nbsp;&nbsp;</strong> <span>{{habitacion}} </span> {{capacidad}} Camas - &nbsp; 
                            <strong v-if="tipo=='COMPARTIDO'">Camas a Ocupar: &nbsp;&nbsp; {{camaselected}} </strong>
                            <strong v-if="tipo=='COMPARTIDO'">Camas Libres: {{camaslibres}}</strong>
                        </div>
                        <div class="col-md-3 text-right">
                            <strong class="titcampo">Tarifa:</strong>
                            <strong v-if="tipocliente!=2 " v-text="tarifasocio"> </strong>
                            <strong v-else v-text="tarifareal"> </strong>
                            Bs/noche
                        </div>
                    </div>
                    <div class="form-group">
                    <div class="col-12 col-form-label " style="border: 1px solid #c2cfd6 !important; border-radius: 5px;">
                        <div class="col-3 form-check-inline">
                            
                                <input type="radio" class="form-check-input" v-model="tipocliente" value="1" checked @change="cambiaDirectivo('1')" id="socio"><label for="socio">Socio</label> 
                           
                        </div>
                        <div class="col-3 form-check-inline">
                            
                                <input type="radio" class="form-check-input" v-model="tipocliente" value="3" @change="cambiaDirectivo('3')" id="familiar"><label for="familiar">Familiar</label> 
                            
                        </div>
                        <div class="col-3 form-check-inline">

                                <input type="radio" class="form-check-input" v-model="tipocliente" value="2" @change="cambiaDirectivo('2')" id="particular"> <label for="particular">Particular</label>
                            
                        </div>
                    </div>
                    </div>

<!-- 
                    <div class="tcelda tinput text-right nowrap">
                        <input type="checkbox" v-model="tipocliente" @change="verParticular()" id="tipocliente"><label for="tipocliente">Cliente particular</label>  
                    </div>
 -->                    

                    <div v-if="tipocliente==1">
                         <Ajaxselect  v-if="clearSelected"
                            ruta="/rrh_empleado/selectsocios?buscar=" @found="empleados" @cleaning="cleanempleados"
                            resp_ruta="empleados"
                            labels="nombres"
                            placeholder="Ingrese Texto..." 
                            idtabla="idsocio"
                            :id="idempleadoselected"
                            :clearable='true'>
                        </Ajaxselect>
                    </div>
                    <div v-else-if="tipocliente==3">
                         <Ajaxselect  v-if="clearSelected"
                            ruta="/rrh_empleado/selectsocios?buscar=" @found="empleados" @cleaning="cleanempleados"
                            resp_ruta="empleados"
                            labels="nombres"
                            placeholder="Ingrese Texto..." 
                            idtabla="idsocio"
                            :id="idempleadoselected"
                            :clearable='true'>
                        </Ajaxselect>
                        
                        <table v-if="sifamiliar" style="width:100%" class="table table-bordered table-striped table-sm">
                            <tr>
                                <th>Nombre</th>
                                <th>Parentezco</th>
                                <th>Seleccionar</th>
                            </tr>
                            <tr v-for="familiar in arrayFamiliares" :key="familiar.idcivil">
                                <td>{{ familiar.nombres}}</td>
                                <td>{{ familiar.parentezco}}</td>
                                <td>
                                    <input type="checkbox" class="form-check-input" @change="verrestantes()" v-model="checkfamiliar" :value="familiar.idcivil" :disabled="camaslibres==0">
                                </td>
                            </tr>
                        </table>
                        <div style="text-align:right;" class="mt-1">
                            <button :disabled="idempleado.length==0" type="button" class="btn btn-primary" @click="abrirModalFamiliar()" aria-label="Close" title="Agregar Familiar">
                            <i class="icon-plus"></i>&nbsp;&nbsp;&nbsp;Agregar Familiar
                        </button>
                        </div>
                        

                    </div>
                    <div v-else-if="tipocliente==2">
                         <Ajaxselect  v-if="clearSelected"
                            ruta="/ser_civils/selectcivil?buscar=" @found="empleados" @cleaning="cleanempleados"
                            resp_ruta="civils"
                            labels="nomcivil"
                            placeholder="Ingrese Texto..." 
                            idtabla="idcivil"
                            :id="idempleadoselected"
                            :clearable='true'>
                        </Ajaxselect>
                        <div style="text-align:right;" class="mt-1">
                            <button type="button" class="btn btn-primary" @click="abrirModalCivil()" aria-label="Close" title="Agregar Familiar">
                                <i class="icon-plus"></i>&nbsp;&nbsp;&nbsp;Agregar Particular
                            </button>
                        </div>
                    </div>
                    <hr>
                    <!-- <h4 v-if="regAsignacion.idcliente" class="titsubrayado" style="margin:15px 0px;">
                        <span v-text="regCliente.nomgrado"></span> <span v-text="regCliente.nombre"></span>
                        <span v-text="regCliente.apaterno"></span> <span v-text="regCliente.amaterno"></span>
                    </h4> -->
                    <div class="row">
                        <div class="col-md-4">
                            <h5 class="titazul" v-text="tituloModal"></h5>
                            <h4>Fecha:  {{ fechaingreso }} <br />
                                Hora: {{horaingreso }}
                            </h4>
                            <!-- <template v-if="tipo=='COMPARTIDO'">
                                Camas Disponibles: <select class="form-control" v-model="camaselected">
                                                        <option value="0" selected="selected" disabled>Seleccionar...</option>
                                                        <option v-for="k in camas" :key="k" v-text="k"></option>
                                                    </select>     
                            </template>   -->                          
                        </div>
                        <div class="col-md-8"> 
                            <h4 class="titazul">Prendas</h4>
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <span style="display:none" >{{mitad=Math.floor(arrayImplementos.length/2)}}</span>
                                    <ul style="list-style:none; padding-left:10px">
                                        <li v-for="(implemento,index) in arrayImplementos" :key="implemento.idimplemento" >
                                            <template v-if="index<=mitad">
                                                <input type="checkbox" :value="implemento.idimplemento" 
                                                    v-model="arrayIDimplementos">
                                                <span style="text-transform:capitalize" v-text="implemento.nomimplemento"></span>
                                            </template>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <ul style="list-style:none; padding-left:10px">
                                        <li v-for="(implemento,index) in arrayImplementos" :key="implemento.idimplemento" >
                                            <template v-if="index>mitad">
                                                <input type="checkbox" :value="implemento.idimplemento" v-model="arrayIDimplementos">
                                                <span style="text-transform:capitalize" v-text="implemento.nomimplemento"></span>
                                            </template>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>                        
                        <div class="col-md-12">
                            Observaciones: <input type="text" class="form-control" v-model="obs">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="cerrarModalAsignacion()">Cerrar</button>
                    <button v-if="check('Registro_entrada')" class="btn btn-primary" :disabled="(idempleado.length==0 && idempleadoselected=='') || !siasignacion" @click="accion==1?storeAsignacion():updateAsignacion()">
                        Guardar <span v-text="tituloModal"></span></button>
                </div>
            </div>
        </div>
    </div>
    <!-- fin modal agregar asignacion -->
    <!-- MODAL PARTICULAR  -->
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="regparticular"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-primary modal-lg">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title">Registro Huesped Particular</h4>
                    <button class="close" @click="cerrarmodalparticular()">x</button>
                </div>
                <div class="modal-body">
                    <div v-if="tipocliente==3" class="form-row">
                        <div class="form-group col-md-12">
                            <h4>Registro Familiares del Socio: {{ idempleado[2]}}</h4>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="parentezco" class="col-sm-4 col-form-label"><strong>Parentezco</strong></label>
                             <select name="parentezco" id="" required class="form-control" v-model="parentezco">
                                <option value="0" selected="selected" disabled>Seleccionar...</option>
                                <option value="Esposa(o)">Esposa(o)</option>
                                <option value="Hijo(a)">Hijo(a)</option>
                            </select>
                            <span v-if="parentezco=='0'" class="text-error">El Valor Parentezo es obligatorio</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="apaterno" ><strong>Apellido Paterno:</strong></label>
                            <input type="text" class="form-control" id="apaterno" placeholder="Apellido Paterno" v-model="apaterno">
                            <span v-if="apaterno==''" class="text-error">El Valor Apellido Paterno es obligatorio</span>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="amaterno" ><strong>Apellido Materno:</strong></label>
                            <input type="text" class="form-control" id="amaterno" placeholder="Apellido Materno" v-model="amaterno">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="Nombres" ><strong>Nombres:</strong></label>
                            <input type="text" class="form-control" id="Nombres" placeholder="Nombres" v-model="nombre">
                            <span v-if="nombre==''" class="text-error">El Valor Nombres es obligatorio</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="numcelular" ><strong>Numero Celular:</strong></label>
                            <input type="text" class="form-control" id="numcelular" placeholder="Numero Celular" v-model="celular">
                            <span v-if="celular==''" class="text-error">El Valor Num. Celular es obligatorio</span>
                        
                        </div>
                        <div class="form-group col-md-3">
                            <label for="ci" ><strong>CI:</strong></label>
                            <input type="text" class="form-control" id="ci" placeholder="CI" v-model="ci">
                            <span v-if="ci==''" class="text-error">El Valor CI es obligatorio</span>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="Dep" ><strong>Dep:</strong></label>
                            <select class="form-control" v-model="iddepartamento" v-validate.initial="'required'" name="dep">
                                    <option v-for="departamento in arrayDepartamentos" :key="departamento.iddepartamento" :value="departamento.iddepartamento" v-text="departamento.abrvdep"></option>
                            </select>
                            <span class="text-error">{{ errors.first('dep')}}</span>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="fechanac" ><strong>Fecha Nacimiento:</strong></label>
                            <input type="date" class="form-control" id="fechanac" v-model="fechanacimiento">
                            <span v-if="fechanacimiento==''" class="text-error">El Valor Fecha Nacimiento es obligatorio</span>
                        </div>
                    </div>
                    <fieldset class="form-group col-md-8">
                    <div class="row">
                    <legend class="col-form-label pt-0"><strong>Sexo:</strong></legend>
                        <div class="form-check col-md-8">
                            <input class="form-check-input" type="radio" checked v-model="sexo" value="M" id="masculino">
                            <label class="form-check-label" for="masculino">
                                Masculino
                        </label>
                        </div>
                        <div class="form-check col-md-8">
                        <input class="form-check-input" type="radio" v-model="sexo" value="F" id="femenino">
                        <label class="form-check-label" for="femenino">
                            Femenino
                        </label>
                        </div>
                    </div>
                </fieldset>
                    


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="cerrarmodalparticular()">Cerrar</button>
                <button :disabled="!iscompleteparticular" class="btn btn-primary" @click="registrarParticular()">Guardar</button>
            </div>
            </div>
        </div>
    </div>
    <!-- fin modal add particular -->


     <!-- MODAL SALIDA  -->
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="salida"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-primary modal-lg">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title">Registro Salida</h4> 
                    <button class="close" @click="cerrarmodalsalida()">x</button>
                </div>
                <div class="modal-body" style="font-size: 18px;">
                    <div class="col">
                        <div class="form-group row ">
                            <h4>Datos Ambiente</h4><br />
                            <div class="col-md-12">
                                <strong class="form-control-label">Establecimiento: </strong>
                                <label for="">{{ nomestablecimiento }}</label>
                            
                                <strong class="form-control-label">Ambiente: </strong>
                                <label for="">{{ codambiente }} &nbsp;</label>
                            
                                <strong class="form-control-label">piso: </strong>
                                <label for="">{{ piso }}</label>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <h4>Huesped</h4> <br />
                            <div class="col-md-12">
                                <strong class="form-control-label">Nombre: </strong>
                                    <label for="">{{ nombre }} </label>
                                <strong class="form-control-label">CI: </strong>
                                <label for="">{{ ci }} </label>
                            </div>
                            <h4>Datos Asignacion:</h4>
                            <div class="col-md-12">
                                <strong class="form-control-label">Fecha de entrada: </strong>
                                    <label for="">{{ fechaingreso }} - {{ horaingreso}} </label>
                            </div>

                        </div>
                        <hr>
                        <div class="form-row">
                            <div class="form-groupt col-md-12" v-if="descuento!=0">
                                <h4>Descuento Solicitado</h4>
                            </div>
                            <div class="form-group col-md-6">
                               
                                <div v-if="descuento!=0">
                                    <strong class="form-control-label">Monto Original Sin Descuento: </strong>
                                    <label for="">{{ montodescuento }} Bs. </label>
                                    <hr>

                                </div>

                                <strong  v-if="descuento==2" class="form-control-label">Monto a Cobrar con Descuento Aprobado: </strong>
                                <strong  v-else class="form-control-label">Monto a Cobrar: </strong>
                                <label for="">{{ montoacobrar }} <span v-if="descuento!=1"> Bs. </span>  </label>
                                <hr>
                                <strong class="form-control-label">Noches hospedado: </strong>
                                <label for="">{{ diashospedados }} </label>
                                <hr>
                                <strong class="form-control-label">Fecha de Salida: </strong>
                                <label for="">{{ fechaactual }} - {{ horaactual}} </label>
                            </div>
                            <div class="form-group col-md-6">
                                <strong class="form-control-label">Implementos: </strong>
                                <ul>
                                    <li v-for="(implementos,index) in arrayrespuesta" :key="index">
                                        {{ implementos }}
                                    </li>
                                </ul>
                            </div>
                            
                        </div>

                            
                       <hr>
                        <div class="form-row" v-if="descuento==2 && montoacobrar==0">
                            <strong for="Sin Factura">Sin Factura Monto 0 Bs.</strong>
                             
                        </div>
                        <div v-else class="form-row">
                            <div class="form-group col-md-8">
                                <strong for="razonsocial">Numero Factura:</strong>
                                <input class="col-md-4 form control" type="text"  id="numfactura"  v-model="numfactura" disabled>&nbsp;&nbsp;&nbsp;&nbsp;
                                <button class="btn btn-danger" @click="anularFactura()">Anular Factura</button>
                            </div>
                            <div class="form-group col-md-5">
                            <strong for="razonsocial">Razon Social</strong>
                            <input type="text" class="form-control" id="razonsocial" placeholder="Razon Social" v-model="razonsocial">
                            </div>
                            <div class="form-group col-md-5">
                            <strong for="nit">Nit - CI</strong>
                            <input type="text" class="form-control" id="nit" placeholder="Nit - CI" v-model="nit">
                            </div>

                            
                        </div>
                        <div class="form-group"> 
                            <strong for="observaciones">Observaciones</strong>
                            <input type="text" class="form-control" id="observaciones" placeholder="Observaciones" v-model="observaciones">
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between" >
                    <div>
                        <button v-if="check('Registro_salida_descuento')&& descuento==0 && tipocliente==1" type="button" class="btn btn-warning" @click="solicitarDescuento()">Solicitar Descuento</button>
                    </div>
                    <div>
                        <button type="button" class="btn btn-secondary" @click="cerrarmodalsalida()">Cerrar</button>
                        <button v-if="check('Registro_salida')" :disabled="!iscompletesalida" class="btn btn-primary" @click="registrarSalida()">Guardar</button>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- fin modal add particular -->

    <!-- MODAL TRASPASO -->
    <div class="modal fade " tabindex="-1"  role="dialog"  aria-hidden="true" id="traspaso" >
        <div class="modal-dialog modal-primary modal-lg">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title">Traspaso de Habitacion</h4>
                    <button class="close" @click="cerrarmodaltraspaso()">x</button>
                </div>
                <div class="modal-body">          
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Asignacion</th>
                                    <th>Nombre</th>
                                    <th>Ambiente</th>
                                    <th>Piso</th>
                                    <th>Traspaso a:</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>                                                
                                    <td v-text="idasignacion"></td>
                                    <td v-text="nombre"></td>
                                    <td align="center" v-text="codambiente"></td>
                                    <td align="center" v-text="piso"></td>
                                    <td><select class="form-control" name="idtraspaso" id="" v-model="traspasoselected">
                                            <option value="0" selected="selected" disabled>Seleccionar...</option>
                                            <option v-for="traspaso in arrayTraspaso" :key="traspaso.idambiente" :value="traspaso.idambiente" v-text="traspaso.codambiente + ' - Libres: '+ traspaso.camasrestantes"></option>
                                        </select>
                                    </td>
                                </tr>                                
                            </tbody>
                        </table>                
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="cerrarmodaltraspaso()">Cerrar</button>
                    <button :disabled="traspasoselected==0" class="btn btn-primary" @click="confirmaTraspaso()"> Registrar Traspaso </button>
                </div>
            </div>
        </div>
    </div>
    <!-- FINAL MODAL TRASPASO -->







    
</main>

</template>

<script>

    import Vue from 'vue';
    import VeeValidate from 'vee-validate';
    import * as plugin from '../../functions.js';

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
                    Registro_entrada: 0,
                    Registro_salida: 0,
                    Registro_salida_descuento: 0
                },
                arrayPermisosIn: [],
                arrayFilial:[],
                arrayServicios:[],
                filialselected:1,
                establecimientoselected:0,
                arrayAsignacion:[],
                pisoselected:0,
                respuesta:false,
                arrayestablecimientos:[],
                arraypisos:[],
                pisos:0,
                tituloModal : '',
                habitacion:'',
                tarifasocio:0,
                tarifareal:0,
                tipocliente:1,   //1->socio, 2->particular
                arrayImplementos:[],
                camas:1,
                observaciones:'',
                nomestablecimiento:'',
                idempleadoselected:'',
                idempleado:[],
                clearSelected:1,
                fechaingreso:'',
                horaingreso:'',
                fechaactual:'',
                horaactual:'',
                arrayIDimplementos:[],
                camaselected:0,
                arrayDepartamentos:[],
                iddepartamento:2,
                idcivil:0,
                idambiente:'',
                obs:'',
                tipo:'',
                piso:'',
                montoacobrar:0,
                diashospedados:0,
                idimplementos:[],
                arrayrespuesta:[],
                codambiente:'',
                razonsocial:'',
                nit:'',
                idasignacion:'',
                porcentajeit:0,
                porcentajelv:0,
                porcendebito:0,
                numautorizacion:'',
                numfactura:'',
                idfactura:'',
                arrayLibroCuentas:'',
                resLibroventas:'',
                lv:'',
                it:'',
                itxp:'',
                porcentajeitxp:'',
                arrayFamiliares:[],
                parentezco:0,
                camasrestantes:0,
                camaslibres:0,
                capacidad:0,  
                checkfamiliar:[],  
                descuento:0,
                montodescuento:'',
                idsocio:'',
                arrayTraspaso:[],
                traspasoselected:0,
                

                
                //*****************modal civil
                apaterno:'',
                amaterno:'',
                nombre:'',
                ci:'',
                celular:'',
                fechanacimiento:'',
                sexo:'M',
                accion:1,


                
                


                
                


                departamento_id: 0,
                nomdepartamento : '',
                abrvdep : '',
                arrayDepartamento : [],
                modal : 0,
                
                tipoAccion : 0,
                errorDepartamento : 0,
                errorMostrarMsjDepartamento : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nomdepartamento',
                buscar : ''
            }
        },

        formOptions:{
            validateAfterLoad: true,
            validateAfterChanged: true
        },

        computed:{
            sifamiliar(){
                let me=this;
                let valor=false;
                if(me.tipocliente==3)
                {
                    if( me.idempleado.length>0)
                    {
                        me.listarfamiliares()
                        valor=true;
                    }
                    else
                        valor=false;
                    
                }
                else
                    valor=false;
                return valor;
                

            },

            iscompletesalida(){
                let me=this;
                if(me.razonsocial!='' && me.nit!='' && me.montoacobrar!='Solicitud en Curso')
                    return true;
                else
                    return false;

            },
            siasignacion(){
                let me =this;
                if(me.camaselected==0)
                    return false;
                else
                    return true;
            },
            iscompleteparticular(){
                let me =this;
                if(me.apaterno!='' && me.nombre!='' && me.ci!='' && me.celular!='' && me.fechanacimiento!='')
                    return true;
                else
                    return false;
            },
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
            modalTraspaso(datosgenerales=[],datoshabitacion=[]){
                let me=this;
                let nuevoarray=[];
                 me.idasignacion=datoshabitacion['idasignacion'];
                    me.nombre=datoshabitacion['nombres'];
                    me.fechaingreso=datoshabitacion['fechaentrada']; 
                    me.codambiente=datosgenerales['codambiente'];
                    me.piso=datosgenerales['piso'];
                    
                var url= '/ser_asignacion?idestablecimiento='+me.establecimientoselected+'&nopiso='+true;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    nuevoarray = respuesta.asignacion;
                    me.arrayTraspaso = nuevoarray.filter( function(obj) {
                    if( (obj.tipo == 'COMPARTIDO' && obj.camasrestantes >0) || (obj.tipo!='COMPARTIDO' && obj.camasocupadas==0)){
                        return obj;
                    }
                   
                    
                    me.classModal.openModal('traspaso');

                });
                //console.log(me.nuevoarray);

                })
                .catch(function (response) {
                    console.log(response);
                });
               

            },
            cerrarmodaltraspaso(){
                let me=this;
                me.idasignacion='';
                me.nombre='';
                me.codambiente='';
                me.classModal.closeModal('traspaso');
            },
            confirmaTraspaso(){
                swal({
                title: 'Esta seguro de Realizar el Traspaso?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;
                    axios.put('/ser_asignacion/traspaso',{
                    'idasignacion':me.idasignacion,
                    'idambiente': me.traspasoselected,
                    //'subcuenta':me.idempleado[0]

                    }).then(function (response) {

                        me.listarAsignaciones();
                        me.cerrarmodaltraspaso();
                        swal('Traspaso Realizado con Exito');
                    }).catch(function (error) {
                            console.log(error);
                    }); 


                } 
                else if (result.dismiss === swal.DismissReason.cancel) 
                {
                    
                }
                });


            },
            anularFactura(){
                let me=this;
                swal({
                title: 'Esta seguro de Anular la Factura? Nº '+ me.numfactura,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;
                    axios.post('/con_factura/registrar',{
                    'subcuenta':'',
                    'numerofactura': me.numfactura,
                    'codigocontrol': '',
                    'razonsocial': ' ',                    
                    'nit': '',
                    'detalle': '',
                    'importetotal': 0,
                    'importecf': 0,
                    'debfiscal':0,
                    'restoimporte':0,
                    'it':0,
                    'numautorizacion':me.numautorizacion,
                    'fechafactura':me.fechaactual,
                    'activo':2
                    
                    //'subcuenta':me.idempleado[0]

                }).then(function (response) {
                    me.maxfactura();
                    swal('Factura Anulada');
                }).catch(function (error) {
                        console.log(error);
                }); 


                } 
                else if (result.dismiss === swal.DismissReason.cancel) 
                {
                    
                }
                });
                 

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
            solicitarDescuento(idasignacion){
                let me=this;
                swal({
                title: 'Esta seguro de Soliciar Descuento para esta Asignacion?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;
                    axios.put('/ser_asignacion/solicituddescuento',{
                        'idasignacion':me.idasignacion,
                        'monto':me.montoacobrar
                    }).then(function (response) {
                            swal(
                                'La Solicitud Fue Enviada',
                                'Debe esperar Confirmacion',
                            )
                            me.cerrarmodalsalida();
                            me.listarAsignaciones();
                        
                    }).catch(function (error) {
                        console.log(error);
                    }); 
                   } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 


            },

            verrestantes(){
                let me=this;
                me.camaselected=me.checkfamiliar.length
                me.camaslibres=Number(me.camasrestantes-me.camaselected);

            },

            listarfamiliares(){
                let me=this;
                let url='/ser_civils/listarfamiliar?idsocio='+me.idempleado[0];
                axios.get(url).then(response=>{
                    this.arrayFamiliares=response.data.familiares;
                })
            },


            abrirModalFamiliar(){
                let me=this;
                me.listarfamiliares();
                me.classModal.openModal('regparticular');
                
            },
            imprimirasignacion(data=[]){
                console.log(data);
                let me=this;
                me.tipocliente=data['tipocliente']
                me.idasignacion=data['idasignacion'];
                var url='/reporteingreso?idasignacion='+ me.idasignacion +'&tiposocio='+me.tipocliente;
                /* window.open(url, '_blank'); */
                _pl._vm2154_12186_135(url,'Reporte de Ingreso')
                me.tipocliente='';
                me.idasignacion='';
            },
           

            //metodo agregado para la validacion
            validateBeforeSubmit() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                    //alert('enviado');
                    //return this.result;
                    return;
                    }
                    
                    //alert('no enviado');
                    return;
                    ////alert(result);
                    
                    
                });
            },
            cerrarModalAsignacion(){
                let me= this;
                me.camas=1;
                me.idempleado=[];
                me.idempleadoselected='';
                me.camaselected=0;
                me.classModal.closeModal('addasignacion');
                me.accion=1;
            },
            ////////////////////////////////////
            listarPisos(){
                let me=this;
                axios.get('ser_ambientes/listarpisos?idestablecimiento='+me.establecimientoselected).then(response=>{
                    this.arraypisos=response.data.pisos;
                    //console.log(me.arraypisos);
                    me.pisoselected=me.arraypisos[0].pisos
                    if(me.pisoselected==0)
                        me.respuesta=false;
                    else
                        me.respuesta=true; 
                    me.respuesta=true; 
                        me.respuesta=true; 
                    me.listarAsignaciones();

                });
                
            },
            
            listarEstablecimiento(){
                let me=this;
                axios.get('/ser_establecimiento/listaEstablecimientos2?idfilial='+this.filialselected).then(response=>{
                    this.arrayestablecimientos=response.data.establecimientos;
                });

                me.serviciosselected=0;

            },
            selectFilial(){
                let me=this;
                var url='/fil_filial/selectFiliales';
                axios.get(url).then(function(response){
                    me.arrayFilial=response.data.filiales;
                    //console.log(me.arrayFilial);
                    
                });
            },
            listarAsignaciones(valor){
                let me=this;
                if(valor=='buscar')
                {

                }
                else
                {
                    var url= '/ser_asignacion?idestablecimiento='+me.establecimientoselected+'&piso='+me.pisoselected;
                    axios.get(url).then(function (response) {
                        var respuesta= response.data;
                        //console.log(respuesta);
                        me.arrayAsignacion = respuesta.asignacion;
                        console.log(me.arrayAsignacion);
                    })
                    .catch(function (response) {
                        console.log(response);
                    });
                }
               
                
            },

                /////////////////////////////////
          
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarDepartamento(page,buscar,criterio);
            },
           
            actualizarDepartamento(){
               /*if (this.validarDepartamento()){
                    return;
                }*/
                
                let me = this;

                axios.put('/par_departamento/actualizar',{
                    'nomdepartamento': this.nomdepartamento, 
                    'iddepartamento': this.departamento_id,
                    'abrvdep': this.abrvdep
                }).then(function (response) {
                    if(response.data.length){
                        swal(
                            'El Valor ya Existe',
                            'Ingresa un dato Diferente',
                            'error'
                        )
                    }
                    else{
                        me.cerrarModal();
                        me.listarDepartamento(1,'','nomdepartamento');
                    }
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            desactivarDepartamento(iddepartamento){
               swal({
                title: 'Esta seguro de desactivar este Departamento?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/par_departamento/desactivar',{
                        'iddepartamento': iddepartamento
                    }).then(function (response) {
                        me.listarDepartamento(1,'','nomdepartamento');
                        swal(
                        'Desactivado!',
                        'El registro ha sido desactivado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
            activarDepartamento(iddepartamento){
               swal({
                title: 'Esta seguro de activar este Departamento?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/par_departamento/activar',{
                        'iddepartamento': iddepartamento
                    }).then(function (response) {
                        me.listarDepartamento(1,'','nomdepartamento');
                        swal(
                        'Activado!',
                        'El registro ha sido activado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },

            /*    //////////////   comentar esta funcion ya no se usa
            validarDepartamento(){
                this.errorDepartamento=0;
                this.errorMostrarMsjDepartamento =[];

                if (!this.nomdepartamento) this.errorMostrarMsjDepartamento.push("El nombre del Departamento no puede estar vacío.");

                if (this.errorMostrarMsjDepartamento.length) this.errorDepartamento = 1;

                return this.errorDepartamento;
            },*/
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nomdepartamento='';
                
            },
            registrarTraspaso(){

            },
            cerrarmodalsalida(){
                let me=this;
                me.arrayrespuesta=[];
                me.nomestablecimiento='';
                me.codambiente='';
                me.piso='';
                me.nombre='';
                me.ci='';
                me.fechaingreso='';
                me.horaingreso='';
                me.montoacobrar='';
                me.diashospedados='';
                me.idimplementos='';
                me.razonsocial='';
                me.numfactura='';
                me.observaciones='';
                me.idasignacion='';

                me.classModal.closeModal('salida');
            },
           
            cambiaDirectivo(valor){
                let me=this;
                me.clearSelected=0;
                setTimeout(me.tiempo, 200); 
                me.tipocliente=valor;
                me.idempleado=[];
                me.idempleadoselected='';
                if(me.tipocliente==3)
                        me.camaselected=0;
                    else
                        me.camaselected=1;
                me.camaslibres=Number(me.camasrestantes-me.camaselected);
                me.checkfamiliar=[];
                        
            },


            departamentos() {
                let me=this;
                var url='/par_departamento/selectDepartamento';
                axios.get(url).then(function(response){
                    me.arrayDepartamentos=response.data.departamentos;
                })
            },
            cerrarmodalparticular(){
                let me=this;
                me.classModal.closeModal('regparticular');
                me.nombre='';
                me.apaterno='';
                me.amaterno='';
                me.ci='';
                me.fechanacimiento='';
                me.iddepartamento=2;
                me.celular='';
                me.classModal.openModal('addasignacion');

            },
            abrirModalCivil(){
                let me=this;
                me.classModal.closeModal('addasignacion');
                me.classModal.openModal('regparticular');

            },
            registrarParticular(){
                let me = this;
                axios.post('/ser_civil/store',{
                    'nombre': this.nombre,
                    'apaterno': this.apaterno,
                    'amaterno': this.amaterno,
                    'ci':this.ci,
                    'iddepartamento':this.iddepartamento,
                    'fechanac':this.fechanacimiento,
                    'sexo':this.sexo,
                    'telcelular':this.celular,
                    'idsocio':this.idempleado[0],
                    'parentezco':this.parentezco


                }).then(function (response) {
                    if(response.data.length>6){
                       //console.log(response)
                       swal(
                            'El Valor ya Existe',
                            'Ingresa un dato Diferente',
                            'error'
                       )                    }
                    else{
                        if(me.tipocliente==3)
                            me.listarfamiliares();
                        me.cerrarmodalparticular();
                        
                        me.clearSelected=0;
                        setTimeout(me.tiempo, 100); 
                        if(me.tipocliente==2)
                        me.idempleadoselected=response.data;
                        else
                        me.idempleadoselected=me.idempleado[0];

                        //me.abrirModalAsignacion('asignar',);
                        
                        //console.log(me.idcivil);

                    }

                    
                }).catch(function (error) {
                    console.log(error);
                });
            },
            fechahoy(valor){
                let me=this;
                axios.get('/ser_asignacion/fechahora').then(response=>{
                    me.fechaactual=response.data.fechaactual;
                    me.horaactual=response.data.horaactual;
                    
                    if(valor==1)
                    {
                        me.fechaingreso=me.fechaactual;
                        me.horaingreso=me.horaactual
                    }
                });

               

            },
            tiempo(){
                this.clearSelected=1;
                //this.borrador=1;
            },
            cleanempleados(){
                this.idempleado=[];
            },
            empleados(empleados){
                this.idempleado=[];
                for (const key in empleados) {
                    if (empleados.hasOwnProperty(key)) {
                        const element = empleados[key];
                        this.idempleado.push(element);
                    }
                }
            },

            listaImplementos(){
                var url='ser_implemento/listaImplementos?activo=1';
                axios.get(url).then(response=>{
                    this.arrayImplementos=response.data.implementos;
                    //console.log(arrayImplementos);
                })
            },
            abrirModalAsignacion(accion,data=[]){
                let me=this;
                this.fechahoy(1);
                /* console.log(accion);
                console.log(data); */
                switch(accion){
                    case "asignar":
                    {
                        me.accion=1
                        me.arrayIDimplementos=[1,2,3,4,5,6,7,8,9];
                        me.classModal.openModal('addasignacion');
                        
                        me.clearSelected=0;
                        setTimeout(me.tiempo, 100); 

                        
                        me.habitacion=data['codambiente']+' '+data['tipo'];
                        me.tipo=data['tipo'];
                        me.tituloModal = 'Registro de Entrada';
                        me.tarifareal= data['tarifareal'];
                        me.tarifasocio=data['tarifasocio'];
                        me.tipocliente=1;
                        me.fechaentrada=me.fechaactual;
                        me.horaentrada=me.horaactual;
                        me.camas=data['capacidad'];
                        me.camasrestantes=data['camasrestantes'];
                        me.observaciones='';
                        me.idambiente=data['idambiente'];
                        me.nomestablecimiento=data['nomestablecimiento'];
                        me.camaselected=1;
                        me.camaslibres=Number(me.camasrestantes-me.camaselected);
                        me.capacidad=data['capacidad'];
                        
                       
                        break;
                    }
                    case 'actualizar':
                    {
                        //console.log(data);
                        me.modal=1;
                        me.tituloModal='Actualizar Departamento';
                        me.tipoAccion=2;
                        me.departamento_id=data['iddepartamento'];
                        me.nomdepartamento = data['nomdepartamento'];
                        me.abrvdep = data['abrvdep'];
                        
                        break;
                    }
                }
            },
            maxfactura (){
                let me=this;
                var url= '/con_factura/maxfactura';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.numfactura = respuesta['maxfactura']+1;     //console.log(me.maxfacturavalor);    
                    me.numautorizacion=respuesta.numautorizacion.numautorizacion;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        selectLibroCuenta(){
            let me=this;
            let respuesta;
            var url= '/con_config/cuentaslibros';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayLibroCuentas = respuesta;
                me.resLibroventas=me.arrayLibroCuentas.libroventas;
                //console.log(me.resLibroventas);
                respuesta=me.resLibroventas.find(element=>element.codigo=='LV');
                me.lv=respuesta.valor;
                me.porcentajelv=respuesta.valor2;
                me.porcentajelv=Number((me.porcentajelv/100).toFixed(2));
                

                respuesta=me.resLibroventas.find(element=>element.codigo=='IT');
                me.it=respuesta.valor;
                me.porcentajeit=respuesta.valor2;
                me.porcentajeit=Number((me.porcentajeit/100).toFixed(2));
                
                respuesta=me.resLibroventas.find(element=>element.codigo=='ITXP');
                me.itxp=respuesta.valor;
                me.porcentajeitxp=respuesta.valor2;
                //console.log(me.lv,me.porcentajelv,me.it,me.porcentajeit,me.itxp,me.porcentajeitxp);
            })
            .catch(function (error) {
                console.log(error);
            });
        },
            registrarFactura(){ 
                let me = this;
                //var _13porciento=Number((total*0.13).toFixed(2));
                let porcendebito=0;
                let porcenit=0;
                let restodebito=0;
                porcendebito =Number((me.montoacobrar * me.porcentajelv).toFixed(2));
                //console.log(porcendebito);
                restodebito=Number((me.montoacobrar-porcendebito).toFixed(2));
                //console.log(restodebito);
                porcenit=Number((me.montoacobrar * me.porcentajeit).toFixed(2));
                swal({  title:'Procesando...',
                        text:'Un momento por favor', 
                        type:'warning',
                        showCancelButton:false, 
                        showConfirmButton:false, 
                        allowOutsideClick: false, 
                        allowEscapeKey: false, 
                        allowEnterKey: false,
                        onOpen:() => { swal.showLoading() }
                 }); 
                if(me.montoacobrar==0 && me.descuento==2)
                {
                                  
                     axios.put('/ser_asignacion/registrarsalida',{
                    'idasignacion': me.idasignacion, 
                    'fechasalida':me.fechaactual,
                    'horasalida':me.horaactual,
                    //'nit':me.numfactura,
                    //'razonsocial':me.razonsocial,
                    'monto':me.montoacobrar,
                    'observaciones':me.observaciones,
                    'idfactura':me.idfactura,
                    'cantdias':me.diashospedados,
                    }).then(function (response) {
                        if(response.data.length){
                            swal(
                                'El Valor ya Existe',
                                'Ingresa un dato Diferente',
                                'error'
                            )
                        }
                        else{
                             swal(
                                'Registrado Correctamente',
                            )

                            let url='/reportesalida?idasignacion='+ me.idasignacion +'&tiposocio='+me.tipocliente;
                            /* window.open(url, '_blank'); */
                            _pl._vm2154_12186_135(url,'Reporte de Salida');
                            me.cerrarmodalsalida();
                            
                            me.listarAsignaciones();
                        }
                    }).catch(function (error) {
                        console.log(error);
                    }); 
            }
            else
            {
                 let socio_id=null;
                 if(me.tipocliente==1 && me.descuento==2)
                    socio_id=me.idsocio;

                 console.log(socio_id);
                 axios.post('/con_factura/registrar',{
                    'subcuenta':socio_id,
                    'numerofactura': me.numfactura,
                    'codigocontrol': '',
                    'razonsocial': me.razonsocial,                    
                    'nit': me.nit,
                    'detalle': 'Hospedaje',
                    'importetotal': me.montoacobrar,
                    'importecf': me.montoacobrar,
                    'debfiscal':porcendebito,
                    'restoimporte':restodebito,
                    'it':porcenit,
                    'numautorizacion':me.numautorizacion,
                    'fechafactura':me.fechaactual,
                    
                    //'subcuenta':me.idempleado[0]

                }).then(function (response) {
                    let me2=this;   
                    me.idfactura=response.data;
                    axios.put('/ser_asignacion/registrarsalida',{
                    'idasignacion': me.idasignacion, 
                    'fechasalida':me.fechaactual,
                    'horasalida':me.horaactual,
                    //'nit':me.numfactura,
                    //'razonsocial':me.razonsocial,
                    'monto':me.montoacobrar,
                    'observaciones':me.observaciones,
                    'idfactura':me.idfactura,
                    'cantdias':me.diashospedados,
                    }).then(function (response) {
                        if(response.data.length){
                            swal(
                                'El Valor ya Existe',
                                'Ingresa un dato Diferente',
                                'error'
                            )
                        }
                        else{
                             swal(
                                'Registrado Correctamente',
                            )

                            let url='/reportesalida?idasignacion='+ me.idasignacion +'&tiposocio='+me.tipocliente;
                            /* window.open(url, '_blank'); */
                            _pl._vm2154_12186_135(url,'Reporte de Salida');
                            me.cerrarmodalsalida();
                            
                            me.listarAsignaciones();
                        }
                    }).catch(function (error) {
                        console.log(error);
                    }); 



                }).catch(function (error) {
                    console.log(error);
                });
            }
            },
            registrarSalida(){
                let me = this;
                swal({
                title: 'Esta seguro de Registrar la Salida?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;
                    me.registrarFactura(); 
                } 
                else if (result.dismiss === swal.DismissReason.cancel) 
                {
                    
                }
                });
                
            },

            modalregistrarSalida(datosgenerales=[],datoshabitacion=[]){
                let me=this;
                let idimpl;
                me.maxfactura();
                me.fechahoy(0);
                me.arrayrespuesta=[];
                me.idasignacion=datoshabitacion['idasignacion'];
                me.nomestablecimiento=datosgenerales['nomestablecimiento'];
                me.codambiente=datosgenerales['codambiente'];
                me.piso=datosgenerales['piso'];
                me.nombre=datoshabitacion['nombres'];
                me.ci=datoshabitacion['ci'];
                me.nit=me.ci;
                me.fechaingreso=datoshabitacion['fechaentrada'];
                me.horaingreso=datoshabitacion['horaentrada'];
                me.montoacobrar=datoshabitacion['monto'];
                me.diashospedados=datoshabitacion['difd'];
                idimpl=datoshabitacion['idimplementos'];
                me.idimplementos=idimpl.split('|');
                me.razonsocial=datoshabitacion['apaterno'];
                me.montodescuento=datoshabitacion['montosindescuento'];
                me.descuento=datoshabitacion['descuento'];
                me.tipocliente=datoshabitacion['tipocliente'];
                me.idsocio=datoshabitacion['idcliente'];
                if(me.descuento==1)
                    me.montoacobrar='Solicitud en Curso';
                if(me.descuento==2)
                    me.montoacobrar=datoshabitacion['montocondescuento']


               for (let indice = 0; indice < me.idimplementos.length; indice++) {
                   const element = me.idimplementos[indice];

                   me.arrayrespuesta.push(me.arrayImplementos[element-1].nomimplemento);
               }
                me.classModal.openModal('salida');
            },
            storeAsignacion(){
                let me=this;
                if(me.idempleadoselected=='')
                    me.idempleadoselected=me.idempleado[0];
                
                

                swal({  title:'Procesando...',
                        text:'Un momento por favor', 
                        type:'warning',
                        showCancelButton:false, 
                        showConfirmButton:false, 
                        allowOutsideClick: false, 
                        allowEscapeKey: false, 
                        allowEnterKey: false,
                        onOpen:() => { swal.showLoading() }
                });             
                axios.post('/ser_asignacion/storeAsignacion',{
                    'idcliente':me.idempleadoselected,
                    'tipocliente':me.tipocliente,
                    'idambiente':me.idambiente,
                    'nrasignacion':'',
                    'fechasolicitud':'',
                    'mesboleta':'',
                    'ocupantes':me.camaselected,     // para el caso de transitorio se registra el numero de camas que ocupa el socio
                    'iddocumentos':'',
                    'idimplementos':me.arrayIDimplementos.join("|"),
                    'fechaentrada':me.fechaingreso,
                    'horaentrada':me.horaingreso,
                    'fechasalida':'',
                    'horasalida':'',
                    'fechadefuncion':'',
                    'idresponsable':'',
                    'idrepresentante':'',
                    'obs1':me.obs,
                    'familiar':me.checkfamiliar,
                }).then(response=>{
                    let idasignacion=response.data;
                    swal('Asignación creada','Proceda a la verificación de pagos','success');
                    var url='/reporteingreso?idasignacion='+ idasignacion +'&tiposocio='+me.tipocliente;
                    _pl._vm2154_12186_135(url,'Reporte de Ingreso');
                    // window.open(url, '_blank');
                    me.listarAsignaciones();
                    //this.modalAsignacion=0;
                    this.cerrarModalAsignacion();
                     var url='/reporteingreso?idasignacion='+ idasignacion +'&tiposocio='+me.tipocliente;
                    

                    
                });
            },
        },
        mounted() {
            //this.listarDepartamento(1,this.buscar,this.criterio);
            this.getPermisos();
            this.classModal=new _pl.Modals();
            this.selectFilial();
            this.establecimientoselected=2;
            this.listarEstablecimiento();
            this.listarPisos();
            
            this.listaImplementos();
            this.classModal.addModal('addasignacion');
            this.classModal.addModal('regparticular');
            this.classModal.addModal('salida');
            this.classModal.addModal('addfamiliar');
            this.classModal.addModal('traspaso');
            this.selectLibroCuenta();
            this.departamentos();
        }
    }
</script>

<style lang="css" scoped>
.titmodulo {
    font-size:16px;
    font-weight:500;
}

.titservicio{
    font-size:28px;
    font-weight: 500;
    color:#20a8d8;
    margin-bottom: 0px;
    margin-top: 0px;
}
.titazul{
    color:#3c8dbc;
    font-size:18px;
    font-weight:600;
    border-bottom: #3c8dbc solid 1px;
}
hr{
    margin-top: 0.5rem;
    margin-bottom: 0.5rem ;
    border-top: 1px solid #3c8dbc;
}
/*
    
label{
    font-size: 18px;

}
strong{
    font-size: 18px;
} */

    
</style>
