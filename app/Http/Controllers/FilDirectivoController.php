<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fil_Directivo;

class FilDirectivoController extends Controller
{
    
    public function listaDirectivos(Request $request)
    {
        $directivos=Fil_Directivo::
        select('iddirectivo','fil__directivos.idunidad','nomcargo','nomgrado','nomespecialidad',
        'socios.idsocio','nombre','apaterno','amaterno','nomfuerza','telcelular',
        'fechaini','fechafin','fil__directivos.activo')
        ->join('fil__unidads','fil__unidads.idunidad','fil__directivos.idunidad')
        ->join('socios','socios.idsocio','fil__directivos.idsocio')
        ->join('par_grados','par_grados.idgrado','socios.idgrado')
        ->join('par_fuerzas','par_fuerzas.idfuerza','socios.idfuerza')
        ->leftjoin('par_especialidades','par_especialidades.idespecialidad','socios.idespecialidad')
        ->where('idfilial',$request->idfilial)->where('fil__directivos.activo',$request->activo);;
        $directivos->orderBy('fil__unidads.idunidad');
        return ['directivos'=>$directivos->get()];
    }

    public function storeDirectivo(Request $request)
    {
        $directivo=new Fil_Directivo();
        $directivo->idunidad=$request->idunidad;
        $directivo->idfilial=$request->idfilial;
        $directivo->idsocio=$request->idsocio;
        $directivo->fechaini=$request->fechaini;
        $directivo->fechafin=$request->fechafin;
        $directivo->save();
    }

    public function updateDirectivo(Request $request)
    {
        $directivo=Fil_Directivo::findOrFail($request->iddirectivo);
        $directivo->idunidad=$request->idunidad;
        $directivo->idsocio=$request->idsocio;
        $directivo->fechaini=$request->fechaini;
        $directivo->fechafin=$request->fechafin;
        $directivo->save();
    }

    public function switchDirectivo(Request $request)
    {
        $directivo=Fil_Directivo::findOrFail($request->iddirectivo);
        $directivo->activo=abs($directivo->activo-1);
        $directivo->save();
    }

}
