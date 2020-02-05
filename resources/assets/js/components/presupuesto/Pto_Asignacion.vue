<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Registro de Asignaciones.</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Asignacion
                        <button type="button" @click="abrirModal('asignacion','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- <div class="form-group row">
                            <div class="col-md-6">                
                            <Ajaxselect1 ruta="/pto_asignacion?buscar=" @foundsocio="objunidadfound" placeholder="Ingrese texto"></Ajaxselect1>                
                            </div>
                        </div>  -->
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!-- <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nomobjunidad">Nombre</option>                                      
                                    </select> -->
                                    <input type="text" v-model="buscar" @keyup.enter="listarAsignacion(1,buscar,criterio)" class="form-control" placeholder="Busca por mision, vision, obj gral, gestion">
                                    <button type="submit" @click="listarAsignacion(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th class="col-sm-2">Opciones</th>
                                    <th>Gestion</th>
                                    <th>Obj. Estrategico</th>
                                    <th>Programa</th>
                                    <th>Observaciones</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="asignacion in arrayAsignacion" :key="asignacion.id">
                                    <td>
                                        <button type="button" @click="abrirModal('asignacion','actualizar',asignacion)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="asignacion.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarasignacion(asignacion.idasignacion)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarasignacion(asignacion.idasignacion)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="asignacion.gestion"></td>
                                    <td v-text="asignacion.objestrategico"></td>
                                    <td v-text="asignacion.programa"></td>                                    
                                    <td v-text="asignacion.observacion"></td>
                                    <td>
                                        <div v-if="asignacion.activo">
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

           
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                <!-- <ul>
                    <li v-for="error in errors.all()" v-bind:key="error"> {{ error }}</li>
                </ul> -->
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">PEI - Gestion</label>
                                    <div class="col-md-6">
                                    <select 
										v-model="idpei"
										v-validate.initial="'required'" 
										placeholder="PEI"                                               
										name="pei"
                                        @change="selectEstructuraprog(idpei)">
										<option selected="selected" value="" disabled >Gestion...</option>
										<option v-for="pei in arrayPei" :key="pei.idpei" :value="pei.idpei" v-text="pei.gestion"></option>
									</select>
                                    <span class="text-error">{{ errors.first('pei')}}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Obj. Estrategico</label>
                                        <div class="col-md-6">
                                        <select class="form-control"
                                            v-model="idestructuraprog"
                                            v-validate.initial="'required'" 
                                            name="idestructuraprog"
                                            @change="selectPrograma(idestructuraprog)">
                                            <option selected="selected" value="" disabled >Obj Estrategico...</option>
                                            <option v-for="objestrategico in arrayEstructuraprog" :key="objestrategico.idestructuraprog" :value="objestrategico.idestructuraprog" v-text="objestrategico.objestrategico"></option>
                                        </select>                                        
                                        <span class="text-error">{{ errors.first('idestructuraprog')}}</span>                                    
                                        </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Programa</label>
                                        <div class="col-md-6">
                                        <select class="form-control"									
										v-model="idobjgestion"
										v-validate.initial="'required'" 
										name="idobjgestion">
										<option selected="selected" value="" disabled >Est. Programatica...</option>
										<option v-for="objgestion in arrayGestion" :key="objgestion.idobjgestion" :value="objgestion.idobjgestion" v-text="objgestion.objgestion"></option>
                                        </select>                                        
                                        <span class="text-error">{{ errors.first('idobjgestion')}}</span>                                    
                                        </div>
                                </div>

                                <!-- para adiciona una o mas partidas -->
<div class="form-group row">
<label class="col-md-3 form-control-label" for="text-input">Partidas</label>
<button type='button' class="btn btn-info" @click="addNewRow">
    <i class="fas fa-plus-circle"></i>
    Add
</button>
<template v-if="registra">
 <div class="col-md-7">
    <tr v-for="(invoice_product, k) in invoice_products" :key="k">       
        <td> 
            <Ajaxselect v-if="test" 
                ruta="/pto_partida/selectGestionPartida?buscar=" @found="partidas" 
                resp_ruta="partidas"
                labels="codigon2,nombren2"
                placeholder="Ingrese texto" 
                :clearable="false"
                idtabla="idpartidan2">
            </Ajaxselect>                     
        </td>
        <td scope="row" class="trashIconContainer"><i class="far fa-trash-alt" @click="deleteRow(k, invoice_product)"></i></td>
    </tr>
 </div> 
</template>

<template v-else>
 <div class="col-md-6">
    <tr v-for="(invoice_product, k) in invoice_products" :key="k">       
        <td>
            <Ajaxselect v-if="test" 
                ruta="/pto_partida/selectGestionPartida?buscar=" @found="partidas" 
                resp_ruta="partidas"
                labels="codigon2,nombren2"
                placeholder="Ingrese texto" 
                :clearable="false"
                :id="invoice_product.product_no"
                idtabla="idpartidan2">
            </Ajaxselect>                     
        </td>
        <td scope="row" class="trashIconContainer"><i class="far fa-trash-alt" @click="deleteRow(k, invoice_product)"></i></td>
    </tr>
 </div> 
</template>
</div>


<div class="form-group row">
<label class="col-md-3 form-control-label" for="text-input">Reparticion</label>
<button type='button' class="btn btn-info" @click="addNewRow1">
    <i class="fas fa-plus-circle"></i>
    Add
</button>
<template v-if="registra1">
 <div class="col-md-6">
    <tr v-for="(invoice_product1, k1) in invoice_products1" :key="k1">       
    <td>
        <!-- <v-select 
        :options="arrayReparticion"    
        label="reparticion"
        v-model="invoice_product1.selected">
        </v-select> -->

        <select 
            v-model="invoice_product1.selected"
            v-validate.initial="'required'"
            name='reparticion'>            
            <option v-for="reparticion in arrayReparticion" :key="reparticion.idoficina" :value="reparticion.idoficina+'-'+reparticion.idmunicipio " v-text="reparticion.reparticion"></option>
        </select>

        <!-- <input class="form-control" type="text" v-model="invoice_product1.repa" /> -->
    </td>
    <td scope="row" class="trashIconContainer"><i class="far fa-trash-alt" @click="deleteRow1(k1, invoice_product1)"></i></td>
    </tr>
    <span class="text-error">{{ errors.first('reparticion')}}</span>    
 </div> 
</template>
</div>
                                                          

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Observaciones</label>
                                    <div class="col-md-9">
                                        <textarea v-validate.initial="'required'" 
                                                v-model="observacion" 
                                                class="form-control" 
                                                placeholder="observacion"
                                                name="observacion"></textarea>
                                    <span class="text-error">{{ errors.first('observacion')}}</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>

                            <button :disabled = "errors.any()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarasignacion()">Guardar</button>
                            <button :disabled = "errors.any()" type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarasignacion()">Actualizar</button>

                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->                         

        </main>
</template>

<script>
    export default {
        data (){
            return {

                invoice_products: [],
                datopartida: [],

                invoice_products1: [],

                Modalview:'',
                asignacion_id: 0,
                test:1, registra:1, registra1:1, selected:'',

                datapartida:'', datareparticion:'',

                idpei : '',
                idfilial : '',
                idoficina : '',
                idestructuraprog : '',
                idestructuraprog : '',
                idobjgestion : '',
                idpartida : '',
                observacion : '',
                arrayAsignacion : [],
                arrayGestion : [],
                arrayEstructuraprog: [],
                arrayPartida : [],
                arrayPei: [],
                arrayReparticion: [],
                arrayFilial: [],
                arrayOficina: [],
                modal : 0,
                ModalObjespecifico : 0,
                tituloModal : '',
                tituloModalObjespecifico:'',
                tipoAccion : 0,
                errorobjunidad : 0,
                errorMostrarMsjobjunidad : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 10,
                criterio : 'nomobjunidad',
                buscar : ''
            }
        },
       
        formOptions: {
            validateAfterLoad: true,
            validateAfterChanged: true
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
        
            addNewRow() {
                this.invoice_products.push({
                    product_no: ''
                });                
            },

            deleteRow(index, invoice_product) {
                let me=this;    
                var idx = this.invoice_products.indexOf(invoice_product);
                console.log(idx, index);
                if (idx > -1) {
                    this.invoice_products.splice(idx, 1);
                    this.datopartida.splice(idx, 1);
                }                                                
                console.log(me.invoice_products);                
            },

            deleteRow1(index, invoice_product1) {
                let me=this;    
                var idx = this.invoice_products1.indexOf(invoice_product1);
                console.log(idx, index);
                if (idx > -1) {
                    this.invoice_products1.splice(idx, 1);
                }                                                
            },

            addNewRow1() {                
                this.invoice_products1.push({
                    selected: ''
                });                
                console.log(this.invoice_products1);
            },
            
            partidas(partidas){ 
                this.datopartida.push({partida:partidas.idpartidan2})
                console.log(this.datopartida);
            },

            refre () {
                this.test=1;
            },

            listarAsignacion (page,buscar,criterio){
                let me=this;
                var url= '/pto_asignacion?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    //console.log(response.data);
                    var respuesta= response.data;
                    me.arrayAsignacion = respuesta.asignaciones.data;
                    me.pagination= respuesta.pagination;
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
                me.listarAsignacion(page,buscar,criterio);
            },

            registrarasignacion(){  
               let me = this;

                var aux1=0;
                for (var i=0; i<this.datopartida.length; i++) {
                    if (aux1===0) {
                        me.datapartida = this.datopartida[i].partida;
                        aux1 ++;
                    }
                    else {                                                     
                        me.datapartida = me.datapartida +',' +this.datopartida[i].partida;
                    }
                }

                var aux1=0;
                for (var i=0; i<this.invoice_products1.length; i++) {
                    if (aux1===0) {
                        me.datareparticion = this.invoice_products1[i].selected;
                        aux1 ++;
                    }
                    else {                                                     
                        me.datareparticion = me.datareparticion +',' +this.invoice_products1[i].selected;
                    }
                }

                axios.post('/pto_asignacion/registrar',{ 
                    'idpei': this.idpei,
                    'idestructuraprog': this.idestructuraprog,
                    'idobjgestion': this.idobjgestion,
                    'partidas': this.datapartida,
                    'reparticiones': this.datareparticion,
                    'observacion': this.observacion
                }).then(function (response) {
                    
                    if (response.data.length) {                        
                        swal(
                            // response.data,
                            'El valor ya existe',
                            'Ingresar una dato diferente',
                            'error')
                    }
                    else {
                        me.cerrarModal();
                        me.listarAsignacion(1,'','nomobjunidad');
                    }
                    
                }).catch(function (error) {
                    console.log(error);
                });
            },

            actualizarasignacion(){
                let me = this;
                
                var aux1=0;
                for (var i=0; i<this.datopartida.length; i++) {
                    if (aux1===0) {
                        me.datapartida = this.datopartida[i].partida;
                        aux1 ++;
                    }
                    else {                                                     
                        me.datapartida = me.datapartida +',' +this.datopartida[i].partida;
                    }
                }

                var aux1=0;
                for (var i=0; i<this.invoice_products1.length; i++) {
                    if (aux1===0) {
                        me.datareparticion = this.invoice_products1[i].selected;
                        aux1 ++;
                    }
                    else {                                                     
                        me.datareparticion = me.datareparticion +',' +this.invoice_products1[i].selected;
                    }
                }

                axios.put('/pto_asignacion/actualizar',{                    
                    'idasignacion': this.asignacion_id,
                    'idpei': this.idpei,
                    'idestructuraprog': this.idestructuraprog,
                    'idobjgestion': this.idobjgestion,
                    'partidas': this.datapartida,
                    'reparticiones': this.datareparticion,
                    'observacion': this.observacion
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
                        me.listarAsignacion(1,'','nomobjunidad');
                    }
                }).catch(function (error) {
                    console.log(error);
                }); 
            },

            cerrarModal(){
                //this.Modalview='0';
                this.modal=0;
                this.tituloModal='';
                this.gestion='';
                this.mision='',
                this.vision=''
            },        

            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "asignacion":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.invoice_products.length = 0
                                this.invoice_products1.length = 0
                                this.datopartida.length = 0
                                this.arrayGestion.length=0;
                                this.arrayEstructuraprog=0;

                                this.modal = 1;
                                this.tituloModal = 'Registrar Asignacion';
                                this.tipoAccion = 1;
                                this.idpei = '';
                                this.idobjgestion = '',
                                this.idestructuraprog = '';
                                this.observacion = '';
                                break;
                            }
                            case 'actualizar':
                            {
                                
                                this.invoice_products.length = 0
                                this.invoice_products1.length = 0
                                this.datopartida.length = 0

                                this.registra=0;
                                this.modal=1;
                                this.tituloModal='Actualizar Asignacion';
                                this.tipoAccion=2;
                                this.asignacion_id = data['idasignacion'];
                                this.idpei = data['idpei']; this.selectEstructuraprog(this.idpei); 
                                this.idestructuraprog = data['idestructuraprog']; this.selectPrograma(this.idestructuraprog);
                                this.idobjgestion = data['idobjgestion']; 
                                                                
                                var array = JSON.parse("[" + data['partidas'] + "]"); console.log(array);
                                for (var i=0; i < array.length; i++){                                    
                                    this.invoice_products.push({product_no:array[i]})
                                    this.datopartida.push({partida:array[i]})
                                }

                                var repa = [];
                                repa = data['reparticiones'].split(',');
                                //var array1 = JSON.parse("[" + data['reparticiones'] + "]"); console.log(array1);
                                for (var i=0; i < repa.length; i++){                                    
                                    this.invoice_products1.push({selected:repa[i]})
                                }
                                
                                this.observacion = data['observacion'];
                                break;
                            }
                        }
                    }
                    this.selectPei();
                    this.selectReparticion();                    
                }
            },
            
            selectPei(){
                let me=this;
                var url= '/pto_pei/selectPei';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayPei = respuesta.gestionpei; 
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectReparticion(){
                let me=this;
                var url= '/pto_asignacion/selectReparticion';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayReparticion = respuesta.reparticion; //alert(me.arrayReparticion.nomoficina); console.log(me.arrayReparticion);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectFilial(){
                let me=this;
                var url= '/fil_filial/listaFiliales';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayFilial = respuesta.filiales; 
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectOficina(e){
                let me=this;
                var url= '/fil_filial/listaOficinas?idfilial=' + e;
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayOficina = respuesta.oficinas; 
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            selectEstructuraprog(e){
                let me=this;
                var url= '/pto_estructuraprog/selectGestionEstructuraprog?idpei=' + e;
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayEstructuraprog = respuesta.estructuraprog; 
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            selectPrograma(e){
                let me=this;
                var url= '/pto_objgestion/selectObjgestion?idestructuraprog=' + e;
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayGestion = respuesta.objgestion; 
                    //me.selectPartida(e);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectPartida(e){
                let me=this;
                var url= '/pto_partida/selectGestionPartida?idpei=' + e;
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayPartida = respuesta.partida; 
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            activarasignacion(id){
               swal({
                title: 'Esta seguro de activar este asignacion?',
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

                    axios.put('/pto_asignacion/activar',{
                         'idasignacion': id
                    }).then(function (response) {
                        me.listarAsignacion(1,'','nomobjunidad');
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

            desactivarasignacion(id){
               swal({
                title: 'Esta seguro de desactivar este asignacion?',
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

                    axios.put('/pto_asignacion/desactivar',{
                        'idasignacion': id
                    }).then(function (response) {
                        me.listarAsignacion(1,'','nomobjunidad');
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

        },
            
        mounted() {
            this.listarAsignacion(1,this.buscar,this.criterio);
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
        color: red !important;
        font-style: italic;
    }
</style>
