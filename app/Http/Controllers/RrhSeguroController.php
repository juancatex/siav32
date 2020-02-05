<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rrh_Seguro;

class RrhSeguroController extends Controller
{
    public function listaSeguros(Request $request)
    {
        $seguros=Rrh_Seguro::select('*');
        if($request->activo) $seguros=$seguros->where('activo',1);
        return ['seguros'=>$seguros->get()];
    }

    public function storeSeguro(Request $request)
    {
        $seguro=new Rrh_Seguro();
        $seguro->tipo=$request->tipo;
        $seguro->sigla=$request->sigla;
        $seguro->nomseguro=$request->nomseguro;
        $seguro->save();
    }

    public function updateSeguro(Request $request)
    {
        $seguro=Rrh_Seguro::findOrFail($request->idseguro);
        $seguro->tipo=$request->tipo;
        $seguro->sigla=$request->sigla;
        $seguro->nomseguro=$request->nomseguro;
        $seguro->save();
    }

    public function switchSeguro(Request $request)
    {
        $seguro=Rrh_Seguro::findOrFail($request->idseguro);
        $seguro->activo=abs($seguro->activo-1);
        $seguro->save();
    }
}