<?php

namespace App\Http\Controllers;

use App\Pto_Objestrategico;
use Illuminate\Http\Request;

class PtoObjestrategicoController extends Controller
{
    
    public function index(Request $request)
    { 
        //if (!$request->ajax()) return redirect('/');
            
        $idpei = $request->idpei;                              
        $objestrategico = Pto_Objestrategico::select('*')->where('idpei','=',$idpei)->orderBy('idobjestrategico', 'desc')->paginate(10);
        
        return [
            'pagination' => [
                'total'        => $objestrategico->total(),
                'current_page' => $objestrategico->currentPage(),
                'per_page'     => $objestrategico->perPage(),
                'last_page'    => $objestrategico->lastPage(),
                'from'         => $objestrategico->firstItem(),
                'to'           => $objestrategico->lastItem(),
            ],
            'objestrategico' => $objestrategico
        ];
    }

    public function store(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'nompei' => 'unique:daa__peis'
        // ]);         
        // if ($validator->fails()) {
        //     return ($validator->messages()->first());
        // }
        
        if (!$request->ajax()) return redirect('/');      
        $objestrategico = new Pto_Objestrategico();
        $objestrategico->idpei = $request->idpei;
        $objestrategico->objestrategico = $request->objestrategico;
        $objestrategico->activo = '1';
        $objestrategico->save();
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $objestrategico = Pto_objestrategico::findOrFail($request->idobjestrategico);        
        $objestrategico->objestrategico = $request->objestrategico;
        $objestrategico->activo = '1';
        $objestrategico->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $objestrategico = Pto_objestrategico::findOrFail($request->idobjestrategico);
        $objestrategico->activo = '0';
        $objestrategico->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $objestrategico = Pto_objestrategico::findOrFail($request->idobjestrategico);
        $objestrategico->activo = '1';
        $objestrategico->save();
    }
}
