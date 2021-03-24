<?php

namespace App\Http\Controllers;

use App\Par_Producto;
use App\Par_Escala;
use App\Par_Prestamos;
use App\Par_productos_perfilcuenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; 

class ParProductoController extends Controller
{ public function __construct()
    {
        $this->middleware('auth');
    }
    public function getproductos(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
            // $productos = Par_Producto:: where('par__productos.moneda','=',$request->mon)
            // ->where('activo','=','1')
            // ->orderBy('par__productos.nomproducto', 'asc')
            // ->get();
            $productos = Par_Producto::where('activo','=','1')
            ->orderBy('par__productos.nomproducto', 'asc')
            ->get();
          
         
        return ['productos' => $productos ];
    }
     
    public function getproductosid(Request $request)
    {
       if (!$request->ajax()) return redirect('/');
 
            $productos = Par_Producto::join('par__monedas','par__productos.moneda','=','par__monedas.idmoneda')
            ->join('par__productos__factores','par__productos.idfactor','=','par__productos__factores.idfactor') 
            ->select( 'par__productos.moneda','par__productos.linea','par__productos__factores.aprobacion','par__productos.idescala',
            'par__productos.garantes',
            'par__productos.max_prestamos','par__productos.lote','par__productos.activar_garante','par__productos.cancelarprestamos',
            'par__productos.idfactor','par__productos.tasa','par__productos.codproducto','par__productos.idproducto',
            'par__productos.nomproducto','par__productos.plazominimo','par__productos.plazomaximo','par__productos.activo',
            'par__monedas.codmoneda','par__monedas.idmoneda','par__monedas.tipocambio')
            ->where('par__productos.idproducto','=',$request->id)  
            ->get();
            
              
          $escala = Par_Escala::select('maxmonto','minmonto')
          ->where('idescala','=',$productos[0]->idescala) 
          ->where('minanios','<=',$request->totalmesesaporte) 
          ->where('maxanios','>=',$request->totalmesesaporte) 
          ->get();

          $validador=0;
            
          if($productos[0]->max_prestamos==1){
            $validador = Par_Prestamos::where('par__prestamos.idproducto','=',$productos[0]->idproducto)
            ->where('par__prestamos.idsocio','=',$request->idsocio) 
            ->whereBetween('par__prestamos.idestado', [1, 3])
            ->whereBetween('par__prestamos.apro_conta', [0, 1])
            ->orWhere('par__prestamos.idestado','=',10)->count();  
          } 
      
          $formulas = Par_productos_perfilcuenta::join('par__productos','par__productos.cobranza_perfil_ascii','=','par__productos__perfilcuentas.idperfilcuentamaestro')
            ->select('par__productos__perfilcuentas.idperfilcuentadetalle',
            'par__productos__perfilcuentas.valor_abc',
            'par__productos__perfilcuentas.formula',
            'par__productos.linea',
            'par__productos__perfilcuentas.iscargo')
            ->where('par__productos__perfilcuentas.activo','=','1')
            ->where('par__productos.idproducto','=',$request->id)
            ->get();
          
         
        return ['productos' => $productos,'escala' => $escala,'formulas' => $formulas ,'status'=>$validador];
    }
    public function getproductosidLista(Request $request)
    {
     if (!$request->ajax()) return redirect('/');
 
            $productos = Par_Producto::join('par__monedas','par__productos.moneda','=','par__monedas.idmoneda')
            ->join('par__productos__factores','par__productos.idfactor','=','par__productos__factores.idfactor') 
            ->select( 'par__productos.moneda','par__productos.linea','par__productos__factores.aprobacion','par__productos.idescala',
            'par__productos.garantes',
            'par__productos.max_prestamos','par__productos.lote','par__productos.activar_garante','par__productos.cancelarprestamos',
            'par__productos.idfactor','par__productos.tasa','par__productos.codproducto','par__productos.idproducto',
            'par__productos.nomproducto','par__productos.plazominimo','par__productos.plazomaximo','par__productos.activo',
            'par__monedas.codmoneda','par__monedas.idmoneda','par__monedas.tipocambio')
            ->where('par__productos.idproducto','=',$request->id)  
            ->get();
             
          $formulas = Par_productos_perfilcuenta::join('par__productos','par__productos.cobranza_perfil_ascii','=','par__productos__perfilcuentas.idperfilcuentamaestro')
            ->select('par__productos__perfilcuentas.idperfilcuentadetalle',
            'par__productos__perfilcuentas.valor_abc',
            'par__productos__perfilcuentas.formula',
            'par__productos.linea',
            'par__productos__perfilcuentas.iscargo')
            ->where('par__productos__perfilcuentas.activo','=','1')
            ->where('par__productos.idproducto','=',$request->id)
            ->get();
          
         
        return ['productos' => $productos, 'formulas' => $formulas];
    }
    public function getproductosid_tabla(Request $request)
    {
       if (!$request->ajax()) return redirect('/');
 
            $productos = Par_Producto::join('par__monedas','par__productos.moneda','=','par__monedas.idmoneda')
            ->join('par__productos__factores','par__productos.idfactor','=','par__productos__factores.idfactor') 
            ->select( 'par__productos.linea','par__productos__factores.aprobacion','par__productos.idescala','par__productos.garantes',
            'par__productos.max_prestamos','par__productos.lote','par__productos.activar_garante','par__productos.cancelarprestamos',
            'par__productos.idfactor','par__productos.tasa','par__productos.codproducto','par__productos.idproducto',
            'par__productos.nomproducto','par__productos.plazominimo','par__productos.plazomaximo','par__productos.activo',
            'par__monedas.codmoneda','par__monedas.idmoneda','par__monedas.tipocambio')
            ->where('par__productos.idproducto','=',$request->id)  
            ->get();
             
          
          $validadorcuota=0;
          
          if($productos[0]->max_prestamos==1){
            

             if($productos[0]->linea==0&&$productos[0]->cancelarprestamos==0){
                  $validadorcuota=0;
             }elseif($productos[0]->linea==1&&$productos[0]->cancelarprestamos==0){
                 $validadorcuota=1;
             }elseif($productos[0]->linea==0&&$productos[0]->cancelarprestamos==1){
                 $validadorcuota=2;
             }else{
                $validadorcuota=10;// indica error de configuracion del producto
             }
          }else{
            
            if($productos[0]->linea==0&&$productos[0]->cancelarprestamos==0){
                $validadorcuota=3;
            }else{
                $validadorcuota=10;// indica error de configuracion del producto
            }
          }
      
          
         
        return $validadorcuota;
    }
    public function getproductosperfil(Request $request)
    {
      if (!$request->ajax()) return redirect('/');
       //DB::connection()->enableQueryLog();
          $formulascobranza = Par_productos_perfilcuenta::join('par__productos','par__productos.cobranza_perfil_ascii','=','par__productos__perfilcuentas.idperfilcuentamaestro')
          ->join('con__perfilcuentadetalles','con__perfilcuentadetalles.idperfilcuentadetalle','=','par__productos__perfilcuentas.idperfilcuentadetalle')
          ->select( 'par__productos.linea','par__productos.cobranza_perfil_ascii','par__productos__perfilcuentas.idperfilcuentadetalle',
          'par__productos__perfilcuentas.valor_abc',
          'par__productos__perfilcuentas.formula',
          'con__perfilcuentadetalles.tipocargo',
          'con__perfilcuentadetalles.idcuenta',
          'par__productos__perfilcuentas.iscargo')
          ->where('par__productos__perfilcuentas.activo','=','1')
          ->where('par__productos.idproducto','=',$request->id)
          ->get();

        // $formulasdesembolso = Par_productos_perfilcuenta::join('par__productos','par__productos.desembolso_perfil','=','par__productos__perfilcuentas.idperfilcuentamaestro')
        //     ->join('con__perfilcuentadetalles','con__perfilcuentadetalles.idperfilcuentadetalle','=','par__productos__perfilcuentas.idperfilcuentadetalle')
        //     ->select( 'par__productos.linea','par__productos.desembolso_perfil','par__productos__perfilcuentas.idperfilcuentadetalle',
        //     'par__productos__perfilcuentas.valor_abc',
        //     'par__productos__perfilcuentas.formula',
        //     'con__perfilcuentadetalles.tipocargo',
        //     'con__perfilcuentadetalles.idcuenta',
        //     'par__productos__perfilcuentas.iscargo')
        //     ->where('par__productos__perfilcuentas.activo','=','1')
        //     ->where('par__productos.idproducto','=',$request->id)
        //     ->get();
        // echo dd(DB::getQueryLog());
        //printf($formulasdesembolso);
    //    return ['formulascobranza' => $formulascobranza,'formulasdesembolso' => $formulasdesembolso ];
       return ['formulascobranza' => $formulascobranza];
    }

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
        $raw2= DB::raw("par__productos.serializedmap as seriemap");
        
        if ($buscar==''){
            $productos = Par_Producto::join('par__monedas','par__productos.moneda','=','par__monedas.idmoneda')
            ->join('par__productos__factores','par__productos.idfactor','=','par__productos__factores.idfactor')
            ->select( $raw2, 'par__productos.linea','par__productos.serializedmap','par__productos.desembolso_perfil',
            'par__productos.cobranza_perfil','par__productos.idescala','par__productos.garantes','par__productos.max_prestamos',
            'par__productos.lote','par__productos.activar_garante','par__productos.cancelarprestamos','par__productos.idfactor',
            'par__productos.tasa','par__productos.codproducto','par__productos.idproducto','par__productos.nomproducto',
            'par__productos.plazominimo','par__productos.plazomaximo','par__productos.activo','par__monedas.codmoneda',
            'par__monedas.idmoneda','par__monedas.tipocambio'
            ,'par__productos.desembolso_perfil_refi'
            ,'par__productos.desembolso_perfil_garante'
            ,'par__productos.cobranza_perfil_refi'
            ,'par__productos.cobranza_perfil_garante'
            ,'par__productos.cobranza_perfil_ascii'
            ,'par__productos.cobranza_perfil_acreedor'
            ,'par__productos.perfil_cambio_estado'
            ,'par__productos.perfil_cambio_estado_mora'
            ,'par__productos.cobranza_perfil_daro')
            ->orderBy('idproducto', 'asc')->paginate(10);
        }
        else{
            $productos = Par_Producto::join('par__monedas','par__productos.moneda','=','par__monedas.idmoneda')
            ->join('par__productos__factores','par__productos.idfactor','=','par__productos__factores.idfactor')
            ->select($raw2, 'par__productos.linea','par__productos.serializedmap','par__productos.desembolso_perfil',
            'par__productos.cobranza_perfil','par__productos.idescala','par__productos.garantes','par__productos.max_prestamos',
            'par__productos.lote','par__productos.activar_garante','par__productos.cancelarprestamos','par__productos.idfactor',
            'par__productos.tasa','par__productos.codproducto','par__productos.idproducto','par__productos.nomproducto',
            'par__productos.plazominimo','par__productos.plazomaximo','par__productos.activo','par__monedas.codmoneda',
            'par__monedas.idmoneda','par__monedas.tipocambio'
            ,'par__productos.desembolso_perfil_refi'
            ,'par__productos.desembolso_perfil_garante'
            ,'par__productos.cobranza_perfil_refi'
            ,'par__productos.cobranza_perfil_garante'
            ,'par__productos.cobranza_perfil_ascii'
            ,'par__productos.cobranza_perfil_acreedor'
            ,'par__productos.perfil_cambio_estado'
            ,'par__productos.perfil_cambio_estado_mora'
            ,'par__productos.cobranza_perfil_daro')
            ->where($criterio, 'like', '%'. $buscar . '%')->orderBy('idproducto', 'asc')->paginate(10);
        }
         
        return [
            'pagination' => [
                'total'        => $productos->total(),
                'current_page' => $productos->currentPage(),
                'per_page'     => $productos->perPage(),
                'last_page'    => $productos->lastPage(),
                'from'         => $productos->firstItem(),
                'to'           => $productos->lastItem(),
            ],
            'productos' => $productos
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
            'nomproducto' => 'unique:par__productos'
        ]);
     
        if ($validator->fails()) {
            return ($validator->messages()->first());
        }

        if (!$request->ajax()) return redirect('/');
        $fecha=(DB::select("select getfecha() as total"))[0]->total;
        $producto = new Par_producto();
        
        $producto->nomproducto = $request->nomproducto;
        $producto->moneda = $request->moneda;
        $producto->codproducto = $request->codproducto;
        $producto->tasa = $request->tasa; 
        $producto->idfactor = $request->idfactor;
        $producto->idescala = $request->idescala;
        $producto->garantes = $request->garantes;
        $producto->max_prestamos = $request->max_prestamos;
        $producto->lote = $request->lote; 
        $producto->activar_garante = $request->activar_garante;
        $producto->linea = $request->linea;
        $producto->cancelarprestamos = $request->cancelarprestamos; 
        $producto->plazominimo = $request->plazominimo;
        $producto->plazomaximo = $request->plazomaximo; 

        $producto->desembolso_perfil = $request->desembolso_perfil; 
        $producto->cobranza_perfil = $request->cobranza_perfil; 

        $producto->desembolso_perfil_refi = $request->desembolso_perfil_refi; 
        $producto->desembolso_perfil_garante = $request->desembolso_perfil_garante; 
        $producto->cobranza_perfil_refi = $request->cobranza_perfil_refi; 
        $producto->cobranza_perfil_garante = $request->cobranza_perfil_garante; 

        $producto->cobranza_perfil_ascii = $request->cobranza_perfil_ascii; 
        $producto->cobranza_perfil_acreedor = $request->cobranza_perfil_acreedor; 
        $producto->cobranza_perfil_daro = $request->cobranza_perfil_daro; 
        $producto->perfil_cambio_estado = $request->perfil_cambio_estado; 
        $producto->perfil_cambio_estado_mora = $request->perfil_cambio_estado_mora; 
        $producto->serializedmap = '[]';
        $producto->fecharegistro = $fecha; 
        $producto->idusuario=Auth::id();
        $producto->save();  
        return response()->json(array('id' => $producto->idproducto), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Par_Producto  $par_Producto
     * @return \Illuminate\Http\Response
     */
    public function show(Par_Producto $par_Producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Par_Producto  $par_Producto
     * @return \Illuminate\Http\Response
     */
    public function edit(Par_Producto $par_Producto)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Par_Producto  $par_Producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    { 
        if (!$request->ajax()) return redirect('/');

        $producto = Par_Producto::findOrFail($request->idproducto);
        $producto->nomproducto = $request->nomproducto;
        $producto->moneda = $request->moneda;
        $producto->codproducto = $request->codproducto;
        $producto->tasa = $request->tasa;
        $producto->garantes = $request->garantes;
        $producto->idfactor = $request->idfactor;
        $producto->idescala = $request->idescala;
        $producto->max_prestamos = $request->max_prestamos;
        $producto->lote = $request->lote;
        $producto->activar_garante = $request->activar_garante;
        $producto->linea = $request->linea;
        $producto->cancelarprestamos = $request->cancelarprestamos;
        $producto->plazominimo = $request->plazominimo;
        $producto->plazomaximo = $request->plazomaximo;  
        $producto->desembolso_perfil = $request->desembolso_perfil; 
        $producto->cobranza_perfil = $request->cobranza_perfil; 
        
        $producto->desembolso_perfil_refi = $request->desembolso_perfil_refi; 
        $producto->desembolso_perfil_garante = $request->desembolso_perfil_garante; 
        $producto->cobranza_perfil_refi = $request->cobranza_perfil_refi; 
        $producto->cobranza_perfil_garante = $request->cobranza_perfil_garante; 

        $producto->cobranza_perfil_ascii = $request->cobranza_perfil_ascii; 
        $producto->cobranza_perfil_acreedor = $request->cobranza_perfil_acreedor; 
        $producto->cobranza_perfil_daro = $request->cobranza_perfil_daro;
        $producto->perfil_cambio_estado = $request->perfil_cambio_estado;
        $producto->perfil_cambio_estado_mora = $request->perfil_cambio_estado_mora;
        $producto->serializedmap = '[]';  
        $producto->save();
    }
    
    public function updatemap(Request $request)
    { 
        if (!$request->ajax()) return redirect('/'); 
        $producto = Par_Producto::findOrFail($request->idproducto);
        $producto->serializedmap = $request->map;  
        $producto->save();
    }
    public function desactivar(Request $request)
    { 
        if (!$request->ajax()) return redirect('/');

        $producto = Par_Producto::findOrFail($request->idproducto); 
        $producto->activo = '0';
        $producto->save();
    }
    public function activar(Request $request)
    { 
        if (!$request->ajax()) return redirect('/');

        $producto = Par_Producto::findOrFail($request->idproducto); 
        $producto->activo = '1';
        $producto->save();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Par_Producto  $par_Producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Par_Producto $par_Producto)
    {
        //
    }
}
