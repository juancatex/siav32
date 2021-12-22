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
        //$nopiso=

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
                                    ->where('ser__ambientes.idestablecimiento',$idestablecimiento);
                                    if($request->nopiso==false)
                                    {
                                        $asignacion=$asignacion->where('ser__ambientes.piso',$piso)
                                                                ->orderby('codambiente','asc');
                                    }
                                    else
                                        $asignacion=$asignacion->orderby('codambiente','desc');
                                    
                                    $asignacion=$asignacion->get();

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
                                                'apaterno',
                                                'descuento',
                                                'montosindescuento',
                                                'monto as montocondescuento'
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
                                                    'apaterno',
                                                    'descuento',
                                                    'montosindescuento',
                                                    'monto as montocondescuento'

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
    public function registrartraspaso(Request $request)
    {
        $fecha = date('Y-m-d H:i:s');
        $idasignacion=$request->idasignacion;
        $asignacion=Ser_Asignacion::findOrFail($idasignacion);
        $asignacion->idambiente=$request->idambiente;
        $asignacion->u_traspaso=Auth::id();
        $asignacion->fechatraspaso=$fecha;
        $asignacion->save();           
    }
    public function reporteingreso(Request $request)
    {
        $idasignacion=$request->idasignacion;
        $tiposocio=$request->tiposocio;
        $rawsocio=DB::raw('concat(nomgrado," ",apaterno," ",amaterno," ",nombre," - ",nomfuerza) as nombres');
        $rawsocio2=DB::raw('concat(nomgrado," ",socios.apaterno," ",socios.amaterno," ",socios.nombre," - ",nomfuerza) as nombresocio'); 
        $rawcivil=DB::raw('concat(ser__civils.apaterno," ",ser__civils.amaterno," ",ser__civils.nombre) as nombres');
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
                                        
            // $hospedaje=Ser_Asignacion::select('idasignacion',
            //                                         'idcliente',
            //                                         'tipocliente',
            //                                         'estado',
            //                                         'nrasignacion',
            //                                         'idimplementos',
            //                                         'fechaentrada',
            //                                         'horaentrada',
            //                                         'fechasalida',
            //                                         'obs1',
            //                                         $rawcivil,
            //                                         'ci',
            //                                         'ocupantes',
            //                                         'codambiente',
            //                                         'piso',
            //                                         'tarifasocio',
            //                                         'tarifareal',
            //                                         )
            //                             ->join('ser__civils','ser__civils.idcivil','ser__asignacions.idcliente')
            //                             ->join('ser__ambientes','ser__ambientes.idambiente','ser__asignacions.idambiente')
            //                             ->where('idasignacion',$idasignacion)
            //                             ->get();
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
                                                    $rawsocio2,
                                                    'ser__civils.ci',
                                                    'ocupantes',
                                                    'codambiente',
                                                    'piso',
                                                    'tarifasocio',
                                                    'tarifareal',
                                                    'monto',
                                                    'cantdias',
                                                    'numerofactura',
                                                    'razonsocial',
                                                    'nit',
                                                    'montosindescuento',
                                                    'descuento',
                                                    'parentezco'
                                                    )
                                        ->join('ser__civils','ser__civils.idcivil','ser__asignacions.idcliente')
                                        ->join('ser__ambientes','ser__ambientes.idambiente','ser__asignacions.idambiente')
                                        ->leftjoin('con__facturas','con__facturas.idfactura','ser__asignacions.idfactura')
                                        ->leftjoin('socios','socios.idsocio','ser__civils.idsocio')
                                        ->leftjoin('par_fuerzas','par_fuerzas.idfuerza','socios.idfuerza')
                                        ->leftjoin('par_grados','par_grados.idgrado','socios.idgrado')
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
        $fecha = date('Y-m-d H:i:s');
        $idasignacion=$request->idasignacion;
        $asignacion=Ser_Asignacion::findOrFail($idasignacion);
        $asignacion->montosindescuento=$request->monto;
        $asignacion->fechasoldescuento=$fecha;
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
                                                'ser__asignacions.idimplementos',
                                                'fechaentrada',
                                                'horaentrada',
                                                'fechasalida',
                                                $rawsocio,
                                                'ci',
                                                'piso',
                                                'tarifasocio',
                                                'tarifareal',
                                                'numpapeleta',
                                                'fechasoldescuento',
                                                'fechaaprodescuento',
                                                'montosindescuento',
                                                'descuento',
                                                'monto',
                                                'ser__ambientes.codambiente',
                                                'ser__ambientes.tipo',
                                                'ser__establecimientos.nomestablecimiento'
                                                )
                                        ->join('socios','socios.idsocio','ser__asignacions.idcliente')
                                        ->join('par_fuerzas','par_fuerzas.idfuerza','socios.idfuerza')
                                        ->join('par_grados','par_grados.idgrado','socios.idgrado')
                                        ->join('ser__ambientes','ser__ambientes.idambiente','ser__asignacions.idambiente')
                                        ->join('ser__establecimientos','ser__establecimientos.idestablecimiento','ser__ambientes.idestablecimiento')
                                        ->where('descuento','<>',0)
                                        ->orderby('fechasoldescuento','desc')
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
        $fecha = date('Y-m-d H:i:s');
        $idasignacion=$request->idasignacion;
        $asignacion=Ser_Asignacion::findOrFail($idasignacion);
        $asignacion->monto=$request->monto;
        $asignacion->obs2=$request->obs2;
        $asignacion->descuento=2;
        $asignacion->u_autorizador=Auth::id();
        $asignacion->fechaaprodescuento=$fecha;
        $asignacion->save();   
    }
    public function reportediario(Request $request)
    {

        //$fechainicio=strtotime($request->fechadiario);
        $fechainicio = date("Y-m-d", strtotime($request->fechadiario));

        //echo $fechainicio;
        //$fechafin=$request->fechaout;
        $rawsocio=DB::raw('concat(nomgrado," ",apaterno," ",amaterno," ",nombre) as nombres');
        $rawcivil=DB::raw('concat(ser__civils.apaterno," ",ser__civils.amaterno," ",ser__civils.nombre) as nombres');
        $rawsocio2=DB::raw('concat(nomgrado," ",socios.apaterno," ",socios.amaterno," ",socios.nombre," - ",nomfuerza) as nombresocio'); 


        
       /*  $resultado=Ser_Asignacion::where(DB::raw('fechasalida'),'=',$fechainicio)->get();
        dd($resultado); */


        $hospedajesocio=Ser_Asignacion::select('idasignacion',
                                                    'idcliente',
                                                    'tipocliente',
                                                    'estado',
                                                    'nrasignacion',
                                                    'fechaentrada',
                                                    'horaentrada',
                                                    'fechasalida',
                                                    'horasalida',
                                                    'obs1',
                                                    $rawsocio,
                                                    $rawsocio2,
                                                    'nomfuerza',
                                                    'ci',
                                                    'ocupantes',
                                                    'cantdias',
                                                    'codambiente',
                                                    'piso',
                                                    'tipo',
                                                    'monto',
                                                    'numerofactura',
                                                    DB::raw('1 as parentezco')
                                                    )
                                                ->join('socios','socios.idsocio','ser__asignacions.idcliente')
                                                ->join('par_fuerzas','par_fuerzas.idfuerza','socios.idfuerza')
                                                ->join('par_grados','par_grados.idgrado','socios.idgrado')
                                                ->join('ser__ambientes','ser__ambientes.idambiente','ser__asignacions.idambiente')
                                                ->leftjoin('con__facturas','con__facturas.idfactura','ser__asignacions.idfactura')
                                                
                                                ->where('ser__asignacions.estado',2)//solo los ocupados
                                                ->where('ser__asignacions.activo',1)
                                                ->where('tipocliente',1)//socio
                                                ->where(DB::raw('date(fechaentrada)'),'<=',$fechainicio);
                                                //->where(DB::raw('date(fechaentrada)'),'<=',$fechainicio)
                                                //->get();
        //dd($hospedajesocio);

        $hospedados=Ser_Asignacion::select('idasignacion',
                        'idcliente',
                        'tipocliente',
                        'estado',
                        'nrasignacion',
                        'fechaentrada',
                        'horaentrada',
                        'fechasalida',
                        'horasalida',
                        'obs1',
                        $rawcivil,
                        $rawsocio2,
                        DB::raw('1 as nomfuerza'),
                        'ser__civils.ci',
                        'ocupantes',
                        'cantdias',
                        'codambiente',
                        'piso',
                        'tipo',
                        'monto',
                        'numerofactura',
                        'parentezco'
                        )
            ->join('ser__civils','ser__civils.idcivil','ser__asignacions.idcliente')
            ->join('ser__ambientes','ser__ambientes.idambiente','ser__asignacions.idambiente')
            ->leftjoin('con__facturas','con__facturas.idfactura','ser__asignacions.idfactura')
            ->leftjoin('socios','socios.idsocio','ser__civils.idsocio')
            ->leftjoin('par_fuerzas','par_fuerzas.idfuerza','socios.idfuerza')
            ->leftjoin('par_grados','par_grados.idgrado','socios.idgrado')
            ->where('ser__asignacions.estado',2)//solo los ocupados
            ->where('ser__asignacions.activo',1)
            //->where('tipocliente',2)//civil
            ->where(function($query) {
                $query->where('tipocliente',2)
                    ->orwhere('tipocliente',3);
                })
            ->where(DB::raw('date(fechaentrada)'),'<=',$fechainicio)
            ->union($hospedajesocio)
            ->orderBy('fechaentrada', 'ASC')
            ->orderBy('horaentrada','ASC')
            ->get();  
        
        //$hospedados;

        $salientessocio=Ser_Asignacion::select('idasignacion',
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
                                                    $rawsocio2,
                                                    'nomfuerza',
                                                    'descuento',
                                                    'ci',
                                                    'ocupantes',
                                                    'cantdias',
                                                    'codambiente',
                                                    'piso',
                                                    'tipo',
                                                    'monto',
                                                    'numerofactura',
                                                    DB::raw('1 as parentezco')
                                                    )
                                                ->join('socios','socios.idsocio','ser__asignacions.idcliente')
                                                ->join('par_fuerzas','par_fuerzas.idfuerza','socios.idfuerza')
                                                ->join('par_grados','par_grados.idgrado','socios.idgrado')
                                                ->join('ser__ambientes','ser__ambientes.idambiente','ser__asignacions.idambiente')
                                                ->leftjoin('con__facturas','con__facturas.idfactura','ser__asignacions.idfactura')
                                                ->where('ser__asignacions.estado',0)//solo los liberados
                                                ->where('ser__asignacions.activo',1)
                                                ->where('tipocliente',1)//socio
                                                ->where(DB::raw('date(fechasalida)'),'=',$fechainicio);
                                                
       //dd($salientessocio);   
        $salientescivils=Ser_Asignacion::select('idasignacion',
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
                        $rawsocio2,
                        DB::raw('1 as nomfuerza'),
                        'descuento',
                        'ser__civils.ci',
                        'ocupantes',
                        'cantdias',
                        'codambiente',
                        'piso',
                        'tipo',
                        'monto',
                        'numerofactura',
                        'parentezco'
                        )
            ->join('ser__civils','ser__civils.idcivil','ser__asignacions.idcliente')
            ->join('ser__ambientes','ser__ambientes.idambiente','ser__asignacions.idambiente')
            ->leftjoin('con__facturas','con__facturas.idfactura','ser__asignacions.idfactura')
            ->leftjoin('socios','socios.idsocio','ser__civils.idsocio')
            ->leftjoin('par_fuerzas','par_fuerzas.idfuerza','socios.idfuerza')
            ->leftjoin('par_grados','par_grados.idgrado','socios.idgrado')
            ->where('ser__asignacions.estado',0)//solo los liberados
            ->where('ser__asignacions.activo',1)
            //->where('tipocliente',2)//civil
            ->where(function($query) {
                $query->where('tipocliente',2)
                    ->orwhere('tipocliente',3);
                })
            ->where(DB::raw('date(fechasalida)'),'=',$fechainicio)
            ->union($salientessocio)
            ->orderBy('horasalida','ASC')
            ->get(); 
        
            //$salientescivils;

            /* return ['hospedados'=>$hospedados,
                    'salientes'=>$salientescivils]; */
            $logo = Storage::path('fotos/cabecera_casacomunitaria.PNG');
            $logo64 = base64_encode(Storage::get('fotos/cabecera_casacomunitaria.PNG')); 

            /* return view('reportes/servicios/reportediario')->with(['hospedados'=>$hospedados,
                                            'salientes'=>$salientescivils,
                                            'fecha'=>$fechainicio,
                                            'foto'=>'data:'.mime_content_type($logo) . ';base64,' . $logo64]);  */


            $pdf = PDF::loadView('reportes/servicios/reportediario', ['hospedados'=>$hospedados,
                                                                        'salientes'=>$salientescivils,
                                                                        'fecha'=>$fechainicio,
                                            'foto'=>'data:'.mime_content_type($logo) . ';base64,' . $logo64]); 
            return $pdf->stream('reportediario.pdf'); 





    }
    public function reportemensual(Request $request)
    {
          //$fechainicio=strtotime($request->fechadiario);
          $fechainicio = date("Y-m-d", strtotime($request->fechainicio));
          $fechafin = date("Y-m-d", strtotime($request->fechafin));

          //echo $fechainicio;
          //$fechafin=$request->fechaout;
          $rawsocio=DB::raw('concat(nomgrado," ",apaterno," ",amaterno," ",nombre) as nombres');
          $rawcivil=DB::raw('concat(ser__civils.apaterno," ",ser__civils.amaterno," ",ser__civils.nombre) as nombres');
          $rawsocio2=DB::raw('concat(nomgrado," ",socios.apaterno," ",socios.amaterno," ",socios.nombre," - ",nomfuerza) as nombresocio'); 

          $salientessocio=Ser_Asignacion::select('idasignacion',
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
                                                      $rawsocio2,
                                                      'nomfuerza',
                                                      'descuento',
                                                      'ci',
                                                      'ocupantes',
                                                      'cantdias',
                                                      'codambiente',
                                                      'piso',
                                                      'tipo',
                                                      'monto',
                                                      'numerofactura',
                                                      DB::raw('1 as parentezco')
                                                      )
                                                  ->join('socios','socios.idsocio','ser__asignacions.idcliente')
                                                  ->join('par_fuerzas','par_fuerzas.idfuerza','socios.idfuerza')
                                                  ->join('par_grados','par_grados.idgrado','socios.idgrado')
                                                  ->join('ser__ambientes','ser__ambientes.idambiente','ser__asignacions.idambiente')
                                                  ->leftjoin('con__facturas','con__facturas.idfactura','ser__asignacions.idfactura')
                                                  ->where('ser__asignacions.estado',0)//solo los liberados
                                                  ->where('ser__asignacions.activo',1)
                                                  ->where('tipocliente',1)//socio
                                                  //->where(DB::raw('date(fechasalida)'),'=',$fechainicio);
                                                  ->whereBetween(DB::raw('date(fechasalida)'), [$fechainicio, $fechafin]); 
                                                  
         //dd($salientessocio);   
          $salientescivils=Ser_Asignacion::select('idasignacion',
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
                          $rawsocio2,
                          DB::raw('1 as nomfuerza'),
                          'descuento',
                          'ser__civils.ci',
                          'ocupantes',
                          'cantdias',
                          'codambiente',
                          'piso',
                          'tipo',
                          'monto',
                          'numerofactura',
                          'parentezco'
                          )
              ->join('ser__civils','ser__civils.idcivil','ser__asignacions.idcliente')
              ->join('ser__ambientes','ser__ambientes.idambiente','ser__asignacions.idambiente')
              ->leftjoin('con__facturas','con__facturas.idfactura','ser__asignacions.idfactura')
              ->leftjoin('socios','socios.idsocio','ser__civils.idsocio')
              ->leftjoin('par_fuerzas','par_fuerzas.idfuerza','socios.idfuerza')
              ->leftjoin('par_grados','par_grados.idgrado','socios.idgrado')
              ->where('ser__asignacions.estado',0)//solo los liberados
              ->where('ser__asignacions.activo',1)
              //->where('tipocliente',2)//civil
              ->where(function($query) {
                  $query->where('tipocliente',2)
                      ->orwhere('tipocliente',3);
                  })
                  ->whereBetween(DB::raw('date(fechasalida)'), [$fechainicio, $fechafin])
              ->union($salientessocio)
              ->orderBy('fechasalida','ASC')
              ->orderBy('horasalida','ASC')
              ->get(); 
          
              //$salientescivils;
  
              /* return ['salientes'=>$salientescivils,
                        'fechainicio'=>$fechainicio,
                        'fechafin'=>$fechafin]; */
              $logo = Storage::path('fotos/cabecera_casacomunitaria.PNG');
              $logo64 = base64_encode(Storage::get('fotos/cabecera_casacomunitaria.PNG')); 
  
              /* return view('reportes/servicios/reportediario')->with(['hospedados'=>$hospedados,
                                              'salientes'=>$salientescivils,
                                              'fecha'=>$fechainicio,
                                              'foto'=>'data:'.mime_content_type($logo) . ';base64,' . $logo64]);  */
  
  
            $pdf = PDF::loadView('reportes/servicios/reportemensual', ['salientes'=>$salientescivils,
                                'fechainicio'=>$fechainicio,
                                'fechafin'=>$fechafin,
                                'foto'=>'data:'.mime_content_type($logo) . ';base64,' . $logo64]); 
            return $pdf->stream('reportemensual.pdf'); 
  
        

    }
    public function reporteporsocio(Request $request)
    {
           
          $rawsocio=DB::raw('concat(nomgrado," ",apaterno," ",amaterno," ",nombre) as nombres'); 
          $rawsocio2=DB::raw('concat(nomgrado," ",socios.apaterno," ",socios.amaterno," ",socios.nombre," - ",nomfuerza) as nombresocio'); 

          $salientessocio=Ser_Asignacion::select('idasignacion',
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
                                                      $rawsocio2,
                                                      'nomfuerza',
                                                      'descuento',
                                                      'ci',
                                                      'ocupantes',
                                                      'cantdias',
                                                      'codambiente',
                                                      'piso',
                                                      'tipo',
                                                      'monto',
                                                      'numerofactura',
                                                      DB::raw('1 as parentezco'),
                                                      DB::raw('(select username from  adm__users aa where aa.id=ser__asignacions.u_registro) as uentrada'),
                                                      DB::raw('(select username from  adm__users aa where aa.id=ser__asignacions.u_salida) as usalida')
                                                      )
                                                  ->join('socios','socios.idsocio','ser__asignacions.idcliente')
                                                  ->join('par_fuerzas','par_fuerzas.idfuerza','socios.idfuerza')
                                                  ->join('par_grados','par_grados.idgrado','socios.idgrado')
                                                  ->join('ser__ambientes','ser__ambientes.idambiente','ser__asignacions.idambiente') 
                                                  ->leftjoin('con__facturas','con__facturas.idfactura','ser__asignacions.idfactura') 
                                                //   ->where('ser__asignacions.estado',0)//solo los liberados
                                                  ->where('ser__asignacions.activo',1)
                                                  ->where('tipocliente',1)
                                                  ->where('ser__asignacions.idcliente',$request->idsocio)
                                                  ->orderBy('fechasalida','ASC')
                                                  ->orderBy('idasignacion','ASC')//socio
                                                  ->get(); 
                                              
         
              $socioco=Socio::select(DB::raw('concat(IF(socios.idfuerza=5,par_grados.abrev, par_grados.nomgrado)," ",socios.nombre," ",socios.apaterno," ",socios.amaterno) as nombrescompleto'))
                                    ->join('par_grados','socios.idgrado','par_grados.idgrado') 
                                    ->where('socios.idsocio',$request->idsocio) 
                                    ->get()->first();
                                    
              $logo = Storage::path('fotos/cabecera_casacomunitaria.PNG');
              $logo64 = base64_encode(Storage::get('fotos/cabecera_casacomunitaria.PNG')); 
   
            $pdf = PDF::loadView('reportes/servicios/reporteporsocio', ['data'=>$salientessocio,
                                'nombrec'=>$socioco->nombrescompleto,
                                'foto'=>'data:'.mime_content_type($logo) . ';base64,' . $logo64]); 
            return $pdf->stream('reporteporsocio.pdf'); 
  
        

    }
    public function reportesalida(Request $request)
    {
        $idasignacion=$request->idasignacion;
        $tiposocio=$request->tiposocio;
        $rawsocio=DB::raw('concat(nomgrado," ",apaterno," ",amaterno," ",nombre," - ",nomfuerza) as nombres');
        $rawsocio2=DB::raw('concat(nomgrado," ",socios.apaterno," ",socios.amaterno," ",socios.nombre," - ",nomfuerza) as nombresocio');
        $rawcivil=DB::raw('concat(ser__civils.apaterno," ",ser__civils.amaterno," ",ser__civils.nombre) as nombres');
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
                                                'monto',
                                                'montosindescuento',
                                                'descuento'


                                                )
                                        ->join('socios','socios.idsocio','ser__asignacions.idcliente')
                                        ->join('par_fuerzas','par_fuerzas.idfuerza','socios.idfuerza')
                                        ->join('par_grados','par_grados.idgrado','socios.idgrado')
                                        ->join('ser__ambientes','ser__ambientes.idambiente','ser__asignacions.idambiente')
                                        ->leftjoin('con__facturas','con__facturas.idfactura','ser__asignacions.idfactura')
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
                                                    $rawsocio2,
                                                    'ser__civils.ci',
                                                    'ocupantes',
                                                    'codambiente',
                                                    'piso',
                                                    'tarifasocio',
                                                    'tarifareal',
                                                    'monto',
                                                    'cantdias',
                                                    'numerofactura',
                                                    'razonsocial',
                                                    'nit',
                                                    'montosindescuento',
                                                    'descuento',
                                                    'parentezco'
                                                    )
                                        ->join('ser__civils','ser__civils.idcivil','ser__asignacions.idcliente')
                                        ->join('ser__ambientes','ser__ambientes.idambiente','ser__asignacions.idambiente')
                                        ->leftjoin('con__facturas','con__facturas.idfactura','ser__asignacions.idfactura')
                                        ->leftjoin('socios','socios.idsocio','ser__civils.idsocio')
                                        ->leftjoin('par_fuerzas','par_fuerzas.idfuerza','socios.idfuerza')
                                        ->leftjoin('par_grados','par_grados.idgrado','socios.idgrado')
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
        $asignacion->obs2=$asignacion->obs2." ".$request->observaciones;
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
