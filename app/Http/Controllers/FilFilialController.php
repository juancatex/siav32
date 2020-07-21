<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fil_Filial;

class FilFilialController extends Controller
{
    public function listaFiliales(Request $request)
    {
        $ip=config('app.ip'); 
        $filiales=Fil_Filial::select('fil__filials.*','nomdepartamento','abrvdep','nommunicipio')
        ->join('par_departamentos','fil__filials.iddepartamento','par_departamentos.iddepartamento')
        ->join('par_municipios','fil__filials.idmunicipio','par_municipios.idmunicipio');
        if($request->iddepartamento) $filiales->where('fil__filials.iddepartamento',$request->iddepartamento);
        if($request->orden) $filiales->orderBy('codfilial','desc'); 
        else $filiales->where('fil__filials.activo',$request->activo);
        if($request->idfilial) $filiales->where('idfilial',$request->idfilial);
        return ['filiales'=>$filiales->get(),'ipbirt'=>$ip];
    }

    public function storeFilial(Request $request)
    {
        $filial=new Fil_Filial();
        $filial->iddepartamento=$request->iddepartamento;
        $filial->idmunicipio=$request->idmunicipio;
        $filial->codfilial=$request->codfilial;
        $filial->direccion=$request->direccion;
        $filial->sigla=$request->sigla;
        $filial->telfijo=$request->telfijo;
        $filial->telcelular=$request->telcelular;
        $filial->save();
    }

    public function updateFilial(Request $request)
    {
        $filial=Fil_Filial::findOrFail($request->idfilial);
        $filial->codfilial=$request->codfilial;
        $filial->direccion=$request->direccion;
        $filial->telfijo=$request->telfijo;
        $filial->sigla=$request->sigla;
        $filial->telcelular=$request->telcelular;
        $filial->save();
    }

    public function switchFilial(Request $request)
    {
        $filial=Fil_Filial::findOrFail($request->idfilial);
        $filial->activo=abs($filial->activo-1);
        $filial->save();        
    }

    public function selectFiliales(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $filiales=Fil_Filial::select('fil__filials.idfilial','fil__filials.codfilial','nomdepartamento','nommunicipio')
        ->join('par_departamentos','fil__filials.iddepartamento','=','par_departamentos.iddepartamento')
        ->join('par_municipios','fil__filials.idmunicipio','=','par_municipios.idmunicipio')
        ->orderBy('par_departamentos.iddepartamento','asc')
        ->where('fil__filials.activo',1)->get();
        return ['filiales'=>$filiales];
    }

}
