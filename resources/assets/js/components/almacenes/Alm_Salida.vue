<template>
<main class="main">
    <div class="breadcrumb titmodulo">Almacén > Salida de Suministros</div>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header titcard">
                <div class="row">
                    <div class="col-md-6">
                        <div class="tablatit">
                            <div class="tcelda">Periodo Feb/2019</div>
                        </div>
                    </div>
                    <div class="col-md-6 text-right">
                        <button class="btn btn-primary" style="margin-top:0" @click="nuevaSalida()">Nueva salida</button>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-striped table-sm">
                    <thead class="tcabecera">
                        <tr>
                            <th><span class="badge badge-success">1 items</span></th>
                            <th>Nro</th>
                            <th>Fecha</th>
                            <th>Destino</th>
                            <th>Receptor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr align="center">
                            <td>
                                <button class="btn btn-warning icon-pencil"></button>
                                <button class="btn btn-warning icon-docs"></button>
                            </td>
                            <td>01/2019</td>
                            <td>15/sep/2019</td>
                            <td align="left">Strai de hacienda</td>
                            <td align="left">sof eee rrr ttt</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL SALIDA  MODAL SALIDA MODAL SALIDA MODAL SALIDA MODAL SALIDA MODAL SALIDA-->
    <!-- MODAL SALIDA  MODAL SALIDA MODAL SALIDA MODAL SALIDA MODAL SALIDA MODAL SALIDA-->
    <div class="modal" :class="modalSalida?'mostrar':''">
        <div class="modal-dialog modal-primary modal-lg">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button class="close" @click="modalSalida=0">x</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            Destino:
                            <select class="form-control" v-model="idoficina" @change="listaEmpleados(idoficina)">
                                <option v-for="oficina in arrayOficinas" :key="oficina.idoficina"
                                    :value="oficina.idoficina" v-text="oficina.nomoficina"></option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            Receptor:
                            <select class="form-control">
                                <option v-for="empleado in arrayEmpleados" :key="empleado.idempleado"
                                    :value="empleado.idempleado" v-text="empleado.nombre+' '+empleado.apaterno"></option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            Fecha:
                            <input type="date" class="form-control">
                        </div>                       
                    </div>
                    <br>
                    <table class="table table-striped table-sm">
                        <thead class="tcabecera">
                            <tr>
                                <th>Cuenta</th>
                                <th>Suministro</th>
                                <th>Cantidad</th>
                                <th>Unidad</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr align="center">
                                <td>cuenta cuenta</td>
                                <td>papel papel</td>
                                <td>5</td>
                                <td>paquetes</td>
                                <td><button class="btn btn-warning btn-sm icon-pencil"></button>
                                    <button class="btn btn-danger btn-sm icon-trash"></button>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control"></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <!--
                    <h4 class="titazul">Realizar entrega</h4>
                    <div class="row">
                        <div class="col-md-4">
                            Cuenta:
                            <select class="form-control" v-model="idcuenta" @change="listaEntradas(idcuenta)">
                                <option v-for="cuenta in arrayCuentas" :key="cuenta.idcuenta"
                                    :value="cuenta.idcuenta" v-text="cuenta.nomcuenta"></option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            Suministro:
                            <select class="form-control" v-model="identrada" @change="verEntrada(identrada)">
                                <option v-for="entrada in arrayEntradas" :key="entrada.identrada" :value="entrada.identrada" 
                                    v-text="'(L'+entrada.nrlote+'): '+entrada.nomsuministro"></option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            Disponible
                            <input type="text" class="form-control text-center" disabled v-model="disponible">
                        </div>
                        <div class="col-md-2">
                            Entregado
                            <input type="text" class="form-control text-right" v-model="cantidad" @keyup="disponible=disponible-cantidad">
                        </div>
                    </div>
                    -->

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="modalSalida=0">Cerrar</button>
                    <button v-if="accion==1" class="btn btn-primary">Guardar</button>
                    <button v-if="accion==2" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>

</main>
</template>

<script>
export default {
    data(){ return {
        modalSalida:0, accion:1, tituloModal:'',
        arrayOficinas:[], arrayEmpleados:[], arrayCuentas:[], arrayEntradas:[],
        regEntrada:[],
        idoficina:'', idempleado:'', idcuenta:'', identrada:'', idsuministro:'', fechasalida:'',
        cantidad:'', disponible:'',
    }},

    methods:{
        listaOficinas(){
            let me=this;
            var url='/fil_oficina/listaOficinas?idfilial=1&activo=1';
            axios.get(url).then(function(response){
                me.arrayOficinas=response.data.oficinas;
            })
        },

        listaEmpleados(idoficina){
            var url='/rrh_empleado/listaEmpleados?idoficina='+idoficina+'&activo=1';
            let me=this;
            axios.get(url).then(function(response){
                me.arrayEmpleados=response.data.empleados;
            });
        },

        listaCuentas(){
            let me=this;
            var url='/alm_suministro/listaCuentas';
            axios.get(url).then(function(response){
                me.arrayCuentas=response.data.cuentas;
            });
        },

        listaEntradas(idcuenta){
            var url='/alm_entrada/listaEntradas?idcuenta='+idcuenta;
            let me=this;
            axios.get(url).then(function(response){
                me.arrayEntradas=response.data.entradas;
            });
            this.disponible='';
        },

        verEntrada(identrada){
            for(var i=0; i<this.arrayEntradas.length; i++)
                if(this.arrayEntradas[i].identrada==identrada)
                {   this.regEntrada=this.arrayEntradas[i]; 
                    this.disponible=this.regEntrada.cantentrada;
                    return;
                }
        },

        resetSalida(){

        },

        nuevaSalida(){
            this.modalSalida=1;
            this.accion=1;
            this.tituloModal='Entrega de Suministros';
            this.listaOficinas();
            this.listaCuentas();
        },
    },

    mounted(){

    }
}
</script>
