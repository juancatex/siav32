<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Par_Municipio;

class Par_MunicipioController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');  //quitar y ver el objeto json

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $municipios = Par_Municipio::join('par_departamentos','par_municipios.iddepartamento','=','par_departamentos.iddepartamento')
            ->select('par_municipios.idmunicipio','par_municipios.iddepartamento','par_municipios.nommunicipio','par_departamentos.nomdepartamento','par_municipios.activo')
            ->orderBy('par_municipios.idmunicipio', 'asc')->paginate(10);
        }
        else{
            $municipios = Par_Municipio::join('par_departamentos','par_municipios.iddepartamento','=','par_departamentos.iddepartamento','par_municipios.activo')
            ->select('par_municipios.idmunicipio','par_municipios.iddepartamento','par_municipios.nommunicipio')
            ->where('par_municipios'.$criterio, 'like', '%'.$buscar.'%')
            ->orderBy('par_municipios.idmunicipio', 'desc')->paginate(10);
        }

        return [
            'pagination' => [
                'total'        => $municipios->total(),
                'current_page' => $municipios->currentPage(),
                'per_page'     => $municipios->perPage(),
                'last_page'    => $municipios->lastPage(),
                'from'         => $municipios->firstItem(),
                'to'           => $municipios->lastItem(),
            ],
            'municipios' => $municipios
        ];
    }
    
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $validator = Validator::make($request->all(), [
            'nommunicipio'=>'unique:par_municipios'
        ]);
        if ($validator->fails()) {
            return ($validator->messages()->first());
        }
        $municipio = new Par_Municipio();
        $municipio->iddepartamento = $request->iddepartamento;
        $municipio->codmunicipio= '';
        $municipio->nommunicipio = $request->nommunicipio;
        $municipio->activo = '1';
        $municipio->save();
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $validator = Validator::make($request->all(), [
            'nommunicipio'=>'unique:par_municipios'
        ]);

        if ($validator->fails()) {
            //echo $validator->messages()->first(); exit();
            return ($validator->messages()->first());
       }
        

        $municipio = Par_Municipio::findOrFail($request->idmunicipio);
        $municipio->iddepartamento = $request->iddepartamento;
        $municipio->nommunicipio = $request->nommunicipio;
        $municipio->activo = '1';
        $municipio->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $municipio = Par_Municipio::findOrFail($request->idmunicipio);
        $municipio->activo = '0';
        $municipio->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $municipio = Par_Municipio::findOrFail($request->idmunicipio);
        $municipio->activo = '1';
        $municipio->save();
    }

    public function selectMunicipio(Request $request){
        if (!$request->ajax()) return redirect('/'); 
        $municipios=Par_Municipio::select('idmunicipio','nommunicipio')
        ->where('iddepartamento','=',$request->iddepartamento)->orderBy('nommunicipio', 'asc')->get();
        return ['municipios' => $municipios];
    }

    public function listaMunicipios(Request $request)
    {
        $municipios=Par_Municipio::where('iddepartamento','=',$request->iddepartamento)
        ->orderBy('nommunicipio')->get();
        return ['municipios'=>$municipios];
    }

}
