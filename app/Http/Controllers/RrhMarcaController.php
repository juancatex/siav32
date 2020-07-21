<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Rrh_Marca;


class RrhMarcaController extends Controller
{
    public function listaMarcas(Request $request)
    {   
        $ip=config('app.ip'); 
        if($request->periodo)
        {   
            $ip=config('app.ip'); 
            $mins=DB::raw('sum(atraso) as minutos');
            $atraso=Rrh_Marca::select($mins)
            ->where('codbiom',$request->codbiom)->where('fecha','like',$request->periodo.'%');
            return ['atraso'=>$atraso->get(),'ipbirt'=>$ip];
        }
        $horas=DB::raw('group_concat(substring(hora,1,5)) as horas');
        $marcas=Rrh_Marca::select('fecha',$horas)
        ->where('codbiom',$request->codbiom)
        ->where('fecha','like',$request->periodo.'%')
        ->groupBy('fecha')->orderBy('fecha')->orderBy('hora')->get();
        return ['marcas'=>$marcas,'ipbirt'=>$ip];
    }
}
