<template>
<main>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <div class="tablatit">
                        <div class="tcelda">
                            <span class="titcard" v-text="regEmpleado.nombre+' '+regEmpleado.apaterno"></span>
                            <span class="titcard" v-text="regEmpleado.amaterno"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div style="inline-block">
                        <div class="tcelda">Atraso calculado <span v-text="arrayMeses[per-1]+'/'+ges"></span>:&nbsp;&nbsp;</div>
                        <div class="tcelda">
                            <div class="input-group">
                                <input type="text" class="form-control text-right" style="width:50px" v-model="minutos">
                                <div class="input-group-append"><span class="input-group-text">Minutos</span></div>
                            </div>
                        </div>
                        <div class="tcelda" style="padding-left:5px">
                            <button class="btn btn-primary" @click="accion==1?storeAtraso():updateAtraso()">Guardar</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <div style="display:inline-block; padding-bottom:10px">
                        <div class="tcelda">Periodo:</div>
                        <div class="tcelda">
                            <select class="form-control" v-model="per" @change="listaMarcas(regEmpleado.idempleado,ges,per)">
                                <option v-for="(mes,i) in arrayMeses" :key="i" :value="i+1" v-text="mes"></option>
                            </select>
                        </div>
                        <div class="tcelda">
                            <select class="form-control" v-model="ges" @change="listaMarcas(regEmpleado.idempleado,ges,per)">
                                <option value="2020">2020</option>
                                <option value="2019">2019</option>
                            </select>
                        </div>
                        <div class="tcelda" style="padding-left:5px">
                            <button class="btn btn-success btn-sm icon-printer"></button>
                        </div>                        
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead class="tcabecera">
                        <tr align="center">
                            <th>Fecha</th>
                            <th>Marcas</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Atraso</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Atraso</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(marca) in arrayMarcas" :key="marca.id" align="center">
                            <td v-text="jsfechas.fechadia(marca.fecha)"></td>
                            <td v-text="marca.horas" align="left"></td>
                            <!--
                            <td v-text="marca.hora2"></td>
                            <td v-text="marca.hora3"></td>
                            <td v-text="marca.hora4"></td>
                            -->
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
    props:['regEmpleado','currfecha'],

    data(){ return {
        arrayMarcas:[],
        modalAsistencia:'', accion:'', ipbirt:'', jsfechas:'',
        idatraso:'',minutos:'', periodo:'', ges:'', per:'',
        arrayMeses:['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
        diasMes:[31,28,31,30,31,30,31,31,30,31,30,31],        
    }},

    methods:{
        listaMarcas(idempleado,ges,per){
            if(per<10) per='0'+per;
            var url='/rrh_marca/listaMarcas?idempleado='+idempleado+'&periodo='+ges+'-'+per;
            axios.get(url).then(response=>{
                this.arrayMarcas=response.data.marcas;
                this.ipbirt=response.data.ipbirt;
                this.verAtraso(idempleado,ges,per)
            });
        },

        verAtraso(idempleado,ges,per){
            var url='/rrh_atraso/verAtraso?idempleado='+idempleado+'&periodo='+ges+'-'+per;
            axios.get(url).then(response=>{
                if(response.data.atraso.length){
                    this.idatraso=response.data.atraso[0].idatraso;
                    this.minutos=response.data.atraso[0].minutos;
                    this.accion=2;
                }
                else{
                    url='/rrh_marca/listaMarcas?idempleado='+idempleado+'&periodo='+ges+'-'+per;
                    axios.get(url).then(response=>{
                        this.minutos=response.data.atraso[0].minutos;
                        this.accion=1;
                    });       
                }    
            });
        },

        storeAtraso(){
            swal({ title:'Procesando...',text:'Un momento por favor', type:'warning',
                showCancelButton:false, showConfirmButton:false, 
                allowOutsideClick: false, allowEscapeKey: false, allowEnterKey: false,
                onOpen:() => { swal.showLoading() }
            });             
            axios.post('/rrh_atraso/storeAtraso',{
                'idempleado':this.regEmpleado.idempleado,
                'periodo':this.ges+'-'+(this.per<10?'0':'')+this.per,
                'minutos':this.minutos
            }).then(function(){
                swal('Guardado correctamente','','success');
            });
        },

        updateAtraso(){
            axios.put('/rrh_atraso/updateAtraso',{
                'idatraso':this.idatraso,
                'minutos':this.minutos
            }).then(function(){
                swal('Guardado correctamente','','success');
            });
        },

        papeletaPago(periodo){
            var url=[];
            url.push('http://'+this.ipbirt+':8080');
            url.push('/birt-viewer/frameset?__report=reportes/rhumanos');
            url.push('/rrh_papeleta.rptdesign'); //archivo
            url.push('&idempleado='+this.regEmpleado.idempleado); 
            url.push('&periodo='+periodo); 
            url.push('&__format=pdf'); //formato
            reporte.viewPDF(url.join(''),'Papeleta de Pago');
        },

        reporteAsistencia(idempleado,ges,per){
            var url=[];
            url.push('http://'+this.ipbirt+':8080');
            url.push('/birt-viewer/frameset?__report=reportes/rhumanos');
            url.push('/rrh_asistencia.rptdesign'); 
            url.push('&idempleado='+idempleado); 
            url.push('&periodo='+per+'-'+(per<10?'0':'')+per); 
            url.push('&__format=pdf'); //formato
            reporte.viewPDF(url.join(''),'Papeleta de Pago');
        },
    },

    mounted(){
        this.jsfechas=jsfechas;
        this.ges=this.currfecha.substr(0,4);
        this.per=1*this.currfecha.substr(5,2);
        this.listaMarcas(this.regEmpleado,this.ges,this.per);
        //this.verAtraso(this.regEmpleado,this.ges,this.per);
    },
}
</script>