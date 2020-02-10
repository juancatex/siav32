<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Act_Ambiente;

class ActAmbienteController extends Controller
{
    public function listaAmbientes(Request $request)
    {
        $ambientes=Act_Ambiente::select('*');
        if($request->activo) $ambientes->where('activo',1);
        return ['ambientes'=>$ambientes->get(),'ipbirt'=>$_SERVER['SERVER_ADDR']];
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
