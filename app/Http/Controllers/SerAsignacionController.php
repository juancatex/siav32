<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Ser_Asignacion;
use App\Ser_Ambiente;
use App\Ser_Civil;
use App\Afi_Beneficiario;
use App\Ser_Implemento;
use App\Socio;
use DateTime;
use Illuminate\Support\Facades\Storage;
use PDF;
use Illuminate\Support\Facades\Auth;


class SerAsignacionController extends Controller
{
    public function index(Request $request)
    {
        $idestablecimiento=$request->idestablecimiento;
        $piso=$request->piso;

        //$pisos=

        $asignacion=Ser_ambiente::select('ser__ambientes.idambiente',
                                            'ser__ambientes.idestablecimiento',
                                            'codambiente',
                                            'piso',
                                            'tipo',
                                            'capacidad',
                                            'garantia',
                                            'tarifasocio',
                                            'tarifareal',
                                            'nomestablecimiento',
                                            )
                                    ->join('ser__establecimientos','ser__establecimientos.idestablecimiento','ser__ambientes.idestablecimiento')
                                    ->where('ser__ambientes.idestablecimiento',$idestablecimiento)
                                    ->where('ser__ambientes.piso',$piso)
                                    ->orderby('codambiente','asc')
                                    ->get();

        $rawsocio=DB::raw('concat(nomgrado," ",apaterno," ",amaterno," ",nombre," - ",nomfuerza) as nombres');
        //$rawsocio=DB::raw('concat(nomgrado," ",apaterno," ",amaterno," ",nombre," - ",nomfuerza) as nombres');
        $rawcivil=DB::raw('concat(apaterno," ",amaterno," ",nombre) as nombres');
        $fa=date("Y-m-d");
        $fechaactual=new DateTime($fa);
        $fa=strtotime($fa);

        $horaactual=strtotime(date("H:i"));
        
        foreach ($asignacion as $valor) {
            $idambiente=$valor->idambiente;
            
            $hospedajesocio=Ser_Asignacion::select('idasignacion',
                                                'idcliente',
                                                'tipocliente',
                                                'estado',
                                                'nrasignacion',
                                                'idimplementos',
                                                'fechaentrada',
                                                'horaentrada',
                                                'fechasalida',
                                                'obs1',
                                                $rawsocio,
                                                'ci',
                                                'ocupantes',
                                                'apaterno'
                                                )
                                        ->join('socios','socios.idsocio','ser__asignacions.idcliente')
                                        ->join('par_fuerzas','par_fuerzas.idfuerza','socios.idfuerza')
                                        ->join('par_grados','par_grados.idgrado','socios.idgrado')
                                        ->where('ser__asignacions.estado',2)//solo los asignados
                                        ->where('idambiente',$idambiente)
                                        ->where('tipocliente',1);//socio
                                        
            $hospedajecivil=Ser_Asignacion::select('idasignacion',
                                                    'idcliente',
                                                    'tipocliente',
                                                    'estado',
                                                    'nrasignacion',
                                                    'idimplementos',
                                                    'fechaentrada',
                                                    'horaentrada',
                                                    'fechasalida',
                                                    'obs1',
                                                    $rawcivil,
                                                    'ci',
                                                    'ocupantes',
                                                    'apaterno'

                                                    )
                                        ->join('ser__civils','ser__civils.idcivil','ser__asignacions.idcliente')
                                        ->where('idambiente',$idambiente)
                                        ->where('ser__asignacions.estado',2)//solo los asignados
                                        ->where(function($query) {
                                            $query->where('tipocliente',2)
                                                ->orwhere('tipocliente',3);
                                            })
                                        //->where('tipocliente',2)//civil
                                        ->union($hospedajesocio)
                                        ->get();
            $c=0;
            if(count($hospedajecivil)>0)
            {
                foreach ($hospedajecivil as $i => $v) {
                    $c=$c+$v->ocupantes;
                    $fechaingreso=new DateTime($v->fechaentrada);
                    $diferencia=$fechaactual->diff($fechaingreso);
                    //$horaingreso=strtotime($v->horaingreso);
                    if($fa>strtotime($v->fechaentrada))
                    {
                        if($horaactual>strtotime("14:00")) ///cambiar este valor para que la fecha de salida sea dinamica;
                            $v->difd=$diferencia->days+1;
                        else
                            $v->difd=$diferencia->days;
                    }
                    else
                    {
                        if($fa==strtotime($v->fechaentrada))
                            $v->difd=$diferencia->days+1;
                        else
                            $v->difd=$diferencia->days;
                    }
                        
                    
                    if($diferencia->days==0)
                        if($v->tipocliente==1 || $v->tipocliente==3)
                            $v->monto=$valor->tarifasocio;  
                        else
                            $v->monto=$valor->tarifareal;
                    else
                        if($v->tipocliente==1 || $v->tipocliente==3)
                            $v->monto=$valor->tarifasocio*$v->difd;  
                        else
                            $v->monto=$valor->tarifareal*$v->difd;
                        
                }
            }


            $valor->camasocupadas=$c;
            $valor->camasrestantes=$valor->capacidad-$c;
            $valor->hospedaje=$hospedajecivil;
            

        }
        return['asignacion'=>$asignacion];
    }
    public function reporteingreso(Request $request)
    {
        $idasignacion=$request->idasignacion;
        $tiposocio=$request->tiposocio;
        $rawsocio=DB::raw('concat(nomgrado," ",apaterno," ",amaterno," ",nombre," - ",nomfuerza) as nombres');
        $rawcivil=DB::raw('concat(apaterno," ",amaterno," ",nombre) as nombres');
        if($tiposocio==1)
        $hospedaje=Ser_Asignacion::select('idasignacion',
                                                'idcliente',
                                                'tipocliente',
                                                'estado',
                                                'nrasignacion',
                                                'idimplementos',
                                                'fechaentrada',
                                                'horaentrada',
                                                'fechasalida',
                                                'obs1',
                                                $rawsocio,
                                                'ci',
                                                'ocupantes',
                                                'codambiente',
                                                'piso',
                                                'tarifasocio',
                                                'tarifareal',
                                                'numpapeleta'
                                                

                                                )
                                        ->join('socios','socios.idsocio','ser__asignacions.idcliente')
                                        ->join('par_fuerzas','par_fuerzas.idfuerza','socios.idfuerza')
                                        ->join('par_grados','par_grados.idgrado','socios.idgrado')
                                        ->join('ser__ambientes','ser__ambientes.idambiente','ser__asignacions.idambiente')
                                        ->where('idasignacion',$idasignacion)
                                        ->get();
            else
                                        
            $hospedaje=Ser_Asignacion::select('idasignacion',
                                                    'idcliente',
                                                    'tipocliente',
                                                    'estado',
                                                    'nrasignacion',
                                                    'idimplementos',
                                                    'fechaentrada',
                                                    'horaentrada',
                                                    'fechasalida',
                                                    'obs1',
                                                    $rawcivil,
                                                    'ci',
                                                    'ocupantes',
                                                    'codambiente',
                                                    'piso',
                                                    'tarifasocio',
                                                    'tarifareal',
                                                    )
                                        ->join('ser__civils','ser__civils.idcivil','ser__asignacions.idcliente')
                                        ->join('ser__ambientes','ser__ambientes.idambiente','ser__asignacions.idambiente')
                                        ->where('idasignacion',$idasignacion)
                                        ->get();
            //dd($hospedaje);
            $implementos=$hospedaje[0]->idimplementos;
            $arrayimplementos=explode('|',$implementos);
            //dd($arrayimplementos);

            $implementos=Ser_Implemento::select('nomimplemento')->whereIn('idimplemento',$arrayimplementos)->get();
            //dd($implementos);
            /* return ['hospedaje'=>$hospedaje,
            'implementos'=>$implementos]; */
            $logo = Storage::path('fotos/cabecera_casacomunitaria.PNG');
            $logo64 = base64_encode(Storage::get('fotos/cabecera_casacomunitaria.PNG')); 
            

            // return view('reportes/servicios/reporteingreso')->with(['hospedaje'=>$hospedaje,
            //                                 'implementos'=>$implementos,
            //                                 'foto'=>'data:'.mime_content_type($logo) . ';base64,' . $logo64]);

         $pdf = PDF::loadView('reportes/servicios/reporteingreso', ['hospedaje'=>$hospedaje,
         'implementos'=>$implementos,
         'foto'=>'data:'.mime_content_type($logo) . ';base64,' . $logo64]); 
         return $pdf->stream('reporteingreso.pdf'); 

    }
    public function soliciardescuento(Request $request)
    {
        $idasignacion=$request->idasignacion;
        $asignacion=Ser_Asignacion::findOrFail($idasignacion);
        $asignacion->montosindescuento=$request->monto;
        $asignacion->descuento=1;
        $asignacion->save();   

    }
    public function autorizaciones(Request $request)
    {
        
        $rawsocio=DB::raw('concat(nomgrado," ",apaterno," ",amaterno," ",nombre," - ",nomfuerza) as nombres');
        $solicitud=Ser_Asignacion::select('idasignacion',
                                                'idcliente',
                                                'tipocliente',
                                                'estado',
                                                'nrasignacion',
                                                'idimplementos',
                                                'fechaentrada',
                                                'horaentrada',
                                                'fechasalida',
                                                'obs1',
                                                $rawsocio,
                                                'ci',
                                                'piso',
                                                'tarifasocio',
                                                'tarifareal',
                                                'numpapeleta',
                                                'ser__asignacions.updated_at',
                                                'montosindescuento',
                                                'descuento'
                                                )
                                        ->join('socios','socios.idsocio','ser__asignacions.idcliente')
                                        ->join('par_fuerzas','par_fuerzas.idfuerza','socios.idfuerza')
                                        ->join('par_grados','par_grados.idgrado','socios.idgrado')
                                        ->join('ser__ambientes','ser__ambientes.idambiente','ser__asignacions.idambiente')
                                        ->where('descuento','<>',0)
                                        ->paginate(50);
        
        return [
            'pagination' => [
                'total'        => $solicitud->total(),
                'current_page' => $solicitud->currentPage(),
                'per_page'     => $solicitud->perPage(),
                'last_page'    => $solicitud->lastPage(),
                'from'         => $solicitud->firstItem(),
                'to'           => $solicitud->lastItem(),
            ],
            'solicitud' => $solicitud
        ];
    }

    public function aprobardescuento(Request $request)
    {
        $idasignacion=$request->idasignacion;
        $asignacion=Ser_Asignacion::findOrFail($idasignacion);
        $asignacion->monto=$request->monto;
        $asignacion->obs2=$request->obs2;
        $asignacion->descuento=2;
        $asignacion->save();   
    }
    public function reportesalida(Request $request)
    {
        $idasignacion=$request->idasignacion;
        $tiposocio=$request->tiposocio;
        $rawsocio=DB::raw('concat(nomgrado," ",apaterno," ",amaterno," ",nombre," - ",nomfuerza) as nombres');
        $rawcivil=DB::raw('concat(apaterno," ",amaterno," ",nombre) as nombres');
        if($tiposocio==1)
        $hospedaje=Ser_Asignacion::select('idasignacion',
                                                'idcliente',
                                                'tipocliente',
                                                'estado',
                                                'nrasignacion',
                                                'idimplementos',
                                                'fechaentrada',
                                                'horaentrada',
                                                'fechasalida',
                                                'horasalida',
                                                'obs2',
                                                $rawsocio,
                                                'ci',
                                                'ocupantes',
                                                'codambiente',
                                                'piso',
                                                'tarifasocio',
                                                'tarifareal',
                                                'numpapeleta',
                                                'cantdias',
                                                'numerofactura',
                                                'razonsocial',
                                                'nit',
                                                'monto'

                                                )
                                        ->join('socios','socios.idsocio','ser__asignacions.idcliente')
                                        ->join('par_fuerzas','par_fuerzas.idfuerza','socios.idfuerza')
                                        ->join('par_grados','par_grados.idgrado','socios.idgrado')
                                        ->join('ser__ambientes','ser__ambientes.idambiente','ser__asignacions.idambiente')
                                        ->join('con__facturas','con__facturas.idfactura','ser__asignacions.idfactura')
                                        ->where('idasignacion',$idasignacion)
                                        ->get();
            else
                                        
            $hospedaje=Ser_Asignacion::select('idasignacion',
                                                    'idcliente',
                                                    'tipocliente',
                                                    'estado',
                                                    'nrasignacion',
                                                    'idimplementos',
                                                    'fechaentrada',
                                                    'horaentrada',
                                                    'fechasalida',
                                                    'horasalida',
                                                    'obs2',
                                                    $rawcivil,
                                                    'ci',
                                                    'ocupantes',
                                                    'codambiente',
                                                    'piso',
                                                    'tarifasocio',
                                                    'tarifareal',
                                                    'monto',
                                                    'cantdias',
                                                    'numerofactura',
                                                    'razonsocial',
                                                    'nit'
                                                    )
                                        ->join('ser__civils','ser__civils.idcivil','ser__asignacions.idcliente')
                                        ->join('ser__ambientes','ser__ambientes.idambiente','ser__asignacions.idambiente')
                                        ->join('con__facturas','con__facturas.idfactura','ser__asignacions.idfactura')
                                        ->where('idasignacion',$idasignacion)
                                        ->get();

            $implementos=$hospedaje[0]->idimplementos;
            $arrayimplementos=explode('|',$implementos);
            //dd($arrayimplementos);

            $implementos=Ser_Implemento::select('nomimplemento')->whereIn('idimplemento',$arrayimplementos)->get();
            //dd($implementos);
            /* return ['hospedaje'=>$hospedaje,
            'implementos'=>$implementos]; */
            $logo = Storage::path('fotos/cabecera_casacomunitaria.PNG');
            $logo64 = base64_encode(Storage::get('fotos/cabecera_casacomunitaria.PNG')); 
            

            /* return view('reportes/servicios/reportesalida')->with(['hospedaje'=>$hospedaje,
                                            'implementos'=>$implementos,
                                            'foto'=>'data:'.mime_content_type($logo) . ';base64,' . $logo64]); */
                                            $pdf = PDF::loadView('reportes/servicios/reportesalida', ['hospedaje'=>$hospedaje,
                                            'implementos'=>$implementos,
                                            'foto'=>'data:'.mime_content_type($logo) . ';base64,' . $logo64]); 
                                            return $pdf->stream('reportesalida.pdf'); 
    }

    public function registrarsalida(Request $request)
    {
        $asignacion=Ser_Asignacion::findOrFail($request->idasignacion);
        $asignacion->fechasalida=$request->fechasalida;
        $asignacion->horasalida=$request->horasalida;
        $asignacion->monto=$request->monto;
        $asignacion->obs2=$request->observaciones;
        $asignacion->idfactura=$request->idfactura;
        $asignacion->estado=0;
        $asignacion->cantdias=$request->cantdias;
        $asignacion->u_salida=Auth::id();
        $asignacion->save();   
        /* DB::table('ser__asignacions')
                ->where('idasignacion', $request->idasignacion)
                ->update(['estado' => 0]); //liberar habitacion o cama */
    }
    
    public function listaAsignaciones(Request $request)
    {
        $ip=config('app.ip'); 
        //$noches=DB::raw('datediff(curdate(),fechaentrada) as noches'); 
        $noches=DB::raw('if(datediff(curdate(),fechaentrada)=0,"1",if(CURRENT_TIME()>"14:00",datediff(curdate(),fechaentrada)+1,datediff(curdate(),fechaentrada))) as noches');
        
        //$currhora=DB::raw('curtime() as currhora');
        //$currfecha=DB::raw('curdate() as currfecha');
        
        $asignaciones_all=Ser_Asignacion::
        select('idasignacion','idcliente','vigente','tipocliente','ser__asignacions.idambiente',
            'nrasignacion','ocupantes','fechaentrada','fechasalida','horaentrada','horasalida','tipocliente')            
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
                'nrasignacion','ocupantes','fechaentrada','fechasalida','horaentrada','horasalida',$noches,
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
                'nrasignacion','ocupantes','fechaentrada','fechasalida','horaentrada','horasalida',$noches,
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
                'nrasignacion','ocupantes','fechaentrada','fechasalida','horaentrada','horasalida',$noches,
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
        //$noches=DB::raw('datediff(fechasalida,fechaentrada) as noches'); //para hospedaje
        //$noches=DB::raw('if(datediff(curdate(),fechaentrada)=0,"1",datediff(curdate(),fechaentrada)) as noches'); 
        $noches=DB::raw('if(datediff(fechasalida,fechaentrada)=0,"1",if(horasalida>"14:00",datediff(fechasalida,fechaentrada)+1,datediff(fechasalida,fechaentrada))) as noches');
        $horaentrada=DB::raw('substring(horaentrada,1,5) as horaentrada');
        $horasalida=DB::raw('substring(horasalida,1,5) as horasalida');
        $asignacion=Ser_Asignacion::select('*',$dias,$noches,$horaentrada,$horasalida);
        $asignacion=$asignacion->where('idasignacion','=',$request->idasignacion);
        return ['asignacion'=>$asignacion->get()];
    }

    public function storeAsignacion(Request $request)
    {
                
        //dd($request->familiar);
        if(count($request->familiar)>0)
        {
            foreach ($request->familiar as $key => $value) {
                $asignacion=new Ser_Asignacion();
                $asignacion->idcliente=$value;
                $asignacion->tipocliente=3;
                $asignacion->estado=2;
                $asignacion->idambiente=$request->idambiente;
                $asignacion->nrasignacion=$request->nrasignacion;
                $asignacion->fechasolicitud=$request->fechasolicitud;
                $asignacion->mesboleta=$request->mesboleta;
                $asignacion->ocupantes=1;
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
                $asignacion->u_registro=Auth::id();
                $asignacion->save();
                
            }

        }
        else
        {
            $asignacion=new Ser_Asignacion();
            $asignacion->idcliente=$request->idcliente;
            $asignacion->tipocliente=$request->tipocliente;
            $asignacion->estado=2;
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
            $asignacion->u_registro=Auth::id();
            $asignacion->save();
        }
        
    

        return $asignacion->idasignacion;
        
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
        
        $rawsocio=DB::raw('concat(nomgrado," ",apaterno," ",amaterno," ",nombre," - ",nomfuerza) as nombres');
        $rawcivil=DB::raw('concat(apaterno," ",amaterno," ",nombre) as nombres');$buscararray = array();  
        if(!empty($request->buscar)){
            $buscararray = explode(" ",$request->buscar);
        }
        if (sizeof($buscararray)>0){
            $sqls='';
            $sql2=''; 
            foreach($buscararray as $valor){
                if(empty($sqls)){
                    $sqls="(socios.numpapeleta like '%".$valor."%' or socios.nombre like '%".$valor."%' or socios.apaterno like '%".$valor."%' or socios.amaterno like '%".$valor."%' or socios.ci like '%".$valor."%' )";
                    $sql2="(ser__civils.nombre like '%".$valor."%' or ser__civils.apaterno like '%".$valor."%' or ser__civils.amaterno like '%".$valor."%' or ser__civils.ci like '%".$valor."%' )";
                }else{
                    $sqls.=" and (socios.numpapeleta like '%".$valor."%' or socios.nombre like '%".$valor."%' or socios.apaterno like '%".$valor."%' or socios.amaterno like '%".$valor."%' or socios.ci like '%".$valor."%' )";
                    $sql2="(ser__civils.nombre like '%".$valor."%' or ser__civils.apaterno like '%".$valor."%' or ser__civils.amaterno like '%".$valor."%' or ser__civils.ci like '%".$valor."%' )";
                } 
            }  
            
            $hospedajesocio=Ser_Asignacion::select('idasignacion',
                                                    'idcliente',
                                                    'tipocliente',
                                                    'estado',
                                                    'nrasignacion',
                                                    'idimplementos',
                                                    'fechaentrada',
                                                    'horaentrada',
                                                    'fechasalida',
                                                    'horasalida',
                                                    'obs1',
                                                    $rawsocio,
                                                    'ci',
                                                    'ocupantes',
                                                    'codambiente',
                                                    'piso',
                                                    'tipo',
                                                    'monto'
                                                    )
                                                ->join('socios','socios.idsocio','ser__asignacions.idcliente')
                                                ->join('par_fuerzas','par_fuerzas.idfuerza','socios.idfuerza')
                                                ->join('par_grados','par_grados.idgrado','socios.idgrado')
                                                ->join('ser__ambientes','ser__ambientes.idambiente','ser__asignacions.idambiente')
                                                ->where('ser__asignacions.estado',0)//solo los liberados
                                                ->where('ser__asignacions.activo',1)
                                                ->whereraw($sqls)   
                                                ->where('tipocliente',1);//socio
            
        $hospedajecivil=Ser_Asignacion::select('idasignacion',
                        'idcliente',
                        'tipocliente',
                        'estado',
                        'nrasignacion',
                        'idimplementos',
                        'fechaentrada',
                        'horaentrada',
                        'fechasalida',
                        'horasalida',
                        'obs1',
                        $rawcivil,
                        'ci',
                        'ocupantes',
                        'codambiente',
                        'piso',
                        'tipo',
                        'monto'
                        )
            ->join('ser__civils','ser__civils.idcivil','ser__asignacions.idcliente')
            ->join('ser__ambientes','ser__ambientes.idambiente','ser__asignacions.idambiente')
            ->where('ser__asignacions.estado',0)//solo los liberados
            ->where('ser__asignacions.activo',1)
            ->where(function($query) {
                $query->where('tipocliente',2)
                    ->orwhere('tipocliente',3);
                })
            //->where('tipocliente',2)//civil
            //->whereraw($sqls)   
            ->whereraw($sql2)   
            ->union($hospedajesocio)
            ->orderBy('fechaentrada', 'DESC')
            ->orderBy('horaentrada','DESC')
            ->paginate(50);    
        }
        else{
            $hospedajesocio=Ser_Asignacion::select('idasignacion',
                                                    'idcliente',
                                                    'tipocliente',
                                                    'estado',
                                                    'nrasignacion',
                                                    'idimplementos',
                                                    'fechaentrada',
                                                    'horaentrada',
                                                    'fechasalida',
                                                    'horasalida',
                                                    'obs1',
                                                    $rawsocio,
                                                    'ci',
                                                    'ocupantes',
                                                    'cantdias',
                                                    'codambiente',
                                                    'piso',
                                                    'tipo',
                                                    'monto'
                                                    )
                                                ->join('socios','socios.idsocio','ser__asignacions.idcliente')
                                                ->join('par_fuerzas','par_fuerzas.idfuerza','socios.idfuerza')
                                                ->join('par_grados','par_grados.idgrado','socios.idgrado')
                                                ->join('ser__ambientes','ser__ambientes.idambiente','ser__asignacions.idambiente')
                                                ->where('ser__asignacions.estado',0)//solo los liberados
                                                ->where('ser__asignacions.activo',1)
                                                ->where('tipocliente',1);//socio
            
        $hospedajecivil=Ser_Asignacion::select('idasignacion',
                        'idcliente',
                        'tipocliente',
                        'estado',
                        'nrasignacion',
                        'idimplementos',
                        'fechaentrada',
                        'horaentrada',
                        'fechasalida',
                        'horasalida',
                        'obs1',
                        $rawcivil,
                        'ci',
                        'ocupantes',
                        'cantdias',
                        'codambiente',
                        'piso',
                        'tipo',
                        'monto'
                        )
            ->join('ser__civils','ser__civils.idcivil','ser__asignacions.idcliente')
            ->join('ser__ambientes','ser__ambientes.idambiente','ser__asignacions.idambiente')
            ->where('ser__asignacions.estado',0)//solo los liberados
            ->where('ser__asignacions.activo',1)
            //->where('tipocliente',2)//civil
            ->where(function($query) {
                $query->where('tipocliente',2)
                    ->orwhere('tipocliente',3);
                })
            ->union($hospedajesocio)
            ->orderBy('fechaentrada', 'DESC')
            ->orderBy('horaentrada','DESC')
            
            ->paginate(50);    
        }                        
            
        return [
            'pagination' => [
                'total'        => $hospedajecivil->total(),
                'current_page' => $hospedajecivil->currentPage(),
                'per_page'     => $hospedajecivil->perPage(),
                'last_page'    => $hospedajecivil->lastPage(),
                'from'         => $hospedajecivil->firstItem(),
                'to'           => $hospedajecivil->lastItem(),
            ],
            'hospedaje' => $hospedajecivil
        ];
            
    }

    public function verifica(Request $request) {

        $capacidad=Ser_Ambiente::select('capacidad')->where('idambiente',$request->idambiente)->first();
        $verifica=Ser_Asignacion::where('idambiente',$request->idambiente)->where('vigente','1')->sum('ocupantes');

        $total =  $capacidad->capacidad - $verifica;
        return $total;        
    }

    public function traspasoSocio(Request $request) {
        
        // buscamos el establecimiento        
        $estable = Ser_Ambiente::select('idestablecimiento')->where('idambiente',$request->idambiente)->first(); 
        

        $sql1=DB::raw('(select sum(sa2.ocupantes) from ser__asignacions sa2 
                where sa2.idambiente = ser__ambientes.idambiente
                and sa2.vigente =1) as ocupados');
        $sql2=DB::raw('(case when (ser__ambientes.capacidad - (select sum(sa4.ocupantes) from ser__asignacions sa4 where sa4.idambiente = ser__ambientes.idambiente and sa4.vigente =1)) is NULL
                        then ser__ambientes.capacidad else 
                        ser__ambientes.capacidad - (select sum(sa4.ocupantes) from ser__asignacions sa4 where sa4.idambiente = ser__ambientes.idambiente and sa4.vigente =1) end) as disponibles');

        $libres = Ser_Ambiente::select('idambiente','codambiente','tipo','ocupado','capacidad', $sql1, $sql2)
            ->where('ocupado','0')
            ->where('capacidad','>=',$request->camas)
            ->where('idestablecimiento',$estable->idestablecimiento)->get();

        return ['libres'=>$libres];
    }
    
    public function confirmaTraspaso(Request $request) { 

         DB::table('ser__asignacions')
                ->where('idasignacion', $request->idasignacion)
                ->update(['idambiente' => $request->newidambiente]);
    }
    public function fechaHoraSistema(Request $request)
    {
        $fechaactual=date("Y-m-d");
        $horaactual=date("H:i");

        

        return['fechaactual'=>$fechaactual,
                'horaactual'=>$horaactual];
    }

}
