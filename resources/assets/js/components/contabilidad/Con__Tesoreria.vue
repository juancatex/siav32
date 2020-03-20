<template>
    <main class="main">
        <div class="container-fluid" style="padding-top: 10px;">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-3" style="padding-top: 8px;">
                            <i class="fa fa-align-justify"></i> Tesoreria
                        </div>
                        <div :class="tipodesembolso>0?'col-md-4':'col-md-9'">
                            <div class="row">
                                <div class="col-md-5 my-auto" style="text-align: right;">
                                    <strong>Tipo Desembolso:</strong>
                                </div>
                                <div class="col-md-7">
                                    <select class="form-control" v-model="tipodesembolso" @change="listarDesembolso()">
                                        <option value="0" disabled>Seleccionar...</option>
                                        <option v-for="(desembolso,index) in arrayTipoDesembolso" :key="index" :value="desembolso.valor" v-text="desembolso.tipodesembolso"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div v-if="tipodesembolso==1" class="col-md-5 col-form-label " style="text-align: center;border: 1px solid #c2cfd6 !important; border-radius: 5px;background-color: white;">
                            <div class="form-group" style="margin-bottom: 5px;" >
                                <label class="form-check-label" style="margin-right: 15px;">
                                    <input type="radio" class="form-check-input" v-model="desembolsocheck" value="por_desembolsar" checked @change="listarDesembolso()" style="margin-right: 5px;">Por Desembolsar
                                </label>
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" v-model="desembolsocheck" value="ya_desembolsado" @change="listarDesembolso()" style="margin-right: 5px;">Ya Desembolsados
                                </label>
                            </div> 
                        </div>
                        <div v-else-if="tipodesembolso==2" class="col-md-5"> 
                                        <div class="row">
                                            <strong class="col-md-4 my-auto" style="text-align: right;">Seleccionar Perfil:</strong>
                                            <div class="col-md-8">
                                                <select class="form-control" v-model="perfildesembolso" @change="listarDesembolsoPrestamos()">
                                                    <option value="0" disabled>Seleccionar...</option>
                                                    <option v-for="desembolso in arrayPerfilDesembolso" :key="desembolso.idperfilcuentamaestro" :value="desembolso.idperfilcuentamaestro" v-text="desembolso.nomperfil"></option>
                                                </select>
                                            </div>
                                        </div> 
                            </div>


                    </div>
                </div>
                <div class="card-body">
                    <!-- inicio de cargo de cuenta -->
                    <table class="table table-bordered table-striped table-sm" v-if="tipodesembolso==1">
                        <thead class="thead-dark">
                            <tr>
                                <th v-if="desembolsocheck=='ya_desembolsado'">Nº</th>
                                <th v-else style="width:50px">Opciones</th>
                                <th style="width:110px">Fecha Sol.</th>
                                <th>a Nombre de:</th>
                                <th>Nº Cuenta</th>
                                <th style="width:70px">Filial</th>
                                <th >Glosa</th>
                                <th style="width:100px">Monto</th>
                                <th style="width:200px"><span v-if="desembolsocheck=='ya_desembolsado'">Hora Desembolso </span><span v-else>Desembolso/Cheque</span> </th>
                                
                                
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(movimiento,index) in arrayDesembolso" :key="movimiento.idsolccuenta">
                                <td v-if="desembolsocheck=='ya_desembolsado'" style="text-align:right">{{ index+1}}</td>
                                <td v-else>
                                     <button type="button" @click="observarCargo(movimiento.idsolccuenta,1)" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Observar Cargo de Cuenta">
                                        <i class="icon-eye"></i>
                                    </button> 
                                </td>
                                <!-- <td style="text-align:right">{{ index+1}}</td> --> <!-- index+1 + -->
                                <td v-text="movimiento.fecha_solicitud" style="text-align:right"></td>
                                <td v-text="movimiento.nombres"></td>
                                <td v-text="movimiento.nrcuenta"></td>
                                <td v-text="movimiento.sigla"></td>
                                <td v-text="movimiento.glosa"></td>
                                <td style="text-align:right">{{movimiento.monto | currency}}</td><!--  -->
                                <td v-if="desembolsocheck=='ya_desembolsado'">
                                    <span  v-text="movimiento.fecha_desembolso"></span>
                                </td>                              
                                <td v-else-if="desembolsocheck=='por_desembolsar'">
                                    
                                        <label class="switch switch-label switch-pill switch-outline-primary-alt">
                                            <input class="switch-input" type="checkbox" unchecked="" v-model="checkValidacion[index]" value="movimiento.idsolccuenta" >
                                            <span class="switch-slider" data-checked="Si" data-unchecked="No"></span>
                                        </label>
                                        <input v-if="checkValidacion[index]" type="text"  v-model="num_documento[index]" placeholder="Nº Cheque" style="width:100px;text-align: right;">
                                        <!--  -->
                                    <!-- <input type="checkbox" class="form-check-input" style="margin-left: 5px;"  v-model="checkValidacion" :value="movimiento.idsolccuenta">
                                    <input type="text" class="form-control" v-model="documento[checkValidacion]"> -->
                                </td>                                  
                            </tr> 
                            <tr v-if="tipodesembolso==1 && desembolsocheck=='por_desembolsar'">
                                <td colspan="4" style="text-align:right"><span class="text-error" v-if="cuentasconciliacion==0">Debe Seleccionar la Cuenta de Desembolso</span></td>
                                <td colspan="3" style="text-align:right">
                                    <select class="form-control" v-model="cuentasconciliacion">
                                        <option value="0" disabled>Seleccionar Cuenta de Desembolso</option>
                                        <option v-for="cconciliacion in arrayCuentasC" :key="cconciliacion.valor" :value="cconciliacion.valor" v-text="cconciliacion.nomcuenta"></option>
                                    </select>
                                </td>
                                <td style="text-align:center"><button class="btn btn-primary" type="button" @click="registrarDesembolso(1)" :disabled="cuentasconciliacion==0 || !vercheckvalidacion">
                                            Desembolsar
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- fin de cargo de cuenta -->
                     <!-- inicio prestamos -->
    <div v-if="perfildesembolso>0" class="form-group row" style="justify-content: flex-end;">
            <div v-if="tipodesembolso==2" class="col-md-8">
              <div class="input-group" style="align-items: center;">
                <p style="text-align: right;margin: 0px; margin-right: 10px; font-weight: 500;">Criterio de busqueda:
                </p>
                <input type="text" v-model="buscar" @keyup.enter="listarDesembolsoPrestamos()" class="form-control"
                  placeholder="Nombres , Numero de Prestamos , Apellidos , Numero de Papeleta , lote , Fecha Solicitud (YYYY-MM-DD)" />
                <button type="submit" @click="listarDesembolsoPrestamos()" class="btn btn-primary">
                  <i class="fa fa-search"></i> Buscar
                </button>
              </div>
            </div>
          </div>
                        <div v-if="perfildesembolso>0" class="row" style="text-align: center;margin:15px;"> 
                            <div v-if="tipodesembolso==2" class="col-md-12 col-form-label " style="border: 1px solid #c2cfd6 !important; border-radius: 5px;background-color: white;">
                                    <div>
                                        <label class="form-check-label" style="margin-right: 15px;">
                                            <input type="radio" class="form-check-input" v-model="desembolsocheck" value="por_desembolsar" checked @change="listarDesembolsoPrestamos()" style="margin-right: 5px;">Por Desembolsar
                                        </label>
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" v-model="desembolsocheck" value="ya_desembolsado" @change="listarDesembolsoPrestamos()" style="margin-right: 5px;">Ya Desembolsados
                                        </label> 
                                    </div>
                            </div>
                        </div>
                        

                    <table class="table table-bordered table-striped table-sm" v-if="tipodesembolso==2">
                        <thead class="thead-dark">
                            <tr><th>Nº</th> 
                                <th v-if="desembolsocheck!='ya_desembolsado'" style="width:50px">Opciones</th>
                                <th style="width:110px">Fecha Sol.</th>
                                <th style="min-width:90px">Papeleta</th>
                                <th style="min-width:270px">a Nombre de:</th> 
                                <th>Nº Cuenta</th>
                                <th style="width:130px"># Prestamo</th>
                                <th >Glosa</th>
                                <th style="width:100px">Monto</th>
                                <th style="width:70px">Lote</th>
                                <th style="width:170px"><span v-if="desembolsocheck=='ya_desembolsado'">Hora Desembolso </span><span v-else>Desembolso/Cheque</span> </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(movimiento,index) in arrayPrestamos" :key="movimiento.idasientomaestro" v-bind:class="[movimiento.estado==3 ? 'table-danger' :true , false]">
                                <td  style="vertical-align: middle;text-align: center;">{{ index+1}}</td> 
                                <td v-if="desembolsocheck!='ya_desembolsado'" style="vertical-align: middle;text-align: center;">
                                    <button type="button" v-if="movimiento.estado!=3" @click="observarCargo(movimiento.idasientomaestro,2)" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Observar Desembolso">
                                        <i class="icon-eye"></i>
                                    </button> 
                                    <button type="button" v-else @click="observado_view(movimiento.observaciones)" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Ver Observacion">
                                        <i class="icon-eye"></i>
                                    </button> 
                                </td>
                                <td v-text="movimiento.fecharegistro" style="text-align:right;vertical-align: middle;"></td>
                                 <td v-text="movimiento.numpapeleta" style="vertical-align: middle;text-align: center;"></td>
                                <td v-text="movimiento.nomsocio"  style="vertical-align: middle; "></td> 
                                <td v-text="movimiento.numcuentasocio" style="vertical-align: middle;text-align: center;"></td>
                                <td v-text="movimiento.numdocumento" style="vertical-align: middle;"></td>
                                <td v-text="movimiento.glosa"  style="vertical-align: middle;"></td>
                                <td style="text-align:right;vertical-align: middle;">{{movimiento.monto | currency}}</td> 
                                <td style="text-align: center;vertical-align: middle;"> <h5>{{movimiento.lote}}</h5></td>
                                <td v-if="desembolsocheck=='ya_desembolsado'" style="vertical-align: middle;text-align: center;">
                                    <span  v-text="movimiento.fechahora_desembolso"></span>
                                </td>                              
                                <td v-else-if="desembolsocheck=='por_desembolsar'" style="vertical-align: middle;text-align: center;">
                                    <label class="switch switch-label switch-pill switch-outline-primary-alt" style="margin: 0;">
                                        <input class="switch-input" type="checkbox" unchecked="" v-model="checkValidacion[index]" value="movimiento.idasientomaestro" :disabled="movimiento.estado==3">
                                        <span class="switch-slider" data-checked="Si" data-unchecked="No"></span>
                                    </label>
                                    <input v-if="checkValidacion[index]" type="text"  v-model="num_documento[index]" placeholder="Nº Cheque" style="text-align: right;">
                                </td>                                  
                            </tr> 
                            
                        </tbody>
                    </table>
                     
                  
                    <!-- fin prestamos -->
                </div>
            </div>
             
                <div id="inferior" class="position-fixed animated slideInUp"  v-if="checkvalidate&&tipodesembolso==2&&desembolsocheck=='por_desembolsar' && arrayPrestamos.length!=0">
                                
                                
                                    <div class="col-md-5 row" style="float: right;float: right;    padding: 10px;    background-color: rgb(33, 43, 49);    color: white;">
                                            <div class="col-md-8 my-auto">     
                                                <span style="padding-right: 8px;">Cuenta de Desembolso:</span>
                                                <strong style="font-size: 15px;" v-text="cuentasconciliacion.codcuenta + ' - '+ cuentasconciliacion.nomcuenta"></strong> 
                                            </div>
                                            <div class="col-md-4"><button class="btn btn-primary   btn-block" style="font-size: large;" type="button" 
                                            @click="registrarDesembolso(2)" :disabled="!cuentasconciliacion || !vercheckvalidacion">
                                                        Desembolsar
                                                </button>
                                            </div>
                                    </div>
                                  
                                
                  </div> 
        </div>
    </main>
</template>

<script>
    import Vue from 'vue';
    import VeeValidate from 'vee-validate';
    import VueNumeric from 'vue-numeric';
    import VueCurrencyFilter from 'vue-currency-filter'
    Vue.use(VueCurrencyFilter,
    {
        symbol : '',
        thousandsSeparator: '.',
        fractionCount: 2,
        fractionSeparator: ',',
        symbolPosition: 'front',
        symbolSpacing: true
    })

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
                arrayTipoDesembolso:[{valor:1,tipodesembolso:'Cargo de Cuenta'},
                                        {valor:2,tipodesembolso:'Prestamos'}],
                desembolsocheck:'por_desembolsar',
                arrayCargoscuenta:[],
                arrayCCDesembolsados:[],
                arrayCCNodesembolsados:[],
                tipodesembolso:0,
                checkValidacion:[],
                documento:[],
                num_documento:[],
                arrayDesembolsados:[],
                arrayResultado:[],
                arrayDatosDesembolso:[],
                perfildesembolso:0,
                arrayPerfilDesembolso:[],
                arrayPrestamos:[],
                arraycuentasHaber:[],
                buscar:'',

                arrayCuentasC:[],
                cuentasconciliacion:0,
                criterio:'',
                
                buscar:'',
                fechaselected:'',
                fecha_movimiento:'',
                fecha_movimientodeb:'',
                fechaactual:'',
                nomcuenta:'',
                codcuenta:'',
                idcuenta:'',
                arrayMovimientos:[],
                concepto:'',
                conceptodeb:'',
                iddepartamento:2,
                iddepartamentodeb:2,
                num_operacion:'',
                num_operaciondeb:'',
                monto:0,
                montodeb:0,
                
                tituloModal:'',
                numero:0,
                arrayMovimientosCred:[],
                arrayMovimientosDeb:[],
                arrayDebitosNoBanco:[],
                fechainicio:'',
                fechafin:'',
                saldo:0,
                arrayCheques:[],
                montobanco:0,
                fecha_movimientodebNoBanco:'',
                conceptodebNoBanco:'',
                iddepartamentodebNoBanco:2,
                num_operaciondebNoBanco:'',
                montodebNoBanco:0,
                boton:true

            }
        },

        formOptions:{
            validateAfterLoad: true,
            validateAfterChanged: true
        },

        computed:{
            vercheckvalidacion(){
                let me=this;
                var valor=false;
                me.checkValidacion.forEach(element => {
                    if(element)
                        valor=true;
                    
                   
                });
                //console.log(valor);
                return valor


            },
            checkvalidate(){ 
                return this.boton;
            },
            arrayDesembolso(){
                let me=this;
                me.arrayResultado=[];
                if (me.tipodesembolso==1)
                {
                    if(me.desembolsocheck=='por_desembolsar')
                    {
                        var arrayResultado=me.arrayCargoscuenta.filter(obj=>{
                            if(obj.estado_aprobado==0)
                                return obj
                        });
                    }
                    if(me.desembolsocheck=='ya_desembolsado')
                    {
                        var arrayResultado=me.arrayCargoscuenta.filter(obj=>{
                            if(obj.estado_aprobado!=0)
                            return obj
                        });
                        arrayResultado.sort((a, b) => new Date(b.fecha_desembolso) - new Date(a.fecha_desembolso));
                        arrayResultado.splice(30);
                        
                    }
                } 
                me.arrayResultado=arrayResultado;
                return arrayResultado;  
            },
            
        },
        methods : {
            observado_view(observado){
                //console.log(pr);
                let me=this;
                swal({
                title: '¡Observaciones!',
                html:   '<div class="form-group row "> ' +
                        '<div class="col-md-12"> <div class="input-group"> <textarea style="    background: transparent;"  class="form-control"  placeholder="Observaciones" autofocus readonly>'+
                        observado+'</textarea></div> </div></div>',
                type:"info",
                showCancelButton: false,
                confirmButtonColor: "#4dbd74",
                cancelButtonColor: "#f86c6b",
                confirmButtonText: "Ok", 
                buttonsStyling: true,
                reverseButtons: true
                });
            },
            observarCargo(id,tipo){
                let me=this;
                swal({
                title: 'Esta seguro de Observar Esta Solicitud?',
                html:   '<div class="form-group row "> <label style="text-align: right; align-items: center;" class="col-md-3 form-control-label" for="text-input">Obs.: </label>' +
                        '<div class="col-md-9"> <div class="input-group"> <textarea  id="observacionswal" class="form-control"  placeholder="Observaciones" autofocus step="any"></textarea></div> </div></div>',
                type: 'warning',
                showConfirmButton: true,
                allowOutsideClick: () => false,
                allowEscapeKey: () => false,
                showCancelButton: true,
                showLoaderOnConfirm: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                buttonsStyling: true,
                reverseButtons: true,
                onOpen:function(){
                    swal.disableConfirmButton();
                    $('#observacionswal').on("input",function(){
                        var oInput = this.value;
                        if(oInput!='')
                        {
                            swal.enableConfirmButton();
                            me.observado=oInput;
                        }
                        else swal.disableConfirmButton();
                    });

                },
                
                }).then((result) => {
                    if (result.value) {
                        let me = this;
                        if(tipo==1)
                        {
                            axios.put('/glo_solccuenta/observar',{
                                'idsolccuenta': id,
                                'observado':me.observado
                            }).then(function (response) {
                                me.listarDesembolso()
                                swal(
                                'Observado!',
                                'El registro ha sido Observado correctamente.',
                                'success'
                                )
                            }).catch(function (error) {
                                console.log(error);
                            });
                        }
                        else if(tipo==2)
                        {
                            axios.put('/con_asientomaestro/observar',{
                                'idasientomaestro': id,
                                'observado':me.observado
                            }).then(function (response) {
                                me.listarDesembolsoPrestamos()
                                swal(
                                'Observado!',
                                'El registro ha sido Observado correctamente.',
                                'success'
                                )
                            }).catch(function (error) {
                                console.log(error);
                            });
                        }
                        
                    } 
                    else if (result.dismiss === swal.DismissReason.cancel)
                    {
                        
                    }
                }) 
            },
            desactivarMovimientos(segbancario_id){
                //console.log(segbancario_id);
                swal({
                title: 'Esta seguro de Eliminar este Registro?',
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
                    if (result.value) 
                    {
                        let me = this;
                        axios.put('/con_segbancario/actualizar',{
                            'idsegbancario': segbancario_id, 
                        }).then(function (response) {
                            if(response.data.length){
                                swal(
                                    'El Valor ya Existe',
                                    'Ingresa un dato Diferente',
                                    'error'
                                )
                            }
                            else{
                                me.listarMovimiento();
                            }
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }
                    else if (result.dismiss === swal.DismissReason.cancel) 
                    {

                    }
                })
            },
            selectAll: function (event) {
      
                setTimeout(function () {
                    event.target.select()
                }, 0)
            },
          
            selectConciliacion(){
                let me=this;
                var url= '/con_config/selectconciliacion';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayCuentasC = respuesta.cuentaslibros;
                })
                .catch(function (response) {
                    console.log(response);
                });
            },
            listarDesembolso(){
                let me=this;
                this.buscar='';
                switch (me.tipodesembolso) {
                    case 1:
                        var url= '/glo_solccuenta/listartesoreria';
                        //console.log(url);
                        axios.get(url).then(function (response) {
                            var respuesta= response.data; 
                            me.arrayCargoscuenta=respuesta.cargocuentas;
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                    break;
                    case 2:
                        //para desembolso de prestamos
                        url= '/con_perfilcuentamaestro/selectcuentatesoreria';
                        //console.log(url);
                        axios.get(url).then(function (response) {
                            var respuesta= response.data; 
                            //console.log(respuesta); 
                            me.arrayPerfilDesembolso=respuesta.perfilcuentamaestros;
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                    break;
                }
            },
            listarDesembolsoPrestamos(){
                this.arrayPrestamos=[];
                var estadodesembolso=0;//id de modulo prestamos
                let me=this;
                me.checkValidacion=[];
                me.num_documento=[];
                if(me.desembolsocheck=='por_desembolsar')
                    estadodesembolso=0;
                else
                    estadodesembolso=1;
                
                var url= '/con_asientomaestro/selectdesembolso?idperfil='+me.perfildesembolso+'&estadodesembolso='+estadodesembolso+'&buscar='+me.buscar;
                //console.log(url);
                axios.get(url).then(function (response) {
                    var respuesta= response.data; 
                    me.cuentasconciliacion='';
                    me.arrayPrestamos=respuesta.desembolsados;
                    me.arraycuentasHaber=respuesta.cuentashaber;
                    me.arrayCuentasC.forEach(element => {
                       me.arraycuentasHaber.forEach(element2=>{
                           if(element.valor==element2.idcuenta)
                                me.cuentasconciliacion=element;

                       });
                            
                    });
                    //console.log(cuentasconciliacion);
                    if(!me.cuentasconciliacion)
                    swal(
                            'El Perfil No Tiene Seguimiento Bancario',
                            'Cuenta no asociada',
                            'error'
                       ) 
                        //console.log('no existe cuenta de desembolso');
                        

                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            obtenerfecha(){
                let me = this;
                var url= '/getdate?modal=1';
                axios.get(url).then(function (response) {
                    var respuesta= response.data; 
                    me.fechaactual=respuesta[0]['fecha'];
                    var arrayfechas = me.fechaactual.split("-");
                    me.anio=arrayfechas[0];//año
                    me.mes=arrayfechas[1];//mes
                    me.dia=arrayfechas[2];//dia
                    me.fechainicio=me.anio+'-01-01';
                    me.fechaselected=me.fechaactual;
                    me.fecha_movimiento=me.fechaactual;
                    me.fecha_movimientodeb=me.fechaactual;
                    me.fecha_movimientodebNoBanco=me.fechaactual;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            registrarDesembolso(valor){
                this.boton=false;
                swal({
                        title: "Registrando los datos",
                        allowOutsideClick: () => false,
                        allowEscapeKey: () => false,
                        onOpen: function () {
                            swal.showLoading();
                        }
                    }).catch(error => {
                        swal.showValidationError('Request failed: ${error}')
                    });
                let me=this;
                me.arrayDatosDesembolso=[];
                var cheque='';
                switch (valor) {
                    case 1:
                    {
                        me.checkValidacion.forEach((element,index) => {
                        if(element)
                        {
                            if(me.num_documento[index])
                                cheque=me.num_documento[index];
                            else
                                cheque='';
                            me.arrayDatosDesembolso.push({idsolccuenta:me.arrayResultado[index].idsolccuenta,
                                                        num_cheque:cheque,
                                                        portador:me.arrayResultado[index].nombres,
                                                        importe:me.arrayResultado[index].monto});    
                        }
                        });
                        //console.log(me.arrayDatosDesembolso);
                        if(me.tipodesembolso==1)
                        {
                            axios.put('/glo_solccuenta/desembolsar',{
                                'arrayids': me.arrayDatosDesembolso,
                                'idcuentadesembolso':me.cuentasconciliacion
                            }).then(function (response) {
                                me.boton=true;
                                if(response.data.length){
                                //console.log(response)
                                swal(
                                        'El Valor ya Existe',
                                        'Ingresa un dato Diferente',
                                        'error'
                                )                    
                                }
                                else{
                                    me.cuentasconciliacion=0;
                                    me.checkValidacion=[];
                                    me.num_documento=[];
                                    me.listarDesembolso();
                                    swal(
                                        'Registrado Correctamente',
                                        ) ;
                                

                                }
                            }).catch(function (error) {
                                console.log(error);
                                me.boton=true;
                            });   
                        }
                        break;
                    }
                    case 2:
                    {
                        me.checkValidacion.forEach((element,index) => {
                        if(element)
                        {
                            if(me.num_documento[index])
                                cheque=me.num_documento[index];
                            else
                                cheque='';
                            
                            me.arrayDatosDesembolso.push({idasientomaestro:me.arrayPrestamos[index].idasientomaestro,
                                                        num_cheque:cheque,
                                                        portador:me.arrayPrestamos[index].nomsocio,
                                                        importe:me.arrayPrestamos[index].monto});    
                        }
                        });
                        //console.log(me.arrayDatosDesembolso);
                        axios.put('/con_asientomaestro/desembolsar',{
                            'arrayids': me.arrayDatosDesembolso,
                            'idcuentadesembolso':me.cuentasconciliacion.valor
                        }).then(function (response) {
                            me.boton=true;
                            if(response.data.length){
                            //console.log(response)
                            swal(
                                    'El Valor ya Existe',
                                    'Ingresa un dato Diferente',
                                    'error'
                            )                    
                            }
                            else{
                                me.cuentasconciliacion=0;
                                me.checkValidacion=[];
                                me.num_documento=[];
                                me.listarDesembolsoPrestamos();
                                swal(
                                    'Registrado Correctamente',
                                    ) ;
                            

                            }
                        }).catch(function (error) {
                            console.log(error);
                            me.boton=true;
                        });   
                        
                        break;
                    }
                }
            },
        },
        mounted() {
            this.obtenerfecha();
            
            this.selectConciliacion();
            this.classModal=new _pl.Modals();
            this.classModal.addModal('registrarmov');
            
        }
    }
</script>
<style >
.rojo{
    color:red;

}
 #inferior{ 
position:absolute; /*El div será ubicado con relación a la pantalla*/
left:0px; /*A la derecha deje un espacio de 0px*/
right:0px; /*A la izquierda deje un espacio de 0px*/
bottom:0px; /*Abajo deje un espacio de 0px*/ 
z-index:99999;
 }
</style>
