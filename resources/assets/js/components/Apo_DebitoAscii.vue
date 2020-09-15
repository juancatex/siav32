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
                        <i class="fa fa-align-justify"></i> Historial Carga Ascii
                        <!--<button type="button" @click="abrirModal('csvdata','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>-->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <!--<div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nomcsvdata">Nombre</option>
                                      
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarCsvdata(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarCsvdata(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>-->
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Lote</th>
                                    <th>Nombre Archivo</th>
                                    <th>Tipo Aporte</th>
                                    <th>Fecha de Aporte</th>
                                    <th>Fecha de Carga</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="csvdata in arrayCsvdata" :key="csvdata.idcsvdata">
                                    <td>
                                        <template v-if="csvdata.activo && csvdata.estado==0">
                                            <button type="button" class="btn btn-danger btn-sm" @click="abrirModal(csvdata.idcsvdata,csvdata.idlote,csvdata.fecha_archivo,csvdata.idasientomaestro)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="csvdata.idlote"></td>
                                    <td v-text="csvdata.csv_filename"></td>
                                    <td v-text="csvdata.descripcion"></td>
                                    <td v-text="csvdata.fecha_archivo"></td>
                                    <td v-text="csvdata.created_at"></td>
                                    
                                    
                                    <td>
                                        <div v-if="csvdata.activo">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Debitado</span>
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
            <!--Inicio del modal debito-->
            
            <div class="validation-component">
            <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" id="modaldebito" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModalDebito+ ' Lote Nº '+textolote+' Fecha: '+ fechaaporte "  ></h4>
                            <button type="button" class="close" @click="cerrarModalDebito()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                              <div class="card">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Se realizara el Debito de los Siguientes Archivos:</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="listarArchivo in arrayArchivo" :key="listarArchivo.idcsvdata">
                                            <td v-text="listarArchivo.csv_filename"></td>
                                        </tr>                                
                                    </tbody>
                                </table>
                             </div>
                            <h4>El Proceso eliminara Todos los Aportes registrados en la carga de archivos.</h4>
                            <div class="form-group row col-sm-12">
                                <div class="col-md-6"></div>
                                <label class="col-md-2 form-control-label" for="text-input">¿Esta Seguro?</label>
                                <label class="switch switch-label switch-pill switch-outline-primary-alt">
                                    <input class="switch-input" type="checkbox" unchecked="" v-model="checkValidacion" >
                                    <span class="switch-slider" data-checked="Si" data-unchecked="No"></span>
                                </label>
                            </div>
                            <!-- <div class="form-row">
                                <div class="form-group row col-sm-6">
                                    <label class="col-md-4 form-control-label" for="text-input">Tipo de Documento</label>
                                    <div class="col-md-8">
                                        <input class="form-control formu-entrada" 
                                            v-validate.initial="'required'"
                                            type="text" v-model="tipodocumentodeb"
                                            name="Tipo Documento Debito">
                                        <span class="text-error">{{ errors.first('Tipo Documento Debito')}}</span>
                                    </div>
                                </div>
                                <div class="form-group row col-sm-6">
                                    <label class="col-md-4 form-control-label" for="text-input">Numero de Documento</label>
                                    <div class="col-md-8">
                                        <input class="form-control formu-entrada" 
                                            v-validate.initial="'required'"
                                            type="text" v-model="numdocumentodeb"
                                            name="Numero Documento Debito">
                                        <span class="text-error">{{ errors.first('Numero Documento Debito')}}</span>
                                    </div>
                                </div>
                                <div class="form-group row col-sm-6">
                                    <label class="col-md-4 form-control-label" for="text-input">Perfil de cuenta:</label>
                                    <div class="col-md-8">
                                        <select class="form-control" 
                                                v-model="idperfilcuentamaestro"
                                                v-validate.initial="'required'"
                                                
                                                name="Perfilcuentamaestro">

                                            <option value="0">Seleccione</option>
                                            <option v-for="perfilcuentamaestro in arrayPerfilcuentamaestro" :key="perfilcuentamaestro.idperfilcuentamaestro" :value="perfilcuentamaestro.idperfilcuentamaestro" v-text="perfilcuentamaestro.nomperfil"></option>
                                        </select>                                        
                                        <span class="text-error">{{ errors.first('Perfilcuentamaestro')}}</span>   
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group row col-md-6">
                                    <label class="col-md-4 form-control-label" for="text-input"></label>
                                    <p class="col-md-8"> </p>
                                </div>
                            </div>
                            <div class="form-row">
                                   <div class="form-group row col-md-12">
                                    <label class="col-md-2 form-control-label" for="text-input">Glosa</label>
                                    <div class="col-md-10">
                                        <input  type="text" 
                                                v-model="obsdebito" 
                                                v-validate.initial="'required'"
                                                class="form-control" 
                                                placeholder="Glosa Debito"
                                                name="obs_debito"> 
                                        <span class="text-error">{{ errors.first('obs_debito')}}</span>  
                                    </div>
                                </div>
                            </div> -->
                            
                                   
                        </div>  
                            
                            
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModalDebito()">Cerrar</button>
                            <!-- modificar boton aceptar -->
                            
                            <button :disabled="!checkValidacion" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarDebito()">Registar</button>
                        </div><!--:disabled="errors.any() || !isCompleted"-->
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        </div>
            <!--Fin del modal-->

            <!--Inicio del modal progreso-->
            
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title" >Progreso...</h6>
                            <!--
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close"></button>

                            <span aria-hidden="true">×</span>
                            -->
                        </div>
                        <div class="modal-body">
                            <div >
                                <div class="col-md-6">                                    
                                    <img :src="'img/gif/cargando.gif'" style="width:100px">                                 
                                </div>    
                            </div>
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

    import Vue from 'vue';
    import VeeValidate from 'vee-validate';
    import * as plugin from './../functions.js';

    export default {
        props:['idmodulo','idventanamodulo'],
        data (){
            return {
                csvdata_id: 0,
                nomcsvdata : '',
                abrvdep : '',
                arrayCsvdata : [],
                modal : 0,
                tituloModalDebito : '',
                tipoAccion : 0,
                errorCsvdata : 0,
                errorMostrarMsjCsvdata : [],
                tipodocumentodeb:'',
                numdocumentodeb:'',
                idperfilcuentamaestro:0,
                arrayPerfilcuentamaestro:[],
                obsdebito:'',
                textolote:'',

                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nomcsvdata',
                buscar : '',
                //modalDebito:0,
                arrayArchivo:[],
                fechaaporte:'',
                lote_id:'',
                reporte_debito_ascii:'',
                checkValidacion:'',
                idasientomaestro:'',
                arrayPermisos:{eliminar:0},
                
                arrayPermisosIn:[]
            }
        },

        formOptions:{
            validateAfterLoad: true,
            validateAfterChanged: true
        },

        computed:{
            isComplete () {
                //console.log( this.idperfilcuentamaestro && this.montodebito && this.obsdebito);
                return this.idperfilcuentamaestro && this.obsdebito;
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
             /* abrirVentanaModalURL(url, title, w, h) { 
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
				}, */
            getRutasReports (){ 
                let me=this;
                var url= '/apo_reportes';
                axios.get(url).then(function (response) {
                     var respuesta= response.data; ;
                    me.reporte_debito_ascii = respuesta.REP_APO_DEBITO_ASCII; 
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectPerfilcuentamaestro(tipooperacion){
                let me=this;
                var url= '/con_perfilcuentamaestro/selectPerfilcuentamaestro?idmodulo='+2;
                axios.get(url).then(function (response) {
                    //console.log(url);
                    var respuesta= response.data;
                    me.arrayPerfilcuentamaestro = respuesta.perfilcuentamaestros;
                })
                .catch(function (error) {
                    console.log(error);
                });
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




            listarCsvdata(page,buscar,criterio){
                let me=this;
                var url= '/csvdata/selectcsvdata?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayCsvdata = respuesta.csvdatas.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (response) {
                    console.log(response);
                });
            },
            listarArchivo(idcsvdata,idlote){
                console.log(idcsvdata+' idcsvdata'+idlote+' idlote')
                let me=this;
                var url='/csvdata/selectlote?idcsvdata='+idcsvdata;
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.arrayArchivo=respuesta.csvarchivos;
                    console.log(respuesta.csvarchivos);
                    me.textolote=idlote;
                })
                .catch(function(response){
                    console.log(response);
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarCsvdata(page,buscar,criterio);
            },
            registrarCsvdata(){
                /*if (this.validarCsvdata()){
                    return;
                }*/
                
                let me = this;

                axios.post('/csvdata/registrar',{
                    'nomcsvdata': this.nomcsvdata,
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
                        me.listarCsvdata(1,'','nomcsvdata');

                    }

                    
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarCsvdata(){
               /*if (this.validarCsvdata()){
                    return;
                }*/
                
                let me = this;

                axios.put('/csvdata/actualizar',{
                    'nomcsvdata': this.nomcsvdata, 
                    'idcsvdata': this.csvdata_id,
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
                        me.listarCsvdata(1,'','nomcsvdata');
                    }
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            desactivarCsvdata(idcsvdata){
               swal({
                title: 'Esta seguro de desactivar este Csvdata?',
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

                    axios.put('/csvdata/desactivar',{
                        'idcsvdata': idcsvdata
                    }).then(function (response) {
                        me.listarCsvdata(1,'','nomcsvdata');
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
            activarCsvdata(idcsvdata){
               swal({
                title: 'Esta seguro de activar este Csvdata?',
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

                    axios.put('/csvdata/activar',{
                        'idcsvdata': idcsvdata
                    }).then(function (response) {
                        me.listarCsvdata(1,'','nomcsvdata');
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
            validarCsvdata(){
                this.errorCsvdata=0;
                this.errorMostrarMsjCsvdata =[];

                if (!this.nomcsvdata) this.errorMostrarMsjCsvdata.push("El nombre del Csvdata no puede estar vacío.");

                if (this.errorMostrarMsjCsvdata.length) this.errorCsvdata = 1;

                return this.errorCsvdata;
            },*/
            resetModalDebito(){
                this.numdocumentodeb='',
                this.tipodocumentodeb='',
                this.obsdebito=''

            },
            cerrarModalDebito(){
                //this.modalDebito=0;
                this.classModal.closeModal('modaldebito');
                this.tituloModal='';
                this.tipodocumentodeb='';
                //this.idperfilcuentamaestro=0;
                this.obsdebito='';
                
            },
            abrirModal(idcsvdata,idlote,fechaporte,asientomaestro_id){
                //this.modalDebito = 1;
                this.classModal.openModal('modaldebito')
                this.lote_id=idlote;
                this.tituloModalDebito = 'Debito de Carga Ascii';
                this.tipoAccion = 1;
                this.fechaaporte=fechaporte;
                this.idasientomaestro=asientomaestro_id;
                this.listarArchivo(idcsvdata,idlote)
                
            },
             comprobantedebito(idlotereturn,reporte_debito_ascii){
                var url=reporte_debito_ascii + idlotereturn; 
                //this.abrirVentanaModalURL(url,"Comprobante Debito",800,700);		
                plugin.viewPDF(url,'Comprobante de Debito');

                //window.open('http://192.168.100.60:8080/birt-viewer/frameset?__report=reportes/apo_reportes/rep_horizontal.rptdesign&numpapeleta='+papeletanum,'_blank');
                //window.open('http://localhost:8080/birt-viewer/frameset?__report=reportes/apo_reportes/rep_horizontal.rptdesign&numpapeleta='+papeletanum,'_blank');
            },
            registrarDebito(){
                let me = this;
                //console.log(this.aporte_id);
                //console.log(this.lote_id)
                me.modal=1;
                axios.post('/csvdata/registrar',{
                    'idlote':me.lote_id,
                    'idperfilcuentamaestro':me.idperfilcuentamaestro,
                    'obsdebito':me.obsdebito,
                    'numdocumentodeb':me.numdocumentodeb,
                    'tipodocumentodeb':me.tipodocumentodeb,
                    'idmodulo':me.idmodulo,
                    'idasientomaestro':me.idasientomaestro
                }).then(function (response) {
                        me.listarCsvdata(1,me.buscar,me.criterio);  
                        me.modal=0;
                        var idlotereturn=response.data;
                        console.log(idlotereturn+' idloteretunr')
                        swal(
                            'Registrado Correctamente'
                       )
                       //me.modalDebito=0;
                       me.classModal.closeModal('modaldebito');
                      
                        
                        //me.listarAporteDebito(1,me.numeropapeleta);
                        me.resetModalDebito();
                        //console.log(iddebito+' iddebito'+ me.reporte_debito)
                        me.comprobantedebito(idlotereturn,me.reporte_debito_ascii);
                        
                        // window.open('http://192.168.100.60:8080/birt-viewer/frameset?__report=reportes/apo_reportes/rep_debito_aportes.rptdesign&iddebito='+iddebito,'_blank');
                       // window.open('http://localhost:8080/birt-viewer/frameset?__report=reportes/apo_reportes/rep_debito_aportes.rptdesign&iddebito='+iddebito,'_blank');
                        
                    
                }).catch(function (error) {
                    swal({
                            text: "Algo Salio Mal",
                            type: "warning",
                    })
                    me.modal=0;
                    me.classModal.closeModal('modaldebito');
                    //me.modalDebito=0;
                    console.log(error);
                });

            },
             getPermisos() {
            var url= '/adm_role/selectPermisos?idmodulo=' + this.idmodulo+'&idventanamodulo='+this.idventanamodulo;
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
            },
        },
        mounted() {
            this.selectPerfilcuentamaestro(this.tipooperacion);
            this.listarCsvdata(1,this.buscar,this.criterio);
            this.getRutasReports();
            this.classModal=new _pl.Modals();
            this.classModal.addModal('modaldebito');
            this.getPermisos();
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
