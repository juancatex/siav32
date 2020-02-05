<template>
    <main>  
        <div style="display:none" id="divreportediario">    
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h4>Resultados</h4>
                    </div>
                    <div class="card-body">
                       

                    <table id="rep_diario"  style="width: 100%;">
                         <tbody>
                            <tr v-for="prueba in arrayCabecera" :key="prueba.index">
                               
                                <td>
                                    <table   class="table table-sm" >
                                        <tbody>
                                        <tr>
                                            <th class="table-info" style="width:10%">Fecha: </th>
                                            <td v-text="prueba.fecharegistro.split(' ')[0]" style="width:10%"></td>
                                            <th class="table-info" style="width:15%">Comprobante: </th>
                                            <td v-text="prueba.cod_comprobante" style="width:10%"></td>
                                            <th class="table-info" style="width:10%">Documento: </th>
                                            <td v-text="prueba.tipodocumento"></td>
                                            <th  class="table-info" style="width:15%"># Doc.: </th>
                                            <td v-text="prueba.numdocumento" style="width:15%"></td>
                                        </tr>
                                        <tr>
                                            <th class="table-info">Glosa: </th>
                                            <td colspan="7" v-text="prueba.glosa"></td>
                                        </tr>  
                                        <tr class="table-info">
                                            <th>Cuenta</th>
                                            <th colspan="5">Descripcion</th>
                                            <th style="text-align:center">Debe</th>
                                            <th style="text-align:center">Haber</th>
                                        </tr> 
                                        <tr v-for="prueba2 in arraydetalle=funcionprueba(prueba.idasientomaestro)" :key="prueba2.index">
                                            <td v-text="prueba2.codcuenta"></td>
                                            <td colspan="5" v-text="prueba2.nomcuenta"></td>
                                            <td style="text-align: right">{{prueba2.debe | currency}}</td>
                                            <td style="text-align: right" >{{prueba2.haber | currency}}</td>
                                        </tr> 
                                        <tr class="table-secondary">
                                            <th colspan="6" style="text-align: right">TOTAL COMPROBANTE:</th>
                                            <th style="text-align: right" >{{prueba.shaber | currency}}</th>
                                            <th style="text-align: right">{{prueba.shaber | currency}}</th>
                                        </tr>
                                        </tbody>
                                    </table>   
                                </td>
                                
                            </tr>
                            </tbody>
                        </table > 
                        <!-- <table id="rep_diario" class="table table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>First name</th>
                                    <th>Last name</th>
                                    <th>Email</th>
                                    <th>Country</th>
                                    <th>IP-address</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Donna</td>
                                    <td>Moore</td>
                                    <td>dmoore0@furl.net</td>
                                    <td>China</td>
                                    <td>211.56.242.221</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Janice</td>
                                    <td>Henry</td>
                                    <td>jhenry1@theatlantic.com</td>
                                    <td>Ukraine</td>
                                    <td>38.36.7.199</td>
                                </tr>
                            </tbody>
                        </table> -->  
                    </div>
                    <div class="card-footer">
                        <button @click="generarpdf()">Generate pdf</button>

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
         arrayLibrodiario:[],
         codcuenta:'',
         nuevoarray:[],
         idtipocomprobante:'',
         orders:[],
         nnuevoarray:[],
         arrayCabecera:[],
         arraydetalle:[],
         contador:0
    }},
    computed:{
    },

    methods:{
         generarpdf() {
            var doc = new jsPDF();

            // Simple data example
            /* var head = [["ID", "Country", "Rank", "Capital"]];
            var body = [
                [1, "Denmark", 7.526, "Copenhagen"],
                [2, "Switzerland", 	7.509, "Bern"],
                [3, "Iceland", 7.501, "Reykjavík"]
            ];
            doc.autoTable({head: head, body: body});
            */
            // Simple html example
            doc.autoTable({html: '#rep_diario'});

            doc.save('tabladiario.pdf');
            },
        cuenta(valor){
            let me=this;
            me.codcuenta=valor;
        },
        cargarvue(arrayvalores){
            $('#divreportediario').css('display','block');
            this.fechainicio=arrayvalores['fechainicio'];
            this.fechafin=arrayvalores['fechafin'];
            this.idtipocomprobante=arrayvalores['idtipocomprobante'];
            this.obtenerlibrodiario();
        },
        cerrarvue(){
            $('#divreportediario').css('display','none');
            //console.log('hola');
            /* this.idcuentafinal=[];
            this.idcuentainicial=[]; */
            
        },
        obtenerlibrodiario(){
            let me = this;
            var url= '/librodiario?finicio='+me.fechainicio+'&ffin='+me.fechafin+'&idtipocomprobante='+me.idtipocomprobante;
            
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
                me.arrayLibrodiario=respuesta;
                var sumd=0;
                var sumh=0;

                me.nuevoarray=[];
                me.nuevoarray=me.arrayLibrodiario.map(function(obj){
                    return JSON.parse(obj);

                })
                //console.log(me.nuevoarray);
                
                me.arrayCabecera = me.nuevoarray.filter( function(obj) {
                if( obj.idasientodetalle == null ){
                    return obj;
                }
                });

                //console.log(me.arrayCabecera);
            })
            .catch(function (error) {
                console.log(error);
            });
            //swal.stopLoading();
            swal.close();
            
        },
        funcionprueba(valor){
            var  arrayprueba=[];
            arrayprueba=this.nuevoarray.filter(obj=>(obj.idasientomaestro==valor && obj.idasientodetalle!=null))
            return arrayprueba;
        }
    },
}
</script>
<style>
.lbl{
    padding-right: 2px;
    padding-left: 2px;
}

</style>
