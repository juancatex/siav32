<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Adm_User;
use Illuminate\Support\Facades\Validator;
use App\Con_Cuentanivel2;
use App\Con_Cuentanivel1;
use Illuminate\Support\Facades\DB;


class ConCuentanivel2Controller extends Controller
{
    public function index(Request $request)
    {
        $user = New Adm_User();
       //$user()->authorizeRoles(['user', 'admin']);
        $user->authorizeRoles('user');
    
        //if (!$request->ajax()) return redirect('/');


        //$cuentanivel2 = Con_Cuentanivel2::orderBy('valorn2', 'asc')
                        
        $codn1=$request->codn1;               
        $cuentanivel2 = Con_Cuentanivel2::join('con__cuentanivel1','con__cuentanivel1.codn1','=','con__cuentanivel2.codn1')
        //->where('con__cuentanivel2.activo','=','1')
        ->where('con__cuentanivel2.codn1','=',$codn1)
        ->orderBy('valorn2', 'asc')
        ->paginate(20);   

        $raw=DB::raw('max(valorn2) as maxcn2');
        $maxcn2=Con_Cuentanivel2::where('codn1',$codn1)
                                    ->select($raw)
                                    ->get()->toarray();
                                    
        
        return [
            'pagination' => [
                'total'        => $cuentanivel2->total(),
                'current_page' => $cuentanivel2->currentPage(),
                'per_page'     => $cuentanivel2->perPage(),
                'last_page'    => $cuentanivel2->lastPage(),
                'from'         => $cuentanivel2->firstItem(),
                'to'           => $cuentanivel2->lastItem(),
            ],
            'cuentasnivel2' => $cuentanivel2,
            'maxcn2'=>$maxcn2
        ];
    }
    public function selectCuentanivel2(Request $request){
        // if (!$request->ajax()) return redirect('/');
        $codn1=$request->codn1;
         $cuentanivel2 = Con_Cuentanivel2::join('con__cuentanivel1','con__cuentanivel1.codn1','=','con__cuentanivel2.codn1')
         ->where('con__cuentanivel2.activo','=','1')
         ->where('con__cuentanivel2.codn1','=',$codn1)
         ->orderBy('valorn2', 'asc')->get();
         return ['cuentasnivel2' => $cuentanivel2];
     }

     public function selectValorn2(Request $request){
        // if (!$request->ajax()) return redirect('/');
         $valorn2 = Con_Cuentanivel2::select('valorn2','nomcuentan2')
                            ->where('codn2','=',$request->codn2)
                            ->get();
         return ['valorn2' => $valorn2];
     }

     public function store(Request $request)
     {
         if (!$request->ajax()) return redirect('/');

      
             $validator = Validator::make($request->all(), [
                'con__cuentanivel2_codn1_valorn2_unique' => 'unique:con__cuentanivel2' 
                //'valorn2'     => 'unique:con__cuentanivel2'
            ]);
        
         
         $codn1=$request->codn1;
         $valorcuentanivel2=strtoupper($request->valorn2);
         $nombrecuentanivel2=ucwords(strtolower($request->nombrecuentan2));
 
         $cuentanivel2 = new Con_Cuentanivel2();
         $cuentanivel2->codn1=$codn1;
         $cuentanivel2->codn2=$codn1.$valorcuentanivel2;
         $cuentanivel2->nomcuentan2 = $nombrecuentanivel2;
         $cuentanivel2->valorn2 = $valorcuentanivel2;
         $cuentanivel2->save();
     }
 
}
