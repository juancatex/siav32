<?php

namespace App\Http\Controllers;
use App\Rrh_Presentado;

use Illuminate\Http\Request;

class RrhPresentadoController extends Controller
{
    public function listaPresentados(Request $request)
    {
        $presentados=Rrh_Presentado::select('rrh__presentados.*','nomdocumento')
        ->join('par__documentos','par__documentos.iddocumento','=','rrh__presentados.iddocumento')
        ->where('idempleado',$request->idempleado);
        return ['presentados'=>$presentados->get()];
    }

    public function storePresentado(Request $request)
    {
        $presentado=new Rrh_Presentado();
        $presentado->idempleado=$request->idempleado;
        $presentado->iddocumento=$request->iddocumento;
        $presentado->fechapres=$request->fechapres;
        $presentado->idformato=$request->idformato;
        $presentado->obs=$request->obs;
        $presentado->save();
    }

    public function updatePresentado(Request $request)
    {
        $presentado=Rrh_Presentado::findOrFail($request->idpresentado);
        $presentado->idempleado=$request->idempleado;
        $presentado->iddocumento=$request->iddocumento;
        $presentado->fechapres=$request->fechapres;
        $presentado->idformato=$request->idformato;
        $presentado->obs=$request->obs;
        $presentado->save();
    }

    public function switchPresentado(Request $request)
    {
        $presentado=Rrh_Presentado::findOrFail($request->idpresentado);
        $presentado->activo=abs($presentado->activo-1);
        $presentado->save();
    }
}
