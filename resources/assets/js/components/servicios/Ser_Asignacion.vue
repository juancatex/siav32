<template>
<main class="main">
    <div class="breadcrumb">
        <div class="col-md-8 col-sm-11 titmodulo" style="padding:0px" >
            <div class="tablatit">
                <div v-if="divFiliales==0" class="tcelda" style="padding-right:8px">
                    <button class="btn btn-success cui-arrow-left botonnav"
                    @click="nivelPrevio(regEstablecimiento.codservicio)"></button>
                </div>
                <div class="tcelda">Servicios 
                    <span v-if="divFiliales"> > Filiales </span>
                    <span v-if="!divFiliales" v-text="' > '+regEstablecimiento.nommunicipio"></span>
                    <span v-if="!divFiliales" v-text="' > '+regEstablecimiento.nomestablecimiento"></span>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-1 text-right" style="padding:0px">
            <button class="btn btn-success cui-options botonnav"></button>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row" v-if="divFiliales">
            <div class="col-md-3" v-for="filial in arrayFiliales" :key="filial.idfilial">
                <div class="card">
                    <div class="card-header titcampo text-center">
                        <span v-text="filial.sigla+' - '+filial.nommunicipio"></span>
                    </div>
                    <div class="card-body">
                        <select class="form-control" v-model="idestablecimiento" @change="verEstablecimiento(idestablecimiento)">
                            <!-- <option>Servicios...</option> -->
                            <template v-for="establecimiento in arrayEstablecimientos">
                                <option v-if="establecimiento.idfilial==filial.idfilial" 
                                    :key="establecimiento.idestablecimiento"
                                    :value="establecimiento.idestablecimiento"
                                    v-text="establecimiento.nomestablecimiento"> 
                                </option>
                            </template>
                        </select>                        
                    </div>
                </div>        
            </div>            
        </div>

        <asgVivienda     :regEstablecimiento="regEstablecimiento" v-if="vueVivienda" ></asgVivienda>
        <asgHtransitorio :regEstablecimiento="regEstablecimiento" v-if="vueHtransitorio" ></asgHtransitorio>
        <asgHpermanente  :regEstablecimiento="regEstablecimiento" v-if="vueHpermanente"></asgHpermanente>
        <asgMausoleo     :regEstablecimiento="regEstablecimiento" v-if="vueMausoleo" ></asgMausoleo>
        <asgRegular      :regEstablecimiento="regEstablecimiento" v-if="vueRegular"></asgRegular>
        <asgEventual     :regEstablecimiento="regEstablecimiento" v-if="vueEventual"></asgEventual>
    </div>
</main>
</template>




<script>
Vue.component('asgVivienda',require('./asgVivienda.vue').default);
Vue.component('asgHtransitorio',require('./asgHtransitorio.vue').default);
Vue.component('asgHpermanente',require('./asgHpermanente.vue').default);
Vue.component('asgMausoleo',require('./asgMausoleo.vue').default);
Vue.component('asgRegular',require('./asgRegular.vue').default);
Vue.component('asgEventual',require('./asgEventual.vue').default);

export default {
    data(){ return {  
        regFilial:[], regServicio:[], regAmbiente:[],
        subModulo:0, recibido:'', divFiliales:1,
        arrayFiliales:[], arrayServicios:[], 
        arrayEstablecimientos:[], regEstablecimiento:[], idestablecimiento:'',
        vueVivienda:0, vueHtransitorio:0, vueHpermanente:0,
        vueMausoleo:0, vueRegular:0, vueEventual:0, 
    }},

    methods:{
        listaFiliales(){
            axios.get('/fil_filial/listaFiliales?activo=1').then(response=>{
                this.arrayFiliales=response.data.filiales;
            });
        },

        listaEstablecimientos(){
            axios.get('/ser_establecimiento/listaEstablecimientos?activo=1').then(response=>{
                this.arrayEstablecimientos=response.data.establecimientos;
            });
        },

        verEstablecimiento(idestablecimiento){
            this.divFiliales=0;
            this.idestablecimiento=idestablecimiento;
            for(var i=0;i<=this.arrayEstablecimientos.length;i++)
                if(this.arrayEstablecimientos[i].idestablecimiento==idestablecimiento)
                {    this.regEstablecimiento=this.arrayEstablecimientos[i]; break;    }
            switch(this.regEstablecimiento.codservicio){
                case 'VIV': this.vueVivienda=1; break;
                case 'HTR': this.vueHtransitorio=1; break;
                case 'HPR': this.vueHpermanente=1; break;
                case 'MAU': this.vueMausoleo=1; break;
                case 'REG': this.vueRegular=1; break;
                case 'EVE': this.vueEventual=1; break;
            }
        },

        nivelPrevio(codservicio){
            this.vueVivienda=0;
            this.vueHtransitorio=0;
            this.vueHpermanente=0;
            this.vueMausoleo=0;
            this.vueRegular=0;
            this.vueEventual=0;
            this.idestablecimiento=0;
            this.divFiliales=1;
        },
    },

    mounted(){
        this.listaFiliales();
        this.listaEstablecimientos();
    }
}
</script>


<style lang="css">
.titmodulo {
    font-size:16px;
    font-weight:500;
}

.titservicio{
    font-size:28px;
    font-weight: 500;
    color:#20a8d8;
    margin-bottom: 0px;
    margin-top: 0px;
}

.titazul{
    color:#3c8dbc;
    font-size:18px;
    font-weight:600;
    border-bottom: #3c8dbc solid 1px;
}

.titsubrayado{
    color:#5c6873; 
    font-weight:600;
    font-size:18px;
    text-align:center; 
    border-bottom: #acb4bc solid 1px;
}

.titcampo{
    color:#5c6873;
    font-weight:700;
    padding-right: 5px;
}

.titcard{
    color: #5c6873;/* #20a8d8;*/
    font-size:18px;
    font-weight:600;
    
}

.txtdesactivado{
    font-style: italic;
    color:#acb4bc;
}

.txtnegrita{
    font-weight: bold;
}


.txtasterisco:before{
    content: "*";
    font-size:14px;
    color:#fd4b49;
    font-weight: bold;
}

.txtobligatorio:before{
    content: "* Datos obligatorios";
    font-size:12px;
    color:#fd4b49;
    font-weight: bold;
}

.txtvalidador{
    font-size:12px;
    color:#fd4b49;
    font-style: italic;
    /* text-align: right;  */
    margin-top:0px; 
    padding-bottom:8px;
    margin-bottom:0px;
}

.invalido{
  /* display: block; */
    width: 100%; 
    height: calc(1.5em + 0.75rem + 2px);
    border: 1px solid #fd4b49;/* #f86c6b;*/
    box-shadow: 0 0 0 0.2rem rgba(253, 75, 75, 0.25);
}

.botonnav{ 
    font-size:20px; 
    padding:5px;
}

.vervigente{
    display:inline; 
    border:1px solid #acb4bc; 
    border-radius:5px; 
    padding:5px 15px; 
    vertical-align: middle;
}

.bloquecelda{
    padding:10px 15px; 
    font-size:20px; 
    text-align:center; 
    font-weight: 500;
}

.bloquecelda:hover, .celdahover:hover{
    background-color:#c1e7f4;
    cursor:pointer; 
}

.bloquefila:hover{
    background-color: rgba(203,221,230,0.3); /*#cbdde6;  */
}

.ocupado{
    background-color:#4dbd74;
    color:#fff; 
    font-weight: 700;
}

.vencido{
    background-color:#fee2e1;
}

.bgcolor1 { background-color:#fee2e1; }
.bgcolor2 { background-color:#ddc2e1; }
.bgcolor3 { background-color:#aae2e1; }
.bgcolor4 { background-color:#cce2e1; }
.bgcolor5 { background-color:#bbe2e1; }

.tablacen { display:table; margin-left: auto; margin-right: auto; }
.tablatit { display:table; height:100%;}
.tabla100 { display:table; width:100%; } 
.tfila  { display:table-row; }
.tcelda { display:table-cell; vertical-align:middle; }
.taltura{ padding: 10px 0px; }
.tinput { width:100%; padding-left:10px; }
.nowrap { white-space:nowrap; }
.tcabecera th{ 
    background-color: #e4e7ea; 
    vertical-align:middle;
}

.fotosocioservicios{
    border:#efefef 2px solid;
    filter:drop-shadow(4px 4px 5px #333);
    height: auto;
}

</style>