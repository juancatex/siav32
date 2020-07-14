<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Ser_Asignacion;
use App\Ser_Civil;
use App\Socio;


class SerAsignacionController extends Controller
{
    public function listaAsignaciones(Request $request)
    {
        $noches=DB::raw('datediff(curdate(),fechaentrada) as noches'); 
        //$currhora=DB::raw('curtime() as currhora');
        //$currfecha=DB::raw('curdate() as currfecha');
        $asignaciones=Ser_Asignacion::
        select('idasignacion','idcliente','tipocliente','ser__asignacions.idambiente',
        'nrasignacion','fechaentrada','fechasalida','horaentrada','horasalida',$noches,
        'nomgrado','nombre', 'apaterno','amaterno','nomfuerza')
        ->join('socios','socios.idsocio','ser__asignacions.idcliente')
        ->join('par_grados','par_grados.idgrado','socios.idgrado')
        ->join('par_fuerzas','par_fuerzas.idfuerza','socios.idfuerza')
        ->join('ser__ambientes','ser__ambientes.idambiente','ser__asignacions.idambiente')
        ->join('ser__establecimientos','ser__establecimientos.idestablecimiento','ser__ambientes.idestablecimiento')
        ->where('ser__establecimientos.idestablecimiento',$request->idestablecimiento);
        if($request->bloque) $asignaciones=$asignaciones->where('codambiente','like',$request->bloque.'%');
        if($request->piso)   $asignaciones=$asignaciones->where('piso',$request->piso);
        return ['asignaciones'=>$asignaciones->get(),'ipbirt'=>$_SERVER['SERVER_ADDR'],
            'currfecha'=>date('Y-m-d'),'currhora'=>date('H:i')];
    }

    public function verAsignacion(Request $request)
    {   
        $dias=DB::raw('datediff(curdate(),fechaentrada) as dias'); 
        $noches=DB::raw('datediff(fechasalida,fechaentrada) as noches'); //para hospedaje
        $horaentrada=DB::raw('substring(horaentrada,1,5) as horaentrada');
        $horasalida=DB::raw('substring(horasalida,1,5) as horasalida');
        $asignacion=Ser_Asignacion::select('*',$dias,$noches,$horaentrada,$horasalida);
        $asignacion=$asignacion->where('idasignacion','=',$request->idasignacion);
        return ['asignacion'=>$asignacion->get()];
    }

    public function storeAsignacion(Request $request)
    {
        $asignacion=new Ser_Asignacion();
        $asignacion->idcliente=$request->idcliente;
        $asignacion->tipocliente=$request->tipocliente;
        $asignacion->idambiente=$request->idambiente;
        $asignacion->nrasignacion=$request->nrasignacion;
        $asignacion->fechasolicitud=$request->fechasolicitud;
        $asignacion->mesboleta=$request->mesboleta;
        $asignacion->ocupantes=$request->ocupantes;
        $asignacion->iddocumentos=$request->iddocumentos;
        $asignacion->idimplementos=$request->idimplementos;
        $asignacion->fechaentrada=$request->fechaentrada;
        $asignacion->fechasalida=$request->fechasalida;
        $asignacion->horaentrada=$request->horaentrada;
        $asignacion->horasalida=$request->horasalida;
        $asignacion->fechadefuncion=$request->fechadefuncion;
        $asignacion->idresponsable=$request->idresponsable;
        $asignacion->idrepresentante=$request->idrepresentante;
        $asignacion->obs1=$request->obs1;
        $asignacion->save();
        DB::table('ser__ambientes')->where('idambiente',$request->idambiente)->update(['ocupado'=>1]);
    }

    public function updateAsignacion(Request $request)
    {   
        $asignacion=Ser_Asignacion::findOrFail($request->idasignacion);
        $asignacion->fechasolicitud=$request->fechasolicitud;
        $asignacion->mesboleta=$request->mesboleta;
        $asignacion->ocupantes=$request->ocupantes;
        $asignacion->iddocumentos=$request->iddocumentos;
        $asignacion->idimplementos=$request->idimplementos;
        $asignacion->fechaentrada=$request->fechaentrada;
        $asignacion->horaentrada=$request->horaentrada;
        $asignacion->fechasalida=$request->fechasalida;
        $asignacion->horasalida=$request->horasalida;
        $asignacion->nrasignacion=$request->nrasignacion;
        $asignacion->fechadefuncion=$request->fechadefuncion;
        $asignacion->idresponsable=$request->idresponsable;
        $asignacion->idrepresentante=$request->idrepresentante;
        $asignacion->obs1=$request->obs1;
        $asignacion->save();
    }

    public function verCliente(Request $request)
    {   $fecha=DB::raw('curdate() as currfecha');
        $hora=DB::raw('substring(curtime(),1,5) as currhora');
        if($request->tipocliente=="s")
            $cliente=Socio::
            select('socios.idsocio as idcliente','nombre','apaterno','amaterno','ci','abrvdep',
            'numpapeleta','nomgrado','nomfuerza','telcelular','nomdestino','idestservicios',
            'par_municipios.idmunicipio','nommunicipio','socios.idestadocivil','nomestadocivil',
            'rutafoto','nomestado',$fecha,$hora)
            ->join('par_grados','par_grados.idgrado','=','socios.idgrado')
            ->join('par_fuerzas','par_fuerzas.idfuerza','=','socios.idfuerza')
            ->join('par_departamentos','par_departamentos.iddepartamento','=','iddepartamentoexpedido')
            ->join('par_estadocivil','par_estadocivil.idestadocivil','=','socios.idestadocivil')
            ->join('par__destinos','par__destinos.iddestino','=','socios.iddestino')
            ->join('par_municipios','par_municipios.idmunicipio','=','par__destinos.idmunicipio')
            ->join('par_estadomodulos','par_estadomodulos.idestado','=','socios.idestservicios')
            ->where('par_estadomodulos.idmodulo','=',4)
            ->where('socios.idsocio','=',$request->idcliente)->get();
        if($request->tipocliente=="c")
            $cliente=Ser_Civil::
            select('idcivil as idcliente','nombre','apaterno','amaterno','ci','abrvdep','telcelular',
            'fechanac','sexo',$fecha,$hora)
            ->join('par_departamentos','par_departamentos.iddepartamento','=','ser__civils.iddepartamentoexpedido')
            ->where('ser__civils.idcivil','=',$request->idcliente)->get();
        return ['cliente'=>$cliente];
    }
    
}
