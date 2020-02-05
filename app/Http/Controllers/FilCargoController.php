<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Fil_Cargo;

class FilCargoController extends Controller
{
    public function listaCargos(Request $request)
    {
        $cargos=Fil_Cargo::select('*');
        if($request->activo) $cargos->where('fil__cargos.activo',1);
        return ['cargos'=>$cargos->get()];
    }

    public function storeCargo(Request $request)
    {
        $validator = Validator::make($request->all(), ['nomcargo' => 'unique:fil__cargos']);
        if ($validator->fails()) return ($validator->messages()->first());
        $cargo=new Fil_Cargo();
        $cargo->nomcargo=$request->nomcargo;
        $cargo->save();
    }

    public function updateCargo(Request $request)
    {
        $cargo=Fil_Cargo::FindOrFail($request->idcargo);
        $cargo->nomcargo=$request->nomcargo;
        $cargo->save();
    }

    public function switchCargo(Request $request)
    {
        $cargo=Fil_Cargo::findOrFail($request->idcargo);
        $cargo->activo=abs($cargo->activo-1);
        $cargo->save();
    }
}
