<?php

namespace App\Http\Controllers;

use App\Par_Modulo;
use Illuminate\Http\Request;

class ParModuloController extends Controller
{
    public function listaModulos(Request $request)
    {
        $modulos=Par_Modulo::where('activo',1)->orderBy('nommodulo')->get(); 
        return ['modulos' => $modulos];
    }

    public function storeModulo(Request $request)
    {
        $modulo=new Par_Modulo();
        $modulo->nommodulo=$request->nommodulo;
        $modulo->save();
    }

    public function updateModulo(Request $request)
    {
        $modulo=Par_Modulo::findOrFail($request->idmodulo);
        $modulo->nommodulo=$request->nommodulo;
        $modulo->save();
    }

    public function switchModulo(Request $request)
    {
        $modulo=Par_Modulo::findOrFail($request->idmodulo);
        $modulo->activo=abs($modulo->activo-1);
        $modulo->save();
    }

    public function selectModulo(Request $request){
        $modulos=Par_Modulo::where('activo',1)->orderBy('nommodulo')->get(); 
        return ['modulos' => $modulos];
    }
    public function selectModulocontable(Request $request){
        $modulos=Par_Modulo::where('activo',1)->where('contabilizable',1)->orderBy('nommodulo')->get(); 
        return ['modulos' => $modulos];
    }
}
