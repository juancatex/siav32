<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Registro de Factura</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Factura
                        <button type="button" @click="abrirModal('factura','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Registrar Factura
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">                                    
                                    <input type="text" v-model="buscar" @keyup.enter="listarFactura(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarFactura(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>No. Factura</th>
                                    <th>Nombre / Razon Social</th>
                                    <th>NIT</th>                                    
                                    <th>Importe</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="factura in arrayFactura" :key="factura.id">
                                    <td>
                                        <button type="button" @click="abrirModal('factura','actualizar',factura)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="factura.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarFactura(factura.idfactura)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarFactura(factura.idfactura)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                        <button type="button" @click="ver_factura(factura)" class="btn btn-warning btn-sm">
                                          <i class="icon-doc"></i>
                                        </button> &nbsp;
                                    </td>
                                    <td v-text="factura.numerofactura"></td>
                                    <td v-text="factura.razonsocial"></td>
                                    <td v-text="factura.nit"></td>
                                    <td v-text="factura.importetotal"></td>                                    
                                    <td>
                                        <div v-if="factura.activo">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                        
                                    </td>
                                </tr>                                
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">No. Factura:</label>
                                    <div class="col-md-3"> 
                                        <input readonly v-validate.initial="'required'" 
                                                type="text" 
                                                v-model="numerofactura" 
                                                name="numerofactura"
                                                class="form-control">                                    
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">NIT:</label>
                                    <div class="col-md-3">
                                        <input v-validate.initial="'required'" 
                                                type="number" 
                                                v-model="nit" 
                                                class="form-control" 
                                                placeholder="nit"
                                                name="nit">
                                    <span class="text-error">{{ errors.first('nit')}}</span>   
                                    </div>
                                    <label class="col-md-3 form-control-label" for="text-input">Razon Social / Nombre:</label>
                                    <div class="col-md-3">
                                        <input v-validate.initial="'required'" 
                                                type="text" 
                                                v-model="razonsocial" 
                                                class="form-control" 
                                                placeholder="razon social"
                                                name="razonsocial">
                                    <span class="text-error">{{ errors.first('razonsocial')}}</span>   
                                    </div>                                                                                                                                                                                                            
                                </div>
                                <!-- parte del detalle  de la factura -->
                                <!-- <div class="form-group row"> -->
                                    <label class="col-md-3 form-control-label" for="text-input">Detalle:</label>
                                    <table class="table table-sm">
                                         <thead class="thead-dark">
                                            <tr>
                                                <th>Borrar Fila</th>
                                                <th>No.</th>
                                                <th>Nombre</th>
                                                <th>Importe Bs.</th>
                                                <th>Cantidad</th>
                                                <th>Total Parcial Bs.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(invoice_product, k) in invoice_products" :key="k">
                                                <td scope="row" class="trashIconContainer">
                                                    <i class="far fa-trash-alt" @click="deleteRow(k, invoice_product)"></i>
                                                </td>
                                                <td> {{k+1}}</td>
                                                <td>
                                                    <input class="form-control" type="text" v-model="invoice_product.product_name" />
                                                </td>
                                                <td>
                                                    <input class="form-control text-right" type="number" min="0" step=".01" v-model="invoice_product.product_price" @change="calculateLineTotal(invoice_product)"/>
                                                </td>
                                                <td>
                                                    <input class="form-control text-right" type="number" min="0" step=".01" v-model="invoice_product.product_qty" @change="calculateLineTotal(invoice_product)"/>
                                                </td>
                                                <td>
                                                    <input readonly class="form-control text-right" type="number" min="0" step=".01" v-model="invoice_product.line_total" />
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <!-- </div> -->
                                
                                <div class="form-group row col-md-3 ">
                                <button type='button' class="btn btn-info" @click="addNewRow">
                                    <i class="fas fa-plus-circle"></i>
                                    Nueva Fila
                                </button>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Importe Total Bs.:</label>
                                    <div class="col-md-3">
                                        <input readonly class="form-control text-right" type="number" min="0" step=".01" v-model="invoice_subtotal" />
                                    <span class="text-error">{{ errors.first('importetotal')}}</span>   
                                    </div>                                
                                    <label class="form-control-label" for="text-input">Codigo Control:</label>
                                    <div class="col-md-3">
                                        <input v-validate.initial="'required'" readonly class="form-control text-right" type="text" name="codigocontrol" v-model="codigocontrol" />
                                    </div>
                                </div>                                
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>

                            <button :disabled = "errors.any()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarFactura()">Guardar</button>
                            <button :disabled = "errors.any()" type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarFactura()">Actualizar</button>

                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
        </main>
</template>


<script>        
    // import * as pdf from '../../pdf.js';
    import * as factura from '../../factura.js';

    export default {
        data (){
            return {
                factura_id: 0,
                numerofactura : '',
                codigocontrol:'',
                razonsocial:'',                
                nit:'',

                invoice_subtotal: 0,
                invoice_total: 0,
                invoice_tax: 5,
                
                invoice_products: [{
                    product_no: '',
                    product_name: '',
                    product_price: '',
                    product_qty: '',
                    line_total: 0
                }],
                

                detalle:'',
                importeparcial:'',
                importetotal:'',
                importecf:'',
                arrayFactura : [],
                arrayFacturaParametro:[],
                maxfacturavalor:'',
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorFactura : 0,
                errorMostrarMsjFactura : [],
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

            ver_factura(ft) {
                this.invoice_products = [];
                //console.log(factura);
                var detalle_fin = [];
                
                var aux1 = ft.detalle.split(',');

                for (var i=0; i<aux1.length; i++) {    
                    var aux2 = aux1[i].split('|');
                    detalle_fin.push({
                    product_name: aux2[0],
                    product_price: aux2[1],
                    product_qty: aux2[2],
                    line_total: aux2[3],
                    });
                }
                 _pdf.imgToBase64('img/iconad.png', function (base) {
                  _pdf.openRecibo(ft.numerofactura,ft.codigocontrol,ft.razonsocial,ft.nit,detalle_fin,ft.importetotal,base); 
                });
            },

            calculateTotal() {
                var subtotal, total;
                subtotal = this.invoice_products.reduce(function (sum, product) {
                    var lineTotal = parseFloat(product.line_total);
                    if (!isNaN(lineTotal)) {
                        return sum + lineTotal;
                    }
                }, 0);

                this.invoice_subtotal = subtotal.toFixed(2);

                total = (subtotal * (this.invoice_tax / 100)) + subtotal;
                total = parseFloat(total);
                if (!isNaN(total)) {
                    this.invoice_total = total.toFixed(2);
                } else {
                    this.invoice_total = '0.00'
                }

                //para el codigo de control
                var hoy = new Date();
                var datehoy=hoy.getFullYear()+''+hoy.getMonth()+1+''+hoy.getDate();  
                if(this.invoice_subtotal!=0) {
                    this.codigocontrol=_code.genCode(
                        this.arrayFacturaParametro[0].numeroautorizacion,//Numero de autorizacion
                        //this.numerofactura,//Numero de factura
                        '934509',
                        this.nit,//Número de Identificación Tributaria o Carnet de Identidad
                        '20080825',//fecha de transaccion
                        this.invoice_subtotal,//Monto de la factura
                        this.arrayFacturaParametro[0].llave//Llave de dosificación 
                    );
                }
                else 
                    this.codigocontrol='';
                
                // console.log('Cod control:',_code.genCode(
                //     '7904005357862',//Numero de autorizacion
                //     '297141',//Numero de factura
                //     '331073014',//Número de Identificación Tributaria o Carnet de Identidad
                //     '20071018',//fecha de transaccion
                //     '39375',//Monto de la factura
                //     '_(Ia6BU#Q_*Qc{T4P}s{QrhF+%cF-Qrjtviv]TIxgImcnKWX6jbn6}Mt=up{a6X3'//Llave de dosificación 
                // ));
            },

            calculateLineTotal(invoice_product) {
                var total = parseFloat(invoice_product.product_price) * parseFloat(invoice_product.product_qty);
                if (!isNaN(total)) {
                    invoice_product.line_total = total.toFixed(2);
                }
                this.calculateTotal();
                },
                
            deleteRow(index, invoice_product) {
                var idx = this.invoice_products.indexOf(invoice_product);
                console.log(idx, index);
                if (idx > -1) {
                    this.invoice_products.splice(idx, 1);
                }
                
                this.calculateTotal();
            },
            
            addNewRow() {
                this.invoice_products.push({
                    product_no: '',
                    product_name: '',
                    product_price: '',
                    product_qty: '',
                    line_total: 0
                });
            },
            
            dataparametro (){
                let me=this;
                var url= '/con_factura/dataparametro';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayFacturaParametro = respuesta.facturaparametro.data;         //console.log(me.arrayFacturaParametro[0].numeroautorizacion);           
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            maxfactura (){
                let me=this;
                var url= '/con_factura/maxfactura';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.maxfacturavalor = respuesta['maxfactura'];     //console.log(me.maxfacturavalor);    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            listarFactura (page,buscar,criterio){
                let me=this;
                var url= '/con_factura?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayFactura = respuesta.factura.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarFactura(page,buscar,criterio);
            },
            
            registrarFactura(){ 
               let me = this;
                axios.post('/con_factura/registrar',{
                    'numerofactura': this.numerofactura,
                    'codigocontrol': this.codigocontrol,
                    'razonsocial': this.razonsocial,                    
                    'nit': this.nit,
                    'detalle': this. invoice_products,
                    'importetotal': this.invoice_subtotal,
                    'importecf': this.invoice_subtotal                    
                }).then(function (response) {                    
                    
                    if (response.data.length) {                        
                        swal(
                            // response.data,
                            'El valor ya existe',
                            'Ingresar una dato diferente',
                            'error')
                    }
                    else { 
                        _pdf.imgToBase64('img/iconad.png', function (base) {
                            _pdf.openRecibo(me.numerofactura,me.codigocontrol,me.razonsocial,me.nit,me.invoice_products,me.importetotal,base); 
                        });
                        
                        me.cerrarModal();                        
                        me.listarFactura(1,'','nombreinstitucion');
                    }
                    
                }).catch(function (error) {
                    console.log(error);
                });
            },

            actualizarFactura(){  
                let me = this;
                axios.put('/con_factura/actualizar',{
                    'idfactura': this.factura_id, 
                    'numerofactura': this.numerofactura,
                    'codigocontrol': this.codigocontrol,
                    'razonsocial': this.razonsocial,                    
                    'nit': this.nit,
                    'detalle': this. invoice_products,
                    'importetotal': this.invoice_subtotal,
                    'importecf': this.invoice_subtotal
                }).then(async function (response) {
                    if(response.data.length){
                        swal(
                            'El Valor ya Existe',
                            'Ingresa un dato Diferente',
                            'error'
                        )
                    }
                    else{                                                
                        //campo detalle: detalle|importe|cantidad|total
                        //factura.genera(nit,nombre,detalle,monto)                                                
                        // var resultado = await factura.genera('123123123','nombre','121231231|23.2|1|23.3,84846548|11.23|1|11.23',23.36);                         
                        // console.log('dev: ' + resultado['nrofactura']);   
                        me.cerrarModal();
                        me.listarFactura(1,'','nombreinstitucion');
                    }
                }).catch(function (error) {
                    console.log(error);
                });                 
            },

            desactivarFactura(id){
               swal({
                title: 'Esta seguro de desactivar?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/con_factura/desactivar',{
                        'idfactura': id
                    }).then(function (response) {
                        me.listarFactura(1,'','nombreinstitucion');
                        swal(
                        'Desactivado!',
                        'El registro ha sido desactivado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
            
            activarFactura(id){
               swal({
                title: 'Esta seguro de activar?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/con_factura/activar',{

                         'idfactura': id
                    }).then(function (response) {
                        me.listarFactura(1,'','nombreinstitucion');
                        swal(
                        'Activado!',
                        'El registro ha sido activado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
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
            this.listarFactura(1,this.buscar,this.criterio);
            this.dataparametro();
            this.maxfactura();                    
        }
    }
</script>
