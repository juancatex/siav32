<template>
<main class="main">
    <div class="breadcrumb titmodulo">Activos > Cuentas Contables</div>
    <div class="container-fluid">
        <div class="card" >
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped table-sm">
                    <thead class="tcabecera">
                        <tr>
                            <th></th>
                            <th>Cód. Contable </th>
                            <th>Cód. Agrupación </th>
                            <th>Cuenta</th>
                            <th>Vida útil</th>
                            <th>Depreciación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="cuenta in arrayCuentas" :key="cuenta.idcuenta" align="center">
                            <td><button class="btn btn-warning btn-sm icon-pencil" @click="editarCuenta(cuenta)"></button></td>
                            <td v-text="cuenta.codcuenta"></td>
                            <td v-text="cuenta.valorcuenta"></td>
                            <td v-text="cuenta.nomcuenta" align="left"></td>
                            <td><span v-if="cuenta.vida" v-text="cuenta.vida+' años'"></span> </td>
                            <td><span v-if="cuenta.vida" v-text="Math.round(10000/cuenta.vida)/100+'%'"></span></td>
                            <!-- Math.round(x*100)/100 -->
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
                    <div class="tfila">
                        <div class="tcelda taltura nowrap">Vida útil:</div>
                        <div class="tcelda tinput">
                            <div class="input-group">
                                <input  type="text" v-model="vida" class="form-control text-right">
                                <span class="input-group-text">Años</span>
                            </div>
                        </div>
                    </div>
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
        idcuenta:'', codcuenta:'', nomcuenta:'', vida:'',
    }},

    methods : {
        listaCuentas(){
            let me=this;
            var url= '/act_auxiliar/listaCuentas';
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
            this.vida=cuenta.vida;
        },
      
        updateCuenta(){
            let me = this;
            axios.put('/act_auxiliar/updateCuenta',{
                'idcuenta': this.idcuenta,
                'nomcuenta':this.nomcuenta,
                'vida':this.vida,
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
