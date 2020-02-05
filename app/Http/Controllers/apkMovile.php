<?php

namespace App\Http\Controllers;

use Auth;
use App\Socio; 
use App\Par_Prestamos;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class apkMovile extends Controller
{
    public function login(Request $request) {
        if (Auth::attempt(['username' => $request->user, 'password' => $request->pass])) { 

            $socios=Socio::join ('par_fuerzas','socios.idfuerza','=','par_fuerzas.idfuerza')
            ->join ('par_grados','socios.idgrado','=','par_grados.idgrado') 
            ->join ('par_departamentos','socios.iddepartamentoexpedido','=','par_departamentos.iddepartamento') 
            ->select('par_departamentos.abrvdep','socios.liquidopagable_papeleta','socios.rutafoto','socios.activo',
            'socios.idsocio','socios.nombre','socios.apaterno','socios.amaterno','socios.ci','socios.numpapeleta','par_fuerzas.nomfuerza','par_grados.nomgrado')
            // ->where('socios.idsocio','=',Auth::id())
             ->where('socios.idsocio','=','4143') 
            //->where('socios.idsocio','=','4143')
            ->orderBy('socios.nombre', 'asc')->get()->toArray(); 
            return response()->json(array('data' =>'ok','id' =>$socios[0]['idsocio'],'num' => $socios[0]['numpapeleta'],
            'ruta'=>$socios[0]['rutafoto']==null?'':$socios[0]['rutafoto'],'gra' => $socios[0]['nomgrado'],'nom' =>  $socios[0]['nombre'],'ape' =>$socios[0]['apaterno'] .' '. $socios[0]['amaterno']), 200);
        } else {
            return response()->json('Usuario no encontrado.', 500);
        }
    }
    public function validate() {
        if (Auth::check()) {
            return response()->json(array('data' =>'ok'), 200);
        } else {
            return response()->json(array('data' =>'error'), 500);
        }
    }
    public function getPrestamos(Request $request){
        $tipo = DB::raw("par__prestamos__tipo__ejecucion.nombre as nombretipo");
        $ascii = DB::raw("(SELECT count(*) FROM par__prestamos__plans pplan where pplan.idprestamo=par__prestamos.idprestamo and pplan.idestado=10) as acsii");
        $socios=Par_Prestamos::select('par__productos.tasa',
        'par__prestamos.detalle_desembolso','par__monedas.codmoneda','par__monedas.nommoneda','par__prestamos.idprestamo',$tipo,$ascii,
        'par__productos.nomproducto','par_grados.nomgrado','socios.nombre','socios.apaterno','socios.amaterno',
        'par__prestamos.monto','par__prestamos.plazo','par__prestamos.fecharegistro','par__prestamos.idestado',
        'par__prestamos__estados.nombreestado','par__prestamos.cuota','par__prestamos.apro_conta')
            ->join ('par__productos','par__prestamos.idproducto','=','par__productos.idproducto')
            ->join ('socios','par__prestamos.idsocio','=','socios.idsocio')
            ->join ('par_grados','socios.idgrado','=','par_grados.idgrado')
            ->join ('par__prestamos__estados','par__prestamos.idestado','=','par__prestamos__estados.idestado')
            ->join ('par__prestamos__tipo__ejecucion','par__prestamos.idejecucion','=','par__prestamos__tipo__ejecucion.idejecucion')
            ->join ('par__monedas','par__productos.moneda','=','par__monedas.idmoneda') 
            ->whereBetween('par__prestamos.idestado',[1,9])
            ->where('par__prestamos.idsocio','=',$request->idsocio)
            ->orderBy('par__prestamos.idestado', 'asc')->get();
        return ['prestamos'=>$socios];
        
    }

    public function verServicios(Request $request)
    {
        $socios ="select nomestablecimiento, substring(codambiente,2,1) as piso, substring(codambiente,4,2) as pieza, 
        concat(fechaentrada,' ',horaentrada) as fechaentrada, CONCAT(fechasalida,' ',horasalida) as fechasalida, concat(alquiler,' Bs.') as precionoche, concepto, 
        periodo, nrdocumento, concat(importe,' Bs.') as importe
        from ser__asignacions 
        join ser__ambientes on ser__ambientes.idambiente=ser__asignacions.idambiente
        join ser__establecimientos on ser__establecimientos.idestablecimiento=ser__ambientes.idestablecimiento
        join ser__pagos on ser__pagos.idasignacion=ser__asignacions.idasignacion
        where idcliente=?"; 
    return ['servicios'=>DB::select($socios, array($request->idsocio))];
    }
    public function verAportes(Request $request)
    {
        $aportes = "select min(a.fechaaporte) as primeraporte,
        max(a.fechaaporte) as ultimoaporte,
        if(c.obligatorios=0,c.cantobligados,c.cantjubilacion) as cantidad,
        if(c.obligatorios=0,c.totalobligados,c.totaljubilacion) as total  
        from apo__aportes a
        join socios b ON
        a.numpapeleta=b.numpapeleta
        join apo__total_aportes c 
        on a.numpapeleta=c.numpapeleta
        where b.idsocio=?";
    return ['aportes'=>DB::select($aportes, array($request->idsocio))];
    }
    public function extractoAportes(Request $request)
    {
        $aportes2 ="SELECT YEAR(apo__aportes.fechaaporte) AS anio,
		MONTH(apo__aportes.fechaaporte) AS mes,
		apo__aportes.aporte AS sumatotal, 
		apo__tipoaportes.descripcion AS tipo
  
                                from `apo__aportes` 
                                inner join `apo__tipoaportes` 
                                on `apo__aportes`.`idtipoaporte` = `apo__tipoaportes`.`idtipoaporte` 
                                JOIN apo__idsaportes a 
                                ON apo__aportes.idaporte=a.idaporte AND apo__aportes.activo=1 AND a.tipodevolucion=0
                                inner join `socios` 
                                on a.`numpapeleta` = `socios`.`numpapeleta` 
                                inner join `con__perfilcuentamaestros` 
                                on `apo__aportes`.`idperfilcuentamaestro` = `con__perfilcuentamaestros`.`idperfilcuentamaestro`  
                               
                                WHERE socios.idsocio=?                    
                                group by `apo__aportes`.`idaporte` 
                                order by `apo__aportes`.`fechaaporte`";
     return ['aportesdetalle'=>DB::select($aportes2, array($request->idsocio))];
    }
    public function getimage(Request $request){
       
    if(empty($request->ruta)){
        return base64_encode(file_get_contents("img/socios/avatar.png"));
    }else{
        return base64_encode(file_get_contents("img/socios/".$request->ruta));
    }
    
    }
    

}
