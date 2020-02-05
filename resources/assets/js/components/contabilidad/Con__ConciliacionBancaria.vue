<template>
    <main class="main">
        <div class="container-fluid" style="padding-top: 10px;">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-2" style="padding-top: 8px;">
                            <i class="fa fa-align-justify"></i> Concliacion Bancaria
                        </div>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-6" style="padding-top: 8px;padding-right: 0px;">
                                    <strong>Fecha de Corte:</strong>
                                </div>
                                <div class="col-md-6" style="padding-left: 0px;padding-right: 0px;">
                                    <input class="form-control" 
                                    type="date" v-model="fechaselected"
                                    :max="fechaactual">
                                </div>
                                
                                
                                <!-- <span class="text-error" v-if="!verificarfechainicio">La Fecha debe ser menor a la fecha actual o fecha final</span> -->
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-3" style="padding-top: 8px;">
                                    <strong>Seleccionar Cuenta:</strong>
                                </div>
                                <div class="col-md-9">
                                    <select class="form-control" v-model="cuentasconciliacion" @change="listarMovimiento()">
                                        <option value="0" disabled>Seleccionar...</option>
                                        <option v-for="cconciliacion in arrayCuentasC" :key="cconciliacion.valor" :value="cconciliacion.valor" v-text="cconciliacion.nomcuenta"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                                       
                </div>
                <div class="card-body" v-if="cuentasconciliacion!=0">
                    <div class="form-group row">
                        <div class="col-md-7">
                            <div class="input-group">
                                <strong>Cuenta:&nbsp;&nbsp; </strong><label  v-text="codcuenta+' - ' +nomcuenta"></label>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <strong>Saldo segun Empresa:&nbsp;&nbsp;&nbsp;</strong>
                                <div style="text-align:right;width:120px">
                                    <label :class="[saldo<0?'rojo':'']" style="font-size:15px"><strong>{{saldo | currency}}</strong></label>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    
                        <h6> + Creditos del Banco no Registrados por la Empresa</h6>
                        <table class="table table-bordered table-striped table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th style="width:50px">Nº</th>
                                    <th style="width:160px">Fecha</th>
                                    <th>Concepto</th>
                                    <th style="width:100px">Depto.</th>
                                    <th style="width:100px">Operacion</th>
                                    <th style="width:150px">Valor</th>
                                    <th style="width:150px">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(movimiento,index) in arrayMovimientosCred" :key="index">
                                    <td style="text-align:right">{{index+1}}</td>
                                    <td v-text="movimiento.fecha_movimiento1" style="text-align:right"></td>
                                    <td v-text="movimiento.concepto"></td>
                                    <td v-text="movimiento.abrvdep"></td>
                                    <td v-text="movimiento.num_operacion" style="text-align:right"></td>
                                    <td style="text-align:right">{{movimiento.monto | currency}}</td>
                                    <td><button type="button" class="btn btn-danger btn-sm" @click="desactivarMovimientos(movimiento.idsegbancario)">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </td>                                
                                </tr> 
                                <tr>
                                    <td colspan="5" class="table-primary" style="text-align:right"><strong>Total:</strong></td>
                                    <td style="text-align:right"><strong>{{sumacred | currency}}</strong></td>
                                    <td class="table-primary"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input class="form-control" 
                                        type="date" v-model="fecha_movimiento"
                                        :max="fechaactual"></td>
                                    <td><input type="text" class="form-control" v-model="concepto"></td>
                                    <td><select class="form-control" v-model="iddepartamento">
                                            <option v-for="departamento in arrayDepartamento" :key="departamento.iddepartamento" :value="departamento.iddepartamento" v-text="departamento.abrvdep">Nombre</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" v-model="num_operacion"></td>
                                    <td><vue-numeric  
                                            class="form-control input-importe"
                                            currency="Bs." 
                                            separator="," 
                                            v-model="monto"
                                            v-bind:precision="2"
                                            v-on:focus="selectAll">
                                    </vue-numeric></td>
                                    <td><button :disabled="!credito" type="button" @click="registrarMovimiento(1)" class="btn btn-success btn-sm">
                                        <i class="icon-plus"></i>
                                    </button> &nbsp;
                                </td>
                            </tr>                             
                            </tbody>
                        </table>
                        <h6>- Debitos del banco no registrados por la empresa</h6>
                        <table class="table table-bordered table-striped table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th style="width:50px">Nº</th>
                                    <th style="width:160px">Fecha</th>
                                    <th>Concepto</th>
                                    <th style="width:100px">Depto.</th>
                                    <th style="width:100px">Operacion</th>
                                    <th style="width:150px">Valor</th>
                                    <th style="width:150px">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(movimiento,index) in arrayMovimientosDeb" :key="index">
                                    <td style="text-align:right">{{index+1}}</td>
                                    <td v-text="movimiento.fecha_movimiento1" style="text-align:right"></td>
                                    <td v-text="movimiento.concepto"></td>
                                    <td v-text="movimiento.abrvdep"></td>
                                    <td v-text="movimiento.num_operacion" style="text-align:right"></td>
                                    <td style="text-align:right">{{movimiento.monto | currency}}</td>
                                    <td><button type="button" class="btn btn-danger btn-sm" @click="desactivarMovimientos(movimiento.idsegbancario)">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </td>                                
                                </tr> 
                                <tr>
                                    <td colspan="5" class="table-primary" style="text-align:right"><strong>Total:</strong></td>
                                    <td style="text-align:right"><strong>{{sumadeb | currency}}</strong></td>
                                    <td class="table-primary"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input class="form-control" 
                                        type="date" v-model="fecha_movimientodeb"
                                        :max="fechaactual"></td>
                                    <td><input type="text" class="form-control" v-model="conceptodeb"></td>
                                    <td><select class="form-control" v-model="iddepartamentodeb">
                                            <option v-for="departamento in arrayDepartamento" :key="departamento.iddepartamento" :value="departamento.iddepartamento" v-text="departamento.abrvdep">Nombre</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" v-model="num_operaciondeb"></td>
                                    <td><vue-numeric  
                                            class="form-control input-importe"
                                            currency="Bs." 
                                            separator="," 
                                            v-model="montodeb"
                                            v-bind:precision="2"
                                            v-on:focus="selectAll">
                                    </vue-numeric></td>
                                    <td><button :disabled="!debito" type="button" @click="registrarMovimiento(2)" class="btn btn-success btn-sm">
                                        <i class="icon-plus"></i>
                                    </button> &nbsp;
                                </td>
                            </tr>                             
                            </tbody>
                        </table>
                        <div style="float:left">
                            <h5>Saldo a la Fecha:</h5>
                        </div>
                        <div style="text-align:right">
                            <h5>{{saldo + sumacred - sumadeb | currency}}</h5>
                        </div>
                        <hr>




                        <div class="form-group row">
                        <div class="col-md-7">
                        </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <strong style="padding-top: 7px;">Saldo según Extracto Bancario:&nbsp;&nbsp;&nbsp;</strong>
                                    <vue-numeric  
                                            class="form-control input-importe"
                                            currency="Bs." 
                                            separator="," 
                                            v-model="montobanco"
                                            v-bind:precision="2"
                                            v-on:focus="selectAll">
                                    </vue-numeric>
                            </div>
                        </div>
                    </div>
                        <h6> - Creditos de la Empresa no Registrados por el Banco (Cheques Pendientes de Cobro )</h6>
                        <table class="table table-bordered table-striped table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th style="width:50px">Nº</th>
                                    <th style="width:160px">Fecha</th>
                                    <th>Beneficiario</th>
                                    <th style="width:100px">Cheque</th>
                                    <th style="width:100px">Cod. Comprobante</th>
                                    <th style="width:150px">Valor</th>
                                    <th style="width:150px">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(movimiento,index) in arrayCheques" :key="index">
                                    <td style="text-align:right">{{index+1}}</td>
                                    <td v-text="movimiento.fecharegistro1" style="text-align:right"></td>
                                    <td v-text="movimiento.nomportador"></td>
                                    <td v-text="movimiento.numdocumento" style="text-align:right"></td>
                                    <td v-text="movimiento.cod_comprobante"></td>
                                    <td style="text-align:right">{{movimiento.importe | currency}}</td>
                                    <td><button v-if="movimiento.estado==0" type="button" @click="registrarCambio(2,movimiento.idmovimiento)" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Registrar Entrega">
                                        <i class="fa fa-address-book"></i>
                                    </button>
                                    <button v-if="movimiento.estado==2" type="button" @click="registrarCambio(1,movimiento.idmovimiento)" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Registrar Cobro">
                                        <i class="cui-briefcase"></i>
                                    </button> </td>                                
                                </tr> 
                                <tr>
                                    <td colspan="5" class="table-primary" style="text-align:right"><strong>Total:</strong></td>
                                    <td style="text-align:right"><strong>{{sumacheques| currency}}</strong></td>
                                    <td class="table-primary"></td>
                                </tr>
                            </tbody>
                        </table>
                        <h6>+ Debitos de la Empresa no registrados por el Banco (Ingreso por Ajustes)</h6>
                        <table class="table table-bordered table-striped table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th style="width:50px">Nº</th>
                                    <th style="width:160px">Fecha</th>
                                    <th>Concepto</th>
                                    <th style="width:100px">Depto.</th>
                                    <th style="width:100px">Documento</th>
                                    <th style="width:150px">Valor</th>
                                    <th style="width:150px">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(movimiento,index) in arrayDebitosNoBanco" :key="index">
                                    <td style="text-align:right">{{index+1}}</td>
                                    <td v-text="movimiento.fecha_movimiento1" style="text-align:right"></td>
                                    <td v-text="movimiento.concepto"></td>
                                    <td v-text="movimiento.abrvdep"></td>
                                    <td v-text="movimiento.num_operacion" style="text-align:right"></td>
                                    <td style="text-align:right">{{movimiento.monto | currency}}</td>
                                    <td><button type="button" class="btn btn-danger btn-sm" @click="desactivarMovimientos(movimiento.idsegbancario)">
                                            <i class="icon-trash"></i>
                                        </button></td>                                
                                </tr> 
                                <tr>
                                    <td colspan="5" class="table-primary" style="text-align:right"><strong>Total:</strong></td>
                                    <td style="text-align:right"><strong>{{sumadebNoBanco | currency}}</strong></td>
                                    <td class="table-primary"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input class="form-control" 
                                        type="date" v-model="fecha_movimientodebNoBanco"
                                        :max="fechaactual"></td>
                                    <td><input type="text" class="form-control" v-model="conceptodebNoBanco"></td>
                                    <td><select class="form-control" v-model="iddepartamentodebNoBanco">
                                            <option v-for="departamento in arrayDepartamento" :key="departamento.iddepartamento" :value="departamento.iddepartamento" v-text="departamento.abrvdep">Nombre</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" v-model="num_operaciondebNoBanco"></td>
                                    <td><vue-numeric  
                                            class="form-control input-importe"
                                            currency="Bs." 
                                            separator="," 
                                            v-model="montodebNoBanco"
                                            v-bind:precision="2"
                                            v-on:focus="selectAll">
                                    </vue-numeric></td>
                                    <td><button :disabled="!debitoNoBanco" type="button" @click="registrarMovimiento(3)" class="btn btn-success btn-sm">
                                        <i class="icon-plus"></i>
                                    </button> &nbsp;
                                </td>
                            </tr>                             
                            </tbody>
                        </table>
                        <div style="float:left">
                            <h5>Saldo Real a la Fecha:</h5>
                        </div>
                        <div style="text-align:right">
                            <h5 :class="[(saldoreal)<0?'rojo':'']">{{ saldoreal | currency}}</h5>
                        </div>
                        <hr>
                        <div style="float:left">
                            <h5>Diferencia:</h5>
                        </div>
                        <div style="text-align:right">
                            <h5 :class="[(diferencia)<0?'rojo':'']">{{ diferencia | currency}}</h5>
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
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                arrayCuentasC:[],
                cuentasconciliacion:0,
                criterio:'',
                arrayDepartamento:[],
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
                tipomovimiento:0,
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
                montodebNoBanco:0

            }
        },

        formOptions:{
            validateAfterLoad: true,
            validateAfterChanged: true
        },

        computed:{
            diferencia(){
                if(this.saldoreal<0)
                    return this.saldoalafecha+this.saldoreal;
                
                return this.saldoalafecha-this.saldoreal;
            },
            saldoalafecha(){
                return this.saldo + this.sumacred - this.sumadeb;

            },
            saldoreal(){
                return this.montobanco - this.sumacheques + this.sumadebNoBanco;

            },
            sumacheques(){
                let me=this;
                var sumatotalcheques=0;
                for (let i = 0; i < me.arrayCheques.length; i++) {
                    const element = me.arrayCheques[i];
                    //console.log(element);
                    sumatotalcheques=sumatotalcheques+element.importe;
                    
                }
                return sumatotalcheques;
            },
            sumacred(){
                let me=this;
                var sumatotalcreditos=0;
                for (let i = 0; i < me.arrayMovimientosCred.length; i++) {
                    const element = me.arrayMovimientosCred[i];
                    //console.log(element);
                    sumatotalcreditos=sumatotalcreditos+element.monto;
                    
                }
                return sumatotalcreditos;
            },
             sumadeb(){
                let me=this;
                var sumatotaldebitos=0;
                for (let i = 0; i < me.arrayMovimientosDeb.length; i++) {
                    const element = me.arrayMovimientosDeb[i];
                    //console.log(element);
                    sumatotaldebitos=sumatotaldebitos+element.monto;
                    
                }
                return sumatotaldebitos;
            },
            sumadebNoBanco(){
                let me=this;
                var sumatotaldebitosnobanco=0;
                for (let i = 0; i < me.arrayDebitosNoBanco.length; i++) {
                    const element = me.arrayDebitosNoBanco[i];
                    //console.log(element);
                    sumatotaldebitosnobanco=sumatotaldebitosnobanco+element.monto;
                    
                }
                return sumatotaldebitosnobanco;

            },
            credito(){
                var valor=0;
                if(this.monto && this.num_operacion)
                    valor=1;
                return valor
            },
             debito(){
                var valor=0;
                if(this.montodeb && this.num_operaciondeb)
                    valor=1;
                return valor
            },
            debitoNoBanco(){
                var valor=0;
                if(this.montodebNoBanco && this.num_operaciondebNoBanco)
                    valor=1;
                return valor
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
            registrarCambio(valor,movimiento)
            { //valor 1 para registrar cobro de cheque, 2 para registrar la entrega del cheque al portador
                let me = this;
                if(valor==2)
                    var titulo=' la entrega '
                else{
                    var titulo=' el cobro '
                }
                swal({
                title: '¿Esta Seguro de registrar'+titulo+'del cheque?',
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
                        axios.put('/con_conciliacion/regcambio',{
                            'valor': valor,
                            'idmovimiento':movimiento 
                        }).then(function (response) {
                            swal(
                            'Se Registro' + titulo + 'del cheque',
                            )
                            me.listarMovimiento();
                            
                        }).catch(function (error) {
                            console.log(error);
                        });
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
            selectDepartamento(){
                let me=this;
                var url= '/par_departamento/selectDepartamento';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayDepartamento = respuesta.departamentos;
                })
                .catch(function (error) {
                    console.log(error);
                });
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
            listarMovimiento(){
                let me=this;
                var datos=[]
                //console.log(me.cuentasconciliacion);
                me.montobanco=0;
                datos=me.arrayCuentasC.filter(obj=>{
                    if(obj.valor==me.cuentasconciliacion)
                        return obj
                        //console.log(obj.valor);
                })
                me.nomcuenta=datos[0].nomcuenta;
                me.codcuenta=datos[0].codcuenta;
                me.idcuenta=datos[0].valor;
                var url= '/con_segbancario?idcuenta='+me.idcuenta+'&fechacorte='+me.fechaselected;
                //console.log(url);
                axios.get(url).then(function (response) {
                    me.saldoconciliacion()
                    var respuesta= response.data; 
                    me.arrayMovimientos=respuesta.movimientos;
                    me.arrayMovimientosCred=me.arrayMovimientos.filter(obj=>{
                       if(obj.tipomovimiento==1)
                        return obj
                   });
                   me.arrayMovimientosDeb=me.arrayMovimientos.filter(obj=>{
                       if(obj.tipomovimiento==2)
                        return obj
                   })
                   me.arrayDebitosNoBanco=me.arrayMovimientos.filter(obj=>{
                       if(obj.tipomovimiento==3)
                        return obj
                   })
                })
                .catch(function (error) {
                    console.log(error);
                });

                url= '/con_conciliacion/segcheques?finicio='+me.fechainicio+'&ffin='+me.fechaselected+'&idcuenta='+me.idcuenta;
                //console.log(url);
                axios.get(url).then(function (response) {
                    var respuesta= response.data; 
                    //console.log(respuesta);
                    me.arrayCheques=respuesta.segcheques;
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
            registrarMovimiento(tipomovimiento){
                let me = this;
                if(tipomovimiento==2)
                {
                    me.fecha_movimiento=me.fecha_movimientodeb;
                    me.concepto=me.conceptodeb;
                    me.iddepartamento=me.iddepartamentodeb;
                    me.num_operacion=me.num_operaciondeb;
                    me.monto=me.montodeb;
                }
                if(tipomovimiento==3)
                {
                    me.fecha_movimiento=me.fecha_movimientodebNoBanco;
                    me.concepto=me.conceptodebNoBanco;
                    me.iddepartamento=me.iddepartamentodebNoBanco;
                    me.num_operacion=me.num_operaciondebNoBanco;
                    me.monto=me.montodebNoBanco;
                }
                axios.post('/con_segbancario/registrar',{
                    'idcuenta': me.idcuenta,
                    'fecha_movimiento': me.fecha_movimiento,
                    'concepto':me.concepto,
                    'iddepartamento':me.iddepartamento,
                    'num_operacion':me.num_operacion,
                    'monto':me.monto,
                    'tipomovimiento':tipomovimiento
                }).then(function (response) {
                    if(response.data.length){
                       //console.log(response)
                       swal(
                            'El Valor ya Existe',
                            'Ingresa un dato Diferente',
                            'error'
                       )                    
                    }
                    else{
                        me.resetMovimiento();
                        me.listarMovimiento();
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            },
            resetMovimiento(valor){
                let me=this;
                me.concepto='';
                me.num_operacion='';
                me.monto=0;
                me.conceptodeb='';
                me.num_operaciondeb='';
                me.montodeb='';
                me.conceptodebNoBanco='';
                me.num_operaciondebNoBanco='';
                me.montodebNoBanco='';
            
            },
           
            saldoconciliacion(){
                let me=this;
                var url= '/saldoconciliacion?finicio='+me.fechainicio+'&ffin='+me.fechaselected+'&idcuenta='+me.idcuenta;
                axios.get(url).then(function (response) {
                    var respuesta= response.data; 
                   console.log(respuesta);
                   me.saldo=respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        },
        mounted() {
            this.selectConciliacion();
            this.obtenerfecha();
            this.selectDepartamento();
            this.classModal=new _pl.Modals();
            this.classModal.addModal('registrarmov');
            
        }
    }
</script>
<style >
.rojo{
    color:red;

}

</style>

