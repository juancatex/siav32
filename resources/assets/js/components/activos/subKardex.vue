<template>
<main>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header titcard">Código <span v-text="regActivo.codactivo"></span></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="tfila">
                                <div class="tcelda titcampo nowrap">Grupo Contable:</div>
                                <div class="tcelda" v-text="regActivo.nomgrupo"></div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Auxiliar:</div>
                                <div class="tcelda" v-text="regActivo.nomauxiliar"></div>
                            </div>
                            <span class="titcampo">Descripción:</span><br>
                            <span v-text="regActivo.descripcion"></span>
                            <div v-if="regActivo.obs"><br>
                                <span class="titcampo">Observaciones:</span><br>
                                <span v-text="regActivo.obs"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="tfila">
                                <div class="tcelda titcampo">Marca:</div>
                                <div class="tcelda" v-text="regActivo.marca"></div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Nr Serie:</div>
                                <div class="tcelda" v-text="regActivo.serie"></div>
                            </div>
                            <div class="tfila">
                                <div class="tcelda titcampo">Estado:</div>
                                <div class="tcelda" v-text="regActivo.estado==1?'Bueno':regActivo.estado==2?'Regular':'Malo'">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header titcard">Asignación</div>
                <div class="card-body">
                    <div class="tfila">
                        <div class="tcelda titcampo">Filial:</div>
                        <div class="tcelda" v-text="regActivo.nommunicipio" ></div>
                    </div>                        
                    <div class="tfila">
                        <div class="tcelda titcampo">Oficina:</div>
                        <div class="tcelda" v-text="regActivo.nomambiente"></div>
                    </div>                        
                    <div class="tfila">
                        <div class="tcelda titcampo">Responsable:</div>
                        <div class="tcelda">
                            {{regActivo.nombre}}
                            <!--
                            <span v-if="!verResponsable(regActivo.idactivo)" class="badge badge-danger">Sin Asignar</span>
                            <span v-else v-text="verResponsable(regActivo.idactivo)"></span>
                            -->
                        </div>
                    </div>                        
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6 titcard">
                    <div class="tablatit">
                        <div class="tcelda">Calculo de Depreciacion</div>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <button class="btn btn-success icon-printer" style="margin-top:0px" title="Vista de impresión" 
                        @click="reporteKardex(regActivo.idactivo)"></button>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive">
            <div class="row">
                <div class="col-md-2 nowrap">
                    <span class="titcampo">Compra:</span>
                    <span v-text="regActivo.fechaingreso?jsfechas.fechames(regActivo.fechaingreso):''"> </span> 
                </div>
                <div class="col-md-2">
                    <span class="titcampo">UFV:</span><span v-text="regActivo.ufvini"></span>
                </div>
                <div class="col-md-2">
                    <span class="titcampo">Costo:</span><span v-text="regActivo.costo+'Bs.'"></span>
                </div>
                <div class="col-md-2">
                    <span class="titcampo">V.Residual:</span><span v-text="regActivo.residual+'Bs.'"></span>
                </div>
                <div class="col-md-2">
                    <span class="titcampo">Coeficiente:</span><span v-text="regActivo.coeficiente+'%'"></span>
                </div>
                <div class="col-md-2 text-right">
                    <span class="titcampo">Vida útil:</span><span v-text="regActivo.vida+'años'"></span>
                </div>
            </div>
            <br>
            <h5 class="titsubrayado">Drepreciación Anual</h5>
            <table class="table table-sm table-striped">
                <thead class="tcabecera">
                    <tr align="center">
                        <th>Gestión</th>
                        <th>UFV Inicio</th>
                        <th>UFV Cierre</th>
                        <th>Consumido</th>
                        <th>Saldo de vida</th>
                        <th align="right">Incremento Anual (+)</th>
                        <th align="right">Depreciación Anual</th>
                        <th align="right">Depreciación Acumulada (-)</th>
                        <th align="right">Valor final</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="depreciacion in arrayDepreciaciones" :key="depreciacion.id" align="center">
                        <td v-text="depreciacion.gestion"></td> 
                        <td v-text="depreciacion.ufvini"></td> 
                        <td v-text="depreciacion.ufvfin"></td>
                        <td v-text="depreciacion.consumido"></td>
                        <td v-text="depreciacion.saldovida"></td>
                        <td v-text="jsfunc.formatomon(depreciacion.incranual)" align="right"></td>
                        <td v-text="jsfunc.formatomon(depreciacion.depranual)" align="right"></td>
                        <td v-text="jsfunc.formatomon(depreciacion.depracum)"  align="right"></td>
                        <td v-text="jsfunc.formatomon(depreciacion.valorfin)"  align="right"></td>
                    </tr>
                    
                </tbody>
            </table>
            <br>
            <h5 class="titsubrayado">Depreciación a la fecha</h5>
            <table class="table table-bordered table-sm ">
                <thead class="tcabecera">
                    <tr align="center">
                        <th>Seleccione Fecha:</th>
                        <th>UFV Inicio</th>
                        <th>UFV Cierre</th>
                        <th>Consumido</th>
                        <th>Saldo de vida</th>
                        <th>Incremento Total</th>
                        <th>Depreciación Total</th>
                        <th>Valor Final</th>
                    </tr>
                </thead>
                <tbody>
                    <tr align="center">
                        <td><input type="date" class="form-control"  v-model="fechahoy" 
                            @change="depreciacionActual(idactivo,fechahoy)"></td>
                        <td v-text="regDepreciacion.ufvini"></td>
                        <td v-text="regDepreciacion.ufvfin"></td>
                        <td v-text="regDepreciacion.consumido"></td>
                        <td v-text="regDepreciacion.saldovida"></td>
                        <td v-text="formatomon(regDepreciacion.incrtotal)"></td>
                        <td v-text="formatomon(regDepreciacion.deprtotal)"></td>
                        <td v-text="formatomon(regDepreciacion.valorfin)" class="titcampo"></td>
                    </tr>
                </tbody>
            </table>
            
        </div>
    </div>
</main>
</template>

<script>
import * as reporte from '../../functions.js';
import * as jsfechas from '../../fechas.js';
import * as jsfunc from '../../funciones.js';

export default {
    props:['idactivo','currfecha'],

    data(){ return {
        jsfechas:'', jsfunc:'', ipbirt:'', fechahoy:'',
        arrayDepreciaciones:[], regDepreciacion:[], regActivo:[],
        arrayUfvini:[], arrayUfvfin:[],
    }},

    methods:{
        async depreciacionAnual(idactivo){
            var url='/act_activo/verActivo?idactivo='+idactivo;
            let response = await axios.get(url);
            this.regActivo=response.data.activo[0];
            response=await axios.get('/act_ufv/ufvGestion?criterio=ini');
            this.arrayUfvini=response.data.ufvgestion;
            response=await axios.get('/act_ufv/ufvGestion?criterio=fin');
            this.arrayUfvfin=response.data.ufvgestion;
            this.ipbirt=response.data.ipbirt;
            this.arrayDepreciaciones=[];
            var activo=this.regActivo;    
            var gesini=activo.fechaingreso.substr(0,4)*1;
            var gesfin=activo.currfecha.substr(0,4)*1;
            var depranual=(activo.costo/activo.vida);
            var depracum=0; //valorres=0;
            for(var ges=gesini; ges<gesfin; ges++){
                var regDepreciacion=new Object();
                regDepreciacion.gestion=ges;
                regDepreciacion.ufvini=this.arrayUfvini[ges-2008].valor;
                regDepreciacion.ufvfin=this.arrayUfvfin[ges-2008].valor;
                regDepreciacion.depranual=depranual;
                if(ges==gesini) {
                    regDepreciacion.ufvini=activo.ufvini;
                    var cantdias=this.diastransc(activo.fechaingreso,gesini+'-12-31')
                    regDepreciacion.depranual=(activo.costo/(activo.vida*365))*cantdias;
                }
                regDepreciacion.incranual=activo.costo*((regDepreciacion.ufvfin/regDepreciacion.ufvini)-1);
                depracum+=regDepreciacion.depranual;
                regDepreciacion.depracum=depracum;
                regDepreciacion.valorfin=activo.costo-depracum+regDepreciacion.incranual;
                var ini=activo.fechaingreso; var fin=ges+'-12-31';
                regDepreciacion.consumido=this.tiempotransc(ini,fin);
                ini=(ges+1)+'-01-01'; fin=(gesini+activo.vida)+activo.fechaingreso.substr(4,6);
                regDepreciacion.saldovida=this.tiempotransc(ini,fin);
                this.arrayDepreciaciones.push(regDepreciacion);
            }
        },
        

        async depreciacionActual(idactivo,fecha){
            var url='/act_activo/verActivo?idactivo='+idactivo;
            let response=await axios.get(url);
            var activo=response.data.activo[0];
            var url='/act_ufv/verUfv?fecha='+activo.fechaingreso;
            response=await axios.get(url);
            this.regDepreciacion.ufvini=response.data.ufvfecha.valor;
            url='/act_ufv/verUfv?fecha='+fecha; 
            response=await axios.get(url);
            this.regDepreciacion.ufvfin=response.data.ufvfecha.valor;
            this.regDepreciacion.consumido=this.tiempotransc(activo.fechaingreso,fecha);
            var fechafin=(1*activo.fechaingreso.substr(0,4)+activo.vida)+activo.fechaingreso.substr(4,6);
            this.regDepreciacion.saldovida=this.tiempotransc(fecha,fechafin);
            this.regDepreciacion.incrtotal=activo.costo*((this.regDepreciacion.ufvfin/this.regDepreciacion.ufvini)-1);
            var cantdias =this.diastransc(activo.fechaingreso,fecha);
            var valorincr=this.regDepreciacion.incrtotal+activo.costo;
            this.regDepreciacion.deprtotal=valorincr/(activo.vida*365)*cantdias;
            this.regDepreciacion.valorfin=activo.costo+this.regDepreciacion.incrtotal-this.regDepreciacion.deprtotal;
        },
        
        reporteKardex(idactivo){
            axios.get('/act_activo/calcDepreciacion?idactivo='+idactivo);
            var url=[];
            url.push('http://'+this.ipbirt+':8080');
            url.push('/birt-viewer/frameset?__report=reportes/activos');
            url.push('/depr_activo.rptdesign');
            url.push('&__format=pdf'); 
            url.push('&idactivo='+idactivo); 
            url.push('&ip='+this.ipbirt);//pa la foto
            reporte.viewPDF(url.join(''),'Kardex del Activo');
        },

        formatomon(x){  //215451.325145 --> 215,451.32
            if(x<0) x*=-1;
            var num=Math.round(x*100)/100;
            var cad=num.toString();
            if(!cad.includes('.')) cad=cad+'.00';
            cad=cad.split('').reverse();
            if(cad[1]=='.') cad.unshift('0');
            var arr=[]; var c=0;
            for(var i=0;i<cad.length;i++){
                if(i<3) arr.push(cad[i]);
                else {
                    if(c<3){ arr.push(cad[i]); c++; }
                    else { arr.push(','); c=0; i--; }
                }
            }
            return arr.reverse().join('');
        },

        tiempotransc(fini,ffin){
            var aa=ffin.substr(0,4)-fini.substr(0,4);
            var mm=ffin.substr(5,2)-fini.substr(5,2);
            if(mm<0) {mm=12+mm; aa--;}
            //var dd=ffin.substr(8,2)-fini.substr(8,2);
            //if(dd<0) {dd=30+dd; mm--;}
            //return aa+'a'+' '+mm+'m'+' '+dd+'d';
            return aa+'a'+' '+mm+'m';
        },

        diastransc(fini,ffin){ //dias transcurridos
            var fechaini = new Date(fini);
            var fechafin = new Date(ffin);
            return Math.round((fechafin.getTime()-fechaini.getTime())/86400000);
        },


    },

    mounted(){
        this.jsfechas=jsfechas;
        this.jsfunc=jsfunc;
        this.fechahoy=this.currfecha;
        this.depreciacionAnual(this.idactivo);
        this.depreciacionActual(this.idactivo,this.currfecha);
    }
}
</script>
