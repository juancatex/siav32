<?php

namespace App\Http\Controllers;
use App\Adm_User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Par_Fuerza;

class Par_FuerzaController extends Controller
{
     /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   
   
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request)
   {
       $user = New Adm_User();
       //$user()->authorizeRoles(['user', 'admin']);
       $user->authorizeRoles('user');
    
       if (!$request->ajax()) return redirect('/sistema');

       $buscar = $request->buscar;
       $criterio = $request->criterio;
       
       if ($buscar==''){
           $fuerzas = Par_Fuerza::orderBy('idfuerza', 'asc')->paginate(10);
       }
       else{
           $fuerzas = Par_Fuerza::where($criterio, 'like', '%'. $buscar . '%')->orderBy('idfuerza', 'asc')->paginate(10);
       }
       

       return [
           'pagination' => [
               'total'        => $fuerzas->total(),
               'current_page' => $fuerzas->currentPage(),
               'per_page'     => $fuerzas->perPage(),
               'last_page'    => $fuerzas->lastPage(),
               'from'         => $fuerzas->firstItem(),
               'to'           => $fuerzas->lastItem(),
           ],
           'fuerzas' => $fuerzas
       ];
   }

   public function selectFuerza(Request $request){
       if (!$request->ajax()) return redirect('/');

       $fuerzas = Par_Fuerza::where('activo','=','1')
       ->select('idfuerza','nomfuerza')->orderBy('nomfuerza', 'asc')->get();
       return ['fuerzas' => $fuerzas];
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
        'nomfuerza' => 'unique:par_fuerzas'
    ]);
     
    if ($validator->fails()) {
        //echo $validator->messages()->first(); exit();
        return ($validator->messages()->first());
   }
    

       if (!$request->ajax()) return redirect('/');

       $fuerza = new Par_Fuerza();
       
       $fuerza->nomfuerza = $request->nomfuerza;
       $fuerza->activo = '1';
       $fuerza->save();
   }
 

   /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $idfuerza
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request)
   {
       if (!$request->ajax()) return redirect('/');

       $fuerza = Par_Fuerza::findOrFail($request->idfuerza);
       $fuerza->nomfuerza = $request->nomfuerza;
       $fuerza->activo = '1';
       $fuerza->save();
   }

   public function desactivar(Request $request)
   {
       if (!$request->ajax()) return redirect('/');

       $fuerza = Par_Fuerza::findOrFail($request->idfuerza);

       $fuerza->activo = '0';
       $fuerza->save();
   }

   public function activar(Request $request)
   {
       if (!$request->ajax()) return redirect('/');

       $fuerza = Par_Fuerza::findOrFail($request->idfuerza);
       $fuerza->activo = '1';
       $fuerza->save();
   }
}
