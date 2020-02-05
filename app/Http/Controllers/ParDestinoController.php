<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Par_Destino;


class ParDestinoController extends Controller
{
    public function index(Request $request){

        if (!$request->ajax()) return redirect('/');  

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $destinos = Par_Destino::join('par_fuerzas','par__destinos.idfuerza','=','par_fuerzas.idfuerza')
            ->select('par__destinos.iddestino','par__destinos.idfuerza','par__destinos.nomdestino','par_fuerzas.nomfuerza','par__destinos.activo')
            ->orderBy('par__destinos.iddestino', 'desc')->paginate(10);
        }
        else{
            $destinos = Par_Destino::join('par_fuerzas','par__destinos.idfuerza','=','par_fuerzas.idfuerza')
            ->select('par__destinos.iddestino','par__destinos.idfuerza','par_fuerzas.nomfuerza','par__destinos.nomdestino','par__destinos.activo')
            ->where('par__destinos.'.$criterio, 'like', '%'.$buscar.'%')
            ->orderBy('par__destinos.nomdestino', 'desc')->paginate(10);
            
        }
        

        return [
            'pagination' => [
                'total'        => $destinos->total(),
                'current_page' => $destinos->currentPage(),
                'per_page'     => $destinos->perPage(),
                'last_page'    => $destinos->lastPage(),
                'from'         => $destinos->firstItem(),
                'to'           => $destinos->lastItem(),
            ],
            'destinos' => $destinos
        ];
    }
    //////////////////

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $validator = Validator::make($request->all(), [
            'nomdestino'=>'unique:par__destinos'
        ]);
        
        if ($validator->fails()) {
            //echo $validator->messages()->first(); exit();
            return ($validator->messages()->first());
        }
 
        $destino = new Par_Destino();
        $destino->iddestino = $request->iddestino;
        $destino->idfuerza = $request->idfuerza;
        $destino->nomdestino = $request->nomdestino;
        $destino->activo = '1';
        $destino->save();
    }
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $validator = Validator::make($request->all(), [
            'nomdestino'=>'unique:par__destinos'
        ]);

        if ($validator->fails()) {
            //echo $validator->messages()->first(); exit();
            return ($validator->messages()->first());
       }
        

        $destino = Par_Destino::findOrFail($request->iddestino);
        $destino->iddestino = $request->iddestino;
        $destino->nomdestino = $request->nomdestino;
        $destino->activo = '1';
        $destino->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $destino = Par_Destino::findOrFail($request->iddestino);
        $destino->activo = '0';
        $destino->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $destino = Par_Destino::findOrFail($request->iddestino);
        $destino->activo = '1';
        $destino->save();
    }

    public function selectDestino(Request $request){
        if (!$request->ajax()) return redirect('/');
        $idFuerza = $request->idfuerza;  
        $destinos = Par_Destino::where('par__destinos.activo','=','1' ) 
		->where('par__destinos.idfuerza','=', $idFuerza)
        ->select('par__destinos.iddestino','par__destinos.nomdestino')
        ->orderBy('par__destinos.nomdestino', 'asc')
        ->get(); 
        return ['destinos' => $destinos];
    }


}
