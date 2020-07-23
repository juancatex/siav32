<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Rrh_Permiso;

class RrhPermisoController extends Controller
{
    public function listaPermisos(Request $request)
    {   
        $ip=config('app.ip'); 
        $horasalida=DB::raw('substring(horasalida,1,5) as horasalida');
        $permisos=Rrh_Permiso::select('rrh__permisos.*',$horasalida,'nommotivo',)
        ->join('rrh__motivos','rrh__motivos.idmotivo','rrh__permisos.idmotivo')
        ->where('idempleado',$request->idempleado)
        ->where('fechasalida','like',$request->periodo.'%')->get();
        
        $sql="select sum(cantidad) as acumhoras from rrh__permisos where cantidad<=4 and idempleado=$request->idempleado";
        $acumhoras=DB::select($sql)[0]->acumhoras;
        $sql="select floor(sum(cantidad)/8) as acumdias from rrh__permisos where cantidad>4 and idempleado=$request->idempleado";
        $acumdias =DB::select($sql)[0]->acumdias;
        return ['permisos'=>$permisos,'acumdias'=>$acumdias,'acumhoras'=>$acumhoras,'ipbirt'=>$ip];
    }

    public function storePermiso(Request $request)
    {
        $permiso=new Rrh_Permiso();
        $permiso->idempleado=$request->idempleado;
        $permiso->idmotivo=$request->idmotivo;
        $permiso->gocehaberes=$request->gocehaberes;
        $permiso->cargovacacion=$request->cargovacacion;
        $permiso->fechasolicitud=$request->fechasolicitud;
        $permiso->cantidad=$request->cantidad;
        $permiso->fechasalida=$request->fechasalida;
        $permiso->horasalida=$request->horasalida;
        $permiso->obs=$request->obs;
        $permiso->save();
    }

    public function updatePermiso(Request $request)
    {
        $permiso=Rrh_Permiso::findOrFail($request->idpermiso);
        $permiso->idmotivo=$request->idmotivo;
        $permiso->gocehaberes=$request->gocehaberes;
        $permiso->cargovacacion=$request->cargovacacion;
        $permiso->fechasolicitud=$request->fechasolicitud;
        $permiso->cantidad=$request->cantidad;
        $permiso->fechasalida=$request->fechasalida;
        $permiso->horasalida=$request->horasalida;
        $permiso->obs=$request->obs;
        $permiso->save();
    }

    public function switchPermiso(Request $request)
    {
        $permiso=Rrh_Permiso::findOrFail($request->idpermiso);
        $permiso->activo=abs($permiso->activo-1);
        $permiso->save();
    }

    public function horasAcumuladasConGoce(Request $request)
    {
        $sql="SELECT TMeses.mes,ifnull(T1.acumulado,0) as total FROM
            (SELECT 1 as IdMes , 'Enero'     as Mes UNION
            SELECT 2 as IdMes , 'Febrero'    as Mes UNION
            SELECT 3 as IdMes , 'Marzo'      as Mes UNION
            SELECT 4 as IdMes , 'Abril'      as Mes UNION
            SELECT 5 as IdMes , 'Mayo'       as Mes UNION
            SELECT 6 as IdMes , 'Junio'      as Mes UNION
            SELECT 7 as IdMes , 'Julio'      as Mes UNION
            SELECT 8 as IdMes , 'Agosto'     as Mes UNION
            SELECT 9 as IdMes , 'Septiembre' as Mes UNION
            SELECT 10 as IdMes, 'Octubre'    as Mes UNION
            SELECT 11 as IdMes, 'Noviembre'  as Mes UNION
            SELECT 12 as IdMes, 'Diciembre'  as Mes) TMeses
            LEFT JOIN
                (SELECT MONTH(fechasalida) as Mes, SUM(cantidad) as acumulado 
                FROM rrh__permisos rp  
                WHERE YEAR(fechasalida)= (select year(now()))     
                and gocehaberes =1 
                and idempleado = $request->idempleado GROUP BY Mes) T1
            ON T1.Mes = TMeses.idMes";
      
        return ['acumuladoConGoce'=>DB::select($sql)];
    }

    public function horasAcumuladasSinGoce(Request $request)
    {
        $sql="SELECT TMeses.mes,ifnull(T1.acumulado,0) as total FROM
            (SELECT 1 as IdMes , 'Enero'     as Mes UNION
            SELECT 2 as IdMes , 'Febrero'    as Mes UNION
            SELECT 3 as IdMes , 'Marzo'      as Mes UNION
            SELECT 4 as IdMes , 'Abril'      as Mes UNION
            SELECT 5 as IdMes , 'Mayo'       as Mes UNION
            SELECT 6 as IdMes , 'Junio'      as Mes UNION
            SELECT 7 as IdMes , 'Julio'      as Mes UNION
            SELECT 8 as IdMes , 'Agosto'     as Mes UNION
            SELECT 9 as IdMes , 'Septiembre' as Mes UNION
            SELECT 10 as IdMes, 'Octubre'    as Mes UNION
            SELECT 11 as IdMes, 'Noviembre'  as Mes UNION
            SELECT 12 as IdMes, 'Diciembre'  as Mes) TMeses
            LEFT JOIN
                (SELECT MONTH(fechasalida) as Mes, SUM(cantidad) as acumulado 
                FROM rrh__permisos rp  
                WHERE YEAR(fechasalida)= (select year(now()))     
                and gocehaberes =0 
                and idempleado = $request->idempleado GROUP BY Mes) T1
            ON T1.Mes = TMeses.idMes";
      
        return ['acumuladoSinGoce'=>DB::select($sql)];
    }

}
