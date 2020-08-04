<template>
    <div class="autocomplete">
        <input type="text" class="form-control" v-model="cadena" 
            @input="buscarCadena()" 
            @keydown.down="teclaAbajo()"
            @keydown.up="teclaArriba()"
            @keydown.enter="teclaEnter()"
            @keydown.esc="desplegar=0">
        <ul class="autocomplete-list" v-show="desplegar">
            <li v-if="cargando" class="loading" >Buscando...</li>
            <li v-else v-for="(socio,i) in arraySocios" :key="i" class="autocomplete-result"
                @click="devolver(socio.idsocio, socio.tipo)" :class="pulsado==i?'is-active':''">
                    <span v-text="socio.nomgrado"></span>
                    <span v-text="socio.nombre"></span>
                    <span v-text="socio.apaterno"></span>
                    <span v-text="socio.amaterno"></span> <i class="fa fa-angle-right"></i>
                    CI: <span v-text="socio.ci"></span> <i class="fa fa-angle-right"></i>
                    PAP: <span v-text="socio.numpapeleta"></span>
            </li>
        </ul>
    </div>
    <!-- https://alligator.io/vuejs/vue-autocomplete-component/ -->
</template>

<script>
export default {
    props: {isAsync:{ type: Boolean, required: false, default: false,},},
    //props:['dedonde'],

    data() { return {
        desplegar:0, cargando:0,
        pulsado: 0,
        cadena:'', 
        arraySocios:[],
    }},

    methods: {
        async buscarCadena(){
            if(this.cadena.length>3){
                var url='/socio/listaSociosCivil?cadena='+this.cadena;
                let response=await axios.get(url);
                this.arraySocios=response.data.socios;
                this.desplegar=1; 
            }
        },

        async buscarCadena22222(){
            if(this.cadena.length>3){
                if(this.isAsync) this.cargando=1;
                else { 
                    var url='/socio/listaSocios?cadena='+this.cadena;
                    let response=await axios.get(url);
                    this.arraySocios=response.data.socios;
                    this.desplegar=1; 
                }
            }
        },

        devolver(idsocio, tipo){
            this.$emit('encontrado',idsocio);
            this.desplegar=0;
            var regSocio=[];
            axios.get('/socio/listaSociosCivil?idsocio='+idsocio+'&tipo='+tipo).then(response=>{
                regSocio=response.data.socios[0];
                this.cadena=regSocio.nomgrado+' '+regSocio.nombre.trim()+' '+regSocio.apaterno+' '+regSocio.amaterno;
            });
        },



        teclaAbajo(evt) {
            if (this.pulsado<this.arraySocios.length) this.pulsado ++;
        },

        teclaArriba() {
            if (this.pulsado>0) this.pulsado--;    
        },

        teclaEnter() {
            this.devolver(this.arraySocios[this.pulsado].idsocio);
            this.pulsado=-1;
        },

        handleClickOutside(evt) {
            if (!this.$el.contains(evt.target)) {
                this.desplegar=0;
                this.pulsado=-1;
          }
        }
    },

    mounted() {
        document.addEventListener('click', this.handleClickOutside);
    },
/*
    destroyed() {
        document.removeEventListener('click', this.handleClickOutside)
    }*/

  };
</script>

<style>
.autocomplete {
    position: relative;
}

.autocomplete-list {
    padding: 0;
    margin: 0;
    border: 1px solid #eeeeee;
    height: 120px;
    overflow: auto;
    width: 100%;
}

.autocomplete-result {
    list-style: none;
    text-align: left;
    padding: 4px 2px;
    cursor: pointer;
}

.autocomplete-result.is-active,
.autocomplete-result:hover {
    background-color: #20a8d8;
    color: white;
}

</style>