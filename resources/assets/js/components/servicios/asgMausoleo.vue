<template>
    <div class="card" v-if="divAsignaciones">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10 titcard">
                    <div class="tablatit">
                        <div class="tcelda"><span v-text="regEstablecimiento.sector"></span>
                            BLOQUE "<span v-text="String.fromCharCode(nrgrupo+64)"></span>"</div>
                    </div>
                </div>  
                <div class="col-md-2 text-right"> 
                    <div class="input-group-append" style="display:inline">
                        <button class="btn btn-primary dropdown-toggle" style="margin-top:0"
                            data-toggle="dropdown" aria-expanded="false">
                            Bloque...<span class="caret"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                            <a href="#" class="dropdown-item" v-for="k in cantgrupos" :key="k" 
                                v-text="'Bloque '+String.fromCharCode(k+64)" 
                                @click="listaAmbientes(regEstablecimiento.idestablecimiento,k)"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive">
<!--
                <table class="table-bordered" align="center">
                    <tr v-for="fil in fils" :key="fil" class="bloquefila">
                        <td class="card-header titcard" v-text="String.fromCharCode(nrgrupo+64)+(fils+1-fil)"></td>
                        <td v-for="ambiente in arrayAmbientes" :key="ambiente.id" class="bloquecelda"
                            v-if="ambiente.codambiente.substr(1,1)==(fils+1-fil)" 
                            @click="ambiente.ocupado?verAsignacion(ambiente.idasignacion,ambiente):asignarCliente(ambiente)" 
                             :class="ambiente.ocupado?'ocupado':''" title="">
                            <span v-text="ambiente.codambiente.substr(-2)" ></span>
                            
                        </td>
                    </tr>
                </table>
-->
                <br>
                <table class="table-bordered" align="center">
                    <tr v-for="fil in fils" :key="fil" class="bloquefila">
                        <td class="card-header titcard" v-text="String.fromCharCode(nrgrupo+64)+(fils+1-fil)"></td>
                        <!--
                        <td v-for="col in cols" :key="col" class="bloquecelda" v-text="col<10?'0'+col:col"
                            :class="ocupado(nrgrupo,fil,col)?'ocupado':''">
                        </td>
                        -->

                        <td v-for="ambiente in arrayAmbientes" :key="ambiente.id" >
                            <template v-if="ambiente.piso==(fils+1-fil)">
                                <span v-text="ambiente.codambiente"></span>
                            </template>
                        </td>
                    </tr>
                </table>
        </div>
        
    </div>
</template>

<script>
import * as jsfechas from '../../fechas.js';

export default {
    props:['regEstablecimiento'],

    data(){return {
        modalAsignacion:0, divAsignaciones:1, jsfechas:'',
        nrgrupo:1, cantgrupos:'', fils:'', codsector:'',//col:'', 
        arrayAmbientes:[],
        regCantgrupos:[],
        arrayOrdinal:['','Primer','Segundo','Tercer','Cuarto','Quinto','Sexto','Séptimo'],
    }},

    methods:{
        listaAsignaciones(idestablecimiento,nrgrupo){
            let me=this;
            var url='/ser_asignacion/listaAsignaciones?idestablecimiento='
                +this.regEstablecimiento.idestablecimiento+'&bloque='+String.fromCharCode(nrgrupo+64);
            axios.get(url).then(function(response){
                me.arrayAsignaciones=response.data.asignaciones;
            });
        },

        listaAmbientes(idestablecimiento,nrgrupo){
            this.nrgrupo=nrgrupo;
            this.fils=Math.floor(this.regCantgrupos[nrgrupo]/100);
            this.cols=Math.floor(this.regCantgrupos[nrgrupo]%100);
            var url='/ser_ambiente/listaAmbientes?idestablecimiento='
                +idestablecimiento+'&bloque='+this.codsector+String.fromCharCode(nrgrupo+64);
                console.log(url);
            axios.get(url).then(response=>{
                this.arrayAmbientes=response.data.ambientes;
            });
        },

        ocupado(nrbloque,fil,col){
            if(col==3) return true;
        }
    },

    mounted(){
        this.regCantgrupos=JSON.parse('['+this.regEstablecimiento.cantgrupos+']');
        this.cantgrupos=this.regCantgrupos.length-1;//cant bloques
        this.jsfechas=jsfechas;
        this.codsector=this.regEstablecimiento.sector.substr(0,1);
        this.listaAmbientes(this.regEstablecimiento.idestablecimiento,1);
    }
    

}
</script>
