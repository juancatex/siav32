<?php

namespace App\Http\Controllers;

use App\Pto_Estructuraprog;
use App\Pto_Objgestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PtoEstructuraprogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $buscararray = array();  
        if(!empty($request->buscar)){
            $buscararray = explode(" ",$request->buscar);
        }
           
        if (sizeof($buscararray)>0) {
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls)){
                    
                    $sqls="(pto__objestrategicos.objestrategico like '%".$valor."%')";
                }else{
                    $sqls.=" and (pto__objestrategicos.objestrategico like '%".$valor."%')";
                } 
            } 
            
            if($request->idpei!='')   
            $estructuraprog = Pto_Estructuraprog::
                join('pto__peis','pto__estructuraprogs.idpei','pto__peis.idpei')
                ->join('pto__objestrategicos','pto__estructuraprogs.idobjestrategico','pto__objestrategicos.idobjestrategico')
                ->select('pto__estructuraprogs.*','pto__peis.gestion','pto__objestrategicos.objestrategico')
                ->orderBy('pto__estructuraprogs.idestructuraprog', 'desc')
                ->where('pto__peis.activo','=',1)
                ->whereraw($sqls)
                ->where('pto__estructuraprogs.idpei','=',$request->idpei)
                ->paginate(10); 
            else  
                $estructuraprog = Pto_Estructuraprog::join('pto__peis','pto__estructuraprogs.idpei','pto__peis.idpei')
                ->join('pto__objestrategicos','pto__estructuraprogs.idobjestrategico','pto__objestrategicos.idobjestrategico')
                ->select('pto__estructuraprogs.*','pto__peis.gestion','pto__objestrategicos.objestrategico')
                ->orderBy('pto__estructuraprogs.idestructuraprog', 'desc')
                ->whereraw($sqls)
                ->where('pto__peis.activo','=',1)
                ->paginate(10); 
        }
        else {
            if($request->idpei!='')
                $estructuraprog = Pto_Estructuraprog::
                join('pto__peis','pto__estructuraprogs.idpei','pto__peis.idpei')
                ->join('pto__objestrategicos','pto__estructuraprogs.idobjestrategico','pto__objestrategicos.idobjestrategico')
                ->select('pto__estructuraprogs.*','pto__peis.gestion','pto__objestrategicos.objestrategico')
                ->orderBy('pto__estructuraprogs.idestructuraprog', 'desc')
                ->where('pto__estructuraprogs.idpei','=',$request->idpei)
                ->where('pto__peis.activo','=',1)
                ->paginate(10);
            else 
                $estructuraprog = Pto_Estructuraprog::
                join('pto__peis','pto__estructuraprogs.idpei','pto__peis.idpei')
                ->join('pto__objestrategicos','pto__estructuraprogs.idobjestrategico','pto__objestrategicos.idobjestrategico')
                ->select('pto__estructuraprogs.*','pto__peis.gestion','pto__objestrategicos.objestrategico')
                ->orderBy('pto__estructuraprogs.idestructuraprog', 'desc')
                ->where('pto__peis.activo','=',1)
                ->paginate(10);            
        }  
        return [
            'pagination' => [
                'total'        => $estructuraprog->total(),
                'current_page' => $estructuraprog->currentPage(),
                'per_page'     => $estructuraprog->perPage(),
                'last_page'    => $estructuraprog->lastPage(),
                'from'         => $estructuraprog->firstItem(),
                'to'           => $estructuraprog->lastItem(),
            ],
            'estructuraprog' => $estructuraprog
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
            'idobjestrategico' => 'unique:pto__estructuraprogs'
        ]);         
        if ($validator->fails()) {
            return ($validator->messages()->first());
        }


        $estructuraprog = new Pto_estructuraprog();
        $estructuraprog->idpei = $request->idpei;
        $estructuraprog->idobjestrategico = $request->idobjestrategico;
        $estructuraprog->activo = '1';
        $estructuraprog->save();

        //guardando los objetivos de gestion
        
        for ($i=0; $i<count($request->objgestion); $i++) {
            $objgestion = new Pto_objgestion();                    
            $objgestion->idestructuraprog = $estructuraprog->idestructuraprog;
            $objgestion->objgestion = $request->objgestion[$i]['objgestion'];
            $objgestion->resultado = $request->objgestion[$i]['resultado'];
            $objgestion->idusuario = 'user';
            $objgestion->activo = '1';
            $objgestion->save();
        }         
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pto_Estructuraprog  $pto_Estructuraprog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $estructuraprog = Pto_estructuraprog::findOrFail($request->idestructuraprog);
        $estructuraprog->idpei = $request->idpei;
        $estructuraprog->idobjestrategico = $request->idobjestrategico;
        $estructuraprog->activo = '1';
        $estructuraprog->save();

        
            $objgestion = Pto_objgestion::where('idestructuraprog','=',$request->idestructuraprog);
            $objgestion->delete();

            for ($i=0; $i<count($request->objgestion); $i++) {
                $objgestion = new Pto_objgestion();                    
                $objgestion->idestructuraprog = $estructuraprog->idestructuraprog;
                $objgestion->objgestion = $request->objgestion[$i]['objgestion'];
                $objgestion->resultado = $request->objgestion[$i]['resultado'];
                $objgestion->idusuario = 'user';
                $objgestion->activo = '1';
                $objgestion->save();
            } 

    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $estructuraprog_id = Pto_estructuraprog::findOrFail($request->idestructuraprog);
        $estructuraprog_id->activo = '0';
        $estructuraprog_id->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $estructuraprog_id = Pto_estructuraprog::findOrFail($request->idestructuraprog);
        $estructuraprog_id->activo = '1';
        $estructuraprog_id->save();
    }    

    public function selectgestion(Request $request){ 
        if (!$request->ajax()) return redirect('/');

        $estructuraprog = Pto_estructuraprog::join('pto__objestrategicos','pto__estructuraprogs.idobjestrategico','pto__objestrategicos.idobjestrategico')
        ->where('pto__estructuraprogs.idpei','=',$request->idpei)
        ->select('pto__estructuraprogs.*','pto__objestrategicos.objestrategico')
        ->where('pto__objestrategicos.activo','=',1)
        ->orderBy('pto__estructuraprogs.idestructuraprog', 'asc')->get();
        return ['estructuraprog' => $estructuraprog];
    }

}
