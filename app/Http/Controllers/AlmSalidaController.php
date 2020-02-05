<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Alm_Salida;
use App\Alm_Entrada;

class AlmSalidaController extends Controller
{
    function listaSalidas(Request $request)
    {
        $salidas=Alm_Salida::select('alm__salidas.*','nrlote','nomoficina')
        ->join('fil__oficinas','fil__oficinas.idoficina','=','alm__salidas.idoficina')
        ->join('alm__entradas','alm__entradas.identrada','=','alm__salidas.identrada')
        ->where('alm__salidas.idsuministro','=',$request->idsuministro)->get();
        //->where('identrada','=',$request->identrada)->get();
        return ['salidas'=>$salidas];
    }

    function storeSalida(Request $request)
    {
        $salida=new Alm_Salida();
        $salida->identrada=$request->identrada;
        $salida->idsuministro=$request->idsuministro;
        $salida->cantsalida=$request->cantsalida;
        $salida->fechasalida=$request->fechasalida;
        $salida->idoficina=$request->idoficina;
        $salida->save();
        /*
        $entrada=Alm_Entrada::update(['usado'=>($entrada->usado+$request->cantsalida)])
        ->where('identrada','=',$request->identrada);
        */
        $entrada=Alm_Entrada::findOrFail($request->identrada);
        $entrada->usado=$entrada->usado+$request->cantsalida;
        $entrada->save();
    }

    function updateSalida(Request $request)
    {
        $salida=Alm_Salida::findOrFail($request->idsalida);
        $salida->identrada=$request->identrada;
        $salida->cantsalida=$request->cantsalida;
        $salida->fechasalida=$request->fechasalida;
        $salida->idoficina=$request->idoficina;
        $salida->save();
    }

    function switchSalida(Request $request)
    {
        $salida=Alm_Salida::where('idsalida','=',$request->idsalida)
        ->update(['activo'=>abs($salida->activo-1)]);
    }
}
