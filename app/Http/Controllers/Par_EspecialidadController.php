<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Par_Especialidad;

class Par_EspecialidadController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');  //quitar y ver el objeçto json

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $especialidades = Par_Especialidad::join('par_fuerzas','par_especialidades.idfuerza','=','par_fuerzas.idfuerza')
            ->select('par_especialidades.idespecialidad','par_especialidades.idfuerza','par_especialidades.nomespecialidad','par_fuerzas.nomfuerza','par_especialidades.activo')
            ->orderBy('par_especialidades.idfuerza', 'desc')->paginate(10);
        }
        else{
            $especialidades = Par_Especialidad::join('par_fuerzas','par_especialidades.idfuerza','=','par_fuerzas.idfuerza')
            ->select('par_especialidades.idespecialidad','par_especialidades.idfuerza','par_fuerzas.nomfuerza','par_especialidades.nomespecialidad','par_especialidades.activo')
            ->where('par_especialidades.'.$criterio, 'like', '%'.$buscar.'%')
            ->orderBy('par_especialidades.idfuerza', 'desc')->paginate(10);
        }
        

        return [
            'pagination' => [
                'total'        => $especialidades->total(),
                'current_page' => $especialidades->currentPage(),
                'per_page'     => $especialidades->perPage(),
                'last_page'    => $especialidades->lastPage(),
                'from'         => $especialidades->firstItem(),
                'to'           => $especialidades->lastItem(),
            ],
            'especialidades' => $especialidades
        ];
    }
    
    public function selectEspecialidad(Request $request){
        if (!$request->ajax()) return redirect('/');
         $idFuerza = $request->idfuerza; 
        $especialidads = Par_Especialidad::where('activo','=','1')
		->where('idfuerza','=', $idFuerza)
        ->select('idespecialidad','nomespecialidad')->orderBy('nomespecialidad', 'asc')->get();
        return ['especialidads' => $especialidads];
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->name('nomespecialidad,'), [
            'nomespecialidad' => 'unique:par_especialidades',
            'idfuerza'=>'unique:par_especialidades'
        ]);
     
        if ($validator->fails()) {
            return ($validator->messages()->first());
        }

        if (!$request->ajax()) return redirect('/');

        $especialidad = new Par_Especialidad();
        $especialidad->idfuerza = $request->idfuerza;
        $especialidad->nomespecialidad = $request->nomespecialidad;
        $especialidad->activo = '1';
        $especialidad->save();
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomespecialidad' => 'unique:par_especialidades'
        ]);
     
        if ($validator->fails()) {
            //echo $validator->messages()->first(); exit();
            return ($validator->messages()->first());
        }

        if (!$request->ajax()) return redirect('/');

        $especialidad = Par_Especialidad::findOrFail($request->idespecialidad);
        $especialidad->idfuerza = $request->idfuerza;
        $especialidad->nomespecialidad = $request->nomespecialidad;
        $especialidad->activo = '1';
        $especialidad->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $especialidad = Par_Especialidad::findOrFail($request->idespecialidad);
        $especialidad->activo = '0';
        $especialidad->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $especialidad = Par_Especialidad::findOrFail($request->idespecialidad);
        $especialidad->activo = '1';
        $especialidad->save();
    }

}
