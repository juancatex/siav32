<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rrh_Profesion;

class RrhProfesionController extends Controller
{
    public function listaProfesiones(Request $request)
    {
        $profesiones=Rrh_Profesion::select('*');
        if($request->activo) $profesiones=$profesiones->where('activo',1);
        return ['profesiones'=>$profesiones->get()];
    }

    public function storeProfesion(Request $request)
    {
        $profesion=new Rrh_Profesion();
        $profesion->nomprofesion=$request->nomprofesion;
        $profesion->save();
    }

    public function updateProfesion(Request $request)
    {
        $profesion=Rrh_Profesion::findOrFail($request->idprofesion);
        $profesion->nomprofesion=$request->nomprofesion;
        $profesion->save();
    }

    public function switchProfesion(Request $request)
    {
        $profesion=Rrh_Profesion::findOrFail($request->idprofesion);
        $profesion->activo=abs($profesion->activo-1);
        $profesion->save();
    }
}