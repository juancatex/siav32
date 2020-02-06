<template>
<main class="main">
    <div class="modal" :class="modalAsistencia?'mostrar':''">
        <div class="modal-dialog modal-primary modal-lg">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 class="modal-title">Reporte de Asistencia</h4>
                    <button class="close" @click="modalAsistencia=0">x</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="ttabla">
                                <div class="tcelda">
                                    <span class="titcard" v-text="regEmpleado.nombre+' '+regEmpleado.apaterno"></span>
                                    <span class="titcard" v-text="regEmpleado.amaterno"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-right">
                            <div class="tfila">
                                <div class="tcelda">Periodo:&nbsp;</div>
                                <div class="tcelda">



                                    <select class="form-control" v-model="per" @change="verAtraso(periodo)">
                                        <option v-for="mes in arrayMeses" :key="mes" :value="mes" v-text="mes"></option>                                        
                                    </select>
                                    <select v-model="ges">
                                        <option value="2019">2019</option>
                                        <option value="2020">2020</option>
                                    </select>

                                </div>
                            </div>                           
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead class="tcabecera">
                                <tr>
                                    <th>Fecha</th>
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
                <div class="modal-footer">
                    <div class="tfila">
                        <div class="tcelda">Atrasos <span v-text="periodo?jsfechas.periodo(periodo):''"></span>:&nbsp;</div>
                        <div class="tcelda">
                            <div class="input-group">
                                <input type="text" class="form-control text-right" style="width:50px" v-model="minutos">
                                <div class="input-group-append"><span class="input-group-text">Minutos</span></div>
                            </div>
                        </div>
                        <div class="tcelda" style="width:20px"></div>
                        <div class="tcelda">
                            <button class="btn btn-primary" @click="accion==1?storeAtraso():updateAtraso()">Guardar</button>
                        </div>
                        <div class="tcelda" style="width:20px"></div>
                        <div class="tcelda">
                            <button class="btn btn-primary" @click="papeletaPago(periodo)">Papeleta de Pago</button>
                        </div>

                    </div>
                </div>
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
        regEmpleado:[], arrayMarcas:[],
        modalAsistencia:'', accion:'', jsfechas:'',
        idatraso:'',minutos:'', periodo:'', ipbirt:'', per:'', ges:'',
        arrayMeses:['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
        diasMes:[31,28,31,30,31,30,31,31,30,31,30,31],        
    }},

    methods:{
        abrirModal(empleado){
            window.scroll({top:0,left:0,behavior:'smooth'});
            this.jsfechas=jsfechas;
            this.modalAsistencia=1;
                    this.periodo='2019-12';
            //this.listaMarcas(empleado.codbiom);
            this.listaMarcas(empleado.codbiom,this.periodo);
            this.verAtraso(this.periodo);
            this.regEmpleado=empleado;
        },

        listaMarcas(codbiom,periodo){
            let me=this;
            var url='/rrh_marca/listaMarcas?codbiom='+codbiom+'&periodo='+periodo;
            axios.get(url).then(function(response){
                me.arrayMarcas=response.data.marcas;
                me.ipbirt=response.data.ipbirt;
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

        //verAtraso(idempleado){
        verAtraso(periodo){
            let me=this;
            var url='/rrh_atraso/verAtraso?idempleado='+this.regEmpleado.idempleado+'&periodo='+periodo;
            axios.get(url).then(function(response){
                if(response.data.atraso.length){
                    me.idatraso=response.data.atraso[0].idatraso;
                    me.minutos=response.data.atraso[0].minutos;
                    me.accion=2;
                }
                else{
                    url='/rrh_marca/listaMarcas?codbiom='+me.regEmpleado.codbiom+'&periodo='+me.periodo;
                    axios.get(url).then(function(response){
                        me.minutos=response.data.atraso[0].minutos;
                        me.accion=1;
                    });       
                }
                
            });
        },

        storeAtraso(){
            axios.post('/rrh_atraso/storeAtraso',{
                'idempleado':this.regEmpleado.idempleado,
                'periodo':this.periodo,
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

    }
}
</script>