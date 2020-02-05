<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Con_Entidadbancaria;

class ConEntidadbancariaController extends Controller
{
    public function index(Request $request)
    {
        $entidadbancarias=Con_Entidadbancaria::orderBy('identidadbancaria', 'desc')->get();
        return ['entidadbancarias' => $entidadbancarias];
    }

    public function selectEntidadbancaria(Request $request){
        $entidadbancarias=Con_Entidadbancaria::where('activo',1)->orderBy('nomentidadbancaria')->get();
        return ['entidadbancarias' => $entidadbancarias, 'bancos'=>$entidadbancarias];
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['nomentidadbancaria' => 'unique:con__entidadbancarias']);
        if ($validator->fails()) return ($validator->messages()->first());      
        $entidadbancaria = new Con_Entidadbancaria();
        $entidadbancaria->nomentidadbancaria = $request->nomentidadbancaria;
        $entidadbancaria->siglaentidadbancaria = $request->siglaentidadbancaria;
        $entidadbancaria->activo = '1';
        $entidadbancaria->save();
    }
  
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), ['nomentidadbancaria' => 'unique:con__entidadbancarias']);
        if ($validator->fails()) return ($validator->messages()->first());
        $entidadbancaria = Con_Entidadbancaria::findOrFail($request->identidadbancaria);
        $entidadbancaria->nomentidadbancaria = $request->nomentidadbancaria;
        $entidadbancaria->siglaentidadbancaria= $request->siglaentidadbancaria;
        $entidadbancaria->activo = '1';
        $entidadbancaria->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $entidadbancaria = Con_Entidadbancaria::findOrFail($request->identidadbancaria);
        $entidadbancaria->activo = '0';
        $entidadbancaria->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $entidadbancaria = Con_Entidadbancaria::findOrFail($request->identidadbancaria);
        $entidadbancaria->activo = '1';
        $entidadbancaria->save();
    }

}
