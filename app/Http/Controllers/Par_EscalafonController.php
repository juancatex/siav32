<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Par_Escalafon;

class Par_EscalafonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { //echo 'asdfasd'; exit();
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $escalafones = Par_Escalafon::orderBy('idescalafon', 'desc')->paginate(10);
        }
        else{
            $escalafones = Par_Escalafon::where($criterio, 'like', '%'. $buscar . '%')->orderBy('idescalafon', 'desc')->paginate(10);
        }
        

        return [
            'pagination' => [
                'total'        => $escalafones->total(),
                'current_page' => $escalafones->currentPage(),
                'per_page'     => $escalafones->perPage(),
                'last_page'    => $escalafones->lastPage(),
                'from'         => $escalafones->firstItem(),
                'to'           => $escalafones->lastItem(),
            ],
            'escalafones' => $escalafones
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
            'nomescalafon' => 'unique:par_escalafones'
        ]);
         
        if ($validator->fails()) {
            //echo $validator->messages()->first(); exit();
            return ($validator->messages()->first());
       }
        

        if (!$request->ajax()) return redirect('/');
        $escalafon = new Par_Escalafon();
        $escalafon->nomescalafon = $request->nomescalafon;
        $escalafon->activo = '1';
        $escalafon->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function selectEscalafon(Request $request){
        if (!$request->ajax()) return redirect('/');
 
        $escalafon = Par_Escalafon::where('activo','=','1')
        ->select('idescalafon','nomescalafon')->orderBy('nomescalafon', 'asc')->get();
        return ['escalafons' => $escalafon];
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    { //echo 'asdfasdf: '. $idescalafon; exit();

        $validator = Validator::make($request->all(), [
            'nomescalafon' => 'unique:par_escalafones'
        ]);
         
        if ($validator->fails()) {
            //echo $validator->messages()->first(); exit();
            return ($validator->messages()->first());
       }
        


        if (!$request->ajax()) return redirect('/');
        $escalafon = Par_Escalafon::findOrFail($request->idescalafon);
        $escalafon->nomescalafon = $request->nomescalafon;
        $escalafon->activo = '1';
        $escalafon->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $escalafon = Par_Escalafon::findOrFail($request->idescalafon);
        $escalafon->activo = '0';
        $escalafon->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $escalafon = Par_Escalafon::findOrFail($request->idescalafon);
        $escalafon->activo = '1';
        $escalafon->save();
    }
}
