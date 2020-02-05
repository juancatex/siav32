<template>
<main class="main">
    <div class="modal" :class="modalConfig?'mostrar':''">
        <div class="modal-dialog modal-primary ">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title">Configurar Servicio</h4>
                    <button class="close" @click="modalConfig=0">x</button>
                </div>
                
                <div class="modal-body" >
                    <h4 class="titsubrayado" v-text="regEstablecimiento.nommunicipio+': '+regEstablecimiento.nomestablecimiento"></h4>

                    <!-- MULTIFAMILIAR JUANCITO PINTO  MULTIFAMILIAR JUANCITO PINTO  MULTIFAMILIAR JUANCITO PINTO -->
                    <template v-if="regEstablecimiento.codservicio=='VIV'">
                        <div v-if="!divFormulario" class="row" style="padding-bottom:20px">
                            <div class="col-md-6">
                                <div class="ttabla titcard">
                                    <div class="tcelda">Configurar <span v-text="tipogrupos+' '+String.fromCharCode(nrgrupo+64)"></span></div>
                                </div>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="input-group-append" style="display:inline">
                                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <span v-text="tipogrupos+' '+String.fromCharCode(nrgrupo+64)"></span><span class="caret"></span>
                                    </button>
                                    <div class="dropdown-menu" x-placement="bottom-end">
                                        <a href="#" class="dropdown-item" v-for="k in regEstablecimiento.cantgrupos*1" :key="k" 
                                            v-text="tipogrupos+' '+String.fromCharCode(k+64)" @click="listaAmbientes(k)"></a>
                                        <a href="#" class="dropdown-item" @click="addGrupo()">Crear  <span v-text="tipogrupos"></span></a>
                                        <a href="#" class="dropdown-item" @click="remGrupo()">Quitar <span v-text="tipogrupos"></span></a>
                                    </div>
                                </div>
                                <button class="btn btn-primary" @click="nuevoAmbiente()">Nuevo Depto</button>
                            </div>                            
                        </div>
                        <div v-if="!divFormulario" class="table-responsive" style="height:400px; overflow-y:scroll">
                            <table class="table table-striped table-sm ">
                                <thead class="tcabecera">
                                    <tr>
                                        <th>Cód</th>
                                        <th>Tipo</th>
                                        <th>Piso</th>
                                        <th>Garantía</th>
                                        <th>Canon Socio </th>
                                        <th>Canon Real</th>
                                        <th><span class="badge badge-success" v-text="arrayAmbientes.length+' items'"></span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="ambiente in arrayAmbientes" :key="ambiente.id" align="center" :class="ambiente.activo?'':'txtdesactivado'">
                                        <td v-text="ambiente.codambiente" ></td>
                                        <td v-text="ambiente.tipo" ></td>
                                        <td v-text="ambiente.piso" ></td>
                                        <td v-text="ambiente.garantia+'Bs'"></td>
                                        <td v-text="ambiente.tarifasocio+'Bs'" ></td>
                                        <td v-text="ambiente.tarifareal+'U$'" ></td>
                                        <td v-if="ambiente.activo" nowrap align="center">
                                            <button class="btn btn-warning btn-sm icon-pencil" @click="editarAmbiente(ambiente)"></button>
                                            <button class="btn btn-danger btn-sm icon-trash" @click="estadoAmbiente(ambiente)"></button>
                                        </td>
                                        <td v-else align="center">
                                            <button class="btn btn-warning btn-sm icon-action-undo" title="Restaurar ambiente" @click="estadoAmbiente(ambiente)"></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <h4  v-if="divFormulario" class="titazul">
                            <span v-text="tipogrupos+' '+String.fromCharCode(nrgrupo+64)"></span>:
                            <span v-text="accion==1?'Nuevo':'Editar '"></span> departamento 
                        </h4>
                        <div v-if="divFormulario" class="row">
                            <div class="col-md-6 col-sm-6">
                                Código: <span class="txtasterisco"></span>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" v-text="String.fromCharCode(nrgrupo+64)+'-'"></span>
                                    </div>
                                    <input type="text" class="form-control" v-model="codambiente">
                                </div>
                                Tipo: <span class="txtasterisco"></span>
                                <select class="form-control" v-model="tipo">
                                    <option >Garzonier</option>
                                    <option >2 Dorm</option>
                                    <option >3 Dorm</option>
                                    <option >Duplex</option>
                                </select>
                                Piso: <span class="txtasterisco"></span>
                                <select class="form-control text-right" v-model="piso">
                                    <option v-for="i in 7" :key="i" :value="i" v-text="i"></option>
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                Garantía: <span class="txtasterisco"></span>
                                <div class="input-group">
                                    <input type="text" class="form-control text-right" v-model="garantia">
                                    <div class="input-group-append"><span class="input-group-text">Bs</span></div>
                                </div>                            
                                Canon Socio: <span class="txtasterisco"></span>
                                <div class="input-group">
                                    <input type="text" class="form-control text-right" v-model="tarifasocio">
                                    <div class="input-group-append"><span class="input-group-text">Bs</span></div>
                                </div>                                
                                Canon Real: <span class="txtasterisco"></span>
                                <div class="input-group">
                                    <input type="text" class="form-control text-right" v-model="tarifareal">
                                    <div class="input-group-append"><span class="input-group-text">US</span></div>
                                </div>
                            </div>
                            <div class="col-md-12 text-center txtvalidador">* Todos los campos son obligatorios</div>
                        </div>
                    </template>

                    <!-- MAUSOLEO MAUSOLEO MAUSOLEO MAUSOLEO MAUSOLEO MAUSOLEO MAUSOLEO MAUSOLEO -->
                    <template v-if="regEstablecimiento.codservicio=='MAU'">
                        <div v-if="!divFormulario" class="row">
                            <div class="col-md-6">
                                <div class="ttabla titcard">
                                    <div class="tcelda" v-text="tipogrupos+' '+String.fromCharCode(nrgrupo+64)"></div>
                                </div>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="input-group-append" style="display:inline">
                                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <span v-text="tipogrupos+' '+String.fromCharCode(nrgrupo+64)"></span><span class="caret"></span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                                        <a href="#" class="dropdown-item" v-for="k in cantgrupos" :key="k" 
                                            v-text="tipogrupos+' '+String.fromCharCode(k+64)" @click="verBloque(k)"></a>
                                        <a href="#" class="dropdown-item" @click="addBloque()">Crear  <span v-text="tipogrupos"></span></a>
                                        <a href="#" class="dropdown-item" @click="remBloque()">Quitar <span v-text="tipogrupos"></span></a>
                                    </div>
                                </div>
                                <button class="btn btn-primary" @click="verBloque(nrgrupo),divFormulario=1">Configurar</button>
                            </div>
                        </div>
                        <div v-if="!divFormulario" class="table-responsive"><br>
                            <!--
                            <table class="table table-bordered table-sm">
                                <tr v-for="i in fil" :key="i">
                                    <td v-text="String.fromCharCode(nrgrupo+64)+(fil+1-i)" class="titcampo" align="center"></td>
                                    <td v-for="j in col" :key="j" v-text="j<10?'0'+j:j" align="center" 
                                        class="celdahover" style="cursor:pointer" @click="switchCaja(nrgrupo,i,j)"></td>
                                </tr>
                            </table>
                            -->
                        </div>
                        <!--
                        <div v-if="!divFormulario" class="row">
                            <div class="col-md-6">
                                <div class="table">
                                    <div class="tfila">
                                        <div class="tcelda tinput">Cant filas:
                                <select class="form-control text-right" v-model="fil">
                                    <option v-for="i in 6"  :key="i" :value="i" v-text="i"></option>
                                </select>
                                        </div>
                                        <div class="tcelda tinput">Nichos/Fila:
                                <select class="form-control text-right" v-model="col">
                                    <option v-for="i in 20" :key="i" :value="i" v-text="i"></option>
                                </select>
                                        </div>
                                        <div class="tcelda tinput" style="vertical-align:bottom">
                                        <button class="btn btn-primary icon-energy"></button>        
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 ">Tarifa:
                                <div class="input-group">
                                    <input type="text" class="form-control text-right" v-model="tarifasocio">
                                    <div class="input-group-append"><span class="input-group-text">Bs.</span></div>
                                    <button class="btn btn-primary cui-task"></button>
                                </div>
                            </div>
                        </div>
                        -->
                        <h4 v-if="divFormulario" class="titazul" v-text="'Configurar Bloque '+String.fromCharCode(nrgrupo+64)"></h4>
                        <div v-if="divFormulario" class="row">
                            <div class="col-md-4">Cant filas:
                                <select class="form-control text-right" v-model="fil">
                                    <option v-for="i in 6"  :key="i" :value="i" v-text="i"></option>
                                </select>
                            </div>
                            <div class="col-md-4">Nichos/Fila:
                                <select class="form-control text-right" v-model="col">
                                    <option v-for="i in 20" :key="i" :value="i" v-text="i"></option>
                                </select>
                            </div>
                            <div class="col-md-4">Tarifa:
                                <div class="input-group">
                                    <input type="text" class="form-control text-right" v-model="tarifasocio">
                                    <div class="input-group-append"><span class="input-group-text">Bs.</span></div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- HOSPEDAJE TRANSITORIO HOSPEDAJE TRANSITORIO HOSPEDAJE TRANSITORIO HOSPEDAJE TRANSITORIO -->
                    <template v-if="regEstablecimiento.codservicio=='HTR'">
                        <div v-if="!divFormulario" class="row" style="padding-bottom:20px">
                            <div class="col-md-6">
                                <div class="ttabla titcard">
                                    <div class="tcelda">Configurar <span v-text="tipogrupos+' '+nrgrupo"></span></div>
                                </div>
                            </div>                        
                            <div class="col-md-6 text-right">
                                <div class="input-group-append" style="display:inline">
                                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <span v-text="tipogrupos+' '+nrgrupo"></span><span class="caret"></span>
                                    </button>
                                    <div class="dropdown-menu" x-placement="bottom-end">
                                        <a href="#" class="dropdown-item" v-for="k in regEstablecimiento.cantgrupos" :key="k" 
                                            v-text="tipogrupos+' '+k" @click="listaAmbientes(k)"></a>
                                        <a href="#" class="dropdown-item" @click="addGrupo()">Crear  <span v-text="tipogrupos"></span></a>
                                        <a href="#" class="dropdown-item" @click="remGrupo()">Quitar <span v-text="tipogrupos"></span></a>
                                    </div>
                                </div>
                                <button class="btn btn-primary" @click="nuevoAmbiente()">Nueva Pieza</button>
                            </div>
                        </div>
                        <div v-if="!divFormulario" class="table-responsive" style="height:400px; overflow-y:scroll">
                            <table class="table table-striped table-sm">
                                <thead class="tcabecera">
                                    <tr>
                                        <th>Pieza</th>
                                        <th>Tipo</th>
                                        <th>Cap.</th>
                                        <th>Tarifa Socios</th>
                                        <th>Tarifa Externa</th>
                                        <th><span class="badge badge-success" v-text="arrayAmbientes.length+' items'"></span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="ambiente in arrayAmbientes" :key="ambiente.id" align="center" :class="ambiente.activo?'':'txtdesactivado'">
                                        <td v-text="ambiente.codambiente"></td>
                                        <td v-text="ambiente.tipo"></td>
                                        <td v-text="ambiente.capacidad"></td>
                                        <td v-text="ambiente.tarifasocio+'Bs'" align="right"></td>
                                        <td v-text="ambiente.tarifareal+'Bs'" align="right"></td>
                                        <td v-if="ambiente.activo" nowrap>
                                            <button class="btn btn-warning btn-sm icon-pencil" title="Editar datos" @click="editarAmbiente(ambiente)"></button>
                                            <button class="btn btn-danger btn-sm icon-trash" title="Desactivar ambiente" @click="estadoAmbiente(ambiente)"></button>
                                        </td>
                                        <td v-else>
                                            <button class="btn btn-warning btn-sm icon-action-undo" title="Restaurar ambiente" @click="estadoAmbiente(ambiente)"></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <h4 v-if="divFormulario" class="titazul">
                            <span v-text="tipogrupos+' '+nrgrupo"></span>:
                            <span v-text="accion==1?'Nueva':'Editar '"></span> pieza
                        </h4>
                        <div v-if="divFormulario" class="row">
                            <div class="col-md-6 col-sm-6">
                                Código: <span class="txtasterisco"></span>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">P</span></div>
                                    <input type="text" class="form-control" v-model="codambiente">
                                </div>
                                Tipo: <span class="txtasterisco"></span>
                                <select class="form-control" v-model="tipo">
                                    <option >Individual</option>
                                    <option >Matrimonial</option>
                                    <option >Familiar</option>
                                </select>
                                Capacidad: <span class="txtasterisco"></span>
                                <select class="form-control text-right" v-model="capacidad">
                                    <option v-for="i in 5" :key="i" :value="i" v-text="i"></option>
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                Tarifa Socios: <span class="txtasterisco"></span>
                                <div class="input-group">
                                    <input type="text" class="form-control text-right" v-model="tarifasocio">
                                    <div class="input-group-append"><span class="input-group-text">Bs.</span></div>
                                </div>
                                Tarifa Externa: <span class="txtasterisco"></span>
                                <div class="input-group">
                                    <input type="text" class="form-control text-right" v-model="tarifareal">
                                    <div class="input-group-append"><span class="input-group-text">Bs.</span></div>
                                </div>
                            </div>
                            <div class="col-md-12 txtvalidador text-center">* Todos los campos son obligatorios</div>
                        </div>                        
                    </template>

                    <!-- HOSPEDAJE PERMANENTE HOSPEDAJE PERMANENTE HOSPEDAJE PERMANENTE HOSPEDAJE PERMANENTE -->
                    <template v-if="regEstablecimiento.codservicio=='HPR'">
                        <div v-if="!divFormulario" class="row" style="padding-bottom:20px">
                            <div class="col-md-6">
                                <div class="ttabla titcard">
                                    <div class="tcelda">Configurar <span v-text="tipogrupos+' '+nrgrupo"></span></div>
                                </div>
                            </div>                        
                            <div class="col-md-6 text-right">
                                <div class="input-group-append" style="display:inline">
                                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <span v-text="tipogrupos+' '+nrgrupo"></span><span class="caret"></span>
                                    </button>
                                    <div class="dropdown-menu" x-placement="bottom-end">
                                        <a href="#" class="dropdown-item" v-for="k in regEstablecimiento.cantgrupos" :key="k" 
                                            v-text="tipogrupos+' '+k" @click="listaAmbientes(k)"></a>
                                        <a href="#" class="dropdown-item" @click="addGrupo()">Crear  <span v-text="tipogrupos"></span></a>
                                        <a href="#" class="dropdown-item" @click="remGrupo()">Quitar <span v-text="tipogrupos"></span></a>
                                    </div>
                                </div>
                                <button class="btn btn-primary" @click="nuevoAmbiente()">Nueva Pieza</button>
                            </div>
                        </div>
                        <div v-if="!divFormulario" class="table-responsive" style="height:400px; overflow-y:scroll">
                            <table class="table table-striped table-sm">
                                <thead class="tcabecera">
                                    <tr>
                                        <th>Código</th>
                                        <th>Tipo</th>
                                        <th>Piso</th>
                                        <th>Garantía</th>
                                        <th>Tarifa Socios</th>
                                        <th>Tarifa Externa</th>
                                        <th><span class="badge badge-success" v-text="arrayAmbientes.length+' items'"></span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="ambiente in arrayAmbientes" :key="ambiente.id" align="center" :class="ambiente.activo?'':'txtdesactivado'">
                                        <td v-text="ambiente.codambiente"></td>
                                        <td v-text="ambiente.tipo" nowrap></td>
                                        <td v-text="ambiente.piso"></td>
                                        <td v-text="ambiente.garantia+'Bs'" align="right"></td>
                                        <td v-text="ambiente.tarifasocio+'Bs'" align="right"></td>
                                        <td v-text="ambiente.tarifareal+'Bs'" align="right"></td>
                                        <td v-if="ambiente.activo" nowrap>
                                            <button class="btn btn-warning btn-sm icon-pencil" title="Editar datos" @click="editarAmbiente(ambiente)"></button>
                                            <button class="btn btn-danger btn-sm icon-trash" title="Desactivar ambiente" @click="estadoAmbiente(ambiente)"></button>
                                        </td>
                                        <td v-else>
                                            <button class="btn btn-warning btn-sm icon-action-undo" title="Restaurar ambiente" @click="estadoAmbiente(ambiente)"></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <h4 v-if="divFormulario" class="titazul">
                            <span v-text="tipogrupos+' '+nrgrupo"></span>:
                            <span v-text="accion==1?'Nueva':'Editar '"></span> pieza
                        </h4>
                        <div v-if="divFormulario" class="row">
                            <div class="col-md-6 col-sm-6">
                                Código: <span class="txtasterisco"></span>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">P</span></div>
                                    <input type="text" class="form-control" v-model="codambiente">
                                </div>
                                Piso: <span class="txtasterisco"></span>
                                <select class="form-control text-right" v-model="piso">
                                    <option v-for="i in 5" :key="i" :value="i" v-text="i"></option>
                                </select>
                                Tipo: <span class="txtasterisco"></span>
                                <select class="form-control" v-model="tipo">
                                    <option>B/privado</option>
                                    <option>B/compartido</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                Garantía: <span class="txtasterisco"></span>
                                <div class="input-group">
                                    <input type="text" class="form-control text-right" v-model="garantia">
                                    <div class="input-group-append"><span class="input-group-text">Bs.</span></div>
                                </div>
                                Tarifa Socios: <span class="txtasterisco"></span>
                                <div class="input-group">
                                    <input type="text" class="form-control text-right" v-model="tarifasocio">
                                    <div class="input-group-append"><span class="input-group-text">Bs.</span></div>
                                </div>
                                Tarifa Externa: <span class="txtasterisco"></span>
                                <div class="input-group">
                                    <input type="text" class="form-control text-right" v-model="tarifareal">
                                    <div class="input-group-append"><span class="input-group-text">Bs.</span></div>
                                </div>
                            </div>
                            <div class="col-md-12 txtvalidador text-center">* Todos los campos son obligatorios</div>
                        </div>                        
                    </template>

                    <!-- SERVICIOS PERMANENTES  SERVICIOS PERMANENTES SERVICIOS PERMANENTES SERVICIOS PERMANENTES -->
                    <template v-if="regEstablecimiento.codservicio=='PER'">
                        <div v-if="!divFormulario" class="row" style="padding-bottom:20px">
                            <div class="col-md-6">
                                <div class="ttabla titcard"><div class="tcelda">Tarifario</div></div>
                            </div>
                            <div class="col-md-6 text-right">
                                <button class="btn btn-primary" @click="nuevoAmbiente()">Crear Tarifa</button>
                            </div>
                        </div>
                        <table v-if="!divFormulario" class="table table-striped table-sm">
                            <thead class="tcabecera">
                                <tr>
                                    <th>Código</th>
                                    <th>Garantía</th>
                                    <th>Tarifa Socios</th>
                                    <th>Tarifa Externa</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="ambiente in arrayAmbientes" :key="ambiente.id" align="center" :class="ambiente.activo?'':'txtdesactivado'">
                                    <td v-text="ambiente.codambiente"></td>
                                    <td v-text="ambiente.garantia"></td>
                                    <td v-text="ambiente.tarifasocio"></td>
                                    <td v-text="ambiente.tarifareal"></td>
                                    <td v-if="ambiente.activo" align="center">
                                        <button class="btn btn-warning icon-pencil" title="Editar tarifa" @click="editarAmbiente(ambiente)"></button>
                                        <button class="btn btn-danger icon-trash" title="Desactivar tarifa" @click="estadoAmbiente(ambiente)"></button>
                                    </td>
                                    <td v-else>
                                        <button class="btn btn-warning icon-action-undo" title="Restaurar ambiente" @click="estadoAmbiente(ambiente)"></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <h4 v-if="divFormulario" class="titazul">
                            <span v-text="accion==1?'Crear':'Editar'"></span> Tarifas
                        </h4>
                        <div v-if="divFormulario" class="row">
                            <div class="col-md-6 col-sm-6">
                                Código Actividad: <span class="txtasterisco"></span>
                                <input type="text" class="form-control" v-model="codambiente">                            
                                <br>Tarifa Garantía: <span class="txtasterisco"></span>
                                <div class="input-group">
                                    <input type="text" class="form-control text-right" v-model="garantia">
                                    <div class="input-group-append"><span class="input-group-text">Bs.</span></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                Tarifa Socios: <span class="txtasterisco"></span>
                                <div class="input-group">
                                    <input type="text" class="form-control text-right" v-model="tarifasocio">
                                    <div class="input-group-append"><span class="input-group-text">Bs.</span></div>
                                </div>
                                <br>Tarifa Externa: <span class="txtasterisco"></span>
                                <div class="input-group">
                                    <input type="text" class="form-control text-right" v-model="tarifareal">
                                    <div class="input-group-append"><span class="input-group-text">Bs.</span></div>
                                </div>
                            </div>
                            <div class="col-md-12 text-center txtvalidador">* Todos los campos son obligatorios</div>
                        </div>                        
                    </template>

                    <!-- SERVICIOS EVENTUALES SERVICIOS EVENTUALES SERVICIOS EVENTUALES SERVICIOS EVENTUALES-->
                    <template v-if="regEstablecimiento.codservicio=='EVE'">
                        <div v-if="!divFormulario" class="row" style="padding-bottom:20px">
                            <div class="col-md-6">
                                <div class="ttabla titcard"><div class="tcelda">Tarifario</div></div>
                            </div>
                            <div class="col-md-6 text-right">
                                <button class="btn btn-primary" @click="nuevoAmbiente()">Crear Tarifa</button>
                            </div>
                        </div>
                        <div v-if="!divFormulario" class="table-responsive">
                            <table class="table table-striped table-sm">
                                <thead class="tcabecera">
                                    <tr>
                                        <th>Cód</th>                                    
                                        <th>Actividad</th>
                                        <th>Garantía</th>
                                        <th>Tarifa Socios</th>
                                        <th>Tarifa Externa</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="ambiente in arrayAmbientes" :key="ambiente.id" align="center" :class="ambiente.activo?'':'txtdesactivado'">
                                        <td v-text="ambiente.codambiente"></td>
                                        <td v-text="ambiente.tipo"></td>
                                        <td v-text="ambiente.garantia?ambiente.garantia+'Bs':'--'"></td>
                                        <td><span v-text="ambiente.tarifasocio"></span>Bs<span v-text="ambiente.capacidad?'/Hr':''"></span></td>
                                        <td><span v-text="ambiente.tarifareal"></span>Bs<span v-text="ambiente.capacidad?'/Hr':''"></span></td>
                                        <td v-if="ambiente.activo" nowrap>
                                            <button class="btn btn-warning btn-sm icon-pencil" title="Editar tarifas" @click="editarAmbiente(ambiente)"></button>
                                            <button class="btn btn-danger btn-sm icon-trash" title="Desactivar tarifas" @click="estadoAmbiente(ambiente)"></button>
                                        </td>
                                        <td v-else>
                                            <button class="btn btn-warning btn-sm icon-action-redo" title="Restaurar tarifas" @click="estadoAmbiente(ambiente)"></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <h4 v-if="divFormulario" class="titazul">
                            <span v-text="accion==1?'Crear':'Editar'"></span> Actividad / Tarifas
                        </h4>
                        <div v-if="divFormulario" class="row">
                            <div class="col-md-6 col-sm-6">
                                Código Actividad: <span class="txtasterisco"></span>
                                <input type="text" class="form-control" v-model="codambiente">
                                Actividad: <span class="txtasterisco"></span>
                                <input type="text" class="form-control" v-model="tipo">
                                <br><input type="checkbox" v-model="capacidad"> Servicio tarifado por Hora
                            </div>
                            <div class="col-md-6 col-sm-6">
                                Garantía: <span class="txtasterisco"></span>
                                <div class="input-group">
                                    <input type="text" class="form-control text-right" v-model="garantia">
                                    <div class="input-group-append"><span class="input-group-text">Bs.</span></div>
                                </div>
                                Tarifa Socios: <span class="txtasterisco"></span>
                                <div class="input-group">
                                    <input type="text" class="form-control text-right" v-model="tarifasocio">
                                    <div class="input-group-append"><span class="input-group-text">Bs.{{capacidad?'/Hr':''}}</span></div>
                                </div>
                                Tarifa Externa: <span class="txtasterisco"></span>
                                <div class="input-group">
                                    <input type="text" class="form-control text-right" v-model="tarifareal">
                                    <div class="input-group-append"><span class="input-group-text">Bs.{{capacidad?'/Hr':''}}</span></div>
                                </div>
                            </div>
                            <div class="col-md-12 text-center txtvalidador">* Todos los campos son obligatorios</div>
                        </div>                        
                    </template>

                </div>

                <div class="modal-footer" v-if="divFormulario&&regEstablecimiento.codservicio!='MAU'" >
                    <button class="btn btn-secondary" @click="divFormulario=0">Cancelar</button>
                    <button class="btn btn-primary" @click="accion==1?storeAmbiente():updateAmbiente()" v-text="txtBoton"></button>
                </div>
                <div class="modal-footer" v-if="divFormulario&&regEstablecimiento.codservicio=='MAU'" >
                    <button class="btn btn-secondary" @click="verBloque(nrgrupo)">Cancelar</button>
                    <button class="btn btn-primary" @click="storeBloque(nrgrupo)">Guardar Configuración</button>
                </div>

            </div>
        </div>
    </div>
</main>
</template>

<script>
export default {
    data(){return {
        modalConfig:0, txtBoton:'', accion:'', divFormulario:0,
        regEstablecimiento:[], cantgrupos:'', tipogrupos:'',
        arrayAmbientes:[], arrayCantgrupos:[],
        idambiente:'',codambiente:'', piso:'',capacidad:'', tipo:'',
        garantia:'', tarifasocio:'',tarifareal:'', fil:'', col:'',
    }},

    methods:{
        async abrirModal(establecimiento){
            var url='/ser_establecimiento/listaEstablecimientos?idestablecimiento='+establecimiento.idestablecimiento;
            let response=await axios(url);
            this.regEstablecimiento=response.data.establecimientos[0];
            this.tipogrupos='';
            if((this.regEstablecimiento.idservicio==1)||(this.regEstablecimiento.idservicio==4)) this.tipogrupos='Bloque';
            if((this.regEstablecimiento.idservicio==2)||(this.regEstablecimiento.idservicio==3)) this.tipogrupos='Piso';
            this.cantgrupos=this.regEstablecimiento.cantgrupos;
            if(this.regEstablecimiento.codservicio=='MAU') this.verBloque(1); 
            else this.listaAmbientes(1);
            this.modalConfig=1;
            this.divFormulario=0;
            this.resetAmbiente();
        },

        listaAmbientes(nrgrupo){
            this.nrgrupo=nrgrupo;
            var url='/ser_ambiente/listaAmbientes?idestablecimiento='+this.regEstablecimiento.idestablecimiento;
            if(this.tipogrupos=='Bloque') url=url+'&bloque='+String.fromCharCode(nrgrupo+64);
            if(this.tipogrupos=='Piso')   url=url+'&piso='+nrgrupo;
            let me=this;
            axios.get(url).then(function(response){
                me.arrayAmbientes=response.data.ambientes;
            });
        },

        resetAmbiente(){
            this.codambiente='';
            this.piso='';
            this.tipo='';
            this.capacidad='';
            this.garantia=0;
            this.tarifasocio=0;
            this.tarifareal=0;
        },

        nuevoAmbiente(){
            this.divFormulario=1;
            this.accion=1;
            this.txtBoton='Guardar';
            this.resetAmbiente();
        },

        editarAmbiente(ambiente){
            this.accion=2;
            this.txtBoton ='Guardar modificaciones';
            this.divFormulario=1;
            this.idambiente=ambiente.idambiente;
            this.codambiente=ambiente.codambiente;
            if(this.tipogrupos) this.codambiente=ambiente.codambiente.substr(1,4);
            this.piso=ambiente.piso;
            this.tipo=ambiente.tipo;
            this.capacidad=ambiente.capacidad;
            this.garantia=ambiente.garantia;
            this.tarifasocio=ambiente.tarifasocio;
            this.tarifareal=ambiente.tarifareal;
        },

        storeAmbiente(){
            let me=this;
            var inicodigo='';
            if(this.tipogrupos=='Bloque') inicodigo=String.fromCharCode(this.nrgrupo+64);
            if(this.tipogrupos=='Piso') { inicodigo='P';  this.piso=this.nrgrupo; }
            axios.post('/ser_ambiente/storeAmbiente',{
                'idestablecimiento':this.regEstablecimiento.idestablecimiento,
                'codambiente':inicodigo+(this.codambiente.toUpperCase()),
                'piso':this.piso,
                'tipo':this.tipo?this.tipo.toUpperCase():'',
                'capacidad':this.capacidad,
                'garantia':this.garantia,
                'tarifasocio':this.tarifasocio,
                'tarifareal':this.tarifareal
            }).then(function(){
                swal('Creado correctamente','','success');
                me.listaAmbientes(me.nrgrupo);
                me.divFormulario=0;
            });
        },

        updateAmbiente(){
            let me=this;
            var inicodigo='';
            if(this.tipogrupos=='Bloque') inicodigo=String.fromCharCode(this.nrgrupo+64);
            if(this.tipogrupos=='Piso') { inicodigo='P';  this.piso=this.nrgrupo; }
            axios.put('/ser_ambiente/updateAmbiente',{
                'idambiente':this.idambiente,
                'codambiente':inicodigo+(this.codambiente.toUpperCase()),
                'piso':this.piso,
                'tipo':this.tipo?this.tipo.toUpperCase():'',
                'capacidad':this.capacidad,
                'garantia':this.garantia,
                'tarifasocio':this.tarifasocio,
                'tarifareal':this.tarifareal
            }).then(function(){
                swal('Datos actualizados','','success');
                me.listaAmbientes(me.nrgrupo);
                me.divFormulario=0;
            });
        },

        estadoAmbiente(ambiente){
            this.idambiente=ambiente.idambiente;
            if(ambiente.activo){
                swal({  title:'Desactivará '+ambiente.codambiente, type: 'warning', 
                    html: 'No podrá acceder a la información dependiente', showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Desactivar Ambiente',
                    cancelButtonText:'Cancelar', reverseButtons: true
                }).then(confirmar=>{
                    if(confirmar.value) this.switchAmbiente(1);
                });
            }
            else this.switchAmbiente(0);
        },
        
        switchAmbiente(activo){
            if(activo) var titswal='Desactivado'; else var titswal='Activado';
            var url='/ser_ambiente/switchAmbiente?idambiente='+this.idambiente;
            let me=this;
            axios.put(url).then(function(){
                swal(titswal+' correctamente','','success');
                me.listaAmbientes(me.nrgrupo);
            });
        },

        addGrupo(){
            swal({  title:'Nuevo '+this.tipogrupos, type: 'info', showCancelButton: true,
                html: 'Creará nuevo '+this.tipogrupos+' en<br>'+this.regEstablecimiento.nomestablecimiento, 
                confirmButtonColor:'#2eb85c', confirmButtonText:'Crear '+this.tipogrupos,
                cancelButtonText:'Cancelar', reverseButtons: true
            }).then(confirmar=>{if(confirmar.value) this.updateCantgrupos('add')});
        },

        remGrupo(){
            if(this.cantgrupos==1){
                swal('Imposible remover','Debe existir al menos un '+this.tipogrupos,'error'); return;
            }
            swal({  title:'Quitar '+this.tipogrupos, type: 'warning', showCancelButton: true,
                html: 'Removerá el último '+this.tipogrupos+' en<br>'+this.regEstablecimiento.nomestablecimiento
                    +'.<br>No podrá acceder a la información dependiente', 
                confirmButtonColor:'#f86c6b', confirmButtonText:'Remover '+this.tipogrupos,
                cancelButtonText:'Cancelar', reverseButtons: true
            }).then(confirmar=>{if(confirmar.value) this.updateCantgrupos('rem')});
        },

        updateCantgrupos(op){
            if(op=='add') { this.cantgrupos++; var ope='creado'; }
            if(op=='rem') { this.cantgrupos--; var ope='eliminado'}
            let me=this;
            axios.put('/ser_establecimiento/updateEstablecimiento',{
                'idestablecimiento':this.regEstablecimiento.idestablecimiento,
                'cantgrupos':this.cantgrupos
            }).then(function(){
                swal(me.tipogrupos+' '+ope+' correctamente','','success');
                me.regEstablecimiento.cantgrupos=me.cantgrupos;
                me.listaAmbientes(me.cantgrupos);
            });
        },

        // para mausoleos para mausoleos para mausoleos para mausoleos
        verBloque(nrgrupo){
            this.nrgrupo=nrgrupo;
            this.divFormulario=0;
            this.arrayCantgrupos=JSON.parse('['+this.regEstablecimiento.cantgrupos+']');
            this.cantgrupos=this.arrayCantgrupos.length-1;
            //this.tarifasocio=this.arrayCantgrupos[0];
            this.fil=Math.floor(this.arrayCantgrupos[nrgrupo]/100);
            this.col=Math.floor(this.arrayCantgrupos[nrgrupo]%100);
        },

        addBloque(){
            swal({  title:'Nuevo Bloque', type: 'info', showCancelButton: true,
                html: 'Creará un nuevo bloque en<br>'+this.regEstablecimiento.nomestablecimiento, 
                confirmButtonColor:'#2eb85c', confirmButtonText:'Crear '+this.tipogrupos,
                cancelButtonText:'Cancelar', reverseButtons: true
            }).then(confirmar=>{if(confirmar.value) this.updateBloques('add')});
        },

        remBloque(){
            if(this.cantgrupos==1){
                swal('Imposible remover','Debe existir al menos un Bloque','error'); return;
            }
            swal({  title:'Quitar Bloque', type: 'warning', showCancelButton: true,
                html: 'Removerá el último '+this.tipogrupos+' en<br>'+this.regEstablecimiento.nomestablecimiento
                    +'.<br>No podrá acceder a la información dependiente', 
                confirmButtonColor:'#f86c6b', confirmButtonText:'Remover '+this.tipogrupos,
                cancelButtonText:'Cancelar', reverseButtons: true
            }).then(confirmar=>{if(confirmar.value) this.updateBloques('rem')});            
        },

        updateBloques(op){
            if(op=='add') { this.arrayCantgrupos.push(100); var titswal='creado'; }
            if(op=='rem') { this.arrayCantgrupos.pop();  var titswal='eliminado'; }
            let me=this;
            axios.put('/ser_establecimiento/updateEstablecimiento',{
                'idestablecimiento':this.regEstablecimiento.idestablecimiento,
                'cantgrupos':this.arrayCantgrupos.join()
            }).then(function(){
                swal('Bloque '+titswal,'','success');
                /*
                var url='/ser_establecimiento/listaEstablecimientos?idestablecimiento='
                    +me.regEstablecimiento.idestablecimiento;
                axios.get(url).then(function(response){
                    me.regEstablecimiento=response.data.establecimientos[0];
                    me.verBloque(me.nrgrupo);
                });
                */
                me.abrirModal(me.regEstablecimiento);
                me.verBloque(me.nrgrupo);
            });
        },

        storeBloque(nrgrupo){
            //this.arrayCantgrupos[0]=this.tarifasocio*1;
            this.arrayCantgrupos[nrgrupo]=(this.fil*100)+this.col;
            let me=this;
            axios.put('/ser_ambiente/storeBloque',{
                'idestablecimiento':this.regEstablecimiento.idestablecimiento,
                'cantgrupos':this.arrayCantgrupos.join(),
                'nrgrupo':nrgrupo,
                'tarifasocio':this.tarifasocio*1,
                'fil':this.fil,
                'col':this.col
            }).then(function(){
                swal('Configuración guardada','','success');
                var url='/ser_establecimiento/listaEstablecimientos?idestablecimiento='
                    +me.regEstablecimiento.idestablecimiento;
                axios.get(url).then(function(response){
                    me.regEstablecimiento=response.data.establecimientos[0];
                    me.verBloque(nrgrupo);
                });
            });
        },
        // hasta aqui mausoleos hasta aqui mausoleos hasta aqui mausoleos


    },

}
</script>