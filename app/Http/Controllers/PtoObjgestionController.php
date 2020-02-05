<?php

namespace App\Http\Controllers;

use App\Pto_Objgestion;
use Illuminate\Http\Request;

class PtoObjgestionController extends Controller
{
    public function index(Request $request)
    { 
        //if (!$request->ajax()) return redirect('/');
            
        $idpei = $request->idpei;                              
        $objgestion = Pto_Objgestion::select('*')->where('idpei','=',$idpei)->orderBy('idobjgestion', 'desc')->paginate(10);
        
        return [
            'pagination' => [
                'total'        => $objgestion->total(),
                'current_page' => $objgestion->currentPage(),
                'per_page'     => $objgestion->perPage(),
                'last_page'    => $objgestion->lastPage(),
                'from'         => $objgestion->firstItem(),
                'to'           => $objgestion->lastItem(),
            ],
            'objgestion' => $objgestion
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
        $objgestion = new Pto_Objgestion();
        $objgestion->idpei = $request->idpei;
        $objgestion->objgestion = $request->objgestion;
        $objgestion->activo = '1';
        $objgestion->save();
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $objgestion = Pto_objgestion::findOrFail($request->idobjgestion);        
        $objgestion->objgestion = $request->objgestion;
        $objgestion->activo = '1';
        $objgestion->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $objgestion = Pto_objgestion::findOrFail($request->idobjgestion);
        $objgestion->activo = '0';
        $objgestion->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $objgestion = Pto_objgestion::findOrFail($request->idobjgestion);
        $objgestion->activo = '1';
        $objgestion->save();
    }

    public function selectObjgestion(Request $request){ 
        if (!$request->ajax()) return redirect('/');

        $objgestion = Pto_objgestion::join('pto__estructuraprogs','pto__objgestions.idestructuraprog','pto__estructuraprogs.idestructuraprog')
        ->where('pto__objgestions.idestructuraprog','=',$request->idestructuraprog)
        ->where('pto__estructuraprogs.activo','=',1)
        ->select('pto__objgestions.*')->orderBy('pto__objgestions.idestructuraprog', 'asc')->get();
        return ['objgestion' => $objgestion];
    }
}
