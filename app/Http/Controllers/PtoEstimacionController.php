<?php

namespace App\Http\Controllers;

use App\Pto_Estimacion;
use App\Pto_Asignacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PtoEstimacionController extends Controller
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
                    $sqls="(mision like '%".$valor."%' or vision like '%".$valor."%' or gestion like '%".$valor."%')";
                }else{
                    $sqls.=" and (mision like '%".$valor."%' or vision like '%".$valor."%' or gestion like '%".$valor."%')";
                } 
            }   
            $estimacion = Pto_Estimacion::select('*')->orderBy('gestion', 'desc')->whereraw($sqls)->paginate(10);
        }
        else {
            $raw=DB::raw('(select objgestion from pto__objgestions where idobjgestion=pto__asignacions.idobjgestion) as programa');
            $raw1=DB::raw('
                (select e.objestrategico 
                from pto__objestrategicos e,pto__estructuraprogs p, pto__asignacions a, pto__estimacions s
                where s.idasignacion=a.idasignacion
                and a.idestructuraprog=p.idestructuraprog
                and p.idobjestrategico=e.idobjestrategico
                and s.idasignacion=pto__estimacions.idasignacion) as estrategico
            ');
            $raw2=DB::raw('
            (select o.nomoficina from 
            pto__estimacions e, fil__oficinas o
            where e.idoficina=o.idoficina
            and o.idoficina=pto__estimacions.idoficina) as nomoficina
            ');
            $raw3=DB::raw('
            (select m.nommunicipio from 
            pto__estimacions e, par_municipios m
            where e.idmunicipio=m.idmunicipio
            and m.idmunicipio=pto__estimacions.idmunicipio) as nommunicipio
            ');

            $estimacion = Pto_Estimacion::join('pto__asignacions','pto__estimacions.idasignacion','pto__asignacions.idasignacion')
            ->join('pto__peis','pto__asignacions.idpei','pto__peis.idpei')
            ->select('pto__estimacions.*','pto__peis.gestion','pto__peis.idpei', $raw, $raw1,$raw2,$raw3)
            ->orderBy('pto__estimacions.idestimacion', 'desc')
            ->paginate(10);  //toSql(); echo $estimacion; exit();
        }
        return [
            'pagination' => [
                'total'        => $estimacion->total(),
                'current_page' => $estimacion->currentPage(),
                'per_page'     => $estimacion->perPage(),
                'last_page'    => $estimacion->lastPage(),
                'from'         => $estimacion->firstItem(),
                'to'           => $estimacion->lastItem(),
            ],
            'estimacion' => $estimacion
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
        
        // $validator = Validator::make($request->all(), [
        //     'idobjgestion' => 'unique:pto__asignacions'
        // ]);         
        // if ($validator->fails()) {
        //     return ($validator->messages()->first());
        // }
        
        //para obtener las reparticiones en base al idasignacion
        $asignacion = Pto_Asignacion::select('reparticiones')->where('idasignacion','=',$request->idasignacion)->get();
        foreach ($asignacion as $asi) {
            echo $asi;  
            $repa =  explode('-',$asi);
        }

        $estimacion = new Pto_estimacion();
        $estimacion->idasignacion = $request->idasignacion;
        $estimacion->monto = $request->monto;        
        $estimacion->idoficina = $repa[0];
        $estimacion->idmunicipio = $repa[1];
        $estimacion->descripcion = $request->descripcion;
        $estimacion->iduser = 'user';        
        $estimacion->activo = '1'; 
        $estimacion->save();//
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pto_Estimacion  $pto_Estimacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $asignacion = Pto_estimacion::findOrFail($request->idasignacion); 
        $repa =  explode('-',$request->reparticion); 

        $asignacion->idasignacion = $request->idasignacion;
        $asignacion->monto = $request->monto;        
        $asignacion->idoficina = $repa[0];
        $asignacion->idmunicipio = $repa[1];
        $asignacion->descripcion = $request->descripcion;
        $asignacion->iduser = 'user';        
        $asignacion->activo = '1';
        $asignacion->save();//
    }
}
