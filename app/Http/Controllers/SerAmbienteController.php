<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Ser_Ambiente;
use App\Ser_Asignacion;

class SerAmbienteController extends Controller
{
    
    public function listaPisos(Request $request)
    {
        $idestablecimiento=$request->idestablecimiento;
        $raw=DB::raw('distinct(piso) as pisos');
        $pisos=Ser_Ambiente::select($raw)
                                ->where('idestablecimiento',$idestablecimiento)
                                ->orderBy('piso','asc')
                                ->get();

        return['pisos'=>$pisos];

    }public function listaAmbientes(Request $request)
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

    public function listaAmbientesSocio(Request $request)
    {
        $ip=config('app.ip'); 

        $buscararray = array();  
        if(!empty($request->buscar)){
            $buscararray = explode(" ",$request->buscar);
        }         
        
        if (sizeof($buscararray)>0){
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls)){
                    $sqls="(socios.numpapeleta like '%".$valor."%' or socios.nombre like '%".$valor."%' or socios.apaterno like '%".$valor."%' or socios.amaterno like '%".$valor."%' or socios.ci like '%".$valor."%')";
                }else{
                    $sqls.=" and (socios.numpapeleta like '%".$valor."%' or socios.nombre like '%".$valor."%' or socios.apaterno like '%".$valor."%' or socios.amaterno like '%".$valor."%' or socios.ci like '%".$valor."%')";
                }
                $ambientes=Ser_Ambiente::select('*')//canchas
                ->join('ser__asignacions','ser__asignacions.idambiente','ser__ambientes.idambiente')
                ->join('socios','socios.idsocio','ser__asignacions.idcliente')
                ->where('idestablecimiento',$request->idestablecimiento)
                ->whereraw($sqls);
                $ambientes->orderBy('codambiente');        
                return ['ambientes'=>$ambientes->get(),'ipbirt'=>$ip]; 
            } 
        }
        else {
            $ambientes=Ser_Ambiente::select('*','capacidad as porhora')//canchas
            ->where('idestablecimiento',$request->idestablecimiento);
            if($request->bloque) $ambientes->where('codambiente','like',$request->bloque.'%');
            if($request->piso)   $ambientes->where('piso',$request->piso);
            $ambientes->orderBy('codambiente');
            if($request->idambiente) $ambientes=Ser_Ambiente::where('idambiente',$request->idambiente);
            return ['ambientes'=>$ambientes->get(),'ipbirt'=>$ip];
        }
           
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

    function liberarAmbiente(Request $request) { 
        
        //DB::table('ser__ambientes')->where('idambiente',$request->idambiente)->update(['ocupado'=>0]);

        $ambiente=Ser_Ambiente::findOrFail($request->idambiente);
        $ambiente->ocupado=0;
        $ambiente->save();
                
        //DB::table('ser__asignacions')->where('idasignacion',$request->idasignacion)->update(['vigente'=>0]);

        $asignacion=Ser_Asignacion::findOrFail($request->idasignacion);
        $asignacion->vigente=0;
        $asignacion->save();

        return 1;
    }


}
