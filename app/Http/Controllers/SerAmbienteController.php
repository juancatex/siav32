<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Ser_Ambiente;

class SerAmbienteController extends Controller
{
    public function listaAmbientes(Request $request)
    {
        $ip=config('app.ip'); 
        $ambientes=Ser_Ambiente::select('*','capacidad as porhora')//canchas
        ->where('idestablecimiento',$request->idestablecimiento);
        if($request->bloque) $ambientes->where('codambiente','like',$request->bloque.'%');
        if($request->piso)   $ambientes->where('piso',$request->piso);
        $ambientes->orderBy('codambiente');
        if($request->idambiente) $ambientes=Ser_Ambiente::where('idambiente',$request->idambiente);
        return ['ambientes'=>$ambientes->get(),'ipbirt'=>$ip];
    }

    public function storeAmbiente(Request $request)
    {   
        $ambiente=new Ser_Ambiente();
        $ambiente->idestablecimiento=$request->idestablecimiento;
        $ambiente->codambiente=$request->codambiente;
        $ambiente->piso=$request->piso;
        $ambiente->tipo=$request->tipo;
        $ambiente->capacidad=$request->capacidad;
        $ambiente->garantia=$request->garantia;
        $ambiente->tarifasocio=$request->tarifasocio;
        $ambiente->tarifareal=$request->tarifareal;
        $ambiente->save();
    }

    public function updateAmbiente(Request $request)
    {
        $ambiente=Ser_Ambiente::findOrFail($request->idambiente);
        $ambiente->codambiente=$request->codambiente;
        $ambiente->piso=$request->piso;
        $ambiente->tipo=$request->tipo;
        $ambiente->capacidad=$request->capacidad;
        $ambiente->garantia=$request->garantia;
        $ambiente->tarifasocio=$request->tarifasocio;
        $ambiente->tarifareal=$request->tarifareal;
        $ambiente->save();
    }

    public function switchAmbiente(Request $request)
    {
        $ambiente=Ser_Ambiente::findOrFail($request->idambiente);
        $ambiente->activo=abs($ambiente->activo-1);
        $ambiente->save();
    }

    public function storeBloque(Request $request)
    {
        DB::table('ser__establecimientos')->where('idestablecimiento',$request->idestablecimiento)
            ->update(['cantgrupos'=>$request->cantgrupos]);
        DB::table('ser__ambientes')->where('idestablecimiento',$request->idestablecimiento)
            ->where('codambiente','like',chr($request->nrgrupo+64).'%')->delete();
        for($i=1;$i<=$request->fil;$i++)
        {
            for($j=1;$j<=$request->col;$j++)
            {
                $ambiente=new Ser_Ambiente();
                $j<10?$caja="0".$j:$caja=$j;
                $request->cantgrupos[0]==1?$sector="N":$sector="S";
                $ambiente->codambiente=chr($request->nrgrupo+64).$i.$sector.$caja;
                $ambiente->idestablecimiento=$request->idestablecimiento;
                $ambiente->tarifasocio=$request->tarifasocio;
                $ambiente->save();
            }
        }
    }

    function updateTarifa(Request $request)
    {
        DB::table('ser__ambientes')->where('idestablecimiento',$request->idestablecimiento)
            ->where('codambiente','like',chr($request->nrgrupo+64).'%')
            ->update(['tarifasocio'=>$request->tarifasocio]);
        exit;
    }

    function liberarAmbiente(Request $request) { echo $request->idambiente;
        DB::table('ser__ambientes')->where('idambiente',$request->idambiente)         
        ->update(['ocupado'=>0]);
        return 1;
    }


}
