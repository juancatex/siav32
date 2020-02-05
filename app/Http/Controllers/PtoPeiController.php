<?php

namespace App\Http\Controllers;

use App\Pto_Pei;
use App\Pto_Objestrategico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PtoPeiController extends Controller
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
            $peis = Pto_Pei::select('*')->orderBy('gestion', 'desc')->whereraw($sqls)->paginate(10);
        }
        else {
            $peis = Pto_Pei::select('*')->orderBy('gestion', 'desc')->paginate(10);
        }
        return [
            'pagination' => [
                'total'        => $peis->total(),
                'current_page' => $peis->currentPage(),
                'per_page'     => $peis->perPage(),
                'last_page'    => $peis->lastPage(),
                'from'         => $peis->firstItem(),
                'to'           => $peis->lastItem(),
            ],
            'peis' => $peis
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
        $validator = Validator::make($request->all(), [
            'gestion' => 'unique:pto__peis'
        ]);         
        if ($validator->fails()) {
            return ($validator->messages()->first());
        }
        
        if (!$request->ajax()) return redirect('/');      
        $pei = new Pto_Pei();
        $pei->gestion = $request->gestion;
        $pei->mision = $request->mision;
        $pei->vision = $request->vision;
        $pei->descripcion = $request->descripcion;        
        $pei->activo = '1';
        $pei->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pto_Pei  $pto_Pei
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $pei = Pto_pei::findOrFail($request->idpei);
        //$pei->gestion = $request->gestion;
        $pei->mision = $request->mision;
        $pei->vision = $request->vision;
        $pei->descripcion = $request->descripcion;
        $pei->activo = '1';
        $pei->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $pei = Pto_pei::findOrFail($request->idpei);
        $pei->activo = '0';
        $pei->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $pei = Pto_pei::findOrFail($request->idpei);
        $pei->activo = '1';
        $pei->save();
    }

    public function selectPei(Request $request){
        if (!$request->ajax()) return redirect('/');
        $gestionpei = Pto_pei::where('activo','=','1')
        ->select('*')->orderBy('gestion', 'asc')->get();
        return ['gestionpei' => $gestionpei];
    }

    public function selectObjestrategico(Request $request){
        if (!$request->ajax()) return redirect('/');
        $objestrategico = Pto_objestrategico::where('activo','=','1')->where('idpei','=',$request->idpei)
        ->select('*')->orderBy('idobjestrategico', 'asc')->get();
        return ['objestrategico' => $objestrategico];
    }

}
