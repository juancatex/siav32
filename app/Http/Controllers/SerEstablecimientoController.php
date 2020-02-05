<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Ser_Establecimiento;

class SerEstablecimientoController extends Controller
{
    public function listaEstablecimientos(Request $request)
    {
        $sector=DB::raw("case 
            when locate('NICH',nomestablecimiento)>0 then 'NICHOS'  
            when locate('SARC',nomestablecimiento)>0 then 'SARCÓFAGOS' 
            when locate('FOSA',nomestablecimiento)>0 then 'FOSA' 
        end as sector");
        $establecimientos=Ser_Establecimiento::
        select('idestablecimiento','fil__filials.idfilial','fil__filials.iddepartamento','telefonos',
        'ser__servicios.idservicio','nommunicipio','nomservicio','codservicio','nomestablecimiento',
        'iddocumentos','idimplementos','cantgrupos',$sector,
        'ser__establecimientos.direccion','ser__establecimientos.descripcion','ser__establecimientos.activo')
        ->join('fil__filials','fil__filials.idfilial','ser__establecimientos.idfilial')
        ->join('ser__servicios','ser__servicios.idservicio','ser__establecimientos.idservicio')
        ->join('par_municipios','par_municipios.idmunicipio','fil__filials.idmunicipio')
        ->join('par_departamentos','par_departamentos.iddepartamento','fil__filials.iddepartamento');
        if($request->iddepartamento)
            $establecimientos=$establecimientos->where('par_departamentos.iddepartamento',$request->iddepartamento);
        if($request->idestablecimiento)
            $establecimientos=$establecimientos->where('ser__establecimientos.idestablecimiento',$request->idestablecimiento);
        return ['establecimientos'=>$establecimientos->get()];
    }

    public function storeEstablecimiento(Request $request)
    {
        if($request->idservicio==4) $this->storeMausoleo($request); 
        $establecimiento=new Ser_Establecimiento();
        $establecimiento->idfilial=$request->idfilial;
        $establecimiento->idservicio=$request->idservicio;
        $establecimiento->nomestablecimiento=$request->nomestablecimiento;
        $establecimiento->descripcion=$request->descripcion;
        $establecimiento->direccion=$request->direccion;
        $establecimiento->telefonos=$request->telefonos;
        $establecimiento->save();
    }

    function storeMausoleo($request)
    {
        for($i=0;$i<3;$i++)
        {
            $establecimiento=new Ser_Establecimiento();
            $establecimiento->idfilial=$request->idfilial;
            $establecimiento->idservicio=4;
            //$establecimiento->cantgrupos=$request->configuracion;
            if($i==0) { $establecimiento->nomestablecimiento="MAUSOLEO NICHOS";     $establecimiento->cantgrupos="0,100"; }
            if($i==1) { $establecimiento->nomestablecimiento="MAUSOLEO SARCÓFAGOS"; $establecimiento->cantgrupos="0,100"; }
            if($i==2) $establecimiento->nomestablecimiento="MAUSOLEO FOSA COMÚN";
            $establecimiento->direccion=$request->direccion;
            $establecimiento->telefonos=$request->telefonos;
            $establecimiento->save();    
        }
        exit;
    }

    function storeCantgrupos($request)
    {
        $establecimiento=Ser_Establecimiento::findOrFail($request->idestablecimiento);        
        $establecimiento->cantgrupos=$request->cantgrupos;
        $establecimiento->save(); 
        exit;
    }

    public function updateEstablecimiento(Request $request)
    {
        if($request->cantgrupos)    $this->storeCantgrupos ($request);
        $establecimiento=Ser_Establecimiento::findOrFail($request->idestablecimiento);
        $establecimiento->idfilial=$request->idfilial;
        $establecimiento->idservicio=$request->idservicio;
        $establecimiento->nomestablecimiento=$request->nomestablecimiento;
        $establecimiento->descripcion=$request->descripcion;
        $establecimiento->direccion=$request->direccion;
        $establecimiento->telefonos=$request->telefonos;
        $establecimiento->save();
    }

    public function switchEstablecimiento(Request $request)
    {
        $establecimiento=Ser_Establecimiento::findOrFail($request->idestablecimiento);
        $establecimiento->activo=abs($establecimiento->activo-1);
        $establecimiento->save();
    }

    public function storeRequisitos(Request $request)
    {
        $establecimiento=Ser_Establecimiento::findOrFail($request->idestablecimiento);
        $establecimiento->iddocumentos=$request->iddocumentos;
        $establecimiento->save();
    }

}
