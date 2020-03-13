<?php

namespace App\Http\Controllers;

use App\Par_productos_perfilcuenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParProductosPerfilcuentaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
 
        $fecha=(DB::select("select getfecha() as total"))[0]->total;

        $productoperfil = new Par_productos_perfilcuenta();  
        $productoperfil->idproducto = $request->idproducto; 
        $productoperfil->idperfilcuentadetalle = $request->idperfilcuentadetalle; 
        $productoperfil->idperfilcuentamaestro = $request->idperfilcuentamaestro; 
        $productoperfil->valor_abc = $request->valor_abc;  
        $productoperfil->valor_abc_php = $request->valor_abc_php;  
        $productoperfil->iscargo = $request->iscargo; 
        $productoperfil->formula = $request->formula;  
        $productoperfil->formulaphp = $request->formulaphp;  
        $productoperfil->fecharegistro = $fecha; 
        $productoperfil->activo = '1';
        $productoperfil->save(); 
    }
    public function getFormulas(Request $request)
    { 
        if (!$request->ajax()) return redirect('/'); 
        $productos_array = array();
        $productos=DB::select('select idproducto from par__productos where activo=1'); 
        foreach($productos as $valor){
         $formulascobranza = Par_productos_perfilcuenta::join('par__productos','par__productos.cobranza_perfil_ascii','=','par__productos__perfilcuentas.idperfilcuentamaestro')
        ->join('con__perfilcuentadetalles','con__perfilcuentadetalles.idperfilcuentadetalle','=','par__productos__perfilcuentas.idperfilcuentadetalle')
        ->select( 'par__productos.linea','par__productos.cobranza_perfil_ascii','par__productos__perfilcuentas.idperfilcuentadetalle',
        'par__productos__perfilcuentas.valor_abc',
        'par__productos__perfilcuentas.formula',
        'con__perfilcuentadetalles.tipocargo',
        'con__perfilcuentadetalles.idcuenta',
        'par__productos__perfilcuentas.iscargo')
        ->where('par__productos__perfilcuentas.activo','=','1')
        ->where('par__productos.idproducto','=',$valor->idproducto)
        ->get()->toArray();
        $productos_array[$valor->idproducto] =$formulascobranza;
        }
        return  $productos_array;
    }
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/'); 

       Par_productos_perfilcuenta::where('idproducto',$request->idproducto)
        ->update(['activo' => 0]);
    }

   
}
