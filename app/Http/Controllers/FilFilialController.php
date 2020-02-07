<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Fil_Filial;
use App\Socio;

class FilFilialController extends Controller
{
    public function listaFiliales(Request $request)
    {
        $filiales=Fil_Filial::select('fil__filials.*','nomdepartamento','abrvdep','nommunicipio')
        ->join('par_departamentos','fil__filials.iddepartamento','par_departamentos.iddepartamento')
        ->join('par_municipios','fil__filials.idmunicipio','par_municipios.idmunicipio');
        if($request->iddepartamento) 
            $filiales->where('fil__filials.iddepartamento',$request->iddepartamento);
        $filiales->where('fil__filials.activo',$request->activo);
        if($request->idfilial) $filiales->where('idfilial',$request->idfilial);
        return ['filiales'=>$filiales->get(),'ipbirt'=>$_SERVER['SERVER_ADDR']];
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

    // debo susituir con mi autocomplete
    // debo susituir con mi autocomplete
    // debo susituir con mi autocomplete
    /*
    public function listaSocios(Request $request) // debo susituir con mi autocomplete
    {
        $nomsocio=DB::raw('concat (apaterno," ",amaterno," ",nombre) as nomsocio');
        $socios=Socio::select('idsocio',$nomsocio,'numpapeleta')->orderBy('apaterno')->orderBy('amaterno')->where('idgrado','>',3);
        $arrayBuscar = array();
        $arrayBuscar = explode(" ",trim($request->buscar));
        if(sizeof($arrayBuscar)>0){
            $sql=""; 
            foreach($arrayBuscar as $valor)
            {
                $filtro="(numpapeleta like '%".$valor."%' or nombre like '%".$valor."%' or apaterno like '%".$valor."%' 
                    or amaterno like '%".$valor."%' or ci like '%".$valor."%')";
                if(empty($sql)) $sql=$filtro;
                else $sql.=" and ".$filtro;
            } 
            $socios=$socios->whereraw($sql);
        }
        elseif(!empty($request->id)) $socios->where('idsocio','=',$request->id);
        else $socios->where('idsocio','=',0);
        return ['socios'=>$socios->get()];
    }
    */

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
