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
            <div class="card-header">
            <div class="row">
                <div class="col-md-6 titcard">
                    <div class="tablatit">
                        <div class="tcelda">Baja de Activos Fijos</div>
                    </div>
                </div>
                <!-- <div class="col-md-6 text-right">
                    <button class="btn btn-success icon-printer" style="margin-top:0px" title="Vista de impresión" 
                        @click="reporteBajatotal()"></button>
                </div> -->
            </div>                
            </div>
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
                                <button class="btn btn-warning btn-sm icon-printer" title="Informe de Baja"
                                    @click="reporteBaja(activo.idactivo)"></button>
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
import * as reporte from '../../functions.js';

export default {
    data(){ return {
        arrayActivos:[],
        regActivo:[],
        ipbirt:''
    }},

    methods:{
        listafililes(){

        },

        listaBajas(){
            var url='/act_activo/listaBajas';
            axios.get(url).then(response=>{
                this.arrayActivos=response.data.activos;
                this.ipbirt=response.data.ipbirt;

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

        reporteBajatotal(){
            var url=[];
            url.push('http://'+this.ipbirt+':8080');
            url.push('/birt-viewer/frameset?__report=reportes/activos');
            url.push('/act_bajastotal.rptdesign'); 
            url.push('&__format=pdf'); 
            url.push('&idactivo='+idactivo); 
            url.push('&ip='+this.ipbirt);
            reporte.viewPDF(url.join(''),'Baja del Activo');
        },

        reporteBaja(idactivo){
            var url=[];
            url.push('http://localhost:8080');
            url.push('/birt-viewer/frameset?__report=reportes/activos');
            url.push('/act_baja_activo.rptdesign'); 
            url.push('&__format=pdf'); 
            url.push('&idactivo='+idactivo); 
            console.log(url.join(''));
            reporte.viewPDF(url.join(''),'Baja del Activo');
        },


},

    mounted(){
        this.listaBajas();
        this.jsfechas=jsfechas;
    }
    
}
</script>

