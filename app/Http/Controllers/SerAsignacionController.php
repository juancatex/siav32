<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Ser_Asignacion;
use App\Ser_Ambiente;
use App\Ser_Civil;
use App\Afi_Beneficiario;
use App\Socio;


class SerAsignacionController extends Controller
{
    public function listaAsignaciones(Request $request)
    {
        $ip=config('app.ip'); 
        $noches=DB::raw('datediff(curdate(),fechaentrada) as noches'); 
        //$currhora=DB::raw('curtime() as currhora');
        //$currfecha=DB::raw('curdate() as currfecha');
        
        $asignaciones_all=Ser_Asignacion::
        select('idasignacion','idcliente','vigente','tipocliente','ser__asignacions.idambiente',
            'nrasignacion','fechaentrada','fechasalida','horaentrada','horasalida','tipocliente')            
            ->join('ser__ambientes','ser__ambientes.idambiente','ser__asignacions.idambiente')
            ->join('ser__establecimientos','ser__establecimientos.idestablecimiento','ser__ambientes.idestablecimiento')
            ->where('ser__establecimientos.idestablecimiento',$request->idestablecimiento)
            ->where('ser__asignacions.vigente',1);
            if($request->bloque) $asignaciones_all=$asignaciones_all->where('codambiente','like',$request->bloque.'%');
            if($request->piso)   $asignaciones_all=$asignaciones_all->where('piso',$request->piso);
        $asignaciones_all=$asignaciones_all->get();

        $x=[]; $i=0; 
        foreach($asignaciones_all as $asi){             
            if ($asi->tipocliente=='s') {
                $asignaciones=Ser_Asignacion::
                select('idasignacion','idcliente','vigente','tipocliente','ser__asignacions.idambiente',
                'nrasignacion','fechaentrada','fechasalida','horaentrada','horasalida',$noches,
                'nomgrado','nombre', 'apaterno','amaterno','nomfuerza')
                ->join('socios','socios.idsocio','ser__asignacions.idcliente')
                ->join('par_grados','par_grados.idgrado','socios.idgrado')
                ->join('par_fuerzas','par_fuerzas.idfuerza','socios.idfuerza')
                ->join('ser__ambientes','ser__ambientes.idambiente','ser__asignacions.idambiente')
                ->join('ser__establecimientos','ser__establecimientos.idestablecimiento','ser__ambientes.idestablecimiento')
                ->where('ser__establecimientos.idestablecimiento',$request->idestablecimiento)
                ->where('ser__asignacions.vigente',1)
                ->where('ser__asignacions.idasignacion',$asi->idasignacion);
                if($request->bloque) $asignaciones=$asignaciones->where('codambiente','like',$request->bloque.'%');
                if($request->piso)   $asignaciones=$asignaciones->where('piso',$request->piso);                
                $x[$i]=$asignaciones; $i++;
            }
            if ($asi->tipocliente=='c') {
                $asignaciones=Ser_Asignacion::
                select('idasignacion','idcliente','vigente','tipocliente','ser__asignacions.idambiente',
                'nrasignacion','fechaentrada','fechasalida','horaentrada','horasalida',$noches,
                'idcivil','nombre', 'apaterno','amaterno','idcivil')
                ->join('ser__civils','ser__civils.idcivil','ser__asignacions.idcliente')                
                ->join('ser__ambientes','ser__ambientes.idambiente','ser__asignacions.idambiente')
                ->join('ser__establecimientos','ser__establecimientos.idestablecimiento','ser__ambientes.idestablecimiento')
                ->where('ser__establecimientos.idestablecimiento',$request->idestablecimiento)
                ->where('ser__asignacions.vigente',1)
                ->where('ser__asignacions.idasignacion',$asi->idasignacion);
                if($request->bloque) $asignaciones=$asignaciones->where('codambiente','like',$request->bloque.'%');
                if($request->piso)   $asignaciones=$asignaciones->where('piso',$request->piso);                
                $x[$i]=$asignaciones; $i++;
            }
            if ($asi->tipocliente=='b') {
                $asignaciones=Ser_Asignacion::
                select('idasignacion','idcliente','vigente','tipocliente','ser__asignacions.idambiente',
                'nrasignacion','fechaentrada','fechasalida','horaentrada','horasalida',$noches,
                'idbeneficiario','nombre', 'apaterno','amaterno','idbeneficiario')
                ->join('afi__beneficiarios','afi__beneficiarios.idbeneficiario','ser__asignacions.idcliente')                
                ->join('ser__ambientes','ser__ambientes.idambiente','ser__asignacions.idambiente')
                ->join('ser__establecimientos','ser__establecimientos.idestablecimiento','ser__ambientes.idestablecimiento')
                ->where('ser__establecimientos.idestablecimiento',$request->idestablecimiento)
                ->where('ser__asignacions.vigente',1)
                ->where('ser__asignacions.idasignacion',$asi->idasignacion);
                if($request->bloque) $asignaciones=$asignaciones->where('codambiente','like',$request->bloque.'%');
                if($request->piso)   $asignaciones=$asignaciones->where('piso',$request->piso);                
                $x[$i]=$asignaciones; $i++;
            }            
        } 
                                
        if (count($x)==0) {
            return ['asignaciones'=>'','ipbirt'=>$ip, 'currfecha'=>date('Y-m-d'),'currhora'=>date('H:i')];
        }            
        else {
            $asigna=$x[0];
            for ($a=1; $a<count($x); $a++) {            
                $asigna = $asigna->union($x[$a]);
            }
            return ['asignaciones'=>$asigna->get(),'ipbirt'=>$ip, 'currfecha'=>date('Y-m-d'),'currhora'=>date('H:i')];
        }
        
        // $asignaciones=Ser_Asignacion::
        // select('idasignacion','idcliente','vigente','tipocliente','ser__asignacions.idambiente',
        // 'nrasignacion','fechaentrada','fechasalida','horaentrada','horasalida',$noches,
        // 'nomgrado','nombre', 'apaterno','amaterno','nomfuerza')
        // ->join('socios','socios.idsocio','ser__asignacions.idcliente')
        // ->join('par_grados','par_grados.idgrado','socios.idgrado')
        // ->join('par_fuerzas','par_fuerzas.idfuerza','socios.idfuerza')
        // ->join('ser__ambientes','ser__ambientes.idambiente','ser__asignacions.idambiente')
        // ->join('ser__establecimientos','ser__establecimientos.idestablecimiento','ser__ambientes.idestablecimiento')
        // ->where('ser__establecimientos.idestablecimiento',$request->idestablecimiento)
        // ->where('ser__asignacions.vigente',1);
        // if($request->bloque) $asignaciones=$asignaciones->where('codambiente','like',$request->bloque.'%');
        // if($request->piso)   $asignaciones=$asignaciones->where('piso',$request->piso);
        // return ['asignaciones'=>$asignaciones->get(),'ipbirt'=>$ip,
        //     'currfecha'=>date('Y-m-d'),'currhora'=>date('H:i')];
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

        //capccidad del amebiente
        $capacidad = DB::table('ser__ambientes')->select('capacidad')->where('idambiente',$request->idambiente)->first();        
        //total de asignados a ese ambiebte
        $vigentes = DB::table('ser__asignacions')->select('vigente')->where('idambiente',$request->idambiente)->where('vigente',1)->count();

        $asignacion=new Ser_Asignacion();
        $asignacion->idcliente=$request->idcliente;
        $asignacion->tipocliente=$request->tipocliente;
        $asignacion->vigente=1;
        $asignacion->idambiente=$request->idambiente;
        $asignacion->nrasignacion=$request->nrasignacion;
        $asignacion->fechasolicitud=$request->fechasolicitud;
        $asignacion->mesboleta=$request->mesboleta;
        $asignacion->ocupantes=$request->ocupantes;
        $asignacion->iddocumentos=$request->iddocumentos;
        $asignacion->idimplementos=$request->idimplementos;
        $asignacion->fechaentrada=$request->fechaentrada;
        $asignacion->horaentrada=$request->horaentrada;
        $asignacion->fechasalida=$request->fechasalida;
        $asignacion->horasalida=$request->horasalida;
        $asignacion->fechadefuncion=$request->fechadefuncion;
        $asignacion->idresponsable=$request->idresponsable;
        $asignacion->idrepresentante=$request->idrepresentante;
        $asignacion->obs1=$request->obs1;
        $asignacion->save();

        if ($capacidad->capacidad == $vigentes) {
            //DB::table('ser__ambientes')->where('idambiente',$request->idambiente)->update(['ocupado'=>1]);
            $ambiente=Ser_Ambiente::findOrFail($request->idambiente);
            $ambiente->ocupado=1;
            $ambiente->save();
        }            
        else    
            {};
        
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
            'numpapeleta','nomgrado','nomfuerza','telcelular','idestservicios', 
            //'nomdestino',
            //'par_municipios.idmunicipio','nommunicipio',
            'socios.idestadocivil','nomestadocivil',
            'rutafoto',
            'nomestado',
            $fecha,$hora)
            ->join('par_grados','par_grados.idgrado','=','socios.idgrado')
            ->join('par_fuerzas','par_fuerzas.idfuerza','=','socios.idfuerza')
            ->join('par_departamentos','par_departamentos.iddepartamento','=','iddepartamentoexpedido')
            ->join('par_estadocivil','par_estadocivil.idestadocivil','=','socios.idestadocivil')
            //->join('par__destinos','par__destinos.iddestino','=','socios.iddestino')
            //->join('par_municipios','par_municipios.idmunicipio','=','par__destinos.idmunicipio')
            ->join('par_estadomodulos','par_estadomodulos.idestado','=','socios.idestservicios')
            ->where('par_estadomodulos.idmodulo','=',4)
            ->where('socios.idsocio','=',$request->idcliente)->get();
        if($request->tipocliente=="c")
            $cliente=Ser_Civil::
            select('idcivil as idcliente','nombre','apaterno','amaterno','ci','abrvdep','telcelular',
            'fechanac','sexo',$fecha,$hora)
            ->join('par_departamentos','par_departamentos.iddepartamento','=','ser__civils.iddepartamento')
            ->where('ser__civils.idcivil','=',$request->idcliente)->get();
        if($request->tipocliente=="b")
            $cliente=Afi_Beneficiario::
            select('idbeneficiario as idcliente','nombre','apaterno','amaterno','ci','abrvdep','telcelular',
            'fechanac','sexo',$fecha,$hora)
            ->join('par_departamentos','par_departamentos.iddepartamento','=','afi__beneficiarios.iddepartamento')
            ->where('afi__beneficiarios.idbeneficiario','=',$request->idcliente)->get();
        return ['cliente'=>$cliente];
    }

    public function listarRegistrados(Request $request)
    { 
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
            }            
            $registrados=Ser_Asignacion::
            select('idasignacion','par_grados.nomgrado', 'socios.apaterno','socios.amaterno','socios.nombre','socios.idsocio','socios.numpapeleta','fechaentrada','horaentrada','fil__filials.sigla','ser__servicios.codservicio')
            ->join('socios','socios.idsocio','ser__asignacions.idcliente')
            ->join('par_grados','par_grados.idgrado','socios.idgrado')                
            ->join('ser__ambientes','ser__ambientes.idambiente','ser__asignacions.idambiente')
            ->join('ser__establecimientos','ser__ambientes.idestablecimiento','ser__establecimientos.idestablecimiento')
            ->join('fil__filials','fil__filials.idfilial','ser__establecimientos.idfilial')
            ->join('ser__servicios','ser__servicios.idservicio','ser__establecimientos.idservicio')
            ->whereraw($sqls)                        
            ->orderBy('socios.nombre', 'asc')
            ->paginate(10);
        }
        else{
            $registrados=Ser_Asignacion::
            select('idasignacion','par_grados.nomgrado', 'socios.apaterno','socios.amaterno','socios.nombre','socios.idsocio','socios.numpapeleta','fechaentrada','horaentrada','fil__filials.sigla','ser__servicios.codservicio')
            ->join('socios','socios.idsocio','ser__asignacions.idcliente')
            ->join('par_grados','par_grados.idgrado','socios.idgrado')                
            ->join('ser__ambientes','ser__ambientes.idambiente','ser__asignacions.idambiente')
            ->join('ser__establecimientos','ser__ambientes.idestablecimiento','ser__establecimientos.idestablecimiento')
            ->join('fil__filials','fil__filials.idfilial','ser__establecimientos.idfilial')
            ->join('ser__servicios','ser__servicios.idservicio','ser__establecimientos.idservicio')
            ->where('idasignacion','=',9999999)->paginate(10);    
        }                        
            
        return [
            'pagination' => [
                'total'        => $registrados->total(),
                'current_page' => $registrados->currentPage(),
                'per_page'     => $registrados->perPage(),
                'last_page'    => $registrados->lastPage(),
                'from'         => $registrados->firstItem(),
                'to'           => $registrados->lastItem(),
            ],
            'registrados' => $registrados
        ];
            
    }


    
}
