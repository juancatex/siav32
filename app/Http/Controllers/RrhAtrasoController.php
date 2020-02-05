<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rrh_Atraso;

class RrhAtrasoController extends Controller
{
    public function verAtraso(Request $request)
    {
        $atraso=Rrh_Atraso::where('idempleado',$request->idempleado)
        ->where('periodo',$request->periodo)->get();
        return ['atraso'=>$atraso];
    }

    public function storeAtraso(Request $request)
    {
        $atraso=new Rrh_Atraso();
        $atraso->idempleado=$request->idempleado;
        $atraso->periodo=$request->periodo;
        $atraso->minutos=$request->minutos;
        $atraso->save();
    }

    public function updateAtraso(Request $request)
    {
        $atraso=Rrh_Atraso::findOrFail($request->idatraso);
        //$atraso->idempleado=$request->idempleado;
        //$atraso->periodo=$request->periodo;
        $atraso->minutos=$request->minutos;
        $atraso->save();
    }
}
