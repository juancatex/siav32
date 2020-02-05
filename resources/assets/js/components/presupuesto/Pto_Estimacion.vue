<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Registro de Estimacion</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Estimacion
                        <button type="button" @click="abrirModal('estimacion','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- <div class="form-group row">
                            <div class="col-md-6">                
                            <Ajaxselect1 ruta="/pto_estimacion?buscar=" @foundsocio="peifound" placeholder="Ingrese texto"></Ajaxselect1>                
                            </div>
                        </div>  -->
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!-- <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nompei">Nombre</option>                                      
                                    </select> -->
                                    <input type="text" v-model="buscar" @keyup.enter="listarestimacion(1,buscar,criterio)" class="form-control" placeholder="Busca por mision, vision, obj gral, gestion">
                                    <button type="submit" @click="listarestimacion(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Opciones</th>
                                    <th>Gestion</th>
                                    <th>Reparticion</th>
                                    <th>Estrategico</th>
                                    <th>Programa</th>                                    
                                    <th>Monto</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="esti in arrayEstimacion" :key="esti.id">
                                    <td>
                                        <button type="button" @click="abrirModal('estimacion','actualizar',esti)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="esti.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarestimacion(esti.idestimacion)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarestimacion(esti.idestimacion)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>                                                                                
                                    </td>
                                    <td v-text="esti.gestion"></td>
                                    <td> {{esti.nomoficina}}-{{esti.nommunicipio}}</td>
                                    <td v-text="esti.estrategico"></td>
                                    <td v-text="esti.programa"></td>                                    
                                    <td v-text="esti.monto"></td>
                                    <td>
                                        <div v-if="esti.activo">
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
                                    <label class="col-md-2 form-control-label" for="text-input">Gestion</label>
                                    <div class="col-md-10">
                                        <select 
                                            v-model="idpei"
                                            v-validate.initial="'required'"
                                            name="pei">
                                            <option selected="selected" value="" disabled >Gestion...</option>
                                            <option v-for="pei in arrayPei" :key="pei.idpei" :value="pei.idpei" v-text="pei.gestion"></option>
                                        </select>
                                    <span class="text-error">{{ errors.first('pei')}}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Reparticion</label>
                                    <div class="col-md-10">
                                       <select 
                                            v-model="reparticion"
                                            v-validate.initial="'required'"
                                            name='reparticion'                                            
                                            @change=selectAsignacion(idpei,reparticion)>
                                            <option selected="selected" value="" disabled >Reparticion...</option>
                                            <option v-for="reparticion in arrayReparticion" :key="reparticion.idoficina" :value="reparticion.idoficina+'-'+reparticion.idmunicipio " v-text="reparticion.reparticion"></option>
                                        </select>
                                    <span class="text-error">{{ errors.first('reparticion')}}</span>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Opciones:</label>
                                    <div class="col-md-10">
                                    <table class="table table-bordered table-striped table-sm">
                                    <tr>
                                        <th class="thead-dark">Obj. Estrategico</th><th>Programa</th><th>Reparticion</th><th>Elegir</th>
                                    </tr>

                                    <tr v-for="lista in arrayListaReparticion" :key="lista.id">
                                        <td>
                                            {{lista.objestrategico}}
                                        </td>
                                        <td>
                                            {{lista.objgestion}}
                                        </td>
                                        <td>
                                            {{lista.repa}}                                            
                                        </td>
                                        <td>
                                            <input type="radio" :value="lista.idasignacion" v-model="idasignacion" name="elejido">
                                        </td>
                                    </tr>  
                                    </table>                                  
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Descripcion</label>
                                    <div class="col-md-6">
                                        <textarea v-validate.initial="'required'" 
                                                v-model="descripcion" 
                                                class="form-control" 
                                                placeholder="descripcion"
                                                name="descripcion"></textarea>
                                    <span class="text-error">{{ errors.first('descripcion')}}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Monto</label>
                                    <div class="col-md-3">
                                         <vue-numeric  
                                                    class="form-control input-importe"
                                                    currency="Bs." 
                                                    separator="," 
                                                    v-validate.initial="{required:true}"                                                    
                                                    v-model="monto"
                                                    v-bind:precision="2"                                                    
                                                    name="monto">
                                        </vue-numeric>                                        
                                    <span class="text-error">{{ errors.first('monto')}}</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>

                            <button :disabled = "errors.any()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarestimacion()">Guardar</button>
                            <button :disabled = "errors.any()" type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarestimacion()">Actualizar</button>

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
                Modalview:'',
                estimacion_id: 0,
                objestrategico_id: 0,
                objgestion_id: 0,
                idpei : '',
                idasignacion : '',
                gestion : '',
                reparticion : '', 
                descripcion : '',
                monto : '',
                arrayEstimacion : [],
                arrayPei : [],
                arrayReparticion : [],
                arrayListaReparticion : [],
                modal : 0,
                tituloModal : '',
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
        
            listarestimacion (page,buscar,criterio){
                let me=this;
                var url= '/pto_estimacion?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    // console.log(response.data); 
                    var respuesta= response.data;
                    me.arrayEstimacion = respuesta.estimacion.data; 
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
                me.listarestimacion(page,buscar,criterio);
            },

            registrarestimacion(){
               let me = this; 
                axios.post('/pto_estimacion/registrar',{
                    'idpei': this.idpei,
                    'idoficina': this.idoficina, 
                    'idmunicipio': this.idmunicipio,
                    'idasignacion': this.idasignacion,
                    'descripcion': this.descripcion,
                    'monto': this.monto,
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
                        me.listarestimacion(1,'','');
                    }
                    
                }).catch(function (error) {
                    console.log(error);
                });
            },

            actualizarestimacion(){
                let me = this;
                axios.put('/pto_estimacion/actualizar',{                    
                    'idpei': this.idpei,
                    'idoficina': this.idoficina,
                    'reparticion': this.reparticion,
                    'idmunicipio': this.idmunicipio,
                    'idasignacion': this.idasignacion,
                    'descripcion': this.descripcion,
                    'monto': this.monto,
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
                        me.listarestimacion(1,'','nompei');
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
                    case "estimacion":
                    {
                        switch(accion){
                            case 'registrar':
                            {                                
                                this.modal = 1;
                                this.tituloModal = 'Registrar Estimacion';
                                this.tipoAccion = 1;
                                this.idpei='';
                                this.reparticion='';
                                this.idoficina='';
                                this.idmunicipio='';
                                this.arrayListaReparticion.length=0;
                                this.monto = '';
                                this.descripcion = '';
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1; 
                                this.tituloModal='Actualizar Estimacion';
                                this.tipoAccion=2;
                                this.idasignacion = data['idasignacion'];
                                this.estimacion_id = data['idestimacion'];
                                this.reparticion = data['idoficina'] + '-' + data['idmunicipio']; 
                                this.idpei = data['idpei'];  this.selectAsignacion(this.idpei,this.reparticion);
                                this.descripcion = data['descripcion'];
                                this.monto = data['monto'];
                                break;
                            }
                        }
                    }
                }
                this.selectReparticion();
                this.selectPei();
            },            
 
            activarestimacion(id){
               swal({
                title: 'Esta seguro de activar este estimacion?',
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

                    axios.put('/pto_estimacion/activar',{

                         'idestimacion': id
                    }).then(function (response) {
                        me.listarestimacion(1,'','nompei');
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

            desactivarestimacion(id){
               swal({
                title: 'Esta seguro de desactivar este estimacion?',
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

                    axios.put('/pto_estimacion/desactivar',{
                        'idestimacion': id
                    }).then(function (response) {
                        me.listarestimacion(1,'','nompei');
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

            selectAsignacion(idpei,reparticion) {
                let me=this;
                var url= '/pto_asignacion/selectAsignacion?idpei=' + idpei + '&reparticion=' + reparticion;
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayListaReparticion= respuesta.asignacion; 
                })
                .catch(function (error) {
                    console.log(error);
                });
            }

        },
            
        mounted() {
            this.listarestimacion(1,this.buscar,this.criterio);
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
