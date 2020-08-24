<template>
    <main class ="main">
        <div class="row">
            <!-- Aquí pon las col-x necesarias, comienza tu contenido, etcétera -->
             <div class="container-fluid">
                <div class="card">
                    <div class="col-12">
                        <h1>Actualizacion Datos Porcentajes Cargas Ascii</h1>
                        <label>Servidor: {{host}}</label><br>
                        <label>Base Datos: </label><br>
                        <label>Estado: </label>
                        <!-- <a href="//parzibyte.me/blog" target="_blank">By Parzibyte</a> -->
                    </div>
                    <div class="col-6">
                        <form action="pos.php" method="POST" id="formu">
                            <div class="form-group">
                                <label for="num_comprobante"><b>Comprobante:</b></label>
                                <input name="num_comprobante" required type="number" id="num_comprobante"
                                    class="form-control" placeholder="Num Comprobante">
                            </div>
                            <div class="form-group">
                                <label for="cuenta1">Cuenta Prestamo Regular: 41101101</label> <br>                                                   
                                <label for="cuenta2">Cuenta reserva: 22501101</label> <br>
                                <label for="cuenta3">Cuenta Debito fiscal: 21301102</label> <br>
                                <label for="cuenta4">Cuenta impuestos transacciones - Haber: 21301103</label> <br>
                                <label for="cuenta5">Cuenta impuestos transacciones - Debe: 51301106</label> <br>
                                <label for="subcuenta">Subcuenta: 20000000000</label> <br>
                                <label for="tipo">Tipo: SEC_CON_COM_TRASPASO</label> <br>
                            </div>
                            <div class="form-group">
                                <label for="porcentaje2">% a la cuenta reserva: 20%</label>
                                <label for="porcentaje1">% a la cuenta de prestamo regular: 80% (100%)</label> 
                                <ol>
                                    <ul><label for="porcentaje1">% a la cuenta prestamo regular: 87%</label></ul>
                                    <ul><label for="porcentaje1">% a la cuenta Debito fiscal: 13%</label></ul>
                                    <ul><label for="porcentaje1">% a la cuenta Debito fiscal: 3%</label></ul>
                                </ol>
                                
                            </div>
                            <div class="form-group">
                                <!-- <input class="btn-success btn" type="submit" name="submit"></input> -->
                                <button class="btn-success" type="button" id="boton" @click="proceso(num_comprobante)">Procesar</button>                            
                            </div>
                        </form>
                    </div>
                </div>
             </div>
        </div>
    </main>
</template>


<script>        
    // import * as pdf from '../../pdf.js';
    import * as factura from '../../factura.js';

    export default {
        data (){
            return {
                host:process.env.DB_HOST,
                num_comprobante:'980',

               
                invoice_products: [{
                    product_no: '',
                    product_name: '',
                    product_price: '',
                    product_qty: '',
                    line_total: 0
                }],
                
                arrayProceso : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 10,
                criterio : 'nombreinstitucion',
                buscar : ''
            }
        },
       
        formOptions: {
            validateAfterLoad: true,
            validateAfterChanged: true
          },  

        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            }
        },

        methods : {

            proceso(num_comprobante) {
                //alert(num_comprobante); 
                let me=this;
                var url= '/con_contabilidad/proceso';
                axios.post(url,{'com':this.num_comprobante}).then(function (response) {
                    var respuesta= response.data; console.log(respuesta);       
                    //me.arrayProceso = respuesta.proceso.data;    console.log(me.arrayProceso);           
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';                
                this.numerofactura='';
                this.codigocontrol='';
                this.razonsocial='';               
                this.nit='';
                //this.invoice_products='';
                this.invoice_subtotal='';
                this.invoice_subtotal='';
            },

            abrirModal(modelo, accion, data = []){ //console.log('ja' + this.maxfacturavalor);
                switch(modelo){
                    case "factura":
                    {
                        switch(accion){
                            case 'registrar':
                            {                                                                
                                this.invoice_products = [];
                                this.modal = 1;
                                this.tituloModal = 'Registrar Factura';
                                this.numerofactura=this.maxfacturavalor+1;
                                this.codigocontrol='',
                                this.razonsocial='',               
                                this.nit='',
                                this.detalle='',
                                this.importeparcial='',
                                this.importetotal='',
                                this.importecf='',                         
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            { 
                                this.invoice_products = [];
                                var detalle0 = [];
                                var detalle1 = [];
                                detalle0 = data['detalle'].split(','); //console.log(detalle0[0]);
                                //var array1 = JSON.parse("[" + data['reparticiones'] + "]"); 
                                
                                for (var i=0; i < detalle0.length; i++){
                                    //console.log(detalle1[i]);         
                                    detalle1 = detalle0[i].split('|'); //console.log(detalle0[0]);
                                    this.invoice_products.push({
                                        product_name:detalle1[0],
                                        product_price:detalle1[1],
                                        product_qty:detalle1[2],
                                        line_total:detalle1[3]}) 
                                }
                                                                                                
                                //console.log(this.invoice_products);

                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Factura';
                                this.tipoAccion=2;
                                this.factura_id=data['idfactura'];
                                this.numerofactura = data['numerofactura'];
                                this.codigocontrol = data['codigocontrol'];
                                this.razonsocial = data['razonsocial'];
                                this.nit = data['nit'];
                                this.detalle = data['detalle'];
                                
                                this.importetotal = data['importetotal'];
                                this.importecf = data['importecf'];
                                this.calculateTotal();
                                break;
                            }
                        }
                    }
                }
            },

        },
        mounted() {
                
        }
    }
</script>
