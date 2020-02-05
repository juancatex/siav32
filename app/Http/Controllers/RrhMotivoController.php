<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rrh_Motivo;

class RrhMotivoController extends Controller
{
    public function listaMotivos(Request $request)
    {
        $motivos=Rrh_Motivo::select('*');
        if($request->activo) $motivos=$motivos->where('activo',1);
        return ['motivos'=>$motivos->get()];
    }

    public function storeMotivo(Request $request)
    {
        $motivo=new Rrh_Motivo();
        $motivo->nommotivo=$request->nommotivo;
        $motivo->save();
    }

    public function updateMotivo(Request $request)
    {
        $motivo=Rrh_Motivo::findOrFail($request->idmotivo);
        $motivo->nommotivo=$request->nommotivo;
        $motivo->save();        
    }

    public function switchMotivo(Request $request)
    {
        $motivo=Rrh_Motivo::findOrFail($request->idmotivo);
        $motivo->activo=abs($motivo->activo-1);
        $motivo->save();
    }
}
