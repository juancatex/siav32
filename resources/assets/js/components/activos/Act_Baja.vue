<template>
<main class="main">
    <div class="breadcrumb">
        <div class="col-md-6 titmodulo" style="padding:0">Activos > Bajas</div>
        <!--
        <div class="col-md-6 text-right">
            <button class="btn btn-outline-success cui-options botonnav"></button>
        </div>
        -->
    </div>    

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr style="background-color:#e4e7ea">
                            <th></th>
                            <th class="text-center">Código</th>
                            <th class="text-center">Cuenta Auxiliar</th>
                            <th class="text-center">Descripción</th>
                            <th class="text-center">Nro Orden</th>
                            <th class="text-center">Fecha Baja</th>
                            <th class="text-center">Motivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="activo in arrayActivos" :key="activo.idactivo">
                            <td nowrap>
                                <button class="btn btn-warning btn-sm icon-docs" title="Informe de Baja"></button>
                            </td>    
                            <td v-text="activo.codactivo" align="center"></td>
                            <td v-text="activo.nomauxiliar"></td>
                            <td v-text="activo.descripcion"></td>
                            <td v-text="activo.nrorden" align="center"></td>
                            <td v-text="jsfechas.fechames(activo.fechabaja)" align="center"></td>
                            <td v-text="activo.nommotivo" ></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
</template>

<script>
import * as jsfechas from '../../fechas.js';

export default {
    data(){ return {
        arrayActivos:[],
        regActivo:[],
    }},

    methods:{
        listafililes(){

        },

        listaBajas(){
            let me=this;
            var url='act_activo/listaBajas';
            axios.get(url).then(function(response){
                me.arrayActivos=response.data.activos;
            })
        },

        restoreBaja(activo){
            swal({title:'Restaurará este activo', type:'warning',
                    html: activo.nomauxiliar+'<br>'+activo.descripcion, showCancelButton: true,
                    confirmButtonColor:'#f86c6b', confirmButtonText:'Restaurar',
                    cancelButtonText:'Cancelar', reverseButtons: true
            }).then(confirmar=>{
                if(confirmar.value){
                    var url='/act_activo/restoreBaja?idactivo='+activo.idactivo;
                    let me=this;
                    axios.put(url).then(function(){
                        swal('Activo restaurado','Verifique en Activos -> Catálogo','success');
                        me.listaBajas();
                    });
                }
            });
        },
        /*
        verActivo(idactivo){
            url='/act_activo/verActivo?idactivo='+idactivo;
            axios.get(url).then(function(response){
                me.regActivo=response.data.activo;
            });
        },
        */
    },

    mounted(){
        this.listaBajas();
        this.jsfechas=jsfechas;
    }
    
}
</script>

