<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Par_Estadocivil;

class Par_EstadocivilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $estadocivil = Par_Estadocivil::orderBy('idestadocivil', 'desc')->paginate(10);
        }
        else{
            $estadocivil = Par_Estadocivil::where($criterio, 'like', '%'. $buscar . '%')->orderBy('idestadocivil', 'desc')->paginate(10);
        }
        

        return [
            'pagination' => [
                'total'        => $estadocivil->total(),
                'current_page' => $estadocivil->currentPage(),
                'per_page'     => $estadocivil->perPage(),
                'last_page'    => $estadocivil->lastPage(),
                'from'         => $estadocivil->firstItem(),
                'to'           => $estadocivil->lastItem(),
            ],
            'estadocivil' => $estadocivil
        ];
    }

    public function selectEstadocivil(Request $request){
        if (!$request->ajax()) return redirect('/');

        $estadocivil = Par_Estadocivil::where('activo','=','1')
        ->select('idestadocivil','nomestadocivil')->orderBy('nomestadocivil', 'asc')->get();
        return ['estadocivil' => $estadocivil];
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
            'nomestadocivil' => 'unique:par_estadocivil'
        ]);
     
        if ($validator->fails()) {
            //echo $validator->messages()->first(); exit();
            return ($validator->messages()->first());
        }

        if (!$request->ajax()) return redirect('/');

        $estadocivil = new Par_Estadocivil();
        
        $estadocivil->nomestadocivil = $request->nomestadocivil;
        $estadocivil->activo = '1';
        $estadocivil->save();
    }
  

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $idestadocivil
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $estadocivil = Par_Estadocivil::findOrFail($request->idestadocivil);
        $estadocivil->nomestadocivil = $request->nomestadocivil;
        $estadocivil->activo = '1';
        $estadocivil->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $estadocivil = Par_Estadocivil::findOrFail($request->idestadocivil);

        $estadocivil->activo = '0';
        $estadocivil->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $estadocivil = Par_Estadocivil::findOrFail($request->idestadocivil);
        $estadocivil->activo = '1';
        $estadocivil->save();
    }
}
