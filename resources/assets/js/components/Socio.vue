<template>
<main class="main">
    <div class="breadcrumb titmodulo">Afiliaciones > Socios</div>
    <div class="container-fluid">
        <div class="card">
<!--
            <div class="card-header">
                <div class="row">
                    <div class="col-md-7 titcard">
                        <div class="tablatit">
                            <div class="tcelda">Listado de Socios</div>
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
-->




                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Socios
                        <!-- <template v-if="registrar">
                        <button type="button" @click="abrirModal('socio','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo Socio
                        </button>
                        </template> -->
                    </div> 
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!-- <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre">Nombre</option>
                                      <option value="apaterno">Ap. Paterno</option>
                                      <option value="amaterno">Ap. Materno</option>
                                      <option value="ci">CI</option>
                                      <option value="numpapeleta">Papeleta</option>
                                    </select> -->
                                    <input type="text" v-model="buscar" @keyup.enter="listarSocio(1,buscar)" class="form-control" placeholder="Ingrese Apellidos, Nombres, CI, o Papeleta">
                                    <button type="submit" @click="listarSocio(1,buscar)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead class="tcabecera">
                                <tr>
                                    <th>Opciones</th> 
                                    <th>Foto</th> 
                                    <th>Grado</th>                    
                                    <th>Nombre</th>
                                    <th>Ap. Paterno</th>
                                    <th>Ap. Materno</th>
                                    <th>No. Papeleta</th> 
                                    <th>C.I.</th>                                    
                                    <th>Estado</th>                     
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="socio in arraySocio" :key="socio.idsocio">                                   
                                    <td>                                   
											                                            
                                        <button v-if="check('borrar')" type="button" class="btn btn-danger btn-sm" @click="desactivarSocio(socio.idsocio)">
                                        <i class="icon-trash"></i>
                                        </button>&nbsp;
                                                                                                                                
                                        <button v-if="check('editar') || check('ver')" type="button" @click="abrirModal('socio','actualizar',socio)" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                        </button>&nbsp; 
                                                                                                                                
                                        <button v-if="check('cuentasocio')"  type="button" @click="abrirModalCuentasocio('cuentasocio','registrar',socio)" class="btn btn-warning btn-sm">
                                        <i class="icon-credit-card"></i>                                            
                                        </button>&nbsp;                                            
                                                                                    
                                        <button v-if="check('beneficiario')" type="button" @click="abrirModalBeneficiario('beneficiario','registrar',socio)" class="btn btn-warning btn-sm">
                                        <i class="icon-people"></i>                                            
                                        </button>&nbsp;
                                                                                                                                                                            
                                        <button v-if="check('impkardex')" type="button" @click="kardex_new(socio.idsocio, kardex_socio)" class="btn btn-warning btn-sm">
                                        <i class="icon-user"></i>
                                        </button>&nbsp; 
                                                                                                                                
                                        <button v-if="check('credencial')" type="button" @click="generarCarnetSocio(socio)" class="btn btn-warning btn-sm">
                                        <i class="icon-camera"></i>
                                        </button>  
                                                                                        
                                    </td>
									<td><img v-if="socio.rutafoto" :src="'img/socios/'+socio.rutafoto"  class="rounded-circle fotosociomini" alt="Cinque Terre">
										<img v-else :src="'img/socios/avatar.png'"  class="rounded-circle fotosociomini" alt="Cinque Terre" >
									</td> 
                                    <td v-text="socio.nomgrado"></td> 
                                    <td v-text="socio.nombre"></td>
                                    <td v-text="socio.apaterno"></td>
                                    <td v-text="socio.amaterno"></td>
                                    <td v-text="socio.numpapeleta"></td>
                                    <td v-text="socio.ci+' '+ socio.abrvdep"></td>
                                                                                                            
                                    <td>
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
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
        <div v-if="Modalview === 'R'">

        <form @submit.prevent="validateBeforeSubmit">
        <div class="modal fade" tabindex="-1" :class="{'mostrar':modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" class="close" @click="cerrarModal()" aria-label="Close"><span aria-hidden="true">x</span></button>
                </div>
                <div class="modal-body1">
                 <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" @submit.prevent="validateBeforeSubmit">
                    
        <!-- primera file-->
                        <h5 class="titazul">IDENTIDAD</h5>
                        <div class="form-group row">                            
                            <div class="col-md-4">
                                
                                <div class="formu-tabla">
                                <span class="formu-etiqueta">Nombres</span>
                                <input v-validate.initial="'required|alpha_spaces'"
                                    :class="{'input': true, 'is-invalid': errors.has('name') }" 
                                    type="text" 
                                    v-model="nombre" 
                                    class="form-control formu-entrada"
                                    placeholder="Nombres" 
                                    name="nombre"
                                     @keyup="generarcodsocio()">
                                    <!-- <span v-show="errors.has('nombre')" class="text-error">{{ errors.first('nombre')}}</span> -->
                                </div>
                                <p class="text-error">{{ errors.first('nombre')}}</p>

                                <div class="formu-tabla">
                                <span class="formu-etiqueta">Paterno</span>
                                <input v-validate.initial="'required|alpha_spaces'"
                                    :class="{'input': true, 'is-danger': errors.has('name') }" 
                                    type="text" 
                                    v-model="apaterno" 
                                    class="form-control formu-entrada" 
                                    placeholder="Ap. Paterno"
                                    name="apaterno"
                                     @keyup="generarcodsocio()">
                                </div>
                                <p class="text-error">{{ errors.first('apaterno')}}</p>
                                

                                <div class="formu-tabla">
                                <span class="formu-etiqueta">Materno</span>
                                <input v-validate.initial="'required|alpha_spaces'"
                                :class="{'input': true, 'is-danger': errors.has('name') }" 
                                    type="text" 
                                    v-model="amaterno" 
                                    class="form-control formu-entrada" 
                                    placeholder="Ap. Materno"
                                    name="amaterno"
                                    @keyup="generarcodsocio()">
                                </div>
                                <p class="text-error">{{ errors.first('amaterno')}}</p>
                               
                                <div class="formu-tabla">
                                <span class="formu-etiqueta">Ap. Esposo</span>
                                <input v-validate.initial="'alpha_spaces'"
                                    :class="{'input': true, 'is-danger': errors.has('name') }"
                                    type="text" 
                                    v-model="aesposo" 
                                    class="form-control formu-entrada" 
                                    placeholder="Ap. Esposo"
                                    name="aesposo">
                                    <!-- <span v-show="errors.has('aesposo')" class="text-error">{{ errors.first('aesposo')}}</span>  -->
                                </div>
                                <p class="text-error">{{ errors.first('aesposo')}}</p>

                                <div class="formu-tabla">
                                <span class="formu-etiqueta">Sexo</span>
                                <div class="formu-entrada">
                                    
                                <input v-validate.initial="'required'"
                                    type="radio" 
                                    id="varon" value="M" v-model="sexo"
                                      name="sexo" >
                                <label for="Masculino">Masculino</label>
                                <br>
                                <input  v-validate.inicial="'required'"
                                    type="radio" 
                                    id="mujer" value="F" v-model="sexo"
                                      name="sexo">                                    
                                <label for="Femenino">Femenino</label>
                                </div>                                                                
                                </div>
                                <p class="text-error">{{ errors.first('sexo')}}</p>
								
								<div class="formu-tabla">
                                <span class="formu-etiqueta">Fecha Nacimiento</span>
                                    <div id="box">
                                    <input v-validate.initial="'required'" class="form-control formu-entrada" 
                                    type="date" v-model="fechanacimiento" placeholder="Fecha Nacimiento" name="FecNac">                                    
                                    <!-- <p class="text-error">{{ errors.first('sexo')}}</p>   -->
                                    </div>                                    
                                </div>
                                <p class="text-error">{{ errors.first('FecNac')}}</p>
                            </div>

                            <div class="col-md-4">
                                <div class="formu-tabla">
                                <span class="formu-etiqueta">Cédula Nro</span>
                                 <input v-validate.initial="'required'" :class="{'input': true, 'is-danger': errors.has('CI') }"
                                    type="text" 
                                    v-model="ci" 
                                    class="form-control formu-entrada" 
                                    placeholder="C.I."
                                    name="CI"
                                   @keyup="generarcodsocio()">
                                <!-- <span v-show="errors.has('CI')" class="text-error">{{ errors.first('CI')}}</span> -->
                                </div>
                                <p class="text-error">{{ errors.first('CI')}}</p>  
                                

                                <div class="formu-tabla">
									<span class="formu-etiqueta">Expedido</span>
									<select 
										type="text"  
										v-model="iddepartamentoexpedido"
										v-validate.initial="'required'" 
										placeholder="Departamento"                                               
										name="Departamento" required
										:class="{'form-control formu-entrada': true, 'error': errors.has('Departamento')}" @change="onChangeDep(iddepartamentoexpedido)">
										<option selected="selected" value="" disabled >departamento...</option>
										<option v-for="departamento in arrayDepartamento" :key="departamento.iddepartamento" :value="departamento.iddepartamento" v-text="departamento.nomdepartamento"></option>
									</select>                                
                                </div>
                                <p  class="text-error">{{ errors.first('Departamento') }}</p>  
                                  
                                <!-- <p class="text-error">&nbsp;</p> -->
 

                                <div class="formu-tabla">
                                <span class="formu-etiqueta">Lugar Nacimiento</span>
                                																							
                                <div v-if="arrayMunicipio.length > 0">   
                                        <select class="form-control formu-entrada" type="text" 
                                            v-model="idmunicipionacimiento" placeholder="Municipio" 
                                            v-validate.initial="'required'"
                                            name="Municipio" required
                                            :class="{'form-control formu-entrada': true, 'error': errors.has('Municipio')}" >
                                            <option selected="selected" value="" disabled>Municipio...</option>
                                            
                                                <option v-for="municipio in arrayMunicipio" :key="municipio.idmunicipio" :value="municipio.idmunicipio" v-text="municipio.nommunicipio"> </option> 
                                            
                                            </select>
                                </div>
                                <div v-else> 
                                        <select class="form-control formu-entrada" type="text" 
                                            v-model="idmunicipionacimiento" placeholder="Municipio" 
                                            v-validate.initial="'required'"
                                            name="Municipio" required
                                            :class="{'form-control formu-entrada': true, 'error': errors.has('Municipio')}">
                                            <option selected="selected" value="" disabled>Municipio...</option>
                                            </select>
                                </div> 																 								
                                </div>
                                <p  class="text-error">{{ errors.first('Municipio') }}</p> 
                                
                                <div class="formu-tabla">
                                <span class="formu-etiqueta">Estado Civil</span>
                                <select class="form-control formu-entrada"
                                    type="text"                                    
                                    v-model="idestadocivil" placeholder="Estadocivil" 
                                    v-validate.initial="'required'"                                    
                                    name="Estadocivil" required
                                     :class="{'form-control formu-entrada': true, 'error': errors.has('Estadocivil')}">
                                    <option selected="selected" value="" disabled >Estado Civil...</option>
                                    <option v-for="estadocivil in arrayEstadocivil" :key="estadocivil.idestadocivil" :value="estadocivil.idestadocivil" v-text="estadocivil.nomestadocivil"></option>
                                </select>                                
                            </div> 
                            <p class="text-error">{{ errors.first('Estadocivil') }}</p>                               
                               <div class="formu-tabla">
                                    <span class="formu-etiqueta" style="width:35%">Cod Socio</span>
                                    <input v-validate.initial="'required'"
                                        type="text" 
                                        v-model="codsocio" 
                                        class="form-control formu-entrada formu-codsocio" disabled                                        
                                        name="CodSocio">
                                </div>
								<p></p>								
                            </div>

                            <div class="col-md-4">
                                           
                               <div style="vertical-align: middle;">											
										<center style="vertical-align: middle;">  
											<template>
											 
												<div v-if="image">   
                                                    <div v-if="success">   
                                                            <img :src="image" id="imgC"  class="fotosocio" >		
                                                            <input  accept="image/*" id="fileInputSocio" type="file" v-on:change="onFileChange" class="form-control" style="display:none">
                                                            <button style="width:89%" type="button" class="btn btn-primary" onclick="document.getElementById('fileInputSocio').click()" >Fotografia</button>
                                                            <div class="formu-tabla" style="display:none"> 
                                                            <input :class="{'input': true, 'is-danger': errors.has('name') }"  type="text"  v-model="fotografia"  name="fotografia" >
                                                            </div>
                                                            <p class="text-error">{{ errors.first('fotografia')}}</p>
                                                
                                                    </div>
                                                    <div v-else> 
                                                            <img :src="image" id="imgC"  class="fotosocio" v-on:load="cambio"> 
                                                            <div style="padding: 9px;"><button  type="button" class="btn btn-success" @click="saveFoto()">Seleccionar</button> 
                                                            <button  type="button" class="btn btn-danger" @click="cancelSaveFoto()">Cancelar</button></div>	 															
                                                            <div class="formu-tabla" style="display:none"> 
                                                            <input :class="{'input': true, 'is-danger': errors.has('name') }"  type="text"  v-model="fotografia"  name="fotografia" >
                                                            </div>
                                                            <p class="text-error">{{ errors.first('fotografia')}}</p>                                                    
                                                    </div> 												 
												</div>
												<div v-else> 															 															
                                                    <img v-if="rutafoto" :src="'img/socios/'+rutafoto"   class="fotosocio">
                                                    <img v-else :src="'img/socios/avatar.png'"  class="fotosocio">		
                                                    <input  accept="image/*" id="fileInputSocio" type="file" v-on:change="onFileChange" class="form-control" style="display:none">
                                                    
                                                    <button style="width:89%" type="button" class="btn btn-primary" onclick="document.getElementById('fileInputSocio').click()" >Fotografia</button>
                                                    
                                                    <div class="formu-tabla" style="display:none"> 
                                                    <!-- <input v-validate.initial="'required'" :class="{'input': true, 'is-danger': errors.has('name') }"  type="text"  v-model="fotografia"  name="fotografia" > -->
                                                    <input :class="{'input': true, 'is-danger': errors.has('name') }"  type="text"  v-model="fotografia"  name="fotografia" >
                                                    </div>
                                                    <p class="text-error">{{ errors.first('fotografia')}}</p>															
												</div>                                                                                                                    											     												 												 
											</template> 
										</center>																				
							    </div>
                            </div>                            
                                
                        </div>
        <!-- segundo bloque-->

                        <h5 class="titazul">INSTITUCIONAL</h5>
                        <div class="form-group row">
                            <div class="col-md-4">
                                
								<div class="formu-tabla">
                                <span class="formu-etiqueta">Fuerza</span> 
								<select class="form-control formu-entrada" type="text"
                                    v-model="idfuerza" placeholder="Fuerza" 
                                    v-validate.initial="'required'"
                                    name="Fuerza" required
                                    :class="{'form-control formu-entrada': true, 'error': errors.has('Fuerza')}" @change="onChangeFuerza(idfuerza)" disabled>
                                    <option selected="selected" value="" disabled>Fuerza...</option>
                                    <option v-for="fuerza in arrayFuerza" :key="fuerza.idfuerza" :value="fuerza.idfuerza" v-text="fuerza.nomfuerza"></option>
                                </select> 
                                </div>
                                <p v-show="errors.has('Fuerza')" class="text-error">{{ errors.first('Fuerza') }}</p> 
                                <p></p>
								
                                <div class="formu-tabla">
                                <span class="formu-etiqueta">Especialidad</span>
                                   <div v-if="arrayEspecialidad.length > 0">   
											<select class="form-control formu-entrada" type="text" 
											v-model="idespecialidad" placeholder="Grado" 
											v-validate.initial="'required'"
											name="Especialidad" required
											:class="{'form-control formu-entrada': true, 'error': errors.has('Especialidad')}">
											<option selected="selected" value="" disabled>Especialidad...</option>
											<option v-for="especialidad in arrayEspecialidad" :key="especialidad.idespecialidad" :value="especialidad.idespecialidad" v-text="especialidad.nomespecialidad"></option>                                
											 </select>
									
									</div>
									<div v-else> 
											<select class="form-control formu-entrada" type="text" 
											v-model="idespecialidad" placeholder="Grado" 
											v-validate.initial="'required'"
											name="Especialidad" required
											:class="{'form-control formu-entrada': true, 'error': errors.has('Especialidad')}">
											<option selected="selected" value="" disabled>Especialidad...</option>
											</select> 
									</div> 
									  
                                </div>
                                <p v-show="errors.has('Especialidad')" class="text-error">{{ errors.first('Especialidad') }}</p> 
	                            <p></p> 

								<div class="formu-tabla">
                                <span class="formu-etiqueta">Destino</span> 
								 
									<div v-if="arrayDestino.length > 0">   
											<select class="form-control formu-entrada" type="text"
											v-model="iddestino" placeholder="Destino" 
											v-validate.initial="'required'"
											name="Destino" required
											:class="{'form-control formu-entrada': true, 'error': errors.has('Destino')}">
											<option selected="selected" value="" disabled>Destino...</option>
											<option v-for="destino in arrayDestino" :key="destino.iddestino" :value="destino.iddestino" v-text="destino.nomdestino"></option>
											 </select> 
									
									</div>
									<div v-else> 
											<select class="form-control formu-entrada" type="text"
											v-model="iddestino" placeholder="Destino" 
											v-validate.initial="'required'"
											name="Destino" required
											:class="{'form-control formu-entrada': true, 'error': errors.has('Destino')}">
											<option selected="selected" value="" disabled>Destino...</option>
											</select> 
									</div> 
									 
                               
                                </div>
                                <p v-show="errors.has('Destino')" class="text-error">{{ errors.first('Destino') }}</p> 
                              <p></p> 
							  <div class="formu-tabla">
                                <span class="formu-etiqueta">Grado</span>
                                <select class="form-control formu-entrada" type="text" 
                                    v-model="idgrado" placeholder="Grado" 
                                    v-validate.initial="'required'"
                                    name="Grado" required
                                    :class="{'form-control formu-entrada': true, 'error': errors.has('Grado')}" disabled>
                                    <option selected="selected" value="" disabled>Grado...</option>
                                    <option v-for="grado in arrayGrado" :key="grado.idgrado" :value="grado.idgrado" v-text="grado.nomgrado"></option>
                                </select>
                                </div>
                                <p v-show="errors.has('Grado')" class="text-error">{{ errors.first('Grado') }}</p> 
	                            
								 
                            </div>  
                              
                              
                              <div class="col-md-4">  
                               
								
                                <div class="formu-tabla">
                                <span class="formu-etiqueta">Tipo Socio</span>
                                <select class="form-control formu-entrada" type="text" 
                                    v-model="idtiposocio" placeholder="Tiposocio" 
                                    v-validate.initial="'required'"
                                    name="Tiposocio" required
                                    :class="{'form-control formu-entrada': true, 'error': errors.has('Tiposocio')}">
                                     <option selected="selected" value="" disabled>Tiposocio...</option>
                                    <option v-for="tiposocio in arrayTiposocio" :key="tiposocio.idtiposocio" :value="tiposocio.idtiposocio" v-text="tiposocio.nomtiposocio"></option>
                                </select>
                                </div>
                                <p v-show="errors.has('Tiposocio')" class="text-error">{{ errors.first('Tiposocio') }}</p>  
								<p></p> 
								

                                <div class="formu-tabla">
                                <span class="formu-etiqueta">Escalafon...</span>
                                <select class="form-control formu-entrada" type="text"
                                    v-model="idescalafon" placeholder="Escalafon" 
                                    v-validate.initial="'required'"
                                    name="Escalafon" required
                                    :class="{'form-control formu-entrada': true, 'error': errors.has('Escalafon')}" disabled>
                                    <option selected="selected" value="" disabled>Escalafon...</option>
                                    <option v-for="escalafon in arrayEscalafon" :key="escalafon.idescalafon" :value="escalafon.idescalafon" v-text="escalafon.nomescalafon"></option>
                                </select>
                                </div>
                                <p v-show="errors.has('Escalafon')" class="text-error">{{ errors.first('Escalafon') }}</p>                                 
                                <p></p> 

                                <div class="formu-tabla">
                                <span class="formu-etiqueta">Fecha Egreso</span>
                                    <div id="box">
                                    <input v-validate.initial="'required'" class="form-control formu-entrada" 
                                    type="date" v-model="fechaegreso" placeholder="Fecha Egreso" name="FecEgr">
                                    </div>                                    
                                </div>
                                <p class="text-error">{{ errors.first('FecEgr')}}</p>
                                <p></p> 
                               <div class="formu-tabla">
                                <span class="formu-etiqueta">Fecha Incorporacion</span>
                                    <div id="box">
                                    <input v-validate.initial="'required'" class="form-control formu-entrada" disabled
                                    type="date" v-model="fechaincorporacion" placeholder="Fecha Incorporacion" name="FecInc">                                    
                                    </div>                                    
                                </div>
                                <p class="text-error">{{ errors.first('FecInc')}}</p>
                            </div>

                            <div class="col-md-4">
                                <div class="formu-tabla">
                                <span class="formu-etiqueta" style="width:45%">Num Papeleta</span>
                                <input v-validate.initial="'required'"
                                    type="text" 
                                    v-model="numpapeleta" 
                                    class="form-control form-entrada" disabled
                                    placeholder="Papeleta"
                                    name="Papeleta">
                                </div>
                                <p class="text-error">{{ errors.first('Papeleta')}}</p>
								<p></p>
                                <div class="formu-tabla">
                                <span class="formu-etiqueta" style="width:45%">Carnet Militar</span>
                                <input v-validate.initial="'required|alpha_dash'" :class="{'input': true, 'is-danger': errors.has('carnetmilitar') }"
                                    type="text" 
                                    v-model="carnetmilitar" 
                                    class="form-control form-entrada" 
                                    placeholder="C.I. Militar"
                                    name="CiMilitar"
                                    @keyup="generarcodsocio()">
                                </div>
                                <p class="text-error">{{ errors.first('CiMilitar')}}</p>
                                <div class="formu-tabla">
                                <span class="formu-etiqueta">Fecha Inscripcion</span>
                                    <div id="box">
                                    <input v-validate.initial="'required'" class="form-control formu-entrada" 
                                    type="date" v-model="fechainscripcion" placeholder="Fecha Inscripcion" name="FecIns">
                                    </div>                                    
                                </div>
                                <p class="text-error">{{ errors.first('FecIns')}}</p>
								<p></p>
                                

                            </div>
                        </div>     

            <!-- tercer bloque-->
                        <h5 class="titazul">CONTACTO</h5>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <div class="formu-tabla">
                                <span class="formu-etiqueta">Tel. Fijo</span>
                                <input 
                                    type="text" 
                                    v-model="telfijo" 
                                    class="form-control formu-entrada" 
                                    placeholder="Telefono fijo"
                                    name="TelFijo"
                                    v-validate="'numeric'" :class="{'input': true, 'is-danger': errors.has('telFijo') }">
                                </div>
                                <p class="text-error">{{ errors.first('TelFijo')}}</p>

                                <div class="formu-tabla">
                                <span class="formu-etiqueta">Tel. Celular</span>
                                <input 
                                    type="text" 
                                    v-model="telcelular" 
                                    class="form-control formu-entrada" 
                                    placeholder="Telefono Celular"
                                    name="TelCelular"
                                    v-validate="'numeric'" :class="{'input': true, 'is-danger': errors.has('telCelular') }">
                                </div>
                                <p class="text-error">{{ errors.first('TelCelular')}}</p>

                            </div>

                            <div class="col-md-8">
                                <div class="formu-tabla">
                                <span class="formu-etiqueta"  style="width:15%">Direccion</span>
                                <input 
                                    type="text" 
                                    v-model="direcciondomicilio" 
                                    class="form-control formu-entrada" 
                                    placeholder="Direccion."
                                    name="Direccion">
                                </div>
                                <p class="text-error">{{ errors.first('Direccion')}}</p>

                                <div class="formu-tabla">
                                <span class="formu-etiqueta" style="width:15%">Email</span>
                                <input v-validate.initial="'email'" :class="{'input': true, 'is-danger': errors.has('email') }"
                                    type="text" 
                                    v-model="email" 
                                    class="form-control formu-entrada"
                                    placeholder="Email"
                                    name="email">
                                </div>
                                <p class="text-error">{{ errors.first('email')}}</p>

                            </div>
                        </div>

        <!-- cuarto bloque-->

                        <h5 class="titazul">SISTEMA</h5>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <div class="formu-tabla">
                                <span class="formu-etiqueta">Estado</span>
                                <select class="form-control formu-entrada" type="text"  
                                    v-model="idestado" placeholder="Estado"
                                    v-validate.initial="'required'"
                                    name="Estado" 
                                    :class="{'form-control formu-entrada': true, 'error': errors.has('Estado')}" >
                                    <option selected="selected" value="" disabled>Estado...</option>
                                    <option v-for="estado in arrayEstado" :key="estado.idestadomodulo" :value="estado.idestadomodulo" v-text="estado.nomestado"></option>
                                </select>
                                
                                </div>
                                <p v-show="errors.has('Estado')" class="text-error">{{ errors.first('Estado') }}</p> 
                            </div>
                        </div>    
                        <div class="form-group row">
                            <div class="col-md-12" >
                                <div class="formu-tabla">
                                <span class="formu-etiqueta" style="width:10%">Observaciones</span>
                                <input 
                                    type="text"                                     
                                    v-model="observaciones" 
                                    class="form-control formu-entrada"                                     
                                    placeholder="Obs"
                                    name="Obs">                                
                                </div>
                                <p class="text-error">{{ errors.first('Obs')}}</p>
                            </div>
                        </div> 

                 </form>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>

                            <button id="BmodalSocio" :disabled = "errors.any()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarsocio()">Guardar</button>
                            <button id="BmodalSocio" :disabled = "errors.any()" type="button" v-if="tipoAccion==2 && check('editar')" class="btn btn-primary" @click="actualizarFotosocio()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
         </form>

</div>
<div v-else-if="Modalview === 'B'">
        <!--Inicio del listar beneficiario-->
            <form @submit.prevent="validateBeforeSubmit"> 
            <div class="modal fade" tabindex="-1" :class="{'mostrar':modalBeneficiario}" >
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title">Registro de Beneficiarios</h4>
                    <button type="button" class="close" @click="cerrarModalBeneficiario()" ><span aria-hidden="true">x</span></button>
                </div>
                <div class="modal-body">
                 <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" @submit.prevent="validateBeforeSubmitBeneficiario">                                        
                        <div class="col-md-12 text-center">
                            <h5 v-text="'SOCIO: '+nombrecompleto"></h5>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead class="tcabecera">
                                <tr>
                                    <th></th>
                                    <th>Nombre Completo</th>
                                    <th>Parentesco</th>
                                    <th>C.I.</th>
                                    <th>Fecha Nacim.</th>
                                    <th>Celular</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="beneficiario in arrayBeneficiarios" :key="beneficiario.idbeneficiario" :class="beneficiario.activo?'':'txtdesactivado'">
                                    <td align="center">
                                        <button type="button" class="btn btn-warning btn-sm icon-pencil" @click="abrirModalBeneficiario('beneficiario','actualizar',beneficiario)">
                                        </button>
                                        <template v-if="beneficiario.activo">
                                            <button type="button" class="btn btn-danger btn-sm icon-trash" @click="desactivarBeneficiario(beneficiario)"></button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-warning btn-sm icon-action-redo" @click="activarBeneficiario(beneficiario)"></button>
                                        </template>                                                                               
                                    </td>
                                    <td> {{beneficiario.nombre}} {{beneficiario.apaterno}} {{beneficiario.amaterno}} </td>
                                    <td v-text="beneficiario.parentesco"></td>
                                    <td v-text="beneficiario.ci" align="center"></td>
                                    <td v-text="beneficiario.fechanac" align="center"></td>
                                    <td v-text="beneficiario.telcelular" align="center"></td>
                                </tr>                                
                            </tbody>
                        </table>


                        <h4 class="titsubrayado" v-text="tituloSeccion"></h4>
                        <div class="row" >
                            <div class="col-md-4" >
                                <table>
                                    <tr>
                                        <td>Nombre:</td>
                                        <td><input type="text" name="nom" class="form-control" v-model="nombre"  v-validate.initial="'required'"> </td>
                                    </tr>
                                    <span class="text-error">{{ errors.first('nom')}}</span>
                                    <tr>
                                        <td>Paterno:</td>
                                        <td> <input type="text" name="pat" class="form-control" v-model="apaterno"  v-validate.initial="'required'"> </td>
                                    </tr>
                                    <span class="text-error">{{ errors.first('pat')}}</span>
                                    <tr>
                                        <td>Materno:</td>
                                        <td> <input type="text" class="form-control" v-model="amaterno"> </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4" >
                                <table>
                                    <tr>
                                        <td nowrap>Parentesco:</td>
                                        <td><select class="form-control" name="paren" v-model="parentesco" v-validate.initial="'required'">
                                            <option value=''>--Seleccione--</option>
                                            <option value='Espos@'>Espos@</option>
                                            <option value='Hij@'>Hij@</option>
                                            <option value='Herman@'>Herman@</option>
                                            <option value='Padre'>Padre</option>
                                            <option value='Madre'>Madre</option>
                                            <option value='Otro'>Otro</option>                                            
                                            </select></td>
                                    </tr>
                                        <span class="text-error">{{ errors.first('paren')}}</span>
                                    <tr>
                                        <td>Nro C.I. :</td>
                                        <td><input type="text" name="ci1" class="form-control" v-model="ci" v-validate.initial="'required|numeric'"> </td>
                                    </tr>
                                        <span class="text-error">{{ errors.first('ci1')}}</span>
                                    <tr>
                                        <td>Expedido :</td>
                                        <td><select class="form-control" name="expe" v-model="iddepartamentoexpedido"  v-validate.initial="'required'">
                                                <option value=''>--Seleccione--</option>
                                                <option v-for="departamento in arrayDepartamento" :key="departamento.iddepartamento"
                                                :value="departamento.iddepartamento" v-text="departamento.nomdepartamento"></option>
                                            </select>
                                        </td>
                                    </tr>
                                        <span class="text-error">{{ errors.first('expe')}}</span>
                                    <!-- <tr>
                                        <td>Sexo</td>
                                        <td><input type="radio" name="sexo" value="m" v-model="sexo">Masculino
                                            <input type="radio" name="sexo" value="f" v-model="sexo">Femenino
                                        </td>
                                    </tr> -->

                                </table>
                            </div>
                            <div class="col-md-4" >
                                <table >
                                    <tr>
                                        <td nowrap>Fecha Nac.:</td>
                                        <td><input type="date" name="fecnac11" class="form-control" v-model="fechanacimiento" style="width:150px"> </td>
                                    </tr>
                                    <span class="text-error">{{ errors.first('fecnac1')}}</span>
                                    <tr>
                                        <td>Celular:</td> 
                                        <td><input type="text" class="form-control" v-model="telcelular" > </td> 
                                    </tr>
                                </table>
                            </div>
                        </div>                       

                </form> 
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModalBeneficiario()">Cerrar</button>
                            <!-- :disabled = "errors.any()" -->
                            <button :disabled = "errors.any()"  type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarBeneficiario()">Guardar</button>
                            <button :disabled = "errors.any()"  type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarBeneficiario()">Actualizar</button>
                        </div>
                    </div>                    
                </div>                
            </div> 
            <!--Fin del modal-->
            </form>

</div>
<div v-else-if="Modalview === 'C'">

             <!--Inicio del listar cuentasocios-->
        <form @submit.prevent="validateBeforeSubmit"> 
        <div class="modal fade" tabindex="-1" :class="{'mostrar':modalCuentasocio}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModalCuentasocio"></h4>
                    <button type="button" class="close" @click="cerrarModalCuentasocio()" aria-label="Close"><span aria-hidden="true">x</span></button>
                </div>
                <div class="modal-body">
                 <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" @submit.prevent="validateBeforeSubmitCuentasocio">
                    
                    
                        <div class="col-md-12">
                        <label><h5>Numeros de Cuenta Socio: {{ nombre| capitalize}} {{ apaterno|capitalize }}  {{ amaterno|capitalize }} </h5></label>
                        
                        </div>

                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Editar</th>
                                    <th>Banco</th>                     
                                    <th>Numero de Cuenta</th>
                                    <th>Estado</th>                                                                                                       
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="cuentasocio in arrayCuentasocio" :key="cuentasocio.idcuentasocio">
                                    <td>
                                        <button type="button" @click="abrirModalCuentasocio('cuentasocio','actualizar',cuentasocio)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button>&nbsp;
                                        <template v-if="cuentasocio.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarCuentasocio(cuentasocio.idcuentasocio,cuentasocio.idsocio)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarCuentasocio(cuentasocio.idcuentasocio,cuentasocio.idsocio)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                                                               
                                    </td>
                                    <td v-text="cuentasocio.nomentidadbancaria"></td>
                                    <td v-text="cuentasocio.numcuentasocio"></td>
                                    <td>
                                        <div v-if="cuentasocio.activo">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                        
                                    </td>
                                    
                                </tr>                                
                            </tbody>
                        </table>

                        <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Banco</label>
                                    <div class="col-md-9">
                                        <select class="form-control" 
                                                v-model="identidadbancaria">
                                            <option value="0">Seleccione</option>
                                            <option v-for="entidadbancaria in arrayEntidadbancaria" :key="entidadbancaria.identidadbancaria" :value="entidadbancaria.identidadbancaria" v-text="entidadbancaria.nomentidadbancaria"></option>
                                        </select> 
                                    </div>
                                </div>
                                                          
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Numero de Cuenta</label>
                                    <div class="col-md-9">
                                        <input 
                                              v-validate.initial="'required'"
                                                type="text"                                                 
                                                v-model="numcuentasocio" 
                                                class="form-control formu-entrada" 
                                                placeholder="Numero de Cuenta"
                                                name="Numero de cuenta"
                                                autofocus>  <!--
                                                     v-validate.initial="'required'" :class="{'input': true, 'is-danger': errors.has('numcuentasocio') }"
                                                    v-validate.immediate="'required'" -->

                                     <span class="text-error">{{ errors.first('Numero de cuenta')}}</span>                                     
                                    
                                    </div>
                                </div>
                </form> 
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModalCuentasocio()">Cerrar</button>
                            <!-- :disabled = "errors.any()" -->
                            <button :disabled = "errors.any()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarCuentasocio()">Guardar</button>
                            <button :disabled = "errors.any()" type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarCuentasocio()">Actualizar</button>
                        </div>
                    </div>                    
                </div>                
            </div> 
            <!--Fin del modal-->
            </form>

</div>
      







   <div class="modal fade" tabindex="-1" role="dialog" style="z-index: 1600;" aria-hidden="true" id="credencial">
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content animated fadeIn">
          <div class="modal-header">
            <h4 class="modal-title" id="modalOneLabel">Carnet del socio</h4>
            <button type="button" class="close" aria-hidden="true" aria-label="Close"
              @click="classModal.closeModal('credencial')">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body-plandepagos">
            <!--  <div style="display:none" v-html="plandepagosSimulacion"></div>-->
            <iframe  name="planout" id="planout" :style="printcredencial==0?'width:100%;height:100%;':'display:none;'"></iframe> 
            <iframe  name="2planout" id="2planout" :style="printcredencial==1?'width:100%;height:100%;':'display:none;'"></iframe>
          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" type="button"
              @click="classModal.closeModal('credencial')">cerrar</button>
             <button v-if="printcredencial==0" class="btn btn-primary" type="button" @click="generarCarnetSocioAtraz()">Siguiente</button> 
          </div>
        </div>
      </div>
    </div>
</main>

</template>

<script>
    //import Vue from 'vue';
    import VeeValidate from 'vee-validate';
    import * as repo from '../functions.js';
    
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

    var box = new Vue({
       
    data:{
        form:{
        date2 : '2017-07-04'
        }
        }
    });

    Vue.filter('capitalize', function (value) {
        if (!value) return ''
        value = value.toString()
        return value.charAt(0).toUpperCase() + value.slice(1)
        })
    
    export default {
        props:['idmodulo','idventanamodulo'],
        data (){
            return { 
                printcredencial:0,
                Modalview:'',
                kardex_socio: '', 
                canet_socio_Re: '', 
                socio_id:0,
                numpapeleta:'',
                codsocio:'',
                nombre:'',
                apaterno:'',
                amaterno:'',
                aesposo:'',                
                sexo:'',
                carnetmilitar:'',
                ci:'',
               // cisocio:'',
                iddepartamento: 0,
                nomdepartamento:'',
                iddepartamentoexpedido: '',
                fechanacimiento:'',
                fechaegreso:'',
                fechaincorporacion:'',
                fechainscripcion:'',
                idmunicipionacimiento:'',
                idgrado:'',
                nomgrado:'',
                iddestino:'',                
                idfuerza:'',                
                idescalafon:'',
                nomescalafon:'',
                idespecialidad:'',
                nomespecialidad:'',
                nommunicipio:'',                   
                idestadocivil:'',
                nomestadocivil:'',
                idestado:'',
                nomestado:'',
                direcciondomicilio:'',
                telfijo:'',
                telcelular:'',
                email:'',
                observaciones:'',
                activo:'',
                carnetestado:'',
              //  bloqueo:'',
                rutafoto:'',  image:'',fotografia:'', success: '',
                idusuarioregistro:'',
                idusuariomodificacion:'',
                arrayRol : [],
                modal : 0, modal_foto : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorsocio : 0,
                errorMostrarMsjsocio : [],
                nombrecompleto:'',
                arrayEntidadbancaria:[],
                identidadbancaria:0,
                entidadbancaria_id:0,
                nomentidadbancaria:'',
                numcuentasocio:'',
                cuentasocio_id:0,                
                modalCuentasocio:0,                
                arrayCuentasocio:[],
                arrayBeneficiarios:[],
               
                //datos beneficiario
                modalBeneficiario:0,
                beneficiario_id:0,                
                tituloModalBeneficiario :'',
                tituloSeccion:'',
                parentesco  : '',
                nombeneficiario  : '',
                cibeneficiario  : '',
                fecnacbeneficiario  : '',

                tituloModalCuentasocio:'',
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombre',
                buscar : '',
                
                arraySocio :[],
                arrayEstadocivil :[],
                arrayFuerza :[],
                arrayEscalafon :[],
                arrayDepartamento :[],
                arrayGrado :[],
                arrayEspecialidad :[],
                arrayMunicipio :[],
                arrayDestino :[], 
                arrayEstado :[],
                
                arrayTiposocio: [],
                idtiposocio:'',
                nomtiposocio:'',
                //para los permisos, por defecto desactivados
                arrayPermisos : {ver:0,editar:0,borrar:0,credencial:0,impkardex:0,beneficiario:0,cuentasocio:0},
                arrayPermisosIn:[]

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
            },
        },


        methods : { 
		 abrirVentanaModalURL(url, title, w, h) { 
					var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : window.screenX;
					var dualScreenTop = window.screenTop != undefined ? window.screenTop : window.screenY; 
					var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
					var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height; 
					var left = ((width / 2) - (w / 2)) + dualScreenLeft;
					var top = ((height / 2) - (h / 2)) + dualScreenTop;
					var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
 
					if (window.focus) {
						newWindow.focus();
					}
				}
				,
		    getRutasReports (){ 
                let me=this;
                var url= '/afi_reportes';
                axios.get(url).then(function (response) {
                     var respuesta= response.data; ;
                    me.kardex_socio = respuesta.RUTE_REPORT; 
                    me.canet_socio_Re = respuesta.REP_CARNET; 
                })
                .catch(function (error) {
                    console.log(error);
                });
            },          
			onChangeFuerza(e){ 
			   let me=this;
                var url= '/par_destino/selectDestino?idfuerza=' + e;
                axios.get(url).then(function (response) {
                    me.iddestino='';
                    var respuesta= response.data;
                    me.arrayDestino = respuesta.destinos; 
                })
                .catch(function (error) {
                    console.log(error);
                });
				 
                var url= '/par_especialidad/selectEspecialidad?idfuerza=' + e;
                axios.get(url).then(function (response) { 
				    me.idespecialidad='';
                    var respuesta= response.data;
                    me.arrayEspecialidad = respuesta.especialidads;
                })
                .catch(function (error) {
                    console.log(error);
                });												
			},
			
			onChangeDep(e){ 
			   let me=this;
                var url= '/par_municipio/selectMunicipio?iddepartamento=' + e;
                axios.get(url).then(function (response) { 
				    me.idmunicipionacimiento='';
                    var respuesta= response.data;
                    me.arrayMunicipio = respuesta.municipios;
                })
                .catch(function (error) {
                    console.log(error);
                });
			},
			updateDep(idDep,numm){ 
			   let me=this;
                var url= '/par_municipio/selectMunicipio?iddepartamento=' + idDep;
                axios.get(url).then(function (response) { 
                    var respuesta= response.data;
                    me.arrayMunicipio = respuesta.municipios; 
					me.idmunicipionacimiento =numm;
                })
                .catch(function (error) {
                    console.log(error);
                });
			},
			updateFuerza(idfuerzaa,iddestinoo,idespe){ 
			   let me=this;
                var url= '/par_destino/selectDestino?idfuerza=' + idfuerzaa;
                axios.get(url).then(function (response) {
                    
                    var respuesta= response.data;
                    me.arrayDestino = respuesta.destinos;
                    me.iddestino=iddestinoo;					
                })
                .catch(function (error) {
                    console.log(error);
                });
				 
                var url= '/par_especialidad/selectEspecialidad?idfuerza=' + idfuerzaa;
                axios.get(url).then(function (response) {  
                    var respuesta= response.data;
                    me.arrayEspecialidad = respuesta.especialidads;
					me.idespecialidad=idespe;
                })
                .catch(function (error) {
                    console.log(error);
                });												
			},
			 			
            onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                this.createImage(files[0]);
                // console.log(this.image);                             
            },

            createImage(file) { 			
                if(!file.type.match('image.*')){
                console.log('${file.name} is not an image');
                alert("Incorrecto...!, debe seleccionar solo Imágen:");
               return;
				}else{
				let reader = new FileReader();
					let vm = this;
					vm.success=''; 
					vm.fotografia=vm.rutafoto;
					
					reader.onload = (e) => {
					vm.image = e.target.result; 
					};
					reader.readAsDataURL(file);
			 	}                               
            }, 
			cambio(){ 
			let vm = this;
			if(vm.tipoAccion==2){$("#BmodalSocio").attr("disabled","disabled");}
			
            var canvas = $("#imgC").cropper({
			            aspectRatio: 1/1,
					    viewMode: 3,
					    dragMode: 'move',
						autoCropArea: 1,
						restore: false, 
						guides: false, 
						rotatable: false,
					    multiple: false
					});            
			},
			saveFoto(){
			let vm = this;
			vm.success='ok'; 
			         if(vm.tipoAccion==2){$("#BmodalSocio").removeAttr('disabled');}
			
					 var canvas = $("#imgC").cropper('getCroppedCanvas').toDataURL(); 
					 vm.image=canvas; 
					 vm.fotografia=canvas; 
					 $('#imgC').cropper('destroy');
			}
			,
			cancelSaveFoto(){
			let vm = this;
			vm.success='';
			vm.image='';
			vm.fotografia=vm.rutafoto;
			if(vm.tipoAccion==2){$("#BmodalSocio").removeAttr('disabled');}
			     			
			},               
            //metodo agregado para la validacion
            
            validateBeforeSubmitCuentasocio() {
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
          

            validateBeforeSubmitBeneficiario() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                    return;
                    }                    
                    return;
                });
            },
            
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

            listarSocio (page,buscar){ 
                let me=this;
                var url= '/socio?page=' + page + '&buscar='+ buscar;
                axios.get(url).then(function (response) {
                    var respuesta= response.data; 
                    me.arraySocio = respuesta.socios.data;                     
                        for (var i=0; i<(respuesta.escalafones_permisos.data.length); i++) {
                            switch(respuesta.escalafones_permisos.data[i].metodo) {
                                case 'editar' : me.editar=1; break;
                                case 'registrar' : me.registrar=1; break;
                                case 'borrar' : me.borrar=1; break;
                                case 'credencial' : me.credencial=1; break;
                                case 'impkardex' : me.impkardex=1; break;
                                case 'beneficiario' : me.beneficiario=1; break;
                                case 'cuentasocio' : me.cuentasocio=1; break;
                            }
                        }                     
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            listarCuentasocio (page,idsocio){ 
                let me=this;
                var url= '/con_cuentasocio?page=' + page + '&idsocio='+ idsocio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data; ;
                    me.arrayCuentasocio = respuesta.cuentasocios.data; 
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },


            listaBeneficiarios (idsocio){ 
                let me=this;
                var url= '/afi_beneficiario?idsocio='+ idsocio;
                axios.get(url).then(function (response) {
                    me.arrayBeneficiarios = response.data.beneficiarios;
                });
            },


            kardex(id, kardex_socio) {
                //var url=dir_reportes + this.file_reporte +id;
                var url=kardex_socio + id; 
                this.abrirVentanaModalURL(url,"Kardex socio",800,700);				
            },

            kardex_new(id, kardex_socio) {
                var url=kardex_socio + id; 
                repo.viewPDF(url,'Kardex');
            },

            selectEstadocivil(){
                let me=this;
                var url= '/par_estadocivil/selectEstadocivil';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayEstadocivil = respuesta.estadocivil; 
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectGrado(){
                let me=this;
                var url= '/par_grado/selectGrado';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayGrado = respuesta.grados;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            selectFuerza(){
                let me=this;
                var url= '/par_fuerza/selectFuerza';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayFuerza = respuesta.fuerzas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectDepartamento(){
                let me=this;
                var url= '/par_departamento/selectDepartamento';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayDepartamento = respuesta.departamentos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectTiposocio(){
                let me=this;
                var url= '/par_tiposocio/selectTiposocio';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayTiposocio = respuesta.tiposocios;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectEscalafon(){
                let me=this;
                var url= '/par_escalafon/selectEscalafon';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayEscalafon = respuesta.escalafons;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

           
   
            selectEstado(){
                let me=this;
                var url= '/par_estadomodulo/afiliacion';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta = response.data;
                    me.arrayEstado = respuesta.estados; console.log(me.arrayEstado);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectRol(){
                let me=this;
                var url= '/rol/selectRol';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayRol = respuesta.roles;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarSocio(page,buscar,criterio);
            },

            registrarsocio(){
				let me = this;
                axios.post('/socio/registrar',{
                    'numpapeleta': this.numpapeleta,
                    'image': this.image,
                    'codsocio': this.codsocio,
                    'nombre': this.nombre,
                    'apaterno': this.apaterno,
                    'amaterno' : this.amaterno,
                    'aesposo' : this.aesposo,
                    'sexo' : this.sexo,
                    'carnetmilitar' : this.carnetmilitar,
                    'ci' : this.ci,
                  //  'cisocio' : this.cisocio,
                    'iddepartamentoexpedido' : this.iddepartamentoexpedido,
                    'fechanacimiento' : this.fechanacimiento,
                    'fechaegreso' : this.fechaegreso,
                    'fechaincorporacion' : this.fechaincorporacion,
                    'fechainscripcion' : this.fechainscripcion,
                    'idmunicipionacimiento' : this.idmunicipionacimiento,
                    'idgrado' : this.idgrado,
                    'iddestino' : this.iddestino,
                    'idfuerza' : this.idfuerza,
                    'idespecialidad' : this.idespecialidad,
                    'idescalafon' : this.idescalafon,
                    'idestadocivil' : this.idestadocivil,
                    'direcciondomicilio' : this.direcciondomicilio,
                    'telfijo' : this.telfijo,
                    'telcelular' : this.telcelular,
                    'email' : this.email,
                    'observaciones' : this.observaciones,
                    'activo' : this.activo,
                   // 'bloqueo' : this.bloqueo,
                    'idestafiliaciones' : this.idestado, 
                    'idusuarioregistro' : this.idusuarioregistro,
                    'idusuariomodificacion' : this.idusuariomodificacion,
                    
                    'idestadoservicio':this.idestado,

                }).then(function (response) {
                    me.cerrarModal();
                     
					swal("¡Se registro los datos correctamente!", "", "success").then((result) => {
					    me.image=''
                        me.listarSocio(1,'','nombre');  
                      })
					
                }).catch(function (error) {
                    console.log(error);
                });
            },

            actualizarFotosocio(){
			
               let me = this; 
                  if(me.image){
							axios.post('/socio/actualizarfoto',{image: me.image, idsocio: me.socio_id}).then(response =>  {
							
							this.actualizaDatosSocio();
							
						}).catch(function (error) {
						   console.log(error);
							me.output = error;
							swal("¡Error al momento subir la Foto!", error.message, "error");
						});
				  }else{
				       this.actualizaDatosSocio();
				  }
            },
			actualizaDatosSocio(){
			let me = this;
				axios.put('/socio/actualizar',{
                    'idsocio': this.socio_id,   
                    'numpapeleta': this.numpapeleta,
                    'codsocio': this.codsocio,
                    'nombre': this.nombre,
                    'apaterno': this.apaterno,
                    'amaterno' : this.amaterno,
                    'aesposo' : this.aesposo,
                    'sexo' : this.sexo,
                    'carnetmilitar' : this.carnetmilitar,
                    'ci' : this.ci,
                   // 'cisocio' : this.cisocio,
                    'iddepartamentoexpedido' : this.iddepartamentoexpedido,
                    'fechanacimiento' : this.fechanacimiento,
                    'fechaegreso' : this.fechaegreso,
                    'fechaincorporacion' : this.fechaincorporacion,
                    'fechainscripcion' : this.fechainscripcion,
                    'idmunicipionacimiento' : this.idmunicipionacimiento,
                    'idgrado' : this.idgrado,
                    'iddestino' : this.iddestino,
                    'idfuerza' : this.idfuerza,
                    'idespecialidad' : this.idespecialidad,
                    'idescalafon' : this.idescalafon,
                    'idestadocivil' : this.idestadocivil,
                    'direcciondomicilio' : this.direcciondomicilio,
                    'telfijo' : this.telfijo,
                    'telcelular' : this.telcelular,
                    'email' : this.email,
                    'observaciones' : this.observaciones,
                    'activo' : this.activo,
                    //'bloqueo' : this.bloqueo,
                    'idestservicios' : this.idestado,  
                    'idusuarioregistro' : this.idusuarioregistro,
                    'idusuariomodificacion' : this.idusuariomodificacion,
                    'idtiposocio':this.idtiposocio

                }).then(function (response) { 
                    me.cerrarModal();
					swal("¡Se actualizo los datos correctamente!", "", "success").then((result) => {
					    me.image=''
                        me.listarSocio(1,'','nombre');  
                      })
                    
					
                }).catch(function (error) {
                    console.log(error);
					me.output = error;
					 
						swal("¡Error al actualizar los datos!", error.message, "error");
                });
					
					
			} ,           


            cerrarModal(){
                this.Modalview='';
			    $("#BmodalSocio").removeAttr('disabled');
			    this.image='';
                this.modal=0;
                this.tituloModal='Registro de Socio';
                this.idusuarioregistro='';
                this.num_documento='';
                this.idrol=0;
                this.tipoAccion = 1; 
                this.errorsocio=0;

            },
            cerrarModal_foto(){
			    this.Modalview='';
                this.modal_foto=0;
                this.tituloModal='Regsitro de Socio';
                this.nombre='';
                this.apaterno='';
                this.amaterno='';
                this.aesposo='';
                this.carnetmilitar='';
                this.ci='';                
                this.errorsocio=0;
            },

            generarCarnetSocio(socio){ 
                this.printcredencial=0;
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
                  axios.get('/sociogetfotoCR').then(function (response) {
                           _pl._vvp2521_cr01(socio,response.data,()=>{
                                swal.close()
                                me.classModal.openModal('credencial');
                            });
                    }).catch(function (error) {
                        console.log(error);
                    });
            
            }, 
            generarCarnetSocioAtraz(){ 
                let me=this;
                me.printcredencial=1;
                me.classModal.closeModal('credencial');
                swal(
                  "¡Debe de dar la vuelta el pvc en el mismo sentido de la impresión!",
                  "",
                  "warning"
                ).then(()=>{ 
                           me.classModal.openModal('credencial');
                });
 
            }, 
			 
            abrirModal(modelo, accion, data = []){
               this.Modalview='R';
				
                switch(modelo){
                    case "socio":
                    {
                        switch(accion){
                            case 'registrar':
                            {   this.image='';
							    this.success='';
                                this.modal = 1;
                                this.tituloModal = 'Registro de Socio';
                                this.numpapeleta= '';
                                this.carnetmilitar= '';
                                this.codsocio= '';
                                this.nombre= '';
                                this.apaterno = '';
                                this.amaterno = '';
                                this.aesposo = '';
                                this.sexo ='';
                                this.ci ='';
                                this.iddepartamentoexpedido ='';
                                this.fechanacimiento ='';
                                this.fechaegreso ='';
                                this.fechaincorporacion ='';
                                this.fechainscripcion ='',
                                this.idmunicipionacimiento ='';
                                this.idgrado ='';
                                this.iddestino ='';
                                this.idfuerza ='';
                                this.idespecialidad ='';
                                this.idescalafon ='';
                                this.idestadocivil ='';
                                this.direcciondomicilio ='';
                                this.telfijo = '';
                                this.telcelular = '';
                                this.email ='';    
                                this.observaciones = '';                            
                                this.idestado ='';
                                this.rutafoto ='';
                                this.fotografia ='';
                                this.idusuarioregistro='';
                                this.num_documento='';
                                this.direccion='';                               
                                this.email='';
                                this.usuario='';
                                this.password='';
                                this.idrol=0;
                                this.tipoAccion = 1;
                                this.idtiposocio='';
                                break;
                            }
                            case 'actualizar':
                            {
								this.image='';
								this.success='';
                                this.modal=1;
                                this.tituloModal='Actualizar Usuario';
                                this.tipoAccion=2;
                                this.socio_id=data['idsocio'];
                                this.numpapeleta = data['numpapeleta'];
                                this.codsocio = data['codsocio'];
                                this.nombre = data['nombre'];
                                this.apaterno = data['apaterno'];
                                this.amaterno = data['amaterno'];
                                this.aesposo = data['aesposo'];
                                this.sexo = data['sexo'];
                                this.carnetmilitar = data['carnetmilitar'];
                                this.ci = data['ci'];
                                this.iddepartamentoexpedido = data['iddepartamentoexpedido'];
                                this.fechanacimiento = data['fechanacimiento'];
                                this.fechaegreso = data['fechaegreso'];
                                this.fechaincorporacion = data['fechaincorporacion'];
                                this.fechainscripcion = data['fechainscripcion'];
                                this.idgrado = data['idgrado'];
                                this.idfuerza = data['idfuerza'];
                                this.idescalafon = data['idescalafon'];
                                this.idestadocivil = data['idestadocivil'];
                                this.direcciondomicilio = data['direcciondomicilio'];
                                this.telfijo = data['telfijo'];
                                this.telcelular = data['telcelular'];
                                this.email = data['email'];
                                this.observaciones = data['observaciones'];
                                this.activo = data['activo'];
                                this.carnetestado = data['carnetestado'];
                                this.idestado = data['idestafiliaciones'];
                                this.rutafoto = data['rutafoto'];
                                // firebase.storage().ref().child('socios/img'+this.numpapeleta+'.png').getDownloadURL().then((url)=>{
                                //      var xhr = new XMLHttpRequest();
                                //         xhr.responseType = 'blob';
                                //         xhr.onload = function(event) {
                                //             var blob = xhr.response;
                                //         };
                                //         xhr.open('GET', url);
                                //         xhr.send();
                                //     }).catch(()=>{
                                //         console.log('no')
                                //     });

                                this.fotografia = data['rutafoto'];
                                this.idusuarioregistro = data['idusuarioregistro'];
                                this.idusuariomodificacion = data['idusuariomodificacion'];
                                this.idtiposocio=data['idtiposocio'];
                                this.idrol = data['idrol'];		
                                this.generarcodsocio();						
								this.updateDep(data['iddepartamentoexpedido'],data['idmunicipionacimiento']);
								this.updateFuerza(data['idfuerza'],data['iddestino'],data['idespecialidad']);
                                break;
                            }
                        }
                    }
                    
                    this.selectEstadocivil();
                    this.selectFuerza();
                    this.selectEscalafon();
                    this.selectDepartamento();
                    this.selectGrado();   
                    this.selectEstado();
                    this.selectTiposocio();
                }
            },

            generarcodsocio(){
                this.nombre.toUpperCase();
                this.apaterno.toUpperCase();
                this.amaterno.toUpperCase();
                var strcodigo='';
                // if(this.nombre) strcodigo+=this.nombre[0]; 
                _.forEach(_.split(_.trim(this.nombre),' '), function(value) {
                 strcodigo+=value[0];
                });

                if(this.apaterno) strcodigo+=this.apaterno[0];
                if(this.amaterno) strcodigo+=this.amaterno[0];
                // if(this.carnetmilitar) strcodigo=this.carnetmilitar+'-'+strcodigo;
                if(this.numpapeleta) strcodigo=this.numpapeleta+'-'+strcodigo;
                this.codsocio=strcodigo; 
            },

            resetBeneficiario(){
                this.nombre='';
                this.apaterno='';
                this.amaterno='';
                this.parentesco='';                                
                this.ci='';
                this.iddepartamentoexpedido='';
                this.fechanacimiento='';
                this.telcelular='';
                this.sexo='';
            },


            registrarBeneficiario(){               
                let me = this;
                axios.post('/afi_beneficiario/registrar',{
                    'idsocio':this.socio_id,                         
                    'nombre': this.nombre.toUpperCase(),
                    'apaterno':this.apaterno.toUpperCase(),
                    'amaterno':this.amaterno.toUpperCase(),
                    'parentesco': this.parentesco,
                    'ci': this.ci,
                    'iddepartamentoexpedido':this.iddepartamentoexpedido,
                    'fechanac': this.fechanacimiento,
                    'telcelular':this.telcelular,
                    //'sexo':this.sexo
                }).then(function (response) {
                    swal('Creado correctamente','','success');
                    me.listaBeneficiarios(me.socio_id);
                    me.resetBeneficiario();
                });
            },

            actualizarBeneficiario(){ 
                let me = this;
                axios.put('/afi_beneficiario/actualizar',{
                    'idbeneficiario':this.beneficiario_id,
                    'nombre': this.nombre,
                    'apaterno':this.apaterno,
                    'amaterno':this.amaterno,
                    'parentesco': this.parentesco,
                    'ci': this.ci,
                    'idsocio': this.idsocio,
                    'iddepartamentoexpedido':this.iddepartamentoexpedido,
                    'fechanac': this.fechanacimiento,
                    'telcelular':this.telcelular,
                    //'sexo':this.sexo
                }).then(function (response) {
                    swal('Datros actualizados','','success');
                    me.listaBeneficiarios(me.socio_id);
                    me.resetBeneficiario();
                    me.tipoAccion=1;
                }); 
            }, 

            registrarCuentasocio(){               
                let me = this;
                axios.post('/con_cuentasocio/registrar',{
                    'idsocio':this.socio_id,                         
                    'identidadbancaria': this.identidadbancaria,
                    'numcuentasocio': this.numcuentasocio,
                    
                }).then(function (response) {
                    me.listarCuentasocio(1,me.socio_id);
                    me.identidadbancaria=0;
                    me.numcuentasocio='';
                }).catch(function (error) {
                    console.log(error);
                });
            },
            
            actualizarCuentasocio(){ 
                let me = this;

                axios.put('/con_cuentasocio/actualizar',{
                    'idcuentasocio':this.cuentasocio_id,
                    'idsocio':this.socio_id,
                    'identidadbancaria': this.identidadbancaria,
                    'numcuentasocio': this.numcuentasocio,
                }).then(function (response) {
                    //me.cerrarModal();
                    me.listarCuentasocio(1,me.socio_id);
                    me.identidadbancaria=0;
                    me.numcuentasocio='';
                    me.tipoAccion=1;

                }).catch(function (error) {
                    console.log(error);
                }); 
            },               

            desactivarBeneficiario(beneficiario){
                swal({
                    title: 'Esta seguro de desactivar este Beneficiario?',
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
                    axios.put('/afi_beneficiario/desactivar',{
                        'idbeneficiario': beneficiario.idbeneficiario
                    }).then(function (response) {
                        me.listaBeneficiarios(beneficiario.idsocio);
                        swal('Desactivado!','El Beneficiario ha sido desactivado con éxito.','success')
                    });                                        
                } 
                }) 
            },

            activarBeneficiario(beneficiario){
                swal({
                    title: 'Esta seguro de activar este Beneficiario?',
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
                        axios.put('/afi_beneficiario/activar',{
                            'idbeneficiario': beneficiario.idbeneficiario
                        }).then(function (response) {
                            me.listaBeneficiarios(beneficiario.idsocio);
                            swal('Activado!','El Beneficiario ha sido activado con éxito.','success');
                        });                      
                    } 
                }) 
            },

            desactivarCuentasocio(idcuentasocio,idsocio){
                swal({
                title: 'Esta seguro de desactivar esta Cuenta?',
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

                    axios.put('/con_cuentasocio/desactivar',{
                        'idcuentasocio': idcuentasocio
                    }).then(function (response) {
                        me.listarCuentasocio(1,idsocio);
                        swal(
                        'Desactivado!',
                        'El registro ha sido desactivado con éxito.',
                        'success'
                        )
                    });
                } 
                }) 
            },

            activarCuentasocio(idcuentasocio,idsocio){
               swal({
                title: 'Esta seguro de activar esta Cuenta?',
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
                        axios.put('/con_cuentasocio/activar',{
                            'idcuentasocio': idcuentasocio
                        }).then(function (response) {
                            me.listarCuentasocio(1,idsocio);
                            swal(
                            'Activado!',
                            'El registro ha sido activado con éxito.',
                            'success'
                            )
                        });
                    } 
                });
            },
            
            selectEntidadbancaria(){
                let me=this;
                var url= '/con_entidadbancaria/selectEntidadbancaria';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayEntidadbancaria = respuesta.entidadbancarias;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            cerrarModalCuentasocio(){
                this.Modalview='';
                this.modalCuentasocio=0;
                this.tituloModalCuentasocio='';
                this.identidadbancaria=0;
                this.nomentidadbancaria='';
                this.numcuentasocio='';
            },

            cerrarModalBeneficiario(){
                this.Modalview='';
                this.modalBeneficiario=0;
                this.tituloModalBeneficiario='';
                this.parentesco='';
                this.nombeneficiario='';
                this.cibeneficiario='';
                this.fecnacbeneficiario='';
            },
            
            abrirModalCuentasocio(modelo, accion, data=[]){
                this.Modalview='C';
                //this.selectRol();
                switch(modelo){
                    case "cuentasocio":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.socio_id=data['idsocio'];
                                this.listarCuentasocio(1,this.socio_id);
                                this.nombre=data['nombre'];
                                this.apaterno=data['apaterno'];
                                this.amaterno=data['amaterno'];


                                this.modalCuentasocio = 1;
                                this.tituloModalCuentasocio = 'Registro de Cuenta de Socio';
                                this.identidadbancaria=0;
                                this.nomentidadbancaria='';
                                this.numcuentasocio='';
                                this.tipoAccion=1;


                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                //this.listarCuentasocio(1,this.socio_id);
                                this.modalCuentasocio=1;
                                this.tituloModalCuentasocio='Actualizar Cuenta de Socio';
                                this.tipoAccion=2;
                                this.cuentasocio_id=data['idcuentasocio'];

                                this.identidadbancaria=data['identidadbancaria'];
                                this.numcuentasocio=data['numcuentasocio'];                                
                                
                                //this.identidadbancaria = data['entidadbancaria'];
                                //this.numcuentasocio = data['numcuentasocio'];
                                
                                //this.activo = data['activo'];
                                break;
                            }
                        }
                    }
                }
                this.selectEntidadbancaria();
            },


            //modal de beneficiario
            abrirModalBeneficiario(modelo, accion, data=[]){
                //this.selectRol();
                this.Modalview='B';
                this.modalBeneficiario=1;
                this.nombrecompleto=data.nomgrado+' '+data.apaterno+' '+data.amaterno+' '+data.nombre;
                this.selectDepartamento();
                this.resetBeneficiario();
                
                switch(modelo){
                    case "beneficiario":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.socio_id=data['idsocio'];
                                this.listaBeneficiarios(this.socio_id);
                                this.tipoAccion=1;
                                this.tituloSeccion = 'Nuevo Beneficiario/Familiar';
                                break;
                            }
                            case 'actualizar':
                            {
                                this.tipoAccion=2;
                                this.tituloSeccion='Modificar datos del Beneficiario/Familiar';
                                this.beneficiario_id=data.idbeneficiario;
                                this.parentesco=data.parentesco;
                                this.nombre=data.nombre;
                                this.apaterno=data.apaterno;
                                this.amaterno=data.amaterno;
                                this.ci=data.ci;
                                this.idsocio=data.idsocio;
                                this.iddepartamentoexpedido=data.iddepartamento
                                this.fechanacimiento=data.fechanac;
                                this.telcelular=data.telcelular;
                                //this.sexo.data.sexo
                                break;
                            }
                        }
                    }
                }
            },

            desactivarSocio(id){
               swal({
                title: 'Esta seguro de desactivar este usuario?',
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

                    axios.put('/socio/desactivar',{
                        'idsocio': id
                    }).then(function (response) {
                        me.listarSocio(1,'','nombre');
                        swal(
                        'Desactivado!',
                        'El registro ha sido desactivado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                } 
                }) 
            },
            activarSocio(id){
               swal({
                title: 'Esta seguro de activar este usuario?',
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

                        axios.put('/socio/activar',{
                            'idsocio': id
                        }).then(function (response) {
                            me.listarSocio(1,'','nombre');
                            swal(
                            'Activado!',
                            'El registro ha sido activado con éxito.',
                            'success'
                            )
                        }).catch(function (error) {
                            console.log(error);
                        });
                    } 
                }) 
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
		    this.getRutasReports();
            this.listarSocio(1,this.buscar,this.criterio); 
            this.classModal = new _pl.Modals();
            this.classModal.addModal("credencial");  
        }
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }   
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: #c93f23 !important;
        font-size: 12px;
        font-style: italic;
        text-align: right;
    }
  

    /* INICIO adicionado por ivan*/
    .formu-tabla{
        display:table; width:100%; 
    }
    .formu-etiqueta{
        display:table-cell; width:35%; vertical-align:middle;
    }
    .formu-entrada{
        display:table-cell;
    }
        .formu-codsocio{
        text-transform: uppercase;
        text-align: center;
        font-family: 'verdana';
        font-size: 14px;
        font-weight: bold;
        color:#369;
        border: none;
    }
    .fotosocio{
        border:#efefef 2px solid;
        filter:drop-shadow(4px 4px 5px #333);
        width:90%;
		max-width: 100%;
    }
	 .fotosociomini{
	     display: inline-block;
        border:#efefef 1px solid;
        filter:drop-shadow(1px 0px 2px #333);
        width:40px;
    }
    .mayus{
        text-transform: uppercase;
    }
    /* FIN adicionado por ivan*/


img {
  width: 80%;
  margin: auto;
  display: block;
  margin-bottom: 10px;
}
.dropbox {
    outline: 2px dashed grey; /* the dash box */
    outline-offset: -10px;
    background: lightcyan;
    color: dimgray;
    padding: 10px 10px;
    min-height: 200px; /* minimum height */
    position: relative;
    cursor: pointer;
  }

  .input-file {
    opacity: 0; /* invisible but it's there! */
    width: 100%;
    height: 200px;
    position: absolute;
    cursor: pointer;
  }

  .dropbox:hover {
    background: lightblue; /* when mouse over to the drop zone, change color */
  }

  .dropbox p {
    font-size: 1.2em;
    text-align: center;
    padding: 50px 0;
  }

</style>