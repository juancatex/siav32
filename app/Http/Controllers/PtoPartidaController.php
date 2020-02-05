<?php

namespace App\Http\Controllers;

use App\Pto_Partida;
use App\Pto_PartidaN1;
use App\Pto_PartidaN2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PtoPartidaController extends Controller
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
                    $sqls="(pto__partida_n2_s.codigon2 like '%".$valor."%' or pto__partida_n2_s.nombren2 like '%".$valor."%')";
                }else{
                    $sqls.=" and (pto__partida_n2_s.codigon2 like '%".$valor."%' or pto__partida_n2_s.nombren2 like '%".$valor."%')";
                } 
            }   
            $partidas = Pto_Partida::join('pto__peis','pto__partidas.idpei','pto__peis.idpei')
                        ->join('pto__partida_n1_s','pto__partidas.idpartida','pto__partida_n1_s.idpartida')
                        ->join('pto__partida_n2_s','pto__partida_n1_s.idpartidan1','pto__partida_n2_s.idpartidan1')
                        ->select('pto__partidas.*','pto__peis.gestion', 'pto__partida_n1_s.*', 'pto__partida_n2_s.*')->orderBy('pto__partida_n2_s.codigon2', 'asc')->whereraw($sqls)->paginate(10);
        }
        else {
                $partidas = Pto_Partida::join('pto__peis','pto__partidas.idpei','pto__peis.idpei')
                        ->join('pto__partida_n1_s','pto__partidas.idpartida','pto__partida_n1_s.idpartida')
                        ->join('pto__partida_n2_s','pto__partida_n1_s.idpartidan1','pto__partida_n2_s.idpartidan1')
                        ->select('pto__partidas.*','pto__peis.gestion', 'pto__partida_n1_s.*', 'pto__partida_n2_s.*')->orderBy('pto__partida_n2_s.codigon2', 'asc')->paginate(10);
        }

        //sacando el arbol y asignando a un vector cuando se recupera una partida especifica       
        $niveles = array();
        if ($request->idpartida) {            
            $a=0; 

            $arbol1 = Pto_Partida::select('idpartida')->where('idpartida','=',$request->idpartida)->get();
            foreach ($arbol1 as $padre) {
            //echo ' * ' . $padre->idpartida;            
            $hijo1 = Pto_PartidaN1::select('idpartidan1','codigon1','nombren1')->where('idpartida','=',$padre->idpartida)->get();

                foreach ($hijo1 as $h1) {
                    //echo ' ** ' . $h1->idpartidan1.'-'.$h1->nombren1;
                    $niveles[$a]['name'] = $h1->nombren1;
                    $niveles[$a]['codigo'] = $h1->codigon1;
                    $hijo2 = Pto_PartidaN2::select('idpartidan2','nombren2','codigon2','monto','tipo')->where('idpartidan1','=',$h1->idpartidan1)->get();

                    if (count($hijo2)==0) {
                        $niveles[$a]['children']=[];
                    }
                    $b=0;
                    foreach ($hijo2 as $h2) {
                        //echo ' *** ' . $h2->idpartidan2.'-'.$h2->nombren2;
                        $niveles[$a]['children'][$b]['name'] = $h2->nombren2;
                        $niveles[$a]['children'][$b]['codigo'] = $h2->codigon2;
                        $niveles[$a]['children'][$b]['monto'] = $h2->monto;
                        $niveles[$a]['children'][$b]['tipo'] = $h2->tipo;
                        $b++;
                    }
                    $a++;
                }            
            }
            //print_r($niveles); 
        }

        return [
            'pagination' => [
                'total'        => $partidas->total(),
                'current_page' => $partidas->currentPage(),
                'per_page'     => $partidas->perPage(),
                'last_page'    => $partidas->lastPage(),
                'from'         => $partidas->firstItem(),
                'to'           => $partidas->lastItem(),
            ],
            'partidas' => $partidas, 'niveles'=>$niveles
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
            'codigo' => 'unique:pto__partidas'
        ]);         
        if ($validator->fails()) {
            return ($validator->messages()->first());
        }
        
        $partida = new Pto_partida();
        $partida->idpei = $request->idpei;
        $partida->codigo = $request->codigo;
        $partida->nompartida = $request->nompartida;
        $partida->descripcion = $request->descripcion;
        $partida->ptoinicial = $request->ptoinicial;        
        $partida->ptototal = $request->ptototal;        
        $partida->valida1 = $request->valida1;        
        $partida->valida2 = $request->valida2;        
           
        $partida->user = 'user';        
        $partida->activo = '1';
        $partida->save();
                
        $level = $request->niveles;
        $partidaid = $partida->idpartida;        
        
        for ($x1=0; $x1<count($level); $x1++) {
            $partidan1 = new Pto_partidaN1();
            $partidan1->idpartida = $partidaid;
            $partidan1->nombren1 = $level[$x1]['name'];
            $partidan1->codigon1 = $level[$x1]['codigo'];
            $partidan1->activo = 1;
            $partidan1->save();   
              
            for ($x2=0; $x2<count($level[$x1]['children']); $x2++) {                                              
                $partidan2 = new Pto_partidaN2();
                $partidan2->idpartidan1 = $partidan1->idpartidan1;
                $partidan2->nombren2 = $level[$x1]['children'][$x2]['name'];
                $partidan2->codigon2 = $level[$x1]['children'][$x2]['codigo'];
                $partidan2->monto = $level[$x1]['children'][$x2]['monto'];
                $partidan2->tipo = $level[$x1]['children'][$x2]['tipo'];
                $partidan2->activo = 1;
                $partidan2->save();            
            }
        }        
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pto_Partida  $pto_Partida
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {        
        
        $partida = Pto_partida::findOrFail($request->idpartida);
        
        $partida->codigo = $request->codigo;
        $partida->nompartida = $request->nompartida;
        $partida->descripcion = $request->descripcion;
        $partida->idpei = $request->idpei;
      
        $partida->ptoinicial = $request->ptoinicial;                
        $partida->ptototal = $request->ptototal;        
        $partida->valida1 = $request->valida1;        
        $partida->valida2 = $request->valida2;        
        $partida->user = 'user';        
        $partida->activo = '1';
        $partida->save();

        //borramos los niveles para volverlos a insertar
        $nivel1 = Pto_PartidaN1::where('idpartida','=',$request->idpartida)->select('idpartidan1')->get();
        foreach ($nivel1 as $nivel) { 
            $nivel2 = Pto_PartidaN2:: where('idpartidan1','=',$nivel->idpartidan1);
            $nivel2->delete();
        }
        $nivel1 = Pto_PartidaN1::where('idpartida','=',$request->idpartida)->select('idpartidan1');
        $nivel1->delete();

        $level = $request->niveles;
        $partidaid = $request->idpartida;        
        
        for ($x1=0; $x1<count($level); $x1++) {
            $partidan1 = new Pto_partidaN1();
            $partidan1->idpartida = $partidaid;
            $partidan1->nombren1 = $level[$x1]['name'];
            $partidan1->codigon1 = $level[$x1]['codigo'];
            $partidan1->activo = 1;
            $partidan1->save();   
              
            for ($x2=0; $x2<count($level[$x1]['children']); $x2++) {                                              
                $partidan2 = new Pto_partidaN2();
                $partidan2->idpartidan1 = $partidan1->idpartidan1;
                $partidan2->nombren2 = $level[$x1]['children'][$x2]['name'];
                $partidan2->codigon2 = $level[$x1]['children'][$x2]['codigo'];
                $partidan2->monto = $level[$x1]['children'][$x2]['monto'];
                $partidan2->tipo = $level[$x1]['children'][$x2]['tipo'];
                $partidan2->activo = 1;
                $partidan2->save();            
            }
        }
    }

    public function selectgestion(Request $request){ 
        if (!$request->ajax()) return redirect('/');
        
        $buscararray = array();  
        if(!empty($request->buscar)){
            $buscararray = explode(" ",$request->buscar);
        } 
        
        if (sizeof($buscararray)>0) { 
            $sqls=''; 
            foreach($buscararray as $valor){
                if(empty($sqls)){
                    $sqls="(codigon2 like '%".$valor."%' or nombren2 like '%".$valor."%')";
                }else{
                    $sqls.=" and (codigon2 like '%".$valor."%' or nombren2 like '%".$valor."%')";
                } 
            }   
            $partidas = Pto_PartidaN2::select('idpartidan2','codigon2','nombren2')->orderBy('idpartidan2', 'desc')->whereraw($sqls)->where('activo','=',1)->get();
        }
        else {
            if ($request->id)
                $partidas = Pto_PartidaN2::select('idpartidan2','codigon2','nombren2')->orderBy('idpartidan2', 'desc')->where('idpartidan2','=',$request->id)->where('activo','=',1)->get();
            else
                $partidas = Pto_PartidaN2::select('idpartidan2','codigon2','nombren2')->orderBy('idpartidan2', 'desc')->where('activo','=',1)->get();
        }        
        return ['partidas' => $partidas];
    }

    public function partidaCuentas(Request $request)
    { 
        if (!$request->ajax()) return redirect('/'); 
        $partidan2 = Pto_PartidaN2::findOrFail($request->idpartida);
        $partidan2->idcuenta=$request->idcuenta;
        $partidan2->tipocuenta=$request->tipocuenta;
        $partidan2->save();
    }

    public function desactivar(Request $request)
    { 
        if (!$request->ajax()) return redirect('/'); 
        $partida = Pto_PartidaN2::findOrFail($request->idpartida);
        $partida->activo = '0';
        $partida->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $partida = Pto_PartidaN2::findOrFail($request->idpartida);
        $partida->activo = '1';
        $partida->save();
    }  

}