export function openRecibo(nrofactura,codcontrol,nombre,nit,detalle,total,base) {
    var aux = _pdf(base, nrofactura, codcontrol, nombre, nit, detalle, total);
    swal({
      html: '<div id="framepdf" >' +
        '<div style=" width: 100%; "> <button id="close_id_swal" class="close" style="float: right; margin: 0 15px 2px 5px; ">x</button> </div>' +
        '<iframe id="printpdf" name="printpdf" src="' + aux + '" style="width:100%;height:600px;" allowfullscreen></iframe></div>',
      showConfirmButton: false,
      showCancelButton: true,
      confirmButtonText: 'Ok',
      cancelButtonText: 'Cerrar',
      confirmButtonColor: '#4dbd74',
      cancelButtonColor: '#f86c6b',
      buttonsStyling: true,
      reverseButtons: true,
      width: '900px'
    });

        
  // var aux = _pdf(ima,nrofactura, codcontrol, nombre, nit, detalle, total);

  // swal({
  //   html: '<div id="framepdf" >' +
  //     '<div style=" width: 100%; "> <button id="close_id_swal" class="close" style="float: right; margin: 0 15px 2px 5px; ">x</button> </div>' +
  //     '<iframe id="printpdf" name="printpdf" src="' + aux + '" style="width:100%;height:600px;" allowfullscreen></iframe></div>',
  //   showConfirmButton: false,
  //   showCancelButton: true,
  //   confirmButtonText: 'Ok',
  //   cancelButtonText: 'Cerrar',
  //   confirmButtonColor: '#4dbd74',
  //   cancelButtonColor: '#f86c6b',
  //   buttonsStyling: true,
  //   reverseButtons: true,   
  //   width: '900px'
  // });
  
}

//function _pdf(base64, ta, empresa = {}, cod, nombre) {
  function _pdf(base64, nrofactura, codcontrol, nombre, nit, detalle, total1) {
    

  var doc = new jsPDF('p', 'mm', 'letter'); //216mm X 279mm (carta)
  doc.setProperties({
    title: 'FACTURA1'  
  });

  doc.addImage(base64, 'JPEG', 10, 10, 43, 38);

  doc.setFontSize(13);
  doc.setFontStyle('bold');
  doc.setFontStyle('normal');
  doc.setFontSize(8);
  // doc.text("Dir.: "+empresa.dir,55,27);
  // doc.text("Tel.: "+empresa.tel,55,31);
  // doc.text("Cel.: "+empresa.cel,55,35);

  doc.text("Dir.: asdf",55,27);
  doc.text("Tel.: 23424",55,31);
  doc.text("Cel.: 23452",55,35);

  doc.text("Cod: " + codcontrol, 175, 43);

  doc.setFontStyle('bold');
  //doc.text("De: "+empresa.pro,55,39);
  doc.text("De: adsf adsf asdf",55,39);


  doc.setFontSize(20);
  doc.setFontStyle('bold');
  doc.text('No. Factura:  ' + nrofactura, 55, 52);
  doc.setFontStyle('normal');

  doc.setFontStyle('bold');
  doc.setFontSize(10);
  doc.text("No. NIT: ", 15, 60);
  doc.setFontSize(9);
  doc.setFontStyle('normal');
  doc.text(nit, 50, 60);

  doc.setFontStyle('bold');
  doc.setFontSize(10);
  doc.text("Razon Social: ", 15, 65);
  doc.setFontSize(9);
  doc.setFontStyle('normal');
  doc.text(nombre, 50, 65);

  doc.setFontStyle('bold');
  doc.setFontSize(10);
  doc.text("Fecha: ", 105, 60);
  doc.setFontSize(9);
  doc.setFontStyle('normal');
  doc.text(moment().format("YYYY-MM-DD HH:mm:ss"), 140, 60);

  doc.setFontStyle('bold');
  doc.setFontSize(10);
  doc.text("factura: ", 105, 65);
  doc.setFontSize(9);
  doc.setFontStyle('normal');
  doc.text('', 140, 65);

  var total = total1;
  var totalpv = 12341;

  // var qr = new QRious();
  // qr.background = "green";
  // qr.backgroundAlpha = 0.8;
  // qr.foreground = "blue";
  // qr.foregroundAlpha = 0.8;
  // qr.level = "H";
  // qr.padding = 25;
  // qr.size = 500;
  // qr.value = "https://github.com/neocotic/qrious";
  // //qr.toDataURL("image/jpeg");

  // doc.addImage(qr.toDataURL("image/jpeg"), 'JPEG', 175, 14, 25, 25);

    var datos= [];
      
    for (var i = 0; i < detalle.length; i++) {
      //console.log(detalle[i].product_name);           //console.log('contador: '+i);  
      datos.push({
        num: i+1,
        detalle1: detalle[i].product_name,
        importe: detalle[i].product_price,
        cantidad: detalle[i].product_qty,
        parcial: detalle[i].line_total,        
      });      
  }
    
//   var datos = [{
//     num: '1',
//     detalle: 'Detalle 1',
//     importe: '65',
//     cantidad: '1',
//     parcial: '65',
//     total: '66'
//     }, {
//     num: '2',
//     detalle: 'Detalle 2',
//     importe: '622',
//     cantidad: '11',
//     parcial: '111',
//     total: '2226'
//   }];
// console.log(datos[0].importe);

  doc.autoTable({
    
    head: [{
      num: 'No.',
      detalle1: 'Concepto',
      importe: 'Importe',
      cantidad: 'Cantidad',
      parcial: 'Total Parcial',      
    }],
    
    body: datos,

    foot: [{
       num: {
         content: 'TOTAL : ',
         colSpan: 4,
         styles: {
           halign: 'right'
         }
       },
// //      pvt: totalpv + ' pv',
       parcial: total + ' Bs'
    }],

    theme: 'grid',
    styles: {
      lineColor: [211, 211, 211],
      lineWidth: 0.2,
      halign: 'center'
    },
    headStyles: {
      fillColor: [203, 207, 212],
      textColor: [0, 0, 0],
      fontStyle: 'bold',
      cellWidth: 'wrap',
      fontSize: 9
    },
    bodyStyles: {
      fontSize: 8,
      cellWidth: 'wrap',
    },
    footStyles: {
      fillColor: [231, 234, 238],
      textColor: [0, 0, 0],
      cellWidth: 'wrap',
      fontStyle: 'normal'
    },
    showFoot: 'lastPage',
    showHead: 'firstPage',
    startY: 68
  });
  doc.setFontStyle('bold');
  doc.setFontSize(9);
  doc.text('Son: ', 14, doc.previousAutoTable.finalY + 7);
  doc.setFontStyle('normal');
  doc.setFontSize(8);
  doc.text(numero_a_literal(total), 23, doc.previousAutoTable.finalY + 7);

  doc.setDrawColor(0, 0, 0);

  ///doc.line(desde-ancho,desde-alto,hasta-ancho,hasta-alto);
  doc.setFontSize(10);
  doc.line(34, doc.previousAutoTable.finalY + 38, 85, doc.previousAutoTable.finalY + 38);

  doc.text('Entrege Conforme', 45, doc.previousAutoTable.finalY + 43);

  doc.line(130, doc.previousAutoTable.finalY + 38, 181, doc.previousAutoTable.finalY + 38);
  doc.text('Recibi Conforme', 143, doc.previousAutoTable.finalY + 43);

  return doc.output('datauristring');

}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 function Unidades(num){

  switch(num)
  {
      case 1: return "UNO";
      case 2: return "DOS";
      case 3: return "TRES";
      case 4: return "CUATRO";
      case 5: return "CINCO";
      case 6: return "SEIS";
      case 7: return "SIETE";
      case 8: return "OCHO";
      case 9: return "NUEVE";
  }

  return "";
}//Unidades()

function Decenas(num){

  var decena = Math.floor(num/10);
  var unidad = num - (decena * 10);

  switch(decena)
  {
      case 1:
          switch(unidad)
          {
              case 0: return "DIEZ";
              case 1: return "ONCE";
              case 2: return "DOCE";
              case 3: return "TRECE";
              case 4: return "CATORCE";
              case 5: return "QUINCE";
              default: return "DIECI" + Unidades(unidad);
          }
      case 2:
          switch(unidad)
          {
              case 0: return "VEINTE";
              default: return "VEINTI" + Unidades(unidad);
          }
      case 3: return DecenasY("TREINTA", unidad);
      case 4: return DecenasY("CUARENTA", unidad);
      case 5: return DecenasY("CINCUENTA", unidad);
      case 6: return DecenasY("SESENTA", unidad);
      case 7: return DecenasY("SETENTA", unidad);
      case 8: return DecenasY("OCHENTA", unidad);
      case 9: return DecenasY("NOVENTA", unidad);
      case 0: return Unidades(unidad);
  }
}//Unidades()

function DecenasY(strSin, numUnidades) {
  if (numUnidades > 0)
  return strSin + " Y " + Unidades(numUnidades)

  return strSin;
}//DecenasY()

function Centenas(num) {
  var centenas = Math.floor(num / 100);
  var decenas = num - (centenas * 100);

  switch(centenas)
  {
      case 1:
          if (decenas > 0)
              return "CIENTO " + Decenas(decenas);
          return "CIEN";
      case 2: return "DOSCIENTOS " + Decenas(decenas);
      case 3: return "TRESCIENTOS " + Decenas(decenas);
      case 4: return "CUATROCIENTOS " + Decenas(decenas);
      case 5: return "QUINIENTOS " + Decenas(decenas);
      case 6: return "SEISCIENTOS " + Decenas(decenas);
      case 7: return "SETECIENTOS " + Decenas(decenas);
      case 8: return "OCHOCIENTOS " + Decenas(decenas);
      case 9: return "NOVECIENTOS " + Decenas(decenas);
  }

  return Decenas(decenas);
}//Centenas()

function Seccion(num, divisor, strSingular, strPlural) {
  var cientos = Math.floor(num / divisor)
  var resto = num - (cientos * divisor)

  var letras = "";

  if (cientos > 0)
      if (cientos > 1)
          letras = Centenas(cientos) + " " + strPlural;
      else
          letras = strSingular;

  if (resto > 0)
      letras += "";

  return letras;
}//Seccion()

function Miles(num) {
  var divisor = 1000;
  var cientos = Math.floor(num / divisor)
  var resto = num - (cientos * divisor)

  var strMiles = Seccion(num, divisor, "UN MIL", "MIL");
  var strCentenas = Centenas(resto);

  if(strMiles == "")
      return strCentenas;

  return strMiles + " " + strCentenas;
}//Miles()

function Millones(num) {
  var divisor = 1000000;
  var cientos = Math.floor(num / divisor)
  var resto = num - (cientos * divisor)

  var strMillones = Seccion(num, divisor, "UN MILLON DE", "MILLONES DE");
  var strMiles = Miles(resto);

  if(strMillones == "")
      return strMiles;

  return strMillones + " " + strMiles;
}//Millones()

 function numero_a_literal(num) {
  var data = {
      numero: num,
      enteros: Math.floor(num),
      centavos: (((Math.round(num * 100)) - (Math.floor(num) * 100))),
      letrasCentavos: "",
      letrasMonedaPlural: 'Bolivianos', 
      letrasMonedaSingular: 'Boliviano', 

      letrasMonedaCentavoPlural: "Centavos",
      letrasMonedaCentavoSingular: "Centavo"
  };

  if (data.centavos > 0) {
      data.letrasCentavos = data.centavos+'/100';
  };

  if(data.enteros == 0)
      return "CERO " + data.letrasMonedaPlural + " " + data.letrasCentavos;
  if (data.enteros == 1)
      return Millones(data.enteros) + " " + data.letrasCentavos + " " + data.letrasMonedaSingular;
  else
      return Millones(data.enteros) + " " + data.letrasCentavos+ " " + data.letrasMonedaPlural;
} 

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 
function getTotalpv(datas) {
  var out = 0;
  for (var valor in datas) { 
    out += parseFloat(datas[valor].pvt);
  }
  return redondeo_valor(out);
}
function getTotal(datas) {
  var out = 0;
  for (var valor in datas) { 
    out += parseFloat(datas[valor].total);
  }
  return redondeo_valor(out);
}
 function redondeo_valor(num, decimales = 2, redoncero = true) {
  var signo = (num >= 0 ? 1 : -1);
  num = num * signo;
  if (decimales === 0)
    return signo * Math.round(num);
  num = num.toString().split('e');
  num = Math.round(+(num[0] + 'e' + (num[1] ? (+num[1] + decimales) : decimales)));
  num = num.toString().split('e');
  if (redoncero) {
    return fillDecimals(signo * (num[0] + 'e' + (num[1] ? (+num[1] - decimales) : -decimales)), decimales);
  } else {
    return (signo * (num[0] + 'e' + (num[1] ? (+num[1] - decimales) : -decimales)));
  }

}
 function fillDecimals(number, length = 2) {
  function pad(input, length, padding) {
    var str = input + "";
    return (length <= str.length) ? str : pad(str + padding, length, padding);
  }
  var str = number + "";
  var dot = str.lastIndexOf('.');
  var isDecimal = dot != -1;
  var integer = isDecimal ? str.substr(0, dot) : str;
  var decimals = isDecimal ? str.substr(dot + 1) : "";
  decimals = pad(decimals, length, 0);
  return integer + '.' + decimals;
}

function generateBodyTable(datas) {
  let body = [];
  for (var valor of datas.values()) {
    body.push({
        codi: 'Codigo',
        prod: 'Producto',
        pv: 'Pv',
        prec: 'Precio',
        cant: 'Cantidad',
        pend: 'Pend',
        prom: 'Promo',
        pag: 'Pag',
        pvt: 'Pvt',
        total: 'Costo total'
    });
  }
  return body;
}

 export function imgToBase64(src, callback) {
   var outputFormat = src.substr(-3) === 'png' ? 'image/png' : 'image/jpeg';
   var img = new Image();
   img.crossOrigin = 'Anonymous';
   img.onload = function () {
     var canvas = document.createElement('CANVAS');
     var ctx = canvas.getContext('2d');
     var dataURL;
     canvas.height = this.naturalHeight;
     canvas.width = this.naturalWidth;
     ctx.drawImage(this, 0, 0);
     dataURL = canvas.toDataURL(outputFormat);
     callback(dataURL);
   };
   img.src = src;
   if (img.complete || img.complete === undefined) {
     img.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
     img.src = src;
   }
 }