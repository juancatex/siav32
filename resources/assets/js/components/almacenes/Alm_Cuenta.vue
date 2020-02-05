<template>
<main class="main">
    <div class="breadcrumb titmodulo">Almacén > Cuentas Contables</div>
    <div class="container-fluid">
        <div class="card" >
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped table-sm">
                    <thead class="tcabecera">
                        <tr>
                            <th><span class="badge badge-success" v-text="arrayCuentas.length+' items'"></span></th>
                            <th>Cód. Contable </th>
                            <th>Cód. Agrupación </th>
                            <th>Cuenta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="cuenta in arrayCuentas" :key="cuenta.idcuenta" align="center">
                            <td><button class="btn btn-warning btn-sm icon-pencil" @click="editarCuenta(cuenta)"></button></td>
                            <td v-text="cuenta.codcuenta"></td>
                            <td v-text="cuenta.codcuenta.substr(-2)"></td>
                            <td v-text="cuenta.nomcuenta" align="left"></td>
                        </tr>                                
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL CUENTA CONTABLE MODAL CUENTA CONTABLE MODAL CUENTA CONTABLE MODAL CUENTA CONTABLE   -->
    <!-- MODAL CUENTA CONTABLE MODAL CUENTA CONTABLE MODAL CUENTA CONTABLE MODAL CUENTA CONTABLE   -->
    <!-- MODAL CUENTA CONTABLE MODAL CUENTA CONTABLE MODAL CUENTA CONTABLE MODAL CUENTA CONTABLE   -->
    <div class="modal" :class="modalCuenta?'mostrar':''">
        <div class="modal-dialog modal-primary modal-sm">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" class="close" @click="modalCuenta=0">x</button>
                </div>
                <div class="modal-body">
                    <p class="titsubrayado text-center">CÓDIGO: <span v-text="codcuenta"></span></p>
                    Cuenta:<input  type="text" v-model="nomcuenta" class="form-control" >
                </div>        
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="modalCuenta=0">Cerrar</button>
                    <button class="btn btn-primary" @click="updateCuenta()">Actualizar Datos</button>
                </div>
            </div>
        </div>
    </div>    
</main>
</template>

<script>
export default {
    data (){ return {
        modalCuenta:0, tituloModal:'', arrayCuentas:[],
        idcuenta:'', codcuenta:'', nomcuenta:'', 
    }},

    methods : {
        listaCuentas(){
            let me=this;
            var url= '/alm_suministro/listaCuentas';
            axios.get(url).then(function (response) {                
                me.arrayCuentas = response.data.cuentas;
            });
        },

        editarCuenta(cuenta){
            this.modalCuenta=1;
            this.tituloModal='Editar Cuenta';
            this.idcuenta =cuenta.idcuenta;
            this.codcuenta=cuenta.codcuenta;
            this.nomcuenta=cuenta.nomcuenta;
        },
      
        updateCuenta(){
            let me = this;
            axios.put('/alm_suministro/updateCuenta',{
                'idcuenta': this.idcuenta,
                'nomcuenta':this.nomcuenta.toUpperCase(),
            }).then(function () {
                swal('Actualizado correctamente','','success');
                me.listaCuentas();
                me.modalCuenta=0;
            }); 
        },

    },

    mounted() {
        this.listaCuentas();
    }
}
</script>
