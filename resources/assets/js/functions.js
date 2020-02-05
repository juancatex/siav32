
//DAARO
export function detalleAportes(arrayAportes,fecini,fecfin,totalapo) { 
  var start = new Date(fecini);
  var end = new Date(fecfin);
  var anoini = start.getFullYear(new Date(fecini));
  var anofin = end.getFullYear(new Date(fecfin));
  var aporteacumulado=0;
  var totalprestacion=0;
  var interesactuarial=0;
  
  for (var [key, value] of Object.entries(arrayAportes)) {
    var tam = value.length; break;
  }

  for (var i=0; i<tam; i++) {
    for (var [key,value] of Object.entries(arrayAportes)) {   
      switch (key) {
        case 'aporte':
            aporteacumulado = aporteacumulado + value[i];
          break;        
        case 'total':
            totalprestacion = totalprestacion + value[i];
          break;
        case 'intact':
            interesactuarial = interesactuarial + value[i];
          break;
      }      
    }       
  }

  var tablaaportes='<div class="col-md-12"> <table class="table table-bordered"><tr><th>CONCEPTO</th><th>VALORES</th><th>CONCEPTO</th><th>VALORES</th></tr>';    
  tablaaportes+='<tr>'
  +'<td align="center">Anio Inicial de Pago de Aportes</td>'
  +'<td align="center">'+fecini+'</td>'
  +'<td align="center">Anio Final de Pago de Aportes</td>'
  +'<td align="center">'+fecfin+'</td>'
  +'</tr>'
  +'<tr><td align="center">Anios pagados / Antiguedad</td>'
  +'<td align="center">'+(anofin-anoini+1) +'</td>'
  +'<td align="center">Numero de Aporte Realizados</td>'
  +'<td align="center">'+i  +'</td>'
  +'</tr>'
  +'<tr><td align="center">Valor Total Aportado</td>'
  +'<td align="center">'+redondeo_valor(aporteacumulado,2)+'</td>'
  +'<td align="center"><b>Interes Ganado / Rendimiento de Aportes</b></td>'
  +'<td align="center"><b>'+redondeo_valor(interesactuarial,2)+'</b></td>'
  +'</tr>'
  +'<tr><td colspan="2" align="center"><b>LIQUIDACION TOTAL DE PRESTACIONES</b></td>'
  +'<td colspan="2" align="center"><b>'+redondeo_valor(totalprestacion,2)+'</b></td>'
  +'</tr>';     
  tablaaportes+='</table></div>';  

  tablaaportes+='<div class="col-md-12">' 
  +'<table class="table table-bordered">'
  +'<tr><td colspan="7" style="width: 10px" align="center"><b>DETALLE</b></td></tr>'
  +'<tr><th style="width: 10px">Periodo</th>'
  +'<th>Periodo Aporte</th>'
  +'<th>Aporte Mensual</th>'
  +'<th>Aporte Acumulado</th>'
  +'<th>Factor Capitalizacion</th>'
  +'<th>Interes Actuarial</th>'
  +'<th>Interes Acumulado</th>'
  +'<th>Total Prestacion</th></tr>';
      
  var aporteacumulado=0;
  var totalprestacion=0;
  var interesactuarial=0;
  for (var i=0; i<tam; i++) {
    tablaaportes+='<tr>';
    for (var [key,value] of Object.entries(arrayAportes)) {   
      tablaaportes+='<td align="center">'+value[i]+'</td>';
      switch (key) {
        case 'aporte':
            aporteacumulado = aporteacumulado + value[i];
          break;
        case 'total':
            totalprestacion = totalprestacion + value[i];
          break;
        case 'intact':
            interesactuarial = interesactuarial + value[i];
          break;
      }      
    }    
    tablaaportes+='</tr>';
  }

  // totales
  tablaaportes+='<tr><th colspan="2" style="width: 10px">TOTALES</th>'
  +'<td align="right"><b>'+this.redondeo_valor(aporteacumulado,2)+'</b></td>'  
  +'<td align="right"><b>***</b></td>'
  +'<td align="right"><b>'+this.redondeo_valor(interesactuarial,2)+'</b></td>'  
  +'<td align="right"><b>***</b></td>'
  +'<td align="right"><b>'+this.redondeo_valor(totalprestacion,2)+'</b></td></tr>';  
  tablaaportes+='</table></div>';  
  
  tablaaportes+='<div class="col-md-12">' 
  +'<table class="table table-bordered">'
  +'<tr><td><b>Relacion Liquidacion con Total de Aporte Reales Efectuados</b></td>'
  +'<td><b>'+this.redondeo_valor((this.redondeo_valor(totalprestacion,2))/(this.redondeo_valor(aporteacumulado,2)),4)+'</b></td></tr>'
  tablaaportes+='</table></div>';  


return{'plan':tablaaportes,'totalaporte':aporteacumulado,'rendimiento':interesactuarial};

} 

export function redondeo_valor(num, decimales = 2,redoncero=true) {
  var signo = (num >= 0 ? 1 : -1);
  num = num * signo;
  if (decimales === 0) 
      return signo * Math.round(num); 
  num = num.toString().split('e');
  num = Math.round(+(num[0] + 'e' + (num[1] ? (+num[1] + decimales) : decimales))); 
  num = num.toString().split('e');
  if(redoncero){
    return fillDecimals(signo * (num[0] + 'e' + (num[1] ? (+num[1] - decimales) : -decimales)),decimales);
  }else{
    return (signo * (num[0] + 'e' + (num[1] ? (+num[1] - decimales) : -decimales))); 
  }
 
}

export function fillDecimals(number, length=2) {
  function pad(input, length, padding) { 
    var str = input + "";
    return (length <= str.length) ? str : pad(str + padding, length, padding);
  }
  var str        = number+"";
  var dot        = str.lastIndexOf('.');
  var isDecimal  = dot != -1;
  var integer    = isDecimal ? str.substr(0, dot) : str;
  var decimals   = isDecimal ? str.substr(dot+1)  : "";
  decimals       = pad(decimals, length, 0);
  return integer + '.' + decimals;
}

export class Modals{ 
  constructor() { this.modales=new Map();} 
   addModal(id) { 
    $("#"+id).children(".modal-dialog" ).addClass("modal-dialog-centered");
    $("#"+id).attr('data-backdrop', 'static');
    $("#"+id).attr('data-keyboard', 'false');

        this.modales.set(id, { 
        openPrimary: function() { $("#"+id).modal("show"); },
        close: function() { $("#"+id).modal("hide"); },
        onclose:function() { $("#"+id).on('hidden.bs.modal', function () { console.log('se cerro el modal con id='+id); });},
        openInfo: function() {$("#"+id).modal({backdrop: true, keyboard: true});}});  
    }
    
   openModal(id) { 
        if(this.modales.has(id)){ 
          this.closeOthers(id);
          this.modales.get(id).openPrimary(); 
          }
      }

   openModalInfo(id) {
        if(this.modales.has(id)){
          this.closeOthers(id);
          this.modales.get(id).openInfo();  
          }
      }

   closeModal(id) {
      if(this.modales.has(id)){
        this.modales.get(id).close();
        }
    }
    
    closeOthers(id){
      for(var [key, data] of this.modales){
        if(key!=id){
          data.close();
        }
      }
    }
}

export function datapicker(id,fechamin,fechamax,fechaini){
  $("#"+id).daterangepicker({
    singleDatePicker: true,
    forceUpdate: true,
    autoUpdateInput:true,
    autoApply:true,
    showDropdowns: true, 
    maxDate:fechamax,
    minDate: fechamin,
    startDate:fechaini,
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
        "Diciembre"],
      firstDay: 1
    }
     
    });

}
export function datatime(id){
  $("#"+id).datetimepicker({  
     autoclose: 1,
     startView: 3,	
     minView: 3,  
     pickerPosition:'bottom-right',
     format: 'MM - yyyy'
  });
}


export function getdatapicker(id){
  $("#"+id).val(); 
}

export function viewPDF(t, l) {
  return swal({
    html: '<div id="framepdf" style="display:none;"><div style=" width: 100%; "><label style="display: inline;padding-left: 23px;font-weight: 500;float: left;">' 
          + l + '</label> <button id="close_id_swal" class="close" style="float: right; margin: 0 15px 2px 5px; ">x</button> </div><iframe id="printpdf" name="printpdf" src="' 
          + t + '" style="width:100%;height:800px;" allowfullscreen></iframe></div>',
    showConfirmButton: !1,
    showCancelButton: !0,
    allowOutsideClick: !1,
    allowEscapeKey: !1,
    confirmButtonText: "Ok",
    cancelButtonText: "Cerrar",
    confirmButtonColor: "#4dbd74",
    cancelButtonColor: "#f86c6b",
    buttonsStyling: !0,
    reverseButtons: !0,
    onBeforeOpen: function () {
      swal.showLoading(), swal.disableButtons(), 
      $("#close_id_swal").click(function () {
        swal.close()
      }), $("#printpdf").on("load", function () {
        $("#framepdf").css("display", "inline"), swal.hideLoading(), $(".swal2-popup").css("width", "90em"), $(".swal2-popup").css("padding", "0px 0px 20px 0px")
      })
    }
  })
}
 
