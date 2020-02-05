<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Registro de P.E.I.</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> P.E.I.
                        <button type="button" @click="abrirModal('pei','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- <div class="form-group row">
                            <div class="col-md-6">                
                            <Ajaxselect1 ruta="/pto_pei?buscar=" @foundsocio="peifound" placeholder="Ingrese texto"></Ajaxselect1>                
                            </div>
                        </div>  -->
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!-- <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nompei">Nombre</option>                                      
                                    </select> -->
                                    <input type="text" v-model="buscar" @keyup.enter="listarpei(1,buscar,criterio)" class="form-control" placeholder="Busca por mision, vision, obj gral, gestion">
                                    <button type="submit" @click="listarpei(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Opciones</th>
                                    <th>Gestion</th>
                                    <th>Mision</th>
                                    <th>Vision</th>
                                    <th>Descripcion</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="pei in arraypei" :key="pei.id">
                                    <td>
                                        <button type="button" @click="abrirModal('pei','actualizar',pei)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button>
                                        <template v-if="pei.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarpei(pei.idpei)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarpei(pei.idpei)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                        
                                            <button type="button" @click="abrirModalObjestrategico('objestrategico','registrar',pei)" class="btn btn-warning btn-sm">
                                            <i class="icon-credit-card"></i>                                            
                                            </button>
                                        
                                        <!-- <template v-if="1===1">
                                            <button type="button" @click="abrirModalObjgestion('objgestion','registrar',pei)" class="btn btn-warning btn-sm">
                                            <i class="icon-credit-card"></i>                                            
                                            </button>&nbsp;
                                        </template> -->
                                        
                                    </td>
                                    <td v-text="pei.gestion"></td>
                                    <td v-text="pei.mision"></td>
                                    <td v-text="pei.vision"></td>                                    
                                    <td v-text="pei.descripcion"></td>
                                    <td>
                                        <div v-if="pei.activo">
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

            <div v-if="Modalview === 'G'">
            
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
                                    <label class="col-md-3 form-control-label" for="text-input">Gestion</label>
                                    <div class="col-md-9">
                                        <input v-if="tipoAccion===2"
                                                v-validate.initial="'required|numeric'" 
                                                disabled="disabled"
                                                type="number" 
                                                v-model="gestion" 
                                                class="col-md-2 form-control " 
                                                placeholder="gestion"
                                                name="gestion">
                                        <input v-else
                                                v-validate.initial="'required|numeric'" 
                                                type="number" 
                                                v-model="gestion" 
                                                class="col-md-2 form-control " 
                                                placeholder="gestion"
                                                name="gestion">
                                    <span class="text-error">{{ errors.first('gestion')}}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Mision</label>
                                    <div class="col-md-7">
                                        <textarea v-validate.initial="'required'"                                                 
                                                v-model="mision" 
                                                class="form-control" 
                                                placeholder="mision"
                                                name="mision"></textarea>
                                    <span class="text-error">{{ errors.first('mision')}}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Vision</label>
                                    <div class="col-md-7">
                                        <textarea v-validate.initial="'required'" 
                                                v-model="vision" 
                                                class="form-control" 
                                                placeholder="vision"
                                                name="vision"></textarea>
                                    <span class="text-error">{{ errors.first('vision')}}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Descripcion</label>
                                    <div class="col-md-7">
                                        <textarea v-validate.initial="'required'" 
                                                v-model="descripcion" 
                                                class="form-control" 
                                                placeholder="descripcion"
                                                name="descripcion"></textarea>
                                    <span class="text-error">{{ errors.first('descripcion')}}</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>

                            <button :disabled = "errors.any()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarpei()">Guardar</button>
                            <button :disabled = "errors.any()" type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarpei()">Actualizar</button>

                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
            
            </div>
            
            <div v-else-if="Modalview === 'O'">
            <form @submit.prevent="validateBeforeSubmit"> 
            <!--Inicio de obejtivos estrategicos-->

            <div class="modal fade" tabindex="-1" :class="{'mostrar':modalObjestrategico}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModalObjestrategico"></h4>
                    <button type="button" class="close" @click="cerrarModalObjestrategico()" aria-label="Close"><span aria-hidden="true">x</span></button>
                </div>
                <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                        
                        <div class="col-md-12">
                        <label><h6><b>Gestion:</b> {{gestion1}}</h6></label>
                        </div>
                        <div class="col-md-12">
                        <label><h6><b>Mision:</b> {{mision1}}</h6></label>
                        </div>
                        <div class="col-md-12">
                        <label><h6><b>Vision:</b> {{vision1}}</h6></label>
                        </div>

                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th class="col-2">Editar</th>
                                    <th>Gestion</th>
                                    <th>Obj. Estrategico</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="objestrategico in arrayObjestrategico" :key="objestrategico.idcuentasocio">
                                    <td>
                                        <button type="button" @click="abrirModalObjestrategico('objestrategico','actualizar',objestrategico)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button>
                                        <template v-if="objestrategico.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarObjestrategico(objestrategico.idobjestrategico,objestrategico.idpei)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarObjestrategico(objestrategico.idobjestrategico,objestrategico.idpei)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                                                               
                                    </td>
                                    <td v-text="gestion1"></td>
                                    <td v-text="objestrategico.objestrategico"></td>
                                    <td>
                                        <div v-if="objestrategico.activo">
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
                                    <label class="col-md-3 form-control-label" for="text-input">Obj. estrategico</label>
                                    <div class="col-md-9">
                                        <textarea
                                                v-validate.initial="'required'" 
                                                v-model="objestrategico" 
                                                class="form-control formu-entrada" 
                                                placeholder="Objetivo estrategico"
                                                name="objestrategico"></textarea>
                                     <span class="text-error">{{ errors.first('objestrategico')}}</span>                                    
                                    </div>
                                </div>
                        </form> 
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModalObjestrategico()">Cerrar</button>
                            <!-- :disabled = "errors.any()" -->
                            <button :disabled = "errors.any()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarObjestrategico()">Guardar</button>
                            <button :disabled = "errors.any()" type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarObjestrategico()">Actualizar</button>
                        </div>
                    </div>                    
                </div>                
            </div> 
            <!--Fin del modal-->
            </form>
            </div>


            <div v-else-if="Modalview === 'N'">
            <form @submit.prevent="validateBeforeSubmit"> 
            <!--Inicio de obejtivos gestion-->

            <div class="modal fade" tabindex="-1" :class="{'mostrar':modalObjgestion}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModalObjgestion"></h4>
                    <button type="button" class="close" @click="cerrarModalObjgestion()" aria-label="Close"><span aria-hidden="true">x</span></button>
                </div>
                <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                        
                        <div class="col-md-12">
                        <label><h6><b>Gestion:</b> {{gestion1}}</h6></label>
                        </div>
                        <div class="col-md-12">
                        <label><h6><b>Mision:</b> {{mision1}}</h6></label>
                        </div>
                        <div class="col-md-12">
                        <label><h6><b>Vision:</b> {{vision1}}</h6></label>
                        </div>

                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Editar</th>
                                    <th>Gestion</th>
                                    <th>Obj. Gestion</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="objgestion in arrayObjgestion" :key="objgestion.idcuentasocio">
                                    <td>
                                        <button type="button" @click="abrirModalObjgestion('objgestion','actualizar',objgestion)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button>&nbsp;                                        
                                        <template v-if="objgestion.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarObjgestion(objgestion.idobjgestion,objgestion.idpei)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarObjgestion(objgestion.idobjgestion,objgestion.idpei)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                                                               
                                    </td>
                                    <td v-text="gestion1"></td>
                                    <td v-text="objgestion.objgestion"></td>
                                    <td>
                                        <div v-if="objgestion.activo">
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
                                    <label class="col-md-3 form-control-label" for="text-input">Obj. de Gestion</label>
                                    <div class="col-md-9">
                                        <textarea
                                                v-validate.initial="'required'" 
                                                v-model="objgestion" 
                                                class="form-control formu-entrada" 
                                                placeholder="Objetivo gestion"
                                                name="objgestion"></textarea>
                                     <span class="text-error">{{ errors.first('objgestion')}}</span>                                    
                                    </div>
                                </div>
                        </form> 
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModalObjgestion()">Cerrar</button>
                            <!-- :disabled = "errors.any()" -->
                            <button :disabled = "errors.any()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarObjgestion()">Guardar</button>
                            <button :disabled = "errors.any()" type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarObjgestion()">Actualizar</button>
                        </div>
                    </div>                    
                </div>                
            </div> 
            <!--Fin del modal-->
            </form>
        </div>


        </main>
</template>

<script>
    export default {
        data (){
            return {
                Modalview:'',
                pei_id: 0,
                objestrategico_id: 0,
                objgestion_id: 0,
                gestion : '',
                mision : '',
                vision : '',
                gestion1 : '',
                mision1 : '',
                vision1 : '',
                descripcion : '',
                objestrategico : '',
                objgestion : '',
                arraypei : [],
                arrayObjestrategico : [],
                arrayObjgestion : [],
                modal : 0,
                modalObjestrategico : 0,
                modalObjgestion : 0,
                tituloModal : '',
                tituloModalObjestrategico:'',
                tituloModalObjgestion:'',
                tipoAccion : 0,
                errorpei : 0,
                errorMostrarMsjpei : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 10,
                criterio : 'nompei',
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
            // peifound(test){
            //     console.log('llegada de variable=');
            //     console.log(test);
            //     var respuesta1= test.data; alert(test.data.mision);
            //     me.arraypei = new array(test);                
            // },
        
            listarpei (page,buscar,criterio){
                let me=this;
                var url= '/pto_pei?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    //console.log(response.data);
                    var respuesta= response.data;
                    me.arraypei = respuesta.peis.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            listarObjestrategico (page,idpei){ 
                let me=this;
                var url= '/pto_objestrategico?page=' + page + '&idpei='+ idpei;
                axios.get(url).then(function (response) {
                    var respuesta= response.data; ;
                    me.arrayObjestrategico = respuesta.objestrategico.data; 
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            listarObjgestion (page,idpei){ 
                let me=this;
                var url= '/pto_objgestion?page=' + page + '&idpei='+ idpei;
                axios.get(url).then(function (response) {
                    var respuesta= response.data; ;
                    me.arrayObjgestion = respuesta.objgestion.data; 
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
                me.listarpei(page,buscar,criterio);
            },

            registrarpei(){
               let me = this;
                axios.post('/pto_pei/registrar',{
                    'gestion': this.gestion,
                    'mision': this.mision,
                    'vision': this.vision,
                    'descripcion': this.descripcion
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
                        me.listarpei(1,'','nompei');
                    }
                    
                }).catch(function (error) {
                    console.log(error);
                });
            },

            actualizarpei(){
                let me = this;
                axios.put('/pto_pei/actualizar',{                    
                    'idpei': this.pei_id,
                    'gestion': this.gestion,
                    'mision': this.mision,
                    'vision': this.vision,
                    'descripcion': this.descripcion
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
                        me.listarpei(1,'','nompei');
                    }
                }).catch(function (error) {
                    console.log(error);
                }); 
            },

            registrarObjestrategico(){               
                let me = this;
                axios.post('/pto_objestrategico/registrar',{
                    'idpei':this.pei_id,
                    'objestrategico': this.objestrategico,                    
                }).then(function (response) {
                    me.listarObjestrategico(1,me.pei_id);
                    me.objestrategico='';
                }).catch(function (error) {
                    console.log(error);
                });
            },

            actualizarObjestrategico(){ 
                let me = this; 
                axios.put('/pto_objestrategico/actualizar',{
                    'idobjestrategico':this.objestrategico_id,
                    'idpei':this.pei_id,
                    'objestrategico': this.objestrategico,
                }).then(function (response) {
                    me.listarObjestrategico(1,me.pei_id);
                    me.objestrategico='';
                    me.tituloModalObjestrategico = 'Registro de Objetivos Estrategicos';
                    me.tipoAccion=1;

                }).catch(function (error) {
                    console.log(error);
                }); 
            },   
            
            registrarObjgestion(){               
                let me = this;
                axios.post('/pto_objgestion/registrar',{
                    'idpei':this.pei_id,
                    'objgestion': this.objgestion,
                }).then(function (response) {
                    me.listarObjgestion(1,me.pei_id);
                    me.objgestion='';
                }).catch(function (error) {
                    console.log(error);
                });
            },

            actualizarObjgestion(){ 
                let me = this; 
                axios.put('/pto_objgestion/actualizar',{
                    'idobjgestion':this.objgestion_id,
                    'idpei':this.pei_id,
                    'objgestion': this.objgestion,
                }).then(function (response) {
                    me.listarObjgestion(1,me.pei_id);
                    me.objgestion='';
                    me.tituloModalObjgestion = 'Registro de Objetivos de Gestion';
                    me.tipoAccion=1;

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

            cerrarModalObjestrategico(){
                //this.Modalview='';
                this.modalObjestrategico=0;
                this.tituloModalObjestrategico='';                
                this.objestrategico='';
                this.objgestion='';
            },

            cerrarModalObjgestion(){
                //this.Modalview='';
                this.modalObjgestion=0;
                this.tituloModalObjgestion='';
                this.objestrategico='';
                this.objgestion=''
            },

            abrirModal(modelo, accion, data = []){
                this.Modalview='G';
                switch(modelo){
                    case "pei":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar P.E.I.';
                                this.tipoAccion = 1;
                                this.mision = '';
                                this.vision = '';
                                this.gestion = '';
                                this.descripcion = '';
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar P.E.I.';
                                this.tipoAccion=2;
                                this.pei_id = data['idpei'];
                                this.mision = data['mision'];
                                this.vision = data['vision'];
                                this.gestion = data['gestion'];
                                this.descripcion = data['descripcion'];
                                break;
                            }
                        }
                    }
                }
            },            
            
            abrirModalObjestrategico(modelo, accion, data=[]){ 
                this.Modalview='O';
                
                switch(modelo){
                    case "objestrategico":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modalObjestrategico=1;
                                this.pei_id=data['idpei'];
                                this.listarObjestrategico(1,this.pei_id);
                                this.gestion1=data['gestion']; 
                                this.mision1=data['mision'];
                                this.vision1=data['vision'];

                                this.tituloModalObjestrategico = 'Registro de Objetivos Estrategicos'; 
                                this.idobjestrategico=0;
                                this.objestrategico='';
                                this.tipoAccion=1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modalObjestrategico=1;
                                this.tituloModalObjestrategico='Actualizar Objetivo Estrategico';
                                this.tipoAccion=2;
                                this.pei_id=data['idpei'];
                                this.objestrategico=data['objestrategico']; 
                                this.objestrategico_id=data['idobjestrategico'];
                                break;
                            }
                        }
                    }
                }
            },

            abrirModalObjgestion(modelo, accion, data=[]){ 
                this.Modalview='N';
                
                switch(modelo){
                    case "objgestion":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modalObjgestion=1;
                                this.pei_id=data['idpei'];
                                this.listarObjgestion(1,this.pei_id);
                                this.gestion1=data['gestion']; 
                                this.mision1=data['mision'];
                                this.vision1=data['vision'];

                                this.tituloModalObjgestion = 'Registro de Objetivo de Gestion'; 
                                this.idobjgestion=0;
                                this.objgestion='';
                                this.tipoAccion=1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modalObjgestion=1;
                                this.tituloModalObjgestion='Actualizar Objetivo de Gestion';
                                this.tipoAccion=2;
                                this.pei_id=data['idpei'];
                                this.objgestion=data['objgestion']; 
                                this.objgestion_id=data['idobjgestion'];
                                break;
                            }
                        }
                    }
                }
            },
            
            activarpei(id){
               swal({
                title: 'Esta seguro de activar este pei?',
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

                    axios.put('/pto_pei/activar',{

                         'idpei': id
                    }).then(function (response) {
                        me.listarpei(1,'','nompei');
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

            desactivarpei(id){
               swal({
                title: 'Esta seguro de desactivar este pei?',
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

                    axios.put('/pto_pei/desactivar',{
                        'idpei': id
                    }).then(function (response) {
                        me.listarpei(1,'','nompei');
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

            activarObjestrategico(id,idpei){
               swal({
                title: 'Esta seguro de activar este Obj. Estrategico?',
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

                    axios.put('/pto_objestrategico/activar',{
                         'idobjestrategico': id
                    }).then(function (response) {
                        me.listarObjestrategico(1,idpei);
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

            desactivarObjestrategico(id,idpei){
               swal({
                title: 'Esta seguro de desactivar este Obj. Estrategico?',
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

                    axios.put('/pto_objestrategico/desactivar',{
                        'idobjestrategico': id
                    }).then(function (response) {
                        me.listarObjestrategico(1,idpei);
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

            activarObjgestion(id,idpei){
               swal({
                title: 'Esta seguro de activar este Obj. de Gestion?',
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

                    axios.put('/pto_objgestion/activar',{
                         'idobjgestion': id
                    }).then(function (response) {
                        me.listarObjgestion(1,idpei);
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

            desactivarObjgestion(id,idpei){
               swal({
                title: 'Esta seguro de desactivar este Obj. de Gestion?',
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

                    axios.put('/pto_objgestion/desactivar',{
                        'idobjgestion': id
                    }).then(function (response) {
                        me.listarObjgestion(1,idpei);
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

            validarpei(){
                this.errorpei=0;
                this.errorMostrarMsjpei =[];

                if (!this.nompei) this.errorMostrarMsjpei.push("El nombre del pei no puede estar vacío.");
                if (this.errorMostrarMsjpei.length) this.errorpei = 1;

                return this.errorpei;
            },
        },
            
        mounted() {
            this.listarpei(1,this.buscar,this.criterio);
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
