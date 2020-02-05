<template>
<main class="main">
    <div class="breadcrumb titmodulo">Contabilidad > Libro de Ventas</div>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-9">
                        <div class="tablatit"><div class="tcelda titcard">Libro de Ventas</div></div>
                    </div>
                    <div class="col-md-3">
                        <div class="tabla100">
                        <div class="tfila">
                            <div class="tcelda">Filial:</div>
                            <div class="tcelda">
                                <select name="esto" class="form-control" v-model="idfilial">
                                    <option v-for="filial in arrayFiliales" :key="filial.id" 
                                        :value="filial.idfilial" v-text="filial.nommunicipio"></option>
                                </select>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="tfila">
                            <div class="tcelda">Año:</div>
                            <div class="tcelda tinput">
                                <select class="form-control" v-model="ges">
                                    <option value="2020">2020</option>
                                    <option value="2019">2019</option>
                                </select>   
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="tfila">
                            <div class="tcelda">Mes:</div>
                            <div class="tcelda tinput">
                                <select class="form-control" v-model="per">
                                    <option v-for="(mes,i) in arrayMeses" :key="mes" :value="i+1" v-text="mes"></option>
                                </select>   
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary" @click="verLibroventa(idfilial,ges,per)">Ver Periodo</button>
                        <button class="btn btn-primary">Cerrar Periodo</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>
</template>

<script>
import * as reporte from '../../functions.js';

export default {
    data() { return {
        arrayFiliales:[], idfilial:'', per:'', ges:'', ipbirt:'',
        arrayMeses:['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
    }},

    methods:{
        listaFiliales(){
            var url='/fil_filial/listaFiliales?activo=1';
            axios.get(url).then(response=>{
                this.arrayFiliales=response.data.filiales;
                this.ipbirt=response.data.ipbirt;
            })
        },

        verLibroventa(idfilial,ges,per){
            if(!idfilial) { swal('Seleccione una filial','','error'); return; }
            if(!ges) { swal('Especifique año','','error'); return; }
            if(!per) { swal('Especifique mes','','error'); return; }
            var url='/ser_pago/listaPagos?periodo='+ges+'-'+(per<10?'0'+per:per);
            axios.get(url).then(response=>{
                if(!response.data.pagos.length) {
                    swal('','No existen transacciones registradas en el periodo '
                        +this.arrayMeses[per-1]+'/'+ges,'error'); return;
                }
            });
            var url=[];
            url.push('http://'+this.ipbirt+':8080');
            url.push('/birt-viewer/frameset?__report=reportes/con_reportes');
            url.push('/libro_ventas.rptdesign'); //archivo
            url.push('&__format=pdf'); //formato
            url.push('&idfilial='+idfilial);
            url.push('&ges='+ges);
            url.push('&per='+per);
            reporte.viewPDF(url.join(''),'Libro de Ventas');
        },

    },

    mounted(){
        this.listaFiliales();
    }
}
</script>