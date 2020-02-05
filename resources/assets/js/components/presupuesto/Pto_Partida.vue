<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-nompartida"><a href="/">Registro de Partida </a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Partida
                        <button type="button" @click="abrirModal('partida','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Registrar Partida
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!-- <select class="form-control col-md-3" v-model="criterio">
                                      <option value="idconfig">Nombre</option>                                      
                                    </select> -->
                                    <input type="text" v-model="buscar" @keyup.enter="listarPartida(1,buscar)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarPartida(1,buscar)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>

                        <table class="table table-bordered table-striped table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Opciones</th>
                                    <th>Codigo</th>                                    
                                    <th>Nombre</th>                                    
                                    <th>Pto. Inicial</th>
                                    <th>Pto. Total</th>
                                    <th>Tipo</th>
                                    <th>Gestion</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="partida in arrayPartida" :key="partida.id">
                                    <td>
                                        <button type="button" @click="abrirModal('partida','actualizar',partida)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button>
                                        <button type="button" @click="abrirModalConta('partida','registrar',partida)" class="btn btn-warning btn-sm">
                                          <i class="icon-share"></i>
                                        </button>
                                        <template v-if="partida.activo">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarPartida(partida.idpartidan2)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarPartida(partida.idpartidan2)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="partida.codigon2"></td>
                                    <td v-text="partida.nombren2"></td>                                    
                                    <td v-text="partida.ptoinicial"></td>                                    
                                    <td v-text="partida.ptototal"></td>
                                    <td v-if="partida.tipo==='r'">Recurso</td>                                    
                                    <td v-else-if="partida.tipo==='g'">Gasto</td>                                    
                                    <td v-else  >---</td> 
                                    <td v-text="partida.gestion"></td>                                    
                                    <td>
                                        <div v-if="partida.activo">
                                            <span class="badge badge-success">Activo</span>
                                        </div>                    
                                        <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                        <div v-if="partida.idcuenta">
                                            <span class="badge badge-success">Cuenta Asignada</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Cuenta NO Asignada</span>
                                        </div>                                        
                                    </td>
                                </tr>                                
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-nompartida" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar)">Ant</a>
                                </li>
                                <li class="page-nompartida" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar)" v-text="page"></a>
                                </li>
                                <li class="page-nompartida" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

            <div v-if="Modalview === 'P'">
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
                        <!-- <ul>
                            <li v-for="error in errors.all()" v-bind:key="error"> {{ error }}</li>
                        </ul> -->
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row table-info">
                                    <div class="container">
                                        <div class="row justify-content-center">
                                            <div class="col-md-2">
                                                <label for="text-input">Gestion</label>        
                                            </div>
                                            <div class="col-md-3">
                                                <label for="text-input">Codigo</label>
                                            </div>
                                            <div class="col-md-5">
                                                <label for="text-input">Nombre</label>
                                            </div>
                                        </div>

                                        <div class="row justify-content-center">
                                            <div class="col-md-2">
                                                <select 
                                                    v-model="idpei"
                                                    v-validate.initial="'required'"
                                                    name="pei">
                                                    <option selected="selected" value="" disabled >Gestion...</option>
                                                    <option v-for="pei in arrayPei" :key="pei.idpei" :value="pei.idpei" v-text="pei.gestion"></option>
                                                </select>   
                                                <span class="text-error">{{ errors.first('pei')}}</span>
                                            </div>
                                            <div class="col-md-3">
                                                <input v-validate.initial="'required'" 
                                                    type="text" 
                                                    v-model="codigo" 
                                                    class="form-control" 
                                                    placeholder="codigo"
                                                    name="codigo">
                                                <span class="text-error">{{ errors.first('codigo')}}</span>
                                            </div>
                                            <div class="col-md-5">
                                                <input v-validate.initial="'required'" 
                                                    type="text" 
                                                    v-model="nompartida" 
                                                    class="form-control" 
                                                    placeholder="Nombre Partida"
                                                    name="nompartida">
                                                <span class="text-error">{{ errors.first('nompartida')}}</span>
                                            </div>
                                        </div>
                                    </div>                                    
                                </div>

                                <div class="form-group row table-info">
                                    <div class="container">
                                        <div class="row justify-content-center" >
                                            <div class="col-5">
                                                <label class="form-control-label" for="descripcion">Descripcion</label>
                                            </div>
                                             <div class="col-3">
                                                <label class="form-control-label" for="text-input">Pto. Inical</label>        
                                            </div>
                                             <div class="col-3">
                                                <label class="form-control-label" for="text-input">Pto. Total</label>
                                            </div>
                                        </div>
                                    
                                        <div class="row justify-content-center">
                                            <div class="col-5">
                                                <textarea v-validate.initial="'required'" 
                                                v-model="descripcion" 
                                                class="form-control" 
                                                placeholder="descripcion"
                                                name="descripcion"> </textarea>                                                                        
                                                <span class="text-error">{{ errors.first('descripcion')}}</span>
                                            </div>
                                            <div class="col-md-3">
                                                <vue-numeric  
                                                    class="form-control input-importe"
                                                    currency="Bs." 
                                                    separator="," 
                                                    v-validate.initial="{required:true}"                                                    
                                                    v-model="ptoinicial"
                                                    v-bind:precision="2"
                                                    name="ptoinicial">
                                                </vue-numeric>
                                                <span class="text-error">{{ errors.first('ptoinicial')}}</span>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Total Bs.: {{ptototal}}</label>
                                            </div>

                                        </div>
                                    </div>                                    
                                </div>
                                <div class="form-group row table-info">
                                    <div class="container">
                                        <div class="row justify-content-center">
                                            <div class="row">
                                                <label  for="text-input">Dividir entre 12 meses</label>
                                                <div class="col-auto">                                        
                                                    <input v-model="valida1" type="checkbox" @change="calculomensual(ptototal)">
                                                    <label v-if="valida1">Monto mensual Bs.: {{calculo}} </label>
                                                </div>
                                            </div>  
                                        </div>

                                        <div class="row justify-content-center">
                                            <div class="row">
                                                <label  for="text-input">Valida Ejecucion</label>      
                                                <div class="col-md-3">
                                                    <label class="switch switch-3d switch-primary">
                                                    <input class="switch-input" v-model="valida2" type="checkbox" >
                                                    <span class="switch-slider"></span>
                                                    </label>
                                                </div>                                        
                                            </div>  
                                        </div>
                                    </div>
                                </div>   

                                <!-- construccion del arbol para la partidas -->
<!-- {{children[2].children[0].name}} -->

<!-- <div v-for="(chi, k) in children" :key="k">
{{k}} : {{chi.name}} <br>

<div v-for="(chi2, k1) in chi.children" :key="k1">
<li>
    <ul>
            {{k1}} : {{chi2.name}}
    </ul>
</li>
</div>    
</div> -->
                                <div class="form-group" >
                                    <label class="col-md-3 form-control-label" for="text-input"><B>ARBOL DE LA PARTIDA</B></label>
                                    <DIV class="col-md-3">
                                        <button type='button' class="btn btn-info" @click="addPartida">Nivel1</button>                                        
                                    </DIV>
                                    <div>
<div class="scroll-resultado">

                                    <table class="table table-bordered table-dark table-sm">
                                    <tr v-for="(chi, k) in partidaAnidada" :key="k">                                        
                                    <td class="bg-success">
                                        Total Bs: {{parcial}} {{parcialTotal[k]}} <br>
                                        <input type="text" v-validate.initial="'required'" name="nombren1" placeholder="Partida nivel 1" v-model="chi.name">
                                        <input type="number" v-validate.initial="'required'" name="codigon1" placeholder="Codigo nivel 1" v-model="chi.codigo">
                                        <i class="far fa-trash-alt" @click="deletePartida(k, chi)"></i>                                        
                                        <div>
                                            <span v-if="errors.first('nombren1')" class="text-error">Error Partida</span>
                                            <span v-if="errors.first('codigon1')" class="text-error">Error Codigo</span>
                                        </div>                                        
                                        
                                    </td>
                                    <td class="bg-info">
                                        <button type='button' class="btn btn-warning" @click="addPartidaHijo(k)">Nivel2</button>
                                        <tr v-for="(chi2, k1) in chi.children" :key="k1">
                                            <td class="col-4">
                                                <input type="text" v-validate.initial="'required'" placeholder="Partida nivel 2" name="nombren2" v-model="chi2.name">
                                                <input type="number" v-validate.initial="'required'" placeholder="Codigo nivel 2" name="codigon2" v-model="chi2.codigo">                                            
                                                <div>
                                                    <!-- <span class="text-error">{{ errors.first('nombren2')}}</span>
                                                    <span class="text-error">{{ errors.first('codigon2')}}</span> -->
                                                    <span v-if="errors.first('nombren2')" class="text-error">Error Partida</span>
                                                    <span v-if="errors.first('codigon2')" class="text-error">Error Codigo</span>
                                                </div>                  
                                            </td>
                                            <td>                                                
                                                <!-- <input type="numeric" v-validate.initial="'required'" placeholder="monto" name="monto" v-model="chi2.monto"> -->
                                                <vue-numeric  
                                                    class="form-control input-importe"
                                                    currency="Bs." 
                                                    separator="," 
                                                    v-validate.initial="{required:true}"                                                    
                                                    v-model="chi2.monto"
                                                    v-bind:precision="2"
                                                    name="monto">
                                                </vue-numeric>
                                                <span class="text-error">{{ errors.first('monto')}}</span>                  
                                            </td>
                                            <td>                                                
                                                <select 
                                                    v-model="chi2.tipo"
                                                    v-validate.initial="'required'"
                                                    name="tipopartida">
                                                    <option selected="selected" value="" disabled >Tipo...</option>
                                                    <option value="r">Recursos</option>
                                                    <option value="g">Gastos</option>
                                                </select>                              
                                                <i class="far fa-trash-alt" @click="deletePartidaHijo(k, chi2)"></i>
                                                <div><span v-if="errors.first('tipopartida')" class="text-error">Error Partida</span></div>                                                
                                            </td>
                                        </tr>
                                    </td>
                                    </tr>
                                    </table>
</div>                                    
                                    </div>
                                </div>                                
                                
                                                              
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>

                            <button :disabled = "errors.any()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPartida(Modalview)">Guardar</button>
                            <button :disabled = "errors.any()" type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPartida()">Actualizar</button>

                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            </div>

            
            <div v-else-if="Modalview === 'C'">
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
                                <div class="form-group row table-info">
                                    <div class="container">
                                        <div class="row justify-content-center">                                            
                                            <div class="col-md-3">
                                                <label for="text-input">Codigo</label>
                                            </div>
                                            <div class="col-md-5">
                                                <label for="text-input">Nombre</label>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">                                            
                                            <div class="col-md-3">
                                                {{codigo}}
                                            </div>
                                            <div class="col-md-5">
                                                {{nompartida}}  
                                            </div>
                                        </div>

                                        <div class="container">
                                            <div class="row justify-content-center">        
                                                
                                                    <label for="text-input"> Relacion Cuenta Contable
                                                    <button type='button' class="btn btn-info" @click="addNewRow">
                                                        <i class="fas fa-plus-circle"></i>
                                                        Addicionar
                                                    </button></label>
                                                
                                            </div>
                                                <div class="row justify-content-center">
                                                    <div class="col-9"> 
                                                        <template v-if="registra">                                            
                                                            <tr v-for="(invoice_product, k) in invoice_products" :key="k">       
                                                                <td class="col-9">
                                                                    <Ajaxselect v-if="test" 
                                                                        ruta="/con_cuentas/selectcuentapto?buscar=" @found="cuentas" @cleaning="clean"
                                                                        resp_ruta="cuentas"
                                                                        labels="codcuenta,nomcuenta"
                                                                        placeholder="Ingrese texto" 
                                                                        :clearable="false"                                                                        
                                                                        idtabla="idcuenta">
                                                                    </Ajaxselect>                     
                                                                </td>
                                                                <td>
                                                                    <select 
                                                                        v-model="invoice_product.selected1"
                                                                        v-validate.initial="'required'"
                                                                        name="selected1">
                                                                        <option selected="selected" value="" disabled >Tipo Cuenta...</option>
                                                                        <option value="1">Debe</option>
                                                                        <option value="2">Haber</option>
                                                                    </select>
                                                                </td>
                                                                <td scope="row" class="trashIconContainer"><i class="far fa-trash-alt" @click="deleteRow(k, invoice_product)"></i></td>
                                                            </tr>
                                                            <span class="text-error">{{ errors.first('selected1')}}</span>                                                
                                                        </template>                                        

                                                        <template v-else>                                              
                                                                <tr v-for="(invoice_product, k) in invoice_products" :key="k">       
                                                                <td>                                   
                                                                    <Ajaxselect v-if="test" 
                                                                        ruta="/con_cuentas/selectcuentapto?buscar=" @found="cuentas" 
                                                                        resp_ruta="cuentas"
                                                                        labels="codcuenta,nomcuenta"
                                                                        placeholder="Ingrese texto" 
                                                                        :id="invoice_product.product_no"
                                                                        idtabla="idcuenta">
                                                                    </Ajaxselect>            
                                                                </td>
                                                                <td>
                                                                    <select 
                                                                        v-model="invoice_product.selected1"
                                                                        v-validate.initial="'required'"
                                                                        name="selected1">
                                                                        <option selected="selected" value="" disabled >Tipo Cuenta...</option>
                                                                        <option value="1">Debe</option>
                                                                        <option value="2">Haber</option>
                                                                    </select>
                                                                </td>
                                                                <td scope="row" class="trashIconContainer"><i class="far fa-trash-alt" @click="deleteRow(k, invoice_product)"></i></td>
                                                            </tr>
                                                        
                                                        </template>
                                                    </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>

                            <button :disabled = "errors.any()" type="submit" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPartida(Modalview)">Guardar</button>
                            <button :disabled = "errors.any()" type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPartida()">Actualizar</button>

                        </div>
                    </div>                
                </div>
            </div>
            </div>
            <!--Fin del modal-->
        </main>
</template>
<script>
        
    export default {
        data (){
            return {
                Modalview:'',
                invoice_products: [],                
                partidaAnidada:[],
                
                // children: [
                //     { name: 'hello' },
                //     { name: 'wat',
                //         children: [
                //             {name:'jaja'}
                //         ]
                //     },
                //     { name: 'child folder',
                //         children: [
                //             {name: 'child foldern3'},
                //             {name: 'hello n3' },
                //             {name: 'wat n3' },
                //         ]
                //     },
                //     { name: 'nuevo',
                //         children: [
                //             {name: 'n12'},
                //             {name: 'ss' },
                //             {name: 'adsf' },
                //             {name: 'fgfdgd' },
                //         ]
                //     }
                // ],

                

                datocuenta: [],                

                listapartida_id: 0, 
                listapartidan2_id: 0, test:1, registra:1,
                cuentacombinada:0, 
                idpartida : '', 
                codigo : '',
                nompartida: '',
                descripcion: '',
                idpei: '',
                idcuenta: '',
                tipopartida: '',
                selected1: '',
                ptoinicial: '',
                ptomodificado: '',
                ptototal: '', 
                ptototalaux: '', 
                parcialTotal: [],
                mensual: '',
                valida1: '0',
                valida2: '0',
                arrayPartida : [], arrayValor:[],
                arrayPei : [],
                valido: 0,                
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorEscalafon : 0,
                errorMostrarMsjEscalafon : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 10,
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

            },
            calculo: function() {
                var formatNumber = {
                    separador: ",", // separador para los miles
                    sepDecimal: '.', // separador para los decimales
                    formatear:function (num){
                    num +='';
                    var splitStr = num.split('.');
                    var splitLeft = splitStr[0];
                    var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
                    var regx = /(\d+)(\d{3})/;
                    while (regx.test(splitLeft)) {
                    splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
                    }
                    return this.simbol + splitLeft +splitRight;
                    },
                    new:function(num, simbol){
                    this.simbol = simbol ||'';
                    return this.formatear(num);
                    }
                };
                var aa = parseFloat(this.ptototalaux/12);
                var cadames=aa.toFixed(2);
                var tot = formatNumber.new(cadames, "")
                
                return(tot);
            },

            isFolder: function () {
            return this.model.children &&
                this.model.children.length
            },
            parcial: function () {                

                var formatNumber = {
                    separador: ",", // separador para los miles
                    sepDecimal: '.', // separador para los decimales
                    formatear:function (num){
                    num +='';
                    var splitStr = num.split('.');
                    var splitLeft = splitStr[0];
                    var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
                    var regx = /(\d+)(\d{3})/;
                    while (regx.test(splitLeft)) {
                    splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
                    }
                    return this.simbol + splitLeft +splitRight;
                    },
                    new:function(num, simbol){
                    this.simbol = simbol ||'';
                    return this.formatear(num);
                    }
                };

                this.parcialTotal.length=0;
                for (var a=0; a<this.partidaAnidada.length; a++) {
                    var suma=0;
                    for (var i=0; i<this.partidaAnidada[a].children.length; i++) {                    
                        suma = suma + parseFloat(this.partidaAnidada[a].children[i].monto);
                    }
                    this.parcialTotal[a]=suma;                
                }
                
                var sumaT=0;
                for (var x=0; x<this.parcialTotal.length;x++) {
                    sumaT = sumaT + this.parcialTotal[x];
                }
                this.ptototalaux = sumaT; 
                this.ptototal = formatNumber.new(sumaT);
            }
        },
        methods : {

            addPartida (){
                this.partidaAnidada.push({
                    name: '',
                    children: [{name:'',monto:'',tipo:''}]
                }) 
                console.log(this.partidaAnidada);
            },

            addPartidaHijo (e){ 
                this.partidaAnidada[e].children.push({
                    name: '', monto: 0, tipo: ''
                })                 
                console.log(this.partidaAnidada);
            },

            deletePartida(index, valor) {
                let me=this;    
                var idx = this.partidaAnidada.indexOf(valor);
                console.log(idx, index);
                if (idx > -1) {
                    this.partidaAnidada.splice(idx, 1);
                }                                                
                console.log(me.partidaAnidada);
            },

            deletePartidaHijo(index, valor) {
                let me=this;    
                var idx = this.partidaAnidada[index].children.indexOf(valor);
                console.log(idx, index);
                if (idx > -1) {
                    this.partidaAnidada[index].children.    splice(idx, 1);
                }                                                
                console.log(me.partidaAnidada);
            },

            addNewRow() {
                this.invoice_products.push({                    
                    product_no: '',
                    selected1: '',
                });                
                console.log(this.invoice_products);
            },

            deleteRow(index, invoice_product) {
                let me=this;    
                var idx = this.invoice_products.indexOf(invoice_product);
                console.log(idx, index);
                if (idx > -1) {
                    this.invoice_products.splice(idx, 1);
                    this.datocuenta.splice(idx, 1);
                }                                                
                console.log(me.invoice_products);
                console.log(me.datocuenta);
            },

            cuentas(cuentas){ 
                this.datocuenta.push({cuentita:cuentas.idcuenta})                
                console.log(this.datocuenta);
            },

            cambiarPagina(page,buscar){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarPartida(page,buscar);
            },

            async arbol(id) {                
                var url= '/pto_partida?idpartida=' + id;
                let response = await axios.get(url);                   
                    this.partidaAnidada = response.data.niveles;
                    //console.log(this.partidaAnidada);                           
            },

            registrarPartida(e){  
                let me = this; 
                if (e==='C') { //es contabilidad

                    var aux1=0;
                    for (var i=0; i<this.datocuenta.length; i++) {
                        if (aux1===0) {
                            me.cuentacombinada = this.datocuenta[i].cuentita; 
                            me.selected1 = this.invoice_products[i].selected1;
                            aux1 ++;
                        }
                        else {                                                     
                            me.cuentacombinada = me.cuentacombinada +',' +this.datocuenta[i].cuentita;
                            me.selected1 = me.selected1 +',' +this.invoice_products[i].selected1;
                        }
                    } 
                    axios.put('/pto_partida/partidaCuentas',{                    
                        'idpartida': this.listapartidan2_id,                                                
                        'tipocuenta': this.selected1,                        
                        'idcuenta': this.cuentacombinada,                        
                    }).then(function (response) {                    
                        if (response.data.length) {                        
                            swal(
                                // response.data,
                                'El valor ya existe',
                                'Ingresar una dato diferente',
                                'error')
                        }
                        else {
                            me.cerrarModal();
                            me.listarPartida(1,'');
                        }
                        
                    }).catch(function (error) {
                        console.log(error);
                    });
                }
                else if (e==='P') {  // es para partidas
                        axios.post('/pto_partida/registrar',{                    
                        'codigo': this.codigo,
                        'nompartida': this.nompartida,
                        'descripcion': this.descripcion,
                        'idpei': this.idpei,                    
                        'ptoinicial': this.ptoinicial,                    
                        'ptototal': this.ptototal, 
                        'valida1': this.valida1,
                        'valida2': this.valida2,
                        // 'idcuenta': this.cuentacombinada,
                        'niveles': this.partidaAnidada
                    }).then(function (response) {                    
                        if (response.data.length) {                        
                            swal(
                                // response.data,
                                'El valor ya existe',
                                'Ingresar una dato diferente',
                                'error')
                        }
                        else {
                            me.cerrarModal();
                            me.listarPartida(1,'');
                        }
                        
                    }).catch(function (error) {
                        console.log(error);
                    });
                }                
            },

            actualizarPartida(){
                let me = this; console.log(this.datocuenta);
                var aux1=0;
               
                axios.put('/pto_partida/actualizar',{
                    'idconfig': this.idconfig,
                    'idpartida': this.listapartida_id,
                    'codigo': this.codigo,
                    'nompartida': this.nompartida,
                    'descripcion': this.descripcion,
                    'idpei': this.idpei,
                    // 'tipopartida': this.tipopartida,
                    // 'tipocuenta': this.selected1,
                    // 'ptomodificado': this.ptomodificado,
                    'ptoinicial': this.ptoinicial,                
                    'ptototal': this.ptototal,
                    'valida1': this.valida1,
                    'valida2': this.valida2,
                    // 'idcuenta': this.cuentacombinada,
                    'niveles': this.partidaAnidada
                }).then(function (response) {
                    if(response.data.length){
                        swal(
                            'El Valor ya Existe',
                            'Ingresa un dato Diferente',
                            'error'
                        )
                    }
                    else{
                        me.cerrarModal();
                        me.listarPartida(1,'');
                    }
                }).catch(function (error) {
                    console.log(error);
                }); 
            },

            abrirModal(modelo, accion, data = []){
                this.Modalview='P';
                switch(modelo){
                    case "partida":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                //this.invoice_products.length = 0
                                //this.datocuenta.length = 0
                                this.partidaAnidada.length=0;
                                this.partidaAnidada = [{name:'',codigo:'',children:[{name:'',codigo:'',monto:'',tipo:''}]}];
                                this.modal = 1;
                                this.tituloModal = 'Registrar Partida';
                                this.idpartida= '';
                                this.codigo= '';
                                this.nompartida= '';
                                this.descripcion = '';
                                this.idpei = '';
                                //this.idcuenta = '';
                                this.tipopartida = '';
                                //this.selected1 = '';
                                this.ptoinicial = '';
                                this.ptototal = '';
                                this.valida1 = '';
                                this.valida2 = '';
                                this.tipoAccion=1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(invoice_product.data);
                                //this.registra=0;
                                this.listapartida_id=data['idpartida'];
                                this.modal=1; //this.test=0;
                                this.tituloModal='Actualizar Partida';
                                this.tipoAccion=2;                                
                                this.codigo= data['codigo'];
                                this.nompartida= data['nompartida'];
                                this.descripcion = data['descripcion'];
                                this.idpei = data['idpei'];
                                //this.invoice_products.length = 0
                                //this.datocuenta.length = 0
                                //this.partidaAnidada.length=0;
                                this.arbol(data['idpartida']);
                                
                                console.log(this.partidaAnidada);
                                
                                                                                                
                                this.tipopartida = data['tipopartida'];
                                this.ptoinicial = data['ptoinicial'];
                                //this.ptomodificado = data['ptomodificado'];
                                this.ptototal = data['ptototal'];                     
                                this.valida1 = data['valida1'];                     
                                this.valida2 = data['valida2'];                     
                                this.valido = data['valido'];
                                //setTimeout(this.refre,200);
                                break;
                            }
                        }
                    }
                }
                this.selectPei();   
            },

            abrirModalConta(modelo, accion, data = []){ 
                this.Modalview='C'; 
                switch(modelo){
                    case "partida":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.invoice_products.length = 0
                                this.datocuenta.length = 0
                                this.registra=1;

                                if (data['idcuenta']!='') {
                                    var array = JSON.parse("[" + data['idcuenta'] + "]"); 
                                    var array1 = JSON.parse("[" + data['tipocuenta'] + "]"); 
                                    for (var i=0; i < array.length; i++){                                    
                                        this.invoice_products.push({product_no:array[i],selected1:array1[i]});
                                        this.datocuenta.push({cuentita:array[i]});
                                    }
                                    this.listapartidan2_id=data['idpartidan2']; 
                                    this.modal = 1;
                                    this.tituloModal = 'Registrar/Actualizar Contabilidad';
                                    this.idpartida= data['idpartinan2'];
                                    this.codigo= data['codigon2'];
                                    this.nompartida= data['nombren2'];                                
                                    this.tipoAccion=1; 
                                    setTimeout(this.refre,200);
                                    break;
                                }
                                else {
                                    this.idcuenta = '';
                                    this.selected1 = '';
                                    this.listapartidan2_id=data['idpartidan2']; 
                                    this.modal = 1;
                                    this.tituloModal = 'Registrar/Actualizar Contabilidad';
                                    this.idpartida= data['idpartinan2'];
                                    this.codigo= data['codigon2'];
                                    this.nompartida= data['nombren2'];                                
                                    this.tipoAccion=1;                                     
                                    break;
                                }     
                                console.log(this.invoice_products);
                                console.log(this.datocuenta);                                                          
                            }
                        }
                    }
                }
             },

            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.idpartida='';

            },
            
            selectPei(){
                let me=this;
                var url= '/pto_pei/selectPei';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayPei = respuesta.gestionpei;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            selectValoraporte(){
                let me=this;
                var url= '/pto_partida/selectValoraporte';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayValoraporte = respuesta.aportes;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            calculomensual(total) {
                this.calculo;
                if (total===0 || total==='')
                    alert('Valor no debe ser 0 o vacio');
                else {
                    var cadames=total/12;
                    //this.mensual = cadames.toFixed(2);
                }
            },

            clean(){
                //this.invoice_products = [];
                console.log('clean')
            },

            refre () {
                this.test=1;
                this.registra=0;
            },

            listarPartida (page,buscar){
                let me=this;
                var url= '/pto_partida?page=' + page + '&buscar='+ buscar;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayPartida = respuesta.partidas.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            desactivarPartida(id){ 
               swal({
                title: 'Esta seguro de desactivar este Partida?',
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

                    axios.put('/pto_partida/desactivar',{  //desactivara el nivel 2, es decir la partida como tal
                        'idpartida': id
                    }).then(function (response) {
                        me.listarPartida(1,'');
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

            activarPartida(id){
               swal({
                title: 'Esta seguro de activar este Partida?',
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

                    axios.put('/pto_partida/activar',{ //activara el nivel 2, es decir la partida como tal
                         'idpartida': id
                    }).then(function (response) {
                        me.listarPartida(1,'');
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
            
        },
        mounted() {
            this.listarPartida(1,this.buscar);
            console.log(this.children);
            // this.children.push({                    
            //         name: 'jiji',
            //         children: [{name: 'jiji2'},{name: 'jiji3'}]
            //     });                
            // this.children[2].children.push({
            //     name :'ppppp'
            // })
            
        }
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-nompartida !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-style: italic;
    }
    .scroll-resultado {
        position:relative;
        padding:15px;
        height:300px;
        overflow-y:scroll;
    }  
    ::-webkit-scrollbar {
    width: 6px;
    border-radius: 10px;
    }
    ::-webkit-scrollbar-track {
    background: transparent;
    }
    ::-webkit-scrollbar-thumb {
    background: #2186aab0;
    border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb:hover {
    background: #276f8a;
    }
</style>
