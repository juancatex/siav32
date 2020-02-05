<template>
    <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
    </ol>
    
    <div class="container-fluid">
    <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <div class="card-header" style="display:table; width:100%">
                 <div style="display:table-cell">
                    <i class="fa fa-angle-right titsubmodulo"></i><span class="titsubmodulo">Contabilidad</span>
                    <i class="fa fa-angle-right titsubmodulo"></i><span class="titsubmodulo">Perfiles</span>
                </div>
                <div style="display:table-cell; text-align:right;">
                    <button type="button" @click="abrirModal('perfil','registrar',arrayFilial)" class="btn btn-secondary">
                    <i class="fa fa-plus-square-o"></i>&nbsp;&nbsp;Nueva Filial</button>
                </div>
            </div> 

            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-control col-md-3" v-model="criterio">
                                <option value="nomfilial">Nombre Filial</option>
                            </select>
                            <input type="text" v-model="buscar" @keyup.enter="listarFilial(1,buscar,criterio)" class="form-control" placeholder="Filial a buscar...">
                            <button type="submit" @click="listarFilial(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                        <th>Opciones</th>
                        <th>Código</th>
                        <th>Filial</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>Presidente</th>
                        <th>Secretario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="filial in arrayFilial" :key="filial.idfilial">
                        <td>
                            <button type="button" @click="abrirModal('filial','actualizar',filial)" class="btn btn-warning btn-sm">
                            <i class="icon-pencil"></i>
                            </button> &nbsp;
                            <template v-if="filial.activo">
                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarFilial(filial.idfilial)">
                            <i class="icon-trash"></i>
                            </button>
                            </template>
                            <template v-else>
                            <button type="button" class="btn btn-info btn-sm" @click="activarFilial(filial.idfilial)">
                            <i class="icon-check"></i>
                            </button>
                            </template>
                        </td>
                        <td v-text="filial.codfilial"></td>
                        <td v-text="filial.nommunicipio"></td>
                        <td v-text="filial.direccion"></td>
                        <td v-text="filial.telefono"></td>
                        <td v-text="filial.nombre+' '+filial.apaterno"></td>
                        <td v-text="filial.sociosecretario"></td>
                        <td>
                            <div v-if="filial.activo"><span class="badge badge-success">Activo</span></div>
                            <div v-else><span class="badge badge-danger">Desactivado</span></div>
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
    <form @submit.prevent="validateBeforeSubmit">
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
                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" >
                        <div class="form-group row">
                            <label class="col-md-2 form-control-label" for="text-input">Departamento</label>
                            <div class="col-md-4">
                                <select v-model="iddepartamento" v-validate.initial="'required'" name="Departamento" required
                                    :class="{'form-control': true, 'error': errors.has('Departamento')}"  @change="selectMunicipio()">
                                    <option selected="selected" value="">Departamento...</option>
                                    <option v-for="departamento in arrayDepartamento" v-text="departamento.nomdepartamento"
                                    :key="departamento.iddepartamento" :value="departamento.iddepartamento" ></option>
                                </select>                                
                                <p v-show="errors.has('Departamento')" class="text-error">{{ errors.first('Departamento') }}</p> 
                            </div>

                            <div class="col-md-6" style="text-align:center; font-weight:bold; color:#444; font-size:20px; ">Código Filial:
                                <input  v-model="codfilial" readonly style="border:none; width:70px; font-weight:bold; color:#444;">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label" for="text-input">Filial</label>
                            <div class="col-md-4">
                                <select v-model="idmunicipio" v-validate.initial="'required'" name="Municipio" required
                                    :class="{'form-control': true, 'error': errors.has('Municipio')}">
                                    <option selected="selected" value="">Municipio...</option>
                                    <option v-for="municipio in arrayMunicipio" v-text="municipio.nommunicipio"  
                                    :key="municipio.idmunicipio" :value="municipio.idmunicipio" ></option>
                                </select>
                                <p v-show="errors.has('Municipio')" class="text-error">{{ errors.first('Municipio')}}</p>
                            </div>

                            <label class="col-md-2 form-control-label" for="text-input">Teléfono</label>
                            <div class="col-md-4">
                            <input  v-validate.initial="'required|numeric'"
                                    type="text" 
                                    v-model="telefono" 
                                    class="form-control" 
                                    placeholder="Teléfono(s)"
                                    name="Teléfono">   
                            <p class="text-error">{{ errors.first('Teléfono')}}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 form-control-label" for="text-input">Dirección</label>
                            <div class="col-md-10">
                            <input  v-validate.initial="'required'"
                                    type="text" 
                                    v-model="direccion" 
                                    class="form-control" 
                                    placeholder="Dirección de la filial"
                                    name="Dirección de Filial">
                            <p class="text-error">{{ errors.first('Dirección de Filial')}}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 form-control-label" for="text-input">Presidente</label>
                            <div class="col-md-4">
                                <select v-model="sociopresidente" v-validate.initial="'required'" 
                                    name="Presidente" required
                                    :class="{'form-control':true,'error':errors.has('Presidente')}" >
                                    <option selected="selected" value="">Presidente...</option>
                                    <option v-for="usuario in arrayUsuario" v-text="usuario.nombre+' '+usuario.apaterno"
                                    :key="usuario.idusuario" :value="usuario.idusuario" ></option> 
                                </select>
                                <p v-show="errors.has('Presidente')" class="text-error">{{errors.first('Presidente')}}</p>
                            </div>

                            <label class="col-md-2 form-control-label" for="text-input">Secretario</label>
                            <div class="col-md-4">
                                <select v-model="sociosecretario" v-validate.initial="'required'"
                                    name="Secretario" required 
                                    :class="{'form-control':true, 'error':errors.has('Secretario')}">
                                    <option selected="selected" value="">Secretario...</option>
                                    <option v-for="usuario in arrayUsuario" v-text="usuario.nombre+' '+usuario.apaterno"
                                    :key="usuario.idusuario" :value="usuario.idusuario"></option>
                                </select>
                                <p v-show="errors.has('Secretario')" class="text-error">{{errors.first('Secretario')}} </p>
                            </div>
                        </div>
                    </form>
                </div>        
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    <input  :disabled = "errors.any ()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarFilial()" value="Guardar">
                    <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarFilial()">Actualizar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
    </form>
    <!--Fin del modal-->
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
            iddepartamento:'',
            codfilial:'',
            idmunicipio:'',
            direccion:'',
            telefono:'',
            sociopresidente:'',
            sociosecretario:'',
            arrayDepartamento :[],
            arrayMunicipio :[],
            arrayUsuario :[],
            arrayFilial:[],
            modal : 0,
            tituloModal : '',
            tipoAccion : 0,
            pagination : {
                'total' : 0,
                'current_page' : 0,
                'per_page' : 0,
                'last_page' : 0,
                'from' : 0,
                'to' : 0,
            },
            offset : 3,
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
        listarFilial (page,buscar,criterio){
            let me=this;
            var url= '/ser_filial?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayFilial = respuesta.filiales.data;
                me.pagination= respuesta.pagination;
            });
        },
        selectDepartamento(){
            let me=this;
            var url= '/par_departamento/selectDepartamento';
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayDepartamento = respuesta.departamentos;
            })
        },
        selectMunicipio(){
            let me=this;
            var url= '/par_municipio/selectMunicipio';
            axios.get(url,{params:{'iddepartamento':this.iddepartamento}}).then(function (response) {
                var respuesta= response.data;
                me.arrayMunicipio = respuesta.municipios;
            });
        },
        selectSocioUsuario(){
            let me=this;
            var url='/socio/selectSocioUsuario';
            axios.get(url).then(function(response){
                var respuesta=response.data;
                me.arrayUsuario=respuesta.usuarios;
            })
        },
        cambiarPagina(page,buscar,criterio){
            let me = this;
            me.pagination.current_page = page;
            me.listarFilial(page,buscar,criterio);
        },
        registrarFilial(){
            let me = this;
            axios.post('/ser_filial/registrar',{
                'iddepartamento': this.iddepartamento,
                'idmunicipio': this.idmunicipio,
                'codfilial':this.codfilial,
                'direccion':this.direccion,
                'telefono':this.telefono,
                'sociopresidente':this.sociopresidente,
                'sociosecretario':this.sociosecretario
            }).then(function (response) {
                if(response.data.length){
                    swal('El Valor ya Existe','Ingresa un dato Diferente','error')
                }
                else {
                    me.cerrarModal();
                    me.listarFilial(1,'','');
                }
            });
        },
        
        actualizarFilial(){
            let me = this;
            axios.put('/ser_filial/actualizar',{
                'idfilial': this.idfilial,
                'iddepartamento': this.iddepartamento,
                'idmunicipio':this.idmunicipio,
                'codfilial':this.codfilial,
                'direccion':this.direccion,
                'telefono':this.telefono,
                'sociopresidente':this.sociopresidente,
                'sociosecretario':this.sociosecretario,
            }).then(function (response) {
                if(response.data.length){
                    swal('El Valor ya Existe','Ingresa un dato Diferente','error')
                }
                else {
                    me.cerrarModal();
                    me.listarFilial(1,'','');
                }
            }); 
        }, 
        desactivarFilial(idfilial){
            swal({
                title: 'Esta seguro de desactivar esta Filial?',
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
                    axios.put('/ser_filial/desactivar',{
                        'idfilial': idfilial
                    }).then(function (response) {
                        me.listarFilial(1,'','');
                        swal('Desactivado!','El registro ha sido desactivado con éxito.','success')
                    });
                } 
            });
        },
        activarFilial(idfilial){
            swal({
                title: 'Esta seguro de activar esta Filial?',
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
                    axios.put('/ser_filial/activar',{
                        'idfilial': idfilial
                    }).then(function (response) {
                        me.listarFilial(1,'','nomfilial');
                        swal('Activado!','El registro ha sido activado con éxito.','success')
                    });
                } 
            });
        },
        cerrarModal(){
            this.modal=0;
            this.tituloModal='';
            this.iddepartamento= '';
            this.idmunicipio = '';
            this.direccion='';
            this.telefono='';
            this.sociopresidente='';
            this.sociosecretario='';
        },
        abrirModal(modelo, accion, data = []){ 
            this.modal = 1;
            this.selectDepartamento();
            this.selectMunicipio();
            this.selectSocioUsuario(); 
            
            for (var i=0, max=0; i <data.length; i++) { 
                if (data[i]['idfilial']>max) max=data[i]['idfilial'];
            } max++;
            if(max<10) max='0'+max;

            switch(accion){
                case 'registrar':{
                    this.codfilial='FIL'+max;
                    this.tituloModal = 'Registrar Filial';
                    this.iddepartamento='';
                    this.idmunicipio='';
                    this.telefono='';
                    this.direccion='';
                    this.sociopresidente='';
                    this.sociosecretario='';
                    this.tipoAccion = 1;
                    break;
                }
                case 'actualizar':{
                    this.idfilial=data['idfilial'];
                    this.tituloModal='Actualizar Filial';
                    this.codfilial=data['codfilial'];
                    this.iddepartamento=data['iddepartamento'];
                    this.idmunicipio=data['idmunicipio'];
                    this.telefono=data['telefono'];
                    this.direccion=data['direccion'];
                    this.sociopresidente=data['sociopresidente'];
                    this.sociosecretario=data['sociosecretario'];
                    this.tipoAccion=2;
                    break;
                }
            }
        }
    },
    mounted() {
        this.listarFilial(1,this.buscar,this.criterio);
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

</style>
