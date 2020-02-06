<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Rrh_Permiso;

class RrhPermisoController extends Controller
{
    public function listaPermisos(Request $request)
    {   $horasalida=DB::raw('substring(horasalida,1,5) as horasalida');
        $permisos=Rrh_Permiso::select('rrh__permisos.*',$horasalida,'nommotivo',)
        ->join('rrh__motivos','rrh__motivos.idmotivo','rrh__permisos.idmotivo')
        ->where('idempleado',$request->idempleado)->get();
        $sql="select sum(cantidad) as acumhoras from rrh__permisos where cantidad<=4 and idempleado=$request->idempleado";
        $acumhoras=DB::select($sql)[0]->acumhoras;
        $sql="select floor(sum(cantidad)/8) as acumdias from rrh__permisos where cantidad>4 and idempleado=$request->idempleado";
        $acumdias =DB::select($sql)[0]->acumdias;
        return ['permisos'=>$permisos,'acumdias'=>$acumdias,'acumhoras'=>$acumhoras,'ipbirt'=>$_SERVER['SERVER_ADDR']];
    }

    public function storePermiso(Request $request)
    {
        $permiso=new Rrh_Permiso();
        $permiso->idempleado=$request->idempleado;
        $permiso->idmotivo=$request->idmotivo;
        $permiso->gocehaberes=$request->gocehaberes;
        $permiso->cargovacacion=$request->cargovacacion;
        $permiso->fechasolicitud=$request->fechasolicitud;
        //$permiso->lapso=$request->lapso;
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
        //$permiso->lapso=$request->lapso;
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

}
