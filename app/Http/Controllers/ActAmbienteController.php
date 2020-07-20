<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Act_Ambiente;

class ActAmbienteController extends Controller
{
    public function listaAmbientes(Request $request)
    {
        $ip=config('app.ip'); 
        $ambientes=Act_Ambiente::
        where('idfilial',$request->idfilial)->where('activo',$request->activo);
        if($request->orden) $ambientes->orderBy($request->orden,'asc');
        return ['ambientes'=>$ambientes->get(),'ipbirt'=>$ip];
    }

    public function storeAmbiente(Request $request)
    {   
        $ambiente=new Act_Ambiente();
        $ambiente->idfilial=$request->idfilial;
        $ambiente->codambiente=$request->codambiente;
        $ambiente->nomambiente=$request->nomambiente;
        $ambiente->save();
    }

    public function updateAmbiente(Request $request)
    {
        $ambiente=Act_Ambiente::findOrFail($request->idambiente);
        $ambiente->nomambiente=$request->nomambiente;
        $ambiente->save();
    }

    public function switchAmbiente(Request $request)
    {
        $ambiente=Act_Ambiente::findOrFail($request->idambiente);
        $ambiente->activo=abs($ambiente->activo-1);
        $ambiente->save();
    }    

}
