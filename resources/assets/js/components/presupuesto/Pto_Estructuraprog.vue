<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-nomtipodevolucion"><a href="/">Registro de Estructura Programatica </a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Estructura Programatica
                        <button type="button" @click="abrirModal('estructuraprog','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Registrar Estructura Programatica
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <label class="col-md-3 form-control-label" for="text-input">Gestion</label>
                                    <div class="col-md-9">
                                        <select 
                                            v-model="idpei" @change="onChangeGestion(idpei)">
                                            <option selected="selected" value="" disabled >Gestion...</option>
                                            <option v-for="pei in arrayPei" :key="pei.idpei" :value="pei.idpei" v-text="pei.gestion"></option>
                                        </select>
                                    </div>
                                    <input type="text" v-model="buscar" @keyup.enter="listarEstructuraprog(1,buscar,idpei)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarEstructuraprog(1,buscar,idpei)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>

                        <table class="table table-bordered table-striped table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="col-2">Opciones</th>
                                    <th>Gestion</th>                                    
                                    <th>Objetivo Estrategico</th>                                    
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="estructuraprog in arrayEstructuraprog" :key="estructuraprog.id">
                                    <td>
                                        <button type="button" @click="abrirModal('estructuraprog','actualizar',estructuraprog)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button>
                                        <template v-if="estructuraprog.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarEstructuraprog(estructuraprog.idestructuraprog)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarEstructuraprog(estructuraprog.idestructuraprog)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="estructuraprog.gestion"></td>                                    
                                    <td v-text="estructuraprog.objestrategico"></td>
                                    <td>
                                        <div v-if="estructuraprog.activo">
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
                                <li class="page-nomtipodevolucion" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,idpei)">Ant</a>
                                </li>
                                <li class="page-nomtipodevolucion" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,idpei)" v-text="page"></a>
                                </li>
                                <li class="page-nomtipodevolucion" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,idpei)">Sig</a>
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
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">                                                                                   

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Gestion P.E.I.</label>
                                    <div class="col-md-9">
                                        <select 
                                            v-model="idpei"
                                            v-validate.initial="'required'"
                                            name="pei"
                                            @change="selectObjestrategico(idpei)">
                                            <option selected="selected" value="" disabled >Gestion...</option>
                                            <option v-for="pei in arrayPei" :key="pei.idpei" :value="pei.idpei" v-text="pei.gestion"></option>
                                        </select>
                                        <span class="text-error">{{ errors.first('pei')}}</span>
                                    </div>                                    
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Obj. Estrategico</label>
                                    <div class="col-md-6" >
                                        <select class="form-control col-md-9"
                                            v-model="idobjestrategico"
                                            v-validate.initial="'required'"
                                            name="idobjestrategico">
                                            <option selected="selected" value="" disabled >Programa...</option>
                                            <option v-for="estrategico in arrayEstrategico" :key="estrategico.idobjestrategico" :value="estrategico.idobjestrategico" v-text="estrategico.objestrategico"></option>
                                        </select>
                                        <span class="text-error">{{ errors.first('idobjestrategico')}}</span>
                                    </div>                                    
                                </div>                            


                                <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Obj. de Gestion</label>
                                <button type='button' class="btn btn-info" @click="addNewRow">
                                    <i class="fas fa-plus-circle"></i>
                                    Add
                                </button>
                                    <div class="col-md-7">
                                        <tr v-for="(invoice_product, k) in invoice_products" :key="k">       
                                            <td class="form-control col-md-9">{{k+1}}:
                                            </td>
                                            <td> 
                                                <textarea v-validate.initial="'required'" name="espe" class="form-control col-md-12" placeholder="Obj Especifico" v-model="invoice_product.objgestion"></textarea>                                                        
                                            </td>
                                            <td>
                                                <textarea v-validate.initial="'required'" name="resu" class="form-control col-md-12" placeholder="Resultado esperado" v-model="invoice_product.resultado"></textarea>                                                            
                                            </td>
                                            <td scope="row" class="trashIconContainer"><i class="far fa-trash-alt" @click="deleteRow(k, invoice_product)"></i></td>
                                        </tr>
                                        <span class="text-error">{{ errors.first('espe')}}</span>    
                                        <span class="text-error">{{ errors.first('resu')}}</span>
                                    </div>                                                                     
                                </div>


                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>

                            <button :disabled = "errors.any()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarEstructuraprog()">Guardar</button>
                            <button :disabled = "errors.any()" type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarEstructuraprog()">Actualizar</button>

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
                estructuraprog_id: 0,
                idestructuraprog : '',
                idpei : '',
                idobjestrategico : '',
                
                arrayEstructuraprog : [],
                arrayPei: [],
                arrayEstrategico: [],
                arrayGestion: [],
                valido: 0,
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorEscalafon : 0,
                errorMostrarMsjEscalafon : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 10,
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
                    objetivo: '',
                    resultado: ''
                });                
            },

            deleteRow(index, invoice_product) {
                let me=this;    
                var idx = this.invoice_products.indexOf(invoice_product);
                console.log(idx, index);
                if (idx > -1) {
                    this.invoice_products.splice(idx, 1);                    
                }                                                
            },

            cambiarPagina(page,buscar){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarEstructuraprog(page,buscar,idpei);
            },

            registrarEstructuraprog(){
               let me = this;
                axios.post('/pto_estructuraprog/registrar',{

                    'idpei': this.idpei,
                    'idobjestrategico': this.idobjestrategico,
                    'objgestion': this.invoice_products,
                    
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
                        me.listarEstructuraprog(1,'','');
                    }
                    
                }).catch(function (error) {
                    console.log(error);
                });
            },

            actualizarEstructuraprog(){
                let me = this;
                axios.put('/pto_estructuraprog/actualizar',{
                    'idestructuraprog': this.estructuraprog_id,
                    'idpei': this.idpei,
                    'idobjestrategico': this.idobjestrategico,
                    'objgestion': this.invoice_products,

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
                        me.listarEstructuraprog(1,'','');
                        me.idpei = '';
                    }
                }).catch(function (error) {
                    console.log(error);
                }); 
            },

            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.idestructuraprog='';
                this.idobjestrategico='';
                this.proyecto='';
                this.actividad='';
                this.descripcion='';
            },

            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "estructuraprog":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.invoice_products.length = 0;
                                this.arrayGestion.length = 0;
                                this.arrayEstrategico.length = 0;
                                this.modal = 1;
                                this.tituloModal = 'Registrar Estructura Programatica';
                                this.idestructuraprog= '';
                                this.idpei = '';
                                this.idobjestrategico = '';
                                this.objgestion = '';                                
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);                                
                                this.invoice_products.length = 0
                                this.arrayGestion.length = 0

                                this.modal=1;
                                this.tituloModal='Actualizar Estructura Programatica';
                                this.tipoAccion=2;
                                this.estructuraprog_id=data['idestructuraprog'];                                
                                this.idpei = data['idpei'];
                                this.selectObjestrategico(this.idpei);                                
                                this.idobjestrategico = data['idobjestrategico']; 
                                this.selectObjgestion(this.estructuraprog_id);
                                break;
                            }
                        }
                    }
                }
                this.selectPei();                   
            },

            desactivarEstructuraprog(id){
               swal({
                title: 'Esta seguro de desactivar este Estructura Programatica?',
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

                    axios.put('/pto_estructuraprog/desactivar',{
                        'idestructuraprog': id
                    }).then(function (response) {
                        me.listarEstructuraprog(1,'','');
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

            activarEstructuraprog(id){
               swal({
                title: 'Esta seguro de activar este Estructura Programatica?',
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

                    axios.put('/pto_estructuraprog/activar',{
                         'idestructuraprog': id
                    }).then(function (response) {
                        me.listarEstructuraprog(1,'','');
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

            selectObjestrategico(e){
                let me=this;
                var url= '/pto_pei/selectObjestrategico?idpei=' + e;
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayEstrategico = respuesta.objestrategico;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectObjgestion(e){ 
                let me=this;
                var url= '/pto_objgestion/selectObjgestion?idestructuraprog=' + e;
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayGestion = respuesta.objgestion; 
                    
                    //asignar ya los valores
                    //alert(this.arrayGestion[0].resultado);
                    me.invoice_products.length=0;
                    for (var i=0; i < me.arrayGestion.length; i++){
                        //alert(me.arrayGestion[i].objgestion);
                        //me.invoice_products.push({objetivo:me.arrayGestion[i].objetivo})
                        me.invoice_products.push({objgestion:me.arrayGestion[i].objgestion,resultado:me.arrayGestion[i].resultado,idgestion:me.arrayGestion[i].idobjgestion})
                    }
                    console.log(me.arrayGestion);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            listarEstructuraprog (page,buscar,idpei){  
                let me=this;
                var url= '/pto_estructuraprog?page=' + page + '&buscar='+ buscar + '&idpei='+ idpei;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayEstructuraprog = respuesta.estructuraprog.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            onChangeGestion(gestion1) {
                this.listarEstructuraprog(1,this.buscar,gestion1);            
            }
        },
        mounted() {
            this.selectPei(); 
            this.listarEstructuraprog(1,this.buscar,this.idpei);            
        }
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-nomtipodevolucion !important;
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
