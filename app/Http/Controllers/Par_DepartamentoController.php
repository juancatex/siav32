<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Par_Departamento;

class Par_DepartamentoController extends Controller
{
    public function index(Request $request)
    {
       if (!$request->ajax()) return redirect('/');

        if($request->activo) {
            $departamentos=Par_Departamento::get();
            return ['departamentos'=>$departamentos];
        }

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if (!$buscar){
            $departamentos = Par_Departamento::orderBy('iddepartamento', 'asc')
            ->paginate(5);
        }
        else{
            $departamentos = Par_Departamento::where($criterio, 'like', '%'. $buscar . '%')
            ->orderBy('iddepartamento', 'asc')->paginate(5);
        }
        
        return [
            'pagination' => [
                'total'        => $departamentos->total(),
                'current_page' => $departamentos->currentPage(),
                'per_page'     => $departamentos->perPage(),
                'last_page'    => $departamentos->lastPage(),
                'from'         => $departamentos->firstItem(),
                'to'           => $departamentos->lastItem(),
            ],
            'departamentos' => $departamentos
        ];
    }

    public function selectDepartamento(Request $request){
        if (!$request->ajax()) return redirect('/');
        $departamentos = Par_Departamento::
        select('iddepartamento','nomdepartamento','abrvdep')
        ->where('activo','=','1')
        ->orderBy('iddepartamento', 'asc')->get();
        return ['departamentos' => $departamentos];
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
            'nomdepartamento' => 'unique:par_departamentos'
        ]);

        if ($validator->fails()) {
            //echo $validator->messages()->first(); exit();
            return ($validator->messages()->first());
       }
        
        

        $departamento = new Par_Departamento();
        
        $departamento->nomdepartamento = ucwords(strtolower($request->nomdepartamento));
        $departamento->abrvdep = strtoupper($request->abrvdep);
        $departamento->activo = '1';
        $departamento->save();
    }
  

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $iddepartamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomdepartamento' => 'unique:par_departamentos'
        ]);

        if ($validator->fails()) { 
            return ($validator->messages()->first());
       }
        
        if (!$request->ajax()) return redirect('/');

        $departamento = Par_Departamento::findOrFail($request->iddepartamento);
        $departamento->nomdepartamento = ucwords(strtolower($request->nomdepartamento));
		$departamento->abrvdep = strtoupper($request->abrvdep);
        $departamento->activo = '1';
        $departamento->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $departamento = Par_Departamento::findOrFail($request->iddepartamento);

        $departamento->activo = '0';
        $departamento->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $departamento = Par_Departamento::findOrFail($request->iddepartamento);
        $departamento->activo = '1';
        $departamento->save();
    }

    
    //para el nuevo entorno de filiales //para el nuevo entorno de filiales
    //para el nuevo entorno de filiales //para el nuevo entorno de filiales
    //para el nuevo entorno de filiales //para el nuevo entorno de filiales
    public function listaDepartamentos(Request $request)
    {
        $departamentos=Par_Departamento::orderBy('iddepartamento','asc')->get();
        return ['departamentos'=>$departamentos];
    }

}
