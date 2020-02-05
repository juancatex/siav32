<?php

namespace App\Http\Controllers;

use App\Adm_Permiso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdmPermisoController extends Controller
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
        $idmodulo = $request->idmodulo;  // parametrizaciom por el emodulo
                
        if ($buscar=='' && $idmodulo !=''){
            $permisos = Adm_Permiso::join('par__ventanamodulos','adm__permisos.idventanamodulo','par__ventanamodulos.idventanamodulo')
            ->join('par__modulos','par__ventanamodulos.idmodulo','par__modulos.idmodulo')
            ->select('adm__permisos.idpermiso','par__ventanamodulos.idmodulo','adm__permisos.nompermiso','par__ventanamodulos.idventanamodulo',
                    'adm__permisos.descripcion','adm__permisos.metodo','adm__permisos.activo','par__modulos.nommodulo')
            ->where('par__modulos.idmodulo','=',$idmodulo)
            ->orderBy('idpermiso', 'desc')->paginate(10);
        }
        
        else if ($buscar!='' && $idmodulo !=''){
            $permisos = Adm_Permiso::join('par__ventanamodulos','adm__permisos.idventanamodulo','par__ventanamodulos.idventanamodulo')
            ->join('par__modulos','par__ventanamodulos.idmodulo','par__modulos.idmodulo')
            ->select('adm__permisos.idpermiso','par__ventanamodulos.idmodulo','adm__permisos.nompermiso','par__ventanamodulos.idventanamodulo',
                    'adm__permisos.descripcion','adm__permisos.metodo','adm__permisos.activo','par__modulos.nommodulo')
            ->where('par__modulos.idmodulo','=',$idmodulo)
            ->where($criterio, 'like', '%'. $buscar . '%')
            ->orderBy('idpermiso', 'desc')->paginate(10);
        }

        else{
            $permisos = Adm_Permiso::join('par__ventanamodulos','adm__permisos.idventanamodulo','par__ventanamodulos.idventanamodulo')
            ->join('par__modulos','par__ventanamodulos.idmodulo','par__modulos.idmodulo')
            ->where($criterio, 'like', '%'. $buscar . '%')
            ->select('adm__permisos.idpermiso','par__ventanamodulos.idmodulo','adm__permisos.nompermiso','par__ventanamodulos.idventanamodulo',
                    'adm__permisos.descripcion','adm__permisos.metodo','adm__permisos.activo','par__modulos.nommodulo')
            ->orderBy('idpermiso', 'desc')->paginate(10);            
        }
        
        return [
            'pagination' => [
                'total'        => $permisos->total(),
                'current_page' => $permisos->currentPage(),
                'per_page'     => $permisos->perPage(),
                'last_page'    => $permisos->lastPage(),
                'from'         => $permisos->firstItem(),
                'to'           => $permisos->lastItem(),
            ],
            'permisos' => $permisos
        ];
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
            'nompermiso' => 'unique:adm__permisos'
        ]);
         
        if ($validator->fails()) {
            return ($validator->messages()->first());
       }
        
        if (!$request->ajax()) return redirect('/');
        $permiso = new Adm_Permiso();
        $permiso->idventanamodulo = $request->idventanamodulo;
        $permiso->nompermiso = $request->nompermiso;
        $permiso->descripcion = $request->descripcion;
        $permiso->metodo = $request->metodo;
        $permiso->activo = '1';
        $permiso->save();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Adm_Permiso  $adm_Permiso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');
        // $validator = Validator::make($request->all(), [
        // 'metodo' => 'unique:adm__permisos'
        // ]);
         
        // if ($validator->fails()) {
        //     return ($validator->messages()->first());
        // }
        $permiso = Adm_Permiso::findOrFail($request->idpermiso); 
        $permiso->idventanamodulo = $request->idventanamodulo;
        $permiso->nompermiso = $request->nompermiso;
        $permiso->descripcion = $request->descripcion;
        $permiso->metodo = $request->metodo;
        $permiso->activo = '1';
        $permiso->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Adm_Permiso  $adm_Permiso
     * @return \Illuminate\Http\Response
     */
    public function destroy(Adm_Permiso $adm_Permiso)
    {
        //        
    }

    public function desactivar(Request $request)
    { 
        if (!$request->ajax()) return redirect('/');
        $permiso = Adm_Permiso::findOrFail($request->idpermiso);
        $permiso->activo = '0';
        $permiso->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $permiso = Adm_Permiso::findOrFail($request->idpermiso);
        $permiso->activo = '1';
        $permiso->save();
    }
}
