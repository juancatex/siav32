<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
    
    <div class="container-fluid">
    <div class="card ">
        <div class="card-header">
            <div class="panel-heading">
                <h4>Importar XLSX</h4>
            </div>
        </div>
            <div class="card-body col-sm-offset-3 col-sm-12">
                <table class="table table-bordered table-striped table-sm col-sm-offset-6">
                            <thead>
                                <tr>
                                    <th colspan="5">Historial de Archivos Subidos</th>
                                </tr>
                                <tr>
                                    <th>Lote</th>
                                    <th>Nombre Archivo</th>
                                    <th>Tipo Aporte</th>
                                    <th>Fecha Aporte </th>
                                    <th>Fecha-hora Carga &darr;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="datoscsv in arrayCsvdata" :key="datoscsv.idcsvdata">
                                    <td v-text="datoscsv.idlote"></td>
                                    <td v-text="datoscsv.csv_filename"></td>
                                    <td v-text="datoscsv.descripcion"></td>
                                    <td v-text="datoscsv.fecha_archivo" ></td>
                                    <td v-text="datoscsv.created_at" ></td>
                                </tr>                                
                            </tbody>
                        </table>
            <div class="form-row">
                <div class="form-group col-sm-12">
                    <label for="csv_file"><strong> XLSX para importar</strong></label>
                    <div >
                        <!-- <input type="file" ref="fileInput" id="csv_file" name="csv_file" class="form-control" @change="LoadCSV($event)" multiple="true" > -->
                    <excelReader v-if="resetExcelVue" col_codigo="COA" :remove="removef" @array_Files_Data="datasArray"></excelReader>    
                    </div>
                </div>

                <!-- <div class="form-group col-sm-3">
                    <label for="csv_file"><strong> Delimitador</strong></label>
                    <div >
                        <input type="text" class="form-control col-sm-3 w-100" size=1 v-model='delimitador' maxlength="1" minlength="1">
                        
                    </div>
                    
                </div> -->

            </div>

            <div class="form-row">
                <div class="form-group col-sm-4">
                    <span><strong> Fecha del Archivo</strong></span>
                        <input class="form-control formu-entrada" 
                        v-validate.initial="'required'"
                        type="date" 
                        v-model="fecha_aporte"
                        name="Fechar Archivo"
                        v-on:change.stop="verificarfecha()"
                        v-bind:max='fechanow'
                        >
                    <p class="text-error">{{ errors.first('Fecha Archivo')}}</p>
                </div>
                <div class="form-group col-sm-4">
                <span><strong>Tipo Aporte</strong></span>
                    <select class="form-control" 
                            v-model="idtipoaporte"
                            v-validate.initial="'required'"
                            name="Tipo Aporte">
                        <option value="0">Seleccionar</option>
                        <option v-for="tipoaporte in arrayTipoaporte" :key="tipoaporte.idtipoaporte" :value="tipoaporte.idtipoaporte" v-text="tipoaporte.descripcion"></option>
                    </select>                                        
                    <span class="text-error">{{ errors.first('Tipo Aporte')}}</span>   <!--Lineas Agregadas<-->
                </div>
                <div class="form-group col-sm-4">
                    <span><strong>Perfil de Cuenta</strong></span>
                        <select class="form-control" 
                                v-model="idperfilcuentamaestro"
                                v-validate.initial="'required'"
                                name="Perfil Cuenta">
                                <option value="0">Seleccionar</option>
                            <option v-for="perfilcuentamaestro in arrayPerfilcuentamaestro" :key="perfilcuentamaestro.idperfilcuentamaestro" :value="perfilcuentamaestro.idperfilcuentamaestro" v-text="perfilcuentamaestro.nomperfil"></option>
                        </select>                                        
                        <span class="text-error">{{ errors.first('Perfil Cuenta')}}</span>   <!--Lineas Agregadas<-->
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-sm-12">
                    <label for="csv_file"><strong> Glosa</strong></label>
                    <div >
                        <input class="form-control formu-entrada" 
                            v-validate.initial="'required'"
                            type="text" v-model="observaciones"
                            name="glosa">
                        
                        <span class="text-error">{{ errors.first('glosa')}}</span>
                        
                    </div>
                </div>
            </div>
                <div class="col-sm-offset-3 col-sm-12 panel-heading" >
                    <button :disabled = "errors.any()" type="submit" class="btn btn-primary" @click="subirCSV()">Subir XLSX</button>
                    <br>&nbsp;<br>
                    <!--<a href="#" class="btn btn-primary">Subir CSV</a>-->
                </div>
                <table v-if="parse_csv">
                    
                    <thead>
                        <tr>
                            <th v-for="key in parse_header" :key="key.key"
                                @click="sortBy(key)"
                                :class="{ active: sortKey==key }">
                                {{ key | capitalize }} 
                            <span class="arrow" :class="sortOrders[key]>0 ? 'asc':'dsc'"></span>
                            </th>
                        </tr>
                    </thead>
                    
                    <tr v-for="csv in parse_csv" :key="csv.key">
                        <td v-for="key in parse_header" :key="key.key">
                            {{ csv[key]}}
                        </td>
                    </tr>
                </table>
            </div>
    </div>
  </div>
 </div>
<!--Inicio del modal progreso-->
            
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title" >Progreso...</h6>
                            <!--
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close"></button>

                            <span aria-hidden="true">×</span>
                            -->
                        </div>
                        <div class="modal-body">
                            <div >
                                <div class="sk-folding-cube" v-if="files.length===0">
                                    <div class="sk-cube1 sk-cube"></div>
                                    <div class="sk-cube2 sk-cube"></div>
                                    <div class="sk-cube4 sk-cube"></div>
                                    <div class="sk-cube3 sk-cube"></div>
                                </div>
                                <!--
                                <div class="sk-wave" >
                                    <div class="sk-rect sk-rect1"></div>
                                    <div class="sk-rect sk-rect2"></div>
                                    <div class="sk-rect sk-rect3"></div>
                                    <div class="sk-rect sk-rect4"></div>
                                    <div class="sk-rect sk-rect5"></div>
                                </div>
                                -->
                                <!--<div class="col-md-6">                                    
                                    <img :src="'img/gif/cargando.gif'" style="width:100px">                                 
                                </div>    
                                -->
                            </div>
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
     
    import Vue from 'vue';
    import VeeValidate from 'vee-validate';
    
    const VueValidationEs = require('vee-validate/dist/locale/es');
        Vue.use(VeeValidate, 
        {
            locale: 'es',
            dictionary: 
            {
                es: VueValidationEs
            }
        });

    Vue.use(VeeValidate);

    export default {
        props:['idmodulo'],
        data (){
            return {
                removef:(valuein)=>{return valuein.CODIGO_CONCEPTO != '056'},//elimina del archivo todos los que noson aportes identificados por el codigo 56
                 resetExcelVue:1,
                modal:0,
                observaciones:'',
                channel_name:'',
                channel_fields:[],
                channel_entries:[],
                parse_header:[],
                parse_csv:[],
                sortOrders:{},
                sortKey:'',
                nombre_archivo:[],
                arrayCsvdata:[],
                files: [],
                delimitador:';',
                file: '',
                uploadPercentage: 0,
                fecha_aporte:'',
                arrayTipoaporte:[],
                arrayPerfilcuentamaestro:[],
                idperfilcuentamaestro:0,
                idtipoaporte:0, 
                arrayCabeceras:[],
                fechaactual: new Date(),
                fechanow:'',
                
                mes:'',
                anio:0,
                
            };
        },
        formOptions:{
            validateAfterLoad: true,
            validateAfterChanged: true
        },
        filters:{
            capitalize:function(str){
                return str.charAt(0).toUpperCase()+str.slice(1)
            }
        },
        methods : { 
            
            datasArray(data) {
                   this.parse_csv=[];
                   this.nombre_archivo=[];
               if(data!=null){
                     this.nombre_archivo=data.names;
                    this.parse_csv=data.values;    
                    for (let index = 0; index < this.parse_csv.length; index++) {
                        const element = this.parse_csv[index];
                        if(element.numpapeleta=='00009213'){
                                console.log(element);
                                const element2 = this.parse_csv[index-1];
                                console.log(element2);
                        }
                    }
               }
            },
            verificarfecha(){
                let me=this;

                this.idmodulo
                //console.log(this.idmodulo+ ' modulo');
                //console.log(me.fecha_aporte+"fecha");
                axios.post('/csvdata/fechaaporte',{
                        //'csv_header': this.parse_header,
                        'fecha_aporte': this.fecha_aporte,
                    })
                    .then(function(response){
                       //console.log(response.data.estado);
                       if(response.data.estado==1)
                       {
                            swal({
                                text:'Ya Existen Aportes Registrados en esa Fecha',
                                type:"info"
                            })
                       }
                       
                    })
                                     
                    .catch(function (error) {
                        console.log(error);
                    })
                    
            },
            obtenerfecha(){
                let me = this;
                me.anio=me.fechaactual.getFullYear();//año
                me.mes=me.fechaactual.getMonth()+1;//mes
                var dia = me.fechaactual.getDate(); //obteniendo dia
                if(me.mes==0)
                {   
                    me.mes=11;
                    me.anio=me.anio-1;
                }
                else    
                    me.mes=me.mes-1;

                me.fechaanterior = new Date(me.anio+'-'+me.mes+'-01');
                var ultimoDia = new Date(me.fechaanterior.getFullYear(),me.fechaanterior.getMonth()+1, 0);
                if(me.mes<10)
                    me.fecha_aporte=me.anio+'-0'+me.mes+'-'+ultimoDia.getDate();
                else
                    me.fecha_aporte=me.anio+'-'+me.mes+'-'+ultimoDia.getDate();

                me.fechanow=me.fecha_aporte;
                //console.log(me.fecha_aporte);
            },

            
            guardarobservados(fecha_ap){
                let me=this;
                //console.log(fecha_ap+' fecha_ap');
                axios.post('/apo_observados',{
                        'fecha_aporte': fecha_ap,
                    })
                    .then(function(response){
                    //console.log(response.data)
                    })

                    .catch(function (error) {
                        console.log(error);
                    })
            },

            selectTipoaporte(){
                let me=this;
                var url= '/apo_tipoaporte/selectTipoaporte';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayTipoaporte = respuesta.tipoaportes;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectPerfilcuentamaestro(){
                let me=this;
                var url= '/con_perfilcuentamaestro/selectPerfilcuentamaestro?idmodulo='+this.idmodulo;
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayPerfilcuentamaestro = respuesta.perfilcuentamaestros;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            reset() {
               /* const input = this.$refs.fileInput
                input.type = 'text'
                input.type = 'file',*/
                this.resetExcelVue=1;
                this.obtenerfecha();
                this.parse_header=[],
                this.parse_csv=[],
                this.nombre_archivo=[],
                this.idtipoaporte=0,
                this.idperfilcuentamaestro=0,
                this.observaciones=''
            
            },
            abrirModal(){
                this.modal=1;

            },
            cerrarModal(){
                this.modal=0;
            },
            cerrarModalbloque(){
                this.modalbloque=0;
            },
            listarCsvdata(){
                let me=this;
                var url= '/csvdata';
                axios.get(url).then(function (response) {
                    
                    var respuesta= response.data;
                    
                    me.arrayCsvdata = respuesta.csvdatas;
                    me.arrayCabeceras=respuesta.cabeceras;
                    
                })
                .catch(function (response) {
                    //console.log(response);
                });
            },

            sortBy:function(key){
                var vm=this
                vm.sortKey=key
                vm.sortOrders[key]=vm.sortOrders[key]*-1
            },
            csvJSON(csv,result){
                
                let me =this;
                var vm=this
                var lines=csv.split("\n")
                vm.parse_header=[];
                
                //var result =[]
                //var headers=lines[0].split(vm.delimitador)
                var headers=this.arrayCabeceras;
                //console.log(headers);
                //console.log(this.arrayCabeceras);
               /* vm.parse_header=lines[0].split(vm.delimitador)
                console.log(vm.parse_header)
                lines[0].split(this.delimitador).forEach(function(key){
                    vm.sortOrders[key]=1
                
                })*/
                lines.map(function(line,indexLine){
                   // if(indexLine<1) return  ///salta a header

                    var obj={}
                    console.log(vm.delimitador);
                    
                    var currentline=line.split(vm.delimitador)
                    
                    headers.map(function(header,indexHeader){
                        obj[header]=currentline[indexHeader]
                    })
                    result.push(obj)
                })
                result.pop()
                //console.log(result);
                return result
            },
            LoadCSV(e){
                
                
                var vm=this
                
                if(window.FileReader){
                    //vm.parse_csv=[];
                    
                   // console.log(e.target.files.length);
                    var result =[]
                    this.nombre_archivo=[];
                    for(this.i=0;this.i<e.target.files.length;this.i++)
                    {
                        var reader=new FileReader();
                        this.nombre_archivo[this.i]=e.target.files[this.i].name;
                        
                        reader.readAsText(e.target.files[this.i]);
                        var csv=[];
                        reader.onload=function(event){
                            csv=event.target.result;
                            vm.parse_csv=vm.csvJSON(csv,result)
                            //console.log(vm.parse_csv.length);
                        }; 
                        reader.onerror=function(evt){
                            if(evt.target.error.name=="NotReadableError")
                            {
                                alert("no se puede leer el archivo");
                            }
                        }; 
                    }
                    //console.log(this.nombre_archivo);
                    csv=[];
                    return result  
                    
                }else{
                    alert('FileReader no es soportado por este navegador');
                }
            },
            subirCSV(){
                var idasientomaestro;
                let me = this;
 
                if(me.parse_csv.length)
                { this.resetExcelVue=0;
                   
                   // this.abrirModal();
                   swal({
                              title: "Registrando los datos",
                              allowOutsideClick:  false,
                              allowEscapeKey: false,
                              onOpen: function () {   swal.showLoading();  }
                          }).catch(function(error){
                              swal.showValidationError('Request failed: ${error}')
                          });
                    axios.post('/import_process',{
                        //'csv_header': this.parse_header,
                        // 'csv_data': this.parse_csv,
                        'csv_data': JSON.stringify(this.parse_csv),
                        'nombre_archivo':this.nombre_archivo,
                        'fecha_archivo':this.fecha_aporte,
                        'idtipoaporte':this.idtipoaporte,
                        'idperfilcuentamaestro':this.idperfilcuentamaestro,
                        'observaciones':this.observaciones,
                        'idmodulo':this.idmodulo
                        
                    })
                    .then(function(response){
                       console.log(response.data);
                        idasientomaestro=response.data;
                     if(response.data=='Correcto!')
                       {
                            swal(
                                'Datos Agregados Correctamente'
                            )
                            me.cerrarModal();
                            me.guardarobservados(me.fecha_aporte);
                            me.reset();
                            me.listarCsvdata();
                            //me.actualizarasiento(idasientomaestro);
                        } 
                       else
                       {
                           swal("Algo salio Mal Revise los Datos",response.data,"warning" );
                           me.cerrarModal();
                           me.listarCsvdata()
                           me.reset();
                       }
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                }
                else
                {
                    swal({
                        text: "Debe Seleccionar un Archivo.", 
                        type: "warning",                                                  
                    })                                               
                }
            },
        },
        mounted() {
            this.listarCsvdata();
            this.selectTipoaporte();
            this.selectPerfilcuentamaestro();
            this.obtenerfecha();
            
        }
    }
</script>
<style>
.modal-content{
        width: 100% !important;
        position: absolute !important;
       /* background-color: #2C3E50 !important;*/
        
        
    }
.mostrar{
    display: list-item !important;
     
    opacity: 1 !important;
    position: absolute !important;
    background-color: #3c29297a !important;
}
.panel{
    border: 2px solid #dfdfdf;
    box-shadow: rgba(0,0,0,0.15)0 1px 0 0;
    margin: 10px;

}
.pnale.panel-sw{
    max-width: 700px;
    margin:10px auto;
}
.panel-heading{
    border-bottom:2px solid #dfdfdf;
}
.panel-heading h1, .panel-heading h2, .panel-heading h3, .panel-heading h4, .panel-heading h5, .panel-heading h6{
    margin: 0;
    padding: 0;
}
.panel-body .checkbox-inline{
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}
 
.text-error{
        color: #c93f23 !important;
        font-size: 12px;
        font-style: italic;
        text-align: right;
    }
/*.sk-rotating-plane {
  width: 4em !default;
  height: 4em !default;
  margin: auto;
  background-color: #337ab7 !default;
  animation: sk-rotating-plane 1.2s infinite ease-in-out;
}
*/
</style>    
