<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Rrh_Marca;


class RrhMarcaController extends Controller
{
    public function listaMarcas(Request $request)
    {   
        if($request->periodo)
        {   
            $mins=DB::raw('sum(atraso) as minutos');
            $atraso=Rrh_Marca::select($mins)
            ->where('codbiom',$request->codbiom)->where('fecha','like',$request->periodo.'%');
            return ['atraso'=>$atraso->get(),'ipbirt'=>$_SERVER['SERVER_ADDR']];
        }
        $horas=DB::raw('group_concat(substring(hora,1,5)) as horas');
        $marcas=Rrh_Marca::select('fecha',$horas)
        ->where('codbiom',$request->codbiom)
        ->where('fecha','like',$request->periodo.'%')
        ->groupBy('fecha')->orderBy('fecha')->orderBy('hora')->get();
        return ['marcas'=>$marcas,'ipbirt'=>$_SERVER['SERVER_ADDR']];
    }
}
