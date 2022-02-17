<template>
 <div>
    <div class="row col-sm-12" style=" margin: 0px !important;padding: 0px !important;" >
            <label class="input-group-append mb-0 inputFilevue col-md-12 row" >
                <span class="col-sm-2 btn btn-primary input-file-btn botonSelect" style="padding-left: 5px;padding-right: 5px;">
                Seleccione archivo <input type="file" multiple="true" id="inputField" name="csv_file" accept=".xls,.xlsx" hidden>
                </span>  
                    <div id="filesName" class="col-sm-10 labelInput" style=" display: inline-block; vertical-align: middle;margin: 0;font-size: 12px;line-height: 31px;">  
                    </div>
            </label> 
        </div>
        <span class="linelenght">{{lineas}}</span> 
    </div>
</template>
<script>
 var XLSX = require('./func_10262');
 export default {
     props: ["col_codigo", "remove"],
     data() {
         return {
             columnas: [],
             idcol: this.col_codigo,
             functionin: this.remove,
             arrayOut: [],
             arrayOutNames: [],
             lineas: ''
         }
     },
     methods: {
         handleFiles(e) {
             var filesin = e.target.files;
             $('#filesName').text('');
             this.arrayOut = [];
             this.arrayOutNames = [];
             this.lineas = '';
             let me = this;
             if (filesin.length > 0) {
                 if (_.findIndex(filesin, function (o) {
                         return !((/^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/).test(o.name.toLowerCase()));
                     }) < 0) {
                     swal({
                         title: "Procesando datos de los archivos",
                         allowOutsideClick: false,
                         allowEscapeKey: false,
                         onOpen: function () {
                             swal.showLoading();
                         }
                     }).catch(function (error) {
                         swal.showValidationError('Request failed: ${error}')
                     });
                     setTimeout(function () { 

                         me.readFile(filesin,0,[]).then((outvalue)=>{ 
                                     swal("¡Archivos procesados correctamente!", "", "success");
                                            me.lineas = 'Total lineas obtenidas : ' + outvalue.values.length;
                                            me.$emit('array_Files_Data',outvalue);
                         }); 
                     }, 500);
                 } else {
                     swal("¡Algunos de los archivos seleccionados no son permitidos!", "Solo se permiten archivos en formato xls y xlsx", "error");
                     this.$emit('array_Files_Data', null);
                 }
             } else {
                 swal("¡No se seleccionaron archivos !", "", "warning");
                 this.$emit('array_Files_Data', null);
             }
         },
         readFileAsync(file) {
             const reader = new FileReader(); 
             return new Promise((resolve, reject) => {  
                 reader.readAsBinaryString(file);
                 reader.onload = () => {
                     resolve(reader.result);
                 };  
                 reader.onerror = () => {
                    reader.abort();
                    reject(new DOMException("Problem parsing input file."));
                };
             })
         },
        async readFile(files,posi,tuarray) {
            let arraynames=[]; 
            var outfuna = [];
            try {
                for (let file of files) {
                    arraynames.push(file.name); 
                    $('#filesName').text((($('#filesName').text().length > 0) ? $('#filesName').text() + ',   ' : '') + '"' + file.name + '"');
                    let contentBuffer = await this.readFileAsync(file); 
                    let lectura= await this.ProcessExcel(contentBuffer);
                    if (typeof this.functionin !== "undefined") {
                            _.remove(lectura,this.functionin);
                     }  
                    ////////////////////////////////////////////////////////////////////////////////
                    if (this.idcol) { 
                         let arrayNew = [];
                         for (var index in lectura) {  
                            let filesss = new Object();  
                             var jsonvalues =lectura[index];
                            for (var clave in jsonvalues){ 
                                if (jsonvalues.hasOwnProperty(clave)) { 
                                        let columnsin = _.findKey(this.columnas, function (o) {
                                            return o.file === clave;
                                        })
                                        if (columnsin >= 0) {  
                                            filesss[this.columnas[columnsin]['db']] = jsonvalues[clave].toString().trim();
                                        }
                                }
                            } 
                           
                           if(Object.keys(filesss).length != 0){
                                 arrayNew.push(filesss);
                           }
                         }
                       
                        outfuna = outfuna.concat(arrayNew);  
                     } else {
                        outfuna = outfuna.concat(lectura);  
                     }
                    ////////////////////////////////////////////////////////////////////////////////

                }
                return { names: arraynames,values: outfuna};
            } catch (err) { 
                return { names: [],values: []};
            }
 
         }, 
         async ProcessExcel(data) {
             var workbook = XLSX.read(data, {
                 type: 'binary'
             });
             return await XLSX.utils.sheet_to_row_object_array(workbook.Sheets[workbook.SheetNames[0]]);
         },
         ini(e) {
             let me = this;
             if (this.idcol) {
                 axios.get('/getColumnsFileInput?col=' + this.idcol)
                     .then(function (response) {
                         me.columnas = JSON.parse(response.data.columns);
                         me.handleFiles(e);
                     }).catch(function (response) {
                         console.log(response);
                         swal("¡Error al momento de consultar con la DB!", "", "error");
                     });
             } else {
                 me.handleFiles(e);
             }
         }
     },
     mounted() {
         var inputElement = document.getElementById("inputField");
         inputElement.addEventListener("change", this.ini, false);

     }
 }
    /* var aux=[];
  aux.push({'file':'GESTION','db':'gestion'});
  aux.push({'file':'DOCUMENTO_RESPALDO','db':'codaporte'}); 
  aux.push({'file':'EIT_CODORG','db':'codfuerza'});  
  aux.push({'file':'ORGANISMOS','db':'fuerza'});   
  aux.push({'file':'EIT_CODREP','db':'coddestino'});  
  aux.push({'file':'REPARTICION','db':'destino'});      
  aux.push({'file':'IDENTIFICADOR_ACREEDOR','db':'codaportedestino'});   
  aux.push({'file':'ACREEDOR','db':'descaporte'});    
  aux.push({'file':'CTA_BANCARIA_ACREEDOR','db':'cuentaasscinals'});     
  aux.push({'file':'CODIGO_PERSONAL','db':'numpapeleta'});    
  aux.push({'file':'CARNET','db':'cisocio'});     
  aux.push({'file':'GRADO','db':'grado'});   
  aux.push({'file':'MENSION','db':'especialidad'});   
  aux.push({'file':'NOMBRES','db':'nombres'});     
  aux.push({'file':'MONTO_DESCUENTO','db':'aporte'});       
  aux.push({'file':'TOT_2','db':'aporte2'});        
  aux.push({'file':'COMISION','db':'comision'});       
 console.log(JSON.stringify(aux));*/
</script>
<style> 
.labelInput:empty:not(:focus):before{
    content:'Seleccione los archivos';
        color: #808080a8;
}

 .inputFilevue { 
    border: solid 1px #c2cfd6;
    border-radius: 5px; 
    padding: 0px !important;
    margin: 0px !important;
 }
 .botonSelect{
    border-radius: 5px;
    border-bottom-right-radius: 0;
    border-top-right-radius: 0;
 }
 .linelenght{
    color: #29363d !important;
    font-style: italic;
    float: right;
    padding-right: 11px;
 }
     
</style>