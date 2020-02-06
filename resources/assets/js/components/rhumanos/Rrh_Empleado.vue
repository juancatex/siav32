<template>
<main class="main">
    <div class="breadcrumb titmodulo">RRHH > Empleados</div>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-7 titcard">
                        <div class="tablatit">
                            <div class="tcelda">Empleados <span v-text="activo?'Vigentes':'Inactivos'"></span></div>
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
            <div class="card-body table-responsive">
                <table class="table table-striped table-sm">
                    <thead class="tcabecera">
                        <tr>
                            <th>
                                <!--
                                <label class="switch switch-3d switch-primary">
                                    <input type="checkbox" class="switch-input" v-model="activo" checked @change="listaEmpleados()">
                                    <span class="switch-slider"></span>
                                </label>
                                -->
                                <span class="badge badge-success" v-text="arrayEmpleados.length+' items'"></span>
                            </th>
                            <th></th>
                            <th>Paterno</th>
                            <th>Materno</th>
                            <th>Nombre</th>
                            <th>CI</th>
                            <th>Celular</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="empleado in arrayEmpleados" :key="empleado.idempleado" :class="empleado.activo?'':'txtdesactivado'">
                            <td v-if="empleado.activo" align="center" nowrap>
                                <button class="btn btn-warning btn-sm icon-user"  title="Kardex personal"
                                    @click="kardexEmpleado(empleado)"></button>
                                <button class="btn btn-warning btn-sm icon-pencil" title="Editar datos"
                                    @click="editarEmpleado(empleado)"></button>
                                <button class="btn btn-warning btn-sm icon-clock" title="Asistencia"
                                    @click="$refs.empAsistencia.abrirModal(empleado)"></button>
                                <button class="btn btn-warning btn-sm icon-umbrella" title="Permisos"
                                    @click="$refs.empPermiso.abrirModal(empleado)"></button>
                                <button class="btn btn-warning btn-sm icon-briefcase" title="Contratos" 
                                    @click="$refs.empContrato.abrirModal(empleado)"></button>
                                <button class="btn btn-warning btn-sm icon-docs"  title="Documentos" 
                                    @click="$refs.empDocumento.abrirModal(empleado)"></button>
                                <button class="btn btn-warning btn-sm icon-people" title="Referencias" 
                                    @click="$refs.empReferencia.abrirModal(empleado)"></button>
                                <button class="btn btn-danger btn-sm icon-trash"  title="Desactivar" 
                                    @click="estadoEmpleado(empleado)"></button>
                            </td>
                            <td v-else align="center">
                                <button class="btn btn-warning btn-sm icon-user"   title="Kardex personal"></button>
                                <button class="btn btn-warning btn-sm icon-action-redo" title="Restaurar" 
                                    @click="estadoEmpleado(empleado)"></button>
                            </td>
                            <td><img v-if="empleado.foto" :src="'img/empleados/'+empleado.foto"  class="rounded-circle fotosociomini">
                                <img v-else :src="'img/empleados/avatar.png'"  class="rounded-circle fotosociomini" >
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

    <!-- MODAL EMPLEADO  MODAL EMPLEADO MODAL EMPLEADO MODAL EMPLEADO MODAL EMPLEADO  MODAL EMPLEADO -->
    <!-- MODAL EMPLEADO  MODAL EMPLEADO MODAL EMPLEADO MODAL EMPLEADO MODAL EMPLEADO  MODAL EMPLEADO -->
    <div class="modal" :class="modalEmpleado?'mostrar':''">
        <div class="modal-dialog modal-primary modal-lg">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="accion==1?'Nuevo Empleado':'Modificar Datos'"></h4>
                    <button class="close" @click="modalEmpleado=0">x</button>
                </div>
                <div class="modal-body" style="height:400px; overflow-y:scroll">
                    <!-- <form @submit.prevent="validarAntes()"> -->
                    <h4 class="titazul">Identidad</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <table width="100%">
                                <tr><td>Nombres: <span class="txtasterisco"></span></td>
                                    <td><input type="text" class="form-control" autofocus v-model="nombre" name="Nombres" v-validate.initial="'required|alpha_spaces'"
                                        @keyup.esc="modalEmpleado=0">
                                    </td>
                                </tr>
                                <tr><td colspan="2" class="text-right txtvalerror" v-text="errors.first('Nombres')"></td></tr>
                                <tr><td nowrap>Ap. Paterno: <span class="txtasterisco"></span></td>
                                    <td><input type="text" class="form-control" v-model="apaterno" 
                                        name="Ap Paterno" v-validate.initial="'required|alpha'">
                                    </td>
                                </tr>
                                <tr><td colspan="2" class="text-right txtvalerror" v-text="errors.first('Ap Paterno')"></td></tr>
                                <tr><td>Ap. Materno:</td>
                                    <td><input type="text" class="form-control" v-model="amaterno"></td>
                                </tr>
                                <tr><td >Nro CI: <span class="txtasterisco"></span></td>
                                    <td><input type="text" class="form-control" v-model="ci" 
                                        name="CI" v-validate.initial="'required|numeric'">
                                    </td>
                                </tr>
                                <tr><td colspan="2" class="txtvalerror text-right" v-text="errors.first('CI')"></td></tr>
                                <tr><td>Expedido: <span class="txtasterisco"></span></td>
                                    <td><select class="form-control" v-model="iddepartamento" name="Expedido" v-validate.initial="'required'">
                                            <option v-for="departamento in arrayDepartamentos" :key="departamento.id"
                                                :value="departamento.iddepartamento" v-text="departamento.nomdepartamento"></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr><td colspan="2" class="txtvalerror text-right" v-text="errors.first('Expedido')"></td></tr>
                                <tr><td colspan="2">
                                    <div class="tabla100">
                                        <div class="tcelda">Sexo: <span class="txtasterisco"></span></div>
                                        <div class="tcelda text-right">
                                            <input type="radio" name="Sexo" value="M" v-model="sexo" v-validate.initial="'required'">Masculino&nbsp;
                                            <input type="radio" name="Sexo" value="F" v-model="sexo" v-validate.initial="'required'">Femenino
                                        </div>
                                    </div>
                                    </td>
                                </tr>
                                <tr><td colspan="2" class="txtvalerror text-right" v-text="errors.first('Sexo')"></td></tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <table width="100%">
                                <tr><td nowrap>Fecha Nacim: <span class="txtasterisco"></span></td>
                                    <td><input type="date" class="form-control" v-model="fechanacimiento" 
                                        name="Fecha Nacim" v-validate.initial="'required'">
                                    </td>
                                </tr>
                                <tr><td colspan="2" class="txtvalerror text-right" v-text="errors.first('Fecha Nacim')"></td></tr>
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
                                <tr><td colspan="2" align="center"><span class="txtvalidador">* Datos Obligatorios</span></td></tr>
                            </table>
                        </div>
                        <div class="col-md-4 text-center"> 
                            <img v-if="nuevafoto" :src="foto" id="lafoto" @load="ajustarImagen"> 
                            <img v-else :src="regEmpleado.foto?'/img/empleados/'+regEmpleado.foto:'/img/empleados/avatar.png'"> 
                            <input type="file" style="display:none" accept=".jpg,.jpeg,.JPG,.JPEG" ref="buscar" @change="buscarImagen">
                            <button class="btn btn-primary" @click="$refs.buscar.click()">Cargar Foto</button>
                            
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
                                                name="Celular" v-validate.initial="'required|numeric'">
                                            </td>
                                        </tr>
                                        <tr><td colspan="2" class="txtvalerror text-right" v-text="errors.first('Celular')"></td></tr>
                                        <tr><td>Tel Fijo: </td>
                                            <td><input type="text" class="form-control" v-model="telfijo"></td>
                                        </tr>
                                        <tr><td>email:</td>
                                            <td><input type="text" class="form-control" v-model="email"></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table width="100%">
                                        <tr class="tfila">
                                            <td>Domicilio:<br><span class="txtasterisco"></span></td>
                                            <td><textarea class="form-control" style="resize:none" rows="2" v-model="domicilio" 
                                                name="Domicilio" v-validate.initial="'required'"></textarea>
                                            </td>
                                        </tr>
                                        <tr><td colspan="2" class="txtvalerror text-right" v-text="errors.first('Domicilio')"></td></tr>
                                        <tr>
                                            <td class="tcelda">Zona:</td>
                                            <td class="tcelda"><input type="text" class="form-control" v-model="zona"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 txtvalidador">* Datos Obligatorios</div>
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
                                                name="Filial" v-validate.initial="'required'">
                                                    <option v-for="filial in arrayFiliales" :key="filial.id"
                                                        :value="filial.idfilial" v-text="filial.nommunicipio"></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr><td colspan="2" class="txtvalerror text-right" v-text="errors.first('Filial')"></td></tr>
                                        <tr><td>Oficina: <span class="txtasterisco"></span></td>
                                            <td><select class="form-control" v-model="idoficina" name="Oficina" v-validate.initial="'required'">
                                                    <option v-for="oficina in arrayOficinas" :key="oficina.id"
                                                        :value="oficina.idoficina" v-text="oficina.nomoficina"></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr><td colspan="2" class="txtvalerror text-right" v-text="errors.first('Oficina')"></td></tr>
                                        <tr><td>Cargo: <span class="txtasterisco"></span></td>
                                            <td><select class="form-control" v-model="idcargo" name="Cargo" v-validate.initial="'required'">
                                                    <option v-for="cargo in arrayCargos" :key="cargo.id"
                                                        :value="cargo.idcargo" v-text="cargo.nomcargo"></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr><td colspan="2" class="txtvalerror text-right" v-text="errors.first('Cargo')"></td></tr>
                                        <tr><td nowrap>Ingreso: <span class="txtasterisco"></span></td>
                                            <td><input type="date" class="form-control" v-model="fechaingreso" 
                                                name="Ingreso" v-validate.initial="'required'">
                                            </td>
                                        </tr>
                                        <tr><td colspan="2" class="txtvalerror text-right" v-text="errors.first('Ingreso')"></td></tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table width="100%">
                                        <tr><td nowrap>ID Biométrico: <span class="txtasterisco"></span></td>
                                            <td><input type="text" class="form-control" v-model="codbiom" 
                                                name="Biométrico" v-validate.initial="'required|numeric'"></td>
                                        </tr>
                                        <tr><td colspan="2" class="txtvalerror text-right" v-text="errors.first('Biométrico')"></td></tr>
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
                                        <tr><td colspan="2" align="center"><span class="txtvalidador">* Datos Obligatorios</span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h4 class="titazul">Seguro Social</h4>
                            <div>
                                <div class="tabla100">
                                    <div class="tfila">
                                        <div class="tcelda taltura">Pensiones:</div>
                                        <div class="tcelda">
                                            <select class="form-control" v-model="ssocial" >
                                                <option>FUTURO</option>
                                                <option>PREVISIÓN</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="tfila">
                                        <div class="tcelda taltura nowrap">Nro Seguro: </div>
                                        <div class="tcelda"><input class="form-control" v-model="codssocial"></div>
                                    </div>
                                    <div class="tfila">
                                        <div class="tcelda taltura nowrap">Salud:</div>
                                        <div class="tcelda"><select class="form-control" v-model="ssalud">
                                            <option>CNS</option>
                                            <option>CPS</option>
                                            <option>CSBP</option>
                                            <option>COSSMIL</option>
                                            <option>CSC</option>
                                            <option>CORDES</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="tfila">
                                        <div class="tcelda taltura nowrap">Cód Seguro: </div>
                                        <div class="tcelda"><input type="text" class="form-control" v-model="codssalud"></div>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                    </div>
                    <!-- </form> -->
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="modalEmpleado=0">Cerrar</button>
                    <button class="btn btn-primary" :disabled="errors.any()" @click="accion==1?storeEmpleado():updateEmpleado()">
                        Guardar <span v-if="accion==2">Modificaciones</span></button>
                </div>
            </div>
        </div>
    </div>

    <empDocumento  ref="empDocumento"> </empDocumento>
    <empContrato   ref="empContrato">  </empContrato>
    <empPermiso    ref="empPermiso">   </empPermiso>
    <empAsistencia ref="empAsistencia"></empAsistencia>
    <empReferencia ref="empReferencia"></empReferencia>
</main>
</template>

<script>

Vue.component('empDocumento' ,require('./empDocumento.vue').default);
Vue.component('empContrato'  ,require('./empContrato.vue').default);
Vue.component('empPermiso'   ,require('./empPermiso.vue').default);
Vue.component('empAsistencia',require('./empAsistencia.vue').default);
Vue.component('empReferencia',require('./empReferencia.vue').default);

import * as reporte from '../../functions.js';
  
export default {     
    data (){ return {
        modalEmpleado:0, accion:1, completo:1, activo:1, ipbirt:'',
        buscado:'', regEmpleado:[],
        arrayEmpleados:[], arrayDepartamentos:[], arrayFormaciones:[], arrayProfesiones:[],
        arrayFiliales:[], arrayOficinas:[], arrayCargos:[], arrayBancos:[], 
        idempleado:'', nombre:'', apaterno:'', amaterno:'', sexo:'', ci:'', iddepartamento:'',
        fechanacimiento:'', idestadocivil:'', idformacion:'', idprofesion:'', foto:'', nuevafoto:0,
        telcelular:'', telfijo:'', email:'', domicilio:'', zona:'',
        idfilial:'', idoficina:'', idcargo:'', fechaingreso:'',
        idbanco:'', nrcuenta:'', codbiom:'',
        ssocial:'', ssalud:'', codssocial:'',codssalud:'', gruposangre:'',
        arraySangre:['O+','O-','A+','A-','B+','B-','AB+','AB-'],
        arrayEstados:[{id:1,estado:'Solter@'},{id:2,estado:'Casad@'},{id:3,estado:'Divorciad@'}],        
    }},

directives: { focus },

    methods : {
        listaEmpleados(){
            var activo;
            this.activo?activo=1:activo=0;
            var url='/rrh_empleado/listaEmpleados?activo='+activo+'&buscado='+this.buscado;
            axios.get(url).then(response=>{
                this.arrayEmpleados=response.data.empleados;
                this.ipbirt=response.data.ipbirt;
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
            var url='/fil_oficina/listaOficinas?activo=1&idfilial='+idfilial;
            axios.get(url).then(response=>{
                this.arrayOficinas=response.data.oficinas;
            });
        },

        listaCargos(){
            axios.get('/rrh_cargo/listaCargos?activo=1').then(response=>{
                this.arrayCargos=response.data.cargos;
            });
        },

        listaBancos(){
            var url='/con_entidadbancaria/selectEntidadbancaria?activo=1';
            axios.get(url).then(response=>{
                this.arrayBancos=response.data.bancos;
            });
        },

        resetEmpleado(){ 
            this.nombre='hhh'; this.apaterno='hhh'; this.amaterno=''; 
            this.sexo='M'; this.ci='444'; this.iddepartamento='1';
            this.fechanacimiento='1987-01-01'; this.idestadocivil=''; 
            this.idformacion=''; this.idprofesion=''; this.foto='';
            this.telcelular='777'; this.telfijo=''; this.email=''; 
            this.domicilio='yyy'; this.zona=''; this.foto='';
            this.idfilial='1'; this.idoficina='1'; this.idcargo='1'; this.fechaingreso='2020-01-01';
            this.nrcontrato=''; this.tipocontrato=''; this.inicontrato=''; this.fincontrato=''; 
            this.sueldo=''; this.idbanco=''; this.nrcuenta=''; this.codbiom='11';
        },

        buscarImagen(event){
            let archivo=event.target.files[0];
            let lector=new FileReader();
            lector.onload = event => this.foto=event.target.result;
            this.nuevafoto=1;
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

        kardexEmpleado(empleado){
            var url=[];
            url.push('http://'+this.ipbirt+':8080');
            url.push('/birt-viewer/frameset?__report=reportes/rhumanos');
            url.push('/rrh_kardex.rptdesign'); //archivo
            url.push('&idempleado='+empleado.idempleado); //idempleado
            url.push('&__format=pdf'); //formato
            url.push('&ip='+this.ipbirt);//pa la foto
            reporte.viewPDF(url.join(''),'Kardex Personal');
        },

        nuevoEmpleado(){            
            this.modalEmpleado=1;                        
            this.accion=1;
            this.completo=0;
            this.resetEmpleado();
        },

        async editarEmpleado(empleado){
            window.scroll({top:0,left:0,behavior:'smooth'});
            this.modalEmpleado=1;       this.nuevafoto=0;
            this.accion=2;
            this.idempleado=empleado.idempleado;
            let response=await axios.get('/rrh_empleado/verEmpleado?idempleado='+this.idempleado);
            this.regEmpleado=response.data.empleado[0];
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
        },
/*
        valEmpleado(){
            this.completo=0;
            if((this.nombre)&&(this.apaterno)&&(this.ci)&&(this.iddepartamento)&&(this.sexo)
                &&(this.fechanacimiento)&&(this.telcelular)&&(this.domicilio)
                &&(this.idfilial)&&(this.idoficina)&&(this.cargo)&&(this.fechaingreso)
                &&(this.codbiom)) this.completo=1;
        },
*/
        storeEmpleado(){
            swal({ title:'Procesando...',text:'Un momento por favor', type:'warning',
                showCancelButton:false, showConfirmButton:false, 
                allowOutsideClick: false, allowEscapeKey: false, allowEnterKey: false,
                onOpen:() => { swal.showLoading() }
            });             
            axios.post('/rrh_empleado/storeEmpleado',{
                'nombre':this.nombre.toUpperCase(),
                'apaterno':this.apaterno.toUpperCase(),
                'amaterno':this.amaterno.toUpperCase(),
                'ci':this.ci,
                'iddepartamento':this.iddepartamento,
                'sexo':this.sexo,
                'gruposangre':this.gruposangre,
                'fechanacimiento':this.fechanacimiento,
                'idestadocivil':this.idestadocivil,
                'foto':$('#lafoto').length?$('#lafoto').cropper('getCroppedCanvas').toDataURL():'',
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
                //this.activo=1;
                this.buscado=this.apaterno;
                this.modalEmpleado=0;
                this.listaEmpleados();
            });
        },

        updateEmpleado(){
            axios.put('/rrh_empleado/updateEmpleado',{
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
                'foto':$('#lafoto').length?$("#lafoto").cropper('getCroppedCanvas').toDataURL():'',
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
                swal('Datos Actualizados','','success');
                this.modalEmpleado=0;
                //this.activo=1;
                this.listaEmpleados();
            });
            
        },

        estadoEmpleado(empleado){
            this.idempleado=empleado.idempleado;
            if(empleado.activo){
                swal({  title:'Desactivará el registro de<br>'+empleado.nombre+' '+empleado.apaterno, type: 'warning', 
                    html: 'No podrá acceder a la información dependiente', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Empleado',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(confirmar=>{
                    if(confirmar.value) this.switchEmpleado(1);
                });
            }
            else this.switchEmpleado(0);
        },

        switchEmpleado(activo){
            if(activo) var titswal='Desactivado'; else var titswal='Activado';
            var url='/rrh_empleado/switchEmpleado?idempleado='+this.idempleado;
            let me=this;
            axios.put(url).then(function(){
                swal(titswal+' correctamente','','success');
                me.activo=1;
                me.listaEmpleados();
            });
        },

    },
        
    mounted() {
        this.listaEmpleados();
        this.listaDepartamentos();
        this.listaFiliales();
        this.listaOficinas(1);
        this.listaCargos();
        this.listaFormaciones();
        this.listaProfesiones();
        this.listaBancos();
    }
}
</script>
