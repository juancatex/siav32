<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rrh_Cargo;

class RrhCargoController extends Controller
{
    public function listaCargos(Request $request)
    {
        $cargos=Rrh_Cargo::orderBy('nomcargo');
        if($request->activo) $cargos=$cargos->where('activo',1);
        return ['cargos'=>$cargos->get()];
    }

    public function storeCargo(Request $request)
    {
        $cargo=new Rrh_Cargo();
        $cargo->nomcargo=$request->nomcargo;
        $cargo->idoficina=$request->idoficina;
        $cargo->save();
    }

    public function updateCargo(Request $request)
    {
        $cargo=Rrh_Cargo::findOrFail($request->idcargo);
        $cargo->nomcargo=$request->nomcargo;
        $cargo->idoficina=$request->idoficina;
        $cargo->save();
    }

    public function switchCargo(Request $request)
    {
        $cargo=Rrh_Cargo::findOrFail($request->idcargo);
        $cargo->activo=abs($cargo->activo-1);
        $cargo->save();        
    }
}
