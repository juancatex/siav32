<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Adm_User;
use Illuminate\Support\Facades\Validator;
use App\Con_Cuentanivel3;
use App\Con_Cuentanivel2;
use Illuminate\Support\Facades\DB;

class ConCuentanivel3Controller extends Controller
{
    public function index(Request $request)
    {
        $user = New Adm_User();
       //$user()->authorizeRoles(['user', 'admin']);
        $user->authorizeRoles('user');
    
        //if (!$request->ajax()) return redirect('/');


        //$cuentanivel2 = Con_Cuentanivel2::orderBy('valorn2', 'asc')
                        
        $codn2=$request->codn2;               
        $cuentanivel3 = Con_Cuentanivel3::join('con__cuentanivel2','con__cuentanivel2.codn2','=','con__cuentanivel3.codn2')
        //->where('con__cuentanivel3.activo','=','1')
        ->select('con__cuentanivel3.codn2','codn3','valorn3','nomcuentan3','con__cuentanivel3.activo')
        ->where('con__cuentanivel3.codn2','=',$codn2)
        ->orderBy('valorn3', 'asc')
        ->paginate(20);  
        
        $raw=DB::raw('max(valorn3) as maxcn3');
        $maxcn3=Con_Cuentanivel3::where('codn2',$codn2)
                                    ->select($raw)
                                    ->get()->toarray();
        
        return [
            'pagination' => [
                'total'        => $cuentanivel3->total(),
                'current_page' => $cuentanivel3->currentPage(),
                'per_page'     => $cuentanivel3->perPage(),
                'last_page'    => $cuentanivel3->lastPage(),
                'from'         => $cuentanivel3->firstItem(),
                'to'           => $cuentanivel3->lastItem(),
            ],
            'cuentasnivel3' => $cuentanivel3,
            'maxcn3'=>$maxcn3
        ];
    }
    
    public function selectCuentanivel3(Request $request){
        // if (!$request->ajax()) return redirect('/');
        $codn2=$request->codn2;
         $cuentanivel3 = Con_Cuentanivel3::join('con__cuentanivel2','con__cuentanivel3.codn2','=','con__cuentanivel2.codn2')
         ->where('con__cuentanivel3.activo','=','1')
         ->where('con__cuentanivel3.codn2','=',$codn2)
         ->orderBy('valorn3', 'asc')->get();
         return ['cuentasnivel3' => $cuentanivel3];
     }
     public function selectValorn3(Request $request){
        // if (!$request->ajax()) return redirect('/');
         $valorn3 = Con_Cuentanivel3::select('valorn3','nomcuentan3')
                            ->where('codn3','=',$request->codn3)
                            ->get();
         return ['valorn3' => $valorn3];
     }

     public function store(Request $request)
     {
         if (!$request->ajax()) return redirect('/');

      
             $validator = Validator::make($request->all(), [
                'con__cuentanivel3_codn2_valorn3_unique' => 'unique:con__cuentanivel3' 
                //'valorn3'     => 'unique:con__cuentanivel3'
            ]);
        
         
         $codn2=$request->codn2;
         $valorcuentanivel3=strtoupper($request->valorn3);
         $nombrecuentanivel3=ucwords(strtolower($request->nombrecuentan3));
 
         $cuentanivel3 = new Con_Cuentanivel3();
         $cuentanivel3->codn2=$codn2;
         $cuentanivel3->codn3=$codn2.$valorcuentanivel3;
         $cuentanivel3->nomcuentan3 = $nombrecuentanivel3;
         $cuentanivel3->valorn3 = $valorcuentanivel3;
         $cuentanivel3->save();
     }
}
