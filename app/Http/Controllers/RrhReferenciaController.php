<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rrh_Referencia;

class RrhReferenciaController extends Controller
{
    public function listaReferencias(Request $request)
    {
        $referencias=Rrh_Referencia::where('idempleado',$request->idempleado);
        //if($request->activo) $referencias=$referencias->where('activo',1);
        return ['referencias'=>$referencias->get()];
    }

    public function storeReferencia(Request $request)
    {
        $referencia=new Rrh_Referencia();
        $referencia->idempleado=$request->idempleado;
        $referencia->tiporef=$request->tiporef;
        $referencia->nombre=$request->nombre;
        $referencia->relacion=$request->relacion;        
        $referencia->celular=$request->celular;
        $referencia->telefono=$request->telefono;
        $referencia->direccion=$request->direccion;
        $referencia->cargo=$request->cargo;
        $referencia->save();
    }

    public function updateReferencia(Request $request)
    {
        $referencia=Rrh_Referencia::findOrFail($request->idreferencia);
        $referencia->nombre=$request->nombre;
        $referencia->relacion=$request->relacion;        
        $referencia->celular=$request->celular;
        $referencia->telefono=$request->telefono;
        $referencia->direccion=$request->direccion;
        $referencia->cargo=$request->cargo;
        $referencia->save();
    }

    public function switchReferencia(Request $request)
    {
        $referencia=Rrh_Referencia::findOrFail($request->idreferencia);
        $referencia->activo=abs($referencia->activo-1);
        $referencia->save();
    }

}
