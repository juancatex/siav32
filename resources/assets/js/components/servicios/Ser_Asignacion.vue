<template>
    <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
    </ol>
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <div class="card-header">
                <div class="form-group row " style="margin-bottom: 0px;">
                    <div class="col-md-2">
                            <div class="form-group row" style="margin-bottom: 0px;padding-left: 30px;">
                            <i class="fa fa-align-justify"></i>&nbsp; Asignacion &nbsp;
                           <!--  <button v-if="check('libro_agregar')" type="button" @click="abrirModalCompras('registrar')" class="btn btn-secondary" :disabled='!ismescerrado || filialselected==0'>
                                <i class="icon-plus"></i>&nbsp;Nuevo
                            </button> -->
                            <!-- &nbsp;&nbsp;&nbsp;<span class="text-error" v-if="filialselected==0">Debe seleccionar una filial para agregar mas facturas</span> -->
                        </div>
                    </div>
                    <div class="col-md-3">
                            <div class="form-group row" style="margin-bottom: 0px;">
                            <div class="col-md-3">
                                <strong class="form-control-label">Filial:</strong>
                            </div>
                            <div class="col-md-8">
                                <select v-model="filialselected"  class="form-control" @change="listarEstablecimiento()">
                                    <option value="0" disabled selected>Todas</option>
                                    <option v-for="filial in arrayFilial" v-bind:key="filial.idfilial" :value="filial.idfilial" v-text="filial.nommunicipio"></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                            <div class="form-group row" style="margin-bottom: 0px;">
                            <div class="col-md-3">
                                <strong class="form-control-label">Servicio:</strong>
                            </div>
                            <div class="col-md-8">
                                <select v-model="establecimientoselected"  class="form-control" @change="listarPisos()">
                                    <option value="0" disabled selected >Seleccionar...</option>
                                    <option v-for="establecimientos in arrayestablecimientos" v-bind:key="establecimientos.idestablecimiento" :value="establecimientos.idestablecimiento" v-text="establecimientos.nomestablecimiento"></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" v-if="respuesta">
                            <div class="form-group row" style="margin-bottom: 0px;">
                            <div class="col-md-3">
                                <strong class="form-control-label">Piso:</strong>
                            </div>
                            <div class="col-md-8">
                                <select v-model="pisoselected"  class="form-control" @change="listarAsignaciones()">
                                    <option v-for="p in arraypisos" v-bind:key="p.pisos" :value="p.pisos" v-text="p.pisos"></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" v-model="buscar" @keyup.enter="listarAsignaciones('buscar')" class="form-control" placeholder="Texto a buscar">
                            <button type="submit" @click="listarAsignaciones('buscar')" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Cod.</th>
                            <th>Descripcion</th>
                            <th>Tarifas</th>
                            <th>Ocupantes</th>
                            <th>fecha hora <br /> Entrada</th>
                            <th>fecha hora <br /> Salida</th>
                            <th>Opciones</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="asignacion in arrayAsignacion" :key="asignacion.idambiente">
                            <td class="card-header titcard" v-text="asignacion.codambiente"></td>
                            <td>{{ asignacion.tipo }} <br /> {{ asignacion.capacidad }}  Camas </td>
                            <td>{{ asignacion.tarifasocio }}Bs. <br /> {{ asignacion.tarifareal }} Bs. </td>
                            <td></td>
                            <td v-text="asignacion.fechaentrada"></td>
                            <td v-text="asignacion.fechasalida"></td>
                            <td>
                                 <button v-if="asignacion.idasignacion==null" type="button" @click="registrarAsignacion()" class="btn btn-success icon-user-follow" data-toggle="tooltip" data-placement="top" title="Asignar Habitacion">
                                </button> &nbsp;
                            </td>
                        </tr>                                
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <form @submit.prevent="validateBeforeSubmit" @keyup.esc="cerrarModal()">
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" class="close" @click="cerrarModal()"  aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" @submit.prevent="validateBeforeSubmit" >
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Nombre del Departamento</label>
                            <div class="col-md-9">
                                <input  v-validate.initial= "'required|alpha_spaces'"   
                                        type="text" 
                                        v-model="nomdepartamento"  @keyup.esc="cerrarModal()"
                                        class="form-control" 
                                        placeholder="Departamento"
                                        name='Nombre Departamento'
                                        autofocus
                                        >  <!-- @keyup.enter="registrarDepartamento()"  para habilitar enter -->
                                <span class="text-error">{{ errors.first('Nombre Departamento')}}</span>   <!--Lineas Agregadas<-->
                            </div>
                        </div>
                        
                    
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Abreviación</label>
                            <div class="col-md-9">
                                <input  v-validate.initial= "'required|alpha_spaces'"   
                                        type="text" 
                                        v-model="abrvdep"  @keyup.esc="cerrarModal()"
                                        class="form-control" 
                                        placeholder="Abreviación"
                                        name='abreviacion'
                                        autofocus
                                        >  <!-- @keyup.enter="registrarDepartamento()"  para habilitar enter -->
                                <span class="text-error">{{ errors.first('abreviacion')}}</span>   <!--Lineas Agregadas<-->
                            </div>
                        </div>
                    

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    <!-- modificar boton aceptar -->
                    <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarDepartamento()">Guardar</button>
                    <button :disabled = "errors.any ()" type="submit" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarDepartamento()">Actualizar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    </form>
    <!--Fin del modal-->
    
</main>

</template>

<script>

    import Vue from 'vue';
    import VeeValidate from 'vee-validate';

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

    export default {
        data (){
            return {
                arrayFilial:[],
                arrayServicios:[],
                filialselected:0,
                establecimientoselected:0,
                arrayAsignacion:[],
                pisoselected:0,
                respuesta:false,
                arrayestablecimientos:[],
                arraypisos:[],
                pisos:0,
                
                


                departamento_id: 0,
                nomdepartamento : '',
                abrvdep : '',
                arrayDepartamento : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorDepartamento : 0,
                errorMostrarMsjDepartamento : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nomdepartamento',
                buscar : ''
            }
        },

        formOptions:{
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
            //metodo agregado para la validacion
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
        ////////////////////////////////////
        listarPisos(){
            let me=this;
            axios.get('ser_ambientes/listarpisos?idestablecimiento='+me.establecimientoselected).then(response=>{
                this.arraypisos=response.data.pisos;
                //console.log(me.arraypisos);
                me.pisoselected=me.arraypisos[0].pisos
                if(me.pisoselected==0)
                    me.respuesta=false;
                else
                    me.respuesta=true; 
                me.listarAsignaciones();

            });
            
        },
        
        listarEstablecimiento(){
            let me=this;
            axios.get('/ser_establecimiento/listaEstablecimientos2?idfilial='+this.filialselected).then(response=>{
                this.arrayestablecimientos=response.data.establecimientos;
            });

            me.serviciosselected=0;

        },
        selectFilial(){
            let me=this;
            var url='/fil_filial/selectFiliales';
            axios.get(url).then(function(response){
                me.arrayFilial=response.data.filiales;
                //console.log(me.arrayFilial);
                
            });
        },
        listarAsignaciones(valor){
            let me=this;
            if(valor=='buscar')
            {

            }
            else
            {
                var url= '/ser_asignacion?idestablecimiento='+me.establecimientoselected+'&piso='+me.pisoselected;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    console.log(respuesta);
                    me.arrayAsignacion = respuesta.asignacion;
                    console.log(arrayAsignacion);
                })
                .catch(function (response) {
                    console.log(response);
                });
            }
            
        },

            /////////////////////////////////
          
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarDepartamento(page,buscar,criterio);
            },
            registrarDepartamento(){
                /*if (this.validarDepartamento()){
                    return;
                }*/
                
                let me = this;

                axios.post('/par_departamento/registrar',{
                    'nomdepartamento': this.nomdepartamento,
                    'abrvdep': this.abrvdep
                    
                }).then(function (response) {
                    if(response.data.length){
                       //console.log(response)
                       swal(
                            'El Valor ya Existe',
                            'Ingresa un dato Diferente',
                            'error'
                       )                    }
                    else{
                        me.cerrarModal();
                        me.listarDepartamento(1,'','nomdepartamento');

                    }

                    
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarDepartamento(){
               /*if (this.validarDepartamento()){
                    return;
                }*/
                
                let me = this;

                axios.put('/par_departamento/actualizar',{
                    'nomdepartamento': this.nomdepartamento, 
                    'iddepartamento': this.departamento_id,
                    'abrvdep': this.abrvdep
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
                        me.listarDepartamento(1,'','nomdepartamento');
                    }
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            desactivarDepartamento(iddepartamento){
               swal({
                title: 'Esta seguro de desactivar este Departamento?',
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

                    axios.put('/par_departamento/desactivar',{
                        'iddepartamento': iddepartamento
                    }).then(function (response) {
                        me.listarDepartamento(1,'','nomdepartamento');
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
            activarDepartamento(iddepartamento){
               swal({
                title: 'Esta seguro de activar este Departamento?',
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

                    axios.put('/par_departamento/activar',{
                        'iddepartamento': iddepartamento
                    }).then(function (response) {
                        me.listarDepartamento(1,'','nomdepartamento');
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

            /*    //////////////   comentar esta funcion ya no se usa
            validarDepartamento(){
                this.errorDepartamento=0;
                this.errorMostrarMsjDepartamento =[];

                if (!this.nomdepartamento) this.errorMostrarMsjDepartamento.push("El nombre del Departamento no puede estar vacío.");

                if (this.errorMostrarMsjDepartamento.length) this.errorDepartamento = 1;

                return this.errorDepartamento;
            },*/
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nomdepartamento='';
                
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "departamento":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Departamento';
                                this.nomdepartamento= '';
                                this.abrvdep= '';
                                
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Departamento';
                                this.tipoAccion=2;
                                this.departamento_id=data['iddepartamento'];
                                this.nomdepartamento = data['nomdepartamento'];
                                this.abrvdep = data['abrvdep'];
                                
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            //this.listarDepartamento(1,this.buscar,this.criterio);
            this.selectFilial();
        }
    }
</script>
