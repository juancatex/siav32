<template>
            <main > 
                <div class="modal fade " tabindex="-1"  role="dialog"  style="z-index: 1600;" aria-hidden="false" id="modal_cobranza" >
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content animated fadeIn"> 
                                <div class="modal-header"> 
                                    <h4 class="modal-title" v-text="tituloModalCuentasocio"></h4>
                                    <button type="button" class="close" aria-hidden="true" aria-label="Close" @click="cerrarmodaleve('modal_cobranza')"><span aria-hidden="true">×</span></button>
                                </div> 

                                <div class="modal-body">
                                    <div class="col-md-12">
                                       <label style="width: 100%; text-align: center;"><h3>Detalle de la cobranza</h3></label> 
                                    </div>



                                        <div class="form-group row">
                                            <label class="col-md-3" ><h6>Monto Total ASCII :</h6></label>   
                                              <label class="col-md-3" >{{totalascii}}</label> 
                                            <label class="col-md-3" ><h6>Monto a Acreedor :</h6></label>   
                                            <label class="col-md-3" >{{subtotal}}</label> 
                                            <label class="col-md-3" ><h6>Monto a cobrar :</h6></label>  
                                            <label class="col-md-3" >{{cobrado}}</label> 
                                            
                                        </div>
                                      
                                  
                                   <div class="form-group row">
                                        <label style="text-align: left; align-items: center;" class="col-md-12 form-control-label"
                                            for="text-input"><h6>Detalle : </h6></label>
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <textarea v-validate.initial="'required'"
                                                    :class="{'form-control':true,'is-invalid': errors.has('detalle')}"
                                                    name="detalle" class="col-md-12" rows="4" v-model="obs"
                                                    placeholder="Detalle de la cargar ascii"></textarea>
                                            </div>
                                            <span class="text-error">{{ errors.first('detalle') }}</span>
                                        </div>
                                    </div> 
                                    <div class="collapse"   id="detallecarga"> 
                                    <hr class="my-4">

                                    <div class="col-md-12">
                                       <label style="width: 100%; text-align: center;"><h3>Detalle del contenido Ascii</h3></label> 
                                        
                                    </div>
                                    <div class="col-md-12 collapse"   id="detallecarga"> 
                                        <table id="listado" class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>No data</th> 
                                                </tr>
                                            </thead>
                                            <tbody>
                                                                                
                                            </tbody>
                                        </table> 
                                        <div v-if="errors.has('irregular')" class="alert alert-danger" v-validate="'required'"
                                                                 data-vv-name="anios" role="alert">{{ errors.first('irregular') }}</div>
                                   </div>   
                                   </div>
                                </div>  

                                <div class="modal-footer">  
                                    <button class="btn btn-success" type="button" data-toggle="collapse" data-target="#detallecarga" 
                                        aria-expanded="false" aria-controls="collapseExample">
                                            Ver contenido Ascii
                                        </button>
                                <button type="button" class="btn btn-secondary" @click="cerrarmodaleve('modal_cobranza')">Cancelar</button>  
                            
                            <button  v-if="!errors.any()" type="submit"   class="btn btn-primary" @click="procesar()">Procesar</button>
                             </div>    
                        </div>
                    </div>
                </div>
  
      
        </main>
</template>

<script> 
     import VueNumeric from 'vue-numeric';
    export default { 
        data (){
            return {
                    tituloModalCuentasocio:"Cobranza por ASCII" ,
                    totalascii:'0',
                    subtotal:'0',
                    cobrado:'0',
                    obs:'',
                     idmodulomain:0,
                    prestamosProducto:null,
                    prestamosMoras:null,
                    prestamosAcreedor:null
            }
        }, 
        computed:{  
            
        },
        methods : { 
            cerrarmodaleve(id){ 
                this.modalcobranza.closeModal(id);
                this.$emit('cerrarvue');
            }, 
            showVue(prestamos,moras,datas,total_ascii,subtotall,cobrados,errors,modulo){
                this.obs='';
                this.idmodulomain=modulo; 
                if(!errors){
                    this.errors.add({ 
                    field: "irregular",
                    msg:
                        "Existe una cobranza irregular, consulte con el administrador del sistema."
                    });
                }
               

                this.prestamosProducto=prestamos;
                this.prestamosMoras=moras;
                this.prestamosAcreedor=datas;
                this.totalascii= _num(parseFloat(total_ascii)).format('0,0[.]00 $');
                this.subtotal= _num(parseFloat(subtotall)).format('0,0[.]00 $');
                this.cobrado=_num(parseFloat(cobrados)).format('0,0[.]00 $');
                 
                this.modalcobranza=new _pl.Modals();
                this.modalcobranza.addModal('modal_cobranza'); 
                this.modalcobranza.openModal('modal_cobranza'); 
                var dobytable='';
                _.forEach(datas, function(value) {    
                    dobytable+='<tr '+(_.has(value, 'plans')?'style="background-color: #0ee23f7d;"':'style="background-color: transparent;"')+'>'
                    +'<td>'+value.numpapeleta+'</td>'+'<td>'+
                    _num(parseFloat(value.totaloriginal)).format('0,0[.]00 $')
                    +'</td>'
                    +'<td>'+(_.has(value, 'plans_total')?_num(parseFloat(value.plans_total)).format('0,0[.]00 $'):0)+'</td>'+'<td>'+
                     _num(parseFloat(value.aporte)).format('0,0[.]00 $')
                    +'</td>'+'</tr>';
                
                }); 
                 this.initdata(dobytable);
            } ,
            procesar(){
               this.prestamosProducto.forEach(function (valor, clave) { 
                   console.log('clave:', clave, 'valor:', valor);
               });

               console.log(JSON.stringify(this.prestamosProducto));
               
               _.forEach(this.prestamosMoras, function (value) {
                   console.log('value vector:', value);
               });

                 console.log(JSON.stringify( this.prestamosMoras) );


        var acreedores = [];
               _.forEach(this.prestamosAcreedor, function(value) {    
                        if(parseFloat(value.aporte)>0){
                             acreedores.push(value);
                    }
                }); 

                      console.log(JSON.stringify( this.prestamosAcreedor) );
 
                    axios.post('/cobranzaascii',{
                        'obs':this.obs,   
                        'prestamos':this.prestamosProducto,
                        'moras':this.prestamosMoras,
                        'acreedores':acreedores,
                        'idmodulo':this.idmodulomain
                    }).then((response)=>{
                   
                        if(response.data.status){
                            console.log('si');
                        }else{
                            console.log(response.data.mensaje);
                        }
                    });



            }
            ,
            initdata(valuein){
                // this.table.destroy();
                    if ($.fn.DataTable.isDataTable( '#listado' ) ) {
                      $('#listado').DataTable().destroy();
                    }
                $("#listado thead tr").html('<th>Numero papeleta</th><th>Ascii Min. Def.</th><th>Monto a cobrar</th><th>Acreedor</th>');
                $("#listado tbody").html(valuein);
                $('#listado').DataTable( { 
                            "dom": '<"pull-left"f><"pull-right"B>tip',
                            "buttons": [ {
                                extend: 'csv',
                                text: '&nbsp;&nbsp;Export to CSV',
                                filename: 'Pre-proceso de cobranza Ascii',
                                className: 'fa fa-file-text fa-3x btn btn-success',
                            } 
                            ], 
                          "order": [[ 2, 'desc' ]],
						  "lengthChange": false,
						  "searching"   : true,
						  "ordering"    : true,
						  "info"        : true,  
						  "autoWidth": false, 
						  "iDisplayLength":	10, 
						  "responsive": true,
						  "language": {
									"emptyTable":			"No hay datos disponibles en la tabla.",
									"info":		   		    "Del _START_ al _END_ de _TOTAL_ ",
									"infoEmpty":			"Mostrando 0 registros de un total de 0.",
									"infoFiltered":			"(filtrados de un total de _MAX_ )",
									"infoPostFix":			"(Registros)",
									"lengthMenu":			"Mostrar _MENU_ registros",
									"loadingRecords":		"Cargando...",
									"processing":			"Procesando...",
									"search":			    "Buscar :",
									"searchPlaceholder":	"Ingrese datos a buscar",
									"zeroRecords":			"No se han encontrado coincidencias.",
									"paginate": {
										"first":			"Primera",
										"last":				"Última",
										"next":				"Siguiente",
										"previous":			"Anterior"
									},
									"aria": {
										"sortAscending":	"Ordenación ascendente",
										"sortDescending":	"Ordenación descendente"
									}
								}, 
						} );
            }
        } 
    }
</script>
<style> 
 
      
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-style: italic;
    }
    .fotosociomini{
	     display: inline-block;
        border:#efefef 1px solid;
        filter:drop-shadow(1px 0px 2px #333);
        width:40px;
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
