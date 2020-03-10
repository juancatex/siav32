<?php
if(isset($_GET["fecha"])){
set_time_limit(0); 
 $db_connection = pg_connect("host=192.168.100.24 dbname=SAFCon user=postgres password=pos@2k0015");
 $conn = new mysqli('192.168.100.60', 'root', '','db_sia');  
  
  if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
  if (!$db_connection) { die("Error in connection: " . pg_last_error()); }
 $querypostgresql="SELECT id_prestamo,id_persona,imp_desembolsado, fecha_desembolso,plazo,detalle_desembolso,
id_estado, par_estado_prestamo, par_estado,eliminado, fecha_reg,numero_cuenta_abono
FROM finanzas.ptm_prestamos where fecha_desembolso='".$_GET["fecha"]."' and id_producto ='G'";	
		   
 $response = pg_query($db_connection,$querypostgresql);

  if (!$response) { echo "denied, an error occurred about query.\n";  }
  
    $arr = pg_fetch_all($response)?pg_fetch_all($response):[]; 
	    if(sizeof($arr) > 0){
			   echo '<table   border="0" cellpadding="15">';
			   $pasofinal=true;
					foreach($arr as $rowpsql){  
							$paso=true;
							$sql = "SELECT s.numpapeleta,p.* FROM par__prestamos p,socios s where p.idsocio=s.idsocio and p.fecharegistro like '%".$_GET["fecha"]."%'"; 
							$result = $conn->query($sql);
							if ($result->num_rows > 0) { 
									while($row = $result->fetch_assoc()) { 
									   
									   if(($rowpsql['id_persona']==$row["numpapeleta"])&&($rowpsql['imp_desembolsado']==$row["monto"])&&($rowpsql['plazo']==$row["plazo"])){
										   $paso=false;
											break;											  
									   } 
									} 
							} else {
								echo "0 - sin filas";
							}

							if($paso){  
								$pasofinal=false;
								echo '<tr><td><b>Prestamo : </b>'.$rowpsql['id_prestamo'].'</td><td><b>num. papeleta : </b>'.$rowpsql['id_persona'].'</td><td><b>monto : </b>'.$rowpsql['imp_desembolsado'].'</td><td><b>plazo : </b>'.$rowpsql['plazo'].'</td></tr>';
							} 
					}
					if($pasofinal)echo '<b>Todos los prestamos estan registrados, BIEN HECHO</b>';
			}else{ 
			   echo '<b>No se tiene prestamos registrados a la fecha</b>';
			} 
  $conn -> close(); 
  pg_close($db_connection);
}else{
	echo '<b>Ingrese la fecha para verificar los datos, la fecha de estar en formato :   YYYY-MM-DD </b>';
}
?>