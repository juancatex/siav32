<template>
    <main class="main">
         
        <div class="container-fluid">

            <div class="card animated fadeIn">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Estado de Registros de prestamos en relacion sistema  SAFCON - SIA
                    
                </div>
                <div class="card-body">
                    
 
                    <div class=" row " style="justify-content: center; margin-bottom: 15px;">
                        <div class="col-md-8">
                            <div class="input-group"> <input class="form-control" id="fechapapeleta"  type="text"
                                    placeholder="Fecha de registro" autocomplete="off"></div>
                        </div>
                    </div>


                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th class="thcell">No</th>
                                <th class="thcell">Usuario</th>
                                <th class="thcell">Id Prestamo</th>
                                <th class="thcell">Numero de Papeleta</th>
                                <th class="thcell">Monto</th> 
                                <th class="thcell">Plazo</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(prestamos,index) in arrayvalue" :key="index"  valign="middle">
                                 
                                <td class="tdcell" style="text-align:center;vertical-align: middle;" v-text="index+1" >  </td>
                                <td class="tdcell" style="text-align:center;vertical-align: middle;" v-text="prestamos.usuario_reg" >  </td>
                                <td class="tdcell" style="text-align:center;vertical-align: middle;" v-text="prestamos.id_prestamo" >  </td>
                                <td class="tdcell" style="text-align:center;vertical-align: middle;" v-text="prestamos.id_persona" >  </td>
                                <td class="tdcell" style="text-align:center;vertical-align: middle;" v-text="prestamos.imp_desembolsado" >  </td>
                                <td class="tdcell" style="text-align:center;vertical-align: middle;" v-text="prestamos.plazo" >  </td>
                                  
                            </tr>
                        </tbody>
                    </table>
                    
                </div>
            </div>

        </div>

 

    </main>
</template>

<script>
  

    export default {
        props: ['idmodulo','object','idventanamodulo'],
        data (){
            return {
                  arrayvalue:[],
                  fecha:"",
                  fecha_actual:""
            }
        },  
        watch:{
            fecha:function(){ 
                    if(this.fecha){
                        this.viewdata();
                    }
                }
        },
        methods : {
            viewdata(){
                 let me=this;
                 axios.get('/get_status_reg?fecha='+this.fecha).then(function (response) {
                         var respuesta = response.data; 
                            me.arrayvalue=respuesta.data;
                     })
                     .catch(function (response) {
                         console.log(response);
                     });
            },  
             getfecha() {
                 let me = this; 
                 var url = "/getdatacalculo";
                 axios
                     .get(url)
                     .then(function (response) {
                         var respuesta = response.data;
                         me.fecha_actual = respuesta[0].fecha; 
                               $("#fechapapeleta" ).daterangepicker({
                                            singleDatePicker: true, 
                                            autoUpdateInput: true,
                                            autoApply: true,
                                            showDropdowns: true,
                                            maxDate: me.fecha_actual,
                                            minDate: "1200-01-01",
                                            startDate: me.fecha_actual,
                                            locale: {
                                            separator: "   |   ",
                                            format: "YYYY-MM-DD",
                                            applyLabel: "Seleccionar",
                                            cancelLabel: "cancelar",
                                            fromLabel: "Desde",
                                            toLabel: "Hasta",
                                            customRangeLabel: "Seleccionar rango",
                                            daysOfWeek: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                                            monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                                                "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre",
                                                "Diciembre"
                                            ],
                                            firstDay: 1
                                            } 

                                        }, function(start, end, label) { 
                                        me.fecha=start.format('YYYY-MM-DD') ;
                                    });







                     })
                     .catch(function (error) {
                         console.log(error);
                     });
             }
        },
        mounted() {
            this.fecha='';
           this.getfecha();
        }
    }
</script>
<style> 
 
 .thcell{
    text-align: center ; 
    vertical-align: middle ; 
 }
 .tdcell{ 
    vertical-align: middle; 
 }
  
      

::-webkit-scrollbar {
  width: 10px !important;
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
