<template>
<main class="main">
    <div class="breadcrumb titmodulo">Almacén > Entrada de Suministros</div>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header titcard">
                <div class="row">
                    <div class="col-md-6">
                        <div class="tablatit">
                            <div class="tcelda">Cuenta: <span v-text="regCuenta.nomcuenta"></span></div>
                        </div>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="input-group-append" style="display:inline;">
                            <button class="btn btn-primary dropdown-toggle" style="margin-top:0px" data-toggle="dropdown" aria-expanded="false">
                                Otra cuenta...<span class="caret"></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                                <a href="#" v-for="cuenta in arrayCuentas" :key="cuenta.idcuenta"
                                    class="dropdown-item" v-text="cuenta.nomcuenta" @click="listaEntradas(cuenta.idcuenta)"></a>
                            </div>
                        </div>
                        <button class="btn btn-primary" style="margin-top:0px" @click="nuevaEntrada()">Nueva Entrada</button>                        
                    </div>
                </div>                         
            </div>
            <div class="card-body table-responsive">
                <table class="table table-striped table-sm">
                    <thead class="tcabecera">
                        <tr>
                            <th><span class="badge badge-success noprint" v-text="arrayEntradas.length+' items'"></span></th>
                            <th>Suministro</th>
                            <th>Proveedor</th>
                            <th>Fecha Compra</th>
                            <th>Factura</th>
                            <th>Cantidad</th>
                            <th>Costo Unitario</th>
                            <th>Costo Total</th>
                            <th>Usado</th>
                            <th>Disponible</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr align="right" v-for="entrada in arrayEntradas" :key="entrada.identrada">
                            <td nowrap align="center">
                                <button class="btn btn-warning icon-pencil btn-sm" @click="editarEntrada(entrada)"></button>
                                <!-- <button class="btn btn-danger icon-close btn-sm" @click="disableEntrada(entrada)"></button> -->
                            </td>
                            <!-- <td v-text="entrada.nrlote" align="center"></td> -->
                            <td v-text="entrada.nomsuministro" align="left"></td>
                            <td v-text="entrada.nomproveedor" align="left"></td>
                            <td v-text="jsfechas.fechames(entrada.fechaentrada)" align="center"></td>
                            <td v-text="entrada.nrfactura"></td>
                            <td v-text="entrada.cantentrada"></td>
                            <td v-text="jsfunciones.formatomon(entrada.costo)"></td>
                            <td v-text="jsfunciones.formatomon(entrada.costo*entrada.cantentrada)"></td>
                            <td v-text="entrada.usado"></td>
                            <td v-text="entrada.cantentrada-entrada.usado"></td>
                        </tr>                               
                    </tbody>
                </table>
            </div>
        </div>
         

        </div>

    <!-- MODAL ENTRADA MODAL ENTRADA MODAL ENTRADA MODAL ENTRADA MODAL ENTRADA MODAL ENTRADA -->
    <!-- MODAL ENTRADA MODAL ENTRADA MODAL ENTRADA MODAL ENTRADA MODAL ENTRADA MODAL ENTRADA -->
    <!-- MODAL ENTRADA MODAL ENTRADA MODAL ENTRADA MODAL ENTRADA MODAL ENTRADA MODAL ENTRADA -->
    <div class="modal" :class="modalEntrada?'mostrar':''" >
        <div class="modal-dialog modal-primary">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button class="close" @click="modalEntrada=0">×</button>
                </div>
                <div class="modal-body">
                    <h4 class="titsubrayado" v-text="'Cuenta: '+regCuenta.nomcuenta"></h4>
                    <div class="row">
                        <div class="col-md-6">
                            Suministro:
                            <select class="form-control" v-model="idsuministro" @change="setMedida(idsuministro)">
                                <option v-for="suministro in arraySuministros" :key="suministro.idsuministro"
                                    :value="suministro.idsuministro" v-text="suministro.nomsuministro"></option>
                            </select>
                            <br>Proveedor:
                            <select class="form-control" v-model="idproveedor">
                                <option v-for="proveedor in arrayProveedores" :key="proveedor.idproveedor"
                                    :value="proveedor.idproveedor" v-text="proveedor.nomproveedor"></option>
                            </select>
                            <br>
                            Fecha:
                            <input type="date" class="form-control text-center" v-model="fechaentrada">
                        </div>
                        <div class="col-md-6">
                            <div class="tablatit">
                                <div class="tfila">
                                    <div class="tcelda taltura nowrap">Cantidad:</div>
                                    <div class="tcelda tinput">
                                        <div class="input-group">
                                            <input  type="text" v-model="cantentrada" class="form-control text-right">
                                            <div class="input-group-append"><span class="input-group-text" v-text="regSuministro.nommedida+'s'"></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda taltura nowrap">Costo Unit:</div>
                                    <div class="tcelda tinput">
                                        <div class="input-group">
                                            <input type="text" class="form-control text-right" v-model="costo" 
                                                @keyup="costototal=jsfunciones.formatomon(cantentrada*costo)">
                                            <div class="input-group-append"><span class="input-group-text">Bs.</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda taltura nowrap">Costo Total:</div>
                                    <div class="tcelda tinput">
                                        <div class="input-group">
                                            <input type="text" class="form-control text-right" disabled v-model="costototal">
                                            <div class="input-group-append"><span class="input-group-text">Bs.</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tfila">
                                    <div class="tcelda taltura">Nr: Factura:</div>
                                    <div class="tcelda tinput"><input type="text" class="form-control" v-model="nrfactura"></div>
                                </div>

                            </div>                    

                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button class="btn btn-secondary" @click="modalEntrada=0">Cerrar</button>
                    <button v-if="accion==1" class="btn btn-primary" @click="storeEntrada()">Guardar Entrada</button>
                    <button v-if="accion==2" class="btn btn-primary" @click="updateEntrada()">Guardar modificaciones</button>
                </div>
            </div>
        </div>
    </div> 


</main>
</template>

<script>
import * as jsfechas from '../../fechas.js';
import * as jsfunciones from '../../funciones.js';

export default {
    data(){ return {
        jsfechas:'', jsfunciones:'', 
        modalEntrada:'', tituloModal:'', accion:1, disponible:'',
        arrayCuentas:[], arraySuministros:[], arrayProveedores:[], arrayEntradas:[],
        regCuenta:[], regSuministro:[],
        idcuenta:82, identrada:'', idsuministro:'', idproveedor:'', 
        nrfactura:'', fechaentrada:'', cantentrada:'', costo:'', costototal:'',
    }},

    methods:{
        listaCuentas(){
            var url='/alm_suministro/listaCuentas';
            let me=this;
            axios.get(url).then(function(response){
                me.arrayCuentas=response.data.cuentas;
                me.verCuenta(me.idcuenta);
            });
        },

        listaSuministros(idcuenta){
            var url='/alm_suministro/listaSuministros?idcuenta='+idcuenta;
            let me=this;
            axios.get(url).then(function(response){
                me.arraySuministros=response.data.suministros;
            });
        },

        listaEntradas(idcuenta){
            this.idcuenta=idcuenta;
            if(this.arrayEntradas.length) this.verCuenta(idcuenta);
            var url='/alm_entrada/listaEntradas?idcuenta='+idcuenta;
            let me=this;
            axios.get(url).then(function(response){
                me.arrayEntradas=response.data.entradas;
                /*
                var tam=me.arrayEntradas.length-1;
                me.nrlote=0;
                if(tam>=0) me.nrlote=me.arrayEntradas[tam].nrlote;
                me.nrlote++;
                me.listaSalidas(idsuministro);
                */
            });
        },

        listaProveedores(){
            let me=this;
            var url='/alm_proveedor/listaProveedores';
            axios.get(url).then(function(response){
                me.arrayProveedores=response.data.proveedores;
            });
        },

        verCuenta(idcuenta){
            for(var i=0; i<this.arrayCuentas.length; i++)
                if(this.arrayCuentas[i].idcuenta==idcuenta)
                {   this.regCuenta=this.arrayCuentas[i]; return;  }
        },

        setMedida(idsuministro){
            for(var i=0; i<this.arraySuministros.length; i++)
                if(this.arraySuministros[i].idsuministro==idsuministro)
                {   this.regSuministro=this.arraySuministros[i]; return;  }
        },

        nuevaEntrada(){
            this.accion=1;
            this.modalEntrada=1;
            this.tituloModal='Nueva Entrada';
            this.resetEntrada();
            this.listaSuministros(this.idcuenta);
            this.listaProveedores();            
        },

        editarEntrada(entrada){
            this.accion=2;
            this.modalEntrada=1;
            this.tituloModal='Modificar datos';
            this.listaSuministros(this.idcuenta);
            this.setMedida(entrada.idsuministro)
            this.listaProveedores();
            this.costototal=jsfunciones.formatomon(entrada.costo*entrada.cantentrada);
            this.identrada=entrada.identrada;
            this.nrlote=entrada.nrlote;  
            this.idproveedor=entrada.idproveedor;
            this.idsuministro=entrada.idsuministro;
            this.fechaentrada=entrada.fechaentrada;
            this.cantentrada=entrada.cantentrada;
            this.costo=entrada.costo;
            this.nrfactura=entrada.nrfactura;
        },

        resetEntrada(){
            this.idsuministro='';
            this.idproveedor='';
            this.regSuministro.nommedida='';
            this.cantentrada='';
            this.costo='';
            this.costototal='';
            this.fechaentrada='';
            this.nrfactura='';
        },

        storeEntrada(){
            let me=this;    
            axios.post('/alm_entrada/storeEntrada',{
                'idsuministro':this.idsuministro,
                'idcuenta':this.idcuenta,
                'idproveedor':this.idproveedor,
                //'nrlote':1,//this.nrlote,
                'fechaentrada':this.fechaentrada,
                'cantentrada':this.cantentrada,
                'costo':this.costo,
                'nrfactura':this.nrfactura
            }).then(function(){
                swal('Entrada registrada','','success');
                me.listaEntradas(me.idcuenta);
                me.resetEntrada();
                me.modalEntrada=0;
            });
        },

        updateEntrada(){
            let me=this;
            axios.put('/alm_entrada/updateEntrada',{
                'identrada':this.identrada,
                'idsuministro':this.idsuministro,
                'idproveedor':this.idproveedor,
                'fechaentrada':this.fechaentrada,
                'cantentrada':this.cantentrada,
                'costo':this.costo,
                'nrfactura':this.nrfactura
            }).then(function(){
                swal('Proceso correcto','Se han guardado las modificaciones','success');
                me.listaEntradas(me.idcuenta);
                me.modalEntrada=0;
            })
        },
    },

    mounted(){
        //this.listaSuministros(this.idcuenta);
        this.listaCuentas();
        this.listaEntradas(this.idcuenta);
        //this.listaOficinas();
        this.jsfechas=jsfechas;
        this.jsfunciones=jsfunciones;
    },
}
</script>