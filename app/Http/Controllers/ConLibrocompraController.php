<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\con__librocompra;
use App\Alm_Proveedor;
use App\Con_Asientomaestro;
use Illuminate\Support\Facades\DB;
use App\Config;
use App\Con_Cierrelibrocompra;
use App\Con_Configuracion;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;

class ConLibrocompraController extends Controller
{
    public function index(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $mes=$request->mes;
        $anio=$request->anio;
        //$criterio = $request->criterio;
        $modal=$request->modal;
        if ($modal==1){
            //echo "entra modal";
           //$raw=DB::raw('format(con__librocompras.importe,2)');
            $librocompras = con__librocompra::join('alm__proveedors','alm__proveedors.idproveedor','=','con__librocompras.idproveedor')
                                            ->join('adm__users','adm__users.id','=','con__librocompras.registrado_por')
                                            ->join('fil__filials','fil__filials.idfilial','=','con__librocompras.idfilial')
                                            ->leftjoin('con__asientomaestros','con__asientomaestros.idasientomaestro','con__librocompras.idasientomaestro')
                                                ->select('con__librocompras.idlibrocompra',
                                                     'con__librocompras.numeracion',
                                                     'con__librocompras.fecha_factura',
                                                     'con__librocompras.numfactura',
                                                     'con__librocompras.importe',
                                                     'alm__proveedors.nomproveedor',
                                                     'alm__proveedors.nit',
                                                     'alm__proveedors.idproveedor',
                                                     'con__librocompras.idasientomaestro',
                                                     'validadoconta',
                                                     'lote',
                                                     'credfiscal',
                                                     'restoimporte',
                                                     'username',
                                                     'detalle_fac',
                                                     'sigla',
                                                     'con__librocompras.idfilial',
                                                     'con__asientomaestros.estado',
                                                     'subtotal',
                                                     'impnocredfiscal')
                                            ->where('con__librocompras.activo',1)
                                            //->where('con__librocompras.registrado_por',Auth::id())  solo muestra las facturas llenadas por un usuario en el moda y eso es un error debe mostrar todas
                                            ->whereMonth('con__librocompras.fecha_factura','=',$mes)
                                            ->whereYear('con__librocompras.fecha_factura','=',$anio)
                                            //->where('con__librocompras.idfilial',$request->idfilial) mostrara todas las filiales hasta que de decida lo contrario
                                            ->orderBy(DB::raw('IF(lote IS NULL,1000000,lote)'),'DESC')
                                            ->orderBy('con__librocompras.created_at','desc')
                                            ->take(50)
                                            ->get(); 
                                            //->limit(3); //SELECT * FROM `con__librocompras` ORDER BY IF(lote IS NULL,1000000,lote) DESC
            $verdad = 0;
            $cierremes = Con_Cierrelibrocompra::where('mes',$mes)
                                                ->where('anio',$anio)
                                                ->count();

            if($cierremes>0)
            $verdad=1;    
    
            

            return ['librocompras' => $librocompras,
                    'cierremes'=>$verdad];
        }
        else 
        {
            if($modal==2)
            {
                $librocompras = con__librocompra::join('alm__proveedors','alm__proveedors.idproveedor','=','con__librocompras.idproveedor')
                                                ->join('adm__users','adm__users.id','=','con__librocompras.registrado_por')
                                                ->join('fil__filials','fil__filials.idfilial','=','con__librocompras.idfilial')
                                            ->select('con__librocompras.idlibrocompra',
                                                     'con__librocompras.numeracion',
                                                     'con__librocompras.fecha_factura',
                                                     'con__librocompras.numfactura',
                                                     'con__librocompras.importe',
                                                     'alm__proveedors.nomproveedor',
                                                     'alm__proveedors.nit',
                                                     'alm__proveedors.idproveedor',
                                                     'con__librocompras.idasientomaestro',
                                                     'validadoconta',
                                                     'lote',
                                                     'credfiscal',
                                                     'restoimporte',
                                                     'username',
                                                     'detalle_fac',
                                                     'sigla',
                                                     'con__librocompras.idfilial',
                                                     'subtotal',
                                                     'impnocredfiscal')
                                            ->where('con__librocompras.activo',1)
                                            ->where('con__librocompras.validadoconta',0)
                                            ->whereNull('idasientomaestro')
                                            ->whereMonth('con__librocompras.fecha_factura','=',$mes)
                                            ->whereYear('con__librocompras.fecha_factura','=',$anio)
                                            ->where('con__librocompras.idfilial',$request->idfilial)
                                            ->orderBy('con__librocompras.created_at','desc')
                                            ->take(20)
                                            ->get(); 
                                            //->limit(3); //SELECT * FROM `con__librocompras` ORDER BY IF(lote IS NULL,1000000,lote) DESC
            $verdad = 0;
            $cierremes = Con_Cierrelibrocompra::where('mes',$mes)
                                                ->where('anio',$anio)
                                                ->count();

            if($cierremes>0)
            $verdad=1;    
    
            

            return ['librocompras' => $librocompras,
                    'cierremes'=>$verdad];
            }
            else {
                if(!$buscar)
                {
                    //echo "entra !buscar";
                    $librocompras = con__librocompra::join('alm__proveedors','alm__proveedors.idproveedor','=','con__librocompras.idproveedor')
                                                    ->join('adm__users','adm__users.id','=','con__librocompras.registrado_por')
                                                    ->join('fil__filials','fil__filials.idfilial','=','con__librocompras.idfilial')
                                                    ->leftjoin('con__asientomaestros','con__asientomaestros.idasientomaestro','=','con__librocompras.idasientomaestro')
                                                    ->select('con__librocompras.idlibrocompra',
                                                         'con__librocompras.numeracion',
                                                         'con__librocompras.fecha_factura',
                                                         'con__librocompras.numfactura',
                                                         'con__librocompras.importe',
                                                         'con__librocompras.codigocontrol',
                                                         'con__librocompras.impnocredfiscal',
                                                         'con__librocompras.descuentos',
                                                         'alm__proveedors.idproveedor',
                                                         'alm__proveedors.nomproveedor',
                                                         'alm__proveedors.nit',
                                                         'con__librocompras.idasientomaestro',
                                                         'validadoconta',
                                                         'lote',
                                                         'credfiscal',
                                                         'restoimporte',
                                                         'cod_comprobante',
                                                         'username',
                                                         'detalle_fac',
                                                         'sigla',
                                                         'con__librocompras.idfilial',
                                                         'con__asientomaestros.estado',
                                                         'subtotal' )
                                                    ->where('con__librocompras.activo',1)
                                                    ->whereMonth('con__librocompras.fecha_factura','=',$mes)
                                                    ->whereYear('con__librocompras.fecha_factura','=',$anio);
                                                    if($request->filial!=0){
                                                        $librocompras=$librocompras->where('con__librocompras.idfilial',$request->filial);
                                                    }
                                                    $librocompras=$librocompras->orderBy(DB::raw('IF(lote IS NULL,1000000,lote)'),'DESC')
                                                    ->orderBy('con__librocompras.created_at', 'desc')
                                                    ->paginate(10);
                }
                else
                {
                    $buscararray = array();  
                    if(!empty($buscar)){
                        $buscararray = explode(" ",$buscar);
                    } 
                    if (sizeof($buscararray)>0) { 
                        $sqls=''; 
                        foreach($buscararray as $valor){
                            if(empty($sqls)){
                                $sqls="(con__librocompras.numfactura like '%".$valor."%' or alm__proveedors.nomproveedor like '%".$valor."%' or alm__proveedors.nit like '%".$valor."%' or cod_comprobante like '%".$valor."%' or username like '".$valor."')";
                            }else{
                                $sqls.=" and (con__librocompras.numfactura like '%".$valor."%' or alm__proveedors.nomproveedor like '%".$valor."%' or alm__proveedors.nit like '%".$valor."%' or cod_comprobante like '%".$valor."%' or username like '".$valor."')";
                            } 
                        }   
                        //$subcuentas = Socio::select('numpapeleta',$raw,$raw2)->orderBy('numpapeleta', 'desc')->whereraw($sqls)->get();
                    
                    
                    
                        $librocompras = con__librocompra::join('alm__proveedors','alm__proveedors.idproveedor','=','con__librocompras.idproveedor')
                                                    ->join('adm__users','adm__users.id','=','con__librocompras.registrado_por')
                                                    ->leftjoin('con__asientomaestros','con__asientomaestros.idasientomaestro','=','con__librocompras.idasientomaestro')
                                                    ->select('con__librocompras.idlibrocompra',
                                                        'con__librocompras.numeracion',
                                                        'con__librocompras.fecha_factura',
                                                        'con__librocompras.numfactura',
                                                        'con__librocompras.importe',
                                                        'con__librocompras.codigocontrol',
                                                        'con__librocompras.impnocredfiscal',
                                                        'con__librocompras.descuentos',
                                                        'alm__proveedors.idproveedor',
                                                        'alm__proveedors.nomproveedor',
                                                        'alm__proveedors.nit',
                                                        'con__librocompras.idasientomaestro',
                                                        'validadoconta',
                                                        'lote',
                                                        'credfiscal',
                                                        'restoimporte',
                                                        'cod_comprobante',
                                                        'username',
                                                        'con__asientomaestros.estado',
                                                        'subtotal')
                                                    ->where('con__librocompras.activo',1)
                                                    ->whereraw($sqls)
                                                    ->whereMonth('con__librocompras.fecha_factura','=',$mes)
                                                    ->whereYear('con__librocompras.fecha_factura','=',$anio);
                                                    if($request->filial!=0){
                                                        $librocompras=$librocompras->where('con__librocompras.idfilial',$request->filial);
                                                    }
                                                    $librocompras=$librocompras->orderBy(DB::raw('IF(lote IS NULL,1000000,lote)'),'DESC')
                                                    ->orderBy('con__librocompras.created_at', 'desc')
                                                    ->paginate(10);
                    }
                } 
    
                return [
                    'pagination' => [
                        'total'        => $librocompras->total(),
                        'current_page' => $librocompras->currentPage(),
                        'per_page'     => $librocompras->perPage(),
                        'last_page'    => $librocompras->lastPage(),
                        'from'         => $librocompras->firstItem(),
                        'to'           => $librocompras->lastItem(),
                    ],
                    'librocompras' => $librocompras
                ]; 
            }
        }
    }
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        
        
        $importetotal=$request->importetotal;
        $nocreditofiscal=$request->nocreditofiscal;
        $descuentos=$request->descuentos;
        $idasientomaestro=$request->idasientomaestro;
        
        $subtotal=$importetotal-$nocreditofiscal;
        $impcrefiscal=$subtotal-$descuentos;
        //$creditofiscal=$impcrefiscal*0.13;
        $creditofiscal=$request->_13porciento;
        $restoimporte=$request->_87porciento;
        $idfilial=$request->idfilial;
        $detalle=$request->detalle;

        
        $fechafactura=$request->fechafactura;
        $fechaEntera = strtotime($fechafactura);
        $mes = date("m", $fechaEntera);
        
        $raw2=DB::raw('month(fecha_factura)');
        $librocompra = con__librocompra::select(DB::raw('max(numeracion) as maximo'))
                                            ->where($raw2,'=',$mes)
                                            ->get()->toArray();
        //dd($librocompra[0]['maximo']);
        $numeracion=$librocompra[0]['maximo']+1;
        
        //TODO:agregar un commit para evitar llenados equivocados
        $librocompras = new con__librocompra();
        $librocompras->numeracion =$numeracion;
        $librocompras->fecha_factura = $fechafactura;
        $librocompras->idproveedor=$request->idproveedor;
        $librocompras->numfactura = $request->numfactura;
        $librocompras->numautorizacion =$request->numautorizacion;
        $librocompras->importe =$importetotal;
        $librocompras->impnocredfiscal =$nocreditofiscal;
        $librocompras->subtotal=$subtotal;
        $librocompras->descuentos =$descuentos;
        $librocompras->impcredfiscal =$impcrefiscal;
        $librocompras->credfiscal=$creditofiscal;
        $librocompras->restoimporte=$restoimporte;
        $librocompras->codigocontrol = strtoupper($request->codcontrol);
        $librocompras->registrado_por=Auth::id();
        $librocompras->idfilial=$request->idfilial;
        $librocompras->detalle_fac=$detalle;
        $librocompras->save();

        //return $lote;
    }
    public function verificarfactura(Request $request){
        if (!$request->ajax()) return redirect('/');
        $idasientomaestro=$request->idasientomaestro;
        $respuesta=con__librocompra::where('idasientomaestro',$idasientomaestro)
                                    ->count();
        //dd($respuesta);
        $verdad=0;

        if($respuesta>0)
            $verdad=1;    //para el caso de ventas es 2 y para el caso de compras es 1   ,0 es sinfactura

        return $verdad;
    }
    public function update(Request $request)
    {
        /* $validator = Validator::make($request->all(), [
            'nomlibrocompra' => 'unique:par_librocompras'
        ]);

        if ($validator->fails()) { 
            return ($validator->messages()->first());
       } */
        
        if (!$request->ajax()) return redirect('/');

        $creditofiscal=$request->_13porciento;
        $restoimporte=$request->_87porciento;
        
        $importetotal=$request->importetotal;
        $nocreditofiscal=$request->nocreditofiscal;
        $descuentos=$request->descuentos;

        $subtotal=$importetotal-$nocreditofiscal;
        $impcrefiscal=$subtotal-$descuentos;
        $idfilial=$request->idfilial;
        $detalle=$request->detalle;
       //dd($request);
        $librocompra = con__librocompra::findOrFail($request->idlibrocompra);
        $librocompra->fecha_factura = $request->fechafactura;
        $librocompra->idproveedor=$request->idproveedor;
        $librocompra->numfactura=$request->numfactura;
        $librocompra->numautorizacion=$request->numautorizacion;
        $librocompra->importe=$importetotal;
        $librocompra->impnocredfiscal=$nocreditofiscal;
        $librocompra->subtotal=$subtotal;
        $librocompra->descuentos=$descuentos;
        $librocompra->impcredfiscal=$impcrefiscal;
        $librocompra->credfiscal=$creditofiscal;
        $librocompra->restoimporte=$restoimporte;
        $librocompra->codigocontrol=strtoupper($request->codcontrol);
        $librocompra->editado_por=Auth::id();
        $librocompra->idfilial=$idfilial;
        $librocompra->detalle_fac=$detalle;
        $librocompra->save();

        if($request->modificado_montos==1)
        {
            //$modificado_montos=$request->modificado_montos;
            $cuentaslibros = Con_Configuracion::select('codigo','valor')
                                    ->where(function($query){
                                        $query->where('codigo','=','LV')
                                            ->orwhere('codigo','=','LC');
                                    })
                                    ->where('activo',1)
                                    ->get()
                                    ->toArray();
        
            //dd($cuentaslibros);
            foreach ($cuentaslibros as $valor) {
                if($valor['codigo']=='LV')
                    $lv=$valor['valor'];
                else
                {
                    if($valor['codigo']=='LC')
                        $lc=$valor['valor'];
                }
            }
            $raw=DB::raw('sum(credfiscal) as suma');
            $librocompra=con__librocompra::select($raw)
                                            ->where('idasientomaestro',$request->idasientomaestro)
                                            ->get()
                                            ->toArray();
            
           //dd($librocompra);
            $totalcredfiscal=$librocompra[0]['suma'];
            
            DB::table('con__asientodetalles')->where('idasientomaestro', '=', $request->idasientomaestro)
                                            ->where('idcuenta',$lc)
                                            ->update(['monto' => $totalcredfiscal]);
            
            $asientomaestro = Con_Asientomaestro::findOrFail($request->idasientomaestro);
            $asientomaestro->fact_modificada=1;
            $asientomaestro->save();
        }
    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $librocompra = con__librocompra::findOrFail($request->idlibrocompra);

        $librocompra->activo = '3';
        $librocompra->save();
    }
}
