<?php

namespace App\Http\Controllers;

use App\Par_Moneda;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ParMonedaController extends Controller
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
            $monedas = Par_Moneda::orderBy('idmoneda', 'desc')->paginate(10);
        }
        else{
            $monedas = Par_Moneda::where($criterio, 'like', '%'. $buscar . '%')->orderBy('idmoneda', 'desc')->paginate(10);
        }
        

        return [
            'pagination' => [
                'total'        => $monedas->total(),
                'current_page' => $monedas->currentPage(),
                'per_page'     => $monedas->perPage(),
                'last_page'    => $monedas->lastPage(),
                'from'         => $monedas->firstItem(),
                'to'           => $monedas->lastItem(),
            ],
            'monedas' => $monedas
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'codmoneda' => 'unique:par__monedas'
        ]);
         
        if ($validator->fails()) {
            //echo $validator->messages()->first(); exit();
            return ($validator->messages()->first());
       }
        

        if (!$request->ajax()) return redirect('/');
        $moneda = new Par_moneda();
        $moneda->nommoneda = $request->nommoneda;
        $moneda->tipocambio = $request->tipocambio;
        $moneda->codmoneda = $request->codmoneda;
        $moneda->activo = '1';
        $moneda->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Par_Moneda  $par_Moneda
     * @return \Illuminate\Http\Response
     */
    public function show(Par_Moneda $par_Moneda)
    {
        //
    }

    public function selectmoneda(Request $request){
        if (!$request->ajax()) return redirect('/');
 
        $moneda = Par_moneda::where('activo','=','1')
        ->select('idmoneda','codmoneda','nommoneda')->orderBy('codmoneda', 'asc')->get();
        return ['monedas' => $moneda];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Par_Moneda  $par_Moneda
     * @return \Illuminate\Http\Response
     */
    public function edit(Par_Moneda $par_Moneda)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Par_Moneda  $par_Moneda
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Par_Moneda $par_Moneda)
    {
        $validator = Validator::make($request->all(), [
            'nommoneda' => 'unique:par__monedas'
        ]);
         
        if ($validator->fails()) {
            //echo $validator->messages()->first(); exit();
            return ($validator->messages()->first());
       }
        


        if (!$request->ajax()) return redirect('/');
        $moneda = Par_moneda::findOrFail($request->idmoneda);
        $moneda->nommoneda = $request->nommoneda;
        $moneda->tipocambio = $request->tipocambio;
        $moneda->codmoneda = $request->codmoneda;
        $moneda->activo = '1';
        $moneda->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Par_Moneda  $par_Moneda
     * @return \Illuminate\Http\Response
     */
    public function destroy(Par_Moneda $par_Moneda)
    {
        //
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $moneda = Par_moneda::findOrFail($request->idmoneda);
        $moneda->activo = '0';
        $moneda->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $moneda = Par_moneda::findOrFail($request->idmoneda);
        $moneda->activo = '1';
        $moneda->save();
    }
}
