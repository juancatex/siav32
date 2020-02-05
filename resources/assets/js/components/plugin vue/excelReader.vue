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
  var XLSX=require("./func_10262");export default{props:["col_codigo","remove"],data(){return{columnas:[],idcol:this.col_codigo,functionin:this.remove,arrayOut:[],arrayOutNames:[],lineas:""}},methods:{handleFiles(e){var a=e.target.files;$("#filesName").text(""),this.arrayOut=[],this.arrayOutNames=[],this.lineas="";let t=this;a.length>0?_.findIndex(a,function(e){return!/^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/.test(e.name.toLowerCase())})<0?(swal({title:"Procesando datos de los archivos",allowOutsideClick:!1,allowEscapeKey:!1,onOpen:function(){swal.showLoading()}}).catch(function(e){swal.showValidationError("Request failed: ${error}")}),setTimeout(function(){t.readFile(a,a.length)},500)):(swal("¡Algunos de los archivos seleccionados no son permitidos!","Solo se permiten archivos en formato xls y xlsx","error"),this.$emit("array_Files_Data",null)):(swal("¡No se seleccionaron archivos !","","warning"),this.$emit("array_Files_Data",null))},readFile(e,a,t=0){let s=this;var r=e[t],i=new FileReader;i.readAsBinaryString(r),i.onload=(i=>{s.ProcessExcel(i.target.result).then(i=>{if(void 0!==this.functionin&&_.remove(i,this.functionin),$("#filesName").text(($("#filesName").text().length>0?$("#filesName").text()+",   ":"")+'"'+r.name+'"'),this.idcol){var n=[];for(var o in i){var l=_.reduce(i[o],(e,a,t)=>{var s=_.findKey(this.columnas,function(e){return e.file===t});return s>=0&&(e[this.columnas[s].db]=a),e},{});n.push(l)}s.arrayOutNames.push(r.name),s.arrayOut=_.concat(n,s.arrayOut);var c=t+1;c<a?s.readFile(e,a,c):(swal("¡Archivos procesados correctamente!","","success"),this.lineas="Total lineas obtenidas : "+s.arrayOut.length,this.$emit("array_Files_Data",{names:s.arrayOutNames,values:s.arrayOut}))}else{s.arrayOut=_.concat(i,s.arrayOut);c=t+1;c<a?s.readFile(e,a,c):(swal("¡Archivos procesados correctamente!","","success"),this.lineas="Total lineas obtenidas : "+s.arrayOut.length,this.$emit("array_Files_Data",{names:s.arrayOutNames,values:s.arrayOut}))}})})},async ProcessExcel(e){var a=XLSX.read(e,{type:"binary"});return await XLSX.utils.sheet_to_row_object_array(a.Sheets[a.SheetNames[0]])},ini(e){let a=this;this.idcol?axios.get("/getColumnsFileInput?col="+this.idcol).then(function(t){a.columnas=JSON.parse(t.data.columns),a.handleFiles(e)}).catch(function(e){console.log(e),swal("¡Error al momento de consultar con la DB!","","error")}):a.handleFiles(e)}},mounted(){var e=document.getElementById("inputField");e.addEventListener("change",this.ini,!1)}};
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