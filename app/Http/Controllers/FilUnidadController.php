<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fil_Unidad;

class FilUnidadController extends Controller
{
    public function listaUnidades(Request $request)
    {   
        $unidades=Fil_Unidad::select('*');
        if($request->activo) $unidades->where('activo',1);
        return ['unidades'=>$unidades->get()];
    }

    public function storeUnidad(Request $request)
    {
        $unidad=new Fil_Unidad();
        $unidad->codunidad=$request->codunidad;
        $unidad->nomunidad=$request->nomunidad;
        $unidad->nomcargo=$request->nomcargo;
        $unidad->abrev=$request->abrev;
        $unidad->save();
    }

    public function updateUnidad(Request $request)
    {
        $unidad=Fil_Unidad::FindOrFail($request->idunidad);
        $unidad->nomunidad=$request->nomunidad;
        $unidad->nomcargo=$request->nomcargo;
        $unidad->abrev=$request->abrev;
        $unidad->save();
    }

    public function switchUnidad(Request $request)
    {
        $unidad=Fil_Unidad::findOrFail($request->idunidad);
        $unidad->activo=abs($unidad->activo-1);
        $unidad->save();
    }    
}
