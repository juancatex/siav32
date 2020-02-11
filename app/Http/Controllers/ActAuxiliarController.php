<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Act_Auxiliar;

class ActAuxiliarController extends Controller
{
    public function listaAuxiliares(Request $request)
    {
        $auxiliares=Act_Auxiliar::
        select('idauxiliar','codauxiliar','nomauxiliar','act__auxiliars.activo','act__auxiliars.idgrupo')
        ->join('act__grupos','act__grupos.idgrupo','act__auxiliars.idgrupo')
        ->where('act__grupos.idgrupo',$request->idgrupo)->get();
        if($request->activo) $auxiliares->where('act__auxiliars.activo',1);
        return ['auxiliares'=>$auxiliares];
    }


    public function storeAuxiliar(Request $request)
    {   
        $auxiliar=new Act_Auxiliar();
        $auxiliar->idgrupo=$request->idgrupo;
        $auxiliar->codauxiliar=$request->codauxiliar;
        $auxiliar->nomauxiliar=$request->nomauxiliar;
        $auxiliar->save();
    }

    public function updateAuxiliar(Request $request)
    {   
        $auxiliar=Act_Auxiliar::findOrFail($request->idauxiliar);
        $auxiliar->nomauxiliar=$request->nomauxiliar;
        $auxiliar->save();
    }

    public function switchAuxiliar(Request $request)
    {
        $auxiliar=Act_Auxiliar::findOrFail($request->idauxiliar);
        $auxiliar->activo=abs($auxiliar->activo-1);
        $auxiliar->save();
    }
    
}
