<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Act_Motivo;

class ActMotivoController extends Controller
{
    public function listaMotivos(Request $request)
    {
        $motivos=Act_Motivo::select('*');
        if($request->activo) $motivos=$motivos->where('activo',1);
        return ['motivos'=>$motivos->get()];
    }

    public function storeMotivo(Request $request)
    {
        $motivo=new Act_Motivo();
        $motivo->nommotivo=$request->nommotivo;
        $motivo->save();
    }

    public function updateMotivo(Request $request)
    {
        $motivo=Act_Motivo::findOrFail($request->idmotivo);
        $motivo->nommotivo=$request->nommotivo;
        $motivo->save();        
    }

    public function switchMotivo(Request $request)
    {
        $motivo=Act_Motivo::findOrFail($request->idmotivo);
        $motivo->activo=abs($motivo->activo-1);
        $motivo->save();
    }
}
