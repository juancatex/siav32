<template>
    <main>  
        <div style="display:none" id="divreportemayor">    
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h4>Resultados</h4>
                    </div>
                    <div class="card-body">
                        <div v-for="libromayor in arrayCabeceras" :key="libromayor.index">
                            <table class="table table-sm table-bordered">
                                <thead  class="thead-dark"> 
                                    <tr >
                                        <th style="width: 90px">Fecha</th>
                                        <th>Comprobante</th>
                                        <th>Documento</th>
                                        <th>Num. Doc.</th>
                                        <th>Glosa</th>
                                        <th style="width: 95px">Debe</th>
                                        <th style="width: 95px">Haber</th>
                                        <th style="width:13%">Saldo Anterior</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-info">
                                        <th><strong>CUENTA:</strong></th>
                                        <th v-text="libromayor.codcuenta"></th>
                                        <th colspan="5" v-text="libromayor.nomcuenta"></th>
                                        <th style="text-align: right">{{libromayor.saldo | currency}}</th>
                                    </tr>
                                    <tr v-for="librodetalle in arraydetalle=preparamayor(libromayor.idcuenta,libromayor.saldo)" :key="librodetalle.index">
                                        <td v-text="librodetalle.fecharegistro.split(' ')[0]"></td>
                                        <td v-text="librodetalle.cod_comprobante"></td>
                                        <td v-text="librodetalle.tipodocumento"></td>
                                        <td v-text="librodetalle.numdocumento"></td>
                                        <td v-text="librodetalle.glosa"> </td>
                                        <td style="text-align: right">{{librodetalle.debe | currency}} </td>
                                        <td style="text-align: right">{{librodetalle.haber | currency}} </td>
                                        <td style="text-align: right">{{librodetalle.saldoparcial | currency}}</td>
                                    </tr>
                                    <tr class="table-secondary" >
                                        <th colspan="5" style="text-align: right" v-text="'TOTAL CUENTA: '+libromayor.codcuenta"></th>
                                        <th style="text-align: right">{{libromayor.sumdebe | currency}} </th>
                                        <th style="text-align: right">{{libromayor.sumhaber | currency}} </th>
                                        <th style="text-align: right">{{libromayor.saldofinal | currency}}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>

import VueCurrencyFilter from 'vue-currency-filter'
Vue.use(VueCurrencyFilter,
{
  symbol : '',
  thousandsSeparator: '.',
  fractionCount: 2,
  fractionSeparator: ',',
  symbolPosition: 'front',
  symbolSpacing: true
})
export default {
    data(){ return {
         valor:'',
         tituloReporte:'',
         fechainicio:'',
         fechafin:'',
         cuentainicio:0,
         cuentafin:0,
         arrayLibroMayor:[],
         codcuenta:'',
         nuevoarray:[],
         arrayCabeceras:[],
         arrayPie:[],
         arraydetalle:[],
    }},

    methods:{
        cuenta(valor){
            let me=this;
            me.codcuenta=valor;
        },
        cargarvue(arrayvalores){
            $('#divreportemayor').css('display','block');
            this.fechainicio=arrayvalores['fechainicio'];
            this.fechafin=arrayvalores['fechafin'];
            this.cuentainicio=parseInt(arrayvalores['cuentainicio']);
            this.cuentafin=parseInt(arrayvalores['cuentafin']);
            this.obtenerlibromayor();
        },
        cerrarvue(){
            $('#divreportemayor').css('display','none');
        },
        preparamayor(idcuenta,saldoinicial) {
            //console.log(idcuenta);
            let me=this;
            var arraydetalles=[];
            arraydetalles=me.arrayLibroMayor.filter(obj=>(obj.idcuenta==idcuenta && obj.monto!=null))
           
            var saldo=saldoinicial;

            arraydetalles.forEach((element,index) => {
                if(element.debe!=null)
                    saldo=saldo+element.debe;
                else
                    saldo=saldo-element.haber;
                
                arraydetalles[index].saldoparcial=saldo;
                
            });
             return arraydetalles;

        },
        obtenerlibromayor(){
            let me = this;
            var url= '/libromayor?finicio='+me.fechainicio+'&ffin='+me.fechafin+'&cinicio='+me.cuentainicio+'&cfin='+me.cuentafin;
            
            var array=[];
            //console.log(url);
            swal({
                    title: "Procesando...", 
                    allowOutsideClick: () => false,
                    allowEscapeKey:() => false, 
                    onOpen: function() { 
                        swal.showLoading() ;
                    }})
            
            axios.get(url).then(function (response) {
                var respuesta= response.data; 
                me.arrayLibroMayor=respuesta;
                //console.log(respuesta);
                me.arrayCabeceras=respuesta.filter(function(obj){
                    if(obj.cod_comprobante==null && obj.saldo!=null)
                        return obj;
                })
                me.arrayPie=respuesta.filter(obj=>{
                    if(obj.sdebe!=null)
                    return obj;
                })
                var nuevoarr=[];
                var saldofinal=0;
                me.arrayCabeceras.forEach((element,index) => {
                    saldofinal=me.arrayPie[index].sdebe-me.arrayPie[index].shaber+me.arrayCabeceras[index].saldo;
                    //console.log(saldofinal);
                    me.arrayCabeceras[index].saldofinal=saldofinal;
                    me.arrayCabeceras[index].sumdebe=me.arrayPie[index].sdebe;
                    me.arrayCabeceras[index].sumhaber=me.arrayPie[index].shaber;
                });
            })
            .catch(function (error) {
                console.log(error);
            });
            swal.close();
            //me.fechafactura=me.fechaactual;
        },
    },
}
</script>
