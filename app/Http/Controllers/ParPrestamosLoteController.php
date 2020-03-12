<?php

namespace App\Http\Controllers;

use App\Par_prestamos_lote;
use App\Par_Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ParPrestamosLoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

     
    public function get_lote_id(Request $request)
    {
   if (!$request->ajax()) return redirect('/');
         
         
        $lote_validator = Par_Producto::select('lote')
        ->where('idproducto','=',$request->idproducto)  
        ->get();
  $loteout=0; 
 if($lote_validator[0]->lote==1){
    $fecha=(DB::select("SELECT getfecha() as total"))[0]->total; 
    $maxlote=(DB::select("SELECT valor FROM configs WHERE codigo='LOTE'"))[0]->valor;
    $count =(DB::select("SELECT count(*) as total FROM par__prestamos__lotes WHERE activo=1"))[0]->total; 
        if($count==0){
            $lote = new Par_prestamos_lote();  
            $lote->max=$maxlote;  
            $lote->min=1;
            $lote->fecha=$fecha;
            $lote->save(); 
            $loteout=$lote->idlote;
        }else{  
                $value=(DB::select("SELECT idlote,min,max FROM par__prestamos__lotes WHERE activo=1"))[0]; 
                $new_min=$value->min+1; 
                if($new_min <= $value->max){ 
                    $lote = Par_prestamos_lote::findOrFail($value->idlote); 
                    $lote->min =$new_min;  
                    $lote->save(); 
                    $loteout=$lote->idlote; 
                }else{
                     DB::table('par__prestamos__lotes')->update(['activo' => 0]);  
                     $lote = new Par_prestamos_lote();  
                     $lote->max=$maxlote;  
                     $lote->min=1;
                     $lote->fecha=$fecha;
                     $lote->save(); 
                     $loteout=$lote->idlote;
                } 
        }
    }

     return ['id' => $loteout];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function closeLote(Request $request)
    { 
       if (!$request->ajax()) return redirect('/');
        if(((DB::select("SELECT count(*) as total FROM par__prestamos__lotes WHERE activo=1"))[0]->total)>0){   
            $value=(DB::select("SELECT idlote,min,max FROM par__prestamos__lotes WHERE activo=1"))[0]; 
                $lote = Par_prestamos_lote::findOrFail($value->idlote); 
                $lote->close =1;  
                $lote->user=Auth::id();
                $lote->save();  
             DB::table('par__prestamos__lotes')->update(['activo' => 0]);  
             $maxlote=(DB::select("SELECT valor FROM configs WHERE codigo='LOTE'"))[0]->valor; 
             $fecha=(DB::select("SELECT getfecha() as total"))[0]->total; 
             $lotenew = new Par_prestamos_lote();  
             $lotenew->max=$maxlote;  
             $lotenew->fecha=$fecha;
             $lotenew->user=Auth::id();
             $lotenew->save(); 
             
        }
    }
    public function statusLote(Request $request)
    { 
       if (!$request->ajax()) return redirect('/');
       $value=(DB::select("SELECT idlote,min,max FROM par__prestamos__lotes WHERE activo=1"))[0]; 
       return ['lote' => $value];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Par_prestamos_lote  $par_prestamos_lote
     * @return \Illuminate\Http\Response
     */
    public function show(Par_prestamos_lote $par_prestamos_lote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Par_prestamos_lote  $par_prestamos_lote
     * @return \Illuminate\Http\Response
     */
    public function edit(Par_prestamos_lote $par_prestamos_lote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Par_prestamos_lote  $par_prestamos_lote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Par_prestamos_lote $par_prestamos_lote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Par_prestamos_lote  $par_prestamos_lote
     * @return \Illuminate\Http\Response
     */
    public function destroy(Par_prestamos_lote $par_prestamos_lote)
    {
        //
    }
}
