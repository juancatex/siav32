<?php

namespace App\Http\Controllers;

use App\Con_FacturaParametro;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ConFacturaParametroController extends Controller
{

    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $facturaparametro = Con_FacturaParametro::orderBy('idfacturaparametro', 'desc')->paginate(10);
        }
        else{
            $facturaparametro = Con_FacturaParametro::where($criterio, 'like', '%'. $buscar . '%')->orderBy('idfacturaparametro', 'desc')->paginate(10);
        }
        

        return [
            'pagination' => [
                'total'        => $facturaparametro->total(),
                'current_page' => $facturaparametro->currentPage(),
                'per_page'     => $facturaparametro->perPage(),
                'last_page'    => $facturaparametro->lastPage(),
                'from'         => $facturaparametro->firstItem(),
                'to'           => $facturaparametro->lastItem(),
            ],
            'facturaparametro' => $facturaparametro
        ];
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nit' => 'unique:con__factura_parametros'
        ]);
         
        if ($validator->fails()) {
            //echo $validator->messages()->first(); exit();
            return ($validator->messages()->first());
        }
        
        if (!$request->ajax()) return redirect('/');
        $facturaparametro = new Con_FacturaParametro();
        $facturaparametro->nombreinstitucion = $request->nombreinstitucion;
        $facturaparametro->propietario = $request->propietario;
        $facturaparametro->direccion = $request->direccion;
        $facturaparametro->telefono = $request->telefono;
        $facturaparametro->ciudad = $request->ciudad;
        $facturaparametro->actividad = $request->actividad;
        $facturaparametro->nit = $request->nit;
        $facturaparametro->numeroautorizacion = $request->numeroautorizacion;
        $facturaparametro->numerodosificacion = $request->numerodosificacion    ;
        $facturaparametro->rangofactura1 = $request->rangofactura1;
        $facturaparametro->rangofactura2 = $request->rangofactura2;
        $facturaparametro->fechalimite = $request->fechalimite;
        $facturaparametro->texto1 = $request->texto1;
        $facturaparametro->texto2= $request->texto2;        
        $facturaparametro->llave= $request->llave;        
        $facturaparametro->activo = '1';
        $facturaparametro->save();
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nit' => 'unique:con__factura_parametros'
        ]);
         
        if ($validator->fails()) {
            //echo $validator->messages()->first(); exit();
            //return ($validator->messages()->first());
        }
        
        if (!$request->ajax()) return redirect('/'); 
        $facturaparametro = Con_facturaParametro::findOrFail($request->idfacturaparametro);
        $facturaparametro->nombreinstitucion = $request->nombreinstitucion;
        $facturaparametro->propietario = $request->propietario;
        $facturaparametro->direccion = $request->direccion;
        $facturaparametro->telefono = $request->telefono;
        $facturaparametro->ciudad = $request->ciudad;
        $facturaparametro->actividad = $request->actividad;
        $facturaparametro->nit = $request->nit;
        $facturaparametro->numeroautorizacion = $request->numeroautorizacion;
        $facturaparametro->numerodosificacion = $request->numerodosificacion    ;
        $facturaparametro->rangofactura1 = $request->rangofactura1;
        $facturaparametro->rangofactura2 = $request->rangofactura2;
        $facturaparametro->fechalimite = $request->fechalimite;
        $facturaparametro->texto1 = $request->texto1;
        $facturaparametro->texto2= $request->texto2;
        $facturaparametro->llave= $request->llave;
        $facturaparametro->activo = '1';
        $facturaparametro->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $facturaparametro = Con_facturaparametro::findOrFail($request->idfacturaparametro);
        $facturaparametro->activo = '0';
        $facturaparametro->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $facturaparametro = Con_facturaparametro::findOrFail($request->idfacturaparametro);
        $facturaparametro->activo = '1';
        $facturaparametro->save();
    }

}
