<template>
<main class="main">
    <div class="breadcrumb titmodulo">
        <div class="col-md-8 col-sm-11 titmodulo" style="padding:0px">
            <div class="tablatit">
                <div v-if="divEmpleados==0" class="tcelda" style="padding-right:8px">
                    <button class="btn btn-success cui-arrow-left botonnav" @click="nivelPrevio()"></button>
                </div>
                <div class="tcelda">RRHH
                    <span v-if="divEmpleados"> > Empleados</span>
                    <!-- <span v-else v-text="' > listado'"></span> nombre del empleado? -->
                    <span v-if="divAsistencia"> > Asistencia</span>
                    <span v-if="divPermisos"> > Permisos</span>
                    <span v-if="divContratos"> > Contratos</span>
                    <span v-if="divDocumentos"> > Documentos</span>
                    <span v-if="divReferencias"> > Referencias</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-1 text-right" style="padding:0px">
            <button class="btn btn-success cui-options botonnav"></button>
        </div>
    </div>
 
   <div class="form-group row" >
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <i class="fa fa-align-justify"></i>Credenciales</div>
                                    <div class="card-body"> 
                                        <div class="form-group">
                                            <button     @click="openModale('cen')" class="btn btn-primary">CEN</button>  
                                        </div> 
                                    </div>
                                    <div class="card-body"> 
                                        <div class="form-group">
                                            <button     @click="openReport()" class="btn btn-primary">pdf</button>  
                                        </div> 
                                    </div>
                            </div>
                        </div>
    </div>


    <div class="container-fluid">

        <div class="card" v-if="divEmpleados">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-7 titcard">
                        <div class="tablatit">
                            <div class="tcelda">Empleados <span v-text="sedelp=='1'?'Sede La Paz':'en Filiales'"></span></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group" >
                            <input type="text" class="form-control" v-model="buscado" 
                                @keyup.enter="listaEmpleados()" placeholder="Apellidos, nombre o CI">
                            <span class="input-group-append">
                                <button class="btn btn-primary icon-magnifier" style="margin-top:0px" 
                                @click="listaEmpleados()" title="Buscar nombre o CI"></button>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary btn-block" style="margin-top:0" @click="nuevoEmpleado()">Nuevo Empleado</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="text-right" style="padding-bottom:10px">
                    <div class="vervigente">Filtrar: &nbsp;
                        <input type="radio" id="lp1" name="sede" @change="listaEmpleados()" value="1" v-model="sedelp"> 
                        <label for="lp1">Sede La Paz</label> &nbsp;
                        <input type="radio" id="lp0" name="sede" @change="listaEmpleados()" value="0" v-model="sedelp"> 
                        <label for="lp0">Filiales</label> &nbsp; 
                    </div>
                    <div class="vervigente" style="margin-left:10px">Ver: &nbsp;
                        <input type="radio" id="r1" name="estado" @change="listaEmpleados()" value="1" v-model="activo">
                        <label for="r1">Vigentes</label> &nbsp;
                        <input type="radio" id="r0" name="estado" @change="listaEmpleados()" value="0" v-model="activo">
                        <label for="r0">Inactivos</label> &nbsp;
                    </div>
                    <button class="btn btn-success btn-sm icon-printer" title="Vista de impresión" style="margin-left:10px"
                        @click="reporteLista()"></button>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead class="tcabecera">
                            <tr>
                                <th align="center"><span class="badge badge-success" v-text="arrayEmpleados.length+' items'"></span></th>
                                <th v-if="sedelp=='0'">Filial</th>
                                <th></th>
                                <th>Paterno</th>
                                <th>Materno</th>
                                <th>Nombre</th>
                                <th align="center">CI</th>
                                <th align="center">Celular</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="empleado in arrayEmpleados" :key="empleado.idempleado" :class="empleado.activo?'':'txtdesactivado'">
                                <td v-if="empleado.activo" align="center" nowrap>
                                     <button class="btn btn-warning btn-sm icon-camera" title="Credencial"
                                      @click="generarCarnetEmpl(empleado)" ></button>
                                    <button class="btn btn-warning btn-sm icon-pencil" title="Editar datos"
                                        @click="editarEmpleado(empleado)"></button>
                                    <button class="btn btn-warning btn-sm icon-user"  title="Kardex personal"
                                        @click="reporteKardex(empleado)"></button>
                                        <!--
                                    <button class="btn btn-warning btn-sm icon-credit-card" title="Credencial"
                                        @click="reporteCredencial(empleado)"></button>
                                        -->
                                    <button class="btn btn-warning btn-sm icon-clock" title="Asistencia"
                                        @click="asistenciaEmpleado(empleado)"></button>
                                    <button class="btn btn-warning btn-sm icon-umbrella" title="Permisos"
                                        @click="permisosEmpleado(empleado)"></button>
                                    <button class="btn btn-warning btn-sm icon-briefcase" title="Contratos" 
                                        @click="contratosEmpleado(empleado)"></button>
                                    <button class="btn btn-warning btn-sm icon-docs"  title="Documentos" 
                                        @click="documentosEmpleado(empleado)"></button>
                                    <button class="btn btn-warning btn-sm icon-people" title="Referencias" 
                                        @click="referenciasEmpleado(empleado)"></button>
                                    <button class="btn btn-danger btn-sm icon-trash"  title="Desactivar" 
                                        @click="estadoEmpleado(empleado)"></button>
                                </td>
                                <td v-else align="center">
                                    <button class="btn btn-warning btn-sm icon-user"   title="Kardex personal"></button>
                                    <button class="btn btn-warning btn-sm icon-action-redo" title="Restaurar" 
                                        @click="estadoEmpleado(empleado)"></button>
                                </td>
                                <td v-if="sedelp=='0'" v-text="empleado.filial"></td>
                                <td><img v-if="empleado.foto" :src="'storage/emp/'+empleado.foto"  class="rounded-circle fotosociomini">
                                    <img v-else :src="'storage/emp/avatare.jpg'"  class="rounded-circle fotosociomini" >
                                </td>
                                <td v-text="empleado.apaterno"></td>
                                <td v-text="empleado.amaterno"></td>
                                <td v-text="empleado.nombre"></td>
                                <td v-text="empleado.ci+' '+empleado.abrvdep" align="center"></td>
                                <td v-text="empleado.telcelular" align="center"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <empAsistencia v-if="divAsistencia" :regEmpleado="regEmpleado" :currfecha="currfecha"></empAsistencia>
        <empPermiso    v-if="divPermisos"   :regEmpleado="regEmpleado" :currfecha="currfecha"></empPermiso>
        <empContrato   v-if="divContratos"  :regEmpleado="regEmpleado"></empContrato>
        <empDocumento  v-if="divDocumentos" :regEmpleado="regEmpleado"></empDocumento>
        <empReferencia v-if="divReferencias" :regEmpleado="regEmpleado"></empReferencia>

    </div> 

    <!-- MODAL EMPLEADO  MODAL EMPLEADO MODAL EMPLEADO MODAL EMPLEADO MODAL EMPLEADO  MODAL EMPLEADO -->
    <!-- MODAL EMPLEADO  MODAL EMPLEADO MODAL EMPLEADO MODAL EMPLEADO MODAL EMPLEADO  MODAL EMPLEADO -->
     <div class="modal fade" tabindex="-1"  role="dialog"  style="z-index: 1600;" aria-hidden="true" id="modalnewEmpleado" >   
        <div class="modal-dialog modal-primary modal-lg">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="accion==1?'Nuevo Empleado':'Modificar Datos'"></h4>
                    <button class="close" @click="cerrarModal()">x</button>
                </div>
                <div class="modal-body" style="height:400px; overflow-y:scroll;">
                    <h4 class="titazul">Identidad</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <table width="100%">
                                <tr><td>Nombres: <span class="txtasterisco"></span></td>
                                    <td><input type="text" class="form-control" autofocus v-model="nombre"   
                                        name="nom" :class="{'invalido':errors.has('nom')}" v-validate="'required|alpha_spaces'" v-uppercase>
                                    </td>
                                </tr>
                                <tr><td colspan="2" class="txtvalidador text-right" v-if="errors.has('nom')">Dato requerido</td></tr>
                                <tr><td nowrap>Ap. Paterno: <span class="txtasterisco"></span></td>
                                    <td><input type="text" class="form-control" v-model="apaterno" 
                                        name="pat" :class="{'invalido':errors.has('pat')}" v-validate="'required|alpha_spaces'" v-uppercase>
                                    </td>
                                </tr>
                                <tr><td colspan="2" class="txtvalidador text-right" v-if="errors.has('pat')">Dato requerido</td></tr>
                                <tr><td>Ap. Materno:</td>
                                    <td><input type="text" class="form-control" v-model="amaterno" v-uppercase></td>
                                </tr>
                                <tr><td>Nro CI: <span class="txtasterisco"></span></td>
                                    <td><input type="text" class="form-control" v-model="ci" 
                                        name="nci" :class="{'invalido':errors.has('nci')}" v-validate="'required|numeric'">
                                    </td>
                                </tr>
                                <tr><td colspan="2" class="txtvalidador text-right" v-if="errors.has('nci')">Dato requerido</td></tr>
                                <tr><td>Expedido: <span class="txtasterisco"></span></td>
                                    <td><select class="form-control" v-model="iddepartamento" 
                                        name="exp" :class="{'invalido':errors.has('exp')}" v-validate="'required'">
                                            <option v-for="departamento in arrayDepartamentos" :key="departamento.id"
                                                :value="departamento.iddepartamento" v-text="departamento.nomdepartamento"></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr><td colspan="2" class="txtvalidador text-right" v-if="errors.has('Expedido')">Seleccione un Departamento</td></tr>
                                <tr><td colspan="2">
                                    <div class="tabla100">
                                        <div class="tcelda">Sexo: <span class="txtasterisco"></span></div>
                                        <div class="tcelda text-right">
                                            <input type="radio" name="sex" value="M" v-model="sexo" v-validate="'required'">Masculino&nbsp;
                                            <input type="radio" name="sex" value="F" v-model="sexo" v-validate="'required'">Femenino
                                        </div>
                                    </div>
                                    </td>
                                </tr>
                                <tr><td colspan="2" class="txtvalidador text-right" v-if="errors.has('Sexo')">Dato requerido</td></tr>
                                <tr><td colspan="2" align="center" class="txtobligatorio"></td></tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <table width="100%">
                                <tr><td nowrap>Fecha Nacim: <span class="txtasterisco"></span></td>
                                    <td><input type="date" class="form-control" v-model="fechanacimiento" 
                                        name="nac" :class="{'invalido':errors.has('nac')}" v-validate.initial="'required'">
                                    </td>
                                </tr>
                                <tr><td colspan="2" class="txtvalidador text-right" v-if="errors.has('nac')">Dato requerido</td></tr>
                                <tr><td>Grupo Sang:</td>
                                    <td><select class="form-control" v-model="gruposangre">
                                            <option v-for="i in arraySangre" :key="i" v-text="i"></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr><td>Estado Civil:</td>
                                    <td><select class="form-control" v-model="idestadocivil">
                                            <option v-for="estado in arrayEstados" :key="estado.id"
                                                :value="estado.id" v-text="estado.estado"></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr><td nowrap>Niv. Formación:</td>
                                    <td><select class="form-control" v-model="idformacion">
                                            <option v-for="formacion in arrayFormaciones" :key="formacion.id"
                                                :value="formacion.idformacion" v-text="formacion.nomformacion"></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr><td>Profesión:</td>
                                    <td><select class="form-control" v-model="idprofesion">
                                            <option v-for="profesion in arrayProfesiones" :key="profesion.id"
                                                :value="profesion.idprofesion" v-text="profesion.nomprofesion"></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr><td>Código Generado:</td>
                                    <td><input type="text" class="form-control text-center txtnegrita" readonly v-model="codempleado"></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-4 text-center"> 
                            <!-- <img v-if="nuevafoto" :src="foto" id="lafoto" @load="ajustarImagen"> 
                            <img v-else :src="regEmpleado.foto?'/img/empleados/'+regEmpleado.foto:'/img/empleados/avatar.png'">  -->
                              <!-- <input type="file" style="display:none" accept=".jpg,.jpeg,.JPG,.JPEG" ref="buscar" @change="buscarImagen"> -->
                          
                            

                            <div v-if="nuevafoto==''"> 
                                    <img v-if="foto"  :src="'storage/emp/'+foto">
                                    <img v-else :src="'storage/emp/avatare.jpg'">  
                            </div>
                            <div v-else> 
                                     <div id="imgbene"> 
                                     </div>
                            </div>

                            <div class="fileUpload btn btn-primary">
                                <span>Cargar Foto</span> 
                                <input type="file" id="fileon" ref="fileon" class="upload" accept="image/x-png,image/jpeg" @change="imagenView">
                            </div> 
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="titazul">Contacto</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <table width="100%">
                                        <tr><td nowrap>Celular: <span class="txtasterisco"></span></td>
                                            <td><input type="text" class="form-control" v-model="telcelular" 
                                                name="cel" :class="{'invalido':errors.has('cel')}" v-validate="'required|digits:8'">
                                            </td>
                                        </tr>
                                        <tr><td colspan="2" class="txtvalidador text-right" v-if="errors.has('cel')">Dato requerido, 8 dígitos</td></tr>
                                        <tr><td>Tel Fijo: </td>
                                            <td><input type="text" class="form-control" v-model="telfijo"
                                                name="tel" :class="{'invalido':errors.has('tel')}" v-validate="'digits:7'"></td>
                                        </tr>
                                        <tr><td colspan="2" class="txtvalidador text-right" v-if="errors.has('tel')">(opcional) Sólo 7 dígitos</td></tr>
                                        <tr><td>email:</td>
                                            <td><input type="text" class="form-control" v-model="email"
                                                name="mail" :class="{'invalido':errors.has('mail')}" v-validate="'email'"></td>
                                        </tr>
                                        <tr><td colspan="2" class="txtvalidador text-right" v-if="errors.has('mail')">(opcional) email inválido</td></tr>
                                    </table>
                                    <p class="txtobligatorio"></p>
                                </div>
                                <div class="col-md-6">
                                    <table width="100%">
                                        <tr class="tfila">
                                            <td>Domicilio:<br></td>
                                            <td><textarea class="form-control" style="resize:none" rows="2" v-model="domicilio" ></textarea></td>
                                        </tr>
                                        <tr>
                                            <td class="tcelda">Zona:</td>
                                            <td class="tcelda"><input type="text" class="form-control" v-model="zona"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h4 class="titazul">Seguro Social</h4>
                            <div>
                                <table width="100%">
                                    <tr>
                                        <td>Pensiones:</td>
                                        <td><select class="form-control" v-model="ssocial" >
                                                <template v-for="seguro in arraySeguros" >
                                                    <option v-if="seguro.tipo==1" :key="seguro.id" 
                                                        :value="seguro.idseguro" v-text="seguro.sigla"></option>
                                                </template>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td nowrap>Nro Seguro: </td>
                                        <td><input class="form-control" v-model="codssocial"></td>
                                    </tr>
                                    <tr>
                                        <td>Salud:</td>
                                        <td><select class="form-control" v-model="ssalud">
                                                <template v-for="seguro in arraySeguros" >
                                                    <option v-if="seguro.tipo==2" :key="seguro.id" 
                                                        :value="seguro.idseguro" v-text="seguro.sigla"></option>
                                                </template>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td nowrap>Cód Seguro: </td>
                                        <td><input type="text" class="form-control" v-model="codssalud"></td>
                                    </tr>
                                </table>
                            </div>                            
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="titazul">Institucional</h4>                            
                            <div class="row">
                                <div class="col-md-6">
                                    <table width="100%">
                                        <tr><td>Filial: <span class="txtasterisco"></span></td>
                                            <td><select class="form-control" v-model="idfilial" @change="listaOficinas(idfilial)" 
                                                name="fil" :class="{'invalido':errors.has('fil')}" v-validate="'required'">
                                                    <option v-for="filial in arrayFiliales" :key="filial.id"
                                                        :value="filial.idfilial" v-text="filial.nommunicipio"></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr><td colspan="2" class="txtvalidador text-right" v-if="errors.has('fil')">Seleccione una Filial</td></tr>
                                        <tr><td>Oficina: <span class="txtasterisco"></span></td>
                                            <td><select class="form-control" v-model="idoficina" 
                                                name="ofi" :class="{'invalido':errors.has('ofi')}" v-validate="'required'">
                                                    <option v-for="oficina in arrayOficinas" :key="oficina.id"
                                                        :value="oficina.idoficina" v-text="oficina.nomoficina"></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr><td colspan="2" class="txtvalidador text-right" v-if="errors.has('ofi')">Seleccione una Oficina</td></tr>
                                        <tr><td>Cargo: <span class="txtasterisco"></span></td>
                                            <td><select class="form-control" v-model="idcargo" 
                                                name="car" :class="{'invalido':errors.has('car')}" v-validate="'required'">
                                                    <option v-for="cargo in arrayCargos" :key="cargo.id"
                                                        :value="cargo.idcargo" v-text="cargo.nomcargo"></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr><td colspan="2" class="txtvalidador text-right" v-if="errors.has('car')">Seleccione un cargo</td></tr>
                                        <tr><td nowrap>Ingreso: <span class="txtasterisco"></span></td>
                                            <td><input type="date" class="form-control" v-model="fechaingreso" 
                                                name="ing" :class="{'invalido':errors.has('ing')}"  v-validate.initial="'required'">
                                            </td>
                                        </tr>
                                        <tr><td colspan="2" class="txtvalidador text-right" v-if="errors.has('ing')">Dato requerido</td></tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table width="100%">
                                        <tr><td nowrap>ID Biométrico: <span class="txtasterisco"></span></td>
                                            <td><input type="text" class="form-control" v-model="codbiom" 
                                                name="bio" :class="{'invalido':errors.has('bio')}" v-validate="'required|numeric'"></td>
                                        </tr>
                                        <tr><td colspan="2" class="txtvalidador text-right" v-if="errors.has('bio')">Dato requerido. Hasta 3 dígitos</td></tr>
                                        <tr><td>Banco Sueldo:</td>
                                            <td><select class="form-control" v-model="idbanco">
                                                <option v-for="banco in arrayBancos" :key="banco.id"
                                                    :value="banco.identidadbancaria" v-text="banco.siglaentidadbancaria"></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr><td>Nro Cuenta:</td>
                                            <td><input type="text" class="form-control" v-model="nrcuenta"></td>
                                        </tr>
                                        <tr><td colspan="2" align="center"><span class="txtobligatorio"></span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h4 class="titazul">Retiro</h4>
                            <table width="100%">
                                <tr>
                                    <td>Fecha:</td>
                                    <td><input type="date" class="form-control" v-model="fecharetiro"></td>
                                </tr>
                            </table>
                            Motivo: 
                            <textarea class="form-control" rows="2" style="resize:none" v-model="obs"></textarea>
                        </div>
                    </div>
                    <!-- </form> -->
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    <button class="btn btn-primary" @click="validarEmpleado()">
                        Guardar <span v-if="accion==2">Modificaciones</span></button>
                </div>
            </div>
        </div>
    </div>
   <div class="modal fade" tabindex="-1" role="dialog" style="z-index: 1600;" aria-hidden="true" id="credencial">
      <div class="modal-dialog modal-primary modal-xl" role="document">
        <div class="modal-content animated fadeIn">
          <div class="modal-header">
            <h4 class="modal-title" id="modalOneLabel">Credencial</h4>
            <button type="button" class="close" aria-hidden="true" aria-label="Close"
              @click="classModal.closeModal('credencial')">
              <span aria-hidden="true">×</span>
            </button>
          </div>
     
          <div class="modal-body-plandepagos" style="max-height:600px"> 
              <div class="form-group row" v-if="tipoCred=='cen'">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <i class="fa fa-align-justify"></i>Credenciales CEN</div>
                                    <div class="card-body">
                                        <div class="form-group">
                                                <excelReader v-if="tipoCred=='cen'"  @array_Files_Data="carnetcen"></excelReader>
                                        </div> 
                                    </div>
                            </div>
                        </div>
                </div>
                
             <div class="row" id='contenedorframes'>

             </div>
          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" type="button"
              @click="classModal.closeModal('credencial')">cerrar</button> 
          </div>
        </div>
      </div>
    </div>


    <div class="modal fade" tabindex="-1" role="dialog" style="z-index: 1600;" aria-hidden="true" id="credencialUser">
      <div class="modal-dialog modal-primary modal-xl" role="document">
        <div class="modal-content animated fadeIn">
          <div class="modal-header">
            <h4 class="modal-title" id="modalOneLabel">Credencial</h4>
            <button type="button" class="close" aria-hidden="true" aria-label="Close"
              @click="classModal.closeModal('credencialUser')">
              <span aria-hidden="true">×</span>
            </button>
          </div> 
          <div class="modal-body-plandepagos" style="max-height:600px">  
             <div class="row" id='contenedorframesuser'> 
             </div>
          </div> 
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button"
              @click="classModal.closeModal('credencialUser')">cerrar</button> 
          </div>
        </div>
      </div>
    </div>


</main>
</template>

<script>

Vue.component('empDocumento' ,require('./empDocumento.vue').default);
Vue.component('empContrato'  ,require('./empContrato.vue').default);
Vue.component('empPermiso'   ,require('./empPermiso.vue').default);
Vue.component('empAsistencia',require('./empAsistencia.vue').default);
Vue.component('empReferencia',require('./empReferencia.vue').default);
  
export default {     
    data (){ return { 
        tipoCred:'',
        accion:1,  ipbirt:'', currfecha:'',
        buscado:'', regEmpleado:[], sedelp:'1', activo:'1',
        arrayEmpleados:[], arrayDepartamentos:[], arrayFormaciones:[], arrayProfesiones:[],
        arrayFiliales:[], arrayOficinas:[], arrayCargos:[], arrayBancos:[], arraySeguros:[],
        idempleado:'',   nombre:'', apaterno:'', amaterno:'', sexo:'', ci:'', iddepartamento:'',
        fechanacimiento:'', idestadocivil:'', idformacion:'', idprofesion:'', foto:'', nuevafoto:'',
        telcelular:'', telfijo:'', email:'', domicilio:'', zona:'',
        idfilial:'', idoficina:'', idcargo:'', fechaingreso:'',
        idbanco:'', nrcuenta:'', codbiom:'', fecharetiro:'', obs:'',
        ssocial:'', ssalud:'', codssocial:'',codssalud:'', gruposangre:'',
        arraySangre:['O+','O-','A+','A-','B+','B-','AB+','AB-'],
        arrayEstados:[{id:1,estado:'Solter@'},{id:2,estado:'Casad@'},{id:3,estado:'Divorciad@'}],
        divEmpleados:1, divAsistencia:0, divPermisos:0, divContratos:0, 
        divDocumentos:0, divReferencias:0, 
    }},

directives: { focus }, 
computed: { 
    codempleado: function() { 
      var sum =(this.apaterno?this.apaterno.substr(0,1):'')+
        (this.amaterno?this.amaterno.substr(0,1):'')+
        (this.nombre?_.join(_.map(this.nombre.replace(/[^a-zA-ZÀ-ÿ\u00f1\u00d1 0-9.]+/g,' ').split(" "),(value)=>{
             return value.substr(0,1).toUpperCase();
        }),''):'')+(this.fechanacimiento?moment(this.fechanacimiento).format("YYMD"):''); 
      return sum.toUpperCase();
      }
    },
    methods: { 
      generarCarnetEmpl(empe){ 
          let me=this;
           me.classModal.closeModal('credencialUser');
                    axios.get('/rrh_empleado/verEmpleadopdf?idempleado='+empe.idempleado).then(function (responseuser) { 
                           var regEmpleado=responseuser.data.empleado[0]; 
                            swal({
                                title: "Generando reporte",
                                allowOutsideClick: () => false,
                                allowEscapeKey: () => false,
                                onOpen: function() {
                                swal.showLoading();
                                }
                            });  
                            $("#contenedorframesuser").empty(); 
                                    axios.get('/sociogetfotoCRV_emp?foto='+(regEmpleado.foto==null?'':regEmpleado.foto)).then(function (response) { 
                                            _pl._vvp2521_cr_emp(regEmpleado,response.data,'contenedorframesuser',()=>{
                                                swal.close();
                                                me.classModal.openModal('credencialUser');
                                            });
                                    }).catch(function (error) {
                                        console.log('error2:',error);
                                    });
                    }).catch(function (error) {
                        console.log('error1:',error);
                    });
        },
        openModale(valor){
            this.tipoCred='';
             swal.close();
             let me=this; 
             $('#contenedorframes').empty();
            setTimeout(() => {
              me.tipoCred=valor;
              me.classModal.openModal('credencial');  
            },500);
        },openReport(){
            openReport('html','prueba');
        },
        carnetcen(data){
            this.tipoCred='';
               this.classModal.closeModal('credencial');
             swal({
                title: "Generando reporte",
                allowOutsideClick: () => false,
                allowEscapeKey: () => false,
                onOpen: function() {
                swal.showLoading();
                }
            }); 
             
            let me=this;
            $("#contenedorframes").empty();
            this.generatePDFCen(data.values,'contenedorframes').then(()=>{
                         swal.close();
                        me.classModal.openModal('credencial');
            }).catch((error)=>{
                console.log('error cen:',error);
            });
            
        },
   
       
       async generatePDFCen(array,nam) { 
         
            for (var aux of array) {
                var socio={
                            rutafoto:aux['FOTO'],
                            codsocio:aux['COD_SOCIO'].toString(),
                            carnetmilitar:aux['CM'].toString(), 
                            nomgrado:aux['GRADO'].toUpperCase(),
                            nomespecialidad:aux['ESPECIALIDAD'].toUpperCase(),
                            nombre:aux['NOMBRES'].toUpperCase(),
                            apaterno:aux['APELLIDO  AP'].toString().toUpperCase(),
                            amaterno:aux['APELLIDO MT'].toString().toUpperCase(),
                            cargo:aux['CARGO'].toUpperCase(), 
                            ci:aux['CEDULA'].toString(),
                            abrvdep:aux['EXPEDIDO'].toUpperCase(),  
                            validate:aux['VALIDO']  
                          };
            var consulta=await axios.get('/sociogetfotoCRV_cen?foto='+aux['FOTO']);
             _pl._vvp2521_cr_cen_emp(socio,consulta.data,nam);
            } 
        },
         
        listaEmpleados(){ 
            var url='/rrh_empleado/listaEmpleados?sedelp='+this.sedelp+'&activou='+this.activo+'&buscado='+this.buscado;
            axios.get(url).then(response=>{
                this.arrayEmpleados=response.data.empleados;
                this.ipbirt=response.data.ipbirt;
                this.currfecha=response.data.currfecha; 
            });
        },

        listaDepartamentos(){
            axios.get('/par_departamento/listaDepartamentos').then(response=>{
                this.arrayDepartamentos=response.data.departamentos;
            });
        },

        listaFormaciones(){
            axios.get('/rrh_formacion/listaFormaciones?activo=1').then(response=>{
                this.arrayFormaciones=response.data.formaciones;
            });
        },

        listaProfesiones(){
            axios.get('/rrh_profesion/listaProfesiones?activo=1').then(response=>{
                this.arrayProfesiones=response.data.profesiones;
            });
        },

        listaFiliales(){
            axios.get('/fil_filial/listaFiliales?activo=1').then(response=>{
                this.arrayFiliales=response.data.filiales;
            });
        },

        listaOficinas(idfilial){
            var url='/fil_oficina/listaOficinas?activo=1&idfilial='+idfilial+'&orden=nomoficina';
            axios.get(url).then(response=>{
                this.arrayOficinas=response.data.oficinas;
            });
        },

        listaCargos(){
            axios.get('/rrh_cargo/listaCargos?activo=1').then(response=>{
                this.arrayCargos=response.data.cargos;
            });
        },

        listaSeguros(){
            axios.get('rrh_seguro/listaSeguros?activo=1').then(response=>{
                this.arraySeguros=response.data.seguros;
            });
        },

        listaBancos(){
            var url='/con_entidadbancaria/selectEntidadbancaria?activo=1';
            axios.get(url).then(response=>{
                this.arrayBancos=response.data.bancos;
            });
        },
imagenView(evt){  
                let me=this;
                   if(this.$refs.fileon.files.length==0){  
                              me.nuevafoto='';  
                              $('#imgbene').html('');  
                       return;
                   }else{ 
                       me.nuevafoto='ok';
                $(".fileUpload").css('display', 'none');
                 $('#idimagen').cropper('destroy'); 
                  $('#imgbene').html(''); 
                   var file = this.$refs.fileon.files[0]; 
                    var reader = new FileReader();
                    reader.onload = function(event) { 
                     me.nuevafoto=event.target.result; 
                $(`<img src='${event.target.result}' id="idimagen" style="max-width: 70%;margin: auto;">
                    <div style="padding: 9px;" id="fotoimagenbenecrood">
                    <button  type="button" class="btn btn-success" id="btnselecctbene">Seleccionar</button> 
                    <button  type="button" class="btn btn-danger" id="btncancelbene">Cancelar</button>
                    </div>`).appendTo('#imgbene');

                     $("#idimagen").cropper({
			            aspectRatio: 1/1,
					    viewMode: 1,
					    dragMode: 'move',
						autoCropArea: 1,
						restore: false, 
						guides: false, 
						rotatable: false,
					    multiple: false
					});  
                    $("#btncancelbene").click(function(){
                     $('#idimagen').cropper('destroy'); 
                     $('#imgbene').html('');
                     me.nuevafoto=''; 
                     $('#fileon').val("");  
                      $(".fileUpload").css('display', 'block');
                    }); 

                    $("#btnselecctbene").click(function(){ 
                        me.nuevafoto=$("#idimagen").cropper('getCroppedCanvas',{ width: 300, height: 300 }).toDataURL();   
                        $('#idimagen').cropper('destroy');
                        $("#idimagen").attr("src",me.nuevafoto);
                        $('#fotoimagenbenecrood').html(''); 
                        $(".fileUpload").css('display', 'block');
                    }); 
                    }
                    reader.readAsDataURL(file);
                   }
            },
        buscarImagen(event){
            let archivo=event.target.files[0];
            let lector=new FileReader();
            lector.onload = event => this.foto=event.target.result;
            
            lector.readAsDataURL(archivo);            
        },

        ajustarImagen(){
            var canvas=$('#lafoto').cropper({
                aspectRatio: 1/1,
                viewMode: 3,
                dragMode: 'move',
                autoCropArea: 1,
                restore: false,
                guides: false, 
                rotable: true,
                multiple: false
            });
        },

       

        nuevoEmpleado(){   
             $('#fileon').val("");
            $(".fileUpload").css('display', 'block');
            this.nuevafoto='';         
            this.classModal.openModal('modalnewEmpleado');                        
            this.accion=1;
            this.listaDepartamentos();
            this.listaFiliales();
            this.listaOficinas(1);
            this.listaCargos();
            this.listaFormaciones();
            this.listaProfesiones();
            this.listaBancos();
            this.listaSeguros();
            this.nombre=''; this.apaterno=''; this.amaterno=''; 
            this.sexo=''; this.ci=''; this.iddepartamento='';
            this.fechanacimiento=''; this.idestadocivil=''; 
            this.idformacion=''; this.idprofesion=''; 
            this.telcelular=''; this.telfijo=''; this.email=''; 
            this.domicilio=''; this.zona=''; this.foto='';
            //  this.codempleado='',
            this.idfilial=''; this.idoficina=''; this.idcargo=''; this.fechaingreso='';
            this.nrcontrato=''; this.tipocontrato=''; this.inicontrato=''; this.fincontrato=''; 
            this.sueldo=''; this.idbanco=''; this.nrcuenta=''; this.codbiom='';
            this.$validator.reset();
        },

          editarEmpleado(empleado){
            $('#fileon').val(""); 
          
            this.nuevafoto='';
            this.accion=2;
            this.listaDepartamentos();
            this.listaFiliales();
            this.listaOficinas(1);
            this.listaCargos();
            this.listaFormaciones();
            this.listaProfesiones();
            this.listaBancos();
            this.listaSeguros();
            this.idempleado=empleado.idempleado;
           axios.get('/rrh_empleado/verEmpleado?idempleado='+this.idempleado).then(response=>{
                this.regEmpleado=response.data.empleado[0];
            //this.codempleado=this.regEmpleado.codempleado;
            this.nombre=this.regEmpleado.nombre;
            this.apaterno=this.regEmpleado.apaterno;
            this.amaterno=this.regEmpleado.amaterno;
            this.ci=this.regEmpleado.ci;
            this.iddepartamento=this.regEmpleado.iddepartamento;
            this.sexo=this.regEmpleado.sexo;
            this.gruposangre=this.regEmpleado.gruposangre;
            this.idestadocivil=this.regEmpleado.idestadocivil;
            this.fechanacimiento=this.regEmpleado.fechanacimiento;
            this.foto=this.regEmpleado.foto;
            this.idformacion=this.regEmpleado.idformacion;
            this.idprofesion=this.regEmpleado.idprofesion;
            this.telcelular=this.regEmpleado.telcelular;
            this.telfijo=this.regEmpleado.telfijo;
            this.email=this.regEmpleado.email;
            this.domicilio=this.regEmpleado.domicilio;
            this.zona=this.regEmpleado.zona;
            this.idfilial=this.regEmpleado.idfilial;
            this.idoficina=this.regEmpleado.idoficina;
            this.idcargo=this.regEmpleado.idcargo;
            this.fechaingreso=this.regEmpleado.fechaingreso;
            this.idbanco=this.regEmpleado.idbanco;
            this.nrcuenta=this.regEmpleado.nrcuenta;
            this.codbiom=this.regEmpleado.codbiom;
            this.ssocial=this.regEmpleado.ssocial;
            this.codssocial=this.regEmpleado.codssocial;
            this.ssalud=this.regEmpleado.ssalud;
            this.codssalud=this.regEmpleado.codssalud;
             $(".fileUpload").css('display', 'block');
            this.classModal.openModal('modalnewEmpleado'); 
            });
           
        },

        nivelPrevio(){
            this.divEmpleados=1;
            this.divAsistencia=0;
            this.divPermisos=0;
            this.divContratos=0;
            this.divDocumentos=0;
            this.divReferencias=0;
        },

        asistenciaEmpleado(empleado){
            this.regEmpleado=empleado;
            this.divEmpleados=0;
            this.divAsistencia=1;
            this.divPermisos=0;
            this.divContratos=0;
            this.divDocumentos=0;
            this.divReferencias=0;
        },

        permisosEmpleado(empleado){
            this.regEmpleado=empleado;
            this.divEmpleados=0;
            this.divAsistencia=0;
            this.divPermisos=1;
            this.divContratos=0;
            this.divDocumentos=0;
            this.divReferencias=0;
        },

        contratosEmpleado(empleado){
            this.regEmpleado=empleado;
            this.divEmpleados=0;
            this.divAsistencia=0;
            this.divPermisos=0;
            this.divContratos=1;
            this.divDocumentos=0;
            this.divReferencias=0;
        },

        documentosEmpleado(empleado){
            this.regEmpleado=empleado;
            this.divEmpleados=0;
            this.divAsistencia=0;
            this.divPermisos=0;
            this.divContratos=0;
            this.divDocumentos=1;
            this.divReferencias=0;
        },

        referenciasEmpleado(empleado){
            this.regEmpleado=empleado;
            this.divEmpleados=0;
            this.divAsistencia=0;
            this.divPermisos=0;
            this.divContratos=0;
            this.divDocumentos=0;
            this.divReferencias=1;
        },

        validarEmpleado(){
            this.$validator.validateAll().then(result=>{
                this.cerrarModal();
                if(!result){  
                     swal('Datos incorrectos','Revise los errores','error').then(()=>{
                         this.classModal.openModal('modalnewEmpleado'); 
                     });
                     return;
                 }
                this.accion==1?this.storeEmpleado():this.updateEmpleado();
            });
        },

        storeEmpleado(){
            swal({ title:'Procesando...',text:'Un momento por favor', type:'warning',
                showCancelButton:false, showConfirmButton:false, 
                allowOutsideClick: false, allowEscapeKey: false, allowEnterKey: false,
                onOpen:() => { swal.showLoading() }
            });             
            axios.post('/rrh_empleado/storeEmpleado',{
                'codempleado':this.codempleado,
                'nombre':this.nombre.toUpperCase(),
                'apaterno':this.apaterno.toUpperCase(),
                'amaterno':this.amaterno.toUpperCase(),
                'ci':this.ci,
                'iddepartamento':this.iddepartamento,
                'sexo':this.sexo,
                'gruposangre':this.gruposangre,
                'fechanacimiento':this.fechanacimiento,
                'idestadocivil':this.idestadocivil,
                'foto':this.nuevafoto,
                'idformacion':this.idformacion,
                'idprofesion':this.idprofesion,
                'telcelular':this.telcelular,
                'telfijo':this.telfijo,
                'email':this.email,
                'domicilio':this.domicilio,
                'zona':this.zona,
                'idfilial':this.idfilial,
                'idoficina':this.idoficina,
                'idcargo':this.idcargo,
                'fechaingreso':this.fechaingreso,
                'idbanco':this.idbanco,
                'nrcuenta':this.nrcuenta,
                'codbiom':this.codbiom,
                'ssocial':this.ssocial,
                'codssocial':this.codssocial,
                'ssalud':this.ssalud,
                'codssalud':this.codssalud,
            }).then(response=>{
                swal('Registro creado correctamente','','success');
                this.buscado=this.apaterno; 
                this.listaEmpleados();
            });
        },

        updateEmpleado(){
            swal({ title:'Procesando...',text:'Un momento por favor', type:'warning',
                showCancelButton:false, showConfirmButton:false, 
                allowOutsideClick: false, allowEscapeKey: false, allowEnterKey: false,
                onOpen:() => { swal.showLoading() }
            }); 
            axios.put('/rrh_empleado/updateEmpleado',{
                'codempleado':this.codempleado,
                'idempleado':this.idempleado,
                'nombre':this.nombre.toUpperCase(),
                'apaterno':this.apaterno.toUpperCase(),
                'amaterno':this.amaterno?this.amaterno.toUpperCase():'',
                'ci':this.ci,
                'iddepartamento':this.iddepartamento,
                'sexo':this.sexo,
                'gruposangre':this.gruposangre,
                'fechanacimiento':this.fechanacimiento,
                'idestadocivil':this.idestadocivil,
                'foto':this.nuevafoto,
                'idformacion':this.idformacion,
                'idprofesion':this.idprofesion,
                'telcelular':this.telcelular,
                'telfijo':this.telfijo,
                'email':this.email,
                'domicilio':this.domicilio,
                'zona':this.zona,
                'idfilial':this.idfilial,
                'idoficina':this.idoficina,
                'idcargo':this.idcargo,
                'fechaingreso':this.fechaingreso,
                'idbanco':this.idbanco,
                'nrcuenta':this.nrcuenta,
                'codbiom':this.codbiom,
                'ssocial':this.ssocial,
                'codssocial':this.codssocial,
                'ssalud':this.ssalud,
                'codssalud':this.codssalud,
                'fecharetiro':this.fecharetiro,
                'obs':this.obs,
            }).then(response=>{
                swal('Datos Actualizados','','success'); 
                this.listaEmpleados();
            });
        },
        cerrarModal(){
            this.classModal.closeModal('modalnewEmpleado');
        },
        estadoEmpleado(empleado){
            this.idempleado=empleado.idempleado;
            if(empleado.activo){
                swal({  title:'Desactivará el registro de<br>'+empleado.nombre+' '+empleado.apaterno, type: 'warning', 
                    html: 'No podrá acceder a la información dependiente', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Empleado',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(confirmar=>{ confirmar.value?this.switchEmpleado(0):''});
            }
            else this.switchEmpleado(1);
        },

        switchEmpleado(activo){
            var url='/rrh_empleado/switchEmpleado?idempleado='+this.idempleado;
            axios.put(url).then(function(){
                swal(activo?'Activado correctamente':'Desactivado correctamente','','success');
                this.listaEmpleados();
            });
        },

        reporteLista(){ 
             _pl._vm2154_12186_135(this.ipbirt['REP_LISTA']+'&sedelp='+this.sedelp+'&activo='+this.activo,'Kardex Personal');
        },

        reporteKardex(empleado){  
            _pl._vm2154_12186_135(this.ipbirt['REP_KARDEX']+'&idempleado='+empleado.idempleado,'Kardex Personal');
        },       

        reporteCredencial(empleado){ 
            _pl._vm2154_12186_135(this.ipbirt['REP_CREDENCIAL']+'&idempleado='+empleado.idempleado,'Credencial');
        }
    },
        
    mounted() {
        moment.locale('es-us');
        this.listaEmpleados();
        this.classModal=new _pl.Modals();
        this.classModal.addModal('modalnewEmpleado'); 
        this.classModal.addModal("credencial"); 
        this.classModal.addModal("credencialUser"); 
    }
}
</script>
<style>    
   .fileUpload {
    position: relative;
    overflow: hidden;
    margin: 10px;
    display: block;
}
.fileUpload input.upload {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
    padding: 0;
    font-size: 20px;
    cursor: pointer;
    opacity: 0;
    filter: alpha(opacity=0);
}

</style>