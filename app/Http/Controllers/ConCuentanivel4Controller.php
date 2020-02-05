<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Adm_User;
use Illuminate\Support\Facades\Validator;
use App\Con_Cuentanivel4;
use App\Con_Cuentanivel3;
use Illuminate\Support\Facades\DB;

class ConCuentanivel4Controller extends Controller
{
    public function index(Request $request)
    {
        $user = New Adm_User();
       //$user()->authorizeRoles(['user', 'admin']);
        $user->authorizeRoles('user');
    
        //if (!$request->ajax()) return redirect('/');


        //$cuentanivel2 = Con_Cuentanivel2::orderBy('valorn2', 'asc')
                        
        $codn3=$request->codn3;               
        $cuentanivel4 = Con_Cuentanivel4::join('con__cuentanivel3','con__cuentanivel3.codn3','=','con__cuentanivel4.codn3')
        //->where('con__cuentanivel4.activo','=','1')
        ->where('con__cuentanivel4.codn3','=',$codn3)
        ->select('con__cuentanivel4.codn3','codn4','valorn4','nomcuentan4','con__cuentanivel4.activo')
        ->orderBy('valorn4', 'asc')
        ->paginate(20);   
        
        $raw=DB::raw('max(valorn4) as maxcn4');
        $maxcn4=Con_Cuentanivel4::where('codn3',$codn3)
                                    ->select($raw)
                                    ->get()->toarray();
        
        return [
            'pagination' => [
                'total'        => $cuentanivel4->total(),
                'current_page' => $cuentanivel4->currentPage(),
                'per_page'     => $cuentanivel4->perPage(),
                'last_page'    => $cuentanivel4->lastPage(),
                'from'         => $cuentanivel4->firstItem(),
                'to'           => $cuentanivel4->lastItem(),
            ],
            'cuentasnivel4' => $cuentanivel4,
            'maxcn4'=>$maxcn4
        ];
    }
    
    public function selectCuentanivel4(Request $request){
        // if (!$request->ajax()) return redirect('/');
        $codn3=$request->codn3;
         $cuentanivel4 = Con_Cuentanivel4::join('con__cuentanivel3','con__cuentanivel4.codn3','=','con__cuentanivel3.codn3')
         ->where('con__cuentanivel4.activo','=','1')
         ->where('con__cuentanivel4.codn3','=',$codn3)
         
         ->orderBy('valorn4', 'asc')->get();
         return ['cuentasnivel4' => $cuentanivel4];
     }
     public function selectValorn4(Request $request){
        // if (!$request->ajax()) return redirect('/');
         $valorn4 = Con_Cuentanivel4::select('valorn4','nomcuentan4')
                            ->where('codn4','=',$request->codn4)
                            ->get();
         return ['valorn4' => $valorn4];
     }
     public function store(Request $request)
     {
         if (!$request->ajax()) return redirect('/');

      
             $validator = Validator::make($request->all(), [
                'con__cuentanivel4_codn3_valorn4_unique' => 'unique:con__cuentanivel4' 
                //'valorn4'     => 'unique:con__cuentanivel4'
            ]);
        
         
         $codn3=$request->codn3;
         $valorcuentanivel4=strtoupper($request->valorn4);
         $nombrecuentanivel4=ucwords(strtolower($request->nombrecuentan4));
 
         $cuentanivel4 = new Con_Cuentanivel4();
         $cuentanivel4->codn3=$codn3;
         $cuentanivel4->codn4=$codn3.$valorcuentanivel4;
         $cuentanivel4->nomcuentan4 = $nombrecuentanivel4;
         $cuentanivel4->valorn4 = $valorcuentanivel4;
         $cuentanivel4->save();
     }
   /*  public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $cuentanivel4 =  Con_Cuentanivel4::findOrFail($request->idcuenta);

        $cuentanivel4->activo = '0';
        $cuentanivel4->save();
    } */

   /*  public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $cuentanivel4 = Con_Cuentanivel4::findOrFail($request->idcuenta);
        $cuentanivel4->activo = '1';
        $cuentanivel4->save();
    } */
    public function update(Request $request)
    {
        /*$validator = Validator::make($request->all(), [
            'nomcuentan1' => 'unique:con__cuentanivel1'
        ]);

        if ($validator->fails()) { 
            return ($validator->messages()->first());
       }
       */
        
        if (!$request->ajax()) return redirect('/');

        $cuentanivel4 = Con_Cuentanivel4::findOrFail($request->codn4);
        $cuentanivel4->nomcuentan4 = ucwords(strtolower($request->nomcuentan4));
        $cuentanivel4->valorn4 = $request->valorn4;
        
        $cuentanivel4->activo = '1';
        $cuentanivel4->save();
    }
}
