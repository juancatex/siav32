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
                <i class="fa fa-align-justify"></i> Configuracion de Cuentas
                <!-- <button type="button" @click="abrirModal('departamento','registrar')" class="btn btn-secondary">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button> -->
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">
                        <strong class="col-md-4 form-control-label" style="margin-bottom: 0px;margin-top: 8px;" >Tipo de Relacion:</strong>
                        <select v-model="relacion"  class="form-control" @change="listarRelaciones(1)">
                                <!-- <option value="0" selected disabled>Seleccionar...</option> -->
                                <option v-for="relacionArray in arrayRelacion" v-bind:key="relacionArray.abreviacion" :value="relacionArray" v-text="relacionArray.descripcion"></option>
                            </select>   
                        </div>
                    </div>
                </div>
                <hr>
                <div v-if="relacion.abreviacion=='CC'" class="">
                    <h5>Limite de Descargo en Dias</h5>
                    <div class="col-md-12 form-group row " >
                        <label for="" class="col-3">Limite en dias Oficina Regional:</label>
                        <input type="number" v-model="ccdiasor" style="text-align:right; padding" class="form-control col-1" >
                        <span class="text-error" v-if="ccdiasor=='' || ccdiasor==0">* Debe Introducir el Numero de dias</span>
                    </div>
                    <div class="col-md-12 form-group row ">
                        <label for="" class="col-3">Limite en dias Oficina Central:</label>
                        <input type="number" v-model="ccdiasoc" style="text-align:right" class="form-control col-1">
                        <span class="text-error" v-if="ccdiasoc=='' || ccdiasoc==''">* Debe Introducir el Numero de dias</span>
                        <button type="button" v-if="ccdiasoc!='' && ccdiasoc!='' && arrayResRelaciones.length>0" @click="actualizardias()" class="btn btn-success btn-sm col-2" style="margin-left: 20px;">Actualizar Nº de Dias</button>
                    </div>
                </div>
                <div v-else-if="relacion.tipoconfiguracion==1">
                     <div class="col-md-12 form-group row " >
                        <label for="" class="col-6">Porcentaje para: {{ relacion.descripcion }}:</label>
                        <input type="number" v-model="porcentaje" style="text-align:right; padding" class="form-control col-1" :disabled="porcentaje1!=0" >
                        <span class="text-error" v-if="porcentaje==0">* Debe Introducir el Porcentaje</span>
                    </div>

                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Descripcion</th>
                            <th>Cuenta Relacionada</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="relaciones in arrayResRelaciones" :key="relaciones.idconconfig">
                            <td v-text="relaciones.descripcion"></td>
                            <td v-text="relaciones.codcuenta + ' - ' +relaciones.nomcuenta"></td>
                            <td>
                                <button type="button" @click="desactivarRelacion(relaciones.idconconfig)" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Eliminar Relacion">
                                    <i class="icon-trash"></i>
                                </button> &nbsp;
                            </td>
                        </tr>   
                    <template v-if="relacion.abreviacion=='LB' || (arrayResRelaciones.length==0 && relacion.abreviacion!=0)">
                        <tr>
                            <td><input type="text" class="form-control" autofocus v-model="descripcion"> </td>
                            <td><Ajaxselect  v-if="clearSelected"
                                        ruta="/con_cuentas/selectBuscarcuenta2?buscar=" @found="cuentas" @cleaning="clean"
                                        resp_ruta="cuentas"
                                        labels="cuentas"
                                        placeholder="Ingrese texto" 
                                        idtabla="idcuenta"
                                        :clearable='true'
                                        style="background-color: #fff;">

                                    </Ajaxselect></td>
                           <td><button type="button" :disabled="!iscompleteLB" @click="registrarRelacion()" class="btn btn-success btn-sm">
                                <i class="icon-plus"></i>
                            </button> &nbsp;
                            </td>
                        </tr>
                    </template>

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

export default {
    data (){
        return {
            relacion:[],
            arrayRelacion:[
                            {abreviacion:"LV",descripcion:"Libro de Ventas Debito Fiscal",tipoconfiguracion:'1'},
                            {abreviacion:"IT",descripcion:"Impuesto a las trancciones",tipoconfiguracion:'1'},
                            {abreviacion:"ITXP",descripcion:"Impuesto a las Transacciones por pagar",tipoconfiguracion:'1'},
                            {abreviacion:"HXC",descripcion:"Hospedaje por Cobrar Libro de ventas",tipoconfiguracion:'7'},
                            {abreviacion:"LC",descripcion:"Libro de Compras Credito Fiscal",tipoconfiguracion:'2'},
                            {abreviacion:"CC",descripcion:"Cargo de Cuenta",tipoconfiguracion:'4'},
                            {abreviacion:"LB",descripcion:"Libreta Bancaria",tipoconfiguracion:'3'},
                            {abreviacion:"EJD",descripcion:"Aportes Ejercito Debe",tipoconfiguracion:'5'},
                            {abreviacion:"AED",descripcion:"Aportes Aerea Debe",tipoconfiguracion:'5'},
                            {abreviacion:"ARD",descripcion:"Aportes Armada Debe",tipoconfiguracion:'5'},
                            {abreviacion:"EJH",descripcion:"Aportes Ejercito haber",tipoconfiguracion:'5'},
                            {abreviacion:"AEH",descripcion:"Aportes Aerea haber",tipoconfiguracion:'5'},
                            {abreviacion:"ARH",descripcion:"Aportes Armada haber",tipoconfiguracion:'5'}
                          ],
            
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
            ccdiasor:'',
            ccdiasoc:'',
            porcentaje:0,
            porcentaje1:0
        }
    },

    formOptions:{
        validateAfterLoad: true,
        validateAfterChanged: true
    },

    computed:{
       /*  siporcentaje(){
            let me = this;
            if(me.arrayResRelaciones[0].valor2==0)
                return false;
            else
                return true;
        }, */
        iscompleteLB: function(){
            let me=this;
            if(me.relacion.abreviacion=='CC')
            {
                if(me.descripcion && me.idcuenta.length>0 && me.ccdiasor && me.ccdiasoc)
                    return true;
                else    
                    return false;    
            }
            else
            {
                if(me.relacion.tipoconfiguracion==1)
                    if(me.descripcion && me.idcuenta.length>0 && me.porcentaje!=0)
                        return true;
                    else
                        return false;
                    
                if(me.descripcion && me.idcuenta.length>0)
                    return true
                else    
                    return false
            }
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
        actualizardias(){
            let me = this;
           axios.post('/con_config/desactivardias',{
                'ccdiasor': me.ccdiasor,
                'ccdiasoc': me.ccdiasoc,
            }).then(function (response) {
                swal(
                    'Registrado Correctamente',
                )                    
                
                me.listarRelaciones(1);
            }).catch(function (error) {
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
            let dias=false;
            //let porcentaje=false;
            //console.log(me.relacion.abreviacion);
            if(me.relacion.abreviacion=='CC')
                dias=true;
            var url= '/con_config?page=' + page+'&criterio='+me.relacion.abreviacion+'&dias='+dias;
            //console.log(url);
            
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayResRelaciones= respuesta.relaciones.data;
                me.pagination= respuesta.pagination;
                me.ccdiasoc=respuesta.diasoc;
                me.ccdiasor=respuesta.diasor;
                me.porcentaje1=0;
                me.porcentaje=0;
                
                //console.log(me.arrayResRelaciones[0].tipoconfiguracion);
                if(me.arrayResRelaciones[0].tipoconfiguracion==1)
                {   
                    me.porcentaje1=me.arrayResRelaciones[0].valor2;
                    me.porcentaje=me.porcentaje1;

                }

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
            console.log(me.relacion.abreviacion);
            if(me.relacion.abreviacion=='CC')
            {
               axios.post('/con_config/registrar',{
                   'codigo':'ccdiasor',
                   'descripcion':'Cantidad de Dias oficina regional',
                   'valor':me.ccdiasor,
                   'tipoconfiguracion':me.relacion.tipoconfiguracion
               }).then(function (response){}).catch(function(error){
                   console.log(error);
               });
               axios.post('/con_config/registrar',{
                   'codigo':'ccdiasoc',
                   'descripcion':'Cantidad de Dias oficina Central',
                   'valor':me.ccdiasoc,
                   'tipoconfiguracion':me.relacion.tipoconfiguracion
               }).then(function (response){}).catch(function(error){
                   console.log(error);
               });
               me.ccdiasor='';
               me.ccdiasoc='';
            }

            axios.post('/con_config/registrar',{
                'codigo': me.relacion.abreviacion,
                'descripcion': this.descripcion,
                'valor': this.idcuenta[0],
                'descripcion2':'porcentaje',
                'valor2':this.porcentaje,
                'tipoconfiguracion':me.relacion.tipoconfiguracion
                //'tipoconfiguracion':
            }).then(function (response) {
                swal(
                    'Registrado Correctamente',
                )                    
                me.clearSelected=0;
                setTimeout(me.tiempo, 50); 
                me.descripcion='';
                me.porcentaje=0;
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
            let dias=false;
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
                if(me.relacion.abreviacion=='CC')
                {
                    dias=true;
                }

                axios.put('/con_config/desactivar',{
                    'idconconfig': idconconfig,
                    'dias':dias
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
