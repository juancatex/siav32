<?php

namespace App\Http\Controllers;

use App\Con_Factura;
use App\Con_FacturaParametro;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\AsinalssClass\FacturaClass;

class ConFacturaController extends Controller
{
    
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $factura = Con_factura::orderBy('idfactura', 'desc')->paginate(10);
        }
        else{
            $factura = Con_factura::where($criterio, 'like', '%'. $buscar . '%')->orderBy('idfactura', 'desc')->paginate(10);
        }
        
        return [
            'pagination' => [
                'total'        => $factura->total(),
                'current_page' => $factura->currentPage(),
                'per_page'     => $factura->perPage(),
                'last_page'    => $factura->lastPage(),
                'from'         => $factura->firstItem(),
                'to'           => $factura->lastItem(),
            ],
            'factura' => $factura
        ];
    }
        
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');      
        
        $validator = Validator::make($request->all(), [
            'idfactura' => 'unique:con__facturas'
        ]);         
        if ($validator->fails()) {
            return ($validator->messages()->first());
        }

        $detalle_t='';
        for ($i=0; $i<count($request->detalle); $i++) {            
            $detalle_d = $request->detalle[$i]['product_name'].'|'.$request->detalle[$i]['product_price']
            .'|'.$request->detalle[$i]['product_qty'].'|'.$request->detalle[$i]['line_total'];
            if ($i==0)
                $detalle_t = $detalle_d;
            else 
                $detalle_t = $detalle_t.','.$detalle_d; 
        } 

        if (!$request->ajax()) return redirect('/');
        $factura = new Con_factura();
        $factura->idfacturaparametro = 1;
        $factura->numerofactura = $request->numerofactura;
        $factura->codigocontrol = $request->codigocontrol;
        $factura->razonsocial = $request->razonsocial;        
        $factura->nit = $request->nit;
        $factura->detalle = $detalle_t;
        $factura->importetotal = $request->importetotal;
        $factura->importecf = $request->importetotal;        
        $factura->activo = '1';
        $factura->save();
    }

    public function update(Request $request)
    {
        
        // $facturaclass = new FacturaClass();
        // $respuesta=$facturaclass->RegistraFactura('nombre','214243','detalle',23,23);
        // exit();
        
        if (!$request->ajax()) return redirect('/'); 
        $factura = Con_factura::findOrFail($request->idfactura);

        $detalle_t='';
        for ($i=0; $i<count($request->detalle); $i++) {            
            $detalle_d = $request->detalle[$i]['product_name'].'|'.$request->detalle[$i]['product_price']
            .'|'.$request->detalle[$i]['product_qty'].'|'.$request->detalle[$i]['line_total'];
            if ($i==0)
                $detalle_t = $detalle_d;
            else 
                $detalle_t = $detalle_t.','.$detalle_d; 
        } 

        $factura->idfacturaparametro = 1;
        $factura->numerofactura = $request->numerofactura;
        $factura->codigocontrol = $request->codigocontrol;
        $factura->razonsocial = $request->razonsocial;        
        $factura->nit = $request->nit;
        $factura->detalle = $detalle_t;
        $factura->importetotal = $request->importetotal;
        $factura->importecf = $request->importetotal;        
        $factura->activo = '1';
        $factura->save();
    }

    public function dataparametro(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
                
        $facturaparametro = Con_FacturaParametro::orderBy('idfacturaparametro', 'desc')->where('activo','=','1')->paginate(10);
        
        return [
            'facturaparametro' => $facturaparametro
        ];
    }    

    public function maxfactura(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
                
        $maxfactura = Con_Factura::orderBy('idfactura', 'desc')->where('activo','=','1')->max('numerofactura');         
        return ['maxfactura' => $maxfactura];
    }   
}
