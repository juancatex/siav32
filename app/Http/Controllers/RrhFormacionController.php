<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rrh_Formacion;

class RrhFormacionController extends Controller
{
    public function listaFormaciones(Request $request)
    {   
        $formaciones=Rrh_Formacion::select('*');
        if($request->activo) $formaciones=$formaciones->where('activo',1);
        return ['formaciones'=>$formaciones->get()];
    }

    public function storeFormacion(Request $request)
    {
        $formacion=new Rrh_Formacion();
        $formacion->nomformacion=$request->nomformacion;
        $formacion->save();
    }

    public function updateFormacion(Request $request)
    {
        $formacion=Rrh_Formacion::findOrFail($request->idformacion);
        $formacion->nomformacion=$request->nomformacion;
        $formacion->save();
    }

    public function switchFormacion(Request $request)
    {
        $formacion=Rrh_Formacion::findOrFail($request->idformacion);
        $formacion->activo=abs($formacion->activo-1);
        $formacion->save();
    }
}
