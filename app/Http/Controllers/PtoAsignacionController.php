<?php

namespace App\Http\Controllers;

use App\Pto_Asignacion;
use App\Fil_Oficina;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PtoAsignacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscararray = array();  
        if(!empty($request->buscar)){
            $buscararray = explode(" ",$request->buscar);
        } 
      
        if (sizeof($buscararray)>0) {
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls)){
                    $sqls="(pto__asignacions.codigo like '%".$valor."%' or pto__asignacions.descripcion like '%".$valor."%')";
                }else{
                    $sqls.=" and (pto__asignacions.codigo like '%".$valor."%' or pto__asignacions.descripcion like '%".$valor."%')";
                } 
            }   
            $asignaciones = Pto_Asignacion::join('pto__peis','pto__asignacions.idpei','pto__peis.idpei')
                                    ->select('pto__asignacions.*','pto__peis.gestion')->orderBy('pto__asignacions.idpartida', 'desc')->paginate(10);
        }
        else {
            $raw=DB::raw('(select objgestion from pto__objgestions where pto__objgestions.idobjgestion=pto__asignacions.idobjgestion) as programa');
            $asignaciones = Pto_Asignacion::join('pto__peis','pto__asignacions.idpei','pto__peis.idpei')
                                    ->join('pto__estructuraprogs','pto__asignacions.idestructuraprog','pto__estructuraprogs.idestructuraprog')
                                    ->join('pto__objestrategicos','pto__estructuraprogs.idobjestrategico','pto__objestrategicos.idobjestrategico')                                    
                                    ->select('pto__asignacions.*','pto__peis.gestion','pto__objestrategicos.objestrategico', $raw)
                                    ->orderBy('pto__asignacions.idpei', 'desc')->paginate(10);
            //$asignaciones = Pto_Asignacion::select('*')->orderBy('pto__asignacions.idasignacion', 'desc')->paginate(10);
        }
        return [
            'pagination' => [
                'total'        => $asignaciones->total(),
                'current_page' => $asignaciones->currentPage(),
                'per_page'     => $asignaciones->perPage(),
                'last_page'    => $asignaciones->lastPage(),
                'from'         => $asignaciones->firstItem(),
                'to'           => $asignaciones->lastItem(),
            ],
            'asignaciones' => $asignaciones
        ];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        
        $validator = Validator::make($request->all(), [
            'idobjgestion' => 'unique:pto__asignacions'
        ]);         
        if ($validator->fails()) {
            return ($validator->messages()->first());
        }
       
        $asignacion = new Pto_asignacion();
        $asignacion->idpei = $request->idpei;
        $asignacion->idestructuraprog = $request->idestructuraprog;
        $asignacion->idobjgestion = $request->idobjgestion;
        $asignacion->partidas = $request->partidas;
        $asignacion->reparticiones = $request->reparticiones;
        $asignacion->observacion = $request->observacion;                
        $asignacion->iduser = 'user';        
        $asignacion->activo = '1';
        $asignacion->save();//
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pto_Asignacion  $pto_Asignacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $asignacion = Pto_asignacion::findOrFail($request->idasignacion);
        $asignacion->idpei = $request->idpei;
        $asignacion->idestructuraprog = $request->idestructuraprog;
        $asignacion->idobjgestion = $request->idobjgestion;
        $asignacion->partidas = $request->partidas;
        $asignacion->reparticiones = $request->reparticiones;
        $asignacion->observacion = $request->observacion;                
        $asignacion->iduser = 'user';        
        $asignacion->activo = '1';
        $asignacion->save();//
    }

    public function selectReparticion(Request $request){
        if (!$request->ajax()) return redirect('/');

        $raw=DB::raw('concat(par_municipios.nommunicipio," - ",fil__oficinas.nomoficina) as reparticion');
        $reparticion = Fil_oficina::join('fil__filials','fil__oficinas.idfilial','fil__filials.idfilial')
        ->join('par_municipios','fil__filials.idmunicipio','par_municipios.idmunicipio')
        ->select('fil__oficinas.idoficina','fil__filials.codfilial','par_municipios.nommunicipio','par_municipios.idmunicipio','fil__oficinas.nomoficina',$raw)
        ->orderBy('fil__filials.codfilial', 'asc')->get();
        
        //return response()->json($reparticion);
        return ['reparticion' => $reparticion];
    }

    public function selectAsignacion(Request $request){
        if (!$request->ajax()) return redirect('/');
        
        $repa = explode('-', $request->reparticion);

        $raw=DB::raw('
                (select concat(m.nommunicipio,"-", o.nomoficina)
                from fil__oficinas o, fil__filials f, par_municipios m
                where o.idfilial=f.idfilial
                and f.idmunicipio=m.idmunicipio
                and o.idoficina = '.$repa[0].'
                and m.idmunicipio = '.$repa[1].') as repa
        ');
        
        $reparticion = Pto_asignacion::join('pto__estructuraprogs','pto__asignacions.idestructuraprog','pto__estructuraprogs.idestructuraprog')
        ->join('pto__objestrategicos','pto__estructuraprogs.idobjestrategico','pto__objestrategicos.idobjestrategico')
        ->join('pto__objgestions','pto__asignacions.idobjgestion','pto__objgestions.idobjgestion')
        ->select('pto__asignacions.*','pto__objestrategicos.objestrategico','pto__objgestions.objgestion', $raw)
        ->where('pto__asignacions.idpei','=',$request->idpei)
        ->where('pto__asignacions.reparticiones','like','%'.$request->reparticion.'%')
        ->orderBy('pto__asignacions.idasignacion', 'asc')->get(); //echo $reparticion; exit();
        
        return ['asignacion' => $reparticion];
    }

    public function desactivar(Request $request)
    { 
        if (!$request->ajax()) return redirect('/'); 
        $partida = Pto_asignacion::findOrFail($request->idasignacion);
        $partida->activo = '0';
        $partida->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $partida = Pto_asignacion::findOrFail($request->idasignacion);
        $partida->activo = '1';
        $partida->save();
    }  

}
