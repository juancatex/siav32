export async function genera(nit,nombre,detalle,monto) { 
      
  //obtener parametros de la factura.
  var arrayFacturaParametro= [];
  var codigocontrol='';
  
  var url = '/con_factura/dataparametro';
  await axios.get(url).then(function (response) {
    var respuesta = response.data;
      arrayFacturaParametro = respuesta.facturaparametro.data; 
  })
  .catch(function (error) {
    console.log(error);
  });

  var url = '/con_factura/maxfactura';
  var numfactura='';
  var numerofactura = '';
  await axios.get(url).then(function (response) {
    numfactura = response.data; //console.log(numfactura);
    numerofactura = numfactura.maxfactura + 1;
    //console.log(numerofactura);
  })
  .catch(function (error) {
    console.log(error);
  });

  //para la fecha
  var hoy = new Date();
  var datehoy = hoy.getFullYear() + '' + hoy.getMonth() + 1 + '' + hoy.getDate();

  //monto a varchar
  monto =  monto + '';

  //generamos el codigo de control
  codigocontrol = _code.genCode(
    arrayFacturaParametro[0].numeroautorizacion, //Numero de autorizacion
    numerofactura, //Numero de factura
    nit, //Número de Identificación Tributaria o Carnet de Identidad
    datehoy, //fecha de transaccion
    monto, //Monto de la factura
    arrayFacturaParametro[0].llave //Llave de dosificación 
  );
    
  //imprmir variables
  console.log('auto:' + arrayFacturaParametro[0].numeroautorizacion);
  console.log('llave:' + arrayFacturaParametro[0].llave);
  console.log('monto:' + monto);
  console.log('numfactura:' + numerofactura);
  console.log('nit:' + nit);
  console.log('nombre:' + nombre);
  console.log('cd:' + codigocontrol);
  
  //preparamod el detalle
  //campo detalle: "nombre_detalle | importe | cantidad | total"
  var detalle_fin = [];
  
  var aux1 = detalle.split(',');

  for (var i=0; i<aux1.length; i++) {    
    var aux2 = aux1[i].split('|');
    detalle_fin.push({
      product_name: aux2[0],
      product_price: aux2[1],
      product_qty: aux2[2],
      line_total: aux2[3],
    });
  }

  //mandar los datos para almacenar en la tabla factura
  axios.post('/con_factura/registrar', {
    'numerofactura': numerofactura,
    'codigocontrol': codigocontrol,
    'razonsocial': nombre,
    'nit': nit,
    'detalle': detalle_fin,
    'importetotal': monto,
    'importecf': monto
  })
  .catch(function (error) {
    console.log(error);
  });

  console.log('dentro: '+numerofactura);
  
  //imprimiendo la factura
  _pdf.openRecibo(numerofactura, codigocontrol, nombre, nit, detalle_fin, monto);
  return {'nrofactura':numerofactura};
  
} 