<template>
    <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Barra de búsqueda</a></li>
    </ol>

    <div class="container-fluid">
    <!-- Ejemplo de tabla Listado -->
        <div class="card">

            <div class="card-header" style="display:table; width:100%">
                 <div style="display:table-cell">
                    <i class="fa fa-angle-right titsubmodulo"></i><span class="titsubmodulo">Servicios</span>
                    <i class="fa fa-angle-right titsubmodulo"></i><span class="titsubmodulo">Ambientes</span>
                </div>
            </div> 

            <div class="card-body">
                
                <div class="form-group row">
                        <label class="form-control-label col-md-1 text-right">Filial:</label>
                        <select class="form-control col-md-2" v-model="idfilial"  name="Filial"
                            @change="selectEstablecimiento(idfilial)">
                            <!-- :class="{'form-control': true, 'error': errors.has('Filial')}" > -->
                            <option v-for="filial in arrayFiliales" v-text="filial.nommunicipio"
                            :key="filial.idfilial" :value="filial.idfilial" ></option>
                        </select>                 

                        <label class="form-control-label col-md-2 text-right">Establecimiento:</label>
                        <select class="form-control col-md-3" v-model="idestablecimiento"  name="Establecimiento" 
                            @change="listarAmbiente (1,idestablecimiento)">
                            <!-- :class="{'form-control': true, 'error': errors.has('Establecimiento')}" > -->
                            <option selected="selected" value="">Establecimiento...</option>
                            <option v-for="establecimiento in arrayEstablecimiento" v-text="establecimiento.nomestablecimiento"                            
                            :key="establecimiento.idestablecimiento" :value="establecimiento.idestablecimiento" ></option>
                        </select>              
                        <div class="col-md-4">                  
                        <button type="button" @click="abrirModalRegistrar(idestablecimiento,arrayEstablecimiento)" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo Ambiente</button>                            
                        </div>                    
                </div>
                
               
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                        <th>Opciones</th>
                        <th>Código de Ambiente</th>
                        <th>Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="ambiente in arrayAmbiente" :key="ambiente.idambiente">
                        <td>
                            <button type="button" class="btn btn-warning btn-sm" @click="abrirModalActualizar(ambiente)" title="Editar ambiente">
                            <i class="icon-pencil"></i>
                            </button> 
                        </td>
                        <td v-text="ambiente.codambiente"></td>
                        <td v-text="ambiente.descripcion"></td>
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
        </div>
        <!-- Fin ejemplo de tabla Listado -->
    </div>

    <p>&nbsp;</p><p>&nbsp;</p>
<!-- 
    <div class="row">
        <div class="col-md-6">
            <table border="1" align="center" width="90%">
                <tr align="center">
                    <td>D1<br>
                        <button type="button" class="btn btn-info" title="ver detalle">
                            <i class="icon-login"></i></button>
                        <button type="button" class="btn btn-info" title="Ocupado">
                            <i class="icon-people" ></i>
                        </button>
                    </td>
                    <td>D2<br>
                        <button type="button" class="btn btn-info"><i class="icon-eye"></i></button>
                        <button type="button" class="btn btn-info"><i class="icon-user-following"></i></button>                    
                    </td>
                    <td>D3<br>
                        <button type="button" class="btn btn-info"><i class="icon-eye"></i></button>
                        <button type="button" class="btn btn-info"><i class="icon-user-following"></i></button>
                    </td>
                    <td>D4<br>
                        <button type="button" class="btn btn-info"><i class="icon-eye"></i></button>
                        <button type="button" class="btn btn-info"><i class="icon-user-following"></i></button>
                    </td>
                    <td>D5<br>
                        <button type="button" class="btn btn-info"><i class="icon-tag"></i></button>
                        <button type="button" class="btn btn-info"><i class="icon-user-following"></i></button>
                    </td>

                </tr>
                <tr>
                    <td><span style="font-size:30px;">D1</span>
                        <br>Inventario
                        <br>Ocupante
                        <br>Cobros
                    </td>
                    <td><span style="font-size:30px;">D2</span>
                        <br>Inventario
                        <br>Ocupante
                        <br>Cobros
                    </td>
                    <td><span style="font-size:30px;">D3</span>
                        <br>Inventario
                        <br>Ocupante
                        <br>Cobros
                    </td>
                    <td><span style="font-size:30px;">D4</span>
                        <br>Inventario
                        <br>Ocupante
                        <br>Cobros
                    </td>

                </tr>

            </table>
        </div>
    </div> -->



    <form @submit.prevent="validateBeforeSubmit">
    <div class="modal fade" tabindex="-1" :class="{'mostrar':modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" >
                    <!-- selects para JUANCITO PINTO -->
                    <!-- selects para JUANCITO PINTO -->
                    <div id="jpin" style="display:none">
                    <!-- <div id="jpin" v-if="idestablecimiento==1"> -->
                        <div class="form-group row">
                            <div class="col-md-3">&nbsp;</div>
                            <label class="col-md-3 form-control-label">Bloque:</label>
                            <select class="col-md-3 form-control" v-model="nrbloque" @change="generarcodhospedaje(nrbloque,nrnivel,nrambiente)">
                                <option value="A">Bloque A</option>
                                <option value="B">Bloque B</option>
                                <option value="C">Bloque C</option>
                                <option value="D">Bloque D</option>
                                <option value="E">Bloque E</option>
                            </select>

                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">&nbsp;</div>
                            <label class="col-md-3 form-control-label">Nivel:</label>
                            <select class="col-md-3 form-control" v-model="nrnivel" @change="generarcodhospedaje(nrbloque,nrnivel,nrambiente)">
                               
                                <option value="1">Piso 1</option>
                                <option value="2">Piso 2</option>
                                <option value="3">Piso 3</option>
                                <option value="4">Piso 4</option>
                            </select>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">&nbsp;</div>
                            <label class="col-md-3 form-control-label" for="text-input">Departamento:</label>
                            <select class="col-md-3 form-control" v-model="nrambiente" @change="generarcodhospedaje(nrbloque,nrnivel,nrambiente)">
                                
                                <option value="1">Departamento 1</option>
                                <option value="2">Departamento 2</option>
                                <option value="3">Departamento 3</option>
                                <option value="4">Departamento 4</option>
                            </select>
                        </div>
                    </div>

                    <!-- selects para CASA COMUNITARIA -->
                    <!-- selects para CASA CUMUNITARIA -->
                    <div id="ccom" style="display:none">
                        <div class="form-group row">
                            <div class="col-md-3">&nbsp;</div>
                            <label class="col-md-3 form-control-label" for="text-input">Piso:</label>
                            <select class="col-md-3 form-control" v-model="nrnivel" @change="generarcodhospedaje(nrbloque,nrnivel,nrambiente,'D')">
                                <option>Seleccione:</option>
                                <option value="1">Piso 1</option>
                                <option value="2">Piso 2</option>
                            </select>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">&nbsp;</div>
                            <label class="col-md-3 form-control-label" for="text-input">Pieza:</label>
                            <select class="col-md-3 form-control" v-model="nrambiente" @change="generarcodhospedaje(nrbloque,nrnivel,nrambiente,'D')">
                                <option>Seleccione:</option>
                                <option value="1">Pieza 1</option>
                                <option value="2">Pieza 2</option>
                                <option value="3">Pieza 3</option>
                                <option value="4">Pieza 4</option>
                                <option value="5">Pieza 5</option>
                                <option value="6">Pieza 6</option>
                                <option value="7">Pieza 7</option>
                                <option value="8">Pieza 8</option>
                                <option value="9">Pieza 9</option>
                                <option value="10">Pieza 10</option>
                                <option value="11">Pieza 11</option>
                                <option value="12">Pieza 12</option>
                                <option value="13">Pieza 13</option>
                                <option value="14">Pieza 14</option>
                                <option value="15">Pieza 15</option>
                                <option value="16">Pieza 16</option>
                                <option value="17">Pieza 17</option>
                                <option value="18">Pieza 18</option>
                                <option value="19">Pieza 19</option>
                                <option value="20">Pieza 20</option>
                                <option value="21">Pieza 21</option>
                                <option value="22">Pieza 22</option>
                                <option value="23">Pieza 23</option>
                                <option value="24">Pieza 24</option>
                            </select>
                        </div>  
                    </div>

                    <!-- selects para CASA COCHABAMBA -->
                    <!-- selects para CASA COCHABAMBA -->
                    <div id="ccbb" style="display:none">
                        <div class="form-group row">
                            <div class="col-md-3">&nbsp;</div>
                            <label class="col-md-3 form-control-label" for="text-input">Piso:</label>
                            <select class="col-md-3 form-control" v-model="nrnivel" @change="generarcodhospedaje(nrbloque,nrnivel,nrambiente,'C')">
                                <option>Seleccione:</option>
                                <option value="1">Piso 1</option>
                                <option value="2">Piso 2</option>
                                <option value="3">Piso 3</option>
                                <option value="4">Piso 4</option>
                                <option value="5">Piso 5</option>
                            </select>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">&nbsp;</div>
                            <label class="col-md-3 form-control-label" for="text-input">Habitación:</label>
                            <select class="col-md-3 form-control" v-model="nrambiente" @change="generarcodhospedaje(nrbloque,nrnivel,nrambiente,'C')">
                                <option>Seleccione:</option>
                                <option value="1">Habitación 1</option>
                                <option value="2">Habitación 2</option>
                                <option value="3">Habitación 3</option>
                                <option value="4">Habitación 4</option>
                                <option value="5">Habitación 5</option>
                                <option value="6">Habitación 6</option>
                                <option value="7">Habitación 7</option>
                                <option value="8">Habitación 8</option>
                            </select>
                        </div> 
                    </div>

                    <!-- selects para CASA HABITACIONES -->
                    <!-- selects para CASA HABITACIONES -->          
                    <div id="hab" style="display:none">
                        <div class="form-group row">
                            <div class="col-md-3">&nbsp;</div>
                            <label class="col-md-3 form-control-label" for="text-input">Habitación:</label>
                            <select class="col-md-3 form-control" v-model="nrambiente" @change="generarcodhospedaje(nrbloque,nrnivel,nrambiente,'H')">
                                <option>Seleccione:</option>
                                <option value="1">Habitación 1</option>
                                <option value="2">Habitación 2</option>
                                <option value="3">Habitación 3</option>
                                <option value="4">Habitación 4</option>
                                <option value="5">Habitación 5</option>
                                <option value="6">Habitación 6</option>
                                <option value="7">Habitación 7</option>
                                <option value="8">Habitación 8</option>
                            </select>
                        </div> 
                    </div>

                    <!-- selects para CASA VIVIENDA -->
                    <!-- selects para CASA VIVIENDA -->   
                    <div id="viv" style="display:none">
                        <div class="form-group row">
                            <div class="col-md-3">&nbsp;</div>
                            <label class="col-md-3 form-control-label" for="text-input">Vivienda:</label>
                            <select class="col-md-3 form-control" v-model="nrambiente" @change="generarcodhospedaje(nrbloque,nrnivel,nrambiente,'V')">
                                <option>Seleccione:</option>
                                <option value="1">Vivienda 1</option>
                                <option value="2">Vivienda 2</option>
                                <option value="3">Vivienda 3</option>
                                <option value="4">Vivienda 4</option>
                                <option value="5">Vivienda 5</option>
                                <option value="6">Vivienda 6</option>
                                <option value="7">Vivienda 7</option>
                                <option value="8">Vivienda 8</option>
                            </select>
                        </div> 
                    </div>

                    <!-- selects para MAUSOLEOS -->
                    <!-- selects para MAUSOLEOS --> 
                    <div id="maus" style="display:none">
                        <div class="form-group row">
                            <div class="col-md-3">&nbsp;</div>
                            <label class="col-md-3 form-control-label" for="text-input">Tipo:</label>
                            <div class="formu-entrada col-md-3">
                                <input type="radio" v-validate.initial="'required'" value="N" v-model="tipomausoleo" name="nicho" >
                                <label for="nicho">Nicho</label>
                                <input type="radio" v-validate.inicial="'required'" value="S" v-model="tipomausoleo" name="sarcogafo">                                    
                                <label for="sacofago">Sarcófago</label>
                            </div>
                        </div>    
                        <div class="form-group row">
                            <div class="col-md-3">&nbsp;</div>
                            <label class="col-md-3 form-control-label" for="text-input">Bloque:</label>
                            <select class="col-md-3 form-control" v-model="nrbloque" @change="generarcodmausoleo(nrbloque,nrnivel,nrambiente,tipomausoleo)">
                                <option>...</option>
                                <option value="A">Bloque A </option>
                                <option value="B">Bloque B</option>
                            </select>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">&nbsp;</div>
                            <label class="col-md-3 form-control-label" for="text-input">Fila:</label>
                            <select class="col-md-3 form-control" v-model="nrnivel" @change="generarcodmausoleo(nrbloque,nrnivel,nrambiente,tipomausoleo)">
                                <option>...</option>
                                <option value="1">Fila 1</option>
                                <option value="2">Fila 2</option>
                                <option value="3">Fila 3</option>
                                <option value="4">Fila 4</option>
                                <option value="5">Fila 5</option>
                                <option value="6">Fila 6</option>
                            </select>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">&nbsp;</div>
                            <label class="col-md-3 form-control-label" for="text-input">Ambiente:</label>
                            <select class="col-md-3 form-control" v-model="nrambiente" @change="generarcodmausoleo(nrbloque,nrnivel,nrambiente,tipomausoleo)">
                                <option>...</option>
                                <option value="1">Ambiente 1</option>
                                <option value="2">Ambiente 2</option>
                            </select>
                        </div>
                    </div>

                        <div class="form-group row">
                            <div class="col-md-3">&nbsp;</div>
                            <div class="col-md-2">
                                <span>Garantía:</span>
                                <input  v-validate.initial="'required|numeric'" type="text" 
                                    v-model="garantia" class="form-control" name="Garantía">Bs.
                            </div>
                            <div class="col-md-2">
                                <span>Alquiler:</span>
                                <input  v-validate.initial="'required|numeric'" type="text" 
                                    v-model="alquiler" class="form-control" name="Alquiler">Bs.
                            </div>
                            <div class="col-md-2">
                                <span>Capacidad:</span>
                                <input  v-validate.initial="'required|numeric'" type="text" 
                                    v-model="capacidad" class="form-control" name="Capacidad">Px.
                            </div>
                        </div>


                        <div class="form-group row">
                            <div class="col-md-3">&nbsp;</div>
                            <div class="col-md-6">
                                <span>Descripción:</span>
                            <input  v-validate.initial="'required'" type="text" 
                                    v-model="descripcion" class="form-control" placeholder="Descripción" name="Descripción">                          
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-3">&nbsp;</div>
                            <label class="col-md-4"  style="font-weight:bold; color:#444; font-size:18px; ">Código del ambiente:</label>
                            <input  type="text" v-model="codambiente" style="border:none; width:70px;  font-size:18px; font-weight:bold; color:#444;" >
                            <input type="hidden" v-model="idestablecimiento" >
                        </div>
                    </form>  
                </div>        
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    <input   type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarAmbiente()" value="Guardar">
                    <button  type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarAmbiente()">Actualizar</button>
                    <!-- <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarAmbiente()">Actualizar</button> -->
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
    </form>



    <!--modal para asignar  --> <!--modal para asignar  --> <!--modal para asignar  -->
    <!--modal para asignar  --> <!--modal para asignar  --> <!--modal para asignar  -->    
    <!-- <form enctype="multipart/form-data"> -->
    <!-- <div class="modal fade" tabindex="-1" :class="{'mostrar':modalAsignar}" role="dialog" aria-labelledby="myModalLabel" style="display:none;" aria-hidden="true"> -->
    <form @submit.prevent="validateBeforeSubmit">        
    <div class="modal fade" tabindex="-1" :class="{'mostrar':modalAsignar}" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Asignar Servicio</h4>
                    <button type="button" class="close" @click="cerrarModalAsignar()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>                    
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-7">
                            <span style="font-size:20px">{{nomestablecimiento}}</span>
                            <br><span class="nomcampo">Filial: </span>{{nommunicipio}}
                            <br><span class="nomcampo">Descripción: </span>{{descripcion}}
                            <br><span class="nomcampo">Código del Departamento: </span>{{codambiente}}
                        </div>
                        <div class="col-md-2" >
                            <table align="center">
                            <tr><td colspan="2"><b>Ubicación </b></td></tr>                                
                            <tr><td>Bloque:</td><td>{{bloque}}</td></tr>
                            <tr><td>Piso:  </td><td>{{piso}}</td></tr>
                            <tr><td>Depto: </td><td>{{depto}}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-3" >
                            <table align="center">
                            <tr><td colspan="2" align="center"><b>Canon definido</b></td></tr>
                            <tr><td>Garantía:  </td><td align="right">Bs. {{garantia}}</td></tr>
                            <tr><td>Alquiler:  </td><td align="right">Bs. {{alquiler}}</td></tr>
                            <tr><td>Canon Real:</td><td align="right">U$ {{canonreal}}</td></tr>
                            </table>
                        </div>
                    </div>
                <h5 class="titazul">BÚSQUEDA DE SOCIO</h5>
                    <div class="form-group row">
                        <div class="col-md-8">
                            <table align="center"><tr>
                            <td>C.I. Socio:&nbsp;</td>
                            <td><input type="text" v-model="ci" @keyup.enter="encontrarSocio(ci)" class="form-control"></td>
                            <td><button class="btn btn-secondary" @click="encontrarSocio(ci)">Buscar</button></td>
                            </tr>
                            </table>
                            <div v-if="datossocio">
                                <div class="text-center" style="font-size:18px; padding:5px;">{{nomgrado}} {{nombre}} {{apaterno}} {{amaterno}} </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <table>
                                            <tr><td class="nomcampo">Fuerza:</td><td>{{nomfuerza}}</td></tr>
                                            <tr><td class="nomcampo" valign="top">Destino:</td><td>{{nomdestino}}<br>({{nommunicipio}})</td></tr>
                                        </table>
                                    </div>

                                    <div class="col-md-6">
                                        <table>
                                            <tr><td class="nomcampo">Número CI:</td><td>{{ci}} {{abrvdep}}</td></tr>
                                            <tr><td class="nomcampo">Celular:</td><td>{{telcelular}}</td></tr>
                                            <tr><td class="nomcampo">Est. Civil:</td><td>{{nomestadocivil}}</td></tr>
                                            <tr><td class="nomcampo">Situación:&nbsp;</td><td>{{nomestado}}</td></tr>
                                            <tr><td class="nomcampo">Esposa:&nbsp;</td><td>{{nombeneficiario}}</td></tr>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <img v-if="rutafoto && datossocio" :src="'storage/socio/'+rutafoto" width="150" height="150" class="fotosocio">
                        </div>
                    </div>

                    <div v-if="titcalifica" class="text-center">
                        <span style="font-size:18px; padding:5px; color:#c93f23; font-weight:bold;">NO CALIFICA</span>
                        <p v-text="txtcalifica"></p>
                    </div>
                    
                    <div v-if="formasigna">
                    <h5 class="titazul">ASIGNACIÓN</h5>                        
                    <div class="form-group row">
                        <div class="col-md-4">
                            <div style="display:table">
                                <div >
                                    <div style="display:table-cell; vertical-align:middle;">Número Depósito Garantía:</div>
                                    <div style="display:table-cell; padding:5px 0px; width:45%;">
                                        <input type="text" class="form-control" name="Código Operación"
                                            v-model="codoperacion" v-validate.initial="'required|numeric'"
                                            :class="{'input':true,'is-danger':errors.has('Código Operación')}">
                                    </div>
                                </div>
                                <div class="text-error text-right">{{errors.first('Código Operación')}}</div>

                                <div>
                                    <div style="display:table-cell; vertical-align:middle;">Fecha Depósito Garantía:</div>
                                    <div style="display:table-cell; padding:5px 0px; width:45%;">
                                        <input type="date" class="form-control" name="Fecha Depósito"
                                            v-model="fechadeposito" v-validate.initial="'required'"
                                            :class="{'input':true,'is-danger':errors.has('Fecha Depósito')}">
                                    </div>
                                </div>
                                <div class="text-error text-right">{{errors.first('Fecha Depósito')}}</div>    
                                <div >
                                    <div style="display:table-cell; vertical-align:middle; width:55%">Cant. Ocupantes:</div>
                                    <div style="display:table-cell; padding:5px 0px; width:45%;">
                                        <select class="form-control" v-model="ocupantes" v-validate="'required'" name="Cant. Ocupantes"
                                            :class="{'form-control': true, 'error': errors.has('Cant. Ocupantes')}">
                                            <option v-for="n in capacidad" v-text="n" :value="n" :key="n"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="text-error text-right">{{errors.first('Cant. Ocupantes')}}</div>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div style="display:table">
                                <div >
                                    <div style="display:table-cell; vertical-align:middle;">Número Contrato:</div>
                                    <div style="display:table-cell; padding:5px 0px;  width:40%;">
                                        <input type="text" class="form-control" name="Número de contrato"
                                        v-model="numerocontrato" v-validate.initial="'required'" >
                                    </div>
                                </div>
                                <div class="text-error text-right">{{errors.first('Número de contrato')}}</div>

                                <div >
                                    <div style="display:table-cell; vertical-align:middle;">Inicio Contrato:</div>
                                    <div style="display:table-cell; padding:5px 0px;  width:50%;">
                                        <input type="date" class="form-control" name="Inicio de contrato"
                                        @change="calcularFin()"  @click="califica=true"
                                        v-model="iniciocontrato" v-validate.initial="'required'" >

            <!-- @change="fincontrato=((iniciocontrato.substr(0,4)*1)+3)+iniciocontrato.substr(4,6)" 
                var aa=((this.iniciocontrato.substr(0,4)*1)+3);
            this.fincontrato=((this.iniciocontrato.substr(0,4)*1)+3)+this.iniciocontrato.substr(4,6); -->

                                    </div>
                                </div>
                                <div class="text-error text-right">{{errors.first('Inicio de contrato')}}</div>

                                <div >
                                    <div style="display:table-cell; vertical-align:middle;">Fin Contrato:</div>
                                    <div style="display:table-cell; padding:5px 0px;  width:50%;">
                                        <input type="date" class="form-control" name="Fin de contrato" @click="califica=true"
                                        v-model="fincontrato" v-validate.initial="'required'" >
                                    </div>
                                </div>
                                <div class="text-error text-right">{{errors.first('Fin de contrato')}}</div>
                                <input type="hidden" v-model="idsocio"><input type="hidden" v-model="idambiente">
                            </div>
                        </div>


                        <div class="col-md-4">
                            Requisitos:
                            <ul style="list-style:none; padding-left:0px;">
                                <li v-for="req in arrayReq" :key="req.iddocumento">
                                    <input type="checkbox" v-model="arrayDocumentos" :value="req.iddocumento">{{req.nomdocumento}} 
                                </li>
                            </ul> 
                        </div>
                    </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModalAsignar()">Cerrar</button>
                    <!-- <button :disabled="errors.any()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarAsignacion()">Registrar</button> -->
                    <button id="bt" :disabled="!califica" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarAsignacion()">Registrar</button>
                    <button id="bt" :disabled="!califica" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarAsignacion()">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
    </form>



    <!--modal regsitro de pagos  --> <!--modal regsitro de pagos  --> 
    <!--modal regsitro de pagos  --> <!--modal regsitro de pagos  --> 
    <!--modal regsitro de pagos  --> <!--modal regsitro de pagos  --> 
    <!--modal regsitro de pagos  --> <!--modal regsitro de pagos  --> 
    
    <form @submit.prevent="validateBeforeSubmit">        
    <div class="modal fade" tabindex="-1" :class="{'mostrar':modalPagos}" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Registro de Pagos del Locatario</h4>
                    <button type="button" class="close" @click="cerrarModalPagos()"><span aria-hidden="true">×</span></button>                    
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <span class="nomcampo">{{nomestablecimiento}} - {{nommunicipio}}</span>
                            <br><span>{{descripcion}}</span>
                        </div>
                        <div class="col-md-6 text-right">
                            <span class="nomcampo">Código: {{codambiente}}</span>
                            <br>Bloque: {{bloque+piso}} - Departamento: D{{depto}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <span class="nomcampo">Locatario: </span> {{nomgrado+' '+nombre+' '+apaterno}}
                            <br><span class="nomcampo">Esposa: </span> {{nombeneficiario}}
                        </div>
                        <div class="col-md-6">
                            <span class="nomcampo">Fuerza:</span> <span v-text="nomfuerza"></span><br>
                            <span class="nomcampo">Destino:</span> <span v-text="nomdestino"></span>
                        </div>
                    </div>


                <h5 class="titazul">Historial de Pagos</h5>
                <div class="modal-body2">  
                    <table class="table table-bordered table-striped table-sm">
                    <thead>
                    <tr>
                        <th>Concepto</th>
                        <th>Periodo</th>
                        <th>Modo</th>
                        <th>Cód Operación</th>
                        <th>Monto</th>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="pago in arrayPago" :key="pago.idpago">                        
                        <td v-text="pago.concepto"></td>
                        <td v-text="pago.periodo"></td>
                        <td v-text="pago.modopago"></td>
                        <td v-text="pago.codoperacion" class="text-right"></td>
                        <td v-text="pago.monto+',00 Bs'" class="text-right"></td>
                        <td v-text="pago.fecha"></td>
                        <td v-text="pago.idusuario"></td>
                        <td>
                            <button v-if="pago.concepto!='Garantía'" type="button" @click="editarPago(pago.idpago)" title="Editar Pago" 
                            class="btn btn-warning btn-sm"><i class="icon-pencil"></i></button>
                            <button  type="button" @click="editarPago(pago.idpago)" title="Imprimir Comprobante"
                            class="btn btn-warning btn-sm"><i class="icon-printer"></i></button>
                        </td>
                    </tr>
                    </tbody>
                    </table>
                </div>
                <br>
                <h5 class="titazul">Registrar Pago</h5>
                <form>
                <div class="form-group row">
                    <div class="col-md-7">
                    <table width="100%">
                    <tr>
                        <td>Concepto:<br><input type="hidden" v-model="idasignacion">
                            <select class="form-control" v-model="concepto"
                                @change="concepto=='Alquiler'?monto=alquiler:monto=''">
                                <option></option>
                                <option value="Alquiler">Alquiler</option>
                                <option value="Servicio de Agua">Servicio de Agua</option>
                            </select>
                        </td>
                        <td>Periodo:<br>
                            <select class="form-control" v-model="selmes">
                                <option v-for="mm in arrayMM" :key="mm" :value="mm" v-text="mm"> </option>
                            </select>
                        </td>
                        <td>Gestión:<br>
                            <select class="form-control" v-model="selano">
                                <option v-for="aa in arrayAA" :key="aa" :value="aa" v-text="aa"> </option>
                            </select>
                        </td>

                        <td>Modo Pago:<br>
                            <select class="form-control" v-model="modopago" 
                                @change="modopago=='Descuento'?txtoperacion='Nr Prestamo':txtoperacion='Cod Depósito'">
                                <option></option>
                                <option value="Descuento">Descuento</option>
                                <option value="Depósito">Depósito</option>
                            </select>                        
                        </td>
                    </tr>
                    </table>
                    </div>
                    <div class="col-md-5" style="padding-left:0px;">
                    <table width="100%">
                    <tr> 
                        <td>{{txtoperacion}}<br>
                            <input type="text" class="form-control text-right"  v-model="codoperacion">
                        </td>
                        <td>Monto Bs.<br>
                            <input type="text" class="form-control text-right" v-model="monto">
                        </td>
                        <td>Fecha<br>
                            <input type="date" class="form-control" v-model="fecha">
                        </td>
                    </tr>
                    </table>
                    </div>
                </div>    
                </form>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-npm" @click="cerrarModalPagos()">Cerrar</button>
                    <button v-if="btnPago==1" type="submit" class="btn btn-primary" @click="registrarPago()">Registrar Pago</button>
                    <button v-if="btnPago==2" type="submit" class="btn btn-primary" @click="actualizarPago()">Actualizar Pago</button>
                </div>
            </div>
        </div>
    </div>
    </form>




<!-- modal casa comunitaria --> <!-- modal casa comunitaria --> <!-- modal casa comunitaria -->
<!-- modal casa comunitaria --> <!-- modal casa comunitaria --> <!-- modal casa comunitaria -->
<!-- modal casa comunitaria --> <!-- modal casa comunitaria --> <!-- modal casa comunitaria -->
<!-- modal casa comunitaria --> <!-- modal casa comunitaria --> <!-- modal casa comunitaria -->


    <!-- <form enctype="multipart/form-data"> -->
    <!-- <div class="modal fade" tabindex="-1" :class="{'mostrar':modalAsignar}" role="dialog" aria-labelledby="myModalLabel" style="display:none;" aria-hidden="true"> -->
    <form @submit.prevent="validateBeforeSubmit">        
    <div class="modal fade" tabindex="-1" :class="{'mostrar':modalAsignarCama}" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Asignar Servicio</h4>
                    <button type="button" class="close" @click="modalAsignarCama=0" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>                    
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-7">
                            <span style="font-size:20px">{{nomestablecimiento}}</span>
                            <br><span class="nomcampo">Filial: </span>{{nommunicipio}}
                            <br><span class="nomcampo">Descripción: </span>{{descripcion}}
                            <br><span class="nomcampo">Código de Habitación: </span>{{codambiente}}

                        </div>
                        <div class="col-md-2" >
                            <table align="center">
                            <tr><td colspan="2"><b>Ubicación </b></td></tr>                                
                            <tr><td>Piso:  </td><td>{{piso}}</td></tr>
                            <tr><td>Dormit:</td><td>{{depto}}</td></tr>
                            <tr><td>Cama:  </td><td>{{cama}}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-3" >
                            <table align="center">
                            <tr><td colspan="2" align="center"><b>Tarifas</b></td></tr>
                            <tr><td>Pernocte:  </td><td align="right">Bs. {{alquiler}}</td></tr>
                            </table>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                        <h5 class="titazul">DATOS DEL HUÉSPED</h5>
                            <div v-if="barrabusqueda==1">
                            <table align="center"><tr>
                            <td>Apellido:&nbsp;</td>
                            <td><input type="text" v-model="apellido" @keyup.enter="listaApellidos()" class="form-control"></td>
                            <td><button class="btn btn-secondary" @click="listaApellidos()" title="Buscar">
                                <i class="icon-magnifier"></i>   </button>
                                <button class="btn btn-secondary" @click="resApellidos=0,datosHuesped=0,formuCivil=1" title="Nuevo Huésped">
                                <i class="fa fa-user-plus"></i>   </button>
                            </td>
                            </tr>
                            </table>
                            </div>

                            <div v-if="resApellidos" class="scroll-listado">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <!-- <th></th> -->
                                        <th>Grado</th>
                                        <th>Paterno</th>
                                        <th>Materno</th>
                                        <th>Nombre</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr v-for="apellido in arrayApellidos" :key="apellido.idsocio" style="cursor:pointer;">
                                    <!-- <td><button @click="verHuesped(apellido)"><i class="icon-control-play"></i></button>  </td> -->
                                    <td v-text="apellido.nomgrado" @click="muestraHuesped(apellido)"></td>
                                    <td v-text="apellido.apaterno" @click="muestraHuesped(apellido)"></td>
                                    <td v-text="apellido.amaterno" @click="muestraHuesped(apellido)"></td>
                                    <td v-text="apellido.nombre"   @click="muestraHuesped(apellido)"></td>
                                </tr>
                                </tbody>
                            </table>
                            </div>

                            <div v-if="formuCivil"> <br>
                            <table border="0" align="center">
                                <tr>
                                    <td colspan="3" align="center"><b>REGISTRAR NUEVO HUÉSPED</b></td>
                                </tr>
                                <tr>
                                    <td>Nombre</td>
                                    <td colspan="2"><input type="text" class="form-control" v-model="nombre"> </td>
                                </tr>
                                <tr>
                                    <td>Paterno</td>
                                    <td colspan="2"> <input type="text" class="form-control" v-model="apaterno"> </td>
                                </tr>
                                <tr>
                                    <td>Materno</td>
                                    <td colspan="2"> <input type="text" class="form-control" v-model="amaterno"> </td>
                                </tr>
                                <tr>
                                    <td>Número CI</td>
                                    <td><input type="text" class="form-control" v-model="ci"> </td>
                                    <td><select class="form-control" v-model="iddepartamento">
                                            <option v-for="departamento in arrayDepartamentos" :key="departamento.iddepartamento"
                                            :value="departamento.iddepartamento" v-text="departamento.abrvdep"></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Celular</td>
                                    <td colspan="2"> <input type="text" class="form-control" v-model="telcelular"> </td>
                                </tr>
                                <tr>
                                    <td colspan="3" align="center">
                                        <button class="btn btn-secondary" @click="resApellidos=0,formuCivil=0">Cancelar</button>
                                        <button class="btn btn-primary" @click="registrarHuesped()">Guardar</button>
                                    </td>
                                </tr>

                            </table>
                            </div>

                            <div v-if="datosHuesped"> <br>
                            <h3 align="center" style="font-size:18px;" v-text="elhuesped"></h3>
                            <div class="form-group row">
                            <div class="col-md-6">
                                <b>CI:</b> <span v-text="elci"></span>
                            </div>
                            <div class="col-md-6 text-right" >    
                                <b>Celular:</b> <span v-text="elcelular"></span><br>
                                <input type="hidden" v-model="idsocio">
                                <input type="hidden" v-model="idambiente">
                                <input type="hidden" v-model="idasignacion">
                            </div>
                            </div>
                            </div>

                            <div v-if="prendas">
                            <h5 class="titazul">PRENDAS ENTREGADAS</h5>   
                            <div class="form-group row">
                                <div class="col-md-6"> 
                                    <table width="90%" align="center">
                                    <tbody v-for="elemento in arrayElemento" :key="elemento.idelemento" >
                                        <tr v-if="elemento.idelemento<=5">
                                            <td width="20%">
                                                <input class="form-control" style="text-align:center" type="text" value="1" :id="'e'+elemento.idelemento"></td>
                                            <td v-text="elemento.nomelemento"></td>
                                        </tr>
                                    </tbody>
                                    </table> 
                                </div>
                                <div class="col-md-6">
                                    <table width="90%" align="center">
                                    <tbody v-for="elemento in arrayElemento" :key="elemento.idelemento" >
                                        <tr v-if="elemento.idelemento>5">
                                            <td width="20%">
                                                <input class="form-control" style="text-align:center" type="text" value="1" :id="'e'+elemento.idelemento"></td>
                                            <td>{{elemento.nomelemento}}</td>
                                        </tr>
                                    </tbody>
                                    <tbody>
                                        <tr><td colspan="2"><input type="button" @click="haber(invcantidad)" value="haber"></td></tr>
                                    </tbody>
                                    </table> 
                                </div>
                                <span v-text="'***'+invcantidad+'***'" ></span>
                            </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                        <h5 class="titazul">ESTADÍA Y FACTURACIÓN</h5>
                            <div class="form-group row">
                            <table>
                                <tr><td colspan="5" align="center"><b>ENTRADA</b></td></tr>
                                <tr><td>Fecha:</td>
                                    <td><input type="date" class="form-control" v-model="fechaingreso"></td>
                                    <td>Hora:</td>
                                    <td><input type="time" class="form-control" v-model="horaingreso"></td>
                                    <td><button @click="registrarIngreso()" title="Registrar Ingreso"><i class="fa fa-floppy-o" ></i></button>
                                        <button><i class="fa fa-file-text-o" title="Boleta de Ingreso "></i></button>
                                    </td>
                                </tr>
                                <tr><td colspan="5" align="center"><b>SALIDA</b></td></tr>
                                <tr><td>Fecha:</td>
                                    <td><input type="date" class="form-control" v-model="fechasalida"></td>
                                    <td>Hora:</td>
                                    <td><input type="time" class="form-control" v-model="horasalida" @change="calcularNoches()"></td>
                                    <td></td>
                                </tr>
                            </table>
                            </div>

                                    
                        <!-- <h5 class="titazul">FACTURACIÓN</h5> -->
                            <table width="100%" border="0">
                            <tr>
                                <td align="center" colspan="4"><b>FACTURACIÓN</b></td>
                            </tr>
                            <tr>
                                <td nowrap><b>Factura Nro:</b>
                                <td><input type="text" class="form-control" v-model="nrfactura"></td>
                            </tr>
                            <tr>
                                <td><b>Nombre:</b>
                                <td colspan="3">{{elhuesped}}</td>
                            </tr>
                            <tr>
                                <td><b>NIT/CI:</b></td>
                                <td>{{elci}}</td>
                                <td><b>Fecha:</b></td>
                                <td>{{fechasalida}}</td>
                            </tr>
                            <tr>
                                <td><b>Estadía:</b></td>
                                <td>{{noches}} noches</td>
                                <td nowrap><b>Importe Total:</b></td>
                                <td align="right">{{eltotal}}Bs.</td>
                            </tr>

                            <tr><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;</td></tr>

                            <tr>
                                <td align="right" colspan="4">
                                    <button class="btn btn-secondary" @click="modalAsignarCama=0,modalAsignar=0">Cerrar</button>
                                    <button class="btn btn-primary" @click="registrarSalida()">Guardar</button></td>
                            </tr>

                            </table>
                        </div>

                    </div>
                    
                </div>
<!--                 
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="modalAsignarCama=0">Cerrar</button>
                    <button id="bt" :disabled="!califica" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarAsignacion()">Registrar</button>
                    <button id="bt" :disabled="!califica" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarAsignacion()">Actualizar</button>
                </div>
 -->
            </div>
        </div>
    </div>
    </form>




    </main>
</template>

<script>
import VueBarcode from 'vue-barcode';
import Vue from 'vue';
import VeeValidate from 'vee-validate';

const VueValidationEs = require('vee-validate/dist/locale/es');
Vue.use(VeeValidate, 
{   locale: 'es',
    dictionary: { es: VueValidationEs }
});
    
Vue.use(VeeValidate);

export default {
    data (){
        return {
            idfilial:0,
            nommunicipio:'',
            idambiente:0,
            idestablecimiento:0,
            nomestablecimiento:'',
            codambiente:'', bloque:'', piso:'', depto:'',
            descripcion:'',
            ocupante:'',
            arrayAmbiente:[],
            arrayEstablecimiento :[],
            arrayFiliales :[],
            arrayPago:[],
            arrayReq:[],
            arrayDepartamentos:[],

            idsocio:0,
            ci:'', abrvdep:'',
            nomgrado:'',
            nombre:'',
            apaterno:'',
            amaterno:'',
            nomfuerza:'',
            codsocio:'',
            numpapeleta:'',
            nomdestino:'',
            idmunicipio:'',
            nommunicipio:'',
            telcelular:'',
            nomestadocivil:'',
            nombeneficiario:'',
            nomestado:'',
            rutafoto:'',
            modal : 0,
                    modalAsignar:0, formasigna:false, datossocio:false, capacidad:0, garantia:0, alquiler:0, canonreal:0, arrayAA:'', arrayMM:'', 
                    
            nrbloque:'',
            nrnivel:0,   
            nrdepartamento:0,       
            nrambiente:0,
            tipomausoleo:'',
            tituloModal : '',
            tipoAccion : 0,

            numerocontrato:'',
            idasignacion:0, 
            codoperacion:'',
            iniciocontrato:'',
            fincontrato:'',
            fechadeposito:'',
            montoalquiler:'',
            montogarantia:'',
            modopago:'',
            ocupantes:'',
            certmatrimonio:'',
            autmatrimonio:'',
            certhijos:'',
            ciesposa:'',
            cihijos:'',
            nopropiedad:'',
            boletapago:'',
            ordendestino:'',
            txtcalifica:'', titcalifica:false,
            califica:true,
                    modalPagos:0,idpago:0,concepto:'',periodo:'',monto:0,fecha:'',idusuario:0, btnPago:1, selano:'',selmes:'', txtoperacion:'Cód Pago',
                    modalAsignarCama:0, cama:0, arrayDocumentos:[], apellido:'', arrayApellidos:[],
                    fechaingreso:'',fechasalida:'',horaingreso:'',horasalida:'',elhuesped:'',elcelular:'',resApellidos:'0', formuCivil:0,
                    datosHuesped:0, elci:'',elcelular:'', iddepartamento:0, noches:0, arrayElemento:[], prendas:0, nrfactura:0, eltotal:0, 
                    inventario:'',invcantidad:'', barrabusqueda:'',

            pagination : {
                'total' : 0,
                'current_page' : 0,
                'per_page' : 0,
                'last_page' : 0,
                'from' : 0,
                'to' : 0,
            },
            offset:3,
            criterio : '',
            buscar : '',
        }
    },
    components: {
        'barcode': VueBarcode
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
        validateBeforeSubmit() {
            this.$validator.validateAll().then((result) => {
                if (result) {  return;  }                   
                return;
            });
        },

        listarAmbiente(page,idestablecimiento){            
            let me=this;
            var url= '/ser_ambiente?page=' + page + '&idestablecimiento='+ idestablecimiento;
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayAmbiente = respuesta.ambientes.data;
                me.pagination= respuesta.pagination;
            });
        },

        selectFilial(){
            let me=this;
            var url= '/fil_filial/listaFiliales';
            axios.get(url).then(function (response) {
                me.arrayFiliales = response.data.filiales;
            });
        },

        selectEstablecimiento()
        {   let me=this;
            var url='/ser_establecimiento/selectEstablecimiento';
            axios.get(url,{params:{'idfilial':this.idfilial}}).then(function (response){
                var respuesta= response.data;
                me.arrayEstablecimiento=respuesta.establecimientos;
            });
        },

        cambiarPagina(page){
            let me = this;
            me.pagination.current_page = page;
            me.listarAmbiente(page,this.idestablecimiento);
        },

        registrarAmbiente(){
            let me = this;
            axios.post('/ser_ambiente/registrar',{
                'idestablecimiento': this.idestablecimiento,
                'codambiente':this.codambiente,
                'descripcion':this.descripcion,
            }).then(function (response) {
                if(response.data.length){
                    swal('El Departamento ya existe','El código de Departamento ya existe. Revise duplicados','error')
                }
                else {
                    me.cerrarModal();
                    me.listarAmbiente(1,this.idestablecimiento);
                }
            });
        },
        
        actualizarAmbiente(){
            let me = this;
            var idestablecimiento=this.idestablecimiento;
            axios.put('/ser_ambiente/actualizar',{
                'idambiente': this.idambiente,
                'idestablecimiento': this.idestablecimiento,
                'codambiente':this.codambiente,
                'descripcion':this.descripcion,
            }).then(function (response) {
                if(response.data.length){
                    swal('El Valor ya Existe','Ingresa un dato diferente','error')
                }
                else {
                    me.cerrarModal();
                    me.listarAmbiente(1,idestablecimiento);
                }
            }); 
        }, 

        desactivarAmbiente(idambiente){
            swal({
                title: 'Esta seguro de desactivar este Ambiente?',
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
                    var idestablecimiento=this.idestablecimiento;
                    axios.put('/ser_ambiente/desactivar',{'idambiente':idambiente}).then(function (response) {
                        me.listarAmbiente(1,idestablecimiento);
                        swal('Desactivado!','El registro ha sido desactivado con éxito.','success')
                    });
                } 
            });
        },

        activarAmbiente(idambiente){
            swal({
                title: 'Esta seguro de activar este Ambiente?',
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
                    var idestablecimiento=this.idestablecimiento;
                    axios.put('/ser_ambiente/activar',{'idambiente':idambiente}).then(function (response) {
                        me.listarAmbiente(1,idestablecimiento);
                        swal('Activado!','El registro ha sido activado con éxito.','success');
                    });
                } 
            });
        },

        generarcodhospedaje(nrbloque,nrnivel,nrambiente,tipo){
            this.codambiente='';
            if(!nrbloque) 
            {   if(!nrnivel) this.codambiente=tipo+nrambiente;    
                else this.codambiente='P'+nrnivel+tipo+nrambiente;
            }
            else this.codambiente=nrbloque+nrnivel+'D'+nrambiente;

            this.garantia=1000;
            if(nrambiente==1) {this.alquiler=650; this.capacidad=3;}
            if(nrambiente==2) {this.alquiler=770; this.capacidad=5;}
            if(nrambiente==3) {this.alquiler=860; this.capacidad=6;}
            if(nrambiente==4) {this.alquiler=1000; this.capacidad=7;}

        },

        generarcodmausoleo(nrbloque,nrnivel,nrambiente,tipomausoleo){
            this.codambiente=nrbloque+nrnivel+tipomausoleo+nrambiente;
        },

        cerrarModal(){
            this.modal=0;
            document.getElementById('jpin').style.display='none'; 
            document.getElementById('ccom').style.display='none'; 
            document.getElementById('ccbb').style.display='none'; 
            document.getElementById('maus').style.display='none'; 
            document.getElementById('hab').style.display='none'; 
            document.getElementById('viv').style.display='none';                        
        },

        abrirModalRegistrar(idestablecimiento,arrayEstablecimiento){
            if(!idestablecimiento)
            {   swal({title:'Seleccione un establecimiento', type:'warning'});
                return;
            }
            this.modal = 1;
            for(var i=0,titulo='';i<arrayEstablecimiento.length;i++)
            {   if(idestablecimiento==arrayEstablecimiento[i].idestablecimiento)
                {    titulo=arrayEstablecimiento[i].nomestablecimiento; break;
                }
            }
            switch(idestablecimiento){
                case 1: var Q=document.getElementById('jpin'); break;
                case 2: var Q=document.getElementById('ccom'); break;
                case 7: var Q=document.getElementById('ccbb'); break;
                case 3: break;    //this.tituloModal='Cancha sucre';     break;
                case 6: case 11: var Q=document.getElementById('maus'); break;
                case 12: var Q=document.getElementById('hab'); break;
                case 16: var Q=document.getElementById('hab'); break;
                case 18: var Q=document.getElementById('hab'); break;
                case 21: var Q=document.getElementById('hab'); break;
                case 23: var Q=document.getElementById('viv'); break;
                case 25: var Q=document.getElementById('viv'); break;
                case 27: var Q=document.getElementById('viv'); break;
                case 29: var Q=document.getElementById('hab'); break;
            }
            Q.style.display='block';
            this.tituloModal = 'Registrar Ambientes de '+titulo;
            this.idambiente='';
            this.idestablecimiento=idestablecimiento;
            this.nrbloque='';            
            this.nrnivel='';
            this.nrambiente='';
            this.codambiente='';
            this.garantia='';
            this.alquiler='';
            this.capacidad='';
            this.descripcion='';
            this.tipoAccion = 1;
        },

        abrirModalActualizar(data=[]){
            switch(data['idestablecimiento']){
                case 1: var Q=document.getElementById('jpin'); break;
                case 2: var Q=document.getElementById('ccom'); break;
                case 7: var Q=document.getElementById('ccbb'); break;
                case 3: break;    //this.tituloModal='Cancha sucre';     break;
                case 6: case 11: var Q=document.getElementById('maus'); break;
                case 12: var Q=document.getElementById('hab'); break;
                case 16: var Q=document.getElementById('hab'); break;
                case 18: var Q=document.getElementById('hab'); break;
                case 21: var Q=document.getElementById('hab'); break;
                case 23: var Q=document.getElementById('viv'); break;
                case 25: var Q=document.getElementById('viv'); break;
                case 27: var Q=document.getElementById('viv'); break;
                case 29: var Q=document.getElementById('hab'); break;
            }
            Q.style.display='block';            
            this.modal=1;
            this.tipoAccion=2;
            this.tituloModal='Actualizar Ambiente';
            this.idambiente=data['idambiente'];
            this.nrbloque=data['codambiente'].substr(0,1);
            this.nrnivel =data['codambiente'].substr(1,1);
            this.nrambiente=data['codambiente'].substr(-1);
            this.idestablecimiento=data['idestablecimiento'];
            this.codambiente=data['codambiente'];
            this.garantia=data['garantia'];
            this.alquiler=data['alquiler'];
            this.capacidad=data['capacidad'];
            this.descripcion=data['descripcion'];
            
        },

        //desde aqui la asignación de servicios  //desde aqui la asignación de servicios 
        //desde aqui la asignación de servicios  //desde aqui la asignación de servicios 
        //desde aqui la asignación de servicios  //desde aqui la asignación de servicios 
        //desde aqui la asignación de servicios  //desde aqui la asignación de servicios 

        cerrarModalAsignar(){
            this.modalAsignar=0;
            this.datossocio=false;
            this.formasigna=false;
            this.califica=false;
            this.titcalifica=false;
        },

        formuCondominio()
        {

        },

        formuAlojamiento(data=[])
        {
            this.idambiente=data['idambiente'];//para el hidden de la asignacion
            this.modalAsignarCama=1;
            this.tipoAccion=1;
            let me=this;
            me.nomestablecimiento=data['nomestablecimiento'];
            //alert(data['nomestablecimiento']);
            me.nommunicipio=data['nommunicipio'];
            me.descripcion=data['descripcion'];            
            //this.asignaPieza=1;

        },

        abrirModalAsignar(data=[]){
            let me=this;
            me.nomestablecimiento=data['nomestablecimiento'];
            me.nommunicipio=data['nommunicipio'];
            me.descripcion=data['descripcion'];
            me.codambiente=data['codambiente'];
            me.bloque=me.codambiente.substring(0,1); 
            me.piso=me.codambiente.substring(1,2);
            me.depto=me.codambiente.substring(3,4);
            me.garantia=data['garantia'];
            me.alquiler=data['alquiler'];
            me.canonreal=data['canonreal'];
            me.capacidad=data['capacidad'];
            this.idambiente=data['idambiente'];//para el hidden de la asignacion
            this.tipoAccion=1;
            this.modalAsignar=1;

            if(data['idestablecimiento']==2){ 
                this.modalAsignarCama=1;
                this.barrabusqueda=1;
                this.datosHuesped=0;
                this.resApellidos=0;
                this.prendas=0;
                //setear
                this.elhuesped=0;
                this.apellido='';
                this.fechaingreso='';
                this.horaingreso='';
                this.fechasalida='';
                this.horasalida='';           

                me.piso=me.codambiente.substr(1,1);  
                me.depto=me.codambiente.substr(3,2);
                me.cama=me.codambiente.substr(-1); 
                axios.get('/par_departamento?activo=1').then(function(response){
                    me.arrayDepartamentos=response.data.departamentos;
                });
                axios.get('/ser_elemento/elementosServicio?idservicio=4').then(function(response){
                    me.arrayElemento=response.data.elemento;
                })
                if(!data['disponible']){ //editar casa comunitaria
                    var url='ser_asignacion/encontrar?idambiente='+data['idambiente'];
                    axios.get(url).then(function(response){
                        var respuesta=response.data;
                        me.datosHuesped=1;
                        me.prendas=1;
                        me.barrabusqueda=0;
                        me.idasignacion=respuesta.asignacion[0].idasignacion;
                        me.idsocio=respuesta.asignacion[0].idsocio;
                        me.elhuesped=respuesta.asignacion[0].nomgrado+' '+respuesta.asignacion[0].nombre
                                    +' '+respuesta.asignacion[0].apaterno;
                        me.elci=respuesta.asignacion[0].ci;
                        me.elcelular=respuesta.asignacion[0].telcelular;
                        me.fechaingreso=respuesta.asignacion[0].fechaingreso;
                        me.horaingreso=respuesta.asignacion[0].horaingreso;
                        me.fechasalida=respuesta.asignacion[0].fechasalida;
                        me.horasalida=respuesta.asignacion[0].horasalida;
                        me.inventario=respuesta.asignacion[0].inventario;
                        me.invcantidad=respuesta.asignacion[0].invcantidad;
                        me.haber(me.invcantidad);
                    });
                }
                return;
            }
          
            
            this.formasigna=false;
            this.califica=true;
            this.ci='';

            url='/par_documento?idmodulo=4';
            axios.get(url).then(function(response){
                me.arrayReq=response.data.documentos;
            });                

            if(!data['disponible'])
            {   me.tipoAccion=2; me.califica=true;
                var url='ser_asignacion/encontrar?idambiente='+data['idambiente'];
                axios.get(url).then(function(response){
                    var respuesta=response.data; 
                    me.encontrarSocio(respuesta.asignacion[0].ci);
                    me.idasignacion=respuesta.asignacion[0].idasignacion;
                    me.ocupantes=respuesta.asignacion[0].ocupantes;
                    me.numerocontrato=respuesta.asignacion[0].numerocontrato;
                    me.iniciocontrato=respuesta.asignacion[0].iniciocontrato;
                    me.fincontrato=respuesta.asignacion[0].fincontrato;
                    me.arrayDocumentos=JSON.parse('['+respuesta.asignacion[0].iddocumentos+']');
                    me.califica=true;
                });
                var url='/ser_pago/encontrarPago?idasignacion='+me.idasignacion;
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.codoperacion=respuesta.pago[0].codoperacion;
                    me.fechadeposito=respuesta.pago[0].fecha;
                });
            }            
        },

        calcularNoches(){
            var ffin=this.fechasalida.substr(-2)*1;
            var fini=this.fechaingreso.substr(-2)*1;
            var noches=ffin-fini;
            if(this.horasalida>'12:30') noches++;
            this.noches=noches;
            this.eltotal=this.alquiler*noches;
        },

        encontrarSocio(ci){
            this.datossocio=true;
            this.formasigna=true;
            let me = this;
            me.califica=false;
            me.titcalifica=false;
            axios.get('/socio/encontrarSocio?ci='+ci).then(function (response) {
                var respuesta = response.data;
                if(!respuesta.socio[0]) {
                    swal('No encontrado','Este número de cédula no está registrado en el sistema','error'); 
                    me.formasigna=false;
                    me.datossocio=false;
                } 
                me.idsocio=response.data.socio[0].idsocio;
                me.ci=response.data.socio[0].ci;
                me.abrvdep=response.data.socio[0].abrvdep;
                me.nomgrado=response.data.socio[0].nomgrado;
                me.nombre=respuesta.socio[0].nombre;
                me.apaterno=respuesta.socio[0].apaterno;
                me.amaterno=respuesta.socio[0].amaterno;
                me.nomfuerza=respuesta.socio[0].nomfuerza;
                me.codsocio=respuesta.socio[0].codsocio;
                me.numpapeleta=respuesta.socio[0].numpapeleta;
                me.nomdestino=respuesta.socio[0].nomdestino;
                me.idmunicipio=respuesta.socio[0].idmunicipio;
                me.nommunicipio=respuesta.socio[0].nommunicipio;
                me.telcelular=respuesta.socio[0].telcelular;
                me.nomestadocivil=respuesta.socio[0].nomestadocivil;
                me.nomestado=respuesta.socio[0].nomestado;
                me.nombeneficiario=respuesta.socio[0].nombeneficiario;
                me.rutafoto=respuesta.socio[0].rutafoto;

                if(respuesta.socio[0].idmunicipio!=30)
                {   swal('No califica','El socio debe estar destinado en LA PAZ o EL ALTO','error'); 
                    me.califica=false;
                    me.titcalifica=true;
                    me.txtcalifica='El socio debe estar destinado en las ciudades de LA PAZ o EL ALTO';
                    me.formasigna=false;
                }
                
                if(respuesta.socio[0].idestadocivil!=2)
                {   swal('No califica','El socio debe tener estado civil CASADO ','error'); 
                    me.califica=false;
                    me.titcalifica=true;
                    me.txtcalifica='El socio debe tener estado civil CASADO';
                    me.formasigna=false;
                }
                if(respuesta.socio[0].idestservicios!=1)
                {   swal('No califica','El socio debe estar en situación de SERVICIO ACTIVO ','error');
                    me.califica=false;
                    me.titcalifica=true;
                    me.txtcalifica='El socio debe estar en situación de SERVICIO ACTIVO';
                    me.formasigna=false;
                }                
            });
            /*
            axios.get('/ser_asignacion/encontrarAsignacion?ci='+ci+'&idestablecimiento='+this.idestablecimiento).then(function(response){
                var respuesta=response.data;
                if(respuesta.asignacion[0].ci)
                {   swal('No califica','El socio OCUPÓ los ambientes anteriormente','error');
                    var finicio=respuesta.asignacion[0].iniciocontrato;
                    var ffinal=respuesta.asignacion[0].fincontrato;
                    var nrcontrato=respuesta.asignacion[0].numerocontrato;
                    me.califica=false;
                    me.titcalifica=true;                    
                    me.txtcalifica='El socio OCUPÓ los ambientes anteriormente, desde el '+finicio+' al '+ffinal+', según contrato '+nrcontrato;
                    me.formasigna=false;
                }
            });
            */
            //this.codoperacion='';
            this.ocupantes='';
            this.numerocontrato='';
            this.iniciocontrato='';
            this.fincontrato='';
            this.fechadeposito='';
            this.arrayDocumentos=[];
        },

        registrarAsignacion() {
            let me=this;
            axios.post('/ser_asignacion/registrar',{
                'idsocio':this.idsocio,
                'idambiente':this.idambiente,
                'numerocontrato':this.numerocontrato,
                'iniciocontrato':this.iniciocontrato,
                'fincontrato':this.fincontrato,
                'ocupantes':this.ocupantes,
                'iddocumentos':this.arrayDocumentos.join(),
                //pal pago
                'codoperacion':this.codoperacion,
                'monto':this.garantia,
                'fecha':this.fechadeposito,
                'idusuario':1,
            }).then(function(response){swal('Registro correcto','Se ha registrado al localtario','success')});
            me.cerrarModalAsignar();
            me.listarAmbiente(1,1);
        },

        actualizarAsignacion(){
            let me=this;
            axios.put('/ser_asignacion/actualizar',{
                'idasignacion':this.idasignacion,
                'idsocio':this.idsocio,
                'idambiente':this.idambiente,
                'numerocontrato':this.numerocontrato,
                'iniciocontrato':this.iniciocontrato,
                'fincontrato':this.fincontrato,
                'ocupantes':this.ocupantes,
                'iddocumentos':this.arrayDocumentos.join(),
                'codoperacion':this.codoperacion,
                'fecha':this.fechadeposito,

            }).then(function(response){
                me.cerrarModalAsignar();
                me.listarAmbiente(1,1);
            });
        },

        calcularFin(){
            var aa=(this.iniciocontrato.substr(0,4)*1)+3;
            this.fincontrato=aa+this.iniciocontrato.substr(4,6);
        },

        //registro de pagos //registro de pagos //registro de pagos
        //registro de pagos //registro de pagos //registro de pagos
        //registro de pagos //registro de pagos //registro de pagos
        //registro de pagos //registro de pagos //registro de pagos

        cerrarModalPagos(){
            this.modalPagos=0;
        },

        listarPagos(idasignacion){
            let me=this; 
            var url='ser_pago?idasignacion='+idasignacion;
            axios.get(url).then(function(response){
                var respuesta=response.data;
                me.arrayPago=respuesta.pago;
            });
        },

        abrirModalPagos(data=[]){
            this.modalPagos=1;
            this.btnPago=1;
            let me=this;
            me.concepto='';
            me.selmes=''; me.selano='';
            me.modopago='';
            me.codoperacion='';
            me.monto='';
            me.fecha='';
            axios.get('ser_ambiente/encontrarAmbiente?idambiente='+data['idambiente']).then(function(response){
                var respuesta = response.data;
                me.idestablecimiento=respuesta.ambiente[0].idestablecimiento;
                me.nomestablecimiento=respuesta.ambiente[0].nomestablecimiento;
                me.nommunicipio=respuesta.ambiente[0].nommunicipio;
                me.descripcion=respuesta.ambiente[0].descripcion;
                me.alquiler=respuesta.ambiente[0].alquiler;
                me.codambiente=respuesta.ambiente[0].codambiente;
                me.bloque=me.codambiente.substring(0,1);
                me.piso=me.codambiente.substring(1,2);
                me.depto=me.codambiente.substring(3,4);
            });
            axios.get('socio/encontrarSocio?ci='+data['ci']).then(function(response){
                var respuesta=response.data;
                me.nomgrado=respuesta.socio[0].nomgrado;
                me.nombre=respuesta.socio[0].nombre;
                me.apaterno=respuesta.socio[0].apaterno;
                me.nomfuerza=respuesta.socio[0].nomfuerza;
                me.nomdestino=respuesta.socio[0].nomdestino;
                me.nombeneficiario=respuesta.socio[0].nombeneficiario;
            });

            var url='ser_asignacion/encontrarAsignacion?ci='+data['ci']+'&idestablecimiento='+data['idestablecimiento'];
            axios.get(url).then(function(response){
                var respuesta=response.data;
                me.idasignacion=respuesta.asignacion[0].idasignacion;
                me.alquiler=respuesta.asignacion[0].alquiler;
                me.listarPagos(me.idasignacion);
            })
            var aa = (new Date).getFullYear();
            me.arrayAA=[aa-1,aa,aa+1];
            me.arrayMM=['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
        },

        registrarPago(){
            let me=this;
            axios.post('/ser_pago/registrar',{
                'idasignacion':this.idasignacion,
                'concepto':this.concepto,
                'periodo':this.selmes+'/'+this.selano,
                'modopago':this.modopago,
                'codoperacion':this.codoperacion,
                'nrfactura':'', 
                'monto':this.monto,
                'fecha':this.fecha,
                'idusuario':this.idusuario,
            }).then(function(response){
                //swal("Se ha registrado el pago","","success").then((result)=>{
                    //me.listarPagos(this.idasignacion);
                    me.concepto='';
                    me.selano='';
                    me.selmes='';
                    me.modopago='';
                    me.codoperacion='';
                    me.monto='';
                    me.fecha='';
                //});
            });
            this.listarPagos(this.idasignacion);
        },

        editarPago(idpago){
            let me=this;
            this.btnPago=2;
            axios.get('/ser_pago/encontrarPago?idpago='+idpago).then(function(response){
                var respuesta=response.data;
                me.idpago=idpago;
                me.concepto=respuesta.pago[0].concepto;
                me.selano=respuesta.pago[0].periodo.substr(-4);
                me.selmes=respuesta.pago[0].periodo.substr(0,3);
                me.modopago=respuesta.pago[0].modopago;
                me.codoperacion=respuesta.pago[0].codoperacion;
                me.monto=respuesta.pago[0].monto;
                me.fecha=respuesta.pago[0].fecha;
            });            
        },

        actualizarPago(){
            axios.put('/ser_pago/actualizar',{
                'idpago':this.idpago,
                'concepto':this.concepto,
                'periodo':this.selmes+'/'+this.selano,
                'modopago':this.modopago,
                'codoperacion':this.codoperacion,
                'monto':this.monto,
                'fecha':this.fecha,
//                'idusuario':this.idusuario,
            });
            this.listarPagos(this.idasignacion);
            this.concepto='';
            this.selmes='';
            this.selano='';
            this.modopago='';
            this.codoperacion='';
            this.monto='';
            this.fecha='';
            this.btnPago=1;
        },

        //casa comunitaria //casa comunitaria //casa comunitaria //casa comunitaria
        //casa comunitaria //casa comunitaria //casa comunitaria //casa comunitaria
        //casa comunitaria //casa comunitaria //casa comunitaria //casa comunitaria
        //casa comunitaria //casa comunitaria //casa comunitaria //casa comunitaria

        listaApellidos(){
            let me=this;
            me.resApellidos=1;
            me.formuCivil=0;
            me.datosHuesped=0;
            var url='socio?apellido='+this.apellido;
            axios.get(url).then(function(response){
                var respuesta=response.data;
                me.arrayApellidos=respuesta.socios;
            });

        },

        muestraHuesped(data=[]){
            this.resApellidos=0;
            this.datosHuesped=1;
            this.prendas=1;
            this.idsocio=data['idsocio'];
            this.elhuesped=data['nomgrado']+' '+data['apaterno']+' '+data['amaterno']+' '+data['nombre'];
            this.elci=data['ci']+' '+data['abrvdep'];
            this.elcelular=data['telcelular'];
        },

        registrarHuesped(){
            let me=this;
            axios.post('ser_civil/store',{
                'nombre':this.nombre.toUpperCase(),
                'apaterno':this.apaterno.toUpperCase(),
                'amaterno':this.amaterno.toUpperCase(),
                'ci':this.ci,
                'iddepartamentoexpedido':this.iddepartamento,
                'telcelular':this.telcelular,
            }).then(function(response){swal('Cliente guardado','Proceda a registrar la estadía','success');});
            /*
            axios.get('/ser_civil/buscar?ultimo=1').then(function(response){
                var respuesta=response.data;
                //this.idsocio=response.data.civil['idsocio']; 
            });
            */
                this.formuCivil=0;
                this.prendas=1;
                this.datosHuesped=1;
                this.elhuesped=this.apaterno.toUpperCase()+' '+this.amaterno.toUpperCase()+' '+this.nombre.toUpperCase();
                this.elci=this.ci;//+' '+this.abrvdep;
                this.elcelular=this.telcelular;

        },

        registrarIngreso(){
            let me=this;
            for(var i=0;i<this.arrayElemento.length;i++)
                this.inventario+=this.arrayElemento[i].idelemento+',';
            for(var i=1;i<=this.arrayElemento.length;i++)
                this.invcantidad+=$('#e'+i).val()+',';
            axios.post('/ser_asignacion/storeIngreso',{
                'idsocio':this.idsocio,
                'idambiente':this.idambiente,
                'fechaingreso':this.fechaingreso,
                'horaingreso':this.horaingreso,
                'inventario':this.inventario,
                'invcantidad':this.invcantidad,
            }).then(function(response){swal('Registro Correcto','Se ha registrado el ingreso','success')});
            me.modalAsignarCama=0;
            me.modalAsignar=0;
            me.listarAmbiente(1,2);
        },

        registrarSalida(){
            let me=this;            
            for(var i=0;i<this.arrayElemento.length;i++)
                this.inventario+=this.arrayElemento[i].idelemento+',';
            for(var i=1;i<=this.arrayElemento.length;i++)
                this.invcantidad+=$('#e'+i).val()+',';

            axios.put('/ser_asignacion/storeSalida',{
                'idasignacion':this.idasignacion,
                'fechasalida':this.fechasalida,
                'horasalida':this.horasalida,
                'inventario':this.inventario,
                'invcantidad':this.invcantidad,
                'idambiente':this.idambiente,
            });

            
            axios.post('/ser_pago/registrar',{
                'idasignacion':this.idasignacion,
                'concepto':'Hospedaje ',
                'periodo':this.fechaingreso+' al '+this.fechasalida,
                'modopago':'Efectivo',
                'codoperacion':'',
                'monto':this.eltotal,
                'fecha':this.fechasalida,
                'nrfactura':this.nrfactura
            }).then(function(response){swal('Registro Correcto','Se ha registrado la salida','success')});
            this.modalAsignarCama=0;
            this.modalAsignar=0;
            me.listarAmbiente(1,2);
        },

        haber(cantidades)
        {   cantidades=cantidades.substr(0,(cantidades.length)-1);
            //alert(cantidades);
            var arr=JSON.parse('['+cantidades+']');
            for(var i=1;i<=arr.length;i++) $('#e'+i).attr('value',arr[i-1]);

            /*
            var inv='';
            for(var i=0;i<this.arrayElemento.length;i++)
                inv+=this.arrayElemento[i].idelemento+',';            
            var can='';
            for(var i=0;i<this.arrayElemento.length;i++)
                can+=$('#e'+(i+1)).val()+',';
                */
        }

    },

    mounted() {
        this.listarAmbiente(1,'');
        this.selectFilial();
        this.selectEstablecimiento();
    }
}


</script>
