<?php

namespace App\Http\Controllers;
use App\Adm_User;

use App\Con_Tipocomprobante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ConTipocomprobanteController extends Controller
{
    
    public function __construct() {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $user = New Adm_User();
       //$user()->authorizeRoles(['user', 'admin']);
        $user->authorizeRoles('user');
    
        //if (!$request->ajax()) return redirect('/');


        $tipocomprobantes = Con_Tipocomprobante::orderBy('idtipocomprobante', 'asc')
                        ->paginate(10)    ;
                        
                        
            
        
        return [
            'pagination' => [
                'total'        => $tipocomprobantes->total(),
                'current_page' => $tipocomprobantes->currentPage(),
                'per_page'     => $tipocomprobantes->perPage(),
                'last_page'    => $tipocomprobantes->lastPage(),
                'from'         => $tipocomprobantes->firstItem(),
                'to'           => $tipocomprobantes->lastItem(),
            ],
            'tipocomprobantes' => $tipocomprobantes
        ];
    }
    public function selectTipocomprobante(Request $request){
       // if (!$request->ajax()) return redirect('/');
        $tipocomprobantes = Con_Tipocomprobante::
        select('idtipocomprobante','nomtipocomprobante')
        ->where('activo','=','1')
        ->orderBy('idtipocomprobante', 'asc')->get();
        return ['tipocomprobantes' => $tipocomprobantes];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       // if (!$request->ajax()) return redirect('/');
        
        $validator = Validator::make($request->all(), [
            'nomtipocomprobante' => 'unique:con__tipocomprobantes'
        ]);

        if ($validator->fails()) {
            //echo $validator->messages()->first(); exit();
            return ($validator->messages()->first());
       }
        $nomtipocomprobante=ucwords(strtolower($request->nomtipocomprobante));
        $prefijo = substr($nomtipocomprobante, 0, 1);
        $tipocomprobantes = Con_Tipocomprobante::where('prefijo',$prefijo)->get()->toArray();
        //dd($tipocomprobantes);
        if($tipocomprobantes)
            $prefijo = substr($nomtipocomprobante, 0, 2);

        $tipocomprobante = new Con_Tipocomprobante();
        $tipocomprobante->nomtipocomprobante = $nomtipocomprobante; 
        $tipocomprobante->prefijo=$prefijo;
        $tipocomprobante->descripcion = $request->descripcion;
        $tipocomprobante->save();
    }
  

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $idtipocomprobante
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomtipocomprobante' => 'unique:con__tipocomprobantes'
        ]);

        if ($validator->fails()) { 
            return ($validator->messages()->first());
       }
        
       // if (!$request->ajax()) return redirect('/');

        $tipocomprobante = Con_Tipocomprobante::findOrFail($request->idtipocomprobante);
        $tipocomprobante->nomtipocomprobante = ucwords(strtolower($request->nomtipocomprobante));
		$tipocomprobante->descripcion = $request->descripcion;
        $tipocomprobante->activo = '1';
        $tipocomprobante->save();
    }

    public function desactivar(Request $request)
    {
       // if (!$request->ajax()) return redirect('/');

        $tipocomprobante = Con_Tipocomprobante::findOrFail($request->idtipocomprobante);

        $tipocomprobante->activo = '0';
        $tipocomprobante->save();
    }

    public function activar(Request $request)
    {
      //  if (!$request->ajax()) return redirect('/');

        $tipocomprobante = Con_Tipocomprobante::findOrFail($request->idtipocomprobante);
        $tipocomprobante->activo = '1';
        $tipocomprobante->save();
    }
}
