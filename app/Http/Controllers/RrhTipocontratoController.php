<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rrh_Tipocontrato;

class RrhTipocontratoController extends Controller
{
    public function listaTipocontratos(Request $request)
    {
        $tipocontratos=Rrh_Tipocontrato::select('*');
        if($request->activo) $tipocontratos=$tipocontratos->where('activo',1);
        return ['tipocontratos'=>$tipocontratos->get()];
    }

    public function storeTipocontrato(Request $request)
    {
        $tipocontrato=new Rrh_Tipocontrato();
        $tipocontrato->nomtipocontrato=$request->nomtipocontrato;
        $tipocontrato->save();
    }

    public function updateTipocontrato(Request $request)
    {
        $tipocontrato=Rrh_Tipocontrato::findOrFail($request->idtipocontrato);
        $tipocontrato->nomtipocontrato=$request->nomtipocontrato;
        $tipocontrato->save();
    }

    public function switchTipocontrato(Request $request)
    {
        $tipocontrato=Rrh_Tipocontrato::findOrFail($request->idtipocontrato);
        $tipocontrato->activo=abs($tipocontrato->activo-1);
        $tipocontrato->save();
    }
}