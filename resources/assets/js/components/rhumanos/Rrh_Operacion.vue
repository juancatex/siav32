<template>
<main class="main">
    <div class="breadcrumb titmodulo">RRHH > Operaciones</div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header titcard">
                        Biométrico
                    </div>
                    <div class="card-body text-center">
                        <button class="btn btn-primary">Recoger Marcas</button>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header titcard">
                        Salarios
                    </div>
                    <div class="card-body">
                        <!--
                        <div class="tabla100">
                            <div class="tfila">
                                <div class="tcelda">Periodo:</div>
                                <div class="tcelda">
                                    <select class="form-control" v-model="periodo">
                                        <option value="2019-09-30">Sep/2019</option>
                                        <option value="2019-10-31">Oct/2019</option>
                                        <option value="2019-11-30">Nov/2019</option>
                                        <option value="2019-12-31">Dic/2019</option>
                                        <option value="2020-01-31">Ene/2020</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>-->
                        <div class="tabla100">
                            <div class="tfila">
                                <div class="tcelda">Periodo:</div>
                                <div class="tcelda">
                                    <select class="form-control" v-model="per">
                                        <option v-for="(i,index) in arrayMeses" :key="index" :value="index" v-text="i"></option>
                                    </select>
                                </div>
                                <div class="tcelda">
                                    <select class="form-control" v-model="ges">
                                        <option v-for="i in 2" :key="i" :value="i+2018" v-text="i+2018"></option>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <center><br>
                        <button class="btn btn-primary" @click="verPlanilla(ges,per+1)">Cargar</button>
                        <button class="btn btn-primary" @click="consolidarPlanilla(periodo)" >Consolidar</button>
                        </center>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header titcard">Indemnizaciones</div>
                    <div class="card-body">
                        <div class="tabla100">
                            <div class="tcelda">Gestión:</div>
                            <div class="tcelda">
                                <select class="form-control" v-model="gestion">
                                    <option value="2018">2018</option>
                                    <option value="2019">2019</option>
                                </select>
                            </div>
                        </div>
                        <center><br>
                        <button class="btn btn-primary" @click="verIndemnizacion(gestion)">Generar</button>
                        </center>
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
    data(){ return {
        ipbirt:'', periodo:'', ges:'', per:'', gestion:'',
        arrayPlanillas:[], 
        arrayMeses:['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
        diasMes:[31,28,31,30,31,30,31,31,30,31,30,31],
    }},

    methods:{
        
        listaEmpleados(){
            let me=this;
            var url='/rrh_empleado/listaEmpleados?activo=1';
            axios.get(url).then(function(response){
                me.ipbirt=response.data.ipbirt;
            })
        },

        listaPlanillas(){
            axios.get('/rrh_planilla/listaPlanillas').then(response=>{
                this.arrayPlanillas=response.data.planillas;
            });
        },

        consolidarPlanilla(ges,per){

            for(var i=0; i<this.arrayPlanillas.length; i++)
            {
                var peri=this.per+1;
                if(peri<10) peri='0'+peri;
                peri=this.ges+'-'+peri;
                if(this.arrayPlanillas[i].periodo.substr(0,7)==peri) 
                {
                    swal('No es posible procesar','Ya existe una planilla consolidada para el periodo '+this.ges+'-'+(this.per+1),'error');
                    return;
                }
            }


            


            swal({  title:'Proceso irreversible', type: 'warning', 
                html: 'Generará la planilla de sueldos '+this.ges+'-'+this.per+' <br>Asegúrese de haber verificado los datos', 
                showCancelButton: true,
                confirmButtonColor:'#f86c6b', confirmButtonText:'Generar Planilla',
                cancelButtonText:'Cancelar', reverseButtons: true
            }).then(confirmar=>{
                if(confirmar.value) {
                    var dias=this.diasMes[this.per];
                    this.per++;
                    var per =this.per<10?'0'+this.per:this.per;
                    var periodo=this.ges+'-'+per+'-'+dias;
                    var url='/rrh_planilla/storePlanilla?periodo='+periodo;
                    axios.post(url).then(response=>{
                        swal('Proceso concluido','Se ha generado la planilla de sueldos de '+periodo,'success');
                    });
                    
                }
            });
        },


        verPlanilla(ges,per){
            if(per<10) per='0'+per;
            var url=[], dias=this.diasMes[per-1];
            url.push('http://'+this.ipbirt+':8080/');
            url.push('birt-viewer/frameset?__report=reportes/rhumanos/');
            url.push('rrh_sueldos.rptdesign'); //archivo
            url.push('&periodo='+ges+'-'+per+"-"+dias); //periodo
            url.push('&__format=pdf'); //formato
            reporte.viewPDF(url.join(''),'Planilla de Sueldos');
        },

        verIndemnizacion(gestion){
            var url=[];
            url.push('http://'+this.ipbirt+':8080/');
            url.push('birt-viewer/frameset?__report=reportes/rhumanos/');
            url.push('rrh_indemnizacion.rptdesign'); //archivo
            url.push('&gestion='+gestion); //periodo
            url.push('&__format=pdf'); //formato
            reporte.viewPDF(url.join(''),'Planilla de Sueldos');
        }
        
    },

    mounted(){
        this.listaEmpleados();
        this.listaPlanillas();
    }
}
</script>