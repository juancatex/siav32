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
                <i class="fa fa-align-justify"></i> Configuracion del Perfil de Cuentas de Aportes
            </div>
            <div class="card-body">
                <div class="form-group row">
                     <div class="col-md-6">
                        <div class="input-group">
                            <strong class="col-md-4">Seleccione Perfil:</strong>
                            <select class="form-control col-md-8" v-model="idperfil" @change="selectPerfilcuentadetalle()">
                                <option value="0">Seleccionar</option>
                                <option v-for="perfil in arrayPerfilcuentamaestro" :key="perfil.idperfilcuentamaestro" :value="perfil.idperfilcuentamaestro" v-text="perfil.nomperfil"></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div v-if="idperfil!=0">
                    <h3>Debe</h3>
                    <table class="table table-bordered table-striped table-sm" style="margin-bottom: 0px;">
                        <thead>
                            <tr>
                                <th style="width:60%">Cuenta</th>
                                <th>Relacion Fuerza</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="perfilcuentadetalled in arrayDetalleD" :key="perfilcuentadetalled.idperfilcuentadetalle" :index="perfilcuentadetalled.idperfilcuentadetalle">
                                <td v-text="perfilcuentadetalled.codcuenta + ' ' +perfilcuentadetalled.nomcuenta"></td>
                                <td v-if="perfilcuentadetalled.relacionfuerza" v-text="perfilcuentadetalled.nomfuerza"></td>
                                <td v-else>
                                    <select class="form-control" 
                                            v-model="idfuerza[index]">
                                        <option value="0"  disabled>Seleccione...</option>
                                        <option value="1">Todos</option>
                                        <option v-for="fuerza in arrayFuerza" :key="fuerza.idfuerza" :value="fuerza.idfuerza" v-text="fuerza.nomfuerza"></option>
                                    </select>    
                                </td>
                            </tr> 
                        </tbody>
                    </table>
                    
                    <hr>
                    <h3>Haber</h3>
                    
                    <table class="table table-bordered table-striped table-sm" style="margin-bottom: 0px;">
                        <thead>
                            <tr>
                                <th style="width:60%">Cuenta</th>
                                <th>Relacion Fuerza</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="perfilcuentadetalleh in arrayDetalleH" :key="perfilcuentadetalleh.idperfilcuentadetalle">
                                <td v-text="perfilcuentadetalleh.codcuenta +' ' + perfilcuentadetalleh.nomcuenta"></td>
                                <td v-if="perfilcuentadetalleh.relacionfuerza" v-text="perfilcuentadetalleh.nomfuerza"></td>
                                <td v-else>
                                </td>
                            </tr> 
                        </tbody>
                    </table>
                </div>
                
                


                
            </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
    </div>
    
    
</main>

</template>

<script>

    import Vue from 'vue';
    import VeeValidate from 'vee-validate';

export default {
    props:['idmodulo'],
    data (){
        
        return {
            relacion:0,
            
                          
            
            arrayResRelaciones:[],
            idcuenta:[],
            clearSelected:1,
            descripcion:'',

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
            buscar : '',

            arrayPerfilcuentamaestro:[],
            idperfil:0,
            arrayDetalle:[],
            arrayDetalleD:[],
            arrayDetalleH:[],
            arrayFuerza:[],
            idfuerza:[],
        }
    },

    formOptions:{
        validateAfterLoad: true,
        validateAfterChanged: true
    },

    computed:{
        iscompleteLB: function(){
            if(this.descripcion && this.idcuenta.length>0)
                return true
            else    
                return false
        },
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
        selectPerfilcuentadetalle(){
            let me=this;
            
            var url= '/con_perfilcuentadetalle/selectPerfilcuentadetalle?idmaestro='+me.idperfil;
            axios.get(url).then(function (response) {
                //console.log(response);
                var respuesta= response.data;
                me.arrayDetalle=respuesta.perfilcuentadetalles;
                me.arrayDetalleD=me.arrayDetalle.filter(elem=>elem.tipocargo=='d')
                me.arrayDetalleH=me.arrayDetalle.filter(elem=>elem.tipocargo=='h')
                //me.recorrerarrayd();
                })
            .catch(function (error) {
                console.log(error);
            });
            
            
            
        },
         listarPerfil(){
            let me=this;
            me.idperfil=0;
            me.arrayAsientomaestro=[];
            var url= '/con_perfilcuentamaestro/selectPerfilcuentamaestro?idmodulo='+me.idmodulo;
            axios.get(url).then(function (response) {
                //console.log(response);
                var respuesta= response.data;
                me.arrayPerfilcuentamaestro = respuesta.perfilcuentamaestros;
            })
            .catch(function (error) {
                console.log(error);
            });
        },

        cuentas(cuentas){ 
            this.idcuenta=[];
            // this.arrayCuenta.push({cuentita:cuentas.idcuenta})         
            //console.log(cuentas);
            for (const key in cuentas) {
                if (cuentas.hasOwnProperty(key)) {
                    const element = cuentas[key];
                    //console.log(element);
                    this.idcuenta.push(element);
                    
                }
            }
            //console.log(this.idcuenta);
            
        },
        clean(){
            this.idcuenta=[];
            //this.idproveedorrespuesta=0;
            //console.log('clean')
        },
        tiempo(){
            this.clearSelected=1;
            },

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




        listarRelaciones(page){
            let me=this;
            var url= '/con_config?page=' + page+'&criterio='+me.relacion;
            //console.log(url);
            
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayResRelaciones= respuesta.relaciones.data;
                me.pagination= respuesta.pagination;
            })
            .catch(function (response) {
                console.log(response);
            });
        },
        cambiarPagina(page,buscar,criterio){
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            //Envia la petición para visualizar la data de esa página
            me.listarDepartamento(page,buscar,criterio);
        },
        registrarRelacion(){
            let me = this;

            axios.post('/con_config/registrar',{
                'codigo': me.relacion,
                'descripcion': this.descripcion,
                'valor': this.idcuenta[0],
            }).then(function (response) {
                swal(
                    'Registrado Correctamente',
                )                    
                me.clearSelected=0;
                setTimeout(me.tiempo, 50); 
                me.descripcion='';
                me.listarRelaciones(1);

               
                
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
        desactivarRelacion(idconconfig){
            swal({
            title: 'Esta seguro de desactivar esta Relacion?',
            text:'No podra hacer seguimiento de esta cuenta',
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

                axios.put('/con_config/desactivar',{
                    'idconconfig': idconconfig
                }).then(function (response) {
                    me.listarRelaciones(1);
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
        this.listarPerfil();
        //this.listarDepartamento(1,this.buscar,this.criterio);
    }
}
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
        /*background-color: #2C3E50;*/
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
