<?php

namespace App\Http\Controllers;

use App\Par_Ventanamodulo;
use Illuminate\Http\Request;

class ParVentanamoduloController extends Controller
{
    
    public function selectVentanamodulo(Request $request){
        if (!$request->ajax()) return redirect('/');

        if ($request->id) {
            $ventanamodulos = Par_Ventanamodulo::where('par__ventanamodulos.activo','=','1')
            ->join('par__modulos','par__ventanamodulos.idmodulo','par__modulos.idmodulo')
            ->select('par__ventanamodulos.*')
            ->where('idventanamodulo','=',$request->id)
            ->orderBy('par__modulos.idmodulo', 'asc')->get(); 
            return ['ventanamodulos' => $ventanamodulos];
        }
        else if ($request->idmodulo) {//echo 'entro2' . $request->idmodulo;
            $ventanamodulos = Par_Ventanamodulo::where('par__ventanamodulos.activo','=','1')
            ->join('par__modulos','par__ventanamodulos.idmodulo','par__modulos.idmodulo')
            ->select('par__ventanamodulos.*','par__modulos.nommodulo')
            ->where('par__modulos.idmodulo','=',$request->idmodulo)
            ->orderBy('par__modulos.idmodulo', 'asc')->get(); 
            return ['ventanamodulos' => $ventanamodulos];
        }
        else {
            $ventanamodulos = Par_Ventanamodulo::where('par__ventanamodulos.activo','=','1')
            ->join('par__modulos','par__ventanamodulos.idmodulo','par__modulos.idmodulo')
            ->select('par__ventanamodulos.idventanamodulo','par__ventanamodulos.nomventanamodulo','par__modulos.nommodulo')
            ->orderBy('par__modulos.idmodulo', 'asc')->get(); 
            return ['ventanamodulos' => $ventanamodulos];
        }

        
    }
}
