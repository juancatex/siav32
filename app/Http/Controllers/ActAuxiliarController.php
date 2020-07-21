<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Act_Auxiliar;

class ActAuxiliarController extends Controller
{
    public function listaAuxiliares(Request $request)
    { 
        $ip=config('app.ip'); 
        $auxiliares=Act_Auxiliar::
        select('idauxiliar','codauxiliar','nomauxiliar','act__auxiliars.idgrupo','act__auxiliars.activo')
        ->join('act__grupos','act__grupos.idgrupo','act__auxiliars.idgrupo')
        ->where('act__grupos.idgrupo',$request->idgrupo);
        if ($request->activo!='')
            $auxiliares->where('act__auxiliars.activo',$request->activo);
        if($request->orden) $auxiliares->orderBy($request->orden);
        return ['auxiliares'=>$auxiliares->get(),'ipbirt'=>$ip];
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
